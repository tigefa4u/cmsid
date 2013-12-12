<?php 
/**
 * @fileName: admin.php
 * @dir: portal/options/
 */
if(!defined('_iEXEC')) exit;

global $theme_name;

ob_start();

switch( $_GET['to'] ){
default:

if( isset( $_POST['submit'] ) ) {
	
	$data[logo] 		= filter_txt($_POST['logo']);
	$data[logo_w] 		= filter_int($_POST['logo_w']);
	$data[logo_h] 		= filter_int($_POST['logo_h']);
	$data[limit_post] 	= filter_int($_POST['limit_post']);
	$data[tabber] 		= filter_int($_POST['tabber']);
	$data[front] 		= filter_txt($_POST['front']);
	$data[topnews] 		= filter_txt($_POST['topnews']);
	$data[ads468x60] 	= filter_txt($_POST['ads468x60']);
	$data[ads160x600] 	= filter_txt($_POST['ads160x600']);
	$data[ads300x250] 	= filter_txt($_POST['ads300x250']);
	
	if ( ! is_array( $data ) )
		return false;
		
	$data = json_encode( $data );
	if( !checked_option( 'portal_options' ) ) add_option( 'portal_options', $data );
	else set_option( 'portal_options', $data );
	
	add_activity('theme',"Mengubah pengaturan portal_options", 'appearance');
	redirect('?admin&sys=appearance&go=custom-theme');
}
?>
<div class="padding">
<table width="100%" border="0" cellpadding="4" cellspacing="2">
  <tr>
    <td width="17%" align="left" valign="top">Logo View</td>
    <td width="1%" align="left" valign="top"><strong>:</strong></td>
    <td width="82%" align="left" valign="top"><img src="<?php echo portal_theme_option('logo');?>" width="200"></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">Logo URL</td>
    <td align="left" valign="top"><strong>:</strong></td>
    <td align="left" valign="top"><textarea name="logo" style="width:95%;"><?php echo portal_theme_option('logo');?></textarea></td>
  </tr>
  <tr>
    <td align="left" valign="top">Logo Width</td>
    <td align="left" valign="top"><strong>:</strong></td>
    <td align="left" valign="top"><input type="text" name="logo_w" value="<?php echo portal_theme_option('logo_w');?>" style="width:60px;" /> px</td>
  </tr>
  <tr>
    <td align="left" valign="top">Logo Height</td>
    <td align="left" valign="top"><strong>:</strong></td>
    <td align="left" valign="top"><input type="text" name="logo_h" value="<?php echo portal_theme_option('logo_h');?>" style="width:60px;" /> px</td>
  </tr>
  <tr>
    <td align="left" valign="top">Limit Post View</td>
    <td align="left" valign="top"><strong>:</strong></td>
    <td align="left" valign="top"><input type="text" name="limit_post" value="<?php echo portal_theme_option('limit_post');?>" style="width:60px;" /></td>
  </tr>
  <tr>
    <td align="left" valign="top">Tabs Widget</td>
    <td align="left" valign="top"><strong>:</strong></td>
    <td align="left" valign="top">
      <select name="tabber">
      <?php $tabber_true = ''; $tabber_false = 'selected="selected"'; if( portal_theme_option('tabber') > 0 ): $tabber_true = 'selected="selected"'; $tabber_false = ''; endif;?>
        <option value="1"<?php echo $tabber_true;?>>Show</option>
        <option value="0"<?php echo $tabber_false;?>>Hide</option>
      </select>
      </td>
  </tr>
  <tr>
    <td align="left" valign="top">Top News Widget</td>
    <td align="left" valign="top"><strong>:</strong></td>
    <td align="left" valign="top">
      <select name="topnews">
      <?php $topnews_true = ''; $topnews_false = 'selected="selected"'; if( portal_theme_option('topnews') > 0 ): $topnews_true = 'selected="selected"'; $topnews_false = ''; endif;?>
        <option value="1"<?php echo $topnews_true;?>>Show</option>
        <option value="0"<?php echo $topnews_false;?>>Hide</option>
      </select>
      </td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top"></td>
  </tr>
  <tr>
    <td align="left" valign="top">Front Style</td>
    <td align="left" valign="top"><strong>:</strong></td>
    <td align="left" valign="top">
    <?php 
	$front = portal_theme_option('front');
	$front_select_default = '';
	$front_select_slide = '';
	$front_select_topic = '';
	if( $front == 'slide' )
		$front_select_slide = ' checked="checked"';
	elseif( $front == 'topic' )
		$front_select_topic = ' checked="checked"';
	else
		$front_select_default = ' checked="checked"';
	?>
      <center style="float:left; width:100px;">
      <img src="<?php echo get_template_directory_uri();?>/images/front-news-default.png" width="58" height="58" /><br />
      <input type="radio" name="front" value="default"<?php echo $front_select_default?>/><br />Default
      </center>
      <center style="float:left; width:100px;">
      <img src="<?php echo get_template_directory_uri();?>/images/front-news-slide.png" width="58" height="58" /><br />
      <input type="radio" name="front" value="slide"<?php echo $front_select_slide?>/><br />Slide
      </center>
      <center style="float:left; width:100px;">
      <img src="<?php echo get_template_directory_uri();?>/images/front-news-slide-topic.png" width="58" height="58" /><br />
      <input type="radio" name="front" value="topic"<?php echo $front_select_topic?>/><br />Slide Topic
      </center>
    </td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top"></td>
  </tr>
  <tr>
    <td align="left" valign="top">Ads URL 468x60</td>
    <td align="left" valign="top"><strong>:</strong></td>
    <td align="left" valign="top"><textarea name="ads468x60" style="width:95%;"><?php echo portal_theme_option('ads468x60');?></textarea></td>
  </tr>
  <tr>
    <td align="left" valign="top">Ads URL 160x600</td>
    <td align="left" valign="top"><strong>:</strong></td>
    <td align="left" valign="top"><textarea name="ads160x600" style="width:95%;"><?php echo portal_theme_option('ads160x600');?></textarea></td>
  </tr>
  <tr>
    <td align="left" valign="top">Ads URL 300x250</td>
    <td align="left" valign="top"><strong>:</strong></td>
    <td align="left" valign="top"><textarea name="ads300x250" style="width:95%;"><?php echo portal_theme_option('ads300x250');?></textarea></td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top">
    <table width="95%" border="0" cellpadding="2">
      <tr>
        <td>ads160x600</td>
        <td>ads300x250</td>
        <td>ads468x60</td>
        </tr>
      <tr>
        <td valign="top" style="vertical-align: top; padding:1px;"><img src="<?php echo portal_theme_option('ads160x600');?>"></td>
        <td valign="top" style="vertical-align: top; padding:1px;"><img src="<?php echo portal_theme_option('ads300x250');?>"></td>
        <td valign="top" style="vertical-align: top; padding:1px;"><img src="<?php echo portal_theme_option('ads468x60');?>"></td>
        </tr>
      </table>
      </td>
    </tr>
</table>
</div>
<?php
break;
}

$content = ob_get_contents();
ob_end_clean();

$header_menu = '<div class="header_menu_top">';
$header_menu.= '<input type="submit" name="submit" class="button on blue" value="Save &amp; Update">';
$header_menu.= '</div>';
$header_menu.= '<div class="header_menu_top2">';
$header_menu.= '<a href="?admin&sys=appearance&go=custom-theme" class="button l">Home</a>';
$header_menu.= '<a href="?admin&sys=appearance" class="button r"><span class="icon_head back">&laquo; Back</span></a>';
$header_menu.= '</div>';

add_templates_manage( $content, $theme_name[1] . ' Theme Options', $header_menu, null,'action="" method="post"');