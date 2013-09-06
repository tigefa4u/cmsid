<?php
/**
 * @file init.php
 * @dir: admin/manage/users
 *
 */
//dilarang mengakses
if(!defined('_iEXEC')) exit;

function get_widget(){
	global $widget;
	
	$actions = $gadget = array();
	$actions[] = array(
		'title' => 'General',
		'link'  => '?admin&amp;sys=options'
	);
	$actions[] = array(
		'title' => 'Gravatar',
		'link'  => '?admin&amp;sys=options&go=gravatar'
	);
	
	$widget = array(
		'menu'		=> $actions,
		'help_desk' => 'Ganti Opsi / pengaturan pilihan anda dengan mudah'
	);
	return;
}
add_action('the_actions_menu', 'get_widget');

if(!function_exists('set_general')){
	function set_general($data){
		extract($data, EXTR_SKIP);
		
		$msg = array();
		if( empty($sitename) ) $msg[] ='<strong>ERROR</strong>: The title is empty.';
		if( empty($sitedescription) ) $msg[] ='<strong>ERROR</strong>: The description is empty.';
		if( empty($sitekeywords) ) $msg[] ='<strong>ERROR</strong>: The meta keywords is empty.';
		if( empty($admin_email) ) $msg[] ='<strong>ERROR</strong>: The e-mail address is empty.';
		if( empty($datetime_format) ) $msg[] ='<strong>ERROR</strong>: The time format is empty.';
		if( empty($author) ) $msg[] ='<strong>ERROR</strong>: The author is empty.';
		if( empty($avatar_type) ) $msg[] ='<strong>ERROR</strong>: The avatar type is empty.';
		if( empty($timezone) ) $msg[] ='<strong>ERROR</strong>: The timezone is empty.';
		
		if($msg) foreach($msg as $error) echo '<div id="error">'.$error.'</div>';
		else{
			if(save_options($data)) {
				redirect('?admin&sys=options');
			}
		}
	}
}

if(!function_exists('save_options')){
	function save_options($data){

		if ( ! is_array( $data ) )
			return false;
		
		foreach ( (array) array_keys( $data ) as $name ) {
			
			if( !checked_option( $name ) ) add_option( $name, $data[$name] );
			else set_option( $name, $data[$name] );
		}
		
	}
}

if(!function_exists('set_avatar')){
	function set_avatar($data){
		extract($data, EXTR_SKIP);
		if(save_options($data)) echo '<div id="success"><strong>SUCCESS</strong>: Opsi Avatar berhasil di perbaharui</div>';
	}
}
