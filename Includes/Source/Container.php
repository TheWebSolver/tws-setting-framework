<?php // phpcs:ignore WordPress.NamingConventions
/**
 * Creates WordPress Admin Menu page with settings and/or contents.
 *
 * @package TheWebSolver\Core\Setting_Framework\Class\API
 * @since 2.0
 * @version 2.1 Deprecated "Container::set_sections" method.
 * @version 2.1 Added getters for id, hook suffix, capability and page title.
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

use WP_Error;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Creates WordPress Admin Menu page with settings and/or contents.
 *
 * @class TheWebSolver\Core\Setting\Container
 * @api
 */
class Container {
	/**
	 * WordPress Core Settings API class instance.
	 *
	 * Sets an instance of `Options()` class
	 *
	 * @var Options|null
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
	 * @since 2.1 Possible to set empty parent slug.
	 *
	 * @access public
	 */
	public function __construct( string $id, string $parent_slug = '' ) {
		$this->id          = $id;
		$this->parent_slug = $parent_slug;
	}

	/**
	 * Sets menu args.
	 *
	 * @see function: `add_menu_page()`
	 *
	 * @param array $args Menu args.
	 * * @type `string` `menu_slug`   - The menu page slug.
	 * * @type `string` `page_slug`   - The setting page slug for URL.
	 * * @type `string` `page_title`  - The menu page title.
	 * * @type `string` `menu_title`  - The menu title on admin menu.
	 * * @type `string` `icon`
	 * * @type `int`    `position`.
	 *
	 * @return Container
	 *
	 * @since 2.0
	 * @since 2.1 `menu_title` key can be empty.
	 */
	public function set_page( array $args = array() ): Container {
		// Prepare vars from args.
		$page_title = ucwords( strtolower( str_replace( array( '_', '-' ), ' ', $this->id ) ) );
		$menu_slug  = isset( $args['menu_slug'] ) && ! empty( $args['menu_slug'] ) ? $args['menu_slug'] : $this->id;
		$page_slug  = isset( $args['page_slug'] ) && ! empty( $args['page_slug'] ) ? $args['page_slug'] : $menu_slug;
		$page_title = isset( $args['page_title'] ) && ! empty( $args['page_title'] ) ? $args['page_title'] : $page_title;
		$menu_title = isset( $args['menu_title'] ) ? $args['menu_title'] : $page_title;
		$position   = isset( $args['position'] ) && ! empty( $args['position'] ) ? $args['position'] : 99;
		$icon       = isset( $args['icon'] ) && ! empty( $args['icon'] ) ? $args['icon'] : '';

		$this->hook_priority = $this->parent_slug ? 999 : 10;

		// Prepare menu page args.
		$page = array(
			'page_title' => $page_title,
			'menu_title' => $menu_title,
			'menu_slug'  => $menu_slug,
			'page_slug'  => $page_slug,
			'position'   => $position,
			'icon'       => $icon,
		);

		$this->page_args = $page;

		return $this;
	}

	/**
	 * Sets user capability who can access the page.
	 *
	 * @param string $capability The current user capability.
	 *
	 * @since 2.0
	 */
	public function set_capability( string $capability = 'manage_options' ): Container {
		$this->capability = $capability;
		return $this;
	}

	/**
	 * Adds setting page sections.
	 *
	 * This can be used multiple times to set different sections.
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
	public function add_section( string $id, array $args ): Container {
		$this->sections[ $id ] = $args;
		return $this;
	}

	/**
	 * Sets setting page sections.
	 *
	 * NOTE: This will set sections that overrides anything added with
	 * {@see @method `Container::add_section()`}.\
	 * Useful when only need to add a single section.
	 *
	 * @deprecated 2.1
	 *
	 * @param array $args Setting page section args.
	 *
	 * @return Container
	 *
	 * @todo remove on next version.
	 * @since 2.0
	 */
	public function set_sections( array $args ): Container {
		_deprecated_function( __METHOD__, '2.1', esc_html( get_class() ) . '::add_sections' );

		$error = new WP_Error(
			'section_id_not_set',
			sprintf(
				'<h3><code>%1$s</code> %2$s</h3>%3$s',
				__METHOD__,
				__( 'method is deprecated. Use <b>"add_section"</b> method instead.', 'tws-setting' ),
				__( '"id" key/value pair must be set in args when adding section using this method.', 'tws-setting' )
			),
			__( 'Section ID not set', 'tws-setting' )
		);

		$id = isset( $args['id'] ) && ! empty( $args['id'] ) ? $args['id'] : $error;

		if ( is_wp_error( $id ) ) {
			wp_die(
				wp_kses(
					$id->get_error_message(),
					array(
						'h3' => array(),
						'b'  => array(),
					)
				),
				esc_html( $id->get_error_data() )
			);
		}

		// Clear id from args.
		unset( $args['id'] );

		$this->sections[ $id ] = $args;
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
		$this->setting = new Options();

		// Hooks on each menu/submenu page load.
		add_action( 'load-' . $this->hook_suffix, array( $this, 'load' ) );

		/**
		 * Filters user capability to manage each section of the page.
		 *
		 * Works in this order:
		 * - Checks if submenu page has sections.
		 * - Loops through all sections to get the page ID.
		 * - Adds filter for changing user capability to view/save/update options.
		 *
		 * @since 2.0 Used closure (anonymous) function to set capability.
		 */
		if ( $this->is_valid_content() ) {
			foreach ( $this->get_sections() as $section ) {
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
		// Gets Setting Fields.
		require_once __DIR__ . '/Fields.php';

		// enqueues styles and scripts.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		/**
		 * WPHOOK: Action -> Fires after loading of each option page.
		 *
		 * @param $this Container Object.
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

		if ( ! wp_script_is( 'hzfex_admin_style', 'registered' ) ) {
			// Registers setting page stylesheet.
			wp_enqueue_style( 'hzfex_setting_page_style', HZFEX_SETTING_URL . 'assets/style.css', array(), HZFEX_SETTING_VERSION );
		}

		// Verify if WooCommerce Select2 already exists.
		if ( ! wp_script_is( 'wc-enhanced-select-js', 'registered' ) ) {
			if ( ! wp_script_is( 'hzfex_select2', 'registered' ) ) {
				// Register select2 plugin scripts.
				wp_register_script( 'hzfex_select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.min.js', array( 'jquery' ), '4.0.12', true );
			}
			if ( ! wp_style_is( 'hzfex_select2_style', 'registered' ) ) {
				// Register styles.
				wp_register_style( 'hzfex_select2_style', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2.min.css', array(), '4.0.12' );
			}

			// Only enqueue if no WooCommerce script found, or registered handle doesn't already exist.
			$field_types = array_column( $this->has_fields(), 'type' );
			if ( in_array( 'select', $field_types, true ) || in_array( 'multi-select', $field_types, true ) ) {
				wp_enqueue_script( 'hzfex_select2' );
				wp_enqueue_style( 'hzfex_select2_style' );
			}
		}
	}

	/**
	 * Hooks setting sections and it's fields.
	 *
	 * @since 1.0
	 */
	public function load_page_content() {
		/**
		 * WPHOOK: Action -> fires before setting framework display.
		 *
		 * @param @string $id The current container ID.
		 * @since 2.1
		 */
		do_action( 'hzfex_before_setting_page_framework', $this->id );
		?>
		<!-- HZFEX_Core Setting Framework API -->
		<div id="hzfex_setting_framework" class="hz_min_container hz_flx">
			<?php
			/**
			 * WPHOOK: Action -> fires before setting sections display.
			 *
			 * @param @string $id The current container ID.
			 * @since 2.1
			 */
			do_action( 'hzfex_before_setting_page_section', $this->id );
			?>
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
				if ( count( $this->sections ) > $section_count ) {
					$this->setting->show_navigation();
				}

				// Set section form fields.
				$this->setting->show_forms();
				?>
			</div>
			<!-- #hz_setting_section -->
			<?php
			/**
			 * WPHOOK: Action -> fires after setting sections display.
			 *
			 * @param @string $id The current container ID.
			 * @since 2.1
			 */
			do_action( 'hzfex_after_setting_page_section', $this->id );
			?>
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
		if ( ! $this->is_valid_content() ) {
			return;
		}

		// Sets sections, fields for Settings API and register.
		$this->setting
			->set_sections( $this->get_sections() )
			->set_fields( $this->get_valid_fields() )
			->register_setting();
	}

	/**
	 * Gets valid fields.
	 *
	 * @return array
	 */
	private function get_valid_fields() {
		$fields = array();
		foreach ( $this->get_sections() as $key => $data ) {
			$fields[ $key ] = $data['fields'];
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
	 * * @type `string`   `id`                    - The field ID.
	 *
	 * Common args:
	 * * @type `string`   `label`               - The field display name.
	 * * @type `string`   `description`         - The field description to display.
	 * * @type `string`   `placeholder`         - The field placeholder value.
	 * * @type `mixed`    `default`             - The field default value.
	 * * @type `string`   `type`                - The field type attribute.
	 * * @type `string`   `class`               - The field class attribute.
	 * * @type `callable` `sanitize_callabck`   - The callback function for field sanitization.
	 * * @type `int`      `priority`            - The field's position priority.
	 *
	 * Number field:
	 * * @type `int`      `min`                 - Minimum number value.
	 * * @type `int`      `max`                 - Maximum number value.
	 * * @type `int`      `step`                - Step of increment.
	 *
	 * Radio & Select/Multi-select field:
	 * * @type `array`    `options`             - Field options.
	 *
	 * Textarea field:
	 * * @type `int`      `rows`                - The number of rows to define.
	 *
	 * @return Container
	 */
	public function add_field( string $id, string $section_id, array $args ) {
		// Set fields property.
		$this->fields[ $id ]               = $args;
		$this->fields[ $id ]['section_id'] = $section_id;

		return $this;
	}

	/**
	 * Validates if content is a valid content.
	 *
	 * @return bool
	 *
	 * @since 2.0
	 * @since 2.1 Removed empty field check for showing section even if no fields.
	 */
	private function is_valid_content(): bool {
		return 0 < count( $this->sections );
	}

	/**
	 * Prepares sections and it's data.
	 *
	 * @return array
	 *
	 * @since 2.0
	 */
	private function get_sections(): array {
		$sections = array();

		// Prepare section data.
		foreach ( $this->sections as $key => $args ) {
			$cap = isset( $args['capability'] ) && ! empty( $args['capability'] ) ? $args['capability'] : $this->capability;

			// Only continue if current user has assigned capability.
			if ( ! array_key_exists( $cap, wp_get_current_user()->allcaps ) ) {
				continue;
			}

			$fields = array_filter(
				$this->has_fields(),
				function( $field ) use ( $key ) {
					return $field['section_id'] === $key;
				}
			);

			$sections[ $key ] = array(
				'id'          => $key,
				'capability'  => $cap,
				'title'       => isset( $args['title'] ) && ! empty( $args['title'] ) ? $args['title'] : '',
				'description' => isset( $args['desc'] ) && ! empty( $args['desc'] ) ? $args['desc'] : '',
				'callback'    => isset( $args['callback'] ) && ! empty( $args['callback'] ) ? $args['callback'] : '',
				'tab_title'   => isset( $args['tab_title'] ) && ! empty( $args['tab_title'] ) ? $args['tab_title'] : $args['title'],
				'fields'      => $fields,
			);
		}

		return $sections;
	}

	/**
	 * Sets fields data after validation.
	 *
	 * @return array Fields in an array.
	 *
	 * @since 2.0
	 */
	private function has_fields(): array {
		// Filter out fields that doesn't belong to any section.
		$fields = array_filter( $this->fields, array( $this, 'is_section_set' ) );

		uasort( $fields, array( $this, 'sort_fields' ) );

		return $fields;
	}

	/**
	 * Checks if field has section ID set.
	 *
	 * @param array $field The field to check for.
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */
	private function is_section_set( $field ): bool {
		return isset( $this->sections[ $field['section_id'] ] );
	}

	/**
	 * Sorts field position by it's priority.
	 *
	 * @param array $first The first field to compare.
	 * @param array $last The last field to compare.
	 *
	 * @return int
	 *
	 * @since 2.0
	 */
	private function sort_fields( $first, $last ) {
		if ( ! isset( $first['priority'], $last['priority'] ) ) {
			return 0;
		}

		if ( $first['priority'] === $last['priority'] ) {
			return 0;
		}

		return $first['priority'] < $last['priority'] ? -1 : 1;
	}

	/**
	 * Gets the container id.
	 *
	 * @return string
	 *
	 * @since 2.1
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Gets page hook suffix.
	 *
	 * @return string
	 *
	 * @since 2.1
	 */
	public function get_suffix() {
		return $this->hook_suffix;
	}

	/**
	 * Gets the user capability who can assess the page.
	 *
	 * @return string
	 *
	 * @since 2.1
	 */
	public function get_cap() {
		return $this->capability;
	}

	/**
	 * Gets page title.
	 *
	 * @return string
	 *
	 * @since 2.1
	 */
	public function title() {
		return $this->page_args['page_title'];
	}
}
