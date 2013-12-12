<?php 
/**
 * @fileName: manage.php
 * @dir: admin/manage/example
 */
if(!defined('_iEXEC')) exit;
global $db;
$go 	= filter_txt($_GET['go']);
$act	= filter_txt($_GET['act']);

ob_start();

switch($go){
default:

echo 'this your manage form app';

break;
}

$content = ob_get_contents();
ob_end_clean();

$widget_manual = array();
$widget_manual['gadget'][] = array('title' => 'Example Widgets manual', 'desc' => 'this your content');


$form = 'method="post" action="" enctype="multipart/form-data"'; // form jika menggunakan action form keseluruhan app
add_templates_manage( $content, 'Judul Example App', $header_menu = null , $widget_manual, $form );

?>
