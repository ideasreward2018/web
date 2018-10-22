<?php
    global $VISUAL_COMPOSER_EXTENSIONS;
	if ((class_exists('WPBakeryShortCode')) && (!class_exists('WPBakeryShortCode_TS_VCSC_Preloaders'))) {
		class WPBakeryShortCode_TS_VCSC_Preloaders extends WPBakeryShortCode {};
	};
    $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
		"name"                      	=> __( "TS Preloader Animations", "ts_visual_composer_extend" ),
		"base"                      	=> "TS_VCSC_Preloaders",
		"icon" 	                    	=> "ts-composer-element-icon-demo-elements",
		"category"                  	=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorElementFilter == "true" ? __( "VC Extensions", "ts_visual_composer_extend" ) : __( 'VC Demos', "ts_visual_composer_extend" )),
		"description"               	=> __("Place a preview of preloader animations", "ts_visual_composer_extend"),
		"admin_enqueue_js"        		=> "",
		"admin_enqueue_css"       		=> "",
		"params"                    	=> array(
			array(
				"type"              	=> "messenger",
				"param_name"        	=> "messenger",
				"color"					=> "#006BB7",
				"size"					=> "14",
				"message"            	=> __( "This element will display a preview of a selected preloader animation.", "ts_visual_composer_extend" ),
			),
			array(
				"type"					=> "livepreview",
				"heading"				=> __( "Preloader Style", "ts_visual_composer_extend" ),
				"param_name"			=> "preloader",
				"preview"				=> "preloaders",
				"value"					=> 0,
				"admin_label"       	=> true,
				"description"			=> __( "Select the style for the preloader animation you want to preview.", "ts_visual_composer_extend" ),
			),
		)
	);	
	if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
		return $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element;
	} else {	
		vc_map($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element);
	};
?>