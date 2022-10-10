<?php
/**
 * Automatic.css Database_Settings file.
 *
 * @package Automatic_CSS
 */

namespace Automatic_CSS\Model;

use Automatic_CSS\CSS_Engine\CSS_Engine;
use Automatic_CSS\Exceptions\Invalid_Form_Values;
use Automatic_CSS\Exceptions\Invalid_Variable;
use Automatic_CSS\Helpers\Timer;
use Automatic_CSS\Plugin;
use Automatic_CSS\Helpers\Logger;
use Automatic_CSS\Model\Config\Variables;
use Automatic_CSS\Traits\Singleton;

/**
 * Automatic.css Database_Settings class.
 */
class Database_Settings {

	use Singleton;

	/**
	 * Stores the name of the plugin's database option
	 *
	 * @var string
	 */
	public const ACSS_SETTINGS_OPTION = 'automatic_css_settings';

	/**
	 * Stores the current value from the wp_options table.
	 *
	 * @var array
	 */
	private $plugin_wp_options = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		if ( is_admin() ) {
			// Handle database changes when the plugin is updated.
			add_filter( 'automaticcss_upgrade_database', array( $this, 'upgrade_database' ), 10, 4 );
			// Handle deleting database options when the plugin is deleted.
			add_action( 'automaticcss_delete_plugin_data_start', array( $this, 'delete_database_options' ) );
		}
	}

	/**
	 * Get the current VARS values from the wp_options database table.
	 *
	 * @return array
	 */
	public function get_vars() {
		if ( ! isset( $this->plugin_wp_options ) ) {
			$this->plugin_wp_options = get_option( self::ACSS_SETTINGS_OPTION );
		}
		return $this->plugin_wp_options;
	}

	/**
	 * Get the value for a specific variable from the wp_options database table.
	 *
	 * @param string $var The variable name.
	 * @return mixed
	 * @throws \Exception If the variable name cannot be found.
	 */
	public function get_var( $var ) {
		$vars = $this->get_vars();
		if ( is_array( $vars ) && array_key_exists( $var, $vars ) ) {
			return $vars[ $var ];
		}
		return null;
	}

	/**
	 * Save the plugin's options to the database. Will work even if option doesn't exist (fresh start).
	 *
	 * @see https://developer.wordpress.org/reference/functions/update_option/
	 * @param array $values The plugin's options.
	 * @param bool  $trigger_css_generation Trigger the CSS generation process upon saving or not.
	 * @return array Info about the saved options and the generated CSS files.
	 * @throws Invalid_Form_Values If the form values are not valid.
	 */
	public function save_vars( $values, $trigger_css_generation = true ) {
		$timer = new Timer();
		$return_info = array(
			'has_changed' => false,
			'generated_files' => array(),
			'generated_files_number' => 0,
		);
		$allowed_variables = ( Variables::get_instance() )->load();
		$sanitized_values = array();
		$errors = array();
		Logger::log( sprintf( '%s: triggering automaticcss_settings_save', __METHOD__ ) );
		do_action( 'automaticcss_settings_before_save', $values );
		// STEP: validate the form values and get the sanitized values.
		foreach ( $allowed_variables as $var_id => $var_options ) {
			// This makes it so that we ignore non allowed variables coming from the form (i.e. variables not in our config file).
			if ( array_key_exists( $var_id, $values ) ) {
				try {
					$sanitized_values[ $var_id ] = $this->get_validated_var( $var_id, $values[ $var_id ], $var_options, $values );
				} catch ( Invalid_Variable $e ) {
					$errors[ $var_id ] = $e->getMessage();
				}
			}
		}
		// STEP: if there are errors, throw an exception.
		if ( ! empty( $errors ) ) {
			Logger::log( sprintf( "%s: errors found while saving settings:\n%s", __METHOD__, print_r( $errors, true ) ) );
			$error_message = 'The settings you tried to save contain errors.';
			throw new Invalid_Form_Values( $error_message, $errors );
		}
		// STEP: save the sanitized values to the database.
		Logger::log( sprintf( "%s: saving these variables to the database:\n%s", __METHOD__, print_r( $sanitized_values, true ) ) );
		if ( update_option( self::ACSS_SETTINGS_OPTION, $sanitized_values ) ) {
			$return_info['has_changed'] = true;
			$this->plugin_wp_options = $sanitized_values;
			// STEP: if the settings have changed and CSS generation is enabled, regenerate the CSS.
			if ( $trigger_css_generation ) {
				$return_info['generated_files'] = CSS_Engine::get_instance()->generate_all_css_files( $sanitized_values );
				$return_info['generated_files_number'] = count( $return_info['generated_files'] );
			}
			do_action( 'automaticcss_settings_after_save', $sanitized_values );
		}
		Logger::log(
			sprintf(
				'%s: done (saved settings: %b; regenerated CSS files: %s) in %s seconds',
				__METHOD__,
				$return_info['has_changed'],
				print_r( implode( ', ', $return_info['generated_files'] ), true ),
				$timer->get_time()
			)
		);
		return $return_info;
	}

	/**
	 * Validate a variable based on its type and value and return a sanitized value.
	 *
	 * @param string $var_id Variable's ID.
	 * @param mixed  $var_value Variable's value.
	 * @param array  $var_options Variable's options.
	 * @param array  $all_values All variables' values.
	 * @return mixed
	 * @throws Invalid_Variable Exception if the variable is invalid.
	 */
	public function get_validated_var( $var_id, $var_value, $var_options, $all_values = array() ) {
		$type = isset( $var_options['type'] ) ? $var_options['type'] : null;
		if ( null === $type ) {
			throw new Invalid_Variable( sprintf( '%s has no type defined.', $var_id ) );
		}
		// STEP: perform a basic sanitization on the form's field.
		$var_value = sanitize_text_field( $var_value );
		// STEP: check that the value is not empty, if required.
		$required = self::is_required( $var_id, $var_value, $var_options, $all_values );
		if ( ! $required && '' === $var_value ) {
			// nothing else to check.
			Logger::log( sprintf( '%s: %s is not required and is empty, skipping its validation', __METHOD__, $var_id ) );
			return $var_value;
		} else if ( $required && '' === $var_value ) {
			Logger::log( sprintf( '%s: %s is required and is empty, throwing an exception', __METHOD__, $var_id ) );
			throw new Invalid_Variable( sprintf( '%s cannot be empty.', $var_id ) );
		}
		// STEP: validate the value based on the type.
		switch ( $type ) {
			case 'text':
				if ( ! is_string( $var_value ) ) {
					throw new Invalid_Variable( sprintf( '%s is not a string.', $var_id ) );
				}
				break;
			case 'number':
				// STEP: check that the value is a number.
				if ( ! is_numeric( $var_value ) ) {
					throw new Invalid_Variable( sprintf( '%s is not a number.', $var_id ) );
				}
				// STEP: convert it to the proper type.
				$step = isset( $var_options['step'] ) ? $var_options['step'] : 1;
				$var_value = 1 === $step ? intval( $var_value ) : floatval( $var_value );
				// STEP: check that the value is within the allowed range.
				$min = isset( $var_options['min'] ) ? $var_options['min'] : null;
				$max = isset( $var_options['max'] ) ? $var_options['max'] : null;
				if ( null !== $min && $var_value < $min ) {
					throw new Invalid_Variable( sprintf( '%s is less than the minimum allowed value of %s.', $var_id, $min ) );
				}
				if ( null !== $max && $var_value > $max ) {
					throw new Invalid_Variable( sprintf( '%s is greater than the maximum allowed value of %s.', $var_id, $max ) );
				}
				break;
			case 'color':
				// STEP: check that the value is a hex color.
				$var_value = sanitize_hex_color( $var_value );
				if ( ! preg_match( '/^#[a-f0-9]{6}$/i', $var_value ) ) {
					throw new Invalid_Variable( sprintf( '%s is not a valid hex color.', $var_id ) );
				}
				break;
			case 'select':
				// STEP: convert the value to the proper type (if it's a string, it stays that way).
				$var_value = self::get_converted_value( $var_value );
				// STEP: check if the value is in the list of allowed values.
				$options = isset( $var_options['options'] ) ? $var_options['options'] : null;
				if ( null === $options ) {
					throw new Invalid_Variable( sprintf( '%s has no options defined.', $var_id ) );
				}
				if ( ! in_array( $var_value, $options, true ) ) {
					throw new Invalid_Variable( sprintf( '%s is not a valid option for %s.', $var_value, $var_id ) );
				}
				break;
			case 'toggle':
				// STEP: check that the value is one of the valueon or valueoff options.
				$value_on = isset( $var_options['valueon'] ) ? $var_options['valueon'] : null;
				$value_off = isset( $var_options['valueoff'] ) ? $var_options['valueoff'] : null;
				if ( null === $value_on || null === $value_off ) {
					throw new Invalid_Variable( sprintf( '%s has no valueon or valueoff defined.', $var_id ) );
				}
				Logger::log( sprintf( '%s: %s is a toggle with value %s, valueon is %s, valueoff is %s', __METHOD__, $var_id, $var_value, $value_on, $value_off ) );
				if ( $var_value !== $value_on && $var_value !== $value_off ) {
					throw new Invalid_Variable( sprintf( '%s is not a valid option.', $var_value ) );
				}
				break;
		}
		// STEP: return the validated and sanitized value.
		return $var_value;
	}

	/**
	 * Check weather a variable is required based on its settings and possibly other variables' values.
	 *
	 * @param string $var_id Variable's ID.
	 * @param mixed  $var_value Variable's value.
	 * @param array  $var_options Variable's options.
	 * @param array  $all_values All variables' values.
	 * @return boolean
	 */
	private static function is_required( $var_id, $var_value, $var_options, $all_values ) {
		// STEP: check if it has a default value.
		$required = ! empty( $var_options['default'] );
		// STEP: check if another field requires this field.
		$required_by_condition = false;
		if ( ! empty( $var_options['condition'] ) && is_array( $var_options['condition'] ) ) {
			$condition = $var_options['condition'];
			if ( ! empty( $condition['type'] ) && 'show_only_if' === $condition['type'] ) {
				// This field is required if condition_field is set to condition_value and condition_required is true.
				$condition_field = ! empty( $condition['field'] ) ? $condition['field'] : '';
				$condition_value = isset( $condition['value'] ) ? self::get_converted_value( $condition['value'] ) : null;
				$condition_required = ! empty( $condition['required'] ) ? (bool) $condition['required'] : true;
				if ( '' !== $condition_field && null !== $condition_value ) {
					$actual_value = isset( $all_values[ $condition_field ] ) ? self::get_converted_value( $all_values[ $condition_field ] ) : null;
					Logger::log( sprintf( '%s: checking condition for %s - %s is %s (required value is %s)', __METHOD__, $var_id, $condition_field, $actual_value, $condition_value ) );
					if ( null !== $actual_value && $actual_value === $condition_value ) {
						Logger::log( sprintf( '%s: %s required %b because %s is %s', __METHOD__, $var_id, $condition_required, $condition_field, $actual_value ) );
						$required_by_condition = $condition_required;
					}
				}
			}
		}
		// STEP: return the result.
		return $required || $required_by_condition;
	}


	/**
	 * Convert the value based on the type. Supports int, float and string.
	 *
	 * @param mixed $value The input value.
	 * @return mixed
	 */
	public static function get_converted_value( $value ) {
		if ( self::is_int( $value ) ) {
			return intval( $value );
		} else if ( self::is_float( $value ) ) {
			return floatval( $value );
		}
		return $value;
	}

	/**
	 * Is this value an integer?
	 *
	 * @param mixed $value The value to check.
	 * @return boolean
	 */
	private static function is_int( $value ) {
		return( ctype_digit( strval( $value ) ) );
	}

	/**
	 * Is this value a float?
	 *
	 * @param mixed $value The value to check.
	 * @return boolean
	 */
	private static function is_float( $value ) {
		return (string) (float) $value === $value;
	}

	/**
	 * Update database fields and values upon plugin upgrade.
	 *
	 * @param array  $values The database values.
	 * @param string $current_version The version of the plugin we're upgrading to.
	 * @param string $previous_version The version of the plugin we're upgrading from.
	 * @return array The (maybe modified) database values.
	 */
	public function upgrade_database( $values, $current_version, $previous_version ) {
		if ( version_compare( $previous_version, '2.0.0-dev0', '<' ) && version_compare( $current_version, '2.0.0-dev0', '>=' ) ) {
			Logger::log( sprintf( '%s: upgrading database from before 2.0 to 2.0', __METHOD__ ) );
			// Handle section-padding-x -> section-padding-x-max conversion.
			if ( array_key_exists( 'section-padding-x', $values ) ) {
				Logger::log( sprintf( '%s: converting section-padding-x to section-padding-x-max', __METHOD__ ) );
				$values['section-padding-x-max'] = $values['section-padding-x'];
				unset( $values['section-padding-x'] );
			}
			// Handle primary-hover-var -> primary-hover-l conversion.
			$color_types = array( 'primary', 'secondary', 'base', 'accent', 'shade' );
			$color_variations = array( 'hover', 'ultra-light', 'light', 'medium', 'dark', 'ultra-dark' );
			foreach ( $color_types as $color_type ) {
				foreach ( $color_variations as $color_variation ) {
					$old_var = $color_type . '-' . $color_variation . '-val';
					if ( array_key_exists( $old_var, $values ) ) {
						$new_var = $color_type . '-' . $color_variation . '-l';
						Logger::log(
							sprintf(
								'%s: converting %s to %s with value %s',
								__METHOD__,
								$old_var,
								$new_var,
								$values[ $old_var ]
							)
						);
						$values[ $new_var ] = $values[ $old_var ];
						unset( $values[ $old_var ] );
					}
				}
			}
			// Handle text overrides REM -> px conversion.
			$text_size_variations = array( 'xs', 's', 'm', 'l', 'xl', 'xxl' );
			$text_size_min_max_variations = array( 'min', 'max' );
			$root_font_size = array_key_exists( 'root-font-size', $values ) ? floatval( $values['root-font-size'] ) : 62.5;
			foreach ( $text_size_variations as $text_size_variation ) {
				foreach ( $text_size_min_max_variations as $min_max_variation ) {
					$text_size_var = 'text-' . $text_size_variation . '-' . $min_max_variation;
					// When these values were converted from REM to PX, they were divided by 10 and then adjusted for root-font-size.
					// So: new value = old value * 10 * root-font-size / 62.5.
					if ( array_key_exists( $text_size_var, $values ) && '' !== $values[ $text_size_var ] ) { // accept 0 though.
						$text_size_old_value = $values[ $text_size_var ];
						$text_size_new_value = $text_size_old_value * 10 * $root_font_size / 62.5;
						Logger::log( sprintf( '%s: converting %s from %s to %s', __METHOD__, $text_size_var, $text_size_old_value, $text_size_new_value ) );
						$values[ $text_size_var ] = $text_size_new_value;
					}
				}
			}
		}
		return $values;
	}

	/**
	 * Get all possible color combinations.
	 *
	 * @param boolean $with_transparency Whether to include transparency in the color combinations.
	 * @return array
	 */
	public static function get_all_color_combinations( $with_transparency = true ) {
		$colors = array();
		$main_colors = array( 'primary', 'secondary', 'accent', 'base', 'shade' );
		$color_modifiers = array( 'ultra-light', 'light', 'medium', 'dark', 'ultra-dark', 'hover', 'comp' );
		$transparent_colors = array( '', 'light', 'ultra-dark' );
		$color_transparencies = array( 'trans-10', 'trans-20', 'trans-40', 'trans-60', 'trans-80', 'trans-90' );
		foreach ( $main_colors as $main_color ) {
			$color = array(
				'name' => ucfirst( $main_color ),
				'main_color' => $main_color,
				'shades' => array(),
				'trans' => array(),
			);
			// STEP: add color modifiers (i.e. primary-dark).
			foreach ( $color_modifiers as $color_modifier ) {
				$color['shades'][] = $main_color . '-' . $color_modifier;
			}
			if ( $with_transparency ) {
				// STEP: add color transparencies (i.e. primary-trans-10).
				foreach ( $transparent_colors as $transparent_color ) {
					foreach ( $color_transparencies as $color_transparency ) {
						$middle_part = empty( $transparent_color ) ? '' : '-' . $transparent_color;
						$final_part = '-' . $color_transparency;
						$color['trans'][] = $main_color . $middle_part . $final_part;
					}
				}
			}
			$colors[ $main_color ] = $color;
		}
		return $colors;
	}

	/**
	 * Delete the framework's database option(s).
	 *
	 * @return void
	 */
	public function delete_database_options() {
		delete_option( self::ACSS_SETTINGS_OPTION );
	}
}
