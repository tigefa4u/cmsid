<?php 
/**
 * @fileName: manage.php
 * @dir: admin/manage/post
 */
if(!defined('_iEXEC')) exit;
global $db, $login, $class_country, $widget;
$go 	= filter_txt($_GET['go']);
$act	= filter_txt($_GET['act']);
$type	= filter_txt($_GET['type']);
$pub	= filter_txt($_GET['pub']);
$from	= filter_txt($_GET['from']);
$id 	= filter_int($_GET['id']);
$reply 	= filter_int($_GET['reply']);
$offset	= filter_int($_GET['offset']);
?>
<link href="content/applications/post/style.css" rel="stylesheet" media="screen" type="text/css" />

<script>
/*<![CDATA[*/
$(function(){
	$('#post_meta_show').click( function(){
		$('#post_meta_show_content').slideToggle();
	});
});
/*]]>*/
</script>
<?php

echo js_redirec_list();

switch($go){
default:

ob_start();

if($type=='page'){
	$add_query	='WHERE `type`="page"';
	$type		='&type=page';
}else{
	$add_query	='WHERE `type`="post" AND `approved`=1';
	$type		= '&type=post';
}

if(!empty($act))
if($act == 'pub'){
	if ($pub == 'no') $stat =0;	
	if ($pub == 'yes') $stat =1;
	update_pub_post(array('status'=>$stat),$id);
	add_activity('post',"mengubah status post $id menjadi $stat", 'post');
}

if($act == 'approv'){
	if ($pub == 'no') $approv =0;	
	if ($pub == 'yes') $approv =1;
	update_pub_post(array('approved'=>$approv),$id);
	add_activity('post',"menyetujui status post $id menjadi $approv", 'post');
}

if($act == 'del'){    
	del_post(compact('id'));  
	redirect('?admin&apps=post' . $type);
	add_activity('post',"menghapus post $id", 'post');
}

$bt = 20;
$pg = (int) get_query_var('pg');
if(empty($pg)){
	$ps = 0;
	$pg = 1;
}
else{
	$ps = ($pg-1) * $bt;
}

$sql = $db->query( "SELECT * FROM `$db->post` $add_query ORDER BY id DESC LIMIT $ps,$bt");
$total = $db->num_query("SELECT * FROM $db->post $add_query");
?>

<table id="table" cellpadding="0" cellspacing="0">
<tr class="head">
    <td width="63%" class="depan"><strong>Title</strong></td>
    <td class="depan"><div align="center"><strong>Approved</strong></div></td>
    <td class="depan"><div align="center"><strong>Status</strong></div></td>
    <td class="depan"><div align="center"><strong>Action</strong></div></td>
  </tr>
<?php
$warna = '';
while ($data = $db->fetch_array($sql)) {
$id 	= $data['id'];
$warna 	= empty ($warna) ? ' bgcolor="#f1f6fe"' : '';

$data_user 	= array( 'user_login' => $data['user_login'] );
$field 		= $login->data( $data_user );

$approved 	= ($data['approved'] == 1) ? '<a  class="enable" title="Disable Now" href="?admin&apps=post'.$type.'&act=approv&pub=no&id='.$id.'&p='.$p.'">Enable</a>' : '<a  class="disable" title="Enable Now" href="?admin&apps=post'.$type.'&act=approv&pub=yes&id='.$id.'&p='.$p.'">Disable</a>';
$status 	= ($data['status'] == 1) ? '<a  class="enable" title="Disable Now" href="?admin&apps=post'.$type.'&act=pub&pub=no&id='.$id.'&p='.$p.'">Enable</a>' : '<a  class="disable" title="Enable Now" href="?admin&apps=post'.$type.'&act=pub&pub=yes&id='.$id.'&p='.$p.'">Disable</a>';
$link_view 	= ($data['type'] == 'page') ? "?com=page&id=$id" : "?com=post&view=item&id=$id";
?>
<tr <?php echo $warna?> class="isi">
	<td valign="top"><span title="<?php echo $data['title']?>"><?php echo $data['title']?></span></td>
	<td valign="top"><div align="center"><?php echo $approved?></div></td>
	<td valign="top"><div align="center"><?php echo $status?></div></td>
    <td valign="top">
    <div class="action">
<a href="<?php echo $link_view?>" class="view" title="view" target="_blank">view</a>
<a href="?admin=single&apps=post&go=edit<?php echo $type?>&id=<?php echo $id;?>" class="edit" title="edit">edit</a>
<a href="?admin&apps=post&act=del<?php echo $type?>&id=<?php echo $id?>" class="delete" title="delete" onclick="return confirm('Are You sure delete this post?')">delete</a>
    </div></td>
</tr>
<?php

}
?>
</table>
<?php
$paging = new Pagination();
$paging->set('urlscheme','?admin&apps=post&pg=%page%');
$paging->set('perpage',$bt);
$paging->set('page',$pg);
$paging->set('total',$total);
$paging->set('nexttext','Next');
$paging->set('prevtext','Previous');
$paging->set('focusedclass','selected');
?>
<?php

$content = ob_get_contents();
ob_end_clean();

$header_menu = '';
$header_menu.= '<a href="?admin&apps=post&go=setting" class="button button3 black" style="margin-right:2px;">Pengaturan</a>';
$header_menu.= '<a href="?admin=single&apps=post&go=add&type=post" class="button button3 l">+ Post</a><a href="?admin=single&apps=post&go=add&type=page" class="button button3 m ">+ Page</a><a href="?admin&apps=post&go=addcat" class="button button3 r">+ Topik</a>';
$footer = '<div class="left">'.$paging->display(true).'</div>';
$footer.= '<div class="right">Total Post: '.$total.' Article</div>';

add_templates_manage( $content, 'Posting Manager', $header_menu,null,null,$footer );

break;
case'add':

ob_start();
?>
<div class="padding">
<?php
if(isset($_POST['draf']) || isset($_POST['publish'])){
	$title 		= filter_txt($_POST['title']);
	
	$title 		= sanitize( $title );
	
	$category 	= filter_int($_POST['category']);
	$status_comment = filter_int($_POST['status_comment']);
	$headline 	= filter_int($_POST['headline']);
	$type 		= filter_txt($_POST['type']);
	
	if( get_option('text_editor') == 'classic' ):
	$isi	 	= nl2br2($_POST['isi']);
	else:
	$isi	 	= $_POST['isi'];
	endif;
	
	$isi 		= sanitize( $isi );
	
	$tags 		= filter_txt($_POST['tags']);
	$date 		= filter_txt($_POST['date']);
	$date		= $date .' '.date('H:i:s');
	
	$meta_keys 	= filter_txt($_POST['meta_keys']);
	$meta_desc 	= filter_txt($_POST['meta_desc']);
	$thumb	 	= $_FILES['thumb'];
	
	if(isset($_POST['draf'])) $status = 0;
	else $status = 1;
	
	$data = compact('title','category','type','isi','thumb','tags','date','status','status_comment','meta_keys','meta_desc','headline');	
	add_post($data);
}
?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><label for="title">Judul: <span class="required">*</span></label><br /><input type="text" id="title" name="title" value="" placeholder="Judul Posting" required style="width:98%;" /></td>
    </tr>
    <tr>
      <td><label for="isi">Isi:</label><?php the_editor('','idEditor', array('editor_name' => 'isi','editor_style' => 'width:550px; height:250px;') );?></td>
    </tr>
    <tr>
      <td><label for="tags">Tags *(:</label><br /><input id="tags" type="text" name="tags" value="" style="width:98%;"/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>*( : Jika type posting {page} option ini diabaikan<br /><span class="required">*</span> : Harus diisini</td>
    </tr>
  </table>
</div>
<?php
$content = ob_get_contents();
ob_end_clean();

$widget_manual = array();

if( $type != 'page' ):
$widget_manual['gadget'][] = array('title' => 'Options', 'desc' => '
<div class="padding" id="post_opt_show_content">
<label for="category">Kategori: <span class="required">*</span> </label><br />
<select id="category" name="category">
<option value="">-- Pilih --</option> 
'.list_category(0,true).'
</select><br>
<label for="thumb">Gambar *(:</label><br /><input id="thumb" type="file" name="thumb"><br>
<label for="thumb_desc">Keterangan Gambar *(:</label><br /><input id="thumb_desc" type="text" name="thumb_desc" value="" style="width:90%;"/>
<label for="status_comment">Terima Komentar:</label><br />
<select id="status_comment" name="status_comment">
<option value="0">Tidak</option> 
<option value="1">Ya</option> 
</select><br>
<label for="headline">Headline:</label><br />
<select id="headline" name="headline">
<option value="0">Tidak</option> 
<option value="1">Ya</option> 
</select>
</div>
');
endif;

$menu_widget = '<a id="post_meta_show" href="#" class="button button2">Show</a>';
$widget_manual['gadget'][] = array('title' => 'Meta', 'menu' => $menu_widget,'desc' => '
<div class="padding" id="post_meta_show_content" style="display:none;">
<label for="meta_keys">Kata kunci:</label><input type="text" id="meta_keys" name="meta_keys" value="" placeholder="Keyboard" style="width:95%;" />
<label for="meta_desc">Keterangan:</label><textarea id="meta_desc" name="meta_desc" style="width:95%; height:100px"></textarea>
</div>');
$header_menu = '<div class="header_menu_top">Date : <input id="date-picker" type="text" name="date" value="'.date('Y-m-d').'">';

$select_post = 'selected="selected"';
if( $type == 'page' ) $select_page = 'selected="selected"';

if( !$type ):
$header_menu.= '<select name="type" style="margin-left:2px;"><option value="post" '.$select_post.'>Post</option><option value="page" '.$select_page.'>Page</option></select>';
else:
$header_menu.= '<input type="hidden" name="type" value="'.$type.'">';
endif;

$header_menu.= '<input type="submit" name="publish" value="Terbitkan" class="l button on green" style="margin-left:2px;"/>';
$header_menu.= '<input type="submit" name="draf" value="Simpan di Draf" class="m button black"/>';
$header_menu.= '<input type="Reset" value="Bersihkan" class="r button"/></div>';
$header_menu.= '<a href="?admin&apps=post" class="button button3 l"><span class="icon_head back">&laquo; Back</span></a>';
$header_menu.= '<a href="?admin&apps=post&go=addcat" class="button button3 r">+ Topik</a>';

$form = 'method="post" action="" enctype="multipart/form-data"';
add_templates_manage( $content, 'Add Post', $header_menu, $widget_manual, $form );

break;
case'edit':

ob_start();
?>
<div class="padding">
<?php

$row = view_post( $id );	
if( empty($type) ) $type = 'post';

if(isset($_POST['update'])){
	$title 		= filter_txt($_POST['title']);
	$title 		= sanitize( $title );
	$category 	= filter_int($_POST['category']);
	$status_comment = filter_int($_POST['status_comment']);
	$headline 	= filter_int($_POST['headline']);
	
	if(get_option('text_editor')=='classic')
	$isi	 	= nl2br2($_POST['isi']);
	else
	$isi	 	= $_POST['isi'];
	
	$isi 		= sanitize( $isi );
	
	$tags 		= filter_txt($_POST['tags']);
	$date 		= filter_txt($_POST['date']);
	$meta_keys 	= filter_txt($_POST['meta_keys']);
	$meta_desc 	= filter_txt($_POST['meta_desc']);
	$thumb_desc = filter_txt($_POST['thumb_desc']);
	$date		= $date .' '.date('H:i:s');
	$thumb	 	= $_FILES['thumb'];	
	
	$approved 	= filter_int($_POST['approved']);
	
	$data = compact('title','type','category','status_comment','isi','thumb','thumb_desc','tags','date','meta_keys','meta_desc','approved','headline');
	update_post($data,$id); 
}

?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2"><label for="title">Judul: <span class="required">*</span></label><br /><input type="text" id="title" name="title" value="<?php echo sanitize_title($row['title'])?>" placeholder="Judul Posting" required style="width:98%;" /></td>
    </tr>
    <tr>
      <td colspan="2"><label for="isi">Isi:</label><?php the_editor( sanitize($row['content']) ,'idEditor', array('editor_name' => 'isi','editor_style' => 'width:550px; height:420px;') );?></td>
    </tr>
<?php if( $type == 'post' ):?>
    <tr>
      <td colspan="2"><label for="tags">Tags: *(</label><br /><input id="tags" type="text" name="tags" style="width:98%;" value="<?php echo $row['tags']?>"/></td>
    </tr>
    <tr>
      <td><label for="thumb">Ganti Thumbnail:</label><br /><input id="thumb" type="file" name="thumb"></td>
      <td align="right"><label for="thumb_desc">Keterangan Gambar *(:</label><input id="thumb_desc" type="text" name="thumb_desc" value="<?php echo $row['thumb_desc']?>" style="width:90%;"/></td>
      </tr>
<?php endif;?>
    <tr>
      <td></td>
      <td></td>
    </tr>
<?php if( $type == 'post' ):?>
    <tr>
      <td colspan="2">*( : Jika type posting {page} option ini diabaikan<br /><span class="required">*</span> : Harus disini</td>
    </tr>
<?php endif;?>
  </table>

</div>

<?php
$content = ob_get_contents();
ob_end_clean();

$widget_manual = array();

if( $type == 'post' ):
if( $row['status_comment'] > 0 ){
$status_comment = '<option value="0">Ya</option>';
$status_comment.= '<option value="1" selected="selected">Tidak</option>';
}else{
$status_comment = '<option value="0" selected="selected">Ya</option>';
$status_comment.= '<option value="1">Tidak</option>';
}

if( $row['headline'] > 0 ){
$headline = '<option value="0">Ya</option>';
$headline.= '<option value="1" selected="selected">Tidak</option>';
}else{
$headline = '<option value="0" selected="selected">Ya</option>';
$headline.= '<option value="1">Tidak</option>';
}

if( $row['approved'] > 0 ){
$approved = '<option value="0">Panding</option>';
$approved.= '<option value="1" selected="selected">Approved</option>';
}else{
$approved = '<option value="0" selected="selected">Panding</option>';
$approved.= '<option value="1">Approved</option>';
}

$widget_manual['gadget'][] = array('title' => 'Options', 'desc' => '
<div class="padding" id="post_opt_show_content">
<label for="category">Status: <span class="required">*</span> </label><br />
<select name="approved">'.$approved.'</select><br />
<label for="category">Kategori: <span class="required">*</span> </label><br />
<select id="category" name="category">
<option value="">-- Pilih --</option> 
'.list_category( $row['post_topic'],true ).'
</select><br>
<label for="status_comment">Terima Komentar:</label><br />
<select id="status_comment" name="status_comment">'.$status_comment.'</select><br>
<label for="headline">Headline:</label><br />
<select id="headline" name="headline">'.$headline.'</select>
</div>
');
endif;

$widget_meta = '
<div class="padding" id="post_meta_show_content" style="display:none;">
<label for="meta_keys">Kata kunci:</label><input type="text" id="meta_keys" name="meta_keys" value="'.$row['meta_keys'].'" placeholder="Keyboard" style="width:95%;" />
<label for="meta_desc">Keterangan:</label><textarea id="meta_desc" name="meta_desc" style="width:95%; height:100px">'.$row['meta_desc'].'</textarea>
</div>';
$menu_widget = '<a id="post_meta_show" href="#" class="button button2">Show</a>';

if( $type != 'page' ) $widget_metax = $widget_meta;
else $widget_metax = '<div class="padding"><p id="message_no_ani">No meta needed</p></div>';

$widget_manual['gadget'][] = array('title' => 'Meta', 'desc' => $widget_metax,'menu' => $menu_widget);

if( $type != 'page' ):
$widget_manual['gadget'][] = array('title' => 'Thumbnail', 'desc' => ' 
<center><img style="max-width:100%;" src="?request&load=libs/timthumb.php&src='.content_url('/uploads/post/'.$row['thumb']).'&w=230&h=130&zc=1"></center>');
endif;

$header_menu = '<div class="header_menu_top">';
if( $type != 'page' ):
$tanggal = strtotime($row['date_post']);
$header_menu.= 'Date : <input id="date-picker" type="text" name="date" value="'.date('Y-m-d',$tanggal).'">';
endif;

if( empty($from) ):
$header_menu.= '<input type="hidden" name="approved" value="'.$row['approved'].'">';
endif;

$header_menu.= '<input type="submit" name="update" value="Simpan" class="l button on orange" style="margin-left:2px;"/>';
$header_menu.= '<input type="Reset" value="Bersihkan" class="m button"/></div>';
if( $id != 1 ):
$header_menu.= '<a href="?admin&apps=post&act=del&id='.$id.'" class="button button3 r red" style="margin-left:-2px; margin-right:2px;" onclick="return confirm(\'Are You sure delete this post?\')">Hapus</a>';
endif;
$header_menu.= '<a href="?admin&apps=post" class="button button3 l"><span class="icon_head back">&laquo; Back</span></a>';
$header_menu.= '<a href="?admin=single&apps=post&go=add" class="button button3 m">+ Post</a><a href="?admin&apps=post&go=addcat" class="button button3 r">+ Topik</a>';

$form = 'method="post" action="" enctype="multipart/form-data"';
add_templates_manage( $content, 'Editing '.uc_first($type), $header_menu, $widget_manual, $form );

?>
<br /><br /><br /><br /><br /><br />
<?php

break;
case'category':
ob_start();

if(!empty($act))
if($act == 'pub'){
	if ($pub == 'no') $stat =0;	
	if ($pub == 'yes') $stat =1;
	update_category_post(array('status'=>$stat),$id);
	add_activity('post',"mengubah status ketegory post $id", 'post');
}
if($act == 'del'){    
	del_category_post(compact('id'));  
	add_activity('post',"menghapus kategory post $id", 'post');
}
?>
<table id=table cellpadding="0" cellspacing="0">
<tr class="head">
    <td width="65%" class="depan"><strong>Title</strong></td>
    <td class="depan"><div align="center"><strong>Total Post</strong></div></td>
    <td class="depan"><div align="center"><strong>Status</strong></div></td>
    <td colspan="2"><div align="center"><strong>Action</strong></div></td>
</tr>
<?php
$limit		= 10;
$sql		= $db->query( "SELECT * FROM `$db->post_topic` ORDER BY id DESC LIMIT $limit");
$warna 		= '';
while($data = $db->fetch_array($sql)) {
$id 		= $data['id'];
$title 		= sanitize( $data['topic'] );
$q2			= $db->query("SELECT COUNT(*) AS jumNews FROM `$db->post` WHERE post_topic='$id'");
$data2     	= $db->fetch_array($q2);
$jumNews 	= $data2['jumNews'];
$warna 		= empty ($warna) ? ' bgcolor="#f1f6fe"' : '';
$status = ($data['status'] == 1) ? '<a  class="enable" title="Disable Now" href="?admin&apps=post&go=category&act=pub&pub=no&id='.$id.'">Enable</a>' : '<a  class="disable" title="Enable Now" href="?admin&apps=post&go=category&act=pub&pub=yes&id='.$id.'">Disable</a>';

?>
<tr <?php echo $warna?> class="isi">
	<td><?php echo $title?></td>
	<td><div align="center">( <?php echo $jumNews?> )</div></td>
	<td><div align="center"><?php echo $status?></div></td>
    <td>
    <div class="action">
<a href="?admin&apps=post&go=editcat<?php echo $type?>&id=<?php echo $id?>" class="edit" title="edit">edit</a>
<a href="?admin&apps=post&go=category&act=del&id=<?php echo $id?>" class="delete" title="delete" onclick="return confirm('Are You sure delete this category post?')">delete</a>
    </div></td>
</tr>
<?php
}
?>
</table>
<?php
$content = ob_get_contents();
ob_end_clean();

$header_title = 'Category Post Manager';
$header_menu = '<a href="?admin&apps=post&go=addcat" class="button button3">+ Topik</a>';

add_templates_manage( $content, $header_title, $header_menu );


break;
case'addcat':

ob_start();
?>
<div class="padding">
<?php
if(isset($_POST['submit'])){
	$title	= filter_txt($_POST['title']);	
	$desc	= filter_txt($_POST['desc']);
	
	$title	= sanitize($title);
	$desc	= sanitize($desc);
	
	$data = compact('title','desc');
	add_category_post($data);
	add_activity('post',"menambah kategory post dengan judul ' $title ' ", 'post');
}
?>
  <table width="100%" border="0" cellpadding="0" cellspacing="2">
    <tr>
      <td>Title</td>
    </tr>
	<tr>
      <td width="84%"><input type="text" name="title" style="width:400px;"></td>
    </tr>
    <tr>
      <td>Description</td>
    </tr>
    <tr>
      <td><textarea type="text" name="desc" style="width:90%; height:60px;"></textarea></td>
    </tr>
</table>

</div>
<?php
$content = ob_get_contents();
ob_end_clean();


$header_title = 'Add Category Post';
$form = 'action="" method="post" enctype="multipart/form-data" name="form1"';
$header_menu = '<div class="header_menu_top"><input type="submit" name="submit" value="Tambahkan" class="l button on"/>';
$header_menu.= '<input type="Reset" value="Bersihkan" class="r button"/></div>';
$header_menu.= '<a href="?admin&apps=post&go=category" class="button button3"><span class="icon_head back">&laquo; Back</span></a>';
add_templates_manage( $content, $header_title, $header_menu, null, $form );

break;
case'editcat':
 
ob_start();
?>
<div class="padding">
<?php
$row = view_category_post( $id );
if(isset($_POST['update'])){
	$title	= filter_txt($_POST['title']);
	$desc	= filter_txt($_POST['desc']);	
	
	$title	= sanitize($title);
	$desc	= sanitize($desc);
		
	if(empty($title)) 	$msg ='<strong>ERROR</strong>: The title is empty.';
		
	if($msg){
		echo '<div id="error">'.$msg.'</div>';
	}else{				
		$topic 		= esc_sql($title);
		$desc 		= esc_sql($desc);
		
		$data = compact('topic','desc');
		update_category_post($data,$id);
		add_activity('post',"mengubah kategory post dengan judul ' $row[topic] ' menjadi ' $title ' ", 'post');
	}
}
?>
  <table width="100%" border="0" cellpadding="0" cellspacing="2">
    <tr>
      <td>Title</td>
    </tr>
	<tr>
      <td width="84%"><input type="text" name="title" style="width:400px;" value="<?php echo sanitize( $row['topic'] )?>"></td>
    </tr>
    <tr>
      <td>Description</td>
    </tr>
    <tr>
      <td><textarea type="text" name="desc" style="width:90%; height:60px;"><?php echo sanitize( $row['desc'] )?></textarea></td>
    </tr>
    
</table>

</div>
<?php
$content = ob_get_contents();
ob_end_clean();

$header_title = 'Editing Category Post';
$form = 'action="" method="post" enctype="multipart/form-data" name="form1"';
$header_menu = '<div class="header_menu_top"><input type="submit" name="update" value="Perbaharui" class="l button on"/>';
$header_menu.= '<input type="Reset" value="Bersihkan" class="r button"/></div>';
$header_menu.= '<a href="?admin&apps=post&go=category" class="button button3"><span class="icon_head back">&laquo; Back</span></a>';
add_templates_manage( $content, 'Editing Category Post', $header_menu, null, $form );

break;
case'comment':
?>
<link href="content/applications/post/style-comment.css" rel="stylesheet" media="screen" type="text/css" />
<?php

ob_start();

if( $act == 'del' && !empty($id) ){
	delete_commentar($id); 
	add_activity('post_comment',"menghapus commentar $id", 'comment');
}

if( isset($_POST['submitDelete']) ) {
	$commentar_id = (array) $_POST['commentar_id'];
		
	foreach($commentar_id as $key){
		delete_commentar($key); 
		add_activity('post_comment',"menghapus commentar $key", 'comment');
	}
}

if( isset($_POST['submitApproved']) ) {
	$commentar_id = (array) $_POST['commentar_id'];
		
	foreach($commentar_id as $key){
		approved_commentar($key); 
		add_activity('post_comment',"menyetujui commentar $key", 'comment');
	}
}

if( $act == 'wait' ){
	
$warna = '';
$checkbox_id 	= 0;
$sql_comment = $db->select( 'post_comment' , array('approved' => 0), 'ORDER BY date DESC LIMIT 30' );
?>
<div id="list-comment">
<table id=table cellpadding="0" cellspacing="0" widtd="100%">
    <tr class="head" style="border-bottom:0;">
		<td style="text-align:left; width:1%; vertical-align:middle; padding-left:5px"><input type="checkbox" onClick="checkbox_all()" id="set_checkbox_all"></td>
		<td style="text-align:left; width:25%">By</td>
		<td style="text-align:left">Comment</td>
	</tr>
<?php
while( $row_comment = $db->fetch_obj( $sql_comment ) ){
$warna 	= empty ($warna) ? ' style="background:#f9f9f9"' : '';

$sql_post = $db->select( 'post' , array('id' => $row_comment->post_id) );
$row_post = $db->fetch_obj( $sql_post );
?>
	<tr class="isi" <?php echo $warna;?>>
	  <td style="text-align:left; vertical-align:middle; padding-left:5px"><input type="checkbox" name="commentar_id[]" value="<?php echo $row_comment->comment_id?>" id="checkbox_id_<?php echo $checkbox_id;?>" /></td>
	  <td style="text-align:left;">
      <div style="height:auto; margin:2px; margin-right:5px; padding:2px; padding-top:10px;">
      <img src="<?php echo get_gravatar($row_comment->email);?>" style="width:40px; height:40px; border:1px solid #ddd;" class="radius">
      </div>  
      <?php echo $row_comment->author?>  
      </td>
	  <td>
      <strong><?php echo $row_post->title?></strong>
      <p><?php echo $row_comment->comment?></p>
      </td>
    </tr>
<?php
$checkbox_id++;
}
?>
</table>
<input type="hidden" id="checkbox_total" value="<?php echo $checkbox_id?>" name="checkbox_total">
</div>

<?php

$header_title = 'Approved Comment Manager';
$header_menu = '';
$header_menu.= '<div class="header_menu_top">';
$header_menu.= '<input type="submit" name="submitApproved" class="primary button on green" value="Approve the selected" id="checkbox_go">';
$header_menu.= '</div>';
$header_menu.= '<a href="?admin&apps=post&go=comment" class="button button3"><span class="icon_head back">&laquo; Back</span></a>';
	
}elseif( $act == 'view' ){

$color			= '';
$comment_no		= 0;
$qry_comment	= $db->select("post_comment",array('comment_id'=>$id,'comment_parent'=>0,'approved'=>1)); 
$data			= $db->fetch_array($qry_comment);
	
$no_comment 	= filter_int( $data['comment_id'] );
$no_respon  	= filter_int( $comment_no++ );
$reply_link		= '';

$sql_post = $db->select( 'post' , array('id' => $data['post_id']) );
$row_post = $db->fetch_obj( $sql_post );

?>
<div class="comment_post_title">
<strong>Title : <?php echo sanitize( $row_post->title )?></strong>
</div>
<?php
if( isset($_POST['submit']) ){
	$comment = nl2br2($_POST['comment']);
	$comment = sanitize( $comment );
	
	$user_login = $login->exist_value('username');
	$field 		= $login->data( compact('user_login') );
		
	$user_id	= $field->user_login;
	$email 		= $field->user_email;
	$author		= $field->user_author;
	
	$approved	= 1;
	
	$comment_parent = $data['comment_id'];
	$post_id    = $data['post_id'];
	$date    	= date('Y-m-d H:i:s');	
	$time		= time();
	
	$reply_data = compact('user_id','author','email','comment','date','time','approved','comment_parent','post_id');
	set_comment_manager($reply_data);
	add_activity('post_comment',"menambah commentar pada $row_post->title", 'comment');
}
?>
<div id="list-comment">
<div class="comment-wrap" id="respon-<?php echo $no_respon;?>">
<div class="comment-img" style="margin-left:5px; margin-top:5px; margin-right:10px"><img src="<?php echo get_gravatar($data['email']);?>" class="radius" /></div>
<div class="comment-text">
<strong><?php echo $data['author'];?></strong> <?php echo $data['comment'];?>
<div class="comment-time"><?php echo time_stamp($data['time']);?></div> 
<br style="clear:both" />
<!--comment reply admin -->
<?php if( $reply == 1 ):?>
<div class="comment-reply-bg" id="respon">
<div class="comment-img comment-reply-img" style="margin-left:15px;"><img src="<?php echo get_gravatar( get_option('admin_email') );?>" /></div>
<div class="comment-text comment-reply-text">
<textarea cols="50" rows="5" name="comment" class="grow" style="height:15px; width:95%"></textarea><br />
<input type="submit" name="submit" value="Kirim" class="l button blue" /><input type="reset" name="Reset" value="Bersihkan" class="r button white" />
</div>
</div>
<?php endif;?>

<!--comment reply-->
<?php

$color = '';
$checkbox_id = 0;
$q2					= $db->select("post_comment",array('comment_parent'=>$id,'approved'=>1),'ORDER BY date DESC LIMIT 30'); 
while ($data2		= $db->fetch_array($q2)) {
$color 	= empty ($color) ? ' style="background:#fff"' : '';
?>
<div class="comment-reply-bg" id="respon-<?php echo $checkbox_id;?>"<?php echo $color;?>>
<input type="checkbox" name="commentar_id[]" value="<?php echo $data2['comment_id']?>" id="checkbox_id_<?php echo $checkbox_id;?>" />
<div class="comment-img comment-reply-img"><img src="<?php echo get_gravatar($data2['email']);?>" class="radius" /></div>
<div class="comment-text comment-reply-text">
<strong><?php echo $data2['author'];?></strong> <?php echo $data2['comment'];?>
<div class="comment-time comment-reply-time"><?php echo time_stamp($data2['time']);?></div> 
</div>
</div>
<?php
$checkbox_id++;
}
?>
<input type="hidden" id="checkbox_total" value="<?php echo $checkbox_id?>" name="checkbox_total">
</div>
</div>
</div>
<?php

$header_title = 'Reply Comment Manager';
$header_menu = '';

$header_menu.= '<div class="header_menu_top">';
$header_menu.= '<label for="set_checkbox_all">Check All</label> ';
$header_menu.= '<input type="checkbox" onClick="checkbox_all()" id="set_checkbox_all" style="margin-top:8px;">';
$header_menu.= '<input type="submit" name="submitDelete" class="primary button on red" value="Delete the selected" id="checkbox_go">';
$header_menu.= '</div>';

if( $reply == 1 )
$header_menu.= '<a href="?admin&apps=post&go=comment&act=view&id='.$id.'" class="button button3">Cencel</a>';
else
$header_menu.= '<a href="?admin&apps=post&go=comment&act=view&reply=1&id='.$id.'" class="button button3 blue">Reply</a>';

$header_menu.= '<a href="?admin&apps=post&go=comment" class="button button3" style="margin-left:2px;"><span class="icon_head back">&laquo; Back</span></a>';

}else{

$warna = '';
$checkbox_id 	= 0;
$sql_comment = $db->select( 'post_comment' , array('comment_parent' => 0,'approved'=>1), 'ORDER BY date DESC LIMIT 30' );
?>
<div id="list-comment">
<table id=table cellpadding="0" cellspacing="0" widtd="100%">
    <tr class="head" style="border-bottom:0;">
		<td style="text-align:left; width:1%; vertical-align:middle; padding-left:5px"><input type="checkbox" onClick="checkbox_all()" id="set_checkbox_all"></td>
		<td style="text-align:left; width:25%">By</td>
		<td style="text-align:left">Comment</td>
	</tr>
<?php
while( $row_comment = $db->fetch_obj( $sql_comment ) ){
$warna 	= empty ($warna) ? ' style="background:#f9f9f9"' : '';

$sql_post = $db->select( 'post' , array('id' => $row_comment->post_id) );
$row_post = $db->fetch_obj( $sql_post );
?>
	<tr class="isi" <?php echo $warna;?>>
	  <td style="text-align:left; vertical-align:middle; padding-left:5px"><input type="checkbox" name="commentar_id[]" value="<?php echo $row_comment->comment_id?>" id="checkbox_id_<?php echo $checkbox_id;?>" /></td>
	  <td style="text-align:left;">
      <div style="height:auto; margin:2px; margin-right:5px; padding:2px; padding-top:10px;">
      <img src="<?php echo get_gravatar($row_comment->email);?>" style="width:40px; height:40px; border:1px solid #ddd;" class="radius">
      </div>  
      <?php echo $row_comment->author?>  
      </td>
	  <td>
      <strong><?php echo $row_post->title?></strong>
      <p><?php echo $row_comment->comment?></p>
      </td>
    </tr>
	<tr class="isi" <?php echo $warna;?>>
	  <td style="text-align:left; vertical-align:middle; padding-left:5px;border-top:0;">&nbsp;</td>
	  <td style="text-align:left;alignment-adjust:central;border-top:0;"><a href="?admin&apps=post&go=comment&act=view&id=<?php echo $row_comment->comment_id?>" class="button button4 green l" style="margin-top:3px;">Lihat</a><a href="?admin&apps=post&go=comment&act=view&reply=1&id=<?php echo $row_comment->comment_id?>" class="button button4 blue r" style="margin-top:3px;">Balas</a></td>
	  <td style="border-top:0;"><a href="mailto:<?php echo $row_comment->email?>" title="Visit autdor homepage">Mail</a> &bull; <?php echo  time_stamp($row_comment->time)?></td>
    </tr>
<?php
$checkbox_id++;
}
?>
</table>
<input type="hidden" id="checkbox_total" value="<?php echo $checkbox_id?>" name="checkbox_total">
</div>

<?php

$header_title= 'Comment Manager';

$header_menu.= '<div class="header_menu_top">';
$header_menu.= '<input type="submit" name="submitDelete" class="primary button on red" value="Delete the selected" id="checkbox_go">';
$header_menu.= '</div>';

}

$content = ob_get_contents();
ob_end_clean();

$form = 'action="" method="post" enctype="multipart/form-data"';
add_templates_manage( $content, $header_title, $header_menu, null, $form );

break;
case 'setting':
ob_start();
?>
<div class="padding">
<?php
if(isset($_POST['submit'])){	
	$post_comment = (string)$_POST['post_comment'];
	if( checked_option( 'post_comment' ) ) set_option( 'post_comment', $post_comment );
	else add_option( 'post_comment', $post_comment );
	
	
	$post_comment_filter = (int)$_POST['post_comment_filter'];
	if( checked_option( 'post_comment_filter' ) ) set_option( 'post_comment_filter', $post_comment_filter );
	else add_option( 'post_comment_filter', $post_comment_filter );
	
	add_activity('post',"Merubah setting post", 'post');	
	echo "<div id=\"success\">Berhasil merubah pengaturan post</div>";
    echo "<meta http-equiv=\"refresh\" content=\"0;url=?admin&apps=post\" />";
}
?>
  <table width="100%" cellpadding="4">
    <tbody>
    <tr>
      <td>Post Comment Filter</td>
      <td><strong>:</strong></td>
      <td>
      <select name="post_comment_filter">
<?php
if(get_option('post_comment_filter')==1){
	echo '	
      <option value="0">Unaproved</option>
      <option value="1" selected="selected">Approved</option>
	';
}else{	
	echo '	
      <option value="0" selected="selected">Unapproved</option>
      <option value="1">Approved</option>
	';
}
?>
      </select>
      </td>
    </tr>
    
    <tr>
      <td>Post Comment Form</td>
      <td><strong>:</strong></td>
      <td>
      <select name="post_comment">
<?php
if(get_option('post_comment')==1){
	echo '	
      <option value="0">Hide</option>
      <option value="1" selected="selected">Show</option>
	';
}else{	
	echo '	
      <option value="0" selected="selected">Hide</option>
      <option value="1">Show</option>
	';
}
?>
      </select>
      </td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td>
      </td>
    </tr>
    <tbody>
</table>
</div>
<?php
$header_title = 'Setting Post Manager';
$header_menu = '';
$header_menu.= '<div class="header_menu_top">';
$header_menu.= '<input type="submit" name="submit" class="button on l blue" value="Save & Update"><input type="reset" class="button r" name="Reset" value="Reset">';
$header_menu.= '</div>';
$header_menu.= '<a href="?admin&apps=post&go=comment" class="button button3"><span class="icon_head back">&laquo; Back</span></a>';

$content = ob_get_contents();
ob_end_clean();

$form = 'action="" method="post" enctype="multipart/form-data"';
add_templates_manage( $content, $header_title, $header_menu, null, $form );
break;
}
?>
