<?php
/**
 * @file: install.php
 * @dir: content/plugins/activity/
 */
if(!defined('_iEXEC')) exit;

function activity_recods_setup(){	
	global $db;
	
	$db->drop("stat_activity");
	$sql = "CREATE TABLE IF NOT EXISTS `$db->stat_activity` (
			`activity_id` int(11) NOT NULL AUTO_INCREMENT,
			`user_id` varchar(30) NOT NULL,
			`activity_name` varchar(80) NOT NULL,
			`activity_value` longtext NOT NULL,
			`activity_img` text NOT NULL,
			`activity_date` date NOT NULL,
			PRIMARY KEY (`activity_id`) 
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2";			
	$db->query($sql);
	return;
}

function activity_recods_drop(){
	global $db;
	
	$db->query("stat_activity");
	return;
}

add_action('plugin_install', 'activity_recods_setup');
add_action('plugin_uninstall', 'activity_recods_drop');