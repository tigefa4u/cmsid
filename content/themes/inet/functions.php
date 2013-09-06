<?php 
/**
 * @fileName: functions.php
 * @dir: content/themes/
 */
if(!defined('_iEXEC')) exit;

function inet_theme_option( $param = null, $args = '' ){	
	$defaults = array(
		'logo' => get_template_directory_uri() .'/images/logo.png',
		'logo_w' => 120,
		'artist' => 1,
		'ads160x600' => get_template_directory_uri() .'/images/ads/ads160x600.jpg',		
		'limit_post' => 10
	);
	
	if( checked_option( 'inet_options' ) && empty($args) ){
		$args = get_option('inet_options');
		$args = (array) json_decode( $args );
	}
	
	$r = parse_args( $args, $defaults );
	
	if( $param )
		return $r[$param];
	else
		return $r;
}

function the_content_home(){
	
	if(!the_contents() )
		require_once( 'page-front.php' );		
}

function inet_widgets_init() {
if (function_exists('register_sidebar'))
{
	register_sidebar(array(
		'name'			=> 'Home/Page Left',
	    'before_widget'	=> '',
	    'after_widget'	=> '</div>',
	    'before_title'	=> '<h1 class="bg">',
	    'after_title'	=> '</h1><div class="box">',
	));		
	
    register_sidebar(array(
		'name'			=> 'Home Right #Full Width',
        'before_widget'	=> '',
        'after_widget'	=> '</div>',
        'before_title'	=> '<h1 class="bg">',
        'after_title'	=> '</h1><div class="box">',
    ));		
}}
add_action( 'widgets_init', 'inet_widgets_init' );

function view_action(){
	global $view;

if( $view ){
if( 
get_sidebar_action('sidebar-1') == false  && 
get_sidebar_action('sidebar-2') == false 
){?>
<style type="text/css">
#main{width:99%; float:left;}
</style>
<?php	
}elseif( 
get_sidebar_action('sidebar-1') == true  && 
get_sidebar_action('sidebar-2') == false  
){?>
<style type="text/css">
#sidebar{width:20%;float:left;}
#main{width:78%; float:left;}
</style>
<?php
}elseif( 
get_sidebar_action('sidebar-1') == false  && 
get_sidebar_action('sidebar-2') == true  
){?>
<style type="text/css">
#main{width:78%;float:left;}
#rightbar{width:20%; float:right;}
</style>
<?php
}elseif( 
in_array($view, array('archive','page','page-full') ) 
&& get_sidebar_action('sidebar-1') == true  
&& get_sidebar_action('sidebar-2') == true  ){?>
<style type="text/css">
#sidebar{width:20%;float:left;}
#main{width:59%; float:left;}
#rightbar{width:24%;float:right;}
</style>
<?php
}else{
?>
<style type="text/css">
#sidebar{width:20%;float:left;}
#main{width:54%; float:left;}
#rightbar{width:24%;float:right;}
</style>
<?php
}
}
}
add_action('the_head','view_action');

function add_the_content_view( $data ){
global $view;

$view = $data->view;
	
if( $data->the_title )
$GLOBALS['the_title'] = $data->the_title;
if( $data->the_desc )
$GLOBALS['the_desc'] = $data->the_desc;
if( $data->the_key )
$GLOBALS['the_key'] = $data->the_key;

if( $data->view == 'page-full' ){?>
    <?php if ( !empty($data->title) ) : ?>
		<div class="bg"><?php echo $data->browse?></div>
	  	<h4 class="bg"><?php echo $data->title?></h4>
        <div class="border"><?php echo $data->posted?></div>
	  	<div class="border">
	    	<?php echo $data->content?>
      		<div class="clear"></div>
	    	<div class="tags">
	      		<?php the_tags(); ?>
	    	</div> <!--end: tags-->
	  	</div>
	  	<?php comments_template(); ?>
    <?php else : ?>
        <h4 class="bg">Page Not Found</h4>
    <?php endif; ?>
<?php }elseif( $data->view == 'page' ){?>
    <?php if ( !empty($data->title) ) : ?>
	<h4 class="bg"><?php echo $data->title; ?></h4>
	<div class="border">
    	<?php echo $data->content; ?>
      <div class="clear"></div>
	</div>
    <?php else : ?>
        <h4 class="bg">Page Not Found</h4>
    <?php endif; ?>
<?php }elseif( $data->view == 'archive' ){?>
	<?php if ( is_array($data->content) && count($data->content) > 0 ) : ?>
	<div class="bg"><?php echo $data->browse;?></div>
	<?php foreach ( $data->content as $content ) :?>
  	<h4 class="bg"><?php echo $content[title]; ?></h4>
    <div class="news">
    <?php if( file_exists('content/uploads/post/'.$content[thumb]) && !empty($content[thumb]) ):?>
	<div class="thumb">
    <a href="#" rel="bookmark" class="img-url">
    <img src="<?php echo $content[thumb]; ?>&h=100&w=100&zc=1" alt="">
    </a>
    </div>
    <?php endif;?>
	<?php echo $content[content]; ?>
    <div style="clear:both"></div>
    </div>		
                
    <p class="post-footer">					
    <a href="<?php echo $content[title_url]; ?>" title="<?php echo $content[title]; ?>" class="readmore">Read more</a>
    <span class="comments">By <a href="<?php echo $content[title_url]; ?>#<?php echo $content[author]; ?>"><?php echo $content[author]; ?></a></span>
    <span class="date"><?php echo datetimes2( $content[date] ); ?></span>	
    </p>
  <?php endforeach; ?>
  	<div class="clear"></div>
    <?php if( count($data->paging) > 0 ):?>
  	<div class="pagenavi">  
    <?php 
	$paging = new Pagination();
	$paging->set('urlscheme',$data->paging[urlscheme]);
	$paging->set('perpage',$data->paging[perpage]);
	$paging->set('page',$data->paging[page]);
	$paging->set('total',$data->paging[total]);
	$paging->set('nexttext','Next');
	$paging->set('prevtext','Previous');
	$paging->set('focusedclass','selected');
	$paging->display();
	?>        
    <div class="clear"></div>
	</div> <!--end: pagenavi-->
    <?php endif;?>
	<?php else : ?>
        <h4 class="bg">Page Not Found</h4>
    <?php endif; ?>
<?php
}elseif( $data->view == '404' ){
set_query_var('com','404');
?>
	<h4 class="bg">Page Not Found</h4>
<?php 
}elseif( $data->view == 'apps' ){
?>
	<h4 class="bg"><?php echo $data->title;?></h4>
	<div><?php echo $data->content;?></div>
<?php
}
}

function get_comment_total( $id ){
	global $db;
	
	$id = filter_int( $id );
		
	$qry_comment			= $db->select("post_comment",array('post_id'=>$id,'approved'=>1,'comment_parent'=>0)); 
	$num1					= $db->num($qry_comment);
	$num2					= 0;
	while ($data			= $db->fetch_array($qry_comment)) {
		$no_comment 		= filter_int( $data['comment_id'] );
						
		$qry_comment2		= $db->select("post_comment",array('approved'=>1,'comment_parent'=>$no_comment)); 
		$num2				= $num2+$db->num($qry_comment2);
	}
	return $num1+$num2;	
		
}
