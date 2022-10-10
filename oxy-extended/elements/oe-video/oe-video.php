<?php
namespace Oxygen\OxyExtended;

/**
 * Gallery Slider Element
 */
class OEVideo extends \OxyExtendedEl {
	public $css_added = false;
	public $js_added = false;
	private $video_js_code = array();

	/**
	 * Retrieve before after slider element name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'Video', 'oxy-extended' );
	}

	/**
	 * Retrieve before after slider element slug.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element slug.
	 */
	public function slug() {
		return 'oe_video';
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
	 * Retrieve before after slider element icon.
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
		$this->video_controls();
		$this->video_settings_controls();
		$this->thumbnail_controls();
		$this->play_icon_controls();
		$this->video_overlay_controls();
	}

	/**
	 * Controls for Video Source
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function video_controls() {
		$video_source = $this->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Video Source', 'oxy-extended' ),
				'slug'    => 'video_source',
				'value'   => array(
					'youtube'     => __( 'YouTube', 'oxy-extended' ),
					'vimeo'       => __( 'Vimeo', 'oxy-extended' ),
					'link'        => __( 'External Link', 'oxy-extended' ),
				),
				'default' => 'youtube',
				'css'     => false,
			)
		);
		$video_source->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$video_source->rebuildElementOnChange();

		$video_url = $this->addCustomControl(
			'<div class="oxygen-file-input oxygen-option-default" ng-class="{\'oxygen-option-default\':iframeScope.isInherited(iframeScope.component.active.id, \'oxy-oe_video_video_url\')}">
				<input type="text" spellcheck="false" ng-model="iframeScope.component.options[iframeScope.component.active.id][\'model\'][\'oxy-oe_video_video_url\']" ng-model-options="{ debounce: 10 }" ng-change="iframeScope.setOption(iframeScope.component.active.id, iframeScope.component.active.name,\'oxy-oe_video_video_url\');iframeScope.checkResizeBoxOptions(\'oxy-oe_video_video_url\'); " class="ng-pristine ng-valid ng-touched" placeholder="https://">
				<div class="oxygen-dynamic-data-browse" ctdynamicdata data="iframeScope.dynamicShortcodesLinkMode" callback="iframeScope.oeDynamicVideoUrl">data</div>
			</div>
			',
			'video_url'
		);
		$video_url->setParam( 'heading', __( 'External Video URL', 'oxy-extended' ) );
		$video_url->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_video_video_source']=='link'" );
		$video_url->rebuildElementOnChange();

		$youtube_url = $this->addCustomControl(
			'<div class="oxygen-file-input oxygen-option-default" ng-class="{\'oxygen-option-default\':iframeScope.isInherited(iframeScope.component.active.id, \'oxy-oe_video_youtube_url\')}">
				<input type="text" spellcheck="false" ng-model="iframeScope.component.options[iframeScope.component.active.id][\'model\'][\'oxy-oe_video_youtube_url\']" ng-model-options="{ debounce: 10 }" ng-change="iframeScope.setOption(iframeScope.component.active.id, iframeScope.component.active.name,\'oxy-oe_video_youtube_url\');iframeScope.checkResizeBoxOptions(\'oxy-oe_video_youtube_url\'); " class="ng-pristine ng-valid ng-touched" placeholder="https://">
				<div class="oxygen-dynamic-data-browse" ctdynamicdata data="iframeScope.dynamicShortcodesLinkMode" callback="iframeScope.oeDynamicVideoUrl">data</div>
			</div>
			',
			'youtube_url'
		);
		$youtube_url->setParam( 'heading', __( 'YouTube Video URL', 'oxy-extended' ) );
		$youtube_url->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_video_video_source']=='youtube'" );
		$youtube_url->rebuildElementOnChange();

		$vimeo_url = $this->addCustomControl(
			'<div class="oxygen-file-input oxygen-option-default" ng-class="{\'oxygen-option-default\':iframeScope.isInherited(iframeScope.component.active.id, \'oxy-oe_video_vimeo_url\')}">
				<input type="text" spellcheck="false" ng-model="iframeScope.component.options[iframeScope.component.active.id][\'model\'][\'oxy-oe_video_vimeo_url\']" ng-model-options="{ debounce: 10 }" ng-change="iframeScope.setOption(iframeScope.component.active.id, iframeScope.component.active.name,\'oxy-oe_video_vimeo_url\');iframeScope.checkResizeBoxOptions(\'oxy-oe_video_vimeo_url\'); " class="ng-pristine ng-valid ng-touched" placeholder="https://">
				<div class="oxygen-dynamic-data-browse" ctdynamicdata data="iframeScope.dynamicShortcodesLinkMode" callback="iframeScope.oeDynamicVideoUrl">data</div>
			</div>
			',
			'vimeo_url'
		);
		$vimeo_url->setParam( 'heading', __( 'Vimeo Video URL', 'oxy-extended' ) );
		$vimeo_url->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_video_video_source']=='vimeo'" );
		$vimeo_url->rebuildElementOnChange();

		$start_time = $this->addOptionControl(
			array(
				'type' => 'slider-measurebox',
				'name' => __( 'Start Time', 'oxy-extended' ),
				'slug' => 'start_time',
			),
		);
		$start_time->setParam( 'description', __( 'Enter start time in seconds', 'oxy-extended' ) );
		$start_time->setUnits( 's', 'sec' );
		$start_time->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );

		$end_time = $this->addOptionControl(
			array(
				'type' => 'slider-measurebox',
				'name' => __( 'End Time', 'oxy-extended' ),
				'slug' => 'end_time',
			),
		);
		$end_time->setParam( 'description', __( 'Enter end time in seconds', 'oxy-extended' ) );
		$end_time->setUnits( 's', 'sec' );
		$end_time->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );

		$aspect_ratio = $this->addOptionControl([
			'type'    => 'radio',
			'name'    => __( 'Aspect Ratio', 'oxy-extended' ),
			'slug'    => 'oe_aspect_ratio',
			'value'   => [
				'11'  => __( '1:1', 'oxy-extended' ),
				'32'  => __( '3:2', 'oxy-extended' ),
				'43'  => __( '4:3', 'oxy-extended' ),
				'169' => __( '16:9', 'oxy-extended' ),
				'219' => __( '21:9', 'oxy-extended' ),
			],
			'default' => '169',
			'css'     => false,
		]);
		$aspect_ratio->rebuildElementOnChange();
	}

	/**
	 * Controls for Video Options
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function video_settings_controls() {
		/**
		 * Video Options Section
		 * -------------------------------------------------
		 */
		$video_options = $this->addControlSection( 'oe_video_options', __( 'Video Options', 'oxy-extended' ), 'assets/icon.png', $this );

		$lightbox = $video_options->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Lightbox', 'oxy-extended' ),
				'slug'    => 'lightbox',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'no',
				'css'     => false,
			)
		);

		$lightbox->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$lightbox->rebuildElementOnChange();

		$autoplay = $video_options->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Autoplay', 'oxy-extended' ),
				'slug'    => 'autoplay',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'no',
				'css'     => false,
			)
		);

		$autoplay->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$autoplay->rebuildElementOnChange();

		$loop = $video_options->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Loop', 'oxy-extended' ),
				'slug'    => 'loop',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'no',
			)
		);

		$loop->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$loop->rebuildElementOnChange();

		$mute = $video_options->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Mute', 'oxy-extended' ),
				'slug'    => 'mute',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'no',
			)
		);

		$mute->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$mute->rebuildElementOnChange();

		$controls = $video_options->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Player Controls', 'oxy-extended' ),
				'slug'    => 'controls',
				'value'   => array(
					'yes' => __( 'Show', 'oxy-extended' ),
					'no'  => __( 'Hide', 'oxy-extended' ),
				),
				'default' => 'yes',
			)
		);

		$controls->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_video_video_source']!='vimeo'" );
		$controls->rebuildElementOnChange();

		$modest_branding = $video_options->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Modest Branding', 'oxy-extended' ),
				'slug'    => 'modestbranding',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'no',
			),
		);
		$modest_branding->setParam( 'description', __( 'Turn on this option to use a YouTube player that does not show a YouTube logo.', 'oxy-extended' ) );
		$modest_branding->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_video_video_source']=='youtube'" );

		$yt_privacy = $video_options->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Privacy Mode', 'oxy-extended' ),
				'slug'    => 'yt_privacy',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'no',
			),
		);
		$yt_privacy->setParam( 'description', __( 'When you turn on privacy mode, YouTube won\'t store information about visitors on your website unless they play the video.', 'oxy-extended' ) );
		$yt_privacy->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_video_video_source']=='youtube'" );

		$rel = $video_options->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Suggested Videos', 'oxy-extended' ),
				'slug'    => 'rel',
				'value'   => array(
					'current_channel' => __( 'Current Video Channel', 'oxy-extended' ),
					'any_video'       => __( 'Any Video', 'oxy-extended' ),
				),
				'default' => 'current_channel',
			),
		);
		//$rel->setParam( 'description', __( 'When you turn on privacy mode, YouTube won\'t store information about visitors on your website unless they play the video.', 'oxy-extended' ) );
		$rel->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_video_video_source']=='youtube'" );
	}

	public function thumbnail_controls() {
		$video_thumbnail_section = $this->addControlSection( 'oe_video_thumbnail_section', __( 'Thumbnail', 'oxy-extended' ), 'assets/icon.png', $this );

		$thumbnail_size = $video_thumbnail_section->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Thumbnail Size', 'oxy-extended' ),
				'slug'    => 'oe_thumbnail_size',
				'value'   => array(
					'maxresdefault' => __( 'Maximum Resolution', 'oxy-extended' ),
					'hqdefault'     => __( 'High Quality', 'oxy-extended' ),
					'mqdefault'     => __( 'Medium Quality', 'oxy-extended' ),
					'sddefault'     => __( 'Standard Quality', 'oxy-extended' ),
				),
				'default' => 'maxresdefault',
				'css'     => false,
			)
		);
		$thumbnail_size->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_video_video_source']=='youtube'" );
		$thumbnail_size->rebuildElementOnChange();

		$video_thumbnail = $video_thumbnail_section->addOptionControl([
			'type'    => 'radio',
			'name'    => __( 'Custom Thumbnail', 'oxy-extended' ),
			'slug'    => 'oe_custom_thumbnail',
			'value'   => [
				'yes' => __( 'Yes', 'oxy-extended' ),
				'no'  => __( 'No', 'oxy-extended' ),
			],
			'default' => 'no',
			'css'     => false,
		]);
		$video_thumbnail->rebuildElementOnChange();

		$video_thumbnail_url = $video_thumbnail_section->addCustomControl(
			"<div class=\"oxygen-control not-available-for-classes not-available-for-media\">			
				<div class=\"oxygen-file-input\">
					<input type=\"text\" spellcheck=\"false\" ng-change=\"iframeScope.setOption(iframeScope.component.active.id,'ct_image','oxy-oe_video_custom_image'); iframeScope.parseImageShortcode()\" ng-class=\"{'oxygen-option-default':iframeScope.isInherited(iframeScope.component.active.id, 'oxy-oe_video_custom_image')}\" ng-model=\"iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_video_custom_image']\" ng-model-options=\"{ debounce: 10 }\" class=\"ng-pristine ng-valid oxygen-option-default ng-touched\">
					<div class=\"oxygen-file-input-browse\" data-mediatitle=\"Select Image\" data-mediabutton=\"Select Image\" data-mediaproperty=\"oxy-oe_video_custom_image\" data-mediatype=\"mediaUrl\" data-returnvalue=\"url\">browse</div>
					<div class=\"oxygen-dynamic-data-browse ng-isolate-scope\" ctdynamicdata data=\"iframeScope.dynamicShortcodesImageMode\" callback=\"iframeScope.ouDynamicUVImage\">data</div>
				</div>
			</div>",
			'custom_image'
		);
		$video_thumbnail_url->setParam( 'heading', __( 'Image', 'oxy-extended' ) );
		$video_thumbnail_url->setParam( 'css', false );
		$video_thumbnail_url->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_video_oe_custom_thumbnail']=='yes'" );
		$video_thumbnail_url->rebuildElementOnChange();
	}

	public function play_icon_controls() {
		$play_icon_section = $this->addControlSection( 'oe_play_icon_section', __( 'Play Icon', 'oxy-extended' ), 'assets/icon.png', $this );

		$play_icon_section->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Icon Type', 'oxy-extended' ),
				'slug'    => 'oe_play_icon_type',
				'value'   => array(
					'none'  => esc_html__( 'None', 'oxy-extended' ),
					'icon'  => esc_html__( 'Icon', 'oxy-extended' ),
					'image' => esc_html__( 'Image', 'oxy-extended' ),
				),
				'default' => 'icon',
				'css'     => false,
			)
		)->rebuildElementOnChange();

		$play_icon_section->addOptionControl(
			array(
				'type'      => 'icon_finder',
				'name'      => __( 'Icon', 'oxy-extended' ),
				'slug'      => 'oe_play_icon',
				'value'     => 'FontAwesomeicon-play-circle',
				'default'   => 'FontAwesomeicon-play-circle',
				'css'       => false,
				'condition' => 'oe_play_icon_type=icon',
			)
		)->rebuildElementOnChange();

		$image = $play_icon_section->addCustomControl(
			"<div class='oxygen-control'>
				<div class='oxygen-file-input'
				ng-class=\"{'oxygen-option-default':iframeScope.isInherited(iframeScope.component.active.id, 'oe_play_icon_image')}\">
					<input type=\"text\" spellcheck=\"false\" ng-model=\"iframeScope.component.options[iframeScope.component.active.id]['model']['oe_play_icon_image']\" ng-model-options=\"{ debounce: 10 }\"
						ng-change=\"iframeScope.setOption(iframeScope.component.active.id,'oe_flip_box','oe_play_icon_image');\">
					<div class=\"oxygen-file-input-browse\"
						data-mediaTitle=\"Select Image\" 
						data-mediaButton=\"Select Image\"
						data-mediaMultiple=\"false\"
						data-mediaProperty=\"oe_play_icon_image\"
						data-mediaType=\"mediaUrl\">" . __( 'browse', 'oxy-extended' ) . "
					</div>
				</div>
			</div>",
			'oe_play_icon_image',
			$play_icon_section
		);
		$image->setParam( 'heading', __( 'Image', 'oxy-extended' ) );
		$image->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_video_oe_play_icon_type']=='image'" );
		$image->rebuildElementOnChange();

		$play_icon_style_section = $play_icon_section->addControlSection( 'play_icon_style_section', __( 'Play Icon Style', 'oxy-extended' ), 'assets/path', $this );

		$play_icon_size = $play_icon_style_section->addStyleControl([
			'name'         => __( 'Size', 'oxy-extended' ),
			'selector'     => '.oe-video-play-icon svg',
			'property'     => 'width|height',
			'slug'         => 'pi_size',
			'control_type' => 'slider-measurebox',
		])->setUnits( 'px', 'px' )->setRange( '20', '200', '10' )->setValue( '80' );

		$play_icon_style_section->addStyleControls([
			[
				'selector'     => '.oe-video-play-icon svg',
				'name'         => __( 'Color', 'oxy-extended' ),
				'property'     => 'fill',
				'control_type' => 'colorpicker',
				'slug'         => 'oe_play_icon_color',
			],
			[
				'name'         => __( 'Hover Color', 'oxy-extended' ),
				'selector'     => '.oe-video-play-icon:hover svg',
				'property'     => 'fill',
				'control_type' => 'colorpicker',
				'slug'         => 'oe_play_icon_color_hover',
			],
			[
				'selector' => '.oe-video-play-icon',
				'property' => 'opacity',
				'slug'     => 'oe_play_icon_opacity',
			],
		]);
	}

	public function video_overlay_controls() {
		$overlay_style = $this->addControlSection( 'overlay_section', __( 'Overlay Style', 'oxy-extended' ), 'assets/path', $this );

		$overlay_style->addStyleControls([
			[
				'property' => 'background-color',
				'slug'     => 'overlay_background',
				'selector' => '.oe-video-overlay',
			],
		]);
	}

	/**
	 * Returns Video ID.
	 *
	 * @access protected
	 */
	protected function get_video_id( $options ) {
		$video_id = '';
		$video_url = $this->get_video_url( $options );

		if ( 'youtube' === $options['video_source'] ) {
			$url = $options['youtube_url'];

			if ( preg_match( '#(?<=v=|v\/|vi=|vi\/|youtu.be\/)[a-zA-Z0-9_-]{11}#', $url, $matches ) ) {
				$video_id = $matches[0];
			}
		} elseif ( 'vimeo' === $options['video_source'] ) {
			$url = $options['vimeo_url'];

			$video_id = preg_replace( '/[^\/]+[^0-9]|(\/)/', '', rtrim( $url, '/' ) );

		}

		return $video_id;
	}

	/**
	 * Returns Video Thumbnail.
	 *
	 * @access protected
	 */
	protected function get_video_thumbnail( $options, $thumb_size ) {
		$thumb_url = '';
		$video_id  = $this->get_video_id( $options );

		if ( 'yes' === $options['oe_custom_thumbnail'] ) {

			if ( $options['custom_image'] ) {
				$thumb_url = $options['custom_image'];
			}
		} elseif ( 'youtube' === $options['video_source'] ) {

			if ( $video_id ) {
				$thumb_url = 'https://i.ytimg.com/vi/' . $video_id . '/' . $thumb_size . '.jpg';
			}
		} elseif ( 'vimeo' === $options['video_source'] ) {

			if ( $video_id ) {
				$vimeo     = unserialize( file_get_contents( "https://vimeo.com/api/v2/video/$video_id.php" ) );
				$thumb_url = $vimeo[0]['thumbnail_large'];
			}
		}

		return $thumb_url;

	}

	/**
	 * Render play icon output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_play_icon( $options ) {
		$play_icon = $options['oe_play_icon'];

		if ( 'none' === $options['oe_play_icon_type'] ) {
			return;
		}

		$this->add_render_attribute( 'play-icon', 'class', 'oe-video-play-icon' );

		if ( 'icon' === $options['oe_play_icon_type'] && $play_icon ) {
			$this->add_render_attribute( 'play-icon', 'class', 'oe-icon' );
			?>
			<span <?php echo $this->get_render_attribute_string( 'play-icon' ); ?>>
				<?php
					global $oxygen_svg_icons_to_load;

					$oxygen_svg_icons_to_load[] = $play_icon;

					echo '<svg id="svg' . esc_attr( $options['selector'] ) . '-icon" class="oe-video-icon-svg"><use xlink:href="#' . $play_icon . '"></use></svg>';
				?>
			</span>
			<?php

		} elseif ( 'image' === $options['oe_play_icon_type'] ) {

			if ( $options['play_icon_image']['url'] != '' ) {
				?>
				<span <?php echo $this->get_render_attribute_string( 'play-icon' ); ?>>
					<img src="<?php echo esc_url( $settings['play_icon_image']['url'] ); ?>" alt="<?php echo Control_Media::get_image_alt( $settings['play_icon_image'] ); ?>">
				</span>
				<?php
			}
		}
	}

	protected function render_video_overlay() {
		$this->add_render_attribute(
			'overlay',
			'class',
			array(
				'oe-media-overlay',
				'oe-video-overlay',
			)
		);

		return '<div ' . $this->get_render_attribute_string( 'overlay' ) . '></div>';
	}

	public function render_video( $options ) {
		$video_url_src = '';
		$thumb_size    = '';
		$video_url = $this->get_video_url( $options );
		$play_tag = 'div';

		if ( 'youtube' === $options['video_source'] ) {
			$video_url_src = $options['youtube_url'];
			$thumb_size    = $options['oe_thumbnail_size'];
		} elseif ( 'vimeo' === $options['video_source'] ) {
			$video_url_src = $options['vimeo_url'];
		}

		$embed_params  = $this->oe_get_embed_params( $options, $video_url );
		$embed_options = $this->oe_get_embed_options( $options );

		$video_url = $this->oe_get_embed_url( $video_url_src, $embed_params, $embed_options );

		$autoplay = ( 'yes' === $options['autoplay'] ) ? '1' : '0';

		$aspect_ratio = isset($options['oe_aspect_ratio']) ? $options['oe_aspect_ratio'] : '169';

		$this->add_render_attribute(
			array(
				'video-container' => array(
					'class' => array( 'oe-video-container', 'oe-aspect-ratio-' . $aspect_ratio ),
				),
				'video-play'      => array(
					'class' => 'oe-video-play',
				),
				'video-player'    => array(
					'class'    => 'oe-video-player',
					'data-src' => $video_url,
				),
			)
		);

		if ( 'yes' === $options['lightbox'] ) {
			$play_tag = 'a';
			$id = str_replace( '-', '', $options['selector'] ) . '_' . get_the_ID();

			$this->add_render_attribute( 'video-play', 'class', 'oe-video-play-lightbox' );
			$this->add_render_attribute(
				'video-play',
				array(
					'data-fancybox' => '',
					'href' => $video_url_src,
				)
			);

		} else {
			$this->add_render_attribute( 'video-play', 'data-autoplay', $autoplay );
		}
		?>
		<div <?php echo $this->get_render_attribute_string( 'video-container' ); ?>>
			<<?php echo $play_tag . ' ' . $this->get_render_attribute_string( 'video-play' ); ?>>
				<?php
					// Video Overlay
					echo $this->render_video_overlay();
				?>
				<div <?php echo $this->get_render_attribute_string( 'video-player' ); ?>>
					<img class="oe-video-thumb" src="<?php echo esc_url( $this->get_video_thumbnail( $options, $thumb_size ) ); ?>" alt="">
					<?php $this->render_play_icon( $options ); ?>
				</div>
			</<?php echo $play_tag; ?>>
		</div>
		<?php
	}

	public function render( $options, $defaults, $content ) {
		$video_url = $this->get_video_url( $options );
		$uid = str_replace( '.', '', uniqid( 'oe-video-', true ) );

		$this->add_render_attribute(
			array(
				'video-wrap' => array(
					'class' => 'oe-video-wrap',
				),
				'video' => array(
					'class' => 'oe-video',
					'id'    => $uid,
				),
			)
		);
		?>
		<div <?php echo $this->get_render_attribute_string( 'video-wrap' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'video' ); ?>>
				<?php $this->render_video( $options ); ?>
			</div>
		</div>
		<?php
		if( '2' == $video_url )
		{
			$videoHTML =  $this->get_video_content( $options, $video_url );
			$aspect_ratio = isset($options['oe_aspect_ratio']) ? $options['oe_aspect_ratio'] : '169';
			$uid = str_replace( ".", "", uniqid( 'oev-', true ) );

			if ( ! empty( $videoHTML ) )  {
				//ouv' . $options['selector'] . '-' . get_the_ID() . ' 

				if( isset( $options['action_type'] ) && $options['action_type'] !== 'default' ) :
					echo '<div class="oe-video-wrapper oe-video-lightbox oxy-inner-content '. $uid . ' oev-ar-' . $aspect_ratio .'">';
					if ( ! defined('OXY_ELEMENTS_API_AJAX') ) :
						wp_enqueue_style('oe-fancybox');
						wp_enqueue_script('oe-fancybox');
						wp_enqueue_script('oev-frontend-script');
						$this->El->inlineJS("
							jQuery(document).ready(function($) {
								new OEVideo({
									id: '". $uid ."',
									type: '".$options['video_source']."',
									aspectRatio: '".$aspect_ratio ."',
									lightbox: true,
									overlay: false,
									selector: '". $options['selector'] . "'
								});
							});
						");
					endif;
				?>
					<div class="oe-video-image-overlay">
						<?php 
							if( $content ) {
								echo do_shortcode($content);
							}

							if( $this->has_lightbox( $options ) ) {
								echo '<script type="text/html" class="oe-video-lightbox-content">';
								echo '<div class="oe-video-container"><div class="oe-aspect-ratio"><button data-fancybox-close="" class="fancybox-close-small" title="Close"><svg viewBox="0 0 32 32"><path d="M10,10 L22,22 M22,10 L10,22"></path></svg></button>';
								echo $videoHTML;
								echo '</div></div>';
								echo '</script>';
							}
						?>
					</div>
				<?php
					echo '</div>';
				else:
					echo '<div class="oe-video-wrapper '. $uid . ' oev-ar-' . $aspect_ratio .'">';
					echo '<div class="oe-aspect-ratio">';			

					if ( $this->has_image_overlay( $options ) ) 
					{
						$overlay_attrs['class'] = 'oe-video-image-overlay';
						if( isset( $options['image'] ) ) {
							$overlayImage = $options['image'];
							if( strstr( $overlayImage, 'oedata_') ) {
								$overlayImage = base64_decode( str_replace( 'oedata_', '', $overlayImage ) );
								$shortcode = oxyextend_gsss( $this->El, $overlayImage );
								$overlayImage = do_shortcode( $shortcode );
							}
							$overlay_attrs['style'] = 'background-image: url(' . esc_url( $overlayImage ) . ');';
						}

						if ( $this->has_lightbox( $options ) ) 
						{
							if ( ! defined('OXY_ELEMENTS_API_AJAX') ) :
								wp_enqueue_style('oe-fancybox');
								wp_enqueue_script('oe-fancybox');
								wp_enqueue_script('oev-frontend-script');
								$this->El->inlineJS("
									jQuery(document).ready(function($) {
										new OEVideo({
											id: '". $uid ."',
											type: '".$options['video_source']."',
											aspectRatio: '".$aspect_ratio ."',
											lightbox: true,
											overlay: false,
											selector: '". $options['selector'] . "'
										});
									});
								");
							endif;
						} else {
							if ( ! defined('OXY_ELEMENTS_API_AJAX') ) :
								wp_enqueue_script('oev-frontend-script');
								$this->El->inlineJS("
									jQuery(document).ready(function($) {
										new OEVideo({
											id: '". $uid ."',
											type: '".$options['video_source']."',
											aspectRatio: '".$aspect_ratio ."',
											lightbox: false,
											overlay: true,
										});
									});
								");
							endif;

							echo $videoHTML;
						}
					?>
						<div <?php echo $this->oe_video_attributes( $overlay_attrs ); ?>>
							<?php 
								if( $this->has_lightbox( $options ) ) {
									echo '<script type="text/html" class="oe-video-lightbox-content">';
									echo '<div class="oe-video-container"><div class="oe-aspect-ratio"><button data-fancybox-close="" class="fancybox-close-small" title="Close"><svg viewBox="0 0 32 32"><path d="M10,10 L22,22 M22,10 L10,22"></path></svg></button>';
									echo $videoHTML;
									echo '</div></div>';
									echo '</script>';
								}
							?>
							<?php if ( 'show' === $options['play_icon'] ) { ?>
								<div class="oe-video-play-btn" role="button">
									<?php echo file_get_contents(__DIR__.'/play-button.svg' ); ?>
									<span class="oe-screen-only"><?php _e( 'Play Video', 'oxy-extended' ); ?></span>
								</div>
							<?php } ?>
						</div>
					<?php
					} else {

						echo $videoHTML;

					}
					echo '</div></div>';
				endif;
			}
		}

		ob_start();

		$this->get_video_script( $options, $uid );

		if ( isset( $_GET['oxygen_iframe'] ) || defined('OXY_ELEMENTS_API_AJAX') ) {
			if ( 'yes' === $options['lightbox'] ) {
				$this->oe_enqueue_scripts( $options );
			}
			$this->El->builderInlineJS( ob_get_clean() );
		} else {
			$this->video_js_code[] = ob_get_clean();

			if ( ! $this->js_added ) {
				if ( 'yes' === $options['lightbox'] ) {
					add_action( 'wp_footer', array( $this, 'oe_enqueue_scripts' ) );
				}
				$this->js_added = true;
			}

			$this->El->footerJS( join('', $this->video_js_code) );
		}
	}

	public function has_image_overlay( $options ) {
		if( isset( $options['custom_img'] ) && $options['custom_img'] == 'yes' && isset( $options['image'] ) )
			return true;
		else
			return false;
	}

	public function has_lightbox( $options ) {
		if( isset( $options['video_lightbox'] ) && $options['video_lightbox'] == 'yes' )
			return true;
		else
			return false;
	}

	public function oe_video_attributes( array $attributes ) {
		$rendered_attributes = array();

		foreach ( $attributes as $attribute_key => $attribute_values ) {
			if ( is_array( $attribute_values ) ) {
				$attribute_values = implode( ' ', $attribute_values );
			}

			$rendered_attributes[] = sprintf( '%1$s="%2$s"', $attribute_key, esc_attr( $attribute_values ) );
		}

		return implode( ' ', $rendered_attributes );
	}

	public function oe_render_external_video( $options, $video_url ) {
		$video_params = array();

		foreach ( array( 'autoplay', 'loop' ) as $option_name ) {
			if ( 'yes' === $options[$option_name] ) {
				$video_params[ $option_name ] = '';
				if ( 'autoplay' == $option_name ) {
					$video_params['webkit-playsinline'] = '';
					$video_params['playsinline'] = '';
				}
			}
		}

		if ( 'yes' === $options['controls'] ) {
			$video_params['controls'] = '';
		}

		if ( 'yes' === $options['mute'] ) {
			$video_params['muted'] = 'muted';
		}

		if ( isset( $options['poster'] ) ) {
			$poster = $options['poster'];
			if( strstr( $poster, 'oedata_') ) {
				$poster = base64_decode( str_replace( 'oedata_', '', $poster ) );
				$shortcode = oxyextend_gsss( $this->El, $poster );
				$poster = do_shortcode( $shortcode );
			}
			$video_params['poster'] = esc_url( $poster );
			$video_params['preload'] = 'none';
		}
		?>
		<video class="oe-video-player oe-video-iframe" src="<?php echo esc_url( $video_url ); ?>" <?php echo $this->oe_video_attributes( $video_params ); ?>></video>
		<?php
	}

	public function oe_get_embed_params( $options, $video_url ) {
		$params = array();

		/* if ( 'yes' === $options['autoplay'] ) {
			$params['autoplay'] = '1';
		} */

		$params_dictionary = array();

		if ( 'youtube' === $options['video_source'] ) {
			$params_dictionary = array(
				'loop',
				'mute',
				'controls',
				'modestbranding',
				'rel'
			);

			if ( 'yes' === $options['loop'] ) {
				$video_properties = $this->oe_get_video_properties( $video_url );

				$params['playlist'] = $video_properties['video_id'];
			}

			$params['autoplay'] = 1;

			if( isset( $options['start_time'] ) )
				$params['start'] = $options['start_time'];

			if( isset( $options['end_time'] ) )
				$params['end'] = $options['end_time'];

			$params['wmode'] = 'opaque';
		} elseif ( 'vimeo' === $options['video_source'] ) {
			$params_dictionary = array(
				'loop',
				'mute' 				=> 'muted',
				'vimeo_title' 		=> 'title',
				'vimeo_portrait' 	=> 'portrait',
				'vimeo_byline' 		=> 'byline',
			);

			$params['color'] = str_replace( '#', '', $options['control_color'] );

			$params['autopause'] = '0';
		}

		foreach ( $params_dictionary as $key => $param_name ) {
			$setting_name = $param_name;

			if ( is_string( $key ) ) {
				$setting_name = $key;
			}

			$setting_value = 'yes' === $options[$setting_name] ? '1' : '0';

			$params[ $param_name ] = $setting_value;
		}

		return $params;
	}

	public function oe_get_embed_options( $options ) {
		$embed_options = array();

		if ( 'youtube' === $options['video_source'] ) {
			$embed_options['privacy'] = 'yes' === $options['yt_privacy'];
		} elseif ( 'vimeo' === $options['video_source'] && isset( $options['start_time'] ) ) {
			$embed_options['start'] = $options['start_time'];
		}

		return $embed_options;
	}

	public function oe_get_embed_html( $settings, $video_url, $embed_url_params = array(), $options = array(), $frame_attributes = array() ) {
		$default_frame_attributes = array(
			'class' => 'oe-video-iframe',
			'allowfullscreen',
			'allow'	=> 'autoplay',
		);

		$video_embed_url = $this->oe_get_embed_url( $video_url, $embed_url_params, $options );
		if ( ! $video_embed_url ) {
			return null;
		}
		if ( ! $this->has_image_overlay( $settings ) || $this->has_lightbox( $settings ) ) {
			$default_frame_attributes['src'] = $video_embed_url;
		} else {
			$default_frame_attributes['data-src'] = $video_embed_url;
		}

		$frame_attributes = array_merge( $default_frame_attributes, $frame_attributes );

		$attributes_for_print = array();

		foreach ( $frame_attributes as $attribute_key => $attribute_value ) {
			$attribute_value = esc_attr( $attribute_value );

			if ( is_numeric( $attribute_key ) ) {
				$attributes_for_print[] = $attribute_value;
			} else {
				$attributes_for_print[] = sprintf( '%1$s="%2$s"', $attribute_key, $attribute_value );
			}
		}

		$attributes_for_print = implode( ' ', $attributes_for_print );

		$iframe_html = "<iframe $attributes_for_print></iframe>";

		/** This filter is documented in wp-includes/class-oembed.php */
		return apply_filters( 'oembed_result', $iframe_html, $video_url, $frame_attributes );
	}

	public function oe_get_embed_url( $video_url, array $embed_url_params = array(), array $options = array() ) {
		$video_properties = $this->oe_get_video_properties( $video_url );

		if ( ! $video_properties ) {
			return null;
		}

		$embed_patterns = array(
			'youtube' 		=> 'https://www.youtube{NO_COOKIE}.com/embed/{VIDEO_ID}?feature=oembed',
			'vimeo' 		=> 'https://player.vimeo.com/video/{VIDEO_ID}#t={TIME}',
		);

		$embed_pattern = $embed_patterns[ $video_properties['provider'] ];

		$replacements = array(
			'{VIDEO_ID}' => $video_properties['video_id'],
		);

		if ( 'youtube' === $video_properties['provider'] ) {
			$replacements['{NO_COOKIE}'] = ! empty( $options['privacy'] ) ? '-nocookie' : '';
		} elseif ( 'vimeo' === $video_properties['provider'] ) {
			$time_text = '';

			if ( ! empty( $options['start'] ) ) {
				$time_text = date( 'H\hi\ms\s', $options['start'] );
			}

			$replacements['{TIME}'] = $time_text;
		}

		$embed_pattern = str_replace( array_keys( $replacements ), $replacements, $embed_pattern );

		return add_query_arg( $embed_url_params, $embed_pattern );
	}

	public function oe_get_video_properties( $video_url ) {
		$provider_regex = array(
			'youtube' => '/^.*(?:youtu\.be\/|youtube(?:-nocookie)?\.com\/(?:(?:watch)?\?(?:.*&)?vi?=|(?:embed|v|vi|user)\/))([^\?&\"\'>]+)/',
			'vimeo' => '/^.*vimeo\.com\/(?:[a-z]*\/)*([‌​0-9]{6,11})[?]?.*/',
		);

		foreach ( $provider_regex as $provider => $match_mask ) {
			preg_match( $match_mask, $video_url, $matches );

			if ( $matches ) {
				return array(
					'provider' => $provider,
					'video_id' => $matches[1],
				);
			}
		}

		return null;
	}

	public function get_video_content( $options, $video_url ) {
		$video_html = '';

		if ( 'link' === $options['video_source'] ) {
			ob_start();

			$this->oe_render_external_video( $options, $video_url );

			$video_html = ob_get_clean();
		} else {
			$embed_params = $this->oe_get_embed_params( $options, $video_url );

			$embed_options = $this->oe_get_embed_options( $options );

			$video_html = $this->oe_get_embed_html( $options, $video_url, $embed_params, $options, $embed_options );
		}

		return $video_html;
	}

	public function get_video_url( $options ) {
		$video_type = $options['video_source'];

		if ( 'link' === $video_type ) {
			return $this->oe_get_external_link( $options );
		} elseif ( isset( $options['video_url'] ) ) {
			$video_url = $options['video_url'];
			if ( strstr( $video_url, 'oedata_' ) ) {
				$video_url = base64_decode( str_replace( 'oedata_', '', $video_url ) );
				$shortcode = oxyextend_gsss( $this->El, $video_url );
				$video_url = do_shortcode( $shortcode );
			}

			return $video_url;
		} else {
			return;
		}
	}

	public function oe_get_external_link( $options ) {

		if ( 'link' === $options['video_source'] && isset( $options['video_url'] )) {
			$video_url = $options['video_url'];
			if ( strstr( $video_url, 'oedata_') ) {
				$video_url = base64_decode( str_replace( 'oedata_', '', $video_url ) );
				$shortcode = oxyextend_gsss( $this->El, $video_url );
				$video_url = do_shortcode( $shortcode );
			}
		}

		if ( empty( $video_url ) ) {
			return false;
		}

		if ( isset( $options['start_time'] ) || isset( $options['end_time'] ) ) {
			$video_url .= '#t=';
		}

		if ( isset( $options['start_time'] ) ) {
			$video_url .= $options['start_time'];
		}

		if ( isset( $options['end_time'] ) ) {
			$video_url .= ',' . $options['end_time'];
		}

		return $video_url;
	}

	public function oe_enqueue_scripts() {
			wp_enqueue_style(
				'oe-fancybox',
				OXY_EXTENDED_URL . 'assets/lib/fancybox/jquery.fancybox.min.css',
				array(),
				'3.5.2',
				'all'
			);

			wp_enqueue_script(
				'oe-fancybox',
				OXY_EXTENDED_URL . 'assets/lib/fancybox/jquery.fancybox.min.js',
				array( 'jquery' ),
				'3.5.2',
				true
			);
	}

	public function get_video_script( $options, $uid ) {
		?>
		jQuery(document).ready(function($){
			var PPVideo = {
				_play: function( $selector ) {

					var $iframe 		= $( '<iframe/>' );
					var $vid_src 		= $selector.data( 'src' );

					if ( 0 === $selector.find( 'iframe' ).length ) {

						$iframe.attr( 'src', $vid_src );
						$iframe.attr( 'frameborder', '0' );
						$iframe.attr( 'allowfullscreen', '1' );
						$iframe.attr( 'allow', 'autoplay;encrypted-media;' );

						$selector.html( $iframe );
					}
				}
			};

			var videoPlay           = $( '#<?php echo $uid; ?>' ).find( '.oe-video-play' ),
				isLightbox          = videoPlay.hasClass( 'oe-video-play-lightbox' );

			if( ! isLightbox ) {
				videoPlay.off( 'click' ).on( 'click', function( e ) {

					e.preventDefault();

					var $selector 	= $( this ).find( '.oe-video-player' );

						PPVideo._play( $selector );

				});

				if ( videoPlay.data( 'autoplay' ) === '1' ) {

					PPVideo._play( $( '.oe-video-player' ) );
		
				}
			}
		});
		<?php
	}

	public function customCSS( $original, $selector ) {
		$css = '';
		if( ! $this->css_added ) {
			$css = file_get_contents(__DIR__.'/'.basename(__FILE__, '.php').'.css');
			$this->css_added = true;
		}

		$prefix = $this->El->get_tag();

		if( isset( $original[$prefix .'_pi_size'] ) ) {
			$css .= $selector . ' .oe-video-play-btn{padding: calc( ' . $original[$prefix .'_pi_size'] . 'px / 1.5 );}';
		}

		if( isset( $original[$prefix . '_video_lightbox'] ) && $original[$prefix . '_video_lightbox'] == 'yes' ) {
			$ratio = $original[$prefix . '_aspect_ratio'];
			$selc = str_replace('#', '', $selector );
			//$css .= '.fancybox-ouv' . $selc .' .ouv-ar-'. $ratio . ',.fancybox-is-open .fancybox-content{background: none; width: 100%;height: 100%;}';

			if( isset( $original[$prefix . '_fb_bgclr'] ) ) {
				$css .= '.fancybox' . $selc . '.fancybox-is-open .fancybox-bg{background:' . $original[$prefix . '_fb_bgclr'] . ';}';
			}

			if( isset( $original[$prefix . '_fb_opacity'] ) ) {
				$css .= '.fancybox' . $selc . '.fancybox-is-open .fancybox-bg{opacity: '.$original[$prefix . '_fb_opacity'].';}';
			}

			if( isset( $original[$prefix . '_fbc_bgclr'] ) ) {
				$css .= '.fancybox' . $selc . ' .fancybox-content .oe-aspect-ratio .fancybox-close-small{background-color: '.$original[$prefix . '_fbc_bgclr'].';}';
			}

			if( isset( $original[$prefix . '_fbc_bghclr'] ) ) {
				$css .= '.fancybox' . $selc . ' .fancybox-content .oe-aspect-ratio .fancybox-close-small:hover{background-color: '.$original[$prefix . '_fbc_bghclr'].';}';
			}

			if( isset( $original[$prefix . '_fbc_clr'] ) ) {
				$css .= '.fancybox' . $selc . ' .fancybox-content .oe-aspect-ratio .fancybox-close-small{color: '.$original[$prefix . '_fbc_clr'].';}';
			}

			if( isset( $original[$prefix . '_fbc_hclr'] ) ) {
				$css .= '.fancybox' . $selc . ' .fancybox-content .oe-aspect-ratio .fancybox-close-small:hover{color: '.$original[$prefix . '_fbc_hclr'].';}';
			}

			if( isset( $original[$prefix . '_fbc_size'] ) ) {
				$css .= '.fancybox' . $selc . ' .fancybox-content .oe-aspect-ratio .fancybox-close-small svg{width: '.$original[$prefix . '_fbc_size'].'px;height:'.$original[$prefix . '_fbc_size'].'px;}';
			}

			if( isset( $original[$prefix . '_fbc_wh'] ) ) {
				$css .= '.fancybox' . $selc . ' .fancybox-content .oe-aspect-ratio .fancybox-close-small{width: '.$original[$prefix . '_fbc_wh'].'px;height:'.$original[$prefix . '_fbc_wh'].'px;}';
			}

			if( isset( $original[$prefix . '_fbc_postop'] ) ) {
				$css .= '.fancybox' . $selc . ' .fancybox-content .oe-aspect-ratio .fancybox-close-small{top: '.$original[$prefix . '_fbc_postop'].'px;}';
			}

			if( isset( $original[$prefix . '_fbc_posright'] ) ) {
				$css .= '.fancybox' . $selc . ' .fancybox-content .oe-aspect-ratio .fancybox-close-small{right: '.$original[$prefix . '_fbc_posright'].'px;}';
			}
		}

		return $css;
	}
}

new OEVideo();
