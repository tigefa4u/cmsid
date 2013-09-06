<?php
/**
 * @file: codemirror.php
 * @dir: content/plugins
 */
 
/*
Plugin Name: Code Mirror
Plugin URI: http://cmsid.org/#
Description: Plugin bla bla bla
Author: Eko Azza
Version: 1.1.0
Author URI: http://cmsid.org/
*/ 

if(!defined('_iEXEC')) exit;

add_action('the_head_admin', 'codemirror_register');
add_action('the_head_admin', 'codemirror_control_js');
function codemirror_register() {
	?>
	<link rel="stylesheet" href="<?php echo plugins_url();?>/codemirror/codemirror.css">
	<script src="<?php echo plugins_url();?>/codemirror/jquery.codemirror.js"></script>
	<script src="<?php echo plugins_url();?>/codemirror/codemirror.js"></script>
	<script src="<?php echo plugins_url();?>/codemirror/util/foldcode.js"></script>
	<script src="<?php echo plugins_url();?>/codemirror/mode/htmlmixed.js"></script>
	<script src="<?php echo plugins_url();?>/codemirror/mode/xml.js"></script>
	<script src="<?php echo plugins_url();?>/codemirror/mode/javascript.js"></script>
	<script src="<?php echo plugins_url();?>/codemirror/mode/css.js"></script>
	<script src="<?php echo plugins_url();?>/codemirror/mode/clike.js"></script>
	<script src="<?php echo plugins_url();?>/codemirror/mode/php.js"></script>
	<?php
}

function codemirror_control_js() {
	if (isset($_GET['file'])) {
		$filename_to_edit = end(explode("/", $_GET['file']));
		$file = substr($filename_to_edit, stripos($filename_to_edit, '.')+1);
		$file = end(explode(".", $file));
		switch ($file) {
			case "php": $file = "application/x-httpd-php"; break;
			case "js" : $file = "text/javascript"; break;
			case "css": $file = "text/css"; break;
			case "xml": $file = "text/xml"; break;
		}	
	}
	else {
		$file = "application/x-httpd-php";
	}
		
?>
	<script type="text/javascript">	
    $(function() {
	  	var foldFunc_html = CodeMirror.newFoldFunction(CodeMirror.tagRangeFinder);
      	var editor = $('#textcode').codemirror({
        	lineNumbers: true,
        	matchBrackets: true,
        	mode: "<?php echo $file ;?>",
        	indentUnit: 4,
        	indentWithTabs: true,
        	enterMode: "keep",
        	tabMode: "shift",
        	onGutterClick: foldFunc_html,
        	extraKeys: {"Ctrl-Q": function(cm){foldFunc_html(cm, cm.getCursor().line);}}
      	});
    });
	</script>
<?php
}