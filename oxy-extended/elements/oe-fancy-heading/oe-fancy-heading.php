<?php

namespace Oxygen\OxyExtended;

class OEFancyHeading extends \OxyExtendedEl {

	public $css_added = false;

	/**
	 * Retrieve fancy heading element name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'Fancy Heading', 'oxy-extended' );
	}

	/**
	 * Retrieve fancy heading element slug.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element slug.
	 */
	public function slug() {
		return 'oe_fancy_heading';
	}

	/**
	 * Element Subsection
	 *
	 * @return string
	 */
	public function oxyextend_button_place() {
		return 'general';
	}

	public function icon() {
		return OXY_EXTENDED_URL . 'assets/images/elements/' . basename( __FILE__, '.php' ) . '.svg';
	}

	public function tag() {
		return $this->headingTagChoices();
	}

	public function controls() {
		$heading_text = $this->addOptionControl(
			array(
				'type'  => 'textfield',
				'name'  => __( 'Heading', 'oxy-extended' ),
				'slug'  => 'oe_heading_text',
				'value' => __( 'Fancy Heading!', 'oxy-extended' ),
				'css'   => false,
			)
		);
		$heading_text->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$heading_text->rebuildElementOnChange();

		$this->addTagControl();

		$animation_type = $this->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Animation Type', 'oxy-extended' ),
				'slug'    => 'oe_animation_type',
				'value'   => array(
					'solid'    => __( 'Color', 'oxy-extended' ),
					'gradient' => __( 'Gradient', 'oxy-extended' ),
					'fade'     => __( 'Fade', 'oxy-extended' ),
					'rotate'   => __( 'Rotate', 'oxy-extended' ),
				),
				'default' => 'fade',
			)
		);

		$animation_type->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$animation_type->rebuildElementOnChange();

		$this->addOptionControl(
			array(
				'type'  => 'slider-measurebox',
				'name'  => __( 'Animation Speed', 'oxy-extended' ),
				'slug'  => 'oe_animation_speed',
				'value' => 2,
				'min'   => 0,
				'max'   => 50,
			)
		)->setUnits( 'sec', 'sec' )
		->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );

		$selector = '.oe-fancy-heading-title';

		$primary_color = $this->addStyleControl(
			array(
				'name'     => __( 'Primary Color', 'oxy-extended' ),
				'slug'     => 'oe_primary_color',
				'selector' => $selector,
				'property' => 'color',
				'value'    => '255dea',
			)
		);
		$primary_color->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$primary_color->rebuildElementOnChange();

		$secondary_color = $this->addStyleControl(
			array(
				'name'      => __( 'Secondary Color', 'oxy-extended' ),
				'slug'      => 'oe_secondary_color',
				'property'  => 'color',
				'value'     => '34d6e5',
				"condition" => 'oe_animation_type=gradient',
			)
		);
		$secondary_color->rebuildElementOnChange();

		$this->addStyleControls(
			array(
				array(
					'selector' => $selector,
					'property' => 'font-family',
					'css'      => false,
				),
				array(
					'selector' => $selector,
					'property' => 'font-weight',
				),
				array(
					'selector' => $selector,
					'property' => 'font-size',
					'value'    => 28,
				),
				array(
					'selector' => $selector,
					'property' => 'line-height',
				),
				array(
					'selector' => $selector,
					'property' => 'letter-spacing',
				),
				array(
					'selector' => $selector,
					'property' => 'text-decoration',
				),
				array(
					'selector' => $selector,
					'property' => 'text-transform',
				),
			)
		);
	}

	public function render( $options, $defaults, $content ) {
		echo '<p class="oe-fancy-heading-title">' . $options['oe_heading_text'] . '</p>';
	}

	public function customCSS( $original, $selector ) {
		error_log( print_r( $original, true) );
		$css = $default_css = '';

		if ( ! $this->css_added ) {
			$default_css = file_get_contents( __DIR__ . '/' . basename( __FILE__, '.php' ) . '.css' );
			$this->css_added = true;
		}

		$prefix = 'oxy-' . $this->slug();
		$animation_type = ! empty( $original[ $prefix . '_oe_animation_type' ] ) ? $original[ $prefix . '_oe_animation_type' ] : 'anm_fade';
		$animation_speed = ! empty( $original[ $prefix . '_oe_animation_speed' ] ) ? $original[ $prefix . '_oe_animation_speed' ] : 2;

		if ( 'fade' === $animation_type ) {
			$css = '-webkit-animation: oe-fancy-heading-fade ' . $animation_speed . 's infinite linear; animation: oe-fancy-heading-fade ' . $animation_speed . 's infinite linear;';
		}

		if ( 'solid' === $animation_type ) {
			$css = '-webkit-animation: oe-fancy-heading-hue ' . $animation_speed . 's infinite linear; animation: oe-fancy-heading-hue ' . $animation_speed . 's infinite linear;';
		}

		if ( 'rotate' === $animation_type ) {
			$css = '-webkit-animation: oe-fancy-heading-rotate ' . $animation_speed . 's infinite linear; animation: oe-fancy-heading-rotate ' . $animation_speed . 's infinite linear;';
		}

		if ( 'gradient' === $animation_type ) {
			$css = 'background-image: -webkit-linear-gradient(92deg, ' . $original[ $prefix . '_slug_oefancyheadingtitle_color' ] . ', ' . $original['oe_secondary_color'] . '); -webkit-background-clip: text;-webkit-text-fill-color: transparent;-webkit-animation: oe-fancy-heading-hue ' . $animation_speed . 's infinite linear; animation: oe-fancy-heading-hue ' . $animation_speed . 's infinite linear;';
		}

		if ( ! empty( $css ) ) {
			$css = $selector . ' .oe-fancy-heading-title{' . $css . '}';
		}

		return $default_css . $css;
	}
}

( new OEFancyHeading() )->removeApplyParamsButton();
