<?php
defined('ABSPATH') or die();

class OxyPowerPackContactForm7 extends OxyPowerPackEl
{
    function init() {

    }

    function button_place() {
        return "oxypowerpack::basic";
    }

    function button_priority() {
        return 1;
    }

	function description() {
		return "Contact Form 7 component allows you to easily style CF7 forms.";
	}

    function name() {
        return "Contact Form 7";
    }

    function icon() {
        return plugin_dir_url(__FILE__) . '../assets/img/icon-contactform7.svg';
    }

    function options(){
        return array(
            "server_side_render" => true
        );
    }

    function render(){
        $form_id = $this->El->defaults[$this->getSlug().'_form'];
        echo do_shortcode('[contact-form-7 id="'.$form_id.'"]');
    }

    function controls(){

        $args = array(
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => 'wpcf7_contact_form',
            'post_status' => 'publish'
        );
        $forms = new WP_Query($args);
        $dropdown_options = array();
        foreach ($forms->posts as $form)
        {
            $dropdown_options[$form->ID] = $form->post_title;
        }

        $this->addOptionControl(
            array(
                "type" => "dropdown",
                "name" => "Contact Form 7 Form",
                "slug" => "form"
            )
        )->setValue($dropdown_options);

        $this->typographySection('Label Typography','label',$this );

        $form_control_selector = '.wpcf7-form-control';
        $form_control_section = $this->addControlSection('cf7_form_controls', __("Form Controls"), "assets/icon.png", $this);
        $form_control_section->addStyleControls(
            array(
                array(
                    "name" => __('Background Color'),
                    "property" => 'background-color',
                    "selector" => $form_control_selector,
                    "default" => ""
                )
            )
        );
        $form_control_section->typographySection('Typography', $form_control_selector,$this);
        $form_control_section->borderSection('Border', $form_control_selector,$this);
        $form_control_section->boxShadowSection('Box Shadow', $form_control_selector,$this);
        $form_control_section->addStyleControl(array(
            "property" => 'margin-top',
            "selector" => $form_control_selector
        ))->setValue('2');
        $form_control_section->addStyleControl(array(
            "property" => 'margin-right',
            "selector" => $form_control_selector
        ))->setValue('2');
        $form_control_section->addStyleControl(array(
            "property" => 'margin-bottom',
            "selector" => $form_control_selector
        ))->setValue('2');
        $form_control_section->addStyleControl(array(
            "property" => 'margin-left',
            "selector" => $form_control_selector
        ))->setValue('2');
        $form_control_section->addStyleControl(array(
            "property" => 'padding-top',
            "selector" => $form_control_selector
        ))->setValue('2px');
        $form_control_section->addStyleControl(array(
            "property" => 'padding-right',
            "selector" => $form_control_selector
        ))->setValue('2px');
        $form_control_section->addStyleControl(array(
            "property" => 'padding-bottom',
            "selector" => $form_control_selector
        ))->setValue('2px');
        $form_control_section->addStyleControl(array(
            "property" => 'padding-left',
            "selector" => $form_control_selector
        ))->setValue('2px');

        $submit_selector = '.wpcf7-submit';
        $submit_section = $this->addControlSection('cf7_form_submit', __("Submit Button"), "assets/icon.png", $this);
        $submit_section->addStyleControls(
            array(
                array(
                    "name" => __('Background Color'),
                    "property" => 'background-color',
                    "selector" => $submit_selector,
                    "default" => ""
                )
            )
        );

        $submit_section->typographySection('Typography', $submit_selector,$this);
        $submit_section->borderSection('Border', $submit_selector,$this);
        $submit_section->boxShadowSection('Box Shadow', $submit_selector,$this);

        $response_output_selector = '.wpcf7-response-output';
        $response_output_section = $this->addControlSection("cf7_response_output", __("Response Output"), "assets/icon.png", $this);

        $response_output_section->addStyleControls(
            array(
                array(
                    "name" => __('Background Color'),
                    "property" => 'background-color',
                    "selector" => $response_output_selector,
                    "default" => ""
                ),
                array(
                    "name" => __('Success Color'),
                    "property" => 'border-color',
                    "selector" => '.wpcf7-mail-sent-ok',
                    "default" => ""
                ),
                array(
                    "name" => __('Error / Aborted color'),
                    "property" => 'border-color',
                    "selector" => '.wpcf7-mail-sent-ng,.wpcf7-aborted,.wpcf7-spam-blocked',
                    "default" => ""
                ),
                array(
                    "name" => __('Validation Error Color'),
                    "property" => 'border-color',
                    "selector" => '.wpcf7-validation-errors',
                    "default" => ""
                )
            )
        );
        $response_output_section->typographySection('Typography', $response_output_selector,$this);
        $response_output_section->borderSection('Border', $response_output_selector,$this);
        $response_output_section->boxShadowSection('Box Shadow', $response_output_selector,$this);

        // manually add button to re-render element anytime
        $this->addApplyParamsButton();
    }
}
global $oxyPowerPackComponents;
if(defined('WPCF7_VERSION')) $oxyPowerPackComponents['oxy-contact-form-7'] = new OxyPowerPackContactForm7();
