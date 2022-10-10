<?php
namespace Oxygen\OxyExtended;

/**
 * Counter Element
 */
class OECounter extends \OxyExtendedEl {

	public $css_added = false;
	public $countdown_js_code = false;

	/**
	 * Retrieve counter element name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'Counter', 'oxy-extended' );
	}

	/**
	 * Retrieve counter element slug.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element slug.
	 */
	public function slug() {
		return 'oe_counter';
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
	 * Retrieve counter element icon.
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
		$this->number_controls();
		$this->icon_controls();
		$this->title_controls();
		$this->settings_controls();
	}

	/**
	 * Controls for front section
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function number_controls() {
		$number_section = $this->addControlSection( 'number_section', __( 'Number', 'oxy-extended' ), 'assets/icon.png', $this );

		$number_section->addOptionControl(
			array(
				'type'  => 'textfield',
				'name'  => __( 'Starting Number', 'oxy-extended' ),
				'slug'  => 'oe_starting_number',
				'value' => '0',
			)
		)->setParam( 'hide_wrapper_end', true );

		$number_section->addOptionControl(
			array(
				'type'  => 'textfield',
				'name'  => __( 'Ending Number', 'oxy-extended' ),
				'slug'  => 'oe_ending_number',
				'value' => '250',
			)
		)->setParam( 'hide_wrapper_start', true );

		$number_section->addOptionControl(
			array(
				'type'  => 'textfield',
				'name'  => __( 'Number Prefix', 'oxy-extended' ),
				'slug'  => 'oe_number_prefix',
				'value' => '',
			)
		)->rebuildElementOnChange();

		$number_section->addOptionControl(
			array(
				'type'  => 'textfield',
				'name'  => __( 'Number Suffix', 'oxy-extended' ),
				'slug'  => 'oe_number_suffix',
				'value' => '',
			)
		)->rebuildElementOnChange();

		$number_section->addOptionControl(
			array(
				'type'          => 'radio',
				'name'          => __( 'Thousand Separator', 'oxy-extended' ),
				'slug'          => 'oe_thousand_separator',
				'value'         => array(
					'yes'       => __( 'Show', 'oxy-extended' ),
					'no'        => __( 'Hide', 'oxy-extended' ),
				),
				'default'       => 'no',
			)
		)->rebuildElementOnChange();

		$number_section->addOptionControl(
			array(
				'type'          => 'radio',
				'name'          => __( 'Separator', 'oxy-extended' ),
				'slug'          => 'oe_thousand_separator_char',
				'value'         => array(
					''          => __( 'Default', 'oxy-extended' ),
					'.'         => __( 'Dot', 'oxy-extended' ),
					'space'     => __( 'Space', 'oxy-extended' ),
				),
				'default'       => '',
				'condition'     => 'oe_thousand_separator=yes',
			)
		)->rebuildElementOnChange();

		$number_section->addStyleControls(
			array(
				array(
					'name'             => __( 'Separator Color', 'oxy-extended' ),
					'selector'         => '.odometer-formatting-mark',
					'property'         => 'color',
					'control_type'     => 'colorpicker',
					'condition'        => 'oe_thousand_separator=yes&&oe_thousand_separator_char!=space',
				),
			)
		);

		$number_divider = $number_section->addControlSection( 'number_divider', __( 'Number Divider', 'oxy-extended' ), 'assets/icon.png', $this );

		$divider_selector = '.oe-counter-num-divider';

		$number_divider->addOptionControl(
			array(
				'type'         => 'radio',
				'name'         => __( 'Number Divider', 'oxy-extended' ),
				'slug'         => 'oe_num_divider',
				'value'        => array(
					'yes'      => __( 'Show', 'oxy-extended' ),
					'no'       => __( 'Hide', 'oxy-extended' ),
				),
				'default'      => 'no',
			)
		)->rebuildElementOnChange();

		$number_divider->addStyleControls(
			array(
				array(
					'name'             => __( 'Divider Color', 'oxy-extended' ),
					'selector'         => $divider_selector,
					'property'         => 'border-bottom-color',
					'control_type'     => 'colorpicker',
					'condition'        => 'oe_num_divider=yes',
				),
				array(
					'name'             => __( 'Height', 'oxy-extended' ),
					'selector'         => $divider_selector,
					'property'         => 'border-bottom-width',
					'condition'        => 'oe_num_divider=yes',
				),
				array(
					'name'             => __( 'Width', 'oxy-extended' ),
					'selector'         => $divider_selector,
					'property'         => 'width',
					'condition'        => 'oe_num_divider=yes',
				),
			)
		);

		$number_style = $number_section->addControlSection( 'number_style', __( 'Number Style', 'oxy-extended' ), 'assets/icon.png', $this );

		$number_style->addStyleControls(
			array(
				array(
					'selector'          => '.oe-counter-number',
					'property'          => 'color',
					'css'               => false,
				),
				array(
					'selector'          => '.oe-counter-number',
					'property'          => 'font-family',
					'css'               => false,
				),
				array(
					'selector'          => '.oe-counter-number',
					'property'          => 'font-weight',
				),
				array(
					'selector'          => '.oe-counter-number',
					'property'          => 'font-size',
					'value'             => 28,
				),
				array(
					'selector'          => '.oe-counter-number',
					'property'          => 'line-height',
				),
				array(
					'selector'          => '.oe-counter-number',
					'property'          => 'letter-spacing',
				),
			)
		);

		$number_prefix_style = $number_section->addControlSection( 'number_prefix_style', __( 'Number Prefix Style', 'oxy-extended' ), 'assets/icon.png', $this );

		$number_prefix_style->addStyleControls(
			array(
				array(
					'selector'          => '.oe-counter-number-prefix',
					'property'          => 'color',
					'css'               => false,
				),
				array(
					'selector'          => '.oe-counter-number-prefix',
					'property'          => 'font-family',
					'css'               => false,
				),
				array(
					'selector'          => '.oe-counter-number-prefix',
					'property'          => 'font-weight',
				),
				array(
					'selector'          => '.oe-counter-number-prefix',
					'property'          => 'font-size',
					'value'             => 28,
				),
				array(
					'selector'          => '.oe-counter-number-prefix',
					'property'          => 'line-height',
				),
				array(
					'selector'          => '.oe-counter-number-prefix',
					'property'          => 'letter-spacing',
				),
			)
		);

		$number_suffix_style = $number_section->addControlSection( 'number_suffix_style', __( 'Number Suffix Style', 'oxy-extended' ), 'assets/icon.png', $this );

		$number_suffix_style->addStyleControls(
			array(
				array(
					'selector'          => '.oe-counter-number-suffix',
					'property'          => 'color',
					'css'               => false,
				),
				array(
					'selector'          => '.oe-counter-number-suffix',
					'property'          => 'font-family',
					'css'               => false,
				),
				array(
					'selector'          => '.oe-counter-number-suffix',
					'property'          => 'font-weight',
				),
				array(
					'selector'          => '.oe-counter-number-suffix',
					'property'          => 'font-size',
					'value'             => 28,
				),
				array(
					'selector'          => '.oe-counter-number-suffix',
					'property'          => 'line-height',
				),
				array(
					'selector'          => '.oe-counter-number-suffix',
					'property'          => 'letter-spacing',
				),
			)
		);

	}

	/**
	 * Controls for icon section
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function icon_controls() {
		$icon_section = $this->addControlSection( 'icon_section', __( 'Icon', 'oxy-extended' ), 'assets/icon.png', $this );

		$icon_section->addOptionControl(
			array(
				'type'          => 'radio',
				'name'          => __( 'Icon Type', 'oxy-extended' ),
				'slug'          => 'oe_icon_type',
				'value'         => array(
					'none'      => esc_html__( 'None', 'oxy-extended' ),
					'icon'      => esc_html__( 'Icon', 'oxy-extended' ),
					'image'     => esc_html__( 'Image', 'oxy-extended' ),
				),
				'default'       => 'none',
				'css'           => false,
			)
		)->rebuildElementOnChange();

		$icon_section->addOptionControl(
			array(
				'type'          => 'icon_finder',
				'name'          => __( 'Icon', 'oxy-extended' ),
				'slug'          => 'oe_counter_icon',
				'value'         => 'FontAwesomeicon-check',
				'default'       => 'FontAwesomeicon-check',
				'css'           => false,
				'condition'     => 'oe_icon_type=icon',
			)
		)->rebuildElementOnChange();

		$image = $icon_section->addCustomControl(
			"<div class='oxygen-control'>
				<div class='oxygen-file-input'
				ng-class=\"{'oxygen-option-default':iframeScope.isInherited(iframeScope.component.active.id, 'oe_counter_image')}\">
					<input type=\"text\" spellcheck=\"false\" ng-model=\"iframeScope.component.options[iframeScope.component.active.id]['model']['oe_counter_image']\" ng-model-options=\"{ debounce: 10 }\"
						ng-change=\"iframeScope.setOption(iframeScope.component.active.id,'oe_counter','oe_counter_image');\">
					<div class=\"oxygen-file-input-browse\"
						data-mediaTitle=\"Select Image\" 
						data-mediaButton=\"Select Image\"
						data-mediaMultiple=\"false\"
						data-mediaProperty=\"oe_counter_image\"
						data-mediaType=\"mediaUrl\">" . __( 'browse', 'oxy-extended' ) . '
					</div>
				</div>
			</div>',
			'oe_counter_image',
			$icon_section
		);
		$image->setParam( 'heading', __( 'Image', 'oxy-extended' ) );
		$image->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_counter_oe_icon_type']=='image'" );
		$image->rebuildElementOnChange();

		$icon_section->addOptionControl(
			array(
				'type'          => 'radio',
				'name'          => __( 'Icon Divider', 'oxy-extended' ),
				'slug'          => 'oe_icon_divider',
				'value'         => array(
					'yes'       => __( 'Show', 'oxy-extended' ),
					'no'        => __( 'Hide', 'oxy-extended' ),
				),
				'default'       => 'no',
			)
		)->rebuildElementOnChange();

		$divider_selector = '.oe-counter-icon-divider';

		$icon_section->addStyleControls(
			array(
				array(
					'name'         => __( 'Divider Color', 'oxy-extended' ),
					'selector'     => $divider_selector,
					'property'     => 'border-bottom-color',
					'control_type' => 'colorpicker',
					'condition'    => 'oe_icon_divider=yes',
				),
				array(
					'name'         => __( 'Height', 'oxy-extended' ),
					'selector'     => $divider_selector,
					'property'     => 'border-bottom-width',
					'condition'    => 'oe_icon_divider=yes',
				),
				array(
					'name'         => __( 'Width', 'oxy-extended' ),
					'selector'     => $divider_selector,
					'property'     => 'width',
					'condition'    => 'oe_icon_divider=yes',
				),
			)
		);

		$counter_selector = '.oe-counter-icon-wrap';

		$icon_style = $icon_section->addControlSection( 'icon_spacing_section', __( 'Icon Style', 'oxy-extended' ), 'assets/icon.png', $this );

		$icon_style->addPreset(
			'margin',
			'oe_icon_margin',
			__( 'Margin', 'oxy-extended' ),
			$counter_selector
		)->whiteList();

		$icon_style->addPreset(
			'padding',
			'oe_icon_padding',
			__( 'Padding', 'oxy-extended' ),
			$counter_selector
		)->whiteList();

		$icon_style->addStyleControl(
			array(
				'name'          => __( 'Icon Size', 'oxy-extended' ),
				'slug'          => 'icon_size',
				'selector'      => 'svg.oe-counter-icon-svg',
				'control_type'  => 'slider-measurebox',
				'value'         => '20',
				'property'      => 'width|height',
			)
		)
		->setRange( 20, 80, 1 )
		->setUnits( 'px', 'px' )
		->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_counter_oe_icon_type']=='icon'" );

		$icon_style->addStyleControls(
			array(
				array(
					'selector'      => $counter_selector,
					'property'      => 'width',
					'control_type'  => 'slider-measurebox',
					'units'         => 'px',
					'condition'     => 'oe_icon_type=icon',
				),
				array(
					'selector'      => $counter_selector,
					'property'      => 'height',
					'control_type'  => 'slider-measurebox',
					'units'         => 'px',
					'condition'     => 'oe_icon_type=icon',
				),
				array(
					'selector'      => $counter_selector,
					'property'      => 'line-height',
					'default'       => '2',
					'description'   => __( 'Using it, you can vertically center align the icon.', 'oxy-extended' ),
					'condition'     => 'oe_icon_type=icon',
				),
				array(
					'selector'      => $counter_selector,
					'property'      => 'background-color',
					'condition'     => 'oe_icon_type=icon',
				),
				array(
					'selector'      => '.oe-counter-icon-svg',
					'name'          => __( 'Color', 'oxy-extended' ),
					'property'      => 'fill',
					'control_type'  => 'colorpicker',
					'slug'          => 'oe_counter_icon_color',
					'condition'     => 'oe_icon_type=icon',
				),
				array(
					'name'          => __( 'Hover Color', 'oxy-extended' ),
					'selector'      => '.oe-counter-icon-wrap:hover svg',
					'property'      => 'fill',
					'control_type'  => 'colorpicker',
					'slug'          => 'oe_counter_icon_color_hover',
					'condition'     => 'oe_icon_type=icon',
				),
			)
		);

		$icon_style->addStyleControl(
			array(
				'name'          => __( 'Image Width', 'oxy-extended' ),
				'slug'          => 'icon_image_width',
				'selector'      => '.oe-counter-icon-img img',
				'control_type'  => 'slider-measurebox',
				'value'         => '80',
				'property'      => 'width',
			)
		)
		->setRange( 20, 500, 1 )
		->setUnits( 'px', 'px' )
		->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')&&iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_counter_oe_icon_type']=='image'" );

		$icon_style->addStyleControls(
			array(
				array(
					'selector'      => $counter_selector,
					'property'      => 'background-color',
					'condition'     => 'oe_icon_type=image',
				),
			)
		);
	}

	/**
	 * Controls for title section
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function title_controls() {
		/**
		 * Title Section
		 * -------------------------------------------------
		 */
		$title_section = $this->addControlSection( 'title_section', __( 'Title', 'oxy-extended' ), 'assets/icon.png', $this );
		$title_section->addOptionControl(
			array(
				'type'  => 'textfield',
				'name'  => __( 'Title', 'oxy-extended' ),
				'slug'  => 'oe_counter_title',
				'value' => __( 'Counter Title', 'oxy-extended' ),
			)
		)->rebuildElementOnChange();

		$title_section->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Title HTML Tag', 'oxy-extended' ),
				'slug'    => 'oe_title_html_tag',
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
				'default' => 'div',
			)
		)->rebuildElementOnChange();

		$title_spacing_section = $title_section->addControlSection( 'title_spacing_section', __( 'Colors & Spacing', 'oxy-extended' ), 'assets/icon.png', $this );

		$title_color = $title_spacing_section->addStyleControl(
			array(
				'name'     => __( 'Background Color', 'oxy-extended' ),
				'selector' => '.oe-counter-title-wrap',
				'value'    => '',
				'property' => 'background-color',
			)
		);
		$title_color->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );

		$title_spacing_section->addPreset(
			'margin',
			'oe_title_margin',
			__( 'Margin', 'oxy-extended' ),
			'.oe-counter-title-wrap'
		)->whiteList();

		$title_spacing_section->addPreset(
			'padding',
			'oe_title_padding',
			__( 'Padding', 'oxy-extended' ),
			'.oe-counter-title-wrap'
		)->whiteList();

		$title_section->typographySection( __( 'Typography', 'oxy-extended' ), '.oe-counter-title', $this );
	}

	/**
	 * Controls for Counter settings
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function settings_controls() {
		/**
		 * Settings Section
		 * -------------------------------------------------
		 */
		$options_section = $this->addControlSection( 'settings', __( 'Settings', 'oxy-extended' ), 'assets/icon.png', $this );

		$options_section->addOptionControl(
			array(
				'type'         => 'buttons-list',
				'name'         => __( 'Alignment', 'oxy-extended' ),
				'slug'         => 'oe_counter_alignment',
				'value'        => array(
					'left'     => __( 'Left', 'oxy-extended' ),
					'center'   => __( 'Center', 'oxy-extended' ),
					'right'    => __( 'Right', 'oxy-extended' ),
					'justify'  => __( 'Justify', 'oxy-extended' ),
				),
				'default'      => 'center',
			)
		)->setValueCSS( array(
			'left'             => '.oe-counter-container { text-align: left; }',
			'center'           => '.oe-counter-container { text-align: center; }',
			'right'            => '.oe-counter-container { text-align: right; }',
			'justify'          => '.oe-counter-container { text-align: justify; }',
		));

		$layouts = array();
		for ( $i = 1; $i <= 10; $i++ ) {
			if ( $i < 10 ) {
				$x = $i;
			}

			$layouts[ 'layout-' . $x ] = __( 'Layout', 'oxy-extended' ) . ' ' . $i;
		}

		$options_section->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Layout', 'oxy-extended' ),
				'slug'    => 'oe_counter_layout',
				'value'   => $layouts,
				'default' => 'layout-1',
			)
		)->rebuildElementOnChange();

		$options_section->addOptionControl(
			array(
				'type'  => 'textfield',
				'name'  => __( 'Counter Speed', 'oxy-extended' ),
				'slug'  => 'oe_counter_speed',
				'value' => '1500',
			)
		)->rebuildElementOnChange();
	}

	/**
	 * Render counter number output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	public function render_counter_number( $options ) {
		$starting_number = ( $options['oe_starting_number'] ) ? (int) $options['oe_starting_number'] : 0;
		$ending_number = ( $options['oe_ending_number'] ) ? (int) $options['oe_ending_number'] : 250;
		$counter_speed = ( $options['oe_counter_speed'] ) ? (int) $options['oe_counter_speed'] : 1500;
		$thousand_separator = $options['oe_thousand_separator'];
		$thousand_separator_char = $options['oe_thousand_separator_char'];
		if ( 'space' === $thousand_separator_char ) {
			$thousand_separator_char = '&nbsp;';
		}
		?>
		<div class="oe-counter-number-wrap">
			<?php
			if ( $options['oe_number_prefix'] ) {
				printf( '<span class="oe-counter-number-prefix">%1$s</span>', $options['oe_number_prefix'] );
			}
			?>
			<div class="oe-counter-number <?php echo 'oe-counter-number-' . get_the_ID(); ?>" data-from=<?php echo $starting_number; ?> data-to=<?php echo $ending_number; ?> data-speed=<?php echo $counter_speed; ?> data-separator=<?php echo $thousand_separator; ?> data-separator-char=<?php echo $thousand_separator_char; ?>>
				<?php
					echo $starting_number;
				?>
			</div>
			<?php
			if ( $options['oe_number_suffix'] ) {
				printf( '<span class="oe-counter-number-suffix">%1$s</span>', $options['oe_number_suffix'] );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render counter title output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	public function render_counter_title( $options ) {

		if ( $options['oe_counter_title'] ) { ?>
			<div class="oe-counter-title-wrap">
				<<?php echo $options['oe_title_html_tag']; ?> class="oe-counter-title">
					<?php echo $options['oe_counter_title']; ?>
				</<?php echo $options['oe_title_html_tag']; ?>>
			</div>
			<?php
		}
	}

	/**
	 * Render counter icon output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	public function render_counter_icon( $options ) {
		$counter_icon = $options['oe_counter_icon'];

		if ( 'icon' === $options['oe_icon_type'] && $counter_icon ) { ?>
			<span class="oe-counter-icon-wrap">
				<span class="oe-counter-icon oe-icon">
					<?php
						global $oxygen_svg_icons_to_load;

						$oxygen_svg_icons_to_load[] = $counter_icon;

						echo '<svg id="svg' . esc_attr( $options['selector'] ) . '-icon" class="oe-counter-icon-svg"><use xlink:href="#' . $counter_icon . '"></use></svg>';
					?>
				</span>
			</span>
		<?php }
		if ( 'image' === $options['oe_icon_type'] ) {
			$counter_image = $options['oe_counter_image'];
			if ( $counter_image ) { ?>
				<span class="oe-counter-icon-wrap">
					<span class="oe-counter-icon oe-counter-icon-img">
						<?php echo '<img src=' . $counter_image . '>'; ?>
					</span>
				</span>
			<?php }
		}

		if ( 'yes' === $options['oe_icon_divider'] ) {
			if ( 'layout-1' === $options['oe_counter_layout'] || 'layout-2' === $options['oe_counter_layout'] ) { ?>
				<div class="oe-counter-icon-divider-wrap">
					<span class="oe-counter-icon-divider"></span>
				</div>
				<?php
			}
		}
	}

	/**
	 * Render counter element output on the frontend.
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
		?>
		<div class="oe-counter-container">
			<div class="oe-counter oe-counter-<?php echo esc_attr( $options['oe_counter_layout'] ); ?>" data-target="<?php echo '.oe-counter-number-' . get_the_ID(); ?>">
				<?php if ( 'layout-1' === $options['oe_counter_layout'] || 'layout-5' === $options['oe_counter_layout'] || 'layout-6' === $options['oe_counter_layout'] ) { ?>
					<?php
						// Counter Icon
						$this->render_counter_icon( $options );
					?>

					<div class="oe-counter-number-title-wrap">
						<?php
							// Counter Number
							$this->render_counter_number( $options );
						?>

						<?php if ( 'yes' === $options['oe_num_divider'] ) { ?>
							<div class="oe-counter-num-divider-wrap">
								<span class="oe-counter-num-divider"></span>
							</div>
						<?php } ?>

						<?php
							// Counter Title
							$this->render_counter_title( $options );
						?>
					</div>
				<?php } elseif ( 'layout-2' === $options['oe_counter_layout'] ) { ?>
					<?php
					// Counter Icon
					$this->render_counter_icon( $options );

					// Counter Title
					$this->render_counter_title( $options );

					// Counter Number
					$this->render_counter_number( $options );

					if ( 'yes' === $options['oe_num_divider'] ) { ?>
						<div class="oe-counter-num-divider-wrap">
							<span class="oe-counter-num-divider"></span>
						</div>
						<?php
					}
				} elseif ( 'layout-3' === $options['oe_counter_layout'] ) {

					// Counter Number
					$this->render_counter_number( $options );
					?>

					<?php if ( 'yes' === $options['oe_num_divider'] ) { ?>
						<div class="oe-counter-num-divider-wrap">
							<span class="oe-counter-num-divider"></span>
						</div>
					<?php } ?>

					<div class="oe-icon-title-wrap">
						<?php
						// Counter Icon
						$this->render_counter_icon( $options );

						// Counter Title
						$this->render_counter_title( $options );
						?>
					</div>
				<?php } elseif ( 'layout-4' === $options['oe_counter_layout'] ) { ?>
					<div class="oe-icon-title-wrap">
						<?php
							// Counter Icon
							$this->render_counter_icon( $options );

							// Counter Title
							$this->render_counter_title( $options );
						?>
					</div>

					<?php
						// Counter Number
						$this->render_counter_number( $options );
					?>

					<?php if ( 'yes' === $options['oe_num_divider'] ) { ?>
						<div class="oe-counter-num-divider-wrap">
							<span class="oe-counter-num-divider"></span>
						</div>
					<?php }
				} elseif ( 'layout-7' === $options['oe_counter_layout'] || 'layout-8' === $options['oe_counter_layout'] ) {

					// Counter Number
					$this->render_counter_number( $options );
					?>
					<div class="oe-icon-title-wrap">
					<?php
						// Counter Icon
						$this->render_counter_icon( $options );

						// Counter Title
						$this->render_counter_title( $options );
					?>
					</div>
				<?php } elseif ( 'layout-9' === $options['oe_counter_layout'] ) {
					?>
						<div class="oe-icon-number-wrap">
							<?php
								// Counter Icon
								$this->render_counter_icon( $options );

								// Counter Number
								$this->render_counter_number( $options );
							?>
						</div>
						<?php
							// Counter Title
							$this->render_counter_title( $options );
						?>
				<?php } elseif ( 'layout-10' === $options['oe_counter_layout'] ) {
					?>
					<div class="oe-icon-number-wrap">
						<?php
							// Counter Number
							$this->render_counter_number( $options );

							// Counter Icon
							$this->render_counter_icon( $options );
						?>
					</div>
					<?php
						// Counter Title
						$this->render_counter_title( $options );
					?>
				<?php } ?>
			</div>
		</div>
		<?php
		ob_start();

		$this->get_counter_script( $options, $uid );

		$js = ob_get_clean();

		if ( isset( $_GET['oxygen_iframe'] ) || defined( 'OXY_ELEMENTS_API_AJAX' ) ) {
			$this->oe_enqueue_scripts();
			$this->El->builderInlineJS( $js );
		} else {

			add_action( 'wp_footer', array( $this, 'oe_enqueue_scripts' ) );

			$this->countdown_js_code[] = $js;
			$this->El->footerJS( join( '', $this->countdown_js_code ) );
		}
	}

	public function get_counter_script( $options, $uid ) {
		?>
		jQuery(document).ready(function($) {
			var counter_elem   = $('.oe-counter'),
			target         = counter_elem.data('target'),
			separator      = $('.oe-counter-number').data('separator'),
			separator_char = $('.oe-counter-number').data('separator-char'),
			format         = ( separator_char !== '' ) ? '(' + separator_char + 'ddd).dd' : '(,ddd).dd';

		$(counter_elem).waypoint(function () {
			$(target).each(function () {
				var v             = $(this).data('to'),
					speed         = $(this).data('speed'),
					od            = new Odometer({
						el:       this,
						value:    0,
						duration: speed,
						format:   (separator === 'yes') ? format : ''
					});
				od.render();
				setInterval(function () {
					od.update(v);
				});
			});
		},
			{
				offset:      '80%',
				triggerOnce: true
			});
		});
		<?php
	}

	public function counter_load_scripts() {
		if ( isset( $_GET['ct_builder'] ) && $_GET['ct_builder'] ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'oe_enqueue_scripts' ) );
		}
	}

	public function oe_enqueue_scripts() {
		wp_enqueue_style(
			'odometer',
			OXY_EXTENDED_URL . 'assets/lib/odometer/odometer-theme-default.css',
			array(),
			'0.4.8',
			'all'
		);
		wp_enqueue_script(
			'waypoints',
			OXY_EXTENDED_URL . 'assets/lib/waypoints/waypoints.min.js',
			array(
				'jquery',
			),
			'4.0.1',
			true
		);
		wp_enqueue_script(
			'odometer',
			OXY_EXTENDED_URL . 'assets/lib/odometer/odometer.min.js',
			array(
				'jquery',
			),
			'0.4.8',
			true
		);
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

$oecounter = new OECounter();
$oecounter->counter_load_scripts();
