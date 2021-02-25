<?php // phpcs:ignore WordPress.NamingConventions
/**
 * TheWebSolver\Core\Setting\Option class
 *
 * @package TheWebSolver\Core\Setting_Framework\Option\Class\API
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
 * Setting Page class.
 *
 * @class TheWebSolver\Core\Setting\Options
 * @api
 */
class Option {
	/**
	 * WordPress Core Settings API class instance.
	 *
	 * Sets an instance of `Settings_API()` class
	 *
	 * @var Settings_API|null
	 *
	 * @since 1.0
	 *
	 * @access private
	 */
	private $setting;

	/**
	 * The WordPress admin menu page identifier.
	 *
	 * @var string
	 *
	 * @since 2.0
	 */
	protected $id;

	/**
	 * The parent slug to hook submenu into.
	 *
	 * @var string
	 *
	 * @since 2.0
	 */
	public $parent_slug;

	/**
	 * The WordPress admin menu page args.
	 *
	 * @var array
	 *
	 * @since 2.0
	 */
	public $page_args = array();

	/**
	 * The WordPress Menu Page Sections.
	 *
	 * @var array
	 *
	 * @since 2.0
	 */
	protected $sections = array();

	/**
	 * The WordPress Menu Page Sections.
	 *
	 * @var array
	 *
	 * @since 2.0
	 */
	protected $all_sections = array();

	/**
	 * The WordPress Menu Page Section's fields.
	 *
	 * @var array
	 *
	 * @since 2.0
	 */
	protected $fields = array();

	/**
	 * User capability to access setting page.
	 *
	 * @var string
	 *
	 * @since 2.0
	 */
	protected $capability;

	/**
	 * The WordPress Menu/Submenu page hook suffix.
	 *
	 * @var string
	 *
	 * @since 2.0
	 */
	protected $hook_suffix;

	/**
	 * Constructor.
	 *
	 * @param string $id The ID for menu/submenu.
	 * @param string $parent_slug The parent menu slug.
	 *
	 * @since 2.0
	 *
	 * @access public
	 */
	public function __construct( string $id, string $parent_slug = '' ) {
		$this->id          = $id;
		$this->parent_slug = '' !== $parent_slug ? $parent_slug : HZFEX_SETTING_MENU;
	}

	/**
	 * Sets menu args.
	 *
	 * @see function: `add_menu_page()`
	 *
	 * @param array $args Menu args.
	 * * @type `string` `parent_slug` - The parent menu slug, if this is a submenu page.
	 * * @type `string` `menu_slug`   - The menu page slug.
	 * * @type `string` `page_slug`   - The setting page slug for URL.
	 * * @type `string` `page_title`  - The menu page title.
	 * * @type `string` `menu_title`  - The menu title on admin menu.
	 * * @type `string` `icon`
	 * * @type `int`    `position`.
	 *
	 * @return Option
	 *
	 * @since 2.0
	 */
	public function set_page( array $args = array() ): Option {
		$this->page_args = $args;
		return $this;
	}

	/**
	 * Sets user capability who can access the page.
	 *
	 * @param string $capability The current user capability.
	 *
	 * @since 2.0
	 */
	public function set_capability( string $capability = 'manage_options' ): Option {
		$this->capability = $capability;
		return $this;
	}

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
	public function add_section( string $id, array $args ): Option {
		$this->all_sections[ $id ] = $args;
		return $this;
	}

	/**
	 * Sets setting page section.
	 *
	 * This will set the section data and override anything added with.\
	 * {@see @method `Option::add_section()`}
	 *
	 * @param array $args Setting page section args.
	 * @return Option
	 */
	public function set_sections( array $args ): Option {
		$this->sections = $args;
		return $this;
	}

	/**
	 * Sets admin menu.
	 *
	 * @since 2.0
	 */
	public function set_menu() {
		add_action( 'admin_menu', array( $this, 'add_menu' ), 99 );
	}

	/**
	 * Adds WordPress Menu.
	 *
	 * @since 2.0
	 */
	public function add_menu() {
		$args = $this->page_args;
		if ( '' === $this->parent_slug ) {
			$this->hook_suffix = add_menu_page(
				$args['page_title'],
				$args['menu_title'],
				$this->capability,
				$args['menu_slug'],
				array( $this, 'load_page_content' ),
				$args['icon'],
				$args['position']
			);
		} else {
			$this->hook_suffix = add_submenu_page(
				$this->parent_slug,
				$args['page_title'],
				$args['menu_title'],
				$this->capability,
				$args['menu_slug'],
				array( $this, 'load_page_content' ),
				$args['position']
			);
		}

		// Create Settings API instance.
		$this->setting = new Settings_API();

		// Gets Setting Fields.
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
	}

	/**
	 * Hooks and loads assets on each menu/submenu page.
	 *
	 * @since 1.0
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
	 * @since 1.0
	 */
	public function enqueue_scripts() {
		// core style/script.
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_media();
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'jquery' );

		// Registers setting page stylesheet.
		wp_enqueue_style( 'hzfex_setting_page_style', HZFEX_SETTING_URL . 'assets/style.css', array(), HZFEX_SETTING_VERSION );

		if ( ! wp_script_is( 'hzfex_select2', 'enqueued' ) ) {
			// Register select2 plugin scripts.
			wp_enqueue_script( 'hzfex_select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.min.js', array( 'jquery' ), '4.0.12', true );
		}
		if ( ! wp_style_is( 'hzfex_select2_style', 'enqueued' ) ) {
			// Register styles.
			wp_enqueue_style( 'hzfex_select2_style', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2.min.css', array(), '4.0.12' );
		}
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
	 * @since 1.0
	 * @since 2.0 Changed method name and code review.
	 */
	public function add_page_contents() {
		// Bail early if no section defined.
		if ( false === $this->valid_sections() ) {
			return;
		}

		?>
		<style>
			#adminmenu, #adminmenu .wp-submenu, #adminmenuback, #adminmenuwrap {
				width: 0 !important;
			}
		</style>
		<?php

		// phpcs:disable
		// var_dump( $this->fields );
		// var_dump( $this->all_sections );
		// var_dump( $this->validate_fields() );
		// echo '<pre>'; print_r( $this->get_sections() ); echo '</pre>';
		// echo '<pre>'; print_r( $this->valid_fields() ); echo '</pre>';
		// echo '<pre>'; print_r( $this->get_valid_fields() ); echo '</pre>';
		// phpcs:enable

		// Sets sections, fields for Settings API and register.
		$this->setting
			->set_sections( $this->get_sections() )
			->set_fields( $this->get_valid_fields() )
			->register_setting();
	}

	/**
	 * Gets valid fields.
	 *
	 * @return void
	 */
	private function get_valid_fields() {
		foreach ( $this->get_sections() as $key => $data ) {
			$fields[ $key ] = $data['fields'];
		}
		return $fields;
	}

	/**
	 * Sets fields data after validation.
	 *
	 * @return array Fields in an array.
	 *
	 * @since 1.0
	 * @since 2.0 code revised
	 */
	private function valid_fields() {
		// Bail early if no section defined.
		if ( false === $this->valid_sections() ) {
			return false;
		}

		$fields = array();

		foreach ( $this->valid_sections() as $key => $data ) {
			// Only continue if section has fields.
			if ( false === $data['fields'] ) {
				continue;
			}

			/**
			 * WPHOOK Filter - Extend each setting section fields
			 *
			 * NOTE: Use API instead.
			 *
			 * - Highly recommended to add all fields from API.
			 * - Useful to add new fields to an already existing section.
			 *
			 * @param string $key -> the section key where field should be added.
			 * @param array $data['fields'] -> an array of fields that belong to that section.
			 *
			 * @example If $key is set as `test_section`:
			 *
			 * add_filter( "hzfex_setting_test_section_fields", "add_new_fields" );
			 * function add_new_fields( $fields ) {
			 *      $fields['new_test_field'] = [
			 *          'label'             => 'New Test Field',
			 *          'desc'              => 'New Test Description',
			 *          'placeholder'       => 'Placeholder.... anything....',
			 *          'type'              => 'text',
			 *          'sanitize_callback  => 'sanitize_text_field',
			 *          'priority'          => 5
			 *      ];
			 *      return $fields; // this is required to return back all fields.
			 * }
			 */
			$fields[ $key ] = apply_filters( "hzfex_setting_{$key}_fields", $data['fields'] );

			/**
			 * Sorts fields by priority, if set, inside each section.
			 *
			 * NOTE: Plugin specific feature
			 * - This feature is only available in plugin and not in this framework.
			 *
			 * @link coming soon!!!
			 *
			 * @uses    sort_by_priority() Sorts array data by priority
			 */
			if ( function_exists( '\TheWebSolver\Core\sort_by_priority' ) ) {
				uasort( $fields[ $key ], '\TheWebSolver\Core\sort_by_priority' );
			}
		}
		return $fields;
	}

	/**
	 * Adds field to the section ID.
	 *
	 * @param string $id         The field ID.
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
	 * @return Option
	 */
	public function add_field( string $id, string $section_id, array $args ) {
		// Set fields property.
		$this->fields[ $id ]               = $args;
		$this->fields[ $id ]['section_id'] = $section_id;

		return $this;
	}

	/**
	 * Sets fields data after validation.
	 *
	 * @return array Fields in an array.
	 *
	 * @since 2.0
	 */
	private function validate_fields() {
		$fields = array();

		// Bail early if no section defined.
		if ( 0 === count( $this->fields ) ) {
			return $fields;
		}

		$fields = array_filter(
			$this->fields,
			function( $field ) {
				return isset( $this->all_sections[ $field['section_id'] ] );
			}
		);

		return $fields;
	}

	/**
	 * Prepares sections and it's data as content to display on menu page.
	 *
	 * @return array
	 *
	 * @since 2.0
	 */
	private function get_sections() {
		$sections = array();

		// phpcs:disable

		// Bail if no section data.
		if ( 0 === count( $this->all_sections ) ) {
			return $sections;
		}

		// Prepare section data.
		foreach ( $this->all_sections as $key => $s ) {
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
				'fields'     => array_filter( $this->validate_fields(), function( $field ) use ( $key ) {
					return $field['section_id'] === $key;
				} ),
			);
			// phpcs:enable
		}

		return $sections;
	}

	/**
	 * Sets sections data after validation.
	 *
	 * @return array/false Sections in an array, false if no section data
	 *
	 * @since 1.0
	 */
	private function valid_sections() {
		// Bail if no section data.
		if ( false === $this->has_section() ) {
			return false;
		}

		$sections = array();

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
				'fields'     => isset( $s['fields'] ) && ! empty( $s['fields'] ) ? $s['fields'] : false,
			);
		}
		return $sections;
	}

	/**
	 * Checks if section has data.
	 *
	 * @return array/false array of sections data if defined, else false
	 *
	 * @since 1.0
	 * @since 2.0 Changed method name and code review.
	 *
	 * @access private
	 */
	private function has_section() {
		return count( $this->sections ) < 1 ? false : $this->sections;
	}
}
