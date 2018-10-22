<?php
    /*
     No Additional Setting Options
    */
    if (!class_exists('TS_Parameter_HiddenTextArea')) {
        class TS_Parameter_HiddenTextArea {
            function __construct() {	
                if (function_exists('vc_add_shortcode_param')) {
					vc_add_shortcode_param('hidden_textarea', array(&$this, 'hiddentextarea_setting_field'));
				} else if (function_exists('add_shortcode_param')) {
                    add_shortcode_param('hidden_textarea', array(&$this, 'hiddentextarea_setting_field'));
				}
            }        
            function hiddentextarea_setting_field($settings, $value){
                $param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
                $type           = isset($settings['type']) ? $settings['type'] : '';
                $radios         = isset($settings['options']) ? $settings['options'] : '';
				$randomizer		= rand(100000, 999999);
                $output 		= '';
				$output .= '<div id="ts-hidden-textarea-wrapper-' . $randomizer . '" class="ts-hidden-textarea-wrapper" data-name="' . $param_name . '" style="display: none;">';
					$output .= '<textarea name="' . $param_name . '" id="' . $param_name . '" class="wpb_vc_param_value ts_shortcode_hidden ' . $param_name . ' ' . $type . '" style="display: none !important;">' . $value . '</textarea>';
				$output .= '</div>';
			    return $output;
            }  
        }
    }
    if (class_exists('TS_Parameter_HiddenTextArea')) {
        $TS_Parameter_HiddenTextArea = new TS_Parameter_HiddenTextArea();
    }
?>