<?php
	if (!class_exists('TS_HorizontalTimeline')){
		class TS_HorizontalTimeline {
			function __construct() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						$this->TS_VCSC_Add_HorizontalTimeline_Elements_Lean();
					} else if (function_exists('vc_map')) {
						add_action('init',									array($this, 'TS_VCSC_Add_HorizontalTimeline_Element_Container'), 9999999);
						add_action('init',									array($this, 'TS_VCSC_Add_HorizontalTimeline_Element_Item'), 9999999);
					}
				} else {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_HorizontalTimeline_Elements_Lean'), 9999999);
					} else if (function_exists('vc_map')) {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_HorizontalTimeline_Element_Container'), 9999999);
						add_action('admin_init',							array($this, 'TS_VCSC_Add_HorizontalTimeline_Element_Item'), 9999999);
					}
				}
				if ((is_admin() == false) || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAJAX == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAlways == "true")) {
					add_shortcode('TS_VCSC_Timeline_Horizontal_Container',	array($this, 'TS_VCSC_Timeline_Horizontal_Container'));
					add_shortcode('TS_VCSC_Timeline_Horizontal_Item',		array($this, 'TS_VCSC_Timeline_Horizontal_Item'));
				}
			}
			
			// Register Element(s) via LeanMap
			function TS_VCSC_Add_HorizontalTimeline_Elements_Lean() {
				vc_lean_map('TS_VCSC_Timeline_Horizontal_Container',		array($this, 'TS_VCSC_Add_HorizontalTimeline_Element_Container'), null);
				vc_lean_map('TS_VCSC_Timeline_Horizontal_Item',				array($this, 'TS_VCSC_Add_HorizontalTimeline_Element_Item'), null);
			}
			
			// Function to Convert Date String
			function TS_VCSC_Timeline_Horizontal_Date($datestring, $dateformat, $dateinput = null) {
				$datestring 						= new DateTime($datestring);
				return $datestring->format($dateformat);
			}

			// Timeline Item
			function TS_VCSC_Timeline_Horizontal_Item ($atts, $content = null) {
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();
		
				extract( shortcode_atts( array(
					// Event Timestamp
					'date_detail'					=> 'dateonly',
					'date_dateonly'					=> '',
					'date_dateonly_quick'			=> '',
					'date_datetime'					=> '',
					'date_datetime_quick'			=> '',
					'date_format'					=> 'F jS, Y',
					// Event Title
					'event_deco'					=> 'none',
					'event_icon'					=> '',
					'event_image'					=> '', 
					'event_title'					=> '',
					// Event Media
					'media_type'					=> 'none',
					'media_position'				=> 'left',
					'media_image'					=> '',
					'media_youtube'					=> '',
					'media_vimeo'					=> '',
					'media_dailymotion'				=> '',
					'media_preview'					=> '',
					'media_lightbox'				=> 'false',
					// Style Settings
					'title_iconcolor'				=> '#696969',
					'title_textcolor'				=> '#696969',
					'title_weight'					=> 'bold',					
					'title_style'					=> 'normal',
					'title_decoration'				=> 'none',
					'title_transform' 				=> 'none',					
					'title_size'					=> 36,
					'title_family'					=> 'Default:regular',
					'title_font'					=> 'default',
					'stamp_color'					=> '#676767',
					'stamp_size'					=> 16,
					'stamp_family'					=> 'Default:regular',
					'stamp_font'					=> 'default',
					'content_color'					=> '#959595',
					'content_size'					=> 14,
					'content_family'				=> 'Default:regular',
					'content_font'					=> 'default',
					// Background Settings
					'background_type' 				=> 'color',
					'background_color' 				=> '#ffffff',
					'background_gradient' 			=> '',
					'background_pattern' 			=> '',
					'background_image' 				=> '',
					'background_size' 				=> 'cover',
					'background_repeat' 			=> 'no-repeat',
					'background_position'			=> 'center center',
					// Tooltip Settings
					'tooltip_usage'					=> 'false',
					'tooltip_content'				=> '',
					'tooltip_style'					=> 'ts-simptip-style-black',
					'tooltip_animation'				=> 'swing',
					'tooltip_arrow'					=> 'true',
					'tooltip_background'			=> '#000000',
					'tooltip_border'				=> '#000000',
					'tooltip_color'					=> '#ffffff',
					'tooltip_offsetx'				=> 0,
					'tooltip_offsety'				=> 20,
					// Other Settings
					'content_wpautop'				=> 'false',	
					'el_id' 						=> '',
					'el_class'                  	=> '',
					'css'							=> '',
				), $atts ));
				
				// Global Variables
				$media_string						= '';
				$output 							= '';
				$wpautop 							= ($content_wpautop == "true" ? true : false);			
				$randomizer							= mt_rand(999999, 9999999);
			
				// Determine Event ID
				if (!empty($el_id)) {
					$timeline_id					= $el_id;
				} else {
					$timeline_id					= 'ts-vcsc-horizontal-timeline-single-' . $randomizer;
				}
				
				// Retrieve Event Date
				$data_structure						= array();
				if ($date_detail == "dateonly") {
					$event_original					= $date_dateonly;
				} else if ($date_detail == "dateonlyquick") {
					$event_original					= $date_dateonly_quick;
				} else if ($date_detail == "datetime") {
					$event_original					= $date_datetime;
				} else if ($date_detail == "datetimequick") {
					$event_original					= $date_datetime_quick;
				}			
				$event_date							= $this->TS_VCSC_Timeline_Horizontal_Date($event_original, $date_format);
				
				// Retrieve Custom Fonts
				if (strpos($title_family, 'Default') === false) {
					$font_title 					= TS_VCSC_GetFontFamily($timeline_id, $title_family, $title_font, false, true, false);
				} else {
					$font_title						= '';
				}
				if (strpos($stamp_family, 'Default') === false) {
					$font_stamp 					= TS_VCSC_GetFontFamily($timeline_id, $stamp_family, $stamp_font, false, true, false);
				} else {
					$font_stamp						= '';
				}
				if (strpos($content_family, 'Default') === false) {
					$font_content 					= TS_VCSC_GetFontFamily($timeline_id, $content_family, $content_font, false, true, false);
				} else {
					$font_content					= '';
				}
				
				// Background Style
				$background_style					= '';
				if ($background_type == "pattern") {
					$background_style				.= "background-color: transparent;";
					$background_style				.= "background-image: url('" . $background_pattern . "');";
					$background_style				.= "background-repeat: repeat;";
				} else if ($background_type == "color") {
					$background_style				.= "background-image: none;";
					$background_style				.= "background-color: " . $background_color . ";";
				} else if ($background_type == "gradient") {
					$background_style				.= $background_gradient;
				} else if ($background_type == "image") {
					$background_image				= wp_get_attachment_image_src($background_image, 'full');
					$background_image				= $background_image[0];
					$background_style				.= "background-color: " . $background_color . ";";
					$background_style				.= "background-image: url('" . $background_image . "');";
					$background_style				.= "background-repeat: " . $background_repeat . ";";
					$background_style				.= "background-position: " . $background_position . ";";
					$background_style				.= "-webkit-background-size: " . $background_size . ";";
					$background_style				.= "-moz-background-size: " . $background_size . ";";
					$background_style				.= "-o-background-size: " . $background_size . ";";
					$background_style				.= "background-size: " . $background_size . ";";
				} else if ($background_type == "transparent") {
					$background_style				.= "background-image: none;";
					$background_style				.= "background-color: transparent;";
				}
				
				// Media Style
				$media_string						= '';
				$media_columns						= 'false';
				$media_class						= '';
				if (($media_type == "image") && ($media_image != '')) {
					$media_image					= wp_get_attachment_image_src($media_image, 'full');
					$media_columns					= "true";
					$media_string					= '<img class="ts-horizontal-timeline-media-image" src="' . $media_image[0] . '">';
					$media_class					= 'ts-horizontal-timeline-columns';
				} else if (($media_type == "youtube") && ($media_youtube != '')) {
					$media_youtube 					= TS_VCSC_VideoID_Youtube($media_youtube);
					$media_columns					= "true";
					$media_string					= '<div class="ts-horizontal-timeline-media-video ts-video-container ts-ratio-sixteen-to-nine"><iframe src="https://www.youtube.com/embed/' . $media_youtube . '?wmode=opaque" width="100%" height="auto" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
					$media_class					= 'ts-horizontal-timeline-columns';
				} else if (($media_type == "vimeo") && ($media_vimeo != '')) {
					$media_vimeo 					= TS_VCSC_VideoID_Vimeo($media_vimeo);
					$media_columns					= "true";
					$media_string					= '<div class="ts-horizontal-timeline-media-video ts-video-container ts-ratio-sixteen-to-nine"><iframe src="https://player.vimeo.com/video/  ' . $media_vimeo . '?wmode=opaque" width="100%" height="auto" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
					$media_class					= 'ts-horizontal-timeline-columns';
				} else if (($media_type == "dailymotion") && ($media_dailymotion != '')) {
					$media_columns					= "true";
					$media_string					= '<div class="ts-horizontal-timeline-media-video ts-video-container ts-ratio-sixteen-to-nine"><iframe src="' . $media_dailymotion . '?wmode=opaque" width="100%" height="auto" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
					$media_class					= 'ts-horizontal-timeline-columns';
				}
				
				// Visual Composer Override
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-horizontal-timeline-single ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Timeline_Horizontal_Item', $atts);
				} else {
					$css_class 						= 'ts-horizontal-timeline-single ' . $el_class;
				}
				
				// Generate Final Output
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "false") {
					$output .= '<li id="' . $timeline_id . '" class="' . $css_class . ' ' . $media_class . '" data-columns="' . $media_columns . '" data-position="' . $media_position . '" data-timestamp="" data-date="' . $event_date . '" data-original="' . $event_original . '" style="' . $background_style . '">';
						$output .= '<h2 class="ts-horizontal-timeline-title" style="font-size: ' . $title_size . 'px; color: ' . $title_textcolor . ';">';
							if (($event_deco == "icon") && ($event_icon != "")) {				
								$output .= '<i class="ts-horizontal-timeline-deco-icon ' . $event_icon . '" style="color: ' . $title_iconcolor . ';"></i>';
							} else if (($event_deco == "image") && ($event_image != "")) {
								$event_image		= wp_get_attachment_image_src($event_image, 'thumbnail');
								$output .= '<img class="ts-horizontal-timeline-deco-image" src="' . $event_image[0] . '" style="height: ' . $title_size . 'px;">';
							}
							$output .= '<span class="ts-horizontal-timeline-deco-text" style="font-weight: ' . $title_weight . '; font-style: ' . $title_style . '; text-transform: ' . $title_transform . '; text-decoration: ' . $title_decoration . '; ' . $font_title . '">' . $event_title . '</span>';
						$output .= '</h2>';
						$output .= '<span class="ts-horizontal-timeline-date" style="font-size: ' . $stamp_size . 'px; color: ' . $stamp_color . '; ' . $font_stamp . '">' . $event_date . '</span>';
						$output .= '<div class="ts-horizontal-timeline-text" style="font-size: ' . $content_size . 'px; color: ' . $content_color . '; ' . $font_content . '">';
							if ($media_position == "left") {
								$output .= '<div class="ts-horizontal-timeline-columnA" style="display: ' . ($media_columns == "true" ? "inline-block" : "none") . '">';
									$output .= $media_string;
								$output .= '</div>';							
								$output .= '<div class="ts-horizontal-timeline-columnB" style="' . ($media_columns == "false" ? "width: 100%; margin: 0;" : "") . '">';
									if (function_exists('wpb_js_remove_wpautop')){
										$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
									} else {
										$output .= do_shortcode($content);
									}
								$output .= '</div>';
							} else if ($media_position == "right") {
								$output .= '<div class="ts-horizontal-timeline-columnA" style="' . ($media_columns == "false" ? "width: 100%; margin: 0;" : "") . '">';
									if (function_exists('wpb_js_remove_wpautop')){
										$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
									} else {
										$output .= do_shortcode($content);
									}
								$output .= '</div>';
								$output .= '<div class="ts-horizontal-timeline-columnB" style="display: ' . ($media_columns == "true" ? "inline-block" : "none") . '">';
									$output .= $media_string;
								$output .= '</div>';	
							}
						$output .= '</div>';							
					$output .= '</li>';
				} else {
					$output .= '<div id="' . $timeline_id . '" class="ts-horizontal-timeline-frontend-single">';
						$output .= '<span class="ts-horizontal-timeline-frontend-title">' . $event_title . '</span>';
						$output .= '<span class="ts-horizontal-timeline-frontend-date">' . $event_date . '</span>';
						$output .= '<span class="ts-horizontal-timeline-frontend-text">' . $content . '</span>';
					$output .= '</div>';
				}
				
				echo $output;
				
				$myvariable = ob_get_clean();
				return $myvariable;
			}
			// Timeline Container
 			function TS_VCSC_Timeline_Horizontal_Container ($atts, $content = null){
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();

				wp_enqueue_style('dashicons');
				wp_enqueue_style('ts-extend-horizontaltimeline');
				wp_enqueue_style('ts-visual-composer-extend-front');
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "false") {
					wp_enqueue_script('ts-extend-hammer');
					wp_enqueue_script('ts-extend-momentjs');
					wp_enqueue_script('ts-visual-composer-extend-front');
					wp_enqueue_script('ts-extend-horizontaltimeline');
				}
				
				extract( shortcode_atts( array(
					'timeline_preloader'			=> 0,
					'timeline_startevent'			=> 0,
					'timeline_minspace'				=> 60,
					'timeline_maxspace'				=> 360,
					'timeline_leftspace'			=> 80,
					'timeline_leftalign'			=> 'false',
					'timeline_rightspace'			=> 80,
					'timeline_rightalign'			=> 'false',
					'timeline_equalize'				=> 'false',
					'timeline_keyboard'				=> 'true',
					'timeline_reverse'				=> 'false',
					'timeline_direction'			=> 'ltr',
					'timeline_formata'				=> 'j M Y',
					'timeline_formatb'				=> 'j M Y h:i A',
					'timeline_shadow'				=> '',
					'timeline_fullwidth'			=> 'false',
					'timeline_breakouts'			=> 0,
					'timeline_columns'				=> 640,
					// Theme Settings
					'theme_customize'				=> 'false',
					'theme_progress_fontsize'		=> 12,
					'theme_progress_fontcolor'		=> '#383838',
					'theme_progress_strength'		=> 2,
					'theme_progress_future'			=> '#dfdfdf',
					'theme_progress_past'			=> '#35a6e2',
					'theme_bullets_size'			=> 18,
					'theme_bullets_border'			=> 2,
					'theme_bullets_color'			=> '#dfdfdf',
					'theme_bullets_background'		=> '#f8f8f8',
					'theme_bullets_active'			=> '#35a6e2',
					'theme_nav_border'				=> '#dfdfdf',
					'theme_nav_icon'				=> '#848484',
					'theme_nav_hover'				=> '#35a6e2',
					'theme_nav_inactive'			=> '#eaeaea',
					'theme_border_main'				=> 'border-style:solid;|border-width:1px;|border-color:#ededed;',
					'theme_border_split'			=> 'border-style:solid;|border-width:1px;|border-color:#ededed;',
					'theme_content_padding'			=> 'padding-top:10px;padding-right:20px;padding-bottom:10px;padding-left:20px;',
					'theme_content_align'			=> 'inherit',
					// Background Settings
					'background_type' 				=> 'color',
					'background_color' 				=> '#ffffff',
					'background_gradient' 			=> '',
					'background_pattern' 			=> '',
					'background_image' 				=> '',
					'background_size' 				=> 'cover',
					'background_repeat' 			=> 'no-repeat',
					'background_position'			=> 'center center',
					// Other Settings
					'content_wpautop'				=> 'false',
					'margin_bottom'					=> '0',
					'margin_top' 					=> '0',					
					'el_id' 						=> '',
					'el_class'                  	=> '',
					'css'							=> '',
				), $atts ));
				
				$timeline_random                 	= mt_rand(999999, 9999999);
				
				// Create Timeline ID
				if (!empty($el_id)) {
					$timeline_id					= $el_id;
				} else {
					$timeline_id					= 'ts-vcsc-horizontal-timeline-container-' . $timeline_random;
				}
				
				// Other Global Variables
				$output 							= '';
				$styles								= '';
				$wpautop 							= ($content_wpautop == "true" ? true : false);
				$inline								= TS_VCSC_FrontendAppendCustomRules('style');
				
				// Extract Section Dates from $content
				preg_match_all('/TS_VCSC_Timeline_Horizontal_Item([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE);
				$event_data 						= array();
				$event_count						= 0;
				$event_even							= false;				
				if (isset($matches[1])) {
					$event_data 					= $matches[1];
				}
				$event_total						= count($event_data);
				
				// Contingency Checks
				if ($timeline_leftspace == 0) {
					$timeline_leftalign 				= "true";
				}
				if ($timeline_leftspace < ($theme_bullets_size / 2)) {
					$timeline_leftspace				= $timeline_leftspace + ($theme_bullets_size / 2);
				}
				if ($timeline_rightspace < ($theme_bullets_size / 2)) {
					$timeline_rightspace			= $timeline_rightspace + ($theme_bullets_size / 2);
				}
				
				// Create Data Attributes
				$timeline_data						= 'data-direction="' . $timeline_direction . '" data-reverse="' . $timeline_reverse . '" data-equalizer="' . $timeline_equalize . '" data-columnsstack="' . $timeline_columns . '"';
				$timeline_data						.= ' data-startevent="' . $timeline_startevent . '" data-keyboard="' . $timeline_keyboard . '"';
				$timeline_data						.= ' data-minspace="' . $timeline_minspace . '" data-maxspace="' . $timeline_maxspace . '" data-leftspace="' . $timeline_leftspace . '" data-rightspace="' . $timeline_rightspace . '"';
				
				// Background Style
				$background_style					= '';
				$background_class					= '';
				$background_datas					= '';
				if ($background_type == "pattern") {
					$background_style				.= "background-color: transparent;";
					$background_style				.= "background-image: url('" . $background_pattern . "');";
					$background_style				.= "background-repeat: repeat;";
				} else if ($background_type == "color") {
					$background_style				.= "background-image: none;";
					$background_style				.= "background-color: " . $background_color . ";";
				} else if ($background_type == "gradient") {
					$background_style				.= $background_gradient;
				} else if ($background_type == "image") {
					$background_image				= wp_get_attachment_image_src($background_image, 'full');
					$background_image				= $background_image[0];
					$background_style				.= "background-color: " . $background_color . ";";
					$background_style				.= "background-image: url('" . $background_image . "');";
					$background_style				.= "background-repeat: " . $background_repeat . ";";
					$background_style				.= "background-position: " . $background_position . ";";
					$background_style				.= "-webkit-background-size: " . $background_size . ";";
					$background_style				.= "-moz-background-size: " . $background_size . ";";
					$background_style				.= "-o-background-size: " . $background_size . ";";
					$background_style				.= "background-size: " . $background_size . ";";
				} else if ($background_type == "transparent") {
					$background_style				.= "background-image: none;";
					$background_style				.= "background-color: transparent;";
				}
				
				// Create Custom Styling
				if ($theme_customize == "true") { 
					$theme_border_main 				= str_replace("|", "", $theme_border_main);
					$theme_border_split 			= str_replace("|", "", $theme_border_split);
					if ($inline == "false") {
						$styles .= '<style id="ts-horizontal-timeline-styles-' . $timeline_random . '" type="text/css">';
					}
						$styles .= '#' . $timeline_id . '.ts-horizontal-timeline-main {';
							$styles .= $theme_border_main;
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-controls-' . $timeline_random . ' {';
							$styles .= $theme_border_split;
							$styles .= 'border-top: none !important;';
							$styles .= 'border-left: none !important;';
							$styles .= 'border-right: none !important;';
							$styles .= $background_style;
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-events-' . $timeline_random . ',';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-filling-' . $timeline_random . ' {';
							$styles .= 'height: ' . $theme_progress_strength . 'px;';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-filling-' . $timeline_random . ' {';
							$styles .= 'background: ' . $theme_progress_past . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-events-' . $timeline_random . ' {';
							$styles .= 'background: ' . $theme_progress_future . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-events-' . $timeline_random . ' .ts-horizontal-timeline-bullets li a {';
							$styles .= 'font-size: ' . $theme_progress_fontsize . 'px;';
							$styles .= 'color: ' . $theme_progress_fontcolor . ';';
						$styles .= '}';						
						if ($timeline_leftalign == "true") {
							$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-events-' . $timeline_random . ' .ts-horizontal-timeline-bullets li:first-of-type a span {';								
								$styles .= '-webkit-transform: none;';
								$styles .= '-moz-transform: none;';
								$styles .= 'transform: none;';
								$styles .= 'text-align: left;';
								$styles .= 'margin-left: -9px;';
							$styles .= '}';
						}
						if ($timeline_rightalign == "true") {
							$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-events-' . $timeline_random . ' .ts-horizontal-timeline-bullets li:last-of-type a span {';								
								$styles .= '-webkit-transform: translateX(-100%);';
								$styles .= '-moz-transform: translateX(-100%);';
								$styles .= 'transform: translateX(-100%);';
								$styles .= 'text-align: right;';
								$styles .= 'margin-left: 9px;';
							$styles .= '}';
						}
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-events-' . $timeline_random . ' .ts-horizontal-timeline-bullets li:nth-child(odd) a::after,';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-events-' . $timeline_random . ' .ts-horizontal-timeline-bullets li:nth-child(even) a::before {';
							$styles .= 'height: ' . $theme_bullets_size . 'px;';
							$styles .= 'width: ' . $theme_bullets_size . 'px;';
							$styles .= 'border: ' . $theme_bullets_border . 'px solid ' . $theme_bullets_color . ';';
							$styles .= 'background-color: ' . $theme_bullets_background . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-events-' . $timeline_random . ' .ts-horizontal-timeline-bullets li:nth-child(odd) a.older-event::after,';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-events-' . $timeline_random . ' .ts-horizontal-timeline-bullets li:nth-child(even) a.older-event::before {';
							$styles .= 'border-color: ' . $theme_bullets_active . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-events-' . $timeline_random . ' .ts-horizontal-timeline-bullets li:nth-child(odd) a.selected::after,';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-events-' . $timeline_random . ' .ts-horizontal-timeline-bullets li:nth-child(even) a.selected::before {';
							$styles .= 'border-color: ' . $theme_bullets_active . ';';
							$styles .= 'background-color: ' . $theme_bullets_active . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-events-' . $timeline_random . ' .ts-horizontal-timeline-bullets li:nth-child(odd) a:hover::after,';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-events-' . $timeline_random . ' .ts-horizontal-timeline-bullets li:nth-child(even) a:hover::before {';
							$styles .= 'border-color: ' . $theme_bullets_active . ';';
							$styles .= 'background-color: ' . $theme_bullets_active . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-navigation-' . $timeline_random . ' a,';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-nextprev-' . $timeline_random . ' a {';
							$styles .= 'border-color: ' . $theme_nav_border . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-navigation-' . $timeline_random . ' a::after,';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-nextprev-' . $timeline_random . ' a::after {';
							$styles .= 'color: ' . $theme_nav_icon . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-navigation-' . $timeline_random . ' a:hover,';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-nextprev-' . $timeline_random . ' a:hover {';
							$styles .= 'border-color: ' . $theme_nav_hover . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-navigation-' . $timeline_random . ' a:hover::after,';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-nextprev-' . $timeline_random . ' a:hover::after {';
							$styles .= 'color: ' . $theme_nav_hover . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-navigation-' . $timeline_random . ' a.inactive::after,';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-nextprev-' . $timeline_random . ' a.inactive::after {';
							$styles .= 'color: ' . $theme_nav_inactive . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-navigation-' . $timeline_random . ' a.inactive,';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-nextprev-' . $timeline_random . ' a.inactive,';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-navigation-' . $timeline_random . ' a.inactive:hover,';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-nextprev-' . $timeline_random . ' a.inactive:hover {';
							$styles .= 'border-color: ' . $theme_nav_inactive . ';';
						$styles .= '}';
						$styles .= '#' . $timeline_id . ' #ts-horizontal-timeline-content-' . $timeline_random . ' .ts-horizontal-timeline-text {';
							$styles .= $theme_content_padding;
							$styles .= 'text-align: ' . $theme_content_align . ';';
						$styles .= '}';
					if ($inline == "false") {
						$styles .= '</style>';
					}
					if (($styles != "") && ($inline == "true")) {
						wp_add_inline_style('ts-visual-composer-extend-custom', TS_VCSC_MinifyCSS($styles));
					}
				} else {
					if ($timeline_leftalign == "true") {
						$timeline_leftalign			= 'ts-horizontal-timeline-bullet-first';
					} else {
						$timeline_leftalign			= '';
					}
					if ($timeline_rightalign == "true") {
						$timeline_rightalign			= 'ts-horizontal-timeline-bullet-last';
					} else {
						$timeline_rightalign			= '';
					}
				}
				
				// Visual Composer Override
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-horizontal-timeline-main ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Timeline_Horizontal_Container', $atts);
				} else {
					$css_class 						= 'ts-horizontal-timeline-main ' . $el_class;
				}

				// Create Final Output
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "false") {
					$output .= '<div id="' . $timeline_id . '" class="' . $css_class . ' ts-horizontal-timeline-' . $timeline_direction . ' ts-css-shadow ' . $timeline_shadow . '" ' . $timeline_data . '>';
						// Custom Styling
						if (($styles != "") && ($inline == "false") && ($theme_customize == "true")) {
							$output .= TS_VCSC_MinifyCSS($styles);
						}
						// Preloader Animation
						if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "false") {
							$output .= '<div class="ts-horizontal-timeline-preloader">';
								$output .= TS_VCSC_CreatePreloaderCSS("ts-horizontal-timeline-preloader-" . $timeline_random, "", $timeline_preloader, "true");
							$output .= '</div>';
						}
						// Timeline Element
						$output .= '<div id="ts-horizontal-timeline-controls-' . $timeline_random . '" class="ts-horizontal-timeline-controls">';
							$output .= '<div id="ts-horizontal-timeline-wrapper-' . $timeline_random . '" class="ts-horizontal-timeline-wrapper">';
								$output .= '<div id="ts-horizontal-timeline-events-' . $timeline_random . '" class="ts-horizontal-timeline-events">';
									$output .= '<ol id="ts-horizontal-timeline-bullets-' . $timeline_random . '" class="ts-horizontal-timeline-bullets">';
										foreach ($event_data as $event) {
											$event_atts 					= shortcode_parse_atts($event[0]);
											$event_count++;
											$event_even 					= ($event_count % 2 == 0);
											$event_original					= '';
											$event_format					= '';
											if ((isset($event_atts['date_detail'])) && ($event_atts['date_detail'] == 'dateonly')) {
												if (isset($event_atts['date_dateonly'])) {
													$event_original			= $event_atts['date_dateonly'];
													$event_format			= $this->TS_VCSC_Timeline_Horizontal_Date($event_original, $timeline_formata);
												}
											} else if ((isset($event_atts['date_detail'])) && ($event_atts['date_detail'] == 'dateonlyquick')) {
												if (isset($event_atts['date_dateonly_quick'])) {
													$event_original			= $event_atts['date_dateonly_quick'];
													$event_format			= $this->TS_VCSC_Timeline_Horizontal_Date($event_original, $timeline_formata);
												}
											} else if ((isset($event_atts['date_detail'])) && ($event_atts['date_detail'] == 'datetime')) {
												if (isset($event_atts['date_datetime'])) {
													$event_original			= $event_atts['date_datetime'];
													$event_format			= $this->TS_VCSC_Timeline_Horizontal_Date($event_original, $timeline_formatb);
												}
											} else if ((isset($event_atts['date_detail'])) && ($event_atts['date_detail'] == 'datetimequick')) {
												if (isset($event_atts['date_datetime_quick'])) {
													$event_original			= $event_atts['date_datetime_quick'];
													$event_format			= $this->TS_VCSC_Timeline_Horizontal_Date($event_original, $timeline_formatb);
												}
											}			
											$event_date						= $this->TS_VCSC_Timeline_Horizontal_Date($event_original, 'd/m/Y H:i');
											if ($event_original != '') {
												// Tooltip Setup
												$tooltip_content			= '';
												$tooltip_class				= '';
												if ((isset($event_atts['tooltip_usage'])) && (($event_atts['tooltip_usage'] == 'title') || ($event_atts['tooltip_usage'] == 'true')) && ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "false")) {
													if ((isset($event_atts['tooltip_content'])) && (strip_tags($event_atts['tooltip_content']) != '')) {
														$tooltip_content	= strip_tags($event_atts['tooltip_content']);
													} else if ((isset($event_atts['event_title'])) && (strip_tags($event_atts['event_title']) != '')) {
														$tooltip_content	= base64_encode(strip_tags($event_atts['event_title']));
													}
													if ($tooltip_content != "") {
														wp_enqueue_style('ts-extend-tooltipster');
														wp_enqueue_script('ts-extend-tooltipster');
														$tooltip_position	= ($event_even == true ? TS_VCSC_TooltipMigratePosition("ts-simptip-position-bottom") : TS_VCSC_TooltipMigratePosition("ts-simptip-position-top"));
														$tooltip_style		= ((isset($event_atts['tooltip_style'])) ? TS_VCSC_TooltipMigrateStyle($event_atts['tooltip_style']) : TS_VCSC_TooltipMigrateStyle("ts-simptip-style-black"));
														$tooltip_arrow		= ((isset($event_atts['tooltip_arrow'])) ? $event_atts['tooltip_arrow'] : "true");
														$tooltip_animation	= ((isset($event_atts['tooltip_animation'])) ? $event_atts['tooltip_animation'] : "swing");
														$tooltip_background	= ((isset($event_atts['tooltip_background'])) ? $event_atts['tooltip_background'] : "#000000");
														$tooltip_border		= ((isset($event_atts['tooltip_border'])) ? $event_atts['tooltip_border'] : "#000000");
														$tooltip_color		= ((isset($event_atts['tooltip_color'])) ? $event_atts['tooltip_color'] : "#ffffff");
														$tooltip_offsetx	= ((isset($event_atts['tooltip_offsetx'])) ? $event_atts['tooltip_offsetx'] : "0");
														$tooltip_offsety	= ((isset($event_atts['tooltip_offsety'])) ? $event_atts['tooltip_offsety'] : "20");
														$tooltip_content	= 'data-tooltipster-html="true" data-tooltipster-title="" data-tooltipster-text="' . $tooltip_content . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="' . $tooltip_arrow . '" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="' . $tooltip_animation . '" data-tooltipster-trigger="hover" data-tooltipster-background="' . $tooltip_background . '" data-tooltipster-border="' . $tooltip_border . '" data-tooltipster-color="' . $tooltip_color . '" data-tooltipster-offsetx="' . $tooltip_offsetx . '" data-tooltipster-offsety="' . $tooltip_offsety . '"';
														$tooltip_class		= 'ts-has-tooltipster-tooltip';
													}
												}
												$output .= '<li class="ts-horizontal-timeline-quick" data-timestamp="" data-date="' . $event_date . '" data-original="' . $event_original . '">';
													$output .= '<a class="' . $tooltip_class . '" href="#0" ' . $tooltip_content . ' data-timestamp="" data-date="' . $event_date . '" data-original="' . $event_original . '"><span class="' . ($event_count == 1 ? $timeline_rightalign : ($event_count == $event_total ? $timeline_rightalign : "")) . '">' . $event_format . '</span></a>';
												$output .= '</li>';
											}
										}
									$output .= '</ol>';
									$output .= '<span id="ts-horizontal-timeline-filling-' . $timeline_random . '" class="ts-horizontal-timeline-filling" aria-hidden="true"></span>';
								$output .= '</div>';
							$output .= '</div>';		
							$output .= '<ul id="ts-horizontal-timeline-navigation-' . $timeline_random . '" class="ts-horizontal-timeline-navigation">';
								$output .= '<li><a href="#0" class="prev inactive"></a></li>';
								$output .= '<li><a href="#0" class="next"></a></li>';
							$output .= '</ul>';
						$output .= '</div>';
						$output .= '<div id="ts-horizontal-timeline-nextprev-' . $timeline_random . '" class="ts-horizontal-timeline-nextprev">';
							$output .= '<a href="#0" class="prev"></a>';
							$output .= '<a href="#0" class="next"></a>';
						$output .= '</div>';
						$output .= '<div id="ts-horizontal-timeline-content-' . $timeline_random . '" class="ts-horizontal-timeline-content">';
							$output .= '<ol class="ts-horizontal-timeline-items" data-columnsstack="' . $timeline_columns . '">';
								if (function_exists('wpb_js_remove_wpautop')){
									$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
								} else {
									$output .= do_shortcode($content);
								}
							$output .= '</ol>';
						$output .= '</div>';
					$output .= '</div>';
				} else {
					$output .= '<div id="' . $timeline_id . '" class="ts-horizontal-timeline-frontend-main">';
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
			
			// Add Timeline Elements
			function TS_VCSC_Add_HorizontalTimeline_Element_Container() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Timeline Container Element
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					"name"                              => __("TS Horizontal Timeline", "ts_visual_composer_extend"),
					"base"                              => "TS_VCSC_Timeline_Horizontal_Container",
					"icon"                              => "ts-composer-element-icon-horizontimeline-container",
					"category"                          => __("VC Extensions", "ts_visual_composer_extend"),
					"as_parent"                         => array('only' => 'TS_VCSC_Timeline_Horizontal_Item'),
					"description"                       => __("Build a horizontal date based timeline", "ts_visual_composer_extend"),
					"controls" 							=> "full",
					"is_container" 						=> true,
					"container_not_allowed" 			=> false,
					"show_settings_on_create"           => true,
					"admin_enqueue_js"            		=> "",
					"admin_enqueue_css"           		=> "",
					"js_view"                           => "VcColumnView",
					"params"                            => array(
						// General Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_1",
							"seperator"					=> "General Setup",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Timeline: Keyboard Navigation", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_keyboard",
							"value"                 	=> "true",
							"description"		    	=> __( "Switch the toggle if you want to allow the usage of keyboard buttons to navigate the timeline.", "ts_visual_composer_extend" ),
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Timeline: Reverse Order", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_reverse",
							"value"                 	=> "false",
							"admin_label"				=> true,
							"description"		    	=> __( "Switch the toggle if you want to reverse the order of events within the timeline.", "ts_visual_composer_extend" ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Timeline: Start Event", "ts_visual_composer_extend" ),
							"param_name"                => "timeline_startevent",
							"value"                     => "0",
							"min"                       => "0",
							"max"                       => "20",
							"step"                      => "1",
							"unit"                      => '',
							"admin_label"				=> true,
							"description"               => __( "Define the event the timeline should initially start out with; zero (0) equals the first element.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Timeline: Shadow Effect", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_shadow",
							"width"             		=> 300,
							"value"             		=> array(
								__( "None", "ts_visual_composer_extend" )                          => "",
								__( "Lifted", "ts_visual_composer_extend" )                        => "lifted",
								__( "Raised", "ts_visual_composer_extend" )                        => "raised",
								__( "Perspective - Right", "ts_visual_composer_extend" )           => "perspective-right",
								__( "Perspective - Left", "ts_visual_composer_extend" )            => "perspective-left",
								__( "Curved - Horizontal", "ts_visual_composer_extend" )           => "curved",
								__( "Curved - Horizontal (Top)", "ts_visual_composer_extend" )     => "curved-top",
								__( "Curved - Horizontal (Bottom)", "ts_visual_composer_extend" )  => "curved-bottom",
								__( "Curved - Vertical", "ts_visual_composer_extend" )             => "curved-vertical",
								__( "Curved - Vertical (Left)", "ts_visual_composer_extend" )      => "curved-vertical-left",
								__( "Curved - Vertical (Right)", "ts_visual_composer_extend" )     => "curved-vertical-right",
							),
							"admin_label"				=> true,
							"description"       		=> __( "Select the shadow effect to be applied to the overall element.", "ts_visual_composer_extend" ),
						),
						// Format Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_3",
							"seperator"					=> "Date + Time Format",
						),
						array(
							"type"                      => "textfield",
							"heading"                   => __( "Format: Date Only", "ts_visual_composer_extend" ),
							"param_name"                => "timeline_formata",
							"value"                     => "j M Y",
							"admin_label"				=> true,
							"description"               => __( "Provide the format to be used to format date only events on the timeline.", "ts_visual_composer_extend" ) . ' <a href="http://php.net/manual/en/function.date.php" target="_blank">' . __( "Learn more here.", "ts_visual_composer_extend" ) . '</a>',
						),
						array(
							"type"                      => "textfield",
							"heading"                   => __( "Format: Date + Time", "ts_visual_composer_extend" ),
							"param_name"                => "timeline_formatb",
							"value"                     => "j M Y h:i A",
							"admin_label"				=> true,
							"description"               => __( "Provide the format to be used to format events on the timeline with a date and time.", "ts_visual_composer_extend" ) . ' <a href="http://php.net/manual/en/function.date.php" target="_blank">' . __( "Learn more here.", "ts_visual_composer_extend" ) . '</a>',
						),
						// Spacing Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_3",
							"seperator"					=> "Timeline Spacings",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Timeline: Equal Distances", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_equalize",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want to space each event on the timeline with an equal distance to each other.", "ts_visual_composer_extend" ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Timeline: Minimum Space", "ts_visual_composer_extend" ),
							"param_name"                => "timeline_minspace",
							"value"                     => "60",
							"min"                       => "20",
							"max"                       => "200",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define the minimum required space between each event on the timeline.", "ts_visual_composer_extend" ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Timeline: Maximum Space", "ts_visual_composer_extend" ),
							"param_name"                => "timeline_maxspace",
							"value"                     => "360",
							"min"                       => "100",
							"max"                       => "480",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define the maximum allowable space between each event on the timeline.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_equalize", 'value' => 'false' ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Timeline: Left Space", "ts_visual_composer_extend" ),
							"param_name"                => "timeline_leftspace",
							"value"                     => "80",
							"min"                       => "0",
							"max"                       => "120",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define the desired distance from the beginning of the timeline to the first event on the timeline.", "ts_visual_composer_extend" ),
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Timeline: Left Align", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_leftalign",
							"value"            	 		=> "false",
							"description"       		=> __( "Switch the toggle if you want to left align the label for the first bullet instead of centering it.", "ts_visual_composer_extend" ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Timeline: Right Space", "ts_visual_composer_extend" ),
							"param_name"                => "timeline_rightspace",
							"value"                     => "80",
							"min"                       => "0",
							"max"                       => "120",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define the minimum distance from the last event on the timeline to the end of the timeline.", "ts_visual_composer_extend" ),
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Timeline: Right Align", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_rightalign",
							"value"            	 		=> "false",
							"description"       		=> __( "Switch the toggle if you want to right align the label for the last bullet instead of centering it.", "ts_visual_composer_extend" ),
						),
						// Preloader Setting
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_4",
							"seperator"					=> "Preloader Settings",
						),
						array(
							"type"				    	=> "livepreview",
							"heading"			    	=> __( "Preloader Style", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_preloader",
							"preview"					=> "preloaders",
							"value"                 	=> 0,
							"description"		    	=> __( "Select the style for the preloader animation to be shown while the element is rendering.", "ts_visual_composer_extend" ),
						),
						// Theme Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_5a",
							"seperator"					=> "General Styling",
							"group" 			        => "Styling",
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Theme: Customize", "ts_visual_composer_extend" ),
							"param_name"        		=> "theme_customize",
							"value"            	 		=> "false",
							"description"       		=> __( "Switch the toggle if you want to customize some aspects of the timeline theme.", "ts_visual_composer_extend" ),
							"group" 					=> "Styling",
						),
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_5b",
							"seperator"					=> __( "Date / Time Strings", "ts_visual_composer_extend" ),
							"borderwidth"				=> 2,
							"bordertype"				=> 'dashed',
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Theme: Date/Time Font Size", "ts_visual_composer_extend" ),
							"param_name"                => "theme_progress_fontsize",
							"value"                     => "12",
							"min"                       => "10",
							"max"                       => "16",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define the font size to be used for all date/time strings on the timeline itself.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Theme: Date/Time Font Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'theme_progress_fontcolor',
							'value'						=> '#383838',
							'description' 				=> __( 'Define the font color to be used for all date/time strings on the timeline itself.', 'ts_visual_composer_extend' ),
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_5c",
							"seperator"					=> __( "Timeline Bar", "ts_visual_composer_extend" ),
							"borderwidth"				=> 2,
							"bordertype"				=> 'dashed',
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Theme: Timeline Strength", "ts_visual_composer_extend" ),
							"param_name"                => "theme_progress_strength",
							"value"                     => "2",
							"min"                       => "1",
							"max"                       => "10",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define the overall strength for the timeline bar.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Theme: Timeline Color Future', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'theme_progress_future',
							'value'						=> '#dfdfdf',
							'description' 				=> __( 'Define the color to be used for the timeline sections in the future.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),	
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Theme: Timeline Color Past', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'theme_progress_past',
							'value'						=> '#35a6e2',
							'description' 				=> __( 'Define the color to be used for the timeline sections in the past.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_5d",
							"seperator"					=> __( "Timeline Bullets", "ts_visual_composer_extend" ),
							"borderwidth"				=> 2,
							"bordertype"				=> 'dashed',
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Theme: Bullets Size", "ts_visual_composer_extend" ),
							"param_name"                => "theme_bullets_size",
							"value"                     => "18",
							"min"                       => "12",
							"max"                       => "30",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define the overall size for all bullets on the timeline bar.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Theme: Bullets Border Strength", "ts_visual_composer_extend" ),
							"param_name"                => "theme_bullets_border",
							"value"                     => "2",
							"min"                       => "1",
							"max"                       => "6",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define the border strength for all bullets on the timeline bar.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Theme: Bullets Border Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'theme_bullets_color',
							'value'						=> '#dfdfdf',
							'description' 				=> __( 'Define the color to be used for the border of all bullets on the timeline.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),	
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Theme: Bullets Background Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'theme_bullets_background',
							'value'						=> '#f8f8f8',
							'description' 				=> __( 'Define the color to be as background for all bullets on the timeline.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),		
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Theme: Bullets Active Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'theme_bullets_active',
							'value'						=> '#35a6e2',
							'description' 				=> __( 'Define the color to be used for the currently active bullet on the timeline.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),	
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_5e",
							"seperator"					=> __( "Navigation Buttons", "ts_visual_composer_extend" ),
							"borderwidth"				=> 2,
							"bordertype"				=> 'dashed',
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Theme: Navigation Border Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'theme_nav_border',
							'value'						=> '#dfdfdf',
							'description' 				=> __( 'Define the color to be used for the border of all navigation buttons.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Theme: Navigation Icon Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'theme_nav_icon',
							'value'						=> '#848484',
							'description' 				=> __( 'Define the color to be used for the icon in all navigation buttons.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Theme: Navigation Hover Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'theme_nav_hover',
							'value'						=> '#35a6e2',
							'description' 				=> __( 'Define the color to be used when hovering over any navigation button.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_5f",
							"seperator"					=> __( "Border / Separator", "ts_visual_composer_extend" ),
							"borderwidth"				=> 2,
							"bordertype"				=> 'dashed',
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"						=> "advanced_styling",
							"heading"					=> __("Theme: Main Border", "ts_visual_composer_extend"),
							"param_name"				=> "theme_border_main",
							"style_type"				=> "border",
							"show_main"					=> "false",
							"show_preview"				=> "false",
							"show_width"				=> "true",
							"show_style"				=> "true",
							"show_radius"				=> "false",					
							"show_color"				=> "true",
							"show_unit_width"			=> "true",
							"show_unit_radius"			=> "false",
							"override_all"				=> "false",
							"default_positions"			=> array(
								"All"							=> array("string" => __("All", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#ededed", "radius" => "0", "unitradius" => "px"),
								"Top"							=> array("string" => __("Top", "ts_visual_composer_extend"),	"width" => "0", "unitwidth" => "px", "style" => "solid", "color" => "#ededed", "radius" => "0", "unitradius" => "px"),
								"Right"							=> array("string" => __("Right", "ts_visual_composer_extend"),	"width" => "0", "unitwidth" => "px", "style" => "solid", "color" => "#ededed", "radius" => "0", "unitradius" => "px"),
								"Bottom"						=> array("string" => __("Bottom", "ts_visual_composer_extend"),	"width" => "0", "unitwidth" => "px", "style" => "solid", "color" => "#ededed", "radius" => "0", "unitradius" => "px"),
								"Left"							=> array("string" => __("Left", "ts_visual_composer_extend"),	"width" => "0", "unitwidth" => "px", "style" => "solid", "color" => "#ededed", "radius" => "0", "unitradius" => "px"),
							),
							"description"				=> __( "Define the overall border for the horizontal timeline element.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"						=> "advanced_styling",
							"heading"					=> __("Theme: Split Border", "ts_visual_composer_extend"),
							"param_name"				=> "theme_border_split",
							"style_type"				=> "border",
							"show_main"					=> "false",
							"show_preview"				=> "false",
							"show_width"				=> "true",
							"show_style"				=> "true",
							"show_radius"				=> "false",					
							"show_color"				=> "true",
							"show_unit_width"			=> "true",
							"show_unit_radius"			=> "false",
							"override_all"				=> "false",
							"default_positions"			=> array(
								"All"							=> array("string" => __("All", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#ededed", "radius" => "0", "unitradius" => "px"),
								"Top"							=> array("string" => __("Top", "ts_visual_composer_extend"),	"width" => "0", "unitwidth" => "px", "style" => "solid", "color" => "#ededed", "radius" => "0", "unitradius" => "px"),
								"Right"							=> array("string" => __("Right", "ts_visual_composer_extend"),	"width" => "0", "unitwidth" => "px", "style" => "solid", "color" => "#ededed", "radius" => "0", "unitradius" => "px"),
								"Bottom"						=> array("string" => __("Bottom", "ts_visual_composer_extend"),	"width" => "0", "unitwidth" => "px", "style" => "solid", "color" => "#ededed", "radius" => "0", "unitradius" => "px"),
								"Left"							=> array("string" => __("Left", "ts_visual_composer_extend"),	"width" => "0", "unitwidth" => "px", "style" => "solid", "color" => "#ededed", "radius" => "0", "unitradius" => "px"),
							),
							"description"				=> __( "Define the border between the timeline and event content sections.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),						
						// Background Settings
                        array(
                            "type"                      => "seperator",
                            "param_name"                => "seperator_5g",
                            "seperator"					=> "Background Styling",
							"borderwidth"				=> 2,
							"bordertype"				=> 'dashed',
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
                        ),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Background Type", "ts_visual_composer_extend" ),
							"param_name"        		=> "background_type",
							"width"             		=> 300,
							"value"             		=> array(
								__( "Solid Color", "ts_visual_composer_extend" )				=> "color",
								__( "Transparent Background", "ts_visual_composer_extend" )		=> "transparent",
								__( "Gradient Background", "ts_visual_composer_extend" )		=> "gradient",
								__( "Background Pattern", "ts_visual_composer_extend" )			=> "pattern",
								__( "Custom Image", "ts_visual_composer_extend" )				=> "image",
							),
							"description"       		=> __( "Select the background type for the timeline section.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "background_color",
							"value"             		=> "#ffffff",
							"description"       		=> __( "Select the background color for the timeline section.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "background_type", 'value' => array('color', 'image') ),
							"group"						=> "Styling",
						),			
						array(
							"type"						=> "advanced_gradient",
							"heading"					=> __("Gradient Background", "ts_visual_composer_extend"),						
							"param_name"				=> "background_gradient",
							"description"				=> __('Use the controls above to create a custom gradient background for the timeline section.', 'ts_visual_composer_extend'),
							"dependency"        		=> array( 'element' => "background_type", 'value' => 'gradient' ),
							"group"						=> "Styling",
						),			
						array(
							"type"              		=> "background",
							"heading"           		=> __( "Background Pattern", "ts_visual_composer_extend" ),
							"param_name"        		=> "background_pattern",
							"height"            		=> 200,
							"pattern"           		=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Background_List,
							"value"						=> "",
							"encoding"          		=> "false",
							"empty"						=> "true",
							"description"       		=> __( "Select the background pattern for the timeline section.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "background_type", 'value' => 'pattern' ),
							"group"						=> "Styling",
						),
						array(
							"type"              		=> "attach_image",
							"heading"           		=> __( "Background Image", "ts_visual_composer_extend" ),
							"param_name"        		=> "background_image",
							"value"             		=> "",
							"description"       		=> __( "Select an image or pattern to be used as background for the timeline section.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "background_type", 'value' => 'image' ),
							"group"						=> "Styling",
						),		
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Background Size", "ts_visual_composer_extend" ),
							"param_name"				=> "background_size",
							"width"						=> 150,
							"value"						=> array(
								__( "Cover", "ts_visual_composer_extend" ) 			=> "cover",
								__( "150%", "ts_visual_composer_extend" )			=> "150%",
								__( "200%", "ts_visual_composer_extend" )			=> "200%",
								__( "Contain", "ts_visual_composer_extend" ) 		=> "contain",
								__( "Initial", "ts_visual_composer_extend" ) 		=> "initial",
								__( "Auto", "ts_visual_composer_extend" ) 			=> "auto",
							),
							"description"				=> __( "Select how the custom background image should be sized.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "background_type", 'value' => 'image' ),
							"group"						=> "Styling",
						),
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Background Repeat", "ts_visual_composer_extend" ),
							"param_name"				=> "background_repeat",
							"width"						=> 150,
							"value"						=> array(
								__( "No Repeat", "ts_visual_composer_extend" )		=> "no-repeat",
								__( "Repeat X + Y", "ts_visual_composer_extend" )	=> "repeat",
								__( "Repeat X", "ts_visual_composer_extend" )		=> "repeat-x",
								__( "Repeat Y", "ts_visual_composer_extend" )		=> "repeat-y"
							),
							"description"				=> __( "Select if and how the background image should be repeated.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "background_type", 'value' => 'image' ),
							"group"						=> "Styling",
						),						
						array(
							"type" 						=> "dropdown",
							"heading" 					=> __( "Background Position", "ts_visual_composer_extend" ),
							"param_name" 				=> "background_position",
							"value" 					=> array(
								__( "Center Center", "ts_visual_composer_extend" ) 				=> "center center",
								__( "Center Top", "ts_visual_composer_extend" )					=> "center top",
								__( "Center Bottom", "ts_visual_composer_extend" ) 				=> "center bottom",
								__( "Left Top", "ts_visual_composer_extend" ) 					=> "left top",
								__( "Left Center", "ts_visual_composer_extend" ) 				=> "left center",
								__( "Left Bottom", "ts_visual_composer_extend" ) 				=> "left bottom",
								__( "Right Top", "ts_visual_composer_extend" ) 					=> "right top",
								__( "Right Center", "ts_visual_composer_extend" ) 				=> "right center",
								__( "Right Bottom", "ts_visual_composer_extend" ) 				=> "right bottom",
							),
							"description" 				=> __("Select the position of the background image; will have most effect on smaller screens.", "ts_visual_composer_extend"),
							"dependency"        		=> array( 'element' => "background_type", 'value' => 'image' ),
							"group"						=> "Styling",
						),
						// Content Settings
                        array(
                            "type"                      => "seperator",
                            "param_name"                => "seperator_5h",
                            "seperator"					=> "Content Styling",
							"borderwidth"				=> 2,
							"bordertype"				=> 'dashed',
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group" 			        => "Styling",
                        ),
						array(
							"type" 						=> "advanced_styling",
							"heading" 					=> __("Content Padding", "ts_visual_composer_extend"),
							"param_name" 				=> "theme_content_padding",
							"style_type"				=> "padding",
							"show_main"					=> "false",
							"show_preview"				=> "false",
							"show_width"				=> "true",
							"show_style"				=> "false",
							"show_radius" 				=> "false",					
							"show_color"				=> "false",
							"show_unit_width"			=> "false",
							"show_unit_radius"			=> "false",
							"label_width"				=> "",
							"override_all"				=> "false",
							"default_positions"			=> array(
								//"All"							=> array("string" => __("All", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#000000", "radius" => "0", "unitradius" => "px"),
								"Top"							=> array("string" => __("Top", "ts_visual_composer_extend"),	"width" => "10", "unitwidth" => "px", "style" => "solid", "color" => "#000000", "radius" => "0", "unitradius" => "px"),
								"Right"							=> array("string" => __("Right", "ts_visual_composer_extend"),	"width" => "20", "unitwidth" => "px", "style" => "solid", "color" => "#000000", "radius" => "0", "unitradius" => "px"),
								"Bottom"						=> array("string" => __("Bottom", "ts_visual_composer_extend"),	"width" => "10", "unitwidth" => "px", "style" => "solid", "color" => "#000000", "radius" => "0", "unitradius" => "px"),
								"Left"							=> array("string" => __("Left", "ts_visual_composer_extend"),	"width" => "20", "unitwidth" => "px", "style" => "solid", "color" => "#000000", "radius" => "0", "unitradius" => "px"),
							),
							"value"						=> "padding-top:10px;padding-right:20px;padding-bottom:10px;padding-left:20px;",
							"description"       		=> __( "Define the internal paddings for the element.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group"						=> "Styling",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Content Alignment", "ts_visual_composer_extend" ),
							"param_name"        		=> "theme_content_align",
							"width"             		=> 300,
							"value"             		=> array(
								__( 'Inherit', "ts_visual_composer_extend" )		=> "inherit",
								__( 'Left', "ts_visual_composer_extend" )			=> "left",
								__( 'Right', "ts_visual_composer_extend" )			=> "right",			 
								__( 'Center', "ts_visual_composer_extend" )			=> "center",
								__( 'Justify', "ts_visual_composer_extend" )		=> "justify",
							),
							"description"       		=> __( "Select the default text alignment for the element.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"group"						=> "Styling",
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
							"value"                     => "0",
							"min"                       => "0",
							"max"                       => "200",
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
							"min"                       => "0",
							"max"                       => "200",
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
			function TS_VCSC_Add_HorizontalTimeline_Element_Item() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Single Timeline Element
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					"name"                      		=> __( "TS Horizontal Timeline Item", "ts_visual_composer_extend" ),
					"base"                      		=> "TS_VCSC_Timeline_Horizontal_Item",
					"icon" 	                    		=> "ts-composer-element-icon-horizontimeline-item",
					"category"                  		=> __("VC Extensions", "ts_visual_composer_extend"),
					"description"               		=> __("Place a timeline item element", "ts_visual_composer_extend"),
					"as_child"							=> array('only' => 'TS_VCSC_Timeline_Horizontal_Container'),
					"admin_enqueue_js"            		=> "",
					"admin_enqueue_css"           		=> "",
					"params"                    		=> array(
						// Event Timestamp
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_1a",
							"seperator"					=> "Event Date/Time",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Event: Period", "ts_visual_composer_extend" ),
							"param_name"        		=> "date_detail",
							"value"             		=> array(
								__( "Date Only (Picker)", "ts_visual_composer_extend" )			=> "dateonly",
								__( "Date Only (Quick Entry)", "ts_visual_composer_extend" )	=> "dateonlyquick",
								__( "Date + Time (Picker)", "ts_visual_composer_extend" )		=> "datetime",
								__( "Date + Time (Quick Entry)", "ts_visual_composer_extend" )	=> "datetimequick",
							),
							"save_always" 				=> true,
							"description"       		=> __( "Define how the event shall be added to the timeline.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              		=> "datetime_picker",
							"heading"           		=> __( "Event: Date", "ts_visual_composer_extend" ),
							"param_name"        		=> "date_dateonly",
							"period"					=> "date",
							"year_start"				=> "1700",
							"value"             		=> "",
							"admin_label"				=> true,
							"description"       		=> __( "Select the date for this event.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "date_detail", 'value' => 'dateonly' ),
						),
						array(
							"type"                      => "textfield",
							"heading"                   => __( "Event: Date", "ts_visual_composer_extend" ),
							"param_name"                => "date_dateonly_quick",
							"value"                     => "",
							"admin_label"				=> true,
							"description"               => __( "Provide the date for the event; date must be provided in format mm/dd/yyyy.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "date_detail", 'value' => 'dateonlyquick' ),
						),
						array(
							"type"              		=> "datetime_picker",
							"heading"           		=> __( "Event: Date + Time", "ts_visual_composer_extend" ),
							"param_name"        		=> "date_datetime",
							"period"					=> "datetime",
							"year_start"				=> "1700",
							"value"             		=> "",
							"admin_label"				=> true,
							"description"       		=> __( "Select the date and time for this event.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "date_detail", 'value' => 'datetime' ),
						),
						array(
							"type"                      => "textfield",
							"heading"                   => __( "Event: Date + Time", "ts_visual_composer_extend" ),
							"param_name"                => "date_datetime_quick",
							"value"                     => "",
							"admin_label"				=> true,
							"description"               => __( "Provide the date and time for the event; date and time must be provided in format mm/dd/yyyy hh:mm AM (or PM).", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "date_detail", 'value' => 'datetimequick' ),
						),
						// Time Stamp Format
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_1b",
							"seperator"					=> "Date/Time Stamp",
						),
						array(
							"type"                      => "textfield",
							"heading"                   => __( "Stamp: Format", "ts_visual_composer_extend" ),
							"param_name"                => "timeline_formata",
							"value"                     => "F jS, Y",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"description"               => __( "Provide the format to be used to format the date/time stamp.", "ts_visual_composer_extend" ) . ' <a href="http://php.net/manual/en/function.date.php" target="_blank">' . __( "Learn more here.", "ts_visual_composer_extend" ) . '</a>',
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Stamp: Font Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'stamp_color',
							'value'						=> '#696969',
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							'description' 				=> __( 'Define the color to be used for the event date/time stamp.', 'ts_visual_composer_extend' ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Stamp: Font Size", "ts_visual_composer_extend" ),
							"param_name"                => "stamp_size",
							"value"                     => "16",
							"min"                       => "12",
							"max"                       => "22",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define the font size for the event date/time stamp.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              		=> "fontsmanager",
							"heading"           		=> __( "Stamp: Font Family", "ts_visual_composer_extend" ),
							"param_name"        		=> "stamp_family",
							"value"             		=> "",
							"default"					=> "true",
							"connector"					=> "stamp_font",
							"description"       		=> __( "Select the font to be used for the event date/time stamp.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              		=> "hidden_input",
							"param_name"        		=> "stamp_font",
							"value"             		=> "",
						),
						// Event Title
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_2",
							"seperator"					=> "Event Title",
							"group" 					=> "Title",
						),
						array(
							"type"              		=> "textfield",
							"heading"          	 		=> __( "Title: Text", "ts_visual_composer_extend" ),
							"param_name"        		=> "event_title",
							"value"             		=> "",
							"admin_label"				=> true,
							"group" 					=> "Title",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Event: Decoration", "ts_visual_composer_extend" ),
							"param_name"        		=> "event_deco",
							"value"             		=> array(
								__( "None", "ts_visual_composer_extend" )					=> "none",
								__( "Font Icon", "ts_visual_composer_extend" )				=> "icon",
								__( "Icon Image", "ts_visual_composer_extend" )				=> "image",
							),
							"description"       		=> __( "Define if and what type of decoration should be added to the even title.", "ts_visual_composer_extend" ),
							"group" 					=> "Title",
						),
						array(
							"type" 						=> "icons_panel",
							'heading' 					=> __( 'Title: Font Icon', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'event_icon',
							'value'						=> '',
							"settings" 					=> array(
								"emptyIcon" 					=> false,
								'emptyIconValue'				=> 'transparent',
								"type" 							=> 'extensions',
							),
							"description"       		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorVisualSelector == "true" ? __( "Select the icon for the event title.", "ts_visual_composer_extend" ) : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconSelectorString),
							"dependency"        		=> array( 'element' => "event_deco", 'value' => 'icon' ),
							"group" 					=> "Title",
						),
						array(
							"type"              		=> "attach_image",
							"heading"           		=> __( "Title: Icon Image", "ts_visual_composer_extend" ),
							"param_name"        		=> "event_image",
							"value"             		=> "",
							"description"       		=> __( "Image must have equal dimensions for scaling purposes (i.e. 100x100).", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "event_deco", 'value' => 'image' ),
							"group" 					=> "Title",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Title: Icon Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'title_iconcolor',
							'value'						=> '#696969',
							'description' 				=> __( 'Define the color to be used for the title icon.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"        		=> array( 'element' => "event_deco", 'value' => 'icon' ),
							"group" 					=> "Title",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Title: Font Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'title_textcolor',
							'value'						=> '#696969',
							'description' 				=> __( 'Define the color to be used for the title text.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 			        => "Title",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Title: Font Weight", "ts_visual_composer_extend" ),
							"param_name"        		=> "title_weight",
							"width"             		=> 150,
							"value"             		=> array(
								__( 'Default', "ts_visual_composer_extend" )  => "inherit",
								__( 'Bold', "ts_visual_composer_extend" )     => "bold",
								__( 'Bolder', "ts_visual_composer_extend" )   => "bolder",
								__( 'Normal', "ts_visual_composer_extend" )   => "normal",
								__( 'Light', "ts_visual_composer_extend" )    => "300",
							),
							"description"       		=> __( "Select the font weight for the title text.", "ts_visual_composer_extend" ),
							"default"					=> "bold",
							"standard"					=> "bold",
							"std"						=> "bold",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 			        => "Title",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Title: Font Style", "ts_visual_composer_extend" ),
							"param_name"        		=> "title_style",
							"width"             		=> 300,
							"value"             		=> array(
								__( 'Normal', "ts_visual_composer_extend" )      	=> "normal",
								__( 'Italic', "ts_visual_composer_extend" )       	=> "italic",			 
								__( 'Oblique', "ts_visual_composer_extend" )		=> "oblique",
							),
							"description"       		=> __( "Select the default font style for the title text.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 			        => "Title",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Title: Text Transform", "ts_visual_composer_extend" ),
							"param_name"        		=> "title_transform",
							"width"             		=> 300,
							"value"             		=> array(
								__( 'None', "ts_visual_composer_extend" )			=> "none",
								__( 'Capitalize', "ts_visual_composer_extend" )		=> "capitalize",			 
								__( 'Uppercase', "ts_visual_composer_extend" )		=> "uppercase",
								__( 'Lowercase', "ts_visual_composer_extend" )		=> "lowercase",
							),
							"description"       		=> __( "Select the default text transform for the title text.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 			        => "Title",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Title: Text Decoration", "ts_visual_composer_extend" ),
							"param_name"        		=> "title_decoration",
							"width"             		=> 300,
							"value"             		=> array(
								__( 'None', "ts_visual_composer_extend" )       	=> "none",
								__( 'Underline', "ts_visual_composer_extend" )		=> "underline",			 
								__( 'Overline', "ts_visual_composer_extend" )		=> "overline",
								__( 'Line Through', "ts_visual_composer_extend" )	=> "line-through",
							),
							"description"       		=> __( "Select the default font decoration for the title text.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 			        => "Title",
						),	
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Title: Font Size", "ts_visual_composer_extend" ),
							"param_name"                => "title_size",
							"value"                     => "36",
							"min"                       => "16",
							"max"                       => "48",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define the font size for the title text.", "ts_visual_composer_extend" ),
							"group" 			        => "Title",
						),
						array(
							"type"              		=> "fontsmanager",
							"heading"           		=> __( "Title: Font Family", "ts_visual_composer_extend" ),
							"param_name"        		=> "title_family",
							"value"             		=> "",
							"default"					=> "true",
							"connector"					=> "title_font",
							"description"       		=> __( "Select the font to be used for the title text.", "ts_visual_composer_extend" ),
							"group" 					=> "Title",
						),
						array(
							"type"              		=> "hidden_input",
							"param_name"        		=> "title_font",
							"value"             		=> "",
							"group" 					=> "Title",
						),
						// Event Content
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_3",
							"seperator"					=> "Event Content",
							"group" 					=> "Content",
						),
						array(
							"type"						=> "textarea_html",
							"heading"					=> __( "Content: Text", "ts_visual_composer_extend" ),
							"param_name"				=> "content",
							"value"						=> "",
							"group" 					=> "Content",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Content: Font Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'content_color',
							'value'						=> '#959595',
							'description' 				=> __( 'Define the color to be used for the event content text.', 'ts_visual_composer_extend' ),
							"group" 					=> "Content",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Content: Font Size", "ts_visual_composer_extend" ),
							"param_name"                => "content_size",
							"value"                     => "14",
							"min"                       => "12",
							"max"                       => "28",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define the font size for the event content text.", "ts_visual_composer_extend" ),
							"group" 					=> "Content",
						),
						array(
							"type"              		=> "fontsmanager",
							"heading"           		=> __( "Content: Font Family", "ts_visual_composer_extend" ),
							"param_name"        		=> "content_family",
							"value"             		=> "",
							"default"					=> "true",
							"connector"					=> "content_font",
							"description"       		=> __( "Select the font to be used for the content text.", "ts_visual_composer_extend" ),
							"group" 					=> "Content",
						),
						array(
							"type"              		=> "hidden_input",
							"param_name"        		=> "content_font",
							"value"             		=> "",
							"group" 					=> "Content",
						),
						// Event Media
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_4",
							"seperator"					=> "Event Media",
							"group" 					=> "Media",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Media: Type", "ts_visual_composer_extend" ),
							"param_name"        		=> "media_type",
							"value"             		=> array(
								__( "None", "ts_visual_composer_extend" )					=> "none",
								__( "Single Image", "ts_visual_composer_extend" )			=> "image",
								__( "YouTube Video", "ts_visual_composer_extend" )			=> "youtube",
								__( "Vimeo Video", "ts_visual_composer_extend" )			=> "vimeo",
								__( "DailyMotion Video", "ts_visual_composer_extend" )		=> "dailymotion",
							),
							"description"       		=> __( "Define if and what type of featured media you want to add to the event.", "ts_visual_composer_extend" ),
							"group" 					=> "Media",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Media: Position", "ts_visual_composer_extend" ),
							"param_name"        		=> "media_position",
							"value"             		=> array(
								__( "Left", "ts_visual_composer_extend" )					=> "left",
								__( "Right", "ts_visual_composer_extend" )					=> "right",
							),
							"description"       		=> __( "Define how the featured media should be positioned in relation to the event content.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "media_type", 'value' => 'image' ),
							"group" 					=> "Media",
						),
						array(
							"type"              		=> "attach_image",
							"heading"           		=> __( "Media: Image", "ts_visual_composer_extend" ),
							"param_name"        		=> "media_image",
							"value"             		=> "",
							"description"       		=> __( "Select the image you want to use as featured media.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "media_type", 'value' => 'image' ),
							"group" 					=> "Media",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "Media: YouTube", "ts_visual_composer_extend" ),
							"param_name"        		=> "media_youtube",
							"value"             		=> "",
							"description"       		=> __( "Please provide the URL to the YouTube video you want to use as featured media.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "media_type", 'value' => 'youtube' ),
							"group" 					=> "Media",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "Media: Vimeo", "ts_visual_composer_extend" ),
							"param_name"        		=> "media_vimeo",
							"value"             		=> "",
							"description"       		=> __( "Please provide the URL to the Vimeo video you want to use as featured media.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "media_type", 'value' => 'vimeo' ),
							"group" 					=> "Media",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "Media: DailyMotion", "ts_visual_composer_extend" ),
							"param_name"        		=> "media_dailymotion",
							"value"             		=> "",
							"description"       		=> __( "Please provide the URL to the DailyMotion video you want to use as featured media.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "media_type", 'value' => 'dailymotion' ),
							"group" 					=> "Media",
						),
						// Event Background
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_5",
							"seperator"					=> "Event Background",
							"group" 					=> "Background",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Background Type", "ts_visual_composer_extend" ),
							"param_name"        		=> "background_type",
							"width"             		=> 300,
							"value"             		=> array(
								__( "Solid Color", "ts_visual_composer_extend" )				=> "color",
								__( "Transparent Background", "ts_visual_composer_extend" )		=> "transparent",
								__( "Gradient Background", "ts_visual_composer_extend" )		=> "gradient",
								__( "Background Pattern", "ts_visual_composer_extend" )			=> "pattern",
								__( "Custom Image", "ts_visual_composer_extend" )				=> "image",
							),
							"description"       		=> __( "Select the background type for the timeline section.", "ts_visual_composer_extend" ),
							"group" 			        => "Background",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "background_color",
							"value"             		=> "#ffffff",
							"description"       		=> __( "Select the background color for the timeline section.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "background_type", 'value' => array('color', 'image') ),
							"group"						=> "Background",
						),			
						array(
							"type"						=> "advanced_gradient",
							"heading"					=> __("Gradient Background", "ts_visual_composer_extend"),						
							"param_name"				=> "background_gradient",
							"description"				=> __('Use the controls above to create a custom gradient background for the timeline section.', 'ts_visual_composer_extend'),
							"dependency"        		=> array( 'element' => "background_type", 'value' => 'gradient' ),
							"group"						=> "Background",
						),			
						array(
							"type"              		=> "background",
							"heading"           		=> __( "Background Pattern", "ts_visual_composer_extend" ),
							"param_name"        		=> "background_pattern",
							"height"            		=> 200,
							"pattern"           		=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Background_List,
							"value"						=> "",
							"encoding"          		=> "false",
							"empty"						=> "true",
							"description"       		=> __( "Select the background pattern for the timeline section.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "background_type", 'value' => 'pattern' ),
							"group"						=> "Background",
						),
						array(
							"type"              		=> "attach_image",
							"heading"           		=> __( "Background Image", "ts_visual_composer_extend" ),
							"param_name"        		=> "background_image",
							"value"             		=> "",
							"description"       		=> __( "Select an image or pattern to be used as background for the timeline section.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "background_type", 'value' => 'image' ),
							"group"						=> "Background",
						),		
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Background Size", "ts_visual_composer_extend" ),
							"param_name"				=> "background_size",
							"width"						=> 150,
							"value"						=> array(
								__( "Cover", "ts_visual_composer_extend" ) 			=> "cover",
								__( "150%", "ts_visual_composer_extend" )			=> "150%",
								__( "200%", "ts_visual_composer_extend" )			=> "200%",
								__( "Contain", "ts_visual_composer_extend" ) 		=> "contain",
								__( "Initial", "ts_visual_composer_extend" ) 		=> "initial",
								__( "Auto", "ts_visual_composer_extend" ) 			=> "auto",
							),
							"description"				=> __( "Select how the custom background image should be sized.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "background_type", 'value' => 'image' ),
							"group"						=> "Background",
						),
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Background Repeat", "ts_visual_composer_extend" ),
							"param_name"				=> "background_repeat",
							"width"						=> 150,
							"value"						=> array(
								__( "No Repeat", "ts_visual_composer_extend" )		=> "no-repeat",
								__( "Repeat X + Y", "ts_visual_composer_extend" )	=> "repeat",
								__( "Repeat X", "ts_visual_composer_extend" )		=> "repeat-x",
								__( "Repeat Y", "ts_visual_composer_extend" )		=> "repeat-y"
							),
							"description"				=> __( "Select if and how the background image should be repeated.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "background_type", 'value' => 'image' ),
							"group"						=> "Background",
						),						
						array(
							"type" 						=> "dropdown",
							"heading" 					=> __( "Background Position", "ts_visual_composer_extend" ),
							"param_name" 				=> "background_position",
							"value" 					=> array(
								__( "Center Center", "ts_visual_composer_extend" ) 				=> "center center",
								__( "Center Top", "ts_visual_composer_extend" )					=> "center top",
								__( "Center Bottom", "ts_visual_composer_extend" ) 				=> "center bottom",
								__( "Left Top", "ts_visual_composer_extend" ) 					=> "left top",
								__( "Left Center", "ts_visual_composer_extend" ) 				=> "left center",
								__( "Left Bottom", "ts_visual_composer_extend" ) 				=> "left bottom",
								__( "Right Top", "ts_visual_composer_extend" ) 					=> "right top",
								__( "Right Center", "ts_visual_composer_extend" ) 				=> "right center",
								__( "Right Bottom", "ts_visual_composer_extend" ) 				=> "right bottom",
							),
							"description" 				=> __("Select the position of the background image; will have most effect on smaller screens.", "ts_visual_composer_extend"),
							"dependency"        		=> array( 'element' => "background_type", 'value' => 'image' ),
							"group"						=> "Background",
						),		
						// Tooltip Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_6",
							"seperator"            		=> "Tooltip Settings",
							"group" 					=> "Tooltip",
						),
						/*array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Tooltip Addition", "ts_visual_composer_extend" ),
							"param_name"        		=> "tooltip_usage",
							"value"            	 		=> "false",
							"description"       		=> __( "Switch the toggle if you want to add an optional tooltip to the element.", "ts_visual_composer_extend" ),
							"group" 					=> "Tooltip",
						),*/						
						array(
							"type" 						=> "dropdown",
							"heading" 					=> __( "Tooltip Addition", "ts_visual_composer_extend" ),
							"param_name" 				=> "tooltip_usage",
							"value" 					=> array(
								__( "No Tooltip", "ts_visual_composer_extend" ) 				=> "false",
								__( "Use Event Title", "ts_visual_composer_extend" )			=> "title",
								__( "Custom Tooltip Content", "ts_visual_composer_extend" )		=> "true",
							),
							"description" 				=> __("Select if you want to add an optional tooltip to the event bullet on the timeline.", "ts_visual_composer_extend"),
							"group" 					=> "Tooltip",
						),						
						array(
							"type"              		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorBase64TinyMCE == "true" ? "wysiwyg_base64" : "textarea_raw_html"),
							"heading"           		=> __( "Tooltip Content", "ts_visual_composer_extend" ),
							"param_name"        		=> "tooltip_content",
							"minimal"					=> "true",
							"value"             		=> base64_encode(""),
							"description"      	 		=> __( "Enter the tooltip content for the element; basic HTML code can be used.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "tooltip_usage", 'value' => 'true' ),
							"group" 					=> "Tooltip",
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Tooltip Arrow", "ts_visual_composer_extend" ),
							"param_name"        		=> "tooltip_arrow",
							"value"             		=> "true",
							"description"       		=> __( "Switch the toggle to either show or hide the tooltip arrow.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "tooltip_usage", 'value' => array('title', 'datetime', 'true') ),
							"group" 					=> "Tooltip",
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Tooltip Animation", "ts_visual_composer_extend" ),
							"param_name"		   	 	=> "tooltip_animation",
							"value"                 	=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Animations,
							"description"		    	=> __( "Select how the tooltip entry and exit should be animated once triggered.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"        		=> array( 'element' => "tooltip_usage", 'value' => array('title', 'datetime', 'true') ),
							"group"						=> "Tooltip",
						),	
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Tooltip Style", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltip_style",
							"value"             		=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Styles,
							"description"				=> __( "Select the tooltip style.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"        		=> array( 'element' => "tooltip_usage", 'value' => array('title', 'datetime', 'true') ),
							"group" 					=> "Tooltip",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Tooltip Font Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "tooltip_color",
							"value"             		=> "#ffffff",
							"description"       		=> __( "Define the custom font color for the tooltip.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "tooltip_style", 'value' => array('tooltipster-custom', 'ts-simptip-style-custom') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Tooltip",
						),		
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Tooltip Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "tooltip_background",
							"value"             		=> "#000000",
							"description"       		=> __( "Define the custom background color for the tooltip.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "tooltip_style", 'value' => array('tooltipster-custom', 'ts-simptip-style-custom') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Tooltip",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Tooltip Border Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "tooltip_border",
							"value"             		=> "#000000",
							"description"       		=> __( "Define the custom border color for the tooltip.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "tooltip_style", 'value' => array('tooltipster-custom', 'ts-simptip-style-custom') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Tooltip",
						),	
						array(
							"type"						=> "nouislider",
							"heading"					=> __( "Tooltip X-Offset", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltip_offsetx",
							"value"						=> "0",
							"min"						=> "-100",
							"max"						=> "100",
							"step"						=> "1",
							"unit"						=> 'px',
							"description"				=> __( "Define an optional X-Offset for the tooltip position.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "tooltip_usage", 'value' => array('title', 'datetime', 'true') ),
							"group" 					=> "Tooltip",
						),
						array(
							"type"						=> "nouislider",
							"heading"					=> __( "Tooltip Y-Offset", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltip_offsety",
							"value"						=> "20",
							"min"						=> "-100",
							"max"						=> "100",
							"step"						=> "1",
							"unit"						=> 'px',
							"description"				=> __( "Define an optional Y-Offset for the tooltip position.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "tooltip_usage", 'value' => array('title', 'datetime', 'true') ),
							"group" 					=> "Tooltip",
						),
						// Other Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_7",
							"seperator"					=> "Other Settings",
							"group"						=> "Other",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "Define ID Name", "ts_visual_composer_extend" ),
							"param_name"        		=> "el_id",
							"value"             		=> "",
							"description"       		=> __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
							"group"						=> "Other",
						),
						array(
							"type"                  	=> "tag_editor",
							"heading"           		=> __( "Extra Class Names", "ts_visual_composer_extend" ),
							"param_name"            	=> "el_class",
							"value"                 	=> "",
							"description"      			=> __( "Enter additional class names for the element.", "ts_visual_composer_extend" ),
							"group" 					=> "Other",
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
	if ((class_exists('WPBakeryShortCodesContainer')) && (!class_exists('WPBakeryShortCode_TS_VCSC_Timeline_Horizontal_Container'))) {
		class WPBakeryShortCode_TS_VCSC_Timeline_Horizontal_Container extends WPBakeryShortCodesContainer {};
	}
	if ((class_exists('WPBakeryShortCode')) && (!class_exists('WPBakeryShortCode_TS_VCSC_Timeline_Horizontal_Item'))) {
		class WPBakeryShortCode_TS_VCSC_Timeline_Horizontal_Item extends WPBakeryShortCode {};
	}
	// Initialize "TS Horizontal Timeline" Class
	if (class_exists('TS_HorizontalTimeline')) {
		$TS_HorizontalTimeline = new TS_HorizontalTimeline;
	}
?>