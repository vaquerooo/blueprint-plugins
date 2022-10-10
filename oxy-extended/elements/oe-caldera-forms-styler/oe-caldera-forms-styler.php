<?php
namespace Oxygen\OxyExtended;

if ( ! class_exists( 'Caldera_Forms' ) ) {
	return;
}
/**
 * Caldera Forms Styler
 */
class OECalderaFormsStyler extends \OxyExtendedEl {

	/**
	 * Retrieve Caldera Forms element name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'Caldera Forms Styler', 'oxy-extended' );
	}

	/**
	 * Retrieve Caldera Forms element slug.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element slug.
	 */
	public function slug() {
		return 'oe_caldera_forms_styler';
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
	 * Retrieve Caldera Forms element icon.
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
	 * Get Caldera Forms list.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function oe_get_caldera_forms() {
		$field_options = array();
		$field_options[0] = esc_html__( 'Select a form', 'oxy-extended' );

		// Caldera Forms
		if ( class_exists( 'Caldera_Forms' ) ) {
			$caldera_forms = \Caldera_Forms_Forms::get_forms( true, true );

			if ( ! empty( $caldera_forms ) && ! is_wp_error( $caldera_forms ) ) {
				foreach ( $caldera_forms as $form ) {
					$field_options[ $form['ID'] ] = $form['name'];
				}
			}
		}

		if ( empty( $field_options ) ) {
			$field_options = array(
				'-1' => __( 'You have not added any Caldera Forms yet.', 'oxy-extended' ),
			);
		}

		return $field_options;
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
				'value'   => $this->oe_get_caldera_forms(),
				'default' => '-1',
				'css'     => false,
			)
		);
		$select_form->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$select_form->rebuildElementOnChange();

		/**
		 * Custom Title & Description
		 * -------------------------------------------------
		 */
		$custom_td_section  = $this->addControlSection( 'oe_cf_title_section', __( 'Custom Title & Description', 'oxy-extended' ), 'assets/icon.png', $this );

		$form_title_desc = $custom_td_section->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Show Title & Description', 'oxy-extended' ),
				'slug'    => 'oe_custom_title_description',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'no',
			)
		);
		$form_title_desc->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$form_title_desc->rebuildElementOnChange();

		$form_title = $custom_td_section->addOptionControl(
			array(
				'type'  => 'textfield',
				'name'  => __( 'Title', 'oxy-extended' ),
				'slug'  => 'oe_cf_form_title',
				'value' => __( 'Form Title', 'oxy-extended' ),
				'css'   => false,
				'condition' => 'oe_custom_title_description=yes',
			)
		)->rebuildElementOnChange();

		$form_description = $custom_td_section->addOptionControl(
			array(
				'type'  => 'textarea',
				'name'  => __( 'Description', 'oxy-extended' ),
				'slug'  => 'oe_cf_form_description',
				'value' => '',
				'css'   => false,
				'condition' => 'oe_custom_title_description=yes',
			)
		)->rebuildElementOnChange();

		$custom_td_section->typographySection( __( 'Title Typography', 'oxy-extended' ), '.oe-caldera-forms-title', $this );

		$custom_td_section->typographySection( __( 'Description Typography', 'oxy-extended' ), '.oe-caldera-forms-description', $this );

		$custom_td_section->addPreset(
			'margin',
			'custom_heading_margin',
			__( 'Margin', 'oxy-extended' ),
			'.oe-caldera-forms-heading'
		)->whiteList();

		/**
		 * Labels Section
		 * -------------------------------------------------
		 */
		$labels_section  = $this->addControlSection( 'oe_cf_labels_section', __( 'Labels', 'oxy-extended' ), 'assets/icon.png', $this );
		$labels_selector = '.oe-caldera-form .form-group label';

		$cf_hide_labels = $labels_section->addControl( 'buttons-list', 'cf_hide_labels', __( 'Hide Labels', 'oxy-extended' ) );
		$cf_hide_labels->setValue( [ 'No', 'Yes' ] );
		$cf_hide_labels->setValueCSS( [ 'Yes' => '.form-group label{display:none}' ] );
		$cf_hide_labels->setDefaultValue( 'No' );
		$cf_hide_labels->whiteList();

		$labels_section->typographySection( __( 'Typography', 'oxy-extended' ), $labels_selector, $this );

		/**
		 * Input Fields Section
		 * -------------------------------------------------
		 */
		$input_fields_section = $this->addControlSection( 'oe_cf_input_fields', __( 'Input & Textarea', 'oxy-extended' ), 'assets/icon.png', $this );
		$field_selector       = '.oe-caldera-form input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), .oe-caldera-form .form-group textarea, .oe-caldera-form .form-group select, .oe-caldera-form .form-control';
		$input_selector       = '.oe-caldera-form input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), .oe-caldera-form .form-control';
		$textarea_selector    = '.oe-caldera-form .caldera-grid textarea.form-control';

		$input_fields_section->addStyleControls(
			array(
				array(
					'name'         => __( 'Space Between Fields', 'oxy-extended' ),
					'selector'     => '.oe-caldera-form .caldera-grid .form-group, .oe-caldera-form .cf-color-picker .form-group',
					'property'     => 'margin-bottom',
					'control_type' => 'slider-measurebox',
					'units'        => 'px',
					'slug'         => 'oe_cf_vgap_fields',
					'value'        => '15',
				),
			)
		);

		$input_fields_section->addStyleControls(
			array(
				array(
					'name'              => __( 'Asterix Color', 'oxy-extended' ),
					'selector'          => '.field_required',
					'property'          => 'color',
				),
				array(
					'name'         => __( 'Background Color', 'oxy-extended' ),
					'selector'     => $field_selector,
					'property'     => 'background-color',
					'control_type' => 'colorpicker',
				),
				array(
					'name'         => __( 'Placeholder Color', 'oxy-extended' ),
					'slug'         => 'cf_inp_placeholder',
					'selector'     => '::placeholder',
					'property'     => 'color',
					'css'          => false,
				),
				array(
					'name'         => __( 'Input Width', 'oxy-extended' ),
					'selector'     => $input_selector,
					'property'     => 'width',
				),
				array(
					'name'         => __( 'Textarea Width', 'oxy-extended' ),
					'selector'     => $textarea_selector,
					'property'     => 'width',
				),
				array(
					'name'         => __( 'Textarea Height', 'oxy-extended' ),
					'selector'     => $textarea_selector,
					'property'     => 'height',
				),
			)
		);

		$input_fields_padding_section = $input_fields_section->addControlSection( 'oe_cf_input_fields_padding_section', __( 'Padding', 'oxy-extended' ), 'assets/icon.png', $this );

		$input_fields_padding_section->addPreset(
			'padding',
			'oe_cf_fields_padding',
			__( 'Padding', 'oxy-extended' ),
			$field_selector
		)->whiteList();

		$input_fields_section->typographySection( __( 'Typography', 'oxy-extended' ), $field_selector, $this );

		$input_fields_section->borderSection( __( 'Border', 'oxy-extended' ), $field_selector, $this );

		$input_fields_section->boxShadowSection( __( 'Box Shadow', 'oxy-extended' ), $field_selector, $this );

		/**
		 * Section Field
		 * -------------------------------------------------
		 */
		$section_field_style = $this->addControlSection( 'section_field_style', __( 'Section Field', 'oxy-extended' ), 'assets/icon.png', $this );
		$section_field_selector = '.oe-caldera-form .help-block';

		$section_field_style->addStyleControl(
			array(
				'selector'      => $section_field_selector,
				'property'      => 'background-color',
			)
		);

		$section_field_style->addPreset(
			'padding',
			'gsection_padding',
			__( 'Padding', 'oxy-extended' ),
			$section_field_selector
		)->whiteList();

		$section_field_style->typographySection( __( 'Section Title', 'oxy-extended' ), $section_field_selector, $this );

		/**
		 * Radio & Checkboxes Fields
		 * -------------------------------------------------
		 */

		$gfrc_section = $this->addControlSection( 'gfrc_section', __( 'Checkbox & Radio', 'oxy-extended' ), 'assets/icon.png', $this );
		$cr_selector = '.oe-caldera-form input[type="checkbox"], .oe-caldera-form input[type="radio"]';
		$gfrc_section->addOptionControl(
			array(
				'type'          => 'radio',
				'name'          => __( 'Enable Smart UI', 'oxy-extended' ),
				'slug'          => 'oe_radio_check_smart_ui',
				'value'         => array(
					'yes'       => __( 'Yes', 'oxy-extended' ),
					'no'        => __( 'No', 'oxy-extended' ),
				),
				'default'       => 'no',
				'css'           => false,
			)
		)->rebuildElementOnChange();

		$gfrc_section->addStyleControls(
			array(
				array(
					'selector'          => '.form-group .checkbox label, .form-group .radio label',
					'property'          => 'color',
					'name'              => __( 'Text Color', 'oxy-extended' ),
					'slug'              => 'gfcr_clr',
				),
				array(
					'selector'          => '.form-group .checkbox label, .form-group .radio label',
					'property'          => 'font-size',
					'control_type'      => 'slider-measurebox',
					'name'              => __( 'Text Font Size', 'oxy-extended' ),
					'unit'              => 'px',
					'slug'              => 'gfcr_fs',
				),
				array(
					'selector'          => $cr_selector,
					'name'              => __( 'Width', 'oxy-extended' ),
					'property'          => 'width|height',
					'control_type'      => 'slider-measurebox',
					'unit'              => 'px',
					'value'             => 15,
					'slug'              => 'gfcr_wd',
					'condition'         => 'oe_radio_check_smart_ui=yes',
				),
				array(
					'selector'          => $cr_selector,
					'property'          => 'background-size',
					'control_type'      => 'measurebox',
					'unit'              => 'px',
					'value'             => 9,
					'slug'              => 'gfcr_bgc',
					'condition'         => 'oe_radio_check_smart_ui=yes',
				),
				array(
					'selector'          => $cr_selector,
					'property'          => 'background-color',
					'slug'              => 'gfcr_bg_color',
					'condition'         => 'oe_radio_check_smart_ui=yes',
				),
				array(
					'selector'          => $cr_selector,
					'property'          => 'border-color',
					'slug'              => 'cr_brd_color',
					'condition'         => 'oe_radio_check_smart_ui=yes',
				),
				array(
					'selector'          => $cr_selector . ', .form-group .checkbox input[type=checkbox]:checked, .form-group .radio input[type=radio]:checked',
					'property'          => 'border-width',
					'condition'         => 'oe_radio_check_smart_ui=yes',
					'slug'              => 'gfcr_brdw',
				),
				array(
					'name'              => __( 'Checked Background Color', 'oxy-extended' ),
					'selector'          => '.form-group .checkbox input[type=checkbox]:checked, .form-group .radio input[type=radio]:checked',
					'property'          => 'background-color|border-color',
					'slug'              => 'gfrc_bg_color',
					'control_type'      => 'colorpicker',
					'condition'         => 'oe_radio_check_smart_ui=yes',
				),
			)
		);

		/**
		 * Submit Button Section
		 * -------------------------------------------------
		 */
		$submit_button_section        = $this->addControlSection( 'oe_cf_submit_button_section', __( 'Submit Button', 'oxy-extended' ), 'assets/icon.png', $this );
		$submit_button_selector       = '.oe-caldera-form .form-group input[type="submit"], .oe-caldera-form .form-group input[type="button"]';
		$submit_button_selector_hover = '.oe-caldera-form .form-group input[type="submit"]:hover, .oe-caldera-form .form-group input[type="button"]:hover';

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

		$submit_button_padding_section = $submit_button_section->addControlSection( 'oe_cf_submit_button_padding_section', __( 'Padding', 'oxy-extended' ), 'assets/icon.png', $this );

		$submit_button_padding_section->addPreset(
			'padding',
			'oe_cf_submit_button_padding',
			__( 'Padding', 'oxy-extended' ),
			$submit_button_selector
		)->whiteList();

		$submit_button_section->typographySection( __( 'Typography', 'oxy-extended' ), $submit_button_selector, $this );

		$submit_button_section->borderSection( __( 'Border', 'oxy-extended' ), $submit_button_selector, $this );

		$submit_button_section->boxShadowSection( __( 'Box Shadow', 'oxy-extended' ), $submit_button_selector, $this );

	}

	/**
	 * Render Caldera Forms element output on the frontend.
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
		$forms = $this->oe_get_caldera_forms();
		if ( class_exists( 'Caldera_Forms' ) ) {
			if ( empty( $forms ) && isset( $forms[-1] ) ) {
				echo __( 'You have not added any Caldera Forms form yet.', 'oxy-extended' );
				return;
			}

			if ( '-1' === $options['oe_select_form'] ) {
				echo __( 'No Contact Form Selected!', 'oxy-extended' );
				return;
			}
			?>
			<div class = "oe-caldera-forms-container">
				<div class = "oe-contact-form oe-caldera-form">
					<?php if ( 'yes' === $options['oe_custom_title_description'] ) { ?>
						<div class="oe-caldera-forms-heading">
							<?php if ( $options['oe_cf_form_title'] ) { ?>
								<h3 class="oe-contact-form-title oe-caldera-forms-title">
									<?php echo esc_attr( $options['oe_cf_form_title'] ); ?>
								</h3>
							<?php } ?>
							<?php if ( $options['oe_cf_form_description'] ) { ?>
								<div class="oe-contact-form-description oe-caldera-forms-description">
									<?php echo esc_attr( $options['oe_cf_form_description'] ); ?>
								</div>
							<?php } ?>
						</div>
					<?php }
					if ( $options['oe_select_form'] ) {
						echo do_shortcode( '[caldera_form id="' . $options['oe_select_form'] . '" ]' );
					}
						?>
				</div>
			</div>
			<?php
		}
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

( new OECalderaFormsStyler() )->removeApplyParamsButton();
