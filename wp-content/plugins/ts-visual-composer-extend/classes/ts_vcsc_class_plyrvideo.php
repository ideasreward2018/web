<?php
	if (!class_exists('TS_Plyr_Video_Player')){
		class TS_Plyr_Video_Player {
			private $TS_VCSC_Plyr_Player_Language;
			
			function __construct() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						$this->TS_VCSC_Add_PlyrVideoPlayer_Element_Lean();
					} else if (function_exists('vc_map')) {
						add_action('init',									array($this, 'TS_VCSC_Add_PlyrVideoPlayer_Element_Define'), 9999999);
					}
				} else {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_PlyrVideoPlayer_Element_Lean'), 9999999);
					} else if (function_exists('vc_map')) {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_PlyrVideoPlayer_Element_Define'), 9999999);
					}
				}
				if ((is_admin() == false) || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAJAX == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAlways == "true")) {
					add_shortcode('TS_VCSC_Plyr_Player',					array($this, 'TS_VCSC_Add_PlyrVideoPlayer_Element_Shortcode'));
				}
				$this->TS_VCSC_Plyr_Player_Language							= get_option('ts_vcsc_extend_settings_translationsPlyrPlayer',	$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults);
			}
			
			// Register Element(s) via LeanMap
			function TS_VCSC_Add_PlyrVideoPlayer_Element_Lean() {
				vc_lean_map('TS_VCSC_Plyr_Player', 							array($this, 'TS_VCSC_Add_PlyrVideoPlayer_Element_Define'), null);
			}
	
			// Plyr Video Player Function
			function TS_VCSC_Add_PlyrVideoPlayer_Element_Shortcode ($atts, $content = null) {
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();
				
				extract( shortcode_atts( array(
					// Video Sources
					'video_title'					=> '',
					'video_poster'					=> '',
					'video_mp4_source'				=> 'false',
					'video_mp4_remote'				=> '',
					'video_mp4_local'				=> '',
					'video_ogg_source'				=> 'false',
					'video_ogg_remote'				=> '',
					'video_ogg_local'				=> '',			
					'video_webm_source'				=> 'false',
					'video_webm_remote'				=> '',
					'video_webm_local'				=> '',
					'video_dimensions'				=> 'false',
					'video_width'					=> 1280,
					'video_height'					=> 720,
					// Player Settings
					'player_autoplay'				=> 'false',
					'player_loop'					=> 'false',
					'player_volume'					=> 50,
					'player_hidecontrols'			=> 'true',
					'player_showposterend'			=> 'true',
					'player_tooltips'				=> 'false',
					'player_hidecontext'			=> 'true',
					// Theme Customizations
					'theme_customize'				=> 'false',
					'theme_playbutton'				=> '#3498db',
					'theme_hoverback'				=> '#3498db',
					'theme_progressbar'				=> '#3498db',
					'theme_volumebar'				=> '#3498db',
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
					// Trigger Settings
					'content_open'					=> 'false',
					'content_open_hide'				=> 'false',
					'content_open_delay'			=> 0,
					'content_trigger'				=> 'poster',
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
					// Language Settings
					'language_customize'			=> 'false',
					'language_restart' 				=> (isset($this->TS_VCSC_Plyr_Player_Language['restart'])				? $this->TS_VCSC_Plyr_Player_Language['restart']				: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['restart']),
					'language_rewind' 				=> (isset($this->TS_VCSC_Plyr_Player_Language['rewind'])				? $this->TS_VCSC_Plyr_Player_Language['rewind']					: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['rewind']),
					'language_play' 				=> (isset($this->TS_VCSC_Plyr_Player_Language['play'])					? $this->TS_VCSC_Plyr_Player_Language['play']					: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['play']),
					'language_pause' 				=> (isset($this->TS_VCSC_Plyr_Player_Language['pause'])					? $this->TS_VCSC_Plyr_Player_Language['pause']					: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['pause']),
					'language_forward' 				=> (isset($this->TS_VCSC_Plyr_Player_Language['forward'])				? $this->TS_VCSC_Plyr_Player_Language['forward']				: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['forward']),
					'language_played' 				=> (isset($this->TS_VCSC_Plyr_Player_Language['played'])				? $this->TS_VCSC_Plyr_Player_Language['played']					: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['played']),
					'language_buffered' 			=> (isset($this->TS_VCSC_Plyr_Player_Language['buffered'])				? $this->TS_VCSC_Plyr_Player_Language['buffered']				: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['buffered']),
					'language_currenttime' 			=> (isset($this->TS_VCSC_Plyr_Player_Language['currenttime'])			? $this->TS_VCSC_Plyr_Player_Language['currenttime']			: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['currenttime']),
					'language_duration' 			=> (isset($this->TS_VCSC_Plyr_Player_Language['duration'])				? $this->TS_VCSC_Plyr_Player_Language['duration']				: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['duration']),
					'language_volume' 				=> (isset($this->TS_VCSC_Plyr_Player_Language['volume'])				? $this->TS_VCSC_Plyr_Player_Language['volume']					: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['volume']),
					'language_togglemute' 			=> (isset($this->TS_VCSC_Plyr_Player_Language['togglemute'])			? $this->TS_VCSC_Plyr_Player_Language['togglemute']				: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['togglemute']),
					'language_togglecaptions' 		=> (isset($this->TS_VCSC_Plyr_Player_Language['togglecaptions'])		? $this->TS_VCSC_Plyr_Player_Language['togglecaptions']			: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['togglecaptions']),
					'language_togglefullscreen' 	=> (isset($this->TS_VCSC_Plyr_Player_Language['togglefullscreen'])		? $this->TS_VCSC_Plyr_Player_Language['togglefullscreen']		: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['togglefullscreen']),
					'language_frametitle' 			=> (isset($this->TS_VCSC_Plyr_Player_Language['frametitle'])			? $this->TS_VCSC_Plyr_Player_Language['frametitle']				: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['frametitle']),
					// Other Settings
					'margin_top'					=> 0,
					'margin_bottom'					=> 0,
					'el_id'							=> '',
					'el_class'						=> '',
					'css'							=> '',
				), $atts ));
				
				// Load Required JS/CSS Files
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "false") {
					if ($lightbox_open == "true") {
						wp_enqueue_script('ts-extend-krautlightbox');
						wp_enqueue_style('ts-extend-krautlightbox');
					}
					wp_enqueue_script('ts-visual-composer-extend-front');			
					wp_enqueue_script('ts-extend-plyrvideo');
				}
				wp_enqueue_style('ts-extend-plyrvideo');
				
				// Create Global Variables
				$output 							= '';
				$styles								= '';
				$randomizer							= mt_rand(999999, 9999999);
				$inline								= TS_VCSC_FrontendAppendCustomRules('style');
			
				// Create Player ID
				if (!empty($el_id)) {
					$player_id						= $el_id;
				} else {
					$player_id						= 'ts-vcsc-plyr-player-' . $randomizer;
				}
				
				// Extract Video Sources
				if ($video_mp4_source == "true") {
					$player_mp4 					= wp_get_attachment_url($video_mp4_local);
				} else {
					$player_mp4						= $video_mp4_remote;
				}
				if ($video_ogg_source == "true") {
					$player_ogg 					= wp_get_attachment_url($video_ogg_local);
				} else {
					$player_ogg						= $video_ogg_remote;
				}
				if ($video_webm_source == "true") {
					$player_webm 					= wp_get_attachment_url($video_webm_local);
				} else {
					$player_webm					= $video_webm_remote;
				}
				
				// Extract Poster Source
				$player_poster 						= wp_get_attachment_image_src($video_poster, 'large');
				$player_poster 						= isset($player_poster[0]) ? $player_poster[0] : TS_VCSC_GetResourceURL('images/defaults/default_html5.jpg');
				
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
					if (($video_dimensions == "true") && ($video_width > 0)) {
						$lightbox_dimensions		.= ' data-width="' . $video_width . '" ';
					} else {
						$lightbox_dimensions		.= '';
					}			
				} else if ($lightbox_width == "widthpercent") {
					$lightbox_dimensions 			.= ' data-width="' . $lightbox_width_percent . '%" ';
				} else if ($lightbox_width == "widthpixel") {
					$lightbox_dimensions 			.= ' data-width="' . $lightbox_width_pixel . '" ';
				}
				if ($lightbox_height == "auto") {
					if (($video_dimensions == "true") && ($video_height > 0)) {
						$lightbox_dimensions		.= ' data-height="' . $video_height . '" ';
					} else {
						$lightbox_dimensions		.= '';
					}			
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
				
				// Auto-Open Class
				if ($content_open == "true") {
					$modal_openclass				= "nch-lightbox-open";
					if ($content_open_hide == "true") {
						$modal_hideclass			= "nch-lightbox-hide";
					} else {
						$modal_hideclass			= "";
					}
				} else {
					$modal_openclass				= "nch-lightbox-plyr no-ajaxy";
					$modal_hideclass				= "";
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
				
				// Image Size Adjustments
				if ($content_image_responsive == "true") {
					$image_dimensions				= 'width: 100%; height: auto;';
					$parent_dimensions				= 'width: ' . $content_image_width_r . '%; ' . $content_image_height . '';
				} else {
					$image_dimensions				= 'width: 100%; height: auto;';
					$parent_dimensions				= 'width: ' . $content_image_width_f . 'px; ' . $content_image_height . '';
				}
		
				// Visual Composer Style Override
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Plyr_Player', $atts);
				} else {
					$css_class						= '';
				}
				
				// Create Data Attributes
				$player_videos						= 'data-videomp4="' . $player_mp4 . '" data-videowebm="' . $player_webm . '" data-videoogv="' . $player_ogg . '"';
				$player_settings					= 'data-baseid="' . $player_id . '" data-randomizer="' . $randomizer . '" data-title="' . $video_title . '" data-videoplay="' . $player_autoplay . '" data-loop="' . $player_loop . '" data-volume="' . ($player_volume / 10) . '" data-showposterend="' . $player_showposterend . '"';
				$player_settings					.= ' data-videoposter="' . $player_poster . '" data-hidecontrols="' . $player_hidecontrols . '" data-hidecontext="' . $player_hidecontext . '" data-tooltips="' . $player_tooltips . '"';
				$player_lightbox					= '  data-thumbnail="' . $player_poster . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $lightbox_color . ' ' . $lightbox_dimensions . '';
				$player_language					= 'data-langrestart="' . $language_restart . '" data-langrewind="' . $language_rewind . '" data-langplay="' . $language_play . '" data-langpause="' . $language_pause . '" data-langforward="' . $language_forward . '"';
				$player_language					.= ' data-langplayed="' . $language_played . '" data-langbuffered="' . $language_buffered . '" data-langcurrenttime="' . $language_currenttime . '" data-langduration="' . $language_duration . '" data-langvolume="' . $language_volume . '"';
				$player_language					.= ' data-langtogglemute="' . $language_togglemute . '" data-langtogglecaptions="' . $language_togglecaptions . '" data-langtogglefullscreen="' . $language_togglefullscreen . '" data-langframetitle="' . $language_frametitle . '"';
				
				// Create Custom Theme
				if ($theme_customize == "true") {
					if ($inline == "false") {
						$styles .= '<style id="ts-fancy-tabs-main-styles-' . $tab_contid . '" type="text/css">';
					}
						// Video Play Button
						$styles .= '#' . $player_id . '-container .plyr .plyr__play-large {';
							$styles .= 'background: ' . $theme_playbutton . ';';
						$styles .= '}';
						// Video Hover Buttons
						$styles .= '#' . $player_id . '-container .plyr.plyr--video .plyr__controls button.tab-focus:focus,';
						$styles .= '#' . $player_id . '-container .plyr.plyr--video .plyr__controls button:hover {';
							$styles .= 'background: ' . $theme_hoverback . ';';
						$styles .= '}';
						// Video Progessbar Bar
						$styles .= '#' . $player_id . '-container .plyr .plyr__progress--played {';
							$styles .= 'color: ' . $theme_progressbar . ';';
						$styles .= '}';
						$styles .= '#' . $player_id . '-container .plyr input.plyr__progress--seek[type=range]:active::-webkit-slider-thumb {';
							$styles .= 'background: ' . $theme_progressbar . ';';
						$styles .= '}';
						$styles .= '#' . $player_id . '-container .plyr input.plyr__progress--seek[type=range]:active::-moz-range-thumb {';
							$styles .= 'background: ' . $theme_progressbar . ';';
						$styles .= '}';
						$styles .= '#' . $player_id . '-container .plyr input.plyr__progress--seek[type=range]:active::-ms-thumb {';
							$styles .= 'background: ' . $theme_progressbar . ';';
						$styles .= '}';
						// Volume Level Bar
						$styles .= '#' . $player_id . '-container .plyr .plyr__volume--display {';
							$styles .= 'color: ' . $theme_volumebar . ';';
						$styles .= '}';
						$styles .= '#' . $player_id . '-container .plyr input.plyr__volume--input[type=range]:active::-webkit-slider-thumb {';
							$styles .= 'background: ' . $theme_volumebar . ';';
						$styles .= '}';
						$styles .= '#' . $player_id . '-container .plyr input.plyr__volume--input[type=range]:active::-moz-range-thumb {';
							$styles .= 'background: ' . $theme_volumebar . ';';
						$styles .= '}';
						$styles .= '#' . $player_id . '-container .plyr input.plyr__volume--input[type=range]:active::-ms-thumb {';
							$styles .= 'background: ' . $theme_volumebar . ';';
						$styles .= '}';
					if ($inline == "false") {
						$styles .= '</style>';
					}
					if (($styles != "") && ($inline == "true")) {
						wp_add_inline_style('ts-visual-composer-extend-custom', TS_VCSC_MinifyCSS($styles));
					}
				}
				
				// Create Final Output
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "false") {
					if ($lightbox_open == "true") {
						$output .= '<div id="' . $player_id . '-trigger" class="ts-plyr-player-trigger" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
							// Styling Output
							if (($styles != "") && ($inline == "false")) {
								$output .= TS_VCSC_MinifyCSS($styles);
							}
							// Trigger Output
							if ($content_trigger == "poster") {
								if ($tooltip_content != '') {
									$output .= '<div class="' . $player_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $tooltip_class . '" ' . $tooltip_content . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
										$output .= '<div id="' . $player_id . '-trigger" class="' . $el_class . ' nchgrid-item nch-lightbox-modal no-ajaxy nch-lightbox-video nch-lightbox-single ' . $overlay_visible . ' nch-lightbox-hover-' . $overlay_animation . ' ' . $css_class . '" style="width: 100%; height: 100%;">';
								} else {
										$output .= '<div id="' . $player_id . '-trigger" class="' . $player_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' nchgrid-item no-ajaxy nch-lightbox-video nch-lightbox-single ' . $overlay_visible . ' nch-lightbox-hover-' . $overlay_animation . ' ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
								}
										$output .= '<a id="nch-lightbox-trigger-plyr-' . $randomizer . '" href="#' . $player_id . '-modal" class="nch-lightbox-trigger ' . $modal_openclass . ' nch-lightbox-trigger-plyr" data-title="' . $video_title . '" data-open="' . $content_open . '" data-lightbox-size="' . $lightbox_size . '" data-delay="' . $content_open_delay . '" data-type="plyr" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $player_videos . ' ' . $player_settings . ' ' . $player_language . ' ' . $player_lightbox . '>';
											$output .= '<img class="nchgrid-image-' . $overlay_animation . '" src="' . $player_poster . '" title="" style="display: block; ' . $image_dimensions . '">';
											$output .= '<div class="nchgrid-caption ' . $overlay_classes . '" style="' . $overlay_background . ' ' . $overlay_styling . '">' . $overlay_addition . '</div>';
											if (!empty($video_title)) {
												$output .= '<div class="nchgrid-caption-text" style="background: ' . $overlay_title_back . '; color: ' . $overlay_title_color . ';">' . $video_title . '</div>';
											}
										$output .= '</a>';
									$output .= '</div>';
								if ($tooltip_content != '') {
									$output .= '</div>';
								}
							}
							if ($content_trigger == "default") {
								$modal_image = TS_VCSC_GetResourceURL('images/defaults/default_html5.jpg');
								if ($tooltip_content != '') {
									$output .= '<div class="' . $player_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $tooltip_class . '" ' . $tooltip_content . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
										$output .= '<div id="' . $player_id . '-trigger" class="' . $el_class . ' nchgrid-item nch-lightbox-modal no-ajaxy nch-lightbox-video nch-lightbox-single ' . $overlay_visible . ' nch-lightbox-hover-' . $overlay_animation . ' ' . $css_class . '" style="width: 100%; height: 100%;">';
								} else {
										$output .= '<div id="' . $player_id . '-trigger" class="' . $player_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' nchgrid-item no-ajaxy nch-lightbox-video nch-lightbox-single ' . $overlay_visible . ' nch-lightbox-hover-' . $overlay_animation . ' ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
								}
										$output .= '<a id="nch-lightbox-trigger-plyr-' . $randomizer . '" href="#' . $player_id . '-modal" class="nch-lightbox-trigger ' . $modal_openclass . ' nch-lightbox-trigger-plyr" data-title="' . $video_title . '" data-open="' . $content_open . '" data-lightbox-size="' . $lightbox_size . '" data-delay="' . $content_open_delay . '" data-type="plyr" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $player_videos . ' ' . $player_settings . ' ' . $player_language . ' ' . $player_lightbox . '>';
											$output .= '<img class="nchgrid-image-' . $overlay_animation . '" src="' . $modal_image . '" title="" style="display: block; ' . $image_dimensions . '">';
											$output .= '<div class="nchgrid-caption ' . $overlay_classes . '" style="' . $overlay_background . ' ' . $overlay_styling . '">' . $overlay_addition . '</div>';
											if (!empty($video_title)) {
												$output .= '<div class="nchgrid-caption-text" style="background: ' . $overlay_title_back . '; color: ' . $overlay_title_color . ';">' . $video_title . '</div>';
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
										$output .= '<div class="' . $player_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $tooltip_class . '" ' . $tooltip_content . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
											$output .= '<div id="' . $player_id . '-trigger" class="' . $el_class . ' nchgrid-item nch-lightbox-modal no-ajaxy nch-lightbox-video nch-lightbox-single ' . $overlay_visible . ' nch-lightbox-hover-' . $overlay_animation . ' ' . $css_class . '" style="width: 100%; height: 100%;">';
									} else {
											$output .= '<div id="' . $player_id . '-trigger" class="' . $player_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' nchgrid-item no-ajaxy nch-lightbox-video nch-lightbox-single ' . $overlay_visible . ' nch-lightbox-hover-' . $overlay_animation . ' ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
									}
											$output .= '<a id="nch-lightbox-trigger-plyr-' . $randomizer . '" href="#' . $player_id . '-modal" class="nch-lightbox-trigger ' . $modal_openclass . ' nch-lightbox-trigger-plyr" data-title="' . $video_title . '" data-open="' . $content_open . '" data-lightbox-size="' . $lightbox_size . '" data-delay="' . $content_open_delay . '" data-type="plyr" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $player_videos . ' ' . $player_settings . ' ' . $player_language . ' ' . $player_lightbox . '>';
												$output .= '<img class="nchgrid-image-' . $overlay_animation . '" src="' . $modal_image . '" title="" style="display: block; ' . $image_dimensions . '">';
												$output .= '<div class="nchgrid-caption ' . $overlay_classes . '" style="' . $overlay_background . ' ' . $overlay_styling . '">' . $overlay_addition . '</div>';
												if (!empty($video_title)) {
													$output .= '<div class="nchgrid-caption-text" style="background: ' . $overlay_title_back . '; color: ' . $overlay_title_color . ';">' . $video_title . '</div>';
												}
											$output .= '</a>';
										$output .= '</div>';
									if ($tooltip_content != '') {
										$output .= '</div>';
									}
								} else {
									$output .= '<a id="nch-lightbox-trigger-plyr-' . $randomizer . '" href="#' . $player_id . '-modal" class="' . $player_id . '-parent nch-holder nch-lightbox ' . $tooltip_class . ' nch-lightbox-trigger-plyr" ' . $tooltip_content . ' style="' . $parent_dimensions . '" data-lightbox-size="' . $lightbox_size . '"  data-title="' . $video_title . '" data-type="plyr" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group) . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $player_videos . ' ' . $player_settings . ' ' . $player_language . ' ' . $player_lightbox . '>';
										$output .= '<img class="" src="' . $modal_image . '" style="display: block; ' . $image_dimensions . '">';
									$output .= '</a>';
								}
							}
							if ($content_trigger == "icon") {
								$icon_style = 'color: ' . $content_iconcolor . '; width:' . $content_iconsize . 'px; height:' . $content_iconsize . 'px; font-size:' . $content_iconsize . 'px; line-height:' . $content_iconsize . 'px;';
								$output .= '<div id="' . $player_id . '-trigger" style="" class="' . $player_id . '-parent nch-holder ts-vcsc-font-icon ts-font-icons ts-shortcode ts-icon-align-center ' . $el_class . ' ' . $css_class . ' ' . $tooltip_class . '" ' . $tooltip_content . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
									$output .= '<a id="nch-lightbox-trigger-plyr-' . $randomizer . '" href="#' . $player_id . '-modal" class="' . $modal_openclass . ' nch-lightbox-trigger-plyr" data-title="' . $video_title . '" data-open="' . $content_open . '" data-lightbox-size="' . $lightbox_size . '" data-delay="' . $content_open_delay . '" data-type="plyr" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $player_videos . ' ' . $player_settings . ' ' . $player_language . ' ' . $player_lightbox . '>';
										$output .= '<i class="ts-font-icon ' . $content_icon . '" style="' . $icon_style . '"></i>';
									$output .= '</a>';
								$output .= '</div>';
							}
							if (($content_trigger == "flat") || ($content_trigger == "flaticon")) {
								wp_enqueue_style('ts-extend-buttonsdual');
								$button_style				= $content_buttonstyle . ' ' . $content_buttonhover;				
								$output .= '<a id="nch-lightbox-trigger-plyr-' . $randomizer . '" href="#' . $player_id . '-modal" class="ts-dual-buttons-container ' . $modal_openclass . ' nch-lightbox-trigger-plyr" data-title="' . $video_title . '" data-open="' . $content_open . '" data-lightbox-size="' . $lightbox_size . '" data-delay="' . $content_open_delay . '" data-type="plyr" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $player_videos . ' ' . $player_settings . ' ' . $player_language . ' ' . $player_lightbox . '>';
									$output .= '<div id="' . $player_id . '-trigger" class="ts-dual-buttons-wrapper clearFixMe ' . $button_style . ' ' . $player_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' ' . $tooltip_class . ' ' . $css_class . '" ' . $tooltip_content . ' style="width: 100%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
										if (($content_icon != '') && ($content_icon != 'transparent') && ($content_trigger == "flaticon")) {
											$output .= '<i class="ts-dual-buttons-icon ' . $content_icon . '" style="font-size: ' . $content_buttonsize . 'px; line-height: ' . $content_buttonsize . 'px;"></i>';
										}
										$output .= '<span class="ts-dual-buttons-title" style="font-size: ' . $content_buttonsize . 'px; line-height: ' . $content_buttonsize . 'px;">' . $content_buttontext . '</span>';			
									$output .= '</div>';
								$output .= '</a>';
							}
							if ($content_trigger == "winged") {
								$output .= '<div id="' . $player_id . '-trigger" class="' . $player_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' ' . $tooltip_class . ' ' . $css_class . '" ' . $tooltip_content . ' style="display: block; width: 100%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
									$output .= '<div class="ts-lightbox-button-1 clearFixMe">';
										$output .= '<div class="top">' . $video_title . '</div>';
										$output .= '<div class="bottom">' . $content_subtitle . '</div>';
										$output .= '<a id="nch-lightbox-trigger-plyr-' . $randomizer . '" href="#' . $player_id . '-modal" class="icon ' . $modal_openclass . ' nch-lightbox-trigger-plyr" data-open="' . $content_open . '" data-lightbox-size="' . $lightbox_size . '" data-delay="' . $content_open_delay . '" data-type="plyr" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $player_videos . ' ' . $player_settings . ' ' . $player_language . ' ' . $player_lightbox . '><span class="popup">' . $content_buttontext . '</span></a>';
									$output .= '</div>';
								$output .= '</div>';
							}
							if ($content_trigger == "simple") {
								$output .= '<div id="' . $player_id . '-trigger" class="' . $player_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' ' . $tooltip_class . ' ' . $css_class . '" ' . $tooltip_content . ' style="display: block; width: 100%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
									$output .= '<a id="nch-lightbox-trigger-plyr-' . $randomizer . '" href="#' . $player_id . '-modal" class="ts-lightbox-button-2 icon ' . $modal_openclass . ' nch-lightbox-trigger-plyr" data-open="' . $content_open . '" data-lightbox-size="' . $lightbox_size . '" data-delay="' . $content_open_delay . '" data-type="plyr" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $player_videos . ' ' . $player_settings . ' ' . $player_language . ' ' . $player_lightbox . '><span class="popup">' . $content_buttontext . '</span></a>';
								$output .= '</div>';
							}
							if ($content_trigger == "text") {
								$output .= '<div id="' . $player_id . '-trigger" class="' . $player_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' ' . $css_class . '" style="text-align: center; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
									$output .= '<a id="nch-lightbox-trigger-plyr-' . $randomizer . '" href="#' . $player_id . '-modal" class="' . $tooltip_class . ' ' . $modal_openclass . ' nch-lightbox-trigger-plyr" ' . $tooltip_content . ' data-open="' . $content_open . '" data-lightbox-size="' . $lightbox_size . '" data-delay="' . $content_open_delay . '" data-type="plyr" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $player_videos . ' ' . $player_settings . ' ' . $player_language . ' ' . $player_lightbox . '>' . $content_text . '</a>';
								$output .= '</div>';
							}
							if ($content_trigger == "custom") {
								if ($content_raw != "") {
									$content_raw =  rawurldecode(base64_decode(strip_tags($content_raw)));
									$output .= '<div id="' . $player_id . '-trigger" class="' . $player_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' ' . $css_class . '" style="text-align: center; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
										$output .= '<a id="nch-lightbox-trigger-plyr-' . $randomizer . '" href="#' . $player_id . '-modal" class="' . $tooltip_class . ' ' . $modal_openclass . ' nch-lightbox-trigger-plyr" ' . $tooltip_content . ' data-open="' . $content_open . '" data-lightbox-size="' . $lightbox_size . '" data-delay="' . $content_open_delay . '" data-type="plyr" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="0" data-duration="' . $lightbox_speed . '" ' . $player_videos . ' ' . $player_settings . ' ' . $player_language . ' ' . $player_lightbox . '>';
											$output .= $content_raw;
										$output .= '</a>';
									$output .= '</div>';
								}
							}
							// Player Output
							$output .= '<div id="' . $player_id . '-modal" class="ts-plyr-player-modal" style="display: none;"></div>';
						$output .= '</div>';
					} else {
						$output .= '<div id="' . $player_id . '-container" class="ts-plyr-player-container ts-plyr-player-direct" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
							// Styling Output
							if (($styles != "") && ($inline == "false")) {
								$output .= TS_VCSC_MinifyCSS($styles);
							}
							// Player Output
							$output .= '<div id="' . $player_id . '-element" class="ts-plyr-player-element ' . $el_class . '" ' . $player_settings . ' ' . $player_language . '>';
								$output .= '<video poster="' . $player_poster . '" title="' . $video_title . '" controls>';
									if ($player_mp4 != "") {
										$output .= '<source src="' . $player_mp4 . '" type="video/mp4">';
									}
									if ($player_webm != "") {
										$output .= '<source src="' . $player_webm . '" type="video/webm">';
									}
									if ($player_ogg != "") {
										$output .= '<source src="' . $player_ogg . '" type="video/ogv">';
									}
								$output .= '</video>';
							$output .= '</div>';
						$output .= '</div>';
					}
				} else {
					$output .= '<div id="' . $player_id . '-frontend" class="ts-plyr-player-frontend">';
					$output .= '<span style="font-weight: bold; font-size: 14px;">' . __( "TS Plyr Video Player", "ts_visual_composer_extend" ) . '</span>';
						$output .= '<span style="font-weight: bold;">' . __( "Video Sources", "ts_visual_composer_extend" ) . '</span>';
						$output .= '<span>' . __( "MP4 Video", "ts_visual_composer_extend" ) . ': ' . ($player_mp4 != "" ? $player_mp4 : __( "N/A", "ts_visual_composer_extend" )) . '</span>';
						$output .= '<span>' . __( "WEBM Video", "ts_visual_composer_extend" ) . ': ' . ($player_webm != "" ? $player_webm : __( "N/A", "ts_visual_composer_extend" )) . '</span>';
						$output .= '<span>' . __( "OGG Video", "ts_visual_composer_extend" ) . ': ' . ($player_ogg != "" ? $player_ogg : __( "N/A", "ts_visual_composer_extend" )) . '</span>';
						$output .= '<span style="font-weight: bold;">' . __( "Player Settings", "ts_visual_composer_extend" ) . '</span>';
						$output .= '<span>' . __( "Video Title", "ts_visual_composer_extend" ) . ': ' . ($video_title != "" ? $video_title : __( "N/A", "ts_visual_composer_extend" )) . '</span>';
						$output .= '<span>' . __( "Video Auto-Play", "ts_visual_composer_extend" ) . ': ' . ($player_autoplay != "" ? $player_autoplay : __( "N/A", "ts_visual_composer_extend" )) . '</span>';
						$output .= '<span>' . __( "Video Lightbox", "ts_visual_composer_extend" ) . ': ' . ($lightbox_open != "" ? $lightbox_open : __( "N/A", "ts_visual_composer_extend" )) . '</span>';
					$output .= '</div> ';
				}
				
				echo $output;
			
				$myvariable = ob_get_clean();
				return $myvariable;
			}
			
			// Add Plyr Video Player Element
			function TS_VCSC_Add_PlyrVideoPlayer_Element_Define() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Plyr Video Player
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					"name"                          	=> __( "TS Plyr Video Player", "ts_visual_composer_extend" ),
					"base"                          	=> "TS_VCSC_Plyr_Player",
					"icon" 	                        	=> "ts-composer-element-icon-plyr-video",
					"category"                      	=> __( "VC Extensions", "ts_visual_composer_extend" ),
					"description"                   	=> __("Place a Plyr Video player element", "ts_visual_composer_extend"),
					"admin_enqueue_js"              	=> "",
					"admin_enqueue_css"             	=> "",
					"params"                        	=> array(
						// Video Sources
						array(
							"type"                  	=> "seperator",
							"param_name"            	=> "seperator_1",
							"seperator"					=> "Video Sources",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "MP4 Video Source", "ts_visual_composer_extend" ),
							"param_name"		   	 	=> "video_mp4_source",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want to use a local or remote MP4 video file.", "ts_visual_composer_extend" )
						),
						array(
							"type"                  	=> "videoselect",
							"heading"               	=> __( "MP4 Video", "ts_visual_composer_extend" ),
							"param_name"            	=> "video_mp4_local",
							"video_format"				=> "mp4,m4v",
							"value"                	 	=> "",
							"description"           	=> __( "Select a local MP4 video from WordPress.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "video_mp4_source", 'value' => 'true' ),
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "MP4 Video", "ts_visual_composer_extend" ),
							"param_name"            	=> "video_mp4_remote",
							"value"                 	=> "",
							"description"           	=> __( "Enter the remote path to the MP4 version of the video.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "video_mp4_source", 'value' => 'false' ),
						),			
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "WEBM Video Source", "ts_visual_composer_extend" ),
							"param_name"		    	=> "video_webm_source",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want to use a local or remote WEBM video file.", "ts_visual_composer_extend" )
						),
						array(
							"type"                  	=> "videoselect",
							"heading"               	=> __( "WEBM Video", "ts_visual_composer_extend" ),
							"param_name"            	=> "video_webm_local",
							"video_format"				=> "webm",
							"value"                 	=> "",
							"description"           	=> __( "Select a local WEBM video from WordPress.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "video_webm_source", 'value' => 'true' ),
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "WEBM Video", "ts_visual_composer_extend" ),
							"param_name"            	=> "video_webm_remote",
							"value"                 	=> "",
							"description"           	=> __( "Enter the remote path to the WEBM version of the video.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "video_webm_source", 'value' => 'false' ),
						),				
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "OGV Video Source", "ts_visual_composer_extend" ),
							"param_name"		    	=> "video_ogg_source",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want to use a local or remote OGV video file.", "ts_visual_composer_extend" )
						),
						array(
							"type"                 	 	=> "videoselect",
							"heading"               	=> __( "OGV Video", "ts_visual_composer_extend" ),
							"param_name"            	=> "video_ogg_local",
							"video_format"				=> "ogg,ogv",
							"value"                 	=> "",
							"description"           	=> __( "Select a local OGV video from WordPress.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "video_ogg_source", 'value' => 'true' ),
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "OGV Video", "ts_visual_composer_extend" ),
							"param_name"            	=> "video_ogg_remote",
							"value"                 	=> "",
							"description"           	=> __( "Enter the remote path to the OGV version of the video.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "video_ogg_source", 'value' => 'false' ),
						),
						// Video Information
						array(
							"type"				    	=> "seperator",
							"param_name"		    	=> "seperator_2",
							"seperator"					=> "Video Information",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Video: Title", "ts_visual_composer_extend" ),
							"param_name"            	=> "video_title",
							"value"                 	=> "",
							"admin_label"           	=> true,
							"description"           	=> __( "Enter an optional title for the video.", "ts_visual_composer_extend" ),
						),
						array(
							"type"                  	=> "attach_image",
							"heading"               	=> __( "Video: Poster", "ts_visual_composer_extend" ),
							"param_name"            	=> "video_poster",
							"value"                 	=> "",
							"description"           	=> __( "Select the image that should be used as video poster.", "ts_visual_composer_extend" ),
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Video: Dimensions", "ts_visual_composer_extend" ),
							"param_name"		    	=> "video_dimensions",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want to provide the original dimensions of the video (width/height).", "ts_visual_composer_extend" ),
						),
						array(
							"type"                  	=> "nouislider",
							"heading"               	=> __( "Video: Width", "ts_visual_composer_extend" ),
							"param_name"            	=> "video_width",
							"value"                 	=> "1280",
							"min"                   	=> "360",
							"max"                   	=> "3840",
							"step"                  	=> "1",
							"unit"                  	=> 'px',
							"description"           	=> __( "Define the width of the video to be shown within the player.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "video_dimensions", 'value' => 'true' ),
						),
						array(
							"type"                  	=> "nouislider",
							"heading"               	=> __( "Video: Height", "ts_visual_composer_extend" ),
							"param_name"            	=> "video_height",
							"value"                 	=> "720",
							"min"                   	=> "240",
							"max"                   	=> "2160",
							"step"                  	=> "1",
							"unit"                  	=> 'px',
							"description"           	=> __( "Define the height of the video to be shown within the player.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "video_dimensions", 'value' => 'true' ),
						),
						// Player Settings
						array(
							"type"				    	=> "seperator",
							"param_name"		    	=> "seperator_3",
							"seperator"					=> "Player Settings",
							"group" 					=> "Player",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Video: Auto-Play", "ts_visual_composer_extend" ),
							"param_name"		    	=> "player_autoplay",
							"value"                 	=> "false",
							"admin_label"           	=> true,
							"description"		    	=> __( "Switch the toggle if you want the media to start playing upon page load (non-mobile devices).", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Player",
						),	
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Video: Loop", "ts_visual_composer_extend" ),
							"param_name"		    	=> "player_loop",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want the media to loop and start over each time it has finished.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Player",
						),
						array(
							"type"                  	=> "nouislider",
							"heading"               	=> __( "Video: Volume", "ts_visual_composer_extend" ),
							"param_name"            	=> "player_volume",
							"value"                 	=> "50",
							"min"                   	=> "0",
							"max"                   	=> "100",
							"step"                  	=> "1",
							"unit"                  	=> '%',
							"description"           	=> __( "Select the startup volume for the media; set to 0 (Zero) to mute; desktop only (valid for first session).", "ts_visual_composer_extend" ),
							"group" 					=> "Player",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Video: Hide Controls", "ts_visual_composer_extend" ),
							"param_name"		    	=> "player_hidecontrols",
							"value"                 	=> "true",
							"description"		    	=> __( "Switch the toggle if you want to automatically hide the video controls when the video is playing.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Player",
						),	
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Video: Show Poster", "ts_visual_composer_extend" ),
							"param_name"		    	=> "player_showposterend",
							"value"                 	=> "true",
							"description"		    	=> __( "Switch the toggle if you want to switch back to the video poster once the video has finished playing.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Player",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Video: Tooltips", "ts_visual_composer_extend" ),
							"param_name"		    	=> "player_tooltips",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want to show tooltips for the video control elements.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Player",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Video: Hide Context", "ts_visual_composer_extend" ),
							"param_name"		    	=> "player_hidecontext",
							"value"                 	=> "true",
							"description"		    	=> __( "Switch the toggle if you want to hide the right click context menu for the video.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Player",
						),
						// Theme Settings
						array(
							"type"				    	=> "seperator",
							"param_name"		    	=> "seperator_4",
							"seperator"					=> "Theme Settings",
							"group" 					=> "Player",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Customize Theme", "ts_visual_composer_extend" ),
							"param_name"		    	=> "theme_customize",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want to customize some aspects of the player theme.", "ts_visual_composer_extend" ),
							"group" 					=> "Player",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Theme: Play Button", "ts_visual_composer_extend" ),
							"param_name"        		=> "theme_playbutton",
							"value"             		=> "#3498db",
							"description"       		=> __( "Define the background color for the main play button.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Player",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Theme: Hover Background", "ts_visual_composer_extend" ),
							"param_name"        		=> "theme_hoverback",
							"value"             		=> "#3498db",
							"description"       		=> __( "Define the background color for control buttons when hovering.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Player",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Theme: Progress Bar", "ts_visual_composer_extend" ),
							"param_name"        		=> "theme_progressbar",
							"value"             		=> "#3498db",
							"description"       		=> __( "Define the background color for the player progress bar.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Player",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Theme: Volume Bar", "ts_visual_composer_extend" ),
							"param_name"        		=> "theme_volumebar",
							"value"             		=> "#3498db",
							"description"       		=> __( "Define the background color for the volume bar.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "theme_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Player",
						),
						// Lightbox Settings
						array(
							"type"				    	=> "seperator",
							"param_name"		    	=> "seperator_5",
							"seperator"					=> "Lightbox Settings",
							"group" 					=> "Lightbox",
						),
						array(
							"type"              		=> "switch_button",
							"heading"           		=> __( "Show in Lightbox", "ts_visual_composer_extend" ),
							"param_name"        		=> "lightbox_open",
							"value"             		=> "false",
							"description"       		=> __( "Switch the toggle if the video should be opened within a lightbox.", "ts_visual_composer_extend" ),
							"admin_label"           	=> true,
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
							"dependency"            	=> array( 'element' => "lightbox_open", 'value' => 'true' ),
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
							"max"                  	 	=> "1920",
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
							"dependency"            	=> array( 'element' => "lightbox_open", 'value' => 'true' ),
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
							"description"           	=> __( "Select the maximum element height inside the lightbox in percent.", "ts_visual_composer_extend" ),
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
							"description"           	=> __( "Enter a custom group name to manually build group with other video items; leave empty for non-grouping", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "lightbox_open", 'value' => "true" ),
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
							"description"           	=> __( "Select the transition effect to be used for the image in the lightbox.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "lightbox_open", 'value' => 'true' ),
							"group" 					=> "Lightbox",
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Backlight Color", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_backlight_choice",
							"width"                 	=> 150,
							"value"                 	=> array(
								__( 'Predefined Color', "ts_visual_composer_extend" )			=> "predefined",
								__( 'Custom Color', "ts_visual_composer_extend" )				=> "customized",
								__( 'Transparent Backlight', "ts_visual_composer_extend" )     	=> "hideit",
							),
							"description"           	=> __( "Select the (backlight) color style for the popup box.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "lightbox_open", 'value' => 'true' ),
							"group" 					=> "Lightbox",
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Select Backlight Color", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_backlight_color",
							"width"                 	=> 150,
							"value"                 	=> array(
								__( 'Default', "ts_visual_composer_extend" )      		=> "#0084E2",
								__( 'Neutral', "ts_visual_composer_extend" )      		=> "#FFFFFF",
								__( 'Success', "ts_visual_composer_extend" )      		=> "#4CFF00",
								__( 'Warning', "ts_visual_composer_extend" )      		=> "#EA5D00",
								__( 'Error', "ts_visual_composer_extend" )        		=> "#CC0000",
								__( 'None', "ts_visual_composer_extend" )         		=> "#000000",
							),
							"description"           	=> __( "Select the predefined backlight color for the modal popup.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "lightbox_backlight_choice", 'value' => 'predefined' ),
							"group" 					=> "Lightbox",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Select Backlight Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "lightbox_backlight_custom",
							"value"             		=> "#000000",
							"description"       		=> __( "Define a custom backlight color for the modal popup.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "lightbox_backlight_choice", 'value' => 'customized' ),
							"group" 					=> "Lightbox",
						),
						// Trigger Settings
						array(
							"type"				    	=> "seperator",
							"param_name"		    	=> "seperator_6",
							"seperator"					=> "Trigger Settings",
							"dependency"            	=> array( 'element' => "lightbox_open", 'value' => 'true' ),
							"group" 					=> "Trigger",
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Trigger Type", "ts_visual_composer_extend" ),
							"param_name"            	=> "content_trigger",
							"value"                 	=> array(
								__("Poster Image", "ts_visual_composer_extend")          	=> "poster",
								__("Default Image", "ts_visual_composer_extend")          	=> "default",
								__("Custom Image", "ts_visual_composer_extend")           	=> "image",
								__("Font Icon", "ts_visual_composer_extend")              	=> "icon",
								__("Winged Button", "ts_visual_composer_extend")          	=> "winged",
								__("Simple Button", "ts_visual_composer_extend")          	=> "simple",
								__("Flat Icon Button", "ts_visual_composer_extend")       	=> "flaticon",
								__("Flat Button", "ts_visual_composer_extend")       		=> "flat",
								__("Text", "ts_visual_composer_extend")                   	=> "text",
								__("Custom HTML", "ts_visual_composer_extend")            	=> "custom",
							),
							"description"           	=> __( "Select the type of trigger to click on in order to show the lightbox.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "lightbox_open", 'value' => 'true' ),
							"group" 					=> "Trigger",
						),
						// Custom Image
						array(
							"type"                  	=> "attach_image",
							"heading"               	=> __( "Select Image", "ts_visual_composer_extend" ),
							"param_name"            	=> "content_image",
							"value"                 	=> "",
							"description"           	=> __( "Select the preview image for the modal popup.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "content_trigger", 'value' => 'image' ),
							"group" 					=> "Trigger",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Simple Image Only", "ts_visual_composer_extend" ),
							"param_name"		    	=> "content_image_simple",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want display just the image without any styling.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "content_trigger", 'value' => 'image' ),
							"group" 					=> "Trigger",
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Auto Height Setting", "ts_visual_composer_extend" ),
							"param_name"            	=> "content_image_height",
							"width"                 	=> 150,
							"value"                 	=> array(
								__( '100% Height Setting', "ts_visual_composer_extend" )		=> "height: 100%;",
								__( 'Auto Height Setting', "ts_visual_composer_extend" )     	=> "height: auto;",
							),
							"description"           	=> __( "Select what CSS height setting should be applied to the image (change only if image height does not display correctly).", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "content_trigger", 'value' => array('poster', 'default', 'image') ),
							"group" 					=> "Trigger",
						),
						// Font Icon
						array(
							"type" 						=> "icons_panel",
							'heading' 					=> __( 'Select Icon', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'content_icon',
							'value'						=> '',
							"settings" 					=> array(
								"emptyIcon" 					=> false,
								'emptyIconValue'				=> 'transparent',
								"type" 							=> 'extensions',
							),
							"description"       		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorVisualSelector == "true" ? __( "Select the icon you want to display.", "ts_visual_composer_extend" ) : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconSelectorString),
							"dependency"            	=> array( 'element' => "content_trigger", 'value' => array('icon', 'flaticon') ),
							"group" 					=> "Trigger",
						),			
						array(
							"type"                  	=> "nouislider",
							"heading"               	=> __( "Icon Size", "ts_visual_composer_extend" ),
							"param_name"            	=> "content_iconsize",
							"value"                 	=> "30",
							"min"                   	=> "16",
							"max"                   	=> "512",
							"step"                  	=> "1",
							"unit"                  	=> 'px',
							"description"           	=> __( "Select the icon size", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "content_trigger", 'value' => 'icon' ),
							"group" 					=> "Trigger",
						),
						array(
							"type"                  	=> "colorpicker",
							"heading"               	=> __( "Icon Color", "ts_visual_composer_extend" ),
							"param_name"            	=> "content_iconcolor",
							"value"                 	=> "#cccccc",
							"description"           	=> __( "Define the color of the icon.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "content_trigger", 'value' => 'icon' ),
							"group" 					=> "Trigger",
						),
						// Flat Button
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Button Color Style", "ts_visual_composer_extend" ),
							"param_name"            	=> "content_buttonstyle",
							"width"                 	=> 300,
							"value"                 	=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Flat_Button_Default_Colors,
							"description"           	=> __( "Select the general color style for button.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "content_trigger", 'value' => array('flat', 'flaticon') ),
							"group" 					=> "Trigger",
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Button Hover Style", "ts_visual_composer_extend" ),
							"param_name"            	=> "content_buttonhover",
							"width"                 	=> 300,
							"value"                 	=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Flat_Button_Hover_Colors,
							"description"           	=> __( "Select the general hover style for button.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "content_trigger", 'value' => array('flat', 'flaticon') ),
							"group" 					=> "Trigger",
						),
						// Button
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Button Text", "ts_visual_composer_extend" ),
							"param_name"            	=> "content_buttontext",
							"value"                 	=> "View Video",
							"description"           	=> __( "Enter the text for the button.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "content_trigger", 'value' => array('winged', 'simple', 'flat', 'flaticon') ),
							"group" 					=> "Trigger",
						),
						array(
							"type"                  	=> "nouislider",
							"heading"               	=> __( "Button Text Size", "ts_visual_composer_extend" ),
							"param_name"            	=> "content_buttonsize",
							"value"                 	=> "16",
							"min"                   	=> "12",
							"max"                   	=> "20",
							"step"                  	=> "1",
							"unit"                  	=> 'px',
							"description"           	=> __( "Select the top margin for the element.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "content_trigger", 'value' => array('flat', 'flaticon') ),
							"group" 					=> "Trigger",
						),
						// Text Link
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Trigger Text", "ts_visual_composer_extend" ),
							"param_name"            	=> "content_text",
							"value"                 	=> "",
							"description"           	=> __( "Enter the trigger text for the modal popup.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "content_trigger", 'value' => 'text' ),
							"group" 					=> "Trigger",
						),
						// Custom Code
						array(
							"type"                  	=> "textarea_raw_html",
							"heading"               	=> __("Raw HTML", "ts_visual_composer_extend"),
							"param_name"            	=> "content_raw",
							"value"                 	=> base64_encode(""),
							"description"           	=> __("Enter your custom HTML code; code will be wrapped in appropriate link automatically.", "ts_visual_composer_extend"),
							"dependency"            	=> array( 'element' => "content_trigger", 'value' => 'custom' ),
							"group" 					=> "Trigger",
						),
						// Subtitle
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Subtitle", "ts_visual_composer_extend" ),
							"param_name"            	=> "content_subtitle",
							"value"                 	=> "",
							"description"           	=> __( "Enter a subtitle for video trigger.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "content_trigger", 'value' => array('winged') ),
							"group" 					=> "Trigger",
						),			
						// Overlay Settings
						array(
							"type"                  	=> "seperator",
							"param_name"            	=> "seperator_7",
							"seperator"             	=> "Overlay Settings",
							"dependency"        		=> array( 'element' => "content_trigger", 'value' => array('default', 'image', 'poster') ),				
							"group" 					=> "Trigger",
						),			
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Overlay: Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "overlay_background",
							"value"             		=> "rgba(24, 24, 24, 0.3)",
							"description"       		=> __( "Select the background color and opacity for the overlay.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "content_trigger", 'value' => array('default', 'image', 'poster') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",	
							"group" 					=> "Trigger"
						),		
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Overlay: Animation", "ts_visual_composer_extend" ),
							"param_name"        		=> "overlay_animation",
							"width"             		=> 300,
							"value"             		=> array(
								__( "Zoom Effect", "ts_visual_composer_extend" )				=> "zoom",
								__( "Zoom + Rotate Effect", "ts_visual_composer_extend" )		=> "rotate",
								__( "No Effect", "ts_visual_composer_extend" )					=> "none",
							),
							"description"       		=> __( "Select if and what type of animation should be applied to the image on hover.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "content_trigger", 'value' => array('default', 'image', 'poster') ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Trigger"
						),			
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Overlay: Decoration", "ts_visual_composer_extend" ),
							"param_name"        		=> "overlay_decoration",
							"width"             		=> 300,
							"value"             		=> array(
								__( "Default Image", "ts_visual_composer_extend" )				=> "default",
								__( "Custom Internal Image", "ts_visual_composer_extend" )		=> "image",
								__( "Custom External Image", "ts_visual_composer_extend" )		=> "external",
								__( "Font Icon", "ts_visual_composer_extend" )					=> "icon",
								__( "No Decoration", "ts_visual_composer_extend" )				=> "none",
							),
							"description"       		=> __( "Select if and how the overlay should be decorated.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"        		=> array( 'element' => "content_trigger", 'value' => array('default', 'image', 'poster') ),
							"group" 					=> "Trigger",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Overlay: Visibility", "ts_visual_composer_extend" ),
							"param_name"        		=> "overlay_visibility",
							"width"             		=> 300,
							"value"             		=> array(
								__( "Show Only On Hover", "ts_visual_composer_extend" )			=> "hover",
								__( "Always Show Decoration", "ts_visual_composer_extend" )		=> "only_deco",
								__( "Always Show Title", "ts_visual_composer_extend" )			=> "only_title",
								__( "Always Show Full Overlay", "ts_visual_composer_extend" )	=> "always",
							),
							"admin_label"           	=> true,
							"description"       		=> __( "Select if and when the overlay should be visible.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"        		=> array( 'element' => "content_trigger", 'value' => array('default', 'image', 'poster') ),
							"group" 					=> "Trigger",
						),			
						array(
							"type"              		=> "attach_image",
							"heading"           		=> __( "Overlay: Decoration Image", "ts_visual_composer_extend" ),
							"param_name"        		=> "overlay_image",
							"value"             		=> "",
							"description"       		=> __( "Select an image to be used as decoration for the overlay.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "overlay_decoration", 'value' => 'image' ),
							"group" 					=> "Trigger",
						),	
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "Overlay: Decoration Image", "ts_visual_composer_extend" ),
							"param_name"        		=> "overlay_external",
							"value"             		=> "",
							"description"       		=> __( "Enter the full path to the image to be used as decoration for the overlay.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "overlay_decoration", 'value' => 'external' ),
							"group" 					=> "Trigger",
						),
						array(
							"type" 						=> "icons_panel",
							'heading' 					=> __( 'Overlay: Decoration Icon', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'overlay_icon_name',
							'value'						=> '',
							"settings" 					=> array(
								"emptyIcon" 					=> false,
								'emptyIconValue'				=> 'transparent',
								"type" 							=> 'extensions',
							),
							"description"       		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorVisualSelector == "true" ? __( "Select the icon to be used as decoration for the overlay.", "ts_visual_composer_extend" ) : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconSelectorString),
							"dependency"        		=> array( 'element' => "overlay_decoration", 'value' => 'icon' ),
							"group" 					=> "Trigger",
						),			
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Overlay: Decoration Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "overlay_icon_color",
							"value"             		=> "#ededed",
							"description"       		=> __( "Select the color for the decoration element.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "overlay_decoration", 'value' => 'icon' ),
							"group" 					=> "Trigger",
						),
						array(
							"type"                  	=> "nouislider",
							"heading"               	=> __( "Overlay: Decoration Size", "ts_visual_composer_extend" ),
							"param_name"           	 	=> "overlay_size",
							"value"                 	=> "100",
							"min"                   	=> "50",
							"max"                   	=> "200",
							"step"                  	=> "1",
							"unit"                  	=> 'px',
							"description"           	=> __( "Define the size of the decoration element in the overlay.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "overlay_decoration", 'value' => array('icon', 'image', 'external') ),
							"group" 					=> "Trigger",
						),
						array(
							"type"                  	=> "nouislider",
							"heading"               	=> __( "Overlay: Decoration Opacity", "ts_visual_composer_extend" ),
							"param_name"            	=> "overlay_opacity",
							"value"                 	=> "75",
							"min"                   	=> "50",
							"max"                   	=> "100",
							"step"                  	=> "1",
							"unit"                  	=> '%',
							"description"           	=> __( "Define the opacity of the decoration element in the overlay.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "overlay_decoration", 'value' => array('icon', 'image', 'external') ),
							"group" 					=> "Trigger",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Overlay: Title Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "overlay_title_color",
							"value"             		=> "#ffffff",
							"description"       		=> __( "Select the font color for the overlay title.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"        		=> array( 'element' => "content_trigger", 'value' => array('default', 'image', 'poster') ),
							"group" 					=> "Trigger",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Overlay: Title Background", "ts_visual_composer_extend" ),
							"param_name"        		=> "overlay_title_back",
							"value"             		=> "rgba(0, 0, 0, 0.4)",
							"description"       		=> __( "Select the background color and opacity for the overlay title.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"        		=> array( 'element' => "content_trigger", 'value' => array('default', 'image', 'poster') ),
							"group" 					=> "Trigger",
						),
						// Auto-Open Lightbox
						array(
							"type"				    	=> "seperator",
							"param_name"		    	=> "seperator_8",
							"seperator"					=> "Auto-Open Lightbox",
							"dependency"            	=> array( 'element' => "lightbox_open", 'value' => 'true' ),
							"group" 					=> "Trigger",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Show on Page Load", "ts_visual_composer_extend" ),
							"param_name"		    	=> "content_open",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want show the popup on page load (limit to one per page).", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "lightbox_open", 'value' => 'true' ),
							"group" 					=> "Trigger",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Hide Popup Trigger on Page", "ts_visual_composer_extend" ),
							"param_name"		    	=> "content_open_hide",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want show or hide the popup trigger on the page.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "content_open", 'value' => 'true' ),
							"group" 					=> "Trigger",
						),
						array(
							"type"                  	=> "nouislider",
							"heading"               	=> __( "Time Delay", "ts_visual_composer_extend" ),
							"param_name"            	=> "content_open_delay",
							"value"                 	=> "0",
							"min"                   	=> "0",
							"max"                   	=> "10000",
							"step"                  	=> "100",
							"unit"                  	=> 'ms',
							"description"           	=> __( "Define the delay in ms until the modal popup should be shown (starting from 'Document Ready').", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "content_open", 'value' => 'true' ),
							"group" 					=> "Trigger",
						),
						// Language Settings
						array(
							"type"				    	=> "seperator",
							"param_name"		    	=> "seperator_9",
							"seperator"					=> "Language Settings",
							"group" 					=> "Language",
						),
						array(
							"type"              		=> "switch_button",
							"heading"           		=> __( "Customize Text Strings", "ts_visual_composer_extend" ),
							"param_name"        		=> "language_customize",
							"value"             		=> "false",
							"description"       		=> __( "Switch the toggle if you want to customize some text strings used within the player.", "ts_visual_composer_extend" ),
							"group" 					=> "Language",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['restart'],
							"param_name"            	=> "language_restart",
							"value"                 	=> (isset($this->TS_VCSC_Plyr_Player_Language['restart']) ? $this->TS_VCSC_Plyr_Player_Language['restart'] : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['restart']),
							"dependency"            	=> array( 'element' => "language_customize", 'value' => 'true' ),
							"group" 					=> "Language",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['rewind'],
							"param_name"            	=> "language_rewind",
							"value"                 	=> (isset($this->TS_VCSC_Plyr_Player_Language['rewind']) ? $this->TS_VCSC_Plyr_Player_Language['rewind'] : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['rewind']),
							"dependency"            	=> array( 'element' => "language_customize", 'value' => 'true' ),
							"group" 					=> "Language",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['play'],
							"param_name"            	=> "language_play",
							"value"                 	=> (isset($this->TS_VCSC_Plyr_Player_Language['play']) ? $this->TS_VCSC_Plyr_Player_Language['play'] : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['play']),
							"dependency"            	=> array( 'element' => "language_customize", 'value' => 'true' ),
							"group" 					=> "Language",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['pause'],
							"param_name"            	=> "language_pause",
							"value"                 	=> (isset($this->TS_VCSC_Plyr_Player_Language['pause']) ? $this->TS_VCSC_Plyr_Player_Language['pause'] : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['pause']),
							"dependency"            	=> array( 'element' => "language_customize", 'value' => 'true' ),
							"group" 					=> "Language",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['forward'],
							"param_name"            	=> "language_forward",
							"value"                 	=> (isset($this->TS_VCSC_Plyr_Player_Language['forward']) ? $this->TS_VCSC_Plyr_Player_Language['forward'] : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['forward']),
							"dependency"            	=> array( 'element' => "language_customize", 'value' => 'true' ),
							"group" 					=> "Language",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['played'],
							"param_name"            	=> "language_played",
							"value"                 	=> (isset($this->TS_VCSC_Plyr_Player_Language['played']) ? $this->TS_VCSC_Plyr_Player_Language['played'] : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['played']),
							"dependency"            	=> array( 'element' => "language_customize", 'value' => 'true' ),
							"group" 					=> "Language",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['buffered'],
							"param_name"            	=> "language_buffered",
							"value"                 	=> (isset($this->TS_VCSC_Plyr_Player_Language['buffered']) ? $this->TS_VCSC_Plyr_Player_Language['buffered'] : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['buffered']),
							"dependency"            	=> array( 'element' => "language_customize", 'value' => 'true' ),
							"group" 					=> "Language",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['currenttime'],
							"param_name"            	=> "language_currenttime",
							"value"                 	=> (isset($this->TS_VCSC_Plyr_Player_Language['currenttime']) ? $this->TS_VCSC_Plyr_Player_Language['currenttime'] : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['currenttime']),
							"dependency"            	=> array( 'element' => "language_customize", 'value' => 'true' ),
							"group" 					=> "Language",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['duration'],
							"param_name"            	=> "language_duration",
							"value"                 	=> (isset($this->TS_VCSC_Plyr_Player_Language['duration']) ? $this->TS_VCSC_Plyr_Player_Language['duration'] : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['duration']),
							"dependency"            	=> array( 'element' => "language_customize", 'value' => 'true' ),
							"group" 					=> "Language",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['volume'],
							"param_name"            	=> "language_volume",
							"value"                 	=> (isset($this->TS_VCSC_Plyr_Player_Language['volume']) ? $this->TS_VCSC_Plyr_Player_Language['volume'] : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['volume']),
							"dependency"            	=> array( 'element' => "language_customize", 'value' => 'true' ),
							"group" 					=> "Language",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['togglemute'],
							"param_name"            	=> "language_togglemute",
							"value"                 	=> (isset($this->TS_VCSC_Plyr_Player_Language['togglemute']) ? $this->TS_VCSC_Plyr_Player_Language['togglemute'] : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['togglemute']),
							"dependency"            	=> array( 'element' => "language_customize", 'value' => 'true' ),
							"group" 					=> "Language",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['togglecaptions'],
							"param_name"            	=> "language_togglecaptions",
							"value"                 	=> (isset($this->TS_VCSC_Plyr_Player_Language['togglecaptions']) ? $this->TS_VCSC_Plyr_Player_Language['togglecaptions'] : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['togglecaptions']),
							"dependency"            	=> array( 'element' => "language_customize", 'value' => 'true' ),
							"group" 					=> "Language",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['togglefullscreen'],
							"param_name"            	=> "language_togglefullscreen",
							"value"                 	=> (isset($this->TS_VCSC_Plyr_Player_Language['togglefullscreen']) ? $this->TS_VCSC_Plyr_Player_Language['togglefullscreen'] : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['togglefullscreen']),
							"dependency"            	=> array( 'element' => "language_customize", 'value' => 'true' ),
							"group" 					=> "Language",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['frametitle'],
							"param_name"            	=> "language_frametitle",
							"value"                 	=> (isset($this->TS_VCSC_Plyr_Player_Language['frametitle']) ? $this->TS_VCSC_Plyr_Player_Language['frametitle'] : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Plyr_Player_Language_Defaults['frametitle']),
							"dependency"            	=> array( 'element' => "language_customize", 'value' => 'true' ),
							"group" 					=> "Language",
						),				
						// Tooltip Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_10",
							"seperator"            		=> "Tooltip Settings",
							"group" 					=> "Tooltip",
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Tooltip Addition", "ts_visual_composer_extend" ),
							"param_name"        		=> "tooltip_usage",
							"value"            	 		=> "false",
							"description"       		=> __( "Switch the toggle if you want to add an optional tooltip to the element.", "ts_visual_composer_extend" ),
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
							"dependency"        		=> array( 'element' => "tooltip_usage", 'value' => 'true' ),
							"group" 					=> "Tooltip",
						),
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Tooltip Position", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltip_position",
							"value"						=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Positions,
							"description"				=> __( "Select the tooltip position in relation to the element.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"        		=> array( 'element' => "tooltip_usage", 'value' => 'true' ),
							"group" 					=> "Tooltip",
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Tooltip Animation", "ts_visual_composer_extend" ),
							"param_name"		   	 	=> "tooltip_animation",
							"value"                 	=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Animations,
							"description"		    	=> __( "Select how the tooltip entry and exit should be animated once triggered.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"        		=> array( 'element' => "tooltip_usage", 'value' => 'true' ),
							"group"						=> "Tooltip",
						),	
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Tooltip Style", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltip_style",
							"value"             		=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Styles,
							"description"				=> __( "Select the tooltip style.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"        		=> array( 'element' => "tooltip_usage", 'value' => 'true' ),
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
							"dependency"        		=> array( 'element' => "tooltip_usage", 'value' => 'true' ),
							"group" 					=> "Tooltip",
						),
						array(
							"type"						=> "nouislider",
							"heading"					=> __( "Tooltip Y-Offset", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltip_offsety",
							"value"						=> "0",
							"min"						=> "-100",
							"max"						=> "100",
							"step"						=> "1",
							"unit"						=> 'px',
							"description"				=> __( "Define an optional Y-Offset for the tooltip position.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "tooltip_usage", 'value' => 'true' ),
							"group" 					=> "Tooltip",
						),
						// Other Settings
						array(
							"type"				    	=> "seperator",
							"param_name"		    	=> "seperator_11",
							"seperator"					=> "Other Settings",
							"group" 					=> "Other",
						),
						array(
							"type"                  	=> "nouislider",
							"heading"               	=> __( "Margin: Top", "ts_visual_composer_extend" ),
							"param_name"            	=> "margin_top",
							"value"                 	=> "0",
							"min"                   	=> "0",
							"max"                   	=> "200",
							"step"                  	=> "1",
							"unit"                  	=> 'px',
							"description"          	 	=> __( "Select the top margin for the element.", "ts_visual_composer_extend" ),
							"group" 					=> "Other",
						),
						array(
							"type"                  	=> "nouislider",
							"heading"               	=> __( "Margin: Bottom", "ts_visual_composer_extend" ),
							"param_name"            	=> "margin_bottom",
							"value"                 	=> "0",
							"min"                   	=> "0",
							"max"                   	=> "200",
							"step"                  	=> "1",
							"unit"                  	=> 'px',
							"description"           	=> __( "Select the bottom margin for the element.", "ts_visual_composer_extend" ),
							"group" 					=> "Other",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Define ID Name", "ts_visual_composer_extend" ),
							"param_name"            	=> "el_id",
							"value"                 	=> "",
							"description"           	=> __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
							"group" 					=> "Other",
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
	if ((class_exists('WPBakeryShortCode')) && (!class_exists('WPBakeryShortCode_TS_VCSC_Plyr_Player'))) {
		class WPBakeryShortCode_TS_VCSC_Plyr_Player extends WPBakeryShortCode {};
	}
	// Initialize "TS Plyr Video Player" Class
	if (class_exists('TS_Plyr_Video_Player')) {
		$TS_Plyr_Video_Player = new TS_Plyr_Video_Player;
	}
?>