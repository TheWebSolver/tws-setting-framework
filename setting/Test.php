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
 * Test class.
 */
final class Test extends Page implements Page_Interface {

	public function set_id( string $id ) {
		$this->id = $id;
	}

	public function set_capability( string $capability ) {
		$this->capability = $capability;
	}

	public function add_section( string $id, array $args ) {
		$this->sections[ $id ] = $args;
	}

	public function add_field( string $section_id, array $args ) {
		$this->fields[ $section_id ] = $args;
	}
}
