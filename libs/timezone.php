<?php 
/**
 * @fileName: timezone.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;

function timezone_supported() {
	$support = false;
	if (
		function_exists( 'date_create' ) &&
		function_exists( 'date_default_timezone_set' ) &&
		function_exists( 'timezone_identifiers_list' ) &&
		function_exists( 'timezone_open' ) &&
		function_exists( 'timezone_offset_get' )
	) {
		$support = true;
	}
	return $support;
}

function timezone_usort_callback( $a, $b ) {
	// Don't use translated versions of Etc
	if ( 'Etc' === $a['continent'] && 'Etc' === $b['continent'] ) {
		// Make the order of these more like the old dropdown
		if ( 'GMT+' === substr( $a['city'], 0, 4 ) && 'GMT+' === substr( $b['city'], 0, 4 ) ) {
			return -1 * ( strnatcasecmp( $a['city'], $b['city'] ) );
		}
		if ( 'UTC' === $a['city'] ) {
			if ( 'GMT+' === substr( $b['city'], 0, 4 ) ) {
				return 1;
			}
			return -1;
		}
		if ( 'UTC' === $b['city'] ) {
			if ( 'GMT+' === substr( $a['city'], 0, 4 ) ) {
				return -1;
			}
			return 1;
		}
		return strnatcasecmp( $a['city'], $b['city'] );
	}
	if ( $a['t_continent'] == $b['t_continent'] ) {
		if ( $a['t_city'] == $b['t_city'] ) {
			return strnatcasecmp( $a['t_subcity'], $b['t_subcity'] );
		}
		return strnatcasecmp( $a['t_city'], $b['t_city'] );
	} else {
		// Force Etc to the bottom of the list
		if ( 'Etc' === $a['continent'] ) {
			return 1;
		}
		if ( 'Etc' === $b['continent'] ) {
			return -1;
		}
		return strnatcasecmp( $a['t_continent'], $b['t_continent'] );
	}
}

function timezone_choice( $selected_zone ) {
	static $mo_loaded = false;

	$continents = array( 'Africa', 'America', 'Antarctica', 'Arctic', 'Asia', 'Atlantic', 'Australia', 'Europe', 'Indian', 'Pacific');

	$zonen = array();
	foreach ( timezone_identifiers_list() as $zone ) {
		$zone = explode( '/', $zone );
		if ( !in_array( $zone[0], $continents ) ) {
			continue;
		}

		// This determines what gets set and translated - we don't translate Etc/* strings here, they are done later
		$exists = array(
			0 => ( isset( $zone[0] ) && $zone[0] ),
			1 => ( isset( $zone[1] ) && $zone[1] ),
			2 => ( isset( $zone[2] ) && $zone[2] ),
		);
		$exists[3] = ( $exists[0] && 'Etc' !== $zone[0] );
		$exists[4] = ( $exists[1] && $exists[3] );
		$exists[5] = ( $exists[2] && $exists[3] );

		$zonen[] = array(
			'continent'   => ( $exists[0] ? $zone[0] : '' ),
			'city'        => ( $exists[1] ? $zone[1] : '' ),
			'subcity'     => ( $exists[2] ? $zone[2] : '' ),
			't_continent' => ( $exists[3] ? str_replace( '_', ' ', $zone[0]) : '' ),
			't_city'      => ( $exists[4] ? str_replace( '_', ' ', $zone[1]) : '' ),
			't_subcity'   => ( $exists[5] ? str_replace( '_', ' ', $zone[2]) : '' )
		);
	}
	usort( $zonen, 'timezone_usort_callback' );

	$structure = array();

	if ( empty( $selected_zone ) ) {
		$structure[] = '<option selected="selected" value="">Select a city</option>';
	}

	foreach ( $zonen as $key => $zone ) {
		// Build value in an array to join later
		$value = array( $zone['continent'] );

		if ( empty( $zone['city'] ) ) {
			// It's at the continent level (generally won't happen)
			$display = $zone['t_continent'];
		} else {
			// It's inside a continent group

			// Continent optgroup
			if ( !isset( $zonen[$key - 1] ) || $zonen[$key - 1]['continent'] !== $zone['continent'] ) {
				$label = $zone['t_continent'];
				$structure[] = '<optgroup label="'. $label .'">';
			}

			// Add the city to the value
			$value[] = $zone['city'];

			$display = $zone['t_city'];
			if ( !empty( $zone['subcity'] ) ) {
				// Add the subcity to the value
				$value[] = $zone['subcity'];
				$display .= ' - ' . $zone['t_subcity'];
			}
		}

		// Build the value
		$value = join( '/', $value );
		$selected = '';
		if ( $value === $selected_zone ) {
			$selected = 'selected="selected" ';
		}
		$structure[] = '<option ' . $selected . 'value="' . $value . '">' . $display . "</option>";

		// Close continent optgroup
		if ( !empty( $zone['city'] ) && ( !isset($zonen[$key + 1]) || (isset( $zonen[$key + 1] ) && $zonen[$key + 1]['continent'] !== $zone['continent']) ) ) {
			$structure[] = '</optgroup>';
		}
	}

	// Do UTC
	$structure[] = '<optgroup label="UTC">';
	$selected = '';
	if ( 'UTC' === $selected_zone )
		$selected = 'selected="selected" ';
	$structure[] = '<option ' . $selected . 'value="UTC">UTC</option>';
	$structure[] = '</optgroup>';

	// Do manual UTC offsets
	$structure[] = '<optgroup label="Manual Offsets">';
	$offset_range = array (-12, -11.5, -11, -10.5, -10, -9.5, -9, -8.5, -8, -7.5, -7, -6.5, -6, -5.5, -5, -4.5, -4, -3.5, -3, -2.5, -2, -1.5, -1, -0.5,
		0, 0.5, 1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5, 5.5, 5.75, 6, 6.5, 7, 7.5, 8, 8.5, 8.75, 9, 9.5, 10, 10.5, 11, 11.5, 12, 12.75, 13, 13.75, 14);
	foreach ( $offset_range as $offset ) {
		if ( 0 <= $offset )
			$offset_name = '+' . $offset;
		else
			$offset_name = (string) $offset;

		$offset_value = $offset_name;
		$offset_name = str_replace(array('.25','.5','.75'), array(':15',':30',':45'), $offset_name);
		$offset_name = 'UTC' . $offset_name;
		$offset_value = 'UTC' . $offset_value;
		$selected = '';
		if ( $offset_value === $selected_zone )
			$selected = 'selected="selected" ';
		$structure[] = '<option ' . $selected . 'value="' . $offset_value . '">' . $offset_name . "</option>";

	}
	$structure[] = '</optgroup>';

	return join( "\n", $structure );
}

function site_timezone( $timezone = null ){
	if( function_exists('timezone') ) {
		
		if( empty($timezone) )  
			$timezone = get_option('timezone');
					
		@date_default_timezone_set( $timezone );
	}
}
