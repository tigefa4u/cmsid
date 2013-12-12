<?php 
if(!defined('_iEXEC')) exit;

global $db;

/* 
 * fungsi page() bertujuan untuk mengextrax content halaman misal title dengan id 1(satu)
 * title disini mengacu pada field nama table di id 1 (satu) pada database
 * maka akan didapatkan judul yang akan ditampilkan pada halaman web
 * fungsi ini bisa anda cari di file function.php dengan nama function page(){}
 * untuk menampilkan anda bisa menggunakan fungsi _e() atau echo
 */

$bt = portal_theme_option('limit_post');

if( portal_theme_option('front') != 'slide' ){
$pg = (int) get_query_var('pg');
if(empty($pg)){
	$ps = 0;
	$pg = 1;
}
else{
	$ps = ($pg-1) * $bt;
}

$ps = filter_int( $ps );
}else{
	$ps = 4;
}


$sqlFrontArtikel = $db->select( 'post',array('type'=>'post','status'=>1),"ORDER BY date_post DESC LIMIT $ps,$bt" );
$total_post = $db->num_query("SELECT * FROM `$db->post` WHERE type = 'post' AND approved=1 AND status = 1");
if( $db->num( $sqlFrontArtikel ) > 0 ):
?>
<div id="content">
	<?php 
while ($wFrontArtikel = $db->fetch_obj($sqlFrontArtikel)) {
$dFrontArtikel	= array('view'=>'item','id'=>$wFrontArtikel->id,'title'=>$wFrontArtikel->title);
?>
  	<div class="archive">
    	<?php $thumb = get_template_directory_uri().'/images/no-preview.png'; if( !empty($wFrontArtikel->thumb) ): $thumb = content_url('/uploads/post/'.$wFrontArtikel->thumb); endif;?>
    	<div class="thumb left">
    		<a href="<?php echo do_links('post',$dFrontArtikel);?>" rel="bookmark"><img src="<?php echo site_url().'/?request&load=libs/timthumb.php&src=' . $thumb; ?>&amp;h=100&amp;w=100&amp;zc=1" alt="" style="width:100px; height:100px;" /></a>
    	</div> <!--end: thumb-->
      	<h2><a href="<?php echo do_links('post',$dFrontArtikel);?>" rel="bookmark"><?php echo $wFrontArtikel->title; ?></a></h2>
      		<?php echo limittxt( sanitize( strip_tags($wFrontArtikel->content) ),250); ?>
    	<div class="clear"></div>
   	</div>
	<?php }?>
</div>
<div class="pagenavi">    
<?php
$paging = new Pagination();
$paging->set('urlscheme', site_url() . '/?pg=%page%');
$paging->set('perpage',$bt);
$paging->set('page',$pg);
$paging->set('total',$total_post);
$paging->set('nexttext','Next');
$paging->set('prevtext','Previous');
$paging->set('focusedclass','selected');
$paging->display();
?>        
<div class="clear"></div>
</div> <!--end: pagenavi-->
<?php else:?>
<h4 class="bg">Page Not Found / Empty</h4>
<?php endif;?>