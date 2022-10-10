<?php
namespace OxyExtended\Classes;

/**
 * Handles logic for the maintenance mode.
 *
 * @package Oxy_Extended
 * @since 1.0.0
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * OE_Maintenance_Mode.
 */
final class OE_Maintenance_Mode {
	/**
	 * Holds the value of setting field Template.
	 *
	 * @var $template
	 * @since 1.0.0
	 * @access protected
	 */
	static protected $template = '';

	/**
	 * Settings Tab constant.
	 */
	const SETTINGS_TAB = 'maintenance_mode';

	/**
	 * Initializing OxyExtended maintenance mode.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		add_filter( 'oe_elements_admin_settings_tabs', __CLASS__ . '::render_settings_tab', 10, 1 );
		add_action( 'oe_elements_admin_settings_save', __CLASS__ . '::save_settings' );

		self::$template = get_option( 'oe_maintenance_mode_template' );

		if ( ! self::is_enabled() ) {
			return;
		}

		add_action( 'admin_bar_menu', __CLASS__ . '::add_menu_in_admin_bar', 300 );
		add_action( 'admin_head', __CLASS__ . '::print_style' );

		add_action( 'init', __CLASS__ . '::pre_get_posts_init' );
		add_action( 'wp_head', __CLASS__ . '::print_style' );
		add_action( 'wp', __CLASS__ . '::setup_maintenance_mode' );
	}

	/**
	 * Is enabled.
	 *
	 * Check if maintenance mode is enabled or not.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean true or false
	 */
	public static function is_enabled() {
		return 'yes' === get_option( 'oe_maintenance_mode_enable', 'no' ) && ! empty( self::$template );
	}

	/**
	 * Body class.
	 *
	 * Add "Maintenance Mode" CSS classes to the body tag.
	 *
	 * Fired by `body_class` filter.
	 *
	 * @since 1.0.0
	 *
	 * @param array $classes An array of body classes.
	 *
	 * @return array An array of body classes.
	 */
	public static function body_class( $classes ) {
		$classes[] = 'oe-maintenance-mode';

		return $classes;
	}

	public static function pre_get_posts_init( $query ) {
		add_action( 'pre_get_posts', __CLASS__ . '::pre_get_posts' );
	}

	public static function pre_get_posts( $query ) {
		if ( is_admin() ) {
			return;
		}

		$access      = get_option( 'oe_maintenance_mode_access' );
		$access_type = get_option( 'oe_maintenance_mode_access_type' );
		$ips         = get_option( 'oe_maintenance_mode_ip_whitelist' );

		// Access type.
		if ( 'logged_in' === $access && is_user_logged_in() ) {
			return;
		}

		// User roles.
		if ( 'custom' === $access ) {
			$access_roles = get_option( 'oe_maintenance_mode_access_roles', array() );
			$user         = wp_get_current_user();
			$user_roles   = $user->roles;

			if ( is_multisite() && is_super_admin() ) {
				$user_roles[] = 'super_admin';
			}

			$compare_roles = array_intersect( $user_roles, $access_roles );

			if ( ! empty( $compare_roles ) ) {
				return;
			}
		}

		// Include/Exclude URLs.
		if ( 'entire_site' !== $access_type ) {
			$access_urls = get_option( 'oe_maintenance_mode_access_urls' );
			if ( ! empty( $access_urls ) ) {
				$matches = self::check_url( $access_urls );
				if ( 'exclude_urls' === $access_type && $matches ) {
					return;
				}
				if ( 'include_urls' === $access_type && ! $matches ) {
					return;
				}
			}
		}

		// IP whitelist.
		$ips = trim( $ips );
		if ( ! empty( $ips ) ) {
			$ips = explode( "\n", $ips );
			$current_ip = oe_get_client_ip();
			if ( in_array( $current_ip, $ips ) ) {
				return;
			}
		}

		if ( ! $query->is_main_query() ) {
			return;
		}

		if ( -1 === self::$template ) {
			return;
		}

		$query->set( 'page_id', self::$template );
		$query->is_singular = true;
		$query->is_page = true;
		$query->is_category = false;
		$query->is_archive = false;
	}

	/**
	 * Setup Maintenance Mode.
	 *
	 * Conditionally check and setup the maintenance mode.
	 *
	 * @since 1.0.0
	 */
	public static function setup_maintenance_mode() {
		$access      = get_option( 'oe_maintenance_mode_access' );
		$access_type = get_option( 'oe_maintenance_mode_access_type' );
		$ips         = get_option( 'oe_maintenance_mode_ip_whitelist' );

		// Access type.
		if ( 'logged_in' === $access && is_user_logged_in() ) {
			return;
		}

		// User roles.
		if ( 'custom' === $access ) {
			$access_roles = get_option( 'oe_maintenance_mode_access_roles', array() );
			$user         = wp_get_current_user();
			$user_roles   = $user->roles;

			if ( is_multisite() && is_super_admin() ) {
				$user_roles[] = 'super_admin';
			}

			$compare_roles = array_intersect( $user_roles, $access_roles );

			if ( ! empty( $compare_roles ) ) {
				return;
			}
		}

		// Include/Exclude URLs.
		if ( 'entire_site' !== $access_type ) {
			$access_urls = get_option( 'oe_maintenance_mode_access_urls' );
			if ( ! empty( $access_urls ) ) {
				$matches = self::check_url( $access_urls );
				if ( 'exclude_urls' === $access_type && $matches ) {
					return;
				}
				if ( 'include_urls' === $access_type && ! $matches ) {
					return;
				}
			}
		}

		// IP whitelist.
		$ips = trim( $ips );
		if ( ! empty( $ips ) ) {
			$ips = explode( "\n", $ips );
			$current_ip = oe_get_client_ip();
			if ( in_array( $current_ip, $ips ) ) {
				return;
			}
		}

		// Custom action to hook any configuration before render.
		do_action( 'oe_maintenance_mode_before_render' );

		// Priority = 11 that is *after* WP default filter `redirect_canonical` in order to avoid redirection loop.
		add_action( 'template_redirect', __CLASS__ . '::template_redirect', 11 );
	}

	/**
	 * Template redirect.
	 *
	 * Redirect to the "Maintenance Mode" template.
	 *
	 * Fired by `template_redirect` action.
	 *
	 * @since 1.0.0
	 */
	public static function template_redirect() {
		add_filter( 'body_class', __CLASS__ . '::body_class' );

		if ( 'maintenance' === get_option( 'oe_maintenance_mode_type' ) ) {
			$protocol = wp_get_server_protocol();
			header( "$protocol 503 Service Unavailable", true, 503 );
			header( 'Content-Type: text/html; charset=utf-8' );
			header( 'Retry-After: 600' );
		}

		/* // @codingStandardsIgnoreStart
		$GLOBALS['post'] = get_post( self::$template );

		// Set the template as `$wp_query->current_object` for `wp_title` and etc.
		query_posts( array(
			'p' => self::$template,
			'post_type' => 'page',
			'page_id' => self::$template,
		) );

		$GLOBALS['wp_query']->is_page = true;
		$GLOBALS['wp_query']->is_single = false;

		// WPML fix.
		if ( class_exists( 'sitepress' ) ) {
			$GLOBALS['wp_the_query'] = $GLOBALS['wp_query'];
		}
		// @codingStandardsIgnoreEnd */

		/* if ( ! is_admin() ) {
			global $post;

			if ( -1 === self::$template ) {
				return;
			};

			$maintenance_page_url = get_post_permalink( get_post( self::$template ) );
			if ( ! isset( $post ) || $post->ID !== self::$template ) {
				wp_redirect( $maintenance_page_url, 302, 'OxyExtended' );
				exit();
			}
		} */
	}

	/**
	 * Get templates.
	 *
	 * Get all layout templates and create options for select field.
	 *
	 * @since 1.0.0
	 * @param string $selected Selected template for the field.
	 */
	public static function get_templates( $selected = '' ) {
		$args = array(
			'post_type'              => 'page',
			'post_status'            => 'publish',
			'orderby'                => 'title',
			'order'                  => 'ASC',
			'posts_per_page'         => '-1',
			'update_post_meta_cache' => false,
		);

		$posts = get_posts( $args );

		$options = '<option value="">' . __( '-- Select --', 'oxy-extended' ) . '</option>';

		if ( count( $posts ) ) {
			foreach ( $posts as $post ) {
				$options .= '<option value="' . $post->ID . '" ' . selected( $selected, $post->ID, false ) . '>' . $post->post_title . '</option>';
			}
		} else {
			$options = '<option value="" disabled>' . __( 'No templates found!', 'oxy-extended' ) . '</option>';
		}

		return $options;
	}

	/**
	 * Add menu in admin bar.
	 *
	 * Adds "Maintenance Mode" items to the WordPress admin bar.
	 *
	 * Fired by `admin_bar_menu` filter.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Admin_Bar $wp_admin_bar WP_Admin_Bar instance, passed by reference.
	 */
	public static function add_menu_in_admin_bar( \WP_Admin_Bar $wp_admin_bar ) {
		if ( ! self::is_enabled() ) {
			return;
		}

		$wp_admin_bar->add_node( array(
			'id'    => 'oe-maintenance-on',
			'title' => __( 'Maintenance Mode ON', 'oxy-extended' ),
			'href'  => OE_Admin_Settings::get_form_action( '&tab=' . self::SETTINGS_TAB ),
		) );
	}

	/**
	 * Print style.
	 *
	 * Adds custom CSS to the HEAD html tag. The CSS that emphasise the maintenance
	 * mode with red colors.
	 *
	 * Fired by `admin_head` and `wp_head` filters.
	 *
	 * @since 1.0.0
	 */
	public static function print_style() {
		?>
		<style>#wp-admin-bar-oe-maintenance-on > a { background-color: #F44336; }
			#wp-admin-bar-oe-maintenance-on > .ab-item:before { content: "\f160"; top: 2px; }</style>
		<?php
	}

	/**
	 * Render settings tab.
	 *
	 * Adds Maintenance Mode tab in OxyExtended admin settings.
	 *
	 * @since 1.0.0
	 * @param array $tabs Array of existing settings tabs.
	 */
	public static function render_settings_tab( $tabs ) {
		$tabs[ self::SETTINGS_TAB ] = array(
			'title'    => esc_html__( 'Maintenance Mode', 'oxy-extended' ),
			'show'     => ! is_network_admin(),
			'cap'      => ! is_network_admin() ? 'manage_options' : 'manage_network_plugins',
			'file'     => OXY_EXTENDED_DIR . 'includes/admin/admin-settings-maintenance-mode.php',
			'priority' => 350,
		);

		return $tabs;
	}

	/**
	 * Save settings.
	 *
	 * Saves setting fields value in options.
	 *
	 * @since 1.0.0
	 */
	public static function save_settings() {
		if ( ! isset( $_POST['oe_maintenance_mode_enable'] ) ) {
			return;
		}

		$enable       = sanitize_text_field( $_POST['oe_maintenance_mode_enable'] );
		$type         = sanitize_text_field( $_POST['oe_maintenance_mode_type'] );
		$access       = sanitize_text_field( $_POST['oe_maintenance_mode_access'] );
		$access_type  = sanitize_text_field( $_POST['oe_maintenance_mode_access_type'] );
		$access_urls  = sanitize_textarea_field( $_POST['oe_maintenance_mode_access_urls'] );
		$ip_whitelist = sanitize_textarea_field( $_POST['oe_maintenance_mode_ip_whitelist'] );
		$template     = isset( $_POST['oe_maintenance_mode_template'] ) ? sanitize_text_field( $_POST['oe_maintenance_mode_template'] ) : '';
		$roles        = array();

		if ( isset( $_POST['oe_maintenance_mode_access_roles'] ) && ! empty( $_POST['oe_maintenance_mode_access_roles'] ) ) {
			foreach ( $_POST['oe_maintenance_mode_access_roles'] as $role ) {
				$roles[] = sanitize_text_field( $role );
			}
		}

		update_option( 'oe_maintenance_mode_enable', $enable );
		update_option( 'oe_maintenance_mode_type', $type );
		update_option( 'oe_maintenance_mode_template', $template );
		update_option( 'oe_maintenance_mode_access', $access );
		update_option( 'oe_maintenance_mode_access_roles', $roles );
		update_option( 'oe_maintenance_mode_access_type', $access_type );
		update_option( 'oe_maintenance_mode_access_urls', $access_urls );
		update_option( 'oe_maintenance_mode_ip_whitelist', $ip_whitelist );
	}

	public static function check_url( $urls ) {
		$urls = trim( $urls );

		if ( empty( $urls ) ) {
			return true;
		}

		if ( self::match_path( $urls ) ) {
			return true;
		}

		return false;
	}

	public static function match_path( $patterns ) {
		$patterns_safe = array();

		// Get the request URI from WP
		list( $url_request ) = explode( '?', $_SERVER['REQUEST_URI'] ); //$wp->request;
		$url_request = ltrim( trim( $url_request ), '/' );

		// Append the query string
		// if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
		// 	$url_request .= '?' . $_SERVER['QUERY_STRING'];
		// } else {
		// 	$url_request = trim( $url_request, '/' );
		// }
		$url_request = trim( $url_request, '/' );

		$rows = explode( "\n", $patterns );

		foreach ( $rows as $pattern ) {
			// Trim trailing, leading slashes and whitespace
			$pattern = trim( trim( $pattern ), '/' );

			// Escape regex chars
			$pattern = preg_quote( $pattern, '/' );

			// Enable wildcard checks
			$pattern = str_replace( '\*', '.*', $pattern );

			$patterns_safe[] = $pattern;
		}

		// Remove empty patterns
		$patterns_safe = array_filter( $patterns_safe );

		$regexps = sprintf( '/^(%s)$/i', implode( '|', $patterns_safe ) );

		return preg_match( $regexps, $url_request );
	}
}

// Initialize the class.
OE_Maintenance_Mode::init();
