<?php
/**
 * Template file to display content on welcome tab
 * 
 * @since 1.0
 * 
 * @package tws-core
 * @subpackage framework
 * @category template
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

// exit if file is accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

?>

<div id="hzfex_welcome">
    <div class="hz_flx">
        <div class="hz_welcome_image">
            <img src="<?= HZFEX_Setting_Framework_Url . 'assets/graphics/setting.png'; ?>">
        </div>
    </div>
    <div class="hz_container_content hz_center">
        <h2>TWS Setting Framework Features</h2>
        <div class="hz_inc_wrapper">
            <div class="hz_isometric">
                <svg class="hz_sml_svg hz_triangle_svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 148.65 106.28">
                    <g class="svg_isolate">
                        <g class="svg_blend_mode_overlay">
                            <polygon class="svg_nofill svg_stroke" points="145.08 104.84 51.42 1.4 1.97 67.94 145.08 104.84"></polygon>
                            <polygon class="svg_nofill svg_stroke" points="1.97 67.94 54.57 53.91 51.42 1.4 1.97 67.94"></polygon>
                            <polygon class="svg_nofill svg_stroke" points="54.57 53.91 1.97 67.94 145.08 104.84 54.57 53.91"></polygon>
                        </g>
                    </g>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 34.22 34.22" class="hz_sml_svg hz_plus_svg">
                    <g class="svg_isolate">
                        <g class="svg_blend_mode_overlay">
                            <polygon class="svg_fill" points="34.22 18.98 22.96 14.56 27.39 3.3 18.98 0 14.56 11.26 3.3 6.84 0 15.24 11.26 19.66 6.84 30.92 15.24 34.22 19.66 22.96 30.92 27.39 34.22 18.98"></polygon>
                        </g>
                    </g>
                </svg>
                
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 82.67 87.36" class="hz_sml_svg hz_triangle3d_svg">
                    <g class="svg_isolate">
                    <g class="svg_blend_mode_overlay">
                        <polygon class="svg_nofill svg_stroke" points="1.7 24.23 60.32 83.39 80.94 4.64 1.7 24.23"></polygon>
                        <polygon class="svg_nofill svg_stroke" points="80.94 4.64 60.55 45.99 60.32 83.39 80.94 4.64"></polygon><polygon class="svg_nofill svg_stroke" points="60.55 45.99 80.94 4.64 1.7 24.23 60.55 45.99"></polygon></g></g>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44.8 44.8" class="hz_sml_svg hz_square_svg">
                    <g class="svg_isolate">
                        <g class="svg_blend_mode_overlay"><path class="svg_fill" d="M0,22.4,22.4,44.8,44.8,22.4,22.4,0Zm37,0L22.4,37,7.77,22.4,22.4,7.77Z"></path></g>
                    </g>
                </svg>
            </div>
            <div class="hz_flx hz_flx_2">
                <div class="hz_flx hz_flx_content grid_item lite_blue create_page">
                    <div class="hz_thumb left">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-devices" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#0277bd" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z"></path>
                            <rect x="13" y="8" width="8" height="12" rx="1"></rect>
                            <path d="M18 8v-3a1 1 0 0 0 -1 -1h-13a1 1 0 0 0 -1 1v12a1 1 0 0 0 1 1h9"></path>
                            <line x1="16" y1="9" x2="18" y2="9"></line>
                        </svg>
                    </div>
                    <div class="hz_thumb_details right">
                        <h4>Create Page and Sections</h4>
                        <p class="hz_color_off hz_small">Any content inside section. Even callback content like this.</p>
                    </div>
                </div>
                <div class="hz_flx hz_flx_content grid_item ease_use lite_purple">
                    <div class="hz_thumb left">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-click" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#6a1b9a" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z"></path>
                            <line x1="3" y1="12" x2="6" y2="12"></line>
                            <line x1="12" y1="3" x2="12" y2="6"></line>
                            <line x1="7.8" y1="7.8" x2="5.6" y2="5.6"></line>
                            <line x1="16.2" y1="7.8" x2="18.4" y2="5.6"></line>
                            <line x1="7.8" y1="16.2" x2="5.6" y2="18.4"></line>
                            <path d="M12 12l9 3l-4 2l-2 4l-3 -9"></path>
                        </svg>
                    </div>
                    <div class="hz_thumb_details right">
                        <h4>Easy to use and implement</h4>
                        <p class="hz_color_off hz_small">If you are getting this from 
                            <a href="https://github.com/TheWebSolver/tws-setting-framework" target="_blank">github</a>, you will know what to do!</p>
                    </div>
                </div>
                <div class="hz_flx hz_flx_content grid_item flexibility lite_green">
                    <div class="hz_thumb left">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-tool" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#00695c" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z"></path>
                            <path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6-6a6 6 0 0 1 -8 -8l3.5 3.5"></path>
                        </svg>
                    </div>
                    <div class="hz_thumb_details right">
                        <h4>Made with flexibility in mind</h4>
                        <p class="hz_color_off hz_small">Flexibility on extending using WordPress <b><em>Hooks & Filters</b></em>.</p>
                    </div>
                </div>
                <div class="hz_flx hz_flx_content grid_item templates lite_yellow">
                    <div class="hz_thumb left">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-browser" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#d84315" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z"></path>
                            <rect x="4" y="4" width="16" height="16" rx="1"></rect>
                            <line x1="4" y1="8" x2="20" y2="8"></line>
                            <line x1="8" y1="4" x2="8" y2="8"></line>
                        </svg>
                    </div>
                    <div class="hz_thumb_details right">
                        <h4>Working Templates included</h4>
                        <p class="hz_color_off hz_small">Modify this template and make it your own.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>