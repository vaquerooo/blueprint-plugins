<?php
defined('ABSPATH') or die();

class OxyPowerPackEventTicket extends OxyPowerPackEl
{
	function init() {
	}

    function button_place() {
        return "oxypowerpack::basic";
    }

    function button_priority() {
        return 1;
    }
    function icon() {
        return plugin_dir_url(__FILE__) . '../assets/img/icon-ticket.svg';
    }

	function description() {
		return "Editable ticket for events.";
	}

	function name() {
		return "Event Ticket";
	}

	function defaultCSS(){
		return file_get_contents( plugin_dir_path(__FILE__) . 'eventticket/style.css' );
	}

	function options(){
		return array(
			"server_side_render" => false
		);
	}

	function render(){
		return file_get_contents( plugin_dir_path(__FILE__) . 'eventticket/template.html' );
	}

	function controls(){
		$event_texts_section = $this->addControlSection("event_content", __("Content"), "assets/icon.png", $this);
		$this->addOptionControl(
			array(
				"type" => "textfield",
				"name" => "Title",
				"slug" => "event_title"
			),$event_texts_section
		)->setValue('The Oxygeners');
		$this->addOptionControl(
			array(
				"type" => "textfield",
				"name" => "Subtitle",
				"slug" => "event_subtitle"
			),$event_texts_section
		)->setValue('World Tour');
		$this->addOptionControl(array("type" => "mediaurl", "slug" => 'event_image', "name" => 'Image'),$event_texts_section )->setValue('https://s3-us-west-2.amazonaws.com/s.cdpn.io/199011/concert.png');
		$this->addOptionControl(
			array(
				"type" => "textfield",
				"name" => "Date",
				"slug" => "event_date"
			),$event_texts_section
		)->setValue('March 2nd, 2020');
		$this->addOptionControl(
			array(
				"type" => "textfield",
				"name" => "Location",
				"slug" => "event_location"
			),$event_texts_section
		)->setValue('Monterrey, Mexico');
		$this->addOptionControl(
			array(
				"type" => "textfield",
				"name" => "Price Label",
				"slug" => "event_price_label"
			),$event_texts_section
		)->setValue('Price');
		$this->addOptionControl(
			array(
				"type" => "textfield",
				"name" => "Price",
				"slug" => "event_price"
			),$event_texts_section
		)->setValue('$99');
		$this->addOptionControl(
			array(
				"type" => "textfield",
				"name" => "Button Text",
				"slug" => "event_button_text"
			),$event_texts_section
		)->setValue('BUY TICKET');
		$this->addOptionControl(
			array(
				"type" => "textfield",
				"name" => "Button URL",
				"slug" => "button_url"
			),$event_texts_section
		)->setValue('#');
		$this->addStyleControl(
			array(
				"property" => "background-color",
				"name" => "Button Color",
				"selector" => "a.buy"
			),$event_texts_section
		);

		$typography_section = $this->addControlSection("typography", __("Typography"), "assets/icon.png", $this);
		$typography_section->typographySection('Title', '.bandname',$this);
		$typography_section->typographySection('Sub Title', '.tourname',$this);
		$typography_section->typographySection('Date', '.date',$this);
		$typography_section->typographySection('Location', '.location',$this);
		$typography_section->typographySection('Price Label', '.price .label',$this);
		$typography_section->typographySection('Price', '.price .cost',$this);
		$typography_section->typographySection('Button', '.buy',$this);
	}
}
global $oxyPowerPackComponents;
$oxyPowerPackComponents['oxy-event-ticket'] = new OxyPowerPackEventTicket();

