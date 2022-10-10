<?php
namespace Oxygen\OxyExtended;

use OxyExtended\Classes\OE_Helper;

/**
 * Gallery Slider Element
 */
class OEGallerySlider extends \OxyExtendedEl {

	public $has_js = true;
	public $css_added = false;
	public $js_added = false;
	private $slide_js_code = array();
	/**
	 * Retrieve before after slider element name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'Gallery Slider', 'oxy-extended' );
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
		return 'oe_gallery_slider';
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
		return array( 'oxy-extended-element oe-gallery-slider-wrapper' );
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

	public function controls() {
		$this->gallery_controls();
		$this->slider_layout_controls();
		$this->slider_settings_controls();
		$this->navigation_arrows_controls();
		$this->pagination_controls();
		$this->thumbnails_pagination_control();
		$this->caption_controls();
		$this->lightbox_controls();
	}

	public function gallery_controls() {
		$gallery_section = $this->addControlSection( 'gallery_section', __( 'Gallery', 'oxy-extended' ), 'assets/icon.png', $this );

		if ( class_exists( 'WooCommerce' ) ) {
			$gallery_section->addCustomControl(
				__( '<div class="oxygen-option-default" style="color: #c3c5c7; line-height: 1.3; font-size: 13px;">For WooCommerce product images, select the <strong>Custom Field</strong> option & enter <span style="color:#ff7171;">_product_image_gallery</span> into the <strong>Gallery Field Name</strong> input field.</div>', 'oxy-extended' ),
				'WooCommerce_note'
			)->setParam( 'heading', 'Note:' );
		}

		$gsource = $gallery_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Gallery Source', 'oxy-extended' ),
				'slug'      => 'oe_gallery_source',
				'value'     => array(
					'media'        => __( 'Media Library', 'oxy-extended' ),
					'acf'          => __( 'Custom Field', 'oxy-extended' ),
					'acf_repeater' => __( 'ACF Repeater', 'oxy-extended' ),
				),
				'default'   => 'media',
			)
		);

		$wp_gallery = $gallery_section->addCustomControl("
			<div class='oxygen-control'>
				<div class='oxygen-file-input'
				ng-class=\"{'oxygen-option-default':iframeScope.isInherited(iframeScope.component.active.id, 'oe_gallery_images')}\">
					<input type=\"text\" spellcheck=\"false\" ng-model=\"iframeScope.component.options[iframeScope.component.active.id]['model']['oe_gallery_images']\" ng-model-options=\"{ debounce: 10 }\"
						ng-change=\"iframeScope.setOption(iframeScope.component.active.id,'oe_gallery_slider','oe_gallery_images');\">
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

		$wp_gallery->setParam( 'heading', 'Images' );
		$wp_gallery->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_oe_gallery_source']=='media'" );

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

		$images_source->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_oe_gallery_source']!='media'" );

		$page_id = $gallery_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Enter Post/Page ID', 'oxy-extended' ),
				'slug'      => 'page_id',
			)
		);

		$page_id->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_oe_gallery_source']!='media'&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_acf_gallery_source']=='other'" );
		$page_id->rebuildElementOnChange();

		$gf_name = $gallery_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Gallery Field Name', 'oxy-extended' ),
				'slug'      => 'field_name',
			)
		);

		$gf_name->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_oe_gallery_source']=='acf'" );
		$gf_name->rebuildElementOnChange();

		//* Repeater fields
		$acf_rep_field_name = $gallery_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Repeater Field Name', 'oxy-extended' ),
				'slug'      => 'repeater_field_name',
			)
		);

		$acf_rep_field_name->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_oe_gallery_source']=='acf_repeater'" );
		$acf_rep_field_name->rebuildElementOnChange();

		$acf_rep_img_field_name = $gallery_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Image Field Name', 'oxy-extended' ),
				'slug'      => 'repeater_img_field_name',
			)
		);

		$acf_rep_img_field_name->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_oe_gallery_source']=='acf_repeater'" );
		$acf_rep_img_field_name->rebuildElementOnChange();

		$acf_rep_title_field_name = $gallery_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Title Field Name', 'oxy-extended' ),
				'slug'      => 'repeater_title_field_name',
			)
		);

		$acf_rep_title_field_name->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_oe_gallery_source']=='acf_repeater'" );
		$acf_rep_title_field_name->rebuildElementOnChange();

		$acf_rep_desc_field_name = $gallery_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Description Field Name', 'oxy-extended' ),
				'slug'      => 'repeater_desc_field_name',
			)
		);

		$acf_rep_desc_field_name->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_oe_gallery_source']=='acf_repeater'" );
		$acf_rep_desc_field_name->rebuildElementOnChange();

		$image_size = $gallery_section->addOptionControl(
			array(
				'type'      => 'dropdown',
				'name'      => __( 'Image Size', 'oxy-extended' ),
				'slug'      => 'image_size',
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
		);
	}

	public function slider_layout_controls() {
		$slider_layout = $this->addControlSection( 'slider_layout_section', __( 'Slider Layout', 'oxy-extended' ), 'assets/icon.png', $this );

		$slider_type = $slider_layout->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Slider Type', 'oxy-extended' ),
			'slug'      => 'gallery_slider_type',
			'value'     => [
				'carousel'  => __( 'Carousel', 'oxy-extended' ),
				'slideshow' => __( 'Slideshow', 'oxy-extended' ),
				'coverflow' => __( 'Coverflow', 'oxy-extended' ),
			],
		]);
		$slider_type->rebuildElementOnChange();

		$anim_effect = $slider_layout->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Animation Effect', 'oxy-extended' ),
			'slug'      => 'oe_gallery_slider_effect',
			'default'   => 'slide',
			'value'     => [
				'slide'     => __( 'Slide', 'oxy-extended' ),
				'fade'      => __( 'Fade', 'oxy-extended' ),
				'cube'      => __( 'Cube', 'oxy-extended' ),
				'kenburns'  => __( 'Ken Burns', 'oxy-extended' ),
			],
		]);
		$anim_effect->rebuildElementOnChange();

		$slider_layout->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Auto Height', 'oxy-extended' ),
			'slug'      => 'oe_gs_auto_height',
			'default'   => 'no',
			'value'     => [
				'no'        => __( 'No', 'oxy-extended' ),
				'yes'       => __( 'Yes', 'oxy-extended' ),
			],
		])->rebuildElementOnChange();

		$image_fit = $slider_layout->addControl( 'buttons-list', 'image_fit', __( 'Image Fit', 'oxy-extended' ) );
		$image_fit->setValue( array( 'Cover', 'Contain', 'Auto' ) );
		$image_fit->setValueCSS( array(
			'Cover'     => '.oe-gallery-slider-image-container{background-size: cover;}',
			'Contain'   => '.oe-gallery-slider-image-container{background-size: contain;}',
			'Auto'      => '.oe-gallery-slider-image-container{background-size: auto;}',
		));
		$image_fit->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_oe_gs_auto_height']=='no'" );
		$image_fit->setDefaultValue( 'Cover' );
		$image_fit->whiteList();

		$slider_layout->addStyleControl(
			array(
				'control_type'  => 'slider-measurebox',
				'name'          => __( 'Images Height', 'oxy-extended' ),
				'selector'      => '.oe-gallery-slider .oegsld-img', //, .oe-gallery-slider.oe-gallery-slider-slideshow
				'slug'          => 'oe_gs_img_height',
				'property'      => 'height',
				'condition'     => 'oe_gs_auto_height=no',
			)
		)
		->setUnits( 'px', 'px' )
		->setRange( '0', '1500', '10' )
		->setValue( '250' )
		->rebuildElementOnChange();

		//* Items per View
		$items_per_view = $slider_layout->addControlSection( 'itemsPerView', __( 'Items per View', 'oxy-extended' ), 'assets/icon.png', $this );
		$items_per_view_desk = $items_per_view->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'All Devices', 'oxy-extended' ),
			'slug'      => 'columns',
			'condition' => 'gallery_slider_type!=slideshow',
		]);

		$items_per_view_desk->setUnits( ' ', ' ' );
		$items_per_view_desk->setRange( '1', '10', '1' );
		$items_per_view_desk->setValue( '4' );
		$items_per_view_desk->rebuildElementOnChange();

		$items_per_view_lg = $items_per_view->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Large Devices (Range: 769px to 992px)', 'oxy-extended' ),
			'slug'      => 'columns_tablet',
			'condition' => 'gallery_slider_type!=slideshow',
		]);

		$items_per_view_lg->setUnits( ' ', ' ' );
		$items_per_view_lg->setRange( '1', '10', '1' );
		$items_per_view_lg->setValue( '3' );
		$items_per_view_lg->rebuildElementOnChange();

		$items_per_view_md = $items_per_view->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Medium Devices (Range: 640px to 768px)', 'oxy-extended' ),
			'slug'      => 'columns_landscape',
			'condition' => 'gallery_slider_type!=slideshow',
		]);

		$items_per_view_md->setUnits( ' ', ' ' );
		$items_per_view_md->setRange( '1', '10', '1' );
		$items_per_view_md->setValue( '2' );
		$items_per_view_md->rebuildElementOnChange();

		$items_per_view_sm = $items_per_view->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Small Devices (Below 640px)', 'oxy-extended' ),
			'slug'      => 'columns_portrait',
			'condition' => 'gallery_slider_type!=slideshow',
		]);

		$items_per_view_sm->setUnits( ' ', ' ' );
		$items_per_view_sm->setRange( '1', '10', '1' );
		$items_per_view_sm->setValue( '1' );
		$items_per_view_sm->rebuildElementOnChange();

		//* Items per Columns
		$maultirow = $slider_layout->addControlSection( 'slidesPerColumn', __( 'Multi Row Layout', 'oxy-extended' ), 'assets/icon.png', $this );

		$maultirow->addCustomControl(
			__( '<div class="oxygen-option-default" style="color: #c3c5c7; line-height: 1.3; font-size: 13px;">Number of slides per column, for multirow layout. It is currently not compatible with loop mode (infiniteloop: true). This would be good option for carousel slide effect.</div>', 'oxy-extended' ),
			'multirow_note'
		)->setParam( 'heading', 'Note:' );

		$multirow_all = $maultirow->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'All Devices', 'oxy-extended' ),
			'slug'      => 'rows',
			'condition' => 'gallery_slider_type!=slideshow',
		]);

		$multirow_all->setUnits( ' ', ' ' );
		$multirow_all->setRange( '1', '10', '1' );
		$multirow_all->setValue( '1' );
		$multirow_all->rebuildElementOnChange();

		$multirow_lg = $maultirow->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Large Devices (Range: 769px to 992px)', 'oxy-extended' ),
			'slug'      => 'rows_tablet',
			'condition' => 'gallery_slider_type!=slideshow',
		]);

		$multirow_lg->setUnits( ' ', ' ' );
		$multirow_lg->setRange( '1', '10', '1' );
		$multirow_lg->setValue( '1' );
		$multirow_lg->rebuildElementOnChange();

		$multirow_md = $maultirow->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Medium Devices (Range: 640px to 768px)', 'oxy-extended' ),
			'slug'      => 'rows_landscape',
			'condition' => 'gallery_slider_type!=slideshow',
		]);

		$multirow_md->setUnits( ' ', ' ' );
		$multirow_md->setRange( '1', '10', '1' );
		$multirow_md->setValue( '1' );
		$multirow_md->rebuildElementOnChange();

		$multirow_sm = $maultirow->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Small Devices (Below 640px)', 'oxy-extended' ),
			'slug'      => 'rows_portrait',
			'condition' => 'gallery_slider_type!=slideshow',
		]);

		$multirow_sm->setUnits( ' ', ' ' );
		$multirow_sm->setRange( '1', '10', '1' );
		$multirow_sm->setValue( '1' );
		$multirow_sm->rebuildElementOnChange();

		//* Slides to Scroll
		$slides_to_scroll = $slider_layout->addControlSection( 'slides_to_scroll_section', __( 'Slides to Scroll', 'oxy-extended' ), 'assets/icon.png', $this );
		$slides_to_scroll_all = $slides_to_scroll->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'All Devices', 'oxy-extended' ),
			'slug'      => 'slides_to_scroll',
			'condition' => 'gallery_slider_type!=slideshow',
		]);

		$slides_to_scroll_all->setUnits( ' ', ' ' );
		$slides_to_scroll_all->setRange( '1', '10', '1' );
		$slides_to_scroll_all->setValue( '1' );
		$slides_to_scroll_all->setParam( 'description', __( 'Set numbers of slides to move at a time.', 'oxy-extended' ) );

		$slides_to_scroll_lg = $slides_to_scroll->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Large Devices (Range: 769px to 992px)', 'oxy-extended' ),
			'slug'      => 'slides_to_scroll_tablet',
			'condition' => 'gallery_slider_type!=slideshow',
		]);

		$slides_to_scroll_lg->setUnits( ' ', ' ' );
		$slides_to_scroll_lg->setRange( '1', '10', '1' );
		$slides_to_scroll_lg->setValue( '1' );

		$slides_to_scroll_md = $slides_to_scroll->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Medium Devices (Range: 640px to 768px)', 'oxy-extended' ),
			'slug'      => 'slides_to_scroll_landscape',
			'condition' => 'gallery_slider_type!=slideshow',
		]);

		$slides_to_scroll_md->setUnits( ' ', ' ' );
		$slides_to_scroll_md->setRange( '1', '10', '1' );
		$slides_to_scroll_md->setValue( '1' );

		$slides_to_scroll_sm = $slides_to_scroll->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Small Devices (Below 640px)', 'oxy-extended' ),
			'slug'      => 'slides_to_scroll_portrait',
			'condition' => 'gallery_slider_type!=slideshow',
		]);

		$slides_to_scroll_sm->setUnits( ' ', ' ' );
		$slides_to_scroll_sm->setRange( '1', '10', '1' );
		$slides_to_scroll_sm->setValue( '1' );

		//* Spacing
		$items_gap = $slider_layout->addControlSection( 'items_gap_section', __( 'Items Gap', 'oxy-extended' ), 'assets/icon.png', $this );
		$items_gap_all = $items_gap->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'All Devices', 'oxy-extended' ),
			'slug'      => 'items_gap',
		]);

		$items_gap_all->setUnits( 'px', 'px' );
		$items_gap_all->setRange( '5', '50', '5' );
		$items_gap_all->setValue( '15' );
		$items_gap_all->rebuildElementOnChange();

		$items_gap_lg = $items_gap->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Large Devices (Range: 769px to 992px)', 'oxy-extended' ),
			'slug'      => 'items_gap_tablet',
		]);

		$items_gap_lg->setUnits( 'px', 'px' );
		$items_gap_lg->setRange( '5', '50', '5' );
		$items_gap_lg->setValue( '15' );
		$items_gap_lg->rebuildElementOnChange();

		$items_gap_md = $items_gap->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Medium Devices (Range: 640px to 768px)', 'oxy-extended' ),
			'slug'      => 'items_gap_landscape',
		]);

		$items_gap_md->setUnits( 'px', 'px' );
		$items_gap_md->setRange( '5', '50', '5' );
		$items_gap_md->setValue( '15' );
		$items_gap_md->rebuildElementOnChange();

		$items_gap_sm = $items_gap->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Small Devices (Below 640px)', 'oxy-extended' ),
			'slug'      => 'items_gap_portrait',
		]);

		$items_gap_sm->setUnits( 'px', 'px' );
		$items_gap_sm->setRange( '5', '50', '5' );
		$items_gap_sm->setValue( '15' );
		$items_gap_sm->rebuildElementOnChange();

		$kenburns_effect = $slider_layout->addControlSection( 'kenburns_effect', __( 'Ken Burns Settings', 'oxy-extended' ), 'assets/icon.png', $this );
		$kenburns_effect_transition = $kenburns_effect->addStyleControl([
			'selector'      => '.swiper-scale-effect .swiper-slide-cover',
			'property'      => 'transition-duration',
			'control_type'  => 'slider-measurebox',
			'name'          => __( 'Transition Duration', 'oxy-extended' ),
			'slug'          => 'oe_kenburns_duration',
			'condition'     => 'oe_gallery_slider_effect=kenburns',
		]);
		$kenburns_effect_transition->setUnits( 's', 'sec' );
		$kenburns_effect_transition->setRange( '0', '10', '0.5' );
		$kenburns_effect_transition->setValue( '4.5' );

		$kenburns_effect_transform = $kenburns_effect->addOptionControl([
			'type'          => 'slider-measurebox',
			'name'          => __( 'Transform Scale', 'oxy-extended' ),
			'slug'          => 'oe_kenburns_scale',
			'condition'     => 'oe_gallery_slider_effect=kenburns',
		]);
		$kenburns_effect_transform->setUnits( ' ', ' ' );
		$kenburns_effect_transform->setRange( '0', '4', '0.02' );
		$kenburns_effect_transform->setValue( '1.18' );
		$kenburns_effect_transform->rebuildElementOnChange();
	}

	public function slider_settings_controls() {
		$slider_settings = $this->addControlSection( 'slider_settings', __( 'Slider Settings', 'oxy-extended' ), 'assets/icon.png', $this );

		$centered_slide = $slider_settings->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Centered Slide', 'oxy-extended' ),
			'slug'      => 'centered_slide',
			'default'   => 'no',
			'value'     => [
				'yes' => __( 'Yes', 'oxy-extended' ),
				'no'  => __( 'No', 'oxy-extended' ),
			],
		]);
		$centered_slide->rebuildElementOnChange();

		$transition_speed = $slider_settings->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Transition Speed', 'oxy-extended' ),
			'slug'      => 'transition_speed',
		]);

		$transition_speed->setUnits( 'ms', 'ms' );
		$transition_speed->setRange( '1000', '20000', '500' );
		$transition_speed->setValue( '1000' );
		$transition_speed->setParam( 'description', __( 'Values are in milliseconds', 'oxy-extended' ) );
		$transition_speed->rebuildElementOnChange();

		$auto_play = $slider_settings->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Auto Play', 'oxy-extended' ),
			'slug'      => 'autoplay',
			'default'   => 'yes',
			'value'     => [
				'yes' => __( 'Yes', 'oxy-extended' ),
				'no'  => __( 'No', 'oxy-extended' ),
			],
		]);
		$auto_play->setParam( 'description', __( 'Autoplay is disabled in editor', 'oxy-extended' ) );
		$auto_play->rebuildElementOnChange();

		$auto_play_speed = $slider_settings->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Auto Play Speed', 'oxy-extended' ),
			'slug'      => 'autoplay_speed',
			'condition' => 'autoplay=yes',
		]);

		$auto_play_speed->setUnits( 'ms', 'ms' );
		$auto_play_speed->setRange( '1000', '20000', '500' );
		$auto_play_speed->setValue( '5000' );
		$auto_play_speed->setParam( 'description', __( 'Values are in milliseconds', 'oxy-extended' ) );
		$auto_play_speed->rebuildElementOnChange();

		$slider_settings->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Pause on Hover', 'oxy-extended' ),
			'slug'      => 'pause_on_hover',
			'default'   => 'no',
			'value'     => [
				'no'  => __( 'No', 'oxy-extended' ),
				'yes' => __( 'Yes', 'oxy-extended' ),
			],
			'condition' => 'autoplay=yes',
		]);

		$slider_settings->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Pause on Interaction', 'oxy-extended' ),
			'slug'      => 'pause_on_interaction',
			'default'   => 'yes',
			'value'     => [
				'yes' => __( 'Yes', 'oxy-extended' ),
				'no'  => __( 'No', 'oxy-extended' ),
			],
			'condition' => 'autoplay=yes',
		]);

		$slider_loop = $slider_settings->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Infinite Loop', 'oxy-extended' ),
			'slug'      => 'oegsld_loop',
			'default'   => 'yes',
			'value'     => [
				'yes' => __( 'Yes', 'oxy-extended' ),
				'no'  => __( 'No', 'oxy-extended' ),
			],
		]);
		$slider_loop->rebuildElementOnChange();
	}

	public function navigation_arrows_controls() {
		$arrows_section = $this->addControlSection( 'arrows_section', __( 'Navigation Arrows', 'oxy-extended' ), 'assets/icon.png', $this );

		$nav_arrows = $arrows_section->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Show Navigation Arrows', 'oxy-extended' ),
			'slug'      => 'slider_navigation',
			'default'   => 'no',
			'value'     => [
				'yes' => __( 'Yes', 'oxy-extended' ),
				'no'  => __( 'No', 'oxy-extended' ),
			],
		]);
		$nav_arrows->rebuildElementOnChange();

		$arrow_on_hover = $arrows_section->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Show on Hover', 'oxy-extended' ),
			'slug'      => 'arrows_on_hover',
			'default'   => 'default',
			'value'     => [
				'no'  => __( 'No', 'oxy-extended' ),
				'yes' => __( 'Yes', 'oxy-extended' ),
			],
		]);
		$arrow_on_hover->setParam( 'description', 'Preview is disable for builder editor.' );
		$arrow_on_hover->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_slider_navigation']!='no'" );
		$arrow_on_hover->rebuildElementOnChange();

		$arrows_section->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Hide on Devices', 'oxy-extended' ),
			'slug'      => 'arrows_hide_responsive',
			'default'   => 'no',
			'value'     => [
				'no'  => __( 'No', 'oxy-extended' ),
				'yes' => __( 'Yes', 'oxy-extended' ),
			],
			'condition' => 'slider_navigation=yes',
		]);

		$arrow_breakpoint = $arrows_section->addOptionControl([
			'type'      => 'measurebox',
			'name'      => __( 'Breakpoint', 'oxy-extended' ),
			'slug'      => 'arrows_breakpoint',
			'condition' => 'arrows_hide_responsive=yes',
		]);
		$arrow_breakpoint->setUnits( 'px', 'px' );
		$arrow_breakpoint->setDefaultValue( 680 );
		$arrow_breakpoint->setParam( 'description', 'Default breakpoint value is 680px.' );
		$arrow_breakpoint->rebuildElementOnChange();

		$arrows_icon_section = $arrows_section->addControlSection( 'arrows_icon_section', __( 'Icon', 'oxy-extended' ), 'assets/icon.png', $this );
		$left_arrow = $arrows_icon_section->addOptionControl(
			array(
				'type' => 'icon_finder',
				'name' => __( 'Left Arrow', 'oxy-extended' ),
				'slug' => 'arrow_left',
			)
		);
		$left_arrow->rebuildElementOnChange();

		$right_arrow = $arrows_icon_section->addOptionControl(
			array(
				'type' => 'icon_finder',
				'name' => __( 'Right Arrow', 'oxy-extended' ),
				'slug' => 'arrow_right',
			)
		);
		$right_arrow->rebuildElementOnChange();

		$arrows_style = $arrows_section->addControlSection( 'arrows_style', __( 'Color & Size', 'oxy-extended' ), 'assets/icon.png', $this );

		$arrows_style->addStyleControls([
			[
				'name'          => __( 'Size', 'oxy-extended' ),
				'slug'          => 'arrows_size',
				'selector'      => '.oe-swiper-button svg',
				'control_type'  => 'slider-measurebox',
				'value'         => '25',
				'property'      => 'width|height',
				'unit'          => 'px',
			],
			[
				'selector'      => '.oe-swiper-button',
				'property'      => 'background-color',
				'slug'          => 'arrows_bg_color',
			],
			[
				'name'          => __( 'Hover Background Color', 'oxy-extended' ),
				'selector'      => '.oe-swiper-button:hover',
				'property'      => 'background-color',
				'slug'          => 'arrows_bg_color_hover',
			],
			[
				'name'          => __( 'Color', 'oxy-extended' ),
				'selector'      => '.oe-swiper-button svg',
				'property'     => 'fill',
				'control_type' => 'colorpicker',
			],
			[
				'name'          => __( 'Hover Color', 'oxy-extended' ),
				'selector'      => '.oe-swiper-button:hover svg',
				'property'     => 'fill',
				'control_type' => 'colorpicker',
			],
		]);

		$arrows_style->addPreset(
			'padding',
			'arrows_padding',
			__( 'Padding', 'oxy-extended' ),
			'.oe-swiper-button'
		)->whiteList();

		$arrows_border = $arrows_section->borderSection( __( 'Border', 'oxy-extended' ), '.oe-swiper-button', $this );
		//$arrows_section->borderSection( __( 'Hover Border', 'oxy-extended' ), '.oe-swiper-button:hover', $this );

		$arrows_section->boxShadowSection( __( 'Shadow', 'oxy-extended' ), '.oe-swiper-button', $this );
		$arrows_section->boxShadowSection( __( 'Hover Shadow', 'oxy-extended' ), '.oe-swiper-button:hover', $this );
	}

	public function pagination_controls() {
		$pagination = $this->addControlSection( 'pagination_style', __( 'Pagination', 'oxy-extended' ), 'assets/icon.png', $this );

		$pagination_type = $pagination->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Pagination Type', 'oxy-extended' ),
			'slug'      => 'pagination_type',
			'default'   => 'bullets',
			'value'     => [
				'none'     => __( 'None', 'oxy-extended' ),
				'bullets'  => __( 'Dots', 'oxy-extended' ),
				'fraction' => __( 'Fraction', 'oxy-extended' ),
				//'progress'		=> __( 'Progress', 'oxy-extended' ),
			],
		]);
		$pagination_type->rebuildElementOnChange();

		$pagination_dots = $pagination->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Dynamic Dots', 'oxy-extended' ),
			'slug'      => 'pagination_dynamic_dots',
			'default'   => 'no',
			'value'     => [
				'no'  => __( 'Disable', 'oxy-extended' ),
				'yes' => __( 'Enable', 'oxy-extended' ),
			],
			'condition' => 'pagination_type=bullets',
		]);
		$pagination_dots->setParam( 'description', __( 'Shows fewer dots visible at a time.', 'oxy-extended' ) );
		$pagination_dots->rebuildElementOnChange();

		$pos = $pagination->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Position', 'oxy-extended' ),
			'slug'      => 'pagination_position',
			'default'   => 'outside',
			'value'     => [
				'outside'   => __( 'Below Image', 'oxy-extended' ),
				'inside'    => __( 'Overlay', 'oxy-extended' ),
			],
			'condition' => 'pagination_type!=none',
		]);
		$pos->rebuildElementOnChange();

		$pagination->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Hide on Devices', 'oxy-extended' ),
			'slug'      => 'pagination_hide_responsive',
			'default'   => 'no',
			'value'     => [
				'no'  => __( 'No', 'oxy-extended' ),
				'yes' => __( 'Yes', 'oxy-extended' ),
			],
			'condition' => 'pagination_type!=none',
		]);

		$pagination_breakpoint = $pagination->addOptionControl([
			'type'      => 'measurebox',
			'name'      => __( 'Breakpoint', 'oxy-extended' ),
			'slug'      => 'pg_rspbp',
			'condition' => 'pagination_hide_responsive=yes',
		]);
		$pagination_breakpoint->setUnits( 'px', 'px' );
		$pagination_breakpoint->setDefaultValue( 680 );
		$pagination_breakpoint->setParam( 'description', 'Default breakpoint value is 680px.' );
		$pagination_breakpoint->rebuildElementOnChange();

		$pagination->addStyleControls([
			[
				'name'          => __( 'Dots/Fraction Position', 'oxy-extended' ),
				'selector'      => '.oe-gallery-slider.oe-gallery-slider-navigation-outside',
				'property'      => 'padding-bottom',
				'control_type'  => 'measurebox',
				'value'         => '40',
				'default'       => '40',
				'unit'          => 'px',
				'slug'          => 'bullets_position',
				'condition'     => 'pagination_type!=none&&pagination_position=outside',
			],
			[
				'selector'      => '.oe-gallery-slider .swiper-pagination-bullet, .oe-gallery-slider.swiper-container-horizontal>.swiper-pagination-progress',
				'property'      => 'background-color',
				'slug'          => 'pagination_bg_color',
				'condition'     => 'pagination_type=bullets',
			],
			[
				'name'          => __( 'Active Background Color', 'oxy-extended' ),
				'selector'      => '.oe-gallery-slider .swiper-pagination-bullet:hover, .oe-gallery-slider .swiper-pagination-bullet-active, .oe-gallery-slider .swiper-pagination-progress .swiper-pagination-progressbar',
				'property'      => 'background-color',
				'slug'          => 'pagination_bg_color_hover',
				'condition'     => 'pagination_type=bullets',
			],
			[
				'name'          => __( 'Dots Opacity', 'oxy-extended' ),
				'selector'      => '.swiper-pagination-bullet',
				'property'      => 'opacity',
				'default'       => '0.2',
				'slug'          => 'bullets_opacity',
				'condition'     => 'pagination_type=bullets',
			],
			[
				'name'          => __( 'Dots Width', 'oxy-extended' ),
				'selector'      => '.swiper-pagination-bullet',
				'property'      => 'width',
				'slug'          => 'dots_width',
				'condition'     => 'pagination_type=bullets',
			],
			[
				'name'          => __( 'Active Dots Width', 'oxy-extended' ),
				'selector'      => '.oe-gallery-slider .swiper-pagination-bullet-active',
				'property'      => 'width',
				'slug'          => 'active_dots_width',
				'condition'     => 'pagination_type=bullets',
			],
			[
				'name'          => __( 'Dots Height', 'oxy-extended' ),
				'selector'      => '.swiper-pagination-bullet',
				'property'      => 'height',
				'slug'          => 'dots_height',
				'condition'     => 'pagination_type=bullets',
			],
			[
				'selector'      => '.swiper-pagination-fraction',
				'property'      => 'width',
				'control_type'  => 'slider-measurebox',
				'default'       => 100,
				'step'          => 1,
				'min'           => 0,
				'max'           => 1000,
				'unit'          => '%',
				'condition'     => 'pagination_type=fraction',
			],
			[
				'name'          => __( 'Dots Border Radius', 'oxy-extended' ),
				'selector'      => '.swiper-pagination-bullet',
				'property'      => 'border-radius',
				'slug'          => 'dots_border_radius',
				'condition'     => 'pagination_type=bullets',
			],
			[
				'selector'      => '.swiper-pagination-fraction',
				'property'      => 'background-color',
				'condition'     => 'pagination_type=fraction',
			],
			[
				'selector'      => '.swiper-pagination-fraction',
				'property'      => 'color',
				'condition'     => 'pagination_type=fraction',
			],
			[
				'selector'      => '.swiper-pagination-fraction',
				'property'      => 'font-size',
				'condition'     => 'pagination_type=fraction',
			],
			[
				'selector'      => '.swiper-pagination-fraction',
				'property'      => 'font-weight',
				'condition'     => 'pagination_type=fraction',
			],
		]);

		$pagination->addStyleControl(
			[
				'selector'      => '.swiper-pagination-fraction',
				'property'      => 'padding-top',
				'control_type'  => 'measurebox',
				'unit'          => 'px',
				'condition'     => 'pagination_type=fraction',
			]
		)->setParam( 'hide_wrapper_end', true );

		$pagination->addStyleControl(
			[
				'selector'      => '.swiper-pagination-fraction',
				'property'      => 'padding-bottom',
				'control_type'  => 'measurebox',
				'unit'          => 'px',
				'condition'     => 'pagination_type=fraction',
			]
		)->setParam( 'hide_wrapper_start', true );

		$pagination->addStyleControl(
			[
				'selector'      => '.swiper-pagination-fraction',
				'property'      => 'padding-left',
				'control_type'  => 'measurebox',
				'unit'          => 'px',
				'condition'     => 'pagination_type=fraction',
			]
		)->setParam( 'hide_wrapper_end', true );

		$pagination->addStyleControl(
			[
				'selector'      => '.swiper-pagination-fraction',
				'property'      => 'padding-right',
				'control_type'  => 'measurebox',
				'unit'          => 'px',
				'condition'     => 'pagination_type=fraction',
			]
		)->setParam( 'hide_wrapper_start', true );
	}

	public function thumbnails_pagination_control() {
		$thumbs_pagination = $this->addControlSection( 'thumbs_pagination', __( 'Thumbnails Pagination', 'oxy-extended' ), 'assets/icon.png', $this );
		$thumbs_pagination->addCustomControl( __( '<div class="oxygen-option-default" style="color: #c3c5c7; line-height: 1.325; font-size: 13px;">Thumbnails Pagination works with slideshow slider type</div>', 'oxy-extended' ), 'pg_desc' );

		$thumbs_position = $thumbs_pagination->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Position', 'oxy-extended' ),
			'slug'      => 'thumbs_position',
			'default'   => 'below',
			'value'     => [
				'above'     => __( 'Above', 'oxy-extended' ),
				'below'     => __( 'Below', 'oxy-extended' ),
			],
		]);
		$thumbs_position->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_gallery_slider_type']=='slideshow'" );
		$thumbs_position->rebuildElementOnChange();

		$thumbs_size = $thumbs_pagination->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Thumbnails Resolution', 'oxy-extended' ),
			'slug'      => 'thumbs_size',
			'default'   => 'thumbnail',
			'value'     => [
				'thumbnail'     => __( 'Small', 'oxy-extended' ),
				'medium'        => __( 'Medium', 'oxy-extended' ),
				'large'         => __( 'Large', 'oxy-extended' ),
				'full'          => __( 'Full', 'oxy-extended' ),
			],
		]);
		$thumbs_size->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_gallery_slider_type']=='slideshow'" );
		$thumbs_size->rebuildElementOnChange();

		$thumbs_aspect_ratio = $thumbs_pagination->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Aspect Ratio', 'oxy-extended' ),
			'slug'      => 'thumb_ratio',
			'default'   => '43',
			'value'     => [
				'11'  => __( '1:1', 'oxy-extended' ),
				'43'  => __( '4:3', 'oxy-extended' ),
				'169' => __( '16:9', 'oxy-extended' ),
				'219' => __( '21:9', 'oxy-extended' ),
			],
		]);
		$thumbs_aspect_ratio->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_gallery_slider_type']=='slideshow'" );
		$thumbs_aspect_ratio->rebuildElementOnChange();

		$thumbs_pagination->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Hide on Devices', 'oxy-extended' ),
			'slug'      => 'hide_thumbs_mobile',
			'default'   => 'no',
			'value'     => [
				'no'  => __( 'No', 'oxy-extended' ),
				'yes' => __( 'Yes', 'oxy-extended' ),
			],
			'condition'     => 'gallery_slider_type=slideshow',
		]);

		$thumbs_breakpoint = $thumbs_pagination->addOptionControl([
			'type' => 'measurebox',
			'name' => __( 'Breakpoint', 'oxy-extended' ),
			'slug' => 'thumbs_breakpoint',
		]);
		$thumbs_breakpoint->setUnits( 'px', 'px' );
		$thumbs_breakpoint->setDefaultValue( 768 );
		$thumbs_breakpoint->setParam( 'description', 'Default breakpoint value is 768px.' );
		$thumbs_breakpoint->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_gallery_slider_type']=='slideshow'&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_hide_thumbs_mobile']=='yes'" );
		$thumbs_breakpoint->rebuildElementOnChange();

		//* Items per View
		$thumbs_count = $thumbs_pagination->addControlSection( 'thumbs_view', __( 'Items per View', 'oxy-extended' ), 'assets/icon.png', $this );
		$thumbs_count_desk = $thumbs_count->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'All Devices', 'oxy-extended' ),
			'slug'      => 'thumb_columns',
			'condition' => 'gallery_slider_type=slideshow',
		]);

		$thumbs_count_desk->setUnits( ' ', ' ' );
		$thumbs_count_desk->setRange( '1', '10', '1' );
		$thumbs_count_desk->setValue( '4' );
		$thumbs_count_desk->rebuildElementOnChange();

		$thumbs_count_lg = $thumbs_count->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Large Devices (Range: 769px to 992px)', 'oxy-extended' ),
			'slug'      => 'thumb_columns_tablet',
			'condition' => 'gallery_slider_type=slideshow',
		]);

		$thumbs_count_lg->setUnits( ' ', ' ' );
		$thumbs_count_lg->setRange( '1', '10', '1' );
		$thumbs_count_lg->setValue( '3' );
		$thumbs_count_lg->rebuildElementOnChange();

		$thumbs_count_md = $thumbs_count->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Medium Devices (Range: 640px to 768px)', 'oxy-extended' ),
			'slug'      => 'thumb_columns_landscape',
			'condition' => 'gallery_slider_type=slideshow',
		]);

		$thumbs_count_md->setUnits( ' ', ' ' );
		$thumbs_count_md->setRange( '1', '10', '1' );
		$thumbs_count_md->setValue( '2' );
		$thumbs_count_md->rebuildElementOnChange();

		$thumbs_count_sm = $thumbs_count->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Small Devices (Below 640px)', 'oxy-extended' ),
			'slug'      => 'thumb_columns_portrait',
			'condition' => 'gallery_slider_type=slideshow',
		]);

		$thumbs_count_sm->setUnits( ' ', ' ' );
		$thumbs_count_sm->setRange( '1', '10', '1' );
		$thumbs_count_sm->setValue( '2' );
		$thumbs_count_sm->rebuildElementOnChange();

		$thumbs_settings = $thumbs_pagination->addControlSection( 'thumbs_effect', __( 'Effect', 'oxy-extended' ), 'assets/icon.png', $this );

		//*Opacity
		$thumbs_settings->addStyleControls([
			[
				'name'          => __( 'Initial Opacity', 'oxy-extended' ),
				'selector'      => '.oe-gallery-slider-thumb',
				'property'      => 'opacity',
				'value'         => 1,
				'units'         => ' ',
				'min'           => 0,
				'max'           => 1,
				'step'          => 0.1,
				'condition'     => 'gallery_slider_type=slideshow',
			],
			[
				'name'          => __( 'Opacity for Active or Hover Thumbnail', 'oxy-extended' ),
				'selector'      => '.swiper-slide-active .oe-gallery-slider-thumb, .oe-gallery-slider-thumb:hover',
				'property'      => 'opacity',
				'value'         => 1,
				'units'         => ' ',
				'min'           => 0,
				'max'           => 1,
				'step'          => 0.1,
				'condition'     => 'gallery_slider_type=slideshow',
			],
		]);

		$thumbs_transition_duration = $thumbs_settings->addStyleControl([
			'name'          => __( 'Transition Duration', 'oxy-extended' ),
			'selector'      => '.oe-thumbnails-swiper .oe-gallery-slider-thumb',
			'property'      => 'transition-duration',
			'control_type'  => 'slider-measurebox',
		]);
		$thumbs_transition_duration->setRange( '0', '20', '.1' );
		$thumbs_transition_duration->setUnits( 's', 'sec' );
		$thumbs_transition_duration->setDefaultValue( '0.5' );

		$thumbs_intital_scale = $thumbs_settings->addStyleControl([
			'name'          => __( 'Scale (Initial State)', 'oxy-extended' ),
			'selector'      => '.oe-gallery-slider-thumb',
			'property'      => '--thumb-initial-scale',
			'control_type'  => 'slider-measurebox',
		]);
		$thumbs_intital_scale->setRange( -5, 5, 0.01 );
		$thumbs_intital_scale->setUnits( '', ' ' );
		$thumbs_intital_scale->setDefaultValue( '1' );

		$thumbs_hover_scale = $thumbs_settings->addStyleControl([
			'name'          => __( 'Scale (Hover/Active State)', 'oxy-extended' ),
			'selector'      => '.oe-gallery-slider-thumb',
			'property'      => '--thumb-hover-scale',
			'control_type'  => 'slider-measurebox',
		]);
		$thumbs_hover_scale->setRange( -5, 5, 0.01 );
		$thumbs_hover_scale->setUnits( '', ' ' );
		$thumbs_hover_scale->setDefaultValue( '1' );
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
		$caption_description->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_oe_gallery_source']!='acf_repeater'&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_show_caption']=='yes'" );
		$caption_description->rebuildElementOnChange();

		$caption_position = $caption->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Caption Position', 'oxy-extended' ),
				'slug'    => 'caption_position',
				'value'   => array(
					'overlay'     => __( 'Overlay', 'oxy-extended' ),
					'below_image' => __( 'Below Image', 'oxy-extended' ),
				),
				'default' => 'overlay',
			)
		);
		$caption_position->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_show_caption']=='yes'" );
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
				'condition' => 'show_caption=yes&&caption_position=overlay',
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
				'condition' => 'show_caption=yes&&caption_position=overlay',
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
			'Left'  => '.oe-gallery-slider-content{text-align: left;}',
			'Right' => '.oe-gallery-slider-content{text-align: right;}',
		]);
		$content_align->setDefaultValue( 'Center' );
		$content_align->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_show_caption']=='yes'" );
		$content_align->whiteList();

		$style = $caption->addControlSection( 'caption_style', __( 'Style', 'oxy-extended' ), 'assets/icon.png', $this );

		$style->addStyleControls([
			[
				'selector'      => '.oe-image-caption',
				'property'      => 'background-color',
				'slug'          => 'oeg_captionbg',
			],
		]);

		$style->addPreset(
			'padding',
			'oegcapsp_padding',
			__( 'Padding', 'oxy-extended' ),
			'.oe-image-caption'
		)->whiteList();

		$style->addPreset(
			'margin',
			'oegcapsp_margin',
			__( 'Margin', 'oxy-extended' ),
			'.oe-image-caption'
		)->whiteList();

		$caption->typographySection( __( 'Title', 'oxy-extended' ), '.oe-gallery-slider-caption-title', $this );
		$desc_gap = $caption->typographySection( __( 'Description', 'oxy-extended' ), '.oe-gallery-slider-caption-text', $this );

		$desc_gap->addPreset(
			'margin',
			'oegcaptxt_margin',
			__( 'Margin', 'oxy-extended' ),
			'.oe-gallery-slider-caption-text'
		)->whiteList();
	}

	public function lightbox_controls() {
		$lightbox = $this->addControlSection( 'lightbox_section', __( 'Lightbox', 'oxy-extended' ), 'assets/icon.png', $this );

		$lightbox->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Enable Lightbox', 'oxy-extended' ),
				'slug'      => 'enable_lightbox',
				'value'     => array(
					'no'  => __( 'No', 'oxy-extended' ),
					'yes' => __( 'Yes', 'oxy-extended' ),
				),
				'default'   => 'no',
			)
		);

		$lightbox->addOptionControl(
			array(
				'name'          => __( 'Animation Effect', 'oxy-extended' ),
				'slug'          => 'lb_aimeffect',
				'type'          => 'radio',
				'value'         => [
					'none'                  => __( 'None', 'oxy-extended' ),
					'mfp-zoom-in'           => __( 'Zoom', 'oxy-extended' ),
					'mfp-newspaper'         => __( 'Newspaper', 'oxy-extended' ),
					'mfp-move-horizontal'   => __( 'Move Horizontal', 'oxy-extended' ),
					'mfp-move-from-top'     => __( 'Move from Top', 'oxy-extended' ),
					'mfp-3d-unfold'         => __( '3d Unfold', 'oxy-extended' ),
					'mfp-zoom-out'          => __( 'Zoom Out', 'oxy-extended' ),
				],
				'default'       => 'mfp-zoom-in',
			)
		)->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_gallery_slider_enable_lightbox']=='yes'" );
	}

	public function render_thumbnails( $options, $images ) {
		if ( 'slideshow' === $options['gallery_slider_type'] && count( $images ) > 1 ) :
			$thumbs_size = isset( $options['thumbs_size'] ) ? $options['thumbs_size'] : 'thumbnail'; ?>
			<div class="oe-thumbnails-swiper swiper-container oe-thumbs-ratio-<?php echo $options['thumb_ratio']; ?>">
				<div class="swiper-wrapper">
					<?php
					foreach ( $images as $i => $image_id ) :
						if ( is_array( $image_id ) ) {
							$thumb = wp_get_attachment_image_src( $image_id[0], $thumbs_size );
						} else {
							$thumb = wp_get_attachment_image_src( $image_id, $thumbs_size );
						}
						?>
						<div class="swiper-slide">
							<div class="oe-gallery-slider-thumb" style="background-image:url(<?php echo $thumb[0]; ?>)"></div>
						</div>
						<?php
					endforeach; ?>
				</div>
			</div>
			<?php
		endif;
	}

	public function render( $options, $defaults, $content ) {
		$uid = str_replace( '.', '', uniqid( 'oegs', true ) );

		if ( ! empty( $options['oe_gallery_source'] ) && 'media' === $options['oe_gallery_source'] && isset( $options['oe_gallery_images'] ) ) {

			$images = explode( ',', $options['oe_gallery_images'] );

		} elseif ( ! empty( $options['oe_gallery_source'] ) && 'acf' === $options['oe_gallery_source'] ) {

			if ( empty( $options['field_name'] ) ) {
				echo __( 'Enter Gallery Field Key.', 'oxy-extended' );
			} else {
				if ( ! empty( $options['acf_gallery_source'] ) && 'other' === $options['acf_gallery_source'] ) {
					$post_id = (int) $options['page_id'];
				} else {
					$post_id = get_the_id();
				}

				$meta_single = true;

				if ( class_exists( 'RWMB_Loader' ) ) {
					$settings = rwmb_get_field_settings( $options['field_name'] );
					if ( ! empty( $settings ) && is_array( $settings ) ) {
						if ( 'image_advanced' === $settings['type'] ) {
							$meta_single = false;
						}
					}
				}

				if ( class_exists( 'WooCommerce' ) && '_product_image_gallery' === $options['field_name'] ) {
					$images = array();
					if ( has_post_thumbnail( $post_id ) && is_singular( 'product' ) ) {
						$images[] = get_post_thumbnail_id( $post_id );
					}

					$product = wc_get_product( $post_id );
					if ( @method_exists( $product, 'get_gallery_attachment_ids' ) ) {
						$images = @array_merge( $images, $product->get_gallery_attachment_ids() );
					}
				} else {
					$images = get_post_meta( $post_id, $options['field_name'], $meta_single );

					if ( ! $images ) {
						$images = get_option( 'options_' . $options['field_name'] );
					}
				}

				if ( isset( $images['pod_item_id'] ) ) {
					$images = get_post_meta( $post_id, '_pods_' . $options['field_name'], $meta_single );
				}
			}
		} elseif ( ! empty( $options['oe_gallery_source'] ) && 'acf_repeater' === $options['oe_gallery_source'] && function_exists( 'have_rows' ) ) {

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

		if ( $images ) :

			$image_size = isset( $options['image_size'] ) ? $options['image_size'] : 'full';

			if ( isset( $options['oe_gallery_order'] ) && 'rand' === $options['oe_gallery_order'] ) {
				$images = $this->oe_applyRandomOrder( $images );
			}

			if ( isset( $options['thumbs_position'] ) && 'above' === $options['thumbs_position'] ) {
				$this->render_thumbnails( $options, $images );
			}
			?>
			<div class="oe-gallery-slider oe-gallery-slider-<?php echo $uid; ?> swiper-container<?php echo ( 'slideshow' === $options['gallery_slider_type'] ) ? ' oe-gallery-slider-slideshow' : ''; ?> oe-gallery-slider-navigation-<?php echo $options['pagination_position']; ?><?php if ( 'kenburns' === $options['oe_gallery_slider_effect'] ) {
				?> swiper-scale-effect<?php } ?>">
				<div class="swiper-wrapper auto-height-<?php echo $options['oe_gs_auto_height']; ?>">
					<?php foreach ( $images as $key => $image_id ) {
						$alt = '';
						if ( is_array( $image_id ) ) {
							$slider_image = wp_get_attachment_image_src( $image_id[0], $image_size );
							$srcset = wp_get_attachment_image_srcset( $image_id[0], $image_size );
							$attachment_id = $image_id[0];
						} else {
							$slider_image = wp_get_attachment_image_src( $image_id, $image_size );
							$srcset = wp_get_attachment_image_srcset( $image_id, $image_size );
							$attachment_id = $image_id;
						}

						$meta = $this->oegsld_get_attachment_data( $attachment_id );
						$alt = ! empty( $meta->alt ) ? ' alt="' . $meta->alt . '"' : ( ! empty( $meta->title ) ? ' alt="' . $meta->title . '"' : '' );
						?>
						<div class="swiper-slide oe-gallery-slider-item">
							<?php
							if ( isset( $options['enable_lightbox'] ) && 'yes' === $options['enable_lightbox'] ) {

								if ( is_array( $image_id ) ) {
									$full_image = wp_get_attachment_image_src( $image_id[0], 'full' );
								} else {
									$full_image = wp_get_attachment_image_src( $image_id, 'full' );
								}

								printf( '<a href="%1$s" data-effect="%2$s">', esc_url( $full_image[0] ), $options['lb_aimeffect'] );
							}
							?>
							<?php
							if ( isset( $options['oe_gs_auto_height'] ) && 'yes' === $options['oe_gs_auto_height'] ) :
								list($image_src, $image_width, $image_height) = $slider_image;

								?>
								<div class="oe-gallery-slider-image-container<?php if ( 'kenburns' === $options['oe_gallery_slider_effect'] ) {
									?> swiper-slide-cover<?php } ?>">
									<img src="<?php echo esc_url( $slider_image[0] ); ?>" srcset="<?php echo $srcset; ?>" sizes="(max-width: <?php echo $image_width; ?>px) 100vw, <?php echo $image_width; ?>px" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>"<?php echo $alt; ?>/>
								</div>
							<?php else : ?>
								<div class="oegsld-img oe-gallery-slider-image-container<?php if ( 'kenburns' === $options['oe_gallery_slider_effect'] ) {
									?> swiper-slide-cover<?php } ?>" style="background-image:url(<?php echo esc_url( $slider_image[0] ); ?>)"></div>
							<?php endif; ?>

							<?php
							if ( isset( $options['show_caption'] ) && 'yes' === $options['show_caption'] ) {
								if ( isset( $options['caption_position'] ) && 'below_image' === $options['caption_position'] ) {
									$caption_cssclass = ' basic-caption';
								} else {
									$caption_cssclass = ' oe-media-content caption-' . $options['caption_vertical_align'];
								}
								?>
								<div class="oe-gallery-slider-content<?php echo $caption_cssclass; ?>">
									<div class="oe-image-caption">
									<?php
									if ( 'acf_repeater' === $options['oe_gallery_source'] ) {
										if ( ! empty( $image_id['title'] ) ) : ?>
											<h3 class="oe-gallery-slider-caption-title"><?php echo $image_id['title']; ?></h3>
										<?php endif; ?>
										<?php if ( ! empty( $image_id['desc'] ) ) : ?>
											<div class="oe-gallery-slider-caption-text"><?php echo $image_id['desc']; ?></div>
										<?php endif; ?>

										<?php if ( ! empty( $image_id['btnTxt'] ) && ! empty( $image_id['btnLink'] ) ) : ?>
											<a href="<?php echo esc_url( $image_id['btnLink'] ); ?>" role="button" class="oe-gallery-slider-caption-btn"><?php echo $image_id['btnTxt']; ?></a>
										<?php endif; ?>
										<?php
									} else {
										$data = $this->oegsld_get_attachment_data( $image_id );
										$title = ! empty( $data->title ) ? $data->title : ( ! empty( $data->alt ) ? $data->alt : '' );
										if ( $title ) {
											echo '<h3 class="oe-gallery-slider-caption-title">' . esc_attr( $title ) . '</h3>';
										}

										$desc = ! empty( $data->description ) ? $data->description : ( ! empty( $data->caption ) ? $data->caption : '' );
										if ( $desc && isset( $options['show_caption_description'] ) && 'yes' === $options['show_caption_description'] ) {
											echo '<p class="oe-gallery-slider-caption-text">' . $desc . '</p>';
										}
									}
									?>
									</div>
								</div>
							<?php } ?>

							<?php if ( isset( $options['enable_lightbox'] ) && 'yes' === $options['enable_lightbox'] ) {
								echo '</a>'; } ?>
						</div>
					<?php } ?>
				</div>

				<?php
				if ( 1 < count( $images ) ) {
					if ( 'none' !== $options['pagination_type'] ) {
						?><div class="swiper-pagination"></div><?php
					}
				}
				?>

				<?php if ( 'yes' === $options['slider_navigation'] && count( $images ) > 1 ) {
					global $oxygen_svg_icons_to_load; ?>
					<?php if ( isset( $options['arrow_left'] ) ) {
						$oxygen_svg_icons_to_load[] = $options['arrow_left']; ?>
						<div class="oe-swiper-button<?php if ( isset( $options['arrows_on_hover'] ) && 'yes' === $options['arrows_on_hover'] ) {
							?> show-on-hover<?php } ?> oe-swiper-button-prev">
							<svg><use xlink:href="#<?php echo $options['arrow_left']; ?>"></use></svg>
						</div>
					<?php } else { ?>
						<div class="oe-swiper-button<?php if ( isset( $options['arrows_on_hover'] ) && 'yes' === $options['arrows_on_hover'] ) {
							?> show-on-hover<?php } ?> oe-swiper-button-prev">
							<svg><use xlink:href="#Lineariconsicon-chevron-left"></use></svg>
						</div>
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1"><defs><symbol id="Lineariconsicon-chevron-left" viewBox="0 0 20 20"><title>chevron-left</title><path class="path1" d="M14 20c0.128 0 0.256-0.049 0.354-0.146 0.195-0.195 0.195-0.512 0-0.707l-8.646-8.646 8.646-8.646c0.195-0.195 0.195-0.512 0-0.707s-0.512-0.195-0.707 0l-9 9c-0.195 0.195-0.195 0.512 0 0.707l9 9c0.098 0.098 0.226 0.146 0.354 0.146z"/></symbol></defs></svg>
					<?php } if ( isset( $options['arrow_right'] ) ) {
						$oxygen_svg_icons_to_load[] = $options['arrow_right']; ?>
						<div class="oe-swiper-button<?php if ( isset( $options['arrows_on_hover'] ) && 'yes' === $options['arrows_on_hover'] ) {
							?> show-on-hover<?php } ?> oe-swiper-button-next"><svg><use xlink:href="#<?php echo $options['arrow_right']; ?>"></use></svg></div>
					<?php } else { ?>
						<div class="oe-swiper-button<?php if ( isset( $options['arrows_on_hover'] ) && 'yes' === $options['arrows_on_hover'] ) {
							?> show-on-hover<?php } ?> oe-swiper-button-next"><svg><use xlink:href="#Lineariconsicon-chevron-right"></use></svg></div>

						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1"><defs><symbol id="Lineariconsicon-chevron-right" viewBox="0 0 20 20"><title>chevron-right</title><path class="path1" d="M5 20c-0.128 0-0.256-0.049-0.354-0.146-0.195-0.195-0.195-0.512 0-0.707l8.646-8.646-8.646-8.646c-0.195-0.195-0.195-0.512 0-0.707s0.512-0.195 0.707 0l9 9c0.195 0.195 0.195 0.512 0 0.707l-9 9c-0.098 0.098-0.226 0.146-0.354 0.146z"/></symbol></defs></svg>
					<?php } ?>
				<?php } ?>
			</div>
			<?php
			if ( isset( $options['thumbs_position'] ) && 'below' === $options['thumbs_position'] ) {
				$this->render_thumbnails( $options, $images );
			}

			if ( ! isset( $options['thumbs_position'] ) ) {
				$this->render_thumbnails( $options, $images );
			}

			ob_start();
			?>
				jQuery(document).ready(function($){
					<?php
					$slides_to_scroll = $slides_to_scroll_tablet = $slides_to_scroll_landscape = $slides_to_scroll_portrait = 1;
					if ( isset( $options['slides_to_scroll'] ) && 'slideshow' !== $options['gallery_slider_type'] ) {
						$slides_to_scroll = $options['slides_to_scroll'];
					}
					if ( isset( $options['slides_to_scroll_tablet'] ) && 'slideshow' !== $options['gallery_slider_type'] ) {
						$slides_to_scroll_tablet = $options['slides_to_scroll_tablet'];
					} elseif ( 'slideshow' !== $options['gallery_slider_type'] ) {
						$slides_to_scroll_tablet = $slides_to_scroll;
					}
					if ( isset( $options['slides_to_scroll_landscape'] ) && 'slideshow' !== $options['gallery_slider_type'] ) {
						$slides_to_scroll_landscape = $options['slides_to_scroll_landscape'];
					} elseif ( 'slideshow' !== $options['gallery_slider_type'] ) {
						$slides_to_scroll_landscape = $slides_to_scroll_tablet;
					}
					if ( isset( $options['slides_to_scroll_portrait'] ) && 'slideshow' !== $options['gallery_slider_type'] ) {
						$slides_to_scroll_portrait = $options['slides_to_scroll_portrait'];
					} elseif ( 'slideshow' !== $options['gallery_slider_type'] ) {
						$slides_to_scroll_portrait = $slides_to_scroll_landscape;
					}

					if ( isset( $options['enable_lightbox'] ) && 'yes' === $options['enable_lightbox'] && ! defined( 'OXY_ELEMENTS_API_AJAX' ) ) {
						wp_enqueue_style( 'oe-mfp-style' );
						wp_enqueue_script( 'oe-mfp-script' );
						?>
							var oeGallerySlider = $( '.oe-gallery-slider-<?php echo $uid; ?>' );
							if ( oeGallerySlider.length && typeof $.fn.magnificPopup !== 'undefined') {
								oeGallerySlider.magnificPopup({
									delegate: '.oe-gallery-slider-item a',
									closeBtnInside: true,
									type: 'image',
									gallery: {
										enabled: true,
										navigateByImgClick: true,
										tCounter: ''
									},
									removalDelay: 500,
									fixedContentPos: 'auto',
									callbacks: {
										beforeOpen: function() { 
											this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
											this.st.mainClass = this.st.el.attr('data-effect');
										}
									}
								});
							}
						<?php
					}
					?>
					var settings = {
						id: '<?php echo $uid; ?>',
						type: '<?php echo $options['gallery_slider_type']; ?>',
						initialSlide: 0,
						spaceBetween: {
							desktop: '<?php echo $options['items_gap']; ?>',
							tablet: '<?php echo $options['items_gap_tablet']; ?>',
							landscape: '<?php echo $options['items_gap_landscape']; ?>',
							portrait: '<?php echo $options['items_gap_portrait']; ?>'
						},
						slidesPerView: {
							desktop: <?php echo $options['columns']; ?>,
							tablet: <?php echo $options['columns_tablet']; ?>,
							landscape: <?php echo $options['columns_landscape']; ?>,
							portrait: <?php echo $options['columns_portrait']; ?>
						},
						slidesPerColumn: {
							desktop: <?php echo $options['rows']; ?>,
							tablet: <?php echo $options['rows_tablet']; ?>,
							landscape: <?php echo $options['rows_landscape']; ?>,
							portrait: <?php echo $options['rows_portrait']; ?>
						},
						slidesToScroll: {
							desktop: <?php echo $slides_to_scroll; ?>,
							tablet: <?php echo $slides_to_scroll_tablet; ?>,
							landscape: <?php echo $slides_to_scroll_landscape; ?>,
							portrait: <?php echo $slides_to_scroll_portrait; ?>
						},
						slideshow_slidesPerView: {
							desktop: <?php echo $options['thumb_columns']; ?>,
							tablet: <?php echo $options['thumb_columns_tablet']; ?>,
							landscape: <?php echo $options['thumb_columns_landscape']; ?>,
							portrait: <?php echo $options['thumb_columns_portrait']; ?>
						},
						effect: '<?php echo ( ( 'kenburns' === $options['oe_gallery_slider_effect'] ) ? 'fade' : $options['oe_gallery_slider_effect'] ); ?>',
						isBuilderActive: <?php echo ( defined( 'OXY_ELEMENTS_API_AJAX' ) ) ? 'true' : 'false'; ?>,
						autoplay: <?php echo 'yes' === $options['autoplay'] ? 'true' : 'false'; ?>,
						autoplay_speed: <?php echo 'yes' === $options['autoplay'] ? $options['autoplay_speed'] : 'false'; ?>,
						pagination: '<?php echo $options['pagination_type']; ?>',
						dynamicBullets: <?php echo 'yes' === $options['pagination_dynamic_dots'] ? 'true' : 'false'; ?>,
						centered: <?php echo 'yes' === $options['centered_slide'] ? 'true' : 'false'; ?>,
						loop: <?php echo ( 'yes' === $options['oegsld_loop'] && absint( $options['rows'] ) <= 1 ) ? 'true' : 'false'; ?>,
						pause_on_hover: <?php echo ( ( 'yes' === $options['pause_on_hover'] && 'yes' === $options['autoplay'] ) ? 'true' : 'false' ); ?>,
						pause_on_interaction: <?php echo 'yes' === $options['pause_on_interaction'] ? 'true' : 'false'; ?>,
						speed: <?php echo isset( $options['transition_speed'] ) ? $options['transition_speed'] : 5000; ?>,
						autoHeight: <?php echo 'yes' === $options['oe_gs_auto_height'] ? 'true' : 'false'; ?>,
						navigation: {
							nextEl: '.oe-swiper-button-next',
							prevEl: '.oe-swiper-button-prev',
						},
						breakpoint: {
							desktop: 993,
							tablet: 768,
							landscape: 640,
							portrait: 100
						}
					};
					oegslider_<?php echo $uid; ?> = new OEGallerySlider(settings);

					function OEUpdateCarousel() {
						setTimeout(function() {
							if ( 'number' !== typeof oegslider_<?php echo $uid; ?>.swipers.main.length ) {
								oegslider_<?php echo $uid; ?>.swipers.main.update();
							} else {
								oegslider_<?php echo $uid; ?>.swipers.main.forEach(function(item) {
									if ( 'undefined' !== typeof item ) {
										item.update();
									}
								});
							}
						}, 10);
					}

					$(document).on('oe-accordion-slide-complete', function(e) {
						if ( $(e.target).find('.oe-gallery-slider-<?php echo $uid; ?>').length > 0 ) {
							OEUpdateCarousel();
						}
					});

					if ( $('.oxy-tab').length > 0 ) {
						$('.oxy-tab').on('click', function(e) {
							setTimeout(function(){ OEUpdateCarousel(); }, 5 );
						});
					}
				});
			<?php
			if ( isset( $_GET['oxygen_iframe'] ) || defined( 'OXY_ELEMENTS_API_AJAX' ) ) {
				$this->El->builderInlineJS( ob_get_clean() );
			} else {
				$this->slide_js_code[] = ob_get_clean();

				if ( ! $this->js_added ) {
					add_action( 'wp_footer', array( $this, 'oegsld_enqueue_scripts' ) );
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

		if ( isset( $original[ $prefix . '_oe_kenburns_ts' ] ) ) {
			$css .= $selector . ' .swiper-scale-effect .swiper-slide-cover{transform: scale(' . $original[ $prefix . '_oe_kenburns_ts' ] . ');}';
			$css .= $selector . ' .swiper-scale-effect .swiper-slide.swiper-slide-active .swiper-slide-cover { transform: scale(1);}';
		}

		if ( isset( $original[ $prefix . '_show_caption' ] ) && 'yes' === $original[ $prefix . '_show_caption' ] ) {
			$css .= $selector . ' .oe-gallery-slider-content { display: flex; flex-direction: column; }';

			$css .= $selector . ' .oe-gallery-slider-caption-title, ' . $selector . ' .oe-gallery-slider-caption-text{width: 100%; line-height: 1.35;}';
		}

		if ( isset( $original[ $prefix . '_gallery_slider_type' ] ) && 'slideshow' === $original[ $prefix . '_gallery_slider_type' ] ) {
			if ( ! isset( $original[ $prefix . '_thumbs_position' ] ) || ( isset( $original[ $prefix . '_thumbs_position' ] ) && 'below' === $original[ $prefix . '_thumbs_position' ] ) ) {
				$css .= $selector . ' .oe-gallery-slider-slideshow {margin-bottom:' . ( isset( $original[ $prefix . '_items_gap' ] ) ? $original[ $prefix . '_items_gap' ] : '15' ) . 'px}';

				if ( isset( $original[ $prefix . '_items_gap_tablet' ] ) ) {
					$css .= '@media only screen and (max-width: ' . oxygen_vsb_get_breakpoint_width( 'tablet' ) . 'px){' . $selector . ' .oe-gallery-slider-slideshow {margin-bottom:' . $original[ $prefix . '_items_gap_tablet' ] . 'px}}';
				}

				if ( isset( $original[ $prefix . '_items_gap_landscape' ] ) ) {
					$css .= '@media only screen and (max-width: ' . oxygen_vsb_get_breakpoint_width( 'phone-landscape' ) . 'px){' . $selector . ' .oe-gallery-slider-slideshow {margin-bottom:' . $original[ $prefix . '_items_gap_landscape' ] . 'px}}';
				}

				if ( isset( $original[ $prefix . '_items_gap_portrait' ] ) ) {
					$css .= '@media only screen and (max-width: ' . oxygen_vsb_get_breakpoint_width( 'phone-portrait' ) . 'px){' . $selector . ' .oe-gallery-slider-slideshow {margin-bottom:' . $original[ $prefix . '_items_gap_portrait' ] . 'px}}';
				}
			}

			if ( isset( $original[ $prefix . '_thumbs_position' ] ) && 'above' === $original[ $prefix . '_thumbs_position' ] ) {
				$css .= $selector . ' .oe-gallery-slider.oe-gallery-slider-slideshow {margin-top:' . $original[ $prefix . '_items_gap' ] . 'px}';

				if ( isset( $original[ $prefix . '_items_gap_tablet' ] ) ) {
					$css .= '@media only screen and (max-width: ' . oxygen_vsb_get_breakpoint_width( 'tablet' ) . 'px){' . $selector . ' .oe-gallery-slider-slideshow {margin-top:' . $original[ $prefix . '_items_gap_tablet' ] . 'px}}';
				}

				if ( isset( $original[ $prefix . '_items_gap_landscape' ] ) ) {
					$css .= '@media only screen and (max-width: ' . oxygen_vsb_get_breakpoint_width( 'phone-landscape' ) . 'px){' . $selector . ' .oe-gallery-slider-slideshow {margin-top:' . $original[ $prefix . '_items_gap_landscape' ] . 'px}}';
				}

				if ( isset( $original[ $prefix . '_items_gap_portrait' ] ) ) {
					$css .= '@media only screen and (max-width: ' . oxygen_vsb_get_breakpoint_width( 'phone-portrait' ) . 'px){' . $selector . ' .oe-gallery-slider-slideshow {margin-top:' . $original[ $prefix . '_items_gap_portrait' ] . 'px}}';
				}
			}

			if ( isset( $original[ $prefix . '_hide_thumbs_mobile' ] ) && 'yes' === $original[ $prefix . '_hide_thumbs_mobile' ] ) {
				$thumbs_breakpoint = isset( $original[ $prefix . '_thumbs_breakpoint' ] ) ? $original[ $prefix . '_thumbs_breakpoint' ] : 768;
				$css .= '@media only screen and (max-width: ' . absint( $thumbs_breakpoint ) . 'px){' . $selector . ' .oe-thumbnails-swiper{display:none}}';
			}

			$css .= $selector . ' .oe-gallery-slider-thumb{transform: scale(var(--thumb-initial-scale));}';
			$css .= $selector . ' .swiper-slide-active .oe-gallery-slider-thumb, ' . $selector . ' .oe-gallery-slider-thumb:hover{transform: scale(var(--thumb-hover-scale));}';
		}

		if ( isset( $original[ $prefix . '_slider_navigation' ] ) && 'yes' === $original[ $prefix . '_slider_navigation' ] ) {
			$prev_position = $next_position = '';
			if ( isset( $original[ $prefix . '_prev_arrow_top_position-unit' ] ) && 'auto' === $original[ $prefix . '_prev_arrow_top_position-unit' ] ) {
				$prev_position .= 'top: auto;';
			}
			if ( isset( $original[ $prefix . '_prev_arrow_bottom_position-unit' ] ) && 'auto' === $original[ $prefix . '_prev_arrow_bottom_position-unit' ] ) {
				$prev_position .= 'bottom: auto;';
			}
			if ( isset( $original[ $prefix . '_prev_arrow_left_position-unit' ] ) && 'auto' === $original[ $prefix . '_prev_arrow_left_position-unit' ] ) {
				$prev_position .= 'left: auto;';
			}
			if ( isset( $original[ $prefix . '_prev_arrow_right_position-unit' ] ) && 'auto' === $original[ $prefix . '_prev_arrow_right_position-unit' ] ) {
				$prev_position .= 'right: auto;';
			}

			if ( isset( $original[ $prefix . '_next_arrow_top_position-unit' ] ) && 'auto' === $original[ $prefix . '_next_arrow_top_position-unit' ] ) {
				$next_position .= 'top: auto;';
			}
			if ( isset( $original[ $prefix . '_next_arrow_bottom_position-unit' ] ) && 'auto' === $original[ $prefix . '_next_arrow_bottom_position-unit' ] ) {
				$next_position .= 'bottom: auto;';
			}
			if ( isset( $original[ $prefix . '_next_arrow_left_position-unit' ] ) && 'auto' === $original[ $prefix . '_next_arrow_left_position-unit' ] ) {
				$next_position .= 'left: auto;';
			}
			if ( isset( $original[ $prefix . '_next_arrow_right_position-unit' ] ) && 'auto' === $original[ $prefix . '_next_arrow_right_position-unit' ] ) {
				$next_position .= 'right: auto;';
			}
			$css .= $selector . ' .oe-swiper-button-prev{' . $prev_position . '}';
			$css .= $selector . ' .oe-swiper-button-next{' . $next_position . '}';

			if ( isset( $original[ $prefix . '_arrows_hide_responsive' ] ) && 'yes' === $original[ $prefix . '_arrows_hide_responsive' ] ) {
				$arrows_breakpoint = isset( $original[ $prefix . '_arrows_breakpoint' ] ) ? $original[ $prefix . '_arrows_breakpoint' ] : 680;
				$css .= '@media only screen and (max-width: ' . absint( $arrows_breakpoint ) . 'px){' . $selector . ' .oe-swiper-button{display:none;}}';
			}

			if ( isset( $original[ $prefix . '_arrows_on_hover' ] ) && 'yes' === $original[ $prefix . '_arrows_on_hover' ] ) {
				$css .= 'body:not(.oxygen-builder-body) ' . $selector . ' .oe-swiper-button.show-on-hover{display:none;}';
				$css .= 'body:not(.oxygen-builder-body) ' . $selector . ':hover .oe-swiper-button.show-on-hover{display:inline-flex;}';
			}
		}

		if ( isset( $original[ $prefix . '_pagination_type' ] ) && 'none' !== $original[ $prefix . '_pagination_type' ] ) {
			if ( isset( $original[ $prefix . '_pagination_hide_responsive' ] ) && 'yes' === $original[ $prefix . '_pagination_hide_responsive' ] ) {
				$dots_breakpoint = isset( $original[ $prefix . '_pg_rspbp' ] ) ? $original[ $prefix . '_pg_rspbp' ] : 680;
				$css .= '@media only screen and (max-width: ' . absint( $dots_breakpoint ) . 'px){' . $selector . ' .swiper-pagination{display:none;}}';
			}
		}

		return $css;
	}

	public function init() {
		$this->El->useAJAXControls();
		if ( isset( $_GET['oxygen_iframe'] ) ) {
			add_action( 'wp_footer', array( $this, 'oegsld_enqueue_scripts' ) );
		}
	}

	public function oegsld_enqueue_scripts() {
		wp_enqueue_style( 'oe-swiper-style' );
		wp_enqueue_script( 'oe-swiper-script' );
		wp_enqueue_script( 'oegs-slider-script' );
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

	public function oegsld_get_attachment_data( $id ) {
		$data = wp_prepare_attachment_for_js( $id );

		if ( gettype( $data ) == 'array' ) {
			return json_decode( json_encode( $data ) );
		}

		return $data;
	}

	public function enablePresets() {
		return true;
	}

	public function enableFullPresets() {
		return true;
	}
}

new OEGallerySlider();
