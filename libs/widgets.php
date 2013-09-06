<?php 
/**
 * @fileName: class-widgets.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;
/**
 * API for creating dynamic sidebar without hardcoding functionality into
 * themes. Includes both internal WordPress routines and theme use routines.
 *
 * This functionality was found in a plugin before WordPress 2.2 release which
 * included it in the core from that point on.
 *
 * @link http://codex.wordpress.org/Plugins/WordPress_Widgets WordPress Widgets
 * @link http://codex.wordpress.org/Plugins/WordPress_Widgets_Api Widgets API
 */
class Widgets {

	var $id_base;
	var $name;
	var $widget_options;

	var $number = false;
	var $id = false;
	var $updated = false;
	
	function widget($args) {
		die('function Widgets::widget() must be over-ridden in a sub-class.');
	}

	function Widgets( $id_base = false, $name, $widget_options = array() ) {
		Widgets::__construct( $id_base, $name, $widget_options );
	}

	function __construct( $id_base = false, $name, $widget_options = array() ) {
		$this->id_base = empty($id_base) ? preg_replace( '/()?widget_/', '', strtolower(get_class($this)) ) : strtolower($id_base);
		$this->name = $name;
		$this->option_name = 'widget_' . $this->id_base;
		$this->widget_options = parse_args( $widget_options, array('classname' => $this->option_name) );
	}
	
	function _register() {
		register_sidebar_widget( $this->id_base, $this->name, $this->_get_display_callback(), $this->widget_options );
	}

	function _get_display_callback() {
		return array(&$this, 'display_callback');
	}

	function display_callback( $args ) {
		$this->widget($args);
	}
}

/**
 * Singleton that registers and instantiates Widget classes.
 */
 
class Widget_Factory {
	var $widgets = array();

	function Widget_Factory() {
		add_action( 'widgets_init', array( &$this, '_register_widgets' ), 100 );
	}

	function register($widget_class) {
		$this->widgets[$widget_class] = new $widget_class();
	}

	function unregister($widget_class) {
		if ( isset($this->widgets[$widget_class]) )
			unset($this->widgets[$widget_class]);
	}

	function _register_widgets() {
		global $registered_widgets;
		
		$keys = array_keys($this->widgets);
		$registered = array_keys($registered_widgets);
		$registered = array_map('_get_widget_id_base', $registered);
		
		foreach ( $keys as $key ) {
			if ( in_array($this->widgets[$key]->id_base, $registered, true) ) {
				unset($this->widgets[$key]);
				continue;
			}

			$this->widgets[$key]->_register();
		}
	}
}


//meregister widget / add new widget
function register_widget($widget_class) {
	$widget_factory = new Widget_Factory();
	
	$widget_factory->register($widget_class);
}


function list_widget_controls( $sidebar ) {

	echo "<div id='$sidebar' class='widgets-sortables'>\n";

	$description = sidebar_description( $sidebar );

	if ( !empty( $description ) ) {
		echo "<div class='sidebar-description'>\n";
		echo "\t<p class='description'>$description</p>";
		echo "</div>\n";
	}

	dynamic_sidebar( $sidebar );
	echo "</div>\n";
}

function widget_control( $sidebar_args ) {
	global $registered_widgets, $registered_widget_controls, $sidebars_widgets;

	$widget_id = $sidebar_args['widget_id'];
	$sidebar_id = isset($sidebar_args['id']) ? $sidebar_args['id'] : false;
	$key = $sidebar_id ? array_search( $widget_id, $sidebars_widgets[$sidebar_id] ) : '-1'; // position of widget in sidebar
	$control = isset($registered_widget_controls[$widget_id]) ? $registered_widget_controls[$widget_id] : array();
	$widget = $registered_widgets[$widget_id];

	$id_format = $widget['id'];
	$widget_number = isset($control['params'][0]['number']) ? $control['params'][0]['number'] : '';
	$id_base = isset($control['id_base']) ? $control['id_base'] : $widget_id;
	$multi_number = isset($sidebar_args['_multi_num']) ? $sidebar_args['_multi_num'] : '';
	$add_new = isset($sidebar_args['_add']) ? $sidebar_args['_add'] : '';

	$query_arg = array( 'editwidget' => $widget['id'] );
	if ( $add_new ) {
		$query_arg['addnew'] = 1;
		if ( $multi_number ) {
			$query_arg['num'] = $multi_number;
			$query_arg['base'] = $id_base;
		}
	} else {
		$query_arg['sidebar'] = $sidebar_id;
		$query_arg['key'] = $key;
	}

	// We aren't showing a widget control, we're outputting a template for a multi-widget control
	if ( isset($sidebar_args['_display']) && 'template' == $sidebar_args['_display'] && $widget_number ) {
		// number == -1 implies a template where id numbers are replaced by a generic '__i__'
		$control['params'][0]['number'] = -1;
		// with id_base widget id's are constructed like {$id_base}-{$id_number}
		if ( isset($control['id_base']) )
			$id_format = $control['id_base'] . '-__i__';
	}

	$registered_widgets[$widget_id]['callback'] = $registered_widgets[$widget_id]['_callback'];
	unset($registered_widgets[$widget_id]['_callback']);

	$widget_title = strip_tags( $sidebar_args['widget_name'] );

	echo $sidebar_args['before_widget']; ?>
	<div class="widget-top">
	<div class="widget-title">
    	<h4><?php echo $widget_title ?><span class="in-widget-title"></span></h4>
    </div>
	</div>
<?php
	echo $sidebar_args['after_widget'];
	return $sidebar_args;
}


function sidebar_description( $id ) {
	if ( !is_scalar($id) )
		return;

	global $registered_sidebars;

	if ( isset($registered_sidebars[$id]['description']) )
		return $registered_sidebars[$id]['description'];
}

function dynamic_sidebar($index = 1) {
	global $registered_sidebars, $registered_widgets;

	if ( is_int($index) ) {
		$index = "sidebar-$index";
	} else {
		$index = sanitize_title($index);
		foreach ( (array) $registered_sidebars as $key => $value ) {
			if ( sanitize_title($value['name']) == $index ) {
				$index = $key;
				break;
			}
		}
	}

	$data_sidebars_widgets = get_sidebars_widgets();
	
	if ( empty( $data_sidebars_widgets ) )
		return false;

	if ( empty($registered_sidebars[$index]) || !array_key_exists($index, $data_sidebars_widgets) || !is_array($data_sidebars_widgets[$index]) || empty($data_sidebars_widgets[$index]) )
		return false;

	$sidebar = $registered_sidebars[$index];

	$did_one = false;
	foreach ( (array) $data_sidebars_widgets[$index] as $id ) {

		if ( !isset($registered_widgets[$id]) ) continue;

		$params = array_merge(
			array( array_merge( $sidebar, array('widget_id' => $id, 'widget_name' => $registered_widgets[$id]['name']) ) ),
			(array) $registered_widgets[$id]['params']
		);

		// Substitute HTML id and class attributes into before_widget
		$classname_ = '';
		foreach ( (array) $registered_widgets[$id]['classname'] as $cn ) {
			if ( is_string($cn) )
				$classname_ .= '_' . $cn;
			elseif ( is_object($cn) )
				$classname_ .= '_' . get_class($cn);
		}
		$classname_ = ltrim($classname_, '_');
		$params[0]['before_widget'] = sprintf($params[0]['before_widget'], $id, $classname_);

		$params = apply_filters( 'dynamic_sidebar_params', $params );

		$callback = $registered_widgets[$id]['callback'];

		do_action( 'dynamic_sidebar', $registered_widgets[$id] );

		if ( is_callable($callback) ) {
			call_user_func_array($callback, $params);
			$did_one = true;
		}
	}

	return $did_one;
}

function get_sidebars_active($index = 1) {
	global $registered_sidebars, $registered_widgets;

	if ( is_int($index) ) {
		$index = "sidebar-$index";
	} else {
		$index = sanitize_title($index);
		foreach ( (array) $registered_sidebars as $key => $value ) {
			if ( sanitize_title($value['name']) == $index ) {
				$index = $key;
				break;
			}
		}
	}

	$data_sidebars_widgets = get_sidebars_widgets();
	
	if ( empty( $data_sidebars_widgets ) )
		return false;

	if ( empty($registered_sidebars[$index]) || !array_key_exists($index, $data_sidebars_widgets) || !is_array($data_sidebars_widgets[$index]) || empty($data_sidebars_widgets[$index]) )
		return false;
		
	if( count($data_sidebars_widgets[$index]) > 0 && get_sidebar_action( $index ) ) 
		return true;
}

function get_sidebar_action( $i ){
	global $db;
	
	$c = get_query_var('com');
	if( $c && $c != 'page' ){	
		$c = filter_txt( $c );
		
		$c = esc_sql( $c );
		$i = esc_sql( $i );
		
		$sidebar_action = get_option('sidebar_actions');
		$sidebar_action = json_decode( $sidebar_action );
		$sidebar_action_cout = count( (array) $sidebar_action->$c );
	
		if( $c && $sidebar_action_cout > 0 ):
			foreach( $sidebar_action->$c as $sidebar_id => $status ){
				if( $sidebar_id == $i )
				return ( $status == 0 ) ? false : true;
			}
		else:
			return true;
		endif;
	}else{	
		return true;
	}
	
	
}

function get_sidebars_widgets($deprecated = true) {

	global $registered_widgets, $_sidebars_widgets, $sidebars_widgets;

	// If loading from front page, consult $_sidebars_widgets rather than options
	// to see if convert_widget_settings() has made manipulations in memory.
	if ( !$_GET['admin'] ) {
		if ( empty($_sidebars_widgets) )
			$_sidebars_widgets = get_option_array_widget();

		$sidebars_widgets = $_sidebars_widgets;
	} else {
		$sidebars_widgets = get_option_array_widget();
	}
	
	$sidebars_widgets = (array) $sidebars_widgets;

	if ( is_array( $sidebars_widgets ) && isset($sidebars_widgets['array_version']) )
		unset($sidebars_widgets['array_version']);
		

	$sidebars_widgets = apply_filters('sidebars_widgets', $sidebars_widgets);
	return $sidebars_widgets;
}

// register theme

function register_sidebar($args = array()) {
	global $registered_sidebars;

	$i = count($registered_sidebars) + 1;

	$defaults = array(
		'name' => sprintf('Sidebar %d', $i ),
		'id' => "sidebar-$i",
		'description' => '',
		'class' => '',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => "</li>\n",
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => "</h2>\n",
	);

	$sidebar = parse_args( $args, $defaults );

	$registered_sidebars[$sidebar['id']] = $sidebar;

	//add_theme_support('widgets');

	do_action( 'register_sidebar', $sidebar );

	return $sidebar['id'];
}

function register_sidebar_widget($id, $name, $output_callback) {
	global $registered_widgets, $registered_widget_controls, $_deprecated_widgets_callbacks;

	$id = strtolower($id);

	if ( empty($output_callback) ) {
		unset($registered_widgets[$id]);
		return;
	}

	$id_base = _get_widget_id_base($id);
	if ( in_array($output_callback, $_deprecated_widgets_callbacks, true) && !is_callable($output_callback) ) {
		if ( isset($registered_widget_controls[$id]) )
			unset($registered_widget_controls[$id]);

		return;
	}

	$options = array('classname' => $output_callback);
	$widget = array(
		'name' => $name,
		'id' => $id,
		'callback' => $output_callback,
		'params' => array_slice(func_get_args(), 4)
	);
	$widget = array_merge($widget, $options);

	if ( is_callable($output_callback) && ( !isset($registered_widgets[$id]) || did_action( 'widgets_init' ) ) ) {
		do_action( 'register_sidebar_widget', $widget );
		$registered_widgets[$id] = $widget;
	}
}