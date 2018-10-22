<?php
    global $VISUAL_COMPOSER_EXTENSIONS;
	if ((class_exists('WPBakeryShortCode')) && (!class_exists('WPBakeryShortCode_TS_VCSC_Content_Export'))) {
		class WPBakeryShortCode_TS_VCSC_Content_Export extends WPBakeryShortCode {};
	};
	$TS_VCSC_Export_Pages 				= array();
	$TS_VCSC_Export_Posts 				= array();
	$TS_VCSC_WordPress_Pages			= get_pages(array('post_type' => 'page'));	
	foreach ($TS_VCSC_WordPress_Pages as $page) {
		$TS_VCSC_Export_Pages[$page->post_title . ' (' . $page->ID . ')'] = $page->ID;
	}
	unset($TS_VCSC_WordPress_Pages);
	$TS_VCSC_WordPress_Posts			= get_posts(array('posts_per_page' => -1, 'post_type' => 'post'));
	foreach ($TS_VCSC_WordPress_Posts as $post) {
		$TS_VCSC_Export_Posts[$post->post_title . ' (' . $post->ID . ')'] = $post->ID;
	}
	unset($TS_VCSC_WordPress_Posts);
    $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
		"name"                      	=> __( "TS Page Content Export", "ts_visual_composer_extend" ),
		"base"                      	=> "TS_VCSC_Content_Export",
		"icon" 	                    	=> "ts-composer-element-icon-demo-elements",
		"category"                  	=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorElementFilter == "true" ? __( "VC Extensions", "ts_visual_composer_extend" ) : __( 'VC Demos', "ts_visual_composer_extend" )),
		"description"               	=> __("Place the unprocessed page or post output for export", "ts_visual_composer_extend"),
		"admin_enqueue_js"        		=> "",
		"admin_enqueue_css"       		=> "",
		"params"                    	=> array(
			array(
				"type"              	=> "messenger",
				"param_name"        	=> "messenger",
				"color"					=> "#006BB7",
				"size"					=> "14",
				"message"            	=> __( "This element will output the unprocessed (shortcode based) content for the selected page or post.", "ts_visual_composer_extend" ),
			),
			array(
				"type"              	=> "dropdown",
				"heading"           	=> __( "Content Source", "ts_visual_composer_extend" ),
				"param_name"        	=> "target",
				"width"             	=> 150,
				"admin_label"       	=> true,
				"value"             	=> array(
					__( "Current Page or Post", "ts_visual_composer_extend" )			=> "current",
					__( "Specific Page", "ts_visual_composer_extend" )					=> "page",
					__( "Specific Post", "ts_visual_composer_extend" )					=> "post",
				),
			),			
			array(
				"type"              	=> "dropdown",
				"heading"           	=> __( "Select Page", "ts_visual_composer_extend" ),
				"param_name"        	=> "pageid",
				"width"             	=> 150,
				"admin_label"       	=> true,
				"value"             	=> $TS_VCSC_Export_Pages,
				"dependency"        	=> array( 'element' => "target", 'value' => 'page' )
			),
			array(
				"type"              	=> "dropdown",
				"heading"           	=> __( "Select Post", "ts_visual_composer_extend" ),
				"param_name"        	=> "postid",
				"width"             	=> 150,
				"admin_label"       	=> true,
				"value"             	=> $TS_VCSC_Export_Posts,
				"dependency"        	=> array( 'element' => "target", 'value' => 'post' )
			),
			array(
				"type"					=> "switch_button",
				"heading"           	=> __( "Toggle: Show/Hide", "ts_visual_composer_extend" ),
				"param_name"        	=> "toggle_switch",
				"value"             	=> "false",
				"description"       	=> __( "Switch the toggle if you want to provide a toggle to show or collapse the page export output.", "ts_visual_composer_extend" ),
			),
			array(
				"type"              	=> "colorpicker",
				"heading"           	=> __( "Toggle: Font Color", "ts_visual_composer_extend" ),
				"param_name"        	=> "toggle_color",
				"value"            	 	=> "#f6f6f6",
				"description"			=> __( "Define the font color for the toggle control.", "ts_visual_composer_extend" ),
				"edit_field_class"		=> "vc_col-sm-6 vc_column",
				"dependency"        	=> array( 'element' => "toggle_switch", 'value' => 'true' )
			),
			array(
				"type"              	=> "colorpicker",
				"heading"           	=> __( "Toggle: Background Color", "ts_visual_composer_extend" ),
				"param_name"        	=> "toggle_background",
				"value"            	 	=> "#2ac4ea",
				"description"			=> __( "Define the background color for the toggle control.", "ts_visual_composer_extend" ),
				"edit_field_class"		=> "vc_col-sm-6 vc_column",
				"dependency"        	=> array( 'element' => "toggle_switch", 'value' => 'true' )
			),
			array(
				"type"					=> "textfield",
				"heading"				=> __( "Toggle: String", "ts_visual_composer_extend" ),
				"param_name"			=> "string_toggle",
				"value"					=> "Export Page Content",
				"description"			=> __( "Define the text string to be used for the toggle header.", "ts_visual_composer_extend" ),
				"dependency"        	=> array( 'element' => "toggle_switch", 'value' => 'true' )
			),
			array(
				"type"					=> "switch_button",
				"heading"           	=> __( "Clipboard: Button", "ts_visual_composer_extend" ),
				"param_name"        	=> "clipboard",
				"value"             	=> "false",
				"description"       	=> __( "Switch the toggle if you want to provide a button to copy the page export into the clipboard.", "ts_visual_composer_extend" ),
			),
			array(
				"type"					=> "textfield",
				"heading"				=> __( "Clipboard: String", "ts_visual_composer_extend" ),
				"param_name"			=> "string_copy",
				"value"					=> "Copy",
				"description"			=> __( "Define the text string to be used for the clipboard button.", "ts_visual_composer_extend" ),
				"dependency"        	=> array( 'element' => "clipboard", 'value' => 'true' )
			),	
			array(
				"type"					=> "textfield",
				"heading"				=> __( "Clipboard: Success", "ts_visual_composer_extend" ),
				"param_name"			=> "string_success",
				"value"					=> "The content for this page or post has been copied to your clipboard!",
				"description"			=> __( "Define the text string to be used for the success message after copying.", "ts_visual_composer_extend" ),
				"dependency"        	=> array( 'element' => "clipboard", 'value' => 'true' )
			),
			array(
				"type"					=> "textfield",
				"heading"				=> __( "Clipboard: Error", "ts_visual_composer_extend" ),
				"param_name"			=> "string_error",
				"value"					=> "The content for this page or post could NOT be copied to your clipboard!",
				"description"			=> __( "Define the text string to be used for the error message if copying fails.", "ts_visual_composer_extend" ),
				"dependency"        	=> array( 'element' => "clipboard", 'value' => 'true' )
			),	
		)
	);	
	if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
		return $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element;
	} else {	
		vc_map($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element);
	};
?>