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
	public $version = '2.1';

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
	 * @param string $plugin_file Main plugin file if using normally.
	 *                            If used via composer, auto generates it.
	 *
	 * @return Plugin
	 *
	 * @since 2.0
	 * @static
	 */
	public static function boot( $plugin_file = '' ): Plugin {
		static $instance;
		if ( ! is_a( $instance, get_class() ) ) {
			if ( ! $plugin_file ) {
				$plugin_file = dirname( __FILE__ );
			}

			$instance = new self();
			$instance->define_constants( $plugin_file );
			$instance->init();
		}
		return $instance;
	}

	/**
	 * Define plugin constants.
	 *
	 * @param string $plugin_file The main plugin file.
	 *
	 * @since 2.0
	 */
	private function define_constants( $plugin_file ) {
		// Define plugin debug mode. DEBUG: set to true when needed.
		// TWS Core plugin already defines it.
		if ( ! defined( 'HZFEX_DEBUG_MODE' ) ) {
			define( 'HZFEX_DEBUG_MODE', false );
		}

		define( 'HZFEX_SETTING', __( 'The Web Solver Setting Framework', 'tws-setting' ) );
		define( 'HZFEX_SETTING_URL', plugin_dir_url( $plugin_file ) );
		define( 'HZFEX_SETTING_BASENAME', plugin_basename( $plugin_file ) );
		define( 'HZFEX_SETTING_PATH', plugin_dir_path( $plugin_file ) );
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
		// Register this plugin as extension to TWS Core.
		// Using inside hook so it always fire after core plugin is loaded.
		add_action( 'hzfex_core_loaded', array( $this, 'register' ) );

		// Add settings menu and remove submenu created by the main menu.
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_menu', array( $this, 'remove_main_submenu' ), 999 );

		/**
		 * Example files. delete if not needed. Uncomment to load example files.
		 *
		 * @deprecated 2.0
		 */
		// include_once HZFEX_SETTING_PATH . 'templates/init.php'; //phpcs:ignore -- Valid comment OK.
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
		if ( function_exists( '\tws_core' ) ) {
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
	 */
	private function __construct() {}
}
