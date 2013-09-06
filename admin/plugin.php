<?php 
/**
 * @fileName: plugins.php
 * @dir: admin/
 */
if(!defined('_iEXEC')) exit;

function get_dir_plugins( $plugin = null ) {
	
	$component_root = plugin_path .'/';
	$component = array();
	
	if( $plugin ):
	
		$component_data = get_plugin_data( "$component_root/$plugin" ); 
		$plugin_id = plugin_basename( $plugin );
		$plugin_id = explode( '/', $plugin_id );
		$plugin_id = end( $plugin_id );
		$plugin_id = explode( '/' . $plugin_id, $plugin );
		
		$component_data = array_merge( $component_data, array('id' => $plugin_id[0]) );  
		
		if ( empty ( $component_data['Name'] ) )
			continue;

		$component[plugin_basename( $component_file, $component_root )] = $component_data;
		
		return $component;
	
	else:

	// Files in icontent/plugins directory
	$component_dir = @opendir( $component_root );
	$component_files = array();
	if ( $component_dir ) {
		while (($file = readdir( $component_dir ) ) !== false ) {
			if ( substr($file, 0, 1) == '.' )
				continue;
			if ( is_dir( $component_root.$file ) ) {
				/*
				
				$plugins_subdir = @opendir( $plugin_root.$file );
				if ( $plugins_subdir ) {
					while (($subfile = readdir( $plugins_subdir ) ) !== false ) {
						if ( substr($subfile, 0, 1) == '.' )
							continue;
						if ( substr($subfile, -4) == '.php' )
							$plugin_files[] = "$file/$subfile";
					}
				}
				
				*/
					$component_files[] = $file.'/'.$file.'.php';
			} else {
				if ( substr($file, -4) == '.php' )
					$component_files[] = $file;
			}
		}
	} else {
		return $component;
	}

	@closedir( $component_dir );
	@closedir( $component_subdir );

	if ( empty($component_files) )
		return $component;

	foreach ( $component_files as $component_file ) {
		if ( !is_readable( "$component_root/$component_file" ) )
			continue;
		
		$component_data = get_plugin_data( "$component_root/$component_file" ); 
		if ( empty ( $component_data['Name'] ) )
			continue;

		$component[plugin_basename( $component_file, $component_root )] = $component_data;
		//echo "$component_root/$component_file<br>";
		//echo  $component_data['Name'];
	}
	uasort( $component, create_function( '$a, $b', 'return strnatcasecmp( $a["Name"], $b["Name"] );' ));
	
	return $component;
	
	endif;

}

function get_plugin_data( $file ) {

	$default_headers 	=  array(
		'Name' 			=> 'Plugin Name',
		'URI' 			=> 'Plugin URI',
		'Version' 		=> 'Version',
		'Description' 	=> 'Description',
		'Author' 		=> 'Author',
		'AuthorURI' 	=> 'Author URI',
	);

	$data = get_file_data( $file, $default_headers );
	return $data;
}

function get_plugins( $param = null ){	
	$json = new JSON();
	
	$active_plugins = get_option( 'active_plugins');
	$active_plugins = $json->decode( $active_plugins );
	$active_plugins = (array) $active_plugins;
	
	foreach( $active_plugins as $pluginName => $pluginStatus ){
		if( esc_sql( $param ) == $pluginName )
		return $pluginStatus;
	}
}

function get_plugins_name($string){
	if( empty($string) )
		return false;
	
	if(	explode('/',$string) || explode('.php',$string)):
		$string = explode('.php',$string);
		$string = explode('/',$string[0]);
		return $string[0];
	endif;
}