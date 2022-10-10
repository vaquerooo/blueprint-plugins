<?php
namespace Oxygen\OxyExtended;

use OxyExtended\Classes\OE_Helper;

/**
 * Fluent Forms Styler
 */
class OEFluentFormsStyler extends \OxyExtendedEl {

	/**
	 * Retrieve Fluent Forms element name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'Fluent Forms Styler', 'oxy-extended' );
	}

	/**
	 * Retrieve Fluent Forms element slug.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element slug.
	 */
	public function slug() {
		return 'oe_fluent_forms_styler';
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
	 * Retrieve Fluent Forms element icon.
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
				'value'   => OE_Helper::get_contact_forms( 'Fluent_Forms' ),
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
		$custom_td_section  = $this->addControlSection( 'oe_ff_title_section', __( 'Custom Title & Description', 'oxy-extended' ), 'assets/icon.png', $this );

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
				'slug'  => 'oe_ff_form_title',
				'value' => __( 'Form Title', 'oxy-extended' ),
				'css'   => false,
				'condition' => 'oe_custom_title_description=yes',
			)
		)->rebuildElementOnChange();

		$form_description = $custom_td_section->addOptionControl(
			array(
				'type'  => 'textarea',
				'name'  => __( 'Description', 'oxy-extended' ),
				'slug'  => 'oe_ff_form_description',
				'value' => '',
				'css'   => false,
				'condition' => 'oe_custom_title_description=yes',
			)
		)->rebuildElementOnChange();

		$custom_td_section->typographySection( __( 'Title Typography', 'oxy-extended' ), '.oe-fluent-forms-title', $this );

		$custom_td_section->typographySection( __( 'Description Typography', 'oxy-extended' ), '.oe-fluent-forms-description', $this );

		$custom_td_section->addPreset(
			'margin',
			'custom_heading_margin',
			__( 'Margin', 'oxy-extended' ),
			'.oe-fluent-forms-heading'
		)->whiteList();

		/**
		 * Labels Section
		 * -------------------------------------------------
		 */
		$labels_section  = $this->addControlSection( 'oe_ff_labels_section', __( 'Labels', 'oxy-extended' ), 'assets/icon.png', $this );
		$labels_selector = '.fluentform .ff-el-input--label label';

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
		$field_selector       = '.oe-fluent-forms .fluentform .ff-el-form-control';
		$input_selector       = '.oe-fluent-forms .fluentform input.ff-el-form-control';
		$textarea_selector    = '.oe-fluent-forms .fluentform textarea.ff-el-form-control';

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
		$submit_button_selector       = '.oe-fluent-forms .ff-btn-submit';
		$submit_button_selector_hover = '.oe-fluent-forms .ff-btn-submit:hover';

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

		$submit_button_section->borderSection( __( 'Border', 'oxy-extended' ), $submit_button_selector, $this );

		$submit_button_section->boxShadowSection( __( 'Box Shadow', 'oxy-extended' ), $submit_button_selector, $this );

		/**
		 * Spacing Section
		 * -------------------------------------------------
		 */
		$spacing_section = $this->addControlSection( 'oe_ff_fields_spacing', __( 'Spacing', 'oxy-extended' ), 'assets/icon.png', $this );
		$fields_selector = '.oe-fluent-forms .ff-el-group';
		$label_spacing_selector = '.fluentform .ff-el-input--label';

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
	 * Render Fluent Forms element output on the frontend.
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
		<div class = "oe-fluent-forms-container">
			<div class = "oe-contact-form oe-fluent-forms">
				<?php if ( 'yes' === $options['oe_custom_title_description'] ) { ?>
					<div class="oe-fluent-forms-heading">
						<?php if ( $options['oe_ff_form_title'] ) { ?>
							<h3 class="oe-contact-form-title oe-fluent-forms-title">
								<?php echo esc_attr( $options['oe_ff_form_title'] ); ?>
							</h3>
						<?php } ?>
						<?php if ( $options['oe_ff_form_description'] ) { ?>
							<div class="oe-contact-form-description oe-fluent-forms-description">
								<?php echo esc_attr( $options['oe_ff_form_description'] ); ?>
							</div>
						<?php } ?>
					</div>
				<?php }
				if ( $options['oe_select_form'] ) {
					echo do_shortcode( '[fluentform id=' . $options['oe_select_form'] . ']' );
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

( new OEFluentFormsStyler() )->removeApplyParamsButton();
