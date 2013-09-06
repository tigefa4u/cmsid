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

function read_folder_files( $dir_root ){
	$list_dir = array();
	if( $handler = opendir($dir_root) ){
		while( ($sub_dir = readdir($handler)) !== false ){
			if( $sub_dir != "." && $sub_dir != ".." && $sub_dir != "Thumb.db" && $sub_dir != "Thumbs.db" ){
				if( is_file( $dir_root .'/'. $sub_dir ) ){
					$list_dir[] = $sub_dir;
				}elseif( is_dir( $dir_root .'/'. $sub_dir ) ){
					$list_dirx = read_folder_files( $dir_root .'/'. $sub_dir );
					foreach( $list_dirx as $file ){
						$list_dir[] = $sub_dir .'/'. $file;
					}
				}
			}
		}
		closedir($handler);
	}
	return $list_dir;
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
}

function show_file_author( $dir_root ){
	$dir_array = read_folder_files( $dir_root );
	print '<ul>';
	foreach( $dir_array as $file ){
		echo '<li>' . $file . '</li>';
	}
	print '</ul>';
}

function show_data_file_author( $mode, $file_xml, $installer_preview ){	
	if( !file_exists($file_xml) )
		return false;

	$xml = simplexml_load_file( $file_xml );	
	echo '
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="table">
	  <tr class="head" style="border-top:1px solid #d6d7db">
		<td colspan="3"><b>'.uc_first( $mode ).' '.$xml->name.'</b></td>
	  </tr>';
	if( $images = get_images_preview( $installer_preview ) ){
	echo '
	  <tr class="isi">
		<td colspan="3">'.count($images).' Screenshots</td>
	  </tr>
	  <tr class="head">
		<td colspan="3" align="center">';
	echo'<div class="sc_menu" style="overflow: hidden; ">
		 <ul class="sc_menu">';			
			foreach($images as $img) {
			   echo '<li><a href="#"><img src="'.$installer_preview.'/'.$img['file'].'" alt="'.basename($img['filename']).'"><span>'.basename($img['filename']).'</span></a></li>	';
			}
	echo'</ul></div>
		</td>
	  </tr>';
	}
	echo '
	  <tr class="isi">
		<td width="30%" >Release Date</td>
		<td width="1%"><strong>:</strong></td>
		<td width="69%">'.$xml->creationDate.'</td>
	  </tr>
	  <tr class="isi">
		<td>Author</td>
		<td><strong>:</strong></td>
		<td>'.$xml->author.'</td>
	  </tr>
	  <tr class="isi">
		<td>Website</td>
		<td><strong>:</strong></td>
		<td><a href="'.$xml->authorUrl.'">'.$xml->authorUrl.'</a></td>
	  </tr>
	  <tr class="isi">
		<td>Email</td>
		<td><strong>:</strong></td>
		<td>'.$xml->authorEmail.'</td>
	  </tr>
	  <tr class="isi">
		<td>Version Aplication</td>
		<td><strong>:</strong></td>
		<td>'.$xml->version.'</td>
	  </tr>
	  <tr class="isi">
		<td>License</td>
		<td><strong>:</strong></td>
		<td>'.$xml->license.'</td>
	  </tr>
	  <tr class="isi">
		<td>Copyright</td>
		<td><strong>:</strong></td>
		<td>'.$xml->copyright.'</td>
	  </tr>
	  <tr class="isi">
		<td>Description</td>
		<td><strong>:</strong></td>
		<td>'.$xml->description.'</td>
	  </tr>
	</table>
	';
	
}

function get_images_preview( $source ){
	$list_file = array();
	
	if(substr($source, -1) != "/") $source .= "/";
	
	if ( is_dir( $source ) ) {
		$directory = dir( $source );
		while ( FALSE !== ( $readdirectory = $directory->read() ) ) {
			if ( $readdirectory == '.' || $readdirectory == '..' ) continue;
			if( is_file( $source . $readdirectory ) ){
				if( $extension = explode('.png', $readdirectory) ){
					$getimagesize = getimagesize( $source . $readdirectory );
					$filesize = filesize( $source . $readdirectory );
					
					$list_file[$extension[0]]['file'] = $readdirectory;
					$list_file[$extension[0]]['filename'] = $extension[0];
					$list_file[$extension[0]]['filesize'] = $filesize;
					$list_file[$extension[0]]['size'] = $getimagesize;
				}
			}
		}
 
		$directory->close();
	}
	
	return $list_file;
}

function installer_applications( $mode, $folder ){
	global $format_error, $install;
	
	$config_file = "tmp/$folder/$folder.xml";
	$installer_file = "tmp/$folder/installer.php";
	$installer_preview = "tmp/$folder/preview";
	$installer_moved_req = "tmp/$folder/applications/";
	$installer_moved = "content/applications/$folder";
	$installer_moved_file_req = "$installer_moved_req$folder.php";
				
	if(file_exists( $config_file ) 
	&& file_exists( $installer_file )  
	&& ( is_dir( $installer_moved_req ) or is_file( $installer_moved_file_req )) 
	){
		
	$format_error = false;	
										
	include $installer_file;
					
	echo '<div class="padding"><div id="success">Applikasi berhasil terinstall.</div></div>';
	show_data_file_author( $mode, $config_file, $installer_preview );
						
	//install database
	//echo $install['sql'];
						
	//move file
	copy_directory( $installer_moved_req, $installer_moved );
					
	//echo "<br>Ektrak file:<br>";
	//show_file_author( "content/applications/$folder" );						
					
	}
}

function installer_plugins( $mode, $folder ){
	global $format_error, $install;
	
	$config_file = "tmp/$folder/$folder.xml";
	$installer_file = "tmp/$folder/installer.php";
	$installer_preview = "tmp/$folder/preview";
	$installer_moved_req = "tmp/$folder/plugins/";
	$installer_moved_folder_req = "$installer_moved_req$folder";
	$installer_moved_file_req = "$installer_moved_req$folder.php";
	$installer_moved = "content/plugins";
	
	$install_plugins = false;
	if(file_exists( $config_file ) 
	&& file_exists( $installer_file )  
	&& ( is_dir( $installer_moved_folder_req ) or is_file( $installer_moved_file_req ) )
	){
	
	if( is_dir( $installer_moved_folder_req ) )
	$install_plugins = true;
	
	if( is_file( $installer_moved_file_req ) )
	$install_plugins = true;
	
	}
	
	if( $install_plugins ){
		
	$format_error = false;	
										
	include $installer_file;
	
	echo '<div class="padding"><div id="success">Plugins berhasil terinstall.</div></div>';
	show_data_file_author( $mode, $config_file, $installer_preview );
	
	//install database
	//echo $install['sql'];
	
	//move file
	copy_directory( $installer_moved_req, $installer_moved );	
		
	}
}