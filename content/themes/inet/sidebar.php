<?php $get_query = get_query_var('com'); if ( $get_query == 'post' || $get_query == 'page' || $get_query == '404' ) {?>
    <div class="ad160x600">
    <a href="#" target="_blank">
    <img src="<?php echo inet_theme_option('ads160x600');?>">
    </a>
    </div>
    <div class="clear"></div>
    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Page Right') ) : ?>
    <?php endif; ?>	
<?php }else{ ?>
    <div id="rightbar">
    <!--start-right-->
    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Home Right #Full Width') ) : ?>
    <?php endif;?>
    <!--end-right-->
    </div>
<?php } ?>