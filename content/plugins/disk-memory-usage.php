<?php
/**
 * @file: disk-memory-usage.php
 * @dir: content/plugins
 */
 
/*
Plugin Name: Disk and Memory Overview
Plugin URI: http://cmsid.org/#
Description: Plugin bla bla bla
Author: Eko Azza
Version: 1.2.0
Author URI: http://cmsid.org/
*/ 

if(!defined('_iEXEC')) exit;

function get_directory_size($path) 
{ 
  $totalsize = 0; 
  $totalcount = 0; 
  $dircount = 0; 
  if ($handle = opendir ($path)) 
  { 
    while (false !== ($file = readdir($handle))) 
    { 
      $nextpath = $path . '/' . $file; 
      if ($file != '.' && $file != '..' && !is_link ($nextpath)) 
      { 
        if (is_dir ($nextpath)) 
        { 
          $dircount++; 
          $result = get_directory_size($nextpath); 
          $totalsize += $result['size']; 
          $totalcount += $result['count']; 
          $dircount += $result['dircount']; 
        } 
        elseif (is_file ($nextpath)) 
        { 
          $totalsize += filesize ($nextpath); 
          $totalcount++; 
        } 
      } 
    } 
  } 
  closedir ($handle); 
  $total['size'] = $totalsize; 
  $total['count'] = $totalcount; 
  $total['dircount'] = $dircount; 
  return $total; 
} 

function size_format($size) 
{ 
    if($size<1024) 
    { 
        return $size." bytes"; 
    } 
    else if($size<(1024*1024)) 
    { 
        $size=round($size/1024,1); 
        return $size." KB"; 
    } 
    else if($size<(1024*1024*1024)) 
    { 
        $size=round($size/(1024*1024),1); 
        return $size." MB"; 
    } 
    else 
    { 
        $size=round($size/(1024*1024*1024),1); 
        return $size." GB"; 
    } 

}  

function convert_percentage($memory){	
	//dalam megabyte		
	if ( !empty($memory['usage']) && !empty($memory['limit']) ) {
		$memory['percent'] = round ($memory['usage'] / $memory['limit'] * 100, 0);
		$memory['color'] = '#21759B';
	if ($memory['percent'] > 25) $memory['color'] = '#219b66';
	if ($memory['percent'] > 45) $memory['color'] = '#2dbb70';
	if ($memory['percent'] > 65) $memory['color'] = '#E66F00';
	if ($memory['percent'] > 85) $memory['color'] = 'red';
	}
	
	return $memory;	
}

function convert_graphic($memory){	

	if (!empty($memory['percent'])) :
	
	if( $memory['percent'] > 100 ) $memory['percent'] = 100;
	
	echo '<div class="progressbar">
	<div class="widget" style="height:2em; background-color:#e4e4e4;">
	<div class="widget progress_anim" style="width: '.$memory['percent'].'%;height:99%;background:'.$memory['color'].' ;border-width:0px;text-shadow:0 1px 0 #000000;color:#FFFFFF;text-align:right;"><div style="padding:4px; min-width:15px;">'.$memory['percent'].'%</div></div>
	</div>
	</div>';
	endif; 
}

function disk_memory_overview(){ 

if( checked_option( 'disk_limit' )  ) $disk_limit = get_option('disk_limit');
else $disk_limit = 50; //desk default

?>

<?php
$dp_content = '<div class="padding">';
$dp_content.= '<label for="txtDiskLimit">Disk Your Host Limit : </label>';
$dp_content.= '<input id="txtDiskLimit" name="txtDiskLimit" type="text" style="width:50px" value="'.$disk_limit.'" /> MByte';
$dp_content.= '</div>';

$dp_footer = '<input id="log_submit" type="submit" name="submitDiskLimit" value="Submit" class="button on l" />';
$dp_footer.= '<input type="reset" name="Reset" value="Reset" class="button r" />';

$setting = array(
	'dp_id' => 'disk_memory_overview',
	'dp_title' => 'Pengaturan Space Memory',
	'dp_content' => $dp_content,	
	'dp_footer' => $dp_footer
);
add_dialog_popup( $setting );
?>
<div style="clear:both"></div>
<div class="padding">
<?php 
if( isset($_POST['submitDiskLimit']) ){
	$txt_disk_limit = $_POST['txtDiskLimit'];
	if( checked_option( 'disk_limit' ) ) set_option( 'disk_limit', $txt_disk_limit );
	else add_option( 'disk_limit', $txt_disk_limit );
	
	redirect('?admin');
}

$disk = $memory = array();
$directory_info = get_directory_size( abs_path ); 

$disk['limit'] = (int) $disk_limit;
$disk['usage'] = $directory_info['size'] ? round($directory_info['size'] / 1024 / 1024, 2) : 0;

$disk = convert_percentage($disk);	

$disk['limit'] = empty($disk['limit']) ? 'N/A' : $disk['limit'] .' MByte';
$disk['usage'] = empty($disk['usage']) ? 'N/A' : $disk['usage'] .' MByte';

echo "<strong>Disk limit :</strong> ".$disk['limit']; 
echo ", <strong>Disk usage :</strong> ".$disk['usage']; 
echo ", <strong>Files :</strong> ".$directory_info['count']; 
echo ", <strong>Dirs :</strong> ".$directory_info['dircount']; 

convert_graphic($disk);

$memory['limit'] = (int) ini_get('memory_limit') ;
$memory['usage'] = function_exists('memory_get_usage') ? round(memory_get_usage() / 1024 / 1024, 2) : 0;
			
$memory = convert_percentage($memory);	
			
$memory['limit'] = empty($memory['limit']) ? 'N/A' : $memory['limit'] .' MByte';
$memory['usage'] = empty($memory['usage']) ? 'N/A' : $memory['usage'] .' MByte';

echo '<strong>Memory limit :</strong> <span>'.$memory['limit'].'</span>, <strong>Memory usage :</strong> <span>'.$memory['usage'].'</span>';

convert_graphic($memory);
?>
<br />
</div>
<?php
}

add_dashboard_widget( 'disk_memory_overview', 'Disk & Memory Overview', 'disk_memory_overview', true );