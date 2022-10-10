<?php
namespace Oxygen\OxyExtended;

use OxyExtended\Classes\OE_Helper;

/**
 * Random Image Element
 */
class OERandomeImage extends \OxyExtendedEl {

	public $css_added = false;
	private $si_js_code = array();

	/**
	 * Retrieve Random Image element name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'Random Image', 'oxy-extended' );
	}

	/**
	 * Retrieve Random Image element slug.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element slug.
	 */
	public function slug() {
		return 'oe_random_image';
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
	 * Retrieve Random Image element icon.
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
		$wp_gallery = $this->addCustomControl(
			"<div class='oxygen-control'>
				<div class='oxygen-file-input'
				ng-class=\"{'oxygen-option-default':iframeScope.isInherited(iframeScope.component.active.id, 'oe_images')}\">
					<input type=\"text\" spellcheck=\"false\" ng-model=\"iframeScope.component.options[iframeScope.component.active.id]['model']['oe_images']\" ng-model-options=\"{ debounce: 10 }\"
						ng-change=\"iframeScope.setOption(iframeScope.component.active.id,'oe_gallery_slider','oe_images');\">
					<div class=\"oxygen-file-input-browse\"
						data-mediaTitle=\"Select Image\" 
						data-mediaButton=\"Select Image\"
						data-mediaMultiple=\"true\"
						data-mediaProperty=\"oe_images\"
						data-mediaType=\"mediaUrl\">" . __( 'browse', 'oxy-extended' ) . '
					</div>
				</div>
			</div>',
			'oe_images'
		);
		$wp_gallery->setParam( 'heading', __( 'Images', 'oxy-extended' ) );
		$wp_gallery->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$wp_gallery->rebuildElementOnChange();

		$image_size = $this->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Image Size', 'oxy-extended' ),
				'slug'    => 'image_size',
				'value'   => OE_Helper::get_image_sizes(),
				'default' => 'medium',
				'css'     => false,
			)
		);
		$image_size->rebuildElementOnChange();

		$image_url = $this->addCustomControl(
			'<div class="oxygen-file-input oxygen-option-default" ng-class="{\'oxygen-option-default\':iframeScope.isInherited(iframeScope.component.active.id, \'oxy-oe_random_image_image_url\')}">
				<input type="text" spellcheck="false" ng-model="iframeScope.component.options[iframeScope.component.active.id][\'model\'][\'oxy-oe_random_image_image_url\']" ng-model-options="{ debounce: 10 }" ng-change="iframeScope.setOption(iframeScope.component.active.id, iframeScope.component.active.name,\'oxy-oe_random_image_image_url\');iframeScope.checkResizeBoxOptions(\'oxy-oe_random_image_image_url\'); " class="ng-pristine ng-valid ng-touched" placeholder="http://">
				<div class="oxygen-set-link" data-linkproperty="url" data-linktarget="target" onclick="processOELink(\'oxy-oe_random_image_image_url\')">set</div>
				<div class="oxygen-dynamic-data-browse" ctdynamicdata data="iframeScope.dynamicShortcodesLinkMode" callback="iframeScope.oeDynamicRILUrl">data</div>
			</div>
			',
			'image_url',
		);
		$image_url->setParam( 'heading', __( 'URL', 'oxy-extended' ) );
		$image_url->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );

		$this->addOptionControl(
			array(
				'type'         => 'dropdown',
				'name'         => __( 'Image Caption', 'oxy-extended' ),
				'slug'         => 'caption',
				'value'        => array(
					''             => __( 'None', 'oxy-extended' ),
					'title'        => __( 'Title', 'oxy-extended' ),
					'caption'      => __( 'Caption', 'oxy-extended' ),
					'description'  => __( 'Description', 'oxy-extended' ),
				),
				'default' => '',
			)
		)->rebuildElementOnChange();

		$this->addOptionControl(
			array(
				'type'         => 'dropdown',
				'name'         => __( 'Image Caption', 'oxy-extended' ),
				'slug'         => 'caption_position',
				'value'        => array(
					'over_image'   => __( 'Over Image', 'oxy-extended' ),
					'below_image'  => __( 'Below Image', 'oxy-extended' ),
				),
				'default' => 'below_image',
			)
		)->rebuildElementOnChange();

		/**
		 * Image Style Section
		 * -------------------------------------------------
		 */
		$image_style = $this->addControlSection( 'image_style', __( 'Image Style', 'oxy-extended' ), 'assets/icon.png', $this );

		$image_style->addOptionControl(
			array(
				'type'         => 'radio',
				'name'         => __( 'Alignment', 'oxy-extended' ),
				'slug'         => 'image_align',
				'value'        => array(
					'left'     => __( 'Left', 'oxy-extended' ),
					'center'   => __( 'Center', 'oxy-extended' ),
					'right'    => __( 'Right', 'oxy-extended' ),
				),
				'default'      => 'center',
			)
		)->setValueCSS( array(
			'left'   => '.oe-random-image-container {
                            text-align: left;
                        }',
			'center' => '.oe-random-image-container {
                            text-align: center;
                        }',
			'right'  => '.oe-random-image-container {
                            text-align: right;
                        }',
		) );

		$image_style->addStyleControl(
			array(
				'control_type' => 'slider-measurebox',
				'name'         => __( 'Image Width', 'oxy-extended' ),
				'slug'         => 'image_width',
				'selector'     => '.oe-random-image',
				'property'     => 'width',
			)
		);

		$image_style->addStyleControl(
			array(
				'control_type' => 'slider-measurebox',
				'name'         => __( 'Image Max Width', 'oxy-extended' ),
				'slug'         => 'image_max_width',
				'selector'     => '.oe-random-image',
				'property'     => 'max-width',
			)
		);

		$image_style->addStyleControl(
			array(
				'control_type' => 'slider-measurebox',
				'name'         => __( 'Image Height', 'oxy-extended' ),
				'slug'         => 'image_height',
				'selector'     => '.oe-random-image',
				'property'     => 'height',
			)
		)->setRange( '1', '800', '1' )->setUnits( 'px', 'px,vh' )->setValue( '300' );

		$image_style->addOptionControl(
			array(
				'type'         => 'dropdown',
				'name'         => __( 'Object Fit', 'oxy-extended' ),
				'slug'         => 'object_fit',
				'value'        => array(
					''         => __( 'Default', 'oxy-extended' ),
					'fill'     => __( 'Fill', 'oxy-extended' ),
					'cover'    => __( 'Cover', 'oxy-extended' ),
					'contain'  => __( 'Contain', 'oxy-extended' ),
				),
				'default' => '',
			)
		)->setValueCSS( array(
			'default' => '.oe-random-image {
                            object-fit: initial;
                        }',
			'fill'    => '.oe-random-image {
                            object-fit: fill;
                        }',
			'cover'   => '.oe-random-image {
                            object-fit: cover;
                        }',
			'contain' => '.oe-random-image {
                            object-fit: contain;
                        }',
		) );

		$image_style->borderSection( __( 'Border', 'oxy-extended' ), '.oe-random-image', $this );

		$overlay_style = $image_style->addControlSection( 'overlay_style', __( 'Overlay', 'oxy-extended' ), 'assets/icon.png', $this );

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
				'selector' => '.oe-random-image-overlay',
				'value'    => '',
				'property' => 'background-color',
			)
		);
		$overlay_color->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_random_image_overlay'] == 'yes'" );

		/**
		 * Caption Style section
		 * -------------------------------------------------
		 */
		$caption_style = $this->addControlSection( 'caption_style', __( 'Caption Style', 'oxy-extended' ), 'assets/icon.png', $this );

		$caption_style->addOptionControl(
			array(
				'type'         => 'radio',
				'name'         => __( 'Vertical Alignment', 'oxy-extended' ),
				'slug'         => 'caption_vertical_align',
				'value'        => array(
					'top'      => __( 'Top', 'oxy-extended' ),
					'middle'   => __( 'Middle', 'oxy-extended' ),
					'bottom'   => __( 'Bottom', 'oxy-extended' ),
				),
				'default'      => 'bottom',
				'condition'    => 'caption_position=over_image',
			)
		)->setValueCSS( array(
			'top'    => '.oe-random-image-content {
                            justify-content: flex-start;
                        }',
			'middle' => '.oe-random-image-content {
                            justify-content: center;
                        }',
			'bottom' => '.oe-random-image-content {
                            justify-content: flex-end;
                        }',
		) );

		$caption_style->addOptionControl(
			array(
				'type'         => 'radio',
				'name'         => __( 'Horizontal Alignment', 'oxy-extended' ),
				'slug'         => 'caption_horizontal_align',
				'value'        => array(
					'left'     => __( 'Left', 'oxy-extended' ),
					'center'   => __( 'Center', 'oxy-extended' ),
					'right'    => __( 'Right', 'oxy-extended' ),
					'justify'  => __( 'Justify', 'oxy-extended' ),
				),
				'default'      => 'left',
				'condition'    => 'caption_position=over_image',
			)
		)->setValueCSS( array(
			'left'      => ' .oe-random-image-content {
                            align-items: flex-start;
                          }',
			'center'    => ' .oe-random-image-content {
                            align-items: center;
                          }',
			'right'    => ' .oe-random-image-content {
                            align-items: flex-end;
                          }',
			'justify'    => ' .oe-random-image-content {
                            align-items: stretch;
                          }',
		) );

		$caption_style->addOptionControl(
			array(
				'type'         => 'radio',
				'name'         => __( 'Text Alignment', 'oxy-extended' ),
				'slug'         => 'caption_align',
				'value'        => array(
					'left'     => __( 'Left', 'oxy-extended' ),
					'center'   => __( 'Center', 'oxy-extended' ),
					'right'    => __( 'Right', 'oxy-extended' ),
				),
				'default'      => 'center',
				'condition'    => 'caption_position=below_image',
			)
		)->setValueCSS( array(
			'left'      => ' .oe-random-image-content {
                            text-align: left;
                          }',
			'center'    => ' .oe-random-image-content {
                            text-align: center;
                          }',
			'right'    => ' .oe-random-image-content {
                            text-align: right;
                          }',
		) );

		$caption_style->addStyleControl(
			array(
				'name'     => __( 'Background Color', 'oxy-extended' ),
				'selector' => '.oe-random-image-caption',
				'value'    => '',
				'property' => 'background-color',
			)
		);

		$caption_style->addStyleControl(
			array(
				'name'     => __( 'Background Hover Color', 'oxy-extended' ),
				'selector' => '.oe-random-image-wrap:hover .oe-random-image-caption',
				'value'    => '',
				'property' => 'background-color',
			)
		);

		$caption_style->addStyleControl(
			array(
				'name'     => __( 'Text Hover Color', 'oxy-extended' ),
				'selector' => '.oe-random-image-wrap:hover .oe-random-image-caption',
				'value'    => '',
				'property' => 'color',
			)
		);

		$caption_style->addStyleControl(
			array(
				'name'     => __( 'Border Hover Color', 'oxy-extended' ),
				'selector' => '.oe-random-image-wrap:hover .oe-random-image-caption',
				'value'    => '',
				'property' => 'border-color',
			)
		);

		$caption_style->borderSection( __( 'Border', 'oxy-extended' ), '.oe-random-image-caption', $this );

		$caption_style->typographySection( __( 'Typography', 'oxy-extended' ), '.oe-random-image-caption', $this );

		$caption_style->addPreset(
			'padding',
			'oe_caption_padding',
			__( 'Padding', 'oxy-extended' ),
			'.oe-random-image-caption'
		)->whiteList();

		$caption_style->addPreset(
			'margin',
			'oe_caption_margin',
			__( 'Margin', 'oxy-extended' ),
			'.oe-random-image-caption'
		)->whiteList();
	}

	protected function render_image_caption( $options, $id ) {

		if ( '' === $options['caption'] ) {
			return '';
		}

		$caption_type = $options['caption'];

		$attachment = get_post( $id );

		$caption = '';

		if ( $caption_type == 'title' ) {
			$caption = $attachment->post_title;
		} elseif ( $caption_type == 'caption' ) {
			$caption = $attachment->post_excerpt;
		} elseif ( $caption_type == 'description' ) {
			$caption = $attachment->post_content;
		}

		if ( $caption == '' ) {
			return '';
		}

		ob_start();

		echo $caption;

		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	/**
	 * Render Random Image element output on the frontend.
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

		if ( ! isset( $options['oe_images'] ) ) {
			return;
		}

		$images = explode( ',', $options['oe_images'] );

		if ( $images ) {

			$image_size = isset( $options['image_size'] ) ? $options['image_size'] : 'full';

			$count = count( $images );
			$index = ( $count > 1 ) ? rand( 0, $count - 1 ) : 0;
			$id    = $images[ $index ];

			$image_data = wp_get_attachment_image_src( $id, $image_size );

			$image_url = $image_data[0];
			$attachment  = get_post( $id );
		}

		if ( isset( $options['image_url'] ) ) {
			$link_url = $options['image_url'];
		}
		$has_caption = isset( $options['caption'] ) ? $options['caption'] : '';
		$figure = 'wp-caption oe-random-image-figure';
		if ( 'over_image' === $options['caption_position'] ) {
			$figure .= ' oe-random-image-caption-over';
		}
		?>
		<div class="oe-random-image-container">
			<div class="oe-random-image-wrap">
				<?php if ( $has_caption ) { ?>
				<figure class="<?php echo $figure; ?>">
				<?php } ?>
					<?php if ( $options['overlay'] == 'yes' ) { ?>
						<div class="oe-random-image-overlay oe-media-overlay">
					<?php } ?>
					<?php if ( 'yes' === $options['overlay'] ) { ?>
						</div> 
					<?php } ?>
					<?php if ( ! empty( $link_url ) ) { ?>
						<a class="oe-random-image-link oe-media-overlay" href="<?php echo esc_url( $link_url ); ?>"></a>
					<?php } ?>
					<?php echo '<img class="oe-random-image" src="' . esc_url( $image_url ) . '">'; ?>

					<?php if ( $has_caption ) { ?>
						<?php if ( 'over_image' === $options['caption_position'] ) { ?>
					<div class="oe-random-image-content oe-media-content">
					<?php } ?>
						<figcaption class="widget-image-caption wp-caption-text oe-random-image-caption">
							<?php echo $this->render_image_caption( $options, $attachment ); ?>
						</figcaption>
						<?php if ( 'over_image' === $options['caption_position'] ) { ?>
					</div>
					<?php } ?>
				</figure>
				<?php } ?>
			</div>
		</div>
		<?php
	}

	public function customCSS( $original, $selector ) {
		$css = '';

		if ( ! $this->css_added ) {
			$this->css_added = true;
			$css .= file_get_contents( __DIR__ . '/' . basename( __FILE__, '.php' ) . '.css' );
		}

		$prefix = $this->El->get_tag();
		$css .= '';

		return $css;
	}
}

new OERandomeImage();
