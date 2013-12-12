<?php
/**
 * @file: referal.php
 * @dir: content/plugins
 */
 
/*
Plugin Name: Referal Site
Plugin URI: http://cmsid.org/#
Description: Plugin bla bla bla
Author: Eko Azza
Version: 1.1.0
Author URI: http://cmsid.org/
*/ 

if(!defined('_iEXEC')) exit;

/**
 * mengeset statistik penyerahan
 */
function set_stats_ref(){
	global $db;
	
	$spam_words = array(
		'roulette', 'gambl', 'vegas', 'poker', 'casino', 'blackjack', 'omaha',
		'stud', 'hold', 'slot', 'bet', 'pills', 'cialis', 'viagra', 'xanax',
		'watches', 'loans', 'phentermine', 'naked', 'cam', 'sex', 'nude',
		'loan', 'mortgage', 'financ', 'rates', 'debt', 'dollar', 'cash',
		'traffic', 'babes', 'valium' );
		
	$data 					= array();
	$data['referrer'] 		= ( isset( $_SERVER['HTTP_REFERER'] ) ) ? $_SERVER['HTTP_REFERER'] : '';
	$url 					= parse_url( $data['referrer'] );
	$data['referrer'] 		= mb_substr( _utf8_encode( $data['referrer'] ), 0, 255 );
	
	$data['domain']   		= ( isset( $url['host'] ) ) ? mb_eregi_replace( '^www.', '', $url['host'] ) : '';
	$data['domain']   		= mb_substr( $data['domain'], 0, 255 );
	$data['search_terms'] 	= mb_substr( _utf8_encode( determine_search_terms( $url ) ), 0, 255 );
	
	
	$data['date_modif'] = date('Y-m-d H:i:s');
	foreach ( $spam_words as $spam_word ) {
		if ( stristr( $data['referrer'], $spam_word ) ) {
		return;
		}
	}
	
	if ( mb_strlen( $data['domain'] ) >= 25 && 
		( !isset( $_SERVER['SERVER_NAME'] ) || 
		$data['domain'] != mb_eregi_replace( '^www.', '', $_SERVER['SERVER_NAME'] ) ) ) {
		return;
	}

	$hits   = 1;
	$insert = true;
	$update = false;
	
	$query	= $db->select("stat_urls");
	while( $row	= $db->fetch_array($query) ){
		if($row['referrer']==$data['referrer']){
			$id		= $row['id'];
			$hits   = $row['hits'];
			$insert = false;
			$update = true;
		}
	}
	if( $data['domain'] != get_domain_name( site_url() ) && !empty($data['domain']) ):
	$hits = $hits+1;
	
	if( !$insert && $update )
		$db->update('stat_urls',array('hits' => $hits, 'date_modif' => $data['date_modif']),compact('id'));
	else
		$db->insert('stat_urls',$data);
		
	endif;
}
add_action( 'the_head', 'set_stats_ref' );

function refering(){
global $db;

if( checked_option( 'referal_limit' )  ) $referal_limit = get_option('referal_limit');
else $referal_limit = 10;

$dp_content = '<div class="padding">';
$dp_content.= '<label for="txtShow">Jumlah yang di tampilkan</label>';
$dp_content.= '<input id="txtShow" name="txtShow" type="text" style="width:50px" value="'.$referal_limit.'" />';
$dp_content.= '</div>';

$dp_footer = '<div style="float:left"><input id="rec_submit" type="submit" name="submitReferal" value="Submit" class="button on l" /><input type="reset" name="Reset" value="Reset" class="button r" /></div>
<div style="float:right"><input onclick="return confirm(\'Are You sure delete all records?\')" id="rec_submit" type="submit" name="submitReferalClear" value="Set to Empty" class="button red" /></div>';

$setting = array(
	'dp_id' => 'refering',
	'dp_title' => 'Pengaturan Referal',
	'dp_content' => $dp_content,	
	'dp_footer' => $dp_footer
);
add_dialog_popup( $setting );


if( isset($_POST['submitReferal']) ){
	$txt_referal_limit = $_POST['txtShow'];
	if( checked_option( 'referal_limit' ) ) set_option( 'referal_limit', $txt_referal_limit );
	else add_option( 'referal_limit', $txt_referal_limit );
	
	redirect('?admin');
}

if( isset($_POST['submitReferalClear']) ){
	
	$clear = $db->truncate('stat_urls');
	
	if( $clear )
	redirect('?admin');
}
?>
<style type="text/css">
.tab_referr_urls
{
	padding:0;
}
.tab_referr_urls ul.ul-box
{
	
}
ul.referr_urls
{
	margin: 0;
	padding: 0;
	float: left;
	list-style: none;
	height: 25px;
	margin-left:3px;
	margin-right:0;
	margin-top:2px;
}
ul.referr_urls li 
{
	float: left;
	margin: 0;
	padding: 0;
	height: 24px;
	line-height: 24px;
	border: 1px solid #ddd;
	margin-right:2px;
	overflow: hidden;
	font-weight:normal;
    border-top-left-radius: 2px;
    border-top-right-radius: 2px;
    -moz-border-radius-topleft: 2px;
    -moz-border-radius-topright: 2px;
}
ul.referr_urls li a
{
	text-decoration: none;
	display: block;
	padding: 0 5px 0 5px;
	outline: none;
}
ul.referr_urls li a:hover 
{
	background: #f2f2f2;
    border-top-left-radius: 2px;
    border-top-right-radius: 2px;
    -moz-border-radius-topleft: 2px;
    -moz-border-radius-topright: 2px;
}
html ul.referr_urls li.active,
html ul.referr_urls li.active a:hover
{
	background: #f2f2f2;
	border-bottom: 1px solid #ccc;
	border-bottom-style:dotted;
}
</style>
<script type="text/javascript">
/* <![CDATA[ */
$(document).ready(function(){
	
$(".tab_referr_urls").hide();
$(".tab_referr_urls:first").show();
$("ul.referr_urls li:first").addClass("active").show();
$("ul.referr_urls li").click(function() {
	$("ul.referr_urls li").removeClass("active");
	$(this).addClass("active");
	$(".tab_referr_urls").hide();
	var activeTab = $(this).find("a").attr("href");
	$(activeTab).slideDown('slow');
	return false;
});

});
/* ]]> */
</script>
<div style="clear:both"></div>
<!--start-tabs-->
<ul class="referr_urls">
<li class="active"><a href="#ref-new">Terbaru</a></li>
<li><a href="#ref-top">Teratas</a></li>
<li><a href="#ref-domain">Domain</a></li>
<li><a href="#ref-keywords">Kata Kunci</a></li>
</ul>
<div style="clear:both"></div>
<div class="tabs-content">
<div id="ref-new" class="tab_referr_urls" style="display: block; ">
<div style="overflow:auto; max-height:200px;">
<?php
$query = $db->select('stat_urls',null,"ORDER BY `date_modif` DESC LIMIT $referal_limit");
if($db->num($query) < 1) echo '<div class="padding"><div id="error_no_ani">Data kosong</div></div>';
else{
echo '<ul class="ul-box">';
while($data	= $db->fetch_obj($query)){
if(!empty($data->referrer)){
?>
<li title="<?php echo str_replace('http://', '', $data->referrer);?>"><a href="<?php echo $data->referrer?>"><?php echo limittxt(str_replace('http://', '', $data->referrer),50);?></a><span><?php echo $data->hits;?></span></li>
<?php }}?>
</ul>
<?php }?>
</div>
</div>
<div id="ref-top" class="tab_referr_urls" style="display: none; ">
<div style="overflow:auto; max-height:200px;">
<?php
$query	= $db->select('stat_urls',null,"ORDER BY `hits` DESC LIMIT $referal_limit");
if($db->num($query) < 1) echo '<div class="padding"><div id="error_no_ani">Data kosong</div></div>';
else{

echo '<ul class="ul-box">';
while($data	= $db->fetch_obj($query)){
if(!empty($data->referrer)){
?>
<li title="<?php echo str_replace('http://', '', $data->referrer);?>"><a href="<?php echo $data->referrer?>"><?php echo limittxt(str_replace('http://', '', $data->referrer),50);?></a><span><?php echo $data->hits;?></span></li>
<?php }}?>
</ul>
<?php }?>
</div>
</div>

<div id="ref-domain" class="tab_referr_urls" style="display: none; ">
<div style="overflow:auto; max-height:200px;">
<?php
$query	= $db->select('stat_urls',null,"GROUP BY  `domain` ORDER BY `date_modif` DESC LIMIT $referal_limit");
if($db->num($query) < 1) echo '<div class="padding"><div id="error_no_ani">Data kosong</div></div>';
else{
echo '<ul class="ul-box">';
while($data	= $db->fetch_obj($query)){
if(!empty($data->domain)){
?>
<li><a href="<?php echo $data->referrer?>"><?php echo $data->domain;?></a><span><?php echo $data->hits;?></span></li>
<?php }}?>
</ul>
<?php }?>
</div>
</div>

<div id="ref-keywords" class="tab_referr_urls" style="display: none; ">
<div style="overflow:auto; max-height:200px;">
<ul class="ul-box">
<?php
$search_terms 	= $search_terms_hits = $search_terms_links = array();
$query			= $db->select('stat_urls',null,"GROUP BY `search_terms` ORDER BY `date_modif` DESC LIMIT $referal_limit");
if($db->num($query) < 1) echo '<div class="padding"><div id="error_no_ani">Data kosong</div></div>';
else{

echo '<ul class="ul-box">';
while($data		= $db->fetch_obj($query)){
	if(!empty($data->search_terms)){
		$search_terms[] = $data->search_terms;
		$search_terms_hits[] = $data->hits;
		$search_terms_links[] = $data->referrer;
	}
}
?>
<?php 
foreach($search_terms as $key => $val){ 
if( $key <= $referal_limit ){
?>
<li><a href="<?php echo $search_terms_links[$key]?>"><?php echo $val;?></a><span><?php echo $search_terms_hits[$key];?></span></li> 
<?php }}?>
</ul>
<?php }?>
</div>
</div>


</div>
<!--end-tabs-->
<?php
	
}
add_dashboard_widget( 'refering', 'Refering', 'refering', true );