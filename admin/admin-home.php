<?php 
/**
 * @fileName: home.php
 * @dir: admin/templates/
 */
if(!defined('_iEXEC')) exit;

set_current_screen();
add_screen_option('layout_columns', array('max' => 4, 'default' => 2) );

dashboard_init();
dashboard_setup();
dashboard();