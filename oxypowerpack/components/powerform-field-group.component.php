<?php
defined('ABSPATH') or die();

class OxyPowerPackPowerFormFieldGroup extends OxyPowerPackEl
{
    function init() {

        $this->enableNesting();
        $this->El->builderCSS(".oxy-power-form-field-group{min-height: 50px;}");
    }

    function tag() {
        return "div";
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
        return "";
    }

    function name() {
        return "Power Form Field Group";
    }

    function options(){
        return array(
            "server_side_render" => true
        );
    }

    function render($options, $defaults, $content){
        echo do_shortcode($content);
    }

    function controls(){

        $labels_section = $this->addControlSection("labels", __("Labels"), "assets/icon.png", $this);
        $label_position = $labels_section->addControl("buttons-list", "label_position", __("Label Position") );
        $label_position->setValue( array('top' => "Top",'left'=>"Left",'right'=>"Right",'none'=>"No labels") );
        $label_position->setDefaultValue('top');
        $label_position->setValueCSS( array(
            "top"  => "
                .label-and-field-wrapper{
                    flex-direction: column !important;
                }
                label{
                    width: auto;
                }",
            "left"  => "
                .label-and-field-wrapper{
                    flex-direction: row !important;
                }
                .power-form-field-wrapper{
                    flex-grow: 1 !important;
                }",
            "right"  => "
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

        $label_spacing_section = $labels_section->addControlSection("label_spacing", __("Spacing"), "assets/icon.png", $this);
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
        $label_border_section = $labels_section->addControlSection("label_border", __("Borders"), "assets/icon.png", $this);
        $label_border_section->addPreset(
            "border",
            "label_border",
            __("Label Borders"),
            "label"
        )->whiteList();
        $label_typography_section = $labels_section->addControlSection("label_typography", __("Typography"), "assets/icon.png", $this);
        $label_typography_section->addPreset(
            "typography",
            "label_typography",
            __("Typography"),
            "label"
        )->whiteList();

        $field_section = $this->addControlSection("fields", __("Input Fields"), "assets/icon.png", $this);

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


        $validation_message_section = $this->addControlSection("validation", __("Validation Messages"), "assets/icon.png", $this);

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
        $validation_message_section->addCustomControl('<div ng-click="oxyPowerPack.previewRequiredMessages()" class="oxygen-apply-button">Toggle Required Messages</div>');

    }
    function afterInit() {
        if(method_exists('OxygenElementControl', 'rebuildElementOnChange')) $this->removeApplyParamsButton();
    }
}
global $oxyPowerPackComponents;
$oxyPowerPackComponents['oxy-power-form-field-group'] = new OxyPowerPackPowerFormFieldGroup();
