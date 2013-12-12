<?php 
$page_view = get_page_view();
$data = array(
	'view' 		=> 'page', 
	
	'the_title' => sanitize( $page_view->title ),
	'the_desc' 	=> '',
	'the_key' 	=> '',
	
	'title' 	=> sanitize( $page_view->title ), 
	'content' 	=> sanitize( $page_view->content ) 
);
add_the_content_view( (object) $data );