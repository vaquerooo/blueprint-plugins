<?php

class ExtraSocial extends OxygenExtraElements {

	function name() {
        return 'Social Share Buttons';
    }

    /* function icon() {
        return plugin_dir_url(__FILE__) . 'assets/'.basename(__FILE__, '.php').'.svg';
    } */
    
    function extras_button_place() {
        return "other";
    }
    
    function enablePresets() {
        return true;
    }
    
    function enableFullPresets() {
        return true;
    }

    function render($options, $defaults, $content) { 
        
        // icons
        $twitter_icon = esc_attr($options['twitter_icon']);
        $facebook_icon  = esc_attr($options['facebook_icon']);
        $email_icon  = esc_attr($options['email_icon']);
        $linkedin_icon  = esc_attr($options['linkedin_icon']);
        $whatsapp_icon = esc_attr($options['whatsapp_icon']);
        $telegram_icon = esc_attr($options['telegram_icon']);
        $pinterest_icon = esc_attr($options['pinterest_icon']);
        
        global $oxygen_svg_icons_to_load;
        $oxygen_svg_icons_to_load[] = $twitter_icon;
        $oxygen_svg_icons_to_load[] = $facebook_icon;
        $oxygen_svg_icons_to_load[] = $email_icon;
        $oxygen_svg_icons_to_load[] = $linkedin_icon;
        $oxygen_svg_icons_to_load[] = $whatsapp_icon;
        $oxygen_svg_icons_to_load[] = $telegram_icon;
        $oxygen_svg_icons_to_load[] = $pinterest_icon;
        
        // Button Text
        
        //$twitter_texts = htmlentities(esc_attr($options['twitter_text']));
        $twitter_text = esc_attr($options['twitter_text']);
        $facebook_text = esc_attr($options['facebook_text']);
        $email_text = esc_attr($options['email_text']);
        $linkedin_text = esc_attr($options['linkedin_text']);
        $whatsapp_text = esc_attr($options['whatsapp_text']);
        $telegram_text = esc_attr($options['telegram_text']);
        $pinterest_text = esc_attr($options['pinterest_text']);
        
        global $post;
        $post_id = $post->ID;
        $site_title = get_bloginfo();
        $home_url = get_home_url();
        $home_url_encode = urlencode($home_url);
        $title = get_the_title( $post_id );
        $title_noquotes = str_replace('"', '&quot;', $title);
        $title_decode = html_entity_decode($title,ENT_QUOTES,'UTF-8');
        $title_encode = urlencode($title_decode);
        $link = get_permalink( $post_id );
        $url = urlencode($link);
        $thumbnail = get_the_post_thumbnail_url();
        $email_body = esc_attr($options['email_body']);
        $email_subject = esc_attr($options['email_subject']);
        $twitter_handle  = isset( $options['twitter_handle'] ) ? '&via='. esc_attr($options['twitter_handle']) : '';
        $whatsapp_urltext = urlencode($title_decode . ' - ' . $link);
   
        // Share URLs
        $twitter_url = 'https://twitter.com/share?text=' . $title_encode . '&url=' . $url . $twitter_handle;
        $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
        $email_url = 'mailto:?body='. $email_body .' '. $url .'&subject='. $email_subject .' '. $title_noquotes;
        $linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url='. $url .'&title='. $title_noquotes .'&summary='. $email_subject .'&source='. $site_title;
        $whatsapp_url = 'https://api.whatsapp.com/send?text=' . $whatsapp_urltext;
        $telegram_url = 'https://telegram.me/share/url?url='  . $url . '&text=' . $title_encode;
        $pinterest_url = 'https://www.pinterest.com/pin/create/button/?url=' . $url . '&media=' . $thumbnail . '&description=' . $title_encode;
        
        
        // Twitter
        if ( $options['twitter_display'] !== 'hide' ) {
            
             ?> <a class="oxy-share-button twitter" target="_blank" aria-label="<?php echo $twitter_text; ?>" href="<?php echo $twitter_url; ?>" rel="noopener noreferrer nofollow">
        <?php if ( esc_attr($options['social_display']) != 'text' ) { ?>
                <span class="oxy-share-icon"><svg id="twitter<?php echo esc_attr($options['selector']); ?>-share-icon"><use xlink:href="#<?php echo $twitter_icon; ?>"></use></svg></span> 
        <?php } ?>
        <?php if ( esc_attr($options['social_display']) != 'icon' ) { ?>
                <span class="oxy-share-name"><?php echo $twitter_text; ?></span>
        <?php } ?>
        </a> <?php
            
        } 
        
        
         // Facebook
         if ( $options['facebook_display'] !== 'hide' ) {
            
             ?> <a class="oxy-share-button facebook" target="_blank" aria-label="<?php echo $facebook_text; ?>" href="<?php echo $facebook_url; ?>" rel="noopener noreferrer nofollow">
            <?php if ( esc_attr($options['social_display']) != 'text' ) { ?>
                <span class="oxy-share-icon"><svg id="facebook<?php echo esc_attr($options['selector']); ?>-share-icon"><use xlink:href="#<?php echo $facebook_icon; ?>"></use></svg></span> <?php } ?>
            
            <?php if ( esc_attr($options['social_display']) != 'icon' ) { ?>
                <span class="oxy-share-name"><?php echo $facebook_text; ?></span>
        </a> <?php }
             
         }
        
        
         // Email
         if ( $options['email_display'] !== 'hide' ) {
            
             ?> <a class="oxy-share-button email" target="_blank" aria-label="<?php echo $email_text; ?>" href="<?php echo $email_url; ?>" rel="noopener noreferrer nofollow">
            <?php if ( esc_attr($options['social_display']) != 'text' ) { ?>
                <span class="oxy-share-icon"><svg id="email<?php echo esc_attr($options['selector']); ?>-share-icon"><use xlink:href="#<?php echo $email_icon; ?>"></use></svg></span>
             <?php } ?>
            <?php if ( esc_attr($options['social_display']) != 'icon' ) { ?>
                <span class="oxy-share-name"><?php echo $email_text; ?></span>
           <?php } ?>
        </a> <?php
             
        }
        
        
        // LinkedIn
        
        if ( $options['linkedin_display'] !== 'hide' ) {
            
             ?> <a class="oxy-share-button linkedin" target="_blank" aria-label="<?php echo $linkedin_text; ?>" href="<?php echo $linkedin_url; ?>" rel="noopener noreferrer nofollow">
            <?php if ( esc_attr($options['social_display']) != 'text' ) { ?>
                <span class="oxy-share-icon"><svg id="linkedin<?php echo esc_attr($options['selector']); ?>-share-icon"><use xlink:href="#<?php echo $linkedin_icon; ?>"></use></svg></span>
             <?php } ?>
            <?php if ( esc_attr($options['social_display']) != 'icon' ) { ?>
                <span class="oxy-share-name"><?php echo $linkedin_text; ?></span>
           <?php } ?>
        </a> <?php
             
        }
        
        
        // WhatsApp
        
        if ( $options['whatsapp_display'] !== 'hide' ) {
            
             ?> <a class="oxy-share-button whatsapp" target="_blank" aria-label="<?php echo $whatsapp_text; ?>" href="<?php echo $whatsapp_url; ?>" rel="noopener noreferrer nofollow">
            <?php if ( esc_attr($options['social_display']) != 'text' ) { ?>
                <span class="oxy-share-icon"><svg id="whatsapp<?php echo esc_attr($options['selector']); ?>-share-icon"><use xlink:href="#<?php echo $whatsapp_icon; ?>"></use></svg></span>
             <?php } ?>
            <?php if ( esc_attr($options['social_display']) != 'icon' ) { ?>
                <span class="oxy-share-name"><?php echo $whatsapp_text; ?></span>
           <?php } ?>
        </a> <?php
             
        }
        
        
         // Telegram
        
        if ( $options['telegram_display'] !== 'hide' ) {
            
             ?> <a class="oxy-share-button telegram" target="_blank" aria-label="<?php echo $telegram_text; ?>" href="<?php echo $telegram_url; ?>" rel="noopener noreferrer nofollow">
            <?php if ( esc_attr($options['social_display']) != 'text' ) { ?>
                <span class="oxy-share-icon"><svg id="telegram<?php echo esc_attr($options['selector']); ?>-share-icon"><use xlink:href="#<?php echo $telegram_icon; ?>"></use></svg></span>
             <?php } ?>
            <?php if ( esc_attr($options['social_display']) != 'icon' ) { ?>
                <span class="oxy-share-name"><?php echo $telegram_text; ?></span>
           <?php } ?>
        </a> <?php
             
        }
        
        
         // Pinterest
        
        if ( $options['pinterest_display'] !== 'hide' ) {
            
             ?> <a class="oxy-share-button pinterest" target="_blank" aria-label="<?php echo $pinterest_text; ?>" href="<?php echo $pinterest_url; ?>" rel="noopener noreferrer nofollow">
            <?php if ( esc_attr($options['social_display']) != 'text' ) { ?>
                <span class="oxy-share-icon"><svg id="pinterest<?php echo esc_attr($options['selector']); ?>-share-icon"><use xlink:href="#<?php echo $pinterest_icon; ?>"></use></svg></span>
             <?php } ?>
            <?php if ( esc_attr($options['social_display']) != 'icon' ) { ?>
                <span class="oxy-share-name"><?php echo $pinterest_text; ?></span>
           <?php } ?>
        </a> <?php
             
        }
        
        
       
    }

    function class_names() {
        
        $output = '';
        
        return $output;
    }


    function controls() {
        
        
        /**
         * Display
         */
        $this->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Output',
                'slug' => 'social_display'
            )
        )->setValue(array( "text" => "Text", "icon" => "Icon", "both" => "Both" ))
         ->setDefaultValue('both')->rebuildElementOnChange();
        
        
        /**
         * Layout
         */
        $this->addStyleControl(
            array( 
                "property" => 'font-size',
                "default" => '12',
            )
        );
        
        
        /**
         * Layout
         */
        $layout_section = $this->addControlSection("layout_section", __("Layout / Position"), "assets/icon.png", $this);
        $layout_section->flex('', $this);
        
        $position = $layout_section->addControl("buttons-list", "position", __("Position") );
        $position->setValue( array("Fixed","Sticky","Manual") );
        $position->setDefaultValue('Manual');
        $position->setValueCSS( array(
            "Fixed"  => "
                { 
                    position: fixed;
               }
               ",
            "Sticky"  => "
               {
                    position: -webkit-sticky;  
                    position: sticky;
               }

            ",
            "Manual"  => "",
        ) );
        $position->whiteList();
        
        
        
        $layout_section->addStyleControl(
            array( 
                "property" => 'top',
                "control_type" => "measurebox",
                "condition" => "position!=Manual",
                "unit" => "px"
            )
        );
        
        $layout_section->addStyleControl(
            array( 
                "property" => 'bottom',
                "control_type" => "measurebox",
                "condition" => "position!=Manual",
                "unit" => "px"
            )
        );
        
        $layout_section->addStyleControl(
            array( 
                "property" => 'left',
                "control_type" => "measurebox",
                "condition" => "position=Fixed",
                "unit" => "px"
            )
        );
        
        $layout_section->addStyleControl(
            array( 
                "property" => 'right',
                "control_type" => "measurebox",
                "condition" => "position=Fixed",
                "unit" => "px"
            )
        );
        
        $layout_section->addStyleControl(
            array( 
                "property" => 'z-index',
                "control_type" => "measurebox",
                "condition" => "position=Fixed",
            )
        );
        
        $display_section = $this->addControlSection("display_section", __("Networks"), "assets/icon.png", $this);
        
            
        /**
          * Twitter
          */
        $twitter_section = $display_section->addControlSection("twitter_section", __("Twitter"), "assets/icon.png", $this);

         $twitter_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Twitter Button',
                'slug' => 'twitter_display')

            )->setValue(array( "display" => "Display", "hide" => "Remove" ))
             ->setDefaultValue('display')->rebuildElementOnChange();

        $twitter_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Button Text'),
                "slug" => 'twitter_text',
                "default" => 'Twitter',
                "condition" => 'twitter_display=display',
                "base64" => true
            )
        )->rebuildElementOnChange();

        $twitter_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Twitter Handle'),
                "slug" => 'twitter_handle',
                "condition" => 'twitter_display=display',
                "base64" => true
            )
        );

        $twitter_section->addOptionControl(
            array(
                "type" => 'icon_finder',
                "name" => __('Icon'),
                "slug" => 'twitter_icon',
                "value" => 'FontAwesomeicon-twitter',
                "condition" => 'twitter_display=display',
            )
        )->rebuildElementOnChange();
        
        $twitter_section->addStyleControl(
            array( 
                "name" => __('Order (flex)'),
                "selector" => '.oxy-share-button.twitter',
                "property" => 'order',
                "control_type" => "textfield",
                "default" => '',
            )
        );
        
        
        
        /**
          * Facebook
          */
        
        $facebook_section = $display_section->addControlSection("facebook_section", __("Facebook"), "assets/icon.png", $this);
        
         $facebook_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'facebook Button',
                'slug' => 'facebook_display')

            )->setValue(array( "display" => "Display", "hide" => "Remove" ))
             ->setDefaultValue('display')->rebuildElementOnChange();

        $facebook_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Button Text'),
                "slug" => 'facebook_text',
                "default" => 'Facebook',
                "condition" => 'facebook_display=display',
                "base64" => true
            )
        )->rebuildElementOnChange();

        $facebook_section->addOptionControl(
            array(
                "type" => 'icon_finder',
                "name" => __('Icon'),
                "slug" => 'facebook_icon',
                "value" => 'FontAwesomeicon-facebook',
                "condition" => 'facebook_display=display',
            )
        )->rebuildElementOnChange();
        
        $facebook_section->addStyleControl(
            array( 
                "name" => __('Order (flex)'),
                "selector" => '.oxy-share-button.facebook',
                "property" => 'order',
                "control_type" => "textfield",
                "default" => '',
            )
        );
        
        
        /**
          * linkedin
          */
        $linkedin_section = $display_section->addControlSection("linkedin_section", __("Linked In"), "assets/icon.png", $this);
        
         $linkedin_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'linkedin Button',
                'slug' => 'linkedin_display')

            )->setValue(array( "display" => "Display", "hide" => "Remove" ))
             ->setDefaultValue('display')->rebuildElementOnChange();

        $linkedin_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Button Text'),
                "slug" => 'linkedin_text',
                "default" => 'Linkedin',
                "condition" => 'linkedin_display=display',
                "base64" => true
            )
        )->rebuildElementOnChange();

        $linkedin_section->addOptionControl(
            array(
                "type" => 'icon_finder',
                "name" => __('Icon'),
                "slug" => 'linkedin_icon',
                "value" => 'FontAwesomeicon-linkedin',
                "condition" => 'linkedin_display=display',
            )
        )->rebuildElementOnChange();    
        
        $linkedin_section->addStyleControl(
            array( 
                "name" => __('Order (flex)'),
                "selector" => '.oxy-share-button.linkedin',
                "property" => 'order',
                "control_type" => "textfield",
                "default" => '',
            )
        );
            
        
        
         /**
          * email
          */
        $email_section = $display_section->addControlSection("email_section", __("Email"), "assets/icon.png", $this);
        
         $email_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Email Button',
                'slug' => 'email_display')

            )->setValue(array( "display" => "Display", "hide" => "Remove" ))
             ->setDefaultValue('display')->rebuildElementOnChange();

        $email_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Button Text'),
                "slug" => 'email_text',
                "default" => 'Email',
                "condition" => 'email_display=display',
                "base64" => true
            )
        )->rebuildElementOnChange();
        
        $email_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Email Subject - Text to come before the post title'),
                "slug" => 'email_subject',
                "default" => 'Check out this post -',
                "condition" => 'email_display=display',
                "base64" => true
            )
        )->rebuildElementOnChange();
        
        $email_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Email Body - Text to come before the post link'),
                "slug" => 'email_body',
                "default" => 'Here is the link -',
                "condition" => 'email_display=display',
                "base64" => true
            )
        )->rebuildElementOnChange();

        $email_section->addOptionControl(
            array(
                "type" => 'icon_finder',
                "name" => __('Icon'),
                "slug" => 'email_icon',
                "value" => 'FontAwesomeicon-envelope',
                "condition" => 'email_display=display',
            )
        )->rebuildElementOnChange();        
        
        $email_section->addStyleControl(
            array( 
                "name" => __('Order (flex)'),
                "selector" => '.oxy-share-button.email',
                "property" => 'order',
                "control_type" => "textfield",
                "default" => '',
            )
        );
        
        
         /**
          * WhatsApp
          */
        
        $whatsapp_section = $display_section->addControlSection("whatsapp_section", __("WhatsApp"), "assets/icon.png", $this);
        
        
        $whatsapp_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'WhatsApp Button',
                'slug' => 'whatsapp_display')

            )->setValue(array( "display" => "Display", "hide" => "Remove" ))
             ->setDefaultValue('hide')->rebuildElementOnChange();

        $whatsapp_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('WhatsApp Text'),
                "slug" => 'whatsapp_text',
                "default" => 'WhatsApp',
                "condition" => 'whatsapp_display=display',
                "base64" => true
            )
        )->rebuildElementOnChange();
        
        
        $whatsapp_section->addOptionControl(
            array(
                "type" => 'icon_finder',
                "name" => __('Icon'),
                "slug" => 'whatsapp_icon',
                "value" => 'FontAwesomeicon-whatsapp',
                "condition" => 'whatsapp_display=display',
            )
        )->rebuildElementOnChange();  
        
        $whatsapp_section->addStyleControl(
            array( 
                "name" => __('Order (flex)'),
                "selector" => '.oxy-share-button.whatsapp',
                "property" => 'order',
                "control_type" => "textfield",
                "default" => '',
            )
        );
        
        
        /**
          * Telegram
          */
        
        $telegram_section = $display_section->addControlSection("telegram_section", __("Telegram"), "assets/icon.png", $this);
        
        
        $telegram_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Telegram Button',
                'slug' => 'telegram_display')

            )->setValue(array( "display" => "Display", "hide" => "Remove" ))
             ->setDefaultValue('hide')->rebuildElementOnChange();

        $telegram_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Telegram Text'),
                "slug" => 'telegram_text',
                "default" => 'Telegram',
                "condition" => 'telegram_display=display',
                "base64" => true
            )
        )->rebuildElementOnChange();
        
        
        $telegram_section->addOptionControl(
            array(
                "type" => 'icon_finder',
                "name" => __('Icon'),
                "slug" => 'telegram_icon',
                "value" => 'FontAwesomeicon-paper-plane',
                "condition" => 'telegram_display=display',
            )
        )->rebuildElementOnChange();  
        
        $telegram_section->addStyleControl(
            array( 
                "name" => __('Order (flex)'),
                "selector" => '.oxy-share-button.telegram',
                "property" => 'order',
                "control_type" => "textfield",
                "default" => '',
            )
        );
        
        
        /**
          * Pinterest
          */
        
        $pinterest_section = $display_section->addControlSection("pinterest_section", __("Pinterest"), "assets/icon.png", $this);
        
        
        $pinterest_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Pinterest Button',
                'slug' => 'pinterest_display')

            )->setValue(array( "display" => "Display", "hide" => "Remove" ))
             ->setDefaultValue('hide')->rebuildElementOnChange();

        $pinterest_section->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __('Pinterest Text'),
                "slug" => 'pinterest_text',
                "default" => 'Pinterest',
                "condition" => 'pinterest_display=display',
                "base64" => true
            )
        )->rebuildElementOnChange();
        
        
        $pinterest_section->addOptionControl(
            array(
                "type" => 'icon_finder',
                "name" => __('Icon'),
                "slug" => 'pinterest_icon',
                "value" => 'FontAwesomeicon-pinterest-p',
                "condition" => 'pinterest_display=display',
            )
        )->rebuildElementOnChange();  
        
        $pinterest_section->addStyleControl(
            array( 
                "name" => __('Order (flex)'),
                "selector" => '.oxy-share-button.pinterest',
                "property" => 'order',
                "control_type" => "textfield",
                "default" => '',
            )
        );

        
        
        /**
         * Button Styles
         */
        
        $button_section = $this->addControlSection("button_section", __("Button Styles"), "assets/icon.png", $this);
        $button_selector = '.oxy-share-button';
        
        $button_spacing = $button_section->addControlSection("styles_spacing", __("Layout / Spacing"), "assets/icon.png", $this);
        
        $button_spacing->flex($button_selector, $this);
            
        
        
       $button_spacing->addStyleControl(
            array( 
                //"name" => __('Top'),
                "selector" => $button_selector,
                "property" => 'margin-top',
                "control_type" => "measurebox",
                "unit" => "em",
                "default" => '.2',
            )
        );
        
        $button_spacing->addStyleControl(
            array( 
                //"name" => __('Top'),
                "selector" => $button_selector,
                "property" => 'margin-left',
                "control_type" => "measurebox",
                "unit" => "em",
                "default" => '.2',
            )
        );
        
        $button_spacing->addStyleControl(
            array( 
                //"name" => __('Top'),
                "selector" => $button_selector,
                "property" => 'margin-right',
                "control_type" => "measurebox",
                "unit" => "em",
                "default" => '.2',
            )
        );
        
        $button_spacing->addStyleControl(
            array( 
                //"name" => __('Top'),
                "selector" => $button_selector,
                "property" => 'margin-bottom',
                "control_type" => "measurebox",
                "unit" => "em",
                "default" => '.2',
            )
        );
        
        $button_section->borderSection('Borders', $button_selector,$this);
        
        $button_section->boxShadowSection('Shadows', $button_selector,$this);
        
        $button_brand = $button_section->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Button Colours',
                'slug' => 'brand')

            );
        $button_brand->setValue(array( "brand" => "Brand", "custom" => "Custom" ));
        $button_brand->setDefaultValue('custom');
        $button_brand->setValueCSS( array(
            "brand"  => "
                .oxy-share-button.twitter { 
                    background-color: #00b6f1;
               }
               
               .oxy-share-button.twitter:hover { 
                    background-color: #20d6ff;
               }
               
               .oxy-share-button.facebook { 
                    background-color: #3b5998;
               }
               
               .oxy-share-button.facebook:hover { 
                    background-color: #5b79b8;
               }
               
               .oxy-share-button.linkedin { 
                    background-color: #007bb6;
               }
               
               .oxy-share-button.linkedin:hover { 
                    background-color: #209bd6;
               }
               
               .oxy-share-button.whatsapp { 
                    background-color: #23D366;
               }
               
               .oxy-share-button.whatsapp:hover { 
                    background-color: #3DED7F;
               }
               
               .oxy-share-button.telegram {
                    background-color: #28A8EA;
               }
               
               .oxy-share-button.telegram:hover {
                    background-color: #5CDAFF;
               }
               
               .oxy-share-button.pinterest {
                    background-color: #E60023;
               }
               
               .oxy-share-button.pinterest:hover {
                    background-color: #FF1A3D;
               }
               
               
               
               
               ",
            "custom"  => "",
        ) );
        $button_brand->whiteList();
        
        
        $button_section->addStyleControls(
            array(
                array(
                    "name" => __('Background Color'),
                    "selector" => $button_selector,
                    "property" => 'background-color',
                    "default" => '#111',
                    "condition" => 'brand=custom'
                ),
                array(
                    "name" => __('Text / Icon Color'),
                    "selector" => $button_selector,
                    "property" => 'color',
                    "default" => '#fff',
                    "condition" => 'brand=custom'
                )
            )
        );
        
        
        /**
         * Button Hover Styles
         */
        
        $button_hover_section = $this->addControlSection("button_hover_section", __("Button Hover Styles"), "assets/icon.png", $this);
        
        $button_hover_section->addStyleControls(
            array(
                
                array(
                    "name" => __('Background Color'),
                    "selector" => $button_selector.":hover",
                    "property" => 'background-color',
                    "condition" => 'brand=custom'
                ),
                array(
                    "name" => __('Text / Icon Color'),
                    "selector" => $button_selector.":hover",
                    "property" => 'color',
                    "condition" => 'brand=custom'
                )
            )
        );
        
        $button_hover_section->borderSection('Borders', $button_selector.":hover",$this);
        $button_hover_section->boxShadowSection('Shadows', $button_selector.":hover",$this);
        
        $button_hover_section->addStyleControl(
            array(
                "name" => __('Hover Transition Duration'),
                "selector" => $button_selector,
                "property" => 'transition-duration',
                "control_type" => 'slider-measurebox',
                "default" => '0.3',
            )
        )
        ->setUnits('ms','ms')
        ->setRange('0','1000','1');
        
        
        /**
          * Icons
          */
        
        $icon_section = $this->addControlSection("icon_section", __("Icons"), "assets/icon.png", $this);
        $icon_selector = '.oxy-share-icon';
        $icon_hover_selector = '.oxy-share-button:hover .oxy-share-icon';
        
        $icon_section->addStyleControls(
            array(
                
                array(
                    "name" => __('Icon Size'),
                    "selector" => $icon_selector,
                    "property" => 'font-size',
                )
            )
        );
        
        $icon_section->addStyleControls(
            array(
                array(
                    "name" => __('Icon Area Background'),
                    "selector" => $icon_selector,
                    "property" => 'background-color',
                    "default" => 'rgba(255,255,255,0.15)',
                ),
                array(
                    "name" => __('Icon Area Hover Background'),
                    "selector" => $icon_hover_selector,
                    "property" => 'background-color',
                    "default" => 'rgba(255,255,255,0.25)',
                )
            )
        );
        
        $icon_section->addStyleControl(
            array(
                "name" => __('Hover Transition Duration'),
                "selector" => $icon_selector,
                "property" => 'transition-duration',
                "control_type" => 'slider-measurebox',
                "default" => '0.3',
            )
        )
        ->setUnits('ms','ms')
        ->setRange('0','1000','1');
        
        $hide_icon_control = $icon_section->addOptionControl(
            array(
                "name" => __('Hide Icon Below', 'oxygen'),
                "slug" => 'hide_icon_below',
                "type" => 'medialist',
                "default" => 'never'
            )
        );
        $hide_icon_control->rebuildElementOnChange();
        
        
        $icon_spacing_section = $icon_section->addControlSection("icon_spacing_section", __("Spacing"), "assets/icon.png", $this);
        
        
        $icon_spacing_section->addStyleControl(
            array( 
                //"name" => __('Top'),
                "selector" => $icon_selector,
                "property" => 'padding-top',
                "control_type" => "measurebox",
                "unit" => "em",
                "default" => '1',
            )
        )->setParam('hide_wrapper_end', true);
        
       $icon_spacing_section->addStyleControl(
            array( 
                //"name" => __('Top'),
                "selector" => $icon_selector,
                "property" => 'padding-left',
                "control_type" => "measurebox",
                "unit" => "em",
                "default" => '1.5',
            )
        )->setParam('hide_wrapper_start', true);
        
        $icon_spacing_section->addStyleControl(
            array( 
                //"name" => __('Top'),
                "selector" => $icon_selector,
                "property" => 'padding-right',
                "control_type" => "measurebox",
                "unit" => "em",
                "default" => '1.5',
            )
        )->setParam('hide_wrapper_end', true);
        
        $icon_spacing_section->addStyleControl(
            array( 
                //"name" => __('Top'),
                "selector" => $icon_selector,
                "property" => 'padding-bottom',
                "control_type" => "measurebox",
                "unit" => "em",
                "default" => '1',
            )
        )->setParam('hide_wrapper_start', true);
        
        $icon_section->borderSection('Borders', $icon_selector,$this);
        
        $icon_section->boxShadowSection('Shadows', $icon_selector,$this);
        
        
        /**
          * Button Text
          */
        
        $text_section = $this->addControlSection("text_section", __("Text"), "assets/icon.png", $this);
        $text_selector = '.oxy-share-name';
        
        $hide_text_control = $text_section->addOptionControl(
            array(
                "name" => __('Hide Text Below', 'oxygen'),
                "slug" => 'hide_text_below',
                "type" => 'medialist',
                "default" => 'never'
            )
        );
        $hide_text_control->rebuildElementOnChange();
        
        $text_section->typographySection('Typography', $text_selector,$this);
        
        $text_section->addStyleControl(
            array( 
                "selector" => $text_selector,
                "property" => 'padding-top',
                "control_type" => "measurebox",
                "unit" => "em",
                "default" => '1',
            )
        )->setParam('hide_wrapper_end', true);
        
       $text_section->addStyleControl(
            array( 
                "selector" => $text_selector,
                "property" => 'padding-left',
                "control_type" => "measurebox",
                "unit" => "em",
                "default" => '1.5',
            )
        )->setParam('hide_wrapper_start', true);
        
        $text_section->addStyleControl(
            array( 
                "selector" => $text_selector,
                "property" => 'padding-right',
                "control_type" => "measurebox",
                "unit" => "em",
                "default" => '1.5',
            )
        )->setParam('hide_wrapper_end', true);
        
        $text_section->addStyleControl(
            array( 
                "selector" => $text_selector,
                "property" => 'padding-bottom',
                "control_type" => "measurebox",
                "unit" => "em",
                "default" => '1',
            )
        )->setParam('hide_wrapper_start', true);
        
        
        
        
    }
    
    function customCSS($options, $selector) {
        
        $css = ".oxy-social-share-buttons {
                    display: inline-flex;
                    flex-wrap: wrap;
                    font-size: 12px;
                }
                
                .oxy-share-button {
                    display: flex;
                    align-items: stretch;
                    margin: .2em;
                }
            
                .oxy-social-share-buttons .oxy-share-button {
                    background: #111;
                    color: #fff;
                    display: flex;
                    transition-duration: .35s;
                    line-height: 1;
                    transition-timing-function: ease;
                    transition-property: background-color,color,border-color;
                }

                .oxy-share-icon svg {
                    fill: currentColor;
                    width: 1em;
                    height: 1em;
                }

                .oxy-share-icon {
                    background-color: rgba(255,255,255,0.15);
                    display: flex;
                    align-items: center;
                    padding: 1em 1.5em;
                    transition-duration: 0.3s;
                    transition-property: background-color;
                }

                .oxy-share-button:hover .oxy-share-icon {
                    background-color: rgba(255,255,255,0.25);
                }

                .oxy-share-name {
                    display: flex;
                    align-items: center;
                    padding: 1em 1.5em;
                }";
        
        
            if ((isset($options["oxy-social-share-buttons_hide_text_below"]) && $options["oxy-social-share-buttons_hide_text_below"]!="never")) {    
                $max_width = oxygen_vsb_get_media_query_size($options["oxy-social-share-buttons_hide_text_below"]);
                $css .= "@media (max-width: {$max_width}px) {
                
                            $selector .oxy-share-name {
                                display: none;
                            }

                        }";
                }
        
            if ((isset($options["oxy-social-share-buttons_hide_icon_below"]) && $options["oxy-social-share-buttons_hide_icon_below"]!="never")) {
                $max_width = oxygen_vsb_get_media_query_size($options["oxy-social-share-buttons_hide_icon_below"]);
                $css .= "@media (max-width: {$max_width}px) {
                
                            $selector .oxy-share-icon {
                                display: none;
                            }
                            
                        }";
            }
        

            return $css;
    }
    
    
    function afterInit() {
        $this->removeApplyParamsButton();
    }

}

new ExtraSocial();