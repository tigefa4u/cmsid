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
		$gadget[] = array('title' => 'Update Baru Tersedia','desc' => info_app_update_all());
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
	$app_name	 	= esc_sql( $_GET['app_name'] );
	$path_dir 		= application_path .'/'. $app_name;
	
	if( !file_exists( $path_dir ) ) $filed .= 'Empty File';
	else{	
	
	$filed		= '<ul class="list-file">';		
	foreach(rec_listFiles($path_dir) as $k) {
		if(in_array(substr($k, -5), $file_allow) 
		|| in_array(substr($k, -4), $file_allow) 
		|| in_array(substr($k, -3), $file_allow) ){
		$k = str_replace( $path_dir , '' , $k );
		$filed .= '<li><a href="?admin=single&sys=applications&go=edit&app_name='.$app_name.'&file='.$k.'">'.$k.'</a></li>';
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

if(!function_exists('delete_applications')){
	function delete_applications( $plugin_path ){			
		del_folder_applications( esc_sql( $plugin_path ) );
	}
}
	
if(!function_exists('del_folder_applications')){
	function del_folder_applications($path){
		
		$path_dir = application_path . $path;
		if( file_exists( $path_dir . '/' . $path . '.php' ) ){
			deleteDirectory($path_dir);
		}else{
			if( file_exists( $path_dir . '.php' ) )
			unlink($path_dir . '.php');
		}
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

if(!function_exists('info_app_update_all')){
	function info_app_update_all(){	
		$info = '<div class="padding">';
		$info.= '<div id="message_no_ani">No update found</div>';
		$info.= '</div>';
		return $info;
	}
}