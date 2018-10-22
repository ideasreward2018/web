<?php
    global $VISUAL_COMPOSER_EXTENSIONS;
	if ((class_exists('WPBakeryShortCode')) && (!class_exists('WPBakeryShortCode_TS_VCSC_Title_Ticker'))) {
		class WPBakeryShortCode_TS_VCSC_Title_Ticker extends WPBakeryShortCode {};
	};
    $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
		"name"                      	=> __( "TS Title Ticker (Deprecated)", "ts_visual_composer_extend" ),
		"base"                      	=> "TS_VCSC_Title_Ticker",
		"icon" 	                    	=> "ts-composer-element-icon-title-ticker",
		"category"                  	=> __('Deprecated', "ts_visual_composer_extend"),
		"description"               	=> __("Place a title with ticker effect", "ts_visual_composer_extend"),
		"admin_enqueue_js"        		=> "",
		"admin_enqueue_css"       		=> "",
		"deprecated" 					=> "2.2.0",
		"content_element"				=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_UseDeprecatedElements == "true" ? true : false,
		"params"                    	=> array(
			// Content Settings
			array(
				"type"              	=> "seperator",
				"param_name"        	=> "seperator_1",
				"seperator"				=> "Title Content",
			),
			array(
				"type"              	=> "switch_button",
				"heading"               => __( "Add Prefix String", "ts_visual_composer_extend" ),
				"param_name"            => "fixed_addition",
				"value"                 => "false",
				"description"           => __( "Switch the toggle if you want to add a fixed pre-string to the animated segments.", "ts_visual_composer_extend" ),
			),
			array(
				"type"              	=> "textfield",
				"heading"           	=> __( "Prefix: String", "ts_visual_composer_extend" ),
				"param_name"        	=> "fixed_string",
				"value"             	=> "",
				"description"       	=> __( "Enter an optional fixed text string to be shown before the animated segments.", "ts_visual_composer_extend" ),
				"dependency"        	=> array( 'element' => "fixed_addition", 'value' => 'true' ),
			),
			array(
				"type"                  => "exploded_textarea",
				"heading"               => __( "Title: Strings", "ts_visual_composer_extend" ),
				"param_name"            => "title_strings",
				"value"                 => "",
				"description"           => __( "Enter the individual title strings for the segments to be animated; separate by line break (NO commas allowed).", "ts_visual_composer_extend" ),
			),
			array(
				"type"              	=> "switch_button",
				"heading"               => __( "Show All Segments", "ts_visual_composer_extend" ),
				"param_name"            => "showall",
				"value"                 => "false",
				"description"           => __( "Switch the toggle if you want to show all title segments at the same time and just rotate them around.", "ts_visual_composer_extend" ),
			),
			array(
				"type"                  => "nouislider",
				"heading"               => __( "Number of Segments", "ts_visual_composer_extend" ),
				"param_name"            => "showitems",
				"value"                 => "1",
				"min"                   => "1",
				"max"                   => "25",
				"step"                  => "1",
				"unit"                  => 'x',
				"description"           => __( "Define the number of segments that should be shown at the same time.", "ts_visual_composer_extend" ),
				"dependency"        	=> array( 'element' => "showall", 'value' => 'false' )
			),
			array(
				"type"              	=> "switch_button",
				"heading"               => __( "Add Postfix String", "ts_visual_composer_extend" ),
				"param_name"            => "post_addition",
				"value"                 => "false",
				"description"           => __( "Switch the toggle if you want to add a fixed post-string to the animated segments.", "ts_visual_composer_extend" ),
			),
			array(
				"type"              	=> "textfield",
				"heading"           	=> __( "Postfix: String", "ts_visual_composer_extend" ),
				"param_name"        	=> "post_string",
				"value"             	=> "",
				"description"       	=> __( "Enter an optional fixed text string to be shown after the animated segments.", "ts_visual_composer_extend" ),
				"dependency"        	=> array( 'element' => "post_addition", 'value' => 'true' ),
			),	
			// Mobile Settings
			array(
				"type"              	=> "seperator",
				"param_name"        	=> "seperator_2",
				"seperator"				=> "Mobile Settings",
			),
			array(
				"type"              	=> "switch_button",
				"heading"               => __( "Use on Mobile", "ts_visual_composer_extend" ),
				"param_name"            => "mobile",
				"value"                 => "true",
				"admin_label"			=> true,
				"description"           => __( "Switch the toggle if you want to show the animation on mobile devices.", "ts_visual_composer_extend" ),
			),
			array(
				"type"              	=> "dropdown",
				"heading"           	=> __( "Alternative Wrapper", "ts_visual_composer_extend" ),
				"param_name"        	=> "wrapper",
				"width"             	=> 150,
				"value"             	=> array(
					__( "H1", "ts_visual_composer_extend" )                      	=> "h1",
					__( "H2", "ts_visual_composer_extend" )                    		=> "h2",
					__( "H3", "ts_visual_composer_extend" )                   		=> "h3",
					__( "H4", "ts_visual_composer_extend" )                   		=> "h4",
					__( "H5", "ts_visual_composer_extend" )                   		=> "h5",
					__( "H6", "ts_visual_composer_extend" )                   		=> "h6",
					__( "DIV", "ts_visual_composer_extend" )                   		=> "div",
				),
				"dependency"        	=> array( 'element' => "mobile", 'value' => 'false' ),
				"description"       	=> __( "Select the alternative wrapper for the title to be used on mobile devices.", "ts_visual_composer_extend" )
			),
			array(
				"type"              	=> "textfield",
				"heading"           	=> __( "Alternative Title", "ts_visual_composer_extend" ),
				"param_name"        	=> "title_mobile",
				"value"             	=> "",
				"dependency"        	=> array( 'element' => "mobile", 'value' => 'false' ),
				"description"       	=> __( "Provide an alternative title to be used on mobile devices.", "ts_visual_composer_extend" )
			),
			// Style Settings
			array(
				"type"              	=> "seperator",
				"param_name"        	=> "seperator_3",
				"seperator"				=> "Global Styling",
				"group"					=> "Style Settings"
			),	
			array(
				"type"					=> "image_selector",
				"heading"				=> __( "Text: Horizontal Align", "ts_visual_composer_extend" ),
				"param_name"			=> "font_align",
				"template"				=> "alignfull",
				"value"					=> "center",
				"edit_field_class"		=> "vc_col-sm-6 vc_column",
				"description"       	=> __( "Select the horizontal alignment for all sections.", "ts_visual_composer_extend" ),
				"group"					=> "Style Settings"
			),
			array(
				"type"					=> "image_selector",
				"heading"           	=> __( "Text: Vertical Align", "ts_visual_composer_extend" ),
				"param_name"        	=> "font_vertical",
				"template"				=> "vertical",
				"value"					=> "bottom",
				"edit_field_class"		=> "vc_col-sm-6 vc_column",
				"description"       	=> __( "Select the vertical alignment for all sections.", "ts_visual_composer_extend" ),
				"group"					=> "Style Settings"
			),
			array(
				"type"              	=> "nouislider",
				"heading"           	=> __( "Position Adjustment", "ts_visual_composer_extend" ),
				"param_name"        	=> "position_adjust",
				"value"             	=> "0",
				"min"               	=> "-10",
				"max"               	=> "10",
				"step"              	=> "1",
				"unit"              	=> 'px',
				"description"       	=> __( "Due to the combination of prestring, title strings, and post string, the title string can appear with a slight offset. Use the control above to adjust its position, if necessary.", "ts_visual_composer_extend" ),
				"group"					=> "Style Settings"
			),
			// Title Sections
			array(
				"type"              	=> "seperator",
				"param_name"        	=> "seperator_4",
				"seperator"				=> "Title Styling",
				"group"					=> "Style Settings"
			),
			array(
				"type"              	=> "colorpicker",
				"heading"           	=> __( "Font Color", "ts_visual_composer_extend" ),
				"param_name"        	=> "font_color",
				"value"             	=> "#000000",
				"edit_field_class"		=> "vc_col-sm-6 vc_column",
				"group"					=> "Style Settings"
			),
			array(
				"type"              	=> "dropdown",
				"heading"           	=> __( "Font Weight", "ts_visual_composer_extend" ),
				"param_name"        	=> "font_weight",
				"width"             	=> 150,
				"value"             	=> array(
					__( 'Default', "ts_visual_composer_extend" )  => "inherit",
					__( 'Bold', "ts_visual_composer_extend" )     => "bold",
					__( 'Bolder', "ts_visual_composer_extend" )   => "bolder",
					__( 'Normal', "ts_visual_composer_extend" )   => "normal",
					__( 'Light', "ts_visual_composer_extend" )    => "300",
				),
				"edit_field_class"		=> "vc_col-sm-6 vc_column",
				"group"					=> "Style Settings"
			),
			array(
				"type"              	=> "fontsmanager",
				"heading"           	=> __( "Font Family", "ts_visual_composer_extend" ),
				"param_name"        	=> "font_family",
				"value"             	=> "",
				"default"				=> "true",
				"connector"				=> "font_type",
				"group"					=> "Style Settings"
			),
			array(
				"type"              	=> "hidden_input",
				"param_name"        	=> "font_type",
				"value"             	=> "",
				"group"					=> "Style Settings"
			),
			array(
				"type"              	=> "nouislider",
				"heading"           	=> __( "Font Size", "ts_visual_composer_extend" ),
				"param_name"        	=> "font_size",
				"value"             	=> "24",
				"min"               	=> "24",
				"max"               	=> "72",
				"step"              	=> "1",
				"unit"              	=> 'px',
				"group"					=> "Style Settings"
			),
			// Prefix String
			array(
				"type"              	=> "seperator",
				"param_name"        	=> "seperator_5",
				"seperator"				=> "Prefix Styling",
				"dependency"        	=> array( 'element' => "fixed_addition", 'value' => 'true' ),
				"group"					=> "Style Settings"
			),
			array(
				"type"              	=> "switch_button",
				"heading"               => __( "Prefix: Customize", "ts_visual_composer_extend" ),
				"param_name"            => "fixed_custom",
				"value"                 => "false",
				"description"           => __( "Switch the toggle if you want to style the prefix string to be different then the title strings.", "ts_visual_composer_extend" ),
				"dependency"        	=> array( 'element' => "fixed_addition", 'value' => 'true' ),
				"group"					=> "Style Settings"
			),
			array(
				"type"              	=> "colorpicker",
				"heading"           	=> __( "Prefix: Font Color", "ts_visual_composer_extend" ),
				"param_name"        	=> "fixed_color",
				"value"             	=> "#000000",
				"edit_field_class"		=> "vc_col-sm-6 vc_column",
				"dependency"        	=> array( 'element' => "fixed_custom", 'value' => "true" ),
				"group"					=> "Style Settings"
			),
			array(
				"type"              	=> "dropdown",
				"heading"           	=> __( "Prefix: Font Weight", "ts_visual_composer_extend" ),
				"param_name"        	=> "fixed_weight",
				"width"             	=> 150,
				"value"             	=> array(
					__( 'Default', "ts_visual_composer_extend" )  => "inherit",
					__( 'Bold', "ts_visual_composer_extend" )     => "bold",
					__( 'Bolder', "ts_visual_composer_extend" )   => "bolder",
					__( 'Normal', "ts_visual_composer_extend" )   => "normal",
					__( 'Light', "ts_visual_composer_extend" )    => "300",
				),
				"edit_field_class"		=> "vc_col-sm-6 vc_column",
				"dependency"        	=> array( 'element' => "fixed_custom", 'value' => "true" ),
				"group"					=> "Style Settings"
			),
			array(
				"type"              	=> "fontsmanager",
				"heading"           	=> __( "Prefix: Font Family", "ts_visual_composer_extend" ),
				"param_name"        	=> "fixed_family",
				"value"             	=> "",
				"default"				=> "true",
				"connector"				=> "fixed_type",
				"dependency"        	=> array( 'element' => "fixed_custom", 'value' => "true" ),
				"group"					=> "Style Settings"
			),
			array(
				"type"              	=> "hidden_input",
				"param_name"        	=> "fixed_type",
				"value"             	=> "",
				"dependency"        	=> array( 'element' => "fixed_custom", 'value' => "true" ),
				"group"					=> "Style Settings"
			),
			array(
				"type"              	=> "nouislider",
				"heading"           	=> __( "Prefix: Font Size", "ts_visual_composer_extend" ),
				"param_name"        	=> "fixed_size",
				"value"             	=> "75",
				"min"               	=> "50",
				"max"               	=> "100",
				"step"              	=> "1",
				"unit"              	=> '%',
				"dependency"        	=> array( 'element' => "fixed_custom", 'value' => "true" ),
				"description"           => __( "Define the font size as a percentage of the font size used for the main title strings.", "ts_visual_composer_extend" ),
				"group"					=> "Style Settings"
			),
			// Postfix String
			array(
				"type"              	=> "seperator",
				"param_name"        	=> "seperator_6",
				"seperator"				=> "Postfix Styling",
				"dependency"        	=> array( 'element' => "post_addition", 'value' => 'true' ),
				"group"					=> "Style Settings"
			),
			array(
				"type"              	=> "switch_button",
				"heading"               => __( "Postfix: Customize", "ts_visual_composer_extend" ),
				"param_name"            => "post_custom",
				"value"                 => "false",
				"description"           => __( "Switch the toggle if you want to style the postfix string to be different then the title strings.", "ts_visual_composer_extend" ),
				"dependency"        	=> array( 'element' => "post_addition", 'value' => 'true' ),
				"group"					=> "Style Settings"
			),
			array(
				"type"              	=> "colorpicker",
				"heading"           	=> __( "Postfix: Font Color", "ts_visual_composer_extend" ),
				"param_name"        	=> "post_color",
				"value"             	=> "#000000",
				"edit_field_class"		=> "vc_col-sm-6 vc_column",
				"dependency"        	=> array( 'element' => "post_custom", 'value' => "true" ),
				"group"					=> "Style Settings"
			),
			array(
				"type"              	=> "dropdown",
				"heading"           	=> __( "Postfix: Font Weight", "ts_visual_composer_extend" ),
				"param_name"        	=> "post_weight",
				"width"             	=> 150,
				"value"             	=> array(
					__( 'Default', "ts_visual_composer_extend" )  => "inherit",
					__( 'Bold', "ts_visual_composer_extend" )     => "bold",
					__( 'Bolder', "ts_visual_composer_extend" )   => "bolder",
					__( 'Normal', "ts_visual_composer_extend" )   => "normal",
					__( 'Light', "ts_visual_composer_extend" )    => "300",
				),
				"edit_field_class"		=> "vc_col-sm-6 vc_column",
				"dependency"        	=> array( 'element' => "post_custom", 'value' => "true" ),
				"group"					=> "Style Settings"
			),
			array(
				"type"              	=> "fontsmanager",
				"heading"           	=> __( "Postfix: Font Family", "ts_visual_composer_extend" ),
				"param_name"        	=> "post_family",
				"value"             	=> "",
				"default"				=> "true",
				"connector"				=> "post_type",
				"dependency"        	=> array( 'element' => "post_custom", 'value' => "true" ),
				"group"					=> "Style Settings"
			),
			array(
				"type"              	=> "hidden_input",
				"param_name"        	=> "post_type",
				"value"             	=> "",
				"dependency"        	=> array( 'element' => "post_custom", 'value' => "true" ),
				"group"					=> "Style Settings"
			),
			array(
				"type"              	=> "nouislider",
				"heading"           	=> __( "Postfix: Font Size", "ts_visual_composer_extend" ),
				"param_name"        	=> "post_size",
				"value"             	=> "75",
				"min"               	=> "50",
				"max"               	=> "100",
				"step"              	=> "1",
				"unit"              	=> '%',
				"dependency"        	=> array( 'element' => "post_custom", 'value' => "true" ),
				"description"           => __( "Define the font size as a percentage of the font size used for the main title strings.", "ts_visual_composer_extend" ),
				"group"					=> "Style Settings"
			),
			// Ticker Settings
			array(
				"type"              	=> "seperator",
				"param_name"        	=> "seperator_7",
				"seperator"				=> "Ticker Settings",
				"group" 				=> "Ticker Settings",
			),
			array(
				"type"              	=> "switch_button",
				"heading"               => __( "Trigger on Viewport", "ts_visual_composer_extend" ),
				"param_name"            => "viewport",
				"value"                 => "true",
				"admin_label"			=> true,
				"description"           => __( "Switch the toggle if you want the animation to be triggered upon viewport entry.", "ts_visual_composer_extend" ),
				"group" 				=> "Ticker Settings",
			),
			array(
				"type"                  => "nouislider",
				"heading"               => __( "Start Delay", "ts_visual_composer_extend" ),
				"param_name"            => "delay",
				"value"                 => "0",
				"min"                   => "0",
				"max"                   => "10000",
				"step"                  => "100",
				"unit"                  => 'ms',
				"description"           => __( "Define the start delay before the animation begins with the first segment.", "ts_visual_composer_extend" ),
				"group" 				=> "Ticker Settings",
			),
			array(
				"type"                  => "nouislider",
				"heading"               => __( "Speed", "ts_visual_composer_extend" ),
				"param_name"            => "speed",
				"value"                 => "700",
				"min"                   => "0",
				"max"                   => "100000",
				"step"                  => "100",
				"unit"                  => 'ms',
				"admin_label"			=> true,
				"description"           => __( "Define the ticker speed for each segment; the higher the value, the slower.", "ts_visual_composer_extend" ),
				"group" 				=> "Ticker Settings",
			),
			array(
				"type"                  => "nouislider",
				"heading"               => __( "Segment Delay", "ts_visual_composer_extend" ),
				"param_name"            => "break",
				"value"                 => "3000",
				"min"                   => "1000",
				"max"                   => "10000",
				"step"                  => "100",
				"unit"                  => 'ms',
				"description"           => __( "Define the delay before the animation moves to the next segment.", "ts_visual_composer_extend" ),
				"group" 				=> "Ticker Settings",
			),		
			array(
				"type"              	=> "switch_button",
				"heading"               => __( "Stop on Hover", "ts_visual_composer_extend" ),
				"param_name"            => "hover",
				"value"                 => "true",
				"admin_label"			=> true,
				"description"           => __( "Switch the toggle if you want to stop the animation while hovering over the title.", "ts_visual_composer_extend" ),
				"group" 				=> "Ticker Settings",
			),
			array(
				"type"              	=> "switch_button",
				"heading"               => __( "Ticker Controls", "ts_visual_composer_extend" ),
				"param_name"            => "controls",
				"value"                 => "false",
				"admin_label"			=> true,
				"edit_field_class"		=> "vc_col-sm-6 vc_column",
				"description"           => __( "Switch the toggle if you want to provide controls for the ticker.", "ts_visual_composer_extend" ),
				"group" 				=> "Ticker Settings",
			),
			array(
				"type"					=> "image_selector",
				"heading"           	=> __( "Controls Position", "ts_visual_composer_extend" ),
				"param_name"        	=> "position",
				"template"				=> "positions",
				"value"					=> "left",
				"edit_field_class"		=> "vc_col-sm-6 vc_column",
				"description"       	=> __( "Select the where the ticker controls should be positioned in relation to the title.", "ts_visual_composer_extend" ),
				"dependency"        	=> array( 'element' => "controls", 'value' => 'true' ),
				"group" 				=> "Ticker Settings",
			),
			// Breakpoint Settings
			array(
				"type"              	=> "seperator",
				"param_name"        	=> "seperator_8",
				"seperator"             => "Breakpoint Settings",
				"group" 				=> "Ticker Settings",
			),
			array(
				"type"                  => "nouislider",
				"heading"               => __( "Medium Break", "ts_visual_composer_extend" ),
				"param_name"            => "switch_medium",
				"value"                 => "768",
				"min"                   => "481",
				"max"                   => "1240",
				"step"                  => "1",
				"unit"                  => 'px',
				"description"           => __( "Define the ticker width at which the font size should scale to 75% of the selected value.", "ts_visual_composer_extend" ),
				"group" 				=> "Ticker Settings",
			),		
			array(
				"type"                  => "nouislider",
				"heading"               => __( "Small Break", "ts_visual_composer_extend" ),
				"param_name"            => "switch_small",
				"value"                 => "480",
				"min"                   => "240",
				"max"                   => "480",
				"step"                  => "1",
				"unit"                  => 'px',
				"description"           => __( "Define the ticker width at which the font size should scale to 50% of the selected value.", "ts_visual_composer_extend" ),
				"group" 				=> "Ticker Settings",
			),
			// Other Settings
			array(
				"type"              	=> "seperator",
				"param_name"        	=> "seperator_9",
				"seperator"             => "Other Settings",
				"group" 				=> "Other Settings",
			),
			array(
				"type"              	=> "nouislider",
				"heading"           	=> __( "Margin: Top", "ts_visual_composer_extend" ),
				"param_name"        	=> "margin_top",
				"value"             	=> "0",
				"min"               	=> "-50",
				"max"               	=> "200",
				"step"              	=> "1",
				"unit"              	=> 'px',
				"description"       	=> __( "Select the top margin for the element.", "ts_visual_composer_extend" ),
				"group" 				=> "Other Settings",
			),
			array(
				"type"              	=> "nouislider",
				"heading"           	=> __( "Margin: Bottom", "ts_visual_composer_extend" ),
				"param_name"        	=> "margin_bottom",
				"value"             	=> "0",
				"min"               	=> "-50",
				"max"               	=> "200",
				"step"              	=> "1",
				"unit"              	=> 'px',
				"description"       	=> __( "Select the bottom margin for the element.", "ts_visual_composer_extend" ),
				"group" 				=> "Other Settings",
			),
			array(
				"type"              	=> "textfield",
				"heading"           	=> __( "Define ID Name", "ts_visual_composer_extend" ),
				"param_name"        	=> "el_id",
				"value"             	=> "",
				"description"       	=> __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
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