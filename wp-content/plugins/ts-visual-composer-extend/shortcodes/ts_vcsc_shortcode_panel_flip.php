<?php
	add_shortcode('TS_VCSC_Panel_Flip', 'TS_VCSC_Panel_Flip_Function');
	function TS_VCSC_Panel_Flip_Function ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();
		
		extract( shortcode_atts( array(
			// Effect Settings
			'flip_style'				=> 'style1',
			'flip_effect_style1'		=> 'horizontal flip-container-ltr',
			'flip_effect_style2'		=> 'ts-flip-right',
			'flip_effect_speed'			=> 'medium',
			'flip_effect_trigger'		=> 'hover',
			// Size Settings
			'flip_size_auto'			=> 'true',
			'flip_size_type'			=> 'fixed',
			'flip_size'					=> 200,
			'flip_size_min'				=> 200,
			'flip_size_max'				=> 200,
			// Indicator Settings
			'flip_handle_show'			=> 'false',
			'flip_handle_color'			=> '#0094FF',
			// Front Panel Settings
			'front_banner_use'			=> 'false',
			'front_banner_image'		=> '',
			'front_icon_type'			=> 'icon',
			'front_icon_font'			=> '',
			'front_icon_image'			=> '',
			'front_icon_customize'		=> 'false',
			'front_icon_size'			=> 70,
			'front_icon_color'			=> '#000000',
			'front_icon_background'		=> 'transparent',
			'front_icon_padding'		=> 'padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;',
			'front_icon_border'			=> '',
			'front_icon_animation'		=> '',
			'front_title_string'		=> '',
			'front_title_customize'		=> 'false',
			'front_title_wrapper'		=> 'h3',
			'front_title_color'			=> '#000000',
			'front_content_html'		=> '',
			'front_content_customize'	=> 'false',
			'front_content_color'		=> '#000000',
			'front_content_line'		=> 120,
			'front_panel_customize'		=> 'false',
			'front_panel_styling'		=> 'color',
			'front_panel_color'			=> '#ffffff',
			'front_panel_gradient'		=> '',
			'front_panel_pattern'		=> '',
			'front_panel_border'		=> '',
			// Back Panel Settings
			'back_banner_use'			=> 'false',
			'back_banner_image'			=> '',
			'back_icon_type'			=> 'icon',
			'back_icon_font'			=> '',
			'back_icon_image'			=> '',
			'back_icon_customize'		=> 'false',
			'back_icon_size'			=> 70,
			'back_icon_color'			=> '#000000',
			'back_icon_background'		=> 'transparent',
			'back_icon_padding'			=> 'padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;',
			'back_icon_border'			=> '',
			'back_icon_animation'		=> '',
			'back_title_string'			=> '',
			'back_title_customize'		=> 'false',
			'back_title_wrapper'		=> 'h3',
			'back_title_color'			=> '#000000',
			'back_content_html'			=> '',
			'back_content_customize'	=> 'false',
			'back_content_color'		=> '#000000',
			'back_content_line'			=> 120,
			'back_panel_customize'		=> 'false',
			'back_panel_styling'		=> 'color',
			'back_panel_color'			=> '#ffffff',
			'back_panel_gradient'		=> '',
			'back_panel_pattern'		=> '',
			'back_panel_border'			=> '',
			// Link Settings
			'link_usage'				=> 'false',
			'link_data'					=> '',
			'link_effect'				=> 'effect-1',
			'link_content'				=> 'Link Text',
			'link_message'				=> '',
			'link_uppercase'			=> 'true',
			'link_align'				=> 'center',
			'link_font_family' 			=> '',
			'link_font_type' 			=> '',
			'link_text_color'			=> '#9a9a9a',
			'link_text_hover'			=> '#717171',
			'link_message_color'		=> '#cccccc',
			'link_message_hover'		=> '#cccccc',
			'link_back_color'			=> '#001f3a',
			'link_back_hover'			=> '#011425',		
			'link_border_type'			=> 'solid',
			'link_border_width'			=> 2,
			'link_border_color'			=> '#cccccc',
			'link_border_hover'			=> '#ededed',
			// Tooltip Settings
			'tooltip_usage'				=> 'false',
			'tooltip_content'			=> '',
			'tooltip_position'			=> 'ts-simptip-position-top',
			'tooltip_style'				=> 'ts-simptip-style-black',
			'tooltip_arrow'				=> 'true',
			'tooltip_background'		=> '#000000',
			'tooltip_border'			=> '#000000',
			'tooltip_color'				=> '#ffffff',
			'tooltip_animation'			=> 'swing',
			'tooltip_offsetx'			=> 0,
			'tooltip_offsety'			=> 0,
			// Font Settings
			'font_fronttitle_family'	=> 'Default',
			'font_fronttitle_type'		=> '',
			'font_fronttitle_size'		=> 18,
			'font_frontcontent_family'	=> 'Default',
			'font_frontcontent_type'	=> '',
			'font_frontcontent_size'	=> 13,
			'font_backtitle_family'		=> 'Default',
			'font_backtitle_type'		=> '',
			'font_backtitle_size'		=> 18,
			'font_backcontent_family'	=> 'Default',
			'font_backcontent_type'		=> '',
			'font_backcontent_size'		=> 13,
			// Animation Settings
			'animation_fronticon'		=> '',
			'animation_frontname'		=> '',
			'animation_backicon'		=> '',
			'animation_backname'		=> '',
			'viewport_class'            => '',
			'viewport_name'				=> '',
			'viewport_offset'			=> '50%',
			'viewport_delay'			=> 0,
			'viewport_limit'			=> 360,
			// Other Settings
			'conditionals'				=> '',
			'content_wpautop'			=> 'false',
			'margin_bottom'				=> '0',
			'margin_top' 				=> '0',
			'el_id' 					=> '',
			'el_class'                  => '',
			'css'						=> '',
		), $atts ));
		
		// Check Conditional Output
		$render_conditionals			= (empty($conditionals) ? true : TS_VCSC_CheckConditionalOutput($conditionals));
		if (!$render_conditionals) {
			$myvariable 				= ob_get_clean();
			return $myvariable;
		}
		
		// Frontend Editor Detection
		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
			$frontend 					= "true";
		} else {
			$frontend 					= "false";
		}
		
		// Load JS/CSS Files
		if (($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndWaypoints == "true") && ($frontend == "false") && ($viewport_class != "")) {
			if (wp_script_is('waypoints', $list = 'registered')) {
				wp_enqueue_script('waypoints');
			} else {
				wp_enqueue_script('ts-extend-waypoints');
			}
		}
		wp_enqueue_style('ts-extend-animations');
		wp_enqueue_style('ts-extend-iconboxes');
		wp_enqueue_style('ts-visual-composer-extend-front');
		wp_enqueue_script('ts-visual-composer-extend-front');
	
		$output 						= '';
		$randomizer						= mt_rand(999999, 9999999);
		$wpautop 						= ($content_wpautop == "true" ? true : false);
		
		// Random ID Generator
		if (!empty($el_id)) {
			$flip_box_id				= $el_id;
		} else {
			$flip_box_id				= 'ts-vcsc-flip-box-tiny-' . $randomizer;
		}
		
		// Banner Images
		if (($front_banner_use == "true") && ($front_banner_image != "")) {
			$front_banner_path 			= wp_get_attachment_image_src($front_banner_image, 'large');
			$front_banner_adjust		= 'padding: 0px;';
		} else {
			$front_banner_path			= '';
			$front_banner_adjust		= '';
		}
		if (($back_banner_use == "true") && ($back_banner_image != "")) {
			$back_banner_path 			= wp_get_attachment_image_src($back_banner_image, 'large');
			$back_banner_adjust			= 'padding: 0px;';
		} else {
			$back_banner_path			= '';
			$back_banner_adjust			= '';
		}
		
		// Icon Font / Image
		if (($front_icon_type == "image") && (!empty($front_icon_image))) {
			$front_image_path 			= wp_get_attachment_image_src($front_icon_image, 'large');
			$front_image_style			= $front_icon_padding . ' background-color:' . $front_icon_background . '; width: ' . $front_icon_size . 'px; height: auto; ';	
		} else {
			$front_icon_style			= 'background-color:' . $front_icon_background . '; width: ' . $front_icon_size . 'px; height: ' . $front_icon_size . 'px; font-size: ' . $front_icon_size . 'px; line-height: ' . $front_icon_size . 'px;';
		}
		if (($back_icon_type == "image") && (!empty($back_icon_image))) {
			$back_image_path 			= wp_get_attachment_image_src($back_icon_image, 'large');
			$back_image_style			= $back_icon_padding . ' background-color:' . $back_icon_background . '; width: ' . $back_icon_size . 'px; height: auto; ';	
		} else {
			$back_icon_style			= 'background-color:' . $back_icon_background . '; width: ' . $back_icon_size . 'px; height: ' . $back_icon_size . 'px; font-size: ' . $back_icon_size . 'px; line-height: ' . $back_icon_size . 'px;';
		}

		// Contingency Adjustments
		$front_icon_border				= str_replace("|", "", $front_icon_border);
		$front_panel_border				= str_replace("|", "", $front_panel_border);
		$back_icon_border				= str_replace("|", "", $back_icon_border);
		$back_panel_border				= str_replace("|", "", $back_panel_border);
		
		// Link Data
		if ($link_usage == "true") {
			$link 						= TS_VCSC_Advancedlinks_GetLinkData($link_data);
			$a_href						= $link['url'];
			$a_title 					= $link['title'];
			$a_target 					= $link['target'];
			$a_rel 						= $link['rel'];
			if (!empty($a_rel)) {
				$a_rel 					= 'rel="' . esc_attr(trim($a_rel)) . '"';
			}
		}
		
		// Panel Backgrounds
		if ($front_panel_styling == "color") {
			$front_panel_background		= 'background: ' . $front_panel_color . ';';
		} else if ($front_panel_styling == "gradient") {
			$front_panel_background		= $front_panel_gradient;
		} else if ($front_panel_styling == "pattern") {
			$front_panel_background		= 'background: url(' . $front_panel_pattern . ') repeat;';
		} else {
			$front_panel_background		= 'background: transparent;';
		}
		if ($back_panel_styling == "color") {
			$back_panel_background		= 'background: ' . $back_panel_color . ';';
		} else if ($back_panel_styling == "gradient") {
			$back_panel_background		= $back_panel_gradient;
		} else if ($back_panel_styling == "pattern") {
			$back_panel_background		= 'background: url(' . $back_panel_pattern . ') repeat;';
		} else {
			$back_panel_background		= 'background: transparent;';
		}
		
		// Trigger Type Adjustments
		if ($flip_effect_trigger == "hover") {
			if ($flip_style == "style1") {
				$flipper_type			= 'flip-container-frame-hover';
			} else if ($flip_style == "style2") {
				$flipper_type			= 'ts-flip-cube-hover';
			}
		} else if ($flip_effect_trigger == "click") {
			if ($flip_style == "style1") {
				$flipper_type			= 'flip-container-frame-click';
			} else if ($flip_style == "style2") {
				$flipper_type			= 'ts-flip-cube-click';
			}
		}

		// Flip Effect Speed
		if ($flip_effect_speed == "veryslow") {
			$effectspeed				= 2000;
		} else if ($flip_effect_speed == "slow") {
			$effectspeed				= 1500;
		} else if ($flip_effect_speed == "medium") {
			$effectspeed				= 1000;
		} else if ($flip_effect_speed == "fast") {
			$effectspeed				= 750;
		} else if ($flip_effect_speed == "veryfast") {
			$effectspeed				= 500;
		}
		
		// Viewport Animation
		if ($viewport_class != '') {
			$viewport_animation			= 'data-viewport-frontend="' . $frontend . '" data-viewport-use="' . ($viewport_class != "" ? "true" : "false") . '" data-viewport-limit="' . $viewport_limit . '" data-viewport-class="' . $viewport_class . '" data-viewport-opacity="1" data-viewport-delay="' . $viewport_delay . '" data-viewport-offset="' . $viewport_offset . '"';
			$viewport_classname			= 'ts-has-viewport-animation';
		} else {
			$viewport_animation 		= '';
			$viewport_classname			= '';
		}
				
		// Height Settings
		if ($flip_size_auto == "false") {
			if ($flip_size_type == 'fixed') {
				$content_height			= 'height: ' . $flip_size .  'px;';
			} else if ($flip_size_type == 'minimum') {
				$content_height			= 'min-height: ' . $flip_size .  'px;';
			} else if ($flip_size_type == 'maximum') {
				$content_height			= 'max-height: ' . $flip_size .  'px;';
			}			
		} else {
			$content_height				= '';
		}
		
		// Handle Padding
		if (($flip_handle_show == "true") && ($flip_style == "style2")) {
			$flip_handle_padding		= "padding-bottom: 25px;";
			$flip_handle_adjust  		= "";
		} else {
			$flip_handle_padding		= "";
			$flip_handle_adjust  		= "";
		}
		// Handle Icon
		if ($flip_effect_trigger == "click") {
			$flip_handle_icon			= 'handle_click';
		} else {
			$flip_handle_icon			= 'handle_hover';
		}
		
		// Custom Font Settings
		$google_font_fronttitle			= '';
		$google_font_frontcontent		= '';
		$google_font_backtitle			= '';
		$google_font_backcontent		= '';
		if (($front_title_customize == "true") && (strpos($font_fronttitle_family, 'Default') === false)) {
			$google_font_fronttitle		= TS_VCSC_GetFontFamily($flip_box_id . " h3.ts-flip-front-title", $font_fronttitle_family, $font_fronttitle_type, false, true, false);
		}
		if (($front_content_customize == "true") && (strpos($font_frontcontent_family, 'Default') === false)) {
			$google_font_frontcontent	= TS_VCSC_GetFontFamily($flip_box_id . " .ts-flip-front-content", $font_frontcontent_family, $font_frontcontent_type, false, true, false);
		}	
		if (($back_title_customize == "true") && (strpos($font_backtitle_family, 'Default') === false)) {
			$google_font_backtitle		= TS_VCSC_GetFontFamily($flip_box_id . " h3.ts-flip-back-title", $font_backtitle_family, $font_backtitle_type, false, true, false);
		}
		if (($back_content_customize == "true") && (strpos($font_backcontent_family, 'Default') === false)) {
			$google_font_backcontent	= TS_VCSC_GetFontFamily($flip_box_id . " .ts-flip-back-content", $font_backcontent_family, $font_backcontent_type, false, true, false);
		}
		
		// Tooltip	
		if (($tooltip_usage == "true") && (strip_tags($tooltip_content) != '')) {
			$tooltip_position			= TS_VCSC_TooltipMigratePosition($tooltip_position);
			$tooltip_style				= TS_VCSC_TooltipMigrateStyle($tooltip_style);
			wp_enqueue_style('ts-extend-tooltipster');
			wp_enqueue_script('ts-extend-tooltipster');	
			$Tooltip_Content			= 'data-tooltipster-html="true" data-tooltipster-title="" data-tooltipster-text="' . strip_tags($tooltip_content) . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="' . $tooltip_arrow . '" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="' . $tooltip_animation . '" data-tooltipster-trigger="hover" data-tooltipster-background="' . $tooltip_background . '" data-tooltipster-border="' . $tooltip_border . '" data-tooltipster-color="' . $tooltip_color . '" data-tooltipster-offsetx="' . $tooltip_offsetx . '" data-tooltipster-offsety="' . $tooltip_offsety . '"';
			$Tooltip_Class				= 'ts-has-tooltipster-tooltip';
		} else {
			$Tooltip_Content			= '';
			$Tooltip_Class				= '';
		}
		
		// Visual Composer Override
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 					= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Panel_Flip', $atts);
		} else {
			$css_class					= '';
		}
		
		// Create Final Output
		if ($flip_style == "style1") {
			$output .= '<div id="' . $flip_box_id . '" ' . $viewport_animation . ' class="ts-panel-flip-wrapper flip-container-frame ts-flip-tiny-container ' . $flipper_type . ' ' . $viewport_classname . ' ' . $el_class . ' ' . ($flip_size_auto == "true" ? "auto" : "fixed") . ' ' . $css_class . '" data-trigger="' . $flip_effect_trigger . '" data-autoheight="' . $flip_size_auto . '" data-fixedtype="' . $flip_size_type . '" data-fixedheight="' . $flip_size . '" style="' . $content_height . ' width: 100%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
				if ($flip_handle_show == "true") {
					$output .= '<div class="ts-flip-padding" style="' . $flip_handle_padding . ' ' . $content_height . '">';
				}
					$output .= '<div class="flip-container-main ' . $flip_effect_style1 . ' ' . $flip_effect_speed . '" data-speed="' . $effectspeed . '" style="' . $content_height . '">';
						$output .= '<div class="flip-container-flipper" style="' . $content_height . '">';
							$output .= '<div class="flip-container-flipper-content flip-container-flipper-front ' . $flip_effect_speed . '" style="' . ($flip_size_auto == "true" ? "" : "height: 100%; ") . $content_height . ' width: 100%; ' . $front_panel_background . '; ' . $front_panel_border . '">';
								$output .= '<div class="ts-flip-content" style="color: ' . $front_content_color . '; ' . $front_banner_adjust . '">';
									// Front Panel Banner
									if (($front_banner_use == "true") && (isset($front_banner_path[0]))) {
										$output .= '<img class="ts-flip-front-banner" src="' . $front_banner_path[0] . '">';
									}
									// Front Panel Icon
									if (($front_icon_type == "icon") && ($front_icon_font != '')) {
										$output .= '<i style="' . $front_icon_padding . ' color:' . $front_icon_color . ';' . $front_icon_style . ' ' . $front_icon_border . '" class="ts-flip-front-icon ts-font-icon ' . $front_icon_font . ' ' . $animation_fronticon . '" data-animation="' . $animation_fronticon . '"></i>';
									} else if (($front_icon_type == "image") && (isset($front_image_path[0]))) {
										$output .= '<img src="' . $front_image_path[0] . '" style="' . $front_image_style . ' ' . $front_icon_border . '" class="ts-flip-front-icon ts-font-icon ' . $animation_fronticon . '" data-animation="' . $animation_fronticon . '">';
									}
									// Front Panel Title
									if ($front_title_string != "") {
										$output .= '<' . $front_title_wrapper . ' class="ts-flip-front-title" style="color: ' . $front_title_color . '; ' . $google_font_fronttitle . '">' . rawurldecode(base64_decode(strip_tags($front_title_string))) . '</' . $front_title_wrapper . '>';
									}
									// Front Panel Content
									if ($front_content_html != "") {
										$output .= '<div class="ts-flip-front-content ts-flip-text" style="line-height: ' . $front_content_line . '%; ' . $google_font_frontcontent . '">' . do_shortcode(rawurldecode(base64_decode(strip_tags($front_content_html)))) . '</div>';
									}
								$output .= '</div>';
							$output .= '</div>';
							$output .= '<div class="flip-container-flipper-content flip-container-flipper-back ' . $flip_effect_speed . '" style="' . ($flip_size_auto == "true" ? "" : "height: 100%; ") . $content_height . ' width: 100%; ' . $back_panel_background . '; ' . $back_panel_border . '">';
								$output .= '<div class="ts-flip-content" style="color: ' . $back_content_color . '; ' . $back_banner_adjust . '">';
									// Back Panel Banner
									if (($back_banner_use == "true") && (isset($back_banner_path[0]))) {
										$output .= '<img class="ts-flip-back-banner" src="' . $back_banner_path[0] . '">';
									}
									// Back Panel Icon
									if (($back_icon_type == "icon") && ($back_icon_font != '')) {
										$output .= '<i style="' . $back_icon_padding . ' color:' . $back_icon_color . ';' . $back_icon_style . ' ' . $back_icon_border . '" class="ts-flip-back-icon ts-font-icon ' . $back_icon_font . ' ' . $animation_backicon . '" data-animation="' . $animation_backicon . '"></i>';
									} else if (($back_icon_type == "image") && (isset($back_image_path[0]))) {
										$output .= '<img src="' . $back_image_path[0] . '" style="' . $back_image_style . ' ' . $back_icon_border . '" class="ts-flip-back-icon ts-font-icon ' . $animation_backicon . '" data-animation="' . $animation_backicon . '">';
									}
									// Back Panel Title
									if ($back_title_string != "") {
										$output .= '<' . $back_title_wrapper . ' class="ts-flip-back-title" style="color: ' . $back_title_color . '; ' . $google_font_backtitle . '">' . rawurldecode(base64_decode(strip_tags($back_title_string))) . '</' . $back_title_wrapper . '>';
									}
									// Back Panel Content
									if ($back_content_html != "") {
										$output .= '<div class="ts-flip-back-content ts-flip-text" style="line-height: ' . $back_content_line . '%; ' . $google_font_backcontent . '">' . do_shortcode(rawurldecode(base64_decode(strip_tags($back_content_html)))) . '</div>';
									}
									// Back Panel Link
									if ($link_usage == "true") {
										if (($link_data != "") && ($a_href != "")) {
											if ($link_effect == "effect-12") {
												$link_margin = 'margin-top: 30px; margin-bottom: 50px;';
											} else if ($link_effect == "effect-18") {
												$link_margin = 'margin-top: 30px; margin-bottom: 40px;';
											} else {
												$link_margin = 'margin-top: 10px; margin-bottom: 20px;';
											}
											$output .= '<div class="ts-flip-back-button ' . $Tooltip_Class .' " style="text-align: ' . $link_align . '; ' . $link_margin . '" ' . $Tooltip_Content . '>';
												if (shortcode_exists('TS_VCSC_Creative_Link')) {
													$link_attributes = 'scroll_navigate="false" link="' . $link_data . '" link_effect="' . $link_effect . '" link_content="' . $link_content . '" link_message="' . $link_message . '" link_align="' . $link_align . '"';
													$link_attributes .= ' link_uppercase="' . $link_uppercase . '" link_font_family="' . $link_font_family . '" link_font_type="' . $link_font_type . '" link_text_color="' . $link_text_color . '" link_text_hover="' . $link_text_hover . '"';
													$link_attributes .= ' link_message_color="' . $link_message_color . '" link_message_hover="' . $link_message_hover . '" link_back_color="' . $link_back_color . '" link_back_hover="' . $link_back_hover . '"';
													$link_attributes .= ' link_border_type="' . $link_border_type . '" link_border_width="' . $link_border_width . '" link_border_color="' . $link_border_color . '" link_border_hover="' . $link_border_hover . '"';
													$link_attributes .= ' margin_top="0" margin_bottom="0"';
													$output .= do_shortcode('[TS_VCSC_Creative_Link ' . $link_attributes . ']');
												} else {
													$output .= '<a href="' . $a_href . '" target="' . $a_target . '" title="' . $a_title . '" ' . $a_rel . ' style="margin-top: 20px; ' . $google_font_button . '">' . $link_content . '</a>';
												}
												$output .= '<div class="clearFixMe"></div>';
											$output .= '</div>';											
										}
									}
								$output .= '</div>';
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';
					if ($flip_handle_show == "true") {
						$output .= '<div class="ts-image-hover-handle" style="' . $flip_handle_adjust . '"><span class="frame_' . $flip_handle_icon . '" style="background-color: ' . $flip_handle_color . '"><i class="' . $flip_handle_icon . '"></i></span></div>';
					}
				if ($flip_handle_show == "true") {
					$output .= '</div>';
				}
			$output .= '</div>';
		} else if ($flip_style == "style2") {
			$output .= '<div class="clearfix">';
				$output .= '<div id="' . $flip_box_id . '" ' . $viewport_animation . ' class="ts-panel-flip-wrapper ts-flip-cube ' . $flip_effect_style2 . ' ' . $flipper_type . ' ' . $viewport_classname . ' ' . $el_class . ' ' . ($flip_size_auto == "true" ? "auto" : "fixed") . '  ' . $css_class . '" data-trigger="' . $flip_effect_trigger . '" data-autoheight="' . $flip_size_auto . '" data-fixedtype="' . $flip_size_type . '" data-fixedheight="' . $flip_size . '" style="' . $content_height . 'width: 100%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					if ($flip_handle_show == "true") {
						$output .= '<div class="ts-flip-padding" style="' . $flip_handle_padding . ' ' . $content_height . '">';
					}
						$output .= '<div class="ts-object ts-flip-cube-object ' . $flip_effect_speed . '" data-speed="' . $effectspeed . '" style="height: 100%; width: 100%;">';
							$output .= '<div class="ts-front ts-flip-cube-front ' . $flip_effect_speed . '" style="height: 100%; width: 100%; ' . $front_panel_background . '; ' . $front_panel_border . '">';
								$output .= '<div class="ts-flip-content" style="color: ' . $front_content_color . '; ' . $front_banner_adjust . '">';
									// Front Panel Banner
									if (($front_banner_use == "true") && (isset($front_banner_path[0]))) {
										$output .= '<img class="ts-flip-front-banner" src="' . $front_banner_path[0] . '">';
									}
									// Front Panel Icon
									if (($front_icon_type == "icon") && ($front_icon_font != '')) {
										$output .= '<i style="' . $front_icon_padding . ' color:' . $front_icon_color . ';' . $front_icon_style . ' ' . $front_icon_border . '" class="ts-font-icon ' . $front_icon_font . ' ' . $animation_fronticon . '" data-animation="' . $animation_fronticon . '"></i>';
									} else if (($front_icon_type == "image") && (isset($front_image_path[0]))) {
										$output .= '<img src="' . $front_image_path[0] . '" style="' . $front_image_style . ' ' . $front_icon_border . '" class="ts-flip-front-icon ts-font-icon ' . $animation_fronticon . '" data-animation="' . $animation_fronticon . '">';
									}
									// Front Panel Title
									if ($front_title_string != "") {
										$output .= '<' . $front_title_wrapper . ' class="ts-flip-front-title" style="color: ' . $front_title_color . '; ' . $google_font_fronttitle . '">' . rawurldecode(base64_decode(strip_tags($front_title_string))) . '</' . $front_title_wrapper . '>';
									}
									// Front Panel Content
									if ($front_content_html != "") {
										$output .= '<div class="ts-flip-front-content ts-flip-text" style="line-height: ' . $front_content_line . '%; ' . $google_font_frontcontent . '">' . do_shortcode(rawurldecode(base64_decode(strip_tags($front_content_html)))) . '</div>';
									}
								$output .= '</div>';
							$output .= '</div>';
							$output .= '<div class="ts-back ts-flip-cube-back ' . $flip_effect_speed . '" style="height: 100%; width: 100%; ' . $back_panel_background . '; ' . $back_panel_border . '">';
								$output .= '<div class="ts-flip-content" style="color: ' . $back_content_color . '; ' . $back_banner_adjust . '">';
									// Back Panel Banner
									if (($back_banner_use == "true") && (isset($back_banner_path[0]))) {
										$output .= '<img class="ts-flip-back-banner" src="' . $back_banner_path[0] . '">';
									}
									// Back Panel Icon
									if (($back_icon_type == "icon") && ($back_icon_font != '')) {
										$output .= '<i style="' . $back_icon_padding . ' color:' . $back_icon_color . ';' . $back_icon_style . ' ' . $back_icon_border . '" class="ts-flip-back-icon ts-font-icon ' . $back_icon_font . ' ' . $animation_backicon . '" data-animation="' . $animation_backicon . '"></i>';
									} else if (($back_icon_type == "image") && (isset($back_image_path[0]))) {
										$output .= '<img src="' . $back_image_path[0] . '" style="' . $back_image_style . ' ' . $back_icon_border . '" class="ts-flip-back-icon ts-font-icon ' . $animation_backicon . '" data-animation="' . $animation_backicon . '">';
									}
									// Back Panel Title
									if ($back_title_string != "") {
										$output .= '<' . $back_title_wrapper . ' class="ts-flip-back-title" style="color: ' . $back_title_color . '; ' . $google_font_backtitle . '">' . rawurldecode(base64_decode(strip_tags($back_title_string))) . '</' . $back_title_wrapper . '>';
									}
									// Back Panel Content
									if ($back_content_html != "") {
										$output .= '<div class="ts-flip-back-content ts-flip-text" style="line-height: ' . $back_content_line . '%; ' . $google_font_backcontent . '">' . do_shortcode(rawurldecode(base64_decode(strip_tags($back_content_html)))) . '</div>';
									}
									// Back Panel Link
									if ($link_usage == "true") {
										if (($link_data != "") && ($a_href != "")) {
											if ($link_effect == "effect-12") {
												$link_margin = 'margin-top: 30px; margin-bottom: 50px;';
											} else if ($link_effect == "effect-18") {
												$link_margin = 'margin-top: 30px; margin-bottom: 40px;';
											} else {
												$link_margin = 'margin-top: 10px; margin-bottom: 20px;';
											}
											$output .= '<div class="ts-flip-back-button ' . $Tooltip_Class .' " style="text-align: ' . $link_align . '; ' . $link_margin . '" ' . $Tooltip_Content . '>';
												if (shortcode_exists('TS_VCSC_Creative_Link')) {
													$link_attributes = 'scroll_navigate="false" link="' . $link_data . '" link_effect="' . $link_effect . '" link_content="' . $link_content . '" link_message="' . $link_message . '" link_align="' . $link_align . '"';
													$link_attributes .= ' link_uppercase="' . $link_uppercase . '" link_font_family="' . $link_font_family . '" link_font_type="' . $link_font_type . '" link_text_color="' . $link_text_color . '" link_text_hover="' . $link_text_hover . '"';
													$link_attributes .= ' link_message_color="' . $link_message_color . '" link_message_hover="' . $link_message_hover . '" link_back_color="' . $link_back_color . '" link_back_hover="' . $link_back_hover . '"';
													$link_attributes .= ' link_border_type="' . $link_border_type . '" link_border_width="' . $link_border_width . '" link_border_color="' . $link_border_color . '" link_border_hover="' . $link_border_hover . '"';
													$link_attributes .= ' margin_top="0" margin_bottom="0"';
													$output .= do_shortcode('[TS_VCSC_Creative_Link ' . $link_attributes . ']');
												} else {
													$output .= '<a href="' . $a_href . '" target="' . $a_target . '" title="' . $a_title . '" ' . $a_rel . ' style="margin-top: 20px; ' . $google_font_button . '">' . $link_content . '</a>';
												}
												$output .= '<div class="clearFixMe"></div>';
											$output .= '</div>';
										}
									}
								$output .= '</div>';
							$output .= '</div>';
						$output .= '</div>';
						if ($flip_handle_show == "true") {
							$output .= '<div class="ts-image-hover-handle" style="' . $flip_handle_adjust . '"><span class="frame_' . $flip_handle_icon . '" style="background-color: ' . $flip_handle_color . '"><i class="' . $flip_handle_icon . '"></i></span></div>';
						}
					if ($flip_handle_show == "true") {
						$output .= '</div>';
					}
				$output .= '</div>';
			$output .= '</div>';
		}
		
		echo $output;
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>