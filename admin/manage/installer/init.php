<?php
/**
 * @file init.php
 * @dir: admin/manage/users
 *
 */
//dilarang mengakses
if(!defined('_iEXEC')) exit;

function extracted_zip( $file, $directory ) {
	$zip = new ZipArchive;
	$res = $zip->open($file);
	if ($res === true ) {
		$zip->extractTo($directory);
		$zip->close();
		return true;
	}
	else 
		return false;
}

function copy_directory( $source, $destination ) {
	if ( is_dir( $source ) ) {
		@mkdir( $destination );
		$directory = dir( $source );
		while ( FALSE !== ( $readdirectory = $directory->read() ) ) {
			if ( $readdirectory == '.' || $readdirectory == '..' ) {
				continue;
			}
			$PathDir = $source . '/' . $readdirectory; 
			if ( is_dir( $PathDir ) ) {
				copy_directory( $PathDir, $destination . '/' . $readdirectory );
				continue;
			}
			copy( $PathDir, $destination . '/' . $readdirectory );
		}
 
		$directory->close();
	}else {
		copy( $source, $destination );
	}
	return true;
}

function installer_moved_to( $param, $path, $folder ){
	global $format_error;
	
	$root = abs_path;
	$tmp = "$root/$path/$folder";
	$installer_tmp = $tmp;
		
	if( is_dir($tmp) ){
		$installer_tmp = $tmp;
		$tmp = "$tmp/$folder.php";
	}
	
	if( $param == 'applications' ){
		
		$installer_moved = "$root/content/applications";
		$installer_tmp = "$root/$path";
		
		$component_data = get_applications_data( "$tmp" ); 
		$nama = $component_data['Name'];
		
	}elseif( $param == 'plugins' ){
		
		$installer_moved = "$root/content/plugins";		
		if( is_file($tmp) ) $installer_tmp = "$root/$path";
				
		$component_data = get_plugin_data( "$tmp" );
		$nama = $component_data['Name'];
	}elseif( $param == 'themes' ){
		$tmp = $installer_tmp."/info.xml";
		$installer_moved = "$root/content/themes";	
		$installer_tmp = "$root/$path";
		if( file_exists($tmp) ){
			$nama = $folder;
		}
	}
	
	if( is_dir($tmp) ) $installer_moved = "$installer_moved/$folder";
	
	
	if ( !empty ( $nama ) ){
		if( copy_directory( $installer_tmp, $installer_moved ) ){
			echo '<div class="padding"><div id="success_no_ani">Berhasil install '.$param.'.</div></div>';
		}else{
			echo '<div class="padding"><div id="error_no_ani">Gagal install '.$param.'.</div></div>';
		}
	}else $format_error = true;
}