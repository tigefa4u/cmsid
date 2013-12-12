<?php
/**
 * @file image-upload.php
 *
 */
//dilarang mengakses
if(!defined('_iEXEC')) exit;

//global $login;

if('libs/ajax/image.php' == is_load_values()
&& $login->check() 
&& $login->level('admin') ):

function read_image_folder_files( $dir_root ){
	$list_dir = array();
	if( $handler = opendir($dir_root) ){
		while( ($sub_dir = readdir($handler)) !== false ){
			if( $sub_dir != "." && $sub_dir != ".." && $sub_dir != "Thumb.db" && $sub_dir != "Thumbs.db" ){
				if( is_file( $dir_root .'/'. $sub_dir ) ){
					$list_dir[] = $sub_dir;
				}elseif( is_dir( $dir_root .'/'. $sub_dir ) ){
					$list_dirx = read_image_folder_files( $dir_root .'/'. $sub_dir );
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

$list_image = array();
foreach( read_image_folder_files( upload_path .'/' ) as $filename ){
	
	$name_ext = end( explode('.', $filename) );
	
	if( in_array($name_ext, array('jpg','jpeg','gif','png') ) ){
		$image_url = content_url('uploads/') . $filename;
		$thumb_url = site_url('/?request&load=libs/timthumb.php&src=') . $image_url .'&w=126&h=96&zc=1';
		$list_image[] = array('thumb' => $thumb_url,'image' => $image_url);
	}
	
}


$json = new JSON;
echo $json->encode($list_image);

endif;
?>