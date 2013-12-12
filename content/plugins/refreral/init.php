<?php
/**
 * @file: install.php
 * @dir: content/plugins/referal/
 */
if(!defined('_iEXEC')) exit;

function referal_setup(){	
	global $db;
	
	$db->drop("stat_urls");
	$sql = "CREATE TABLE IF NOT EXISTS `$db->stat_urls` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `domain` varchar(256) NOT NULL,
			  `referrer` longtext NOT NULL,
			  `search_terms` text NOT NULL,
			  `hits` int(11) NOT NULL DEFAULT '1',
			  `date_modif` datetime NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";		
	$db->query($sql);
	return;
}

function referal_drop(){
	global $db;
	
	$db->drop("stat_urls");
	return;
}

add_action('plugin_install', 'referal_setup');
add_action('plugin_uninstall', 'referal_drop');