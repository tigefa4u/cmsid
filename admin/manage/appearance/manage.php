<?php 
/**
 * @fileName: manage.php
 * @dir: admin/manage/appearance
 */
if(!defined('_iEXEC')) exit;
global $db, $widget, $registered_widgets, $registered_sidebars, $sidebars_widgets, $registered_widget_controls;

$go 		= filter_txt($_GET['go']);
$act		= filter_txt($_GET['act']);
$order		= filter_txt($_GET['order']);
$custom		= filter_txt($_GET['custom']);
$id			= filter_int($_GET['id']);
$parent_id	= filter_int($_GET['parent_id']);
$file   	= filter_txt($_GET['file']);
$theme   	= filter_txt($_GET['theme']);
$pub		= filter_txt($_GET['pub']);
$app_id  	= filter_txt($_GET['app_id']);
$sidebar_id = filter_txt($_GET['sidebar_id']);
$widget_id  = filter_txt($_GET['widget_id']);
$widget_key = filter_int($_GET['widget_key']);

$applications = get_dir_applications();

switch($go){
default:

if( isset($_GET['theme']) && isset($_GET['preview']) ){
$_SESSION['theme'] = esc_sql($theme);
ob_start();
?>
<style>
@media only screen and (-webkit-min-device-pixel-ratio: 0) and (min-device-width: 1025px) {
div.nav > div.nav-top {
	min-width:1024px;
	width: 100%;
	margin:0 auto;
}
.gd.full-single {
	min-width:1024px;
	width: 100%;
	margin:0 auto;
}
}
@media only screen and (-webkit-min-device-pixel-ratio: 0) and (max-device-width: 1025px) {
div.nav > div.nav-top {
	min-width:980px;
	width: 90%;
	margin:0 auto;
}
.gd.full-single {
	min-width:980px;
	width: 90%;
	margin:0 auto;
}
}

.gd-menu{
	margin-top:5px;
}
</style>
<iframe class="shadow-inside" src='<?php echo site_url('/')."?theme=$theme";?>' style="width:100%; min-height:500px;" frameborder="0" scrolling="auto"></iframe>
<?php
$content = ob_get_contents();
ob_end_clean();

$header_menu = '
<a href="?admin&sys=appearance&act=active&theme='.$theme.'" class="button button2 l orange" onclick="return confirm(\'Are You sure current this theme?\')">Activate</a>';
$header_menu.= '<a class="button button2 m red" href="?admin&sys=appearance&act=delete&theme=' . $theme . '" onclick="return confirm(\'Are You sure delete this theme?\')">Delete</a>';
$header_menu.= '<a href="?admin&sys=appearance" class="button button2 r">Cencel</a>'; 
	
add_templates_manage( $content, 'Theme Live Preview : <span style="color:green">'.$theme.'</span>', $header_menu, null, null, null, 'full-single' );

}else{
	
if( $_SESSION['theme'] ){
	$_SESSION['theme'] = '';
unset(
	$_SESSION['theme']
	);
}

ob_start();
?>
<div class="padding">
<?php
if(isset($act) && !empty($act)){
if($act == 'active' && !empty($theme) ){
	set_option('template',$theme);
	add_activity('theme',"Menerapkan themes $theme", 'appearance');
	redirect('?admin&sys=appearance');
}
if( $act == 'delete' && !empty($theme) ){
	del_folder_themes($theme); 
	add_activity('theme',"menghapus themes $theme", 'appearance');
	redirect('?admin&sys=appearance');
}}

?>
<strong>Curent Theme</strong>
<div style="border-top:1px solid #eeeeee; margin-bottom:10px;">
<?php themes_current();?>
</div>
<div style="clear:bolth"></div>
<strong>Available theme</strong>
<div style="border-bottom:1px solid #eeeeee"></div>
</div>
<?php 
$count_available = $count_available_all = 0;
$theme_arr = themes_available();

$warna = 'fff';
if ( @count($theme_arr) > 1 ){
	foreach($theme_arr as $file){
		if( $file != get_option('template') ):
			$warna  = ($warna == 'fff') ? 'f8f8f8' : 'fff';
			echo "<div style=\"background:#$warna; padding:0 5px;\">";
			load_style_info($file);
			$count_available++;
			echo '</div>';
		endif;
		$count_available_all++;
	}
}else{	
?>
<div class="padding">
<p>Theme not available</p>
</div>
<?php 
}
?>

<?php 
$content = ob_get_contents();
ob_end_clean();

add_templates_manage($content,'Appearance Manager');
}

break;
case'widgets':
?>
<link href="<?php echo site_url('admin/manage/appearance/css/style.css');?>" rel="stylesheet" media="screen" type="text/css" />
<?php
ob_start();

if( $act == 'order' ){

$sidebars = array();
$data_sidebars_widgets_x = get_option('sidebar_widgets');
$data_sidebars_widgets_x = json_decode($data_sidebars_widgets_x);
foreach($data_sidebars_widgets_x as $data_sidebar_id => $data_widgets){
	
	if( $data_sidebar_id == $sidebar_id ){
		$widgets = array();
		foreach($data_widgets as $_widget_key => $_widget_value){
			if( $order == 'up' ):
			
				$_widget_key_up = $widget_key;
				$_widget_key_down = ( $widget_key - 1 );
				
				$_widget_key_up_change = ( $_widget_key - 1 );
				$_widget_key_down_change = ( $_widget_key + 1 );
				
				if( $_widget_key_up == $_widget_key )
					$widgets[$_widget_key_up_change] = $_widget_value;
				elseif( $_widget_key_down == $_widget_key )
					$widgets[$_widget_key_down_change] = $_widget_value;
				else
					$widgets[$_widget_key] = $_widget_value;
					
			elseif( $order == 'down' ):
			
				$_widget_key_up = $widget_key;
				$_widget_key_down = ( $widget_key + 1 );
				
				$_widget_key_up_change = ( $_widget_key + 1 );
				$_widget_key_down_change = ( $_widget_key - 1 );
				
				if( $_widget_key_up == $_widget_key )
					$widgets[$_widget_key_up_change] = $_widget_value;
				elseif( $_widget_key_down == $_widget_key )
					$widgets[$_widget_key_down_change] = $_widget_value;
				else
					$widgets[$_widget_key] = $_widget_value;
					
			endif;
		}
		
		ksort($widgets); 
		$sidebars[$data_sidebar_id] = $widgets;
	}else{
		$widgets = array();
		foreach($data_widgets as $_widget_key => $_widget_value){
			$widgets[] = $_widget_value;
		}
		$sidebars[$data_sidebar_id] = $widgets;
	}
}
$sidebars = json_encode($sidebars);

set_option('sidebar_widgets', $sidebars);
add_activity('widget',"Memperbarui widget dengan mengurutkan $widget_id' ke '$order' ", 'appearance');
redirect('?admin&sys=appearance&go=widgets');
}

if( $act == 'clean' ){
	if( checked_option( 'sidebar_widgets' )  )
		set_option('sidebar_widgets', '');
	else
		add_option('sidebar_widgets', '');
		
	redirect('?admin&sys=appearance&go=widgets');
}

if( $act == 'del' ){

$sidebars = array();
$data_sidebars_widgets_x = get_option('sidebar_widgets');
$data_sidebars_widgets_x = json_decode($data_sidebars_widgets_x);
foreach($data_sidebars_widgets_x as $data_sidebar_id => $data_widgets){
	
	if( $data_sidebar_id == $sidebar_id ){
		$widgets = array();
		foreach($data_widgets as $_widget_key => $_widget_value){
			if( $_widget_value == $widget_id  ){
				if( $_widget_key != $widget_key ){
					$widgets[] = $_widget_value;
				}
			}else{
				$widgets[] = $_widget_value;
			}
		}
		$sidebars[$data_sidebar_id] = $widgets;
	}else{
		$widgets = array();
		foreach($data_widgets as $_widget_value){
			$widgets[] = $_widget_value;
		}
		$sidebars[$data_sidebar_id] = $widgets;
	}
}



$sidebars = json_encode($sidebars);

set_option('sidebar_widgets', $sidebars);
add_activity('widget',"Menghapus widget $widget_id dari sidebar $sidebar_id", 'appearance');
redirect('?admin&sys=appearance&go=widgets');

}

$widget_availabe = array();
$data_sidebars_widgets = get_sidebars_widgets();
	
if ( empty( $data_sidebars_widgets ) )
	return false;
	
foreach ( (array) $data_sidebars_widgets as $sidebar_id_x => $widgets ) {
		
	foreach( $widgets as $widget_key => $widget_id_x ){
		$widget_availabe[] = array(
			'widget_key' => $widget_key,
			'widget_nums' => count( $widgets ) - 1,
			'widgets' => $registered_widgets[$widget_id_x],
			'sidebar_id' => $sidebar_id_x,
			'sidebar_name' => $registered_sidebars[$sidebar_id_x]['name']
		);
	}
}

?>
<table id="table" cellpadding="0" cellspacing="0">
<tr class="head">
    <td width="43%" class="depan"><strong>Widget</strong></td>
    <td class="depan"><strong>Sidebar</strong></td>
    <td class="depan"><div align="center"><strong>Order</strong></div></td>
    <td class="depan"><div align="center"><strong>Action</strong></div></td>
  </tr>
<?php
$warna = '';
foreach ( (array) $widget_availabe as $widgets ) {	
$warna 	= empty ($warna) ? ' bgcolor="#f1f6fe"' : '';

if( !empty($widgets['widgets']['name']) ):
$widget_id = $widgets['widgets']['id'];	

$ordering_down = '<a href="?admin&sys=appearance&go=widgets&act=order&order=down&sidebar_id='.$widgets['sidebar_id'].'&widget_id='.$widget_id.'&widget_key='.$widgets['widget_key'].'" class="down" title="down">down</a>';    
$ordering_up = '<a href="?admin&sys=appearance&go=widgets&act=order&order=up&sidebar_id='.$widgets['sidebar_id'].'&widget_id='.$widget_id.'&widget_key='.$widgets['widget_key'].'" class="up" title="up">up</a>'; 		       
					
if( $widgets['widget_key'] == 0 ) $ordering_up = '';
if( $widgets['widget_key'] == $widgets['widget_nums'] ) $ordering_down = '';

?>
<tr <?php echo $warna?> class="isi">
	<td valign="top"><span title="<?php echo $widgets['widgets']['name']?>"><?php echo $widgets['widgets']['name']?></span></td>
	<td valign="top"><?php echo $widgets['sidebar_name']?></td>
    <td valign="top"><div class="action"><?php echo $ordering_up.' '.$ordering_down?></div></td>
    <td valign="top">
    <div class="action">
<a href="?admin&sys=appearance&go=widgets&act=del&sidebar_id=<?php echo $widgets['sidebar_id']?>&widget_id=<?php echo $widget_id?>&widget_key=<?php echo $widgets['widget_key']?>" class="delete" title="delete" onclick="return confirm('Are You sure delete widget <?php echo $widgets['widgets']['name']?>?')">delete</a>
    </div></td>
</tr>
<?php
endif;

}
?>
</table>
<?php

$content = ob_get_contents();
ob_end_clean();

ob_start();
?>
<div class="padding">
<?php
$widget_manual_title = 'Add New Widgets';

if( isset($_POST['submit']) ){
	
$sidebar_available = esc_sql( $_POST['sidebar_available'] );
$witgets_available = esc_sql( $_POST['witgets_available'] );

$sidebars = array();
$data_sidebars_widgets_x = get_option('sidebar_widgets');
$data_sidebars_widgets_x = json_decode($data_sidebars_widgets_x);
foreach($data_sidebars_widgets_x as $data_sidebar_id => $data_widgets){
	
	if( $data_sidebar_id == $sidebar_available ){
		$widgets = array();
		foreach($data_widgets as $_widget_value){
			$widgets[] = $_widget_value;
		}
		$widgets[] = $witgets_available;			
		$sidebars[$data_sidebar_id] = $widgets;
	}else{
		$widgets = array();
		foreach($data_widgets as $_widget_value){
			$widgets[] = $_widget_value;
		}		
		$sidebars[$data_sidebar_id] = $widgets;
	}
}

$sidebars = json_encode($sidebars);

set_option('sidebar_widgets', $sidebars);
echo "<div id='success'>Memasang Widget berhasil.</div>";
add_activity('widget',"Menambahkan widget '$witgets_available' pada sidebar '$sidebar_available'", 'appearance');
redirect('?admin&sys=appearance&go=widgets');

}
?>
<form action="" method="post">
<label for="sidebar_available">Sidebar Available :</label><br />
  <select name="sidebar_available">
<?php
foreach ( $registered_sidebars as $sidebar => $registered_sidebar ) {
	if ( false !== strpos( $registered_sidebar['class'], 'inactive-sidebar' ) || 'orphaned_widgets' == substr( $sidebar, 0, 16 ) )
		continue;
?>    
	<option value="<?php echo $registered_sidebar['id']?>"><?php echo $registered_sidebar['name']?></option>
<?php 
}
?>
  </select><br />

<label for="witgets_available">Widgets Available :</label><br />
  <select name="witgets_available">
<?php
$sort = $registered_widgets;
usort( $sort, '_sort_name_callback' );
$done = array();

foreach ( $sort as $widget ) {
	if ( in_array( $widget['callback'], $done, true ) ) // We already showed this multi-widget
		continue;

	$done[] = $widget['callback'];
		
	if ( ! isset( $widget['params'][0] ) )
		$widget['params'][0] = array();	
		
	$widget_id_replace = explode('-',$widget['id']);
	$widget_id_replace = $widget_id_replace[0];			
?>
    <option value="<?php echo $widget_id_replace?>"><?php echo $widget['name']?></option>
<?php
}
?>
  </select><br/><br/>
  <input name="submit" type="submit" value="Add" />
</form>
</div>
<?php
$content_widget = ob_get_contents();
ob_end_clean();


$header_menu = '<div class="header_menu_top">';
$header_menu.= '<a href="?admin&sys=appearance&go=widgets&act=clean" class="button" onclick="return confirm(\'Are You sure delete and clean data widgets sidebars\')">Clean Data</a></div>';

$widget_manual = array();
$widget_manual['gadget'][] = array('title' => $widget_manual_title, 'desc' => $content_widget);

add_templates_manage($content, 'Widgets Manager', $header_menu, $widget_manual);

break;
case'sidebars':
ob_start();

if( $act == 'pub' ){
	
	$sidebar_action_op = get_option('sidebar_actions');
	$sidebar_action_op = json_decode( $sidebar_action_op );
	
	$sidebar_actions = array();
	foreach ( (array) $sidebar_action_op as $app => $sidebar ) {
		if( $app == $app_id ){
			$sidebars = array();
			foreach($sidebar as $_sidebar_key => $_sidebar_value){	
				if( $_sidebar_key == $sidebar_id ){
					
					$stat = 0;
					if ($pub == 'no') $stat = 0;	
					if ($pub == 'yes') $stat = 1;
					
					$sidebars[$_sidebar_key] = $stat;	
				}else	
					$sidebars[$_sidebar_key] = $_sidebar_value;
			}	
			
			$sidebar_actions[$app] = $sidebars;
		}else{
			$sidebars = array();
			foreach($sidebar as $_sidebar_key => $_sidebar_value){
				$sidebars[$_sidebar_key] = $_sidebar_value;
			}	
			$sidebar_actions[$app] = $sidebars;
		}
	}
	
	$sidebar_actions = json_encode($sidebar_actions);
		
	set_option('sidebar_actions', $sidebar_actions);
	add_activity('sidebar',"Memperbaharui status pub menjadi $pub sidebar ".$registered_sidebars[$sidebar_id]['name']." pada app ".$applications[$app_id]['Name'], 'appearance');
	redirect('?admin&sys=appearance&go=sidebars');
}

if( $act == 'clean' ){
	if( checked_option( 'sidebar_actions' )  )
		set_option('sidebar_actions', '');
	else
		add_option('sidebar_actions', '');
		
	redirect('?admin&sys=appearance&go=sidebars');
}
if( $act == 'del' ){
	
	$sidebar_action_op = get_option('sidebar_actions');
	$sidebar_action_op = json_decode( $sidebar_action_op );
	
	$sidebar_actions = array();
	foreach ( (array) $sidebar_action_op as $app => $sidebar ) {
		if( $app == $app_id ){
			$sidebars = array();
			foreach($sidebar as $_sidebar_key => $_sidebar_value){	
				if( $_sidebar_key != $sidebar_id )						
					$sidebars[$_sidebar_key] = $_sidebar_value;
			}	
			
			$sidebar_actions[$app] = $sidebars;
		}else{
			$sidebars = array();
			foreach($sidebar as $_sidebar_key => $_sidebar_value){
				$sidebars[$_sidebar_key] = $_sidebar_value;
			}	
			$sidebar_actions[$app] = $sidebars;
		}
	}
	
	$sidebar_actions = json_encode($sidebar_actions);
		
	set_option('sidebar_actions', $sidebar_actions);
	echo "<div id='success'>Menghapus Sidebar berhasil.</div>";
	add_activity('sidebar',"Menghapus sidebar ".$registered_sidebars[$sidebar_id]['name']." pada app ".$applications[$app_id]['Name'], 'appearance');
	redirect('?admin&sys=appearance&go=sidebars');
}

$sidebar_action_op = get_option('sidebar_actions');
$sidebar_action_op = json_decode( $sidebar_action_op );
$sidebars = array();
foreach ( (array) $sidebar_action_op as $app => $sidebar ) {
	foreach($sidebar as $sidebar_id => $status){		
		$sidebars[] = array(
		'app_id' => $app, 
		'app_name' => $applications[$app]['Name'], 
		'app_desc' => $applications[$app]['Description'], 
		'sidebar_id' => $sidebar_id,
		'sidebar_name' => $registered_sidebars[$sidebar_id]['name'],
		'status' => $status );
	}
}
?>
<table id="table" cellpadding="0" cellspacing="0">
<tr class="head">
    <td width="43%" class="depan"><strong>Apps</strong></td>
    <td class="depan"><strong>Sidebar</strong></td>
    <td class="depan"><div align="center"><strong>Status</strong></div></td>
    <td class="depan"><div align="center"><strong>Action</strong></div></td>
  </tr>
<?php

$warna = '';
$sidebars = array_multi_sort($sidebars, array('app_name' => SORT_ASC,'sidebar_id' => SORT_ASC));
foreach ( (array) $sidebars as $k => $sidebar ) {	
$warna 	= empty ($warna) ? ' bgcolor="#f1f6fe"' : '';	

$status 	= ($sidebar['status'] == 1) ? '<a  class="enable" title="Disable Now" href="?admin&sys=appearance&go=sidebars&act=pub&pub=no&app_id='.$sidebar['app_id'].'&sidebar_id='.$sidebar['sidebar_id'].'">Enable</a>' : '<a  class="disable" title="Enable Now" href="?admin&sys=appearance&go=sidebars&act=pub&pub=yes&app_id='.$sidebar['app_id'].'&sidebar_id='.$sidebar['sidebar_id'].'">Disable</a>';      

?>
<tr <?php echo $warna?> class="isi">
	<td valign="top"><span title="<?php echo $sidebar['app_desc']?>"><?php echo $sidebar['app_name']?></span></td>
	<td valign="top"><?php echo $sidebar['sidebar_name']?></td>
    <td valign="top"><div class="action"><?php echo $status;?></div></td>
    <td valign="top">
    <div class="action">
<a href="?admin&sys=appearance&go=sidebars&act=del&app_id=<?php echo $sidebar['app_id'];?>&sidebar_id=<?php echo $sidebar['sidebar_id'];?>" class="delete" title="delete" onclick="return confirm('Are You sure delete widget <?php echo $sidebar['sidebar_name']?>?')">delete</a>
    </div></td>
</tr>
<?php
}
?>
</table>
<?php

$content = ob_get_contents();
ob_end_clean();

ob_start();
?>
<div class="padding">
<?php
	
if( isset($_POST['submit']) ){
	
$apps_available = esc_sql( $_POST['apps_available'] );
$sidebar_available = esc_sql( $_POST['sidebar_available'] );

$add = false;
$apps = array();
$sidebar_actions = array();
$sidebar_action_op = get_option('sidebar_actions');
if( empty($sidebar_action_op) ){
	$add = true;
	$sidebars[$sidebar_available] = 0;
	$sidebar_actions[$apps_available] = $sidebars;
}else{
	$sidebar_action_op = (array) json_decode( $sidebar_action_op );
	foreach ( $sidebar_action_op as $app => $sidebar ) {
		$apps[] = $app; 
		//jika app tidak sama dengan app box
		if( $app != $apps_available ){
			$sidebars = array();
			foreach($sidebar as $_sidebar_key => $_sidebar_value){						
				$sidebars[$_sidebar_key] = $_sidebar_value;
			}
			$sidebar_actions[$app] = $sidebars;	
		}else{
			$add = true;
			$sidebars = array();
			foreach($sidebar as $_sidebar_key => $_sidebar_value){
				if( $_sidebar_key == $sidebar_available )
					$add = false;
						
				$sidebars[$_sidebar_key] = $_sidebar_value;
			}
				
			if( $add )
			$sidebars[$sidebar_available] = 0;
					
			$sidebar_actions[$apps_available] = $sidebars;
		}
	}
}
	
if( !in_array($apps_available,$apps) ){
	$add = true;
	$sidebars = array();
	$sidebars[$sidebar_available] = 0;
	$sidebar_actions[$apps_available] = $sidebars;
}

if( !$add ){
	echo "<div id='error'>Sidebar ".$registered_sidebars[$sidebar_available]['name']." pada app ".$applications[$apps_available]['Name']." sudah ada.</div>";
}else{
	
	$sidebar_actions = json_encode($sidebar_actions);
	set_option('sidebar_actions', $sidebar_actions);
	echo "<div id='success'>Memasang Sidebar berhasil.</div>";
	add_activity('sidebar',"Menambahkan action sidebar ".$registered_sidebars[$sidebar_available]['name']." pada app ".$applications[$apps_available]['Name'], 'appearance');
	redirect('?admin&sys=appearance&go=sidebars');
}

}
?>
<form action="" method="post">
<label for="sidebar_available">Sidebar Available :</label><br />
  <select name="sidebar_available">
<?php
foreach ( $registered_sidebars as $sidebar => $registered_sidebar ) {
	if ( false !== strpos( $registered_sidebar['class'], 'inactive-sidebar' ) || 'orphaned_widgets' == substr( $sidebar, 0, 16 ) )
		continue;
?>    
	<option value="<?php echo $registered_sidebar['id']?>"><?php echo $registered_sidebar['name']?></option>
<?php 
}
?>
  </select><br />

<label for="apps_available">Apps Available :</label><br />
  <select name="apps_available">
<?php
foreach ( $applications as $k => $app ) {	
?>
    <option value="<?php echo $k?>"><?php echo $app['Name']?></option>
<?php
}
?>
  </select><br />
  <input name="submit" type="submit" value="Add" />
</form>
</div>
<?php
$content_widget = ob_get_contents();
ob_end_clean();


$header_menu = '<div class="header_menu_top">';
$header_menu.= '<a href="?admin&sys=appearance&go=sidebars&act=clean" class="button" onclick="return confirm(\'Are You sure delete and clean data action sidebars\')">Clean Data</a></div>';

$widget_manual = array();
$widget_manual['gadget'][] = array('title' => 'Add New Action', 'desc' => $content_widget);

add_templates_manage($content, 'Sidebar Manager', $header_menu, $widget_manual);

break;
case'theme-editor':

ob_start();

$path_dir = theme_path .'/'. get_option('template');

if( file_exists( $path_dir . $file ) && isset($file))
	$edit_file = $path_dir . $file;	
else
	$edit_file = $path_dir.'/index.php';

if (isset($_POST['submit'])) {
	$file = $_POST['file'];
	echo '<div class="padding">';
	save_to_file($file);
	echo '</div>';
	add_activity('theme','editing theme using theme editor','write');
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
$header_menu.= '<input type="submit" name="submit" class="button on l green" value="Save & Update">';
$header_menu.= '<input name="Reset" type="reset" value="Reset" class="button r black">';
$header_menu.= '</div>';
$header_menu.= '<a href="?admin&sys=appearance" class="button"><span class="icon_head back">&laquo; Back</span></a>';
add_templates_manage( $content, 'Themes Editor', $header_menu, null, $form );

break;
case'menus':

global $get_group_title, $get_group_id, $get_menu_groups;

ob_start();
$group_id = esc_sql( $get_group_id );
$parent_id = esc_sql( $parent_id );

if( $act == 'up'){

$select = $db->query ("SELECT MAX(position) as sc FROM $db->menu WHERE group_id='".$group_id."' AND parent_id = '".$parent_id."'");
$data = $db->fetch_array ($select);

if ($data['sc'] <= 0){
	$qquery = mysql_query ("SELECT `id` FROM `$db->menu` WHERE parent_id='$parent_id' AND group_id='".$group_id."'");
	$integer = 1;
	while ($getsql = mysql_fetch_assoc($qquery)){
		mysql_query ("UPDATE `$db->menu` SET `position` = $integer WHERE `id` = '".$getsql['id']."'");
		$integer++;	
	}		
}

$total = $data['sc'] + 1;
$a = $db->query ("UPDATE $db->menu SET position='".$total."' WHERE position='".($id-1)."' AND group_id='".$group_id."' AND parent_id = '".$parent_id."'"); 
$a.= $db->query ("UPDATE $db->menu SET position=position-1 WHERE position='".$id."' AND group_id='".$group_id."' AND parent_id = '".$parent_id."'");
$a.= $db->query ("UPDATE $db->menu SET position='".$id."' WHERE position='".$total."' AND group_id='".$group_id."' AND parent_id = '".$parent_id."'");
}

if( $act == 'down'){
$select = $db->query ("SELECT MAX(position) as sc FROM $db->menu WHERE group_id='".$group_id."' AND parent_id = '".$parent_id."'");
$data = $db->fetch_array ($select);

if ($data['sc'] <= 0){
	$qquery = mysql_query ("SELECT `id` FROM `$db->menu` WHERE parent_id='$parent_id' AND group_id='".$group_id."'");
	$integer = 1;
	while ($getsql = mysql_fetch_assoc($qquery)){
		mysql_query ("UPDATE `$db->menu` SET `position` = $integer WHERE `id` = '".$getsql['id']."'");
		$integer++;	
	}		
}

$total = $data['sc'] + 1;
$a = $db->query ("UPDATE $db->menu SET position='".$total."' WHERE position='".($id+1)."' AND group_id='".$group_id."' AND parent_id = '".$parent_id."'"); 
$a.= $db->query ("UPDATE $db->menu SET position=position+1 WHERE position='".$id."' AND group_id='".$group_id."' AND parent_id = '".$parent_id."'");
$a.= $db->query ("UPDATE $db->menu SET position='".$id."' WHERE position='".$total."' AND group_id='".$group_id."' AND parent_id = '".$parent_id."'");  
}
?>
<link rel="stylesheet" href="<?php echo site_url('admin/manage/appearance/css/style-menu-popup.css');?>">
<link rel="stylesheet" href="<?php echo site_url('admin/manage/appearance/css/style-menu.css');?>">
<script>
var BASE_URL = '<?php echo site_url('');?>/';
var current_group_id = <?php echo $get_group_id; ?>;
</script>
<script src="<?php echo site_url('admin/manage/appearance/js/interface-1.2.js');?>"></script>
<script src="<?php echo site_url('admin/manage/appearance/js/menu.js');?>"></script>
<div style="clear:both"></div>
<ul id="menu-group">
<?php 
$get_menu_groups_sort = array_multi_sort($get_menu_groups, array('id' => SORT_ASC));
foreach ((array)$get_menu_groups_sort as $data_get_menu) : 
?>
<li id="group-<?php echo $data_get_menu['id']; ?>"><a href="?admin&sys=appearance&go=menus&group_id=<?php echo $data_get_menu['id']; ?>"><?php echo $data_get_menu['title']; ?></a></li>
<?php endforeach; ?>
</ul>
<div class="clear" style="border-top:2px solid #ccc;"></div>

<div class="ns-row" id="ns-header">
<div class="ns-orders">Orders</div>
<div class="ns-actions">Actions</div>
<div class="ns-class">Class</div>
<div class="ns-url">URL</div>
<div class="ns-title">Title</div>
</div>
<div class="padding">
<?php 
$get_menu = dynamic_menus_data($get_group_id);

$group_menu_ul = '<ul id="dragbox_easymn"></ul>';
if ( $get_menu ) {

	include libs_path . '/class-tree.php';
	$tree = new Tree;
	
	foreach($get_menu as $row) {
	
		$querymax	= $db->query ("SELECT MAX(`position`) FROM `$db->menu` WHERE parent_id = '".$row['parent_id']."' AND group_id = '$get_group_id'");
		$alhasil 	= $db->fetch_array($querymax);	
		$numbers	= $alhasil[0];
	
		$tree->add_row(
			$row['id'],
			$row['parent_id'],
			' id="menu-'.$row['id'].'" class="sortable_easymn"',
			dynamic_menus_label($row, $numbers)
		);
	}	
	$group_menu_ul = $tree->generate_list('id="dragbox_easymn"');
}

echo $group_menu_ul;
?>
</div>
<?php
$content = ob_get_contents();
ob_end_clean();
$header_menu = '<a id="add-group" href="?request&load=libs/ajax/menu-group.php&aksi=add" class="button" title="Add new group">&nbsp;&nbsp;+&nbsp;&nbsp;</a>';
add_templates_manage( $content, 'Menus Manager', $header_menu);
break;
case 'custom-theme':

$theme_path_root = theme_path . '/' . get_option('template');

if( file_exists( $theme_path_root . '/options/admin.php' ) ){
	global $theme_name;	
	
	do_action('custom-theme');

	if (file_exists($theme_path_root . '/info.xml')) {
		$index_theme 	= @implode( '', file( $theme_path_root . '/info.xml' ) );
		$info_themes 	= str_replace ( '\r', '\n', $index_theme );
				
		preg_match( '|<themes>(.*)<\/themes>|ims', $info_themes, $themes);
		preg_match( '|<name>(.*)<\/name>|ims', $themes[1], $theme_name);
	}
	
	require_once( $theme_path_root . '/options/admin.php' );

}
else
{
	redirect('?admin&sys=appearance');
}
break;
}
?>
