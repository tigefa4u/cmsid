<?php
/**
 * @file: install.php
 * @dir: content/plugins/block/
 */
if(!defined('_iEXEC')) exit;

function block_setup(){	
	global $db;
	
	$db->drop("block");
	$sql = "CREATE TABLE IF NOT EXISTS `$db->block` (
			  `block_id` int(11) NOT NULL AUTO_INCREMENT,
			  `title` varchar(30) NOT NULL,
			  `html` text NOT NULL,
			  `sidebar` varchar(35) NOT NULL,
			  `status` int(1) NOT NULL,
			  `order_by` int(2) NOT NULL,
			  PRIMARY KEY (`block_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4";		
	$db->query($sql);
	return;
}

function block_drop(){
	global $db;
	
	$db->drop("block");
	return;
}

add_action('plugin_install', 'block_setup');
add_action('plugin_uninstall', 'block_drop');