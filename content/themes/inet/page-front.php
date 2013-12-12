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

$bt = inet_theme_option('limit_post');
$pg = (int) get_query_var('pg');
if(empty($pg)){
	$ps = 0;
	$pg = 1;
}
else{
	$ps = ($pg-1) * $bt;
}

$page_found = false;
$ps = filter_int( $ps );

$add_query = "WHERE type='post' AND approved='1' AND status='1'";
$sqlFrontArtikel = $db->query( "SELECT * FROM `$db->post` $add_query ORDER BY `id` DESC LIMIT $ps,$bt" );
$total_post = $db->num_query( "SELECT * FROM $db->post $add_query" );

while ($wFrontArtikel = $db->fetch_obj($sqlFrontArtikel)) {
$page_found = true;
$dFrontArtikel	= array('view'=>'item','id'=>$wFrontArtikel->id,'title'=>$wFrontArtikel->title);
?>

<h4 class="bg"><?php echo $wFrontArtikel->title?></h4>
<div class="news">
<?php if( file_exists('content/uploads/post/'.$wFrontArtikel->thumb) && !empty($wFrontArtikel->thumb) ):?>
<div class="thumb">
<a href="#" rel="bookmark" class="img-url">
<img src="<?php echo site_url('/?request&load=libs/timthumb.php'); ?>&src=<?php echo content_url('/uploads/post/'.$wFrontArtikel->thumb);?>&h=100&w=100&zc=1" alt="">
</a>
</div>
<?php endif;?>
<div class="bg">
<span class="align-justify"> <?php echo limittxt( sanitize(strip_tags($wFrontArtikel->content)), 300 )?></span>
</div>
<div style="clear:both"></div>
</div>		
			
<p class="post-footer">					
<a href="<?php echo do_links('post',$dFrontArtikel);?>" class="readmore">Read more</a>
<span class="comments">Hits (<?php echo $wFrontArtikel->hits?>)</span>
<span class="date"><?php echo datetimes( $wFrontArtikel->date_post, false )?></span>	
</p>
<?php
}
if( !$page_found ):
?>
<h4 class="bg">Page Not Found</h4>
<?php else:?>
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
<?php endif;?>