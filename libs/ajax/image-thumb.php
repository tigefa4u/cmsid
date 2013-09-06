<?php
/**
 * @file image-thumb.php
 *
 */
//dilarang mengakses
if(!defined('_iEXEC')) exit;

global $login;

if('libs/ajax/image-thumb.php' == is_load_values() 
&& !empty($_GET[src]) 
&& $login->check() 
&& $login->level('admin') ):

crop_image( upload_path .'/' );
	
endif;

