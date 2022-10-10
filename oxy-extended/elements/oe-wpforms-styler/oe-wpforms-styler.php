<?php
namespace Oxygen\OxyExtended;

use OxyExtended\Classes\OE_Helper;

if ( ! function_exists( 'wpforms' ) ) {
	return;
}
/**
 * WP Forms Styler
 */
class OEWpFormsStyler extends \OxyExtendedEl {

	public $css_added = false;

	/**
	 * Retrieve WP Forms element name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'WP Forms Styler', 'oxy-extended' );
	}

	/**
	 * Retrieve WP Forms element slug.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element slug.
	 */
	public function slug() {
		return 'oe_wpforms_styler';
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
	 * Retrieve WP Forms element icon.
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
				'value'   => OE_Helper::get_contact_forms( 'WP_Forms' ),
				'default' => '-1',
				'css'     => false,
			)
		);
		$select_form->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$select_form->rebuildElementOnChange();

		$form_title = $this->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Show Title', 'oxy-extended' ),
				'slug'    => 'wpforms_title',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'yes',
			)
		);
		$form_title->rebuildElementOnChange();

		$form_desc = $this->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Show Description', 'oxy-extended' ),
				'slug'    => 'wpforms_desc',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'yes',
			)
		);
		$form_desc->rebuildElementOnChange();

		$form_ajax = $this->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Enable Ajax', 'oxy-extended' ),
				'slug'    => 'gform_ajax',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default' => 'yes',
			)
		);
		$form_ajax->rebuildElementOnChange();

		$gform_tab_index = $this->addOptionControl(
			array(
				'type'  => 'textfield',
				'name'  => __( 'Tab Index', 'oxy-extended' ),
				'slug'  => 'gform_tabi',
				'value' => '10',
				'css'   => false,
			)
		)->rebuildElementOnChange();

		/**
		 * Form Wrapper
		 * -------------------------------------------------
		 */
		$form_wrapper_section = $this->addControlSection( 'form_wrap_section', __( 'Form Wrapper', 'oxy-extended' ), 'assets/icon.png', $this );
		$selector = '.gform_wrapper';

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

		$form_title = $this->typographySection( __( 'Form Title', 'oxy-extended' ), '.wpforms_title', $this );
		$form_title_spacing = $form_title->addControlSection( 'form_title_spacing', __( 'Spacing', 'oxy-extended' ), 'assets/icon.png', $this );

		$form_title_spacing->addPreset(
			'padding',
			'form_title_padding',
			__( 'Padding', 'oxy-extended' ),
			'.wpforms_title'
		)->whiteList();

		$form_title_spacing->addPreset(
			'margin',
			'form_title_margin',
			__( 'Margin', 'oxy-extended' ),
			'.wpforms_title'
		)->whiteList();

		$form_desc = $this->typographySection( __( 'Form Description', 'oxy-extended' ), '.wpforms_description', $this );
		$form_desc_spacing = $form_desc->addControlSection( 'form_desc_spacing', __( 'Spacing', 'oxy-extended' ), 'assets/icon.png', $this );

		$form_desc_spacing->addPreset(
			'padding',
			'form_desc_padding',
			__( 'Padding', 'oxy-extended' ),
			'.wpforms_description'
		)->whiteList();

		$form_desc_spacing->addPreset(
			'margin',
			'form_desc_margin',
			__( 'Margin', 'oxy-extended' ),
			'.wpforms_description'
		)->whiteList();

		$field_desc = $this->typographySection( __( 'Field Description', 'oxy-extended' ), '.gfield_description', $this );
		$field_desc_spacing = $field_desc->addControlSection( 'field_desc_spacing', __( 'Spacing', 'oxy-extended' ), 'assets/icon.png', $this );

		$field_desc_spacing->addPreset(
			'padding',
			'form_desc_padding',
			__( 'Padding', 'oxy-extended' ),
			'.gfield_description'
		)->whiteList();

		/**
		 * Labels Section
		 * -------------------------------------------------
		 */
		$labels_section  = $this->addControlSection( 'oe_gf_labels_section', __( 'Labels', 'oxy-extended' ), 'assets/icon.png', $this );
		$labels_selector = '.gform_wrapper .gfield_label';

		$gf_hide_labels = $labels_section->addControl( 'buttons-list', 'gf_hide_labels', __( 'Hide Labels', 'oxy-extended' ) );
		$gf_hide_labels->setValue( [ 'No', 'Yes' ] );
		$gf_hide_labels->setValueCSS( [ 'Yes' => '.gform_wrapper .top_label .gfield_label{display:none}' ] );
		$gf_hide_labels->setDefaultValue( 'No' );
		$gf_hide_labels->whiteList();

		$sublabels = $labels_section->addControlSection( 'sublabels', __( 'Sub Labels', 'oxy-extended' ), 'assets/icon.png', $this );
		$sublabels->addStyleControls(
			array(
				array(
					'selector'  => '.ginput_container span label, .gfield_time_hour label, .gfield_time_minute label',
					'property'  => 'color',
					'slug'      => 'sublb_color',
				),
				array(
					'selector'  => '.ginput_container span label, .gfield_time_hour label, .gfield_time_minute label',
					'property'  => 'font-size',
					'slug'      => 'sublb_fs',
				),
				array(
					'selector'  => '.ginput_container span label, .gfield_time_hour label, .gfield_time_minute label',
					'property'  => 'font-weight',
					'slug'      => 'sublb_fw',
				),
				array(
					'selector'  => '.ginput_container span label, .gfield_time_hour label, .gfield_time_minute label',
					'property'  => 'text-transform',
					'slug'      => 'sublb_tt',
				),
			)
		);

		$labels_section->typographySection( __( 'Typography', 'oxy-extended' ), $labels_selector, $this );

		/**
		 * Input Fields Section
		 * -------------------------------------------------
		 */
		$input_fields_section = $this->addControlSection( 'oe_ff_input_fields', __( 'Input & Textarea', 'oxy-extended' ), 'assets/icon.png', $this );
		$field_selector       = '.gform_wrapper .gfield input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), .gform_wrapper .gfield select, .gform_wrapper .gfield textarea';
		$input_selector       = '.gform_wrapper .gfield input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])';
		$textarea_selector    = '.gform_wrapper .gfield textarea';

		$hgap_fields = $input_fields_section->addStyleControl([
			'control_type'       => 'slider-measurebox',
			'selector'           => '.gform_wrapper li.gfield.gf_left_half, .gform_wrapper li.gf_right_half, div.ginput_container_name span, .gform_wrapper .ginput_complex.ginput_container .ginput_left, .gform_wrapper .ginput_complex.ginput_container .ginput_right',
			'name'               => __( 'Gap Between Two Horizontal Fields', 'oxy-extended' ),
			'slug'               => 'oe_gf_hgap_fields',
			'property'           => 'padding-right',
		]);
		$hgap_fields->setUnits( 'px', 'px,%,em' );
		$hgap_fields->setRange( '0', '50', '5' );
		$hgap_fields->setDefaultValue( '16' );

		$input_fields_section->addStyleControls(
			array(
				array(
					'name'         => __( 'Space Between Two Vertical Fields', 'oxy-extended' ),
					'selector'     => '.gform_wrapper .gfield',
					'property'     => 'margin-top',
					'control_type' => 'slider-measurebox',
					'units'        => 'px',
					'slug'         => 'oe_gf_vgap_fields',
					'value'        => '10',
				),
			)
		);

		$input_fields_section->addStyleControls(
			array(
				array(
					'name'              => __( 'Asterix Color', 'oxy-extended' ),
					'selector'          => '.gfield_required',
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
					'slug'         => 'gf_inp_placeholder',
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
		 * Section Field
		 * -------------------------------------------------
		 */
		$section_field_style = $this->addControlSection( 'section_field_style', __( 'Section Field', 'oxy-extended' ), 'assets/icon.png', $this );
		$section_field_style->typographySection( __( 'Section Title', 'oxy-extended' ), '.gsection_title', $this );
		$section_field_style->typographySection( __( 'Section Description', 'oxy-extended' ), '.gsection_description', $this );
		$section_field_selector = '.gfield.gsection';

		$section_field_style->addStyleControl(
			array(
				'selector'      => $section_field_selector,
				'property'      => 'background-color',
			)
		);

		$section_field_style->addStyleControl(
			array(
				'selector'      => $section_field_selector,
				'property'      => 'border-color',
			)
		)->setParam( 'hide_wrapper_end', true );

		$section_field_style->addStyleControl(
			array(
				'selector'      => $section_field_selector,
				'property'      => 'border-bottom-width',
			)
		)->setParam( 'hide_wrapper_start', true );

		$section_field_style->addPreset(
			'padding',
			'gsection_padding',
			__( 'Padding', 'oxy-extended' ),
			$section_field_selector
		)->whiteList();

		$section_field_style->addPreset(
			'margin',
			'gsection_margin',
			__( 'Margin', 'oxy-extended' ),
			$section_field_selector
		)->whiteList();

		/**
		 * Radio & Checkboxes Fields
		 * -------------------------------------------------
		 */

		$gfrc_section = $this->addControlSection( 'gfrc_section', __( 'Checkbox & Radio', 'oxy-extended' ), 'assets/icon.png', $this );
		$cr_selector = '.gfield_checkbox input[type=checkbox], .gfield_radio input[type=radio]';
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
					'selector'          => '.gfield_checkbox label, .gfield_radio span label',
					'property'          => 'color',
					'name'              => __( 'Text Color', 'oxy-extended' ),
					'slug'              => 'gfcr_clr',
				),
				array(
					'selector'          => '.gfield_checkbox label, .gfield_radio span label',
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
					'selector'          => $cr_selector . ', .gfield_checkbox input[type=checkbox]:checked, .gfield_radio input[type=radio]:checked',
					'property'          => 'border-width',
					'condition'         => 'oe_radio_check_smart_ui=yes',
					'slug'              => 'gfcr_brdw',
				),
				array(
					'name'              => __( 'Checked Background Color', 'oxy-extended' ),
					'selector'          => '.gfield_checkbox input[type=checkbox]:checked, .gfield_radio input[type=radio]:checked',
					'property'          => 'background-color|border-color',
					'slug'              => 'gfrc_bg_color',
					'control_type'      => 'colorpicker',
					'condition'         => 'oe_radio_check_smart_ui=yes',
				),
			)
		);

		$gfrc_section->addPreset(
			'margin',
			'gfrc_margin',
			__( 'Field Margin', 'oxy-extended' ),
			'.gfield_checkbox input[type=checkbox], .gfield_radio input[type=radio]'
		)->whiteList();

		$gfrc_section->addPreset(
			'padding',
			'gfrcl_padding',
			__( 'Label Spacing', 'oxy-extended' ),
			'.gfield_checkbox label, .gfield_radio label'
		)->whiteList();

		/**
		 * File Upload Field
		 * -------------------------------------------------
		 */
		$fileupload_section = $this->addControlSection( 'oe_file_upload', __( 'File Upload Field', 'oxy-extended' ), 'assets/icon.png', $this );
		$fileupload_selector = '.gfield .ginput_container_fileupload > input[type=file]';
		$fileupload_section->addStyleControls(
			array(
				array(
					'selector'          => $fileupload_selector,
					'property'          => 'background-color',
					'slug'              => 'fileupload_bg_color',
				),
				array(
					'selector'          => $fileupload_selector,
					'property'          => 'width',
					'slug'              => 'fileupload_width',
				),
			)
		);

		$fileupload_section->typographySection( __( 'Typography', 'oxy-extended' ), $fileupload_selector, $this );
		$fileupload_section->borderSection( __( 'Border', 'oxy-extended' ), $fileupload_selector, $this );
		$fileupload_section->addPreset(
			'padding',
			'fileupload_padding',
			__( 'Padding', 'oxy-extended' ),
			$fileupload_selector
		)->whiteList();

		/**
		 * Submit Button Section
		 * -------------------------------------------------
		 */
		$submit_button_section        = $this->addControlSection( 'oe_gf_submit_button_section', __( 'Submit Button', 'oxy-extended' ), 'assets/icon.png', $this );
		$submit_button_selector       = '.gform_button';
		$submit_button_selector_hover = '.gform_button:hover';

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

		$submit_button_padding_section = $submit_button_section->addControlSection( 'oe_gf_submit_button_padding_section', __( 'Padding', 'oxy-extended' ), 'assets/icon.png', $this );

		$submit_button_padding_section->addPreset(
			'padding',
			'oe_ff_submit_button_padding',
			__( 'Padding', 'oxy-extended' ),
			$submit_button_selector
		)->whiteList();

		$submit_button_section->borderSection( __( 'Border', 'oxy-extended' ), $submit_button_selector, $this );

		$submit_button_section->boxShadowSection( __( 'Box Shadow', 'oxy-extended' ), $submit_button_selector, $this );

	}

	/**
	 * Render WP Forms element output on the frontend.
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
		<div class = "oe-wp-forms-container">
			<div class = "oe-contact-form oe-wp-forms">
				<?php
				if ( $options['oe_select_form'] ) {
					$title = ( ! empty( $options['wpforms_title'] ) && 'no' === $options['wpforms_title'] ) ? 'false' : 'true';
					$desc = ( ! empty( $options['wpforms_desc'] ) && 'no' === $options['wpforms_desc'] ) ? 'false' : 'true';

					echo do_shortcode( '[wpforms id=' . $options['oe_select_form'] . ' title="' . $options['wpforms_title'] . '" description="' . $options['wpforms_desc'] . '"]' );
				}
				?>
			</div>
		</div>
		<?php
	}

	public function customCSS( $original, $selector ) {
		if ( ! $this->css_added ) {
			$this->css_added = true;

			return 'div.wpforms-container-full .wpforms-form input[type=date], 
				div.wpforms-container-full .wpforms-form input[type=datetime], 
				div.wpforms-container-full .wpforms-form input[type=datetime-local], 
				div.wpforms-container-full .wpforms-form input[type=email], 
				div.wpforms-container-full .wpforms-form input[type=month], 
				div.wpforms-container-full .wpforms-form input[type=number], 
				div.wpforms-container-full .wpforms-form input[type=password], 
				div.wpforms-container-full .wpforms-form input[type=range], 
				div.wpforms-container-full .wpforms-form input[type=search], 
				div.wpforms-container-full .wpforms-form input[type=tel], 
				div.wpforms-container-full .wpforms-form input[type=text], 
				div.wpforms-container-full .wpforms-form input[type=time], 
				div.wpforms-container-full .wpforms-form input[type=url], 
				div.wpforms-container-full .wpforms-form input[type=week], 
				div.wpforms-container-full .wpforms-form select {
				  height: auto!important;
				}
				.wpforms-container, .oxy-ou-wpf-styler {width: 100%}
				.form-missing {
					background: #fc5020;
					color: #fff;
					display: inline-block;
					clear: both;
					font-size: 25px;
					font-weight: 400;
					padding: 20px 40px;
					margin: 30px 0;
					width: 100%;
				}';
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

( new OEWpFormsStyler() )->removeApplyParamsButton();
