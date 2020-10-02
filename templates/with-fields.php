<?php
/**
 * Submenu page with menu title as "Fields Demo" under main menu "The Web Solver"
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

final class With_Fields extends Setting_Component {

    /** Constructor */
    public function __construct() {
        parent::__construct( 
            [
                __CLASS__ => [
                    'page_title'    => __( 'Fields Demo', 'tws-core' ),
                    'menu_title'    => __( 'Fields Demo', 'tws-core' ),
                    'cap'           => 'manage_options',
                    'slug'          =>'tws_with_fields_page',
                    // 'icon'          => 'files', // gear icon will be used if no URL provided here
                ]
            ]
        );
    }

    // Always override this method from child class to set page sections and fields.
    protected function sections() {
        $section = [
            'simple_section_id' => [
                'title'     => __( 'General Setting Section', 'tws-core' ),
                'tab_title' => __( 'General', 'tws-core' ),
                'desc'      => sprintf( '<p>%1$s</p>', __( 'This section demonstrates general setting fields.', 'tws-core' ) ),
                'fields'    => [
                    'text_field_id'        => [
                        'label'              => __( 'Text Field', 'tws-core' ),
                        'desc'               => __( 'Description for a simple text field', 'tws-core' ),
                        'type'               => 'text',
                        'placeholder'        => __( 'Placeholder text', 'tws-core' ),
                        'default'            => 'Text default',
                        'class'              => 'widefat', // WP default for 100% width
                        'sanitize_callback'  => 'sanitize_text_field', // IMPORTANT !!!
                    ],
                    'number_field_id'      => [
                        'label'              => __( 'Number Input Field', 'tws-core' ),
                        'desc'               => __( 'Number field with [min | max | step] options.', 'tws-core' ),
                        'type'               => 'number',
                        'placeholder'        => __( '0.5', 'tws-core' ),
                        'default'            => '0.7',
                        'class'              => 'widefat', // WP default for 100% width
                        'sanitize_callback'  => 'floatval', // IMPORTANT !!!
                        'min'                => 0,
                        'max'                => 1,
                        'step'               => 0.1,
                    ],
                    'checkbox_field_id'    => [
                        'label'              => __( 'Checkbox field', 'tws-core' ),
                        'desc'               => __( 'Check/Uncheck this field. Value is saved as "on" or "off"', 'tws-core' ),
                        'type'               => 'checkbox',
                        'default'            => 'off',
                        'class'              => '',
                        'sanitize_callback'  => 'sanitize_key', // IMPORTANT !!!
                    ],
                    'radio_field_id'         => [
                        'label'              => __( 'Radio button', 'tws-core' ),
                        'desc'               => __( 'Select one of the radio button', 'tws-core' ),
                        'type'               => 'radio',
                        'default'            => 'radio_three',
                        'class'              => '',
                        'options'            => [
                            'radio_one'      => __( 'Radio One', 'tws-core' ),
                            'radio_two'      => __( 'Radio Two', 'tws-core' ),
                            'radio_three'    => __( 'Radio Three', 'tws-core' ),
                        ],
                    ],
                    'select_field_id'         => [
                        'label'              => __( 'Select dropdown field', 'tws-core' ),
                        'desc'               => __( 'Select any option from dropdown', 'tws-core' ),
                        'type'               => 'select',
                        'default'            => 'select_two',
                        'class'              => 'widefat',
                        'options'            => [
                            'select_one'      => __( 'Select One', 'tws-core' ),
                            'select_two'      => __( 'Select Two', 'tws-core' ),
                            'select_three'    => __( 'Select Three', 'tws-core' ),
                        ],
                    ],
                    'textarea_field_id'    => [
                        'label'              => __( 'Text-area Field', 'tws-core' ),
                        'desc'               => __( 'Description for a simple text-area field with placeholder', 'tws-core' ),
                        'type'               => 'textarea',
                        'class'              => '',
                        'placeholder'        => 'Placeholder text for text-area field....',
                        'default'            => '',
                        'sanitize_callback'  => 'sanitize_textarea_field',
                        'rows'               => '8',
                    ],
                ],
            ],
            'advanced_section_id' => [
                'title'     => __( 'Advanced Setting Section', 'tws-core' ),
                'tab_title' => __( 'Advanced', 'tws-core' ),
                'desc'      => sprintf( '<p>%1$s</p>', __( 'This section demonstrates advanced setting fields', 'tws-core' ) ),
                'fields'    => [
                    'multi_checkbox_id'   => [
                        'label'     => __( 'Multi-checkbox Field', 'tws-core' ),
                        'desc'      => __( 'A multi-checkbox field where multiple options can be selected.', 'tws-core' ),
                        'type'      => 'multi_checkbox',
                        'class'     => '',
                        'default'   => [ 'checkbox_two' ], // default options value in an array
                        'options'            => [
                            'checkbox_one'      => __( 'Checkbox One', 'tws-core' ),
                            'checkbox_two'      => __( 'Checkbox Two', 'tws-core' ),
                            'checkbox_three'    => __( 'Checkbox Three', 'tws-core' ),
                        ],
                    ],
                    'multi_select_id'   => [
                        'label'     => __( 'Multi-select Field', 'tws-core' ),
                        'desc'      => __( 'A multi-select field where multiple options can be selected. Ctrl + click to select multiple.', 'tws-core' ),
                        'type'      => 'multi_select',
                        'class'     => 'widefat',
                        'default'   => [ 'select_one', 'select_three' ], // default option value in array
                        'options'            => [
                            'select_one'      => __( 'Select One', 'tws-core' ),
                            'select_two'      => __( 'Select Two', 'tws-core' ),
                            'select_three'    => __( 'Select Three', 'tws-core' ),
                        ],
                    ],
                    'wysiwyg_field_id'   => [
                        'label'         => __( 'Wysiwyg field', 'tws-core' ),
                        'desc'          => __( 'Wysiwyg field for advanced field editing.', 'tws-core' ),
                        'type'          => 'wysiwyg',
                        'class'         => 'widefat',
                        'default'       => __( 'Default text for wysiwyg field', 'tws-core' ),
                        'sanitize_callback'  => 'wp_kses_post', // IMPORTANT !!!
                    ],
                    'file_field_id'       => [
                        'label'         => __( 'File Field', 'tws-core' ),
                        'desc'          => __( 'This is a file field to select file uploaded.', 'tws-core' ),
                        'type'          => 'file',
                        'class'         => '',
                    ],
                    'color_field_id'    => [
                        'label'         => __( 'Color Picker', 'tws-core' ),
                        'desc'          => __( 'A color picker field to select color', 'tws-core' ),
                        'type'          => 'color',
                        'class'         => '',
                    ],
                    'password'          => [
                        'label'         => __( 'Password Field', 'tws-core' ),
                        'desc'          => __( 'A password input field', 'tws-core' ),
                        'type'          => 'password',
                    ]
                ],
            ],
            'customized_section_id' => [
                'title'                  => 'Customized Form Fields',
                'tab_title'              => __( 'Stylized Fields', 'tws-core' ),
                'desc'                   => __( 'Fields that have class applied to change the appearance', 'tws-core' ),
                'fields'                 => [
                    'checkbox_field_id'    => [
                        'label'              => __( 'Checkbox field', 'tws-core' ),
                        'desc'               => __( 'Check/Uncheck this field. Value is saved as "on" or "off"', 'tws-core' ),
                        'type'               => 'checkbox',
                        'default'            => 'on',
                        'class'              => 'hz_switcher_control', // makes checkbox switch
                        'sanitize_callback'  => 'sanitize_key', // IMPORTANT !!!
                    ],
                    'radio_field_id'         => [
                        'label'              => __( 'Radio button', 'tws-core' ),
                        'desc'               => __( 'Select one of the radio button', 'tws-core' ),
                        'type'               => 'radio',
                        'default'            => 'radio_two',
                        'class'              => 'hz_card_control', // makes radio card
                        'options'            => [
                            'radio_one'      => __( 'Radio One', 'tws-core' ),
                            'radio_two'      => __( 'Radio Two', 'tws-core' ),
                            'radio_three'    => __( 'Radio Three', 'tws-core' ),
                        ],
                    ],
                    'select_field_id'         => [
                        'label'              => __( 'Select dropdown field', 'tws-core' ),
                        'desc'               => __( 'Select any option from dropdown', 'tws-core' ),
                        'type'               => 'select',
                        'default'            => 'select_three',
                        'class'              => 'widefat hz_select_control', // adds select2
                        'options'            => [
                            'select_one'      => __( 'Select One', 'tws-core' ),
                            'select_two'      => __( 'Select Two', 'tws-core' ),
                            'select_three'    => __( 'Select Three', 'tws-core' ),
                        ],
                    ],
                    'multi_checkbox_id'   => [
                        'label'     => __( 'Multi-checkbox Field', 'tws-core' ),
                        'desc'      => __( 'A multi-checkbox field where multiple options can be selected.', 'tws-core' ),
                        'type'      => 'multi_checkbox',
                        'class'     => 'hz_switcher_control', // same as checkbox
                        'default'   => [ 'checkbox_three' ], // default options value in an array
                        'options'            => [
                            'checkbox_one'      => __( 'Checkbox One', 'tws-core' ),
                            'checkbox_two'      => __( 'Checkbox Two', 'tws-core' ),
                            'checkbox_three'    => __( 'Checkbox Three', 'tws-core' ),
                        ],
                    ],
                    'multi_select_id'   => [
                        'label'     => __( 'Multi-select Field', 'tws-core' ),
                        'desc'      => __( 'A multi-select field where multiple options can be selected. Ctrl + click to select multiple.', 'tws-core' ),
                        'type'      => 'multi_select',
                        'class'     => 'widefat hz_select_control', // same as select
                        'default'   => [ 'select_two' ], // default option value in array
                        'options'            => [
                            'select_one'      => __( 'Select One', 'tws-core' ),
                            'select_two'      => __( 'Select Two', 'tws-core' ),
                            'select_three'    => __( 'Select Three', 'tws-core' ),
                        ],
                    ],
                ],
            ],
        ];
        return $section;
    }
}

// initialize this submenu page.
if( Settings_API::get_option( 'tws_enable_fields', 'tws_mixed_section', 'off' ) == 'on' ) {
    new With_Fields();
}