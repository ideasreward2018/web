<?php
    /*
     No Additional Setting Options
    */
	if (!class_exists('TS_Parameter_ScreenSizes')) {
		class TS_Parameter_ScreenSizes 	{
			function __construct() {
				if (function_exists('vc_add_shortcode_param')) {
					vc_add_shortcode_param('screensizes_selectors', array(&$this, 'screensizes_selectors_settings_field'));
				} else if (function_exists('add_shortcode_param')) {
					add_shortcode_param('screensizes_selectors', array(&$this, 'screensizes_selectors_settings_field'));
				}
			}		
			function screensizes_selectors_settings_field($settings, $value) {
				global $VISUAL_COMPOSER_EXTENSIONS;
				$unit 							= isset($settings['unit']) ? $settings['unit'] : 'px';
				$devices 						= isset($settings['devices']) ? $settings['devices'] : array();
				$collapsed						= isset($settings['collapsed']) ? $settings['collapsed'] : 'true';
				$pips							= isset($settings['pips']) ? $settings['pips'] : 'true';
                $min            				= isset($settings['min']) ? $settings['min'] : '0';
                $max           	 				= isset($settings['max']) ? $settings['max'] : '2048';
                $step           				= isset($settings['step']) ? $settings['step'] : '1';
                $unit           				= isset($settings['unit']) ? $settings['unit'] : 'px';
                $decimals						= isset($settings['decimals']) ? $settings['decimals'] : 0;				
				// Other Settings
				$random_id_number               = rand(100000, 999999);
				$random_id_container            = 'ts-screensizes-datastring-' . $random_id_number;
				$random_id_counter				= 0;
				$random_id_slider				= $random_id_number + '-' + $random_id_counter;
				if (($value != '') && (is_numeric($value))) {
					$value 						= 'extra_large:' . $value . 'px;large:' . $value . 'px;medium:' . $value . 'px;small:' . $value . 'px;extra_small:' . $value . 'px;';
				}
				$output							= '';
				$output  .= '<div id="ts-screensizes-container-' . $random_id_slider . '" class="ts-screensizes-container ts-settings-parameter-gradient-grey clearFixMe ' . ($collapsed == "true" ? 'ts-screensizes-container-closed' : 'ts-screensizes-container-open') . '" data-pips="' . $pips . '">';
					$output .= ' <div class="ts-screensizes-listing" >';
					foreach ($devices as $device => $defaults) {
						$random_id_counter++;
						$random_id_slider		= $random_id_number . '-' . $random_id_counter;
						switch ($device) {
							case 'Extra Large':       
								$class 		= 'required';
								$data_id  	= strtolower((preg_replace('/\s+/', '_', $device)));
								$dashicon 	= "<i class='dashicons dashicons-star-filled'></i>";
								$tooltip	= __("Extra Large Screens (%dpx or more)", "ts_visual_composer_extend");
								$sizes		= array($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Screen_Sizes_Custom['ExtraLarge'], 9999);
								$output .= $this->TS_VCSC_ScreenSizes_Media_Item($class, $dashicon, $tooltip, $sizes, $device, $defaults['default'], $defaults['min'], $defaults['max'], $defaults['step'], $unit, $data_id, $random_id_slider);
								$output .= "<div id='ts-screensizes-toggle-" . $random_id_slider . "' class='ts-screensizes-toggle' data-container='ts-screensizes-container-" . $random_id_number . "' data-toggle='" . ($collapsed == "true" ? 'collapsed' : 'expanded') . "'>
												<i class='ts-screensizes-toggle-icon dashicons " . ($collapsed == "true" ? 'dashicons-arrow-down-alt2' : 'dashicons-arrow-up-alt2') . "'></i>
											</div>";
								$output .= '<div class="ts-screensizes-subtypes" style="display: ' . ($collapsed == "true" ? 'none' : 'block') . '">';
								break;
							case 'Large':        
								$class 		= 'optional';
								$data_id  	= strtolower((preg_replace('/\s+/', '_', $device)));
								$dashicon 	= "<i class='dashicons dashicons-star-half'></i>";
								$tooltip	= __("Large Screens (%dpx to %dpx)", "ts_visual_composer_extend");
								$sizes		= array($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Screen_Sizes_Custom['Large'], $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Screen_Sizes_Custom['ExtraLarge']);
								$output .= $this->TS_VCSC_ScreenSizes_Media_Item($class, $dashicon, $tooltip, $sizes, $device, $defaults['default'], $defaults['min'], $defaults['max'], $defaults['step'], $unit, $data_id, $random_id_slider);
								break;
							case 'Medium':        
								$class 		= 'optional';
								$data_id  	= strtolower((preg_replace('/\s+/', '_', $device)));
								$dashicon 	= "<i class='dashicons dashicons-star-empty'></i>";
								$tooltip	= __("Medium Screens (%dpx to %dpx)", "ts_visual_composer_extend");
								$sizes		= array($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Screen_Sizes_Custom['Medium'], $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Screen_Sizes_Custom['Large']);
								$output .= $this->TS_VCSC_ScreenSizes_Media_Item($class, $dashicon, $tooltip, $sizes, $device, $defaults['default'], $defaults['min'], $defaults['max'], $defaults['step'], $unit, $data_id, $random_id_slider);
								break;
							case 'Small':       
								$class 		= 'optional';
								$data_id  	= strtolower((preg_replace('/\s+/', '_', $device)));
								$dashicon 	= "<i class='dashicons dashicons-plus'></i>";
								$tooltip	= __("Small Screens (%dpx to %dpx)", "ts_visual_composer_extend");
								$sizes		= array($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Screen_Sizes_Custom['Small'], $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Screen_Sizes_Custom['Medium']);
								$output .= $this->TS_VCSC_ScreenSizes_Media_Item($class, $dashicon, $tooltip, $sizes, $device, $defaults['default'], $defaults['min'], $defaults['max'], $defaults['step'], $unit, $data_id, $random_id_slider);
								break;
							case 'Extra Small':        
								$class 		= 'optional';
								$data_id  	= strtolower((preg_replace('/\s+/', '_', $device)));
								$dashicon 	= "<i class='dashicons dashicons-minus'></i>";
								$tooltip	= __("Extra Small Screens (%dpx to %dpx)", "ts_visual_composer_extend");
								$sizes		= array(0, $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Screen_Sizes_Custom['Small']);
								$output .= $this->TS_VCSC_ScreenSizes_Media_Item($class, $dashicon, $tooltip, $sizes, $device, $defaults['default'], $defaults['min'], $defaults['max'], $defaults['step'], $unit, $data_id, $random_id_slider);
								break;
						}
					}
				$output .= '</div></div>';
					// Create Hidden Input to store final values
					$output .= '<input id="' . $random_id_container . '" type="hidden" data-unit="' . $unit . '"  name="' . $settings['param_name'] . '" class="wpb_vc_param_value ts-screensizes-datastring ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" value="' . $value . '"/>';
				$output .= '</div>';			
				return $output;
			}
			// Function to Create Screen Size Inputs
			function TS_VCSC_ScreenSizes_Media_Item($class, $dashicon, $tooltip, $sizes, $device, $default, $min, $max, $step, $unit, $data_id, $identifier) {
				$output  = '<div class="ts-screensizes-item clearFixMe ' . $class . ' ' . $data_id . ' " style="">';
					$output .= '<div id="ts-nouislider-input-slider-' . $identifier . '" class="ts-nouislider-input-slider" style="display: inline-block;">';
						$output .= '<div class="ts-screensizes-icon" style="display: inline-block">';
							$output .= '<div class="ts-screensizes-tooltip">' . sprintf($tooltip, $sizes[0], $sizes[1]) . '</div>';
							$output .= $dashicon;
						$output .= '</div>';
						$output .= '<input id="ts-screensizes-item-input-' . $identifier . '" class="ts-screensizes-item-input ts-nouislider-serial nouislider-input-selector nouislider-input-composer" style="width: 100px; display: inline-block; margin-left: 0px; margin-right: 10px;" data-default="' . $default . '" data-unit="' . $unit . '" data-id="' . $data_id . '" type="text" min="' . $min . '" max="' . $max . '" step="' . $step . '" value="' . $default . '"/>';
						$output .= '<span class="ts-nouislider-devicetype-unit" class="unit">' . $unit . '</span>';
						$output .= '<span class="ts-nouislider-input-down dashicons-arrow-left"></span>';
						$output .= '<div id="ts-nouislider-devicetype-element-' . $identifier . '" class="ts-nouislider-input ts-nouislider-devicetype-element" data-type="' . $class . '" data-unit="' . $unit . '" data-value="' . $default . '" data-min="' . $min . '" data-max="' . $max . '" data-decimals="0" data-step="' . $step . '" style="width: 250px; display: inline-block; float: left; margin-top: 10px; vertical-align: top;"></div>';
						$output .= '<span class="ts-nouislider-input-up dashicons-arrow-right"></span>';						
					$output .= '</div>';					
				$output .= '</div>';
				return $output;
			}
		}
	}
	if (class_exists('TS_Parameter_ScreenSizes')) {
		$TS_Parameter_ScreenSizes = new TS_Parameter_ScreenSizes();
	}
?>