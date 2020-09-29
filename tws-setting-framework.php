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

// defines this Framework plugin debug mode.
define( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE', false );

// define plugin url
if( ! defined ( 'HZFEX_Setting_Framework_Url' ) ) define( 'HZFEX_Setting_Framework_Url', plugin_dir_url( __FILE__ ) );

// define plugin version
if( ! defined( 'HZFEX_Setting_Framework_Version' ) ) define( 'HZFEX_Setting_Framework_Version', '1.0' );

// require files.
require_once 'setting/setting-api.php';
require_once 'setting/setting-component.php';

// example/test files
include_once 'templates/with-fields.php';

add_action( 'after_setup_theme', 'scripts' );
function scripts() {
   wp_register_style( 'hzfex_setting_page_style', plugin_dir_url( __FILE__ ) . 'assets/style.css', HZFEX_Setting_Framework_Version );
}