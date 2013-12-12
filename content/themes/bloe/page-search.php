<?php 
global $db;
$s = esc_sql( filter_txt(get_query_var('s')) );

ob_start();

$query = $db->query("SELECT * FROM $db->post WHERE ((title LIKE '%$s%' OR content LIKE '%$s%') AND status=1 AND approved=1 AND type='post') ORDER BY hits DESC LIMIT 10");
while( $data = $db->fetch_obj($query) ){
	$search_title = ereg_replace($s,"<span style='background:#fcfcae'>$s</span>",strtolower(substr(strip_tags($data->title),0,80)));
	$search_content = ereg_replace($s,"<span style='background:#fcfcae'>$s</span>",strtolower(substr(strip_tags($data->content),0,180)));
	echo '<div class="search-frame"><a href="'.do_links('post', array('view' => 'item', 'id' => $data->id, 'title' => $data->title)).'"><strong>'.$search_title.'</strong></a><div style="clear:both"></div>'.$search_content.'<br></div>';
}

$search = ob_get_contents();
ob_end_clean();

$data = array(
	'view' 		=> 'page',	
	'the_title' => 'Search for "'.$s.'"',	
	'title' 	=> 'Search for "'.$s.'"', 
	'content' 	=> $search
);
add_the_content_view( (object) $data );