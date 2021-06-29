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
    <img src="images/logo.png" alt="Logo" width="80" height="80">
  </a>

  <h3 align="center">TWS Setting Framework</h3>
  <p align="center">
    CREATE ADMIN PAGES WITH SECTIONS -|- CONTENTS/FIELDS WITHIN EACH SECTION
    <br/>
    This plugin is a framework meant for creating settting pages and setting fields in WordPress admin dashboard.
  </p>
</p>

## Getting Started

### Recommended Setup

This plugin is developed using:

* [Code Editor - **Visual Studio Code (VS Code)**](https://code.visualstudio.com/download) - Loved by most developers for coding.
* [VS Code extension - **WordPress Snippets**](https://github.com/jason-pomerleau/vscode-wordpress-toolbox) - Snippets for every WordPress function, class and constant.
* [VS Code extension - **PHP Intelephense**](https://github.com/bmewburn/vscode-intelephense) - Essential features for productive PHP development.
* [VS Code extension - **Comment Anchors**](https://github.com/ExodiusStudios/vscode-comment-anchors) - Find anchors for WordPress **Action** & **Filter** hooks (and other anchors too) added in this framework's files.
* [VS Code Font - **Fira Code**](https://github.com/tonsky/FiraCode) - Free monospaced font with programming ligatures. Set it from VS Code font family.
* [WordPress Plugin - **Show Hooks**](https://wordpress.org/plugins/show-hooks/) - See visual representation of WordPess action and filter hooks.

### Installation (via Composer)

Require framework file in `composer.json` file:
```
"require": {
		"thewebsolver/tws-setting-framework": "^2.0"
	}
```
Then from terminal, run:
```
$ composer install
```

Alternatively, install directly from CLI:
```
composer require thewebsolver/tws-setting-framework
```

## Usage
- This framework uses PHP Namespace:

	```php
	namespace TheWebSolver\Core\Setting;
	```

## Examples
- For exmaple codes, check [EXAMPLE](https://github.com/TheWebSolver/tws-setting-framework/blob/master/EXAMPLE.md) file.

## Screenshots

### General Fields
Text, number, textarea, radio, checkbox, select fields

![simple]

### Advanced Fields
Multi selection fields, wysiwyg, password, color fields

![advanced]

### Stylized Fields
Fields that are applied custom styling, Select2 library for select fields.

![stylized]

## License
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

## Acknowledegement
>This project uses base for Settings_API from [wordpress-settings-api-class](https://github.com/tareq1988/wordpress-settings-api-class).


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
[simple]: images/simple-fields-v2.png
[advanced]: images/advanced-fields-v2.png
[stylized]: images/stylized-fields-v2.png
[screenshot-4]: images/advanced-fields-v2.png
[screenshot-5]: images/stylized-fields-v2.png
[screenshot-6]: images/debug.png
