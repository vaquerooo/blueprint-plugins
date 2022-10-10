<?php

/**
 * Plugin Name: Oxy Extended
 * Plugin URI: https://oxyextended.com
 * Description: Extend Oxygen Page Builder with 15+ Creative Elements and exciting extensions.
 * Version: 1.1.0
 * Author: IdeaBox Creations
 * Author URI: https://ideabox.io
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: oxy-extended
 * Domain Path: languages
 */

/**
 * Copyright (c) 2021 IdeaBox. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 */
update_option( 'oe_license_status', 'valid' );
update_option( 'oe_license_key', '**********' );
// * Prevent direct access to the plugin
if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Sorry, you are not allowed to access this page directly.', 'oxy-extended' ) );
}

// * Define constants
define( 'OXY_EXTENDED_VER', '1.1.0' );
define( 'OXY_EXTENDED_DIR', plugin_dir_path( __FILE__ ) );
define( 'OXY_EXTENDED_BASE', plugin_basename( __FILE__ ) );
define( 'OXY_EXTENDED_URL', plugins_url( '/', __FILE__ ) );

// * Activate plugin
register_activation_hook( __FILE__, 'oxyextend_activate' );

require_once OXY_EXTENDED_DIR . 'classes/class-oe-admin-settings.php';
require_once OXY_EXTENDED_DIR . 'classes/class-oe-helper.php';
require_once OXY_EXTENDED_DIR . 'classes/class-oe-maintenance-mode.php';
require_once OXY_EXTENDED_DIR . 'includes/updater/update-config.php';
add_action( 'admin_init', 'oxyextend_activate' );
add_action( 'switch_theme', 'oxyextend_activate' );
add_action( 'plugins_loaded', 'oxyextend_init' );

/**
 * Activate plugin
 */
function oxyextend_activate() {
	if ( ! class_exists( 'OxyEl' ) ) {
		// * Deactivate ourself
		deactivate_plugins( __FILE__ );
		add_action( 'admin_notices', 'oxyextend_admin_notice_message' );
		add_action( 'network_admin_notices', 'oxyextend_admin_notice_message' );
	}
}

/**
 * Shows an admin notice if you're not using the Oxygen Builder.
 */
function oxyextend_admin_notice_message() {
	if ( ! is_admin() ) {
		return;
	} elseif ( ! is_user_logged_in() ) {
		return;
	} elseif ( ! current_user_can( 'update_core' ) ) {
		return;
	}

	$error = __( 'Sorry, you can\'t use the Oxy Extended addon unless the Oxygen Builder Plugin is active. The plugin has been deactivated.', 'oxy-extended' );

	echo '<div class="error"><p>' . $error . '</p></div>';

	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}
}


/**
 * Loads text domain.
 */
function oxyextend_init() {
	if ( ! class_exists( 'OxyEl' ) ) {
		return;
	}

	// * Load textdomain for translation
	load_plugin_textdomain( 'oxy-extended', false, basename( OXY_EXTENDED_DIR ) . '/languages' );

	// * include files
	require_once 'classes/class-oxy-extended.php';
	require_once 'includes/helpers.php';
}
