<?php
	if (!class_exists('TS_Image_GIFPlayer')){
		class TS_Image_GIFPlayer {
			function __construct() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						$this->TS_VCSC_Add_Image_GIFPlayer_Lean();
					} else if (function_exists('vc_map')) {
						add_action('init',                                  array($this, 'TS_VCSC_Add_Image_GIFPlayer_Elements'), 9999999);
					}
				} else {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_Image_GIFPlayer_Lean'), 9999999);
					} else if (function_exists('vc_map')) {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_Image_GIFPlayer_Elements'), 9999999);
					}
				}
				if ((is_admin() == false) || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAJAX == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAlways == "true")) {
					add_shortcode('TS_VCSC_Image_GIFPlayer',				array($this, 'TS_VCSC_Image_GIFPlayer_Function'));
				}
			}
			
			// Register Element(s) via LeanMap
			function TS_VCSC_Add_Image_GIFPlayer_Lean() {
				vc_lean_map('TS_VCSC_Image_GIFPlayer', 						array($this, 'TS_VCSC_Add_Image_GIFPlayer_Elements'), null);
			}
			
			// Extract First Frame from GIF Image
			function TS_VCSC_Image_GIFPlayer_FirstFrame($img_url){
				$new_still 						= preg_replace('/\.gif$/', '_firstframe.jpg', $img_url);
				$new_still_path 				= str_replace(home_url(), ABSPATH, $new_still);
				imagejpeg(imagecreatefromgif($img_url), $new_still_path, 90);		
				return $new_still_path;
			}
			
			// Create Absolute Path for Image
			function TS_VCSC_Image_GIFPlayer_GetStillURL($still_path){
				return str_replace(ABSPATH, home_url(), $still_path);
			}
			
			// Image IHover
			function TS_VCSC_Image_GIFPlayer_Function ($atts, $content = null) {
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();
				
				// Check for Front End Editor
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					$gif_frontent				= "true";
				} else {
					$gif_frontent				= "false";
				}
				
				extract( shortcode_atts( array(
					'image'							=> '',
					'preview'						=> 'first',		// first, custom
					'placeholder'					=> '',
					'trigger'						=> 'click',		// click, hover, inview
					'preloader'						=> 22,
					'ondemand'						=> 'false',		// true, false
					'autostop'						=> 'true',		// true, false
					// ALT Attribute Settings
					'attribute_alt'					=> 'false',
					'attribute_alt_value'			=> '',
					// Lightbox Settings
					'lightbox_usage'				=> 'false',
					'lightbox_group'				=> 'true',
					'lightbox_group_name'			=> '',
					'lightbox_title'				=> '',
					'lightbox_size'					=> 'full',
					'lightbox_effect'				=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LightboxDefaultAnimation,
					'lightbox_speed'				=> 5000,
					'lightbox_social'				=> 'false',
					'lightbox_backlight'			=> 'auto',
					'lightbox_backlight_color'		=> '#ffffff',
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
					// Other Settings
					'margin_top'					=> 0,
					'margin_bottom'					=> 0,
					'el_id' 						=> '',
					'el_class'                  	=> '',
					'css'							=> '',
				), $atts ));
			
				$output								= "";
				$randomizer							= mt_rand(999999, 9999999);
				
				if (!empty($el_id)) {
					$gif_image_id					= $el_id;
				} else {
					$gif_image_id					= 'ts-vcsc-image-gif-' . $randomizer;
				}
				
				// Retrieve GIF Image
				$gif_image 							= wp_get_attachment_image_src($image, 'full');
				
				// Contingency Check
				if	(get_post_mime_type($image) !== "image/gif"){
					$output .= '<div class="ts-image-gif-missing">';
						$output .= __("You need to select an actual .gif image as foundation for this element, as any other image types will not work for this element!", "ts_visual_composer_extend");
					$output .= '</div>';
					echo $output;
					$myvariable = ob_get_clean();
					return $myvariable;
				} else {
					wp_enqueue_style('dashicons');					
					wp_enqueue_style('ts-extend-imageeffects');
					if ($gif_frontent == "false") {
						wp_enqueue_script('ts-visual-composer-extend-front');
						wp_enqueue_script('ts-extend-imageeffects');
					}
				}
				
				// Lightbox Settings
				if (($lightbox_usage == "true") && ($gif_frontent == "false")) {
					wp_enqueue_script('ts-extend-krautlightbox');
					wp_enqueue_style('ts-extend-krautlightbox');
				}
				if ($lightbox_backlight == "auto") {
					$nacho_color					= '';
				} else if ($lightbox_backlight == "custom") {
					$nacho_color					= 'data-color="' . $lightbox_backlight_color . '"';
				} else if ($lightbox_backlight == "hideit") {
					$nacho_color					= 'data-color="rgba(0, 0, 0, 0)"';
				}
				
				// Retrieve Preview Image
				if ($preview == "first") {
					$still_attach 					= preg_replace('/\.gif$/', '_firstframe.jpg', $gif_image[0]);
					if (!file_exists($still_attach)) {
						$still_path 				= $this->TS_VCSC_Image_GIFPlayer_FirstFrame($gif_image[0]);
						$still_url 					= $this->TS_VCSC_Image_GIFPlayer_GetStillURL($still_path);
					} else {
						$still_url					= $still_attach;
					}
				} else if ($preview == "custom") {
					$still_url						= wp_get_attachment_image_src($placeholder, 'full');
					$still_url						= $still_url[0];
				} else {
					$still_url						= $gif_image[0];
				}
				
				// Retrieve Lightbox Image
				if ($lightbox_usage == "true") {
					$gif_lightbox					= wp_get_attachment_image_src($image, 'full');
				}

				// Image ALT Settings
				$image_extension 					= pathinfo($gif_image[0], PATHINFO_EXTENSION);
				if ($attribute_alt == "true") {
					$alt_attribute					= $attribute_alt_value;
				} else {
					$alt_attribute					= get_post_meta($image, '_wp_attachment_image_alt', true);
					if ($alt_attribute == "") {
						$alt_attribute				= basename($gif_image[0], "." . $image_extension);
					}			
				}
				
				// Tooltip Setup
				if (($tooltip_usage == "true") && (strip_tags($tooltip_content) != '') && ($gif_frontent == "false")) {
					$tooltip_position				= TS_VCSC_TooltipMigratePosition($tooltip_position);
					$tooltip_style					= TS_VCSC_TooltipMigrateStyle($tooltip_style);
					wp_enqueue_style('ts-extend-tooltipster');
					wp_enqueue_script('ts-extend-tooltipster');	
					$Tooltip_Content				= 'data-tooltipster-html="true" data-tooltipster-title="" data-tooltipster-text="' . strip_tags($tooltip_content) . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="' . $tooltip_arrow . '" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="' . $tooltip_animation . '" data-tooltipster-trigger="hover" data-tooltipster-background="' . $tooltip_background . '" data-tooltipster-border="' . $tooltip_border . '" data-tooltipster-color="' . $tooltip_color . '" data-tooltipster-offsetx="' . $tooltip_offsetx . '" data-tooltipster-offsety="' . $tooltip_offsety . '"';
					$Tooltip_Class					= 'ts-has-tooltipster-tooltip';
				} else {
					$Tooltip_Content				= '';
					$Tooltip_Class					= '';
				}
								
				// Create Data + Class Attributes
				$gif_data							= 'data-image-playing="false" data-image-ondemand="' . $ondemand . '" data-image-autostop="' . $autostop . '" data-image-loaded="false" data-image-gif="' . $gif_image[0] . '" data-image-preview="' . $still_url . '"';
				if ($gif_frontent == "false") {
					$gif_classes					= 'ts-image-gif-paused ts-image-gif-' . $trigger . ' ts-image-gif-' . ($ondemand == "true" ? "ondemand" : "preload") . ($autostop == "true" ? ' ts-image-gif-autostop' : '');
				} else {
					$gif_classes					= 'ts-image-gif-frontend';
				}
				
				// Visual Composer Custom Override
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Image_GIF', $atts);
				} else {
					$css_class						= '';
				}
				
				// Create Final Output
				$output .= '<div id="' . $gif_image_id . '" class="ts-image-gif-container ' . $gif_classes . ' ' . $el_class . ' ' . $css_class . ' ' . $Tooltip_Class . '" ' . $gif_data . ' ' . $Tooltip_Content . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';            
					$output .= '<img class="ts-image-gif-preview no-lazy" data-no-lazy="1" src="' . $still_url . '" alt="' . $alt_attribute . '"/>';
					if ($gif_frontent == "false") {
						if ($ondemand == "true") {
							$output .= '<img class="ts-image-gif-fullgif no-lazy no-ajaxy" data-no-lazy="1" src="' . $still_url . '" data-image-gif="' . $gif_image[0] . '" alt="' . $alt_attribute . '" style="display: none;"/>';
						} else {
							$output .= '<img class="ts-image-gif-fullgif no-lazy no-ajaxy" data-no-lazy="1" src="' . $gif_image[0] . '" data-image-gif="' . $gif_image[0] . '" alt="' . $alt_attribute . '" style="display: none;"/>';
						}						
						$output .= '<div class="ts-image-gif-preloader" style="display: ' . ($ondemand == "true" ? "none" : "block") . '">';
							$output .= TS_VCSC_CreatePreloaderCSS("ts-image-gif-loader-" . $randomizer, "", $preloader, "true");
						$output .= '</div>';
					}
					$output .= '<span class="ts-image-gif-button" style="display: ' . ($ondemand == "true" ? "block" : "none") . '"></span>';
					if ($lightbox_usage == "true") {
						$output .= '<a class="ts-image-gif-lightbox nch-holder nch-lightbox-media no-lazy no-ajaxy" href="' . $gif_lightbox[0] . '" target="_blank" data-title="' . $lightbox_title . '" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '"></a>';
					}
				$output .= '</div>';
			
				echo $output;
		
				$myvariable = ob_get_clean();
				return $myvariable;
			}
		
			// Add Image GIF Player Element
			function TS_VCSC_Add_Image_GIFPlayer_Elements() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add GIF Player Element
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					"name"                          => __( "TS Image GIF Player", "ts_visual_composer_extend" ),
					"base"                          => "TS_VCSC_Image_GIF",
					"icon"                          => "ts-composer-element-icon-image-gif",
					"class"                         => "ts_vcsc_main_image_gif",
					"category"                      => __( "VC Extensions", "ts_visual_composer_extend" ),
					"description" 		            => __("Place a GIF image player", "ts_visual_composer_extend"),
					"admin_enqueue_js"            	=> "",
					"admin_enqueue_css"           	=> "",
					"params"                        => array(
						// Image Selection
						array(
							"type"                  => "seperator",
							"param_name"            => "seperator_1",
							"seperator"				=> "Image Selection",
						),
						array(
							"type"                  => "attach_image",
							"holder" 				=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorImagePreview == "true" ? "img" : ""),
							"heading"               => __( "GIF Image", "ts_visual_composer_extend" ),
							"param_name"            => "image",
							"class"					=> "ts_vcsc_holder_image",
							"value"                 => "",
							"admin_label"           => ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorImagePreview == "true" ? false : true),
							"description"           => __( "Select the GIF image you want to use.", "ts_visual_composer_extend" )
						),
						array(
							"type"             	 	=> "switch_button",
							"heading"			    => __( "Add Custom ALT Attribute", "ts_visual_composer_extend" ),
							"param_name"		    => "attribute_alt",
							"value"				    => "false",
							"description"       	=> __( "Switch the toggle if you want add a custom ALT attribute value, otherwise file name will be set.", "ts_visual_composer_extend" )
						),
						array(
							"type"                  => "textfield",
							"heading"               => __( "Enter ALT Value", "ts_visual_composer_extend" ),
							"param_name"            => "attribute_alt_value",
							"value"                 => "",
							"description"           => __( "Enter a custom value for the ALT attribute for this image.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "attribute_alt", 'value' => 'true' )
						),
						// Player Setup
						array(
							"type"                  => "seperator",
							"param_name"            => "seperator_2",
							"seperator"				=> "Player Setup",
						),
						array(
							"type"              	=> "dropdown",
							"heading"           	=> __( "Player: Preview Source", "ts_visual_composer_extend" ),
							"param_name"        	=> "preview",
							"width"             	=> 150,
							"value"             	=> array(
								__( 'First Image in GIF', "ts_visual_composer_extend" )		=> "first",
								__( 'Custom Image', "ts_visual_composer_extend" )			=> "custom",
							),
							"admin_label"			=> true,
							"description"       	=> __( "Select the source for the preview image; the automatic first image extraction will not work with GIF images using a transparent background.", "ts_visual_composer_extend" ),
						),
						array(
							"type"                  => "attach_image",
							"heading"               => __( "Player: Preview Image", "ts_visual_composer_extend" ),
							"param_name"            => "placeholder",
							"value"                 => "",
							"dependency"        	=> array( 'element' => "preview", 'value' => array('custom') ),
							"description"           => __( "Select the custom preview image you want to use.", "ts_visual_composer_extend" )
						),	
						array(
							"type"              	=> "dropdown",
							"heading"           	=> __( "Player: Trigger", "ts_visual_composer_extend" ),
							"param_name"        	=> "trigger",
							"width"             	=> 150,
							"value"             	=> array(
								__( 'Click Event', "ts_visual_composer_extend" )			=> "click",
								__( 'Hover + Touch Event', "ts_visual_composer_extend" )	=> "hover",
								__( 'Browser In-View Event', "ts_visual_composer_extend" )	=> "inview",
							),
							"admin_label"			=> true,
							"description"       	=> __( "Select how the GIF player should be triggered.", "ts_visual_composer_extend" ),
						),
						array(
							"type"				    => "livepreview",
							"heading"			    => __( "Player: Preloader", "ts_visual_composer_extend" ),
							"param_name"		    => "preloader",
							"preview"				=> "preloaders",
							"shownone"				=> "false",
							"value"                 => 22,
							"description"		    => __( "Select the style for the preloader animation to be shown while the GIF image is loading.", "ts_visual_composer_extend" ),
						),
						array(
							"type"					=> "switch_button",
							"heading"           	=> __( "Player: On-Demand", "ts_visual_composer_extend" ),
							"param_name"        	=> "ondemand",
							"value"            	 	=> "false",
							"description"       	=> __( "Switch the toggle if you want to start loading the GIF image only once the player has been started for the first time.", "ts_visual_composer_extend" ),
						),		
						array(
							"type"					=> "switch_button",
							"heading"           	=> __( "Player: Auto-Stop", "ts_visual_composer_extend" ),
							"param_name"        	=> "autostop",
							"value"            	 	=> "true",
							"description"       	=> __( "Switch the toggle if you want to automatically stop this GIF player once another GIF player has been started.", "ts_visual_composer_extend" ),
						),
						// Lightbox Settings
						array(
							"type"                  => "seperator",
							"param_name"            => "seperator_3",
							"seperator"				=> "Lightbox Settings",
							"group" 				=> "Lightbox Settings",
						),
						array(
							"type"					=> "switch_button",
							"heading"           	=> __( "Add Lightbox Button", "ts_visual_composer_extend" ),
							"param_name"        	=> "lightbox_usage",
							"value"            	 	=> "false",
							"description"       	=> __( "Switch the toggle if you want to provide an overlay button to show the full sized GIF image within a lightbox.", "ts_visual_composer_extend" ),
							"group" 				=> "Lightbox Settings",
						),
						array(
							"type"             	 	=> "switch_button",
							"heading"			    => __( "Create AutoGroup", "ts_visual_composer_extend" ),
							"param_name"		    => "lightbox_group",
							"value"				    => "true",
							"description"       	=> __( "Switch the toggle if you want the plugin to group this image with all other non-gallery images on the page.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "lightbox_usage", 'value' => 'true' ),
							"group" 				=> "Lightbox Settings",
						),
						array(
							"type"                  => "textfield",
							"heading"               => __( "Group Name", "ts_visual_composer_extend" ),
							"param_name"            => "lightbox_group_name",
							"value"                 => "",
							"description"           => __( "Enter a custom group name to manually build group with other non-gallery items.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "lightbox_group", 'value' => 'false' ),
							"group" 				=> "Lightbox Settings",
						),
						array(
							"type"                  => "textfield",
							"heading"               => __( "Lightbox Title", "ts_visual_composer_extend" ),
							"param_name"            => "lightbox_title",
							"value"                 => "",
							"description"           => __( "Enter a title to be shown alongside the image inside the lightbox.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "lightbox_usage", 'value' => 'true' ),
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
							"description"           => __( "Select the transition effect to be used for the image in the lightbox.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "lightbox_usage", 'value' => 'true' ),
							"group" 				=> "Lightbox Settings",
						),
						array(
							"type"                  => "dropdown",
							"heading"               => __( "Backlight Effect", "ts_visual_composer_extend" ),
							"param_name"            => "lightbox_backlight",
							"width"                 => 150,
							"value"                 => array(
								__( 'Auto Color', "ts_visual_composer_extend" )					=> "auto",
								__( 'Custom Color', "ts_visual_composer_extend" )				=> "custom",
								__( 'Transparent Backlight', "ts_visual_composer_extend" )     	=> "hideit",
							),
							"description"           => __( "Select the backlight effect for the image.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "lightbox_usage", 'value' => 'true' ),
							"group" 				=> "Lightbox Settings",
						),
						array(
							"type"                  => "colorpicker",
							"heading"               => __( "Custom Backlight Color", "ts_visual_composer_extend" ),
							"param_name"            => "lightbox_backlight_color",
							"value"                 => "#ffffff",
							"description"           => __( "Define the backlight color for the lightbox image.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "lightbox_backlight", 'value' => 'custom' ),
							"group" 				=> "Lightbox Settings",
						),
						// Tooltip Settings
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_4",
							"seperator"            	=> "Tooltip Settings",
							"group" 				=> "Tooltip Settings",
						),
						array(
							"type"					=> "switch_button",
							"heading"           	=> __( "Tooltip Addition", "ts_visual_composer_extend" ),
							"param_name"        	=> "tooltip_usage",
							"value"            	 	=> "false",
							"description"       	=> __( "Switch the toggle if you want to add an optional tooltip to the element.", "ts_visual_composer_extend" ),
							"group" 				=> "Tooltip Settings",
						),
						array(
							"type"              	=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorBase64TinyMCE == "true" ? "wysiwyg_base64" : "textarea_raw_html"),
							"heading"           	=> __( "Tooltip Content", "ts_visual_composer_extend" ),
							"param_name"        	=> "tooltip_content",
							"minimal"				=> "true",
							"value"             	=> base64_encode(""),
							"description"      	 	=> __( "Enter the tooltip content for the element; basic HTML code can be used.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "tooltip_usage", 'value' => 'true' ),
							"group" 				=> "Tooltip Settings",
						),
						array(
							"type"					=> "switch_button",
							"heading"           	=> __( "Tooltip Arrow", "ts_visual_composer_extend" ),
							"param_name"        	=> "tooltip_arrow",
							"value"             	=> "true",
							"description"       	=> __( "Switch the toggle to either show or hide the tooltip arrow.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "tooltip_usage", 'value' => 'true' ),
							"group" 				=> "Tooltip Settings",
						),
						array(
							"type"					=> "dropdown",
							"heading"				=> __( "Tooltip Position", "ts_visual_composer_extend" ),
							"param_name"			=> "tooltip_position",
							"value"					=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Positions,
							"description"			=> __( "Select the tooltip position in relation to the element.", "ts_visual_composer_extend" ),
							"edit_field_class"		=> "vc_col-sm-6 vc_column",
							"dependency"        	=> array( 'element' => "tooltip_usage", 'value' => 'true' ),
							"group" 				=> "Tooltip Settings",
						),
						array(
							"type"				    => "dropdown",
							"heading"			    => __( "Tooltip Animation", "ts_visual_composer_extend" ),
							"param_name"		    => "tooltip_animation",
							"value"                 => $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Animations,
							"description"		    => __( "Select how the tooltip entry and exit should be animated once triggered.", "ts_visual_composer_extend" ),
							"edit_field_class"		=> "vc_col-sm-6 vc_column",
							"dependency"        	=> array( 'element' => "tooltip_usage", 'value' => 'true' ),
							"group"					=> "Tooltip Settings",
						),	
						array(
							"type"					=> "dropdown",
							"heading"				=> __( "Tooltip Style", "ts_visual_composer_extend" ),
							"param_name"			=> "tooltip_style",
							"value"             	=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Styles,
							"description"			=> __( "Select the tooltip style.", "ts_visual_composer_extend" ),
							"edit_field_class"		=> "vc_col-sm-6 vc_column",
							"dependency"        	=> array( 'element' => "tooltip_usage", 'value' => 'true' ),
							"group" 				=> "Tooltip Settings",
						),
						array(
							"type"              	=> "colorpicker",
							"heading"           	=> __( "Tooltip Font Color", "ts_visual_composer_extend" ),
							"param_name"        	=> "tooltip_color",
							"value"             	=> "#ffffff",
							"description"       	=> __( "Define the custom font color for the tooltip.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "tooltip_style", 'value' => array('tooltipster-custom', 'ts-simptip-style-custom') ),
							"edit_field_class"		=> "vc_col-sm-6 vc_column",
							"group" 				=> "Tooltip Settings",
						),		
						array(
							"type"              	=> "colorpicker",
							"heading"           	=> __( "Tooltip Background Color", "ts_visual_composer_extend" ),
							"param_name"        	=> "tooltip_background",
							"value"             	=> "#000000",
							"description"       	=> __( "Define the custom background color for the tooltip.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "tooltip_style", 'value' => array('tooltipster-custom', 'ts-simptip-style-custom') ),
							"edit_field_class"		=> "vc_col-sm-6 vc_column",
							"group" 				=> "Tooltip Settings",
						),
						array(
							"type"              	=> "colorpicker",
							"heading"           	=> __( "Tooltip Border Color", "ts_visual_composer_extend" ),
							"param_name"        	=> "tooltip_border",
							"value"             	=> "#000000",
							"description"       	=> __( "Define the custom border color for the tooltip.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "tooltip_style", 'value' => array('tooltipster-custom', 'ts-simptip-style-custom') ),
							"edit_field_class"		=> "vc_col-sm-6 vc_column",
							"group" 				=> "Tooltip Settings",
						),	
						array(
							"type"					=> "nouislider",
							"heading"				=> __( "Tooltip X-Offset", "ts_visual_composer_extend" ),
							"param_name"			=> "tooltip_offsetx",
							"value"					=> "0",
							"min"					=> "-100",
							"max"					=> "100",
							"step"					=> "1",
							"unit"					=> 'px',
							"description"			=> __( "Define an optional X-Offset for the tooltip position.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "tooltip_usage", 'value' => 'true' ),
							"group" 				=> "Tooltip Settings",
						),
						array(
							"type"					=> "nouislider",
							"heading"				=> __( "Tooltip Y-Offset", "ts_visual_composer_extend" ),
							"param_name"			=> "tooltip_offsety",
							"value"					=> "0",
							"min"					=> "-100",
							"max"					=> "100",
							"step"					=> "1",
							"unit"					=> 'px',
							"description"			=> __( "Define an optional Y-Offset for the tooltip position.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "tooltip_usage", 'value' => 'true' ),
							"group" 				=> "Tooltip Settings",
						),
						// Other Settings
						array(
							"type"                  => "seperator",
							"param_name"            => "seperator_5",
							"seperator"				=> "Other Settings",
							"group" 				=> "Other Settings",
						),
						array(
							"type"                  => "nouislider",
							"heading"               => __( "Margin: Top", "ts_visual_composer_extend" ),
							"param_name"            => "margin_top",
							"value"                 => "0",
							"min"                   => "0",
							"max"                   => "200",
							"step"                  => "1",
							"unit"                  => 'px',
							"description"           => __( "Select the top margin for the element.", "ts_visual_composer_extend" ),
							"group" 				=> "Other Settings",
						),
						array(
							"type"                  => "nouislider",
							"heading"               => __( "Margin: Bottom", "ts_visual_composer_extend" ),
							"param_name"            => "margin_bottom",
							"value"                 => "0",
							"min"                   => "0",
							"max"                   => "200",
							"step"                  => "1",
							"unit"                  => 'px',
							"description"           => __( "Select the bottom margin for the element.", "ts_visual_composer_extend" ),
							"group" 				=> "Other Settings",
						),
						array(
							"type"                  => "textfield",
							"heading"               => __( "Define ID Name", "ts_visual_composer_extend" ),
							"param_name"            => "el_id",
							"value"                 => "",
							"description"           => __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
							"group" 				=> "Other Settings",
						),
						array(
							"type"                  => "tag_editor",
							"heading"           	=> __( "Extra Class Names", "ts_visual_composer_extend" ),
							"param_name"            => "el_class",
							"value"                 => "",
							"description"      		=> __( "Enter additional class names for the element.", "ts_visual_composer_extend" ),
							"group" 				=> "Other Settings",
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
	if ((class_exists('WPBakeryShortCode')) && (!class_exists('WPBakeryShortCode_TS_VCSC_Image_GIFPlayer'))) {
		class WPBakeryShortCode_TS_VCSC_Image_GIFPlayer extends WPBakeryShortCode {};
	}
	// Initialize "TS Image GIF Player" Class
	if (class_exists('TS_Image_GIFPlayer')) {
		$TS_Image_GIFPlayer = new TS_Image_GIFPlayer;
	}
?>