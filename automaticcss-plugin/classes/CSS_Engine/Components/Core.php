<?php
/**
 * Automatic.css Framework's Core file.
 *
 * @package Automatic_CSS
 */

namespace Automatic_CSS\CSS_Engine\Components;

use Automatic_CSS\CSS_Engine\Components\Base;
use Automatic_CSS\CSS_Engine\CSS_File;
use Automatic_CSS\Helpers\Logger;
use Automatic_CSS\Helpers\Timer;
use Automatic_CSS\Model\Config\Variables;

/**
 * Automatic.css Framework's Core class.
 */
class Core extends Base {

	/**
	 * Instance of the core CSS file
	 *
	 * @var CSS_File
	 */
	private $core_css_file;

	/**
	 * Instance of the vars CSS file
	 *
	 * @var CSS_File
	 */
	private $vars_css_file;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->core_css_file = $this->add_css_file( new CSS_File( 'automaticcss-core', 'automatic.css', 'automatic.scss' ) );
		$this->vars_css_file = $this->add_css_file( new CSS_File( 'automaticcss-variables', 'automatic-variables.css', 'automatic-variables.scss' ) );
		// enqueue the framework's CSS file(s).
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_core_stylesheet' ) );
		// add the CSS file to the ones enqueued when the Oxygen editor is detected.
		add_action( 'automaticcss_in_builder_context', array( $this, 'enqueue_variables_and_dequeue_core' ) );
	}

	/**
	 * Enqueue the core CSS file.
	 *
	 * @return void
	 */
	public function enqueue_core_stylesheet() {
		$this->core_css_file->enqueue_stylesheet();
	}

	/**
	 * Enqueue the variables CSS file in place of the core CSS file.
	 *
	 * @return void
	 */
	public function enqueue_variables_and_dequeue_core() {
		Logger::log( sprintf( '%s: removing automaticcss-core and enqueuing automaticcss-variables', __METHOD__ ) );
		$this->core_css_file->dequeue_stylesheet();
		$this->vars_css_file->enqueue_stylesheet();
	}

	/**
	 * Generate the framework's CSS variables.
	 *
	 * @param array $values CSS variable values.
	 * @return array
	 */
	public function get_framework_variables( $values ) {
		$timer = new Timer();
		Logger::log( sprintf( '%s: starting', __METHOD__ ) );
		$variables = array();
		$vars = ( Variables::get_instance() )->load();
		// Get the root font size for later calculations.
		$root_font_size = array_key_exists( 'root-font-size', $vars ) ? $vars['root-font-size'] : null;
		$root_font_size_default = is_array( $root_font_size ) && array_key_exists( 'default', $root_font_size ) ? floatval( $root_font_size['default'] ) : '62.5';
		foreach ( $vars as $var => $options ) {
			$skip_var_generation = array_key_exists( 'nocssvar', $options ) ? (bool) $options['nocssvar'] : false;
			if ( $skip_var_generation ) {
				continue; // Some variables from the JSON file don't need a CSS variable generated for them. Skip them.
			}
			$type = $options['type'];
			if ( array_key_exists( $var, $values ) ) {
				/* @since 1.0.5 - allow programmatic filtering of the value of any CSS variable */
				$value = apply_filters( "automaticcss_input_value_{$var}", $values[ $var ] );
				$override = array_key_exists( 'override', $options ) ? true : false;
				if ( $override && '' === $value ) {
					continue; // Override variables only need to be in the CSS if they have a value. Skip them if empty.
				}
				if ( array_key_exists( 'variable', $options ) ) {
					$var = $options['variable'];
				}
				if ( 'color' === $type ) {
					$color = new \Automatic_CSS\Helpers\Color( $value );
					$var = str_replace( 'color-', '', $var ); // Color variables may have a "color-" prefix in the JSON file that needs to be stripped out.
					/* @since 2.0-beta3 - allow programmatic filtering of the value of color variables */
					$variables[ $var . '-h' ] = apply_filters( "automaticcss_output_value_{$var}-h", $color->h );
					$variables[ $var . '-s' ] = apply_filters( "automaticcss_output_value_{$var}-s", $color->s_perc );
					$variables[ $var . '-l' ] = apply_filters( "automaticcss_output_value_{$var}-l", $color->l_perc );
				} else {
					/**
					 * Handle special fields.
					 */
					if ( 'text-scale' === $var && 0.0 === floatval( $value ) ) {
						// "custom" text-scale: use the text-scale-custom value instead.
						$value = array_key_exists( 'text-scale-custom', $values ) ? $values['text-scale-custom'] : '';
					} else if ( 'mob-text-scale' === $var && 0.0 === floatval( $value ) ) {
						// "custom" text-scale: use the mob-text-scale-custom value instead.
						$value = array_key_exists( 'mob-text-scale-custom', $values ) ? $values['mob-text-scale-custom'] : '';
					} else if ( 'space-scale' === $var && 0.0 === floatval( $value ) ) {
						// "custom" space-scale: use the space-scale-custom value instead.
						$value = array_key_exists( 'space-scale-custom', $values ) ? $values['space-scale-custom'] : '';
					} else if ( 'mob-space-scale' === $var && 0.0 === floatval( $value ) ) {
						// "custom" text-scale: use the mob-space-scale-custom value instead.
						$value = array_key_exists( 'mob-space-scale-custom', $values ) ? $values['mob-space-scale-custom'] : '';
					} else if ( 'breakpoint-xl' === $var ) {
						$value = $values['vp-max']; // the value of breakpoint-xl is always calculated on the fly, never saved to the database.
					}
					/**
					 * Handle units.
					 */
					$unit = array_key_exists( 'unit', $options ) ? $options['unit'] : null;
					$skip_conversion = array_key_exists( 'skip-unit-conversion', $options ) ? (bool) $options['skip-unit-conversion'] : false;
					if ( ! $skip_conversion ) {
						// TODO: ensure $value is numeric or... convert to default?
						if ( 'px' === $unit ) {
							// values in px need to be divided by 10 and then converted to rem.
							$value = floatval( $value ) / 10;
						} else if ( '%' === $unit && substr( $value, -1 ) !== '%' ) {
							// add a '%' sign at the end of '%' variables that are missing it.
							$value .= '%';
						}
						/**
						 * Handle 62.5 <-> current root font size conversion (if necessary) before saving the value to the CSS directive.
						 */
						$convert = array_key_exists( 'percentage-convert', $options ) ? (bool) $options['percentage-convert'] : false;
						if ( $convert && null !== $root_font_size ) { // $root_font_size and $root_font_size_default were set earlier.
							$root_font_size_value = array_key_exists( 'root-font-size', $values ) ? floatval( $values['root-font-size'] ) : $root_font_size_default;
							// current value : $root_font_size_default (62.5%) = new value : $root_font_size_value.
							$value = floatval( $value ) * $root_font_size_default / $root_font_size_value;
						}
					}
					/**
					 * Handle adding the unit at the end of the variable, if need be.
					 */
					if ( array_key_exists( 'appendunit', $options ) && '' !== $options['appendunit'] ) {
						$value .= $options['appendunit'];
					}
					/**
					 * Output the actual value.
					 */
					/* @since 1.1.0-beta2 - allow programmatic filtering of the value of any CSS variable */
					$variables[ $var ] = apply_filters( "automaticcss_output_value_{$var}", $value );
				}
			}
		}
		Logger::log( sprintf( '%s: done in %s seconds', __METHOD__, $timer->get_time() ) );
		return $variables;
	}

}
