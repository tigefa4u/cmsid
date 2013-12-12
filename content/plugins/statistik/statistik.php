<?php
/**
 * @file: statistik/statistik.php
 * @dir: content/plugins
 */
 
/*
Plugin Name: Statistik Monitoring Website
Plugin URI: http://cmsid.org/#
Description: Plugin bla bla bla
Author: Eko Azza
Version: 1.2.1
Author URI: http://cmsid.org/
*/ 

if(!defined('_iEXEC')) exit;

if( !get_query_var('sys') && !get_query_var('apps') ):
	add_action('the_head_admin', 'statistik_register');
	add_action('the_head_admin', 'statistik_control_js');
endif;

function statistik_register() {
	?>     
    
    <script type="text/javascript" src="<?php echo plugins_url();?>statistik/jquery.jqplot.min.js"></script>
    <script type="text/javascript" src="<?php echo plugins_url();?>statistik/plugins/jqplot.canvasTextRenderer.min.js"></script>
    <script type="text/javascript" src="<?php echo plugins_url();?>statistik/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>
    <script type="text/javascript" src="<?php echo plugins_url();?>statistik/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
    <script type="text/javascript" src="<?php echo plugins_url();?>statistik/plugins/jqplot.dateAxisRenderer.min.js"></script>
    <script type="text/javascript" src="<?php echo plugins_url();?>statistik/plugins/jqplot.logAxisRenderer.min.js"></script>
    <script type="text/javascript" src="<?php echo plugins_url();?>statistik/plugins/jqplot.categoryAxisRenderer.min.js"></script>
    <script type="text/javascript" src="<?php echo plugins_url();?>statistik/plugins/jqplot.barRenderer.min.js"></script>
    <link rel="stylesheet" type="text/css" hrf="<?php echo plugins_url();?>statistik/jquery.jqplot.min.css" />
    <?php
}

function statistik_control_js() {		
?>
<script type="text/javascript">
$(document).ready(function(){
	
$(".tab_stat").hide();
$(".tab_stat:first").show();
$("ul.stat li:first").addClass("active").show();
$("ul.stat li").click(function() {
	$("ul.stat li").removeClass("active");
	$(this).addClass("active");
	$(".tab_stat").hide();
	var activeTab = $(this).find("a").attr("href");
	$(activeTab).slideDown('slow');
	return false;
});

function fchart(id){
	$.getJSON("?request&load=statistik/data.php&plg=yes&op="+op,
	function (data) {
		$('#chart_display_'+op).html(''); 
    	$.jqplot._noToImageButton = true;
		var plot2 = $.jqplot('chart_display_'+id, [data], {
        seriesColors: ["rgb(97, 162, 30)"],
        highlighter: {
            show: true,
            sizeAdjust: 1,
            tooltipOffset: 9
        },
			grid: {
				background: 'rgba(57,57,57,0.0)',
				drawBorder: false,
				shadow: false,
				gridLineColor: '#444444',
				gridLineWidth: 0.1
			},
			seriesDefaults: {
				rendererOptions: {
					smooth: true,
					animation: {
						show: true
					}
				},
				shadow: false,
				showMarker: false
			},
		      axes: {
		        xaxis: {
		          renderer: $.jqplot.CategoryAxisRenderer,
		          tickRenderer: $.jqplot.CanvasAxisTickRenderer,
		          tickOptions: {
		              labelPosition: 'middle',
		              angle: 90
		          }
		           
		        },
		        yaxis: {
		          labelRenderer: $.jqplot.CanvasAxisLabelRenderer
		        }
		      }
		});
		$('.jqplot-highlighter-tooltip').addClass('ui-corner-all')
	});
}
	
	
	var op = 'os';  
	fchart(op); 
	$("ul.stat li").click(function() {
		var activeTab = $(this).find("a").attr("href");
		if( activeTab ){
			op = activeTab.replace('#stat-','');
		}		
		fchart(op);	    
	});
});
</script>
<?php
}

function statistik_init(){
		
	if ( ! class_exists( 'stats' ) )
		require( plugin_path . '/statistik/class-statistic.php' );
	if ( !class_exists('statistik') )
		require( plugin_path . '/statistik/class.php' );
}
add_action('plugins_loaded', 'statistik_init');

function statistik_monitoring(){
global $db,$class_country;

$dp_content = '<div class="padding">';
$dp_content.= 'Hapus dan kembalikan data menjadi kosong.';
$dp_content.= '</div>';

$dp_footer = '<input onclick="return confirm(\'Are You sure delete all records?\')" type="submit" name="submitResetStatistik" value="Reset to default" class="button red" />';

$setting = array(
	'dp_id' => 'statistik_monitoring',
	'dp_title' => 'Pengaturan Statistik',
	'dp_content' => $dp_content,	
	'dp_footer' => $dp_footer
);
add_dialog_popup( $setting );
?>
<style type="text/css">
.tab_stat
{
	padding:0;
}
.tab_stat ul.ul-box
{
	
}
ul.stat
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
ul.stat li 
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
ul.stat li a
{
	text-decoration: none;
	display: block;
	padding: 0 5px 0 5px;
	outline: none;
}
ul.stat li a:hover 
{
	background: #f2f2f2;
    border-top-left-radius: 2px;
    border-top-right-radius: 2px;
    -moz-border-radius-topleft: 2px;
    -moz-border-radius-topright: 2px;
}
html ul.stat li.active,
html ul.stat li.active a:hover
{
	background: #f2f2f2;
	border-bottom: 1px solid #ccc;
	border-bottom-style:dotted;
}
</style>
<div style="clear:both"></div>
<!--start-tabs-->
<!--start-->
<ul class="stat">
<li class="active"><a href="#stat-os">OS</a></li>
<li><a href="#stat-day">Hari</a></li>
<li><a href="#stat-month">Bulan</a></li>
<li><a href="#stat-clock">Jam</a></li>
<li><a href="#stat-browser">Browser</a></li>
<li><a href="#stat-country">Negara</a></li>
</ul>
<div style="clear:both"></div>
<div class="tabs-content">
<?php 
if( isset($_POST['submitResetStatistik']) ){
	$stat->reset_statistic();
	redirect('?admin');	
}
?>
<div id="stat-os" class="tab_stat" style="display: block; ">
<div class="padding"><div id="chart_display_os" style="padding:5px; 0"><center style="padding:10px;"><div class="_ani_loading"><span style="clear:both">Memuat...</span></div></center></div></div>
</div>
<div id="stat-day" class="tab_stat" style="display: none; ">
<div class="padding"><div id="chart_display_day" style="padding:5px; 0"><center style="padding:10px;"><div class="_ani_loading"><span style="clear:both">Memuat...</span></div></center></div></div>
</div>
<div id="stat-month" class="tab_stat" style="display: none; ">
<div class="padding"><div id="chart_display_month" style="padding:5px; 0"><center style="padding:10px;"><div class="_ani_loading"><span style="clear:both">Memuat...</span></div></center></div></div>
</div>
<div id="stat-clock" class="tab_stat" style="display: none; ">
<div class="padding"><div id="chart_display_clock" style="padding:5px; 0"><center style="padding:10px;"><div class="_ani_loading"><span style="clear:both">Memuat...</span></div></center></div></div>
</div>
<div id="stat-browser" class="tab_stat" style="display: none; ">
<div class="padding"><div id="chart_display_browser" style="padding:5px; 0"><center style="padding:10px;"><div class="_ani_loading"><span style="clear:both">Memuat...</span></div></center></div></div>
</div>
<div id="stat-country" class="tab_stat" style="display: none; ">
<div class="padding"><div id="chart_display_country" style="padding:5px; 0"><center style="padding:10px;"><div class="_ani_loading"><span style="clear:both">Memuat...</span></div></center></div></div>
</div>
<!--end-tabs-->
<br />
</div>
<?php
}

add_dashboard_widget( 'statistik_monitoring', 'Statistik Monitoring', 'statistik_monitoring', true );