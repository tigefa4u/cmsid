<?php 
/**
 * @fileName: admin.php
 * @dir: plugin/shoutbox
 */
if(!defined('_iEXEC')) exit;
global $db;

$act 	= filter_txt($_GET['act']);
$id		= filter_int($_GET['id']);
$link 	= "?admin&sys=plugins&go=setting&plugin_name=shoutbox&file=/shoutbox.php"; 

ob_start();

function delete_shoutbox($id){
	global $db;		
	$db->delete("shoutbox", array('id' => $id) );
}

if( $act == 'del' && !empty($id) ){
	delete_shoutbox($id); 
	add_activity('shoutbox',"menghapus shoutbox $id", 'comment');
	redirect( $link );
}

if( isset($_POST['submitDelete']) ) {
	$shoutbox_id = (array) $_POST['shoutbox_id'];
		
	foreach($shoutbox_id as $key){
		delete_shoutbox($key); 
		add_activity('shoutbox',"menghapus shoutbox $key", 'comment');
	}
	redirect( $link );
}

$warna = '';
$checkbox_id 	= 0;

$sql = $db->query( "SELECT * FROM $db->shoutbox WHERE DATE(`waktu`) != CURDATE()" );
?>
<div id="list-comment">
<table id=table cellpadding="0" cellspacing="0" widtd="100%">
    <tr class="head" style="border-bottom:0;">
		<td style="width:1%;"></td>
		<td style="text-align:left; width:25%">By</td>
		<td style="text-align:left">Message</td>
	</tr>
<?php
while( $row = $db->fetch_obj( $sql ) ){
$warna 	= empty ($warna) ? ' style="background:#f9f9f9"' : '';
?>
	<tr class="isi" <?php echo $warna;?>>
	  <td style="text-align:left; vertical-align:middle; padding-left:5px"><input type="checkbox" name="shoutbox_id[]" value="<?php echo $row->id?>" id="checkbox_id_<?php echo $checkbox_id;?>" /></td>
	  <td>
      <div style="float:left; width:42px; height:100%; margin:2px; margin-right:5px; padding:2px; padding-top:10px;">
      <img src="<?php echo get_gravatar($row->email);?>" style="width:40px; height:40px; border:1px solid #ddd;" class="radius">
      </div>
      <div style="margin:0; margin-top:3px; margin-left:50px;">
      <?php echo $row->nama?><br />
      <a href="<?php echo $link;?>&act=del&id=<?php echo $row->id?>" class="button button4 red" style="margin-top:3px;" onclick="return confirm('Are You sure delete this message?')">Hapus</a>
	  </div>
      <div style="clear:both"></div>
      </td>
	  <td>
      <p><?php echo $row->pesan?></p>
      <div style="clear:both"></div>
      <?php echo date_stamp($row->waktu)?> 
      <div style="clear:both"></div>
      </td>
    </tr>
<?php
$checkbox_id++;
}
?>
</table>
<input type="hidden" id="checkbox_total" value="<?php echo $checkbox_id?>" name="checkbox_total">
</div>

<?php

$content = ob_get_contents();
ob_end_clean();


$header_menu = '<div class="header_menu_top">';
$header_menu.= '<label for="set_checkbox_all">Check All</label> ';
$header_menu.= '<input type="checkbox" onClick="checkbox_all()" id="set_checkbox_all" style="margin-top:8px;">';
$header_menu.= '<input type="submit" name="submitDelete" class="primary button on red" value="Delete the selected" id="checkbox_go">';
$header_menu.= '</div>';
$header_menu.= '<a href="?admin&sys=plugins" class="button"><span class="icon_head back">&laquo; Back</span></a>';

$form = 'action="" method="post" enctype="multipart/form-data"';
add_templates_manage( $content, 'Shoutbox Manager', $header_menu, null, $form  );
