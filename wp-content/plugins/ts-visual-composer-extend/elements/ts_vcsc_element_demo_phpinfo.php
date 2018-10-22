<?php
    global $VISUAL_COMPOSER_EXTENSIONS;
	if ((class_exists('WPBakeryShortCode')) && (!class_exists('WPBakeryShortCode_TS_VCSC_PHPInfo_Summary'))) {
		class WPBakeryShortCode_TS_VCSC_PHPInfo_Summary extends WPBakeryShortCode {};
	};
	$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
		"name"                      	=> __( "TS PHP Info Summary", "ts_visual_composer_extend" ),
		"base"                      	=> "TS_VCSC_PHPInfo_Summary",
		"icon" 	                    	=> "ts-composer-element-icon-demo-elements",
		"category"                  	=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorElementFilter == "true" ? __( "VC Extensions", "ts_visual_composer_extend" ) : __( 'VC Demos', "ts_visual_composer_extend" )),
		"description"               	=> __("Place a summary of PHP server information", "ts_visual_composer_extend"),
		"show_settings_on_create" 		=> false,
		"admin_enqueue_js"        		=> "",
		"admin_enqueue_css"       		=> "",
		"params"                    	=> array(
			array(
				"type"              	=> "messenger",
				"param_name"        	=> "messenger",
				"color"					=> "#006BB7",
				"size"					=> "14",
				"message"            	=> __( "This element will display the information returned by the phpinfo() server function.", "ts_visual_composer_extend" ),
			),
		)
	);	
	if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
		return $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element;
	} else {			
		vc_map($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element);
	};
?>