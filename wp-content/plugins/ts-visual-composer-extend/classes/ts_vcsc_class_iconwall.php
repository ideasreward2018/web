<?php
	if (!class_exists('TS_Icon_Wall')){
		class TS_Icon_Wall {
			function __construct() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						$this->TS_VCSC_Add_IconWall_Elements_Lean();
					} else if (function_exists('vc_map')) {
						add_action('init',									array($this, 'TS_VCSC_Add_IconWall_Element_Container'), 9999999);
						add_action('init',									array($this, 'TS_VCSC_Add_IconWall_Element_Item'), 9999999);
					}
				} else {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_IconWall_Elements_Lean'), 9999999);
					} else if (function_exists('vc_map')) {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_IconWall_Element_Container'), 9999999);
						add_action('admin_init',							array($this, 'TS_VCSC_Add_IconWall_Element_Item'), 9999999);
					}
				}
				if ((is_admin() == false) || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAJAX == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAlways == "true")) {
					add_shortcode('TS_VCSC_Icon_Wall_Container',			array($this, 'TS_VCSC_IconWall_Container'));
					add_shortcode('TS_VCSC_Icon_Wall_Item',              	array($this, 'TS_VCSC_IconWall_Item'));
				}
			}
			
			// Register Element(s) via LeanMap
			function TS_VCSC_Add_IconWall_Elements_Lean() {
				vc_lean_map('TS_VCSC_Icon_Wall_Container', 					array($this, 'TS_VCSC_Add_IconWall_Element_Container'), null);
				vc_lean_map('TS_VCSC_Icon_Wall_Item', 						array($this, 'TS_VCSC_Add_IconWall_Element_Item'), null);
			}
			
			// Icon Wall Container
			function TS_VCSC_IconWall_Container ($atts, $content = null) {
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();
				
				// Check for Front End Editor
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					$wall_frontend					= "true";
				} else {
					$wall_frontend					= "false";
				}
	
				wp_enqueue_style('ts-extend-iconwall');
				if ($wall_frontend == "false") {					
					wp_enqueue_script('ts-extend-iconwall');
					wp_enqueue_style('ts-extend-tooltipster');
					wp_enqueue_script('ts-extend-tooltipster');
					wp_enqueue_style('ts-extend-animations');
				} 
				wp_enqueue_style('ts-visual-composer-extend-front');
				wp_enqueue_script('ts-visual-composer-extend-front');
				
				$output = $title = $interval = $el_class = '';
				extract(shortcode_atts( array(
					// General Settings
					'current'						=> 0,
					'spacing'						=> 5,
					'width'							=> 100,
					'break_large'					=> 1024,
					'break_medium'					=> 768,
					'break_small'					=> 480,
					'fluid_height'					=> 'true',
					'layout_rtl'					=> 'false',
					'item_overlap'					=> 'false',
					'item_shuffle'					=> 'false',
					'item_centerlast'				=> 'true',
					// Style Settings
					'style_shadow'					=> 'true',
					'style_custom'					=> 'true',
					// Font Settings
					'title_fonttype'				=> 'Default:regular',
					'title_fontmatch'				=> 'default',
					'title_fontsize'				=> 32,
					'title_align'					=> 'center',
					'title_weight'					=> 200,
					'title_padding'					=> 'padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;',
					'title_transform'				=> 'uppercase',
					'title_decoration'				=> 'none',
					'title_border'					=> 'none',
					'content_fonttype'				=> 'Default:regular',
					'content_fontmatch'				=> 'default',
					'content_fontsize'				=> 14,
					// Color Settings
					'standard_type'					=> 'color',
					'standard_color'				=> '#676767',
					'standard_gradient'				=> '',
					'standard_image'				=> '',
					'standard_size'					=> 'cover',
					'standard_repeat'				=> 'no-repeat',
					'standard_back'					=> '#ffffff',
					'standard_border'				=> '#cccccc',
					'hover_type'					=> 'color',
					'hover_color'					=> '#676767',
					'hover_gradient'				=> '',
					'hover_image'					=> '',
					'hover_size'					=> 'cover',
					'hover_repeat'					=> 'no-repeat',
					'hover_back'					=> '#FFD800',
					'hover_border'					=> '#cccccc',
					'active_type'					=> 'color',
					'active_color'					=> '#ffffff',
					'active_gradient'				=> '',
					'active_image'					=> '',
					'active_repeat'					=> 'no-repeat',
					'active_size'					=> 'cover',
					'active_back'					=> '#AE0000',
					'active_border'					=> '#cccccc',
					// AutoPlay Settings
					'autoplay'						=> 'false',
					'delay'							=> 5000,
					'pausehover'					=> 'true',
					'progress_bar'					=> 'true',
					'progress_color'				=> '#f7f7f7',
					'progress_height'				=> 2,
					// Tooltip Settings
					'tooltipster_allow'				=> 'true',
					'tooltipster_animation'			=> 'fade',
					'tooltipster_position'			=> 'top',
					'tooltipster_theme'				=> 'tooltipster-black',
					'tooltipster_offsetx'			=> 0,
					'tooltipster_offsety'			=> 0,
					// WPAutoP Callback
					'content_wpautop'				=> 'false',
					// Other Settings
					'margin_top'					=> 0,
					'margin_bottom'					=> 0,
					'el_id'							=> '',
					'el_class'						=> '',
					'css'							=> '',
				), $atts ) );
				
				$output = $styles = '';
				
				$wall_random						= mt_rand(999999, 9999999);
				$wpautop 							= ($content_wpautop == "true" ? true : false);
				$inline								= TS_VCSC_FrontendAppendCustomRules('style');
				
				if (!empty($el_id)) {
					$wall_id						= $el_id;
				} else {
					$wall_id						= 'ts-icon-wall-container-' . $wall_random;
				}
				
				// Extract element titles from $content
				preg_match_all('/TS_VCSC_Icon_Wall_Item([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE);
				$wall_items 						= array();
				if (isset($matches[1])) {
					$wall_items 					= $matches[1];
				}
				
				$el_class 							= str_replace(".", "", $el_class);				
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Icon_Wall_Container', $atts);
				} else {
					$css_class						= $el_class;
				}				
				if ($layout_rtl == "true") {
					$layout_class					= "ts-icon-wall-container-rtl";
				} else {
					$layout_class					= "ts-icon-wall-container-ltr";
				}
				
				$data_general						= 'data-random="' . $wall_random . '" data-tooltips="' . $tooltipster_allow . '" data-rtl="' . $layout_rtl . '" data-initial="' . $current . '" data-current="' . $current . '" data-centerlast="' . $item_centerlast . '" data-overlap="' . $item_overlap . '" data-shuffle="' . $item_shuffle . '" data-offsetx="' . $tooltipster_offsetx . '" data-offsety="' . $tooltipster_offsety . '" data-spacing="' . $spacing . '" data-fluid="' . $fluid_height . '"';
				$data_breaks						= 'data-large="' . $break_large . '" data-medium="' . $break_medium . '" data-small="' . $break_small . '"';
				$data_autoplay						= 'data-autoplay="' . $autoplay . '" data-delay="' . $delay . '" data-pause="false" data-hover="' . $pausehover . '" data-progressbar="' . $progress_bar . '"';
				if ($tooltipster_allow == 'true') {
					$data_tooltips					= 'data-tooltipster-position="' . $tooltipster_position . '" data-tooltipster-theme="' . $tooltipster_theme . '" data-tooltipster-animation="' . $tooltipster_animation . '" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . $tooltipster_offsety . '"';
				} else {
					$data_tooltips					= '';
				}
				
				if ($wall_frontend == "false") {
					if ($inline == "false") {
						$styles .= '<style id="ts-icon-wall-' . $wall_random . '-styles" type="text/css">';
					}
						// Wall Styling
						if ($style_custom == "true") {
							$styles .= 'body #' . $wall_id . '.ts-icon-wall-container .ts-icon-wall-items-icon-single {';
								$styles .= 'color: ' . $standard_color . ';';
								$styles .= 'background: transparent;';
								if ($standard_type == "color") {
									$styles .= 'background-color: ' . $standard_back . ';';
									$styles .= 'background-image: none;';
								} else if ($standard_type == "gradient") {
									$styles .= $standard_gradient . ';';
								} else if ($standard_type == "image") {
									$standard_image	= wp_get_attachment_image_src($standard_image, 'full');
									$styles .= 'background-color: transparent;';
									$styles .= 'background-image: url("' . $standard_image[0] . '");';
									$styles .= 'background-repeat: ' . $standard_repeat . ';';
									$styles .= 'background-size: ' . $standard_size . ';';
									$styles .= 'background-position: center center;';
								}
								$styles .= 'border-color: ' . $standard_border . ';';
							$styles .= '}';
							$styles .= 'body #' . $wall_id . '.ts-icon-wall-container .ts-icon-wall-items-icon-single:hover {';
								$styles .= 'color: ' . $hover_color . ';';
								$styles .= 'background: transparent;';
								if ($hover_type == "color") {									
									$styles .= 'background-color: ' . $hover_back . ';';
									$styles .= 'background-image: none;';
								} else if ($hover_type == "gradient") {
									$styles .= $hover_gradient . ';';
								} else if ($hover_type == "image") {
									$hover_image	= wp_get_attachment_image_src($hover_image, 'full');
									$styles .= 'background-color: transparent;';
									$styles .= 'background-image: url("' . $hover_image[0] . '");';
									$styles .= 'background-repeat: ' . $hover_repeat . ';';
									$styles .= 'background-size: ' . $hover_size . ';';
									$styles .= 'background-position: center center;';
								}
								$styles .= 'border-color: ' . $hover_border . ';';
							$styles .= '}';
							$styles .= 'body #' . $wall_id . '.ts-icon-wall-container .ts-icon-wall-items-icon-single.active {';
								$styles .= 'color: ' . $active_color . ';';
								$styles .= 'background: transparent;';
								if ($active_type == "color") {
									$styles .= 'background-color: ' . $active_back . ';';
									$styles .= 'background-image: none;';
								} else if ($active_type == "gradient") {
									$styles .= $active_gradient . ';';
								} else if ($active_type == "image") {
									$active_image	= wp_get_attachment_image_src($active_image, 'full');
									$styles .= 'background-color: transparent;';
									$styles .= 'background-image: url("' . $active_image[0] . '");';
									$styles .= 'background-repeat: ' . $active_repeat . ';';
									$styles .= 'background-size: ' . $active_size . ';';
									$styles .= 'background-position: center center;';
								}
								$styles .= 'border-color: ' . $active_border . ';';
							$styles .= '}';
						}
						// Title Styling
						if (strpos($title_fonttype, 'Default') === false) {
							$title_default				= TS_VCSC_GetFontFamily($wall_id, $title_fonttype, $title_fontmatch, false, true, false);
						} else {
							$title_default				= '';
						}
						$styles .= 'body #' . $wall_id . '.ts-icon-wall-container .ts-icon-wall-items-contents .ts-icon-wall-items-content-single .ts-icon-wall-items-content-title {';
							if ($title_default != "") {
								$styles .= $title_default . ';';
							}
							$styles .= 'font-size: ' . $title_fontsize . 'px;';
							$styles .= 'font-weight: ' . $title_weight . ';';
							$styles .= 'text-align: ' . $title_align . ';';
							$styles .= 'text-transform: ' . $title_transform . ';';
							$styles .= 'text-decoration: ' . $title_decoration . ';';
							$styles .= $title_padding;
							$styles .= $title_border;	
						$styles .= '}';
						// Content Styling
						if (strpos($content_fonttype, 'Default') === false) {
							$content_default			= TS_VCSC_GetFontFamily($wall_id, $content_fonttype, $content_fontmatch, false, true, false);
						} else {
							$content_default			= '';
						}
						$styles .= 'body #' . $wall_id . '.ts-icon-wall-container .ts-icon-wall-items-contents .ts-icon-wall-items-content-single .ts-icon-wall-items-content-message {';
							if ($content_default != "") {
								$styles .= $content_default . ';';
							}
							$styles .= 'font-size: ' . $content_fontsize . 'px;';
						$styles .= '}';
					if ($inline == "false") {
						$styles .= '</style>';
					}
					if (($styles != "") && ($inline == "true")) {
						wp_add_inline_style('ts-visual-composer-extend-custom', TS_VCSC_MinifyCSS($styles));
					}
					
					// Create Final Output
					$output .= '<div id="' . $wall_id . '" class="ts-icon-wall-container ' . $css_class . ' ' . $layout_class . '" ' . $data_general . ' ' . $data_breaks . ' ' . $data_autoplay . ' ' . $data_tooltips . ' style="width: ' . $width . '%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
						// Style Output
						if ($inline == "false") {
							$output .= TS_VCSC_MinifyCSS($styles);
						}
						// Icon Output
						$output .= '<div class="ts-icon-wall-items-icons" style="">';
							$counter				= 0;
							foreach ($wall_items as $item) {
								$item_atts 			= shortcode_parse_atts($item[0]);								
								// Check Icon/Image
								if ((isset($item_atts['replace'])) && ($item_atts['replace'] == "true") && (!empty($item_atts['image']))) {
									$item_type						= 'image';
									$item_icon						= '';
									$item_image_path 				= wp_get_attachment_image_src($item_atts['image'], 'large');
									$item_image_path				= "background-image: url('" . $item_image_path[0] . "');";
								} else if (isset($item_atts['icon'])) {
									$item_type						= 'icon';
									$item_icon						= $item_atts['icon'];
									$item_image_path				= '';
								} else {
									$item_type						= 'none';
									$item_icon						= '';
									$item_image_path				= '';
								}
								// Assign Animations
								if (isset($item_atts['animation_type']) && ($item_atts['animation_type'] != 'none') && isset($item_atts['animation_class'])) {
									$item_animate	= 'ts-box-icon ts-icon-wall-items-icon-animated';
									$icon_animate	= 'ts-' . $item_atts['animation_type'] . '-css-' . $item_atts['animation_class'] . '';
								} else {
									$item_animate	= '';
									$icon_animate	= '';
								}
								// Create Final Output
								$output .= '<div id="ts-icon-wall-items-icon-single-' . $wall_random . '-' . $counter . '" class="' . $item_animate . ' ts-icon-wall-items-icon-single ' . ($counter == $current ? '' : '') . ' ' . ($style_shadow == 'true' ? 'ts-icon-wall-items-icon-shadow' : '') . ' ' . (((isset($item_atts['title']) && ($tooltipster_allow == 'true'))) ? 'ts-icon-wall-items-icon-tooltip' : '') . '" data-tooltipset="false" data-tooltipster-text="' . ((isset($item_atts['title'])) ? strip_tags($item_atts['title']) : '') . '" data-tooltipster-offsetx="0" data-tooltipster-offsety="0" data-index="' . $counter . '" style="">';
									if ($item_type == "icon") {
										$output .= '<i class="ts-icon-wall-icon ' . $item_icon . ' ' . $icon_animate . '"></i>';
									} else if ($item_type == "image") {
										$output .= '<i class="ts-icon-wall-image ' . $icon_animate . '" style="' . $item_image_path . '"></i>';
									}
								$output .= '</div>';
								$counter++;
							}							
						$output .= '</div>';
						// Progressbar Output
						if (($autoplay == 'true') && ($progress_bar == 'true')) {
							$output .= '<div id="ts-icon-wall-auto-progressbar-holder-' . $wall_random . '" class="ts-icon-wall-auto-progressbar-holder" style=""><div id="ts-icon-wall-auto-progressbar-animate-' . $wall_random . '" class="ts-icon-wall-auto-progressbar-animate" data-progress="0" style="background: ' . $progress_color . '; height: ' . $progress_height . 'px;"></div></div>';
						}
						// Content Output
						$output .= '<div class="ts-icon-wall-items-contents" style="">';					
							if (function_exists('wpb_js_remove_wpautop')){
								$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
							} else {
								$output .= do_shortcode($content);
							}				
						$output .= '</div>';
					$output .= '</div>';
				} else {
					$output .= '<div id="' . $wall_id . '" class="ts-icon-wall-container-edit">';
						if (function_exists('wpb_js_remove_wpautop')){
							$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
						} else {
							$output .= do_shortcode($content);
						}
					$output .= '</div>';
				}
				
				$wall_items 						= array();
					
				echo $output;
				
				$myvariable = ob_get_clean();
				return $myvariable;
			}    
			// Icon Wall Item
			function TS_VCSC_IconWall_Item ($atts, $content = null) {
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();
				
				extract( shortcode_atts( array(
					// General Settings
					'replace'						=> 'false',
					'icon'							=> '',
					'image'							=> '',
					'title'							=> '',
					'animation_type'				=> 'none',
					'animation_class'				=> '',
					'content_wpautop'				=> 'false',
					'el_id' 						=> '',
					'el_class'						=> '',
					'css'							=> '',
				), $atts ) );
				
				// Check for Front End Editor
				if (function_exists('vc_is_inline')){
					if (vc_is_inline()) {
						$wall_frontend				= "true";
					} else {
						$wall_frontend				= "false";
					}
				} else {
					$wall_frontend					= "false";
				}
				
				$output 							= '';
				$wpautop 							= ($content_wpautop == "true" ? true : false);
				
				$el_class 							= str_replace(".", "", $el_class);				
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Icon_Wall_Item', $atts);
				} else {
					$css_class						= $el_class;
				}
				
				if ($wall_frontend == "false") {
					$output .= '<div id="" class="ts-icon-wall-items-content-single ' . $css_class . '" data-index="" style="display: none;">';
						if ($title != '') {
							$output .= '<div class="ts-icon-wall-items-content-title">' . $title . '</div>';
						}
						$output .= '<div class="ts-icon-wall-items-content-message">';
							if (function_exists('wpb_js_remove_wpautop')){
								$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
							} else {
								$output .= do_shortcode($content);
							}
						$output .= '</div>';
					$output .= '</div>';
				} else {
					if (($replace == "true") && (!empty($image))) {
						$item_type						= 'image';
						$item_icon						= '';
						$item_image_path 				= wp_get_attachment_image_src($image, 'large');
						$item_image_path				= "background-image: url('" . $item_image_path[0] . "');";
					} else if (isset($icon)) {
						$item_type						= 'icon';
						$item_icon						= $icon;
						$item_image_path				= '';
					} else {
						$item_type						= 'none';
						$item_icon						= '';
						$item_image_path				= '';
					}
					$output .= '<div id="" class="ts-icon-wall-items-content-single-edit" data-index="" style="display: block;">';						
						$output .= '<div class="ts-icon-wall-items-icon-single-edit">';
							if ($item_type == "icon") {
								$output .= '<i class="ts-icon-wall-icon ' . $item_icon . '"></i>';
							} else if ($item_type == "image") {
								$output .= '<i class="ts-icon-wall-image" style="' . $item_image_path . '"></i>';
							}							
						$output .= '</div>';						
						if ($title != '') {
							$output .= '<h5 class="ts-icon-wall-items-content-title">' . $title . '</h5>';
						}
						$output .= '<div class="ts-icon-wall-items-content-message">';
							if (function_exists('wpb_js_remove_wpautop')){
								$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
							} else {
								$output .= do_shortcode($content);
							}
						$output .= '</div>';
					$output .= '</div>';
				}
					
				echo $output;
				
				$myvariable = ob_get_clean();
				return $myvariable;
			}
			
			// Add Icon Wall Elements
			function TS_VCSC_Add_IconWall_Element_Container() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Icon Wall Container
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					'name'    							=> __('TS Icon Wall', 'ts_visual_composer_extend') ,		
					'base'    							=> 'TS_VCSC_Icon_Wall_Container',
					"icon"                              => "ts-composer-element-icon-icon-wall-container",
					"category"                          => __("VC Extensions", "ts_visual_composer_extend"),
					"as_parent"                         => array('only' => 'TS_VCSC_Icon_Wall_Item'),
					"description"                       => __("Build an Icon Wall Element", "ts_visual_composer_extend"),
					"controls" 							=> "full",
					"content_element"                   => true,
					"is_container" 						=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_UseExtendedNesting == "true" ? false : true),
					"container_not_allowed" 			=> false,
					"show_settings_on_create"           => true,
					"admin_enqueue_js"            		=> "",
					"admin_enqueue_css"           		=> "",
					"js_view"                           => "VcColumnView",
					'params'                  			=> array(
						// General Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_1",
							"seperator"					=> "General Settings",
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Use in RTL Layout", "ts_visual_composer_extend" ),
							"param_name"        		=> "layout_rtl",
							"value"             		=> "false",
							"description"       		=> __( "Switch the toggle if you want to use this element on a website in RTL layout.", "ts_visual_composer_extend" ),							
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Current Item", "ts_visual_composer_extend" ),
							"param_name"                => "current",
							"value"                     => "0",
							"min"                       => "0",
							"max"                       => "40",
							"step"                      => "1",
							"unit"                      => '',
							"admin_label"				=> true,
							"description"               => __( "Define the current (initial) icon wall element; use 0 (zero) for the first element.", "ts_visual_composer_extend" ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Item Spacing", "ts_visual_composer_extend" ),
							"param_name"                => "spacing",
							"value"                     => "5",
							"min"                       => "0",
							"max"                       => "20",
							"step"                      => "1",
							"unit"                      => 'px',
							"admin_label"				=> true,
							"description"               => __( "Define the spacing between each icon wall element.", "ts_visual_composer_extend" ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Large Breakpoint", "ts_visual_composer_extend" ),
							"param_name"                => "break_large",
							"value"                     => "1024",
							"min"                       => "800",
							"max"                       => "1600",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define at which size the layout should use the large icon size.", "ts_visual_composer_extend" ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Medium Breakpoint", "ts_visual_composer_extend" ),
							"param_name"                => "break_medium",
							"value"                     => "768",
							"min"                       => "480",
							"max"                       => "1024",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define at which size the layout should use the medium icon size.", "ts_visual_composer_extend" ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Small Breakpoint", "ts_visual_composer_extend" ),
							"param_name"                => "break_small",
							"value"                     => "480",
							"min"                       => "0",
							"max"                       => "480",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Define at which size the layout should use the smallest icon size.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              	    => "switch_button",
							"heading"                   => __( "Fluid Height", "ts_visual_composer_extend" ),
							"param_name"                => "fluid_height",
							"value"                     => "true",
							"admin_label"				=> true,
							"description"               => __( "Switch the toggle if you want to use a fluid height for the overall icon wall, or a fixed height based on tallest content.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              	    => "switch_button",
							"heading"                   => __( "Item Overlap", "ts_visual_composer_extend" ),
							"param_name"                => "item_overlap",
							"value"                     => "false",
							"admin_label"				=> true,
							"description"               => __( "Switch the toggle if you want to have the icon elements overlap or placed as simple grid.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              	    => "switch_button",
							"heading"                   => __( "Item Center Last", "ts_visual_composer_extend" ),
							"param_name"                => "item_centerlast",
							"value"                     => "true",
							"admin_label"				=> true,
							"description"               => __( "Switch the toggle if you want to attempt to center the items in the last row of the grid as much as possible.", "ts_visual_composer_extend" ),
						),
						// Title Font Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_2",
							"seperator"					=> "Title Font Settings",
							"group" 					=> "Style Settings",
						),
						array(
							"type"						=> "fontsmanager",
							"heading"					=> __( "Font Family", "ts_visual_composer_extend" ),
							"param_name"				=> "title_fonttype",
							"value"						=> "Default:regular",
							"default"					=> "true",
							"connector"					=> "title_fontmatch",
							"description"				=> __( "Select the default font family to be used for the section titles.", "ts_visual_composer_extend" ),
							"group"						=> "Style Settings",
						),
						array(
							"type"						=> "hidden_input",
							"param_name"				=> "title_fontmatch",
							"value"						=> "default",
							"group"						=> "Style Settings",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Font Size", "ts_visual_composer_extend" ),
							"param_name"        		=> "title_fontsize",
							"value"             		=> "32",
							"min"               		=> "14",
							"max"               		=> "64",
							"step"              		=> "1",
							"unit"              		=> 'px',
							"description"       		=> "Define the font size to be used for the section titles.",
							"group"						=> "Style Settings",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Font Style", "ts_visual_composer_extend" ),
							"param_name"        		=> "title_style",
							"width"             		=> 300,
							"value"             		=> array(
								__( 'Normal', "ts_visual_composer_extend" )      	=> "normal",
								__( 'Italic', "ts_visual_composer_extend" )       	=> "italic",			 
								__( 'Oblique', "ts_visual_composer_extend" )		=> "oblique",
							),
							"description"       		=> __( "Select the default font style for the element.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group"						=> "Style Settings",
						),	
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Text Alignment", "ts_visual_composer_extend" ),
							"param_name"        		=> "title_align",
							"width"             		=> 300,
							"value"             		=> array(
								__( 'Left', "ts_visual_composer_extend" )			=> "left",
								__( 'Right', "ts_visual_composer_extend" )			=> "right",			 
								__( 'Center', "ts_visual_composer_extend" )			=> "center",
								__( 'Justify', "ts_visual_composer_extend" )		=> "justify",
							),
							"description"       		=> __( "Select the default text alignment for the element.", "ts_visual_composer_extend" ),
							"default"					=> "center",
							"standard"					=> "center",
							"std"						=> "center",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group"						=> "Style Settings",
						),								
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Text Transform", "ts_visual_composer_extend" ),
							"param_name"        		=> "title_transform",
							"width"             		=> 300,
							"value"             		=> array(
								__( 'None', "ts_visual_composer_extend" )			=> "none",
								__( 'Capitalize', "ts_visual_composer_extend" )		=> "capitalize",			 
								__( 'Uppercase', "ts_visual_composer_extend" )		=> "uppercase",
								__( 'Lowercase', "ts_visual_composer_extend" )		=> "lowercase",
							),
							"description"       		=> __( "Select the default text transform for the element.", "ts_visual_composer_extend" ),
							"default"					=> "uppercase",
							"standard"					=> "uppercase",
							"std"						=> "uppercase",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group"						=> "Style Settings",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Text Decoration", "ts_visual_composer_extend" ),
							"param_name"        		=> "title_decoration",
							"width"             		=> 300,
							"value"             		=> array(
								__( 'None', "ts_visual_composer_extend" )       	=> "none",
								__( 'Underline', "ts_visual_composer_extend" )		=> "underline",			 
								__( 'Overline', "ts_visual_composer_extend" )		=> "overline",
								__( 'Line Through', "ts_visual_composer_extend" )	=> "line-through",
							),
							"description"       		=> __( "Select the default font decoration for the element.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group"						=> "Style Settings",
						),
						array(
							"type" 						=> "advanced_styling",
							"heading" 					=> __("Internal Paddings", "ts_visual_composer_extend"),
							"param_name" 				=> "title_padding",
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
								"Top"							=> array("string" => __("Top", "ts_visual_composer_extend"),	"width" => "0", "unitwidth" => "px", "style" => "solid", "color" => "#000000", "radius" => "0", "unitradius" => "px"),
								"Right"							=> array("string" => __("Right", "ts_visual_composer_extend"),	"width" => "0", "unitwidth" => "px", "style" => "solid", "color" => "#000000", "radius" => "0", "unitradius" => "px"),
								"Bottom"						=> array("string" => __("Bottom", "ts_visual_composer_extend"),	"width" => "0", "unitwidth" => "px", "style" => "solid", "color" => "#000000", "radius" => "0", "unitradius" => "px"),
								"Left"							=> array("string" => __("Left", "ts_visual_composer_extend"),	"width" => "0", "unitwidth" => "px", "style" => "solid", "color" => "#000000", "radius" => "0", "unitradius" => "px"),
							),
							"value"						=> "padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;",
							"description"       		=> __( "Define the internal paddings for the title.", "ts_visual_composer_extend" ),
							"group"						=> "Style Settings",
						),
						array(
							"type" 						=> "advanced_styling",
							"heading" 					=> __("Border Settings", "ts_visual_composer_extend"),
							"param_name" 				=> "title_border",
							"style_type"				=> "border",
							"show_main"					=> "false",
							"show_preview"				=> "true",
							"show_width"				=> "true",
							"show_style"				=> "true",
							"show_radius" 				=> "true",					
							"show_color"				=> "true",
							"show_unit_width"			=> "true",
							"show_unit_radius"			=> "true",
							"override_all"				=> "true",
							"default_positions"			=> array(
								"All"							=> array("string" => __("All", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#cccccc", "radius" => "0", "unitradius" => "px"),
								"Top"							=> array("string" => __("Top", "ts_visual_composer_extend"),	"width" => "0", "unitwidth" => "px", "style" => "solid", "color" => "#cccccc", "radius" => "0", "unitradius" => "px"),
								"Right"							=> array("string" => __("Right", "ts_visual_composer_extend"),	"width" => "0", "unitwidth" => "px", "style" => "solid", "color" => "#cccccc", "radius" => "0", "unitradius" => "px"),
								"Bottom"						=> array("string" => __("Bottom", "ts_visual_composer_extend"),	"width" => "0", "unitwidth" => "px", "style" => "solid", "color" => "#cccccc", "radius" => "0", "unitradius" => "px"),
								"Left"							=> array("string" => __("Left", "ts_visual_composer_extend"),	"width" => "0", "unitwidth" => "px", "style" => "solid", "color" => "#cccccc", "radius" => "0", "unitradius" => "px"),
							),
							"description"       		=> __( "Define the border settings for each side and corner of the title.", "ts_visual_composer_extend" ),
							"group"						=> "Style Settings",
						),
						// Content Font Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_3",
							"seperator"					=> "Content Font Settings",
							"group" 					=> "Style Settings",
						),
						array(
							"type"						=> "fontsmanager",
							"heading"					=> __( "Font Family", "ts_visual_composer_extend" ),
							"param_name"				=> "content_fonttype",
							"value"						=> "Default:regular",
							"default"					=> "true",
							"connector"					=> "content_fontmatch",
							"description"				=> __( "Select the default font family to be used for the section contents.", "ts_visual_composer_extend" ),
							"group"						=> "Style Settings",
						),
						array(
							"type"						=> "hidden_input",
							"param_name"				=> "content_fontmatch",
							"value"						=> "default",
							"group"						=> "Style Settings",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Font Size", "ts_visual_composer_extend" ),
							"param_name"        		=> "content_fontsize",
							"value"             		=> "14",
							"min"               		=> "10",
							"max"               		=> "24",
							"step"              		=> "1",
							"unit"              		=> 'px',
							"description"       		=> "Define the font size to be used for the section contents.",
							"group"						=> "Style Settings",
						),	
						// Other Style Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_4",
							"seperator"            		=> "Other Style Settings",
							"group" 					=> "Style Settings",
						),
						array(
							"type"              	    => "switch_button",
							"heading"                   => __( "Add Shadow", "ts_visual_composer_extend" ),
							"param_name"                => "style_shadow",
							"value"                     => "true",
							"description"               => __( "Switch the toggle if you want to apply a box shadow to each icon element.", "ts_visual_composer_extend" ),
							"admin_label"				=> true,
							"group" 					=> "Style Settings",
						),
						array(
							"type"              	    => "switch_button",
							"heading"                   => __( "Customize Elements", "ts_visual_composer_extend" ),
							"param_name"                => "style_custom",
							"value"                     => "false",
							"description"               => __( "Switch the toggle if you want to customize colors for the icon wall elements.", "ts_visual_composer_extend" ),
							"group" 					=> "Style Settings",
						),
						// Standard Colors
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_4_1",
							"seperator"            		=> "Standard Colors",
							"dependency"		    	=> array( 'element' => "style_custom", 'value' => 'true' ),
							"group" 					=> "Style Settings",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Standard Icon Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'standard_color',
							'value'						=> '#676767',
							'description' 				=> __( 'Define the standard icon color for each element.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"		    	=> array( 'element' => "style_custom", 'value' => 'true' ),
							"group" 					=> "Style Settings",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Standard Border Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'standard_border',
							'value'						=> '#cccccc',
							'description' 				=> __( 'Define the standard border color for each element.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"		    	=> array( 'element' => "style_custom", 'value' => 'true' ),
							"group" 					=> "Style Settings",
						),
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Standard Background Type", "ts_visual_composer_extend" ),
							"param_name"				=> "standard_type",
							"value"						=> array(
								__( "Single Color", "ts_visual_composer_extend" )						=> "color",
								__( "Gradient Color", "ts_visual_composer_extend" )						=> "gradient",
								__( "Background Image", "ts_visual_composer_extend" )					=> "image",
							),
							"description"				=> __( "Select the standard background type for all icon wall elements.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "style_custom", 'value' => 'true' ),
							"group" 					=> "Style Settings",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Standard Background Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'standard_back',
							'value'						=> '#ffffff',
							'description' 				=> __( 'Define the standard background color for each element.', 'ts_visual_composer_extend' ),							
							"dependency"				=> array( 'element' => "standard_type", 'value' => 'color' ),
							"group" 					=> "Style Settings",
						),
						array(
							"type"						=> "advanced_gradient",
							"heading"					=> __("Standard Gradient Background", "ts_visual_composer_extend"),						
							"param_name"				=> "standard_gradient",
							"description"				=> __('Use the controls above to create a custom gradient background for the element.', 'ts_visual_composer_extend'),
							"dependency"				=> array( 'element' => "standard_type", 'value' => 'gradient' ),
							"group" 					=> "Style Settings",
						),
						array(
							"type"                  	=> "attach_image",
							"heading"               	=> __( "Standard Background Image", "ts_visual_composer_extend" ),
							"param_name"            	=> "standard_image",
							"value"                 	=> "",
							"description"           	=> __( "Select the image you want to use as standard background.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "standard_type", 'value' => 'image' ),
							"group" 					=> "Style Settings",
						),
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Standard Background Size", "ts_visual_composer_extend" ),
							"param_name"				=> "standard_size",
							"value"						=> array(
								__( "Cover", "ts_visual_composer_extend" ) 								=> "cover",
								__( "Contain", "ts_visual_composer_extend" ) 							=> "contain",
								__( "Initial", "ts_visual_composer_extend" ) 							=> "initial",
								__( "Auto", "ts_visual_composer_extend" ) 								=> "auto",
							),
							"description"				=> __( "Select the standard background size for all icon wall elements.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"				=> array( 'element' => "standard_type", 'value' => 'image' ),
							"group" 					=> "Style Settings",
						),
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Standard Background Repeat", "ts_visual_composer_extend" ),
							"param_name"				=> "standard_repeat",
							"value"						=> array(
								__( "No Repeat", "ts_visual_composer_extend" )							=> "no-repeat",
								__( "Repeat X + Y", "ts_visual_composer_extend" )						=> "repeat",
								__( "Repeat X", "ts_visual_composer_extend" )							=> "repeat-x",
								__( "Repeat Y", "ts_visual_composer_extend" )							=> "repeat-y"
							),
							"description"				=> __( "Select if and how the standard background image for all icon wall elements should be repeated.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"				=> array( 'element' => "standard_type", 'value' => 'image' ),
							"group" 					=> "Style Settings",
						),
						// Hover Colors
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_4_2",
							"seperator"            		=> "Hover Colors",
							"dependency"		    	=> array( 'element' => "style_custom", 'value' => 'true' ),
							"group" 					=> "Style Settings",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Hover Icon Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'hover_color',
							'value'						=> '#676767',
							'description' 				=> __( 'Define the hover icon color for each element.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"		    	=> array( 'element' => "style_custom", 'value' => 'true' ),
							"group" 					=> "Style Settings",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Hover Border Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'hover_border',
							'value'						=> '#cccccc',
							'description' 				=> __( 'Define the hover border color for each element.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"		    	=> array( 'element' => "style_custom", 'value' => 'true' ),
							"group" 					=> "Style Settings",
						),
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Hover Background Type", "ts_visual_composer_extend" ),
							"param_name"				=> "hover_type",
							"value"						=> array(
								__( "Single Color", "ts_visual_composer_extend" )						=> "color",
								__( "Gradient Color", "ts_visual_composer_extend" )						=> "gradient",
								__( "Background Image", "ts_visual_composer_extend" )					=> "image",
							),
							"description"				=> __( "Select the hover background type for all icon wall elements.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "style_custom", 'value' => 'true' ),
							"group" 					=> "Style Settings",
						),	
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Hover Background Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'hover_back',
							'value'						=> '#FFD800',
							'description' 				=> __( 'Define the hover background color for each element.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "hover_type", 'value' => 'color' ),
							"group" 					=> "Style Settings",
						),
						array(
							"type"						=> "advanced_gradient",
							"heading"					=> __("Hover Gradient Background", "ts_visual_composer_extend"),						
							"param_name"				=> "hover_gradient",
							"description"				=> __('Use the controls above to create a custom gradient background for the element.', 'ts_visual_composer_extend'),
							"dependency"				=> array( 'element' => "hover_type", 'value' => 'gradient' ),
							"group" 					=> "Style Settings",
						),
						array(
							"type"                  	=> "attach_image",
							"heading"               	=> __( "Hover Background Image", "ts_visual_composer_extend" ),
							"param_name"            	=> "hover_image",
							"value"                 	=> "",
							"description"           	=> __( "Select the image you want to use as hover background.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "hover_type", 'value' => 'image' ),
							"group" 					=> "Style Settings",
						),
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Hover Background Size", "ts_visual_composer_extend" ),
							"param_name"				=> "hover_size",
							"value"						=> array(
								__( "Cover", "ts_visual_composer_extend" ) 								=> "cover",
								__( "Contain", "ts_visual_composer_extend" ) 							=> "contain",
								__( "Initial", "ts_visual_composer_extend" ) 							=> "initial",
								__( "Auto", "ts_visual_composer_extend" ) 								=> "auto",
							),
							"description"				=> __( "Select the hover background size for all icon wall elements.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"				=> array( 'element' => "hover_type", 'value' => 'image' ),
							"group" 					=> "Style Settings",
						),
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Hover Background Repeat", "ts_visual_composer_extend" ),
							"param_name"				=> "hover_repeat",
							"value"						=> array(
								__( "No Repeat", "ts_visual_composer_extend" )							=> "no-repeat",
								__( "Repeat X + Y", "ts_visual_composer_extend" )						=> "repeat",
								__( "Repeat X", "ts_visual_composer_extend" )							=> "repeat-x",
								__( "Repeat Y", "ts_visual_composer_extend" )							=> "repeat-y"
							),
							"description"				=> __( "Select if and how the hover background image for all icon wall elements should be repeated.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"				=> array( 'element' => "hover_type", 'value' => 'image' ),
							"group" 					=> "Style Settings",
						),						
						// Active Colors
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_4_3",
							"seperator"            		=> "Active Colors",
							"dependency"		    	=> array( 'element' => "style_custom", 'value' => 'true' ),
							"group" 					=> "Style Settings",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Active Icon Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'active_color',
							'value'						=> '#ffffff',
							'description' 				=> __( 'Define the active icon color for each element.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"		    	=> array( 'element' => "style_custom", 'value' => 'true' ),
							"group" 					=> "Style Settings",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Active Border Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'active_border',
							'value'						=> '#cccccc',
							'description' 				=> __( 'Define the active border color for each element.', 'ts_visual_composer_extend' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"		    	=> array( 'element' => "style_custom", 'value' => 'true' ),
							"group" 					=> "Style Settings",
						),
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Active Background Type", "ts_visual_composer_extend" ),
							"param_name"				=> "active_type",
							"value"						=> array(
								__( "Single Color", "ts_visual_composer_extend" )						=> "color",
								__( "Gradient Color", "ts_visual_composer_extend" )						=> "gradient",
								__( "Background Image", "ts_visual_composer_extend" )					=> "image",
							),
							"description"				=> __( "Select the hover background type for all icon wall elements.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "style_custom", 'value' => 'true' ),
							"group" 					=> "Style Settings",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Active Background Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'active_back',
							'value'						=> '#AE0000',
							'description' 				=> __( 'Define the active background color for each element.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "active_type", 'value' => 'color' ),
							"group" 					=> "Style Settings",
						),
						array(
							"type"						=> "advanced_gradient",
							"heading"					=> __("Active Gradient Background", "ts_visual_composer_extend"),						
							"param_name"				=> "active_gradient",
							"description"				=> __('Use the controls above to create a custom gradient background for the element.', 'ts_visual_composer_extend'),
							"dependency"				=> array( 'element' => "active_type", 'value' => 'gradient' ),
							"group" 					=> "Style Settings",
						),
						array(
							"type"                  	=> "attach_image",
							"heading"               	=> __( "Active Background Image", "ts_visual_composer_extend" ),
							"param_name"            	=> "active_image",
							"value"                 	=> "",
							"description"           	=> __( "Select the image you want to use as active background.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "active_type", 'value' => 'image' ),
							"group" 					=> "Style Settings",
						),
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Active Background Size", "ts_visual_composer_extend" ),
							"param_name"				=> "active_size",
							"value"						=> array(
								__( "Cover", "ts_visual_composer_extend" ) 								=> "cover",
								__( "Contain", "ts_visual_composer_extend" ) 							=> "contain",
								__( "Initial", "ts_visual_composer_extend" ) 							=> "initial",
								__( "Auto", "ts_visual_composer_extend" ) 								=> "auto",
							),
							"description"				=> __( "Select the active background size for all icon wall elements.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"				=> array( 'element' => "active_type", 'value' => 'image' ),
							"group" 					=> "Style Settings",
						),
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Active Background Repeat", "ts_visual_composer_extend" ),
							"param_name"				=> "active_repeat",
							"value"						=> array(
								__( "No Repeat", "ts_visual_composer_extend" )							=> "no-repeat",
								__( "Repeat X + Y", "ts_visual_composer_extend" )						=> "repeat",
								__( "Repeat X", "ts_visual_composer_extend" )							=> "repeat-x",
								__( "Repeat Y", "ts_visual_composer_extend" )							=> "repeat-y"
							),
							"description"				=> __( "Select if and how the active background image for all icon wall elements should be repeated.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"				=> array( 'element' => "active_type", 'value' => 'image' ),
							"group" 					=> "Style Settings",
						),
						// AutoPlay Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_5",
							"seperator"            		=> "Rotate Settings",
							"group" 					=> "Rotate Settings",
						),
						array(
							"type"              	    => "switch_button",
							"heading"                   => __( "Use Auto Rotation", "ts_visual_composer_extend" ),
							"param_name"                => "autoplay",
							"value"                     => "false",
							"description"               => __( "Switch the toggle if you want to apply an auto-rotation to the icon wall element.", "ts_visual_composer_extend" ),
							"admin_label"				=> true,
							"group" 					=> "Rotate Settings",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Rotation Delay", "ts_visual_composer_extend" ),
							"param_name"                => "delay",
							"value"                     => "5000",
							"min"                       => "1000",
							"max"                       => "20000",
							"step"                      => "100",
							"unit"                      => 'ms',
							"description"               => __( "Select the delay between each icon rotation.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "autoplay", 'value' => 'true' ),
							"group" 			        => "Rotate Settings",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Pause on Hover", "ts_visual_composer_extend" ),
							"param_name"		    	=> "pausehover",
							"value"                 	=> "true",
							"description"		    	=> __( "Switch the toggle if you want to pause the timer when hovering over the icon wall.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "autoplay", 'value' => 'true' ),
							"group" 					=> "Rotate Settings",
						),
						array(
							"type"                  	=> "switch_button",
							"heading"			    	=> __( "Show Progressbar", "ts_visual_composer_extend" ),
							"param_name"		    	=> "progress_bar",
							"value"                 	=> "true",
							"description"		    	=> __( "Switch the toggle if you want to show a progressbar for the delay timer.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "autoplay", 'value' => 'true' ),
							"group" 					=> "Rotate Settings",
						),
						array(
							'type' 						=> 'colorpicker',
							'heading' 					=> __( 'Progressbar Color', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'progress_color',
							'value'						=> '#f7f7f7',
							'description' 				=> __( 'Define the color for the progressbar.', 'ts_visual_composer_extend' ),
							"dependency"		    	=> array( 'element' => "progress_bar", 'value' => 'true' ),
							"group" 					=> "Rotate Settings",
						),
						array(
							"type"						=> "nouislider",
							"heading"					=> __( "Progressbar Height", "ts_visual_composer_extend" ),
							"param_name"				=> "progress_height",
							"value"						=> "2",
							"min"						=> "1",
							"max"						=> "20",
							"step"						=> "1",
							"unit"						=> 'px',
							"description"				=> __( "Define the height of the progressbar.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "progress_bar", 'value' => 'true' ),
							"group" 					=> "Rotate Settings",
						),
						// Tooltip Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_6",
							"seperator"            		=> "Tooltip Settings",
							"group" 					=> "Tooltip Settings",
						),
						array(
							"type"              	    => "switch_button",
							"heading"                   => __( "Show Tooltips", "ts_visual_composer_extend" ),
							"param_name"                => "tooltipster_allow",
							"value"                     => "true",
							"description"               => __( "Switch the toggle if you want to show the title for each segment via tooltip when hovering over the icons.", "ts_visual_composer_extend" ),
							"admin_label"				=> true,
							"group" 					=> "Tooltip Settings",
						),
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Tooltip Position", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltipster_position",
							"value"						=> array(
								__( "Top", "ts_visual_composer_extend" )                            => "top",
								__( "Bottom", "ts_visual_composer_extend" )                         => "bottom",
							),
							"description"				=> __( "Select the tooltip position in relation to the element.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "tooltipster_allow", 'value' => 'true' ),
							"group" 					=> "Tooltip Settings",
						),	
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Tooltip Style", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltipster_theme",
							"value"             		=> array(
								__( "Black", "ts_visual_composer_extend" )                          => "tooltipster-black",
								__( "Gray", "ts_visual_composer_extend" )                           => "tooltipster-gray",
								__( "Green", "ts_visual_composer_extend" )                          => "tooltipster-green",
								__( "Blue", "ts_visual_composer_extend" )                           => "tooltipster-blue",
								__( "Red", "ts_visual_composer_extend" )                            => "tooltipster-red",
								__( "Orange", "ts_visual_composer_extend" )                         => "tooltipster-orange",
								__( "Yellow", "ts_visual_composer_extend" )                         => "tooltipster-yellow",
								__( "Purple", "ts_visual_composer_extend" )                         => "tooltipster-purple",
								__( "Pink", "ts_visual_composer_extend" )                           => "tooltipster-pink",
								__( "White", "ts_visual_composer_extend" )                          => "tooltipster-white"
							),
							"description"				=> __( "Select the tooltip style.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "tooltipster_allow", 'value' => 'true' ),
							"group" 					=> "Tooltip Settings",
						),							
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Tooltip Animation", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltipster_animation",
							"value"             		=> array(
								__( "Fade", "ts_visual_composer_extend" )                          => "fade",
								__( "Grow", "ts_visual_composer_extend" )                          => "grow",
								__( "Swing", "ts_visual_composer_extend" )                         => "swing",
								__( "Slide", "ts_visual_composer_extend" )                         => "slide",
								__( "Fall", "ts_visual_composer_extend" )                          => "fall",
							),
							"description"				=> __( "Select the tooltip entry/exit animation.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "tooltipster_allow", 'value' => 'true' ),
							"group" 					=> "Tooltip Settings",
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
							"dependency"				=> array( 'element' => "tooltipster_allow", 'value' => 'true' ),
							"group" 					=> "Tooltip Settings",
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
							"dependency"				=> array( 'element' => "tooltipster_allow", 'value' => 'true' ),
							"group" 					=> "Tooltip Settings",
						),		
						// Other Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_7",
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
			function TS_VCSC_Add_IconWall_Element_Item() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Icon Wall Item
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					'name' 								=> __('TS Icon Wall Item', 'ts_visual_composer_extend'),
					'base' 								=> 'TS_VCSC_Icon_Wall_Item',
					"icon" 	                    		=> "ts-composer-element-icon-icon-wall-item",
					"content_element"					=> true,
					"controls"							=> "full",						
					'is_container' 						=> false,
					"as_child" 							=> array('only' => 'TS_VCSC_Icon_Wall_Container'),
					'params' 							=> array(
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_1",
							"seperator"             	=> "General Settings",
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Use Normal Image", "ts_visual_composer_extend" ),
							"param_name"        		=> "replace",
							"value"             		=> "false",
							"admin_label"       		=> true,
							"description"       		=> __( "Switch the toggle to either use an icon or a normal image.", "ts_visual_composer_extend" )
						),
						array(
							"type" 						=> "icons_panel",
							'heading' 					=> __( 'Icon', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'icon',
							'value'						=> '',
							"settings" 					=> array(
								"emptyIcon" 					=> false,
								'emptyIconValue'				=> 'transparent',
								"type" 							=> 'extensions',
							),
							"admin_label"				=> true,
							"description"       		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorVisualSelector == "true" ? __( "Select the icon for the tab.", "ts_visual_composer_extend" ) : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconSelectorString),
							"dependency"        		=> array( 'element' => "replace", 'value' => 'false' )
						),						
						array(
							"type"              		=> "attach_image",
							"heading"           		=> __( "Select Image", "ts_visual_composer_extend" ),
							"param_name"        		=> "image",
							"value"             		=> "",
							"admin_label"       		=> true,
							"description"       		=> __( "Image must have equal dimensions for scaling purposes (i.e. 100x100).", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "replace", 'value' => 'true' )
						),
						array(
							'type' 						=> 'dropdown',
							'heading' 					=> __( 'Animation Type', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'animation_type',
							'value' => array(
								__('No Animation', 'ts_visual_composer_extend') 	=> 'none',
								__('Hover', 'ts_visual_composer_extend') 			=> 'hover',
								__('Infinite', 'ts_visual_composer_extend')			=> 'infinite',
							),
							"admin_label"				=> true,
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
							"dependency"				=> array( 'element' => "animation_type", 'value' => array('hover', 'infinite') ),
							"description"				=> __("Select the hover animation for the icon.", "ts_visual_composer_extend"),
						),
						array(
							"type"						=> "hidden_input",
							"heading"					=> __( "Icon Animation", "ts_visual_composer_extend" ),
							"param_name"				=> "css3animations_in",
							"value"						=> "",
						),
						array (
							'type' 						=> 'textfield',
							'heading' 					=> __( 'Title', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'title',
							"admin_label"				=> true,
							'description' 				=> __( 'Provide a title or name for this icon wall element.', 'ts_visual_composer_extend' )
						),
						array(
							"type"						=> "textarea_html",
							"heading"					=> __( "Content", "ts_visual_composer_extend" ),
							"param_name"				=> "content",
							"value"						=> "",
							"description"				=> __( "Create the content for this icon wall element.", "ts_visual_composer_extend" ),
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
	if ((class_exists('WPBakeryShortCodesContainer')) && (!class_exists('WPBakeryShortCode_TS_VCSC_Icon_Wall_Container'))) {
		class WPBakeryShortCode_TS_VCSC_Icon_Wall_Container extends WPBakeryShortCodesContainer {};
	}
	if ((class_exists('WPBakeryShortCode')) && (!class_exists('WPBakeryShortCode_TS_VCSC_Icon_Wall_Item'))) {
		class WPBakeryShortCode_TS_VCSC_Icon_Wall_Item extends WPBakeryShortCode {};
	}
	// Initialize "TS Icon Wall" Class
	if (class_exists('TS_Icon_Wall')) {
		$TS_Icon_Wall = new TS_Icon_Wall;
	}
?>