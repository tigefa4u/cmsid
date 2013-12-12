<?php
/**
 * @file image-upload.php
 *
 */
//dilarang mengakses
if(!defined('_iEXEC')) exit;

global $login;

if('libs/ajax/image-upload.php' == is_load_values() 
&& isset( $_FILES['file']['name'] )
&& $login->check() 
&& $login->level('admin') ):

$thumb = esc_sql( $_FILES['file'] );
$thumb = hash_image( $thumb );

copy($thumb['tmp_name'], upload_path .'/'. $thumb['name']);
						
echo '<img src="' . site_url('content/uploads/' . $thumb['name']).'" />';	

endif;
?>