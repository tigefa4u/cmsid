<?php
/*
App Name: Contoh Aplikasi
App URI: http://cmsid.org/#
Description: Example Apps
Author: Eko Azza
Version: 1.1.1
Author URI: http://cmsid.org/#
*/ 

//dilarang mengakses
if(!defined('_iEXEC')) exit;
global $db, $login;

ob_start();
?>
<div class="border">
<a href="<?php echo do_links( 'example' );?>">Home</a> | 
<a href="<?php echo do_links( 'example', array('view' => 'top') );?>">Top</a> | 
<a href="<?php echo do_links( 'example', array('view' => 'counter') );?>">Counter</a>
</div>
<div class="clear"></div>
<div class="border">
<?php

switch( $_GET[view] ){
default:
?>
download
<?php
break;
case'top':
?>
example -> top
<?php
break;
case'counter':
?>
example -> counter
<?php
break;
}
?>
</div>
<?php
$dl = ob_get_contents();
ob_end_clean();

add_the_content_view( (object) array('view' => 'apps','title' => 'Example', 'content' => $dl) );