<?php
/**
 * @file feed.php
 *
 */
//dilarang mengakses
if(!defined('_iEXEC')) exit;

global $login;

if( 'libs/ajax/feed.php' == is_load_values() 
&& $login->check() 
&& $login->level('admin') 
):

@set_time_limit(0);
$json = new JSON();
$rssfeed = doing_feed();
$news_feeds_old = (array) $json->decode( $rssfeed );
?>
<script>
/* <![CDATA[ */
$(document).ready(function(){

$(".tab_feednews").hide();
$(".tab_feednews:first").show();
$("ul.feednews li:first").addClass("active").show();
$("ul.feednews li").click(function() {
	$("ul.feednews li").removeClass("active");
	$(this).addClass("active");
	$(".tab_feednews").hide();
	var activeTab = $(this).find("a").attr("href");
	$(activeTab).slideDown('slow');
	return false;
});

});
/* ]]> */
</script>
<div style="clear:both"></div>
<ul class="feednews">
<?php 
$i1 = 0;
foreach( $news_feeds_old as $v ){
	$active = '';
	if( $i1 == 0 ) $active = ' class="active"';
?>
    <li<?php echo $active;?>><a href="#<?php echo feed_add_space($v->feed_title);?>"><?php echo $v->feed_title;?></a></li>
<?php $i1++;}?>
</ul>
<div style="clear:both"></div>
<div class="tabs-content">
<?php 
$i2 = 0;
foreach( $news_feeds_old as $v ){	
	$disp = 'none';
	if( $i2 == 0 ) $disp = 'block';
?>
    <div id="<?php echo feed_add_space( $v->feed_title );?>" class="tab_feednews" style="display: <?php echo $disp;?>; ">
	<?php  
	if( !empty( $v->error ) || count($v->feed_content) < 1 ){
		echo '<div class="padding"><div id="error_no_ani"><strong>ERROR</strong>: The Feed not connect to server</div></div>';
	}else{
		echo '<div style="overflow:auto; max-height:200px;">';
		echo ul_feed( $v->feed_content );
		echo '</div>';
	}
	?>
    </div>
<?php 
$i2++;
}
?>
</div>

<?php endif;?>