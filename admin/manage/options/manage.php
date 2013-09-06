<?php 
/**
 * @fileName: manage.php
 * @dir: admin/manage/options
 */
if(!defined('_iEXEC')) exit;
global $db, $widget;

$go 	= filter_txt($_GET['go']);
$act	= filter_txt($_GET['act']);
$id		= filter_int($_GET['id']);
$name	= filter_txt($_GET['name']);

ob_start();

switch($go){
default:

?>
<div class="padding">
<?php
if(isset($_POST['submit'])){
	$sitename 				= filter_txt($_POST['sitename']);
	$sitedescription 		= filter_txt($_POST['sitedescription']);
	$sitekeywords 			= filter_txt($_POST['sitekeywords']);
	$site_copyright			= filter_txt($_POST['site_copyright']);
	$admin_email 			= filter_txt($_POST['mail_adress']);
	$datetime_format 		= filter_txt($_POST['datetime_format']);
	$author 				= filter_txt($_POST['author']);
	$account_registration 	= filter_int($_POST['account_registration']);
	$avatar_type			= filter_txt($_POST['avatar_type']);
	$timezone				= filter_txt($_POST['timezone']);
	$post_comment_filter	= filter_txt($_POST['post_comment_filter']);
	
	$data = compact('sitename','sitedescription','sitekeywords','site_copyright','admin_email','datetime_format','timeout','author','account_registration','timezone','avatar_type','post_comment_filter');
	set_general( $data );
	echo '<div id="success">Data berhasil disimpan</div><br>';
	add_activity('manager_options','mengubah settingan general option', 'setting');
}
?>
  <table width="100%" cellpadding="4">
    <tbody>
    <tr>
      <td width="24%">Site Title</td>
      <td width="1%"><strong>:</strong></td>
      <td width="75%"><input type="text" name="sitename" value="<?php echo get_option('sitename')?>" style="width:400px;"></td>
    </tr>
    <tr>
      <td>Description</td>
      <td><strong>:</strong></td>
      <td>
      <textarea type="text" name="sitedescription" style="width:400px; height:60px;"><?php echo get_option('sitedescription')?></textarea></td>
    </tr>
    <tr>
      <td>Keywords</td>
      <td><strong>:</strong></td>
      <td>
      <textarea type="text" name="sitekeywords" style="width:400px; height:60px;"><?php echo get_option('sitekeywords')?></textarea></td>
    </tr>
    <tr>
      <td>Copyright</td>
      <td><strong>:</strong></td>
      <td></textarea><input type="text" name="site_copyright" value="<?php echo get_option('site_copyright')?>" style="width:400px;"></td>
    </tr>
    <tr>
      <td>E-mail address</td>
      <td><strong>:</strong></td>
      <td><input type="text" name="mail_adress" value="<?php echo get_option('admin_email')?>" style="width:400px;"></td>
    </tr>
    <tr>
      <td>Datetime format</td>
      <td><strong>:</strong></td>
      <td><input type="text" name="datetime_format" value="<?php echo get_option('datetime_format')?>" style="width:150px;"></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td>ex: F j, Y, g:i a => March 10, 2001, 5:16 pm</td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td><?php echo round(($time/60),2)?> minutes, <?php echo round(($minute/60),2)?> hours</td>
    </tr>
    <tr>
      <td>Author Name Post</td>
      <td><strong>:</strong></td>
      <td><input type="text" name="author" value="<?php echo get_option('author')?>"></td>
    </tr>
    <tr>
      <td>Post Approved</td>
      <td><strong>:</strong></td>
      <td>
      <select name="post_comment_filter">
<?php
if(get_option('post_comment_filter')==1){
	echo '	
      <option value="0">Disable</option>
      <option value="1" selected="selected">Enable</option>
	';
}else{	
	echo '	
      <option value="0" selected="selected">Disable</option>
      <option value="1">Enable</option>
	';
}
?>
      </select>
      </td>
    </tr>
    <tr>
      <td>Account Registration</td>
      <td><strong>:</strong></td>
      <td>
      <select name="account_registration">
<?php
if(get_option('account_registration')==1){
	echo '	
      <option value="0">Disable</option>
      <option value="1" selected="selected">Enable</option>
	';
}else{	
	echo '	
      <option value="0" selected="selected">Disable</option>
      <option value="1">Enable</option>
	';
}
?>
      </select>
      </td>
    </tr>
    <tr>
      <td>Avatar Type</td>
      <td><strong>:</strong></td>
      <td>      
      <select name="avatar_type">
<?php
if(get_option('avatar_type')=='gravatar'){
?>	
      <option value="">-- Pilih --</option>
      <option value="gravatar" selected="selected">Gravatar</option>
      <option value="computer">Computer</option>
<?php
}elseif(get_option('avatar_type')=='computer'){
?>	
      <option value="">-- Pilih --</option>
      <option value="gravatar">Gravatar</option>
      <option value="computer" selected="selected">Computer</option>
<?php
}else{	
?>
      <option value="" selected="selected">-- Pilih --</option>
      <option value="gravatar">Gravatar</option>
      <option value="computer">Computer</option>
<?php
}
?>
      </select>
      </td>
    </tr>
    <?php  if ( function_exists('timezone_supported') ) :?>
    <tr>
      <td>Time Zone</td>
      <td><strong>:</strong></td>
      <td><select name="timezone"><?php $tzstring = get_option('timezone'); echo timezone_choice($tzstring);?></select></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td>UTC: <i><?php echo gmdate('Y-m-d G:i:s')?></i> Local: <i><?php echo date('Y-m-d G:i:s')?></i></td>
    </tr>
    <?php else:?>
    <tr>
      <td>Time Zone</td>
      <td><strong>:</strong></td>
      <td><input type="text" name="timezone" value="<?php echo get_option('timezone')?>"></td>
    </tr>
    <?php endif;?>
  </tbody></table><br />
</div>
<?php
$header_title = 'General Manage';
$form = 'action="" method="post" enctype="multipart/form-data" name="form1"';
$header_menu = '<input type="submit" name="submit" class="button on l blue" value="Save & Update"><input type="reset" class="button r" name="Reset" value="Reset">';

break;
case'fix':
if( isset($_POST['submitFix']) && $_POST['logFix'] == 1 ){
	$name = filter_txt( $_POST['option_name'] );
	add_option( $name, 1);
}
?>
<div class="padding">
<input type="hidden" name="option_name" value="<?php echo $name;?>" />
<input type="hidden" name="logFix" value="1" />
<input type="submit" name="submitFix" value="Fix it Now" />
</div>
<?php
$header_title = 'Option Fix Manager';
$form = 'action="" method="post" name="form1"';

break;
case'gravatar':

if(isset($_POST['submit'])){
	$avatar_default = filter_txt($_POST['avatar_default']);
	echo '<div class="padding">';
	set_avatar( compact('avatar_default') );
	echo '</div>';
	add_activity('manager_options','mengubah settingan avatar', 'setting');
}

$avatar_defaults = array(
	'mystery' 			=> 'Mystery Man',
	'blank' 			=> 'Blank',
	'gravatar_default' 	=> 'Gravatar Logo',
	'identicon' 		=> 'Identicon (Generated)',
	'wavatar' 			=> 'Wavatar (Generated)',
	'monsterid' 		=> 'MonsterID (Generated)',
	'retro' 			=> 'Retro (Generated)'
);
$default = get_option('avatar_default');
if ( empty($default) )
$default 		= 'mystery';

foreach ( $avatar_defaults as $default_key => $default_name ) {
	$selected = ($default == $default_key) ? 'checked="checked" ' : '';
	$avatar_list .= "";

	$avatar = get_gravatar( 'unknow@mail.com', $default_key );
	$avatar_img = '';

	$avatar_name= $default_name;
}

?>
<table id=table cellpadding="0" cellspacing="0" width="100%">
<tr class="head">
    <td width="3%" style="text-align:left;border-top:0"><strong>Pilih</strong></td>
    <td width="7%" style="text-align:center;border-top:0"><strong>Image</strong></td>
    <td width="90%" style="text-align:left;border-top:0"><strong>Title</strong></td>
  </tr>
<?php
$warna 		= '';
foreach ( $avatar_defaults as $default_key => $default_name ) {
	
$warna 		= empty ($warna) ? ' bgcolor="#f1f6fe"' : '';
$selected 	= ($default == $default_key) ? 'checked="checked" ' : '';
$avatar 	= get_gravatar( 'unknow@mail.com', $default_key );
?>
  <tr <?php echo $warna?> class="isi">
    <td><center><?php echo "<input type='radio' name='avatar_default' id='avatar_{$default_key}' value='" . esc_sql($default_key)  . "' {$selected}/>"?></center></td>
    <td align="center" style="padding:2px;"><img src="<?php echo $avatar?>" alt="" width="30" style="-moz-border-radius: 3px;-khtml-border-radius: 3px;-webkit-border-radius: 3px;border-radius: 3px;behavior: url(border-radius.htc);"/></td>
    <td style="padding:2px;"><?php echo $default_name?></td>
  </tr>
<?php
}
?>
</table>
<?php
$header_title = 'Gravatar Manage';
$form = 'action="" method="post" enctype="multipart/form-data" name="form1"';
$header_menu = '<input type="submit" name="submit" class="button on l blue" value="Save & Update"><input type="reset" class="button r" name="Reset" value="Reset">';

break;
case'help-guide':
?>
<iframe class="shadow-inside" src='http://help.cmsid.org/' style="width:100%; height:500px;" frameborder="0" scrolling="auto"></iframe>
<?php
$header_title = 'Help &amp; Guide';
$header_menu = '<div class="header_menu_top2">';
$header_menu.= '<a href="?admin=single&sys=options&go=help-guide" class="button l">Home</a>';
$header_menu.= '<a href="?admin" class="button r"><span class="icon_head back">&laquo; Back</span></a>';
$header_menu.= '</div>';
break;
}

$content = ob_get_contents();
ob_end_clean();

$header_menu = '<div class="header_menu_top">'.$header_menu.'</div>';
add_templates_manage( $content, $header_title, $header_menu, null, $form );