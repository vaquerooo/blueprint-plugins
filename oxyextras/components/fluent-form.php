<?php

class ExtraFluentForm extends OxygenExtraElements {

	function name() {
        return __('Fluent Form'); 
    }
    
    /* function icon() {
        return plugin_dir_url(__FILE__) . 'assets/'.basename(__FILE__, '.php').'.svg';
    } 
    */
    
    function enablePresets() {
        return true;
    }
    
    function enableFullPresets() {
        return true;
    }
    
    function init() {
        
        // include forms CSS/JS only for builder as they're not loaded by default
        if (isset( $_GET['oxygen_iframe'] )) {
			add_action( 'wp_footer', array( $this, 'output_styles_scripts' ) );
		}
        
    }
    
     function afterInit() {
        //$this->removeApplyParamsButton();
    }
    
    
    function extras_button_place() {
        return "other";
    }
    
    function render($options, $defaults, $content) {
        
        // Get ID
        $fluent_form_id =  isset( $options['fluent_form_id'] ) ? esc_attr($options['fluent_form_id']) : "";
        $acf_form_field =  isset( $options['acf_form_field'] ) ? $options['acf_form_field'] : "";
        $form_source = isset( $options['form_source'] ) ? esc_attr($options['form_source']) : "manual";
        
        //$acf_form_id =  class_exists( 'acf' ) ? intval(get_field($acf_form_field)) : '';
        
         // Get the value from the textfield
        //$start_field = isset( $options['start'] ) ? $options['start'] : ''; 
        $acf_form_field =  isset( $options['acf_form_field'] ) ? $options['acf_form_field'] : "";

        // Only do_shortcode if an Oxygen shortcode is found
        if( strstr( $acf_form_field, '[oxygen') ) {
            // We need to sign the shortcode, or else the do_shortcode will return nothing
            $acf_form_field = ct_sign_oxy_dynamic_shortcode(array($acf_form_field));
            $acf_form_id = do_shortcode($acf_form_field);
                
        } else {
            // Otherwise just output as is
            $acf_form_id = isset( $options['acf_form_field'] ) ? esc_attr($options['acf_form_field']) : '';
            
        }
        
        
        $form_id = ('manual' !== $form_source) ? $acf_form_id : $fluent_form_id;
        
        if (function_exists('wpFluent') ) {
        
            echo do_shortcode('[fluentform id=' . $form_id . ']');  

            
        } else {
            echo 'No Fluent Forms Found';
        }
        
    }

    function class_names() {
        return array('');
    }

    function controls() {
        
        /**
         * Form Source
         */
        $form_source_control = $this->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Form source',
                'slug' => 'form_source'
            )
        )->setValue(array( "manual" => "Select form", "acf" => "Form ID" ));
        $form_source_control->setDefaultValue('manual')->rebuildElementOnChange();
        
        /**
         * Form Source
         */
        $this->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Form ID'),
                "slug" => 'acf_form_field',
                "condition" => 'form_source=acf'
            )
        )->rebuildElementOnChange()->setParam('dynamicdatacode', '<div class="oxygen-dynamic-data-browse" ctdynamicdata data="iframeScope.dynamicShortcodesContentMode" callback="iframeScope.insertShortcodeToFormID">data</div>');
        
        
        /**
         * Form ID
         */
        
        $dropdown_options = array();
        
        if (function_exists('wpFluent') ) {
        
            $fluentForms = wpFluent()->table( 'fluentform_forms' )
            ->select( ['id', 'title'] )
            ->orderBy( 'id', 'DESC' )
            ->get();
            
            foreach ( $fluentForms as $fluentform ) {
                //$dropdown_options[$fluentform->id] = $fluentform->title;
                // remove any strange characters from form name and replace spaces with &#8205; incase users use words that break with angular such as 'as'.
                 $dropdown_options[$fluentform->id] = str_replace(' ', '&#8205; ', preg_replace("/[^a-zA-Z0-9\s]+/", "", $fluentform->title));
                                                     
                
            }
            
        } else {
            $dropdown_options['noforms'] = esc_attr('No fluent forms');
        }

        $this->addOptionControl(
            array(
                "type" => "dropdown",
                "name" => "Form",
                "slug" => "fluent_form_id",
                "condition" => 'form_source!=acf'
            )
        )->setValue($dropdown_options)->rebuildElementOnChange();
        
        /*$this->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Text'),
                "slug" => 'text',
                "default" => '%',
               // "" => '<div class="oxygen-dynamic-data-browse" ctdynamicdata data="iframeScope.dynamicShortcodesContentMode" callback="iframeScope.insertShortcodeToMapAddress">data</div>',
            )
        )->setParam('dynamicdatacode', '<div class="oxygen-dynamic-data-browse" ctdynamicdata data="iframeScope.dynamicShortcodesContentMode" callback="iframeScope.insertShortcodeToImageAlt">data</div>');
        */
        
        
        
        /**
         * Inputs
         */
        $input_section = $this->addControlSection("input_section", __("Inputs"), "assets/icon.png", $this);
        $input_selector = '.fluentform .ff-el-form-control';
        
        $input_inherit_font_control = $input_section->addOptionControl(
            array(
                "type" => 'checkbox',
                "name" => __('Inherit Font Family'),
                "slug" => 'input_inherit_font',
                "value" => 'false'
            )
        );
        $input_inherit_font_control->setValueCSS( array(
            "true"  => " textarea,
                         button,
                         select,
                         input {
                            font-family: inherit;
                        }
                    
               ",
        ) );
        
        
        $input_section->typographySection('Typography', $input_selector,$this);
        
        
        $input_spacing_section = $input_section->addControlSection("input_spacing_section", __("Spacing"), "assets/icon.png", $this);
        $input_spacing_section->addPreset(
            "padding",
            "input_padding",
            __("Padding"),
            $input_selector
        )->whiteList();
        
        $input_spacing_section->addPreset(
            "margin",
            "input_margin",
            __("Margin"),
            $input_selector
        )->whiteList();
        
        $input_colors_section = $input_section->addControlSection("input_colors_section", __("Colors"), "assets/icon.png", $this);
        
        $input_colors_section->addStyleControls(
             array( 
                array(
                    "name" => 'Background Color',
                    "selector" => $input_selector,
                    "property" => 'background-color',
                ),
                array(
                    "name" => 'Hover Background  Color',
                    "selector" => $input_selector.":hover",
                    "property" => 'background-color',
                ),
                 array(
                    "name" => 'Focus Background Color',
                    "selector" => $input_selector.":focus",
                    "property" => 'background-color',
                ),
                 array(
                    "name" => 'Invalid Background Color',
                    "selector" => $input_selector.":invalid",
                    "property" => 'background-color',
                ),
                 array(
                    "name" => 'Color',
                    "selector" => $input_selector,
                    "property" => 'color',
                ),
                array(
                    "name" => 'Hover Color',
                    "selector" => $input_selector.":hover",
                    "property" => 'color',
                ),
                 array(
                    "name" => 'Focus Color',
                    "selector" => $input_selector.":focus",
                    "property" => 'color',
                ),
                 array(
                    "name" => 'Invalid Color',
                    "selector" => $input_selector.":invalid",
                    "property" => 'color',
                ),
                 array(
                    "name" => 'Placeholder Color',
                    "selector" => $input_selector."::placeholder",
                    "property" => 'color',
                )
                 
            )
        );
        
        $input_section->borderSection('Borders', $input_selector,$this);
        $input_section->boxShadowSection('Shadows', $input_selector,$this);
        $input_section->borderSection('Focus Borders', $input_selector.":focus",$this);
        $input_section->boxShadowSection('Focus Shadows', $input_selector.":focus",$this);
        $input_section->borderSection('Invalid Borders', $input_selector.":invalid",$this);
        $input_section->boxShadowSection('Invalid Shadows', $input_selector.":invalid",$this);
        
        
        /**
         * Labels
         */
        $label_section = $this->addControlSection("label_section", __("Labels"), "assets/icon.png", $this);
        $label_selector = '.fluentform .ff-el-input--label label';
        $label_section->typographySection('Typography', $label_selector,$this);
        
        $label_spacing_section = $label_section->addControlSection("label_spacing_section", __("Spacing"), "assets/icon.png", $this);
        
        $label_spacing_section->addPreset(
            "margin",
            "label_margin",
            __("Margin"),
            $label_selector
        )->whiteList();
        
        $label_section->addStyleControls(
             array( 
                array(
                    "name" => 'Label Color',
                    "selector" => $label_selector,
                    "property" => 'color',
                ),
                array(
                    "name" => 'Asterisk Color',
                    "selector" => ".fluentform .ff-el-input--label.ff-el-is-required label:before, .fluentform .ff-el-input--label.ff-el-is-required label:after",
                    "property" => 'color',
                )
            )
        );
        
        $label_tooltip_section = $label_section->addControlSection("label_tooltip_section", __("Label Tooltips"), "assets/icon.png", $this);
        $label_tooltip_selector = '.fluentform .ff-el-tooltip';
        
        $label_tooltip_section->addStyleControls(
             array( 
                 array(
                    "name" => 'Icon Color',
                    "selector" => $label_tooltip_selector,
                    "property" => 'color',
                ),
                 array(
                    "name" => 'Tooltip Font Size',
                    "selector" => $label_tooltip_selector.":before",
                    "property" => 'font-size',
                )
            )
        );
        
        
        /**
         * Submit Button
         */
        $submit_section = $this->addControlSection("submit_section", __("Submit"), "assets/icon.png", $this);
        $submit_selector = '.fluentform .ff-btn-submit';
        
        
        $submit_section->typographySection('Typography', $submit_selector,$this);
        
        $submit_colors_section = $submit_section->addControlSection("submit_colors_section", __("Colors"), "assets/icon.png", $this);
        
        $submit_colors_section->addStyleControls(
             array( 
                array(
                    "name" => 'Background Color',
                    "selector" => $submit_selector,
                    "property" => 'background-color',
                ),
                array(
                    "name" => 'Hover Background  Color',
                    "selector" => $submit_selector.":hover",
                    "property" => 'background-color',
                ),
                 array(
                    "name" => 'Focus Background  Color',
                    "selector" => $submit_selector.":focus",
                    "property" => 'background-color',
                ),
                 array(
                    "name" => 'Color',
                    "selector" => $submit_selector,
                    "property" => 'color',
                ),
                array(
                    "name" => 'Hover Color',
                    "selector" => $submit_selector.":hover",
                    "property" => 'color',
                ),
                 array(
                    "name" => 'Focus Color',
                    "selector" => $submit_selector.":focus",
                    "property" => 'color',
                ) 
                 
            )
        );
        
        $submit_spacing_section = $submit_section->addControlSection("submit_spacing_section", __("Spacing"), "assets/icon.png", $this);
        
        $submit_spacing_section->addPreset(
            "padding",
            "submit_padding",
            __("Padding"),
            $submit_selector
        )->whiteList();
        
        $submit_spacing_section->addPreset(
            "margin",
            "submit_margin",
            __("Margin"),
            $submit_selector
        )->whiteList();
        
        $submit_section->borderSection('Borders', $submit_selector,$this);
        $submit_section->boxShadowSection('Shadows', $submit_selector,$this);
        
        $submit_section->borderSection('Hover Borders', $submit_selector.":hover",$this);
        $submit_section->borderSection('Focus Borders', $submit_selector.":focus",$this);
        
        
        $submit_section->addStyleControls(
             array( 
                array(
                    "selector" => $submit_selector,
                    "property" => 'width',
                ),
                array(
                    "name" => 'Hover Opacity',
                    "selector" => $submit_selector.":hover",
                    "default" => '.8',
                    "property" => 'opacity',
                ) 
                 
            )
        );
        
        
       
        
        
        /**
         * Checkboxes 
         */
        
        
        $checkboxes = $this->addControlSection("checkboxes", __("Radio & Checkboxes"), "assets/icon.png", $this);
        $checkboxes_selector = '.fluentform .ff-el-form-check';
        $checkboxes_after_selector = '.fluentform .ff-el-group input[type=checkbox]:after, .fluentform .ff-el-group input[type=radio]:after';
        
        
        $smart_ui_control = $checkboxes->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Smart UI',
                'slug' => 'smart_ui'
            )
        )->setValue(array( "enable" => "Enable", "disable" => "Disable" ));
        $smart_ui_control->setDefaultValue('disable');
        $smart_ui_control->setValueCSS( array(
            "enable"  => " .ff-el-group input[type=checkbox]::after {
                                content: '';
                                display: inline-block;
                            }
                           .ff-el-group input[type=radio]::after {
                                content: '';
                                display: inline-block;
                            }
                            .ff-el-group input[type=radio],
                            .ff-el-group input[type=checkbox] {
                                appearance: none ;
                                -moz-appearance: none ;
                                -webkit-appearance: none ;
                                height: unset;
                                width: unset;
                                visibility: hidden;
                            }
                       ",
        ) );
        
        
        
        $checkboxes->typographySection('Typography', $checkboxes_selector,$this);
        
        $checkboxes->addStyleControl( 
            array(
                "name" => 'Checkbox / Radio Size',
                "type" => 'measurebox',
                "default" => "15",
                "units" => 'px',
                "property" => 'width|height',
                "control_type" => 'slider-measurebox',
                "selector" => '.ff-el-group input[type=checkbox]::after,.ff-el-group input[type=radio]::after',
                 "condition" => 'smart_ui=enable'
            )
        )
        ->setUnits('px','px')    
        ->setRange('15','100','1');
        
        
        $checkboxes->addStyleControls(
             array( 
                array(
                   // "name" => 'Border Color',
                    "selector" => $checkboxes_after_selector,
                    "property" => 'border-width',
                    "default" => '1',
                    "condition" => 'smart_ui=enable'
                ), 
                array(
                    "name" => 'Border Color',
                    "selector" => $checkboxes_after_selector,
                    "property" => 'border-color',
                    "condition" => 'smart_ui=enable'
                ),
                 array(
                    "name" => 'Background Color',
                    "selector" => $checkboxes_after_selector,
                    "property" => 'background-color',
                    "condition" => 'smart_ui=enable'
                ),
                array(
                    "name" => 'Checked Border Color',
                    "selector" => ".fluentform .ff-el-group input[type=checkbox]:checked:after, .fluentform .ff-el-group input[type=radio]:checked:after",
                    "property" => 'border-color',
                    "condition" => 'smart_ui=enable'
                ), 
                array(
                    "name" => 'Checked Background Color',
                    "selector" => ".fluentform .ff-el-group input[type=checkbox]:checked:after, .fluentform .ff-el-group input[type=radio]:checked:after",
                    "property" => 'background-color',
                    "condition" => 'smart_ui=enable'
                )
                 
            )
        );
        
        
        
        /**
         * Checkbox Spacing
         */
        $checkboxes_spacing_section = $checkboxes->addControlSection("checkboxes_spacing_section", __("Checkbox Spacing"), "assets/icon.png", $this);
        $checkboxes_spacing_selector = '.fluentform .ff-el-form-check-input';
        
        
        
        $checkboxes_spacing_section->addPreset(
            "margin",
            "checkbox_margin",
            __("Margin"),
            $checkboxes_spacing_selector
        )->whiteList();
        
        /**
         * Checkbox Option Spacing
         */
        $checkboxes_option_spacing_section = $checkboxes->addControlSection("checkboxes_option_spacing_section", __("Option Spacing"), "assets/icon.png", $this);
        $checkboxes_option_spacing_selector = '.fluentform .ff-el-form-check';
        
        $checkboxes_option_spacing_section->addPreset(
            "padding",
            "checkbox_option_padding",
            __("Padding"),
            $checkboxes_option_spacing_selector
        )->whiteList();
        
        
         
        /**
         * Message Styles 
         */
        
        $after_submit_success_section = $this->addControlSection("after_submit_success_section", __("Messages"), "assets/icon.png", $this);
        $after_submit_success_selector = '.ff-message-success';
        $error_selector = '.fluentform .text-danger';
        
        
        $after_submit_success_section->addStyleControls(
             array( 
                array(
                    "name" => 'Success Background Color',
                    "selector" => $after_submit_success_selector,
                    "property" => 'background-color',
                )
            )
        );  
        
        $after_submit_success_spacing = $after_submit_success_section->addControlSection("after_submit_success_spacing", __("Success Spacing"), "assets/icon.png", $this);
        
        $after_submit_success_spacing->addPreset(
            "padding",
            "success_padding",
            __("Padding"),
            $after_submit_success_selector
        )->whiteList();
        
        $after_submit_success_spacing->addPreset(
            "margin",
            "success_margin",
            __("Margin"),
            $after_submit_success_selector
        )->whiteList();
        
        
        $after_submit_success_section->borderSection('Success Borders', $after_submit_success_selector,$this);
        $after_submit_success_section->boxShadowSection('Success Shadows', $after_submit_success_selector,$this);
        $after_submit_success_section->typographySection('Success Typography', $after_submit_success_selector,$this);
        
        $error_spacing = $after_submit_success_section->addControlSection("error_spacing", __("Error Spacing"), "assets/icon.png", $this);
        $error_spacing->addPreset(
            "margin",
            "error_margin",
            __("Margin"),
            $checkboxes_option_spacing_selector
        )->whiteList();
        
        $after_submit_success_section->typographySection('Error Typography', $error_selector,$this);
        
        
         /**
         * Net Promoter Score
         */
        $promoter = $this->addControlSection("promoter", __("Net Promoter Score"), "assets/icon.png", $this);
        
        $promoter->addStyleControls(
             array( 
                array(
                    "name" => 'Hover Border Color',
                    "selector" => '.fluentform .ff_net_table tbody tr td label:hover:after',
                    "property" => 'border-color',
                ),
                array(
                    "name" => 'Checked Background Color',
                    "selector" => ".fluentform .ff_net_table tbody tr td input[type=radio]:checked+label",
                    "property" => 'background-color',
                ),
                 array(
                    "name" => 'Checked Color',
                    "selector" => ".fluentform .ff_net_table tbody tr td input[type=radio]:checked+label",
                    "property" => 'color',
                ),
                 array(
                    "selector" => ".fluentform .ff_net_table tbody tr td label",
                    "property" => 'font-size',
                ),
                 array(
                    "selector" => ".fluentform .ff_net_table tbody tr td label",
                    "property" => 'height',
                    "default" => '40',
                )
                 
            )
        );  
        
        
         
        /**
         * Multipage Buttons
         */
        $step_buttons = $this->addControlSection("step_buttons", __("Prev / Next Buttons"), "assets/icon.png", $this);
        $step_buttons_selector = '.fluentform .ff-btn-secondary';
        $step_buttons->typographySection('Typography', $step_buttons_selector,$this);
        
        
        $step_buttons->borderSection('Borders', $step_buttons_selector, $this);
        
        $step_buttons_colors = $step_buttons->addControlSection("step_buttons_colors", __("Colors"), "assets/icon.png", $this);
        
        $step_buttons_colors->addStyleControls(
             array( 
                array(
                    "name" => 'Background Color',
                    "selector" => $step_buttons_selector,
                    "property" => 'background-color',
                ),
                array(
                    "name" => 'Hover Background  Color',
                    "selector" => $step_buttons_selector.":hover",
                    "property" => 'background-color',
                ),
                 array(
                    "name" => 'Focus Background  Color',
                    "selector" => $step_buttons_selector.":focus",
                    "property" => 'background-color',
                ),
                 array(
                    "name" => 'Color',
                    "selector" => $step_buttons_selector,
                    "property" => 'color',
                ),
                array(
                    "name" => 'Hover Color',
                    "selector" => $step_buttons_selector.":hover",
                    "property" => 'color',
                ),
                 array(
                    "name" => 'Focus Color',
                    "selector" => $step_buttons_selector.":focus",
                    "property" => 'color',
                ) 
                 
            )
        );
        
        $step_buttons_spacing = $step_buttons->addControlSection("step_buttons_spacing", __("Size & Spacing"), "assets/icon.png", $this);
        $step_buttons_spacing->addPreset(
            "padding",
            "step_buttons_padding",
            __("Padding"),
            $step_buttons_selector
        )->whiteList();
        
        $step_buttons_spacing->addPreset(
            "margin",
            "step_buttons_margin",
            __("Margin"),
            $step_buttons_selector
        )->whiteList();
        
        $step_buttons_spacing->addStyleControls(
            array(
                array(
                    "property" => 'width',
                    "selector" => $step_buttons_selector,
                )
            )
        );
        
        
        /**
         * Multipage Progress
         */
        $step_progress = $this->addControlSection("step_progress", __("Progress Bar"), "assets/icon.png", $this);
        $step_progress_selector = '.fluentform .ff-el-progress-bar';
        $step_progress_status_selector = '.fluentform .ff-el-progress-status';
        
        $step_progress->addStyleControls(
             array( 
                array(
                    "name" => 'Active Color',
                    "selector" => $step_progress_selector,
                    "property" => 'background-color',
                ),
                array(
                    "name" => 'In-Active Color',
                    "selector" => $step_progress_selector,
                    "property" => 'background-color',
                ),
                 array(
                    //"name" => 'Progress Height',
                    "selector" => ".fluentform .ff-el-progress",
                    "property" => 'height',
                )
                 
            )
        );
        
        $step_progress->typographySection('Progess Typography', $step_progress_selector,$this);
        $step_progress->typographySection('Label Typography', $step_progress_status_selector,$this);
        
        $step_progress_layout = $step_progress->addControlSection("step_progress_layout", __("Inner Layout"), "assets/icon.png", $this);
        
        $step_progress_layout->flex($step_progress_selector, $this);
        
        
        /**
         * Multiselect
         
        $multiselect_section = $this->addControlSection("multiselect_section", __("Multiselect"), "assets/icon.png", $this);
    
        $multiselect_section->addStyleControls(
             array( 
                array(
                    "name" => 'Highlight Background Color',
                    "selector" => '.select2-container--default .select2-results__option--highlighted[data-selected]',
                    "property" => 'background-color',
                ),
                array(
                    "name" => 'Highlight Color',
                    "selector" => '.select2-container--default .select2-results__option--highlighted[data-selected]',
                    "property" => 'color',
                )
                 
            )
        ); */   
            
        /**
         * Payment
         */
        $payment_section = $this->addControlSection("payment_section", __("Payment Summary"), "assets/icon.png", $this);
        //$input_selector = '.fluentform .ff-el-form-control';
        
        $payment_section->addStyleControls(
             array( 
                array(
                    "selector" => '.ffp_table',
                    "default" => '15',
                    "property" => 'font-size',
                ),
                array(
                    "name" => 'Table Head Background',
                    "default" => '#e3e8ee',
                    "selector" => '.ffp_table thead',
                    "property" => 'background-color',
                ),
                 array(
                    "name" => 'Table Head Text Color',
                    "default" => '#ffffff',
                    "selector" => '.ffp_table thead',
                    "property" => 'color',
                ),
                 array(
                    "name" => 'Border Color',
                    "default" => '#cbcbcb',
                    "selector" => 'table.input_items_table tr td, table.input_items_table tr th, .ffp_table',
                    "property" => 'border-color',
                ),
                 array(
                    "name" => 'Border Width',
                    "default" => '1',
                    "selector" => 'table.input_items_table tr td, table.input_items_table tr th, .ffp_table',
                    "property" => 'border-width',
                )
                 
            )
        ); 
        
        
        
        $payment_spacing_section = $payment_section->addControlSection("payment_spacing_section", __("Spacing"), "assets/icon.png", $this);
        
        $payment_spacing_section->addPreset(
            "padding",
            "payment_cell_padding",
            __("Cell Padding"),
            '.ffp_table td, .ffp_table th'
        )->whiteList();
        
        
       /**
         * Checkable Grids
         */
        $checkable_grid_section = $this->addControlSection("checkable_grid_section", __("Checkable Grids"), "assets/icon.png", $this);
        $checkable_grid_selector = '.fluentform .ff-checkable-grids';
        
        $checkable_grid_section->addStyleControls(
             array( 
                array(
                    "selector" => $checkable_grid_selector,
                    "property" => 'font-size',
                ),
                array(
                    "name" => 'Table Head Background',
                    "default" => '#f1f1f1',
                    "selector" => '.fluentform .ff-checkable-grids thead>tr>th',
                    "property" => 'background-color',
                ),
                 array(
                    "name" => 'Table Head Text Color',
                    "selector" => '.fluentform .ff-checkable-grids thead>tr>th',
                    "property" => 'color',
                ),
                 array(
                    "name" => 'Alt Background Color',
                    "selector" => '.fluentform .ff-checkable-grids tbody>tr:nth-child(2n)>td',
                    "property" => 'background-color',
                ),
                 array(
                    "name" => 'Alt Text Color',
                    "selector" => '.fluentform .ff-checkable-grids tbody>tr:nth-child(2n)>td',
                    "property" => 'color',
                ),
                 array(
                    "name" => 'Border Color',
                    "default" => '#f1f1f1',
                    "selector" => $checkable_grid_selector,
                    "property" => 'border-color',
                ),
                 array(
                    "name" => 'Border Width',
                    "default" => '1',
                    "selector" => $checkable_grid_selector,
                    "property" => 'border-width',
                )
                 
            )
        ); 
        
        $checkable_grid_spacing = $checkable_grid_section->addControlSection("checkable_grid_spacing", __("Spacing"), "assets/icon.png", $this);
        
        
        $checkable_grid_spacing->addPreset(
            "padding",
            "grid_cell_padding",
            __("Cell Padding"),
            '.fluentform .ff-checkable-grids tbody>tr>td'
        )->whiteList();
        
        
        
        
         /**
         * Uploads
         */
        $upload_section = $this->addControlSection("upload_section", __("Uploads"), "assets/icon.png", $this);
        $upload_selector = '.fluentform .ff_upload_btn.ff-btn';
        
        $upload_section->addStyleControls(
             array( 
                
                 array(
                    "name" => 'Button Hover Opacity',
                    "default" => '0.8',
                    "selector" => $upload_selector.":hover",
                    "property" => 'opacity',
                ) 
                 
            )
        ); 
        
        $upload_colors = $upload_section->addControlSection("upload_colors", __("Button Colors"), "assets/icon.png", $this);
        
        $upload_colors->addStyleControls(
             array( 
                array(
                    "selector" => $upload_selector,
                    "default" => '#6f757e',
                    "property" => 'background-color',
                ),
                array(
                    "name" => 'Hover Background Color',
                    "selector" => $upload_selector.":hover",
                    "property" => 'background-color',
                ),
                array(
                    "name" => 'Text Color',
                    "selector" => $upload_selector,
                    "default" => '#ffffff',
                    "property" => 'color',
                ),
                array(
                    "name" => 'Hover Text Color',
                    "selector" => $upload_selector.":hover",
                    "property" => 'color',
                )
                 
            )
        ); 
        
        $upload_section->borderSection('Borders', $upload_selector,$this);
        $upload_section->boxShadowSection('Shadows', $upload_selector,$this);
        $upload_section->borderSection('Hover Borders', $upload_selector.":hover",$this);
        $upload_section->boxShadowSection('Hover Shadows', $upload_selector.":hover",$this);
        $upload_section->typographySection('Typography', $upload_selector,$this);
        
        
        /**
         * Form Structure
         */
        $form_structure = $this->addControlSection("form_structure", __("Overall Styles"), "assets/icon.png", $this);
        $form_structure_selector = '.fluentform';
        
        $group_spacing_selector = '.fluentform .ff-el-group';
        $cell_spacing_selector = '.frm-fluent-form .ff-t-cell';
        
         $form_structure->addStyleControl( 
            array(
                "name" => 'Group Margin Bottom',
                "type" => 'measurebox',
                "default" => "15",
                "units" => 'px',
                "property" => 'margin-bottom',
                "control_type" => 'slider-measurebox',
                "selector" => $group_spacing_selector,
            )
        )
        ->setUnits('px','px')    
        ->setRange('1','60','1');
        
        $form_structure->addStyleControl( 
            array(
                "name" => 'Cell Gap (Between Columns)',
                "type" => 'measurebox',
                "default" => "15",
                "units" => 'px',
                "property" => 'padding-left|padding-right',
                "control_type" => 'slider-measurebox',
                "selector" => $cell_spacing_selector,
            )
        )
        ->setUnits('px','px')    
        ->setRange('0','40','1');
        
        
        $form_hover_transitions = $form_structure->addStyleControl(
            array(
                "name" => __('Buttons Transition Duration'),
                "property" => 'transition-duration',
                "selector" => '.fluentform .ff-btn,',
                "control_type" => 'slider-measurebox',
                "default" => '150',
            )
        );

        $form_hover_transitions->setUnits('ms','ms');
        $form_hover_transitions->setRange(0, 1000, 1);
        
        $form_structure->addStyleControl(
            array(
                "name" => __('Buttons Transition Duration'),
                "property" => 'transition-timing-function',
                "selector" => '.fluentform .ff-btn,',
                "control_type" => 'dropdown',
                "default" => 'ease-in-out',
            )
        )->setValue(
           array( 
                "ease","ease-in","ease-out","ease-in-out","ease-in-out","linear",
           )
        );
        
        
        $form_structure->addStyleControls(
             array( 
                array(
                    "name" => 'h2 Font Size',
                    "selector" => 'h2',
                    "property" => 'font-size',
                ),
                array(
                    "name" => 'h3 Font Size',
                    "selector" => 'h3',
                    "property" => 'font-size',
                ),
                array(
                    "name" => 'h4 Font Size',
                    "selector" => 'h4',
                    "property" => 'font-size',
                )
                 
            )
        ); 
        
        
    }
        

	function output_styles_scripts() { // In Builder Only
        wp_enqueue_style('fluentform-public-default');
		wp_enqueue_style('fluent-form-styles');
		wp_enqueue_script('fluent-form-submission');
	}
    
    function defaultCSS() {
        
        return file_get_contents(__DIR__.'/'.basename(__FILE__, '.php').'.css');
          
    }
    
    
    function customCSS($options, $selector) {
        
        $css = ".oxy-fluent-form {
                    width: 100%;
                }
                
                .oxy-fluent-form .fluentform .ff-checkable-grids {
                    width: 100%;
                }
                
                .oxy-fluent-form .fluentform .ff-btn {
                    transition-property: all;
                }";
        
        $css .= "$selector select.ff-el-form-control:not([size]):not([multiple]){
                    height:auto;
                }
                
                $selector .fluentform .frm-fluent-form .ff-t-cell:last-of-type {
                    padding-right: 0;
                }
                
                $selector .fluentform .frm-fluent-form .ff-t-cell:first-of-type {
                    padding-left: 0;
                }
                
                $selector .fluentform .iti__flag-container + input[type=tel].ff-el-form-control {
                    padding-left: 52px;
                }
                
                @media (max-width: 768px) {
                
                    $selector .fluentform .frm-fluent-form .ff-t-cell {
                        padding-left: 0;
                        padding-right: 0;
                    }
                
                }
                    
                ";
        
               
        
        return $css;
    }
    
}

// All the parameters that can contain dynamic data, should be added to this filter
add_filter("oxy_base64_encode_options", 
    function($items) { 
        $items=array_merge($items, array('oxy-fluent-form_acf_form_field')); 
        return $items;
    }
);

new ExtraFluentForm();