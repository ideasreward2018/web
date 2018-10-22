<?php
	add_shortcode('TS-VCSC-Modal-Popup', 'TS_VCSC_Modal_Function');
	function TS_VCSC_Modal_Function ($atts, $content = null) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();
		
		extract( shortcode_atts( array(
			'content_image_responsive'		=> 'true',
			'content_image_height'			=> 'height: 100%;',
			'content_image_width_r'			=> 100,
			'content_image_width_f'			=> 300,
			'content_image_size'			=> 'large',

			'lightbox_group'				=> 'false',
			'lightbox_group_name'			=> 'nachogroup',
			'lightbox_size'					=> 'full',
			'lightbox_effect'				=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LightboxDefaultAnimation,
			'lightbox_speed'				=> 5000,
			'lightbox_social'				=> 'true',
			'lightbox_backlight_choice'		=> 'predefined',
			'lightbox_backlight_color'		=> '#0084E2',
			'lightbox_backlight_custom'		=> '#000000',
			
			'lightbox_custom_width'			=> 'false',
			'lightbox_width'				=> 960,
			'lightbox_custom_height'		=> 'false',
			'lightbox_height'				=> 540,
			
			'lightbox_width'				=> 'auto',
			'lightbox_width_percent'		=> 100,
			'lightbox_width_pixel'			=> 1024,
			'lightbox_height'				=> 'auto',
			'lightbox_height_percent'		=> 100,
			'lightbox_height_pixel'			=> 400,
			
			'lightbox_custom_padding'		=> 15,
			'lightbox_custom_background'	=> 'none',
			'lightbox_background_image'		=> '',
			'lightbox_background_size'		=> 'cover',
			'lightbox_background_repeat'	=> 'no-repeat',
			'lightbox_background_color'		=> '#ffffff',
			
			'height'						=> 500,
			'width'							=> 300,
			'content_style'					=> '',
			
			'content_provider'				=> 'custom',
			'content_retrieve'				=> '',
			
			'content_wpautop'				=> 'false',
			'content_open'					=> 'false',
			'content_open_hide'				=> 'true',
			'content_open_delay'			=> 0,
			'content_open_offset'			=> '50%',
			
			'content_trigger'				=> 'default',
			'content_title'					=> '',
			'content_subtitle'				=> '',
			'content_image'					=> '',
			'content_image_simple'			=> 'false',
			'content_icon'					=> '',
			'content_iconsize'				=> 30,
			'content_iconcolor' 			=> '#cccccc',
			'content_button'				=> '',
			'content_buttonstyle'			=> 'ts-dual-buttons-color-sun-flower',
			'content_buttonhover'			=> 'ts-dual-buttons-preview-default ts-dual-buttons-hover-default',
			'content_buttontext'			=> 'View Popup',
			'content_buttonsize'			=> 16,
			'content_text'					=> '',
			'content_raw'					=> '',
			'content_shortcode'				=> '',
			
			'content_tooltip_css'			=> 'true',
			'content_tooltip_content'		=> '',
			'content_tooltip_position'		=> 'ts-simptip-position-top',
			'content_tooltip_style'			=> 'ts-simptip-style-black',
			'content_tooltip_animation'		=> 'swing',
			
			'tooltipster_offsetx'			=> 0,
			'tooltipster_offsety'			=> 0,
			
			'content_show_title'			=> 'true',
			'title'							=> '',
			'margin_top'					=> 0,
			'margin_bottom'					=> 0,
			'el_id'							=> '',
			'el_class'						=> '',
			'css'							=> '',
		), $atts ));
		
		// Load Required Files
		wp_enqueue_script('ts-extend-krautlightbox');
		wp_enqueue_style('ts-extend-krautlightbox');
		wp_enqueue_style('ts-extend-tooltipster');
		wp_enqueue_script('ts-extend-tooltipster');	
		wp_enqueue_style('ts-extend-animations');
		if (($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndWaypoints == "true") && ($content_open == "inview")) {
			if (wp_script_is('waypoints', $list = 'registered')) {
				wp_enqueue_script('waypoints');
			} else {
				wp_enqueue_script('ts-extend-waypoints');
			}
		}
		wp_enqueue_style('ts-visual-composer-extend-front');
		wp_enqueue_script('ts-visual-composer-extend-front');
	
		if (!empty($el_id)) {
			$modal_id						= $el_id;
		} else {
			$modal_id						= 'ts-vcsc-modal-' . mt_rand(999999, 9999999);
		}
		
		if (($content_provider == "identifier") && ($content_retrieve != '')) {
			$retrieval_id					= $content_retrieve;
		} else {
			$retrieval_id					= $modal_id;
		}
		
		// Tooltip
		$content_tooltip_position			= TS_VCSC_TooltipMigratePosition($content_tooltip_position);
		$content_tooltip_style				= TS_VCSC_TooltipMigrateStyle($content_tooltip_style);	
		$tooltipclasses						= 'ts-has-tooltipster-tooltip';		
		if (($content_tooltip_css == "true") && (strlen($content_tooltip_content) != 0)) {
			$Tooltip_Content				= 'data-tooltipster-title="" data-tooltipster-text="' . str_replace('<br/>', ' ', $content_tooltip_content) . '" data-tooltipster-image="" data-tooltipster-position="' . $content_tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="' . $content_tooltip_style . '" data-tooltipster-animation="' . $content_tooltip_animation . '" data-tooltipster-trigger="hover" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . $tooltipster_offsety . '"';
			$Tooltip_Class					= $tooltipclasses;
		} else {
			$Tooltip_Class					= "";
			if (strlen($content_tooltip_content) != 0) {
				$Tooltip_Content			= ' title="' . $content_tooltip_content . '"';
			} else {
				$Tooltip_Content			= "";
			}
		}
		
		if ($content_image_responsive == "true") {
			$image_dimensions				= 'width: 100%; height: auto;';
			$parent_dimensions				= 'width: ' . $content_image_width_r . '%; ' . $content_image_height . '';
		} else {
			$image_dimensions				= 'width: 100%; height: auto;';
			$parent_dimensions				= 'width: ' . $content_image_width_f . 'px; ' . $content_image_height . '';
		}
		
		// Auto-Open or In-View Class
		if ($content_open == "true") {
			$modal_openclass				= "nch-lightbox-open";
			if ($content_open_hide == "true") {
				$modal_hideclass			= "nch-lightbox-hide";
				$content_trigger			= "automatic";
			} else {
				$modal_hideclass			= "";
			}
		} else if ($content_open == "inview") {
			$modal_openclass				= "nch-lightbox-inview";
			if ($content_open_hide == "true") {
				$modal_hideclass			= "nch-lightbox-offset";
				$content_trigger			= "automatic";
			} else {
				$modal_hideclass			= "";
			}
		} else {
			$modal_openclass				= "nch-lightbox-modal no-ajaxy";
			$modal_hideclass				= "";
		}
		
		// Backlight Color
		if ($lightbox_backlight_choice == "predefined") {
			$lightbox_backlight_selection	= $lightbox_backlight_color;
		} else {
			$lightbox_backlight_selection	= $lightbox_backlight_custom;
		}

		// Custom Width / Height
		$lightbox_dimensions				= ' ';
		if ($lightbox_width == "auto") {
			$lightbox_dimensions			.= '';
		} else if ($lightbox_width == "widthpercent") {
			$lightbox_dimensions 			.= 'data-width="' . $lightbox_width_percent . '%" ';
		} else if ($lightbox_width == "widthpixel") {
			$lightbox_dimensions 			.= 'data-width="' . $lightbox_width_pixel . '" ';
		}
		if ($lightbox_height == "auto") {
			$lightbox_dimensions			.= '';
		} else if ($lightbox_height == "heightpercent") {
			$lightbox_dimensions 			.= 'data-height="' . $lightbox_height_percent . '%" ';
		} else if ($lightbox_height == "heightpixel") {
			$lightbox_dimensions 			.= 'data-height="' . $lightbox_height_pixel . '" ';
		}
		
		// Background Settings
		if ($lightbox_custom_background == "image") {
			$background_image 				= wp_get_attachment_image_src($lightbox_background_image, 'full');
			$background_image 				= $background_image[0];
			$lightbox_background			= 'background: url(' . $background_image . ') ' . $lightbox_background_repeat . ' 0 0; background-size: ' . $lightbox_background_size . ';';
		} else if ($lightbox_custom_background == "color") {
			$lightbox_background			= 'background: ' . $lightbox_background_color . ';';
		} else {
			$lightbox_background			= '';
		}
		
		$output 							= '';
		$wpautop 							= ($content_wpautop == "true" ? true : false);
		
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS-VCSC-Modal-Popup', $atts);
		} else {
			$css_class						= '';
		}
		
		$output .= '<div id="' . $modal_id . '-container" class="ts-vcsc-modal-container" data-content-provider="' . $content_provider . '" data-content-trigger="' . $content_trigger . '">';
			if ($content_trigger == "default") {
				$modal_image = TS_VCSC_GetResourceURL('images/defaults/default_modal.jpg');
				if ($Tooltip_Content != '') {
					$output .= '<div class="' . $modal_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
						$output .= '<div id="' . $modal_id . '-trigger" class="' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-modal no-ajaxy ' . $css_class . '" style="width: 100%; height: 100%;">';
				} else {
						$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-modal no-ajaxy ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
				}
						$output .= '<a href="#' . $retrieval_id . '" id="' . $modal_id . '-link" class="nch-lightbox-trigger ' . $modal_openclass . '" ' . $lightbox_dimensions . ' data-title="" data-open="' . $content_open . '" data-delay="' . $content_open_delay . '" data-offset="' . $content_open_offset . '" data-type="html" rel="' . ($lightbox_group == "true" ? $lightbox_group : "") . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
							$output .= '<img src="' . $modal_image . '" title="" style="display: block; ' . $image_dimensions . '">';
							$output .= '<div class="nchgrid-caption"></div>';
							if (!empty($content_title)) {
								$output .= '<div class="nchgrid-caption-text">' . $content_title . '</div>';
							}
						$output .= '</a>';
					$output .= '</div>';
				if ($Tooltip_Content != '') {
					$output .= '</div>';
				}
			}
			if ($content_trigger == "image") {
				$modal_image = wp_get_attachment_image_src($content_image, 'large');
				$modal_image = $modal_image[0];
				if ($content_image_simple == "false") {
					if ($Tooltip_Content != '') {
						$output .= '<div class="' . $modal_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
							$output .= '<div id="' . $modal_id . '-trigger" class="' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-modal no-ajaxy ' . $css_class . '" style="width: 100%; height: 100%;">';
					} else {
							$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-modal no-ajaxy ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
					}
							$output .= '<a href="#' . $retrieval_id . '" id="' . $modal_id . '-link" class="nch-lightbox-trigger ' . $modal_openclass . '" ' . $lightbox_dimensions . ' data-title="" data-open="' . $content_open . '" data-delay="' . $content_open_delay . '" data-offset="' . $content_open_offset . '" data-type="html" rel="' . ($lightbox_group == "true" ? $lightbox_group : "") . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
								$output .= '<img src="' . $modal_image . '" title="" style="display: block; ' . $image_dimensions . '">';
								$output .= '<div class="nchgrid-caption"></div>';
								if (!empty($content_title)) {
									$output .= '<div class="nchgrid-caption-text">' . $content_title . '</div>';
								}
							$output .= '</a>';
						$output .= '</div>';
					if ($Tooltip_Content != '') {
						$output .= '</div>';
					}
				} else {
					$output .= '<a href="#' . $retrieval_id . '" id="' . $modal_id . '-link" class="' . $modal_id . '-parent nch-holder nch-lightbox-modal no-ajaxy ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' style="' . $parent_dimensions . '" ' . $lightbox_dimensions . ' data-title="' . $content_title . '" data-type="html" rel="' . ($lightbox_group == "true" ? $lightbox_group : "") . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
						$output .= '<img class="" src="' . $modal_image . '" style="display: block; ' . $image_dimensions . '">';
					$output .= '</a>';
				}
			}
			if ($content_trigger == "icon") {
				$icon_style = 'color: ' . $content_iconcolor . '; width:' . $content_iconsize . 'px; height:' . $content_iconsize . 'px; font-size:' . $content_iconsize . 'px; line-height:' . $content_iconsize . 'px;';
				$output .= '<div id="' . $modal_id . '-trigger" style="" class="' . $modal_id . '-parent nch-holder ts-vcsc-font-icon ts-font-icons ts-shortcode ts-icon-align-center ' . $el_class . ' ' . $css_class . ' ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<a href="#' . $retrieval_id . '" id="' . $modal_id . '-link" class="' . $modal_openclass . '" ' . $lightbox_dimensions . ' data-title="" data-open="' . $content_open . '" data-delay="' . $content_open_delay . '" data-offset="' . $content_open_offset . '" data-type="html" rel="' . ($lightbox_group == "true" ? $lightbox_group : "") . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
						$output .= '<i class="ts-font-icon ' . $content_icon . '" style="' . $icon_style . '"></i>';
					$output .= '</a>';
				$output .= '</div>';
			}
			if (($content_trigger == "flat") || ($content_trigger == "flaticon")) {
				wp_enqueue_style('ts-extend-buttonsdual');
				$button_style				= $content_buttonstyle . ' ' . $content_buttonhover;				
				$output .= '<a href="#' . $retrieval_id . '" target="_blank" id="' . $modal_id . '-link" class="ts-dual-buttons-container ' . $modal_openclass . '" ' . $lightbox_dimensions . ' data-open="' . $content_open . '" data-delay="' . $content_open_delay . '" data-offset="' . $content_open_offset . '" data-type="html" rel="' . ($lightbox_group == "true" ? $lightbox_group : "") . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
					$output .= '<div id="' . $modal_id . '-trigger" class="ts-dual-buttons-wrapper clearFixMe ' . $button_style . ' ' . $modal_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' ' . $Tooltip_Class . ' ' . $css_class . '" ' . $Tooltip_Content . ' style="width: 100%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
						if (($content_icon != '') && ($content_icon != 'transparent') && ($content_trigger == "flaticon")) {
							$output .= '<i class="ts-dual-buttons-icon ' . $content_icon . '" style="font-size: ' . $content_buttonsize . 'px; line-height: ' . $content_buttonsize . 'px;"></i>';
						}
						$output .= '<span class="ts-dual-buttons-title" style="font-size: ' . $content_buttonsize . 'px; line-height: ' . $content_buttonsize . 'px;">' . $content_buttontext . '</span>';			
					$output .= '</div>';
				$output .= '</a>';
			}
			if ($content_trigger == "winged") {
				$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' ' . $Tooltip_Class . ' ' . $css_class . '" ' . $Tooltip_Content . ' style="display: block; width: 100%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<div class="ts-lightbox-button-1 clearFixMe">';
						$output .= '<div class="top">' . $content_title . '</div>';
						$output .= '<div class="bottom">' . $content_subtitle . '</div>';
						$output .= '<a href="#' . $retrieval_id . '" id="' . $modal_id . '-link" class="icon ' . $modal_openclass . '" ' . $lightbox_dimensions . ' data-open="' . $content_open . '" data-delay="' . $content_open_delay . '" data-offset="' . $content_open_offset . '" data-type="html" rel="' . ($lightbox_group == "true" ? $lightbox_group : "") . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '"><span class="popup">' . $content_buttontext . '</span></a>';
					$output .= '</div>';
				$output .= '</div>';
			}
			if ($content_trigger == "simple") {
				$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' ' . $Tooltip_Class . ' ' . $css_class . '" ' . $Tooltip_Content . ' style="display: block; width: 100%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<a href="#' . $retrieval_id . '" id="' . $modal_id . '-link" class="ts-lightbox-button-2 icon ' . $modal_openclass . '" ' . $lightbox_dimensions . ' data-open="' . $content_open . '" data-delay="' . $content_open_delay . '" data-offset="' . $content_open_offset . '" data-type="html" rel="' . ($lightbox_group == "true" ? $lightbox_group : "") . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '"><span class="popup">' . $content_buttontext . '</span></a>';
				$output .= '</div>';
			}
			if ($content_trigger == "text") {
				$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' ' . $css_class . ' ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' style="text-align: center; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<a href="#' . $retrieval_id . '" id="' . $modal_id . '-link" class="' . $modal_openclass . '" ' . $lightbox_dimensions . ' data-open="' . $content_open . '" data-delay="' . $content_open_delay . '" data-offset="' . $content_open_offset . '" data-type="html" rel="' . ($lightbox_group == "true" ? $lightbox_group : "") . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">' . $content_text . '</a>';
				$output .= '</div>';
			}
			if ($content_trigger == "custom") {
				if ($content_raw != "") {
					$content_raw =  rawurldecode(base64_decode(strip_tags($content_raw)));
					$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' ' . $css_class . ' ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' style="text-align: center; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
						$output .= '<a href="#' . $retrieval_id . '" id="' . $modal_id . '-link" class="' . $modal_openclass . '" ' . $lightbox_dimensions . ' data-open="' . $content_open . '" data-delay="' . $content_open_delay . '" data-offset="' . $content_open_offset . '" data-type="html" rel="' . ($lightbox_group == "true" ? $lightbox_group : "") . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
							$output .= $content_raw;
						$output .= '</a>';
					$output .= '</div>';
				}
			}
			if ($content_trigger == "automatic") {
				$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' ' . $css_class . ' ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' style="margin: 0px; padding: 0px; border: none; height: 0; width: 0;">';
					$output .= '<a href="#' . $retrieval_id . '" id="' . $modal_id . '-link" class="' . $modal_openclass . '" ' . $lightbox_dimensions . ' data-open="' . $content_open . '" data-delay="' . $content_open_delay . '" data-offset="' . $content_open_offset . '" data-type="html" rel="' . ($lightbox_group == "true" ? $lightbox_group : "") . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">x</a>';
				$output .= '</div>';
			}
			if ($content_trigger == "shortcode") {
				$content_shortcode			= rawurldecode(base64_decode(strip_tags($content_shortcode)));
				$content_shortcode			= do_shortcode($content_shortcode);
				$content_regexp 			= "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
				$content_checkup			= array();
				if (preg_match_all("/$content_regexp/siU", $content_shortcode, $matches)) {
					// $matches[0] = array of full link tags
					// $matches[2] = array of link addresses
					// $matches[3] = array of link text including HTML code
					$content_checkup		= $matches[0];
				}				
				$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' ' . $css_class . ' ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' style="text-align: center; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<a href="#' . $retrieval_id . '" id="' . $modal_id . '-link" class="' . $modal_openclass . '" ' . $lightbox_dimensions . ' data-open="' . $content_open . '" data-delay="' . $content_open_delay . '" data-offset="' . $content_open_offset . '" data-type="html" rel="' . ($lightbox_group == "true" ? $lightbox_group : "") . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
						if (count($content_checkup) > 0) {
							$output .= __( "The shortcode used to generate the trigger for this modal popup has been found to include link elements itself and can therefore not be used.", "ts_visual_composer_extend" );
						} else {
							$output .= $content_shortcode;
						}
					$output .= '</a>';
				$output .= '</div>';
			}
			
			// Create hidden DIV with Modal Content
			if (($content_provider == "custom") || (($content_provider == "identifier") && ($content_retrieve == ''))) {
				$output .= '<div id="' . $modal_id . '" class="ts-modal-content ts-modal-outside-screen ' . $el_class . '" data-style="padding: ' . $lightbox_custom_padding . 'px; ' . $lightbox_background . '" style="display: block; position: absolute; top: -9999px; left: -9999px; opacity: 0; width: 100%; height: auto; margin: 0; padding: 0;">';
					$output .= '<div class="ts-modal-white-header"></div>';
					$output .= '<div class="ts-modal-white-frame" style="">';
						$output .= '<div class="ts-modal-white-inner">';
							if (($content_show_title == "true") && ($title != "")) {
								$output .= '<h2 style="border-bottom: 1px solid #eeeeee; padding-bottom: 10px; margin-bottom: 10px;">' . $title . '</h2>';
							}
							if (function_exists('wpb_js_remove_wpautop')){
								$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
							} else {
								$output .= do_shortcode($content);
							}
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
			}
		$output .= '</div>';
		
		echo $output;
	
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>