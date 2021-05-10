<?php // phpcs:ignore WordPress.NamingConventions
/**
 * WordPress Settings API - Extended
 *
 * @package TheWebSolver\Core\Setting_Framework\Class
 *
 * Based on tareq1988
 * @author Tareq Hasan <tareq@weDevs.com>
 * @filesource https://github.com/tareq1988/wordpress-settings-api-class/blob/master/src/class.settings-api.php
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
 * WordPress Settings API. (WordPress Options).
 *
 * @class TheWebSolver\Core\Setting\Options class
 * @api
 */
final class Options {
	/**
	 * Settings sections array.
	 *
	 * @var array
	 *
	 * @since 1.0
	 */
	protected $sections = array();

	/**
	 * Settings fields array.
	 *
	 * @var array
	 *
	 * @since 1.0
	 */
	protected $fields = array();

	/**
	 * Supported Input Fields Types.
	 *
	 * @var array
	 *
	 * @since 1.0
	 * @static
	 */
	private static $field_types = array( 'text', 'number', 'checkbox', 'multi_checkbox', 'radio', 'select', 'multi_select', 'textarea', 'wysiwyg', 'file', 'color', 'pages', 'password', 'categories' );

	/**
	 * Sets settings sections.
	 *
	 * @param array $sections Sections set from `Container`.
	 *
	 * @since 1.0
	 */
	public function set_sections( $sections ) {
		$this->sections = $sections;
		return $this;
	}

	/**
	 * Sets settings fields
	 *
	 * @param array $fields Fields set from `Container`.
	 *
	 * @since 1.0
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
	 */
	public function register_setting() {

		// Registers sections.
		foreach ( $this->sections as $section ) {
			// Sets option id to save values to.
			if ( false === get_option( $section['id'] ) ) {
				add_option( $section['id'] );
			}

			// Gets callback data from section args.
			$callback = $this->section_callback( $section );

			/**
			 * Adds new section to settings page.
			 *
			 * NOTE: Regarding user capability:
			 * `$section['id']` is used when filtering option page capability
			 */
			add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
		}

		// add setting fields only if fields are set in section.
		if ( $this->fields && is_array( $this->fields ) && count( $this->fields ) > 0 ) {
			// Registers settings fields.
			foreach ( $this->fields as $section_id => $fields ) {

				foreach ( $fields as $field_id => $field_args ) {
					// Gets and sets field data args from $field_args.
					$args     = $this->field_data( $section_id, $field_id, $field_args );
					$id       = "{$section_id}[{$field_id}]";
					$callback = isset( $field_args['callback'] ) ? $field_args['callback'] : array( __CLASS__, 'field_callback' );

					// Adds new fields to each sections.
					add_settings_field( "{$id}", $args['name'], $callback, $section_id, $section_id, $args );
				}
			}
		}

		// Registers settings.
		foreach ( $this->sections as $section ) {
			register_setting(
				$section['id'],
				$section['id'],
				array( 'sanitize_callback' => array( $this, 'sanitize_callback' ) )
			);
		}
	}

	/**
	 * Gets Setting section callbacks.
	 *
	 * @param array $section Callbacks are displayed after section titles.
	 *                       Usually, used for displaying description.
	 *                       But as a hack, can be used to display any content.
	 *
	 * @since 1.0
	 */
	private function section_callback( $section ) {
		if ( isset( $section['desc'] ) && ! empty( $section['desc'] ) ) {
			$section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';

			// Set callback function.
			$callback = function() use ( $section ) {
				echo wp_kses_post( str_replace( '"', '\"', $section['desc'] ) );
			};
		} else {
			$callback = null;
		}

		return $callback;
	}

	/**
	 * Sets field data.
	 *
	 * @param array  $section_id The Section ID.
	 * @param string $field_id   The Field ID.
	 * @param array  $field_args The Field args.
	 *
	 * @return array
	 *
	 * @since 1.0
	 */
	private function field_data( $section_id, $field_id, $field_args ) {

		$class = isset( $field_args['class'] ) && ! empty( $field_args['class'] ) ? ' ' . $field_args['class'] : '';
		$label = isset( $field_args['label'] ) && ! empty( $field_args['label'] ) ? $field_args['label'] : $field_args['type'] . ' field';

		$args = array(
			'id'                => $field_id,
			'class'             => $field_id . '' . $class,
			'label_for'         => "{$section_id}[{$field_id}]",
			'desc'              => isset( $field_args['desc'] ) && ! empty( $field_args['desc'] ) ? $field_args['desc'] : '',
			'name'              => $label,
			'section'           => $section_id,
			'sanitize_callback' => isset( $field_args['sanitize_callback'] ) && ! empty( $field_args['sanitize_callback'] ) ? $field_args['sanitize_callback'] : '',
			'type'              => isset( $field_args['type'] ) && ! empty( $field_args['type'] ) ? $field_args['type'] : 'text',
			'placeholder'       => isset( $field_args['placeholder'] ) && ! empty( $field_args['placeholder'] ) ? $field_args['placeholder'] : '',
		);

		// Only set "min", "max" and "step" arg to number field type.
		if ( 'number' === $field_args['type'] ) {
			$args['min']  = isset( $field_args['min'] ) && ! empty( $field_args['min'] ) ? $field_args['min'] : '';
			$args['max']  = isset( $field_args['max'] ) && ! empty( $field_args['max'] ) ? $field_args['max'] : '';
			$args['step'] = isset( $field_args['step'] ) && ! empty( $field_args['step'] ) ? $field_args['step'] : '';
		}

		// Only set "rows" and "cols" arg to textarea field type.
		if ( 'textarea' === $field_args['type'] ) {
			$args['rows'] = isset( $field_args['rows'] ) && ! empty( $field_args['rows'] ) ? $field_args['rows'] : '5';
			$args['cols'] = isset( $field_args['cols'] ) && ! empty( $field_args['cols'] ) ? $field_args['cols'] : '50';
		}

		// Only set "options" arg to radio|select|multi_select|multi_checkbox field types.
		if (
			'radio' === $field_args['type'] ||
			'select' === $field_args['type'] ||
			'multi_select' === $field_args['type'] ||
			'multi_checkbox' === $field_args['type']
		) {
			$args['options'] = isset( $field_args['options'] ) && ! empty( $field_args['options'] ) ? $field_args['options'] : '';
		}

		// Set "default" arg to array if multi-checkbox field type, else set it to string.
		if ( 'multi_checkbox' === $field_args['type'] ) {
			$args['default'] = isset( $field_args['default'] ) && is_array( $field_args['default'] ) && count( $field_args['default'] ) > 0 ? $field_args['default'] : array();
		} else {
			$args['default'] = isset( $field_args['default'] ) && ! empty( $field_args['default'] ) ? $field_args['default'] : '';
		}

		if ( 'wysiwyg' === $field_args['type'] ) {
			$args['class'] = $args['class'] . ' hz_wysiwyg_field';
		}

		return $args;
	}

	/**
	 * Get Setting Fields utility functions.
	 *
	 * @param array $field The field args.
	 *
	 * @return mixed HTML field output according to it's type
	 *
	 * @since 1.0
	 * @static
	 */
	public static function field_callback( $field ) {
		// Get the field type from field args.
		$fieldtype = isset( $field['type'] ) && ! empty( $field['type'] ) ? $field['type'] : '';

		$value = self::get_option( $field['id'], $field['section'], $field['default'] );
		$desc  = self::get_field_description( $field );

		switch ( $fieldtype ) {
			case 'text':
				text_field( $field, $value, $desc );
				break;
			case 'number':
				number_field( $field, $value, $desc );
				break;
			case 'checkbox':
				checkbox_field( $field, $value, $desc );
				break;
			case 'multi_checkbox':
				multi_checkbox_field( $field, $value, $desc );
				break;
			case 'radio':
				radio_field( $field, $value, $desc );
				break;
			case 'select':
				select_field( $field, $value, $desc );
				break;
			case 'multi_select':
				multi_select_field( $field, $value, $desc );
				break;
			case 'textarea':
				textarea_field( $field, $value, $desc );
				break;
			case 'wysiwyg':
				wysiwyg_field( $field, $value, $desc );
				break;
			case 'file':
				file_field( $field, $value, $desc );
				break;
			case 'color':
				color_field( $field, $value, $desc );
				break;
			case 'pages':
				pages_field( $field, $value, $desc );
				break;
			case 'password':
				password_field( $field, $value, $desc );
				break;
			default:
				return '';
		}
	}

	/**
	 * Display field description
	 *
	 * @param array $field Settings field.
	 *
	 * @since 1.0
	 * @static
	 */
	public static function get_field_description( $field ) {
		$desc = (string) $field['desc'];

		/* Translators: %s - Field description */
		return ! empty( $field['desc'] ) ? '<p class="description">' . sprintf( '%s', $desc ) . '</p>' : '';
	}

	/**
	 * Sanitize callback for Settings fields
	 *
	 * @param array $pre_saved_values values that needs to be sanitized before saving.
	 *
	 * @return array sanitized values that will be saved to database.
	 *
	 * @since 1.0
	 */
	public function sanitize_callback( $pre_saved_values = array() ) {
		// Bail early if no pre-saved values exist.
		if ( ! $pre_saved_values || ! is_array( $pre_saved_values ) ) {
			return $pre_saved_values;
		}

		// Loops through registering section fields pre-saved values that exists in $key => $value pair.
		foreach ( $pre_saved_values as $key => $value ) {
			$sanitize_callback = $this->get_sanitize_callback( $key );

			// If callback arg is set in each field, call it.
			/** @var \callable $sanitize_callback */ // phpcs:ignore
			if ( $sanitize_callback ) {
				$pre_saved_values[ $key ] = call_user_func( $sanitize_callback, $value );
				continue;
			}
		}

		return $pre_saved_values;
	}

	/**
	 * Gets sanitization callback for given field.
	 *
	 * @param string $key The field ID.
	 *
	 * @return string/bool callback function if found, false otherwise
	 *
	 * @since 1.0
	 */
	private function get_sanitize_callback( $key = '' ) {
		// Bail early if no slug.
		if ( empty( $key ) ) {
			return false;
		}

		// Loops through registering fields and see if proper callback arg is set.
		foreach ( $this->fields as $section_id => $options ) {
			foreach ( $options as $field_id => $option ) {
				// Only continue if field key matches.
				if ( $field_id !== $key ) {
					continue;
				}

				// Return the callback name.
				return isset( $option['sanitize_callback'] ) && is_callable( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : false;
			}
		}

		return false;
	}

	/**
	 * Get the value of a settings field.
	 *
	 * @param string $field   The field name.
	 * @param string $section The section name of $field.
	 * @param string $default Default text if $field not found.
	 *
	 * @return string
	 *
	 * @since 1.0
	 * @static
	 */
	public static function get_option( $field, $section, $default = false ) {
		$options = get_option( $section );

		if ( isset( $options[ $field ] ) ) {
			return $options[ $field ];
		}

		return $default;
	}

	/**
	 * Show navigations as section tab.
	 *
	 * @return void Section tab in HTML format.
	 *
	 * @since 1.0
	 */
	public function show_navigation() {
		ob_start();
		$html = '<div class="nav-tab-wrapper"><div class="hz_setting_nav">';

		foreach ( $this->sections as $tab ) {
			$fields = isset( $tab['fields'] ) ? true : $tab['fields'];

			if ( $fields ) {
				/* Translators: 1. Tab ID, 2. Tab Title */
				$html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['tab_title'] );
			}
		}

		$html .= '</div></div>';

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		ob_get_contents();
	}

	/**
	 * Show the section settings forms.
	 *
	 * Display each section as forms with its fields as tab.
	 *
	 * @since 1.0
	 */
	public function show_forms() {
		ob_start();
		echo '<div class="metabox-holder">';

		// Loops through all sections and output sections and it's fields.
		foreach ( $this->sections as $section ) :
			echo '<div id="' . esc_attr( $section['id'] ) . '" class="group" style="display: none;">';

			/**
			 * DEBUG: Section Field args.
			 *
			 * @example usage: Set to true on main plugin file.
			 *
			 * define( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE', true );
			 */
			if ( defined( 'HZFEX_DEBUG_MODE' ) && HZFEX_DEBUG_MODE ) {
				// phpcs:ignore -- Debug code OK.
				echo '<div class="hzfex_debug_out"><h3>' . esc_html( $section['tab_title'] ) . ' Debug Output</h3><b>Section Data:</b><pre>', htmlspecialchars( print_r( $section, true ) ), '</pre></div>';
			}

			// Gets section callback data, if any.
			if ( $section['callback'] && is_callable( $section['callback'] ) ) {
				call_user_func( $section['callback'] );
			}

			// Display form and it's fields if section fields are set.
			if ( $section['fields'] ) {
				$this->show_fields( $section );
			}

			echo '</div>';
		endforeach;
		echo '</div>';
		ob_get_contents();

		// Required script for click, navigate, etc.
		$this->script();
	}

	/**
	 * Creates form tags, sets setting sections and fields.
	 *
	 * Main method that actually displays form and its data.
	 *
	 * @param array $section Section and its data.
	 *
	 * @since 1.0
	 */
	private function show_fields( $section ) {
		ob_start();
		echo '<form method="post" action="options.php">';

		/**
		 * WPHOOK Action -> fires before displaying section content.
		 *
		 * @param array $section The current section args.
		 */
		do_action( "hzfex_before_{$section['id']}_fields", $section );

		// WordPress API to set setting fields.
		settings_fields( $section['id'] );

		// WordPress API to set setting section.
		do_settings_sections( $section['id'] );

		/**
		 * WPHOOK Aciton -> Fires after displaying section contents.
		 *
		 * @param array $section The current section args.
		 */
		do_action( "hzfex_after_{$section['id']}_fields", $section );

		// Quick check if unsupported fields are only set.
		if ( self::has_input_field( $section['fields'] ) ) {
			echo '<div class="hz_setting_submit">';
				echo submit_button(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';
		}

		echo '</form>';
		ob_get_contents();
	}

	/**
	 * Checks if section has valid input fields to show submit button.
	 *
	 * @param array $fields All fields that belong to a section.
	 *
	 * @return bool True if any one field type matches, false otherwise.
	 *
	 * @since 1.0
	 * @static
	 */
	public static function has_input_field( array $fields ): bool {
		// Get field type from fields.
		$fields = array_column( $fields, 'type' );

		return array_intersect( self::$field_types, $fields ) ? true : false;
	}

	/**
	 * Tabbable JavaScript codes & Initiate Color Picker.
	 *
	 * This code uses localstorage for displaying active tabs.
	 *
	 * @since 1.0
	 */
	public function script() {
		?>
		<script>
			jQuery(document).ready(function($) {

				//Initiate Color Picker
				$('.hz_color_picker_control').wpColorPicker();

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
				if($('.hz_select_control .hz_select_control').length > 0) {
					$('.hz_select_control .hz_select_control').select2({
						width: '100%',
						placeholder: 'Select Options',
						allowClear: false,
						dropdownParent: $('.hz_select_control td'),
						minimumResultsForSearch: 5,
						closeOnSelect: true
					});
				}

				// radio input field selection.
				$('input[type="radio"]').on('click', function() {
					$('input[type="radio"]').each(function(){
						$(this).closest('li').toggleClass('hz_radio_selected', this.checked);
					});
				});
				$('input:radio:checked').closest('li').addClass('hz_radio_selected');
			});
		</script>

		<?php
	}
} // end class
