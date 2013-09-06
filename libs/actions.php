<?php 
/**
 * @fileName: actions.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;
/**
 * Execute functions hooked on a specific action hook.
 *
 * @param string $tag
 * @param mixed $arg
 * @return null
 */
function do_action($tag, $arg = '') {
	global $filter, $actions, $merged_filters, $current_filter;

	if ( ! isset($actions) )
		$actions = array();

	if ( ! isset($actions[$tag]) )
		$actions[$tag] = 1;
	else
		++$actions[$tag];

	// Do 'all' actions first
	if ( isset($filter['all']) ) {
		$current_filter[] = $tag;
		$all_args = func_get_args();
		_call_all_hook($all_args);
	}

	if ( !isset($filter[$tag]) ) {
		if ( isset($filter['all']) )
			array_pop($current_filter);
		return;
	}

	if ( !isset($filter['all']) )
		$current_filter[] = $tag;

	$args = array();
	if ( is_array($arg) && 1 == count($arg) && isset($arg[0]) && is_object($arg[0]) ) // array(&$this)
		$args[] =& $arg[0];
	else
		$args[] = $arg;
	for ( $a = 2; $a < func_num_args(); $a++ )
		$args[] = func_get_arg($a);

	// Sort
	if ( !isset( $merged_filters[ $tag ] ) ) {
		ksort($filter[$tag]);
		$merged_filters[ $tag ] = true;
	}

	reset( $filter[ $tag ] );

	do {
		foreach ( (array) current($filter[$tag]) as $the_ )
			if ( !is_null($the_['function']) )
				call_user_func_array($the_['function'], array_slice($args, 0, (int) $the_['accepted_args']));

	} while ( next($filter[$tag]) !== false );

	array_pop($current_filter);
}
/**
 * Hooks a function on to a specific action.
 *
 * @param string $tag
 * @param callback $function_to_add
 * @param int $priority
 * @param int $accepted_args
 */
function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
	return add_filter($tag, $function_to_add, $priority, $accepted_args);
}
/**
 * Hooks a function or method to a specific filter action.
 *
 * @param string $tag
 * @param callback $function_to_add
 * @param int $priority
 * @param int $accepted_args
 * @return boolean true
 */
function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
	global $filter, $merged_filters;

	$idx = _filter_build_unique_id($tag, $function_to_add, $priority);
	$filter[$tag][$priority][$idx] = array('function' => $function_to_add, 'accepted_args' => $accepted_args);
	unset( $merged_filters[ $tag ] );
	return true;
}