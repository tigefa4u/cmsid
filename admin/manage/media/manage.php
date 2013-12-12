<?php 
/**
 * @fileName: manage.php
 * @dir: admin/manage/media
 */
if(!defined('_iEXEC')) exit;
global $db, $widget;
$go 	= filter_txt($_GET['go']);
$type	= filter_txt($_GET['type']);

ob_start();

switch($go){
default:

if( empty($type) ) 
$type = 'images';

?>
<iframe src="admin/manage/media/kcfinder/browse.php?type=<?php echo $type; ?>" width="100%" height="500px" style="background:#fff; margin:0; padding:0;" scrolling="no"></iframe>
<?php
break;

}

$content = ob_get_contents();
ob_end_clean();

$select_image = $select_media = $select_flash = $select_file = '';
if( $type == 'media' ) $select_media = ' black';
elseif( $type == 'flash' ) $select_flash = ' black';
elseif( $type == 'file' ) $select_file = ' black';
else $select_image = ' black';


$header_menu = '<div class="header_menu_top2">
<a href="?admin=single&sys=media&type=images" class="button l'.$select_image.'">Image</a>
<a href="?admin=single&sys=media&type=media" class="button m'.$select_media.'">Media</a>
<a href="?admin=single&sys=media&type=flash" class="button m'.$select_flash.'">Flash</a>
<a href="?admin=single&sys=media&type=file" class="button r'.$select_file.'">Files</a></div>'; 

add_templates_manage( $content, 'Media Manager', $header_menu ); 

?>