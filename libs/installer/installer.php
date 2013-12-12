<?php 
/**
 * @fileName: setup.php
 * @dir: libs/installer/
 */
if(!defined('_iEXEC')) exit;
if(!defined('installing')) exit;

$the_title_installer = 'Installer';
$menuStep1 = $menuStep2 = $menuStep3 = '';
if(empty($_SESSION['hostname']) && empty($_SESSION['success'])) {
	$menuStep1 = ' current';
	$the_title_installer = 'Installer &gt; Database Configuration';
}elseif(!empty($_SESSION['hostname']) && !empty($_SESSION['mysql_user'])){
	$menuStep2 = ' current';
	$the_title_installer = 'Installer &gt; Site Configuration';
}elseif(!empty($_SESSION['success'])){
	$menuStep3 = ' current';
	$the_title_installer = 'Installer &gt; Done';
}

global $the_title_installer;?><!DOCTYPE html>
<html lang="en">  
<head>
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">
<title><?php echo $the_title_installer;?></title>

<meta name="description" content="">
<meta name="author" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0">    

<link href="libs/css/reset.css" rel="stylesheet" />
<link href="libs/css/element.css" rel="stylesheet" />
<link href="libs/css/forms.css" rel="stylesheet" />
<link href="libs/css/forms-select.css" rel="stylesheet" />
<link href="libs/css/style.css" rel="stylesheet" />
<link href="libs/css/aside.css" rel="stylesheet" />
<link href="libs/css/tiptip.css" rel="stylesheet" />
<link href="libs/css/gd.css" rel="stylesheet" />
<link href="libs/css/colors.css" rel="stylesheet" />
<link href="libs/css/table.css" rel="stylesheet" />
<link href="libs/css/css3-buttons.css" rel="stylesheet" />
<link href="libs/css/default.css" rel="stylesheet" />
<link href="libs/css/drop-shadow.css" rel="stylesheet" />
<link href="libs/css/oops.css" rel="stylesheet" />
<link href="libs/css/button.css" rel="stylesheet" />
<link href="libs/css/scroll.css" rel="stylesheet" />

<!--[if lte IE 8]>
<script src="libs/js/html5.js" type="text/javascript"></script>
<![endif]-->
</head>
<body>


<link href="libs/installer/css/nav.css" rel="stylesheet" />
<link href="libs/installer/css/nav-menu.css" rel="stylesheet" />
<div class="nav-bg">
<div class="nav nav-fix">
<div class="nav-top">

<div class="left" style="width:650px">

<ul class="mainMenu tiptip">
    <li class="topNavLink<?php echo $menuStep1;?>"><a><div class="icon menuStep1"></div><div class="icon menuStep1Name">Database Configuration</div></a>
    <li class="topNavLink<?php echo $menuStep2;?>"><a><div class="icon menuStep2"></div><div class="icon menuStep2Name">Site Configuration</div></a>
    <li class="topNavLink<?php echo $menuStep3;?>"><a><div class="icon menuStep3"></div><div class="icon menuStep3Name">Done</div></a></li>
</ul>
</div>
</div>
</div>
</div>
<div class="shadow-inside-top"></div>


<div id="body">
<div class="body-content right">
        
<div style="margin:20px 0;">
<div style="clear:both"></div>
<style type="text/css">
div.nav > div.nav-top, div.nav > div.nav-top-sub,div.gd.full-single {
	width: 450px;
}
div.body-content {
	width:100%;
}
div.body-content.right {
	float:left;
}
</style>
<?php
error_reporting(E_ALL);
$timezone = 'Asia/Jakarta';

if( $_GET['setup'] != 'yes' && !file_exists('config.php') ){
	header("Location:./?setup=yes");
	exit;
}


$dir_name_file = dirname(__FILE__);
	
/** menentukan abs_path berdasarkan direktori file*/
if (DIRECTORY_SEPARATOR=='/') $absolute_path = $dir_name_file.'/'; 
else $absolute_path = str_replace('\\', '/',$dir_name_file).'/'; 
	  
if ( !defined( 'abs_path' ) ) define( 'abs_path',  $absolute_path );

require( abs_path . 'libs/default-constants.php' );
require( abs_path . 'libs/timezone.php' );
directory_constants();
site_timezone($timezone);

require( abs_path . 'libs/filters.php' );

if( !function_exists('my_escape') ){
function my_escape( $string ){
	if( !empty( $string ) ){
		
		if (version_compare(phpversion(),"4.3.0", "<")) mysql_escape_string($string);
		else mysql_real_escape_string($string);
		
		return $string;
	}
}
}

function site_anonymise_geoip() {
	global $country_geoip,$class_country;
		
	if ( ! class_exists( 'get_country_geoip_list' ) ):
		require( abs_path . 'libs/geoip/geoip.php' );		
		require( abs_path . 'libs/class-country.php' );
		
		$country_geoip = get_country_geoip_list();
		$class_country = new country;
		
	endif;
}
site_anonymise_geoip();

if( !function_exists('sanitize') ){
function sanitize( $string ) { 
	//TRANSLIT or //IGNORE or //TRANSLIT//IGNORE
	$clean = iconv("UTF-8", "ISO-8859-1//IGNORE", $string);	
	//$clean = filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
	return $clean;
} 
}

function url(){
  	//$protocol = ($_SERVER['HTTPS'] != "off") ? "https" : "http";
  	$protocol = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '/?setup=yes';
	
	if( $protocol = explode('/?setup=yes', $protocol) ) 
		$protocol = $protocol[0];
		
	return $protocol;
}

function xcopy($source, $destination ) {
        if ( is_dir( $source ) ) {
        @mkdir( $destination );
        $directory = dir( $source );
        while ( FALSE !== ( $readdirectory = $directory->read() ) ) {
            if ( $readdirectory == '.' || $readdirectory == '..' ) {
                continue;
            }
            $PathDir = $source . '/' . $readdirectory; 
            if ( is_dir( $PathDir ) ) {
                xcopy( $PathDir, $destination . '/' . $readdirectory );
                continue;
            }
            copy( $PathDir, $destination . '/' . $readdirectory );
        }

        $directory->close();
        }else {
        copy( $source, $destination );
        }
}

function dump($sqlFile, $replace = null)
	{
		@set_time_limit(0);
		$openSqlFile = @fopen($sqlFile,"a+");
		
		while(!feof($openSqlFile)){
			$dataSqlFile = fgets($openSqlFile,50);
			@$feof.= $dataSqlFile;
		}
		
		$go = null;
		$data = explode("---",$feof);
		foreach($data as $val){
			foreach( $replace as $k => $v ){
				$val = str_replace("$k","$v",$val);
			}
			
			$val = str_replace("\r","%BR%",$val);
			$val = str_replace("\n","%BR%",$val);
			$val = str_replace("%BR%%BR%","%BR%",$val);
			$val = str_replace("%BR%"," ",$val);
			$val = str_replace("'", "'", $val);
			$val = str_replace("</p><p>", "</p> <p>", $val);
			$val = str_replace("<p><br />", "<p>", $val);
			$val = trim($val);
			//echo ("$val");
			$go  = mysql_query("$val");
		}
		return $go;
	}
	
?>
<div class="gd full-single">
<div class="gd-content">
<form action="" method="post">
<?php if(empty($_SESSION['hostname']) && empty($_SESSION['success'])) { ?>
<div class="padding">
<?php
if(isset($_POST['step_1']) 
&& empty($_SESSION['hostname']))
{ 
	$hostname 		= filter_txt( $_POST['hostname'] );
	$mysql_user 	= filter_txt( $_POST['mysql_user'] );
	$mysql_pass 	= filter_txt( $_POST['mysql_pass'] );
	$mysql_db_name 	= filter_txt( $_POST['mysql_db_name'] );
	$db_prefix 		= filter_txt( $_POST['db_prefix'] );
	
	$hostname 		= esc_sql( $hostname );
	$mysql_user 	= esc_sql( $mysql_user );
	$mysql_pass 	= esc_sql( $mysql_pass );
	$mysql_db_name 	= esc_sql( $mysql_db_name );
	$db_prefix 		= esc_sql( $db_prefix );
	$db_prefix		= preg_replace("/[^0-9a-zA-Z_]/i", '', $db_prefix);
	
	if(!empty( $hostname )
	&& !empty( $mysql_user )
	&& !empty( $mysql_db_name )
	&& !empty( $db_prefix )
	){
	
	$conn = mysql_connect($hostname, $mysql_user, $mysql_pass);
	if( !$conn ){
		echo "<div id='error'>'Could not connect host: " . mysql_error() ."</div>";
	}else{
		mysql_query("DROP DATABASE $mysql_db_name", $conn);
		mysql_query("CREATE DATABASE IF NOT EXISTS $mysql_db_name", $conn);
		$db = mysql_select_db( $mysql_db_name,$conn );
		if( !$db ){
			echo "<div id='error'>'Could not connect db: " . mysql_error() ."</div>";
		}else{
			/**
			 * making file _config.php
			 */	
			
			$file_name = "_config.php";
			if(!file_exists($file_name))
			{
				$file = "libs/installer/_config.php";
				@copy($file,'../');
			}
			$fo = @fopen($file_name,"w+");
			$s = fgets($fo,6);
			$text = ("<?php
/**
 * @fileName: config.php
 * @setting ketentuan website
 * 
 * isi sesuai dengan hak akses ke mysql server anda
 */
 
//not direct access
if(!defined('_iEXEC')) exit;


/*
 *************** Basic Setting *************
 */

//nama host mysql db
define('DB_HOST', '$hostname');
//nama pengguna mysql db
define('DB_USER', '$mysql_user');
//kata sandi mysql db	
define('DB_PASS', '$mysql_pass');
//nama database mysql	
define('DB_NAME', '$mysql_db_name');
//nama awal table
define('DB_PRE', '$db_prefix');

/*
 * untuk setting alamat url website manual jika anda pindah host/alamat domain
 * silahkan atur di database anda pd table _options cari siteurl di field option_name
 */
?>");
		
			rewind($fo);
			fwrite($fo,$text);
			$conn = fclose($fo);
			
			$_SESSION['hostname']	= $hostname;
			$_SESSION['mysql_db_name']	= $mysql_db_name;
			$_SESSION['mysql_user']	= $mysql_user;
			$_SESSION['mysql_pass']	= $mysql_pass;
			$_SESSION['db_prefix']	= $db_prefix;
			
			header('Location:./?setup=yes');
			exit;		
		}
	}
	mysql_close($conn);
	
	}else{
		echo "<div id='error'>Form can't be empty !</div>";
	}
}
?>
<table width="100%" border="0" cellpadding="2">
  <tr>
    <td width="24%">HostName</td>
    <td width="1%"><strong>:</strong></td>
    <td width="75%"><input title="your server host name"  name="hostname" autocomplete="off"  type="text" value="<?php echo (@$_POST['hostname'])? @$_POST['hostname'] : 'localhost' ?>" style="width:80%;"></td>
  </tr>
  <tr>
    <td>MySQL User</td>
    <td><strong>:</strong></td>
    <td><input title="server mysql username"  name="mysql_user" type="text" value="<?php echo @$_POST['mysql_user']; ?>"></td>
  </tr>
  <tr>
    <td>MySQL Password</td>
    <td><strong>:</strong></td>
    <td><input title="server mysql password"  name="mysql_pass" type="text" value="<?php echo @$_POST['mysql_pass']; ?>"></td>
  </tr>
  <tr>
    <td>MySQL DB</td>
    <td><strong>:</strong></td>
    <td><input title="your database name"  name="mysql_db_name" type="text" value="<?php echo (@$_POST['mysql_db_name'])? @$_POST['mysql_db_name'] : '' ?>" style="width:80%;"></td>
  </tr>
  <tr>
    <td>DB Prefix</td>
    <td><strong>:</strong></td>
    <td><input title="prefix table name"  name="db_prefix"autocomplete="off"  type="text" value="<?php echo (@$_POST['db_prefix'])? @$_POST['db_prefix'] : 'iw_' ?>"></td>
  </tr>
</table>
</div>
<div class="num" style="text-align:right;">
<input type="submit" name="step_1" value="Next &raquo;&raquo;" class="button on blue">
</div>
<?php }elseif(!empty($_SESSION['hostname']) && !empty($_SESSION['mysql_user']) ){ ?>
<div class="padding">
<?php
if( !file_exists('_config.php') ){
			
	$_SESSION['hostname']	= '';
	$_SESSION['mysql_db_name']	= '';
	$_SESSION['mysql_user']	= '';
	$_SESSION['mysql_pass']	= '';
	$_SESSION['db_prefix']	= '';
}

if(isset($_POST['step_2']))
{ 
	$siteurl 		= filter_txt( $_POST['siteurl'] );
	$sitename 		= filter_txt( $_POST['sitename'] );
	$username 		= filter_txt( $_POST['username'] );
	$password 		= filter_txt( $_POST['password'] );
	$author 		= filter_txt( $_POST['author'] );
	$admin_email 	= filter_txt( $_POST['admin_email'] );
	$country 		= filter_txt( $_POST['country'] );
	$sex 			= filter_txt( $_POST['sex'] );
	$timezone		= filter_txt( $_POST['timezone'] );
	
	$siteurl 		= esc_sql( $siteurl );
	$sitename 		= esc_sql( $sitename );
	$username 		= esc_sql( $username );
	$password 		= esc_sql( $password );
	$author 		= esc_sql( $author );
	$admin_email 	= esc_sql( $admin_email );
	$country 		= esc_sql( $country );
	$sex 			= esc_sql( $sex );
	$timezone		= esc_sql( $timezone );
	
	if(!empty($siteurl) 
	or !empty($sitename) 
	or !empty($username)  
	or !empty($password)
	or !empty($author)
	or !empty($admin_email)
	or !empty($country)
	or !empty($sex))
	{
						
		$data = abs_path . "libs/installer/data.sql";
		
			
		if(preg_match('/^.+@.+\\..+$/',$admin_email))
		{
		
		if( file_exists('_config.php') ) 
		{
			require('_config.php');
			
			mysql_connect(DB_HOST,DB_USER,DB_PASS);
			mysql_select_db(DB_NAME);
			
			$replace = array(
				"_installer_prefix_" => DB_PRE,
				"_installer_siteurl_" => "$siteurl",
				"_installer_sitename_" => "$sitename",
				"_installer_admin_email_" => "$admin_email",
				"_installer_timezone_" => "$timezone",
				"_installer_author_" => "$username"			
			);
			
			$go_data = dump( $data, $replace );	
		}	
		
		if( $go_data )
		{
			
			$user_registered 		= date('Y-m-d H:i:s');
			$user_last_update 		= $user_registered;
			$user_login 			= $username;
			$user_pass 				= $password;
			$user_pass 				= md5($user_pass);
			$user_email 			= $admin_email;
			$user_sex 				= $sex;
			$user_country 			= $country;
			$user_author 			= $author;
			$user_status			= '1';
			$user_level 			= 'admin';
			
			$data = compact('user_login','user_author','user_email','user_pass','user_sex','user_registered','user_last_update','user_level','user_status','user_country');
			
			$fieldKeys = $fieldValues = array();
			foreach ( $data as $fieldKey => $fieldValue ) {
				$fieldKeys[] = my_escape( $fieldKey );
				$fieldValues[] = my_escape( $fieldValue );
			}
			
			$sql = "INSERT INTO `".DB_PRE."users` (`" . implode( '`,`', $fieldKeys ) . "`) VALUES ('" . implode( "','", $fieldValues ) . "')";
			$qr  = mysql_query($sql); 
			if( $qr ){
				$_SESSION['mysql_user'] = "";
				$_SESSION['success'] = 1;
				
				header('Location:./?setup=yes');
				exit;
			}else{
				echo mysql_error();
			}
		}
		
		}
		else
		{
			echo "<div id='error'>Email or user are invalid !</div>";
		}
	}
	else
		echo "<div is='error'>Please complete the fields first !</div>";
}
?>
<table width="100%" border="0" cellpadding="2">
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr style="border-bottom:1px solid #ddd;">
    <td colspan="3"><strong>Login Account</strong></td>
    </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td>User Name</td>
    <td><strong>:</strong></td>
    <td><input name="username" autocomplete="off"  type="text" value="<?php echo @$_POST['username']; ?>" style="width:50%;"></td>
  </tr>
  <tr>
    <td>Password</td>
    <td><strong>:</strong></td>
    <td><input name="password" autocomplete="off"  type="password" value="<?php echo @$_POST['password']; ?>" style="width:50%;"></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr style="border-bottom:1px solid #ddd;">
    <td colspan="3"><strong>Data Site</strong></td>
    </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td width="24%">Site Url</td>
    <td width="1%"><strong>:</strong></td>
    <td width="75%"><input name="siteurl" type="text" value="<?php echo (@$_POST['siteurl']) ? @$_POST['siteurl'] : url(); ?>" style="width:80%;">*</td>
  </tr>

  <tr>
    <td></td>
    <td></td>
    <td>*( ex: http://domain.com</td>
  </tr>
  <tr>
    <td>Site Name</td>
    <td><strong>:</strong></td>
    <td><input name="sitename" type="text" value="<?php echo @$_POST['sitename']; ?>" style="width:80%;"></td>
  </tr>
  <?php  if ( function_exists('timezone_supported') ) :?>
  <tr>
    <td>Timezone</td>
    <td><strong>:</strong></td>
    <td><select name="timezone"><?php echo timezone_choice($timezone);?></select></td>
  </tr>
    <?php endif;?>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr style="border-bottom:1px solid #ddd;">
    <td colspan="3"><strong>Data Personal</strong></td>
    </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td>Author Name</td>
    <td><strong>:</strong></td>
    <td><input name="author"  type="text" value="<?php echo @$_POST['author']; ?>" style="width:50%;"></td>
  </tr>
  <tr>
    <td>Email</td>
    <td><strong>:</strong></td>
    <td><input name="admin_email"  type="text" value="<?php echo (@$_POST['admin_email']) ? @$_POST['admin_email'] : $_SERVER["SERVER_ADMIN"]; ?>" style="width:80%;"></td>
  </tr>
  <tr>
    <td>Country</td>
    <td><strong>:</strong></td>
    <td><select name="country"><?php $class_country->country_list(); ?></select></td>
  </tr>
  <tr>
    <td>Sex</td>
    <td><strong>:</strong></td>
    <td><select name="sex"><option value="l">Laki-laki</option><option value="p">Perempuan</option></select></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
</table>
</div>
<div class="num" style="text-align:right;">
<input type="submit" name="step_2" value="Next &raquo;&raquo;" class="button on blue">
</div>
<?php }elseif(!empty($_SESSION['success'])){?>
<?php
if(isset($_POST['admin']))
{ 		
	header("location:index.php?admin");
}
else if(isset($_POST['finish']))
{ 	
	header("location:index.php");
} 

if( isset($_POST['admin']) || isset($_POST['finish']) ){
// Unset all of the session variables.
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();
rename("_config.php","config.php");
}
?>
<div class="padding">
<p><strong>Install Successfuly</strong></p>
<p>Selamat, CMS telah sukses di instal dan telah siap digunakan :)<br>Gunakan tombol dibawah ini sebagai navigasi anda.</p>
<p><strong>Admin Page:</strong> untuk melihat antarmuka administrator<br><strong>Finish:</strong> selesai instalasi dan di redirect ke halaman utama / home</p>
</div>
<div class="num" style="text-align:right;">
<input type="submit" name="admin" value="Admin Page" class="button l"><input type="submit" name="finish" value="Finish" class="button on r green">
</div>
<?php }?>
</form> 

</div>
</div>       
</div>
</div>	

</div>


</body>
</html>