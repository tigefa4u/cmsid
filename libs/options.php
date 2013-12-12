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
	
	$sql = $db->select( "options", array('option_name' => esc_sql( $option ) ),'LIMIT 1' );	
		
	if( $db->num( $sql ) > 0 ){
		$obj = $db->fetch_obj( $sql );
		return $obj->option_value;
	/*}elseif( $db->num( $sql ) < 1 && $default == false ){
		$oops_title = "Kesalahan Table Database";
		$oops_msg = sprintf( "<p>Telah terjadi kesalahan dalam membangun hubungan ke table <code>%s</code>. Ini bisa berarti database server host Anda sedang down atau table tersebut tidak ada pada database atau terjadi kesalahan pada konfigurasi table <code>%s</code></p>
			<ul>
				<li>Apakah Anda yakin bahwa table <code>%s</code> ada di dalam database?</li>
				<li>Apakah Anda yakin Anda memiliki username dan password yang benar?</li>
				<li>Apakah Anda yakin bahwa Anda telah mengetik nama host yang benar?</li>
				<li>Apakah Anda yakin bahwa database server berjalan?</li>
			</ul>
			<p>Jika Anda tidak yakin dengan istilah tersebut Anda mungkin harus menghubungi host Anda.</p>
			", "$db->options" , "$db->options" , "$db->options");
		the_oops_message( $oops_title, $oops_msg, 'simple' );
	*/
	}
	else return $default;
		
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