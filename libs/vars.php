<?php 
/**
 * @fileName: vars.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;

$is_lynx = $is_gecko = $is_winIE = $is_macIE = $is_opera = $is_NS4 = $is_safari = $is_chrome = $is_iphone = false;

if ( isset($_SERVER['HTTP_USER_AGENT']) ) {
	if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Lynx') !== false ) {
		$is_lynx = true;
	} elseif ( stripos($_SERVER['HTTP_USER_AGENT'], 'chrome') !== false ) {
		if ( stripos( $_SERVER['HTTP_USER_AGENT'], 'chromeframe' ) !== false ) {
			if ( $is_chrome = apply_filters( 'use_google_chrome_frame', is_admin() ) )
				header( 'X-UA-Compatible: chrome=1' );
			$is_winIE = ! $is_chrome;
		} else {
			$is_chrome = true;
		}
	} elseif ( stripos($_SERVER['HTTP_USER_AGENT'], 'safari') !== false ) {
		$is_safari = true;
	} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Gecko') !== false ) {
		$is_gecko = true;
	} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'Win') !== false ) {
		$is_winIE = true;
	} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'Mac') !== false ) {
		$is_macIE = true;
	} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false ) {
		$is_opera = true;
	} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Nav') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'Mozilla/4.') !== false ) {
		$is_NS4 = true;
	}
}

if ( $is_safari && stripos($_SERVER['HTTP_USER_AGENT'], 'mobile') !== false )
	$is_iphone = true;

$is_IE = ( $is_macIE || $is_winIE );

// Server detection

/**
 * Whether the server software is Apache or something else
 * @global bool $is_apache
 */
$is_apache = (strpos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false || strpos($_SERVER['SERVER_SOFTWARE'], 'LiteSpeed') !== false);

/**
 * Whether the server software is IIS or something else
 * @global bool $is_IIS
 */
$is_IIS = !$is_apache && (strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== false || strpos($_SERVER['SERVER_SOFTWARE'], 'ExpressionDevServer') !== false);

/**
 * Whether the server software is IIS 7.X
 * @global bool $is_iis7
 */
$is_iis7 = $is_IIS && (strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS/7.') !== false);
