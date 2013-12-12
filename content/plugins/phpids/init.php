<?php
/**
 * @file: install.php
 * @dir: content/plugins/activity/
 */
if(!defined('_iEXEC')) exit;

function phpids_setup(){	
	global $db;
	
	$db->drop("phpids");
	$sql = "CREATE TABLE IF NOT EXISTS `$db->phpids` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(128) NOT NULL DEFAULT '',
			  `value` text NOT NULL,
			  `page` varchar(255) NOT NULL DEFAULT '',
			  `ip` varchar(15) NOT NULL DEFAULT '',
			  `impact` int(11) unsigned NOT NULL DEFAULT '0',
			  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2";		
	$db->query($sql);
	return;
}

function phpids_drop(){
	global $db;
	
	$db->drop("phpids");
	return;
}

add_action('plugin_install', 'phpids_setup');
add_action('plugin_uninstall', 'phpids_drop');