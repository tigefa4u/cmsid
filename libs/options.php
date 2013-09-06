<?php 
/**
 * @fileName: options.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;
/**
 * Memanggil pengaturan dari table options
 *
 * @param string|int $option
 * @return string|int|false
 */
function get_option( $option, $default = false ){
	global $db, $login;
	
	if( empty( $option ) )
		return false;
	
	$option	= esc_sql( $option );
	
	$sql = $db->select( "options", array('option_name' => $option),'LIMIT 1' );
	$obj = $db->fetch_obj( $sql );
		
	if( empty( $obj ) )
		return false;
		
	if( is_object($obj) )
		return $obj->option_value;
	else 
		return $default;
		
	/*
	if( empty($val) 
	&& $login->check() 
	&& $login->level('admin') 
	){
		echo '<div class="padding"><div id="message">';
		echo "Atention Option '$option' not found in table '$db->options' please check and try again or you can <a href=\"?admin&sys=options&go=fix&name=$option\">fix it</a>";
		echo '</div></div>';
		return false;
	}else{ 	
		return apply_filters( 'option_' . $option, maybe_unserialize( $val ) );
	}*/
}
/**
 * Mengecek options
 *
 * @param string|int $option
 * @return string|int|false
 */
function checked_option( $option ){
	global $db;
	
	if( empty( $option ) )
		return false;
	
	$option	= esc_sql( $option );
	
	$sql = $db->query( "SELECT COUNT(*) AS total FROM $db->options WHERE option_name='$option'" );
	$row = $db->fetch_obj( $sql );
	
	if( $row->total > 0 ) 
		return true;
	else 
		return false;
}
/**
 * Memperbaharui pengaturan dari table options
 *
 * @param string|int $option
 * @param string|int $value
 */
function set_option($option, $value = ''){
	global $db;
	
	if( empty($option) )
	return false;			
		
	$option	= esc_sql( $option );
	$value	= esc_sql( $value );
			
	if( checked_option( $option ) ){
		$db->update( "options",  array('option_value' => $value),  array('option_name' => $option) );
		return true;
	}
	return false;	
}
/**
 * Menambahkan pengaturan ke table options
 *
 * @param string|int $option
 * @param string|int $value
 */
function add_option($option, $value = ''){
	global $db;
	
	if( empty($option) )
	return false;
		
	$option	= esc_sql( $option );
	$value	= esc_sql( $value );
		
	if( !get_option($option) ){
		$db->insert( "options", array('option_value' => $value, 'option_name' => $option) );
		return true;	
	}
	return false;
}