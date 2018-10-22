<?php
	if (!class_exists('TS_Image_Hotspot')){
		class TS_Image_Hotspot {
			function __construct() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						$this->TS_VCSC_Add_Image_Hotspot_Lean();
					} else if (function_exists('vc_map')) {
						add_action('init',									array($this, 'TS_VCSC_Add_Image_Hotspot_Element_Container'), 9999999);
						add_action('init',									array($this, 'TS_VCSC_Add_Image_Hotspot_Element_Single'), 9999999);
					}
				} else {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_Image_Hotspot_Lean'), 9999999);
					} else if (function_exists('vc_map')) {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_Image_Hotspot_Element_Container'), 9999999);
						add_action('admin_init',							array($this, 'TS_VCSC_Add_Image_Hotspot_Element_Single'), 9999999);
					}
				}
				if ((is_admin() == false) || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAJAX == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAlways == "true")) {
					add_shortcode('TS_VCSC_Image_Hotspot_Single',			array($this, 'TS_VCSC_Image_Hotspot_Single'));
					add_shortcode('TS_VCSC_Image_Hotspot_Container',		array($this, 'TS_VCSC_Image_Hotspot_Container'));
				}
			}
			
			// Register Element(s) via LeanMap
			function TS_VCSC_Add_Image_Hotspot_Lean() {
				vc_lean_map('TS_VCSC_Image_Hotspot_Container', 				array($this, 'TS_VCSC_Add_Image_Hotspot_Element_Container'), null);
				vc_lean_map('TS_VCSC_Image_Hotspot_Single', 				array($this, 'TS_VCSC_Add_Image_Hotspot_Element_Single'), null);
			}
	
			// Single Hotspot
			function TS_VCSC_Image_Hotspot_Single ($atts, $content = null) {
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();
			
				extract( shortcode_atts( array(
					'hotspot_overlay'				=> '',
					'hotspot_positions'				=> '0,0',
					'hotspot_widthrule'				=> 'none', // none, widthmin, widthmax, widthcorridor
					'hotspot_widthmin'				=> 240,
					'hotspot_widthmax'				=> 1920,
					'hotspot_show_title'			=> 'true',
					'hotspot_title'					=> '',
					'hotspot_event'					=> 'none',
					'hotspot_pulse'					=> 'true',

					'hotspot_content'				=> 'empty', // empty, icon, string, image
					'hotspot_icon'					=> '',
					'hotspot_string'				=> '',
					'hotspot_graphic'				=> '',
					
					'viewport_animation'			=> '',
					'viewport_name'					=> '',
					'viewport_delay'				=> 0,
					
					'hotspot_color_dot'				=> '#45453f',
					'hotspot_color_content'			=> '#ffffff',
					'hotspot_color_circle'			=> '#f7f14c',
					'hotspot_color_pulse'			=> '#fff601',
					
					'hotspot_editor'				=> 'false',
					'hotspot_text'					=> '',
					'hotspot_picture'				=> '',
					'hotspot_link'					=> '',				
					'hotspot_video_link'			=> '',
					'hotspot_video_auto'			=> 'true',
					'hotspot_video_related'			=> 'false',
					'hotspot_toggle'				=> 'false',
					
					'video_mp4_remote'				=> '',
					'video_ogg_remote'				=> '',
					'video_webm_remote'				=> '',
					'video_poster'					=> '',
					'video_theme'					=> 'maccaco',
					'video_auto'					=> 'true',
					'video_fullscreen'				=> 'true',
					'video_volume'					=> 50,
					
					'audio_bar_only'				=> 'false',
					'audio_bar_width'				=> 480,
					'audio_mp3_remote'				=> '',
					'audio_ogg_remote'				=> '',
					'audio_poster'					=> '',
					'audio_theme'					=> 'maccaco',
					'audio_auto'					=> 'true',
					'audio_loop'					=> 'false',
					'audio_volume'					=> 50,
					
					'height'						=> 500,
					'width'							=> 300,
					'content_style'					=> '',
					
					'content_wpautop'				=> 'false',
					'content_image_responsive'		=> 'true',
					'content_image_height'			=> 'height: 100%;',
					'content_image_width_r'			=> 100,
					'content_image_width_f'			=> 300,
					'content_image_size'			=> 'large',
					'content_tooltip_html'			=> 'true',
					'content_tooltip_title'			=> '',
					'content_tooltip_content'		=> '',
					'content_tooltip_content_html'	=> '',
					'content_tooltip_position'		=> 'ts-simptip-position-top',
					'content_tooltip_style'			=> 'ts-simptip-style-black',
					'content_tooltip_show'			=> 'false',
					'content_tooltip_trigger'		=> 'hover',
					'content_tooltip_animation'		=> 'swing',
					'tooltipster_offsetx'			=> 0,
					'tooltipster_offsety'			=> 0,
					
					'lightbox_group'				=> 'false',
					'lightbox_group_name'			=> '',
					'lightbox_size'					=> 'full',
					'lightbox_effect'				=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LightboxDefaultAnimation,
					'lightbox_speed'				=> 5000,
					'lightbox_social'				=> 'true',
					'lightbox_backlight_choice'		=> 'predefined',
					'lightbox_backlight_color'		=> '#0084E2',
					'lightbox_backlight_custom'		=> '#000000',
					
					'lightbox_custom_padding'		=> 15,
					'lightbox_custom_background'	=> 'none',
					'lightbox_background_image'		=> '',
					'lightbox_background_size'		=> 'cover',
					'lightbox_background_repeat'	=> 'no-repeat',
					'lightbox_background_color'		=> '#ffffff',
					
					'el_id' 						=> '',
					'el_class'                  	=> '',
					'css'							=> '',
				), $atts ));
				
				$hotspot_random                    	= mt_rand(999999, 9999999);
				$wpautop 							= ($content_wpautop == "true" ? true : false);
				$modal_image						= array();

				// Hotspot Content Image
				if ($hotspot_content == "image") {
					if (!empty($hotspot_graphic)) {
						$hotspot_graphic 			= wp_get_attachment_image_src($hotspot_graphic, 'thumbnail');
					} else {
						$hotspot_content			= "empty";
					}
				}
				
				if ($viewport_animation != '') {
					wp_enqueue_style('ts-extend-animations');
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndWaypoints == "true") {
						if (wp_script_is('waypoints', $list = 'registered')) {
							wp_enqueue_script('waypoints');
						} else {
							wp_enqueue_script('ts-extend-waypoints');
						}
					}
				}
				
				if (!empty($el_id)) {
					$modal_id						= $el_id;
				} else {
					$modal_id						= 'ts-vcsc-hotspot-trigger-' . mt_rand(999999, 9999999);
				}
				
				// Check for Front End Editor
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					$hotspot_frontend				= "true";
				} else {
					$hotspot_frontend				= "false";
				}
				
				// iFrame / Link
				if (($hotspot_event == "link") || ($hotspot_event == "iframe")) {
					$link 							= TS_VCSC_Advancedlinks_GetLinkData($hotspot_link);
					$a_href							= $link['url'];
					$a_title 						= $link['title'];
					$a_target 						= $link['target'];
				}
				// Image (Switch + Hotspot)
				if (($hotspot_event == "image") || ($hotspot_event == "switch") || ($hotspot_event == "hotspot")) {
					if ($hotspot_event == "hotspot") {
						if (!empty($hotspot_overlay)) {
							$modal_image			= wp_get_attachment_image_src($hotspot_overlay, 'full');
						}
					} else {
						if (!empty($hotspot_picture)) {
							$modal_image			= wp_get_attachment_image_src($hotspot_picture, 'full');
						}
					}
				}			
				// YouTube Video
				if ($hotspot_event == "youtube") {
					if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $hotspot_video_link)) {
						$hotspot_video_link		= $hotspot_video_link;
					} else {
						$hotspot_video_link		= 'https://www.youtube.com/watch?v=' . $hotspot_video_link;
					}
				}
				// DailyMotion Video
				if ($hotspot_event == "dailymotion") {
					if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $hotspot_video_link)) {
						$hotspot_video_link	= $hotspot_video_link;
					} else {			
						$hotspot_video_link	= $hotspot_video_link;
					}
				}
	
				// Tooltip
				if ($content_tooltip_show == "true") {
					$popup_tooltipclasses			= 'ts-use-tooltipster-tooltip';
				} else {
					$popup_tooltipclasses			= 'ts-has-tooltipster-tooltip';
				}
				if (($hotspot_event != 'none') && ($content_tooltip_trigger == "click")) {
					$content_tooltip_trigger		= 'hover';
				}		
				if ($content_tooltip_trigger == "hover") {
					$popup_tooltipclasses			.= ' ts-use-tooltipster-hover';
				} else if ($content_tooltip_trigger == "click") {
					$popup_tooltipclasses			.= ' ts-use-tooltipster-click';
				}				
				$content_tooltip_position			= TS_VCSC_TooltipMigratePosition($content_tooltip_position);
				$content_tooltip_style				= TS_VCSC_TooltipMigrateStyle($content_tooltip_style);
				if ($content_tooltip_html == "true") {
					if (strlen($content_tooltip_content_html) != 0) {
						$Tooltip_Content			= 'data-tooltipster-html="true" data-tooltipster-title="' . $content_tooltip_title . '" data-tooltipster-text="' . strip_tags($content_tooltip_content_html) . '" data-tooltipster-image="" data-tooltipster-position="' . $content_tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="' . $content_tooltip_style . '" data-tooltipster-animation="' . $content_tooltip_animation . '" data-tooltipster-trigger="' . $content_tooltip_trigger . '" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . $tooltipster_offsety . '"';
						$Tooltip_Class				= $popup_tooltipclasses;
					} else {
						$Tooltip_Content			= '';
						$Tooltip_Class				= '';
					}
				} else {
					if (strlen($content_tooltip_content) != 0) {
						$Tooltip_Content			= 'data-tooltipster-html="false" data-tooltipster-title="' . $content_tooltip_title . '" data-tooltipster-text="' . str_replace('<br/>', ' ', $content_tooltip_content) . '" data-tooltipster-image="" data-tooltipster-position="' . $content_tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="' . $content_tooltip_style . '" data-tooltipster-animation="' . $content_tooltip_animation . '" data-tooltipster-trigger="' . $content_tooltip_trigger . '" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . $tooltipster_offsety . '"';
						$Tooltip_Class				= $popup_tooltipclasses;
					} else {
						$Tooltip_Content			= '';
						$Tooltip_Class				= '';
					}
				}
				
				// Dimensions
				if ($content_image_responsive == "true") {
					$image_dimensions				= 'width: 100%; height: auto;';
					$parent_dimensions				= 'width: ' . $content_image_width_r . '%; ' . $content_image_height . '';
				} else {
					$image_dimensions				= 'width: 100%; height: auto;';
					$parent_dimensions				= 'width: ' . $content_image_width_f . 'px; ' . $content_image_height . '';
				}
				
				// Viewport Animation
				if ($viewport_animation != '') {
					$viewport_class					= 'ts-image-hotspot-viewport';
					$viewport_data					= 'data-type="viewport" data-animation="' . $viewport_animation . '" data-delay="' . $viewport_delay . '" data-opacity="1"';
				} else {
					$viewport_class					= 'ts-image-hotspot-standard';
					$viewport_data					= '';
				}
				
				// Backlight Color
				if ($lightbox_backlight_choice == "predefined") {
					$lightbox_backlight_selection	= $lightbox_backlight_color;
				} else {
					$lightbox_backlight_selection	= $lightbox_backlight_custom;
				}
		
				// Custom Width / Height
				$lightbox_dimensions				= '';
				
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
				
				$hotspot_positions					= explode(",", $hotspot_positions);

				$style_hotspot_dot					= 'background-color: ' . $hotspot_color_dot . '; border-color: ' . $hotspot_color_circle . ';';
				$style_hotspot_pulse				= 'border-color: ' . $hotspot_color_pulse . ';';
	
				$output 							= '';
				
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-image-hotspot-single ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Image_Hotspot_Single', $atts);
				} else {
					$css_class						= 'ts-image-hotspot-single ' . $el_class;
				}
				
				$output .= '<div class="ts-image-hotspot-single-container ' . ($hotspot_frontend == "false" ? "ts-image-hotspot-single-hidden" : "") . '" data-width-rule="' . $hotspot_widthrule . '" data-width-min="' . $hotspot_widthmin . '" data-width-max="' . $hotspot_widthmax . '" style="' . ($hotspot_frontend == "false" ? "display: none;" : "") . '">';
					if ($hotspot_frontend == "false") {
						$output .= '<div class="' . $css_class . ' ts-image-hotspot-single-' . $hotspot_content . ' ' . $viewport_class . '" ' . $viewport_data . ' style="left: ' . $hotspot_positions[0] . '%; top: ' . $hotspot_positions[1] . '%;">';
							if (($hotspot_event != "none") && ($hotspot_event == "popup")) {
								// Modal Popup
								$output .= '<a id="' . $modal_id . '-trigger" href="#' . $modal_id . '" class="ts-image-hotspot-trigger ' . $modal_id . '-parent nch-holder nch-lightbox-modal no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $hotspot_title . '" data-type="html" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-share="0" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
							} else if (($hotspot_event != "none") && ($hotspot_event == "information")) {
								// Information Section
								$output .= '<a id="' . $modal_id . '-trigger" href="javascript:void(0)" class="ts-image-hotspot-trigger ' . $modal_id . '-parent ts-image-hotspot-showinfo no-ajaxy" style="" data-information="' . $modal_id . '">';
							} else if (($hotspot_event != "none") && ($hotspot_event == "iframe")) {
								// iFrame Popup
								$output .= '<a id="' . $modal_id . '-trigger" href="' . $a_href . '" target="' . $a_target . '" class="ts-image-hotspot-trigger ' . $modal_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $hotspot_title . '" data-type="iframe" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-share="0" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
							} else if (($hotspot_event != "none") && ($hotspot_event == "image")) {
								// Custom Image Popup
								$output .= '<a id="' . $modal_id . '-trigger" href="' . $modal_image[0] . '" class="ts-image-hotspot-trigger ' . $modal_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $hotspot_title . '" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
							} else if (($hotspot_event != "none") && ($hotspot_event == "hotspot")) {
								// Hotspot Image Popup
								$output .= '<a id="' . $modal_id . '-trigger" href="' . $modal_image[0] . '" class="ts-image-hotspot-trigger ' . $modal_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $hotspot_title . '" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
							} else if (($hotspot_event != "none") && ($hotspot_event == "switch")) {
								// Image Switch
								$output .= '<div id="' . $modal_id . '-trigger" class="ts-image-hotspot-trigger ' . $modal_id . '-parent ts-image-hotspot-switch" ' . $lightbox_dimensions . ' style="" data-title="' . $hotspot_title . '" data-image="' . $modal_image[0] . '" data-toggle="' . $hotspot_toggle . '">';
							} else if (($hotspot_event != "none") && ($hotspot_event == "youtube")) {
								// YouTube Popup
								$output .= '<a id="' . $modal_id . '-trigger" href="' . $hotspot_video_link .'" class="ts-image-hotspot-trigger ' . $modal_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $hotspot_title . '" data-related="' . $hotspot_video_related .'" data-videoplay="' . $hotspot_video_auto .'" data-type="youtube" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-share="0" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
							} else if (($hotspot_event != "none") && ($hotspot_event == "vimeo")) {
								// Vimeo Popup
								$output .= '<a id="' . $modal_id . '-trigger" href="' . $hotspot_video_link . '" class="ts-image-hotspot-trigger ' . $modal_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $hotspot_title . '" data-videoplay="' . $hotspot_video_auto . '" data-type="vimeo" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-share="0" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
							} else if (($hotspot_event != "none") && ($hotspot_event == "dailymotion")) {
								// DailyMotion Popup
								$output .= '<a id="' . $modal_id . '-trigger" href="' . $hotspot_video_link .'" class="ts-image-hotspot-trigger ' . $modal_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $hotspot_title . '" data-videoplay="' . $hotspot_video_auto . '" data-type="dailymotion" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-share="0" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
							} else if (($hotspot_event != "none") && ($hotspot_event == "html5video")) {
								// HTML5 Video Popup
								$poster_image		= wp_get_attachment_image_src($video_poster, 'full');
								if ($poster_image != false) {
									$poster_image	= $poster_image[0];
								} else {
									$poster_image	= TS_VCSC_GetResourceURL("images/defaults/default_html5.jpg");
								}
								$iframe_data		= 'data-videoid="projekktor' . $hotspot_random . '"
													data-videotheme="' . $video_theme . '"
													data-videoholder="' . $modal_id . '_iframe"
													data-videoautoplay="' . $video_auto .'"
													data-videoautostop="true"
													data-videorepeat="false"
													data-videofullscreen="' . $video_fullscreen . '"
													data-videoposter="' . $poster_image . '"
													data-videoposterfit="aspectratio"
													data-videotitle="' . $hotspot_title . '"
													data-videoobjectfit="aspectratio"
													data-logoshow="logonone"
													data-logoimage=""
													data-logoheight="50"
													data-logoopacity="50"
													data-logoposition="left"
													data-logourl=""
													data-logotitle=""
													data-logotarget=""
													data-videomp4="' . $video_mp4_remote . '"
													data-videowebm="' . $video_webm_remote . '"
													data-videoogg="' . $video_ogg_remote . '"
													data-videovolume="' . $video_volume . '"
													data-videolightbox="true"
													data-videoshare="false"
													data-videofallback="' . TS_VCSC_GetResourceURL("projekktor/swf/StrobeMediaPlayback/StrobeMediaPlayback.swf") . '"
													data-videoscreensize="50"';
								$output .= '<a id="' . $modal_id . '-trigger" href="' . TS_VCSC_GetResourceURL("projekktor/iframe-video.html") . '" ' . $iframe_data . ' class="ts-image-hotspot-trigger ' . $modal_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $hotspot_title . '" data-type="video" data-thumbnail="' . $poster_image . '" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-share="0" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
							} else if (($hotspot_event != "none") && ($hotspot_event == "html5audio")) {
								// HTML5 Audio Popup
								$poster_image		= wp_get_attachment_image_src($audio_poster, 'full');
								if ($poster_image != false) {
									$poster_image	= $poster_image[0];
								} else {
									$poster_image	= TS_VCSC_GetResourceURL("images/defaults/default_html5.jpg");
								}
								$iframe_data		= 'data-audioid="projekktor' . $hotspot_random . '"
													data-audiotheme="' . $audio_theme . '"
													data-audioholder="' . $modal_id . '_iframe"
													data-audiobaronly="' . $audio_bar_only . '"
													data-audiobarwidth="' . $audio_bar_width . '"
													data-audioautoplay="' . $audio_auto .'"
													data-audioautostop="true"
													data-audiorepeat="false"
													data-audioposter="' . $poster_image . '"
													data-audioposterfit="aspectratio"
													data-audiotitle="' . $hotspot_title . '"
													data-logoshow="logonone"
													data-logoimage=""
													data-logoheight="50"
													data-logoopacity="50"
													data-logoposition="left"
													data-logourl=""
													data-logotitle=""
													data-logotarget=""
													data-audiomp3="' . $audio_mp3_remote . '"
													data-audioogg="' . $audio_ogg_remote . '"
													data-audiovolume="' . $audio_volume . '"
													data-audioshare="false"
													data-audiofallback="' . TS_VCSC_GetResourceURL("projekktor/swf/StrobeMediaPlayback/StrobeMediaPlayback.swf") . '"';
								if ($audio_bar_only == "true") {
									$lightbox_dimensions = 'data-height="38"';
								}							
								$output .= '<a id="' . $modal_id . '-trigger" href="' . TS_VCSC_GetResourceURL("projekktor/iframe-audio.html") . '" ' . $iframe_data . ' class="ts-image-hotspot-trigger ' . $modal_id . '-parent nch-holder nch-lightbox-media no-ajaxy" ' . $lightbox_dimensions . ' style="" data-title="' . $hotspot_title . '" data-type="audio" data-thumbnail="' . $poster_image . '" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-share="0" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
							} else if (($hotspot_event != "none") && ($hotspot_event == "link")) {
								// Link Event
								$output .= '<a id="' . $modal_id . '-trigger" href="' . $a_href . '" target="' . $a_target . '" class="ts-image-hotspot-trigger ' . $modal_id . '-parent" style="" data-title="' . $hotspot_title . '">';
							} else {
								// No Hotspot Event
								$output .= '<div id="' . $modal_id . '-trigger" class="ts-image-hotspot-trigger ' . $modal_id . '-parent" ' . $lightbox_dimensions . ' style="" data-title="' . $hotspot_title . '">';
							}
								if ($hotspot_content == "empty") {
									if ($hotspot_pulse == "true") {
										$output 		.= '<div class="ts-image-hotspot-trigger-pulse ts-image-hotspot-trigger-pulse-empty" style="' . $style_hotspot_pulse . '"></div>';
									}
									$output 		.= '<div class="ts-image-hotspot-trigger-dot ts-image-hotspot-trigger-dot-empty ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' style="' . $style_hotspot_dot . '"></div>';
								} else if ($hotspot_content == "icon") {
									if ($hotspot_pulse == "true") {
										$output 		.= '<div class="ts-image-hotspot-trigger-pulse ts-image-hotspot-trigger-pulse-icon" style="' . $style_hotspot_pulse . '"></div>';
									}
									$output 		.= '<div class="ts-image-hotspot-trigger-dot ts-image-hotspot-trigger-dot-icon ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' style="' . $style_hotspot_dot . '"><i class="' . $hotspot_icon . '" style="color: ' . $hotspot_color_content . ';"></i></div>';
								} else if ($hotspot_content == "string") {
									if ($hotspot_pulse == "true") {
										$output 		.= '<div class="ts-image-hotspot-trigger-pulse ts-image-hotspot-trigger-pulse-string" style="' . $style_hotspot_pulse . '"></div>';
									}
									$output 		.= '<div class="ts-image-hotspot-trigger-dot ts-image-hotspot-trigger-dot-string ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' style="' . $style_hotspot_dot . '"><span style="color: ' . $hotspot_color_content . ';">' . $hotspot_string . '</span></div>';
								} else if ($hotspot_content == "image") {
									if ($hotspot_pulse == "true") {
										$output 		.= '<div class="ts-image-hotspot-trigger-pulse ts-image-hotspot-trigger-pulse-image" style="' . $style_hotspot_pulse . '"></div>';
									}
									$output 		.= '<img src="' . $hotspot_graphic[0] . '" class="ts-image-hotspot-trigger-dot ts-image-hotspot-trigger-dot-image ' . $Tooltip_Class . ' no-ajaxy" ' . $Tooltip_Content . ' data-no-lazy="1">';
								}
							if (($hotspot_event != "none") && ($hotspot_event != "switch")) {
								$output .= '</a>';
							} else {
								$output .= '</div>';
							}
						$output .= '</div>';
					} else {
						$output .= '<div class="ts-image-hotspot-single-edit" style="">';					
							if ($hotspot_title) {
								$output .= '<div style="font-weight: bold;">Hotspot - ' . $hotspot_title . ':</div>';
							} else {
								$output .= '<div style="font-weight: bold;">Hotspot Data:</div>';
							}
							$output .= '<div>Top: ' . $hotspot_positions[1] . '% / Left: ' . $hotspot_positions[0] . '% / Event: ' . ucfirst($hotspot_event) . ' / Content: ' . ucfirst($hotspot_content) . '</div>';
						$output .= '</div>';
					}
					// Create hidden DIV with Modal Popup Hotspot Content
					if (($hotspot_frontend == "false") && (($hotspot_event == "popup") || ($hotspot_event == "information"))) {
						if ($hotspot_event == "popup") {
							$output .= '<div id="' . $modal_id . '" class="ts-modal-content nch-hide-if-javascript ' . $el_class . '" style="display: none; padding: ' . $lightbox_custom_padding . 'px; ' . $lightbox_background . '">';
								$output .= '<div class="ts-modal-white-header"></div>';
								$output .= '<div class="ts-modal-white-frame">';
									$output .= '<div class="ts-modal-white-inner">';
						} else if ($hotspot_event == "information") {
							$output .= '<div id="' . $modal_id . '" class="ts-image-hotspot-information ' . $el_class . '" style="display: none;">';
								$output .= '<div class="ts-image-hotspot-closer" data-information="' . $modal_id . '">x</div>';
						}
							if (($hotspot_show_title == "true") && ($hotspot_title != "")) {
								$output .= '<h2 class="ts-image-hotspot-title">' . $hotspot_title . '</h2>';
							}
							if ($hotspot_editor == "true") {
								if (function_exists('wpb_js_remove_wpautop')){
									$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
								} else {
									$output .= do_shortcode($content);
								}
							} else {
								$output .= do_shortcode(rawurldecode(base64_decode(strip_tags($hotspot_text))));
							}
						if ($hotspot_event == "popup") {
									$output .= '</div>';
								$output .= '</div>';
							$output .= '</div>';
						} else {
							$output .= '</div>';
						}
					}
				$output .= '</div>';
				
				echo $output;
				
				$myvariable = ob_get_clean();
				return $myvariable;
			}			
			
			// Hotspot Container
			function TS_VCSC_Image_Hotspot_Container ($atts, $content = null) {
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();
		
				// Check for Front End Editor
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					$hotspot_frontend				= "true";
				} else {
					$hotspot_frontend				= "false";
				}
				
				if ($hotspot_frontend == "false") {
					wp_enqueue_script('ts-extend-krautlightbox');
					wp_enqueue_style('ts-extend-krautlightbox');
					wp_enqueue_style('ts-extend-tooltipster');
					wp_enqueue_script('ts-extend-tooltipster');	
					wp_enqueue_style('ts-extend-animations');
					wp_enqueue_script('ts-visual-composer-extend-front');					
					wp_enqueue_script('ts-extend-imageeffects');
				}
				wp_enqueue_style('ts-extend-imageeffects');
				wp_enqueue_style('ts-visual-composer-extend-front');				
				
				extract( shortcode_atts( array(
					'hotspot_image'					=> '',				
					'hotspot_break'					=> 'false',
					'hotspot_break_parents'			=> 6,
					'hotspot_break_zindex'			=> 0,
					'hotspot_height'				=> '100%',
					
					'hotspot_emptyfull'				=> 'true',
					'hotspot_responsive'			=> 'true',
					'hotspot_size'					=> 'large',
					'hotspot_large'					=> 900,
					'hotspot_medium'				=> 600,					
					
					'info_placement'				=> 'bottom', // cover, bottom, top, left, right
					'info_switcheroo'				=> 640,
					'info_heighttype'				=> 'auto', // auto, minimum, maximum, image
					'info_heightvalue'				=> 300,
					'info_placeholder'				=> 'false',
					
					'placeholder_content'			=> '',
					'placeholder_fontsize'			=> 13,
					'placeholder_fontcolor'			=> '#696969',
					
					'margin_left'					=> 0,
					'margin_right'					=> 0,
					'margin_top'                    => 0,
					'margin_bottom'                 => 0,
					'el_id' 						=> '',
					'el_class'                  	=> '',
					'css'							=> '',
				), $atts ));
				
				$hotspot_random                    	= mt_rand(999999, 9999999);
				
				if (!empty($el_id)) {
					$image_hotspot_id			    = $el_id;
				} else {
					$image_hotspot_id			    = 'ts-vcsc-hotspot-container-' . $hotspot_random;
				}			
				
				if (!empty($hotspot_image)) {
					$modal_image 					= wp_get_attachment_image_src($hotspot_image, 'full');
				}
				
				$data_breakouts						= 'data-inline="' . $hotspot_frontend . '" data-index="' . $hotspot_break_zindex . '" data-marginleft="' . $margin_left . '" data-marginright="' . $margin_right . '" data-image="' . $modal_image[0] . '" data-break-parents="' . esc_attr($hotspot_break_parents) . '"';
				$date_infosection					= 'data-info-placement="' . $info_placement . '" data-info-switcheroo="' . $info_switcheroo . '" data-info-heighttype="' . $info_heighttype . '" data-info-heightvalue="' . $info_heightvalue . '" data-info-placeholder="' . $info_placeholder . '"';
	
				$output 							= '';
				
				// Add Hotspot Image ID to Individual Hotspot Shortcodes
				$replace_find						= '[TS_VCSC_Image_Hotspot_Single ';
				$replace_text						= '[TS_VCSC_Image_Hotspot_Single hotspot_overlay="' . $hotspot_image . '" ';
				$content							= str_ireplace('hotspot_overlay=""', '', $content);
				$content							= str_ireplace($replace_find, $replace_text, $content);

				if ($hotspot_responsive == "false") {
					$dotsize_class					= 'ts-image-hotspot-container-size-' . $hotspot_size;
				} else {
					$dotsize_class					= 'ts-image-hotspot-container-size-large';
				}
				if ($hotspot_emptyfull == "true") {
					$dotsize_class					.= ' ts-image-hotspot-container-empty-full';
				} else {
					$dotsize_class					.= ' ts-image-hotspot-container-empty-small';
				}
				
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-image-hotspot-container ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Image_Hotspot_Container', $atts);
				} else {
					$css_class						= 'ts-image-hotspot-container ' . $el_class;
				}
				
				if ($hotspot_frontend == "false") {
					if ($hotspot_break == "true") {
						$output .= '<div id="' . $image_hotspot_id . '-fullwidth" class="ts-image-full-frame ' . $css_class . ' ' . $dotsize_class . '" style="width: 100%; height: ' . $hotspot_height . '; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;" ' . $data_breakouts . ' ' . $date_infosection . ' data-responsive="' . $hotspot_responsive . '" data-size="' . $hotspot_size . '" data-large="' . $hotspot_large . '" data-medium="' . $hotspot_medium . '">';
							$output .= '<div id="' . $image_hotspot_id . '" class="ts-image-hotspot-wrapper" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; height: 100%; width: 100%;">';
					} else {
						$output .= '<div id="' . $image_hotspot_id . '" class="' . $css_class . ' ' . $dotsize_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; height: ' . $hotspot_height . '; width: 100%;" ' . $date_infosection . ' data-responsive="' . $hotspot_responsive . '" data-size="' . $hotspot_size . '" data-large="' . $hotspot_large . '" data-medium="' . $hotspot_medium . '">';	
					}
							if (($info_placement == "top") || ($info_placement == "left")) {
								$output .= '<div class="ts-image-hotspot-contents" style="display: none;">';
									if (($info_placement == "left") && ($info_placeholder == "true") && ($placeholder_content != "")) {
										$output .= '<div class="ts-image-hotspot-placeholder" style="font-size: ' . $placeholder_fontsize . 'px; color: ' . $placeholder_fontcolor . ';">' . rawurldecode(base64_decode(strip_tags($placeholder_content))) . '</div>';
									}
								$output .= '</div>';
							}
							$output .= '<div class="ts-image-hotspot-casing">';
								$output .= '<img class="ts-image-hotspot-image ' . ($hotspot_break == "true" ? "ts-imagefull" : "") . '" data-no-lazy="1" src="' . $modal_image[0] . '">';
								$output .= '<div class="ts-image-hotspot-holder">';
									$output .= do_shortcode($content);
								$output .= '</div>';								
							$output .= '</div>';
							if (($info_placement == "bottom") || ($info_placement == "right") || ($info_placement == "cover")) {
								$output .= '<div class="ts-image-hotspot-contents" style="display: none;">';
									if (($info_placement == "right") && ($info_placeholder == "true") && ($placeholder_content != "")) {
										$output .= '<div class="ts-image-hotspot-placeholder" style="font-size: ' . $placeholder_fontsize . 'px; color: ' . $placeholder_fontcolor . ';">' . rawurldecode(base64_decode(strip_tags($placeholder_content))) . '</div>';
									}
								$output .= '</div>';
							}
						$output .= '</div>';
					if ($hotspot_break == "true") {
						$output .= '</div>';
					}
				} else {
					$output .= '<div id="' . $image_hotspot_id . '" class="ts-image-hotspot-container-edit" style="margin-top: 40px; margin-bottom: 40px;">';
						$output .= '<img class="ts-image-hotspot-image-edit" data-no-lazy="1" src="' . $modal_image[0] . '">';
						$output .= '<div class="ts-image-hotspot-holder-edit">';
							$output .= do_shortcode($content);
						$output .= '</div>';
					$output .= '</div>';
				}
				
				echo $output;
				
				$myvariable = ob_get_clean();
				return $myvariable;
			}
		
			// Add Hotspot Elements
			function TS_VCSC_Add_Image_Hotspot_Element_Container() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Hotspot Container
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					"name"                              => __("TS Image Hotspot", "ts_visual_composer_extend"),
					"base"                              => "TS_VCSC_Image_Hotspot_Container",
					"icon"                              => "ts-composer-element-icon-image-hotspot-container",
					"category"                          => __("VC Extensions", "ts_visual_composer_extend"),
					"as_parent"                         => array('only' => 'TS_VCSC_Image_Hotspot_Single'),
					"description"                       => __("Create an image with hotspots", "ts_visual_composer_extend"),
					"js_view"                           => "TS_VCSC_HotspotContainerViewCustom",
					"controls" 							=> "full",
					"content_element"                   => true,
					"is_container" 						=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_UseExtendedNesting == "true" ? false : true),
					"container_not_allowed" 			=> false,
					"show_settings_on_create"           => true,
					"admin_enqueue_js"        			=> "",
					"admin_enqueue_css"       			=> "",
					"front_enqueue_js"					=> preg_replace( '/\s/', '%20', TS_VCSC_GetResourceURL('/js/frontend/ts-vcsc-frontend-hotspot-container.min.js')),
					"front_enqueue_css"					=> "",
					"params"                            => array(
						// Image Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_1",
							"seperator"                 => "Hotspot Image",
						),
						array(
							"type"                  	=> "attach_image",
							"holder" 					=> "img",
							"heading"               	=> __( "Image", "ts_visual_composer_extend" ),
							"param_name"            	=> "hotspot_image",
							"class"						=> "ts_vcsc_holder_image",
							"value"                 	=> "",
							"admin_label"           	=> false,
							"description"           	=> __( "Select the image you want to use with the hotspots.", "ts_visual_composer_extend" )
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Full Width Image", "ts_visual_composer_extend" ),
							"param_name"		    	=> "hotspot_break",
							"value"                 	=> "false",
							"admin_label"				=> true,
							"description"		    	=> __( "Switch the toggle if you want to attempt to make the image full width.", "ts_visual_composer_extend" )
						),
						array(
							"type"						=> "nouislider",
							"heading"					=> __( "Full Width Breakouts", "ts_visual_composer_extend" ),
							"param_name"				=> "hotspot_break_parents",
							"value"						=> "6",
							"min"						=> "0",
							"max"						=> "99",
							"step"						=> "1",
							"unit"						=> '',
							"description"				=> __( "Define the number of parent containers the image should attempt to break away from.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "hotspot_break", 'value' => 'true' ),
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Container Height", "ts_visual_composer_extend" ),
							"param_name"		    	=> "hotspot_height",
							"value"                 	=> array(
								__("100%", "ts_visual_composer_extend")                    		=> "100%",
								__("Auto", "ts_visual_composer_extend")                 		=> "auto",
							),							
							"description"		    	=> __( "Select how the height for the main hotspot container should be set by the browser.", "ts_visual_composer_extend" ),
						),
						// Hotspot Sizes
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_2",
							"seperator"                 => "Hotspot Sizes",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Responsive Hotspot Size", "ts_visual_composer_extend" ),
							"param_name"		    	=> "hotspot_responsive",
							"value"                 	=> "true",
							"admin_label"				=> true,
							"description"		    	=> __( "Switch the toggle if you want to use dynamic hotspot sizes to account for different image sizes.", "ts_visual_composer_extend" )
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Large Empty Hotspot Size", "ts_visual_composer_extend" ),
							"param_name"		    	=> "hotspot_emptyfull",
							"value"                 	=> "true",
							"description"		    	=> __( "Switch the toggle if you want to size empty hotspots the same as image or icon/text filled hotspots, whenever the overall large hotspot size has been triggered.", "ts_visual_composer_extend" )
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Hotspot Size", "ts_visual_composer_extend" ),
							"param_name"		    	=> "hotspot_size",
							"value"                 	=> array(
								__("Large", "ts_visual_composer_extend")                    	=> "large",
								__("Medium", "ts_visual_composer_extend")                 		=> "medium",
								__("Small", "ts_visual_composer_extend")						=> "small",
							),							
							"description"		    	=> __( "Select the fixed size of the individual hotspots.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "hotspot_responsive", 'value' => 'false' ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Large Size", "ts_visual_composer_extend" ),
							"param_name"                => "hotspot_large",
							"value"                     => "900",
							"min"                       => "720",
							"max"                       => "2048",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Select the minimum width for the hotspot image to allow for the full-sized hotspots.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "hotspot_responsive", 'value' => 'true' ),
						),
						array(
							"type"              		=> "messenger",
							"param_name"        		=> "messenger",
							"color"						=> "#006BB7",
							"size"						=> "13",
							"layout"					=> "notice",
							"message"            		=> __( "Please ensure that the size limit for large-sized hotpots is higher than the limit set for medium sized hotspots. Image sizes not matching the set criteria will automatically use the smallest hotspot size.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "hotspot_responsive", 'value' => 'true' ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Medium Size", "ts_visual_composer_extend" ),
							"param_name"                => "hotspot_medium",
							"value"                     => "600",
							"min"                       => "480",
							"max"                       => "1024",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Select the minimum width for the hotspot image to allow for the medium-sized hotspots.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "hotspot_responsive", 'value' => 'true' ),
						),
						// Other Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_3",
							"seperator"                 => "Other Settings",
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
			function TS_VCSC_Add_Image_Hotspot_Element_Single() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Single Hotspot
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					"name"                      	=> __( "TS Single Hotspot", "ts_visual_composer_extend" ),
					"base"                      	=> "TS_VCSC_Image_Hotspot_Single",
					"icon" 	                    	=> "ts-composer-element-icon-image-hotspot-single",
					"class"                     	=> "ts_vcsc_main_image_hotspot",
					"content_element"                => true,
					"as_child"                       => array('only' => 'TS_VCSC_Image_Hotspot_Container'),
					"category"                  	=> __( 'VC Extensions', "ts_visual_composer_extend" ),
					"description"                       => __("Place a single hotspot to the image", "ts_visual_composer_extend"),
					"js_view"						=> "TS_VCSC_HotspotSingleViewCustom",
					"admin_enqueue_js"        		=> "",
					"admin_enqueue_css"       		=> "",
					"front_enqueue_js"				=> preg_replace( '/\s/', '%20', TS_VCSC_GetResourceURL('/js/frontend/ts-vcsc-frontend-hotspot-single.min.js')),
					"front_enqueue_css"				=> "",
					"params"                    	=> array(
						// Hotspot Settings
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_1",
							"seperator"				=> "Hotspot Title",
						),
						array(
							"type"					=> "textfield",
							"heading"				=> __( "Hotspot Title", "ts_visual_composer_extend" ),
							"param_name"			=> "hotspot_title",
							"value"					=> "",
							"admin_label"       	=> true,
							"description"			=> __( "Enter a hotspot title to be used for easier identification when in edit mode.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_2",
							"seperator"				=> "Hotspot Position",
						),
						array(
							"type"                  => "imagehotspot",
							"heading"               => __( "Hotspot Positions", "ts_visual_composer_extend" ),
							"param_name"            => "hotspot_positions",
							"value"                 => "0,0",
							"min"                   => "0",
							"max"                   => "100",
							"step"                  => "1",
							"unit"                  => '%',
							"admin_label"       	=> true,
						),
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_3",
							"seperator"				=> "Hotspot Visibility",
						),
						array(
							"type"				    => "dropdown",
							"heading"			    => __( "Hotspot Visibility", "ts_visual_composer_extend" ),
							"param_name"		    => "hotspot_widthrule",
							"value"                 => array(
								__("Always Show Hotspot", "ts_visual_composer_extend")										=> "none",                        
								__("Show Hotspot If Minimum Element Width", "ts_visual_composer_extend")					=> "widthmin",
								__("Show Hotspot Up To Maximum Element Width", "ts_visual_composer_extend")					=> "widthmax",
								__("Show Hotspot If Minimum + Up To Maximum Element Width", "ts_visual_composer_extend")	=> "widthcorridor",
							),
							"admin_label"			=> true,
							"description"		    => __( "Select if and when the hotspot should be visible (width applies to element width).", "ts_visual_composer_extend" ),
						),
						array(
							"type"              	=> "nouislider",
							"heading"           	=> __( "Minimum Width", "ts_visual_composer_extend" ),
							"param_name"        	=> "hotspot_widthmin",
							"value"             	=> "240",
							"min"               	=> "0",
							"max"               	=> "1680",
							"step"              	=> "1",
							"unit"              	=> 'px',
							"description"       	=> __( "Define the required minimum element width for the hotspot to be visible.", "ts_visual_composer_extend" ),							
							"dependency"        	=> array( 'element' => "hotspot_widthrule", 'value' => array('widthmin', 'widthcorridor') )
						),
						array(
							"type"              	=> "nouislider",
							"heading"           	=> __( "Maximum Width", "ts_visual_composer_extend" ),
							"param_name"        	=> "hotspot_widthmax",
							"value"             	=> "1920",
							"min"               	=> "0",
							"max"               	=> "2440",
							"step"              	=> "1",
							"unit"              	=> 'px',
							"description"       	=> __( "Define the allowable maximum element width for the hotspot to be visible.", "ts_visual_composer_extend" ),							
							"dependency"        	=> array( 'element' => "hotspot_widthrule", 'value' => array('widthmax', 'widthcorridor') )
						),						
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_4",
							"seperator"				=> "Hotspot Style",
						),
						array(
							"type"				    => "dropdown",
							"heading"			    => __( "Hotspot Content", "ts_visual_composer_extend" ),
							"param_name"		    => "hotspot_content",
							"value"                 => array(
								__("Empty", "ts_visual_composer_extend")                    	=> "empty",
								__("Font Icon", "ts_visual_composer_extend")                 	=> "icon",
								__("Number / Letter", "ts_visual_composer_extend")				=> "string",
								__("Image", "ts_visual_composer_extend")						=> "image",
							),
							"admin_label"       	=> true,
							"description"		    => __( "Select if the inner hotspot section should show any additional content.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              	=> "colorpicker",
							"heading"           	=> __( "Icon / String Color", "ts_visual_composer_extend" ),
							"param_name"        	=> "hotspot_color_content",
							"value"             	=> "#ffffff",
							"description"       	=> __( "Define the color for the icon or string inside the inner hotspot dot.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "hotspot_content", 'value' => array('icon', 'string') )
						),
						array(
							"type" 					=> "icons_panel",
							'heading' 				=> __( 'Select Icon', 'ts_visual_composer_extend' ),
							'param_name' 			=> 'hotspot_icon',
							'value'					=> '',
							"settings" 				=> array(
								"emptyIcon" 					=> false,
								'emptyIconValue'				=> 'transparent',
								"type" 							=> 'extensions',
							),
							"description"       	=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorVisualSelector == "true" ? __( "Select the icon to be shown inside the inner hotspot dot.", "ts_visual_composer_extend" ) : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconSelectorString),
							"dependency"        	=> array( 'element' => "hotspot_content", 'value' => 'icon' )
						),						
						array(
							"type"                  => "textfield",
							"heading"               => __( "Number / Letter", "ts_visual_composer_extend" ),
							"param_name"            => "hotspot_string",
							"value"                 => "",
							"description"           => __( "Enter a number or letter to be shown inside the inner hotspot dot.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "hotspot_content", 'value' => 'string' )
						),						
						array(
							"type"                  => "attach_image",
							"heading"               => __( "Hotspot Image", "ts_visual_composer_extend" ),
							"param_name"            => "hotspot_graphic",
							"value"                 => "",
							"description"           => __( "Select a circular or square image with transparent background you want to use for this hotspot.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "hotspot_content", 'value' => 'image' )
						),						
						array(
							"type"              	=> "colorpicker",
							"heading"           	=> __( "Inner Dot Color", "ts_visual_composer_extend" ),
							"param_name"        	=> "hotspot_color_dot",
							"value"             	=> "#45453f",
							"description"       	=> __( "Define the color for the inner dot of the hotspot.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "hotspot_content", 'value' => array('empty', 'icon', 'string') ),
							"edit_field_class"		=> "vc_col-sm-6 vc_column",
						),
						array(
							"type"              	=> "colorpicker",
							"heading"           	=> __( "Outer Circle Color", "ts_visual_composer_extend" ),
							"param_name"        	=> "hotspot_color_circle",
							"value"             	=> "#f7f14c",
							"description"       	=> __( "Define the color for the outer dot border of the hotspot.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "hotspot_content", 'value' => array('empty', 'icon', 'string') ),
							"edit_field_class"		=> "vc_col-sm-6 vc_column",
						),
						array(
							"type"                  => "switch_button",
							"heading"			    => __( "Use Pulse Effect", "ts_visual_composer_extend" ),
							"param_name"		    => "hotspot_pulse",
							"value"                 => "true",
							"edit_field_class"		=> "vc_col-sm-6 vc_column",
							"description"		    => __( "Switch the toggle if you want have a pulse effect for the hotspot.", "ts_visual_composer_extend" )
						),
						array(
							"type"              	=> "colorpicker",
							"heading"           	=> __( "Pulse Color", "ts_visual_composer_extend" ),
							"param_name"        	=> "hotspot_color_pulse",
							"value"             	=> "#fff601",
							"description"       	=> __( "Define the color for the pulsating ring of the hotspot.", "ts_visual_composer_extend" ),
							"edit_field_class"		=> "vc_col-sm-6 vc_column",
							"dependency"		    => array( 'element' => "hotspot_pulse", 'value' => 'true' ),
						),
						// Viewport Animation
						array(
							"type"					=> "css3animations",
							"heading"				=> __("Viewport Animation", "ts_visual_composer_extend"),
							"param_name"			=> "viewport_animation",
							"prefix"				=> "ts-viewport-css-",
							"connector"				=> "viewport_name",
							"noneselect"			=> "true",
							"default"				=> "",
							"value"					=> "",
							"admin_label"			=> false,
							"description"			=> __("Select the viewport animation for this hotspot.", "ts_visual_composer_extend"),
							"dependency"        	=> array( 'element' => "hotspot_widthrule", 'value' => array('none') ),
						),
						array(
							"type"					=> "hidden_input",
							"heading"				=> __( "Viewport Animation", "ts_visual_composer_extend" ),
							"param_name"			=> "viewport_name",
							"value"					=> "",
							"admin_label"			=> true,
							"dependency"        	=> array( 'element' => "hotspot_widthrule", 'value' => array('none') ),
						),
						array(
							"type"              	=> "nouislider",
							"heading"           	=> __( "Animation Delay", "ts_visual_composer_extend" ),
							"param_name"        	=> "viewport_delay",
							"value"             	=> "0",
							"min"               	=> "0",
							"max"               	=> "20000",
							"step"              	=> "100",
							"unit"              	=> 'ms',
							"description"       	=> __( "Define an optional delay for the viewport animation.", "ts_visual_composer_extend" ),
							"admin_label"			=> true,
							"dependency"        	=> array( 'element' => "viewport_animation", 'not_empty' => true ),
						),
						// Hotspot Event
						array(
							"type"				    => "seperator",
							"param_name"		    => "seperator_5",
							"seperator"				=> "Hotspot Event",
							"group" 				=> "Hotspot Event",
						),						
						array(
							"type"                  => "dropdown",
							"heading"               => __( "Hotspot Event", "ts_visual_composer_extend" ),
							"param_name"            => "hotspot_event",
							"width"                 => 150,
							"value" 				=> array(
								__( "None", "ts_visual_composer_extend" )									=> "none",								
								__( "Open Other Image in Lightbox", "ts_visual_composer_extend" )			=> "image",
								__( "Open Hotspot Image in Lightbox", "ts_visual_composer_extend" )			=> "hotspot",
								__( "Open YouTube Video in Lightbox", "ts_visual_composer_extend" )			=> "youtube",
								__( "Open Vimeo Video in Lightbox", "ts_visual_composer_extend" )			=> "vimeo",
								__( "Open DailyMotion Video in Lightbox", "ts_visual_composer_extend" )		=> "dailymotion",								
								__( "Open HTML5 Video in Lightbox", "ts_visual_composer_extend" )			=> "html5video",
								__( "Open HTML5 Audio in Lightbox", "ts_visual_composer_extend" )			=> "html5audio",								
								__( "Open Page in iFrame Lightbox", "ts_visual_composer_extend" )			=> "iframe",
								__( "Show Information via Modal Popup", "ts_visual_composer_extend" )		=> "popup",
								__( "Show Information next to Hotspot Image", "ts_visual_composer_extend" )	=> "information",
								__( "Simple Link to Page", "ts_visual_composer_extend" )					=> "link",
								__( "Switch Hotspot Image", "ts_visual_composer_extend" )					=> "switch",
							),
							"description"           => __( "Select if the hotspot should trigger any other action, aside from the tooltip.", "ts_visual_composer_extend" ),
							"admin_label"       	=> true,
							"group" 				=> "Hotspot Event",
						),
						// Modal Popup + Information Section
						array(
							"type"                  => "switch_button",
							"heading"			    => __( "Show Hotspot Title", "ts_visual_composer_extend" ),
							"param_name"		    => "hotspot_show_title",
							"value"                 => "true",
							"description"		    => __( "Switch the toggle if you want to show the title in the modal popup or information section.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => array('popup', 'information') ),
							"group" 				=> "Hotspot Event",
						),						
						array(
							"type"              	=> "switch_button",
							"heading"			    => __( "Use Full HTML Editor", "ts_visual_composer_extend" ),
							"param_name"		    => "hotspot_editor",
							"value"				    => "false",
							"description"		    => __( "Switch the toggle if you want to use the full tinyMCE editor to provide the element content.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "hotspot_event", 'value' => array('popup', 'information') ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"              	=> "textarea_raw_html",
							"heading"           	=> __( "Hotspot Description", "ts_visual_composer_extend" ),
							"param_name"        	=> "hotspot_text",
							"value"             	=> base64_encode(""),
							"description"       	=> __( "Enter the more detailed description for the modal popup; HTML code can be used.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "hotspot_editor", 'value' => 'false' ),
							"group" 				=> "Hotspot Event",
						),						
						array(
							"type"		            => "textarea_html",
							"heading"               => __( "Hotspot Description", "ts_visual_composer_extend" ),
							"param_name"            => "content",
							"value"                 => "",
							"admin_label"           => false,
							"description"           => __( "Enter the more detailed description for the modal popup; basic shortcodes can be used.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "hotspot_editor", 'value' => 'true' ),
							"group" 				=> "Hotspot Event",
						),						
						// Lightbox Image
						array(
							"type"					=> "attach_image",
							"heading"				=> __( "Image", "ts_visual_composer_extend" ),
							"param_name"			=> "hotspot_picture",
							"value"					=> "",
							"description"			=> __( "Select the image you want to open or switch to when clicked on the hotspot.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "hotspot_event", 'value' => array('image', 'switch') ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"                  => "switch_button",
							"heading"			    => __( "Hotspot Toggle", "ts_visual_composer_extend" ),
							"param_name"		    => "hotspot_toggle",
							"value"                 => "false",
							"description"		    => __( "Switch the toggle if the hotspot should be able to toggle back and forth between both images.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'switch' ),
							"group" 				=> "Hotspot Event",
						),
						// YouTube / DailyMotion / Vimeo
						array(
							"type"                  => "textfield",
							"heading"               => __( "Video URL", "ts_visual_composer_extend" ),
							"param_name"            => "hotspot_video_link",
							"value"                 => "",
							"description"           => __( "Enter the URL for the video to be shown in a lightbox.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => array('youtube','dailymotion','vimeo') ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"              	=> "switch_button",
							"heading"			    => __( "Show Related Videos", "ts_visual_composer_extend" ),
							"param_name"		    => "hotspot_video_related",
							"value"             	=> "false",
							"description"		    => __( "Switch the toggle if you want to show related videos once the video has finished playing.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'youtube' ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"              	=> "switch_button",
							"heading"			    => __( "Autoplay Video", "ts_visual_composer_extend" ),
							"param_name"		    => "hotspot_video_auto",
							"value"             	=> "true",
							"description"		    => __( "Switch the toggle if you want to auto-play the video once opened in the lightbox.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => array('youtube','dailymotion','vimeo') ),
							"group" 				=> "Hotspot Event",
						),
						// HTML5 Video
						array(
							"type"                  => "textfield",
							"heading"               => __( "MP4 Video", "ts_visual_composer_extend" ),
							"param_name"            => "video_mp4_remote",
							"value"                 => "",
							"description"           => __( "Enter the remote path to the MP4 version of the video.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'html5video' ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"                  => "textfield",
							"heading"               => __( "WEBM Video", "ts_visual_composer_extend" ),
							"param_name"            => "video_webm_remote",
							"value"                 => "",
							"description"           => __( "Enter the remote path to the WEBM version of the video.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'html5video' ),
							"group" 				=> "Hotspot Event",
						),	
						array(
							"type"                  => "textfield",
							"heading"               => __( "OGV Video", "ts_visual_composer_extend" ),
							"param_name"            => "video_ogg_remote",
							"value"                 => "",
							"description"           => __( "Enter the remote path to the OGV version of the video.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'html5video' ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"				    => "dropdown",
							"heading"			    => __( "Player Theme", "ts_visual_composer_extend" ),
							"param_name"		    => "video_theme",
							"value"                 => array(
								__("Maccaco", "ts_visual_composer_extend")					=> "maccaco",                        
								__("Totally Looks Alike", "ts_visual_composer_extend")		=> "totallylookslike",
								__("Minimum", "ts_visual_composer_extend")					=> "minimum",
							),
							"description"		    => __( "Select the overall theme for the player.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'html5video' ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"                  => "attach_image",
							"heading"               => __( "Video Poster", "ts_visual_composer_extend" ),
							"param_name"            => "video_poster",
							"value"                 => "",
							"description"           => __( "Select the image that should be used as video poster.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'html5video' ),
							"group" 				=> "Hotspot Event",
						),	
						array(
							"type"                  => "nouislider",
							"heading"               => __( "Video Volume", "ts_visual_composer_extend" ),
							"param_name"            => "video_volume",
							"value"                 => "50",
							"min"                   => "0",
							"max"                   => "100",
							"step"                  => "1",
							"unit"                  => '%',
							"description"           => __( "Select the startup volume for the media; set to 0 (Zero) to mute; desktop only (valid for first session).", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'html5video' ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"                  => "switch_button",
							"heading"			    => __( "Video Fullscreen", "ts_visual_composer_extend" ),
							"param_name"		    => "video_fullscreen",
							"value"                 => "true",
							"description"		    => __( "Switch the toggle if you want the media to have a fullscreen option.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'html5video' ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"                  => "switch_button",
							"heading"			    => __( "Video Auto-Play", "ts_visual_composer_extend" ),
							"param_name"		    => "video_auto",
							"value"                 => "true",
							"description"		    => __( "Switch the toggle if you want the media to start playing upon page load.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'html5video' ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"                  => "switch_button",
							"heading"			    => __( "Video Loop", "ts_visual_composer_extend" ),
							"param_name"		    => "video_loop",
							"value"                 => "false",
							"description"		    => __( "Switch the toggle if you want the media to loop and start over each time it has finished.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'html5video' ),
							"group" 				=> "Hotspot Event",
						),
						// HTML5 Audio
						array(
							"type"                  => "textfield",
							"heading"               => __( "MP3 Audio", "ts_visual_composer_extend" ),
							"param_name"            => "audio_mp3_remote",
							"value"                 => "",
							"description"           => __( "Enter the remote path to the MP3 version of the audio.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'html5audio' ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"                  => "textfield",
							"heading"               => __( "OGG Audio", "ts_visual_composer_extend" ),
							"param_name"            => "audio_ogg_remote",
							"value"                 => "",
							"description"           => __( "Enter the remote path to the OGG version of the audio.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'html5audio' ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"				    => "dropdown",
							"heading"			    => __( "Player Theme", "ts_visual_composer_extend" ),
							"param_name"		    => "audio_theme",
							"value"                 => array(
								__("Maccaco", "ts_visual_composer_extend")					=> "maccaco",                        
								__("Totally Looks Alike", "ts_visual_composer_extend")		=> "totallylookslike",
								__("Minimum", "ts_visual_composer_extend")					=> "minimum",
							),
							"description"		    => __( "Select the overall theme for the player.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'html5audio' ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"                  => "switch_button",
							"heading"			    => __( "Show Bar Only", "ts_visual_composer_extend" ),
							"param_name"		    => "audio_bar_only",
							"value"                 => "false",
							"description"		    => __( "Switch the toggle if you just want to show the player bar without poster.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'html5audio' ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"					=> "nouislider",
							"heading"				=> __( "Maximum Player Width", "ts_visual_composer_extend" ),
							"param_name"			=> "audio_bar_width",
							"value"					=> "480",
							"min"					=> "100",
							"max"					=> "1024",
							"step"					=> "1",
							"unit"					=> 'px',
							"description"			=> __( "Define the maximum width the player bar is allowed to have in the lightbox.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "audio_bar_only", 'value' => 'true' ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"                  => "attach_image",
							"heading"               => __( "Audio Poster", "ts_visual_composer_extend" ),
							"param_name"            => "audio_poster",
							"value"                 => "",
							"description"           => __( "Select the image that should be used as audio poster.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'html5audio' ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"                  => "nouislider",
							"heading"               => __( "Audio Volume", "ts_visual_composer_extend" ),
							"param_name"            => "audio_volume",
							"value"                 => "50",
							"min"                   => "0",
							"max"                   => "100",
							"step"                  => "1",
							"unit"                  => '%',
							"description"           => __( "Select the startup volume for the media; set to 0 (Zero) to mute; desktop only (valid for first session).", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'html5audio' ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"                  => "switch_button",
							"heading"			    => __( "Audio Auto-Play", "ts_visual_composer_extend" ),
							"param_name"		    => "audio_auto",
							"value"                 => "true",
							"description"		    => __( "Switch the toggle if you want the media to start playing upon page load.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'html5audio' ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"                  => "switch_button",
							"heading"			    => __( "Audio Loop", "ts_visual_composer_extend" ),
							"param_name"		    => "audio_loop",
							"value"                 => "false",
							"description"		    => __( "Switch the toggle if you want the media to loop and start over each time it has finished.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'html5audio' ),
							"group" 				=> "Hotspot Event",
						),
						// Link / iFrame
						array(
							"type" 					=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterLinkPicker['enabled'] == "false" ? "vc_link" : "advancedlinks"),
							"heading" 				=> __("Link + Title", "ts_visual_composer_extend"),
							"param_name" 			=> "hotspot_link",
							"description" 			=> __("Provide a link to another site/page to be used for the hotspot event.", "ts_visual_composer_extend"),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => array('iframe','link') ),
							"group" 				=> "Hotspot Event",
						),
						// Tooltip Settings
						array(
							"type"				    => "seperator",
							"param_name"		    => "seperator_6",
							"seperator"				=> "Hotspot Tooltip",
							"group" 				=> "Hotspot Tooltip",
						),
						array(
							"type"                  => "switch_button",
							"heading"			    => __( "Use HTML in Tooltip", "ts_visual_composer_extend" ),
							"param_name"		    => "content_tooltip_html",
							"value"                 => "true",
							"description"		    => __( "Switch the toggle if you want to allow basic HTML code for the tooltip content.", "ts_visual_composer_extend" ),
							"group" 				=> "Hotspot Tooltip",
						),
						array(
							"type"					=> "textfield",
							"heading"				=> __( "Tooltip Title", "ts_visual_composer_extend" ),
							"param_name"			=> "content_tooltip_title",
							"value"					=> "",
							"description"			=> __( "Enter an optional title to be used for the hotspot tooltip.", "ts_visual_composer_extend" ),
							"group" 				=> "Hotspot Tooltip",
						),
						array(
							"type"				    => "textarea",
							"heading"			    => __( "Tooltip Content", "ts_visual_composer_extend" ),
							"param_name"		    => "content_tooltip_content",
							"value"				    => "",
							"description"		    => __( "Enter the tooltip content here (do not use quotation marks or HTML code).", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "content_tooltip_html", 'value' => 'false' ),
							"group" 				=> "Hotspot Tooltip",
						),
						array(
							"type"              	=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorBase64TinyMCE == "true" ? "wysiwyg_base64" : "textarea_raw_html"),
							"heading"           	=> __( "Tooltip Content", "ts_visual_composer_extend" ),
							"param_name"        	=> "content_tooltip_content_html",
							"minimal"				=> "true",
							"value"             	=> base64_encode(""),
							"description"      	 	=> __( "Enter the tooltip content here; shortcodes are NOT supported.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "content_tooltip_html", 'value' => 'true' ),
							"group" 				=> "Hotspot Tooltip",
						),
						array(
							"type"              	=> "switch_button",
							"heading"			    => __( "Show Tooltip After Pageload", "ts_visual_composer_extend" ),
							"param_name"		    => "content_tooltip_show",
							"value"				    => "false",
							"description"		    => __( "Switch the toggle if you want to automatically show this tooltip after the page has loaded.", "ts_visual_composer_extend" ),
							"group" 				=> "Hotspot Tooltip",
						),
						array(
							"type"				    => "dropdown",
							"heading"			    => __( "Tooltip Trigger", "ts_visual_composer_extend" ),
							"param_name"		    => "content_tooltip_trigger",
							"value"                 => array(
								__("Hover", "ts_visual_composer_extend")                    => "hover",
								__("Click", "ts_visual_composer_extend")                 	=> "click",
							),
							"description"		    => __( "Select how the tooltip should be triggered; only available if no hotspot event assigned.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "hotspot_event", 'value' => 'none' ),
							"group" 				=> "Hotspot Tooltip",
						),
						array(
							"type"				    => "dropdown",
							"heading"			    => __( "Tooltip Animation", "ts_visual_composer_extend" ),
							"param_name"		    => "content_tooltip_animation",
							"value"                 => $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Animations,
							"description"		    => __( "Select how the tooltip entry and exit should be animated once triggered.", "ts_visual_composer_extend" ),
							"group" 				=> "Hotspot Tooltip",
						),
						array(
							"type"				    => "dropdown",
							"heading"			    => __( "Tooltip Position", "ts_visual_composer_extend" ),
							"param_name"		    => "content_tooltip_position",
							"value"                 => $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Positions,
							"description"		    => __( "Select the tooltip position in relation to the hotspot.", "ts_visual_composer_extend" ),
							"group" 				=> "Hotspot Tooltip",
						),
						array(
							"type"				    => "dropdown",
							"heading"			    => __( "Tooltip Style", "ts_visual_composer_extend" ),
							"param_name"		    => "content_tooltip_style",
							"value"                 => $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Layouts,
							"description"		    => __( "Select the tooltip style.", "ts_visual_composer_extend" ),
							"group" 				=> "Hotspot Tooltip",
						),
						array(
							"type"					=> "nouislider",
							"heading"				=> __( "Tooltip X-Offset", "ts_visual_composer_extend" ),
							"param_name"			=> "tooltipster_offsetx",
							"value"					=> "0",
							"min"					=> "-100",
							"max"					=> "100",
							"step"					=> "1",
							"unit"					=> 'px',
							"description"			=> __( "Define an optional X-Offset for the tooltip position.", "ts_visual_composer_extend" ),
							"group" 				=> "Hotspot Tooltip",
						),
						array(
							"type"					=> "nouislider",
							"heading"				=> __( "Tooltip Y-Offset", "ts_visual_composer_extend" ),
							"param_name"			=> "tooltipster_offsety",
							"value"					=> "0",
							"min"					=> "-100",
							"max"					=> "100",
							"step"					=> "1",
							"unit"					=> 'px',
							"description"			=> __( "Define an optional Y-Offset for the tooltip position.", "ts_visual_composer_extend" ),
							"group" 				=> "Hotspot Tooltip",
						),	
						// Lightbox Settings
						array(
							"type"                  => "seperator",
							"param_name"            => "seperator_7",
							"seperator"             => "Hotspot Popup Settings",
							"dependency"            => array( 'element' => "hotspot_event", 'value' => array('popup', 'image', 'hotspot', 'youtube', 'vimeo', 'dailymotion', 'html5video', 'html5audio', 'iframe') ),
							"group" 				=> "Lightbox Settings",
						),
						array(
							"type"                  => "dropdown",
							"heading"               => __( "Transition Effect", "ts_visual_composer_extend" ),
							"param_name"            => "lightbox_effect",
							"width"                 => 150,
							"value"                 => $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Animations,
							"default" 				=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LightboxDefaultAnimation,
							"std" 					=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LightboxDefaultAnimation,
							"description"           => __( "Select the transition effect to be used for the hotspot content in the lightbox.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => array('popup', 'image', 'hotspot', 'youtube', 'vimeo', 'dailymotion', 'html5video', 'html5audio', 'iframe') ),
							"group" 				=> "Lightbox Settings",
						),
						array(
							"type"              	=> "switch_button",
							"heading"			    => __( "Create AutoGroup", "ts_visual_composer_extend" ),
							"param_name"		    => "lightbox_group",
							"value"				    => "false",
							"description"		    => __( "Switch the toggle if you want the plugin to group this hotspot event with all other lightbox items on the page.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => array('popup', 'image', 'hotspot', 'youtube', 'vimeo', 'dailymotion', 'html5video', 'html5audio', 'iframe') ),
							"group" 				=> "Lightbox Settings",
						),
						array(
							"type"                  => "textfield",
							"heading"               => __( "Group Name", "ts_visual_composer_extend" ),
							"param_name"            => "lightbox_group_name",
							"value"                 => "",
							"description"           => __( "Enter a custom group name to manually build a group with other lightbox items.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "lightbox_group", 'value' => 'false' ),
							"group" 				=> "Lightbox Settings",
						),
						array(
							"type"                  => "dropdown",
							"heading"               => __( "Backlight Color", "ts_visual_composer_extend" ),
							"param_name"            => "lightbox_backlight_choice",
							"width"                 => 150,
							"value"                 => array(
								__( 'Predefined Color', "ts_visual_composer_extend" )	=> "predefined",
								__( 'Custom Color', "ts_visual_composer_extend" )		=> "customized",
							),
							"description"           => __( "Select the (backlight) color style for the hotspot content.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => array('popup', 'image', 'hotspot', 'youtube', 'vimeo', 'dailymotion', 'html5video', 'html5audio', 'iframe') ),
							"group" 				=> "Lightbox Settings",
						),
						array(
							"type"                  => "dropdown",
							"heading"               => __( "Select Backlight Color", "ts_visual_composer_extend" ),
							"param_name"            => "lightbox_backlight_color",
							"width"                 => 150,
							"value"                 => array(
								__( 'Default', "ts_visual_composer_extend" )      		=> "#0084E2",
								__( 'Neutral', "ts_visual_composer_extend" )      		=> "#FFFFFF",
								__( 'Success', "ts_visual_composer_extend" )      		=> "#4CFF00",
								__( 'Warning', "ts_visual_composer_extend" )      		=> "#EA5D00",
								__( 'Error', "ts_visual_composer_extend" )        		=> "#CC0000",
								__( 'None', "ts_visual_composer_extend" )         		=> "#000000",
							),
							"description"           => __( "Select the predefined backlight color for the hotspot popup.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "lightbox_backlight_choice", 'value' => 'predefined' ),
							"group" 				=> "Lightbox Settings",
						),
						array(
							"type"              	=> "colorpicker",
							"heading"           	=> __( "Select Backlight Color", "ts_visual_composer_extend" ),
							"param_name"        	=> "lightbox_backlight_custom",
							"value"             	=> "#000000",
							"description"       	=> __( "Define a custom backlight color for the hotspot popup.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "lightbox_backlight_choice", 'value' => 'customized' ),
							"group" 				=> "Lightbox Settings",
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
	if ((class_exists('WPBakeryShortCodesContainer')) && (!class_exists('WPBakeryShortCode_TS_VCSC_Image_Hotspot_Container'))) {
		class WPBakeryShortCode_TS_VCSC_Image_Hotspot_Container extends WPBakeryShortCodesContainer {};
	}
	if ((class_exists('WPBakeryShortCode')) && (!class_exists('WPBakeryShortCode_TS_VCSC_Image_Hotspot_Single'))) {
		class WPBakeryShortCode_TS_VCSC_Image_Hotspot_Single extends WPBakeryShortCode {};
	}
	// Initialize "TS Image Hotspots" Class
	if (class_exists('TS_Image_Hotspot')) {
		$TS_Image_Hotspot = new TS_Image_Hotspot;
	}
?>