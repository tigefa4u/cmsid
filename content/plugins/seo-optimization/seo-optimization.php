<?php
/**
 * @file: seo-optimization.php
 * @type: plugin
 */
/*
Plugin Name: SEO Optimization
Plugin URI: http://cmsid.org/#
Description: Plugin SEO Optimization Search Engine User Frendly.
Author: Eko Azza
Version: 1.3.2
Author URI: http://cmsid.org/
*/ 
//not direct access
if(!defined('_iEXEC')) exit;

function seo_optimization(){	
	global $rewrite_link, $rewrite_app, $rewrite_data;
	
	if( !checked_option('rewrite') ) $o = 'advance';
	else $o = get_option('rewrite');
	
	if( !checked_option('rewrite_html') ) $o_t = 0;
	else $o_t = get_option('rewrite_html');
	
	$o_type_a = "";
	$o_type_b = "/";
	if( $o_t > 0 && $o != 'advance' ){
		$o_type_a = ".html";
		$o_type_b = ".html";
	}
	
	$e = new engine;	
	$h = rewrite_h();			
	$f = rewrite_f();
	
	if( !empty($rewrite_data) ):
	
		if( !is_array($rewrite_data) )
		return false;
		
		extract($rewrite_data, EXTR_SKIP);
		
	endif;
	
	if( $o == 'advance' ) rewrite_d();	
	
	$fw = $fw_default = "";	
	if($o == 'slash' 
	|| $o == 'slash-clear' 
	|| $o == 'clear' )
	{
		if( $rewrite_app && $view && $id && $title && $pg ){
			$rewrite_link = "$rewrite_app/$view/$id/".$e->judul($title)."/pg/$pg$o_type_a";
		}elseif( $rewrite_app && $view && $id && $pg ){
			$rewrite_link = "$rewrite_app/$view/$id/pg/$pg$o_type_a";
		}elseif( $rewrite_app && $view && $pg ){
			$rewrite_link = "$rewrite_app/$view/pg/$pg$o_type_a";
		}elseif( $rewrite_app && $pg ){
			$rewrite_link = "$rewrite_app/pg/$pg$o_type_a";		
		}elseif( $rewrite_app && $view && $id && $title && $go && $to ){
			$rewrite_link = "$rewrite_app/$view/$id/".$e->judul($title)."/go/$go/to/$to$o_type_a";
		}elseif( $rewrite_app && $view && $id && $title && $go ){
			$rewrite_link = "$rewrite_app/$view/$id/".$e->judul($title)."/go/$go$o_type_a";
		}elseif( $rewrite_app && $view && $id && $title ){
			$rewrite_link = "$rewrite_app/$view/$id/".$e->judul($title).$o_type_a;
		}elseif( $rewrite_app && $view && $id ){
			$rewrite_link = "$rewrite_app/$view/$id$o_type_a";
		}elseif( $rewrite_app && !$view && $id ){
			$rewrite_link = "$rewrite_app/$id/".$e->judul($title).$o_type_a;
		}elseif( $rewrite_app && $view ){
			$rewrite_link = "$rewrite_app/$view$o_type_a";
		}elseif( $rewrite_app ){
			$rewrite_link = "$rewrite_app$o_type_a";
		}
		
		$fw_default.= "\n\n";
		$fw_default.= "RewriteRule ^([a-z-]+)/([a-z-]+)/([a-z0-9-]+)/([a-z0-9-]+)/pg/([0-9]+)$o_type_b?$ index.php?com=$1&view=$2&id=$3&pg=$5\n";	
		$fw_default.= "RewriteRule ^([a-z-]+)/([a-z-]+)/([a-z0-9-]+)/pg/([0-9]+)$o_type_b?$ index.php?com=$1&view=$2&id=$3&pg=$4\n";
		$fw_default.= "RewriteRule ^([a-z-]+)/([a-z-]+)/pg/([0-9]+)$o_type_b?$ index.php?com=$1&view=$2&pg=$3\n";
		$fw_default.= "RewriteRule ^([a-z-]+)/pg/([0-9]+)$o_type_b?$ index.php?com=$1&pg=$2\n";
		
		$fw_default.= "\n\n";		
		$fw_default.= "RewriteRule ^([a-z-]+)/([a-z-]+)/([a-z0-9-]+)/([a-z0-9-]+)/([a-z]+)/([a-z0-9]+)/([a-z]+)/([a-z0-9]+)$o_type_b?$ index.php?com=$1&view=$2&id=$3&$5=$6&$7=$8\n";
		$fw_default.= "RewriteRule ^([a-z-]+)/([a-z-]+)/([a-z0-9-]+)/([a-z0-9-]+)/([a-z]+)/([a-z0-9]+)$o_type_b?$ index.php?com=$1&view=$2&id=$3&$5=$6\n";
		$fw_default.= "RewriteRule ^([a-z-]+)/([a-z-]+)/([a-z0-9-]+)/([a-z0-9-]+)$o_type_b?$ index.php?com=$1&view=$2&id=$3\n";	
		$fw_default.= "RewriteRule ^([a-z-]+)/([a-z-]+)/([a-z0-9-]+)$o_type_b?$ index.php?com=$1&view=$2&id=$3\n";
		$fw_default.= "RewriteRule ^([a-z-]+)/([0-9]+)/([a-z0-9-]+)$o_type_b?$ index.php?com=$1&id=$2\n";
		$fw_default.= "RewriteRule ^([a-z-]+)/([a-z-]+)$o_type_b?$ index.php?com=$1&view=$2\n";
		$fw_default.= "RewriteRule ^([a-z-]+)$o_type_b?$ index.php?com=$1\n";
	}
	
	if( $o == 'slash-clear' 
	&& ( $rewrite_app == 'post' 
	|| $rewrite_app == 'page' ) )
	{
		if( $view && $id && $title && $pg ){
			$rewrite_link = "$view/".$e->judul($title)."/pg/$pg$o_type_a"; 
		}elseif( $view && $id && $pg ){
			$rewrite_link = "$view/$id/pg/$pg$o_type_a";
		}elseif( $view && $id && $title && $go && $to ){
			$rewrite_link = "$view/".$e->judul($title)."/go/$go/to/$to$o_type_a";
		}elseif( $view && $id && $title && $go ){
			$rewrite_link = "$view/".$e->judul($title)."/go/$go$o_type_a";
		}elseif( $view && $id && $title ){ 
			$rewrite_link = "$view/".$e->judul($title).$o_type_a;
		}elseif(!$view && $id && $title  ){
			$rewrite_link = "$rewrite_app/".$e->judul($title).$o_type_a;
		}elseif( $view && $id ){
			$rewrite_link = "$view/$id$o_type_a";
		}
		
		$fw.= "\n\n";
		$fw.= "RewriteRule ^([a-z-]+)/([a-z0-9-]+)/pg/([0-9]+)$o_type_b?$ index.php?com=post&view=$1&id=$2&pg=$3\n";
		
		$fw.= "\n\n";
		$fw.= "RewriteRule ^([a-z-]+)/([a-z0-9-]+)/([a-z]+)/([a-z0-9]+)/([a-z]+)/([a-z0-9]+)$o_type_b?$ index.php?com=post&view=$1&id=$2&$3=$4&$5=$6\n";
		$fw.= "RewriteRule ^([a-z-]+)/([a-z0-9-]+)/([a-z]+)/([a-z0-9]+)$o_type_b?$ index.php?com=post&view=$1&id=$2&$3=$4\n";
		
		$fw.= "\n\n";
		$fw.= "RewriteRule ^page/([a-z0-9-]+)$o_type_b?$ index.php?com=page&id=$1\n";
		$fw.= "RewriteRule ^archive/([a-z0-9-]+)$o_type_b?$ index.php?com=post&view=archive&id=$1\n";
		$fw.= "RewriteRule ^item/([a-z0-9-]+)$o_type_b?$ index.php?com=post&view=item&id=$1\n";
		$fw.= "RewriteRule ^category/([a-z0-9-]+)$o_type_b?$ index.php?com=post&view=category&id=$1\n";
		$fw.= "RewriteRule ^tags/([a-z0-9-]+)$o_type_b?$ index.php?com=post&view=tags&id=$1\n";
	}
	
	if( $o == 'clear' 
	&& ( $rewrite_app == 'post' 
	|| $rewrite_app == 'page' ) )
	{
		if( $view && $id && $title && $pg ){
			$rewrite_link = "$view-".$e->judul($title)."-pg-$pg$o_type_a"; 
		}elseif( $view && $id && $pg ){
			$rewrite_link = "$view-$id-pg-$pg$o_type_a";
		}elseif( $view && $id && $title && $go && $to ){
			$rewrite_link = "$view-".$e->judul($title)."-go-$go-to-$to$o_type_a";
		}elseif( $view && $id && $title && $go ){
			$rewrite_link = "$view-".$e->judul($title)."-go-$go$o_type_a";
		}elseif( $view && $id && $title ){ 
			$rewrite_link = "$view-".$e->judul($title).$o_type_a;
		}elseif(!$view && $id && $title  ){
			$rewrite_link = "$rewrite_app-".$e->judul($title).$o_type_a;
		}elseif( $view && $id ){
			$rewrite_link = "$view-$id$o_type_a";
		}
		
		$fw.= "\n\n";
		$fw.= "RewriteRule ^item-([a-z0-9-]+)-go-([a-z0-9-]+)-to-([a-z0-9-]+)$o_type_b?$ index.php?com=post&view=item&id=$1&go=$2&to=$3\n";
		$fw.= "RewriteRule ^item-([a-z0-9-]+)-go-([a-z0-9-]+)$o_type_b?$ index.php?com=post&view=item&id=$1&go=$2\n";
		$fw.= "RewriteRule ^archive-([a-z0-9-]+)-pg-([0-9]+)$o_type_b?$ index.php?com=post&view=archive&id=$1&pg=$2\n";
		$fw.= "RewriteRule ^category-([a-z0-9-]+)-pg-([0-9]+)$o_type_b?$ index.php?com=post&view=category&id=$1&pg=$2\n";
		$fw.= "RewriteRule ^tags-([a-z0-9-]+)-pg-([0-9]+)$o_type_b?$ index.php?com=post&view=tags&id=$1&pg=$2\n";
		
		$fw.= "\n\n";
		$fw.= "RewriteRule ^page-([a-z0-9-]+)$o_type_b?$ index.php?com=page&id=$1\n";
		$fw.= "RewriteRule ^item-([a-z0-9-]+)$o_type_b?$ index.php?com=post&view=item&id=$1\n";
		$fw.= "RewriteRule ^archive-([a-z0-9-]+)$o_type_b?$ index.php?com=post&view=archive&id=$1\n";
		$fw.= "RewriteRule ^category-([a-z0-9-]+)$o_type_b?$ index.php?com=post&view=category&id=$1\n";
		$fw.= "RewriteRule ^tags-([a-z0-9-]+)$o_type_b?$ index.php?com=post&view=tags&id=$1\n";
		
		$fw.= "\n\n";
		$fw.= "RewriteRule ^([a-z-]+)-([a-z0-9-]+)-pg-([0-9]+)$o_type_b?$ index.php?com=post&view=$1&id=$2&pg=$3\n";
		
		$fw.= "\n\n";
		$fw.= "RewriteRule ^([a-z-]+)-([a-z0-9-]+)-go-([a-z0-9]+)-to-([a-z0-9]+)$o_type_b?$ index.php?com=post&view=$1&id=$2&go=$3&to=$4\n";
		$fw.= "RewriteRule ^([a-z-]+)-([a-z0-9-]+)-go-([a-z0-9]+)$o_type_b?$ index.php?com=post&view=$1&id=$2&go=$3\n";
		
	}
	
	if( $fw_default ) $fw.= $fw_default;
	if( $rewrite_link ) rewrite_w( $h . $fw . $f);
}

function rewrite_w($c){
	$f = fopen(".htaccess", "w");
	fwrite( $f, $c );
	fclose( $f) ;
}

function rewrite_h(){	
$base_dir = dirname($_SERVER["SCRIPT_NAME"]);
$base_dir = filter_txt($base_dir);
return "<IfModule mod_rewrite.c> 
Options -MultiViews
Options +FollowSymlinks
RewriteEngine On
RewriteBase $base_dir\n";
}

function rewrite_f(){		
return "</IfModule>
<Files ~ \"^.*\.([Hh][Tt][Aa])\">
order allow,deny
deny from all
satisfy all
</Files>";
}


function get_uri(){
	$return = str_replace(''.$_SERVER['PHP_SELF'].'','/',$_SERVER['REQUEST_URI']);
	return $return;
}

add_action('rewrite','seo_optimization');