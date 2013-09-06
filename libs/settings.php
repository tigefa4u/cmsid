<?php 
/**
 * @fileName: load.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;

initial_constants();

directory_constants();

@ini_set( 'magic_quotes_runtime', 0 );
@ini_set( 'magic_quotes_sybase', 0 );

handle_register_globals();

if( function_exists( 'date_default_timezone_set' ) )
date_default_timezone_set( 'UTC' );
	
unregister_globals();

fix_server_vars();

maintenance();

timer_start();

debug_mode();
	
require_once( libs_path . '/formatting.php' );

magic_quotes();
require_once( libs_path . '/filters.php' );
require_once( libs_path . '/actions.php' );
require_once( libs_path . '/default-filters.php' );
require_once( libs_path . '/class-error.php' );	
require_once( libs_path . '/class-json.php' );	
require_once( libs_path . '/class-pagination.php' );
require_once( libs_path . '/dashboard.php' );
require_once( libs_path . '/theme.php' );

require_mysql_db();	

require_once( libs_path . '/options.php' );

templating_constants();

require_once( libs_path . '/functions.php' );
require_once( libs_path . '/functions-old.php' );

if( is_admin() )
require_once( libs_path . '/screen.php' );

cookie_constants();

site_authentication();

require_once( libs_path . '/rewrite.php' );
require_once( libs_path . '/link-template.php' );
require_once( libs_path . '/general-template.php' );
require_once( libs_path . '/dynamic-menus.php' );

site_anonymise_geoip();

require( libs_path . '/vars.php' );
require( libs_path . '/timezone.php' );

site_timezone();

registered_global_widgets();

require( libs_path . '/widgets.php' );
require( libs_path . '/default-widgets.php' );	

plugin_directory_constants();

foreach ( get_active_and_valid_plugins() as $mu_plugin ) {
	include_once( $mu_plugin );
}
unset( $mu_plugin );

//load plugin for all action
do_action( 'plugins_loaded' );

templating_constants();

if ( ! defined( 'installing' ) ) {		
	if ( file_exists( template_path . '/functions.php' ) )
		include( template_path . '/functions.php' );
	if ( is_admin() && file_exists( admin_path . '/functions.php' ) )
		include( admin_path . '/functions.php' );	
}

//set init plugins or dashboard unit
do_action( 'init' );
	
//if( file_exists('libs/installer/index.php') )
	//delete_directory('libs/installer');