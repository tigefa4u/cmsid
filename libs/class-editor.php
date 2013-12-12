<?php 
/**
 * @fileName: class-editor.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;

final class wysywigEditor{
	
	private static $baseurl;
	private static $first_init;
	private static $settings = array();
	private static $editor_id;
	private static $setting;
	
	private function __construct() {}
	
	public static function settings($editor_id, $settings) {
		self::$baseurl = site_url();
		self::$editor_id = $editor_id;
		$setting = parse_args( $settings,  array(
			'lang' => 'id',
			'toolbar' => 'a77a',
			'css' => 'wym.css',
			'imageGetJson' => self::$baseurl .'/?request&load=libs/ajax/image.php',
			'fileUpload' => self::$baseurl .'/?request&load=libs/ajax/file-upload.php',	
			'imageUpload' => self::$baseurl .'/?request&load=libs/ajax/image-upload.php',
			'linkFileUpload' => self::$baseurl .'/?request&load=libs/ajax/file_link_upload.php'			
			) );
		return $setting;
	}
	
	public static function editor_settings($editor_id, $setting){
		self::$first_init = $setting;
		$baseurl = self::$baseurl.'/libs/';
		
		$wInit = '';
		$wInit.= "<link rel='stylesheet' href='{$baseurl}js/redactor/css/redactor.css' />";
		$wInit.= "<script type='text/javascript' src='{$baseurl}js/redactor/redactor.js'></script>";		
		$wInit.= "<script type=\"text/javascript\">\n";
		$wInit.= "$(document).ready(function(){\n";
		
		$qtInit = "";
		if ( !empty(self::$first_init) ) {
			foreach ( self::$first_init as $options_id => $options ) {
				$qtInit .= "'$options_id':'{$options}',\n";
			}
			$qtInit = "$('#".self::$editor_id."').redactor({\n" . trim($qtInit, ",\n") . "\n});\n";
		} else {
			$qtInit = '{}';
		}		
		
		$wInit.= $qtInit;
		$wInit.= "});\n";
		$wInit.= "</script>\n";
		
		return $wInit;
	}	
	
	public static function editor( $content = '<p>&nbsp;</p>', $editor_id, $setting_area, $setting_js ) {		
		
		$editor_class = ' class="' . trim( $setting_area['editor_class'] . 'editor-area' ) . '"';
		$editor_style = ' style="' . (empty($setting_area['editor_style']) ? '' : $setting_area['editor_style']) . '"';
		//$content = (empty($content) ? '&lt;p&gt;&nbsp;&lt;/p&gt;' : $content);
		$content = empty($content) ? '' : $content;
		$rows = ' rows="' . (int) $setting_area['editor_rows'] . '"';
		
		$content = apply_filters('the_editor_content', $content);
		$the_editor = apply_filters('the_editor', '<textarea' . $editor_class . $editor_style . $rows . ' cols="40" name="' . $setting_area['editor_name'] . '" id="' . $editor_id . '">'.$content.'</textarea>');
		
		$setting = self::settings($editor_id, $setting_js);
		print self::editor_settings($editor_id, $setting);		
		print $the_editor;
	}	
	
}


