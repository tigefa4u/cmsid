<?php 
/**
 * @fileName: rewrite.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;

class engine{
	var $data_judul;
	
	function construct($data_judul){
		return $this->data_judul = $this->add_space($data_judul);
	}
	
	function __toString(){
		return $this->data_judul;
	}
	
	private function add_space($string){
		if($string!='')
		$stringname=html_entity_decode($string);
		$stringname=strtolower(preg_replace("/[^A-Za-z0-9-]/","-",$stringname));
		return $stringname;
	}
	
	private function add_to_expload($explode){
		$explode=explode('-',$this->construct($explode));
		$jumcount=count($explode);
		for($i=0; $i<=$jumcount; $i++){
			if(!empty($explode[$i]))
				$string[] = $explode[$i];
		}
		if(!empty($string))	$implode=implode("-",$string);
		return $implode;		
	}
	
	function judul($data){
		return $this->data_judul=$this->add_to_expload($data);
	}
	
	function convert_tags($data){
		$explode = explode('+',$data);
		//$jumcount=count($explode);
		//for($i=0; $i<=$jumcount; $i++){
		for($i=0; $i<=5; $i++){
			if(!empty($explode[$i]))
				$string[] = $explode[$i];
		}
		return implode("-",$string);
	}
	
	function item($int){
		if (is_numeric ($int)){
			return (int)preg_replace ( '/\D/i', '', $int);
		}
	}
	
	function no(){
		$result = get_option('rewrite_setting');
		$a 		= explode('#',$result);
		$r 		= array();
		
		foreach($a as $b){
			$exp = explode('::',$b);
			
			if($exp[3]==1)
				$no	= $exp[0];
		}
		return $no;
	}	
}
class linked extends engine{
	
	function set( $app, $data = null, $auto = true ){
		
	global $rewrite, $rewrite_link, $rewrite_app, $rewrite_data;
	
	
	$rewrite_app = esc_sql( $app );
	$rewrite_data = esc_sql( $data );
	
	do_action('rewrite');
	
	if( !checked_option('rewrite') ) $o = 'advance';
	else $o = get_option('rewrite');
	
	if( $o != 'advance' 
	&& empty($rewrite_link)
	) 	$rewrite = 'advance';
	
	//view,cat_id,id,pg
	if( $rewrite != 'advance' 
	&& $auto 
	&& $rewrite_link ):	
		/*
		 * Type: Where using plugin load global $rewrite_link
		 * Number: N/A
		 */	
		$link = $rewrite_link;
			
	else:		
		
		if(!empty($data)):
		if(!is_array($data))
		return false;	
		extract($data, EXTR_SKIP);
		endif;
		/*
		 * Type: Advance (default)
		 * Number: 1
		 */	
		if($app=='login' || $app=='logout'):
			if(!empty($go) && !empty($act)):
				//?login&go=N/A&cat=N/A; 
				$link = '?'.$app.'&go='.$go.'&act='.$act;
			elseif(!empty($go)):
				//?login&go=N/A; 
				$link = '?'.$app.'&go='.$go;
			else:
				if($app == 'logout'):
				//?login&go=N/A; 
				$link = '?login&go='.$app;
				else:
				//?login
				$link = '?'.$app; 
				endif;
			endif;
		else:
			if(!empty($pg) && !empty($cat_id) && !empty($id) && !empty($view)):
				//?com=string&view=string&cat_id=string&id=string&pg=0
				$link = '?com='.$app.'&view='.$view.'&cat_id='.$cat_id.'&id='.$id.'&pg='.$pg; 
			elseif(!empty($pg) && !empty($id) && !empty($view)):
				//?com=string&view=string&id=string&pg=0
				$link = '?com='.$app.'&view='.$view.'&id='.$id.'&pg='.$pg; 
			elseif(!empty($pg) && !empty($view)):
				//?com=string&view=string&pg=0
				$link = '?com='.$app.'&view='.$view.'&pg='.$pg; 
			elseif(!empty($cat_id) && !empty($id) && !empty($view)):
				//?com=string&view=string&cat_id=string&id=string
				$link = '?com='.$app.'&view='.$view.'&cat_id='.$cat_id.'&id='.$id; 
			elseif(!empty($id) && !empty($view) && !empty($go) && !empty($to)):
				//?com=string&view=string&id=string&go=0&to=0
				$link = '?com='.$app.'&view='.$view.'&id='.$id.'&go='.$go.'&to='.$to;  
			elseif(!empty($id) && !empty($view) && !empty($go)):
				//?com=string&view=string&id=string&go=0
				$link = '?com='.$app.'&view='.$view.'&id='.$id.'&go='.$go; 
			elseif(!empty($id) && !empty($view)):
				//?com=string&view=string&id=string
				$link = '?com='.$app.'&view='.$view.'&id='.$id; 
			elseif(!empty($id)):
				//?com=string&id=string
				$link = '?com='.$app.'&id='.$id; 
			elseif(!empty($view)):
				//?com=string&view=string
				$link = '?com='.$app.'&view='.$view; 
			else:
				//?com=string
				$link = '?com='.$app; 
			endif;
		endif;
		
	endif;
	
	if( !empty($app) ) return site_url( $link );
	else return site_url();
	
	}
}

function do_links( $type, $data = false, $auto = true ){
	$link = new linked();
	return $link->set( $type, $data, $auto );
}

function rewrite_d(){
	global $rewrite, $rewrite_link;
	
	if( $rewrite == 'advance' || !$rewrite_link  ):
	
	$f = abs_path.'.htaccess';
	if( file_exists($f) )
		unlink($f);
		
	endif;
}
add_action('the_head','rewrite_d');