<?php
namespace Oxygen\OxyExtended;

use OxyExtended\Classes\OE_Helper;

/**
 * Formidable Forms Styler
 */
class OEFormidableFormsStyler extends \OxyExtendedEl {

	/**
	 * Retrieve Formidable Forms element name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'Formidable Forms Styler', 'oxy-extended' );
	}

	/**
	 * Retrieve Formidable Forms element slug.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element slug.
	 */
	public function slug() {
		return 'oe_formidable_forms_styler';
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
	 * Retrieve Formidable Forms element icon.
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
				'value'   => OE_Helper::get_contact_forms( 'Formidable_Forms' ),
				'default' => '-1',
				'css'     => false,
			)
		);
		$select_form->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$select_form->rebuildElementOnChange();

		$frmform_title = $this->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Show Title', 'oxy-extended' ),
				'slug'      => 'frmform_title',
				'value'     => array(
					'yes'   => __( 'Yes', 'oxy-extended' ),
					'no'    => __( 'No', 'oxy-extended' ),
				),
				'default'   => 'yes',
			)
		);
		$frmform_title->rebuildElementOnChange();

		$frmform_desc = $this->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Show Description', 'oxy-extended' ),
				'slug'      => 'frmform_desc',
				'value'     => array(
					'yes'   => __( 'Yes', 'oxy-extended' ),
					'no'    => __( 'No', 'oxy-extended' ),
				),
				'default'   => 'yes',
			)
		);
		$frmform_desc->rebuildElementOnChange();

		$frmform_ajax = $this->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Enable Ajax', 'oxy-extended' ),
				'slug'      => 'frmform_ajax',
				'value'     => array(
					'yes'   => __( 'Yes', 'oxy-extended' ),
					'no'    => __( 'No', 'oxy-extended' ),
				),
				'default'   => 'yes',
			)
		);
		$frmform_ajax->rebuildElementOnChange();

		/**
		 * Form Wrapper
		 * -------------------------------------------------
		 */
		$form_wrapper_section = $this->addControlSection( 'form_wrap_section', __( 'Form Wrapper', 'oxy-extended' ), 'assets/icon.png', $this );
		$selector = '.frm_forms';

		$form_wrapper_section->addStyleControl(
			array(
				'selector'  => $selector,
				'property'  => 'background-color',
			)
		);

		$form_spacing = $form_wrapper_section->addControlSection( 'form_spacing', __( 'Spacing', 'oxy-extended' ), 'assets/icon.png', $this );

		$form_spacing->addPreset(
			'padding',
			'form_padding',
			__( 'Padding', 'oxy-extended' ),
			$selector
		)->whiteList();

		$form_spacing->addPreset(
			'margin',
			'form_margin',
			__( 'Margin', 'oxy-extended' ),
			$selector
		)->whiteList();

		$form_wrapper_section->borderSection( __( 'Border', 'oxy-extended' ), $selector, $this );
		$form_wrapper_section->boxShadowSection( __( 'Box Shadow', 'oxy-extended' ), $selector, $this );

		$form_title = $this->typographySection( __( 'Form Title', 'oxy-extended' ), '.frm_form_title', $this );
		$form_title_spacing = $form_title->addControlSection( 'form_title_spacing', __( 'Spacing', 'oxy-extended' ), 'assets/icon.png', $this );

		$form_title_spacing->addPreset(
			'padding',
			'form_title_padding',
			__( 'Padding', 'oxy-extended' ),
			'.frm_form_title'
		)->whiteList();

		$form_title_spacing->addPreset(
			'margin',
			'form_title_margin',
			__( 'Margin', 'oxy-extended' ),
			'.frm_form_title'
		)->whiteList();

		$form_desc = $this->typographySection( __( 'Form Description', 'oxy-extended' ), '.frm_description p', $this );
		$form_desc_spacing = $form_desc->addControlSection( 'form_desc_spacing', __( 'Spacing', 'oxy-extended' ), 'assets/icon.png', $this );

		$form_desc_spacing->addPreset(
			'padding',
			'form_desc_padding',
			__( 'Padding', 'oxy-extended' ),
			'.frm_description p'
		)->whiteList();

		$form_desc_spacing->addPreset(
			'margin',
			'form_desc_margin',
			__( 'Margin', 'oxy-extended' ),
			'.frm_description p'
		)->whiteList();

		/**
		 * Labels Section
		 * -------------------------------------------------
		 */
		$labels_section  = $this->addControlSection( 'oe_ff_labels_section', __( 'Labels', 'oxy-extended' ), 'assets/icon.png', $this );
		$labels_selector = '.frm_form_field > label, .frm_form_field .frm_primary_label, .frm_scale label';

		$labels_color = $labels_section->addStyleControl(
			array(
				'name'         => __( 'Labels Color', 'oxy-extended' ),
				'selector'     => $labels_selector,
				'value'        => '',
				'property'     => 'color',
				'control_type' => 'colorpicker',
			)
		);

		$labels_section->typographySection( __( 'Typography', 'oxy-extended' ), $labels_selector, $this );

		/**
		 * Input Fields Section
		 * -------------------------------------------------
		 */
		$input_fields_section = $this->addControlSection( 'oe_ff_input_fields', __( 'Input & Textarea', 'oxy-extended' ), 'assets/icon.png', $this );
		$field_selector       = '.with_frm_style input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), .with_frm_style select, .with_frm_style textarea';
		$input_selector       = '.with_frm_style input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])';
		$textarea_selector    = '.with_frm_style textarea';

		$input_fields_section->addStyleControls(
			array(
				array(
					'name'         => __( 'Background Color', 'oxy-extended' ),
					'selector'     => $field_selector,
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
					'property' => 'height',
				),
			)
		);

		$input_fields_padding_section = $input_fields_section->addControlSection( 'oe_ff_input_fields_padding_section', __( 'Padding', 'oxy-extended' ), 'assets/icon.png', $this );

		$input_fields_padding_section->addPreset(
			'padding',
			'oe_ff_fields_padding',
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
		$submit_button_section        = $this->addControlSection( 'oe_ff_submit_button_section', __( 'Submit Button', 'oxy-extended' ), 'assets/icon.png', $this );
		$submit_button_selector       = '.frm_button_submit';
		$submit_button_selector_hover = '.frm_button_submit:hover';

		$button_align = $submit_button_section->addControl( 'buttons-list', 'button_align', __( 'Alignment', 'oxy-extended' ) );
		$button_align->setValue( [ 'Left', 'Center', 'Right' ] );
		$button_align->setValueCSS([
			'Center'    => '.with_frm_style .frm_submit{text-align: center}',
			'Right'     => '.with_frm_style .frm_submit{text-align: right}',
		]);
		$button_align->setDefaultValue( 'Left' );
		$button_align->whiteList();

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

		$submit_button_padding_section = $submit_button_section->addControlSection( 'oe_ff_submit_button_padding_section', __( 'Padding', 'oxy-extended' ), 'assets/icon.png', $this );

		$submit_button_padding_section->addPreset(
			'padding',
			'oe_ff_submit_button_padding',
			__( 'Padding', 'oxy-extended' ),
			$submit_button_selector
		)->whiteList();

		$submit_button_section->typographySection( __( 'Typography', 'oxy-extended' ), $submit_button_selector, $this );

		$submit_button_section->borderSection( __( 'Border', 'oxy-extended' ), $submit_button_selector, $this );

		$submit_button_section->boxShadowSection( __( 'Box Shadow', 'oxy-extended' ), $submit_button_selector, $this );

		/**
		 * Spacing Section
		 * -------------------------------------------------
		 */
		$spacing_section = $this->addControlSection( 'oe_ff_fields_spacing', __( 'Spacing', 'oxy-extended' ), 'assets/icon.png', $this );
		$fields_selector = '.with_frm_style .form-field';
		$label_spacing_selector = '.frm_form_field > label, .frm_form_field .frm_primary_label, .frm_scale label';

		$spacing_section->addStyleControls(
			array(
				array(
					'name'     => __( 'Between Label & Input', 'oxy-extended' ),
					'selector' => $label_spacing_selector,
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
	 * Render Formidable Forms element output on the frontend.
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
		<div class = "oe-formidable-forms-container">
			<div class = "oe-contact-form oe-formidable-forms">
				<?php
				if ( $options['oe_select_form'] ) {
					$title = ( ! empty( $options['frmform_title'] ) && 'no' === $options['frmform_title'] ) ? 'false' : 'true';
					$desc = ( ! empty( $options['frmform_desc'] ) && 'no' === $options['frmform_desc'] ) ? 'false' : 'true';
					$ajax = ( ! empty( $options['frmform_ajax'] ) && 'no' === $options['frmform_ajax'] ) ? 'false' : 'true';

					echo do_shortcode( '[formidable id=' . $options['oe_select_form'] . ' title="' . $title . '" description="' . $desc . '" ajax="' . $ajax . '"]' );
				}
				?>
			</div>
		</div>
		<?php
	}

	public function customCSS( $original, $selector ) {
		$css = '.frm_style_formidable-style.with_frm_style .form-field input:not([type=file]):focus, .frm_style_formidable-style.with_frm_style select:focus, .frm_style_formidable-style.with_frm_style textarea:focus, .frm_style_formidable-style.with_frm_style .frm_focus_field input[type=text], .frm_style_formidable-style.with_frm_style .frm_focus_field input[type=password], .frm_style_formidable-style.with_frm_style .frm_focus_field input[type=email], .frm_style_formidable-style.with_frm_style .frm_focus_field input[type=number], .frm_style_formidable-style.with_frm_style .frm_focus_field input[type=url], .frm_style_formidable-style.with_frm_style .frm_focus_field input[type=tel], .frm_style_formidable-style.with_frm_style .frm_focus_field input[type=search], .frm_form_fields_active_style, .frm_style_formidable-style.with_frm_style .frm_focus_field .frm-card-element.StripeElement, .frm_style_formidable-style.with_frm_style .chosen-container-single.chosen-container-active .chosen-single, .frm_style_formidable-style.with_frm_style .chosen-container-active .chosen-choices{box-shadow: none}
			.form-missing {background: #fc5020;color: #fff;display: inline-block;clear: both;font-size: 25px;font-weight: 400;padding: 20px 40px;margin: 30px 0;width: 100%;}
			.oxy-oe-formidable-forms-styler{width: 100%}';

		return $css;
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

( new OEFormidableFormsStyler() )->removeApplyParamsButton();
