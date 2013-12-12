<?php 
/**
 * @fileName: required.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;
/**
 * mendefinisikan variable use_themes bernilai benar
 */
define('use_themes', true);
/**
 * melaporkan kesalahan berdasarkan opsi
 */


$setupFile = abs_path . libs . "/installer/installer.php";
if(!file_exists( abs_path . 'config.php') &&  file_exists( $setupFile ) ) {
	define('installing', true);
   
	require_once( $setupFile );
	
}else{		

	require_once( abs_path . 'config.php' );
	require_once( abs_path . libs . '/query.php' );
	require_once( abs_path . libs . '/load.php' );	
	require_once( abs_path . libs . '/version.php' );	
	require_once( abs_path . libs . '/default-constants.php' );		
	require_once( abs_path . libs . '/settings.php' );	
	require_once( abs_path . libs . '/theme-loader.php' );
}