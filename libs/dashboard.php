<?php 
/**
 * @fileName: dashboard.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;

function dashboard() {
	global $screen_layout_columns;

	$hide2 = $hide3 = $hide4 = '';
	switch ( $screen_layout_columns ) {
		case 4:
			$width = 'width:25%;';
			break;
		case 3:
			$width = 'width:33.333333%;';
			$hide4 = 'display:none;';
			break;
		case 2:
			$width = 'width:50%;';
			$hide3 = $hide4 = 'display:none;';
			break;
		default:
			$width = 'width:100%;';
			$hide2 = $hide3 = $hide4 = 'display:none;';
	}
	do_action('the_notif');
	
	echo '<div id="dashboard-widgets" class="metabox-holder">';
	echo "\t<div class='column column0' id='column0' style='$width'>\n";
	do_meta_boxes( 'normal', '' );

	echo "\t</div><div class='column column1' id='column1' style='{$hide2}$width'>\n";
	do_meta_boxes( 'side', '' );
	
	echo '</div>';
	echo '<div style="clear:both;"></div>';
	echo '</div>';
}
	
function dashboard_update_info(){ 
	global $version_system, $version_project, $version_beta, $version_build;
	
	$dp_content = '<div class="padding">';
    $dp_content.= '<div id="updater" style="overflow:auto; height:250px;"></div>';
	$dp_content.= '</div>';
    
    $setting = array(
        'dp_id' => 'updater_pop',
        'dp_title' => 'Pembaruan Terbaru',
        'dp_content' => $dp_content,
		'width' => 500
    );
    add_dialog_popup( $setting );
    ?>	
	<div class="padding">
	<p>Versi system anda <?php echo $version_system . ' '.$version_project.' '.$version_beta.' build '.$version_build;?>, pastikan versi yang anda pakai adalah versi terbaru, agar situs anda aman dan mengurangi kemungkinan masalah yang ada pada situs anda.</p>
	<div style="margin-top:5px"><a href="javascript:void(0);"  onclick="javascript:$('#dialog_updater_pop').showX()" class="button popupCore black icon-latest-upgrade">Cek Pembaruan Terbaru</a></div>
	<div style="clear:both"></div>
	<br /></div>
 <?php
}


function dashboard_feed_news(){ 
global $db;
	
$limit_form = 5;
$json = new JSON();

$news_feeds_default = array(
	'news_feeds' => array( 'cmsid.org Feed' => 'http://cmsid.org/rss.xml'),
	'display' => array('desc' => 0,'author' => 0,'date' => 0,'limit' => 10)
);
	
$news_feeds_default = $json->encode( $news_feeds_default );
$news_feeds_old_value = get_option('feed-news');
	
if( !empty($news_feeds_old_value) ) $feed_obj = $news_feeds_old_value;
else $feed_obj = $news_feeds_default;
	
$feed_obj = $json->decode( $feed_obj );
	
$news_feeds_old = $feed_obj->{'news_feeds'};
$display = $feed_obj->{'display'};

ob_start();
?>
<div class="padding">
<script type="text/javascript">
$(document).ready(function(){
	
var m = <?php echo $limit_form;?>;
var i = $('.dynamic_feed_form').size();
	
$("#dynamic_add").click(function() {

	if( i != m ){
		$("<div style=\"clear:both;\" class=\"dynamic_feed_form\"><input type=\"text\" class=\"dynamic_feed_title\" name=\"dynamic_feed_title[]\" style=\"width: 25%; float:left;\" placeholder=\"Judul\">&nbsp;<input type=\"text\" class=\"dynamic_feed_url\" name=\"dynamic_feed_url[]\" style=\"width: 65%; float:right;\" placeholder=\"Alamat URL\"></div>").fadeIn('slow').appendTo(".dynamic_inputs");
		i++;
	}else{
		alert("Maximal Form "+m);
	}
		
});
	
$("#dynamic_remove").click(function() {
	if(i > 1) {
		$(".dynamic_feed_form:last").remove(); 
		i--; 
	}
});
	
$("#dynamic_reset").click(function() {
	while(i > 1) { 
	$(".dynamic_feed_form:last").remove(); 
	i--; 
	}
});

});
</script>
<div style="height:35px;">
<a href="#" id="dynamic_add" class="button button2 l" style="margin-left:0;"> + </a> 
<a href="#" id="dynamic_remove" class="button button2 m"> - </a> 
<a href="#" id="dynamic_reset" class="button button2 r red" onclick="confirm('Are you sure reset field?')">Reset</a>
</div>
<div style="clear: both;"></div>
<?php
if( count($news_feeds_old) < 1 ){
?>
<div style="clear: both;" class="dynamic_feed_form"><input type="text" class="dynamic_feed_title" name="dynamic_feed_title[]" style="width: 25%; float:left;" placeholder="Judul">&nbsp;<input type="text" class="dynamic_feed_url" name="dynamic_feed_url[]" style="width: 65%; float:right;" placeholder="Alamat URL"></div>
<div class="dynamic_inputs">
</div>
<?php
}
else
{
$i = 0;
foreach( $news_feeds_old as $v => $k ){
?>
<div style="clear: both;" class="dynamic_feed_form"><input type="text" class="dynamic_feed_title" name="dynamic_feed_title[]" style="width: 25%; float:left;" placeholder="Judul" value="<?php echo $v?>">&nbsp;<input type="text" class="dynamic_feed_url" name="dynamic_feed_url[]" style="width: 65%; float:right;" placeholder="Alamat URL" value="<?php echo $k?>"></div>
<?php
$i++;
}
?>
<div class="dynamic_inputs">
</div>
<?php
}
$checked_desc = $checked_author = $checked_date = '';
if( $display->{'desc'} == 1 ) $checked_desc = 'checked="checked"';
if( $display->{'author'} == 1 ) $checked_author = 'checked="checked"';
if( $display->{'date'} == 1 ) $checked_date = 'checked="checked"';

?>
<div style="clear:both"></div><br />
<label for="feed_list">Berapa banyak yang ditampilkan</label> 
<select id="feed_list" name="display_feed_limit">
<?php 
$select_feed_limit_array = array(5,10,30,50,100);
foreach( $select_feed_limit_array as $sk => $sv ){
	$selected = '';
	if( $sv == $display->{'limit'} ) $selected = ' selected="selected"';
	echo '<option value="'.$sv.'"'.$selected.'>'.$sv.'</option>';
}
?>
</select><br>
<label for="feed_desc">Tampilkan isi berita?</label>
<input id="feed_desc" name="display_feed_desc" type="checkbox" value="1" <?php echo $checked_desc?>>
<br style="clear:both" />
<label for="feed_author">Tampilkan penulis jika ada?</label>
<input id="feed_author" name="display_feed_author" type="checkbox" value="1" <?php echo $checked_author?>>
<br style="clear:both" />
<label for="feed_date">Tampilkan tanggal?</label>
<input id="feed_date" name="display_feed_date" type="checkbox" value="1" <?php echo $checked_date?>>
</div>
<?php
$dp_content = ob_get_contents();
ob_end_clean();
$dp_footer = '<input id="log_submit" type="submit" name="submitFeed" value="Submit" class="button l" />';
$dp_footer.= '<input type="reset" name="Reset" value="Reset" class="button r" />';
    
$setting = array(
	'dp_id' => 'dashboard_feed_news',
	'dp_title' => 'Pengaturan Feed News Favorit',
	'dp_content' => $dp_content,
	'dp_footer' => $dp_footer,
	'width' => 500
);
add_dialog_popup( $setting );
?>	
<div style="clear:both"></div>
<?php 
if( isset($_POST['submitFeed']) ){
	$title_feed 	= $_POST['dynamic_feed_title']; 
	$url_feed 		= $_POST['dynamic_feed_url']; 
	
	$display_feed_limit	 	= filter_int( $_POST['display_feed_limit'] ); 
	$display_feed_desc	 	= filter_int( $_POST['display_feed_desc'] ); 
	$display_feed_author 	= filter_int( $_POST['display_feed_author'] ); 
	$display_feed_date 		= filter_int( $_POST['display_feed_date'] ); 
	
	$news_feeds_old_combine = array_combine( $title_feed, $url_feed );
	$display = array('desc' => $display_feed_desc,'author' => $display_feed_author,'date' => $display_feed_date,'limit' => $display_feed_limit);
	
	$news_feeds = array();
	foreach( $news_feeds_old_combine as $news_feeds_old_combine_k => $news_feeds_old_combine_v ){
		
		if( !empty($news_feeds_old_combine_k) && !empty($news_feeds_old_combine_v) )
			$news_feeds[$news_feeds_old_combine_k] = $news_feeds_old_combine_v;
	}
	
	$data_news_feeds = compact('news_feeds','display');
	$data_news_feeds = $json->encode( $data_news_feeds );
	
	if( !checked_option( 'feed-news' ) ) add_option( 'feed-news', $data_news_feeds );
	else set_option( 'feed-news', $data_news_feeds );
	
	redirect('?admin');
}

?>
<div style="clear:both"></div>
<!--start-tabs-->
<div id="feed_news_view"></div>
<!--end-tabs-->
<?php
}

function dashboard_quick_post(){ 
?>
<div class="padding"><div style="clear:both"></div>    
<?php

if(isset($_POST['post_publish']) || isset($_POST['post_draf'])){
	
	$title 		= filter_txt($_POST['title']);
	$category 	= filter_int($_POST['category']);
	
	if(get_option('text_editor')=='classic') $isi = nl2br2($_POST['isi']);
	else $isi = $_POST['isi'];
	
	$tags 		= filter_txt($_POST['tags']);
	$date 		= date('Y-m-d H:i:s');
	
	if(isset($_POST['post_draf'])) $status = 0;
	else $status = 1;
	
	$type 		= 'post';
	$approved	= 1;
	
	$data = compact('title','category','type','isi','tags','date','status');
	add_quick_post($data);
}
?>
<form action="" method="post">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2"><input type="text" id="judul" name="title" placeholder="Judul Posting" required style="width:97%;" /></td>
    </tr>
    <tr>
      <td colspan="2"><?php the_editor('','editor_quick_post', array('editor_name' => 'isi','editor_style' => 'height:100px;width:97%;'), array( 'toolbar' => 'simple', 'css' => 'wym-simple.css') );?></td>
    </tr>
    <tr>
      <td width="45%">
      <select name="category">
      <option value="">Pilih Category</option>
	  <?php echo list_category_op();?>
      </select></td>
      <td width="45%" align="right"><input id="tags" type="text" name="tags" placeholder="Tag ex: news,top,etc." /></td>
    </tr>
    <tr>
      <td colspan="2"><div class="left"><input type="submit" name="post_draf" value="Save Draf" class="button l black"/><input type="reset" value="Reset" class="button r"/></div><div class="right"><input type="submit" name="post_publish" value="Publish" class="button on blue"/></div></td>
    </tr>
  </table>

</form>

</div>
<?php
}

function dashboard_recent_registration(){ 

if( checked_option( 'recent_reg_limit' )  ) $recent_reg_limit = get_option('recent_reg_limit');
else $recent_reg_limit = 10;

$dp_content = '<div class="padding">';
$dp_content.= '<label for="txtShow">Jumlah yang di tampilkan</label>';
$dp_content.= '<input id="txtShow" name="txtShow" type="text" style="width:50px" value="'.$recent_reg_limit.'" />';
$dp_content.= '</div>';

$dp_footer = '<input id="log_submit" type="submit" name="submitRecReg" value="Submit" class="button l" />';
$dp_footer.= '<input type="reset" name="Reset" value="Reset" class="button r"  />';

$setting = array(
	'dp_id' => 'dashboard_recent_registration',
	'dp_title' => 'Pengaturan Recent Registration',
	'dp_content' => $dp_content,	
	'dp_footer' => $dp_footer
);
add_dialog_popup( $setting );
?>
<div style="clear:both"></div>
<?php 
if( isset($_POST['submitRecReg']) ){
	$txt_recent_reg_limit = $_POST['txtShow'];
	if( checked_option( 'recent_reg_limit' ) ) set_option( 'recent_reg_limit', $txt_recent_reg_limit );
	else add_option( 'recent_reg_limit', $txt_recent_reg_limit );
	
	redirect('?admin');
}
?>
<div style="clear:both"></div>
<!--start-tabs-->
<div id="recent_reg_view"></div>
<!--end-tabs-->
<?php

}

function dashboard_init() {
do_action('dashboard_init');
	
?>
<script type="text/javascript">
var base_url = '<?php echo site_url();?>';

$(document).ready(function(){

$(".popupCore").click(function() {		
	var update_url = '?request&load=libs/ajax/latest.php';
	getLoad('updater',update_url);	
	setInterval(function() {
		getLoad('updater',update_url);
	}, 90000); // 30 second
});	
getLoad('recent_reg_view','?request&load=libs/ajax/recent.php');
getLoad('feed_news_view','?request&load=libs/ajax/feed.php');
 
});

function updateWidgetData(){
	var sortorder = new Array();
	$('#dashboard-widgets').each(function(){
		var dwa = $(this);	
		$('.column .meta-box-sortables').each(function(i){		
			var sortorder_by = $(this).attr('id').replace(/-sortables/i,'');
			$('.dragbox', this).each(function(i){
				
				if( 'normal' == sortorder_by )
					sortorder.push( {normal:$(this).attr('id')} );				
				else if( 'side' == sortorder_by )
					sortorder.push( {side:$(this).attr('id')} );
				
			});
		});	
	});
	
	var normal_array = new Array();
	var side_array = new Array();
	for(i=0; i < sortorder.length; i++){
		if( sortorder[i].normal ) normal_array.push( sortorder[i].normal );
		else if( sortorder[i].side ) side_array.push( sortorder[i].side );
	}
	
	var normal_string = '';
	var side_string = '';
	for(i=0; i < normal_array.length; i++){
		normal_string+= normal_array[i]+',';
	}
	
	for(i=0; i < side_array.length; i++){
		side_string+= side_array[i]+',';
	}
	
	var set_sortorder = {normal:normal_string,side:side_string};
	console.log(set_sortorder);
			
	//Pass sortorder variable to server using ajax to save state
	//$.post('irequest.php?auto', 'sort='+$.toJSON(sortorder));
	//autosave(sortorder);
	$.post( base_url +'/?request=dashboard', 'data='+$.toJSON( set_sortorder ), function(response){ 	   
		var winHeight = $(window).height();
		var winWidth = $(window).width();
		
		$('#redactor_modal_console').css({
			top: '15%',
			left: winWidth / 2 - $('#redactor_modal_console').width() / 2
		});
        $('#redactor_modal_overlay_loading,#redactor_modal_console').show().fadeOut('slow');
    });  
	
}

function show_empty_container(){
	$(".column .meta-box-sortables").each(function(index, element) {
		var t = $(this);
		if ( !t.children('.gd:visible').length )
			t.addClass('empty-container');
		else
			t.removeClass('empty-container');
	});
}
</script>	
<?php
}

function dashboard_setup() {
	global $current_screen;
	$current_screen->render_screen_meta();
	
	add_dashboard_widget( 'dashboard_update_info', 'Information', 'dashboard_update_info' );
	add_dashboard_widget( 'dashboard_quick_post', 'Quick Post', 'dashboard_quick_post' );
	add_dashboard_widget( 'dashboard_recent_registration', 'Recent Registration', 'dashboard_recent_registration', true );
	add_dashboard_widget( 'dashboard_feed_news', 'Feed News', 'dashboard_feed_news', true );
}

function add_dashboard_widget( $widget_id, $widget_name, $callback, $setting = null ) {

	$side_widgets = array('dashboard_quick_post', 'dashboard_recent_registration');

	$location = 'normal';
	if ( in_array($widget_id, $side_widgets) )
		$location = 'side';

	$priority = 'core';
	if ( 'dashboard_update_info' === $widget_id )
		$priority = 'high';

	add_meta_box( $widget_id, $widget_name, $callback, $location, $priority, $setting );
}

function add_meta_box( $id, $title, $callback, $context = 'advanced', $priority = 'default', $setting = null ) {
	global $meta_boxes;
	//call do_meta_boxes in screen.php

	if ( !isset($meta_boxes) )
		$meta_boxes = array();
	if ( !isset($meta_boxes[$context]) )
		$meta_boxes[$context] = array();

	foreach ( array_keys($meta_boxes) as $a_context ) {
		foreach ( array('high', 'core', 'default', 'low') as $a_priority ) {
			if ( !isset($meta_boxes[$a_context][$a_priority][$id]) )
				continue;

			if ( 'core' == $priority ) {
				if ( false === $meta_boxes[$a_context][$a_priority][$id] )
					return;
				if ( 'default' == $a_priority ) {
					$meta_boxes[$a_context]['core'][$id] = $meta_boxes[$a_context]['default'][$id];
					unset($meta_boxes[$a_context]['default'][$id]);
				}
				return;
			}
			
			if ( empty($priority) ) {
				$priority = $a_priority;
			} elseif ( 'sorted' == $priority ) {
				$title = $meta_boxes[$a_context][$a_priority][$id]['title'];
				$callback = $meta_boxes[$a_context][$a_priority][$id]['callback'];
				$setting = $meta_boxes[$a_context][$a_priority][$id]['setting'];
			}
			
			if ( $priority != $a_priority || $context != $a_context )
				unset($meta_boxes[$a_context][$a_priority][$id]);
		}
	}

	if ( empty($priority) )
		$priority = 'low';

	if ( !isset($meta_boxes[$context][$priority]) )
		$meta_boxes[$context][$priority] = array();

	$meta_boxes[$context][$priority][$id] = array('id' => $id, 'title' => $title, 'callback' => $callback, 'setting' => $setting);
}

