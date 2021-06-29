<?php
/**
 * Plugin Name:       The Web Solver Setting Framework
 * Plugin URI:        https://github.com/TheWebSolver/tws-setting-framework
 * Description:       WordPress Settings (Options) API framework
 * Version:           2.1
 * Author:            Shesh Ghimire
 * Author URI:        https://www.linkedin.com/in/sheshgh/
 * Requires at least: 5.3
 * Requires PHP:      7.0
 * Text Domain:       tws-setting
 * License:           GNU General Public License v3.0 (or later)
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package           TheWebSolver\Core\Setting_Framework
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

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use TheWebSolver\Core\Setting\Plugin;

if ( ! defined( 'HZFEX_SETTING_FILE' ) ) {
	define( 'HZFEX_SETTING_FILE', __FILE__ );
}

require_once __DIR__ . '/Includes/Plugin.php';

if ( ! function_exists( 'tws_setting' ) ) {
	/**
	 * Main function to instantiate HZFEX_Setting_Framework class.
	 *
	 * @return Plugin
	 *
	 * @since 2.0
	 */
	function tws_setting(): Plugin {
		return Plugin::boot( __FILE__ );
	}
}

// Initializes the plugin.
tws_setting();
