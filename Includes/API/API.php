<?php
/**
 * WordPress Settings Registration API.
 *
 * @package TheWebSolver\Core\Setting_Framework\API
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

namespace TheWebSolver\Register;

use TheWebSolver\Core\Setting\Container;
use TheWebSolver\Core\Setting\Test;
// phpcs:disable
/**
 * Adds WordPress admin page with contents.
 *
 * @param string $id
 * @param array $sections
 * @param string $parent_slug
 * @param array $args
 * @param string $capability
 *
 * @return void
 *
 * @since 2.0
 */
function setting (
    string $id,
    array $sections,
    string $parent_slug = '',
    array $args = [],
    string $capability = 'manage_options'
) {
    $id         = sanitize_title( $id );
    $page_title = ucwords( strtolower( str_replace( ['_', '-'], ' ', $id ) ) );
    $icon       = HZFEX_SETTING_URL . 'assets/graphics/gear-icon.svg';

    // Prepare vars from args.
    $menu_slug  = isset( $args['menu_slug'] ) && ! empty( $args['menu_slug'] ) ? $args['menu_slug'] : $id;
    $page_slug  = isset( $args['page_slug'] ) && ! empty( $args['page_slug'] ) ? $args['page_slug'] : $menu_slug;
    $page_title = isset( $args['page_title'] ) && ! empty( $args['page_title'] ) ? $args['page_title'] : $page_title;
    $menu_title = isset( $args['menu_title'] ) && ! empty( $args['menu_title'] ) ? $args['menu_title'] : $page_title;
    $position   = isset( $args['position'] ) && ! empty( $args['position'] ) ? $args['position'] : null;
    $icon       = isset( $args['icon'] ) && ! empty( $args['icon'] ) ? $args['icon'] : $icon;

    // Collect page args into an array.
    $page = [
        'page_title'    => $page_title,
        'menu_title'    => $menu_title,
        'menu_slug'     => $menu_slug,
        'page_slug'     => $page_slug,
        'position'      => $position,
        'icon'          => $icon
    ];

    $option = new Container( $id, $parent_slug );
    $option
    ->set_page( $page )
	->add_section( 'new_api_section', array(
		'title' => 'Testing API',
		'callback' => function() {
			echo 'Hello! this works';
		},
	) )
	->add_section( 'hooks_new', array(
		'capability' => 'read',
		'title'     => __( 'Hooks/Filters', 'tws-core' ),
		'tab_title' => __( 'Hooks & Filters', 'tws-core' ),
		'callback'  => function() { if( file_exists( HZFEX_SETTING_PATH. 'templates/contents/hooks-filters.php' ) ) include_once HZFEX_SETTING_PATH. 'templates/contents/hooks-filters.php'; }, // callback can be used this way also
	) )
	->add_field( 'api_test_field', 'new_api_section', array(
		'label'     => __( 'Enable another child-class?', 'tws-core' ),
		'desc'      => __( 'You should definitely enable this to test other types of input fields.', 'tws-core' ),
		'type'      => 'checkbox',
		'class'     => 'hz_switcher_control',
		'default'   => 'off',
		'priority'  => 12,
	) )
	->add_field( 'new_field_test_two', 'new_api_section', array(
		'label'      => 'Interface Field Label',
		'type'       => 'checkbox',
		'class'      => 'hz_switcher_control',
		'default'    => 'off',
		'priority'   =>15,
	) )
	->add_field( 'new_field_test_three', 'hooks_new', array(
		'label'      => 'Interface Field Label',
		'type'       => 'text',
		'class'      => 'widefat',
		'default'    => 'This is default text.',
		'sanitize_callback' => 'sanitize_text_field',
	) )
    // ->set_sections( $sections )
    ->set_capability( $capability )
    ->set_menu();
}

setting(
    'tws_api_option',
    [
        'new_api_section' => [
            // 'capability' => 'jpt',
            'title' => 'Testing API',
            'callback' => function() {
                echo 'Hello! this works';
            },
            'fields'        => [
                'api_test_field' => [
                    'label'     => __( 'Enable another child-class?', 'tws-core' ),
                    'desc'      => __( 'You should definitely enable this to test other types of input fields.', 'tws-core' ),
                    'type'      => 'checkbox',
                    'class'     => 'hz_switcher_control',
                    'default'   => 'off'
                ],
            ],
        ],
        'hooks_new'   => [
            'capability' => 'read',
            'title'     => __( 'Hooks/Filters', 'tws-core' ),
            'tab_title' => __( 'Hooks & Filters', 'tws-core' ),
            'callback'  => function() { if( file_exists( HZFEX_SETTING_PATH. 'templates/contents/hooks-filters.php' ) ) include_once HZFEX_SETTING_PATH. 'templates/contents/hooks-filters.php'; }, // callback can be used this way also
        ],
    ],
    HZFEX_SETTING_MENU
);

// add_action( 'hzfex_tws_api_option_loaded', function( $page ) {
//     var_dump( $page );
// } );

// $test = new Test();
// $test->set_id( 'testing_page_id' );
// $test->set_capability( 'manage_options' );
$section['test_interface_section'] = array(
	'title'       => 'Test Section',
	'tab_title'   => 'Test Section Tab Title',
	'description' => 'Test Section Description',
	'callback'    => function() { echo 'Hello There callback!'; },
);
$fields['interface_field_id'] = array(
	'section_id' => 'test_interface_section',
	'label'      => 'Interface Field Label',
	'type'       => 'checkbox',
	'class'      => 'hz_switcher_control',
);

// $test->set_menu();

// var_dump( $test->menu_args );
// var_dump( $test->parent_slug );
// var_dump( $test->hook_priority );
