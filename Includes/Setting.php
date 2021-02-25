<?php // phpcs:ignore WordPress.NamingConventions
/**
 * TheWebSolver\Core\Setting\Plugin class.
 *
 * Handles plugin initialization.
 *
 * @package TheWebSolver\Core\Setting_Framework\Class
 * @version 2.0
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

namespace TheWebSolver\Core\Setting;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * The Web Solver Setting Framework Plugin class.
 *
 * @class TheWebSolver\Core\Setting\Plugin
 *
 * @since 1.0
 */
final class Plugin {
	/**
	 * Plugin version.
	 *
	 * @var string
	 *
	 * @since 2.0
	 */
	public $version = '2.0';

	/**
	 * Plugin args.
	 *
	 * @var array
	 *
	 * @since 2.0
	 */
	public $args;

	/**
	 * Menu hook suffix.
	 *
	 * @var string
	 *
	 * @since 2.0
	 */
	public $hook_suffix;

	/**
	 * Boot plugin.
	 *
	 * @return Plugin
	 *
	 * @since 2.0
	 * @static
	 */
	public static function boot(): Plugin {
		static $instance;
		if ( ! is_a( $instance, get_class() ) ) {
			$instance = new self();
			$instance->define_constants();
			$instance->init();
		}
		return $instance;
	}

	/**
	 * Define plugin constants.
	 *
	 * @since 2.0
	 */
	private function define_constants() {
		// Define plugin debug mode. DEBUG: set to true when needed.
		// TWS Core plugin already defines it.
		if ( ! defined( 'HZFEX_DEBUG_MODE' ) ) {
			define( 'HZFEX_DEBUG_MODE', false );
		}

		define( 'HZFEX_SETTING', __( 'The Web Solver Setting Framework', 'tws-setting' ) );
		define( 'HZFEX_SETTING_URL', plugin_dir_url( HZFEX_SETTING_FILE ) );
		define( 'HZFEX_SETTING_BASENAME', plugin_basename( HZFEX_SETTING_FILE ) );
		define( 'HZFEX_SETTING_PATH', plugin_dir_path( HZFEX_SETTING_FILE ) );
		define( 'HZFEX_SETTING_VERSION', $this->version );
		define( 'HZFEX_SETTING_SCOPE', 'framework' );
		define( 'HZFEX_SETTING_MENU', 'tws_setting' );

		// Set args.
		$this->args = array(
			'id'        => basename( HZFEX_SETTING_BASENAME, '.php' ),
			'name'      => HZFEX_SETTING,
			'version'   => HZFEX_SETTING_VERSION,
			'activated' => in_array( HZFEX_SETTING_BASENAME, get_option( 'active_plugins' ), true ),
			'loaded'    => in_array( HZFEX_SETTING_BASENAME, get_option( 'active_plugins' ), true ),
			'scope'     => HZFEX_SETTING_SCOPE,
		);
	}

	/**
	 * Include files, perform WordPress actions and hooks.
	 *
	 * @since 2.0
	 */
	private function init() {
		// Require necessary plugin files.
		require_once __DIR__ . '/Source/Options.php';
		require_once __DIR__ . '/Source/Container.php';

		include_once HZFEX_SETTING_PATH . 'setting/Page_Interface.php';
		include_once HZFEX_SETTING_PATH . 'setting/Page.php';
		include_once HZFEX_SETTING_PATH . 'setting/Test.php';

		require_once __DIR__ . '/API/API.php';

		// Example files. delete if not needed.
		// $this->include_examples(); //phpcs:ignore -- Valid comment OK.

		// Register this plugin as extension to TWS Core.
		// Using inside hook so it always fire after core plugin is loaded.
		add_action( 'hzfex_core_loaded', array( $this, 'register' ) );

		// Add settings menu and remove submenu created by the main menu.
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_menu', array( $this, 'remove_main_submenu' ), 999 );
	}

	/**
	 * Adds main setting menu.
	 *
	 * @since 2.0
	 */
	public function add_menu() {
		if ( ! $this->menu_exists( HZFEX_SETTING_MENU ) ) {
			$this->hook_suffix = add_menu_page(
				__( 'The Web Solver', 'tws-setting' ),
				__( 'The Web Solver', 'tws-setting' ),
				'read',
				HZFEX_SETTING_MENU,
				'', // 404 error if no callback function passed.
				'',
				3.1008
			);
		}
	}

	/**
	 * Removes the default submenu page created by main menu.
	 *
	 * This resolves 404 error when clicking on main menu page.\
	 * when there is no callback function passed to it.
	 *
	 * @since 2.0
	 */
	public function remove_main_submenu() {
		if ( $this->menu_exists( HZFEX_SETTING_MENU ) ) {
			remove_submenu_page( HZFEX_SETTING_MENU, HZFEX_SETTING_MENU );
		}
	}

	/**
	 * Registers this plugin as an extension.
	 *
	 * Makes this plugin an extension of **The Web Solver Extended** plugin.
	 *
	 * @link TODO: add later.
	 *
	 * @since 2.0
	 */
	public function register() {
		if ( function_exists( 'tws_core' ) ) {
			tws_core()->extensions()->register( $this->args );
		}
	}

	/**
	 * Checks if main menu already exists.
	 *
	 * @param string $slug The main menu slug to look for.
	 *
	 * @return bool True if already exists, else false.
	 *
	 * @since 1.0
	 *
	 * @access public
	 */
	public function menu_exists( $slug ) {
		global $menu;

		// Get menu slugs from array.
		$menu_slugs = array_column( $menu, 2 );
		return in_array( $slug, $menu_slugs, true ) ? true : false;
	}

	/**
	 * Private constructor to prevent direct instantiation.
	 *
	 * @since 2.0
	 *
	 * @access private
	 */
	private function __construct() {}

	/**
	 * Settings page examples.
	 *
	 * ### NOTE: !!! MAYBE REMOVE EXAMPLE FILES AND CODES??? !!!
	 *
	 * Included files contain example codes to create submenu
	 * pages inside main menu with slug as `HZFEX_SETTING_MENU`.
	 * If you want to create your own menu/submenu pages, delete this.
	 * HINT: don't only remove this include file here,
	 * delete **_templates_** folder also so no messy leftovers.
	 *
	 * @return void
	 */
	private function include_examples() {
		include_once HZFEX_SETTING_PATH . 'templates/without-fields.php';
		include_once HZFEX_SETTING_PATH . 'templates/with-fields.php';
	}
}
