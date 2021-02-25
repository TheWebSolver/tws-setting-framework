<?php
/**
 * Template file to display content on recommendations tab
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
namespace TheWebSolver\Core\Setting;

// exit if file is accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

$anchor = '
    "commentAnchors.tags.list": [

        {
            "tag": "ANCHOR",
            "iconColor": "default",
            "highlightColor": "#A8C023",
            "scope": "workspace"
        },
        {
            "tag": "TODO",
            "iconColor": "#ff0303",
            "highlightColor": "#ff0303",
            "scope": "workspace"
        },
        {
            "tag": "FIXME",
            "iconColor": "#952694",
            "highlightColor": "#952694",
            "scope": "workspace"
        },
        {
            "tag": "STUB",
            "iconColor": "purple",
            "highlightColor": "#BA68C8",
            "scope": "file"
        },
        {
            "tag": "NOTE",
            "iconColor": "#FFB300",
            "highlightColor": "#FFB300",
            "scope": "workspace"
        },
        {
            "tag": "DEBUG",
            "iconColor": "#FFB300",
            "highlightColor": "#FFB300",
            "scope": "workspace"
        },
        {
            "tag": "REVIEW",
            "iconColor": "#237625",
            "highlightColor": "#237625",
            "scope": "workspace"
        },
        {
            "tag": "SECTION",
            "iconColor": "blurple",
            "highlightColor": "#896afc",
            "scope": "workspace",
            "isRegion": true
        },
        {
            "tag": "TEMPLATE",
            "iconColor": "#01579b",
            "highlightColor": "#01579b",
            "scope": "workspace",
        },
        {
            "tag": "WPHOOK",
            "iconColor": "#14dcc9",
            "highlightColor": "#14dcc9",
            "scope": "workspace",
        }
    ]
';

//Declare the custom function for formatting
function pretty_print($json_data) {

    //Initialize variable for adding space
    $space = 0;
    $flag = false;

    //Using <pre> tag to format alignment and font
    echo '<pre style="line-height:1em;display:block;padding:10px;margin: 10px auto;border-radius:5px;background:rgba(213, 0, 0, 0.1);">';

    //loop for iterating the full json data
    for($counter=0; $counter<strlen($json_data); $counter++)
    {

    //Checking ending second and third brackets
        if ( $json_data[$counter] == '}' || $json_data[$counter] == ']' )
        {
        $space--;
        echo "\n";
        echo str_repeat(' ', ($space*2));
        }


        //Checking for double quote(“) and comma (,)
        if ( $json_data[$counter] == '"' && ($json_data[$counter-1] == ',' ||
        $json_data[$counter-2] == ',') )
        {
        echo "\n";
        echo str_repeat(' ', ($space*2));
        }
        if ( $json_data[$counter] == '"' && !$flag )
        {
        if ( $json_data[$counter-1] == ':' || $json_data[$counter-2] == ':' )

        //Add formatting for question and answer
        echo '<span style="color:var(--primary);font-weight:bold">';
        else

        //Add formatting for answer options
        echo '<span style="color:var(--secondary);">';
        }
        echo $json_data[$counter];
        //Checking conditions for adding closing span tag
        if ( $json_data[$counter] == '"' && $flag )
        echo '</span>';
        if ( $json_data[$counter] == '"' )
        $flag = !$flag;

        //Checking starting second and third brackets
        if ( $json_data[$counter] == '{' || $json_data[$counter] == '[' )
        {
        $space++;
        echo "\n";
        echo str_repeat(' ', ($space*2));
        }
    }
echo '</pre>';
}

?>

<div id="hzfex_recommendations">
    <div class="recommend_head">
        <h2>Recommended setup to use this plugin.</h2>
        <p>This plugin can be best coded using the following recommended setup.</p>
    </div>
    <div class="recommend_content callback_content_wrapper">
        <hr>
        <div class="setup_environment">
            <h4>Setup environment for coding: <code><pre>Visual Studio Code</pre></code></h4>
            <ul>
                <li class="filter">
                    <p><b>Extension: WordPress Snippets -></b> Snippet for every WordPress function, class and constant</p>
                    <small><em>Use the extension <a href="https://github.com/jason-pomerleau/vscode-wordpress-toolbox" target="_blank">WordPress Snippets</a> to view every WordPress function, class and constant. Easy auto-completion with type hints and tab stops in all the right places.</em></small>
                </li>
                <li class="filter">
                    <p><b>Extension: PHP Intelephense -></b>PHP code intelligence for Visual Studio Code</p>
                    <small><em>Use the extension <a href="https://github.com/bmewburn/vscode-intelephense" target="_blank">PHP Intelephense</a> as Intelephense is a high performance PHP language server packed full of essential features for productive PHP development.</em></small>
                </li>
                <li class="filter">
                    <p><b>Font: Fira Code -></b>Free monospaced font with programming ligatures</p>
                    <small><em>Use the font <a href="https://github.com/tonsky/FiraCode" target="_blank">Fira Code</a> as a better coding font that supports Programming Ligatures. Download and install to your OS and then use as VS Code font family.</em></small>
                </li>
                <li class="filter">
                    <p><b>Extension: Comment Anchors -></b> View all available anchor tags in a file/workspace</p>
                    <small><em>Use the extension <a href="https://github.com/ExodiusStudios/vscode-comment-anchors" target="_blank">Comment Anchors</a> to view TODO | NOTE | WPHOOK | DEBUG, etc. anchors so that you can find and navigate to different sections of files in this plugin easily. Follow the steps of this extension's readme file on the github page on how to add new anchor tags.</em><br>Then, add following lines on Visual Studio code settings (in JSON Format):</small>
                    <?= pretty_print( $anchor ); ?>
                </li>
            </ul>
        </div>
        <hr>
        <div class="wordpress_environment">
            <h4>Setup environment for WordPresss: <code><pre>Plugins</pre></code></h4>
            <ul>
                <li class="action">
                    <p><b>Plugin: Show Hooks -></b> View all action/filter hooks availble on WordPress</p>
                    <small><em>Use the plugin <a href="https://wordpress.org/plugins/show-hooks" target="_blank">Show Hooks</a> to see all existing hooks as well as hooks added by this plugin.</em></small>
                </li>
            </ul>
        </div>
    </div>
</div>
