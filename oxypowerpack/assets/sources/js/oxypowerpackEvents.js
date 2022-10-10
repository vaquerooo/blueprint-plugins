( function( $, doc, eventName ) {

    window.oxyPowerPackEvents = {

        trigger: function(type, args){

            var event = new CustomEvent(eventName, {
                detail: {
                    type: type,
                    args: args
                }
            });

            doc.dispatchEvent(event);
        },

        processEvent: function(type, args){

            switch( type ) {
                case 'mouseover':
                case 'mouseout':
                case 'click':
                case 'cf7-submit':
                case 'enterviewport':
                case 'exitviewport':
                case 'startoverlapping':
                case 'finishoverlapping':
                    oxyPowerPackEvents.processActions( args.target, args.target.data(eventName+'-'+type) );
                    break;
                case 'page-load':
                    $('[data-' + eventName + '-'+type+']').each(function(){
                        oxyPowerPackEvents.processActions( $(this), $(this).data(eventName+'-'+type) );
                    });
                    break;
                case 'scroll':
                    if(Number.isInteger( args )){
                        $('[data-' + eventName + '-'+type+']').each(function(){
                            if( args >= parseInt($(this).data(eventName+'-'+type+'-percentage'))){
                                oxyPowerPackEvents.processActions( $(this), $(this).data(eventName+'-'+type) );
                                $(this).data(eventName+'-'+type, '');
                            }
                        });
                    }
                    break;
                case 'countdown-finished':
                    var timer = args.timer.trim();
                    $('[data-' + eventName + '-'+type+']').each(function(){
                        var selector = $(this).data(eventName+'-'+type+'-selector');
                        if( typeof selector === 'undefined') selector = '';
                        selector = selector.trim().replace('#','');
                        if( selector == '' || timer == selector ){
                            oxyPowerPackEvents.processActions( $(this), $(this).data(eventName+'-'+type) );
                        }
                    });
                    break;
                case 'powerform-submitted':
                    var form = args.form;
                    $('[data-' + eventName + '-'+type+']').each(function(){
                        var selector = $(this).data(eventName+'-'+type+'-selector');
                        if( typeof selector === 'undefined') selector = '';
                        selector = selector.trim().replace('#','');
                        if( selector == '' || form.attr('id').trim() == selector || form.hasClass( selector.replace('.', '') ) ){
                            oxyPowerPackEvents.processActions( $(this), $(this).data(eventName+'-'+type) );
                        }
                    });
                    break;
            }

        },

        processActions: function( $element, actions ) {
            if( !Array.isArray(actions) ) actions = actions.split('|');

            if(actions.length > 0) {
                var currentAction = actions.splice(0,1)[0];
                var actionTokens = currentAction.split('&');
                var action = actionTokens.splice(0,1)[0];

                var actionAttributes = {};
                for(var i = 0; i< actionTokens.length; i++){
                    var attr = actionTokens[i].split('=');
                    actionAttributes[attr[0]] = attr[1];
                }

                if( action == 'delay' ){
                    var millis = parseFloat(actionAttributes.seconds) * 1000;
                    setTimeout( function(){
                        oxyPowerPackEvents.processActions( $element, actions );
                    }, millis );
                }else{
                    oxyPowerPackEvents.doAction( $element, action, actionAttributes);
                    oxyPowerPackEvents.processActions( $element, actions );
                }
            }
        },

        doAction: function( $element, action, attributes ) {
            if( typeof attributes.selector != 'undefined' && attributes.selector.trim() != ''){
                $element = $(attributes.selector);
            }
            switch(action){
                case 'hide':
                    if( attributes.fading != '1' ) {
                        $element.css('visibility', 'hidden');
                    }else{
                        $element.stop().fadeTo(250, 0, function(){
                            $element.css('visibility', 'hidden');
                            $element.css('opacity', '1');
                        });
                    }
                    break;
                case 'show':
                    if( attributes.fading != '1' ) {
                        $element.css('visibility', 'visible');
                    }else{
                        $element.css('opacity', '0');
                        $element.css('visibility', 'visible');
                        $element.stop().fadeTo(250, 1);
                    }
                    break;
                case 'addclass':
                    $element.addClass(attributes.class);
                    break;
                case 'removeclass':
                    $element.removeClass(attributes.class);
                    break;
                case 'toggleclass':
                    $element.toggleClass(attributes.class);
                    break;
                case 'remove':
                    $element.remove();
                    break;
                case 'settext':
                    $element.html(oxyPowerPackEvents.replace_string_tokens(attributes.text));
                    break;
                case 'appendtext':
                    $element.html($element.html() + oxyPowerPackEvents.replace_string_tokens( attributes.text ) );
                    break;
                case 'log':
                    console.log( 'OxyPowerPack Log: ' + oxyPowerPackEvents.replace_string_tokens( attributes.text ) );
                    break;
                case 'alert':
                    alert(oxyPowerPackEvents.replace_string_tokens( attributes.text ));
                    break;
                case 'redirect':
                    location.href = attributes.location;
                    break;
                case 'closemodal':
                    if(typeof window.oxyCloseModal == 'function') window.oxyCloseModal();
                    break;
                case 'openmodal':
                    if( !$element.hasClass('ct-modal') ) {
                        $element = $('.ct-modal').first();
                        if($element.length == 0) break;
                    }
                    var $modal = $element.closest('.oxy-modal-backdrop');
                    $modal.addClass("live");
                    // trick to make jQuery fadeIn with flex
                    $modal.css("display", "flex");
                    $modal.hide();
                    // trick to force AOS trigger on elements inside the modal
                    $modal.find(".aos-animate").removeClass("aos-animate").addClass("aos-animate-disabled");
                    // show the modal
                    $modal.fadeIn(250, function(){
                        // trick to force AOS trigger on elements inside the modal
                        $modal.find(".aos-animate-disabled").removeClass("aos-animate-disabled").addClass("aos-animate");
                    });
                    break;
                case 'scrolltotop':
                case 'scrolltoelement':
                    var scrolltop = 0;
                    if(typeof attributes.selector != 'undefined') scrolltop = $element.offset().top;
                    $([document.documentElement, document.body]).animate({
                        scrollTop: scrolltop
                    }, parseInt( parseFloat(attributes.seconds) * 1000 ));
                    break;
            }
        },

        replace_string_tokens: function( text ){
            return text.replace(/"|\||:|__QUOTATION_MARK__|__VERTICAL_LINE__|__COLON__/gi, function (x) {
                switch(x){
                    case '"':
                        return '__QUOTATION_MARK__';
                        break;
                    case '|':
                        return '__VERTICAL_LINE__';
                        break;
                    case "&":
                        return '__AMPERSAND__';
                        break;
                    case '__QUOTATION_MARK__':
                        return '"';
                        break;
                    case '__VERTICAL_LINE__':
                        return '|';
                        break;
                    case '__AMPERSAND__':
                        return '&';
                        break;
                }
            });
        }

    };

    $(doc).ready(function(){

        doc.addEventListener(eventName, function(event) {

            oxyPowerPackEvents.processEvent(event.detail.type, event.detail.args);

        });

    });

    // Event handlers for each especific event

    $(window).on('load', function(){

        oxyPowerPackEvents.trigger('page-load');

        $('[data-' +eventName+ '-click]').click(function(ev){
            oxyPowerPackEvents.trigger('click', {target: $(this)});
            ev.preventDefault();
        });
        $('[data-' +eventName+ '-mouseover]').on('mouseenter', function(){
            oxyPowerPackEvents.trigger('mouseover', {target: $(this)});
        });
        $('[data-' +eventName+ '-mouseout]').on('mouseleave',function(){
            oxyPowerPackEvents.trigger('mouseout', {target: $(this)});
        });

        document.addEventListener('wpcf7submit', function (event) {
            oxyPowerPackEvents.trigger('cf7-submit', {cf7Id: event.details.contactFormId});
        }, false);

        var intersectionObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting && entry.target.hasAttribute('data-' +eventName+ '-enterviewport' )) {
                    oxyPowerPackEvents.trigger('enterviewport', {target: $(entry.target)});
                } else if(!entry.isIntersecting && entry.target.hasAttribute('data-' +eventName+ '-exitviewport' )) {
                    oxyPowerPackEvents.trigger('exitviewport', {target: $(entry.target)});
                }
            });
        }, {
            root: null,
            rootMargin: "0px",
            threshold: 0
        });

        $('[data-' +eventName+ '-enterviewport],[data-' +eventName+ '-exitviewport]').each(function(){
            intersectionObserver.observe(this);
        });

    });

    var maxScrolled = 0;
    var amountOfOverlappingEvents = $('[data-oxypowerpack-startoverlapping],[data-oxypowerpack-finishoverlapping]').length;
    $(window).on('scroll', function scrollDetection(){
        var winheight= window.innerHeight || (document.documentElement || document.body).clientHeight;
        var docheight = jQuery(document).height();
        var scrollTop = window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop;
        var trackLength = docheight - winheight;
        var pctScrolled = Math.floor(scrollTop/trackLength * 100);
        if( isNaN( pctScrolled ) ) pctScrolled = 0;

        if( pctScrolled > maxScrolled ){
            oxyPowerPackEvents.trigger('scroll', pctScrolled);
        }

        if( pctScrolled >= 100 && amountOfOverlappingEvents == 0){
            $(window).off( "scroll", scrollDetection );
        }

        $('[data-oxypowerpack-startoverlapping],[data-oxypowerpack-finishoverlapping]').each(function(){
            var $elem1 = $(this);
            if(typeof $elem1.data('isoverlapping') === 'undefined' ) $elem1.data('isoverlapping', false);

            var type = $elem1[0].hasAttribute('data-oxypowerpack-startoverlapping') ? 'start' : 'finish';
            var isCollidingNow = false;
            $( $elem1.attr( 'data-oxypowerpack-' + type + 'overlapping-selector' ) ).each(function(){
                if( oppIsColliding($elem1,$(this)) ){
                    isCollidingNow = true;
                    return false;
                }
            });

            if( isCollidingNow && !$elem1.data('isoverlapping') ){
                oxyPowerPackEvents.trigger('startoverlapping', {target: $elem1});
                $elem1.data('isoverlapping', true);
            } else if( !isCollidingNow && $elem1.data('isoverlapping') ) {
                oxyPowerPackEvents.trigger('finishoverlapping', {target: $elem1});
                $elem1.data('isoverlapping', false);
            }
        });

    });

    var oppIsColliding = function( $div1, $div2 ) {
        // Div 1 data
        var d1_offset             = $div1.offset();
        var d1_height             = $div1.outerHeight( true );
        var d1_width              = $div1.outerWidth( true );
        var d1_distance_from_top  = d1_offset.top + d1_height;
        var d1_distance_from_left = d1_offset.left + d1_width;

        // Div 2 data
        var d2_offset             = $div2.offset();
        var d2_height             = $div2.outerHeight( true );
        var d2_width              = $div2.outerWidth( true );
        var d2_distance_from_top  = d2_offset.top + d2_height;
        var d2_distance_from_left = d2_offset.left + d2_width;

        var not_colliding = ( d1_distance_from_top < d2_offset.top || d1_offset.top > d2_distance_from_top || d1_distance_from_left < d2_offset.left || d1_offset.left > d2_distance_from_left );

        // Return whether it IS colliding
        return ! not_colliding;
    };


} )( jQuery, document,'oxypowerpack' );

