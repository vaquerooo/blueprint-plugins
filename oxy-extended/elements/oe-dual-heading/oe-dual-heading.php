<?php
namespace Oxygen\OxyExtended;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Dual Color Heading Element
 */
class OEDualHeading extends \OxyExtendedEl {

	/**
	 * Retrieve dual heading element name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'Dual Heading', 'oxy-extended' );
	}

	/**
	 * Retrieve dual heading element slug.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element slug.
	 */
	public function slug() {
		return 'oe_dual_heading';
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
	 * Retrieve dual heading element icon.
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
	 * @return $tags
	 */
	public function tag() {
		$tags = array(
			'default' => 'h3',
			'choices' => 'h1,h2,h3,h4,h5,h6,div,p',
		);

		return $tags;
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
		$this->addTagControl();

		$text1 = $this->addOptionControl(
			array(
				'type'  => 'textfield',
				'name'  => __( 'First Text', 'oxy-extended' ),
				'slug'  => 'oe_first_text',
				'value' => __( 'Our', 'oxy-extended' ),
			)
		);

		$text2 = $this->addOptionControl(
			array(
				'type'  => 'textfield',
				'name'  => __( 'Second Text', 'oxy-extended' ),
				'slug'  => 'oe_second_text',
				'value' => __( 'Services', 'oxy-extended' ),
			)
		);

		$link = $this->addCustomControl(
			'<div class="oxygen-file-input oxygen-option-default" ng-class="{\'oxygen-option-default\':iframeScope.isInherited(iframeScope.component.active.id, \'oxy-oe_dual_heading_link\')}">
				<input type="text" spellcheck="false" ng-model="iframeScope.component.options[iframeScope.component.active.id][\'model\'][\'oxy-oe_dual_heading_link\']" ng-model-options="{ debounce: 10 }" ng-change="iframeScope.setOption(iframeScope.component.active.id, iframeScope.component.active.name,\'oxy-oe_dual_heading_link\');iframeScope.checkResizeBoxOptions(\'oxy-oe_dual_heading_link\'); " class="ng-pristine ng-valid ng-touched" placeholder="http://">
				<div class="oxygen-set-link" data-linkproperty="url" data-linktarget="target" onclick="processOELink(\'oxy-oe_dual_heading_link\')">set</div>
				<div class="oxygen-dynamic-data-browse" ctdynamicdata data="iframeScope.dynamicShortcodesLinkMode" callback="iframeScope.oeDynamicDHUrl">data</div>
			</div>
			',
			'link',
		);
		$link->setParam( 'heading', __( 'Link', 'oxy-extended' ) );
		$link->rebuildElementOnChange();

		$this->addStyleControl(
			array(
				'name'         => __( 'Space between two texts', 'oxy-extended' ),
				'control_type' => 'slider-measurebox',
				'property'     => 'margin-left',
				'selector'     => '.oe-second-text',
				'default'      => '0',
			)
		);

		/* First Text Style Section */
		$first_text_style = $this->addControlSection( 'first_text_style', __( 'Style - First Text', 'oxy-extended' ), 'assets/icon.png', $this );
		$first_text_style->typographySection( __( 'Typography', 'oxy-extended' ), '.oe-first-text', $this );
		$first_text_bg_color = $first_text_style->addStyleControl(
			array(
				'name'     => __( 'Background Color', 'oxy-extended' ),
				'selector' => '.oe-first-text',
				'value'    => '',
				'property' => 'background-color',
			)
		);
		$first_text_bg_color->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );

		$first_text_style->addPreset(
			'padding',
			'oe_padding_front',
			__( 'Padding', 'oxy-extended' ),
			'.oe-first-text'
		)->whiteList();

		$first_text_style->borderSection( __( 'Border', 'oxy-extended' ), '.oe-first-text', $this );

		/* Second Text Style Section */
		$second_text_style = $this->addControlSection( 'second_text_style', __( 'Style - Second Text', 'oxy-extended' ), 'assets/icon.png', $this );
		$second_text_style->typographySection( __( 'Typography', 'oxy-extended' ), '.oe-second-text', $this );

		$second_text_bg_color = $second_text_style->addStyleControl(
			array(
				'name'     => __( 'Background Color', 'oxy-extended' ),
				'selector' => '.oe-second-text',
				'value'    => '',
				'property' => 'background-color',
			)
		);
		$second_text_bg_color->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );

		$second_text_style->addPreset(
			'padding',
			'oe_padding_front',
			__( 'Padding', 'oxy-extended' ),
			'.oe-second-text'
		)->whiteList();

		$second_text_style->borderSection( __( 'Border', 'oxy-extended' ), '.oe-second-text', $this );
	}

	/**
	 * Render dual heading element output on the frontend.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render( $options, $defaults, $content ) {
		$link = isset( $options['link'] ) ? $options['link'] : '';

		if ( strstr( $link, 'oedata_' ) ) {
			$link = base64_decode( str_replace( 'oedata_', '', $link ) );
			$shortcode = oxyextend_gsss( $this->El, $link );
			$link = do_shortcode( $link );
		}

		$html = '<span class="oe-first-text">' . $options['oe_first_text'] . '</span> <span class="oe-second-text">' . $options['oe_second_text'] . '</span>';

		if ( $link ) {
			$html = '<a href="' . esc_url( $link ) . '" class="oe-dual-heading-link">' . $html . '</a>';
		}

		echo $html;
	}
}

new OEDualHeading();
