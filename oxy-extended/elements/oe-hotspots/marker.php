<?php
namespace Oxygen\OxyExtended;

use OxyExtended\Classes\OE_Helper;

class OEHotspotMarker extends \OxyExtendedEl {
	public $js_added = false;
	public $css_added = false;

	public function name() {
		return __( 'Add Marker', 'oxy-extended' );
	}

	public function button_place() {
		return 'oehsmarker::comp';
	}

	public function init() {
		$this->El->useAJAXControls();
		$this->enableNesting();
		add_action( 'ct_toolbar_component_settings', function() {
			?>
			<label class="oxygen-control-label oxy-add-marker-elements-label"
				ng-if="isActiveName('oxy-add-marker')&&!hasOpenTabs('oxy-add-marker')" style="text-align: center; margin-top: 15px;">
				<?php _e( 'Available Elements', 'oxy-extended' ); ?>
			</label>
			<div class="oxygen-control-row oxy-add-marker-elements"
				ng-if="isActiveName('oxy-add-marker')&&!hasOpenTabs('oxy-add-marker')">
				<?php do_action( 'oxygen_add_plus_oehsmarker_comp' ); ?>
			</div>
			<?php
		}, 32 );

		if ( isset( $_GET['oxygen_iframe'] ) ) {
			add_action( 'wp_footer', array( $this, 'oe_hotspot_enqueue_scripts' ) );
		}
	}

	public function icon() {
		return OXY_EXTENDED_URL . 'assets/images/elements/oe-marker.svg';
	}

	public function controls() {
		$this->marker_type_controls();
		$this->marker_style_controls();
		$this->tooltip_controls();
	}

	public function marker_type_controls() {
		$marker_type_section = $this->addControlSection( 'marker_type_section', __( 'Choose Marker', 'oxy-extended' ), 'assets/icon.png', $this );

		$marker_type_section->addOptionControl([
			'type'    => 'radio',
			'name'    => __( 'Marker Type', 'oxy-extended' ),
			'slug'    => 'oe_marker_type',
			'value'   => [
				'icon'  => __( 'Icon', 'oxy-extended' ),
				'image' => __( 'Image', 'oxy-extended' ),
				'text'  => __( 'Text', 'oxy-extended' ),
			],
			'default' => 'icon',
		]);

		$text = $marker_type_section->addOptionControl(
			array(
				'type' => 'textfield',
				'name' => __( 'Text', 'oxy-extended' ),
				'slug' => 'marker_text',
			)
		);

		$text->setParam( 'description', __( 'Click on Apply Params button to see the text on editor.', 'oxy-extended' ) );
		$text->setParam( 'ng_show', "iframeScope.getOption('oxy-add-marker_oe_marker_type')=='text'" );

		$marker_img = $marker_type_section->addControl( 'mediaurl', 'marker_img', __( 'Upload Image', 'oxy-extended' ) );
		$marker_img->setParam( 'description', 'Click on Apply Params button to see the image on editor.' );
		$marker_img->setParam( 'ng_show', "iframeScope.getOption('oxy-add-marker_oe_marker_type')=='image'" );

		$image_size = $marker_type_section->addOptionControl(
			array(
				'type'      => 'dropdown',
				'name'      => __( 'Image Size', 'oxy-extended' ),
				'slug'      => 'image_size',
				'value'     => OE_Helper::get_image_sizes(),
				'default'   => 'medium',
				'css'       => false,
				'condition' => 'oe_marker_type=image',
			)
		);
		$image_size->rebuildElementOnChange();

		$imgw = $marker_type_section->addStyleControl(
			array(
				'control_type' => 'measurebox',
				'slug'         => 'marker_img_width',
				'name'         => __( 'Width', 'oxy-extended' ),
				'selector'     => '.marker-image',
				'property'     => 'width',
				'condition'    => 'oe_marker_type=image',
			)
		);
		$imgw->setUnits( 'px', 'px' );
		$imgw->setParam( 'hide_wrapper_end', true );

		$imgh = $marker_type_section->addStyleControl(
			array(
				'control_type'  => 'measurebox',
				'slug'          => 'marker_img_height',
				'name'          => __( 'Height', 'oxy-extended' ),
				'selector'      => '.marker-image',
				'property'      => 'height',
				'condition'     => 'oe_marker_type=image',
			)
		);
		$imgh->setUnits( 'px', 'px' );
		$imgh->setParam( 'hide_wrapper_start', true );

		$icon = $marker_type_section->addOptionControl(
			array(
				'type'      => 'icon_finder',
				'name'      => __( 'Icon', 'oxy-extended' ),
				'slug'      => 'marker_icon',
				'value'     => 'FontAwesomeicon-map-marker',
				'condition' => 'oe_marker_type=icon',
			)
		);
		$icon->setParam( 'description', __( 'Click on Apply Params button to see the icon on editor.', 'oxy-extended' ) );

		$position = $marker_type_section->addControlSection( 'marker_position', __( 'Position', 'oxy-extended' ), 'assets/icon.png', $this );

		$position->addStyleControl([
			'selector'      => ' ',
			'property'      => 'top',
			'control_type'  => 'slider-measurebox',
			'unit'          => '%',
			'value'         => 2,
			'default'       => 2,
		]);

		$position->addStyleControl([
			'selector'      => ' ',
			'property'      => 'left',
			'control_type'  => 'slider-measurebox',
			'value'         => 2,
			'default'       => 2,
			'unit'          => '%',
		]);

		$position->addStyleControl([
			'selector'  => ' ',
			'property'  => 'z-index',
		]);

		$icon_style = $marker_type_section->addControlSection( 'marker_icon_style', __( 'Icon Style', 'oxy-extended' ), 'assets/icon.png', $this );

		$icon_style->addStyleControls([
			[
				'name'         => __( 'Size', 'oxy-extended' ),
				'selector'     => '.oe-hs-marker-inner svg',
				'property'     => 'width|height',
				'control_type' => 'slider-measurebox',
				'unit'         => 'px',
			],
			[
				'selector' => '.oe-hs-marker-inner svg',
				'property' => 'color',
			],
			[
				'name'     => __( 'Hover Color', 'oxy-extended' ),
				'selector' => ':hover svg',
				'property' => 'color',
			],
		]);

		$typography = $marker_type_section->typographySection( __( 'Text Typography', 'oxy-extended' ), '.oe-hs-marker-inner', $this );
	}

	public function marker_style_controls() {
		$marker_style_section = $this->addControlSection( 'marker_style_section', __( 'Marker Style', 'oxy-extended' ), 'assets/icon.png', $this );

		//$style = $marker_style_section->addControlSection( 'marker_btn_size', __( 'Marker Style', 'oxy-extended' ), 'assets/icon.png', $this );

		$marker_style_section->addStyleControls([
			array(
				'selector'      => '.oe-hs-marker-inner',
				'property'      => 'width',
				'control_type'  => 'slider-measurebox',
				'unit'          => 'px',
			),
			array(
				'selector'      => '.oe-hs-marker-inner',
				'property'      => 'height',
				'control_type'  => 'slider-measurebox',
				'unit'          => 'px',
			),
			array(
				'selector'  => '.oe-hs-marker-inner, .marker-glow-effect:before',
				'property'  => 'background-color',
			),
			array(
				'name'      => 'Hover Background Color',
				'selector'  => '.oe-hs-marker-inner:hover, .marker-glow-effect:hover:before',
				'property'  => 'background-color',
			),
		]);

		$glow_effect = $marker_style_section->addControl( 'buttons-list', 'marker_glow_effect', __( 'Glow Effect', 'oxy-extended' ) );
		$glow_effect->setValue( [ 'Yes', 'No' ] );
		$glow_effect->setValueCSS( [ 'No' => '.marker-glow-effect:before{content: normal;}' ] );
		$glow_effect->setDefaultValue( 'Yes' );
		$glow_effect->whiteList();

		$marker_style_section->addStyleControl([
			'control_type'      => 'slider-measurebox',
			'name'              => __( 'Animation Duration', 'oxy-extended' ),
			'selector'          => '.marker-glow-effect:before',
			'property'          => 'animation-duration',
		])->setRange( 0, 10, 0.1 )->setUnits( 's', 'sec' )->setDefaultValue( 2 );

		$disable_glow_effect = $marker_style_section->addControl( 'buttons-list', 'marker_glow_effect_disable', __( 'Disable Glow Effect on Hover', 'oxy-extended' ) );
		$disable_glow_effect->setValue( [ 'Yes', 'No' ] );
		$disable_glow_effect->setValueCSS( [ 'Yes' => '.marker-glow-effect:hover:before{content: normal!important;}' ] );
		$disable_glow_effect->setDefaultValue( 'No' );
		$disable_glow_effect->whiteList();

		$padding = $marker_style_section->addControlSection( 'marker_padding', __( 'Padding', 'oxy-extended' ), 'assets/icon.png', $this );

		$padding->addStyleControl(
			array(
				'name'          => __( 'Top', 'oxy-extended' ),
				'selector'      => '.oe-hs-marker-inner',
				'property'      => 'padding-top',
				'control_type'  => 'measurebox',
			)
		)->setUnits( 'px', 'px' )->setRange( 0, 100, 5 )->setParam( 'hide_wrapper_end', true );

		$padding->addStyleControl(
			array(
				'name'          => __( 'Right', 'oxy-extended' ),
				'selector'      => '.oe-hs-marker-inner',
				'property'      => 'padding-right',
				'control_type'  => 'measurebox',
			)
		)->setUnits( 'px', 'px' )->setRange( 0, 100, 5 )->setParam( 'hide_wrapper_start', true );

		$padding->addStyleControl(
			array(
				'name'          => __( 'Bottom', 'oxy-extended' ),
				'selector'      => '.oe-hs-marker-inner',
				'property'      => 'padding-bottom',
				'control_type'  => 'measurebox',
			)
		)->setUnits( 'px', 'px' )->setRange( 0, 100, 5 )->setParam( 'hide_wrapper_end', true );

		$padding->addStyleControl(
			array(
				'name'          => __( 'Left', 'oxy-extended' ),
				'selector'      => '.oe-hs-marker-inner',
				'property'      => 'padding-left',
				'control_type'  => 'measurebox',
			)
		)->setUnits( 'px', 'px' )->setRange( 0, 100, 5 )->setParam( 'hide_wrapper_start', true );

		$marker_border = $marker_style_section->borderSection( __( 'Border', 'oxy-extended' ), '.oe-hs-marker-inner, .marker-glow-effect:before', $this );

		$marker_border->addStyleControl([
			'name'          => 'Border Hover Color',
			'selector'      => '.oe-hs-marker-inner:hover, .marker-glow-effect:hover:before',
			'property'      => 'border-color',
			'default'       => '',
		]);

		$marker_style_section->boxShadowSection( __( 'Box Shadow', 'oxy-extended' ), '.oe-hs-marker-inner', $this );
		$marker_style_section->boxShadowSection( __( 'Hover Box Shadow', 'oxy-extended' ), '.oe-hs-marker-inner:hover', $this );
	}

	public function tooltip_controls() {
		$tooltip_settings_section = $this->addControlSection( 'tooltip_settings', __( 'Tooltip Settings', 'oxy-extended' ), 'assets/icon.png', $this );

		$preview = $tooltip_settings_section->addControl( 'buttons-list', 'tooltip_preview', __( 'Edit Tooltip Content in Editor', 'oxy-extended' ) );
		$preview->setValue( [ 'Yes', 'No' ] );
		$preview->setValueCSS( [ 'No' => '.tooltip-content-toggle{display: none;}' ] );
		$preview->setDefaultValue( 'Yes' );
		$preview->whiteList();

		$tooltip_settings_section->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Trigger', 'oxy-extended' ),
			'slug'      => 'popup_trigger',
			'value'     => [
				'hover' => __( 'Hover', 'oxy-extended' ),
				'click' => __( 'Click', 'oxy-extended' ),
			],
			'default'   => 'hover',
		]);

		$tooltip_settings_section->addOptionControl([
			'type'      => 'radio',
			'name'      => __( 'Disable Arrow', 'oxy-extended' ),
			'slug'      => 'popup_arrow',
			'value'     => [
				'yes' => __( 'Yes', 'oxy-extended' ),
				'no'  => __( 'No', 'oxy-extended' ),
			],
			'default'   => 'no',
		]);

		$tooltip_settings_section->addStyleControl([
			'name'          => 'Arrow Color',
			'selector'      => '.tippy-arrow',
			'property'      => 'color',
			'default'       => '#333333',
			'condition'     => 'popup_arrow=no',
		]);

		$tooltip_settings_section->addOptionControl([
			'type'      => 'dropdown',
			'name'      => __( 'Placement', 'oxy-extended' ),
			'slug'      => 'popup_placement',
			'value'     => [
				'top'           => __( 'Top', 'oxy-extended' ),
				'top-start'     => __( 'Top Start', 'oxy-extended' ),
				'top-end'       => __( 'Top End', 'oxy-extended' ),
				'right'         => __( 'Right', 'oxy-extended' ),
				'right-start'   => __( 'Right Start', 'oxy-extended' ),
				'right-end'     => __( 'Right End', 'oxy-extended' ),
				'bottom'        => __( 'Bottom', 'oxy-extended' ),
				'bottom-start'  => __( 'Bottom Start', 'oxy-extended' ),
				'bottom-end'    => __( 'Bottom End', 'oxy-extended' ),
				'left'          => __( 'Left', 'oxy-extended' ),
				'left-start'    => __( 'Left Start', 'oxy-extended' ),
				'left-end'      => __( 'Left End', 'oxy-extended' ),
				'auto'          => __( 'Auto', 'oxy-extended' ),
				'auto-start'    => __( 'Auto Start', 'oxy-extended' ),
				'auto-end'      => __( 'Auto End', 'oxy-extended' ),
			],
			'default'   => 'auto',
		]);

		$tooltip_size = $tooltip_settings_section->addControlSection( 'popup_size', __( 'Width & Color', 'oxy-extended' ), 'assets/icon.png', $this );
		$tooltip_size->addStyleControl([
			'selector'      => '.oe-tooltip-content-wrap, .tippy-box',
			'property'      => 'width',
			'slug'          => 'tippy_box_width',
			'control_type'  => 'slider-measurebox',
		])->setRange( 0, 600, 10 )->setUnits( 'px', 'px,%,vw' )->setDefaultValue( 250 );

		$tooltip_size->addStyleControl([
			'selector'      => '.oe-tooltip-content-wrap, .tippy-box',
			'property'      => 'background-color',
			'default'       => '#333333',
		]);

		$tooltip_spacing = $tooltip_settings_section->addControlSection( 'popup_spacing', __( 'Spacing', 'oxy-extended' ), 'assets/icon.png', $this );
		$tooltip_spacing->addPreset(
			'padding',
			'ttip_padding',
			__( 'Padding', 'oxy-extended' ),
			'.oe-tooltip-content-wrap, .tippy-box'
		)->whiteList();

		$tooltip_settings_section->borderSection( __( 'Border', 'oxy-extended' ), '.oe-tooltip-content-wrap, .tippy-box', $this );
		$tooltip_settings_section->boxShadowSection( __( 'Box Shadow', 'oxy-extended' ), '.oe-tooltip-content-wrap, .tippy-box', $this );

		$layout = $tooltip_settings_section->addControlSection( 'popup_child', __( 'Content Layout', 'oxy-extended' ), 'assets/icon.png', $this );
		$layout->addStyleControl([
			'control_type'      => 'radio',
			'selector'          => '.oe-tooltip-content',
			'property'          => 'display',
			'value'             => [
				'flex' => 'flex',
				'inline-flex' => 'inline-flex',
			],
			'default'           => 'flex',
		]);
		$layout->flex( '.oe-tooltip-content', $this );

		$effect = $tooltip_settings_section->addControlSection( 'popup_effect', __( 'Animation', 'oxy-extended' ), 'assets/icon.png', $this );
		$effect->addOptionControl([
			'type'    => 'radio',
			'name'    => __( 'Animation', 'oxy-extended' ),
			'slug'    => 'popup_animation',
			'value'   => [
				'fade'         => __( 'Fade', 'oxy-extended' ),
				'shift-away'   => __( 'Shift Away', 'oxy-extended' ),
				'shift-toward' => __( 'Shift Toward', 'oxy-extended' ),
				'perspective'  => __( 'Perspective', 'oxy-extended' ),
			],
			'default' => 'fade',
		]);

		$effect->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Offset', 'oxy-extended' ),
			'slug'      => 'popup_offset',
		])->setRange( 0, 100, 5 )->setUnits( ' ', ' ' )->setDefaultValue( 0 );

		$effect->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Distance', 'oxy-extended' ),
			'slug'      => 'popup_distance',
		])->setRange( 0, 100, 5 )->setUnits( ' ', ' ' )->setDefaultValue( 10 );

		$effect->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Delay Start', 'oxy-extended' ),
			'slug'      => 'popup_delay_start',
		])->setRange( 0, 1000, 10 )->setUnits( 'ms', 'ms' )->setDefaultValue( 0 );

		$effect->addOptionControl([
			'type'      => 'slider-measurebox',
			'name'      => __( 'Delay End', 'oxy-extended' ),
			'slug'      => 'popup_delay_end',
		])->setRange( 0, 1000, 10 )->setUnits( 'ms', 'ms' )->setDefaultValue( 0 );

		$effect->addOptionControl([
			'type'      => 'textfield',
			'name'      => __( 'Z-Index', 'oxy-extended' ),
			'slug'      => 'popup_zindex',
			'default'   => 9999,
		]);
	}

	/**
	 * Get thumbnail size list
	 */
	public function image_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = $img_sizes = array();

		foreach ( get_intermediate_image_sizes() as $s ) {
			$sizes[ $s ] = array( 0, 0 );
			if ( in_array( $s, array( 'thumbnail', 'medium', 'large' ) ) ) {
				$sizes[ $s ][0] = get_option( $s . '_size_w' );
				$sizes[ $s ][1] = get_option( $s . '_size_h' );
			} else {
				if ( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) ) {
					$sizes[ $s ] = array( $_wp_additional_image_sizes[ $s ]['width'], $_wp_additional_image_sizes[ $s ]['height'] );
				}
			}
		}

		foreach ( $sizes as $size => $atts ) {
			$size_title = ucwords( str_replace( '-', ' ', $size ) );
			$img_sizes[ $size ] = $size_title . ' (' . implode( 'x', $atts ) . ')';
		}
		$img_sizes['full'] = __( 'Full', 'oxy-extended' );

		return $img_sizes;
	}

	public function render( $options, $default, $content ) {
		$data_attr = ' data-selector="' . $options['selector'] . '" data-tpwidth="' . $options['tippy_box_width'] . '"';
		$data_attr .= ' data-placement="' . $options['popup_placement'] . '"';
		$data_attr .= ' data-animation="' . $options['popup_animation'] . '"';
		$data_attr .= ' data-hptrigger="' . ( ( $options['popup_trigger'] == 'hover' ) ? 'mouseenter focus' : 'click' ) . '"';
		$data_attr .= ' data-arrow="' . $options['popup_arrow'] . '"';
		$data_attr .= ' data-zindex="' . ( isset( $options['popup_zindex'] ) ? $options['popup_zindex'] : 9999 ) . '"';
		$data_attr .= ' data-offset="' . ( isset( $options['popup_offset'] ) ? $options['popup_offset'] : 0 ) . '"';
		$data_attr .= ' data-distance="' . ( isset( $options['popup_distance'] ) ? $options['popup_distance'] : 10 ) . '"';
		$data_attr .= ' data-delaystart="' . ( isset( $options['popup_delay_start'] ) ? $options['popup_delay_start'] : 0 ) . '"';
		$data_attr .= ' data-delayend="' . ( isset( $options['popup_delay_end'] ) ? $options['popup_delay_end'] : 0 ) . '"';

		echo '<div class="oe-hs-marker-inner tippy-selector' . $options['selector'] . ' marker-glow-effect marker-type-' . $options['oe_marker_type'] . '"' . $data_attr . '>';

		if ( $options['oe_marker_type'] == 'icon' ) {
			global $oxygen_svg_icons_to_load;

			$oxygen_svg_icons_to_load[] = $options['marker_icon'];

			echo '<svg id="' . $options['selector'] . '-oe-marker-icon" class="oe-marker-icon"><use xlink:href="#' . $options['marker_icon'] . '"></use></svg>';
		}

		if ( $options['oe_marker_type'] == 'text' ) {
			echo '<span class="marker-text">' . $options['marker_text'] . '</span>';
		}

		if ( $options['oe_marker_type'] == 'image' ) {
			$width = isset( $options['marker_img_width'] ) ? ' width="' . $options['marker_img_width'] . '"' : '';
			$height = isset( $options['marker_img_height'] ) ? ' height="' . $options['marker_img_height'] . '"' : '';
			$image = $options['marker_img'];
			$image_id = attachment_url_to_postid($image);
			$image_size = $options['image_size'];
			$image_src = wp_get_attachment_image_src($image_id, $image_size);
			echo '<img src="' . esc_url( $image_src[0] ) . '"' . $width . $height . ' class="marker-image" />';
		}

		echo '</div>';

		$class = '';
		if ( isset( $_GET['oxygen_iframe'] ) || defined( 'OXY_ELEMENTS_API_AJAX' ) ) {
			$class = ' tooltip-content-toggle tippy-builder-content' . $options['selector'];
		}

		if ( $content ) {
			echo '<div class="oe-tooltip-content-wrap' . $class . '"><div class="oe-tooltip-content oxy-inner-content">' . do_shortcode( $content ) . '</div></div>';

			if ( ! empty( $class ) ) {
				$this->El->builderInlineJS('
					jQuery(document).ready(function($){
						if( $(".tippy-builder-content' . $options['selector'] . '").css("display") == "none") {
							setTimeout(function(){
								tippy( ".tippy-selector' . $options['selector'] . '", {
									content: $(".tippy-builder-content' . $options['selector'] . '").html(),
									trigger: "' . ( ( $options['popup_trigger'] == 'hover' ) ? 'mouseenter focus' : 'click' ) . '",
									maxWidth: "none",
									allowHTML: true,
									interactive: true,
									animation: "' . $options['popup_animation'] . '",
									delay: [' . intval( $options['popup_delay_start'] ) . ',' . intval( $options['popup_delay_end'] ) . '],
									offset: [' . intval( $options['popup_offset'] ) . ',' . intval( $options['popup_distance'] ) . '],
									placement: "' . $options['popup_placement'] . '",
									zIndex: ' . intval( $options['popup_zindex'] ) . ',
									arrow: ' . ( $options['popup_arrow'] == 'no' ? 'true' : 'false' ) . '
								});
							}, 100 );
						}
					});
				');
			}
		}

		if ( ! $this->js_added && ! isset( $_GET['oxygen_iframe'] ) ) {
			add_action( 'wp_footer', array( $this, 'oe_hotspot_enqueue_scripts' ) );
			$this->js_added = true;
		}
	}

	public function customCSS( $original, $selector ) {
		$css = '';
		if ( ! $this->css_added ) {
			$css = file_get_contents( __DIR__ . '/' . basename( __FILE__, '.php' ) . '.css' );

			$this->css_added = true;
		}

		return $css;
	}

	public function oe_hotspot_enqueue_scripts() {
		wp_enqueue_script( 'oe-popper-script' );
		wp_enqueue_script( 'oe-tippy-script' );

		if ( ! isset( $_GET['oxygen_iframe'] ) && ! defined( 'OXY_ELEMENTS_API_AJAX' ) ) : ?>
			<script type="text/javascript">
				jQuery(window).ready(function($){
					$(".oe-hs-marker-inner").each(function(e){
						tippy( ".tippy-selector" + $(this).attr("data-selector"), {
							content: $(this).closest(".oxy-add-marker").find(".oe-tooltip-content-wrap").html(),
							trigger: $(this).attr("data-hptrigger"),
							maxWidth: 'none', //$(this).attr('data-tpwidth')
							allowHTML: true,
							interactive: true,
							animation: $(this).attr("data-animation"),
							delay: [parseInt( $(this).attr("data-delaystart") ), parseInt( $(this).attr("data-delayend") )],
							offset: [parseInt( $(this).attr("data-offset") ), parseInt( $(this).attr("data-distance") )],
							placement: $(this).attr("data-placement"),
							zIndex: parseInt( $(this).attr("data-zindex") ),
							arrow: ( $(this).attr("data-arrow") == 'no' ? true : false )
						});
					});
				});
			</script>
			<?php
		endif;
	}
}

new OEHotspotMarker();
