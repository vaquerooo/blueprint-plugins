<?php
/*
Plugin Name: OxyExtras
Description: Component Library for Oxygen.
Version: 1.1.7
Author: OxyExtras
Author URI: https://oxyextras.com
*/


if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Oxy_Extras_Plugin_Updater' ) ) {
	// load our custom updater.
	include dirname( __FILE__ ) . '/includes/oxy-extras-updater.php';
}

require_once 'includes/oxy-extras-license.php';


class OxyExtrasPlugin {

	const PREFIX    = 'oxy_extras_';
	const TITLE     = 'OxyExtras';
	const VERSION   = '1.1.7';
	const STORE_URL = 'https://oxyextras.com';
	const ITEM_ID   = 240;

	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'oxy_extras_init' ) );
		OxyExtrasLicense::init( self::PREFIX, self::TITLE, self::STORE_URL, self::ITEM_ID );

		add_action( 'activate_' . plugin_basename( __FILE__ ), array( __CLASS__, 'activate' ), 10, 2 );
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ), 11 );
		add_action( 'admin_init', array( __CLASS__, 'plugin_updater' ), 0 );

		add_filter( 'plugin_action_links_' . basename( __DIR__ ) . '/' . basename( __FILE__ ), array( __CLASS__, 'settings_link' ) );
	}



	public static function activate( $plugin ) {
		if ( ! defined( 'CT_FW_PATH' ) ) {
			die( '<p>\'Oxygen builder\' must be installed and activated, in order to activate \'' . self::TITLE . '\'</p>' );
		}
	}

	public static function admin_menu() {

		global $menu;
		$menu_exists = false;

		foreach ( $menu as $item ) {
			if ( array_search( 'ct_dashboard_page', $item ) !== false ) {
				$menu_exists = true;
				break;
			}
		}

		if ( $menu_exists === false ) {
			add_menu_page( self::TITLE, self::TITLE, 'manage_options', self::PREFIX . 'menu', array( __CLASS__, 'menu_item' ) );
		} else {
			add_submenu_page( 'ct_dashboard_page', self::TITLE, self::TITLE, 'manage_options', self::PREFIX . 'menu', array( __CLASS__, 'menu_item' ) );
		}
	}

	public static function menu_item() {
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : false;
		?>
	<div class="wrap">
		<h2 class="nav-tab-wrapper">
		<a href="?page=<?php echo self::PREFIX . 'menu'; ?>&amp;tab=settings" class="nav-tab<?php echo ( $tab === false || $tab == 'settings' ) ? ' nav-tab-active' : ''; ?>">Settings</a>
		<a href="?page=<?php echo self::PREFIX . 'menu'; ?>&amp;tab=license" class="nav-tab<?php echo $tab == 'license' ? ' nav-tab-active' : ''; ?>">License</a>
		</h2>

		<?php
		if ( $tab === 'license' ) {
			OxyExtrasLicense::license_page();
		} else {
			self::settings_page();
		}
		?>
	</div>
		<?php
	}

	public static function settings_page() {
		?>
	<h2><?php echo self::TITLE . ' ' . __( 'General Settings' ); ?></h2>
	<div class="form-plugin-links">
	  <form method="post" action="options.php">

		<?php settings_fields( self::PREFIX . 'settings' ); ?>
		<table class="wp-list-table widefat plugins">
		  <thead>
		  <tr>
			<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Activate All</label><input id="cb-select-all-1" type="checkbox"></td><th scope="col" id="name" class="manage-column column-name column-primary">Enable All</th><td></td></tr>
		  </thead>
		  <tbody>
			<?php do_action( self::PREFIX . 'form_options' ); ?>
		  </tbody>
		  <thead>
		  <tr>
			<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Activate All</label><input id="cb-select-all-1" type="checkbox"></td><th scope="col" id="name" class="manage-column column-name column-primary">Enable All</th><td></td></tr>
		  </thead>
		</table>
		
		<?php submit_button(); ?>
	  </form>
	</div>
		<?php
	}


	public static function oxy_extras_init() {

		// check if Oxygen installed & active.
		if ( ! class_exists( 'OxygenElement' ) ) {
			return;
		}

		if ( version_compare( CT_VERSION, '3.2', '<' ) || version_compare( get_bloginfo( 'version' ), '4.7', '<' ) ) {

			add_action( 'admin_notices', array( __CLASS__, 'oxy_extras_admin_versions_notice' ) );
			return;

		}

		require_once 'OxyExtrasEl.php';
		require_once 'OxyExtras.php';

		$OxyExtras = new OxyExtras( self::PREFIX );

	}

	function oxy_extras_admin_versions_notice() {
		?>

		  <div class="notice notice-warning">
			  <p><?php _e( 'OxyExtras needs Oxygen Builder 3.2+ and WordPress 4.7+ to work.', 'oxyextras' ); ?></p>
		  </div>
		<?php
	}

	public static function plugin_updater() {
		// retrieve our license key from the DB.
		$license_key = trim( get_option( self::PREFIX . 'license_key' ) );

		// setup the updater.
		$edd_updater = new Oxy_Extras_Plugin_Updater(
			self::STORE_URL,
			__FILE__,
			array(
				'version'   => self::VERSION, // current version number
				'license'   => $license_key, // license key (used get_option above to retrieve from DB)
				'item_id'   => self::ITEM_ID, // ID of the product
				'item_name' => self::TITLE,
				'author'    => 'OxyExtras', // author of this plugin
				'url'       => home_url(),
				'beta'      => false,
			)
		);
	}

	public static function settings_link( $links ) {
		$url = esc_url(
			add_query_arg(
				'page',
				self::PREFIX . 'menu',
				get_admin_url() . 'admin.php'
			)
		);

		// Create the link.
		$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';

		// Adds the link to the beginning of the array.
		array_unshift(
			$links,
			$settings_link
		);

		return $links;
	}

}

OxyExtrasPlugin::init();
