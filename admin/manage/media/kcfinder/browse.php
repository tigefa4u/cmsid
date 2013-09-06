<?php
/** This file is part of KCFinder project
  *
  *      @desc Browser calling script
  *   @package KCFinder
  *   @version 2.51
  *    @author Pavel Tzonkov <pavelc@users.sourceforge.net>
  * @copyright 2010, 2011 KCFinder Project
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://kcfinder.sunhater.com
  */
  
session_start();

error_reporting(0);

define('_iEXEC', true);;


$dir_name_file = dirname(__FILE__);
	
/** menentukan abs_path berdasarkan direktori file*/
if (DIRECTORY_SEPARATOR=='/') $absolute_path = $dir_name_file.'/'; 
else $absolute_path = str_replace('\\', '/',$dir_name_file).'/'; 

$absolute_path = str_replace('admin/manage/media/kcfinder/','',$absolute_path);
	  
if ( !defined( 'abs_path' ) ) define( 'abs_path',  $absolute_path );
	
/** menentukan libs berdasarkan direktori libs*/
define( 'libs', 'libs' );

require_once( abs_path . 'config.php' );
require_once( abs_path . libs . '/query.php' );
require_once( abs_path . libs . '/load.php' );	
require_once( abs_path . libs . '/version.php' );	
require_once( abs_path . libs . '/default-constants.php' );	
require_once( abs_path . libs . '/settings.php' );

global $login;

if(
ereg(basename (__FILE__), $_SERVER['PHP_SELF'])
&& $login->check() 
&& $login->level('admin') 
):

require "core/autoload.php";
$browser = new browser();
$browser->action();
else:
redirect();
endif;
?>
