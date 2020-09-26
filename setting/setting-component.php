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
 * TheWebSolver\Plugin\Core\Setting_Component class
 */
class Setting_Component {

    /**
     * WordPress Core Settings API
     * 
     * @var object Sets an instance of `Settings_API()` class
     * 
     * @since 1.0
     * 
     * @access private
     */
    private $settings;

    /**
     * Child-class submenu hooks
     * 
     * @var array Hooks submenus created using `add_submenu_page()`
     * 
     * @since 1.0
     * 
     * @access public
     */
    public $submenu_hook = [];

    /**
     * Child-class submenu pages
     * 
     * @var array Sets each child-class submenu page in an array
     * 
     * @since 1.0
     * 
     * @access public
     */
    public $subpages = [];

    /**
     * Default User Capability
     * 
     * @var string Sets default user capability that can access each child-class submenu page
     * 
     * @since 1.0
     * 
     * @access private
     */
    private $default_cap = 'manage_options';

    /**
     * User capabilities for submenu pages
     *
     * @var array Sets user capability for each submenu page in an array
     * 
     * @since 1.0
     * 
     * @access private
     */
    private static $subpages_cap = [];

    /**
     * Page navigations
     *
     * @var array Sets each child-class navigation for each submenu page in an array
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
     * The subclass should set this parent constructor from it's own constructor
     * 
     * @param array $subclass  {
     * 
     * Array with key as subclass and values as an array of submenu page arguments as follows:
     * 
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
     * @example -
     * --------------------------------------------
     * Use the template below to construct subclass
     * --------------------------------------------
     * 
     *   ```
     *   use TheWebSolver\Plugin\Core\Setting_Component;
     * 
     *   final class Subclass_Setting extends Setting_Component {
     *       public function __construct() {
     *           parent::__construct( 
     *               [
     *                   __CLASS__ => [ // change these data
     *                       'page_title'    => __( 'Menu Title Here', 'tws-core' ),
     *                       'menu_title'    => __( 'Page Title Here', 'tws-core' ),
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
     *           $section = [
     *               'test_section' => [
     *                   'title'     => __( 'General Setting', 'tws-core' ),
     *                   'tab_title' => __( 'General', 'tws-core' ),
     *                   'desc'      => sprintf( '<p>%1$s</p>', __( 'General Plugin Setting', 'tws-core' ) ), // used as callback to display content between title and fields
     *                   'fields'    => [
     *                       'field_id_here'     => [
     *                           'label' => __( 'Field Title here', 'tws-core' ),
     *                           'desc'  => __( 'Field details here', 'tws-core' ),
     *                           'type'  => 'text',
     *                           'priority'  => 5
     *                       ],
     *                       // next field here
     *                   ],
     *               ],
     *               // new section here
     *           ];
     *           return $section;
     *       }
     *   }
     *   ```
     */
    public function __construct( $subclass = [] ) {

        // Sets child-class submenu pages.
        $this->subpages = $subclass;

        // Sets Settings API.
        $this->settings = new Settings_API();

        // Registers each child-class submenu pages.
        add_action( 'admin_menu', [ $this, 'menu' ], 99 );

        // Registers each child-class page sections and fields.
        add_action( 'admin_init', [ $this, 'add_page' ], 15 );

        // Requires Settings Fields Controllers.
        require_once __DIR__ . '/setting-controller.php';
    }

    /**
     * Registers each child-class submenu pages.
     * 
     * @since 1.0
     * 
     * @access public
     */
    public function menu() {

        $subpages = $this->subpages;

        foreach( $subpages as $class => $page ) {

            // Validates each child-class args.
            $slug       = isset( $page['slug'] ) ? $page['slug'] : false;

            // Bail early if slug is not set in clild-class args.
            if( false === $slug ) return false;

            $page_title = isset( $page['page_title'] ) && ! empty( $page['page_title'] ) ? $page['page_title'] : 'The Web Solver';
            $menu_title = isset( $page['menu_title'] ) && ! empty( $page['menu_title'] ) ? $page['menu_title'] : $page_title;
            $cap        = isset( $page['cap'] ) && ! empty( $page['cap'] ) ? $page['cap'] : $this->default_cap;
            $priority   = isset( $page['priority'] ) ? $page['priority'] : null;
            $icon       = isset( $page['icon'] ) && ! empty( $page['icon'] ) ? $page['icon'] : 'gear';

            /**
             * WPHOOK: Filter to change all capabilities at once.
             * 
             * This can be useful for forcing all submenu pages
             * accessible to users that have a particular capability
             * instead of changing `cap` value from each child-class.
             * 
             * @example - 
             * ```
             *  add_filter( 'hzfex_options_page_capability', 'common_capability' );
             *  function common_capability( $cap ) {
             *      return 'edit_posts';
             *  }
             * ``` 
             */
            $cap = apply_filters( 'hzfex_options_page_capability', $cap );

            // Sets each child-class args as an array of subpage navigations.
            $this->set_subpages_nav(
                $class,
                [
                    'page_title'   => $page_title,
                    'menu_title'   => $menu_title,
                    'cap'          => $cap,
                    'slug'         => $slug,
                    'icon'         => $icon
                ]
            );
                
            // Registers submenu page to parent
            $this->submenu_hook[$class] = add_submenu_page(
                'hz_setting', // FIXME set static slug at admin menu and use that here.
                $page_title,
                $menu_title,
                $cap,
                $slug,
                [ $this, 'load_sections' ], 
                $priority
            );

            // Hooks on each submenu page load
            add_action( 'load-' . $this->submenu_hook[$class], [ $this, 'load_assets' ] );

            /**
             * Filters user capability to manage each submenu page
             * 
             * Works in this order:
             * * Checks if submenu page has sections
             * * Loops through all sections to get the page ID
             * * Sets `$subpage_cap` data using method `set_subpages_cap()`
             * * Adds filter for changing user capability to save/update the options
             */
            if( $this->valid_sections() ) {
                foreach( $this->valid_sections() as $section ) {
                    self::set_subpages_cap( $section['id'], $cap );
                    add_filter( "option_page_capability_{$section['id']}", [ $this, 'set_capability' ] );
                }
            }

        }

    }

    /**
     * Sets each submenu pages user capability
     *
     * @param string $options_page_id The setting page ID
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
     * Sets subpage navigation from each child-class.
     * 
     * @since 1.0
     * 
     * @static
     * 
     * @access private
     */
    private static function set_subpages_nav( $class = '', $value = '' ) {
        self::$subpages_nav[$class] = $value;
    }

    /**
     * Filters user capability to save/update options
     *
     * @param string $cap   Current User Capability
     * 
     * @return string
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

        foreach( $subpages_cap as $page_id => $capability ) {
            foreach( $this->valid_sections() as $options_page ) {

                // Checks if setting page and it's capability matches.
                if( in_array( $page_id, $options_page ) ) {
                    return $capability;
                }
            }
        }
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

        // load necessary style and script
        wp_enqueue_style( 'hzfex_style' );
        wp_enqueue_script( 'hzfex_script' );

        // support WP admin color scheme
        add_filter( 'admin_body_class', [ $this, 'has_color_scheme_support' ] );
        
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
                // set section naviagation content
                if( is_array( $sections ) ) {

                    /**
                     * WPHOOK: Filter - display navigation bar on single section?
                     * 
                     * Controls whether or not to show nav if page has only one section.
                     * 
                     * @example - shows nav even if only have one section.
                     *  add_filter( 'hzfex_show_setting_sections_nav_if_single', '__return_true' );
                     * 
                     */
                    $show_nav = apply_filters( 'hzfex_show_setting_sections_nav_if_single', false );
                    
                    // Sets no of sections to be defined to show navigation.
                    $section_count = $show_nav ? 0 : 1;
                    
                    // show section tabs if have atleast two sections
                    if( sizeof( $sections) > $section_count ) $this->settings->show_navigation();

                    // set section form fields
                    $this->settings->show_forms();

                } else {

                    die( $this->section_die() );
                }
                ?>
            </div>
            <!-- #hz_setting_section -->
        </div>
        <!-- HZFEX_Core Setting Framework API end -->

        <?php

    }

    /**
     * Hooks WordPress setting sections and fields.
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
     * This method can be called by other frameworks to hook their own navigation links.
     *
     * @param bool $container   add CSS for container
     * @param bool $head        Show setting head
     * @param bool $page        is subclass setting page
     * @param string $wrapper   CSS for wrapper
     * @param string $item      CSS for link items
     * 
     * @since 1.0
     * 
     * @static
     * 
     * @access public
     */
    public static function get_setting_nav( $container = false, $head = false, $page = false, $wrapper = '', $item = 'list' ) {

        $nav = (array) self::$subpages_nav;

        $user       = wp_get_current_user();
        $screen_id  = get_current_screen()->id;
        $count      = count( $nav );
        $count_cls  = $count > 0 ? ' hz_flx_'.$count : '';
        $count_cls  = $count >= 3 ? ' hz_flx_3' : $count_cls;
        $image      = hz_get_svg_icon( 'setting' );

        /**
         * WPHOOK Filter - modify nav links
         * 
         * Useful to add other links than default submenu page links.
         */
        $nav    = apply_filters( 'hzfex_setting_modify_nav_links', $nav );

        /**
         * WPHOOK Filter - modify head image
         * 
         * Useful to change header image.
         */
        $image  = apply_filters( 'hzfex_setting_modify_head_image', $image );

        ?>

        <!-- hz_setting_navigation -->
        <div id="hz_setting_navigation" class="<?= $wrapper; ?>">

            <?php if( $head ) { 
                
                /**
                 * WPHOOK: Action - before page head
                 */
                do_action( 'hzfex_before_setting_page_head' );
                ?>
                
                <div class="hz_setting_nav_head">
                    <a href="<?= admin_url( 'admin.php?page=hz_setting' ); //FIXME: page slug ?>">
                        <figure><?= $image; ?></figure>
                        <h1>
                        <?php

                            $title = __( 'Setting', 'tws-core' );

                            /**
                             * WPHOOK: Filter - change setting page main title below head image
                             */
                            apply_filters( 'hzfex_setting_head_title', $title );

                            echo $title;
                        ?>
                        </h1>
                    </a>
                </div>

                <?php 
                /**
                 * WPHOOK: Action - after page head
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

                    $icon       = function_exists( 'hz_get_svg_icon' ) ? hz_get_svg_icon( $option['icon'] ) : '';
                    $current    = strpos( $screen_id, $option['slug'] ) !== false ? ' current' : '';
                    
                    // Quick check if user have capability before showing nav link
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
                 * WPHOOK: Action - after page nav links
                 */
                do_action( 'hzfex_after_setting_page_nav' );
                
                ?>

            </div>

        </div>
        <!-- #hz_setting_navigation -->

        <?php
    }

    /**
     * Die message for no setting page section
     * 
     * @since 1.0
     * 
     * @return mixed
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
     * Sets sections data after validation.
     *
     * @return array Sections in an array
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
                'title'     => isset( $s['title'] ) ? $s['title'] : '', 
                'desc'      => isset( $s['desc'] ) ? $s['desc'] : '',
                'tab_title' => isset( $s['tab_title'] ) ? $s['tab_title'] : $s['title'],
                'fields'    => isset( $s['fields'] ) ? $s['fields'] : false
            ];
        }

        return $sections;
    }

    /**
     * Sets fields data after validation.
     * 
     * @access private
     *
     * @return array Fields in an array
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
                 * NOTE: It's better to add all fields from child-class rather than using this filter.
                 * One limitation of using this filter is that fields added using this filter
                 * can't be sorted by priority used in method `validate_sections`
                 * 
                 * @param string $section_id -> is the section key where field should be displayed.
                 * @param array $s['fields'] -> is an array of fields that belong to the section.
                 * 
                 * @example
                 * 
                 * If the section $key is set as -> [`test_section`]
                 * 
                 * add_filter( "hzfex_setting_test_section_fields", "add_new_fields" );
                 * function add_new_fields( $fields ) {
                 * 
                 *      $fields['new_test_field'] = [
                 *          'label'             => 'New Text Field',
                 *          'desc'              => 'Simple Description',
                 *          'placeholder'       => 'Enter your proper text data',
                 *          'type'              => 'text',
                 *          'sanitize_callback  => 'sanitize_text_field',
                 *          'priority'          => 50
                 *      ];
                 *      
                 *      return $fields; // this is required to return back all fields.
                 * }
                 */
                $fields[$section_id] = apply_filters( "hzfex_setting_{$section_id}_fields", $section_data['fields'] );

            }
        }
        return $fields;
    }

    /**
     * Validate Sections set from subclass
     * 
     * @since 1.0
     * 
     * @access private
     *
     * @return array/bool array of sections data if defined, else false
     */
    private function validate_sections() {

        $sections = $this->sections();

        // section has array of fields data
        if( is_array( $sections ) && count( $sections ) > 0 ) {
            /** 
             * NOTE: 
             * * Sorting function to arrange fields inside each section.
             * * This features doesn't work in framework.
             * * To use this, please use the full plugin.
             * 
             * @link TODO: add github/wordpress plugin link here.
             * 
             * @uses    hzfex_sort_by_priority() Sorts array data by priority
             * @internal -
             * * Can only sort fields by priority added from method `sections()`
             * * Doesn't sort fields by priority added using filter on method `valid_fields()`
             */
            if( function_exists( __NAMESPACE__ . '\hzfex_sort_by_priority' ) ) {

                // Sort each of the fields based on priority
                foreach ( $sections as $section_id => $section_data ) {

                    if( isset( $section_data['fields'] ) ) {

                        $fields = $sections[$section_id]['fields'];

                        uasort( $fields, __NAMESPACE__ . '\hzfex_sort_by_priority' );
                    }
                }
            }
            return $sections;
        }
        return false;
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
        $die    = '<div class="hz_die_msg"><b><em>Method <code>\TheWebSolver\Plugin\Core\Setting_Component::sections()</code></em></b> must be overridden in a subclass to set sections and fields.';
        die( $die );
    }

    /**
     * WordPress Color Scheme support. Callback function from subclass.
     * 
     * TODO: check later.
     * 
     * @return string adds class to body tag in admin
     * 
     * @since 1.0
     * 
     * @access public
     */ 
    public function has_color_scheme_support() {
        return 'hz_color_scheme_support';
    }

} // end class