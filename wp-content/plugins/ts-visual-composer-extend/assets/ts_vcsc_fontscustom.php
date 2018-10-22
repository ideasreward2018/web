<?php
	global $VISUAL_COMPOSER_EXTENSIONS;

	if (isset($_POST['Submit'])) {
		echo '<div id="ts_vcsc_extend_settings_save" style="position: relative; margin: 20px auto 20px auto; width: 128px; height: 128px;">';
			echo TS_VCSC_CreatePreloaderCSS("ts-settings-panel-loader", "", 4, "false");
		echo '</div>';
		$TS_FontManager_Dataset 	= ((isset($_POST['ts-fonts-manager-dataset'])) ?	$_POST['ts-fonts-manager-dataset'] : array());
		update_option('ts_vcsc_extend_settings_fontCustoms', $TS_FontManager_Dataset);
		echo '<script> window.location="' . $_SERVER['REQUEST_URI'] . '"; </script> ';
		//Header('Location: '.$_SERVER['REQUEST_URI']);
		Exit();
	} else {
		$TS_FontManager_Dataset		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_RegisteredCustomFonts;
		$TS_FontManager_Counter		= 0;
	}
?>
<form id="ts-vcsc-fonts-manager-wrap" class="ts-vcsc-fonts-manager-wrap" name="oscimp_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<div id="ts-settings-about" class="tab-content">
		<div class="ts-vcsc-settings-group-header">
			<div class="display_header">
				<h2><span class="dashicons dashicons-editor-textcolor"></span>Composium - WP Bakery Page Builder Extensions v<?php echo TS_VCSC_GetPluginVersion(); ?> ... Custom Fonts Manager</h2>
			</div>
			<div class="clear"></div>
		</div>
		<div class="ts-vcsc-settings-group-topbar ts-vcsc-settings-group-buttonbar">
			<a href="javascript:void(0);" class="ts-vcsc-settings-group-toggle" style="display: none;">Expand</a>
			<div class="ts-vcsc-settings-group-actionbar">
				<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder ts-advanced-link-tooltip-right ts-advanced-link-tooltip-bottom">
					<span class="ts-advanced-link-tooltip-content"><?php _e("Click here to save your custom font definitions.", "ts_visual_composer_extend"); ?></span>
					<button type="submit" name="Submit" id="ts-fonts-manager-submit-1" class="ts-advanced-link-button-main ts-advanced-link-button-blue ts-advanced-link-button-save" style="margin: 0;">
						<?php echo __("Save Fonts", "ts_visual_composer_extend"); ?>
					</button>
				</div>				
			</div>
			<div class="clear"></div>
		</div>	
		<div class="ts-vcsc-settings-fonts-main">
			<div class="ts-vcsc-section-main">
				<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-info"></i>General Information</div>
				<div class="ts-vcsc-section-content">
					<?php
						if (current_user_can('manage_options')) {
							echo '<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder" style="margin: 10px auto;">
								<span class="ts-advanced-link-tooltip-content">' . __("Click here to return to the plugins settings page.", "ts_visual_composer_extend") . '</span>
								<a href="' . $VISUAL_COMPOSER_EXTENSIONS->settingsLink . '" target="_parent" class="ts-advanced-link-button-main ts-advanced-link-button-grey ts-advanced-link-button-settings">'. __("Back to Settings", "ts_visual_composer_extend") . '</a>
							</div>';
						}
					?>	
					<div class="ts-vcsc-notice-field ts-vcsc-success" style="margin-top: 10px; font-size: 13px; text-align: justify;">
						If you are using other plugins or a theme that allows you to utilize custom fonts and you would like to use those fonts within the elements of this plugin that already provide access to standard and Google fonts, you can use the manager below to define the core information for your custom fonts, so that the fonts can be added to the font manager option within the element settings panel. You will require the official name of the custom font, as it is used in the CSS file defining the font, as well as a name string that should be used to identify the font within the font manager.
					</div>	
				</div>
			</div>
			<div class="ts-vcsc-section-main">
				<div class="ts-vcsc-section-title ts-vcsc-section-hide"><i class="dashicons-editor-help"></i>How to use the Custom Fonts Manager</div>
				<div class="ts-vcsc-section-content slideFade" style="display: none;">
					<div class="ts-vcsc-notice-field ts-vcsc-critical" style="margin-top: 10px; font-size: 13px; text-align: justify;">
						The custom font manager will ask you for several information for each font; some of those are are required, while others are optional. By default, the plugin will simply assume that the font is already loaded and handled by another plugin or your theme; but you can provide the path to the underlying CSS file and set the plugin to load the font for you, if necessary.
					</div>
					<span style="display: block; width: 100%; padding: 0; margin: 10px 0; font-weight: bold; font-size: 14px;">REQUIRED:</span>
					<span style="display: block; width: 100%; padding: 0; margin: 10px 0;"><strong>Font Family:</strong> The exact name of the font as it is used in the underlying CSS file (as used in the @font-face rule) to identify the font so it can later be assigned to page content.</span>
					<span style="display: block; width: 100%; padding: 0; margin: 10px 0; font-weight: bold; font-size: 14px;">OPTIONAL:</span>
					<span style="display: block; width: 100%; padding: 0; margin: 10px 0;"><strong>Favorite Font:</strong> If activated, this font will be listed under the "Favorite Fonts" section within the font picker parameters within the WP Bakery Page Builder elements, instead of the global "Custom Fonts" section.</span>					
					<span style="display: block; width: 100%; padding: 0; margin: 10px 0;"><strong>Font Path:</strong> The absolute path to the underlying CSS file for the font. This setting is optional and can be left empty if another plugin or theme is already loading the CSS file.</span>
					<span style="display: block; width: 100%; padding: 0; margin: 10px 0;"><strong>Load Never:</strong> If selected, the plugin will never load this font, even if a path to the underlying CSS file is present; <i>please ensure that another plugin/theme is loading the font file</i>.</span>
					<span style="display: block; width: 100%; padding: 0; margin: 10px 0;"><strong>Load On Demand:</strong> If selected and provided a path to the underlying CSS file is present, the plugin will attempt to load this font only on those posts or pages where the font is assigned to an element using the font picker parameter.</span>
					<span style="display: block; width: 100%; padding: 0; margin: 10px 0;"><strong>Load Always:</strong> If selected and provided a path to the underlying CSS file is present, the plugin will attempt to load this font at all times, even on posts or pages where it is not assigned to an element.</span>
					<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 20px; font-size: 13px; text-align: justify;">
						You can drag and drop the individual fonts in order to create a custom order at which the fonts will be listed within the font picker parameters within WP Bakery Page Builder. Simply click on the "Move" button, and while keeping your mouse button pressed down, drag/move the font to its desired position within the list.
					</div>	
				</div>
			</div>			
			<div class="ts-vcsc-section-main">
				<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-editor-textcolor"></i>Custom Fonts Manager</div>
				<div class="ts-vcsc-section-content">				
					<div id="ts-fonts-manager-repeater" class="ts-fonts-manager-repeater clearFixMe">
						<div class="ts-fonts-manager-wrapper">
							<div class="ts-fonts-manager-messages" style="display: none;">
								<span id="ts-fonts-manager-string-ok"><?php _e("OK", "ts_visual_composer_extend"); ?></span>
								<span id="ts-fonts-manager-string-confirm"><?php _e("Confirm", "ts_visual_composer_extend"); ?></span>
								<span id="ts-fonts-manager-string-cancel"><?php _e("Cancel", "ts_visual_composer_extend"); ?></span>
								<span id="ts-fonts-manager-string-understood"><?php _e("Understood", "ts_visual_composer_extend"); ?></span>
								<span id="ts-fonts-manager-string-title"><?php _e("Custom Fonts Manager", "ts_visual_composer_extend"); ?></span>
								<span id="ts-fonts-manager-string-nonewline"><?php _e("Please complete all required fields for the existing fonts before a new font can be added.", "ts_visual_composer_extend"); ?></span>
								<span id="ts-fonts-manager-string-delete"><?php _e("Do you really want to delete this font?", "ts_visual_composer_extend"); ?></span>
								<span id="ts-fonts-manager-string-unknown"><?php _e("N/A", "ts_visual_composer_extend"); ?></span>
							</div>
							<div class="ts-fonts-manager-serial" style="display: none;">
								<textarea class="ts-fonts-manager-dataset" name="ts-fonts-manager-dataset"></textarea>
							</div>
							<div class="ts-fonts-manager-new">
								<span class="ts-fonts-manager-add"><?php _e("Add New Font", "ts_visual_composer_extend"); ?></span>
							</div>
							<div class="ts-fonts-manager-groups">
								<div class="ts-fonts-manager-row ts-fonts-manager-template clearFixMe" style="display: none;">
									<span class="ts-fonts-manager-label"><?php _e("Font Family:", "ts_visual_composer_extend"); ?></span>
									<input type="text" class="ts-fonts-manager-input" name="ts-fonts-manager-input[{{row-count-placeholder}}][family]" data-required="true" data-key="family" data-base="ts-fonts-manager-input[{{row-count-placeholder}}][family]" value=""/>
									<input type="checkbox" class="ts-fonts-manager-input" name="ts-fonts-manager-input[{{row-count-placeholder}}][favorite]" data-required="false" data-key="favorite" data-base="ts-fonts-manager-input[{{row-count-placeholder}}][favorite]" value="0"/>
									<div class="ts-fonts-manager-label"><?php _e("Favorite Font", "ts_visual_composer_extend"); ?></div>
									<span class="ts-fonts-manager-remove"><?php _e("Remove", "ts_visual_composer_extend"); ?></span>
									<span class="ts-fonts-manager-sortit"><?php _e("Move", "ts_visual_composer_extend"); ?></span>
									<div class="ts-fonts-manager-split"></div>
									<span class="ts-fonts-manager-label"><?php _e("Font Path:", "ts_visual_composer_extend"); ?></span>
									<input type="text" class="ts-fonts-manager-input" name="ts-fonts-manager-input[{{row-count-placeholder}}][path]" data-required="false" data-key="path" data-base="ts-fonts-manager-input[{{row-count-placeholder}}][path]" value=""/>
									<input type="radio" class="ts-fonts-manager-input" name="ts-fonts-manager-input[{{row-count-placeholder}}][load]" data-required="false" data-key="load" data-base="ts-fonts-manager-input[{{row-count-placeholder}}][load]" data-checked="true" value="never" checked="checked"/>
									<div class="ts-fonts-manager-label"><?php _e("Load Never", "ts_visual_composer_extend"); ?></div>
									<input type="radio" class="ts-fonts-manager-input" name="ts-fonts-manager-input[{{row-count-placeholder}}][load]" data-required="false" data-key="load" data-base="ts-fonts-manager-input[{{row-count-placeholder}}][load]" data-checked="false" value="demand"/>
									<div class="ts-fonts-manager-label"><?php _e("Load On Demand", "ts_visual_composer_extend"); ?></div>
									<input type="radio" class="ts-fonts-manager-input" name="ts-fonts-manager-input[{{row-count-placeholder}}][load]" data-required="false" data-key="load" data-base="ts-fonts-manager-input[{{row-count-placeholder}}][load]" data-checked="false" value="always"/>
									<div class="ts-fonts-manager-label"><?php _e("Load Always", "ts_visual_composer_extend"); ?></div>
								</div>
								<?php
									if (count($TS_FontManager_Dataset) > 0) {
										foreach ($TS_FontManager_Dataset as $fonts => $font) {
											echo '<div class="ts-fonts-manager-row clearFixMe" style="display: block;">';
												echo '<span class="ts-fonts-manager-label">' . __("Font Family:", "ts_visual_composer_extend") . '</span>';
												echo '<input type="text" class="ts-fonts-manager-input" name="ts-fonts-manager-input[' . $TS_FontManager_Counter . '][family]" data-required="true" data-key="family" data-base="ts-fonts-manager-input[{{row-count-placeholder}}][family]" value="' . base64_decode($font->family) . '"/>';
												echo '<input type="checkbox" class="ts-fonts-manager-input" name="ts-fonts-manager-input[' . $TS_FontManager_Counter . '][favorite]" data-required="false" data-key="favorite" data-base="ts-fonts-manager-input[{{row-count-placeholder}}][load]" value="' . ($font->load == true ? "1" : "0") . '" ' . (checked($font->favorite, true, false)) . '/>';
												echo '<span class="ts-fonts-manager-label">' . __("Favorite Font", "ts_visual_composer_extend") . '</span>';		
												echo '<span class="ts-fonts-manager-remove">' . __("Remove", "ts_visual_composer_extend") . '</span>';
												echo '<span class="ts-fonts-manager-sortit">' . __("Move", "ts_visual_composer_extend") . '</span>';
												echo '<div class="ts-fonts-manager-split"></div>';
												echo '<span class="ts-fonts-manager-label">' . __("Font Path:", "ts_visual_composer_extend") . '</span>';
												echo '<input type="text" class="ts-fonts-manager-input" name="ts-fonts-manager-input[' . $TS_FontManager_Counter . '][path]" data-required="false" data-key="path" data-base="ts-fonts-manager-input[{{row-count-placeholder}}][path]" value="' . rawurldecode($font->path) . '"/>';
												echo '<input type="radio" class="ts-fonts-manager-input" name="ts-fonts-manager-input[' . $TS_FontManager_Counter . '][load]" data-required="false" data-key="load" data-base="ts-fonts-manager-input[{{row-count-placeholder}}][load]" data-checked="' . ($font->load == "never" ? "true" : "false") . '" value="never" ' . (checked($font->load, "never", false)) . '/>';
												echo '<span class="ts-fonts-manager-label">' . __("Load Never", "ts_visual_composer_extend") . '</span>';
												echo '<input type="radio" class="ts-fonts-manager-input" name="ts-fonts-manager-input[' . $TS_FontManager_Counter . '][load]" data-required="false" data-key="load" data-base="ts-fonts-manager-input[{{row-count-placeholder}}][load]" data-checked="' . ($font->load == "demand" ? "true" : "false") . '" value="demand" ' . (checked($font->load, "demand", false)) . '/>';
												echo '<span class="ts-fonts-manager-label">' . __("Load On Demand", "ts_visual_composer_extend") . '</span>';
												echo '<input type="radio" class="ts-fonts-manager-input" name="ts-fonts-manager-input[' . $TS_FontManager_Counter . '][load]" data-required="false" data-key="load" data-base="ts-fonts-manager-input[{{row-count-placeholder}}][load]" data-checked="' . ($font->load == "always" ? "true" : "false") . '" value="always" ' . (checked($font->load, "always", false)) . '/>';
												echo '<span class="ts-fonts-manager-label">' . __("Load Always", "ts_visual_composer_extend") . '</span>';
											echo '</div>';
											$TS_FontManager_Counter++;
										}
									}
								?>
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div>
	</div>
	<div class="ts-vcsc-settings-group-bottombar ts-vcsc-settings-group-buttonbar" style="">
		<div class="ts-vcsc-settings-group-actionbar">
			<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder ts-advanced-link-tooltip-right">
				<span class="ts-advanced-link-tooltip-content"><?php _e("Click here to save your custom font definitions.", "ts_visual_composer_extend"); ?></span>
				<button type="submit" name="Submit" id="ts-fonts-manager-submit-2" class="ts-advanced-link-button-main ts-advanced-link-button-blue ts-advanced-link-button-save" style="margin: 0;">
					<?php _e("Save Fonts", "ts_visual_composer_extend"); ?>
				</button>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</form>