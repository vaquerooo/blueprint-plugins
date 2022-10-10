<?php

class ExtraReadingTime extends OxygenExtraElements {
        

	function name() {
        return 'Reading Time';
    }
    
    /*function icon() {
        return plugin_dir_url(__FILE__) . 'assets/'.basename(__FILE__, '.php').'.svg';
    }*/
    
    function extras_button_place() {
        return "single";
    }
    
    function tag() {
        return array('default' => 'div', 'choices' => 'div,p,span' );
    }
    
    function init() {
        
        add_filter("oxy_allowed_empty_options_list", array( $this, "allowedEmptyOptions") );
    
    }

    function render($options, $defaults, $content) {
        
        //get options
        $text_after_singular = isset( $options['text_after_singular'] ) ? esc_attr($options['text_after_singular']) : "minute";
        $text_after_plural = isset( $options['text_after_plural'] ) ? esc_attr($options['text_after_plural']) : "minutes";
        
        global $post;
        
        $before = esc_attr($options['before']);
        $wpm = intval($options['wpm']);
    
        $content = get_post_field( 'post_content', $post->ID );
        $word_count = str_word_count( strip_tags( $content ) );
        
        $readingtime = ceil($word_count / $wpm);
        if ($readingtime == 1) {
            $timer = " ". $text_after_singular;
        } else {
            $timer = " ". $text_after_plural;
        }
        
        $output = $before . ' ' .$readingtime . $timer;
            
        echo $output;    
        
    }

    function class_names() {
        return array();
    }

    function controls() {
        
        $this->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Text Before'),
                "slug" => 'before',
                "default" => 'Est. Reading:  ',
                "base64" => true
            )
        )->rebuildElementOnChange();
        
        $this->addOptionControl(
            array(
                "type" => 'slider-measurebox',
                "name" => __('Words Per Minute (avg is 275)'),
                "slug" => 'wpm',
                "value" => '275',
            )
        )->setRange('100','500','1')->rebuildElementOnChange();
        
        $this->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Text After (Singular)'),
                "slug" => 'text_after_singular',
                "default" => 'minute',
                "base64" => true
            )
        )->rebuildElementOnChange();
        
        $this->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Text After (Plural)'),
                "slug" => 'text_after_plural',
                "default" => 'minutes',
                "base64" => true
            )
        )->rebuildElementOnChange();
        
        $this->typographySection('Typography', '',$this);
        
        $this->addTagControl();

    }
    
    function allowedEmptyOptions($options) {

        $options_to_add = array(
            "oxy-reading-time_before",
        );

        $options = array_merge($options, $options_to_add);

        return $options;
    }
    
    function afterInit() {
        $this->removeApplyParamsButton();
    }

}

new ExtraReadingTime();