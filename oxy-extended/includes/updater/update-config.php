<?php
use OxyExtended\Classes\OE_Admin_Settings;

// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define( 'OE_SL_URL', 'https://oxyextended.com' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

// the name of your product. This should match the download name in EDD exactly
define( 'OE_ITEM_NAME', 'Oxy Extended' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

// the name of the settings page for the license input to be displayed
define( 'OE_LICENSE_PAGE', 'oe-settings' );

if ( ! class_exists( 'OE_SL_Plugin_Updater' ) ) {
	// load our custom updater
	include dirname( __FILE__ ) . '/class-oe-plugin-updater.php';
}

function oe_get_license_key() {
	return defined( 'OE_LICENSE_KEY' ) ? OE_LICENSE_KEY : trim( get_option( 'oe_license_key' ) );
}

function oe_plugin_updater() {

	// retrieve our license key from the DB
	$license_key = oe_get_license_key();

	// setup the updater
	$updater = new OE_SL_Plugin_Updater( OE_SL_URL, OXY_EXTENDED_DIR . '/oxy-extended.php',
		array(
			'version'   => OXY_EXTENDED_VER,  // current version number
			'license'   => $license_key,            // license key
			'item_name' => OE_ITEM_NAME,     // name of this plugin
			'author'    => 'IdeaBox Creations',     // author of this plugin
			'beta'      => false,
		)
	);

}
add_action( 'admin_init', 'oe_plugin_updater', 0 );

function oe_sanitize_license( $new ) {
	$old = oe_get_license_key();
	if ( $old && $old != $new ) {
		delete_option( 'oe_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}

function oe_do_license_action( $action ) {
	if ( ! in_array( $action, array( 'activate_license', 'deactivate_license' ) ) ) {
		return;
	}

	// retrieve the license.
	$license = oe_get_license_key();

	// data to send in our API request
	$api_params = array(
		'edd_action' => $action,
		'license'    => $license,
		'item_name'  => urlencode( OE_ITEM_NAME ), // the name of our product in EDD
		'url'        => network_home_url(),
	);

	// Call the custom API.
	$response = wp_remote_post(
		OE_SL_URL,
		array(
			'timeout' => 15,
			'sslverify' => false,
			'body' => $api_params,
		)
	);

	return $response;
}

function oe_activate_license() {
	// listen for our activate button to be clicked
	if ( ! isset( $_POST['oe_license_activate'] ) ) {
		return;
	}

	// run a quick security check
	if ( ! check_admin_referer( 'oe_license_activate_nonce', 'oe_license_activate_nonce' ) ) {
		return; // get out if we didn't click the Activate button
	}

	// Call the custom API.
	$response = oe_do_license_action( 'activate_license' );

	// make sure the response came back okay
	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

		if ( is_wp_error( $response ) ) {
			$message = $response->get_error_message();
		} else {
			$code = wp_remote_retrieve_response_code( $response );
			$response_msg = wp_remote_retrieve_response_message( $response );
			if ( 403 === $code ) {
				$message = __( 'An error occurred while activating license. The request is getting blocked by a security plugin or security settings on server.', 'oxy-extended' );
			} else {
				$message = sprintf( __( 'An error occurred, please try again. Status: %1$s %2$s', 'oxy-extended' ), $code, $response_msg );
			}
		}
	} else {

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( false === $license_data->success ) {

			$message = oe_get_license_error( $license_data );

		}
	}

	// Check if anything passed on a message constituting a failure
	if ( ! empty( $message ) ) {
		$base_url = OE_Admin_Settings::get_form_action();
		$redirect = add_query_arg( array(
			'sl_activation' => 'false',
			'message' => urlencode( $message ),
		), $base_url );

		wp_redirect( $redirect );
		exit();
	}

	// $license_data->license will be either "valid" or "invalid"

	update_option( 'oe_license_status', $license_data->license );
	wp_redirect( OE_Admin_Settings::get_form_action() );
	exit();
}
add_action( 'admin_init', 'oe_activate_license' );

function oe_deactivate_license() {
	// listen for our activate button to be clicked
	if ( ! isset( $_POST['oe_license_deactivate'] ) ) {
		return;
	}

	// run a quick security check
	if ( ! check_admin_referer( 'oe_license_deactivate_nonce', 'oe_license_deactivate_nonce' ) ) {
		return; // get out if we didn't click the Deactivate button
	}

	// Call the custom API.
	$response = oe_do_license_action( 'deactivate_license' );

	// make sure the response came back okay
	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

		if ( is_wp_error( $response ) ) {
			$message = $response->get_error_message();
		} else {
			$code = wp_remote_retrieve_response_code( $response );
			$response_msg = wp_remote_retrieve_response_message( $response );
			if ( 403 === $code ) {
				$message = __( 'An error occurred while deactivating license. The request is getting blocked by a security plugin or security settings on server.', 'oxy-extended' );
			} else {
				$message = sprintf( __( 'An error occurred, please try again. Status: %1$s %2$s', 'oxy-extended' ), $code, $response_msg );
			}
		}

		$base_url = OE_Admin_Settings::get_form_action();
		$redirect = add_query_arg( array(
			'sl_activation' => 'false',
			'message' => urlencode( $message ),
		), $base_url );

		wp_redirect( $redirect );
		exit();
	}

	// decode the license data
	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	// $license_data->license will be either "deactivated" or "failed"
	if ( $license_data->license == 'deactivated' ) {
		delete_option( 'oe_license_status' );
	}

	wp_redirect( OE_Admin_Settings::get_form_action( '&sl_status=' . $license_data->license ) );
	exit();
}
add_action( 'admin_init', 'oe_deactivate_license' );


/************************************
* this illustrates how to check if
* a license key is still valid
* the updater does this for you,
* so this is only needed if you
* want to do something custom
*************************************/

function oe_check_license() {

	global $wp_version;

	$license = oe_get_license_key();

	$api_params = array(
		'edd_action' => 'check_license',
		'license' => $license,
		'item_name' => urlencode( OE_ITEM_NAME ),
		'url'       => home_url(),
	);

	// Call the custom API.
	$response = wp_remote_post( OE_SL_URL, array(
		'timeout' => 15,
		'sslverify' => false,
		'body' => $api_params,
	) );

	if ( is_wp_error( $response ) ) {
		return false;
	}

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	// if ( $license_data->license !== 'valid' ) {
	// 	// this license is no longer valid
	// 	// delete license status.
	// 	if ( in_array( $license_data->license, array( 'deactivated', 'inactive', 'site_inactive' ) ) ) {
	//      delete_option( 'oe_license_status' );
	// 	} else {
	// 		update_option( 'oe_license_status', $license_data->license );
	// 	}
	// }

	return $license_data;
}

/**
* Show update message on plugins page
*/
function oe_in_plugin_update_message( $plugin_data, $response ) {
	$data = oe_check_license();

	if ( 'valid' !== $data->license ) {
		?>
		<style>
		tr[data-plugin="<?php echo OXY_EXTENDED_BASE; ?>"] .update-message {
			padding: 0;
		}
		tr[data-plugin="<?php echo OXY_EXTENDED_BASE; ?>"] .update-message p:first-of-type {
			border-bottom: 1px solid #ffb922;
			padding-bottom: 8px;
			padding-left: 12px;
		}
		tr[data-plugin="<?php echo OXY_EXTENDED_BASE; ?>"] .oe-update-message {
			padding: 5px 15px;
		}
		tr[data-plugin="<?php echo OXY_EXTENDED_BASE; ?>"] .oe-update-message:before {
			display: none !important;
		}
		tr[data-plugin="<?php echo OXY_EXTENDED_BASE; ?>"] .oe-update-message + p:empty{
			display: none;
		}
		tr[data-plugin="<?php echo OXY_EXTENDED_BASE; ?>"] .oe-buy-button {
			text-decoration: none;
			font-weight: bold;
		}
		</style>
		<?php

		$main_msg = sprintf( __( 'Please activate the license to enable automatic updates for this plugin. License status: %s', 'oxy-extended' ), $data->license );

		$message  = '';
		$message .= '<p class="oe-update-message">';
		$message .= __( '<strong>UPDATE UNAVAILABLE!</strong>', 'oxy-extended' );
		$message .= '&nbsp;&nbsp;';
		$message .= $main_msg;
		$message .= ' <a href="' . OE_SL_URL . '" class="oe-buy-button" target="_blank">';
		$message .= __( 'Buy Now', 'oxy-extended' );
		$message .= ' &raquo;</a>';
		$message .= '</p>';

		echo $message;
	}
}
add_action( 'in_plugin_update_message-' . OXY_EXTENDED_BASE, 'oe_in_plugin_update_message', 1, 2 );

function oe_get_license_error( $license_data ) {
	$message = '';

	switch ( $license_data->error ) {

		case 'expired':
			$message = sprintf(
				__( 'Your license key expired on %s.', 'oxy-extended' ),
				date_i18n( get_site_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
			);
			break;

		case 'revoked':
			$message = __( 'Your license key has been disabled.', 'oxy-extended' );
			break;

		case 'missing':
			$message = __( 'Invalid license.', 'oxy-extended' );
			break;

		case 'invalid':
		case 'site_inactive':
			$message = __( 'Your license is not active for this URL.', 'oxy-extended' );
			break;

		case 'item_name_mismatch':
			$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'oxy-extended' ), OE_ITEM_NAME );
			break;

		case 'no_activations_left':
			$message = __( 'Your license key has reached its activation limit.', 'oxy-extended' );
			break;

		default:
			$message = sprintf( __( 'An error occurred, please try again. Status: %s', 'oxy-extended' ), $license_data->error );
			break;
	}

	return $message;
}

/**
 * This is a means of catching errors from the activation method above and displaying it to the customer
 */
function oe_admin_notices() {
	$start_el = '<div class="notice error" style="background: #fbfbfb; border-top: 1px solid #eee; border-right: 1px solid #eee;"><p>';
	$end_el = '</p></div>';

	if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {

		switch ( $_GET['sl_activation'] ) {

			case 'false':
				$message = urldecode( $_GET['message'] );
				echo $start_el;
				echo $message;
				echo $end_el;
				break;

			case 'true':
			default:
				break;
		}
	}

	if ( current_user_can( 'update_plugins' ) ) {

		$license_data = get_transient( 'oxy_extended_license_data' );
		if ( ! $license_data ) {
			$license_data = oe_check_license();
			set_transient( 'oxy_extended_license_data', $license_data, 12 * HOUR_IN_SECONDS );
		}

		if ( is_object( $license_data ) && 'expired' === $license_data->license ) {
			$settings = OE_Admin_Settings::get_settings();
			$admin_label = OE_Admin_Settings::get_admin_label();
			if ( 'off' === $settings['hide_wl_settings'] && 'off' === $settings['hide_plugin'] ) {
				echo $start_el;
				echo $admin_label . ': ' . oe_get_license_error( $license_data );
				echo $end_el;
			}
		}
	}
}
add_action( 'admin_notices', 'oe_admin_notices', 10 );


