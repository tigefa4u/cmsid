<?php
/**
 * @file data.php
 *
 */
//dilarang mengakses
if(!defined('_iEXEC')) exit;

global $login;

if( 'phpids/data.php' == is_load_values() 
&& $login->check() 
&& $login->level('admin') 
):

if( checked_option( 'phpids_limit' )  ) $phpids_limit = get_option('phpids_limit');
else $phpids_limit = 10;

?>

<script type="text/javascript">
/* <![CDATA[ */
$(document).ready(function(){
	
$(".tab_phpids").hide();
$(".tab_phpids:first").show();
$("ul.phpids li:first").addClass("active").show();
$("ul.phpids li").click(function() {
	$("ul.phpids li").removeClass("active");
	$(this).addClass("active");
	$(".tab_phpids").hide();
	var activeTab = $(this).find("a").attr("href");
	$(activeTab).slideDown('slow');
	return false;
});

});
/* ]]> */
</script>
<div style="clear:both"></div>
<ul class="phpids">
	<li class="active"><a href="#phpids-now">Sekarang</a></li>
	<li><a href="#phpids-ip">IP</a></li>
	<li><a href="#phpids-impact">Pengaruh</a></li>
</ul>
<div style="clear:both"></div>
<div class="tabs-content">
	<div id="phpids-now" class="tab_phpids" style="display: block; ">
	<?php echo phpids_monitor( 'now', $phpids_limit );?>
	</div>
	<div id="phpids-ip" class="tab_phpids" style="display: none; ">
	<?php echo phpids_monitor( 'ip', $phpids_limit );?>
	</div>
	<div id="phpids-impact" class="tab_phpids" style="display: none; ">
	<?php echo phpids_monitor( 'impact', $phpids_limit );?>
	</div>
</div>
<div style="clear:both"></div>

<?php endif;?>