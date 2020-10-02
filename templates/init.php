<?php
/**
 * Init demo
 * 
 * This file initializes all demo files and codes.
 * 
 * @since       1.0
 * 
 * @package     tws-core
 * @subpackage  framework
 * @category    template
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

/**
 * defines main menu slug.
 * 
 * TODO: Replace menu slug
 * 
 * replace this constant value to different main menu slug
 * if want to use your own or existing main menu.
 * 
 * @var string menu slug
 * 
 * @example usage:
 * 
 * Change main menu to one of WordPress default -> `Settings`
 * 
 * ```
 * define( 'HZFEX_SETTING_FRAMEWORK_MENU', 'options-general.php' );
 * ```
 */
define( 'HZFEX_SETTING_FRAMEWORK_MENU', 'tws_setting_framework' );


function setup_plugin() {
   /**
    * Include example child-class files
    *
    * NOTE: the file included first comes first in main menu.
    */
   include_once 'without-fields.php';
   include_once 'with-fields.php';
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\setup_plugin' );

function enqueue_scripts() {

   // register stylesheet.
   wp_register_style( 'hzfex_setting_page_style', HZFEX_Setting_Framework_Url . 'assets/style.css', HZFEX_Setting_Framework_Version );

   // if only framework loaded.
   if( ! function_exists( 'tws_core' ) ) {

      // select2 for customized select fields.
      wp_register_script( 'hzfex_select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.min.js', ['jquery'], '4.0.12', true );
      wp_register_style( 'hzfex_select2_style', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2.min.css', [], '4.0.12', 'all' );
   }
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_scripts' );

/**
 * Creates main menu where child-class submenu pages is hooked.
 *
 * @return void
 */
function tws_setting_framework_main_menu() {
    add_menu_page(
            __( 'The Web Solver', 'tws-core' ),
            __( 'The Web Solver', 'tws-core' ),
            'read',
            HZFEX_SETTING_FRAMEWORK_MENU,
            __NAMESPACE__ . '\tws_setting_framework_main_page',
            '',
            3.1008
    );
}
// adds main menu page only if menu constant is defined as 'tws_setting_framework'.
if( HZFEX_SETTING_FRAMEWORK_MENU === (string) 'tws_setting_framework' ) {
    add_action( 'admin_menu', __NAMESPACE__ . '\tws_setting_framework_main_menu' );
}

/**
 * Includes welcome template for main menu created by `tws_setting_framework_main_menu()`.
 *
 * @return void
 */
function tws_setting_framework_main_page() {
   include_once HZFEX_Setting_Framework_Path . 'templates/contents/welcome.php';
   wp_enqueue_style( 'hzfex_setting_page_style' );
};

/**
 * Removes menu link created by `tws_setting_framework_main_menu()` when adding main menu.
 * 
 * This way only child-class submenus as displayed under main menu `The Web Solver`
 * 
 * Remove this if you want to display main menu page link in admin menu sidebar.
 *
 * @return void
 */
function tws_setting_framework_remove_main_submenu() {
   remove_submenu_page( 'tws_setting_framework', 'tws_setting_framework' );
}
add_action( 'admin_menu', __NAMESPACE__ . '\tws_setting_framework_remove_main_submenu', 999 );