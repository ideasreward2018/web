<?php
	if (!class_exists('TS_Image_Hover_Effects')){
		class TS_Image_Hover_Effects {
			function __construct() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						$this->TS_VCSC_Add_Image_Hover_Effects_Lean();
					} else if (function_exists('vc_map')) {
						add_action('init',                                  array($this, 'TS_VCSC_Add_Image_Hover_Effects_Elements'), 9999999);
					}
				} else {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_Image_Hover_Effects_Lean'), 9999999);
					} else if (function_exists('vc_map')) {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_Image_Hover_Effects_Elements'), 9999999);
					}
				}
				if ((is_admin() == false) || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAJAX == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAlways == "true")) {
					add_shortcode('TS_VCSC_Image_Hover_Effects',			array($this, 'TS_VCSC_Image_Hover_Effects_Function'));
				}
			}
			
			// Register Element(s) via LeanMap
			function TS_VCSC_Add_Image_Hover_Effects_Lean() {
				vc_lean_map('TS_VCSC_Image_Hover_Effects', 					array($this, 'TS_VCSC_Add_Image_Hover_Effects_Elements'), null);
			}
			
			// Image Advanced Hover Effects
			function TS_VCSC_Image_Hover_Effects_Function ($atts, $content = null) {
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();
	
				extract( shortcode_atts( array(
					'hover_image'					=> '',
					'hover_size'					=> 'medium',
					'hover_responsive'				=> 'true',
					'hover_truncate'				=> 'false',
					
					'effect_style_type'				=> 'text',
					'effect_style_text'				=> 'ts-hover-effect-lily',
					'effect_style_icons'			=> 'ts-hover-effect-zoe',
					'effect_permanent'				=> 'false',
					
					'custom_styling'				=> 'false',
					'custom_overlay'				=> '#3085a3',
					
					'size_type'						=> 'auto',
					'size_percent'					=> 100,
					'size_pixels'					=> 400,
					'size_align'					=> 'center',
					
					'title_text'					=> '',
					'title_color'					=> '#ffffff',
					'title_singleline'				=> 'true',
					'title_size'					=> 30,
					
					'content_code'					=> 'false',
					'content_text'					=> '',
					'content_html'					=> '',
					'content_size'					=> 16,
					'content_color_text'			=> '#ffffff',
					'content_color_icons'			=> '#000000',
					'content_color_other'			=> '#ffffff',
					
					'font_titlefamily'				=> 'Default:regular',
					'font_titletype'				=> 'default',
					'font_textfamily1'				=> 'Default:regular',
					'font_texttype1'				=> 'default',
					'font_textfamily2'				=> 'Default:regular',
					'font_texttype2'				=> 'default',
					
					'content_icons'					=> '',
					'content_link1'					=> '',
					'content_link1_icon'			=> '',
					'content_link1_tooltip'			=> '',
					'content_link2'					=> '',
					'content_link2_icon'			=> '',
					'content_link2_tooltip'			=> '',
					'content_link3'					=> '',
					'content_link3_icon'			=> '',
					'content_link3_tooltip'			=> '',
					'content_link4'					=> '',
					'content_link4_icon'			=> '',
					'content_link4_tooltip'			=> '',
	
					'hover_event'					=> 'none',
					'hover_other'					=> '',
					'hover_show_title'				=> 'true',
					'hover_text'					=> '',
					'hover_image'					=> '',
					'hover_link'					=> '',				
					'hover_video_link'				=> '',
					'hover_video_auto'				=> 'true',
					'hover_video_related'			=> 'false',				
					
					'overlay_trigger'				=> 'ts-trigger-hover',
					'overlay_handle_show'			=> 'true',
					'overlay_handle_color'			=> '#0094FF',
					
					'tooltip_html'					=> 'false',
					'tooltip_content'				=> '',
					'tooltip_content_html'			=> '',
					'tooltip_position'				=> 'ts-simptip-position-top',
					'tooltip_style'					=> 'ts-simptip-style-black',
					'tooltip_animation'				=> 'swing',
					'tooltipster_offsetx'			=> 0,
					'tooltipster_offsety'			=> 0,
					
					'lightbox_width'				=> 'auto',
					'lightbox_width_percent'		=> 100,
					'lightbox_width_pixel'			=> 1024,
					'lightbox_height'				=> 'auto',
					'lightbox_height_percent'		=> 100,
					'lightbox_height_pixel'			=> 400,
					
					'lightbox_group_name'			=> 'nachogroup',
					'lightbox_size'					=> 'full',
					'lightbox_effect'				=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LightboxDefaultAnimation,
					'lightbox_speed'				=> 5000,
					'lightbox_social'				=> 'false',
					'lightbox_backlight'			=> 'auto',
					'lightbox_backlight_color'		=> '#ffffff',
					
					'lightbox_custom_padding'		=> 15,
					'lightbox_custom_background'	=> 'none',
					'lightbox_background_image'		=> '',
					'lightbox_background_size'		=> 'cover',
					'lightbox_background_repeat'	=> 'no-repeat',
					'lightbox_background_color'		=> '#ffffff',
					
					'margin_top'					=> 0,
					'margin_bottom'					=> 0,
					'el_id' 						=> '',
					'el_class'              		=> '',
					'css'							=> '',
				), $atts ));
				
				$output                             = '';
				$linkStringStart					= '';
				$linkStringEnd						= '';		
				
				// Check for Front End Editor
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					$hover_frontent					= "true";
				} else {
					$hover_frontent					= "false";
				}
	
				if (($hover_frontent == "false") && ($hover_event != 'none') && ($hover_event != 'link')) {
					wp_enqueue_script('ts-extend-krautlightbox');
					wp_enqueue_style('ts-extend-krautlightbox');
				}	
				wp_enqueue_style('ts-extend-tooltipster');
				wp_enqueue_script('ts-extend-tooltipster');	
				wp_enqueue_style('ts-extend-hovereffects');
				wp_enqueue_script('ts-extend-badonkatrunc');
				wp_enqueue_style('ts-font-teammates');
				wp_enqueue_style('ts-visual-composer-extend-front');
				wp_enqueue_script('ts-visual-composer-extend-front');
				wp_enqueue_script('ts-extend-imageeffects');
			
				if (!empty($el_id)) {
					$hover_image_id					= $el_id;
				} else {
					$hover_image_id					= 'ts-vcsc-hover-effects-' . mt_rand(999999, 9999999);
				}
				
				if ($effect_permanent == "true") {
					$overlay_handle_show			= "false";
				}
				
				// Effect Style
				if ($effect_style_type == "text") {
					$effect_style					= $effect_style_text;
				} else {
					$effect_style					= $effect_style_icons;
				}			
				// Image
				if (!empty($hover_image)) {
					if ($hover_event == "image") {
						$hover_image_link			= wp_get_attachment_image_src($hover_image, $lightbox_size);
					}
					$hover_image					= wp_get_attachment_image_src($hover_image, $hover_size);
				} else {
					$hover_image_link				= array();
				}
				
				if (!isset($hover_image[0])) {
					$myvariable = ob_get_clean();
					return $myvariable;
				}
				
				// Content Icon Links
				$link_content1 						= TS_VCSC_Advancedlinks_GetLinkData($content_link1);
				$a_href_content1					= $link_content1['url'];
				$a_title_content1 					= $link_content1['title'];
				$a_target_content1 					= $link_content1['target'];
				$a_rel_content1 					= $link_content1['rel'];
				if (!empty($a_rel_content1)) {
					$a_rel_content1 				= 'rel="' . esc_attr(trim($a_rel_content1)) . '"';
				}
				$link_content2 						= TS_VCSC_Advancedlinks_GetLinkData($content_link2);
				$a_href_content2					= $link_content2['url'];
				$a_title_content2 					= $link_content2['title'];
				$a_target_content2 					= $link_content2['target'];
				$a_rel_content2 					= $link_content2['rel'];
				if (!empty($a_rel_content2)) {
					$a_rel_content2 				= 'rel="' . esc_attr(trim($a_rel_content2)) . '"';
				}
				$link_content3 						= TS_VCSC_Advancedlinks_GetLinkData($content_link3);
				$a_href_content3					= $link_content3['url'];
				$a_title_content3 					= $link_content3['title'];
				$a_target_content3 					= $link_content3['target'];
				$a_rel_content3 					= $link_content3['rel'];
				if (!empty($a_rel_content3)) {
					$a_rel_content3 				= 'rel="' . esc_attr(trim($a_rel_content3)) . '"';
				}
				$link_content4 						= TS_VCSC_Advancedlinks_GetLinkData($content_link4);
				$a_href_content4					= $link_content4['url'];
				$a_title_content4 					= $link_content4['title'];
				$a_target_content4 					= $link_content4['target'];
				$a_rel_content4 					= $link_content4['rel'];
				if (!empty($a_rel_content4)) {
					$a_rel_content4 				= 'rel="' . esc_attr(trim($a_rel_content4)) . '"';
				}
				// Other Image
				if ($hover_event == "other") {
					$hover_image_link				= wp_get_attachment_image_src($hover_other, $lightbox_size);
				}
				// iFrame / Link
				if (($hover_event == "link") || ($hover_event == "iframe")) {
					$link 							= TS_VCSC_Advancedlinks_GetLinkData($hover_link);
					$a_href							= $link['url'];
					$a_title 						= $link['title'];
					$a_target 						= $link['target'];
					$a_rel 							= $link['rel'];
					if (!empty($a_rel)) {
						$a_rel 						= 'rel="' . esc_attr(trim($a_rel)) . '"';
					}
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
						$hover_video_link			= $hover_video_link;
					} else {
						$hover_video_link			= 'https://www.youtube.com/watch?v=' . $hover_video_link;
					}
				}
				// DailyMotion Video
				if ($hover_event == "dailymotion") {
					if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $hover_video_link)) {
						$hover_video_link			= $hover_video_link;
					} else {			
						$hover_video_link			= $hover_video_link;
					}
				}				
				// Backlight Color
				if ($lightbox_backlight != "auto") {
					if ($lightbox_backlight == "custom") {
						$nacho_color				= 'data-color="' . $lightbox_backlight_color . '" data-nohashes="true"';
					} else if ($lightbox_backlight == "hideit") {
						$nacho_color				= 'data-color="rgba(0, 0, 0, 0)" data-nohashes="true"';
					}
				} else {
					$nacho_color					= 'data-nohashes="true"';
				}		
				// Custom Width / Height
				$lightbox_dimensions				= '';
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
				// Handle Padding
				if ($overlay_handle_show == "true") {
					$overlay_padding				= "padding-bottom: 25px;";
					$switch_handle_adjust  			= "";
				} else {
					$overlay_padding				= "";
					$switch_handle_adjust  			= "";
				}				
				// Handle Icon
				if ($hover_event != "none") {
					$switch_handle_icon				= 'handle_click';
				} else {
					$switch_handle_icon				= 'handle_hover';
				}				
				// Size Settings
				if ($size_type == 'auto') {
					$element_dimensions				= '';
				} else if ($size_type == 'percent') {
					$element_dimensions				= 'width: ' . $size_percent . '%;';
				} else if ($size_type == 'pixels') {
					$element_dimensions				= 'width: ' . $size_pixels . 'px;';
				}
				if ($size_type != 'auto') {
					if ($size_align == 'center') {
						$element_dimensions			.= 'float: none; margin-left: auto; margin-right: auto;';
					} else if ($size_align == 'left') {
						$element_dimensions			.= 'float: left; margin-left: 0; margin-right: 0;';
					} else if ($size_align == 'right') {
						$element_dimensions			.= 'float: right; margin-left: 0; margin-right: 0;';
					}
				}				
				// Make Effect Permanent
				if ($effect_permanent == "true") {
					$Permanent_Class			= 'ts-hover-effect-permanent';
					$Permanent_Link				= 'ts-hover-image-link-permanent';
				} else {
					$Permanent_Class			= '';
					$Permanent_Link				= 'ts-hover-image-link-trigger';
				}
				// Link Output
				if (($hover_frontent == "false") && ($effect_style != "ts-hover-effect-zoe") && ($effect_style != "ts-hover-effect-hera") && ($effect_style != "ts-hover-effect-winston") && ($effect_style != "ts-hover-effect-terry") && ($effect_style != "ts-hover-effect-phoebe") && ($effect_style != "ts-hover-effect-kira")) {
					if (($hover_event != "none") && ($hover_event == "popup")) {
						// Modal Popup
						$linkStringStart 		.= '<a id="' . $hover_image_id . '-trigger" href="#' . $hover_image_id . '-modal" class="ts-hover-image-link ' . $Permanent_Link . ' ' . $hover_image_id . '-parent nch-holder nch-lightbox-modal no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $title_text . '" data-type="html" rel="' . $lightbox_group_name . '" data-share="0" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
						$linkStringEnd			.= '</a>';
					} else if (($hover_event != "none") && ($hover_event == "iframe")) {
						// iFrame Popup
						$linkStringStart 		.= '<a id="' . $hover_image_id . '-trigger" href="' . $a_href . '" target="' . $a_target . '" ' . $a_rel . ' class="ts-hover-image-link ' . $Permanent_Link . ' ' . $hover_image_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $title_text . '" data-type="iframe" rel="' . $lightbox_group_name . '" data-share="0" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
						$linkStringEnd			.= '</a>';
					} else if (($hover_event != "none") && (($hover_event == "image") || ($hover_event == "other"))) {
						// (Other) Image Popup
						$linkStringStart 		.= '<a id="' . $hover_image_id . '-trigger" href="' . $hover_image_link[0] . '" class="ts-hover-image-link ' . $Permanent_Link . ' ' . $hover_image_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $title_text . '" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
						$linkStringEnd			.= '</a>';
					} else if (($hover_event != "none") && ($hover_event == "youtube")) {
						// YouTube Popup
						$linkStringStart 		.= '<a id="' . $hover_image_id . '-trigger" href="' . $hover_video_link .'" class="ts-hover-image-link ' . $Permanent_Link . ' ' . $hover_image_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $title_text . '" data-related="' . $hover_video_related .'" data-videoplay="' . $hover_video_auto .'" data-type="youtube" rel="' . $lightbox_group_name . '" data-share="0" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
						$linkStringEnd			.= '</a>';
					} else if (($hover_event != "none") && ($hover_event == "vimeo")) {
						// Vimeo Popup
						$linkStringStart 		.= '<a id="' . $hover_image_id . '-trigger" href="' . $hover_video_link . '" class="ts-hover-image-link ' . $Permanent_Link . ' ' . $hover_image_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $title_text . '" data-videoplay="' . $hover_video_auto . '" data-type="vimeo" rel="' . $lightbox_group_name . '" data-share="0" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
						$linkStringEnd			.= '</a>';
					} else if (($hover_event != "none") && ($hover_event == "dailymotion")) {
						// DailyMotion Popup
						$linkStringStart 		.= '<a id="' . $hover_image_id . '-trigger" href="' . $hover_video_link .'" class="ts-hover-image-link ' . $Permanent_Link . ' ' . $hover_image_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $title_text . '" data-videoplay="' . $hover_video_auto . '" data-type="dailymotion" rel="' . $lightbox_group_name . '" data-share="0" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
						$linkStringEnd			.= '</a>';
					} else if (($hover_event != "none") && ($hover_event == "html5")) {
						// HTML5 Video Popup
						$linkStringStart 		.= '<a id="' . $hover_image_id . '-trigger" href="#' . $hover_image_id . '-modal" class="ts-hover-image-link ' . $Permanent_Link . ' ' . $hover_image_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $title_text . '" data-type="html" rel="' . $lightbox_group_name . '" data-share="0" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
						$linkStringEnd			.= '</a>';
					} else if (($hover_event != "none") && ($hover_event == "link")) {
						// Link Event
						$linkStringStart 		.= '<a id="' . $hover_image_id . '-trigger" class="ts-hover-image-link ' . $Permanent_Link . ' ' . $hover_image_id . '-parent" href="' . $a_href . '" target="' . $a_target . '" ' . $a_rel . ' title="' . $a_title . '">';
						$linkStringEnd			.= '</a>';
					} else {
						// No Link Event
						$linkStringStart 		= '';
						$linkStringEnd			= '';
					}
				} else {
					$linkStringStart 			= '';
					$linkStringEnd				= '';
				}				
				// Tooltip
				$tooltip_position				= TS_VCSC_TooltipMigratePosition($tooltip_position);
				$tooltip_style					= TS_VCSC_TooltipMigrateStyle($tooltip_style);	
				$tooltip_class					= 'ts-has-tooltipster-tooltip';		
				if ($tooltip_html == "true") {
					if (strlen($tooltip_content_html) != 0) {
						$Tooltip_Content		= 'data-tooltipster-html="true" data-tooltipster-title="" data-tooltipster-text="' . strip_tags($tooltip_content_html) . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="' . $tooltip_animation . '" data-tooltipster-trigger="hover" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . $tooltipster_offsety . '"';
						$Tooltip_Class			= $tooltip_class;
					} else {
						$Tooltip_Content		= '';
						$Tooltip_Class			= '';
					}
				} else {
					if (strlen($tooltip_content) != 0) {
						$Tooltip_Content		= 'data-tooltipster-html="false" data-tooltipster-title="" data-tooltipster-text="' . $tooltip_content . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="' . $tooltip_animation . '" data-tooltipster-trigger="hover" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . $tooltipster_offsety . '"';
						$Tooltip_Class			= $tooltip_class;
					} else {
						$Tooltip_Content		= '';
						$Tooltip_Class			= '';
					}
				}
				// Icon Tooltip
				if (strlen($content_link1_tooltip) != 0) {
					$Tooltip_Link1_Icon			= 'data-tooltipster-html="true" data-tooltipster-title="" data-tooltipster-text="' . strip_tags($content_link1_tooltip) . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="' . $tooltip_animation . '" data-tooltipster-trigger="hover" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . ($tooltipster_offsety + 10) . '"';
					$Tooltip_Link1_Class		= $tooltip_class;
				} else {
					$Tooltip_Link1_Icon			= '';
					$Tooltip_Link1_Class		= '';
				}
				if (strlen($content_link2_tooltip) != 0) {
					$Tooltip_Link2_Icon			= 'data-tooltipster-html="true" data-tooltipster-title="" data-tooltipster-text="' . strip_tags($content_link2_tooltip) . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="' . $tooltip_animation . '" data-tooltipster-trigger="hover" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . ($tooltipster_offsety + 10) . '"';
					$Tooltip_Link2_Class		= $tooltip_class;
				} else {
					$Tooltip_Link2_Icon			= '';
					$Tooltip_Link2_Class		= '';
				}
				if (strlen($content_link3_tooltip) != 0) {
					$Tooltip_Link3_Icon			= 'data-tooltipster-html="true" data-tooltipster-title="" data-tooltipster-text="' . strip_tags($content_link3_tooltip) . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="' . $tooltip_animation . '" data-tooltipster-trigger="hover" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . ($tooltipster_offsety + 10) . '"';
					$Tooltip_Link3_Class		= $tooltip_class;
				} else {
					$Tooltip_Link3_Icon			= '';
					$Tooltip_Link3_Class		= '';
				}
				if (strlen($content_link4_tooltip) != 0) {
					$Tooltip_Link4_Icon			= 'data-tooltipster-html="true" data-tooltipster-title="" data-tooltipster-text="' . strip_tags($content_link4_tooltip) . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="' . $tooltip_animation . '" data-tooltipster-trigger="hover" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . ($tooltipster_offsety + 10) . '"';
					$Tooltip_Link4_Class		= $tooltip_class;
				} else {
					$Tooltip_Link4_Icon			= '';
					$Tooltip_Link4Class			= '';
				}
				// Custom Fonts
				if (strpos($font_titlefamily, 'Default') === false) {
					$font_title 				= TS_VCSC_GetFontFamily($hover_image_id, $font_titlefamily, $font_titletype, false, true, false);
				} else {
					$font_title					= '';
				}
				if (strpos($font_textfamily1, 'Default') === false) {
					$font_text1 				= TS_VCSC_GetFontFamily($hover_image_id, $font_textfamily1, $font_texttype1, false, true, false);
				} else {
					$font_text1					= '';
				}
				if (strpos($font_textfamily2, 'Default') === false) {
					$font_text2 				= TS_VCSC_GetFontFamily($hover_image_id, $font_textfamily2, $font_texttype2, false, true, false);
				} else {
					$font_text2					= '';
				}
				// Visual Composer Class Override
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 					= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-hover-effects-container ts-image-effects-frame ts-image-hover-frame ' . $el_class . '' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Image_Hover_Effects', $atts);
				} else {
					$css_class					= 'ts-hover-effects-container ts-image-effects-frame ts-image-hover-frame ' . $el_class . '';
				}
				// Create Final Output
				$output .= '<div id="' . $hover_image_id . '" class="' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $element_dimensions . '">';
					if ((strlen($tooltip_content_html) != 0) || (strlen($tooltip_content) != 0)) {
						$output .= '<div class="' . $Tooltip_Class . '" ' . $Tooltip_Content . ' title="" style="width: 100%; height: 100%;">';
					}
						if ($overlay_handle_show == "true") {
							$output .= '<div class="" style="' . $overlay_padding . '">';
						}				
							$output .= '<div class="ts-hover-effects-grid">';					
								$output .= $linkStringStart;
									$output .= '<figure class="' . $effect_style . ' ' . $Permanent_Class . '" style="' . ($custom_styling == "true" ? "background: " . $custom_overlay . ";" : "") . '">';
										$output .= '<img src="' . $hover_image[0] . '" data-no-lazy="1" data-width="' . $hover_image[1] . '" data-height="' . $hover_image[2] . '" data-ratio="' . ((isset($hover_image[1]) && isset($hover_image[2])) ? ($hover_image[1] / $hover_image[2]) : 1) . '" alt=""/>';
										$output .= '<figcaption class="ts-hover-effects-figcaption">';
											if (($effect_style == "ts-hover-effect-lily") || ($effect_style == "ts-hover-effect-julia")) {
												$output .= '<div>';
											}
												$output .= '<h2 class="' . ($title_singleline == "false" ? "ts-hover-effects-breakall" : "") . '" style="' . $font_title . ' color: ' . $title_color . '; font-size: ' . $title_size . 'px;">' . $title_text . '</h2>';
												if (($effect_style != "ts-hover-effect-zoe") && ($effect_style != "ts-hover-effect-hera") && ($effect_style != "ts-hover-effect-winston") && ($effect_style != "ts-hover-effect-terry") && ($effect_style != "ts-hover-effect-kira")) {
													if ($content_code == "false") {
														$output .= '<div class="ts-hover-effect-content" style="' . $font_text1 . ' color: ' . $content_color_text . '; font-size: ' . $content_size . 'px;">' . strip_tags($content_text) . '</div>';
													} else {
														$output .= '<div class="ts-hover-effect-content" style="' . $font_text1 . ' color: ' . $content_color_text . '; font-size: ' . $content_size . 'px;">' . rawurldecode(base64_decode(strip_tags($content_html))) . '</div>';
													}
												}
												if ($effect_style == "ts-hover-effect-zoe") {
													$output .= '<div class="zoe-icon-links" style="color: ' . $content_color_icons . '">';
														if (($a_href_content1 != "") && ($content_link1_icon != "")) {
															$output .= '<a href="' . $a_href_content1 . '" target="' . $a_target_content1 . '" ' . $a_rel_content1 . ' class="' . $Tooltip_Link1_Class . '" title="' . $a_title_content1 . '" ' . $Tooltip_Link1_Icon . '><span style="color: ' . $content_color_icons . '" class="ts-teammate-icon ' . $content_link1_icon . '"></span></a>';
														}
														if (($a_href_content2 != "") && ($content_link2_icon != "")) {
															$output .= '<a href="' . $a_href_content2 . '" target="' . $a_target_content2 . '" ' . $a_rel_content2 . ' class="' . $Tooltip_Link1_Class . '" title="' . $a_title_content2 . '" ' . $Tooltip_Link2_Icon . '><span style="color: ' . $content_color_icons . '" class="ts-teammate-icon ' . $content_link2_icon . '"></span></a>';
														}
														if (($a_href_content3 != "") && ($content_link3_icon != "")) {
															$output .= '<a href="' . $a_href_content3 . '" target="' . $a_target_content3 . '" ' . $a_rel_content3 . ' class="' . $Tooltip_Link1_Class . '" title="' . $a_title_content3 . '" ' . $Tooltip_Link3_Icon . '><span style="color: ' . $content_color_icons . '" class="ts-teammate-icon ' . $content_link3_icon . '"></span></a>';
														}
													$output .= '</div>';
													$output .= '<div class="ts-hover-effect-content" style="' . $font_text2 . ' color: ' . $content_color_other . ';">' . strip_tags($content_icons) . '</div>';
												}
												if (($effect_style == "ts-hover-effect-hera") || ($effect_style == "ts-hover-effect-terry") || ($effect_style == "ts-hover-effect-kira")) {
													$output .= '<div class="ts-hover-effect-content" style="color: ' . $content_color_icons . '">';
														if (($a_href_content1 != "") && ($content_link1_icon != "")) {
															$output .= '<a href="' . $a_href_content1 . '" target="' . $a_target_content1 . '" ' . $a_rel_content1 . ' class="' . $Tooltip_Link1_Class . '" title="' . $a_title_content1 . '" ' . $Tooltip_Link1_Icon . '><i style="color: ' . $content_color_icons . '" class="ts-teammate-icon ' . $content_link1_icon . '"></i></a>';
														}
														if (($a_href_content2 != "") && ($content_link2_icon != "")) {
															$output .= '<a href="' . $a_href_content2 . '" target="' . $a_target_content2 . '" ' . $a_rel_content2 . ' class="' . $Tooltip_Link1_Class . '" title="' . $a_title_content2 . '" ' . $Tooltip_Link2_Icon . '><i style="color: ' . $content_color_icons . '" class="ts-teammate-icon ' . $content_link2_icon . '"></i></a>';
														}
														if (($a_href_content3 != "") && ($content_link3_icon != "")) {
															$output .= '<a href="' . $a_href_content3 . '" target="' . $a_target_content3 . '" ' . $a_rel_content3 . ' class="' . $Tooltip_Link1_Class . '" title="' . $a_title_content3 . '" ' . $Tooltip_Link3_Icon . '><i style="color: ' . $content_color_icons . '" class="ts-teammate-icon ' . $content_link3_icon . '"></i></a>';
														}
														if (($a_href_content4 != "") && ($content_link4_icon != "")) {
															$output .= '<a href="' . $a_href_content4 . '" target="' . $a_target_content4 . '" ' . $a_rel_content4 . ' class="' . $Tooltip_Link1_Class . '" title="' . $a_title_content4 . '" ' . $Tooltip_Link4_Icon . '><i style="color: ' . $content_color_icons . '" class="ts-teammate-icon ' . $content_link4_icon . '"></i></a>';
														}
													$output .= '</div>';
												}
												if (($effect_style == "ts-hover-effect-winston") || ($effect_style == "ts-hover-effect-phoebe")) {
													$output .= '<div class="ts-hover-effect-content" style="color: ' . $content_color_icons . '">';
														if (($a_href_content1 != "") && ($content_link1_icon != "")) {
															$output .= '<a href="' . $a_href_content1 . '" target="' . $a_target_content1 . '" ' . $a_rel_content1 . ' class="' . $Tooltip_Link1_Class . '" title="' . $a_title_content1 . '" ' . $Tooltip_Link1_Icon . '><i style="color: ' . $content_color_icons . '" class="ts-teammate-icon ' . $content_link1_icon . '"></i></a>';
														}
														if (($a_href_content2 != "") && ($content_link2_icon != "")) {
															$output .= '<a href="' . $a_href_content2 . '" target="' . $a_target_content2 . '" ' . $a_rel_content2 . ' class="' . $Tooltip_Link1_Class . '" title="' . $a_title_content2 . '" ' . $Tooltip_Link2_Icon . '><i style="color: ' . $content_color_icons . '" class="ts-teammate-icon ' . $content_link2_icon . '"></i></a>';
														}
														if (($a_href_content3 != "") && ($content_link3_icon != "")) {
															$output .= '<a href="' . $a_href_content3 . '" target="' . $a_target_content3 . '" ' . $a_rel_content3 . ' class="' . $Tooltip_Link1_Class . '" title="' . $a_title_content3 . '" ' . $Tooltip_Link3_Icon . '><i style="color: ' . $content_color_icons . '" class="ts-teammate-icon ' . $content_link3_icon . '"></i></a>';
														}
													$output .= '</div>';
												}
											if (($effect_style == "ts-hover-effect-lily") || ($effect_style == "ts-hover-effect-julia")) {
												$output .= '</div>';
											}
										$output .= '</figcaption>';	
									$output .= '</figure>';
								$output .= $linkStringEnd;
							$output .= '</div>';				
							if ($overlay_handle_show == "true") {
								$output .= '<div class="ts-image-hover-handle" style="' . $switch_handle_adjust . '"><span class="frame_' . $switch_handle_icon . '" style="background-color: ' . $overlay_handle_color . '"><i class="' . $switch_handle_icon . '"></i></span></div>';
							}
						if ($overlay_handle_show == "true") {
							$output .= '</div>';
						}
					if ((strlen($tooltip_content_html) != 0) || (strlen($tooltip_content) != 0)) {
						$output .= '</div>';
					}
					// Create hidden DIV with Modal Popup Hover Content
					if (($hover_frontent == "false") && ($hover_event == "popup")) {
						$output .= '<div id="' . $hover_image_id . '-modal" class="ts-modal-content nch-hide-if-javascript ' . $el_class . '" style="display: none; padding: ' . $lightbox_custom_padding . 'px; ' . $lightbox_background . '">';
							$output .= '<div class="ts-modal-white-header"></div>';
							$output .= '<div class="ts-modal-white-frame" style="">';
								$output .= '<div class="ts-modal-white-inner">';
									if (($hover_show_title == "true") && ($title_text != "")) {
										$output .= '<h2 style="border-bottom: 1px solid #eeeeee; padding-bottom: 10px; margin-bottom: 10px;">' . $title_text . '</h2>';
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
			// Add Image Hover Effects Elements
			function TS_VCSC_Add_Image_Hover_Effects_Elements() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					"name"                              => __( "TS Image Advanced Overlay", "ts_visual_composer_extend" ),
					"base"                              => "TS_VCSC_Image_Hover_Effects",
					"icon" 	                            => "ts-composer-element-icon-image-hovereffects",
					"category"                          => __( 'VC Extensions', "ts_visual_composer_extend" ),
					"description"                       => __("Place an image with Hover effects", "ts_visual_composer_extend"),
					"admin_enqueue_js"            		=> "",
					"admin_enqueue_css"           		=> "",
					"params"                            => array(
						// Style Selection
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_1",
							"seperator"					=> "Hover Selection",
						),
						array(
							"type"                  	=> "attach_image",
							"holder" 					=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorImagePreview == "true" ? "img" : ""),
							"heading"               	=> __( "Image", "ts_visual_composer_extend" ),
							"param_name"            	=> "hover_image",
							"class"						=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorImagePreview == "true" ? "ts_vcsc_holder_image" : ""),
							"value"                 	=> "",
							"admin_label"       		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorImagePreview == "true" ? false : true),
							"description"           	=> __( "Select the image you want to use with the Hover effect.", "ts_visual_composer_extend" )
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Image Source", "ts_visual_composer_extend" ),
							"param_name"            	=> "hover_size",
							"width"                 	=> 150,
							"value"                 	=> array(
								__( 'Medium Size Image', "ts_visual_composer_extend" )					=> "medium",
								__( 'Thumbnail Size Image', "ts_visual_composer_extend" )				=> "thumbnail",
								__( 'Large Size Image', "ts_visual_composer_extend" )					=> "large",
								__( 'Full Size Image', "ts_visual_composer_extend" )					=> "full",
							),
							"description"           	=> __( "Select which image size based on WordPress settings should be used for the preview image.", "ts_visual_composer_extend" ),
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Element Sizing", "ts_visual_composer_extend" ),
							"param_name"		    	=> "size_type",
							"value"                 	=> array(
								__("Full Column Width", "ts_visual_composer_extend")					=> "auto",
								__("Width in Percent of Column", "ts_visual_composer_extend")			=> "percent",
								__("Fixed Width in Pixels", "ts_visual_composer_extend")				=> "pixels",
							),
							"description"		    	=> __( "Select the general style for the Hover effect.", "ts_visual_composer_extend" ),
						),
						array(
							"type"						=> "nouislider",
							"heading"					=> __( "Width in Percent", "ts_visual_composer_extend" ),
							"param_name"				=> "size_percent",
							"value"						=> "100",
							"min"						=> "10",
							"max"						=> "100",
							"step"						=> "1",
							"unit"						=> '%',
							"description"				=> __( "Define a width in percent of the column the element is embedded in.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "size_type", 'value' => 'percent' ),
						),	
						array(
							"type"						=> "nouislider",
							"heading"					=> __( "Fixed Width in Pixels", "ts_visual_composer_extend" ),
							"param_name"				=> "size_pixels",
							"value"						=> "400",
							"min"						=> "200",
							"max"						=> "1024",
							"step"						=> "1",
							"unit"						=> 'px',
							"description"				=> __( "Define a fixed width for the element; all responsiveness will be lost.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "size_type", 'value' => 'pixels' ),
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Element Alignment", "ts_visual_composer_extend" ),
							"param_name"		    	=> "size_align",
							"value"                 	=> array(
								__("Center", "ts_visual_composer_extend")								=> "center",
								__("Left", "ts_visual_composer_extend")									=> "left",
								__("Right", "ts_visual_composer_extend")								=> "right",
							),
							"description"		    	=> __( "Select how the element should be aligned inside the column.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "size_type", 'value' => array('percent', 'pixels') ),
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Effect Style", "ts_visual_composer_extend" ),
							"param_name"		    	=> "effect_style_type",
							"value"                 	=> array(
								__("Overlay with Text", "ts_visual_composer_extend")	=> "text",
								__("Overlay with Icons", "ts_visual_composer_extend")	=> "icons",
							),
							"description"		    	=> __( "Select the general style for the Hover effect.", "ts_visual_composer_extend" ),
							"admin_label"       		=> true,
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Text Style", "ts_visual_composer_extend" ),
							"param_name"		    	=> "effect_style_text",
							"value"                 	=> array(
								__("Nice Lily", "ts_visual_composer_extend")			=> "ts-hover-effect-lily",
								__("Holy Sadie", "ts_visual_composer_extend")			=> "ts-hover-effect-sadie",
								__("Dreamy Honey", "ts_visual_composer_extend")			=> "ts-hover-effect-honey",
								__("Crazy Layla", "ts_visual_composer_extend")			=> "ts-hover-effect-layla",								
								__("Warm Oscar", "ts_visual_composer_extend")			=> "ts-hover-effect-oscar",
								__("Sweet Marley", "ts_visual_composer_extend")			=> "ts-hover-effect-marley",
								__("Glowing Ruby", "ts_visual_composer_extend")			=> "ts-hover-effect-ruby",
								__("Charming Roxy", "ts_visual_composer_extend")		=> "ts-hover-effect-roxy",
								__("Fresh Bubba", "ts_visual_composer_extend")			=> "ts-hover-effect-bubba",
								__("Wild Romeo", "ts_visual_composer_extend")			=> "ts-hover-effect-romeo",
								__("Strange Dexter", "ts_visual_composer_extend")		=> "ts-hover-effect-dexter",
								__("Free Sarah", "ts_visual_composer_extend")			=> "ts-hover-effect-sarah",
								__("Silly Chico", "ts_visual_composer_extend")			=> "ts-hover-effect-chico",
								__("Faithful Milo", "ts_visual_composer_extend")		=> "ts-hover-effect-milo",
								__("Passionate Julia", "ts_visual_composer_extend")		=> "ts-hover-effect-julia",
								__("Thoughtful Goliath", "ts_visual_composer_extend")	=> "ts-hover-effect-goliath",
								__("Happy Selena", "ts_visual_composer_extend")			=> "ts-hover-effect-selena",
								__("Strong Apollo", "ts_visual_composer_extend")		=> "ts-hover-effect-apollo",
								__("Lonely Steve", "ts_visual_composer_extend")			=> "ts-hover-effect-steve",
								__("Cute Moses", "ts_visual_composer_extend")			=> "ts-hover-effect-moses",
								__("Dynamic Jazz", "ts_visual_composer_extend")			=> "ts-hover-effect-jazz",
								__("Funny Ming", "ts_visual_composer_extend")			=> "ts-hover-effect-ming",
								__("Altruistic Lexi", "ts_visual_composer_extend")		=> "ts-hover-effect-lexi",
								__("Messy Duke", "ts_visual_composer_extend")			=> "ts-hover-effect-duke",
							),
							"description"		    	=> __( "Select the general style for the Hover effect.", "ts_visual_composer_extend" ),
							"admin_label"       		=> true,
							"dependency"            	=> array( 'element' => "effect_style_type", 'value' => 'text' ),
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Icon Style", "ts_visual_composer_extend" ),
							"param_name"		    	=> "effect_style_icons",
							"value"                 	=> array(
								__("Creative Zoe", "ts_visual_composer_extend")			=> "ts-hover-effect-zoe",
								__("Tender Hera", "ts_visual_composer_extend")			=> "ts-hover-effect-hera",
								__("Jolly Winston", "ts_visual_composer_extend")		=> "ts-hover-effect-winston",
								__("Noisy Terry", "ts_visual_composer_extend")			=> "ts-hover-effect-terry",
								__("Plain Pheobe", "ts_visual_composer_extend")			=> "ts-hover-effect-phoebe",
								__("Dark Kira", "ts_visual_composer_extend")			=> "ts-hover-effect-kira",
							),
							"description"		    	=> __( "Select the general style for the Hover effect.", "ts_visual_composer_extend" ),
							"admin_label"       		=> true,
							"dependency"            	=> array( 'element' => "effect_style_type", 'value' => 'icons' ),
						),
						array(
							"type"             	 		=> "switch_button",
							"heading"               	=> __( "Always Show Overlay", "ts_visual_composer_extend" ),
							"param_name"            	=> "effect_permanent",
							"value"                 	=> "false",
							"admin_label"       		=> true,
							"description"       		=> __( "Use the toggle to always show the overlay content or show on hover only.", "ts_visual_composer_extend" ),
						),						
						array(
							"type"             	 		=> "switch_button",
							"heading"               	=> __( "Custom Overlay Background", "ts_visual_composer_extend" ),
							"param_name"            	=> "custom_styling",
							"value"                 	=> "false",
							"description"       		=> __( "Use the toggle to apply a custom background color to the overlay style you selected.", "ts_visual_composer_extend" ),
						),
						array(
							"type"                  	=> "colorpicker",
							"heading"              	 	=> __( "Overlay Background Color", "ts_visual_composer_extend" ),
							"param_name"            	=> "custom_overlay",
							"value"                 	=> "#3085a3",
							"description"           	=> __( "Define the background color for the overlay style you selected.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "custom_styling", 'value' => 'true' ),
						),						
						// Content Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_2",
							"seperator"					=> "Hover Title",
							"group" 			        => "Content",
						),
						array(
							"type"						=> "textfield",
							"heading"					=> __( "Title", "ts_visual_composer_extend" ),
							"param_name"				=> "title_text",
							"value"						=> "",
							"admin_label"       		=> true,
							"description"				=> __( "Enter a title to be used for the Hover effect.", "ts_visual_composer_extend" ),
							"group" 			        => "Content",
						),
						array(
							"type"              		=> "fontsmanager",
							"heading"           		=> __( "Title: Font Family", "ts_visual_composer_extend" ),
							"param_name"        		=> "font_titlefamily",
							"value"             		=> "",
							"default"					=> "true",
							"connector"					=> "font_titletype",
							"dependency"        		=> array( 'element' => "title_text", 'not_empty' => true ),
							"group"						=> "Content",
						),
						array(
							"type"              		=> "hidden_input",
							"param_name"        		=> "font_titletype",
							"value"             		=> "",
							"dependency"        		=> array( 'element' => "title_text", 'not_empty' => true ),
							"group"						=> "Content",
						),
						array(
							"type"             	 		=> "switch_button",
							"heading"               	=> __( "Title: Single Line", "ts_visual_composer_extend" ),
							"param_name"            	=> "title_singleline",
							"value"                 	=> "true",
							"description"       		=> __( "Use the toggle to switch between a forced single line for the title, or allowing for a line and word break, if necessary.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"        		=> array( 'element' => "title_text", 'not_empty' => true ),
							"group" 			        => "Content",
						),
						array(
							"type"                  	=> "colorpicker",
							"heading"              	 	=> __( "Title: Color", "ts_visual_composer_extend" ),
							"param_name"            	=> "title_color",
							"value"                 	=> "#ffffff",
							"description"           	=> __( "Define the color for the title text.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"        		=> array( 'element' => "title_text", 'not_empty' => true ),
							"group" 			        => "Content",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Title: Size", "ts_visual_composer_extend" ),
							"param_name"                => "title_size",
							"value"                     => "30",
							"min"                       => "10",
							"max"                       => "60",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define the font size for the title text.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "title_text", 'not_empty' => true ),
							"group" 			        => "Content",
						),						
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_3",
							"seperator"					=> "Hover Message",
							"group" 			        => "Content",
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Allow HTML Code", "ts_visual_composer_extend" ),
							"param_name"        		=> "content_code",
							"value"             		=> "false",
							"description"       		=> __( "Switch the toggle to allow for HTML code to create the overlay content.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "effect_style_type", 'value' => 'text' ),
							"group" 					=> "Content",
						),
						array(
							"type"                  	=> "textarea",
							"heading"               	=> __( "Message", "ts_visual_composer_extend" ),
							"param_name"            	=> "content_text",
							"value"                 	=> "",
							"description"	        	=> __( "Enter a short message to be used for the Hover effect; HTML code can NOT be used.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "content_code", 'value' => 'false' ),
							"group" 					=> "Content",
						),
						array(
							"type"              		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorBase64TinyMCE == "true" ? "wysiwyg_base64" : "textarea_raw_html"),
							"heading"           		=> __( "Message", "ts_visual_composer_extend" ),
							"param_name"        		=> "content_html",
							"minimal"					=> "true",
							"value"             		=> base64_encode(""),
							"description"       		=> __( "Enter a short message to be used for the Hover effect; basic HTML code CAN be used.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "content_code", 'value' => 'true' ),
							"group" 					=> "Content",
						),
						array(
							"type"              		=> "fontsmanager",
							"heading"           		=> __( "Message: Font Family", "ts_visual_composer_extend" ),
							"param_name"        		=> "font_textfamily1",
							"value"             		=> "",
							"default"					=> "true",
							"connector"					=> "font_texttype1",
							"dependency"            	=> array( 'element' => "effect_style_type", 'value' => 'text' ),
							"group"						=> "Content",
						),
						array(
							"type"              		=> "hidden_input",
							"param_name"        		=> "font_texttype1",
							"value"             		=> "",
							"dependency"            	=> array( 'element' => "effect_style_type", 'value' => 'text' ),
							"group"						=> "Content",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Message: Size", "ts_visual_composer_extend" ),
							"param_name"                => "content_size",
							"value"                     => "16",
							"min"                       => "10",
							"max"                       => "32",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define the font size for the message text.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "effect_style_type", 'value' => 'text' ),
							"group" 			        => "Content",
						),
						array(
							"type"                  	=> "colorpicker",
							"heading"              	 	=> __( "Message: Color", "ts_visual_composer_extend" ),
							"param_name"            	=> "content_color_text",
							"value"                 	=> "#ffffff",
							"description"           	=> __( "Define the color for the content text.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "effect_style_type", 'value' => 'text' ),
							"group" 			        => "Content",
						),
						array(
							"type"                  	=> "textarea",
							"heading"               	=> __( "Message", "ts_visual_composer_extend" ),
							"param_name"            	=> "content_icons",
							"value"                 	=> "",
							"description"	        	=> __( "Enter a short message to be used for the Hover effect; HTML code can NOT be used.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "effect_style_icons", 'value' => 'ts-hover-effect-zoe' ),
							"group" 					=> "Content",
						),
						array(
							"type"              		=> "fontsmanager",
							"heading"           		=> __( "Message: Font Family", "ts_visual_composer_extend" ),
							"param_name"        		=> "font_textfamily2",
							"value"             		=> "",
							"default"					=> "true",
							"connector"					=> "font_texttype2",
							"dependency"            	=> array( 'element' => "effect_style_icons", 'value' => 'ts-hover-effect-zoe' ),
							"group"						=> "Content",
						),
						array(
							"type"              		=> "hidden_input",
							"param_name"        		=> "font_texttype2",
							"value"             		=> "",
							"dependency"            	=> array( 'element' => "effect_style_icons", 'value' => 'ts-hover-effect-zoe' ),
							"group"						=> "Content",
						),
						array(
							"type"                  	=> "colorpicker",
							"heading"              	 	=> __( "Message: Color", "ts_visual_composer_extend" ),
							"param_name"            	=> "content_color_other",
							"value"                 	=> "#ffffff",
							"description"           	=> __( "Define the color for the content text.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "effect_style_icons", 'value' => 'ts-hover-effect-zoe' ),
							"group" 			        => "Content",
						),
						// Hover Icons
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_4",
							"seperator"					=> "Hover Icons",
							"dependency"            	=> array( 'element' => "effect_style_type", 'value' => 'icons' ),
							"group" 			        => "Content",
						),
						array(
							"type"                  	=> "colorpicker",
							"heading"              	 	=> __( "Icons Color", "ts_visual_composer_extend" ),
							"param_name"            	=> "content_color_icons",
							"value"                 	=> "#000000",
							"description"           	=> __( "Define the color for the content icons.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "effect_style_type", 'value' => 'icons' ),
							"group" 			        => "Content",
						),
						array(
							"type" 						=> "icons_panel",
							"heading" 					=> __( 'Icon #1', 'ts_visual_composer_extend' ),
							"param_name" 				=> 'content_link1_icon',
							"value"						=> "",
							"settings" 					=> array(
								"emptyIcon" 					=> true,
								"emptyIconValue"				=> 'transparent',
								"hasSearch"						=> false,
								"override"						=> true,
								"type" 							=> 'hovereffect',
							),
							"description"       		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorVisualSelector == "true" ? __( "Select the icon you want to display.", "ts_visual_composer_extend" ) : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconSelectorString),
							"dependency"            	=> array( 'element' => "effect_style_icons", 'value' => array("ts-hover-effect-zoe", "ts-hover-effect-hera", "ts-hover-effect-winston", "ts-hover-effect-terry", "ts-hover-effect-phoebe", "ts-hover-effect-kira") ),
							"group" 					=> "Content",
						),				
						array(
							"type"              		=> "textarea_raw_html",
							"heading"           		=> __( "Icon #1 Tooltip", "ts_visual_composer_extend" ),
							"param_name"        		=> "content_link1_tooltip",
							"value"             		=> base64_encode(""),
							"description"      	 		=> __( "Enter the tooltip for the icon here; basic HTML code can be used.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "effect_style_icons", 'value' => array("ts-hover-effect-zoe", "ts-hover-effect-hera", "ts-hover-effect-winston", "ts-hover-effect-terry", "ts-hover-effect-phoebe", "ts-hover-effect-kira") ),
							//"dependency"        		=> array( 'element' => "content_link1_icon", 'not_empty' => true ),
							"group" 					=> "Content",
						),
						array(
							"type" 						=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterLinkPicker['enabled'] == "false" ? "vc_link" : "advancedlinks"),
							"heading" 					=> __("Icon Link + Title #1", "ts_visual_composer_extend"),
							"param_name" 				=> "content_link1",
							"description" 				=> __("Provide a link to another site/page to be used for Icon #1.", "ts_visual_composer_extend"),
							"dependency"            	=> array( 'element' => "effect_style_icons", 'value' => array("ts-hover-effect-zoe", "ts-hover-effect-hera", "ts-hover-effect-winston", "ts-hover-effect-terry", "ts-hover-effect-phoebe", "ts-hover-effect-kira") ),
							//"dependency"        		=> array( 'element' => "content_link1_icon", 'not_empty' => true ),
							"group" 					=> "Content",
						),
						array(
							"type" 						=> "icons_panel",
							"heading" 					=> __( 'Icon #2', 'ts_visual_composer_extend' ),
							"param_name" 				=> 'content_link2_icon',
							"value"						=> "",
							"settings" 					=> array(
								"emptyIcon" 					=> true,
								"emptyIconValue"				=> 'transparent',
								"hasSearch"						=> false,
								"override"						=> true,
								"type" 							=> 'hovereffect',
							),
							"description"       		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorVisualSelector == "true" ? __( "Select the icon you want to display.", "ts_visual_composer_extend" ) : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconSelectorString),
							"dependency"            	=> array( 'element' => "effect_style_icons", 'value' => array("ts-hover-effect-zoe", "ts-hover-effect-hera", "ts-hover-effect-winston", "ts-hover-effect-terry", "ts-hover-effect-phoebe", "ts-hover-effect-kira") ),
							"group" 					=> "Content",
						),						
						array(
							"type"              		=> "textarea_raw_html",
							"heading"           		=> __( "Icon #2 Tooltip", "ts_visual_composer_extend" ),
							"param_name"        		=> "content_link2_tooltip",
							"value"             		=> base64_encode(""),
							"description"      	 		=> __( "Enter the tooltip for the icon here; basic HTML code can be used.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "effect_style_icons", 'value' => array("ts-hover-effect-zoe", "ts-hover-effect-hera", "ts-hover-effect-winston", "ts-hover-effect-terry", "ts-hover-effect-phoebe", "ts-hover-effect-kira") ),
							//"dependency"        		=> array( 'element' => "content_link2_icon", 'not_empty' => true ),
							"group" 					=> "Content",
						),
						array(
							"type" 						=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterLinkPicker['enabled'] == "false" ? "vc_link" : "advancedlinks"),
							"heading" 					=> __("Icon Link + Title #2", "ts_visual_composer_extend"),
							"param_name" 				=> "content_link2",
							"description" 				=> __("Provide a link to another site/page to be used for Icon #2.", "ts_visual_composer_extend"),
							"dependency"            	=> array( 'element' => "effect_style_icons", 'value' => array("ts-hover-effect-zoe", "ts-hover-effect-hera", "ts-hover-effect-winston", "ts-hover-effect-terry", "ts-hover-effect-phoebe", "ts-hover-effect-kira") ),
							//"dependency"        		=> array( 'element' => "content_link2_icon", 'not_empty' => true ),
							"group" 					=> "Content",
						),
						array(
							"type" 						=> "icons_panel",
							"heading" 					=> __( 'Icon #3', 'ts_visual_composer_extend' ),
							"param_name" 				=> 'content_link3_icon',
							"value"						=> "",
							"settings" 					=> array(
								"emptyIcon" 					=> true,
								"emptyIconValue"				=> 'transparent',
								"hasSearch"						=> false,
								"override"						=> true,
								"type" 							=> 'hovereffect',
							),
							"description"       		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorVisualSelector == "true" ? __( "Select the icon you want to display.", "ts_visual_composer_extend" ) : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconSelectorString),
							"dependency"            	=> array( 'element' => "effect_style_icons", 'value' => array("ts-hover-effect-zoe", "ts-hover-effect-hera", "ts-hover-effect-winston", "ts-hover-effect-terry", "ts-hover-effect-phoebe", "ts-hover-effect-kira") ),
							"group" 					=> "Content",
						),						
						array(
							"type"              		=> "textarea_raw_html",
							"heading"           		=> __( "Icon #3 Tooltip", "ts_visual_composer_extend" ),
							"param_name"        		=> "content_link3_tooltip",
							"value"             		=> base64_encode(""),
							"description"      	 		=> __( "Enter the tooltip for the icon here; basic HTML code can be used.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "effect_style_icons", 'value' => array("ts-hover-effect-zoe", "ts-hover-effect-hera", "ts-hover-effect-winston", "ts-hover-effect-terry", "ts-hover-effect-phoebe", "ts-hover-effect-kira") ),
							//"dependency"        		=> array( 'element' => "content_link3_icon", 'not_empty' => true ),
							"group" 					=> "Content",
						),
						array(
							"type" 						=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterLinkPicker['enabled'] == "false" ? "vc_link" : "advancedlinks"),
							"heading" 					=> __("Icon Link + Title #3", "ts_visual_composer_extend"),
							"param_name" 				=> "content_link3",
							"description" 				=> __("Provide a link to another site/page to be used for Icon #3.", "ts_visual_composer_extend"),
							"dependency"            	=> array( 'element' => "effect_style_icons", 'value' => array("ts-hover-effect-zoe", "ts-hover-effect-hera", "ts-hover-effect-winston", "ts-hover-effect-terry", "ts-hover-effect-phoebe", "ts-hover-effect-kira") ),
							//"dependency"        		=> array( 'element' => "content_link3_icon", 'not_empty' => true ),
							"group" 					=> "Content",
						),
						array(
							"type" 						=> "icons_panel",
							"heading" 					=> __( 'Icon #4', 'ts_visual_composer_extend' ),
							"param_name" 				=> 'content_link4_icon',
							"value"						=> "",
							"settings" 					=> array(
								"emptyIcon" 					=> true,
								"emptyIconValue"				=> 'transparent',
								"hasSearch"						=> false,
								"override"						=> true,
								"type" 							=> 'hovereffect',
							),
							"description"       		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorVisualSelector == "true" ? __( "Select the icon you want to display.", "ts_visual_composer_extend" ) : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconSelectorString),
							"dependency"            	=> array( 'element' => "effect_style_icons", 'value' => array("ts-hover-effect-hera", "ts-hover-effect-terry", "ts-hover-effect-kira") ),
							"group" 					=> "Content",
						),						
						array(
							"type"              		=> "textarea_raw_html",
							"heading"           		=> __( "Icon #4 Tooltip", "ts_visual_composer_extend" ),
							"param_name"        		=> "content_link4_tooltip",
							"value"             		=> base64_encode(""),
							"description"      	 		=> __( "Enter the tooltip for the icon here; basic HTML code can be used.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "effect_style_icons", 'value' => array("ts-hover-effect-hera", "ts-hover-effect-terry", "ts-hover-effect-kira") ),
							//"dependency"        		=> array( 'element' => "content_link4_icon", 'not_empty' => true ),
							"group" 					=> "Content",
						),
						array(
							"type" 						=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterLinkPicker['enabled'] == "false" ? "vc_link" : "advancedlinks"),
							"heading" 					=> __("Icon Link + Title #4", "ts_visual_composer_extend"),
							"param_name" 				=> "content_link4",
							"description" 				=> __("Provide a link to another site/page to be used for Icon #4.", "ts_visual_composer_extend"),
							"dependency"            	=> array( 'element' => "effect_style_icons", 'value' => array("ts-hover-effect-hera", "ts-hover-effect-terry", "ts-hover-effect-kira") ),
							//"dependency"        		=> array( 'element' => "content_link4_icon", 'not_empty' => true ),
							"group" 					=> "Content",
						),
						// Hover Indicator
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_5",
							"seperator"					=> "Hover Indicator",
							"group" 			        => "Content",
						),
						array(
							"type"             	 		=> "switch_button",
							"heading"               	=> __( "Show Overlay Handle", "ts_visual_composer_extend" ),
							"param_name"            	=> "overlay_handle_show",
							"value"                 	=> "true",
							"description"       		=> __( "Use the toggle to show or hide a handle button below the image.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "effect_permanent", 'value' => 'false' ),
							"group" 			        => "Content",
						),
						array(
							"type"                  	=> "colorpicker",
							"heading"              	 	=> __( "Handle Color", "ts_visual_composer_extend" ),
							"param_name"            	=> "overlay_handle_color",
							"value"                 	=> "#0094FF",
							"description"           	=> __( "Define the color for the overlay handle button.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "overlay_handle_show", 'value' => 'true' ),
							"group" 			        => "Content",
						),
						// Click Events
						array(
							"type"				    	=> "seperator",
							"param_name"		    	=> "seperator_6",
							"seperator"					=> "Click Event",
							"dependency"            	=> array( 'element' => "effect_style_type", 'value' => 'text' ),
							"group" 					=> "Event",
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Click Event", "ts_visual_composer_extend" ),
							"param_name"            	=> "hover_event",
							"width"                 	=> 150,
							"value" 					=> array(
								__( "None", "ts_visual_composer_extend" )									=> "none",
								__( "Open Current Image in Lightbox", "ts_visual_composer_extend" )			=> "image",
								__( "Open Other Image in Lightbox", "ts_visual_composer_extend" )			=> "other",
								__( "Open Popup in Lightbox", "ts_visual_composer_extend" )					=> "popup",
								__( "Open YouTube Video in Lightbox", "ts_visual_composer_extend" )			=> "youtube",
								__( "Open Vimeo Video in Lightbox", "ts_visual_composer_extend" )			=> "vimeo",
								__( "Open DailyMotion Video in Lightbox", "ts_visual_composer_extend" )		=> "dailymotion",
								__( "Open Page in iFrame", "ts_visual_composer_extend" )					=> "iframe",
								__( "Simple Link to Page", "ts_visual_composer_extend" )					=> "link",
							),
							"description"           	=> __( "Select if the Hover image should trigger any other action.", "ts_visual_composer_extend" ),
							"admin_label"       		=> true,
							"dependency"            	=> array( 'element' => "effect_style_type", 'value' => 'text' ),
							"group" 					=> "Event",
						),
						// Lightbox Image
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Lightbox Image Source", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_size",
							"width"                 	=> 150,
							"value"                 	=> array(
								__( 'Full Size Image', "ts_visual_composer_extend" )			=> "full",
								__( 'Large Size Image', "ts_visual_composer_extend" )			=> "large",
								__( 'Medium Size Image', "ts_visual_composer_extend" )			=> "medium",
							),
							"description"           	=> __( "Select which image size based on WordPress settings should be used for the lightbox image.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "hover_event", 'value' => array('image', 'other') ),
							"group" 					=> "Event",
						),
						// Other Image
						array(
							"type"                  	=> "attach_image",
							"heading"               	=> __( "Image", "ts_visual_composer_extend" ),
							"param_name"            	=> "hover_other",
							"value"                 	=> "",
							"description"           	=> __( "Select the image you want to show in the lightbox.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "hover_event", 'value' => 'other' ),
							"group" 					=> "Event",
						),			
						// Modal Popup
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Show Hover Title", "ts_visual_composer_extend" ),
							"param_name"		    	=> "hover_show_title",
							"value"                 	=> "true",
							"description"		    	=> __( "Switch the toggle if you want to show the title in the modal popup.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "hover_event", 'value' => 'popup' ),
							"group" 					=> "Event",
						),
						array(
							"type"              		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorBase64TinyMCE == "true" ? "wysiwyg_base64" : "textarea_raw_html"),
							"heading"           		=> __( "Hover Description", "ts_visual_composer_extend" ),
							"param_name"        		=> "hover_text",
							"value"             		=> base64_encode(""),
							"description"       		=> __( "Enter the more detailed description for the modal popup; HTML code can be used.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "hover_event", 'value' => 'popup' ),
							"group" 					=> "Event",
						),
						// YouTube / DailyMotion / Vimeo
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Video URL", "ts_visual_composer_extend" ),
							"param_name"            	=> "hover_video_link",
							"value"                 	=> "",
							"description"           	=> __( "Enter the URL for the video to be shown in a lightbox.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "hover_event", 'value' => array('youtube','dailymotion','vimeo') ),
							"group" 					=> "Event",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Show Related Videos", "ts_visual_composer_extend" ),
							"param_name"		    	=> "hover_video_related",
							"value"             		=> "false",
							"description"		    	=> __( "Switch the toggle if you want to show related videos once the video has finished playing.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "hover_event", 'value' => 'youtube' ),
							"group" 					=> "Event",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Autoplay Video", "ts_visual_composer_extend" ),
							"param_name"		    	=> "hover_video_auto",
							"value"             		=> "true",
							"description"		    	=> __( "Switch the toggle if you want to auto-play the video once opened in the lightbox.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "hover_event", 'value' => array('youtube','dailymotion','vimeo') ),
							"group" 					=> "Event",
						),
						// Link / iFrame
						array(
							"type" 						=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterLinkPicker['enabled'] == "false" ? "vc_link" : "advancedlinks"),
							"heading" 					=> __("Link + Title", "ts_visual_composer_extend"),
							"param_name" 				=> "hover_link",
							"description" 				=> __("Provide a link to another site/page to be used for the Hover event.", "ts_visual_composer_extend"),
							"dependency"            	=> array( 'element' => "hover_event", 'value' => array('iframe','link') ),
							"group" 					=> "Event",
						),
						// Lightbox Settings
						array(
							"type"						=> "seperator",
							"param_name"				=> "seperator_7",
							"seperator"					=> "Lightbox Settings",
							"dependency"            	=> array( 'element' => "hover_event", 'value' => array('image','other','popup','youtube','dailymotion','vimeo','iframe') ),
							"group" 					=> "Lightbox",
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Transition Effect", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_effect",
							"width"                 	=> 150,
							"value"                 	=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Animations,
							"default" 					=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LightboxDefaultAnimation,
							"std" 						=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LightboxDefaultAnimation,
							"description"           	=> __( "Select the transition effect to be used for each image in the lightbox.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "hover_event", 'value' => array('image','other','popup','youtube','dailymotion','vimeo','iframe') ),
							"group" 					=> "Lightbox",
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Backlight Effect", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_backlight",
							"width"                 	=> 150,
							"value"                 	=> array(
								__( 'Auto Color', "ts_visual_composer_extend" )					=> "auto",
								__( 'Custom Color', "ts_visual_composer_extend" )				=> "custom",
								__( 'Transparent Backlight', "ts_visual_composer_extend" )     	=> "hideit",
							),
							"description"           	=> __( "Select the backlight effect for the gallery images.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "hover_event", 'value' => array('image','other','popup','youtube','dailymotion','vimeo','iframe') ),
							"group" 					=> "Lightbox",
						),
						array(
							"type"                  	=> "colorpicker",
							"heading"               	=> __( "Custom Backlight Color", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_backlight_color",
							"value"                 	=> "#ffffff",
							"description"           	=> __( "Define the backlight color for the gallery images.", "ts_visual_composer_extend" ),
							"dependency"           	 	=> array( 'element' => "lightbox_backlight", 'value' => 'custom' ),
							"group" 					=> "Lightbox",
						),						
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Maximum Lightbox Width", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_width",
							"width"                 	=> 150,
							"value"                 	=> array(
								__( 'Auto', "ts_visual_composer_extend" )                 	=> "auto",
								__( 'Set Width (%)', "ts_visual_composer_extend" )        	=> "widthpercent",
								__( 'Set Width (px)', "ts_visual_composer_extend" )       	=> "widthpixel",
							),
							"description"           	=> __( "Select how the maximum element width inside the lightbox should be determined.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "hover_event", 'value' => array('image','other','popup','youtube','dailymotion','vimeo','iframe') ),
							"group" 					=> "Lightbox",
						),
						array(
							"type"                  	=> "nouislider",
							"heading"               	=> __( "Maximum Lightbox Width", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_width_percent",
							"value"                 	=> "100",
							"min"                   	=> "25",
							"max"                   	=> "100",
							"step"                  	=> "1",
							"unit"                  	=> '%',
							"description"           	=> __( "Select the maximum element width inside the lightbox in percent.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "lightbox_width", 'value' => 'widthpercent' ),
							"group" 					=> "Lightbox",
						),
						array(
							"type"                  	=> "nouislider",
							"heading"               	=> __( "Maximum Lightbox Width", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_width_pixel",
							"value"                 	=> "960",
							"min"                   	=> "1",
							"max"                   	=> "1920",
							"step"                  	=> "1",
							"unit"                  	=> 'px',
							"description"           	=> __( "Select the maximum element width inside the lightbox in px.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "lightbox_width", 'value' => 'widthpixel' ),
							"group" 					=> "Lightbox",
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Maximum Lightbox Height", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_height",
							"width"                 	=> 150,
							"value"                 	=> array(
								__( 'Auto', "ts_visual_composer_extend" )                 	=> "auto",
								__( 'Set Height (%)', "ts_visual_composer_extend" )      	=> "heightpercent",
								__( 'Set Height (px)', "ts_visual_composer_extend" )      	=> "heightpixel",
							),
							"description"           	=> __( "Select how the maximum element height inside the lightbox should be determined.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "hover_event", 'value' => array('image','other','popup','youtube','dailymotion','vimeo','iframe') ),
							"group" 					=> "Lightbox",
						),
						array(
							"type"                  	=> "nouislider",
							"heading"               	=> __( "Maximum Lightbox Height", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_height_percent",
							"value"                 	=> "100",
							"min"                   	=> "25",
							"max"                   	=> "100",
							"step"                  	=> "1",
							"unit"                  	=> '%',
							"description"           	=> __( "Select the maximum element height inside the lightbox in px.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "lightbox_height", 'value' => 'heightpercent' ),
							"group" 					=> "Lightbox",
						),
						array(
							"type"                  	=> "nouislider",
							"heading"               	=> __( "Maximum Lightbox Height", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_height_pixel",
							"value"                 	=> "540",
							"min"                   	=> "100",
							"max"                   	=> "1080",
							"step"                  	=> "1",
							"unit"                  	=> 'px',
							"description"           	=> __( "Select the maximum element height inside the lightbox in px.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "lightbox_height", 'value' => 'heightpixel' ),
							"group" 					=> "Lightbox",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Group Name", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_group_name",
							"value"                 	=> "nachogroup",
							"description"           	=> __( "Enter a custom group name to manually build group with other non-gallery items; leave empty for non-grouping", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "hover_event", 'value' => array('image','other','popup','youtube','dailymotion','vimeo','iframe') ),
							"group" 					=> "Lightbox",
						),	
						// Tooltip Settings
						array(
							"type"						=> "seperator",
							"param_name"				=> "seperator_8",
							"seperator"					=> "Tooltip Settings",
							"group" 					=> "Tooltip",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Use HTML in Tooltip", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tooltip_html",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want to allow basic HTML code for the tooltip content.", "ts_visual_composer_extend" ),
							"group" 					=> "Tooltip",
						),
						array(
							"type"						=> "textarea",
							"heading"					=> __( "Tooltip Content", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltip_content",
							"scope"						=> array(
								"menubar"				=> "false",
								"media"					=> "false",
								"link"					=> "false",
								"blockquote"			=> "false",
								"lists"					=> "false",
								"background"			=> "false",
								"height"				=> 150,
							),
							"value"						=> "",
							"description"		    	=> __( "Enter the tooltip content here (do not use quotation marks or HTML code).", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "tooltip_html", 'value' => 'false' ),
							"group" 					=> "Tooltip",
						),
						array(
							"type"              		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorBase64TinyMCE == "true" ? "wysiwyg_base64" : "textarea_raw_html"),
							"heading"           		=> __( "Tooltip Content", "ts_visual_composer_extend" ),
							"param_name"        		=> "tooltip_content_html",
							"minimal"					=> "true",
							"value"             		=> base64_encode(""),
							"description"      	 		=> __( "Enter the tooltip content here; HTML code can be used.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "tooltip_html", 'value' => 'true' ),
							"group" 					=> "Tooltip",
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Tooltip Animation", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tooltip_animation",
							"value"                 	=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Animations,
							"description"		    	=> __( "Select how the tooltip entry and exit should be animated once triggered.", "ts_visual_composer_extend" ),
							"group" 					=> "Tooltip",
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Tooltip Position", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tooltip_position",
							"value"                 	=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Vertical,
							"description"		    	=> __( "Select the tooltip position in relation to the element.", "ts_visual_composer_extend" ),
							"group" 					=> "Tooltip",
						),
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Tooltip Style", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltip_style",
							"value"             		=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Layouts,
							"description"				=> __( "Select the tooltip style.", "ts_visual_composer_extend" ),
							"group" 					=> "Tooltip",
						),
						array(
							"type"						=> "nouislider",
							"heading"					=> __( "Tooltip X-Offset", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltipster_offsetx",
							"value"						=> "0",
							"min"						=> "-100",
							"max"						=> "100",
							"step"						=> "1",
							"unit"						=> 'px',
							"description"				=> __( "Define an optional X-Offset for the tooltip position.", "ts_visual_composer_extend" ),
							"group" 					=> "Tooltip",
						),
						array(
							"type"						=> "nouislider",
							"heading"					=> __( "Tooltip Y-Offset", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltipster_offsety",
							"value"						=> "0",
							"min"						=> "-100",
							"max"						=> "100",
							"step"						=> "1",
							"unit"						=> 'px',
							"description"				=> __( "Define an optional Y-Offset for the tooltip position.", "ts_visual_composer_extend" ),
							"group" 					=> "Tooltip",
						),	
						// Other Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_9",
							"seperator"					=> "Other Settings",
							"group" 			        => "Other Settings",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Margin: Top", "ts_visual_composer_extend" ),
							"param_name"                => "margin_top",
							"value"                     => "0",
							"min"                       => "-50",
							"max"                       => "500",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Select the top margin for the element.", "ts_visual_composer_extend" ),
							"group" 			        => "Other Settings",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Margin: Bottom", "ts_visual_composer_extend" ),
							"param_name"                => "margin_bottom",
							"value"                     => "0",
							"min"                       => "-50",
							"max"                       => "500",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Select the bottom margin for the element.", "ts_visual_composer_extend" ),
							"group" 			        => "Other Settings",
						),
						array(
							"type"                      => "textfield",
							"heading"                   => __( "Define ID Name", "ts_visual_composer_extend" ),
							"param_name"                => "el_id",
							"value"                     => "",
							"description"               => __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
							"group" 			        => "Other Settings",
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
		}
	}
	// Register Container and Child Shortcode with Visual Composer
	if ((class_exists('WPBakeryShortCode')) && (!class_exists('WPBakeryShortCode_TS_Image_Hover_Effects'))) {
		class WPBakeryShortCode_TS_Image_Hover_Effects extends WPBakeryShortCode {};
	}
	// Initialize "TS Image Hover Effects" Class
	if (class_exists('TS_Image_Hover_Effects')) {
		$TS_Image_Hover_Effects = new TS_Image_Hover_Effects;
	}
?>