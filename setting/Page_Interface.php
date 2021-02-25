<?php // phpcs:ignore WordPress.NamingConventions
/**
 * TheWebSolver\Core\Setting\Plugin class.
 *
 * Settings Page Interface.
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
 * Setting page Interface.
 *
 * @interface TheWebSolver\Core\Setting_Interface
 */
interface Page_Interface {
	/**
	 * Sets setting page identifier.
	 *
	 * @param string $id Page ID.
	 *
	 * @since 2.0
	 */
	public function set_id( string $id );

	/**
	 * Sets menu args.
	 *
	 * @see function: `add_menu_page()`
	 *
	 * @param mixed $args Menu args.
	 * * @type `string` `menu_slug`
	 * * @type `string` `page_title`
	 * * @type `string` `menu_title`
	 * * @type `string` `capability`
	 * * @type `string` `slug`
	 * * @type `string` `icon`
	 * * @type `int`    `position`.
	 *
	 * @since 2.0
	 */
	public function set_menu_args( $args );

	/**
	 * Sets user capability who can access the page.
	 *
	 * @param string $capability The current user capability.
	 *
	 * @return void
	 *
	 * @since 2.0
	 */
	public function set_capability( string $capability );

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
	public function add_section( string $id, array $args );

	/**
	 * Adds field to the section ID.
	 *
	 * @param string $section_id  The section ID to add fields to.
	 * @param array  $args        The field args.
	 *
	 * Required Args:
	 * * @type `string` `id`          - The field ID.
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
	 * * @type `int` `min`  - Minimum number value.
	 * * @type `int` `max`  - Maximum number value.
	 * * @type `int` `step` - Step of increment.
	 *
	 * Radio & Select/Multi-select field:
	 * * @type `array` `options` - Field options.
	 *
	 * Textarea field:
	 * * @type `int` `rows` - The number of rows to define.
	 *
	 * @return void
	 */
	public function add_field( string $section_id, array $args );
}
