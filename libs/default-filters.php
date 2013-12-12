<?php 
/**
 * @fileName: default-filters.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;

foreach ( array( 'siteinfo', 'the_title' ) as $f ) {
	add_filter( $f, 'esc_html' );
}

add_action( 'do_robots', 'do_robots' );
add_action( 'the_head', 'noindex', 1 );



add_action( 'the_head', 'the_favicon' );

add_action( 'the_head', 'query_security' );
add_action( 'the_head_login', 'query_security' );
add_action( 'the_head_admin', 'query_security' );

add_action( 'the_head_admin', 'loaded_component' );
add_action( 'the_head_request', 'loaded_component');
add_action( 'the_head', 'base_js',1);

add_action( 'the_head_admin', 'aside_default' );
add_action( 'the_head_request', 'aside_default');

