<?php

if ( class_exists( 'OxyExtras' ) ) {
	return;
}

class OxyExtras {

	public $modules = array();
	public $prefix;
	function __construct( $prefix ) {

		if ( true !== OxyExtrasLicense::is_activated_license() ) {
			return;
		}
		$this->prefix = $prefix;
		$this->set_files();

		add_action( 'admin_init', array( $this, 'register_options' ) );
		add_action( $this->prefix . 'form_options', array( $this, 'options_form' ) );

		$this->load_files();

		// Iframe Scripts
		add_action( 'oxygen_enqueue_iframe_scripts', array( $this, 'iframe_scripts' ) );

		// Register section.
		add_action( 'oxygen_add_plus_sections', array( $this, 'register_add_plus_section' ) );

		// Register Sub Section.
		add_action( 'oxygen_add_plus_extras_section_content', array( $this, 'register_add_plus_subsections' ) );

	}

	function register_add_plus_section() {

		// Register 'Extras' Accordian Section.
		CT_Toolbar::oxygen_add_plus_accordion_section( 'extras', __( 'Extras' ) );

	}

	function register_add_plus_subsections() {

		ob_start();
		do_action( 'oxygen_add_plus_extras_interactive' );
		$result = ob_get_clean();
		if ( ! empty( trim( $result ) ) ) {
			?>
			<h2><?php _e( 'Interactive', 'oxygen' ); ?></h2>
			<?php
			echo $result;
		}

		ob_start();
		do_action( 'oxygen_add_plus_extras_single' );
		$result = ob_get_clean();
		if ( ! empty( trim( $result ) ) ) {
			?>
			<h2><?php _e( 'Single Posts', 'oxygen' ); ?></h2>
			<?php
			echo $result;
		}

		ob_start();
		do_action( 'oxygen_add_plus_extras_other' );
		$result = ob_get_clean();
		if ( ! empty( trim( $result ) ) ) {
			?>
			<h2><?php _e( 'Other', 'oxygen' ); ?></h2>
			<?php
			echo $result;
		}

		ob_start();
		do_action( 'oxygen_add_plus_extras_dynamic' );
		$result = ob_get_clean();
		if ( ! empty( trim( $result ) ) ) {
			?>
			<h2><?php _e( 'Dynamic Text', 'oxygen' ); ?></h2>
			<?php
			echo $result;
		}

		ob_start();
		do_action( 'oxygen_add_plus_extras_wordpress' );
		$result = ob_get_clean();
		if ( ! empty( trim( $result ) ) ) {
			?>
			<h2><?php _e( 'WordPress', 'oxygen' ); ?></h2>
			<?php
			echo $result;
		}

		ob_start();
		do_action( 'oxygen_add_plus_extras_woo' );
		$result = ob_get_clean();
		if ( ! empty( trim( $result ) ) ) {
			?>
			<h2><?php _e( 'Woocommerce', 'oxygen' ); ?></h2>
			<?php
			echo $result;
		}

	}

	function register_options() {
		foreach ( $this->modules as $key => $module ) {
			add_option( $this->prefix . $key, 0 );
			register_setting( $this->prefix . 'settings', $this->prefix . $key, array( $this, 'sanitize_enable' ) );
		}

	}

	function sanitize_enable( $enable ) {

		if ( is_numeric( $enable ) && intval( $enable ) === 1 ) {
			return 1;
		}

		return 0; // default
	}

	function options_form() {
		foreach ( $this->modules as $key => $module ) {
			?>

		
			<tr valign="top"<?php echo get_option( $this->prefix . $key ) === '1' ? ' class="active"' : ' class="inactive"'; ?>>
				<th class="check-column">
					<input id="<?php echo $this->prefix . $key; ?>" name="<?php echo $this->prefix . $key; ?>" type="checkbox" value="1" <?php checked( get_option( $this->prefix . $key ), 1 ); ?> />
				</th>
				<td class="plugin-title column-primary">
					<?php echo '<strong>' . $module['title'] . '</strong>'; ?>
				</td>
				<th class="doc-link-th"></th>
			</tr>
			

			<?php
		}

	}

	function set_files() {
		$this->modules = array(
			'slide_menu'               => array(
				'title' => 'Slide Menu',
				'file'  => 'components/slide-menu.php',
			),
			'read_more'                => array(
				'title' => 'Read More',
				'file'  => 'components/read-more.php',
			),
			'reading_progress_bar'     => array(
				'title' => 'Reading Progress Bar',
				'file'  => 'components/reading-progress-bar.php',
			),
			'reading_time'             => array(
				'title' => 'Reading Time',
				'file'  => 'components/reading-time.php',
			),
			'alert_box'                => array(
				'title' => 'Alert Box',
				'file'  => 'components/alert-box.php',
			),
			'copyright_text'           => array(
				'title' => 'Copyright Text',
				'file'  => 'components/copyright-text.php',
			),
			'author_box'               => array(
				'title' => 'Author Box',
				'file'  => 'components/author-box.php',
			),
			'back_to_top'              => array(
				'title' => 'Back To Top',
				'file'  => 'components/back-to-top.php',
			),
			'gutenberg_reusable_block' => array(
				'title' => 'Gutenberg Reusable Block',
				'file'  => 'components/gutenberg-reusable-block.php',
			),
			'counter'                  => array(
				'title' => 'Counter',
				'file'  => 'components/counter.php',
			),
			'social_share_buttons'     => array(
				'title' => 'Social Share Buttons',
				'file'  => 'components/social-share-buttons.php',
			),
			'adjecent_posts'           => array(
				'title' => 'Adjacent Posts',
				'file'  => 'components/adjacent-posts.php',
			),
			'post_modified_date'       => array(
				'title' => 'Post Modified Date',
				'file'  => 'components/post-modified-date.php',
			),
			'pro_login'                => array(
				'title' => 'Pro Login',
				'file'  => 'components/pro-login.php',
			),
			'lottie'                   => array(
				'title' => 'Lottie',
				'file'  => 'components/lottie.php',
			),
			'fluent_form'              => array(
				'title' => 'Fluent Form',
				'file'  => 'components/fluent-form.php',
			),
			'post_terms'               => array(
				'title' => 'Post Terms',
				'file'  => 'components/post-terms.php',
			),
			'off_canvas_wrapper'       => array(
				'title' => 'Off Canvas',
				'file'  => 'components/off-canvas-wrapper.php',
			),
			'header_search'            => array(
				'title' => 'Header Search',
				'file'  => 'components/header-search.php',
			),
			'burger_trigger'           => array(
				'title' => 'Burger Trigger',
				'file'  => 'components/burger-trigger.php',
			),

			'preloader'                => array(
				'title' => 'Preloader',
				'file'  => 'components/preloader.php',
			),
			'minicart'                 => array(
				'title' => 'Mini Cart',
				'file'  => 'components/mini-cart.php',
			),
			'cartcount'                => array(
				'title' => 'Cart Count',
				'file'  => 'components/cart-count.php',
			),

			'toggle'                   => array(
				'title' => 'Toggle Switch',
				'file'  => 'components/toggle.php',
			),
			'content-switcher'         => array(
				'title' => 'Content Switcher',
				'file'  => 'components/content-switcher.php',
			),
       'carousel-builder'         => array(
				'title' => 'Carousel Builder',
				'file'  => 'components/carousel.php',
			),

		);
	}

	function load_files() {

		foreach ( $this->modules as $key => $module ) {
			// this will block the rest of the mod to load, if it is not checked.
			if ( 0 === intval( get_option( $this->prefix . $key, 0 ) ) ) {
				continue;
			}
			include_once $module['file'];
		}

	}

	function iframe_scripts() {
		wp_enqueue_script( 'extras-iframe', plugin_dir_url( __FILE__ ) . 'components/assets/iframe.js', array(), '1.0.0' );
	}


}
