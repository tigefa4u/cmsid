<?php 
/**
 * @fileName: screen.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;

function get_current_screen() {
	global $current_screen;

	if ( ! isset( $current_screen ) )
		return null;

	return $current_screen;
}

function do_meta_boxes( $context, $object ) {
	global $meta_boxes;
	static $already_sorted = false;
	
	$json = new JSON();
	
	$sorted = array();	
	$dashboard_widget_value = get_option('dashboard_widget');	

	printf('<div id="%s-sortables" class="meta-box-sortables">', htmlspecialchars($context));

	$i = 0;
	do {		
		
		if ( !$already_sorted && !empty($dashboard_widget_value) ) {
			$sorted = $json->decode( $dashboard_widget_value );
					
			foreach ( $sorted as $box_context => $ids ) {
				foreach ( explode(',', $ids ) as $id ) {
					if ( $id ) add_meta_box( $id, null, null, $box_context, 'sorted' );
				}
			}
		}
		$already_sorted = true;

		if ( !isset($meta_boxes) || !isset($meta_boxes) || !isset($meta_boxes[$context]) )
			break;

		foreach ( array('high', 'sorted', 'core', 'default', 'low') as $priority ) {
			if ( isset($meta_boxes[$context][$priority]) ) {
				foreach ( (array) $meta_boxes[$context][$priority] as $box ) {
					if ( false == $box || ! $box['title'] )
						continue;
					$i++;
					$style = '';
					echo '<div class="gd dragbox" id="' . $box['id'] . '">'."\n";
					echo '<div class="gd-header">' . $box['title'] . "\n";		
					echo '<span class="coltoggle" title="Sembunyikan/Munculkan"></span>'."\n";			
					echo '<span class="colspace" title="Pindahkan"></span>'."\n";		
					
					if ( $box['setting'] ){
					echo '<a href="javascript:void(0);"  onclick="javascript:$(\'#dialog_' . $box['id'] . '\').showX()"><span class="configure ico config" style="visibility: hidden;" title="Ubah Pengaturan"></span></a>';
					}
					
					echo '</div>' . "\n";
					echo '<div class="gd-content">'."\n";
					call_user_func($box['callback'], $object, $box);
					echo '</div>'."\n";
					
					if ( $box['foot'] ){
					echo '<div class="gd-content">'."\n";
					echo $box['foot'];
					echo '</div>'."\n";
					}
					echo '</div>';
				}
			}
		}
	} while(0);

	echo "</div>";

	return $i;

}

function add_screen_option( $option, $args = array() ) {
	$current_screen = get_current_screen();

	if ( ! $current_screen )
		return;

	$current_screen->add_option( $option, $args );
}

function set_current_screen( $hook_name =  '' ) {
	Screen::get( $hook_name )->set_current_screen();
}

function get_column_headers( $screen ) {
	if ( is_string( $screen ) )
		$screen = convert_to_screen( $screen );

	static $column_headers = array();

	if ( ! isset( $column_headers ) )
		$column_headers = array();

	return $column_headers;
}

/**
 * A class representing the admin screen.
 *
 * @since 3.3.0
 * @access public
 */
final class Screen {
	public $parent_base;
	public $parent_file;
	private $_help_tabs = array();
	private $_help_sidebar = '';
	private static $_old_compat_help = array();
	private $_options = array();
	private $_show_screen_options;
	private $_screen_settings;
	public static function get( $hook_name = '' ) {

		if ( is_a( $hook_name, 'Screen' ) )
			return $hook_name;
			
		$screen = new Screen();

		return $screen;
 	}
	
	function set_current_screen() {
		global $current_screen;
		$current_screen = $this;
		do_action( 'current_screen', $current_screen );
	}

	private function __construct() {}

	public function add_option( $option, $args = array() ) {
		$this->_options[ $option ] = $args;
	}

	public function get_option( $option, $key = false ) {
		if ( ! isset( $this->_options[ $option ] ) )
			return null;
		if ( $key ) {
			if ( isset( $this->_options[ $option ][ $key ] ) )
				return $this->_options[ $option ][ $key ];
			return null;
		}
		return $this->_options[ $option ];
	}

	public function render_screen_meta() {
		// Add screen options
		if ( $this->show_screen_options() )
			$this->render_screen_options();
	}

	public function show_screen_options() {
		global $meta_boxes;

		if ( is_bool( $this->_show_screen_options ) )
			return $this->_show_screen_options;

		$columns = get_column_headers( $this );

		$show_screen = ! empty( $meta_boxes ) || $columns || $this->get_option( 'per_page' );

		if ( $this->_options )
			$show_screen = true;

		$this->_show_screen_options = $show_screen;
		return $this->_show_screen_options;
	}

	public function render_screen_options() {
		global $meta_boxes;

		$this->render_screen_layout();
	}

	function render_screen_layout() {
		global $screen_layout_columns;

		// Back compat for plugins using the filter instead of add_screen_option()
		$columns = array();

		if ( ! empty( $columns ) && isset( $columns ) )
			$this->add_option( 'layout_columns', array('max' => $columns ) );

		if ( ! $this->get_option('layout_columns') ) {
			$screen_layout_columns = 0;
			return;
		}
		
		//set limit colomn
		$screen_layout_columns = '';
		$num = $this->get_option( 'layout_columns', 'max' );

		if ( ! $screen_layout_columns || 'auto' == $screen_layout_columns ) {
			if ( $this->get_option( 'layout_columns', 'default' ) )
				$screen_layout_columns = $this->get_option( 'layout_columns', 'default' );
		}

	}

}
