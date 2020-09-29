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

namespace TheWebSolver\Plugin\Core\Framework;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * TheWebSolver\Plugin\Core\Framework\Setting_Component class
 * 
 * @api
 */
class Setting_Component {

    /**
     * WordPress Core Settings API
     * 
     * Sets an instance of `Settings_API()` class
     * 
     * @var object
     * 
     * @since 1.0
     * 
     * @access private
     */
    private $settings;

    /**
     * Main menu slug
     * 
     * This is where all submenu pages gets hooked by default.
     *
     * @var string
     * 
     * @since 1.0
     * 
     * @access private
     */
    private $menu_slug = 'options-general.php';

    /**
     * Child-class submenu pages
     * 
     * Will be used to:
     * * hook submenus
     * * set each child-class args data
     * * load assets
     * * filter user capability
     * 
     * @var array Sets each child-class submenu page in an array
     * 
     * @since 1.0
     * 
     * @access public
     */
    public $subpages = [];

    /**
     * Child-class submenu hooks
     * 
     * Will be used to hook assets only to submenu pages for performance reasons.
     * 
     * @var array Hooks submenus created using `add_submenu_page()`
     * 
     * @since 1.0
     * 
     * @access public
     */
    public $submenu_hook = [];

    /**
     * Default User Capability
     * 
     * Sets default user capability that can access each child-class submenu page
     * 
     * @var string
     * 
     * @since 1.0
     * 
     * @access private
     */
    private $default_cap = 'manage_options';

    /**
     * User capabilities from child-class args
     * 
     * Sets user capability for each submenu page in an array
     * Will be used with filter hook to change default capability
     *
     * @var array
     * 
     * @since 1.0
     * 
     * @static
     * 
     * @access private
     */
    private static $subpages_cap = [];

    /**
     * Page navigations
     * 
     * Sets each submenu page args in an array
     *
     * @var array
     * 
     * @since 1.0
     * 
     * @static
     * 
     * @access private
     */
    private static $subpages_nav = [];

    /**
     * Main Constructor.
     * 
     * * The child-class should set this parent constructor from it's own constructor.
     * * It will instantiate this parent class each time child-class is instantiated.
     * * Everything inside this {@method `__construct()`} will run on each child-class instance.
     * 
     * @param array $childclass {
     * 
     * Array with key as child-class and values as an array of submenu page arguments as follows:
     * 
     * * @var string $menu_slug    `optional` Main menu slug. Defaults to `$this->menu_slug`
     * * @var string $page_title   `required` Submen Page Title. Will be used in `$subpages_nav`.
     * * @var string $menu_title   `optional` Menu title. Will be used in Admin Menu 
     * * @var string $cap          `optional` User capability. Will be used to create submenu as well as for permission to save options. Defaults to `$this->default_cap`
     * * @var string $slug         `required` Setting page slug. Will be used in URL.
     * * @var string $icon         `optional` Icon to show on nav bar inside setting page. Will be used in `$subpages_nav`. Defaults to gear icon.
     * * @var int    $priority     `optional` Submenu placement priority. Defaults to null
     * 
     * }
     * 
     * @since 1.0
     * 
     * @example Usage:
     * 
     * Use the template below to construct child-class.
     * 
     *   ```
     *   use TheWebSolver\Plugin\Core\Framework\Setting_Component;
     * 
     *   final class Subclass_Setting extends Setting_Component {
     *       public function __construct() {
     *           parent::__construct( 
     *               [
     *                   __CLASS__ => [
     *                       'menu_slug'     => 'menu_slug_here', // create menu by yourself first if you want submenu pages to have their own main menu. To create, use function add_menu_page()
     *                       'page_title'    => __( 'Submenu Title Here', 'tws-core' ),
     *                       'menu_title'    => __( 'Submenu page Title Here', 'tws-core' ),
     *                       'cap'           => 'capability_here',
     *                       'slug'          =>'page_slug_here',
     *                       'icon'          => 'icon_slug_here',
     *                   ]
     *               ]
     *           );
     *       }
     * 
     *       // Always override this method from child-class to set page sections and fields.
     *       protected function sections() {
     *           $sections = [
     *               'first_test_section_id' => [
     *                   'title'     => __( 'General Setting', 'tws-core' ),
     *                   'tab_title' => __( 'General', 'tws-core' ),
     *                   'desc'      => sprintf( '<p>%1$s</p>', __( 'General Plugin Setting', 'tws-core' ) ), // used as callback to display content between title and fields
     *                   'callback'  => 'callback_function_name_here', // used as callback to display anything you want. Maybe about your page, some support message, anything!. This won't work if "desc" is set. Recommended to use this if planning to only show info within this section and don't want to set "fields" in this section.
     *                   
     *                   // Recommended to set fields when "callback" is not set. You can use both btw!
     *                   'fields'    => [
     *                        
     *                       'first_field_id'     => [
     *                           'label' => __( 'Field Title here', 'tws-core' ),
     *                           'desc'  => __( 'Field details here', 'tws-core' ),
     *                           'type'  => 'text',
     *                           'type'  => __( 'Placeholder text here', 'tws-core' ),
     *                           'class' => 'field_wrapper_class',
     *                           'sanitize_callback' => 'sanitize_text_field', // your choice
     *                           'priority'  => 5
     *                           
     *                       ], // end of first field args
     *                       
     *                       // <-----------next field here----------->
     *                       
     *                   ], // end of fields args
     * 
     *               ], // end of first section args
     * 
     *               // <-----------next section here----------->
     * 
     *           ]; // end of all sections
     *           
     *           // Sets sections and its fields data
     *           return $sections;
     *           
     *       }
     *   }
     *   ```
     */
    public function __construct( $childclass = [] ) {

        // Sets child-class submenu pages.
        $this->subpages = $childclass;

        // Registers each child-class submenu pages.
        add_action( 'admin_menu', [ $this, 'menu' ], 99 );
    }

    /**
     * Registers each child-class submenu pages.
     * 
     * Performs these actions:
     * * checks if child-class has array args {@see `foreach => $page`} && child-class doesn't repeat
     * * hooks submenus
     * * sets each child-class args data
     * * loads assets
     * * filters user capability
     * 
     * @since 1.0
     * 
     * @return false/void False if submenu page slug not set.
     * 
     * @access public
     */
    public function menu() {

        $subpages = $this->subpages;

        // Loops through all submenu pages from each child-class.
        foreach( $subpages as $class => $page ) {

            /**
             * Checks if $page is an array.
             * Makes sure no duplicate pages are created.
             * 
             * NOTE: Duplication!
             * - Duplication can happen if child-class is instantiated multiple times.
             * - Happens intentionally (by others, not you ....of course!),
             * - Happens by mistake (myabe you ....can't really tell ....duh!)
             */
            if( is_array( $page ) && ! array_key_exists( $class, self::$subpages_nav ) ) {

                // Gets each child-class slug.
                $slug = isset( $page['slug'] ) && ! empty( $page['slug'] ) ? $page['slug'] : false;

                // Bail early if slug is not set in clild-class args.
                if( false === $slug ) return false;

                // Validates each child-class args.
                $menu_slug  = isset( $page['menu_slug']) && ! empty( $page['menu_slug'] ) ? $page['menu_slug'] : '';
                $page_title = isset( $page['page_title'] ) && ! empty( $page['page_title'] ) ? $page['page_title'] : 'The Web Solver';
                $menu_title = isset( $page['menu_title'] ) && ! empty( $page['menu_title'] ) ? $page['menu_title'] : $page_title;
                $cap        = isset( $page['cap'] ) && ! empty( $page['cap'] ) ? $page['cap'] : $this->default_cap;
                $priority   = isset( $page['priority'] ) ? $page['priority'] : null;
                $icon       = isset( $page['icon'] ) && ! empty( $page['icon'] ) ? $page['icon'] : HZFEX_Setting_Framework_Url . 'assets/graphics/gear-icon.svg';

                /**
                 * WPHOOK: Filter -> change all capabilities at once.
                 * 
                 * This can be useful for forcing all submenu pages
                 * accessible to users that have a particular capability
                 * instead of changing `cap` value from each child-class.
                 * 
                 * @var string
                 * 
                 * @example Usage:
                 * ```
                 *  add_filter( 'hzfex_set_setting_page_capability', 'common_capability' );
                 *  function common_capability( $cap ) {
                 *      return 'edit_posts';
                 *  }
                 * ``` 
                 */
                $cap = apply_filters( 'hzfex_set_setting_page_capability', $cap );

                // checks if menu already exists.
                $existing_menu = $this->menu_exists( $menu_slug );

                // sets menu slug.
                $menu = $existing_menu ? $menu_slug : $this->menu_slug;

                /**
                 * WPHOOK: Filter -> force change menu slug for all child-class submenu pages
                 * 
                 * Useful if want to get all child-class to have same main menu
                 * even though it had different menu set.
                 * 
                 * @var string
                 * 
                 * @example Usage:
                 * ```
                 *  add_filter( 'hzfex_set_setting_main_menu_slug', 'set_menu_slug' );
                 *  function set_menu_slug( $slug ) {
                 *      return 'hzfex_setting';
                 *  }
                 * ``` 
                 */
                $menu = apply_filters( 'hzfex_set_setting_main_menu_slug', $menu );

                // Sets each child-class args as an array of subpage navigations.
                self::set_subpages_nav(
                    $class,
                    [
                        'menu_slug'    => $menu,
                        'page_title'   => $page_title,
                        'menu_title'   => $menu_title,
                        'cap'          => $cap,
                        'slug'         => $slug,
                        'icon'         => $icon
                    ]
                );
                    
                // Registers submenu page to main menu
                $this->submenu_hook[$class] = add_submenu_page(
                    $menu,
                    $page_title,
                    $menu_title,
                    $cap,
                    $slug,
                    [ $this, 'load_sections' ], 
                    $priority
                );

                // Sets Settings API.
                $this->settings = new Settings_API();

                // Registers each child-class page sections and fields.
                add_action( 'admin_init', [ $this, 'add_page' ], 15 );

                // Gets Setting Fields
                require_once __DIR__ . '/setting-fields.php';

                // Hooks on each submenu page load
                add_action( 'load-' . $this->submenu_hook[$class], [ $this, 'load_assets' ] );

                /**
                 * Filters user capability to manage each submenu page
                 * 
                 * Works in this order:
                 * - Checks if submenu page has sections
                 * - Loops through all sections to get the page ID
                 * - Sets cap {@property `$subpage_cap`} {@see @method `set_subpages_cap()`}
                 * - Adds filter for changing user capability to view/save/update settings
                 */
                if( $this->valid_sections() ) {
                    foreach( $this->valid_sections() as $section ) {
                        self::set_subpages_cap( $section['id'], $cap );
                        add_filter( "option_page_capability_{$section['id']}", [ $this, 'set_capability' ] );
                    }
                }

                /**
                 * WPHOOK: Action -> after successfull loading of submenu page
                 */
                do_action( 'hzfex_setting_framework_loaded' );
            }
        }
    }

    /**
     * Filters user capability to view/save/update settings
     *
     * @param string $cap Current User Capability
     * 
     * @return string the desired user capability
     * 
     * @since 1.0
     * 
     * @access public
     */
    public function set_capability( $cap ) {

        $subpages_cap = (array) self::$subpages_cap;

        // Sets default cap if cap not set for each submenu page.
        if( sizeof( $subpages_cap ) === 0 ) {
            return $this->default_cap;
        }

        // Loops through submenu pages to get page ID and capability
        foreach( $subpages_cap as $page_id => $capability ) {
            foreach( $this->valid_sections() as $options_page ) {

                // Sets capability once page ID matches.
                if( in_array( $page_id, $options_page ) ) {
                    return $capability;
                }
            }
        }

        // Fallback if nothing worked.
        return $this->default_cap;
    }

    /**
     * Hooks and loads assets on each submenu page.
     * 
     * @since 1.0
     * 
     * @access public
     */
    public function load_assets() {

        // core style/script
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_media();
        wp_enqueue_script( 'jquery' );
        
        // enqueue setting page stylesheet.
        wp_enqueue_style( 'hzfex_setting_page_style' );

        /**
         * WPHOOK: Action -> After loading of each submenu page
         * 
         * This hook can only be accessed on pages created by child-class
         */
        do_action( 'hzfex_setting_framework_page_loaded' );
        
    }

    /**
     * Hooks setting sections and it's fields.
     * 
     * @since 1.0
     * 
     * @access public
     */
    public function load_sections() {

        $sections   = $this->valid_sections();
        ?>

        <!-- HZFEX_Core Setting Framework API -->
        <div id="hzfex_setting_framework" class="hz_min_container hz_flx">

            <?php
                $wrapper = 'hz_content_first hz_sml_content';
                self::get_setting_nav( false, true, true, $wrapper );
            ?>
            
            <!-- hz_setting_section -->
            <div id="hz_setting_section" class="hz_lrg_content wrap">
            
                <?php
                // Displays section die msg if section  isn't an array
                if( ! is_array( $sections ) ) die( $this->section_die() );

                /**
                 * WPHOOK: Filter -> display section navigation if page has single section?
                 * 
                 * Controls whether or not to show nav if page has only one section.
                 * 
                 * @example - usage:
                 * 
                 * Shows nav even if only have one section.
                 * add_filter( 'hzfex_show_setting_sections_nav_if_single', '__return_true' );
                 * 
                 */
                $show_nav = apply_filters( 'hzfex_show_setting_sections_nav_if_single', false );
                
                // Sets no of sections to be defined to show navigation.
                $section_count = $show_nav ? 0 : 1;
                
                // show section tabs if have atleast two sections
                if( sizeof( $sections) > $section_count ) $this->settings->show_navigation();

                // set section form fields
                $this->settings->show_forms();
                ?>

            </div>
            <!-- #hz_setting_section -->
        </div>
        <!-- HZFEX_Core Setting Framework API end -->

        <?php
    }

    /**
     * Hooks WordPress setting page with sections and fields.
     * 
     * @since 1.0
     * 
     * @access public
     */
    public function add_page() {

        // bail early if no section defined
        if( false === $this->validate_sections() ) return;

        // Sets sections for Settings API.
        $this->settings->set_sections( $this->valid_sections() );

        // Sets fields for Settings API.
        $this->settings->set_fields( $this->valid_fields() );

        // Initializes settings.
        $this->settings->register_setting();
    }
    
    /**
     * Sets page navigations.
     * 
     * This method can be called by other frameworks.
     * Doing so will set this nav in their page also.
     * The page nav system will then look uniform.
     *
     * @param bool $container   add CSS for container
     * @param bool $head        Show setting head
     * @param bool $page        is child-class setting page
     * @param string $wrapper   CSS for wrapper
     * @param string $item      CSS for link items
     * 
     * @return false/void false if empty nav array
     * 
     * @since 1.0
     * 
     * @static
     * 
     * @access public
     */
    public static function get_setting_nav( $container = false, $head = false, $page = false, $wrapper = '', $item = 'list' ) {

        $nav = (array) self::$subpages_nav;

        // bail early if no submenu pages set.
        if( sizeof( $nav ) === 0 ) return false;

        $head_image = HZFEX_Setting_Framework_Url . '/assets/graphics/setting-head.svg';

        $user       = wp_get_current_user();
        $screen_id  = get_current_screen()->id;
        $count      = count( $nav );
        $count_cls  = $count > 0 ? ' hz_flx_'.$count : '';
        $count_cls  = $count >= 3 ? ' hz_flx_3' : $count_cls;

        /**
         * WPHOOK Filter -> modify nav links
         * 
         * Useful to add other links than default submenu page links.
         * 
         * @var array
         * 
         * @example usage:
         * 
         * add_filter( 'hzfex_set_setting_page_nav_links', 'set_page_nav' );
         * function set_page_nav( $nav ) {
         *      $nav['test'] = [
         *           'page_title'   => __( 'Test page title', 'tws-core' ),
         *           'menu_title'   => __( 'Test page title', 'tws-core' ),
         *           'cap'          => 'edit_posts',
         *           'slug'         => 'test_nav_slug', // menu/submenu page must exist with this slug
         *           'icon'         => 'http://path/to/image/url'
         *       ];
         * 
         *      return $nav;
         * }
         */
        $nav        = apply_filters( 'hzfex_set_setting_page_nav_links', $nav );

        /**
         * WPHOOK: Filter -> modify head image 
         * 
         * @var string
         * 
         * @example usage:
         * 
         * add_filter( 'hzfex_set_setting_page_head_image', 'set_page_head_image' );
         * function set_page_head_image( $url ) {
         *      return "https://url/to/your/image";
         * }
         */
        $head_image = apply_filters( 'hzfex_set_setting_page_head_image', $head_image );
        $image      = function_exists( 'hz_get_svg_icon' ) ? hz_get_svg_icon( 'setting' ) : '<img src="'. $head_image . '">';

        if( $container ) echo '<div class="'.$container.'">';
            ?>
            <!-- hz_setting_navigation -->
            <div id="hz_setting_navigation" class="<?= $wrapper; ?>">

                <?php if( $head ) { 
                    
                    /**
                     * WPHOOK: Action -> before page head
                     */
                    do_action( 'hzfex_before_setting_page_head' );
                    ?>
                    
                    <div class="hz_setting_nav_head">
                        <?php if( function_exists( 'tws_core' ) ) : ?>
                            <a href="<?= admin_url( 'admin.php?page=hzfex_setting' ); //FIXME: page slug ?>">
                        <?php endif; ?>
                            <figure><?= $image; ?></figure>
                            <h1>
                            <?php

                                /**
                                 * WPHOOK: Filter -> change setting page main title below head image
                                 * 
                                 * @var string
                                 * 
                                 * @example usage:
                                 * 
                                 * add_filter( 'hzfex_set_setting_head_title', 'header_title' );
                                 * function header_title( $title ) {
                                 *      $title = __( 'My Setting Framework', 'tws-core' );
                                 * 
                                 *      return $title;
                                 * }
                                 */
                                $title = apply_filters( 'hzfex_set_setting_head_title', __( 'Setting', 'tws-core' ) );

                                echo esc_attr( $title );
                            ?>
                            </h1>
                            <small>v.<?= HZFEX_Setting_Framework_Version; ?></small>
                        <?php if( function_exists( 'tws_core' ) ) : ?> </a> <?php endif; ?>
                    </div>

                    <?php 
                    /**
                     * WPHOOK: Action -> after page head
                     */
                    do_action( 'hzfex_after_setting_page_head' );
                } ?>

                <div class="hz_flx">

                    <?php
                    /**
                     * WPHOOK: Action - before page nav links
                     */
                    do_action( 'hzfex_before_setting_page_nav' );

                    foreach( $nav as $option ) {

                        $icon       = function_exists( 'hz_get_svg_icon' ) ? hz_get_svg_icon( $option['icon'] ) : '<img src="' .$option['icon']. '">'; // TODO add image for this in framework.
                        $current    = strpos( $screen_id, $option['slug'] ) !== false ? ' current' : '';
                        
                        // Quick check if user has assigned capability before showing nav link
                        if( $user->has_cap( $option['cap'] ) ) : ?>
                            <a
                            class="hz_flx hz_flx_content hz_setting_page_nav <?= $current . ' '. $item; ?> hz_<?= sanitize_title( $option['page_title'] ); ?>_nav<?= $page ? ' is_page' : ''; ?>"
                            href="<?= admin_url( 'admin.php?page=' . $option['slug'] ); ?>">
                                <div class="hz_setting_icon hz_icon"><?= $icon; ?></div>
                                <div class="hz_info"><?= $option['page_title'] ; ?></div>
                            </a>
                        <?php endif;
                    } 
                    
                    /**
                     * WPHOOK: Action -> after page nav links
                     */
                    do_action( 'hzfex_after_setting_page_nav' );
                    ?>

                </div>
            </div>
            <!-- #hz_setting_navigation -->
            <?php
        if( $container ) echo '</div>';
    }

    /**
     * Sets submenu page navigation from each child-class.
     * 
     * @param string $class the child-class
     * @param array $args submenu page data. `menu_slug`|`page_title`|`menu_title`|`cap`|`slug`|`icon`
     * 
     * @return array child-class as key and submenu page data args as value
     * 
     * @since 1.0
     * 
     * @static
     * 
     * @access private
     */
    private static function set_subpages_nav( $class = '', $args = [] ) {
        self::$subpages_nav[$class] = $args;
    }

    /**
     * Sets each submenu pages user capability
     *
     * @param string $page_id The setting page ID
     * @param string $cap The user capability
     * 
     * @return array page ID as key and capability as value
     * 
     * @since 1.0
     * 
     * @static
     * 
     * @access private
     */
    private static function set_subpages_cap( $page_id = '', $cap = '' ) {
        self::$subpages_cap[$page_id] = $cap;
    }

    /**
     * Die message for no setting page section
     * 
     * @return string
     * 
     * @since 1.0
     * 
     * @access private
     */
    private function section_die() {

        $die = '<div class="hz_die_msg">Add sections and fields to be displayed here.<br>EXAMPLE</br>';
        $die .= '<pre>' . 
            print_r( "\$section =
            [
                'first_section_id_here'   =>
                [
                    'title'         => 'Section Title', // as section title
                    'tab_title'     => 'Tab Title', // as tab title
                    'fields'        => [ // different field types in an array
                        'first_field_id_here'   =>
                        [
                            'label'     => 'First Field',
                            'desc'      => 'This is first text field type.',
                            'type'      => 'text', // @see Method \$field_types
                            'class'     => 'define_class', // for styling
                            'priority'  => 5 // for positioning
                        ],
                        // next field here
                    ]
                ],
                // next section here
            ];",
            true ) .
        '</pre>';
        $die .= '</div>';

        return $die;

    }

    /**
     * Sets fields data after validation.
     *
     * @return array Fields in an array
     * 
     * @since 1.0
     * 
     * @access private
     */
    private function valid_fields() {

        // get validated sections
        $section =  $this->validate_sections();

        // bail if no section
        if( false === $section ) return false;

        foreach( $section as $section_id => $section_data ) {

            if( isset( $section_data['fields'] ) ) {
                /** 
                 * WPHOOK Filter - Extend each setting section fields
                 * 
                 * NOTE: Use child-class instead
                 * - Highly recommended to add all fields from child-class {@method `sections()`}.
                 * - Use this filter only to add new fields to already an existing section added by others.
                 * 
                 * @param string $section_id -> the section key where field should be added.
                 * @param array $section_data['fields'] -> an array of fields that belong to that section.
                 * 
                 * @example usage:
                 * 
                 * If the $section_id is set as -> [`test_section`]
                 * 
                 * add_filter( "hzfex_setting_test_section_fields", "add_new_fields" );
                 * function add_new_fields( $fields ) {
                 * 
                 *      $fields['new_test_field'] = [
                 *          'label'             => 'New Test Field',
                 *          'desc'              => 'New Test Description',
                 *          'placeholder'       => 'Placeholder.... anything....',
                 *          'type'              => 'text',
                 *          'sanitize_callback  => 'sanitize_text_field',
                 *          'priority'          => 5
                 *      ];
                 *      
                 *      return $fields; // this is required to return back all fields.
                 * }
                 */
                $fields[$section_id] = apply_filters( "hzfex_setting_{$section_id}_fields", $section_data['fields'] );

                /**
                 * Sorts fields by priority, if set, inside each section.
                 * 
                 * NOTE: Plugin specific feature
                 * - This feature is only available in plugin and not in this framework.
                 * 
                 * @link TODO: add github/wordpress plugin link here.
                 * 
                 * @uses    hzfex_sort_by_priority() Sorts array data by priority
                 */
                if( function_exists( '\TheWebSolver\Plugin\Core\hzfex_sort_by_priority' ) ) {
                    uasort( $fields[$section_id], '\TheWebSolver\Plugin\Core\hzfex_sort_by_priority' );
                }

            }
        }
        return $fields;
    }

    /**
     * Sets sections data after validation.
     *
     * @return array/false Sections in an array, false if no section data
     * 
     * @since 1.0
     * 
     * @access private
     */
    private function valid_sections() {

        // get validated sections
        $section =  $this->validate_sections();

        // bail if no section
        if( false === $section ) return false;

        foreach( $section as $key => $s ) {
            $sections[]	= [ 
                'id'        => $key, 
                'title'     => isset( $s['title'] ) && ! empty( $s['title'] ) ? $s['title'] : '', 
                'desc'      => isset( $s['desc'] ) && ! empty( $s['desc'] ) ? $s['desc'] : '',
                'callback'  => isset( $s['callback'] ) && ! empty( $s['callback'] ) ? $s['callback'] : '',
                'tab_title' => isset( $s['tab_title'] ) && ! empty( $s['tab_title'] ) ? $s['tab_title'] : $s['title'],
                'fields'    => isset( $s['fields'] ) && ! empty( $s['fields'] ) ? $s['fields'] : false
            ];
        }

        return $sections;
    }

    /**
     * Validate Sections from child-class
     * 
     * @return array/false array of sections data if defined, else false
     * 
     * @since 1.0
     * 
     * @access private
     */
    private function validate_sections() {

        $sections = $this->sections();

        // section has array of fields data
        if( ! is_array( $sections ) || sizeof( $sections ) === 0 ) return false;
            
        return $sections;
    }

    /**
     * Gets sections and fields data.
     * 
     * This method must be overridden from the child-class.
     * 
     * @return array
     * 
     * @since 1.0
     * 
     * @access protected
     */
    protected function sections() {
        $die    = '<div class="hz_die_msg"><b><em>Method <code>\TheWebSolver\Plugin\Core\Setting_Component::sections()</code></em></b> must be overridden in a child-class to set sections and fields.';
        die( $die );
    }

    /**
     * Checks if main menu already exists
     *
     * @param string $slug The main menu slug to look for
     * 
     * @return bool True if already exists, else false
     * 
     * @since 1.0
     * 
     * @access private
     */
    private function menu_exists( $slug ) {

        global $menu;

        $menu_slugs = array_column( $menu, 2 );

        return in_array( $slug, $menu_slugs ) ? true : false;
    }

} // end class