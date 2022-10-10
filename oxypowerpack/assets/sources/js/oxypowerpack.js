(function($, angular) {
    $(document).ready( function(){
        var initInterval = setInterval(function(){
            if( !$("#ct-page-overlay").is(':visible') ){
                clearInterval( initInterval );
                OxyPowerPack.init();
            }
        }, 250);
    } );

    var OxyPowerPack = {
        $scope: null,
        init: function(){
            this.getScope();
            this.$scope.oxyPowerPack = {
                avoidQuoteRegex: '^[^"\']+$',
                currentTab: 'start',
                drawerOpen: true,
                drawerDocked: false,
                currentPostObject: null,
                persistentState:{
                    docked: false
                },
                library_enabled: window.OxyPowerPackBEData.library_enabled,
                components: window.OxyPowerPackBEData.components,
                currentComponent: null,
                maintenanceModeFormBusy:true,
                comingSoonFeature: function(feature){
                    alert( feature + " will be available soon");
                },
                objectPropertyExists: function(o,p){
                    if( typeof o === 'undefined' ) return false;
                    return typeof o[p] != 'undefined';
                },
                oxyPowerPackHelperAvailable: function(){
                    return [
                        'oxy-countdown-timer'
                    ].indexOf(OxyPowerPack.$scope.iframeScope.component.active.name) != -1;
                },
                premadeForms: {
                    forms: [
                        { name: 'Contact Form 1', image: 'https://designset.oxypowerpack.com/wp-content/uploads/2020/09/Captura-de-Pantalla-2020-09-26-a-las-13.07.20.png', data: '{"id":0,"name":"root","depth":0,"children":[{"id":1,"name":"oxy-power-form","options":{"ct_id":1,"ct_parent":0,"selector":"-power-form-91-68","original":{"oxy-power-form_label_margin_margin-top":"5","oxy-power-form_field_padding_padding-top":"5","oxy-power-form_field_padding_padding-bottom":"5","oxy-power-form_field_padding_padding-left":"5","oxy-power-form_field_padding_padding-right":"5","oxy-power-form_slug_inputselectbuttontextarea_border_radius":"0","oxy-power-form_field_border_border-top-width-unit":"","oxy-power-form_field_border_border-right-width-unit":"","oxy-power-form_field_border_border-bottom-width-unit":"","oxy-power-form_field_border_border-left-width-unit":"","oxy-power-form_field_border_border-top-width":"1","oxy-power-form_field_border_border-right-width":"1","oxy-power-form_field_border_border-bottom-width":"1","oxy-power-form_field_border_border-left-width":"1","oxy-power-form_field_border_border-top-color":"#000000","oxy-power-form_field_border_border-right-color":"#000000","oxy-power-form_field_border_border-bottom-color":"#000000","oxy-power-form_field_border_border-left-color":"#000000","oxy-power-form_field_border_border-top-style":"solid","oxy-power-form_field_border_border-right-style":"solid","oxy-power-form_field_border_border-bottom-style":"solid","oxy-power-form_field_border_border-left-style":"solid","border-top-color":"#000000","border-right-color":"#000000","border-bottom-color":"#000000","border-left-color":"#000000","border-top-width":"1","border-right-width":"1","border-bottom-width":"1","border-left-width":"1","border-top-style":"solid","border-right-style":"solid","border-bottom-style":"solid","border-left-style":"solid","padding-top":"5","padding-left":"5","padding-right":"5","padding-bottom":"5","oxy-power-form_save_to_database":"true","background-color":"#ededed"},"activeselector":false},"children":[{"id":2,"name":"ct_headline","options":{"ct_id":2,"ct_parent":1,"selector":"headline-118-68","original":{"tag":"h2"},"activeselector":false,"ct_content":"Contact Us"},"depth":false},{"id":3,"name":"ct_new_columns","options":{"ct_id":3,"ct_parent":1,"selector":"new_columns-92-68"},"children":[{"id":4,"name":"ct_div_block","options":{"ct_id":4,"ct_parent":3,"selector":"div_block-94-68","original":{"width":"33.33","width-unit":"%","align-items":"stretch","text-align":"justify","padding-top":"0","padding-left":"0","padding-right":"0","padding-bottom":"0"},"activeselector":false},"children":[{"id":5,"name":"oxy-power-form-field","options":{"ct_id":5,"ct_parent":4,"selector":"-power-form-field-97-68","original":{"oxy-power-form-field_required":"true","oxy-power-form-field_field_label":"First Name","oxy-power-form-field_field_name":"first_name"},"activeselector":false,"ct_content":""},"depth":false}],"depth":1},{"id":6,"name":"ct_div_block","options":{"ct_id":6,"ct_parent":3,"selector":"div_block-95-68","original":{"width":"33.33","width-unit":"%","align-items":"stretch","text-align":"justify","padding-left":"0","padding-right":"0","padding-bottom":"0","padding-top":"0"},"activeselector":false},"children":[{"id":7,"name":"oxy-power-form-field","options":{"ct_id":7,"ct_parent":6,"selector":"-power-form-field-98-68","original":{"oxy-power-form-field_field_label":"Last Name","oxy-power-form-field_field_name":"last_name"},"activeselector":false,"ct_content":""},"depth":false}],"depth":1},{"id":8,"name":"ct_div_block","options":{"ct_id":8,"ct_parent":3,"selector":"div_block-96-68","original":{"width":"33.34","width-unit":"%","align-items":"stretch","text-align":"justify","padding-top":"0","padding-left":"0","padding-right":"0","padding-bottom":"0"},"activeselector":false},"children":[{"id":9,"name":"oxy-power-form-field","options":{"ct_id":9,"ct_parent":8,"selector":"-power-form-field-99-68","original":{"oxy-power-form-field_field_label":"Email","oxy-power-form-field_field_name":"email"},"activeselector":false,"ct_content":""},"depth":false}],"depth":1}],"depth":1},{"id":10,"name":"oxy-power-form-field","options":{"ct_id":10,"ct_parent":1,"selector":"-power-form-field-100-68","original":{"oxy-power-form-field_field_type":"select","oxy-power-form-field_field_label":"Subject","oxy-power-form-field_required":"true","oxy-power-form-field_field_name":"subject","oxy-power-form-field_oxyPowerPackFormOptions":[{"label":"oxy_base64_encoded::UHJvamVjdCBRdW90YXRpb24=","value":"oxy_base64_encoded::cHJvamVjdF9xdW90YXRpb24="},{"label":"oxy_base64_encoded::U3VwcG9ydCBSZXF1ZXN0","value":"oxy_base64_encoded::c3VwcG9ydF9yZXF1ZXN0"},{"label":"oxy_base64_encoded::U2NoZWR1bGUgTWVldGluZw==","value":"oxy_base64_encoded::c2NoZWR1bGVfbWVldGluZw=="}],"oxy-power-form-field_oxyPowerPackFormOptionsSerialized":"W3sibGFiZWwiOiJveHlfYmFzZTY0X2VuY29kZWQ6OlVISnZhbVZqZENCUmRXOTBZWFJwYjI0PSIsInZhbHVlIjoib3h5X2Jhc2U2NF9lbmNvZGVkOjpjSEp2YW1WamRGOXhkVzkwWVhScGIyND0iLCIkJGhhc2hLZXkiOiJvYmplY3Q6MTA1ODkifSx7ImxhYmVsIjoib3h5X2Jhc2U2NF9lbmNvZGVkOjpVM1Z3Y0c5eWRDQlNaWEYxWlhOMCIsInZhbHVlIjoib3h5X2Jhc2U2NF9lbmNvZGVkOjpjM1Z3Y0c5eWRGOXlaWEYxWlhOMCIsIiQkaGFzaEtleSI6Im9iamVjdDoxMDU5MCJ9LHsibGFiZWwiOiJveHlfYmFzZTY0X2VuY29kZWQ6OlUyTm9aV1IxYkdVZ1RXVmxkR2x1Wnc9PSIsInZhbHVlIjoib3h5X2Jhc2U2NF9lbmNvZGVkOjpjMk5vWldSMWJHVmZiV1ZsZEdsdVp3PT0iLCIkJGhhc2hLZXkiOiJvYmplY3Q6MTA1OTEifV0="},"activeselector":false,"ct_content":""},"depth":false},{"id":11,"name":"oxy-power-form-field","options":{"ct_id":11,"ct_parent":1,"selector":"-power-form-field-104-68","original":{"oxy-power-form-field_field_label":"Message","oxy-power-form-field_field_name":"message","oxy-power-form-field_field_type":"textarea","oxy-power-form-field_textarea_rows":"9"},"activeselector":false,"ct_content":""},"depth":false},{"id":12,"name":"oxy-power-form-field","options":{"ct_id":12,"ct_parent":1,"selector":"-power-form-field-121-68","original":{"oxy-power-form-field_field_type":"submit"},"activeselector":false,"ct_content":""},"depth":false}],"depth":false}],"meta_keys":[]}', classes: '{}'},
                        { name: 'Contact Form 2', image: 'https://designset.oxypowerpack.com/wp-content/uploads/2020/09/Captura-de-Pantalla-2020-09-26-a-las-13.07.33.png', data: '{"id":0,"name":"root","depth":0,"children":[{"id":1,"name":"oxy-power-form","options":{"ct_id":1,"ct_parent":0,"selector":"-power-form-124-68","original":{"oxy-power-form_label_margin_margin-top":"5","oxy-power-form_field_padding_padding-top":"8","oxy-power-form_field_padding_padding-bottom":"8","oxy-power-form_field_padding_padding-left":"8","oxy-power-form_field_padding_padding-right":"8","oxy-power-form_slug_inputselectbuttontextarea_border_radius":"0","oxy-power-form_field_border_border-top-width-unit":"","oxy-power-form_field_border_border-right-width-unit":"","oxy-power-form_field_border_border-bottom-width-unit":"","oxy-power-form_field_border_border-left-width-unit":"","oxy-power-form_field_border_border-top-width":"1","oxy-power-form_field_border_border-right-width":"1","oxy-power-form_field_border_border-bottom-width":"1","oxy-power-form_field_border_border-left-width":"1","oxy-power-form_field_border_border-top-color":"#000000","oxy-power-form_field_border_border-right-color":"#000000","oxy-power-form_field_border_border-bottom-color":"#000000","oxy-power-form_field_border_border-left-color":"#000000","oxy-power-form_field_border_border-top-style":"solid","oxy-power-form_field_border_border-right-style":"solid","oxy-power-form_field_border_border-bottom-style":"solid","oxy-power-form_field_border_border-left-style":"solid","border-top-color":"#000000","border-right-color":"#000000","border-bottom-color":"#000000","border-left-color":"#000000","border-top-width":"2","border-right-width":"2","border-bottom-width":"2","border-left-width":"2","border-top-style":"solid","border-right-style":"solid","border-bottom-style":"solid","border-left-style":"solid","padding-top":"5","padding-left":"5","padding-right":"5","padding-bottom":"5","oxy-power-form_save_to_database":"true","background-color":"#536d50","oxy-power-form_label_position":"left","border-radius":"0","display":"flex","flex-direction":"column","align-items":"stretch","oxy-power-form_label_typography_font-weight":"600","oxy-power-form_label_typography_color":"#ffffff"},"activeselector":false},"children":[{"id":2,"name":"ct_new_columns","options":{"ct_id":2,"ct_parent":1,"selector":"new_columns-126-68","original":{"stack-columns-vertically":"phone-portrait","reverse-column-order":"phone-portrait"}},"children":[{"id":3,"name":"ct_div_block","options":{"ct_id":3,"ct_parent":2,"selector":"div_block-127-68","original":{"width":"70","width-unit":"%","align-items":"stretch","text-align":"justify","padding-top":"0","padding-left":"0","padding-bottom":"0","padding-right":"5"},"activeselector":false},"children":[{"id":4,"name":"ct_headline","options":{"ct_id":4,"ct_parent":3,"selector":"headline-125-68","original":{"tag":"h2","text-align":"center","color":"#ffffff"},"activeselector":false,"ct_content":"Leave Us a Message"},"depth":false},{"id":5,"name":"oxy-power-form-field","options":{"ct_id":5,"ct_parent":3,"selector":"-power-form-field-128-68","original":{"oxy-power-form-field_required":"true","oxy-power-form-field_field_label":"First Name","oxy-power-form-field_field_name":"first_name"},"activeselector":false,"ct_content":""},"depth":false},{"id":6,"name":"oxy-power-form-field","options":{"ct_id":6,"ct_parent":3,"selector":"-power-form-field-130-68","original":{"oxy-power-form-field_field_label":"Last Name","oxy-power-form-field_field_name":"last_name","oxy-power-form-field_required":"true"},"activeselector":false,"ct_content":""},"depth":false},{"id":7,"name":"oxy-power-form-field","options":{"ct_id":7,"ct_parent":3,"selector":"-power-form-field-152-68","original":{"oxy-power-form-field_field_label":"Company","oxy-power-form-field_field_name":"company"},"activeselector":false,"ct_content":""},"depth":false},{"id":8,"name":"oxy-power-form-field","options":{"ct_id":8,"ct_parent":3,"selector":"-power-form-field-143-68","original":{"oxy-power-form-field_field_label":"Email","oxy-power-form-field_field_name":"email","oxy-power-form-field_required":"true"},"activeselector":false,"ct_content":""},"depth":false},{"id":9,"name":"oxy-power-form-field","options":{"ct_id":9,"ct_parent":3,"selector":"-power-form-field-133-68","original":{"oxy-power-form-field_field_type":"radio","oxy-power-form-field_field_label":"Subject","oxy-power-form-field_required":"true","oxy-power-form-field_field_name":"subject","oxy-power-form-field_oxyPowerPackFormOptions":[{"label":"oxy_base64_encoded::UHJvamVjdCBRdW90YXRpb24=","value":"oxy_base64_encoded::cHJvamVjdF9xdW90YXRpb24="},{"label":"oxy_base64_encoded::U3VwcG9ydCBSZXF1ZXN0","value":"oxy_base64_encoded::c3VwcG9ydF9yZXF1ZXN0"},{"label":"oxy_base64_encoded::U2NoZWR1bGUgTWVldGluZw==","value":"oxy_base64_encoded::c2NoZWR1bGVfbWVldGluZw=="}],"oxy-power-form-field_oxyPowerPackFormOptionsSerialized":"W3sibGFiZWwiOiJveHlfYmFzZTY0X2VuY29kZWQ6OlVISnZhbVZqZENCUmRXOTBZWFJwYjI0PSIsInZhbHVlIjoib3h5X2Jhc2U2NF9lbmNvZGVkOjpjSEp2YW1WamRGOXhkVzkwWVhScGIyND0iLCIkJGhhc2hLZXkiOiJvYmplY3Q6MTA1ODkifSx7ImxhYmVsIjoib3h5X2Jhc2U2NF9lbmNvZGVkOjpVM1Z3Y0c5eWRDQlNaWEYxWlhOMCIsInZhbHVlIjoib3h5X2Jhc2U2NF9lbmNvZGVkOjpjM1Z3Y0c5eWRGOXlaWEYxWlhOMCIsIiQkaGFzaEtleSI6Im9iamVjdDoxMDU5MCJ9LHsibGFiZWwiOiJveHlfYmFzZTY0X2VuY29kZWQ6OlUyTm9aV1IxYkdVZ1RXVmxkR2x1Wnc9PSIsInZhbHVlIjoib3h5X2Jhc2U2NF9lbmNvZGVkOjpjMk5vWldSMWJHVmZiV1ZsZEdsdVp3PT0iLCIkJGhhc2hLZXkiOiJvYmplY3Q6MTA1OTEifV0="},"activeselector":false,"ct_content":""},"depth":false}],"depth":1},{"id":10,"name":"ct_div_block","options":{"ct_id":10,"ct_parent":2,"selector":"div_block-129-68","original":{"width":"30.00","width-unit":"%","align-items":"stretch","text-align":"justify","padding-left":"0","padding-right":"0","padding-bottom":"0","padding-top":"0"},"activeselector":false},"children":[{"id":11,"name":"ct_image","options":{"ct_id":11,"ct_parent":10,"selector":"image-149-68","original":{"src":"https:\/\/designset.oxypowerpack.com\/wp-content\/uploads\/2019\/11\/our-company-team-min.jpg"},"activeselector":false,"ct_content":""},"depth":false}],"depth":1}],"depth":1},{"id":12,"name":"oxy-power-form-field","options":{"ct_id":12,"ct_parent":1,"selector":"-power-form-field-134-68","original":{"oxy-power-form-field_field_label":"Message","oxy-power-form-field_field_name":"message","oxy-power-form-field_field_type":"textarea","oxy-power-form-field_textarea_rows":"9","oxy-power-form-field_label_position":"top","oxy-power-form-field_required":"true"},"activeselector":false,"ct_content":""},"depth":false},{"id":13,"name":"oxy-power-form-field","options":{"ct_id":13,"ct_parent":1,"selector":"-power-form-field-135-68","original":{"oxy-power-form-field_field_type":"submit","oxy-power-form-field_field_typography_font-weight":"600","oxy-power-form-field_field_typography_letter-spacing":"3","oxy-power-form-field_field_typography_text-transform":"uppercase","oxy-power-form-field_field_background_background-color":"#5587aa","oxy-power-form-field_field_typography_color":"#ffffff"},"activeselector":false,"ct_content":""},"depth":false}],"depth":false}],"meta_keys":[]}', classes: '{}'},
                        { name: 'Newsletter Opt-In 1', image: 'https://designset.oxypowerpack.com/wp-content/uploads/2020/09/newsletter-form-1.png', data: '{"id":0,"name":"root","depth":0,"children":[{"id":1,"name":"ct_div_block","options":{"ct_id":1,"ct_parent":0,"selector":"div_block-62-68","classes":["opp-newsletter-form-1"],"activeselector":"opp-newsletter-form-1"},"children":[{"id":2,"name":"ct_new_columns","options":{"ct_id":2,"ct_parent":1,"selector":"new_columns-63-68","original":{"set-columns-width-50":"phone-portrait"},"classes":[]},"children":[{"id":3,"name":"ct_div_block","options":{"ct_id":3,"ct_parent":2,"selector":"div_block-64-68","original":{"width":"50","width-unit":"%","align-items":"center","text-align":"center","justify-content":"center","padding-top":"25","padding-left":"25","padding-right":"25","padding-bottom":"25"}},"children":[{"id":4,"name":"ct_fancy_icon","options":{"ct_id":4,"ct_parent":3,"selector":"fancy_icon-65-68","original":{"icon-color":"#ffffff","icon-id":"FontAwesomeicon-recycle","margin-bottom":"7"},"ct_content":""},"depth":false},{"id":5,"name":"ct_text_block","options":{"ct_id":5,"ct_parent":3,"selector":"text_block-66-68","original":{"color":"#ffffff","font-weight":"600","font-size":"19"},"ct_content":"Receive News About Our Efforts To Make This A Better World For Everyone"},"depth":false}],"depth":"3"},{"id":6,"name":"ct_div_block","options":{"ct_id":6,"ct_parent":2,"selector":"div_block-67-68","original":{"width":"50","width-unit":"%","background-color":"#1eac40","align-items":"center","text-align":"center","justify-content":"center"}},"children":[{"id":7,"name":"oxy-power-form","options":{"ct_id":7,"ct_parent":6,"selector":"-power-form-68-68","original":{"oxy-power-form_label_position":"none","oxy-power-form_form_name":"Newsletter Form 1","oxy-power-form_show_alert_message":"false","oxyPowerPackEvent-powerform-submitted-settings":{"selector":".opp-nf1"},"oxyPowerPackEvent-powerform-submitted":[{"slug":"show","comment":"","attributes":{"selector":".opp-newsletter-form-1-success-message","fading":true}}]},"classes":["opp-nf1"],"activeselector":"opp-nf1"},"children":[{"id":8,"name":"oxy-power-form-field","options":{"ct_id":8,"ct_parent":7,"selector":"-power-form-field-69-68","original":{"oxy-power-form-field_field_type":"text","oxy-power-form-field_field_label":"Email","oxy-power-form-field_field_name":"email","oxy-power-form-field_required":"true","oxy-power-form-field_required_message":"We need your email address","oxy-power-form-field_field_padding_padding-top":"10","oxy-power-form-field_field_padding_padding-left":"10","oxy-power-form-field_field_padding_padding-right":"10","oxy-power-form-field_field_padding_padding-bottom":"10","oxy-power-form-field_field_border_border-top-style":"none","oxy-power-form-field_field_border_border-right-style":"none","oxy-power-form-field_field_border_border-bottom-style":"none","oxy-power-form-field_field_border_border-left-style":"none","oxy-power-form-field_field_value":"your@email.com"},"ct_content":""},"depth":false},{"id":9,"name":"oxy-power-form-field","options":{"ct_id":9,"ct_parent":7,"selector":"-power-form-field-70-68","original":{"oxy-power-form-field_field_type":"submit","oxy-power-form-field_field_background_background-color":"#ffdc00","oxy-power-form-field_field_border_border-top-style":"none","oxy-power-form-field_field_border_border-right-style":"none","oxy-power-form-field_field_border_border-bottom-style":"none","oxy-power-form-field_field_border_border-left-style":"none","oxy-power-form-field_slug_inputselectbuttontextarea_border_radius":"0","oxy-power-form-field_field_padding_padding-top":"10","oxy-power-form-field_field_padding_padding-left":"10","oxy-power-form-field_field_padding_padding-right":"10","oxy-power-form-field_field_padding_padding-bottom":"10","oxy-power-form-field_field_typography_text-transform":"uppercase","oxy-power-form-field_field_typography_font-weight":"700","oxy-power-form-field_field_typography_color":"#ffffff","oxy-power-form-field_field_typography_letter-spacing":"2"},"ct_content":""},"depth":false}],"depth":false}],"depth":"3"}],"depth":"3"},{"id":10,"name":"ct_div_block","options":{"ct_id":10,"ct_parent":1,"selector":"div_block-71-68","classes":["opp-newsletter-form-1-success-message"],"activeselector":"opp-newsletter-form-1-success-message"},"children":[{"id":11,"name":"ct_headline","options":{"ct_id":11,"ct_parent":10,"selector":"headline-72-68","ct_content":"Thank You"},"depth":false},{"id":12,"name":"ct_fancy_icon","options":{"ct_id":12,"ct_parent":10,"selector":"fancy_icon-73-68","ct_content":""},"depth":false}],"depth":"3"}],"depth":"2"}],"meta_keys":[]}', classes: '{"opp-nf1":{"original":{}},"opp-newsletter-form-1":{"original":{"width-unit":"%","width":"100","background-color":"#2ecc70","position":"relative"}},"opp-newsletter-form-1-success-message":{"original":{"visibility":"hidden","position":"absolute","top":"0","left":"0","bottom":"0","right":"0","background-color":"#ffffff","flex-direction":"column","display":"flex","align-items":"center","text-align":"center","justify-content":"center"}}}'},
                        { name: 'Newsletter Opt-In 2', image: 'https://designset.oxypowerpack.com/wp-content/uploads/2020/09/newsletter-form-2.png', data: '{"id":0,"name":"root","depth":0,"children":[{"id":1,"name":"ct_div_block","options":{"ct_id":1,"ct_parent":0,"selector":"div_block-74-68","classes":["opp-newsletter-form-2"],"activeselector":"opp-newsletter-form-2"},"children":[{"id":2,"name":"ct_new_columns","options":{"ct_id":2,"ct_parent":1,"selector":"new_columns-75-68","original":{"stack-columns-vertically":"phone-portrait"}},"children":[{"id":3,"name":"ct_div_block","options":{"ct_id":3,"ct_parent":2,"selector":"div_block-76-68","original":{"width":"45","width-unit":"%","background-image":"https:\/\/designset.oxypowerpack.com\/wp-content\/uploads\/2020\/09\/e9b8056eb92d4a51abceec135c8d6944-2.jpg","background-size":"cover","background-repeat":"no-repeat","background-position-left-unit":"%","background-position-left":"50","background-position-top":"50","background-position-top-unit":"%","min-height":"150","align-items":"center","text-align":"center","justify-content":"center"}},"children":[{"id":4,"name":"ct_fancy_icon","options":{"ct_id":4,"ct_parent":3,"selector":"fancy_icon-77-68","original":{"icon-id":"FontAwesomeicon-coffee"},"ct_content":""},"depth":false}],"depth":"3"},{"id":5,"name":"ct_div_block","options":{"ct_id":5,"ct_parent":2,"selector":"div_block-78-68","original":{"width":"55.00","width-unit":"%","padding-top":"30","padding-left":"30","padding-right":"30","padding-bottom":"30"},"media":{"phone-portrait":{"original":{"padding-bottom":"10","padding-top":"11"}}}},"children":[{"id":6,"name":"ct_headline","options":{"ct_id":6,"ct_parent":5,"selector":"headline-79-68","original":{"tag":"h2"},"ct_content":"Subscribe to our Newsletter"},"depth":false},{"id":7,"name":"ct_text_block","options":{"ct_id":7,"ct_parent":5,"selector":"text_block-80-68","ct_content":"Be one of the first to hear all about our world-class premium roasted coffee brand"},"depth":false}],"depth":"3"}],"depth":"3"},{"id":8,"name":"oxy-power-form","options":{"ct_id":8,"ct_parent":1,"selector":"-power-form-81-68","original":{"oxy-power-form_form_name":"Newsletter Form 2","display":"flex","flex-direction":"column","padding-top":"5","padding-left":"5","padding-right":"5","padding-bottom":"5","flex-reverse":"false","oxy-power-form_label_position":"top","oxy-power-form_field_padding_padding-top":"10","oxy-power-form_field_padding_padding-bottom":"10","oxy-power-form_field_padding_padding-left":"10","oxy-power-form_field_padding_padding-right":"10","oxy-power-form_label_padding_padding-bottom":"10","background-color":"#58524b","oxyPowerPackEvent-powerform-submitted-settings":{"selector":".opp-nf2"},"oxyPowerPackEvent-powerform-submitted":[{"slug":"show","comment":"","attributes":{"selector":".opp-newsletter-form-2-success-message","fading":true}}]},"classes":["opp-nf2"],"activeselector":"opp-nf2"},"children":[{"id":9,"name":"ct_new_columns","options":{"ct_id":9,"ct_parent":8,"selector":"new_columns-82-68"},"children":[{"id":10,"name":"ct_div_block","options":{"ct_id":10,"ct_parent":9,"selector":"div_block-83-68","original":{"width":"40","width-unit":"%","align-items":"stretch","display":"flex","flex-direction":"column","justify-content":"flex-start","text-align":"justify","padding-left":"10","padding-top":"10","padding-right":"10","padding-bottom":"10"},"media":{"tablet":{"original":{"padding-left":"5","padding-top":"5","padding-right":"5","padding-bottom":"5"}}}},"children":[{"id":11,"name":"oxy-power-form-field","options":{"ct_id":11,"ct_parent":10,"selector":"-power-form-field-84-68","original":{"oxy-power-form-field_field_label":"Name","oxy-power-form-field_field_name":"name","oxy-power-form-field_required":"true","oxy-power-form-field_required_message":"We need your name"},"ct_content":""},"depth":false}],"depth":1},{"id":12,"name":"ct_div_block","options":{"ct_id":12,"ct_parent":9,"selector":"div_block-85-68","original":{"width":"40","width-unit":"%","align-items":"stretch","text-align":"justify","justify-content":"flex-start","flex-direction":"column","display":"flex","padding-left":"10","padding-top":"10","padding-right":"10","padding-bottom":"10"},"media":{"tablet":{"original":{"padding-left":"5","padding-top":"5","padding-right":"5","padding-bottom":"5"}}}},"children":[{"id":13,"name":"oxy-power-form-field","options":{"ct_id":13,"ct_parent":12,"selector":"-power-form-field-86-68","original":{"oxy-power-form-field_field_label":"Email","oxy-power-form-field_field_name":"email","oxy-power-form-field_required":"true","oxy-power-form-field_required_message":"Your Email is important"},"ct_content":""},"depth":false}],"depth":1},{"id":14,"name":"ct_div_block","options":{"ct_id":14,"ct_parent":9,"selector":"div_block-87-68","original":{"width":"20.00","width-unit":"%","align-items":"stretch","text-align":"justify","justify-content":"flex-start","flex-direction":"column","display":"flex","padding-left":"10","padding-top":"10","padding-right":"10","padding-bottom":"10"},"media":{"tablet":{"original":{"padding-left":"5","padding-top":"5","padding-right":"5","padding-bottom":"5"}}}},"children":[{"id":15,"name":"oxy-power-form-field","options":{"ct_id":15,"ct_parent":14,"selector":"-power-form-field-88-68","original":{"oxy-power-form-field_field_type":"submit","oxy-power-form-field_field_label":"Submit","oxy-power-form-field_field_name":"submit","oxy-power-form-field_field_background_background-color":"#caa782","oxy-power-form-field_field_border_border-top-style":"none","oxy-power-form-field_field_border_border-right-style":"none","oxy-power-form-field_field_border_border-bottom-style":"none","oxy-power-form-field_field_border_border-left-style":"none","oxy-power-form-field_field_typography_text-transform":"uppercase","oxy-power-form-field_field_typography_letter-spacing":"3","oxy-power-form-field_field_typography_font-weight":"600","oxy-power-form-field_field_typography_color":"#ffffff","oxy-power-form-field_label_margin_margin-top":"26","oxy-power-form-field_field_padding_padding-top":"13","oxy-power-form-field_field_padding_padding-bottom":"12"},"media":{"phone-landscape":{"original":{"oxy-power-form-field_label_margin_margin-top":"0"}}},"ct_content":""},"depth":false}],"depth":1}],"depth":1}],"depth":false},{"id":16,"name":"ct_div_block","options":{"ct_id":16,"ct_parent":1,"selector":"div_block-89-68","classes":["opp-newsletter-form-2-success-message"],"activeselector":"opp-newsletter-form-2-success-message"},"children":[{"id":17,"name":"ct_headline","options":{"ct_id":17,"ct_parent":16,"selector":"headline-90-68","original":{"color":"#000000"},"ct_content":"Thank You"},"depth":false}],"depth":"3"}],"depth":"2"}],"meta_keys":[]}', classes: '{"opp-nf2":{"original":{}},"opp-newsletter-form-2":{"original":{"width-unit":"%","width":"100","background-color":"#45403b","color":"#ffffff","position":"relative"}},"opp-newsletter-form-2-success-message":{"original":{"position":"absolute","top":"0","left":"0","bottom":"0","right":"0","background-color":"rgba(255,255,255,0.9)","flex-direction":"column","display":"flex","align-items":"center","text-align":"center","justify-content":"center","visibility":"hidden"}}}'}
                    ],
                    premadeFormsModal: false,
                    insertPremadeForm: function(premadeForm){
                        jQuery.each( JSON.parse(premadeForm.classes), function( key, value ) {
                            if( !(key in $scope.iframeScope.classes) ) {
                                $scope.iframeScope.classes[key] = value;
                            }
                        } );
                        $scope.iframeScope.addReusableChildren(JSON.parse(premadeForm.data),0);
                        $scope.oxyPowerPack.premadeForms.premadeFormsModal=false;

                    }
                },
                eventticket:{
                    insertShortcodeToButtonUrl: function(text) {
                        text=text.replace(/\"/ig, "'");
                        var id = $scope.iframeScope.component.active.id;
                        $scope.iframeScope.setOptionModel('button_url', text, id);
                    }
                },
                tooltips:{
                    tooltipsModal: false,
                    currentTooltip: null,
                    showTooltipsModal: function(){
                        var tooltip = $scope.iframeScope.getOption('oxyPowerPackTooltip', $scope.iframeScope.component.active.id);
                        if( tooltip == '') {
                            tooltip = {
                                type: 'text',
                                animation: 'fade',
                                arrow: true,
                                content: 'This is a tooltip',
                                contentCopy: false,
                                maxWidth: '350px',
                                placement: 'top',
                                trigger: 'mouseenter focus',
                                theme: ''
                            };
                            $scope.iframeScope.setOptionModel('oxyPowerPackTooltip', tooltip ,$scope.iframeScope.component.active.id);
                        }
                        $scope.oxyPowerPack.tooltips.currentTooltip = tooltip;
                        $scope.oxyPowerPack.tooltips.tooltipsModal = true;
                    },
                    stopEditingTooltip: function(){
                        $scope.oxyPowerPack.tooltips.applyCurrentTooltip();
                        $scope.oxyPowerPack.tooltips.tooltipsModal = false;
                        $scope.oxyPowerPack.tooltips.currentTooltip = null;
                    },
                    deleteTooltip: function(){
                        $scope.oxyPowerPack.tooltips.tooltipsModal = false;
                        $scope.oxyPowerPack.tooltips.currentTooltip = null;
                        $scope.iframeScope.setOptionModel('oxyPowerPackTooltip', $scope.oxyPowerPack.tooltips.currentTooltip ,$scope.iframeScope.component.active.id);
                    },
                    applyCurrentTooltip: function(){
                        //sanitize
                        var currentTooltip = $scope.oxyPowerPack.tooltips.currentTooltip;
                        for (var prop in currentTooltip) {
                            if (Object.prototype.hasOwnProperty.call(currentTooltip, prop) && typeof currentTooltip[prop].replace == 'function') {
                                currentTooltip[prop] = currentTooltip[prop].replace(/"/g,'');
                                currentTooltip[prop] = currentTooltip[prop].replace(/'/g,'');
                                currentTooltip[prop] = currentTooltip[prop].replace(/´/g,'');
                            }
                        }
                        $scope.iframeScope.setOptionModel('oxyPowerPackTooltip', $scope.oxyPowerPack.tooltips.currentTooltip ,$scope.iframeScope.component.active.id);
                    }
                },
                powermap:{
                    infoModal: false,
                    createMap: function(container){
                        var mapStyle = null;
                        var base_map = $scope.iframeScope.getOption('oxy-power-map_base_map');
                        switch(base_map){
                            case 'osm':
                                mapStyle = {
                                    'version': 8,
                                    'sources': {
                                        'raster-tiles': {
                                            'type': 'raster',
                                            'tiles': [
                                                'https://a.tile.openstreetmap.org/{z}/{x}/{y}.png',
                                                'https://b.tile.openstreetmap.org/{z}/{x}/{y}.png',
                                                'https://c.tile.openstreetmap.org/{z}/{x}/{y}.png'
                                            ],
                                            'tileSize': 256,
                                            'attribution':
                                                ''
                                        }
                                    },
                                    'layers': [
                                        {
                                            'id': 'simple-tiles',
                                            'type': 'raster',
                                            'source': 'raster-tiles',
                                            'maxZoom': 17
                                        }
                                    ]
                                };
                                break;
                            case 'mapbox_streets':
                                mapStyle = 'mapbox://styles/mapbox/streets-v11';
                                break;
                            case 'mapbox_outdoors':
                                mapStyle = 'mapbox://styles/mapbox/outdoors-v11';
                                break;
                            case 'mapbox_light':
                                mapStyle = 'mapbox://styles/mapbox/light-v10';
                                break;
                            case 'mapbox_dark':
                                mapStyle = 'mapbox://styles/mapbox/dark-v10';
                                break;
                            case 'mapbox_satellite':
                                mapStyle = 'mapbox://styles/mapbox/satellite-v9';
                                break;
                            case 'mapbox_satellite_streets':
                                mapStyle = 'mapbox://styles/mapbox/satellite-streets-v11';
                                break;
                        }
                        if(OxyPowerPackBEData.mapbox_key.trim() != ''){
                            mapboxgl.accessToken = OxyPowerPackBEData.mapbox_key.trim();
                        }

                        var mapa = new mapboxgl.Map({
                            container: container, // container id
                            style: mapStyle,
                            center: [$scope.iframeScope.getOption('oxy-power-map_map_lng'), $scope.iframeScope.getOption('oxy-power-map_map_lat')], // starting position
                            zoom: $scope.iframeScope.getOption('oxy-power-map_map_zoom'), // starting zoom
                            pitch: container == 'oppLocationMap' ? $scope.iframeScope.getOption('oxy-power-map_map_pitch') : 0,
                            bearing: container == 'oppLocationMap' ? $scope.iframeScope.getOption('oxy-power-map_map_bearing') : 0,
                            attributionControl: false
                        });
                        var nav = new mapboxgl.NavigationControl();
                        mapa.addControl(nav, 'bottom-right');
                        if(container != "oppFeaturesMap" && OxyPowerPackBEData.mapbox_key.trim() != ''){
                            mapa.addControl(
                                new MapboxGeocoder({
                                    accessToken: mapboxgl.accessToken,
                                    mapboxgl: mapboxgl,
                                    marker: false
                                })
                            );
                        }

                        if(container == "oppFeaturesMap"){
                            $scope.oxyPowerPack.powermap.featuresDrawObject = new MapboxDraw({
                                userProperties: true,
                                controls: {
                                    combine_features: false,
                                    uncombine_features: false
                                },
                                styles: [
                                    {
                                        'id': 'highlight-active-points',
                                        'type': 'circle',
                                        'filter': ['all',
                                            ['==', '$type', 'Point'],
                                            ['==', 'meta', 'feature'],
                                            ['==', 'active', 'true']],
                                        'paint': {
                                            'circle-radius': 7,
                                            'circle-color': '#0000FF'
                                        }
                                    },
                                    {
                                        'id': 'points-inactive',
                                        'type': 'circle',
                                        'filter': ['all',
                                            ['==', '$type', 'Point'],
                                            ['==', 'meta', 'feature'],
                                            ['==', 'active', 'false']],
                                        'paint': {
                                            'circle-radius': 5,
                                            'circle-color': [ "get", "user_stroke_color" ]
                                        }
                                    },
                                    // ACTIVE (being drawn)
                                    // line stroke
                                    {
                                        "id": "gl-draw-line",
                                        "type": "line",
                                        "filter": ["all", ["==", "$type", "LineString"], ["==", "active", "true"]],//["!=", "mode", "static"]],
                                        "layout": {
                                            "line-cap": "round",
                                            "line-join": "round"
                                        },
                                        "paint": {
                                            "line-color": "#D20C0C",
                                            "line-dasharray": [0.2, 2],
                                            "line-width": 2
                                        }
                                    },
                                    // polygon fill
                                    {
                                        "id": "gl-draw-polygon-fill",
                                        "type": "fill",
                                        "filter": ["all", ["==", "$type", "Polygon"], ["==", "active", "true"]],
                                        "paint": {
                                            "fill-color": "#D20C0C",
                                            "fill-outline-color": "#D20C0C",
                                            "fill-opacity": 0.1
                                        }
                                    },
                                    // polygon outline stroke
                                    // This doesn't style the first edge of the polygon, which uses the line stroke styling instead
                                    {
                                        "id": "gl-draw-polygon-stroke-active",
                                        "type": "line",
                                        "filter": ["all", ["==", "$type", "Polygon"], ["==", "active", "true"]],
                                        "layout": {
                                            "line-cap": "round",
                                            "line-join": "round"
                                        },
                                        "paint": {
                                            "line-color": "#D20C0C",
                                            "line-dasharray": [0.2, 2],
                                            "line-width": 2
                                        }
                                    },
                                    // vertex point halos
                                    {
                                        "id": "gl-draw-polygon-and-line-vertex-halo-active",
                                        "type": "circle",
                                        "filter": ["all", ["==", "meta", "vertex"], ["==", "$type", "Point"], ["!=", "mode", "static"]],
                                        "paint": {
                                            "circle-radius": 5,
                                            "circle-color": "#FFF"
                                        }
                                    },
                                    // vertex points
                                    {
                                        "id": "gl-draw-polygon-and-line-vertex-active",
                                        "type": "circle",
                                        "filter": ["all", ["==", "meta", "vertex"], ["==", "$type", "Point"], ["!=", "mode", "static"]],
                                        "paint": {
                                            "circle-radius": 3,
                                            "circle-color": "#D20C0C",
                                        }
                                    },

                                    // INACTIVE (static, already drawn)
                                    // line stroke
                                    {
                                        'id': 'gl-draw-line-inactive',
                                        'type': 'line',
                                        'filter': ['all', ['==', 'active', 'false'],
                                            ['==', '$type', 'LineString'],
                                            ['==', 'meta', 'feature'],
                                        ],
                                        'layout': {
                                            'line-cap': 'round',
                                            'line-join': 'round'
                                        },
                                        'paint': {
                                            "line-color": [ "get", "user_stroke_color" ],
                                            "line-width": [ "get", "user_stroke_width" ]
                                        }
                                    },
                                    // polygon fill
                                    {
                                        "id": "gl-draw-polygon-fill-inactive",
                                        "type": "fill",
                                        "filter": ['all',
                                            ['==', '$type', 'Polygon'],
                                            ['==', 'meta', 'feature'],
                                            ['==', 'active', 'false']
                                        ] ,
                                        "paint": {
                                            "fill-color": ["get", "user_fill_color"],
                                            "fill-outline-color": ["get", "user_stroke_color"],
                                            "fill-opacity": ["get", "user_fill_opacity"]
                                        }
                                    },
                                    {
                                        'id': 'gl-draw-polygon-stroke-inactive',
                                        'type': 'line',
                                        'filter': ['all', ['==', 'active', 'false'],
                                            ['==', 'meta', 'feature'],
                                            ['==', '$type', 'Polygon']
                                        ],
                                        'layout': {
                                            'line-cap': 'round',
                                            'line-join': 'round'
                                        },
                                        'paint': {
                                            "line-color": [ "get", "user_stroke_color" ],
                                            "line-width": [ "get", "user_stroke_width" ]
                                        }
                                    }
                                ]
                            });
                            mapa.addControl($scope.oxyPowerPack.powermap.featuresDrawObject);
                            mapa.on('draw.selectionchange', function (e) {
                                if(e.features.length == 1){
                                    $scope.oxyPowerPack.powermap.currentlyEditingFeature = e.features[0];
                                    jQuery('#editPropertiesControl').show();
                                }else{
                                    jQuery('#editPropertiesControl').hide();
                                }
                            });
                            mapa.on('draw.create', function (e) {
                                switch(e.features[0].geometry.type){
                                    case "Polygon":
                                        $scope.oxyPowerPack.powermap.featuresDrawObject.setFeatureProperty(e.features[0].id,'fill_color',"#3FB1CE");
                                        $scope.oxyPowerPack.powermap.featuresDrawObject.setFeatureProperty(e.features[0].id,'fill_opacity',0.1);
                                        $scope.oxyPowerPack.powermap.featuresDrawObject.setFeatureProperty(e.features[0].id,'stroke_color',"#3FB1CE");
                                        $scope.oxyPowerPack.powermap.featuresDrawObject.setFeatureProperty(e.features[0].id,'stroke_width',2);
                                        break;
                                    case "Point":
                                        $scope.oxyPowerPack.powermap.featuresDrawObject.setFeatureProperty(e.features[0].id,'stroke_color','#3FB1CE');
                                        break;
                                    case "LineString":
                                        $scope.oxyPowerPack.powermap.featuresDrawObject.setFeatureProperty(e.features[0].id,'stroke_color','#3FB1CE');
                                        $scope.oxyPowerPack.powermap.featuresDrawObject.setFeatureProperty(e.features[0].id,'stroke_width',2);
                                        break;
                                }
                                $scope.oxyPowerPack.powermap.featuresDrawObject.setFeatureProperty(e.features[0].id,'description','');
                            });

                            function editFeaturesControl() {};
                            editFeaturesControl.prototype.onAdd = function(map) {
                                this._map = map;
                                this._container = document.createElement('div');
                                this._container.id = "editPropertiesControl";
                                this._container.className = 'mapboxgl-ctrl-group mapboxgl-ctrl';
                                this._container.innerHTML = '<button class="mapbox-gl-draw_ctrl-draw-btn">✍</button>';
                                this._container.style.display = 'none';
                                this._container.onclick = $scope.oxyPowerPack.powermap.openPropertiesModal;
                                return this._container;
                            };
                            editFeaturesControl.prototype.onRemove = function() {
                                this._container.parentNode.removeChild(this._container);
                                this._map = undefined;
                            };
                            mapa.addControl(new editFeaturesControl());
                        }

                        return mapa;
                    },
                    locationModal: false,
                    locationMap: null,
                    openLocationMap: function(){
                        $scope.oxyPowerPack.powermap.locationModal = true;
                        setTimeout(function(){
                            $scope.oxyPowerPack.powermap.locationMap = $scope.oxyPowerPack.powermap.createMap('oppLocationMap');
                        },200);
                    },
                    closeLocationMap: function(){
                        $scope.oxyPowerPack.powermap.locationMap.remove();
                        $scope.oxyPowerPack.powermap.locationMap = null;
                        $scope.oxyPowerPack.powermap.locationModal = false;
                    },
                    saveLocationMap: function(){
                        var center = $scope.oxyPowerPack.powermap.locationMap.getCenter();
                        var zoom = $scope.oxyPowerPack.powermap.locationMap.getZoom();
                        var pitch = $scope.oxyPowerPack.powermap.locationMap.getPitch();
                        var bearing = $scope.oxyPowerPack.powermap.locationMap.getBearing();
                        $scope.iframeScope.setOptionModel('oxy-power-map_map_lng', center.lng);
                        $scope.iframeScope.setOptionModel('oxy-power-map_map_lat', center.lat);
                        $scope.iframeScope.setOptionModel('oxy-power-map_map_zoom', zoom);
                        $scope.iframeScope.setOptionModel('oxy-power-map_map_pitch', pitch);
                        $scope.iframeScope.setOptionModel('oxy-power-map_map_bearing', bearing);
                        $scope.oxyPowerPack.powermap.closeLocationMap();
                        $scope.iframeScope.rebuildDOM($scope.iframeScope.component.active.id)
                    },
                    featuresModal: false,
                    featuresMap: null,
                    featuresDrawObject:null,
                    openFeaturesMap: function(){
                        $scope.oxyPowerPack.powermap.featuresModal = true;
                        setTimeout(function(){
                            $scope.oxyPowerPack.powermap.featuresMap = $scope.oxyPowerPack.powermap.createMap('oppFeaturesMap');
                            var featureJson = $scope.iframeScope.getOption('oxy-power-map_geojson');
                            if(featureJson.trim() != '' ) {
                                if(featureJson.includes('oxy_base64_encoded::')){
                                    featureJson = atob( featureJson.replace('oxy_base64_encoded::', '') );
                                }
                                var featuresData = JSON.parse(featureJson);
                                if(featuresData){
                                    $scope.oxyPowerPack.powermap.featuresDrawObject.add(featuresData);
                                }
                            }
                        },200);
                    },
                    closeFeaturesMap: function(){
                        $scope.oxyPowerPack.powermap.featuresMap.remove();
                        $scope.oxyPowerPack.powermap.featuresMap = null;
                        $scope.oxyPowerPack.powermap.featuresDrawObject = null;
                        $scope.oxyPowerPack.powermap.featuresModal = false;
                    },
                    saveFeaturesMap: function(){
                        //var center = $scope.oxyPowerPack.powermap.featuresMap.getCenter();
                        //$scope.iframeScope.setOptionModel('oxy-power-map_map_lng', center.lng);
                        var featuresData = 'oxy_base64_encoded::'+btoa(JSON.stringify($scope.oxyPowerPack.powermap.featuresDrawObject.getAll()));
                        $scope.iframeScope.setOptionModel('oxy-power-map_geojson', featuresData);
                        $scope.oxyPowerPack.powermap.closeFeaturesMap();
                        $scope.iframeScope.rebuildDOM($scope.iframeScope.component.active.id)
                    },
                    propertiesModal: false,
                    openPropertiesModal: function(){
                        switch($scope.oxyPowerPack.powermap.currentlyEditingFeature.geometry.type){
                            case "Polygon":
                                jQuery('#powermap_properties_fill_color').val($scope.oxyPowerPack.powermap.currentlyEditingFeature.properties.fill_color);
                                jQuery('#powermap_properties_fill_opacity').val($scope.oxyPowerPack.powermap.currentlyEditingFeature.properties.fill_opacity);
                                jQuery('#powermap_properties_stroke_color').val($scope.oxyPowerPack.powermap.currentlyEditingFeature.properties.stroke_color);
                                jQuery('#powermap_properties_stroke_width').val($scope.oxyPowerPack.powermap.currentlyEditingFeature.properties.stroke_width);
                                break;
                            case "LineString":
                                jQuery('#powermap_properties_stroke_color').val($scope.oxyPowerPack.powermap.currentlyEditingFeature.properties.stroke_color);
                                jQuery('#powermap_properties_stroke_width').val($scope.oxyPowerPack.powermap.currentlyEditingFeature.properties.stroke_width);
                                break;
                            case "Point":
                                jQuery('#powermap_properties_stroke_color').val($scope.oxyPowerPack.powermap.currentlyEditingFeature.properties.stroke_color);
                                break;
                        }
                        if(!$scope.oxyPowerPack.powermap.currentlyEditingFeature.properties.popup_open) $scope.oxyPowerPack.powermap.currentlyEditingFeature.properties.popup_open = 'false';
                        jQuery('#powermap_properties_popup_open').prop('checked', $scope.oxyPowerPack.powermap.currentlyEditingFeature.properties.popup_open == 'true' );
                        jQuery('#powermap_properties_description').val($scope.oxyPowerPack.powermap.currentlyEditingFeature.properties.description);
                        $scope.oxyPowerPack.powermap.propertiesModal = true;
                    },
                    closePropertiesModal: function(){
                        $scope.oxyPowerPack.powermap.propertiesModal = false;
                    },
                    saveProperties: function(){
                        switch($scope.oxyPowerPack.powermap.currentlyEditingFeature.geometry.type){
                            case "Polygon":
                                $scope.oxyPowerPack.powermap.featuresDrawObject.setFeatureProperty($scope.oxyPowerPack.powermap.currentlyEditingFeature.id,'fill_color',jQuery('#powermap_properties_fill_color').val());
                                $scope.oxyPowerPack.powermap.featuresDrawObject.setFeatureProperty($scope.oxyPowerPack.powermap.currentlyEditingFeature.id,'fill_opacity',parseFloat(jQuery('#powermap_properties_fill_opacity').val()));
                                $scope.oxyPowerPack.powermap.featuresDrawObject.setFeatureProperty($scope.oxyPowerPack.powermap.currentlyEditingFeature.id,'stroke_color',jQuery('#powermap_properties_stroke_color').val());
                                $scope.oxyPowerPack.powermap.featuresDrawObject.setFeatureProperty($scope.oxyPowerPack.powermap.currentlyEditingFeature.id,'stroke_width',parseInt(jQuery('#powermap_properties_stroke_width').val()));
                                break;
                            case "LineString":
                                $scope.oxyPowerPack.powermap.featuresDrawObject.setFeatureProperty($scope.oxyPowerPack.powermap.currentlyEditingFeature.id,'stroke_color',jQuery('#powermap_properties_stroke_color').val());
                                $scope.oxyPowerPack.powermap.featuresDrawObject.setFeatureProperty($scope.oxyPowerPack.powermap.currentlyEditingFeature.id,'stroke_width',parseInt(jQuery('#powermap_properties_stroke_width').val()));
                                break;
                            case "Point":
                                $scope.oxyPowerPack.powermap.featuresDrawObject.setFeatureProperty($scope.oxyPowerPack.powermap.currentlyEditingFeature.id,'stroke_color',jQuery('#powermap_properties_stroke_color').val());
                                break;
                        }
                        $scope.oxyPowerPack.powermap.featuresDrawObject.setFeatureProperty($scope.oxyPowerPack.powermap.currentlyEditingFeature.id,'popup_open',jQuery('#powermap_properties_popup_open').is(":checked") ? 'true' : 'false');
                        $scope.oxyPowerPack.powermap.featuresDrawObject.setFeatureProperty($scope.oxyPowerPack.powermap.currentlyEditingFeature.id,'description',jQuery('#powermap_properties_description').val());
                        $scope.oxyPowerPack.powermap.closePropertiesModal();
                    }
                },
                attributes:{
                    currentAttribute: null,
                    attributesModal: false,
                    migrateAttributes: function(){
                        var id = $scope.iframeScope.component.active.id;
                        var name = $scope.iframeScope.component.active.name;

                        var customAttributes = $scope.iframeScope.component.options[id]['model']['custom-attributes'];

                        var type = typeof(customAttributes);

                        if(type === 'string' || type === 'undefined') {
                            customAttributes = [];
                        }


                        var OPPattributes = $scope.iframeScope.getOption('oxyPowerPackAttributes', id);
                        if( OPPattributes == '') {
                            OPPattributes = [];
                        }

                        if(OPPattributes.length == 0){
                            alert("You don't have any custom attribute");
                            return;
                        }

                        var r = confirm("Move Attributes to Native Oxygen Builder\nSince V3.5 RC 1, Oxygen Builder includes a custom attributes feature that is more powerful than the one included with OxyPowerPack. Do you want to move your OxyPowerPack attributes to Oxygen Native attributes?");
                        if (r != true) {
                            return;
                        }

                        for (var i = 0; i < OPPattributes.length; i++) {
                            var tmp = OPPattributes[i].value;
                            if(tmp.includes('oxy_base64_encoded::')){
                                tmp = atob( tmp.replace('oxy_base64_encoded::', '') );
                            }
                            customAttributes.push({
                                name: OPPattributes[i].name,
                                value: tmp,
                            });
                        }

                        OPPattributes = [];
                        $scope.iframeScope.setOptionModel('oxyPowerPackAttributes', OPPattributes ,id);

                        $scope.iframeScope.component.options[id]['model']['custom-attributes'] = customAttributes;
                        $scope.iframeScope.setOption(id, name, 'custom-attributes');
                        alert("Done! All your custom attributes for this element are now available at Advanced > Custom Attributes");
                    },
                    addAttribute: function(){
                        var attributes = $scope.iframeScope.getOption('oxyPowerPackAttributes', $scope.iframeScope.component.active.id);
                        if( attributes == '') {
                            attributes = [];
                        }
                        attributes.push({name: '', value: ''});
                        $scope.iframeScope.setOptionModel('oxyPowerPackAttributes', attributes ,$scope.iframeScope.component.active.id);
                        $scope.oxyPowerPack.attributes.editAttribute(attributes[attributes.length-1]);
                    },
                    editAttribute: function(attribute){
                        $scope.oxyPowerPack.attributes.currentAttribute = attribute;

                        jQuery('#oppCurrentAttributeName').val($scope.oxyPowerPack.attributes.currentAttribute.name);
                        var tmp = $scope.oxyPowerPack.attributes.currentAttribute.value;
                        if(tmp.includes('oxy_base64_encoded::')){
                            tmp = atob( tmp.replace('oxy_base64_encoded::', '') );
                        }
                        jQuery('#oppCurrentAttributeValue').val(tmp);
                    },
                    removeAttributeAtIndex: function(index){
                        var attributes = $scope.iframeScope.getOption('oxyPowerPackAttributes', $scope.iframeScope.component.active.id);
                        attributes.splice(index, 1);
                        $scope.iframeScope.setOptionModel('oxyPowerPackAttributes', attributes ,$scope.iframeScope.component.active.id);
                    },
                    stopEditingAttribute: function(){
                        if($scope.oxyPowerPack.attributes.currentAttribute == null) return;
                        var attributes = $scope.iframeScope.getOption('oxyPowerPackAttributes', $scope.iframeScope.component.active.id);
                        $scope.oxyPowerPack.attributes.currentAttribute.name = jQuery('#oppCurrentAttributeName').val();
                        $scope.oxyPowerPack.attributes.currentAttribute.value = jQuery('#oppCurrentAttributeValue').val();
                        jQuery('#oppCurrentAttributeName').val('');
                        jQuery('#oppCurrentAttributeValue').val('');
                        $scope.oxyPowerPack.attributes.currentAttribute.name = $scope.oxyPowerPack.attributes.currentAttribute.name.replace(/ /g,'-');
                        $scope.oxyPowerPack.attributes.currentAttribute.name = $scope.oxyPowerPack.attributes.currentAttribute.name.replace(/[^a-zA-Z\-]/g,'');
                        //$scope.oxyPowerPack.attributes.currentAttribute.name = $scope.oxyPowerPack.attributes.currentAttribute.name.replace(/'/g,'');
                        //$scope.oxyPowerPack.attributes.currentAttribute.name = $scope.oxyPowerPack.attributes.currentAttribute.name.replace(/=/g,'');
                        //$scope.oxyPowerPack.attributes.currentAttribute.name = $scope.oxyPowerPack.attributes.currentAttribute.name.replace(/\d/g,'');
                        $scope.oxyPowerPack.attributes.currentAttribute.value = $scope.oxyPowerPack.attributes.currentAttribute.value.replace(/"/g,'\'');
                        $scope.oxyPowerPack.attributes.currentAttribute.value = 'oxy_base64_encoded::'+btoa($scope.oxyPowerPack.attributes.currentAttribute.value);
                        var name = $scope.oxyPowerPack.attributes.currentAttribute.name.trim();
                        if( name == '' || name.toLowerCase() == 'id' || name.toLowerCase() == 'class' ){
                            var index = attributes.indexOf($scope.oxyPowerPack.attributes.currentAttribute);
                            if (index >= 0) attributes.splice(index, 1);
                        }
                        $scope.iframeScope.setOptionModel('oxyPowerPackAttributes', attributes ,$scope.iframeScope.component.active.id);
                        $scope.oxyPowerPack.attributes.currentAttribute = null;
                    }
                },
                richtext:{
                    option: null,
                    richTextModal: false,
                    windowTitle: 'Rich Text Editor',
                    open: function(option, title){
                        $scope.oxyPowerPack.richtext.windowTitle = title;
                        $scope.oxyPowerPack.richtext.richTextModal=true;
                        $scope.oxyPowerPack.richtext.option = option;
                        var content = $scope.iframeScope.getOption($scope.oxyPowerPack.richtext.option);

                        if(content == ''){
                            if(title.includes('Admin')){
                                content ="Hey\n" +
                                    "\n" +
                                    "A visitor has just filled the form in your website. Here's the full information:\n" +
                                    "\n" +
                                    "[full_data]";
                            }else{
                                content = "This email is to inform you that we have received your information. You should hear back from us shortly.\n" +
                                    "\n" +
                                    "Thank you.";
                            }
                        }

                        if(content.includes('oxy_base64_encoded::')){
                            content = atob( content.replace('oxy_base64_encoded::', '') );
                        }

                        if ( tinyMCE.get("oxyPowerPackTinyMce") ) {
                            tinyMCE.get("oxyPowerPackTinyMce").setContent(content);
                        } else{
                            jQuery('#oxyPowerPackTinyMce').val(content);
                        }
                        var activeComponent = $scope.iframeScope.getComponentById($scope.iframeScope.component.active.id);
                        var tags = "[full_data] ";
                        jQuery(activeComponent[0]).find('.label-and-field-wrapper').each(function(){
                            tags+='['+jQuery(this).data('oppfield') + '] ';
                        });
                        jQuery('#oppFormTags').html(tags);
                    },
                    close: function(){
                        $scope.oxyPowerPack.richtext.richTextModal=false;
                        $scope.oxyPowerPack.richtext.option = null;
                    },
                    save: function(){
                        var content = "";

                        if ( tinyMCE.get("oxyPowerPackTinyMce") ) {
                            content = tinyMCE.get("oxyPowerPackTinyMce").getContent();
                        }
                        else {
                            content = jQuery('#oxyPowerPackTinyMce').val();
                        }

                        content = 'oxy_base64_encoded::' + btoa(content);

                        $scope.iframeScope.setOptionModel($scope.oxyPowerPack.richtext.option, content);
                        $scope.oxyPowerPack.richtext.close();
                        $scope.oxyPowerPack.richtext.windowTitle='Rich Text Editor';
                    }
                },
                formOptions:{
                    currentFormOption: null,
                    formOptionsModal: false,
                    addFormOption: function(){
                        if($scope.oxyPowerPack.formOptions.currentFormOption != null){
                            $scope.oxyPowerPack.formOptions.stopEditingFormOption();
                        }
                        var options = $scope.iframeScope.getOption('oxy-power-form-field_oxyPowerPackFormOptions', $scope.iframeScope.component.active.id);
                        if( options == '') {
                            options = [];
                        }
                        options.push({label: 'oxy_base64_encoded::'+btoa('Option ' + (options.length +1)), value: 'oxy_base64_encoded::'+btoa('option_' + (options.length+1))});
                        $scope.iframeScope.setOptionModel('oxy-power-form-field_oxyPowerPackFormOptions', options ,$scope.iframeScope.component.active.id);
                        $scope.iframeScope.setOptionModel('oxy-power-form-field_oxyPowerPackFormOptionsSerialized', btoa(JSON.stringify(options)) ,$scope.iframeScope.component.active.id);
                        $scope.iframeScope.rebuildDOM($scope.iframeScope.component.active.id);
                        var formOptions = $scope.iframeScope.getOption('oxy-power-form-field_oxyPowerPackFormOptions', $scope.iframeScope.component.active.id);
                        $scope.oxyPowerPack.formOptions.editFormOption(formOptions[options.length-1]);
                    },
                    editFormOption: function(option){
                        $scope.oxyPowerPack.formOptions.currentFormOption = option;

                        var tmp = $scope.oxyPowerPack.formOptions.currentFormOption.label;
                        if(tmp.includes('oxy_base64_encoded::')){
                            tmp = atob( tmp.replace('oxy_base64_encoded::', '') );
                        }
                        jQuery('#oppCurrentFormOptionLabel').val(tmp);

                        var tmp = $scope.oxyPowerPack.formOptions.currentFormOption.value;
                        if(tmp.includes('oxy_base64_encoded::')){
                            tmp = atob( tmp.replace('oxy_base64_encoded::', '') );
                        }
                        jQuery('#oppCurrentFormOptionValue').val(tmp);
                    },
                    removeFormOptionAtIndex: function(index){
                        var formOptions = $scope.iframeScope.getOption('oxy-power-form-field_oxyPowerPackFormOptions', $scope.iframeScope.component.active.id);
                        formOptions.splice(index, 1);
                        $scope.iframeScope.setOptionModel('oxy-power-form-field_oxyPowerPackFormOptions', formOptions ,$scope.iframeScope.component.active.id);
                        $scope.iframeScope.setOptionModel('oxy-power-form-field_oxyPowerPackFormOptionsSerialized', btoa(JSON.stringify(formOptions)) ,$scope.iframeScope.component.active.id);
                        $scope.iframeScope.rebuildDOM($scope.iframeScope.component.active.id);
                    },
                    stopEditingFormOption: function(){
                        if($scope.oxyPowerPack.formOptions.currentFormOption == null) return;
                        var formOptions = $scope.iframeScope.getOption('oxy-power-form-field_oxyPowerPackFormOptions', $scope.iframeScope.component.active.id);
                        $scope.oxyPowerPack.formOptions.currentFormOption.label = jQuery('#oppCurrentFormOptionLabel').val();
                        $scope.oxyPowerPack.formOptions.currentFormOption.value = jQuery('#oppCurrentFormOptionValue').val();
                        jQuery('#oppCurrentFormOptionLabel').val('');
                        jQuery('#oppCurrentFormOptionValue').val('');
                        $scope.oxyPowerPack.formOptions.currentFormOption.label = 'oxy_base64_encoded::'+btoa($scope.oxyPowerPack.formOptions.currentFormOption.label);
                        $scope.oxyPowerPack.formOptions.currentFormOption.value = $scope.oxyPowerPack.formOptions.currentFormOption.value.replace(/"/g,'\'');
                        $scope.oxyPowerPack.formOptions.currentFormOption.value = 'oxy_base64_encoded::'+btoa($scope.oxyPowerPack.formOptions.currentFormOption.value);

                        $scope.iframeScope.setOptionModel('oxy-power-form-field_oxyPowerPackFormOptions', formOptions ,$scope.iframeScope.component.active.id);
                        $scope.iframeScope.setOptionModel('oxy-power-form-field_oxyPowerPackFormOptionsSerialized', btoa(JSON.stringify(formOptions)) ,$scope.iframeScope.component.active.id);
                        $scope.oxyPowerPack.formOptions.currentFormOption = null;
                        $scope.iframeScope.rebuildDOM($scope.iframeScope.component.active.id);
                    }
                },
                textRotator:{
                    currentText: null,
                    textRotatorModal: false,
                    addText: function(){
                        var attributes = $scope.iframeScope.getOption('oxyPowerPackTextRotator', $scope.iframeScope.component.active.id);
                        if( attributes == '') {
                            attributes = [];
                        }
                        attributes.push({text:'Sample text'});
                        $scope.iframeScope.setOptionModel('oxyPowerPackTextRotator', attributes ,$scope.iframeScope.component.active.id);
                        $scope.oxyPowerPack.textRotator.currentText = attributes[attributes.length-1];
                    },
                    removeTextAtIndex: function(index){
                        var attributes = $scope.iframeScope.getOption('oxyPowerPackTextRotator', $scope.iframeScope.component.active.id);
                        attributes.splice(index, 1);
                        $scope.iframeScope.setOptionModel('oxyPowerPackTextRotator', attributes ,$scope.iframeScope.component.active.id);
                    },
                    stopEditingText: function(){
                        if($scope.oxyPowerPack.textRotator.currentText == null) return;
                        var attributes = $scope.iframeScope.getOption('oxyPowerPackTextRotator', $scope.iframeScope.component.active.id);
                        $scope.oxyPowerPack.textRotator.currentText.text = $scope.oxyPowerPack.textRotator.currentText.text.replace(/"/g,'');
                        $scope.oxyPowerPack.textRotator.currentText.text = $scope.oxyPowerPack.textRotator.currentText.text.replace(/'/g,'');
                        var name = $scope.oxyPowerPack.textRotator.currentText.text.trim();
                        if( name == '' || name.toLowerCase() == 'id' || name.toLowerCase() == 'class' ){
                            var index = attributes.indexOf($scope.oxyPowerPack.textRotator.currentText);
                            if (index >= 0) attributes.splice(index, 1);
                        }
                        $scope.iframeScope.setOptionModel('oxyPowerPackTextRotator', attributes ,$scope.iframeScope.component.active.id);
                        $scope.oxyPowerPack.textRotator.currentText = null;
                    }
                },
                parallaxOptions:[
                    {title:'-10 - Slowest', value: -10},
                    {title:'-9.5', value: -9.5},
                    {title:'-9', value: -9},
                    {title:'-8.5', value: -8.5},
                    {title:'-8', value: -8},
                    {title:'-7.5', value: -7.5},
                    {title:'-7', value: -7},
                    {title:'-6.5', value: -6.5},
                    {title:'-6', value: -6},
                    {title:'-5.5', value: -5.5},
                    {title:'-5', value: -5},
                    {title:'-4.5', value: -4.5},
                    {title:'-4', value: -4},
                    {title:'-3.5', value: -3.5},
                    {title:'-3', value: -3},
                    {title:'-2.5', value: -2.5},
                    {title:'-2', value: -2},
                    {title:'-1.5', value: -1.5},
                    {title:'-1', value: -1},
                    {title:'-0.5', value: -0.5},
                    {title:'0 - Normal (parallax disabled)', value: 0},
                    {title:'0.5', value: 0.5},
                    {title:'1', value: 1},
                    {title:'1.5', value: 1.5},
                    {title:'2', value: 2},
                    {title:'2.5', value: 2.5},
                    {title:'3', value: 3},
                    {title:'3.5', value: 3.5},
                    {title:'4', value: 4},
                    {title:'4.5', value: 4.5},
                    {title:'5', value: 5},
                    {title:'5.5', value: 5.5},
                    {title:'6', value: 6},
                    {title:'6.5', value: 6.5},
                    {title:'7', value: 7},
                    {title:'7.5', value: 7.5},
                    {title:'8', value: 8},
                    {title:'8.5', value: 8.5},
                    {title:'9', value: 9},
                    {title:'9.5', value: 9.5},
                    {title:'10 - Fastest', value: 10}
                ],
                events:
                {
                    currentEvent: null,
                    currentAction: null,
                    eventList: [
                        {
                            slug: 'page-load',
                            name: 'Page Load',
                            category: 'global',
                            description: 'Fires one time when the whole page has loaded, including all dependent resources such as stylesheets and images.',
                            defaultSettings: {
                            }
                        },
                        {
                            slug: 'scroll',
                            name: 'Scroll Down',
                            category: 'global',
                            description: 'Fires one time when the user scrolls down the specified percentage of the page.',
                            defaultSettings: {
                                percentage: 50
                            }
                        },
                        {
                            slug: 'countdown-finished',
                            name: 'CountDown Timer Finished',
                            category: 'global',
                            description: 'Fired by the specified OxyPowerPack CountDown component when it reaches zero seconds. Leaving the selector blank will make the event fire when any countdown in the page reaches zero.',
                            defaultSettings: {
                                'selector': ''
                            }
                        },
                        {
                            slug: 'powerform-submitted',
                            name: 'Power Form Submitted',
                            category: 'global',
                            description: 'Fires after the specified Power Form is successfully submitted. Leaving the selector blank will make the event fire after any Power Form in the page is submitted.',
                            defaultSettings: {
                                'selector': ''
                            }
                        },
                        {
                            slug: 'cf7-submit',
                            name: 'Contact Form 7 Submitted',
                            category: 'global',
                            description: 'Fires when a CF7 form is submitted by AJAX and a success response code is returned.',
                            defaultSettings: {
                            }
                        },
                        {
                            slug: 'click',
                            name: 'Click',
                            category: 'element',
                            description: 'Fires every time the user clicks the specified element.',
                            defaultSettings: {
                            }
                        },
                        {
                            slug: 'mouseover',
                            name: 'Mouse Over',
                            category: 'element',
                            description: 'Fires every time the user moves the mouse cursor over the specified element.',
                            defaultSettings: {
                            }
                        },
                        {
                            slug: 'mouseout',
                            name: 'Mouse Out',
                            category: 'element',
                            description: 'Fires every time the user moves the mouse cursor out of the specified element.',
                            defaultSettings: {
                            }
                        },
                        {
                            slug: 'enterviewport',
                            name: 'Enter Viewport',
                            category: 'element',
                            description: 'Fires every time the element enters the user\'s visible area of the page as a result of a scroll, a window resize or a DOM structure change.',
                            defaultSettings: {
                            }
                        },
                        {
                            slug: 'exitviewport',
                            name: 'Exit Viewport',
                            category: 'element',
                            description: 'Fires every time the element leaves the user\'s visible area of the page as a result of a scroll, a window resize or a DOM structure change.',
                            defaultSettings: {
                            }
                        },
                        {
                            slug: 'startoverlapping',
                            name: 'Start Overlapping Element',
                            category: 'element',
                            description: 'Fires every time the element starts overlapping the other specified element. IE. A sticky header passing through a section during the scroll.',
                            defaultSettings: {
                                'selector': ''
                            }
                        },
                        {
                            slug: 'finishoverlapping',
                            name: 'Finish Overlapping Element',
                            category: 'element',
                            description: 'Fires every time the element stops overlapping the other specified element. IE. A sticky header passing through a section during the scroll.',
                            defaultSettings: {
                                'selector': ''
                            }
                        }
                    ],
                    actionList: {
                        'hide': {
                            name: 'Hide',
                            description: 'If visible, hides element itself or the element specified in the selector box, with an optional fading animation.',
                            defaultAttributes:{
                                selector: '',
                                fading: true
                            }
                        },
                        'show': {
                            name: 'Show',
                            description: 'If hidden, makes visible element itself or the element specified in the selector box, with an optional fading animation.',
                            defaultAttributes:{
                                selector: '',
                                fading: true
                            }
                        },
                        'addclass': {
                            name: 'Add Class',
                            description: 'Adds the specified class to the element itself or to the element speficied in the selector box.',
                            defaultAttributes:{
                                selector: '',
                                class: 'sampleclass'
                            }
                        },
                        'removeclass': {
                            name: 'Remove Class',
                            description: 'Removes the specified class from the element itself or from the element speficied in the selector box.',
                            defaultAttributes:{
                                selector: '',
                                class: 'sampleclass'
                            }
                        },
                        'toggleclass': {
                            name: 'Toggle Class',
                            description: 'Toggles the specified class to the element itself or the element specified in the selector box.',
                            defaultAttributes:{
                                selector: '',
                                class: 'sampleclass'
                            }
                        },
                        'remove': {
                            name: 'Remove',
                            description: 'Removes the element itself from the DOM or the element specified in the selector box',
                            defaultAttributes:{
                                selector: ''
                            }
                        },
                        'settext': {
                            name: 'Set Text',
                            description: 'Replaces the element itself or the element specified in the selector box with the specified text. It is a good idea to use this action on text-only elements, like headings, paragraps or spans.',
                            defaultAttributes:{
                                selector: '',
                                text: 'Hello!'
                            }
                        },
                        'appendtext': {
                            name: 'Append Text',
                            description: 'Appends the text to the element itself, or to the element specified in the selector box',
                            defaultAttributes:{
                                selector: '',
                                text: 'Hello!'
                            }
                        },
                        'log': {
                            name: 'Log',
                            description: 'Outputs the specified text to the browser console, shown with developer tools open',
                            defaultAttributes:{
                                text: 'Hello!'
                            }
                        },
                        'alert': {
                            name: 'Alert',
                            description: 'Shows a browser alert dialog with the specified text',
                            defaultAttributes:{
                                text: 'Hello!'
                            }
                        },
                        'delay': {
                            name: 'Delay',
                            description: 'Pauses the execution of the action list for the specified amount of seconds',
                            defaultAttributes:{
                                seconds: 0.5
                            }
                        },
                        'redirect': {
                            name: 'Redirect',
                            description: 'Navigates to the specified location (URL)',
                            defaultAttributes:{
                                location: 'https://www.oxypowerpack.com'
                            }
                        },
                        'closemodal': {
                            name: 'Close Modal',
                            description: 'Close the currently open Oxygen Modal',
                            defaultAttributes:{
                            }
                        },
                        'openmodal': {
                            name: 'Open Modal',
                            description: 'Open the specified modal, itself if the event is set to a modal component, or the first modal found in the DOM',
                            defaultAttributes:{
                                selector: '',
                            }
                        },
                        'scrolltotop': {
                            name: 'Scroll To Top',
                            description: 'Smoothly scroll to the top of the page',
                            defaultAttributes:{
                                seconds: 0.25
                            }
                        },
                        'scrolltoelement': {
                            name: 'Scroll To Element',
                            description: 'Smoothly scroll the page to the specified element',
                            defaultAttributes:{
                                selector: '',
                                seconds: 0.25
                            }
                        }
                    },
                    addAction: function( actionSlug ){
                        var actions = $scope.iframeScope.getOption('oxyPowerPackEvent-' +$scope.oxyPowerPack.events.currentEvent.slug);
                        if( actions == '' || actions.length == 0) {
                            actions = [];
                            $scope.iframeScope.setOptionModel('oxyPowerPackEvent-'+$scope.oxyPowerPack.events.currentEvent.slug + '-settings', Object.assign({}, $scope.oxyPowerPack.events.currentEvent.defaultSettings) ,$scope.iframeScope.component.active.id);
                        }
                        actions.push({slug: actionSlug, comment: '', attributes:Object.assign({}, $scope.oxyPowerPack.events.actionList[actionSlug].defaultAttributes )});
                        $scope.iframeScope.setOptionModel('oxyPowerPackEvent-'+$scope.oxyPowerPack.events.currentEvent.slug, actions ,$scope.iframeScope.component.active.id);
                        $scope.oxyPowerPack.events.currentAction = $scope.iframeScope.component.options[$scope.iframeScope.component.active.id].model['oxyPowerPackEvent-'+$scope.oxyPowerPack.events.currentEvent.slug][actions.length-1];
                        $scope.oxyPowerPack.events.actionModal = false;
                    },
                    deleteAction: function( actionIndex ){
                        var actions = $scope.iframeScope.getOption('oxyPowerPackEvent-' +$scope.oxyPowerPack.events.currentEvent.slug);
                        if( actions == '') {
                            return;
                        }
                        if( actions[actionIndex] === $scope.oxyPowerPack.events.currentAction ){
                            $scope.oxyPowerPack.events.currentAction = null;
                        }
                        actions.splice(actionIndex,1);
                        $scope.iframeScope.setOptionModel('oxyPowerPackEvent-'+$scope.oxyPowerPack.events.currentEvent.slug, actions ,$scope.iframeScope.component.active.id);
                    },
                    applyCurrentAction: function(){
                        $scope.oxyPowerPack.events.sanitizeCurrentAction();
                        $scope.safeApply();
                        var actions = $scope.iframeScope.getOption('oxyPowerPackEvent-' +$scope.oxyPowerPack.events.currentEvent.slug);
                        var eventSettings = $scope.iframeScope.getOption('oxyPowerPackEvent-' +$scope.oxyPowerPack.events.currentEvent.slug + '-settings');
                        $scope.iframeScope.setOptionModel('oxyPowerPackEvent-'+$scope.oxyPowerPack.events.currentEvent.slug, actions ,$scope.iframeScope.component.active.id);
                        $scope.iframeScope.setOptionModel('oxyPowerPackEvent-'+$scope.oxyPowerPack.events.currentEvent.slug + '-settings', eventSettings ,$scope.iframeScope.component.active.id);
                    },
                    sanitizeCurrentAction: function(){
                        var currentAction = $scope.oxyPowerPack.events.currentAction;
                        for (var prop in currentAction) {
                            if (Object.prototype.hasOwnProperty.call(currentAction, prop) && typeof currentAction[prop].replace == 'function') {
                                currentAction[prop] = currentAction[prop].replace(/"/g,'');
                                currentAction[prop] = currentAction[prop].replace(/'/g,'');
                                currentAction[prop] = currentAction[prop].replace(/´/g,'');
                                if( prop == 'selector') currentAction[prop] = currentAction[prop].replace(/ /g,'-');
                            }
                        }

                    }
                }
            };
            var OxyPowerPack = this;
            var $scope = OxyPowerPack.$scope;

            $scope.oxyPowerPack.activeComponentHasParentOfType = function(type){
                var activeComponent = $scope.iframeScope.getComponentById($scope.iframeScope.component.active.id);
                if(typeof activeComponent === 'undefined') return false;
                var closest = jQuery(activeComponent[0]).closest('.' + type);
                return closest.length > 0 ? true : false;
            };

            $scope.oxyPowerPack.previewRequiredMessages = function(){
                jQuery($scope.iframeScope.getComponentById($scope.iframeScope.component.active.id)[0]).find('.power-form-field-wrapper span').stop().toggle();
            };

            $scope.$watch('iframeScope.component.active.id', function() {
                if( $scope.iframeScope.component.active.id != $scope.oxyPowerPack.events.currentComponent ){
                    $scope.oxyPowerPack.events.currentComponent = $scope.iframeScope.component.active.id;
                    $scope.oxyPowerPack.events.currentEvent = null;
                    $scope.oxyPowerPack.events.currentAction = null;
                    $scope.oxyPowerPack.events.search = '';
                }
            });
            $scope.$watch('iframeScope.component.options', function() {
                if( !$scope.iframeScope.component.active || $scope.iframeScope.component.active.id == 0) return;
                if( $scope.iframeScope.component.options[$scope.iframeScope.component.active.id].model.oxyPowerPackTempSelector){
                    if( typeof $scope.oxyPowerPack.tooltips.currentTooltip != 'undefined' && $scope.oxyPowerPack.tooltips.currentTooltip != null ){
                        $scope.oxyPowerPack.tooltips.currentTooltip.content = $scope.iframeScope.component.options[$scope.iframeScope.component.active.id].model.oxyPowerPackTempSelector;
                        $scope.oxyPowerPack.tooltips.applyCurrentTooltip();
                    } else if( typeof $scope.oxyPowerPack.events.currentAction != 'undefined' && $scope.oxyPowerPack.events.currentAction != null ){
                        $scope.oxyPowerPack.events.currentAction.attributes.selector = $scope.iframeScope.component.options[$scope.iframeScope.component.active.id].model.oxyPowerPackTempSelector;
                        $scope.oxyPowerPack.events.applyCurrentAction();
                    }
                    $scope.iframeScope.setOptionModel('oxyPowerPackTempSelector', '');
                    console.log("y");
                }
                if($scope.iframeScope.component.active.name == 'oxy-power-map'){
                    if($scope.iframeScope.component.options[$scope.iframeScope.component.active.id].model['oxy-power-map_base_map'] != 'osm' && OxyPowerPackBEData.mapbox_key.trim() == ''){
                        alert('No MapBox Access Token is configured. Please set yours in the OxyPowerPack settings page and try again. Switching back to Basic OpenStreetMaps...');
                        $scope.iframeScope.setOptionModel('oxy-power-map_base_map', 'osm');
                    }
                    if($scope.iframeScope.component.options[$scope.iframeScope.component.active.id].model['oxy-power-map_markers_source'] != 'manual'){
                        alert('Advanced Custom Fields integration is coming soon');
                        $scope.iframeScope.setOptionModel('oxy-power-map_markers_source', 'manual');
                    }
                }
                if($scope.iframeScope.component.active.name == 'oxy-power-form-field'){
                    var activeComponent = $scope.iframeScope.getComponentById($scope.iframeScope.component.active.id);
                    var shouldRebuildFormField = false;
                    if($scope.iframeScope.component.options[$scope.iframeScope.component.active.id].model['oxy-power-form-field_field_type'] != 'textarea' && jQuery(activeComponent[0]).find('textarea').length > 0 ) {
                        shouldRebuildFormField = true;
                    } else if($scope.iframeScope.component.options[$scope.iframeScope.component.active.id].model['oxy-power-form-field_field_type'] == 'textarea' && jQuery(activeComponent[0]).find('textarea[rows="' + $scope.iframeScope.component.options[$scope.iframeScope.component.active.id].model['oxy-power-form-field_textarea_rows'] + '"]').length == 0 ) {
                        shouldRebuildFormField = true;
                    } else if($scope.iframeScope.component.options[$scope.iframeScope.component.active.id].model['oxy-power-form-field_field_type'] != 'color' && jQuery(activeComponent[0]).find('input[type="color"]').length > 0 ) {
                        shouldRebuildFormField = true;
                    } else if($scope.iframeScope.component.options[$scope.iframeScope.component.active.id].model['oxy-power-form-field_field_type'] != 'text' && jQuery(activeComponent[0]).find('input[type="text"]').length > 0 ) {
                        shouldRebuildFormField = true;
                    } else if($scope.iframeScope.component.options[$scope.iframeScope.component.active.id].model['oxy-power-form-field_field_type'] != 'number' && jQuery(activeComponent[0]).find('input[type="number"]').length > 0 ) {
                        shouldRebuildFormField = true;
                    } else if($scope.iframeScope.component.options[$scope.iframeScope.component.active.id].model['oxy-power-form-field_field_type'] != 'checkbox' && jQuery(activeComponent[0]).find('input[type="checkbox"]').length > 0 ) {
                        shouldRebuildFormField = true;
                    } else if($scope.iframeScope.component.options[$scope.iframeScope.component.active.id].model['oxy-power-form-field_field_type'] != 'radio' && jQuery(activeComponent[0]).find('input[type="radio"]').length > 0 ) {
                        shouldRebuildFormField = true;
                    } else if($scope.iframeScope.component.options[$scope.iframeScope.component.active.id].model['oxy-power-form-field_field_type'] != 'select' && jQuery(activeComponent[0]).find('select').length > 0 ) {
                        shouldRebuildFormField = true;
                    }

                    if(shouldRebuildFormField){
                        if(window.rebuildFormFieldTimeout) clearTimeout(window.rebuildFormFieldTimeout);
                        window.rebuildFormFieldTimeout = setTimeout(function(){
                            $scope.iframeScope.rebuildDOM($scope.iframeScope.component.active.id);
                        },100);
                    }
                }
            },true);
            OxyPowerPack.$scope.adjustArtificialViewport = function(artificialViewportWidth) {

                //console.log(artificialViewportWidth);

                var oxyPowerPackDrawerGap = $scope.oxyPowerPack.drawerDocked ? 350 : 0;

                var heightOffset = 71;

                if ($scope.viewportRullerShown) {
                    heightOffset += 16;
                }

                // adjust artificial viewport based on "Page width"
                if (!$scope.viewportRullerShown) {

                    var viewportContainerWidth = $scope.viewportContainer.width();
                    pageWidth = $scope.iframeScope.getWidth($scope.iframeScope.getPageWidth());

                    if (artificialViewportWidth===undefined) {
                        artificialViewportWidth = $scope.artificialViewport.width();
                    }

                    if ( pageWidth.value > artificialViewportWidth ) {

                        var neededSpace = parseInt($scope.iframeScope.getPageWidth()) + 20;

                        $scope.artificialViewport.css({
                            "width": neededSpace,
                            "min-width": ""
                        });

                        // rescale iframe if not fit
                        if ( !$scope.viewportScaleLocked ) {
                            if ( neededSpace > artificialViewportWidth ) {
                                var scale = artificialViewportWidth / neededSpace;
                                $scope.artificialViewport.css({
                                    transform: "scale("+scale+")",
                                    height: "calc("+(100/scale)+"vh - "+(heightOffset/scale+oxyPowerPackDrawerGap/scale)+"px)"
                                });
                                $scope.viewportScale = scale;
                            }
                            $scope.viewportContainer.css("overflow-x","");
                        }
                        else {
                            $scope.artificialViewport.css({
                                "transform": "scale(1)",
                                "height": "calc(100vh - "+(heightOffset+oxyPowerPackDrawerGap)+"px)",
                            });
                            $scope.viewportContainer.css("overflow-x","auto");
                            $scope.viewportScale = 1;
                        }
                    }
                    else
                    if ( pageWidth.value < viewportContainerWidth - 12 ) {
                        $scope.artificialViewport.css({
                            "transform": "scale(1)",
                            "height": "calc(100vh - "+(heightOffset+oxyPowerPackDrawerGap)+"px)",
                        });
                        $scope.viewportContainer.css("overflow-x","auto");
                        if ( !$scope.viewportRullerShown ) {
                            $scope.artificialViewport.css({
                                "width": "",
                                "min-width": ""
                            });
                        }
                        $scope.viewportScale = 1;
                        //console.log("adjustArtificialViewport()", "")
                    }

                    // unset builder width
                    $scope.builderElement.css("width", "");

                }
                else {
                    // unset builder width
                    $scope.builderElement.css("width", "");
                    //console.log("adjustArtificialViewport()", "")

                    var viewportContainertWidth = $scope.viewportContainer.width(),
                        artificialViewportWidth = $scope.artificialViewport.width() + 20,
                        scale = viewportContainertWidth / artificialViewportWidth;

                    // rescale iframe if not fit
                    if ( artificialViewportWidth > viewportContainertWidth ) {

                        if ( !$scope.viewportScaleLocked ) {
                            $scope.artificialViewport.css({
                                transform: "scale("+scale+")",
                                height: "calc("+(100/scale)+"vh - "+(heightOffset/scale+oxyPowerPackDrawerGap/scale)+"px)",
                            });
                            $scope.viewportContainer.css("overflow-x","");
                            $scope.viewportScale = scale;
                        }
                        else {
                            $scope.artificialViewport.css({
                                transform: "scale(1)",
                                height: "calc(100vh - "+(heightOffset+oxyPowerPackDrawerGap)+"px)",
                                marginBottom: 23
                            });
                            $scope.viewportContainer.css("overflow-x","auto");
                            $scope.viewportScale = 1;
                        }
                    }
                    else {
                        $scope.artificialViewport.css({
                            transform: "scale(1)",
                            height: "calc(100vh - "+(heightOffset+oxyPowerPackDrawerGap)+"px)",
                            marginBottom: 23
                        });
                        $scope.viewportContainer.css("overflow-x","auto");
                        $scope.viewportScale = 1;
                    }
                }

                $scope.iframeScope.adjustResizeBox();

                // safely apply scope
                setTimeout(function() {
                    $scope.$apply();
                }, 0);

            };
            // No more compiling the UI after the application bootstrap
            //OxyPowerPack.$scope.compileInsertUI(document.getElementById('oxypowerpack-ui').content, "body");
            jQuery('#oxypowerpack_countdown_helper').datetimepicker({
                format:'M d Y H:i:s 0',
                inline:true,
                lang:'en',
                theme: 'dark',
                onChangeDateTime:function(newDateTime){
                    //$scope.iframeScope.setOption($scope.iframeScope.component.active.id,newDateTime.toString(),'oxy-countdown-timer_target_time');
                    $scope.iframeScope.setOptionModel('oxy-countdown-timer_target_time',newDateTime.toString(),$scope.iframeScope.component.active.id);
                    //$scope.iframeScope.component.options[$scope.iframeScope.component.active.id].model['oxy-countdown-timer_target_time'] = newDateTime.toString();
                    $scope.$apply();
                }
            });
            jQuery('.xdsoft_time_variant').css('margin-top', '0');
            var $floatingButtons = $('.oxypowerpack-floating-buttons');
            $floatingButtons.find('.gotoright').click(function(){
                $floatingButtons.removeClass('left');
                $floatingButtons.css({left: '8px'});
                $floatingButtons.animate({left: '95%'}, 300, 'swing', function(){
                    $floatingButtons.css({left: ''});
                    $floatingButtons.addClass('right');
                });
                $scope.oxyPowerPack.persistentState.floatingButtonsPosition = 'right';
                localStorage.setItem('oxyPowerPack', JSON.stringify($scope.oxyPowerPack.persistentState) );
            });
            $floatingButtons.find('.gotoleft').click(function(){
                $floatingButtons.removeClass('right');
                $floatingButtons.css({right: '8px'});
                $floatingButtons.animate({right: '95%'}, 300, 'swing', function(){
                    $floatingButtons.css({right: ''});
                    $floatingButtons.addClass('left');
                });
                $scope.oxyPowerPack.persistentState.floatingButtonsPosition = 'left';
                localStorage.setItem('oxyPowerPack', JSON.stringify($scope.oxyPowerPack.persistentState) );
            });
            $floatingButtons.find('.toggle').first().click(function(){
                OxyPowerPack.$scope.oxyPowerPack.drawerOpen = true;
                OxyPowerPack.$scope.$apply();
            });
            $('.oxypowerpack').mouseleave(function(e){
                if( $('.oxypowerpack-action-modal-backdrop').is(':visible') ) return;
                window.oxyPowerPack_closingDrawerHandler = setTimeout(function(){
                    OxyPowerPack.$scope.oxyPowerPack.drawerOpen = false;
                    OxyPowerPack.$scope.$apply();
                },500);
            });
            $('.oxypowerpack').mouseenter(function(e){
                if( $('.oxypowerpack-action-modal-backdrop').is(':visible') ) return;
                clearTimeout(window.oxyPowerPack_closingDrawerHandler);
                OxyPowerPack.$scope.oxyPowerPack.drawerOpen = true;
                OxyPowerPack.$scope.$apply();
            });

            $('#oxypowerpack-docked-toggle').click(function(){
                updateFixedDrawerClasses();
                $scope.oxyPowerPack.persistentState.docked = $scope.oxyPowerPack.drawerDocked;
                localStorage.setItem('oxyPowerPack', JSON.stringify($scope.oxyPowerPack.persistentState) );
            });

            $('.oxypowerpack').on('click', '.oxygen-selector-browse', function(e) {
                jQuery('body').addClass('choosing-selector');
                $scope.iframeScope.enterChoosingSelectorMode( jQuery(e.target).data('option') );
                $scope.oxyPowerPack.events.previousEventData = {};
                $scope.oxyPowerPack.events.previousEventData.componentName = $scope.iframeScope.component.active.name;
                $scope.oxyPowerPack.events.previousEventData.currentEvent = $scope.oxyPowerPack.events.currentEvent;
                $scope.oxyPowerPack.events.previousEventData.currentAction = $scope.oxyPowerPack.events.currentAction;
                $scope.iframeScope.component.active.name = 'ct_modal';

                jQuery(e.target).on('change',function selectorChanged() {
                    $scope.iframeScope.component.active.name = $scope.oxyPowerPack.events.previousEventData.componentName;
                    $scope.oxyPowerPack.events.currentEvent = $scope.oxyPowerPack.events.previousEventData.currentEvent;
                    $scope.oxyPowerPack.events.currentAction = $scope.oxyPowerPack.events.previousEventData.currentAction;
                    jQuery(e.target).off('change', selectorChanged);
                });
            });

            $scope.$watch('oxyPowerPack.events.currentAction.attributes.selector', function() {
                $scope.iframeScope.exitChoosingSelectorMode();
            });

            var persistentState = JSON.parse(localStorage.getItem('oxyPowerPack'));
            if( persistentState && typeof persistentState == 'object' ) {
                if( persistentState.docked ){
                    $scope.oxyPowerPack.drawerDocked = true;
                    $('.oxypowerpack').removeClass('closed');
                }
                if( persistentState.floatingButtonsPosition ){
                    $floatingButtons.addClass( persistentState.floatingButtonsPosition );
                } else {
                    $floatingButtons.addClass( 'left' );
                }
            } else {
                $floatingButtons.addClass( 'left' );
            }
            $('.oxypowerpack').trigger('mouseleave');

            var updateFixedDrawerClasses = function(){
                setTimeout(function(){
                    if( OxyPowerPack.$scope.oxyPowerPack.drawerDocked ){
                        $('body').addClass('oxypowerpack-docked');
                    }else{
                        $('body').removeClass('oxypowerpack-docked');
                    }
                    OxyPowerPack.$scope.adjustArtificialViewport();
                },150);
            };
            updateFixedDrawerClasses();

            var postsPager = function(type){
                this.enabled = false;
                if( typeof wp.api.collections === 'undefined') return;
                if( typeof wp.api.collections[type] === 'undefined') return;
                this.enabled = true;
                this.collection = [];
                this.fetching = true;
                this.collection = new wp.api.collections[type]();
                this.type = type;
                if( this.type.charAt(this.type.length-1) == 's'){
                    this.type = this.type.substring(0, this.type.length-1);
                }
                this.fancyName = this.type;
                if(this.fancyName == 'Oxy_user_library') this.fancyName = 'Block';
                var thisPager = this;
                this.collection.fetch({
                    success:function(){
                        thisPager.fetching = false;
                        $scope.$apply();
                    },
                    error:function(){

                    }
                });
                this.changePage = function( page ){
                    if(!thisPager.enabled) return;
                    if(thisPager.fetching === true) return;
                    if((page < 0 && thisPager.collection.state.currentPage > 1) || (page > 0 && thisPager.collection.state.currentPage < thisPager.collection.state.totalPages)){
                        thisPager.fetching = true;
                        thisPager.collection.fetch( { data: { page: thisPager.collection.state.currentPage+page }, success: function(){
                            thisPager.fetching = false;
                            $scope.$apply();
                        } } );
                    }
                };
                this.newElement = function(){
                    if(!thisPager.enabled) return;
                    var title = prompt("Title for new " + thisPager.fancyName, 'New ' + thisPager.fancyName);
                    if(title == null) return;
                    var page = new wp.api.models[thisPager.type]();
                    thisPager.fetching = true;
                    page.save({ title: title, status:'publish' },{
                        success:function(){
                            var collection = thisPager.collection;
                            collection.fetch( { data: { page: collection.state.currentPage }, success: function(){
                                    thisPager.fetching = false;
                                    $scope.$apply();
                                } } );
                        },
                        error:function(){
                            thisPager.fetching = false;
                            alert('Could not create the new ' + thisPager.fancyName);
                        }
                    });
                };
            }

            // Pages and templates initial load
            wp.api.loadPromise.done( function() {
                $scope.oxyPowerPack.pages = new postsPager('Pages');
                $scope.oxyPowerPack.posts = new postsPager('Posts');
                $scope.oxyPowerPack.templates = new postsPager('Ct_template');
                if($scope.oxyPowerPack.library_enabled) $scope.oxyPowerPack.blocks = new postsPager('Oxy_user_library');
            } );

            $scope.oxyPowerPack.changePostTitle = function(){
                var kindOfPost = 'page';
                if($scope.oxyPowerPack.currentPostObject.attributes.type == 'ct_template') kindOfPost = 'template';
                if($scope.oxyPowerPack.currentPostObject.attributes.meta.ct_template_type && $scope.oxyPowerPack.currentPostObject.attributes.meta.ct_template_type == 'reusable_part') kindOfPost = 'reusable part';
                var newTitle = prompt("New title for " + kindOfPost, $scope.oxyPowerPack.currentPostObject.attributes.title.rendered);
                if (newTitle != null && newTitle.trim() != '') {
                    newTitle = newTitle.trim();
                    if($scope.oxyPowerPack.currentPostObject.attributes.type == 'ct_template'){
                        $scope.oxyPowerPack.templates.fetching = true;
                    }else {
                        $scope.oxyPowerPack.pages.fetching = true;
                    }
                    $scope.oxyPowerPack.currentPostObject.save({title: newTitle}, {
                        patch: true,
                        success:function(){
                            $scope.oxyPowerPack.templates.fetching = false;
                            $scope.oxyPowerPack.pages.fetching = false;
                            $scope.$apply();
                        },
                        error:function(){
                            $scope.oxyPowerPack.templates.fetching = false;
                            $scope.oxyPowerPack.pages.fetching = false;
                            alert('Could not set the new name');
                        }
                    });
                }
            };

            $scope.oxyPowerPack.openIn = function(where){
                var url = new URL($scope.oxyPowerPack.currentPostObject.attributes.link);
                if(typeof where !== 'undefined') url.searchParams.append('oxypowerpack_redirect_to', where);
                location.href=url.href;
            };
            var removeHtml = function(text){
                var pos = text.indexOf('<');
                if(pos > 0) {
                    text = text.substring(0, pos);
                }
                return text;
            };
            $.getJSON( OxyPowerPackBEData.admin_url + '?oxypowerpack_menu_json=true', function( data ) {
                $scope.oxyPowerPack.adminMenu = [];
                $.each(data.menu, function(key, value){
                    if(value[0] != '' && value[0] != null){
                        var menuItem = {};
                        menuItem.title = removeHtml(value[0]);
                        menuItem.entries = [];
                        menuItem.entries.push({title: removeHtml(value[0]), url: OxyPowerPackBEData.admin_url + (value[2].indexOf('.php') == -1 ? '?page='+value[2] : value[2]) });
                        if( typeof data.submenu[value[2]] != 'undefined'){
                            $.each(data.submenu[value[2]], function(key, value){
                                if(value[0] != ''  && value[0] != null) {
                                    var submenuItem = {};
                                    submenuItem.title = removeHtml(value[0]);
                                    submenuItem.url = OxyPowerPackBEData.admin_url + (value[2].indexOf('.php') == -1 ? 'admin.php?page='+value[2] : value[2]);
                                    menuItem.entries.push(submenuItem);
                                }
                            });
                        }
                        $scope.oxyPowerPack.adminMenu.push(menuItem);
                    }
                });
                $scope.$apply();
            });
            $.get( OxyPowerPackBEData.admin_url + '?page=oxypowerpack&oxypowerpack_render_maintenance_form=true', function( data ) {
                OxyPowerPack.$scope.compileInsertUI(data, "#oxypowerpack-maintenance-form");
                $('#oxypowerpack-maintenance-form form').submit( $scope.oxyPowerPack.maintenanceModeFormSubmit );
                $scope.oxyPowerPack.maintenanceModeFormBusy = false;
                $scope.$apply();
            });

            $scope.oxyPowerPack.maintenanceModeFormSubmit = function(){
                var enabled = $('#oxy_maintenance_enabled');
                var defaultPage = $('#oxy_maintenance_page_default');
                if( enabled.is(':checked') && parseInt(defaultPage.val()) == -1 ){
                    alert('A default page (for logged-out users) must be set in order to enable maintenance mode');
                    return false;
                }
                $scope.oxyPowerPack.maintenanceModeFormBusy = true;
                $('#oxypowerpack-maintenance-form form').ajaxSubmit({
                    success: function(){
                        $scope.oxyPowerPack.maintenanceModeFormBusy = false;
                        $scope.$apply();
                    },
                    timeout: 5000
                });
                return false;
            };

            $scope.oxyPowerPack.maintenanceModeBypassCopy = function(){
                var copyText = document.getElementById("oxy_maintenance_bypass_dummy");
                copyText.select();
                copyText.setSelectionRange(0, 99999); /*For mobile devices*/
                document.execCommand("copy");
                jQuery('#oxy_maintenance_bypass_dummy').hide().fadeIn('slow');
                return false;
            };

            $scope.oxyPowerPack.maintenanceModeBypassChanged = function(){
                setTimeout(function(){
                    var currDummy = jQuery("#oxy_maintenance_bypass_dummy").val();
                    currDummy = currDummy.split('=')[0] + '=' + jQuery("#oxy_maintenance_bypass").val();
                    jQuery("#oxy_maintenance_bypass_dummy").val( currDummy );
                },50);
            };

            $scope.oxyPowerPack.BEData = OxyPowerPackBEData;

            if( OxyPowerPackBEData.events_enabled != 'true' ) $scope.oxyPowerPack.currentTab = 'wordpress';

            $('.oxypowerpack,.oxypowerpack-floating-buttons').show();

            var grabStyles = function() {
                    
                $.getJSON( OxyPowerPackBEData.admin_url + '?oxypowerpack-get-styles', function( data ) {
                    if( typeof data.style !== 'undefined' ) {
                        for (var index = 0; index < $scope.iframeScope.styleSheets.length; index++) {
                            var element = $scope.iframeScope.styleSheets[index];
                            if( element.name == 'OxyPowerPack-Styles' ){
                                element.css = atob(data.style);
                                $scope.iframeScope.applyStyleSheet(element);
                            }
                        }
                    }
                }).always(function(){
                    setTimeout( grabStyles, 6000 );
                });
            };

            if( OxyPowerPackBEData.sass_enabled == 'true' ) setTimeout( grabStyles, 4000 );

            var oPPHeartBeat = function() {
                $.getJSON( OxyPowerPackBEData.admin_url + '?oxypowerpack-heartbeat=' + CtBuilderAjax.postId, function( data ) {
                    if( typeof data.session_status !== 'undefined' && data.session_status != 'expired' ) {
                        $scope.iframeScope.ajaxVar.nonce = data.session_status;
                        document.getElementById('ct-artificial-viewport').contentWindow.CtBuilderAjax.nonce = data.session_status;
                        CtBuilderAjax.nonce = data.session_status;
                        $('#opp-login').fadeOut(300,function(){ $('#opp-login').remove(); });
                        setTimeout( oPPHeartBeat, 6000 );
                    } else if(typeof data.session_status !== 'undefined' && data.session_status == 'expired'){
                        if( !$('#opp-login').length ){
                            var html = $('#opp-login-template').html();
                            html = html.replace('IFRAMESRC', OxyPowerPackBEData.loginIframeSrc);
                            $('body').append(html);
                        }
                        setTimeout( oPPHeartBeat, 6000 );
                    } else {
                        setTimeout( oPPHeartBeat, 6000 );
                    }
                }).fail(function(){
                    setTimeout( oPPHeartBeat, 6000 );
                });
            };

            if( OxyPowerPackBEData.relogin_enabled == 'true' ) setTimeout( oPPHeartBeat, 4000 );



            console.log( "OxyPowerPack started!" );
        },
        getScope: function(){
            var bodyElement = angular.element("body");
            this.$scope = bodyElement.scope();
            return this.$scope;
        }
    };

} ) ( jQuery, angular );

CTFrontendBuilderUI.filter('oppBase64', function() {
    return function(text){
        if(text.includes('oxy_base64_encoded::')){
            return atob( text.replace('oxy_base64_encoded::', '') );
        }
        return text;
    };
});

