<?php 
/**
 * @fileName: update-core.php
 * @dir: libs/ajax/
 */
if(!defined('_iEXEC')) exit;

class update_system {
	
	// base url update core info
	protected $url_server;
	protected $server;
	
	function try_xml( $url )
	{   		
		$xml = get_content($url);		
		$val = simplexml_load_string($xml);
		return $val;
	}
	
	function cheking_updates(){
		global $api_url;
		
		$print = '';
		$this->url_server = $api_url;
		
		$server = esc_sql( $this->url_server . '/repository.xml' );			
		//$this->server = simplexml_load_string( get_content($server) );	
		$this->server = $this->try_xml( $server );
		
		/*
		$set_text_check = 'Cek pembaharuan kembali &raquo;';
		if(isset($_POST['submit']) && $_POST['update'] == 'yes'){
			
		//-----------------------------------------------
			
		}else{
		
			$get_last_checked_data = get_option('last_checked_update');
			
			$print.= '<form method="post" action="">';
			if( !empty($get_last_checked_data) ){ 
			*/				
			
				$data  = $this->compare_version();
				//$print.= 'Last checked on '.datetimes( $get_last_checked_data ).'.<br><br>';
				
				if( $this->server ){
					if($data['show_content']) $print .= $this->message($data);
					else $print.= "<div id=\"message_no_ani\" style=\"margin-left:0;margin-right:0;margin-bottom:10px\">Tidak ada  update versi baru ditemukan</div>";
				
				}else{
					$print.= "<div id=\"error_no_ani\" style=\"margin-left:0;margin-right:0;margin-bottom:10px\"><strong>HTTP Salah : </strong>tidak bisa terhubung ke host</div>";
				}
				
			/*	
			}		
			$print.= '<input type="hidden" name="update" value="yes">';
			$print.= '<input type="hidden" name="last_checked_update" value="'.date('Y-m-d H:i:s').'">';			
			$print.= '<input name="submit" type="submit" value="'.$set_text_check.'" class="button primary" style="width:175px;">';
			$print.= '</form>';
		
		}
		*/
		return $print;
	}
	
	function compare_version(){	
		global $version_project, $version_system;
		
		if( 'stable' == $version_project ) $system_stable = $version_system;
		elseif( 'beta' == $version_project ) $system_beta = $version_system;
			
		$system_beta = !isset($system_beta) ? '' : $system_beta;
		$system_stable = !isset($system_stable) ? '' : $system_stable;
		$server_available_beta = !isset($this->server->available->beta) ? '' : $this->server->available->beta;
		$server_available_stable = !isset($this->server->available->stable) ? '' : $this->server->available->stable;
		
		
		$r = array();
		$r[show_content] 	= $r[show_beta] = $opt = false;
		$r[show_package] 	= $this->server->package;
		$r[system_beta] 	= $system_beta;
		$r[system_stable] 	= $system_stable;
	
		if($system_stable){
			if($server_available_stable){
				
				if(version_compare($server_available_stable,$system_stable, '>')) {
					$r[show_content] = $r[show_content_stable] = true;
					$r[server_version_stable] = $server_available_stable;
					
					if($server_available_beta)
					$r[server_version_beta] = $server_available_beta;
				}
				
				if( $server_available_beta ){
				if( version_compare($server_available_stable,$system_stable, '=') &&
					version_compare($server_available_beta,$system_stable, '>') ){
					$r[show_package] = false;
					$r[show_content] = $r[show_content_stable] = $r[show_beta] = true;
					$r[server_version_stable] = $server_available_stable;
					$r[server_version_beta] = $server_available_beta;
				}}
						
			}elseif( $server_available_beta ){
				if(version_compare($server_available_beta,$system_stable, '>')) {
					$r[show_content] = $r[show_content_stable] = $r[show_content_beta] = $r[show_beta] = true;
					$r[server_version_beta] = $server_available_beta;
				}
			}
		}elseif($system_beta){
			if( $server_available_beta ){
				
				if(version_compare($server_available_beta,$system_beta, '>')) {
					$r[show_content] = $r[show_content_beta] = $r[show_beta] = true;
					$r[server_version_beta2] = $server_available_beta;
					
					if($server_available_stable){
						$r[server_version_stable] = $server_available_stable;
						$r[server_version_beta] = $server_available_beta;
					}else
						$r[show_content_stable] = false;
					
					$opt = true;
				}
				
				if( $server_available_stable ){
				if( version_compare($server_available_beta,$system_beta, '=') &&
					version_compare($server_available_stable,$system_beta, '>') ){
					$r[show_package] = false;
					$r[show_content] = $r[show_content_beta] = $r[show_stable] = true;
					$r[server_version_beta] = $server_available_beta;
					$r[server_version_stable] = $server_available_stable;
					
					$opt = true;
				}}
				
			}
			if( $server_available_stable && $opt == false &&
				version_compare($server_available_stable, $system_beta, '>=')  ){
				
				//$r[show_package] = true;
				$r[show_content] = $r[show_content_beta] = $r[show_beta] = true;
				$r[server_version_stable] = $server_available_stable;
				
			}
			
		}else{ $r[show_content] = false; }
	
		return $r;
	}
	
	function show_message($data){
		extract($data,EXTR_SKIP);
		
		if( $show_content )
		$this->message_attention();
			
		$print = "<p style=\"line-height:20px\">";
		
		if($show_content_stable){
		
		if( $show_beta ){
			
		$print.= "<strong>Ada versi baru yang tersedia untuk di ujicoba.</strong><br>";
		$print.= datetimes( date('Y-m-d H:i:s') )."<br><br>";
		$print.= "Tidak diharuskan melakukan pembaharuan tetapi kami merekomendasikan mencoba versi beta. ";
		$print.= "Versi system dipakai adalah versi <u>stable ".$system_stable."</u>. ";	
		
		if( $server_version_beta )		
		$print.= "untuk ujicoba silahkan coba versi <u>beta ".$server_version_beta."</u> ";		
		$print.= "<br>";
		}else{
			
		$print.= "<strong>Ada versi baru yang tersedia untuk perbaharui.</strong><br>";
		$print.= datetimes( date('Y-m-d H:i:s') )."<br><br>";
		$print.= "Tidak diharuskan melakukan pembaharuan tetapi kami merekomendasikan. ";
		
		$print.= "Versi yang di pakai adalah versi <u>stable ".$system_stable."</u>. ";
		$print.= " untuk diperbaharui ke versi <u>stable ".$server_version_stable."</u> "; 
		
		if( $server_version_beta )
		$print.= " atau bisa mencoba versi <u>beta ".$server_version_beta."</u>";
		
		$print.= "<br>";
		
		$print.= "<a target=\"_blank\" href=\"".$this->url_server."/latest.zip\" class=\"button\" style=\"margin-top:10px\">Unduh Full Stable ".$server_version_stable."</a> ";
		}
		
		if( $show_package == 1 )
		$print.= "<a target=\"_blank\" href=\"".$this->url_server."/latest-pack.zip\" class=\"button\" style=\"margin-top:10px\">Unduh Pack Stable ".$server_version_stable."</a> ";
		
		if( $server_version_beta )
		$print.= "<a target=\"_blank\" href=\"".$this->url_server."/latest-beta.zip\" class=\"button\" style=\"margin-top:10px\">Unduh & Coba Beta ".$server_version_beta."</a> ";
		
		
		}
		elseif($show_content_beta){			
		
		if( $show_stable ){
			
		}elseif( $show_beta ){
			
		$print.= "<strong>Ada versi baru yang tersedia untuk diperbaharui.</strong><br>";
		$print.= datetimes( date('Y-m-d H:i:s') )."<br><br>";
		$print.= "Tidak diharuskan melakukan pembaharuan tetapi kami merekomendasikan.<br>";
		
		$print.= "Versi yang dipakai adalah versi <u>beta ".$system_beta."</u>. ";
		
		if( $server_version_stable )
		$print.= "Untuk diperbaharui ke versi <u>stable ".$server_version_stable."</u>";
		
		if( $server_version_beta )
		$print.= " atau bisa mencoba versi <u>beta ".$server_version_beta."</u>";
		elseif( $server_version_beta2 )
		$print.= " untuk bisa mencoba versi <u>beta ".$server_version_beta2."</u>";
		
		$print.= "<br>";
		
		if( $server_version_stable )
		$print.= "<a target=\"_blank\" href=\"".$this->url_server."/latest.zip\" class=\"button black\" style=\"margin-top:10px\">Unduh Full Stable ".$server_version_stable."</a> ";		
		}
		
		if( $show_package == 1 )
		$print.= "<a target=\"_blank\" href=\"".$this->url_server."/latest-pack.zip\" class=\"button black\" style=\"margin-top:10px\">Unduh Pack Stable ".$server_version_stable."</a> ";
		
		if( $server_version_beta )
		$print.= "<a target=\"_blank\" href=\"".$this->url_server."/latest-beta.zip\" class=\"button black\" style=\"margin-top:10px\">Unduh & Coba Beta ".$server_version_beta."</a> ";
		elseif( $server_version_beta2 )
		$print.= "<a target=\"_blank\" href=\"".$this->url_server."/latest-beta.zip\" class=\"button black\" style=\"margin-top:10px\">Unduh & Coba Beta ".$server_version_beta2."</a> ";
		
		}
		else $print.='Compare Error';
		$print.= "</p>";
		
		return $print;
	}

	function message_attention(){
		$print = "<p class=\"message\">";
		$print.= "<strong>Penting : </strong>sebelum ujicoba/upgrade, silahkan <a href=\"?admin&sys=backup\">backup database dan file</a> terlebih dahulu";
		$print.= "</p>";
		return $print;
	}
	
	function message($data){
		$print = '';
		$print.= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
  		$print.= '<tr>';
   		$print.= '<td width="10%" valign="top" style="vertical-align:top"><img src="libs/img/icon-download-upgrade.png" style="margin-top:2px;"></td>';
    	$print.= '<td width="60%" valign="top">';
		$print.= $this->show_message($data);		
		$print.= "</td>";
  		$print.= "</tr>";
		$print.= "</table>";
		return $print;
	}
	
	function display(){
		echo $this->cheking_updates();
	}
}

//echo base64_encode('libs/version.xml');
//echo base64_decode('aHR0cDovL2xvY2FsaG9zdC9jbXNpZC9zZXJ2ZXIvMi4xLzQv');

/*
<project>	
	<available>
		<stable>2.1.5</stable>
		<beta>2.2.1</beta>
	</available>
	<package>1</package>	
	<build>0.3.55</build>	
	<date>2012-12-21 07:30:59</date>	
</project>

$array = array(
	'project' => array(
		'availabe' => array(
			'stable' => '2.1.5',
			'beta'	=> '2.2.1'
			),
		'package' => '1'
		),
	'build' => '0.3.55'
	'date' => date('Y-m-d H:i:s')
);

echo jencode($array);
*/

global $login;

if('libs/ajax/update-core.php' == is_load_values() 
&& $login->check() 
&& $login->level('admin') 
):

$update = new update_system;
$update->display();

endif;