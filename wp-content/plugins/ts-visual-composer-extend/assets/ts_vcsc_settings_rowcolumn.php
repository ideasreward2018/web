<?php
	global $VISUAL_COMPOSER_EXTENSIONS;
	if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_UseExtendedRows == "true") {
		// Row Global Options
		$TS_VCSC_RowGlobalEnabled			= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["globals"]["enabled"];
		$TS_VCSC_RowGlobalRowheight			= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["globals"]["rowheight"];
		$TS_VCSC_RowGlobalRowwidth			= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["globals"]["rowwidth"];
		$TS_VCSC_RowGlobalGeneral			= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["globals"]["general"];
		$TS_VCSC_RowGlobalVisibility		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["globals"]["visibility"];
		$TS_VCSC_RowGlobalColumnheight		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["globals"]["columnheight"];
		$TS_VCSC_RowGlobalViewport			= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["globals"]["viewport"];
		// Row Background Options
		$TS_VCSC_RowBackgroundEnabled		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["backgrounds"]["enabled"];	
		$TS_VCSC_RowBackgroundImageSingle	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["backgrounds"]["imagesingle"];
		$TS_VCSC_RowBackgroundImageFixed	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["backgrounds"]["imagefixed"];
		$TS_VCSC_RowBackgroundImageSlider	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["backgrounds"]["imageslider"];
		$TS_VCSC_RowBackgroundImageParallax	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["backgrounds"]["imageparallax"];
		$TS_VCSC_RowBackgroundImageAutomove	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["backgrounds"]["imageautomove"];
		$TS_VCSC_RowBackgroundImageMovement	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["backgrounds"]["imagemovement"];
		$TS_VCSC_RowBackgroundColorSingle	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["backgrounds"]["colorsingle"];
		$TS_VCSC_RowBackgroundColorGradient	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["backgrounds"]["colorgradient"];
		$TS_VCSC_RowBackgroundOtherPattern	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["backgrounds"]["otherpatternbold"];
		$TS_VCSC_RowBackgroundOtherParticle	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["backgrounds"]["otherparticles"];
		$TS_VCSC_RowBackgroundOtherTriangle	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["backgrounds"]["othertriangle"];
		$TS_VCSC_RowBackgroundVideoYoutube	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["backgrounds"]["videoyoutube"];
		$TS_VCSC_RowBackgroundVideoHTML5	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["backgrounds"]["videohtml5"];
		// Row Effect Options
		$TS_VCSC_RowEffectEnabled			= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["effects"]["enabled"];
		$TS_VCSC_RowEffectOverlays			= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["effects"]["overlays"];
		$TS_VCSC_RowEffectKenburns			= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["effects"]["kenburns"];
		$TS_VCSC_RowEffectSeperators		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["effects"]["seperators"];
		$TS_VCSC_RowEffectBlurring			= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ExtendedRowsModules["effects"]["blurring"];
	} else {
		// Row Global Options
		$TS_VCSC_RowGlobalEnabled			= "false";
		$TS_VCSC_RowGlobalRowheight			= "false";
		$TS_VCSC_RowGlobalRowwidth			= "false";
		$TS_VCSC_RowGlobalGeneral			= "false";
		$TS_VCSC_RowGlobalVisibility		= "false";
		$TS_VCSC_RowGlobalColumnheight		= "false";
		$TS_VCSC_RowGlobalViewport			= "false";
		// Row Background Options
		$TS_VCSC_RowBackgroundEnabled		= "false";
		$TS_VCSC_RowBackgroundImageSingle	= "false";
		$TS_VCSC_RowBackgroundImageFixed	= "false";
		$TS_VCSC_RowBackgroundImageSlider	= "false";
		$TS_VCSC_RowBackgroundImageParallax	= "false";
		$TS_VCSC_RowBackgroundImageAutomove	= "false";
		$TS_VCSC_RowBackgroundImageMovement	= "false";
		$TS_VCSC_RowBackgroundColorSingle	= "false";
		$TS_VCSC_RowBackgroundColorGradient	= "false";
		$TS_VCSC_RowBackgroundOtherPattern	= "false";
		$TS_VCSC_RowBackgroundOtherParticle	= "false";
		$TS_VCSC_RowBackgroundOtherTriangle	= "false";
		$TS_VCSC_RowBackgroundVideoYoutube	= "false";
		$TS_VCSC_RowBackgroundVideoHTML5	= "false";
		// Row Effect Options
		$TS_VCSC_RowEffectEnabled			= "false";
		$TS_VCSC_RowEffectOverlays			= "false";
		$TS_VCSC_RowEffectKenburns			= "false";
		$TS_VCSC_RowEffectSeperators		= "false";
		$TS_VCSC_RowEffectBlurring			= "false";
	}
	
	// WP Bakery Page Builder 5.x Check
	if (TS_VCSC_VersionCompare($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Version, '5.0.0') >= 0) {
		$TS_VCSC_ComposerNativeAnimation 	= "true";
	} else {
		$TS_VCSC_ComposerNativeAnimation 	= "false";
	}
?>
<div id="ts-settings-rowcolumn" class="tab-content">
	<?php if ((($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginExtended == "true") && (get_option('ts_vcsc_extend_settings_additions', 1) == 1)) || (($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginExtended == "false"))) { ?>
		<div class="ts-vcsc-section-main">
			<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-info"></i>General Information</div>
			<div class="ts-vcsc-section-content">
				<?php
					if ($TS_VCSC_ComposerSectionElement == "true") {
						echo '<div class="ts-vcsc-notice-field ts-vcsc-success" style="margin-top: 10px; font-size: 13px; text-align: justify;">
							"Composium - WP Bakery Page Builder Extensions" allows you to extend the available options for section, row and column settings, adding features such as viewport animations (section, row and column) and a variety of background effects (section + row). If you already use other plugins that provide the same or similar options you should decide for either one but not use both at the same time as they can cause contradicting settings. Also, if your theme incorporates Visual Composer by itself, some themes already provide you with similar options; in these cases, you should disable the settings below in order to avoid any conflicts.
						</div>';
						echo '<div style="margin-top: 20px; font-weight: bold;">The extended row and column options require a WP Bakery Page Builder version of 5.0 or higher, in order to function correctly!</div>';
					} else {
						echo '<div class="ts-vcsc-notice-field ts-vcsc-success" style="margin-top: 10px; font-size: 13px; text-align: justify;">
							"Composium - WP Bakery Page Builder Extensions" allows you to extend the available options for row and column settings, adding features such as viewport animations (row + column) and a variety of background effects (row). If you already use other plugins that provide the same or similar options you should decide for either one but not use both at the same time as they can cause contradicting settings. Also, if your theme incorporates Visual Composer by itself, some themes already provide you with similar options; in these cases, you should disable the settings below in order to avoid any conflicts.
						</div>';
						echo '<div style="margin-top: 20px; font-weight: bold;">The extended section, row and column options require a WP Bakery Page Builder version of 4.3 or higher, in order to function correctly!</div>';
					}
				?>
				
			</div>
		</div>
        <div class="ts-vcsc-section-main">
			<?php
				if ($TS_VCSC_ComposerSectionElement == "true") {
					echo '<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-editor-kitchensink"></i>Extended Section + Row Options</div>';
				} else {
					echo '<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-editor-kitchensink"></i>Extended Row Options</div>';
				}
			?>			
			<div class="ts-vcsc-section-content">
				<?php
					if ($TS_VCSC_ComposerSectionElement == "true") {
						echo '<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
							The amount of available additional options for section and row settings is very extensive and can slow down the rendering of the row settings panel in WP Bakery Page Builder, particularly, if all available options are utilized. The controls below allow you to easily define your custom set of additional section and row options, which can significantly decrease the rendering time for the settings panel in WP Bakery Page Builder, particularly, if only a few options ar added.
						</div>';
					} else {
						echo '<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
							The amount of available additional options for row settings is very extensive and can slow down the rendering of the row settings panel in WP Bakery Page Builder, particularly, if all available options are utilized. The controls below allow you to easily define your custom set of additional row options, which can significantly decrease the rendering time for the settings panel in WP Bakery Page Builder, particularly, if only a few options ar added.
						</div>';
					}
				?>	
				<div style="margin-top: 10px; margin-bottom: 10px;">
					<?php
						if ($TS_VCSC_ComposerSectionElement == "true") {
							echo '<div style="font-weight: bold; font-size: 14px; margin: 0;">Extend Options for WP Bakery Page Builder Sections + Rows:</div>';
							echo '<p style="font-size: 12px;">Extend Section + Row Options with Background Effects and Viewport Animation Settings:</p>';
						} else {
							echo '<div style="font-weight: bold; font-size: 14px; margin: 0;">Extend Options for WP Bakery Page Builder Rows:</div>';
							echo '<p style="font-size: 12px;">Extend Row Options with Background Effects and Viewport Animation Settings:</p>';
						}
						$settings = array(
							"param_name"        => "ts_vcsc_extend_settings_additionsRows",
							"label"				=> ($TS_VCSC_ComposerSectionElement == "true" ? "Extend Section + Row Options" : "Extend Row Options"),
							"value"             => $ts_vcsc_extend_settings_additionsRows,
							"order"				=> 1,
						);
						echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_additionsRows);
					?>				
				</div>
				<div id="ts_vcsc_extend_settings_additionsRows_true" style="margin-top: 30px; margin-bottom: 10px; margin-left: 25px; <?php echo ($ts_vcsc_extend_settings_additionsRows == 0 ? 'display: none;' : 'display: block;'); ?>">
					<div id="ts_vcsc_extend_settings_rowAllowableOptionsWrap" class="ts-multiselect-holder" style="display: block; height: auto; margin: 20px 0 10px 0;">
						<?php
							if ($TS_VCSC_ComposerSectionElement == "true") {
								echo '<h2>Enable Section + Row Option Groups:</h2>';
								echo '<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 20px; font-size: 13px; text-align: justify;">
									Based on the option groups you enable, additional settings will appear below, allowing you to further finetune which section + row options within that group you actually want to use.
								</div>';
							} else {
								echo '<h2>Enable Row Option Groups:</h2>';
								echo '<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 20px; font-size: 13px; text-align: justify;">
									Based on the option groups you enable, additional settings will appear below, allowing you to further finetune which row options within that group you actually want to use.
								</div>';
							}
						?>
						<textarea name="ts_vcsc_extend_settings_rowAllowableOptionsValue" id="ts_vcsc_extend_settings_rowAllowableOptionsValue" class="" style="display: none;"></textarea >
						<select id="ts_vcsc_extend_settings_rowAllowableOptionsSelect" name="ts_vcsc_extend_settings_rowAllowableOptionsSelect" multiple="multiple" class="ts-multiple-options-selector" data-type="main" data-callback="rowoptions" data-holder="ts_vcsc_extend_settings_rowAllowableOptionsValue">
							<option value="globals" <?php selected($TS_VCSC_RowGlobalEnabled, "true"); ?> data-initial="<?php echo $TS_VCSC_RowGlobalEnabled; ?>"><?php echo ($TS_VCSC_ComposerSectionElement == "true" ? "General Section + Row Settings" : "General Row Settings") ?></option>
							<option value="backgrounds" <?php selected($TS_VCSC_RowBackgroundEnabled, "true"); ?> data-initial="<?php echo $TS_VCSC_RowBackgroundEnabled; ?>"><?php echo ($TS_VCSC_ComposerSectionElement == "true" ? "Section + Row Backgrounds" : "Row Backgrounds") ?></option>							
							<option value="effects" <?php selected($TS_VCSC_RowEffectEnabled, "true"); ?> data-initial="<?php echo $TS_VCSC_RowEffectEnabled; ?>"><?php echo ($TS_VCSC_ComposerSectionElement == "true" ? "Section + Row Effects" : "Row Effects") ?></option>
						</select>
					</div>
					<div id="ts_vcsc_extend_settings_rowAllowableGlobalsWrap" class="ts-multiselect-holder" style="display: block; height: auto; margin: 10px 0 10px 0; border: 1px solid #ededed; padding: 10px 20px; background: #f9f9f9;">
						<?php
							if ($TS_VCSC_ComposerSectionElement == "true") {
								echo '<h2>Enable General Section + Row Options:</h2>';
							} else {
								echo '<h2>Enable General Row Options:</h2>';
							}
						?>						
						<textarea name="ts_vcsc_extend_settings_rowAllowableGlobalsValue" id="ts_vcsc_extend_settings_rowAllowableGlobalsValue" class="" style="display: none;"></textarea >
						<select id="ts_vcsc_extend_settings_rowAllowableGlobalsSelect" name="ts_vcsc_extend_settings_rowAllowableGlobalsSelect" multiple="multiple" class="ts-multiple-options-selector" data-type="conditional" data-callback="globaloptions" data-holder="ts_vcsc_extend_settings_rowAllowableGlobalsValue">
							<option value="rowheight" <?php selected($TS_VCSC_RowGlobalRowheight, "true"); ?> data-initial="<?php echo $TS_VCSC_RowGlobalRowheight; ?>"><?php echo ($TS_VCSC_ComposerSectionElement == "true" ? "Section + Row Height Settings" : "Row Height Settings") ?></option>
							<option value="rowwidth" <?php selected($TS_VCSC_RowGlobalRowwidth, "true"); ?> data-initial="<?php echo $TS_VCSC_RowGlobalRowwidth; ?>"><?php echo ($TS_VCSC_ComposerSectionElement == "true" ? "Section + Row Full Width Settings" : "Row Full Width Settings") ?></option>
							<option value="visibility" <?php selected($TS_VCSC_RowGlobalVisibility, "true"); ?> data-initial="<?php echo $TS_VCSC_RowGlobalVisibility; ?>"><?php echo ($TS_VCSC_ComposerSectionElement == "true" ? "Section + Row Visibility Settings" : "Row Visibility Settings") ?></option>
							<option value="viewport" <?php selected($TS_VCSC_RowGlobalViewport, "true"); ?> data-initial="<?php echo $TS_VCSC_RowGlobalViewport; ?>"><?php echo ($TS_VCSC_ComposerSectionElement == "true" ? "Section + Row Viewport Animation" : "Row Viewport Animation") ?></option>
							<option value="columnheight" <?php selected($TS_VCSC_RowGlobalColumnheight, "true"); ?> data-initial="<?php echo $TS_VCSC_RowGlobalColumnheight; ?>"><?php echo ($TS_VCSC_ComposerSectionElement == "true" ? "Row Columns Equalizier" : "Row Columns Equalizier") ?></option>
							<option value="general" <?php selected($TS_VCSC_RowGlobalGeneral, "true"); ?> data-initial="<?php echo $TS_VCSC_RowGlobalGeneral; ?>"><?php echo ($TS_VCSC_ComposerSectionElement == "true" ? "Other General Section + Row Settings" : "Other General Row Settings") ?></option>
						</select>						
						<?php
							if ($TS_VCSC_ComposerNativeAnimation == "true") {
								echo '<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
									WP Bakery Page Builder introduced section + row viewport animations with v5.0.0 as native feature. While the section + row viewport animation feature from this addon does allow for some additional controls over the viewport animation that WP Bakery Page Builder does not, it is always better to use a native feature if available. If you are using the section + row viewport animation from this addon, ensure that you don not also use the native viewport animation option from WP Bakery Page Builder for the same section or row at the same time in order to avoid conflicts.
								</div>';
							}
						?>						
						<div id="ts_vcsc_extend_settings_rowAllowablePaddingsWrap" style="display: block; width: 100%; margin: 0;">
							<h3>Enable Padding/Margin Options:</h3>
							<?php
								if ($TS_VCSC_ComposerSectionElement == "true") {
									echo '<p style="font-size: 12px;">When a section or row background has been applied with the extended section or row options, a background indicator can be shown next to the section or row control options:</p>';
									echo '<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
										Up until version 4.0.0 of this add-on, the extended row options also included settings to define a top/bottom padding to the row and left/right margins to the background style. Due to the historic names of the setting parameters, conflicts with some themes could occur that used the same names for their custom setting options for rows. In order to avoid such problems, the padding and margin options have been disabled by default but can easily be re-enabled using the setting below. If you notice any conflicts or layout issues with the option enabled, you should keep it disabled.
									</div>';
								} else {
									echo '<p style="font-size: 12px;">When a row background has been applied with the extended row options, a background indicator can be shown next to the row control options:</p>';
									echo '<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
										Up until version 4.0.0 of this add-on, the extended row options also included settings to define a top/bottom padding to the row and left/right margins to the background style. Due to the historic names of the setting parameters, conflicts with some themes could occur that used the same names for their custom setting options for rows. In order to avoid such problems, the padding and margin options have been disabled by default but can easily be re-enabled using the setting below. If you notice any conflicts or layout issues with the option enabled, you should keep it disabled.
									</div>';
								}
								$settings = array(
									"param_name"        => "ts_vcsc_extend_settings_additionsOffsets",
									"label"				=> "Enable Padding/Margin Options",
									"value"             => $ts_vcsc_extend_settings_additionsOffsets,
									"order"				=> 1,
								);
								echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_additionsOffsets);
							?>
						</div>
						<div id="ts_vcsc_extend_settings_rowAllowableVisibilityWrap" style="display: block; width: 100%; margin: 20px 0 0 0;">
							<?php
								if ($TS_VCSC_ComposerSectionElement == "true") {
									echo '<h3>Section + Row Visibility Limits:</h3>';
									echo '<p style="font-size: 12px;">Define the minimum screen size limits to be used for the section or row visibility control settings within the extended section or row options:</p>';
									echo '<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
										As the section and row shortcode is actually defined and handled by WP Bakery Page Builder itself and due to the way WP Bakery Page Builder allows add-ons to extend section and row options, it is NOT possible to apply the row visibility check server side, but only via JS function (client side).
									</div>';
								} else {
									echo '<h3>Row Visibility Limits:</h3>';
									echo '<p style="font-size: 12px;">Define the minimum screen size limits to be used for the row visibility control settings within the extended row options:</p>';
									echo '<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
										As the row shortcode is actually defined and handled by WP Bakery Page Builder itself and due to the way WP Bakery Page Builder allows add-ons to extend row options, it is NOT possible to apply the row visibilit check server side, but only via JS function (client side).
									</div>';
								}
							?>					
							<div class="ts-nouislider-input-slider clearFixMe" style="margin-bottom: 20px; width: 100%; float: left;">
								<h4>Large Screen Devices:</h4>
								<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="ts_vcsc_extend_settings_rowLimitLarge" id="ts_vcsc_extend_settings_rowLimitLarge" class="ts_vcsc_extend_settings_rowLimitLarge ts-nouislider-serial nouislider-input-selector nouislider-input-composer" type="number" min="<?php echo $TS_VCSC_Row_Visibility_Limits['Medium Devices']; ?>" max="4096" step="1" value="<?php echo $TS_VCSC_Row_Visibility_Limits['Large Devices']; ?>"/>
								<span style="float: left; margin-right: 30px; margin-top: 10px;" class="unit">px</span>
								<div id="ts_vcsc_extend_settings_rowLimitLarge_slider" class="ts-nouislider-input ts-nouislider-settings-element" data-value="<?php echo $TS_VCSC_Row_Visibility_Limits['Large Devices']; ?>" data-min="<?php echo $TS_VCSC_Row_Visibility_Limits['Medium Devices']; ?>" data-max="4096" data-decimals="0" data-step="1" style="width: 250px; float: left; margin-top: 10px;"></div>
							</div>
							<div class="ts-nouislider-input-slider clearFixMe" style="margin-bottom: 20px; width: 100%; float: left;">
								<h4>Medium Screen Devices:</h4>
								<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="ts_vcsc_extend_settings_rowLimitMedium" id="ts_vcsc_extend_settings_rowLimitMedium" class="ts_vcsc_extend_settings_rowLimitMedium ts-nouislider-serial nouislider-input-selector nouislider-input-composer" type="number" min="<?php echo $TS_VCSC_Row_Visibility_Limits['Small Devices']; ?>" max="<?php echo $TS_VCSC_Row_Visibility_Limits['Large Devices']; ?>" step="1" value="<?php echo $TS_VCSC_Row_Visibility_Limits['Medium Devices']; ?>"/>
								<span style="float: left; margin-right: 30px; margin-top: 10px;" class="unit">px</span>
								<div id="ts_vcsc_extend_settings_rowLimitMedium_slider" class="ts-nouislider-input ts-nouislider-settings-element" data-value="<?php echo $TS_VCSC_Row_Visibility_Limits['Medium Devices']; ?>" data-min="<?php echo $TS_VCSC_Row_Visibility_Limits['Small Devices']; ?>" data-max="<?php echo $TS_VCSC_Row_Visibility_Limits['Large Devices']; ?>" data-decimals="0" data-step="1" style="width: 250px; float: left; margin-top: 10px;"></div>
							</div>
							<div class="ts-nouislider-input-slider clearFixMe" style="margin-bottom: 20px; width: 100%; float: left;">
								<h4>Small Screen Devices:</h4>
								<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="ts_vcsc_extend_settings_rowLimitSmall" id="ts_vcsc_extend_settings_rowLimitSmall" class="ts_vcsc_extend_settings_rowLimitSmall ts-nouislider-serial nouislider-input-selector nouislider-input-composer" type="number" min="0" max="<?php echo $TS_VCSC_Row_Visibility_Limits['Medium Devices']; ?>" step="1" value="<?php echo $TS_VCSC_Row_Visibility_Limits['Small Devices']; ?>"/>
								<span style="float: left; margin-right: 30px; margin-top: 10px;" class="unit">px</span>
								<div id="ts_vcsc_extend_settings_rowLimitSmall_slider" class="ts-nouislider-input ts-nouislider-settings-element" data-value="<?php echo $TS_VCSC_Row_Visibility_Limits['Small Devices']; ?>" data-min="0" data-max="<?php echo $TS_VCSC_Row_Visibility_Limits['Medium Devices']; ?>" data-decimals="0" data-step="1" style="width: 250px; float: left; margin-top: 10px;"></div>
							</div>
							<h4>Extra Small Screen Devices:</h4>
							<p style="font-size: 12px;">All devices with a screen resolution of less than the minimum resolution defined for "Small Screen Devices" will automatically be treated as "Extra Small Screen Devices".</p>
						</div>
					</div>
					<div id="ts_vcsc_extend_settings_rowAllowableBackgroundsWrap" class="ts-multiselect-holder" style="display: block; height: auto; margin: 20px 0 10px 0; border: 1px solid #ededed; padding: 10px 20px; background: #f9f9f9;">
						<?php
							if ($TS_VCSC_ComposerSectionElement == "true") {
								echo '<h2>Enable Individual Section + Row Background Types:</h2>';
							} else {
								echo '<h2>Enable Individual Row Background Types:</h2>';
							}
						?>
						<textarea name="ts_vcsc_extend_settings_rowAllowableBackgroundsValue" id="ts_vcsc_extend_settings_rowAllowableBackgroundsValue" class="" style="display: none;"></textarea >
						<select id="ts_vcsc_extend_settings_rowAllowableBackgroundsSelect" name="ts_vcsc_extend_settings_rowAllowableBackgroundsSelect" multiple="multiple" class="ts-multiple-options-selector" data-type="conditional" data-callback="backgroundoptions" data-holder="ts_vcsc_extend_settings_rowAllowableBackgroundsValue">
							<option value="imagesingle" <?php selected($TS_VCSC_RowBackgroundImageSingle, "true"); ?> data-initial="<?php echo $TS_VCSC_RowBackgroundImageSingle; ?>">Single Image</option>
							<option value="imagefixed" <?php selected($TS_VCSC_RowBackgroundImageFixed, "true"); ?> data-initial="<?php echo $TS_VCSC_RowBackgroundImageFixed; ?>">Fixed Image</option>
							<option value="imageslider" <?php selected($TS_VCSC_RowBackgroundImageSlider, "true"); ?> data-initial="<?php echo $TS_VCSC_RowBackgroundImageSlider; ?>">Image Slider</option>
							<option value="imageparallax" <?php selected($TS_VCSC_RowBackgroundImageParallax, "true"); ?> data-initial="<?php echo $TS_VCSC_RowBackgroundImageParallax; ?>">Parallax Image</option>
							<option value="imageautomove" <?php selected($TS_VCSC_RowBackgroundImageAutomove, "true"); ?> data-initial="<?php echo $TS_VCSC_RowBackgroundImageAutomove; ?>">Automove Image</option>
							<option value="imagemovement" <?php selected($TS_VCSC_RowBackgroundImageMovement, "true"); ?> data-initial="<?php echo $TS_VCSC_RowBackgroundImageMovement; ?>">Movement Image</option>
							<option value="colorsingle" <?php selected($TS_VCSC_RowBackgroundColorSingle, "true"); ?> data-initial="<?php echo $TS_VCSC_RowBackgroundColorSingle; ?>">Single Color</option>
							<option value="colorgradient" <?php selected($TS_VCSC_RowBackgroundColorGradient, "true"); ?> data-initial="<?php echo $TS_VCSC_RowBackgroundColorGradient; ?>">Gradient Color</option>
							<option value="otherpatternbold" <?php selected($TS_VCSC_RowBackgroundOtherPattern, "true"); ?> data-initial="<?php echo $TS_VCSC_RowBackgroundOtherPattern; ?>">Patternbolt Pattern</option>
							<option value="otherparticles" <?php selected($TS_VCSC_RowBackgroundOtherParticle, "true"); ?> data-initial="<?php echo $TS_VCSC_RowBackgroundOtherParticle; ?>">Particlify Animation</option>
							<option value="othertriangle" <?php selected($TS_VCSC_RowBackgroundOtherTriangle, "true"); ?> data-initial="<?php echo $TS_VCSC_RowBackgroundOtherTriangle; ?>">Trianglify Pattern</option>
							<option value="videoyoutube" <?php selected($TS_VCSC_RowBackgroundVideoYoutube, "true"); ?> data-initial="<?php echo $TS_VCSC_RowBackgroundVideoYoutube; ?>">YouTube Video</option>
							<option value="videohtml5" <?php selected($TS_VCSC_RowBackgroundVideoHTML5, "true"); ?> data-initial="<?php echo $TS_VCSC_RowBackgroundVideoHTML5; ?>">Selfhosted HTML5 Video</option>
						</select>
						<div id="ts_vcsc_extend_settings_rowAllowableIndicatorWrap" class="" style="display: block; width: 100%; margin: 0;">
							<h3>Show Background Preview Indicator:</h3>							
							<?php
								if ($TS_VCSC_ComposerSectionElement == "true") {
									echo '<p style="font-size: 12px;">When a section or row background has been applied with the extended section or row options, a background indicator can be shown next to the section or row control options:</p>';
								} else {
									echo '<p style="font-size: 12px;">When a row background has been applied with the extended row options, a background indicator can be shown next to the row control options:</p>';
								}
								$settings = array(
									"param_name"        => "ts_vcsc_extend_settings_backgroundIndicator",
									"label"				=> "Show Background Indicator",
									"value"             => $ts_vcsc_extend_settings_backgroundIndicator,
									"order"				=> 1,
								);
								echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_backgroundIndicator);
							?>
						</div>
						<div id="ts_vcsc_extend_settings_rowAllowableBreakpointWrap" class="" style="display: block; width: 100%; margin: 20px 0 0 0;">							
							<?php
								if ($TS_VCSC_ComposerSectionElement == "true") {
									echo '<h3>Define Breakpoint for Section + Row Backgrounds:</h3>';
									echo '<p style="font-size: 12px;">Define the breakpoint (based on section or row width) to determine if a section or row background should be used or not:</p>';
									echo '<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
										This plugin provides a variety of background effects that can be applied to sections or rows. Those background effects are automatically removed on mobile devices but you can also define a breakpoint, based on section or row width, that is used on desktop devices to determine when a background effect should be disabled. When a section or row width falls below the defined breakpoint, the background effect applied to that section or row will be disabled automatically.
									</div>';
								} else {
									echo '<h3>Define Breakpoint for Row Backgrounds:</h3>';
									echo '<p style="font-size: 12px;">Define the breakpoint (based on row width) to determine if a row background should be used or not:</p>';
									echo '<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
										This plugin provides a variety of background effects that can be applied to rows. Those background effects are automatically removed on mobile devices but you can also define a breakpoint, based on row width, that is used on desktop devices to determine when a background effect should be disabled. When a row width falls below the defined breakpoint, the background effect applied to that row will be disabled automatically.
									</div>';
								}
							?>
							<div class="ts-nouislider-input-slider" style="margin-bottom: 40px; width: 100%;">								
								<?php
									if ($TS_VCSC_ComposerSectionElement == "true") {
										echo '<h4>Activate Background Effects for Sections + Rows larger than:</h4>';
									} else {
										echo '<h4>Activate Background Effects for Rows larger than:</h4>';
									}
								?>
								<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="ts_vcsc_extend_settings_additionsRowEffectsBreak" id="ts_vcsc_extend_settings_additionsRowEffectsBreak" class="ts_vcsc_extend_settings_additionsRowEffectsBreak ts-nouislider-serial nouislider-input-selector nouislider-input-composer" type="number" min="0" max="4096" step="1" value="<?php echo $ts_vcsc_extend_settings_additionsRowEffectsBreak; ?>"/>
								<span style="float: left; margin-right: 30px; margin-top: 10px;" class="unit">px</span>
								<div id="ts_vcsc_extend_settings_additionsRowEffectsBreak_slider" class="ts-nouislider-input ts-nouislider-settings-element" data-value="<?php echo $ts_vcsc_extend_settings_additionsRowEffectsBreak; ?>" data-min="0" data-max="4096" data-decimals="0" data-step="1" style="width: 250px; float: left; margin-top: 10px;"></div>
							</div>							
						</div>
					</div>					
					<div id="ts_vcsc_extend_settings_rowAllowableEffectsWrap" class="ts-multiselect-holder" style="display: block; height: auto; margin: 20px 0 10px 0; border: 1px solid #ededed; padding: 10px 20px; background: #f9f9f9;">
						<?php
							if ($TS_VCSC_ComposerSectionElement == "true") {
								echo '<h2>Enable Additional Section + Row Effect Options:</h2>';
							} else {
								echo '<h2>Enable Additional Row Effect Options:</h2>';
							}
						?>						
						<textarea name="ts_vcsc_extend_settings_rowAllowableEffectsValue" id="ts_vcsc_extend_settings_rowAllowableEffectsValue" class="" style="display: none;"></textarea >
						<select id="ts_vcsc_extend_settings_rowAllowableEffectsSelect" name="ts_vcsc_extend_settings_rowAllowableEffectsSelect" multiple="multiple" class="ts-multiple-options-selector" data-type="conditional" data-callback="" data-holder="ts_vcsc_extend_settings_rowAllowableEffectsValue">
							<option value="seperators" <?php selected($TS_VCSC_RowEffectSeperators, "true"); ?> data-initial="<?php echo $TS_VCSC_RowEffectSeperators; ?>"><?php echo ($TS_VCSC_ComposerSectionElement == "true" ? "Section + Row Seperators / Shapes" : "Row Seperators / Shapes") ?></option>
							<option value="overlays" <?php selected($TS_VCSC_RowEffectOverlays, "true"); ?> data-initial="<?php echo $TS_VCSC_RowEffectOverlays; ?>">Background Overlays</option>
							<option value="kenburns" <?php selected($TS_VCSC_RowEffectKenburns, "true"); ?> data-initial="<?php echo $TS_VCSC_RowEffectKenburns; ?>">Background KenBurns Effect</option>							
							<option value="blurring" <?php selected($TS_VCSC_RowEffectBlurring, "true"); ?> data-initial="<?php echo $TS_VCSC_RowEffectBlurring; ?>">Background Blur</option>						
						</select>
					</div>	
				</div>
			</div>
		</div>
		<div class="ts-vcsc-section-main">
			<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-layout"></i>Extended Column Options</div>
			<div class="ts-vcsc-section-content">
				<div style="margin-top: 10px; margin-bottom: 20px;">
					<div style="font-weight: bold; font-size: 14px; margin: 0;">Extend Options for WP Bakery Page Builder Columns:</div>
					<?php
						if ($TS_VCSC_ComposerNativeAnimation == "true") {
							echo '<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
								WP Bakery Page Builder introduced column viewport animations with v5.0.0 as native feature. While the column viewport animation feature from this addon does allow for some additional controls over the viewport animation that WP Bakery Page Builder does not, it is always better to use a native feature if available. If you are using the column viewport animation from this addon, ensure that you don not also use the native viewport animation option from WP Bakery Page Builder for the same column at the same time in order to avoid conflicts.
							</div>';
						}
					?>
					<p style="font-size: 12px;">Extend Column Options with Viewport Animation & Equal Height Settings:</p>
					<?php
						$settings = array(
							"param_name"        => "ts_vcsc_extend_settings_additionsColumns",
							"label"				=> "Extend Column Options",
							"value"             => $ts_vcsc_extend_settings_additionsColumns,
							"order"				=> 1,
						);
						echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_additionsColumns);
					?>
				</div>
			</div>
		</div>
	<?php } ?>
</div>