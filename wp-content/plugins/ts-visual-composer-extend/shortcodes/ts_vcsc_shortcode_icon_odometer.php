<?php
	add_shortcode('TS_VCSC_Counter_Odometer', 'TS_VCSC_Counter_Odometer');
	function TS_VCSC_Counter_Odometer ($atts, $content = null) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();
		
		extract( shortcode_atts( array(			
			'odometer_start_dynamic'				=> 'false',
			'odometer_start_value'					=> '',
			'odometer_start_shortcode'				=> '',			
			
			'odometer_end_dynamic'					=> 'false',
			'odometer_end_value'					=> '',
			'odometer_end_shortcode'				=> '',
			
			'content_output'						=> 'false',
			'content_message'						=> '',
			'content_spacing'						=> 20,
			'content_family'						=> 'Default',
			'content_type'							=> '',
			'content_frame'							=> 'false',
			'content_border'						=> '',
			'content_padding'						=> 'padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;',
			
			'icon_type'								=> 'none',
			'icon_font'								=> '',
			'icon_image'							=> '',
			'icon_position'							=> 'top',
			'icon_size'								=> 64,
			'icon_color'							=> '#cccccc',
			'icon_background'						=> '#ffffff',
			'icon_frame'							=> 'false',
			'icon_border'							=> '',
			'icon_padding'							=> 'padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;',
			'icon_spacing'							=> 20,
			
			'odometer_theme'						=> 'default',
			'odometer_fillup'						=> 'true',
			'odometer_align'						=> 'center', 
			'odometer_size'							=> 36,
			'odometer_format'						=> 'none',
			'odometer_speed'						=> 4000,
			'odometer_simple'						=> 'false',
			'odometer_delay'						=> 500,
			'odometer_limit'						=> 360,
			'odometer_viewport'						=> 'true',
			
			'odometer_custom'						=> 'false',
			'odometer_family'						=> 'Default',
			'odometer_type'							=> '',
			'odometer_color'						=> '#696969',
			'odometer_background'					=> '#ffffff',
			'odometer_columnwide'					=> 'false',
			'odometer_frame'						=> 'false',
			'odometer_border'						=> '',
			'odometer_padding'						=> 'padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;',
			
			'tooltip_usage'							=> 'false',
			'tooltip_content'						=> '',
			'tooltip_position'						=> 'ts-simptip-position-top',
			'tooltip_style'							=> 'ts-simptip-style-black',
			'tooltip_animation'						=> 'swing',
			'tooltip_arrow'							=> 'true',
			'tooltip_background'					=> '#000000',
			'tooltip_border'						=> '#000000',
			'tooltip_color'							=> '#ffffff',
			'tooltip_offsetx'						=> 0,
			'tooltip_offsety'						=> 0,
			
			'viewport_class'            			=> '',
			'viewport_name'							=> '',
			'viewport_offset'						=> 'bottom-in-view',
			'viewport_delay'						=> 500,
			'viewport_limit'						=> 360,

			'margin_top'                			=> 0,
			'margin_bottom'             			=> 0,
			'el_id' 								=> '',
			'el_class'                  			=> '',
			'css'									=> '',
		), $atts ));
		
		// Frontend Editor Detection
		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
			$frontend 								= "true";
		} else {
			$frontend 								= "false";
		}
		
		// Load JS/CSS Files
		if (($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndWaypoints == "true") && (($viewport_class != "") || ($odometer_viewport == "true"))) {
			if (wp_script_is('waypoints', $list = 'registered')) {
				wp_enqueue_script('waypoints');
			} else {
				wp_enqueue_script('ts-extend-waypoints');
			}
		}
		if ($viewport_class != "") {
			wp_enqueue_style('ts-extend-animations');
		}
		wp_enqueue_style('ts-extend-odometer');
		wp_enqueue_script('ts-extend-odometer');
		wp_enqueue_script('ts-visual-composer-extend-front');
		
		// Image Icon Retrieval
		if (($icon_type == "image") && ($icon_image != '')) {
			$icon_path 								= wp_get_attachment_image_src($icon_image, 'large');
			$icon_path								= $icon_path[0];
		} else {
			$icon_path								= '';
		}
		
		// Load Google Fonts
		if ($odometer_family == "Default") {
			if ($odometer_theme == "digital") {
				wp_enqueue_style('ts-extend-font-wallpoet');
				$odometer_custom					= "false";
			} else if ($odometer_theme == "car") {
				wp_enqueue_style('ts-extend-font-arimo');
				$odometer_custom					= "false";
			} else if ($odometer_theme == "slot-machine") {
				wp_enqueue_style('ts-extend-font-rye');
				$odometer_custom					= "false";
			} else if ($odometer_theme == "train-station") {
				wp_enqueue_style('ts-extend-font-economica');
				$odometer_custom					= "false";
			}
		}
		
		$output										= '';
		$styles										= '';
		$randomizer									= mt_rand(999999, 9999999);
		
		// Create Random Element ID
		if (!empty($el_id)) {
			$odometer_id							= $el_id;
		} else {
			$odometer_id							= 'ts-odometer-counter-' . $randomizer;
		}
		
		// Format Adjustment
		if (($odometer_format == "none") || ($odometer_format == "")) {
			$odometer_format						= "d";
		} else if ($odometer_format == "comma") {
			$odometer_format						= "(,ddd)";
		} else if ($odometer_format == "dot") {
			$odometer_format						= "(.ddd)";
		} else if ($odometer_format == "space") {
			$odometer_format						= "( ddd)";
		}

		// Process Start + End Values
		if ($odometer_start_dynamic == "true") {
			$odometer_start_value					= rawurldecode(base64_decode(strip_tags($odometer_start_shortcode)));
			$odometer_start_value					= do_shortcode($odometer_start_value);
		}		
		$odometer_start_value						= (int)$odometer_start_value;
		if ($odometer_end_dynamic == "true") {
			$odometer_end_value						= rawurldecode(base64_decode(strip_tags($odometer_end_shortcode)));
			$odometer_end_value						= do_shortcode($odometer_end_value);
		}		
		$odometer_end_value							= (int)$odometer_end_value;
		if ($odometer_start_value == "") {
			$odometer_start_value 					= 0;
		}
		if ($odometer_end_value == "") {
			$odometer_end_value 					= 0;
		}
		if ($odometer_start_value > $odometer_end_value) {
			list($odometer_end_value, $odometer_start_value) = array($odometer_start_value, $odometer_end_value);
		}
		
		// Icon Styling
		if ($icon_frame == "false") {
			$icon_padding							= '';
			$icon_border							= '';
		}
		if (($icon_type == "font") && ($icon_font != "")) {
			$icon_style								= 'font-size: ' . $icon_size . 'px; line-height: ' . $icon_size . 'px; width: auto; height: auto; text-align: ' . $odometer_align . '; color: ' . $icon_color . '; background: ' . $icon_background . ';';
		} else if (($icon_type == "image") && ($icon_path != "")) {
			$icon_style								= 'height: ' . $icon_size . 'px; width: auto; text-align: ' . $odometer_align . '; background: ' . $icon_background . ';';
		} else {
			$icon_style								= '';
		}
		
		// Message Styling
		if ($content_frame == "false") {
			$content_padding						= '';
			$content_border							= '';
		}
		
		// Odometer Alignment
		if ($odometer_align == "center") {
			$odometer_align							= 'float: none; margin: 0 auto; text-align: center;';
		} else if ($odometer_align == "left") {
			$odometer_align							= 'float: left; margin: 0; text-align: left;';
		} else if ($odometer_align == "right") {
			$odometer_align							= 'float: right; margin: 0; text-align: right;';
		}
		
		// Custom Styling
		if ($odometer_custom == "false") {
			$odometer_style							= $odometer_align;
		} else {
			if ($odometer_frame == "false") {
				$odometer_padding					= '';
				$odometer_border					= '';
			}
			if (strpos($odometer_family, 'Default') === false) {
				$odometer_google					= TS_VCSC_GetFontFamily($odometer_id . " .ts-odometer-counter-message", $odometer_family, $odometer_type, false, true, false);
			} else {
				$odometer_google					= '';
			}
			$odometer_style							= $odometer_align . ' color: ' . $odometer_color . '; background: ' . $odometer_background . '; ' . $odometer_padding . ' ' . $odometer_border . ' ' . $odometer_google . ' ' . ($odometer_columnwide == "true" ? "width: 100%;" : "");
		}
		
		// Tooltip	
		if (($tooltip_usage == "true") && (strip_tags($tooltip_content) != '')) {
			$tooltip_position						= TS_VCSC_TooltipMigratePosition($tooltip_position);
			$tooltip_style							= TS_VCSC_TooltipMigrateStyle($tooltip_style);
			wp_enqueue_style('ts-extend-tooltipster');
			wp_enqueue_script('ts-extend-tooltipster');	
			$Tooltip_Content						= 'data-tooltipster-html="true" data-tooltipster-title="" data-tooltipster-text="' . strip_tags($tooltip_content) . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="' . $tooltip_arrow . '" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="' . $tooltip_animation . '" data-tooltipster-trigger="hover" data-tooltipster-background="' . $tooltip_background . '" data-tooltipster-border="' . $tooltip_border . '" data-tooltipster-color="' . $tooltip_color . '" data-tooltipster-offsetx="' . $tooltip_offsetx . '" data-tooltipster-offsety="' . $tooltip_offsety . '"';
			$Tooltip_Class							= 'ts-has-tooltipster-tooltip';
		} else {
			$Tooltip_Content						= '';
			$Tooltip_Class							= '';
		}
		
		// Viewport Animation
		if ($viewport_class != '') {
			$viewport_animation						= 'data-viewport-frontend="' . $frontend . '" data-viewport-use="' . ($viewport_class != "" ? "true" : "false") . '" data-viewport-limit="' . $viewport_limit . '" data-viewport-class="' . $viewport_class . '" data-viewport-opacity="1" data-viewport-delay="' . $viewport_delay . '" data-viewport-offset="' . $viewport_offset . '"';
			$viewport_classname						= 'ts-has-viewport-animation';
			$odometer_delay							= 2000 + $odometer_delay + $viewport_delay;
		} else {	
			$viewport_animation 					= '';
			$viewport_classname						= '';
		}
		
		// Visual Composer Override
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 								= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-odometer-counter-wrapper ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Counter_Odometer', $atts);
		} else {
			$css_class								= 'ts-odometer-counter-wrapper ' . $el_class;
		}
		
		// Create Data Attributes
		$odometer_data								= 'data-theme="' . $odometer_theme . '" data-format="' . $odometer_format . '" data-value-start="' . $odometer_start_value . '" data-value-end="' . $odometer_end_value . '" data-duration="' . $odometer_speed . '"';
		$odometer_data								.= ' data-fillup="' . $odometer_fillup . '" data-viewport="' . $odometer_viewport . '" data-simple="' . $odometer_simple . '" data-limit="' . $odometer_limit . '" data-delay="' . $odometer_delay . '"';

		// Create Final Output
		$output .= '<div id="' . $odometer_id . '" class="' . $css_class . ' ' . $viewport_classname . ' ' . $Tooltip_Class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . ';px; text-align: ' . $odometer_align . ';" ' . $Tooltip_Content . ' ' . $viewport_animation . '>';
			if (($icon_type != '') && ($icon_position == "top")) {
				if (($icon_type == "font") && ($icon_font != "")) {
					$output .= '<div class="ts-odometer-counter-graphic" style="margin-bottom: ' . $icon_spacing . 'px;">';
						$output .= '<i class="ts-odometer-counter-icon ts-font-icon ' . $icon_font . '" style="' . $icon_padding . ' ' . $icon_border . ' ' . $icon_style . '"></i>';
					$output .= '</div>';
				} else if (($icon_type == "image") && ($icon_path != "")) {
					$output .= '<div class="ts-odometer-counter-graphic" style="margin-bottom: ' . $icon_spacing . 'px;">';
						$output .= '<img class="ts-odometer-counter-icon ' . $icon_font . '" src="' . $icon_path . '" style="' . $icon_padding . ' ' . $icon_border . ' ' . $icon_style . '">';
					$output .= '</div>';
				}
			}			
			$output .= '<div id="ts-odometer-counter-element-' . $randomizer . '" class="ts-odometer-counter-element clearFixMe" style="font-size: ' . $odometer_size . 'px; ' . $odometer_style . '" ' . $odometer_data . '>';
				$output .= $odometer_start_value;
			$output .= '</div>';
			if (($icon_type != '') && ($icon_position == "bottom")) {
				if (($icon_type == "font") && ($icon_font != "")) {
					$output .= '<div class="ts-odometer-counter-graphic" style="margin-top: ' . $icon_spacing . 'px;">';
						$output .= '<i class="ts-odometer-counter-icon ts-font-icon ' . $icon_font . '" style="' . $icon_padding . ' ' . $icon_border . ' ' . $icon_style . '"></i>';
					$output .= '</div>';
				} else if (($icon_type == "image") && ($icon_path != "")) {
					$output .= '<div class="ts-odometer-counter-graphic" style="margin-top: ' . $icon_spacing . 'px;">';
						$output .= '<img class="ts-odometer-counter-icon ' . $icon_font . '" src="' . $icon_path . '" style="' . $icon_padding . ' ' . $icon_border . ' ' . $icon_style . '">';
					$output .= '</div>';
				}
			}	
			if (($content_output == 'true') && ($content_message != "")) {
				$output .= '<div id="ts-odometer-counter-message-' . $randomizer . '" class="ts-odometer-counter-message" style="margin-top: ' . $content_spacing . 'px; ' . $content_padding . ' ' . $content_border . '">';
					$output .= rawurldecode(base64_decode(strip_tags($content_message)));
				$output .= '</div>';
			}
		$output .= '</div>';
		
		echo $output;
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>