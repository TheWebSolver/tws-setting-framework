<?php
/**
 * Setting Options
 * 
 * @since       2.1
 * 
 * @package     hzfex
 * @subpackage  class
 * @category    setting
 * 
 * --------------------------------
 * DEVELOPED AND CUSTOMIZED BY
 * --------------------------------
 * 
 * ███      ███╗   ████████████████
 * ███      ███║            ██████
 * ███      ███║       ╔══█████
 *  ████████████║      ╚█████
 * ███║     ███║     █████
 * ███║     ███║   █████
 * ███║     ███║   ████████████████╗
 * ╚═╝      ╚═╝    ════════════════╝ 
 */

namespace TheWebSolver\Plugin\Core\Framework;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

final class Test_Setting extends Setting_Component {

    /** Constructor */
    public function __construct() {
        parent::__construct( 
            [
                __CLASS__ => [
                    'page_title'    => __( 'Menu Test Setting', 'hzfex' ),
                    'menu_title'    => __( 'Working Setting', 'hzfex' ),
                    'cap'           => 'manage_options',
                    'slug'          =>'test_setting',
                    // 'icon'          => 'files',
                ]
            ]
        );
    }

    // Always override this method from child class to set page sections and fields.
    protected function sections() {
        $section = [
            'test_section' => [
                'title'     => __( 'General Setting', 'tws-core' ),
                'tab_title' => __( 'General', 'hzfex' ),
                'desc'      => sprintf( '<p>%1$s</p>', __( 'General Plugin Setting', 'tws-core' ) ),
                'fields'    => [
                    'test_one'   => [
                        'label'     => __( 'Test Field', 'hzfex' ),
                        'desc'      => __( 'This field is for testing purpose', 'tws-core' ),
                        'type'      => 'checkbox',
                        'class'     => 'hz_switcher_control',
                        'priority'  => 5
                    ],
                    // next field here
                ],
            ],
            'test_section_2' => [
                'title'     => __( 'Advanced Setting', 'tws-core' ),
                'tab_title' => __( 'Advanced', 'hzfex' ),
                'desc'      => sprintf( '<p>%1$s</p>', __( 'General Plugin Setting', 'tws-core' ) ),
                'fields'    => [
                    'test_two'   => [
                        'label'     => __( 'Test Field', 'hzfex' ),
                        'desc'      => __( 'This field is for testing purpose', 'tws-core' ),
                        'type'      => 'checkbox',
                        'class'     => 'hz_switcher_control',
                        'priority'  => 5,
                        'default'   => true
                    ],
                    'test_three'   => [
                        'label'     => __( 'third Field', 'hzfex' ),
                        'desc'      => __( 'Third field is for testing purpose', 'tws-core' ),
                        'type'      => 'checkbox',
                        'class'     => 'hz_switcher_control',
                        'priority'  => 999,
                        'default'   => true
                    ],
                    // next field here
                ],
            ],
            'test_three' => [
                'title'  => 'Trying',
            ]
            // new section here
        ];
        return $section;
    }
}

// instantiate class and set it to global $hzfex_classes variable
new Test_Setting();