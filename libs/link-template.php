<?php 
/**
 * @fileName: link-template.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;
/**
 * Mengambil url untuk situs saat ini
 *
 * @param string $path
 * @param string $scheme
 * @return string
*/
function site_url( $path = '', $scheme = null ) {
	return get_site_url($path, $scheme);
}
/**
 * Mengambil url untuk situs tertentu
 *
 * @param string $path
 * @param string $scheme
 * @return string
*/
function get_site_url( $path = '', $scheme = null ) {
	$orig_scheme = $scheme;
	if ( !in_array( $scheme, array( 'http', 'https' ) ) ) {
		if ( ( 'account' == $scheme ) && force_ssl_admin() )
			$scheme = 'https';
		elseif ( ( 'admin' == $scheme ) && force_ssl_admin() )
			$scheme = 'https';
		else
			$scheme = ( is_ssl() ? 'https' : 'http' );
	}

	$url = get_option( 'siteurl' );

	if ( 'http' != $scheme )
		$url = str_replace( 'http://', "{$scheme}://", $url );

	if ( !empty( $path ) && is_string( $path ) && strpos( $path, '..' ) === false )
		$url .= '/' . ltrim( $path, '/' );

	return apply_filters( 'site_url', $url, $path, $orig_scheme );
}

function force_ssl_admin(){
}
/**
 * Mengambil url untuk direktori kontent
 *
 * @param string $path
 * @return string
*/
function content_url($path = '') {
	$url = content_url;
	if ( 0 === strpos($url, 'http') && is_ssl() )
		$url = str_replace( 'http://', 'https://', $url );

	if ( !empty($path) && is_string($path) && strpos($path, '..') === false )
		$url .= '/' . ltrim($path, '/');

	return apply_filters('content_url', $url, $path);
}
/**
 * Mengambil url untuk direktori plugin
 *
 * @param string $path
 * @param string $plugin
 * @return string
*/
function plugins_url($path = '', $plugin = '') {
	$url = plugin_url . '/';

	if ( 0 === strpos($url, 'http') && is_ssl() )
		$url = str_replace( 'http://', 'https://', $url );

	if ( !empty($plugin) && is_string($plugin) ) {
		$folder = dirname(plugin_basename($plugin));
		if ( '.' != $folder )
			$url .= '/' . ltrim($folder, '/');
	}

	if ( !empty($path) && is_string($path) && strpos($path, '..') === false )
		$url .= '/' . ltrim($path, '/');

	return apply_filters('plugins_url', $url, $path, $plugin);
}
/**
 * Retrieve the url to the includes directory.
 *
 * @param string $path
 * @return string
*/
function includes_url($path = '') {
	$url = site_url() . '/' . libs . '/';

	if ( !empty($path) && is_string($path) && strpos($path, '..') === false )
		$url .= ltrim($path, '/');

	return apply_filters('includes_url', $url, $path);
}