<?php
/**
 * @file: phpids.php
 * @type: plugin
 */
 /*
Plugin Name: PHP Security
Plugin URI: http://cmsid.org/#
Description: Plugin keamanan dari phpids.org.
Author: Eko Azza
Version: 1.2.0
Author URI: http://cmsid.org/
*/
//not direct access
if(!defined('_iEXEC')) exit;

function phpids_init(){
	global $db, $login;
	
	
	if(!$login->check() 
	&& !$login->level('admin')
	&& !get_query_var('admin') )
	{
	
	
	if( substr(phpversion(),0,3) >= 5.1 ):
	
	set_include_path(dirname(__FILE__));
	
	$request	= array(
		//yang difilter oleh php ids				
		'GET' 			=> $_GET//,
		//'REQUEST' 	=> $_REQUEST,
		//'COOKIE' 		=> $_COOKIE,
		//'POST' 			=> $_POST
	);
	require_once 'IDS/Init.php';
	try{
	$init 									= IDS_Init::init(dirname(__FILE__) . '/IDS/Config/Config.ini');		
	$init->config['General']['tmp_path'] 	= dirname(__FILE__) . '/IDS/tmp';
	$init->config['General']['filter_path'] = dirname(__FILE__) . '/IDS/default_filter.xml';
	$init->config['Caching']['caching'] 	= 'none';
	
	$ids 	= new IDS_Monitor($request, $init);
	$result = $ids->run();
	if (!$result->isEmpty()) {
		require_once 'IDS/Log/Composite.php';
		require_once 'IDS/Log/Database.php'; 
		
		$compositeLog = new IDS_Log_Composite();    	
		$compositeLog->addLogger(IDS_Log_Database::getInstance($init));
		$compositeLog->execute($result);
		
		global $Output;
		if (is_array($Output)) {
			$Output 	= array_map('mysql_escape_string',$Output);
			
			$name 		= esc_sql($Output['name']);
			$value 		= esc_sql($Output['value']);
			$page 		= esc_sql($Output['page']);
			$ip 		= esc_sql($Output['ip']);
			$impact 	= esc_sql($Output['impact']);
			$created 	= date('Y-m-d H:i:s');
			
			$data = compact('name','value','page','ip','impact','created');
			$db->insert('phpids',$data);
		}
		else
		{
			redirect();
			//die('403 Request Not Found<br> Apologies, but the Request you requested could not be found');
		}
	}
	}catch ( Exception $e ){		
		redirect();
		//die ('<center>'.$e->getMessage().'</center>');
	}
	
	endif;
	
	}
}
add_action('plugins_loaded', 'phpids_init');

function phpids_monitor( $status, $limit = 10 ){
	global $db;
	
	$add_query = "`created`";
	if( $status == 'impact' ) 
		$add_query = "`impact`";
?>
    <div style="overflow:auto; max-height:200px;">
    <?php
    $query = $db->query( "SELECT * FROM $db->phpids ORDER BY $add_query DESC LIMIT $limit");
    if($db->num($query) < 1) echo '<div class="padding"><div id="error_no_ani">Data kosong</div></div>';
    else{
		
    echo '<ul class="ul-box">';
    while($data	= $db->fetch_obj($query)){
		
		if( $status == 'ip' ){
    ?>
    <li title="<?php echo $data->ip;?>"><a href="#" onclick="alert('<?php echo $data->ip?>')"><?php echo limittxt($data->ip,55);?></a><span><?php echo $data->impact;?></span></li>
    <?php
		}else{
    ?>
    <li title="<?php echo $data->page;?>"><a href="#" onclick="alert('<?php echo $data->page?>')"><?php echo limittxt($data->page,55);?></a><span><?php echo $data->impact;?></span></li>
    <?php }}?>
    </ul>
    <?php }?>
    </div>
<?php
}

function phpids_box(){
global $db;
	
if( checked_option( 'phpids_limit' )  ) $phpids_limit = get_option('phpids_limit');
else $phpids_limit = 10;

$dp_content = '<div class="padding">';
$dp_content.= '<label for="txtShow">Jumlah yang di tampilkan</label>';
$dp_content.= '<input id="txtShow" name="txtShow" type="text" style="width:50px" value="'.$phpids_limit.'" />';
$dp_content.= '</div>';

$dp_footer = '<div style="float:left"><input id="rec_submit" type="submit" name="submitPHPids" value="Submit" class="button on l" /><input type="reset" name="Reset" value="Reset" class="button r" /></div>
<div style="float:right"><input onclick="return confirm(\'Are You sure delete all records?\')" id="rec_submit" type="submit" name="submitPHPidsClear" value="Set to Empty" class="button red" /></div>';

$setting = array(
	'dp_id' => 'phpids',
	'dp_title' => 'Pengaturan PHPids Monitoring',
	'dp_content' => $dp_content,	
	'dp_footer' => $dp_footer
);
add_dialog_popup( $setting );


if( isset($_POST['submitPHPids']) ){
	$txt_phpids_limit = (int)$_POST['txtShow'];
	if( checked_option( 'phpids_limit' ) ) set_option( 'phpids_limit', $txt_phpids_limit );
	else add_option( 'phpids_limit', $txt_phpids_limit );
	
	redirect('?admin');
}

if( isset($_POST['submitPHPidsClear']) ){
	
	$clear = $db->truncate('phpids');
	
	if( $clear )
	redirect('?admin');
}
?>
<style type="text/css">
.tab_phpids
{
	padding:0;
}
.tab_phpids ul.ul-box
{
	
}
ul.phpids
{
	margin: 0;
	padding: 0;
	float: left;
	list-style: none;
	height: 25px;
	margin-left:3px;
	margin-right:0;
	margin-top:2px;
}
ul.phpids li 
{
	float: left;
	margin: 0;
	padding: 0;
	height: 24px;
	line-height: 24px;
	border: 1px solid #ddd;
	margin-right:2px;
	overflow: hidden;
	font-weight:normal;
    border-top-left-radius: 2px;
    border-top-right-radius: 2px;
    -moz-border-radius-topleft: 2px;
    -moz-border-radius-topright: 2px;
}
ul.phpids li a
{
	text-decoration: none;
	display: block;
	padding: 0 5px 0 5px;
	outline: none;
}
ul.phpids li a:hover 
{
	background: #f2f2f2;
    border-top-left-radius: 2px;
    border-top-right-radius: 2px;
    -moz-border-radius-topleft: 2px;
    -moz-border-radius-topright: 2px;
}
html ul.phpids li.active,
html ul.phpids li.active a:hover
{
	background: #f2f2f2;
	border-bottom: 1px solid #ccc;
	border-bottom-style:dotted;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
	//var phpids_url = '?request&load=phpids/data.php&plg=yes';
	getLoad('phpids_view','?request&load=phpids/data.php&plg=yes');	
	/*setInterval(function() {
		getLoad('phpids_view',phpids_url);
	}, 30000); // 30 second*/
});	
</script>
<div id="phpids_view"></div>    
<?php
}

function phpids_box_echo(){ 

echo phpids_box();
 
} 

add_dashboard_widget( 'phpids', 'PHP Security Monitor', 'phpids_box_echo', true );