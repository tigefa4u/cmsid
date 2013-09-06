<?php 
/**
 * @fileName: admin.php
 * @dir: admin/
 */
if(!defined('_iEXEC')) exit;
?><!DOCTYPE html>
<html lang="en">  
<head>
<meta charset="utf-8"> 
<title><?php the_admin_title()?></title>

<meta name="description" content="">
<meta name="author" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0">    

<link href="libs/css/reset.css" rel="stylesheet" />
<link href="libs/css/element.css" rel="stylesheet" />
<link href="libs/css/forms.css" rel="stylesheet" />
<link href="libs/css/forms-select.css" rel="stylesheet" />
<link href="libs/css/style.css" rel="stylesheet" />
<link href="libs/css/aside.css" rel="stylesheet" />
<link href="libs/css/tiptip.css" rel="stylesheet" />
<link href="libs/css/gd.css" rel="stylesheet" />
<link href="libs/css/colors.css" rel="stylesheet" />
<link href="libs/css/table.css" rel="stylesheet" />
<link href="libs/css/css3-buttons.css" rel="stylesheet" />
<link href="libs/css/default.css" rel="stylesheet" />
<link href="libs/css/drop-shadow.css" rel="stylesheet" />
<link href="libs/css/oops.css" rel="stylesheet" />
<link href="libs/css/button.css" rel="stylesheet" />
<link href="libs/css/scroll.css" rel="stylesheet" />

<!--[if lte IE 8]>
<script src="libs/js/html5.js" type="text/javascript"></script>
<![endif]-->
<script src="libs/js/jquery.js"></script>
<script src="libs/js/expand.js"></script>
<script src="libs/js/jquery-ui.js"></script>
<script src="libs/js/jquery.json.min.js?v=2.2"></script>
<script src="libs/js/jquery.ata.js"></script>

<script src="libs/js/bootstrap-datepicker.js"></script>
<link href="libs/css/datepicker.css" rel="stylesheet"/>

<script src="libs/js/jquery.tiptip.js"></script>
<script src="libs/js/running-script.js"></script>
<script src="libs/js/widget-home.js"></script>

<script src="libs/js/redactor/redactor.js"></script>
<link href="libs/js/redactor/css/redactor.css" rel="stylesheet"/>

<script src="libs/js/dialog/pbscript.js"></script>
<link href="libs/js/dialog/dialog.css" rel="stylesheet"/>


<script src="libs/js/cekbox.js"></script>
<?php the_head_admin();?>
</head>

<body>

<div id="redactor_modal_overlay" style="display: none;"></div>
<div id="redactor_modal_overlay_loading" style="display: none;"></div>

<!--Show Dialog loading-->
<div id="redactor_modal_console"  class="redactor_modal redactor_modal_loading" style="height: auto;display: none; ">
<div id="redactor_modal_inner_loading">Sedang memperbaharui ....</div>
</div>
<!--End Dialog loading-->
<?php 
do_action('manager_top');
do_action('manager_header');
do_action('manager_content');
do_action('manager_footer');
?>

</body>
</html>