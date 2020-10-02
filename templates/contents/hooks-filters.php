<?php
/**
 * Template file to display content on hooks & filters tab
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
<div id="hzfex_hooks_filters">
    <div class="hooks_head">
        <h2>Modify/Extend plugin with WordPress Hooks and Filters.</h2>
        <p>The main plugin files have various action and filter hooks that you can use to modify it's output.</p>
        <p>Check different files that use hooks and filters to know more.</p>
    </div>
    <div class="hooks_content callback_content_wrapper">
        <hr>
        <div class="component_class">
            <h4>Available in file: <code><pre>setting/setting-component.php</pre></code></h4>
            <ul>
                <li class="filter">
                    <p><b>Filter Hook:</b>Force change all submenu page user capability at once</p>
                    <small><em>This can be useful for forcing all submenu pages accessible to users that have a particular capability instead of changing `cap` value from each child-class.</em></small>
                    <p>Paramter passed: <b>$cap -> user capability</b></p>
                    <code>hzfex_set_setting_page_capability</code>
                </li>
                <li class="filter">
                    <p><b>Filter Hook:</b>Force change menu slug for all child-class submenu pages at once</p>
                    <small><em>Useful if want to get all child-class to have same main menu even though it had different menu set.</em></small>
                    <p>Paramter passed: <b>$menu -> main menu slug</b></p>
                    <code>hzfex_set_setting_main_menu_slug</code>
                </li>
                <li class="action">
                    <p><b>Action Hook:</b>After successful hooking as submenu page</p>
                    <small><em>This hook is present just after <code>admin_menu</code> hook. Use it as you need.</em></small>
                    <p>Paramter passed: <b>$class -> child-class object -|- $page -> child-class submenu page array args</b></p>
                    <code>hzfex_setting_framework_loaded</code>
                </li>
                <li class="action">
                    <p><b>Action Hook:</b>After successful loading of each submenu page</p>
                    <small><em>This hook is present just after <code>load-{submenu_page}</code> hook. Accessible to only child-class pages and not available globally.</em></small>
                    <br><code>hzfex_setting_framework_page_loaded</code>
                </li>
                <li class="filter">
                    <p><b>Filter Hook:</b>Display section tabs if page has a single section?</p>
                    <small><em>This hook controls whether or not to show section tabs if page has only one section. <br>Hint: This page has four section tabs.</em></small>
                    <p>Paramter passed: <b>$show -> boolean: default is false</b></p>
                    <code>hzfex_show_setting_sections_nav_if_single</code>
                </li>
                <li class="filter">
                    <p><b>Filter Hook:</b>Modify setting page navigation links</p>
                    <small><em>This hook can let you modify page navigation links on left. (maybe use it to add other admin page link, or even external links).</em></small>
                    <p>Paramter passed: <b>$nav -> array of navigation links data</b></p>
                    <code>hzfex_set_setting_page_nav_links</code>
                </li>
                <li class="filter">
                    <p><b>Filter Hook:</b>Modify setting page head image</p>
                    <small><em>This hook can let you modify header image (currently set to <b>big white gear image</b> on green background).</em></small>
                    <p>Paramter passed: <b>$head_image -> URL link to head image</b></p>
                    <code>hzfex_set_setting_page_head_image</code>
                </li>
                <li class="filter">
                    <p><b>Filter Hook:</b>Modify setting zpage head title</p>
                    <small><em>This hook can let you modify header title (currently set to <b>TWS Setting Framework</b> below big white gear image).</em></small>
                    <p>Paramter passed: <b>$head_title -> title that will be displayed in <code><?= htmlspecialchars( '<h1>' ); ?></code> tag</b></p>
                    <code>hzfex_set_setting_head_title</code>
                </li>
                <li class="action">
                    <p><b>Action Hook:</b>Add contents before page head</p>
                    <small><em>This hook is present above the setting page head. Use it as you need.</em></small>
                    <br><code>hzfex_before_setting_page_head</code>
                </li>
                <li class="action">
                    <p><b>Action Hook:</b>Add contents after page head</p>
                    <small><em>This hook is present below the setting page head. Use it as you need.</em></small>
                    <br><code>hzfex_after_setting_page_head</code>
                </li>
                <li class="action">
                    <p><b>Action Hook:</b>Add contents before page navigation</p>
                    <small><em>This hook is present above the setting page navigation. Use it as you need.</em></small>
                    <br><code>hzfex_before_setting_page_nav</code>
                </li>
                <li class="action">
                    <p><b>Action Hook:</b>Add contents after page navigation</p>
                    <small><em>This hook is present below the setting page navigation. Use it as you need.</em></small>
                    <br><code>hzfex_after_setting_page_nav</code>
                </li>
                <li class="filter">
                    <p><b>Filter Hook:</b>Extend each setting section fields <small><em>(not the recommended way)</em></small></p>
                    <small><em>Highly recommended to add all fields from child-class <b>protected function sections()</b>. Use this filter only to add new fields to already an existing section added by others, just in case.</em></small>
                    <p>Paramter passed: <b>$section_fields -> array of section fields that belong to particular $section_id</b></p>
                    <code>hzfex_setting_<b>{$section_id}</b>_fields</code>
                </li>
            </ul>
        </div>
        <hr>
        <div class="api_class">
            <h4>Available in file: <code><pre>setting/setting-api.php</pre></code></h4>
            <ul>
                <li class="action">
                    <p><b>Action Hook:</b>Content before form fields</p>
                    <small><em>This hook is present below section callback and above the section title. Use it as you need.</em></small>
                    <p>Paramter passed: <b>$section -> array of $section data</b></p>
                    <code>hzfex_before_<b>{$section['id']}</b>_fields</code>
                </li>
                <li class="action">
                    <p><b>Action Hook:</b>Content after form fields</p>
                    <small><em>This hook is present below the section form fields and above the submit button. Use it as you need.</em></small>
                    <p>Paramter passed: <b>$section -> array of $section data</b></p>
                    <code>hzfex_after_<b>{$section['id']}</b>_fields</code>
                </li>
            </ul>
        </div>
    </div>
</div>