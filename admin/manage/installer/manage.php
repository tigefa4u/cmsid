<?php 
/**
 * @fileName: manage.php
 * @dir: admin/manage/installer
 */
if(!defined('_iEXEC')) exit;


global $db, $format_error, $install;

$type = filter_txt($_GET['type']);
?>

<link href="admin/manage/installer/style.css" rel="stylesheet" media="screen" type="text/css" />
<div style="margin:20px 0;">
<script type="text/javascript">
/*<![CDATA[*/
$(function(){
	//Get our elements for faster access and set overlay width
	var div = $('div.sc_menu'),
		ul = $('ul.sc_menu'),
		ulPadding = 0;
	
	//Get menu width
	var divWidth = div.width();

	//Remove scrollbars	
	div.css({overflow: 'hidden'});
	
	//Find last image container
	var lastLi = ul.find('li:last-child');
	
	//When user move mouse over menu
	div.mousemove(function(e){
		//As images are loaded ul width increases,
		//so we recalculate it each time
		var ulWidth = lastLi[0].offsetLeft + lastLi.outerWidth() + ulPadding;	
		var left = (e.pageX - div.offset().left) * (ulWidth-divWidth) / divWidth;
		div.scrollLeft(left);
	});
});
/*]]>*/
</script>
<div style="clear:both"></div>
<?php
ob_start();

if( isset($_POST['installed']) ) {
	
	$filename 	= $_FILES['zipped_required']['name'];
	$source 	= $_FILES['zipped_required']['tmp_name'];
	$type 		= $_FILES['zipped_required']['type'];
	$format_error = false;
	
	if( empty($filename) ){
		echo '<div class="padding"><div id="error_no_ani"><strong>Error :</strong> File instalasi kosong.</div></div>';
	}else{
	
	$name_file_zip = explode('.zip', $filename);
	$name_file = explode('.', $filename);
	$name_file_ext = end( $name_file );
	
	// Ensures that the correct file type was chosen.
	$accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
	foreach($accepted_types as $mime_type) {
		if($mime_type == $type) {
			$okay = true;
			break;
		} 
	}
	
	// Safari/Chrome don't register zip mime types. Something better could be used here - with the use of the PEAR extension.
	$okay = strtolower($name_file_ext) == 'zip' ? true : false;

	if( $okay ) {
	if( extracted_zip($source, 'tmp') ) {	
		$dir = opendir("tmp"); 
		while( $folder = readdir($dir) ){ 
			if( $folder == "." or $folder == ".." ){
				$format_error = true;
				continue; 
			}
			if(!preg_match ( "/[\.]/i" , $folder )){
				
				//echo $name_file_zip[0].'/'.$folder.'<br>';
				
				if( $type == 'applications' ){
					installer_applications( $type, $folder );			
					add_activity('manager_installer',"menginstall aplikasi $folder",'install');	
				}elseif( $type == 'plugins' ){
					installer_plugins( $type, $folder );					
					add_activity('manager_installer',"menginstall plugin $folder",'install');	
				}
								
				$opendir = $folder;	
			}
		}
	}else{
		echo '<div class="padding"><div id="error_no_ani"><strong>Error :</strong> Maaf file kosong</div></div>';
	}}else{
		echo '<div class="padding"><div id="error_no_ani"><strong>Error :</strong> Format tidak diizinkan!, format yang diizinkan hanya \'zip\' </div></div>';
	}
		
	if( $format_error ){
		echo '<div class="padding"><div id="error_no_ani"><strong>Error :</strong> Paket instalasi tidak sesuai dengan standar installer '.$type.'.</div></div>';
	}
	
	
}
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
<?php
$installer_array = array('applications' =>'Applications','plugins' => 'Plugins');
foreach($installer_array as $k_installer => $v_installer){
	$selected = 's';
	if( $k_installer == $type ) $selected = 'selected="selected"';
  	echo '<option value="'.$k_installer.'"'.$selected.'>'.$v_installer.'</option>';
}

?>
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
$header_menu  = '
<a href="?admin&sys=installer" class="button l"><span class="icon_head back">&laquo; Back</span></a>
<a href="?admin&sys=installer&type='.$type.'" class="button m">+ Instal Baru</a>
<a href="?admin&sys='.$type.'" class="button on r">Manage '.uc_first($type).'</a>';
}else{	
$header_menu  = '
<a href="?admin" class="button l">Dashboard</a>
<a href="?admin&sys=applications" class="button m">Manage Applications</a>
<a href="?admin&sys=plugins" class="button r">Manage Plugins</a>'; 
}

$form = 'enctype="multipart/form-data" action="" method="post"';
add_templates_manage( $content, 'Installer Manager', $header_menu, null, $form, null,'full-single' );

?>