<?php
    global $VISUAL_COMPOSER_EXTENSIONS;	
    $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
		"name"                          => __( "TS Image Caman", "ts_visual_composer_extend" ),
		"base"                          => "TS-VCSC-Image-Caman",
		"icon"                          => "ts-composer-element-icon-image-caman",
		"class"                         => "ts_vcsc_main_image_caman",
		"category"                      => __( "VC Extensions", "ts_visual_composer_extend" ),
		"description" 		            => __("Place an image with Caman effects", "ts_visual_composer_extend"),
		"admin_enqueue_js"            	=> "",
		"admin_enqueue_css"           	=> "",
		"params"                        => array(
			// Image Selection and Dimensions
			array(
				"type"                  => "seperator",
				"param_name"            => "seperator_1",
				"seperator"				=> "Image Selection",
			),
			array(
				"type"                  => "attach_image",
				"holder" 				=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorImagePreview == "true" ? "img" : ""),
				"heading"               => __( "Image", "ts_visual_composer_extend" ),
				"param_name"            => "image",
				"class"					=> "ts_vcsc_holder_image",
				"value"                 => "",
				"admin_label"           => ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorImagePreview == "true" ? false : true),
				"description"           => __( "Select the image you want to use.", "ts_visual_composer_extend" )
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
			// Image Styles
			array(
				"type"                  => "seperator",
				"param_name"            => "seperator_2",
				"seperator"				=> "Image Effect",
			),
			array(
				"type"                  => "dropdown",
				"heading"               => __( "Effect", "ts_visual_composer_extend" ),
				"param_name"            => "caman_effect",
				"width"                 => 150,
				"admin_label"           => true,
				"value"                 => array(
					__( 'Vintage', "ts_visual_composer_extend" )          				=> "vintage",
					__( 'Lomo', "ts_visual_composer_extend" )             				=> "lomo",
					__( 'Clarity', "ts_visual_composer_extend" )          				=> "clarity",
					__( 'Sin City', "ts_visual_composer_extend" )         				=> "sinCity",
					__( 'Sunrise', "ts_visual_composer_extend" )          				=> "sunrise",
					__( 'Cross Process', "ts_visual_composer_extend" )    				=> "crossProcess",
					__( 'Orange Peel', "ts_visual_composer_extend" )      				=> "orangePeel",
					__( 'Love', "ts_visual_composer_extend" )             				=> "love",
					__( 'Grungy', "ts_visual_composer_extend" )           				=> "grungy",
					__( 'Jarques', "ts_visual_composer_extend" )          				=> "jarques",
					__( 'Pin Hole', "ts_visual_composer_extend" )         				=> "pinhole",
					__( 'Old Boot', "ts_visual_composer_extend" )         				=> "oldBoot",
					__( 'Glowing Sun', "ts_visual_composer_extend" )      				=> "glowingSun",
					__( 'Hazy Days', "ts_visual_composer_extend" )        				=> "hazyDays",
					__( 'Her Majesty', "ts_visual_composer_extend" )      				=> "herMajesty",
					__( 'Nostalgia', "ts_visual_composer_extend" )        				=> "nostalgia",
					__( 'Hemingway', "ts_visual_composer_extend" )        				=> "hemingway",
					__( 'Concentrate', "ts_visual_composer_extend" )      				=> "concentrate",
					__( 'Emboss', "ts_visual_composer_extend" )           				=> "emboss",
					__( 'Grayscale', "ts_visual_composer_extend" )        				=> "greyscale",
					__( 'Invert', "ts_visual_composer_extend" )           				=> "invert",
				),
				"description"           => __( "Select the effect you want to apply to the image.", "ts_visual_composer_extend" )
			),
			array(
				"type"             	 	=> "switch_button",
				"heading"			    => __( "Allow for Switch Effekt", "ts_visual_composer_extend" ),
				"param_name"		    => "caman_switch_allow",
				"value"				    => "true",
				"admin_label"           => true,
				"description"       	=> __( "Switch the toggle if you want to provide a switch option between the Caman and the Original image.", "ts_visual_composer_extend" )
			),
			array(
				"type"                  => "dropdown",
				"heading"               => __( "Switch Style", "ts_visual_composer_extend" ),
				"param_name"            => "caman_switch_type",
				"width"                 => 300,
				"value"                 => array(
					__( 'Flip', "ts_visual_composer_extend" )             				=> "ts-imageswitch-flip",
					__( 'Fade', "ts_visual_composer_extend" )             				=> "ts-imageswitch-fade",
				),
				"description"           => __( "Define how the two images should be switched out.", "ts_visual_composer_extend" ),
				"dependency"            => array( 'element' => "caman_switch_allow", 'value' => 'true' )
			),
			array(
				"type"                  => "dropdown",
				"heading"               => __( "Trigger Type", "ts_visual_composer_extend" ),
				"param_name"            => "caman_trigger_flip",
				"width"                 => 300,
				"value"                 => array(
					__( "Click", "ts_visual_composer_extend" )							=> "ts-trigger-click",
					__( "Hover", "ts_visual_composer_extend" )							=> "ts-trigger-hover",
				),
				"description"           => __( "Define how to trigger the image switch.", "ts_visual_composer_extend" ),
				"dependency"            => array( 'element' => "caman_switch_type", 'value' => 'ts-imageswitch-flip' )
			),
			array(
				"type"                  => "dropdown",
				"heading"               => __( "Trigger Type", "ts_visual_composer_extend" ),
				"param_name"            => "caman_trigger_fade",
				"width"                 => 300,
				"value"                 => array(
					__( "Click", "ts_visual_composer_extend" )							=> "ts-trigger-click",
					__( "Hover", "ts_visual_composer_extend" )							=> "ts-trigger-hover",
				),
				"description"           => __( "Define how to trigger the image switch.", "ts_visual_composer_extend" ),
				"dependency"            => array( 'element' => "caman_switch_type", 'value' => 'ts-imageswitch-fade' )
			),
			array(
				"type"             	 	=> "switch_button",
				"heading"               => __( "Show Caman Handle", "ts_visual_composer_extend" ),
				"param_name"            => "caman_handle_show",
				"value"                 => "true",
				"description"       	=> __( "Use the toggle to show or hide a handle button below the image.", "ts_visual_composer_extend" ),
				"dependency"            => array( 'element' => "caman_switch_allow", 'value' => 'true' )
			),
			array(
				"type"                  => "colorpicker",
				"heading"               => __( "Handle Color", "ts_visual_composer_extend" ),
				"param_name"            => "caman_handle_color",
				"value"                 => "#0094FF",
				"description"           => __( "Define the color for the Caman handle button.", "ts_visual_composer_extend" ),
				"dependency"            => array( 'element' => "caman_handle_show", 'value' => 'true' )
			),
			// Image Tooltip
			array(
				"type"                  => "seperator",
				"param_name"            => "seperator_3",
				"seperator"				=> "Image Tooltip",
				"group" 				=> "Tooltip Settings",
			),
			array(
				"type"             	 	=> "switch_button",
				"heading"               => __( "Use Advanced Tooltip", "ts_visual_composer_extend" ),
				"param_name"            => "tooltip_css",
				"value"                 => "false",
				"description"       	=> __( "Switch the toggle if you want to apply am advanced tooltip to the image.", "ts_visual_composer_extend" ),
				"group" 				=> "Tooltip Settings",
			),
			array(
				"type"                  => "textarea",
				"heading"               => __( "Tooltip Content", "ts_visual_composer_extend" ),
				"param_name"            => "tooltip_content",
				"value"                 => "",
				"description"           => __( "Enter the tooltip content here (do not use quotation marks).", "ts_visual_composer_extend" ),
				"group" 				=> "Tooltip Settings",
			),
			array(
				"type"					=> "dropdown",
				"heading"				=> __( "Tooltip Position", "ts_visual_composer_extend" ),
				"param_name"			=> "tooltip_position",
				"value"					=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Vertical,
				"description"			=> __( "Select the tooltip position in relation to the image.", "ts_visual_composer_extend" ),
				"dependency"            => array( 'element' => "tooltip_css", 'value' => 'true' ),
				"group" 				=> "Tooltip Settings",
			),
			array(
				"type"					=> "dropdown",
				"heading"				=> __( "Tooltip Style", "ts_visual_composer_extend" ),
				"param_name"			=> "tooltip_style",
				"value"             	=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Layouts,
				"description"			=> __( "Select the tooltip style.", "ts_visual_composer_extend" ),
				"dependency"            => array( 'element' => "tooltip_css", 'value' => 'true' ),
				"group" 				=> "Tooltip Settings",
			),
			array(
				"type"				    => "dropdown",
				"heading"			    => __( "Tooltip Animation", "ts_visual_composer_extend" ),
				"param_name"		    => "tooltip_animation",
				"value"                 => $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Animations,
				"description"		    => __( "Select how the tooltip entry and exit should be animated once triggered.", "ts_visual_composer_extend" ),
				"dependency"            => array( 'element' => "tooltip_css", 'value' => 'true' ),
				"group"					=> "Tooltip Settings",
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
				"dependency"            => array( 'element' => "tooltip_css", 'value' => 'true' ),
				"group" 				=> "Tooltip Settings",
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
				"dependency"            => array( 'element' => "tooltip_css", 'value' => 'true' ),
				"group" 				=> "Tooltip Settings",
			),
			// Other Settings
			array(
				"type"                  => "seperator",
				"param_name"            => "seperator_4",
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
?>