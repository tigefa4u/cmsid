<?php 
/**
 * @fileName: manage.php
 * @dir: admin/manage/users
 */
if(!defined('_iEXEC')) exit;
global $db, $login, $widget;
$go 			= filter_txt($_GET['go']);
$act			= filter_txt($_GET['act']);
$to				= filter_txt($_GET['to']);
$offset			= filter_int($_GET['offset']);
$pub			= filter_txt($_GET['pub']);
$path  			= filter_txt($_GET['path']);
$file 			= filter_txt($_GET['file']);
$app_name  		= filter_txt($_GET['app_name']);

$applications = get_dir_applications();

switch($go){
default:
?>
<link href="admin/manage/applications/style.css" rel="stylesheet" media="screen" type="text/css" />
<?php

ob_start();

if( $act == 'del' && !empty($app_name) ){
	delete_applications($app_name); 
	add_activity('manager_applications',"menghapus aplikasi $app_name",'app');
}

if( isset($_POST['submitDelete']) ) {
	$plugin_path 	= $_POST['plugin_path'];
	
	if(is_array($plugin_path))	
	foreach($plugin_path as $key){
		delete_applications($key); 			
		add_activity('manager_applications','menghapus aplikasi','app');
	}
}
if( isset($_POST['submitDelete']) || $act == 'del' && !empty($app_name)  ) {
	redirect('?admin&sys=applications');
}

?>
<div id="list-comment">
<table id=table cellpadding="0" cellspacing="0" widtd="100%">
    <tr class="head" style="border-bottom:0;">
		<td style="text-align:left; width:1%; vertical-align:middle; padding-left:5px"><input type="checkbox" onClick="checkbox_all()" id="set_checkbox_all"></td>
		<td style="text-align:left; width:35%">Applications</td>
		<td style="text-align:left">Description</td>
	</tr>
<?php
$warna = '';
$checkbox_id 	= 0;
foreach($applications as $key => $val){
$warna 	= empty ($warna) ? ' style="background:#f9f9f9"' : '';

?>
	<tr class="isi" <?php echo $warna;?>>
	  <td style="text-align:left; vertical-align:middle; padding-left:5px"><input type="checkbox" name="plugin_path[]" value="<?php echo $name?>" id="checkbox_id_<?php echo $checkbox_id;?>" /></td>
	  <td><p><strong><?php echo $val['Name']?></strong></p>
      <div style="clear:both"></div>
      <?php 
	  $style_button = 'l';
	  if(file_exists( application_path .'/'. $key . '/manage.php' ) && !empty($key)){
	  $style_button = 'm';
	  echo '<a href="?admin&apps='.$key.'" title="Manage this application" class="button button2 l green">Manage</a>';
	  }
	  
	  $path_dir = '/' . $key .'/';
	  //$path_dir = str_replace( '//', '/' , $path_dir );
	  //$key2 = str_replace( $path_dir, '' , $key );	  
	  $key2 = explode($path_dir,$val['File']);
	  
	  echo '<a href="?admin=single&sys=applications&go=edit&app_name='.$key.'&file=/'.$key2[1].'" title="Open this file in the Plugin Editor" class="button button2 '.$style_button.'">Edit File</a><a href="?admin&sys=applications&act=del&app_name='.$key.'" title="Delete this plugin" onclick="return confirm(\'Are You sure delete this applications?\')" class="button button2 r red">Delete</a>';
	  ?>      
      </td>
	  <td><p><?php echo $val['Description']?></p>
      <div style="clear:both"></div>
      Version <?php echo $val['Version']?> | By <a target="_blank" href="<?php echo $val['AuthorURI']?>" title="Visit autdor homepage"><?php echo $val['Author']?></a> | <a target="_blank" href="<?php echo $val['URI']?>" title="Visit application site">Visit application site</a>
      
      </td>
    </tr>
<?php
$checkbox_id++;
}
?>
</table>
</div>
<input type="hidden" id="checkbox_total" value="<?php echo $checkbox_id?>" name="checkbox_total">
<?php
$content = ob_get_contents();
ob_end_clean();

$form = 'action="" method="post" enctype="multipart/form-data"';
$header_menu = '<div class="header_menu_top">';
$header_menu.= '<input type="submit" name="submitDelete" class="primary button on l red" value="Delete the Checked" id="checkbox_go">';
$header_menu.= '<input name="Reset" type="reset" value="Cencel" class="button r">';
$header_menu.= '</div>';
$header_menu.= '<a href="?admin&sys=installer&type=applications" class="button">+ New</a>';
add_templates_manage( $content, 'Applications Manager', $header_menu, null, $form );

break;
case 'edit':

ob_start();
?>
<link href="admin/manage/applications/style.css" rel="stylesheet" media="screen" type="text/css" />
<?php
$path_dir = application_path .'/';
if( file_exists( $path_dir . $app_name . $file ) && isset($file))
	$edit_file = $path_dir . $app_name . $file;	
elseif( file_exists( $path_dir . $app_name . "/$plugin_name.php" ) )
	$edit_file = $path_dir . $app_name . "/$plugin_name.php";	
else
	$edit_file = $path_dir . "/$plugin_name.php";

if (isset($_POST['submit'])) {
	$file = $_POST['file'];	
	echo '<div class="padding">';
	save_to_file($file);
	echo '</div>';
	add_activity('manager_applications',"mengubah file aplikasi $file dengan file editor",'write');
}
?>
<div class=num style="text-align:left; height:30px; line-height:30px; border-bottom:1px solid #ddd;">
File : <?php echo get_file_name($edit_file)?>
</div>

<input type="hidden" name="file" value="<?php echo $edit_file?>">
<textarea id="textcode" name="content" style="width:98.5%; height:450px">
<?php echo htmlspecialchars(file_get_contents( $edit_file ))?>
</textarea>

<?php
$content = ob_get_contents();
ob_end_clean();

$form = 'action="" method="post" enctype="multipart/form-data" name="form1"';
$header_menu = '<div class="header_menu_top">';
$header_menu.= '<input type="submit" name="submit" class="button on l blue" value="Save & Update"><input name="Reset" type="reset" value="Reset" class="button r"></div>';
$header_menu.= '<a href="?admin&sys=applications" class="button button3"><span class="icon_head back">&laquo; Back</span></a>';
add_templates_manage( $content, 'Editor Manager', $header_menu, null, $form );

break;
}
?>

