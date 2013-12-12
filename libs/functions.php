<?php 
/**
 * @fileName: functions.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;

function redirect( $url = null ){	
	$base_url = site_url( $url );
	
    if (!headers_sent()){ 
        header('Location: '.$base_url); exit;
    }else{ 
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$base_url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$base_url.'" />';
        echo '</noscript>'; 
		return;
		exit;
    }
}

function array_merge_simple( $array1, $array2 ){
	$merged = array();
	
	if( !empty($array1) )
	foreach ( $array1 as $key => $value ){
		$merged [$key] = $value;
	}
	  
	if( !empty($array2) )
	foreach ( $array2 as $key => $value ){
		$merged [$key] = $value;
	}
	
	return $merged;
}

function object_merge_simple( $array1, $array2 ){
	$merged = array();
	
	if( !empty($array1) )
	foreach ( $array1 as $key => $value ){
		$merged[$key] = $value;
	}
	  
	if( !empty($array2) )
	foreach ( $array2 as $key => $value ){
		$merged[$key] = $value;
	}
	
	return (object) $merged;
}
/**
 * Mengetahui protokol
 *
 * @return true|false
 */
function is_ssl() {
	if ( isset($_SERVER['HTTPS']) ) {
		if ( 'on' == strtolower($_SERVER['HTTPS']) )
			return true;
		if ( '1' == $_SERVER['HTTPS'] )
			return true;
	} elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
		return true;
	}
	return false;
}
/**
 * Menghapus direktori folder
 *
 * @param string $dirname
 * @return true|false
 */
function delete_directory($dirname) {
   if(is_dir($dirname))
      $dir_handle = opendir($dirname);
   if(!isset($dir_handle))
      return false;
   while($file = readdir($dir_handle)) {
      if ($file != "." && $file != "..") {
         if (!is_dir($dirname."/".$file))
            unlink($dirname."/".$file);
         else
            delete_directory($dirname.'/'.$file);       
      }
   }
   closedir($dir_handle);
   rmdir($dirname);
   return true;
}
/**
 * Memvalidasi berkas
 *
 * @param string $file
 * @param array $allowed_files
 * @return int
 */
function validate_file( $file, $allowed_files = '' ) {
	if ( false !== strpos( $file, '..' ) )
		return 1;

	if ( false !== strpos( $file, './' ) )
		return 1;

	if ( ! empty( $allowed_files ) && ! in_array( $file, $allowed_files ) )
		return 3;

	if (':' == substr( $file, 1, 1 ) )
		return 2;

	return 0;
}
/**
 * Gets the current locale.
 *
 * @return string
 */
function get_locale() {
	global $locale;
	
	if ( empty( $locale ) )
		$locale = 'en_US';

	return apply_filters( 'locale', $locale );
}

function get_option_array_widget(){
	$get_option_array_widget = get_option('sidebar_widgets');
	$get_option_array_widget = esc_sql( $get_option_array_widget );
	$get_option_array_widget = json_decode( $get_option_array_widget );
	return $get_option_array_widget;
}

/**
 * Retrieve or display list of pages in list (li) format.
 *
 * @since 1.5.0
 *
 * @param array|string $args Optional. Override default arguments.
 * @return string HTML content, if not displaying.
 */
function list_pages($args = '') {
	
	$defaults = array(
		'title_li' => 'Pages', 
		'echo' => 0,
		'sort_column' => 'menu_order, post_title',
		'link_before' => '', 
		'link_after' => ''
	);

	$r = parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	$output = '';
	$current_page = 0;
	
	global $db;
	$sql = $db->select( 'post', array('type' => 'page', 'approved' => 1, 'status' => 1) );
	$pages = $db->num( $sql );

	if ( !empty($pages) ) {
		if ( $r['title_li'] )
			$output .= '<li class="pagenav">' . $r['title_li'] . '<ul>';
		
		while( $page = $db->fetch_obj( $sql ) ):
		$dPages	= array('id'=>$page->id,'title'=>$page->title);
		$output .= '<li class="page_item page-item-2"><a href="'.do_links('page',$dPages).'" title="About">'.$page->title.'</a></li>';
		endwhile;

		if ( $r['title_li'] )
			$output .= '</ul></li>';
	}

	$output = apply_filters('list_pages', $output, $r);

	if ( $r['echo'] )
		echo $output;
	else
		return $output;
}

function dateformat($str,$format = null){
	$str = strtotime($str);
	return date("Y/m/d",$str);
	/*
	$today = date("F j, Y, g:i a");                 // March 10, 2001, 5:16 pm
	$today = date("m.d.y");                         // 03.10.01
	$today = date("j, n, Y");                       // 10, 3, 2001
	$today = date("Ymd");                           // 20010310
	$today = date('h-i-s, j-m-y, it is w Day');     // 05-16-18, 10-03-01, 1631 1618 6 Satpm01
	$today = date('\i\t \i\s \t\h\e jS \d\a\y.');   // it is the 10th day.
	$today = date("D M j G:i:s T Y");               // Sat Mar 10 17:16:18 MST 2001
	$today = date('H:m:s \m \i\s\ \m\o\n\t\h');     // 17:03:18 m is month
	$today = date("H:i:s");                         // 17:16:18
	*/
}

function date_times( $tgl, $jam = true, $hari = true ){
	/*thanks code date from aura
	/*Contoh Format : 2007-08-15 01:27:45*/
	$tanggal = strtotime($tgl);
	$bln_array = array (
		'01'=>'January',
		'02'=>'February',
		'03'=>'Mart',
		'04'=>'April',
		'05'=>'Mey',
		'06'=>'Juny',
		'07'=>'July',
		'08'=>'August',
		'09'=>'September',
		'10'=>'Octtober',
		'11'=>'Nopvemer',
		'12'=>'December'
				);
	$hari_arr = array (	
		'0'=>'Minggu',
		'1'=>'Senin',
		'2'=>'Selasa',
		'3'=>'Rabu',
		'4'=>'Kamis',
		'5'=>'Jum\'at',
		'6'=>'Sabtu'
		);
	$hari 	= $hari ? @$hari_arr[date('w',$tanggal)] : '';
	$tggl 	= date('j',$tanggal);
	$tgl 	= date('d',$tanggal);
	$bln 	= @$bln_array[date('m',$tanggal)];
	$thn 	= date('Y',$tanggal);
	$jam 	= $jam ? date ('H:i:s',$tanggal) : '';
	
	return array('hari' => $hari,'tggl' => $tggl,'tgl' => $tgl,'bln' => $bln,'thn' => $hari,'thn' => $thn,'jam' => $jam);
}

function to_monthly( $monthly ){
	$bln_array = array (
		'1' =>'01',
		'2' =>'02',
		'3' =>'03',
		'4' =>'04',
		'5' =>'05',
		'6' =>'06',
		'7' =>'07',
		'8' =>'08',
		'9' =>'09',
		'10'=>'10',
		'11'=>'11',
		'12'=>'12');
	$bln = @$bln_array[$monthly];
	return $bln;
}	

function datetimes( $tgl, $jam = true ){
	$value = date_times( $tgl, $jam );
	return "$value[hari], $value[tggl] $value[bln] $value[thn] $value[jam]";
}

function datetimes2( $tgl, $jam = true ){
	$value = date_times( $tgl, $jam );
	return "$value[thn]-$value[bln]-$value[tgl]";
}
/**
 * Gets the limit content.
 *
 * @return string limit
 */
function limittxt($nama, $limit){
    if (strlen ($nama) > $limit) {
    	$nama = substr($nama, 0, $limit) . '...';
    }else {
        $nama = $nama;
    }
	return apply_filters( 'limit_txt', $nama );
}
/**
 * Gets the sanitaze and limit content.
 *
 * @return string
 */
function initialized_text( $text, $limit = 120, $tags = false  ){
	$text = htmlentities( strip_tags($text) );
	if( $limit ) $text = limittxt( $text, $limit );
	if( $tags ) $text = empty($text) ? implode(',',explode(' ',$text)) : $text;
	return $text;
}
/**
 * Display or retrieve the HTML list of categories.
 *
 * @param string|array $args Optional. Override default arguments.
 * @return string HTML content only if 'echo' argument is 0.
 */
function list_categories( $args = '' ) {
	$defaults = array(
		'show_option_none' => 'No categories',
		'orderby' => 'id', 
		'order' => 'ASC',
		'style' => 'list',
		'title_li' => 'Categories',
		'echo' => 1,
		'taxonomy' => 'category', 
		'no_categories' => 5 
	);
	$r = parse_args( $args, $defaults );
	
	if ( !isset( $r['class'] ) )
		$r['class'] = ( 'category' == $r['taxonomy'] ) ? 'categories' : $r['taxonomy'];
		
	extract( $r );
	
	$output = '';
	if ( $title_li && 'list' == $style )
			$output = '<li class="' . esc_attr( $class ) . '">' . $title_li . '<ul>';
		
	$order = "ORDER BY $orderby $order  LIMIT $no_categories";

	global $db;
	
	$query_request = $db->select( 'post_topic', array( 'status' => 1), $order );
	$categories_count = $db->num( $query_request );	
	
	if ( empty( $categories_count ) ) {
		$output .= $show_option_none;
	} else {
		while ( $categories = $db->fetch_obj($query_request) ) {
			$permalinks = do_links('post', array('view' => 'category', 'id' => $categories->id, 'title' => $categories->topic));
			if ( 'list' == $style )
				$output .= "<li><a href='$permalinks'>$categories->topic</a></li>";
			else
				$output .= "<a href='$permalinks'>$categories->topic</a>";
		}
	}
	if ( $title_li && 'list' == $style )
		$output .= '</ul></li>';

	$output = apply_filters( 'list_categories', $output, $args );

	if ( $echo )
		echo $output;
	else
		return $output;
}
/**
 * Display archive links based on type and format.
 *
 * @param string|array $args Optional. Override defaults.
 * @return string|null String when retrieving, null when displaying.
 */
function get_archives($args = '') {
	global $db;

	$defaults = array(
		'type' => 'monthly', 'limit' => '',
		'format' => 'html', 
		'before' => '',
		'after' => '', 
		'show_post_count' => false,
		'echo' => 1, 
		'order' => 'DESC',
	);

	$r = parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	if ( '' == $type )
		$type = 'monthly';

	if ( '' != $limit ) {
		$limit = (int) $limit;
		$limit = ' LIMIT '.$limit;
	}

	$order = strtoupper( $order );
	if ( $order !== 'ASC' )
		$order = 'DESC';

	$archive_week_separator = '&#8211;';

	$archive_date_format_over_ride = 0;

	$archive_day_date_format = 'Y/m/d';

	$archive_week_start_date_format = 'Y/m/d';
	$archive_week_end_date_format	= 'Y/m/d';

	if ( !$archive_date_format_over_ride ) {
		$archive_day_date_format = get_option('date_format');
		$archive_week_start_date_format = get_option('date_format');
		$archive_week_end_date_format = get_option('date_format');
	}

	//filters
	$where = apply_filters( 'getarchives_where', "WHERE type = 'post' AND status = '1' AND approved = '1'", $r );
	$join = apply_filters( 'getarchives_join', '', $r );

	$output = '';
	if ( 'monthly' == $type ) {
		$query = "SELECT YEAR(date_post) AS `year`, MONTH(date_post) AS `month`, count(id) as posts FROM $db->post $join $where GROUP BY YEAR(date_post), MONTH(date_post) ORDER BY date_post $order $limit";
		$arcresults = $db->query($query);
		$afterafter = $after;
		while ( $arcresult = $db->fetch_obj($arcresults) ) {
			$id = $arcresult->year.':'.to_monthly( $arcresult->month );
			
			if( get_option('rewrite') != 'advance' ) 
				$id = str_replace(':','-',$id);
			
			$url = do_links('post', array('view' => 'archive', 'id' => $id));
			/* translators: 1: month name, 2: 4-digit year */
			$text = date_times($arcresult->year.'-'.$arcresult->month,false,false);
			$text = sprintf('%1$s %2$d', $text['bln'], $arcresult->year);
			if ( $show_post_count )
				$after = '&nbsp;('.$arcresult->posts.')' . $afterafter;
			$output.= get_archives_link($url, $text, $format, $before, $after);
		}
	} elseif ( 'yearly' == $type ) {
		$query = "SELECT YEAR(date_post) AS `year`, count(id) as posts FROM $db->post $join $where GROUP BY YEAR(date_post) ORDER BY date_post $order $limit";
		//desc your function output
	} elseif ( 'daily' == $type ) {
		$query = "SELECT YEAR(date_post) AS `year`, MONTH(date_post) AS `month`, DAYOFMONTH(date_post) AS `dayofmonth`, count(id) as posts FROM $db->post $join $where GROUP BY YEAR(date_post), MONTH(date_post), DAYOFMONTH(date_post) ORDER BY date_post $order $limit";
		//desc your function output
	} elseif ( 'weekly' == $type ) {
		$query = "SELECT DISTINCT $week AS `week`, YEAR( `date_post` ) AS `yr`, DATE_FORMAT( `date_post`, '%Y-%m-%d' ) AS `yyyymmdd`, count( `id` ) AS `posts` FROM `$db->post` $join $where GROUP BY `date_post`, YEAR( `date_post` ) ORDER BY `date_post` $order $limit";
		//desc your function output
	} elseif ( ( 'postbypost' == $type ) || ('alpha' == $type) ) {
		$orderby = ('alpha' == $type) ? 'title ASC ' : 'date_post DESC ';
		$query = "SELECT * FROM $db->post $join $where ORDER BY $orderby $limit";
		//desc your function output
	}
	if ( $echo )
		echo $output;
	else
		return $output;
}

/**
 * Retrieve archive link content based on predefined or custom code.
 *
 * @param string $url URL to archive.
 * @param string $text Archive text description.
 * @param string $format Optional, default is 'html'. Can be 'link', 'option', 'html', or custom.
 * @param string $before Optional.
 * @param string $after Optional.
 * @return string HTML link content for archive.
 */
function get_archives_link($url, $text, $format = 'html', $before = '', $after = '') {

	if ('link' == $format)
		$link_html = "\t<link rel='archives' title='$title_text' href='$url' />\n";
	elseif ('option' == $format)
		$link_html = "\t<option value='$url'>$before $text $after</option>\n";
	elseif ('html' == $format)
		$link_html = "\t<li>$before<a href='$url' title='$title_text'>$text</a>$after</li>\n";
	else // custom
		$link_html = "\t$before<a href='$url' title='$title_text'>$text</a>$after\n";

	$link_html = apply_filters( 'get_archives_link', $link_html );

	return $link_html;
}


function get_posted_id( $act, $id ){
	global $db;
		
	$engine =  new engine;		
	$o = get_option('rewrite');
		
	if( $o == 'slash' || $o == 'slash-clear'  || $o == 'clear' ):
		$selftitle = filter_clear( $id );
		$selftitle = filter_txt( $selftitle );
		$selftitle = esc_sql( $selftitle );
		
	if( $act == 'page' || $act == 'item' )
		$query = $db->select( "post" );
	elseif( $act == 'category' )
		$query = $db->select( "post_topic" );
	
	
	while($data = $db->fetch_array($query)){
		if( $act == 'page' || $act == 'item' ) 
			$get_sefttitle = $data['sefttitle'];
		elseif( $act == 'category' )
			$get_sefttitle = $engine->judul( $data['topic'] );
		
		if($get_sefttitle == $selftitle){
			$id = (int) esc_sql( $data['id'] );
		}
	}
	
	endif;
	
	return $id;
}

function get_page_view( $get_query_id = false ){
	global $db, $login;
	
	if(!$get_query_id )
	$get_query_id = get_query_var('id');
	
	$get_query_id = get_posted_id( 'page', $get_query_id );
	
	$id   = esc_sql( (int) $get_query_id );
	
	$id   = filter_int( $id );
		
	if( $login->check() && $login->level('admin') && !empty($id) )
	$where = array('id' => $id, 'type' => 'page');
	else
	$where = array('type' => 'page', 'id' => $id, 'approved' => 1, 'status' => 1);
	
	$sql 	= $db->select( 'post', $where );
	$post 	= $db->fetch_obj( $sql );
	return $post;
}

/**
 * Display tag cloud.
 *
 * @param array|string $args Optional. Override default arguments.
 * @return array Generated tag cloud, only if no failures and 'array' is set for the 'format' argument.
 */
function tag_cloud( $args = '' ) {
	$defaults = array(
		'smallest' => 11, 
		'largest' => 22, 
		'orderby' => 'name', 
		'order' => 'ASC',
		'taxonomy' => 'post_tag', 
		'echo' => true,
		'show_option_none' => 'No Tag Clouds'
	);
		
	$args = parse_args( $args, $defaults );
	
	$add_query = '';
	
	if( $id = get_query_var('id') ):	
		$id = get_posted_id('item', $id );
		$id = filter_int( $id );
		$id = esc_sql($id);
	
		$add_query.= ' AND id='.$id.' ';
	endif;
		
	if( $args['status'] = '' )
		$add_query.= " AND status='1' AND approved='1'";
	
	global $db;
	
	$tagresults = $db->query( "SELECT * FROM $db->post WHERE type='post' $add_query" );
	$tag_count = $db->num( $tagresults );
	
	if ( empty( $tag_count ) ) {
		$output = $show_option_none;
	} else {
		$tags = array();
		while ( $tagresult = $db->fetch_obj($tagresults) ) {
			$tags_x = explode(',',strtolower( trim($tagresult->tags) ) );
			
			foreach($tags_x as $key => $val)	{
				$tags[] = $val;	
			}
		}
	
		$output = generate_tag_cloud( $tags, $args );
		$output = apply_filters( 'tag_cloud', $output, $args );
	}

	if ( 'array' == $args['format'] || empty($args['echo']) )
		return $output;

	echo $output;
}

/**
 * Generates a tag cloud (heatmap) from provided data.
 *
 * @param array $tags List of tags.
 * @param string|array $args Optional, override default arguments.
 * @return string
 */
function generate_tag_cloud( $tags, $args = '' ) {	
	$defaults = array(
		'smallest' => 11, 
		'largest' => 22,  
		'orderby' => 'name', 
		'order' => 'ASC'
	);
	
	$args = parse_args( $args, $defaults );
	extract( $args );
	
	$output = '';
	$totalTags = count($tags);
	$jumlah_tag = array_count_values($tags);
	ksort($jumlah_tag);
	if ($totalTags > 0) {
		$tag_mod = array();
		$tag_mod['fontsize']['max'] = $largest;
		$tag_mod['fontsize']['min'] = $smallest;
		
		$min_count = min($jumlah_tag);
		$spread = max($jumlah_tag) - $min_count;
			
		if ( $spread <= 0 )
			$spread = 1;
			
		$font_spread = $tag_mod['fontsize']['max'] - $tag_mod['fontsize']['min'];
			
		if ( $font_spread <= 0 )
			$font_spread = 1;
				
		$font_step = $font_spread / $spread;
			
		foreach($jumlah_tag as $key=>$val) {
				
			$font_size = ( $tag_mod['fontsize']['min'] + ( ( $val - $min_count ) * $font_step ) );
			$datas  = array('view'=>'tags','id'=>urlencode($key));
				
			$style = '';
			if( empty($id) ) $style = "style='font-size:".$font_size."px'";
				
			$output.= '<a href="'.do_links( "post", $datas ).'" '.$style.'>'.$key .'</a>, ';
		}
	}
	
	return $output;
}
/**
 * Retrieve the tags for a post.
 *
 * @since 2.3.0
 *
 * @param string $before Optional. Before list.
 * @param string $sep Optional. Separate items using this.
 * @param string $after Optional. After list.
 * @return string
 */
function the_tags( $sep = ', ' ) {
	echo tag_cloud( array('echo' => false,'sep' => $sep) );
}
/**
 * Loads the comment template specified in $file.
 *
 * @param string $file Optional, default '/comments.php'. The file to load
 * @param bool $separate_comments Optional, whether to separate the comments by comment type. Default is false.
 * @return null Returns null if no comments appear
 */
function comments_template( $file = '/comments.php', $separate_comments = false ) {
	global $db, $id, $comment, $user_login;

	if ( empty($file) )
		$file = '/comments.php';

	if ( file_exists( template_path . $file ) )
		require( template_path . $file );
	else
		require( libs_path . '/comments.php');
}

function count_comment($id){
	global $db;
		
	$comment_results = $db->query("SELECT COUNT(comment_id) AS comment_total FROM `$db->post_comment` WHERE approved='1' AND `post_id`='$id'"); 
	$comment_result = $db->fetch_obj($comment_results);
	return $comment_result->comment_total;	
		
}

function get_status_comment($id){
	global $db;
	$id = esc_sql( filter_int($id) );
	$post_results = $db->query("SELECT * FROM $db->post WHERE id='$id'");
	$post_result = $db->fetch_obj($post_results);
	return $post_result->status_comment;
}

function get_comment_login(){
	global $login;
	return $login->check();
}
	
function get_current_comment(){	
	global $login;
	
	$user_login = $login->exist_value('username');
	$field 		= $login->data( compact('user_login') );

	return $field;
}

function comment_post( $r ){
		
	extract($r, EXTR_SKIP);
		
	if(!$author) $error .= "Nama kosong<br />";
	if(!$email) $error .= "Mail kosong<br />";
	if(!valid_mail($email)) $error .= "Format Mail salah<br />";		
	if(empty($comment)) $error .= "Isi Komentar kosong<br />"; 
	
	$user = get_current_comment();	
	
	if( $user->user_level != 'admin' )
	if($security_code_check != $security_code or !isset($security_code) ) $error .= "Code failed<br>";
	
	if( security_posted('comment_on_post', true) > 0 && $user->user_level != 'admin' )
	$error .= 'Maaf anda sudah berkomentar, silahkan tunggu beberapa menit untuk berkomentar lagi.<br>';
		
	if( $error )
	{
		echo'<div class="div_alert">'.$error.'</div>';
	}
	else
	{
		save_comment_data($r);
	}
}

function save_comment_data($r){
	global $db, $login;
	extract($r, EXTR_SKIP);
		
	$author 		= esc_sql($author);
	$user_id		= esc_sql($user);
	$email 			= esc_sql($email);
	$comment 		= esc_sql($comment);
	$date 			= date('Y-m-d H:i:s');
	$date 			= esc_sql($date);
	$approved		= esc_sql($approved);
	$comment_parent	= esc_sql($reply);
	$post_id		= esc_sql($post_id);
	$time			= time();
		
	if( get_comment_login() && $login->level('admin') ) $approved = 1;
		
	$data = compact('user_id','author','email','comment','date','time','approved','comment_parent','post_id');
	
	$waiting_comment = '';
	if($approved    != 1) $waiting_comment ='<em>Your comment is awaiting moderation.</em><br>';
		
	$user = get_current_comment();
	
	if( $user->user_level != 'admin' )
		security_posted('comment_on_post');
		
	if( $db->insert('post_comment',$data) ) 
	echo '<div class="div_info">Komentar berhasil. '.$waiting_comment.' </div>';
}

function check_ip_address( $ip ) {
	$bytes = explode('.', $ip);
	if (count($bytes) == 4 or count($bytes) == 6) {
		$returnValue = true;
		foreach ($bytes as $byte) {
			if (!(is_numeric($byte) && $byte >= 0 && $byte <= 255))
				$returnValue = false;
		}
		return $returnValue;
	}
	return false;
}

function get_ip_address(){
	$banned = array ('127.0.0.1', '192.168', '10');
	$ip_adr = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$bool = false;
	foreach ($banned as $key=>$val){
		if(!empty($ip_adr) && ereg("^$val",$ip_adr) ){
			$bool = true;
			break;
		}
	}
	
	if (empty($ip_adr) or $bool or !check_ip_address($ip_adr) ){
		$ip_adr = @$_SERVER['REMOTE_ADDR'];	
	}
	return $ip_adr; 	
}

function security_posted( $file_name = null, $cek_ip_total = false, $timer = 10 ){
	$ip_total = 0;
	$timed = time();
	$ip = get_ip_address();
	$pip_array1 = $pip_array2 = array();
	
	if( checked_option( 'security_pip' ) &&  get_option('security_pip') != '' ){
		
		$option_pip = get_option('security_pip');
		//echo 'before DELETE:'.$option_pip.'<br>';
		$option_pip = json_decode( $option_pip );
		foreach( $option_pip as $pip ){
			
			if( $pip->time > $timed )
				$pip_array1[] = array('file' => $pip->file,'ip' => $pip->ip,'time' => $pip->time);
		}
		
		$security_pip = json_encode($pip_array1);
		//echo 'after DELETE:'.$security_pip.'<br>';
		if( checked_option( 'security_pip' ) ) set_option( 'security_pip', $security_pip );
		else add_option( 'security_pip', $security_pip );
		
		foreach( $option_pip as $pip ){
			
			if( $pip->file == $file_name && $pip->ip == $ip && $pip->time > $timed )
				$ip_total = $ip_total+1;
		}
	}
	
	if( $cek_ip_total )
		return $ip_total;
	else{
	
		$timer = ($timed * $timer);
		$pip_array2[] = array('file' => $file_name,'ip' => $ip, 'time' => $timer );
		//$security_pip = json_encode($pip_array2);
		//echo 'before INSERT NEW:'.$security_pip.'<br>';
		$pip_array3 = array_merge($pip_array2,$pip_array1);
		//$security_pip = json_encode($pip_array3);
		//echo 'after INSERT NEW MERGE using OLD:'.$security_pip.'<br>';
		$security_pip = json_encode($pip_array3);
			
		if( checked_option( 'security_pip' ) ) set_option( 'security_pip', $security_pip );
		else add_option( 'security_pip', $security_pip );		
	}
		
}

/**
 * Mengecek tanggal dan waktu berdasarkan kata
 *
 * @param int $session_time
 * @return string
 */
function date_stamp($session_time, $language = 'id'){ 
	$date 		= new DateTime($session_time);
	$timestamp 	= $date->format('U');
	$timestamp 	= time_stamp( $timestamp );
	return $timestamp;
}
/**
 * Mengecek tanggal dan waktu berdasarkan kata
 *
 * @param int $session_time
 * @return string
 */
function time_stamp($session_time, $language = 'id'){ 
	 
	$time_difference 	= time() - $session_time ; 
	$seconds 			= $time_difference ; 
	$minutes 			= round($time_difference / 60 );
	$hours 				= round($time_difference / 3600 ); 
	$days 				= round($time_difference / 86400 ); 
	$weeks 				= round($time_difference / 604800 ); 
	$months 			= round($time_difference / 2419200 ); 
	$years 				= round($time_difference / 29030400 ); 
	
	
	if( $language == 'id' ):
		$lang[0] = 'satu';
		$lang[1] = 'detik';
		$lang[2] = 'menit';
		$lang[3] = 'jam';
		$lang[4] = 'hari';
		$lang[5] = 'minggu';
		$lang[6] = 'bulan';
		$lang[7] = 'tahun';
		$lang[8] = 'yg lalu';
	else:
		$lang[0] = 'one';
		$lang[1] = 'seconds';
		$lang[2] = 'minutes';
		$lang[3] = 'hours';
		$lang[4] = 'day';
		$lang[5] = 'week';
		$lang[6] = 'month';
		$lang[7] = 'years';
		$lang[8] = 'ago';
	endif;
	
	if($seconds <= 60){
	$retval = "$seconds $lang[1] $lang[8]"; 
	}else if($minutes <=60){
		if($minutes==1) $retval = "$lang[0] $lang[2] $lang[8]"; 
		else $retval = "$minutes $lang[2] $lang[8]"; 
	}
	else if($hours <=24){
	   if($hours==1) $retval = "$lang[0] $lang[3] $lang[8]";
	   else $retval = "$hours $lang[3] $lang[8]";
	}
	else if($days <=7){
	  if($days==1) $retval = "$lang[0] $lang[4] $lang[8]";
	  else $retval = "$days $lang[4] $lang[8]";	  
	}
	else if($weeks <=4){
	  if($weeks==1) $retval = "$lang[0] $lang[5] $lang[8]";
	  else $retval = "$weeks $lang[5] $lang[8]";
	}
	else if($months <=12){
	   if($months==1) $retval = "$lang[0] $lang[6] $lang[8]";
	   else $retval = "$months $lang[6] $lang[8]";   
	}	
	else{
		if($years==1) $retval = "$lang[0] $lang[7] $lang[8]";
		else $retval = "$years $lang[7] $lang[8]";	
	}
	
	return $retval;	
	
}

function avatar_url( $user_login, $w = 120, $h = 120, $zc = 1 ){
	global $login;
	
	if( valid_mail($user_login) && $user_email = $user_login ){
		$where = compact('user_email');				
	}
	else
	{			
		$where = compact('user_login');
	}	
	
	$field = $login->data( $where );
	
	if( !checked_option( 'avatar_type' ) ) 
		add_option( 'avatar_type', 'gravatar' );
	
	if( get_option('avatar_type') == 'gravatar' && $field->user_status > 0 ){
		$url_image_profile = get_gravatar($field->user_email);
	}elseif( get_option('avatar_type') == 'computer' ){
		$url_image_profile = '/libs/img/avatar_default.png';
			
		if( file_exists( upload_path . '/avatar_'.$field->user_avatar) && $field->user_status > 0 ): 
			$url_image_profile = '/content/uploads/avatar_' . $field->user_avatar;
		endif;
			
		$url_image_profile = site_url($url_image_profile);
	}else{
		$url_image_profile = includes_url('/img/avatar_default.png');
	}
	
	$retval_url = '?request&load=libs/timthumb.php';
	$retval_url.= '&src='.$url_image_profile;
	$retval_url.= '&w='.$w;
	$retval_url.= '&h='.$h;
	$retval_url.= '&zc='.$zc;
	
	if( get_option('avatar_type') == 'gravatar' && $field->user_status > 0 )
		$retval_url = $url_image_profile;
	
	return $retval_url;
}

function get_file_data( $file, $default_headers, $context = '' ) {
	if(!file_exists($file) ) 
	return false;
	
	$fp 		= fopen( $file, 'r' );
	$file_data 	= fread( $fp, 8192  ); //8kiB
	fclose( $fp );
	
	foreach ( $default_headers as $field => $regex ) {
		preg_match( '/' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, ${$field});
		if ( !empty( ${$field} ) )
			${$field} = cleanup_header(${$field}[1]);
		else
			${$field} = '';
	}

	$data = compact( array_keys( $default_headers ) );

	return $data;
}

function save_to_file($file){
	$content =  stripslashes(trim ($_POST['content']));
	// Let's make sure the file exists and is writable first.
		if (is_writable($file)) {
		   if (!$handle = @fopen($file, 'w+')) {
				echo'<div class="info">Can\'t read a file ('.get_file_name($file).')</div>';
				exit;
		   }
		   if (fwrite($handle, $content) === FALSE) {
				$return='<div id="error">Can\'t write a file('.get_file_name($file).')</div>';
			   
			   exit;
		   } 
			   //clearstatcache($handle);
			fflush($handle);
			fclose($handle);
			echo '<div id="success">Success save to file ('.get_file_name($file).')</div>'; 
		} else {
		    echo '<div class="error">File $file can\'t write</div>';		   
		}
}

/**
 * memperbaharui widget
 */
function set_dashboard_admin( $string ){	
	/*
	$sorted = array();
	$sorted['normal'] = 'box1,box2';
	$sorted['side'] = 'box1,box2';
	*/	
	$string = esc_sql($string);
	
	if( checked_option( 'dashboard_widget' ) ) set_option( 'dashboard_widget', $string );
	else add_option( 'dashboard_widget', $string );
}
/**
 * Pengurutan array berdasarkan kolom nama array
 *
 * @param array $array_sort
 * @param array $cols_sort
 * @return array
 */
function array_multi_sort($array_sort, $cols_sort = array() ){
    $colarr = array();
    foreach ($cols_sort as $col => $order) {
        $colarr[$col] = array();
        foreach ($array_sort as $k => $row) { 
			$colarr[$col]['_'.$k] = strtolower($row[$col]); 
		}
    }
    $eval = 'array_multisort(';
    foreach ($cols_sort as $col => $order) {
        $eval .= '$colarr[\''.$col.'\'],'.$order.',';
    }
    $eval = substr($eval,0,-1).');';
    eval($eval);
    $ret = array();
    foreach ($colarr as $col => $arr) {
        foreach ($arr as $k => $v) {
            $k = substr($k,1);
            if (!isset($ret[$k])) $ret[$k] = $array_sort[$k];
            $ret[$k][$col] = $array_sort[$k][$col];
        }
    }
    return $ret;

}
function submit_button( $text = null, $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = null ) {
	echo get_submit_button( $text, $type, $name, $wrap, $other_attributes );
}

function get_submit_button( $text = null, $type = 'primary large', $name = 'submit', $wrap = true, $other_attributes = null ) {
	if ( ! is_array( $type ) )
		$type = explode( ' ', $type );

	$button_shorthand = array( 'primary', 'small', 'large' );
	$classes = array( 'button' );
	foreach ( $type as $t ) {
		if ( 'secondary' === $t || 'button-secondary' === $t )
			continue;
		$classes[] = in_array( $t, $button_shorthand ) ? 'button-' . $t : $t;
	}
	$class = implode( ' ', array_unique( $classes ) );

	if ( 'delete' === $type )
		$class = 'button-secondary delete';

	$text = $text ? $text : 'Save Changes';

	$id = $name;
	if ( is_array( $other_attributes ) && isset( $other_attributes['id'] ) ) {
		$id = $other_attributes['id'];
		unset( $other_attributes['id'] );
	}

	$attributes = '';
	if ( is_array( $other_attributes ) ) {
		foreach ( $other_attributes as $attribute => $value ) {
			$attributes .= $attribute . '="' .$value . '" '; // Trailing space is important
		}
	} else if ( !empty( $other_attributes ) ) { // Attributes provided as a string
		$attributes = $other_attributes;
	}

	$button = '<input type="submit" name="' . $name . '" id="' . $id . '" class="' . $class;
	$button	.= '" value="' . $text . '" ' . $attributes . ' />';

	if ( $wrap ) {
		$button = '<p class="submit">' . $button . '</p>';
	}

	return $button;
}
/**
 * Query security for ilegal operation
 *
 * @return false
 */
function query_security(){
	$attacked = array('ad_click','%20union%20','/*','*/union/*','c2nyaxb0','+union+','cmd=','&cmd','exec','execu','concat');
				
	if( is_query_values() && ( !stripost( is_query_values(), $attacked[0]) ) ) :
		if( stripost( is_query_values(), $attacked[1] ) or 
			stripost( is_query_values(), $attacked[2] ) or 
			stripost( is_query_values(), $attacked[3] ) or 
			stripost( is_query_values(), $attacked[4] ) or 
			stripost( is_query_values(), $attacked[5] ) or (
			stripost( is_query_values(), $attacked[6] ) and !
			stripost( is_query_values(), $attacked[7] )) or (
			stripost( is_query_values(), $attacked[8] ) and !
			stripost( is_query_values(), $attacked[9] )) or 
			stripost( is_query_values(), $attacked[10] ))
			die('Ilegal Operation');
	endif;
	return true;
}

function add_dialog_popup( $args = '' ){	
	$defaults = array(
		'dp_id' => 'No Title',
		'dp_title' => 'No Title',
		'dp_content' => 'No Desc',
		'dp_footer' => '',
		'dp_form' => 'method="post" action="" enctype="multipart/form-data"',
		'width' => 300,
		'height' => 'auto'
	);
	
	$r = parse_args( $args, $defaults );
	
	extract($r);
?>
<!--Show Dialog-->
<div id="dialog_<?php echo $dp_id?>"  class="redactor_modal" style="width: <?php echo $width?>px; height: <?php echo $height?>;display: none; ">
    <div id="redactor_modal_close">&times;</div>
    <div id="redactor_modal_header"><?php echo $dp_title;?></div>
    <?php if( !empty($dp_form) ):?>
    <form <?php echo $dp_form?>>
    <?php endif;?>
    <div id="redactor_modal_inner">
    <?php echo $dp_content;?>
    <div style="clear:both"></div>
    </div>
    <?php if( !empty($dp_footer) ):?>
    <div class="redactor_content_border" style="padding:5px;">
    <?php echo $dp_footer;?>
    <div style="clear:both"></div>
    </div>
    <?php endif;?>
    <?php if( !empty($dp_form) ):?>
    </form>
    <?php endif;?>
</div>
<!--Show End Dialog-->
<?php
}

function del_img_post($file,$path = ''){
		
		$path = upload_path .'/'. $path;
		if( !empty($file) && file_exists($path . $file))
			unlink($path . $file);
}
/*
$sidebar_action = array();
$sidebar_action['post'] = array('sidebar-1' => 1,'sidebar-2' => 0 );
$sidebar_action['download'] = array('sidebar-1' => 1,'sidebar-2' => 0 );
//echo json_encode($sidebar_action);


$sidebar_action_op = get_option('sidebar_actions');
$sidebar_action_op = json_decode( $sidebar_action_op );

$i  = 'sidebar-2';
$op = get_query_var('com');
if( $op && count($sidebar_action_op->$op) > 0 ):
foreach( $sidebar_action_op->$op as $sidebar_id => $status ){
	if( $sidebar_id == $i )
	echo $op.'=>'.$sidebar_id.':'.$status.'<br>';
}
echo count( (array)$sidebar_action_op->$op);
endif;*/

/**
 * mengubah spasi
 *
 * @return string lower
 */
function feed_add_space($string){
	if( empty($string) ) 
		return false;
	
	$string = html_entity_decode($string);
	$string = strtolower(preg_replace("/[^A-Za-z0-9-]/","-",$string));
	return $string;
}
/**
 * membaca xml 
 *
 * @return array
 */
function ul_feed( $feed ){
	$feed_content = '';		
	$feed_content.= '<ul class="ul-box">';
	if (is_array($feed)) {
	foreach($feed as $item)	{
		$feed_content .= '<li>
		<a href="'.$item->link.'" title="'.$item->title.'" target="_blank">'.$item->title.'</a>';
		if( !empty($item->author) || !empty($item->date) ):
			$feed_content .= '<div style="color:#333;">';
			if( !empty($item->author) ) $feed_content.= $item->author.' - ';
			if( !empty($item->date) ) $feed_content.= datetimes($item->date, false);
			$feed_content.= '</div>';
		endif;
		
		if( !empty($item->desc) ) 
			$feed_content.= '<div style="color:#333">'.initialized_text( filter_clean($item->desc) ).'</div>';
		
		$feed_content.= '</li>';	
	}}
	$feed_content.= '</ul>';
	return $feed_content;
}

function doing_feed(){	
	$json = new JSON();

	$news_feeds_default = array(
		'news_feeds' => array( 'cmsid.org Feed' => 'http://cmsid.org/rss.xml'),
		'display' => array('desc' => 0,'author' => 0,'date' => 0,'limit' => 10)
	);
	
	$news_feeds_default = $json->encode( $news_feeds_default );
	
	$news_feeds_old_value = get_option('feed-news');
	
	if( !empty($news_feeds_old_value) ) $feed_obj = $news_feeds_old_value;
	else $feed_obj = $news_feeds_default;
	
	$feed_obj = $json->decode( $feed_obj );
	
	$news_feeds_old = $feed_obj->{'news_feeds'};
	$display = $feed_obj->{'display'};
	
	if ( !class_exists('Rss') )
	require_once( libs_path . '/class-rss.php' );

	$Rss = new Rss;	
	
	$rssfeed_temp = $_temp = array();		
	foreach( $news_feeds_old as $title => $feed_url ):
		/*
			XML way
		 */
		try {
			
			$feed = $Rss->getFeed($feed_url, Rss::XML);
			
		}catch (Exception $e) {
			$error = $e->getMessage();
		}
		
		$rssfeed_temp[] = array( 
			'feed_title' => $title, 
			'feed_url' => $feed_url, 
			'feed_content' => $feed, 
			'error' => $error
		); 
	endforeach;
	
	foreach( $rssfeed_temp as $feed_item ):
		if (is_array($feed_item['feed_content'])) {	
			$i = 0;	
			foreach($feed_item['feed_content'] as $item):
				if( $i <= $display->{'limit'} ){
				
					$feed_content[$i]['title'] = $item['title'];
					$feed_content[$i]['link'] = $item['link'];
					
					if( $display->{'desc'} == 1 )
					$feed_content[$i]['desc'] = limittxt( filter_clean($item['description']),120);
					
					if( $display->{'author'} == 1 || $display->{'date'} == 1 ):
						if( $display->{'author'} == 1 ) $feed_content[$i]['author'] = $item['4'];
						if( $display->{'date'} == 1 ) $feed_content[$i]['date'] = $item['date'];
					endif;
					
				}
			$i++;
			endforeach;
		}
			
			$_temp[] = array( 
					'feed_title' => $feed_item[feed_title], 
					'feed_url' => $feed_item[feed_url], 
					'feed_content' => $feed_content,
					'error' => $feed_item[error]
				);
		//endif;
	endforeach;
	
	//$rssfeed_x = array_merge_simple($rssfeed_x, array( 'display' => $display) ); 	
	return json_encode( $_temp );
}

function try_xml( $url ){   		
	$xml = get_content($url);		
	$val = simplexml_load_string($xml);
	return $val;
}