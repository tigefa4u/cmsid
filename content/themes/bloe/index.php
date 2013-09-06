<?php
/**
 * The Index for our theme.
 *
 * @package ID
 * @subpackage iNet
 * @since iNet 1.2
 */
if(!defined('_iEXEC')) exit;
?><!DOCTYPE html>
<html <?php language_attributes();?>>
<head>
<title><?php the_title( true );?></title>
<meta charset="<?php get_info( 'charset', true ); ?>">
<meta name="Description" content="<?php get_info( 'description', true ); ?>">
<meta name="Keywords" content="<?php get_info( 'keywords', true ); ?>">
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link href="<?php get_template_directory_uri(true); ?>/css/default.css" rel="stylesheet" type="text/css" />
<link href="<?php get_template_directory_uri(true); ?>/css/message.css" rel="stylesheet" type="text/css" />
<link href="<?php get_template_directory_uri(true); ?>/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php get_template_directory_uri(true); ?>/css/style-menu.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php get_template_directory_uri(true); ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php get_template_directory_uri(true); ?>/js/jquery-ui.min.js"></script>
<script>
$(function() {
	$('.vertical li:has(ul)').addClass('parent');
	$('.horizontal li:has(ul)').addClass('parent');
});
$(document).ready(function(){
	$("#featured > ul").tabs({fx:{opacity: "toggle"}}).tabs("rotate", 7000, true);
});
</script>
<?php the_head();?>
</head>
<body>
<!-- wrap-->
<div id="wrap">
<div id="wrap-top">
<a href="#" class="logos"><img src="<?php get_template_directory_uri(true); ?>/images/logo.png"><div class="logotitle"><?php get_info( 'name', true ); ?></div></a><div class="slogan"><?php get_info( 'desc', true ); ?></div> 
<form  method="get" class="formSearch" action="<?php echo site_url()?>/index.php">
<input type="submit"value="" class="formQuery_s">
<input type="text" name="q" class="formQuery"  value="Search..." onfocus="if(this.value=='Search...'){this.value=''}" onblur="if(this.value==''){this.value='Search...'}"/>
<input type="hidden" name="com" value="search" />
</form>
<div class="mlmenu horizontal fade inaccesible">
<ul>
<?php echo dynamic_menus(2, 'class="horizontal fade"', false); ?>
</ul>
</div> 
</div>
<div id="wrap-middle">
<div class="wrap-middle">
<div id="main">
<?php the_content();?>
<div style="clear:both;"></div>
</div>
<?php if( get_sidebars_active(1) ):?>
<!--start-left-->
<div id="sidebar">
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Home/Page Left') ) : ?>
<?php endif; ?>	
</div>
<div style="clear:both;"></div>
<?php endif; ?>	
</div>
</div>
<div id="wrap-bottom">
<div class="wrap-bottom">&copy; 2013, Powered by cmsid.org <?php echo date('Y');?> &bull; | All Right Reserved.</div>
</div>
</div>
</body>
</html>