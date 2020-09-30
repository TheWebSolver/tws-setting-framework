<?php
/**
 * Plugin Name:     The Web Solver Setting Framework
 * Plugin URI:      http://github.com/thewebsolver/tws-setting-framework
 * Description:     WordPress core Settings API framework
 * Version:         1.0
 * Author:          Shesh Ghimire
 * Author URI:      https://shesh.com.np
 * Text Domain:     tws-core
 * Domain Path:     /lang
 * License:         GPLv2 or later REVIEW: Check plugin license
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.txt
 * This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along
    with this program; if not, visit https://www.gnu.org/licenses/
 * 
 * @package         tws-core
 * 
 * -----------------------------------
 * DEVELOPED-MAINTAINED-SUPPPORTED BY
 * -----------------------------------
 * ███      ███╗   ████████████████
 * ███      ███║            ██████
 * ███      ███║       ╔══█████
 *  ████████████║      ╚█████
 * ███║     ███║     █████
 * ███║     ███║   █████
 * ███║     ███║   ████████████████╗
 * ╚═╝      ╚═╝    ════════════════╝
 */

// exit if file is accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

/**
 * defines main menu slug.
 * 
 * TODO: Replace menu slug
 * remove this constant that is used in every file 
 * if you are going to use different menu. 
 * 
 * Hint: Use search and replace. easy-peasy!!!
 */
define( 'HZFEX_SETTING_FRAMEWORK_MENU', 'tws_setting_framework' );

// defines this Framework plugin debug mode.
define( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE', false );

// define plugin url
if( ! defined ( 'HZFEX_Setting_Framework_Url' ) ) define( 'HZFEX_Setting_Framework_Url', plugin_dir_url( __FILE__ ) );

// define plugin path
if( ! defined ( 'HZFEX_Setting_Framework_Path' ) ) define( 'HZFEX_Setting_Framework_Path', plugin_dir_path( __FILE__ ) );

// define plugin version
if( ! defined( 'HZFEX_Setting_Framework_Version' ) ) define( 'HZFEX_Setting_Framework_Version', '1.0' );

// require files. !!! IMPORTANT !!! 
require_once 'setting/setting-api.php';
require_once 'setting/setting-component.php';

/**
 * NOTE: Example codes and files
 * 
 * TODO: Remove exmaple files and codes
 * 
 * Remove everything below here if you are going to:
 * - use your own main menu (instead of "The Web Solver")
 * - use your own child-class files
 * - use your own stylesheet
 * 
 * HINT: don't only remove codes here,
 * delete all included files and folders also.
 */
add_action( 'after_setup_theme', 'setup_plugin' );
function setup_plugin() {
   /**
    * Include example child-class files
    *
    * NOTE: the file included first comes first in main menu.
    */
   include_once 'templates/without-fields.php';
   include_once 'templates/with-fields.php';
   
   // register stylesheet.
   wp_register_style( 'hzfex_setting_page_style', plugin_dir_url( __FILE__ ) . 'assets/style.css', HZFEX_Setting_Framework_Version );
}
add_action( 'admin_menu', 'tws_setting_framework_main_menu' );
add_action( 'admin_menu', 'tws_setting_framework_remove_main_submenu', 999 );
function tws_setting_framework_main_menu() {
   add_menu_page(
      __( 'The Web Solver', 'tws-core' ),
      __( 'The Web Solver', 'tws-core' ),
      'read',
      HZFEX_SETTING_FRAMEWORK_MENU,
      'tws_setting_framework_main_page',
      '',
      2.1008
   );
   wp_enqueue_style( 'hzfex_setting_page_style' );
}
function tws_setting_framework_main_page() {
   include_once HZFEX_Setting_Framework_Path . 'templates/contents/welcome.php';
};
function tws_setting_framework_remove_main_submenu() {
   remove_submenu_page( 'tws_setting_framework', 'tws_setting_framework' );
}