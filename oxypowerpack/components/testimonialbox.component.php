<?php
defined('ABSPATH') or die();

class OxyPowerPackTestimonialBox extends OxyPowerPackEl
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
        return plugin_dir_url(__FILE__) . '../assets/img/icon-testimonial.svg';
    }

	function description() {
		return "Modern testimonial component with boxed style.";
	}

	function name() {
		return "Testimonial Box";
	}

	function defaultCSS(){
		return file_get_contents( plugin_dir_path(__FILE__) . 'testimonialbox/style.css' );
	}

	function options(){
		return array(
			"server_side_render" => false
		);
	}

	function render(){
		return file_get_contents( plugin_dir_path(__FILE__) . 'testimonialbox/template.html' );
	}

	function controls(){
		$testimonial_texts_section = $this->addControlSection("testimonial_content", __("Content"), "assets/icon.png", $this);
		$this->addOptionControl(array("type" => "mediaurl", "slug" => 'testimonial_photo', "name" => 'Photo'),$testimonial_texts_section )->setValue(plugin_dir_url(__FILE__) . 'testimonialbox/James_Cameron_October_2012.jpg');
		$this->addOptionControl(
			array(
				"type" => "textfield",
				"name" => "Author",
				"slug" => "testimonial_author"
			),$testimonial_texts_section
		)->setValue('James Cameron');
		$this->addOptionControl(
			array(
				"type" => "textfield",
				"name" => "Title",
				"slug" => "testimonial_author_subtitle"
			),$testimonial_texts_section
		)->setValue('Filmmaker');
		$this->addOptionControl(
			array(
				"type" => "textfield",
				"name" => "Text",
				"slug" => "testimonial_text"
			),$testimonial_texts_section
		)->setValue('If you set your goals ridiculously high and it\'s a failure, you will fail above everyone else\'s success.');

		$typography_section = $this->addControlSection("typography", __("Typography"), "assets/icon.png", $this);
		$typography_section->typographySection('Author', '.header h3',$this);
		$typography_section->typographySection('Title', '.header span',$this);
		$typography_section->typographySection('Text', 'blockquote',$this);

		$colors_section = $this->addControlSection("testimonial_colors", __("Colors"), "assets/icon.png", $this);
		$this->addStyleControl(
			array(
				"property" => "background-color",
				"name" => "Header Color",
				"selector" => "figure"
			),$colors_section
		);
		$this->addStyleControl(
			array(
				"property" => "background-color",
				"name" => "Body Color",
				"selector" => "figcaption"
			),$colors_section
		);
	}
}
global $oxyPowerPackComponents;
$oxyPowerPackComponents['oxy-testimonial-box'] = new OxyPowerPackTestimonialBox();
