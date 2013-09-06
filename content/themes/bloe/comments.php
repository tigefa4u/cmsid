<?php
/**
 * @file comments.php
 *
 */
//dilarang mengakses
if(!defined('_iEXEC')) exit;
global $db, $the_title;

$id = get_posted_id('item', get_query_var('id') );
$id_reply = get_query_var('go');
?>
<link href="<?php get_template_directory_uri(true); ?>/css/style-comments.css" rel="stylesheet" type="text/css">
<?php
if( get_option('post_comment') == 1 ):
/*
 *show comment where id post and status = 1 order by id desc limit where data limit table id_comment_set
 */

$color			= '';
$comment_no		= 0;
$qry_comment	= $db->select("post_comment",array('post_id'=>$id,'approved'=>1,'comment_parent'=>0)); 
?>
<br style="clear:both" />
<div class="comments-box">
<h2 class=border><?php echo count_comment($id)?> Respon dari "<?php echo $the_title;?>"</h2>
<ol class="commentlist">
<?php
while ($data	= $db->fetch_obj($qry_comment)) {
	
$no_comment 	= filter_int( $data->comment_id );
$no_respon  	= filter_int( $comment_no++ );
$reply_link = do_links('post',array('view'=>'item','id'=>$id,'title'=>$the_title,'go'=>$no_comment));
?>
<li class="comment-wrap" id="respon-<?php echo $no_respon;?>">

<div class="comment-img"><img src="<?php echo get_gravatar($data->email);?>" /></div>
<div class="comment-text">
<b><?php echo $data->author;?></b> <?php echo $data->comment;?>
<div class="comment-time"><?php echo time_stamp($data->time); ?>
</div>
</div>
<div style="clear:both"></div> 
<?php if($id_reply != $no_comment):?>
<div class='reply'><a rel="nofollow" class="comment-reply-link" href='<?php echo $reply_link?>#respon-comment'>Balas </a></div>
<?php endif;?>
	<ul class="children">
		<?php
        $comment_no_reply	= 0;
        $q2					= $db->select("post_comment",array('post_id'=>$id,'approved'=>1,'comment_parent'=>$no_comment)); 
        while ($data2		= $db->fetch_array($q2)) {
        $no_respon_reply	= filter_int( $comment_no_reply++ );
        ?>
		<li class="comment odd alt depth-2 parent" id="respon-<?php echo $no_respon;?>-<?php echo $no_respon_reply;?>">
            <div class="comment-img comment-reply-img"><img src="<?php echo get_gravatar($data2['email']);?>" /></div>
            <div class="comment-text comment-reply-text">
            <b><?php echo $data2['author'];?></b> <?php echo $data2['comment'];?>
            <div class="comment-time comment-reply-time"><?php echo time_stamp($data2['time']);?></div> 
            </div>
			<?php if($id_reply != $no_comment):?>
            <div class='reply'><a rel="nofollow" class="comment-reply-link" href='<?php echo $reply_link?>#respon-comment'>Balas </a></div>
            <?php endif;?>
        </li>
        <?php
        }
        ?>
    </ul>

</li>
<?php
}
?>
</ol>
<h1 class=border>Tinggalkan Komentar <?php if(isset($id_reply)) echo 'Balasan'?></h1>
<?php

if( $id_reply ): 

$id_link = do_links('post',array('view'=>'item','id'=>$id,'title'=>$the_title));
?>
<div align="right"><a href="<?php echo $id_link?>#respon-comment" class="post_reply">New Comment</a></div>
<?php
endif;

if(isset($_POST['submit_comment'])){

if(!get_comment_login())
{
	$author		= filter_txt($_POST['author']);
	$email 		= filter_txt($_POST['email']);
}
else
{
	$field 		= get_current_comment();
		
	$user 		= $field->user_login;
	$email 		= $field->user_email;
	$author		= $field->user_author;
}

	$comment 	= filter_txt($_POST['comment']);
	$comment 	= nl2br($comment);
	
	$approved	= get_option('post_comment_filter');
	
	$reply	    = $id_reply;
	$post_id    = filter_int($id);
	
	$security_code_check = filter_txt($_POST['security_code_check']);
	$security_code = filter_txt($_SESSION['security_code']);
	
	$data 		= compact('user','author','email','comment','date','approved','security_code_check','security_code','reply','post_id');
	comment_post($data);

}
?>
<div class="border" id="respon-comment">
<form method="post" action="#commentform" id="commentform">
  <table width="100%">
  <?php
  if(!get_comment_login())
  {
  ?>
    <tr>
      <td width="15%" valign="top">Nama*</td>
      <td width="1%" valign="top"><strong>:</strong></td>
      <td width="74%" valign="top"><input class="author" type="text" name="author"></td>
    </tr>
    <tr>
      <td valign="top">Mail*</td>
      <td valign="top"><strong>:</strong></td>
      <td valign="top"><input class="email" type="text" name="email"></td>
    </tr>
    <?php
  	$gfx_code = true;
  }
  else
  {
	$field 	= get_current_comment();
	
	if( $user->user_level == 'admin' )
  		$gfx_code = false;
	?>   
    <tr>
      <td colspan="3" valign="top">Logged in as <a href="<?php echo do_links('login',array('go'=>'profile'))?>"><?php echo $field->user_login?></a>. <a href="?login&go=logout"  onclick="return confirm('Are You sure logout?')">Log out?</a></td>
      </tr>
    <tr>
      <td colspan="3" valign="top">&nbsp;</td>
      </tr>
   <?php
  }
  ?>
    <tr>
      <td valign="top">Komentar*</td>
      <td valign="top"><strong>:</strong></td>
      <td valign="top"><textarea  cols="50" rows="5" name="comment" style="height:100px; width:90%"></textarea></td>
    </tr>
   <?php if( $gfx_code ){?>
    <tr>
      <td valign="top">Kode Keamanan*</td>
      <td valign="top"><strong>:</strong></td>
      <td valign="top"><img src="<?php echo site_url('?request&load=libs/captcha/random.php')?>"></td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td valign="top"><input  name="security_code_check" class="security_code_check" type="text" size=10/></td>
    </tr>
    <?php }?>
    <tr>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td valign="top"><input class="submit" type="submit" name="submit_comment" value="Kirim"></td>
    </tr>
  </table>
</form>
</div>
</div>
<?php
endif;
?>