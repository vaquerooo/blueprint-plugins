<?php
defined('ABSPATH') or die();

class OxyPowerPackCountdown extends OxyPowerPackEl
{
    function init() {
        $this->El->inlineJS("jQuery(document).ready(function(){oxy_setup_countdown('%%ELEMENT_ID%%', '%%target_time%%', '%%message%%', '%%openanimfunc%%', '%%closeanimfunc%%', '%%showdayssegment%%', '%%type%%', '%%days%%', '%%hours%%', '%%minutes%%')});" );
        $this->El->JS(array(plugin_dir_url(__FILE__) . 'countdown/script.js'), array('jquery'));

        //$this->El->base64_options = array(
        //        'oxy-countdown-timer_target_time'
        //);

        add_action('ct_toolbar_component_settings', function(){
        	?>
			<div ng-show="iframeScope.component.active.name=='oxy-countdown-timer'&&!hasOpenTabs('oxy-countdown-timer')&&iframeScope.component.options[iframeScope.component.active.id].model['oxy-countdown-timer_type']!='evergreen'" style="width: 293px; transform: scale(0.90); margin-left: -10px;">
				<input type="text" id="oxypowerpack_countdown_helper">
			</div>
			<?php
		});
    }

    function button_place() {
        return "oxypowerpack::power";
    }

    function button_priority() {
        return 2;
    }

    function icon() {
        return plugin_dir_url(__FILE__) . '../assets/img/icon-countdown.svg';
    }

	function description() {
		return "Countdown timer with configurable target date/time, segment animation, style and a message to show when the countdown reaches zero.";
	}

    function name() {
        return "Countdown Timer";
    }

    function options(){
        return array(
            "server_side_render" => false
        );
    }

    function defaultCSS() {
        return file_get_contents( plugin_dir_path(__FILE__) . 'countdown/style.css' );
    }

    function render(){
        return file_get_contents( plugin_dir_path(__FILE__) . 'countdown/template.html' );
    }

    function controls(){

        $this->addOptionControl(
            array(
                'type' => 'buttons-list',
                'name' => 'Type',
                'slug' => 'type')
        )->setValue(array( "fixed" => "Fixed Date", "evergreen" => "Evergreen" ))->setDefaultValue('fixed');

        $this->addOptionControl(
            array(
                "type" => "textfield",
                "name" => "Target Date & Time",
                "slug" => "target_time",
                "condition" => 'type=fixed',
            )
        )->setValue(date('M d Y H:i:s \G\M\TO', time()+15780000)); // Set initial date/time to 6 months in the future

        $this->addOptionControl(
            array(
                "type" => "textfield",
                "name" => "Days",
                "slug" => "days",
                "condition" => 'type=evergreen',
            )
        )->setValue('0');
        $this->addOptionControl(
            array(
                "type" => "textfield",
                "name" => "Hours",
                "slug" => "hours",
                "condition" => 'type=evergreen',
            )
        )->setValue('6');
        $this->addOptionControl(
            array(
                "type" => "textfield",
                "name" => "Minutes",
                "slug" => "minutes",
                "condition" => 'type=evergreen',
            )
        )->setValue('14');

        $this->addOptionControl(
            array(
                "type" => "textfield",
                "name" => "Time Out Message",
                "slug" => "message"
            )
        )->setValue('Time Out');

        $segments_section = $this->addControlSection("segments", __("Segments"), "assets/icon.png", $this);

        $this->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => 'In Effect',
                "slug" => 'openanimfunc'
            ), $segments_section
        )->setValue(array('show', 'slideDown', 'fadeIn'));

        $this->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => 'Out Effect',
                "slug" => 'closeanimfunc'
            ), $segments_section
        )->setValue(array('hide', 'slideUp', 'fadeOut'));

        $this->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => 'Show Days Segment',
                "slug" => 'showdayssegment'
            ), $segments_section
        )->setValue(array('always' => 'Always', 'if_needed'=>'If Needed', 'never'=>'Never'));

        $this->addOptionControl(
            array(
                "type" => "textfield",
                "name" => "Days Label",
                "slug" => "days_label"
            ), $segments_section
        )->setValue('DAYS');
        $this->addOptionControl(
            array(
                "type" => "textfield",
                "name" => "Hours Label",
                "slug" => "hours_label"
            ), $segments_section
        )->setValue('HOURS');
        $this->addOptionControl(
            array(
                "type" => "textfield",
                "name" => "Minutes Label",
                "slug" => "minutes_label"
            ), $segments_section
        )->setValue('MINUTES');
        $this->addOptionControl(
            array(
                "type" => "textfield",
                "name" => "Seconds Label",
                "slug" => "seconds_label"
            ), $segments_section
        )->setValue('SECONDS');

		$segments_section->addPreset('padding','padding','Segment Padding', '.segment');

        $segments_section->typographySection('Segment Typography', '.segment .number',$this);
        $segments_section->typographySection('Label Typography', '.segment .label',$this);

        // manually add button to re-render element anytime
        $this->addApplyParamsButton();
    }
}
global $oxyPowerPackComponents;
$oxyPowerPackComponents['oxy-countdown-timer'] = new OxyPowerPackCountdown();
