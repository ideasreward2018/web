<?php
    /*
     No Additional Setting Options
    */
    if (!class_exists('TS_Parameter_Animations')) {
        class TS_Parameter_Animations {
            function __construct() {	
                if (function_exists('vc_add_shortcode_param')) {
					vc_add_shortcode_param('css3animations', array(&$this, 'css3animations_settings_field'));
				} else if (function_exists('add_shortcode_param')) {
                    add_shortcode_param('css3animations', array(&$this, 'css3animations_settings_field'));
				}
            }        
            function css3animations_settings_field($settings, $value){
                global $VISUAL_COMPOSER_EXTENSIONS;
                $param_name 	= isset($settings['param_name']) ? $settings['param_name'] : '';
                $type 			= isset($settings['type']) ? $settings['type'] : '';
                $class 			= isset($settings['class']) ? $settings['class'] : '';
                $noneselect		= isset($settings['noneselect']) ? $settings['noneselect'] : 'false';
                $prefix			= isset($settings['prefix']) ? $settings['prefix'] : '';
                $default		= isset($settings['default']) ? $settings['default'] : '';
                $connector		= isset($settings['connector']) ? $settings['connector'] : '';
                $effectgroups	= array();
                $selectedclass	= '';
                $selectedgroup	= '';
                $output 		= '';
                $css3animations = '';
				$randomizer		= mt_rand(999999, 9999999);
                if (empty($value)) {
                    $value		= $prefix . $default;
                }
                // Check for Conversion of VC Animations
                $value			= TS_VCSC_ConvertLegacyAnimation($value);
                // Create "None" Option if requested
                if ($noneselect == 'true') {
                    $css3animations .= '<option class="" value="" data-name=""data-group="" data-prefix="" data-value="">' . __( "None", "ts_visual_composer_extend" ) . '</option>';
                };
                foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_CSS_Animations_Array as $Animation_Class => $animations) {
                    if ($animations) {
                        if (!in_array($animations['group'], $effectgroups)) {
                            array_push($effectgroups, $animations['group']);
                            $css3animations .= '<optgroup label="' . $animations['group'] . '">';
                        }
                        if ($value == $prefix . $animations['class']) {
                            $css3animations .= '<option class="' . $animations['class'] . '" value="' . $prefix . $animations['class'] . '" data-name="' . $Animation_Class . '" data-group="' . $animations['group'] . '" data-prefix="' . $prefix . '" data-value="' . $animations['class'] . '" selected="selected">' . $Animation_Class . '</option>';
                            $selectedgroup 	= $animations['group'];
                            $selectedclass	= 'ts-animation-frame ts-hover-css-' . $animations['class'];
                        } else {
                            $css3animations .= '<option class="' . $animations['class'] . '" value="' . $prefix . $animations['class'] . '" data-name="' . $Animation_Class . '"data-group="' . $animations['group'] . '" data-prefix="' . $prefix . '" data-value="' . $animations['class'] . '">' . $Animation_Class . '</option>';
                        }
                    }
                }
                unset($effectgroups);
                $output .= '<div id="ts-css3-animations-wrapper-' . $randomizer . '" class="ts-css3-animations-wrapper clearFixMe ts-settings-parameter-gradient-grey" data-connector="' . $connector . '" data-prefix="' . $prefix . '">';
                    $output .= '<div class="ts-css3-animations-selector">';
                        $output .= '<select name="' . $param_name . '" class="wpb_vc_param_value wpb-input wpb-select dropdown ' . $param_name . ' ' . $type . ' ' . $class . ' ' . $value . '" data-class="' . $class . '" data-type="' . $type . '" data-name="' . $param_name . '" data-option="' . $value . '" value="' . $value . '">';
                            $output .= $css3animations;
                        $output .= '</select>';
                    $output .= '</div>';
                    $output .= '<div class="ts-css3-animations-preview">';
                        $output .= '<span class="' . $selectedclass . '">' . __( "Animation Preview", "ts_visual_composer_extend" ) . '</span>';
                    $output .= '</div>';
                $output .= '</div>';
                return $output;
            }
        }
    }
    if (class_exists('TS_Parameter_Animations')) {
        $TS_Parameter_Animations = new TS_Parameter_Animations();
    }
?>