<?php
/**
 * WordPress Setting Framework API
 * 
 * @package tws-core
 * @subpackage framework
 * @category wordpress-setting-api
 * 
 * Based on tareq1988
 * @author Tareq Hasan <tareq@weDevs.com>
 * @filesource https://github.com/tareq1988/wordpress-settings-api-class/blob/master/src/class.settings-api.php
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

namespace TheWebSolver\Plugin\Core\Framework;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * TheWebSolver\Plugin\Core\Framework\Settings_API class
 * 
 * It is initialized in Setting_Component() class.
 * 
 * @method array set_sections() Sets setting sections from `Setting_Component()`
 * @method array set_fields() Sets setting sections' fields from `Setting_Component()`
 * 
 * @api
 */
final class Settings_API {

	/**
	 * settings sections array
	 *
	 * @var array
	 * 
	 * @since 1.0
	 * 
	 * @access protected
	 */
	protected $sections = [];

	/**
	 * Settings fields array
	 *
	 * @var array
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
	private $field_types   = [ 'text', 'number', 'checkbox', 'multi_checkbox', 'radio', 'select', 'multi_select', 'textarea', 'wysiwyg', 'file', 'color', 'pages', 'password', 'categories' ];

	/**
	 * Sets settings sections
	 *
	 * @param array
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
	 * Sets settings fields
	 *
	 * @param array $fields
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
	 * Main function to register settings and it's sections and fields
	 * 
	 * @since 1.0
	 * 
	 * @uses add_settings_section() Registers setting page sections
	 * @uses add_settings_field()   Registers setting page fields
	 * @uses register_setting()     Registers setting page
	 * 
	 * @access public
	 */
	public function register_setting() {

		// Registers sections
		foreach ( $this->sections as $section ) {

			// Sets option id to save values to.
			if( false === get_option( $section['id'] ) ) add_option( $section['id'] );

			// Gets callback data from section args.
			$callback = $this->section_callback( $section );
			
			/**
			 * Adds new section to settings page.
			 * 
			 * NOTE: Regarding user capability
			 * `$section['id']` is used when filtering option page capability
			 */
			add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
		}

		// add setting fields only if fields are set in section.
		if( $this->fields && is_array( $this->fields ) && sizeof( $this->fields ) > 0 ) {
			
			// Registers settings fields
			foreach ( $this->fields as $section => $field ) {

				foreach ( $field as $id => $option ) {

					// Sets callback function to display field HTML structure.
					$callback   = isset( $option['callback'] ) ? $option['callback'] : [ __CLASS__, 'field_callback' ];

					// Gets field data args
					$args = $this->field_data( $section, $id, $option );

					// Adds new fields to each sections
					add_settings_field( "{$section}[{$id}]", $args['name'], $callback, $section, $section, $args );
				}
			}
		}

		// Registers settings
		foreach ( $this->sections as $section ) {
			register_setting( $section['id'], $section['id'], [ 'sanitize_callback', [ $this, 'sanitize_options' ] ] );
		}
	}
	
	/**
	 * Gets Setting section callbacks
	 *
	 * @param array $section
	 * 
	 * @return void/null
	 * 
	 * @since 1.0
	 * 
	 * @access private
	 */
	private function section_callback( $section ) {

		if( isset( $section['desc'] ) && ! empty( $section['desc'] ) ) {

			$section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';

			// Set callback function
			$callback = function() use ( $section ) {

				echo str_replace( '"', '\"', $section['desc'] );
			};
		} else {
			$callback = null;
		}

		return $callback;
	}

	/**
	 * Sets field data
	 * 	 *
	 * @param array $section section data
	 * @param string $id field ID
	 * @param array $option field data
	 * 
	 * @return array
	 * 
	 * @since 1.0
	 * 
	 * @internal TODO: breakdown field args accordingly to field type
	 * 
	 * @access private
	 */
	private function field_data( $section, $id, $option ) {

		$class = isset( $option['class'] ) ? $option['class'] : '';
		if( isset( $option['label'] ) ) {
			$label = $option['label'];
		} else {
			$label = 'hzfex_noLabel';
		}

		$args = [
			'id'                => $id,
			'class'             => $id . ' ' . $class . ' ' .$label,
			'label_for'         => "{$section}[{$id}]",
			'desc'              => isset( $option['desc'] ) && ! empty( $option['desc']  ) ? $option['desc'] : '',
			'name'              => $label,
			'section'           => $section,
			'size'              => isset( $option['size'] ) && ! empty( $option['size']  ) ? $option['size'] : null,
			'options'           => isset( $option['options'] ) && ! empty( $option['options']  ) ? $option['options'] : '',
			'default'           => isset( $option['default'] ) && ! empty( $option['default']  ) ? $option['default'] : '',
			'sanitize_callback' => isset( $option['sanitize_callback'] ) && ! empty( $option['sanitize_callback']  ) ? $option['sanitize_callback'] : '',
			'type'              => isset( $option['type'] ) && ! empty( $option['type']  ) ? $option['type'] : 'text',
			'placeholder'       => isset( $option['placeholder'] ) && ! empty( $option['placeholder']  ) ? $option['placeholder'] : '',
			'min'               => isset( $option['min'] ) && ! empty( $option['min']  ) ? $option['min'] : '',
			'max'               => isset( $option['max'] ) && ! empty( $option['max']  ) ? $option['max'] : '',
			'step'              => isset( $option['step'] ) && ! empty( $option['step']  ) ? $option['step'] : '',
		];

		return $args;
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

		$fieldtype    = isset( $field['type'] ) && ! empty( $field['type'] ) ? $field['type'] : '';

		/** @var string/array */
		$value	= self::get_option( $field['id'], $field['section'], $field['default'] );
		$desc	= self::get_field_description( $field );

		switch( $fieldtype ) {

			case 'text': text_field( $field, $value, $desc ); break;

			case 'number' : number_field( $field, $value, $desc ); break;

			case 'checkbox' : checkbox_field( $field, $value, $desc ); break;

			case 'multi_checkbox' : multi_checkbox_field( $field, $value, $desc ); break;

			case 'radio' : radio_field( $field, $value, $desc ); break;

			case 'select' :
				
				// only enqueue select2 on pages with select field.
				wp_enqueue_script( 'hzfex_select2' );
				wp_enqueue_style( 'hzfex_select2_style' );
				select_field( $field, $value, $desc ); break;

			case 'multi_select' :
				
				// only enqueue select2 on pages with multi-select field.
				wp_enqueue_script( 'hzfex_select2' );
				wp_enqueue_style( 'hzfex_select2_style' );
				multi_select_field( $field, $value, $desc ); break;
			
			case 'textarea' : textarea_field( $field, $value, $desc ); break;

			case 'wysiwyg' : wysiwyg_field( $field, $value, $desc ); break;

			case 'file' : file_field( $field, $value, $desc ); break;

			case 'color' : color_field( $field, $value, $desc ); break;

			case 'pages' : pages_field( $field, $value, $desc ); break;

			case 'password' : password_field( $field, $value, $desc ); break;

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
		$desc = ! empty( $field['desc'] ) ? '<p class="description">' .sprintf( __( '%s', 'tws-core' ), $field['desc'] ) .'</p>' : '';

		return $desc;
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
				$options[$slug] = call_user_func( $sanitize_callback, $value ); continue;
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

		$html	= '<div class="nav-tab-wrapper">';

		$html	.= '<div class="hz_setting_nav">';

		foreach( $this->sections as $tab ) {

			$fields = isset( $tab['fields'] ) ? true : $tab['fields'];

			if( $fields ) {

				$html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['tab_title'] );
			}
		}

		$html	.= '</div>';

		$html	.= '</div>';

		echo $html;
	}

	/**
	 * Show the section settings forms
	 *
	 * Display each section as forms with its fields as tab
	 * 
	 * @since 1.0
	 * 
	 * @access public
	 */
	public function show_forms() {

		echo '<div class="metabox-holder">';

			// loops through all sections and output content accordingly. 
			foreach ( $this->sections as $section ) {

				echo '<div id="'.$section['id'].'" class="group" style="display: none;">';

				/**
				 * DEBUG: Section Field args.
				 * 
				 * @example usage: Set to true on main plugin file.
				 * 
				 * define( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE', true );
				 */
				if( defined( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE' ) && HZFEX_SETTING_FRAMEWORK_DEBUG_MODE ) {
					echo '<div class="hzfex_debug_out"><h3>'.$section['title'].' Debug Output</h3><b>Section Data:</b><pre>', htmlspecialchars( print_r( $section, true ) ), '</pre></div>';
				}

				// // Gets section callback data. 
				if( $section['callback'] && is_callable( $section['callback'] ) ) {
					call_user_func( $section['callback'] );
				}

				// Display form and it's fields if section fields are set.
				if( $section['fields'] ) {
					$this->show_fields( $section );
				}

				echo '</div>';
			}
		echo '</div>';

		// required script for click, navigate, etc
		$this->script();
	}

	/**
	 * Creates form tags, sets setting sections and fields.
	 * 
	 * Main method that actually displays form and its data.
	 *
	 * @param array $form section and its data.
	 * 
	 * @return void
	 * 
	 * @since 1.0
	 * 
	 * @access private
	 */
	private function show_fields( $section ) {
		echo '<form method="post" action="options.php">';

		// WPHOOK Action -> content before form fields
		do_action( "hzfex_before_{$section['id']}_fields", $section );

		// WordPress API to set setting fields
		settings_fields( $section['id'] );

		// WordPress API to set setting section
		do_settings_sections( $section['id'] );

		// WPHOOK Aciton -> ontent after form fields
		do_action( "hzfex_after_{$section['id']}_fields", $section );

		// Quick check if unsupported fields are only set.
		if( $this->has_input_field( $section['fields'] ) ) {
			echo '<div class="hz_setting_submit">';
				echo submit_button();
			echo '</div>';
		}

		echo '</form>';
	}

	/**
	 * Checks if section has valid input fields to show submit button
	 *
	 * @param array $fields all fields that belong to a section
	 * 
	 * @return bool true if any one field type matches, else false
	 * 
	 * @since 1.0
	 * 
	 * @access private
	 */
	private function has_input_field( $fields ) {

		$fields = array_column( $fields, 'type' );

		return array_intersect( $this->field_types, $fields ) ? true : false;
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