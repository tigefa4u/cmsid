<?php 
/**
 * @fileName: admin.php
 * @dir: themes/iNet/options/
 */
if(!defined('_iEXEC')) exit;

global $theme_name;

ob_start();

switch( $_GET['to'] ){
default:

if( isset( $_POST['submit'] ) ) {
	
	$data[logo] 		= filter_txt($_POST['logo']);
	$data[logo_w] 		= filter_int($_POST['logo_w']);
	$data[limit_post] 	= filter_int($_POST['limit_post']);
	$data[artist] 		= filter_int($_POST['artist']);
	$data[ads160x600] 	= filter_txt($_POST['ads160x600']);
	
	if ( ! is_array( $data ) )
		return false;
		
	$data = json_encode( $data );
	if( !checked_option( 'inet_options' ) ) add_option( 'inet_options', $data );
	else set_option( 'inet_options', $data );
	
	add_activity('theme',"Mengubah pengaturan inet_options", 'appearance');
	redirect('?admin&sys=appearance&go=custom-theme');
}
?>
<div class="padding">
<table width="100%" border="0" cellpadding="4" cellspacing="2">
  <tr>
    <td width="17%" align="left" valign="top">Logo View</td>
    <td width="1%" align="left" valign="top"><strong>:</strong></td>
    <td width="82%" align="left" valign="top"><img src="<?php echo inet_theme_option('logo');?>" width="100"></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">Logo URL</td>
    <td align="left" valign="top"><strong>:</strong></td>
    <td align="left" valign="top"><textarea name="logo" style="width:95%;"><?php echo inet_theme_option('logo');?></textarea></td>
  </tr>
  <tr>
    <td align="left" valign="top">Logo Width</td>
    <td align="left" valign="top"><strong>:</strong></td>
    <td align="left" valign="top"><input type="text" name="logo_w" value="<?php echo inet_theme_option('logo_w');?>" style="width:60px;" /> px</td>
  </tr>
  <tr>
    <td align="left" valign="top">Limit Post View</td>
    <td align="left" valign="top"><strong>:</strong></td>
    <td align="left" valign="top"><input type="text" name="limit_post" value="<?php echo inet_theme_option('limit_post');?>" style="width:60px;" /></td>
  </tr>
  <tr>
    <td align="left" valign="top">Artist</td>
    <td align="left" valign="top"><strong>:</strong></td>
    <td align="left" valign="top">
      <select name="artist">
      <?php $artist_true = ''; $artist_false = 'selected="selected"'; if( inet_theme_option('artist') > 0 ): $artist_true = 'selected="selected"'; $artist_false = ''; endif;?>
        <option value="1"<?php echo $artist_true;?>>Show</option>
        <option value="0"<?php echo $artist_false;?>>Hide</option>
      </select>
      </td>
  </tr>
  <tr>
    <td align="left" valign="top">Ads URL</td>
    <td align="left" valign="top"><strong>:</strong></td>
    <td align="left" valign="top"><textarea name="ads160x600" style="width:95%;"><?php echo inet_theme_option('ads160x600');?></textarea></td>
  </tr>
  <tr>
    <td align="left" valign="top">Ads View</td>
    <td align="left" valign="top"><strong>:</strong></td>
    <td align="left" valign="top"><img src="<?php echo inet_theme_option('ads160x600');?>"></td>
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