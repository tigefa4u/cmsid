<?php 
/**
 * @fileName: class-stats.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;

/**
 * memanggil statistik browser
 */
function _get_stat_browse($param){
	global $db;
	
	
	if( empty($param) )
		return false;
	
	$title	= esc_sql($param);
	
	$qry 	= $db->select('stat_browse',compact('title'));
	return $db->fetch_array($qry);
	
}
/**
 * memperbaharui statistik browser
 */
function _set_stat_browse($title,$data){
	global $db;
	
	if( is_array($data) && !empty($title) )
		$db->update('stat_browse',$data,compact('title'));	
}

/**
 * membuat statistik kota
 */
function set_country_stat(){
	global $country_geoip;
	$data 		= _get_stat_browse('country');
	if($data['option'] == '' || $data['hits'] == ''){
		
	$datas = array('option'=>$country_geoip.'#','hits'=>'1#');
	_set_stat_browse('country',$datas);
		
	}else{
		$get_opt	= explode("#", $data["option"]);	
		$get_hit 	= explode("#", $data["hits"]);	
		
		$hit 		= $opt = array();
		$hits 		= $option = '';
		$upd  		= true;
		foreach($get_opt as $key => $val){
			if($country_geoip == $val) 
			{
				$optx = $get_opt[$key];
				$hitx = $get_hit[$key]+1;
				$upd  = false;
			}
			else 
			{
				$optx = $get_opt[$key];
				$hitx = $get_hit[$key];
			}
			
			$opt[] = $optx;
			$hit[] = $hitx;
		}
		
		if($upd)
		{
			$opt[] = $country_geoip;
			$hit[] = 1;
		}
		
		foreach($opt as $k=>$v)
		{
			if(!empty($v) && !empty($hit[$k])){
				$option	.= $v.'#';
				$hits	.= $hit[$k].'#';
			}
		}
		
		if(!empty($option) && !empty($hits))
		_set_stat_browse('country',array('option'=>$option,'hits'=>$hits));
	}
	
}
add_action( 'the_head', 'set_country_stat' );
add_action( 'the_head_login', 'set_country_stat' );
add_action( 'the_head_request', 'set_country_stat' );
/**
 * membuat statistik situs
 */
function set_stats(){
	$stats = new stats;
	
	if( !is_admin() && !is_login() )
		$stats->update();
}
add_action( 'the_head', 'set_stats' );
/**
 * memangil statistik situs berdasarkan kata kunci
 */
function get_stats( $switch ){
	$stats = new stats;
	
	if( $switch == 'now' ) return $stats->start('now');
	elseif( $switch == 'day' ) return $stats->start('day');
	elseif( $switch == 'month' ) return $stats->start('month');
	elseif( $switch == 'hits' ) return $stats->start('hits');
	elseif( $switch == 'visitor' ) return $stats->start('visitor');
	return false;
}

class stats
{	
	function start($stat){
		global $db;
		//count
		$q				= $db->select('stat_count',array('id'=>1));
		while ( $data 	= $db->fetch_array($q) ) {
		$visitor		= $data[2];
		$hits			= $data[3];
		}		
		if($stat	   == 'now'){
		$q				= $db->select("stat_online");
		return $db->num($q);
		}elseif($stat  == 'day'){
		$q				= $db->select("stat_onlineday");
		return $db->num($q);
		}elseif($stat  == 'month'){
		$q				= $db->select("stat_onlinemonth");
		return $db->num($q);
		}elseif($stat  == 'hits'){
		return $hits;
		}elseif($stat  == 'visitor'){
		return $visitor;
		}else{
		return 0;
		}
	}
	function updateon($time){
		global $db;
		
		if (preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', getenv("HTTP_X_FORWARDED_FOR")) == ''){
		$uipanda = getenv('REMOTE_ADDR');
		}else{
		$uipanda = getenv('HTTP_X_FORWARDED_FOR');
		}
		$uproxyserver	= getenv("HTTP_VIA");
		$uipproxy		= getenv("REMOTE_ADDR");
		$uhost			= gethostbyaddr($uipproxy);
		$utime			= time();
		if($time=='now'){			
			$now	= $utime-600;
			
					  $db->query("delete from $db->stat_online where timevisit<$now");
			$q		= $db->select('stat_online',array('ipproxy'=>$uipproxy));
			$uexists= $db->num($q);
			
			if ($uexists>0){
				$db->update('stat_online', array('timevisit'=>$utime), array('ipproxy'=>$uipproxy) );
			} else {
				$ipproxy 		= esc_sql($uipproxy);
				$host 			= esc_sql($uhost);
				$ipanda 		= esc_sql($uipanda);
				$proxyserver 	= esc_sql($uproxyserver);
				$timevisit 		= esc_sql($utime);
				
				$data = compact('ipproxy','host','ipanda','proxyserver','timevisit');
				$db->insert('stat_online', $data);
			}
		}elseif($time=='day'){
			$day	= $utime-86400;	
			
					  $db->query("delete from $db->stat_onlineday where timevisit<$day");
			$q		= $db->select('stat_onlineday',array('ipproxy'=>$uipproxy));
			$uexists= $db->num($q);	
			
			if ($uexists>0){ 
				$db->update('stat_onlineday', array('timevisit'=>$utime), array('ipproxy'=>$uipproxy) );
			} else {
				$ipproxy 		= esc_sql($uipproxy);
				$host 			= esc_sql($uhost);
				$ipanda 		= esc_sql($uipanda);
				$proxyserver 	= esc_sql($uproxyserver);
				$timevisit 		= esc_sql($utime);
				
				$data = compact('ipproxy','host','ipanda','proxyserver','timevisit');
				$db->insert('stat_onlineday', $data);
			}
		}elseif($time=='month'){
			$month	= $utime-2592000; // (in seconds)
			
					  $db->query("delete from $db->stat_onlinemonth where timevisit<$month");
			$q		= $db->query("select id from $db->stat_onlinemonth where ipproxy='$uipproxy'");
			$uexists= $db->num($q);	
			
			if ($uexists>0){
				$db->update('stat_onlinemonth', array('timevisit'=>$utime), array('ipproxy'=>$uipproxy) );
			} else {
				$ipproxy 		= esc_sql($uipproxy);
				$host 			= esc_sql($uhost);
				$ipanda 		= esc_sql($uipanda);
				$proxyserver 	= esc_sql($uproxyserver);
				$timevisit 		= esc_sql($utime);
				
				$data = compact('ipproxy','host','ipanda','proxyserver','timevisit');
				$db->insert('stat_onlinemonth', $data);
			}
		}else{
		}
	}
	function update(){
		global $db;
		
		$IPnum 			= "0.0.0.0";
		$userStatus 	= 0;
		$maxadmindata 	= 20;
		$IPnum 			= getenv("REMOTE_ADDR");
		$q 				= $db->select('stat_count',array('id'=>1));
		$total 			= $db->num($q);
		if ($total     <= 0){
			$id 		= 1;
			$ip 		= esc_sql($IPnum);
			$counter 	= 1;
			$hits 		= 1;
			
			$data = compact('id','ip','counter','hits');
			$db->insert('stat_count', $data);
		}
		while ( $data 	= $db->fetch_array($q) ) {
		$IPdata			= $data[1];
		$theCount		= $data[2];
		$hits			= $data[3];
		}
		$IParray 		= explode("-",$IPdata);
		$ipCountMax		= count($IParray);
		for($ipCount=0;$ipCount<$ipCountMax;$ipCount++){	
			if($IParray[$ipCount]==$IPnum){
				$userStatus = 1;       
			}
		}
		$IPdata			= '';	
		
		if($userStatus == 0){
		$IPdata			="$IPnum-";
		for ($i=0; $i<$maxadmindata; $i++){
		$IPdata 	   .= "$IParray[$i]-";		
		}
		$theCount++;		
			$ip 		= esc_sql($IPdata);			
			$counter	= esc_sql($theCount);			
			
			$data = compact('id','counter');
			$db->update('stat_count', $data,array('id'=>1));
		}				
		$hits++;		
			$hits	= esc_sql($hits);			
			$db->update('stat_count', compact('hits'),array('id'=>1));
			
			$this->updateon('now');
			$this->updateon('day');
			$this->updateon('month');
	}
}
/**
 * membuat sandi untuk utf 8
 */
function _utf8_encode( $_str ) {
	$encoding = mb_detect_encoding( $_str );
	if ( $encoding == false || strtoupper( $encoding ) == 'UTF-8' || strtoupper( $encoding ) == 'ASCII' ) {
		return $_str;
	} else {
			return iconv( $encoding, 'UTF-8', $_str );
	}
}
/**
 * menentukan pencarian
 */
function determine_search_terms( $_url ) {
	
	if ( !is_array( $_url ) )
	$_url = parse_url( $_url );
		
	$search_terms = '';
		
	if ( isset( $_url['host'] ) && isset( $_url['query'] ) ) {
		$sniffs = array( // host regexp, query portion containing search terms, parameterised url to decode
			array( "/images\.google\./i", 'q', 'prev' ),
			array( "/google\./i", 'q' ),
			array( "/\.bing\./i", 'q' ),
			array( "/alltheweb\./i", 'q' ),
			array( "/yahoo\./i", 'p' ),
			array( "/search\.aol\./i", 'query' ),
			array( "/search\.cs\./i", 'query' ),
			array( "/search\.netscape\./i", 'query' ),
			array( "/hotbot\./i", 'query' ),
			array( "/search\.msn\./i", 'q' ),
			array( "/altavista\./i", 'q' ),
			array( "/web\.ask\./i", 'q' ),
			array( "/search\.wanadoo\./i", 'q' ),
			array( "/www\.bbc\./i", 'q' ),
			array( "/tesco\.net/i", 'q' ),
			array( "/yandex\./i", 'text' ),
			array( "/rambler\./i", 'words' ),
			array( "/aport\./i", 'r' ),
			array( "/.*/", 'query' ),
			array( "/.*/", 'q' )
		);
			
		foreach ( $sniffs as $sniff ) {
			if ( preg_match( $sniff[0], $_url['host'] ) ) {
				parse_str( $_url['query'], $q );
					
				if ( isset( $sniff[2] ) && array_key_exists( $sniff[2], $q ) ) {
					$decoded_url = parse_url( $q[ $sniff[2] ] );
					if ( array_key_exists( 'query', $decoded_url ) ) {
						parse_str( $decoded_url['query'], $q );
					}
				}
					
				if ( isset( $q[ $sniff[1] ] ) ) {
					$search_terms = trim( stripslashes( $q[ $sniff[1] ] ) );
					break;
				}
			}
		}
	}
		
	return $search_terms;
}
/**
 * mencari nama domain
 */
function get_domain_name($string){
	$url 		= parse_url( $string );
	$string 	= mb_substr( _utf8_encode( $string ), 0, 255 );
	
	$data   	= ( isset( $url['host'] ) ) ? mb_eregi_replace( '^www.', '', $url['host'] ) : '';
	$data   	= mb_substr( $data, 0, 255 );
	
	if ( mb_strlen( $data ) >= 25 && 
		( !isset( $_SERVER['SERVER_NAME'] ) || 
		$data != mb_eregi_replace( '^www.', '', $_SERVER['SERVER_NAME'] ) ) ) {
		return;
	}
	return $data;
}

/**
 * memanggil browser dari daftar
 */
function get_browsers($user_agent){
	$browsers = array(
		0 	=> 'Opera', //Opera
		1	=> '(Firebird)|(Firefox)', //Mozilla Firefox
		2 	=> 'Galeon', //Galeon
		3	=> 'Gecko', //Mozilla, Crome
		4	=> 'MyIE', //MyIE
		5	=> 'Lynx', //Lynx
		6	=> '(Mozilla/4\.75)|(Netscape6)|(Mozilla/4\.08)|(Mozilla/4\.5)|(Mozilla/4\.6)|(Mozilla/4\.79)', //Netscape
		7	=> 'Konqueror', //Konqueror
		8 	=> '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)', //SearchBot
		9 	=> '(MSIE 6\.[0-9]+)', //IE 6
		10 	=> '(MSIE 7\.[0-9]+)', //IE 7
		11 	=> '(MSIE 8\.[0-9]+)', //IE 8
		12 	=> '(MSIE 9\.[0-9]+)', //IE 9
		13 	=> '(MSIE 10\.[0-9]+)', //IE 10
	);

	foreach($browsers as $browser=>$pattern){
		if (eregi($pattern, $user_agent))
			return $browser;
	}
	return 14;
}
/**
 * memanggil os dari daftar
 */
function get_os($user_agent){
	$oss = array(
		0 	=> 'Win', //Windows Microsoft
		1	=> '(Mac)|(PPC)', //Apple Macintosh
		2 	=> 'Linux', //Linux
		3	=> 'FreeBSD', //FreeBSD
		4	=> 'SunOS', //SunOS
		5	=> 'IRIX', //IRIX
		6	=> 'BeOS', //BeOS
		7	=> 'OS/2', //OS/2
		8 	=> 'AIX', //AIX	
	);

	foreach($oss as $os=>$pattern){
		if (eregi($pattern, $user_agent))
			return $os;
	}
	return 9;
}
/**
 * memperbaharui hits browser
 */
function hits_browser( $title, $value ){
	global $db;
	$title	= esc_sql($title);
	
	$qry 	= $db->select('stat_browse',compact('title'));
	$data 	= $db->fetch_array($qry);
	
	
	$tmp	= explode("#", $data["hits"]);
	$tot 	= count($tmp);
	$hits 	= '';
	$tmp[$value]++;
	for($i=0;$i<$tot;$i++) $hits.= $tmp[$i] . "#";	
	$hits 	= substr_replace($hits, "", -1, 1);
	
	$db->update('stat_browse',compact('hits'),compact('title'));
}

/**
 * mengeset statistik browser
 */
function set_stats_browser(){		
	$time 		= 0;
	$jam 		= date('G', time() + $time);
	$bulan 		= date('m', time() + $time);
	$hari		= date('w', time() + $time);
	$tanggal 	= date('d', time() + $time);
	
	$get_agent	= getenv( "HTTP_USER_AGENT" );
	$os 		= get_os( $get_agent );
	$browser 	= get_browsers( $get_agent );
	
	if( !$_SESSION['visitor_browse'] ):

	hits_browser('browser', $browser);
	hits_browser('os', $os);	
	hits_browser('day', $hari);
	hits_browser('month', $bulan - 1);
	hits_browser('clock', $jam);
	
	$_SESSION['visitor_browse'] = true;   
	endif;
}
add_action( 'the_head', 'set_stats_browser' );