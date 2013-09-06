<?php
/**
 * @file init.php
 * @dir: applications/post
 *
 */
//dilarang mengakses
if(!defined('_iEXEC')) exit;

function get_widget(){
	global $widget;
	
	$actions = $gadget = array();
	$actions[] = array(
		'title' => 'News',
		'link'  => '?admin&amp;apps=post'
	);
	$actions[] = array(
		'title' => 'Pages',
		'link'  => '?admin&amp;apps=post&amp;type=page'
	);
	$actions[] = array(
		'title' => 'Category',
		'link'  => '?admin&amp;apps=post&amp;go=category'
	);
	$actions[] = array(
		'title' => 'Comments',
		'link'  => '?admin&amp;apps=post&amp;go=comment'
	);
	
	if( $_GET['go'] != 'comment' )
	$gadget[] = array('title' => 'Waiting Approved','desc' => article_approved() );	
	
	$gadget[] = array('title' => 'Latest Comment','desc' => article_comment() );	
	
	$widget = array(
		'menu'		=> $actions,
		'gadget'	=> $gadget,
		'help_desk' => 'Memungkinkan anda menambahkan beberapa artikel maupun halaman ke website anda dengan mudah'
	);
	return;
}
add_action('the_actions_menu', 'get_widget');

function article_approved(){
	global $db, $login;
	
	$warna 	= '';
	$post 	= '<ul class="sidemenu">';
	$sql_post = $db->select( 'post' , array( 'type' => 'post', 'approved' => '0' ), 'ORDER BY date_post DESC LIMIT 30' );
	
	if( $db->num($sql_post) < 1 )
	$post .= '<div class="padding"><div id="message_no_ani">No article in comming</div></div>';
	
	while( $row_post = $db->fetch_obj( $sql_post ) ){
		$warna 	= empty ($warna) ? ' style="background:#f9f9f9"' : '';
		
		$data_user 	= array( 'user_login' => $row_post->user_login );
		$field 		= $login->data( $data_user );
		
		if( esc_sql($field->user_level) == 'user' ):
		
		$post .= '<li'.$warna.'><img src="'.get_gravatar($field->user_email).'" style="float:left; width:40px; height:40px; margin-right:5px;" class="radius">'.$row_post->title;
		$post .= '<div style="clear:both; padding-bottom:5px;"></div>';
		$post .= '<div style="float:left; width:60%;"><a href="?admin&apps=post&type=post&act=approv&pub=yes&id='.$row_post->id.'" class="button button2 on l">Setuju</a><a href="?admin&apps=post&act=del&id='.$row_post->id.'" class="button button2 r" onclick="return confirm(\'Are You sure delete this post?\')">Hapus</a></div>';
		$post .= '<div style="float:right; width:19%;"><a href="?admin=single&apps=post&go=edit&type=post&id='.$row_post->id.'&from='.$row_post->user_login .'" class="button button2">Ubah</a></div>';
		$post .= '<div style="clear:both; padding-bottom:5px;"></div></li>';
		
		endif;
	}
	$post .= '<ul>';
	
	return $post;
}

function article_comment(){
	global $db;
	
	$warna 		= '';
	$style 		= '';
	$add_query 	= '';
	
	if( $_GET['go'] == 'comment' ){ 
		$style = 'style="max-height:400px;"';
		$add_query = 'WHERE `comment_parent` !=0';
	}
	
	$comment 	= '<ul class="sidemenu" '.$style.'>';
	$sql_comment = $db->query( "SELECT * FROM $db->post_comment $add_query ORDER BY date DESC LIMIT 30" );
	
	if( $db->num($sql_comment) < 1 )
	$post .= '<div class="padding"><div id="message_no_ani">No comment</div></div>';
	
	while( $row_comment = $db->fetch_obj( $sql_comment ) ){
		$warna 	= empty ($warna) ? ' style="background:#f9f9f9"' : '';
		
		$comment .= '<li'.$warna.'><img src="'.get_gravatar($row_comment->email).'" style="float:left; width:40px; height:40px; margin-right:5px;" class="radius">'.$row_comment->author. ', ' .time_stamp($row_comment->time) . '<br>';
		$comment .= '<div style="float:left; width:60%; padding-top:4px;">';
		
		$reply = '';
		$add_color = 'green';
		$comment_content = '';
		if( $row_comment->comment_parent > 0 ){
			$reply = '&reply=1';
			$add_color = 'blue';
			$add_action = 'Jawab';
			$comment_id = $row_comment->comment_parent;
		}else{
			$reply = '';
			$add_action = 'Lihat';
			$comment_id = $row_comment->comment_id;
			$comment_content = '<a href="?admin&apps=post&go=comment&act=view&reply=1&id='.$comment_id.'" class="button button4 on m blue">Jawab</a>';	
		}
		
		$sql_post = $db->select( 'post' , array('id' => $row_comment->post_id) );
		$row_post = $db->fetch_obj( $sql_post );
		
		$comment .= '<a href="?admin&apps=post&go=comment&act=view'.$reply.'&id='.$comment_id.'" class="button button4 on l '.$add_color.'">'.$add_action.'</a>';	
		$comment .= $comment_content;		
		$comment .= '<a href="?admin&apps=post&go=comment&act=del&id='.$row_comment->comment_id.'" class="button button4 r red" onclick="return confirm(\'Are You sure delete this post?\')">Hapus</a></div>';
		$comment .= '<div style="clear:both; padding-bottom:5px;"></div>'; 
		$comment .= '<strong>'.$row_post->title.'</strong>';
		$comment .= '<p>'.$row_comment->comment.'</p></li>';
	}
	$comment .= '<ul>';
	
	return $comment;
}

if(!function_exists('update_pub_post')){
	function update_pub_post($data,$id){
		global $db; 				
		$where = compact('id');
		return $db->update('post',$data,$where);
	}
}

if(!function_exists('del_post')){
	function del_post($data){
		global $db;
		
		$id  = $data['id'];
		$row = view_post( $id );
				
		del_img_post($row['thumb'],'post/');
		$db->delete("post",$data);
	}
}

if(!function_exists('view_post')){
	function view_post($id){
		global $db;
		$q	 = $db->select("post",compact('id'));
		return $db->fetch_array($q);
	}
}

if(!function_exists('list_category')){
	function list_category($id = null ){
		global $db;
		$q		    = $db->select("post_topic");
		while($row 	= $db->fetch_array($q)){
			
			$selected = '';
			if(!empty($id) && $row['id'] == $id) $selected =  'selected="selected"';
			
			echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['topic'].'</option>'."\n";
		}
	}
}

if(!function_exists('add_post')){
	function add_post($data){
		extract($data, EXTR_SKIP);
		
		$msg = array();
		if(empty($title)) $msg[] ='<strong>ERROR</strong>: The title is empty.';
		
		if(!empty($type) && $type=='post' )
		if( empty($category) ) $msg[] ='<strong>ERROR</strong>: The category is empty.';
			
		if( empty($type) ) $msg[] ='<strong>ERROR</strong>: The type not select.';		
		if( empty($date) ) $msg[] ='<strong>ERROR</strong>: The date is empty.';
		
		if( $msg ){
			foreach($msg as $error) {
			echo '<div id="error">'.$error.'</div>';
			}
		}
		else
		{
			if( $thumb ){
				$thumb		= hash_image( $thumb );
				upload_img_post($thumb,'post/',650,120);
				$save_post 	= save_post($data);
			}
			
			if( $save_post ) {
				add_activity('post',"menambah posting baru berjudul ' $title ' ", 'post');
				echo '<div id="success"><strong>SUCCESS</strong>: Posting berhasil di tambahkan</div>';
				
				if( $_GET['type'] =='page' )
					$type = '&type=page';
				else
					$type = '';
				
				redirect( '?admin&apps=post' . $type );
			}
		}
			
	}
}

if(!function_exists('save_post')){
	function save_post($data){
		global $db,$login; 
		extract($data, EXTR_SKIP);
		
		$title 		= esc_sql($title);
		$type 		= esc_sql($type);
		$post_topic	= esc_sql($category);
		$tags 		= esc_sql($tags);
		$content	= esc_sql($isi);
		$date_post	= esc_sql($date);
		
		$meta_keys 	= esc_sql($meta_keys);
		$meta_desc 	= esc_sql($meta_desc);
		$status 	= esc_sql($status);
		
		$thumb		= hash_image( $thumb );
		$thumb 		= esc_sql($thumb['name']);
		
		$seo 		= new engine;
		$sefttitle	= esc_sql($seo->judul($title));
		$user_login	= esc_sql($login->exist_value('username'));		
		$row 		= $login->data( compact('user_login') );
		$mail		= esc_sql($row->user_email);
		
		$data = compact('user_login','title','sefttitle','post_topic','mail','type','status','content','thumb','tags','date_post','meta_keys','meta_desc');		
		return $db->insert('post',$data);
	}
}

if(!function_exists('update_category_post')){
	function update_category_post($data,$id){			
		if(update_save_category_post($data,$id)){ 
			echo '<div id="success"><strong>SUCCESS</strong>: Category Post berhasil di perbaharui</div>';
			redirect( '?admin&apps=post&go=category' );	
		}
	}
}

if(!function_exists('update_save_category_post')){
	function update_save_category_post($data,$id){
		global $db; 		
		return $db->update('post_topic',$data,compact('id'));
	}
}

if(!function_exists('del_category_post')){
	function del_category_post($data){
		global $db;
		$db->delete("post_topic",$data);
	}
}

if(!function_exists('update_post')){
	function update_post($data,$id){
		extract($data, EXTR_SKIP);
		
		$msg = array();
		if(empty($title)) 	$msg[] ='<strong>ERROR</strong>: The title is empty.';
		
		if(!empty($type) && $type=='post')
		if(empty($category) || $category == 0 )$msg[] ='<strong>ERROR</strong>: The category is empty.';
		
		if(empty($date)) 	$msg[] ='<strong>ERROR</strong>: The date is empty.';
		
		if($msg){
			foreach($msg as $error){
				echo '<div id="error">'.$error.'</div>';
			}
		}
		else
		{
			if(update_save_post($data,$id)){ 
			echo '<div id="success"><strong>SUCCESS</strong>: Posting berhasil di perbaharui</div>';
			add_activity('post',"memperbaharui post $id", 'post');
			redirect( '?' . $_SERVER['QUERY_STRING'] );
			}
		}
			
	}
}

if(!function_exists('update_save_post')){
	function update_save_post($data,$id){
		global $db,$login; 
		extract($data, EXTR_SKIP);
		
		$title 		= esc_sql($title);
		$post_topic	= esc_sql($category);
		$tags 		= esc_sql($tags);
		$content	= esc_sql($isi);
		$date_post	= esc_sql($date);
		$thumb_desc	= esc_sql($thumb_desc);		
		
		$meta_keys 	= esc_sql($meta_keys);
		$meta_desc 	= esc_sql($meta_desc);
		
		$approved 	= esc_sql($approved);
		
		$row 		= view_post( $id );
		
		if(!empty($thumb['name'])):
		$thumb	= hash_image( $thumb );
		
		del_img_post($row['thumb'],'post/');
		upload_img_post($thumb,'post/',650,120);
		
		$thumb 		= esc_sql($thumb['name']);
		else:
		$thumb		= esc_sql($row['thumb']);
		endif;
		
		$seo 		= new engine;
		$sefttitle	= esc_sql($seo->judul($title));
		$user_login = esc_sql($login->exist_value('username'));
		
		$data = compact('user_login','title','sefttitle','post_topic','content','thumb','thumb_desc','tags','date_post','meta_keys','meta_desc','approved');
		return $db->update('post',$data, compact('id') );
	}
}

if(!function_exists('add_category_post')){
	function add_category_post($data){
		extract($data, EXTR_SKIP);
		
		if(empty($title)) 	$msg ='<strong>ERROR</strong>: The title is empty.';
		
		if($msg){
			echo '<div id="error">'.$msg.'</div>';
		}else{
			if(save_category_post($data)){
				echo '<div id="success">';
				echo '<strong>SUCCESS</strong>: Category Post berhasil di tambahkan';
				echo '</div>';
			}
		}
			
	}
}

if(!function_exists('save_category_post')){
	function save_category_post($data){
		global $db; 
		
		extract($data, EXTR_SKIP);
		
		$topic 		= esc_sql($title);
		$desc 		= esc_sql($desc);
		
		$data = compact('topic','desc');
		return $db->insert('post_topic',$data);
	}
}

if(!function_exists('view_category_post')){
	function view_category_post($id){
		global $db;
		$q	 = $db->select("post_topic",compact('id'));
		return $db->fetch_array($q);
	}
}


if(!function_exists('delete_commentar')){
	function delete_commentar($id){
		global $db;
		
		$where = array('comment_id' => $id);
		
		$q = $db->select("post_comment", $where );
		$r = $db->fetch_array($q);
		
		if( $r['comment_parent'] == 0 && $r['comment_id'] == $id )
		$db->delete("post_comment", array('comment_parent' => $id) );
		
		$db->delete("post_comment", $where );
		echo '<div class="padding">';
		echo '<div id="success"><strong>SUCCESS</strong>: Commentar berhasil di hapus</div>';
		echo '</div>';
		
		if( esc_sql($_GET['act']) === 'del' )
			redirect( '?admin&apps=post&go=comment' );
		else
			redirect( '?' . $_SERVER['QUERY_STRING'] );
	}
}

if(!function_exists('set_comment_manager')){
	function set_comment_manager($data){
		global $db;
		
		extract($data, EXTR_SKIP);
		echo '<div class="padding ani_fade_out">';
			
		if(empty($comment)) $error = "Isi Komentar kosong<br />"; 
		if($error){
			echo '<div id="error">'.$error.'</div>';
		}else{						
			if( $db->insert('post_comment',$data) ) {
				echo '<div id="success">Komentar berhasil dibalas.</div>';
				redirect( '?' . $_SERVER['QUERY_STRING'] );
			}
		}
		echo '</div>';
	}
}