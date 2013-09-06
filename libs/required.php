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
error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );

if(!file_exists( abs_path . 'config.php')
&&  file_exists( abs_path . libs . '/installer/installer.php') ) {
   define('installing', true);
   require_once( abs_path . libs . '/installer/installer.php' );
	
}else{		
	require_once( abs_path . 'config.php' );
	require_once( abs_path . libs . '/query.php' );
	require_once( abs_path . libs . '/load.php' );	
	require_once( abs_path . libs . '/version.php' );	
	require_once( abs_path . libs . '/default-constants.php' );	
	require_once( abs_path . libs . '/settings.php' );	
	require_once( abs_path . libs . '/theme-loader.php' );
}