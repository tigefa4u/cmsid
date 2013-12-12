<?php 
/**
 * @fileName: default-constants.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;

/**
 * Mendefinisikan direktori konstanta
 *
 */
function directory_constants() {
	
	if ( !defined('admin_path') )
	define( 'admin_path', 		abs_path 	. '/admin' );	
	
	if ( !defined('content_path') )
	define( 'content_path', 	abs_path 	. '/content' );
	
	if ( !defined('libs_path') )
	define( 'libs_path', 		abs_path 	. '/libs' );
	
	if ( !defined('tmp_path') )
	define( 'tmp_path', 		abs_path 	. '/tmp' );	 
	
	if ( !defined('manage_path') )
	define( 'manage_path', 		admin_path 	. '/manage' );	
	
	if ( !defined('theme_path') )
	define( 'theme_path', 		content_path 	. '/themes' );
	
	if ( !defined('plugin_path') )
	define( 'plugin_path', 		content_path 	. '/plugins' );
	
	if ( !defined('application_path') )
	define( 'application_path', content_path 	. '/applications' );
	
	if ( !defined('upload_path') )
	define( 'upload_path',		content_path 	. '/uploads' );
}
/**
 * Mendefinisikan direktori konstanta
 *
 */
function plugin_directory_constants() {
		
	if ( !defined('content_url') )
		define( 'content_url', get_option('siteurl') . '/content');
		
	if ( !defined('plugin_url') )
		define( 'plugin_url', content_url . '/plugins' );		
		
	if ( !defined('plugin_dir') )
		define( 'plugin_dir', plugin_path );
}
/**
 * Defines templating related constants
 */
function templating_constants() {

	if ( !defined('template_path') )
	define('template_path', get_template_directory());

	if ( !defined('default_theme') )
		define( 'default_theme', 'classic' );

}
/**
 * Mendefinisikan konstanta
 *
 */
function initial_constants(){
	
	if ( !defined('memory_limit') ) 
		define('memory_limit', '20M');

	if ( !defined( 'max_memory_limit' ) )
		define( 'max_memory_limit', '256M' );
	
	
	if ( function_exists('memory_get_usage') && ( (int) @ini_get('memory_limit') < abs(intval(memory_limit)) ) )
		@ini_set('memory_limit', memory_limit);
	
	// Increase max upload file size and execution time
    @ini_set( 'upload_max_size' , '20M' );
    @ini_set( 'post_max_size', '20M');
    @ini_set( 'max_execution_time', '300' );
		
	if ( !defined('debug') )
		define( 'debug', false );

	if ( !defined('cache') )
		define('cache', false);
}
/**
 * Defines cookie related constants
 */
function cookie_constants() {
	global $default_secret_key;

	if ( !defined( 'cookie_site_hash' ) ) {
		$siteurl = get_option( 'siteurl' );
		
		if ( $siteurl ) define( 'cookie_site_hash', md5( $siteurl ) );
		else define( 'cookie_site_hash', '' );
	}

	$default_secret_key = 'put your unique phrase here';

	if ( !defined('cookie_name') )
		define('cookie_name', 'user_' . cookie_site_hash);

	if ( !defined('cookie_path') )
		define('cookie_path', preg_replace('|https?://[^/]+|i', '', get_option('siteurl') . '/' ) );

	if ( !defined('cookie_domain') )
		define('cookie_domain', false);
}