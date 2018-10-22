<?php
	add_shortcode('TS_VCSC_Facebook_Video', 'TS_VCSC_Facebook_Video_Function');
	function TS_VCSC_Facebook_Video_Function ($atts, $content = null) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();
		
		extract( shortcode_atts( array(
			// Facebook Video
			'facebook_video'				=> '',
			'facebook_appid'				=> '',
			'facebook_autoplay'				=> 'false',
			'facebook_ratio'				=> 'ts-ratio-sixteen-to-nine',
			'facebook_showtext'				=> 'false',
			'facebook_showcaptions'			=> 'false',
			'facebook_fullscreen'			=> 'true',	
			// Trigger Settings	
			'content_trigger'				=> 'preview',
			'content_title'					=> '',
			'content_subtitle'				=> '',
			'content_image'					=> '',
			'content_image_simple'			=> 'false',			
			'content_image_responsive'		=> 'true',
			'content_image_height'			=> 'height: 100%;',
			'content_image_width_r'			=> 100,
			'content_image_width_f'			=> 300,
			'content_image_size'			=> 'large',
			'content_icon'					=> '',
			'content_iconsize'				=> 30,
			'content_iconcolor' 			=> '#cccccc',
			'content_button'				=> '',
			'content_buttonstyle'			=> 'ts-dual-buttons-color-sun-flower',
			'content_buttonhover'			=> 'ts-dual-buttons-preview-default ts-dual-buttons-hover-default',
			'content_buttontext'			=> 'View Video',
			'content_buttonsize'			=> 16,
			'content_text'					=> '',
			'content_raw'					=> '',
			// Overlay Settings
			'overlay_visibility'			=> 'hover', // hover, only_deco, only_title, always
			'overlay_animation'				=> 'zoom', // zoom, rotate, none
			'overlay_background'			=> 'rgba(24, 24, 24, 0.3)',
			'overlay_decoration'			=> 'default', // default, icon, image, external, none
			'overlay_image'					=> '',
			'overlay_external'				=> '',
			'overlay_icon_name'				=> '',
			'overlay_icon_color'			=> '#ededed',
			'overlay_size'					=> 100,
			'overlay_opacity'				=> 75,
			'overlay_title_color'			=> '#ffffff',
			'overlay_title_back'			=> 'rgba(0, 0, 0, 0.4)',		
			// Lightbox Settings
			'lightbox_open'					=> 'false',
			'lightbox_group_name'			=> 'nachogroup',
			'lightbox_size'					=> 'full',
			'lightbox_effect'				=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LightboxDefaultAnimation,
			'lightbox_speed'				=> 5000,
			'lightbox_social'				=> 'true',
			'lightbox_play'					=> 'false',
			'lightbox_loop'					=> 'false',
			'lightbox_backlight_choice'		=> 'predefined',
			'lightbox_backlight_color'		=> '#0084E2',
			'lightbox_backlight_custom'		=> '#000000',		
			'lightbox_width'				=> 'auto',
			'lightbox_width_percent'		=> 100,
			'lightbox_width_pixel'			=> 960,
			'lightbox_height'				=> 'auto',
			'lightbox_height_percent'		=> 100,
			'lightbox_height_pixel'			=> 540,
			// Tooltip Settings
			'tooltip_usage'					=> 'false',
			'tooltip_content'				=> '',
			'tooltip_position'				=> 'ts-simptip-position-top',
			'tooltip_style'					=> 'ts-simptip-style-black',
			'tooltip_animation'				=> 'swing',
			'tooltip_arrow'					=> 'true',
			'tooltip_background'			=> '#000000',
			'tooltip_border'				=> '#000000',
			'tooltip_color'					=> '#ffffff',
			'tooltip_offsetx'				=> 0,
			'tooltip_offsety'				=> 0,
			// Other Settings
			'margin_top'					=> 0,
			'margin_bottom'					=> 0,
			'el_id'							=> '',
			'el_class'						=> '',
			'css'							=> '',
		), $atts ));		
		
		// Load Required JS/CSS Files
		if ($lightbox_open == "true") {
			wp_enqueue_script('ts-extend-krautlightbox');
			wp_enqueue_style('ts-extend-krautlightbox');
		}
		wp_enqueue_style('ts-visual-composer-extend-front');
		wp_enqueue_script('ts-visual-composer-extend-front');
		
		// Create Global Variables
		$output 							= '';
		$styles								= '';
		$randomizer							= mt_rand(999999, 9999999);
	
		// Create Player ID
		if (!empty($el_id)) {
			$player_id						= $el_id;
		} else {
			$player_id						= 'ts-vcsc-facebookvideo-' . $randomizer;
		}
		
		// Facebook Settings		
		$facebook_identifier				= TS_VCSC_VideoID_Facebook($facebook_video);
		if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $facebook_video)) {
			$facebook_video					= $facebook_video;
		} else {			
			$facebook_video					= 'https://www.facebook.com/facebook/videos/' . $facebook_video;
		}
		$facebook_video 					= rtrim($facebook_video, "/");
		if ($lightbox_open == "false") {
			if ($facebook_autoplay == "true") {
				$facebook_autoplay			= '&autoplay=true';
			} else {
				$facebook_autoplay			= '&autoplay=false';
			}		
			if ($facebook_showtext == "true") {
				$facebook_text				= '&show_text=true';
			} else {
				$facebook_text				= '&show_text=false';
			}
			if ($facebook_showcaptions == "true") {
				$facebook_captions			= '&show_captions=true';
			} else {
				$facebook_captions			= '&show_captions=false';
			}
			if ($facebook_fullscreen == "true") {
				$facebook_fullscreen		= '&allowfullscreen=true';
			} else {
				$facebook_fullscreen		= '&allowfullscreen=false';
			}
			if ($facebook_appid != "") {
				$facebook_appid				= '&appId=' . $facebook_appid;
			} else {
				$facebook_appid				= '&appId';
			}
		}
		
		// Tooltip Setup
		if (($tooltip_usage == "true") && (strip_tags($tooltip_content) != '') && ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "false")) {
			$tooltip_position				= TS_VCSC_TooltipMigratePosition($tooltip_position);
			$tooltip_style					= TS_VCSC_TooltipMigrateStyle($tooltip_style);
			wp_enqueue_style('ts-extend-tooltipster');
			wp_enqueue_script('ts-extend-tooltipster');	
			$tooltip_content				= 'data-tooltipster-html="true" data-tooltipster-title="" data-tooltipster-text="' . strip_tags($tooltip_content) . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="' . $tooltip_arrow . '" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="' . $tooltip_animation . '" data-tooltipster-trigger="hover" data-tooltipster-background="' . $tooltip_background . '" data-tooltipster-border="' . $tooltip_border . '" data-tooltipster-color="' . $tooltip_color . '" data-tooltipster-offsetx="' . $tooltip_offsetx . '" data-tooltipster-offsety="' . $tooltip_offsety . '"';
			$tooltip_class					= 'ts-has-tooltipster-tooltip';
		} else {
			$tooltip_content				= '';
			$tooltip_class					= '';
		}
		
		// Lightbox Settings
		if ($lightbox_backlight_choice == "predefined") {
			$lightbox_color					= $lightbox_backlight_color;
		} else if ($lightbox_backlight_choice == "hideit") {
			$lightbox_color					= 'rgba(0, 0, 0, 0)';
		} else {
			$lightbox_color					= $lightbox_backlight_custom;
		}		
		$lightbox_dimensions				= 'data-thumbs="bottom"';
		if ($lightbox_width == "auto") {
			$lightbox_dimensions			.= '';	
		} else if ($lightbox_width == "widthpercent") {
			$lightbox_dimensions 			.= ' data-width="' . $lightbox_width_percent . '%" ';
		} else if ($lightbox_width == "widthpixel") {
			$lightbox_dimensions 			.= ' data-width="' . $lightbox_width_pixel . '" ';
		}
		if ($lightbox_height == "auto") {
			$lightbox_dimensions			.= '';	
		} else if ($lightbox_height == "heightpercent") {
			$lightbox_dimensions 			.= ' data-height="' . $lightbox_height_percent . '%" ';
		} else if ($lightbox_height == "heightpixel") {
			$lightbox_dimensions 			.= ' data-height="' . $lightbox_height_pixel . '" ';
		}
		
		// Overlay Settings
		$overlay_styling					= '';
		$overlay_addition					= '';
		$overlay_classes					= '';
		$overlay_visible					= '';
		if (($overlay_decoration == 'image') && ($overlay_image != '')) {
			$overlay_classes				= 'nchgrid-caption-custom';
			$overlay_image					= wp_get_attachment_image_src($overlay_image, 'medium');
			$overlay_addition				= '<img class="nchgrid-caption-image" src="' . $overlay_image[0] . '" style="opacity: ' . ($overlay_opacity/100) . '; width: ' . $overlay_size . 'px;">';
		} else if (($overlay_decoration == 'external') && ($overlay_external != '')) {
			$overlay_classes				= 'nchgrid-caption-custom';
			$overlay_addition				= '<img class="nchgrid-caption-image" src="' . $overlay_external . '" style="opacity: ' . ($overlay_opacity/100) . '; width: ' . $overlay_size . 'px;">';
		} else if (($overlay_decoration == 'icon') && ($overlay_icon_name != '')) {
			$overlay_classes				= 'nchgrid-caption-custom';
			$overlay_addition				= '<i class="nchgrid-caption-icon ' . $overlay_icon_name . '" style="opacity: ' . ($overlay_opacity/100) . '; color: ' . $overlay_icon_color . '; font-size: ' . $overlay_size . 'px; line-height: ' . $overlay_size . 'px;"></i>';
		} else if ($overlay_decoration == 'none') {
			$overlay_styling				= 'background-image: none;';
		}
		if ($overlay_background != "") {
			$overlay_background				= 'background-color: ' . $overlay_background . ';';
		}
		if ($overlay_visibility == 'only_deco') {
			$overlay_visible				= 'nchgrid-lighbox-show-onlydeco';
		} else if ($overlay_visibility == 'only_title') {
			$overlay_visible				= 'nchgrid-lighbox-show-onlytitle';
		} else if ($overlay_visibility == 'always') {
			$overlay_visible				= 'nchgrid-lighbox-show-all';
		}
		
		// Other Settings
		if ($content_image_responsive == "true") {
			$image_dimensions				= 'width: 100%; height: auto;';
			$parent_dimensions				= 'width: ' . $content_image_width_r . '%; ' . $content_image_height . '';
		} else {
			$image_dimensions				= 'width: 100%; height: auto;';
			$parent_dimensions				= 'width: ' . $content_image_width_f . 'px; ' . $content_image_height . '';
		}
		
		// Visual Composer Style Override
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Facebook_Video', $atts);
		} else {
			$css_class						= '';
		}
		
		// Create Final Output
		if ($lightbox_open == "true") {
			if ($content_trigger == "preview") {
				$modal_image 				= TS_VCSC_VideoImage_Facebook($facebook_video);
				if ($tooltip_content != '') {
					$output .= '<div class="' . $player_id . '-parent nch-holder ' . $tooltip_class . '" ' . $tooltip_content . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
						$output .= '<div id="' . $player_id . '" class="' . $css_class . ' ' . $el_class . ' nchgrid-item nch-lightbox-facebook nch-lightbox-single ' . $overlay_visible . ' nch-lightbox-hover-' . $overlay_animation . '" style="width: 100%; height: 100%;">';
				} else {
						$output .= '<div id="' . $player_id . '" class="' . $css_class . ' ' . $player_id . '-parent nch-holder ' . $el_class . ' nchgrid-item nch-lightbox-facebook nch-lightbox-single ' . $overlay_visible . ' nch-lightbox-hover-' . $overlay_animation . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
				}
						$output .= '<a href="' . $facebook_video . '" class="nch-lightbox-media no-ajaxy" target="_blank" data-title="' . $content_title . '" data-videoplay="' . $facebook_autoplay . '" data-appid="' . $facebook_appid . '" data-showtext="' . $facebook_showtext . '" data-showcaptions="' . $facebook_showcaptions . '" data-allowfullscreen="' . $facebook_fullscreen . '" data-type="facebook" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $lightbox_color . '>';
							$output .= '<img class="nchgrid-image-' . $overlay_animation . '" src="' . $modal_image . '" title="" data-no-lazy="1" style="display: block; ' . $image_dimensions . '">';
							$output .= '<div class="nchgrid-caption ' . $overlay_classes . '" style="' . $overlay_background . ' ' . $overlay_styling . '">' . $overlay_addition . '</div>';
							if (!empty($content_title)) {
								$output .= '<div class="nchgrid-caption-text" style="background: ' . $overlay_title_back . '; color: ' . $overlay_title_color . ';">' . $content_title . '</div>';
							}
						$output .= '</a>';
					$output .= '</div>';
				if ($tooltip_content != '') {
					$output .= '</div>';
				}
			}
			if ($content_trigger == "default") {
				$modal_image = TS_VCSC_GetResourceURL('images/defaults/default_facebook.jpg');
				if ($tooltip_content != '') {
					$output .= '<div class="' . $player_id . '-parent nch-holder ' . $tooltip_class . '" ' . $tooltip_content . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
						$output .= '<div id="' . $player_id . '" class="' . $css_class . ' ' . $el_class . ' nchgrid-item nch-lightbox-facebook nch-lightbox-facebook nch-lightbox-single ' . $overlay_visible . ' nch-lightbox-hover-' . $overlay_animation . '" style="width: 100%; height: 100%;">';
				} else {
						$output .= '<div id="' . $player_id . '" class="' . $css_class . ' ' . $player_id . '-parent nch-holder ' . $el_class . ' nchgrid-item nch-lightbox-facebook nch-lightbox-single ' . $overlay_visible . ' nch-lightbox-hover-' . $overlay_animation . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
				}
						$output .= '<a href="' . $facebook_video . '" class="nch-lightbox-media no-ajaxy" target="_blank" data-title="' . $content_title . '" data-videoplay="' . $facebook_autoplay . '" data-appid="' . $facebook_appid . '" data-showtext="' . $facebook_showtext . '" data-showcaptions="' . $facebook_showcaptions . '" data-allowfullscreen="' . $facebook_fullscreen . '" data-type="facebook" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $lightbox_color . '>';
							$output .= '<img class="nchgrid-image-' . $overlay_animation . '" src="' . $modal_image . '" title="" data-no-lazy="1" style="display: block; ' . $image_dimensions . '">';
							$output .= '<div class="nchgrid-caption ' . $overlay_classes . '" style="' . $overlay_background . ' ' . $overlay_styling . '">' . $overlay_addition . '</div>';
							if (!empty($content_title)) {
								$output .= '<div class="nchgrid-caption-text" style="background: ' . $overlay_title_back . '; color: ' . $overlay_title_color . ';">' . $content_title . '</div>';
							}
						$output .= '</a>';
					$output .= '</div>';
				if ($tooltip_content != '') {
					$output .= '</div>';
				}
			}
			if ($content_trigger == "image") {
				$modal_image = wp_get_attachment_image_src($content_image, 'large');
				$modal_image = $modal_image[0];
				if ($content_image_simple == "false") {
					if ($tooltip_content != '') {
						$output .= '<div class="' . $player_id . '-parent nch-holder ' . $tooltip_class . '" ' . $tooltip_content . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
							$output .= '<div id="' . $player_id . '" class="' . $css_class . ' ' . $el_class . ' nchgrid-item nch-lightbox-facebook nch-lightbox-facebook nch-lightbox-single ' . $overlay_visible . ' nch-lightbox-hover-' . $overlay_animation . '" style="width: 100%; height: 100%;">';
					} else {
							$output .= '<div id="' . $player_id . '" class="' . $css_class . ' ' . $player_id . '-parent nch-holder ' . $el_class . ' nchgrid-item nch-lightbox-facebook nch-lightbox-single ' . $overlay_visible . ' nch-lightbox-hover-' . $overlay_animation . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
					}
							$output .= '<a href="' . $facebook_video . '" class="nch-lightbox-media no-ajaxy" target="_blank" data-title="' . $content_title . '" data-videoplay="' . $facebook_autoplay . '" data-appid="' . $facebook_appid . '" data-showtext="' . $facebook_showtext . '" data-showcaptions="' . $facebook_showcaptions . '" data-allowfullscreen="' . $facebook_fullscreen . '" data-type="facebook" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $lightbox_color . '>';
								$output .= '<img class="nchgrid-image-' . $overlay_animation . '" src="' . $modal_image . '" title="" data-no-lazy="1" style="display: block; ' . $image_dimensions . '">';
								$output .= '<div class="nchgrid-caption ' . $overlay_classes . '" style="' . $overlay_background . ' ' . $overlay_styling . '">' . $overlay_addition . '</div>';
								if (!empty($content_title)) {
									$output .= '<div class="nchgrid-caption-text" style="background: ' . $overlay_title_back . '; color: ' . $overlay_title_color . ';">' . $content_title . '</div>';
								}
							$output .= '</a>';
						$output .= '</div>';
					if ($tooltip_content != '') {
						$output .= '</div>';
					}
				} else {
					$output .= '<a href="' . $facebook_video . '" class="' . $css_class . ' ' . $player_id . '-parent nch-holder nch-lightbox-media no-ajaxy ' . $tooltip_class . '" ' . $tooltip_content . ' target="_blank" style="' . $parent_dimensions . '" data-title="' . $content_title . '" data-videoplay="' . $facebook_autoplay . '" data-appid="' . $facebook_appid . '" data-showtext="' . $facebook_showtext . '" data-showcaptions="' . $facebook_showcaptions . '" data-allowfullscreen="' . $facebook_fullscreen . '" data-type="facebook" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $lightbox_color . '>';
						$output .= '<img class="" src="' . $modal_image . '" style="display: block; ' . $image_dimensions . '">';
					$output .= '</a>';
				}
			}
			if ($content_trigger == "icon") {
				$icon_style = 'color: ' . $content_iconcolor . '; width:' . $content_iconsize . 'px; height:' . $content_iconsize . 'px; font-size:' . $content_iconsize . 'px; line-height:' . $content_iconsize . 'px;';
				$output .= '<div id="' . $player_id . '" style="" class="' . $css_class . ' ' . $player_id . '-parent nch-holder ts-vcsc-font-icon ts-font-icons ts-shortcode ts-icon-align-center ' . $el_class . ' ' . $tooltip_class . '" ' . $tooltip_content . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<a class="ts-font-icons-link nch-lightbox-media no-ajaxy" href="' . $facebook_video . '" target="_blank" data-title="' . $content_title . '" data-videoplay="' . $facebook_autoplay . '" data-appid="' . $facebook_appid . '" data-showtext="' . $facebook_showtext . '" data-showcaptions="' . $facebook_showcaptions . '" data-allowfullscreen="' . $facebook_fullscreen . '" data-type="facebook" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $lightbox_color . '>';
						$output .= '<i class="ts-font-icon ' . $content_icon . '" style="' . $icon_style . '"></i>';
					$output .= '</a>';
				$output .= '</div>';
			}
			if (($content_trigger == "flat") || ($content_trigger == "flaticon")) {
				wp_enqueue_style('ts-extend-buttonsdual');
				$button_style				= $content_buttonstyle . ' ' . $content_buttonhover;
				$output .= '<a id="' . $player_id . '" class="ts-dual-buttons-container nch-lightbox-media no-ajaxy ' . $css_class . ' ' . $el_class . '" href="' . $facebook_video . '" target="_blank" data-title="' . $content_title . '" data-videoplay="' . $facebook_autoplay . '" data-appid="' . $facebook_appid . '" data-showtext="' . $facebook_showtext . '" data-showcaptions="' . $facebook_showcaptions . '" data-allowfullscreen="' . $facebook_fullscreen . '" data-type="facebook" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $lightbox_color . '>';
					$output .= '<div id="' . $player_id . '-trigger" class="ts-dual-buttons-wrapper clearFixMe ' . $button_style . ' ' . $player_id . '-parent nch-holder ' . $tooltip_class . '" ' . $tooltip_content . ' style="width: 100%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
						if (($content_icon != '') && ($content_icon != 'transparent') && ($content_trigger == "flaticon")) {
							$output .= '<i class="ts-dual-buttons-icon ' . $content_icon . '" style="font-size: ' . $content_buttonsize . 'px; line-height: ' . $content_buttonsize . 'px;"></i>';
						}
						$output .= '<span class="ts-dual-buttons-title" style="font-size: ' . $content_buttonsize . 'px; line-height: ' . $content_buttonsize . 'px;">' . $content_buttontext . '</span>';			
					$output .= '</div>';
				$output .= '</a>';
			}
			if ($content_trigger == "winged") {
				$output .= '<div id="' . $player_id . '-trigger" class="' . $css_class . ' ' . $player_id . '-parent nch-holder ' . $el_class . '" style="display: block; width: 100%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<div class="ts-lightbox-button-1 clearFixMe">';
						$output .= '<div class="top">' . $content_title . '</div>';
						$output .= '<div class="bottom">' . $content_subtitle . '</div>';
						$output .= '<a href="' . $facebook_video . '" class="nch-lightbox-media no-ajaxy icon" target="_blank" data-title="' . $content_title . '" data-videoplay="' . $facebook_autoplay . '" data-appid="' . $facebook_appid . '" data-showtext="' . $facebook_showtext . '" data-showcaptions="' . $facebook_showcaptions . '" data-allowfullscreen="' . $facebook_fullscreen . '" data-type="facebook" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $lightbox_color . '><span class="facebookvideo">' . $content_buttontext . '</span></a>';
					$output .= '</div>';
				$output .= '</div>';
			}
			if ($content_trigger == "simple") {
				$output .= '<div id="' . $player_id . '-trigger" class="' . $css_class . ' ' . $player_id . '-parent nch-holder' . $el_class . ' ' . $tooltip_class . '" ' . $tooltip_content . ' style="display: block; width: 100%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<a href="' . $facebook_video . '" class="ts-lightbox-button-2 icon nch-lightbox-media no-ajaxy" target="_blank" data-title="' . $content_title . '" data-videoplay="' . $facebook_autoplay . '" data-appid="' . $facebook_appid . '" data-showtext="' . $facebook_showtext . '" data-showcaptions="' . $facebook_showcaptions . '" data-allowfullscreen="' . $facebook_fullscreen . '" data-type="facebook" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $lightbox_color . '><span class="facebookvideo">' . $content_buttontext . '</span></a>';
				$output .= '</div>';
			}
			if ($content_trigger == "text") {
				$output .= '<div id="' . $player_id . '-trigger" class="' . $css_class . ' ' . $player_id . '-parent nch-holder" style="text-align: center; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<a href="' . $facebook_video . '" class="nch-lightbox-media no-ajaxy ' . $tooltip_class . '" ' . $tooltip_content . ' data-title="' . $content_title . '" data-videoplay="' . $facebook_autoplay . '" data-appid="' . $facebook_appid . '" data-showtext="' . $facebook_showtext . '" data-showcaptions="' . $facebook_showcaptions . '" data-allowfullscreen="' . $facebook_fullscreen . '" data-type="facebook" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $lightbox_color . ' target="_blank">' . $content_text . '</a>';
				$output .= '</div>';
			}
			if ($content_trigger == "custom") {
				if ($content_raw != "") {
					$content_raw =  rawurldecode(base64_decode(strip_tags($content_raw)));
					$output .= '<div id="' . $player_id . '-trigger" class="' . $css_class . ' ' . $player_id . '-parent nch-holder" style="text-align: center; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
						$output .= '<a href="' . $facebook_video . '" class="nch-lightbox-media no-ajaxy ' . $tooltip_class . '" ' . $tooltip_content . ' data-title="' . $content_title . '" data-videoplay="' . $facebook_autoplay . '" data-appid="' . $facebook_appid . '" data-showtext="' . $facebook_showtext . '" data-showcaptions="' . $facebook_showcaptions . '" data-allowfullscreen="' . $facebook_fullscreen . '" data-type="facebook" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $lightbox_color . 'style="" target="_blank">';
							$output .= $content_raw;
						$output .= '</a>';
					$output .= '</div>';
				}
			}
		} else {
			$modal_image = TS_VCSC_VideoID_Motion($facebook_video);
			$output .= '<div id="' . $player_id . '" class="ts-video-container ' . $facebook_ratio . ' ' . $tooltip_class . '" ' . $tooltip_content . '>';
				$output .= '<iframe class="ts-video-iframe" src="https://www.facebook.com/plugins/video.php?href=' . $facebook_video . $facebook_autoplay . $facebook_text . $facebook_captions . $facebook_fullscreen . $facebook_appid . '&wmode=opaque" width="100%" height="auto" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
			$output .= '</div>';
		}

		echo $output;
	
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>