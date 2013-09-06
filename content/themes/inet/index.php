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
<link href="<?php get_template_directory_uri(true); ?>/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php get_template_directory_uri(true); ?>/css/style-menu.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php get_template_directory_uri(true); ?>/js/menu.js"></script>
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
<div id="top-header">
<?php if( inet_theme_option('logo') ):?>
<div id="logo"><img src="<?php echo inet_theme_option('logo');?>" style="width:<?php echo inet_theme_option('logo_w');?>px;"></div>
<?php endif;?>
<a href="#" class="logo_title"><?php get_info( 'name', true ); ?></a>
<span class="logo_desc"><?php get_info( 'desc', true ); ?></span>
<?php if( inet_theme_option('artist') > 0 ):?>
<div id="demontran"></div>
<?php endif;?>
</div>
<div id="header">
<div class="mlmenu horizontal fade inaccesible">
<ul>
<?php echo dynamic_menus(2, 'class="horizontal fade"', false); ?>
</ul>
</div> 
</div>
<div id="content-top"></div> 
<div id="wrap-content">
<div id="content-wrap">
<div id="bg-menu"></div>
<!-- content-wrap-->
<?php if( get_sidebars_active(1) ):?>
<?php include 'sidebar-left.php';?>
<?php endif;?>
<div id="main">
<!--content-->
<?php the_content();?>
<!--end content-->
</div> 
<?php if( get_sidebars_active(2) ):
include 'sidebar.php';
endif; ?>
 <!-- content-wrap ends here -->  
 </div>

<!-- footer starts here --> 
<div id="footer"></div>
<!-- footer ends here -->

</div>
<div id="footer-img"> 
<div id="footer_text" style="padding-left:20px;">
 <div class="footer-left">
  <p>
    <a href="index.php">Home</a>&nbsp;|&nbsp;
    <a href="#">Site Map</a>&nbsp;|&nbsp;
    <a href="#">Contact Us</a>&nbsp;|&nbsp;
    <a href="#">Privacy</a>&nbsp;|&nbsp;
    <a href="#">Term of Use</a>&nbsp;|&nbsp;
    <a href="#">Site Credit</a></p>
   </div> 
 <div class="footer-right" style="padding-right:20px; margin-top:-15px;">
  <p class="align-right">
  &copy;  <a href="http://cmsid.org">cmsid.org</a>, All right reserved. xhtml validator<br />
Use of this website signifies your agrement to the Terms of Use<br />
Content provider by cmsid 2.2, Design & Contruct by <a href="http://cmsid.org">cmsid.org &trade;</a> (2010) </p>
 </div></div>
</div>
<!-- wrap ends here -->
</div>

<!-- execution time {timer} -->
</body>
</html>