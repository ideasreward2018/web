<?php
    global $VISUAL_COMPOSER_EXTENSIONS;	
    $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
		"name"                      => __( "TS Spacer / Clear", "ts_visual_composer_extend" ),
		"base"                      => "TS-VCSC-Spacer",
		"icon" 	                    => "ts-composer-element-icon-spacer",
		"category"                  => __( "VC Extensions", "ts_visual_composer_extend" ),
		"description"               => __("Place a spacer / clear element", "ts_visual_composer_extend"),
		"admin_enqueue_js"        	=> "",
		"admin_enqueue_css"       	=> "",
		"params"                    => array(
			// Spacer Settings
			array(
				"type"              => "seperator",
				"param_name"        => "seperator_1",
				"seperator"         => "Spacer Dimensions",
			),			
			array(
				"type"              => "dropdown",
				"heading"           => __( "Height Criteria", "ts_visual_composer_extend" ),
				"param_name"        => "implement",
				"width"             => 300,
				"value"             => array(
					__( "Same Height on all Device Types", "ts_visual_composer_extend" )		=> "always",
					__( "Different Height based on Device Type", "ts_visual_composer_extend" )	=> "devices",
					__( "Different Height based on Screen Size", "ts_visual_composer_extend" )	=> "screens",
				),
				"admin_label"		=> true,
				"description"       => __( "Select the type of spacer that should be created.", "ts_visual_composer_extend" ),
			),			
			array(
				"type"              => "nouislider",
				"heading"           => __( "Fixed Height", "ts_visual_composer_extend" ),
				"param_name"        => "height",
				"value"             => "10",
				"min"               => "0",
				"max"               => "500",
				"step"              => "1",
				"unit"              => 'px',
				"admin_label"		=> true,
				"description"       => __( "Define the fixed height to be maintained on all device types.", "ts_visual_composer_extend" ),
				"dependency"        => array( 'element' => "implement", 'value' => 'always' )
			),			
			array(
				"type"				=> "switch_button",
				"heading"           => __( "Screen Width Dependency", "ts_visual_composer_extend" ),
				"param_name"        => "screen_check",
				"value"             => "false",
				"description"       => __( "Switch the toggle if the spacer should also be made dependent upon the overall screen width.", "ts_visual_composer_extend" ),
				"dependency"        => array( 'element' => "implement", 'value' => 'always' )
			),
			array(
				"type"              => "nouislider",
				"heading"           => __( "Minimum Screen Width", "ts_visual_composer_extend" ),
				"param_name"        => "screen_width",
				"value"             => "1024",
				"min"               => "360",
				"max"               => "1980",
				"step"              => "1",
				"unit"              => 'px',
				"description"       => __( "Define the minimum screen width that is required for the spacer to be active.", "ts_visual_composer_extend" ),
				"dependency"        => array( 'element' => "screen_check", 'value' => 'true' )
			),
			array(
				"type" 				=> "devicetype_selectors",
				"heading"           => __( "Device Type Heights", "ts_visual_composer_extend" ),
				"param_name"        => "height_devices",
				"unit"  			=> "px",
				"collapsed"			=> "true",
				"devices" 			=> array(
					"Desktop"           	=> array("default" => 10, "min" => 0, "max" => 500, "step" => 1),					
					"Tablet Portrait"       => array("default" => 10, "min" => 0, "max" => 500, "step" => 1),
					"Tablet Landscape"		=> array("default" => 10, "min" => 0, "max" => 500, "step" => 1),
					"Mobile Portrait"		=> array("default" => 10, "min" => 0, "max" => 500, "step" => 1),
					"Mobile Landscape"		=> array("default" => 10, "min" => 0, "max" => 500, "step" => 1),
				),
				"admin_label"		=> true,
				"value"				=> "desktop:10px;tablet_portrait:10px;tablet_landscape:10px;mobile_portrait:10px;mobile_landscape:10px;",
				"description"		=> __( "Define different spacer heights to be used for the individual device types.", "ts_visual_composer_extend" ),
				"dependency"        => array( 'element' => "implement", 'value' => 'devices' )
			),
			array(
				"type" 				=> "screensizes_selectors",
				"heading"           => __( "Screen Size Heights", "ts_visual_composer_extend" ),
				"param_name"        => "height_screens",
				"unit"  			=> "px",
				"collapsed"			=> "true",
				"devices" 			=> array(
					"Extra Large"           => array("default" => 10, "min" => 0, "max" => 500, "step" => 1),					
					"Large"       			=> array("default" => 10, "min" => 0, "max" => 500, "step" => 1),
					"Medium"				=> array("default" => 10, "min" => 0, "max" => 500, "step" => 1),
					"Small"					=> array("default" => 10, "min" => 0, "max" => 500, "step" => 1),
					"Extra Small"			=> array("default" => 10, "min" => 0, "max" => 500, "step" => 1),
				),
				"admin_label"		=> true,
				"value"				=> "extra_large:10px;large:10px;medium:10px;small:10px;extra_small:10px;",
				"description"		=> __( "Define different spacer heights to be used for the individual screen sizes.", "ts_visual_composer_extend" ),
				"dependency"        => array( 'element' => "implement", 'value' => 'screens' )
			),
			array(
				"type"				=> "switch_button",
				"heading"           => __( "Screen Width Dependency", "ts_visual_composer_extend" ),
				"param_name"        => "screen_invert",
				"value"             => "true",
				"description"       => __( "Switch the toggle if the spacer height should depend on detected screen width, or rather the width of the spacer element itself.", "ts_visual_composer_extend" ),
				"dependency"        => array( 'element' => "implement", 'value' => 'screens' )
			),
			// Other Conditionals
			array(
				"type"              => "seperator",
				"param_name"        => "seperator_2",
				"seperator"			=> "Output Conditions",
			),
			array(
				"type"              => "ts_conditionals",
				"heading"			=> __( "Output Conditions", "ts_visual_composer_extend" ),
				"param_name"        => "conditionals",
			),
		)
	);	
	if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
		return $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element;
	} else {			
		vc_map($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element);
	};
?>