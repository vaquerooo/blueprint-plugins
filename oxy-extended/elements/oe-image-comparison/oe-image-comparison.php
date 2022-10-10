<?php
namespace Oxygen\OxyExtended;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Image Comparison Element
 */
class OEImageComparison extends \OxyExtendedEl {

	public $css_added = false;
	private $oe_ic_js_code = '';
	public $js_added = false;

	/**
	 * Retrieve image comparison element name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'Image Comparison', 'oxy-extended' );
	}

	/**
	 * Retrieve image comparison element slug.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element slug.
	 */
	public function slug() {
		return 'oe_image_comparison';
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
	 * Retrieve image comparison element icon.
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
	 * Get thumbnail size list
	 */
	public function oe_thumbnail_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = $img_sizes = array();

		foreach ( get_intermediate_image_sizes() as $s ) {
			$sizes[ $s ] = array( 0, 0 );
			if ( in_array( $s, array( 'thumbnail', 'medium', 'large' ) ) ) {
				$sizes[ $s ][0] = get_option( $s . '_size_w' );
				$sizes[ $s ][1] = get_option( $s . '_size_h' );
			} else {
				if ( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) )
					$sizes[ $s ] = array( $_wp_additional_image_sizes[ $s ]['width'], $_wp_additional_image_sizes[ $s ]['height'] );
			}
		}

		foreach ( $sizes as $size => $atts ) {
			$size_title = ucwords( str_replace( '-', ' ', $size ) );
			$img_sizes[ $size ] = $size_title . ' (' . implode( 'x', $atts ) . ')';
		}

		$img_sizes['full'] = __( 'Full', 'oxy-extended' );

		return $img_sizes;
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
		$before_image = $this->addCustomControl(
			"<div class=\"oxygen-control  not-available-for-classes not-available-for-media\">			
				<div class=\"oxygen-file-input\">
					<input type=\"text\" spellcheck=\"false\" ng-change=\"iframeScope.setOption(iframeScope.component.active.id,'oe_image_comparison','oxy-oe_image_comparison_oe_ic_before_image'); iframeScope.parseImageShortcode()\" ng-class=\"{'oxygen-option-default':iframeScope.isInherited(iframeScope.component.active.id, 'oxy-oe_image_comparison_oe_ic_before_image')}\" ng-model=\"iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_image_comparison_oe_ic_before_image']\" ng-model-options=\"{ debounce: 10 }\" class=\"ng-pristine ng-valid oxygen-option-default ng-touched\">
					<div class=\"oxygen-file-input-browse\" data-mediatitle=\"Select Image\" data-mediabutton=\"Select Image\" data-mediaproperty=\"oxy-oe_image_comparison_oe_ic_before_image\" data-mediatype=\"mediaUrl\" data-returnvalue=\"url\">browse</div>
					<div class=\"oxygen-dynamic-data-browse ng-isolate-scope\" ctdynamicdata data=\"iframeScope.dynamicShortcodesImageMode\" callback=\"iframeScope.oeDynamicICBImage\">data</div>
				</div>
			</div>",
			'before_image'
		);
		$before_image->setParam( 'heading', __( 'Before Image', 'oxy-extended' ) );
		$before_image->rebuildElementOnChange();

		$before_label = $this->addOptionControl(
			array(
				'type'   => 'textfield',
				'name'   => __( 'Before Label', 'oxy-extended' ),
				'slug'   => 'oe_ic_before_label',
				'base64' => true,
			)
		);
		$before_label->setValue( __( 'Before', 'oxy-extended' ) );
		$before_label->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );

		$after_image = $this->addCustomControl(
			"<div class=\"oxygen-control  not-available-for-classes not-available-for-media\">			
				<div class=\"oxygen-file-input\">
					<input type=\"text\" spellcheck=\"false\" ng-change=\"iframeScope.setOption(iframeScope.component.active.id,'ct_image','oxy-oe_image_comparison_oe_ic_after_image'); iframeScope.parseImageShortcode()\" ng-class=\"{'oxygen-option-default':iframeScope.isInherited(iframeScope.component.active.id, 'oxy-oe_image_comparison_oe_ic_after_image')}\" ng-model=\"iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_image_comparison_oe_ic_after_image']\" ng-model-options=\"{ debounce: 10 }\" class=\"ng-pristine ng-valid oxygen-option-default ng-touched\">
					<div class=\"oxygen-file-input-browse\" data-mediatitle=\"Select Image\" data-mediabutton=\"Select Image\" data-mediaproperty=\"oxy-oe_image_comparison_oe_ic_after_image\" data-mediatype=\"mediaUrl\" data-returnvalue=\"url\">browse</div>
					<div class=\"oxygen-dynamic-data-browse ng-isolate-scope\" ctdynamicdata data=\"iframeScope.dynamicShortcodesImageMode\" callback=\"iframeScope.oeDynamicICAImage\">data</div>
				</div>
			</div>",
			'after_image'
		);
		$after_image->setParam( 'heading', __( 'After Image', 'oxy-extended' ) );
		$after_image->rebuildElementOnChange();

		$after_label = $this->addOptionControl(
			array(
				'type'   => 'textfield',
				'name'   => __( 'After Label', 'oxy-extended' ),
				'slug'   => 'oe_ic_after_label',
				'base64' => true,
			)
		);
		$after_label->setValue( __( 'After', 'oxy-extended' ) );
		$after_label->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );

		$this->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Always Show Label On Mobile', 'oxy-extended' ),
				'slug'    => 'oe_ic_label',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'no',
			)
		);

		$orientation = $this->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Orientation', 'oxy-extended' ),
				'slug'    => 'oe_ic_orientation',
				'value'   => array(
					'horizontal' => __( 'Horizontal', 'oxy-extended' ),
					'vertical'   => __( 'Vertical', 'oxy-extended' ),
				),
				'default' => 'horizontal',
			)
		);
		$orientation->rebuildElementOnChange();

		$move_on_hover = $this->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Move on Hover', 'oxy-extended' ),
				'slug'    => 'oe_ic_mhover',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'no',
			)
		);
		$move_on_hover->rebuildElementOnChange();

		$comparison_handle = $this->addControlSection( 'ic_comparison_handle', __( 'Comparison Handle', 'oxy-extended' ), 'assets/icon.png', $this );

		$comparison_handle->addOptionControl(
			array(
				'type'    => 'slider-measurebox',
				'name'    => __( 'Initial Offset', 'oxy-extended' ),
				'slug'    => 'oe_ic_initial_offset',
			)
		)->setUnits( '', ' ' )->setRange( '0', '0.9', '0.1' )->setValue( '0.5' );

		$comparison_handle->addStyleControl(
			array(
				'control_type' => 'slider-measurebox',
				'name'         => __( 'Triangle Width', 'oxy-extended' ),
				'slug'         => 'oe_ic_handle_twidth',
				'selector'     => '.twentytwenty-left-arrow, .twentytwenty-right-arrow, .twentytwenty-up-arrow, .twentytwenty-down-arrow',
				'property'     => 'border-width',
			)
		)->setRange( '1', '100', '3' )->setUnits( 'px', 'px' )->setValue( '7' );

		$circle = $comparison_handle->addControlSection( 'comparison_handle_circle', __( 'Circle', 'oxy-extended' ), 'assets/icon.png', $this );
		$circle->addStyleControl(
			array(
				'control_type' => 'slider-measurebox',
				'name'         => __( 'Width', 'oxy-extended' ),
				'slug'         => 'oe_ic_handle_width',
				'selector'     => '.twentytwenty-handle',
				'property'     => 'width|height',
			)
		)->setRange( '1', '100', '4' )->setUnits( 'px', 'px' )->setValue( '38' );

		$circle->addStyleControl(
			array(
				'control_type' => 'slider-measurebox',
				'name'         => __( 'Position', 'oxy-extended' ),
				'slug'         => 'oe_ic_handle_pos',
				'selector'     => '.twentytwenty-handle',
				'property'     => 'top',
			)
		)->setRange( '0', '100', '5' )->setUnits( '%', '%' )->setValue( '50' );

		$circle->addStyleControl(
			array(
				'control_type' => 'slider-measurebox',
				'name'         => __( 'Thickness', 'oxy-extended' ),
				'slug'         => 'oe_ic_handle_size',
				'selector'     => '.twentytwenty-handle',
				'property'     => 'border-width',
			)
		)->setRange( '1', '50', '2' )->setUnits( 'px', 'px' )->setValue( '3' );

		$circle->addStyleControl(
			array(
				'control_type' => 'slider-measurebox',
				'name'         => __( 'Radius', 'oxy-extended' ),
				'slug'         => 'oe_ic_handle_radius',
				'selector'     => '.twentytwenty-handle',
				'property'     => 'border-radius',
			)
		)->setRange( '0', '100', '10' )->setUnits( 'px', 'px' )->setValue( '100' );

		$color = $comparison_handle->addControlSection( 'comparison_handle_color', __( 'Color', 'oxy-extended' ), 'assets/icon.png', $this );
		$color->addStyleControls(
			array(
				array(
					'control_type' => 'colorpicker',
					'name'         => __( 'Color', 'oxy-extended' ),
					'slug'         => 'oe_ic_handle_color',
					'selector'     => '.twentytwenty-handle',
					'property'     => 'border-color',
				),
				array(
					'control_type' => 'colorpicker',
					'name'         => __( 'Background Color', 'oxy-extended' ),
					'slug'         => 'oe_ic_handle_bcolor',
					'selector'     => '.twentytwenty-handle',
					'property'     => 'background-color',
				),
				array(
					'control_type' => 'colorpicker',
					'name'         => __( 'Left Triangle Color', 'oxy-extended' ),
					'slug'         => 'oe_ic_lhandle_tcolor',
					'selector'     => '.twentytwenty-left-arrow',
					'property'     => 'border-right-color',
				),
				array(
					'control_type' => 'colorpicker',
					'name'         => __( 'Right Triangle Color', 'oxy-extended' ),
					'slug'         => 'oe_ic_rhandle_tcolor',
					'selector'     => '.twentytwenty-right-arrow',
					'property'     => 'border-left-color',
				),
			)
		);

		$color->addStyleControl(
			array(
				'control_type' => 'colorpicker',
				'name'         => __( 'Bar Color', 'oxy-extended' ),
				'slug'         => 'oe_ic_handle_color',
				'selector'     => '.twentytwenty-horizontal .twentytwenty-handle:before, .twentytwenty-horizontal .twentytwenty-handle:after, .twentytwenty-vertical .twentytwenty-handle:before, .twentytwenty-vertical .twentytwenty-handle:after',
				'property'     => 'background',
			)
		)->setParam( 'ng_show', "!isActiveName('" . $this->El->get_tag() . "')" );

		$this->addApplyParamsButton();
	}

	/**
	 * Render image comparison element output on the frontend.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render( $options, $defaults, $content ) {
		$bimg = OXY_EXTENDED_URL . 'assets/images/before-image.jpg';
		$afimg = OXY_EXTENDED_URL . 'assets/images/after-image.jpg';
		$blabel = $alabel = '';

		if ( isset( $options['oe_ic_before_image'] ) ) {
			$bimgurl = $options['oe_ic_before_image'];
			if ( strstr( $bimgurl, 'oedata_' ) ) {
				$bimgurl = base64_decode( str_replace( 'oedata_', '', $bimgurl ) );
				$shortcode = oxyextend_gsss( $this->El, $bimgurl );
				$bimgurl = do_shortcode( $shortcode );
			}
		} else {
			$bimgurl = $bimg;
		}

		if ( isset( $options['oe_ic_after_image'] ) ) {
			$aimgurl = $options['oe_ic_after_image'];
			if ( strstr( $aimgurl, 'oedata_' ) ) {
				$aimgurl = base64_decode( str_replace( 'oedata_', '', $aimgurl ) );
				$shortcode = oxyextend_gsss( $this->El, $aimgurl );
				$aimgurl = do_shortcode( $shortcode );
			}
		} else {
			$aimgurl = $afimg;
		}

		if ( isset( $options['oe_ic_before_label'] ) ) {
			$blabel = $options['oe_ic_before_label'];
			if ( strstr( $blabel, 'oedata_' ) ) {
				$blabel = base64_decode( str_replace( 'oedata_', '', $blabel ) );
				$shortcode = oxyextend_gsss( $this->El, $blabel );
				$blabel = do_shortcode( $shortcode );
			}
		}

		if ( isset( $options['oe_ic_after_label'] ) ) {
			$alabel = $options['oe_ic_after_label'];
			if ( strstr( $alabel, 'oedata_' ) ) {
				$alabel = base64_decode( str_replace( 'oedata_', '', $alabel ) );
				$shortcode = oxyextend_gsss( $this->El, $alabel );
				$alabel = do_shortcode( $shortcode );
			}
		}

		$uid = str_replace( '.', '', uniqid( 'baimg', true ) );

		//$data = ' data-id="' . $options['selector'] . '"';
		$data = ' data-id="' . $uid . '"';
		$data .= ' data-orientation="' . $options['oe_ic_orientation'] . '"';
		$data .= ' data-mhover="' . $options['oe_ic_mhover'] . '"';
		$data .= ' data-initial-offset="' . $options['oe_ic_initial_offset'] . '"';
		?>
		<div class="oe-image-comparison <?php echo $uid; ?> label-mobile-<?php echo $options['oe_ic_label']; ?>"<?php echo $data; ?>>
			<img src="<?php echo esc_url( $bimgurl ); ?>" alt="<?php echo $blabel; ?>" class="skip-lazy" />
			<img src="<?php echo esc_url( $aimgurl ); ?>" alt="<?php echo $alabel; ?>" class="skip-lazy" />
			<span class="oe_ic-bflbl"><?php echo $blabel; ?></span>
			<span class="oe_ic-aflbl"><?php echo $alabel; ?></span>
		</div>
		<?php
		ob_start();

		$this->oe_image_comparison_script( $options, $uid );

		$js = ob_get_clean();

		if ( isset( $_GET['oxygen_iframe'] ) || defined( 'OXY_ELEMENTS_API_AJAX' ) ) {
			$this->oe_ic_enqueue_scripts();
			$this->El->builderInlineJS( $js );
		} else {
			add_action( 'wp_footer', array( $this, 'oe_ic_enqueue_scripts' ) );

			$this->si_js_code[] = $js;
			$this->El->footerJS( join( '', $this->si_js_code ) );
		}
	}

	public function oe_image_comparison_script( $options, $uid ) {
		?>
		jQuery(document).ready(function($){
			var oe_icTimeout;
			$('.oe-image-comparison').each(function(){
				new oeImageComparison({
					selector: '.' + $(this).attr('data-id'), 
					before_label: $(this).find('.oe_ic-bflbl').text(), 
					after_label: $(this).find('.oe_ic-aflbl').text(),
					orientation: $(this).attr('data-orientation'),
					mhover: $(this).attr('data-mhover'),
					initial_offset: $(this).attr('data-initial-offset')
				});
			});
			jQuery(window).trigger('resize');

			if ( typeof oe_icTimeout != 'undefinied' )
				clearTimeout(oe_icTimeout);

			oe_icTimeout = setTimeout(function(){
				jQuery(window).trigger('resize');
			}, 900);
		});
		<?php
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

	public function oe_ic_load_scripts() {
		if ( isset( $_GET['ct_builder'] ) && $_GET['ct_builder'] ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'oe_ic_enqueue_scripts' ) );
		}
	}

	/*public function oe_ic_enqueue_scripts() {
		wp_enqueue_style('twentytwenty-style', OXY_EXTENDED_URL . 'assets/lib/twentytwenty/twentytwenty.css',array(),filemtime( OXY_EXTENDED_DIR . 'assets/lib/twentytwenty/twentytwenty.css' ),'all');
		wp_enqueue_script('oe-event-script', OXY_EXTENDED_URL . 'assets/lib/event-move/jquery.event.js',array(),filemtime( OXY_EXTENDED_DIR . 'assets/lib/event-move/jquery.event.js' ),true );
		wp_enqueue_script('oe-twentytwenty-script', OXY_EXTENDED_URL . 'assets/lib/twentytwenty/jquery.twentytwenty.js',array(),filemtime( OXY_EXTENDED_DIR . 'assets/lib/twentytwenty/jquery.twentytwenty.js' ),true);
		wp_enqueue_script('oe-ic-script', OXY_EXTENDED_URL . 'assets/js/oe_ic.js',array(),filemtime( OXY_EXTENDED_DIR . 'assets/js/oe_ic.js' ),true);
	}*/
	public function oe_ic_enqueue_scripts() {
		wp_enqueue_script( 'twentytwenty-js' );
		wp_enqueue_script( 'jquery-event-move' );
		wp_enqueue_script( 'oe-ic-js' );
	}
}

$oeimgcomparison = new OEImageComparison();
$oeimgcomparison->oe_ic_load_scripts();
