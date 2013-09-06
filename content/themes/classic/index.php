<?php
/**
 * The Index for our theme.
 *
 * @package ID
 * @subpackage Classic
 * @since Classic 1.3
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
<link href="<?php echo site_url(); ?>/libs/css/colors.css" rel="stylesheet" />
<link href="<?php echo site_url(); ?>/libs/css/table.css" rel="stylesheet" />
<link href="<?php get_template_directory_uri(true); ?>/css/default.css" rel="stylesheet" type="text/css">
<link href="<?php get_template_directory_uri(true); ?>/css/forms.css" rel="stylesheet" type="text/css">
<link href="<?php get_template_directory_uri(true); ?>/css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php get_template_directory_uri(true); ?>/js/jquery.min.js"></script>
<link href="<?php get_template_directory_uri(true); ?>/css/style-menu.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php get_template_directory_uri(true); ?>/js/menu.js"></script>
<?php the_head();?>
</head>
<body>
<div id="wrap">

<div class="wrap-top"></div>
<!--start-head-->
<div id="header"> 
<img src="<?php get_template_directory_uri(true); ?>/rotator.php" class="header-image">
<div id="header-wrap">
<div id="text">
<div class="logo-ID" title="<?php get_info( 'name', true ); ?>"></div>
</div>
<div class="mlmenu horizontal fade inaccesible">
<ul>
<?php echo dynamic_menus(2, 'class="horizontal fade"', false); ?>
</ul>
</div>  
<form id="searching" action="#" method="post"><input type="text" value="" placeholder="Searching here..." class="searching-text" name="q"><button class="searching-button">Go</button></form>
</div> 
</div>
<!--start-content-->
<div id="wrap-content">
<!--content-wrap-->
<div id="content-wrap">
<?php if( get_sidebars_active(1) ):?>
<!--start-left-->
<div id="sidebar">
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Home/Page Left') ) : ?>
<?php endif; ?>	
</div>
<?php endif; ?>	
<!--start-main-->
<div id="main">
<?php the_content();?>
</div> 
<?php if( get_sidebars_active(2) ):?>
<!--start-right-->
<div id="rightbar">
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Home Right #Full Width') ) : ?>
<?php endif; ?>	
</div>
<?php endif; ?>	
</div>
</div>
<!--start-footer-->
<div id="footer"> 
<div id="footer_text">
<div class="footer-right">
<p class="align-right">
&copy; <b><?php echo get_option('copyright');?></b> <?php echo date('Y');?> &bull; Powered by <b><?php echo get_option('poweredby');?></b>
</p>
</div>
</div>
</div>

</div>
</body>
</html>
