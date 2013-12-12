<?php
/**
 * @file init.php
 * @dir: admin/manage/appearance
 *
 */
//dilarang mengakses
if(!defined('_iEXEC')) exit;

global $get_group_title, $get_group_id, $get_menu_groups;

$get_group_id = 1;
if (isset($_GET['group_id'])) {
	$get_group_id = (int)$_GET['group_id'];
}

$get_group_title = dynamic_menus_group_title($get_group_id);
$get_menu_groups = dynamic_menus_groups();

function loading_ajax(){
 	echo '<div id="loading_ajax">
	<img src="'.site_url('libs/img/ajax-loader.gif').'" alt="Loading">
	Processing...
	</div>'."\n";
}


function get_widget(){
	global $widget, $get_group_title, $get_group_id;
	
	$actions = $gadget = array();
	$actions[] = array(
		'title' => 'Themes',
		'link'  => '?admin&amp;sys=appearance'
	);
	$actions[] = array(
		'title' => 'Widgets',
		'link'  => '?admin&amp;sys=appearance&amp;go=widgets'
	);
	$actions[] = array(
		'title' => 'Sidebars',
		'link'  => '?admin&amp;sys=appearance&amp;go=sidebars'
	);
	$actions[] = array(
		'title' => 'Menus',
		'link'  => '?admin&amp;sys=appearance&amp;go=menus'
	);
			
	if( file_exists( theme_path .'/' . get_option('template') . '/options/admin.php' ) ){	
		
		$actions[] = array(
			'title' => 'Setting Theme',
			'link'  => '?admin&sys=appearance&go=custom-theme'
		);
	
	}
			
	$actions[] = array(
		'title' => 'Theme Editor',
		'link'  => '?admin=single&amp;sys=appearance&amp;go=theme-editor'
	);
	
	if( get_query_var('go') == 'theme-editor' )
		$gadget[] = array('title' => 'More Files','desc' => list_files());	
	elseif( get_query_var('sys') == 'appearance' && !get_query_var('go') ){
		$gadget[] = array('title' => 'Update Baru Tersedia','desc' => info_themes_update_all());
	}
	
	if( get_query_var('go') == 'menus' ){
		$gadget[] = array('title' => 'Current Menu Group','desc' => current_menu_group($get_group_title,$get_group_id));	
		$gadget[] = array('title' => 'Add Menu','desc' => add_menu_on_group($get_group_id));	
		add_action('manager_footer','loading_ajax');
	}
	
	$widget = array(
		'menu'		=> $actions,
		'gadget'	=> $gadget,
		'help_desk' => 'Pilih dan Perbaharui tampilan website dengan mudah'
	);
	return;
}
add_action('the_actions_menu', 'get_widget');

function load_style_info( $dir, $current = false ){
	$theme_path_root = theme_path . '/';
		if (file_exists($theme_path_root . $dir . '/info.xml')) {
			$index_theme 	= @implode( '', file( $theme_path_root . $dir.'/info.xml' ) );
			$info_themes 	= str_replace ( '\r', '\n', $index_theme );
			
			preg_match( '|<themes>(.*)<\/themes>|ims'				, $info_themes, $themes 			);
			preg_match( '|<name>(.*)<\/name>|ims'					, $themes[1], $theme_name 			);
			preg_match( '|<version>(.*)<\/version>|ims'				, $themes[1], $theme_version		);
			preg_match( '|<creationDate>(.*)<\/creationDate>|ims'	, $themes[1], $theme_date 			);
			preg_match( '|<author>(.*)<\/author>|ims'				, $themes[1], $theme_author 		);
			preg_match( '|<authorEmail>(.*)<\/authorEmail>|ims'		, $themes[1], $theme_authorEmail 	);
			preg_match( '|<authorUrl>(.*)<\/authorUrl>|ims'			, $themes[1], $theme_authorUrl 		);
			preg_match( '|<copyright>(.*)<\/copyright>|ims'			, $themes[1], $theme_copyright 		);
			preg_match( '|<license>(.*)<\/license>|ims'				, $themes[1], $theme_license 		);
			preg_match( '|<description>(.*)<\/description>|ims'		, $themes[1], $theme_description 	);
			
			$screenhoot = 'content/themes/' . $dir . "/screenshot.png";
			if( !file_exists($screenhoot) ) $screenhoot = '';
			
			echo '<br style="clear:both">';
			echo '<div style="float:left; height:100%;"><img src="'.$screenhoot.'" align="left" style="border:3px solid #ccc;background:#f8f8f8 url(libs/img/icon-no-image.png) no-repeat center; width:183px; height:124px;" /></div>';
			echo '<div style="height:100%; margin-left:200px;">';
			echo '<strong>'.$theme_name[1].'</strong><br>';
			echo 'By <a target="_blank" href="'.$theme_authorUrl[1].'">'.$theme_author[1].'</a> | Version '.$theme_version[1].'<br>';
			echo $theme_description[1]."<br />";
			echo 'semua file themes ada di lokasi<br>
			      <span style="font: 500 1em/1.5em Lucida Console,courier new,monospace;">themes/'.$dir.'</span><br>';
				  
			if( $current ){
				
			$theme_option = '';			
			if( file_exists($theme_path_root . $dir . '/options/admin.php') ):
				$theme_option.= ' | <a href="?admin&sys=appearance&go=custom-theme">Setting Theme</a>';
			endif;
			
			echo '<a href="?admin=single&sys=appearance&go=theme-editor&theme=' . str_replace('/','',$dir) . '" class="button button2 blue">Customize to Theme Editor</a> <div style="line-height:25px; margin-left:160px;">OPTIONS: <a href="?admin&sys=appearance&go=widgets">Widgets</a> | <a href="?admin&sys=appearance&go=menus">Menus</a>'.$theme_option.'</div>';
			}else{
			echo '<a class="button button2 l" href="?admin&sys=appearance&act=active&theme=' . str_replace('/','',$dir) .'" onclick="return confirm(\'Are You sure active this theme?\')">Activate</a>';
			echo '<a class="button button2 m" href="?admin=full&sys=appearance&theme=' . str_replace('/','',$dir) .'&preview">Live Preview</a>';
			echo '<a class="button button2 r red" href="?admin&sys=appearance&act=delete&theme=' . str_replace('/','',$dir) . '" onclick="return confirm(\'Are You sure delete this theme?\')">Delete</a><br />';	
			}	
			echo '<br style="clear:both"/></div><br style="clear:both"/>';
		}else{
			echo '<br style="clear:both">';
			echo 'Theme Corupt';
		}
}

function themes_current(){
	load_style_info(get_option('template').'/', true);
}

if(!function_exists('get_file_name')){
	function get_file_name($string){
		return end( explode('/',$string) );
	}
}

function editing_file_themes(){
	$count = $i = 0;
	
	unset($theme);
	$rep = opendir(theme_path . get_option('template'));
	while ($file = readdir($rep)) {
		if($file != '..' && $file !='.' && $file !=''
		&& $file !='favicon.ico' && $file !='screenshot.gif' && $file !='images'
		&& !is_dir($file))
		$theme[] = $file;
		
	}
	
	closedir($rep);
	clearstatcache();
	
	return $theme;
}

function list_files(){
	$theme_path_root = theme_path . '/';
	$file_allow = array('php','css','xml','html','htm','js','txt');
	$path_dir 	= $theme_path_root . get_option('template');
	$filed		= '<ul class="list-file">';	
	if( file_exists( $path_dir.'/index.php' ) ){		
		foreach(getFilesFromDir($path_dir) as $k) {			
			$ext = end( explode( '.', $k ) );
			if ( in_array( $ext, $file_allow) ){
				$k = str_replace( $path_dir , '' , $k );
				$filed .= '<li><a href="?admin=single&sys=appearance&go=theme-editor&file='.$k.'">'.$k.'</a></li>';
			}
		}
	}else $filed.='Empty File';
	$filed .= '</ul>';
	return $filed;
}

function getFilesFromDir($dir) {

  $files = array();
  if ($handle = opendir($dir)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            if(is_dir($dir.'/'.$file)) {
                $dir2 = $dir.'/'.$file;
                $files[] = getFilesFromDir($dir2);
            }
            else {
              $files[] = $dir.'/'.$file;
            }
        }
    }
    closedir($handle);
  }

  return array_flat($files);
}

function array_flat($array) {
	$tmp = array();
	foreach($array as $a) {
			if( is_array($a) ) {
			  $tmp = array_merge($tmp, array_flat($a));
			}else {
			  $tmp[] = $a;
			}
	}
	return $tmp;
} 

if(!function_exists('del_folder_themes')){
	function del_folder_themes($path){	
		$theme_path_root = theme_path . '/';
		$path_dir = $theme_path_root . $path;
		deleteDirectory($path_dir);
		
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

if(!function_exists('info_themes_update_all')){
	function info_themes_update_all(){	
		$info = '';
		$info.= '<script type="text/javascript">
				$(document).ready(function(){
					getLoad(\'update_view\',\'?request&load=libs/ajax/latest.php&action=etc&type=themes\');	
				});	
				</script>
				<div id="update_view"></div>';
		return $info;
	}
}