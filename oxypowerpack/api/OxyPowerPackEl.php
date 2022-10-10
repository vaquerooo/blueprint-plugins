<?php

class OxyPowerPackEl extends OxyEl {

	function __construct() {

		$name = $this->name();
		$slug = $this->name2slug($name);

		if ($this->slug()) {
			$slug = $this->slug();
		}

		$this->custom_init();

		// store a slug to class name reference in the global space
		global $oxy_el_slug_classes;

		if(!is_array($oxy_el_slug_classes)) {
			$oxy_el_slug_classes = array();
		}

		$oxy_el_slug_classes[$slug] = get_class($this);


		$options = array();
		if (method_exists($this, 'options')) {
			$options = $this->options();
		}

		$server_side_render = true;
		if (isset( $options['server_side_render'] )) {
			$server_side_render = $options['server_side_render'];
		}

        if (method_exists($this, 'button_priority')) {
            $options['button_priority'] = $this->button_priority();
        }

		$this->El = new OxyPowerPackElement(__($name), $slug, '', $this->icon(), $this->button_place(), $options, $this->has_js);

		$this->El->setTag($this->tag());

		if (method_exists($this, 'init')) {
			$this->init();
		}

		if (method_exists($this, 'defaultCSS')) {
			$this->El->pageCSS(
				$this->defaultCSS()
			);
		}

        if (method_exists($this, 'customCSS')) {
            add_filter( "oxygen_id_styles_filter-".$this->El->get_tag(),
                function($styles, $states, $selector){
                    // doesn't work with states or media for now only 'original' options
                    $styles.=$this->customCSS($states['original'], $selector);
                    return $styles;
                },
                10, 3 );
        }

        if (method_exists($this, 'enablePresets') && $this->enablePresets() == true) {
            add_filter("oxygen_elements_with_presets_list", function($elements) {
                if (!is_array($elements)) {
                    $elements = array();
                }
                $elements[] = $this->El->get_tag();
                return $elements;
            });
        }

		$this->controls();
		$this->El->controlsReady();

		if( $server_side_render ) {
			$this->El->PHPCallback(
				array($this, 'render'),
				$this->class_names()
			);
		} else {
			$this->El->HTML(
				$this->render(),
				$this->class_names()
			);
		}

		$this->El->set_prefilled_components($this->prefilledComponentStructure());

        /**
         * Keep it very last one
         */

        if (method_exists($this, 'afterInit')) {
            $this->afterInit();
        }

	}


}
