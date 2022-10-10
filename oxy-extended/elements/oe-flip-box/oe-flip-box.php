<?php
namespace Oxygen\OxyExtended;

/**
 * Flip Box Element
 */
class OEFlipBox extends \OxyExtendedEl {

	public $css_added = false;

	/**
	 * Retrieve flip box element name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'Flip Box', 'oxy-extended' );
	}

	/**
	 * Retrieve flip box element slug.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element slug.
	 */
	public function slug() {
		return 'oe_flip_box';
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
	 * Retrieve flip box element icon.
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
		$this->front_controls();
		$this->back_controls();
		$this->settings_controls();
	}

	/**
	 * Controls for front section
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function front_controls() {
		/**
		 * Front Section
		 * -------------------------------------------------
		 */
		$front_section = $this->addControlSection( 'front', __( 'Front', 'oxy-extended' ), 'assets/icon.png', $this );

		$front_title = $front_section->addOptionControl(
			array(
				'type'  => 'textfield',
				'name'  => __( 'Title', 'oxy-extended' ),
				'slug'  => 'oe_title_front',
				'value' => __( 'This is the heading', 'oxy-extended' ),
			)
		);
		$front_title->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );

		$front_description = $front_section->addOptionControl(
			array(
				'type'  => 'textarea',
				'name'  => __( 'Description', 'oxy-extended' ),
				'slug'  => 'oe_description_front',
				'value' => __( 'This is the front content. Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'oxy-extended' ),
			)
		);
		$front_description->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );

		$front_title_tag = $front_section->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Title HTML Tag', 'oxy-extended' ),
				'slug'    => 'oe_title_html_tag_front',
				'value'   => array(
					'h1'   => __( 'H1', 'oxy-extended' ),
					'h2'   => __( 'H2', 'oxy-extended' ),
					'h3'   => __( 'H3', 'oxy-extended' ),
					'h4'   => __( 'H4', 'oxy-extended' ),
					'h5'   => __( 'H5', 'oxy-extended' ),
					'h6'   => __( 'H6', 'oxy-extended' ),
					'div'  => __( 'div', 'oxy-extended' ),
					'span' => __( 'span', 'oxy-extended' ),
					'p'    => __( 'p', 'oxy-extended' ),
				),
				'default' => 'h3',
				'css'     => false,
			)
		);
		$front_title_tag->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$front_title_tag->rebuildElementOnChange();

		$front_icon_section = $front_section->addControlSection( 'front_icon_section', __( 'Icon', 'oxy-extended' ), 'assets/icon.png', $this );

		$front_icon_section->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Icon Type', 'oxy-extended' ),
				'slug'    => 'oe_front_icon_type',
				'value'   => array(
					'none'  => esc_html__( 'None', 'oxy-extended' ),
					'icon'  => esc_html__( 'Icon', 'oxy-extended' ),
					'image' => esc_html__( 'Image', 'oxy-extended' ),
				),
				'default' => 'none',
				'css'     => false,
			)
		)->rebuildElementOnChange();

		$front_icon_section->addOptionControl(
			array(
				'type'    => 'icon_finder',
				'name'    => __( 'Icon', 'oxy-extended' ),
				'slug'    => 'oe_front_icon',
				'value'   => 'FontAwesomeicon-check',
				'default' => 'FontAwesomeicon-check',
				'css'     => false,
				'condition' => 'oe_front_icon_type=icon',
			)
		)->rebuildElementOnChange();

		$image = $front_icon_section->addCustomControl(
			"<div class='oxygen-control'>
				<div class='oxygen-file-input'
				ng-class=\"{'oxygen-option-default':iframeScope.isInherited(iframeScope.component.active.id, 'oe_front_image')}\">
					<input type=\"text\" spellcheck=\"false\" ng-model=\"iframeScope.component.options[iframeScope.component.active.id]['model']['oe_front_image']\" ng-model-options=\"{ debounce: 10 }\"
						ng-change=\"iframeScope.setOption(iframeScope.component.active.id,'oe_flip_box','oe_front_image');\">
					<div class=\"oxygen-file-input-browse\"
						data-mediaTitle=\"Select Image\" 
						data-mediaButton=\"Select Image\"
						data-mediaMultiple=\"false\"
						data-mediaProperty=\"oe_front_image\"
						data-mediaType=\"mediaUrl\">" . __( 'browse', 'oxy-extended' ) . "
					</div>
				</div>
			</div>",
			'oe_front_image',
			$front_icon_section
		);
		$image->setParam( 'heading', __( 'Image', 'oxy-extended' ) );
		$image->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_flip_box_oe_front_icon_type']=='image'" );
		$image->rebuildElementOnChange();

		$selector = '.oe-flipbox-front .oe-flipbox-overlay';

		$front_layout_section = $front_section->addControlSection( 'front_layout', __( 'Layout & Styling', 'oxy-extended' ), 'assets/icon.png', $this );

		$front_title_color = $front_layout_section->addStyleControl(
			array(
				'name'     => __( 'Background Color', 'oxy-extended' ),
				'selector' => $selector,
				'value'    => '',
				'property' => 'background-color',
			)
		);
		$front_title_color->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );

		$front_alignment = $front_layout_section->addControl( 'buttons-list', 'oe_front_alignment', __( 'Text Alignment', 'oxy-extended' ) );
		$front_alignment->setValue( array(
			'left'   => __( 'Left', 'oxy-extended' ),
			'center' => __( 'Center', 'oxy-extended' ),
			'right'  => __( 'Right', 'oxy-extended' ),
		) );
		$front_alignment->setValueCSS( array(
			'left'  => '.oe-flipbox-front .oe-flipbox-overlay { text-align: left; }',
			'right' => '.oe-flipbox-front .oe-flipbox-overlay { text-align: right; }',
		));
		$front_alignment->setDefaultValue( 'center' );
		$front_alignment->whiteList();

		$vertical_position_front = $front_layout_section->addControl( 'buttons-list', 'oe_vertical_position_front', __( 'Vertical Position', 'oxy-extended' ) );
		$vertical_position_front->setValue( array(
			'top'    => __( 'Top', 'oxy-extended' ),
			'middle' => __( 'Middle', 'oxy-extended' ),
			'bottom' => __( 'Bottom', 'oxy-extended' ),
		) );
		$vertical_position_front->setValueCSS( array(
			'top' => '.oe-flipbox-front .oe-flipbox-overlay { justify-content: flex-start; }',
			'middle' => '.oe-flipbox-front .oe-flipbox-overlay { justify-content: center; }',
			'bottom' => '.oe-flipbox-front .oe-flipbox-overlay { justify-content: flex-end; }',
		));
		$vertical_position_front->setDefaultValue( 'middle' );
		$vertical_position_front->whiteList();

		/* $vertical_position_front = $front_layout_section->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Vertical Position', 'oxy-extended' ),
				'slug'    => 'oe_vertical_position_front',
				'value'   => array(
					'top'    => esc_html__( 'Top', 'oxy-extended' ),
					'middle' => esc_html__( 'Middle', 'oxy-extended' ),
					'bottom' => esc_html__( 'Bottom', 'oxy-extended' ),
				),
				'default' => 'middle',
				'css'     => false,
			)
		);
		$vertical_position_front->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$vertical_position_front->rebuildElementOnChange(); */

		$front_layout_section->addPreset(
			'padding',
			'oe_padding_front',
			__( 'Padding', 'oxy-extended' ),
			$selector
		)->whiteList();

		$front_section->borderSection( __( 'Border', 'oxy-extended' ), $selector, $this );
		$front_section->typographySection( __( 'Title Typography', 'oxy-extended' ), '.oe-flipbox-heading', $this );
		$front_section->typographySection( __( 'Description Typography', 'oxy-extended' ), '.oe-flipbox-content', $this );
	}

	/**
	 * Controls for back section
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function back_controls() {
		/**
		 * Back Section
		 * -------------------------------------------------
		 */
		$back_section = $this->addControlSection( 'back', __( 'Back', 'oxy-extended' ), 'assets/icon.png', $this );
		$back_title   = $back_section->addOptionControl(
			array(
				'type'  => 'textfield',
				'name'  => __( 'Title', 'oxy-extended' ),
				'slug'  => 'oe_title_back',
				'value' => __( 'This is the heading', 'oxy-extended' ),
				'css'   => false,
			)
		);
		$back_title->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$back_title->rebuildElementOnChange();

		$back_title_tag = $back_section->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Title HTML Tag', 'oxy-extended' ),
				'slug'    => 'oe_title_html_tag_back',
				'value'   => array(
					'h1'   => __( 'H1', 'oxy-extended' ),
					'h2'   => __( 'H2', 'oxy-extended' ),
					'h3'   => __( 'H3', 'oxy-extended' ),
					'h4'   => __( 'H4', 'oxy-extended' ),
					'h5'   => __( 'H5', 'oxy-extended' ),
					'h6'   => __( 'H6', 'oxy-extended' ),
					'div'  => __( 'div', 'oxy-extended' ),
					'span' => __( 'span', 'oxy-extended' ),
					'p'    => __( 'p', 'oxy-extended' ),
				),
				'default' => 'h3',
				'css'     => false,
			)
		);
		$back_title_tag->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$back_title_tag->rebuildElementOnChange();

		$back_description = $back_section->addOptionControl(
			array(
				'type'  => 'textarea',
				'name'  => __( 'Description', 'oxy-extended' ),
				'slug'  => 'oe_description_back',
				'value' => __( 'This is the back content. Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'oxy-extended' ),
			)
		);
		$back_description->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );

		/* $back_link = $back_section->addCustomControl(
			'<div class="oxygen-file-input oxygen-option-default" ng-class="{\'oxygen-option-default\':iframeScope.isInherited(iframeScope.component.active.id, \'oxy-oe_flip_box_back_link\')}">
				<input type="text" spellcheck="false" ng-model="iframeScope.component.options[iframeScope.component.active.id][\'model\'][\'oxy-oe_flip_box_back_link\']" ng-model-options="{ debounce: 10 }" ng-change="iframeScope.setOption(iframeScope.component.active.id, iframeScope.component.active.name,\'oxy-oe_flip_box_back_link\');iframeScope.checkResizeBoxOptions(\'oxy-oe_flip_box_back_link\'); " class="ng-pristine ng-valid ng-touched" placeholder="https://">
				<div class="oxygen-dynamic-data-browse" ctdynamicdata data="iframeScope.dynamicShortcodesLinkMode" callback="iframeScope.ouDynamicUVUrl">data</div>
			</div>
			',
			'oe_flip_box_back_link'
		); */
		$back_link = $back_section->addCustomControl(
			'<div class="oxygen-file-input oxygen-option-default" ng-class="{\'oxygen-option-default\':iframeScope.isInherited(iframeScope.component.active.id, \'oxy-oe_flip_box_back_link\')}">
				<input type="text" spellcheck="false" ng-model="iframeScope.component.options[iframeScope.component.active.id][\'model\'][\'oxy-oe_flip_box_back_link\']" ng-model-options="{ debounce: 10 }" ng-change="iframeScope.setOption(iframeScope.component.active.id, iframeScope.component.active.name,\'oxy-oe_flip_box_back_link\');iframeScope.checkResizeBoxOptions(\'oxy-oe_flip_box_back_link\'); " class="ng-pristine ng-valid ng-touched" placeholder="http://">
				<div class="oxygen-set-link" data-linkproperty="url" data-linktarget="target" onclick="processOELink(\'oxy-oe_flip_box_back_link\')">set</div>
				<div class="oxygen-dynamic-data-browse" ctdynamicdata data="iframeScope.dynamicShortcodesLinkMode" callback="iframeScope.oeDynamicFBUrl">data</div>
			</div>
			',
			'oe_flip_box_back_link',
			$back_section
		);
		$back_link->setParam( 'heading', __( 'Link', 'oxy-extended' ) );
		$back_link->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$back_link->rebuildElementOnChange();

		$back_icon_section = $back_section->addControlSection( 'back_icon_section', __( 'Icon', 'oxy-extended' ), 'assets/icon.png', $this );

		$back_icon_section->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Icon Type', 'oxy-extended' ),
				'slug'    => 'oe_back_icon_type',
				'value'   => array(
					'none'  => esc_html__( 'None', 'oxy-extended' ),
					'icon'  => esc_html__( 'Icon', 'oxy-extended' ),
					'image' => esc_html__( 'Image', 'oxy-extended' ),
				),
				'default' => 'none',
			)
		)->rebuildElementOnChange();

		$back_icon_section->addOptionControl(
			array(
				'type'      => 'icon_finder',
				'name'      => __( 'Icon', 'oxy-extended' ),
				'slug'      => 'oe_back_icon',
				'value'     => 'FontAwesomeicon-check',
				'default'   => 'FontAwesomeicon-check',
				'condition' => 'oe_back_icon_type=icon',
			)
		)->rebuildElementOnChange();

		$image = $back_icon_section->addCustomControl(
			"<div class='oxygen-control'>
				<div class='oxygen-file-input'
				ng-class=\"{'oxygen-option-default':iframeScope.isInherited(iframeScope.component.active.id, 'oe_back_image')}\">
					<input type=\"text\" spellcheck=\"false\" ng-model=\"iframeScope.component.options[iframeScope.component.active.id]['model']['oe_back_image']\" ng-model-options=\"{ debounce: 10 }\"
						ng-change=\"iframeScope.setOption(iframeScope.component.active.id,'oe_flip_box','oe_back_image');\">
					<div class=\"oxygen-file-input-browse\"
						data-mediaTitle=\"Select Image\" 
						data-mediaButton=\"Select Image\"
						data-mediaMultiple=\"false\"
						data-mediaProperty=\"oe_back_image\"
						data-mediaType=\"mediaUrl\">" . __( 'browse', 'oxy-extended' ) . "
					</div>
				</div>
			</div>",
			'oe_back_image',
			$back_icon_section
		);
		$image->setParam( 'heading', __( 'Image', 'oxy-extended' ) );
		$image->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_flip_box_oe_back_icon_type']=='image'" );
		$image->rebuildElementOnChange();

		$selector = '.oe-flipbox-back .oe-flipbox-overlay';

		$back_layout_section = $back_section->addControlSection( 'back_layout', __( 'Layout & Styling', 'oxy-extended' ), 'assets/icon.png', $this );

		$back_layout_section->addStyleControl(
			array(
				'name'     => __( 'Background Color', 'oxy-extended' ),
				'selector' => $selector,
				'value'    => '',
				'property' => 'background-color',
			)
		);

		$back_alignment = $back_layout_section->addControl( 'buttons-list', 'oe_front_alignment', __( 'Text Alignment', 'oxy-extended' ) );
		$back_alignment->setValue( array(
			'left'   => __( 'Left', 'oxy-extended' ),
			'center' => __( 'Center', 'oxy-extended' ),
			'right'  => __( 'Right', 'oxy-extended' ),
		) );
		$back_alignment->setValueCSS( array(
			'left'  => '.oe-flipbox-back .oe-flipbox-overlay { text-align: left; }',
			'right' => '.oe-flipbox-back .oe-flipbox-overlay { text-align: right; }',
		));
		$back_alignment->setDefaultValue( 'center' );
		$back_alignment->whiteList();

		$vertical_position_back = $back_layout_section->addControl( 'buttons-list', 'oe_vertical_position_back', __( 'Vertical Position', 'oxy-extended' ) );
		$vertical_position_back->setValue( array(
			'top'    => __( 'Top', 'oxy-extended' ),
			'middle' => __( 'Middle', 'oxy-extended' ),
			'bottom' => __( 'Bottom', 'oxy-extended' ),
		) );
		$vertical_position_back->setValueCSS( array(
			'top' => '.oe-flipbox-front .oe-flipbox-overlay { justify-content: flex-start; }',
			'middle' => '.oe-flipbox-front .oe-flipbox-overlay { justify-content: center; }',
			'bottom' => '.oe-flipbox-front .oe-flipbox-overlay { justify-content: flex-end; }',
		));
		$vertical_position_back->setDefaultValue( 'middle' );
		$vertical_position_back->whiteList();

		$back_section->borderSection( __( 'Border', 'oxy-extended' ), $selector, $this );
		$back_section->typographySection( __( 'Title Typography', 'oxy-extended' ), '.oe-flipbox-heading-back', $this );
		$back_section->typographySection( __( 'Description Typography', 'oxy-extended' ), '.oe-flipbox-content-back', $this );
	}

	/**
	 * Controls for flipbox settings
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function settings_controls() {
		/**
		 * Settings Section
		 * -------------------------------------------------
		 */
		$settings_section = $this->addControlSection( 'settings', __( 'Settings', 'oxy-extended' ), 'assets/icon.png', $this );

		$flip_effect = $settings_section->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Flip Effect', 'oxy-extended' ),
				'slug'    => 'flip_effect',
				'value'   => array(
					'flip'     => esc_html__( 'Flip', 'oxy-extended' ),
					'slide'    => esc_html__( 'Slide', 'oxy-extended' ),
					'push'     => esc_html__( 'Push', 'oxy-extended' ),
					'zoom-in'  => esc_html__( 'Zoom In', 'oxy-extended' ),
					'zoom-out' => esc_html__( 'Zoom Out', 'oxy-extended' ),
					'fade'     => esc_html__( 'Fade', 'oxy-extended' ),
				),
				'default' => 'flip',
				'css'     => false,
			)
		);
		$flip_effect->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$flip_effect->rebuildElementOnChange();

		$flip_direction = $settings_section->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Flip Effect', 'oxy-extended' ),
				'slug'    => 'flip_direction',
				'value'   => array(
					'left'     => esc_html__( 'Left', 'oxy-extended' ),
					'right'    => esc_html__( 'Right', 'oxy-extended' ),
					'up'       => esc_html__( 'Top', 'oxy-extended' ),
					'down'     => esc_html__( 'Bottom', 'oxy-extended' ),
				),
				'default' => 'left',
				'css'     => false,
			)
		);
		$flip_direction->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$flip_direction->rebuildElementOnChange();
	}

	public function render_front( $options ) {
		$front_icon = isset( $options['oe_front_icon'] ) ? $options['oe_front_icon'] : '';
		$front_image = isset( $options['oe_front_image'] ) ? $options['oe_front_image'] : '';
		$title_tag = isset( $options['oe_title_html_tag_front'] ) ? $options['oe_title_html_tag_front'] : 'h3';
		?>
		<div class="oe-flipbox-front">
			<div class="oe-flipbox-overlay">
				<div class="oe-flipbox-inner">
					<?php if ( 'icon' === $options['oe_front_icon_type'] && $front_icon ) { ?>
						<div class="oe-flipbox-icon">
							<?php
								global $oxygen_svg_icons_to_load;

								$oxygen_svg_icons_to_load[] = $front_icon;

								echo '<svg id="svg' . esc_attr( $options['selector'] ) . '-icon" class="oe-flipbox-icon-svg"><use xlink:href="#' . $front_icon . '"></use></svg>';
							?>
						</div>
					<?php } ?>
					<?php if ( 'image' === $options['oe_front_icon_type'] && $front_image ) { ?>
						<div class="oe-flipbox-icon-image">
							<?php echo '<img src='.$front_image.'>'; ?>
						</div>
					<?php } ?>
					<<?php echo esc_attr( $title_tag ); ?> class="oe-flipbox-heading oe-flipbox-heading-front">
						<?php
							echo $options['oe_title_front'];
						?>
					</<?php echo esc_attr( $title_tag ); ?>>
					<div class="oe-flipbox-content">
						<?php
							echo $options['oe_description_front'];
						?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public function render_back( $options ) {
		$back_icon = isset( $options['oe_back_icon'] ) ? $options['oe_back_icon'] : '';
		$back_image = isset( $options['oe_back_image'] ) ? $options['oe_back_image'] : '';
		$back_title_tag = isset( $options['oe_title_html_tag_back'] ) ? $options['oe_title_html_tag_back'] : 'h3';
		?>
		<div class="oe-flipbox-back">
			<div class="oe-flipbox-overlay">
				<div class="oe-flipbox-inner">
					<?php if ( 'icon' === $options['oe_back_icon_type'] && $back_icon ) { ?>
						<div class="oe-flipbox-icon oe-flipbox-icon-back">
							<?php
								global $oxygen_svg_icons_to_load;

								$oxygen_svg_icons_to_load[] = $back_icon;

								echo '<svg id="svg' . esc_attr( $options['selector'] ) . '-icon" class="oe-flipbox-icon-svg"><use xlink:href="#' . $back_icon . '"></use></svg>';
							?>
						</div>
					<?php } ?>
					<?php if ( 'image' === $options['oe_back_icon_type'] && $back_image ) { ?>
						<div class="oe-flipbox-icon-image oe-flipbox-icon-image-back">
							<?php echo '<img src='.$back_image.'>'; ?>
						</div>
					<?php } ?>
					<<?php echo esc_attr( $back_title_tag ); ?> class="oe-flipbox-heading oe-flipbox-heading-back">
						<?php
						$html = $options['oe_title_back'];
						if ( isset( $options['back_link'] ) ) {
							$flip_box_back_link = $options['back_link'];
							if ( strstr( $flip_box_back_link, 'oedata_' ) ) {
								$flip_box_back_link = base64_decode( str_replace( 'oedata_', '', $flip_box_back_link ) );
								$shortcode = oxyextend_gsss( $this->El, $flip_box_back_link );
								$flip_box_back_link = do_shortcode( $flip_box_back_link );
							}
							$html = '<a href="' . esc_url( $flip_box_back_link ) . '">' . $html . '</a>';
						}
						echo $html;
						?>
					</<?php echo esc_attr( $back_title_tag ); ?>>
					<div class="oe-flipbox-content oe-flipbox-content-back">
						<?php
							echo $options['oe_description_front'];
						?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render flip box element output on the frontend.
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
		?>
		<div class="oe-flipbox-container oe-animate-<?php echo esc_attr( $options['flip_effect'] ); ?> oe-direction-<?php echo esc_attr( $options['flip_direction'] ); ?>">
			<div class="oe-flipbox-flip-card">
				<?php
					// Front
					$this->render_front( $options );

					// Back
					$this->render_back( $options );
				?>
			</div>
		</div>
		<?php
	}

	public function position_dictionary( $position ) {
		switch ( $position ) {
			case 'top':
				$selector = 'flex-start';
				break;

			case 'middle':
				$selector = 'center';
				break;

			case 'bottom':
				$selector = 'flex-end';
				break;

			default:
				$selector = 'center';
				break;
		}

		return $selector;
	}

	public function customCSS( $original, $selector ) {
		$css = '';

		if ( ! $this->css_added ) {
			$this->css_added = true;
			$css .= file_get_contents( __DIR__ . '/' . basename( __FILE__, '.php' ) . '.css' );
		}

		$prefix = $this->El->get_tag();

		/* if ( isset( $original[ $prefix . '_oe_vertical_position_front' ] ) ) {
			$vertical_position_front = $this->position_dictionary( $original[ $prefix . '_oe_vertical_position_front' ] );
			$css .= $selector . ' .oe-flipbox-overlay { justify-content: ' . $vertical_position_front . '; }';
		} */

		return $css;
	}
}

( new OEFlipBox() )->removeApplyParamsButton();
