jQuery(document).ready(function(){
    jQuery('[data-tippy-oppcontent]').each(function(){
        var $elem = jQuery(this);
        var content = null;
        if( $elem.data('tippy-opptype') == 'element' ) {
            content = jQuery( $elem.data('tippy-oppcontent') )[0];
            if( $elem.data('tippy-oppcontentcopy') ) {
                content = content.outerHTML;
            }
        } else {
            content = $elem.data('tippy-oppcontent');
        }

        tippy( $elem[0],{
            content: content,
            interactive: true,
            flipOnUpdate: true
        } );
    });
});
