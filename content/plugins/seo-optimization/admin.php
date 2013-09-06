<?php 
/**
 * @fileName: admin.php
 * @dir: plugin/seo-optimization
 */
if(!defined('_iEXEC')) exit;

ob_start();
?>
<div class="padding">
<?php
$plugin_name = filter_txt( $_GET['plugin_name'] );
$file = filter_txt( $_GET['file'] );

$rewrite = get_option('rewrite');
$rewrite_html = get_option('rewrite_html');

if( isset($_POST['changeRewrite']) ){
	$typelink = (string)$_POST['typelink'];
	if( checked_option( 'rewrite' ) ) set_option( 'rewrite', $typelink );
	else add_option( 'rewrite', $typelink );
	
	
	$typelink_html = (int)$_POST['typelink_html'];
	if( checked_option( 'rewrite_html' ) ) set_option( 'rewrite_html', $typelink_html );
	else add_option( 'rewrite_html', $typelink_html );
	
	add_activity('manager_plugins',"Merubah seo dari $rewrite ke $typelink", 'plugin');	
	echo "<div id=\"success\">Berhasil merubah seo $rewrite dari ke $typelink</div>";
    echo "<meta http-equiv=\"refresh\" content=\"0;url=?admin&sys=plugins&go=setting&plugin_name=$plugin_name&file=$file\" />";
}

$o_type = '';
if( $rewrite_html > 0 )
	$o_type = '.html';

$typelinks = array(
    array('id' => 'advance','name' => 'Advance','url' => "$base_url/?com=string&view=string&id=string"),
    array('id' => 'slash','name' => 'Slash','url' => "$base_url/string/string/0/string$o_type"),
    array('id' => 'slash-clear','name' => 'Slash Clear','url' => "$base_url/string/string$o_type"), 
    array('id' => 'clear','name' => 'Clear','url' => "$base_url/string-string$o_type")
	);
	
?>
  <table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
      <td  align="left" valign="top" colspan="3"></td>
    </tr>
    <tr>
      <td align="left" valign="top">HTML Extension</td>
      <td align="left" valign="top"><strong>:</strong></td>
      <td align="left" valign="top">
      <select name="typelink_html">
      <?php 
	  $select_true = $select_false = '';
	  if( $rewrite_html > 0 ) $select_true = ' selected="selected"';
	  else $select_false = ' selected="selected"';
	  ?>
      <option value="1"<?php echo $select_true;?>>True</option>
      <option value="0"<?php echo $select_false;?>>False</option>
      </select>
      </td>
    </tr>
    <tr>
      <td align="left" valign="top">Type Url*</td>
      <td align="left" valign="top"><strong>:</strong></td>
      <td align="left" valign="top">
    <select name="typelink">
    <?php foreach( $typelinks as $typelink ): $select = ''; if( $typelink[id] == $rewrite ) $select = ' selected="selected"';?>
    <option value="<?php echo $typelink[id]?>"<?php echo $select;?>><?php echo $typelink[name]?> &raquo; <?php echo $typelink[url]?></option>
    <?php endforeach;?>
  </select></td>
    </tr>
  </table>
</div>
<?php

$content = ob_get_contents();
ob_end_clean();

$header_menu = '<div class="header_menu_top">';
$header_menu.= '<input type="submit" name="changeRewrite" class="button blue" value="Change">';
$header_menu.= '</div>';
$header_menu.= '<a href="?admin&sys=plugins" class="button"><span class="icon_head back">&laquo; Back</span></a>';

$form = 'action="" method="post"';
add_templates_manage( $content, 'SEO Optimization Manager', $header_menu, null, $form  );
