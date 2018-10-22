<?php
    // Load Social Networks API's
    // --------------------------
    $this->TS_VCSC_SocialNetworkAPIs 			        = ((get_option('ts_vcsc_extend_settings_defaultLightboxSocialAPIs', $this->TS_VCSC_Lightbox_Setting_Defaults['loadapis'])) == 1 ? "true" : "false");
    
    
    // Default Lightbox Animation
    // --------------------------
    $this->TS_VCSC_LightboxDefaultAnimation             = (((array_key_exists('animation', $this->TS_VCSC_LightboxGlobalSettings)) ? $this->TS_VCSC_LightboxGlobalSettings['animation'] : $this->TS_VCSC_Lightbox_Setting_Defaults['animation']) == 1 ? 'true' : 'false');
    
    
    // Lightbox Social Share Buttons
    // -----------------------------
    $this->TS_VCSC_LightboxSocialShare                  = (((array_key_exists('share', $this->TS_VCSC_LightboxGlobalSettings)) ? $this->TS_VCSC_LightboxGlobalSettings['share'] : $this->TS_VCSC_Lightbox_Setting_Defaults['share']) == 1 ? 'true' : 'false');
    
    
    // Lightbox Save Image Buttons
    // ---------------------------
    $this->TS_VCSC_LightboxSaveImages                   = (((array_key_exists('save', $this->TS_VCSC_LightboxGlobalSettings)) ? $this->TS_VCSC_LightboxGlobalSettings['save'] : $this->TS_VCSC_Lightbox_Setting_Defaults['save']) == 1 ? 'true' : 'false');
    
    // Check and Set other Global Variables
    // ------------------------------------
    // Check if All Files should be loaded
    if (get_option('ts_vcsc_extend_settings_loadForcable', 0) == 0) 	        { $this->TS_VCSC_LoadFrontEndForcable = "false"; } 			else { $this->TS_VCSC_LoadFrontEndForcable = "true"; }
    // Check if Modernizr should be loaded
    if (get_option('ts_vcsc_extend_settings_loadModernizr', 1) == 1) 	        { $this->TS_VCSC_LoadFrontEndModernizr = "true"; } 			else { $this->TS_VCSC_LoadFrontEndModernizr = "false"; }
    // Check if MooTools should be loaded
    if (get_option('ts_vcsc_extend_settings_loadMooTools', 1) == 1)				{ $this->TS_VCSC_LoadFrontEndMooTools = "true"; }			else { $this->TS_VCSC_LoadFrontEndMooTools = "false"; }
    // Check if Lightbox should be loaded
    if (get_option('ts_vcsc_extend_settings_loadLightbox', 0) == 1) 	        { $this->TS_VCSC_LoadFrontEndLightbox = "true"; } 			else { $this->TS_VCSC_LoadFrontEndLightbox = "false"; }
    // Check if Tooltips should be loaded
    if (get_option('ts_vcsc_extend_settings_loadTooltip', 0) == 1) 		        { $this->TS_VCSC_LoadFrontEndTooltips = "true"; } 			else { $this->TS_VCSC_LoadFrontEndTooltips = "false"; }
    // Check if ForceLoad of jQuery
    if (get_option('ts_vcsc_extend_settings_loadjQuery', 0) == 1) 		        { $this->TS_VCSC_LoadFrontEndJQuery = "true"; }				else { $this->TS_VCSC_LoadFrontEndJQuery = "false"; }
    // Check for base64 tinyMCE Text Editor
    if (get_option('ts_vcsc_extend_settings_tinymceEncoded', 1) == 1)	        { $this->TS_VCSC_EditorBase64TinyMCE = "true"; } 			else { $this->TS_VCSC_EditorBase64TinyMCE = "false"; }
    // Check for Visual Icon Selector
    if (get_option('ts_vcsc_extend_settings_visualSelector', 1) == 1)	        { $this->TS_VCSC_EditorVisualSelector = "true"; } 			else { $this->TS_VCSC_EditorVisualSelector = "false"; }
    // Check for Editor Image Preview
    if (get_option('ts_vcsc_extend_settings_previewImages', 1) == 1)	        { $this->TS_VCSC_EditorImagePreview = "true"; }				else { $this->TS_VCSC_EditorImagePreview = "false"; }    
    // Check for Container Toggle Control
    if (get_option('ts_vcsc_extend_settings_containerToggle', 0) == 1)	        { $this->TS_VCSC_EditorContainerToggle = "true"; }          else { $this->TS_VCSC_EditorContainerToggle = "false"; }
    // Check for Element Group Filter
     if (get_option('ts_vcsc_extend_settings_elementFilter', 0) == 1)	        { $this->TS_VCSC_EditorElementFilter = "true"; }            else { $this->TS_VCSC_EditorElementFilter = "false"; }
    // Check for Background Indicator
    if (get_option('ts_vcsc_extend_settings_backgroundIndicator', 1) == 1)	    { $this->TS_VCSC_EditorBackgroundIndicator = "true"; }		else { $this->TS_VCSC_EditorBackgroundIndicator = "false"; }   
    // Shortcode Viewer Popup
    if (get_option('ts_vcsc_extend_settings_allowShortcodeViewer', 0) == 1)		{ $this->TS_VCSC_VisualComposer_Shortcodes = "true"; }      else { $this->TS_VCSC_VisualComposer_Shortcodes = "false"; }
    // SmoothScroll Activation
    if (get_option('ts_vcsc_extend_settings_additionsSmoothScroll', 0) == 1)	{ $this->TS_VCSC_UseSmoothScroll = "false"; } 				else { $this->TS_VCSC_UseSmoothScroll = "false"; }
    // Provide Deprecated Elements
    if (get_option('ts_vcsc_extend_settings_allowDeprecated', 0) == 1)			{ $this->TS_VCSC_UseDeprecatedElements = "true"; }			else { $this->TS_VCSC_UseDeprecatedElements = "false"; }
    // Extendend Container Nesting
    if (get_option('ts_vcsc_extend_settings_allowExtendedNesting', 0) == 1)     { $this->TS_VCSC_UseExtendedNesting = "true"; }             else { $this->TS_VCSC_UseExtendedNesting = "false"; }
    
    
    // Extended Row + Column Options
    // -----------------------------
    if ((($this->TS_VCSC_PluginExtended == "true") && (get_option('ts_vcsc_extend_settings_additions', 1) == 1)) || (($this->TS_VCSC_PluginExtended == "false"))) {
        if (get_option('ts_vcsc_extend_settings_additionsRows', 0) == 1) {
            $this->TS_VCSC_UseExtendedRows		        = "true";
        } else {
            $this->TS_VCSC_UseExtendedRows 		        = "false";
        }
        if (get_option('ts_vcsc_extend_settings_additionsColumns', 0) == 1) {
            $this->TS_VCSC_UseExtendedColumns 	        = "true";
        } else {
            $this->TS_VCSC_UseExtendedColumns 	        = "false";
        }
    } else {
        $this->TS_VCSC_UseExtendedRows 			        = "false";
        $this->TS_VCSC_UseExtendedColumns 		        = "false";
    }
    
    
    // Extended Row Modules
    // --------------------
    if ($this->TS_VCSC_UseExtendedRows == "true") {
        $TS_VCSC_ExtendedRowsCustomizer                 = get_option('ts_vcsc_extend_settings_extendedRowsCustomizer', array());
        $this->TS_VCSC_ExtendedRowsModules = array(
            "globals"                                   => array (
                "enabled"               => ((isset($TS_VCSC_ExtendedRowsCustomizer["globals"]["enabled"]))                  ? $TS_VCSC_ExtendedRowsCustomizer["globals"]["enabled"]                 : "true"),
                "rowheight"             => ((isset($TS_VCSC_ExtendedRowsCustomizer["globals"]["rowheight"]))                ? $TS_VCSC_ExtendedRowsCustomizer["globals"]["rowheight"]               : "true"),
                "rowwidth"              => ((isset($TS_VCSC_ExtendedRowsCustomizer["globals"]["rowwidth"]))                 ? $TS_VCSC_ExtendedRowsCustomizer["globals"]["rowwidth"]                : "true"),
                "general"               => ((isset($TS_VCSC_ExtendedRowsCustomizer["globals"]["general"]))                  ? $TS_VCSC_ExtendedRowsCustomizer["globals"]["general"]                 : "true"),
                "visibility"            => ((isset($TS_VCSC_ExtendedRowsCustomizer["globals"]["visibility"]))               ? $TS_VCSC_ExtendedRowsCustomizer["globals"]["visibility"]              : "true"),
                "columnheight"          => ((isset($TS_VCSC_ExtendedRowsCustomizer["globals"]["columnheight"]))             ? $TS_VCSC_ExtendedRowsCustomizer["globals"]["columnheight"]            : "true"),
                "viewport"              => ((isset($TS_VCSC_ExtendedRowsCustomizer["globals"]["viewport"]))                 ? $TS_VCSC_ExtendedRowsCustomizer["globals"]["viewport"]                : "true"),
            ),
            "backgrounds"                               => array (
                "enabled"               => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["enabled"]))              ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["enabled"]             : "true"),
                "imagesingle"           => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imagesingle"]))          ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imagesingle"]         : "true"),
                "imagefixed"            => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imagefixed"]))           ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imagefixed"]          : "true"),
                "imageslider"           => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imageslider"]))          ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imageslider"]         : "true"),
                "imageparallax"         => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imageparallax"]))        ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imageparallax"]       : "true"),
                "imageautomove"         => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imageautomove"]))        ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imageautomove"]       : "true"),
                "imagemovement"         => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imagemovement"]))        ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["imagemovement"]       : "true"),             
                "colorsingle"           => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["colorsingle"]))          ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["colorsingle"]         : "true"),
                "colorgradient"         => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["colorgradient"]))        ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["colorgradient"]       : "true"),
                "otherpatternbold"      => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["otherpatternbold"]))     ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["otherpatternbold"]    : "true"),
                "otherparticles"        => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["otherparticles"]))       ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["otherparticles"]      : "true"),
                "othertriangle"         => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["othertriangle"]))        ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["othertriangle"]       : "true"),
                "videoyoutube"          => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["videoyoutube"]))         ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["videoyoutube"]        : "true"),
                "videohtml5"            => ((isset($TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["videohtml5"]))           ? $TS_VCSC_ExtendedRowsCustomizer["backgrounds"]["videohtml5"]          : "true"),
            ),
            "effects"                                   => array(
                "enabled"               => ((isset($TS_VCSC_ExtendedRowsCustomizer["effects"]["enabled"]))                  ? $TS_VCSC_ExtendedRowsCustomizer["effects"]["enabled"]                 : "true"),
                "overlays"              => ((isset($TS_VCSC_ExtendedRowsCustomizer["effects"]["overlays"]))                 ? $TS_VCSC_ExtendedRowsCustomizer["effects"]["overlays"]                : "true"),
                "kenburns"              => ((isset($TS_VCSC_ExtendedRowsCustomizer["effects"]["kenburns"]))                 ? $TS_VCSC_ExtendedRowsCustomizer["effects"]["kenburns"]                : "true"),
                "seperators"            => ((isset($TS_VCSC_ExtendedRowsCustomizer["effects"]["seperators"]))               ? $TS_VCSC_ExtendedRowsCustomizer["effects"]["seperators"]              : "true"),
                "blurring"              => ((isset($TS_VCSC_ExtendedRowsCustomizer["effects"]["blurring"]))                 ? $TS_VCSC_ExtendedRowsCustomizer["effects"]["blurring"]                : "true"),
            ),
        );
    } else {
        $this->TS_VCSC_ExtendedRowsModules              = array();
    }

    
    // Custom Setting Parameters
    // -------------------------
    $TS_VCSC_CustomSettingParameters                    = get_option('ts_vcsc_extend_settings_parametersCustom', '');
    if (!is_array($TS_VCSC_CustomSettingParameters)) {
        $TS_VCSC_CustomSettingParameters                = array();
    }
    // Advanced Link Picker
    $TS_VCSC_Advanced_Linkpicker_Settings               = ((array_key_exists('LinkPicker', $TS_VCSC_CustomSettingParameters)) ? $TS_VCSC_CustomSettingParameters['LinkPicker'] : array());
    if (($TS_VCSC_Advanced_Linkpicker_Settings == false) || (empty($TS_VCSC_Advanced_Linkpicker_Settings))) {
        $TS_VCSC_Advanced_Linkpicker_Settings           = array();
    }
    $this->TS_VCSC_ParameterLinkPicker = array(
        'enabled'                                       => (((array_key_exists('enabled', $TS_VCSC_Advanced_Linkpicker_Settings))   ? $TS_VCSC_Advanced_Linkpicker_Settings['enabled']      : $this->TS_VCSC_Advanced_Linkpicker_Defaults['enabled'])       == 1 ? "true" : "false"),
        'global'                                        => (((array_key_exists('global', $TS_VCSC_Advanced_Linkpicker_Settings))    ? $TS_VCSC_Advanced_Linkpicker_Settings['global']       : $this->TS_VCSC_Advanced_Linkpicker_Defaults['global'])        == 1 ? "true" : "false"),
        'offset'                                        => ((array_key_exists('offset', $TS_VCSC_Advanced_Linkpicker_Settings))     ? $TS_VCSC_Advanced_Linkpicker_Settings['offset']       : $this->TS_VCSC_Advanced_Linkpicker_Defaults['offset']),
        'posts'                                         => (((array_key_exists('posts', $TS_VCSC_Advanced_Linkpicker_Settings))     ? $TS_VCSC_Advanced_Linkpicker_Settings['posts']        : $this->TS_VCSC_Advanced_Linkpicker_Defaults['posts'])         == 1 ? "true" : "false"),
        'custom'                                        => (((array_key_exists('custom', $TS_VCSC_Advanced_Linkpicker_Settings))    ? $TS_VCSC_Advanced_Linkpicker_Settings['custom']       : $this->TS_VCSC_Advanced_Linkpicker_Defaults['custom'])        == 1 ? "true" : "false"),
        'orderby'                                       => ((array_key_exists('orderby', $TS_VCSC_Advanced_Linkpicker_Settings))    ? $TS_VCSC_Advanced_Linkpicker_Settings['orderby']      : $this->TS_VCSC_Advanced_Linkpicker_Defaults['orderby']),
        'order'                                         => ((array_key_exists('order', $TS_VCSC_Advanced_Linkpicker_Settings))      ? $TS_VCSC_Advanced_Linkpicker_Settings['order']        : $this->TS_VCSC_Advanced_Linkpicker_Defaults['order']),
    );
    // Numeric Slider Inputs (noUiSlider)
    $TS_VCSC_NoUiSlider_Input_Settings                  = ((array_key_exists('NoUiSlider', $TS_VCSC_CustomSettingParameters)) ? $TS_VCSC_CustomSettingParameters['NoUiSlider'] : array());
    if (($TS_VCSC_NoUiSlider_Input_Settings == false) || (empty($TS_VCSC_NoUiSlider_Input_Settings))) {
        $TS_VCSC_NoUiSlider_Input_Settings              = array();
    }
    $this->TS_VCSC_ParameterNoUiSlider = array(
        'enabled'                                       => (((array_key_exists('enabled', $TS_VCSC_NoUiSlider_Input_Settings))      ? $TS_VCSC_NoUiSlider_Input_Settings['enabled']         : $this->TS_VCSC_NoUiSlider_Inputs_Defaults['enabled'])         == 1 ? "true" : "false"),
        'pips'                                          => (((array_key_exists('pips', $TS_VCSC_NoUiSlider_Input_Settings))         ? $TS_VCSC_NoUiSlider_Input_Settings['pips']            : $this->TS_VCSC_NoUiSlider_Inputs_Defaults['pips'])            == 1 ? "true" : "false"),
        'tooltip'                                       => (((array_key_exists('tooltip', $TS_VCSC_NoUiSlider_Input_Settings))      ? $TS_VCSC_NoUiSlider_Input_Settings['tooltip']         : $this->TS_VCSC_NoUiSlider_Inputs_Defaults['tooltip'])         == 1 ? "true" : "false"),
        'input'                                         => (((array_key_exists('input', $TS_VCSC_NoUiSlider_Input_Settings))        ? $TS_VCSC_NoUiSlider_Input_Settings['input']           : $this->TS_VCSC_NoUiSlider_Inputs_Defaults['input'])           == 1 ? "true" : "false"),
    );
    // Screen Size Settings
    $TS_VCSC_ScreenSizes_Input_Settings                 = ((array_key_exists('ScreenSizes', $TS_VCSC_CustomSettingParameters)) ? $TS_VCSC_CustomSettingParameters['ScreenSizes'] : array());
    $this->TS_VCSC_Screen_Sizes_Custom = array(
        'ExtraLarge'                                    => ((array_key_exists('ExtraLarge', $TS_VCSC_ScreenSizes_Input_Settings))   ? $TS_VCSC_ScreenSizes_Input_Settings['ExtraLarge']     : $this->TS_VCSC_Screen_Sizes_Defaults['ExtraLarge']),
        'Large'                                         => ((array_key_exists('Large', $TS_VCSC_ScreenSizes_Input_Settings))        ? $TS_VCSC_ScreenSizes_Input_Settings['Large']          : $this->TS_VCSC_Screen_Sizes_Defaults['Large']),
        'Medium'                                        => ((array_key_exists('Medium', $TS_VCSC_ScreenSizes_Input_Settings))       ? $TS_VCSC_ScreenSizes_Input_Settings['Medium']         : $this->TS_VCSC_Screen_Sizes_Defaults['Medium']),
        'Small'                                         => ((array_key_exists('Small', $TS_VCSC_ScreenSizes_Input_Settings))        ? $TS_VCSC_ScreenSizes_Input_Settings['Small']          : $this->TS_VCSC_Screen_Sizes_Defaults['Small']),
        'ExtraSmall'                                    => ((array_key_exists('ExtraSmall', $TS_VCSC_ScreenSizes_Input_Settings))   ? $TS_VCSC_ScreenSizes_Input_Settings['ExtraSmall']     : $this->TS_VCSC_Screen_Sizes_Defaults['ExtraSmall']),
    );
?>