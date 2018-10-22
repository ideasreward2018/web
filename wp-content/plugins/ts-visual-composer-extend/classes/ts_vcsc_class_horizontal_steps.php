<?php
	if (!class_exists('TS_Horizontal_Steps')){
		class TS_Horizontal_Steps{
			function __construct(){
				global $VISUAL_COMPOSER_EXTENSIONS;
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						$this->TS_VCSC_Add_Horizontal_Steps_Elements_Lean();
					} else if (function_exists('vc_map')) {
						add_action('init',									array($this, 'TS_VCSC_Add_Horizontal_Steps_Element_Container'), 9999999);
						add_action('init',									array($this, 'TS_VCSC_Add_Horizontal_Steps_Element_Item'), 9999999);
					}
				} else {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_Horizontal_Steps_Elements_Lean'), 9999999);
					} else if (function_exists('vc_map')) {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_Horizontal_Steps_Element_Container'), 9999999);
						add_action('admin_init',							array($this, 'TS_VCSC_Add_Horizontal_Steps_Element_Item'), 9999999);
					}
				}
				if ((is_admin() == false) || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAJAX == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAlways == "true")) {
					add_shortcode('TS_VCSC_Horizontal_Steps_Container',		array($this, 'TS_VCSC_Horizontal_Steps_Container'));
					add_shortcode('TS_VCSC_Horizontal_Steps_Item',			array($this, 'TS_VCSC_Horizontal_Steps_Item'));
				}
			}
			
			// Register Element(s) via LeanMap
			function TS_VCSC_Add_Horizontal_Steps_Elements_Lean() {
				vc_lean_map('TS_VCSC_Horizontal_Steps_Container', 			array($this, 'TS_VCSC_Add_Horizontal_Steps_Element_Container'), null);
				vc_lean_map('TS_VCSC_Horizontal_Steps_Item', 				array($this, 'TS_VCSC_Add_Horizontal_Steps_Element_Item'), null);
			}
			
			// Horizontal Steps - Container
			function TS_VCSC_Horizontal_Steps_Container($atts, $content = null){
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();
	
				wp_enqueue_style('dashicons');
				wp_enqueue_style('ts-extend-animations');
				wp_enqueue_style('ts-extend-buttonsdual');
				wp_enqueue_style('ts-visual-composer-extend-front');
				wp_enqueue_script('ts-visual-composer-extend-front');
				
				extract(shortcode_atts(array(
					'layout_rtl'					=> 'false',
					'min_width'						=> 250,
					'max_width'						=> 500,
					'step_inner'					=> 80,
					'icon_size'						=> 75,
					'icon_limit'					=> 'true',
					'icon_max'						=> 400,				
					'animation_view' 				=> '',
					'animation_delay' 				=> 0,
					'animation_steps' 				=> 500,
					'animation_mobile'				=> 'false',
					'margin_bottom'					=> 0,
					'margin_top' 					=> 0,
					'el_id' 						=> '',
					'el_class'                  	=> '',
					'css'							=> '',
				),$atts));
				
				if (($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndWaypoints == "true") && ($animation_view != "") && ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "false")) {
					if (wp_script_is('waypoints', $list = 'registered')) {
						wp_enqueue_script('waypoints');
					} else {
						wp_enqueue_script('ts-extend-waypoints');
					}
				}
				
				if (!empty($el_id)) {
					$steps_id						= $el_id;
				} else {
					$steps_id						= 'ts-vcsc-horizontal-steps-container-' . mt_rand(999999, 9999999);
				}
				
				if ($max_width < $min_width) {
					$max_width						= $min_width;
				}
				
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					$frontend 						= "true";
					$margin_top						= 40;
				} else {
					$frontend 						= "false";
					$margin_top						= $margin_top;
				}
				
				$output 							= '';
				
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-horizontal-steps ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Horizontal_Steps_Container', $atts);
				} else {
					$css_class 						= 'ts-horizontal-steps ' . $el_class;
				}
				
				if (($animation_view != "") && ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "false")) {
					$animation_class				= 'ts-horizontal-steps-viewport';
					$animation_data					= 'data-animation="' . $animation_view . '" data-mobile="' . $animation_mobile . '" data-delay="' . $animation_delay . '" data-break="' . $animation_steps . '"';
				} else {
					$animation_class				= '';
					$animation_data					= '';
				}
				
				$output .= '<div id="' . $steps_id . '" class="' . $css_class . ' ' . $animation_class . ' ' . ($layout_rtl == "true" ? "ts-horizontal-steps-rtl" : "ts-horizontal-steps-ltr") . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;" ' . $animation_data . ' data-layoutrtl="' . $layout_rtl . '" data-lastwidth="0" data-frontend="' . $frontend . '" data-minwidth="' . $min_width . '" data-maxwidth="' . $max_width . '" data-stepinner="' . $step_inner . '" data-iconsize="' . $icon_size . '" data-iconlimit="' . $icon_limit . '" data-iconmax="' . $icon_max . '">';
					$output .= '<ul class="ts-horizontal-steps-list">';
						$output .= do_shortcode($content);
					$output .= '<div class="clearboth"></div>';
					$output .= '</ul>';				
				$output .= '</div>';
				
				echo $output;
				
				$myvariable = ob_get_clean();
				return $myvariable;
			}
			
			// Horizontal Steps - Single Step
			function TS_VCSC_Horizontal_Steps_Item($atts, $content = null){
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();
				
				extract(shortcode_atts(array(
					'step_radius'					=> 'ts-radius-full',
					'step_replace'					=> 'false',
					'step_icon'						=> '',
					'step_image'					=> '',
					'step_size'						=> 'medium',
					'step_link'						=> '',
					'step_title'					=> '',
					'step_animation'				=> 'ts-hover-css-none',
					'content_html'					=> 'false',
					'content_wpautop'				=> 'false',
					'step_content'					=> '',
					
					'button_show'					=> 'false',
					'button_text'					=> 'Read More',
					'button_style'					=> 'ts-dual-buttons-color-default',
					'button_hover'					=> 'ts-dual-buttons-hover-default',
					
					'title_color'					=> '4e4e4e',
					'content_color'					=> '6C6C6C',
					'content_align'					=> 'center',
					'content_size'					=> 14,
					
					'icon_color_default'			=> '#cccccc',
					'icon_color_hover'				=> '#ffffff',
					
					'icon_back_type'				=> 'iconback',
					'icon_back_switch'				=> 'false',
					'icon_back_image'				=> '',
					'icon_back_size'				=> 'medium',
					'icon_back_scale'				=> 'cover',
					'icon_back_position'			=> 'center center',
					'icon_back_repeat'				=> 'no-repeat',
					'icon_back_default'				=> '#ffffff',
					'icon_back_hover'				=> '#ededed',
					'icon_back_gradient'			=> '',
					
					'header_default_color'			=> '#ededed',
					'header_default_size'			=> 2,
					'header_hover_color'			=> 'rgba(0, 0, 0, 0.25)',
					'header_hover_size'				=> 6,
					
					'tooltip_content'				=> '',
					'tooltip_position'				=> 'ts-simptip-position-top',
					'tooltip_style'					=> 'ts-simptip-style-black',
					'tooltip_animation'				=> 'swing',
					'tooltipster_offsetx'			=> 0,
					'tooltipster_offsety'			=> 0,
	
					'el_id' 						=> '',
					'el_class'                  	=> '',
					'css'							=> '',
				),$atts));
				
				$output								= '';
				$style								= '';
				$wpautop 							= ($content_wpautop == "true" ? true : false);
				$inline								= TS_VCSC_FrontendAppendCustomRules('style');
				
				if (!empty($el_id)) {
					$steps_id						= $el_id;
				} else {
					$steps_id						= 'ts-vcsc-horizontal-steps-single-' . mt_rand(999999, 9999999);
				}
				
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					$frontend 						= "true";
				} else {
					$frontend 						= "false";
				}
				
				// Link Values
				$step_link 						= TS_VCSC_Advancedlinks_GetLinkData($step_link);
				$a_href							= $step_link['url'];
				$a_title 						= $step_link['title'];
				$a_target 						= $step_link['target'];
				$a_rel 							= $step_link['rel'];
				if (!empty($a_rel)) {
					$a_rel 						= 'rel="' . esc_attr(trim($a_rel)) . '"';
				}
				
				if (($step_replace == "true") && (!empty($step_image))) {
					$step_image_path 			= wp_get_attachment_image_src($step_image, $step_size);
				}
				if (($icon_back_switch == "image") && (!empty($icon_back_image))) {
					$step_back_path 			= wp_get_attachment_image_src($icon_back_image, $icon_back_size);
				}
				
				// Custom Styling
				if ($inline == "false") {
					$style .= '<style id="' . $steps_id . '-style" type="text/css">';
				}
					$shadow_default				= "-webkit-box-shadow: 0 0 0 2px " . $header_default_color . "; -moz-box-shadow: 0 0 0 2px " . $header_default_color . "; box-shadow: 0 0 0 2px " . $header_default_color . ";";
					$shadow_hover				= "-webkit-box-shadow: 0 0 0 6px " . $header_hover_color . "; -moz-box-shadow: 0 0 0 6px " . $header_hover_color . "; box-shadow: 0 0 0 6px " . $header_hover_color . ";";
					if (($icon_back_switch == "single") || ($icon_back_switch == "false")) {
						$style .= 'body #' . $steps_id . '.ts-horizontal-steps-item .ts-horizontal-step-icon {';
							$style .= 'background: ' . $icon_back_default . ';';
							$style .= $shadow_default;
						$style .= '}';
						$style .= 'body #' . $steps_id . '.ts-horizontal-steps-item:hover .ts-horizontal-step-icon {';
							$style .= 'background: ' . $icon_back_hover . ';';
							$style .= $shadow_default;
						$style .= '}';
					} else if (($icon_back_switch == "image") || ($icon_back_switch == "true")) {
						$style .= 'body #' . $steps_id . '.ts-horizontal-steps-item .ts-horizontal-step-icon {';
							$style .= 'background-image: url("' . $step_back_path[0] . '");';
							$style .= 'background-size: ' . $icon_back_scale .';';
							$style .= 'background-position: ' . $icon_back_position . ';';
							$style .= 'background-repeat: ' . $icon_back_repeat . ';';
							$style .= $shadow_default;
						$style .= '}';
					} else if ($icon_back_switch == "gradient") {
						$style .= 'body #' . $steps_id . '.ts-horizontal-steps-item .ts-horizontal-step-icon {';
							$style .= $icon_back_gradient;
							$style .= $shadow_default;
						$style .= '}';
					}
					$style .= 'body #' . $steps_id . '.ts-horizontal-steps-item:hover .ts-horizontal-step-icon {';
						$style .= $shadow_hover;
					$style .= '}';
					if ($step_replace == "false") {
						$style .= 'body #' . $steps_id . '.ts-horizontal-steps-item .ts-horizontal-step-icon i {';
							$style .= 'color: ' . $icon_color_default . ';';
						$style .= '}';
						$style .= 'body #' . $steps_id . '.ts-horizontal-steps-item:hover .ts-horizontal-step-icon i, ';
						$style .= 'body #' . $steps_id . '.ts-horizontal-steps-item:hover .ts-horizontal-step-icon:before {';
							$style .= 'color: ' . $icon_color_hover . ';';
						$style .= '}';
					}
				if ($inline == "false") {
					$style .= '</style>';
				}
				if (($style != "") && ($inline == "true")) {
					wp_add_inline_style('ts-visual-composer-extend-custom', TS_VCSC_MinifyCSS($style));
				}
				
				// Tooltip
				$tooltipclasses					= 'ts-has-tooltipster-tooltip';
				$tooltip_position				= TS_VCSC_TooltipMigratePosition($tooltip_position);
				$tooltip_style					= TS_VCSC_TooltipMigrateStyle($tooltip_style);
				if (strip_tags($tooltip_content) != '') {
					wp_enqueue_style('ts-extend-tooltipster');
					wp_enqueue_script('ts-extend-tooltipster');	
					$Tooltip_Content			= 'data-tooltipster-html="true" data-tooltipster-title="" data-tooltipster-text="' . strip_tags($tooltip_content) . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="' . $tooltip_animation . '" data-tooltipster-trigger="hover" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . $tooltipster_offsety . '"';
					$Tooltip_Class				= 'ts-has-tooltipster-tooltip';
				} else {
					$Tooltip_Content			= '';
					$Tooltip_Class				= '';
				}
				
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 					= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-horizontal-steps-item ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Horizontal_Steps_Item', $atts);
				} else {
					$css_class 					= 'ts-horizontal-steps-item ' . $el_class;
				}
				
				$output .= '<li id="' . $steps_id . '" class="' . $css_class . ' ts-box-icon ' . ($frontend == "true" ? "ts-horizontal-steps-break" : "") . ' ' . $Tooltip_Class . '" ' . $Tooltip_Content . ' data-frontend="' . $frontend . '" style="' . ($frontend == "true" ? "float: none;" : "") . '">';
					if ($inline == "false") {
						$output .= TS_VCSC_MinifyCSS($style);
					}
					if (($icon_back_type == "iconback") || ($icon_back_type == "icononly")) {
						$icon_class				= $step_animation;
					} else {
						$icon_class				= 'ts-horizontal-step-hidden';
					}
					if ($a_href != '') {
						if ($step_replace == "false") {
							$output .= '<a href="' . $a_href . '" target="' . $a_target . '" ' . $a_rel . ' title="' . $a_title . '"><span class="ts-horizontal-step-icon ' . $step_radius . '"><i class="' . $step_icon . ' ' . $icon_class . '"></i></span></a>';
						} else {
							$output .= '<a href="' . $a_href . '" target="' . $a_target . '" ' . $a_rel . ' title="' . $a_title . '"><span class="ts-horizontal-step-icon ' . $step_radius . '"><img class="' . $icon_class . '" src="' . $step_image_path[0] . '"></span></a>';
						}
					} else {
						if ($step_replace == "false") {
							$output .= '<span class="ts-horizontal-step-icon ' . $step_radius . '"><i class="' . $step_icon . ' ' . $icon_class . '"></i></span>';
						} else {
							$output .= '<span class="ts-horizontal-step-icon ' . $step_radius . '"><img class="' . $icon_class . '" src="' . $step_image_path[0] . '"></span>';
						}
					}
					$output .= '<div class="ts-horizontal-step-content">';
						if ($a_href != '') {
							$output .= '<h3 class="ts-horizontal-step-title" style="color: ' . $title_color . ';"><a href="' . $a_href . '" target="' . $a_target . '" title="' . $a_title . '">' . $step_title . '</a></h3>';
						} else {
							$output .= '<h3 class="ts-horizontal-step-title" style="color: ' . $title_color . ';">' . $step_title . '</h3>';
						}
						$output .= '<div class="clearboth"></div>';
						if ($content_html == "true") {
							if ($content != '') {
								if (function_exists('wpb_js_remove_wpautop')){
									$output .= '<div class="ts-horizontal-step-description" style="color: ' . $content_color . '; text-align: ' . $content_align . '; font-size: ' . $content_size . 'px;">' . wpb_js_remove_wpautop(do_shortcode($content), $wpautop). '</div>';
								} else {
									$output .= '<div class="ts-horizontal-step-description" style="color: ' . $content_color . '; text-align: ' . $content_align . '; font-size: ' . $content_size . 'px;">' . do_shortcode($content) . '</div>';
								}
							}
						} else {
							if ($step_content != '') {
								$output .= '<div class="ts-horizontal-step-description" style="color: ' . $content_color . '; text-align: ' . $content_align . '; font-size: ' . $content_size . 'px;">' . rawurldecode(base64_decode(strip_tags($step_content))) . '</div>';
							}
						}
						if (($button_show == "true") && ($a_href != '')) {
							$output .= '<a class="ts-readmore ' . $button_style . ' ' . $button_hover . '" href="' . $a_href . '" target="' . $a_target . '" title="' . $a_title . '" style="margin: 0 auto; font-size: 14px; padding: 10px 5px;"><span>' . $button_text . '</span></a>';
						}
					$output .= '</div>';
				$output .= '</li>';
				
				echo $output;
				
				$myvariable = ob_get_clean();
				return $myvariable;
			}
			
			// Add Horizontal Steps Elements
			function TS_VCSC_Add_Horizontal_Steps_Element_Container() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Steps Container
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					"name" 								=> __("TS Horizontal Steps", "ts_visual_composer_extend"),
					"base" 								=> "TS_VCSC_Horizontal_Steps_Container",
					"icon" 								=> "ts-composer-element-icon-horizontal-steps-main",
					"as_parent" 						=> array('only' => 'TS_VCSC_Horizontal_Steps_Item'),
					"category" 							=> "VC Extensions",
					"description" 						=> "Place a horizontal steps element.",
					"controls" 							=> "full",
					"content_element"                   => true,
					"is_container" 						=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_UseExtendedNesting == "true" ? false : true),
					"container_not_allowed" 			=> false,
					"show_settings_on_create"           => true,
					"admin_enqueue_js"            		=> "",
					"admin_enqueue_css"           		=> "",
					"js_view" 							=> "VcColumnView",
					"params" 							=> array(
						// Step Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_1",
							"seperator"					=> "Step Settings",
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
							"heading"                   => __( "Min. Width per Step", "ts_visual_composer_extend" ),
							"param_name"                => "min_width",
							"value"                     => "250",
							"min"                       => "100",
							"max"                       => "500",
							"step"                      => "1",
							"unit"                      => 'px',
							"admin_label"           	=> true,
							"description"               => __( "Define the minimum outer width for each individal step.", "ts_visual_composer_extend" )
						),						
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Max. Width per Step", "ts_visual_composer_extend" ),
							"param_name"                => "max_width",
							"value"                     => "500",
							"min"                       => "100",
							"max"                       => "1024",
							"step"                      => "1",
							"unit"                      => 'px',
							"admin_label"           	=> true,
							"description"               => __( "Define the maximum outer width for each individal step.", "ts_visual_composer_extend" )
						),						
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Inner Step Width", "ts_visual_composer_extend" ),
							"param_name"                => "step_inner",
							"value"                     => "80",
							"min"                       => "50",
							"max"                       => "100",
							"step"                      => "1",
							"unit"                      => '%',
							"admin_label"           	=> true,
							"description"               => __( "Define the width in percent (based on outer width) the step content should utilize.", "ts_visual_composer_extend" )
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Default Icon Height", "ts_visual_composer_extend" ),
							"param_name"                => "icon_size",
							"value"                     => "75",
							"min"                       => "50",
							"max"                       => "100",
							"step"                      => "1",
							"unit"                      => '%',
							"admin_label"           	=> true,
							"description"               => __( "Define the height of the icon (in percent), based on inner step width.", "ts_visual_composer_extend" )
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Apply Maximum Height", "ts_visual_composer_extend" ),
							"param_name"        		=> "icon_limit",
							"value"             		=> "true",
							"description"       		=> __( "Switch the toggle if you want to apply a maximum height for the icon in each step.", "ts_visual_composer_extend" ),							
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Max. Icon Size", "ts_visual_composer_extend" ),
							"param_name"                => "icon_max",
							"value"                     => "100",
							"min"                       => "75",
							"max"                       => "400",
							"step"                      => "1",
							"unit"                      => 'px',
							"admin_label"           	=> true,
							"dependency"        		=> array( 'element' => "icon_limit", 'value' => 'true' ),
							"description"               => __( "Define the maximum height for the icon in each step; will overrule default icon size.", "ts_visual_composer_extend" )
						),
						// Animation Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_2",
							"seperator"					=> "Animation Settings",
							"group" 			        => "Animation Settings",
						),
						array(
							"type"						=> "css3animations",
							"heading"					=> __("Viewport Animation", "ts_visual_composer_extend"),
							"param_name"				=> "animation_view",
							"prefix"					=> "ts-viewport-css-",
							"connector"					=> "css3animations_view",
							"noneselect"				=> "true",
							"default"					=> "",
							"value"						=> "",
							"admin_label"				=> false,
							"description"				=> __("Select the viewport animation for the icon / image.", "ts_visual_composer_extend"),
							"group" 					=> "Animation Settings",
						),
						array(
							"type"						=> "hidden_input",
							"heading"					=> __( "Viewport Animation", "ts_visual_composer_extend" ),
							"param_name"				=> "css3animations_view",
							"value"						=> "",
							"admin_label"				=> true,
							"group" 					=> "Animation Settings",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Animation Initial Delay", "ts_visual_composer_extend" ),
							"param_name"       		 	=> "animation_delay",
							"value"             		=> "0",
							"min"               		=> "0",
							"max"               		=> "5000",
							"step"              		=> "100",
							"unit"              		=> 'ms',
							"description"       		=> __( "Define an optional initial delay for the viewport animation.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "animation_view", 'not_empty' => true ),
							"group" 					=> "Animation Settings",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Animation Steps Delay", "ts_visual_composer_extend" ),
							"param_name"       		 	=> "animation_steps",
							"value"             		=> "500",
							"min"               		=> "200",
							"max"               		=> "2000",
							"step"              		=> "100",
							"unit"              		=> 'ms',
							"description"       		=> __( "Define an delay for the viewport animation between each step.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "animation_view", 'not_empty' => true ),
							"group" 					=> "Animation Settings",
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Allow on Mobile", "ts_visual_composer_extend" ),
							"param_name"        		=> "animation_mobile",
							"value"             		=> "false",
							"description"       		=> __( "Switch the toggle to allow the viewport animation to be used on mobile devices.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "animation_view", 'not_empty' => true ),
							"group" 					=> "Animation Settings",
						),		
						// Other Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_3",
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
			function TS_VCSC_Add_Horizontal_Steps_Element_Item() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Single Step Item
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					"name"                      		=> __( "TS Horizontal Steps Item", "ts_visual_composer_extend" ),
					"base"                      		=> "TS_VCSC_Horizontal_Steps_Item",
					"icon" 	                    		=> "ts-composer-element-icon-horizontal-steps-item",
					"content_element"					=> true,
					"as_child"							=> array('only' => 'TS_VCSC_Horizontal_Steps_Container'),
					"category"                  		=> __( 'VC Extensions', "ts_visual_composer_extend" ),
					"description"               		=> __("Place a single step item", "ts_visual_composer_extend"),
					"admin_enqueue_js"            		=> "",
					"admin_enqueue_css"           		=> "",
					"params"                    		=> array(
						// Styling Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_1",
							"seperator"					=> "Step Header",
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Header Styling", "ts_visual_composer_extend" ),
							"param_name"            	=> "icon_back_type",
							"width"                 	=> 150,
							"value"                 	=> array(
								__( 'Icon + Background', "ts_visual_composer_extend" )			=> "iconback",
								__( 'Background Only', "ts_visual_composer_extend" )			=> "backonly",
								__( 'Icon Only', "ts_visual_composer_extend" )					=> "icononly",
							),
							"description"           	=> __( "Select how you want to style the step header section.", "ts_visual_composer_extend" ),							
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Header Radius", "ts_visual_composer_extend" ),
							"param_name"            	=> "step_radius",
							"width"                 	=> 150,
							"value"                 	=> array(
								__( "None", "ts_visual_composer_extend" )                          => "ts-radius-none",
								__( "Small Radius", "ts_visual_composer_extend" )                  => "ts-radius-small",
								__( "Medium Radius", "ts_visual_composer_extend" )                 => "ts-radius-medium",
								__( "Large Radius", "ts_visual_composer_extend" )                  => "ts-radius-large",
								__( "Full Circle", "ts_visual_composer_extend" )                   => "ts-radius-full"
							),
							"default"					=> "radius-full",
							"standard"					=> "radius-full",
							"std"						=> "radius-full",
							"description"           	=> __( "Define the radius of the header section.", "ts_visual_composer_extend" ),							
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Header Shadow Default Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "header_default_color",
							"value"             		=> "#ededed",
							"description"       		=> __( "Define the default shadow color for the step header.", "ts_visual_composer_extend" ),
						),
						array(
							"type"						=> "nouislider",
							"heading"					=> __( "Header Shadow Default Size", "ts_visual_composer_extend" ),
							"param_name"				=> "header_default_sizer",
							"value"						=> "2",
							"min"						=> "0",
							"max"						=> "20",
							"step"						=> "1",
							"unit"						=> 'px',
							"description"				=> __( "Define the standard shadow size for the step header.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Header Shadow Hover Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "header_hover_color",
							"value"             		=> "rgba(0, 0, 0, 0.25)",
							"description"       		=> __( "Define the hover shadow color for the step header.", "ts_visual_composer_extend" ),
						),
						array(
							"type"						=> "nouislider",
							"heading"					=> __( "Header Shadow Hover Size", "ts_visual_composer_extend" ),
							"param_name"				=> "header_hover_sizer",
							"value"						=> "6",
							"min"						=> "0",
							"max"						=> "20",
							"step"						=> "1",
							"unit"						=> 'px',
							"description"				=> __( "Define the hover shadow size for the step header.", "ts_visual_composer_extend" ),
						),
						// Step Icon/Image
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_2",
							"seperator"					=> "Header Icon",
							"dependency"        		=> array( 'element' => "icon_back_type", 'value' => array('iconback', 'icononly') ),
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Use Normal Image", "ts_visual_composer_extend" ),
							"param_name"        		=> "step_replace",
							"value"             		=> "false",
							"description"       		=> __( "Switch the toggle to either use an icon or a normal image.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "icon_back_type", 'value' => array('iconback', 'icononly') ),
						),
						array(
							"type" 						=> "icons_panel",
							'heading' 					=> __( 'Step Icon', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'step_icon',
							'value'						=> '',
							"settings" 					=> array(
								"emptyIcon" 					=> false,
								'emptyIconValue'				=> 'transparent',
								"type" 							=> 'extensions',
							),
							"description"       		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorVisualSelector == "true" ? __( "Select the icon you want to display in the handle instead of the automatic numbering.", "ts_visual_composer_extend" ) : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconSelectorString),
							"dependency"        		=> array( 'element' => "step_replace", 'value' => 'false' ),
						),
						array(
							"type"              		=> "attach_image",
							"heading"           		=> __( "Select Image", "ts_visual_composer_extend" ),
							"param_name"        		=> "step_image",
							"value"             		=> "",
							"description"       		=> __( "Image must have equal dimensions for scaling purposes (i.e. 100x100).", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "step_replace", 'value' => 'true' ),
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Default Icon Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "icon_color_default",
							"value"             		=> "#cccccc",
							"description"       		=> __( "Define the default color for the step icon.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",	
							"dependency"        		=> array( 'element' => "step_replace", 'value' => 'false' ),
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Hover Icon Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "icon_color_hover",
							"value"             		=> "#ffffff",
							"description"       		=> __( "Define the hover color for the step icon.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",	
							"dependency"        		=> array( 'element' => "step_replace", 'value' => 'false' ),
						),
						// Step Background
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_3",
							"seperator"					=> "Header Background",
							"dependency"        		=> array( 'element' => "icon_back_type", 'value' => array('iconback', 'backonly') ),
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Background Type", "ts_visual_composer_extend" ),
							"param_name"            	=> "icon_back_switch",
							"width"                 	=> 150,
							"value"                 	=> array(
								__( 'Single Color', "ts_visual_composer_extend" )				=> "single",
								__( 'Gradient Color', "ts_visual_composer_extend" )				=> "gradient",
								__( 'Background Image', "ts_visual_composer_extend" )			=> "image",
							),
							"description"           	=> __( "Select which background type you want to use for the step header.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "icon_back_type", 'value' => array('iconback', 'backonly') ),
						),
						array(
							"type"              		=> "attach_image",
							"heading"           		=> __( "Select Image", "ts_visual_composer_extend" ),
							"param_name"        		=> "icon_back_image",
							"value"             		=> "",
							"description"       		=> __( "Image must have equal dimensions for scaling purposes (i.e. 100x100).", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "icon_back_switch", 'value' => 'image' ),
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Image Source", "ts_visual_composer_extend" ),
							"param_name"            	=> "icon_back_size",
							"width"                 	=> 150,
							"value"                 	=> array(
								__( 'Medium Size Image', "ts_visual_composer_extend" )			=> "medium",
								__( 'Thumbnail Size Image', "ts_visual_composer_extend" )		=> "thumbnail",
								__( 'Large Size Image', "ts_visual_composer_extend" )			=> "large",
								__( 'Full Size Image', "ts_visual_composer_extend" )			=> "full",
							),
							"description"           	=> __( "Select which image size based on WordPress settings should be used for the background image.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "icon_back_switch", 'value' => 'image' ),
						),
						array(
							"type" 						=> "dropdown",
							"heading" 					=> __( "Background Size", "ts_visual_composer_extend" ),
							"param_name" 				=> "icon_back_scale",
							"value" 					=> array(
								__( "Cover", "ts_visual_composer_extend" ) 			=> "cover",
								__( "100%", "ts_visual_composer_extend" )			=> "100%",
								__( "110%", "ts_visual_composer_extend" )			=> "110%",
								__( "120%", "ts_visual_composer_extend" )			=> "120%",
								__( "130%", "ts_visual_composer_extend" )			=> "130%",
								__( "140%", "ts_visual_composer_extend" )			=> "140%",
								__( "150%", "ts_visual_composer_extend" )			=> "150%",
								__( "160%", "ts_visual_composer_extend" )			=> "160%",
								__( "170%", "ts_visual_composer_extend" )			=> "170%",
								__( "180%", "ts_visual_composer_extend" )			=> "180%",
								__( "190%", "ts_visual_composer_extend" )			=> "190%",
								__( "200%", "ts_visual_composer_extend" )			=> "200%",
								__( "Contain", "ts_visual_composer_extend" ) 		=> "contain",
								__( "Initial", "ts_visual_composer_extend" ) 		=> "initial",
								__( "Auto", "ts_visual_composer_extend" ) 			=> "auto",
							),
							"dependency"        		=> array( 'element' => "icon_back_switch", 'value' => 'image' ),
						),
						array(
							"type" 						=> "dropdown",
							"heading" 					=> __( "Background Position", "ts_visual_composer_extend" ),
							"param_name" 				=> "icon_back_position",
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
							"dependency"        		=> array( 'element' => "icon_back_switch", 'value' => 'image' ),
						),
						array(
							"type" 						=> "dropdown",
							"heading" 					=> __( "Background Repeat", "ts_visual_composer_extend" ),
							"param_name" 				=> "icon_back_repeat",
							"value" 					=> array(
								__( "No Repeat", "ts_visual_composer_extend" )		=> "no-repeat",
								__( "Repeat X + Y", "ts_visual_composer_extend" )	=> "repeat",
								__( "Repeat X", "ts_visual_composer_extend" )		=> "repeat-x",
								__( "Repeat Y", "ts_visual_composer_extend" )		=> "repeat-y"
							),
							"dependency"        		=> array( 'element' => "icon_back_switch", 'value' => 'image' ),
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Default Icon Background", "ts_visual_composer_extend" ),
							"param_name"        		=> "icon_back_default",
							"value"             		=> "#ffffff",
							"description"       		=> __( "Define the default background color for the step header.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"        		=> array( 'element' => "icon_back_switch", 'value' => 'single' ),
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Hover Icon Background", "ts_visual_composer_extend" ),
							"param_name"        		=> "icon_back_hover",
							"value"             		=> "#ededed",
							"description"       		=> __( "Define the hover background color for the step header.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"        		=> array( 'element' => "icon_back_switch", 'value' => 'single' ),
						),
						array(
							"type" 						=> "advanced_gradient",
							"heading" 					=> __("Gradient Generator", "ts_visual_composer_extend"),						
							"param_name" 				=> "icon_back_gradient",
							"description" 				=> __('Use the controls above to create a custom gradient background for the step header.', 'ts_visual_composer_extend'),
							"dependency"        		=> array( 'element' => "icon_back_switch", 'value' => 'gradient' ),
						),
						// Icon/Image Animation
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_4",
							"seperator"					=> "Header Animation",
							"dependency"        		=> array( 'element' => "icon_back_type", 'value' => array('iconback', 'icononly') ),
						),
						array(
							"type"						=> "css3animations",
							"heading"					=> __("Icon Hover Animation", "ts_visual_composer_extend"),
							"param_name"				=> "step_animation",
							"prefix"					=> "ts-hover-css-",
							"connector"					=> "css3animations_name",
							"noneselect"				=> "true",
							"default"					=> "",
							"value"						=> "",
							"admin_label"				=> false,
							"description" 				=> __("Select the CSS3 animation you want to apply to the icon on hover.", "ts_visual_composer_extend"),
							"dependency"        		=> array( 'element' => "icon_back_type", 'value' => array('iconback', 'icononly') ),
						),
						array(
							"type"						=> "hidden_input",
							"heading"					=> __( "Animation Type", "ts_visual_composer_extend" ),
							"param_name"				=> "css3animations_name",
							"value"						=> "",
							"admin_label"				=> true,
							"dependency"        		=> array( 'element' => "icon_back_type", 'value' => array('iconback', 'icononly') ),
						),
						// Content Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_5",
							"seperator"					=> "Step Content",
							"group" 					=> "Step Content",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "Title", "ts_visual_composer_extend" ),
							"param_name"        		=> "step_title",
							"value"             		=> "",
							"admin_label"           	=> true,
							"description"       		=> __( "Enter a title for the step item.", "ts_visual_composer_extend" ),
							"group" 					=> "Step Content",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Title Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "title_color",
							"value"             		=> "#4e4e4d",
							"description"       		=> __( "Define the font color for the step title.", "ts_visual_composer_extend" ),
							"group" 					=> "Step Content",
						),						
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Use Full HTML Editor", "ts_visual_composer_extend" ),
							"param_name"        		=> "content_html",
							"value"             		=> "false",
							"description"       		=> __( "Switch the toggle if you want to use the full tinyMCE editor to provide the element content.", "ts_visual_composer_extend" ),
							"group" 					=> "Step Content",
						),	
						array(
							"type"              		=> "textarea_raw_html",
							"heading"           		=> __( "Step Content", "ts_visual_composer_extend" ),
							"param_name"        		=> "step_content",
							"value"             		=> base64_encode(""),
							"description"      	 		=> __( "Enter the step content here; HTML code can be used.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "content_html", 'value' => 'false' ),
							"group" 					=> "Step Content",
						),						
						array(
							"type"						=> "textarea_html",
							"heading"					=> __( "Step Content", "ts_visual_composer_extend" ),
							"param_name"				=> "content",
							"value"						=> "",
							"description"				=> __( "Create the content for the icon box element.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "content_html", 'value' => 'true' ),
							"group" 					=> "Step Content",
						),						
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Text Align", "ts_visual_composer_extend" ),
							"param_name"        		=> "content_align",
							"width"             		=> 150,
							"value"             		=> array(
								__( "Center", "ts_visual_composer_extend" )                        => "center",
								__( "Left", "ts_visual_composer_extend" )                          => "left",
								__( "Right", "ts_visual_composer_extend" )                         => "right",
								__( "Justify", "ts_visual_composer_extend" )                       => "justify",
							),
							"description"       		=> __( "Select the text alignment for step content.", "ts_visual_composer_extend" ),
							"group" 					=> "Step Content",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Text Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "content_color",
							"value"             		=> "#6C6C6C",
							"description"       		=> __( "Define the font color for the step content.", "ts_visual_composer_extend" ),
							"group" 					=> "Step Content",
						),
						// Step Link
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_6",
							"seperator"					=> "Link Settings",
							"group" 			        => "Step Link",
						),
						array(
							"type" 						=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterLinkPicker['enabled'] == "false" ? "vc_link" : "advancedlinks"),
							"heading" 					=> __("Link", "ts_visual_composer_extend"),
							"param_name" 				=> "step_link",
							"description" 				=> __("Provide a link to another site/page for the step element.", "ts_visual_composer_extend"),
							"group" 					=> "Step Link",
						),
						array(
							"type"              	    => "switch_button",
							"heading"                   => __( "Show Button", "ts_visual_composer_extend" ),
							"param_name"                => "button_show",
							"value"                     => "false",
							"description"               => __( "Switch the toggle if you want to show a dedicated link button.", "ts_visual_composer_extend" ),
							"group" 					=> "Step Link",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "Button Text", "ts_visual_composer_extend" ),
							"param_name"        		=> "button_text",
							"value"             		=> "Read More",
							"description"       		=> __( "Enter a text for the dedicated link button.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "button_show", 'value' => 'true' ),
							"group" 					=> "Step Link",
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Button Style", "ts_visual_composer_extend" ),
							"param_name"            	=> "button_style",
							"width"                 	=> 150,
							"value"                 	=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Flat_Button_Default_Colors,
							"description"           	=> __( "Select the color scheme for the link button.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "button_show", 'value' => 'true' ),
							"group" 					=> "Step Link",
						),						
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Button Hover Style", "ts_visual_composer_extend" ),
							"param_name"            	=> "button_hover",
							"width"                 	=> 150,
							"value"                 	=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Flat_Button_Hover_Colors,
							"description"           	=> __( "Select the hover color scheme for the link button.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "button_show", 'value' => 'true' ),
							"group" 					=> "Step Link",
						),							
						// Tooltip Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_7",
							"seperator"            		=> "Tooltip Settings",
							"group" 					=> "Step Tooltip",
						),
						array(
							"type"              		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorBase64TinyMCE == "true" ? "wysiwyg_base64" : "textarea_raw_html"),
							"heading"           		=> __( "Tooltip Content", "ts_visual_composer_extend" ),
							"param_name"        		=> "tooltip_content",
							"minimal"					=> "true",
							"value"             		=> base64_encode(""),
							"description"      	 		=> __( "Enter the tooltip content for the element; basic HTML code can be used.", "ts_visual_composer_extend" ),
							"group" 					=> "Step Tooltip",
						),
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Tooltip Position", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltip_position",
							"value"						=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Vertical,
							"description"				=> __( "Select the tooltip position in relation to the element.", "ts_visual_composer_extend" ),
							"group" 					=> "Step Tooltip",
						),							
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Tooltip Style", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltip_style",
							"value"             		=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Layouts,
							"description"				=> __( "Select the tooltip style.", "ts_visual_composer_extend" ),
							"group" 					=> "Step Tooltip",
						),							
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Tooltip Animation", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tooltip_animation",
							"value"                 	=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Animations,
							"description"		    	=> __( "Select how the tooltip entry and exit should be animated once triggered.", "ts_visual_composer_extend" ),
							"group" 					=> "Step Tooltip",
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
							"group" 					=> "Step Tooltip",
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
							"group" 					=> "Step Tooltip",
						),								
						// Other Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_8",
							"seperator"					=> "Other Settings",
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
	if ((class_exists('WPBakeryShortCodesContainer')) && (!class_exists('WPBakeryShortCode_TS_VCSC_Horizontal_Steps_Container'))) {
		class WPBakeryShortCode_TS_VCSC_Horizontal_Steps_Container extends WPBakeryShortCodesContainer {};
	}
	if ((class_exists('WPBakeryShortCode')) && (!class_exists('WPBakeryShortCode_TS_VCSC_Horizontal_Steps_Item'))) {
		class WPBakeryShortCode_TS_VCSC_Horizontal_Steps_Item extends WPBakeryShortCode {};
	}	
	// Initialize "TS Horizontal Steps" Class
	if (class_exists('TS_Horizontal_Steps')) {
		$TS_Horizontal_Steps = new TS_Horizontal_Steps;
	}
?>