<?php
/**
 * Plugin Name:     The Web Solver Setting Framework
 * Plugin URI:      https://github.com/TheWebSolver/tws-setting-framework
 * Description:     WordPress core Settings API framework
 * Version:         1.0
 * Author:          Shesh Ghimire
 * Author URI:      https://www.linkedin.com/in/sheshgh/
 * Text Domain:     tws-core
 * License:         GNU General Public License v3.0 (or later)
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.txt
 * 
 * @package         tws-core
 * 
 * -----------------------------------
 * DEVELOPED-MAINTAINED-SUPPPORTED BY
 * -----------------------------------
 * ███║     ███╗   ████████████████
 * ███║     ███║   ═════════██████╗
 * ███║     ███║        ╔══█████═╝
 *  ████████████║      ╚═█████
 * ███║═════███║      █████╗
 * ███║     ███║    █████═╝
 * ███║     ███║   ████████████████╗
 * ╚═╝      ╚═╝    ═══════════════╝
 */

// exit if file is accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

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
 * TODO: !!! MAYBE REMOVE EXAMPLE FILES AND CODES??? !!!
 * 
 * Remove below include file `templates/init.php` if you are going to:
 * - use your own main menu (instead of "The Web Solver")
 * - use your own child-class files
 * - use your own stylesheet
 * 
 * HINT: don't only remove this include file here,
 * delete TEMPLATES folder also so no messy leftovers.
 */
include_once 'templates/init.php';