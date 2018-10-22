<?php
    global $VISUAL_COMPOSER_EXTENSIONS;	
    $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
		"name"                      => __( "TS Shortcode", "ts_visual_composer_extend" ),
		"base"                      => "TS-VCSC-Shortcode",
		"icon" 	                    => "ts-composer-element-icon-shortcode",
		"category"                  => __( "VC Extensions", "ts_visual_composer_extend" ),
		"description"               => __("Place any shortcode in your page", "ts_visual_composer_extend"),
		"admin_enqueue_js"        	=> "",
		"admin_enqueue_css"       	=> "",
		"params"                    => array(
			// Shortcode Settings
			array(
				"type"              => "seperator",
				"param_name"        => "seperator_1",
				"seperator"			=> "Shortcode Input",
			),
			array(
				"type"              => "textarea_raw_html",
				"heading"           => __( "Shortcode", "ts_visual_composer_extend" ),
				"param_name"        => "tscode",
				"value"             => base64_encode(""),
				"description"       => __( "Enter the shortcode with its full syntax here.", "ts_visual_composer_extend" ),
			),
			array(
				"type"				=> "hidden_textarea",
				"heading"			=> __( "Shortcode Key", "ts_visual_composer_extend" ),
				"param_name"		=> "tscodenormal",
				"value"				=> "",
				"admin_label"		=> true,
			),
		)
	);	
	if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
		return $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element;
	} else {			
		vc_map($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element);
	};
?>