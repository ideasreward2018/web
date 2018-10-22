<?php
	add_shortcode('TS_VCSC_Icon_Feature_Box', 'TS_VCSC_Icon_Feature_Box_Function');
	function TS_VCSC_Icon_Feature_Box_Function ($atts, $content = null) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();

		wp_enqueue_style('ts-visual-composer-extend-front');
		wp_enqueue_script('ts-visual-composer-extend-front');
	
		extract( shortcode_atts( array(
			'box_theme'						=> 'blue',
			'box_icon'						=> '',
			'box_title'						=> '',
			// Font Settings
			'font_title_family'				=> 'Default',
			'font_title_type'				=> '',
			'font_title_size'				=> 18,
			'font_content_family'			=> 'Default',
			'font_content_type'				=> '',
			'font_content_size'				=> 14,
			// Other Settings
			'content_wpautop'				=> 'false',
			'margin_top'                	=> 0,
			'margin_bottom'             	=> 0,
			'el_id' 						=> '',
			'el_class'                  	=> '',
			'css'							=> '',
		), $atts ));
		
		$output 							= '';
		$wpautop 							= ($content_wpautop == "true" ? true : false);
		
		if (!empty($el_id)) {
			$info_box_id					= $el_id;
		} else {
			$info_box_id					= 'ts-icon-feature-box-' . mt_rand(999999, 9999999);
		}
		
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Icon_Info_Box', $atts);
		} else {
			$css_class						= '';
		}

		$output .= '<div id="' . $info_box_id . '" class="feature-left-icon" style="">';
			if (function_exists('wpb_js_remove_wpautop')){
				$output .= '<div class="" style="">' . wpb_js_remove_wpautop(do_shortcode($content), $wpautop) . '</div>';
			} else {
				$output .= '<div class="" style="">' . do_shortcode($content) . '</div>';
			}
			$output .= '<div class="clearFixMe"></div>';
		$output .= '</div>';
		
		echo $output;
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>