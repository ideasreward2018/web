<?php
	add_shortcode('TS-VCSC-Image-Picstrips', 'TS_VCSC_Image_Picstrips_Function');
	function TS_VCSC_Image_Picstrips_Function ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();
		
		// Check for Front End Editor
		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
			$hover_frontent					= "true";
		} else {
			$hover_frontent					= "false";
		}

		// Load Required Files
		wp_enqueue_script('ts-extend-krautlightbox');
		wp_enqueue_style('ts-extend-krautlightbox');
		wp_enqueue_style('ts-extend-tooltipster');
		wp_enqueue_script('ts-extend-tooltipster');	
		wp_enqueue_style('ts-extend-animations');
		wp_enqueue_style('ts-visual-composer-extend-front');
		wp_enqueue_script('ts-visual-composer-extend-front');
		wp_enqueue_style('ts-extend-imageeffects');
		wp_enqueue_script('ts-extend-imageeffects');
		
		extract( shortcode_atts( array(			
			'image'							=> '',
			'dynamic_source'				=> 'false',
			'dynamic_image'					=> '',
			'attribute_alt'					=> 'false',
			'attribute_alt_value'			=> '',
			'image_fixed'					=> 'true',
			"image_width_percent"			=> 100,
			'image_width'					=> 300,
			'image_height'					=> 200,
			'image_position'				=> 'ts-imagefloat-center',
			'splits_number'					=> 8,
			'splits_space'					=> 5,
			'splits_offset'					=> 10,
			'splits_background'				=> '#ffffff',
			
			'hover_event'					=> 'none',
			'hover_show_title'				=> 'true',
			'hover_link'					=> '',
			'hover_text'					=> '',
			'hover_title'					=> '',
			'hover_image'					=> '',
			'hover_link'					=> '',				
			'hover_video_link'				=> '',
			'hover_video_auto'				=> 'true',
			'hover_video_related'			=> 'false',				
			
			'overlay_trigger'				=> 'ts-trigger-hover',
			'overlay_handle_show'			=> 'true',
			'overlay_handle_color'			=> '#0094FF',
			
			'lightbox_group'				=> 'true',
			'lightbox_group_name'			=> '',
			'lightbox_size'					=> 'full',
			'lightbox_effect'				=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LightboxDefaultAnimation,
			'lightbox_speed'				=> 5000,
			'lightbox_social'				=> 'false',
			'lightbox_backlight'			=> 'auto',
			'lightbox_backlight_color'		=> '#ffffff',
			
			'tooltip_html'					=> 'false',
			'tooltip_content'				=> '',
			'tooltip_content_html'			=> '',
			'tooltip_position'				=> 'ts-simptip-position-top',
			'tooltip_style'					=> '',
			'tooltipster_offsetx'			=> 0,
			'tooltipster_offsety'			=> 0,
			
			'margin_top'					=> 0,
			'margin_bottom'					=> 0,
			'el_id' 						=> '',
			'el_class'                  	=> '',
			'css'							=> '',
		), $atts ));
		
		// Determine Element ID
		if (!empty($el_id)) {
			$picstrips_image_id				= $el_id;
		} else {
			$picstrips_image_id				= 'ts-vcsc-image-picstrips-' . mt_rand(999999, 9999999);
		}
		
		$output 							= "";
		
		// Dynamic Image Checks
		if (($dynamic_source == "true") && ($dynamic_image != "")) {
			$image							= "";
			$dynamic_image					= rawurldecode(base64_decode(strip_tags($dynamic_image)));
			$dynamic_image					= do_shortcode($dynamic_image);
			$dynamic_image					= (int)$dynamic_image;
			if (($dynamic_image!= "") && (is_numeric($dynamic_image))) {
				$image						= $dynamic_image;
			}
		}
	
		// Retrieve Image from WordPress
		$picstrips_image 					= wp_get_attachment_image_src($image, 'full');

		// Tooltip
		$tooltip_position					= TS_VCSC_TooltipMigratePosition($tooltip_position);
		$tooltip_style						= TS_VCSC_TooltipMigrateStyle($tooltip_style);	
		$tooltip_class						= 'ts-has-tooltipster-tooltip';		
		if ($tooltip_html == "true") {
			if (strlen($tooltip_content_html) != 0) {
				$Tooltip_Content		= 'data-tooltipster-title="" data-tooltipster-text="' . rawurldecode(base64_decode(strip_tags($tooltip_content_html))) . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="swing" data-tooltipster-trigger="hover" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . $tooltipster_offsety . '"';
				$Tooltip_Class			= $tooltip_class;
			} else {
				$Tooltip_Content		= '';
				$Tooltip_Class			= '';
			}
		} else {
			if (strlen($tooltip_content) != 0) {
				$Tooltip_Content		= 'data-tooltipster-title="" data-tooltipster-text="' . $tooltip_content . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="swing" data-tooltipster-trigger="hover" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . $tooltipster_offsety . '"';
				$Tooltip_Class			= $tooltip_class;
			} else {
				$Tooltip_Content		= '';
				$Tooltip_Class			= '';
			}
		}		
		
		// Image Size
		if ($image_fixed == "false") {
			$picstrips_dimensions			= "width: " . $image_width_percent . "%; height: auto;";
			$picstrips_frame_size			= "width: " . $image_width_percent . "%;";
			$picstrips_wrapper_size			= "width: " . $image_width_percent . "%; height: auto;";
			$picstrips_tag					= "responsive";
			$picstrips_height				= "auto";
			$picstrips_width				= $image_width_percent;
		} else {
			$picstrips_dimensions			= "width: " . $image_width . "px; height: " . $image_height . "px;";
			$picstrips_frame_size			= "width: " . $image_width . "px;";
			$picstrips_wrapper_size			= "width: " . $image_width . "px; height: auto;";
			$picstrips_tag					= "fixed";
			$picstrips_height				= $image_height;
			$picstrips_width				= $image_width;
		}
	
		$image_extension 					= pathinfo($picstrips_image[0], PATHINFO_EXTENSION);
		
		// iFrame / Link
		if (($hover_event == "link") || ($hover_event == "iframe")) {
			$link 							= TS_VCSC_Advancedlinks_GetLinkData($hover_link);
			$a_href							= $link['url'];
			$a_title 						= '';
			$a_target 						= $link['target'];
		} else {
			$a_href							= 'javascript:void(0);';
			$a_title						= '';
			$a_target						= '_blank';
		}
		if ($a_href == '') {
			$a_href							= 'javascript:void(0);';
		}		
		// YouTube Video
		if ($hover_event == "youtube") {
			if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $hover_video_link)) {
				$hover_video_link		= $hover_video_link;
			} else {
				$hover_video_link		= 'https://www.youtube.com/watch?v=' . $hover_video_link;
			}
		}
		// DailyMotion Video
		if ($hover_event == "dailymotion") {
			if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $hover_video_link)) {
				$hover_video_link	= $hover_video_link;
			} else {			
				$hover_video_link	= $hover_video_link;
			}
		}
		
		// Handle Padding
		if (($hover_event != "none") && ($overlay_handle_show == "true")) {
			$overlay_margin					= "margin-bottom: 16px;";
			$image_margin					= 16;
			$switch_handle_adjust  			= "bottom: -16px;";
		} else {
			$overlay_margin					= "";
			$image_margin					= 0;
			$switch_handle_adjust  			= "";
		}
		$picstrips_margin 					= 'margin-top: ' . $margin_top . 'px; margin-bottom: ' . ($margin_bottom + $image_margin) . 'px;';
		
		// Handle Icon
		if ($hover_event != "none") {
			$switch_handle_icon				= 'handle_click';
		} else {
			$switch_handle_icon				= 'handle_hover';
		}
		
		// Custom Width / Height
		$lightbox_dimensions				= '';
		
		if ($lightbox_backlight == "auto") {
			$nacho_color					= '';
		} else if ($lightbox_backlight == "custom") {
			$nacho_color					= 'data-color="' . $lightbox_backlight_color . '"';
		} else if ($lightbox_backlight == "hideit") {
			$nacho_color					= 'data-color="rgba(0, 0, 0, 0)"';
		}
		
		// Link Output
		$linkstringStart = $linkstringEnd = '';
		if ($hover_frontent == "false") {
			if (($hover_event != "none") && ($hover_event == "popup")) {
				// Modal Popup
				$linkstringStart .= '<a id="' . $picstrips_image_id . '-trigger" href="#' . $picstrips_image_id . '-modal" class="ts-hover-image-link ' . $picstrips_image_id . '-parent nch-holder nch-lightbox-modal no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $hover_title . '" data-type="html" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '">';
				$linkstringEnd	 .= '</a>';
			} else if (($hover_event != "none") && ($hover_event == "iframe")) {
				// iFrame Popup
				$linkstringStart .= '<a id="' . $picstrips_image_id . '-trigger" href="' . $a_href . '" target="' . $a_target . '" class="ts-hover-image-link ' . $picstrips_image_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $hover_title . '" data-type="iframe" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '">';
				$linkstringEnd	 .= '</a>';
			} else if (($hover_event != "none") && ($hover_event == "image")) {
				// Image Popup
				$linkstringStart .= '<a id="' . $picstrips_image_id . '-trigger" href="' . $picstrips_image[0] . '" class="ts-hover-image-link ' . $picstrips_image_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $hover_title . '" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '">';
				$linkstringEnd	 .= '</a>';
			} else if (($hover_event != "none") && ($hover_event == "youtube")) {
				// YouTube Popup
				$linkstringStart .= '<a id="' . $picstrips_image_id . '-trigger" href="' . $hover_video_link .'" class="ts-hover-image-link ' . $picstrips_image_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $hover_title . '" data-related="' . $hover_video_related .'" data-videoplay="' . $hover_video_auto .'" data-type="youtube" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '">';
				$linkstringEnd	 .= '</a>';
			} else if (($hover_event != "none") && ($hover_event == "vimeo")) {
				// Vimeo Popup
				$linkstringStart .= '<a id="' . $picstrips_image_id . '-trigger" href="' . $hover_video_link . '" class="ts-hover-image-link ' . $picstrips_image_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $hover_title . '" data-videoplay="' . $hover_video_auto . '" data-type="vimeo" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '">';
				$linkstringEnd	 .= '</a>';
			} else if (($hover_event != "none") && ($hover_event == "dailymotion")) {
				// DailyMotion Popup
				$linkstringStart .= '<a id="' . $picstrips_image_id . '-trigger" href="' . $hover_video_link .'" class="ts-hover-image-link ' . $picstrips_image_id . '-parent nch-holder nch-lightbox-media" ' . $lightbox_dimensions . ' style="" data-title="' . $hover_title . '" data-videoplay="' . $hover_video_auto . '" data-type="dailymotion" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '">';
				$linkstringEnd	 .= '</a>';
			} else if (($hover_event != "none") && ($hover_event == "html5")) {
				// HTML5 Video Popup
				$linkstringStart .= '<a id="' . $picstrips_image_id . '-trigger" href="#' . $modal_id . '" class="ts-hover-image-link ' . $picstrips_image_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $hover_title . '" data-type="html" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '">';
				$linkstringEnd	 .= '</a>';
			} else if (($hover_event != "none") && ($hover_event == "link")) {
				// Link Event
				$linkstringStart .= '<a id="' . $picstrips_image_id . '-trigger" class="ts-hover-image-link" href="' . $a_href . '" target="' . $a_target . '" title="' . $a_title . '">';
				$linkstringEnd	 .= '</a>';
			} else {
				// No Link Event
				$linkstringStart .= '';
				$linkstringEnd	 .= '';
			}
		} else {
			$linkstringStart .= '';
			$linkstringEnd	 .= '';
		}
		
		if ($attribute_alt == "true") {
			$alt_attribute					= $attribute_alt_value;
		} else {
			$alt_attribute					= basename($picstrips_image[0], "." . $image_extension);
		}
		
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS-VCSC-Image-Picstrips', $atts);
		} else {
			$css_class						= '';
		}
		
		$content_title						= '';
		
		$output .= '<div id="' . $picstrips_image_id . '" class="ts-image-picstrips ts-image-hover-frame ' . $Tooltip_Class . ' ' . $el_class . ' ' . $css_class . '"' . $Tooltip_Content . ' data-strips="' . $splits_number . '" data-space="' . $splits_space . '" data-offset="' . $splits_offset . '" data-color="' . $splits_background . '" style="width: 100%; ' . $picstrips_margin . '">';
			if (($hover_event != "none") && ($overlay_handle_show == "true")) {
				$output .= '<div class="" style="width: 100%; height: 100%; ' . $overlay_margin . '">';
			}
				$output .= $linkstringStart;
					$output .= '<img class="ts-imagepicstrips" src="' . $picstrips_image[0] . '" alt="' . $alt_attribute . '" data-no-lazy="1" style="width: 100%; height: auto;"/>';
				$output .= $linkstringEnd;
				// Overlay Handle
				if (($hover_event != "none") && ($overlay_handle_show == "true")) {
					$output .= '<div class="ts-image-hover-handle" style="' . $switch_handle_adjust . '"><span class="frame_' . $switch_handle_icon . '" style="background-color: ' . $overlay_handle_color . '"><i class="' . $switch_handle_icon . '"></i></span></div>';
				}
			if (($hover_event != "none") && ($overlay_handle_show == "true")) {
				$output .= '</div>';
			}			
			// Create hidden DIV with Modal Popup iHover Content
			if (($hover_frontent == "false") && ($hover_event == "popup")) {
				$output .= '<div id="' . $picstrips_image_id . '-modal" class="ts-modal-content nch-hide-if-javascript" style="display: none; padding: 15px;">';
					$output .= '<div class="ts-modal-white-header"></div>';
					$output .= '<div class="ts-modal-white-frame" style="">';
						$output .= '<div class="ts-modal-white-inner">';
							if ($hover_title != "") {
								$output .= '<h2 style="border-bottom: 1px solid #eeeeee; padding-bottom: 10px; margin-bottom: 10px;">' . $hover_title . '</h2>';
							}
							$output .= rawurldecode(base64_decode(strip_tags($hover_text)));
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