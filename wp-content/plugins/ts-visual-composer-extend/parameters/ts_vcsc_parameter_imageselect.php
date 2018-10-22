<?php
    /*
     No Additional Setting Options
    */
    if (!class_exists('TS_Parameter_ImageSelect')) {
        class TS_Parameter_ImageSelect {
            function __construct() {	
                if (function_exists('vc_add_shortcode_param')) {
					vc_add_shortcode_param('image_selector', array(&$this, 'image_selector_setting_field'));
				} else if (function_exists('add_shortcode_param')) {
                    add_shortcode_param('image_selector', array(&$this, 'image_selector_setting_field'));
				}
            }        
            function image_selector_setting_field($settings, $value){
                $param_name     	= isset($settings['param_name']) ? $settings['param_name'] : '';
                $type           	= isset($settings['type']) ? $settings['type'] : '';
				$template			= isset($settings['template']) ? $settings['template'] : 'alignfull';				
				$data_alignbasic 	= array("left", "center", "right");
				$data_alignfull 	= array("left", "center", "right", "justify");
				$data_float			= array("left", "center", "right");
				$data_positions		= array("top", "bottom", "left", "right");
				$data_vertical		= array("top", "middle", "bottom");
				$data_horizontal	= array("left", "middle", "right");
				if ($template != "") {
					$data_template	= ${'data_' . $template};
				} else {
					$data_template	= array();
				}
				$randomizer			= rand(100000, 999999);
                $output         	= '';
				$output .= '<div id="ts-image-select-container-' . $randomizer . '" class="ts-image-select-container">';
					$output .= '<input id="ts-image-select-value-' . $randomizer . '" class="ts-image-select-value wpb_vc_param_value ' . $param_name . ' ' . $type . '" value="' . $value . '" name="' . $param_name . '" type="hidden" style="display: none;"/>';
					foreach ($data_template as $align) {
						$output .= '<label id="'. $param_name .'_' . $align . '" class="ts-image-select-label ts-image-select-tooltip-holder">';
							$output .= '<input type="checkbox" class="wpb_vc_param_value" name="'. $param_name .'_' . $align . '" value="' . $align . '" ' . checked($value, $align) . '/>';
							$output .= '<span class="ts-image-select-tooltip">' . ucfirst($align) . '</span>';
							$output .= '<span class="ts-align-image-selector ts-align-block-' . $align . '" alt="' . $align . '"></span>';
						$output .= '</label>';
					}
				$output .= '</div>';
                return $output;
            }
        }
    }
    if (class_exists('TS_Parameter_ImageSelect')) {
        $TS_Parameter_ImageSelect = new TS_Parameter_ImageSelect();
    }
?>