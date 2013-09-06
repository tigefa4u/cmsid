<?php 
/**
 * @fileName: admin.php
 * @dir: plugin/block
 */
if(!defined('_iEXEC')) exit;

global $db, $registered_sidebars;

if( $_SESSION['lw'] != 'full' ){
?>
<style>
#post-left {
	width: 64%;
}
#post-right {
	width: 35%;
}
</style>
<?php
}

$act 	= filter_txt($_GET['act']);
$pub 	= filter_txt($_GET['pub']);
$id		= filter_int($_GET['id']);
$link 	= "?admin&sys=plugins&go=setting&plugin_name=block&file=/block.php"; 

if($act == 'del'){    
	$db->delete("block",array('block_id' => $id)); 
	add_activity('manager_plugin_block',"menghapus id $id pada plugin block", 'plugin');
	redirect($link);
}

if($act == 'pub'){
	if ($pub == 'no') $stat = 0;	
	if ($pub == 'yes') $stat = 1;
	$db->update('block',array('status' => $stat), array('block_id' => $id) );
	add_activity('manager_plugin_block',"memperbaharui status menjadi $stat pada plugin block", 'plugin');
	redirect($link);
}

if( $act == 'up'){

$select = $db->query("SELECT MAX(order_by) as sc FROM $db->block");
$data 	= $db->fetch_array($select);
$total 	= $data['sc'] + 1;
$update = $db->query("UPDATE $db->block SET order_by='$total' WHERE order_by='".($id-1)."'"); 
$update = $db->query("UPDATE $db->block SET order_by=order_by-1 WHERE order_by='$id'");
$update = $db->query("UPDATE $db->block SET order_by='$id' WHERE order_by='$total'");   
redirect($link);
}

if( $act == 'down'){
$select = $db->query("SELECT MAX(order_by) as sc FROM $db->block");
$data 	= $db->fetch_array($select);
$total 	= $data['sc'] + 1;
$update = $db->query("UPDATE $db->block SET order_by='$total' WHERE order_by='".($id+1)."'"); 
$update = $db->query("UPDATE $db->block SET order_by=order_by+1 WHERE order_by='$id'");
$update = $db->query("UPDATE $db->block SET order_by='$id' WHERE order_by='$total'");    
redirect($link);
}

ob_start();
?>
<table id="table" cellpadding="0" cellspacing="0">
<tr class="head">
    <td class="depan"><strong>Block</strong></td>
    <td class="depan"><strong>Sidebar</strong></td>
    <td class="depan"><div align="center"><strong>Status</strong></div></td>
    <td class="depan"><div align="center"><strong>Order</strong></div></td>
    <td class="depan"><div align="center"><strong>Action</strong></div></td>
  </tr>
<?php

$warna = '';
$query = $db->select('block',null,'ORDER BY order_by ASC');
while( $row = $db->fetch_obj($query) ) {	
$warna 	= empty ($warna) ? ' bgcolor="#f1f6fe"' : '';	

$status = ($row->status == 1) ? '<a  class="enable" title="Disable Now" href="'.$link.'&act=pub&pub=no&id='.$row->block_id.'">Enable</a>' : '<a  class="disable" title="Enable Now" href="'.$link.'&act=pub&pub=yes&id='.$row->block_id.'">Disable</a>';   

$ordering_down = '<a href="'.$link.'&act=down&id='.$row->order_by.'" class="down" title="down">down</a>';    
$ordering_up = '<a href="'.$link.'&act=up&id='.$row->order_by.'" class="up" title="up">up</a>'; 
			
if( $row->order_by == 1 ) $ordering_up = '';
if( $row->order_by == $db->num($query) ) $ordering_down = '';
?>
<tr <?php echo $warna?> class="isi">
	<td valign="top"><span title="<?php echo $row->title?>"><?php echo $row->title?></span></td>
	<td valign="top"><?php echo $registered_sidebars[$row->sidebar]['name']?></td>
    <td valign="top"><div class="action"><?php echo $status;?></div></td>
    <td valign="top"><div class="action"><?php echo $ordering_up.' '.$ordering_down?></div></td>
    <td valign="top">
    <div class="action">
<a href="<?php echo $link . '&act=edit&id='.$row->block_id;?>" class="edit" title="edit">edit</a>
<a href="<?php echo $link . '&act=del&id='.$row->block_id;?>" class="delete" title="delete" onclick="return confirm('Are You sure delete block <?php echo $row->title?>?')">delete</a>
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

if( $act == 'edit' ){
	
$title_widget = 'Edit Block';
$menu_widget = '<a href="?admin&sys=plugins&go=setting&plugin_name=block&file=/block.php" class="button">Cancel</a>';

$query 	= $db->query("SELECT * FROM $db->block WHERE block_id='$id'");
$row 	= $db->fetch_obj($query);

if( isset($_POST['submit']) ){
	$title 	= filter_txt( $_POST['block_title'] );
	$code 	= $_POST['block_code'];
	$sidebar_available = filter_txt( $_POST['sidebar_available'] );
	
	if (!$title) $msg[] = "<strong>ERROR</strong>: Title empty.<br />";
    if (!$code) $msg[] = "<strong>ERROR</strong>: Code Blok empty.<br />";

    if( is_array($msg) )	{
		foreach( $msg as $val ){ echo '<div id="error">'.$val.' </div>';}
	}else{
		
		$title 	= esc_sql( $title );
		$code 	= esc_sql( $code );
		$sidebar_available = esc_sql( $sidebar_available );
		$ordering = esc_sql( $ordering );
	
		$db->update('block',array(
			'title' => $title, 
			'html' => $code, 
			'sidebar' => $sidebar_available
			), array( 'block_id' => $id )
		);
		redirect($link);
	}
	
}
?>
<form action="" method="post">
<label for="block_title">Title :</label><br />
<input type="text" name="block_title" id="block_title" style="width:95%;" value="<?php echo $row->title;?>" /><br />
<label for="block_code">Code HTML :</label><br />
<textarea name="block_code" id="block_code" style="width:95%; height:200px;" ><?php echo $row->html;?></textarea><br />
<label for="sidebar_available">Sidebar Available :</label><br />
<select name="sidebar_available">
<?php
foreach ( $registered_sidebars as $sidebar => $registered_sidebar ) {
	if ( false !== strpos( $registered_sidebar['class'], 'inactive-sidebar' ) || 'orphaned_widgets' == substr( $sidebar, 0, 16 ) )
		continue;
		
	$selected = '';
	if( $row->sidebar == $registered_sidebar['id'] ) 
		$selected = ' selected="selected"';
?>    
	<option value="<?php echo $registered_sidebar['id']?>" <?php echo $selected;?>><?php echo $registered_sidebar['name']?></option>
<?php 
}
?>
</select><br />
<input name="submit" type="submit" value="Change" class="button l blue" /><input name="Reset" type="Reset" value="Clear" class="button r" />
</form>
<?php 

}else{
	
if( isset($_POST['submit']) ){
	$title 	= filter_txt( $_POST['block_title'] );
	$code 	= $_POST['block_code'];
	$sidebar_available = filter_txt( $_POST['sidebar_available'] );
	
	$query 		= $db->query("SELECT MAX(order_by) as ordering FROM $db->block");
    $row 		= $db->fetch_array($query);
    $ordering 	= $row['ordering'] + 1;
	
	if (!$title) $msg[] = "<strong>ERROR</strong>: Title empty.<br />";
    if (!$code) $msg[] = "<strong>ERROR</strong>: Code Blok empty.<br />";

    if( is_array($msg) )	{
		foreach( $msg as $val ){ echo '<div id="error">'.$val.' </div>';}
	}else{
		
		$title 	= esc_sql( $title );
		$code 	= esc_sql( $code );
		$sidebar_available = esc_sql( $sidebar_available );
		$ordering = esc_sql( $ordering );
	
		$db->insert('block',array(
			'order_by' => $ordering,
			'title' => $title, 
			'html' => $code, 
			'sidebar' => $sidebar_available
		));
		redirect($link);
	}
	
}
?>
<form action="" method="post">
<label for="block_title">Title :</label><br />
<input type="text" name="block_title" id="block_title" style="width:95%;" /><br />
<label for="block_code">Code HTML :</label><br />
<textarea name="block_code" id="block_code" style="width:95%; height:150px;" ></textarea><br />
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
<input name="submit" type="submit" value="Add" />
</form>
<?php 
}
?>
</div>
<?php
$content_widget = ob_get_contents();
ob_end_clean();

$header_menu = '<div class="header_menu_top2">';
$header_menu.= '<a href="?admin&sys=plugins&go=setting&plugin_name=block&file=/block.php" class="button l">Home</a>';
$header_menu.= '<a href="?admin&sys=plugins" class="button r"><span class="icon_head back">&laquo; Back</span></a>';
$header_menu.= '</div>';

$menu_widget = empty($menu_widget) ? '' : $menu_widget;
$title_widget = empty($title_widget) ? 'Add New Block' : $title_widget;
$widget_manual = array();
$widget_manual['gadget'][] = array('menu' => $menu_widget, 'title' => $title_widget, 'desc' => $content_widget);
add_templates_manage( $content, 'Block Manager', $header_menu, $widget_manual );