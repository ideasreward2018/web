<?php
    global $VISUAL_COMPOSER_EXTENSIONS;	
    $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
		"name"                      => __( "TS QR-Code", "ts_visual_composer_extend" ),
		"base"                      => "TS-VCSC-QRCode",
		"icon" 	                    => "ts-composer-element-icon-qrcode",
		"category"                  => __( "VC Extensions", "ts_visual_composer_extend" ),
		"description"               => __("Place a QR-Code block element", "ts_visual_composer_extend"),
		"admin_enqueue_js"        	=> "",
		"admin_enqueue_css"       	=> "",
		"params"                    => array(
			// QR-Code Settings
			array(
				"type"              => "seperator",
				"param_name"        => "seperator_1",
				"seperator"			=> "QR-Code Settings",
			),
			array(
				"type"              => "dropdown",
				"heading"           => __( "Render Element", "ts_visual_composer_extend" ),
				"param_name"        => "render",
				"width"             => 150,
				"value"             => array(
					__( 'Canvas', "ts_visual_composer_extend" )		=> "canvas",
					__( 'Image', "ts_visual_composer_extend" )		=> "image",
					__( 'DIV', "ts_visual_composer_extend" )			=> "div",
				),
				"description"       => __( "Select as what kind of element the QR-Block should be rendered.", "ts_visual_composer_extend" )
			),
			
			array(
				"type"              => "colorpicker",
				"heading"           => __( "Code Color", "ts_visual_composer_extend" ),
				"param_name"        => "color",
				"value"             => "#000000",
				"description"       => __( "Define the color of the QR-Code block.", "ts_visual_composer_extend" ),
			),
			array(
				"type"				=> "switch_button",
				"heading"           => __( "Responsive QR-Code", "ts_visual_composer_extend" ),
				"param_name"        => "responsive",
				"value"             => "false",
				"description"       => __( "Switch the toggle if you want the QR-Block element to be responsive.", "ts_visual_composer_extend" )
			),
			array(
				"type"              => "nouislider",
				"heading"           => __( "QR-Code Size", "ts_visual_composer_extend" ),
				"param_name"        => "size_r",
				"value"             => "100",
				"min"               => "10",
				"max"               => "100",
				"step"              => "1",
				"unit"              => "%",
				"description"       => __( "Define the responsive size of the QR-Code block.", "ts_visual_composer_extend" ),
				"dependency"        => array( 'element' => "responsive", 'value' => 'true' )
			),
			array(
				"type"              => "nouislider",
				"heading"           => __( "QR-Code Min-Size", "ts_visual_composer_extend" ),
				"param_name"        => "size_min",
				"value"             => "100",
				"min"               => "50",
				"max"               => "1024",
				"step"              => "1",
				"unit"              => "px",
				"description"       => __( "Define the minimum size of the QR-Code block.", "ts_visual_composer_extend" ),
				"dependency"        => array( 'element' => "responsive", 'value' => 'true' )
			),
			array(
				"type"              => "nouislider",
				"heading"           => __( "QR-Code Max-Size", "ts_visual_composer_extend" ),
				"param_name"        => "size_max",
				"value"             => "400",
				"min"               => "50",
				"max"               => "1024",
				"step"              => "1",
				"unit"              => "px",
				"description"       => __( "Define the maximum size of the QR-Code block.", "ts_visual_composer_extend" ),
				"dependency"        => array( 'element' => "responsive", 'value' => 'true' )
			),
			array(
				"type"              => "nouislider",
				"heading"           => __( "QR-Code Size", "ts_visual_composer_extend" ),
				"param_name"        => "size_f",
				"value"             => "100",
				"min"               => "50",
				"max"               => "1024",
				"step"              => "1",
				"unit"              => "px",
				"description"       => __( "Define the fixed size of the QR-Code block.", "ts_visual_composer_extend" ),
				"dependency"        => array( 'element' => "responsive", 'value' => 'false' )
			),
			array(
				"type"              => "textfield",
				"heading"           => __( "Encoded Text", "ts_visual_composer_extend" ),
				"param_name"        => "value",
				"value"             => "",
				"admin_label"       => true,
				"description"       => __( "Enter the text (i.e. URL, Email Address) that should be encoded as QR-Block.", "ts_visual_composer_extend" )
			),
			// Other Settings
			array(
				"type"              => "seperator",
				"param_name"        => "seperator_2",
				"seperator"			=> "Other Settings",
				"group" 			=> "Other Settings",
			),
			array(
				"type"              => "nouislider",
				"heading"           => __( "Margin: Top", "ts_visual_composer_extend" ),
				"param_name"        => "margin_top",
				"value"             => "0",
				"min"               => "-50",
				"max"               => "200",
				"step"              => "1",
				"unit"              => 'px',
				"description"       => __( "Select the top margin for the element.", "ts_visual_composer_extend" ),
				"group" 			=> "Other Settings",
			),
			array(
				"type"              => "nouislider",
				"heading"           => __( "Margin: Bottom", "ts_visual_composer_extend" ),
				"param_name"        => "margin_bottom",
				"value"             => "0",
				"min"               => "-50",
				"max"               => "200",
				"step"              => "1",
				"unit"              => 'px',
				"description"       => __( "Select the bottom margin for the element.", "ts_visual_composer_extend" ),
				"group" 			=> "Other Settings",
			),
			array(
				"type"              => "textfield",
				"heading"           => __( "Define ID Name", "ts_visual_composer_extend" ),
				"param_name"        => "el_id",
				"value"             => "",
				"description"       => __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
				"group" 			=> "Other Settings",
			),
			array(
				"type"				=> "tag_editor",
				"heading"			=> __( "Extra Class Names", "ts_visual_composer_extend" ),
				"param_name"		=> "el_class",
				"value"				=> "",
				"description"		=> __( "Enter additional class names for the element.", "ts_visual_composer_extend" ),
				"group"				=> "Other Settings",
			),
		)
	);	
	if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
		return $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element;
	} else {			
		vc_map($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element);
	};
?>