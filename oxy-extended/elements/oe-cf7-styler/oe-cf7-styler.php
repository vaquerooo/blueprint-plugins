<?php
namespace Oxygen\OxyExtended;

use OxyExtended\Classes\OE_Helper;

/**
 * Contact Form 7 Styler
 */
class OECF7Styler extends \OxyExtendedEl {

	/**
	 * Retrieve Contact Form 7 element name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'Contact Form 7 Styler', 'oxy-extended' );
	}

	/**
	 * Retrieve Contact Form 7 element slug.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element slug.
	 */
	public function slug() {
		return 'oe_cf7_styler';
	}

	/**
	 * Element Subsection
	 *
	 * @return string
	 */
	public function oxyextend_button_place() {
		return 'forms';
	}

	/**
	 * Retrieve Contact Form 7 element icon.
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
		$select_form = $this->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Select Form', 'oxy-extended' ),
				'slug'    => 'oe_select_form',
				'value'   => OE_Helper::get_contact_forms( 'Contact_Form_7' ),
				'default' => '-1',
				'css'     => false,
			)
		);
		$select_form->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$select_form->rebuildElementOnChange();

		$form_title = $this->addOptionControl(
			array(
				'type'  => 'textfield',
				'name'  => __( 'Heading', 'oxy-extended' ),
				'slug'  => 'oe_cf7_form_title',
				'value' => __( 'Form Title', 'oxy-extended' ),
				'css'   => false,
			)
		);
		$form_title->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$form_title->rebuildElementOnChange();

		/**
		 * Labels Section
		 * -------------------------------------------------
		 */
		$labels_selector = '.oe-cf7 label';

		$this->typographySection( __( 'Labels', 'oxy-extended' ), $labels_selector, $this );

		/**
		 * Input Fields Section
		 * -------------------------------------------------
		 */
		$input_fields_section = $this->addControlSection( 'oe_cf7_input_fields', __( 'Input & Textarea', 'oxy-extended' ), 'assets/icon.png', $this );
		$field_selector       = '.oe-cf7 .wpcf7-form-control:not(.wpcf7-submit)';
		$input_selector       = '.oe-cf7 input.wpcf7-form-control:not(.wpcf7-submit)';
		$textarea_selector    = '.oe-cf7 .wpcf7-form-control.wpcf7-textarea';

		$input_fields_section->addStyleControls(
			array(
				array(
					'name'         => __( 'Background Color', 'oxy-extended' ),
					'selector'     => $field_selector,
					'property'     => 'background-color',
					'control_type' => 'colorpicker',
				),
				array(
					'name'         => __( 'Focus Background Color', 'oxy-extended' ),
					'selector'     => $field_selector . ':focus',
					'property'     => 'background-color',
					'control_type' => 'colorpicker',
				),
				array(
					'name'     => __( 'Input Width', 'oxy-extended' ),
					'selector' => $input_selector,
					'property' => 'width',
				),
				array(
					'name'     => __( 'Textarea Width', 'oxy-extended' ),
					'selector' => $textarea_selector,
					'property' => 'width',
				),
				array(
					'name'     => __( 'Textarea Height', 'oxy-extended' ),
					'selector' => $textarea_selector,
					'property' => 'width',
				),
			)
		);

		$input_fields_padding_section = $input_fields_section->addControlSection( 'oe_cf7_input_fields_padding_section', __( 'Padding', 'oxy-extended' ), 'assets/icon.png', $this );

		$input_fields_padding_section->addPreset(
			'padding',
			'oe_cf7_fields_padding',
			__( 'Padding', 'oxy-extended' ),
			$field_selector
		)->whiteList();

		$input_fields_section->typographySection( __( 'Typography', 'oxy-extended' ), $field_selector, $this );

		$input_fields_section->borderSection( __( 'Border', 'oxy-extended' ), $field_selector, $this );

		$input_fields_section->boxShadowSection( __( 'Box Shadow', 'oxy-extended' ), $field_selector, $this );

		/**
		 * Submit Button Section
		 * -------------------------------------------------
		 */
		$submit_button_section        = $this->addControlSection( 'oe_cf7_submit_button_section', __( 'Submit Button', 'oxy-extended' ), 'assets/icon.png', $this );
		$submit_button_selector       = '.oe-cf7 .wpcf7-submit';
		$submit_button_selector_hover = '.oe-cf7 .wpcf7-submit:hover';

		$submit_button_section->addStyleControls(
			array(
				array(
					'name'         => __( 'Text Color', 'oxy-extended' ),
					'selector'     => $submit_button_selector,
					'property'     => 'color',
					'control_type' => 'colorpicker',
				),
				array(
					'name'         => __( 'Text Hover Color', 'oxy-extended' ),
					'selector'     => $submit_button_selector_hover,
					'property'     => 'color',
					'control_type' => 'colorpicker',
				),
				array(
					'name'         => __( 'Background Color', 'oxy-extended' ),
					'selector'     => $submit_button_selector,
					'property'     => 'background-color',
					'control_type' => 'colorpicker',
				),
				array(
					'name'         => __( 'Background Hover Color', 'oxy-extended' ),
					'selector'     => $submit_button_selector_hover,
					'property'     => 'background-color',
					'control_type' => 'colorpicker',
				),
				array(
					'name'     => __( 'Width', 'oxy-extended' ),
					'selector' => $submit_button_selector,
					'property' => 'width',
				),
			)
		);

		$submit_button_padding_section = $submit_button_section->addControlSection( 'oe_cf7_submit_button_padding_section', __( 'Padding', 'oxy-extended' ), 'assets/icon.png', $this );

		$submit_button_padding_section->addPreset(
			'padding',
			'oe_cf7_submit_button_padding',
			__( 'Padding', 'oxy-extended' ),
			$submit_button_selector
		)->whiteList();

		$submit_button_section->borderSection( __( 'Border', 'oxy-extended' ), $submit_button_selector, $this );

		$submit_button_section->boxShadowSection( __( 'Box Shadow', 'oxy-extended' ), $submit_button_selector, $this );

		/**
		 * Spacing Section
		 * -------------------------------------------------
		 */
		$spacing_section = $this->addControlSection( 'oe_cf7_fields_spacing', __( 'Spacing', 'oxy-extended' ), 'assets/icon.png', $this );
		$fields_selector = '.oe-cf7 .wpcf7-form-control';

		$spacing_section->addStyleControls(
			array(
				array(
					'name'     => __( 'Between Label & Input', 'oxy-extended' ),
					'selector' => $labels_selector,
					'property' => 'margin-bottom',
				),
				array(
					'name'     => __( 'Between Fields', 'oxy-extended' ),
					'selector' => $fields_selector,
					'property' => 'margin-bottom',
				),
			)
		);
	}

	/**
	 * Render Contact Form 7 element output on the frontend.
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
		if ( '-1' === $options['oe_select_form'] ) {
			echo __( 'No Contact Form Selected!', 'oxy-extended' );
			return;
		}
		?>
		<div class = "oe-cf7-container">
			<div class = "oe-contact-form oe-cf7">
				<?php
				if ( $options['oe_select_form'] ) {
					echo do_shortcode( '[contact-form-7 id=' . $options['oe_select_form'] . ']' );
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Enable Presets
	 *
	 * @return bool
	 */
	public function enablePresets() {
		return true;
	}
}

( new OECF7Styler() )->removeApplyParamsButton();
