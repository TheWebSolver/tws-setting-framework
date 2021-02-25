<?php // phpcs:ignore WordPress.NamingConventions
/**
 * TheWebSolver\Core\Setting\Plugin class.
 *
 * Handles plugin initialization.
 *
 * @package TheWebSolver\Core\Setting_Framework\Class
 * @since 2.0
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
 * Setting Page Abstract class.
 *
 * @class TheWebSolver\Core\Setting_Abstract
 */
abstract class Page implements Page_Interface {
	/**
	 * The WordPress admin menu page identifier.
	 *
	 * @var string
	 *
	 * @since 2.0
	 */
	protected $id;

	/**
	 * The WordPress admin menu page args.
	 *
	 * @var array
	 *
	 * @since 2.0
	 */
	public $menu_args = array();

	/**
	 * The parent slug to hook submenu into.
	 *
	 * @var string
	 *
	 * @since 2.0
	 */
	public $parent_slug;

	/**
	 * User capability to access setting page.
	 *
	 * @var string
	 *
	 * @since 2.0
	 */
	protected $capability;

	/**
	 * Setting page args.
	 *
	 * @var array
	 *
	 * @since 2.0
	 */
	protected $page = array();

	/**
	 * WordPress admin menu hook priority.
	 *
	 * @var int
	 *
	 * @since 2.0
	 */
	public $hook_priority = 10;

	/**
	 * The WordPress Menu/Submenu page hook suffix.
	 *
	 * @var string
	 *
	 * @since 2.0
	 */
	protected $hook_suffix;

	/**
	 * WordPress Core Settings API class instance.
	 *
	 * Sets an instance of `Settings_API()` class
	 *
	 * @var Settings_API|null
	 *
	 * @since 2.0
	 */
	protected $setting;

	/**
	 * The WordPress Menu Page Sections.
	 *
	 * @var array
	 *
	 * @since 2.0
	 */
	protected $sections = array();

	/**
	 * The WordPress Menu Page Section's fields.
	 *
	 * @var array
	 *
	 * @since 2.0
	 */
	protected $fields = array();

	/**
	 * Constructor.
	 *
	 * @since 2.0
	 */
	public function __construct() {
		$this->id = __( 'The Web Solver', 'tws-setting' );
	}

	/**
	 * Sets setting page identifier.
	 *
	 * @param string $id Page ID.
	 *
	 * @since 2.0
	 */
	abstract public function set_id( string $id );

	/**
	 * Sets menu args.
	 *
	 * @see function: `add_menu_page()`
	 *
	 * @param mixed $args Menu args.
	 * * @type `string` `parent_slug` - The parent menu slug, if this is a submenu page.
	 * * @type `string` `menu_slug`   - The menu page slug.
	 * * @type `string` `page_slug`   - The setting page slug for URL.
	 * * @type `string` `page_title`  - The menu page title.
	 * * @type `string` `menu_title`  - The menu title on admin menu.
	 * * @type `string` `icon`
	 * * @type `int`    `position`.
	 *
	 * @since 2.0
	 */
	public function set_menu_args( $args ) {
		$this->menu_args = $args;
	}

	/**
	 * Sets user capability who can access the page.
	 *
	 * @param string $capability The current user capability.
	 *
	 * @since 2.0
	 */
	abstract public function set_capability( string $capability );

	/**
	 * Sets setting page section.
	 *
	 * Section must be added in key/value pair.\
	 * The key will be the section ID and values will be section args.
	 *
	 * @param string $id   The setting page section ID.
	 * @param array  $args Setting page section args.
	 *
	 * Required args:
	 * * @type `string` `title`       - Section title.
	 * * @type `string` `tab_title`   - Section tab title.
	 * * @type `string` `description` - Section description.
	 * * @type `string` `capability`  - User's capability to access section.
	 * * @type `callable` `callback`  - Section callback function to display contents other than fields below section title.
	 */
	abstract public function add_section( string $id, array $args );

	/**
	 * Adds field to the section ID.
	 *
	 * @param string $section_id The section ID to add fields to.
	 * @param array  $args       The field args.
	 *
	 * Required Args:
	 * * @type `string` `id`                  - The field ID.
	 *
	 * Common args:
	 * * @type `string` `label`               - The field display name.
	 * * @type `string` `description`         - The field description to display.
	 * * @type `string` `placeholder`         - The field placeholder value.
	 * * @type `mixed`  `default`             - The field default value.
	 * * @type `string` `type`                - The field type attribute.
	 * * @type `string` `class`               - The field class attribute.
	 * * @type `callable` `sanitize_callabck` - The callback function for field sanitization.
	 *
	 * Number field:
	 * * @type `int` `min`                    - Minimum number value.
	 * * @type `int` `max`                    - Maximum number value.
	 * * @type `int` `step`                   - Step of increment.
	 *
	 * Radio & Select/Multi-select field:
	 * * @type `array` `options`              - Field options.
	 *
	 * Textarea field:
	 * * @type `int` `rows` - The number of rows to define.
	 *
	 * @return void
	 */
	abstract public function add_field( string $section_id, array $args );

	/**
	 * Prepares data from menu args.
	 *
	 * @since 2.0
	 */
	public function prepare_menu() {
		// Prepare vars from args.
		$page_title    = ucwords( strtolower( str_replace( array( '_', '-' ), ' ', $this->id ) ) );
		$menu_slug     = isset( $this->menu_args['menu_slug'] ) && ! empty( $this->menu_args['menu_slug'] ) ? $this->menu_args['menu_slug'] : $this->id;
		$page_slug     = isset( $this->menu_args['page_slug'] ) && ! empty( $this->menu_args['page_slug'] ) ? $this->menu_args['page_slug'] : $menu_slug;
		$page_title    = isset( $this->menu_args['page_title'] ) && ! empty( $this->menu_args['page_title'] ) ? $this->menu_args['page_title'] : $page_title;
		$menu_title    = isset( $this->menu_args['menu_title'] ) && ! empty( $this->menu_args['menu_title'] ) ? $this->menu_args['menu_title'] : $page_title;
		$position      = isset( $this->menu_args['position'] ) && ! empty( $this->menu_args['position'] ) ? $this->menu_args['position'] : 99;
		$icon          = isset( $this->menu_args['icon'] ) && ! empty( $this->menu_args['icon'] ) ? $this->menu_args['icon'] : '';
		$parent_slug   = isset( $this->menu_args['parent_slug'] ) && ! empty( $this->menu_args['parent_slug'] ) ? $this->menu_args['parent_slug'] : HZFEX_SETTING_MENU; // REVIEW: CHECK.
		$hook_priority = $parent_slug ? 9999 : 10;

		// Prepare menu page args.
		$page = array(
			'page_title' => $page_title,
			'menu_title' => $menu_title,
			'menu_slug'  => $menu_slug,
			'page_slug'  => $page_slug,
			'position'   => $position,
			'icon'       => $icon,
		);

		$this->menu_args     = $page;
		$this->parent_slug   = $parent_slug;
		$this->hook_priority = $hook_priority;
	}

	/**
	 * Hooks setting sections and it's fields.
	 *
	 * @since 1.0
	 */
	public function load_page_content() {
		?>
		<!-- HZFEX_Core Setting Framework API -->
		<div id="hzfex_setting_framework" class="hz_min_container hz_flx">
			<!-- hz_setting_section -->
			<div id="hz_setting_section" class="hz_lrg_content wrap">
				<?php
				/**
				 * WPHOOK: Filter -> display section tabs if page has single section?
				 *
				 * Controls whether or not to show section tabs if page has only one section.
				 *
				 * @var bool $show Whether to show single section tab nav or not.
				 *
				 * @since 2.0
				 *
				 * @example - usage:
				 *
				 * Shows tab even if only have one section.
				 * add_filter( 'hzfex_show_setting_section_tabs_if_single', '__return_true' );
				 */
				$show_nav = apply_filters( 'hzfex_show_setting_section_tabs_if_single', false );

				// Set no of sections to be defined to show navigation.
				$section_count = $show_nav ? 0 : 1;

				// Show section tabs if have atleast two sections.
				if ( count( $this->valid_sections() ) > $section_count ) {
					$this->setting->show_navigation();
				}

				// Set section form fields.
				$this->setting->show_forms();
				?>
			</div>
			<!-- #hz_setting_section -->
		</div>
		<!-- HZFEX_Core Setting Framework API end -->
		<?php
	}

	/**
	 * Hooks WordPress setting page with sections and fields.
	 *
	 * @since 2.0
	 */
	public function add_page_contents() {
		// Bail early if no section defined.
		if ( ! $this->valid_sections() ) {
			return;
		}
		?>
		<style>
			#adminmenu, #adminmenu .wp-submenu, #adminmenuback, #adminmenuwrap {
				width: 0 !important;
			}
		</style>
		<?php

		var_dump( $this->valid_sections() );
		var_dump( $this->fields );

		// Sets sections, fields for Settings API and register.
		$this->setting
		->set_sections( $this->valid_sections() )
		->set_fields( $this->valid_fields() )
		->register_setting();
	}

	/**
	 * Prepares sections and it's data as content to display on menu page.
	 *
	 * @return array
	 *
	 * @since 2.0
	 */
	private function contents() {
		$contents = array();
		// Bail early if no section defined.
		if ( false === $this->valid_sections() ) {
			return $contents;
		}

		// Iterate over each section and prepare section content.
		foreach ( $this->valid_sections() as $id => $args ) {
			$contents[ $id ] = array(
				'id'          => $id,
				'capability'  => $args['capability'],
				'title'       => $args['title'],
				'tab_title'   => $args['tab_title'],
				'description' => $args['description'],
				'callback'    => $args['callback'],
				'fields'      => $this->valid_fields()[ $id ],
			);
		}

		return $contents;
	}

	/**
	 * Sets fields data after validation.
	 *
	 * @return array Fields in an array.
	 *
	 * @since 2.0
	 */
	private function valid_fields() {
		$fields = array();
		// Bail early if no section defined.
		if ( false === $this->has_section() ) {
			return $fields;
		}

		$fields = array_filter(
			$this->fields,
			function( $field ) {
				return isset( $this->has_section()[ $field['section_id'] ] );
			}
		);

		return $fields;
	}

	/**
	 * Sets sections data after validation.
	 *
	 * @return array Sections in an array, false if no section data
	 *
	 * @since 1.0
	 *
	 * @access private
	 */
	public function valid_sections() {
		$sections = array();

		// Bail early if no section data.
		if ( ! $this->has_section() ) {
			return $sections;
		}

		// Prepare section data.
		foreach ( $this->has_section() as $key => $s ) {
			$cap = isset( $s['capability'] ) && ! empty( $s['capability'] ) ? $s['capability'] : $this->capability;

			// Only continue if current user has assigned capability.
			if ( ! array_key_exists( $cap, wp_get_current_user()->allcaps ) ) {
				continue;
			}

			$sections[ $key ] = array(
				'id'         => $key,
				'capability' => $cap,
				'title'      => isset( $s['title'] ) && ! empty( $s['title'] ) ? $s['title'] : '',
				'desc'       => isset( $s['desc'] ) && ! empty( $s['desc'] ) ? $s['desc'] : '',
				'callback'   => isset( $s['callback'] ) && ! empty( $s['callback'] ) ? $s['callback'] : '',
				'tab_title'  => isset( $s['tab_title'] ) && ! empty( $s['tab_title'] ) ? $s['tab_title'] : $s['title'],
				'fields'     => $this->valid_fields()[ $key ],
			);
		}
		return $sections;
	}

	/**
	 * Checks if section has data.
	 *
	 * @return array/false An array of sections data if defined, else false.
	 *
	 * @since 2.0
	 */
	public function has_section() {
		$sections = count( $this->sections ) === 0 ? false : $this->sections;
		return $sections;
	}

	/**
	 * Sets admin menu.
	 *
	 * @since 2.0
	 */
	public function set_menu() {
		$this->prepare_menu();
		$this->valid_fields();
		add_action( 'admin_menu', array( $this, 'add_menu' ), $this->hook_priority );
	}

	/**
	 * Adds WordPress Menu.
	 *
	 * @since 2.0
	 */
	public function add_menu() {
		if ( '' === $this->parent_slug ) {
			$this->hook_suffix = add_menu_page(
				$this->menu_args['page_title'],
				$this->menu_args['menu_title'],
				$this->capability,
				$this->menu_args['menu_slug'],
				array( $this, 'load_page_content' ),
				$this->menu_args['icon'],
				$this->menu_args['position'],
			);
		} else {
			$this->hook_suffix = add_submenu_page(
				$this->parent_slug,
				$this->menu_args['page_title'],
				$this->menu_args['menu_title'],
				$this->capability,
				$this->menu_args['menu_slug'],
				array( $this, 'load_page_content' ),
				$this->menu_args['position'],
			);
		}

		$this->setting = new Settings_API();

		// Get Setting Fields.
		require_once HZFEX_SETTING_PATH . 'setting/setting-fields.php';

		// Hooks on each menu/submenu page load.
		add_action( 'load-' . $this->hook_suffix, array( $this, 'load' ) );

		/**
		 * Filters user capability to manage each submenu page.
		 *
		 * Works in this order:
		 * - Checks if submenu page has sections.
		 * - Loops through all sections to get the page ID.
		 * - Adds filter for changing user capability to view/save/update options.
		 *
		 * @since 2.0 Used inline function to set capability.
		 */
		if ( $this->valid_sections() ) {
			foreach ( $this->valid_sections() as $section ) {
				add_filter(
					"option_page_capability_{$section['id']}",
					function( $cap ) use ( $section ) {
						return $section['capability'];
					}
				);
			}
		}

		// Registers each child-class page sections and fields.
		add_action( 'admin_init', array( $this, 'add_page_contents' ), 15 );

		var_dump( $this->capability );
	}

	/**
	 * Hooks and loads assets on each menu/submenu page.
	 *
	 * @since 1.0
	 *
	 * @access public
	 */
	public function load() {
		// enqueues styles and scripts.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		/**
		 * WPHOOK: Action -> Fires after loading of each option page.
		 *
		 * @since 2.0 Changed tag name.
		 */
		do_action( "hzfex_{$this->id}_loaded", $this );
	}

	/**
	 * Enqueues styles and scripts
	 *
	 * @return void
	 *
	 * @since 1.0
	 *
	 * @access public
	 */
	public function enqueue_scripts() {
		// Core style/script.
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_media();
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'jquery' );

		// Registers setting page stylesheet.
		wp_enqueue_style( 'hzfex_setting_page_style', HZFEX_SETTING_URL . 'assets/style.css', array(), tws_setting()->version );

		if ( ! wp_script_is( 'hzfex_select2', 'enqueued' ) ) {
			// Register select2 plugin scripts.
			wp_enqueue_script( 'hzfex_select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.min.js', array( 'jquery' ), '4.0.12', true );
		}
		if ( ! wp_style_is( 'hzfex_select2_style', 'enqueued' ) ) {
			// Register styles.
			wp_enqueue_style( 'hzfex_select2_style', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2.min.css', array(), '4.0.12' );
		}
	}
}
