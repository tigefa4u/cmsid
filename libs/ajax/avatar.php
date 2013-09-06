<?php
/**
 * @file avatar.php
 *
 */
//dilarang mengakses
if(!defined('_iEXEC')) exit;

if( 'libs/ajax/avatar.php' == is_load_values() ):

if( esc_sql( $_POST['email'] ) )
{
	
	$email 		= filter_txt($_POST['email']);
	$email 		= esc_sql( $email );	
	$image_url 	= avatar_url($email);	
	echo $image_url;
}

endif;
?>