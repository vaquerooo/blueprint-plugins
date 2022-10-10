<?php
namespace Oxygen\OxyExtended;

use OxyExtended\Classes\OE_Helper;

/**
 * Scroll Image Element
 */
class OEScrollImage extends \OxyExtendedEl {

	public $css_added = false;
	private $si_js_code = array();

	/**
	 * Retrieve Scroll Image element name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'Scroll Image', 'oxy-extended' );
	}

	/**
	 * Retrieve Scroll Image element slug.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element slug.
	 */
	public function slug() {
		return 'oe_scroll_image';
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
	 * Retrieve Scroll Image element icon.
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
		$image = $this->addCustomControl(
			"<div class='oxygen-control'>
				<div class='oxygen-file-input'
				ng-class=\"{'oxygen-option-default':iframeScope.isInherited(iframeScope.component.active.id, 'oe_image')}\">
					<input type=\"text\" spellcheck=\"false\" ng-model=\"iframeScope.component.options[iframeScope.component.active.id]['model']['oe_image']\" ng-model-options=\"{ debounce: 10 }\"
						ng-change=\"iframeScope.setOption(iframeScope.component.active.id,'oe_scroll_image','oe_image');\">
					<div class=\"oxygen-file-input-browse\"
						data-mediaTitle=\"Select Image\" 
						data-mediaButton=\"Select Image\"
						data-mediaMultiple=\"false\"
						data-mediaProperty=\"oe_image\"
						data-mediaType=\"mediaUrl\">" . __( 'browse', 'oxy-extended' ) . '
					</div>
				</div>
			</div>',
			'oe_image'
		);
		$image->setParam( 'heading', __( 'Image', 'oxy-extended' ) );
		$image->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$image->rebuildElementOnChange();

		$image_size = $this->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Image Size', 'oxy-extended' ),
				'slug'    => 'image_size',
				'value'   => OE_Helper::get_image_sizes(),
				'default' => 'full',
				'css'     => false,
			)
		);
		$image_size->rebuildElementOnChange();

		$this->addStyleControl(
			array(
				'control_type' => 'slider-measurebox',
				'name'         => __( 'Image Height', 'oxy-extended' ),
				'slug'         => 'image_height',
				'selector'     => '.oe-image-scroll-container',
				'property'     => 'height',
			)
		)->setRange( '1', '800', '1' )->setUnits( 'px', 'px,vh' )->setValue( '300' );

		$image_url = $this->addCustomControl(
			'<div class="oxygen-file-input oxygen-option-default" ng-class="{\'oxygen-option-default\':iframeScope.isInherited(iframeScope.component.active.id, \'oxy-oe_scroll_image_image_url\')}">
				<input type="text" spellcheck="false" ng-model="iframeScope.component.options[iframeScope.component.active.id][\'model\'][\'oxy-oe_scroll_image_image_url\']" ng-model-options="{ debounce: 10 }" ng-change="iframeScope.setOption(iframeScope.component.active.id, iframeScope.component.active.name,\'oxy-oe_scroll_image_image_url\');iframeScope.checkResizeBoxOptions(\'oxy-oe_scroll_image_image_url\'); " class="ng-pristine ng-valid ng-touched" placeholder="http://">
				<div class="oxygen-set-link" data-linkproperty="url" data-linktarget="target" onclick="processOELink(\'oxy-oe_scroll_image_image_url\')">set</div>
				<div class="oxygen-dynamic-data-browse" ctdynamicdata data="iframeScope.dynamicShortcodesLinkMode" callback="iframeScope.oeDynamicSILUrl">data</div>
			</div>
			',
			'image_url',
		);
		$image_url->setParam( 'heading', __( 'URL', 'oxy-extended' ) );
		$image_url->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
        
        
		/**
		 * Icon Section
		 * -------------------------------------------------
		 */
		$icon = $this->addControlSection( 'icon_section', __( 'Icon', 'oxy-extended' ), 'assets/icon.png', $this );

		$cover_icon = $icon->addOptionControl(
			array(
				'type'    => 'icon_finder',
				'name'    => __( 'Icon', 'oxy-extended' ),
				'slug'    => 'cover_icon',
				'default' => '',
				'css'     => false,
			)
		);
		$cover_icon->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']" );
		$cover_icon->rebuildElementOnChange();
        
		$icon_color = $icon->addStyleControl(
			array(
				'name'         => __( 'Icon Color', 'oxy-extended' ),
				'selector'     => '.oe-image-scroll-icon svg',
				'value'        => '',
				'property'     => 'fill',
				'control_type' => 'colorpicker',
			)
		);
		$icon_color->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );

		$icon->addStyleControl(
			array(
				'name'          => __( 'Icon Size', 'oxy-extended' ),
				'slug'          => 'icon_size',
				'selector'      => 'svg.oe-image-scroll-svg',
				'control_type'  => 'slider-measurebox',
				'value'         => '30',
				'property'      => 'width|height',
			)
		)
		->setRange( 20, 80, 5 )
		->setUnits( 'px', 'px' );

		/**
		 * Settings Section
		 * -------------------------------------------------
		 */
		$settings = $this->addControlSection( 'settings', __( 'Settings', 'oxy-extended' ), 'assets/icon.png', $this );

		$trigger = $settings->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Trigger', 'oxy-extended' ),
				'slug'    => 'loop',
				'value'   => array(
					'hover'  => __( 'Hover', 'oxy-extended' ),
					'scroll' => __( 'Mouse Scroll', 'oxy-extended' ),
				),
				'default' => 'hover',
			)
		);
		$trigger->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );

		$settings->addStyleControl(
			array(
				'control_type' => 'slider-measurebox',
				'name'         => __( 'Scroll Speed', 'oxy-extended' ),
				'slug'         => 'scroll_speed',
				'selector'     => '.oe-image-scroll-image img',
				'property'     => 'transition-duration',
			)
		)->setRange( '1', '20', '1' )->setUnits( 's', 'sec' )->setValue( '3' );

		$scroll_direction = $settings->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Scroll Direction', 'oxy-extended' ),
				'slug'    => 'scroll_direction',
				'value'   => array(
					'horizontal' => __( 'Horizontal', 'oxy-extended' ),
					'vertical'   => __( 'Vertical', 'oxy-extended' ),
				),
				'default' => 'vertical',
			)
		);
		$scroll_direction->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
        $scroll_direction->rebuildElementOnChange();

		$reverse = $settings->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Reverse Direction', 'oxy-extended' ),
				'slug'    => 'reverse',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'no',
			)
		);
		$reverse->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
        $reverse->rebuildElementOnChange();

		/**
		 * Style Section
		 * -------------------------------------------------
		 */
		$scroll_image_style = $this->addControlSection( 'styles', __( 'Style', 'oxy-extended' ), 'assets/icon.png', $this );

		$overlay_style = $scroll_image_style->addControlSection( 'overlay_style', __( 'Overlay', 'oxy-extended' ), 'assets/icon.png', $this );

		$overlay = $overlay_style->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Overlay', 'oxy-extended' ),
				'slug'    => 'overlay',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'no',
			)
		);
		$overlay->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$overlay->rebuildElementOnChange();

		$overlay_color = $overlay_style->addStyleControl(
			array(
				'name'     => __( 'Background Color', 'oxy-extended' ),
				'selector' => '.oe-image-scroll-overlay',
				'value'    => '',
				'property' => 'background-color',
			)
		);
		$overlay_color->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_scroll_image_overlay'] == 'yes'" );

		$scroll_image_style->borderSection( __( 'Image Border', 'oxy-extended' ), '.oe-image-scroll-wrap', $this );

		/*        $scroll_image_style->addStyleControl(
			array(
				'control_type' 		=> 'slider-measurebox',
				'name' 				=> __('Image Border Radius', 'oxy-extended'),
				'slug' 				=> 'image_border_radius',
				'selector'			=> '.oe-image-scroll-wrap',
				'property' 			=> 'border-radius',
			)
		)->setRange('0','1000','10')->setUnits('px','px')->setValue('1000');*/

	}

	/**
	 * Render Scroll Image element output on the frontend.
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
		$uid = str_replace( '-', '', $options['selector'] ) . get_the_ID();
        $image = $options['oe_image'];
        $image_id = attachment_url_to_postid($image);
        $image_size = $options['image_size'];

		if ( ! isset( $image ) ) {
			return;
		}
		if ( isset( $options['image_url'] ) ) {
			$link_url = $options['image_url'];
		}
        $image_src = wp_get_attachment_image_src($image_id, $image_size);
        list($width, $height) = getimagesize($image);
		?>
		<div class="oe-image-scroll-wrap">
			<div class="oe-image-scroll-container">
				<?php if ( ! empty( $options['cover_icon'] ) ) { ?>
					<div class="oe-image-scroll-content">
						<span class="oe-image-scroll-icon">
							<?php
								global $oxygen_svg_icons_to_load;

								$oxygen_svg_icons_to_load[] = $options['cover_icon'];

								echo '<svg id="svg' . esc_attr( $options['selector'] ) . '-icon" class="oe-image-scroll-svg"><use xlink:href="#' . $options['cover_icon'] . '"></use></svg>';
							?>
						</span>
					</div>
				<?php } ?>
				<div class="oe-image-scroll-image oe-image-scroll-<?php echo $options['scroll_direction']; ?>">
					<?php if ( $options['overlay'] == 'yes' ) { ?>
						<div class="oe-image-scroll-overlay oe-media-content">
					<?php } ?>
					<?php if ( ! empty( $link_url ) ) { ?>
							<a class="oe-image-scroll-link oe-media-content" href="<?php echo esc_url( $link_url ); ?>"></a>
					<?php } ?>
					<?php if ( 'yes' === $options['overlay'] ) { ?>
						</div> 
					<?php } ?>
					<?php echo '<img src="' . esc_url( $image_src[0] ) . '" width='. $width .' height='. $height .'>'; ?>
				</div>
			</div>
		</div>
		<?php
		ob_start();

		$this->oe_scroll_image_script( $options, $uid );

		$js = ob_get_clean();

		if ( isset( $_GET['oxygen_iframe'] ) || defined( 'OXY_ELEMENTS_API_AJAX' ) ) {
			$this->oesi_enqueue_scripts();
			$this->El->builderInlineJS( $js );
		} else {
			add_action( 'wp_footer', array( $this, 'oesi_enqueue_scripts' ) );

			$this->si_js_code[] = $js;
			$this->El->footerJS( join( '', $this->si_js_code ) );
		}
	}

	public function oe_scroll_image_script( $options, $uid ) {
		?>
		jQuery(document).ready(function($) {
			var scrollElement    = $('.oe-image-scroll-container'),
				scrollOverlay    = scrollElement.find('.oe-image-scroll-overlay'),
				scrollVertical   = scrollElement.find('.oe-image-scroll-vertical'),
				imageScroll      = $('.oe-image-scroll-image img'),
				direction        = '<?php echo $options['scroll_direction']; ?>',
				reverse			 = '<?php echo $options['reverse']; ?>',
				trigger			 = '<?php echo $options['loop']; ?>',
				transformOffset  = null;

			function startTransform() {
				imageScroll.css('transform', (direction === 'vertical' ? 'translateY' : 'translateX') + '( -' +  transformOffset + 'px)');
			}

			function endTransform() {
				imageScroll.css('transform', (direction === 'vertical' ? 'translateY' : 'translateX') + '(0px)');
			}

			function setTransform() {
				if( direction === 'vertical' ) {
					transformOffset = imageScroll.height() - scrollElement.height();
				} else {
					transformOffset = imageScroll.width() - scrollElement.width();
				}
			}

			if ( trigger === 'scroll' ) {
				scrollElement.addClass('oe-container-scroll');
				if ( direction === 'vertical' ) {
					scrollVertical.addClass('oe-image-scroll-ver');
				} else {
					scrollElement.imagesLoaded(function() {
						scrollOverlay.css( { 'width': imageScroll.width(), 'height': imageScroll.height() } );
					});
				}
			} else {
				if ( reverse === 'yes' ) {
					scrollElement.imagesLoaded(function() {
						scrollElement.addClass('oe-container-scroll-instant');
						setTransform();
						startTransform();
					});
				}
				if ( direction === 'vertical' ) {
					scrollVertical.removeClass('oe-image-scroll-ver');
				}
				scrollElement.mouseenter(function() {
					scrollElement.removeClass('oe-container-scroll-instant');
					setTransform();
					reverse === 'yes' ? endTransform() : startTransform();
				});

				scrollElement.mouseleave(function() {
					reverse === 'yes' ? startTransform() : endTransform();
				});
			}
		});
		<?php
	}

	public function oesi_enqueue_scripts() {
		wp_enqueue_script( 'imagesloaded' );
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
}

new OEScrollImage();
