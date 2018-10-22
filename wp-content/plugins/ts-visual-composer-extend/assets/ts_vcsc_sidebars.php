<?php
	global $VISUAL_COMPOSER_EXTENSIONS;
    
	function TS_VCSC_NoUiSlider_Settings_Field($settings, $value) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		$param_name     		= isset($settings['param_name']) ? $settings['param_name'] : '';
		$type           		= isset($settings['type']) ? $settings['type'] : '';
		$min            		= isset($settings['min']) ? $settings['min'] : '';
		$max            		= isset($settings['max']) ? $settings['max'] : '';
		$step           		= isset($settings['step']) ? $settings['step'] : '';
		$unit           		= isset($settings['unit']) ? $settings['unit'] : '';
		$decimals				= isset($settings['decimals']) ? $settings['decimals'] : 0;
		// Single Input Only
		$pips					= isset($settings['pips']) ? $settings['pips'] : "true";
		$tooltip				= isset($settings['tooltip']) ? $settings['tooltip'] : "false";
		// Range Additions
		$range					= isset($settings['range']) ? $settings['range'] : "false";
		$start					= isset($settings['start']) ? $settings['start'] : $min;
		$end					= isset($settings['end']) ? $settings['end'] : $max;				
		// Other Settings
		$suffix         		= isset($settings['suffix']) ? $settings['suffix'] : '';
		$class          		= isset($settings['class']) ? $settings['class'] : '';				
		$url            		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginPath;
		$output         		= '';
		$randomizer             = mt_rand(999999, 9999999);
		$containerclass			= '';
		if ($range == "false") {
			if ($tooltip == "true") {
				$containerclass	.= " ts-nouislider-input-slider-tooltip";
			}
			if ($pips == "true") {
				$containerclass	.= " ts-nouislider-input-slider-pips";
			}
			if (($tooltip == "false") && ($pips == "false")) {
				$containerclass	= "ts-nouislider-input-slider-basic";
			}
			$output .= '<div id="ts-nouislider-input-slider-wrapper' . $randomizer . '" class="ts-nouislider-input-slider-wrapper clearFixMe ts-settings-parameter-gradient-grey ' . $containerclass . '" style="height: 100px;">';
				$output .= '<div id="ts-nouislider-input-slider-' . $randomizer . '" class="ts-nouislider-input-slider">';
					$output .= '<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px; background: #f5f5f5; color: #666666;" name="' . $param_name . '"  class="ts-nouislider-serial nouislider-input-selector nouislider-input-composer wpb_vc_param_value ' . $param_name . ' ' . $type . '" type="text" min="' . $min . '" max="' . $max . '" step="' . $step . '" value="' . $value . '"/>';
					$output .= '<span style="float: left; margin-right: 20px; margin-top: 10px; min-width: 10px;" class="unit">' . $unit . '</span>';
					$output .= '<span class="ts-nouislider-input-down dashicons-arrow-left-alt2" style="position: relative; float: left; display: inline-block; font-size: 30px; top: 5px; cursor: pointer; margin: 0;"></span>';
					$output .= '<span class="ts-nouislider-input-up dashicons-arrow-right-alt2" style="position: relative; float: left; display: inline-block; font-size: 30px; top: 5px; cursor: pointer; margin: 0 20px 0 0;"></span>';
					$output .= '<div id="ts-nouislider-input-element-' . $randomizer . '" class="ts-nouislider-input ts-nouislider-input-element" data-pips="' . $pips . '" data-tooltip="' . $tooltip . '" data-value="' . $value . '" data-min="' . $min . '" data-max="' . $max . '" data-decimals="' . $decimals . '" data-step="' . $step . '" data-unit="' . $unit . '" style="width: 320px; float: left; margin-top: 10px;"></div>';
				$output .= '</div>';
			$output .= '</div>';
		} else if ($range == "true") {
			$output .= '<div id="ts-nouislider-range-slider-' . $randomizer . '" class="ts-nouislider-range-slider clearFixMe ts-settings-parameter-gradient-grey">';
				$output .= '<div id="ts-nouislider-range-output-' . $randomizer . '" class="ts-nouislider-range-output" data-controls="ts-nouislider-range-controls-' . $randomizer . '">';
					$output .= '<div id="ts-nouislider-range-human-' . $randomizer . '" class="ts-nouislider-range-human">';	
						$output .= '<span class="ts-nouislider-range-start"></span> - <span class="ts-nouislider-range-end"></span>';							
					$output .= '</div>';
				$output .= '</div>';
				$output .= '<div id="ts-nouislider-range-controls-' . $randomizer . '" class="ts-nouislider-range-controls" data-output="ts-nouislider-range-output-' . $randomizer . '">';
					$output .= '<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="' . $param_name . '"  class="ts-nouislider-serial nouislider-range-selector nouislider-input-composer wpb_vc_param_value ' . $param_name . ' ' . $type . '" type="hidden" value="' . $value . '" style="display: none;"/>';
					$output .= '<span class="ts-nouislider-range-lower-down dashicons-arrow-left-alt2" style="position: relative; float: left; display: inline-block; font-size: 30px; top: 30px; cursor: pointer; margin: 0;"></span>';
					$output .= '<span class="ts-nouislider-range-lower-up dashicons-arrow-right-alt2" style="position: relative; float: left; display: inline-block; font-size: 30px; top: 30px; cursor: pointer; margin: 0 20px 0 0;"></span>';						
					$output .= '<div id="ts-nouislider-range-element-' . $randomizer . '" class="ts-nouislider-range ts-nouislider-range-element" data-value="' . $value . '" data-start="' . $start . '" data-end="' . $end . '" data-min="' . $min . '" data-max="' . $max . '" data-decimals="' . $decimals . '" data-step="' . $step . '" style="width: 400px; float: left; margin: 10px auto;"></div>';
					$output .= '<span class="ts-nouislider-range-upper-down dashicons-arrow-left-alt2" style="position: relative; float: none; display: inline-block; font-size: 30px; top: 30px; cursor: pointer; margin: 0 0 0 20px;"></span>';
					$output .= '<span class="ts-nouislider-range-upper-up dashicons-arrow-right-alt2" style="position: relative; float: none; display: inline-block; font-size: 30px; top: 30px; cursor: pointer; margin: 0;"></span>';
				$output .= '</div>';
			$output .= '</div>';
		}
		return $output;
	}
	
	// Save / Load Parameters
	// ----------------------
	if (isset($_POST['Sidebars'])) {		
		echo '<div id="ts_vcsc_extend_settings_save" style="position: relative; margin: 20px auto 20px auto; width: 128px; height: 128px;">';
			echo TS_VCSC_CreatePreloaderCSS("ts-settings-panel-loader", "", 4, "false");
		echo '</div>';
        
        $Sidebars_Count                                     = trim ($_POST['ts_vcsc_extend_settings_sidebars_count']);
        $Sidebars_Names                                     = array();
        for ($x = 1; $x <= $Sidebars_Count; $x++) {
            $Sidebars_Names[]                               = trim ($_POST['ts_vcsc_extend_settings_sidebars_' . $x]); 
        }
        $Sidebars_Names                                     = implode(",", $Sidebars_Names);        
		$Sidebars_Data = array(
			'count'                                         => $Sidebars_Count,
			'names'                                         => $Sidebars_Names,
		);
		update_option('ts_vcsc_extend_settings_customSidebars', $Sidebars_Data);
        
		echo '<script> window.location="' . $_SERVER['REQUEST_URI'] . '"; </script> ';
		//Header('Location: '.$_SERVER['REQUEST_URI']);
		Exit();
	} else {
        $Sidebars_Count			= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Sidebars_Manager_Settings["count"];
        $Sidebars_Names			= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Sidebars_Manager_Settings["names"];
        $Sidebars_Names			= explode(",", $Sidebars_Names);
        if (!is_array($Sidebars_Names)) {
            $Sidebars_Names		= array();
        }
	}
	echo '<div class="ts-vcsc-settings-group-header">';
		echo '<div class="display_header">';
			echo '<h2><span class="dashicons dashicons-welcome-widgets-menus"></span>Composium - WP Bakery Page Builder Extensions v' . TS_VCSC_GetPluginVersion() . ' ... Sidebars Manager</h2>';
		echo '</div>';
		echo '<div class="clear"></div>';
	echo '</div>';
?>
<form id="ts-vcsc-extend-sidebars" class="ts-vcsc-sidebars-check-wrap" name="oscimp_form" data-type="sidebars" autocomplete="off" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<span id="sidebars_settings_true" style="display: none !important; margin-bottom: 20px;">
		<input type="text" style="width: 20%;" id="ts_vcsc_extend_settings_true" name="ts_vcsc_extend_settings_true" value="0" size="100">
		<input type="text" style="width: 20%;" id="ts_vcsc_extend_settings_count" name="ts_vcsc_extend_settings_count" value="0" size="100">
	</span>
	<div class="ts-vcsc-section-main">
		<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-welcome-widgets-menus"></i>Sidebars Manager</div>
		<div class="ts-vcsc-section-content" style="margin: 0 auto;">
			<?php
				if (current_user_can('manage_options')) {
					echo '<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder" style="margin: 10px auto;">
						<span class="ts-advanced-link-tooltip-content">' . __("Click here to return to the plugins settings page.", "ts_visual_composer_extend") . '</span>
						<a href="' . $VISUAL_COMPOSER_EXTENSIONS->settingsLink . '" target="_parent" class="ts-advanced-link-button-main ts-advanced-link-button-grey ts-advanced-link-button-settings">'. __("Back to Settings", "ts_visual_composer_extend") . '</a>
					</div>';
				}
			?>	
            <div id="ts_vcsc_sidebars_wrapper_count" class="ts_vcsc_sidebars_wrapper_count" style="display: block;">
                <h4>Number of Sidebars</h4>
                <p style="font-size: 12px; margin: 10px auto;">Use the slider below to define the number of additional sidebars you want to create; set the slider to "0" (zero) for no additional sidebars:</p>
                <div style="height: 120px; max-width: 600px; margin-top: 20px; margin-bottom: 10px;">
                    <?php
                        // Number of Sidebars
                        $settings = array(
                            "param_name"                => "ts_vcsc_extend_settings_sidebars_count",
                            "value"                     => $Sidebars_Count,
                            "min"                       => "0",
                            "max"                       => "25",
                            "step"                      => "1",
                            "unit"                      => '',									
                        );
                        echo TS_VCSC_NoUiSlider_Settings_Field($settings, $Sidebars_Count);
                    ?>
                </div>
            </div>
            <div id="ts_vcsc_sidebars_wrapper_names" class="ts_vcsc_sidebars_wrapper_names" data-names="<?php echo implode(",", $Sidebars_Names); ?>" style="display: block;">
                <h4>Sidebar Names</h4>
                <p style="font-size: 12px; margin: 10px auto;">For easier identification, you can assign custom names to your additional sidebars:</p>
                <?php
                    if ($Sidebars_Count > 0) {
                        for ($x = 1; $x <= $Sidebars_Count; $x++) {
                            if (isset($Sidebars_Names[$x - 1])) {
                                $Sidebar_Name           = $Sidebars_Names[$x - 1];
                                if (trim($Sidebar_Name) == '') {
                                    $Sidebar_Name       = "Custom Sidebar #" . $x;
                                }
                            } else {
                                $Sidebar_Name           = "Custom Sidebar #" . $x;
                            }
                            echo '<div class="ts_vcsc_extend_settings_sidebar_single">';
                                echo '<label for="ts_vcsc_extend_settings_sidebar_' . $x . '" class="ts_vcsc_extend_settings_label">Name Sidebar #' . $x . ':</label>';
                                echo '<input type="text" id="ts_vcsc_extend_settings_sidebars_' . $x . '" data-error="Name - Sidebar #' . $x . '" data-validation-engine="validate[required,custom[onlyLetterNumberCustom]]" class="ts_vcsc_extend_settings_sidebars" name="ts_vcsc_extend_settings_sidebars_' . $x . '" data-id="ts-custom-sidebar-' . $x . '" value="' . $Sidebar_Name . '">';
                            echo '</div>';
                        } 
                    }
                ?>
            </div>
		</div>		
	</div>
	<div class="ts-vcsc-extend-sidebars-controls">
		<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder" style="margin: 20px 0 0 0;">
			<span class="ts-advanced-link-tooltip-content"><?php _e("Click here to save your additional sidebars.", "ts_visual_composer_extend"); ?></span>
			<button type="submit" name="Sidebars" id="ts-vcsc-extend-sidebars-submit" class="ButtonSubmit ts-advanced-link-button-main ts-advanced-link-button-blue ts-advanced-link-button-save">
				<?php _e("Save Sidebars", "ts_visual_composer_extend"); ?>
			</button>
		</div>
	</div>
</form>