<?php
defined('ABSPATH') or die();

class OxyPowerPackPowerForm extends OxyPowerPackEl
{
    function init() {
        //$this->El->inlineJS('window.oxypowerpack_mapbox_key = "' . get_option('oxypowerpack_mapbox_key', '') .'";' . file_get_contents( plugin_dir_path(__FILE__) . 'powermap/inline.js' ));
        $this->El->JS(array(
            plugin_dir_url(__FILE__) . 'powerform/script.js'
        ), array('jquery'));

        add_action('ct_toolbar_component_settings', function(){
            ?>
            <div ng-show="oxyPowerPack.activeComponentHasParentOfType('oxy-power-form')" ng-click="iframeScope.addComponent('oxy-power-form-field','',false,{});" class="oxygen-apply-button" style="margin-bottom: 2px;">Add Form Field</div>
            <div ng-show="oxyPowerPack.activeComponentHasParentOfType('oxy-power-form')" ng-click="iframeScope.addComponent('oxy-power-form-field-group','',false,{});" class="oxygen-apply-button">Add Field Group</div>
            <?php
        },11);

        $this->enableNesting();
        $this->El->builderCSS(".oxy-power-form{min-height: 50px;}.oxy-power-form-field label,.oxy-power-form-field input,.oxy-power-form-field select, .oxy-power-form-field button, .oxy-power-form-field textarea {pointer-events: none;}");
    }

    function tag() {
        return "form";
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
        return "Power Form";
    }

    function options(){
        return array(
            "server_side_render" => true
        );
    }

    function defaultCSS() {
        return file_get_contents( plugin_dir_path(__FILE__) . 'powerform/style.css' );
    }

    function render($options, $defaults, $content){
        if(!(isset($_GET['action']) && $_GET['action'] == 'oxy_render_oxy-power-form' ) ){
            $form_settings = Array(
                'form_name' => $options['form_name'],
                'save_to_database' => $options['save_to_database'],
                'admin_email_address' => $options['admin_email_address'],
                'admin_email_body' => utf8_encode($options['admin_email_body']),
                'user_email_field' => $options['user_email_field'],
                'user_email_body' => utf8_encode($options['user_email_body']),
                'send_email_admins' => $options['send_email_admins'],
                'field_admin_email_body' => utf8_encode($options['field_admin_email_body']),
                'send_email_user' => $options['send_email_user'],
                'field_user_email_body' => utf8_encode($options['field_user_email_body']),
                'selector' => $options['selector'],
                'trigger_webhook' => $options['trigger_webhook'],
                'webhook_url' => $options['webhook_url'],
                'webhook_payload' => $options['webhook_payload'],
                'admin_email_subject' => $options['admin_email_subject'],
                'user_email_subject' => $options['user_email_subject'],
                'show_alert_message' => $options['show_alert_message'],
                'alert_content' => $options['alert_content'],
            );
            $form_settings = json_encode($form_settings);
            $error = json_last_error_msg();
            $form_options = get_option('oxyPowerPackForms', []);
            $form_options[$options['selector']] = $form_settings;
            update_option('oxyPowerPackForms', $form_options);
            echo '<input type="hidden" name="oppForm" value="' . $options['selector'] . '">';
	        wp_nonce_field( 'oppForm_'. $options['selector']);
        }
        echo do_shortcode($content);
    }

    function controls(){

        $form_name_control = $this->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => 'Form Name',
                "slug" => 'form_name',
                "default" => 'Form',
            )
        );

        $labels_section = $this->addControlSection("labels", __("Labels"), "assets/icon.png", $this);
        $label_position = $labels_section->addControl("buttons-list", "label_position", __("Label Position") );
        $label_position->setValue( array('top' => "Top",'left'=>"Left",'right'=>"Right",'none'=>"No labels") );
        $label_position->setDefaultValue('top');
        $label_position->setValueCSS( array(
            "top"  => "
                .label-and-field-wrapper{
                    flex-direction: column;
                }
                label{
                    width: auto;
                }",
            "left"  => "
                .label-and-field-wrapper{
                    flex-direction: row;
                }
                .power-form-field-wrapper{
                    flex-grow: 1;
                }",
            "right"  => "
                .label-and-field-wrapper{
                    flex-direction: row-reverse;
                }
                .power-form-field-wrapper{
                    flex-grow: 1;
                }",
            "none" => "
                .power-form-field-wrapper{
                    flex-grow: 1;
                }
                .label-and-field-wrapper{
                    flex-direction: row;
                }
                label{
                    display: none;
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


	    $submission_section = $this->addControlSection("submission", __("Form Submission"), "assets/icon.png", $this);
	    $save_to_database_control = $submission_section->addOptionControl(
		    array(
			    "type" => 'checkbox',
			    "name" => 'Save to Database',
			    "slug" => 'save_to_database',
		    )
	    )->setValue('true');
	    $show_alert_control = $submission_section->addOptionControl(
		    array(
			    "type" => 'checkbox',
			    "name" => 'Show alert message on submission',
			    "slug" => 'show_alert_message',
		    )
	    )->setValue('true');
	    $alert_message_control = $submission_section->addOptionControl(
		    array(
			    "type" => 'textfield',
			    "name" => 'Alert message',
			    "slug" => 'alert_content',
			    "condition" => 'show_alert_message=true'
		    )
	    )->setValue('Form sent successfully!');

	    $admin_email_section = $submission_section->addControlSection("admin_email_section", __("Email for Admins"), "assets/icon.png", $this);

	    $send_email_control = $admin_email_section->addOptionControl(
		    array(
			    "type" => 'checkbox',
			    "name" => 'Send email to admins',
			    "slug" => 'send_email_admins',
		    )
	    );
	    $admin_email_field_control = $admin_email_section->addOptionControl(
		    array(
			    "type" => 'textfield',
			    "name" => 'Email Addresses',
			    "slug" => 'admin_email_address',
			    "condition" => 'send_email_admins=true'
		    )
	    )->setValue('example@domain.com');
	    $admin_email_subject_control = $admin_email_section->addOptionControl(
		    array(
			    "type" => 'textfield',
			    "name" => 'Email Subject',
			    "slug" => 'admin_email_subject',
			    "condition" => 'send_email_admins=true'
		    )
	    )->setValue('New Form Submission!');
	    $admin_email_body_control = $admin_email_section->addOptionControl(
		    array(
			    "type" => 'textfield',
			    "name" => 'Email Body',
			    "slug" => 'admin_email_body',
		    )
	    )->setValue('')->hidden();
	    $admin_email_section->addCustomControl('<div ng-show="iframeScope.component.options[iframeScope.component.active.id].model[\'oxy-power-form_send_email_admins\'] == \'true\'" ng-click="oxyPowerPack.richtext.open(\'oxy-power-form_admin_email_body\', \'Edit Email Body for Administrators\')" class="oxygen-apply-button">Edit Email Template for Admins</div>');

	    $user_email_section = $submission_section->addControlSection("user_email_section", __("Email for Users"), "assets/icon.png", $this);

	    $send_email_to_user_control = $user_email_section->addOptionControl(
		    array(
			    "type" => 'checkbox',
			    "name" => 'Send email to user',
			    "slug" => 'send_email_user',
		    )
	    );
	    $user_email_field_control = $user_email_section->addOptionControl(
		    array(
			    "type" => 'textfield',
			    "name" => 'Email Address Field',
			    "slug" => 'user_email_field',
                "condition" => 'send_email_user=true'
		    )
	    )->setValue('email_field');
	    $user_email_subject_control = $user_email_section->addOptionControl(
		    array(
			    "type" => 'textfield',
			    "name" => 'Email Subject',
			    "slug" => 'user_email_subject',
			    "condition" => 'send_email_user=true'
		    )
	    )->setValue('Message received!');
	    $user_email_body_control = $user_email_section->addOptionControl(
		    array(
			    "type" => 'textfield',
			    "name" => 'Email Body',
			    "slug" => 'user_email_body',
		    )
	    )->setValue('')->hidden();
	    $user_email_section->addCustomControl('<div ng-show="iframeScope.component.options[iframeScope.component.active.id].model[\'oxy-power-form_send_email_user\'] == \'true\'" ng-click="oxyPowerPack.richtext.open(\'oxy-power-form_user_email_body\', \'Edit Email Body for Users\')" class="oxygen-apply-button">Edit Email Template for Users</div>');

	    $webhook_section = $submission_section->addControlSection("webhook_section", __("WebHook"), "assets/icon.png", $this);

	    $trigger_webhook_control = $webhook_section->addOptionControl(
		    array(
			    "type" => 'checkbox',
			    "name" => 'Trigger WebHook',
			    "slug" => 'trigger_webhook',
		    )
	    );
	    $webhook_url_control = $webhook_section->addOptionControl(
		    array(
			    "type" => 'textfield',
			    "name" => 'WebHook URL',
			    "slug" => 'webhook_url',
			    "condition" => 'trigger_webhook=true'
		    )
	    )->setValue('https://');

	    $webhook_payload_control = $webhook_section->addOptionControl(
		    array(
			    "type" => 'dropdown',
			    "name" => 'Send POST payload as',
			    "slug" => 'webhook_payload',
			    "default" => 'json',
			    "condition" => 'trigger_webhook=true'
		    )
	    );
	    $webhook_payload_control->setValue(
		    array(
			    'json' => 'JSON',
			    'formencoded' => 'Form-Encoded'
		    )
	    );


    }
    function afterInit() {
        if(method_exists('OxygenElementControl', 'rebuildElementOnChange')) $this->removeApplyParamsButton();
    }
}
global $oxyPowerPackComponents;
$oxyPowerPackComponents['oxy-power-form'] = new OxyPowerPackPowerForm();
