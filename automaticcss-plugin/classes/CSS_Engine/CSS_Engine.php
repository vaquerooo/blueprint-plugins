<?php
/**
 * Automatic.css Framework's CSS_Engine file.
 *
 * @package Automatic_CSS
 */

namespace Automatic_CSS\CSS_Engine;

use Automatic_CSS\Model\Database_Settings;
use Automatic_CSS\Model\Config\Variables;
use Automatic_CSS\Helpers\Logger;
use Automatic_CSS\Helpers\Timer;
use Automatic_CSS\PHPColors\Color;
use Automatic_CSS\Traits\Singleton;

/**
 * Automatic.css Framework's CSS_Engine class.
 */
class CSS_Engine {

	use Singleton;

	/**
	 * Stores framework instances.
	 *
	 * @var array
	 */
	private $framework = array();

	/**
	 * Stores platform instances.
	 *
	 * @var array
	 */
	private $platforms = array();

	/**
	 * Stores the current VARS values from the wp_options database table.
	 *
	 * @var Database_Settings
	 */
	private $database_settings;

	/**
	 * Loads the basic CSS_Engine components.
	 *
	 * @return void
	 */
	public function load_components() {
		// (re)generate the framework's CSS file(s) when the plugin is activated.
		add_action( 'automaticcss_activate_plugin_start', array( $this, 'update_framework_css_files' ) );
		// (re)generate the framework's CSS file(s) when the plugin is updated.
		// @since 1.1.1.1 - MG - we don't do this anymore: too many side effects.
		// add_action( 'automaticcss_update_plugin_start', array( $this, 'update_framework_css_files' ) );
		// @since 1.4.0 - MG - to handle changes in variable names that need to carry over the values.
		add_action( 'automaticcss_update_plugin_start', array( $this, 'handle_database_upgrade' ), 10, 2 );
		// delete the framework's CSS file(s) when the plugin is deleted.
		add_action( 'automaticcss_delete_plugin_data_end', array( $this, 'delete_css_files' ) );
		// Initialize modules.
		$this->database_settings = Database_Settings::get_instance();
		Logger::log( sprintf( '%s: activating Core module', __METHOD__ ) );
		$this->framework['core'] = new Components\Core();
		// Initialize platforms.
		if ( Components\Platforms\Oxygen::is_active() ) {
			Logger::log( sprintf( '%s: activating Oxygen module', __METHOD__ ) );
			$this->platforms['oxygen'] = new Components\Platforms\Oxygen();
		} else if ( Components\Platforms\Bricks::is_active() ) { // can't activate Oxygen and Bricks at the same time, Oxygen has precedence.
			Logger::log( sprintf( '%s: activating Bricks module', __METHOD__ ) );
			$this->platforms['bricks'] = new Components\Platforms\Bricks();
		}
		/*
		20220627 - MG - WooCommerce platform will be taken care of later.
		if ( Components\Platforms\WooCommerce::is_active() ) {
			// The WooCommerce platform has to be initialized even if the option is turned off,
			// otherwise it won't be available when saving a form with the option-woocommerce setting on.
			$woocommerce_enabled = $this->database_settings->get_var( 'option-woocommerce' ) === 'on' ? true : false;
			Logger::log( sprintf( '%s: activating WooCommerce module with enabled = %s', __METHOD__, $woocommerce_enabled ) );
			$this->platforms['woocommerce'] = new Components\Platforms\WooCommerce( $woocommerce_enabled );
		}
		*/
	}

	/**
	 * Create of update the framework's stylesheet(s)
	 * from the existing values (if the wp_option exists)
	 * - OR -
	 * from the framework's defaults (if it doesn't exist)
	 */
	public function update_framework_css_files() {
		// TODO: find a better method name.
		Logger::log( sprintf( '%s: creating or updating framework CSS files', __METHOD__ ) );
		// Generate from current vars from db (if they exist), otherwise use defaults.
		$values = $this->database_settings->get_vars();
		if ( empty( $values ) ) {
			Logger::log( sprintf( '%s: no vars found in database, using default values', __METHOD__ ) );
			$values = ( Variables::get_instance() )->load_defaults();
			$this->database_settings->save_vars( $values, false ); // will NOT trigger the CSS file generation.
		}
		$this->generate_all_css_files( $values );
		Logger::log( sprintf( '%s: done', __METHOD__ ) );
	}

	/**
	 * Generate and save all registered stylesheets for the provided values.
	 *
	 * @param array $database_settings The values of the settings from the database.
	 * @return array The generated CSS files.
	 * @throws Exception In case of errors.
	 */
	public function generate_all_css_files( $database_settings ) {
		$timer = new Timer();
		Logger::log( sprintf( '%s: starting', __METHOD__ ) );
		$generated_files = array();
		$components = $this->get_components();
		$variables = apply_filters( 'automaticcss_framework_variables', $this->framework['core']->get_framework_variables( $database_settings ) );
		do_action( 'automaticcss_before_generate_framework_css', $variables );
		Logger::log( sprintf( "%s: generating CSS for these variables:\n%s", __METHOD__, print_r( $variables, true ) ) );
		foreach ( $components as $component_key => $component ) {
			Logger::log( sprintf( '%s: generating CSS file for component %s', __METHOD__, $component_key ) );
			$generated_files = array_merge( $generated_files, $component->generate_own_css_files( $variables ) );
			Logger::log( sprintf( '%s: done generating CSS file for component %s', __METHOD__, $component_key ) );
		}
		do_action( 'automaticcss_after_generate_framework_css', $variables );
		Logger::log( sprintf( '%s: done in %s seconds', __METHOD__, $timer->get_time() ) );
		return $generated_files;
	}

	/**
	 * Handle changes to the database due to upgrading / downgrading the plugin.
	 *
	 * @param string $current_version The plugin version currently installed.
	 * @param string $previous_version The plugin version previously installed.
	 * @return void
	 */
	public function handle_database_upgrade( $current_version, $previous_version ) {
		Logger::log( sprintf( '%s: starting', __METHOD__ ) );
		$current_values = $this->database_settings->get_vars();
		$new_values = apply_filters( 'automaticcss_upgrade_database', $current_values, $current_version, $previous_version );
		if ( $new_values !== $current_values ) {
			Logger::log( sprintf( '%s: settings changed, saving and triggering CSS generation', __METHOD__ ) );
			$this->database_settings->save_vars( $new_values ); // will maybe trigger generate_all_css_files.
		}
		Logger::log( sprintf( '%s: done', __METHOD__ ) );
	}

	/**
	 * Delete all CSS_Files
	 *
	 * @return void
	 */
	public function delete_css_files() {
		$components = $this->get_components();
		foreach ( $components as $component ) {
			$css_files = $component->get_css_files();
			if ( ! empty( $css_files ) ) {
				foreach ( $css_files as $css_file ) {
					if ( is_a( $css_file, 'Automatic_CSS\CSS_Engine\CSS_File' ) ) {
						$css_file->delete_file();
					}
				}
			}
		}
	}

	/**
	 * Get the framework and platform components in one array
	 *
	 * @return array
	 */
	private function get_components() {
		return array_merge( $this->framework, $this->platforms );
	}
}
