<?php
/**
 * @file: install.php
 * @dir: content/plugins/activity/
 */
if(!defined('_iEXEC')) exit;

function shoutbox_setup(){	
	global $db;
	
	$db->drop("shoutbox");
	$sql = "CREATE TABLE IF NOT EXISTS `$db->shoutbox` (
			  `id` int(4) NOT NULL AUTO_INCREMENT,
			  `nama` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
			  `email` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
			  `pesan` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
			  `waktu` datetime NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=141";
	$db->query($sql);	
	$sql = "INSERT INTO `$db->shoutbox` (`id`, `nama`, `email`, `pesan`, `waktu`) VALUES (1, 'eko', 'id.hpaherba@yahoo.co.id', 'test shoutbox pertama', '2013-09-14 00:09:45'), (2, 'admin', 'admin@cmsid.org', 'test kedua salam cms', '2013-09-15 00:34:05');";	
	$db->query($sql);
	return;
}

function shoutbox_drop(){
	global $db;
	
	$db->drop("shoutbox");
	return;
}

add_action('plugin_install', 'shoutbox_setup');
add_action('plugin_uninstall', 'shoutbox_drop');