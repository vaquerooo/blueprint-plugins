<?php

Class OxyPowerPackElement extends OxygenElement {



	public function shortcode($atts, $content=null, $name=null) {

		if ( ! $this->component->validate_shortcode( $atts, $content, $name ) ) {
			return '';
		}

		// set OxygenElement options to old CT_Component options
		$this->component->options['params'] = $this->get_controls();
		$options = $this->component->set_options( $atts );
		$options['tag'] = isset($options['html_tag']) ? $options['html_tag'] : $this->params['html_tag'];

		// save $options to instance
		$this->params["shortcode_options"] = $options;

		// prepare HTML output
		if (isset($this->params['php_callback'])) {
			if ( is_callable($this->params['php_callback']) || function_exists($this->params['php_callback'])) {
				ob_start();

                // base 64 decode $options
                $processed_options = $this->unprefix_options($options);
                if(method_exists($this, 'base64_decode_options' ) ) $processed_options = $this->base64_decode_options($processed_options);

				call_user_func_array($this->params['php_callback'], array($processed_options, $this->unprefix_options($this->defaults), $content));
				$html = ob_get_clean();
			}
			else {
				$this->add_error($this->params['php_callback'] . " PHP Callback does not exist");
			}
		}
		else {
			$html = $this->params['html'];
			$html = str_replace("%%CONTENT%%", do_shortcode($content), $html);
			$html = $this->filterOptions($html);
		}

		ob_start();

		?>

		<<?php echo esc_attr($options['tag'])?> id="<?php echo esc_attr($options['selector']); ?>" class="<?php if(isset($options['classes'])) echo esc_attr($options['classes']); echo " " . $this->params['wrapper_class']; ?>" <?php do_action("oxygen_vsb_component_attr", $options, $options['tag']) ?>><?php echo $html; ?></<?php echo esc_attr($options['tag'])?>>

		<?php

		if ( isset($this->params['inlineJS']) ) {
			$this->JSOutput($this->params['inlineJS']);
		}

		$this->outputErrors();

		$html = ob_get_clean();

		return $html;
	}

}
