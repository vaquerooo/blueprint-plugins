<?php
/**
 * Automatic.css Logger file.
 *
 * @package Automatic_CSS
 */

namespace Automatic_CSS\Helpers;

/**
 * Automatic.css Logger class.
 */
class Logger {

	/**
	 * Undocumented function
	 *
	 * @param boolean $enabled Enable or disable debugging.
	 */
	public function __construct( $enabled = false ) {
		if ( ! defined( 'AUTOMATICCSS_DEBUG_LOG' ) ) {
			define( 'AUTOMATICCSS_DEBUG_LOG', (bool) $enabled );
		}
	}

	/**
	 * Log whatever is being passed as parameter.
	 *
	 * @param mixed $what What to log.
	 * @return void
	 */
	public static function log( $what ) {
		if ( ! defined( 'AUTOMATICCSS_DEBUG_LOG' ) || true !== AUTOMATICCSS_DEBUG_LOG ) {
			return;
		}
		$message = is_array( $what ) || is_object( $what ) ? print_r( $what, true ) : $what;
		$debug_file = ACSS_DYNAMIC_CSS_DIR . DIRECTORY_SEPARATOR . 'debug.log';
		$error_log = defined( WP_DEBUG ) ? (bool) WP_DEBUG : false;
		if ( is_writable( $debug_file ) || ( ! file_exists( $debug_file ) && is_writable( ACSS_DYNAMIC_CSS_DIR ) ) ) {
			// either the file exists and is writable, or it doesn't exist but the directory is writable.
			$message .= "\n";
			file_put_contents( $debug_file, $message, FILE_APPEND );
			$error_log = false; // don't error_log this.
		}
		if ( $error_log ) {
			error_log( $message );
		}
	}

}
