<?php
/**
 * Setting Framework Field API
 *
 * @package tws-core
 * @subpackage framework
 * @category wordpress-setting-api
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

// exit if file is accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Setting option text field
 *
 * @param array $field  text field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 *
 * @return string HTML formatted text input field
 *
 * @since 1.0
 */
function text_field( $field, $value, $desc ) {

    /**
     * DEBUG: Text Field args.
     *
     * @example usage: Set to true on main plugin file.
     *
     * define( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE', true );
     */
    if( defined( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE' ) && HZFEX_SETTING_FRAMEWORK_DEBUG_MODE ) {
        echo '<div class="hzfex_debug_out"><h3>Setting Field Debug Output</h3><b>Field Data:</b><pre>', htmlspecialchars( print_r( $field, true ) ), '</pre><br><b>Field Value:</b><pre>', htmlspecialchars( print_r( $value, true ) ), '</pre></div>';
    }

    // Gets value of section that this field belongs to.
    $section_value = get_option( $field['section'], false );

    // Sets value based on section value. If section has been saved then, select value from it.
    $value = is_array( $section_value ) && array_key_exists( $field['id'], $section_value ) ? $value : $field['default'];

    $placeholder    = empty( $field['placeholder'] ) ? '' : ' placeholder="' . $field['placeholder'] . '"';

    /* translators:
        %1$s is the field class.
        %2$s is the section ID in which field belongs.
        %3$s is the field ID.
        %4$s is the field value that will be saved to the database.
        %5$s is the field placeholder.
    */
    $html           = sprintf(
        '<input type="text" class="hz_text_input %1$s" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"%5$s/>',
        $field['class'], $field['section'], $field['id'], $value, $placeholder
    );

    $html           .= $desc;

    echo $html;
}

/**
 * Setting option number field
 *
 * @param array $field  number field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 *
 * @return string HTML formatted number input field
 *
 * @since 1.0
 */
function number_field( $field, $value, $desc ) {

    /**
     * DEBUG: Number Field args.
     *
     * @example usage: Set to true on main plugin file.
     *
     * define( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE', true );
     */
    if( defined( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE' ) && HZFEX_SETTING_FRAMEWORK_DEBUG_MODE ) {
        echo '<div class="hzfex_debug_out"><h3>Setting Field Debug Output</h3><b>Field Data:</b><pre>', htmlspecialchars( print_r( $field, true ) ), '</pre><br><b>Field Value:</b><pre>', htmlspecialchars( print_r( $value, true ) ), '</pre></div>';
    }

    // Gets value of section that this field belongs to.
    $section_value = get_option( $field['section'], false );

    // Sets value based on section value. If section has been saved then, select value from it.
    $value = is_array( $section_value ) && array_key_exists( $field['id'], $section_value ) ? $value : $field['default'];

    $placeholder    = empty( $field['placeholder'] ) ? '' : ' placeholder="' . $field['placeholder'] . '"';
    $min            = ( $field['min'] == '' ) ? '' : ' min="' . $field['min'] . '"';
    $max            = ( $field['max'] == '' ) ? '' : ' max="' . $field['max'] . '"';
    $step           = ( $field['step'] == '' ) ? '' : ' step="' . $field['step'] . '"';
    /* translators:
        %1$s is the field class.
        %2$s is the section ID in which field belongs.
        %3$s is the field ID.
        %4$s is the field value that will be saved to the database.
        %5$s is the field placeholder.
        %6$s is the field min value.
        %7$s is the field max value.
        %8$s is the step increment/decrement.
    */
    $html        = sprintf( '<input type="number" class="hz_number_input %1$s" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"%5$s%6$s%7$s%8$s/>', $field['class'], $field['section'], $field['id'], $value, $placeholder, $min, $max, $step );
    $html       .= $desc;

    echo $html;
}

/**
 * Setting option checkbox field
 *
 * @param array $field  checkbox field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 *
 * @return string HTML formatted checkbox input field
 *
 * @since 1.0
 */
function checkbox_field( $field, $value, $desc ) {

    /**
     * DEBUG: Checkbox Field args.
     *
     * @example usage: Set to true on main plugin file.
     *
     * define( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE', true );
     */
    if( defined( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE' ) && HZFEX_SETTING_FRAMEWORK_DEBUG_MODE ) {
        echo '<div class="hzfex_debug_out"><h3>Setting Field Debug Output</h3><b>Field Data:</b><pre>', htmlspecialchars( print_r( $field, true ) ), '</pre><br><b>Field Value:</b><pre>', htmlspecialchars( print_r( $value, true ) ), '</pre></div>';
    }

    // Gets value of section that this field belongs to.
    $section_value = get_option( $field['section'], false );

    // Sets value based on section value. If section has been saved then, select value from it.
    $value = is_array( $section_value ) && array_key_exists( $field['id'], $section_value ) ? $value : $field['default'];

    $html  = '<fieldset class="hz_control_field">';

    /* translators: %1$s is the section ID in which field belongs. %2$s is the field ID. */
    $html  .= sprintf( '<label for="%1$s[%2$s]">', $field['section'], $field['id'] );

    /* translators: %1$s is the section ID in which field belongs. %2$s is the field ID. */
    $html  .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', $field['section'], $field['id'] );

    $html  .= $desc;

    /* translators:
        %1$s is the field class.
        %2$s is the section ID in which field belongs.
        %3$s is the field ID.
        %4$s is the field value.
    */
    $html  .= sprintf( '<input type="checkbox" class="hz_checkbox_input %1$s" id="%2$s[%3$s]" name="%2$s[%3$s]" value="on" %4$s /></label>', $field['class'], $field['section'], $field['id'], checked( $value, 'on', false ) );

    $html  .= '</fieldset>';

    echo $html;
}

/**
 * Setting option multi-checkbox field
 *
 * @param array $field  multi-checkbox field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 *
 * @return string HTML formatted multi-checkbox input field
 *
 * @since 1.0
 */
function multi_checkbox_field( $field, $value, $desc ) {

    /**
     * DEBUG: Multi-checkbox Field args.
     *
     * @example usage: Set to true on main plugin file.
     *
     * define( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE', true );
     */
    if( defined( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE' ) && HZFEX_SETTING_FRAMEWORK_DEBUG_MODE ) {
        echo '<div class="hzfex_debug_out"><h3>Setting Field Debug Output</h3><b>Field Data:</b><pre>', htmlspecialchars( print_r( $field, true ) ), '</pre><br><b>Field Value:</b><pre>', htmlspecialchars( print_r( $value, true ) ), '</pre></div>';
    }

    // Get value of section that this field belongs to.
    $section_value = get_option( $field['section'], false );

    $html       = '<fieldset class="hz_control_field hz_multi_checkbox_field">';

    $html       .= $desc;

    /* translators: %1$s is the section ID in which field belongs. %2$s is the field ID. */
    $html .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="" />', $field['section'], $field['id'] );

    $html       .= '<ul>';

    foreach ( $field['options'] as $key => $label ) {

        $checked = false; // defines checked status
        $default = (array) $field['default']; // gets default field options key

        // Sets checked value based on default key.
        if( ! is_array( $section_value ) && in_array( $key, $default, true ) ) {
            $checked = $key;
        }

        // Finally sets checked value from the saved values in database, if exists.
        $checked = isset( $value[$key] ) ? $value[$key] : $checked;

        $html   .= '<li>';

        /* translators:
            %1$s is the section ID in which field belongs.
            %2$s is the field ID.
            %3$s is the field options key to get the field value.
            %4$s is the field class.
        */
        $html    .= sprintf( '<label for="%1$s[%2$s][%3$s]" class="%3$s %4$s">', $field['section'], $field['id'], $key, $field['class'] );

        /* translators: %1$s is the checkbox options key, %2$s is the checkbox option label. */
        $html   .= sprintf( '<p class="description checkbox_label checkbox_label_%1$s">%2$s</p>', $key, $label );

        /* translators:
            %1$s is the section ID in which field belongs.
            %2$s is the field ID.
            %3$s is the checkbox options key. This will be saved as value to database.
            %4$s is the default checked status for checkbox.
            %5$s is the field class.
        */
        $html    .= sprintf( '<input type="checkbox" class="hz_multi_checkbox_input %3$s %5$s" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s /></label>', $field['section'], $field['id'], $key, checked( $checked, $key, false ), $field['class'] );

        $html   .= '</li>';
    }

    $html       .= '</ul></fieldset>';

    echo $html;
}

/**
 * Setting option radio field
 *
 * @param array $field  radio field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 *
 * @return string HTML formatted radio input field
 *
 * @since 1.0
 */
function radio_field( $field, $value, $desc ) {

    /**
     * DEBUG: Radio Field args.
     *
     * @example usage: Set to true on main plugin file.
     *
     * define( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE', true );
     */
    if( defined( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE' ) && HZFEX_SETTING_FRAMEWORK_DEBUG_MODE ) {
        echo '<div class="hzfex_debug_out"><h3>Setting Field Debug Output</h3><b>Field Data:</b><pre>', htmlspecialchars( print_r( $field, true ) ), '</pre><br><b>Field Value:</b><pre>', htmlspecialchars( print_r( $value, true ) ), '</pre></div>';
    }

    $html  = '<fieldset class="hz_control_field hz_radio_field"><ul>';

    foreach ( $field['options'] as $key => $label ) {

        /* translators:
            %1$s is section ID in which field belongs.
            %2$s is the field ID.
            %3$s is the options key to get the field value.
            %4$s is the field class.
        */
        $html .= sprintf( '<li><label for="%1$s[%2$s][%3$s]" class="%3$s %4$s">',  $field['section'], $field['id'], $key, $field['class'] );

        /* translators: %1$s is the field ID. %2$s is the field label. */
        $html   .= sprintf( '<p class="description radio_label radio_label_%1$s">%2$s</p>', $field['id'], $label );

        /* translators:
            %1$s is section ID in which field belongs.
            %2$s is the field ID.
            %3$s is the options key to get the field value.
            %4$s is the field value.
            %5$s is the field class
        */
        $html .= sprintf( '<input type="radio" class="hz_radio_input %3$s %5$s" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s" %4$s /></label></li>', $field['section'], $field['id'], $key, checked( $value, $key, false ), $field['class'] );
    }

    $html   .= $desc;
    $html .= '</ul></fieldset>';

    echo $html;
}

/**
 * Setting option select field
 *
 * @param array $field  select field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 *
 * @return string HTML formatted select field
 *
 * @since 1.0
 */
function select_field( $field, $value, $desc ) {

    /**
     * DEBUG: Select Field args.
     *
     * @example usage: Set to true on main plugin file.
     *
     * define( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE', true );
     */
    if( defined( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE' ) && HZFEX_SETTING_FRAMEWORK_DEBUG_MODE ) {
        echo '<div class="hzfex_debug_out"><h3>Setting Field Debug Output</h3><b>Field Data:</b><pre>', htmlspecialchars( print_r( $field, true ) ), '</pre><br><b>Field Value:</b><pre>', htmlspecialchars( print_r( $value, true ) ), '</pre></div>';
    }

    // Gets value of section that this field belongs to.
    $section_value = get_option( $field['section'], false );

    // Sets value based on section value. If section has been saved then, select value from it.
    $value      = is_array( $section_value ) && array_key_exists( $field['id'], $section_value ) ? $value : '';
    $default    = (string) $field['default']; // gets default field options key

    /* translators:
        %1$s is the field class.
        %2$s is the section ID in which field belongs.
        %3$s is the field ID.
    */
    $html  = sprintf( '<select class="hz_select %1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">', $field['class'], $field['section'], $field['id'] );

    $html   .= '<option></option>'; // for select2 to work

    foreach ( $field['options'] as $key => $label ) {

        $selected = ''; // defines selected status

        // Sets selected status based on default key and only when section isn't saved yet.
        if( $key == $default && ! is_array( $section_value ) ) {
            $selected = 'selected';
        }

        // Finally sets selected status from the saved values in database, if exists.
        $selected   = ! empty( $value ) && $key == $value ? 'selected' : $selected;

        /* translators:
            %1$s is the options key to get the field value.
            %2$s is the field value.
            %3$s is the field options label.
        */
        $html .= sprintf( '<option value="%1$s"%2$s>%3$s</option>', $key, $selected, $label );
    }

    $html .= sprintf( '</select>' );
    $html .= $desc;

    echo $html;
}

/**
 * Setting option multi-select field
 *
 * @param array $field  select field data in an array
 * @param array $value  $field value
 * @param string $desc  $field description
 *
 * @return string HTML formatted select field
 *
 * @since 1.0
 */
function multi_select_field( $field, $value, $desc ) {

    /**
     * DEBUG: Multi-select Field args.
     *
     * @example usage: Set to true on main plugin file.
     *
     * define( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE', true );
     */
    if( defined( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE' ) && HZFEX_SETTING_FRAMEWORK_DEBUG_MODE ) {
        echo '<div class="hzfex_debug_out"><h3>Setting Field Debug Output</h3><b>Field Data:</b><pre>', htmlspecialchars( print_r( $field, true ) ), '</pre><br><b>Field Value:</b><pre>', htmlspecialchars( print_r( $value, true ) ), '</pre></div>';
    }

    // Get value of section that this field belongs to.
    $section_value = get_option( $field['section'], false );

    /**
     * Get field value only if section has value and this field also has value.
     * This is to prevent default value to be selected by default,
     * when all of the multi-select options are unset by the user.
     */
    $value = is_array( $section_value ) && array_key_exists( $field['id'], $section_value ) ? $value : [];

    /* translators:
        %1$s is the field class.
        %2$s is the section ID in which field belongs.
        %3$s is the field ID.
    */
    $html   = sprintf( '<select class="hz_multi_select %1$s" name="%2$s[%3$s][]" id="%2$s[%3$s]" multiple="multiple">', $field['class'], $field['section'], $field['id'] );

    foreach ( $field['options'] as $key => $label ) {

        $selected = ''; // defines selected status

        $default = (array) $field['default']; // gets default field options key

        // Sets selected status based on default key and only when section isn't saved yet.
        if( in_array( $key, $default, true ) && ! is_array( $section_value ) ) {
            $selected = 'selected';
        }

        // Finally sets selected status from the saved values in database, if exists.
        $selected   = is_array( $value ) && in_array( $key, $value ) ? 'selected' : $selected;

        /* translators:
            %1$s is the options key to get the field value.
            %2$s is the field's selected status.
            %3$s is the field options label.
        */
        $html .= sprintf( '<option value="%1$s" %2$s>%3$s</option>', $key, $selected, $label );
    }

    $html .= sprintf( '</select>' );
    $html .= $desc;

    echo $html;
}

/**
 * Setting option textarea field
 *
 * @param array $field  textarea field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 *
 * @return string HTML formatted textarea input field
 *
 * @since 1.0
 */
function textarea_field( $field, $value, $desc ) {

    /**
     * DEBUG: Textarea Field args.
     *
     * @example usage: Set to true on main plugin file.
     *
     * define( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE', true );
     */
    if( defined( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE' ) && HZFEX_SETTING_FRAMEWORK_DEBUG_MODE ) {
        echo '<div class="hzfex_debug_out"><h3>Setting Field Debug Output</h3><b>Field Data:</b><pre>', htmlspecialchars( print_r( $field, true ) ), '</pre><br><b>Field Value:</b><pre>', htmlspecialchars( print_r( $value, true ) ), '</pre></div>';
    }

    $size           = isset( $field['size'] ) && ! empty( $field['size'] ) ? $field['size'] : 'regular';
    $placeholder    = empty( $field['placeholder'] ) ? '' : ' placeholder="'.$field['placeholder'].'"';
    $rows           = empty( $field['rows'] ) ? '' : ' rows="'.$field['rows'].'"';
    $cols           = empty( $field['cols'] ) ? '' : ' cols="'.$field['cols'].'"';

    /* translators:
        %1$s is the field class.
        %2$s is the field section ID in which field belongs.
        %3$s is the field ID.
        %4$s is the field placeholder.
        %5$s is the field textarea rows.
        %6$s is the field textarea cols.
        %7$s is the field value.
    */
    $html        = sprintf( '<textarea class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]"%4$s %5$s%6$s>%7$s</textarea>', $size, $field['section'], $field['id'], $placeholder, $rows, $cols, $value );
    $html        .= $desc;

    echo $html;
}

/**
 * Setting option wysiwyg field
 *
 * @param array $field  wysiwyg field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 *
 * @return string HTML formatted wysiwyg field
 *
 * @since 1.0
 */
function wysiwyg_field( $field, $value, $desc ) {

    /**
     * DEBUG: WYSIWYG Field args.
     *
     * @example usage: Set to true on main plugin file.
     *
     * define( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE', true );
     */
    if( defined( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE' ) && HZFEX_SETTING_FRAMEWORK_DEBUG_MODE ) {
        echo '<div class="hzfex_debug_out"><h3>Setting Field Debug Output</h3><b>Field Data:</b><pre>', htmlspecialchars( print_r( $field, true ) ), '</pre><br><b>Field Value:</b><pre>', htmlspecialchars( print_r( $value, true ) ), '</pre></div>';
    }

    $size  = isset( $field['size'] ) && ! empty( $field['size'] ) ? $field['size'] : '500px';

    echo '<div style="max-width: ' . $size . ';">';

    $editor_settings = [
        'teeny'         => true,
        'textarea_name' => $field['section'] . '[' . $field['id'] . ']',
        'textarea_rows' => 10
    ];

    if ( isset( $field['options'] ) && is_array( $field['options'] ) ) {
        $editor_settings = array_merge( $editor_settings, $field['options'] );
    }

    wp_editor( $value, $field['section'] . '-' . $field['id'], $editor_settings );

    echo '</div>';

    echo $desc;
}

/**
 * Setting option file selection field
 *
 * @param array $field  file selection field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 *
 * @return string HTML formatted file selection field
 *
 * @since 1.0
 */
function file_field( $field, $value, $desc ) {

    /**
     * DEBUG: File Field args.
     *
     * @example usage: Set to true on main plugin file.
     *
     * define( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE', true );
     */
    if( defined( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE' ) && HZFEX_SETTING_FRAMEWORK_DEBUG_MODE ) {
        echo '<div class="hzfex_debug_out"><h3>Setting Field Debug Output</h3><b>Field Data:</b><pre>', htmlspecialchars( print_r( $field, true ) ), '</pre><br><b>Field Value:</b><pre>', htmlspecialchars( print_r( $value, true ) ), '</pre></div>';
    }

    $size  = isset( $field['size'] ) && ! empty( $field['size'] ) ? $field['size'] : 'regular';
    $id    = $field['section']  . '[' . $field['id'] . ']';
    $label = isset( $field['options']['button_label'] ) ? $field['options']['button_label'] : __( 'Choose File' );

    /* translators:
        %1$s is the field class.
        %2$s is the field section ID in which field belongs.
        %3$s is the field ID.
        %4$s is the field value.
    */
    $html  = sprintf( '<input type="text" class="%1$s-text wpsa-url" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $field['section'], $field['id'], $value );
    $html  .= '<input type="button" class="button wpsa-browse" value="' . $label . '" />';
    $html  .= $desc;

    echo $html;
}

/**
 * Setting option color field
 *
 * @param array $field  color field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 *
 * @return string HTML formatted color input field
 *
 * @since 1.0
 */
function color_field( $field, $value, $desc ) {

    /**
     * DEBUG: Color Field args.
     *
     * @example usage: Set to true on main plugin file.
     *
     * define( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE', true );
     */
    if( defined( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE' ) && HZFEX_SETTING_FRAMEWORK_DEBUG_MODE ) {
        echo '<div class="hzfex_debug_out"><h3>Setting Field Debug Output</h3><b>Field Data:</b><pre>', htmlspecialchars( print_r( $field, true ) ), '</pre><br><b>Field Value:</b><pre>', htmlspecialchars( print_r( $value, true ) ), '</pre></div>';
    }

    $size  = isset( $field['size'] ) && ! empty( $field['size'] ) ? $field['size'] : 'regular';

    /* translators:
        %1$s is the field class.
        %2$s is the field section ID in which field belongs.
        %3$s is the field ID.
        %4$s is the field value.
        %5$s is the field default value.
    */
    $html  = sprintf( '<input type="text" class="%1$s-text hz_color_picker_control" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" data-default-color="%5$s" />', $size, $field['section'], $field['id'], esc_attr( $value ), $field['default'] );
    $html  .= $desc;

    echo $html;
}

/**
 * Setting option password field
 *
 * @param array $field  password field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 *
 * @return string HTML formatted password input field
 *
 * @since 1.0
 */
function password_field( $field, $value, $desc ) {

    /**
     * DEBUG: Password Field args.
     *
     * @example usage: Set to true on main plugin file.
     *
     * define( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE', true );
     */
    if( defined( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE' ) && HZFEX_SETTING_FRAMEWORK_DEBUG_MODE ) {
        echo '<div class="hzfex_debug_out"><h3>Setting Field Debug Output</h3><b>Field Data:</b><pre>', htmlspecialchars( print_r( $field, true ) ), '</pre><br><b>Field Value:</b><pre>', htmlspecialchars( print_r( $value, true ) ), '</pre></div>';
    }

    $size  = isset( $field['size'] ) && ! empty( $field['size'] ) ? $field['size'] : 'regular';

    /* translators:
        %1$s is the field class.
        %2$s is the field section ID in which field belongs.
        %3$s is the field ID.
        %4$s is the field value.
    */
    $html  = sprintf( '<input type="password" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $field['section'], $field['id'], $value );
    $html  .= $desc;

    echo $html;
}

/**
 * Setting option pages field
 *
 * @param array $field  pages field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 *
 * @return string HTML formatted pages select field
 *
 * @since 1.0
 */
function pages_field( $field, $value, $desc ) {

    /**
     * DEBUG: Pages Field args.
     *
     * @example usage: Set to true on main plugin file.
     *
     * define( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE', true );
     */
    if( defined( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE' ) && HZFEX_SETTING_FRAMEWORK_DEBUG_MODE ) {
        echo '<div class="hzfex_debug_out"><h3>Setting Field Debug Output</h3><b>Field Data:</b><pre>', htmlspecialchars( print_r( $field, true ) ), '</pre><br><b>Field Value:</b><pre>', htmlspecialchars( print_r( $value, true ) ), '</pre></div>';
    }

    $dropdown_args = [
        'selected' => esc_attr( $value ),
        'name'     => $field['section'] . '[' . $field['id'] . ']',
        'id'       => $field['section'] . '[' . $field['id'] . ']',
        'echo'     => 0
    ];
    $html = wp_dropdown_pages( $dropdown_args );
    echo $html;
}

/**
 * Setting option categories field
 *
 * @param array $field  categories field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 *
 * @return string HTML formatted categories select field
 *
 * @since 1.0
 */
function categories_field( $field, $value, $desc ) {

    /**
     * DEBUG: Categories Field args.
     *
     * @example usage: Set to true on main plugin file.
     *
     * define( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE', true );
     */
    if( defined( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE' ) && HZFEX_SETTING_FRAMEWORK_DEBUG_MODE ) {
        echo '<div class="hzfex_debug_out"><h3>Setting Field Debug Output</h3><b>Field Data:</b><pre>', htmlspecialchars( print_r( $field, true ) ), '</pre><br><b>Field Value:</b><pre>', htmlspecialchars( print_r( $value, true ) ), '</pre></div>';
    }

    $dropdown_args = [
        'selected' => esc_attr( $value ),
        'name'     => $field['section'] . '[' . $field['id'] . ']',
        'id'       => $field['section'] . '[' . $field['id'] . ']',
        'echo'     => 0
    ];
    $html = wp_dropdown_categories( $dropdown_args );
    echo $html;
}
