<?php
namespace Oxygen\OxyExtended;

/**
 * Instagram Feed
 */
class OEInstgramFeed extends \OxyExtendedEl {
	
	public $css_added = false;
	public $js_added = false;
	public $swiper_js_code = false;

	/**
	 * Instagram Access token.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	private $insta_access_token = null;

	/**
	 * Instagram API URL.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	private $insta_api_url = 'https://www.instagram.com/';

	/**
	 * Official Instagram API URL.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	private $insta_official_api_url = 'https://graph.instagram.com/';

	/**
	 * Retrieve Instagram Feed element name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'Instagram Feed', 'oxy-extended' );
	}

	/**
	 * Retrieve Instagram Feed element slug.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element slug.
	 */
	public function slug() {
		return 'oe_instagram_feed';
	}

	/**
	 * Element Subsection
	 *
	 * @return string
	 */
	public function oxyextend_button_place() {
		return 'general';
	}

	/**
	 * Retrieve Instagram Feed element icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element icon.
	 */
	public function icon() {
		return OXY_EXTENDED_URL . 'assets/images/elements/' . basename( __FILE__, '.php' ) . '.svg';
	}

	/**
	 * Element HTML tag
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return tag
	 */
	public function tag() {
		return 'div';
	}

	/**
	 * Element Controls
	 *
	 * Adds different controls to allow the user to change and customize the element settings.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function controls() {
		$insta_display = $this->addOptionControl(
			array(
				'type'      => 'dropdown',
				'name'      => __( 'Display', 'oxy-extended' ),
				'slug'      => 'oe_insta_display',
				'value'     => array(
					'feed'  => __( 'My Photos', 'oxy-extended' ),
					'tags'  => __( 'Tagged Photos', 'oxy-extended' ),
				),
				'default'   => 'feed',
				'css'       => false,
			)
		);
		$insta_display->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$insta_display->rebuildElementOnChange();

		$access_token = $this->addOptionControl(
			array(
				'type'          => 'textfield',
				'name'          => __( 'Custom Access Token', 'oxy-extended' ),
				'description'   => __( 'Overrides global Instagram access token', 'oxy-extended' ),
				'slug'          => 'oe_access_token',
				'css'           => false,
			)
		);
		$access_token->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_instagram_feed_oe_insta_display'] == 'feed'" );
		$access_token->rebuildElementOnChange();

		$insta_hashtag = $this->addOptionControl(
			array(
				'type'  => 'textfield',
				'name'  => __( 'Hashtag', 'oxy-extended' ),
				'slug'  => 'oe_insta_hashtag',
				'css'   => false,
			)
		);
		$insta_hashtag->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_instagram_feed_oe_insta_display'] == 'tags'" );
		$insta_hashtag->rebuildElementOnChange();

		$cache_timeout = $this->addOptionControl(
			array(
				'type'          => 'dropdown',
				'name'          => __( 'Cache Timeout', 'oxy-extended' ),
				'slug'          => 'oe_cache_timeout',
				'value'         => array(
					'none'      => esc_html__( 'None', 'oxy-extended' ),
					'minute'    => esc_html__( 'Minute', 'oxy-extended' ),
					'hour'      => esc_html__( 'Hour', 'oxy-extended' ),
					'day'       => esc_html__( 'Day', 'oxy-extended' ),
					'week'      => esc_html__( 'Week', 'oxy-extended' ),
				),
				'default'       => 'hour',
				'css'           => false,
			)
		);
		$cache_timeout->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_instagram_feed_oe_insta_display'] == 'feed'" );
		$cache_timeout->rebuildElementOnChange();

		$resolution = $this->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Image Resolution', 'oxy-extended' ),
				'slug'    => 'oe_resolution',
				'value'   => array(
					'thumbnail'           => __( 'Thumbnail (150x150)', 'oxy-extended' ),
					'low_resolution'      => __( 'Low Resolution (320x320)', 'oxy-extended' ),
					'standard_resolution' => __( 'Standard Resolution (640x640)', 'oxy-extended' ),
					'high'                => __( 'High Resolution (original)', 'oxy-extended' ),
				),
				'default' => 'low_resolution',
				'css'     => false,
			)
		);
		$resolution->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$resolution->rebuildElementOnChange();

		/**
		 * General Settings Section
		 * -------------------------------------------------
		 */
		$general_settings_section = $this->addControlSection( 'oe_if_general_settings', __( 'General Settings', 'oxy-extended' ), 'assets/icon.png', $this );
        
        $image_count = $general_settings_section->addOptionControl(
			array(
				'type'    => 'slider-measurebox',
				'name'    => __( 'Image Count', 'oxy-extended' ),
				'slug'    => 'oe_images_count',
			),
		);
        $image_count->setRange( '1', '20', '1' );
        $image_count->setValue( '5' );
		$image_count->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$image_count->rebuildElementOnChange();

		$feed_layout = $general_settings_section->addOptionControl(
			array(
				'type'          => 'dropdown',
				'name'          => __( 'Layout', 'oxy-extended' ),
				'slug'          => 'oe_feed_layout',
				'value'         => array(
					'grid'      => __( 'Grid', 'oxy-extended' ),
					'carousel'  => __( 'Carousel', 'oxy-extended' ),
				),
				'default'       => 'grid',
				'css'           => false,
			)
		);
		$feed_layout->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$feed_layout->rebuildElementOnChange();

		$feed_layout_grid_coulumn = $general_settings_section->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Layout', 'oxy-extended' ),
				'slug'    => 'oe_feed_layout_grid_coulumn',
				'value'   => array(
					'1'   => __( '1', 'oxy-extended' ),
					'2'   => __( '2', 'oxy-extended' ),
					'3'   => __( '3', 'oxy-extended' ),
					'4'   => __( '4', 'oxy-extended' ),
					'5'   => __( '5', 'oxy-extended' ),
					'6'   => __( '6', 'oxy-extended' ),
				),
				'default' => '4',
				'css'     => false,
				"condition" => 'oe_feed_layout=grid',
			)
		);
		$feed_layout_grid_coulumn->rebuildElementOnChange();        

		$square_images = $general_settings_section->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Square Images', 'oxy-extended' ),
				'slug'    => 'oe_square_images',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'yes',
				'css'     => false,
			)
		);
		$square_images->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_instagram_feed_oe_feed_layout'] == 'grid'" );
		$square_images->rebuildElementOnChange();

		$insta_caption = $general_settings_section->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Show Caption', 'oxy-extended' ),
				'slug'    => 'oe_insta_caption',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'no',
				'css'     => false,
			)
		);
		$insta_caption->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$insta_caption->rebuildElementOnChange();

		$insta_caption_length = $general_settings_section->addOptionControl(
			array(
				'type'  => 'textfield',
				'name'  => __( 'Caption Length', 'oxy-extended' ),
				'slug'  => 'oe_insta_caption_length',
				'css'   => false,
			)
		);
		$insta_caption_length->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_instagram_feed_oe_insta_caption'] == 'yes'" );
		$insta_caption_length->rebuildElementOnChange();
        
        /**
		 * Carousel Settings Section
		 * -------------------------------------------------
		 */
        $carousel_settings = $this->addControlSection( 'oe_if_carousel_settings', __( 'Carousel Settings', 'oxy-extended' ), 'assets/icon.png', $this );
        
        $carousel_settings->addOptionControl(
			array(
				'type'    => 'slider-measurebox',
				'name'    => __( 'Slides Per View', 'oxy-extended' ),
				'slug'    => 'slide_per_view',
			),
		)->setRange( '1', '10', '1' )->setValue( '3' )->rebuildElementOnChange();
        
        $carousel_settings->addOptionControl(
			array(
				'type'    => 'slider-measurebox',
				'name'    => __( 'Items Gap', 'oxy-extended' ),
				'slug'    => 'items_gap',
			),
		)->setRange( '1', '100', '1' )->setValue( '10' )->rebuildElementOnChange();
        
        $carousel_settings->addOptionControl(
			array(
				'type'    => 'slider-measurebox',
				'name'    => __( 'Slider Speed', 'oxy-extended' ),
				'slug'    => 'slider_speed',
			),
		)->setRange( '1', '2000', '1' )->setValue( '600' )->rebuildElementOnChange();
        
        $carousel_settings->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Autoplay', 'oxy-extended' ),
				'slug'    => 'autoplay',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'yes',
				'css'     => false,
			)
		)->rebuildElementOnChange();
        
        $carousel_settings->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Pause on Interaction', 'oxy-extended' ),
				'slug'    => 'pause_on_interaction',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'yes',
				'css'     => false,
			)
		)->rebuildElementOnChange();
        
        $carousel_settings->addOptionControl(
			array(
				'type'    => 'slider-measurebox',
				'name'    => __( 'Autoplay Speed', 'oxy-extended' ),
				'slug'    => 'autoplay_speed',
                'condition' => 'autoplay=yes',
			),
		)->setRange( '1', '8000', '1' )->setValue( '3000' )->rebuildElementOnChange();
        
        $carousel_settings->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Infinite Loop', 'oxy-extended' ),
				'slug'    => 'infinite_loop',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'yes',
				'css'     => false,
			)
		)->rebuildElementOnChange();
        
        $carousel_settings->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Grab Cursor', 'oxy-extended' ),
				'slug'    => 'grab_cursor',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'no',
				'css'     => false,
			)
		)->rebuildElementOnChange();
        
        $carousel_settings->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Navigation Arrows', 'oxy-extended' ),
				'slug'    => 'navigation_arrows',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'yes',
				'css'     => false,
			)
		)->rebuildElementOnChange();
        
        $carousel_settings->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Pagination', 'oxy-extended' ),
				'slug'    => 'pagination',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'yes',
				'css'     => false,
			)
		)->rebuildElementOnChange();
        
        /**
		 * Image Style Section
		 * -------------------------------------------------
		 */
        $images_style = $this->addControlSection( 'oe_if_images_style', __( 'Image Style', 'oxy-extended' ), 'assets/icon.png', $this );
        
        $images_style->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Grayscale Image', 'oxy-extended' ),
				'slug'    => 'insta_image_grayscale',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'no',
				'css'     => false,
			)
		)->rebuildElementOnChange();
        
        $images_style->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Hover Grayscale Image', 'oxy-extended' ),
				'slug'    => 'insta_image_grayscale_hover',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'no',
				'css'     => false,
			)
		)->rebuildElementOnChange();
        
        $images_style->borderSection( __( 'Border', 'oxy-extended' ), '.oe-instagram-feed .oe-if-img', $this );
        
        /**
		 * Pagination Arrows Style Section
		 * -------------------------------------------------
		 */
        $arrows_style = $this->addControlSection( 'oe_if_pagination_arrows', __( 'Pagination Arrows', 'oxy-extended' ), 'assets/icon.png', $this );
        
        $arrows_style->addPreset(
			'padding',
			'oe_arrows_padding',
			__( 'Padding', 'oxy-extended' ),
			'.oe-instagram-feed .oe-swiper-button'
		)->whiteList();
        
        $arrows_style->addStyleControl(
            array(
                'name'         => __( 'Size', 'oxy-extended' ),
                'selector'     => '.oe-instagram-feed .oe-swiper-button::after',
                'property'     => 'font-size',
                'control_type' => 'slider-measurebox',
            )
		)->setRange( '14', '100', '1' )->setUnits( 'px', 'px' )->rebuildElementOnChange();
        
        $arrows_style->addStyleControl(
            array(
                'name'         => __( 'Align Left Arrow', 'oxy-extended' ),
                'selector'     => '.oe-instagram-feed .swiper-button-prev',
                'property'     => 'left',
                'control_type' => 'slider-measurebox',
            )
		)->setRange( '-100', '100', '1' )->setUnits( 'px', 'px' )->rebuildElementOnChange();
        
        $arrows_style->addStyleControl(
            array(
                'name'         => __( 'Align right Arrow', 'oxy-extended' ),
                'selector'     => '.oe-instagram-feed .swiper-button-next',
                'property'     => 'right',
                'control_type' => 'slider-measurebox',
            )
		)->setRange( '-100', '100', '1' )->setUnits( 'px', 'px' )->rebuildElementOnChange();
        
        $arrows_style->addStyleControl(
            array(
                'name'         => __( 'Background Color', 'oxy-extended' ),
                'selector'     => '.oe-instagram-feed .oe-swiper-button',
                'property'     => 'background',
                'control_type' => 'colorpicker',
            ),
        );
        
        $arrows_style->addStyleControl(
            array(
                'name'         => __( 'Background Hover Color', 'oxy-extended' ),
                'selector'     => '.oe-instagram-feed .oe-swiper-button:hover',
                'property'     => 'background',
                'control_type' => 'colorpicker',
            ),
        );
        
        $arrows_style->addStyleControl(
            array(
                'name'         => __( 'Color', 'oxy-extended' ),
                'selector'     => '.oe-instagram-feed .oe-swiper-button',
                'property'     => 'color',
                'control_type' => 'colorpicker',
            ),
        );
        
        $arrows_style->addStyleControl(
            array(
                'name'         => __( 'Hover Color', 'oxy-extended' ),
                'selector'     => '.oe-instagram-feed .oe-swiper-button:hover',
                'property'     => 'color',
                'control_type' => 'colorpicker',
            ),
        );
        
        $arrows_style->borderSection( __( 'Border', 'oxy-extended' ), '.oe-instagram-feed .oe-swiper-button', $this );
        
        /**
		 * Dots Style Section
		 * -------------------------------------------------
		 */
        $dots_style = $this->addControlSection( 'oe_if_pagination_dots', __( 'Pagination Dots', 'oxy-extended' ), 'assets/icon.png', $this );
        
        $dots_style->addPreset(
			'margin',
			'oe_dots_margin',
			__( 'Margin', 'oxy-extended' ),
			'.oe-instagram-feed .swiper-pagination-bullets'
		)->whiteList();
        
        $dots_style->addStyleControl(
            array(
                'name'         => __( 'Size', 'oxy-extended' ),
                'selector'     => '.oe-instagram-feed .swiper-pagination-bullet',
                'property'     => 'height|width',
                'control_type' => 'slider-measurebox',
            )
		)->setRange( '2', '40', '1' )->setUnits( 'px', 'px' )->rebuildElementOnChange();
        
        $dots_style->addStyleControl(
            array(
                'name'         => __( 'Spacing', 'oxy-extended' ),
                'selector'     => '.oe-instagram-feed .swiper-pagination-bullet',
                'property'     => 'margin-right|margin-left',
                'control_type' => 'slider-measurebox',
            )
		)->setRange( '1', '30', '1' )->setUnits( 'px', 'px' )->rebuildElementOnChange();
        
        $dots_style->addStyleControl(
            array(
                'name'         => __( 'Color', 'oxy-extended' ),
                'selector'     => '.oe-instagram-feed .swiper-pagination-bullet',
                'property'     => 'background',
                'control_type' => 'colorpicker',
            ),
        );        
        
        $dots_style->addStyleControl(
            array(
                'name'         => __( 'Hover Color', 'oxy-extended' ),
                'selector'     => '.oe-instagram-feed .swiper-pagination-bullet:hover',
                'property'     => 'background',
                'control_type' => 'colorpicker',
            ),
        );
        
        $dots_style->addStyleControl(
            array(
                'name'         => __( 'Active Color', 'oxy-extended' ),
                'selector'     => '.oe-instagram-feed .swiper-pagination-bullet.swiper-pagination-bullet-active',
                'property'     => 'background',
                'control_type' => 'colorpicker',
            ),
        );
        
        $dots_style->borderSection( __( 'Border', 'oxy-extended' ), '.oe-instagram-feed .swiper-pagination-bullet', $this );
	}

	/**
	 * Get Instagram access token.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_insta_access_token( $options ) {
		if ( ! $this->insta_access_token ) {
			$custom_access_token = isset( $options['oe_access_token'] ) ? $options['oe_access_token'] : '';

			if ( '' !== trim( $custom_access_token ) ) {
				$this->insta_access_token = $custom_access_token;
			} else {
				$this->insta_access_token = $this->get_insta_global_access_token();
			}
		}

		return $this->insta_access_token;
	}

	/**
	 * Get Instagram access token from Oxy Extended options.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_insta_global_access_token() {
		return \OxyExtended\Classes\OE_Admin_Settings::get_option( 'oe_instagram_access_token' );
	}

	/**
	 * Retrieve a URL for own photos.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_feed_endpoint() {
		return $this->insta_official_api_url . 'me/media/';
	}

	/**
	 * Retrieve a URL for photos by hashtag.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_tags_endpoint() {
		return $this->insta_api_url . 'explore/tags/%s/';
	}

	public function get_user_endpoint() {
		return $this->insta_official_api_url . 'me/';
	}

	public function get_user_media_endpoint() {
		return $this->insta_official_api_url . '%s/media/';
	}

	public function get_media_endpoint() {
		return $this->insta_official_api_url . '%s/';
	}

	public function get_user_url() {
		$url = $this->get_user_endpoint();
		$url = add_query_arg( [
			'access_token' => $this->get_insta_access_token(),
			// 'fields' => 'media.limit(10){comments_count,like_count,likes,likes_count,media_url,permalink,caption}',
		], $url );

		return $url;
	}

	public function get_user_media_url( $user_id ) {
		$url = sprintf( $this->get_user_media_endpoint(), $user_id );
		$url = add_query_arg( [
			'access_token' => $this->get_insta_access_token(),
			'fields' => 'id,like_count',
		], $url );

		return $url;
	}

	public function get_media_url( $media_id ) {
		$url = sprintf( $this->get_media_endpoint(), $media_id );
		$url = add_query_arg( [
			'access_token' => $this->get_insta_access_token(),
			'fields' => 'id,media_type,media_url,timestamp,like_count',
		], $url );

		return $url;
	}

	public function get_insta_user_id() {
		$result = $this->get_insta_remote( $this->get_user_url() );
		return $result;
	}

	public function get_insta_user_media( $user_id ) {
		$result = $this->get_insta_remote( $this->get_user_media_url( $user_id ) );

		return $result;
	}

	public function get_insta_media( $media_id ) {
		$result = $this->get_insta_remote( $this->get_media_url( $media_id ) );

		return $result;
	}

	/**
	 * Retrieve a grab URL.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_fetch_url( $options ) {
		if ( 'tags' === $options['oe_insta_display'] ) {
			$url = sprintf( $this->get_tags_endpoint(), $options['insta_hashtag'] );
			$url = add_query_arg( array( '__a' => 1 ), $url );

		} elseif ( 'feed' === $options['oe_insta_display'] ) {
			$url = $this->get_feed_endpoint();
			$url = add_query_arg( [
				'fields'       => 'id,media_type,media_url,thumbnail_url,permalink,caption,likes_count,likes',
				'access_token' => $this->get_insta_access_token( $options ),
			], $url );
		}

		return $url;
	}

	/**
	 * Get thumbnail data from response data
	 *
	 * @param $post
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_insta_feed_thumbnail_data( $post ) {
		$thumbnail = array(
			'thumbnail' => false,
			'low'       => false,
			'standard'  => false,
			'high'      => false,
		);

		if ( ! empty( $post['images'] ) && is_array( $post['images'] ) ) {
			$data = $post['images'];

			$thumbnail['thumbnail'] = [
				'src'           => $data['thumbnail']['url'],
				'config_width'  => $data['thumbnail']['width'],
				'config_height' => $data['thumbnail']['height'],
			];

			$thumbnail['low'] = [
				'src'           => $data['low_resolution']['url'],
				'config_width'  => $data['low_resolution']['width'],
				'config_height' => $data['low_resolution']['height'],
			];

			$thumbnail['standard'] = [
				'src'           => $data['standard_resolution']['url'],
				'config_width'  => $data['standard_resolution']['width'],
				'config_height' => $data['standard_resolution']['height'],
			];

			$thumbnail['high'] = $thumbnail['standard'];
		}

		return $thumbnail;
	}

	/**
	 * Get data from response
	 *
	 * @param  $response
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function get_insta_feed_response_data( $response, $options ) {
		if ( ! array_key_exists( 'data', $response ) ) { // Avoid PHP notices
			return;
		}

		$response_posts = $response['data'];

		if ( empty( $response_posts ) ) {
			return array();
		}

		$return_data  = array();
		$images_count = ! empty( $options['oe_images_count'] ) ? $options['oe_images_count'] : 5;
		$posts = array_slice( $response_posts, 0, $images_count, true );

		foreach ( $posts as $post ) {
			$_post              = array();

			$_post['id']        = $post['id'];
			$_post['link']      = $post['permalink'];
			$_post['caption']   = '';
			$_post['image']     = 'VIDEO' === $post['media_type'] ? $post['thumbnail_url'] : $post['media_url'];
			$_post['comments']  = ! empty( $post['comments_count'] ) ? $post['comments_count'] : 0;
			$_post['likes']     = ! empty( $post['likes_count'] ) ? $post['likes_count'] : 0;

			$_post['thumbnail'] = $this->get_insta_feed_thumbnail_data( $post );

			if ( ! empty( $post['caption'] ) ) {
				$insta_caption_length = isset( $options['oe_insta_caption_length'] ) ? $options['oe_insta_caption_length'] : 30;
				$_post['caption'] = wp_html_excerpt( $post['caption'], $insta_caption_length, '&hellip;' );
			}

			$return_data[] = $_post;
		}

		return $return_data;
	}

	/**
	 * Get data from response
	 *
	 * @param  $response
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function get_insta_tags_response_data( $response, $options ) {
		$response_posts = $response['graphql']['hashtag']['edge_hashtag_to_media']['edges'];

		$insta_caption_length = ( $options['insta_caption_length'] ) ? $options['insta_caption_length'] : 30;

		if ( empty( $response_posts ) ) {
			$response_posts = $response['graphql']['hashtag']['edge_hashtag_to_top_posts']['edges'];
		}

		$return_data  = array();
		$images_count = ! empty( $options['oe_images_count'] ) ? $options['oe_images_count'] : 5;
		$posts = array_slice( $response_posts, 0, $images_count, true );

		foreach ( $posts as $post ) {
			$_post              = array();

			$_post['link']      = sprintf( $this->insta_api_url . 'p/%s/', $post['node']['shortcode'] );
			$_post['caption']   = '';
			$_post['comments']  = $post['node']['edge_media_to_comment']['count'];
			$_post['likes']     = $post['node']['edge_liked_by']['count'];
			$_post['thumbnail'] = $this->get_insta_tags_thumbnail_data( $post );

			if ( isset( $post['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
				$_post['caption'] = wp_html_excerpt( $post['node']['edge_media_to_caption']['edges'][0]['node']['text'], $insta_caption_length, '&hellip;' );
			}

			$return_data[] = $_post;
		}

		return $return_data;
	}

	/**
	 * Generate thumbnail resources.
	 *
	 * @since 1.0.0
	 * @param $post_data
	 *
	 * @return array
	 */
	public function get_insta_tags_thumbnail_data( $post ) {
		$post = $post['node'];

		$thumbnail = array(
			'thumbnail' => false,
			'low'       => false,
			'standard'  => false,
			'high'		=> false,
		);

		if ( is_array( $post['thumbnail_resources'] ) && ! empty( $post['thumbnail_resources'] ) ) {
			foreach ( $post['thumbnail_resources'] as $key => $resources_data ) {

				if ( 150 === $resources_data['config_width'] ) {
					$thumbnail['thumbnail'] = $resources_data;
					continue;
				}

				if ( 320 === $resources_data['config_width'] ) {
					$thumbnail['low'] = $resources_data;
					continue;
				}

				if ( 640 === $resources_data['config_width'] ) {
					$thumbnail['standard'] = $resources_data;
					continue;
				}
			}
		}

		if ( ! empty( $post['display_url'] ) ) {
			$thumbnail['high'] = array(
				'src'           => $post['display_url'],
				'config_width'  => $post['dimensions']['width'],
				'config_height' => $post['dimensions']['height'],
			) ;
		}

		return $thumbnail;
	}

	/**
	 * Get Insta Thumbnail Image URL
	 *
	 * @since  1.0.0
	 * @return string   The url of the instagram post image
	 */
	protected function get_insta_image_size( $options ) {
		$size = $options['oe_resolution'];

		switch ( $size ) {
			case 'thumbnail':
				return 'thumbnail';
			case 'low_resolution':
				return 'low';
			case 'standard_resolution':
				return 'standard';
			default:
				return 'low';
		}
	}

	/**
	 * Retrieve response from API
	 *
	 * @since  1.0.0
	 * @return array|WP_Error
	 */
	public function get_insta_remote( $url ) {
		$response       = wp_remote_get( $url, array(
			'timeout'   => 60,
			'sslverify' => false,
		) );

		$response_code  = wp_remote_retrieve_response_code( $response );
		$result         = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 200 !== $response_code ) {
			$message = is_array( $result ) && isset( $result['error']['message'] ) ? $result['error']['message'] : __( 'No posts found', 'oxy-extended' );

			return new \WP_Error( $response_code, $message );
		}

		if ( ! is_array( $result ) ) {
			return new \WP_Error( 'error', __( 'Data Error', 'oxy-extended' ) );
		}

		return $result;
	}

	/**
	 * Sanitize endpoint.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function sanitize_endpoint( $options ) {
		return in_array( $options['oe_insta_display'] , array( 'feed', 'tags' ) ) ? $options['oe_insta_display'] : 'tags';
	}

	/**
	 * Get transient key.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_transient_key( $options ) {
		$endpoint = $this->sanitize_endpoint( $options );
		$target = ( 'tags' === $endpoint ) ? sanitize_text_field( $options['insta_hashtag'] ) : 'users';
		$insta_caption_length = isset( $options['oe_insta_caption_length'] ) ? $options['oe_insta_caption_length'] : 30;
		$insta_caption_length = 30;
		$images_count = $options['oe_images_count'];
		//$images_count = 5;

		return sprintf( 'ppe_instagram_%s_%s_posts_count_%s_caption_%s',
			$endpoint,
			$target,
			$images_count,
			$insta_caption_length
		);
	}

	/**
	 * Get Insta Thumbnail Image URL
	 *
	 * @since  2.2.6
	 * @return string   The url of the instagram post image
	 */
	protected function get_insta_image_url( $item, $size = 'high' ) {
		$thumbnail  = $item['thumbnail'];

		if ( ! empty( $thumbnail[ $size ] ) ) {
			$image_url = $thumbnail[ $size ]['src'];
		} else {
			$image_url = isset( $item['image'] ) ? $item['image'] : '';
		}

		return $image_url;
	}

	/**
	 * Render Instagram profile link.
	 *
	 * @since  1.0.0
	 * @param  array $options
	 * @return array
	 */
	public function get_insta_profile_link( $options ) {
		if ( ! isset( $options['insta_title_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default
			$options['insta_title_icon'] = 'fa fa-instagram';
		}

		$has_icon = ! empty( $options['insta_title_icon'] );

		if ( $has_icon ) {
			$this->add_render_attribute( 'i', 'class', $options['insta_title_icon'] );
			$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
		}

		if ( ! $has_icon && ! empty( $options['title_icon']['value'] ) ) {
			$has_icon = true;
		}
		$migrated = isset( $options['__fa4_migrated']['title_icon'] );
		$is_new   = ! isset( $options['insta_title_icon'] ) && Icons_Manager::is_migration_allowed();

		$this->add_render_attribute( 'title-icon', 'class', 'oe-icon oe-icon-' . $options['insta_title_icon_position'] );

		if ( 'yes' === $options['insta_profile_link'] && $options['insta_link_title'] ) { ?>
			<span class="oe-instagram-feed-title-wrap">
				<a <?php echo $this->get_render_attribute_string( 'instagram-profile-link' ); ?>>
					<span class="oe-instagram-feed-title">
						<?php if ( 'before_title' === $options['insta_title_icon_position'] && $has_icon ) { ?>
						<span <?php echo $this->get_render_attribute_string( 'title-icon' ); ?>>
							<?php
							if ( $is_new || $migrated ) {
								Icons_Manager::render_icon( $options['title_icon'], array( 'aria-hidden' => 'true' ) );
							} elseif ( ! empty( $options['insta_title_icon'] ) ) {
								?>
								<i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i>
								<?php
							}
							?>
						</span>
						<?php } ?>

						<?php echo esc_attr( $options['insta_link_title'] ); ?>

						<?php if ( 'after_title' === $options['insta_title_icon_position'] && $has_icon ) { ?>
						<span <?php echo $this->get_render_attribute_string( 'title-icon' ); ?>>
							<?php
							if ( $is_new || $migrated ) {
								Icons_Manager::render_icon( $options['title_icon'], array( 'aria-hidden' => 'true' ) );
							} elseif ( ! empty( $options['insta_title_icon'] ) ) {
								?>
								<i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i>
								<?php
							}
							?>
						</span>
						<?php } ?>
					</span>
				</a>
			</span>
			<?php
		}
	}

	/**
	 * Retrieve Instagram posts.
	 *
	 * @since  1.0.0
	 * @param  array $options
	 * @return array
	 */
	public function get_insta_posts( $options ) {
		$transient_key = md5( $this->get_transient_key( $options ) );

		$data = get_transient( $transient_key );

		if ( ! empty( $data ) && 1 !== $options['oe_cache_timeout'] && array_key_exists( 'thumbnail_resources', $data[0] ) ) {
			return $data;
		}

		// $user = $this->get_insta_user_id();
		// $user_media = $this->get_insta_user_media( $user['id'] );

		// foreach( $user_media['data'] as $media ) {
		// 	$media_object = $this->get_insta_media( $media['id'] );
		// }

		$response = $this->get_insta_remote( $this->get_fetch_url( $options ) );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$custom_access_token = isset( $options['oe_access_token'] ) ? $options['oe_access_token'] : '';

		$data = ( 'tags' === $custom_access_token ) ? $this->get_insta_tags_response_data( $response, $options ) : $this->get_insta_feed_response_data( $response, $options );

		if ( empty( $data ) ) {
			return array();
		}

		set_transient( $transient_key, $data, $options['oe_cache_timeout'] );

		return $data;
	}

	/**
	 * Render Image Thumbnail
	 *
	 * @since  2.2.6
	 * @return void
	 */
	protected function render_image_thumbnail( $options, $item, $index ) {
		$thumbnail_url   = $this->get_insta_image_url( $item, $this->get_insta_image_size( $options ) );
		$thumbnail_alt   = $item['caption'];
		$thumbnail_title = $item['caption'];
		$likes           = $item['likes'];
		$comments        = $item['comments'];
		$image_key       = 'insta_image';
		$link_key        = 'image_link';
		$item_link       = '';
		$image_html       = '';

		$image_html = '<div class="oe-if-img">';
		$image_html .= '<div class="oe-overlay-container oe-media-overlay">';
		if ( 'yes' === $options['oe_insta_caption'] ) {
			$image_html .= '<div class="oe-insta-caption">' . $thumbnail_alt . '</div>';
		}
		$image_html .= '</div>';
		$image_html .= '<img src="' . $thumbnail_url . '"/>';
		$image_html .= '</div>';

		echo $image_html;
	}

	/**
	 * Render load more button output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render_api_images( $options ) {
		$gallery = $this->get_insta_posts( $options );

		if ( empty( $gallery ) || is_wp_error( $gallery ) ) {
			$message = is_wp_error( $gallery ) ? $gallery->get_error_message() : esc_html__( 'No Posts Found', 'oxy-extended' );

			echo $message;
			return;
		}
		$classes          = '';
		$slider_container = '';
		$slider_wrapper   = '';
		$slider_slide     = '';
		$gray     = '';

		if ( 'grid' === $options['oe_feed_layout'] && ! empty( $options['oe_feed_layout_grid_coulumn'] ) ) {
			$classes .= "oe-grid-" . $options['oe_feed_layout_grid_coulumn'];
		} else if ( 'carousel' === $options['oe_feed_layout'] ) {
			$slider_container = 'swiper-container';
			$slider_wrapper   = 'swiper-wrapper';
			$slider_slide     = 'swiper-slide';
		}
        if ( 'yes' === $options['insta_image_grayscale'] ) {
            $gray .= ' oe-instagram-feed-gray ';
		}

		if ( 'yes' === $options['insta_image_grayscale_hover'] ) {
            $gray .= ' oe-instagram-feed-hover-gray ';
		}
		?>
		<div class="oe-instagram-feed <?php echo $slider_container.' '.$gray; ?>">
			<div class="oe-insta-feed-inner oe-grid <?php echo $slider_wrapper; ?>">
				<?php
				foreach ( $gallery as $index => $item ) {
					?>
					<div class="oe-grid-item-wrap <?php echo $classes; echo $slider_slide; ?>">
						<div class="oe-grid-item">
							<?php $this->render_image_thumbnail( $options, $item, $index ); ?>
						</div>
					</div>
					<?php
				}
				?>
			</div>
			<?php
				if ( 'carousel' === $options['oe_feed_layout'] ) {
				?>
                    <?php if( $options['pagination'] == 'yes' ) { ?>
					   <div class="swiper-pagination"></div>
                    <?php } if( $options['navigation_arrows'] == 'yes' ) { ?>
                        <div class="oe-swiper-button swiper-button-next"></div>
                        <div class="oe-swiper-button swiper-button-prev"></div>
                    <?php } ?>
				<?php
				}
			?>
		</div>
		<?php
	}

	/**
	 * Render Instagram Feed element output on the frontend.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param  mixed $options  Element options.
	 * @param  mixed $defaults Element defaults.
	 * @param  mixed $content  Element content.
	 * @return void
	 */
	public function render( $options, $defaults, $content ) {
		if ( 'carousel' === $options['oe_feed_layout'] ) {
			$layout = 'carousel';
		} else {
			$layout = 'grid';
		}
		?>
		<div class ="oe-instagram-feed-container">
            <?php
                $this->render_api_images( $options );
            ?>
		</div>
		<?php
		ob_start(); ?>
                jQuery(document).ready(function($){
				    var swiper = new Swiper('.swiper-container', {
                        slidesPerView: <?php echo $options['slide_per_view']; ?>,
                        spaceBetween: <?php echo $options['items_gap']; ?>,
                        speed: <?php echo $options['slider_speed']; ?>,
                        direction: 'horizontal',
                        autoHeight: false,
                        grabCursor: '<?php echo $options['grab_cursor']; ?>',
                        loop: '<?php echo $options['infinite_loop']; ?>',
                        <?php if( $options['autoplay'] == 'yes' ) { ?>
                            autoplay: {
                                delay: <?php echo $options['autoplay_speed']; ?>,
                                disableOnInteraction: '<?php echo $options['pause_on_interaction']; ?>'
                            },
                        <?php } if( $options['pagination'] == 'yes' ) { ?>
                            pagination: {
                                el: '.swiper-pagination',
                                clickable: true,
                            },
                        <?php } if( $options['navigation_arrows'] == 'yes' ) { ?>
                            // Navigation arrows
                            navigation: {
                                nextEl: '.swiper-button-next',
                                prevEl: '.swiper-button-prev',
                            },
                        <?php } ?>
                    });
				});
        <?php
        if ( isset( $_GET['oxygen_iframe'] ) || defined('OXY_ELEMENTS_API_AJAX') )
        {
            $this->El->builderInlineJS( ob_get_clean() );
        } else {
            $this->swiper_js_code[] = ob_get_clean();

            if( ! $this->js_added ) {
                add_action( 'wp_footer', array( $this, 'oe_swiper_enqueue_scripts' ) );
                $this->js_added = true;
            }

            $this->El->footerJS( join('', $this->swiper_js_code) );
        }
	}
    
    public function init() {
		$this->El->useAJAXControls();
		if ( isset( $_GET['oxygen_iframe'] ) ) {
			add_action( 'wp_footer', array( $this, 'oe_swiper_enqueue_scripts' ) );
		}
	}
    
	public function oe_swiper_enqueue_scripts() {
		wp_enqueue_style('oe-swiper-style');
        wp_enqueue_script('oe-swiper-script');
	}

	public function customCSS( $original, $selector ) {
		$css = '';

		if ( ! $this->css_added ) {
			$this->css_added = true;
			$css .= file_get_contents( __DIR__ . '/' . basename( __FILE__, '.php' ) . '.css' );
		}

		$prefix = $this->El->get_tag();

		return $css;
	}

	/**
	 * Enable Presets
	 *
	 * @return bool
	 */
	public function enablePresets() {
		return true;
	}
}

( new OEInstgramFeed() )->removeApplyParamsButton();
