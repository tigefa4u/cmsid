<?php
/**
 * @file: login.php
 * @dir: content/plugins
 */
 
/*
Plugin Name: Login Widget
Plugin URI: http://cmsid.org/#
Description: Plugin widget untuk login 
Author: Eko Azza
Version: 1
Author URI: http://cmsid.org/
*/ 

if(!defined('_iEXEC')) exit;

function widget_login_form(){
	global $login;		

if( isset($_POST['login']) ){
	$username 		= filter_txt($_POST['username']);
	$password 		= filter_txt($_POST['password']);	
	$remember 		= filter_int($_POST['remember']);
			
	$login->sign_in( compact('username','password','remember') );
}
?>
<?php if( $login->check() ):?>
Anda sedang login,<br>
<ul>
    <li>Tekan <a href="?login&go=logout" onClick="return confirm('Are you sure logout?')"><b>logout</b></a> untuk keluar dari log.</li>
    <li>Silahkan <a href="?login&go=profile"><b>atur profile</b></a> anda</li>
</ul>
<?php else:?>
<form method="post" action="" id="widget-login">
<label>Username</label>
<input type="text" name="username"  id="username"/>
<label>Kata Sandi</label>
<input type="password" name="password" id="password"/>
<div style="margin-top:3px; line-height:16px;">
<label for="remember">Remember Me</label>
<input name="remember" value="1" id="remember" type="checkbox" style="width:14px">
</div>
<input type="submit" name="login" id="login" value="Masuk"/> 
</form>
<?php endif;?>
<?php
return;
}

class Widget_Login extends Widgets {

	function __construct() {
		$widget_ops = array('classname' => 'widget_login', 'description' => "Log in form" );
		parent::__construct('login', 'Login', $widget_ops);
	}

	function widget( $args ) {
		extract($args);
		$title = 'Login';

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
			
		widget_login_form();
			
		echo $after_widget;
	}
}

register_widget('Widget_Login');