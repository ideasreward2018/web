<?php
	add_shortcode('TS-VCSC-Image-Overlay', 'TS_VCSC_Image_Overlay_Function');
	function TS_VCSC_Image_Overlay_Function ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();

		extract( shortcode_atts( array(
			'image'						=> '',
			'attribute_alt'				=> 'false',
			'attribute_alt_value'		=> '',
			'title'						=> '',
			'message_code'				=> 'false',
			'message'					=> '',
			'message_html'				=> '',
			'message_truncate'			=> 'false',
			'image_fixed'				=> 'false',
			'image_responsive'			=> 100,			
			'image_width'				=> 300,
			'image_height'				=> 0,
			'image_position'			=> 'ts-imagefloat-center',
			'hover_type'           		=> 'ts-imagehover-style1',
			'hover_active'				=> 'false',			
			'frame_type'				=> '',
			'frame_thick'				=> 1,
			'frame_color'				=> '#000000',			
			'button_style'				=> 'true',
			'button_text'				=> 'Read More',
			'button_url'				=> '',
			'button_trigger'			=> 'default',
			'button_image'				=> '',
			'button_icon'				=> '',
			'button_target'				=> '_parent',
			'margin_top'				=> 0,
			'margin_bottom'				=> 0,
			'overlay_trigger'			=> 'ts-trigger-hover',
			'overlay_handle_show'		=> 'true',
			'overlay_handle_color'		=> '#0094FF',			
			'font_titlefamily'			=> 'Default:regular',
			'font_titletype'			=> 'default',
			'font_textfamily'			=> 'Default:regular',
			'font_texttype'				=> 'default',
			'font_buttonfamily'			=> 'Default:regular',
			'font_buttontype'			=> 'default',			
			'custom_styling'			=> 'false',
			'custom_overlay'			=> 'rgba(0, 0, 0, 0.5)',
			'custom_titlecolor'			=> '#ffffff',
			'custom_titleback'			=> '#000000',
			'custom_titleshadow'		=> 'rgba(175, 175, 175, 0.5)',
			'custom_textcolor'			=> '#ffffff',
			'custom_buttoncolor'		=> '#ffffff',
			'custom_buttonback'			=> '#000000',
			'custom_buttonshadow'		=> '#000000',
			'tooltip_html'				=> 'false',
			'tooltip_content'			=> '',
			'tooltip_content_html'		=> '',
			'tooltip_position'			=> 'ts-simptip-position-top',
			'tooltip_style'				=> 'ts-simptip-style-black',
			'tooltip_animation'			=> 'swing',
			'tooltipster_offsetx'		=> 0,
			'tooltipster_offsety'		=> 0,
			'el_id' 					=> '',
			'el_class'                  => '',
			'css'						=> '',
		), $atts ));
	
		$hover_image 					= wp_get_attachment_image_src($image, 'large');
		
		// Image Check
		if ((!isset($hover_image[0])) || (empty($hover_image[0]))) {
			echo __( "Unfortunately, The assigned image could not be retrieved from the WordPress media library.", "ts_visual_composer_extend" );
			$myvariable 				= ob_get_clean();
			return $myvariable;
		}
		
		// Load Require Files
		wp_enqueue_style('ts-extend-tooltipster');
		wp_enqueue_script('ts-extend-tooltipster');
		wp_enqueue_style('ts-extend-animations');
		if ($message_truncate == "true") {
			wp_enqueue_script('ts-extend-badonkatrunc');
		}
		wp_enqueue_style('ts-visual-composer-extend-front');		
		wp_enqueue_script('ts-visual-composer-extend-front');
		
		$hover_margin 					= 'margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;';
		
		$output 						= "";
		
		if (!empty($el_id)) {
			$hover_image_id				= $el_id;
		} else {
			$hover_image_id				= 'ts-vcsc-image-hover-' . mt_rand(999999, 9999999);
		}
		
		// Check for Front End Editor
		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
			$hover_frontend				= "true";
			$message_truncate			= "false";
		} else {
			$hover_frontend				= "false";
			$message_truncate			= $message_truncate;
		}
		
		// Border Settings
		if ($frame_type != '') {
			$overlay_border				= 'border: ' . $frame_thick . 'px ' . $frame_type . ' ' . $frame_color;
		} else {
			$overlay_border				= '';
		}
	
		// Handle Padding
		if ($overlay_handle_show == "true") {
			$overlay_padding			= "padding-bottom: 25px;";
			$switch_handle_adjust  		= "";
		} else {
			$overlay_padding			= "";
			$switch_handle_adjust  		= "";
		}
		
		// Handle Icon
		if ($overlay_trigger == "ts-trigger-click") {
			$switch_handle_icon			= 'handle_click';
		} else if ($overlay_trigger == "ts-trigger-hover") {
			$switch_handle_icon			= 'handle_hover';
		}
	
		// Tooltip
		$tooltip_position				= TS_VCSC_TooltipMigratePosition($tooltip_position);
		$tooltip_style					= TS_VCSC_TooltipMigrateStyle($tooltip_style);	
		if ($tooltip_html == "true") {		
			if (strlen($tooltip_content_html) != 0) {
				$hover_tooltipclasses	= "ts-has-tooltipster-tooltip";
				$hover_tooltipcontent	= 'data-tooltipster-html="true" data-tooltipster-title="" data-tooltipster-text="' . $tooltip_content_html . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="' . $tooltip_animation . '" data-tooltipster-trigger="hover" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . $tooltipster_offsety . '"';
			} else {
				$hover_tooltipclasses	= "";
				$hover_tooltipcontent	= "";
			}
		} else {
			if (strlen($tooltip_content) != 0) {
				$hover_tooltipcontent	= 'data-tooltipster-html="false" data-tooltipster-title="" data-tooltipster-text="' . $tooltip_content . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="' . $tooltip_animation . '" data-tooltipster-trigger="hover" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . $tooltipster_offsety . '"';
				$hover_tooltipclasses	= "ts-has-tooltipster-tooltip";
			} else {
				$hover_tooltipcontent	= '';
				$hover_tooltipclasses	= '';
			}
		}
		
		$image_extension 				= pathinfo($hover_image[0], PATHINFO_EXTENSION);
		
		// Custom Fonts
		if (strpos($font_titlefamily, 'Default') === false) {
			$font_title 				= TS_VCSC_GetFontFamily($hover_image_id, $font_titlefamily, $font_titletype, false, true, false);
		} else {
			$font_title					= '';
		}
		if (strpos($font_textfamily, 'Default') === false) {
			$font_text 					= TS_VCSC_GetFontFamily($hover_image_id, $font_textfamily, $font_texttype, false, true, false);
		} else {
			$font_text					= '';
		}
		if (strpos($font_buttonfamily, 'Default') === false) {
			$font_button 				= TS_VCSC_GetFontFamily($hover_image_id, $font_buttonfamily, $font_buttontype, false, true, false);
		} else {
			$font_button				= '';
		}
		
		// Custom Styling
		if ($custom_styling == "true") {
			$custom_overlay				= 'background: ' . $custom_overlay . ';';
			$custom_titlecolor			= 'color: ' . $custom_titlecolor . ';';
			$custom_titleback			= 'background: ' . $custom_titleback . ';';
			if ($custom_titleshadow != "") {
				$custom_titleshadow		= '-webkit-box-shadow: 0 1px 2px ' . $custom_titleshadow . '; -moz-box-shadow: 0 1px 2px ' . $custom_titleshadow . '; box-shadow: 0 1px 2px ' . $custom_titleshadow . ';';
			} else {
				$custom_titleshadow		= '-webkit-box-shadow: none; -moz-box-shadow: none; box-shadow: none;';
			}
			$custom_textcolor			= 'color: ' . $custom_textcolor . ';';
			$custom_buttoncolor			= 'color: ' . $custom_buttoncolor . ';';
			$custom_buttonback			= 'background: ' . $custom_buttonback . ';';
			if ($custom_buttonshadow != "") {
				$custom_buttonshadow	= '-webkit-box-shadow: 0 0 2px ' . $custom_buttonshadow . '; -moz-box-shadow: 0 0 2px ' . $custom_buttonshadow . '; box-shadow: 0 0 2px ' . $custom_buttonshadow . ';';
			} else {
				$custom_buttonshadow	= '-webkit-box-shadow: none; -moz-box-shadow: none; box-shadow: none;';
			}
		} else {
			$custom_overlay				= '';
			$custom_titlecolor			= '';
			$custom_titleback			= '';
			$custom_titleshadow			= '';
			$custom_textcolor			= '';
			$custom_buttoncolor			= '';
			$custom_buttonback			= '';
			$custom_buttonshadow		= '';
		}

		// Other Settings		
		if ($attribute_alt == "true") {
			$alt_attribute				= $attribute_alt_value;
		} else {
			$alt_attribute				= basename($hover_image[0], "." . $image_extension);
		}
		
		if ($message_truncate == "true") {
			$truncate_class				= "ts-imagehover-truncate";
		} else {
			$truncate_class				= "ts-imagehover-static";
		}
		
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 					= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-image-hover-frame ' . $image_position . ' ' . $hover_tooltipclasses . ' ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS-VCSC-Image-Overlay', $atts);
		} else {
			$css_class					= 'ts-image-hover-frame ' . $image_position . ' ' . $hover_tooltipclasses . ' ' . $el_class;
		}
		
		if ($image_fixed == "true") {
			$output .= '<div id="' . $hover_image_id . '" class="' . $css_class . ' ts-trigger-hover-adjust" ' . $hover_tooltipcontent . ' style="width: ' . $image_width . 'px; ' . $hover_margin . '">';
				$output .= '<div id="' . $hover_image_id . '-counter" class="ts-fluid-wrapper " style="width: ' . $image_width . 'px; height: auto;">';
					if ($overlay_handle_show == "true") {
						$output .= '<div style="' . $overlay_padding . '">';
					}
						$output .= '<div id="' . $hover_image_id . '-mask" class="ts-imagehover ' . $truncate_class . ' ' . $hover_type . ' ' . $overlay_trigger . ' ' . ((($hover_active == "true") && ($overlay_trigger == "ts-trigger-click")) ? "active" : "") . '" data-trigger="' . $overlay_trigger . '" data-closer="' . $hover_image_id . '-closer" style="width: ' . $image_width . 'px; height: ' . ($image_height == "0" ? '100%;' : $image_height . 'px;') . '; ' . $overlay_border . '">';
							if ($overlay_trigger == "ts-trigger-click") {
								$output .= '<div id="' . $hover_image_id . '-closer" class="ts-imagecloser" data-mask="' . $hover_image_id . '-mask"></div>';
							}
							$output .= '<img src="' . $hover_image[0] . '" data-no-lazy="1" alt="' . $alt_attribute . '" style="width: ' . $image_width . 'px; height: ' . ($image_height == "0" ? 'auto;' : $image_height . 'px;') . '"/>';
							$output .= '<div class="mask" style="' . $custom_overlay . ' width: ' . $image_width . 'px; height: ' . ($image_height == "0" ? '100%;' : $image_height . 'px;') . ' display: ' . ((($hover_active == "false") && ($overlay_trigger == "ts-trigger-click")) ? "none;" : "block;") . '">';
								$output .= '<h2 style="' . $font_title . ' ' . $custom_titlecolor . ' ' . $custom_titleback . ' ' . $custom_titleshadow . '">' . $title . '</h2>';
								if ($message_code == "false") {
									$output .= '<div id="' . $hover_image_id . '-maskcontent" class="maskcontent" style="' . $font_text . ' ' . $custom_textcolor . '">' . strip_tags($message) . '</div>';
								} else {
									$output .= '<div id="' . $hover_image_id . '-maskcontent" class="maskcontent" style="' . $font_text . ' ' . $custom_textcolor . '">' . rawurldecode(base64_decode(strip_tags($message_html))) . '</div>';
								}
								if (strlen($button_url) != 0) {
									if ($button_style == "true") {
										$output .= '<a id="' . $hover_image_id . '-readmore" style="' . $font_button . ' ' . $custom_buttoncolor . ' ' . $custom_buttonback . ' ' . $custom_buttonshadow . '" href="' . $button_url . '" class="info ts-image-hover-button" target="' . $button_target . '">' . $button_text . '</a>';
									} else {
										$output .= '<a id="' . $hover_image_id . '-readmore" style="" class="ts-imagereadmore" data-mask="' . $hover_image_id . '-mask" href="' . $button_url . '" target="' . $button_target . '"></a>';
									}
								}
							$output .= '</div>';
						$output .= '</div>';
						if ($overlay_handle_show == "true") {
							$output .= '<div class="ts-image-hover-handle" style="' . $switch_handle_adjust . '"><span class="frame_' . $switch_handle_icon . '" style="background-color: ' . $overlay_handle_color . '"><i class="' . $switch_handle_icon . '"></i></span></div>';
						}
					if ($overlay_handle_show == "true") {
						$output .= '</div>';
					}
				$output .= '</div>';
			$output .= '</div>';
		} else {
			$output .= '<div id="' . $hover_image_id . '" class="' . $css_class . ' ts-trigger-hover-adjust" ' . $hover_tooltipcontent . ' style="width: ' . $image_responsive . '%; ' . $hover_margin . '">';
				$output .= '<div id="' . $hover_image_id . '-counter" class="ts-fluid-wrapper " style="width: 100%; height: auto;">';
					if ($overlay_handle_show == "true") {
						$output .= '<div style="' . $overlay_padding . '">';
					}
						$output .= '<div id="' . $hover_image_id . '-mask" class="ts-imagehover ' . $truncate_class . ' ' . $hover_type . ' ' . $overlay_trigger . ' ' . ((($hover_active == "true") && ($overlay_trigger == "ts-trigger-click")) ? "active" : "") . '" data-trigger="' . $overlay_trigger . '" data-closer="' . $hover_image_id . '-closer" style="width: 100%; height: auto; ' . $overlay_border . '">';
							if ($overlay_trigger == "ts-trigger-click") {
								$output .= '<div id="' . $hover_image_id . '-closer" class="ts-imagecloser" data-mask="' . $hover_image_id . '-mask"></div>';
							}
							$output .= '<img src="' . $hover_image[0] . '" data-no-lazy="1" alt="' . $alt_attribute . '" style="width: 100%; height: auto;"/>';
							$output .= '<div class="mask" style="' . $custom_overlay . ' width: 100%; height: 100%; display: ' . ((($hover_active == "false") && ($overlay_trigger == "ts-trigger-click")) ? "none;" : "block;") . '">';
								$output .= '<h2 class="ts-image-hover-title" style="' . $font_title . ' ' . $custom_titlecolor . ' ' . $custom_titleback . ' ' . $custom_titleshadow . '">' . $title . '</h2>';
								if ($message_code == "false") {
									$output .= '<div id="' . $hover_image_id . '-maskcontent" class="maskcontent" style="' . $font_text . ' ' . $custom_textcolor . '">' . strip_tags($message) . '</div>';
								} else {
									$output .= '<div id="' . $hover_image_id . '-maskcontent" class="maskcontent" style="' . $font_text . '' . $custom_textcolor . '">' . rawurldecode(base64_decode(strip_tags($message_html))) . '</div>';
								}
								if (strlen($button_url) != 0) {
									if ($button_style == "true") {
										$output .= '<a id="' . $hover_image_id . '-readmore" style="' . $font_button . ' ' . $custom_buttoncolor . ' ' . $custom_buttonback . ' ' . $custom_buttonshadow . '" href="' . $button_url . '" class="info ts-image-hover-button" target="' . $button_target . '">' . $button_text . '</a>';
									} else {
										$output .= '<a id="' . $hover_image_id . '-readmore" style="" class="ts-imagereadmore" data-mask="' . $hover_image_id . '-mask" href="' . $button_url . '" target="' . $button_target . '"></a>';
									}
								}
							$output .= '</div>';
						$output .= '</div>';
						if ($overlay_handle_show == "true") {
							$output .= '<div class="ts-image-hover-handle" style="' . $switch_handle_adjust . '"><span class="frame_' . $switch_handle_icon . '" style="background-color: ' . $overlay_handle_color . '"><i class="' . $switch_handle_icon . '"></i></span></div>';
						}
					if ($overlay_handle_show == "true") {
						$output .= '</div>';
					}
				$output .= '</div>';
			$output .= '</div>';
		}
		
		echo $output;
		
		$myvariable 					= ob_get_clean();
		return $myvariable;
	}
?>