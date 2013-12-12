<?php 
/**
 * @fileName: query.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;

/**
 * Is the query
 * string @parameter
 * @return bool
 */
function query( $parameter = 'index' ) {	
	
	if( isset($_GET[$parameter]) )
		return true;
		
}

/**
 * Is the query a login (returns no results)?
 *
 * @return bool
 */
function is_login() {
	return query( 'login' );
}
/**
 * Is the query a admin (returns no results)?
 *
 * @return bool
 */
function is_admin() {
	return query( 'admin' );
}
/**
 * Is the query a admin value (returns no results)?
 *
 * @return bool
 */
function is_admin_values() {
	if( $values = get_query_var('admin') )
		return $values;		
}
/**
 * Is the query a request (returns no results)?
 *
 * @return bool
 */
function is_request() {
	return query( 'request' );	
}
/**
 * Is the query a request value (returns no results)?
 *
 * @return bool
 */
function is_request_values() {
	if( $values = get_query_var('request') )
		return $values;		
}
/**
 * Is the query a load (returns no results)?
 *
 * @return bool
 */
function is_load() {	
	return query( 'load' );	
}
/**
 * Is the query a load (returns no results)?
 *
 * @return bool
 */
function is_load_values() {	
	if( $values = get_query_var('load') )
		return $values;	
}
/**
 * Is the query a apps (returns no results)?
 *
 * @return bool
 */
function is_apps() {	
	return query( 'apps' );	
}
/**
 * Is the query a apps value (returns no results)?
 *
 * @return bool
 */
function is_apps_values() {	
	if( $values = get_query_var('apps') )
	return $values;	
}/**
 * Is the query a apps (returns no results)?
 *
 * @return bool
 */
function is_plg() {	
	return query( 'plg' );	
}
/**
 * Is the query a apps value (returns no results)?
 *
 * @return bool
 */
function is_plg_values() {	
	if( $values = get_query_var('plg') )
	return $values;	
}
/**
 * Is the query a go (returns no results)?
 *
 * @return bool
 */
function is_go_values() {	
	if( $values = get_query_var('go') )
		return $values;	
}
/**
 * Is the query for a feed?
 *
 * @param string|array $feeds
 * @return bool
 */
function is_feed() {
	return query( 'feed' );	
}
/**
 * Is the query for the robots file?
 *
 * @return bool
 */
function is_robots() {
	return query( 'robot' );	
}
/**
 * Is the query a sys (returns no results)?
 *
 * @return bool
 */
function is_sys() {
	return query( 'sys' );
}
/**
 * Is the query a sys value (returns no results)?
 *
 * @return bool
 */
function is_sys_values() {	
	if( $values = get_query_var('sys') )
	return $values;	
}
/**
 * Is the query a query value string 
 *
 * @return bool
 */
function is_query_values() {
	if( isset( $_SERVER['QUERY_STRING'] ) ) 
		return $_SERVER['QUERY_STRING'];
}
/**
 * Retrieve variable in the Query class.
 *
 * @param string $var
 * @return mixed
 */
function get_query_var( $var ) {
	//global $query;

	//return $query->get($var);
	return apply_filters('get_query_var',$_GET[$var]);
}
/**
 * Retrieve variable in the Query class.
 *
 * @param string $var
 * @return mixed
 */
function set_query_var( $var, $value ) {
	//global $query;

	//return $query->get($var);
	$_GET[$var] = apply_filters('get_query_var',$value);
}
?>