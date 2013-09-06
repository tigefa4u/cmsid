<?php
/**
 * @file: class-geo.php
 */
 
//dilarang mengakses
if(!defined('_iEXEC')) exit;

class country {

	private $codes = array() ;
	
	public function __construct()
	{			
		$this->codes = new GeoIP;
		
	}
	
	//list of countries for SELECT
	public function country_list( $selected = "ID" )
	{
		foreach($this->codes->GEOIP_COUNTRY_CODES as $key => $val)
		{
			if($key == 0) echo '<option value="">Pilih Negara</option>'."\n";
			else{
			if($val == $selected) echo '<option value="'.$val.'" selected="selected">'.$this->codes->GEOIP_COUNTRY_NAMES[$key].'</option>'."\n";
			else  echo '<option value="'.$val.'">'.$this->codes->GEOIP_COUNTRY_NAMES[$key].'</option>'."\n";
			}
		}
	}
	
	//country count
	public function country_count()
	{
		return count($this->codes->GEOIP_COUNTRY_CODES);
	}
	
	//country code
	public function country_code($name)
	{
		if( empty($name) )
			return 'Tidak diketahui';
			
		foreach($this->codes->GEOIP_COUNTRY_NAMES as $key => $val)
		{
			if(strpos($key,$name) !== false )
				return $this->codes->GEOIP_COUNTRY_CODES[$key];
		}
		return false;
	}
	
	//country name
	public function country_name($code)
	{
		if( empty($code) )
			return 'Tidak diketahui';
			
		foreach($this->codes->GEOIP_COUNTRY_CODES as $key => $val)
		{
			if(strpos($val,$code) !== false )
				return $this->codes->GEOIP_COUNTRY_NAMES[$key];
		}
		
	}
}