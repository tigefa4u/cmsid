<?php 
/**
 * @fileName: theme.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;


/**
 * Retrieve path of admin template in current or parent template.
 *
 * @return string
 */
function get_template_load( $parameter = 'index' ) {
	return get_query_template( $parameter );
}
/**
 * Retrieve path to a template
 *
 * @param string $type
 * @param array $templates
 * @return string
 */
function get_query_template( $file_template, $templates = array() ) {
	$file_template = preg_replace( '|[^a-z0-9-]+|', '', $file_template );

	if ( empty( $templates ) )
		$templates = array("{$file_template}.php");

	return locate_template( $templates );
}
/**
 * Retrieve the name of the highest priority template file that exists.
 *
 * @param string|array $template_names
 * @param bool $load
 * @param bool $require_once
 * @return string
 */
function locate_template($template_names, $load = false, $require_once = true ) {	
	$located = '';
	
	if( is_admin() || is_login() ) $template_path = admin_path;
	elseif(  is_request() ) $template_path = admin_path;
	else $template_path = template_path;
	
	$template_path_detault = theme_path .'/'. default_theme;
	
	foreach ( (array) $template_names as $template_name ) {
		
		if ( !$template_name )
			continue;
		if ( file_exists($template_path . '/' . $template_name)) {
			$located = $template_path . '/' . $template_name;
			break;
		} else if ( file_exists( $template_path_detault . $template_name) ) {
			$located = $template_path_detault . $template_name;
			break;
		}
	}
	
	if ( $load && '' != $located )
		load_template( $located, $require_once );

	return $located;
}
/**
 * Require the template file with environment.
 *
 * @param string $_template_file
 * @param bool $require_once
 */
function load_template( $_template_file, $require_once = true ) {
	global $login, $db, $version_system;

	if ( $require_once )
		require_once( $_template_file );
	else
		require( $_template_file );
}
/**
 * Retrieve current theme directory.
 *
 * @return string
 */
function get_template_directory() {
	$template = get_template();
	$theme_root = get_theme_root( $template );
	$template_dir = "$theme_root/$template";

	return apply_filters( 'template_directory', $template_dir, $template, $theme_root );
}
/**
 * Retrieve name of the current theme.
 *
 * @return string
 */
function get_template() {		
	if( isset($_SESSION['theme']) 
	&& !empty($_SESSION['theme'])
	 )
		$get_template = (string) $_SESSION['theme'];
	else	
		$get_template = get_option('template');

	return apply_filters('template', $get_template);
	
}
/**
 * Retrieve path to themes directory.
 *
 * @param string $stylesheet_or_template
 * @return string
 */
function get_theme_root( $template_name = false ) {
	
	if ( $template_name )
		$theme_root = content_path . '/themes';	
		
	return apply_filters( 'theme_root', $theme_root );
}
/**
 * Retrieve template directory URI.
 *
 * @return string
 */
function get_template_directory_uri( $display = false ) {
	$template = get_template();
	$theme_root_uri = get_theme_root_uri( $template );
	$template_dir_uri = "$theme_root_uri/$template";

	$retval = apply_filters( 'template_directory_uri', $template_dir_uri, $template, $theme_root_uri );
	
	if ( $display )
		echo $retval;
	else
		return $retval;
}
/**
 * Retrieve URI for themes directory.
 *
 * @param string $stylesheet_or_template
 * @return string
 */
function get_theme_root_uri( $template_name ) {
	
	if ( $template_name )
	$theme_root_uri = content_url( '/themes' );

	return apply_filters( 'theme_root_uri', $theme_root_uri );
}
/**
 * Menampilkan konten utama
 *
 * @return file
 */
function the_content( $dispaly = true ){	
	global $the_title, $the_desc, $the_key, $the_loader;
	
	if( $dispaly )
		echo $the_loader;
	else
		return $the_loader;
}

function the_contents(){
	
	$path = theme_path .'/'. get_option('template');
	if( $get_query = get_query_var('s') ){
		$file = $path .'/page-search.php';
		if( file_exists( $file ) ){
			require_once( $file );
			return true;
		}
	}elseif( $get_query = get_query_var('com') ){
		
		$file = $path .'/page.php';
		if( $get_query == 'page' && file_exists( $file ) ){
			require_once( $file );
			return true;
		}
		else
		{
			if( $file_application = get_application_chek( $get_query ) ){ 
				if( file_exists( application_path .'/'.  $get_query . '/functions.php' ) )
				require_once(application_path .'/'. $get_query . '/functions.php');
				
				include_once( $file_application );
				return true;
			}
			else 
			{
				add_the_content_view( (object) array('view' => '404') );
				return true;
			}
		}
	}
}
/**
 * Mengcek aplikasi
 *
 * @param $option string
 * @param $included true|false
 * @return include file | name file
 */
function get_application_chek($option_first, $manager = false, $file = 'manage'){
	
	if( $manager ) $option_second = $file;
	else $option_second = $option_first;
	
	$file_included = $option_first .'/'. $option_second .'.php';
	$file_included = application_path .'/'. $file_included;
	
	if( file_exists( $file_included ) )
		return $file_included;
}

/**
 * Mengcek system yang dimasukkan
 *
 * @param $option string
 * @param $included true|false
 * @return include file | name file
 */
function get_sys_cheked($option, $file = 'manage'){
	
	$file_included = $option .'/'.$file.'.php';
	$file_included = manage_path .'/'. $file_included;
	
	if( file_exists( $file_included ) )
	return true;
}
/**
 * Mengcek aplikasi yang dimasukkan
 *
 * @param $option string
 * @param $included true|false
 * @return include file | name file
 */
function get_apps_cheked($option_first, $manager = false, $file = 'manage'){
	
	if( $manager ) $option_second = $file;
	else $option_second = $option_first;
	
	$file_included = $option_first .'/'. $option_second .'.php';
	$file_included = application_path .'/'. $file_included;
	
	if( file_exists( $file_included ) )
	return true;
}
/**
 * Memanggil system yang dimasukkan
 *
 * @param $option string
 * @param $included true|false
 * @return include file | name file
 */
function get_sys_included($option, $file = 'manage'){
	
	$file_included = $option .'/'.$file.'.php';
	$file_included = manage_path .'/'. $file_included;
	
	if( file_exists( $file_included ) )
	include_once( $file_included );
	return;
}
/**
 * Memanggil aplikasi yang dimasukkan
 *
 * @param $option string
 * @param $included true|false
 * @return include file | name file
 */
function get_apps_included($option_first, $manager = false, $file = 'manage'){
	
	if( $manager ) $option_second = $file;
	else $option_second = $option_first;
	
	$file_included = $option_first .'/'. $option_second .'.php';
	$file_included = application_path .'/'. $file_included;
	
	if( file_exists( $file_included ) )
	include_once( $file_included );
	return;
}
/**
 * Menampilkan konten manager
 *
 * @return file
 */
function the_main_manager(){
	
	do_action('the_main_manager');
	
	if( get_sys_cheked( get_query_var('sys') ) 
	&& $values = is_sys_values() )
	{
		get_sys_included( $values, 'init' );
		get_sys_included( $values );
	}
	elseif( get_apps_cheked( get_query_var('apps'), true )
	&&  $values = is_apps_values() )
	{
		get_apps_included( $values, true, 'init' );
		get_apps_included( $values, true ); 
	}
	else 
	{
		if(get_sys_cheked( get_query_var('sys') ) == false 
		&& get_query_var('sys') 
		or get_apps_cheked( get_query_var('apps'), true ) == false 
		&& get_query_var('apps') )
		{
			header("location:?admin=oops");
			exit;
		}
		else
			get_home_included();
	}
	
}
/**
 * Memanggil system yang dimasukkan
 *
 * @param $option string
 * @param $included true|false
 * @return include file | name file
 */
function get_home_included(){
	
	$file_included = '/admin-home.php';
	$file_included = admin_path . $file_included;
	
	include_once( $file_included );
	return;
}
?>