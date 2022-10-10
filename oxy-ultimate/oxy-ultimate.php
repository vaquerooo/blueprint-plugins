<?php

/**
 * Addon plugin for Oxygen Builder.
 * 
 * @wordpress-plugin
 * Plugin Name: 	OxyUltimate
 * Plugin URI: 		https://www.oxyultimate.com
 * Description: 	A set of custom, creative, unique tools for Oxygen Builder to speed up your workflow.
 * Author: 			Paul Chinmoy
 * Author URI: 		https://www.paulchinmoy.com
 *
 * Version: 		1.5.5
 *
 * License: 		GPLv2 or later
 * License URI: 	http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain: 	oxy-ultimate
 * Domain Path: 	languages  
 */

/**
 * Copyright (c) 2020 Paul Chinmoy. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 */

//* Prevent direct access to the plugin
if ( !defined( 'ABSPATH' ) ) {
	wp_die( __( "Sorry, you are not allowed to access this page directly.", 'oxy-ultimate' ) );
}

require_once 'includes/class-plugin-loader.php';