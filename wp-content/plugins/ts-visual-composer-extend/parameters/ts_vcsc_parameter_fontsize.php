<?php
    // No Additional Settings
    if (!class_exists('TS_Parameter_FontSize')) {
        class TS_Parameter_FontSize {
            function __construct() {	
                if (function_exists('vc_add_shortcode_param')) {
                    vc_add_shortcode_param('fontsize_generator', array(&$this, 'fontsize_generator_settings_field'));
				} else if (function_exists('add_shortcode_param')) {
					add_shortcode_param('fontsize_generator', array(&$this, 'fontsize_generator_settings_field'));
				}
            }			
			function fontsize_generator_settings_field($settings, $value) {
                $param_name     		= isset($settings['param_name'])    ? $settings['param_name']   : '';
                $type           		= isset($settings['type'])          ? $settings['type']         : '';
				$default           		= isset($settings['default'])		? $settings['default']		: '14px';
                $suffix         		= isset($settings['suffix'])        ? $settings['suffix']       : '';
                $class          		= isset($settings['class'])         ? $settings['class']        : '';
				// Other Variables
				$randomizer				= rand(100000, 999999);
				$output         		= '';
				$value_size				= '';
				$value_format			= '';
				$value_aggregate		= '';
				$data_absolute			= array(
					'xx-large',
					'x-large',
					'large',
					'medium',
					'small',
					'x-small',
					'xx-small',
					'larger',
					'smaller',
				);
				$data_formats		= array(
					"px",
					"pt",
					"ex",
					"em",
					"rem",
					"%",
					"vh",
					"vw",
					"vmin",
					"vmax",
				);
				// Contingency Check
				if (empty($value)) {
					$value				= $default;
				}
				if (is_numeric($value)) {
					$value_size			= $value;
					$value_format		= "px";
					$value_aggregate	= $value_size . $value_format;
				} else if (in_array($value, $data_absolute)) {
					$value_size			= $value;
					$value_format		= "absolute";
					$value_aggregate	= $value_size;
				} else {
					$value_size			= floatval($value);
					$value_format		= str_replace($value_size, "", $value);
					$value_aggregate	= $value_size . $value_format;
				};
				// Final Output                
				$output  .= '<div id="ts-fontsizes-generator-container-' . $randomizer . '" class="ts-fontsizes-generator-container ts-settings-parameter-gradient-grey clearFixMe">';
					// Results Label
					$output .= '<div class="ts-fontsizes-results-wrapper">' . __( "Font Size:", "ts_visual_composer_extend" ) . ' <span>' . $value_aggregate . '</span></div>';
					// Show/Hide Toggle
					$output .= '<div class="ts-fontsizes-toggle-wrapper">';
						$output .= '<div class="ts-fontsizes-toggle-switch ts-switch-button ts-codestar-field-switcher" data-render="string">';
							$output .= '<input type="hidden" style="display: none;" class="ts-codestar-value toggle-input" value="false" name="ts-fontsizes-toggle-input"/>';
							$output .= '<div class="ts-codestar-fieldset">';
								$output .= '<label class="ts-codestar-label">';										
									$output .= '<input value="false" class="ts-codestar-checkbox" type="checkbox">';
									$output .= '<em data-on="'. __("Yes", "ts_visual_composer_extend") .'" data-off="'. __("No", "ts_visual_composer_extend") .'"></em>';
									$output .= '<span></span>';
								$output .= '</label>';
							$output .= '</div>';
						$output .= '</div>';
						$output .= '<div class="ts-fontsizes-toggle-label">' . __( "Change Font Size:", "ts_visual_composer_extend" ) . '</div>';
					$output .= '</div>';
					// Toggle (Edit) Section
					$output .= '<div class="ts-fontsizes-edit-wrapper" style="display: none;">';
						// Input for Size Format
						$output .= '<div class="ts-fontsizes-units-wrapper" style="display: block;">';
							$output .= '<div class="ts-fontsizes-title">' . __( "Size Unit:", "ts_visual_composer_extend" ) . '</div>';
							$output .= '<select class="ts-fontsizes-units-select" data-units="' . implode(",", $data_formats) . '">';
								$output .= '<option value="px" ' . selected($value_format, "px", false) . '>' . __( "Pixel (px)", "ts_visual_composer_extend" ) . '</option>';
								$output .= '<option value="pt" ' . selected($value_format, "pt", false) . '>' . __( "Points (pt)", "ts_visual_composer_extend" ) . '</option>';
								$output .= '<option value="em" ' . selected($value_format, "em", false) . '>' . __( "EMS (em)", "ts_visual_composer_extend" ) . '</option>';
								$output .= '<option value="rem" ' . selected($value_format, "rem", false) . '>' . __( "REM (rem)", "ts_visual_composer_extend" ) . '</option>';
								$output .= '<option value="%" ' . selected($value_format, "%", false) . '>' . __( "Percent (%)", "ts_visual_composer_extend" ) . '</option>';							
								$output .= '<option value="vh" ' . selected($value_format, "vh", false) . '>' . __( "Viewport Height (vh)", "ts_visual_composer_extend" ) . '</option>';
								$output .= '<option value="vw" ' . selected($value_format, "vw", false) . '>' . __( "Viewport Width (vw)", "ts_visual_composer_extend" ) . '</option>';
								$output .= '<option value="vmin" ' . selected($value_format, "vmin", false) . '>' . __( "Smaller of Viewport Height / Width (vmin)", "ts_visual_composer_extend" ) . '</option>';
								$output .= '<option value="vmax" ' . selected($value_format, "vmax", false) . '>' . __( "Larger of Viewport Height / Width (vmax)", "ts_visual_composer_extend" ) . '</option>';
								$output .= '<option value="absolute" ' . selected($value_format, "absolute", false) . '>' . __( "Absolute Sizes", "ts_visual_composer_extend" ) . '</option>';
							$output .= '</select>';
						$output .= '</div>';
						$output .= '<div class="ts-fontsizes-description vc_description">' . __( "Select the measurement unit to be used for the font size. Learn more about size units here:", "ts_visual_composer_extend" ) . ' ' . '<a href="https://css-tricks.com/almanac/properties/f/font-size/" target="_blank">Size Units</a>' . '</div>';
						$output .= '<div class="ts-fontsizes-title">' . __( "Size Value:", "ts_visual_composer_extend" ) . '</div>';
						// Input for Absolute Sizes
						if ($value_format != "absolute") {
							$value_check	= "medium";
						} else {
							$value_check	= $value_size;
						}
						$output .= '<div class="ts-fontsizes-absolute-wrapper" style="display: ' . ($value_format == "absolute" ? "block" : "none") . ';">';
							$output .= '<div class="ts-fontsizes-title">' . __( "Absolute Font Size", "ts_visual_composer_extend" ) . '</div>';
							$output .= '<select class="ts-fontsizes-absolute-select" data-absolute="' . implode(",", $data_absolute) . '">';
								foreach($data_absolute as $absolute){
									$output .= '<option value="' . $absolute . '" ' . selected($value_check, $absolute, false) . '>' . $absolute . '</option>';
								}
							$output .= '</select>';
						$output .= '</div>';
						// Input for Non-Floating Numbers
						$output .= '<div class="ts-fontsizes-numeric-wrapper ts-fontsizes-nouislider-input-slider" style="display: ' . ((($value_format == "px") || ($value_format == "pt")) ? "block" : "none") . ';">';
							$output .= '<div id="ts-fontsizes-numeric-slider-' . $randomizer . '" class="ts-fontsizes-numeric-slider clearFixMe">';
								$output .= '<input class="ts-fontsizes-nouislider-serial nouislider-input-selector nouislider-input-composer ts-fontsizes-numeric-nouislider-input" type="number" min="6" max="100" step="1" value="' . $value_size . '"/>';
								$output .= '<span class="ts-fontsizes-nouislider-input-unit">'. $value_format . '</span>';
								$output .= '<span class="ts-fontsizes-nouislider-input-min">' . number_format_i18n(0, 0) . '</span>';
								$output .= '<span class="ts-fontsizes-nouislider-input-down dashicons-arrow-left"></span>';
								$output .= '<div id="ts-fontsizes-nouislider-input-element-numeric-' . $randomizer . '" class="ts-fontsizes-nouislider-input-numeric ts-fontsizes-nouislider-input-element" data-class="numeric" data-value="' . $value_size . '" data-unit="'. $value_format . '" data-extract="false" data-min="0" data-max="100" data-decimals="0" data-step="1" style="width: 280px; float: left; margin-top: 10px;"></div>';
								$output .= '<span class="ts-fontsizes-nouislider-input-up dashicons-arrow-right"></span>';
								$output .= '<span class="ts-fontsizes-nouislider-input-max">' . number_format_i18n(100, 0) . '</span>';
							$output .= '</div>';
						$output .= '</div>';
						// Input for Floating Numbers
						$output .= '<div class="ts-fontsizes-floating-wrapper ts-fontsizes-nouislider-input-slider" style="display: ' . ((($value_format != "absolute") && ($value_format != "px") && ($value_format != "pt")) ? "block" : "none") . ';">';
							$output .= '<div id="ts-fontsizes-floating-slider-' . $randomizer . '" class="ts-fontsizes-floating-slider clearFixMe">';
								$output .= '<input class="ts-fontsizes-nouislider-serial nouislider-input-selector nouislider-input-composer ts-fontsizes-floating-nouislider-input" type="number" min="1" max="400" step="0.1" value="' . $value_size . '"/>';
								$output .= '<span class="ts-fontsizes-nouislider-input-unit">'. $value_format . '</span>';
								$output .= '<span class="ts-fontsizes-nouislider-input-min">' . number_format_i18n(0, 0) . '</span>';
								$output .= '<span class="ts-fontsizes-nouislider-input-down dashicons-arrow-left"></span>';
								$output .= '<div id="ts-fontsizes-nouislider-input-element-floating-' . $randomizer . '" class="ts-fontsizes-nouislider-input-floating ts-fontsizes-nouislider-input-element" data-class="floating" data-value="' . $value_size . '" data-unit="'. $value_format . '" data-extract="false" data-min="0" data-max="100" data-decimals="1" data-step="0.1" style="width: 280px; float: left; margin-top: 10px;"></div>';
								$output .= '<span class="ts-fontsizes-nouislider-input-up dashicons-arrow-right"></span>';
								$output .= '<span class="ts-fontsizes-nouislider-input-max">' . number_format_i18n(100, 0) . '</span>';
							$output .= '</div>';
						$output .= '</div>';
						$output .= '<div class="ts-fontsizes-description vc_description">' . __( "Define the font size based on the measurement unit selected above.", "ts_visual_composer_extend" ) . '</div>';	
					$output .= '</div>';
					// Hidden Input for Data Aggregation
					$output .= '<input type="hidden" id="ts-fontsizes-aggregate-input-' . $randomizer . '" name="' . $settings['param_name'] . '" class="ts-fontsizes-aggregate-input wpb_vc_param_value ' . $param_name . ' ' . $type . '" style="display: none;" value="' . $value_aggregate . '"/>';
				$output .= '</div>';
                return $output;
            }
        }
    }
    if (class_exists('TS_Parameter_FontSize')) {
        $TS_Parameter_FontSize = new TS_Parameter_FontSize();
    }
?>