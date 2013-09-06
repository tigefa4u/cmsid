<?php
/**
 * @file: geoip.php
 */
 
//not direct access
if(!defined('_iEXEC')) exit;

function anonymise_ip( $_addr, $_mask ) {
		$addr = long2ip( ip2long( $_addr ) & ip2long( $_mask ) );
		if ( $addr == '0.0.0.0' ) {
			$addr = '';
		}
		return $addr;
	}
	
function _determine_remote_ip($anonymise_ip_mask) {
		
		$remote_addr = $_SERVER['REMOTE_ADDR'];
		if ( ( $remote_addr == '127.0.0.1' || $remote_addr == '::1' || $remote_addr == $_SERVER['SERVER_ADDR'] ) &&
		     array_key_exists( 'HTTP_X_FORWARDED_FOR', $_SERVER ) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '' ) {
			// There may be multiple comma-separated IPs for the X-Forwarded-For header
			// if the traffic is passing through more than one explicit proxy. Take the
			// last one as being valid. This is arbitrary, but there is no way to know
			// which IP relates to the client computer. We pick the first client IP as
			// this is the client closest to our upstream proxy.
			$remote_addrs = explode( ', ', $_SERVER['HTTP_X_FORWARDED_FOR'] );
			$remote_addr = $remote_addrs[0];
		}
		
		if ( $anonymise_ip_mask != '' && $anonymise_ip_mask != '255.255.255.255' ) {
			$remote_addr = anonymise_ip( $remote_addr, $anonymise_ip_mask );
		}
		
		return $remote_addr;
	}
	
function is_geoip_installed() {
		return ( file_exists( libs_path . '/geoip/geoip.class.php' ) &&
		         file_exists( libs_path . '/geoip/GeoIP.dat' ) );
	}
	
function _determine_country( $_ip ) {
		if ( is_geoip_installed() ) {
			include_once( libs_path . '/geoip/geoip.class.php' );
			$gi = geoip_open( libs_path . '/geoip/GeoIP.dat', GEOIP_STANDARD );
			return geoip_country_code_by_addr( $gi, $_ip );
			geoip_close( $gi );
		} else {
			return '';
		}
	}

function get_country_geoip_list(){
	$anonymise_ip_mask 	= '255.255.255.0';
	$remote_ip 			= mb_substr( _determine_remote_ip( $anonymise_ip_mask ), 0, 15 );
	return _determine_country( $remote_ip );
}

