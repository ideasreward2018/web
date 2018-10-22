<?php
	add_shortcode('TS_VCSC_Title_Ticker', 'TS_VCSC_Title_Ticker_Function');
	function TS_VCSC_Title_Ticker_Function ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();

		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndWaypoints == "true") {
			if (wp_script_is('waypoints', $list = 'registered')) {
				wp_enqueue_script('waypoints');
			} else {
				wp_enqueue_script('ts-extend-waypoints');
			}
		}
		wp_enqueue_style('ts-font-ecommerce');
		wp_enqueue_script('ts-extend-vticker');			
		wp_enqueue_style('ts-visual-composer-extend-front');
		wp_enqueue_script('ts-visual-composer-extend-front');
	
		extract( shortcode_atts( array(
			'title_lines'				=> 'false',
			'fixed_addition'			=> 'false',
			'fixed_string'				=> '',
			'post_addition'				=> 'false',
			'post_string'				=> '',		
			'title_strings'				=> '',
			
			'viewport'					=> 'true',
			'controls'					=> 'false',
			'position'					=> 'left',
			
			'mobile'					=> 'true',
			'wrapper'					=> 'h1',
			'title_mobile'				=> '',
			
			'direction'					=> 'up',
			'reverse'					=> 'false',
			'delay'						=> 0,
			'speed'						=> 1000,
			'break'						=> 3000,			
			'showall'					=> 'false',
			'showitems'					=> 1,
			'hover'						=> 'true',

			'font_align'				=> 'left',
			'font_vertical'				=> 'bottom',
			'font_size'					=> 24,
			'font_color'				=> '#000000',
			'font_weight'				=> 'inherit',
			'font_family'				=> 'Default:regular',
			'font_type'					=> 'default',
			
			'fixed_custom'				=> 'false',
			'fixed_size'				=> 75,
			'fixed_color'				=> '#000000',
			'fixed_weight'				=> 'inherit',
			'fixed_family'				=> '',
			'fixed_type'				=> '',
			
			'post_custom'				=> 'false',
			'post_size'					=> 75,
			'post_color'				=> '#000000',
			'post_weight'				=> 'inherit',
			'post_family'				=> '',
			'post_type'					=> '',
			
			'switch_medium'				=> 768,
			'switch_small'				=> 480,
			
			'position_adjust'			=> 0,
			'margin_top'                => 0,
			'margin_bottom'             => 0,
			'el_id' 					=> '',
			'el_class'                  => '',
			'css'						=> '',
		), $atts ));
		
		$output = $notice = $visible = '';
		
		// Check for Front End Editor
		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
			$editor_frontend			= "true";
		} else {
			$editor_frontend			= "false";
		}
		
		if (!empty($el_id)) {
			$titleticker_id				= $el_id;
		} else {
			$titleticker_id				= 'ts-vcsc-title-ticker-' . mt_rand(999999, 9999999);
		}
		
		if (strpos($font_family, 'Default') === false) {
			$google_font 				= TS_VCSC_GetFontFamily($titleticker_id, $font_family, $font_type, false, true, false);
		} else {
			$google_font				= '';
		}
		if (strpos($fixed_family, 'Default') === false) {
			$google_prefix 				= TS_VCSC_GetFontFamily($titleticker_id, $fixed_family, $fixed_type, false, true, false);
		} else {
			$google_prefix				= '';
		}
		if (strpos($post_family, 'Default') === false) {
			$google_postfix 			= TS_VCSC_GetFontFamily($titleticker_id, $post_family, $post_type, false, true, false);
		} else {
			$google_postfix				= '';
		}
		
		$title_strings					= explode(",", $title_strings);
		$title_strings					= TS_VCSC_ConvertPlaceholderComma($title_strings);
		
		if (($controls == "true") && (($position == "top") || ($position == "left"))) {
			if (($font_size + 6) < 39) {
				$lineheight				= 39;				
			} else {
				$lineheight				= ($font_size + 6);
			}
		} else {
			$lineheight					= ($font_size + 6);
		}
		
		$mobile_settings				= 'data-mobile-allow="' . $mobile . '" data-mobile-wrapper="' . $wrapper . '" data-mobile-title="' . $title_mobile . '"';
		
		// Style Settings
		$line_titles					= (($font_size * 1.5) < 39 ? 39 : ($font_size * 1.5));	
		$style_setting					= $google_font . 'font-size: ' . $font_size . 'px; line-height: 1.3; vertical-align: ' . $font_vertical . '; letter-spacing: 0px; color: ' . $font_color . '; font-weight: ' . $font_weight . '; text-align: ' . $font_align . ';';
		if ($fixed_custom == "false") {
			$style_prefix				= $style_setting;
		} else {			
			$style_prefix				= $google_prefix . 'font-size: ' . ($font_size / 100 * $fixed_size) . 'px; line-height: 1.3; vertical-align: ' . $font_vertical . '; letter-spacing: 0px; color: ' . $fixed_color . '; font-weight: ' . $fixed_weight . '; text-align: ' . $font_align . ';';
		}
		if ($post_custom == "false") {
			$style_postfix				= $style_setting;
		} else {			
			$style_postfix				= $google_postfix . 'font-size: ' . ($font_size / 100 * $post_size) . 'px; line-height: 1.3; vertical-align: ' . $font_vertical . '; letter-spacing: 0px; color: ' . $post_color . '; font-weight: ' . $post_weight . '; text-align: ' . $font_align . ';';
		}
		
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 					= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Title_Ticker', $atts);
		} else {
			$css_class					= '';
		}
	
		$output .= '<div id="' . $titleticker_id . '" class="ts-title-ticker-container ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; width: 100%; ' . $style_setting . '" data-frontend="' . $editor_frontend . '" data-switchmedium="' . $switch_medium . '" data-switchsmall="' . $switch_small . '" ' . $mobile_settings . ' data-viewport="' . $viewport . '" data-alignment="' . $font_align . '" data-pretext="' . ((($fixed_addition == "true") && ($fixed_string != '')) ? 'true' : 'false') . '" data-posttext="' . ((($post_addition == "true") && ($post_string != '')) ? 'true' : 'false') . '" data-controls="' . $controls . '" data-position="' . $position . '" data-reverse="' . $reverse . '" data-direction="' . $direction . '" data-showall="' . $showall . '" data-items="' . $showitems . '" data-hover="' . $hover . '" data-delay="' . $delay . '" data-speed="' . $speed . '" data-break="' . $break . '">';
			$output .= '<div class="ts-title-ticker-mobile" style="display: none; ' . $style_setting . '">';
				$output .= '<' . $wrapper . '>' . $title_mobile . '</' . $wrapper . '>';
			$output .= '</div>';
			$output .= '<div class="ts-title-ticker-holder">';			
				if (($controls == "true") && (($position == "top") || ($position == "left"))) {
					$output .= '<div class="ts-title-ticker-controls ts-title-ticker-controls-' . $position . '" style="vertical-align: ' . $font_vertical . '; display: none;">';
						$output .= '<div class="ts-title-ticker-prev"><span class="ts-ecommerce-arrowleft5"></span></div>';
						$output .= '<div class="ts-title-ticker-next"><span class="ts-ecommerce-arrowright5"></span></div>';
						$output .= '<div class="ts-title-ticker-pause" data-active="false"><span class="ts-ecommerce-pause"></span></div>';
						$output .= '<div class="ts-title-ticker-play" data-active="true"><span class="ts-ecommerce-play"></span></div>';
					$output .= '</div>';
				}			
				if (($fixed_addition == "true") && ($fixed_string != '')) {
					$output .= '<div class="ts-title-ticker-pretext" style="' . $style_prefix . '">' . $fixed_string . '</div><div class="ts-title-ticker-filler">...</div>';
				}
				$output .= '<div class="ts-title-ticker-animated" style="line-height: 1; top: ' . $position_adjust . 'px; ' . $style_setting . '" data-ticker-initialized="false">';
					$output .= '<ul class="ts-title-ticker-list" style="">';
						foreach($title_strings as $item) {
							$output .= '<li style=""><span class="ts-title-ticker-item" style="">' . $item . '</span></li>';
						}
					$output .= '</ul>';
				$output .= '</div>';
				if (($post_addition == "true") && ($post_string != '')) {
					$output .= '<div class="ts-title-ticker-posttext" style="' . $style_postfix . '">' . $post_string . '</div><div class="ts-title-ticker-filler">...</div>';
				}
				if (($controls == "true") && (($position == "bottom") || ($position == "right"))) {
					$output .= '<div class="ts-title-ticker-controls ts-title-ticker-controls-' . $position . '" style="vertical-align: ' . $font_vertical . '; display: none;">';
						$output .= '<div class="ts-title-ticker-prev"><span class="ts-ecommerce-arrowleft5"></span></div>';
						$output .= '<div class="ts-title-ticker-next"><span class="ts-ecommerce-arrowright5"></span></div>';
						$output .= '<div class="ts-title-ticker-pause" data-active="false"><span class="ts-ecommerce-pause"></span></div>';
						$output .= '<div class="ts-title-ticker-play" data-active="true"><span class="ts-ecommerce-play"></span></div>';
					$output .= '</div>';
				}	
			$output .= '</div>';
		$output .= '</div>';		
		
		echo $output;
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>