<?php
/**
 * @file lw.php
 *
 */
//dilarang mengakses
if(!defined('_iEXEC')) exit;

global $login;

if( 'activity/data.php' == is_load_values() 
&& $login->check() 
&& $login->level('admin') 
):

if( checked_option( 'activity_record_echo_limit' )  ) $activity_record_echo_limit = get_option('activity_record_echo_limit');
else $activity_record_echo_limit = 50; //limit default
?>
<script type="text/javascript">
/* <![CDATA[ */
$(document).ready(function(){
	
$(".tab_activity_records").hide();
$(".tab_activity_records:first").show();
$("ul.activity_records li:first").addClass("active").show();
$("ul.activity_records li").click(function() {
	$("ul.activity_records li").removeClass("active");
	$(this).addClass("active");
	$(".tab_activity_records").hide();
	var activeTab = $(this).find("a").attr("href");
	$(activeTab).slideDown('slow');
	return false;
});

});
/* ]]> */
</script>	
<div style="clear:both"></div>
<ul class="activity_records">
	<li class="active"><a href="#recod-all">Semua</a></li>
	<li><a href="#recod-now">Sekarang</a></li>
	<li><a href="#recod-me">Saya</a></li>
</ul>
<div style="clear:both"></div>
<div class="tabs-content">
	<div id="recod-all" class="tab_activity_records" style="display: block; ">
	<?php echo get_activity_all( null, null, $activity_record_echo_limit )?>
	</div>
	<div id="recod-now" class="tab_activity_records" style="display: none; ">
	<?php echo get_activity_now( $activity_record_echo_limit )?>
	</div>
	<div id="recod-me" class="tab_activity_records" style="display: none; ">
	<?php echo get_activity_me( $activity_record_echo_limit )?>
	</div>
</div>
<div style="clear:both"></div>
<?php
endif;
?>