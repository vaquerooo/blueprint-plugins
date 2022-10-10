<?php
/**
 * Oxy Extended Setup
 *
 * @return void
 */
function oxyextend_setup() {
	add_action( 'wp_enqueue_scripts', 'oxyextend_load_scripts', 999 );

	oxyextend_load_all_elements();
}
add_action( 'after_setup_theme', 'oxyextend_setup', 50 );

/**
 * Load Scripts
 *
 * @return void
 */
function oxyextend_load_scripts() {
	wp_enqueue_style(
		'oxyextended-style',
		OXY_EXTENDED_URL . 'assets/css/oxy-extended.css',
		array(),
		filemtime( OXY_EXTENDED_DIR . 'assets/css/oxy-extended.css' ),
		'all'
	);

	wp_register_script(
		'oe-jquery-plugin',
		OXY_EXTENDED_URL . 'assets/lib/js-inheritance/jquery.plugin.js',
		array(),
		OXY_EXTENDED_VER,
		true
	);

	wp_register_script(
		'oe-frontend-countdown',
		OXY_EXTENDED_URL . 'assets/lib/countdown/jquery.countdown.js',
		array(),
		'2.0.2',
		true
	);

	wp_register_script(
		'oe-jquery-cookie',
		OXY_EXTENDED_URL . 'assets/lib/jquery-cookie/jquery.cookie.js',
		array(), '1.4.1',
		true
	);

	wp_register_script(
		'oe-countdown',
		OXY_EXTENDED_URL . 'assets/js/oe-countdown.js',
		array(),
		OXY_EXTENDED_VER,
		true
	);

	if ( isset($_GET['ct_builder']) ) {
		wp_enqueue_script(
			'oe-ctbuilder-scripts',
			OXY_EXTENDED_URL . 'assets/js/oe-ct-builder.js',
			array(),
			filemtime( OXY_EXTENDED_DIR . 'assets/js/oe-ct-builder.js' ),
			true
		);
	}

	wp_register_style(
		'oe-mfp-style',
		OXY_EXTENDED_URL . 'assets/css/mfp.css',
		array(),
		'1.1.0',
		'all'
	);

	wp_register_script(
		'oe-mfp-script',
		OXY_EXTENDED_URL . 'assets/js/jquery.magnificpopup.js',
		array(),
		'1.1.0',
		true
	);

	wp_register_script(
		'oegs-slider-script',
		OXY_EXTENDED_URL . 'assets/js/oe-gallery-slider.js',
		array( 'jquery' ),
		OXY_EXTENDED_VER,
		true
	);

	wp_register_script(
		'twentytwenty-js',
		OXY_EXTENDED_URL . 'assets/lib/twentytwenty/jquery.twentytwenty.js',
		array( 'jquery' ),
		OXY_EXTENDED_VER,
		true
	);

	wp_register_script(
		'jquery-event-move',
		OXY_EXTENDED_URL . 'assets/lib/event-move/jquery.event.move.js',
		array( 'jquery' ),
		'2.0.0',
		true
	);

	wp_register_script(
		'oe-ic-js',
		OXY_EXTENDED_URL . 'assets/js/oeic.js',
		array( 'jquery' ),
		OXY_EXTENDED_VER,
		true
	);

	wp_register_script(
		'oe-popper-script',
		OXY_EXTENDED_URL . 'assets/js/popper.min.js', array(),
		'2.4.4',
		true
	);

	wp_register_script(
		'oe-tippy-script',
		OXY_EXTENDED_URL . 'assets/js/tippy-bundle.umd.min.js',
		array(),
		OXY_EXTENDED_VER,
		true
	);

	wp_register_style(
		'oe-fancybox',
		OXY_EXTENDED_URL . 'assets/lib/fancybox/jquery.fancybox.min.css',
		array(),
		'3.5.2',
		'all'
	);

	wp_register_script(
		'oe-fancybox',
		OXY_EXTENDED_URL . 'assets/lib/fancybox/jquery.fancybox.min.js',
		array( 'jquery' ),
		'3.5.2',
		true
	);

	wp_register_script(
		'tilt',
		OXY_EXTENDED_URL . 'assets/lib/tilt/tilt.jquery.min.js',
		array( 'jquery' ),
		OXY_EXTENDED_VER,
		true
	);

	wp_register_script(
		'isotope',
		OXY_EXTENDED_URL . 'assets/lib/isotope/isotope.pkgd.min.js',
		array( 'jquery' ),
		'3.0.6',
		true
	);

	wp_register_script(
		'justified-gallery',
		OXY_EXTENDED_URL . 'assets/lib/justified-gallery/jquery.justifiedGallery.min.js',
		array( 'jquery' ),
		'3.7.0',
		true
	);

	wp_register_script(
		'oev-frontend-script',
		OXY_EXTENDED_URL . 'assets/js/oev.js',
		array(),
		OXY_EXTENDED_VER,
		true
	);

	wp_register_style(
		'oe-swiper-style',
		OXY_EXTENDED_URL . 'assets/lib/swiper/swiper.min.css',
		array(),
		'6.2.0',
		'all'
	);

	wp_register_script(
		'oe-swiper-script',
		OXY_EXTENDED_URL . 'assets/lib/swiper/swiper.jquery.min.js',
		array( 'jquery' ),
		'6.2.0',
		true
	);

	if ( isset( $_GET['ct_builder'] ) ) {
		wp_enqueue_style(
			'odometer',
			OXY_EXTENDED_URL . 'assets/lib/odometer/odometer-theme-default.css',
			array(),
			'0.4.8',
			'all'
		);

		wp_register_script(
			'waypoints',
			OXY_EXTENDED_URL . 'assets/lib/waypoints/waypoints.min.js',
			array(
				'jquery',
			),
			'4.0.1',
			true
		);

		wp_register_script(
			'odometer',
			OXY_EXTENDED_URL . 'assets/lib/odometer/odometer.min.js',
			array(
				'jquery',
			),
			'0.4.8',
			true
		);

		if ( isset($_GET['ct_builder']) ) {
			wp_enqueue_script(
				'oe-ctbuilder-scripts',
				OXY_EXTENDED_URL . 'assets/js/oe-ct-builder.js',
				array(),
				filemtime( OXY_EXTENDED_DIR . 'assets/js/oe-ct-builder.js' ),
				true
			);
		}
	}
}

/**
 * Loading the elements
 */
function oxyextend_load_all_elements() {

	$deactivated_components = oe_get_enabled_modules();

	if ( 'disabled' === $deactivated_components ) {
		return;
	}

	$paths        = glob( OXY_EXTENDED_DIR . 'elements/*' );
	$element_path = '';

	// Make sure we have an array.
	if ( ! is_array( $paths ) ) {
		return;
	}

	// Load all found modules.
	foreach ( $paths as $path ) {

		// Make sure we have a directory.
		if ( ! is_dir( $path ) ) {
			continue;
		}

		// Get the module slug.
		$slug = basename( $path );

		if( ! in_array( $slug, $deactivated_components ) )
			continue;
		// Paths to check.
		$element_path = OXY_EXTENDED_DIR . 'elements/' . $slug . '/' . $slug . '.php';

		if ( file_exists( $element_path ) ) {
			include_once $element_path;
		}

		if ( 'oe-hotspots' === $slug ) {
			include_once OXY_EXTENDED_DIR . 'elements/oe-hotspots/marker.php';
		}
	}
}

/**
 * Register Primary Section
 *
 * @return void
 */
function oxyextend_register_add_plus_section() {
	CT_Toolbar::oxygen_add_plus_accordion_section( 'oxyextended', __( 'Oxy Extended', 'oxy-extended' ) );
}
add_action( 'oxygen_add_plus_sections', 'oxyextend_register_add_plus_section' );

/**
 * Register General Subsection
 *
 * @return void
 */
function oxyextend_register_add_general_subsections() {
	printf( '<h2>%s</h2>', __( 'General', 'oxy-extended' ) );
	do_action( 'oxygen_add_plus_oxyextended_general' );
}
add_action( 'oxygen_add_plus_oxyextended_section_content', 'oxyextend_register_add_general_subsections' );

/**
 * Register Forms Subsection
 *
 * @return void
 */
function oxyextend_register_add_forms_subsections() {
	printf( '<h2>%s</h2>', __( 'Forms', 'oxy-extended' ) );
	do_action( 'oxygen_add_plus_oxyextended_forms' );
}
add_action( 'oxygen_add_plus_oxyextended_section_content', 'oxyextend_register_add_forms_subsections' );

function oxyextend_filter_global_settings_defaults( $defaults ) {
	$defaults['oxyextend'] = array();

	return $defaults;
}

function oxyextend_vsb_dynamic_shortcode( $atts, $content = null ) {
	// validation will go here
	global $oxygen_VSB_Dynamic_Shortcodes;

	// replace single quotes in atts
	foreach ( $atts as $key => $item ) {
		$atts[ $key ] = str_replace( '__SINGLE_QUOTE__', "'", $item );
	}

	global $wp_query;
	global $oxy_vsb_use_query;

	if ( isset( $oxy_vsb_use_query ) && is_object( $oxy_vsb_use_query ) ) {

		$query = $oxy_vsb_use_query;

	} else {

		global $oxygen_preview_post_id;

		if ( isset( $oxygen_preview_post_id ) && is_numeric( $oxygen_preview_post_id ) ) {
			$query_vars = array(
				'p' => $oxygen_preview_post_id,
				'post_type' => 'any',
			);
		} else {
			$query_vars = $wp_query->query_vars;
		}

		$query = new WP_Query( $query_vars );

		if ( ! is_page() ) {
			$query->the_post();
		}
	}

	$handler = 'oxygen_' . $atts['data'];

	if ( substr( $atts['data'], 0, 7 ) == 'custom_' ) {
		$handler = 'oxygen_custom';
	}

	if ( method_exists( $oxygen_VSB_Dynamic_Shortcodes, $handler ) ) {

		$output = call_user_func( array( $oxygen_VSB_Dynamic_Shortcodes, $handler ), $atts );

	} else {

		return 'No such function ' . $handler;

	}

	if ( isset( $atts['link'] ) ) {
		$link_handler = 'oxygen_' . $atts['link'];

		if ( isset( $link_handler ) && method_exists( $oxygen_VSB_Dynamic_Shortcodes, $link_handler ) ) {
			$link_output = call_user_func( array( $oxygen_VSB_Dynamic_Shortcodes, $link_handler ), $atts );

			if ( $link_output ) {
				$output = "<a href='" . $link_output . "'>" . $output . '</a>';
			}
		}
	}

	$output = apply_filters( 'oxygen_vsb_after_oxy_shortcode_render', $output );

	if ( ! isset( $oxy_vsb_use_query ) || ! is_object( $oxy_vsb_use_query ) ) {
		wp_reset_query();
	}

	return $output;
}
add_shortcode( 'oxyextended', 'oxyextend_vsb_dynamic_shortcode' );

function oxyextend_gsss( $el, $content ) {
	global $oxygen_signature;
	$signature = $oxygen_signature->generate_signature_shortcode_string( $el->get_tag() );
	$shortcode = str_replace( '[oxyextended', '[oxyextended ' . $signature, $content );

	return $shortcode;
}
function oe_get_modules() {
	$modules = array(
		'oe-album'            => __( 'Album', 'oxy-extended' ),
		'oe-counter'          => __( 'Counter', 'oxy-extended' ),
		'oe-countdown'        => __( 'Countdown', 'oxy-extended' ),
		'oe-divider'          => __( 'Divider', 'oxy-extended' ),
		'oe-dual-heading'     => __( 'Dual Heading', 'oxy-extended' ),
		'oe-fancy-heading'    => __( 'Fancy Heading', 'oxy-extended' ),
		'oe-flip-box'         => __( 'Flip Box', 'oxy-extended' ),
		'oe-gallery-slider'   => __( 'Gallery Slider', 'oxy-extended' ),
		'oe-hotspots'         => __( 'Hotspots', 'oxy-extended' ),
		'oe-image-comparison' => __( 'Image Comparison', 'oxy-extended' ),
		'oe-image-gallery'    => __( 'Image Gallery', 'oxy-extended' ),
		'oe-read-more'        => __( 'Read More', 'oxy-extended' ),
		'oe-instagram-feed'   => __( 'Instagram Feed', 'oxy-extended' ),
		'oe-random-image'     => __( 'Random Image', 'oxy-extended' ),
		'oe-scroll-image'     => __( 'Scroll Image', 'oxy-extended' ),
		'oe-video'            => __( 'Video', 'oxy-extended' ),
	);

	// Caldera Forms
	if ( class_exists( 'Caldera_Forms' ) ) {
		$modules['oe-caldera-forms-styler'] = __( 'Caldera Forms Styler', 'oxy-extended' );
	}

	// Contact Form 7
	if ( function_exists( 'wpcf7' ) ) {
		$modules['oe-cf7-styler'] = __( 'Contact Form 7 Styler', 'oxy-extended' );
	}

	// Fluent Forms
	if ( function_exists( 'wpFluentForm' ) ) {
		$modules['oe-fluent-forms-styler'] = __( 'Fluent Forms Styler', 'oxy-extended' );
	}

	// Fluent Forms
	if ( class_exists( 'FrmForm' ) ) {
		$modules['oe-formidable-forms-styler'] = __( 'Formidable Forms Styler', 'oxy-extended' );
	}

	// Gravity Forms
	if ( class_exists( 'GFForms' ) ) {
		$modules['oe-gravity-forms-styler'] = __( 'Gravity Forms Styler', 'oxy-extended' );
	}

	// WP Forms
	if ( class_exists( 'WPForms_Pro' ) || class_exists( 'WPForms_Lite' ) ) {
		$modules['oe-wpforms-styler'] = __( 'WP Forms Styler', 'oxy-extended' );
	}

	ksort( $modules );

	return $modules;
}

function oe_get_enabled_modules() {
	$enabled_modules = get_option( 'oe_elements_modules', 'disabled' );

	if ( is_array( $enabled_modules ) ) {
		return $enabled_modules;
	}

	if ( 'disabled' == $enabled_modules ) {
		return $enabled_modules;
	}

	return oe_get_modules();
}

function oe_get_client_ip() {
	$keys = array(
		'HTTP_CLIENT_IP',
		'HTTP_X_FORWARDED_FOR',
		'HTTP_X_FORWARDED',
		'HTTP_X_CLUSTER_CLIENT_IP',
		'HTTP_FORWARDED_FOR',
		'HTTP_FORWARDED',
		'REMOTE_ADDR',
	);

	foreach ( $keys as $key ) {
		if ( isset( $_SERVER[ $key ] ) && filter_var( $_SERVER[ $key ], FILTER_VALIDATE_IP ) ) {
			return $_SERVER[ $key ];
		}
	}

	// fallback IP address.
	return '127.0.0.1';
}