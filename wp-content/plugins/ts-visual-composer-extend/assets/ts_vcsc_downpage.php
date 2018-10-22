<?php
	global $VISUAL_COMPOSER_EXTENSIONS;
	
	// Mobile Detector
	// ---------------
	$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_MobileDetectorInit();
	
	// Setting Control Elements
	// ------------------------
	function TS_VCSC_CodeStarButton_Settings_Field($settings, $value) {
		$param_name     = isset($settings['param_name'])    ? $settings['param_name']   : '';
		$type           = isset($settings['type'])          ? $settings['type']         : '';
		$on            	= isset($settings['on'])            ? $settings['on']           : __( "Yes", "ts_visual_composer_extend" );
		$off            = isset($settings['off'])           ? $settings['off']          : __( "No", "ts_visual_composer_extend" );
		$label			= isset($settings['label'])			? $settings['label']		: '';
		$order			= isset($settings['order'])			? $settings['order']		: '1';
		// Global Settings
		$suffix         = isset($settings['suffix'])        ? $settings['suffix']       : '';
		$class          = isset($settings['class'])         ? $settings['class']        : '';
		$output         = '';
		// Final Output                
		$output .= '<div class="ts-switch-button ts-codestar-field-switcher" data-value="' . $value . '">';
			$output .= '<div class="ts-codestar-fieldset">';
				$output .= '<label class="ts-codestar-label">';
					$output .= '<input id="' . $param_name . '" data-order="' . $order . '" value="' . $value . '" class="ts-codestar-checkbox ' . $param_name . '" name="' . $param_name . '" type="checkbox" ' . ($value == 1 ? 'checked="checked"' : '') . '>';
					$output .= '<em data-on="' . $on . '" data-off="' . $off . '"></em>';
					$output .= '<span></span>';
				$output .= '</label>';
			$output .= '</div>';
		$output .= '</div>';
		$output .= '<label class="labelToggleBox" for="' . $param_name . '">' . $label . '</label>';
		return $output;
	}
	function TS_VCSC_DateTimePicker_MinuteInterval($minute) {
		$minutes_full		= array('0', 0, '00');
		$minutes_half		= array('30', 30);
		$minutes_quarter	= array('15', 15, '45', 45);
		$minutes_dozens		= array('5', 5, '10', 10, '20', 20, '25', 25, '35', 35, '40', 40, '50', 50, '55', 55);
		$minutes_intval		= 60;
		if ($minute == '') {
			$minutes_intval	= 60;
		} else if ((in_array($minute, $minutes_full)) && (!in_array($minute, $minutes_half))) {
			$minutes_intval	= 60;
		} else if (in_array($minute, $minutes_half)) {
			$minutes_intval	= 30;
		} else if (in_array($minute, $minutes_quarter)) {
			$minutes_intval	= 15;
		} else if (in_array($minute, $minutes_dozens)) {
			$minutes_intval	= 5;
		} else {
			$minutes_intval	= 1;
		}
		return $minutes_intval;
	}
	function TS_VCSC_DateTimePicker_Setting_Field($settings, $value) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		$param_name     	= isset($settings['param_name']) ? $settings['param_name'] : '';
		$type           	= isset($settings['type']) ? $settings['type'] : '';
		$period         	= isset($settings['period']) ? $settings['period'] : '';
		$range				= isset($settings['range']) ? $settings['range'] : 'false';
		$text_start			= isset($settings['text_start']) ? $settings['text_start'] : __('Start:', 'ts_visual_composer_extend');
		$text_end			= isset($settings['text_end']) ? $settings['text_end'] : __('End:', 'ts_visual_composer_extend');
		$spacing			= isset($settings['spacing']) ? $settings['spacing'] : 0;
		$url            	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginPath;
		$randomizer			= rand(100000, 999999);
		// Other Variables
		$minutes_full		= array('0', 0, '00');
		$minutes_half		= array('30', 30);
		$minutes_quarter	= array('15', 15, '45', 45);
		$minutes_dozens		= array('5', 5, '10', 10, '20', 20, '25', 25, '35', 35, '40', 40, '50', 50, '55', 55);
		$minutes_start		= 60;
		$minutes_end		= 60;
		$minutes_interval	= 60;
		$output 			= '';
		if ($range == "false") {
			if ($period == "datetime") {
				$time_start 		= strtotime($value);
				$time_start			= intval(date('i', $time_start));
				// Check Start Value
				if ($value == "") {
					$minutes_start	= 60;
				} else {
					$minutes_start	= TS_VCSC_DateTimePicker_MinuteInterval($time_start);
				}
				$minutes_interval	= $minutes_start;
				$output .= '<div id="ts-datetime-picker-element-' . $randomizer . '" class="ts-datetime-picker-element clearFixMe ts-xdsoft-datetimepicker-wrapper ts-settings-parameter-gradient-grey ts-singleselect-holder">';
					$output .= '<input name="' . $param_name . '" id="' . $param_name . '" class="ts-datetimepicker-value wpb_vc_param_value ' . $param_name . ' ' . $type . '" type="hidden" value="' . $value . '"/>';
					$output .= '<label class="ts-datetimepicker-label" for="ts-datetimepicker-minutes-' . $randomizer . '">' . __( "Select the interval for the time selector:", "ts_visual_composer_extend" ) . '</label>';
					$output .= '<select id="ts-datetimepicker-minutes-' . $randomizer . '" class="ts-datetimepicker-minutes" data-identifier="' . $randomizer . '" style="width: 330px;">';
						$output .= '<option value="60" ' . selected('60', 	$minutes_interval, false) . '>' . __('60 Minutes', 'ts_visual_composer_extend') . '</option>';
						$output .= '<option value="30" ' . selected('30', 	$minutes_interval, false) . '>' . __('30 Minutes', 'ts_visual_composer_extend') . '</option>';
						$output .= '<option value="15" ' . selected('15', 	$minutes_interval, false) . '>' . __('15 Minutes', 'ts_visual_composer_extend') . '</option>';
						$output .= '<option value="10" ' . selected('10', 	$minutes_interval, false) . '>' . __('10 Minutes', 'ts_visual_composer_extend') . '</option>';
						$output .= '<option value="5" ' . selected('5', 	$minutes_interval, false) . '>' . __('5 Minutes', 'ts_visual_composer_extend') . '</option>';
						$output .= '<option value="1" ' . selected('1', 	$minutes_interval, false) . '>' . __('1 Minute', 'ts_visual_composer_extend') . '</option>';
					$output .= '</select>';
					$output .= '<input class="ts-datetimepicker ts-datetimepicker-single" type="text" placeholder="" value="' . $value . '"/>';
				$output .= '</div>';
			} else if ($period == "date") {
				$output .= '<div id="ts-dateonly-picker-element-' . $randomizer . '" class="ts-dateonly-picker-element clearFixMe ts-xdsoft-datetimepicker-wrapper ts-settings-parameter-gradient-grey">';
					$output .= '<input name="' . $param_name . '" id="' . $param_name . '" class="ts-datepicker-value wpb_vc_param_value ' . $param_name . ' ' . $type . '" type="hidden" value="' . $value . '"/>';
					$output .= '<input class="ts-datepicker ts-datepicker-single" type="text" placeholder="" value="' . $value . '"/>';
				$output .= '</div>';
			} else if ($period == "time") {
				// Time Picker via Slider
				if ($value != '') {
					$convert 		= date_parse($value);
					$minutes		= $convert['hour'] * 60 + $convert['minute'];
				} else {
					$minutes		= 1;
				}
				$output .= '<div id="ts-nouislider-time-slider-' . $randomizer . '" class="ts-nouislider-time-slider clearFixMe ts-settings-parameter-gradient-grey">';
					$output .= '<div id="ts-nouislider-time-output-' . $randomizer . '" class="ts-nouislider-time-output" data-controls="ts-nouislider-time-controls-' . $randomizer . '">';
						$output .= '<div id="ts-nouislider-time-human-' . $randomizer . '" class="ts-nouislider-time-human">';	
							$output .= '<span class="ts-nouislider-time-final">' . $value . '</span>';							
						$output .= '</div>';
					$output .= '</div>';
					$output .= '<div id="ts-nouislider-time-controls-' . $randomizer . '" class="ts-nouislider-time-controls" data-output="ts-nouislider-time-output-' . $randomizer . '">';
						$output .= '<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="' . $param_name . '"  class="ts-nouislider-serial nouislider-time-selector nouislider-input-composer wpb_vc_param_value ' . $param_name . ' ' . $type . '" type="hidden" value="' . $value . '" style="display: none;"/>';
						$output .= '<span class="ts-nouislider-time-faster-down dashicons-controls-back" style="position: relative; float: left; display: inline-block; font-size: 30px; top: 30px; cursor: pointer; margin: 0;"></span>';	
						$output .= '<span class="ts-nouislider-time-lower-down dashicons-arrow-left" style="position: relative; float: left; display: inline-block; font-size: 50px; top: 20px; cursor: pointer; margin: 0;"></span>';								
						$output .= '<div id="ts-nouislider-time-element-' . $randomizer . '" class="ts-nouislider-time ts-nouislider-time-element" data-value="' . $minutes . '" data-unit="" data-min="1" data-max="1440" data-decimals="0" data-step="1" style="width: 400px; float: left; margin: 10px auto;"></div>';
						$output .= '<span class="ts-nouislider-time-lower-up dashicons-arrow-right" style="position: relative; float: left; display: inline-block; font-size: 50px; top: 20px; cursor: pointer; margin: 0;"></span>';	
						$output .= '<span class="ts-nouislider-time-faster-up dashicons-controls-forward" style="position: relative; float: left; display: inline-block; font-size: 30px; top: 30px; cursor: pointer; margin: 0;"></span>';
					$output .= '</div>';
				$output .= '</div>';					
			}
		} else {
			$value_array			= explode("|", $value);
			$value_start			= $value_array[0];
			$value_end				= $value_array[1];
			if ($period == "datetime") {
				$time_start 		= strtotime($value_start);
				$time_end 			= strtotime($value_end);
				$time_start			= intval(date('i', $time_start));
				$time_end			= intval(date('i', $time_end));
				// Check Start Value
				if ($value_start == "") {
					$minutes_start	= 60;
				} else {
					$minutes_start	= TS_VCSC_DateTimePicker_MinuteInterval($time_start);
				}
				// Check End Value
				if ($value_end == "") {
					$minutes_end	= 60;
				} else {
					$minutes_end	= TS_VCSC_DateTimePicker_MinuteInterval($time_end);
				}
				// Determine Final Interval
				if ($minutes_start > $minutes_end) {
					$minutes_interval	= $minutes_end;
				} else {
					$minutes_interval	= $minutes_start;
				}
				// Create Output
				$output .= '<div id="ts-datetime-range-element-' . $randomizer . '" class="ts-datetime-range-element clearFixMe ts-xdsoft-datetimepicker-wrapper ts-settings-parameter-gradient-grey ts-singleselect-holder" data-step="' . $minutes_interval . '">';
					$output .= '<input name="' . $param_name . '" id="' . $param_name . '" class="ts-datetimerange-value wpb_vc_param_value ' . $param_name . ' ' . $type . '" type="hidden" value="' . $value . '"/>';
					$output .= '<div id="ts-datetime-range-human-' . $randomizer . '" class="ts-datetime-range-human">';
						$output .= '<div class="ts-datetimerange-output">';
							$output .= '<span class="ts-datetimerange-output-start">' . ($value_start != "" ? $value_start : "...") . '</span> - <span class="ts-datetimerange-output-end">' . ($value_end != "" ? $value_end : "...") . '</span>';
						$output .= '</div>';
					$output .= '</div>';
					$output .= '<label class="ts-datetimerange-label" for="ts-datetimerange-minutes-' . $randomizer . '">' . __( "Select the intervals for the time selectors:", "ts_visual_composer_extend" ) . '</label>';
					$output .= '<select id="ts-datetimerange-minutes-' . $randomizer . '" class="ts-datetimerange-minutes" data-identifier="' . $randomizer . '" style="width: 330px;">';
						$output .= '<option value="60" ' . selected('60', 	$minutes_interval) . '>' . __('60 Minutes', 'ts_visual_composer_extend') . '</option>';
						$output .= '<option value="30" ' . selected('30', 	$minutes_interval) . '>' . __('30 Minutes', 'ts_visual_composer_extend') . '</option>';
						$output .= '<option value="15" ' . selected('15', 	$minutes_interval) . '>' . __('15 Minutes', 'ts_visual_composer_extend') . '</option>';
						$output .= '<option value="10" ' . selected('10', 	$minutes_interval) . '>' . __('10 Minutes', 'ts_visual_composer_extend') . '</option>';
						$output .= '<option value="5" ' . selected('5', 	$minutes_interval) . '>' . __('5 Minutes', 'ts_visual_composer_extend') . '</option>';
						//$output .= '<option value="1" ' . selected('1', 	$minutes_interval) . '>' . __('1 Minute', 'ts_visual_composer_extend') . '</option>';
					$output .= '</select>';
					$output .= '<div class="ts-datetimerange-picker ts-datetimerange-picker-start">';
						$output .= '<span class="ts-datetimerange-header">' . $text_start . '</span>';
						$output .= '<input id="ts-datetimerange-start-' . $randomizer . '" class="ts-datetimerange-start" data-time="' . date('h:i A', strtotime($value_start)) . '" data-date="' . date('m/d/Y', strtotime($value_start)) . '" type="text" placeholder="" value="' . $value_start . '"/>';
					$output .= '</div>';
					$output .= '<div class="ts-datetimerange-picker ts-datetimerange-picker-end">';
						$output .= '<span class="ts-datetimerange-header">' . $text_end . '</span>';
						$output .= '<input id="ts-datetimerange-end-' . $randomizer . '" class="ts-datetimerange-end" data-time="' . date('h:i A', strtotime($value_end)) . '" data-date="' . date('m/d/Y', strtotime($value_end)) . '" type="text" placeholder="" value="' . $value_end . '"/>';
					$output .= '</div>';
				$output .= '</div>';
			} else if ($period == "date") {
				$output .= '<div id="ts-dateonly-range-element-' . $randomizer . '" class="ts-dateonly-range-element clearFixMe ts-xdsoft-datetimepicker-wrapper ts-settings-parameter-gradient-grey">';
					$output .= '<input name="' . $param_name . '" id="' . $param_name . '" class="ts-dateonlyrange-value wpb_vc_param_value ' . $param_name . ' ' . $type . '" type="hidden" value="' . $value . '"/>';
					$output .= '<div id="ts-dateonly-range-human-' . $randomizer . '" class="ts-dateonly-range-human">';
						$output .= '<div class="ts-dateonlyrange-output"><span class="ts-dateonlyrange-output-start">' . ($value_start != "" ? $value_start : "...") . '</span> - <span class="ts-dateonlyrange-output-end">' . ($value_end != "" ? $value_end : "...") . '</span></div>';
					$output .= '</div>';
					$output .= '<div class="ts-dateonlyrange-picker-center">';
						$output .= '<div class="ts-dateonlyrange-picker ts-dateonlyrange-picker-start">';
							$output .= '<span class="ts-dateonlyrange-header">' . $text_start . '</span>';
							$output .= '<input class="ts-dateonlyrange-start" type="text" placeholder="" value="' . $value_start . '"/>';
						$output .= '</div>';
						$output .= '<div class="ts-dateonlyrange-picker ts-dateonlyrange-picker-end">';
							$output .= '<span class="ts-dateonlyrange-header">' . $text_end . '</span>';
							$output .= '<input class="ts-dateonlyrange-end" type="text" placeholder="" value="' . $value_end . '"/>';
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
			}
		}
		return $output;
	}
	function TS_VCSC_NoUiSlider_Settings_Field($settings, $value) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		$param_name     		= isset($settings['param_name']) ? $settings['param_name'] : '';
		$type           		= isset($settings['type']) ? $settings['type'] : '';
		$min            		= isset($settings['min']) ? $settings['min'] : '';
		$max            		= isset($settings['max']) ? $settings['max'] : '';
		$step           		= isset($settings['step']) ? $settings['step'] : '';
		$unit           		= isset($settings['unit']) ? $settings['unit'] : '';
		$decimals				= isset($settings['decimals']) ? $settings['decimals'] : 0;
		// Single Input Only
		$pips					= isset($settings['pips']) ? $settings['pips'] : "true";
		$tooltip				= isset($settings['tooltip']) ? $settings['tooltip'] : "false";
		// Range Additions
		$range					= isset($settings['range']) ? $settings['range'] : "false";
		$start					= isset($settings['start']) ? $settings['start'] : $min;
		$end					= isset($settings['end']) ? $settings['end'] : $max;				
		// Other Settings
		$suffix         		= isset($settings['suffix']) ? $settings['suffix'] : '';
		$class          		= isset($settings['class']) ? $settings['class'] : '';				
		$url            		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginPath;
		$output         		= '';
		$randomizer             = mt_rand(999999, 9999999);
		$containerclass			= '';
		if ($range == "false") {
			if ($tooltip == "true") {
				$containerclass	.= " ts-nouislider-input-slider-tooltip";
			}
			if ($pips == "true") {
				$containerclass	.= " ts-nouislider-input-slider-pips";
			}
			if (($tooltip == "false") && ($pips == "false")) {
				$containerclass	= "ts-nouislider-input-slider-basic";
			}
			$output .= '<div id="ts-nouislider-input-slider-wrapper' . $randomizer . '" class="ts-nouislider-input-slider-wrapper clearFixMe ts-settings-parameter-gradient-grey ' . $containerclass . '" style="height: 100px;">';
				$output .= '<div id="ts-nouislider-input-slider-' . $randomizer . '" class="ts-nouislider-input-slider">';
					$output .= '<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px; background: #f5f5f5; color: #666666;" name="' . $param_name . '"  class="ts-nouislider-serial nouislider-input-selector nouislider-input-composer ' . $param_name . ' ' . $type . '" type="text" min="' . $min . '" max="' . $max . '" step="' . $step . '" value="' . $value . '"/>';
					$output .= '<span style="float: left; margin-right: 20px; margin-top: 10px; min-width: 10px;" class="unit">' . $unit . '</span>';
					$output .= '<span class="ts-nouislider-input-down dashicons-arrow-left" style="position: relative; float: left; display: inline-block; font-size: 30px; top: 5px; cursor: pointer; margin: 0;"></span>';
					$output .= '<div id="ts-nouislider-input-element-' . $randomizer . '" class="ts-nouislider-input ts-nouislider-input-element" data-pips="' . $pips . '" data-tooltip="' . $tooltip . '" data-value="' . $value . '" data-min="' . $min . '" data-max="' . $max . '" data-decimals="' . $decimals . '" data-step="' . $step . '" data-unit="' . $unit . '" style="width: 320px; float: left; margin-top: 10px;"></div>';
					$output .= '<span class="ts-nouislider-input-up dashicons-arrow-right" style="position: relative; float: left; display: inline-block; font-size: 30px; top: 5px; cursor: pointer; margin: 0 20px 0 0;"></span>';					
				$output .= '</div>';
			$output .= '</div>';
		} else if ($range == "true") {
			$output .= '<div id="ts-nouislider-range-slider-wrapper-' . $randomizer . '" class="ts-nouislider-range-slider-wrapper clearFixMe ts-settings-parameter-gradient-grey" style="height: 150px;">';
				$output .= '<div id="ts-nouislider-range-slider-' . $randomizer . '" class="ts-nouislider-range-slider">';
					$output .= '<div id="ts-nouislider-range-output-' . $randomizer . '" class="ts-nouislider-range-output" data-controls="ts-nouislider-range-controls-' . $randomizer . '">';
						$output .= '<div id="ts-nouislider-range-human-' . $randomizer . '" class="ts-nouislider-range-human">';	
							$output .= '<span class="ts-nouislider-range-start"></span> - <span class="ts-nouislider-range-end"></span>';							
						$output .= '</div>';
					$output .= '</div>';
					$output .= '<div id="ts-nouislider-range-controls-' . $randomizer . '" class="ts-nouislider-range-controls" data-output="ts-nouislider-range-output-' . $randomizer . '">';
						$output .= '<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="' . $param_name . '"  class="ts-nouislider-serial nouislider-range-selector nouislider-input-composer ' . $param_name . ' ' . $type . '" type="hidden" value="' . $value . '" style="display: none;"/>';
						$output .= '<span class="ts-nouislider-range-lower-down dashicons-arrow-left" style="position: relative; float: left; display: inline-block; font-size: 30px; top: 30px; cursor: pointer; margin: 0;"></span>';
						$output .= '<span class="ts-nouislider-range-lower-up dashicons-arrow-right" style="position: relative; float: left; display: inline-block; font-size: 30px; top: 30px; cursor: pointer; margin: 0 20px 0 0;"></span>';						
						$output .= '<div id="ts-nouislider-range-element-' . $randomizer . '" class="ts-nouislider-range ts-nouislider-range-element" data-value="' . $value . '" data-start="' . $start . '" data-end="' . $end . '" data-min="' . $min . '" data-max="' . $max . '" data-decimals="' . $decimals . '" data-step="' . $step . '" style="width: 400px; float: left; margin: 10px auto;"></div>';
						$output .= '<span class="ts-nouislider-range-upper-down dashicons-arrow-left" style="position: relative; float: none; display: inline-block; font-size: 30px; top: 30px; cursor: pointer; margin: 0 0 0 20px;"></span>';
						$output .= '<span class="ts-nouislider-range-upper-up dashicons-arrow-right" style="position: relative; float: none; display: inline-block; font-size: 30px; top: 30px; cursor: pointer; margin: 0;"></span>';
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
		}
		return $output;
	}
	function TS_VCSC_UserRoles_Settings_Field($settings, $value) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		global $wp_roles;                
		if (!isset($wp_roles)) {
			$wp_roles       = new WP_Roles();
		}
		$param_name     	= isset($settings['param_name']) ? $settings['param_name'] : '';
		$type           	= isset($settings['type']) ? $settings['type'] : '';
		$url            	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginPath;
		$output         	= '';
		$randomizer			= rand(100000, 999999);
		$value_arr 			= $value;
		if (!is_array($value_arr)) {
			$value_arr      = array_map('trim', explode(',', $value_arr));
		}
		$output .= '<div id="ts-userroles-selector-holder-' . $randomizer . '" class="ts-userroles-selector-holder ts-settings-parameter-gradient-grey ts-singleselect-holder">';
			$output .= '<textarea name="' . $param_name . '" id="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" style="display: none;">' . $value . '</textarea >';
			$output .= '<select multiple="multiple" name="' . $param_name . '_multiple" id="' . $param_name . '_multiple" data-holder="' . $param_name . '" class="ts-multiple-options-selector wpb-input wpb-select dropdown ' . $param_name . '_multiple" value=" ' . $value . '" style="margin-bottom: 20px;" data-selectable="' . __( "Available User Roles:", "ts_visual_composer_extend" ) . '" data-selection="' . __( "Allowed User Roles:", "ts_visual_composer_extend" ) . '">';
				foreach ($wp_roles->roles as $key => $value) {
					$output .= '<option id="" class="" name="" data-id="" data-author="" value="' . $key . '" ' . selected(in_array($key, $value_arr), true, false) . '>' . $value['name'] . '</option>';
				}
			$output .= '</select>';
			$output .= '<span class="ts-userroles-selector-message">' . __( "Click on a name in 'Available User Roles' to allow role to view normal website; click on a name in 'Allowed User Roles' to remove from listing.", "ts_visual_composer_extend" ) . '</span>';
		$output .= '</div>';
		return $output;
	}
	function TS_VCSC_CustomPost_Settings_Field($settings, $value) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		$param_name     	= isset($settings['param_name']) ? $settings['param_name'] : '';
		$posttype			= isset($settings['posttype']) ? $settings['posttype'] : '';
		$posttaxonomy		= isset($settings['posttaxonomy']) ? $settings['posttaxonomy'] : '';
		$postsingle			= isset($settings['postsingle']) ? $settings['postsingle'] : '';
		$postplural			= isset($settings['postplural']) ? $settings['postplural'] : '';
		$postclass			= isset($settings['postclass']) ? $settings['postclass'] : '';
		$type           	= isset($settings['type']) ? $settings['type'] : '';
		$holder           	= isset($settings['holder']) ? $settings['holder'] : '';
		$link           	= isset($settings['link']) ? $settings['link'] : 'false';
		$order           	= isset($settings['order']) ? $settings['order'] : '0';
		$error           	= isset($settings['error']) ? $settings['error'] : '';
		$maintenance		= isset($settings['maintenance']) ? $settings['maintenance'] : 'false';
		$dependency         = isset($settings['dependency']) ? $settings['dependency'] : '';
		$url            	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginPath;
		$randomizer         = mt_rand(999999, 9999999);
		$output         	= '';
		$posts_fields 		= array();
		$categories			= '';
		$category_fields 	= array();
		$categories_count	= 0;
		$terms_slugs 		= array();
		$value_arr 			= $value;
		if (!is_array($value_arr)) {
			$value_arr = array_map('trim', explode(',', $value_arr));
		}
		if (!empty($settings['posttype']) ) {
			$args = array(
				'no_found_rows' 		=> 1,
				'ignore_sticky_posts' 	=> 1,
				'posts_per_page' 		=> -1,
				'post_type' 			=> $posttype,
				'post_status' 			=> 'publish',
				'orderby' 				=> 'title',
				'order' 				=> 'ASC',
			);
			$custompost_nocategory			= 0;
			$custompost_query = new WP_Query($args);
			if ($custompost_query->have_posts()) {
				foreach($custompost_query->posts as $p) {
					$categories = TS_VCSC_GetTheCategoryByTax($p->ID, $posttaxonomy);
					if ($categories && !is_wp_error($categories)) {
						$category_slugs_arr = array();
						foreach ($categories as $category) {
							$category_slugs_arr[] = $category->slug;
							$category_data = array(
								'slug'		=> $category->slug,
								'name'		=> $category->cat_name,
								'count'		=> $category->count,
							);
							$category_fields[] = $category_data;
						}
						$categories_slug_str = join(",", $category_slugs_arr);
					} else {
						$custompost_nocategory++;
						$categories_slug_str = '';
					};
					$posts_fields[] = sprintf(
						'<option id="%s" class="%s" name="%s" value="%s" data-filter="false" data-id="%s" data-categories="%s" %s>%s (ID: %s)</option>',
						$settings['param_name'] . '-' . $p->ID,
						$settings['param_name'] . ' ' . $type,
						$settings['param_name'] . '-' . $p->ID,
						$p->ID,
						$p->post_title,
						$categories_slug_str,
						selected(in_array($p->ID, $value_arr), true, false),
						$p->post_title,
						$p->ID
					);
				}
			}
			wp_reset_postdata();
		}
		$category_fields    = array_map("unserialize", array_unique(array_map("serialize", $category_fields)));
		$output .= '<div id="ts-custompost-selector-wrapper-' . $randomizer . '" class="ts-custompost-selector-wrapper ts-singleselect-holder">';
			$output .= '<div class="ts-custompost-selector-parent" data-selectable="' . __( "Available Categories:", "ts_visual_composer_extend" ) . '" data-selection="' . __( "Filtered By:", "ts_visual_composer_extend" ) . '">';                  
				$output .= '<label for="' . $param_name . '" class="ts-custompost-selector-label" style="display: inline-block; margin-left: 0px; width: 250px;">' . $postsingle . ':</label>';
				$output .= '<select name="' . $param_name . '" id="' . $param_name . '" data-validation-engine="validate[condRequired[' . $dependency . ']]" data-order="' . $order . '" data-error="' . $error . '" class="ts-' . $postclass . '-selector ts-custompost-selector wpb-input wpb-select dropdown wpb_vc_param_value ' . $param_name . ' ' . $type . '" value=" ' . $value . '" style="width: 400px; margin: 0;">';
					$output .= '<option id="" class="placeholder" name="" value="" data-filter="false" data-id="" data-categories="">' . __( "Select Downpage", "ts_visual_composer_extend" ) . '</option>';
					$output .= implode( $posts_fields );
					if ($maintenance == "true") {
						$output .= '<option id="' . $settings['param_name'] . '-' . 'maintenance" class="' . $settings['param_name'] . '-' . 'maintenance" name="' . $settings['param_name'] . '-' . 'maintenance" value="maintenance" data-filter="false" data-id="maintenance" data-categories="maintenance" ' . selected(in_array("maintenance", $value_arr), true, false) . '>' . __( "Use Custom wp-content/maintenance.php File", "ts_visual_composer_extend" ) . '</option>';
					}
				$output .= '</select>';
			$output .= '</div>';
		 $output .= '</div>';
		return $output;
	}

	
	// Time Helper Functions
	// Create Array with Date/Time Values
	function TS_VCSC_DownTime_CreateTimeValueArray($ts = null){ 
		$k = array('seconds', 'minutes', 'hours', 'mday', 'wday', 'mon', 'year', 'yday', 'weekday', 'month', 0);
		return(array_combine($k, explode(":", gmdate('s:i:G:j:w:n:Y:z:l:F:U', is_null($ts) ? time() : $ts)))); 
	}	
	// Check Current Time Against Time Range
	function TS_VCSC_DownTime_DateTimeRangeAgainstCurrent($timeonly, $scope = null, $period, $current){
		$render						= "false";
		if ($scope == "full") {
			$render					= "true";
		} else if ($scope == "none") {
			$render					= "false";
		} else if (($scope == "period") || (($scope == null) && ($timeonly == true))) {
			$period 				= explode(",", $period);
			$start					= $period[0] * 5;
			$stop					= $period[1] * 5;
			$start					= date('H:i:s', mktime(0, $start, 0));
			$stop					= date('H:i:s', mktime(0, $stop, 0));
			if (($current >= $start) && ($current <= $stop)) {
				$render				= "true";
			}
		}
		return $render;
	}
	// Check for Custom maintenance.php File
	function TS_VCSC_DownTime_CheckMaintenancePHP() {
		$maintenance_file 			= WP_CONTENT_DIR . "/maintenance.php";
		if (file_exists($maintenance_file)) {
			return true;
		} else {
			return false;
		}
	}
	
	// Save / Load Parameters
	// ----------------------
	if (isset($_POST['Submit'])) {		
		echo '<div id="ts_vcsc_extend_settings_save" style="position: relative; margin: 20px auto 20px auto; width: 128px; height: 128px;">';
			echo TS_VCSC_CreatePreloaderCSS("ts-settings-panel-loader", "", 22, "false");
		echo '</div>';
		$ts_vcsc_extend_settings_downpage_custom = array(
			'active'                        			=> intval(((isset($_POST['ts_vcsc_extend_settings_downpage_active'])) ? $_POST['ts_vcsc_extend_settings_downpage_active'] : 0)),
			'timer'                     				=> trim ($_POST['ts_vcsc_extend_settings_downpage_timer']),
			'timezone'                      			=> trim ($_POST['ts_vcsc_extend_settings_downpage_timezone']),
			'dateonly'									=> trim ($_POST['ts_vcsc_extend_settings_downpage_dateonly']),
			'datetime'									=> trim ($_POST['ts_vcsc_extend_settings_downpage_datetime']),
			'timerange'									=> trim ($_POST['ts_vcsc_extend_settings_downpage_timerange']),
			'userroles'                     			=> trim ($_POST['ts_vcsc_extend_settings_downpage_userroles']),
			'override'									=> intval(((isset($_POST['ts_vcsc_extend_settings_downpage_override'])) ? $_POST['ts_vcsc_extend_settings_downpage_override'] : 0)),
			'preview'									=> trim ($_POST['ts_vcsc_extend_settings_downpage_preview']),
			'cookie'									=> trim ($_POST['ts_vcsc_extend_settings_downpage_cookie']),
			'singlepage'								=> intval(((isset($_POST['ts_vcsc_extend_settings_downpage_single'])) ?	$_POST['ts_vcsc_extend_settings_downpage_single'] : 0)),
			'alldevices'								=> trim ($_POST['ts_vcsc_extend_settings_downpage_alldevices']),
			'desktop'									=> trim ($_POST['ts_vcsc_extend_settings_downpage_desktop']),
			'tablet'									=> trim ($_POST['ts_vcsc_extend_settings_downpage_tablet']),
			'mobile'									=> trim ($_POST['ts_vcsc_extend_settings_downpage_mobile']),
			'downstatus'								=> trim ($_POST['ts_vcsc_extend_settings_downpage_status']),
		);
		update_option('ts_vcsc_extend_settings_downTimeMode', $ts_vcsc_extend_settings_downpage_custom);
		echo '<script> window.location="' . $_SERVER['REQUEST_URI'] . '"; </script> ';
		//Header('Location: '.$_SERVER['REQUEST_URI']);
		Exit();
	} else {
		// Helper Variables
		$ts_vcsc_extend_settings_downpage_maintenance	= TS_VCSC_DownTime_CheckMaintenancePHP();
		$ts_vcsc_extend_settings_downpage_local			= current_time('timestamp', 0);
		$ts_vcsc_extend_settings_downpage_date			= date("m/d/Y", $ts_vcsc_extend_settings_downpage_local);
		$ts_vcsc_extend_settings_downpage_moment		= TS_VCSC_DownTime_CreateTimeValueArray($ts_vcsc_extend_settings_downpage_local);
		$ts_vcsc_extend_settings_downpage_time			= date('h:i A', mktime($ts_vcsc_extend_settings_downpage_moment['hours'] + 1, 0, 0));
		$ts_vcsc_extend_settings_downpage_offset		= get_option('gmt_offset');
		// Setting Variables
		$ts_vcsc_extend_settings_downpage_active 		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['active'];		
		$ts_vcsc_extend_settings_downpage_single 		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['singlepage'];		
		$ts_vcsc_extend_settings_downpage_alldevices 	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['alldevices'];
		$ts_vcsc_extend_settings_downpage_desktop 		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['desktop'];
		$ts_vcsc_extend_settings_downpage_tablet 		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['tablet'];
		$ts_vcsc_extend_settings_downpage_mobile 		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['mobile'];		
		$ts_vcsc_extend_settings_downpage_users 		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['userroles'];
		$ts_vcsc_extend_settings_downpage_timer 		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['timer'];
		$ts_vcsc_extend_settings_downpage_timezone 		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['timezone'];
		$ts_vcsc_extend_settings_downpage_override 		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['override'];		
		$ts_vcsc_extend_settings_downpage_preview 		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['preview'];
		$ts_vcsc_extend_settings_downpage_cookie 		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['cookie'];
		$ts_vcsc_extend_settings_downpage_status		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['downstatus'];
		if ($ts_vcsc_extend_settings_downpage_timezone == "") {
			$ts_vcsc_extend_settings_downpage_timezone	= $ts_vcsc_extend_settings_downpage_offset;
		}
		$ts_vcsc_extend_settings_downpage_dateonly 		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['dateonly'];
		if ($ts_vcsc_extend_settings_downpage_dateonly == "") {
			$ts_vcsc_extend_settings_downpage_dateonly	= $ts_vcsc_extend_settings_downpage_date;
		}
		$ts_vcsc_extend_settings_downpage_datetime 		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['datetime'];
		if ($ts_vcsc_extend_settings_downpage_datetime == "") {
			$ts_vcsc_extend_settings_downpage_datetime	= $ts_vcsc_extend_settings_downpage_date . " " . $ts_vcsc_extend_settings_downpage_time;
		}
		$ts_vcsc_extend_settings_downpage_timerange		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['timerange'];		
		$ts_vcsc_extend_settings_downpage_pages 		= wp_count_posts('ts_downtime');
		$ts_vcsc_extend_settings_downpage_pages 		= $ts_vcsc_extend_settings_downpage_pages->publish;
		if (TS_VCSC_DownTime_CheckMaintenancePHP() == true) {
			$ts_vcsc_extend_settings_downpage_pages		= $ts_vcsc_extend_settings_downpage_pages + 1;
		}
		if ($ts_vcsc_extend_settings_downpage_active == 1) {
			$MenuItemDisplay							= "display: block;";
		} else {
			$MenuItemDisplay							= "display: none;";
		}
		$CurrentSiteUrl									= site_url();
		$DownPageAddNewUrl 								= admin_url(). "post-new.php?post_type=ts_downtime";
	}
?>
<div id="ts_vcsc_extend_errors" style="display: none;">
	<div class="ts-vcsc-section-main">
		<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-hammer ts-vcsc-section-title-icon"></i><span class="ts-vcsc-section-title-header"></span></div>
		<div class="ts-vcsc-section-content"></div>
	</div>
</div>
<form id="ts-vcsc-downpage-check-wrap" data-type="downtime" class="ts-vcsc-downpage-check-wrap" name="ts-vcsc-downpage-check-wrap" autocomplete="off" style="margin-top: 25px; width: 100%;" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<span id="ts-vcsc-downpage-check-true" style="display: none !important; margin-bottom: 20px;">
		<input type="text" style="width: 20%;" id="ts_vcsc_extend_settings_true" name="ts_vcsc_extend_settings_true" value="0" size="100">
		<input type="text" style="width: 20%;" id="ts_vcsc_extend_settings_count" name="ts_vcsc_extend_settings_count" value="0" size="100">
	</span>
	<div class="wrapper ts-vcsc-settings-group-container">		
		<div class="ts-vcsc-settings-group-header">
			<div class="display_header">
				<h2><span class="dashicons dashicons-clock"></span>Composium - WP Bakery Page Builder Extensions v<?php echo TS_VCSC_GetPluginVersion(); ?> ... Downtime Manager (BETA)</h2>
			</div>
			<div class="clear"></div>
		</div>
		<div class="ts-vcsc-settings-group-topbar ts-vcsc-settings-group-buttonbar">
			<a href="javascript:void(0);" id="ts-vcsc-settings-group-toggle" class="ts-vcsc-settings-group-toggle" data-toggle-status="false">Expand</a>
			<div class="ts-vcsc-settings-group-actionbar">
				<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder ts-advanced-link-tooltip-right ts-advanced-link-tooltip-bottom">
					<span class="ts-advanced-link-tooltip-content"><?php _e("Click here to save your downtime manager settings.", "ts_visual_composer_extend"); ?></span>
					<button type="submit" name="Submit" id="ts_vcsc_extend_settings_submit_1" class="ts-advanced-link-button-main ts-advanced-link-button-blue ts-advanced-link-button-save" style="margin: 0;">
						<?php echo __("Save Settings", "ts_visual_composer_extend"); ?>
					</button>
				</div>				
			</div>
			<div class="clear"></div>
		</div>	
		<div id="v-nav" class="ts-vcsc-settings-group-tabs">
			<ul id="v-nav-main" data-type="settings">
				<li id="link-ts-settings-logo" class="first" style="border-bottom: 1px solid #DDD; height: 230px;">
					<img style="width: auto; height: 100%; margin: 0 auto;" src="<?php echo TS_VCSC_GetResourceURL('images/other/downtime_icon_01.png'); ?>">
				</li>
				<li id="link-ts-downtime-general" 		data-tab="ts-downtime-general" 			data-order="1"		data-name="Downtime Mode"			style="display: block;"						class="link-data current"><i class="dashicons-backup"></i>Downtime Mode<span id="errorTab1" class="errorMarker"></span></li>
				<li id="link-ts-downtime-content" 		data-tab="ts-downtime-content"			data-order="2"		data-name="Page Selection / Status"	style="<?php echo $MenuItemDisplay; ?>" 	class="link-data"><i class="dashicons-welcome-view-site"></i>Page Selection / Status<span id="errorTab2" class="errorMarker"></span></li>
				<li id="link-ts-downtime-expire" 		data-tab="ts-downtime-expire"			data-order="3"		data-name="Expiration Date / Time"	style="<?php echo $MenuItemDisplay; ?>" 	class="link-data"><i class="dashicons-calendar-alt"></i>Expiration Date / Time<span id="errorTab3" class="errorMarker"></span></li>
				<li id="link-ts-downtime-userroles"		data-tab="ts-downtime-userroles"		data-order="4"		data-name="Logged In Users"			style="<?php echo $MenuItemDisplay; ?>" 	class="link-data"><i class="dashicons-businessman"></i>Logged In Users<span id="errorTab4" class="errorMarker"></span></li>
				<li id="link-ts-downtime-override"		data-tab="ts-downtime-override"			data-order="5"		data-name="Visitor Override"		style="<?php echo $MenuItemDisplay; ?>" 	class="link-data"><i class="dashicons-unlock"></i>Visitor Override<span id="errorTab5" class="errorMarker"></span></li>
			</ul>
		</div>
		<div class="ts-vcsc-settings-group-main">
			<div id="ts-downtime-general" class="tab-content">
				<div id="ts-vcsc-downpage-check-activate" class="ts-vcsc-section-main ">
					<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-backup"></i>Downtime Mode (BETA)</div>
					<div class="ts-vcsc-section-content">
						<img style="width: 100%; max-width: 600px; height: auto; margin: 0 auto;" src="<?php echo TS_VCSC_GetResourceURL('images/other/downtime_banner_01.png'); ?>">
						<div class="ts-vcsc-info-field ts-vcsc-warning" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify; font-weight: bold;">
							When you enable the sitewide downtime mode, your website will show a dedicated page of your design to all visitors, instead of the normal website. None of your theme features (like menu) will be available during the scheduled downtime, and none of your "normal" pages or posts will be accessible, so design your dedicated downpage accordingly. The downpage will be shown until the timer you set below expires or you manually disable the downtime mode.
						</div>
						<div style="margin-top: 20px; margin-bottom: 10px; display: <?php echo ($ts_vcsc_extend_settings_downpage_pages > 0 ? 'block' : 'none'); ?>">
							<h4>Place Website in Downtime Mode</h4>
							<p style="font-size: 12px; margin: 10px auto;">Activate the downtime mode for your website:</p>							
							<?php
								$settings = array(
									"param_name"        => "ts_vcsc_extend_settings_downpage_active",
									"label"				=> "Activate Downtime Status",
									"value"             => $ts_vcsc_extend_settings_downpage_active,
									"order"				=> 2,
								);
								echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_downpage_active);
							?>
						</div>
						<div style="margin-top: 20px; margin-bottom: 10px; display: <?php echo ($ts_vcsc_extend_settings_downpage_pages == 0 ? 'block' : 'none'); ?>">
							<div class="ts-vcsc-info-field ts-vcsc-critical" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify; font-weight: bold;">
								Before you can place your website into downtime mode, you need to create at least one downpage that will be displayed to your viewers during the downtime. Please create one such page, using the dedicated custom post type "<a href="<?php echo $DownPageAddNewUrl; ?>" target="_parent">VC Downpages</a>", which you should find in your admin menu to the right. Alternatively, you can also manually <a href="https://www.seedprod.com/how-to-create-custom-maintenance-mode-page-wordpress/" target="_blank">create a global maintenance.php page</a> for WordPress, which can also be used as source for any downtime page.
							</div>
						</div>
					</div>
				</div>	
			</div>
			<div id="ts-downtime-content" class="tab-content">
				<div id="ts-vcsc-downpage-check-pages" class="ts-vcsc-section-main ">
					<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-admin-generic"></i>Downpage Source Selection</div>
					<div class="ts-vcsc-section-content">
						<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
							By default, the page shown when in downtime mode will be the same across all device types (desktop, tablet, mobile), but you have the option to assign different pages for each type, by using the setting option below and then assigning a specific page to each device type.
						</div>
						<?php
							if ($ts_vcsc_extend_settings_downpage_maintenance == "true") {
								echo '<div class="ts-vcsc-notice-field ts-vcsc-info" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">';
									echo 'The plugin detected a custom drop-in "maintenance.php" file in the WordPress "wp-content" directory. You have the option to use the page style and content defined in that file as your downpage; in order to do so, simply select that file in the dropbox(es) below.';
								echo '</div>';
							}						
						?>
						<div style="margin-top: 20px; margin-bottom: 20px;">
							<h4>Use Same Downpage for all Devices</h4>
							<p style="font-size: 12px; margin: 10px auto;">Use the switch to either use the same downpage for all device types, or assign specific ones to each device type:</p>
							<div class="ts-switch-button ts-codestar-field-switcher" data-value="<?php echo $ts_vcsc_extend_settings_downpage_single; ?>">
								<div class="ts-codestar-fieldset">
									<label class="ts-codestar-label">
										<input id="ts_vcsc_extend_settings_downpage_single" data-order="2" value="<?php echo $ts_vcsc_extend_settings_downpage_single; ?>" class="ts-codestar-checkbox ts_vcsc_extend_settings_downpage_single" name="ts_vcsc_extend_settings_downpage_single" type="checkbox" <?php echo ($ts_vcsc_extend_settings_downpage_single == 1 ? 'checked="checked"' : ''); ?>> 
										<input type="hidden" style="display: none; " id="ts_vcsc_extend_settings_downpage_singleyes" data-order="2" class="ts_vcsc_extend_settings_downpage_singleyes" name="ts_vcsc_extend_settings_downpage_singleyes" value="<?php echo ($ts_vcsc_extend_settings_downpage_single == 1 ? 'yes' : ''); ?>">
										<input type="hidden" style="display: none; " id="ts_vcsc_extend_settings_downpage_singlenoa" data-order="2" class="ts_vcsc_extend_settings_downpage_singlenoa" name="ts_vcsc_extend_settings_downpage_singlenoa" value="<?php echo ($ts_vcsc_extend_settings_downpage_single == 0 ? 'yes' : ''); ?>">
										<input type="hidden" style="display: none; " id="ts_vcsc_extend_settings_downpage_singlenob" data-order="2" class="ts_vcsc_extend_settings_downpage_singlenob" name="ts_vcsc_extend_settings_downpage_singlenob" value="<?php echo ($ts_vcsc_extend_settings_downpage_single == 0 ? 'yes' : ''); ?>">
										<input type="hidden" style="display: none; " id="ts_vcsc_extend_settings_downpage_singlenoc" data-order="2" class="ts_vcsc_extend_settings_downpage_singlenoc" name="ts_vcsc_extend_settings_downpage_singlenoc" value="<?php echo ($ts_vcsc_extend_settings_downpage_single == 0 ? 'yes' : ''); ?>">
										<em data-on="Yes" data-off="No"></em>
										<span></span>
									</label>
								</div>
							</div>
							<label class="labelToggleBox" for="ts_vcsc_extend_settings_downpage_single">Use Same Downpage</label>
						</div>
						<div id="ts_vcsc_downtime_wrapper_alldevices" class="ts_vcsc_downtime_wrapper_alldevices" style="margin-top: 10px; margin-bottom: 10px; display: <?php echo ($ts_vcsc_extend_settings_downpage_single == 1 ? "block" : "none"); ?>;">
							<?php
								// Downpage ALL Devices
								$settings = array(
									"heading"                   => __( "Downpage", "ts_visual_composer_extend" ),
									"param_name"                => "ts_vcsc_extend_settings_downpage_alldevices",
									"posttype"                  => "ts_downtime",
									"posttaxonomy"              => "ts_downtime_category",
									"taxonomy"              	=> "ts_downtime_category",
									"postsingle"				=> "Downpage (All Devices)",
									"postplural"				=> "Downpages (All Devices)",
									"postclass"					=> "downpage",
									"value"                     => $ts_vcsc_extend_settings_downpage_alldevices,
									"order"						=> "2",
									"error"						=> "Down Page - All Devices",
									"maintenance"				=> ($ts_vcsc_extend_settings_downpage_maintenance == true ? "true" : "false"),
									"dependency"				=> "ts_vcsc_extend_settings_downpage_singleyes",
								);
								echo TS_VCSC_CustomPost_Settings_Field($settings, $ts_vcsc_extend_settings_downpage_alldevices);
							?>
						</div>
						<div id="ts_vcsc_downtime_wrapper_desktop" class="ts_vcsc_downtime_wrapper_desktop" style="margin-top: 10px; display: <?php echo ($ts_vcsc_extend_settings_downpage_single == 0 ? "block" : "none"); ?>;">
							<?php
								// Downpage Desktop Devices
								$settings = array(
									"heading"                   => __( "Downpage", "ts_visual_composer_extend" ),
									"param_name"                => "ts_vcsc_extend_settings_downpage_desktop",
									"posttype"                  => "ts_downtime",
									"posttaxonomy"              => "ts_downtime_category",
									"taxonomy"              	=> "ts_downtime_category",
									"postsingle"				=> "Downpage (Desktop Devices)",
									"postplural"				=> "Downpages (Desktop Devices)",
									"postclass"					=> "downpage",
									"value"                     => $ts_vcsc_extend_settings_downpage_desktop,
									"order"						=> "2",
									"error"						=> "Down Page - Desktop Devices",
									"maintenance"				=> ($ts_vcsc_extend_settings_downpage_maintenance == true ? "true" : "false"),
									"dependency"				=> "ts_vcsc_extend_settings_downpage_singlenoa",
								);
								echo TS_VCSC_CustomPost_Settings_Field($settings, $ts_vcsc_extend_settings_downpage_desktop);
							?>
						</div>
						<div id="ts_vcsc_downtime_wrapper_tablet" class="ts_vcsc_downtime_wrapper_tablet" style="margin-top: 10px; display: <?php echo ($ts_vcsc_extend_settings_downpage_single == 0 ? "block" : "none"); ?>;">
							<?php
								// Downpage Tablet Devices
								$settings = array(
									"heading"                   => __( "Downpage", "ts_visual_composer_extend" ),
									"param_name"                => "ts_vcsc_extend_settings_downpage_tablet",
									"posttype"                  => "ts_downtime",
									"posttaxonomy"              => "ts_downtime_category",
									"taxonomy"              	=> "ts_downtime_category",
									"postsingle"				=> "Downpage (Tablet Devices)",
									"postplural"				=> "Downpages (Tablet Devices)",
									"postclass"					=> "downpage",
									"value"                     => $ts_vcsc_extend_settings_downpage_tablet,
									"order"						=> "2",
									"error"						=> "Down Page - Tablet Devices",
									"maintenance"				=> ($ts_vcsc_extend_settings_downpage_maintenance == true ? "true" : "false"),
									"dependency"				=> "ts_vcsc_extend_settings_downpage_singlenob",
								);
								echo TS_VCSC_CustomPost_Settings_Field($settings, $ts_vcsc_extend_settings_downpage_tablet);
							?>
						</div>
						<div id="ts_vcsc_downtime_wrapper_mobile" class="ts_vcsc_downtime_wrapper_mobile" style="margin-top: 10px; margin-bottom: 10px; display: <?php echo ($ts_vcsc_extend_settings_downpage_single == 0 ? "block" : "none"); ?>;">
							<?php
								// Downpage Mobile Devices
								$settings = array(
									"heading"                   => __( "Downpage", "ts_visual_composer_extend" ),
									"param_name"                => "ts_vcsc_extend_settings_downpage_mobile",
									"posttype"                  => "ts_downtime",
									"posttaxonomy"              => "ts_downtime_category",
									"taxonomy"              	=> "ts_downtime_category",
									"postsingle"				=> "Downpage (Mobile Devices)",
									"postplural"				=> "Downpages (Mobile Devices)",
									"postclass"					=> "downpage",
									"value"                     => $ts_vcsc_extend_settings_downpage_mobile,
									"order"						=> "2",
									"error"						=> "Down Page - Mobile Devices",
									"maintenance"				=> ($ts_vcsc_extend_settings_downpage_maintenance == true ? "true" : "false"),
									"dependency"				=> "ts_vcsc_extend_settings_downpage_singlenoc",
								);
								echo TS_VCSC_CustomPost_Settings_Field($settings, $ts_vcsc_extend_settings_downpage_mobile);
							?>
						</div>
					</div>
				</div>
				<div id="ts-vcsc-downpage-check-status" class="ts-vcsc-section-main ">
					<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-hammer"></i>Downpage Header Status</div>
					<div class="ts-vcsc-section-content">
						<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
							When activating the downtime mode and using a custom downpage (<strong>not</strong> the maintenance.php file), you must assign a page header status based on the reason for the downtime mode. To learn more about the differences between a "Coming Soon" and "Maintenance" header status, please click <a href="https://www.seedprod.com/coming-soon-vs-maintenance-mode/" target="_blank">here</a>.
						</div>
						<div style="margin-top: 20px; margin-bottom: 10px; display: <?php echo ($ts_vcsc_extend_settings_downpage_pages > 0 ? 'block' : 'none'); ?>">
							<div id="ts_vcsc_downtime_wrapper_status" class="ts_vcsc_downtime_wrapper_status ts-singleselect-holder" style="margin-top: 20px;">
							<h4>Define the Downpage Header Status</h4>
							<p style="font-size: 12px; margin: 10px auto;">Select the downtime header status you want to assign to the custom downpage:</p>	
								<label class="Uniform" style="display: inline-block; margin-left: 0px; width: 250px;" for="ts_vcsc_extend_settings_downpage_status">Downtime Header Status:</label>
								<select id="ts_vcsc_extend_settings_downpage_status" name="ts_vcsc_extend_settings_downpage_status" style="width: 400px; margin: 0;">									
									<option value="503" <?php selected('503',	$ts_vcsc_extend_settings_downpage_status); ?>>503 - General Maintenance Status</option>
									<option value="200" <?php selected('200', 	$ts_vcsc_extend_settings_downpage_status); ?>>200 - Coming Soon Status</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="ts-downtime-expire" class="tab-content">
				<div id="ts-vcsc-downpage-check-expire" class="ts-vcsc-section-main ">
					<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-calendar-alt"></i>Downtime Expiration</div>
					<div class="ts-vcsc-section-content">
						<div id="ts_vcsc_downtime_wrapper_timer" class="ts_vcsc_downtime_wrapper_timer ts-singleselect-holder" style="margin-top: 20px;">				
							<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
								Use the field below to set how you want to determine the date and/or time period during which the page will be placed in downtime mode.
							</div>				
							<label class="Uniform" style="display: inline-block; margin-left: 0px; width: 250px;" for="ts_vcsc_extend_settings_downpage_timer">Downtime Expiration Timer:</label>
							<select id="ts_vcsc_extend_settings_downpage_timer" name="ts_vcsc_extend_settings_downpage_timer" style="width: 348px; margin: 0;">
								<option value="dateonly" <?php selected('dateonly', 	$ts_vcsc_extend_settings_downpage_timer); ?>>Date Only</option>
								<option value="datetime" <?php selected('datetime',		$ts_vcsc_extend_settings_downpage_timer); ?>>Date + Time</option>
								<option value="timerange" <?php selected('timerange', 	$ts_vcsc_extend_settings_downpage_timer); ?>>Daily Time Range</option>
								<option value="endless" <?php selected('endless', 		$ts_vcsc_extend_settings_downpage_timer); ?>>No Automatic Expiration</option>
							</select>
						</div>
						<div id="ts_vcsc_downtime_wrapper_dateonly" class="ts_vcsc_downtime_wrapper_dateonly" style="height: 225px; max-width: 600px; margin-top: 15px; display: <?php echo ($ts_vcsc_extend_settings_downpage_timer == "dateonly" ? "block" : "none"); ?>;">
							<?php
								// Date Only Selector
								$settings = array(
									"param_name"        => "ts_vcsc_extend_settings_downpage_dateonly",
									"period"			=> "date",
									"value"             => $ts_vcsc_extend_settings_downpage_dateonly,
								);
								echo TS_VCSC_DateTimePicker_Setting_Field($settings, $ts_vcsc_extend_settings_downpage_dateonly);
							?>
						</div>			
						<div id="ts_vcsc_downtime_wrapper_datetime" class="ts_vcsc_downtime_wrapper_datetime" style="max-width: 600px; margin-top: 15px; display: <?php echo ($ts_vcsc_extend_settings_downpage_timer == "datetime" ? "block" : "none"); ?>;">
							<?php
								// Date + Time Selector
								$settings = array(
									"param_name"        => "ts_vcsc_extend_settings_downpage_datetime",
									"period"			=> "datetime",
									"value"             => $ts_vcsc_extend_settings_downpage_datetime,
								);
								echo TS_VCSC_DateTimePicker_Setting_Field($settings, $ts_vcsc_extend_settings_downpage_datetime);
							?>
						</div>			
						<div id="ts_vcsc_downtime_wrapper_timerange" class="ts_vcsc_downtime_wrapper_timerange" style="height: 170px; max-width: 600px; margin-top: 20px; display: <?php echo ($ts_vcsc_extend_settings_downpage_timer == "timerange" ? "block" : "none"); ?>;">
							<?php
								// Daily Time Range Selector
								$timerange 				= explode(",", $ts_vcsc_extend_settings_downpage_timerange);
								$settings = array(
									"param_name"		=> "ts_vcsc_extend_settings_downpage_timerange",
									"min"				=> "1",				// 0 Minutes
									"max"				=> "288",			// 12:00 AM
									"step"				=> "1",				// 5 Minutes
									"range"				=> "true",
									"start"				=> $timerange[0],	// 8:00 AM
									"end"				=> $timerange[1],	// 4:00 PM
									"value"				=> $ts_vcsc_extend_settings_downpage_timerange,
								);
								echo TS_VCSC_NoUiSlider_Settings_Field($settings, $ts_vcsc_extend_settings_downpage_timerange);
							?>
						</div>
						<div id="ts_vcsc_downtime_wrapper_timezone" class="ts_vcsc_downtime_wrapper_timezone ts-singleselect-holder" style="display: <?php echo ($ts_vcsc_extend_settings_downpage_timer == "endless" ? "none" : "block"); ?>">
							<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
								Use the field below to set which timezone the downtime should be matched against; default will the timezone as set in the WordPress settings (if available).
							</div>	
							<label class="Uniform" style="display: inline-block; margin-left: 0px; width: 250px;" for="ts_vcsc_extend_settings_downpage_timezone">Downtime Timezone:</label>
							<select id="ts_vcsc_extend_settings_downpage_timezone" name="ts_vcsc_extend_settings_downpage_timezone" style="width: 348px; margin: 0;">
								<option value="-12" <?php selected('-12', 	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT -12:00</option>
								<option value="-11" <?php selected('-11',	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT -11:00</option>
								<option value="-10" <?php selected('-10', 	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT -10:00</option>
								<option value="-9" <?php selected('-9', 	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT -9:00</option>
								<option value="-8" <?php selected('-8', 	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT -8:00</option>
								<option value="-7" <?php selected('-7', 	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT -7:00</option>
								<option value="-6" <?php selected('-6', 	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT -6:00</option>
								<option value="-5" <?php selected('-5', 	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT -5:00</option>
								<option value="-4" <?php selected('-4', 	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT -4:00</option>
								<option value="-3" <?php selected('-3', 	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT -3:00</option>
								<option value="-2" <?php selected('-2', 	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT -2:00</option>
								<option value="-1" <?php selected('-1', 	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT -1:00</option>
								<option value="0" <?php selected('0', 		$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT 00:00</option>
								<option value="1" <?php selected('1', 		$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT 01:00</option>
								<option value="2" <?php selected('2', 		$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT 02:00</option>
								<option value="3" <?php selected('3', 		$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT 03:00</option>
								<option value="3.5" <?php selected('3.5',	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT 03:30</option>
								<option value="4" <?php selected('4', 		$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT 04:00</option>
								<option value="4.5" <?php selected('4.5',	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT 04:30</option>
								<option value="5" <?php selected('5', 		$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT 05:00</option>
								<option value="5.5" <?php selected('5.5',	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT 05:30</option>
								<option value="5.75" <?php selected('5.75',	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT 05:45</option>
								<option value="6" <?php selected('6', 		$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT 06:00</option>
								<option value="6.5" <?php selected('6.5',	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT 06:30</option>
								<option value="7" <?php selected('7', 		$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT 07:00</option>
								<option value="8" <?php selected('8', 		$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT 08:00</option>
								<option value="9" <?php selected('9', 		$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT 09:00</option>
								<option value="9.5" <?php selected('9.5',	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT 09:30</option>
								<option value="10" <?php selected('10', 	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT 10:00</option>
								<option value="11" <?php selected('11',		$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT 11:00</option>
								<option value="12" <?php selected('12', 	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT 12:00</option>
								<option value="13" <?php selected('13', 	$ts_vcsc_extend_settings_downpage_timezone); ?>>GMT 13:00</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div id="ts-downtime-userroles" class="tab-content">
				<div id="ts-vcsc-downpage-check-userroles" class="ts-vcsc-section-main ">
					<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-businessman"></i>Logged In Users</div>
					<div class="ts-vcsc-section-content">
						<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
							By default, the site will remain in downtime mode even for logged in users, with the exception of users with the "administrator" role (privileges). Use the controls below to determine which user roles will be able to view the normal website, once successfully logged into the site.
						</div>
						<div id="ts_vcsc_downtime_wrapper_userroles" class="ts_vcsc_downtime_wrapper_userroles" style="max-width: 600px; margin-bottom: 10px;">
							<?php
								$settings = array(
									"param_name"			=> "ts_vcsc_extend_settings_downpage_userroles",
									"value"					=> $ts_vcsc_extend_settings_downpage_users,
								);
								echo TS_VCSC_UserRoles_Settings_Field($settings, $ts_vcsc_extend_settings_downpage_users);
							?>
						</div>
					</div>
				</div>
			</div>
			<div id="ts-downtime-override" class="tab-content">
				<div id="ts-vcsc-downpage-check-override" class="ts-vcsc-section-main ">
					<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-unlock"></i>Visitor Override</div>
					<div class="ts-vcsc-section-content">
						<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
							If you want some visitors to be able to bypass the downpage and view the actual website, you can do so by defining a keyword/slug that the visitor can add to the page URL (preceded with a "?" character, which will then in return "unlock" the normal website, even if the downtime mode is still active. The page will set a cookie in the visitors browser (valid for a time you can define below), which will allow the visitor to view the website for that time period by using the keyword/slug only once, instead of having to re-enter it for each page load again.
						</div>
						<div class="ts-vcsc-info-field ts-vcsc-critical" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
							This feature will only work if the visitor's browser accepts cookies to be set and read by this website.
						</div>
						<div id="ts_vcsc_downtime_wrapper_override" style="margin-bottom: 20px;">
							<h4>Activate Visitor Override</h4>
							<p style="font-size: 12px; margin: 10px auto;">Use this option to provide visitors to bypass the downpage via keyqord/slug:</p>
							<div class="ts-switch-button ts-codestar-field-switcher" data-value="<?php echo $ts_vcsc_extend_settings_downpage_override; ?>">
								<div class="ts-codestar-fieldset">
									<label class="ts-codestar-label">
										<input id="ts_vcsc_extend_settings_downpage_override" data-order="2" value="<?php echo $ts_vcsc_extend_settings_downpage_override; ?>" class="ts-codestar-checkbox ts_vcsc_extend_settings_downpage_override" name="ts_vcsc_extend_settings_downpage_override" type="checkbox" <?php echo ($ts_vcsc_extend_settings_downpage_override == 1 ? 'checked="checked"' : ''); ?>> 
										<em data-on="Yes" data-off="No"></em>
										<span></span>
									</label>
								</div>
							</div>
							<label class="labelToggleBox" for="ts_vcsc_extend_settings_downpage_active">Activate Visitor Override</label>
						</div>
						<div id="ts_vcsc_downtime_wrapper_preview" class="ts_vcsc_downtime_wrapper_preview" style="margin-bottom: 20px; display: <?php echo ($ts_vcsc_extend_settings_downpage_override == 1 ? "block" : "none;"); ?>">
							<h4>Visitor Preview Keyword/Slug</h4>
							<p style="font-size: 12px; margin: 10px auto;">Provide the keyword or slug visitors need to add to the browser URL in order to bypass the downpage:</p>
							<label style="display: inline-block; margin-left: 0px; width: 250px;" for="ts_vcsc_extend_settings_downpage_preview">"Preview Keyword/Slug":</label>
							<input data-validation-engine="validate[condRequired[ts_vcsc_extend_settings_downpage_overrideon]]" data-error="String - Preview Slug" data-order="5" type="text" style="width: 350px;" id="ts_vcsc_extend_settings_downpage_preview" name="ts_vcsc_extend_settings_downpage_preview" value="<?php echo $ts_vcsc_extend_settings_downpage_preview; ?>" size="100">
						</div>
						<div id="ts_vcsc_downtime_wrapper_urlslug" class="ts_vcsc_downtime_wrapper_urlslug" style="display: <?php echo ($ts_vcsc_extend_settings_downpage_override == 1 ? "block" : "none;"); ?>">
							<h4>Required URL with keyword/slug (preceded with a "?" character) in order to bypass downpage:</h4>
							<div id="ts_vcsc_extend_settings_downpage_urlslug" class="ts_vcsc_extend_settings_downpage_urlslug ts-settings-parameter-gradient-grey" style="margin-top: 10px; margin-bottom: 20px; padding: 10px; width: 580px; border: 1px solid #ededed;" data-siteurl="<?php echo $CurrentSiteUrl; ?>"><?php echo $CurrentSiteUrl . '?' . $ts_vcsc_extend_settings_downpage_preview; ?></div>
						</div>
						<div id="ts_vcsc_downtime_wrapper_cookie" class="ts_vcsc_downtime_wrapper_cookie" style="display: <?php echo ($ts_vcsc_extend_settings_downpage_override == 1 ? "block" : "none;"); ?>">
							<h4>Visitor Cookie Expiration Time</h4>
							<p style="font-size: 12px; margin: 10px auto;">Use the slider below to define the time period during which the visitor can view the page after using the keyword/slug once; set the time to "0" (zero) in order to keep
							the session active as long as the browser is open:</p>
							<div style="height: 120px; max-width: 600px; margin-top: 20px; margin-bottom: 10px;">
								<?php
									// Cookie Expiration Time
									$settings = array(
										"param_name"        => "ts_vcsc_extend_settings_downpage_cookie",
										"value"             => $ts_vcsc_extend_settings_downpage_cookie,
										"min"               => "0",
										"max"               => "120",
										"step"              => "1",
										"unit"              => 'min',									
									);
									echo TS_VCSC_NoUiSlider_Settings_Field($settings, $ts_vcsc_extend_settings_downpage_cookie);
								?>
							</div>
						</div>	
					</div>
				</div>
			</div>
        </div>
		<div class="ts-vcsc-settings-group-bottombar ts-vcsc-settings-group-buttonbar" style="">
			<div class="ts-vcsc-settings-group-actionbar">
				<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder ts-advanced-link-tooltip-right">
					<span class="ts-advanced-link-tooltip-content"><?php _e("Click here to save your downtime manager settings.", "ts_visual_composer_extend"); ?></span>
					<button type="submit" name="Submit" id="ts_vcsc_extend_settings_submit_2" class="ts-advanced-link-button-main ts-advanced-link-button-blue ts-advanced-link-button-save" style="margin: 0;">
						<?php _e("Save Settings", "ts_visual_composer_extend"); ?>
					</button>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</form>