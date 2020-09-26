<?php
/**
 * Setting Framework Field API
 * 
 * @version     2.1
 * @since       2.0
 * 
 * @package     hzfex
 * @subpackage  function
 * @category    API
 * 
 * --------------------------------
 * DEVELOPED AND CUSTOMIZED BY
 * --------------------------------
 * 
 * ███      ███╗   ████████████████
 * ███      ███║            ██████
 * ███      ███║       ╔══█████
 *  ████████████║      ╚████
 * ███║     ███║     ████
 * ███║     ███║   ███
 * ███║     ███║   ████████████████╗
 * ╚═╝      ╚═╝    ════════════════╝
 */

// exit if file is accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Setting option text field
 * 
 * @since 2.0
 *
 * @param array $field  text field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 * 
 * @return mixed    HTML formatted text input field
 */
function hz_setting_option_text_field( $field, $value, $desc ) {

    $size        = isset( $field['size'] ) && !is_null( $field['size'] ) ? $field['size'] : 'regular';
    $type        = isset( $field['type'] ) ? $field['type'] : 'text';
    $placeholder = empty( $field['placeholder'] ) ? '' : ' placeholder="' . $field['placeholder'] . '"';

    $html        = sprintf( 
        '<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"%6$s/>',
        $type, $size, $field['section'], $field['id'], $value, $placeholder 
    );

    $html           .= $desc;

    echo $html;

}

/**
 * Setting option number field
 * 
 * @since 2.0
 *
 * @param array $field  number field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 * 
 * @return mixed    HTML formatted number input field
 */
function hz_setting_option_number_field( $field, $value, $desc ) {

    $size        = isset( $field['size'] ) && !is_null( $field['size'] ) ? $field['size'] : 'regular';
    $type        = isset( $field['type'] ) ? $field['type'] : 'number';
    $placeholder = empty( $field['placeholder'] ) ? '' : ' placeholder="' . $field['placeholder'] . '"';
    $min         = ( $field['min'] == '' ) ? '' : ' min="' . $field['min'] . '"';
    $max         = ( $field['max'] == '' ) ? '' : ' max="' . $field['max'] . '"';
    $step        = ( $field['step'] == '' ) ? '' : ' step="' . $field['step'] . '"';

    $html        = sprintf( '<input type="%1$s" class="%2$s-number" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"%6$s%7$s%8$s%9$s/>', $type, $size, $field['section'], $field['id'], $value, $placeholder, $min, $max, $step );
    $html       .= $desc;

    echo $html;

}

/**
 * Setting option checkbox field
 * 
 * @since 2.0
 *
 * @param array $field  checkbox field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 * 
 * @return mixed    HTML formatted checkbox input field
 */
function hz_setting_option_checkbox_field( $field, $value, $desc ) {

    $html  = '<fieldset>';
    $html  .= sprintf( '<label for="%1$s[%2$s]">', $field['section'], $field['id'] );
    $html  .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', $field['section'], $field['id'] );
    
    $html  .= sprintf( '<span class="desc">%1$s</span>', $field['desc'] );

    $html  .= sprintf( '<input type="checkbox" class="checkbox" id="%1$s[%2$s]" name="%1$s[%2$s]" value="on" %3$s /></label>', $field['section'], $field['id'], checked( $value, 'on', false ) );

    $html  .= '</fieldset>';

    echo $html;
}

/**
 * Setting option multi-checkbox field
 * 
 * @since 2.0
 *
 * @param array $field  multi-checkbox field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 * 
 * @return mixed    HTML formatted multi-checkbox input field
 */
function hz_setting_option_multi_checkbox_field( $field, $value, $desc ) {

    $html  = '<fieldset>';
    $html .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="" />', $field['section'], $field['id'] );
    foreach ( $field['options'] as $key => $label ) {
        $checked = isset( $value[$key] ) ? $value[$key] : '0';
        $html    .= sprintf( '<label for="%1$s[%2$s][%3$s]">', $field['section'], $field['id'], $key );

        $html   .= sprintf( '<span class="desc">%1$s</span>', $label );

        $html    .= sprintf( '<input type="checkbox" class="checkbox" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s /></label>', $field['section'], $field['id'], $key, checked( $checked, $key, false ) );
        // $html    .= sprintf( '%1$s',  $label );
    }
    
    $html           .= $desc;
    
    $html .= '</fieldset>';

    echo $html;
}

/**
 * Setting option radio field
 * 
 * @since 2.0
 *
 * @param array $field  radio field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 * 
 * @return mixed    HTML formatted radio input field
 */
function hz_setting_option_radio_field( $field, $value, $desc ) {

    $html  = '<fieldset><ul>';

    foreach ( $field['options'] as $key => $label ) {
        $html .= sprintf( '<li><label for="%1$s[%2$s][%3$s]" class="%4$s">',  $field['section'], $field['id'], $key, $field['class'] );
        $html   .= sprintf( '<span class="desc">%1$s</span>', $label );
        $html .= sprintf( '<input type="radio" class="radio" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s" %4$s /></label></li>', $field['section'], $field['id'], $key, checked( $value, $key, false ) );
        // $html .= sprintf( '%1$s<br>', $label );
    }

    $html   .= $desc;
    $html .= '</ul></fieldset>';

    echo $html;
}

/**
 * Setting option select field
 * 
 * @since 2.0
 *
 * @param array $field  select field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 * 
 * @return mixed    HTML formatted select field
 */
function hz_setting_option_select_field( $field, $value, $desc ) {

    $size  = isset( $field['size'] ) && !is_null( $field['size'] ) ? $field['size'] : 'regular';
    $html  = sprintf( '<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">', $size, $field['section'], $field['id'] );

    foreach ( $field['options'] as $key => $label ) {
        $html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
    }

    $html .= sprintf( '</select>' );
    $html .= $desc;

    echo $html;
}

/**
 * Setting option textarea field
 * 
 * @since 2.0
 *
 * @param array $field  textarea field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 * 
 * @return mixed    HTML formatted textarea input field
 */
function hz_setting_option_textarea_field( $field, $value, $desc ) {

    $size        = isset( $field['size'] ) && !is_null( $field['size'] ) ? $field['size'] : 'regular';
    $placeholder = empty( $field['placeholder'] ) ? '' : ' placeholder="'.$field['placeholder'].'"';

    $html        = sprintf( '<textarea rows="5" cols="55" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]"%4$s>%5$s</textarea>', $size, $field['section'], $field['id'], $placeholder, $value );
    $html        .= $desc;

    echo $html;
}

/**
 * Setting option wysiwyg field
 * 
 * @since 2.0
 *
 * @param array $field  wysiwyg field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 * 
 * @return mixed    HTML formatted wysiwyg field
 */
function hz_setting_option_wysiwyg_field( $field, $value, $desc ) {

    $size  = isset( $field['size'] ) && !is_null( $field['size'] ) ? $field['size'] : '500px';

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
 * @since 2.0
 *
 * @param array $field  file selection field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 * 
 * @return mixed    HTML formatted file selection field
 */
function hz_setting_option_file_field( $field, $value, $desc ) {

    $size  = isset( $field['size'] ) && !is_null( $field['size'] ) ? $field['size'] : 'regular';
    $id    = $field['section']  . '[' . $field['id'] . ']';
    $label = isset( $field['options']['button_label'] ) ? $field['options']['button_label'] : __( 'Choose File' );

    $html  = sprintf( '<input type="text" class="%1$s-text wpsa-url" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $field['section'], $field['id'], $value );
    $html  .= '<input type="button" class="button wpsa-browse" value="' . $label . '" />';
    $html  .= $desc;

    echo $html;
}

/**
 * Setting option color field
 * 
 * @since 2.0
 *
 * @param array $field  color field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 * 
 * @return mixed    HTML formatted color input field
 */
function hz_setting_option_color_field( $field, $value, $desc ) {

    $size  = isset( $field['size'] ) && !is_null( $field['size'] ) ? $field['size'] : 'regular';

    $html  = sprintf( '<input type="text" class="%1$s-text wp-color-picker-field" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" data-default-color="%5$s" />', $size, $field['section'], $field['id'], $value, $field['std'] );
    $html  .= $desc;

    echo $html;
}

/**
 * Setting option password field
 * 
 * @since 2.0
 *
 * @param array $field  password field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 * 
 * @return mixed    HTML formatted password input field
 */
function hz_setting_option_password_field( $field, $value, $desc ) {

    $size  = isset( $field['size'] ) && !is_null( $field['size'] ) ? $field['size'] : 'regular';

    $html  = sprintf( '<input type="password" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $field['section'], $field['id'], $value );
    $html  .= $desc;

    echo $html;
}

/**
 * Setting option pages field
 * 
 * @since 2.0
 *
 * @param array $field  pages field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 * 
 * @return mixed    HTML formatted pages select field
 */
function hz_setting_option_pages_field( $field, $value, $desc ) {

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
 * @since 2.0
 *
 * @param array $field  categories field data in an array
 * @param string $value $field value
 * @param string $desc  $field description
 * 
 * @return mixed    HTML formatted categories select field
 */
function hz_setting_option_categories_field( $field, $value, $desc ) {

    $dropdown_args = [
        'selected' => esc_attr( $value ),
        'name'     => $field['section'] . '[' . $field['id'] . ']',
        'id'       => $field['section'] . '[' . $field['id'] . ']',
        'echo'     => 0
    ];
    $html = wp_dropdown_categories( $dropdown_args );
    echo $html;

}

/**
 * Setting option multi-select field
 * 
 * @since 2.1
 *
 * @param array $field  select field data in an array
 * @param array $value  $field value
 * @param string $desc  $field description
 * 
 * @return mixed    HTML formatted select field
 */
function hz_setting_option_multi_select_field( $field, $value, $desc ) {

    $id         = $field['section'] . '['.$field['id'].']';
    $name       = $id . '[]';

    $size  = isset( $field['size'] ) && !is_null( $field['size'] ) ? $field['size'] : 'regular';
    $html  = sprintf( '<select class="hz_select2 %1$s" name="'.$name.'" id="'.$id.'" multiple="multiple">', $size );
    $html .= '<option></option>'; // for select2 placeholder to work
    
    foreach ( $field['options'] as $key => $label ) {
        $selected   = is_array( $value ) && in_array( $key, $value ) ? 'selected' : '';
        $html .= sprintf( '<option value="%1$s" %2$s>%3$s</option>', $key, $selected, $label );
    }

    $html .= sprintf( '</select>' );
    $html .= $desc;

    echo $html;
}
