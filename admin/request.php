<?php 
/**
 * @fileName: request.php
 * @dir: admin/
 */
if(!defined('_iEXEC')) exit;

the_head_request();

global $login;

/**
 * updateing request dashboard setup
 */
if($login->check() && 'dashboard' == is_request_values() 
){
	set_dashboard_admin( $_POST["data"] );
}

if( $get_load 	= is_load_values() 

or( $get_load 	= is_load_values() 
&&  $get_apps 	= is_apps_values() )

or( $get_load 	= is_load_values() 
&&  $get_plg 	= is_plg_values() ) )
{

	if( is_load() && is_apps() && $get_apps = 'yes' ) $file = application_path .'/'. $get_load;	
	elseif( is_load() && is_plg() && $get_plg = 'yes' ) $file = plugin_path .'/'. $get_load;
	elseif( is_load() ) $file = abs_path . $get_load;	
		
	if( file_exists( $file ) )
		include( $file );
		
}
elseif( $get_redirect = is_redirect_values() )
{

	$base_url = esc_sql( $get_redirect );

	if (!headers_sent()){ 
			//header('HTTP/1.1 404 Not Found');
			header('Location: '.$base_url); exit;
	}else{ 
			echo '<script type="text/javascript">';
			echo 'window.location.href="'.$base_url.'";';
			echo '</script>';
			echo '<noscript>';
			echo '<meta http-equiv="refresh" content="0;url='.$base_url.'" />';
			echo '</noscript>'; exit;
	}

}