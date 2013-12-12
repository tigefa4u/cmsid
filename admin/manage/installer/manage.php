<?php 
/**
 * @fileName: manage.php
 * @dir: admin/manage/installer
 */
if(!defined('_iEXEC')) exit;


global $db, $format_error, $install;

$type_install = filter_txt($_GET['type']);
?>
<div style="clear:both"></div>
<?php
ob_start();

if( isset($_POST['installed']) ) {
	
	if( empty($type_install) || !empty($_POST['installer']) )
	$type_install = filter_txt($_POST['installer']);
	
	$filename 	= $_FILES['zipped_required']['name'];
	$source 	= $_FILES['zipped_required']['tmp_name'];
	$type 		= $_FILES['zipped_required']['type'];
	$format_error = false;
	
	if( empty($type_install) ){
		echo '<div class="padding"><div id="error_no_ani">Pilih instalasi belum dipilih.</div></div>';
	}else{	
	
	if( empty($filename) ){
		echo '<div class="padding"><div id="error_no_ani"><strong>Error :</strong> File instalasi kosong.</div></div>';
	}else{
	
		$name_file_zip = explode('.zip', $filename);
		$name_file = explode('.', $filename);
		$name_file_ext = end( $name_file );
		
		// Ensures that the correct file type was chosen.
		$accepted_types = array('application/octet-stream','application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
		foreach($accepted_types as $mime_type) {
			if($mime_type == $type) {
				$okay = true;
				break;
			} 
		}
		
		if( $okay ){		
			$path ='tmp';
			if( extracted_zip($source, $path) ) {	
				$dir = opendir($path); 
				while( $folder = readdir($dir) ){ 
					if( $folder != '.' && $folder != '..' 
					|| !preg_match ( "/[\.]/i" , $folder ) ){
						if( $type_install == 'applications' ){
							installer_moved_to( 'applications', $path, $folder );
						}elseif( $type_install == 'plugins' ){
							installer_moved_to( 'plugins', $path, $folder );
						}elseif( $type_install == 'themes' ){
							installer_moved_to( 'themes', $path, $folder );
						}else $format_error = true;
					}
				}
			}else{
				echo '<div class="padding"><div id="error_no_ani"><strong>Error :</strong> Maaf file kosong</div></div>';
			}
			
			//jika struktur direktori tidak sesuai
			if( $format_error ){
				echo '<div class="padding"><div id="error_no_ani"><strong>Error :</strong> Paket instalasi tidak sesuai dengan standar installer '.$type_install.'.</div></div>';
			}
		}else{
			echo '<div class="padding"><div id="error_no_ani"><strong>Error :</strong> Format tidak diizinkan!, format yang diizinkan hanya \'zip\' </div></div>';
		}
	
	}}
	echo '<br>';

}else{
delete_directory( 'tmp' );
?>

<div class="padding">
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="116" rowspan="3" style="padding-right:20px; padding-top:10px;"><img src="libs/img/icon-installer.png"/></td>
    <td colspan="3">&nbsp;</td>
    </tr>
  <tr>
    <td width="106">Pilih Instalasi</td>
    <td width="7"><strong>:</strong></td>
    <td width="723"><select name="installer" id="installer">
  	<option value="">--Pilih instalasi--</option>
  	<option value="applications"<?php if( $type_install == 'applications' ){echo ' selected';}?>>Aplikasi</option>
  	<option value="plugins"<?php if( $type_install == 'plugins' ){echo ' selected';}?>>Plugin</option>
  	<option value="themes"<?php if( $type_install == 'themes' ){echo ' selected';}?>>Themes</option>
	</select></td>
  </tr>
  <tr>
    <td>File Source</td>
    <td><strong>:</strong></td>
    <td><input  id="zipped_required" type="file" name="zipped_required"/></td>
  </tr>
  <tr>
    <td style="padding-right:20px; padding-top:10px;">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</div>
<div class="num" style="text-align:left;">
<input type="submit" name="installed" value="Start Install &raquo;&raquo;" class="button on l blue" /><input type="reset" name="Reset" value="Clear" class="button r"  />
</div>
<?php
}
$content = ob_get_contents();
ob_end_clean();



if( isset($_POST['installed']) ) {

$type = esc_sql( $_POST['installer'] );
if( $type == 'themes' ) $type = 'appearance';

if( empty($_POST['installer']) ){
$header_menu  = '<a href="?admin&sys=installer" class="button" style="margin-top:3px;"><span class="icon_head back">&laquo; Back</span></a>';
}else{
$header_menu  = '<a href="?admin&sys=installer" class="button l"><span class="icon_head back">&laquo; Back</span></a><a href="?admin&sys=installer&type='.$type.'" class="button m">+ Instal Baru</a><a href="?admin&sys='.$type.'" class="button on r">Manage '.uc_first($type).'</a>';
}
}else{	
$header_menu  = '<a href="?admin" class="button l">Dashboard</a><a href="?admin&sys=applications" class="button m">Manage Applications</a><a href="?admin&sys=plugins" class="button r">Manage Plugins</a>'; 
}

$form = 'enctype="multipart/form-data" action="" method="post"';
add_templates_manage( $content, 'Installer Manager', $header_menu, null, $form, null,'full-single' );

?>