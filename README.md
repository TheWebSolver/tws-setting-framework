<!--
<!-- ***
https://www.markdownguide.org/basic-syntax/#reference-style-links
 -->
<p align="center">

<!-- [![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url] -->
[![GPL License][license-shield]][license-url]
[![LinkedIn][linkedin-shield]][linkedin-url]

</p>
<!-- ***
<!-- -->


<!-- PROJECT LOGO -->
<br />
<p align="center">
  <a href="https://github.com/TheWebSolver/tws-setting-framework">
    <img src="images/official_logo.png" alt="Logo" width="80" height="80">
  </a>

  <h3 align="center">TWS Setting Framework</h3>
  <p align="center">
    CREATE ADMIN PAGES WITH SECTIONS -|- CONTENTS/FIELDS WITHIN EACH SECTION
    <br/>
    This plugin is a framework meant for creating settting pages and setting fields in WordPress admin dashboard.
  </p>
</p>

<!-- TABLE OF CONTENTS -->
## Table of Contents

* [About the Project](#about-the-project)
    * [Preface](#preface)
* Getting Started
  * [Prerequisites](#prerequisites)
  * [Installation](#installation)
* [Usage](#usage)
    * [Example Code](#example-code)
    * [Debug Mode](#debug-mode)
* [License](#license)
* [Contact](#contact)
* [Acknowledgement](#acknowledegement)



<!-- ABOUT THE PROJECT -->
## About The Project
<small>[Top↑](#table-of-contents)</small>

#### Screenshot 1
![Screenshot 1][screenshot-1]

#### Screenshot 2
![Screenshot 2][screenshot-2]

### Preface 
<small>[Top↑](#table-of-contents)</small>

This framework is a ready to use plugin _<small>(if not building your own plugin or theme)_</small> or to include inside your own plugin/theme for anyone who needs to create: <br/>
- WordPress Admin pages (**_Welcome | Fields Demo_** navigations at left side in screenshot 1 above)
- Different Sections within a page (tabs **_Getting Started | Hooks & Filters | Recommended Setup | Ready to Go?_** inside **_Welcome_** page in screenshot 1 above)
- Contents within each section (tab content of **_Getting Started_** inside **_Welcome_** page in screenshot 1 above)
- Contents and/or setting fields within each section (tab content and fields of **_Ready to Go?_** inside **_Welcome_** page in screenshot 2 above)


<!-- GETTING STARTED -->
## Getting Started

### Prerequisites
<small>[Top↑](#table-of-contents)</small>

#### Recommended Setup (Built using)

This plugin is developed using:

* [Code Editor - **Visual Studio Code (VS Code)**](https://code.visualstudio.com/download) - Loved by most developers for coding.
* [VS Code extension - **WordPress Snippets**](https://github.com/jason-pomerleau/vscode-wordpress-toolbox) - Snippets for every WordPress function, class and constant.
* [VS Code extension - **PHP Intelephense**](https://github.com/bmewburn/vscode-intelephense) - Essential features for productive PHP development.
* [VS Code extension - **Comment Anchors**](https://github.com/ExodiusStudios/vscode-comment-anchors) - Find anchors for WordPress **Action** & **Filter** hooks (and other anchors too) added in this framework's files.
* [VS Code Font - **Fira Code**](https://github.com/tonsky/FiraCode) - Free monospaced font with programming ligatures. Set it from VS Code font family.
* [WordPress Plugin - **Show Hooks**](https://wordpress.org/plugins/show-hooks/) - See visual representation of WordPess action and filter hooks.

### Installation
<small>[Top↑](#table-of-contents)</small>

#### For using as plugin
>change [with-fields.php](https://github.com/TheWebSolver/tws-setting-framework/tree/master/templates/with-fields.php) and [without-fields.php](https://github.com/TheWebSolver/tws-setting-framework/tree/master/templates/without-fields.php) to your requirement.

- Download or clone this repo into plugins directory using:
    ```sh
    git clone https://github.com/TheWebSolver/tws-setting-framework.git
    ```
#### For using inside your plugin/theme
- Download or clone this repo into appropriate directory inside your plugin/theme using:
    ```sh
    git clone https://github.com/TheWebSolver/tws-setting-framework.git
    ```
- Include the framework file **_tws-setting-framework.php_** from your plugin/theme file
    ```sh
    require_once 'path_to_framework_directory/tws-setting-framework.php';
    ```

## Usage
<small>[Top↑](#table-of-contents)</small>

- This framework uses PHP Namespace:
    ```php
    namespace TheWebSolver\Plugin\Core\Framework;
    ```

- This framework creates main menu with title as **"The Web Solver"**. In file [init.php](https://github.com/TheWebSolver/tws-setting-framework/tree/master/templates/init.php), see function:
    ```php
    function tws_setting_framework_main_menu();
    ```
    >All submenus are created inside it by default if child-class args doesn't define the main menu slug.

- Child-classes can be found inside [templates](https://github.com/TheWebSolver/tws-setting-framework/tree/master/templates) directory. Files that create child-classes are [with-fields.php](https://github.com/TheWebSolver/tws-setting-framework/tree/master/templates/with-fields.php) and [without-fields.php](https://github.com/TheWebSolver/tws-setting-framework/tree/master/templates/without-fields.php).
    >These are example codes and can be used as boilerplate for creating your own child-class.

#### NOTE:

>Regarding documentation, once activated, navigate to **_Welcome_** submenu page which has four sections as tabs, first three of them are documentation tabs. Contents in these three tabs are added from files that are inside directory _[templates/contents](https://github.com/TheWebSolver/tws-setting-framework/tree/master/templates/contents)_.

>_(See screenshot 1 above for reference on how content gets displayed)_

>There is thorough commenting on all of the core files which can also be considered as in-file documentation.

### Example Code
<small>[Top↑](#table-of-contents)</small>

- File [without-fields.php](https://github.com/TheWebSolver/tws-setting-framework/tree/master/templates/without-fields.php) contains child-class `Without_Fields` that extends the parent class `Setting_Component` The parent class is in file [setting-component.php](https://github.com/TheWebSolver/tws-setting-framework/tree/master/setting/setting-component.php). It has basically everything this framework can do such as:
    - creating submenu page _(see method `__construct`)_
    - creating sections as tabs inside that submenu page _(see method `sections`)_
    - creating contents and/or fields on each section (as tab) of that submenu page _(see method `sections`)_
    >For visual representation on how it looks, see screenshot 1 above.

    >For code structure, see PHP code below.

    ```php
    <?php
    /**
    * Submenu page with menu title as "Welcome" under main menu "The Web Solver"
    */

    namespace TheWebSolver\Plugin\Core\Framework;

    // Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) exit;

    final class Without_Fields extends Setting_Component {

        /** Constructor */
        public function __construct() {
            parent::__construct( 
                [
                    __CLASS__ => [
                        'menu_slug'     => '', // uses default if not set
                        'page_title'    => __( 'Welcome', 'tws-core' ),
                        'menu_title'    => __( 'Welcome', 'tws-core' ),
                        'cap'           => 'read', // wordpress user capability to view page and edit/update page section fields
                        'slug'          =>'tws_welcome_page', // submenu page slug
                        'icon'          => HZFEX_Setting_Framework_Url . 'assets/graphics/files-icon.svg', // icon that displays on before page navigation title (files icon before page title Welcome in screenshot 1)
                    ]
                ]
            );
        }

        protected function sections() {
            $sections = [
                'welcome' => [
                    'title'     => __( 'Getting Started', 'tws-core' ),
                    'tab_title' => __( 'Getting Started', 'tws-core' ),
                    'desc'      => 'Some description',
                    'callback'  => [ $this, 'welcome_callback' ], // callback can be used this way
                ],
                'hooks'   => [
                    'title'     => __( 'Hooks/Filters', 'tws-core' ),
                    'tab_title' => __( 'Hooks & Filters', 'tws-core' ),
                    'callback'  => function() { if( file_exists( HZFEX_Setting_Framework_Path. 'templates/contents/hooks-filters.php' ) ) include_once HZFEX_Setting_Framework_Path. 'templates/contents/hooks-filters.php'; }, // callback can be used this way also
                ],
                'recommendation' => [
                    'title'      => __( 'Recommendation', 'tws-core' ),
                    'tab_title'  => __( 'Recommended Setup', 'tws-core' ),
                    'callback'   => function() { if( file_exists( HZFEX_Setting_Framework_Path . 'templates/contents/recommendations.php' ) ) include_once HZFEX_Setting_Framework_Path . 'templates/contents/recommendations.php'; },
                ],
                'tws_mixed_section' => [
                    'title'         => __( 'This title is only visible when fields are set.', 'tws-core' ), // only shows when "fields" are set.
                    'tab_title'     => __( 'Ready to Go?', 'tws-core' ), 
                    'desc'          => sprintf( '<div>%1$s</div><div><small><em>%2$s <code>templates/with-fields.php</code></em></small></div>',
                    __( 'This description is only visible when fields are set.', 'tws-core' ),
                    __( 'Enabling the switch below (actually it is a checkbox field type with style customization) will instantiate another child class on file', 'tws-core' ),
                ), // only shows when "fields" are set.
                    'callback'      => function() { echo 'This is just a callback like other three section tabs in this page.'; },
                    'fields'        => [
                        'tws_enable_fields' => [
                            'label'     => __( 'Enable another child-class?', 'tws-core' ),
                            'desc'      => __( 'You should definitely enable this to test other types of input fields.', 'tws-core' ),
                            'type'      => 'checkbox',
                            'class'     => 'hz_switcher_control',
                            'default'   => 'off'
                        ],
                    ],
                ],
            ];

            return $sections;
        }

        /**
         * Sets content for "Getting Started" section in page "Welcome".
         */
        public function welcome_callback() {
            if( file_exists( HZFEX_Setting_Framework_Path. 'templates/contents/welcome.php' ) ) include_once HZFEX_Setting_Framework_Path. 'templates/contents/welcome.php';
        }
    }

    // initialize this submenu page.
    new Without_Fields();
    ```
- File [with-fields.php](https://github.com/TheWebSolver/tws-setting-framework/tree/master/templates/with-fields.php) contains child-class `With_Fields` that extends the parent class `Setting_Component`. It contains all supported setting fields and can be used as boilerplate.

    >Please check the [with-fields.php](https://github.com/TheWebSolver/tws-setting-framework/tree/master/templates/with-fields.php) file to view the code structure.

    >For refresher, the parent class is in file [setting-component.php](https://github.com/TheWebSolver/tws-setting-framework/tree/master/setting/setting-component.php).

- >For visual representation on how section and field gets displayed, see screenshot 3, 4 and 5 below.

#### Screenshot 3 - General Fields
![Screenshot 3][screenshot-3]

#### Screenshot 4 - Multi selection fields, wysiwyg, password, color, etc. fields
![Screenshot 4][screenshot-4]

#### Screenshot 5 - Fields that are applied custom styling, Select2 plugin for select fields.
![Screenshot 5][screenshot-5]

- To get the saved field value, use:

    ```php
    Settings_API::get_option( $field_id, $section_id, $default );
    ```
    >For example - from the child class [Without_Fields↑](#example-code) above, if you want to get value of the field **`tws_enable_fields`**, use:
    ```php
    <?php

    namespace TheWebSolver\Plugin\Core\Framework;

    // use TheWebSolver\Plugin\Core\Framework\Settings_API; // uncomment this if you are not using above namespace.
    
    $enabled = Settings_API::get_option( 'tws_enable_fields', 'tws_mixed_section', 'off' );
    ```

### Debug Mode
<small>[Top↑](#table-of-contents)</small>

- For debugging purpose, you can define debug constant to `true`.
    
    On file [tws-setting-framework.php](https://github.com/TheWebSolver/tws-setting-framework/tree/master/tws-setting-framework.php) at around **_line 32_**, change contstant value from `false` to `true`.  For visual representation, see screenshot 6 below.

    ```php
    define( 'HZFEX_SETTING_FRAMEWORK_DEBUG_MODE', true );
    ```

    >For example, once set to `true`, it will display sections and fields set at child-class [without-fields.php](https://github.com/TheWebSolver/tws-setting-framework/tree/master/templates/without-fields.php) from:
    
    ```php
    protected function sections();
    ```
    #### Screenshot 6 - Debug mode to view codes and saved field values that are added from child-classes
    ![Screenshot 6][screenshot-6]

<!-- LICENSE -->
## License
<small>[Top↑](#table-of-contents)</small>

Distributed under the GNU General Public License v3.0 (or later) License. See **[LICENSE](https://github.com/TheWebSolver/tws-setting-framework/blob/master/LICENSE)** file for more information.



<!-- CONTACT -->
## Contact
<small>[Top↑](#table-of-contents)</small>

```sh
----------------------------------
DEVELOPED-MAINTAINED-SUPPPORTED BY
----------------------------------
███║     ███╗   ████████████████
███║     ███║   ═════════██████╗
███║     ███║        ╔══█████═╝
 ████████████║      ╚═█████
███║═════███║      █████╗
███║     ███║    █████═╝
███║     ███║   ████████████████╗
╚═╝      ╚═╝    ═══════════════╝
 ```

Shesh Ghimire - [@hsehszroc](https://twitter.com/hsehszroc)

Project Link: [https://github.com/TheWebSolver/tws-setting-framework](https://github.com/TheWebSolver/tws-setting-framework)

<!-- ACKNOWLEDGEMENT -->
## Acknowledegement
<small>[Top↑](#table-of-contents)</small>

- >This project uses base for Settings_API from [wordpress-settings-api-class](https://github.com/tareq1988/wordpress-settings-api-class).


<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[contributors-shield]: https://img.shields.io/github/contributors/TheWebSolver/repo.svg?style=flat-square
[contributors-url]: https://github.com/TheWebSolver/repo/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/TheWebSolver/repo.svg?style=flat-square
[forks-url]: https://github.com/TheWebSolver/tws-setting-framework/network/members
[stars-shield]: https://img.shields.io/github/stars/TheWebSolver/repo.svg?style=flat-square
[stars-url]: https://github.com/TheWebSolver/tws-setting-framework/stargazers
[issues-shield]: https://img.shields.io/github/issues/TheWebSolver/repo.svg?style=flat-square
[issues-url]: https://github.com/TheWebSolver/tws-setting-framework/issues
[license-shield]: https://www.gnu.org/graphics/gplv3-or-later-sm.png
[license-url]: https://github.com/TheWebSolver/repo/blob/master/LICENSE.txt
[linkedin-shield]: https://img.shields.io/badge/LinkedIn-blue?style=flat-square&logo=linkedin&color=blue
[linkedin-url]: https://www.linkedin.com/in/sheshgh/
[screenshot-1]: images/getting-started.png
[screenshot-2]: images/ready-to-go.png
[screenshot-3]: images/simple-fields.png
[screenshot-4]: images/advanced-fields.png
[screenshot-5]: images/stylized-fields.png
[screenshot-6]: images/debug.png