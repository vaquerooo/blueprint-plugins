<?php
/**
 * Automatic.css Bricks class file.
 *
 * @package Automatic_CSS
 */

namespace Automatic_CSS\CSS_Engine\Components\Platforms;

use Automatic_CSS\CSS_Engine\CSS_File;
use Automatic_CSS\CSS_Engine\Components\Base;
use Automatic_CSS\Model\Config\Classes;
use Automatic_CSS\Helpers\Logger;
use Automatic_CSS\Model\Database_Settings;

/**
 * Automatic.css Bricks class.
 */
class Bricks extends Base implements Platform {

	/**
	 * Instance of the CSS file
	 *
	 * @var CSS_File
	 */
	private $css_file;

	/**
	 * Are we in the Bricks builder?
	 * I.e. is $_GET['bricks'] equal to "run" but $_GET['brickspreview'] is not set?
	 *
	 * @var bool
	 */
	private $is_builder;

	/**
	 * Are we in the Bricks iframe?
	 * I.e. is $_GET['brickspreview'] set?
	 *
	 * @var bool
	 */
	private $is_iframe;

	/**
	 * Used to namespace the global classes array.
	 */
	const CLASS_IMPORT_ID_PREFIX = 'acss_import_';

	/**
	 * Used to namespace the color palette array.
	 */
	const PALETTE_IMPORT_ID_PREFIX = 'acss_import_';

	/**
	 * Name of the color palette.
	 */
	const PALETTE_IMPORT_NAME_PREFIX = 'ACSS ';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->css_file = $this->add_css_file(
			new CSS_File(
				'automaticcss-bricks',
				'automatic-bricks.css',
				array(
					'source_file' => 'platforms/bricks/automatic-bricks.scss',
					'imports_folder' => 'platforms/bricks',
				),
				array(
					'deps' => apply_filters( 'automaticcss_bricks_deps', array( 'bricks-frontend' ) ),
				)
			)
		);
		if ( is_admin() ) {
			add_action( 'automaticcss_activate_plugin_end', array( $this, 'update_globals' ) );
			add_action( 'automaticcss_update_plugin_end', array( $this, 'update_globals' ) );
			add_action( 'automaticcss_deactivate_plugin_start', array( $this, 'delete_globals' ) ); // 20220630 - MG - used to hook into automaticcss_delete_plugin_data_end.
			// Inform the SCSS compiler that we're using the Bricks platform.
			add_filter( 'automaticcss_framework_variables', array( $this, 'inject_scss_enabler_option' ) );
		} else {
			// Bricks enqueues in 'wp_enqueue_scripts' with priority 10.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_bricks_resets' ), 11 );
			// Allow for Core stylesheet to be removed in the builder context.
			add_action( 'wp_enqueue_scripts', array( $this, 'remove_framework_stylesheet_from_builder' ), 20 );
		}
	}

	/**
	 * Inject an SCSS variable in the CSS generation process to enable this module.
	 *
	 * @param array $variables The values for the framework's variables.
	 * @return array
	 */
	public function inject_scss_enabler_option( $variables ) {
		$variables['option-bricks'] = 'on';
		return $variables;
	}

	/**
	 * Enqueue the Bricks reset stylesheet.
	 *
	 * @return void
	 */
	public function enqueue_bricks_resets() {
		if ( $this->in_builder_context() ) {
			return;
		}
		$this->css_file->enqueue_stylesheet();
	}

	/**
	 * Remove ACSS scripts & styles if we the builder context.
	 *
	 * @return void
	 */
	public function remove_framework_stylesheet_from_builder() {
		if ( ! $this->in_builder_context() ) {
			return;
		}
		Logger::log( sprintf( '%s: builder context detected, triggering automaticcss_in_builder_context', __METHOD__ ) );
		do_action( 'automaticcss_in_builder_context' );
	}

	/**
	 * Add the framework's classes to Bricks for autocomplete.
	 *
	 * @return void
	 */
	public function update_globals() {
		Logger::log( sprintf( '%s: adding Automatic.css classes and palettes into Bricks global classes', __METHOD__ ) );
		/**
		 * Update the global classes.
		 */
		$acss_classes = ( new Classes() )->load();
		if ( is_array( $acss_classes ) && count( $acss_classes ) > 0 ) {
			$bricks_global_classes = (array) get_option( 'bricks_global_classes', array() );
			$bricks_global_class_names = array_column( $bricks_global_classes, 'name' );
			$bricks_locked_classes = (array) get_option( 'bricks_global_classes_locked', array() );
			/**
			 * Global classes array structure:
			 * 0 => array (
			 *      'id' => a random string,
			 *      'name' => the actual class name,
			 *      'settings' => array(),
			 *  )
			 *
			 * Locked classes array structure:
			 * array (
			 *  0 => 'align-content--baseline',
			 *  )
			 */
			foreach ( $acss_classes as $acss_class ) {
				// STEP: add our class to Bricks global classes, if it's not there yet.
				if ( ! in_array( $acss_class, $bricks_global_class_names ) ) {
					$bricks_global_classes[] = array(
						'id' => self::CLASS_IMPORT_ID_PREFIX . $acss_class,
						'name' => $acss_class,
						'settings' => array(),
					);
				}
				// STEP: add our class to Bricks locked classes, if it's not there yet.
				if ( ! in_array( self::CLASS_IMPORT_ID_PREFIX . $acss_class, $bricks_locked_classes ) ) {
					$bricks_locked_classes[] = self::CLASS_IMPORT_ID_PREFIX . $acss_class;
				}
			}
			// STEP: update the options.
			update_option( 'bricks_global_classes', $bricks_global_classes, false );
			update_option( 'bricks_global_classes_locked', $bricks_locked_classes, false );
			Logger::log( sprintf( '%s: Bricks classes updated', __METHOD__ ) );
		}
		/**
		 * Update the global palettes.
		 * There's a few of them. Each main color has an entry in the 'global' palette, plus a palette of its own for shades and transparencies.
		 * Because the user can add their own colors to the palettes, we need to make sure we don't overwrite them.
		 */
		$bricks_color_palette = (array) get_option( 'bricks_color_palette', array() );
		// STEP: ensure we have the global palette and find its ID.
		$global_palette_id = array_search( self::PALETTE_IMPORT_ID_PREFIX . 'global', array_column( $bricks_color_palette, 'id' ) );
		if ( false === $global_palette_id ) {
			$bricks_color_palette[] = array(
				'id' => self::PALETTE_IMPORT_ID_PREFIX . 'global',
				'name' => self::PALETTE_IMPORT_NAME_PREFIX . 'Global',
				'colors' => array(),
			);
			$global_palette_id = array_key_last( $bricks_color_palette );
		}
		$all_colors = Database_Settings::get_all_color_combinations();
		foreach ( $all_colors as $color_id => $color_options ) {
			// properties: 'name', 'main_color', 'shades', 'transparencies'.
			// STEP: add the main color to the global palette.
			$main_color = $color_options['main_color'];
			if ( ! in_array( self::PALETTE_IMPORT_ID_PREFIX . $main_color, array_column( $bricks_color_palette[ $global_palette_id ]['colors'], 'id' ) ) ) {
				$bricks_color_palette[ $global_palette_id ]['colors'][] = array(
					'id' => self::PALETTE_IMPORT_ID_PREFIX . $main_color,
					'name' => $main_color,
					'raw' => "var(--${main_color})",
				);
			}
			// STEP: ensure there's a palette for this color.
			$color_palette_id = array_search( self::PALETTE_IMPORT_ID_PREFIX . $color_id, array_column( $bricks_color_palette, 'id' ) );
			if ( false === $color_palette_id ) {
				$bricks_color_palette[] = array(
					'id' => self::PALETTE_IMPORT_ID_PREFIX . $color_id,
					'name' => self::PALETTE_IMPORT_NAME_PREFIX . $color_options['name'],
					'colors' => array(),
				);
				$color_palette_id = array_key_last( $bricks_color_palette );
			}
			$color_palette_ids = array_column( $bricks_color_palette[ $color_palette_id ]['colors'], 'id' );
			// STEP: add the main color to this color's palette.
			if ( ! in_array( self::PALETTE_IMPORT_ID_PREFIX . $main_color, $color_palette_ids ) ) {
				$bricks_color_palette[ $color_palette_id ]['colors'][] = array(
					'id' => self::PALETTE_IMPORT_ID_PREFIX . $main_color,
					'name' => $main_color,
					'raw' => "var(--${main_color})",
				);
			}
			// STEP: add the shades and transparencies to this color's palette.
			$shades_and_trans = array_merge( $color_options['shades'], $color_options['trans'] );
			foreach ( $shades_and_trans as $st_color ) {
				if ( ! in_array( self::PALETTE_IMPORT_ID_PREFIX . $st_color, $color_palette_ids ) ) {
					$bricks_color_palette[ $color_palette_id ]['colors'][] = array(
						'id' => self::PALETTE_IMPORT_ID_PREFIX . $st_color,
						'name' => $st_color,
						'raw' => "var(--${st_color})",
					);
				}
			}
		}
		// STEP: update the option.
		update_option( 'bricks_color_palette', $bricks_color_palette, false );
		Logger::log( sprintf( '%s: Bricks color palette updated', __METHOD__ ) );
		// Done.
		Logger::log( sprintf( '%s: done', __METHOD__ ) );
	}

	/**
	 * Remove the framework's classes to Bricks for autocomplete.
	 *
	 * @return void
	 */
	public function delete_globals() {
		Logger::log( sprintf( '%s: deleting Automatic.css classes and palettes from Bricks global classes', __METHOD__ ) );
		/**
		 * Delete the global classes.
		 */
		$acss_classes = ( new Classes() )->load();
		if ( is_array( $acss_classes ) && count( $acss_classes ) > 0 ) {
			$bricks_global_classes = (array) get_option( 'bricks_global_classes', array() );
			$bricks_global_class_ids = array_column( $bricks_global_classes, 'id' );
			$bricks_locked_classes = (array) get_option( 'bricks_global_classes_locked', array() );
			foreach ( $acss_classes as $acss_class ) {
				// STEP: remove our class from Bricks global classes, if it's there.
				// Check that it was added there by us by using the CLASS_IMPORT_ID_PREFIX, so check the 'id' and not the 'name'.
				// We use array_keys and not array_search because we don't know if there's multiple instances of the class for some reason.
				$global_indexes = array_keys( $bricks_global_class_ids, self::CLASS_IMPORT_ID_PREFIX . $acss_class );
				if ( is_array( $global_indexes ) && count( $global_indexes ) > 0 ) {
					foreach ( $global_indexes as $global_index ) {
						unset( $bricks_global_classes[ $global_index ] );
					}
					// STEP: remove our class from Bricks locked classes, if it's there, and only if it was inserted in the globals by us.
					// The locked classes don't have IDs, just names, so we only check $acss_class with no CLASS_IMPORT_ID_PREFIX.
					$locked_indexes = array_keys( $bricks_locked_classes, self::CLASS_IMPORT_ID_PREFIX . $acss_class );
					if ( is_array( $locked_indexes ) && count( $locked_indexes ) > 0 ) {
						foreach ( $locked_indexes as $locked_index ) {
							unset( $bricks_locked_classes[ $locked_index ] );
						}
					}
				}
			}
			// STEP: update the options.
			update_option( 'bricks_global_classes', array_values( $bricks_global_classes ), false ); // array_values to fix holes in the array.
			update_option( 'bricks_global_classes_locked', array_values( $bricks_locked_classes ), false ); // array_values to fix holes in the array.
			Logger::log( sprintf( '%s: Bricks classes updated', __METHOD__ ) );
		}
		/**
		 * Delete the color palettes.
		 */
		$all_colors = Database_Settings::get_all_color_combinations();
		$bricks_color_palette = (array) get_option( 'bricks_color_palette', array() );
		$bricks_color_palette_ids = array_column( $bricks_color_palette, 'id' );
		$global_palette_index = array_search( self::PALETTE_IMPORT_ID_PREFIX . 'global', $bricks_color_palette_ids );
		$global_palette_color_ids = false !== $global_palette_index ? array_column( $bricks_color_palette[ $global_palette_index ]['colors'], 'id' ) : array();
		foreach ( $all_colors as $color_id => $color_options ) {
			// STEP: remove the main color from the global palette.
			$main_color_index = array_search( self::PALETTE_IMPORT_ID_PREFIX . $color_id, $global_palette_color_ids );
			if ( false !== $main_color_index ) {
				unset( $bricks_color_palette[ $global_palette_index ]['colors'][ $main_color_index ] );
			}
			$this_color_index = array_search( self::PALETTE_IMPORT_ID_PREFIX . $color_id, $bricks_color_palette_ids );
			$this_color_ids = false !== $this_color_index ? array_column( $bricks_color_palette[ $this_color_index ]['colors'], 'id' ) : array();
			// STEP: remove the main color from this palette.
			$main_color_in_palette_index = array_search( self::PALETTE_IMPORT_ID_PREFIX . $color_id, $this_color_ids );
			if ( false !== $main_color_in_palette_index ) {
				unset( $bricks_color_palette[ $this_color_index ]['colors'][ $main_color_in_palette_index ] );
			}
			// STEP: remove the shades and trans from this palette.
			$shades_and_trans = array_merge( $color_options['shades'], $color_options['trans'] );
			foreach ( $shades_and_trans as $st_color ) {
				$st_color_index = array_search( self::PALETTE_IMPORT_ID_PREFIX . $st_color, $this_color_ids );
				if ( false !== $st_color_index ) {
					unset( $bricks_color_palette[ $this_color_index ]['colors'][ $st_color_index ] );
				}
			}
			// STEP: remove this palette if empty.
			if ( empty( $bricks_color_palette[ $this_color_index ]['colors'] ) ) {
				unset( $bricks_color_palette[ $this_color_index ] );
			} else {
				$bricks_color_palette[ $this_color_index ]['colors'] = array_values( $bricks_color_palette[ $this_color_index ]['colors'] ); // fix holes.
			}
		}
		// STEP: remove the global palette if empty.
		if ( empty( $bricks_color_palette[ $global_palette_index ]['colors'] ) ) {
			unset( $bricks_color_palette[ $global_palette_index ] );
		} else {
			$bricks_color_palette[ $global_palette_index ]['colors'] = array_values( $bricks_color_palette[ $global_palette_index ]['colors'] ); // fix holes.
		}
		// STEP: update the option.
		update_option( 'bricks_color_palette', array_values( $bricks_color_palette ), false ); // array_values to fix holes in the array.
		Logger::log( sprintf( '%s: Bricks color palette updated', __METHOD__ ) );
		Logger::log( sprintf( '%s: done', __METHOD__ ) );
	}

	/**
	 * Check if the plugin is installed and activated.
	 *
	 * @return boolean
	 */
	public static function is_active() {
		// I checked with class_exists( 'CT_Component' ), but it doesn't work here.
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$theme = wp_get_theme(); // gets the current theme.
		return 'Bricks' === $theme->name || 'Bricks' === $theme->parent_theme;
	}

	/**
	 * Are we in Bricks's builder context?
	 * That means we're in the builder, but not in the preview's iframe.
	 *
	 * @return bool
	 */
	public function in_builder_context() {
		if ( ! isset( $this->is_builder ) ) {
			$this->is_builder = 'run' === filter_input( INPUT_GET, 'bricks' );
		}
		return $this->is_builder && ! $this->in_iframe_context();
	}

	/**
	 * Are we in Bricks's iframe context?
	 * That means we're in NOT in the builder, just in the preview's iframe.
	 *
	 * @return bool
	 */
	public function in_iframe_context() {
		if ( ! isset( $this->in_iframe ) ) {
			$this->is_iframe = null !== filter_input( INPUT_GET, 'brickspreview' );
		}
		return $this->is_iframe;
	}

}
