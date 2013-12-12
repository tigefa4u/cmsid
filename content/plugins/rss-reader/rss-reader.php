<?php
/**
 * @file: rss-reader.php
 * @type: plugin
 */
/*
Plugin Name: Rss Reader
Plugin URI: http://cmsid.org/#
Description: Ini adalah plugin yang digunakan untuk membantu anda membuat & membaca rss secara mudah otomatis.
Author: Eko Azza
Version: 1.2
Author URI: http://cmsid.org/
*/
 
//not direct access
if(!defined('_iEXEC')) exit;

function rss_init(){
	if ( !class_exists('Rss') )
		require( plugin_path . '/rss-reader/class.php' );
}
add_action('plugins_loaded', 'rss_init');

function rss_writter(){
	global $db;
	
	if( !class_exists('DOMDocument') ) die('Class DOMDocument not exits');
	
	$doc	= new DOMDocument();
	$rss	= $doc->createElement('rss');
	$channel= $doc->createElement('channel');
	
	$title	= $doc->createElement('title',get_option('sitename') );
	$link	= $doc->createElement('link',site_url() );
	$desc	= $doc->createElement('description',get_option('sitedesc') );
	$lang	= $doc->createElement('language','en');
	
	$doc->appendChild($rss);
	$rss->appendChild($channel);
	
	$channel->appendChild($title);
	$channel->appendChild($desc);
	$channel->appendChild($desc);
	$channel->appendChild($lang);
	
	$f = '';	
	$l = filter_int( (int) $_GET['l'] );
	if( empty($l) ) $l = 10;	
	
	if( get_query_var('rss') && get_query_var('rss') == 'post' ):
	
	$f = "rss-post.xml";	
	$query = $db->select('post',array('type'=>'post','status'=>1,'approved'=>1),"ORDER BY date_post DESC LIMIT $l");
	
	while($data = $db->fetch_obj($query) ){
	
		$id		= $data->id;	
		$title	= $data->title;
		$author	= $data->user_login;
		$date	= $data->date_post;
		
		$link = '';
		if( function_exists('do_links') ):
			$link	= do_links('post',array('view'=>'item','id'=>$id,'title'=>$title),false);
			$link	= str_replace('&amp;','&',$link);
			$link	= str_replace('&','&amp;',$link);
		endif;
		
		$isi = $data->content;	
		$isi = sanitize( strip_tags($isi) );	
		$isi = htmlentities( strip_tags( nl2br($isi) ) );
		$isi = substr( $isi, 0, 400 );
		$isi = substr( $isi, 0, strrpos($isi, " ") );
		
		$item		= $doc->createElement('item');
		$ititle		= $doc->createElement('title', $title);
		$ilink		= $doc->createElement('link', $link);
		$idesc		= $doc->createElement('description', $isi);
		$iauthor	= $doc->createElement('author', $author);
		$idate		= $doc->createElement('pubDate', $date);
			
		$item->appendChild($ititle);
		$item->appendChild($ilink);
		$item->appendChild($idesc);
		$item->appendChild($iauthor);
		$item->appendChild($idate);
			
		$channel->appendChild($item);
	}
	
	endif;
	
	if( !empty($f) ):
	
		$file = fopen( abs_path . $f, "w" );
		$rss  = str_replace('<rss>','<rss version="2.0">',$doc->saveXML());
		fwrite($file,$rss);
		fclose($file); 
	
	endif;

}

if( get_query_var('rss') )
	add_action('the_head', 'rss_writter');