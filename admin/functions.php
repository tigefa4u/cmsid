<?php 
/**
 * @fileName: functions.php
 * @dir: admin/templates/
 */
if(!defined('_iEXEC')) exit;

if( !function_exists('add_activity') ){
	function add_activity(){}
}
/**
 * membuat menu action pada admin
 *
 * @return array, html
 */
function add_manager_top(){
	global $login, $aside_default;
	


$style_width_top_left = ' style="width:650px"';
$style_width_menu_left = ' style="width:800px"';
if( $_SESSION['lw'] == 'full' ) $style_width_menu_left = $style_width_top_left = '';
?>

<link href="libs/css/nav.css" rel="stylesheet" />
<link href="libs/css/nav-menu.css" rel="stylesheet" />
<div class="nav nav-fix">
<div class="nav-top">
<div class="left"<?php echo $style_width_top_left;?>>
<ul class="mainMenu tiptip">
<?php 
$top_menu_home = $top_menu_app = $top_menu_plugin = $top_menu_install = '';

if( is_sys_values() == 'applications' || is_apps() )
	$top_menu_app = ' current';
elseif( is_sys_values() == 'plugins' )
	$top_menu_plugin = ' current';
elseif( is_sys_values() == 'installer' )
	$top_menu_install = ' current';
elseif( is_sys_values() == 'options' && get_query_var('go') == 'help-guide' )
	$top_menu_help = ' current';
else 
	$top_menu_home = ' current';

?>
    <li class="topNavLink <?php echo $top_menu_home;?>"><a href="?admin" class="tip" title="Home"><div class="icon menuHome"></div></a></li>
    <li class="topNavLink"><a class="tip" id="menuJump" title="Jump" style="cursor:pointer;"><div class="icon menuJump"></div></a></li>
    <div class="menuNavJumpFirst" style="display:block; float:left;">
    <li class="topNavLink<?php echo $top_menu_app;?>"><a href="?admin&sys=applications"><div class="icon menuApps"></div><div class="icon menuAppsName">Applications</div></a><span class="jTips animating"><?php echo get_counted( 'app' );?></span></li>
    <li class="topNavLink<?php echo $top_menu_plugin;?>"><a href="?admin&sys=plugins"><div class="icon menuPlugins"></div><div class="icon menuPluginsName">Plugins</div></a><span class="jTips animating"><?php echo get_counted( 'plg' );?></span></li> 
    <li class="topNavLink<?php echo $top_menu_install;?>"><a href="?admin&sys=installer"><div class="icon menuInstall"></div><div class="icon menuInstallName">Installer</div></a></li>
    <li class="topNavLink<?php echo $top_menu_help;?>"><a href="?admin=single&sys=options&go=help-guide"><div class="icon menuHelp"></div><div class="icon menuHelpName">Help & Guide</div></a></li>
    </div>
    <div class="menuNavJumpSecond" style="display:none; float:left;">
 <li class="topNavLink nav_jump">
    <a>
    <form action="" method="get" class="nav_jump_form">
        <input type="hidden" name="admin" value="single"/>  
        <input type="text" class="text_jump" name="apps" placeholder="Jump App to" /> 
       <!-- <input type="submit" class="submit_jump" value=""/>--> 
    </form>
    </a>
    </li>
<?php
$i = 1;
$limit_show = 5;

$i = 1;
foreach($aside_default as $k => $v){
	
	if( $i <= $limit_show && get_sys_cheked( $k ) || get_apps_cheked( $k, true ) ):	
		
		$current = '';
		if( $k == $_GET['apps'] or $k == $_GET['sys'] ) $current = ' current';		
		
		echo '<li class="topNavLink '.$current.'"><a href="'.$v['link'].'">'.$v['title'].'</a></li>';
		$i++;
	endif;
}

?>
    </div>
</ul>
</div>

<div class="right">
<?php

$data_user 	= array('user_login' => $login->exist_value('username'));
$field 		= $login->data( $data_user );
?>
<ul class="mainMenu tiptip menuNavJumpFirst"  style="display:block;">
    <li class="topNavLink">
    <a href="?login&go=profile" class="tip" title="Lihat Data Profile">
    <?php $avatar_img_profile = avatar_url($login->exist_value('username'));?>
    <img class="img" src="<?php echo $avatar_img_profile;?>" alt="">
    <span class="headerTinymanName"><?php echo $field->user_author;?></span>
    </a>
    </li>
    <li class="topNavLink"><a href="./" class="tip" title="Pratinjau Situs" target="_blank"><div class="icon menuViewsearch"></div></a></li>
    <li class="topNavLink">
    <dl class="staticMenu"><dt><a href="#" onclick="return false"><div class="icon menuPulldown"></div></a></dt>
    <dd>
    <ul class="mainMenuSub" style="right:0;left:auto">
        <li><a href="?admin&sys=options">Pengaturan</a></li>
        <li class="logout"><a href="?login&go=logout" onclick="return confirm('Yakin ingin keluar?')">Keluar</a></li>
        <div style="clear:both"></div>
        <li class="seperator"><div></div></li>
        <li class="help"><a href="?admin=single&sys=options&go=help-guide">Bantuan</a></li>
    </ul>
    </dd>
    </dl>
</li>
</ul>
<ul class="mainMenu right menuNavJumpSecond" style="display:none;">
	<?php
	if( 'full' == $_SESSION['lw'] ){
		$lw_title = 'Change to Wrap';
		$lw = ' goFull';
	}
	elseif( 'single' == $_SESSION['lw'] ){
		$lw_title = 'Change to Full';
		$lw = ' goSingle';
	}
	else{
		$lw_title = 'Change to Single';
		$lw = ' goWrap';
	}
	?>
	<li class="topNavLink navSub currentRight"><a title="<?php echo $lw_title;?>"><div class="icon menuOverStyle<?php echo $lw;?>"></div></a></li>
</ul>

</div>
</div>
</div>
<div class="shadow-inside-top"></div>

<?php
}

add_action('manager_top','add_manager_top');

/**
 * Gets the copunt apps and plugins.
 *
 * @return number
 */
function get_counted( $get_ = 'app' ){
	
	if( $get_ == 'app' ) return count( get_mu_apps() );
	elseif( $get_ == 'plg' ) return count( get_noactive_and_valid_plugins() );
	else return false;	
}
function add_manager_header_notif(){
	
if( get_query_var('x') == 'header-notif' ){
	if( checked_option( 'header-notif-x' ) ) set_option( 'header-notif-x', 1 );
	else add_option( 'header-notif-x', 1 );
	
	redirect('?admin');
}
?>
<style type="text/css">
::-webkit-scrollbar { 
    display: none; 
}
.codrops-top-notif-bg{
	width:100%;
	height:100%;
	z-index: 9999;
	position: fixed;
	
	background-color: #dddddd;
	background-color: rgba(221, 221, 221, .7);
	border-bottom: 1px solid #818285;
	color: #fff;
	line-height: 45px;
	font-family:"LG";
	font-size:14px;
}
.codrops-top-notif-content{
	top:0;
	width:100%;
	height:100%;
	z-index: 9999;
	position: fixed;
	
	background-color: black;
	background-color: rgba(129, 130, 133, .8);
	border-bottom: 1px solid #818285;
	color: #ECECEC;
	line-height: 45px;
	font-family:"LG";
}

.codrops-top-notif-content .wrap-width{
	width:550px;
	height:100%;
	margin:0 auto;
	margin-top:20px;
}

.header_notif a{
	color: #fff;
	font-family:"LGB";
	text-decoration:none;
}

.img_close_h{
	height:50px;
	float:left;
}

.codrops-top-notif-content span{
	color: #fff;
	font-family:"LGB";
	text-decoration:none;
}

.codrops-top-notif-content a span.img_close{
	display: block;
	float:left;
	background:url(libs/img/close_header_top_notif.png) no-repeat center top;
	background-position:0 0;
	width:21px;
	height:19px;
	margin-top:5px;
	margin-right:5px;
}
.codrops-top-notif-content a:hover span.img_close:hover{
	background-position:0 -19px;
}

.header_notif_p{
	margin:0;
	padding:0;
}

.ebbeded_video{
	text-align:center;
	border:3px solid rgba(255,255,255,0.5);
	width:100%;
	height:350px;
	margin:0 auto;
	background:url(libs/img/icon-play.png) no-repeat center;
	
	box-shadow:0 1px 1px #ccc;
	-moz-box-shadow:0 1px 1px #ccc;
	-webkit-box-shadow:0 1px 1px #ccc;
}

.img_close_guide{
	position:absolute;
	height:150px;
	width:150px;
	margin-top:10px;
	margin-left:-150px;
	background:url(libs/img/petunjuk.png) no-repeat right top;
}

</style>

<div class="codrops-top-notif-bg">
<div class="codrops-top-notif-content">
<div class="wrap-width">

<div class="header_notif">
<div class="img_close_guide"></div>
<div class="img_close_h"><a href="?admin&x=header-notif"><span class="img_close"></span></a></div>
<div class="header_notif_p">
<p>
<span>Selamat datang</span>, terimakasih sudah menggunakan karya dan produk anak negeri, untuk berpartisipasi dalam pengembangan cms ini <a href="http://cmsid.org/page-support-us.html" target="_blank">lihat tautan ini</a>.<br /><br />
Jika ingin tahu lebih lanjut penggunaan cms ini Silahkan lihat demo berikut ini.
</p>
</div>
</div>
<div style="clear:both"></div>
<div class="ebbeded_video">
<iframe title="YouTube video player" class="youtube-player" type="text/html" 
width="100%" height="350" src="http://help.cmsid.org/?frame=full&video=demo"
frameborder="0" allowFullScreen></iframe>
</div>
<div style="clear:both"></div>
</div>
</div>
</div>
<?php
}

if( 1 != get_option('header-notif-x') )
add_action('manager_header','add_manager_header_notif');

function add_manager_content(){
	
	if(!isset($_SESSION) )
		session_start();
	
	if( 'oops' == is_admin_values() ) the_main_oops();
	elseif( 'full' == $_SESSION['lw'] || 'full' == is_admin_values() ){
	?>
		<link href="libs/css/full.css" rel="stylesheet" />  
		<div style="padding:10px;"><?php echo the_main_manager()?></div>
		<?php
	}elseif( 'wrap' == $_SESSION['lw'] or is_admin_values() == 'single' ){		
	?>		
	<link href="libs/css/wrap.css" rel="stylesheet" />  
	<div id="body">
        <div class="body-content">
        <?php the_actions_menu()?>
        <?php the_main_manager()?>
        </div>
	</div>
		<?php
	}else{		
	?>
	<div id="body">
        <div class="aside left">
            <div class="section">
            <?php the_actions_menu()?>
            </div>
        </div>
        <div class="body-content right">
        <?php the_main_manager()?>
        </div>
	</div>
	<?php
	}
}

add_action('manager_content','add_manager_content');


function the_main_oops(){
	if ( file_exists( content_path . '/oops.php' ) ) {
		require_once( content_path . '/oops.php' );
		die();
	}
	?>
    <div id="oops_body" class="drop-shadow lifted">
    <div class="oops_content">
    <div class="oops_logo">Ups maaf! 
    <div class="gd-menu right">
    <a href="?admin" class="button">&laquo;&laquo; Kembali ke dashboard</a>
    </div>
    </div>
    <div style="clear:both"></div>
    <div style="padding:10px;">
    <div class="atentions_logo"><span class="atentions_logo"></span></div>
    <p style="margin-left:60px;">
    <h1>Halaman tidak ditemukan!</h1><br />
    Ups halaman '<?php echo esc_sql( $_SERVER['HTTP_REFERER'] )?>' yang Anda cari telah dipindahkan atau tidak ada lagi..<br />
    Silakan coba halaman lain tetapi jika Anda tidak dapat menemukan apa yang Anda cari, beritahukan kepada kami.<br /><br />
    </p>
    </div>
    <div style="clear:both"></div>
    </div>
    </div>
    <?php
}

function list_category_op( $id = false ){
	global $db;
		
	$q = $db->select("post_topic");
		
	$op = '';
	while($row 	= $db->fetch_array($q)){
		if(!empty($id) && $row['id'] == $id)
			$op.= '<option value="'.$row['id'].'" selected="selected">'.$row['topic'].'</option>'."\n";
		else
			$op.= '<option value="'.$row['id'].'">'.$row['topic'].'</option>'."\n";
	}
		
	return $op;
}

/**
 * membuat menu action pada admin
 *
 * @return array, html
 */
function the_actions_menu(){
global $widget, $aside_default, $applications;
	
	if( get_sys_cheked( get_query_var('sys') ) 
	&& $values = is_sys_values() )
	{
		get_sys_included( $values, 'init' );
	}
	elseif( get_apps_cheked( get_query_var('apps'), true )
	&&  $values = is_apps_values() )
	{
		get_apps_included( $values, true, 'init' );
	}
	
	do_action('the_actions_menu');
	
	
	
	if('full' == $_SESSION['lw'] 
	or 'wrap' == $_SESSION['lw'] 
	or is_admin_values() == 'single' 
	or is_sys_values() == 'installer' )
		return false;
	else{
		
?>
<ul class="menu-box"><li><a href="./?admin">Dashboard</a></li></ul>
<div class="p head">Actions<a id="menuActions" style="cursor:pointer">
<span class="menuActions" style="display:block;">↓</span><span class="menuActions" style="display:none;">↑</span></a></div><div class="plr menuActions_list" style="display:none;"><ul class="menu-box">
<?php		
if( isset($widget['menu']) && count($widget['menu']) > 0 && !empty($widget['menu']) ) {
	foreach($widget['menu'] as $k => $v){
		echo '<li><a href="'.$v['link'].'">'.$v['title'].'</a></li>';
	}
}else{
	$applications_new = $plugins_new = $aside_menus = array();
	foreach($applications as $key => $val){
		$applications_new[$key] = array(
			'title' => $val['Name'], 
			'link' => '?admin&apps=' . $key
			);
	}
	
	$plugins 	= get_dir_plugins();
	foreach($plugins as $key => $val){
		$name = get_plugins_name($key);
		$key2 = str_replace( $name .'/', '' , $key );
		
	 	if( !empty($name)
		&& file_exists( plugin_path .'/'. $name . '/admin.php' )
		&& get_plugins( $key ) == 1 ){
			
		$plugins_new[$key] = array(
			'title' => $val['Name'], 
			'link' => '?admin&sys=plugins&go=setting&plugin_name='.$name.'&file=/'.$key2
			);
		}
	}
	
	$aside_menu = parse_args($applications_new,$aside_default);
	foreach($aside_menu as $k => $v){
		if( get_sys_cheked( $k ) || get_apps_cheked( $k, true ) ):
		$aside_menus[$k] = array(
			'title' => $v['title'], 
			'link' => $v['link']
			);
		endif;
	}
	
	$aside_menus = parse_args($plugins_new,$aside_menus);	
	$aside_menus = array_multi_sort($aside_menus, array('title' => SORT_ASC));
	foreach($aside_menus as $k => $v){
		echo '<li><a href="'.$v['link'].'">'.$v['title'].'</a></li>';
	}
}
?>
</ul></div>
<?php if( isset($widget['help_desk']) && !empty($widget['help_desk']) ):?>
<div class="p head">Tip</div><div class="p"><?php echo $widget['help_desk'];?></div>
<?php endif;?>
<div class="p head">Copyright <a href="http://cmsid.org/page-abouts.html" target="_blank">
<span>i</span></a></div><div class="p"><?php echo cleanname( get_option('site_copyright') )?></div> 
    <?php 
	}	
}


function add_templates_manage( 
	$templates_content, 
	$templates_title = null, 
	$templates_header_menu = null, 
	$widget_manual = null, 
	$form = null, 
	$templates_footer = null,
	$gd = 'content-fix' ){
	
	do_action('add_templates_manage');	
	
	return add_templates_content_position( 
	$templates_content, 
	$templates_title, 
	$templates_header_menu, 
	$widget_manual, 
	$form, 
	$templates_footer,
	$gd );
}

function add_templates_content_position( 
	$templates_content, 
	$templates_title = null, 
	$templates_header_menu = null, 
	$widget_manual = null, 
	$form = null, 
	$templates_footer = null,
	$gd = 'content-fix' ){
		
	global $widget;
	
	do_action('add_templates_content_position');	
	
	if( !empty($widget_manual) ) $widgetx = $widget_manual;
	else $widgetx = $widget;	
		
	$widget_avalable = false;
	if( count($widgetx['gadget']) > 0 && !empty($widgetx['gadget'][0]) )
		$widget_avalable = true;	
		
	if( $gd == 'full-single' ){
		?>
        <style type="text/css">
		.gd-menu {margin-top: 0;}
		</style>
        <?php
	}
	
	$add_templates_content_widget = _add_templates_content_widget( $widgetx );
	$add_templates_content = _add_templates_content( 
		$templates_content, 
		$templates_title, 
		$templates_header_menu, 
		$templates_footer,
		$gd );
	
	$content = '';
	if( $widget_avalable ) :
	
	$content.= '<div id="post-left">';
	$content.= $add_templates_content;
	$content.= '</div>';
	$content.= '<div id="post-right">';
	$content.= $add_templates_content_widget;
	$content.= '</div>';	
	
	else:
	
	$content.= $add_templates_content;
	
	endif;
	
	if( !empty($form) )
		echo sprintf('<form %s>%s</form>', $form, $content);
	else
		echo $content;
		
	return;
}

function _add_templates_content( 
	$templates_content, 
	$templates_title = null, 
	$templates_header_menu = null,
	$templates_footer = null,
	$gd = 'content-fix' ){
	global $widget;
	
	do_action('add_templates_content');
	
	$content = '<div class="gd '.$gd.'">';
	$content.= '<div class="gd-header-single">'.$templates_title;
	
	if( !empty($templates_header_menu) )
	$content.= '<div class="gd-menu right">'.$templates_header_menu.'</div>';
		
	$content.= '</div>';
	$content.= '<div class="gd-content">';
	$content.= $templates_content;
	$content.= '</div>';
	
	if( !empty($templates_footer) ):
	$content.= '<div class="gd-footer">';	
	$content.= $templates_footer;		
	$content.= '</div>';
	endif;
		
	$content.= '</div>';
	
	return $content;
}

function _add_templates_content_widget( $current_widget = null){
	
	if( !is_array($current_widget['gadget']) )
		return false;
		
	$content = '';
	foreach($current_widget['gadget'] as $box){
		$content.= '<div class="gd">';
		$content.= '<div class="gd-header-single">';
		$content.= $box['title'];
		
		if( !empty($box['menu']) )
		$content.= '<div class="gd-menu right">'.$box['menu'].'</div>';
		
		$content.= '</div>';
		$content.= '<div class="gd-content">';
		$content.= $box['desc'];
		$content.= '</div>';
		
		if( !empty($box['foot']) ):
		$content.= '<div class="gd-footer">';		
		$content.= $box['foot'];			
		$content.= '</div>';
		endif;
		
		$content.= '</div>';
	}
	
	return $content;
}

function save_quick_post($data){
		global $db,$login; 
		extract($data, EXTR_SKIP);
		
		$title 		= esc_sql($title);
		$type 		= esc_sql($type);
		$post_topic	= esc_sql($category);
		$tags 		= esc_sql($tags);
		$content	= esc_sql($isi);
		$date_post	= esc_sql($date);
		$status		= esc_sql($status);
		$approved	= esc_sql($approved);
		
		$meta_keys 	= esc_sql($meta_keys);
		$meta_desc 	= esc_sql($meta_desc);
		
		if( $thumb ):
		$thumb		= hash_image( $thumb );
		$thumb 		= esc_sql($thumb['name']);
		else: $thumb = '';
		endif;
		
		$seo 		= new engine;
		$sefttitle	= esc_sql($seo->judul($title));
		$user_login	= esc_sql($login->exist_value('user_name'));		
		$row 		= $login->data( compact('user_login') );
		$mail		= esc_sql($row->user_email);
		
		$data = compact('user_login','title','sefttitle','post_topic','mail','type','content','thumb','tags','date_post','status','approved','meta_keys','meta_desc');
		return $db->insert('post',$data);
}


function add_quick_post( $data ){
		extract($data, EXTR_SKIP);
		
		$msg = array();
		if( empty($title) ) $msg[] ='<strong>ERROR</strong>: The title is empty.';
		if( empty($category) ) $msg[] ='<strong>ERROR</strong>: The category is empty.';
		
		if( $msg ) foreach($msg as $error) echo '<div id="error">'.$error.'</div>';
		else
		{
			if( save_quick_post($data) ) 
			echo '<div id="success"><strong>SUCCESS</strong>: Posting berhasil di tambahkan</div>';
		}
}

function restrict_access(){
	global $login;
	
	if( !$login->check() or $login->level('user') ){
		redirect('?login');
	}
}
add_action('the_head_admin','restrict_access');

if( $_SESSION['theme'] ){
	$_SESSION['theme'] = '';
unset(
	$_SESSION['theme']
	);
}
?>