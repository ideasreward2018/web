<?php
	add_shortcode('TS_VCSC_Title_Morphed', 'TS_VCSC_Title_Morphed_Function');
	function TS_VCSC_Title_Morphed_Function ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();

		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndWaypoints == "true") {
			if (wp_script_is('waypoints', $list = 'registered')) {
				wp_enqueue_script('waypoints');
			} else {
				wp_enqueue_script('ts-extend-waypoints');
			}
		}
		wp_enqueue_style('ts-extend-animations');
		wp_enqueue_script('ts-extend-morphtext');
		wp_enqueue_style('ts-visual-composer-extend-front');
		wp_enqueue_script('ts-visual-composer-extend-front');

		extract( shortcode_atts( array(
			// Content Settings
			'title_routine'				=> 'morphext',
			'title_preuse'				=> "false",
			'title_prefix'				=> '',
			'title_strings'				=> '',
			'title_postuse'				=> 'false',
			'title_postfix'				=> '',
			// Animation Settings
			'title_animation1'			=> 'ts-viewport-css-fadeInUp',
			'title_connector1'			=> '',
			'title_animation2'			=> 'ts-viewport-css-fadeOutUp',
			'title_connector2'			=> '',
			'title_viewport'			=> 'true',
			'title_effect'				=> 'false',
			'title_loops'				=> 0,
			'title_wait'				=> 0,
			'title_speed'				=> 2000,
			// Mobile Settings
			'switch_alternate'			=> 'false',
			'switch_title'				=> '',
			'switch_trigger'			=> 480,
			'switch_wrapper'			=> 'div',
			// Style Settings
			'font_align'				=> 'center',
			'font_color'				=> '#000000',
			'font_weight'				=> 'inherit',
			'font_transform'			=> 'none',
			'font_decoration'			=> 'none',
			'font_size'					=> 24,
			'font_family'				=> 'Default:regular',
			'font_type'					=> 'default',	
			'prefix_custom'				=> 'false',
			'prefix_size'				=> 75,
			'prefix_color'				=> '#000000',
			'prefix_weight'				=> 'inherit',
			'prefix_transform'			=> 'none',
			'prefix_decoration'			=> 'none',
			'prefix_family'				=> '',
			'prefix_type'				=> '',
			'prefix_line'				=> 'inline-block',
			'prefix_vertical'			=> 'bottom',
			'prefix_offset'				=> 0,			
			'postfix_custom'			=> 'false',
			'postfix_size'				=> 75,
			'postfix_color'				=> '#000000',
			'postfix_weight'			=> 'inherit',
			'postfix_transform'			=> 'none',
			'postfix_decoration'		=> 'none',
			'postfix_family'			=> '',
			'postfix_type'				=> '',
			'postfix_line'				=> 'inline-block',
			'postfix_vertical'			=> 'bottom',
			'postfix_offset'			=> 0,
			'switch_custom'				=> 'false',
			'switch_align'				=> 'center',
			'switch_color'				=> '#000000',
			'switch_weight'				=> 'inherit',
			'switch_transform'			=> 'none',
			'switch_decoration'			=> 'none',
			'switch_size'				=> 24,
			'switch_family'				=> '',
			'switch_type'				=> '',	
			// Other Settings
			'margin_top'                => 0,
			'margin_bottom'             => 0,
			'el_id' 					=> '',
			'el_class'                  => '',
			'css'						=> '',
		), $atts ));
		
		$output 						= '';
		
		if (!empty($el_id)) {
			$titlemorphed_id			= $el_id;
		} else {
			$titlemorphed_id			= 'ts-title-morphed-container-' . mt_rand(999999, 9999999);
		}
		
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 					= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Title_Morphed', $atts);
		} else {
			$css_class					= '';
		}
		
		$title_raw 						= str_replace(",", ", ", $title_strings);
		$title_raw						= TS_VCSC_ConvertPlaceholderComma($title_raw);
		if (($switch_alternate == "true") && ($switch_title != "")) {
			$title_fixed 				= $switch_title;
		} else {
			$title_fixed 				= $title_prefix . $title_raw . $title_postfix;
		}
		
		$title_data						= 'data-routine="' . $title_routine . '" data-viewport="' . $title_viewport . '" data-loops="' . $title_loops . '" data-effect="' . $title_effect . '" data-animation-1="' . $title_animation1 . '" data-animation-2="' . $title_animation2 . '" data-wait="' . $title_wait . '" data-speed="' . $title_speed . '" ';
		$title_switch 					= 'data-switch-trigger="' . $switch_trigger . '" data-switch-title="' . $title_fixed . '" data-switch-wrapper="' . $switch_wrapper . '"';
		
		// Font Definitions
		if (strpos($font_family, 'Default') === false) {
			$google_font 				= TS_VCSC_GetFontFamily($titlemorphed_id, $font_family, $font_type, false, true, false);
		} else {
			$google_font				= '';
		}
		if (strpos($prefix_family, 'Default') === false) {
			$google_prefix 				= TS_VCSC_GetFontFamily($titlemorphed_id, $prefix_family, $prefix_type, false, true, false);
		} else {
			$google_prefix				= '';
		}
		if (strpos($postfix_family, 'Default') === false) {
			$google_postfix 			= TS_VCSC_GetFontFamily($titlemorphed_id, $postfix_family, $postfix_type, false, true, false);
		} else {
			$google_postfix				= '';
		}
		if (strpos($switch_family, 'Default') === false) {
			$google_switch				= TS_VCSC_GetFontFamily($titlemorphed_id, $switch_family, $switch_type, false, true, false);
		} else {
			$google_switch				= '';
		}
		
		// Final Style Definitions
		$style_title					= $google_font . 'text-align: ' . $font_align . '; font-size: ' . $font_size . 'px; color: ' . $font_color .'; font-weight: ' . $font_weight . '; text-transform: ' . $font_transform . '; text-decoration: ' . $font_decoration . ';';
		if ($prefix_custom == "false") {
			$style_prefix				= $style_title;
		} else {
			if (($prefix_line == "inline-block") && ($prefix_offset != 0)) {
				$offset_prefix			= (($prefix_vertical == "top" || $prefix_vertical == "middle") ? "top: " : "bottom: ") . $prefix_offset . 'px;';
			} else {
				$offset_prefix			= '';
			}
			$style_prefix				= $google_prefix . 'display: ' . $prefix_line . '; font-size: ' . ($font_size / 100 * $prefix_size) . 'px; vertical-align: ' . $prefix_vertical . '; ' . $offset_prefix . ' color: ' . $prefix_color . '; font-weight: ' . $prefix_weight . '; text-transform: ' . $prefix_transform . '; text-decoration: ' . $prefix_decoration . '; text-align: ' . $font_align . ';';
		}
		if ($postfix_custom == "false") {
			$style_postfix				= $style_title;
		} else {
			if (($postfix_line == "inline-block") && ($postfix_offset != 0)) {
				$offset_postfix			= (($postfix_vertical == "top" || $postfix_vertical == "middle") ? "top: " : "bottom: ") . $postfix_offset . 'px;';
			} else {
				$offset_postfix			= '';
			}
			$style_postfix				= $google_postfix . 'display: ' . $postfix_line . '; font-size: ' . ($font_size / 100 * $postfix_size) . 'px; vertical-align: ' . $postfix_vertical . '; ' . $offset_postfix . ' color: ' . $postfix_color . '; font-weight: ' . $postfix_weight . '; text-transform: ' . $postfix_transform . '; text-decoration: ' . $postfix_decoration . '; text-align: ' . $font_align . ';';
		}
		if ($switch_custom == "false") {
			$style_switch				= $style_title;
		} else {
			$style_switch				= $google_switch . 'text-align: ' . $switch_align . '; font-size: ' . $switch_size . 'px; color: ' . $switch_color .'; font-weight: ' . $switch_weight . '; text-transform: ' . $switch_transform . '; text-decoration: ' . $switch_decoration . ';';
		}

		// Create Final Output
		$output .= '<div id="' . $titlemorphed_id . '" class="ts-title-morphed-container" ' . $title_data . ' ' . $title_switch . ' style="display: none; text-align: ' . $font_align . '; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
			// Add Prefix String
			if (($title_preuse == "true") && ($title_prefix != "")) {
				$output .= '<' . ($prefix_line == "inline-block" ? "span" : "div") . ' class="ts-title-morphed-prefix" style="' . $style_prefix . '">' . $title_prefix . '</' . ($prefix_line == "inline-block" ? "span" : "div") . '>';
			}
			// Add Animated Segments
			$title_strings				= explode(",", $title_strings);
			$title_strings				= TS_VCSC_ConvertPlaceholderComma($title_strings);
			$output .= '<ul class="ts-title-morphed-string" style="' . $style_title . '">';
				foreach($title_strings as $item) {
					$output .= '<li>' . $item . '</li>';
				}
			$output .= '</ul>';
			// Add Postfix String
			if (($title_postuse == "true") && ($title_postfix != "")) {
				$output .= '<' . ($postfix_line == "inline-block" ? "span" : "div") . ' class="ts-title-morphed-postfix" style="' . $style_postfix . '">' . $title_postfix . '</' . ($postfix_line == "inline-block" ? "span" : "div") . '>';
			}
			// Add Mobile/Switch String
			$output .= '<'. $switch_wrapper . ' class="ts-title-morphed-switch" style="display: none; ' . $style_switch . '">';
				if ($switch_title != "") {
					$output .= $switch_title;
				} else {
					$output .= $title_prefix . $title_raw . $title_postfix;
				}
			$output .= '</'. $switch_wrapper . '>';
		$output .= '</div>';
		echo $output;
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>