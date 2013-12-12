<?php
/**
 * @file: seo-optimization.php
 * @type: plugin
 */
/*
Plugin Name: Block
Plugin URI: http://cmsid.org/#
Description: Plugin Pembuat block widget.
Author: Eko Azza
Version: 1.1
API Key: EQHZTqp2pAZFgLMC8fbT
Author URI: http://cmsid.org/
*/ 
//not direct access
if(!defined('_iEXEC')) exit;

function block_widget_init(){
}
add_action('plugins_loaded', 'block_widget_init');

class Widget_Block extends Widgets {

	function __construct() {
		$widget_ops = array('classname' => 'widget_block', 'description' => "Block view code html" );
		parent::__construct('block', 'Block', $widget_ops);
	}

	function widget( $args ) {
		extract($args);
		
		global $db;
		
		$id = esc_sql($id);
		$query_block = $db->query("SELECT * FROM $db->block WHERE status=1 AND sidebar='$id' ORDER BY order_by ASC");
		while( $data_block = $db->fetch_obj($query_block) ){
			echo $before_widget;
			echo $before_title . $data_block->title . $after_title;
			echo $data_block->html;
			echo $after_widget;
		}
	}
}

register_widget("Widget_Block");