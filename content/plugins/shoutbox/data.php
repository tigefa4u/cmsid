<?php 
/**
 * @fileName: data.php
 * @dir: shoutbox/
 */
if(!defined('_iEXEC')) exit;
global $db, $login;

if( 'shoutbox/data.php' == is_load_values() ):

$o = filter_txt($_GET['option']);
if( $o == 'post' ){

$data = array(
	'nama' 	=> filter_post('nama'),
	'email' => filter_post('email'),
	'pesan' => filter_post('pesan'),
	'waktu'	=> date('Y-m-d H:i:s') 
);

$security_posted = false;

if( security_posted('shoutbox', true) < 1 || $login->level('admin') ){ $security_posted = true; }
	
if( $security_posted ){
if ( !empty($data['nama']) && !empty($data['email']) && !empty($data['pesan']) ):

	$query_shoutbox	= $db->insert("shoutbox", $data );	
	if( $query_shoutbox && $user->user_level != 'admin' ) 
		security_posted('shoutbox');
		
endif;
}
	
}elseif( $o == 'get' ){

$q = $db->select('shoutbox',null,'ORDER BY id DESC LIMIT 30');
if( $db->num($q) < 1 ){
	echo'<center>No data</center>';
}else{
	echo'<table style="width:100%">';
	$warna	= '';
	
	while($r = $db->fetch_array($q)){
		
	$warna 	= empty ($warna) ? ' bgcolor="#f1f6fe"' : '';
	$pesan	= shoutbox_filter( $r['pesan'] );
	echo'<tr '.$warna.'><td><a href="mailto:'.$r['email'].'">'.substr($r['nama'],0,15).'</a> : '.$pesan.'</td></tr>';
	echo'<tr '.$warna.'><td><span style="font-color:gray;font-size:10px">'.date_stamp($r['waktu']).'</span></td></tr>';	
	
	}
	echo'</table>';
}

}

endif;