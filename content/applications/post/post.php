<?php
/*
App Name: Post
App URI: http://cmsid.org/#
Description: App post
Author: Eko Azza
Version: 2.2.1
API Key: G1sSE7bqtXDxXxT8ssiy
Author URI: http://cmsid.org/#
*/ 

//dilarang mengakses
if(!defined('_iEXEC')) exit;
global $db, $login;

switch( get_query_var('view') ){
default:
	header("Location:" . site_url());
	exit;
break;
case'archives':
case'archive':
$date 		= filter_txt( get_query_var('id') );
$date 		= esc_sql($date);

if( get_option('rewrite') != 'advance' ) 
	$date = str_replace('-',':',$date);

$bt = 10;
$pg = (int) get_query_var('pg');
if(empty($pg)){
	$ps = 0;
	$pg = 1;
}
else{
	$ps = ($pg-1) * $bt;
}

$ps = filter_int( $ps );

if (preg_match('/\d{4}\:\d{2}/',$date) || preg_match('/\d{4}\.\d{2}/',$date)) {
	
	if( preg_match('/\d{4}\:\d{2}/', $date) )
		list($tahun, $bulan) = explode(':',$date);
	
	if( preg_match('/\d{4}\.\d{2}/', $date) )
		list($tahun, $bulan) = explode('.',$date);
	
	$bulan 	= filter_txt($bulan);
	$tahun 	= filter_int($tahun);
	
	$bulan  = esc_sql($bulan);
	$tahun 	= esc_sql($tahun);
	
}

$data_archive 	= array();
$query_post		= $db->query("SELECT * FROM `$db->post` WHERE month(`date_post`) = '$bulan' AND year(`date_post`) = '$tahun' AND type = 'post' AND approved=1 AND status = 1 ORDER BY `date_post` DESC LIMIT $ps,$bt");
$total_post 	= $db->num_query("SELECT * FROM `$db->post` WHERE month(`date_post`) = '$bulan' AND year(`date_post`) = '$tahun' AND type = 'post' AND approved=1 AND status = 1");


while( $data_post = $db->fetch_array($query_post) ){
	$data_archive[] = array( 
	'title' 	=> $data_post['title'], 
	'title_url'	=> do_links('post', array('view' => 'item', 'id' => $data_post['id'], 'title' => $data_post['title'])), 
	'content' 	=> limittxt( sanitize( strip_tags($data_post['content']) ),250),
	'thumb' 	=> $data_post['thumb']
	);
}

$text = date_times($tahun.'-'.$bulan,false,false);
$text = sprintf('%1$s %2$d', $text['bln'], $tahun);

if( get_option('rewrite') != 'advance' ) 
	$id = str_replace(':','-',$date);
				
$url = do_links( 'post', array('view'=>'archive','id'=>$id, 'pg' => "%page%") ); 
$data = array(
	'view' 		=> 'archive', 	
	'the_title' => 'Archive '.$text,	
	'content' 	=> $data_archive, 
	'paging'	=> array(
		'urlscheme' => $url,
		'perpage' 	=> $bt,
		'page' 		=> $pg,
		'total' 	=> $total_post
	), 
	'browse' 	=> '<a href="'.do_links('post').'">Home</a> &raquo; Archive for '.$text
);
add_the_content_view( (object) $data );
break;
case'tags':
$tags = filter_txt( get_query_var('id') );
$tags = esc_sql($tags);
$tags = str_replace('-',' ',$tags);  

if ( strlen($tags) == 3 )
	$finder = "`tags` LIKE '%$tags%'";
else
	$finder = "MATCH (tags) AGAINST ('$tags' IN BOOLEAN MODE)";
	
$bt = 10;
$pg = (int) get_query_var('pg');
if(empty($pg)){
	$ps = 0;
	$pg = 1;
}
else{
	$ps = ($pg-1) * $bt;
}

$ps = filter_int( $ps );


$data_archive 	= array();
$query_post		= $db->query("SELECT * FROM `$db->post` WHERE $finder AND type='post' AND approved='1' AND status='1' ORDER BY date_post DESC LIMIT $ps,$bt");
$total_post 	= $db->num_query("SELECT * FROM `$db->post` WHERE $finder AND type='post' AND approved='1' AND status='1'");

while( $data_post = $db->fetch_array($query_post) ){
	$data_archive[] = array( 
	'title' 	=> $data_post['title'], 
	'title_url'	=> do_links('post', array('view' => 'item', 'id' => $data_post['id'])), 
	'content' 	=> limittxt( sanitize( strip_tags($data_post['content']) ),250),
	'thumb' 	=> $data_post['thumb']
	);
}

$url = do_links( 'post', array('view'=>'tags','id'=>$tags, 'pg' => "%page%") ); 
$data = array(
	'view' 		=> 'archive',	
	'the_title' => 'Tags "'.$tags.'"',	
	'content' 	=> $data_archive, 
	'paging'	=> array(
		'urlscheme' => $url,
		'perpage' 	=> $bt,
		'page' 		=> $pg,
		'total' 	=> $total_post
	), 
	'browse' 	=> '<a href="'.do_links('post').'">Home</a> &raquo; Posts tagged with "'.$tags.'"'
);
add_the_content_view( (object) $data );
break;
case'category':

$id 			= get_posted_id('category', get_query_var('id') );
$id 			= filter_int( $id );
$id 			= esc_sql($id);

$bt = 10;
$pg = (int) get_query_var('pg');
if(empty($pg)){
	$ps = 0;
	$pg = 1;
}
else{
	$ps = ($pg-1) * $bt;
}

$ps = filter_int( $ps );

$data_archive 	= array();
$query_post		= $db->select('post', array('type' => 'post', 'post_topic' => $id, 'approved' => 1, 'status' => 1), "ORDER BY date_post DESC LIMIT $ps,$bt");
$total_post 	= $db->num_query("SELECT * FROM $db->post WHERE type='post' AND post_topic='$id' AND approved='1' AND status='1'");

while( $data_post = $db->fetch_obj($query_post) ){
	$data_archive[] = array( 
	'title' 	=> $data_post->title, 
	'author' 	=> $data_post->user_login, 
	'date' 		=> $data_post->date_post, 
	'title_url'	=> do_links('post', array('view' => 'item', 'id' => $data_post->id, 'title' => $data_post->title)), 
	'content' 	=> limittxt( sanitize( strip_tags($data_post->content) ),400),
	'thumb' 	=> $data_post->thumb
	);
}

$query_topic	= $db->select("post_topic",array( 'id' => $id ) );
$data_topic		= $db->fetch_obj($query_topic);

$url = do_links('post', array('view' => 'category', 'id' => $id, 'title' => $data_topic->topic, 'pg' => "%page%" ));
$data = array(
	'view' 		=> 'archive', 	
	'the_title' => $data_topic->topic,	
	'content' 	=> $data_archive, 
	'paging'	=> array(
		'urlscheme' => $url,
		'perpage' 	=> $bt,
		'page' 		=> $pg,
		'total' 	=> $total_post
	), 
	'browse' 	=> '<a href="'.do_links('post').'">Home</a> &raquo; <a href="'.do_links('post', array('view' => 'category', 'id' => $data_topic->id, 'title' => $data_topic->topic)).'">'.$data_topic->topic.'</a>'
);
add_the_content_view( (object) $data );
break;
case'item':
$hits   	= 0;
$id 		= get_posted_id('item', get_query_var('id') );
$id 		= filter_int( $id );
$id 		= esc_sql($id);

if( $login->check() && $login->level('admin') && !empty($id) )
$where = array('id' => $id, 'type' => 'post');
else
$where = array('id' => $id, 'type' => 'post', 'approved' => 1, 'status' => 1);

$sqlPost	= $db->select( "post", $where );
$wPost 		= $db->fetch_obj($sqlPost);
$hits		= $wPost->hits;

$db->update( "post", array('hits' => $hits+1 ), $where );

$sqlTopic 	= $db->select( 'post_topic', array('status' => 1,'id' => $wPost->post_topic) );
$wTopic 	= $db->fetch_obj($sqlTopic);

$meta_desc = empty($wPost->meta_desc) ? $wPost->content : $wPost->meta_desc;
$meta_keys = empty($wPost->meta_keys) ? $wPost->tags : $wPost->meta_keys;

$data = array(
	'view' 		=> 'page-full', 
	
	'the_title' => initialized_text( sanitize( $wPost->title ), null ),
	'the_desc' 	=> initialized_text( sanitize( $meta_desc ), 280 ),
	'the_key' 	=> initialized_text( sanitize( $meta_keys ), null, true ),
	
	'title' 	=> sanitize( $wPost->title ), 
	'thumb' 	=> $wPost->thumb,  
	'thumb_desc'=> $wPost->thumb_desc, 
	'content' 	=> sanitize( $wPost->content ), 
	'posted' 	=> "Posted by $wPost->user_login on ".datetimes( $wPost->date_post, false )." // ".get_comment_total($wPost->id)." comments", 
	'browse' 	=> "<a href=".do_links('post').">Home</a> &raquo; <a href=".do_links('post', array('view' => 'category', 'id' =>$wTopic->id, 'title' => $wTopic->topic)).">$wTopic->topic</a> &raquo; <a href=".do_links('post', array('view' => 'item', 'id' =>$wPost->id, 'title' => $wPost->title)).">$wPost->title</a>"
);
add_the_content_view( (object) $data );
break;
}
?>