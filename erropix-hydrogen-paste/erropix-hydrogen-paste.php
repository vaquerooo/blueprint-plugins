<?php
/**
 * Plugin Name: Hydrogen Paste
 * Plugin URI: https://www.cleanplugins.com/products/hydrogen-pack/
 * Description: This plugin allows you to paste any elements copied using Hydrogen Pack
 * Version: 1.3
 * Requires PHP: 7.0
 * Requires at least: 5.0
 * Author: Clean Plugins
 * Author URI: https://www.cleanplugins.com/
 **/

// Do not load directly
if (!defined("ABSPATH")) {
    header("HTTP/1.0 403 Forbidden");
    exit;
}

// Do not load if the full plugin is active
if (defined("EPXHYDRO_VER")) {
    return;
}

// Add directive
add_action("wp_footer", function () {
    if (defined("SHOW_CT_BUILDER")) {
        echo "<div hydrogen-paste></div>";
    }
});

// add the paste script to Oxygen iframe
add_action("oxygen_enqueue_iframe_scripts", function () {
    $url = plugin_dir_url(__FILE__);
    wp_enqueue_script("hydrogen-paste", "{$url}js/paste.js", [], "1.3", true);
    
    wp_localize_script("hydrogen-paste", "HP_Options", [
        "OxyKeydownConflict" => version_compare(CT_VERSION, "3.9.9", ">="),
    ]);
});

// Add plugin's action links
add_filter("plugin_action_links", function ($actions, $file) {
    $basename = plugin_basename(__FILE__);
    if ($basename == $file) {
        $plugin_actions = [
            "upgrade" => "<a href='https://www.cleanplugins.com/products/hydrogen-pack/' target='_blank'>Upgrade</a>",
        ];
        $actions = array_merge($plugin_actions, $actions);
    }
    return $actions;
}, 10, 2);
