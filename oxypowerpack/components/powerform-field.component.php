<?php
defined('ABSPATH') or die();

class OxyPowerPackPowerFormField extends OxyPowerPackEl
{
    function init() {

	    $this->El->inlineJS( file_get_contents( plugin_dir_path(__FILE__) . 'powerform/inline.js' ));

        add_action('ct_toolbar_component_settings', function(){
            ?>
            <?php
        });

    }

    function button_place() {
        return "oxypowerpack::hidden";
    }

    function button_priority() {
        return 1;
    }

    function icon() {
        return plugin_dir_url(__FILE__) . '../assets/img/icon-powerform.svg';
    }

    function description() {
        return ".";
    }

    function name() {
        return "Power Form Field";
    }

    function options(){
        return array(
            "server_side_render" => false
        );
    }

    function render(){//($options, $defaults, $content){
        ob_start();
        ?>
            <div class="label-and-field-wrapper" data-oppfield="%%field_name%%" >
                <label for="%%ELEMENT_ID%%_field" %%field_type%% >%%field_label%%</label>
                <div class="power-form-field-wrapper">
                    <span>%%required_message%%</span>
                </div>
            </div>
        <?php
        return ob_get_clean();
    }

    function controls(){

        $field_type_control = $this->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => 'Field Type',
                "slug" => 'field_type',
                "default" => 'text',
            )
        );
        $field_type_control->setValue(
            array(
                'text' => 'Text',
                'number' => 'Number',
                'color' => 'Color',
                'textarea' => 'Text Area',
                'checkbox' => 'Checkbox',
                'radio' => 'Radio Group',
                'select' => 'Select',
                'submit' => 'Submit Button',
                'simple_captcha' => 'Simple Captcha'
            )
        );

        $field_label_control = $this->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => 'Label',
                "slug" => 'field_label',
                "default" => 'Field Name',
                "condition" => 'field_type!=simple_captcha'
            )
        );

        $field_name_control = $this->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => 'Field Name',
                "slug" => 'field_name',
                "condition" => 'field_type!=simple_captcha'
            )
        )->setValue('field_unique_name');
	    $field_value_control = $this->addOptionControl(
		    array(
			    "type" => 'textfield',
			    "name" => 'Field Value',
			    "slug" => 'field_value',
                "condition" => 'field_type!=radio&&field_type!=select&&field_type!=simple_captcha'
		    )
	    )->setValue('');
        $tabindex_control = $this->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => 'Tab Index',
                "slug" => 'tab_index'
            )
        )->setValue('0');
	    $textarea_rows_control = $this->addOptionControl(
		    array(
			    "type" => 'textfield',
			    "name" => 'Text Area Rows',
			    "slug" => 'textarea_rows',
                "condition" => 'field_type=textarea'
		    )
	    )->setValue('4');
	    $textarea_cols_control = $this->addOptionControl(
		    array(
			    "type" => 'textfield',
			    "name" => 'Text Area Cols',
			    "slug" => 'textarea_cols',
			    "condition" => 'field_type=textarea'
		    )
	    )->setValue('20');
	    $additional_class_control = $this->addOptionControl(
		    array(
			    "type" => 'textfield',
			    "name" => 'Additional Class',
			    "slug" => 'additional_class',
		    )
	    )->setValue('');
	    $this->addCustomControl('<div ng-show="iframeScope.component.options[iframeScope.component.active.id].model[\'oxy-power-form-field_field_type\'] == \'radio\' || iframeScope.component.options[iframeScope.component.active.id].model[\'oxy-power-form-field_field_type\'] == \'select\'" ng-click="oxyPowerPack.formOptions.formOptionsModal=true" class="oxygen-apply-button">Manage Options</div>');
	    $is_required_control = $this->addOptionControl(
		    array(
			    "type" => 'checkbox',
			    "name" => 'Is Required?',
			    "slug" => 'required',

		    )
	    );
	    $required_text_control = $this->addOptionControl(
		    array(
			    "type" => 'textfield',
			    "name" => 'Required Message',
			    "slug" => 'required_message',
			    "condition" => 'required=true'
		    )
	    )->setValue('This field is required');


	    ///////////////////////
        ///
	    $label_section = $this->addControlSection("label", __("Label"), "assets/icon.png", $this);
	    $label_position = $label_section->addControl("buttons-list", "label_position", __("Label Position") );
	    $label_position->setValue( array('top' => "Top",'left'=>"Left",'right'=>"Right",'none'=>"No label") );
	    $label_position->setDefaultValue('top');
	    $label_position->setValueCSS( array(
		    "top" => "
                .label-and-field-wrapper{
                    flex-direction: column !important;
                }
                label{
                    width: auto !important;
                }",
		    "left" => "
                .label-and-field-wrapper{
                    flex-direction: row !important;
                }
                .power-form-field-wrapper{
                    flex-grow: 1 !important;
                }",
		    "right" => "
                .label-and-field-wrapper{
                    flex-direction: row-reverse !important;
                }
                .power-form-field-wrapper{
                    flex-grow: 1 !important;
                }",
		    "none" => "
                .power-form-field-wrapper{
                    flex-grow: 1 !important;
                }
                .label-and-field-wrapper{
                    flex-direction: row !important;
                }
                label{
                    display: none !important;
                }
            "
	    ) );
	    $label_position->whiteList();

	    $label_spacing_section = $label_section->addControlSection("label_spacing", __("Spacing"), "assets/icon.png", $this);
	    $label_spacing_section->addPreset(
		    "padding",
		    "label_padding",
		    __("Label Padding"),
		    "label"
	    )->whiteList();
	    $label_spacing_section->addPreset(
		    "margin",
		    "label_margin",
		    __("Label Margin"),
		    "label"
	    )->whiteList();
	    $label_border_section = $label_section->addControlSection("label_border", __("Borders"), "assets/icon.png", $this);
	    $label_border_section->addPreset(
		    "border",
		    "label_border",
		    __("Label Borders"),
		    "label"
	    )->whiteList();
	    $label_typography_section = $label_section->addControlSection("label_typography", __("Typography"), "assets/icon.png", $this);
	    $label_typography_section->addPreset(
		    "typography",
		    "label_typography",
		    __("Typography"),
		    "label"
	    )->whiteList();

	    $field_section = $this->addControlSection("field", __("Input Field"), "assets/icon.png", $this);

	    $field_spacing_section = $field_section->addControlSection("field_spacing", __("Spacing"), "assets/icon.png", $this);
	    $field_spacing_section->addPreset(
		    "padding",
		    "field_padding",
		    __("Input Field Padding"),
		    "input, select, button, textarea"
	    )->whiteList();
	    $field_spacing_section->addPreset(
		    "margin",
		    "label_margin",
		    __("Label Margin"),
		    "input, select, button, textarea"
	    )->whiteList();
	    $field_border_section = $field_section->addControlSection("field_border", __("Borders"), "assets/icon.png", $this);
	    $field_border_section->addPreset(
		    "border",
		    "field_border",
		    __("Input Field Borders"),
		    "input, select, button, textarea"
	    )->whiteList();
	    $field_border_section->addStyleControl(
		    array(
			    "name" => __('Border Radius'),
			    "selector" => "input, select, button, textarea",
			    "property" => 'border-radius',
			    "control_type" => "measurebox",
			    "unit" => "px"
		    )
	    );
	    $field_typography_section = $field_section->addControlSection("field_typography", __("Typography"), "assets/icon.png", $this);
	    $field_typography_section->addPreset(
		    "typography",
		    "field_typography",
		    __("Typography"),
		    "input, select, button, textarea"
	    )->whiteList();
	    $field_background_section = $field_section->addControlSection("field_background", __("Background"), "assets/icon.png", $this);
	    $field_background_section->addPreset(
            "background",
            "field_background",
            __(""),
            'input, select, button, textarea'
        );


	    $validation_message_section = $this->addControlSection("validation", __("Validation Message"), "assets/icon.png", $this);

	    $validation_spacing_section = $validation_message_section->addControlSection("validation_spacing", __("Spacing"), "assets/icon.png", $this);
	    $validation_spacing_section->addPreset(
		    "padding",
		    "validation_padding",
		    __("Validation Message Padding"),
		    ".power-form-field-wrapper span"
	    )->whiteList();
	    $validation_spacing_section->addPreset(
		    "margin",
		    "validation_margin",
		    __("Validation Message Margin"),
		    ".power-form-field-wrapper span"
	    )->whiteList();
	    $validation_border_section = $validation_message_section->addControlSection("validation_border", __("Borders"), "assets/icon.png", $this);
	    $validation_border_section->addPreset(
		    "border",
		    "validation_border",
		    __("Validation Message Borders"),
		    ".power-form-field-wrapper span"
	    )->whiteList();
	    $validation_typography_section = $validation_message_section->addControlSection("validation_typography", __("Typography"), "assets/icon.png", $this);
	    $validation_typography_section->addPreset(
		    "typography",
		    "Validation_typography",
		    __("Typography"),
		    ".power-form-field-wrapper span"
	    )->whiteList();
	    $validation_message_section->addCustomControl('<div ng-click="oxyPowerPack.previewRequiredMessages()" class="oxygen-apply-button">Toggle Required Message</div>');


    }
    function afterInit() {
        if(method_exists('OxygenElementControl', 'rebuildElementOnChange')) $this->removeApplyParamsButton();
    }
}
global $oxyPowerPackComponents;
$oxyPowerPackComponents['oxy-power-form-field'] = new OxyPowerPackPowerFormField();
