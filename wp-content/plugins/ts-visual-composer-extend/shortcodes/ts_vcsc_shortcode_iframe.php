<?php
	add_shortcode('TS-VCSC-IFrame', 'TS_VCSC_IFrame_Function');
	function TS_VCSC_IFrame_Function ($atts, $content = null) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();

		extract( shortcode_atts( array(
			'content_image_responsive'		=> 'true',
			'content_image_width_r'			=> 100,
			'content_image_width_f'			=> 300,
			'content_image_size'			=> 'large',

			'iframe_width'					=> 'auto',
			'iframe_width_percent'			=> 100,
			'iframe_width_pixel'			=> 1024,
			'iframe_height'					=> 'auto',
			'iframe_height_pixel'			=> 400,
			'iframe_transparency'			=> 'true',
			'iframe_seamless'				=> 'true',
			'iframe_scrolling'				=> 'true',

			'lightbox_width'				=> 'auto',
			'lightbox_width_percent'		=> 100,
			'lightbox_width_pixel'			=> 1024,
			'lightbox_height'				=> 'auto',
			'lightbox_height_percent'		=> 100,
			'lightbox_height_pixel'			=> 400,
			
			'border_type'					=> '',
			'border_thick'					=> 1,
			'border_color'					=> '#000000',
			
			'height_adjust'					=> 'resize', // resize, watcher
			'height_minimum'				=> 400,
			'height_overflow'				=> 'hidden', // hidden, hidden-x, hidden-y
			'height_interval'				=> 1000,
			'height_offset'					=> 0,
			'height_position'				=> 'false',
			'height_measure'				=> 'offsetHeight,clientHeight,scrollHeight',

			'iframefullwidth'				=> 'false',
			'breakouts'						=> 6,

			'lightbox_group_name'			=> '',
			'lightbox_size'					=> 'full',
			'lightbox_effect'				=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LightboxDefaultAnimation,
			'lightbox_speed'				=> 5000,
			'lightbox_social'				=> 'true',
			'lightbox_backlight'			=> 'auto',
			'lightbox_backlight_color'		=> '#ffffff',

			'content_lightbox'				=> 'false',
			'content_iframe'				=> '',
			'content_iframe_trigger'		=> 'default',
			'content_iframe_title'			=> '',
			'content_iframe_subtitle'		=> '',
			'content_iframe_image'			=> '',
			'content_iframe_image_simple'	=> 'false',
			'content_iframe_icon'			=> '',
			'content_iframe_iconsize'		=> 30,
			'content_iframe_iconcolor' 		=> '#cccccc',
			'content_iframe_button'			=> '',			
			'content_iframe_buttonstyle'	=> 'ts-dual-buttons-color-sun-flower',
			'content_iframe_buttonhover'	=> 'ts-dual-buttons-preview-default ts-dual-buttons-hover-default',
			'content_iframe_buttonsize'		=> 16,			
			'content_iframe_buttontext'		=> 'View iFrame',
			'content_iframe_text'			=> '',
			'content_raw'					=> '',
			'content_shortcode'				=> '',

			'content_tooltip_css'			=> 'true',
			'content_tooltip_content'		=> '',
			'content_tooltip_position'		=> 'ts-simptip-position-top',
			'content_tooltip_style'			=> 'ts-simptip-style-black',			
			'tooltipster_offsetx'			=> 0,
			'tooltipster_offsety'			=> 0,

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
		wp_enqueue_style('ts-visual-composer-extend-front');
		wp_enqueue_script('ts-visual-composer-extend-front');

		// Determine Element ID
		if (!empty($el_id)) {
			$modal_id						= $el_id;
		} else {
			$modal_id						= 'ts-vcsc-iframe-' . mt_rand(999999, 9999999);
		}

		// Check for Front End Editor
		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
			$editor_frontend				= "true";
		} else {
			$editor_frontend				= "false";
		}

		// Tooltip
		$tooltipclasses						= 'ts-has-tooltipster-tooltip';
		$content_tooltip_position			= TS_VCSC_TooltipMigratePosition($content_tooltip_position);
		$content_tooltip_style				= TS_VCSC_TooltipMigrateStyle($content_tooltip_style);	
		if (($content_tooltip_css == "true") && (strlen($content_tooltip_content) != 0)) {
			$Tooltip_Content			= 'data-tooltipster-html="false" data-tooltipster-title="" data-tooltipster-text="' . str_replace('<br/>', ' ', $content_tooltip_content) . '" data-tooltipster-image="" data-tooltipster-position="' . $content_tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="' . $content_tooltip_style . '" data-tooltipster-animation="swing" data-tooltipster-trigger="hover" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . $tooltipster_offsety . '"';
			$Tooltip_Class				= $tooltipclasses;
		} else {
			$Tooltip_Class				= "";
			if (strlen($content_tooltip_content) != 0) {
				$Tooltip_Content		= ' title="' . $content_tooltip_content . '"';
			} else {
				$Tooltip_Content		= "";
			}
		}
		
		if ($lightbox_backlight == "auto") {
			$nacho_color					= '';
		} else if ($lightbox_backlight == "custom") {
			$nacho_color					= 'data-color="' . $lightbox_backlight_color . '"';
		} else if ($lightbox_backlight == "hideit") {
			$nacho_color					= 'data-color="rgba(0, 0, 0, 0)"';
		}

		if ($content_image_responsive == "true") {
			$image_dimensions				= 'width: 100%; height: auto;';
			$parent_dimensions				= 'width: ' . $content_image_width_r . '%; height: 100%;';
		} else {
			$image_dimensions				= 'width: 100%; height: auto;';
			$parent_dimensions				= 'width: ' . $content_image_width_f . 'px; height: 100%;';
		}

		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS-VCSC-IFrame', $atts);
		} else {
			$css_class						= '';
		}

		$output								= '';

		if ($content_lightbox == "true") {
			$lightbox_attributes			= ' ';
			if ($lightbox_width == "auto") {
				$lightbox_attributes		.= '';
			} else if ($lightbox_width == "widthpercent") {
				$lightbox_attributes 		.= 'data-width="' . $lightbox_width_percent . '%" ';
			} else if ($lightbox_width == "widthpixel") {
				$lightbox_attributes 		.= 'data-width="' . $lightbox_width_pixel . '" ';
			}
			if ($lightbox_height == "auto") {
				$lightbox_attributes		.= '';
			} else if ($lightbox_height == "heightpercent") {
				$lightbox_attributes 		.= 'data-height="' . $lightbox_height_percent . '%" ';
			} else if ($lightbox_height == "heightpixel") {
				$lightbox_attributes 		.= 'data-height="' . $lightbox_height_pixel . '" ';
			}
			$lightbox_attributes			.= 'data-seamless="' . $iframe_seamless . '" ';
			$lightbox_attributes			.= 'data-scrolling="' . ($iframe_scrolling == "true" ? "yes" : "no") . '"';
			if ($content_iframe_trigger == "default") {				
				$modal_image = TS_VCSC_GetResourceURL('images/defaults/default_iframe.jpg');
				if ($Tooltip_Content != '') {
					$output .= '<div class="' . $modal_id . '-parent nch-holder ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
						$output .= '<div id="' . $modal_id . '" class="' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-iframe ' . $css_class . '" style="width: 100%; height: 100%;">';
				} else {
						$output .= '<div id="' . $modal_id . '" class="' . $modal_id . '-parent nch-holder ' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-iframe ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
				}
						$output .= '<a href="' . $content_iframe . '" class="nch-lightbox-media no-ajaxy" ' . $lightbox_attributes . ' data-title="' . $content_iframe_title . '" data-type="iframe" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
							$output .= '<img src="' . $modal_image . '" title="" style="display: block; ' . $image_dimensions . '">';
							$output .= '<div class="nchgrid-caption"></div>';
							if (!empty($content_iframe_title)) {
								$output .= '<div class="nchgrid-caption-text">' . $content_iframe_title . '</div>';
							}
						$output .= '</a>';
					$output .= '</div>';
				if ($Tooltip_Content != '') {
					$output .= '</div>';
				}
			}
			if ($content_iframe_trigger == "image") {
				$modal_image = wp_get_attachment_image_src($content_iframe_image, 'large');
				$modal_image = $modal_image[0];
				if ($content_iframe_image_simple == "false") {
					if ($Tooltip_Content != '') {
						$output .= '<div class="' . $modal_id . '-parent nch-holder ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
							$output .= '<div id="' . $modal_id . '" class="' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-iframe ' . $css_class . '" style="width: 100%; height: 100%;">';
					} else {
							$output .= '<div id="' . $modal_id . '" class="' . $modal_id . '-parent nch-holder ' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-iframe ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
					}
							$output .= '<a href="' . $content_iframe . '" class="nch-lightbox-media no-ajaxy" ' . $lightbox_attributes . ' data-title="' . $content_iframe_title . '" data-type="iframe" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
								$output .= '<img src="' . $modal_image . '" title="" style="display: block; ' . $image_dimensions . '">';
								$output .= '<div class="nchgrid-caption"></div>';
								if (!empty($content_iframe_title)) {
									$output .= '<div class="nchgrid-caption-text">' . $content_iframe_title . '</div>';
								}
							$output .= '</a>';
						$output .= '</div>';
					if ($Tooltip_Content != '') {
						$output .= '</div>';
					}
				} else {
					$output .= '<a href="' . $content_iframe . '" class="' . $modal_id . '-parent nch-holder nch-lightbox-media no-ajaxy ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' style="' . $parent_dimensions . '" ' . $lightbox_attributes . ' data-title="' . $content_iframe_title . '" data-type="iframe" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
						$output .= '<img class="" src="' . $modal_image . '" style="display: block; ' . $image_dimensions . '">';
					$output .= '</a>';
				}
			}
			if ($content_iframe_trigger == "icon") {
				$icon_style = 'color: ' . $content_iframe_iconcolor . '; width:' . $content_iframe_iconsize . 'px; height:' . $content_iframe_iconsize . 'px; font-size:' . $content_iframe_iconsize . 'px; line-height:' . $content_iframe_iconsize . 'px;';
				$output .= '<div id="' . $modal_id . '" style="" class="' . $modal_id . '-parent nch-holder ts-vcsc-font-icon ts-font-icons ts-shortcode ts-icon-align-center ' . $el_class . ' ' . $css_class . ' ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<a class="ts-font-icons-link nch-lightbox-media no-ajaxy" href="' . $content_iframe . '" target="_blank" ' . $lightbox_attributes . ' data-title="' . $content_iframe_title . '" data-type="iframe" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
						$output .= '<i class="ts-font-icon ' . $content_iframe_icon . '" style="' . $icon_style . '"></i>';
					$output .= '</a>';
				$output .= '</div>';
			}
			if (($content_iframe_trigger == "flat") || ($content_iframe_trigger == "flaticon")) {
				wp_enqueue_style('ts-extend-buttonsdual');
				$button_style				= $content_iframe_buttonstyle . ' ' . $content_iframe_buttonhover;
				$output .= '<a id="' . $modal_id . '" class="ts-dual-buttons-container nch-lightbox-media no-ajaxy ' . $css_class . ' ' . $el_class . '" href="' . $content_iframe . '" target="_blank" ' . $lightbox_attributes . ' data-title="' . $content_iframe_title . '" data-type="iframe" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
					$output .= '<div id="' . $modal_id . '-trigger" class="ts-dual-buttons-wrapper clearFixMe ' . $button_style . ' ' . $modal_id . '-parent nch-holder ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' style="width: 100%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
						if (($content_iframe_icon != '') && ($content_iframe_icon != 'transparent') && ($content_iframe_trigger == "flaticon")) {
							$output .= '<i class="ts-dual-buttons-icon ' . $content_iframe_icon . '" style="font-size: ' . $content_iframe_buttonsize . 'px; line-height: ' . $content_iframe_buttonsize . 'px;"></i>';
						}
						$output .= '<span class="ts-dual-buttons-title" style="font-size: ' . $content_iframe_buttonsize . 'px; line-height: ' . $content_iframe_buttonsize . 'px;">' . $content_iframe_buttontext . '</span>';			
					$output .= '</div>';
				$output .= '</a>';
			}
			if ($content_iframe_trigger == "winged") {
				$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $el_class . ' ' . $css_class . '" style="display: block; width: 100%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<div class="ts-lightbox-button-1 clearFixMe">';
						$output .= '<div class="top">' . $content_iframe_title . '</div>';
						$output .= '<div class="bottom">' . $content_iframe_subtitle . '</div>';
						$output .= '<a href="' . $content_iframe . '" class="nch-lightbox-media no-ajaxy icon" ' . $lightbox_attributes . ' data-title="' . $content_iframe_title . '" data-type="iframe" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '><span class="iframe">' . $content_iframe_buttontext . '</span></a>';
					$output .= '</div>';
				$output .= '</div>';
			}
			if ($content_iframe_trigger == "simple") {
				$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $el_class . ' ' . $Tooltip_Class . ' ' . $css_class . '" ' . $Tooltip_Content . ' style="display: block; width: 100%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<a href="' . $content_iframe . '" class="ts-lightbox-button-2 icon nch-lightbox-media no-ajaxy" ' . $lightbox_attributes . ' data-title="' . $content_iframe_title . '" data-type="iframe" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '><span class="iframe">' . $content_iframe_buttontext . '</span></a>';
				$output .= '</div>';
			}
			if ($content_iframe_trigger == "text") {
				$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $el_class . ' ' . $css_class . ' ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' style="text-align: center; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<a href="' . $content_iframe . '" class="nch-lightbox-media no-ajaxy" ' . $lightbox_attributes . ' data-title="' . $content_iframe_title . '" data-type="iframe" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $nacho_color . ' target="_blank">' . $content_iframe_text . '</a>';
				$output .= '</div>';
			}
			if ($content_iframe_trigger == "custom") {
				if ($content_raw != "") {
					$content_raw =  rawurldecode(base64_decode(strip_tags($content_raw)));
					$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $el_class . ' ' . $css_class . ' ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' style="text-align: center; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
						$output .= '<a href="' . $content_iframe . '" class="nch-lightbox-media no-ajaxy" ' . $lightbox_attributes . ' data-title="' . $content_iframe_title . '" data-type="iframe" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $nacho_color . 'style="" target="_blank">';
							$output .= $content_raw;
						$output .= '</a>';
					$output .= '</div>';
				}
			}
			if ($content_iframe_trigger == "shortcode") {
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
				$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $el_class . ' ' . $css_class . ' ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' style="text-align: center; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<a href="' . $content_iframe . '" class="nch-lightbox-media no-ajaxy" ' . $lightbox_attributes . ' data-title="' . $content_iframe_title . '" data-type="iframe" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $nacho_color . ' target="_blank">';
						if (count($content_checkup) > 0) {
							$output .= __( "The shortcode used to generate the trigger for this modal popup has been found to include link elements itself and can therefore not be used.", "ts_visual_composer_extend" );
						} else {
							$output .= $content_shortcode;
						}
					$output .= '</a>';
				$output .= '</div>';
			}
		} else {
			$iframe_dimensions 		= '';
			if ($iframe_width == "auto") {
				$iframe_dimensions 		.= '';
				$iframe_width_set		= '100%';
			} else if ($iframe_width == "widthpercent") {
				$iframe_dimensions 		.= 'width: ' . $iframe_width_percent . '%; ';
				$iframe_width_set		= '' . $iframe_width_percent . '%';
			} else if ($iframe_width == "widthpixel") {
				$iframe_dimensions 		.= 'width: ' . $iframe_width_pixel . 'px; ';
				$iframe_width_set		= '' . $iframe_width_pixel . '';
			}
			if ($iframe_height == "auto") {
				$iframe_dimensions 		.= '';
				$iframe_height_set		= 'auto';
				$iframe_height_parent	= '';
			} else if ($iframe_height == "heightpixel") {
				$iframe_dimensions 		.= 'height: ' . $iframe_height_pixel . 'px; ';
				$iframe_height_set		= '' . $iframe_height_pixel . 'px';
				$iframe_height_parent	= 'height: ' . $iframe_height_pixel . 'px; padding: 0;';
			}
			if ($border_type != "") {
				$border_style			= 'border: ' . $border_thick . 'px ' . $border_type . ' ' . $border_color . ';';
			} else {
				$border_style			= '';
			}
			if ($iframe_transparency == "true") {
				$iframe_transparent		= 'transparent';
			} else {
				$iframe_transparent		= '';
			}
			$iframe_autosize			= 'data-height-type="' . $iframe_height . '" data-height-current="" data-height-last="" data-height-scroll="" data-height-measure="' . $height_measure . '" data-height-position="' . $height_position . '" data-height-minimum="' . $height_minimum . '" data-height-overflow="' . $height_overflow . '" data-height-adjust="' . $height_adjust . '" data-height-interval="' . $height_interval . '" data-height-offset="' . $height_offset . '"';
			if ($iframe_height == "content") {
				$iframe_class			= 'ts-iframe-container-auto';
			} else {
				$iframe_class			= 'ts-iframe-container-fixed';
			}
			$output .= '<div id="' . $modal_id . '-parent" class="ts-iframe-container '. $iframe_class . ' ' . ($iframefullwidth == "true" ? "ts-iframe-full-frame" : "") . ' ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' ' . $iframe_autosize . ' data-break-parents="' . $breakouts . '" data-inline="' . $editor_frontend . '" data-border="' . ($border_type != '' ? $border_thick : 0) . '" style="' . $iframe_height_parent . ' ' . $border_style . ' margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
				$output .= '<iframe id="' . $modal_id . '" name="' . $modal_id . '" class="' . $iframe_transparent . ' ' . $el_class . ' ' . $css_class . '" src="' . $content_iframe . '" style="' . $iframe_dimensions . '" width="' . $iframe_width_set . '" height="' . $iframe_height_set . '" frameborder="0" scrolling="' . ($iframe_scrolling == "true" ? "yes" : "no") . '" webkitallowfullscreen mozallowfullscreen allowfullscreen ' . ($iframe_seamless == "true" ? "seamless" : "") . '></iframe>';
			$output .= '</div>';
		}

		echo $output;

		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>