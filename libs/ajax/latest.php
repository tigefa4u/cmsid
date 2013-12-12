<?php
/**
 * @file release.php
 *
 */
//dilarang mengakses
if(!defined('_iEXEC')) exit;

global $login, $api_url, $version_system, $version_project, $version_beta;

if( 'libs/ajax/latest.php' == is_load_values() 
&& $login->check() 
&& $login->level('admin') ):

$api_version = 2;

function template_major_download($data){
	$x  = '<li><div><strong>' . $data['name'].'</strong>';
	$x .= '<div style="clear:both;"></div>';
	$x .= 'Versi: ' . $data['code'];
	$x .= ', By: ' . $data['author'];
	$x .= ', <a href="' . $data['url'].'" target="_blank">Unduh</a>';
	$x .= '<div style="clear:both; padding-bottom:5px;"></div></li>';
	return $x;
}

function message_attention(){
	$print = "<p class=\"message\">";
	$print.= "<strong>Penting : </strong>sebelum ujicoba/upgrade, silahkan <a href=\"?admin&sys=plugins&go=setting&plugin_name=backup&file=/backup.php\">backup database dan file</a> terlebih dahulu";
	$print.= "</p>";
	return $print;
}

function show_message($data){
		extract($data,EXTR_SKIP);
			
		$print = "";
		$print.= message_attention();
		$print.= "<p>";
		$print.= "Tidak diharuskan melakukan pembaharuan tetapi kami menyarankan. ";
		$print.= "<br><br>";
			
		$print.= "Versi yang di pakai adalah versi <u>".$version_system."</u>. ";
		
		if( $version_project || $version_beta > 0 )
		$print.= "<u>".$version_project." ".$version_beta."</u>";
		
		$print.= " silahkan perbaharui dengan versi full ke versi <u>".$version_server."</u> ";		
		
		if( $version_server_name || $version_server_beta > 0 )
		$print.= "<u>".$version_server_name." ".$version_server_beta."</u>";	
		
		if( $show_package > 0 )
		$print.= " atau gunakan paket perbaikan yang tersedia untuk memperbaharui";
		
		$print.= "<br>";
		
		$print.= "<a target=\"_blank\" href=\"".$server_url."\" class=\"button\" style=\"margin-top:10px\">Dapatkan pembaruan terbaru</a> ";
		$print.= "<div style=\"clear:both;\"></div><br>";
		$print.= "</p>";
		
		$print.= "";
		
		return $print;
}

	
function message($data){
	$print.= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
  	$print.= '<tr>';
   	$print.= '<td width="10%" valign="top" style="vertical-align:top"><img src="libs/img/icon-download-upgrade.png"></td>';
    $print.= '<td width="80%" valign="top">';
	$print.= "<p>";		
	$print.= "<strong>Ada versi baru yang tersedia untuk perbaharui.</strong><br>";
	$print.= datetimes( date('Y-m-d H:i:s') );
	$print.= "</p>";		
	$print.= '</td>';
  	$print.= '</tr>';
  	$print.= '<tr>';
  	$print.= '<td colspan="2" valign="top" style="vertical-align:top">';	
	$print.= show_message($data);
	$print.= '</td>';
  	$print.= '</tr>';
	$print.= '</table>';
	return $print;
}
	
switch( $_GET[action] ){
default:

$server = "/?v=$api_version&content=release";
$server = get_content( esc_sql( $api_url . $server ) );
$data 	= json_decode( $server, TRUE );

if( count($data[content]) > 0 && $server ):
	$xy = $data[content];
	
	$package = !isset($xy[package]) ? '' : $xy[package];
	$versi_system = !isset($version_system) ? '' : $version_system;
	$versi_server = !isset($xy[version][code]) ? '' : $xy[version][code];
	$versi_server_beta = !isset($xy[version][release][code]) ? '' : $xy[version][release][code];
	$release = !isset($xy[version][release][name]) ? '' : $xy[version][release][name];
	
	if( $versi_server_beta > 0 )
	$versi_server_beta_x = $versi_server_beta;
		
	$r = array();
	$r[show_content] = false;
	$r[show_package] = $package;
	$r[version_system] = $version_system;
	$r[version_server] = $xy[version][code];
	$r[version_server_name] = $release;
	$r[version_server_beta] = $versi_server_beta_x;
	$r[version_project] = $version_project;
	$r[version_beta] = $version_beta;
	$r[server_url] = $xy[url];
	
	if( 'stable' == $version_project ){
		if( $versi_server > $version_system ){
			$r[show_content] = true;
		}
	}elseif( 'beta' == $version_project ){
		if( $release == 'beta'
		&&  $versi_server >= $versi_system ){
			$r[show_content] = true;
			if( $versi_server == $versi_system 
			&& $versi_server_beta_x <= $version_beta )
				$r[show_content] = false;
			
		}elseif( $release == 'stable'
		&&  $versi_server >= $versi_system ){
			$r[show_content] = true;
		}
	}
	
	if( $server ){
		if( $r['show_content'] ){
			$print = message($r);
		}else{
			$print = "<div id=\"message_no_ani\" style=\"margin-left:0;margin-right:0;margin-bottom:10px\">Tidak ada  update versi baru yang ditemukan</div>";
		}
	}else{
		$print = "<div id=\"error_no_ani\" style=\"margin-left:0;margin-right:0;margin-bottom:10px\"><strong>HTTP Salah : </strong>tidak bisa terhubung ke host</div>";
	}
	
	echo $print;
else:
	echo '<div class="padding"><p id="error_no_ani">Error 404: Server not found</p></div>';
endif;

break;
case 'etc':
$type = esc_sql( filter_txt( $_GET['type'] ) );
$key_id = esc_sql( filter_txt( $_GET['key_id'] ) );

$add_key_id = '';
if( $key_id ) $add_key_id = "&key_id=$key_id";
		
$server = "/?v=$api_version&content=release&action=etc&type=$type$add_key_id";
$server = get_content( esc_sql( $api_url . $server ) );
$data 	= json_decode( $server, TRUE );

$li = '';
$show = $show_li = true;

if( count($data[content]) > 0 && $server ):

$check_applications = get_dir_applications();
$check_plugins = get_dir_plugins();
$check_themes = get_dir_themes();

$data = array_multi_sort($data[content], array('date' => SORT_DESC));
foreach( $data as $xy){	
	$data_check = template_major_download( array(
					'name' 		=> $xy['version']['name'],
					'code' 		=> $xy['version']['code'],
					'author' 	=> $xy['author'],
					'url' 		=> $xy['url']
					));
	
	if( $type == 'app' ){
		foreach($check_applications as $key => $val){
			if($xy['version']['api_key'] ==  $val['APIKey']
			&& $xy['version']['code'] > $val['Version'] ){
				$li .= $data_check;
			}
		}
	}elseif( $type == 'plugin' ){
		foreach($check_plugins as $key => $val){
			if($xy['version']['api_key'] ==  $val['APIKey']
			&& $xy['version']['code'] > $val['Version'] ){
				$li .= $data_check;
			}
		}
	}elseif( $type == 'themes' ){
		foreach($check_themes as $key => $val){
			if($xy['version']['api_key'] ==  $val['apiKey']
			&& $xy['version']['code'] > $val['version'] ){
				$li .= $data_check;
			}
		}
	}
}

$ul = '<ul class="sidemenu">';
$ul.= $li . '</ul>';

else:
	$li = true;
	$ul = '<div class="padding"><p id="error_no_ani">Error 404: Server not found</p></div>';
endif;
		
if( $show && empty($li) ) $ul = '<div class="padding"><p id="message_no_ani">Update not found</p></div>';

echo $ul;

break;
}
endif;