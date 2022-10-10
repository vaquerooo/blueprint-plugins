<?php
namespace Oxygen\OxyExtended;

use OxyExtended\Classes\OE_Helper;

/**
 * Album Element
 */
class OEAlbum extends \OxyExtendedEl {

	public $css_added = false;
	private $album_js_code = array();
	/**
	 * Retrieve Album element name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'Album', 'oxy-extended' );
	}

	/**
	 * Retrieve Album element slug.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element slug.
	 */
	public function slug() {
		return 'oe_album';
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
	 * Retrieve Album element icon.
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
		$this->album_images_controls();
		$this->trigger_controls();
		$this->cover_content_controls();
		$this->cover_content_styles();
		$this->lightbox_settings_controls();
	}

	/**
	 * Controls for Album Images
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function album_images_controls() {
		$album_images_section = $this->addControlSection( 'album_images_section', __( 'Album Images', 'oxy-extended' ), 'assets/icon.png', $this );

		$album_source = $album_images_section->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Album Source', 'oxy-extended' ),
				'slug'    => 'oe_album_source',
				'value'   => array(
					'media'   => __( 'Media Library', 'oxy-extended' ),
					'acf'     => __( 'ACF Gallery', 'oxy-extended' ),
					'acf_rep' => __( 'ACF Repeater', 'oxy-extended' ),
				),
				'default' => 'media',
				'css'     => false,
			)
		);

		$album_source->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );

		$wp_gallery = $album_images_section->addCustomControl(
			"<div class='oxygen-control'>
				<div class='oxygen-file-input'
				ng-class=\"{'oxygen-option-default':iframeScope.isInherited(iframeScope.component.active.id, 'oe_album_images')}\">
					<input type=\"text\" spellcheck=\"false\" ng-model=\"iframeScope.component.options[iframeScope.component.active.id]['model']['oe_album_images']\" ng-model-options=\"{ debounce: 10 }\"
						ng-change=\"iframeScope.setOption(iframeScope.component.active.id,'oe_album','oe_album_images');\">
					<div class=\"oxygen-file-input-browse\"
						data-mediaTitle=\"Select Images\" 
						data-mediaButton=\"Select Images\"
						data-mediaMultiple=\"true\"
						data-mediaProperty=\"oe_album_images\"
						data-mediaType=\"gallery\">" . __( 'browse', 'oxy-extended' ) . '
					</div>
				</div>
			</div>',
			'oe_album_images'
		);

		$wp_gallery->setParam( 'heading', __( 'Images', 'oxy-extended' ) );
		$wp_gallery->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_album_oe_album_source']=='media'" );
		$wp_gallery->rebuildElementOnChange();

		$source = $album_images_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Images Source', 'oxy-extended' ),
				'slug'      => 'acfg_source',
				'value'     => array(
					'same'      => __( 'Same Post/Page', 'oxy-extended' ),
					'import'    => __( 'Other', 'oxy-extended' ),
				),
				'default'   => 'same',
			)
		);

		$source->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_album_oe_album_source']!='media'" );

		$import = $album_images_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Enter Post/Page ID', 'oxy-extended' ),
				'slug'      => 'page_id',
			)
		);

		$import->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_album_oe_album_source']!='media'&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_album_acfg_source']=='import'" );
		$import->rebuildElementOnChange();

		$gf_name = $album_images_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Gallery Field Name', 'oxy-extended' ),
				'slug'      => 'field_name',
			)
		);

		$gf_name->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_album_oe_album_source']=='acf'" );
		$gf_name->rebuildElementOnChange();

		//* Repeater fields
		$acfrep_name = $album_images_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Repeater Field Name', 'oxy-extended' ),
				'slug'      => 'repfield_name',
			)
		);

		$acfrep_name->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_album_oe_album_source']=='acf_rep'" );
		$acfrep_name->rebuildElementOnChange();

		$repimg_name = $album_images_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Image Field Name', 'oxy-extended' ),
				'slug'      => 'repimg_name',
			)
		);

		$repimg_name->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_album_oe_album_source']=='acf_rep'" );
		$repimg_name->rebuildElementOnChange();

		$reptitle_name = $album_images_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Title Field Name', 'oxy-extended' ),
				'slug'      => 'reptitle_name',
			)
		);

		$reptitle_name->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_album_oe_album_source']=='acf_rep'" );
		$reptitle_name->rebuildElementOnChange();

		$repdesc_name = $album_images_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Description Field Name', 'oxy-extended' ),
				'slug'      => 'repdesc_name',
			)
		);

		$repdesc_name->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_album_oe_album_source']=='acf_rep'" );
		$repdesc_name->rebuildElementOnChange();

		$rep_btn_text = $album_images_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Button Text Field Name', 'oxy-extended' ),
				'slug'      => 'rep_btn_txt',
			)
		);

		$rep_btn_text->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_album_oe_album_source']=='acf_rep'" );
		$rep_btn_text->rebuildElementOnChange();

		$rep_btn_link = $album_images_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Button Link Field Name', 'oxy-extended' ),
				'slug'      => 'rep_btn_link',
			)
		);

		$rep_btn_link->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_album_oe_album_source']=='acf_rep'" );
		$rep_btn_link->rebuildElementOnChange();

		$image_size = $album_images_section->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Image Size', 'oxy-extended' ),
				'slug'    => 'oe_album_image_size',
				'value'   => OE_Helper::get_image_sizes(),
				'default' => 'medium',
				'css'     => false,
			)
		);

		$image_size->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$image_size->rebuildElementOnChange();
	}

	/**
	 * Controls for Trigger
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function trigger_controls() {
		/**
		 * Trigger Section
		 * -------------------------------------------------
		 */
		$trigger_section = $this->addControlSection( 'trigger_settings', __( 'Trigger', 'oxy-extended' ), 'assets/icon.png', $this );

		$trigger_type = $trigger_section->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Trigger', 'oxy-extended' ),
				'slug'    => 'oe_album_trigger_type',
				'default' => 'cover',
				'value'   => array(
					'cover'  => __( 'Album Cover', 'oxy-extended' ),
					'button' => __( 'Button', 'oxy-extended' ),
				),
			)
		);

		$trigger_type->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$trigger_type->rebuildElementOnChange();

		$album_cover_type = $trigger_section->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Cover Image', 'oxy-extended' ),
				'slug'    => 'oe_album_cover_type',
				'default' => 'first_img',
				'value'   => array(
					'custom_img' => __( 'Custom', 'oxy-extended' ),
					'first_img'  => __( 'First Image of Album', 'oxy-extended' ),
				),
			)
		);

		$album_cover_type->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_album_oe_album_trigger_type']=='cover'" );
		$album_cover_type->rebuildElementOnChange();

		$custom_image = $trigger_section->addCustomControl(
			"<div class='oxygen-control'>
				<div class='oxygen-file-input'
				ng-class=\"{'oxygen-option-default':iframeScope.isInherited(iframeScope.component.active.id, 'oe_cover_image')}\">
					<input type=\"text\" spellcheck=\"false\" ng-model=\"iframeScope.component.options[iframeScope.component.active.id]['model']['oe_cover_image']\" ng-model-options=\"{ debounce: 10 }\"
						ng-change=\"iframeScope.setOption(iframeScope.component.active.id,'oe_flip_box','oe_cover_image');\">
					<div class=\"oxygen-file-input-browse\"
						data-mediaTitle=\"Select Image\" 
						data-mediaButton=\"Select Image\"
						data-mediaMultiple=\"false\"
						data-mediaProperty=\"oe_cover_image\"
						data-mediaType=\"mediaUrl\">" . __( 'browse', 'oxy-extended' ) . '
					</div>
				</div>
			</div>',
			'oe_cover_image',
			$trigger_section
		);
		$custom_image->setParam( 'heading', __( 'Custom Image', 'oxy-extended' ) );
		$custom_image->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_album_oe_album_cover_type']=='custom_img'" );
		$custom_image->rebuildElementOnChange();

		$button_text = $trigger_section->addOptionControl(
			array(
				'type'  => 'textfield',
				'name'  => __( 'Button Text', 'oxy-extended' ),
				'slug'  => 'album_trigger_button_text',
				'value' => '',
				'css'   => false,
			)
		);
		$button_text->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_album_oe_album_trigger_type']=='button'" );

		$buttonicon = $trigger_section->addOptionControl(
			array(
				'type'    => 'icon_finder',
				'name'    => __( 'Button Icon', 'oxy-extended' ),
				'slug'    => 'album_trigger_button_icon',
				'css'     => false,
			)
		);
		$buttonicon->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_album_oe_album_trigger_type']=='button'" );
		$buttonicon->rebuildElementOnChange();

	}

	/**
	 * Controls for Cover Content
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function cover_content_controls() {
		/**
		 * Cover Content Section
		 * -------------------------------------------------
		 */
		$cover_content_section = $this->addControlSection( 'cover_content_settings', __( 'Cover Content', 'oxy-extended' ), 'assets/icon.png', $this );

		$cover_content = $cover_content_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Show Cover Content', 'oxy-extended' ),
				'slug'      => 'oe_cover_content',
				'default'   => 'no',
				'value'     => array(
					'yes'   => __( 'Yes', 'oxy-extended' ),
					'no'    => __( 'No', 'oxy-extended' ),
				),
			)
		);

		$cover_content->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$cover_content->rebuildElementOnChange();

		$cover_content_section->addOptionControl(
			array(
				'type'    => 'icon_finder',
				'name'    => __( 'Icon', 'oxy-extended' ),
				'slug'    => 'oe_album_icon',
				'css'     => false,
			)
		)->rebuildElementOnChange();

		$cover_content_title = $cover_content_section->addOptionControl(
			array(
				'type'  => 'textfield',
				'name'  => __( 'Title', 'oxy-extended' ),
				'slug'  => 'oe_album_title',
				'value' => '',
				'css'   => false,
			)
		);
		$cover_content_title->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );

		$cover_content_subtitle = $cover_content_section->addOptionControl(
			array(
				'type'  => 'textfield',
				'name'  => __( 'Subtitle', 'oxy-extended' ),
				'slug'  => 'oe_album_subtitle',
				'value' => '',
				'css'   => false,
			)
		);
		$cover_content_subtitle->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );

		$cover_content_button = $cover_content_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Show Button', 'oxy-extended' ),
				'slug'      => 'oe_album_cover_button',
				'default'   => 'no',
				'value'     => array(
					'yes'   => __( 'Yes', 'oxy-extended' ),
					'no'    => __( 'No', 'oxy-extended' ),
				),
			)
		);

		$cover_content_button->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$cover_content_button->rebuildElementOnChange();

		$cover_button_text = $cover_content_section->addOptionControl(
			array(
				'type'  => 'textfield',
				'name'  => __( 'Button Text', 'oxy-extended' ),
				'slug'  => 'album_cover_button_text',
				'value' => '',
				'css'   => false,
			)
		);
		$cover_button_text->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_album_oe_album_cover_button']=='yes'" );

		$cover_button_icon = $cover_content_section->addOptionControl(
			array(
				'type'    => 'icon_finder',
				'name'    => __( 'Button Icon', 'oxy-extended' ),
				'slug'    => 'album_cover_button_icon',
				'css'     => false,
			)
		);
		$cover_button_icon->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_album_oe_album_cover_button']=='yes'" );
		$cover_button_icon->rebuildElementOnChange();

		$cover_button_icon_position = $cover_content_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Button Position', 'oxy-extended' ),
				'slug'      => 'album_cover_button_icon_position',
				'default'   => 'after',
				'value'     => array(
					'before'   => __( 'Before', 'oxy-extended' ),
					'after'    => __( 'After', 'oxy-extended' ),
				),
			)
		);

		$cover_button_icon_position->setParam( 'ng_show', "iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_album_oe_album_cover_button']=='yes'" );
		$cover_button_icon_position->rebuildElementOnChange();

	}

	/**
	 * Styles for Cover Content
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function cover_content_styles() {
		/**
		 * Cover Content Style Section
		 * -------------------------------------------------
		 */
		$cover_content_style = $this->addControlSection( 'cover_content_style', __( 'Cover Content Style', 'oxy-extended' ), 'assets/icon.png', $this );

		$cover_content_wrapper = $cover_content_style->addControlSection( 'more_link_size', __( 'Width / Spacing', 'oxy-extended' ), 'assets/icon', $this );

		$link_selector = '.oe-album-content-wrap';

		$cover_content_wrapper->addPreset(
			'padding',
			'cover_padding',
			__( 'Padding', 'oxy-extended' ),
			$link_selector
		)->whiteList();

		$cover_content_wrapper->addPreset(
			'margin',
			'cover_margin',
			__( 'Margin', 'oxy-extended' ),
			$link_selector
		)->whiteList();

		$cover_content_wrapper->addStyleControls([
			array(
				'property'  => 'background-color',
				'selector'  => $link_selector,
			),
		]);

		$cover_content_style->typographySection( __( 'Title Typography', 'oxy-extended' ), '.oe-album-title', $this );
		$cover_content_style->typographySection( __( 'Subtitle Typography', 'oxy-extended' ), '.oe-album-subtitle', $this );
		$cover_content_style->typographySection( __( 'Button Typography', 'oxy-extended' ), '.oe-album-cover-button', $this );

		$cover_button = $cover_content_style->addControlSection( 'cover_button_style', __( 'Button Style', 'oxy-extended' ), 'assets/icon', $this );
		$cover_button->addCustomControl(
			__( '<div class="oxygen-option-default" style="color: #c3c5c7; line-height: 1.3; font-size: 14px;">Note: Initial text color will set from Typography section.</div>', 'oxy-extended' ),
			'txtcolor_desc'
		);

		$button_selector = '.oe-album-cover-button';

		$cover_button->addStyleControls([
			array(
				'name'      => 'Hover Color',
				'property'  => 'color',
				'selector'  => $button_selector . ':hover',
			),
			array(
				'property'  => 'background-color',
				'selector'  => $button_selector,
			),
			array(
				'name'      => 'Hover Background Color',
				'property'  => 'background-color',
				'selector'  => $button_selector . ':hover',
			),
		]);

		$cover_button->addPreset(
			'padding',
			'button_padding',
			__( 'Padding', 'oxy-extended' ),
			$button_selector
		)->whiteList();

		$cover_content_style->borderSection( __( 'Button Border', 'oxy-extended' ), $button_selector, $this );
		//$cover_content_style->borderSection( __('Button Hover Border', 'oxy-extended'), $button_selector . ':hover', $this );
		$cover_content_style->boxShadowSection( __( 'Button Box Shadow', 'oxy-extended' ), $button_selector, $this );
		$cover_content_style->boxShadowSection( __( 'Button Hover Box Shadow', 'oxy-extended' ), $button_selector . ':hover', $this );
	}

	/**
	 * Controls for Lightbox Settings
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function lightbox_settings_controls() {
		/**
		 * Lightbox Settings Section
		 * -------------------------------------------------
		 */
		$lightbox_settings_section = $this->addControlSection( 'lightbox_settings', __( 'Lightbox Settings', 'oxy-extended' ), 'assets/icon.png', $this );

		$lightbox_caption = $lightbox_settings_section->addOptionControl(
			array(
				'type'  => 'radio',
				'name'  => __( 'Lightbox Caption', 'oxy-extended' ),
				'slug'  => 'oe_lightbox_caption',
				'value' => array(
					''        => __( 'None', 'oxy-extended' ),
					'caption' => __( 'Caption', 'oxy-extended' ),
					'title'   => __( 'Title', 'oxy-extended' ),
				),
			)
		);

		$lightbox_caption->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$lightbox_caption->rebuildElementOnChange();

		$lightbox_loop = $lightbox_settings_section->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Loop', 'oxy-extended' ),
				'slug'    => 'loop',
				'default' => 'yes',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
			)
		);

		$lightbox_loop->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$lightbox_loop->rebuildElementOnChange();

		$lightbox_arrows = $lightbox_settings_section->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Arrows', 'oxy-extended' ),
				'slug'    => 'arrows',
				'default' => 'yes',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
			)
		);

		$lightbox_arrows->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$lightbox_arrows->rebuildElementOnChange();

		$lightbox_slide_counter = $lightbox_settings_section->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Slides Counter', 'oxy-extended' ),
				'slug'    => 'slides_counter',
				'default' => 'yes',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
			)
		);

		$lightbox_slide_counter->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$lightbox_slide_counter->rebuildElementOnChange();

		$lightbox_keyboard = $lightbox_settings_section->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Keyboard Navigation', 'oxy-extended' ),
				'slug'    => 'keyboard',
				'default' => 'yes',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
			)
		);

		$lightbox_keyboard->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$lightbox_keyboard->rebuildElementOnChange();

		$lightbox_toolbar = $lightbox_settings_section->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Toolbar', 'oxy-extended' ),
				'slug'    => 'toolbar',
				'default' => 'yes',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
			)
		);

		$lightbox_toolbar->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$lightbox_toolbar->rebuildElementOnChange();

		/*$lightbox_toolbar_buttons = $lightbox_settings_section->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Toolbar Buttons', 'oxy-extended' ),
				'slug'    => 'toolbar_buttons',
				'default' => array('zoom','share','close','thumbs'),
				'value'   => array(
					'zoom'         => __( 'Zoom', 'oxy-extended' ),
					'share'        => __( 'Share', 'oxy-extended' ),
					'slideShow'    => __( 'Slide Show', 'oxy-extended' ),
					'fullScreen'   => __( 'Full Screen', 'oxy-extended' ),
					'download'     => __( 'Download', 'oxy-extended' ),
					'thumbs'       => __( 'Thumbs', 'oxy-extended' ),
					'close'        => __( 'Close', 'oxy-extended' ),
				),
				'multiselect'  => true,
			)
		);

		$lightbox_toolbar_buttons->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$lightbox_toolbar_buttons->rebuildElementOnChange();*/

		$lightbox_thumbs = $lightbox_settings_section->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Thumbs Auto Start', 'oxy-extended' ),
				'slug'    => 'thumbs_auto_start',
				'default' => 'no',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
			)
		);

		$lightbox_thumbs->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$lightbox_thumbs->rebuildElementOnChange();

		$lightbox_animation = $lightbox_settings_section->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Lightbox Animation', 'oxy-extended' ),
				'slug'    => 'lightbox_animation',
				'default' => 'zoom',
				'value'   => array(
					''            => __( 'None', 'oxy-extended' ),
					'fade'        => __( 'Fade', 'oxy-extended' ),
					'zoom'        => __( 'Zoom', 'oxy-extended' ),
					'zoom-in-out' => __( 'Zoom in Out', 'oxy-extended' ),
				),
			)
		);

		$lightbox_animation->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$lightbox_animation->rebuildElementOnChange();

		$lightbox_transition_effect = $lightbox_settings_section->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Transition Effect', 'oxy-extended' ),
				'slug'    => 'transition_effect',
				'default' => 'fade',
				'value'   => array(
					''            => __( 'None', 'oxy-extended' ),
					'fade'        => __( 'Fade', 'oxy-extended' ),
					'slide'       => __( 'Slide', 'oxy-extended' ),
					'circular'    => __( 'Circular', 'oxy-extended' ),
					'tube'        => __( 'Tube', 'oxy-extended' ),
					'zoom-in-out' => __( 'Zoom in Out', 'oxy-extended' ),
					'rotate'      => __( 'Rotate', 'oxy-extended' ),
				),
			)
		);

		$lightbox_animation->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$lightbox_animation->rebuildElementOnChange();

	}

	protected function get_album_images( $options, $uid, $images ) {
		if ( is_array( $images ) ) {
			$is_first  = true;
			foreach ( $images as $index => $item ) {

				if ( $is_first ) {
					$is_first = false;
					continue;
				}

				$thumbs_url = wp_get_attachment_image_src( $item, 'full' );

				$thumbs_html = '';

				//if ( in_array( 'thumbs', $options['toolbar_buttons'] ) || $options['thumbs_auto_start'] == 'yes' ) {
					$thumbs_html = '<img src="' . $thumbs_url[0] . '">';
				//}
				$data = $this->get_attachment_data( $item );
				$title = '';
				if ( 'title' === $options['oe_lightbox_caption'] && ! empty( $data->title ) ) {
					$caption = $data->title;
				} elseif ( 'caption' === $options['oe_lightbox_caption'] && ! empty( $data->caption ) ) {
					$caption = $data->caption;
				} else {
					$caption = '';
				}

				if ( $caption && $options['oe_lightbox_caption'] ) {
					$title = ' data-caption="' . $caption . '"';
				}

				echo '<a class="oe-album-image" data-fancybox="' . $uid . '" data-src="' . $thumbs_url[0] . '"' . $title . '>' . $thumbs_html . '</a>';
			}
		}
	}

	public function get_attachment_data( $id ) {
		$data = wp_prepare_attachment_for_js( $id );

		if ( gettype( $data ) == 'array' ) {
			return json_decode( json_encode( $data ) );
		}

		return $data;
	}

	protected function render_album_cover( $options, $uid, $images ) {
		$link_key = 'album-cover-link';
		$thumb_size = $options['oe_album_image_size'];
		$album = $images;

		if ( ! empty( $album ) ) {
			$album_first_item = $album[0];
			$thumb = wp_get_attachment_image_src( $album_first_item, 'full', $options );

			//$album_image_url  = Group_Control_Image_Size::get_attachment_image_src( $album_first_item['id'], 'image', $options );

			//$this->get_lightbox_atts( $link_key, $album_first_item, $album_image_url );
			/*            if( $options['oe_lightbox_caption'] == 'caption' ) {
				$caption = wp_get
			}*/
			$data = $this->get_attachment_data( $album_first_item );
			$title = '';
			if ( 'title' === $options['oe_lightbox_caption'] && ! empty( $data->title ) ) {
				$caption = $data->title;
			} elseif ( 'caption' === $options['oe_lightbox_caption'] && ! empty( $data->caption ) ) {
				$caption = $data->caption;
			} else {
				$caption = '';
			}

			if ( $caption && $options['oe_lightbox_caption'] ) {
				$title = ' data-caption="' . $caption . '"';
			}

			?>
			<a data-fancybox="<?php echo $uid; ?>" data-src="<?php echo $thumb[0]; ?>"<?php echo $title; ?>>
				<div class="oe-album-cover oe-media-content oe-ins-filter-target">
					<?php
					if ( 'custom_img' === $options['oe_album_cover_type'] ) {
						$cover_image = $options['oe_cover_image'];
						$image_html = '<img src="' . $cover_image . '">';

					} else {
						$cover_image_id  = $album_first_item;
						$cover_image_url = wp_get_attachment_image_src( $cover_image_id, 'album_cover', $options );

						$image_html = '<img src="' . $cover_image_url[0] . '"/>';
					}

						$image_html .= $this->render_image_overlay();

					if ( 'yes' === $options['oe_cover_content'] ) {
						$image_html .= $this->oe_get_album_content( $options );
					}

						echo $image_html;
					?>
				</div>
			</a>
			<?php
		}
	}

	protected function render_trigger_button( $options, $uid, $images ) {
		$link_key = 'album-cover-link';

		$thumb_size = $options['oe_album_image_size'];
		$album = $images;

		if ( ! empty( $album ) ) {
			$album_first_item = $album[0];
			$thumb = wp_get_attachment_image_src( $album_first_item, 'full', $options );

			$data = $this->get_attachment_data( $album_first_item );
			$title = '';
			if ( 'title' === $options['oe_lightbox_caption'] && ! empty( $data->title ) ) {
				$caption = $data->title;
			} elseif ( 'caption' === $options['oe_lightbox_caption'] && ! empty( $data->caption ) ) {
				$caption = $data->caption;
			} else {
				$caption = '';
			}

			if ( $caption && $options['oe_lightbox_caption'] ) {
				$title = ' data-caption="' . $caption . '"';
			}
		}

		?>
		<div class="oe-album-trigger-button-wrap">
			<a class="oe-album-trigger-button" data-fancybox="<?php echo $uid; ?>" data-src="<?php echo $thumb[0]; ?>"<?php echo $title; ?>>
				<span class="oe-album-button-content">
					<?php if ( ! empty( $options['album_trigger_button_text'] ) ) { ?>
						<span>
							<?php echo esc_attr( $options['album_trigger_button_text'] ); ?>
						</span>
					<?php } ?>
					<?php if ( ! empty( $options['album_trigger_button_icon'] ) ) { ?>
						<span class="oe-button-icon oe-icon">
							<?php
								global $oxygen_svg_icons_to_load;

								$oxygen_svg_icons_to_load[] = $options['album_trigger_button_icon'];

								echo '<svg id="svg' . esc_attr( $options['selector'] ) . '-icon" class="oe-flipbox-icon-svg"><use xlink:href="#' . $options['album_trigger_button_icon'] . '"></use></svg>';
							?>
						</span>
					<?php } ?>
				</span>
			</a>
		</div>
		<?php
	}

	protected function get_album_cover_button( $options ) { ?>
		<div class="oe-album-cover-button-wrap">
			<div class="oe-album-cover-button">
				<?php if ( ! empty( $options['album_cover_button_icon'] ) ) { ?>
					<?php if ( 'before' === $options['album_cover_button_icon_position'] ) { ?>
					<span class="oe-button-icon oe-icon-left">
						<?php
							global $oxygen_svg_icons_to_load;

							$oxygen_svg_icons_to_load[] = $options['album_cover_button_icon'];

							echo '<svg id="svg' . esc_attr( $options['selector'] ) . '-icon" class="oe-flipbox-icon-svg"><use xlink:href="#' . $options['album_cover_button_icon'] . '"></use></svg>';
						?>
					</span>
				<?php } ?>
				<?php } ?>
				<?php if ( ! empty( $options['album_cover_button_text'] ) ) { ?>
					<span>
						<?php echo esc_attr( $options['album_cover_button_text'] ); ?>
					</span>
				<?php } ?>
				<?php if ( ! empty( $options['album_cover_button_icon'] ) ) { ?>
					<?php if ( 'after' === $options['album_cover_button_icon_position'] ) { ?>
					<span class="oe-button-icon oe-icon-right">
						<?php
							global $oxygen_svg_icons_to_load;

							$oxygen_svg_icons_to_load[] = $options['album_cover_button_icon'];

							echo '<svg id="svg' . esc_attr( $options['selector'] ) . '-icon" class="oe-flipbox-icon-svg"><use xlink:href="#' . $options['album_cover_button_icon'] . '"></use></svg>';
						?>
					</span>
				<?php } ?>
				<?php } ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Image Overlay
	 */
	protected function render_image_overlay() {
		return '<div class="oe-album-cover-overlay oe-media-overlay"></div>';
	}

	protected function oe_get_album_content( $options ) {
		ob_start();
		$has_icon = $options['oe_album_icon'];

		if ( $has_icon || $options['oe_album_title'] || 'yes' === $options['oe_album_subtitle'] ) {
			?>
			<div class="oe-album-content-wrap oe-media-content">
				<div class="oe-album-content">
					<div class="oe-album-content-inner">
						<?php if ( $has_icon ) { ?>
							<div class="oe-icon oe-album-icon">
								<?php
									global $oxygen_svg_icons_to_load;

									$oxygen_svg_icons_to_load[] = $options['oe_album_icon'];

									echo '<svg id="svg' . esc_attr( $options['selector'] ) . '-icon" class="oe-flipbox-icon-svg"><use xlink:href="#' . $options['oe_album_icon'] . '"></use></svg>';
								?>
							</div>
						<?php } ?>
						<?php
						if ( $options['oe_album_title'] ) {
							echo '<div class="oe-album-title">' . $options['oe_album_title'] . '</div>';
						}

						if ( $options['oe_album_subtitle'] ) {
							echo '<div class="oe-album-subtitle">' . $options['oe_album_subtitle'] . '</div>';
						}
						?>
					</div>
					<?php
					if ( 'yes' === $options['oe_album_cover_button'] ) {
						echo $this->get_album_cover_button( $options );
					}
					?>
				</div>
			</div>
			<?php
		}

		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	/**
	 * Render Album element output on the frontend.
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
		$uid = str_replace( '.', '', uniqid( 'oe_album', true ) );
		$cover_class = '';
		if ( ! empty( $options['oe_album_source'] ) && 'media' === $options['oe_album_source'] && isset( $options['oe_album_images'] ) ) {

			$images = explode( ',', $options['oe_album_images'] );

		} elseif ( ! empty( $options['oe_album_source'] ) && 'acf' === $options['oe_album_source'] ) {

			if ( empty( $options['field_name'] ) ) {
				echo __( 'Enter Gallery Field Key.', 'oxy-extended' );
			} else {
				if ( ! empty( $options['acfg_source'] ) && 'import' === $options['acfg_source'] ) {
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
		} elseif ( ! empty( $options['oe_album_source'] ) && 'acf_rep' === $options['oe_album_source'] && function_exists( 'have_rows' ) ) {

			if ( ! empty( $options['acfg_source'] ) && 'import' === $options['acfg_source'] && isset( $options['page_id'] ) ) {
				$post_id = (int) $options['page_id'];
			} else {
				$post_id = get_the_ID();
			}

			if ( isset( $options['repfield_name'] ) && have_rows( $options['repfield_name'], $post_id ) ) :
				$images = array();
				$i = 0;

				while ( have_rows( $options['repfield_name'], $post_id ) ) :
					the_row();
					if ( isset( $options['repimg_name'] ) ) {
						$slug = $options['repfield_name'] . '_' . $i . '_' . $options['repimg_name'];
						$images[ $i ][] = get_post_meta( $post_id, $slug, true );

					}

					if ( isset( $options['reptitle_name'] ) ) {
						$slug = $options['repfield_name'] . '_' . $i . '_' . $options['reptitle_name'];
						$images[ $i ]['title'] = get_post_meta( $post_id, $slug, true );
					}

					if ( isset( $options['repdesc_name'] ) ) {
						$slug = $options['repfield_name'] . '_' . $i . '_' . $options['repdesc_name'];
						$images[ $i ]['desc'] = get_post_meta( $post_id, $slug, true );
					}

					if ( isset( $options['rep_btn_txt'] ) ) {
						$slug = $options['repfield_name'] . '_' . $i . '_' . $options['rep_btn_txt'];
						$images[ $i ]['btnTxt'] = get_post_meta( $post_id, $slug, true );
					}

					if ( isset( $options['rep_btn_link'] ) ) {
						$slug = $options['repfield_name'] . '_' . $i . '_' . $options['rep_btn_link'];
						$images[ $i ]['btnLink'] = get_post_meta( $post_id, $slug, true );
					}

					$i++;
				endwhile;
				function array_flatten( $images ) {
					if ( ! is_array( $images ) ) {
						return false;
					}
					$result = array();
					foreach ( $images as $key => $value ) {
						if ( is_array( $value ) ) {
							$result = array_merge( $result, array_flatten( $value ) );
						} else {
							$result[ $key ] = $value;
						}
					}
					return $result;
				}
				$images = array_flatten( $images );
			endif;
		}

		if ( $options['oe_album_trigger_type'] == 'cover' ) {
			$cover_class = 'oe-album-cover-wrap oe-ins-filter-hover';
		}

		?>
		<div class="oe-album-container">
			<?php if ( ! empty( $images ) ) { ?>
				<div class="oe-album <?php echo $cover_class; ?>" data-id="<?php echo $uid; ?>" data-fancybox-class="oe-fancybox-thumbs-y" data-fancybox-axis="y">
					<?php
					if ( $options['oe_album_trigger_type'] == 'cover' ) {
						// Album Cover
						$this->render_album_cover( $options, $uid, $images );
					} else {
						$this->render_trigger_button( $options, $uid, $images );
					}
					?>
					<div class="oe-album-gallery">
						<?php
							$this->get_album_images( $options, $uid, $images );
						?>
					</div>
				</div>
				<?php
			} else {
				$placeholder = __( 'Choose some images for album in widget settings.', 'oxy-extended' );

				echo 'Album is Empty';
			}
			?>
		</div>
		<?php
		ob_start();

		$this->get_album_script( $options, $uid );
		$js = ob_get_clean();

		if ( isset( $_GET['oxygen_iframe'] ) || defined( 'OXY_ELEMENTS_API_AJAX' ) ) {
			$this->oe_album_enqueue_scripts();
			$this->El->builderInlineJS( $js );
		} else {

			add_action( 'wp_footer', array( $this, 'oe_album_enqueue_scripts' ) );

			$this->album_js_code[] = $js;
			$this->El->footerJS( join( '', $this->album_js_code ) );
		}
	}

	public function get_album_script( $options, $uid ) {
		?>
		jQuery(document).ready(function($) {
			var $album              = $('.oe-album'),
				$id                 = $album.data('id'),
				$fancybox_thumbs    = $album.data('fancybox-class'),
				$fancybox_axis		= $album.data('fancybox-axis'),
				$lightbox_selector  = '[data-fancybox="'+$id+'"]';

			$($lightbox_selector).fancybox({
				loop:               'yes' === '<?php echo $options['loop']; ?>',
				arrows:             'yes' === '<?php echo $options['arrows']; ?>',
				infobar:            'yes' === '<?php echo $options['slides_counter']; ?>',
				keyboard:           'yes' === '<?php echo $options['keyboard']; ?>',
				toolbar:            'yes' === '<?php echo $options['toolbar']; ?>',
				animationEffect:    '<?php echo $options['lightbox_animation']; ?>',
				transitionEffect:   '<?php echo $options['transition_effect']; ?>',
				baseClass:			$fancybox_thumbs,
				thumbs: {
					autoStart:	'yes' === '<?php echo $options['thumbs_auto_start']; ?>',
					axis:		$fancybox_axis
				}
			});
		});
		<?php
	}

	public function init() {
		$this->El->useAJAXControls();
		if ( isset( $_GET['oxygen_iframe'] ) ) {
			add_action( 'wp_footer', array( $this, 'oe_album_enqueue_scripts' ) );
		}
	}

	public function oe_album_enqueue_scripts() {
		wp_enqueue_style( 'oe-fancybox', OXY_EXTENDED_URL . 'assets/lib/fancybox/jquery.fancybox.css', array(), filemtime( OXY_EXTENDED_DIR . 'assets/lib/fancybox/jquery.fancybox.css' ), 'all' );
		wp_enqueue_script( 'oe-fancybox', OXY_EXTENDED_URL . 'assets/lib/fancybox/jquery.fancybox.min.js', array(), filemtime( OXY_EXTENDED_DIR . 'assets/lib/fancybox/jquery.fancybox.min.js' ), true );
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

new OEAlbum();
