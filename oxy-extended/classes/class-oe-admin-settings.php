<?php
namespace OxyExtended\Classes;

/**
 * Handles logic for the admin settings page.
 *
 * @since 1.0.0
 */
final class OE_Admin_Settings {
	/**
	 * Holds any errors that may arise from
	 * saving admin settings.
	 *
	 * @since 1.0.0
	 * @var array $errors
	 */
	public static $errors = array();

	public static $settings = array();

	/**
	 * Initializes the admin settings.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function init() {
		self::migrate_settings();

		add_action( 'plugins_loaded', __CLASS__ . '::init_hooks' );

		add_action( 'plugins_loaded', __CLASS__ . '::save_modules' );
	}

	/**
	 * Adds the admin menu and enqueues CSS/JS if we are on
	 * the plugin's admin settings page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function init_hooks() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'admin_menu', __CLASS__ . '::menu', 601 );
		add_filter( 'all_plugins', __CLASS__ . '::update_branding' );

		if ( isset( $_REQUEST['page'] ) && 'oe-settings' == $_REQUEST['page'] ) {
			//add_action( 'admin_enqueue_scripts', __CLASS__ . '::styles_scripts' );
			self::save();
			self::reset_settings();
		}
	}

	/**
	 * Enqueues the needed CSS/JS for the builder's admin settings page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function styles_scripts() {
		// Styles
		//wp_enqueue_style( 'pp-admin-settings', POWERPACK_ELEMENTS_URL . 'assets/css/admin-settings.css', array(), POWERPACK_ELEMENTS_VER );
	}

	/**
	 * Get settings.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function get_settings() {
		$default_settings = array(
			'plugin_name'          => '',
			'plugin_desc'          => '',
			'plugin_author'        => '',
			'plugin_uri'           => '',
			'admin_label'          => '',
			'support_link'         => '',
			'hide_support'         => 'off',
			'hide_wl_settings'     => 'off',
			'hide_integration_tab' => 'off',
			'hide_plugin'          => 'off',
		);

		$settings = get_option( 'oe_elements_settings' );

		if ( ! is_array( $settings ) || empty( $settings ) ) {
			$settings = $default_settings;
		}

		if ( is_array( $settings ) && ! empty( $settings ) ) {
			$settings = array_merge( $default_settings, $settings );
		}

		return apply_filters( 'oe_elements_admin_settings', $settings );
	}

	/**
	 * Get admin label from settings.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function get_admin_label() {
		$settings = self::get_settings();

		$admin_label = $settings['admin_label'];

		return trim( $admin_label ) == '' ? 'Oxy Extended' : trim( $admin_label );
	}

	/**
	 * Renders the update message.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function render_update_message() {
		if ( ! empty( self::$errors ) ) {
			foreach ( self::$errors as $message ) {
				echo '<div class="error"><p>' . $message . '</p></div>';
			}
		} elseif ( ! empty( $_POST ) && ! isset( $_POST['email'] ) ) {
			echo '<div class="updated"><p>' . esc_html__( 'Settings updated!', 'oxy-extended' ) . '</p></div>';
		}
	}

	/**
	 * Adds an error message to be rendered.
	 *
	 * @since 1.0.0
	 * @param string $message The error message to add.
	 * @return void
	 */
	public static function add_error( $message ) {
		self::$errors[] = $message;
	}

	/**
	 * Renders the admin settings menu.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function menu() {
		$admin_label = self::get_admin_label();

		$title = $admin_label;
		$cap   = 'manage_options';
		$slug  = 'oe-settings';
		$func  = __CLASS__ . '::render';

		add_submenu_page( 'ct_dashboard_page', $title, $title, $cap, $slug, $func );
	}

	public static function render() {
		include OXY_EXTENDED_DIR . 'includes/admin/admin-settings.php';
		//include OXY_EXTENDED_DIR . 'includes/modules-manager.php';
	}

	public static function get_tabs() {
		$settings = self::get_settings();

		return apply_filters( 'oe_elements_admin_settings_tabs', array(
			'general'   => array(
				'title'     => esc_html__( 'General', 'oxy-extended' ),
				'show'      => true,
				'cap'       => 'manage_options',
				'file'      => OXY_EXTENDED_DIR . 'includes/admin/admin-settings-license.php',
				'priority'  => 50,
			),
			'modules'   => array(
				'title'     => esc_html__( 'Elements', 'oxy-extended' ),
				'show'      => true,
				'cap'       => 'edit_posts',
				'file'      => OXY_EXTENDED_DIR . 'includes/admin/admin-settings-modules.php',
				'priority'  => 150,
			),
			'integration'   => array(
				'title'         => esc_html__( 'Integration', 'oxy-extended' ),
				'show'          => 'off' == $settings['hide_integration_tab'],
				'cap'           => ! is_network_admin() ? 'manage_options' : 'manage_network_plugins',
				'file'          => OXY_EXTENDED_DIR . 'includes/admin/admin-settings-integration.php',
				'priority'      => 300,
			),
		) );
	}

	public static function render_tabs( $current_tab ) {
		$tabs = self::get_tabs();
		$sorted_data = array();

		foreach ( $tabs as $key => $data ) {
			$data['key'] = $key;
			$sorted_data[ $data['priority'] ] = $data;
		}

		ksort( $sorted_data );

		foreach ( $sorted_data as $data ) {
			if ( $data['show'] ) {
				if ( isset( $data['cap'] ) && ! current_user_can( $data['cap'] ) ) {
					continue;
				}
				?>
				<a href="<?php echo self::get_form_action( '&tab=' . $data['key'] ); ?>" class="nav-tab<?php echo ( $current_tab == $data['key'] ? ' nav-tab-active' : '' ); ?>"><span><?php echo $data['title']; ?></span></a>
				<?php
			}
		}
	}

	public static function render_setting_page() {
		$tabs = self::get_tabs();
		$current_tab = self::get_current_tab();

		if ( isset( $tabs[ $current_tab ] ) ) {
			$no_setting_file_msg = esc_html__( 'Setting page file could not be located.', 'oxy-extended' );

			if ( ! isset( $tabs[ $current_tab ]['file'] ) || empty( $tabs[ $current_tab ]['file'] ) ) {
				echo $no_setting_file_msg;
				return;
			}

			if ( ! file_exists( $tabs[ $current_tab ]['file'] ) ) {
				echo $no_setting_file_msg;
				return;
			}

			$render = ! isset( $tabs[ $current_tab ]['show'] ) ? true : $tabs[ $current_tab ]['show'];
			$cap = 'manage_options';

			if ( isset( $tabs[ $current_tab ]['cap'] ) && ! empty( $tabs[ $current_tab ]['cap'] ) ) {
				$cap = $tabs[ $current_tab ]['cap'];
			} else {
				$cap = ! is_network_admin() ? 'manage_options' : 'manage_network_plugins';
			}

			if ( ! $render || ! current_user_can( $cap ) ) {
				esc_html_e( 'You do not have permission to view this setting.', 'oxy-extended' );
				return;
			}

			include $tabs[ $current_tab ]['file'];
		}
	}

	/**
	 * Get current tab.
	 */
	public static function get_current_tab() {
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';

		// if ( ! isset( $_GET['tab'] ) ) {
		// 	if ( is_multisite() && ! is_network_admin() ) {
		// 		$current_tab = 'modules';
		// 	}
		// }

		return $current_tab;
	}

	/**
	 * Renders the action for a form.
	 *
	 * @since 1.0.0
	 * @param string $type The type of form being rendered.
	 * @return void
	 */
	public static function get_form_action( $type = '' ) {
		if ( is_network_admin() ) {
			return network_admin_url( '/admin.php?page=oe-settings' . $type );
		} else {
			return admin_url( '/admin.php?page=oe-settings' . $type );
		}
	}

	static public function get_user_roles() {
		global $wp_roles;

		return $wp_roles->get_names();
	}

	/**
	 * Returns an option from the database for
	 * the admin settings page.
	 *
	 * @since 1.0.0
	 * @param string $key The option key.
	 * @return mixed
	 */
	public static function get_option( $key, $network_override = true ) {
		if ( is_network_admin() ) {
			$value = get_site_option( $key );
		} elseif ( ! $network_override && is_multisite() ) {
			$value = get_site_option( $key );
		} elseif ( $network_override && is_multisite() ) {
			$value = get_option( $key );
			$value = ( false === $value || ( is_array( $value ) && in_array( 'disabled', $value ) && get_option( 'oe_override_ms' ) != 1 ) ) ? get_site_option( $key ) : $value;
		} else {
			$value = get_option( $key );
		}

		return $value;
	}

	/**
	 * Updates an option from the admin settings page.
	 *
	 * @since 1.0.0
	 * @param string $key The option key.
	 * @param mixed $value The value to update.
	 * @return mixed
	 */
	public static function update_option( $key, $value, $network_override = true ) {
		if ( is_network_admin() ) {
			update_site_option( $key, $value );
		}
		// Delete the option if network overrides are allowed and the override checkbox isn't checked.
		elseif ( $network_override && is_multisite() && ! isset( $_POST['oe_override_ms'] ) ) {
			delete_option( $key );
		} else {
			update_option( $key, $value );
		}
	}

	/**
	 * Delete an option from the admin settings page.
	 *
	 * @since 1.0.0
	 * @param string $key The option key.
	 * @param mixed $value The value to delete.
	 * @return mixed
	 */
	public static function delete_option( $key ) {
		if ( is_network_admin() ) {
			delete_site_option( $key );
		} else {
			delete_option( $key );
		}
	}

	/**
	 * Set the branding data to plugin.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function update_branding( $all_plugins ) {
		if ( ! is_array( $all_plugins ) || empty( $all_plugins ) || ! isset( $all_plugins[ OXY_EXTENDED_BASE ] ) ) {
			return $all_plugins;
		}

		$settings = self::get_settings();

		$all_plugins[ OXY_EXTENDED_BASE ]['Name']           = ! empty( $settings['plugin_name'] ) ? $settings['plugin_name'] : $all_plugins[ OXY_EXTENDED_BASE ]['Name'];
		$all_plugins[ OXY_EXTENDED_BASE ]['PluginURI']      = ! empty( $settings['plugin_uri'] ) ? $settings['plugin_uri'] : $all_plugins[ OXY_EXTENDED_BASE ]['PluginURI'];
		$all_plugins[ OXY_EXTENDED_BASE ]['Description']    = ! empty( $settings['plugin_desc'] ) ? $settings['plugin_desc'] : $all_plugins[ OXY_EXTENDED_BASE ]['Description'];
		$all_plugins[ OXY_EXTENDED_BASE ]['Author']         = ! empty( $settings['plugin_author'] ) ? $settings['plugin_author'] : $all_plugins[ OXY_EXTENDED_BASE ]['Author'];
		$all_plugins[ OXY_EXTENDED_BASE ]['AuthorURI']      = ! empty( $settings['plugin_uri'] ) ? $settings['plugin_uri'] : $all_plugins[ OXY_EXTENDED_BASE ]['AuthorURI'];
		$all_plugins[ OXY_EXTENDED_BASE ]['Title']          = ! empty( $settings['plugin_name'] ) ? $settings['plugin_name'] : $all_plugins[ OXY_EXTENDED_BASE ]['Title'];
		$all_plugins[ OXY_EXTENDED_BASE ]['AuthorName']     = ! empty( $settings['plugin_author'] ) ? $settings['plugin_author'] : $all_plugins[ OXY_EXTENDED_BASE ]['AuthorName'];

		if ( $settings['hide_plugin'] == 'on' ) {
			unset( $all_plugins[ OXY_EXTENDED_BASE ] );
		}

		return $all_plugins;
	}

	public static function save() {
		// Only admins can save settings.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		self::save_license();
		self::save_modules();
		self::save_integration();

		do_action( 'oe_elements_admin_settings_save' );
	}

	/**
	 * Saves the license.
	 *
	 * @since 1.0.0
	 * @access private
	 * @return void
	 */
	private static function save_license() {
		if ( isset( $_POST['oe_license_key'] ) ) {

			$old = get_option( 'oe_license_key' );
			$new = $_POST['oe_license_key'];

			if ( $old && $old != $new ) {
				delete_option( 'oe_license_status' ); // new license has been entered, so must reactivate
			}

			update_option( 'oe_license_key', $new );
		}
	}

	/**
	 * Saves integrations.
	 *
	 * @since 1.0.0
	 * @access private
	 * @return void
	 */
	private static function save_integration() {
		if ( ! isset( $_POST['oe_license_deactivate'] ) && ! isset( $_POST['oe_license_activate'] ) ) {
			if ( isset( $_POST['oe_instagram_access_token'] ) ) {
				self::update_option( 'oe_instagram_access_token', trim( $_POST['oe_instagram_access_token'] ), false );
			}
		}
	}

	public static function save_modules() {
		if ( ! isset( $_POST['oe-modules-settings-nonce'] ) || ! wp_verify_nonce( $_POST['oe-modules-settings-nonce'], 'oe-modules-settings' ) ) {
			return;
		}

		if ( ! empty( $_POST['oe_enabled_modules'] ) ) {
			update_option( 'oe_elements_modules', $_POST['oe_enabled_modules'] );
		} else {
			update_option( 'oe_elements_modules', 'disabled' );
		}
	}

	public static function reset_settings() {
		if ( isset( $_GET['reset_modules'] ) ) {
			delete_option( 'oe_elements_modules' );
			self::$errors[] = __( 'Modules settings updated!', 'oxy-extended' );
		}
	}

	public static function migrate_settings() {
		if ( ! is_multisite() ) {
			return;
		}
		if ( 'yes' === get_option( 'oe_multisite_settings_migrated' ) ) {
			return;
		}

		$fields = array(
			'oe_license_status',
			'oe_license_key',
			'oe_elements_settings',
			'oe_elements_modules',
		);

		foreach ( $fields as $field ) {
			$value = get_site_option( $field );
			if ( $value ) {
				update_option( $field, $value );
			}
		}

		update_option( 'oe_multisite_settings_migrated', 'yes' );
	}
}

OE_Admin_Settings::init();
