<?php
/**
 * @file init.php
 * @dir: applications/example
 *
 */
//dilarang mengakses
if(!defined('_iEXEC')) exit;

function get_widget(){
	global $widget;
	
	$actions = $gadget = array();
	$actions[] = array(
		'title' => 'Home App',
		'link'  => '?admin&amp;apps=example'
	);
	
	//jika widget didefinisikan maka fungsi gadget di nonaktifkan
	$gadget[] = array('title' => 'Widget dari init','desc' => 'this your content' );		
	
	$widget = array(
		'menu'		=> $actions,
		'gadget'	=> $gadget,
		'help_desk' => 'tool tips dari app example'
	);
	return;
}
add_action('the_actions_menu', 'get_widget');

/*
 * this is content function for app manage
 */