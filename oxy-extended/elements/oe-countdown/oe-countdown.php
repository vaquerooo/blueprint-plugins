<?php
namespace Oxygen\OxyExtended;

/**
 * Countdown Element
 */
class OECountdown extends \OxyExtendedEl {

	public $css_added = false;

	/**
	 * Retrieve countdown element name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element name.
	 */
	public function name() {
		return __( 'Countdown', 'oxy-extended' );
	}

	/**
	 * Retrieve countdown element slug.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element slug.
	 */
	public function slug() {
		return 'oe_countdown';
	}

	/**
	 * Element Subsection
	 *
	 * @return string
	 */
	public function oxyextend_button_place() {
		return 'general';
	}

	/**
	 * Retrieve countdown element icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Element icon.
	 */
	public function icon() {
		return OXY_EXTENDED_URL . 'assets/images/elements/' . basename( __FILE__, '.php' ) . '.svg';
	}

	/**
	 * Element HTML tag
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return tag
	 */
	public function tag() {
		return 'div';
	}

	/**
	 * Years list
	 *
	 * @return array
	 */
	public function get_normal_years() {
		$options = array( '0' => __( 'Select', 'oxy-extended' ) );

		$years = gmdate( 'Y' );

		for ( $i = $years; $i < $years + 6; $i++ ) {
			$options[ $i ] = $i;
		}

		return $options;
	}

	/**
	 * Months list
	 *
	 * @return array
	 */
	public function get_normal_month() {
		$months = array(
			'0'  => __( 'Select', 'oxy-extended' ),
			'01' => __( 'Jan', 'oxy-extended' ),
			'02' => __( 'Feb', 'oxy-extended' ),
			'03' => __( 'Mar', 'oxy-extended' ),
			'04' => __( 'Apr', 'oxy-extended' ),
			'05' => __( 'May', 'oxy-extended' ),
			'06' => __( 'Jun', 'oxy-extended' ),
			'07' => __( 'Jul', 'oxy-extended' ),
			'08' => __( 'Aug', 'oxy-extended' ),
			'09' => __( 'Sep', 'oxy-extended' ),
			'10' => __( 'Oct', 'oxy-extended' ),
			'11' => __( 'Nov', 'oxy-extended' ),
			'12' => __( 'Dec', 'oxy-extended' ),
		);

		return $months;
	}

	/**
	 * Get Counter Date
	 *
	 * @return array
	 */
	protected function get_normal_date() {
		$options = array( '0' => __( 'Select', 'oxy-extended' ) );

		for ( $i = 1; $i <= 31; $i++ ) {
			if ( $i < 10 ) {
				$i = '0' . $i;
			}

			$options[ "{$i}" ] = $i;
		}

		return $options;
	}

	/**
	 * Get Counter Hours
	 *
	 * @return array
	 */
	protected function get_normal_hour() {
		$options = array( '0' => __( 'Select', 'oxy-extended' ) );

		for ( $i = 0; $i < 24; $i++ ) {
			if ( $i < 10 ) {
				$i = '0' . $i;
			}

			$options[ "{$i}" ] = $i;
		}

		return $options;
	}

	/**
	 * Get Counter Minutes
	 *
	 * @return array
	 */
	protected function get_normal_minutes_seconds() {
		$options = array( '0' => __( 'Select', 'oxy-extended' ) );

		for ( $i = 0; $i < 60; $i++ ) {
			if ( $i < 10 ) {
				$i = '0' . $i;
			}

			$options[ "{$i}" ] = $i;
		}

		return $options;
	}

	/**
	 * Element Controls
	 *
	 * Adds different controls to allow the user to change and customize the element settings.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function controls() {
		$this->general_controls();
		$this->action_controls();
		$this->labels_controls();
		$this->digits_controls();
		$this->separator_controls();
	}

	/**
	 * Controls for general section
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function general_controls() {
		$timer_type = $this->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Timer Type', 'oxy-extended' ),
				'slug'    => 'oe_timer_type',
				'value'   => array(
					'fixed'     => __( 'Fixed', 'oxy-extended' ),
					'evergreen' => __( 'Evergreen', 'oxy-extended' ),
				),
				'default' => 'fixed',
				'css'     => false,
			)
		);
		$timer_type->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$timer_type->rebuildElementOnChange();

		$timezone = $this->addCustomControl("
			<div class='oxygen-control'>
				<select name=\"oxy-oe_countdown_oe_countdown_timezone\" id=\"oxy-oe_countdown_oe_countdown_timezone\" 
					ng-init=\"initSelect2('oxy-oe_countdown_oe_countdown_timezone','Choose timezone...')\"
					ng-model=\"iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_countdown_oe_countdown_timezone']\"
					ng-change=\"iframeScope.setOption(iframeScope.component.active.id,'oxy-oe_countdown_oe_countdown_timezone','oxy-oe_countdown_oe_countdown_timezone')\">" .
					$this->oe_timezones( 'Asia/Kolkata' ) . '
				</select>
			</div>',
			'oe_countdown_timezone'
		);
		$timezone->setParam( 'heading', __( 'Time Zone', 'oxy-extended' ) );

		$date_time_source = $this->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Date Time Source', 'oxy-extended' ),
				'slug'    => 'oe_date_time_source',
				'value'   => array(
					'default' => __( 'Default', 'oxy-extended' ),
					'acf'     => __( 'ACF', 'oxy-extended' ),
					'acfopt'  => __( 'ACF Option Page', 'oxy-extended' ),
				),
				'default' => 'default',
				'css'     => false,
			)
		);
		$date_time_source->setParam( 'ng_show', "!iframeScope.isEditing('state')&&!iframeScope.isEditing('media')" );
		$date_time_source->rebuildElementOnChange();

		$acf_field = $this->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Date/Time Picker Field Name', 'oxy-extended' ),
				'slug'      => 'oe_acf_timer',
				'condition' => 'oe_date_time_source=acf',
			)
		);
		$acf_field->setParam( 'description', __( 'You will select the evergreen timer type, when you are using the ACF Time Picker field type.', 'oxy-extended' ) );
		$acf_field->rebuildElementOnChange();

		$acf_opt = $this->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Date/Time Picker Field Name', 'oxy-extended' ),
				'slug'      => 'oe_acf_options',
				'condition' => 'oe_date_time_source=acfopt',
			)
		);
		$acf_opt->setParam( 'description', __( 'Enter the field name of your ACF option page.', 'oxy-extended' ) );
		$acf_opt->rebuildElementOnChange();

		$this->addOptionControl(
			array(
				'type'      => 'dropdown',
				'name'      => __( 'Year', 'oxy-extended' ),
				'slug'      => 'oe_countdown_year',
				'value'     => $this->get_normal_years(),
				'default'   => '2022',
				'condition' => 'oe_timer_type=fixed&&oe_date_time_source=default',
			)
		)->setParam( 'hide_wrapper_end', true );

		$this->addOptionControl(
			array(
				'type'      => 'dropdown',
				'name'      => __( 'Month', 'oxy-extended' ),
				'slug'      => 'oe_countdown_month',
				'value'     => $this->get_normal_month(),
				'default'   => '12',
				'condition' => 'oe_timer_type=fixed&&oe_date_time_source=default',
			)
		)->setParam( 'hide_wrapper_start', true );

		$this->addOptionControl(
			array(
				'type'      => 'dropdown',
				'name'      => __( 'Day', 'oxy-extended' ),
				'slug'      => 'oe_countdown_date',
				'value'     => $this->get_normal_date(),
				'default'   => '0',
				'condition' => 'oe_timer_type=fixed&&oe_date_time_source=default',
			)
		)->setParam( 'hide_wrapper_end', true );

		$this->addOptionControl(
			array(
				'type'      => 'dropdown',
				'name'      => __( 'Hour', 'oxy-extended' ),
				'slug'      => 'oe_countdown_hour',
				'value'     => $this->get_normal_hour(),
				'default'   => '0',
				'condition' => 'oe_timer_type=fixed&&oe_date_time_source=default',
			)
		)->setParam( 'hide_wrapper_start', true );

		$this->addOptionControl(
			array(
				'type'      => 'dropdown',
				'name'      => __( 'Minutes', 'oxy-extended' ),
				'slug'      => 'oe_countdown_minutes',
				'value'     => $this->get_normal_minutes_seconds(),
				'default'   => '0',
				'condition' => 'oe_timer_type=fixed&&oe_date_time_source=default',
			)
		)->setParam( 'hide_wrapper_end', true );

		$this->addOptionControl(
			array(
				'type'      => 'dropdown',
				'name'      => __( 'Seconds', 'oxy-extended' ),
				'slug'      => 'oe_countdown_seconds',
				'value'     => $this->get_normal_minutes_seconds(),
				'default'   => '0',
				'condition' => 'oe_timer_type=fixed&&oe_date_time_source=default',
			)
		)->setParam( 'hide_wrapper_start', true );
	}

	/**
	 * Controls for action section
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function action_controls() {
		/**
		 * Action Section
		 * -------------------------------------------------
		 */
		$action_section = $this->addControlSection( 'action_section', __( 'Action', 'oxy-extended' ), 'assets/icon.png', $this );

		$action_fixed = $action_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Action After Timer Expires', 'oxy-extended' ),
				'slug'      => 'oe_fixed_timer_action',
				'value'     => array(
					'none'     => __( 'None', 'oxy-extended' ),
					'hide'     => __( 'Hide Timer', 'oxy-extended' ),
					'msg'      => __( 'Display Message', 'oxy-extended' ),
					'redirect' => __( 'Redirect to URL', 'oxy-extended' ),
				),
				'default'   => 'none',
				'condition' => 'oe_timer_type=fixed',
			)
		);

		$action_evergreen = $action_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Action After Timer Expires', 'oxy-extended' ),
				'slug'      => 'oe_evergreen_timer_action',
				'value'     => array(
					'none'     => __( 'None', 'oxy-extended' ),
					'hide'     => __( 'Hide Timer', 'oxy-extended' ),
					'reset'    => __( 'Reset Timer', 'oxy-extended' ),
					'msg'      => __( 'Display Message', 'oxy-extended' ),
					'redirect' => __( 'Redirect to URL', 'oxy-extended' ),
				),
				'default'   => 'none',
				'condition' => 'oe_timer_type=evergreen',
			)
		);

		$message = $action_section->addOptionControl(
			array(
				'type'    => 'textarea',
				'name'    => __( 'Message', 'oxy-extended' ),
				'slug'    => 'expire_message',
				'default' => '',
			)
		);
		$message->setParam( 'ng_show', "(iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_countdown_oe_fixed_timer_action']=='msg'||iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_countdown_oe_evergreen_timer_action']=='msg')" );

		$redirect_link = $action_section->addCustomControl(
			'<div class="oxygen-file-input oxygen-option-default" ng-class="{\'oxygen-option-default\':iframeScope.isInherited(iframeScope.component.active.id, \'oxy-oe_countdown_redirect_link\')}">
				<input type="text" spellcheck="false" ng-model="iframeScope.component.options[iframeScope.component.active.id][\'model\'][\'oxy-oe_countdown_redirect_link\']" ng-model-options="{ debounce: 10 }" ng-change="iframeScope.setOption(iframeScope.component.active.id, iframeScope.component.active.name,\'oxy-oe_countdown_redirect_link\');iframeScope.checkResizeBoxOptions(\'oxy-oe_countdown_redirect_link\'); " class="ng-pristine ng-valid ng-touched" placeholder="http://">
				<div class="oxygen-set-link" data-linkproperty="url" data-linktarget="target" onclick="processOELink(\'oxy-oe_countdown_redirect_link\')">set</div>
				<div class="oxygen-dynamic-data-browse" ctdynamicdata data="iframeScope.dynamicShortcodesLinkMode" callback="iframeScope.oeDynamicCDUrl">data</div>
			</div>
			',
			'redirect_link'
		);
		$redirect_link->setParam( 'heading', __( 'Redirect URL', 'oxy-extended' ) );
		$redirect_link->setParam( 'ng_show', "(iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_countdown_oe_fixed_timer_action']=='redirect'||iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_countdown_oe_evergreen_timer_action']=='redirect')" );

		$action_section->addOptionControl(
			array(
				'name'    => __( 'Target', 'oxy-extended' ),
				'slug'    => 'redirect_link_target',
				'type'    => 'radio',
				'value'   => array(
					'_self'  => __( 'Same Window', 'oxy-extended' ),
					'_blank' => __( 'New Window', 'oxy-extended' ),
				),
				'default' => '_self',
			)
		)->setParam( 'ng_show', "(iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_countdown_oe_fixed_timer_action']=='redirect'||iframeScope.component.options[iframeScope.component.active.id]['model']['oxy-oe_countdown_oe_evergreen_timer_action']=='redirect')" );
	}

	/**
	 * Controls for labels section
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function labels_controls() {
		/**
		 * Labels Section
		 * -------------------------------------------------
		 */
		$labels_section = $this->addControlSection( 'labels_section', __( 'Labels', 'oxy-extended' ), 'assets/icon.png', $this );

		$show_labels = $labels_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Show Labels', 'oxy-extended' ),
				'slug'      => 'oe_show_labels',
				'value'     => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default'   => 'yes',
			)
		);
		$show_labels->rebuildElementOnChange();

		$labels_section->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Labels HTML Tag', 'oxy-extended' ),
				'slug'    => 'oe_labels_tag',
				'value'   => array(
					'h1'   => __( 'H1', 'oxy-extended' ),
					'h2'   => __( 'H2', 'oxy-extended' ),
					'h3'   => __( 'H3', 'oxy-extended' ),
					'h4'   => __( 'H4', 'oxy-extended' ),
					'h5'   => __( 'H5', 'oxy-extended' ),
					'h6'   => __( 'H6', 'oxy-extended' ),
					'div'  => __( 'div', 'oxy-extended' ),
					'span' => __( 'span', 'oxy-extended' ),
					'p'    => __( 'p', 'oxy-extended' ),
				),
				'default' => 'p',
			)
		)->rebuildElementOnChange();

		$labels_section->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Labels Position', 'oxy-extended' ),
				'slug'    => 'label_position',
				'value'   => array(
					'below' => __( 'Below Digit', 'oxy-extended' ),
					'above' => __( 'Above Digit', 'oxy-extended' ),
					'right' => __( 'Right Side of Digit', 'oxy-extended' ),
					'left'  => __( 'Left Side of Digit', 'oxy-extended' ),
				),
				'default' => 'below',
			)
		)->rebuildElementOnChange();

		/**
		 * Years Section
		 * -------------------------------------------------
		 */
		$years_section = $labels_section->addControlSection( 'years_section', __( 'Years', 'oxy-extended' ), 'assets/icon.png', $this );
		$years_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Show Years', 'oxy-extended' ),
				'slug'      => 'oe_show_years',
				'value'     => array(
					'Y' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default'   => 'Y',
			)
		)->rebuildElementOnChange();

		$years_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Label in Plural', 'oxy-extended' ),
				'slug'      => 'oe_label_years_plural',
				'value'     => __( 'Years', 'oxy-extended' ),
				'condition' => 'oe_show_labels=yes&&oe_show_years=Y',
			)
		)->rebuildElementOnChange();

		$years_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Label in Singular', 'oxy-extended' ),
				'slug'      => 'oe_label_years_singular',
				'value'     => __( 'Year', 'oxy-extended' ),
				'condition' => 'oe_show_labels=yes&&oe_show_years=Y',
			)
		)->rebuildElementOnChange();

		/**
		 * Months Section
		 * -------------------------------------------------
		 */
		$months_section = $labels_section->addControlSection( 'months_section', __( 'Months', 'oxy-extended' ), 'assets/icon.png', $this );
		$months_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Show Months', 'oxy-extended' ),
				'slug'      => 'oe_show_months',
				'value'     => array(
					'O' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default'   => 'O',
			)
		)->rebuildElementOnChange();

		$months_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Label in Plural', 'oxy-extended' ),
				'slug'      => 'oe_label_months_plural',
				'value'     => __( 'Months', 'oxy-extended' ),
				'condition' => 'oe_show_labels=yes&&oe_show_months=O',
			)
		)->rebuildElementOnChange();

		$months_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Label in Singular', 'oxy-extended' ),
				'slug'      => 'oe_label_months_singular',
				'value'     => __( 'Month', 'oxy-extended' ),
				'condition' => 'oe_show_labels=yes&&oe_show_months=O',
			)
		)->rebuildElementOnChange();

		/**
		 * Days Section
		 * -------------------------------------------------
		 */
		$days_section = $labels_section->addControlSection( 'days_section', __( 'Days', 'oxy-extended' ), 'assets/icon.png', $this );
		$days_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Show Days', 'oxy-extended' ),
				'slug'      => 'oe_show_days',
				'value'     => array(
					'D' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default'   => 'D',
			)
		)->rebuildElementOnChange();

		$days_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Label in Plural', 'oxy-extended' ),
				'slug'      => 'oe_label_days_plural',
				'value'     => __( 'Days', 'oxy-extended' ),
				'condition' => 'oe_show_labels=yes&&oe_show_days=D',
			)
		)->rebuildElementOnChange();

		$days_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Label in Singular', 'oxy-extended' ),
				'slug'      => 'oe_label_days_singular',
				'value'     => __( 'Day', 'oxy-extended' ),
				'condition' => 'oe_show_labels=yes&&oe_show_days=D',
			)
		)->rebuildElementOnChange();

		/**
		 * Hours Section
		 * -------------------------------------------------
		 */
		$hours_section = $labels_section->addControlSection( 'hours_section', __( 'Hours', 'oxy-extended' ), 'assets/icon.png', $this );
		$hours_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Show Hours', 'oxy-extended' ),
				'slug'      => 'oe_show_hours',
				'value'     => array(
					'H' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default'   => 'H',
			)
		)->rebuildElementOnChange();

		$hours_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Label in Plural', 'oxy-extended' ),
				'slug'      => 'oe_label_hours_plural',
				'value'     => __( 'Hours', 'oxy-extended' ),
				'condition' => 'oe_show_labels=yes&&oe_show_hours=H',
			)
		)->rebuildElementOnChange();

		$hours_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Label in Singular', 'oxy-extended' ),
				'slug'      => 'oe_label_hours_singular',
				'value'     => __( 'Hour', 'oxy-extended' ),
				'condition' => 'oe_show_labels=yes&&oe_show_hours=H',
			)
		)->rebuildElementOnChange();

		/**
		 * Mintues Section
		 * -------------------------------------------------
		 */
		$minutes_section = $labels_section->addControlSection( 'minutes_section', __( 'Minutes', 'oxy-extended' ), 'assets/icon.png', $this );
		$minutes_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Show Minutes', 'oxy-extended' ),
				'slug'      => 'oe_show_minutes',
				'value'     => array(
					'M' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default'   => 'M',
			)
		)->rebuildElementOnChange();

		$minutes_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Label in Plural', 'oxy-extended' ),
				'slug'      => 'oe_label_minutes_plural',
				'value'     => __( 'Minutes', 'oxy-extended' ),
				'condition' => 'oe_show_labels=yes&&oe_show_minutes=M',
			)
		)->rebuildElementOnChange();

		$minutes_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Label in Singular', 'oxy-extended' ),
				'slug'      => 'oe_label_minutes_singular',
				'value'     => __( 'Minute', 'oxy-extended' ),
				'condition' => 'oe_show_labels=yes&&oe_show_minutes=M',
			)
		)->rebuildElementOnChange();

		/**
		 * Seconds Section
		 * -------------------------------------------------
		 */
		$seconds_section = $labels_section->addControlSection( 'seconds_section', __( 'Seconds', 'oxy-extended' ), 'assets/icon.png', $this );
		$seconds_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Show Seconds', 'oxy-extended' ),
				'slug'      => 'oe_show_seconds',
				'value'     => array(
					'S' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				),
				'default'   => 'S',
			)
		)->rebuildElementOnChange();

		$seconds_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Label in Plural', 'oxy-extended' ),
				'slug'      => 'oe_label_seconds_plural',
				'value'     => __( 'Seconds', 'oxy-extended' ),
				'condition' => 'oe_show_labels=yes&&oe_show_seconds=S',
			)
		)->rebuildElementOnChange();

		$seconds_section->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => __( 'Label in Singular', 'oxy-extended' ),
				'slug'      => 'oe_label_seconds_singular',
				'value'     => __( 'Second', 'oxy-extended' ),
				'condition' => 'oe_show_labels=yes&&oe_show_seconds=S',
			)
		)->rebuildElementOnChange();

		// Labels Typography
		$labels_section->typographySection( __( 'Typography', 'oxy-extended' ), '.oe-countdown .oe-countdown-label', $this );
	}

	/**
	 * Controls for digits section
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function digits_controls() {
		/**
		 * Digits Section
		 * -------------------------------------------------
		 */
		$digits_section = $this->addControlSection( 'digits_section', __( 'Digits', 'oxy-extended' ), 'assets/icon.png', $this );

		$digits_section->addOptionControl(
			array(
				'type'    => 'dropdown',
				'name'    => __( 'Digits HTML Tag', 'oxy-extended' ),
				'slug'    => 'oe_digits_tag',
				'value'   => array(
					'h1'   => __( 'H1', 'oxy-extended' ),
					'h2'   => __( 'H2', 'oxy-extended' ),
					'h3'   => __( 'H3', 'oxy-extended' ),
					'h4'   => __( 'H4', 'oxy-extended' ),
					'h5'   => __( 'H5', 'oxy-extended' ),
					'h6'   => __( 'H6', 'oxy-extended' ),
					'div'  => __( 'div', 'oxy-extended' ),
					'span' => __( 'span', 'oxy-extended' ),
					'p'    => __( 'p', 'oxy-extended' ),
				),
				'default' => 'p',
			)
		)->rebuildElementOnChange();

		// Digits Typography
		$digits_section->typographySection( __( 'Typography', 'oxy-extended' ), '.oe-countdown .oe-countdown-digit', $this );
	}

	/**
	 * Controls for separator settings
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function separator_controls() {
		/**
		 * Separator Section
		 * -------------------------------------------------
		 */
		$separator_section = $this->addControlSection( 'separator_section', __( 'Separator', 'oxy-extended' ), 'assets/icon.png', $this );

		$display_separator = $separator_section->addOptionControl(
			array(
				'type'    => 'radio',
				'name'    => __( 'Display between blocks', 'oxy-extended' ),
				'slug'    => 'oe_display_separator',
				'value'   => array(
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no' => __( 'No', 'oxy-extended' ),
				),
				'default' => 'no',
			)
		);
		$display_separator->rebuildElementOnChange();

		$separator_type = $separator_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Type', 'oxy-extended' ),
				'slug'      => 'oe_separator_type',
				'value'     => array(
					'colon' => __( 'Colon', 'oxy-extended' ),
					'line'  => __( 'Line', 'oxy-extended' ),
				),
				'default'   => 'line',
				'condition' => 'oe_display_separator=yes',
			)
		);
		$separator_type->rebuildElementOnChange();

		//* Colon properties
		$separator_section->addStyleControls([
			[
				'name'      => __( 'Size', 'oxy-extended' ),
				'selector'  => '.countdown-separator-colon .oe-countdown-digit-wrapper:after',
				'property'  => 'font-size',
				'slug'      => 'oe_colon_size',
				'condition' => 'oe_display_separator=yes&&oe_separator_type=colon',
			],
			[
				'selector'  => '.oe-countdown-separator-colon .oe-countdown-digit-wrapper:after',
				'property'  => 'color',
				'slug'      => 'oe_line_color',
				'condition' => 'oe_display_separator=yes&&oe_separator_type=colon',
			],
		]);

		$postop = $separator_section->addStyleControl([
			'name'         => __( 'Position Top', 'oxy-extended' ),
			'selector'     => '.oe-countdown-separator-colon .oe-countdown-digit-wrapper:after',
			'property'     => 'top',
			'control_type' => 'measurebox',
			'condition'    => 'oe_display_separator=yes&&oe_separator_type=colon',
		]);
		$postop->setRange( '0', '100', '1' );
		$postop->setUnits( '%', 'px,%,em' );
		$postop->setDefaultValue( '50' );
		$postop->setParam( 'hide_wrapper_end', true );

		$posright = $separator_section->addStyleControl([
			'name'      => __( 'Position Right', 'oxy-extended' ),
			'selector'  => '.oe-countdown-separator-colon .oe-countdown-digit-wrapper:after',
			'property'  => 'right',
			'control_type'  => 'measurebox',
			'condition' => 'oe_display_separator=yes&&oe_separator_type=colon',
		]);
		$posright->setRange( '0', '100', '1' );
		$posright->setUnits( 'px', 'px,%,em' );
		$posright->setDefaultValue( '-5' );
		$posright->setParam( 'hide_wrapper_start', true );

		//* Line properties
		$separator_section->addStyleControls([
			[
				'name'      => __( 'Size', 'oxy-extended' ),
				'selector'  => '.oe-countdown-separator-line .oe-countdown-item:after',
				'property'  => 'border-width',
				'slug'      => 'oe_line_size',
				'condition' => 'oe_display_separator=yes&&oe_separator_type=line',
			],
			[
				'selector'  => '.oe-countdown-separator-line .oe-countdown-item:after',
				'property'  => 'border-color',
				'slug'      => 'oe_line_color',
				'condition' => 'oe_display_separator=yes&&oe_separator_type=line',
			],
		]);

		$separator_section->addOptionControl(
			array(
				'type'      => 'radio',
				'name'      => __( 'Hide on mobile', 'oxy-extended' ),
				'slug'      => 'oe_hide_separator',
				'value'     => [
					'yes' => __( 'Yes', 'oxy-extended' ),
					'no'  => __( 'No', 'oxy-extended' ),
				],
				'default'   => 'no',
				'condition' => 'oe_display_separator=yes',
			)
		);
	}

	public function getCountdownScript( $options, $uid ) {
		if ( ! empty( $options['oe_countdown_timezone'] ) ) {

			$time_zone_kolkata = new \DateTimeZone( 'Asia/Kolkata' );
			$time_zone         = new \DateTimeZone( $options['oe_countdown_timezone'] );

			$time_kolkata = new \DateTime( 'now', $time_zone_kolkata );

			$timeoffset = $time_zone->getOffset( $time_kolkata );

			$time_zone = $timeoffset / 3600;
		} else {
			$time_zone = 'NULL';
		}
		if ( class_exists( 'ACF' ) ) {
			if ( isset( $options['oe_date_time_source'] ) && 'acf' === $options['oe_date_time_source'] ) {
				$date = get_post_meta( get_the_ID(), $options['oe_acf_timer'], true );
				$options = $this->getACFDate( $date, $options );
			}

			if ( isset( $options['oe_date_time_source'] ) && 'acfopt' === $options['oe_date_time_source'] && function_exists( 'get_field' ) ) {
				$date = get_option( 'options_' . $options['oe_acf_options'] );
				$options = $this->getACFDate( $date, $options );
			}
		}

		$ftimer_labels = array(
			'year'      => array(
				'singular'  => ( isset( $options['oe_label_years_singular'] ) && '' !== $options['oe_label_years_singular'] ) ? $options['oe_label_years_singular'] : 'Year',
				'plural'    => ( isset( $options['oe_label_years_plural'] ) && '' !== $options['oe_label_years_plural'] ) ? $options['oe_label_years_plural'] : 'Years',
			),
			'month'     => array(

				'singular'  => ( isset( $options['oe_label_months_singular'] ) && '' !== $options['oe_label_months_singular'] ) ? $options['oe_label_months_singular'] : 'Month',
				'plural'    => ( isset( $options['oe_label_months_plural'] ) && '' !== $options['oe_label_months_plural'] ) ? $options['oe_label_months_plural'] : 'Months',
			),
			'day'       => array(
				'singular'  => ( isset( $options['oe_label_days_singular'] ) && '' !== $options['oe_label_days_singular'] ) ? $options['oe_label_days_singular'] : 'Day',
				'plural'    => ( isset( $options['oe_label_days_plural'] ) && '' !== $options['oe_label_days_plural'] ) ? $options['oe_label_days_plural'] : 'Days',
			),
			'hour'      => array(
				'singular'  => ( isset( $options['oe_label_hours_singular'] ) && '' !== $options['oe_label_hours_singular'] ) ? $options['oe_label_hours_singular'] : 'Hour',
				'plural'    => ( isset( $options['oe_label_hours_plural'] ) && '' !== $options['oe_label_hours_plural'] ) ? $options['oe_label_hours_plural'] : 'Hours',
			),
			'minute'    => array(
				'singular'  => ( isset( $options['oe_label_minutes_singular'] ) && '' !== $options['oe_label_minutes_singular'] ) ? $options['oe_label_minutes_singular'] : 'Minute',
				'plural'    => ( isset( $options['oe_label_minutes_plural'] ) && '' !== $options['oe_label_minutes_plural'] ) ? $options['oe_label_minutes_plural'] : 'Minutes',
			),
			'second'    => array(
				'singular'  => ( isset( $options['oe_label_seconds_singular'] ) && '' !== $options['oe_label_seconds_singular'] ) ? $options['oe_label_seconds_singular'] : 'Second',
				'plural'    => ( isset( $options['oe_label_seconds_plural'] ) && '' !== $options['oe_label_seconds_plural'] ) ? $options['oe_label_seconds_plural'] : 'Seconds',
			),
		);

		if ( 'below' === $options['label_position'] ) { ?>
			var default_layout_<?php echo $uid; ?> = '';
			<?php if ( 'evergreen' !== $options['oe_timer_type'] ) { ?>
			default_layout_<?php echo $uid; ?> += '{y<}'+ '<?php echo $this->oe_normal_view( $options, '{ynn}', '{yl}' ); ?>' +
				'{y>}';
			<?php } ?>

			default_layout_<?php echo $uid; ?> += '{o<}'+ '<?php echo $this->oe_normal_view( $options, '{onn}', '{ol}' ); ?>' +
				'{o>}'+
				'{d<}'+ '<?php echo $this->oe_normal_view( $options, '{dnn}', '{dl}' ); ?>' +
				'{d>}'+
				'{h<}'+ '<?php echo $this->oe_normal_view( $options, '{hnn}', '{hl}' ); ?>' +
				'{h>}'+
				'{m<}'+ '<?php echo $this->oe_normal_view( $options, '{mnn}', '{ml}' ); ?>' +
				'{m>}'+
				'{s<}'+ '<?php echo $this->oe_normal_view( $options, '{snn}', '{sl}' ); ?>' +
				'{s>}';

	<?php } elseif ( 'above' === $options['label_position'] ) { ?>

		var default_layout_<?php echo $uid; ?> = '';
			<?php if ( 'evergreen' !== $options['oe_timer_type'] ) { ?>
		default_layout_<?php echo $uid; ?> += '{y<}' + '<?php echo $this->oecd_inside_above_countdown( $options, '{ynn}', '{yl}', '{y>}' ); ?>';
		<?php } ?>
		default_layout_<?php echo $uid; ?> += '{o<}' + '<?php echo $this->oecd_inside_above_countdown( $options, '{onn}', '{ol}', '{o>}' ); ?>' +
			'{d<}' + '<?php echo $this->oecd_inside_above_countdown( $options, '{dnn}', '{dl}', '{d>}' ); ?>' +
			'{h<}' + '<?php echo $this->oecd_inside_above_countdown( $options, '{hnn}', '{hl}', '{h>}' ); ?>' +
			'{m<}' + '<?php echo $this->oecd_inside_above_countdown( $options, '{mnn}', '{ml}', '{m>}' ); ?>' +
			'{s<}' + '<?php echo $this->oecd_inside_above_countdown( $options, '{snn}', '{sl}', '{s>}' ); ?>';

	<?php } elseif ( 'right' === $options['label_position'] || 'left' === $options['label_position'] ) { ?>

		var default_layout_<?php echo $uid; ?> = '';
		<?php if ( 'evergreen' !== $options['oe_timer_type'] ) { ?>
		default_layout_<?php echo $uid; ?> += '{y<}' + '<?php echo $this->oecd_outside_countdown( $options, '{ynn}', '{yl}', '{y>}' ); ?>';
		<?php } ?>

		default_layout_<?php echo $uid; ?> += '{o<}' + '<?php echo $this->oecd_outside_countdown( $options, '{onn}', '{ol}', '{o>}' ); ?>' +
			'{d<}' + '<?php echo $this->oecd_outside_countdown( $options, '{dnn}', '{dl}', '{d>}' ); ?>' +
			'{h<}' + '<?php echo $this->oecd_outside_countdown( $options, '{hnn}', '{hl}', '{h>}' ); ?>' +
			'{m<}' + '<?php echo $this->oecd_outside_countdown( $options, '{mnn}', '{ml}', '{m>}' ); ?>' +
			'{s<}' + '<?php echo $this->oecd_outside_countdown( $options, '{snn}', '{sl}', '{s>}' ); ?>';
	<?php } else { ?>
		var default_layout_<?php echo $uid; ?> =  '';
		<?php if ( 'evergreen' !== $options['oe_timer_type'] ) { ?>
		default_layout_<?php echo $uid; ?> +=  '{y<}'+ '<?php echo $this->oe_normal_view( $options, '{ynn}', '{yl}' ); ?>' + '{y>}';
		<?php } ?>
		default_layout_<?php echo $uid; ?> += '{o<}'+ '<?php echo $this->oe_normal_view( $options, '{onn}', '{ol}' ); ?>' +
		'{o>}'+
		'{d<}'+ '<?php echo $this->oe_normal_view( $options, '{dnn}', '{dl}' ); ?>' +
		'{d>}'+
		'{h<}'+ '<?php echo $this->oe_normal_view( $options, '{hnn}', '{hl}' ); ?>' +
		'{h>}'+
		'{m<}'+ '<?php echo $this->oe_normal_view( $options, '{mnn}', '{ml}' ); ?>' +
		'{m>}'+
		'{s<}'+ '<?php echo $this->oe_normal_view( $options, '{snn}', '{sl}' ); ?>' +
		'{s>}';
	<?php } ?>

		jQuery(document).ready(function($) {
			if( parseInt(window.location.href.toLowerCase().indexOf("?ct_builder")) === parseInt(36) && 'evergreen' == '<?php echo $options['oe_timer_type']; ?>' ) {
				$.removeCookie( "countdown-<?php echo $uid; ?>");
				$.removeCookie( "countdown-<?php echo $uid; ?>-currdate");
				$.removeCookie( "countdown-<?php echo $uid; ?>-day");
				$.removeCookie( "countdown-<?php echo $uid; ?>-hour");
				$.removeCookie( "countdown-<?php echo $uid; ?>-min");
				$.removeCookie( "countdown-<?php echo $uid; ?>-sec");
			}
			new OECountdown({
				id: '<?php echo $uid; ?>',
				fixed_timer_action: '<?php echo $options['oe_fixed_timer_action']; ?>',
				evergreen_timer_action: '<?php echo $options['oe_evergreen_timer_action']; ?>',
				timertype: '<?php echo $options['oe_timer_type']; ?>',
				<?php if ( 'evergreen' === $options['oe_timer_type'] ) { ?>
				timer_date: new Date(),
				<?php } else { ?>
				timer_date: new Date( "<?php if ( isset( $options['oe_countdown_year'] ) ) {
					echo $options['oe_countdown_year']; } ?>", "<?php if ( isset( $options['oe_countdown_month'] ) ) {
					echo $options['oe_countdown_month'] - 1; } ?>", "<?php if ( isset( $options['oe_countdown_date'] ) ) {
					echo $options['oe_countdown_date']; } ?>", "<?php if ( isset( $options['oe_countdown_hour'] ) ) {
					echo $options['oe_countdown_hour']; } ?>", "<?php if ( isset( $options['oe_countdown_minutes'] ) ) {
					echo $options['oe_countdown_minutes']; } ?>", "<?php if ( isset( $options['oe_countdown_seconds'] ) ) {
					echo $options['oe_countdown_seconds']; } ?>" ),
				<?php } ?>
				timer_format: '<?php if ( isset( $options['oe_show_years'] ) && 'no' !== $options['oe_show_years'] ) {
					echo $options['oe_show_years']; } ?><?php if ( isset( $options['oe_show_months'] ) && 'no' !== $options['oe_show_months'] ) {
					echo $options['oe_show_months']; } ?><?php if ( isset( $options['oe_show_days'] ) && 'no' !== $options['oe_show_days'] ) {
					echo $options['oe_show_days']; } ?><?php if ( isset( $options['oe_show_hours'] ) && 'no' !== $options['oe_show_hours'] ) {
					echo $options['oe_show_hours']; } ?><?php if ( isset( $options['oe_show_minutes'] ) && 'no' !== $options['oe_show_minutes'] ) {
					echo $options['oe_show_minutes']; } ?><?php if ( isset( $options['oe_show_seconds'] ) && 'no' !== $options['oe_show_seconds'] ) {
					echo $options['oe_show_seconds']; } ?>',
				timer_layout: default_layout_<?php echo $uid; ?>,
				timer_labels: '<?php echo $ftimer_labels['year']['plural']; ?>,<?php echo $ftimer_labels['month']['plural']; ?>,,<?php echo $ftimer_labels['day']['plural']; ?>,<?php echo $ftimer_labels['hour']['plural']; ?>,<?php echo $ftimer_labels['minute']['plural']; ?>,<?php echo $ftimer_labels['second']['plural']; ?>',
				timer_labels_singular: 	'<?php echo $ftimer_labels['year']['singular']; ?>,<?php echo $ftimer_labels['month']['singular']; ?>,,<?php echo $ftimer_labels['day']['singular']; ?>,<?php echo $ftimer_labels['hour']['singular']; ?>,<?php echo $ftimer_labels['minute']['singular']; ?>,<?php echo $ftimer_labels['second']['singular']; ?>',
				redirect_link_target: '<?php echo ( '' !== $options['redirect_link_target'] ) ? $options['redirect_link_target'] : ''; ?>',
				redirect_link: '<?php echo ( isset( $options['redirect_link'] ) ) ? $options['redirect_link'] : ''; ?>',
				expire_message: '<?php echo ( '' !== $options['expire_message'] ) ? preg_replace( '/\s+/', ' ', $options['expire_message'] ) : ''; ?>',
				evergreen_date_days: '<?php echo isset( $options['oe_countdown_date'] ) ? $options['oe_countdown_date'] : ''; ?>',
				evergreen_date_hours: '<?php echo isset( $options['oe_countdown_hour'] ) ? $options['oe_countdown_hour'] : ''; ?>',
				evergreen_date_minutes: '<?php echo isset( $options['oe_countdown_minutes'] ) ? $options['oe_countdown_minutes'] : ''; ?>',
				evergreen_date_seconds: '<?php echo isset( $options['oe_countdown_seconds'] ) ? $options['oe_countdown_seconds'] : ''; ?>',
				time_zone: '<?php echo $time_zone; ?>',
				<?php if ( isset( $options['oe_fixed_timer_action'] ) && 'msg' === $options['oe_fixed_timer_action'] ) { ?>
				timer_exp_text: '<div class="oe-countdown-expire-message"><?php echo ( '' !== $options['expire_message'] ) ? preg_replace( '/\s+/', ' ', $options['expire_message'] ) : ''; ?></div>'
				<?php } ?>
			});
		});
		<?php
	}

	public function oe_normal_view( $options, $str1, $str2 ) {
		ob_start();

		?><div class="oe-countdown-item"><div class="oe-countdown-digit-wrapper "><<?php echo $options['oe_digits_tag']; ?> class="oe-countdown-digit "><?php echo $str1; ?></<?php echo $options['oe_digits_tag']; ?>></div><?php if ( 'yes' == $options['oe_show_labels'] ) {
		?><div class="oe-countdown-label-wrapper"><<?php echo $options['oe_labels_tag']; ?> class="oe-countdown-label "><?php echo $str2; ?></<?php echo $options['oe_labels_tag']; ?>></div><?php } ?></div><?php

		$html = ob_get_contents();

		ob_end_clean();

		return $html;
	}

	public function oecd_inside_above_countdown( $options, $str1, $str2, $str3 ) {
		ob_start();

		?><div class="oe-countdown-item"><?php if ( 'yes' === $options['oe_show_labels'] ) {
		?><div class="oe-countdown-label-wrapper"><<?php echo $options['oe_labels_tag']; ?> class="oe-countdown-label"><?php echo $str2; ?></<?php echo $options['oe_labels_tag']; ?>></div><?php } ?><div class="oe-countdown-digit-wrapper"><<?php echo $options['oe_digits_tag']; ?> class="oe-countdown-digit"><?php echo $str1; ?></<?php echo $options['oe_digits_tag']; ?>></div><?php echo $str3; ?></div><?php

		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	public function oecd_outside_countdown( $options, $str1, $str2, $str3 ) {
		ob_start();

		?><div class="oe-countdown-item label-position-<?php echo $options['label_position']; ?>"><?php if ( 'yes' === $options['oe_show_labels'] ) {
		?><div class="oe-countdown-label-wrapper"><<?php echo $options['oe_labels_tag']; ?> class="oe-countdown-label "><?php echo $str2; ?></<?php echo $options['oe_labels_tag']; ?>></div><?php } ?><div class="oe-countdown-digit-wrapper "><<?php echo $options['oe_digits_tag']; ?> class="oe-countdown-digit "><?php echo $str1; ?></<?php echo $options['oe_digits_tag']; ?>></div><?php echo $str3; ?></div><?php

		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	/**
	 * Render countdown element output on the frontend.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param  mixed $options  Element options.
	 * @param  mixed $defaults Element defaults.
	 * @param  mixed $content  Element content.
	 * @return void
	 */
	public function render( $options, $defaults, $content ) {
		$uid = str_replace( '-', '', $options['selector'] ) . get_the_ID();
		$sep = '';

		if ( isset( $options['oe_display_separator'] ) && 'yes' === $options['oe_display_separator'] ) {
			$sep = ' oe-countdown-separator-' . $options['oe_separator_type'];
		}

		echo '<div id="countdown-' . $uid . '" class="oe-countdown oe-countdown-' . $options['oe_timer_type'] . '-timer' . $sep . '"></div>';

		ob_start();

		$this->getCountdownScript( $options, $uid );

		$js = ob_get_clean();

		if ( isset( $_GET['oxygen_iframe'] ) || defined( 'OXY_ELEMENTS_API_AJAX' ) ) {
			$this->oe_enqueue_scripts();

			if ( 'evergreen' === $options['oe_timer_type'] ) {
				wp_enqueue_script( 'oucd-cookie-script' ); }
			$this->El->builderInlineJS( $js );
		} else {

			add_action( 'wp_footer', array( $this, 'oe_enqueue_scripts' ) );

			$this->countdown_js_code[] = $js;
			$this->El->footerJS( join( '', $this->countdown_js_code ) );
		}
	}

	public function oe_timezones( $selected_zone ) {
		$continents = array( 'Africa', 'America', 'Antarctica', 'Arctic', 'Asia', 'Atlantic', 'Australia', 'Europe', 'Indian', 'Pacific' );

		$zonen = array();
		foreach ( timezone_identifiers_list() as $zone ) {
			$zone = explode( '/', $zone );
			if ( ! in_array( $zone[0], $continents ) ) {
				continue;
			}

			$exists    = array(
				0 => ( isset( $zone[0] ) && $zone[0] ),
				1 => ( isset( $zone[1] ) && $zone[1] ),
				2 => ( isset( $zone[2] ) && $zone[2] ),
			);
			$exists[3] = ( $exists[0] && 'Etc' !== $zone[0] );
			$exists[4] = ( $exists[1] && $exists[3] );
			$exists[5] = ( $exists[2] && $exists[3] );

			$zonen[] = array(
				'continent'   => ( $exists[0] ? $zone[0] : '' ),
				'city'        => ( $exists[1] ? $zone[1] : '' ),
				'subcity'     => ( $exists[2] ? $zone[2] : '' ),
				't_continent' => ( $exists[3] ? translate( str_replace( '_', ' ', $zone[0] ), 'continents-cities' ) : '' ), // @codingStandardsIgnoreLine
				't_city'      => ( $exists[4] ? translate( str_replace( '_', ' ', $zone[1] ), 'continents-cities' ) : '' ), // @codingStandardsIgnoreLine
				't_subcity'   => ( $exists[5] ? translate( str_replace( '_', ' ', $zone[2] ), 'continents-cities' ) : '' ), // @codingStandardsIgnoreLine
			);
		}
		usort( $zonen, '_wp_timezone_choice_usort_callback' );

		$structure = array();

		if ( empty( $selected_zone ) ) {
			$structure[] = '<option selected="selected" value="">' . __( 'Select a city', 'oxy-extended' ) . '</option>';
		}

		foreach ( $zonen as $key => $zone ) {
			// Build value in an array to join later
			$value = array( $zone['continent'] );

			if ( empty( $zone['city'] ) ) {
				// It's at the continent level (generally won't happen)
				$display = $zone['t_continent'];
			} else {
				// It's inside a continent group

				// Continent optgroup
				if ( ! isset( $zonen[ $key - 1 ] ) || $zonen[ $key - 1 ]['continent'] !== $zone['continent'] ) {
					$label       = $zone['t_continent'];
					$structure[] = '<optgroup label="' . esc_attr( $label ) . '">';
				}

				// Add the city to the value
				$value[] = $zone['city'];

				$display = $zone['t_city'];
				if ( ! empty( $zone['subcity'] ) ) {
					// Add the subcity to the value
					$value[]  = $zone['subcity'];
					$display .= ' - ' . $zone['t_subcity'];
				}
			}

			// Build the value
			$value    = join( '/', $value );
			$selected = '';
			if ( $value === $selected_zone ) {
				$selected = 'selected="selected" ';
			}
			$structure[] = '<option ' . $selected . 'value="' . esc_attr( $value ) . '">' . esc_html( $display ) . '</option>';

			// Close continent optgroup
			if ( ! empty( $zone['city'] ) && ( ! isset( $zonen[ $key + 1 ] ) || ( isset( $zonen[ $key + 1 ] ) && $zonen[ $key + 1 ]['continent'] !== $zone['continent'] ) ) ) {
				$structure[] = '</optgroup>';
			}
		}

		// Do UTC
		$structure[] = '<optgroup label="' . esc_attr__( 'UTC', 'oxy-extended' ) . '">';
		$selected    = '';
		if ( 'UTC' === $selected_zone ) {
			$selected = 'selected="selected" ';
		}
		$structure[] = '<option ' . $selected . 'value="' . esc_attr__( 'UTC', 'oxy-extended' ) . '">' . __( 'UTC', 'oxy-extended' ) . '</option>';
		$structure[] = '</optgroup>';

		return join( "\n", $structure );
	}

	public function init() {
		$this->El->useAJAXControls();
		if ( isset( $_GET['oxygen_iframe'] ) ) {
			add_action( 'wp_footer', array( $this, 'oe_enqueue_scripts' ) );
		}
	}

	public function oe_enqueue_scripts() {
		wp_enqueue_script( 'oe-jquery-plugin' );
		wp_enqueue_script( 'oe-frontend-countdown' );
		wp_enqueue_script( 'oe-jquery-cookie' );
		wp_enqueue_script( 'oe-countdown' );
	}

	public function customCSS( $original, $selector ) {
		$css = '';

		if ( ! $this->css_added ) {
			$this->css_added = true;
			$css .= file_get_contents( __DIR__ . '/' . basename( __FILE__, '.php' ) . '.css' );
		}

		$prefix = $this->El->get_tag();

		return $css;
	}
}

new OECountdown();
