<?php
defined('ABSPATH') or die();

class OxyPowerPackBeforeAfter extends OxyPowerPackEl
{
    function init() {
        $this->El->inlineJS("jQuery(document).ready(function(){ oxyBeforeAfterInit(jQuery('#%%ELEMENT_ID%% div.beforeafter'), '%%before_image%%', '%%after_image%%', '%%before_label%%', '%%after_label%%'); });" );
        $this->El->JS(array(plugin_dir_url(__FILE__) . 'beforeafter/twentytwenty/js/jquery.event.move.js', plugin_dir_url(__FILE__) . 'beforeafter/twentytwenty/js/jquery.twentytwenty.js', plugin_dir_url(__FILE__) . 'beforeafter/script.js'), array('jquery'));
        $this->El->CSS(plugin_dir_url(__FILE__) . 'beforeafter/twentytwenty/css/twentytwenty.css');
    }

    function button_place() {
        return "oxypowerpack::power";
    }

    function button_priority() {
        return 3;
    }

    function icon() {
        return plugin_dir_url(__FILE__) . '../assets/img/icon-beforeafter.svg';
    }

    function description() {
    	return "Compare two images with an interactive slider that allows users unveil one image over another.";
	}

    function name() {
        return "Before After Image";
    }

    function defaultCSS(){
        return '.oxy-before-after-image {width:100%;}';
    }

    function options(){
        return array(
            "server_side_render" => false
        );
    }

    function render(){
        return '<div class="beforeafter"></div>';
    }

    function controls(){
        $before_image = $this->El->addControl("mediaurl", 'before_image', 'Before Image');
        $before_image->setValue(plugin_dir_url(__FILE__) . 'beforeafter/before.jpg');
        $this->addOptionControl(
            array(
                "type" => "textfield",
                "name" => "Before Label",
                "slug" => "before_label"
            )
        )->setValue('Before Gym');

        $after_image = $this->El->addControl("mediaurl", 'after_image', 'After Image');
        $after_image->setValue(plugin_dir_url(__FILE__) . 'beforeafter/after.jpg');
        $this->addOptionControl(
            array(
                "type" => "textfield",
                "name" => "After Label",
                "slug" => "after_label"
            )
        )->setValue('After Gym');

        $this->addApplyParamsButton();
    }
}
global $oxyPowerPackComponents;
$oxyPowerPackComponents['oxy-before-after-image'] = new OxyPowerPackBeforeAfter();
