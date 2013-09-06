<?php 
/**
 * @fileName: functions-old.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;

function get_gravatar( $email, $default = '', $s = 50, $d = 'mm', $r = 'g', $img = false, $atts = array()) {
	
	$set_avatar = get_option('avatar_default');
	if($set_avatar!=$default && empty($default)) $default = $set_avatar;
	
	if($default=='blank'):
	$out = site_url('/libs/img/blank.gif');
	else:
	
	$email_hash = md5( strtolower( $email ) );
	if($img){
		$host = 'https://secure.gravatar.com';
	}else{
	if ( !empty($email) ){
		$host = sprintf( "http://%d.gravatar.com", ( hexdec( $email_hash{0} ) % 2 ) );
	}else{
		$host = 'http://0.gravatar.com';
	}
	$out =$host.'/avatar/s='.$s;
	}
	if ( 'mystery' == $default )
		$default = "$host/avatar/ad516503a11cd5ca435acc9bb6523536?s=".$s; 
		// ad516503a11cd5ca435acc9bb6523536 == md5('unknown@gravatar.com')
	elseif ( !empty($email) && 'gravatar_default' == $default )
		$default = '';
	elseif ( 'gravatar_default' == $default )
		$default = $host."/avatar/s=".$s;
	elseif ( empty($email) )
		$default = $host."/avatar/?d=".$default."&amp;s=".$s;
		
	if ( !empty($email) ) {
		$out  = $host."/avatar/";
		$out .= $email_hash;
		$out .= '?s='.$s;
		$out .= '&amp;d='.urlencode($default);
	}
	endif;
	return $out;
}

function random( $min = 0, $max = 0 ){
	$rnd_value 	= md5( uniqid(microtime() . mt_rand(), true ));
	$rnd_value .= sha1($rnd_value);
	$value 		= substr($rnd_value, 0, 8);
	$value 		= abs(hexdec($value));
	if ( $max  != 0 )
		$value 	= $min + (($max - $min + 1) * ($value / (4294967295 + 1)));
	return abs(intval($value));
}

function random_password($length = 12, $special_chars = false, $extra_special_chars = false){
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	if ( $special_chars )
		$chars .= '!@#$%^&*()';
	if ( $extra_special_chars )
		$chars .= '-_ []{}<>~`+=,.;:/?|';

	$password = '';
	for ( $i = 0; $i < $length; $i++ ) {
		$password .= substr($chars, random(0, strlen($chars) - 1), 1);
	}
	return $password;
}

function has_password($chars){
	return md5($chars);
}

function mail_send($email, $subject, $message, $v = 2) {
		
	$smail   = get_option('admin_email');
	$email   = filter_txt( $email 	);
	$subject = filter_txt( $subject );
		
	if( file_exists( libs_path . 'class-mail.php' ) && !class_exists('Simple_Mail') && $v == 2 ){
		include libs_path . 'class-mail.php';
		
		$mailer = new Simple_Mail();
		$send	= $mailer->setTo($email, $email)
				 ->setSubject($subject)
				 ->setFrom($smail, $smail)
				 ->addMailHeader('Reply-To', $smail, 'Sender')
				 ->addGenericHeader('X-Mailer', 'PHP/' . phpversion())
				 ->addGenericHeader('Content-Type', 'text/html; charset="utf-8"')
				 ->setMessage($message)
				 ->setWrap(300)
				 ->send();
		return ($send) ? true : false;
	}else{
		$headers = "MIME-Version: 1.0\n"
		."Content-Type: text/html; charset=utf-8\n"
		."Reply-To: \"$smail\" <$smail>\n"
		."From: \"$smail\" <$smail>\n"
		."Return-Path: <$smail>\n"
		."X-Priority: 1\n"
		."X-Mailer: Mailer\n";
		
		if( mail($email, $subject, $message, $headers) ) 
			return true;
	}
}

function valid_url($url) {
   return preg_match("/(((ht|f)tps*:\/\/)*)((([a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3}))|(([0-9]{1,3}\.){3}([0-9]{1,3})))((\/|\?)[a-z0-9~#%&'_\+=:\?\.-]*)*)$/", $url);
}

function valid_mail($mail) {
	// checks email address for correct pattern
	// simple: 	"/^[-_a-z0-9]+(\.[-_a-z0-9]+)*@[-a-z0-9]+(\.[-a-z0-9]+)*\.[a-z]{2,6}$/i"
	$r = 0;
	if($mail) {
		$p  =	"/^[-_a-z0-9]+(\.[-_a-z0-9]+)*@[-a-z0-9]+(\.[-a-z0-9]+)*\.(";
		// TLD  (01-30-2004)
		$p .=	"com|edu|gov|int|mil|net|org|aero|biz|coop|info|museum|name|pro|arpa";
		// ccTLD (01-30-2004)
		$p .=	"ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|az|ba|bb|bd|";
		$p .=	"be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|";
		$p .=	"cg|ch|ci|ck|cl|cm|cn|co|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|";
		$p .=	"ec|ee|eg|eh|er|es|et|fi|fj|fk|fm|fo|fr|ga|gd|ge|gf|gg|gh|gi|";
		$p .=	"gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|";
		$p .=	"im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|";
		$p .=	"ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mk|ml|";
		$p .=	"mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|";
		$p .=	"nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|";
		$p .=	"py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|";
		$p .=	"sr|st|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|";
		$p .=	"tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|";
		$p .=	"za|zm|zw";
		$p .=	")$/i";

		$r = preg_match($p, $mail) ? 1 : 0;
	}
	return $r;
}

function hash_image($thumb){
	$option_image_allaw = get_option('image_allaw');
	$image_allaw = (array) json_decode($option_image_allaw);
	if ( in_array($thumb['type'],array_keys( $image_allaw )) ):
	
	$finame = preg_replace('~[^a-zA-Z0-9._-]+~', '', $thumb['name']);	
	$thumb['name']	 	= date('Ymdhis').'@'.$finame;
	$thumb['type']	 	= $thumb['type'];
	$thumb['tmp_name']	= $thumb['tmp_name'];
	
	return $thumb;
	endif;
	
}

function upload_img_post($thumb,$uploadDir = '', $resize = 650, $quality = 120){
		if(!empty($thumb['name'])):
		
		$option_image_allaw = get_option('image_allaw');
		$image_allaw = (array) json_decode($option_image_allaw);
			
		$myfile 	 = $thumb; //image name
		$uploadDir 	 = upload_path . '/' . $uploadDir; //directory upload file
		$data_upload = compact('myfile','uploadDir');
			
		if( function_exists('uploader') ) :
		if( in_array($thumb['type'],array_keys($image_allaw)) ):
			
		//upload file function
		if( uploader($data_upload) ):
			
		$path 	 = $uploadDir . '/' . $thumb['name']; // dir & name path image for upload
		$type 	 = $image_allaw[$thumb['type']]; //type image is allow
			
		$data_resize = compact('path','type','resize','quality');
		//resize if file is image allaw function
		if(function_exists('resize_image'))
			resize_image($data_resize);
		endif;
		endif;
		endif;
		endif;
}

function uploader($data){
	extract($data, EXTR_SKIP);
		
	$myfile_name = $myfile['name'];
	$myfile_temp = $myfile['tmp_name'];
	$uploadFile  = $uploadDir . basename($myfile_name);
		
    if (move_uploaded_file($myfile_temp, $uploadFile)) {
    	echo '<div id="success">File successfully uploaded!</div>';
		return true;
    } else {
			$msg = array();
            switch ($myfile['error']) {
                case 1:
                    $msg[]= 'The file is bigger than this PHP installation allows';
                    break;
                case 2:
                    $msg[]= 'The file is bigger than this form allows';
                    break;
                case 3:
                    $msg[]= 'Only part of the file was uploaded';
                    break;
                case 4:
                    $msg[]= 'No file was uploaded';
                    break;
                default:
                    $msg[]= 'unknown error';
            }
			
			if( is_array($msg))	{
				foreach($msg as $val) echo '<div id="error">'.$val.' </div>';
			}
	}
}

function delete_img_post($file, $path = '/' ){
		
		$path = upload_path . $path;
		if( file_exists( $path . $file ) )
			unlink($path.$file);
}

function resize_image($data){
		
	$path  		= filter_txt( $data['path'] );
	$type   	= filter_txt( $data['type'] );
	$resize 	= filter_int( $data['resize'] );
	$quality 	= filter_int( $data['quality'] );
		
	if(!file_exists($path) && !empty($path)) return false;
	else
	{
		if($type=='.jpg' || $type=='.jpeg') $im_src = imagecreatefromjpeg($path);
		elseif($type=='.gif') $im_src = imagecreatefromgif($path);
		elseif($type=='.png') $im_src = imagecreatefrompng($path);
		else return false;
				
		$src_width 	= imagesx($im_src);
		$src_height = imagesy($im_src);
			
		if($src_width < $resize || $src_height < $resize ){
			$dst_width = $src_width;
		}
		else $dst_width = $resize;
			
		$dst_height = ($dst_width/$src_width)*$src_height;			
		$im = imagecreatetruecolor($dst_width,$dst_height);
		imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
			
			
		//header('Expires: Mon, 26 Jul 1997 05:00:00'); 
		//header('Cache-Control: no-store, no-cache, must-revalidate'); 
		//header('Cache-Control: post-check=0, pre-check=0', FALSE); 
		//header('Pragma: no-cache');
			
		if($type=='.jpg' || $type=='.jpeg') imagejpeg($im,$path,$quality);
		elseif($type=='.gif') imagegif($im,$path);
		elseif($type=='.png') imagepng($im,$path);
		else return false;
			  
		imagedestroy($im_src);
		imagedestroy($im);
	}
}

function uc_first($str){
    $str[0] = strtr($str,
    "abcdefghijklmnopqrstuvwxyz".
    "\x9C\x9A\xE0\xE1\xE2\xE3".
    "\xE4\xE5\xE6\xE7\xE8\xE9".
    "\xEA\xEB\xEC\xED\xEE\xEF".
    "\xF0\xF1\xF2\xF3\xF4\xF5".
    "\xF6\xF8\xF9\xFA\xFB\xFC".
    "\xFD\xFE\xFF",
    "ABCDEFGHIJKLMNOPQRSTUVWXYZ".
    "\x8C\x8A\xC0\xC1\xC2\xC3\xC4".
    "\xC5\xC6\xC7\xC8\xC9\xCA\xCB".
    "\xCC\xCD\xCE\xCF\xD0\xD1\xD2".
    "\xD3\xD4\xD5\xD6\xD8\xD9\xDA".
    "\xDB\xDC\xDD\xDE\x9F");
    return $str;
}

function get_content( $url )
{
	if(function_exists('curl_init')) {
    $ch = curl_init();

    curl_setopt ($ch, CURLOPT_URL, $url );
    curl_setopt ($ch, CURLOPT_HEADER, 0);

    ob_start();

    curl_exec ($ch);
    curl_close ($ch);
    $string = ob_get_contents();

    ob_end_clean();
     
	}
	elseif(function_exists('file_get_contents')) {
		$string = file_get_contents($url);
	}
	else
	{
		$fh = fopen($url, 'r');
		while(!feof($fh))	{
			$string .= fread($fh, 4096);
		}
	}
		
    return $string; 
}

function nl2br2($string) {
	$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
	return $string;
}

function alert($msg) {
	return '<script type="text/javascript">alert("'.$msg.'");</script>';
}

function js_redirec_list() {
	return '<script language="javascript">function redir(mylist){ if (newurl=mylist.options[mylist.selectedIndex].value)
document.location=newurl;}</script>';
}

function hash_files($file){
	$option_file_allaw = get_option('file_allaw');
	$file_allaw = (array) json_decode($option_file_allaw);
	
	if ( in_array(end(explode(".",  strtolower($file['name']))), $file_allaw) ):
	
	$finame = filter_clear($file['name']);
	$finame = str_replace(' ', '_', $finame);
	$finame = str_replace('@', '', $finame);
	
	$file['name']	 	= date('Ymdhis').'@'.$finame;
	$file['type']	 	= $file['type'];
	$file['tmp_name']	= $file['tmp_name'];
	
	return $file;
	endif;
	
}

function get_filename_at( $name ){
	$name = end(explode("@",$name));	
	return $name;
}

function crop_image( $src = false, $width = 80, $height = 80, $crop = 0, $quality = 150 ){
	
	if( isset( $_GET['src'] ) ) $src = $src . $_GET['src'];
	if( isset( $_GET['x'] ) ) $width = filter_txt( $_GET['x'] );
	if( isset( $_GET['y'] ) ) $height = filter_txt( $_GET['y'] );
	if( isset( $_GET['c'] ) ) $crop = filter_txt( $_GET['c'] );	
	
	if( 0 == file_exists( $src ) )
		return false; 
		
	if(empty($width) && empty($height))
	return false;
	
	if(!list($w, $h) = getimagesize($src)) return "Tipe gambar tidak mendukung!";
	
	$type = strtolower(substr(strrchr($src,"."),1));
	if($type == 'jpeg') $type = 'jpg';
	switch($type){
	case 'bmp': $img = imagecreatefromwbmp($src); break;
	case 'gif': $img = imagecreatefromgif($src); break;
	case 'jpg': $img = imagecreatefromjpeg($src); break;
	case 'png': $img = imagecreatefrompng($src); break;
	default : return "Tipe gambar tidak mendukung!";
	}
	
	// resize
	if($crop){
	if($w < $width or $h < $height) return "Gambar terlalu kecil!";
		$ratio = max($width/$w, $height/$h);
		$h = $height / $ratio;
		$x = ($w - $width / $ratio) / 2;
		$w = $width / $ratio;
	}
	else{
		if($w < $width and $h < $height) return "Gambar terlalu kecil!";
		$ratio = min($width/$w, $height/$h);
		$width = $w * $ratio;
		$height = $h * $ratio;
		$x = 0;
	}
	
	$new = imagecreatetruecolor($width, $height);
	
	  // preserve transparency
	  if($type == "gif" or $type == "png"){
		imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
		imagealphablending($new, false);
		imagesavealpha($new, true);
	  }
	
	  imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);
	
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
		header('Cache-Control: no-store, no-cache, must-revalidate'); 
		header('Cache-Control: post-check=0, pre-check=0', FALSE); 
		header('Pragma: no-cache');
	
	  switch($type){
		case 'bmp': 
		header('Content-type: image/bmp');
		imagewbmp($new); 
		imagedestroy($new);
		break;
		case 'gif': 
		header('Content-type: image/gif');
		imagegif($new); 
		imagedestroy($new);
		break;
		case 'jpg': 
		header('Content-type: image/jpeg');
		imagejpeg($new,null,$quality); 
		imagedestroy($new);
		break;
		case 'png': 
		header('Content-type: image/png');
		imagepng($new); 
		imagedestroy($new);
		break;
	  }
	return true;
}