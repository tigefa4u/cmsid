<?php
/**
 * @file: install.php
 * @dir: content/plugins/statistik/
 */
if(!defined('_iEXEC')) exit;

function statistik_setup(){	
	global $db;
	
	$db->drop("stat_browse");
	$sql = "CREATE TABLE IF NOT EXISTS `$db->stat_browse` (
			  `title` varchar(255) NOT NULL DEFAULT '',
			  `option` text NOT NULL,
			  `hits` text NOT NULL,
			  PRIMARY KEY (`title`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1";		
	$db->query($sql);
	
	$sql = "INSERT INTO `$db->stat_browse` (`title`, `option`, `hits`) VALUES
			('browser', 'Opera#Mozilla Firefox#Galeon#Mozilla#MyIE#Lynx#Netscape#Konqueror#SearchBot#IE 6#IE 7#IE 8#IE 9#IE 10#Other#', '0#0#0#0#0#0#0#0#0#0#0#0#0#0#0#'),
			('os', 'Windows#Mac#Linux#FreeBSD#SunOS#IRIX#BeOS#OS/2#AIX#Other#', '0#0#0#0#0#0#0#0#0#0#'),
			('day', 'Minggu#Senin#Selasa#Rabu#Kamis#Jumat#Sabtu#', '0#0#0#0#0#0#0#'),
			('month', 'Januari#Februari#Maret#April#Mei#Juni#Juli#Agustus#September#Oktober#November#Desember#', '0#0#0#0#0#0#0#0#0#0#0#0#'),
			('clock', '0:00#1:00#2:00#3:00#4:00#5:00#6:00#7:00#8:00#9:00#10:00#11:00#12:00#13:00#14:00#15:00#16:00#17:00#18:00#19:00#20:00#21:00#22:00#23:00#', '0#0#0#0#0#0#0#0#0#0#0#0#0#0#0#0#0#0#0#0#0#0#0#0#'),
			('country', '', '')";		
	$db->query($sql);
	
	$db->drop("stat_count");
	$sql = "CREATE TABLE IF NOT EXISTS `$db->stat_count` (
			  `id` tinyint(11) NOT NULL AUTO_INCREMENT,
			  `ip` text NOT NULL,
			  `counter` int(11) NOT NULL DEFAULT '0',
			  `hits` int(11) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2";		
	$db->query($sql);
	
	$db->drop("stat_online");
	$sql = "CREATE TABLE IF NOT EXISTS `$db->stat_online` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `ipproxy` varchar(100) DEFAULT NULL,
			  `host` varchar(100) DEFAULT NULL,
			  `ipanda` varchar(100) DEFAULT NULL,
			  `proxyserver` varchar(100) DEFAULT NULL,
			  `timevisit` int(11) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42";		
	$db->query($sql);
	
	$db->drop("stat_onlineday");
	$sql = "CREATE TABLE IF NOT EXISTS `$db->stat_onlineday` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `ipproxy` varchar(100) DEFAULT NULL,
			  `host` varchar(100) DEFAULT NULL,
			  `ipanda` varchar(100) DEFAULT NULL,
			  `proxyserver` varchar(100) DEFAULT NULL,
			  `timevisit` int(11) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3";		
	$db->query($sql);
	
	$db->drop("stat_onlinemonth");
	$sql = "CREATE TABLE IF NOT EXISTS `$db->stat_onlinemonth` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `ipproxy` varchar(100) DEFAULT NULL,
			  `host` varchar(100) DEFAULT NULL,
			  `ipanda` varchar(100) DEFAULT NULL,
			  `proxyserver` varchar(100) DEFAULT NULL,
			  `timevisit` int(11) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3";		
	$db->query($sql);
	return;
}

function statistik_drop(){
	global $db;
	
	$db->drop("stat_browse");
	$db->drop("stat_count");
	$db->drop("stat_online");
	$db->drop("stat_onlineday");
	$db->drop("stat_onlinemonth");
	return;
}

add_action('plugin_install', 'statistik_setup');
add_action('plugin_uninstall', 'statistik_drop');