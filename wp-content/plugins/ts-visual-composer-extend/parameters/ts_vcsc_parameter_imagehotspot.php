<?php
    /*
     No Additional Setting Options
    */
    if (!class_exists('TS_Parameter_ImageHotspot')) {
        class TS_Parameter_ImageHotspot {
            function __construct() {	
                if (function_exists('vc_add_shortcode_param')) {
					vc_add_shortcode_param('imagehotspot', array(&$this, 'imagehotspot_settings_field'));
				} else if (function_exists('add_shortcode_param')) {
                    add_shortcode_param('imagehotspot', array(&$this, 'imagehotspot_settings_field'));
				}
            }        
            function imagehotspot_settings_field($settings, $value) {
                $param_name     		= isset($settings['param_name']) ? $settings['param_name'] : '';
                $type           		= isset($settings['type']) ? $settings['type'] : '';
                $min            		= isset($settings['min']) ? $settings['min'] : '';
                $max            		= isset($settings['max']) ? $settings['max'] : '';
                $step           		= isset($settings['step']) ? $settings['step'] : '';
                $unit           		= isset($settings['unit']) ? $settings['unit'] : '';
                $decimals				= isset($settings['decimals']) ? $settings['decimals'] : 0;
                $suffix        	 		= isset($settings['suffix']) ? $settings['suffix'] : '';
                $class          		= isset($settings['class']) ? $settings['class'] : '';
                $coordinates			= explode(",", $value);
				// Other Settings
				$random_id_number		= rand(100000, 999999);
                $output         		= '';
                $required_vc 			= '4.3.0';
				$output .= '<div id="ts-image-hotspot-container-wrapper-' . $random_id_number . '" class="ts-image-hotspot-container-wrapper clearFixMe ts-settings-parameter-gradient-grey">';
					if (defined('WPB_VC_VERSION')){
						if (version_compare(WPB_VC_VERSION, $required_vc) >= 0) {
							// Hotspot Image Preview
							$output .= '<div class="ts-image-hotspot-container-preview">';
								$output .= '<img class="ts-image-hotspot-image-preview ts-image-hotspot-image-loading" data-default="' . TS_VCSC_GetResourceURL('images/other/hotspot_raster.jpg') . '" src="">';
								$output .= '<div class="ts-image-hotspot-holder-preview">';				
									$output .= '<div class="ts-image-hotspot-single-preview" style="left: ' . $coordinates[0] . '%; top: ' . $coordinates[1] . '%;">';					
										$output .= '<div class="ts-image-hotspot-trigger-preview"><div class="ts-image-hotspot-trigger-pulse"></div><div class="ts-image-hotspot-trigger-dot"></div></div>';
									$output .= '</div>';				
								$output .= '</div>';
							$output .= '</div>';
							// Message
							$output .= '<div class="ts-image-hotspot-image-message">' . __( "Use the sliders below or use your mouse to drag the hotspot to its desired spot on the image.", "ts_visual_composer_extend" ) . '</div>';
						} else {
							// Message
							$output .= '<div class="ts-image-hotspot-image-message">' . __( "Use the sliders below to position the hotspot on its desired spot on the image.", "ts_visual_composer_extend" ) . '</div>';
						}
					} else {
						// Message
						$output .= '<div class="ts-image-hotspot-image-message">' . __( "Use the sliders below to position the hotspot on its desired spot on the image.", "ts_visual_composer_extend" ) . '</div>';
					}
					// Hidden Input
					$output .= '<input name="' . $param_name . '" id="' . $param_name . '" class="ts-nouislider-hotspot-value wpb_vc_param_value ' . $param_name . ' ' . $type . '" type="hidden" value="' . $value . '" style="display: none;"/>';	
					// X-Position Slider
					$output .= '<div id="ts-nouislider-hotspot-slider-horizontal-' . $random_id_number . '" class="ts-nouislider-hotspot-slider clearFixMe">';
						$output .= '<div class="" style="font-weight: bold; padding-bottom: 5px;">' . __( "Horizontal Position (X)", "ts_visual_composer_extend" ) . '</div>';
						$output .= '<input id="ts-input-hotspot-horizontal-' . $random_id_number . '" name="" class="ts-input-hotspot-horizontal ts-nouislider-serial nouislider-input-selector nouislider-input-composer" type="text" max="100" min="0" step="1" value="' . $coordinates[0] . '"/>';
						$output .= '<span class="ts-nouislider-input-unit">' . $unit . '</span>';
						$output .= '<span class="ts-nouislider-input-min">' . number_format_i18n($min, $decimals) . '%</span>';
						$output .= '<span class="ts-nouislider-input-down dashicons-arrow-left"></span>';						
						$output .= '<div id="ts-nouislider-hotspot-horizontal-' . $random_id_number . '" class="ts-nouislider-input ts-nouislider-hotspot-element ts-nouislider-hotspot-horizontal" data-position="horizontal" data-unit="' . $unit . '" data-value="' . $coordinates[0] . '" data-min="' . $min . '" data-max="' . $max . '" data-decimals="' . $decimals . '" data-step="' . $step . '" style="width: 280px; float: left; margin-top: 10px;"></div>';
						$output .= '<span class="ts-nouislider-input-up dashicons-arrow-right"></span>';
						$output .= '<span class="ts-nouislider-input-max">' . number_format_i18n($max, $decimals) . '%</span>';
					$output .= '</div>';
					// Y-Position Slider
					$output .= '<div id="ts-nouislider-hotspot-slider-vertical-' . $random_id_number . '" class="ts-nouislider-hotspot-slider clearFixMe" style="margin-bottom: 10px;">';
						$output .= '<div class="" style="font-weight: bold; padding-bottom: 5px;">' . __( "Vertical Position (Y)", "ts_visual_composer_extend" ) . '</div>';
						$output .= '<input id="ts-input-hotspot-vertical-' . $random_id_number . '" name="" class="ts-input-hotspot-vertical ts-nouislider-serial nouislider-input-selector nouislider-input-composer" type="text" max="100" min="0" step="1" value="' . $coordinates[1] . '"/>';
						$output .= '<span class="ts-nouislider-input-unit">' . $unit . '</span>';
						$output .= '<span class="ts-nouislider-input-min">' . number_format_i18n($min, $decimals) . '%</span>';
						$output .= '<span class="ts-nouislider-input-down dashicons-arrow-left"></span>';						
						$output .= '<div id="ts-nouislider-hotspot-vertical-' . $random_id_number . '" class="ts-nouislider-input ts-nouislider-hotspot-element ts-nouislider-hotspot-vertical" data-position="vertical" data-unit="' . $unit . '" data-value="' . $coordinates[1] . '" data-min="' . $min . '" data-max="' . $max . '" data-decimals="' . $decimals . '" data-step="' . $step . '" style="width: 280px; float: left; margin-top: 10px;"></div>';
						$output .= '<span class="ts-nouislider-input-up dashicons-arrow-right"></span>';
						$output .= '<span class="ts-nouislider-input-max">' . number_format_i18n($max, $decimals) . '%</span>';
					$output .= '</div>';
				$output .= '</div>';
                return $output;
            }
        }
    }
    if (class_exists('TS_Parameter_ImageHotspot')) {
        $TS_Parameter_ImageHotspot = new TS_Parameter_ImageHotspot();
    }
?>