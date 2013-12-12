<?php
/**
 * @file lw.php
 *
 */
//dilarang mengakses
if(!defined('_iEXEC')) exit;

global $login;

if( 'libs/ajax/lw.php' == is_load_values() 
&& $login->check() 
&& $login->level('admin') 
):

if(!isset($_SESSION) )
	session_start();
		
$_SESSION['lw'] = esc_sql( $_POST['v'] );

endif;