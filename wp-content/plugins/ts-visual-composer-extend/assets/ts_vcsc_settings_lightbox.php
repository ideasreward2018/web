<?php
	global $VISUAL_COMPOSER_EXTENSIONS;
	$Lightbox_Animation				= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('animation', $TS_VCSC_Lightbox_Defaults))) 		? $TS_VCSC_Lightbox_Defaults['animation'] 		: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['animation']);
	$Lightbox_OverlayColor			= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('overlay', $TS_VCSC_Lightbox_Defaults))) 		? $TS_VCSC_Lightbox_Defaults['overlay'] 		: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['overlay']);
	$Lightbox_BackgroundImage		= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('background', $TS_VCSC_Lightbox_Defaults))) 	? $TS_VCSC_Lightbox_Defaults['background'] 		: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['background']);
	$Lightbox_BackgroundRepeat		= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('repeat', $TS_VCSC_Lightbox_Defaults))) 		? $TS_VCSC_Lightbox_Defaults['repeat'] 			: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['repeat']);
	$Lightbox_NoisePattern			= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('noise', $TS_VCSC_Lightbox_Defaults))) 			? $TS_VCSC_Lightbox_Defaults['noise'] 			: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['noise']);
	$Lightbox_ButtonScheme			= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('scheme', $TS_VCSC_Lightbox_Defaults))) 		? $TS_VCSC_Lightbox_Defaults['scheme'] 			: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['scheme']);
	$Lightbox_AllowSave				= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('save', $TS_VCSC_Lightbox_Defaults))) 			? $TS_VCSC_Lightbox_Defaults['save'] 			: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['save']);
	$Lightbox_AllowShare			= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('share', $TS_VCSC_Lightbox_Defaults))) 			? $TS_VCSC_Lightbox_Defaults['share'] 			: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['share']);
	$Lightbox_AllowLoadAPIs			= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('loadapis', $TS_VCSC_Lightbox_Defaults))) 		? $TS_VCSC_Lightbox_Defaults['loadapis'] 		: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['loadapis']);
	$Lightbox_SocialNetworks		= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('social', $TS_VCSC_Lightbox_Defaults))) 		? $TS_VCSC_Lightbox_Defaults['social'] 			: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['social']);
	$Lightbox_AllowTouchSwipe		= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('notouch', $TS_VCSC_Lightbox_Defaults))) 		? $TS_VCSC_Lightbox_Defaults['notouch'] 		: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['notouch']);
	$Lightbox_AllowKeyboard			= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('keyboard', $TS_VCSC_Lightbox_Defaults))) 		? $TS_VCSC_Lightbox_Defaults['keyboard'] 		: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['keyboard']);
	$Lightbox_AllowZoom				= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('zoom', $TS_VCSC_Lightbox_Defaults))) 			? $TS_VCSC_Lightbox_Defaults['zoom'] 			: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['zoom']);
	$Lightbox_AllowFullscreen		= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('fullscreen', $TS_VCSC_Lightbox_Defaults))) 	? $TS_VCSC_Lightbox_Defaults['fullscreen'] 		: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['fullscreen']);
	$Lightbox_CloseButton			= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('closer', $TS_VCSC_Lightbox_Defaults))) 		? $TS_VCSC_Lightbox_Defaults['closer'] 			: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['closer']);
	$Lightbox_BackgroundClose		= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('bgclose', $TS_VCSC_Lightbox_Defaults))) 		? $TS_VCSC_Lightbox_Defaults['bgclose'] 		: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['bgclose']);
	$Lightbox_RemoveHashtag			= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('nohashes', $TS_VCSC_Lightbox_Defaults))) 		? $TS_VCSC_Lightbox_Defaults['nohashes'] 		: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['nohashes']);
	$Lightbox_RemoveLight 			= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('removelight', $TS_VCSC_Lightbox_Defaults))) 	? $TS_VCSC_Lightbox_Defaults['removelight'] 	: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['removelight']);
	$Lightbox_CustomLight			= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('customlight', $TS_VCSC_Lightbox_Defaults))) 	? $TS_VCSC_Lightbox_Defaults['customlight'] 	: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['customlight']);
	$Lightbox_BackColor				= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('customcolor', $TS_VCSC_Lightbox_Defaults))) 	? $TS_VCSC_Lightbox_Defaults['customcolor'] 	: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['customcolor']);
	$Lightbox_URLScan				= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('urlcolorscan', $TS_VCSC_Lightbox_Defaults))) 	? $TS_VCSC_Lightbox_Defaults['urlcolorscan'] 	: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['urlcolorscan']);
	$Lightbox_AllowCORS				= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('cors', $TS_VCSC_Lightbox_Defaults))) 			? $TS_VCSC_Lightbox_Defaults['cors'] 			: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['cors']);
	$Lightbox_TapToNext				= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('tapping', $TS_VCSC_Lightbox_Defaults))) 		? $TS_VCSC_Lightbox_Defaults['tapping'] 		: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['tapping']);
	$Lightbox_ScrollBlock			= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('scrollblock', $TS_VCSC_Lightbox_Defaults)))	? $TS_VCSC_Lightbox_Defaults['scrollblock']		: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['scrollblock']);
	$Lightbox_Protection			= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('protection', $TS_VCSC_Lightbox_Defaults)))		? $TS_VCSC_Lightbox_Defaults['protection']		: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['protection']);
	$Lightbox_SpeedFX				= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('fxspeed', $TS_VCSC_Lightbox_Defaults)))		? $TS_VCSC_Lightbox_Defaults['fxspeed']			: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['fxspeed']);
	$Lightbox_HistoryClose			= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('historyclose', $TS_VCSC_Lightbox_Defaults)))	? $TS_VCSC_Lightbox_Defaults['historyclose']	: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['historyclose']);
	$Lightbox_CustomScroll			= (((is_array($TS_VCSC_Lightbox_Defaults)) && (array_key_exists('customscroll', $TS_VCSC_Lightbox_Defaults)))	? $TS_VCSC_Lightbox_Defaults['customscroll']	: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Setting_Defaults['customscroll']);
	//var_dump($TS_VCSC_Lightbox_Defaults);
?>
<div id="ts-settings-lightbox" class="tab-content">
	<div class="ts-vcsc-section-main">
		<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-images-alt2"></i>Use Built-In Lightbox</div>
		<div class="ts-vcsc-section-content">
			<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
				This add-on includes a built-in lightbox script, which is used for many of the elements that are part of the add-on. It is highly advised to use this built-in lightbox script as all elements are specifically designed around it. But sometimes, your site might be using another lightbox script already, that for some reason is automatically applying itself to all media links, causing two lightboxes to be opened once a user clicks on any of those links. In those rare cases, you can disable the built-in lightbox solution here.
			</div>
			<div class="ts-vcsc-notice-field ts-vcsc-critical" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify; font-weight: bold;">
				If you decide to disable the built-in lightbox solution, we can not guaranty (full) functionality of any of the elements coming from this add-on, which utilize the lightbox. Some elements use unique features of the built-in lightbox solution and will therefore not work at all if the lightbox is disabled, particularly elements that utilize the so-called "Rectangle Auto Grid" layout. Disable at your own risk!
			</div>
			<div style="margin-top: 20px; margin-bottom: 10px;">
				<h4>Use Built-In Lightbox Solution:</h4>
				<p style="font-size: 12px;">Allow the add-on to use its built-in and element optimized lightbox solution:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_builtinLightbox",
						"label"				=> "Use Built-In Lightbox Solution",
						"value"             => $ts_vcsc_extend_settings_builtinLightbox,
						"order"				=> 7,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_builtinLightbox);
				?>
			</div>	
		</div>
	</div>
	<div id="ts_vcsc_extend_settings_builtinLightbox_true" class="ts-vcsc-section-main" style="display: <?php echo ($ts_vcsc_extend_settings_builtinLightbox == 1 ? 'block' : 'none'); ?>;">
		<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-images-alt2"></i>Lightbox Settings</div>
		<div class="ts-vcsc-section-content">
			<h2>External Hooks</h2>
			<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
				These settings can be used to attach the lightbox to media elements that are created without any of the media elements this plugin provides for. Activating any of these settings will automatically cause the lightbox files to be loaded on all pages and posts.
			</div>
			<div style="margin-top: 20px;">
				<h4>Incorporate into "Add-Media" Process:</h4>
				<p style="font-size: 12px;">Define if the lightbox should automatically add a custom class name ("ts-lightbox-integration") and data-title attribute to image links created via the tinyMCE "Add Media" button, so
				those image links can be opened with the lightbox as well.</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_lightboxIntegration",
						"label"				=> 'Enable "Add-Media" Integration',
						"value"             => $ts_vcsc_extend_settings_lightboxIntegration,
						"order"				=> 7,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_lightboxIntegration);
				?>
			</div>
			<div style="margin-top: 20px;">
				<h4>Replace WP Bakery Page Builder's PrettyPhoto:</h4>
				<p style="font-size: 12px;">Define if the lightbox should attempt to replace the PrettyPhoto lightbox script that is used within WP Bakery Page Builder itself, and instead attach this lightbox solution to WP Bakery Page Builder's native "Image Gallery", "Image Carousel","Single Image" and "Media Grid" elements. Using this option will block WP Bakery Page Builder from loading the PrettyPhoto script alltogether, so use only in you know for sure that the "PrettyPhoto" script is not required by any other native VC elements used on your site that are not (yet) covered by this routine.</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_lightboxPrettyPhoto",
						"label"				=> "Replace WP Bakery Page Builder's PrettyPhoto",
						"value"             => $ts_vcsc_extend_settings_lightboxPrettyPhoto,
						"order"				=> 7,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_lightboxPrettyPhoto);
				?>
			</div>
			<div style="margin-top: 20px;">
				<h4>Attach to Class-Less Image Links:</h4>
				<p style="font-size: 12px;">Define if the lightbox should attempt to attach itself to all image links on a page or post that do not carry any class names and are therefore most likely unassociated with any other lightbox.</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_lightboxAttachAllOther",
						"label"				=> "Attach to All Image Links Without Class",
						"value"             => $ts_vcsc_extend_settings_lightboxAttachAllOther,
						"order"				=> 7,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_lightboxAttachAllOther);
				?>
			</div>
			<h2 style="margin-top: 30px;">Background Styling + Button Theme</h2>
			<div style="margin-top: 20px;">
				<h4>Lightbox Background (Overlay) Color:</h4>
				<p style="font-size: 12px;">Define the lightbox background (overlay) color and opacity by using the color and alpha picker below:</p>
				<div class="ts-color-group">
					<input id="ts_vcsc_extend_settings_defaultLightboxOverlay" name="ts_vcsc_extend_settings_defaultLightboxOverlay" data-error="Lightbox - Overlay Color" data-order="9" class="validate[required,funcCall[checkColorPickerSyntax]] ts_vcsc_extend_settings_defaultLightboxOverlay ts-color-control" data-alpha="true" type="text" value="<?php echo $Lightbox_OverlayColor; ?>"/>
				</div>
			</div>
			<div style="margin-top: 20px;">
				<h4>Lightbox Background Image:</h4>
				<p style="font-size: 12px;">Select the image that should be used for the lightbox background, instead of a color overlay:</p>
				<div class="ts-vcsc-notice-field ts-vcsc-critical" style="margin-top: 10px; font-size: 13px; text-align: justify; font-weight: bold;">
					A selected background image will ALWAYS overwrite the color overlay setting above! If "no-repeat" has been selected, ensure that your selected image is sufficient enough to accomodate large screens.
				</div>	
				<div id="ts_vcsc_extend_settings_defaultLightboxBackgroundHolder">
					<input id="ts_vcsc_extend_settings_defaultLightboxBackground" class="ts_vcsc_extend_settings_defaultLightboxBackground" type="hidden" size="36" name="ts_vcsc_extend_settings_defaultLightboxBackground" value="<?php echo $Lightbox_BackgroundImage; ?>" /> 
					<label class="labelToggleBox" for="ts_vcsc_extend_settings_defaultLightboxUploader" style="margin-left: 0px; margin-right: 10px;">Select or Upload a Background Image:</label>
					<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder ts-advanced-link-tooltip-right">
						<span class="ts-advanced-link-tooltip-content"><?php _e("Click here to select the background image you want to show whenever the lightbox is opened.", "ts_visual_composer_extend"); ?></span>
						<button type="button" <?php echo ($Lightbox_BackgroundImage != '' ? 'disabled="disabled"' : ''); ?> id="ts_vcsc_extend_settings_defaultLightboxUploader" class="ts_vcsc_extend_settings_defaultLightboxUploader ts-advanced-link-button-main ts-advanced-link-button-silver ts-advanced-link-button-image" style="margin: 0;">
							<?php echo __("Background Image", "ts_visual_composer_extend"); ?>
						</button>
					</div>				
				</div>
				<div id="ts_vcsc_extend_settings_defaultLightboxImageHolder" style="display: <?php echo ($Lightbox_BackgroundImage != '' ? 'block' : 'none'); ?>;">
					<span id="ts_vcsc_extend_settings_defaultLightboxImageRemove" title="Remove Background Image for Lightbox"><i class="dashicons dashicons-no"></i></span>
					<img id="ts_vcsc_extend_settings_defaultLightboxImageDisplay" class="ts_vcsc_extend_settings_defaultLightboxImage" src="<?php echo $Lightbox_BackgroundImage; ?>"/>
                    <label class="Uniform" style="display: inline-block; margin-left: 0px; width: 148px;" for="ts_vcsc_extend_settings_defaultLightboxRepeat">Background Repeat:</label>
                    <select id="ts_vcsc_extend_settings_defaultLightboxRepeat" name="ts_vcsc_extend_settings_defaultLightboxRepeat" style="width: 198px; margin: 0;">
                        <option value="no-repeat" <?php selected('no-repeat', 	$Lightbox_BackgroundRepeat); ?>>No Repeat</option>
                        <option value="repeat" <?php selected('repeat', 		$Lightbox_BackgroundRepeat); ?>>Repeat X + Y</option>
                        <option value="repeat x" <?php selected('repeat x', 	$Lightbox_BackgroundRepeat); ?>>Repeat X</option>
						<option value="repeat y" <?php selected('repeat y', 	$Lightbox_BackgroundRepeat); ?>>Repeat Y</option>
                    </select>
				</div>
			</div>			
			<div style="margin-top: 20px;">
				<h4>Lightbox Noise Pattern:</h4>
				<p style="font-size: 12px;">Select an optional noise pattern for the lightbox overlay (should only be used with semi-transparent overlay or lightly colored overlay):</p>
				<select id="ts_vcsc_extend_settings_defaultLightboxNoise" name="ts_vcsc_extend_settings_defaultLightboxNoise" data-background="true" data-width="100" data-height="100" class="ts-image-picker ts_vcsc_extend_settings_defaultLightboxNoise" value="<?php echo $Lightbox_NoisePattern; ?>">
					<?php
						foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Rasters_List as $key => $option) {
							$selected = selected(($Lightbox_NoisePattern == TS_VCSC_GetResourceURL($option)) , true, false);
							if ($key != '') {
								echo '<option data-img-src="' . TS_VCSC_GetResourceURL($option) . '" value="' . TS_VCSC_GetResourceURL($option) . '" ' . $selected . '>' . $key . '</option>';
							} else {
								echo '<option data-img-src="" value="" ' . $selected . '>transparent</option>';
							}
						}
					?>
				</select>
			</div>			
			<div style="margin-top: 20px;">
				<h4>Lightbox Button Scheme:</h4>
				<p style="font-size: 12px;">Select the button scheme (color) that should be used with the lightbox:</p>
				<select id="ts_vcsc_extend_settings_defaultLightboxScheme" name="ts_vcsc_extend_settings_defaultLightboxScheme" data-background="true" data-width="286" data-height="60" class="ts-image-picker ts_vcsc_extend_settings_defaultLightboxScheme" value="<?php echo $Lightbox_ButtonScheme; ?>">
					<?php
						$selected = selected(($Lightbox_ButtonScheme == 'dark') , true, false);
						echo '<option data-img-src="' . TS_VCSC_GetResourceURL("images/other/lightbox_dark.jpg") . '" value="dark" ' . $selected . '>Dark</option>';
						$selected = selected(($Lightbox_ButtonScheme == 'light') , true, false);
						echo '<option data-img-src="' . TS_VCSC_GetResourceURL("images/other/lightbox_light.jpg") . '" value="light" ' . $selected . '>Light</option>';
					?>
				</select>
			</div>
			<h2>Backlight (Glowlight) Settings</h2>
			<div class="ts-vcsc-notice-field ts-vcsc-success" style="margin-top: 10px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
				The lightbox is rendering a so-called backlight (glowlight) effect at the bottom of the screen. The color of that light effect is based on the most staturated color in the image currently shown inside the lightbox. The most saturated color is not the same as the most used color in the image, as it is the color intensity (colorfulness of a color relative to its own brightness) that defines the backlight (glowlight).
			</div>
			<div style="margin-top: 20px; margin-bottom: 10px;">
				<h4>Remove Backlight Effect:</h4>
				<p style="font-size: 12px;">Define if the lightbox should remove the backlight effect for all elements (will overwrite individual element settings; recommended when using external servers without full CORS support):</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_defaultLightboxBacklight",
						"label"				=> "Remove Backlight Effect",
						"value"             => $Lightbox_RemoveLight,
						"order"				=> 7,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $Lightbox_RemoveLight);
				?>
			</div>
			<div id="ts_vcsc_extend_settings_defaultLightboxBacklight_false" style="margin-top: 10px; margin-bottom: 10px; margin-left: 25px; <?php echo ($Lightbox_RemoveLight == 1 ? 'display: none;' : 'display: block;'); ?>">
				<h4>Use Global Backlight Color:</h4>
				<p style="font-size: 12px;">Define if the lightbox should use a global backlight color, overriding all individual settings:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_defaultLightboxBackCustom",
						"label"				=> "Use Global Backlight",
						"value"             => $Lightbox_CustomLight,
						"order"				=> 7,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $Lightbox_CustomLight);
				?>
				<div id="ts_vcsc_extend_settings_defaultLightboxBackCustom_true" style="margin-top: 20px; margin-bottom: 10px; margin-left: 25px; <?php echo ($Lightbox_CustomLight == 0 ? 'display: none;' : 'display: block;'); ?>">
					<h4>Lightbox Backlight Color:</h4>
					<p style="font-size: 12px;">Define a global color to be used for lightbox backlight effect:</p>
					<div class="ts-color-group">
						<input id="ts_vcsc_extend_settings_defaultLightboxBackColor" name="ts_vcsc_extend_settings_defaultLightboxBackColor" data-error="Lightbox - Backlight Color" data-order="9" class="validate[required,funcCall[checkColorPickerSyntax]] ts_vcsc_extend_settings_defaultLightboxBackColor ts-color-control" data-alpha="false" type="text" value="<?php echo $Lightbox_BackColor; ?>"/>
					</div>
				</div>
				<div id="ts_vcsc_extend_settings_defaultLightboxBackCustom_false" style="margin-top: 20px; margin-bottom: 10px; margin-left: 0px; <?php echo ($Lightbox_CustomLight == 0 ? 'display: block;' : 'display: none;'); ?>">
					<h4>Use URL Color Scan:</h4>
					<p style="font-size: 12px;">Define if the lightbox should be able to scan image URL's for valid HEX color information to be used as backlight for that image instead:</p>
					<?php
						$settings = array(
							"param_name"        => "ts_vcsc_extend_settings_defaultLightboxURLScan",
							"label"				=> "Use URL Color Scan",
							"value"             => $Lightbox_URLScan,
							"order"				=> 7,
						);
						echo TS_VCSC_CodeStarButton_Settings_Field($settings, $Lightbox_URLScan);
					?>
				</div>
				<div style="margin-top: 20px;">
					<h4>Allow CORS Requests:</h4>
					<p style="font-size: 12px;">Define if the lightbox should attempt to use CORS requests to analyze image data for color information; enable only if images are retrieved cross-domain from a CORS enabled server, as it will increase image loading times:</p>
					<?php
						$settings = array(
							"param_name"        => "ts_vcsc_extend_settings_defaultLightboxCors",
							"label"				=> "Enable CORS Requests",
							"value"             => $Lightbox_AllowCORS,
							"order"				=> 7,
						);
						echo TS_VCSC_CodeStarButton_Settings_Field($settings, $Lightbox_AllowCORS);
					?>
				</div>
			</div>			
			<h2 style="margin-top: 30px;">General Settings</h2>
			<div style="margin-top: 20px;">
				<h4>Animation Speed:</h4>
				<p style="font-size: 12px;">Define the speed in ms that should be used for all animation (transition) effects:</p>				
				<div class="ts-nouislider-input-slider clearFixMe" style="margin-top: 20px;">
					<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="ts_vcsc_extend_settings_defaultLightboxSpeedFX" id="ts_vcsc_extend_settings_defaultLightboxSpeedFX" class="ts_vcsc_extend_settings_defaultLightboxSpeedFX ts-nouislider-serial nouislider-input-selector nouislider-input-composer" type="text" value="<?php echo $Lightbox_SpeedFX; ?>"/>
					<span style="float: left; margin-right: 30px; margin-top: 10px;" class="unit">ms</span>
					<div id="ts_vcsc_extend_settings_defaultLightboxSpeedFX_slider" class="ts-nouislider-input ts-nouislider-settings-element" data-value="<?php echo $Lightbox_SpeedFX; ?>" data-min="250" data-max="4000" data-decimals="0" data-step="50" style="width: 250px; float: left; margin-top: 10px;"></div>
				</div>
			</div>
            <div style="margin-top: 20px;">
                <h4>Default Transition Animation</h4>
                <p>Please define which animation should be used as default (pre-selected) animation in corresponding element setting panels:</p>
				<label for="ts_vcsc_extend_settings_defaultLightboxAnimation" class="ts_vcsc_extend_settings_defaultLightbox">Default Transition Animation:</label>
				<select id="ts_vcsc_extend_settings_defaultLightboxAnimation" name="ts_vcsc_extend_settings_defaultLightboxAnimation" style="width: 250px; margin-left: 20px;">
					<?php
						foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Animations as $Setting => $key) {
							echo '<option value="' . $key . '" ' . selected($key, $Lightbox_Animation) . '>' . $Setting . '</option>';
						}
					?>
				</select>
            </div>
            <div style="margin-top: 20px;">
                <h4>Page Scroll Setting</h4>
                <p>Please define if and how the lightbox should prevent (background) page scrolling when the lightbox is open:</p>
				<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
					This setting will control whether the user can still scroll the page behind the opened lightbox. Preventing page scroll via JS is the option with the most success, while preventing page scroll via CSS rules only can fail on some devices, particularly on devices with touchscreens and/or touchpads, but is the least intrusive method.
				</div>
				<label for="ts_vcsc_extend_settings_defaultLightboxScrollBlock" class="ts_vcsc_extend_settings_defaultLightbox">Page Scroll Setting:</label>
				<select id="ts_vcsc_extend_settings_defaultLightboxScrollBlock" name="ts_vcsc_extend_settings_defaultLightboxScrollBlock" style="width: 250px; margin-left: 20px;">
					<option value="js" <?php echo selected('js', $Lightbox_ScrollBlock); ?>>Prevent Page Scroll via JS</option>
					<option value="css" <?php echo selected('css', $Lightbox_ScrollBlock); ?>>Prevent Page Scroll via CSS Only</option>					
					<option value="none" <?php echo selected('none', $Lightbox_ScrollBlock); ?>>Allow (Background) Page Scroll</option>
				</select>
            </div>			
            <div style="margin-top: 20px;">
                <h4>Image Download Protection</h4>
                <p>Please define if and how images should be protected from downloading via right mouse click or drag operation:</p>
				<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
					This setting will only apply on pages/posts where the lightbox script is actually loaded, meaning where elements are embedded that actually utilize the lightbox. For a sitewide protection, you should set the lightbox to be loaded at all times, using the provided setting option in the tab "External Files".
				</div>
				<label for="ts_vcsc_extend_settings_defaultLightboxProtection" class="ts_vcsc_extend_settings_defaultLightbox">Image Protection:</label>
				<select id="ts_vcsc_extend_settings_defaultLightboxProtection" name="ts_vcsc_extend_settings_defaultLightboxProtection" style="width: 250px; margin-left: 20px;">
					<option value="none" <?php echo selected('none', $Lightbox_Protection); ?>>No Protection</option>
					<option value="lightbox" <?php echo selected('lightbox', $Lightbox_Protection); ?>>Images in Lightbox Only</option>
					<option value="global" <?php echo selected('global', $Lightbox_Protection); ?>>All Images on Page</option>
				</select>
            </div>			
			<div style="margin-top: 20px;">
				<h4>Image Download Button:</h4>
				<p style="font-size: 12px;">Define if the lightbox should provide a download button for each image, in order to save the image directly onto the users computer:</p>
				<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
					This setting will, if enabled, provide a direct download button for each image, even if a general image protection setting has been enabled, using the option provided right above. However, the download button will NOT directly reveal the path to the image back to the server it is hosted on.
				</div>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_defaultLightboxSave",
						"label"				=> "Enable Image Download Button",
						"value"             => $Lightbox_AllowSave,
						"order"				=> 7,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $Lightbox_AllowSave);
				?>
			</div>
			<div style="margin-top: 20px;">
				<h4>Zoom Feature:</h4>
				<p style="font-size: 12px;">Define if the lightbox should provide a zoom option for over-sized images:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_defaultLightboxZoom",
						"label"				=> "Enable Zoom Button",
						"value"             => $Lightbox_AllowZoom,
						"order"				=> 7,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $Lightbox_AllowZoom);
				?>
			</div>	
			<div style="margin-top: 20px;">
				<h4>Full Screen Feature:</h4>
				<p style="font-size: 12px;">Define if the lightbox should provide a full screen option:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_defaultLightboxFullScreen",
						"label"				=> "Enable Full Screen Button",
						"value"             => $Lightbox_AllowFullscreen,
						"order"				=> 7,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $Lightbox_AllowFullscreen);
				?>
			</div>	
			<div style="margin-top: 20px;">
				<h4>Close Button inside Lightbox:</h4>
				<p style="font-size: 12px;">Define if the lightbox should provide another close button inside the Lightbox element:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_defaultLightboxCloser",
						"label"				=> "Enable 2nd Close Button inside Lightbox",
						"value"             => $Lightbox_CloseButton,
						"order"				=> 7,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $Lightbox_CloseButton);
				?>
			</div>
			<div style="margin-top: 20px;">
				<h4>Background Close Feature:</h4>
				<p style="font-size: 12px;">Define if the lightbox can be closed by clicking on the lightbox background:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_defaultLightboxBGClose",
						"label"				=> "Enable Background Close",
						"value"             => $Lightbox_BackgroundClose,
						"order"				=> 7,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $Lightbox_BackgroundClose);
				?>
			</div>			
			<div style="margin-top: 20px;">
				<h4>Browser Back Button Close Feature:</h4>
				<p style="font-size: 12px;">Define if the lightbox can be closed by using the browser back button:</p>
				<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
					This setting will override the default browser back button behavior while the lightbox is opened and will only work if the browser supports the HTML5 "window.history.pushState" routine. If enabled, using the browser back button while the lightbox is opened will only close the lightbox, instead of going back to the last visited page. <span style="font-weight: bold;">This feature will only work if the lightbox hashtag navigation feature remains deactivated!</span>
				</div>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_defaultLightboxHistoryClose",
						"label"				=> "Enable Browser Back Button Close",
						"value"             => $Lightbox_HistoryClose,
						"order"				=> 7,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $Lightbox_HistoryClose);
				?>
			</div>
			<h2 style="margin-top: 30px;">Navigation Settings</h2>
			<div style="margin-top: 20px;">
				<h4>Touch & Swipe Navigation:</h4>
				<p style="font-size: 12px;">Define if the lightbox can be navigated via touch and swipe gestures (on supported devices):</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_defaultLightboxNoTouch",
						"label"				=> "Enable Touch & Swipe Navigation",
						"value"             => $Lightbox_AllowTouchSwipe,
						"order"				=> 7,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $Lightbox_AllowTouchSwipe);
				?>
			</div>	
			<div style="margin-top: 20px;">
				<h4>Keyboard Navigation:</h4>
				<p style="font-size: 12px;">Define if the lightbox can be operated via keyboard navigation (on supported devices):</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_defaultLightboxKeyboard",
						"label"				=> "Enable Keyboard Navigation",
						"value"             => $Lightbox_AllowKeyboard,
						"order"				=> 7,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $Lightbox_AllowKeyboard);
				?>
			</div>
			<div style="margin-top: 20px;">
				<h4>Tap-To-Next Navigation:</h4>
				<p style="font-size: 12px;">Define if the lightbox can be operated via taps (clicks) on the image in order to navigate to the next image (on supported devices):</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_defaultLightboxTapping",
						"label"				=> "Enable Tap-To-Next Navigation",
						"value"             => $Lightbox_TapToNext,
						"order"				=> 7,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $Lightbox_TapToNext);
				?>
			</div>
			<div style="margin-top: 20px;">
				<h4>Remove Hashtag Navigation:</h4>
				<p style="font-size: 12px;">Define if the lightbox should remove hashtags from media elements (otherwise added for navigation purposes and deeplinking):</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_defaultLightboxHashtag",
						"label"				=> "Remove Hashtag Navigation",
						"value"             => $Lightbox_RemoveHashtag,
						"order"				=> 7,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $Lightbox_RemoveHashtag);
				?>
			</div>
			<div style="margin-top: 20px;">
				<h4>Thumbnails Navigation:</h4>
				<p style="font-size: 12px;">Define if the lightbox should use a 3rd party solution (PerfectScrollbar) for the scroll navigation of thumbnails:</p>
				<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
					If enabled, this setting will require the lightbox to load an additional JS and CSS file each for the required 3rd party script (PerfectScrollbar) in order to actually apply the advanced thumbnail navigation. The additional files will be loaded automatically wherever the lightbox is utilized.
				</div>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_defaultLightboxCustomScroll",
						"label"				=> "Enable PerfectScrollbar Navigation",
						"value"             => $Lightbox_CustomScroll,
						"order"				=> 7,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $Lightbox_CustomScroll);
				?>
			</div>
			<h2 style="margin-top: 30px;">Social Share Settings</h2>
			<div style="margin-top: 20px;">
				<h4>Social Share Feature:</h4>
				<p style="font-size: 12px;">Define if the lightbox should allow for a social share feature (<strong>can be overwritten by some elements</strong>):</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_defaultLightboxShare",
						"label"				=> "Enable Global Social Share",
						"value"             => $Lightbox_AllowShare,
						"order"				=> 7,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $Lightbox_AllowShare);
				?>
			</div>			
			<div style="margin-top: 20px; margin-left: 25px;">
				<h4>Social Network API's:</h4>
				<p style="font-size: 12px;">Define if the lightbox should load the respective API's for the social networks identified below (<strong>disable only if already loaded otherwise</strong>):</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_defaultLightboxLoadAPIs",
						"label"				=> "Load Social Network API's",
						"value"             => $Lightbox_AllowLoadAPIs,
						"order"				=> 7,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $Lightbox_AllowLoadAPIs);
				?>
			</div>			
			<div style="margin-top: 20px; margin-left: 25px;">
				<h4>Social Networks:</h4>
				<p style="font-size: 12px;">Define the social networks and their order, using "fb" for Facebook, "tw" for Twitter, "gp" for Google+ and "pin" for Pinterest; separate by comma (i.e. "fb,tw,gp,pin"):</p>
				<label class="Uniform" style="display: inline-block; margin-left: 0;" for="ts_vcsc_extend_settings_defaultLightboxNetworks">Social Networks (fb,tw,gp,pin):</label>
				<input class="validate[required]" data-error="Lightbox - Social Networks" data-order="9" type="text" style="width: 20%;" id="ts_vcsc_extend_settings_defaultLightboxNetworks" name="ts_vcsc_extend_settings_defaultLightboxNetworks" value="<?php echo $Lightbox_SocialNetworks; ?>" size="100">
			</div>	
		</div>
	</div>
</div>