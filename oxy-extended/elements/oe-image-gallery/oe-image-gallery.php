<?php
namespace Oxygen\OxyExtended;

use OxyExtended\Classes\OE_Helper;

/**
 * Image Gallery Element
 */
class OEImageGallery extends \OxyExtendedEl {

	public $has_js = true;
	public $css_added = false;
	public $js_added = false;
	private $slide_js_code = array();
	/**
	 * Retrieve Image Gallery element name.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'Image Gallery', 'oxy-extended' );
	}

	/**
	 * Retrieve Image Gallery element slug.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return string Element slug.
	 */
	public function slug() {
		return 'oe_image_gallery';
	}

	/**
	 * Element Subsection
	 *
	 * @return string
	 */
	public function oxyextend_button_place() {
		return 'general';
	}

	public function class_names() {
		return array( 'oxy-extended-element oe-image-gallery-wrapper' );
	}
	/**
	 * Retrieve Image Gallery element icon.
	 *
	 * @since 1.1.0
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
	 * @since 1.1.0
	 * @access public
	 *
	 * @return tag
	 */
	public function tag() {
		return 'div';
	}

	public function controls() {
		$this->gallery_controls();
		$this->filters_controls();
		$this->layout_settings_controls();
		$this->navigation_arrows_controls();
		$this->caption_controls();
		$this->lightbox_controls();
	}

	public function gallery_controls() {
		$gallery_section = $this->addControlSection( 'gallery_section', __( 'Gallery', 'oxy-extended' ), 'assets/icon.png', $this );

		$gallery_source = $gallery_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Gallery Source', 'oxy-extended' ),
				'slug'      => 'oe_gallery_source',
				'value'     => array(
					'medialibrary' => __( 'Media Library', 'oxy-extended' ),
					'acf'          => __( 'ACF', 'oxy-extended' ),
					'acf_repeater' => __( 'ACF Repeater', 'oxy-extended' ),
					'woocommerce'  => __( 'WooCommerce', 'oxy-extended' ),
				),
				'default'   => 'medialibrary',
			)
		)->rebuildElementOnChange();

		$wp_gallery = $gallery_section->addCustomControl("
			<div class='oxygen-control'>
				<div class='oxygen-file-input'
				ng-class=\"{'oxygen-option-default':iframeScope.isInherited(iframeScope.component.active.id, 'oe_gallery_images')}\">
					<input type=\"text\" spellcheck=\"false\" ng-model=\"iframeScope.component.options[iframeScope.component.active.id]['model']['oe_gallery_images']\" ng-model-options=\"{ debounce: 10 }\"
						ng-change=\"iframeScope.setOption(iframeScope.component.active.id,'oe_image_gallery','oe_gallery_images');\">
					<div class=\"oxygen-file-input-browse\"
						data-mediaTitle=\"Select Images\" 
						data-mediaButton=\"Select Images\"
						data-mediaMultiple=\"true\"
						data-mediaProperty=\"oe_gallery_images\"
						data-mediaType=\"gallery\">" . __( 'browse', 'oxy-extended' ) . '
					</div>
				</div>
			</div>',
			'oe_gallery_images'
		);

		$wp_gallery->setParam( 'heading', 'Image IDs' );
		$wp_gallery->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_image_gallery_oe_gallery_source']=='medialibrary'" );

		$images_source = $gallery_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Images Source', 'oxy-extended' ),
				'slug'      => 'acf_gallery_source',
				'value'     => array(
					'same'  => __( 'Same Post/Page', 'oxy-extended' ),
					'other' => __( 'Other', 'oxy-extended' ),
				),
				'default'   => 'same',
			)
		);

		$images_source->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_image_gallery_oe_gallery_source']!='medialibrary'" );

		$page_id = $gallery_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Enter Post/Page ID', 'oxy-extended' ),
				'slug'      => 'page_id',
			)
		);

		$page_id->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_image_gallery_oe_gallery_source']!='medialibrary'&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_image_gallery_acf_gallery_source']=='other'" );
		$page_id->rebuildElementOnChange();

		$gf_name = $gallery_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Gallery Field Name', 'oxy-extended' ),
				'slug'      => 'acf_field_name',
			)
		);

		$gf_name->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_image_gallery_oe_gallery_source']=='acf'" );
		$gf_name->rebuildElementOnChange();

		//* Repeater fields
		$acf_rep_field_name = $gallery_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Repeater Field Name', 'oxy-extended' ),
				'slug'      => 'repeater_field_name',
			)
		);

		$acf_rep_field_name->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_image_gallery_oe_gallery_source']=='acf_repeater'" );
		$acf_rep_field_name->rebuildElementOnChange();

		$acf_rep_img_field_name = $gallery_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Gallery Field Name', 'oxy-extended' ),
				'slug'      => 'repeater_img_field_name',
			)
		);

		$acf_rep_img_field_name->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_image_gallery_oe_gallery_source']=='acf_repeater'" );
		$acf_rep_img_field_name->rebuildElementOnChange();

		$acf_rep_title_field_name = $gallery_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Filter Field Name', 'oxy-extended' ),
				'slug'      => 'repeater_title_field_name',
			)
		);

		$acf_rep_title_field_name->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_image_gallery_oe_gallery_source']=='acf_repeater'" );
		$acf_rep_title_field_name->rebuildElementOnChange();

		$acf_rep_desc_field_name = $gallery_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Description Field Name', 'oxy-extended' ),
				'slug'      => 'repeater_desc_field_name',
			)
		);

		$acf_rep_desc_field_name->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_image_gallery_oe_gallery_source']=='acf_repeater'" );
		$acf_rep_desc_field_name->rebuildElementOnChange();

		$image_size = $gallery_section->addOptionControl(
			array(
				'type'      => 'dropdown',
				'name'      => __( 'Thumbnail Resolution', 'oxy-extended' ),
				'slug'      => 'oe_gallery_image_size',
				'value'     => OE_Helper::get_image_sizes(),
				'default'   => 'full',
			)
		);
		$image_size->rebuildElementOnChange();

		$order = $gallery_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Order', 'oxy-extended' ),
				'slug'      => 'oe_gallery_order',
				'value'     => [
					'default' => __( 'Default', 'oxy-extended' ),
					'rand'    => __( 'Random', 'oxy-extended' ),
				],
				'default'   => 'default',
			)
		)->rebuildElementOnChange();

		$image_link_to = $gallery_section->addOptionControl(
			array(
				'type'      => 'dropdown',
				'name'      => __( 'Link To', 'oxy-extended' ),
				'slug'      => 'oe_gallery_image_link_to',
				'value'     => [
					'none'       => __( 'None', 'oxy-extended' ),
					'file'       => __( 'Media File', 'oxy-extended' ),
					'attachment' => __( 'Attachment Page', 'oxy-extended' ),
				],
				'default'   => 'none',
			)
		);
		$image_link_to->rebuildElementOnChange();

		$gallery_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Enable Lightbox', 'oxy-extended' ),
				'slug'      => 'oe_gallery_open_lightbox',
				'value'     => array(
					'no'  => __( 'No', 'oxy-extended' ),
					'yes' => __( 'Yes', 'oxy-extended' ),
				),
				'default'   => 'no',
				'condition' => 'oe_gallery_image_link_to=file',
			)
		)->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_image_gallery_oe_gallery_image_link_to']=='file'" );
	}

	public function filters_controls() {
		$gallery_filters = $this->addControlSection( 'filters_section', __( 'Filters', 'oxy-extended' ), 'assets/icon.png', $this );

		$gallery_filters->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Enable Filters', 'oxy-extended' ),
			'slug'      => 'oe_gallery_filters_enable',
			'default'   => 'yes',
			'value'     => [
				'no'  => __( 'No', 'oxy-extended' ),
				'yes' => __( 'Yes', 'oxy-extended' ),
			],
			'condition' => 'oe_gallery_source=acf_repeater',
		])->rebuildElementOnChange();

		/* $gallery_filters->addStyleControls([
			[
				'name'          => __( 'Filters Gap', 'oxy-extended' ),
				'slug'          => 'filters_gap',
				'selector'      => '.oe-gallery-filters .oe-gallery-filter',
				'control_type'  => 'slider-measurebox',
				'value'         => '5',
				'property'      => 'margin-left|margin-right',
				'unit'          => 'px',
			],
		]); */

		$gallery_filters->addPreset(
			'margin',
			'oe_filters_margin',
			__( 'Margin', 'oxy-extended' ),
			'.oe-gallery-filters .oe-gallery-filter'
		)->whiteList();

		$gallery_filters->typographySection( __( 'Typography', 'oxy-extended' ), '.oe-gallery-filter', $this );
	}

	public function layout_settings_controls() {
		$layout_section = $this->addControlSection( 'layout_section', __( 'Layout', 'oxy-extended' ), 'assets/icon.png', $this );

		$layout = $layout_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Layout', 'oxy-extended' ),
				'slug'      => 'oe_gallery_layout',
				'value'     => [
					'grid'      => __( 'Grid', 'oxy-extended' ),
					'masonry'   => __( 'Masonry', 'oxy-extended' ),
					'justified' => __( 'Justified', 'oxy-extended' ),
				],
				'default'   => 'grid',
			)
		)->rebuildElementOnChange();

		$layout = $layout_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Layout', 'oxy-extended' ),
				'slug'      => 'oe_justified_gallery_last_row',
				'value'     => [
					'justify'   => __( 'Justify', 'oxy-extended' ),
					'nojustify' => __( 'No Justify', 'oxy-extended' ),
					'hide'      => __( 'Hide', 'oxy-extended' ),
				],
				'default'   => 'justify',
				'condition' => 'oe_gallery_layout=justified',
			)
		)->rebuildElementOnChange();

		$layout_section->addOptionControl(
			array(
				'type'      => 'slider-measurebox',
				'name'      => __( 'Row Height', 'oxy-extended' ),
				'slug'      => 'oe_justified_gallery_row_height',
				'condition' => 'oe_gallery_layout=justified',
			)
		)
		->setUnits( 'px', 'px' )
		->setRange( '0', '500', '50' )
		->setValue( '120' )
		->rebuildElementOnChange();

		$aspect_ratio = $layout_section->addOptionControl(
			array(
				'type'    => 'textfield',
				'name'    => __( 'Aspect Ratio', 'oxy-extended' ),
				'slug'    => 'oe_gallery_aspect_ratio',
				'value'   => '16:9',
				'default' => '16:9',
			)
		)->rebuildElementOnChange();

		$images_per_row = $layout_section->addOptionControl(
			array(
				'type'    => 'textfield',
				'name'    => __( 'Images Per Row', 'oxy-extended' ),
				'slug'    => 'oe_gallery_images_per_row',
				'value'   => '4',
				'default' => '4',
			)
		)->rebuildElementOnChange();

		$image_fit = $layout_section->addControl( 'buttons-list', 'image_fit', __( 'Image Fit', 'oxy-extended' ) );
		$image_fit->setValue( array( 'Cover', 'Contain', 'Auto' ) );
		$image_fit->setValueCSS( array(
			'Cover'     => '.oe-image-gallery:not(.oe-image-gallery-justified) .oe-image-gallery-thumbnail {background-size: cover;}',
			'Contain'   => '.oe-image-gallery:not(.oe-image-gallery-justified) .oe-image-gallery-thumbnail {background-size: contain;}',
			'Auto'      => '.oe-image-gallery:not(.oe-image-gallery-justified) .oe-image-gallery-thumbnail {background-size: auto;}',
		));
		$image_fit->setDefaultValue( 'Cover' );
		$image_fit->whiteList();

		$tilt_section = $layout_section->addControlSection( 'tilt_section', __( 'Tilt', 'oxy-extended' ), 'assets/icon.png', $this );

		$tilt_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Enable Tilt Effect', 'oxy-extended' ),
				'slug'      => 'oe_gallery_tilt_enable',
				'value'     => array(
					'no'  => __( 'No', 'oxy-extended' ),
					'yes' => __( 'Yes', 'oxy-extended' ),
				),
				'default'   => 'no',
			)
		);

		$tilt_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Axis', 'oxy-extended' ),
				'slug'      => 'oe_gallery_tilt_axis',
				'value'     => [
					'both' => __( 'Both', 'oxy-extended' ),
					'x'    => __( 'X Axis', 'oxy-extended' ),
					'y'    => __( 'Y Axis', 'oxy-extended' ),
				],
				'default'   => 'both',
				'condition' => 'oe_gallery_tilt_enable=yes',
			)
		)
		->setUnits( '%', '%' );

		$tilt_section->addOptionControl(
			array(
				'type'  => 'slider-measurebox',
				'name'          => __( 'Amount', 'oxy-extended' ),
				'slug'      => 'oe_gallery_tilt_amount',
				'condition' => 'oe_gallery_tilt_enable=yes',
			)
		)
		->setUnits( '', '' )
		->setRange( '10', '50', '1' )
		->setValue( '20' )
		->rebuildElementOnChange();

		$tilt_section->addOptionControl(
			array(
				'type'  => 'slider-measurebox',
				'name'          => __( 'Scale', 'oxy-extended' ),
				'slug'          => 'oe_gallery_tilt_scale',
				'condition' => 'oe_gallery_tilt_enable=yes',
			)
		)
		->setUnits( '', '' )
		->setRange( '1', '1.4', '0.01' )
		->setValue( '1.06' )
		->rebuildElementOnChange();

		/* $tilt_section->addStyleControl(
			array(
				'control_type'  => 'slider-measurebox',
				'name'          => __( 'Depth', 'oxy-extended' ),
				'slug'          => 'oe_gallery_tilt_caption_depth',
				'selector'      => '.oe-grid-item .oe-gallery-image-content',
				'property'      => 'transform',
				'condition'     => 'oe_gallery_tilt_enable=yes',
			)
		)
		->setUnits( 'px', 'px' )
		->setRange( '0', '100', '1' )
		->setValue( '20' )
		->rebuildElementOnChange(); */

		$tilt_section->addOptionControl(
			array(
				'type'  => 'slider-measurebox',
				'name'          => __( 'Speed', 'oxy-extended' ),
				'slug'          => 'oe_gallery_tilt_speed',
				'condition' => 'oe_gallery_tilt_enable=yes',
			)
		)
		->setUnits( '', '' )
		->setRange( '100', '1000', '20' )
		->setValue( '800' )
		->rebuildElementOnChange();
	}

	public function navigation_arrows_controls() {
		$link_icon_section = $this->addControlSection( 'link_icon_section', __( 'Link Icon', 'oxy-extended' ), 'assets/icon.png', $this );

		$link_icon = $link_icon_section->addOptionControl(
			array(
				'type' => 'icon_finder',
				'name' => __( 'Link Icon', 'oxy-extended' ),
				'slug' => 'oe_gallery_link_icon',
			)
		);
		$link_icon->rebuildElementOnChange();

		$arrow_on_hover = $link_icon_section->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Show on Hover', 'oxy-extended' ),
			'slug'      => 'oe_gallery_link_icon_on_hover',
			'default'   => 'no',
			'value'     => [
				'no'  => __( 'No', 'oxy-extended' ),
				'yes' => __( 'Yes', 'oxy-extended' ),
			],
		]);
		$arrow_on_hover->setParam( 'description', 'Preview is disable for builder editor.' );
		$arrow_on_hover->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_image_gallery_oe_gallery_image_link_to']!='none'" );
		$arrow_on_hover->rebuildElementOnChange();

		$link_icon_style = $link_icon_section->addControlSection( 'link_icon_style', __( 'Color & Size', 'oxy-extended' ), 'assets/icon.png', $this );

		$link_icon_style->addStyleControls([
			[
				'name'          => __( 'Size', 'oxy-extended' ),
				'slug'          => 'arrows_size',
				'selector'      => '.oe-image-gallery .oe-link-icon svg',
				'control_type'  => 'slider-measurebox',
				'value'         => '25',
				'property'      => 'width|height',
				'unit'          => 'px',
			],
			[
				'selector'      => '.oe-image-gallery .oe-link-icon',
				'property'      => 'background-color',
				'slug'          => 'arrows_bg_color',
			],
			[
				'name'          => __( 'Hover Background Color', 'oxy-extended' ),
				'selector'      => '.oe-image-gallery .oe-link-icon:hover',
				'property'      => 'background-color',
				'slug'          => 'arrows_bg_color_hover',
			],
			[
				'name'          => __( 'Color', 'oxy-extended' ),
				'selector'      => '.oe-image-gallery .oe-link-icon svg',
				'property'     => 'fill',
				'control_type' => 'colorpicker',
			],
			[
				'name'          => __( 'Hover Color', 'oxy-extended' ),
				'selector'      => '.oe-image-gallery .oe-link-icon:hover svg',
				'property'     => 'fill',
				'control_type' => 'colorpicker',
			],
		]);

		$link_icon_style->addPreset(
			'padding',
			'arrows_padding',
			__( 'Padding', 'oxy-extended' ),
			'.oe-link-icon'
		)->whiteList();

		$link_icon_border = $link_icon_section->borderSection( __( 'Border', 'oxy-extended' ), '.oe-image-gallery .oe-link-icon', $this );

		$link_icon_section->boxShadowSection( __( 'Shadow', 'oxy-extended' ), '.oe-image-gallery .oe-link-icon', $this );
	}

	public function caption_controls() {
		$caption = $this->addControlSection( 'caption_section', __( 'Caption', 'oxy-extended' ), 'assets/icon.png', $this );

		$show_caption = $caption->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Show Caption', 'oxy-extended' ),
				'slug'      => 'show_caption',
				'value'     => array(
					'no'  => __( 'No', 'oxy-extended' ),
					'yes' => __( 'Yes', 'oxy-extended' ),
				),
				'default'   => 'no',
			)
		);
		$show_caption->rebuildElementOnChange();

		$caption_description = $caption->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Show Description', 'oxy-extended' ),
				'slug'      => 'show_caption_description',
				'value'     => array(
					'no'  => __( 'No', 'oxy-extended' ),
					'yes' => __( 'Yes', 'oxy-extended' ),
				),
				'default'   => 'no',
			)
		);
		$caption_description->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_image_gallery_oe_gallery_source']!='acf_repeater'&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_image_gallery_show_caption']=='yes'" );
		$caption_description->rebuildElementOnChange();

		$caption_position = $caption->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Caption Position', 'oxy-extended' ),
				'slug'    => 'caption_position',
				'value'   => array(
					'over_image'     => __( 'Over Image', 'oxy-extended' ),
					'below_image' => __( 'Below Image', 'oxy-extended' ),
				),
				'default' => 'over_image',
			)
		);
		$caption_position->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_image_gallery_show_caption']=='yes'" );
		$caption_position->rebuildElementOnChange();

		$caption_vertical_align = $caption->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Vertical Align', 'oxy-extended' ),
				'slug'      => 'caption_vertical_align',
				'value'     => array(
					'top'    => __( 'Top', 'oxy-extended' ),
					'center' => __( 'Center', 'oxy-extended' ),
					'bottom' => __( 'Bottom', 'oxy-extended' ),
				),
				'default'   => 'bottom',
				'condition' => 'show_caption=yes&&caption_position=over_image',
			)
		)->setValueCSS( array(
			'top'    => '.oe-media-content {
                            justify-content: flex-start;
                        }',
			'center' => '.oe-media-content {
							justify-content: center;
                        }',
			'bottom' => '.oe-media-content {
							justify-content: flex-end;
                        }',
		) );

		$caption_horizontal_align = $caption->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Horizontal Align', 'oxy-extended' ),
				'slug'      => 'caption_horizontal_align',
				'value'     => array(
					'left'    => __( 'Left', 'oxy-extended' ),
					'center'  => __( 'Center', 'oxy-extended' ),
					'right'   => __( 'Right', 'oxy-extended' ),
					'justify' => __( 'Justify', 'oxy-extended' ),
				),
				'default'   => 'left',
				'condition' => 'show_caption=yes&&caption_position=over_image',
			)
		)->setValueCSS( array(
			'left'   => '.oe-media-content {
                            align-items: flex-start;
                        }',
			'center' => '.oe-media-content {
							align-items: center;
                        }',
			'right'  => '.oe-media-content {
							align-items: flex-end;
                        }',
			'justify'  => '.oe-media-content {
							align-items: stretch;
                        }',
		) );

		$content_align = $caption->addControl( 'buttons-list', 'caption_alignment', __( 'Content Alignment', 'oxy-extended' ) );
		$content_align->setValue( [ 'Left', 'Center', 'Right' ] );
		$content_align->setValueCSS([
			'Left'  => '.oe-gallery-image-content{ text-align: left; }',
			'Right' => '.oe-gallery-image-content{ text-align: right; }',
		]);
		$content_align->setDefaultValue( 'Center' );
		$content_align->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_image_gallery_show_caption']=='yes'" );
		$content_align->whiteList();

		$style = $caption->addControlSection( 'caption_style', __( 'Style', 'oxy-extended' ), 'assets/icon.png', $this );

		$style->addStyleControls([
			[
				'selector'      => '.oe-gallery-image-caption',
				'property'      => 'background-color',
				'slug'          => 'oe_gallery_caption_bg',
			],
		]);

		$style->addPreset(
			'padding',
			'oe_gallery_caption_padding',
			__( 'Padding', 'oxy-extended' ),
			'.oe-gallery-image-caption'
		)->whiteList();

		$style->addPreset(
			'margin',
			'oe_gallery_caption_margin',
			__( 'Margin', 'oxy-extended' ),
			'.oe-gallery-image-caption'
		)->whiteList();

		$caption->typographySection( __( 'Title', 'oxy-extended' ), '.oe-gallery-image-caption-title', $this );
		$desc_gap = $caption->typographySection( __( 'Description', 'oxy-extended' ), '.oe-gallery-image-caption-text', $this );

		$desc_gap->addPreset(
			'margin',
			'oe_gallery_caption_text_margin',
			__( 'Margin', 'oxy-extended' ),
			'.oe-gallery-image-caption-text'
		)->whiteList();
	}

	public function lightbox_controls() {
	}

	/**
	 * Get attachment data
	 *
	 * @param  int $id attachment id.
	 * @return $data
	 */
	protected function get_attachment_data( $id ) {
		$data = wp_prepare_attachment_for_js( $id );

		if ( gettype( $data ) == 'array' ) {
			return json_decode( json_encode( $data ) );
		}

		return $data;
	}

	/**
	 * Render link icon
	 *
	 * @return $html
	 */
	protected function render_link_icon( $options ) {
		ob_start();
		?>
		<div class="oe-gallery-image-icon-wrap oe-media-content">
			<span class="oe-gallery-image-icon oe-icon">
				<?php
				global $oxygen_svg_icons_to_load; ?>
				<?php if ( isset( $options['oe_gallery_link_icon'] ) ) {
					$oxygen_svg_icons_to_load[] = $options['oe_gallery_link_icon']; ?>
					<div class="oe-link-icon">
						<svg><use xlink:href="#<?php echo $options['oe_gallery_link_icon']; ?>"></use></svg>
					</div>
				<?php } ?>
			</span>
		</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	/**
	 * Render image overlay
	 *
	 * @param int $count image count
	 */
	protected function render_image_overlay() {

		return '<div class="oe-media-overlay"></div>';
	}

	/**
	 * Get filter ids
	 *
	 * @param  array $items gallery items array.
	 * @param  bool $get_labels get labels or not.
	 * @return $unique_ids
	 */
	protected function get_filter_ids( $items = array(), $get_labels = false ) {
		$ids    = array();
		$labels = array();

		if ( ! count( $items ) ) {
			return $ids;
		}

		foreach ( $items as $index => $item ) {
			$image_group  = $item[0];
			$filter_ids   = array();
			$filter_label = '';

			foreach ( $image_group as $group ) {
				$ids[]        = $group;
				$filter_ids[] = $group;
				$filter_label = 'oe-group-' . ( $index + 1 );
			}

			$labels[ $filter_label ] = $filter_ids;
		}

		if ( ! count( $ids ) ) {
			return $ids;
		}

		$unique_ids = array_unique( $ids );

		if ( $get_labels ) {
			$filter_labels = array();

			foreach ( $unique_ids as $unique_id ) {
				if ( empty( $unique_id ) ) {
					continue;
				}

				foreach ( $labels as $key => $filter_ids ) {
					if ( in_array( $unique_id, $filter_ids ) ) {
						if ( isset( $filter_labels[ $unique_id ] ) ) {
							$filter_labels[ $unique_id ] = $filter_labels[ $unique_id ] . ' ' . str_replace( ' ', '-', strtolower( $key ) );
						} else {
							$filter_labels[ $unique_id ] = str_replace( ' ', '-', strtolower( $key ) );
						}
					}
				}
			}

			return $filter_labels;
		}

		return $unique_ids;
	}

	/**
	 * Get photos
	 *
	 * @return $ordered
	 */
	protected function get_acf_repeater_photos( $options ) {
		$images = array();

		if ( ! empty( $options['oe_gallery_source'] ) && 'acf_repeater' === $options['oe_gallery_source'] && function_exists( 'have_rows' ) ) {

			if ( ! empty( $options['acf_gallery_source'] ) && 'other' === $options['acf_gallery_source'] && isset( $options['page_id'] ) ) {
				$post_id = (int) $options['page_id'];
			} else {
				$post_id = get_the_ID();
			}

			if ( isset( $options['repeater_field_name'] ) && have_rows( $options['repeater_field_name'], $post_id ) ) :
				$images = array();
				$i = 0;
				while ( have_rows( $options['repeater_field_name'], $post_id ) ) :
					the_row();
					if ( isset( $options['repeater_img_field_name'] ) ) {
						$slug = $options['repeater_field_name'] . '_' . $i . '_' . $options['repeater_img_field_name'];
						$images[ $i ][] = get_post_meta( $post_id, $slug, true );
					}

					if ( isset( $options['repeater_title_field_name'] ) ) {
						$slug = $options['repeater_field_name'] . '_' . $i . '_' . $options['repeater_title_field_name'];
						$images[ $i ]['title'] = get_post_meta( $post_id, $slug, true );
					}

					if ( isset( $options['repeater_desc_field_name'] ) ) {
						$slug = $options['repeater_field_name'] . '_' . $i . '_' . $options['repeater_desc_field_name'];
						$images[ $i ]['desc'] = get_post_meta( $post_id, $slug, true );
					}

					$i++;
				endwhile;
			endif;
		}

		return $images;
	}

	/**
	 * Get WordPress photos
	 *
	 * @return $photos
	 */
	protected function get_wordpress_photos( $options ) {
		$image_size      = isset( $options['oe_gallery_image_size'] ) ? $options['oe_gallery_image_size'] : 'full';
		$acf_images      = $this->get_acf_repeater_photos( $options );
		$photos          = array();
		$ids             = array();
		$photo_ids       = array();
		$acf_repeater_imaages = $this->acf_repeater_check( $options );

		if ( $acf_repeater_imaages ) {

			if ( ! count( $acf_images ) ) {
				return $photos;
			}

			$photo_ids = $this->get_filter_ids( $acf_images );

		} elseif ( ! empty( $options['oe_gallery_source'] ) && 'medialibrary' === $options['oe_gallery_source'] && isset( $options['oe_gallery_images'] ) ) {

			$photo_ids = explode( ',', $options['oe_gallery_images'] );

		} elseif ( ! empty( $options['oe_gallery_source'] ) && 'acf' === $options['oe_gallery_source'] ) {

			if ( empty( $options['acf_field_name'] ) ) {

				echo __( 'Enter Gallery Field Key.', 'oxy-extended' );

			} else {

				if ( ! empty( $options['acf_gallery_source'] ) && 'other' === $options['acf_gallery_source'] ) {
					$post_id = (int) $options['page_id'];
				} else {
					$post_id = get_the_id();
				}

				$photo_ids = get_field( $options['acf_field_name'], $post_id );
			}
		} else {

			return $photos;

		}

		if ( isset( $options['oe_gallery_order'] ) && 'date' === $options['oe_gallery_order'] ) {
			$photo_ids_by_date = array();

			foreach ( $photo_ids as $id ) {
				$date = get_post_time( 'U', '', $id );
				$photo_ids_by_date[ $date ] = $id;
			}

			$photo_ids = $photo_ids_by_date;

			krsort( $photo_ids );
		}

		foreach ( $photo_ids as $id ) {
			if ( empty( $id ) ) {
				continue;
			}

			$photo = $this->get_attachment_data( $id );

			if ( ! $photo ) {
				continue;
			}

			// Only use photos who have the sizes object.
			if ( isset( $photo->sizes ) ) {
				$data = new \stdClass();

				// Photo data object.
				$data->id          = $id;
				$data->alt         = $photo->alt;
				$data->caption     = $photo->caption;
				$data->description = $photo->description;
				$data->title       = $photo->title;

				// Collage photo src.
				if ( isset( $options['oe_gallery_layout'] ) && 'masonry' === $options['oe_gallery_layout'] ) {
					if ( 'thumbnail' === $image_size && isset( $photo->sizes->thumbnail ) ) {
						$data->src = $photo->sizes->thumbnail->url;
					} elseif ( 'medium' === $image_size && isset( $photo->sizes->medium ) ) {
						$data->src = $photo->sizes->medium->url;
					} else {
						$data->src = $photo->sizes->full->url;
					}
				} else {
					// Grid photo src.
					if ( 'thumbnail' === $image_size && isset( $photo->sizes->thumbnail ) ) {
						$data->src = $photo->sizes->thumbnail->url;
					} elseif ( 'medium' === $image_size && isset( $photo->sizes->medium ) ) {
						$data->src = $photo->sizes->medium->url;
					} else {
						$data->src = $photo->sizes->full->url;
					}
				}

				// Photo Link.
				if ( isset( $photo->sizes->large ) ) {
					$data->link = $photo->sizes->large->url;
				} else {
					$data->link = $photo->sizes->full->url;
				}

				$photos[ $id ] = $data;
			}
		}

		return $photos;
	}

	/**
	 * Get photos
	 *
	 * @return $ordered
	 */
	protected function get_photos( $options ) {
		$photos   = $this->get_wordpress_photos( $options );
		$order    = ( isset( $options['oe_gallery_order'] ) );
		$ordered  = array();

		if ( is_array( $photos ) && 'rand' === $order ) {
			$keys = array_keys( $photos );
			shuffle( $keys );

			foreach ( $keys as $key ) {
				$ordered[ $key ] = $photos[ $key ];
			}
		} else {
			$ordered = $photos;
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return $ordered;
		}

		return $ordered;
	}

	public function acf_repeater_check( $options ) {
		if ( isset( $options['oe_gallery_source'] ) && 'acf_repeater' === $options['oe_gallery_source'] && function_exists( 'have_rows' ) ) {
			return true;
		}

		return false;
	}

	public function filters_check( $options ) {
		if ( isset( $options['oe_gallery_source'] ) && 'acf_repeater' === $options['oe_gallery_source'] && function_exists( 'have_rows' ) ) {
			if ( isset( $options['oe_gallery_filters_enable'] ) && 'yes' === $options['oe_gallery_filters_enable'] ) {
				return true;
			}
		}

		return false;
	}

	public function render_filters( $options, $images ) {
		$filters_enabled = $this->filters_check( $options );

		if ( $filters_enabled ) {
			?>
			<div class="oe-filters-wrapper">
				<div class="oe-gallery-filters">
				<?php
				echo '<div class="oe-gallery-filter" data-filter="*">' . esc_html__( 'All', 'oxy-extended' ) . '</div>';

				foreach ( $images as $index => $filter_gallery ) {
					$filter_label = $filter_gallery['title'];

					if ( empty( $filter_label ) ) {
						$filter_label  = __( 'Group ', 'oxy-extended' );
						$filter_label .= ( $index + 1 );
					}

					echo '<div class="oe-gallery-filter" data-filter=".oe-group-' . ( $index + 1 ) . '">' . esc_html( $filter_label ) . '</div>';
				}
				?>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Get Image Caption.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string image caption.
	 */
	protected function get_image_caption( $id, $caption_type = 'caption' ) {
		$attachment = get_post( $id );

		$attachment_caption = '';

		if ( 'title' === $caption_type ) {
			$attachment_caption = $attachment->post_title;
		} elseif ( 'caption' === $caption_type ) {
			$attachment_caption = $attachment->post_excerpt;
		} elseif ( 'description' === $caption_type ) {
			$attachment_caption = $attachment->post_content;
		}

		return $attachment_caption;

	}

	/**
	 * Render image caption
	 *
	 * @param  int $id image ID.
	 * @return $html
	 */
	protected function render_image_caption( $id, $options ) {
		if ( 'yes' !== $options['show_caption'] ) {
			return '';
		}

		$caption_type = 'title';

		$caption = $this->get_image_caption( $id, $caption_type );
		$description = $this->get_image_caption( $id, 'description' );

		if ( '' === $caption ) {
			return '';
		}
		?>
		<div class="oe-gallery-image-caption">
			<div class="oe-gallery-image-caption-title">
				<?php echo wp_kses_post( $caption ); ?>
			</div>
			<?php if ( 'yes' === $options['show_caption_description'] && '' !== $description ) { ?>
				<div class="oe-gallery-image-caption-text">
					<?php echo wp_kses_post( $description ); ?>
				</div>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Render gallery items
	 */
	protected function render_gallery_items( $options ) {
		$photos     = $this->get_photos( $options );
		$acf_images = $this->get_acf_repeater_photos( $options );
		$count      = 0;
		$link       = '';

		if ( isset( $options['oe_gallery_order'] ) && 'rand' === $options['oe_gallery_order'] ) {
			$photos = $this->oe_applyRandomOrder( $photos );
		}

		foreach ( $photos as $photo ) {
			if ( ! empty( $options['oe_gallery_source'] ) && 'acf_repeater' === $options['oe_gallery_source'] && function_exists( 'have_rows' ) ) {
				$filter_labels      = $this->get_filter_ids( $acf_images, true );
				$filter_label       = $filter_labels[ $photo->id ];
				$final_filter_label = preg_replace( '/[^\sA-Za-z0-9]/', '-', $filter_label );
			} else {
				$final_filter_label = '';
			}

			if ( isset( $options['oe_gallery_image_link_to'] ) ) {
				if ( 'file' === $options['oe_gallery_image_link_to'] ) {

					$link = wp_get_attachment_url( $photo->id );

				} elseif ( 'attachment' === $options['oe_gallery_image_link_to'] ) {

					$link = get_attachment_link( $photo->id );

				}
			}
			?>
			<div class="oe-grid-item-wrap <?php echo wp_kses_post( $final_filter_label ); ?>" data-item-id="<?php echo esc_attr( $photo->id ); ?>">
				<?php if ( $link ) { echo '<a href="' . esc_url( $link ) . '" class="oe-gallery-link" data-fancybox="oe-image-gallery">'; } ?>
					<div class="oe-grid-item">
						<?php
						$image_style = 'style="background-image: url(' . esc_attr( $photo->src ) . ');"';

						if ( isset( $options['oe_gallery_layout'] ) && 'justified' === $options['oe_gallery_layout'] ) {
							$image_html = '<div class="oe-ins-filter-target oe-image-gallery-thumbnail"><img class="oe-gallery-image" src="' . esc_attr( $photo->src ) . '" alt="' . $photo->alt . '" /></div>';
						} else {
							$image_html = '<div class="oe-ins-filter-target oe-image-gallery-thumbnail" style="background-image: url(' . esc_attr( $photo->src ) . ');"><img class="oe-gallery-image" src="' . esc_attr( $photo->src ) . '" alt="' . $photo->alt . '" /></div>';
						}
						?>
						<div class="oe-ins-filter-target oe-image-gallery-thumbnail" <?php echo $image_style; ?>>
							<img class="oe-gallery-image" src="<?php echo esc_attr( $photo->src ); ?>" alt="<?php echo $photo->alt; ?>" />
						</div>

						<?php echo $this->render_image_overlay(); ?>

						<div class="oe-gallery-image-content oe-media-content">
							<?php
							// Link Icon.
							//$image_html .= $this->render_link_icon( $options );
							global $oxygen_svg_icons_to_load;
							if ( isset( $options['oe_gallery_link_icon'] ) ) {
								$oxygen_svg_icons_to_load[] = $options['oe_gallery_link_icon']; ?>
								<div class="oe-link-icon<?php if ( isset( $options['oe_gallery_link_icon_on_hover'] ) && 'yes' === $options['oe_gallery_link_icon_on_hover'] ) {
								?> oe-link-icon-on-hover<?php } ?>">
									<svg><use xlink:href="#<?php echo $options['oe_gallery_link_icon']; ?>"></use></svg>
								</div>
								<?php
							}

							if ( 'over_image' === $options['caption_position'] ) {
								$this->render_image_caption( $photo->id, $options );
							}
							?>
						</div>
					</div>
				<?php
					if ( 'over_image' !== $options['caption_position'] ) {
						$this->render_image_caption( $photo->id, $options );
					}
				?>
				<?php if ( $link ) { echo '</a>'; } ?>
			</div>
			<?php
			$count++;
		}
	}

	public function render( $options, $defaults, $content ) {
		$uid = str_replace( '.', '', uniqid( 'oeig', true ) );

		if ( ! empty( $options['oe_gallery_source'] ) && 'acf_repeater' === $options['oe_gallery_source'] && function_exists( 'have_rows' ) ) {
			$images = $this->get_acf_repeater_photos( $options );
		} else {
			$images = $this->get_photos( $options );
		}

		if ( $images ) :
			$image_size = isset( $options['image_size'] ) ? $options['image_size'] : 'full';

			if ( isset( $options['oe_gallery_order'] ) && 'rand' === $options['oe_gallery_order'] ) {
				$images = $this->oe_applyRandomOrder( $images );
			}

			$acf_repeater_imaages = $this->acf_repeater_check( $options );
			$filters_enabled = $this->filters_check( $options );

			if ( $acf_repeater_imaages ) {
				$this->get_filter_ids( $images, true );
			}

			if ( $filters_enabled ) {
				$this->render_filters( $options, $images );
			}

			if ( isset( $options['oe_gallery_layout'] ) && 'justified' === $options['oe_gallery_layout'] ) {
				$justified_class = 'oe-image-gallery-justified';
			} else {
				$justified_class = '';
			}
			?>
			<div class="oe-image-gallery oe-image-gallery-<?php echo esc_attr( $uid ) . ' ' . esc_attr( $justified_class ); ?>">
				<?php
					$this->render_gallery_items( $options );
				?>
			</div>
			<?php ob_start(); ?>
				jQuery(document).ready(function($){
					<?php
					if ( ! defined( 'OXY_ELEMENTS_API_AJAX' ) ) {
						if ( isset( $options['oe_gallery_open_lightbox'] ) && 'yes' === $options['oe_gallery_open_lightbox'] ) {
							wp_enqueue_style( 'oe-fancybox' );
							wp_enqueue_script( 'oe-fancybox' );
						}
					}

					$filters_enabled = $this->filters_check( $options );
					$masonry_enabled = ( isset( $options['oe_gallery_layout'] ) && 'masonry' === $options['oe_gallery_layout'] );

					if ( $filters_enabled || $masonry_enabled ) {
						wp_enqueue_script( 'isotope' );
						wp_enqueue_script( 'imagesloaded' );
						?>
						var layoutMode = 'fitRows',
							defaultFilter = '';

						var $isotope_args = {
								itemSelector    : '.oe-grid-item-wrap',
								layoutMode		: layoutMode,
								percentPosition : true,
								filter          : defaultFilter,
							},
							isotopeGallery = {};

						var oeImageGallery = $( '.oe-image-gallery-<?php echo $uid; ?>' );

						oeImageGallery.imagesLoaded( function() {
							isotopeGallery = oeImageGallery.isotope( $isotope_args );
							oeImageGallery.find('.oe-gallery-image').on('load', function() {
								if ( $(this).hasClass('lazyloaded') ) {
									return;
								}
								setTimeout(function() {
									oeImageGallery.isotope( 'layout' );
								}, 500);
							});
						});

						$('.oxy-oe-image-gallery').on( 'click', '.oe-gallery-filter', function() {
							var $this = $(this),
								filterValue = $this.attr('data-filter'),
								filterIndex = $this.attr('data-gallery-index'),
								galleryItems = oeImageGallery.find(filterValue);

							if ( filterValue === '*' ) {
								galleryItems = oeImageGallery.find('.oe-grid-item-wrap');
							}

							$(galleryItems).each(function() {
								var imgLink = $(this).find('.oe-gallery-link');

								imgLink.attr('data-fancybox', filterIndex);
							});

							$this.siblings().removeClass('oe-active');
							$this.addClass('oe-active');

							isotopeGallery.isotope({ filter: filterValue });
						});
						<?php
					}

					if ( isset( $options['oe_gallery_layout'] ) && 'justified' === $options['oe_gallery_layout'] ) {
						wp_enqueue_script( 'imagesloaded' );
						wp_enqueue_script( 'justified-gallery' );

						$justified_gallery_row_height = ( isset( $options['oe_justified_gallery_row_height'] ) ) ? $options['oe_justified_gallery_row_height'] : '120';
						$justified_gallery_last_row = ( isset( $options['oe_justified_gallery_last_row'] ) ) ? $options['oe_justified_gallery_last_row'] : 'justify';
						?>
						var justifiedGallery = $('.oe-image-gallery-justified');

						if ( justifiedGallery.length > 0 ) {
							justifiedGallery.imagesLoaded( function() {
							})
							.done(function( instance ) {
								justifiedGallery.justifiedGallery({
									rowHeight : <?php echo $justified_gallery_row_height; ?>,
									lastRow : '<?php echo $justified_gallery_last_row; ?>',
									selector : 'div',
									waitThumbnailsLoad : true,
									margins : 0,
									border : 0
								});
							});
						}
						<?php
					}

					if ( isset( $options['oe_gallery_tilt_enable'] ) && 'yes' === $options['oe_gallery_tilt_enable'] ) {
						wp_enqueue_script( 'tilt' );

						$tilt_axis = ( isset( $options['oe_gallery_tilt_axis'] ) ) ? $options['oe_gallery_tilt_axis'] : 'both';
						$tilt_amount = ( isset( $options['oe_gallery_tilt_amount'] ) ) ? $options['oe_gallery_tilt_amount'] : '20';
						$tilt_scale = ( isset( $options['oe_gallery_tilt_scale'] ) ) ? $options['oe_gallery_tilt_scale'] : '1.06';
						$tilt_speed = ( isset( $options['oe_gallery_tilt_speed'] ) ) ? $options['oe_gallery_tilt_speed'] : '800';
						?>
						var oeImageGallery = $( '.oe-image-gallery-<?php echo $uid; ?>' );

						oeImageGallery.find('.oe-grid-item').tilt({
							disableAxis: '<?php echo $tilt_axis; ?>',
							maxTilt: <?php echo $tilt_amount; ?>,
							scale: <?php echo $tilt_scale; ?>,
							speed: <?php echo $tilt_speed; ?>,
							perspective: 1000
						});
						<?php
					}
					?>
				});
			<?php
			if ( isset( $_GET['oxygen_iframe'] ) || defined( 'OXY_ELEMENTS_API_AJAX' ) ) {
				$this->El->builderInlineJS( ob_get_clean() );
			} else {
				$this->slide_js_code[] = ob_get_clean();

				if ( ! $this->js_added ) {
					add_action( 'wp_footer', array( $this, 'oe_image_gallery_enqueue_scripts' ) );
					$this->js_added = true;
				}

				$this->El->footerJS( join( '', $this->slide_js_code ) );
			}

		endif;
	}

	public function customCSS( $original, $selector ) {
		$css = '';
		if ( ! $this->css_added ) {
			$css .= file_get_contents( __DIR__ . '/' . basename( __FILE__, '.php' ) . '.css' );
			$this->css_added = true;
		}

		$prefix = $this->El->get_tag();

		if ( isset( $original[ $prefix . '_oe_gallery_images_per_row' ] ) ) {
			$width = number_format( ( 100 / $original[ $prefix . '_oe_gallery_images_per_row' ] ), 3 ) . '%';

			$css .= $selector . ' .oe-image-gallery .oe-grid-item-wrap { width: ' . $width . '; }';
		}

		if ( isset( $original[ $prefix . '_oe_gallery_layout' ] ) && 'justified' !== $original[ $prefix . '_oe_gallery_layout' ] ) {
			if ( isset( $original[ $prefix . '_oe_gallery_aspect_ratio' ] ) ) {
				$aspect_ratio = explode( ':', $original[ $prefix . '_oe_gallery_aspect_ratio' ] );
				$aspect_ratio = ( $aspect_ratio[1] / $aspect_ratio[0] );
				$aspect_ratio = number_format( $aspect_ratio * 100, 2 ) . '%';

				$css .= $selector . ' .oe-image-gallery .oe-grid-item { padding-bottom: ' . $aspect_ratio . '; }';
			}
		}

		if ( isset( $original[ $prefix . '_show_caption' ] ) && 'yes' === $original[ $prefix . '_show_caption' ] ) {
			$css .= $selector . ' .oe-gallery-image-content { display: flex; flex-direction: column; }';
		}

		return $css;
	}

	public function init() {
		$this->El->useAJAXControls();
		if ( isset( $_GET['oxygen_iframe'] ) ) {
			add_action( 'wp_footer', array( $this, 'oe_image_gallery_enqueue_scripts' ) );
		}
	}

	public function oe_image_gallery_enqueue_scripts() {
		wp_enqueue_script( 'isotope' );
		wp_enqueue_script( 'imagesloaded' );
		wp_enqueue_script( 'justified-gallery' );
		wp_enqueue_style( 'oe-fancybox' );
		wp_enqueue_script( 'oe-fancybox' );
	}

	public function oe_applyRandomOrder( $images ) {

		if ( is_array( $images ) ) {
			$new_images = array();
			$keys = array_keys( $images );
			shuffle( $keys );

			foreach ( $keys as $key ) {
				$new_images[ $key ] = $images[ $key ];
			}

			return $new_images;
		}

		return $images;

	}

	public function enablePresets() {
		return false;
	}

	public function enableFullPresets() {
		return false;
	}
}

new OEImageGallery();
