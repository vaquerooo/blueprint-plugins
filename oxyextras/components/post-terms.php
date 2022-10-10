<?php

class ExtraPostTerms extends OxygenExtraElements {
        

	function name() {
        return 'Post Terms';
    }
    
    /* function icon() {
        return plugin_dir_url(__FILE__) . 'assets/'.basename(__FILE__, '.php').'.svg';
    } */
    
    function extras_button_place() {
        return "single";
    }
    
    function tag() {
        return array('default' => 'span', 'choices' => 'div,p,span' );
    }
    
    function init() {
        
        add_filter("oxy_allowed_empty_options_list", array( $this, "allowedEmptyOptions") );
    
    }

    function render($options, $defaults, $content) {
        
        // get options
        $taxonomy  = isset( $options['taxonomy'] ) ? esc_attr($options['taxonomy']) : '';
        $taxonomy_term_before = esc_html($options['taxonomy_term_before']);
        $taxonomy_term_after = esc_html($options['taxonomy_term_after']);
        $taxonomy_term_sep = isset( $options['taxonomy_term_sep'] ) ? esc_attr($options['taxonomy_term_sep']) : '';

        $taxonomy_term_links = (isset( $options['taxonomy_term_links'] ) && $options["taxonomy_term_links"] === "false" ) ? false : true;
        
        
            if ($taxonomy_term_links) {

                $output = get_the_term_list( get_the_ID(), $taxonomy, $taxonomy_term_before . ' ', $taxonomy_term_sep. ' ', ' ' . $taxonomy_term_after );

            } else {
                    
                $terms = get_the_terms( get_the_ID(), $taxonomy );

                if ( is_wp_error( $terms ) ) {
                    return $terms;
                }

                $term_names = array();

                foreach ( $terms as $term ) {
                    
                    $term_names[] = $term->name;
                }
                
                if ( empty( $terms ) ) {
                    $output = '';
                } else {
                    $output = $taxonomy_term_before . ' ' . join( $taxonomy_term_sep . ' ', $term_names ) . ' ' . $taxonomy_term_after;
                }
                

            }

            if (!$output) {
                    echo $taxonomy_term_before . ' none '.$taxonomy_term_after;
            } else {
                echo $output;
            }

        }

    function class_names() {
        return array();
    }

    function controls() {
        
        $taxonomy_term_links_selector = "a";
        
        $all_public_taxonomy_terms = get_taxonomies(array('public' => true),'names');
        
        $taxonomy_terms = array_diff($all_public_taxonomy_terms, array("post_format"));
        
        $dropdown_options = array();
        foreach ($taxonomy_terms as $taxonomy_term)
        {
            $dropdown_options[$taxonomy_term] = $taxonomy_term;
        }

        $this->addOptionControl(
            array(
                "type" => "dropdown",
                "name" => "Taxonomy",
                "slug" => "taxonomy"
            )
        )->setValue($dropdown_options)->rebuildElementOnChange();
        
        $this->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Before Text'),
                "slug" => 'taxonomy_term_before',
                "default" => 'Categories: ',
                "base64" => true
            )
        )->rebuildElementOnChange();
        
        $this->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('After Text'),
                "slug" => 'taxonomy_term_after',
                "default" => '',
                "base64" => true
            )
        )->rebuildElementOnChange();
        
        $this->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Seperator'),
                "slug" => 'taxonomy_term_sep',
                "default" => ', ',
            )
        )->rebuildElementOnChange();
        
        $this->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Term Links',
                'slug' => 'taxonomy_term_links')
            
        )->setValue(array( "true" => "Enabled", "false" => "Disabled"))
         ->setDefaultValue('true')->rebuildElementOnChange();
        
        $this->typographySection('Link Typography', $taxonomy_term_links_selector,$this);

    }
    
    function afterInit() {
        $this->removeApplyParamsButton();
    }
    
    function allowedEmptyOptions($options) {

        $options_to_add = array(
            "oxy-post-terms_taxonomy_term_before",
            "oxy-post-terms_taxonomy_term_after",
            "oxy-post-terms_taxonomy_term_sep"
        );

        $options = array_merge($options, $options_to_add);

        return $options;
    }

}

new ExtraPostTerms();