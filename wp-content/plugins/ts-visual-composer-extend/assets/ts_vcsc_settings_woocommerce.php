<?php
	global $VISUAL_COMPOSER_EXTENSIONS;
	
	$Count_WooCommerce 							= 0;
	foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_WooCommerce_Elements as $ElementName => $element) {
		if ($element['deprecated'] == 'false') {
			$Count_WooCommerce++;
		}
	}
?>
<div id="ts-settings-woocommerce" class="tab-content">
	<div class="ts-vcsc-section-main">
		<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-info"></i>General Information</div>
		<div class="ts-vcsc-section-content">
			<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder" style="margin: 20px 0;">
				<span class="ts-advanced-link-tooltip-content"><?php _e("Click here to view the documentation for all official WooCommerce shortcodes.", "ts_visual_composer_extend"); ?></span>
				<a class="ts-advanced-link-button-main ts-advanced-link-button-silver ts-advanced-link-button-purchase" href="http://docs.woothemes.com/document/woocommerce-shortcodes/" target="_blank" style="margin: 0;">
					<?php echo __("WooCommerce Shortcodes", "ts_visual_composer_extend"); ?>
				</a>
			</div>			
			<div class="ts-vcsc-notice-field ts-vcsc-success" style="margin-top: 10px; font-size: 13px; text-align: justify;">
				Starting with v4.4.0, WP Bakery Page Builder itself includes a set of elements that can be used to directly embed the shortcodes that are part of WooCommerce with WP Bakery Page Builder. No extra styling will be applied; all standard shortcodes will be processed by WooCommerce directly. "Composium - WP Bakery Page Builder Extensions" will add some additional elements and shortcodes to WP Bakery Page Builder, that will allow you to display WooCommerce products in layouts that are not part of WooCommerce.
			</div>
		</div>
	</div>
	<div class="ts-vcsc-section-main">
		<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-cart"></i>Manage WooCommerce Elements <span class="ts-vcsc-element-count">(<i class="dashicons-image-filter"></i> <?php echo ($Count_WooCommerce); ?>)</span></div>
		<div class="ts-vcsc-section-content">
			<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
				While you can prevent individual elements from becoming available to certain user groups (using the "User Group Access Rules" in the settings for the original WP Bakery Page Builder plugin), the elements are technically still loaded in the background. In order to allow for an improved overall site performance, you can completely disable unwanted elements that are part of "Composium - WP Bakery Page Builder Extensions" here. Once disabled, the element and associated shortcode will not be loaded anymore.
			</div>
			<?php
				echo '<div style="width: 45%; display: inline-block; vertical-align: top; min-width: 275px; margin-right: 5%;">';
					echo '<h4>Custom Shortcodes</h4>';
					echo '<p style="font-size: 12px; text-align: justify;">These elements reflect custom shortcodes that are part of "Composium - WP Bakery Page Builder Extensions".</p>';		
					foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_WooCommerce_Elements as $ElementName => $element) {
						if (($element['type'] == 'class') && ($element['deprecated'] == 'false')) {
							echo '<div style="margin: 0 0 10px 0; width: 30%; float: left; min-width: 360px; margin-right: 3%;">';
								$settings = array(
									"param_name"        => 'ts_vcsc_extend_settings_woocommerce' . $element['setting'],
									"label"				=> 'Enable "' . $ElementName . '" <span class="ts-vcsc-element-count">(1)</span>',
									"value"             => $element['active'],
									"order"				=> 1,
								);
								echo TS_VCSC_CodeStarButton_Settings_Field($settings, $element['active']);			
							echo '</div>';
						}
					}
				echo '</div>';
				echo '<div style="width: 45%; display: inline-block; vertical-align: top; min-width: 275px; margin-right: 0%;">';
					echo '<h4>Deprecated Shortcodes</h4>';
					echo '<p style="font-size: 12px; text-align: justify;">These elements have been deprecated in favor of other elements.</p>';
					foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_WooCommerce_Elements as $ElementName => $element) {
						if (($element['type'] == 'class') && ($element['deprecated'] == 'true')) {
							echo '<div style="margin: 0 0 10px 0; width: 30%; float: left; min-width: 360px; margin-right: 3%;">';
								$settings = array(
									"param_name"        => 'ts_vcsc_extend_settings_woocommerce' . $element['setting'],
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
		</div>
	</div>
</div>