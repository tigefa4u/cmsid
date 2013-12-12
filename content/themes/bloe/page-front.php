<?php 
if(!defined('_iEXEC')) exit;

global $db;

$page_view = get_page_view(2);
echo '<div class="border">'.$page_view->content.'</div>';

$bt = 10;
$pg = (int) get_query_var('pg');
if(empty($pg)){
	$ps = 0;
	$pg = 1;
}
else{
	$ps = ($pg-1) * $bt;
}

$ps = filter_int( $ps );

$add_query = "WHERE type='post' AND approved='1' AND status='1'";
$sqlFrontArtikel = $db->query( "SELECT * FROM `$db->post` $add_query ORDER BY `id` DESC LIMIT $ps,$bt" );
$total_post = $db->num_query( "SELECT * FROM $db->post $add_query" );

while ($wFrontArtikel = $db->fetch_obj($sqlFrontArtikel)) {

$post_img = '';
if( file_exists('content/uploads/post/' . $wFrontArtikel->thumb ) && !empty($wFrontArtikel->thumb) ):
$post_img = '<img src="'.site_url('/?request&load=libs/timthumb.php').'&src='.content_url('/uploads/post/'.$wFrontArtikel->thumb).'&h=100&w=100&zc=1" class="post-img">';
endif;

?>
<h1 class="bg"><?php echo $wFrontArtikel->title;?></h1>
<div class="post" id="post-1">
           <?php echo $post_img;?>
           <p style="text-align:justify;"><?php echo limittxt(strip_tags($wFrontArtikel->content),360);?></p>
        </div>
<p class="post-footer">					
<a href="<?php echo do_links('post',array('view'=>'item','id'=>$wFrontArtikel->id,'title'=>$wFrontArtikel->title));?>" rel="bookmark" title="<?php echo $wFrontArtikel->title;?>" class="readmore">Read more</a>
<span class="comments">Hits (<?php echo $wFrontArtikel->hits;?>)</span>
<span class="date"><?php echo datetimes($wFrontArtikel->date_post,false);?></span>	
</p>
<?php
}
?>

<div style="clear: both;"></div>
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

