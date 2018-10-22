<?php
	global $VISUAL_COMPOSER_EXTENSIONS;
	
	$Count_bbPress 								= 0;
	foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_bbPress_Elements as $ElementName => $element) {
		if ($element['deprecated'] == 'false') {
			$Count_bbPress++;
		}
	}
?>
<div id="ts-settings-bbpress" class="tab-content">
	<div class="ts-vcsc-section-main">
		<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-info"></i>General Information</div>
		<div class="ts-vcsc-section-content">
			<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder" style="margin: 20px 0;">
				<span class="ts-advanced-link-tooltip-content"><?php _e("Click here to view the documentation for all official bbPress shortcodes.", "ts_visual_composer_extend"); ?></span>
				<a class="ts-advanced-link-button-main ts-advanced-link-button-silver ts-advanced-link-button-groups" href="http://codex.bbpress.org/shortcodes/" target="_blank" style="margin: 0;">
					<?php echo __("bbPress Shortcodes", "ts_visual_composer_extend"); ?>
				</a>
			</div>	
			<div class="ts-vcsc-notice-field ts-vcsc-success" style="margin-top: 10px; font-size: 13px; text-align: justify;">
				Visual Composer Extensions includes a set of elements that can be used to embed the shortcodes that are part of bbPress with Visual Composer. No extra styling will be applied; all standard shortcodes will be processed by bbPress directly. If you encounter errors or styling issues with any of the standard shortcodes, please turn to bbPress for a solution as Visual Composer Extensions does not handle the shortcodes.
			</div>
		</div>
	</div>
	<div class="ts-vcsc-section-main">
		<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-admin-comments"></i>Manage bbPress Elements <span class="ts-vcsc-element-count">(<i class="dashicons-image-filter"></i> <?php echo ($Count_bbPress); ?>)</span></div>
		<div class="ts-vcsc-section-content">
			<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
				While you can prevent individual elements from becoming available to certain user groups (using the "User Group Access Rules" in the settings for the original Visual Composer Plugin), the elements are technically still loaded in the background. In order to allow for an improved overall site performance, you can completely disable unwanted elements that are part of Visual Composer Extensions here. Once disabled, the element itself will not be loaded anymore. The WooCommerce plugin will still load the associated shortcode, however.
			</div>
			<?php
				echo '<div style="width: 30%; float: left; min-width: 275px; margin-right: 5%;">';
					echo '<h4>Standard Shortcodes</h4>';
					echo '<p style="font-size: 12px; text-align: justify;">These elements reflect the standard shortcodes already included in bbPress.</p>';
					foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_bbPress_Elements as $ElementName => $element) {
						if (($element['type'] == 'internal') && ($element['deprecated'] == 'false')) {
							echo '<div style="margin: 0 0 10px 0; width: 30%; float: left; min-width: 360px; margin-right: 3%;">';
								$settings = array(
									"param_name"        => 'ts_vcsc_extend_settings_bbpress' . $element['setting'],
									"label"				=> 'Enable "' . $ElementName . '" <span class="ts-vcsc-element-count">(1)</span>',
									"value"             => $element['active'],
									"order"				=> 1,
								);
								echo TS_VCSC_CodeStarButton_Settings_Field($settings, $element['active']);			
							echo '</div>';
						}
					}
				echo '</div>';
				echo '<div style="width: 30%; float: left; min-width: 275px; margin-right: 5%;">';
					echo '<h4>Custom Shortcodes</h4>';
					echo '<p style="font-size: 12px; text-align: justify;">These elements reflect custom shortcodes that are part of Visual Composer Extensions.</p>';
					foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_bbPress_Elements as $ElementName => $element) {
						if (($element['type'] == 'class') && ($element['deprecated'] == 'false')) {
							echo '<div style="margin: 0 0 10px 0; width: 30%; float: left; min-width: 360px; margin-right: 3%;">';
								$settings = array(
									"param_name"        => 'ts_vcsc_extend_settings_bbpress' . $element['setting'],
									"label"				=> 'Enable "' . $ElementName . '" <span class="ts-vcsc-element-count">(1)</span>',
									"value"             => $element['active'],
									"order"				=> 1,
								);
								echo TS_VCSC_CodeStarButton_Settings_Field($settings, $element['active']);			
							echo '</div>';
						}
					}
				echo '</div>';
				echo '<div style="width: 30%; float: left; min-width: 275px; margin-right: 0%;">';
					echo '<h4>Deprecated Shortcodes</h4>';
					echo '<p style="font-size: 12px; text-align: justify;">These elements have been deprecated in favor of other elements.</p>';
					foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_bbPress_Elements as $ElementName => $element) {
						if (($element['type'] == 'class') && ($element['deprecated'] == 'true')) {
							echo '<div style="margin: 0 0 10px 0; width: 30%; float: left; min-width: 360px; margin-right: 3%;">';
								$settings = array(
									"param_name"        => 'ts_vcsc_extend_settings_bbpress' . $element['setting'],
									"label"				=> 'Enable "' . $ElementName . '" <span class="ts-vcsc-element-count">(1)</span>',
									"value"             => $element['active'],
									"order"				=> 1,
								);
								echo TS_VCSC_CodeStarButton_Settings_Field($settings, $element['active']);			
							echo '</div>';
						}
					}
				echo '</div>';
			?>	
			<div class="clear clearFixMe"></div>
		</div>
	</div>
</div>