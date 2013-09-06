<?php
/**
 * @file file_link_upload.php
 *
 */
//dilarang mengakses
if(!defined('_iEXEC')) exit;

global $login;

if('libs/ajax/file_link_upload.php' == is_load_values() 
&& isset($_FILES['file']['name'])
&& $login->check() 
&& $login->level('admin') ):

$file = esc_sql( $_FILES['file'] );
$file = hash_files( $file );

copy($file['tmp_name'], upload_path .'/'. $file['name']);
					
echo site_url( '/content/uploads/' . $file['name'] );

endif;

?>