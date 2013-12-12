<?php
/**
 * @file init.php
 * @dir: admin/manage/media
 *
 */
//dilarang mengakses
if(!defined('_iEXEC')) exit;

function get_widget(){
	global $widget;
	
	$gadget = array();		
	
	$widget = array(
		'gadget'	=> $gadget,
		'help_desk' => 'tool ini mempermudah anda memanage file'
	);
	return;
}
add_action('the_actions_menu', 'get_widget');