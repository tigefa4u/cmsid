<?php 
/**
 * @fileName: general-template.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;

function the_favicon(){
	echo '<link rel="icon" href="'.site_url('/favicon.ico').'" type=\"image/x-icon">'."\n";
	echo '<link rel="shortcut icon" href="'.site_url('/favicon.ico').'" type="image/x-icon">'."\n";
}
/**
 * Fire the the_head action
 *
 * @calls 'the_head'
 */
function the_head() {
	do_action('the_head');
}
/**
 * Fire the the_head admin action
 *
 * @calls 'the_head'
 */
function the_head_login() {
	do_action('the_head_login');
}
/**
 * Fire the the_head admin action
 *
 * @calls 'the_head'
 */
function the_head_admin() {
	do_action('the_head_admin');
}
/**
 * Fire the the_head request action
 *
 * @calls 'the_head_request'
 */
function the_head_request() {
	do_action('the_head_request');
}
/**
 * Display or retrieve page title for all areas of blog.
 *
 * @param string $sep
 * @param bool $display
 * @param string $seplocation
 * @return string|null
 */
function the_title($display = true) {
	$title = esc_sql( $GLOBALS['the_title'] );
	
	if( empty($title) ) 
		$title = get_info( 'name' );
	
	$title = apply_filters( 'the_title', $title );
	
	if ( $display )
		echo $title;
	else
		return $title;
}
/**
 * Display the language attributes for the html tag.
 *
 * @param string $doctype
 */
function language_attributes($doctype = 'html') {
	$attributes = array();
	$output = '';

	if ( function_exists( 'is_rtl' ) )
		$attributes[] = 'dir="' . ( is_rtl() ? 'rtl' : 'ltr' ) . '"';

	if ( $lang = get_info('language') ) {
		if ( get_option('html_type') == 'text/html' || $doctype == 'html' )
			$attributes[] = "lang=\"$lang\"";

		if ( get_option('html_type') != 'text/html' || $doctype == 'xhtml' )
			$attributes[] = "xml:lang=\"$lang\"";
	}

	$output = implode(' ', $attributes);
	$output = apply_filters('language_attributes', $output);
	echo $output;
}
/**
 * Retrieve information about the blog.
 *
 * @param string $show
 * @param string $filter
 * @return string
 */
function get_info( $show = '', $display = false, $filter = 'display' ) {

	switch( $show ) {
		case 'home' :
		case 'siteurl' :
		case 'url' :
			$output = home_url();
			break;
		case 'description':
			$output = esc_sql( $GLOBALS['the_desc'] );
			
			if( empty($output) )
			$output = get_option('sitedescription');
			
			break;
		case 'keywords':
			$output = esc_sql( $GLOBALS['the_key'] );
			
			if( empty($output) )
			$output = get_option('sitekeywords');
			
			break;
		case 'rss_url':
			//isi dengan url rss
			//$output = get_feed_link('rss');
			break;
		case 'pingback_url':
			$output = get_option('siteurl') .'/?pingback';
			break;
		case 'template_directory':
		case 'stylesheet_url':
		case 'template_url':
			$output = get_template_directory_uri();
			break;
		case 'admin_email':
			$output = get_option('admin_email');
			break;
		case 'charset':
			$output = get_option('site_charset');
			if ('' == $output) $output = 'UTF-8';
			break;
		case 'html_type' :
			$output = get_option('html_type');
			break;
		case 'version':
			global $the_version;
			$output = $the_version;
			break;
		case 'language':
			$output = get_locale();
			$output = str_replace('_', '-', $output);
			break;
		case 'text_direction':
			if ( function_exists( 'is_rtl' ) ) {
				$output = is_rtl() ? 'rtl' : 'ltr';
			} else {
				$output = 'ltr';
			}
			break;
		case 'desc':
			$output = get_option('sitedescription');
			break;
		case 'name':
		default:
			$output = get_option('sitename');
			break;
	}

	$url = true;
	if (strpos($show, 'url') === false &&
		strpos($show, 'directory') === false &&
		strpos($show, 'home') === false)
		$url = false;

	if ( 'display' == $filter ) {
		if ( $url )
			$output = apply_filters('siteinfo_url', $output, $show);
		else
			$output = apply_filters('siteinfo', $output, $show);
	}

	if ( $display )
		echo $output;
	else
		return $output;
}
/**
 * Checks if current locale is RTL.
 *
 * @since 3.0.0
 * @return bool Whether locale is RTL.
 */
function is_rtl() {
	$text_direction = 'ltr';
	return 'rtl' == $text_direction;
}
/**
 * Display or retrieve page title admin for all areas.
 *
 * @param string $sep
 * @param bool $display
 * @param string $seplocation
 * @return string|null
 */
function the_admin_title($display = true) {
	
	$title = the_title_manager();	
	$title = apply_filters( 'the_title', $title );
	
	if ( $display )
		echo $title;
	else
		return $title;
}
/**
 * Menampilkan judul manager
 *
 * @return file
 */
function the_title_manager(){
	
	$title = 'Administrator &gt; ';
	
	if( $value = is_sys_values() )
		$title.= uc_first( $value );
		
	elseif( $value = is_apps_values() )
		$title.= uc_first( $value );
		
	else $title = 'Dashboard';
	
	return $title;
	
}
/**
 * Display or retrieve page title auth for all areas.
 *
 * @param string $sep
 * @param bool $display
 * @param string $seplocation
 * @return string|null
 */
function the_login_title($display = true) {
	
	$title = the_login_manager();	
	$title = apply_filters( 'the_title', $title );
	
	if ( $display )
		echo $title;
	else
		return $title;
}
/**
 * Menampilkan judul auth manager
 *
 * @return file
 */
function the_login_manager(){
	
	$title = '';
	
	if( $value = is_go_values() )
		$title.= uc_first( $value );
		
	if( !empty($title) )
		$title = 'Login &gt; ' . $title;
	else
		$title = 'Login';
	
	return $title;
	
}

/**
 * Renders an editor.
 *
 * @param string $content
 * @param string $editor_id
 * @param array $settings
 */
function the_editor( $content, $editor_id, $setting_area = array(), $setting_js = array() ) {
		
	if ( ! class_exists( 'wysywigEditor' ) )
		require( abs_path .  libs . '/class-editor.php' );
	
	wysywigEditor::editor($content, $editor_id, $setting_area, $setting_js);
}
/**
 * Display a noindex meta tag if required by the blog configuration.
 */
function noindex() {
	if ( '0' == get_option('site_public') )
		no_robots();
}
/**
 * Display a noindex meta tag.
 */
function no_robots() {
	echo "<meta name='robots' content='noindex,nofollow' />\n";
}