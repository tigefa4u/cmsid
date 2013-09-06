<?php 
/**
 * @fileName: applications.php
 * @dir: admin/
 */
if(!defined('_iEXEC')) exit;

function dir_applications() {
	global $applications;
	
	$component = array();	
	$component_files = get_mu_apps();
	
	if ( empty($component_files) )
		return $component;

	foreach ( $component_files as $component_file ) {
		if ( !is_readable( "$component_file" ) )
			continue;
		
		$component_data = get_applications_data( "$component_file" ); 
		if ( empty ( $component_data['Name'] ) )
			continue;
			
		$component_data = array_merge_simple($component_data, array('File' => $component_file ));
		$component[get_applications_name($component_file)] = $component_data;
		//echo "$component_root/$component_file<br>";
		//echo  $component_data['Name'];
	}
	uasort( $component, create_function( '$a, $b', 'return strnatcasecmp( $a["Name"], $b["Name"] );' ));
	
	$applications = $component;

}

function get_applications_data( $file ) {

	$default_headers 	=  array(
		'Name' 			=> 'App Name',
		'URI' 			=> 'App URI',
		'Version' 		=> 'Version',
		'Description' 	=> 'Description',
		'Author' 		=> 'Author',
		'AuthorURI' 	=> 'Author URI',
	);

	$data = get_file_data( $file, $default_headers );
	return $data;
}

function get_applications_name($string){
	if( empty($string) )
		return false;
	
	if(	explode('/',$string) || explode('.php',$string)):
		$string = explode('.php',$string);
		$string = explode('/',$string[0]);
		return end($string);
	endif;
}