<?php
    global $VISUAL_COMPOSER_EXTENSIONS;
	if ((class_exists('WPBakeryShortCode')) && (!class_exists('WPBakeryShortCode_TS_VCSC_System_Information'))) {
		class WPBakeryShortCode_TS_VCSC_System_Information extends WPBakeryShortCode {};
	};
	$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
		"name"                      	=> __( "TS System Information", "ts_visual_composer_extend" ),
		"base"                      	=> "TS_VCSC_System_Information",
		"icon" 	                    	=> "ts-composer-element-icon-demo-elements",
		"category"                  	=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorElementFilter == "true" ? __( "VC Extensions", "ts_visual_composer_extend" ) : __( 'VC Demos', "ts_visual_composer_extend" )),
		"description"               	=> __("Place a summary of system information", "ts_visual_composer_extend"),
		"show_settings_on_create" 		=> false,
		"admin_enqueue_js"        		=> "",
		"admin_enqueue_css"       		=> "",
		"params"                    	=> array(
			array(
				"type"              	=> "messenger",
				"param_name"        	=> "messenger",
				"color"					=> "#006BB7",
				"size"					=> "14",
				"message"            	=> __( "This element will display a summary of system information as they relate to your specific WordPress install and server setup.", "ts_visual_composer_extend" ),
			),
		)
	);	
	if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
		return $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element;
	} else {			
		vc_map($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element);
	};
?>