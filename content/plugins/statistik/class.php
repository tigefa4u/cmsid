<?php

/**
 * membuat class statistik
 */
class statistik {
	/**
	 * membuat progress bar dari hit
	 *
	 * @return persentase
	 */
	function progress($option){
		global $db;
		$query		= $db->select('stat_browse',array('title'=>$option));
		$show		= $db->fetch_array($query);
		
		$option 	= explode("#", $show["option"]);
		$hits	 	= explode("#", $show["hits"]);
		$totopt 	= count($option)-1;
	
		$tothits 	= 0;
		foreach($hits as $vhit) $tothits = $tothits + $vhit;
		
		if($tothits == 0) $tothits = 1;
			$progress = array(
			'opt'		=>$option,
			'hit'		=>$hits,
			'totopt'	=>$totopt,
			'tothit'	=>$tothits,
			'percent'	=>$persentase,
			'option'	=>$option
			);
		return $progress;
	}		
	/**
	 * mengubah persentase menjadi warna
	 *
	 * @return color
	 */
	function select_color($persentase,$color=''){	
		if($persentase < 45) $color='orange';
		elseif($persentase < 70) $color='green';
		else $color='blue';
		
		return $color;
	}
	
	function reset_statistic(){
		global $db;
		
		$sql = $db->select('stat_browse');	
		
		$run = '';	
		while( $row = $db->fetch_obj($sql) ){		
		$v1 	= explode("#", $row->option);
		$totv1 	= count($v1)-1;
		
		$option = $hits = '';
		for($i=0;$i<$totv1;$i++){
			$option	.= $v1[$i].'#';
			$hits	.= '0#';
		}
		
		if($row->title == 'country') $option = $hits = '';
		
		$data = compact('option','hits');
		
		$run .= $db->truncate('stat_count');
		$run .= $db->update('stat_browse', $data, array('title'=>$row->title) );
		}
		return $run;
	}
}