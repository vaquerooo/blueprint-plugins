if(typeof window.installedCountdowns == 'undefined') window.installedCountdowns = [];
function oxy_setup_countdown(countdownId, target_time, message, openAnimFunc, closeAnimFunc, showdayssegment, type, eg_days, eg_hours, eg_minutes){

    if(target_time.includes('oxy_base64_encoded::')){
        target_time = atob( target_time.replace('oxy_base64_encoded::', '') );
    }

    const second = 1000,
        minute = second * 60,
        hour = minute * 60,
        day = hour * 24;

    if(typeof window.installedCountdowns[countdownId] != 'undefined') clearInterval(window.installedCountdowns[countdownId]);

    if( type == 'evergreen' ) {
        if(jQuery('body').hasClass('oxygen-builder-body')) localStorage.removeItem(countdownId + '_date');
        if ( localStorage.getItem(countdownId + '_date') === null) {
            var total_minutes = parseInt(eg_minutes) + (parseInt( eg_hours ) * 60) + (parseInt( eg_days ) * 24 * 60);
            var tmp_date = new Date();
            tmp_date = new Date( tmp_date.getTime() + total_minutes * 60000 );
            localStorage.setItem(countdownId + '_date', tmp_date.toString());
        } else {
            var tmp_date = new Date( localStorage.getItem(countdownId + '_date') );
        }
        target_time = tmp_date.getTime();
    }

    window.installedCountdowns[countdownId] = setInterval(function() {


        let countDown = new Date(target_time).getTime();
        let now = new Date().getTime(),
            distance = countDown - now;

        if(document.querySelector('#' + countdownId) == null){
            clearInterval(window.installedCountdowns[countdownId]);
            return;
        }

        let days = Math.floor(distance / day);
        if( (days <= 0 && showdayssegment =='if_needed') || showdayssegment =='never') jQuery('#'+countdownId+' .segment-days').hide();
        if( days > 0 && showdayssegment != 'never' ) jQuery('#'+countdownId+' .segment-days').show();
        if( parseInt(document.querySelector('#' + countdownId + ' .days').innerText) !== days ) {
            let newNum = jQuery( document.querySelector('#' + countdownId + ' .days').outerHTML );
            newNum.html( days );
            jQuery('#' + countdownId + ' .days').stop()[closeAnimFunc]({duration:closeAnimFunc == 'hide' ? 0: 400, complete:function(){
                    jQuery(this).after( newNum );
                    newNum.hide().css('opacity', 1).css('height', 'auto');
                    jQuery(this).remove();
                    newNum[openAnimFunc]();
                }})
        }

        let hours = Math.floor((distance % (day)) / hour);
        if( showdayssegment == 'never' ) hours += days * 24;
        if( parseInt(document.querySelector('#' + countdownId + ' .hours').innerText) !== hours ) {
            let newNum = jQuery( document.querySelector('#' + countdownId + ' .hours').outerHTML );
            newNum.html( hours );
            jQuery('#' + countdownId + ' .hours').stop()[closeAnimFunc]({duration:closeAnimFunc == 'hide' ? 0: 400, complete:function(){
                    jQuery(this).after( newNum );
                    newNum.hide().css('opacity', 1).css('height', 'auto');
                    jQuery(this).remove();
                    newNum[openAnimFunc]();
                }})
        }

        if( parseInt(document.querySelector('#' + countdownId + ' .minutes').innerText) !== Math.floor((distance % (hour)) / minute) ) {
            let newNum = jQuery( document.querySelector('#' + countdownId + ' .minutes').outerHTML );
            newNum.html( Math.floor((distance % (hour)) / minute) );
            jQuery('#' + countdownId + ' .minutes').stop()[closeAnimFunc]({duration:closeAnimFunc == 'hide' ? 0: 400, complete:function(){
                    jQuery(this).after( newNum );
                    newNum.hide().css('opacity', 1).css('height', 'auto');
                    jQuery(this).remove();
                    newNum[openAnimFunc]();
                }})
        }

        if( parseInt(document.querySelector('#' + countdownId + ' .seconds').innerText) !== Math.floor((distance % (minute)) / second) ) {
            let newNum = jQuery( document.querySelector('#' + countdownId + ' .seconds').outerHTML );
            newNum.html( Math.floor((distance % (minute)) / second) );
            jQuery('#' + countdownId + ' .seconds').stop()[closeAnimFunc]({duration:closeAnimFunc == 'hide' ? 0: 400, complete:function(){
                    jQuery(this).after( newNum );
                    newNum.hide().css('opacity', 1).css('height', 'auto');
                    jQuery(this).remove();
                    newNum[openAnimFunc]();
                }})
        }

        if (distance < 0) {
            clearInterval(window.installedCountdowns[countdownId]);
            if(typeof oxyPowerPackEvents != 'undefined') oxyPowerPackEvents.trigger('countdown-finished', {timer: countdownId});
            document.querySelector('#' + countdownId ).innerHTML = '<h2>'+message+'</h2>';
        }

    }, second);

}
