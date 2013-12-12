<div id="column1">
<div class="mlmenu vertical blindv delay inaccesible">
<ul>
	<?php echo dynamic_menus(1, 'class="vertical white"', false); ?>
</ul>
</div>
<?php if( portal_theme_option('topnews') > 0 ):?>
<div class="leftsidebar">
<h3><a href="#">Top News</a></h3>
		<div class="box">
<?php $sqlFrontArtikel = $db->select( 'post',array('type'=>'post','status'=>1,'approved'=>1),'ORDER BY hits DESC LIMIT 5' );
while ($wFrontArtikel  = $db->fetch_obj($sqlFrontArtikel)):?>
			<div class="leftnews">
            	<?php $thumb = get_template_directory_uri().'/images/no-preview.png'; if( !empty($wFrontArtikel->thumb) ): $thumb = content_url('/uploads/post/'.$wFrontArtikel->thumb); endif;?>                
				<div class="thumb">
					<a href="<?php echo do_links('post', array('view'=>'item','id'=>$wFrontArtikel->id,'title'=>$wFrontArtikel->title) );?>" rel="bookmark"><img src="<?php echo site_url('/?request&load=libs/timthumb.php'); ?>&src=<?php echo $thumb;?>&amp;h=36&amp;w=36&amp;zc=1" alt="<?php echo $wFrontArtikel->title; ?>" style="width:36px; height:36px;" /></a>
				</div> <!--end: thumb-->
				<span><a href="<?php echo do_links('post', array('view'=>'item','id'=>$wFrontArtikel->id,'title'=>$wFrontArtikel->title) );?>" rel="bookmark"><?php echo $wFrontArtikel->title; ?></a></span>
				<div class="clear"></div>
			</div> <!--leftnews-->				
			<?php endwhile; ?>
		</div>
</div>
<?php endif;?>
<div class="leftsidebar">
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Home/Page Left') ) : ?>
<?php endif; ?>
</div>
</div>

