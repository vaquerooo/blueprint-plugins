function oxypp_setup_form_field(element_id, field_name, field_type, tab_index, textarea_rows, textarea_cols, field_value, form_options, additional_class, required) {

    if(required == '' || required =='false') required = '';
    else required = ' opprequired';
    var isBuilder = jQuery('body').hasClass('oxygen-builder-body');
    var field = '';
    var value = field_value;
    if(isBuilder){
        tab_index = '-1';
        if(value == ''){
            if(field_type == 'text') value = 'Sample Text';
            if(field_type == 'textarea') value = "Sample\nmultiline\ntext";
        }
    }

    if(field_type == 'submit' && value == '') value = "Submit";

    if(additional_class.trim() != ''){
        additional_class = ' class="' + additional_class.trim() + '"';
    } else additional_class = '';

    if( form_options.trim() != ''){
        form_options = atob(form_options);
        form_options = JSON.parse(form_options);
    } else {
        form_options = [];
    }

    for(var i = 0; i < form_options.length; i++){
        form_options[i].label = atob(form_options[i].label.replace('oxy_base64_encoded::', ''));
        form_options[i].value = atob(form_options[i].value.replace('oxy_base64_encoded::', ''));
    }

    switch(field_type){
        case 'simple_captcha':
            field = '<input name="simple_captcha_number_1" type="hidden" value="' + (Math.floor(Math.random() * 49) + 1  ) + '">';
            field += '<input name="simple_captcha_number_2" type="hidden" value="' + (Math.floor(Math.random() * 49) + 1  ) + '">';
            field += '<input id="'+element_id+'_field" name="simple_captcha" type="text" tabindex="'+tab_index+'" value=""'+additional_class+''+required+'>';
            break;
        case 'select':
            field = '<select name="'+field_name+'" id="'+element_id+'_field"'+additional_class+''+required+'>';
            for(var i = 0; i < form_options.length; i++){
                field += '  <option value="'+form_options[i].value+'">'+form_options[i].label+'</option>';
            }
            field += '</select>';
            break;
        case 'radio':
            field = '<div class="power-form-radio-group"'+required+'>';
            for(var i = 0; i < form_options.length; i++){
                field += '  <div class="power-form-radio-container">';
                field += '      <input id="'+element_id+'_field_'+i+'" name="'+field_name+'" type="'+field_type+'" tabindex="'+tab_index+'" value="'+form_options[i].value+'"'+additional_class+'>';
                field += '      <label for="'+element_id+'_field_'+i+'">' + form_options[i].label + '</label>';
                field += '  </div>';
            }
            field += '</div>';
            break;
        case 'textarea':
            field = '<textarea id="'+element_id+'_field" name="'+field_name+'" tabindex="'+tab_index+'" rows="'+textarea_rows+'" cols="'+textarea_cols+'"'+additional_class+''+required+'>'+value+'</textarea>';
            break;
        default:
            field = '<input id="'+element_id+'_field" name="'+field_name+'" type="'+field_type+'" tabindex="'+tab_index+'" value="'+value+'"'+additional_class+''+required+'>';
    }
    jQuery('#' +element_id + ' label').first().attr(field_type,'');
    if(field_type == 'submit') jQuery('#' +element_id + ' label[submit]').remove();
    if(jQuery('#' +element_id + ' .power-form-field-wrapper').children().length == 1) jQuery('#' +element_id + ' .power-form-field-wrapper').prepend(field);
    if(field_type == 'simple_captcha') jQuery('#' +element_id + ' label').html(jQuery('#' +element_id + ' input[name="simple_captcha_number_1"]').val() + ' + ' + jQuery('#' +element_id + ' input[name="simple_captcha_number_2"]').val() + ' =');
}

jQuery(function(){
    jQuery("form.oxy-power-form").on('submit',function(event){
        event.preventDefault();
        event.stopPropagation();
        var form = jQuery(this);
        form.find('.power-form-field-wrapper span').slideUp();
        var inputbutton = form.find('input[type="submit"]').first();
        inputbutton.attr('oppval',inputbutton.val()).val('...')[0].disabled=true;


        var validated = true;
        form.find('[opprequired]').not('input[type="submit"]').each(function(){
            if(jQuery(this).is('div')){
                if(jQuery(this).find(':checked').length == 0){
                    jQuery(this).closest('.power-form-field-wrapper').find('span').slideDown();
                    validated = false;
                }
            }else{
                if(jQuery(this).is('[type="checkbox"]') && !jQuery(this).is(':checked') ){
                    jQuery(this).closest('.power-form-field-wrapper').find('span').slideDown();
                    validated = false;
                }else if(!jQuery(this).is('[type="checkbox"]') && jQuery(this).val().trim() == ''){
                    jQuery(this).closest('.power-form-field-wrapper').find('span').slideDown();
                    validated = false;
                }
            }
        })
        if(!validated) {
            inputbutton.val(inputbutton.attr('oppval'))[0].disabled=false;
            return false
        };

        jQuery.ajax({
            url:location.pathname + ( location.pathname.match( /[\?]/g ) ? '&' : '?' ) + 'oppPowerFormSubmission',
            type:'POST',
            dataType: 'json',
            data:form.serialize()
        }).done(function(result){
            if(typeof result.error != 'undefined' && result.error != false){
                if(result.message == 'Invalid Captcha'){
                    form.find('input[name="simple_captcha"]').closest('.power-form-field-wrapper').find('span').slideDown();
                }
                alert(result.message);
            }else{
                if(result.message != '')alert(result.message);
                if(typeof oxyPowerPackEvents != 'undefined') oxyPowerPackEvents.trigger('powerform-submitted', {form: form});
                form[0].reset();
                if(form.find('input[name="simple_captcha"]').length > 0){
                    var element_id =form.find('input[name="simple_captcha"]').first().closest('.oxy-power-form-field').first().attr('id');
                    jQuery('#' +element_id + ' input[name="simple_captcha_number_1"]').val(Math.floor(Math.random() * 49) + 1  );
                    jQuery('#' +element_id + ' input[name="simple_captcha_number_2"]').val(Math.floor(Math.random() * 49) + 1  );
                    jQuery('#' +element_id + ' label').html(jQuery('#' +element_id + ' input[name="simple_captcha_number_1"]').val() + ' + ' + jQuery('#' +element_id + ' input[name="simple_captcha_number_2"]').val() + ' =');
                }
            }
        }).fail(function(result){
            alert('Something went wrong sending your form. Please try again.');
        }).always(function(){
            inputbutton.val(inputbutton.attr('oppval'))[0].disabled=false;
        });
        return false;
    });
});