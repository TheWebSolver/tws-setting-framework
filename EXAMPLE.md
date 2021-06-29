```php
<?php // functions.php file.

use TheWebSolver\Core\Setting\Component\Container;

/**
 * A global container for setting page.
 *
 * var $container \TheWebSolver\Core\Setting\Component\Container
 */
$container = new Container( 'testFields', HZFEX_SETTING_MENU );

/**
 * Initializes admin page.
 */
function add_admin_page() {
	global $container;

	$container->set_page(
		array(
			'page_title' => __( 'Plugin Options', 'tws-setting' ),
			'menu_title' => __( 'Plugin Options', 'tws-setting' ),
			'position'   => 99,
		),
	)
	->set_capability( 'manage_options' )
	->set_menu();
}
add_action( 'after_setup_theme', 'add_admin_page' );

/**
 * Creates sections and fields inside setting container.
 *
 * Creates 3 sections and fields inside respective sections as shown in screenshot in README file.
 */
function add_section_fields() {
	global $container;

	$container->add_section( // Create General fields section.
		'simpleField',
		array(
			'title'     => __( 'General Setting Section', 'tws-core' ),
			'tab_title' => __( 'General', 'tws-core' ),
			'desc'      => 'This section demonstrates general setting fields.',
		)
	)
	->add_field(
		'text_field',
		'simpleField',
		array(
			'label'             => __( 'Text Field', 'tws-core' ),
			'desc'              => __( 'Description for a simple text field', 'tws-core' ),
			'type'              => 'text',
			'placeholder'       => __( 'Placeholder text', 'tws-core' ),
			'default'           => 'Text default',
			'class'             => 'widefat', // WP default for 100% width.
			'sanitize_callback' => 'sanitize_text_field', // IMPORTANT !!!
		)
	)
	->add_field(
		'number_field',
		'simpleField',
		array(
			'label'             => __( 'Number Input Field', 'tws-core' ),
			'desc'              => __( 'Number field with [min | max | step] options.', 'tws-core' ),
			'type'              => 'number',
			'placeholder'       => __( '0.5', 'tws-core' ),
			'default'           => '0.7',
			'class'             => 'widefat', // WP default for 100% width.
			'sanitize_callback' => 'floatval', // IMPORTANT !!!
			'min'               => 0,
			'max'               => 1,
			'step'              => 0.1,
		)
	)
	->add_field(
		'checkbox_field',
		'simpleField',
		array(
			'label'             => __( 'Checkbox field', 'tws-core' ),
			'desc'              => __( 'Check/Uncheck this field. Value is saved as "on" or "off"', 'tws-core' ),
			'type'              => 'checkbox',
			'default'           => 'off',
			'class'             => '',
			'sanitize_callback' => 'sanitize_key', // IMPORTANT !!!
		)
	)
	->add_field(
		'radio_field',
		'simpleField',
		array(
			'label'   => __( 'Radio button', 'tws-core' ),
			'desc'    => __( 'Select one of the radio button', 'tws-core' ),
			'type'    => 'radio',
			'default' => 'radio_three',
			'class'   => '',
			'options' => array(
				'radio_one'   => __( 'Radio One', 'tws-core' ),
				'radio_two'   => __( 'Radio Two', 'tws-core' ),
				'radio_three' => __( 'Radio Three', 'tws-core' ),
			),
		)
	)
	->add_field(
		'select_field',
		'simpleField',
		array(
			'label'   => __( 'Select dropdown field', 'tws-core' ),
			'desc'    => __( 'Select any option from dropdown', 'tws-core' ),
			'type'    => 'select',
			'default' => 'select_two',
			'class'   => 'widefat',
			'options' => array(
				'select_one'   => __( 'Select One', 'tws-core' ),
				'select_two'   => __( 'Select Two', 'tws-core' ),
				'select_three' => __( 'Select Three', 'tws-core' ),
			),
		)
	)
	->add_field(
		'textarea_field_id',
		'simpleField',
		array(
			'label'             => __( 'Text-area Field', 'tws-core' ),
			'desc'              => __( 'Description for a simple text-area field with placeholder', 'tws-core' ),
			'type'              => 'textarea',
			'class'             => '',
			'placeholder'       => 'Placeholder text for text-area field....',
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
			'rows'              => '8',
		)
	)
	->add_section( // Create Advanced fields section.
		'advancedFields',
		array(
			'title'     => __( 'Advanced Setting Section', 'tws-core' ),
			'tab_title' => __( 'Advanced', 'tws-core' ),
			'desc'      => sprintf( '<p>%1$s</p>', __( 'This section demonstrates advanced setting fields', 'tws-core' ) ),
		)
	)
	->add_field(
		'multi_checkbox',
		'advancedFields',
		array(
			'label'   => __( 'Multi-checkbox Field', 'tws-core' ),
			'desc'    => __( 'A multi-checkbox field where multiple options can be selected.', 'tws-core' ),
			'type'    => 'multi_checkbox',
			'class'   => '',
			'default' => array( 'checkbox_two' ), // default options value in an array
			'options' => array(
				'checkbox_one'   => __( 'Checkbox One', 'tws-core' ),
				'checkbox_two'   => __( 'Checkbox Two', 'tws-core' ),
				'checkbox_three' => __( 'Checkbox Three', 'tws-core' ),
			),
		)
	)
	->add_field(
		'multi_select',
		'advancedFields',
		array(
			'label'   => __( 'Multi-select Field', 'tws-core' ),
			'desc'    => __( 'A multi-select field where multiple options can be selected. Ctrl + click to select multiple.', 'tws-core' ),
			'type'    => 'multi_select',
			'class'   => 'widefat',
			'default' => array( 'select_one', 'select_three' ), // default option value in array
			'options' => array(
				'select_one'   => __( 'Select One', 'tws-core' ),
				'select_two'   => __( 'Select Two', 'tws-core' ),
				'select_three' => __( 'Select Three', 'tws-core' ),
			),
		)
	)
	->add_field(
		'wysiwyg',
		'advancedFields',
		array(
			'label'             => __( 'Wysiwyg field', 'tws-core' ),
			'desc'              => __( 'Wysiwyg field for advanced field editing.', 'tws-core' ),
			'type'              => 'wysiwyg',
			'class'             => 'widefat',
			'default'           => __( 'Default text for wysiwyg field', 'tws-core' ),
			'sanitize_callback' => 'wp_kses_post', // IMPORTANT !!!
		)
	)
	->add_field(
		'file',
		'advancedFields',
		array(
			'label' => __( 'File Field', 'tws-core' ),
			'desc'  => __( 'This is a file field to select file uploaded.', 'tws-core' ),
			'type'  => 'file',
			'class' => 'hz_file_control',
		)
	)
	->add_field(
		'color',
		'advancedFields',
		array(
			'label' => __( 'Color Picker', 'tws-core' ),
			'desc'  => __( 'A color picker field to select color', 'tws-core' ),
			'type'  => 'color',
			'class' => '',
		)
	)
	->add_field(
		'password',
		'advancedFields',
		array(
			'label' => __( 'Password Field', 'tws-core' ),
			'desc'  => __( 'A password input field', 'tws-core' ),
			'type'  => 'password',
			'class' => 'hz_password_control',
		)
	)
	->add_section( // Create stylized fields section.
		'stylizedFields',
		array(
			'title'     => 'Customized Form Fields',
			'tab_title' => __( 'Stylized Fields', 'tws-core' ),
			'desc'      => __( 'Fields that have class applied to change the appearance', 'tws-core' ),
		)
	)
	->add_field(
		'checkbox_field_id',
		'stylizedFields',
		array(
			'label'             => __( 'Checkbox field', 'tws-core' ),
			'desc'              => __( 'Check/Uncheck this field. Value is saved as "on" or "off"', 'tws-core' ),
			'type'              => 'checkbox',
			'default'           => 'on',
			'class'             => 'hz_switcher_control', // makes checkbox switch
			'sanitize_callback' => 'sanitize_key', // IMPORTANT !!!
		)
	)
	->add_field(
		'radio_field_id',
		'stylizedFields',
		array(
			'label'   => __( 'Radio button', 'tws-core' ),
			'desc'    => __( 'Select one of the radio button', 'tws-core' ),
			'type'    => 'radio',
			'default' => 'radio_two',
			'class'   => 'hz_card_control', // makes radio card
			'options' => array(
				'radio_one'   => __( 'Radio One', 'tws-core' ),
				'radio_two'   => __( 'Radio Two', 'tws-core' ),
				'radio_three' => __( 'Radio Three', 'tws-core' ),
			),
		)
	)
	->add_field(
		'select_field_id',
		'stylizedFields',
		array(
			'label'   => __( 'Select dropdown field', 'tws-core' ),
			'desc'    => __( 'Select any option from dropdown', 'tws-core' ),
			'type'    => 'select',
			'default' => 'select_three',
			'class'   => 'widefat hz_select_control', // adds select2
			'options' => array(
				'select_one'   => __( 'Select One', 'tws-core' ),
				'select_two'   => __( 'Select Two', 'tws-core' ),
				'select_three' => __( 'Select Three', 'tws-core' ),
			),
		)
	)
	->add_field(
		'multi_checkbox_id',
		'stylizedFields',
		array(
			'label'   => __( 'Multi-checkbox Field', 'tws-core' ),
			'desc'    => __( 'A multi-checkbox field where multiple options can be selected.', 'tws-core' ),
			'type'    => 'multi_checkbox',
			'class'   => 'hz_switcher_control', // same as checkbox
			'default' => array( 'checkbox_three' ), // default options value in an array
			'options' => array(
				'checkbox_one'   => __( 'Checkbox One', 'tws-core' ),
				'checkbox_two'   => __( 'Checkbox Two', 'tws-core' ),
				'checkbox_three' => __( 'Checkbox Three', 'tws-core' ),
			),
		)
	)
	->add_field(
		'multi_select_id',
		'stylizedFields',
		array(
			'label'   => __( 'Multi-select Field', 'tws-core' ),
			'desc'    => __( 'A multi-select field where multiple options can be selected. Ctrl + click to select multiple.', 'tws-core' ),
			'type'    => 'multi_select',
			'class'   => 'widefat hz_select_control', // same as select
			'default' => array( 'select_two' ), // default option value in array
			'options' => array(
				'select_one'   => __( 'Select One', 'tws-core' ),
				'select_two'   => __( 'Select Two', 'tws-core' ),
				'select_three' => __( 'Select Three', 'tws-core' ),
			),
		)
	)
	->add_section( // Callback section for displaying only the contents (no fields but can be added).
		'callbackOnly',
		array(
			'title'     => 'Content Only Section',
			'tab_title' => __( 'Content Only', 'tws-core' ),
			'callback'  => function() {
				echo 'This is a callback content. You can display anything with the callback feature.';
			}
		)
	);
}
add_action( 'admin_init', 'add_section_fields' );
```
Fields within each section are saved an an array values with section id.

To get the saved values of above created sections and fields, use:

```php
<?php // functions.php file.

// General section and text field inside it.
$general    = get_option( 'simpleField', array() );
$text_field = $general['text_field'];

// Stylized section and multi checkbox field inside it.
$stylized       = get_option( 'stylizedFields', array() );
$multi_checkbox = $stylized['multi_checkbox_id'];

// Alternatively, using framework method.
use TheWebSolver\Core\Setting\Component\Options;

$text_field     = Options::get_option( 'text_field', 'simpleField', '' );
$multi_checkbox = Options::get_option( 'multi_checkbox_id', 'stylizedFields', array() );
```
