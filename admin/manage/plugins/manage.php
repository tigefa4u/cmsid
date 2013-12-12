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
$plugin_name  	= filter_txt($_GET['plugin_name']);
?>
<?php

switch($go){
default:

ob_start();
?>
<link href="admin/manage/plugins/style.css" rel="stylesheet" media="screen" type="text/css" />
<?php
function plugin_init( $plugin_name ){	
	$pn = explode('/',$plugin_name);
	
	if( file_exists( plugin_path . '/' . $pn[0] . '/init.php' ) )
		include plugin_path . '/' . $pn[0] . '/init.php';
}

if( $act == 'pub' && !empty($act) ){
	if ($pub == 'no') $stat = 0;	
	if ($pub == 'yes') $stat = 1;
	update_plugins($plugin_name, $stat);
	
	plugin_init( $plugin_name );
	
	if( $pub == 'yes' )
	do_action('plugin_install');
		
	add_activity('manager_plugins',"memperbaharui plugin $plugin_name menjadi $stat", 'plugin');
	redirect('?admin&sys=plugins');
}

if( $act == 'del' && !empty($plugin_name) ){	
	plugin_init( $plugin_name );
	do_action('plugin_uninstall');
		
	delete_plugins($plugin_name); 
	add_activity('manager_plugins',"menghapus plugin $plugin_name", 'plugin');
	redirect('?admin&sys=plugins');
}

if( isset($_POST['submit']) ) {
	$plugin_path 	= $_POST['plugin_path'];
	$action 		= $_POST['action'];
	
	if(is_array($plugin_path))	
	foreach($plugin_path as $key){
		if ($action == 'delete'):
			plugin_init( $key );
			do_action('plugin_uninstall');
	
			delete_plugins($key); 
			add_activity('manager_plugins',"menghapus plugin $key", 'plugin');
		else:
		if ($action == 'deactive') $stat = 0;
		if ($action == 'active'){
			$stat = 1;	
			plugin_init( $key );
			do_action('plugin_install');
		}
		
		update_plugins($key, $stat);
		add_activity('manager_plugins',"memperbaharui plugin $key menjadi $stat", 'plugin');
		
		endif;
	}
}

?>
<div id="list-comment">
<table id=table cellpadding="0" cellspacing="0" widtd="100%">
    <tr class="head" style="border-bottom:0;">
		<td style="text-align:left; width:1%; vertical-align:middle; padding-left:5px"><input type="checkbox" onClick="checkbox_all()" id="set_checkbox_all"></td>
		<td style="text-align:left; width:40%">Plugin</td>
		<td style="text-align:left">Description</td>
	</tr>
<?php
$warna = $warna2 = '';
$checkbox_id 	= 0;
$plugins 	= get_dir_plugins();
foreach($plugins as $key => $val){
$name 		= get_plugins_name($key);
$warna 		= empty ($warna) ? ' bgcolor="background:#f9f9f9"' : '';
$key2 		= str_replace( $name .'/', '' , $key );

if( $checkbox_id == count($plugins) -1 ):
$warna2 = ' style="border-bottom:0;"';
$warna2x = ' border-bottom:0;';
endif;

//if(get_plugins($name)==$ps):
?>
	<tr class="isi"<?php echo $warna;?>>
	  <td style="text-align:left; vertical-align:top; padding-left:5px; padding-top:2px;<?php echo $warna2x;?>"><input type="checkbox" name="plugin_path[]" value="<?php echo $key?>" id="checkbox_id_<?php echo $checkbox_id;?>" /></td>
	  <td style="vertical-align:top;<?php echo $warna2x;?>"><p><strong><?php echo $val['Name']?></strong></p>
      <div style="clear:both"></div>
      <?php 
	  $style_button = 'l';	  
	  if( get_plugins( $key ) == 1 ){
	  if(file_exists( plugin_path .'/'. $name . '/admin.php' ) && !empty($name) ){
	  	$style_button = 'm';
	  	echo '<a href="?admin&sys=plugins&go=setting&plugin_name='.$name.'&file=/'.$key2.'" title="Setting this plugins" class="button button2 l green">Setting</a>';
	  }
	  echo '<a href="?admin&sys=plugins&act=pub&pub=no&plugin_name='.$key.'" title="Deactive this plugin" class="button button2 orange '.$style_button.'">Deactive</a>';
	  }else{
	  
	  echo '<a href="?admin&sys=plugins&act=pub&pub=yes&plugin_name='.$key.'" title="Activate this plugin" class="button button2 blue '.$style_button.'">Activate</a>';
	  }
	  
	  echo '<a href="?admin=single&sys=plugins&go=edit&plugin_name='.$name.'&file=/'.$key2.'" title="Open this file in the Plugin Editor" class="button button2 m">Edit File</a><a href="?admin&sys=plugins&act=del&plugin_name='.$name.'" title="Delete this plugin" onclick="return confirm(\'Are You sure delete this plugins?\')" class="button button2 r red">Delete</a>';
	  ?>      
      </td>
	  <td<?php echo $warna2;?>><p><?php echo $val['Description']?></p>
      <div style="clear:both"></div>
      Version <?php echo $val['Version']?> | By <a target="_blank" href="<?php echo $val['AuthorURI']?>" title="Visit autdor homepage"><?php echo $val['Author']?></a> | <a target="_blank" href="<?php echo $val['URI']?>" title="Visit plugin site">Visit plugin site</a>
      
      </td>
    </tr>
<?php
$checkbox_id++;
//endif;
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
$header_menu.= '<select name="action" class="left"><option value="active">Di Aktifkan</option><option value="deactive">Di Non Aktifkan</option><option value="delete">Di Hapus</option></select>
<input type="submit" name="submit" class="primary button on m" value="Go" id="checkbox_go"><input name="Reset" type="reset" value="Clear" class="button r">';
$header_menu.= '</div>';
$header_menu.= '<a href="?admin&sys=installer&type=plugins" class="button">+ Plugin baru</a>';
$footer = 'Total Plugin : '.$checkbox_id;

add_templates_manage( $content, 'Plugins Manager', $header_menu, null, $form, $footer );

break;
case 'edit':

ob_start();
?>
<link href="admin/manage/plugins/style.css" rel="stylesheet" media="screen" type="text/css" />
<?php
$path_dir = plugin_path . '/';
if( file_exists( $path_dir . $plugin_name . $file ) && isset($file))
	$edit_file = $path_dir . $plugin_name . $file;	
elseif( file_exists( $path_dir . $plugin_name . "/$plugin_name.php" ) )
	$edit_file = $path_dir . $plugin_name . "/$plugin_name.php";	
else
	$edit_file = $path_dir . "/$plugin_name.php";

if (isset($_POST['submit'])) {
	$file = $_POST['file'];
	echo '<div class="padding">';
	save_to_file($file);
	echo '</div>';
	add_activity('manager_plugins',"mengubah file plugin $file dengan file editor",'write');
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

$form = 'action="" method="post" enctype="multipart/form-data"';
$header_menu = '<div class="header_menu_top">';
$header_menu.= '<input type="submit" name="submit" class="button on l blue" value="Save & Update"><input name="Reset" type="reset" value="Reset" class="button r"></div>';
$header_menu.= '<a href="?admin&sys=plugins" class="button"><span class="icon_head back">&laquo; Back</span></a>';
add_templates_manage( $content, 'Editor Manager', $header_menu, null, $form );

break;
case'setting':

$plugins 	= get_dir_plugins( $plugin_name . $file );

if( file_exists( plugin_path .'/'. $plugin_name . '/admin.php' ) && !empty($plugin_name) ){
	include plugin_path .'/'. $plugin_name . '/admin.php';
}else{
	//tampilkan tombol direct dan pesan
	//redirect('?admin&sys=plugins'); 
	$oops_title = "Plugin tidak ditemukan!";
	$oops_msg = "Ups maaf plugin \"<strong>$plugin_name</strong>\" belum terinstall, silahkan unduh di situs pengembang";
	the_oops_message( $oops_title, $oops_msg, 'simple', true, 'notfound' );
}

break;
}
?>

