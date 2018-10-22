<?php
	if (!class_exists('TS_Process_Lines')){
		class TS_Process_Lines {
			function __construct() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						$this->TS_VCSC_Add_ProcessLines_Element_Lean();
					} else if (function_exists('vc_map')) {
						add_action('init',									array($this, 'TS_VCSC_Add_ProcessLines_Element_Container'), 9999999);
						add_action('init',									array($this, 'TS_VCSC_Add_ProcessLines_Element_Item'), 9999999);
					}
				} else {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_ProcessLines_Element_Lean'), 9999999);
					} else if (function_exists('vc_map')) {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_ProcessLines_Element_Container'), 9999999);
						add_action('admin_init',							array($this, 'TS_VCSC_Add_ProcessLines_Element_Item'), 9999999);
					}
				}
				if ((is_admin() == false) || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAJAX == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAlways == "true")) {
					add_shortcode('TS_VCSC_ProcessLines_Item',				array($this, 'TS_VCSC_ProcessLines_Item'));
					add_shortcode('TS_VCSC_ProcessLines_Container',			array($this, 'TS_VCSC_ProcessLines_Container'));
				}
			}
			
			// Register Element(s) via LeanMap
			function TS_VCSC_Add_ProcessLines_Element_Lean() {
				vc_lean_map('TS_VCSC_ProcessLines_Container', 				array($this, 'TS_VCSC_Add_ProcessLines_Element_Container'), null);
				vc_lean_map('TS_VCSC_ProcessLines_Item', 					array($this, 'TS_VCSC_Add_ProcessLines_Element_Item'), null);
			}
			
			// Process Lines Container
			function TS_VCSC_ProcessLines_Container ($atts, $content = null){
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();
				
				// Check for Front End Editor
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					$frontend_edit					= 'true';
				} else {
					$frontend_edit					= 'false';
				}
	
				wp_enqueue_style('ts-extend-animations');
				wp_enqueue_style('ts-extend-processlines');
				wp_enqueue_style('ts-visual-composer-extend-front');
				wp_enqueue_script('ts-visual-composer-extend-front');
				
				extract( shortcode_atts( array(
					// General
					'timeline_style'				=> 'style1',					
					// Style 1
					'timeline_connector'			=> 'true',
					'timeline_dots'					=> 'true',
					'timeline_color1bar'			=> '#000000',
					'timeline_color1dot'			=> '#000000',
					'timeline_color1date'			=> '#ffffff',
					'timeline_color1back'			=> '#000000',
					'timeline_color1title'			=> '#333333',
					'timeline_color1content'		=> '#676767',
					// Style 2
					'timeline_date'					=> 'true',
					'timeline_color2bar'			=> '#E5E5E5',
					'timeline_color2shade'			=> '#E5E5E5',
					'color_odd_date'				=> '#C0C0C0',
					'color_odd_subdate'				=> '#A0A0A0',
					'color_odd_border'				=> '#C4C4C4',
					'color_odd_background'			=> '#FFFFFF',
					'color_odd_title'				=> '#929292',
					'color_odd_content'				=> '#868686',
					'color_even_date'				=> '#C0C0C0',
					'color_even_subdate'			=> '#404040',
					'color_even_border'				=> '#D8D8D8',
					'color_even_background'			=> '#F7F7F7',
					'color_even_title'				=> '#333333',
					'color_even_content'			=> '#505050',
					// Style 3
					'timeline_color3dot'			=> '#ff5050',
					'timeline_color3bar'			=> '#505050',
					'timeline_color3date'			=> '#ff5050',
					'timeline_color3title'			=> '#333333',
					'timeline_color3content'		=> '#696969',
					// Style 4
					'timeline_color4bar'			=> '#d7e4ed',
					'timeline_color4back'			=> '#fbfbfb',
					'timeline_color4shadow'			=> '#d7e4ed',
					'timeline_color4date'			=> '#676767',
					'timeline_color4title'			=> '#303e49',
					'timeline_color4content'		=> '#676767',
					'timeline_color4linka'			=> '#acb7c0',
					'timeline_color4linkb'			=> '#ffffff',
					// Layout Settings
					'timeline_alignment'			=> 'alternate',
					'timeline_switchme'				=> 'false',
					'timeline_switchat'				=> 640,					
					'timeline_switchto'				=> 'left',
					// Styling Settings
					'date_fonttype'					=> 'Default:regular',
					'date_fontmatch'				=> 'default',
					'title_fonttype'				=> 'Default:regular',
					'title_fontmatch'				=> 'default',
					'title_fontsize'				=> 18,
					'content_fonttype'				=> 'Default:regular',
					'content_fontmatch'				=> 'default',
					'content_fontsize'				=> 13,
					// Animation Settings
					'animation_trigger'				=> 'item-in-view',
					'animation_view1' 				=> '',
					'animation_view2' 				=> '',
					'animation_view3' 				=> '',
					'animation_view4' 				=> '',
					'animation_delay' 				=> 0,
					'animation_wait' 				=> 500,
					'animation_offset'				=> 'bottom-in-view',
					'animation_mobile'				=> 'false',
					// WPAutoP Callback
					'content_wpautop'				=> 'false',
					// Other
					'margin_top'                    => 0,
					'margin_bottom'                 => 0,
					'el_id' 						=> '',
					'el_class'                  	=> '',
					'css'							=> '',
				), $atts ));			
				
				$output								= '';
				$styles								= '';
				$wpautop 							= ($content_wpautop == "true" ? true : false);
				$inline								= TS_VCSC_FrontendAppendCustomRules('style');
				
				if (($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndWaypoints == "true") && ($animation_view1 != "") && ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "false")) {
					if (wp_script_is('waypoints', $list = 'registered')) {
						wp_enqueue_script('waypoints');
					} else {
						wp_enqueue_script('ts-extend-waypoints');
					}
				}
				
				if (!empty($el_id)) {
					$timeline_id					= $el_id;
				} else {
					$timeline_id					= 'ts-process-line-container-' . mt_rand(999999, 9999999);
				}
				
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_ProcessLines_Container', $atts);
				} else {
					$css_class						= '';
				}

				// Style Dependent Classes
				if ($timeline_style == 'style1') {
					if (($timeline_connector == "true") && ($timeline_dots == "true")) {
						$timeline_addclass			= "ts-timeline-1-line-starter";
					} else if (($timeline_connector == "true") && ($timeline_dots == "false")) {
						$timeline_addclass			= "ts-timeline-1-line-notlast";
					} else {
						$timeline_addclass			= "ts-timeline-1-line-skipper";
					}
				} else {
					$timeline_addclass				= "";
				}
				
				// Font Styling
				if (strpos($date_fonttype, 'Default') === false) {
					$date_default					= TS_VCSC_GetFontFamily($timeline_id, $date_fonttype, $date_fontmatch, false, true, false);
				} else {
					$date_default					= '';
				}
				if (strpos($title_fonttype, 'Default') === false) {
					$title_default					= TS_VCSC_GetFontFamily($timeline_id, $title_fonttype, $title_fontmatch, false, true, false);
				} else {
					$title_default					= '';
				}
				if (strpos($content_fonttype, 'Default') === false) {
					$content_default				= TS_VCSC_GetFontFamily($timeline_id, $content_fonttype, $content_fontmatch, false, true, false);
				} else {
					$content_default				= '';
				}
				
				$animation_data						= 'data-animation-trigger="' . $animation_trigger . '" data-animation-type1="' . $animation_view1 . '" data-animation-type2="' . $animation_view2 . '" data-animation-type3="' . $animation_view3 . '" data-animation-type4="' . $animation_view4 . '" data-animation-frontend="' . $frontend_edit . '" data-animation-offset="' . $animation_offset . '" data-animation-delay="' . $animation_delay . '" data-animation-wait="' . $animation_wait . '" data-animation-mobile="' . $animation_mobile . '"';
				if (($timeline_style == 'style1') || ($timeline_style == 'style2')) {
					if ($animation_view1 != "") {
						$animation_class			= 'ts-process-line-viewport';
					} else {
						$animation_class			= '';
					}
				} else if (($timeline_style == 'style3') || ($timeline_style == 'style4')) {
					if (((($timeline_alignment == "left") || ($timeline_alignment == "right")) && ($animation_view2 != "")) || (($timeline_alignment == "alternate") && (($animation_view3 != "") || ($animation_view4 != "")))) {
						$animation_class			= 'ts-process-line-viewport';
					} else {
						$animation_class			= '';
					}
				} else {
					$animation_class				= '';
				}
				
				// Create Styles Output
				if ($inline == "false") {
					$styles .= '<style id="' . $timeline_id . '-styles" type="text/css">';
				}
					if ($timeline_style == 'style1') {
						$styles .= '#' . $timeline_id . ' .ts-timeline-1 .ts-timeline-1-date {';
							$styles .= $date_default;
							$styles .= 'color: ' . $timeline_color1date . ';';
							$styles .= 'background: ' . $timeline_color1back . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-1 .ts-timeline-1-line,';
						$styles .= '#' . $timeline_id . ' .ts-timeline-1 .ts-timeline-1-hor-line {';
							$styles .= 'background: ' . $timeline_color1bar . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-1 .ts-timeline-1-start-point {';
							$styles .= 'border-color: ' . $timeline_color1dot . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-1 .ts-timeline-1-container .ts-timeline-1-title {';
							$styles .= $title_default;
							$styles .= 'font-size: ' . $title_fontsize . 'px;';
							$styles .= 'color: ' . $timeline_color1title . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-1 .ts-timeline-1-container .ts-timeline-1-content {';
							$styles .= $content_default;
							$styles .= 'font-size: ' . $content_fontsize . 'px;';
							$styles .= 'color: ' . $timeline_color1content . ';';
						$styles .= '}';
					}
					if ($timeline_style == 'style2') {						
						$styles .= '#' . $timeline_id . ' .ts-timeline-2:before {';
							$styles .= 'background: ' . $timeline_color2bar . ';';
						$styles .= '}';						
						if ($timeline_date == "true") {
							$styles .= '#' . $timeline_id . ' .ts-timeline-2 .ts-timeline-2-time {';
								$styles .= $date_default;
							$styles .= '}';
							$styles .= '#' . $timeline_id . ' .ts-timeline-2 > li:nth-child(odd) .ts-timeline-2-time span:first-child {';
								$styles .= 'color: ' . $color_odd_date . ';';
							$styles .= '}';
							$styles .= '#' . $timeline_id . ' .ts-timeline-2 > li:nth-child(odd) .ts-timeline-2-time span:last-child {';
								$styles .= 'color: ' . $color_odd_subdate . ';';
							$styles .= '}';							
							$styles .= '#' . $timeline_id . ' .ts-timeline-2 > li:nth-child(even) .ts-timeline-2-time span:first-child {';
								$styles .= 'color: ' . $color_even_date . ';';
							$styles .= '}';
							$styles .= '#' . $timeline_id . ' .ts-timeline-2 > li:nth-child(even) .ts-timeline-2-time span:last-child {';
								$styles .= 'color: ' . $color_even_subdate . ';';
							$styles .= '}';
						}
						$styles .= '#' . $timeline_id . ' .ts-timeline-2 .ts-timeline-2-item .ts-timeline-2-image,';
						$styles .= '#' . $timeline_id . ' .ts-timeline-2 .ts-timeline-2-item .ts-timeline-2-icon {';
							$styles .= '-webkit-box-shadow: 0 0 0 8px ' . $timeline_color2shade . ';';
							$styles .= '-moz-box-shadow: 0 0 0 8px ' . $timeline_color2shade . ';';
							$styles .= '-o-box-shadow: 0 0 0 8px ' . $timeline_color2shade . ';';
							$styles .= 'box-shadow: 0 0 0 8px ' . $timeline_color2shade . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-2 > li:nth-child(odd) .ts-timeline-2-label {';
							$styles .= 'background: ' . $color_odd_background . ';';
							$styles .= 'border-color: ' . $color_odd_border . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-2 > li:nth-child(odd) .ts-timeline-2-label:after {';
							$styles .= 'border-right-color: ' . $color_odd_border . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-2 > li:nth-child(even) .ts-timeline-2-label {';
							$styles .= 'background: ' . $color_even_background . ';';
							$styles .= 'border-color: ' . $color_even_border . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-2 > li:nth-child(even) .ts-timeline-2-label:after {';
							$styles .= 'border-right-color: ' . $color_even_border . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-2 .ts-timeline-2-label .ts-timeline-2-label-title {';
							$styles .= $title_default;
							$styles .= 'font-size: ' . $title_fontsize . 'px;';
						$styles .= '}';						
						$styles .= '#' . $timeline_id . ' .ts-timeline-2 > li:nth-child(odd) .ts-timeline-2-label .ts-timeline-2-label-title {';
							$styles .= 'color: ' . $color_odd_title . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-2 > li:nth-child(even) .ts-timeline-2-label .ts-timeline-2-label-title {';
							$styles .= 'color: ' . $color_even_title . ';';
						$styles .= '}';						
						$styles .= '#' . $timeline_id . ' .ts-timeline-2 .ts-timeline-2-label .ts-timeline-2-label-content {';
							$styles .= $content_default;
							$styles .= 'font-size: ' . $content_fontsize . 'px;';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-2 > li:nth-child(odd) .ts-timeline-2-label .ts-timeline-2-label-content {';
							$styles .= 'color: ' . $color_odd_content . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-2 > li:nth-child(even) .ts-timeline-2-label .ts-timeline-2-label-content {';
							$styles .= 'color: ' . $color_even_content . ';';
						$styles .= '}';		
					}
					if ($timeline_style == 'style3') {						
						$styles .= '#' . $timeline_id . ' .ts-timeline-3:before {';
							$styles .= 'background: ' . $timeline_color3bar . ';';
							$styles .= 'background: -moz-linear-gradient(top, rgba(255,255,255,0) 0%, ' . $timeline_color3bar . ' 10%, ' . $timeline_color3bar . ' 90%, rgba(255,255,255,0) 100%);';
							$styles .= 'background: -webkit-linear-gradient(top, rgba(255,255,255,0) 0%, ' . $timeline_color3bar . ' 10%, ' . $timeline_color3bar . ' 90%, rgba(255,255,255,0) 100%);';
							$styles .= 'background: -o-linear-gradient(top, rgba(255,255,255,0) 0%, ' . $timeline_color3bar . ' 10%, ' . $timeline_color3bar . ' 90%, rgba(255,255,255,0) 100%);';
							$styles .= 'background: -ms-linear-gradient(top, rgba(255,255,255,0) 0%, ' . $timeline_color3bar . ' 10%, ' . $timeline_color3bar . ' 90%, rgba(255,255,255,0) 100%);';
							$styles .= 'background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, ' . $timeline_color3bar . ' 10%, ' . $timeline_color3bar . ' 90%, rgba(255,255,255,0) 100%);';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-3 .ts-timeline-3-content-main .flag-wrapper:before {';
							$styles .= 'background: ' . $timeline_color3dot . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-3 .ts-timeline-3-content-main .time-wrapper {';
							$styles .= $date_default;
							$styles .= 'color: ' . $timeline_color3date . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-3 .ts-timeline-3-content-main .flag-wrapper {';
							$styles .= $title_default;
							$styles .= 'font-size: ' . $title_fontsize . 'px;';
							$styles .= 'color: ' . $timeline_color3title . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-3 .ts-timeline-3-content-main .desc-wrapper {';
							$styles .= $content_default;
							$styles .= 'font-size: ' . $content_fontsize . 'px;';
							$styles .= 'color: ' . $timeline_color3content . ';';
						$styles .= '}';
					}
					if ($timeline_style == 'style4') {
						$styles .= '#' . $timeline_id . ' .ts-timeline-4:before {';						
							$styles .= 'background: ' . $timeline_color4bar . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-4 .ts-timeline-4-content {';
							$styles .= $content_default;
							$styles .= 'background-color: ' . $timeline_color4back . ';';
							$styles .= 'color: ' . $timeline_color4content . ';';
							$styles .= '-webkit-box-shadow: 0 3px 0 ' . $timeline_color4shadow . ';';
							$styles .= '-moz-box-shadow: 0 3px 0 ' . $timeline_color4shadow . ';';
							$styles .= 'box-shadow: 0 3px 0 ' . $timeline_color4shadow . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-4.ts-timeline-4-align-left .ts-timeline-4-content:before,';
						$styles .= '#' . $timeline_id . ' .ts-timeline-4.ts-timeline-4-align-alternate .ts-timeline-4-item:nth-child(even) .ts-timeline-4-content:before {';
							$styles .= 'border-right-color: ' . $timeline_color4back . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-4.ts-timeline-4-align-right .ts-timeline-4-content:before,';
						$styles .= '#' . $timeline_id . ' .ts-timeline-4.ts-timeline-4-align-alternate .ts-timeline-4-item:nth-child(odd) .ts-timeline-4-content:before {';
							$styles .= 'border-left-color: ' . $timeline_color4back . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-4 .ts-timeline-4-content .ts-timeline-4-date {';
							$styles .= $date_default;
							$styles .= 'color: ' . $timeline_color4date . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' .ts-timeline-4 .ts-timeline-4-content .ts-timeline-4-title {';
							$styles .= $title_default;
							$styles .= 'font-size: ' . $title_fontsize . 'px;';
							$styles .= 'color: ' . $timeline_color4title . ';';
						$styles .= '}';
					}
				if ($inline == "false") {
					$styles .= '</style>';
				}
				if (($styles != "") && ($inline == "true")) {
					wp_add_inline_style('ts-visual-composer-extend-custom', TS_VCSC_MinifyCSS($styles));
				}
				
				// Create Final Output
				if ($frontend_edit == "false") {					
					$output .= '<div id="' . $timeline_id . '" class="ts-process-line-container ' . $el_class . ' ' . $css_class . ' ' . $timeline_addclass . ' ' . $animation_class . '" ' . $animation_data . ' data-style="' . $timeline_style . '" data-alignment="' . $timeline_alignment . '" data-switchme="' . $timeline_switchme . '" data-switchat="' . $timeline_switchat . '" data-switchto="' . $timeline_switchto . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
						if ($inline == "false") {
							$output .= TS_VCSC_MinifyCSS($styles);
						}
						if ($timeline_style == 'style1') {
							if (function_exists('wpb_js_remove_wpautop')){
								$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
							} else {
								$output .= do_shortcode($content);
							}							
						} else if ($timeline_style == 'style2') {
							$output .= '<ul class="ts-timeline-2 ' . ($timeline_date == "true" ? "showdate" : "nodate") . '">';
								if (function_exists('wpb_js_remove_wpautop')){
									$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
								} else {
									$output .= do_shortcode($content);
								}
							$output .= '</ul">';
						} else if ($timeline_style == 'style3') {
							$output .= '<ul class="ts-timeline-3 ts-timeline-3-align-' . $timeline_alignment . '">';
								if (function_exists('wpb_js_remove_wpautop')){
									$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
								} else {
									$output .= do_shortcode($content);
								}
							$output .= '</ul>';
						} else if ($timeline_style == 'style4') {
							$output .= '<div class="ts-timeline-4 ts-timeline-4-align-' . $timeline_alignment . '">';
								if (function_exists('wpb_js_remove_wpautop')){
									$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
								} else {
									$output .= do_shortcode($content);
								}
							$output .= '</div>';
						}
					$output .= '</div>';
				} else {
					$output .= '<div id="' . $timeline_id . '" class="ts-process-line-container-frontend">';
						if (function_exists('wpb_js_remove_wpautop')){
							$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
						} else {
							$output .= do_shortcode($content);
						}
					$output .= '</div>';
				}
				
				echo $output;
				
				$myvariable = ob_get_clean();
				return $myvariable;
			}
	
			// Single Process Lines Item
			function TS_VCSC_ProcessLines_Item ($atts, $content = null) {
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();
				
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					$frontend_edit					= 'true';
				} else {
					$frontend_edit					= 'false';
				}
			
				extract( shortcode_atts( array(
					// Hidden Input
					'timeline_style'				=> 'style1',
					// Icon / Image / String Settings
					'icon_replace'					=> 'false',	// false, true, string
					'icon'							=> '',
					'image'							=> '',
					'string'						=> '',
					'icon_size'						=> 80,
					'icon_ratio'					=> 65,
					'icon_color'					=> '#000000',
					'icon_background'				=> '#ffffff',
					'icon_frame_type'				=> '',
					'icon_frame_thick'				=> 1,
					'icon_frame_color'				=> '#000000',
					'icon_frame_radius'				=> '',
					'padding'						=> 'false',
					'icon_padding'					=> 0,
					// Icon Animation
					'animation_trigger'				=> 'infinite',
					'animation_class'            	=> '',
					'animation_name'            	=> '',
					'animation_delay' 				=> 500,
					'animation_offset'				=> 'bottom-in-view',
					'animation_mobile'				=> 'false',
					// Content Data
					'date'							=> '',
					'sub_date'						=> '',
					'title'							=> '',
					// Link Data
					'link_usage'					=> 'none',
					'link_data'						=> '',
					// WPAutoP Callback
					'content_wpautop'				=> 'false',
					// Other
					'el_id' 						=> '',
					'el_class'                  	=> '',
					'css'							=> '',
				), $atts ));

				$output								= '';
				$wpautop 							= ($content_wpautop == "true" ? true : false);
				$string 							= substr($string, 0, 2);
				
				if (!empty($el_id)) {
					$timeline_id					= $el_id;
				} else {
					$timeline_id					= 'ts-vcsc-process-line-item-' . mt_rand(999999, 9999999);
				}
				
				if (!empty($image)) {
					$image_path 					= wp_get_attachment_image_src($image, 'large');
				} else {
					$image_path						= array();
				}
			
				if ($icon_frame_type != '') {
					$icon_border_style				= 'border: ' . $icon_frame_thick . 'px ' . $icon_frame_type . ' ' . $icon_frame_color . ';';
				} else {
					$icon_border_style				= '';
				}
			
				if ($icon_frame_type != '') {
					$icon_frame_style				= 'border: ' . $icon_frame_thick . 'px ' . $icon_frame_type . ' ' . $icon_frame_color . ';';
				} else {
					$icon_frame_style				= '';
				}
				
				$icon_size_adjust					= ($icon_size - 2*$icon_frame_thick - 2*$icon_padding);
				
				// Link Values
				if ($link_usage != "none") {
					$link 							= TS_VCSC_Advancedlinks_GetLinkData($link_data);
					$a_href							= $link['url'];
					$a_title 						= $link['title'];
					$a_target 						= $link['target'];
					$a_rel 							= $link['rel'];
					if (!empty($a_rel)) {
						$a_rel 						= 'rel="' . esc_attr(trim($a_rel)) . '"';
					}
				}
				
				if ($animation_class != "") {
					if ($animation_trigger == "viewport") {						
						$animation_data				= 'data-animation-trigger="' . $animation_trigger . '" data-animation-type="ts-' . $animation_trigger . '-css-' . $animation_class . '" data-animation-frontend="' . $frontend_edit . '" data-animation-offset="' . $animation_offset . '" data-animation-delay="' . $animation_delay . '" data-animation-mobile="' . $animation_mobile . '"';
						$animation_class			= 'ts-process-icon-viewport';
					} else {						
						$animation_class			= 'ts-' . $animation_trigger . '-css-' . $animation_class;
						$animation_data				= 'data-animation-trigger="' . $animation_trigger . '" data-animation-type="' . $animation_class . '"';
					}					
				} else {
					$animation_data					= '';
					$animation_class				= '';
				}
				
				if (($timeline_style == 'style1') || ($timeline_style == 'style3')) {
					$icon_style						= 'height: ' . $icon_size_adjust . 'px; width: ' . $icon_size_adjust . 'px; font-size: ' . round($icon_size_adjust * $icon_ratio / 100) . 'px; line-height: ' . $icon_size_adjust . 'px; padding: ' . $icon_padding . 'px; color: ' . $icon_color . ';';
				} else if ($timeline_style == 'style2') {
					$icon_style						= 'color: ' . $icon_color . '; background-color:' . ($icon_background == "" ? "transparent" : $icon_background) . ';';
				} else if ($timeline_style == 'style4') {
					$icon_style						= 'color: ' . $icon_color . ';';
				} else {
					$icon_style						= '';
				}
				
				if (($timeline_style == 'style1') || ($timeline_style == 'style3')) {
					$image_style					= 'height: ' . $icon_size . 'px; width: ' . $icon_size . 'px; font-size: ' . round($icon_size_adjust * $icon_ratio / 100) . 'px; line-height: ' . $icon_size . 'px; padding: ' . $icon_padding . 'px; background-color:' . ($icon_background == "" ? "transparent" : $icon_background) . ';';
				} else {
					$image_style					= 'padding: ' . $icon_padding . 'px; background-color:' . ($icon_background == "" ? "transparent" : $icon_background) . ';';
				}
				
				
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_ProcessLines_Item', $atts);
				} else {
					$css_class						= '';
				}

				if ($frontend_edit == "false") {
					if ($timeline_style == "style1") {				
						$output .= '<div id="' . $timeline_id . '" class="ts-timeline-1 ts-timeline-1-item clearfix ' . $el_class . ' ' . $css_class . '">';
							$output .= '<div class="ts-timeline-1-date">';
								$output .= '<div class="day">' . $date . '</div>';
							$output .= '</div>';
							$output .= '<div class="ts-timeline-1-line"></div>';		
							$output .= '<div class="ts-timeline-1-hor-line"></div>';
							$output .= '<div class="ts-timeline-1-start-point"></div>';
							$output .= '<div class="ts-timeline-1-container">';
								if (($icon_replace == "false") && (!empty($icon))) {
									$output .= '<div class="ts-timeline-1-icon" style="height: ' . $icon_size_adjust . 'px; width: ' . $icon_size_adjust . 'px; background-color:' . ($icon_background == "" ? "transparent" : $icon_background) . ';">';
										$output .= '<i class="ts-font-icon ' . $icon . ' ' . $icon_frame_radius . ' ' . $animation_class . '" ' . $animation_data . ' style="' . $icon_style . ' ' . $icon_border_style . '"></i>';
									$output .= '</div>';
								} else if (($icon_replace == "true") && (isset($image_path[0]))) {
									$output .= '<div class="ts-timeline-1-img" style="height: ' . $icon_size . 'px; width: ' . $icon_size . 'px; background-color:' . ($icon_background == "" ? "transparent" : $icon_background) . ';">';
										$output .= '<img class="ts-font-icon ' . $animation_class . ' ' . $icon_frame_radius . '" ' . $animation_data . ' src="' . $image_path[0] . '" alt="" style="' . $image_style . ' ' . $icon_border_style . '">';
									$output .= '</div>';
								} else if (($icon_replace == "string") && ($string != "")) {
									$output .= '<div class="ts-timeline-1-icon" style="height: ' . $icon_size_adjust . 'px; width: ' . $icon_size_adjust . 'px; background-color:' . ($icon_background == "" ? "transparent" : $icon_background) . ';">';
										$output .= '<i class="ts-font-icon ' . $icon_frame_radius . ' ' . $animation_class . '" ' . $animation_data . ' style="font-style: normal; ' . $icon_style . ' ' . $icon_border_style . '">' . $string . '</i>';
									$output .= '</div>';
								}
								$output .= '<div class="ts-timeline-1-content' . (isset($image_path[0]) ? " ts-timeline-1-hasimg" : "") . '">';
									$output .= '<div class="ts-timeline-1-title">' . $title . '</div>';
									$output .= '<div class="ts-timeline-1-content">';
										if (function_exists('wpb_js_remove_wpautop')){
											$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
										} else {
											$output .= do_shortcode($content);
										}
									$output .= '</div>';
								$output .= '</div>';
							$output .= '</div>';
						$output .= '</div>';
					} else if ($timeline_style == "style2") {	
						$output .= '<li id="' . $timeline_id . '" class="ts-timeline-2-item ' . $el_class . '">';
							$output .= '<time class="ts-timeline-2-time"><span>' . $date . '</span> <span>' . $sub_date . '</span></time>';
							if (($icon_replace == "false") && (!empty($icon))) {
								$output .= '<i class="ts-font-icon ' . $icon . ' ts-timeline-2-icon ' . $animation_class . '" ' . $animation_data . ' style="' . $icon_style . '"></i>';
							} else if (($icon_replace == "true") && (isset($image_path[0]))) {
								$output .= '<img class="ts-font-icon ts-timeline-2-image ' . $animation_class . ' ' . $icon_frame_radius . '" ' . $animation_data . ' src="' . $image_path[0] . '" alt="" style="' . $image_style . ' ' . $icon_border_style . '">';
							} else if (($icon_replace == "string") && ($string != "")) {
								$output .= '<i class="ts-font-icon ts-timeline-2-icon ' . $animation_class . '" ' . $animation_data . ' style="font-style: normal; ' . $icon_style . '">' . $string . '</i>';
							}
							$output .= '<div class="ts-timeline-2-label">';
								$output .= '<div class="ts-timeline-2-label-title">' . $title . '</div>';
								$output .= '<div class="ts-timeline-2-label-content">';
									if (function_exists('wpb_js_remove_wpautop')){
										$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
									} else {
										$output .= do_shortcode($content);
									}
								$output .= '</div>';
							$output .= '</div>';
						$output .= '</li>';
					} else if ($timeline_style == "style3") {
						$output .= '<li id="' . $timeline_id . '" class="ts-timeline-3-item ' . $el_class . '">';
							$output .= '<div class="ts-timeline-3-content-main">';
								$output .= '<div class="flag-wrapper">';
									$output .= '<div class="flag">' . $title . '</div>';
								$output .= '</div>';
								if ($date != "") {
									$output .= '<div class="time-wrapper"><span class="time">' . $date . '</span></div>';
								}
								$output .= '<div class="desc-wrapper">';
									if (($icon_replace == "false") && (!empty($icon))) {
										$output .= '<i class="ts-font-icon ' . $icon . ' ts-timeline-3-icon ' . $animation_class . ' ' . $icon_frame_radius . '" ' . $animation_data . ' style="' . $icon_style . ' ' . $icon_border_style . '"></i>';
									} else if (($icon_replace == "true") && (isset($image_path[0]))) {
										$output .= '<img class="ts-font-icon ts-timeline-3-image ' . $animation_class . ' ' . $icon_frame_radius . '" ' . $animation_data . ' src="' . $image_path[0] . '" alt="" style="' . $image_style . ' ' . $icon_border_style . '">';
									} else if (($icon_replace == "string") && ($string != "")) {
										$output .= '<i class="ts-font-icon ts-timeline-3-icon ' . $animation_class . ' ' . $icon_frame_radius . '" ' . $animation_data . ' style="font-style: normal; ' . $icon_style . ' ' . $icon_border_style . '">' . $string . '</i>';
									}
									$output .= '<div class="desc">';
										if (function_exists('wpb_js_remove_wpautop')){
											$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
										} else {
											$output .= do_shortcode($content);
										}
									$output .= '</div>';
								$output .= '</div>';
							$output .= '</div>';
						$output .= '</li>';
					} else if ($timeline_style == "style4") {
						$output .= '<div class="ts-timeline-4-item">';
							$output .= '<div class="ts-timeline-4-graphic" style="background: ' . ($icon_background == "" ? "transparent" : $icon_background) . ';">';
								if (($icon_replace == "false") && (!empty($icon))) {
									$output .= '<i class="ts-font-icon ' . $icon . ' ts-timeline-4-icon ' . $animation_class . ' ' . $icon_frame_radius . '" ' . $animation_data . ' style="' . $icon_style . ' ' . $icon_border_style . '"></i>';
								} else if (($icon_replace == "true") && (isset($image_path[0]))) {
									$output .= '<img class="ts-font-icon ts-timeline-4-image ' . $animation_class . ' ' . $icon_frame_radius . '" ' . $animation_data . ' src="' . $image_path[0] . '" alt="" style="' . $image_style . ' ' . $icon_border_style . '">';
								} else if (($icon_replace == "string") && ($string != "")) {
									$output .= '<i class="ts-font-icon ts-timeline-4-icon ' . $animation_class . ' ' . $icon_frame_radius . '" ' . $animation_data . ' style="font-style: normal;' . $icon_style . ' ' . $icon_border_style . '">' . $string . '</i>';
								}								
							$output .= '</div>';					 
							$output .= '<div class="ts-timeline-4-content">';
								$output .= '<div class="ts-timeline-4-title">' . $title . '</div>';
								if (function_exists('wpb_js_remove_wpautop')){
									$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
								} else {
									$output .= do_shortcode($content);
								}
								//$output .= '<a href="#0" class="ts-timeline-4-link">Read more</a>';
								$output .= '<span class="ts-timeline-4-date">' . $date . '</span>';
							$output .= '</div>';
						$output .= '</div>';
					}
				} else {
					$output .= '<div id="' . $timeline_id . '" class="ts-processline-item-frontend">';
						$output .= '<div class="ts-processline-item-graphic">';
							if ($icon_replace == "true") {
								if (!empty($image)) {
									$output .= '<img class="ts-processline-item-image" src="' . $image_path[0] . '">';
								}
							} else if ($icon_replace == "false") {
								$output .= '<i class="ts-font-icon ts-processline-item-icon ' . $icon . '"></i>';
							} else if ($icon_replace == "string") {
								$output .= '<i class="ts-font-icon ts-processline-item-icon" style="font-style: normal;">' . $string . '</i>';
							}
						$output .= '</div>';
						$output .= '<div class="ts-processline-item-date">' . $date . '</div>';
						$output .= '<div class="ts-processline-item-title">';
							if (($link_usage == "title") && ($a_href != '')) {
								$output .= '<a class="ts-processline-item-link" href="' . $a_href . '" target="' . $a_target . '" title="' . $a_title . '" ' . $a_rel . '>' . $title . '</a>';
							} else {
								$output .= $title;
							}
						$output .= '</div>';
						$output .= '<div class="ts-processline-item-content">';
							if (function_exists('wpb_js_remove_wpautop')){
								$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
							} else {
								$output .= do_shortcode($content);
							}
						$output .= '</div>';
					$output .= '</div>';
				}
				
				echo $output;
				
				$myvariable = ob_get_clean();
				return $myvariable;
			}
		
			// Add Process Lines Elements
			function TS_VCSC_Add_ProcessLines_Element_Container() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Process Lines Container
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					"name"                              => __("TS Process Lines", "ts_visual_composer_extend"),
					"base"                              => "TS_VCSC_ProcessLines_Container",
					"icon"                              => "ts-composer-element-icon-processlines-container",
					"category"                          => __("VC Extensions", "ts_visual_composer_extend"),
					"as_parent"                         => array('only' => 'TS_VCSC_ProcessLines_Item'),
					"description"                       => __("Build a process lines element", "ts_visual_composer_extend"),
					"controls" 							=> "full",
					"content_element"                   => true,
					"is_container" 						=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_UseExtendedNesting == "true" ? false : true),
					"container_not_allowed" 			=> false,
					"show_settings_on_create"           => true,
					"js_view"							=> "TS_VCSC_ProcessLinesContainerViewCustom",
					"admin_enqueue_js"            		=> "",
					"admin_enqueue_css"           		=> "",
					"front_enqueue_js"					=> preg_replace( '/\s/', '%20', TS_VCSC_GetResourceURL('/js/frontend/ts-vcsc-frontend-processlines-container.min.js')),
					"front_enqueue_css"					=> "",
					"params"                            => array(
						// Process Lines Settings
						array(
							"type"						=> "seperator",
							"param_name"				=> "seperator_1",
							"seperator"					=> "General Settings",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Process Lines Style", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_style",
							"width"             		=> 300,
							"value"             		=> array(
								__( 'Style 1', "ts_visual_composer_extend" )      => "style1",
								__( 'Style 2', "ts_visual_composer_extend" )      => "style2",
								__( 'Style 3', "ts_visual_composer_extend" )      => "style3",
								__( 'Style 4', "ts_visual_composer_extend" )      => "style4",
							),
							"admin_label"       		=> true,
							"description"       		=> __( "Select the process lines style.", "ts_visual_composer_extend" )
						),
						// Style 1 Specific Settings
						array(
							"type"              		=> "switch_button",
							"heading"           		=> __( "Item Connector", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_connector",
							"value"             		=> "true",
							"description"       		=> __( "Switch the toggle to visually connect each item in the process line.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style1') )
						),
						array(
							"type"              		=> "switch_button",
							"heading"          	 		=> __( "Start Marker", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_dots",
							"value"             		=> "true",
							"description"       		=> __( "Switch the toggle to add a marker to the bottom of process line to mark its beginning.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"        		=> array( 'element' => "timeline_connector", 'value' => 'true' )
						),
						// Style 2 Specific Settings
						array(
							"type"              		=> "switch_button",
							"heading"           		=> __( "Show Date / Step", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_date",
							"value"             		=> "true",
							"description"       		=> __( "Switch the toggle to either show or hide the section with the date / step.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style2') ),
						),
						// Style 3 + 4 Specific Settings
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Item Alignment", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_alignment",
							"width"             		=> 300,
							"value"             		=> array(
								__( 'Alternating (Left/Right)', "ts_visual_composer_extend" )		=> "alternate",
								__( 'Left Alignment', "ts_visual_composer_extend" )      			=> "left",
								__( 'Right Alignment', "ts_visual_composer_extend" )      			=> "right",
							),
							"description"       		=> __( "Select the item alignment within the process line.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style3', 'style4') ),
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "One Column Switch", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_switchat",
							"value"             		=> "640",
							"min"               		=> "480",
							"max"               		=> "1024",
							"step"              		=> "1",
							"unit"              		=> 'px',
							"description"       		=> __( "Define at which element width the process line should switch to a one column layout.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_alignment", 'value' => 'alternate' ),
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "One Column Alignment", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_switchto",
							"width"             		=> 300,
							"value"             		=> array(
								__( 'Left Alignment', "ts_visual_composer_extend" )      			=> "left",
								__( 'Right Alignment', "ts_visual_composer_extend" )      			=> "right",
							),
							"description"       		=> __( "Select how the items should be aligned when switching to the one column layout.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_alignment", 'value' => 'alternate' ),
						),
						// Style 4 Specific Settings
						// Animation Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_2",
							"seperator"					=> "Animation Settings",
							"group"						=> "Animation Settings",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Animation Trigger", "ts_visual_composer_extend" ),
							"param_name"        		=> "animation_trigger",
							"width"             		=> 300,
							"value"             		=> array(
								__( 'Individual Items in View', "ts_visual_composer_extend" )			=> "item-in-view",
								__( 'Timeline Container in View', "ts_visual_composer_extend" )			=> "container-in-view",
							),
							"description"       		=> __( "Define which viewport event should be used to trigger/start the animation.", "ts_visual_composer_extend" ),
							"group"						=> "Animation Settings",
						),
						array(
							"type"						=> "css3animations",
							"heading"					=> __("Viewport Animation", "ts_visual_composer_extend"),
							"param_name"				=> "animation_view1",
							"prefix"					=> "ts-viewport-css-",
							"connector"					=> "animation_name1",
							"noneselect"				=> "true",
							"default"					=> "",
							"value"						=> "",
							"admin_label"				=> false,
							"description"				=> __("Select the viewport animation for the individual items in the process line.", "ts_visual_composer_extend"),
							"dependency"        		=> array('element' => "timeline_style", 'value' => array('style1', 'style2')),
							"group"						=> "Animation Settings",
						),
						array(
							"type"						=> "hidden_input",
							"heading"					=> __( "Viewport Animation", "ts_visual_composer_extend" ),
							"param_name"				=> "animation_name1",
							"value"						=> "",
							"dependency"        		=> array('element' => "timeline_style", 'value' => array('style1', 'style2')),
							"group"						=> "Animation Settings",
						),						
						array(
							"type"						=> "css3animations",
							"heading"					=> __("Viewport Animation", "ts_visual_composer_extend"),
							"param_name"				=> "animation_view2",
							"prefix"					=> "ts-viewport-css-",
							"connector"					=> "animation_name2",
							"noneselect"				=> "true",
							"default"					=> "",
							"value"						=> "",
							"admin_label"				=> false,
							"description"				=> __("Select the viewport animation for the individual items in the process line.", "ts_visual_composer_extend"),
							"dependency"        		=> array( 'element' => "timeline_alignment", 'value' => array('left', 'right') ),
							"group"						=> "Animation Settings",
						),
						array(
							"type"						=> "hidden_input",
							"heading"					=> __( "Viewport Animation (Odd)", "ts_visual_composer_extend" ),
							"param_name"				=> "animation_name2",
							"value"						=> "",
							"dependency"        		=> array( 'element' => "timeline_alignment", 'value' => 'alternate' ),
							"group"						=> "Animation Settings",
						),						
						array(
							"type"						=> "css3animations",
							"heading"					=> __("Viewport Animation (Odd)", "ts_visual_composer_extend"),
							"param_name"				=> "animation_view3",
							"prefix"					=> "ts-viewport-css-",
							"connector"					=> "animation_name3",
							"noneselect"				=> "true",
							"default"					=> "",
							"value"						=> "",
							"admin_label"				=> false,
							"description"				=> __("Select the viewport animation for the individual odd items in the process line.", "ts_visual_composer_extend"),
							"dependency"        		=> array( 'element' => "timeline_alignment", 'value' => 'alternate' ),
							"group"						=> "Animation Settings",
						),
						array(
							"type"						=> "hidden_input",
							"heading"					=> __( "Viewport Animation (Odd)", "ts_visual_composer_extend" ),
							"param_name"				=> "animation_name3",
							"value"						=> "",
							"dependency"        		=> array( 'element' => "timeline_alignment", 'value' => 'alternate' ),
							"group"						=> "Animation Settings",
						),
						array(
							"type"						=> "css3animations",
							"heading"					=> __("Viewport Animation (Even)", "ts_visual_composer_extend"),
							"param_name"				=> "animation_view4",
							"prefix"					=> "ts-viewport-css-",
							"connector"					=> "animation_name4",
							"noneselect"				=> "true",
							"default"					=> "",
							"value"						=> "",
							"admin_label"				=> false,
							"description"				=> __("Select the viewport animation for the individual even items in the process line.", "ts_visual_composer_extend"),
							"dependency"        		=> array( 'element' => "timeline_alignment", 'value' => 'alternate' ),
							"group"						=> "Animation Settings",
						),
						array(
							"type"						=> "hidden_input",
							"heading"					=> __( "Viewport Animation (Even)", "ts_visual_composer_extend" ),
							"param_name"				=> "animation_name4",
							"value"						=> "",
							"dependency"        		=> array( 'element' => "timeline_alignment", 'value' => 'alternate' ),
							"group"						=> "Animation Settings",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Animation Initial Delay", "ts_visual_composer_extend" ),
							"param_name"       		 	=> "animation_delay",
							"value"             		=> "0",
							"min"               		=> "0",
							"max"               		=> "5000",
							"step"              		=> "100",
							"unit"              		=> 'ms',
							"description"       		=> __( "Define an optional initial delay for the viewport animation.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "animation_trigger", 'value' => 'container-in-view' ),
							"group"						=> "Animation Settings",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Animation Items Delay", "ts_visual_composer_extend" ),
							"param_name"       		 	=> "animation_wait",
							"value"             		=> "500",
							"min"               		=> "200",
							"max"               		=> "2000",
							"step"              		=> "100",
							"unit"              		=> 'ms',
							"description"       		=> __( "Define an delay for the viewport animation between each item.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "animation_trigger", 'value' => 'container-in-view' ),
							"group"						=> "Animation Settings",
						),
						array(
							"type" 						=> "viewport_offset",
							"heading" 					=> __( "Viewport Offset", "ts_visual_composer_extend"),
							"param_name" 				=> "animation_offset",
							"value" 					=> 'bottom-in-view',
							"description" 				=> __("Define the offset (top of screen) that should trigger the viewport animation.", "ts_visual_composer_extend"),
							"group"						=> "Animation Settings",
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Allow on Mobile", "ts_visual_composer_extend" ),
							"param_name"        		=> "animation_mobile",
							"value"             		=> "false",
							"description"       		=> __( "Switch the toggle to allow the viewport animation to be used on mobile devices.", "ts_visual_composer_extend" ),
							"group"						=> "Animation Settings",
						),
						// Fonts: Date
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_3",
							"seperator"					=> "Date/Step Font",
							"group" 					=> "Font Settings",
						),
						array(
							"type"						=> "fontsmanager",
							"heading"					=> __( "Font Family", "ts_visual_composer_extend" ),
							"param_name"				=> "date_fonttype",
							"value"						=> "Default:regular",
							"default"					=> "true",
							"connector"					=> "date_fontmatch",
							"description"				=> __( "Select the default font family to be used for the item dates/steps.", "ts_visual_composer_extend" ),
							"group"						=> "Font Settings",
						),
						array(
							"type"						=> "hidden_input",
							"param_name"				=> "date_fontmatch",
							"value"						=> "default",
							"group"						=> "Font Settings",
						),
						// Fonts: Title
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_4",
							"seperator"					=> "Title Styling",
							"group" 					=> "Font Settings",
						),
						array(
							"type"						=> "fontsmanager",
							"heading"					=> __( "Font Family", "ts_visual_composer_extend" ),
							"param_name"				=> "title_fonttype",
							"value"						=> "Default:regular",
							"default"					=> "true",
							"connector"					=> "title_fontmatch",
							"description"				=> __( "Select the default font family to be used for the item titles.", "ts_visual_composer_extend" ),
							"group"						=> "Font Settings",
						),
						array(
							"type"						=> "hidden_input",
							"param_name"				=> "title_fontmatch",
							"value"						=> "default",
							"group"						=> "Font Settings",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Font Size", "ts_visual_composer_extend" ),
							"param_name"        		=> "title_fontsize",
							"value"             		=> "18",
							"min"               		=> "12",
							"max"               		=> "36",
							"step"              		=> "1",
							"unit"              		=> 'px',
							"description"       		=> "Define the font size to be used for the item titles.",
							"group"						=> "Font Settings",
						),
						// Fonts: Content
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_5",
							"seperator"					=> "Content Styling",
							"group" 					=> "Font Settings",
						),
						array(
							"type"						=> "fontsmanager",
							"heading"					=> __( "Font Family", "ts_visual_composer_extend" ),
							"param_name"				=> "content_fonttype",
							"value"						=> "Default:regular",
							"default"					=> "true",
							"connector"					=> "content_fontmatch",
							"description"				=> __( "Select the default font family to be used for the item content.", "ts_visual_composer_extend" ),
							"group"						=> "Font Settings",
						),
						array(
							"type"						=> "hidden_input",
							"param_name"				=> "content_fontmatch",
							"value"						=> "default",
							"group"						=> "Font Settings",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Font Size", "ts_visual_composer_extend" ),
							"param_name"        		=> "title_fontsize",
							"value"             		=> "13",
							"min"               		=> "10",
							"max"               		=> "24",
							"step"              		=> "1",
							"unit"              		=> 'px',
							"description"       		=> "Define the font size to be used for the item content.",
							"group"						=> "Font Settings",
						),
						// Bar Stylings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_6_1",
							"seperator"					=> "Bar Styling",
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style1', 'style2', 'style3', 'style4') ),
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Bar Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_color1bar",
							"value"             		=> "#000000",
							"description"       		=> __( "Define the background color to be used for the process line.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style1') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Dots Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_color1dot",
							"value"             		=> "#000000",
							"description"       		=> __( "Define the color to be used for the start marker in the process line.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style1') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Bar Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_color2bar",
							"value"             		=> "#E5E5E5",
							"description"       		=> __( "Define the background color to be used for the process line.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style2') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Border Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_color2shade",
							"value"             		=> "#E5E5E5",
							"description"       		=> __( "Define the border color to be used for the process icons.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style2') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),						
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Bar Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_color3bar",
							"value"             		=> "#505050",
							"description"       		=> __( "Define the background color to be used for the process line.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style3') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Dots Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_color3dot",
							"value"             		=> "#ff5050",
							"description"       		=> __( "Define the background color to be used for the item dots on the process line.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style3') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Bar Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_color4bar",
							"value"             		=> "#d7e4ed",
							"description"       		=> __( "Define the background color to be used for the process line.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style4') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						// Alternate Stylings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_7_2",
							"seperator"					=> "Alternate Styling",
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style2') ),
							"group" 					=> "Theme Settings",
						),
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_7_2a",
							"seperator"					=> "Odd Items",
							"fontsize"					=> 14,
							"borderwidth"				=> 1,
							"bordertype"				=> "dotted",
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style2') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_7_2b",
							"seperator"					=> "Even Items",
							"fontsize"					=> 14,
							"borderwidth"				=> 1,
							"bordertype"				=> "dotted",
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style2') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Odd Items: Date Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "color_odd_date",
							"value"             		=> "#C0C0C0",
							"description"       		=> __( "Define the date font color to be used for all odd items in the process line.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style2') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Even Items: Date Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "color_even_date",
							"value"             		=> "#C0C0C0",
							"description"       		=> __( "Define the date font color to be used for all even items in the process line.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style2') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Odd Items: Sub-Date Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "color_odd_subdate",
							"value"             		=> "#A0A0A0",
							"description"       		=> __( "Define the sub-date font color to be used for all odd items in the process line.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style2') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Even Items: Sub-Date Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "color_even_subdate",
							"value"             		=> "#404040",
							"description"       		=> __( "Define the sub-date font color to be used for all even items in the process line.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style2') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),						
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Odd Items: Border Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "color_odd_border",
							"value"             		=> "#C4C4C4",
							"description"       		=> __( "Define the border color to be used for all odd items in the process line.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style2') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Even Items: Border Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "color_even_border",
							"value"             		=> "#D8D8D8",
							"description"       		=> __( "Define the border color to be used for all even items in the process line.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style2') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Odd Items: Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "color_odd_background",
							"value"             		=> "#FFFFFF",
							"description"       		=> __( "Define the background color to be used for all odd items in the process line.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style2') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Even Items: Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "color_even_background",
							"value"             		=> "#F7F7F7",
							"description"       		=> __( "Define the background color to be used for all even items in the process line.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style2') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),						
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Odd Items: Title Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "color_odd_title",
							"value"             		=> "#929292",
							"description"       		=> __( "Define the title font color to be used for all odd items in the process line.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style2') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Even Items: Title Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "color_even_title",
							"value"             		=> "#333333",
							"description"       		=> __( "Define the title font color to be used for all even items in the process line.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style2') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Odd Items: Content Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "color_odd_content",
							"value"             		=> "#868686",
							"description"       		=> __( "Define the content font color to be used for all odd items in the process line.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style2') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Even Items: Content Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "color_even_content",
							"value"             		=> "#505050",
							"description"       		=> __( "Define the content font color to be used for all even items in the process line.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style2') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						// Date/Step Stylings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_7_3",
							"seperator"					=> "Date/Step Styling",
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style1', 'style3', 'style4') ),
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Date/Step Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_color1date",
							"value"             		=> "#ffffff",
							"description"       		=> __( "Define the font color to be used for the item date/step.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style1') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Date/Step Background", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_color1back",
							"value"             		=> "#000000",
							"description"       		=> __( "Define the background color to be used for the item date/step.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style1') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Date/Step Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_color3date",
							"value"             		=> "#ff5050",
							"description"       		=> __( "Define the font color to be used for the item date/step.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style3') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Date/Step Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_color4date",
							"value"             		=> "#676767",
							"description"       		=> __( "Define the font color to be used for the item date/step.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style4') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						// Title Stylings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_7_4",
							"seperator"					=> "Title Styling",
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style1', 'style3', 'style4') ),
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Title Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_color1title",
							"value"             		=> "#333333",
							"description"       		=> __( "Define the font color to be used for the item title.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style1') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Title Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_color3title",
							"value"             		=> "#333333",
							"description"       		=> __( "Define the font color to be used for the item title.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style3') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Title Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_color4title",
							"value"             		=> "#303e49",
							"description"       		=> __( "Define the font color to be used for the item title.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style4') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						// Content Stylings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_7_5",
							"seperator"					=> "Content Styling",
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style1', 'style3', 'style4') ),
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Content Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_color1content",
							"value"             		=> "#676767",
							"description"       		=> __( "Define the font color to be used for the item content.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style1') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Content Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_color3content",
							"value"             		=> "#696969",
							"description"       		=> __( "Define the font color to be used for the item content.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style3') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),						
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Content Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_color4content",
							"value"             		=> "#676767",
							"description"       		=> __( "Define the font color to be used for the item content.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style4') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Content Background", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_color4back",
							"value"             		=> "#fbfbfb",
							"description"       		=> __( "Define the background color to be used for the item content.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style4') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Content Shadow", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_color4shadow",
							"value"             		=> "#d7e4ed",
							"description"       		=> __( "Define the shadow color to be used for the item content.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style4') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Theme Settings",
						),
						// Other Settings
						array(
							"type"              		=> "seperator",
							"param_name"       		 	=> "seperator_8",
							"seperator"					=> "Other Timeline / Process Settings",
							"group"						=> "Other Settings",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Margin: Top", "ts_visual_composer_extend" ),
							"param_name"        		=> "margin_top",
							"value"             		=> "0",
							"min"               		=> "-50",
							"max"               		=> "200",
							"step"              		=> "1",
							"unit"              		=> 'px',
							"description"       		=> __( "Select the top margin for the element.", "ts_visual_composer_extend" ),
							"group"						=> "Other Settings",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Margin: Bottom", "ts_visual_composer_extend" ),
							"param_name"        		=> "margin_bottom",
							"value"             		=> "0",
							"min"               		=> "-50",
							"max"               		=> "200",
							"step"              		=> "1",
							"unit"              		=> 'px',
							"description"       		=> __( "Select the bottom margin for the element.", "ts_visual_composer_extend" ),
							"group"						=> "Other Settings",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "Define ID Name", "ts_visual_composer_extend" ),
							"param_name"        		=> "el_id",
							"value"             		=> "",
							"description"       		=> __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
							"group"						=> "Other Settings",
						),
						array(
							"type"                  	=> "tag_editor",
							"heading"           		=> __( "Extra Class Names", "ts_visual_composer_extend" ),
							"param_name"            	=> "el_class",
							"value"                 	=> "",
							"description"      		 	=> __( "Enter additional class names for the element.", "ts_visual_composer_extend" ),
							"group" 					=> "Other Settings",
						),
					)
				);
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
					return $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element;
				} else {			
					vc_map($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element);
				};
			}
			function TS_VCSC_Add_ProcessLines_Element_Item() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Single Process Lines Item
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					"name"                      		=> __( "TS Process Lines Item", "ts_visual_composer_extend" ),
					"base"                      		=> "TS_VCSC_ProcessLines_Item",
					"icon" 	                    		=> "ts-composer-element-icon-processlines-item",
					"content_element"					=> true,
					"as_child"							=> array('only' => 'TS_VCSC_ProcessLines_Container'),
					"category"                  		=> __( 'VC Extensions', "ts_visual_composer_extend" ),
					"description"               		=> __("Place a single process lines item", "ts_visual_composer_extend"),
					"show_settings_on_create" 			=> true,
					"js_view"							=> "TS_VCSC_ProcessLinesItemViewCustom",
					"admin_enqueue_js"            		=> "",
					"admin_enqueue_css"           		=> "",
					"front_enqueue_js"					=> preg_replace( '/\s/', '%20', TS_VCSC_GetResourceURL('/js/frontend/ts-vcsc-frontend-processlines-item.min.js')),
					"front_enqueue_css"					=> "",
					"params"                    		=> array(
						// Main Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_1",
							"seperator"					=> "Process Line Step",
						),
						// Hidden Inputs
						array(
							"type"              		=> "hidden_input",
							"heading"           		=> __( "Process Item Style", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_style",
							"value"             		=> "style1",
						),
						// Process Content
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "Date / Step", "ts_visual_composer_extend" ),
							"param_name"        		=> "date",
							"value"             		=> "",
							"description"       		=> __( "Enter the date for the timeline / process element.", "ts_visual_composer_extend" ),
							"admin_label"       		=> true,
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "Sub-Date / Step", "ts_visual_composer_extend" ),
							"param_name"        		=> "sub_date",
							"value"             		=> "",
							"description"       		=> __( "Enter the text below the date for the timeline / process element.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style2') ),
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "Title", "ts_visual_composer_extend" ),
							"param_name"        		=> "title",
							"value"             		=> "",
							"description"       		=> __( "Enter the title for the timeline / process element.", "ts_visual_composer_extend" ),
							"admin_label"       		=> true,
						),						
						array(
							"type"						=> "textarea_html",
							"heading"					=> __( "Item Content", "ts_visual_composer_extend" ),
							"param_name"				=> "content",
							"value"						=> "",
							"description"				=> __( "Create the content for this step in the process line.", "ts_visual_composer_extend" ),
						),
						// Process Link
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_2",
							"seperator"					=> "Process Line Link",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Step Link: Usage", "ts_visual_composer_extend" ),
							"param_name"        		=> "link_usage",
							"value"             		=> array(
								__( "No Link", "ts_visual_composer_extend" )                          	=> "none",
								__( "Add Link to Step Title", "ts_visual_composer_extend" )				=> "title",
							),
							"description"       		=> __( "Define if and where you want to add a link to this step in the process line.", "ts_visual_composer_extend" ),
						),
						array(
							"type" 						=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterLinkPicker['enabled'] == "false" ? "vc_link" : "advancedlinks"),
							"heading" 					=> __("Step Link: Data", "ts_visual_composer_extend"),
							"param_name" 				=> "link_date",
							"description" 				=> __("Provide a link to another site/page for this step in the process line.", "ts_visual_composer_extend"),
							"dependency"    			=> array( 'element' => 'link_usage', 'value' => "title" ),
						),
						// Icon / Image / String Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_3",
							"seperator"					=> "Step Icon",
							"group"						=> "Step Icon",
						),					
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Step Icon: Inner Content", "ts_visual_composer_extend" ),
							"param_name"        		=> "icon_replace",
							"value"             		=> array(
								__( "Font Icon", "ts_visual_composer_extend" )                          => "false",
								__( "Icon Image", "ts_visual_composer_extend" )                  		=> "true",
								__( "Short Text String", "ts_visual_composer_extend" )                 	=> "string",
							),
							"description"       		=> __( "Define what inner content should be used for the step icon.", "ts_visual_composer_extend" ),
							"group"						=> "Step Icon",
						),
						array(
							"type" 						=> "icons_panel",
							'heading' 					=> __( 'Step Icon: Select Icon', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'icon',
							'value'						=> '',
							"settings" 					=> array(
								"emptyIcon" 					=> true,
								'emptyIconValue'				=> 'transparent',
								"type" 							=> 'extensions',
							),
							"description"       		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorVisualSelector == "true" ? __( "Select the icon you want to display.", "ts_visual_composer_extend" ) : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconSelectorString),
							"dependency"        		=> array( 'element' => "icon_replace", 'value' => 'false' ),
							"group"						=> "Step Icon",
						),						
						array(
							"type"              		=> "attach_image",
							"heading"           		=> __( "Step Icon: Select Image", "ts_visual_composer_extend" ),
							"param_name"        		=> "image",
							"value"             		=> "false",
							"description"       		=> __( "Image will be displayed in a fixed size of 80x80 px.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "icon_replace", 'value' => 'true' ),
							"group"						=> "Step Icon",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "Step Icon: Short Text String", "ts_visual_composer_extend" ),
							"param_name"        		=> "string",
							"value"             		=> "",
							"description"       		=> __( "Enter a short text string (no more than 2 characters) to be used as content for the step icon.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "icon_replace", 'value' => 'string' ),
							"group"						=> "Step Icon",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Step Icon: Font Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "icon_color",
							"value"             		=> "#cccccc",
							"description"       		=> __( "Define the font color of the icon or text string.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "icon_replace", 'value' => array('false', 'string') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group"						=> "Step Icon",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Step Icon: Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "icon_background",
							"value"             		=> "#ffffff",
							"description"       		=> __( "Define the background color for the icon / transparent image / text string.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group"						=> "Step Icon",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Step Icon: Container Size", "ts_visual_composer_extend" ),
							"param_name"        		=> "icon_size",
							"value"             		=> "80",
							"min"               		=> "50",
							"max"               		=> "150",
							"step"              		=> "1",
							"unit"              		=> 'px',
							"description"       		=> __( "Define the overall size for the icon / image container; icon will be scaled to 65% of value, after optional paddings and border strength.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style1', 'style3') ),
							"group"						=> "Step Icon",
						),			
						// Icon / Image Border Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_4",
							"seperator"					=> "Step Icon Border Settings",
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style1', 'style3') ),
							"group"						=> "Step Icon",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Step Icon: Border Type", "ts_visual_composer_extend" ),
							"param_name"        		=> "icon_frame_type",
							"width"             		=> 300,
							"value"             		=> array(
								__( "None", "ts_visual_composer_extend" )                          => "",
								__( "Solid Border", "ts_visual_composer_extend" )                  => "solid",
								__( "Dotted Border", "ts_visual_composer_extend" )                 => "dotted",
								__( "Dashed Border", "ts_visual_composer_extend" )                 => "dashed",
								__( "Double Border", "ts_visual_composer_extend" )                 => "double",
								__( "Grouve Border", "ts_visual_composer_extend" )                 => "groove",
								__( "Ridge Border", "ts_visual_composer_extend" )                  => "ridge",
								__( "Inset Border", "ts_visual_composer_extend" )                  => "inset",
								__( "Outset Border", "ts_visual_composer_extend" )                 => "outset",
							),
							"description"       		=> __( "Select the type of border around the step icon.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_style", 'value' => array('style1', 'style3') ),
							"group"						=> "Step Icon",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Step Icon: Border Thickness", "ts_visual_composer_extend" ),
							"param_name"        		=> "icon_frame_thick",
							"value"             		=> "1",
							"min"               		=> "1",
							"max"               		=> "10",
							"step"              		=> "1",
							"unit"              		=> 'px',
							"description"       		=> __( "Define the thickness of the step icon border.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "icon_frame_type", 'value' => $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Border_Type_Values ),
							"group"						=> "Step Icon",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Step Icon: Border Radius", "ts_visual_composer_extend" ),
							"param_name"        		=> "icon_frame_radius",
							"value"             		=> array(
								__( "None", "ts_visual_composer_extend" )                          => "",
								__( "Small Radius", "ts_visual_composer_extend" )                  => "ts-radius-small",
								__( "Medium Radius", "ts_visual_composer_extend" )                 => "ts-radius-medium",
								__( "Large Radius", "ts_visual_composer_extend" )                  => "ts-radius-large",
								__( "Full Circle", "ts_visual_composer_extend" )                   => "ts-radius-full"
							),
							"description"       		=> __( "Define the radius of the step icon border.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "icon_frame_type", 'value' => $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Border_Type_Values ),
							"group"						=> "Step Icon",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Step Icon: Frame Border Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "icon_frame_color",
							"value"             		=> "#000000",
							"description"       		=> __( "Define the color of the step icon border.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "icon_frame_type", 'value' => $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Border_Type_Values ),
							"group"						=> "Step Icon",
						),
						array(
							"type"              		=> "switch_button",
							"heading"           		=> __( "Step Icon: Apply Padding", "ts_visual_composer_extend" ),
							"param_name"        		=> "padding",
							"value"             		=> "true",
							"description"       		=> __( "Switch the toggle if you want to apply a padding to the step icon.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "icon_frame_type", 'value' => $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Border_Type_Values ),
							"group"						=> "Step Icon",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Step Icon: Padding", "ts_visual_composer_extend" ),
							"param_name"        		=> "icon_padding",
							"value"             		=> "0",
							"min"               		=> "0",
							"max"               		=> "50",
							"step"              		=> "1",
							"unit"              		=> 'px',
							"description"       		=> __( "If image instead of icon or string, increase the image size by padding value.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "padding", 'value' => 'true' ),
							"group"						=> "Step Icon",
						),
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_5",
							"seperator"					=> "Step Icon Animation",
							"group"						=> "Step Icon",
						),
						array(
							"type"						=> "css3animations",
							"heading"					=> __("Step Icon: Animation Style", "ts_visual_composer_extend"),
							"param_name"				=> "animation_class",
							"prefix"					=> "",
							"connector"					=> "animation_name",
							"noneselect"				=> "true",
							"default"					=> "",
							"value"						=> "",
							"description"				=> __("Select the optional animation style for the step icon.", "ts_visual_composer_extend"),
							"group"						=> "Step Icon",
						),
						array(
							"type"						=> "hidden_input",
							"heading"					=> __( "Step Icon: Animation Style", "ts_visual_composer_extend" ),
							"param_name"				=> "animation_name",
							"value"						=> "",
							"admin_label"       		=> true,
							"group"						=> "Step Icon",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Step Icon: Animation Type", "ts_visual_composer_extend" ),
							"param_name"        		=> "animation_trigger",
							"value"             		=> array(
								__( "Infinite Repeating Animation", "ts_visual_composer_extend" )		=> "infinite",
								__( "Viewport Entry Animation", "ts_visual_composer_extend" )			=> "viewport",
								__( "Icon Hover Animation", "ts_visual_composer_extend" )				=> "hover",
							),
							"dependency"        => array( 'element' => "animation_class", 'not_empty' => true ),
							"description"       		=> __( "Define how the icon animation should be triggered.", "ts_visual_composer_extend" ),
							"group"						=> "Step Icon",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Animation Items Delay", "ts_visual_composer_extend" ),
							"param_name"       		 	=> "animation_delay",
							"value"             		=> "500",
							"min"               		=> "000",
							"max"               		=> "2000",
							"step"              		=> "100",
							"unit"              		=> 'ms',
							"description"       		=> __( "Define a delay before the viewport animation for the icon is triggered.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "animation_trigger", 'value' => 'viewport' ),
							"group"						=> "Step Icon",
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Allow on Mobile", "ts_visual_composer_extend" ),
							"param_name"        		=> "animation_mobile",
							"value"             		=> "false",
							"description"       		=> __( "Switch the toggle to allow the viewport animation to be used on mobile devices.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "animation_trigger", 'value' => 'viewport' ),
							"group"						=> "Step Icon",
						),
					)
				);
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
					return $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element;
				} else {			
					vc_map($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element);
				};
			}
		}
	}
	// Register Container and Child Shortcode with Visual Composer
	if ((class_exists('WPBakeryShortCodesContainer')) && (!class_exists('WPBakeryShortCode_TS_VCSC_ProcessLines_Container'))) {
		class WPBakeryShortCode_TS_VCSC_ProcessLines_Container extends WPBakeryShortCodesContainer {};
	}
	if ((class_exists('WPBakeryShortCode')) && (!class_exists('WPBakeryShortCode_TS_VCSC_ProcessLines_Item'))) {
		class WPBakeryShortCode_TS_VCSC_ProcessLines_Item extends WPBakeryShortCode {};
	}
	// Initialize "TS Process Lines" Class
	if (class_exists('TS_Process_Lines')) {
		$TS_Process_Lines = new TS_Process_Lines;
	}
?>