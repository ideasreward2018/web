<?php
    global $VISUAL_COMPOSER_EXTENSIONS;

	$TS_VCSC_QuForms_List 			= array();
	$TS_VCSC_QuForms_Name			= __( "Quform", "ts_visual_composer_extend" );
	$TS_VCSC_QuForms_Base			= 'iphorm';
	
	// Retrieve QuForm Forms
	if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_QuFormReleaseTypeUse == "function") {
		// QuForm 1.x
		$TS_VCSC_QuForms_Name		= $TS_VCSC_QuForms_Name . " 1.x";
		$TS_VCSC_QuForms_Base		= "iphorm";
		$TS_VCSC_QuForms_Data		= iphorm_get_all_forms();			
		foreach ($TS_VCSC_QuForms_Data as $form) {
			$formID 				= $form['id'];
			$formName				= $form['name'];
			$formStatus				= $form['active'];
			if ($formStatus == 0) {
				$formName			= $formName . ' ' . __( "Inactive", "ts_visual_composer_extend" );
			}
			$TS_VCSC_QuForms_List[$formName]				= $formID;
		};
	} else if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_QuFormReleaseTypeUse == "class") {
		// QuForm 2.x
		$TS_VCSC_QuForms_Name		= $TS_VCSC_QuForms_Name . " 2.x";
		$TS_VCSC_QuForms_Base		= "quform";
		$TS_VCSC_QuForms_Repo 		= Quform::getService('repository');
		$TS_VCSC_QuForms_Data 		= $TS_VCSC_QuForms_Repo->formsToSelectArray();
		foreach ($TS_VCSC_QuForms_Data as $id => $name) {
			$TS_VCSC_QuForms_List[Quform::escape($name)]	= Quform::escape($id);
        }		
	}
	
	// Check if Forms Found
	if (count($TS_VCSC_QuForms_List) == 0) {
		$TS_VCSC_QuForms_List[__("No QuForms found!", "ts_visual_composer_extend")]	= '-1';
	}
	
	// Generate VC Element	
	$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
		"name"                      => $TS_VCSC_QuForms_Name,
		"base"                      => $TS_VCSC_QuForms_Base,
		"icon" 	                    => "ts-composer-element-icon-quform",
		"category"                  => ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorElementFilter == "true" ? __( "VC Extensions", "ts_visual_composer_extend" ) : __( '3rd Party Plugins', "ts_visual_composer_extend" )),
		"description"               => __("Place a Quform form element", "ts_visual_composer_extend"),
		"admin_enqueue_js"			=> "",
		"admin_enqueue_css"			=> "",
		"params"                    => array(
			// QuForm Settings
			array(
				"type"              => "seperator",
				"param_name"        => "seperator_1",
				"seperator"			=> "Quform Form",
			),
			array(
				"type"              => "dropdown",
				"heading"           => __( "Quform Form", "ts_visual_composer_extend" ),
				"param_name"        => "id",
				"width"             => 300,
				"value"             => $TS_VCSC_QuForms_List,
				"admin_label"       => true,
				"save_always" 		=> true,
				"description"       => __( "Select the Quform Form you want to use.", "ts_visual_composer_extend" ),
			),				
			array(
				"type"              => "hidden_input",
				"heading"           => __( "Form Name", "ts_visual_composer_extend" ),
				"param_name"        => "name",
				"value"             => "",
				"admin_label"		=> true
			),
			array(
				"type"              => "messenger",
				"param_name"        => "messenger",
				"color"				=> "#FF0000",
				"message"           => __( "Please make sure that the QuForm Plugin is installed and activated.", "ts_visual_composer_extend" )
			),
		)
	);

	if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
		return $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element;
	} else {			
		vc_map($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element);
	};
?>