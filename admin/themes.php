<?php 
/**
 * @fileName: themes.php
 * @dir: admin/
 */
if(!defined('_iEXEC')) exit;

function themes_available(){
	$theme_path_root = theme_path .'/';
	$i=0;
	unset($theme_arr);
	if ($handle = opendir($theme_path_root)) {
		while (false !== ($file = readdir($handle))) {
		$i++;
		if ($file != "." && $file != ".." 
		&& is_dir($theme_path_root . $file) 
		&& file_exists( $theme_path_root . $file . '/info.xml' ) ) 
			$theme_arr[] = $file;		
		}
		closedir($handle);
	}
	
	return $theme_arr;
}


function get_theme_data( $dir ){
	$theme_path_root = theme_path . '/';
	if (file_exists($theme_path_root . $dir . '/info.xml')) {
	$index_theme 	= @implode( '', file( $theme_path_root . $dir.'/info.xml' ) );
	$info_themes 	= str_replace ( '\r', '\n', $index_theme );
			
	preg_match( '|<themes>(.*)<\/themes>|ims'				, $info_themes, $themes 	);
	preg_match( '|<name>(.*)<\/name>|ims'					, $themes[1], $name 		);
	preg_match( '|<version>(.*)<\/version>|ims'				, $themes[1], $version		);
	preg_match( '|<creationDate>(.*)<\/creationDate>|ims'	, $themes[1], $date			);
	preg_match( '|<author>(.*)<\/author>|ims'				, $themes[1], $author 		);
	preg_match( '|<authorEmail>(.*)<\/authorEmail>|ims'		, $themes[1], $authorEmail 	);
	preg_match( '|<apiKey>(.*)<\/apiKey>|ims'				, $themes[1], $apiKey 		);
	preg_match( '|<authorUrl>(.*)<\/authorUrl>|ims'			, $themes[1], $authorUrl 	);
	preg_match( '|<copyright>(.*)<\/copyright>|ims'			, $themes[1], $copyright 	);
	preg_match( '|<license>(.*)<\/license>|ims'				, $themes[1], $license 		);
	preg_match( '|<description>(.*)<\/description>|ims'		, $themes[1], $description 	);
	$theme = array(
		'name' => $name[1],
		'version' => $version[1],
		'date' => $date[1],
		'author' => $author[1],
		'authorEmail' => $authorEmail[1],
		'apiKey' => $apiKey[1],
		'authorUrl' => $authorUrl[1],
		'copyright' => $copyright[1],
		'license' => $license[1],
		'description' => $description[1]
	);
	return $theme;
	}
}

function get_dir_themes(){
	$file_data = array();
	$theme_available = themes_available();
	foreach($theme_available as $file){
		$file_data[] = get_theme_data( "$file" ); 
	}
	return $file_data;
}