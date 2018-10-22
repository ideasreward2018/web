<?php
	add_shortcode('TS-VCSC-Circliful', 'TS_VCSC_Circliful_Function');
	function TS_VCSC_Circliful_Function ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();		
		
		// Load JS/CSS Files
		wp_enqueue_script('ts-extend-countup');
		wp_enqueue_script('ts-extend-circliful');
		wp_enqueue_style('ts-extend-animations');
		wp_enqueue_style('ts-visual-composer-extend-front');
		wp_enqueue_script('ts-visual-composer-extend-front');
		
		extract( shortcode_atts( array(
			'color_foreground'					=> '#117d8b',
			'color_background'					=> '#eeeeee',
			'circle_border'						=> 'default',
			'circle_fill'						=> 'false',
			'circle_inside'						=> '#ffffff',
			'circle_half'						=> 'false',
			'circle_responsive'					=> 'true',
			'circle_maxsize'					=> 250,
			'circle_dimension'					=> 200,
			'circle_thickness'					=> 15,
			
			'circle_percent_by_shortcode'		=> 'false',
			'circle_percent'					=> 15,
			'circle_percent_shortcode'			=> '',
			'circle_percent_function'			=> '',
			
			'circle_speed'						=> 0.5,
			
			'circle_value_text_use_percent'		=> 'false',
			'circle_value_text_by_shortcode'	=> 'false',
			'circle_value_text'					=> '',
			'circle_value_text_shortcode'		=> '',
			'circle_value_text_function'		=> '',
			
			'circle_value_pre'					=> '',
			'circle_value_post'					=> '',
			'circle_value_info'					=> '',			
			'circle_value_group'				=> ',',
			'circle_value_seperator'			=> '.',
			'circle_value_decimals'				=> 0,
			
			'circle_font_size'					=> 30,
			'circle_font_color'					=> '#676767',
			'circle_font_family'				=> 'Default',
			'circle_font_type'					=> '',
			'circle_font_weight'				=> 'inherit',
			'circle_info_size'					=> 15,
			'circle_info_color'					=> '#999999',
			'circle_info_family'				=> 'Default',
			'circle_info_type'					=> '',
			'circle_info_weight'				=> 'inherit',
			
			'circle_icon_replace'				=> 'false',
			'circle_icon'						=> '',
			'circle_image'						=> '',
			'circle_icon_position'				=> 'left',
			'circle_icon_color'					=> '#dddddd',
			'circle_icon_size'					=> 30,
			
			'circle_counter_viewport'			=> 'true',
			'circle_counter_offset'				=> 'full', // full, top, bottom
			'circle_counter_delay'				=> 0,
			'circle_counter_repeat'				=> 'false',
			
			'tooltip_encoded'					=> '',
			'tooltip_position'					=> 'ts-simptip-position-top',
			'tooltip_style'						=> 'ts-simptip-style-black',
			'tooltipster_offsetx'				=> 0,
			'tooltipster_offsety'				=> 0,
			
			'margin_top'						=> 0,
			'margin_bottom'						=> 0,
			'el_id' 							=> '',
			'el_class' 							=> '',
			'css'								=> '',
		), $atts ));
		
		$randomizer								= mt_rand(999999, 9999999);
		
		if (!empty($el_id)) {
			$circliful_id						= $el_id;
		} else {
			$circliful_id						= 'ts-vcsc-circliful-' . $randomizer;
		}
		
		$output 								= '';
		$styles									= '';
		$inline									= TS_VCSC_FrontendAppendCustomRules('style');

		if ($circle_fill == "true") {
			$circliful_colors					= 'data-fgcolor="' . $color_foreground . '" data-bgcolor="' . $color_background . '" data-fill="' . $circle_inside . '"';
		} else {
			$circliful_colors					= 'data-fgcolor="' . $color_foreground . '" data-bgcolor="' . $color_background . '"';
		}
		
		// Check for Match in Shortcode
		if (($circle_percent_by_shortcode == "true") && ($circle_value_text_by_shortcode == "true")) {
			if ((rawurldecode(base64_decode(strip_tags($circle_percent_shortcode)))) == (rawurldecode(base64_decode(strip_tags($circle_value_text_shortcode))))) {
				$circle_percent					= rawurldecode(base64_decode(strip_tags($circle_percent_shortcode)));
				$circle_value_text				= $circle_percent;
				// Circle Percent as Shortcode{
				$circle_percent					= do_shortcode($circle_percent);
				$circle_percent					= (int)$circle_percent;
				// Label Value as Shortcode
				$circle_value_text				= $circle_percent;
			} else {
				$circle_percent					= rawurldecode(base64_decode(strip_tags($circle_percent_shortcode)));
				$circle_value_text				= rawurldecode(base64_decode(strip_tags($circle_value_text_shortcode)));
				// Circle Percent as Shortcode
				$circle_percent					= do_shortcode($circle_percent);
				$circle_percent					= (int)$circle_percent;
				// Label Value as Shortcode			
				$circle_value_text				= do_shortcode($circle_value_text);
				$circle_value_text				= (int)$circle_value_text;
			}
		} else if (($circle_percent_by_shortcode == "true") || ($circle_value_text_by_shortcode == "true")) {
			$circle_percent						= rawurldecode(base64_decode(strip_tags($circle_percent_shortcode)));
			$circle_value_text					= rawurldecode(base64_decode(strip_tags($circle_value_text_shortcode)));			
			// Circle Percent as Shortcode
			if ($circle_percent_by_shortcode == "true") {
				$circle_percent					= do_shortcode($circle_percent);
				$circle_percent					= (int)$circle_percent;
			}
			// Label Value as Shortcode
			if (!empty($circle_value_text_shortcode)) {				
				$circle_value_text				= do_shortcode($circle_value_text);
				$circle_value_text				= (int)$circle_value_text;
			} else {
				$circle_value_text				= '';
			}
		}

		// Label Value as Shortcode
		if ($circle_value_text_use_percent == "true") {
			$circle_value_text					= $circle_percent;
		} else {
			if ($circle_value_text_by_shortcode == "false") {
				if (!empty($circle_value_text)) {
					$circle_value_text			= floatval($circle_value_text);
				} else {
					$circle_value_text			= '';
				}
			}
		}
		
		if (empty($circle_value_text)) {
			$circle_value_text					= '';
		}
		if (empty($circle_percent)) {
			$circle_percent						= 0;
		}
		
		if (!empty($circle_value_info)) {
			$circliful_content					= 'data-animationstep="' . $circle_speed . '" data-viewport="' . $circle_counter_viewport . '" data-offset="' . $circle_counter_offset . '" data-delay="' . $circle_counter_delay . '" data-repeat="' . $circle_counter_repeat . '" data-text="' . $circle_value_text . '" data-seperator="' . $circle_value_seperator . '" data-decimals="' . TS_VCSC_numberOfDecimals(floatval($circle_value_text)) . '" data-prefix="' . $circle_value_pre . '" data-postfix="' . $circle_value_post . '" data-group="' . $circle_value_group . '" data-info="' . $circle_value_info . '"';
		} else {
			$circliful_content					= 'data-animationstep="' . $circle_speed . '" data-viewport="' . $circle_counter_viewport . '" data-offset="' . $circle_counter_offset . '" data-delay="' . $circle_counter_delay . '" data-repeat="' . $circle_counter_repeat . '" data-text="' . $circle_value_text . '" data-seperator="' . $circle_value_seperator . '" data-decimals="' . TS_VCSC_numberOfDecimals(floatval($circle_value_text)) . '" data-prefix="' . $circle_value_pre . '" data-postfix="' . $circle_value_post . '" data-group="' . $circle_value_group . '" data-info=""';
		}
		
		if ($circle_half == "false") {
			$circliful_half						= 'data-type="full"';
		} else {
			$circliful_half						= 'data-type="half"';
		}
		
		if ($circle_icon_replace == "false") {
			if (!empty($circle_icon)) {
				$circliful_icon					= 'data-icon="' . $circle_icon . '" data-iconsize="' . $circle_icon_size . '" data-iconposition="' . $circle_icon_position . '" data-iconcolor="' . $circle_icon_color . '"';
			} else {
				$circliful_icon					= '';
			}
			$circliful_image					= '';
		} else if ($circle_icon_replace == "true") {
			if (!empty($circle_image)) {
				$icon_image_path 				= wp_get_attachment_image_src($circle_image, 'full');
				$circliful_image				= 'data-image="' . $icon_image_path[0] . '" data-iconsize="' . $circle_icon_size . '" data-iconposition="' . $circle_icon_position . '" data-iconcolor=""';
			} else {
				$circliful_image				= '';
			}
			$circliful_icon						= '';
		}
		
		// Text Fonts
		if (strpos($circle_font_family, 'Default') === false) {
			$google_font_circle					= TS_VCSC_GetFontFamily($circliful_id . " .circle-text-half", $circle_font_family, $circle_font_type, false, true, false);
		} else {
			$google_font_circle					= '';
		}
		if (strpos($circle_info_family, 'Default') === false) {
			$google_font_info					= TS_VCSC_GetFontFamily($circliful_id . " .circle-info-half", $circle_info_family, $circle_info_type, false, true, false);
		} else {
			$google_font_info					= '';
		}
		
		// Text Styles
		if ($inline == "false") {
			$styles .= '<style id="' . $circliful_id . '-styles" type="text/css">';
		}
			$styles .= 'body #' . $circliful_id . '.ts-circliful-counter .circle-text,';
			$styles .= 'body #' . $circliful_id . '.ts-circliful-counter .circle-text span {';
				if ($google_font_circle != "") {
					$styles .= $google_font_circle;
				}
				$styles .= 'font-weight: ' . $circle_font_weight . ';';
			$styles .= '}';		
			$styles .= 'body #' . $circliful_id . '.ts-circliful-counter .circle-info-half {';
				if ($google_font_info != "") {
					$styles .= $google_font_info;
				}
				$styles .= 'font-weight: ' . $circle_info_weight . ';';
			$styles .= '}';
		if ($inline == "false") {
			$styles .= '</style>';
		}
		if (($styles != "") && ($inline == "true")) {
			wp_add_inline_style('ts-visual-composer-extend-custom', TS_VCSC_MinifyCSS($styles));
		}

		// Tooltip
		$tooltip_position						= TS_VCSC_TooltipMigratePosition($tooltip_position);
		$tooltip_style							= TS_VCSC_TooltipMigrateStyle($tooltip_style);
		$icon_tooltipclasses					= 'ts-has-tooltipster-tooltip';
		if (strlen($tooltip_encoded) != 0) {
			wp_enqueue_style('ts-extend-tooltipster');
			wp_enqueue_script('ts-extend-tooltipster');	
			$icon_tooltipclasses				= " ts-has-tooltipster-tooltip";
			$icon_tooltipcontent 				= 'data-tooltipster-html="true" data-tooltipster-title="" data-tooltipster-text="' . strip_tags($tooltip_encoded) . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="swing" data-tooltipster-trigger="hover" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . $tooltipster_offsety . '"';
		} else {
			$icon_tooltipclasses				= "";
			$icon_tooltipcontent				= "";
		}	
		
		// Visual Composer Override
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 							= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-circliful-counter ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS-VCSC-Circliful', $atts);
		} else {
			$css_class							= 'ts-circliful-counter ' . $el_class;
		}

		// Final Output
		$output .= '<div id="' . $circliful_id . '-parent" class="ts-circliful-counter-parent ' . $icon_tooltipclasses . '" ' . $icon_tooltipcontent . ' style="width: 100%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
			if (($styles != "") && ($inline == "false")) {
				$output .= TS_VCSC_MinifyCSS($styles);
			}
			$output .= '<div id="' . $circliful_id . '" data-id="' . $circliful_id . '" class="' . $css_class . '" data-border="' . $circle_border . '" data-responsive="' . $circle_responsive . '" data-fontsize="' . $circle_font_size . '" ' . $circliful_colors . ' data-fontcolor="' . $circle_font_color . '" data-infosize="' . $circle_info_size . '" data-infocolor="' . $circle_info_color . '" ' . $circliful_content . ' ' . $circliful_half . ' ' . $circliful_icon . ' ' . $circliful_image . ' data-triggered="false" data-dimension="' . $circle_dimension . '" data-maxsize="' . $circle_maxsize . '" data-width="' . $circle_thickness . '" data-percent="' . $circle_percent . '" data-percent-view="' . $circle_percent . '"></div>';
		$output .= '</div>';
	
		echo $output;
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>