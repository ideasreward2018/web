<?php
	add_shortcode('TS_VCSC_Icon_Info_Box', 'TS_VCSC_Icon_Info_Box_Function');
	function TS_VCSC_Icon_Info_Box_Function ($atts, $content = null) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();
	
		extract( shortcode_atts( array(
			// Icon Settings
			'icon_decoration'				=> 'icon', // none, icon, image
			'icon_main'						=> '',
			'icon_image'					=> '',
			'icon_position'					=> 'left',
			'icon_usage'					=> 'main', // none, main, other, image			
			'icon_color'					=> '#ffffff',
			'icon_backclass'				=> '',
			'icon_backposition'				=> 'left',
			'icon_backcolor'				=> 'rgba(255, 255, 255, 0.5)',
			'icon_backimage'				=> '',
			'icon_animationtype'			=> 'none',
			'icon_animationclass'			=> '',
			'icon_animationname'			=> '',
			// Title Settings
			'title_string'					=> '',
			'title_wrapper'					=> 'div',
			'title_family'					=> 'Default',
			'title_type'					=> '',
			'title_size'					=> 24,
			'title_weight'					=> 300,
			'title_color'					=> '#ffffff',
			'title_align'					=> 'left',
			'title_transform'				=> 'uppercase',
			'title_decoration'				=> 'none',
			// Content Settings
			'content_family'				=> 'Default',
			'content_type'					=> '',
			'content_size'					=> 14,
			'content_weight'				=> 'inherit',
			'content_color'					=> '#f2f2f2',
			'content_align'					=> 'justify',
			'content_transform'				=> 'none',
			'content_decoration'			=> 'none',			
			// Box Settings
			'style_heighttype'				=> 'auto',
			'style_heightfixed'				=> 250,
			'style_backtype'				=> 'color',
			'style_backcolor'				=> '#2c3e50',
			'style_backgradient'			=> '',
			'style_backpattern'				=> '',
			'style_backimage'				=> '',
			'style_backsize'				=> 'cover',
			'style_backposition'			=> 'center center',
			'style_backrepeat'				=> 'no-repeat',
			'style_boxborder'				=> '',
			'style_bordereffect'			=> 'true',			
			'style_bordercolor1'			=> '#15273a',
			'style_bordercolor2'			=> '#15273a',
			'style_borderwidth'				=> 2,
			'style_borderspace'				=> 2,
			// Viewport Animation
			'viewport_usage'				=> 'false',			
			'viewport_class'				=> '',
			'viewport_name'					=> '',
			'viewport_limit'				=> 360,
			'viewport_delay'				=> 0,
			'viewport_offset'				=> '50%',
			// Link Settings
			'link_data'						=> '',
			'link_effect'					=> 'effect-1',
			'link_content'					=> 'Link Text',
			'link_message'					=> '',
			'link_uppercase'				=> 'true',
			'link_align'					=> 'left',
			'link_font_family' 				=> '',
			'link_font_type' 				=> '',
			'link_text_color'				=> '#d6d6d6',
			'link_text_hover'				=> '#ffffff',
			'link_message_color'			=> '#cccccc',
			'link_message_hover'			=> '#cccccc',
			'link_back_color'				=> '#001f3a',
			'link_back_hover'				=> '#011425',		
			'link_border_type'				=> 'solid',
			'link_border_width'				=> 2,
			'link_border_color'				=> '#cccccc',
			'link_border_hover'				=> '#ededed',
			// Other Settings
			'conditionals'					=> '',
			'content_wpautop'				=> 'false',
			'margin_top'                	=> 0,
			'margin_bottom'             	=> 0,
			'el_id' 						=> '',
			'el_class'                  	=> '',
			'css'							=> '',
		), $atts ));
		
		// Check Conditional Output
		$render_conditionals				= (empty($conditionals) ? true : TS_VCSC_CheckConditionalOutput($conditionals));
		if (!$render_conditionals) {
			$myvariable 					= ob_get_clean();
			return $myvariable;
		}
		
		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
			$frontend 						= "true";
		} else {
			$frontend 						= "false";
		}

		if (($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndWaypoints == "true") && ($frontend == "false") && ($viewport_usage == "true") && ($viewport_class != "")) {
			if (wp_script_is('waypoints', $list = 'registered')) {
				wp_enqueue_script('waypoints');
			} else {
				wp_enqueue_script('ts-extend-waypoints');
			}
		}
		wp_enqueue_style('ts-extend-iconboxes');
		wp_enqueue_style('ts-extend-animations');
		wp_enqueue_style('ts-visual-composer-extend-front');
		wp_enqueue_script('ts-visual-composer-extend-front');
		
		$output 							= '';
		$styles								= '';
		$wpautop 							= ($content_wpautop == "true" ? true : false);
		$inline								= TS_VCSC_FrontendAppendCustomRules('style');
		
		if (!empty($el_id)) {
			$info_box_id					= $el_id;
		} else {
			$info_box_id					= 'ts-icon-info-box-' . mt_rand(999999, 9999999);
		}
		
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Icon_Info_Box', $atts);
		} else {
			$css_class						= '';
		}
		
		// Link Data
		$link 								= TS_VCSC_Advancedlinks_GetLinkData($link_data);
		$a_href								= $link['url'];
		$a_title 							= $link['title'];
		$a_target 							= $link['target'];
		$a_rel 								= $link['rel'];
		if (!empty($a_rel)) {
			$a_rel 							= 'rel="' . esc_attr(trim($a_rel)) . '"';
		}
		
		// Relevant Classes
		if (($icon_position == "top") || ($icon_position == "bottom")) {
			$class_shadow					= 'ts-icon-info-box-shadow-' . $icon_backposition;
		} else {
			$class_shadow					= '';
		}
		if ($style_heighttype == "fixed") {
			$class_fixed					= 'ts-icon-info-box-fixed';
		} else {
			$class_fixed					= '';
		}
		if ($style_bordereffect == "true") {
			$class_border					= 'ts-icon-info-box-border';			
		} else {
			$class_border					= 'ts-icon-info-box-simple';
		}
		if ($viewport_usage == "true") {
			$class_viewport					= 'ts-icon-info-box-viewport';
		} else {
			$class_viewport					= '';
		}
		if (!empty($icon_animationclass)) {
			$class_animation				= $icon_animationtype . $icon_animationclass;
		} else {
			$class_animation				= '';
		}
		
		// Border Extractions
		$style_boxborder 					= str_replace("|", "", $style_boxborder);
		$style_boxcorners 					= explode(';', $style_boxborder);
		$style_boxtopleft					= '';
		$style_boxbottomright				= '';
		if ($style_bordereffect == "true") {
			foreach ($style_boxcorners as $key => $value) {
				if (strpos($value, 'border-top-left-radius') === 0) {
					$style_boxtopleft 		= $value . ';';
				} else if (strpos($value, 'border-bottom-right-radius') === 0) {
					$style_boxbottomright	= $value . ';';
				}
			}
		}
		
		// Font Extractions
		if (strpos($title_family, 'Default') === false) {
			$google_font_title				= TS_VCSC_GetFontFamily($info_box_id . " .ts-icon-info-box-title", $title_family, $title_type, false, true, false);
		} else {
			$google_font_title				= "";
		}
		if (strpos($content_family, 'Default') === false) {
			$google_font_content			= TS_VCSC_GetFontFamily($info_box_id . " .ts-icon-info-box-text", $content_family, $content_type, false, true, false);
		} else {
			$google_font_content			= "";
		}
		if (strpos($link_font_family, 'Default') === false) {
			$google_font_button				= TS_VCSC_GetFontFamily($info_box_id . " .ts-icon-info-box-button", $link_font_family, $link_font_type, false, true, false);
		} else {
			$google_font_button				= "";
		}
		
		// Styling Rules
		if ($inline == "false") {
			$styles .= '<style id="' . $info_box_id . '-styles" type="text/css">';
		}
			$styles .= '#' . $info_box_id . '.ts-icon-info-box-main {';
				if ($style_backtype == "color") {
					$styles .= 'background-color: ' . $style_backcolor . ';';
				} else if ($style_backtype == "gradient") {
					$styles .= $style_backgradient . ';';					
				} else if ($style_backtype == "image") {
					$background_image		= wp_get_attachment_image_src($style_backimage, 'full');
					$background_image		= $background_image[0];
					$styles .= 'background-color: transparent;';
					$styles .= 'background-image: url(' . $background_image . ');';
					$styles .= 'background-repeat: ' . $style_backrepeat . ';';
					$styles .= 'background-size: ' . $style_backsize . ';';
					$styles .= 'background-position: ' . $style_backposition . ';';
				} else if ($style_backtype == "pattern") {
					$styles .= 'background-color: transparent;';
					$styles .= 'background-image: url(' . $style_backpattern . ');';
					$styles .= 'background-repeat: repeat;';
					$styles .= 'background-size: initial;';
				} else if ($style_backtype == "transparent") {
					$styles .= 'background-color: transparent;';
				}
				$styles .= $style_boxborder;
			$styles .= '}';
			if ($style_heighttype == "fixed") {
				$styles .= '#' . $info_box_id . '.ts-icon-info-box-main .ts-icon-info-box-holder {';				
					$styles .= 'height: ' . $style_heightfixed . 'px;';
					$styles .= 'overflow-y: auto;';
				$styles .= '}';
			}
			if ($style_bordereffect == "true") {
				$styles .= '#' . $info_box_id . '.ts-icon-info-box-main.ts-icon-info-box-border {';
					$styles .= 'margin: ' . ($style_borderwidth + $style_borderspace) . 'px;';
				$styles .= '}';
				$styles .= '#' . $info_box_id . '.ts-icon-info-box-main.ts-icon-info-box-border:before {';
					$styles .= 'border-color: ' . $style_bordercolor1 . ';';
					$styles .= $style_boxtopleft;
					$styles .= 'top: -' . ($style_borderwidth + $style_borderspace) . 'px;';
					$styles .= 'left: -' . ($style_borderwidth + $style_borderspace) . 'px;';
					$styles .= 'border-width: ' . $style_borderwidth . 'px 0 0 ' . $style_borderwidth . 'px !important;';
				$styles .= '}';
				$styles .= '#' . $info_box_id . '.ts-icon-info-box-main.ts-icon-info-box-border:after {';
					$styles .= 'border-color: ' . $style_bordercolor2 . ';';
					$styles .= $style_boxbottomright;
					$styles .= 'bottom: -' . ($style_borderwidth + $style_borderspace) . 'px;';
					$styles .= 'right: -' . ($style_borderwidth + $style_borderspace) . 'px;';
					$styles .= 'border-width: 0 ' . $style_borderwidth . 'px ' . $style_borderwidth . 'px 0 !important;';
				$styles .= '}';
			}
			$styles .= '#' . $info_box_id . '.ts-icon-info-box-main .ts-icon-info-box-foreicon {';
				$styles .= 'color: ' . $icon_color . ';';
				if ($icon_position == "left") {
					$styles .= 'text-align: left;';
				} else if ($icon_position == "right") {
					$styles .= 'text-align: right;';
				} else {
					$styles .= 'text-align: center;';
				}
			$styles .= '}';
			$styles .= '#' . $info_box_id . '.ts-icon-info-box-main .ts-icon-info-box-backicon {';
				$styles .= 'color: ' . $icon_backcolor . ';';
			$styles .= '}';
			$styles .= '#' . $info_box_id . '.ts-icon-info-box-main .ts-icon-info-box-title {';
				$styles .= $google_font_title;
				$styles .= 'font-size: ' . $title_size . 'px;';
				$styles .= 'font-weight: ' . $title_weight . ';';
				$styles .= 'text-transform: ' . $title_transform . ';';
				$styles .= 'text-decoration: ' . $title_decoration . ';';
				$styles .= 'text-align: ' . $title_align . ';';
				$styles .= 'color: ' . $title_color . ';';
			$styles .= '}';
			$styles .= '#' . $info_box_id . '.ts-icon-info-box-main .ts-icon-info-box-text {';
				$styles .= $google_font_content;
				$styles .= 'font-size: ' . $content_size . 'px;';
				$styles .= 'font-weight: ' . $content_weight . ';';
				$styles .= 'text-transform: ' . $content_transform . ';';
				$styles .= 'text-decoration: ' . $content_decoration . ';';
				$styles .= 'text-align: ' . $content_align . ';';
				$styles .= 'color: ' . $content_color . ';';
			$styles .= '}';
		if ($inline == "false") {
			$styles .= '</style>';
		}
		if (($styles != "") && ($inline == "true")) {
			wp_add_inline_style('ts-visual-composer-extend-custom', TS_VCSC_MinifyCSS($styles));
		}
		
		// Animation Data
		$box_animation						= 'data-viewport-frontend="' . $frontend . '" data-viewport-use="' . $viewport_usage . '" data-viewport-limit="' . $viewport_limit . '" data-viewport-class="' . $viewport_class . '" data-viewport-opacity="1" data-viewport-delay="' . $viewport_delay . '" data-viewport-offset="' . $viewport_offset . '"';

		// Final Output
		$output .= '<div id="' . $info_box_id . '" class="ts-icon-info-box-main ts-icon-info-box-' . $icon_position . ' ' . $class_shadow . ' ' . $class_fixed . ' ' . $class_viewport . ' ' . $class_border . '" ' . $box_animation . '>';
			if ($inline == "false") {
				$output .= TS_VCSC_MinifyCSS($styles);
			}
			$output .= '<div class="ts-icon-info-box-wrapper">';
				if ((($icon_usage == "main") && ($icon_main != "")) || (($icon_usage == "other") && ($icon_backclass != "")) || (($icon_usage == "image") && ($icon_backimage != ""))) {
					$output .= '<div class="ts-icon-info-box-backicon">';
						if (($icon_usage == "image") && ($icon_backimage != "")) {
							$icon_backimage			= wp_get_attachment_image_src($icon_backimage, 'medium');
							$output .= '<img src="' . $icon_backimage[0] . '"/>';
						} else {
							$output .= '<i class="' . ($icon_usage == "main" ? $icon_main : $icon_backclass) . '"></i>';
						}
					$output .= '</div>';
				}
				$output .= '<div class="ts-icon-info-box-holder">';
					$output .= '<div class="ts-icon-info-box-inner">';
						if (((($icon_decoration == "icon") && ($icon_main != "")) || (($icon_decoration == "image") && ($icon_image != ""))) && (($icon_position == "left") || ($icon_position == "right") || ($icon_position == "top"))) {
							$output .= '<div class="ts-icon-info-box-foreicon">';
								if (($icon_decoration == "image") && ($icon_image != "")) {
									$icon_image		= wp_get_attachment_image_src($icon_image, 'medium');
									$output .= '<img class="' . $class_animation . '" src="' . $icon_image[0] . '"/>';
								} else {
									$output .= '<i class="' . $icon_main . ' ' . $class_animation . '"></i>';
								}
							$output .= '</div>';
						}
						$output .= '<div class="ts-icon-info-box-content">';
							$output .= '<' . $title_wrapper . ' class="ts-icon-info-box-title">' . $title_string . '</' . $title_wrapper . '>';
							$output .= '<div class="ts-icon-info-box-text">';
								if (function_exists('wpb_js_remove_wpautop')){
									$output .= '<div class="" style="">' . wpb_js_remove_wpautop(do_shortcode($content), $wpautop) . '</div>';
								} else {
									$output .= '<div class="" style="">' . do_shortcode($content) . '</div>';
								}
							$output .= '</div>';
							if (($link_data != "") && ($a_href != "")) {
								$output .= '<div class="ts-icon-info-box-button" style="text-align: ' . $content_align . ';">';
									if (shortcode_exists('TS_VCSC_Creative_Link')) {
										$link_attributes = 'scroll_navigate="false" link="' . $link_data . '" link_effect="' . $link_effect . '" link_content="' . $link_content . '" link_message="' . $link_message . '" link_align="' . $link_align . '"';
										$link_attributes .= ' link_uppercase="' . $link_uppercase . '" link_font_family="' . $link_font_family . '" link_font_type="' . $link_font_type . '" link_text_color="' . $link_text_color . '" link_text_hover="' . $link_text_hover . '"';
										$link_attributes .= ' link_message_color="' . $link_message_color . '" link_message_hover="' . $link_message_hover . '" link_back_color="' . $link_back_color . '" link_back_hover="' . $link_back_hover . '"';
										$link_attributes .= ' link_border_type="' . $link_border_type . '" link_border_width="' . $link_border_width . '" link_border_color="' . $link_border_color . '" link_border_hover="' . $link_border_hover . '"';
										if (($link_effect == "effect-12") || ($link_effect == "effect-18")) {
											$link_attributes .= ' margin_top="40" margin_bottom="20"';
										} else {
											$link_attributes .= ' margin_bottom="0"';
										}
										$output .= do_shortcode('[TS_VCSC_Creative_Link ' . $link_attributes . ']');
									} else {
										$output .= '<a href="' . $a_href . '" target="' . $a_target . '" title="' . $a_title . '" ' . $a_rel . ' style="margin-top: 20px; ' . $google_font_button . '">' . $link_content . '</a>';
									}
								$output .= '</div>';
							}
						$output .= '</div>';
						if (((($icon_decoration == "icon") && ($icon_main != "")) || (($icon_decoration == "image") && ($icon_image != ""))) && ($icon_position == "bottom")) {
							$output .= '<div class="ts-icon-info-box-foreicon">';
								if (($icon_decoration == "image") && ($icon_image != "")) {
									$icon_image		= wp_get_attachment_image_src($icon_image, 'medium');
									$output .= '<img class="' . $class_animation . '" src="' . $icon_image[0] . '"/>';
								} else {
									$output .= '<i class="' . $icon_main . ' ' . $class_animation . '"></i>';
								}
							$output .= '</div>';
						}
					$output .= '</div>';
					$output .= '<div class="clearFixMe"></div>';	
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';
		
		echo $output;
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>