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
	
	$gadget = array();		
	
	if( $_GET['go'] == 'edit' ){
	$gadget[] = array('title' => 'More Files','desc' => list_files() );	
	}else{
		
	if( $_GET['go'] == 'setting' ){
	$gadget[] = array('title' => 'Info','desc' => info_plugin() );
	$gadget[] = array('title' => 'Info Update','desc' => info_plugin_update());
	}else{
	$gadget[] = array('title' => 'Update Baru Tersedia','desc' => info_plugin_update_all());
	}
	
	}
	
	$widget = array(
		'gadget'	=> $gadget,
		'help_desk' => 'tool ini mempermudah anda memanage user atau akun website'
	);
	return;
}
add_action('the_actions_menu', 'get_widget');

if(!function_exists('get_file_name')){
	function get_file_name($string){
		return end( explode('/',$string) );
	}
}

function list_files(){
	$file_allow 	= array('.php','.css','.xml','.html','.htm','.js','.txt');
	$plugin_name 	= esc_sql( $_GET['plugin_name'] );
	$path_dir 		= plugin_path .'/'. $plugin_name;
	
	if( !file_exists( $path_dir ) ) $filed .= 'Empty File';
	else{	
	
	$filed		= '<ul class="list-file">';		
	foreach(rec_listFiles($path_dir) as $k) {
		if(in_array(substr($k, -5), $file_allow) 
		|| in_array(substr($k, -4), $file_allow) 
		|| in_array(substr($k, -3), $file_allow) ){
		$k = str_replace( $path_dir , '' , $k );
		$filed .= '<li><a href="?admin=single&sys=plugins&go=edit&plugin_name='.$plugin_name.'&file='.$k.'">'.$k.'</a></li>';
		}
	}
	$filed .= '</ul>';
		
	}
	
	return $filed;
}

function rec_listFiles( $from = '.'){
    if(!is_dir($from))
        return false;
    
    $files = $files2 = array();
    if( $dh = opendir($from))
    {
        while( false !== ($file = readdir($dh)))
        {
			if( $file == '.' || $file == '..')
                continue;
				
			$path = $from . '/' . $file;
			if( is_dir($path) ) $files[] = rec_listFiles($path);
			else $files[] = $from  . '/' . $file;
        }
        closedir($dh);
    }
	
	foreach($files as $k => $v) {
		if( is_array($v) ) foreach($v as $k2 => $v2) $files2[] = $v2;
		else $files2[] = $v;
	}
	
    return $files2;
}

if(!function_exists('delete_plugins')){
	function delete_plugins( $param ){		
		$json = new JSON();
		
		if(!empty($param))
		$plugin_path  	= esc_sql( $param );		
		
		$plugin_path_delete = get_plugins_name($plugin_path);
		del_folder_plugins( $plugin_path_delete );
		
		
		$plugins = '';
		$active_plugins = get_option( 'active_plugins');
		$active_plugins = $json->decode( $active_plugins );
		$active_plugins = (array) $active_plugins;
		
		foreach( $active_plugins as $pluginName => $pluginStatus ){
			if( esc_sql( $plugin_path ) != $pluginName )
			$plugins .= '"' . $pluginName . '":"' . $pluginStatus . '",';
		}
		
		$plugins = '{' . trim( $plugins, ' ,' ) . '}';
		
		set_option('active_plugins',$plugins);
	}
}
	
if(!function_exists('del_folder_plugins')){
	function del_folder_plugins($path){
		if(empty($path))
		return false;		
		
		$path_dir = plugin_path .'/'. $path;
		if( file_exists( $path_dir.'/'.$path.'.php' ) ){
			deleteDirectory($path_dir);
		}
		else unlink($path_dir.'.php');
	}
}

if(!function_exists('deleteDirectory')){
	function deleteDirectory($dir) { 
		if (!file_exists($dir)) return true; 
		if (!is_dir($dir) || is_link($dir)) return unlink($dir); 
			foreach (scandir($dir) as $item) { 
				if ($item == '.' || $item == '..') continue; 
				if (!deleteDirectory($dir . "/" . $item)) { 
					chmod($dir . "/" . $item, 0777); 
					if (!deleteDirectory($dir . "/" . $item)) return false; 
				}; 
			} 
		return rmdir($dir); 
	} 
}

if(!function_exists('update_plugins')){
	function update_plugins( $setPluginName, $setPluginStatus ){	
		$json = new JSON();
	
		$plugins = '';
		$active_plugins = get_option( 'active_plugins' );
		$active_plugins = $json->decode( $active_plugins );
		$active_plugins = (array) $active_plugins;
		
		/**/
		$add_active_plugins = false;
		foreach( $active_plugins as $pluginName => $pluginStatus ){
			if( esc_sql( $setPluginName ) == $pluginName ){
				$add_active_plugins = true;
				$plugins .= '"' . $pluginName . '":"' . filter_int( $setPluginStatus ) . '",';
			}else $plugins .= '"' . $pluginName . '":"' . $pluginStatus . '",';
		}	
		
		if( !$add_active_plugins ) 
			$plugins .= '"' . esc_sql( $setPluginName ) . '":"' . filter_int( $setPluginStatus ) . '",';
		
		$plugins_add = '{' . trim( $plugins, ' ,' ) . '}';
		
		set_option( 'active_plugins', $plugins_add );
	}
}
if(!function_exists('info_plugin')){
	function info_plugin(){	
		$plugin_name = filter_txt( $_GET['plugin_name'] );
		$file = filter_txt( $_GET['file'] );
		
		
		$plugins = get_dir_plugins( $plugin_name . $file );
		
		$info = '<div class="padding">';
		$info.= 'Name: '.$plugins['']['Name'].'<br>';
		$info.= 'Version: '.$plugins['']['Version'].'<br>';
		$info.= 'Author: '.$plugins['']['Author'].'<br>';	
		$info.= 'AuthorURI: '.$plugins['']['AuthorURI'].'<br>';
		$info.= 'Description: '.$plugins['']['Description'].'<br>';
		$info.= '</div>';
		return $info;
	}
}
if(!function_exists('info_plugin_update')){
	function info_plugin_update(){	
		$plugin_name = filter_txt( $_GET['plugin_name'] );
		$file = filter_txt( $_GET['file'] );
		
		
		$plugins = get_dir_plugins( $plugin_name . $file );
		$info = '<div class="padding">';
		$info.= '<div id="message_no_ani">No update found for '.$plugins['']['Name'].'</div>';
		$info.= '</div>';
		return $info;
	}
}
if(!function_exists('info_plugin_update_all')){
	function info_plugin_update_all(){	
		$info = '<div class="padding">';
		$info.= '<div id="message_no_ani">No update found</div>';
		$info.= '</div>';
		return $info;
	}
}