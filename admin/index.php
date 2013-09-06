<?php
/*
 * @copyright	Copyright (C) 2010 Open Source, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
 
/**
 * melakukan penjegahan pengaksesan ke direktori tsb.
 *
 */

if (!defined("_iEXEC")):
 $base_url = '../#!';

 if (!headers_sent()){ 
 		//header('HTTP/1.1 404 Not Found');
        header('Location: '.$base_url); exit;
 }else{ 
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$base_url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$base_url.'" />';
        echo '</noscript>'; exit;
 }

endif;
?>