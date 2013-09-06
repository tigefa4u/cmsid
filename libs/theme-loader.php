<?php 
/**
 * @fileName: theme-loader.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;

if ( defined('use_themes') && use_themes ) :
	$template = false;
	
	if( is_request() ) : $template = get_template_load( 'request' );
	elseif( is_login() ) : $template = get_template_load( 'login' );
	elseif( is_admin() ) : $template = get_template_load( 'admin' );
	else :
	ob_start();
	
	the_content_home();
		
	$the_loader = ob_get_contents();
	ob_end_clean();	
	
	$template = get_template_load( 'index' );
	
	endif;
		
	if ( $template )
		include( $template );
	return;
	
endif;