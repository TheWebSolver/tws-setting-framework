<?php
/**
 * Submenu page with menu title as "Welcome" under main menu "The Web Solver"
 * 
 * @since       1.0
 * 
 * @package     tws-core
 * @subpackage  framework
 * @category    wordpress-setting-api
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

namespace TheWebSolver\Plugin\Core\Framework;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

final class Without_Fields extends Setting_Component {

    /** Constructor */
    public function __construct() {
        parent::__construct( 
            [
                __CLASS__ => [
                    'menu_slug'     => '', // uses default if not set
                    'page_title'    => __( 'Welcome', 'tws-core' ),
                    'menu_title'    => __( 'Welcome', 'tws-core' ),
                    'cap'           => 'read', // wordpress user capability to view page and edit/update page section fields
                    'slug'          =>'tws_without_fields_page', // submenu page slug
                    'icon'          => HZFEX_Setting_Framework_Url . 'assets/graphics/files-icon.svg', // icon that displays on before page navigation title (files icon before page title Welcome)
                ]
            ]
        );
    }

    protected function sections() {
        $sections = [
            'welcome' => [
                'title'     => __( 'Getting Started', 'tws-core' ),
                'tab_title' => __( 'Getting Started', 'tws-core' ),
                'desc'      => 'Some description',
                'callback'  => [ $this, 'welcome_callback' ], // callback can be used this way
            ],
            'hooks'   => [
                'title'     => __( 'Hooks/Filters', 'tws-core' ),
                'tab_title' => __( 'Hooks & Filters', 'tws-core' ),
                'callback'  => function() { if( file_exists( HZFEX_Setting_Framework_Path. 'templates/contents/hooks-filters.php' ) ) include_once HZFEX_Setting_Framework_Path. 'templates/contents/hooks-filters.php'; }, // callback can be used this way also
            ],
            'recommendation' => [
                'title'      => __( 'Recommendation', 'tws-core' ),
                'tab_title'  => __( 'Recommended Setup', 'tws-core' ),
                'callback'   => function() { if( file_exists( HZFEX_Setting_Framework_Path . 'templates/contents/recommendations.php' ) ) include_once HZFEX_Setting_Framework_Path . 'templates/contents/recommendations.php'; },
            ],
            'tws_mixed_section' => [
                'title'         => __( 'This title is only visible when fields are set.', 'tws-core' ), // only shows when "fields" are set.
                'tab_title'     => __( 'Ready to Go?', 'tws-core' ), 
                'desc'          => sprintf( '<div>%1$s</div><div><small><em>%2$s <code>templates/with-fields.php</code></em></small></div>',
                __( 'This description is only visible when fields are set.', 'tws-core' ),
                __( 'Enabling the switch below (actually it is a checkbox field type with style customization) will instantiate another child class on file', 'tws-core' ),
            ), // only shows when "fields" are set.
                'callback'      => function() { echo 'This is just a callback like other three section tabs in this page.'; },
                'fields'        => [
                    'tws_enable_fields' => [
                        'label'     => __( 'Enable another child-class?', 'tws-core' ),
                        'desc'      => __( 'You should definitely enable this to test other types of input fields.', 'tws-core' ),
                        'type'      => 'checkbox',
                        'class'     => 'hz_switcher_control',
                        'default'   => 'off'
                    ],
                ],
            ],
        ];

        return $sections;
    }

    public function welcome_callback() {
        if( file_exists( HZFEX_Setting_Framework_Path. 'templates/contents/welcome.php' ) ) include_once HZFEX_Setting_Framework_Path. 'templates/contents/welcome.php';
    }
}

// initialize this submenu page.
new Without_Fields();