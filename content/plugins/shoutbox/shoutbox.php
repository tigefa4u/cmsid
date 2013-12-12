<?php
/**
 * @file: shoutbox.php
 * @dir: content/plugins
 */
 
/*
Plugin Name: Shoutbox
Plugin URI: http://cmsid.org/#
Description: Plugin widget untuk shoutbox 
Author: Eko Azza
Version: 2.2.1
Author URI: http://cmsid.org/
*/ 

if(!defined('_iEXEC')) exit;

add_action('widget_shoutbox_form', 'shoutbox_register');
function shoutbox_register() {
	?>
	<script src="<?php echo plugins_url();?>/shoutbox/js/xmlhttp.js"></script>
	<script src="<?php echo plugins_url();?>/shoutbox/js/prototype.js"></script>
	<script src="<?php echo plugins_url();?>/shoutbox/js/shoutbox.js" language="javascript"></script>
    <?php
}
function widget_shoutbox_form(){
	do_action('widget_shoutbox_form');
?>
<form id="formShoutBox">
    <table width="100%">
        <tr><td colspan="2">
			<div id="divShoutBoxList" style="border:1px solid #ddd;overflow:auto;height:250px; min-width:150px; padding:3px;">
			<center><div id="loading" align="center"></div>
        	<img alt="wait.." src="<?php echo plugins_url();?>/shoutbox/waiting.gif" />
        	</center>
		</div>
        </td></tr>
        <tr><td>Nama<span class="req">*</span></td><td>
        <input type="text" name="nama" id="nama" style="width:60%">
        </td></tr>
        <tr><td>E-Mail<span class="req">*</span></td><td>
        <input type="text" name="email" id="email" style="width:60%">
        </td></tr>
        <tr><td valign="top">Pesan<span class="req">*</span></td><td>
        <textarea style="width:90%; height:40px" name="pesan" id="pesan" onKeyPress="check_length(this.form); onKeyDown=check_length(this.form);"></textarea>
        </td></tr>
        <tr><td valign="top">&nbsp;</td><td>
        <input type="text" value=225 name=text_num disabled="disabled" size="3" readonly > huruf lagi
        </td></tr>
        <tr><td valign="top">&nbsp;</td><td>
        <input name="submitButton" type="submit" id="submitButton" value="Kirim">
        </td></tr>
    </table>
</form>
<?php
}

class Widget_Shoutbox extends Widgets {

	function __construct() {
		$widget_ops = array('classname' => 'widget_shoutbox', 'description' => "Shout your message in box" );
		parent::__construct('shoutbox', 'Shoutbox', $widget_ops);
	}

	function widget( $args ) {
	global $login;
		extract($args);
		$title = 'Shoutbox';

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
			
		widget_shoutbox_form();
			
		echo $after_widget;
	}
}

register_widget('Widget_Shoutbox');

function shoutbox_widget_init(){
	global $widget;
	
	$widget = array();
	$widget['gadget'][] = array(
		'title' => 'Shoutbox Today',
		'desc' 	=> shoutbox_today()
	);
	return;
}

if(is_sys_values() == 'plugins' 
&& get_query_var('go') == 'setting' 
&& get_query_var('plugin_name') == 'shoutbox' )
   add_action('add_templates_manage', 'shoutbox_widget_init');
	
function shoutbox_today(){
	global $db;
	
	$warna = '';
	
	$message = '<ul class="sidemenu" style="max-height:400px;">';
	$sql = $db->query( "SELECT * FROM $db->shoutbox WHERE DATE(`waktu`) = CURDATE() ORDER BY waktu DESC LIMIT 30" );
	
	if( $db->num($sql) < 1 )
	$post .= '<div class="padding"><div id="message_no_ani">No comment</div></div>';
	
	while( $row = $db->fetch_obj( $sql ) ){
		$warna 	= empty ($warna) ? ' style="background:#f9f9f9"' : '';
		
		$message .= '<li'.$warna.'><img src="'.get_gravatar($row->email).'" style="float:left; width:40px; height:40px; margin-right:5px;" class="radius">'.$row->nama. ', ' .date_stamp($row->waktu) . '<br>';
		$message .= '<div style="float:left; width:60%; padding-top:4px;">';	
		$message .= $message_content;		
		$message .= '<a href="?admin&sys=plugins&go=setting&plugin_name=shoutbox&file=/shoutbox.php&act=del&id='.$row->id.'" class="button button4 red" onclick="return confirm(\'Are You sure delete this post?\')">Hapus</a></div>';
		$message .= '<div style="clear:both; padding-bottom:5px;"></div>'; 
		$message .= '<p>'.$row->pesan.'</p></li>';
	}
	$message .= '<ul>';
	
	return $message;
}

function shoutbox_filter( $text = '', $target = '_blank' ){
	//filter link
	$text = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $text));
    $data = '';
    foreach( explode(' ', $text) as $str){
        if (preg_match('#^http?#i', trim($str)) || preg_match('#^www.?#i', trim($str))) {
            $data .= '<a href="'.$str.'" target="'.$target.'">click here</a> ';
        } else {
            $data .= $str .' ';
        }
    }
    return trim($data);
}