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
	$robots					= filter_txt($_POST['robots']);
	$toogle_menuaction		= filter_txt($_POST['toogle_menuaction']);
	$toogle_menutop			= filter_txt($_POST['toogle_menutop']);
	$welcome				= filter_txt($_POST['welcome']);
	$help_guide				= filter_txt($_POST['help_guide']);
	
	$data = compact('sitename','sitedescription','sitekeywords','site_copyright','admin_email','datetime_format','timeout','author','account_registration','timezone','avatar_type','robots','toogle_menuaction','toogle_menutop','welcome','help_guide');
	set_general( $data );
	echo '<div id="success">Data berhasil disimpan</div><br>';
	add_activity('manager_options','mengubah settingan general option', 'setting');
	echo '<meta http-equiv="refresh" content="0;url=?admin&sys=options" />';
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
      <td>Robots</td>
      <td><strong>:</strong></td>
      <td><input type="text" name="robots" value="<?php echo get_option('robots')?>"> ex: index,follow</td>
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
      <td>Toogle Menu Actions</td>
      <td><strong>:</strong></td>
      <td>
      <select name="toogle_menuaction">
<?php
if(get_option('toogle_menuaction')==1){
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
      <td>Toogle Menu Top</td>
      <td><strong>:</strong></td>
      <td>
      <select name="toogle_menutop">
<?php
if(get_option('toogle_menutop')==1){
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
      <td>Welcome tools</td>
      <td><strong>:</strong></td>
      <td>
      <select name="welcome">
<?php
if(get_option('welcome')==1){
	echo '	
      <option value="0">Enable</option>
      <option value="1" selected="selected">Disable</option>
	';
}else{	
	echo '	
      <option value="0" selected="selected">Enable</option>
      <option value="1">Disable</option>
	';
}
?>
      </select>
      </td>
    </tr>
    <tr>
      <td>Help n Guide</td>
      <td><strong>:</strong></td>
      <td>
      <select name="help_guide">
<?php
if(get_option('help_guide')==1){
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
if(get_option('help_guide') < 1){	
	$oops_title = "halaman tidak ditemukan!";
	$oops_msg = "Ups maaf halaman \"<strong>help n guide</strong>\" telah dimatikan, silahkan ke menu option > help n guide untuk mengaktifkan kembali";
	the_oops_message( $oops_title, $oops_msg, 'simple', true, 'notfound' );
}else{
global $api_url;
$q = esc_sql( filter_txt($_GET[q]));

$add_q = '';
if( $q )
$add_q = '&q='.$q;

$url = $api_url.'/?v=2&content=help&themes=yes'.$add_q;
?>
<iframe 
	name="helpnguide" 
	class="shadow-inside" 
    src='<?php echo $url;?>' 
    style="width:100%; 
    height:500px;" 
    frameborder="0" 
    scrolling="auto">
</iframe>
<?php
$header_title = 'Help &amp; Guide';
$form = 'action="" method="get"';
$header_menu = '<div class="header_menu_top">';
$header_menu.= '<input type="hidden" name="admin" value="single">';
$header_menu.= '<input type="hidden" name="sys" value="options">';
$header_menu.= '<input type="hidden" name="go" value="help-guide">';
$header_menu.= '<input type="text" name="q" placeholder="Cari item help">';
$header_menu.= '</div>';
$header_menu.= '<div class="header_menu_top2">';
$header_menu.= '<a href="?admin" class="button l"><span class="icon_head home">Home</span></a>';
$header_menu.= '<a href="?admin=single&sys=options&go=help-guide" class="button r" title="Back to Dashboard"><span class="icon_head back">&laquo; Back</span></a>';
$header_menu.= '</div>';
}
break;
case'background':
	
$style = get_option( 'background_admin' );
$style = json_decode( $style );

if( isset($_POST['submitCustom']) ){	
	$default 		= filter_int( $_POST['default'] );
	$color 			= filter_txt( $_POST['color'] );
	$repeat 		= filter_txt( $_POST['repeat'] );
	$image 			= filter_txt( $_POST['image'] );
	$pos1 			= filter_txt( $_POST['pos1'] );
	$pos2 			= filter_txt( $_POST['pos2'] );
	$pos3 			= filter_txt( $_POST['pos3'] );
	$attachment 	= filter_txt( $_POST['attachment'] );
	
	$body = array();
	if( $default ){
		$body[background][color] = '#f9f9f9';
		$body['default'] = 1;
	}else{
		$body[background][color] = $color;
		$body[background][image] = $image;
		$body[background][repeat] = $repeat;
		$body[background][position] = array($pos1,$pos2,$pos3);
		$body[background][attachment] = $attachment;
		$body['default'] = 0;
	}
	$body = json_encode( $body );
	set_option( 'background_admin', $body );
	header("Location:?admin&sys=options&go=background");exit;
	//echo '<meta http-equiv="refresh" content="0;url=?admin&sys=options&go=background" />';
}
?>
<script type="text/javascript">
/*<![CDATA[*/
$(function(){
});
/*]]>*/
</script>
<div class="padding">
  <fieldset>
    <legend>Reset</legend>
    <label><input type="checkbox" name="default" value="1" id="default"> Default</label>
  </fieldset>
  <div id="default-content" style="display:block;">
  <fieldset>
    <legend>Color</legend>
    <input type="text" name="color" id="color" style="width:100px;" value="<?php echo $style->background->color;?>"> 
  </fieldset>
  <fieldset>
    <legend>Repeat</legend>
      <label>
        <input type="radio" name="repeat" value="no-repeat" id="repeat"<?php if( $style->background->repeat == 'no-repeat'){echo ' checked'; }?>>
        No Repeat</label>
        <label>
        <input type="radio" name="repeat" value="repeat" id="repeat"<?php if( $style->background->repeat == 'repeat'){echo ' checked'; }?>>
        Repeat</label>
      <label>
        <input type="radio" name="repeat" value="repeat-x" id="repeat"<?php if( $style->background->repeat == 'repeat-x'){echo ' checked'; }?>>
        Repeat X</label>
      <label>
        <input type="radio" name="repeat" value="repeat-y" id="repeat"<?php if( $style->background->repeat == 'repeat-y'){echo ' checked'; }?>>
        Repeat Y</label>
  </fieldset>
  <fieldset>
    <legend>Image</legend>
    <label for="textfield">URL:</label>
    <input type="text" name="image" id="image" style="width:60%;" value="<?php echo $style->background->image;?>"> *( ex: libs/img/file-name.png
  </fieldset>
  <fieldset>
    <legend>Position 1</legend>
      <label>
        <input type="radio" name="pos1" value="none" id="pos1"<?php if( !$style->background->position[0] || $style->background->position[0] == 'none'){echo ' checked'; }?>>
        None</label>
        <label>
      <label>
        <input type="radio" name="pos1" value="left" id="pos1"<?php if( $style->background->position[0] == 'left'){echo ' checked'; }?>>
        Left</label>
        <label>
      <label>
        <input type="radio" name="pos1" value="right" id="pos1"<?php if( $style->background->position[0] == 'right'){echo ' checked'; }?>>
        Right</label>
        <label>
      <label>
        <input type="radio" name="pos1" value="top" id="pos1"<?php if( $style->background->position[0] == 'top'){echo ' checked'; }?>>
        Top</label>
        <label>
      <label>
        <input type="radio" name="pos1" value="bottom" id="pos1"<?php if( $style->background->position[0] == 'bottom'){echo ' checked'; }?>>
        Bottom</label>
        <label>
      <label>
        <input type="radio" name="pos1" value="center" id="pos1"<?php if( $style->background->position[0] == 'center'){echo ' checked'; }?>>
        Center</label>
        <label>
  </fieldset>
  <fieldset>
    <legend>Position 2</legend>
      <label>
        <input type="radio" name="pos2" value="none" id="pos2"<?php if( !$style->background->position[1] || $style->background->position[1] == 'none'){echo ' checked'; }?>>
        None</label>
        <label>
      <label>
        <input type="radio" name="pos2" value="left" id="pos2"<?php if( $style->background->position[1] == 'left'){echo ' checked'; }?>>
        Left</label>
        <label>
      <label>
        <input type="radio" name="pos2" value="right" id="pos2"<?php if( $style->background->position[1] == 'right'){echo ' checked'; }?>>
        Right</label>
        <label>
      <label>
        <input type="radio" name="pos2" value="top" id="pos2"<?php if( $style->background->position[1] == 'top'){echo ' checked'; }?>>
        Top</label>
        <label>
      <label>
        <input type="radio" name="pos2" value="bottom" id="pos2"<?php if( $style->background->position[1] == 'bottom'){echo ' checked'; }?>>
        Bottom</label>
        <label>
      <label>
        <input type="radio" name="pos2" value="center" id="pos2"<?php if( $style->background->position[1] == 'center'){echo ' checked'; }?>>
        Center</label>
        <label>
  </fieldset>
  <fieldset>
    <legend>Position 3</legend>
      <label>
        <input type="radio" name="pos3" value="none" id="pos3"<?php if( !$style->background->position[2] || $style->background->position[2] == 'none'){echo ' checked'; }?>>
        None</label>
        <label>
      <label>
        <input type="radio" name="pos3" value="left" id="pos3"<?php if( $style->background->position[2] == 'left'){echo ' checked'; }?>>
        Left</label>
        <label>
      <label>
        <input type="radio" name="pos3" value="right" id="pos3"<?php if( $style->background->position[2] == 'right'){echo ' checked'; }?>>
        Right</label>
        <label>
      <label>
        <input type="radio" name="pos3" value="top" id="pos3"<?php if( $style->background->position[2] == 'top'){echo ' checked'; }?>>
        Top</label>
        <label>
      <label>
        <input type="radio" name="pos3" value="bottom" id="pos3"<?php if( $style->background->position[2] == 'bottom'){echo ' checked'; }?>>
        Bottom</label>
        <label>
      <label>
        <input type="radio" name="pos3" value="center" id="pos3"<?php if( $style->background->position[2] == 'center'){echo ' checked'; }?>>
        Center</label>
        <label>
  </fieldset>
  <fieldset>
    <legend>Attachment</legend>
      <label>
        <input type="radio" name="attachment" value="none" id="attachment"<?php if( !$style->background->attachment  || $style->background->attachment == 'none'){echo ' checked'; }?>>
       None</label>
      <label>
        <input type="radio" name="attachment" value="fixed" id="attachment"<?php if( $style->background->attachment == 'fixed'){echo ' checked'; }?>>
       Fixed</label>
        <label>
        <input type="radio" name="attachment" value="local" id="attachment"<?php if( $style->background->attachment == 'local'){echo ' checked'; }?>>
        Local</label>
      <label>
        <input type="radio" name="attachment" value="scroll" id="attachment"<?php if( $style->background->attachment == 'scroll'){echo ' checked'; }?>>
        Scroll</label>
  </fieldset>
  </div>
</div>
<?php
$header_title = 'Background Admin';
$form = 'action="" method="post" name="form1"';
$header_menu = '<input type="submit" name="submitCustom" class="button on l blue" value="Save & Update"><input type="reset" class="button r" name="Reset" value="Reset">';


break;
}

$content = ob_get_contents();
ob_end_clean();

$header_menu = '<div class="header_menu_top">'.$header_menu.'</div>';
add_templates_manage( $content, $header_title, $header_menu, null, $form );