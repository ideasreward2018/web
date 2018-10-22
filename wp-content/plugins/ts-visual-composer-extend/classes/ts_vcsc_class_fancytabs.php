<?php
	if (!class_exists('TS_Fancy_Tabs')){
		class TS_Fancy_Tabs {
			function __construct() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						$this->TS_VCSC_FancyTabs_Container_Lean();
					} else if (function_exists('vc_map')) {
						add_action('init',									array($this, 'TS_VCSC_Add_FancyTabs_Elements_Container'), 9999999);
						add_action('init',									array($this, 'TS_VCSC_Add_FancyTabs_Elements_Single'), 9999999);
					}
				} else {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						add_action('admin_init',							array($this, 'TS_VCSC_FancyTabs_Container_Lean'), 9999999);
					} else if (function_exists('vc_map')) {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_FancyTabs_Elements_Container'), 9999999);
						add_action('admin_init',							array($this, 'TS_VCSC_Add_FancyTabs_Elements_Single'), 9999999);
					}
				}
				if ((is_admin() == false) || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAJAX == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAlways == "true")) {
					add_shortcode('TS_VCSC_Fancy_Tabs_Container',			array($this, 'TS_VCSC_FancyTabs_Container'));
					add_shortcode('TS_VCSC_Fancy_Tabs_Single',				array($this, 'TS_VCSC_FancyTabs_Single'));
				}
			}
			
			// Register Element(s) via LeanMap
			function TS_VCSC_FancyTabs_Container_Lean() {
				vc_lean_map('TS_VCSC_Fancy_Tabs_Container', 				array($this, 'TS_VCSC_Add_FancyTabs_Elements_Container'), null);
				vc_lean_map('TS_VCSC_Fancy_Tabs_Single', 					array($this, 'TS_VCSC_Add_FancyTabs_Elements_Single'), null);
			}
			
			// Fancy Tabs Container
			function TS_VCSC_FancyTabs_Container ($atts, $content = null) {
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();
	
				wp_enqueue_style('ts-extend-fancytabs');
				wp_enqueue_script('ts-extend-fancytabs');
				wp_enqueue_style('dashicons');
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "false") {
					wp_enqueue_style('ts-extend-tooltipster');
					wp_enqueue_script('ts-extend-tooltipster');	
					wp_enqueue_style('ts-extend-animations');
				}
				wp_enqueue_style('ts-visual-composer-extend-front');
				wp_enqueue_script('ts-visual-composer-extend-front');
				
				$output = $title = $interval = $el_class = '';
				extract(shortcode_atts( array(
					// General Settings
					'tabs_preloader'				=> 0,
					'tabs_default'					=> 1,
					'tabs_effect'					=> 'scale',
					'tabs_theme'					=> 'default',					
					'tabs_align'					=> 'horizontal',
					'tabs_horizontal'				=> 'top',
					'tabs_vertical'					=> 'left',
					'tabs_centered'					=> 'false',
					'tabs_minwidth'					=> 200,
					'tabs_maxwidth'					=> 300,
					'tabs_resize'					=> 'false',
					'tabs_deeplinking'				=> 'none',
					'tabs_fullwidth'				=> 100,
					'tabs_zindex'					=> 99,
					// Accordion Settings
					'accordion_collapse'			=> 'false',
					'accordion_align'				=> 'horizontal',
					'accordion_horizontal'			=> 'top',
					'accordion_vertical'			=> 'left',
					'accordion_centered'			=> 'false',
					'accordion_minwidth'			=> 200,
					'accordion_maxwidth'			=> 300,					
					// Breakpoint Settings
					'tabs_breakvertical'			=> 820,
					'tabs_breakmedium'				=> 820,
					'tabs_breaksmall'				=> 480,
					'tabs_breakaccordion'			=> 640,
					// Style Settings
					'tabs_indicator'				=> 'icon',
					'tabs_rtl'						=> 'false',					
					'tabs_effect_shadow'			=> 'true',
					'tabs_effect_grow'				=> 'true',
					'tabs_effect_rounded'			=> 'frame',
					'tabs_effect_line'				=> 'false',
					'tabs_spacing'					=> 0,					
					'tabs_customize'				=> 'false',
					'tabs_background'				=> '#f7f7f7',
					'tabs_active_back'				=> '#f7f7f7',
					'tabs_active_text'				=> '#505050',
					'tabs_active_icon'				=> '#505050',
					// Auto Rotation
					'tabs_autorotate'				=> 'false',
					'tabs_delay'					=> 5000,
					'tabs_hoverpause'				=> 'true',
					'tabs_playpause'				=> 'true',
					'tabs_navigation'				=> 'true',
					// Custom Colors
					'tabs_controlscolor'			=> '#9bd7d5',
					'tabs_controlshover'			=> '#70c5c2',
					'tabs_rotatemobile'				=> 'false',
					'tabs_progressbar'				=> 'true',
					'tabs_progresscolor'			=> '#70c5c2',
					'tabs_linecolor'				=> '#9bd7d5',
					'tabs_mobilebackground'			=> '#9bd7d5',
					'tabs_mobilecolor'				=> '#ffffff',
					'tabs_mobilehoverback'			=> '#70c5c2',
					'tabs_mobilehovercolor'			=> '#ffffff',
					// Font Settings
					'tabs_fonttype'					=> 'Default:regular',
					'tabs_fontmatch'				=> 'default',
					'tabs_fontsize'					=> 14,
					'tabs_iconsize'					=> 18,
					// WPAutoP Callback
					'content_wpautop'				=> 'false',
					// Tooltip Settings
					'tooltipster_allow'				=> 'true',
					'tooltipster_animation'			=> 'fade',
					'tooltipster_theme'				=> 'tooltipster-black',
					'tooltipster_offsetx'			=> 0,
					'tooltipster_offsety'			=> 0,
					// Other Settings
					'margin_top'					=> 0,
					'margin_bottom'					=> 0,
					'tab_contid'					=> '',
					'el_class'						=> '',
					'css'							=> '',
				), $atts ) );
				
				$output = $styles = '';
				$wpautop 							= ($content_wpautop == "true" ? true : false);
				$inline								= TS_VCSC_FrontendAppendCustomRules('style');
				
				// Check for Front End Editor
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					$icontabs_frontend				= "true";
				} else {
					$icontabs_frontend				= "false";
				}
				
				// Responsive Behavior Adjustments
				if ($accordion_collapse == "true") {
					$tabs_align						= $accordion_align;
					$tabs_horizontal				= $accordion_horizontal;
					$tabs_vertical					= $accordion_vertical;
					$tabs_centered					= $accordion_centered;
					$tabs_minwidth					= $accordion_minwidth;
					$tabs_maxwidth					= $accordion_maxwidth;
				}

				// Extract Tab Titles from $content
				preg_match_all('/TS_VCSC_Fancy_Tabs_Single([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE);
				$tab_data 							= array();
				if (isset($matches[1])) {
					$tab_data 						= $matches[1];
				}				
				
				$el_class 							= str_replace(".", "", $el_class);				
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Fancy_Tabs_Container', $atts);
				} else {
					$css_class						= $el_class;
				}
				
				if ($icontabs_frontend == "false") {				
					// Create Tabs Data
					$tabs_rotate					= 'data-rotate="' . $tabs_autorotate . '" data-delay="' . $tabs_delay . '" data-hoverpause="' . $tabs_hoverpause . '" data-playpause="' . $tabs_playpause . '" data-navigation="' . $tabs_navigation . '" data-rotatemobile="' . $tabs_rotatemobile . '" data-progressbar="' . $tabs_progressbar . '" data-progresscolor="' . $tabs_progresscolor . '"';
					$tabs_styling					= 'data-indicator="' . $tabs_indicator . '" data-shadow="' . $tabs_effect_shadow . '" data-grow="' . $tabs_effect_grow . '" data-rounded="' . $tabs_effect_rounded . '" data-rtl="' . $tabs_rtl . '" data-spacing="' . $tabs_spacing . '"';
					$tabs_data						= 'data-identifier="' . $tab_contid . '" data-default="' . $tabs_default . '" data-preloader="' . ($tabs_preloader != -1 ? "ts-fancy-tabs-loader-" . $tab_contid : "") . '" data-effect="' . $tabs_effect . '" data-theme="' . $tabs_theme . '" data-deeplink="' . $tabs_deeplinking . '" data-accordion="' . $accordion_collapse . '" data-align="' . $tabs_align . '" data-horizontal="' . $tabs_horizontal . '" data-vertical="' . $tabs_vertical . '" data-centered="' . $tabs_centered . '" data-minwidth="' . $tabs_minwidth . '" data-maxwidth="' . $tabs_maxwidth . '" data-breakvertical="' . $tabs_breakvertical . '" data-breakmedium="' . $tabs_breakmedium . '" data-breaksmall="' . $tabs_breaksmall . '" data-breakaccordion="' . $tabs_breakaccordion . '" data-resize="' . $tabs_resize . '" data-background="' . $tabs_background . '"';
					$tabs_tooltip					= 'data-tooltips-allow="' . $tooltipster_allow . '" data-tooltips-theme="' . $tooltipster_theme . '" data-tooltips-animation="' . $tooltipster_animation . '" data-tooltips-offsetx="' . $tooltipster_offsetx . '" data-tooltips-offsety="' . $tooltipster_offsety . '"';
					
					// Font Styling
					if (strpos($tabs_fonttype, 'Default') === false) {
						$font_default				= TS_VCSC_GetFontFamily($tab_contid, $tabs_fonttype, $tabs_fontmatch, false, true, false);
					} else {
						$font_default				= '';
					}
					
					// Create Tabs Custom CSS
					if ($inline == "false") {
						$styles .= '<style id="ts-fancy-tabs-main-styles-' . $tab_contid . '" type="text/css">';
					}
						$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .ts-fancy-tabs-hidden {';
							$styles .= '-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";';
							$styles .= 'filter: alpha(opacity=0);';
							$styles .= '-moz-opacity: 0;';
							$styles .= '-khtml-opacity: 0;';
							$styles .= 'opacity: 0;';
						$styles .= '}';
						$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .ts-fancy-tabs-container .pws_tab_single.ts-fancy-tabs-hidden {';
							$styles .= 'position: absolute;';
							$styles .= 'top: 0;';
							$styles .= 'left: -9999px;';
						$styles .= '}';
						$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container ul.pws_tabs_controlls li a,';
						$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container .pws_tabs_accordion_item a {';
							$styles .= 'height: ' . max($tabs_fontsize, $tabs_iconsize) . 'px;';
							$styles .= 'line-height: ' . max($tabs_fontsize, $tabs_iconsize) . 'px;';
						$styles .= '}';
						$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container ul.pws_tabs_controlls li a .pws_tab_text,';
						$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container.pws_tabs_responsive_small .pws_responsive_small_menu .pws_responsive_small_name .pws_tab_text {';
							if ($font_default != "") {
								$styles .= $font_default;
							}
							$styles .= 'font-size: ' . $tabs_fontsize . 'px;';
							$styles .= 'line-height: ' . max($tabs_fontsize, $tabs_iconsize) . 'px;';
						$styles .= '}';
						$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container ul.pws_tabs_controlls li a i,';
						$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container .pws_tabs_accordion_item a i {';
							$styles .= 'font-size: ' . $tabs_iconsize . 'px;';
							$styles .= 'height: ' . $tabs_iconsize . 'px;';
							$styles .= 'width: auto;';
							$styles .= 'line-height: ' . max($tabs_fontsize, $tabs_iconsize) . 'px;';
						$styles .= '}';
						$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container.pws_tabs_responsive.pws_tabs_responsive_small ul.pws_tabs_controlls.pws_tabs_menu_popup li a i,';
						$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container.pws_tabs_responsive.pws_tabs_responsive_small ul.pws_tabs_controlls.pws_tabs_menu_popup li a .pws_tab_text {';
							$styles .= 'font-size: ' . min($tabs_fontsize, $tabs_iconsize) . 'px;';
							$styles .= 'line-height: ' . min($tabs_fontsize, $tabs_iconsize) . 'px;';
						$styles .= '}';
						$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container.pws_tabs_responsive.pws_tabs_responsive_small ul.pws_tabs_controlls.pws_tabs_menu_popup li a {';
							$styles .= 'height: ' . min($tabs_fontsize, $tabs_iconsize) . 'px;';
							$styles .= 'line-height: ' . min($tabs_fontsize, $tabs_iconsize) . 'px;';
						$styles .= '}';
						$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container.pws_tabs_responsive.pws_tabs_responsive_small ul.pws_tabs_controlls.pws_tabs_menu_popup li a i {';
							$styles .= 'height: ' . min($tabs_fontsize, $tabs_iconsize) . 'px;';
							$styles .= 'width: ' . min($tabs_fontsize, $tabs_iconsize) . 'px;';
						$styles .= '}';
						foreach ($tab_data as $tab) {
							$tab_atts 				= shortcode_parse_atts($tab[0]);
							if ((isset($tab_atts['customize'])) && ($tab_atts['customize'] == 'true')) {
								$tab_id				= (isset($tab_atts['tab_id']) ? $tab_atts['tab_id'] : sanitize_title($tab_atts['title']));
								$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_controlls a#trigger-tab-' . $tab_id . ',';
								$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_accordion_item a#trigger-accordion-' . $tab_id . ' {';
									$styles .= 'background: ' . (isset($tab_atts['color_tab']) ? $tab_atts['color_tab'] : '#9bd7d5') . ';';
									$styles .= 'color: ' . (isset($tab_atts['color_text']) ? $tab_atts['color_text'] : '#ffffff') . ';';
								$styles .= '}';
								$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_controlls a#trigger-tab-' . $tab_id . ' i,';
								$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_accordion_item a#trigger-accordion-' . $tab_id . ' i {';
									$styles .= 'color: ' . (isset($tab_atts['color_icon']) ? $tab_atts['color_icon'] : '#ffffff') . ';';
								$styles .= '}';
								$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_controlls a#trigger-tab-' . $tab_id . ':hover,';
								$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_accordion_item a#trigger-accordion-' . $tab_id . ':hover {';
									$styles .= 'background: ' . (isset($tab_atts['hover_tab']) ? $tab_atts['hover_tab'] : '#70c5c2') . ';';
									$styles .= 'color: ' . (isset($tab_atts['hover_text']) ? $tab_atts['hover_text'] : '#ffffff') . ';';
								$styles .= '}';
								$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_controlls a#trigger-tab-' . $tab_id . ':hover i,';
								$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_accordion_item a#trigger-accordion-' . $tab_id . ':hover i {';
									$styles .= 'color: ' . (isset($tab_atts['hover_icon']) ? $tab_atts['hover_icon'] : '#ffffff') . ';';
								$styles .= '}';
							}
						}
						if ($tabs_customize == 'true') {
							$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container ul.pws_tabs_controlls li a.pws_tab_active,';
							$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container .pws_tabs_accordion_item a.pws_tab_active {';
								$styles .= 'background: ' . $tabs_active_back . ' !important;';
								$styles .= 'color: ' . $tabs_active_text . ' !important;';
							$styles .= '}';
							$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container ul.pws_tabs_controlls li a.pws_tab_active i,';
							$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container .pws_tabs_accordion_item a.pws_tab_active i {';
								$styles .= 'color: ' . $tabs_active_icon . ' !important;';
							$styles .= '}';
							$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .ts-fancy-tabs-container .pws_tabs_list,';
							$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .ts-fancy-tabs-container.pws_tabs_responsive_accordion .pws_tabs_list .pws_tab_single {';
								$styles .= 'background: ' . $tabs_background . ';';
							$styles .= '}';
							// Mobile Menu Styling
							$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_responsive_small_menu {';
								$styles .= 'background: ' . $tabs_mobilebackground . ';';
								$styles .= 'color: ' . $tabs_mobilecolor . ';';
							$styles .= '}';
							$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_responsive_small_menu a:hover {';
								$styles .= 'background: ' . $tabs_mobilehoverback . ';';
								$styles .= 'color: ' . $tabs_mobilehovercolor . ';';
							$styles .= '}';
							// Autoplay Styling
							if (($tabs_autorotate == 'true') && ($tabs_progressbar == 'true')) {
								$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container.pws_autorotate_controls .pws_tabs_list .pws_autorotate_progress {';
									$styles .= 'background: ' . $tabs_progresscolor . ' !important;';
								$styles .= '}';								
								$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container.pws_autorotate_controls .pws_autorotate_buttons a i {';
									$styles .= 'color: ' . $tabs_controlscolor . ' !important;';
								$styles .= '}';
								$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container.pws_autorotate_controls .pws_autorotate_buttons a:hover i {';
									$styles .= 'color: ' . $tabs_controlshover . ' !important;';
								$styles .= '}';
							}
							// Separator Styling
							if ($tabs_effect_line == "true") {
								$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container.pws_tabs_horizontal.pws_tabs_horizontal_top:not(.pws_tabs_responsive_accordion) .pws_tabs_list.ts-fancy-tabs-separator {';
									$styles .= 'border-top: 1px solid #9bd7d5;';
								$styles .= '}';
								$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container.pws_tabs_horizontal.pws_tabs_horizontal_bottom:not(.pws_tabs_responsive_accordion) .pws_tabs_list.ts-fancy-tabs-separator {';
									$styles .= 'border-bottom: 1px solid #9bd7d5;';
								$styles .= '}';
								$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container.pws_tabs_vertical.pws_tabs_vertical_left:not(.pws_tabs_responsive_accordion) .pws_tabs_list.ts-fancy-tabs-separator {';
									$styles .= 'border-left: 1px solid #9bd7d5;';
								$styles .= '}';
								$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container.pws_tabs_vertical.pws_tabs_vertical_right:not(.pws_tabs_responsive_accordion) .pws_tabs_list.ts-fancy-tabs-separator {';
									$styles .= 'border-right: 1px solid #9bd7d5;';
								$styles .= '}';
							}
						} else {
							$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container ul.pws_tabs_controlls li a.pws_tab_active,';
							$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container .pws_tabs_accordion_item a.pws_tab_active {';
								$styles .= 'background: ' . $tabs_active_back . ' !important;';
								$styles .= 'color: ' . $tabs_active_text . ' !important;';
							$styles .= '}';
							$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container ul.pws_tabs_controlls li a.pws_tab_active i,';
							$styles .= '#ts-fancy-tabs-main-wrapper-' . $tab_contid . '.ts-fancy-tabs-main-wrapper .pws_tabs_container .pws_tabs_accordion_item a.pws_tab_active i {';
								$styles .= 'color: ' . $tabs_active_icon . ' !important;';
							$styles .= '}';
						}
					if ($inline == "false") {
						$styles .= '</style>';
					}
					if (($styles != "") && ($inline == "true")) {
						wp_add_inline_style('ts-visual-composer-extend-custom', TS_VCSC_MinifyCSS($styles));
					}
					
					// Create Main Tabs Navigation
					$navigation 					= '';
					$navigation .= '<ul class="pws_tabs_controlls ts-fancy-tabs-hidden ' . ($tabs_centered == "true" ? "ts-fancy-tabs-centered" : "") . '" style="display: none;">';
						$tab_count					= 0;
						$tab_zindex					= $tabs_zindex;						
						if ($tabs_align == "horizontal") {
							if ($tabs_rtl == "true") {
								$tab_margin			= 'margin-left: ' . $tabs_spacing . 'px;';
							} else {
								$tab_margin			= 'margin-right: ' . $tabs_spacing . 'px;';
							}
						} else {
							$tab_margin				= 'margin-bottom: ' . $tabs_spacing . 'px;';
						}
						foreach ($tab_data as $tab) {
							$tab_atts 				= shortcode_parse_atts($tab[0]);
							$tab_count++;
							if ($tabs_indicator == 'number') {
								$tab_indicator		= $tab_count;
							} else if ($tabs_indicator == 'roman') {
								$tab_indicator		= TS_VCSC_ConverToRoman($tab_count);
							} else if ($tabs_indicator == 'letter') {
								$tab_indicator		= TS_VCSC_ConvertToAlpha($tab_count - 1);
							} else {
								$tab_indicator		= $tab_count;
							}							
							if ($tabs_effect_rounded == 'all') {
								$tab_class			= "pws_tabs_rounded_all";
							} else if ($tabs_effect_rounded == 'frame') {
								$tab_class			= "pws_tabs_rounded_frame";
							} else {
								$tab_class			= "";
							}
							if ($tabs_effect_shadow == "true") {
								$tab_class			.= ' pws_tabs_shadowed';
							}
							if ($tabs_effect_grow == "true") {
								$tab_class 			.= ' pws_tabs_growing';
							}
							if ((isset($tab_atts['icon'])) && ($tab_atts['icon'] != '')) {
								if ((isset($tab_atts['animation_class'])) && ($tab_atts['animation_class'] != '')) {
									$tab_icon		= 'pws_tab_icon ' . $tab_atts['icon'] . ' ts-' . (isset($tab_atts['animation_type']) ? $tab_atts['animation_type'] : 'hover') . '-css-' . $tab_atts['animation_class'];
									$tab_class		.= ' ts-fancy-tab-icon-' . (isset($tab_atts['animation_type']) ? $tab_atts['animation_type'] : 'hover');
								} else {
									$tab_icon		= 'pws_tab_icon ' . $tab_atts['icon'];
								}
							} else {
								$tab_icon			= 'pws_tab_icon pws_tab_noicon dashicons dashicons-index-card';
							}
							$navigation .= '<li class="pws_tabs_controlls_item" data-tooltipset="false" data-tab-tooltip="' . (isset($tab_atts['title']) ? $tab_atts['title'] : '') . '">';
								$navigation .= '<a id="trigger-tab-' . $tab_atts['tab_id'] . '" class="pws_tabs_controlls_link ' . $tab_class . '" href="#tab-' . $tab_atts['tab_id'] . '" data-tab-id="tab-' . $tab_atts['tab_id'] . '" title="" data-tab-counter="' . $tab_count . '" style="z-index: ' . ($tab_zindex--) . '; ' . $tab_margin . '">';
									if ((isset($tab_atts['icon'])) && ($tab_atts['icon'] != '')) {
										$navigation .= '<i class="' . $tab_icon . '" style="display: inline-block;"></i>';
									} else {
										$navigation .= '<i class="' . $tab_icon . '" style="display: none;"></i>';
									}
									$navigation .= '<span class="pws_tab_text">' . (isset($tab_atts['title']) ? $tab_atts['title'] : '') . '</span>';
									$navigation .= '<span class="pws_tab_counter" style="display: none;">' . $tab_indicator . '</span>';
								$navigation .= '</a>';
							$navigation .= '</li>';
						}
					$navigation .= '</ul>';
					
					// Create Accordion Navigation
					$accordion 						= '';
					if ($accordion_collapse == "true") {
						$accordion .= '<div class="pws_tabs_accordion ts-fancy-tabs-hidden clearFixMe" style="display: none;">';
							$tab_count				= 0;
							$tab_zindex				= $tabs_zindex;
							$tab_margin				= 'margin-bottom: ' . $tabs_spacing . 'px;';
							foreach ($tab_data as $tab) {
								$tab_atts 			= shortcode_parse_atts($tab[0]);
								$tab_count++;
								if ($tabs_indicator == 'number') {
									$tab_indicator	= $tab_count;
								} else if ($tabs_indicator == 'roman') {
									$tab_indicator	= TS_VCSC_ConverToRoman($tab_count);
								} else if ($tabs_indicator == 'letter') {
									$tab_indicator	= TS_VCSC_ConvertToAlpha($tab_count - 1);
								} else {
									$tab_indicator	= $tab_count;
								}
								if ($tabs_effect_rounded == 'all') {
									$tab_class		= "pws_tabs_rounded_all";
								} else if ($tabs_effect_rounded == 'frame') {
									$tab_class		= "pws_tabs_rounded_frame";
								} else {
									$tab_class		= "";
								}
								if ($tabs_effect_shadow == "true") {
									$tab_class		.= ' pws_tabs_shadowed';
								}
								if ($tabs_effect_grow == "true") {
									$tab_class 		.= ' pws_tabs_growing';
								}
								if ((isset($tab_atts['icon'])) && ($tab_atts['icon'] != '')) {
									if ((isset($tab_atts['animation_class'])) && ($tab_atts['animation_class'] != '')) {
										$tab_icon	= 'pws_tab_icon ' . $tab_atts['icon'] . ' ts-' . (isset($tab_atts['animation_type']) ? $tab_atts['animation_type'] : 'hover') . '-css-' . $tab_atts['animation_class'];
										$tab_class	.= ' ts-fancy-tab-icon-' . (isset($tab_atts['animation_type']) ? $tab_atts['animation_type'] : 'hover');
									} else {
										$tab_icon	= 'pws_tab_icon ' . $tab_atts['icon'];
									}
								} else {
									$tab_icon		= 'pws_tab_icon pws_tab_noicon dashicons dashicons-index-card';
								}
								$accordion .= '<nav class="pws_tabs_accordion_item" data-tab-trigger="trigger-tab-' . $tab_atts['tab_id'] . '" data-tab-target="tab-' . $tab_atts['tab_id'] . '" data-tooltipset="false" data-tab-tooltip="' . (isset($tab_atts['title']) ? $tab_atts['title'] : '') . '" style="display: none;">';
									$accordion .= '<a id="trigger-accordion-' . $tab_atts['tab_id'] . '" class="pws_tabs_accordion_link ' . $tab_class . '" href="#tab-' . $tab_atts['tab_id'] . '" data-tab-id="tab-' . $tab_atts['tab_id'] . '" title="" data-tab-counter="' . $tab_count . '" style="z-index: ' . ($tab_zindex--) . '; ' . $tab_margin . '">';
										if ((isset($tab_atts['icon'])) && ($tab_atts['icon'] != '')) {
											$accordion .= '<i class="' . $tab_icon . '" style="display: inline-block;"></i>';
										} else {
											$accordion .= '<i class="' . $tab_icon . '" style="display: none;"></i>';
										}
										$accordion .= '<span class="pws_tab_text">' . (isset($tab_atts['title']) ? $tab_atts['title'] : '') . '</span>';
										$accordion .= '<span class="pws_tab_counter" style="display: none;">' . $tab_indicator . '</span>';
									$accordion .= '</a>';
								$accordion .= '</nav>';
							}
						$accordion .= '</div>';
					}
					
					// Check for Progressbar
					if ((($tabs_autorotate == "true") && (($tabs_playpause == "true") || ($tabs_navigation == "true"))) && ($tabs_progressbar == "true")) {
						$progress_class				= 'ts-fancy-tabs-progressbar';
					} else {
						$progress_class				= '';
					}
					
					// Create Mobile Tabs Navigation
					$hamburger						= '';
					$hamburger .= '<div class="pws_responsive_small_menu" style="display: none;">';
						$hamburger .= '<a href="#" data-visible="0"><i class="dashicons dashicons-editor-justify"></i></a>';
						$hamburger .= '<div class="pws_responsive_small_name">';
							$tab_count			= 0;
							foreach ($tab_data as $tab) {
								$tab_atts 		= shortcode_parse_atts($tab[0]);
								$tab_count++;
								if ($tabs_default == $tab_count) {
									if ((isset($tab_atts['icon'])) && ($tab_atts['icon'] != '')) {
										$hamburger .= '<i class="pws_tab_icon ' . $tab_atts['icon'] . '" style="display: inline-block;"></i>';
									} else {
										$hamburger .= '<i class="pws_tab_icon pws_tab_noicon dashicons dashicons-index-card" style="display: none;"></i>';
									}
									$hamburger .= '<span class="pws_tab_text">' . (isset($tab_atts['title']) ? $tab_atts['title'] : '') . '</span>';
									$hamburger .= '<span class="pws_tab_counter" style="display: none;">' . $tab_count . '</span>';
								}
							}
						$hamburger .= '</div>';
					$hamburger .= '</div>';

					// Other Relevant Classes
					if ($tabs_align == 'vertical') {                
						if ($tabs_vertical == 'left') {
							$class_align 			= 'pws_tabs_vertical pws_tabs_vertical_left';
						} else {
							$class_align			= 'pws_tabs_vertical pws_tabs_vertical_right';
						}
					} else {                
						if ($tabs_horizontal == 'top'){
							$class_align			= 'pws_tabs_horizontal pws_tabs_horizontal_top';
						} else {
							$class_align			= 'pws_tabs_horizontal pws_tabs_horizontal_bottom';
						}
					}
					if ($tabs_rtl == "true") {
						$class_rtl					= 'pws_tabs_rtl';
					} else {
						$class_rtl					= '';
					}
					if ($tabs_effect == "none") {
						$class_effect				= 'pws_tabs_noeffect';
					} else {
						$class_effect				= '';
					}
					if ($tabs_effect_shadow == "true") {
						$class_shadow				= 'pws_tabs_shadow';
					} else {
						$class_shadow				= '';
					}
					if ($tabs_effect_grow == "true") {
						$class_growing				= 'pws_tabs_growing';
					} else {
						$class_growing				= '';
					}
					if ($tabs_theme != "") {
						$class_theme				= $tabs_theme;
					} else {
						$class_theme				= '';
					}
					if (($tabs_autorotate == "true") && (($tabs_playpause == "true") || ($tabs_navigation == "true"))) {
						$class_rotate				= 'pws_autorotate_controls';
					} else {
						$class_rotate				= '';
					}

					// Create Final Tabs Output
					$output .= '<div id="ts-fancy-tabs-main-wrapper-' . $tab_contid . '" class="ts-fancy-tabs-main-wrapper ' . $el_class . ' ' . $css_class . '" ' . $tabs_data . ' ' . $tabs_tooltip . ' ' . $tabs_rotate . ' ' . $tabs_styling . ' style="' . ($icontabs_frontend == "true" ? "margin-top: 40px; margin-bottom: 40px;" : "") . '">';
						// Custom Style Rules
						if (($styles != "") && ($inline == "false")) {
							$output .= TS_VCSC_MinifyCSS($styles);
						}
						$output .= '<div id="ts-fancy-tabs-container-' . $tab_contid . '" class="ts-fancy-tabs-container pws_tabs_container pws_tabs_responsive_large ' . $class_align . ' ' . $class_rtl . ' ' . $class_shadow . ' ' . $class_growing . ' ' . $class_effect . ' ' . $class_theme . ' ' . $class_rotate . '" data-align="' . $tabs_align . '" data-position="' . ($tabs_align == "vertical" ? $tabs_vertical : $tabs_horizontal) . '" style="width: ' . $tabs_fullwidth . '%;">';
							// Auto-Rotate Controls
							if (($tabs_autorotate == "true") && (($tabs_playpause == "true") || ($tabs_navigation == "true"))) {
								$output .= '<div class="pws_autorotate_buttons ts-fancy-tabs-hidden clearFixMe">';
									if ($tabs_navigation == "true") {
										$output .= '<a class="pws_autorotate_buttons_next" href="#"><i class="dashicons dashicons-arrow-right-alt2"></i></a>';
									}
									if ($tabs_playpause == "true") {
										$output .= '<a class="pws_autorotate_buttons_play" href="#" style="display: none;"><i class="dashicons dashicons-controls-play"></i></a>';
										$output .= '<a class="pws_autorotate_buttons_pause" href="#"><i class="dashicons dashicons-controls-pause"></i></a>';
									}
									if ($tabs_navigation == "true") {
										$output .= '<a class="pws_autorotate_buttons_prev" href="#"><i class="dashicons dashicons-arrow-left-alt2"></i></a>';
									}
								$output .= '</div>';
							}
							// Main Tabs Navigation A
							if ((($tabs_align == 'vertical') && ($tabs_vertical == 'left')) || (($tabs_align == 'horizontal') && ($tabs_horizontal == 'top'))) {
								$output .= $navigation;
							}
							// Accordion Navigation
							$output .= $accordion;
							// Mobile Tabs Navigation
							$output .= $hamburger;
							// Main Tabs Content
							$output .= '<div class="ts-fancy-tabs-content ts-fancy-tabs-loader ' . $progress_class . ' pws_tabs_list ' . ($tabs_effect_line == "true" ? "ts-fancy-tabs-separator" : "") . '">';
								// Auto-Rotate Progressbar
								if (($tabs_autorotate == "true") && (($tabs_playpause == "true") || ($tabs_navigation == "true"))) {
									if ($tabs_progressbar == "true") {
										$output .= '<div class="pws_autorotate_progress ts-fancy-tabs-hidden"></div>';
									} 
								}
								// Preloader Animation
								$output .= TS_VCSC_CreatePreloaderCSS("ts-fancy-tabs-loader-" . $tab_contid, "", $tabs_preloader, "true");
								// Individual Tabs Content
								if (function_exists('wpb_js_remove_wpautop')){
									$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
								} else {
									$output .= do_shortcode($content);
								}
							$output .= '</div>';
							// Main Tabs Navigation B
							if ((($tabs_align == 'vertical') && ($tabs_vertical == 'right')) || (($tabs_align == 'horizontal') && ($tabs_horizontal == 'bottom'))) {
								$output .= $navigation;
							}
						$output .= '</div>';		
					$output .= '</div>';				
				} else {
					$tabs_nav = '';
					$tabs_nav .= '<ul id="ts-frontend-editor-tab-links-' . $tab_contid . '" class="ts-frontend-editor-tab-links">';
						$counter = 0;
						foreach ($tab_data as $tab) {
							$tab_atts = shortcode_parse_atts($tab[0]);
							if (empty($tab_atts['icon'])){
								$tab_atts['icon'] = '';
							}
							if (!empty($tab_atts['color_tab'])){
								$style = 'background:' . $tab_atts['color_tab'];
							} else {
								$style = '';
							}
							if (isset($tab_atts['title'])) {
								$tabs_nav .= '<li id="ts-frontend-editor-tab-trigger' . $counter . '" class="" data-order="' . $counter . '" data-frontend="' . $icontabs_frontend . '" data-container="tab-' . $tab_contid . '" data-target="tab-' . (isset($tab_atts['tab_id']) ? $tab_atts['tab_id'] : sanitize_title($tab_atts['title'])) . '" >';
									$tabs_nav .= '<a href="#ts-frontend-editor-' . (isset($tab_atts['tab_id']) ? $tab_atts['tab_id'] : sanitize_title($tab_atts['title'])) . '" data-frontend="' . $icontabs_frontend . '">';
										if ((isset($tab_atts['icon'])) && ($tab_atts['icon'] != '') && ($tab_atts['icon'] != 'undefined')) {
											$tabs_nav .= '<i class="icon ' . $tab_atts['icon'] . '"></i>';
										}
										$tabs_nav .= '<span>' . $tab_atts['title'] . '</span>';
									$tabs_nav .= '</a>';
								$tabs_nav .= '</li>';
							}
							$counter++;
						}
					$tabs_nav .= '</ul>';
					$output .= '<div id="ts-frontend-editor-tabs-wrapper-' . $tab_contid . '" class="ts-frontend-editor-tabs-wrapper" data-frontend="' . $icontabs_frontend . '" style="' . ($icontabs_frontend == "true" ? "margin-top: 40px; margin-bottom: 40px;" : "") . '">';
						// Message
						$output .= '<div class="" style="font-weight: bold; margin: 20px auto; border: 1px solid #ededed; padding: 5px; text-align: justify; color: red;">';
							$output .= 'This tabs element is still in BETA and can ONLY be correctly added to a page or edited as existing element with the standard backend editor. For compatibility reasons with the frontend
							editor, existing tabs are shown in an alternative layout with a reduced capacity and functionality. We aplogoize for the inconvenience.';
						$output .= '</div>';
						// Content
						$output .= '<div class="ts-frontend-editor-tabs" data-frontend="' . $icontabs_frontend . '">';
							$output .= $tabs_nav;
							$output .= '<div class="ts-frontend-editor-tab-content">';
								if (function_exists('wpb_js_remove_wpautop')){
									$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
								} else {
									$output .= do_shortcode($content);
								}
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';
				}
				
				unset ($navigation);
				unset ($hamburger);
				
				echo $output;
				
				$myvariable = ob_get_clean();
				return $myvariable;
			}    
			// Single Fancy Tab
			function TS_VCSC_FancyTabs_Single ($atts, $content = null) {
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();
				
				extract( shortcode_atts( array(
					// General Settings
					'title'							=> '',
					'icon'							=> '',
					'animation_type'				=> 'hover',
					'animation_class'				=> '',				
					'customize'						=> 'false',
					'color_tab'						=> '#9bd7d5',
					'hover_tab'						=> '#70c5c2',
					'color_text'					=> '#ffffff',
					'hover_text'					=> '#ffffff',
					'color_icon'					=> '#ffffff',
					'hover_icon'					=> '#ffffff',
					'color_back'					=> '#f7f7f7',
					// WPAutoP Callback
					'content_wpautop'				=> 'false',
					// Other Settings
					'padding'						=> 'padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;',
					'watch'							=> 'true',
					'resize'						=> 'false',
					'tab_id' 						=> '',
					'el_class'						=> '',
					'css'							=> '',
				), $atts ) );
				
				// Check for Front End Editor
				if (function_exists('vc_is_inline')){
					if (vc_is_inline()) {
						$icontabs_frontend			= "true";
					} else {
						$icontabs_frontend			= "false";
					}
				} else {
					$icontabs_frontend				= "false";
				}
				
				$output 							= '';
				$wpautop 							= ($content_wpautop == "true" ? true : false);
				
				$el_class 							= str_replace(".", "", $el_class);				
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Fancy_Tabs_Single', $atts);
				} else {
					$css_class						= $el_class;
				}
				
				if (!empty($tab_id)){
					if ($icontabs_frontend == "false") {
						if ($animation_class != '') {
							$icon_animation 		= 'ts-' . $animation_type . '-css-' . $animation_class . '';
						} else {
							$icon_animation 		= '';
						}
						// Tab Content
						$output .= '<div class="pws_tab_single ' . $css_class . ' ts-fancy-tabs-hidden" style="' . $padding . '" data-pws-tab="tab-' . $tab_id . '" data-pws-tab-name="' . $title . '" data-pws-tab-watch="' . $watch . '" data-pws-tab-resize="' . $resize . '" data-pws-tab-icon="' . $icon . '" data-pws-tab-trigger="' . $animation_type . '" data-pws-tab-animation="' . $icon_animation . '">';
							if (function_exists('wpb_js_remove_wpautop')){
								$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
							} else {
								$output .= do_shortcode($content);
							}
						$output .= '</div>';
					} else {
						$output .= '<div id="ts-frontend-editor-' . $tab_id . '" class="ts-frontend-editor-tab-single clearFixMe" style="padding-top: 10px;">';
							if (function_exists('wpb_js_remove_wpautop')){
								$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
							} else {
								$output .= do_shortcode($content);
							}
						$output .= '</div>';
					}
				}			
				echo $output;
				
				$myvariable = ob_get_clean();
				return $myvariable;
			}
			
			// Add Fancy Tabs Elements
			function TS_VCSC_Add_FancyTabs_Elements_Container() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Tabs Container
				$tab_id_1 = time() . '-1-' . rand( 0, 100 );
				$tab_id_2 = time() . '-2-' . rand( 0, 100 );
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					'name'    							=> __('TS Fancy Tabs (BETA)', 'ts_visual_composer_extend') ,		
					'base'    							=> 'TS_VCSC_Fancy_Tabs_Container',
					'icon' 								=> "ts-composer-element-icon-fancy-tabs",
					"category" 							=> __("VC Extensions","ts_visual_composer_extend"),		
					"description" 						=> __("Create fancy looking tabs with icons.","ts_visual_composer_extend"),
					"controls"							=> "full",
					'show_settings_on_create' 			=> true,
					'is_container' 						=> true,
					//"html_template"           		=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginPath . '/templates/TS_VCSC_Fancy_Tabs_Container.php',
					//"front_enqueue_js"  				=> preg_replace( '/\s/', '%20', plugins_url( 'js/frontend/ts-vcsc-frontend-fancytabs-container.min.js', __FILE__ ) ),
					'as_parent' 						=> array('except' => 'TS_VCSC_Fancy_Tabs_Container'),
					"as_child"							=> array('except' => 'vc_section'),
					'js_view'                 			=> 'TS_VCSC_IconTabsContainerViewCustom',
					'custom_markup' 					=> '<div class="wpb_tabs_holder wpb_holder vc_container_for_children"><ul class="tabs_controls"></ul>%content%</div>'	,
					'default_content' 					=> '
						[TS_VCSC_Fancy_Tabs_Single title="' . __('Tab 1', 'ts_visual_composer_extend' ) . '" tab_id="' . $tab_id_1 . '][/TS_VCSC_Fancy_Tabs_Single]
						[TS_VCSC_Fancy_Tabs_Single title="' . __('Tab 2', 'ts_visual_composer_extend' ) . '" tab_id="' . $tab_id_2 . '][/TS_VCSC_Fancy_Tabs_Single]
					',
					'params'                  			=> array(
						// General Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_1",
							"seperator"					=> "General Settings",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Tabs Behavior", "ts_visual_composer_extend" ),
							"param_name"		    	=> "accordion_collapse",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want to use only a basic responsive behavior where the tabs simply collapse to an accordion layout at a predetermined breakpoint.", "ts_visual_composer_extend" ),
						),
						array(
							'type' 						=> 'dropdown',
							'heading' 					=> __( 'Tabs Alignment', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tabs_align',
							'value' => array(
								__('Horizontal', 'ts_visual_composer_extend') 		=> 'horizontal',
								__('Vertical', 'ts_visual_composer_extend')			=> 'vertical',
							),
							'description' 				=> __( 'Select how the tab area should be aligned.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "accordion_collapse", 'value' => 'false' ),
						),
						array(
							'type' 						=> 'dropdown',
							'heading' 					=> __( 'Tabs Alignment', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'accordion_align',
							'value' => array(
								__('Horizontal', 'ts_visual_composer_extend') 		=> 'horizontal',
								__('Vertical', 'ts_visual_composer_extend')			=> 'vertical',
							),
							'description' 				=> __( 'Select how the tab area should be aligned.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "accordion_collapse", 'value' => 'true' ),
						),
						array(
							'type' 						=> 'dropdown',
							'heading' 					=> __( 'Tabs Position', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tabs_horizontal',
							'value' => array(
								__('Top', 'ts_visual_composer_extend') 				=> 'top',
								__('Bottom', 'ts_visual_composer_extend')			=> 'bottom',
							),
							'description' 				=> __( 'Select on which side the tabs should be shown at.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "tabs_align", 'value' => 'horizontal' ),
						),
						array(
							'type' 						=> 'dropdown',
							'heading' 					=> __( 'Tabs Position', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'accordion_horizontal',
							'value' => array(
								__('Top', 'ts_visual_composer_extend') 				=> 'top',
								__('Bottom', 'ts_visual_composer_extend')			=> 'bottom',
							),
							'description' 				=> __( 'Select on which side the tabs should be shown at.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "accordion_align", 'value' => 'horizontal' ),
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Tabs Centered", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tabs_centered",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want to center the tab cards atop the visible content section.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "tabs_align", 'value' => 'horizontal' ),
						),		
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Tabs Centered", "ts_visual_composer_extend" ),
							"param_name"		    	=> "accordion_centered",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want to center the tab cards atop the visible content section.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "accordion_align", 'value' => 'horizontal' ),
						),
						array(
							'type' 						=> 'dropdown',
							'heading' 					=> __( 'Tabs Position', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tabs_vertical',
							'value' => array(
								__('Left', 'ts_visual_composer_extend') 			=> 'left',
								__('Right', 'ts_visual_composer_extend')			=> 'right',
							),
							'description' 				=> __( 'Select on which side the tabs should be shown at.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "tabs_align", 'value' => 'vertical' ),
						),
						array(
							'type' 						=> 'dropdown',
							'heading' 					=> __( 'Tabs Position', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'accordion_vertical',
							'value' => array(
								__('Left', 'ts_visual_composer_extend') 			=> 'left',
								__('Right', 'ts_visual_composer_extend')			=> 'right',
							),
							'description' 				=> __( 'Select on which side the tabs should be shown at.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "accordion_align", 'value' => 'vertical' ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Tab Minimum Width", "ts_visual_composer_extend" ),
							"param_name"                => "tabs_minwidth",
							"value"                     => "200",
							"min"                       => "75",
							"max"                       => "300",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define the minimum width for the vertical tabs.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "tabs_align", 'value' => 'vertical' ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Tab Minimum Width", "ts_visual_composer_extend" ),
							"param_name"                => "accordion_minwidth",
							"value"                     => "200",
							"min"                       => "75",
							"max"                       => "300",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define the minimum width for the vertical tabs.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "accordion_align", 'value' => 'vertical' ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Tab Maximum Width", "ts_visual_composer_extend" ),
							"param_name"                => "tabs_maxwidth",
							"value"                     => "300",
							"min"                       => "300",
							"max"                       => "600",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define the maximum width for the vertical tabs.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "tabs_align", 'value' => 'vertical' ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Tab Maximum Width", "ts_visual_composer_extend" ),
							"param_name"                => "accordion_maxwidth",
							"value"                     => "300",
							"min"                       => "300",
							"max"                       => "600",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define the maximum width for the vertical tabs.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "accordion_align", 'value' => 'vertical' ),
						),
						array(
							'type' 						=> 'dropdown',
							'heading' 					=> __( 'Tabs Effect', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tabs_effect',
							'value' => array(
								__('Scale', 'ts_visual_composer_extend') 			=> 'scale',
								__('Slide Left', 'ts_visual_composer_extend')		=> 'slideleft',
								__('Slide Right', 'ts_visual_composer_extend') 		=> "slideright",
								__('Slide Top', 'ts_visual_composer_extend') 		=> "slidetop",
								__('Slide Down', 'ts_visual_composer_extend') 		=> "slidedown",
								__('None', 'ts_visual_composer_extend') 			=> "none",
							),
							'description' 				=> __( 'Select transition effect between the individual tabs.', 'ts_visual_composer_extend' )
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Default Tab", "ts_visual_composer_extend" ),
							"param_name"                => "tabs_default",
							"value"                     => "1",
							"min"                       => "1",
							"max"                       => "20",
							"step"                      => "1",
							"unit"                      => '',
							"description"               => __( "Select which tab should be initially shown upon page load.", "ts_visual_composer_extend" ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Tab Spacing", "ts_visual_composer_extend" ),
							"param_name"                => "tabs_spacing",
							"value"                     => "0",
							"min"                       => "0",
							"max"                       => "10",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define the spacing between each individual tab.", "ts_visual_composer_extend" ),
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Use in RTL Layout", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tabs_rtl",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want to show the tabs with basic support for RTL layouts.", "ts_visual_composer_extend" ),
						),							
						array(
							'type' 						=> 'dropdown',
							'heading' 					=> __( 'Tabs Deeplinking', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tabs_deeplinking',
							'value' => array(
								__('No Deeplinking', 'ts_visual_composer_extend')				=> 'none',
								__('Only for Active Session', 'ts_visual_composer_extend')		=> 'session',
								__('Page Load + Active Session', 'ts_visual_composer_extend')	=> 'all',
							),
							'description' 				=> __( 'Select what type of deeplinking should be applied to the tabs.', 'ts_visual_composer_extend' ),
						),
						// Responsive Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_2",
							"seperator"					=> "Responsive Settings",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Accordion Breakpoint", "ts_visual_composer_extend" ),
							"param_name"                => "tabs_breakaccordion",
							"value"                     => "640",
							"min"                       => "480",
							"max"                       => "1440",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define at which size the layout should switch to the basic accordion style.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "accordion_collapse", 'value' => 'true' ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Medium Breakpoint", "ts_visual_composer_extend" ),
							"param_name"                => "tabs_breakvertical",
							"value"                     => "820",
							"min"                       => "480",
							"max"                       => "1280",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define at which size the layout should switch back from vertical to horizontal and limit tabs to icons only.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "tabs_align", 'value' => 'vertical' ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Medium Breakpoint", "ts_visual_composer_extend" ),
							"param_name"                => "tabs_breakmedium",
							"value"                     => "820",
							"min"                       => "480",
							"max"                       => "1280",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define at which size the layout should limit tabs to icons only.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "tabs_align", 'value' => 'horizontal' ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Small Breakpoint", "ts_visual_composer_extend" ),
							"param_name"                => "tabs_breaksmall",
							"value"                     => "480",
							"min"                       => "0",
							"max"                       => "520",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define at which size the layout should convert the tabs to a collapsible menu.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "accordion_collapse", 'value' => 'false' ),
						),
						// AutoRotate Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_3",
							"seperator"					=> "Rotate Settings",
							"group" 					=> "Rotate Settings",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "AutoRotate Tabs", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tabs_autorotate",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want to automatically rotate between the tabs.", "ts_visual_composer_extend" ),
							"group" 					=> "Rotate Settings",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Rotation Delay", "ts_visual_composer_extend" ),
							"param_name"                => "tabs_delay",
							"value"                     => "5000",
							"min"                       => "1000",
							"max"                       => "20000",
							"step"                      => "100",
							"unit"                      => 'ms',
							"description"               => __( "Select the delay between each tab rotation.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "tabs_autorotate", 'value' => 'true' ),
							"group" 					=> "Rotate Settings",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Pause on Hover", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tabs_hoverpause",
							"value"                 	=> "true",
							"description"		    	=> __( "Switch the toggle if you want to pause the timer when hovering over the tabs.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "tabs_autorotate", 'value' => 'true' ),
							"group" 					=> "Rotate Settings",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Show Progressbar", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tabs_progressbar",
							"value"                 	=> "true",
							"description"		    	=> __( "Switch the toggle if you want to show a progressbar for the delay timer.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "tabs_autorotate", 'value' => 'true' ),
							"group" 					=> "Rotate Settings",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Play/Pause Buttons", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tabs_playpause",
							"value"                 	=> "true",
							"description"		    	=> __( "Switch the toggle if you want to show play/pause controls for the auto rotation.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "tabs_autorotate", 'value' => 'true' ),
							"group" 					=> "Rotate Settings",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Prev/Next Buttons", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tabs_navigation",
							"value"                 	=> "true",
							"description"		    	=> __( "Switch the toggle if you want to show prev/next controls for the auto rotation.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "tabs_autorotate", 'value' => 'true' ),
							"group" 					=> "Rotate Settings",
						),
						// Indicator Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_4",
							"seperator"					=> "Indicator Settings",
							"group" 					=> "Style Settings",
						),
						array(
							'type' 						=> 'dropdown',
							'heading' 					=> __( 'Tab Indicator', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tabs_indicator',
							'value' => array(
								__('Generic Tabs Icon', 'ts_visual_composer_extend')				=> 'icon',
								__('Tab Position as Roman Number', 'ts_visual_composer_extend')		=> 'roman',
								__('Tab Position as Standard Number', 'ts_visual_composer_extend')	=> 'number',
								__('Tab Position as Latin Letter', 'ts_visual_composer_extend')		=> 'letter',
							),
							'description' 				=> __( 'Select how tabs without custom icon should be displayed when the icon only layout has been triggered (Medium Breakpoint).', 'ts_visual_composer_extend' ),
							"group" 					=> "Style Settings",
						),
						// Font Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_5",
							"seperator"					=> "Font Settings",
							"group" 					=> "Style Settings",
						),
						array(
							"type"						=> "fontsmanager",
							"heading"					=> __( "Font Family", "ts_visual_composer_extend" ),
							"param_name"				=> "tabs_fonttype",
							"value"						=> "Default:regular",
							"default"					=> "true",
							"connector"					=> "tabs_fontmatch",
							"description"				=> __( "Select the default font family to be used for the tab index cards.", "ts_visual_composer_extend" ),
							"group"						=> "Style Settings",
						),
						array(
							"type"						=> "hidden_input",
							"param_name"				=> "tabs_fontmatch",
							"value"						=> "default",
							"group"						=> "Style Settings",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Font Size", "ts_visual_composer_extend" ),
							"param_name"        		=> "tabs_fontsize",
							"value"             		=> "14",
							"min"               		=> "10",
							"max"               		=> "28",
							"step"              		=> "1",
							"unit"              		=> 'px',
							"description"       		=> "Define the font size to be used for the tab index cards.",
							"group"						=> "Style Settings",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Icon Size", "ts_visual_composer_extend" ),
							"param_name"        		=> "tabs_iconsize",
							"value"             		=> "18",
							"min"               		=> "14",
							"max"               		=> "36",
							"step"              		=> "1",
							"unit"              		=> 'px',
							"description"       		=> "Define the icon size to be used for the tab index cards.",
							"group"						=> "Style Settings",
						),	
						// Preloader Setting
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_6",
							"seperator"					=> "Preloader Settings",
							"group" 					=> "Style Settings",
						),
						array(
							"type"				    	=> "livepreview",
							"heading"			    	=> __( "Preloader Style", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tabs_preloader",
							"preview"					=> "preloaders",
							"value"                 	=> 0,
							"description"		    	=> __( "Select the style for the preloader animation to be shown while the element is rendering.", "ts_visual_composer_extend" ),
							"group" 					=> "Style Settings",
						),
						// Tooltip Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_7",
							"seperator"					=> "Tooltip Settings",
							"group" 					=> "Style Settings",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Use Tooltips for Tabs", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tooltipster_allow",
							"value"                 	=> "true",
							"description"		    	=> __( "Switch the toggle if you want to apply tooltips to tabs that are too small to show the full tab title.", "ts_visual_composer_extend" ),
							"group" 					=> "Style Settings",
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Tooltip Style", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tooltipster_theme",
							"value"                 	=> array(
								__("Black", "ts_visual_composer_extend")                  => "tooltipster-black",
								__("Gray", "ts_visual_composer_extend")                   => "tooltipster-gray",
								__("Green", "ts_visual_composer_extend")                  => "tooltipster-green",
								__("Blue", "ts_visual_composer_extend")                   => "tooltipster-blue",
								__("Red", "ts_visual_composer_extend")                    => "tooltipster-red",
								__("Orange", "ts_visual_composer_extend")                 => "tooltipster-orange",
								__("Yellow", "ts_visual_composer_extend")                 => "tooltipster-yellow",
								__("Purple", "ts_visual_composer_extend")                 => "tooltipster-purple",
								__("Pink", "ts_visual_composer_extend")                   => "tooltipster-pink",
								__("White", "ts_visual_composer_extend")                  => "tooltipster-white",
							),
							"description"		    	=> __( "Select the tooltip style.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "tooltipster_allow", 'value' => 'true' ),
							"group" 					=> "Style Settings",
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Tooltip Animation", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tooltipster_animation",
							"value"                 	=> array(
								__("Fade", "ts_visual_composer_extend")                 	=> "fade",
								__("Swing", "ts_visual_composer_extend")                    => "swing",
								__("Fall", "ts_visual_composer_extend")                 	=> "fall",
								__("Grow", "ts_visual_composer_extend")                 	=> "grow",
								__("Slide", "ts_visual_composer_extend")                 	=> "slide",
							),
							"description"		    	=> __( "Select how the tooltip entry and exit should be animated once triggered.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "tooltipster_allow", 'value' => 'true' ),
							"group" 					=> "Style Settings",
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
							"dependency"            	=> array( 'element' => "tooltipster_allow", 'value' => 'true' ),
							"group" 					=> "Style Settings",
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
							"dependency"            	=> array( 'element' => "tooltipster_allow", 'value' => 'true' ),
							"group" 					=> "Style Settings",
						),
						// Effect Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_8",
							"seperator"					=> "Effect Settings",
							"group" 					=> "Style Settings",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Add Shadow Effect", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tabs_effect_shadow",
							"value"                 	=> "true",
							"description"		    	=> __( "Switch the toggle if you want to apply a shadow effect to each tab.", "ts_visual_composer_extend" ),
							"group" 					=> "Style Settings",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Add Grow Effect", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tabs_effect_grow",
							"value"                 	=> "true",
							"description"		    	=> __( "Switch the toggle if you want to apply a grow effect on each tab whem hovering over the tab.", "ts_visual_composer_extend" ),
							"group" 					=> "Style Settings",
						),
						array(
							'type' 						=> 'dropdown',
							'heading' 					=> __( 'Add Round Effect', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tabs_effect_rounded',
							'value' => array(
								__('First + Last Tabs Only', 'ts_visual_composer_extend')		=> 'frame',
								__('All Tabs', 'ts_visual_composer_extend')						=> 'all',
								__('No Rounded Effect', 'ts_visual_composer_extend')			=> 'none',
							),
							'description' 				=> __( 'Select if and where a round effect should be applied to the tabs.', 'ts_visual_composer_extend' ),
							"group" 					=> "Style Settings",
						),						
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Add Separator Line", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tabs_effect_line",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want to add a separator line between the tab cards and the visible tab content section.", "ts_visual_composer_extend" ),
							"group" 					=> "Style Settings",
						),
						// Default Theme
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_9",
							"seperator"					=> "Default Theme",
							"group" 					=> "Color Settings",
						),
						array(
							'type' 						=> 'dropdown',
							'heading' 					=> __( 'Global Tabs Theme', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tabs_theme',
							'value' => array(
								__('Default', 'ts_visual_composer_extend') 			=> '',
								__('Violet', 'ts_visual_composer_extend') 			=> 'pws_theme_violet',
								__('Green', 'ts_visual_composer_extend') 			=> "pws_theme_green",
								__('Yellow', 'ts_visual_composer_extend') 			=> "pws_theme_yellow",
								__('Gold', 'ts_visual_composer_extend') 			=> "pws_theme_gold",
								__('Orange', 'ts_visual_composer_extend') 			=> "pws_theme_orange",
								__('Red', 'ts_visual_composer_extend') 				=> "pws_theme_red",
								__('Purple', 'ts_visual_composer_extend') 			=> "pws_theme_purple",
								__('Grey', 'ts_visual_composer_extend') 			=> "pws_theme_grey",
							),
							'description' 				=> __( 'Select the overall tab theme.', 'ts_visual_composer_extend' ),
							"group" 					=> "Color Settings",
						),
						// Theme Customizations
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_10",
							"seperator"					=> "Theme Customizations",
							"group" 					=> "Color Settings",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Customize Colors", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tabs_customize",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want to customize some global tab color settings.", "ts_visual_composer_extend" ),
							"group" 					=> "Color Settings",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Content Background Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tabs_background',
							'value'						=> '#f7f7f7',
							'description' 				=> __( 'Define the background color for the tab content.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "tabs_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Color Settings",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Active Tab Background Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tabs_active_back',
							'value'						=> '#f7f7f7',
							'description' 				=> __( 'Define the background color for the active tab.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "tabs_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Color Settings",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Active Tab Text Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tabs_active_text',
							'value'						=> '#505050',
							'description' 				=> __( 'Define the text color for the active tab.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "tabs_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Color Settings",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Active Tab Icon Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tabs_active_icon',
							'value'						=> '#505050',
							'description' 				=> __( 'Define the icon color for the active tab.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "tabs_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Color Settings",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Auto-Rotate Progressbar Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tabs_progresscolor',
							'value'						=> '#70c5c2',
							'description' 				=> __( 'Define the color for the auto-rotate timer progressbar.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "tabs_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Color Settings",
						),	
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Auto-Rotate Controls Standard Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tabs_controlscolor',
							'value'						=> '#9bd7d5',
							'description' 				=> __( 'Define the color for the auto-rotate controls default color.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "tabs_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Color Settings",
						),							
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Auto-Rotate Controls Hover Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tabs_controlshover',
							'value'						=> '#70c5c2',
							'description' 				=> __( 'Define the color for the auto-rotate controls hover color.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "tabs_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Color Settings",
						),						
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Separator Line Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tabs_linecolor',
							'value'						=> '#9bd7d5',
							'description' 				=> __( 'Define the color for the optional separator line between the tab cards and the visible content section.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "tabs_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Color Settings",
						),
						// Other Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_11",
							"seperator"					=> "Mobile Menu",
							"dependency"		    	=> array( 'element' => "tabs_customize", 'value' => 'true' ),
							"group" 					=> "Color Settings",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Standard Background Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tabs_mobilebackground',
							'value'						=> '#9bd7d5',
							'description' 				=> __( 'Define the standard background color for the status bar of the mobile menu.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "tabs_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Color Settings",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Standard Font Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tabs_mobilecolor',
							'value'						=> '#ffffff',
							'description' 				=> __( 'Define the standard font color for the status bar of the mobile menu.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "tabs_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Color Settings",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Hover Background Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tabs_mobilehoverback',
							'value'						=> '#70c5c2',
							'description' 				=> __( 'Define the hover background color for the status bar of the mobile menu.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "tabs_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Color Settings",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Hover Font Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tabs_mobilehovercolor',
							'value'						=> '#ffffff',
							'description' 				=> __( 'Define the hover font color for the status bar of the mobile menu.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "tabs_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Color Settings",
						),
						// Other Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_12",
							"seperator"					=> "Other Settings",
							"group" 					=> "Other Settings",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Always Trigger Resize Event", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tabs_resize",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want to always trigger a global resize event each time any tab has been selected; can be helpful if tabs contain responsive elements.", "ts_visual_composer_extend" ),
							"group" 					=> "Other Settings",
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
							"group" 					=> "Other Settings",
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
							"group" 					=> "Other Settings",
						),
						array(
							'type' 						=> 'tab_id',
							'heading' 					=> __( 'Tabs Container ID', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'tab_contid',
							'description' 				=> __( 'This is the automatic ID for the element; it can not be changed.', 'ts_visual_composer_extend' ),
							"group" 					=> "Other Settings",
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
			function TS_VCSC_Add_FancyTabs_Elements_Single() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Create Array of Excluded Child Elements
				$TS_VCSC_Exluded_Children				= array();
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Children as $ElementName => $element) {
					array_push($TS_VCSC_Exluded_Children, $element['base']);
				}
				$TS_VCSC_Exluded_Children				= implode(",", $TS_VCSC_Exluded_Children);
				// Add Single Tab
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					'name' 								=> __('TS Single Tab (BETA)', 'ts_visual_composer_extend'),
					'base' 								=> 'TS_VCSC_Fancy_Tabs_Single',
					"controls"							=> "full",						
					'is_container' 						=> true,
					'content_element' 					=> true,
					//"as_parent"                       => array('only' => 'vc_row_inner,vc_row,layerslider_vc,rev_slider_vc,vc_empty_space,vc_toggle,TS_VCSC_Image_Hotspot_Container,TS_VCSC_Horizontal_Steps_Container,TS_VCSC_Anything_Slider,TS_VCSC_iPresenter_Container,TS_VCSC_Lightbox_Gallery,TS-VCSC-Spacer,TS_EnlighterJS_Snippet_Container,TS_VCSC_Circle_Loop_Container'),
					"as_parent"                       	=> array('except' => 'TS_VCSC_Fancy_Tabs_Container,vc_tabs,vc_tour,vc_accordion,vc_tta_tabs,vc_tta_tour,vc_tta_accordion,vc_tta_pageable,vc_tta_section,vc_section,' . $TS_VCSC_Exluded_Children),
					"as_child"							=> array('only' => 'TS_VCSC_Fancy_Tabs_Container'),
					//"html_template"           		=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginPath . '/templates/TS_VCSC_Fancy_Tabs_Single.php',
					//"front_enqueue_js"  				=> preg_replace( '/\s/', '%20', plugins_url( 'js/frontend/ts-vcsc-frontend-fancytabs-single.min.js', __FILE__ ) ),
					'js_view'     						=> 'TS_VCSC_IconTabsSingleViewCustom',
					'params' 							=> array(
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_1",
							"seperator"             	=> "General Tab Settings",
						),
						array (
							'type' 						=> 'textfield',
							'heading' 					=> __( 'Title', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'title',
							'description' 				=> __( 'Provide a title or name for this tab.', 'ts_visual_composer_extend' )
						),
						array(
							"type" 						=> "icons_panel",
							'heading' 					=> __( 'Icon', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'icon',
							'value'						=> '',
							"settings" 					=> array(
								"emptyIcon" 					=> true,
								'emptyIconValue'				=> 'transparent',
								"type" 							=> 'extensions',
							),
							"description"       		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorVisualSelector == "true" ? __( "Select the icon for the tab.", "ts_visual_composer_extend" ) : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconSelectorString),
						),						
						array(
							'type' 						=> 'dropdown',
							'heading' 					=> __( 'Animation Type', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'animation_type',
							'value' => array(
								__('Hover', 'ts_visual_composer_extend') 			=> 'hover',
								__('Infinite', 'ts_visual_composer_extend')			=> 'infinite',
							),
							'description' 				=> __( 'Select what animation type the icon should be using.', 'ts_visual_composer_extend' )
						),	
						array(
							"type"						=> "css3animations",
							"heading"					=> __("Icon Animation", "ts_visual_composer_extend"),
							"param_name"				=> "animation_class",
							"prefix"					=> "",
							"connector"					=> "css3animations_in",
							"noneselect"				=> "true",
							"default"					=> "",
							"value"						=> "",
							"description"				=> __("Select the hover animation for the icon.", "ts_visual_composer_extend"),
						),
						array(
							"type"						=> "hidden_input",
							"heading"					=> __( "Icon Animation", "ts_visual_composer_extend" ),
							"param_name"				=> "css3animations_in",
							"value"						=> "",
						),						
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Customize Tab", "ts_visual_composer_extend" ),
							"param_name"		    	=> "customize",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want to customize the tab design.", "ts_visual_composer_extend" ),
						),
						array (
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Tab Standard Background Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> "color_tab",
							'value'						=> '#9bd7d5',
							'description' 				=> __( 'Set the tab standard background color.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"		    	=> array( 'element' => "customize", 'value' => 'true' ),
						),
						array (
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Tab Hover Background Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> "hover_tab",
							'value'						=> '#70c5c2',
							'description' 				=> __( 'Set the tab hover background color.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"		    	=> array( 'element' => "customize", 'value' => 'true' ),
						),
						array (
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Tab Standard Text Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> "color_text",
							'value'						=> '#ffffff',
							'description' 				=> __( 'Set the tab standard text color.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"		    	=> array( 'element' => "customize", 'value' => 'true' ),
						),
						array (
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Tab Hover Text Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> "hover_text",
							'value'						=> '#ffffff',
							'description' 				=> __( 'Set the tab hover text color.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"		    	=> array( 'element' => "customize", 'value' => 'true' ),
						),
						array (
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Tab Standard Icon Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> "color_icon",
							'value'						=> '#ffffff',
							'description' 				=> __( 'Set the tab standard icon color.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"		    	=> array( 'element' => "customize", 'value' => 'true' ),
						),
						array (
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Tab Hover Icon Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> "hover_icon",
							'value'						=> '#ffffff',
							'description' 				=> __( 'Set the tab hover icon color.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"		    	=> array( 'element' => "customize", 'value' => 'true' ),
						),
						// Other Tab Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_2",
							"seperator"             	=> "Other Tab Settings",
							"group" 					=> "Other Settings",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Trigger Resize Event", "ts_visual_composer_extend" ),
							"param_name"		    	=> "resize",
							"value"                 	=> "false",
							"description"		    	=> __( "Switch the toggle if you want to trigger a global resize event each time this tab has been selected; can be helpful if tab contains responsive elements.", "ts_visual_composer_extend" ),
							"group" 					=> "Other Settings",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Activate Height Watch", "ts_visual_composer_extend" ),
							"param_name"		    	=> "watch",
							"value"                 	=> "true",
							"description"		    	=> __( "Switch the toggle if you want to attempt catching height changes to content elements inside the tab, in order to adjust overall element height if necessary; will not work for height changes due to CSS3 scaling.", "ts_visual_composer_extend" ),
							"group" 					=> "Other Settings",
						),						
						array(
							"type" 						=> "advanced_styling",
							"heading" 					=> __("Tabs Padding", "ts_visual_composer_extend"),
							"param_name" 				=> "padding",
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
							"default_positions"			=> array(
								//"All"							=> array("string" => __("All", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#000000", "radius" => "0", "unitradius" => "px"),
								"Top"							=> array("string" => __("Top", "ts_visual_composer_extend"),	"width" => "0", "unitwidth" => "px", "style" => "solid", "color" => "#000000", "radius" => "0", "unitradius" => "px"),
								"Right" 						=> array("string" => __("Right", "ts_visual_composer_extend"),	"width" => "0", "unitwidth" => "px", "style" => "solid", "color" => "#000000", "radius" => "0", "unitradius" => "px"),
								"Bottom"						=> array("string" => __("Bottom", "ts_visual_composer_extend"),	"width" => "0", "unitwidth" => "px", "style" => "solid", "color" => "#000000", "radius" => "0", "unitradius" => "px"),
								"Left"							=> array("string" => __("Left", "ts_visual_composer_extend"),	"width" => "0", "unitwidth" => "px", "style" => "solid", "color" => "#000000", "radius" => "0", "unitradius" => "px"),
							),
							"description"       		=> __( "Define optional internal paddings for the tab container.", "ts_visual_composer_extend" ),
							"group" 					=> "Other Settings",
						),
						array (
							'type' 						=> 'el_id',
							'heading' 					=> __( 'Tab ID', 'ts_visual_composer_extend' ),
							'param_name' 				=> "tab_id",
							'settings' 					=> array(
								'auto_generate' 		=> true,
							),
							'description' 				=> __( 'This is the automatic ID for the element; if changed, ensure that it remains an unique ID.', 'ts_visual_composer_extend' ),
							"group" 					=> "Other Settings",
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
	if ((class_exists('WPBakeryShortCode')) && (!class_exists('WPBakeryShortCode_TS_VCSC_Fancy_Tabs_Container'))) {
		class WPBakeryShortCode_TS_VCSC_Fancy_Tabs_Container extends WPBakeryShortCode {
			static $filter_added 				= false;
			protected $controls_css_settings 	= 'out-tc vc_controls-content-widget';
			//protected $controls_list 			= array('edit', 'clone', 'delete');
			protected $controls_list 			= array('edit', 'delete');
			public function __construct($settings) {
				// !Important to call parent constructor to active all logic for shortcode.
				parent::__construct($settings);
				if (!self::$filter_added) {
					$this->addFilter( 'vc_inline_template_content', 'setCustomTabId' );
					self::$filter_added 		= true;
				}
			}
			public function contentAdmin($atts, $content = null) {
				$width = $custom_markup = '';
				$shortcode_attributes = array( 'width' => '1/1' );
				foreach ( $this->settings['params'] as $param ) {
					if ( $param['param_name'] != 'content' ) {
						//$shortcode_attributes[$param['param_name']] = $param['value'];
						if ( isset( $param['value'] ) && is_string( $param['value'] ) ) {
							$shortcode_attributes[$param['param_name']] = $param['value'];
						} elseif ( isset( $param['value'] ) ) {
							$shortcode_attributes[$param['param_name']] = $param['value'];
						}
					} else if ( $param['param_name'] == 'content' && $content == NULL ) {
						//$content = $param['value'];
						$content = $param['value'];
					}
				}
				extract( shortcode_atts($shortcode_attributes, $atts ));
				// Extract tab titles
				preg_match_all( '/vc_tab title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $content, $matches, PREG_OFFSET_CAPTURE );
				$output 						= '';
				$tab_data 					= array();
				if ( isset($matches[0])) {
					$tab_data 				= $matches[0];
				}
				$tmp 							= '';
				if (count($tab_data)) {
					$tmp .= '<ul class="clearfix tabs_controls">';
					foreach ( $tab_data as $tab ) {
						preg_match( '/title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE );
						if ( isset( $tab_matches[1][0] ) ) {
							$tmp .= '<li><a href="#tab-' . ( isset( $tab_matches[3][0] ) ? $tab_matches[3][0] : sanitize_title( $tab_matches[1][0] ) ) . '">' . $tab_matches[1][0]. '</a></li>';
						}
					}
					$tmp .= '</ul>' . "\n";
				} else {
					$output .= do_shortcode( $content );
				}
				$elem 							= $this->getElementHolder( $width );
				$iner 							= '';
				foreach ($this->settings['params'] as $param) {
					$custom_markup 				= '';
					$param_value 				= isset($param['param_name']) ? $param['param_name'] : '';
					if ( is_array( $param_value ) ) {
						// Get first element from the array
						reset( $param_value );
						$first_key = key( $param_value );
						$param_value = $param_value[$first_key];
					}
					$iner .= $this->singleParamHtmlHolder( $param, $param_value );
				}
				//$elem = str_ireplace('%wpb_element_content%', $iner, $elem);
				if ( isset( $this->settings["custom_markup"] ) && $this->settings["custom_markup"] != '' ) {
					if ( $content != '' ) {
						$custom_markup = str_ireplace( "%content%", $tmp . $content, $this->settings["custom_markup"] );
					} else if ( $content == '' && isset( $this->settings["default_content_in_template"] ) && $this->settings["default_content_in_template"] != '' ) {
						$custom_markup = str_ireplace( "%content%", $this->settings["default_content_in_template"], $this->settings["custom_markup"] );
					} else {
						$custom_markup = str_ireplace( "%content%", '', $this->settings["custom_markup"] );
					}
					//$output .= do_shortcode($this->settings["custom_markup"]);
					$iner .= do_shortcode( $custom_markup );
				}
				$elem = str_ireplace( '%wpb_element_content%', $iner, $elem );
				$output = $elem;
				return $output;
			}
			public function getTabTemplate() {
				return '<div class="wpb_template">' . do_shortcode('[TS_VCSC_Fancy_Tabs_Single tab_id="" title="Tab" icon="" animation_type="hover" animation_class="" customize="false" color_tab="#9bd7d5" hover_tab="#70c5c2" color_text="#ffffff" hover_text="#ffffff" color_icon="#ffffff" hover_icon="#ffffff"][/TS_VCSC_Fancy_Tabs_Single]') . '</div>';
			}
			public function setCustomTabId($content) {
				return preg_replace( '/tab\_id\=\"([^\"]+)\"/', 'tab_id="$1-' . time() . '"', $content );
			}
		}
		define('VCSC_TAB_TITLE', __("Fancy Tab", "ts_visual_composer_extend"));
		if (function_exists('vc_path_dir')){
			require_once vc_path_dir('SHORTCODES_DIR', 'vc-column.php');
		}
	}
	if ((class_exists('WPBakeryShortCode_VC_Column')) && (!class_exists('WPBakeryShortCode_TS_VCSC_Fancy_Tabs_Single'))) {
		class WPBakeryShortCode_TS_VCSC_Fancy_Tabs_Single extends WPBakeryShortCode_VC_Column {
			protected $controls_css_settings 	= 'tc vc_control-container';
			//protected $controls_list 			= array('add', 'edit', 'clone', 'delete');
			protected $controls_list 			= array('add', 'edit', 'delete');
			//protected $controls_template_file = 'editors/partials/backend_controls_tab.tpl.php';
			protected $predefined_atts = array(
				'tab_id' 						=> VCSC_TAB_TITLE,
				'title' 						=> '',
				'icon'							=> '',
			);
			public function __construct($settings) {
				parent::__construct($settings);
			}
			public function customAdminBlockParams() {
				return ' id="tab-' . $this->atts['tab_id'] . '"';
			}
			public function mainHtmlBlockParams($width, $i) {
				return 'data-element_type="' . $this->settings["base"] . '" class="wpb_' . $this->settings['base'] . ' wpb_sortable wpb_content_holder"' . $this->customAdminBlockParams();
			}
			public function containerHtmlBlockParams($width, $i) {
				return 'class="wpb_column_container vc_container_for_children"';
			}
			public function getColumnControls($controls, $extended_css = '') {					
				return $this->getColumnControlsModular($extended_css);
				/*$controls_start = '<div class="controls controls_column' . ( ! empty( $extended_css ) ? " {$extended_css}" : '' ) . '">';
					if ( $extended_css == 'bottom-controls' ) $control_title = sprintf( __( 'Append to this %s', 'ts_visual_composer_extend' ), strtolower( $this->settings( 'name' ) ) );
					else $control_title = sprintf( __( 'Prepend to this %s', 'ts_visual_composer_extend' ), strtolower( $this->settings( 'name' ) ) );
					$controls_add = ' <a class="column_add" href="#" title="' . $control_title . '"></a>';
					$controls_edit = ' <a class="column_edit" href="#" title="' . sprintf( __( 'Edit this %s', 'ts_visual_composer_extend' ), strtolower( $this->settings( 'name' ) ) ) . '"></a>';
					$controls_clone = '<a class="column_clone" href="#" title="' . sprintf( __( 'Clone this %s', 'ts_visual_composer_extend' ), strtolower( $this->settings( 'name' ) ) ) . '"></a>';
					$controls_delete = '<a class="column_delete" href="#" title="' . sprintf( __( 'Delete this %s', 'ts_visual_composer_extend' ), strtolower( $this->settings( 'name' ) ) ) . '"></a>';
					return $controls_start . $controls_add . $controls_edit . $controls_clone . $controls_delete . $controls_end;
				$controls_end = '</div>';*/
			}
		}
	}	
	// Initialize "TS Fancy Tabs" Class
	if (class_exists('TS_Fancy_Tabs')) {
		$TS_Fancy_Tabs = new TS_Fancy_Tabs;
	}
?>