<div id="sidebar">
<div class="mlmenu vertical blindv delay inaccesible">
<ul>
	<?php echo dynamic_menus(1, 'class="vertical white"', false); ?>
</ul>
</div>
<!--start-right-->
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Home/Page Left') ) : ?>
<?php endif; ?>	
<!--end-right-->
</div>

