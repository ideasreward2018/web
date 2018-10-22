<?php
	if (!class_exists('TS_Image_IHover')){
		class TS_Image_IHover {
			function __construct() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						$this->TS_VCSC_Add_Image_IHover_Lean();
					} else if (function_exists('vc_map')) {
						add_action('init',                                  array($this, 'TS_VCSC_Add_Image_IHover_Elements'), 9999999);
					}
				} else {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_Image_IHover_Lean'), 9999999);
					} else if (function_exists('vc_map')) {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_Image_IHover_Elements'), 9999999);
					}
				}
				if ((is_admin() == false) || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAJAX == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAlways == "true")) {
					add_shortcode('TS_VCSC_Image_IHover',					array($this, 'TS_VCSC_Image_IHover_Function'));
				}
			}
			
			// Register Element(s) via LeanMap
			function TS_VCSC_Add_Image_IHover_Lean() {
				vc_lean_map('TS_VCSC_Image_IHover', 						array($this, 'TS_VCSC_Add_Image_IHover_Elements'), null);
			}
			
			// Image IHover
			function TS_VCSC_Image_IHover_Function ($atts, $content = null) {
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();
				
				// Check for Front End Editor
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					$ihover_frontent				= "true";
				} else {
					$ihover_frontent				= "false";
				}
				
				extract( shortcode_atts( array(
					'ihover_image'					=> '',
					'ihover_size'					=> 'medium',
					'ihover_responsive'				=> 'true',
					'ihover_truncate'				=> 'false',
					'ihover_style'					=> 'circle',
					'ihover_colored'				=> 'false',
					'ihover_background'				=> 'rgba(26, 74, 114, 0.6)',
					
					'size_type'						=> 'auto',
					'size_percent'					=> 100,
					'size_pixels'					=> 300,
					'size_align'					=> 'center',
					
					'content_wpautop'				=> 'false',
					
					'ihover_circle_effect'			=> 'effect1',
					'ihover_circle_border'			=> 10,
					'ihover_circle_color1'			=> '#ecab18',
					'ihover_circle_color2'			=> '#1ad280',
					'ihover_circle_direction'		=> 'left_to_right',
					'ihover_circle_direction2'		=> 'top_to_bottom',
					'ihover_circle_direction3'		=> 'from_left_and_right',
					'ihover_circle_direction4'		=> 'left_to_right',
					'ihover_circle_direction5'		=> '',
					'ihover_circle_scale'			=> 'scale_up',
					
					'ihover_square_effect'			=> 'effect1',
					'ihover_square_border'			=> 8,
					'ihover_square_color'			=> '#ffffff',
					'ihover_square_direction'		=> 'from_left_and_right',
					'ihover_square_direction2'		=> 'left_and_right',
					'ihover_square_direction3'		=> 'top_to_bottom',
					'ihover_square_direction4'		=> 'left_to_right',
					'ihover_square_direction5'		=> 'left_to_right',
					'ihover_square_scale'			=> 'scale_up',
					'ihover_square_shadow'			=> 'true',
					'ihover_title'					=> '',
					'ihover_content'				=> '',
	
					'ihover_event'					=> 'none',
					'ihover_show_title'				=> 'true',
					'ihover_link'					=> '',
					'ihover_text'					=> '',
					'ihover_other'					=> '',
					'ihover_link'					=> '',				
					'ihover_video_link'				=> '',
					'ihover_video_auto'				=> 'true',
					'ihover_video_related'			=> 'false',				
					
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
					'lightbox_effect'				=> 'random',
					'lightbox_speed'				=> 5000,
					'lightbox_social'				=> 'false',
					'lightbox_backlight_choice'		=> 'predefined',
					'lightbox_backlight_color'		=> '#0084E2',
					'lightbox_backlight_custom'		=> '#000000',
					
					'lightbox_custom_padding'		=> 15,
					'lightbox_custom_background'	=> 'none',
					'lightbox_background_image'		=> '',
					'lightbox_background_size'		=> 'cover',
					'lightbox_background_repeat'	=> 'no-repeat',
					'lightbox_background_color'		=> '#ffffff',
					
					'margin_top'					=> 20,
					'margin_bottom'					=> 20,
					'el_id' 						=> '',
					'el_class'              		=> '',
					'css'							=> '',
				), $atts ));
				
	
				if (($ihover_frontent == "false") && ($ihover_event != "none") && ($ihover_event != "link")) {
					wp_enqueue_script('ts-extend-krautlightbox');
					wp_enqueue_style('ts-extend-krautlightbox');
				}
				wp_enqueue_style('ts-extend-tooltipster');
				wp_enqueue_script('ts-extend-tooltipster');	
				wp_enqueue_style('ts-extend-ihover');
				wp_enqueue_script('ts-extend-ihover');
				wp_enqueue_script('ts-extend-badonkatrunc');
				wp_enqueue_style('ts-visual-composer-extend-front');
				wp_enqueue_script('ts-visual-composer-extend-front');
				
				$output                             = '';
				$linkstringStart					= '';
				$linkstringEnd						= '';
				$wpautop 							= ($content_wpautop == "true" ? true : false);
			
				if (!empty($el_id)) {
					$ihover_image_id				= $el_id;
				} else {
					$ihover_image_id				= 'ts-vcsc-ihover-image-' . mt_rand(999999, 9999999);
				}
				
				// Image
				if (!empty($ihover_image)) {
					if ($ihover_event == "image") {
						$ihover_image_link			= wp_get_attachment_image_src($ihover_image, $lightbox_size);
					}
					$ihover_image					= wp_get_attachment_image_src($ihover_image, $ihover_size);
				} else {
					$ihover_image_link				= array();
				}
				// Other Image
				if ($ihover_event == "other") {
					$ihover_image_link				= wp_get_attachment_image_src($ihover_other, $lightbox_size);
				}
				// iFrame / Link
				if (($ihover_event == "link") || ($ihover_event == "iframe")) {
					$link 							= TS_VCSC_Advancedlinks_GetLinkData($ihover_link);
					$a_href							= $link['url'];
					$a_title 						= ''; //$link['title'];
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
				if ($ihover_event == "youtube") {
					if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $ihover_video_link)) {
						$ihover_video_link			= $ihover_video_link;
					} else {
						$ihover_video_link			= 'https://www.youtube.com/watch?v=' . $ihover_video_link;
					}
				}
				// DailyMotion Video
				if ($ihover_event == "dailymotion") {
					if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $ihover_video_link)) {
						$ihover_video_link			= $ihover_video_link;
					} else {			
						$ihover_video_link			= $ihover_video_link;
					}
				}
				
				// Backlight Color
				if ($lightbox_backlight_choice == "predefined") {
					$lightbox_backlight_selection	= $lightbox_backlight_color;
				} else {
					$lightbox_backlight_selection	= $lightbox_backlight_custom;
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
					if (($ihover_style == "circle") && ($ihover_circle_effect == "effect1")) {
						$overlay_padding			= "padding-bottom: " . (10 + $ihover_circle_border) . "px;";
					} else {
						$overlay_padding			= "padding-bottom: 10px;";
					}
					$switch_handle_adjust  			= "";
				} else {
					$overlay_padding				= "";
					$switch_handle_adjust  			= "";
				}
				
				// Handle Icon
				if ($ihover_event != "none") {
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
				
				if ($ihover_style == "circle") {
					$ihovereffect					= $ihover_circle_effect;
				} else {
					$ihovereffect					= $ihover_square_effect;
				}
				
				// Size Adjust Class
				if ($ihover_style == "circle") {
					$sizeadjust1 					= array ("effect1");
					$sizeadjust2 					= array ("effect2","effect3","effect4","effect5","effect6","effect7","effect8","effect9","effect10","effect11","effect12","effect13","effect14","effect15","effect16","effect17","effect18","effect19","effect20");			
					$sizeadjust3					= array();
					$sizeadjust4					= array();
				} else {
					$sizeadjust1					= array();
					$sizeadjust2					= array();
					$sizeadjust3					= array("effect1","effect2","effect3","effect5","effect6","effect7","effect8","effect9","effect10","effect11","effect12","effect13","effect14","effect15");
					$sizeadjust4					= array("effect4");
				}
				if (($ihover_responsive == "true") && (in_array($ihovereffect, $sizeadjust1))) {
					$sizeadjustclass				= 'ts-ihover-image-sizeadjust1';
				} else if (($ihover_responsive == "true") && (in_array($ihovereffect, $sizeadjust2))) {
					$sizeadjustclass				= 'ts-ihover-image-sizeadjust2';
				} else if (($ihover_responsive == "true") && (in_array($ihovereffect, $sizeadjust3))) {
					$sizeadjustclass				= 'ts-ihover-image-sizeadjust3';
				} else if (($ihover_responsive == "true") && (in_array($ihovereffect, $sizeadjust4))) {
					$sizeadjustclass				= 'ts-ihover-image-sizeadjust4';
				} else {
					$sizeadjustclass				= '';
				}
				
				// Direction Version
				if ($ihover_style == "circle") {
					$ihoverdirection1				= array("effect1","effect5","effect17","effect19");
					$ihoverdirection2				= array("effect2","effect3","effect4","effect7","effect8","effect9","effect11","effect12","effect14","effect18");
					$ihoverdirection3				= array("effect6");
					$ihoverdirection4				= array("effect10","effect20");
					$ihoverdirection5				= array("effect13");
					$ihoverdirection6				= array("effect15","effect16");
				} else {
					$ihoverdirection1				= array("effect6");
					$ihoverdirection2				= array("effect1");
					$ihoverdirection3				= array("effect3");
					$ihoverdirection4				= array("effect5");
					$ihoverdirection5				= array("effect8");
					$ihoverdirection6				= array("effect9","effect10","effect11","effect12","effect13","effect14","effect15");
				}
				if ($ihover_style == "circle") {
					if (in_array($ihover_circle_effect, $ihoverdirection1)) {
						$ihover_direction			= '';
					} else if (in_array($ihover_circle_effect, $ihoverdirection2)) {
						$ihover_direction			= $ihover_circle_direction;
					} else if (in_array($ihover_circle_effect, $ihoverdirection3)) {
						$ihover_direction			= $ihover_circle_scale;
					} else if (in_array($ihover_circle_effect, $ihoverdirection4)) {
						$ihover_direction			= $ihover_circle_direction2;
					} else if (in_array($ihover_circle_effect, $ihoverdirection5)) {
						$ihover_direction			= $ihover_circle_direction3;
					} else if (in_array($ihover_circle_effect, $ihoverdirection6)) {
						$ihover_direction			= $ihover_circle_direction4;
					} else {
						$ihover_direction			= '';
					}
				} else {
					if (in_array($ihover_circle_effect, $ihoverdirection1)) {
						$ihover_direction			= '';
					} else if (in_array($ihover_square_effect, $ihoverdirection1)) {
						$ihover_direction			= $ihover_square_direction;
					} else if (in_array($ihover_square_effect, $ihoverdirection2)) {
						$ihover_direction			= $ihover_square_direction2;
					} else if (in_array($ihover_square_effect, $ihoverdirection3)) {
						$ihover_direction			= $ihover_square_direction3;
					} else if (in_array($ihover_square_effect, $ihoverdirection4)) {
						$ihover_direction			= $ihover_square_direction4;
					} else if (in_array($ihover_square_effect, $ihoverdirection5)) {
						$ihover_direction			= $ihover_square_scale;
					} else if (in_array($ihover_square_effect, $ihoverdirection6)) {
						$ihover_direction			= $ihover_square_direction5;
					} else {
						$ihover_direction			= '';
					}
				}
				
				if ($ihover_style == "circle") {
					$ihoverborder_width				= '';
					$ihoverborder_data				= '';
				} else {
					$ihoverborder_width				= 'border: ' . $ihover_square_border . 'px solid ' . $ihover_square_color . ';';
					$ihoverborder_data				= 'data-border="' . $ihover_square_border . '"';
				}
				
				// Output Version
				if ($ihover_style == "circle") {
					$ihoveroutput1					= array("effect1","effect5","effect18","effect20");
					$ihoveroutput2					= array("effect2","effect3","effect4","effect6","effect7","effect9","effect10","effect11","effect12","effect13","effect14","effect15","effect16","effect17","effect19");
					$ihoveroutput3					= array("effect8");
				} else {
					$ihoveroutput1					= array("effect9");
					$ihoveroutput2					= array("effect1","effect2","effect3","effect4","effect5","effect6","effect7","effect8","effect10","effect11","effect12","effect13","effect14","effect15");
					$ihoveroutput3					= array();
				}
				
				// Box Shadow
				if (($ihover_style == "square") && ($ihover_square_shadow == "true")) {
					$boxshadowclass					= "square-boxshadow";		
				} else {
					$boxshadowclass					= "";
				}
				
				// Link Output
				if ($ihover_frontent == "false") {
					if (($ihover_event != "none") && ($ihover_event == "popup")) {
						// Modal Popup
						$linkstringStart .= '<a id="' . $ihover_image_id . '-trigger" href="#' . $ihover_image_id . '" class="ts-ihover-image-link ' . $ihover_image_id . '-parent nch-holder nch-lightbox-modal no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $ihover_title . '" data-type="html" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
						$linkstringEnd	 .= '</a>';
					} else if (($ihover_event != "none") && ($ihover_event == "iframe")) {
						// iFrame Popup
						$linkstringStart .= '<a id="' . $ihover_image_id . '-trigger" href="' . $a_href . '" target="' . $a_target . '" class="ts-ihover-image-link ' . $ihover_image_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $ihover_title . '" data-type="iframe" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
						$linkstringEnd	 .= '</a>';
					} else if (($ihover_event != "none") && (($ihover_event == "image") || ($ihover_event == "other"))) {
						// (Other) Image Popup
						$linkstringStart .= '<a id="' . $ihover_image_id . '-trigger" href="' . $ihover_image_link[0] . '" class="ts-ihover-image-link ' . $ihover_image_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $ihover_title . '" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
						$linkstringEnd	 .= '</a>';
					} else if (($ihover_event != "none") && ($ihover_event == "youtube")) {
						// YouTube Popup
						$linkstringStart .= '<a id="' . $ihover_image_id . '-trigger" href="' . $ihover_video_link .'" class="ts-ihover-image-link ' . $ihover_image_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $ihover_title . '" data-related="' . $ihover_video_related .'" data-videoplay="' . $ihover_video_auto .'" data-type="youtube" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
						$linkstringEnd	 .= '</a>';
					} else if (($ihover_event != "none") && ($ihover_event == "vimeo")) {
						// Vimeo Popup
						$linkstringStart .= '<a id="' . $ihover_image_id . '-trigger" href="' . $ihover_video_link . '" class="ts-ihover-image-link ' . $ihover_image_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $ihover_title . '" data-videoplay="' . $ihover_video_auto . '" data-type="vimeo" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
						$linkstringEnd	 .= '</a>';
					} else if (($ihover_event != "none") && ($ihover_event == "dailymotion")) {
						// DailyMotion Popup
						$linkstringStart .= '<a id="' . $ihover_image_id . '-trigger" href="' . $ihover_video_link .'" class="ts-ihover-image-link ' . $ihover_image_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $ihover_title . '" data-videoplay="' . $ihover_video_auto . '" data-type="dailymotion" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
						$linkstringEnd	 .= '</a>';
					} else if (($ihover_event != "none") && ($ihover_event == "html5")) {
						// HTML5 Video Popup
						$linkstringStart .= '<a id="' . $ihover_image_id . '-trigger" href="#' . $modal_id . '" class="ts-ihover-image-link ' . $ihover_image_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $ihover_title . '" data-type="html" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
						$linkstringEnd	 .= '</a>';
					} else if (($ihover_event != "none") && ($ihover_event == "link")) {
						// Link Event
						$linkstringStart .= '<a id="' . $ihover_image_id . '-trigger" class="ts-ihover-image-link" href="' . $a_href . '" target="' . $a_target . '" title="' . $a_title . '">';
						$linkstringEnd	 .= '</a>';
					} else {
						// No Link Event
						$linkstringStart .= '<span id="' . $ihover_image_id . '-trigger" class="ts-ihover-image-link">';
						$linkstringEnd	 .= '</span>';
					}
				} else {
					$linkstringStart .= '<span id="' . $ihover_image_id . '-trigger" class="ts-ihover-image-link">';
					$linkstringEnd	 .= '</span>';
				}
				
				// Tooltip
				$tooltip_position					= TS_VCSC_TooltipMigratePosition($tooltip_position);
				$tooltip_style						= TS_VCSC_TooltipMigrateStyle($tooltip_style);	
				$tooltip_class						= 'ts-has-tooltipster-tooltip';		
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
				if (function_exists('wpb_js_remove_wpautop')){
					$ihover_content 			= wpb_js_remove_wpautop(do_shortcode($ihover_content), $wpautop);
				} else {
					$ihover_content 			= do_shortcode($content);
				}
				
				// Custom Background Color
				if ($ihover_colored == "true") {
					if ($ihover_style == "circle") {
						$backgroundtarget1			= array("effect1","effect2","effect3","effect4","effect6","effect7","effect8","effect9","effect10","effect11","effect12","effect13","effect14","effect15","effect16","effect17","effect19","effect21");
						if (in_array($ihover_circle_effect, $backgroundtarget1)) {
							$backgroundstyle1		= "background: " . $ihover_background . ";";
						} else {
							$backgroundstyle1		= "";
						}
						$backgroundtarget2			= array("effect5","effect18","effect20");
						if (in_array($ihover_circle_effect, $backgroundtarget2)) {
							$backgroundstyle2		= "background: " . $ihover_background . ";";
						} else {
							$backgroundstyle2		= "";
						}
					} else {
						$backgroundtarget1			= array("effect1","effect2","effect3","effect4","effect5","effect6","effect7","effect8","effect10","effect11","effect12","effect13","effect14","effect15");
						if (in_array($ihover_square_effect, $backgroundtarget1)) {
							$backgroundstyle1		= "background: " . $ihover_background . ";";
						} else {
							$backgroundstyle1		= "";
						}
						$backgroundtarget2			= array("effect9");
						if (in_array($ihover_square_effect, $backgroundtarget2)) {
							$backgroundstyle2		= "background: " . $ihover_background . ";";
						} else {
							$backgroundstyle2		= "";
						}
					}
				} else {
					$backgroundstyle1				= "";
					$backgroundstyle2				= "";
				}
				
				$output .= '<div class="ts-ihover-image-container ts-image-hover-frame" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $element_dimensions . '">';
					if ((strlen($tooltip_content_html) != 0) || (strlen($tooltip_content) != 0)) {
						$output .= '<div class="' . $Tooltip_Class . '" ' . $Tooltip_Content . ' style="width: 100%; height: 100%;">';
					}
						if ($overlay_handle_show == "true") {
							$output .= '<div class="" style="' . $overlay_padding . '">';
						}
							// Create Output Version 1
							if (in_array($ihovereffect, $ihoveroutput1)) {
								$output .= '<div class="ts-ihover-image-main ts-ihover-image-item ' . $ihover_style . ' ' . $boxshadowclass . ' ' . $ihovereffect . ' ' . $sizeadjustclass . ' ' . ($ihover_colored == "true" ? "colored" : "") . ' ' . $ihover_direction . ' ' . ($ihover_truncate == "true" ? "ts-ihover-image-truncated" : "") . '" ' . $ihoverborder_data . ' style="margin-bottom: ' . (($overlay_handle_show == "true") ? 20 : 0 ) . 'px; ' . $ihoverborder_width . '">';
									$output .= $linkstringStart;
										if (($ihover_style == "circle") && ($ihovereffect == "effect1")) {
											$output .= '<div class="spinner" data-border="' . $ihover_circle_border . '" style="border: ' . $ihover_circle_border . 'px solid ' . $ihover_circle_color1 . '; border-right-color: ' . $ihover_circle_color2 . '; border-bottom-color: ' . $ihover_circle_color2 . ';"></div>';
											$position = 'top: ' . $ihover_circle_border . 'px; left: ' . $ihover_circle_border . 'px;';
										} else {
											$position = '';
										}
										$output .= '<div class="ts-ihover-image-picture" style="' . (($ihover_circle_effect == "effect5" || $ihover_circle_effect == "effect18" || $ihover_circle_effect == "effect20") ? "width: 100%; height: 100%;" : "") . ' ' . $position . '"><img data-no-lazy="1" src="' . $ihover_image[0] . '" alt=""></div>';
										$output .= '<div class="ts-ihover-image-info" style="' . $backgroundstyle1 . ' ' . $position . '">';
											$output .= '<div class="ts-ihover-image-info-back" style="' . $backgroundstyle2 . '">';
												$output .= '<div class="ts-ihover-image-title" data-title="' . $ihover_title . '">' . $ihover_title . '</div>';
												$output .= '<div class="ts-ihover-image-content" data-content="' . $ihover_content . '">' . $ihover_content . '</div>';
											$output .= '</div>';
										$output .= '</div>';
									$output .= $linkstringEnd;
								$output .= '</div>';
							}
							// Create Output Version 2
							if (in_array($ihovereffect, $ihoveroutput2)) {
								$output .= '<div class="ts-ihover-image-main ts-ihover-image-item ' . $ihover_style . ' ' . $boxshadowclass . ' ' . $ihovereffect . ' ' . $sizeadjustclass . ' ' . ($ihover_colored == "true" ? "colored" : "") . ' ' . $ihover_direction . ' ' . ($ihover_truncate == "true" ? "ts-ihover-image-truncated" : "") . '" ' . $ihoverborder_data . ' style="margin-bottom: ' . (($overlay_handle_show == "true") ? 20 : 0 ) . 'px; ' . $ihoverborder_width . '">';
									$output .= $linkstringStart;
										$output .= '<div class="ts-ihover-image-picture" style="width: 100%; height: 100%;"><img data-no-lazy="1" src="' . $ihover_image[0] . '" alt=""></div>';
										if (($ihover_style == "square") && ($ihovereffect == "effect4")) {
											$output .= '<div class="mask1"></div>';
											$output .= '<div class="mask2"></div>';
										}
										$output .= '<div class="ts-ihover-image-info" style="' . $backgroundstyle1 . '">';
											$output .= '<div class="ts-ihover-image-title" data-title="' . $ihover_title . '">' . $ihover_title . '</div>';
											$output .= '<div class="ts-ihover-image-content" data-content="' . $ihover_content . '">' . $ihover_content . '</div>';
										$output .= '</div>';
									$output .= $linkstringEnd;
								$output .= '</div>';
							}
							// Create Output Version 3
							if (in_array($ihovereffect, $ihoveroutput3)) {
								$output .= '<div class="ts-ihover-image-main ts-ihover-image-item ' . $ihover_style . ' ' . $boxshadowclass . ' ' . $ihovereffect . ' ' . $sizeadjustclass . ' ' . ($ihover_colored == "true" ? "colored" : "") . ' ' . $ihover_direction . ' ' . ($ihover_truncate == "true" ? "ts-ihover-image-truncated" : "") . '" ' . $ihoverborder_data . ' style="margin-bottom: ' . (($overlay_handle_show == "true") ? 20 : 0 ) . 'px; ' . $ihoverborder_width . '">';
									$output .= $linkstringStart;
										$output .= '<div class="img-container" style="width: 100%; height: 100%;"><div class="ts-ihover-image-picture" style="width: 100%; height: 100%;"><img data-no-lazy="1" src="' . $ihover_image[0] . '" alt="" style=""></div></div>';
										$output .= '<div class="ts-ihover-image-info-container">';
											$output .= '<div class="ts-ihover-image-info" style="' . $backgroundstyle1 . '">';
												$output .= '<div class="ts-ihover-image-title" data-title="' . $ihover_title . '">' . $ihover_title . '</div>';
												$output .= '<div class="ts-ihover-image-content" data-content="' . $ihover_content . '">' . $ihover_content . '</div>';
											$output .= '</div>';
										$output .= '</div>';
									$output .= $linkstringEnd;
								$output .= '</div>';
							}
							// Overlay Handle
							if ($overlay_handle_show == "true") {
								$output .= '<div class="ts-image-hover-handle" style="' . $switch_handle_adjust . '"><span class="frame_' . $switch_handle_icon . '" style="background-color: ' . $overlay_handle_color . '"><i class="' . $switch_handle_icon . '"></i></span></div>';
							}
						if ($overlay_handle_show == "true") {
							$output .= '</div>';
						}
					if ((strlen($tooltip_content_html) != 0) || (strlen($tooltip_content) != 0)) {
						$output .= '</div>';
					}
					// Create hidden DIV with Modal Popup iHover Content
					if (($ihover_frontent == "false") && ($ihover_event == "popup")) {
						$output .= '<div id="' . $ihover_image_id . '" class="ts-modal-content nch-hide-if-javascript ' . $el_class . '" style="display: none; padding: ' . $lightbox_custom_padding . 'px; ' . $lightbox_background . '">';
							$output .= '<div class="ts-modal-white-header"></div>';
							$output .= '<div class="ts-modal-white-frame" style="">';
								$output .= '<div class="ts-modal-white-inner">';
									if (($ihover_show_title == "true") && ($ihover_title != "")) {
										$output .= '<h2 style="border-bottom: 1px solid #eeeeee; padding-bottom: 10px; margin-bottom: 10px;">' . $ihover_title . '</h2>';
									}
									$output .= rawurldecode(base64_decode(strip_tags($ihover_text)));
								$output .= '</div>';
							$output .= '</div>';
						$output .= '</div>';
					}
				$output .= '</div>';
				
				echo $output;
				
				unset($sizeadjust1);
				unset($sizeadjust2);
				unset($sizeadjust3);
				unset($sizeadjust4);
				unset($ihoverdirection1);
				unset($ihoverdirection2);
				unset($ihoverdirection3);
				unset($ihoverdirection4);
				unset($ihoverdirection5);
				unset($ihoverdirection6);
				unset($ihoveroutput1);
				unset($ihoveroutput2);
				unset($ihoveroutput3);
				
				$myvariable = ob_get_clean();
				return $myvariable;
			}
		
			// Add Image IHover Elements
			function TS_VCSC_Add_Image_IHover_Elements() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add IHover Element
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					"name"                              => __( "TS Image iHover", "ts_visual_composer_extend" ),
					"base"                              => "TS_VCSC_Image_IHover",
					"icon" 	                            => "ts-composer-element-icon-image-ihover",
					"category"                          => __( 'VC Extensions', "ts_visual_composer_extend" ),
					"description"                       => __("Place an image with iHover effect", "ts_visual_composer_extend"),
					"admin_enqueue_js"            		=> "",
					"admin_enqueue_css"           		=> "",
					"params"                            => array(
						// Style Selection
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_1",
							"seperator"					=> "iHover Selection",
						),
						array(
							"type"                  	=> "attach_image",
							"holder" 					=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorImagePreview == "true" ? "img" : ""),
							"heading"               	=> __( "Image", "ts_visual_composer_extend" ),
							"param_name"            	=> "ihover_image",
							"class"						=> "ts_vcsc_holder_image",
							"value"                 	=> "",
							"admin_label"       		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorImagePreview == "true" ? false : true),
							"description"           	=> __( "Select the image you want to use with the iHover effect.", "ts_visual_composer_extend" )
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Image Source", "ts_visual_composer_extend" ),
							"param_name"            	=> "ihover_size",
							"width"                 	=> 150,
							"value"                 	=> array(
								__( 'Medium Size Image', "ts_visual_composer_extend" )			=> "medium",
								__( 'Thumbnail Size Image', "ts_visual_composer_extend" )		=> "thumbnail",
								__( 'Large Size Image', "ts_visual_composer_extend" )			=> "large",
								__( 'Full Size Image', "ts_visual_composer_extend" )			=> "full",
							),
							"description"           	=> __( "Select which image size based on WordPress settings should be used for the preview image.", "ts_visual_composer_extend" ),
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Effect Style", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_style",
							"value"                 	=> array(
								__("Circle", "ts_visual_composer_extend")				=> "circle",
								__("Square", "ts_visual_composer_extend")				=> "square",
							),
							"description"		    	=> __( "Select the general style for the iHover effect.", "ts_visual_composer_extend" ),
							"admin_label"       		=> true,
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Effect Type", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_circle_effect",
							"value"                 	=> array(
								__("Effect 1", "ts_visual_composer_extend")				=> "effect1",
								__("Effect 2", "ts_visual_composer_extend")				=> "effect2",
								__("Effect 3", "ts_visual_composer_extend")				=> "effect3",
								__("Effect 4", "ts_visual_composer_extend")				=> "effect4",
								__("Effect 5", "ts_visual_composer_extend")				=> "effect5",
								__("Effect 6", "ts_visual_composer_extend")				=> "effect6",
								__("Effect 7", "ts_visual_composer_extend")				=> "effect7",
								__("Effect 8", "ts_visual_composer_extend")				=> "effect8",
								__("Effect 9", "ts_visual_composer_extend")				=> "effect9",
								__("Effect 10", "ts_visual_composer_extend")			=> "effect10",
								__("Effect 11", "ts_visual_composer_extend")			=> "effect11",
								__("Effect 12", "ts_visual_composer_extend")			=> "effect12",
								__("Effect 13", "ts_visual_composer_extend")			=> "effect13",
								__("Effect 14", "ts_visual_composer_extend")			=> "effect14",
								__("Effect 15", "ts_visual_composer_extend")			=> "effect15",
								__("Effect 16", "ts_visual_composer_extend")			=> "effect16",
								__("Effect 17", "ts_visual_composer_extend")			=> "effect17",
								__("Effect 18", "ts_visual_composer_extend")			=> "effect18",
								__("Effect 19", "ts_visual_composer_extend")			=> "effect19",
								__("Effect 20", "ts_visual_composer_extend")			=> "effect20",
							),
							"description"		    	=> __( "Select the iHover effect you want to apply to the image.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_style", 'value' => 'circle' ),
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Effect Direction", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_circle_direction",
							"value"                 	=> array(
								__("Left To Right", "ts_visual_composer_extend")		=> "left_to_right",
								__("Right To Left", "ts_visual_composer_extend")		=> "right_to_left",
								__("Top To Bottom", "ts_visual_composer_extend")		=> "top_to_bottom",
								__("Bottom To Top", "ts_visual_composer_extend")		=> "bottom_to_top",
							),
							"description"		    	=> __( "Select which direction the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_circle_effect", 'value' => array('effect2','effect3','effect4','effect7','effect8','effect9','effect11','effect12','effect14','effect18') ),
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Effect Scaling", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_circle_scale",
							"value"                 	=> array(
								__("Scale Up", "ts_visual_composer_extend")				=> "scale_up",
								__("Scale Down", "ts_visual_composer_extend")			=> "scale_down",
								__("Scale Down + Up", "ts_visual_composer_extend")		=> "scale_down_up",
							),
							"description"		    	=> __( "Select which scale type the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_circle_effect", 'value' => 'effect6' ),
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Effect Direction", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_circle_direction2",
							"value"                 	=> array(
								__("Top To Bottom", "ts_visual_composer_extend")		=> "top_to_bottom",
								__("Bottom To Top", "ts_visual_composer_extend")		=> "bottom_to_top",
							),
							"description"		    	=> __( "Select which direction the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_circle_effect", 'value' => array('effect10','effect20') ),
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Effect Direction", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_circle_direction3",
							"value"                 	=> array(
								__("From Left And Right", "ts_visual_composer_extend")	=> "from_left_and_right",
								__("Top To Bottom", "ts_visual_composer_extend")		=> "top_to_bottom",
								__("Bottom To Top", "ts_visual_composer_extend")		=> "bottom_to_top",
							),
							"description"		    	=> __( "Select which direction the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_circle_effect", 'value' => array('effect13') ),
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Effect Direction", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_circle_direction4",
							"value"                 	=> array(
								__("Left To Right", "ts_visual_composer_extend")		=> "left_to_right",
								__("Right To Left", "ts_visual_composer_extend")		=> "right_to_left",
							),
							"description"		    	=> __( "Select which direction the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_circle_effect", 'value' => array('effect15','effect16') ),
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Effect Type", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_square_effect",
							"value"                 	=> array(
								__("Effect 1", "ts_visual_composer_extend")				=> "effect1",
								__("Effect 2", "ts_visual_composer_extend")				=> "effect2",
								__("Effect 3", "ts_visual_composer_extend")				=> "effect3",
								__("Effect 4", "ts_visual_composer_extend")				=> "effect4",
								__("Effect 5", "ts_visual_composer_extend")				=> "effect5",
								__("Effect 6", "ts_visual_composer_extend")				=> "effect6",
								__("Effect 7", "ts_visual_composer_extend")				=> "effect7",
								__("Effect 8", "ts_visual_composer_extend")				=> "effect8",
								__("Effect 9", "ts_visual_composer_extend")				=> "effect9",
								__("Effect 10", "ts_visual_composer_extend")			=> "effect10",
								__("Effect 11", "ts_visual_composer_extend")			=> "effect11",
								__("Effect 12", "ts_visual_composer_extend")			=> "effect12",
								__("Effect 13", "ts_visual_composer_extend")			=> "effect13",
								__("Effect 14", "ts_visual_composer_extend")			=> "effect14",
								__("Effect 15", "ts_visual_composer_extend")			=> "effect15",
							),
							"description"		    	=> __( "Select the iHover effect you want to apply to the image.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_style", 'value' => 'square' ),
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Effect Direction", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_square_direction",
							"value"                 	=> array(
								__("From Left And Right", "ts_visual_composer_extend")	=> "from_left_and_right",
								__("From Top And Bottom", "ts_visual_composer_extend")	=> "from_top_and_bottom",
								__("Top To Bottom", "ts_visual_composer_extend")		=> "top_to_bottom",
								__("Bottom To Top", "ts_visual_composer_extend")		=> "bottom_to_top",
							),
							"description"		    	=> __( "Select which direction the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_square_effect", 'value' => array('effect6') ),
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Effect Direction", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_square_direction2",
							"value"                 	=> array(
								__("Left And Right", "ts_visual_composer_extend")		=> "left_and_right",
								__("Top To Bottom", "ts_visual_composer_extend")		=> "top_to_bottom",
								__("Bottom To Top", "ts_visual_composer_extend")		=> "bottom_to_top",
							),
							"description"		    	=> __( "Select which direction the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_square_effect", 'value' => array('effect1') ),
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Effect Direction", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_square_direction3",
							"value"                 	=> array(
								__("Top To Bottom", "ts_visual_composer_extend")		=> "top_to_bottom",
								__("Bottom To Top", "ts_visual_composer_extend")		=> "bottom_to_top",
							),
							"description"		    	=> __( "Select which direction the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_square_effect", 'value' => array('effect3') ),
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Effect Direction", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_square_direction4",
							"value"                 	=> array(
								__("Left To Right", "ts_visual_composer_extend")		=> "left_to_right",
								__("Right To Left", "ts_visual_composer_extend")		=> "right_to_left",
							),
							"description"		    	=> __( "Select which direction the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_square_effect", 'value' => array('effect5') ),
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Effect Direction", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_square_direction5",
							"value"                 	=> array(
								__("Left To Right", "ts_visual_composer_extend")		=> "left_to_right",
								__("Right To Left", "ts_visual_composer_extend")		=> "right_to_left",
								__("Top To Bottom", "ts_visual_composer_extend")		=> "top_to_bottom",
								__("Bottom To Top", "ts_visual_composer_extend")		=> "bottom_to_top",
							),
							"description"		    	=> __( "Select which direction the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_square_effect", 'value' => array('effect9','effect10','effect11','effect12','effect13','effect14','effect15') ),
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Effect Scaling", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_square_scale",
							"value"                 	=> array(
								__("Scale Up", "ts_visual_composer_extend")				=> "scale_up",
								__("Scale Down", "ts_visual_composer_extend")			=> "scale_down",
							),
							"description"		    	=> __( "Select which scale type the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_square_effect", 'value' => 'effect8' ),
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
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Use Colored Effect", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_colored",
							"value"                	 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want to apply a colored background to the effect.", "ts_visual_composer_extend" ),
							"admin_label"       		=> true,
							"dependency"		    	=> array( 'element' => "ihover_style", 'value' => array('circle','square') ),
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "ihover_background",
							"value"             		=> "rgba(26, 74, 114, 0.6)",
							"description"       		=> __( "Define the color for the effect background.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_colored", 'value' => 'true' ),
						),
						// Border Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_2",
							"seperator"					=> "Border Settings",
							"group" 			        => "Border",
						),
						array(
							"type"              		=> "messenger",
							"param_name"        		=> "messenger1",
							"color"						=> "#FF0000",
							"size"						=> "13",
							"layout"					=> "notice",
							"level"						=> "critical",
							"message"            		=> __( "The following border settings apply to all effects using the  'Square' style.", "ts_visual_composer_extend" ),
							"group" 			        => "Border",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Border Thickness", "ts_visual_composer_extend" ),
							"param_name"                => "ihover_square_border",
							"value"                     => "8",
							"min"                       => "0",
							"max"                       => "50",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Select the width of the border around the iHover image.", "ts_visual_composer_extend" ),
							"group" 			        => "Border",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Border Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "ihover_square_color",
							"value"             		=> "#ffffff",
							"description"       		=> __( "Define the color for border around the iHover image.", "ts_visual_composer_extend" ),
							"group" 			        => "Border",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Use Box-Shadow Effect", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_square_shadow",
							"value"                	 	=> "true",
							"description"		    	=> __( "Switch the toggle if you want to apply a box-shadow effect to the iHover image.", "ts_visual_composer_extend" ),
							"group" 			        => "Border",
						),
						array(
							"type"              		=> "messenger",
							"param_name"        		=> "messenger2",
							"color"						=> "#FF0000",
							"size"						=> "13",
							"layout"					=> "notice",
							"level"						=> "critical",
							"message"            		=> __( "The following border settings apply only to 'Circle' style with 'Effect 1'.", "ts_visual_composer_extend" ),
							"group" 			        => "Border",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Border Thickness", "ts_visual_composer_extend" ),
							"param_name"                => "ihover_circle_border",
							"value"                     => "10",
							"min"                       => "0",
							"max"                       => "50",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Select the width of the border around the iHover image.", "ts_visual_composer_extend" ),
							"group" 			        => "Border",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Border Color 1", "ts_visual_composer_extend" ),
							"param_name"        		=> "ihover_circle_color1",
							"value"             		=> "#ecab18",
							"description"       		=> __( "Define the color for border around the iHover image.", "ts_visual_composer_extend" ),
							"group" 			        => "Border",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Border Color 2", "ts_visual_composer_extend" ),
							"param_name"        		=> "ihover_circle_color2",
							"value"             		=> "#1ad280",
							"description"       		=> __( "Define the color for border around the iHover image.", "ts_visual_composer_extend" ),
							"group" 			        => "Border",
						),
						// Content Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_3",
							"seperator"					=> "iHover Content",
							"group" 			        => "Content",
						),
						array(
							"type"             	 		=> "switch_button",
							"heading"               	=> __( "Show Overlay Handle", "ts_visual_composer_extend" ),
							"param_name"            	=> "overlay_handle_show",
							"value"                 	=> "true",
							"description"       		=> __( "Use the toggle to show or hide a handle button below the image.", "ts_visual_composer_extend" ),
							"group" 			        => "Content",
						),
						array(
							"type"                  	=> "colorpicker",
							"heading"              	 	=> __( "Handle Color", "ts_visual_composer_extend" ),
							"param_name"            	=> "overlay_handle_color",
							"value"                 	=> "#0094FF",
							"description"           	=> __( "Define the color for the overlay handle button.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "overlay_handle_show", 'value' => 'true' ),
							"group" 			        => "Content",
						),
						array(
							"type"						=> "textfield",
							"heading"					=> __( "Title", "ts_visual_composer_extend" ),
							"param_name"				=> "ihover_title",
							"value"						=> "",
							"admin_label"       		=> true,
							"description"				=> __( "Enter a title to be used for the iHover effect.", "ts_visual_composer_extend" ),
							"group" 			        => "Content",
						),
						array(
							"type"                  	=> "textarea",
							"heading"               	=> __( "Message", "ts_visual_composer_extend" ),
							"param_name"            	=> "ihover_content",
							"value"                 	=> "",
							"description"	        	=> __( "Enter a short message to be used for the iHover effect; HTML code can NOT be used.", "ts_visual_composer_extend" ),
							"group" 					=> "Content",
						),
						// iHover Event
						array(
							"type"				    	=> "seperator",
							"param_name"		    	=> "seperator_4",
							"seperator"					=> "iHover Event",
							"group" 					=> "Event",
						),						
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "iHover Event", "ts_visual_composer_extend" ),
							"param_name"            	=> "ihover_event",
							"width"                 	=> 150,
							"value" 					=> array(
								__( "None", "ts_visual_composer_extend" )									=> "none",
								__( "Open Image in Lightbox", "ts_visual_composer_extend" )					=> "image",
								__( "Open Other Image in Lightbox", "ts_visual_composer_extend" )			=> "other",
								__( "Open Popup in Lightbox", "ts_visual_composer_extend" )					=> "popup",
								__( "Open YouTube Video in Lightbox", "ts_visual_composer_extend" )			=> "youtube",
								__( "Open Vimeo Video in Lightbox", "ts_visual_composer_extend" )			=> "vimeo",
								__( "Open DailyMotion Video in Lightbox", "ts_visual_composer_extend" )		=> "dailymotion",
								__( "Open Page in iFrame", "ts_visual_composer_extend" )					=> "iframe",
								__( "Simple Link to Page", "ts_visual_composer_extend" )					=> "link",
							),
							"description"           	=> __( "Select if the iHover image should trigger any other action.", "ts_visual_composer_extend" ),
							"admin_label"       		=> true,
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
							"dependency"            	=> array( 'element' => "ihover_event", 'value' => array('image', 'other') ),
							"group" 					=> "Event",
						),
						// Other Image
						array(
							"type"                  	=> "attach_image",
							"heading"               	=> __( "Image", "ts_visual_composer_extend" ),
							"param_name"            	=> "ihover_other",
							"value"                 	=> "",
							"description"           	=> __( "Select the image you want to show in the lightbox.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "ihover_event", 'value' => 'other' ),
							"group" 					=> "Event",
						),	
						// Modal Popup
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Show iHover Title", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_show_title",
							"value"                 	=> "true",
							"description"		    	=> __( "Switch the toggle if you want to show the title in the modal popup.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "ihover_event", 'value' => 'popup' ),
							"group" 					=> "Event",
						),
						array(
							"type"              		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorBase64TinyMCE == "true" ? "wysiwyg_base64" : "textarea_raw_html"),
							"heading"           		=> __( "iHover Description", "ts_visual_composer_extend" ),
							"param_name"        		=> "ihover_text",
							"minimal"					=> "true",
							"value"             		=> base64_encode(""),
							"description"       		=> __( "Enter the more detailed description for the modal popup; HTML code can be used.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "ihover_event", 'value' => 'popup' ),
							"group" 					=> "Event",
						),
						// YouTube / DailyMotion / Vimeo
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Video URL", "ts_visual_composer_extend" ),
							"param_name"            	=> "ihover_video_link",
							"value"                 	=> "",
							"description"           	=> __( "Enter the URL for the video to be shown in a lightbox.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "ihover_event", 'value' => array('youtube','dailymotion','vimeo') ),
							"group" 					=> "Event",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Show Related Videos", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_video_related",
							"value"             		=> "false",
							"description"		    	=> __( "Switch the toggle if you want to show related videos once the video has finished playing.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "ihover_event", 'value' => 'youtube' ),
							"group" 					=> "Event",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Autoplay Video", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_video_auto",
							"value"             		=> "true",
							"description"		    	=> __( "Switch the toggle if you want to auto-play the video once opened in the lightbox.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "ihover_event", 'value' => array('youtube','dailymotion','vimeo') ),
							"group" 					=> "Event",
						),
						// Link / iFrame
						array(
							"type" 						=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterLinkPicker['enabled'] == "false" ? "vc_link" : "advancedlinks"),
							"heading" 					=> __("Link + Title", "ts_visual_composer_extend"),
							"param_name" 				=> "ihover_link",
							"description" 				=> __("Provide a link to another site/page to be used for the iHover event.", "ts_visual_composer_extend"),
							"dependency"            	=> array( 'element' => "ihover_event", 'value' => array('iframe','link') ),
							"group" 					=> "Event",
						),
						// Lightbox Settings
						array(
							"type"						=> "seperator",
							"param_name"				=> "seperator_4",
							"seperator"					=> "Lightbox Settings",
							"dependency"            	=> array( 'element' => "ihover_event", 'value' => array('image','other','popup','youtube','dailymotion','vimeo','iframe') ),
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
							"dependency"            	=> array( 'element' => "ihover_event", 'value' => array('image','other','popup','youtube','dailymotion','vimeo','iframe') ),
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
							"dependency"            	=> array( 'element' => "ihover_event", 'value' => array('image','other','popup','youtube','dailymotion','vimeo','iframe') ),
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
							"dependency"            	=> array( 'element' => "ihover_event", 'value' => array('image','other','popup','youtube','dailymotion','vimeo','iframe') ),
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
							"dependency"            	=> array( 'element' => "ihover_event", 'value' => array('image','other','popup','youtube','dailymotion','vimeo','iframe') ),
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
							"dependency"            	=> array( 'element' => "ihover_event", 'value' => array('image','other','popup','youtube','dailymotion','vimeo','iframe') ),
							"group" 					=> "Lightbox",
						),						
						// Tooltip Settings
						array(
							"type"						=> "seperator",
							"param_name"				=> "seperator_5",
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
							"dependency"				=> array( 'element' => "tooltip_css", 'value' => 'true' ),
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
							"param_name"                => "seperator_6",
							"seperator"					=> "Other Settings",
							"group" 			        => "Other Settings",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Margin: Top", "ts_visual_composer_extend" ),
							"param_name"                => "margin_top",
							"value"                     => "20",
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
							"value"                     => "20",
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
	if ((class_exists('WPBakeryShortCode')) && (!class_exists('WPBakeryShortCode_TS_VCSC_Image_IHover'))) {
		class WPBakeryShortCode_TS_VCSC_Image_IHover extends WPBakeryShortCode {};
	}
	// Initialize "TS Image IHover" Class
	if (class_exists('TS_Image_IHover')) {
		$TS_Image_IHover = new TS_Image_IHover;
	}
?>