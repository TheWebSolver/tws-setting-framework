<?php
/**
 * WordPress Setting Framework API
 * 
 * @package tws-core
 * @subpackage framework
 * @category wordpress-setting-api
 * 
 * -----------------------------------
 * DEVELOPED-MAINTAINED-SUPPPORTED BY
 * -----------------------------------
 * ███      ███╗   ████████████████
 * ███      ███║            ██████
 * ███      ███║       ╔══█████
 *  ████████████║      ╚█████
 * ███║     ███║     █████
 * ███║     ███║   █████
 * ███║     ███║   ████████████████╗
 * ╚═╝      ╚═╝    ════════════════╝
 */

namespace TheWebSolver\Plugin\Core;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * TheWebSolver\Plugin\Core\Settings_API class
 */
final class Settings_API {

    /**
     * settings sections array
     *
     * @var array Holds setting sections from `Setting_Component()->valid_sections()`
     * 
     * @since 1.0
     * 
     * @access protected
     */
    protected $sections = [];

    /**
     * Settings fields array
     *
     * @var array Holds setting fields from `Setting_Component()->valid_fields()`
     * 
     * @since 1.0
     * 
     * @access protected
     */
    protected $fields   = [];

    /**
     * Supported Input Fields Types
     * 
     * @var array
     * 
     * @since 1.0
     * 
     * @access private
     */
    private $field_types   = [ 'text', 'number', 'checkbox', 'multi_checkbox', 'radio', 'select', 'multi_select', 'textarea', 'wysiwyg', 'file', 'color', 'pages', 'password' ];

    /**
     * Set settings sections
     *
     * @param array Sets setting sections to `$sections`
     * 
     * @since 1.0
     * 
     * @access public
     */
    public function set_sections( $sections ) {
        $this->sections = $sections;
        return $this;
    }

    /**
     * Add new single section
     * 
     * TODO: check later
     *
     * @param array $section new section data in array
     * 
     * @since 1.0
     * 
     * @access public
     */
    public function add_section( $section ) {
        $this->sections[] = $section;
        return $this;
    }

    /**
     * Set settings fields
     *
     * @param array $fields Sets setting fields to `$fields`
     * 
     * @since 1.0
     * 
     * @access public
     */
    public function set_fields( $fields ) {
        $this->fields = $fields;
        return $this;
    }

    /**
     * Add new single field
     * 
     * TODO: check later
     *
     * @param string $section   setting section name
     * @param string $field     setting field name
     * 
     * @since 1.0
     * 
     * @access public
     */
    public function add_field( $section, $field ) {

        // set defaults
        $defaults = [
            'name'  => '',
            'label' => '',
            'desc'  => '',
            'type'  => 'text'
        ];

        $arg = wp_parse_args( $field, $defaults );

        // set field data
        $this->fields[$section][] = $arg;

        return $this;
    }

    /**
     * Main function to register settings and it's sections and fields
     * 
     * @since 1.0
     * 
     * @access public
     * 
     * @uses add_settings_section() Registers setting page sections
     * @uses add_settings_field()   Registers setting page fields
     * @uses register_setting()     Registers setting page
     */
    public function register_setting() {
        
        //register sections
        foreach ( $this->sections as $section ) {

            // Set option id to save values to.
            if( false === get_option( $section['id'] ) ) add_option( $section['id'] );

            if( isset( $section['desc'] ) && ! empty( $section['desc'] ) ) {

                $section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';

                // Set callback function
                $callback = function() use ( $section ) {

                    echo str_replace( '"', '\"', $section['desc'] );
                };
            } else if( isset( $section['callback'] ) ) {
                $callback = $section['callback'];
            } else {
                $callback = null;
            }
            
            /**
             * Adds new section to settings page.
             * 
             * NOTE: `$section['id']` is used when filtering option page capability
             */
            add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
        }

        // Registers settings fields
        foreach ( $this->fields as $section => $field ) {

            foreach ( $field as $id => $option ) {

                $name       = $id;
                $type       = isset( $option['type'] ) ? $option['type'] : 'text';
                $label      = isset( $option['label'] ) ? $option['label'] : '';
                $callback   = isset( $option['callback'] ) ? $option['callback'] : [ __CLASS__, 'field_callback' ];
                $class      = isset( $option['class'] ) ? $option['class'] : '';
                $noLabel    = $label ? '' : 'hzfex_noLabel';
                
                // main args for field
                $args = [
                    'id'                => $name,
                    'class'             => $name . ' ' . $class . ' ' .$noLabel,
                    'label_for'         => "{$section}[{$name}]",
                    'desc'              => isset( $option['desc'] ) ? $option['desc'] : '',
                    'name'              => $label,
                    'section'           => $section,
                    'size'              => isset( $option['size'] ) ? $option['size'] : null,
                    'options'           => isset( $option['options'] ) ? $option['options'] : '',
                    'default'           => isset( $option['default'] ) ? $option['default'] : '',
                    'sanitize_callback' => isset( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : '',
                    'type'              => $type,
                    'placeholder'       => isset( $option['placeholder'] ) ? $option['placeholder'] : '',
                    'min'               => isset( $option['min'] ) ? $option['min'] : '',
                    'max'               => isset( $option['max'] ) ? $option['max'] : '',
                    'step'              => isset( $option['step'] ) ? $option['step'] : '',
                ];
                
                // Adds new fields to each sections
                add_settings_field( "{$section}[{$name}]", $label, $callback, $section, $section, $args );

            }

        }

        // Registers settings
        foreach ( $this->sections as $section ) {
            register_setting( $section['id'], $section['id'], [ 'sanitize_callback', [ $this, 'sanitize_options' ] ] );
        }

    }

    /**
     * Get Setting Fields utility functions
     *
     * @param array $field
     * 
     * @return mixed HTML field output according to it's type
     * 
     * @since 1.0
     * 
     * @static
     * 
     * @access public
     */
    public static function field_callback( $field ) {

        $fieldtype    = isset( $field['type'] ) ? $field['type'] : '';

        /** @var string/array */
        $value          = self::get_option( $field['id'], $field['section'], $field['default'] );
        $desc           = self::get_field_description( $field );

        switch( $fieldtype ) {

            case 'text': hz_setting_option_text_field( $field, $value, $desc ); break;

            case 'number' : hz_setting_option_number_field( $field, $value, $desc ); break;

            case 'checkbox' : hz_setting_option_checkbox_field( $field, $value, $desc ); break;

            case 'multi_checkbox' : hz_setting_option_multi_checkbox_field( $field, $value, $desc ); break;

            case 'radio' : hz_setting_option_radio_field( $field, $value, $desc ); break;

            case 'select' : hz_setting_option_select_field( $field, $value, $desc ); break;

            case 'multi_select' :
                wp_enqueue_script( 'hzfex_select2' );
                wp_enqueue_style( 'hzfex_select2_style' );
                hz_setting_option_multi_select_field( $field, $value, $desc ); break;
            
            case 'textarea' : hz_setting_option_textarea_field( $field, $value, $desc ); break;

            case 'wysiwyg' : hz_setting_option_wysiwyg_field( $field, $value, $desc ); break;

            case 'file' : hz_setting_option_file_field( $field, $value, $desc ); break;

            case 'color' : hz_setting_option_color_field( $field, $value, $desc ); break;

            case 'pages' : hz_setting_option_pages_field( $field, $value, $desc ); break;

            case 'password' : hz_setting_option_password_field( $field, $value, $desc ); break;

            default: return '';

        }

    }

    /**
     * Display field description
     *
     * @param array   $field settings field
     * 
     * @since 1.0
     * 
     * @static
     * 
     * @access public
     */
    public static function get_field_description( $field ) {
        if ( empty( $field['desc'] ) ) return '';
        return '<p class="description">' .sprintf( __( '%s', 'tws-core' ), $field['desc'] ) .'</p>';
    }

    /**
     * Sanitize callback for Settings API
     *
     * @return array
     * 
     * @since 1.0
     * 
     * @access public
     */
    public function sanitize_options( $options ) {

        // bail early if no options
        if ( ! $options ) return $options;

        foreach( $options as $slug => $value ) {

            $sanitize_callback = $this->get_sanitize_callback( $slug );

            // If callback is set, call it
            if ( $sanitize_callback ) {

                $options[ $slug ] = call_user_func( $sanitize_callback, $value ); continue;
            }
        }

        return $options;
    }

    /**
     * Get sanitization callback for given option slug
     *
     * @param string $slug option slug
     *
     * @return string/bool callback name if found, false otherwise
     * 
     * @since 1.0
     * 
     * @access public
     */
    public function get_sanitize_callback( $slug = '' ) {

        // bail early if no slug
        if ( empty( $slug ) ) return false;

        // Iterate over registered fields and see if proper callback is found
        foreach( $this->settings_fields as $section => $options ) {

            foreach ( $options as $option ) {

                if ( $option['name'] != $slug ) continue;

                // Return the callback name
                return isset( $option['sanitize_callback'] ) && is_callable( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : false;
            }
        }

        return false;
    }

    /**
     * Get the value of a settings field
     *
     * @param string  $field    settings field name
     * @param string  $section  the section name of $field
     * @param string  $default  default text if $field not found
     * 
     * @return string
     * 
     * @since 1.0
     * 
     * @static
     * 
     * @access public
     */
    public static function get_option( $field, $section, $default = '' ) {

        $options = get_option( $section );

        if ( isset( $options[$field] ) ) return $options[$field];
        return $default;
    }

    /**
     * Show navigations as tab
     *
     * Display Section Label as tab name
     * 
     * @return string   Section Name in HTML format
     * 
     * @since 1.0
     * 
     * @access public
     */
    public function show_navigation() {

        $html = '<div class="nav-tab-wrapper">';

        $html   .= '<div class="hz_setting_nav">';

        foreach( $this->sections as $tab ) {

            $fields = isset( $tab['fields'] ) ? true : $tab['fields'];

            if( $fields ) {

                $html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['tab_title'] );
            }
        }

        $html   .= '</div>';

        $html .= '</div>';

        echo $html;
    }

    /**
     * Show the section settings forms
     *
     * Display each section and it's form fields in separate tab
     * 
     * @since 1.0
     * 
     * @access public
     */
    public function show_forms() {

        echo '<div class="metabox-holder">';

            foreach ( $this->sections as $form ) {

                echo '<div id="'.$form['id'].'" class="group" style="display: none;">
                    <form method="post" action="options.php">';

                        // WPHOOK Action - for adding content before form fields
                        do_action( 'hzfex_before_' . $form['id'] . '_field', $form );

                        // WordPress API to set setting fields
                        settings_fields( $form['id'] );

                        // WordPress API to set setting section
                        do_settings_sections( $form['id'] );

                        // WPHOOK Aciton - for adding content after form fields
                        do_action( 'hzfex_after_' . $form['id'] . '_field', $form );

                        if( $this->has_input_field( $form['fields'] ) ) {
                            echo '<div class="hz_setting_submit">';
                            echo submit_button();
                            echo '</div>';
                        }

                    echo '</form>
                </div>';
            }
        echo '</div>';

        // required script for click, navigate, etc
        $this->script();
    }

    /**
     * Section has valid input fields to show submit button
     *
     * @param array $fields all fields that belong to a section
     * 
     * @return bool
     * 
     * @since 1.0
     * 
     * @access private
     */
    private function has_input_field( $fields ) {

        $types      = [];

        if( $fields ) {

            foreach ( $fields as $key => $field ) {

                if( isset( $field['type'] ) ) $types[]    = $field['type'];
            }
        }

        if( array_intersect( $this->field_types, $types ) ) return true;
        return false;
    }

    /**
     * Tabbable JavaScript codes & Initiate Color Picker
     *
     * This code uses localstorage for displaying active tabs
     * 
     * @since 1.0
     * 
     * @access public
     */
    public function script() {
        ?>
        <script>
            jQuery(document).ready(function($) {

                //Initiate Color Picker
                // $('.wp-color-picker-field').wpColorPicker();

                // Switches option sections
                $('.group').hide();
                var activetab = '';
                if (typeof(localStorage) != 'undefined' ) {
                    activetab = localStorage.getItem("activetab");
                }

                //if url has section id as hash then set it as active or override the current local storage value
                if(window.location.hash){
                    activetab = window.location.hash;
                    if (typeof(localStorage) != 'undefined' ) {
                        localStorage.setItem("activetab", activetab);
                    }
                }

                if (activetab != '' && $(activetab).length ) {
                    $(activetab).fadeIn();
                } else {
                    $('.group:first').fadeIn();
                }
                $('.group .collapsed').each(function(){
                    $(this).find('input:checked').parent().parent().parent().nextAll().each(
                    function(){
                        if ($(this).hasClass('last')) {
                            $(this).removeClass('hidden');
                            return false;
                        }
                        $(this).filter('.hidden').removeClass('hidden');
                    });
                });

                if (activetab != '' && $(activetab + '-tab').length ) {
                    $(activetab + '-tab').addClass('nav-tab-active');
                }
                else {
                    $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
                }
                $('.nav-tab-wrapper a').click(function(evt) {
                    $('.nav-tab-wrapper a').removeClass('nav-tab-active');
                    $(this).addClass('nav-tab-active').blur();
                    var clicked_group = $(this).attr('href');
                    if (typeof(localStorage) != 'undefined' ) {
                        localStorage.setItem("activetab", $(this).attr('href'));
                    }
                    $('.group').hide();
                    $(clicked_group).fadeIn();
                    evt.preventDefault();
                });

                $('.wpsa-browse').on('click', function (event) {
                    event.preventDefault();

                    var self = $(this);

                    // Create the media frame.
                    var file_frame = wp.media.frames.file_frame = wp.media({
                        title: self.data('uploader_title'),
                        button: {
                            text: self.data('uploader_button_text'),
                        },
                        multiple: false
                    });

                    file_frame.on('select', function () {
                        attachment = file_frame.state().get('selection').first().toJSON();
                        self.prev('.wpsa-url').val(attachment.url).change();
                    });

                    // Finally, open the modal
                    file_frame.open();
                });

                // enable select2 when necessary for "select" & "multi-select" field type
                if($('.hz_select2').length > 0) {
                    $('.hz_select2').select2({
                        width: '100%',
                        placeholder: 'Select Options',
                    });
                }
            });
        </script>

        <?php
    }
} // end class