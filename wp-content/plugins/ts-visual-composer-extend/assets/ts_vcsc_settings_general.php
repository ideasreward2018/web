<?php
	global $VISUAL_COMPOSER_EXTENSIONS;

	// Standard Elements
	$Count_Media								= 0;
	$Count_Google								= 0;
	$Count_Buttons								= 0;
	$Count_Counters								= 0;
	$Count_Posts								= 0;
	$Count_Titles								= 0;
	$Count_Popups								= 0;
	$Count_Timelines							= 0;
	$Count_Other								= 0;
	$Count_Beta									= 0;
	$Count_Types								= 0;
	$Count_Extra								= 0;
	$Count_Main 								= 0;
	$Count_Total								= 0;
	
	// Post Type Elements
	$Post_Timeline 								= 0;
	$Post_Team 									= 0;
	$Post_Testimonial 							= 0;
	$Post_Skillsets 							= 0;
	$Post_Logo 									= 0;
	$Post_Widget								= 0;
	
	// Extra Elements
	$Extra_Demos								= 0;
	$Extra_Enlighter 							= 0;
	$Extra_Navigator 							= 0;	
	
	// Count Main + Parent Elements
	foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Elements as $ElementName => $element) {
		if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'Media')) {
			$Count_Media++;
		} else if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'Google')) {
			$Count_Google++;
		} else if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'Buttons')) {
			$Count_Buttons++;
		} else if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'Counters')) {
			$Count_Counters++;
		} else if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'Posts')) {
			$Count_Posts++;
		} else if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'Titles')) {
			$Count_Titles++;
		} else if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'Popups')) {
			$Count_Popups++;
		} else if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'Timelines')) {
			$Count_Timelines++;
		} else if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'Other')) {
			$Count_Other++;
		} else if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'BETA')) {
			$Count_Beta++;
		}
	}
	
	// Count Child Elements
	foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Children as $ElementName => $element) {
		if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'Media')) {
			$Count_Media++;
		} else if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'Google')) {
			$Count_Google++;
		} else if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'Buttons')) {
			$Count_Buttons++;
		} else if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'Counters')) {
			$Count_Counters++;
		} else if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'Posts')) {
			$Count_Posts++;
		} else if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'Titles')) {
			$Count_Titles++;
		} else if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'Popups')) {
			$Count_Popups++;
		} else if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'Timelines')) {
			$Count_Timelines++;
		} else if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'Other')) {
			$Count_Other++;
		} else if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'BETA')) {
			$Count_Beta++;
		}
	}
	
	// Count Post Type Elements
	foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Types as $ElementName => $element) {
		if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'Post Types')) {
			$Count_Types++;
			if ($element['posttype'] == 'ts_skillsets') {
				$Post_Skillsets++;
			} else if ($element['posttype'] == 'ts_team') {
				$Post_Team++;
			} else if ($element['posttype'] == 'ts_testimonials') {
				$Post_Testimonial++;
			} else if ($element['posttype'] == 'ts_timeline') {
				$Post_Timeline++;
			} else if ($element['posttype'] == 'ts_logos') {
				$Post_Logo++;
			}
		}
	}
	
	// Count Widget Elements
	$Post_Widget++;
	
	// Count Extra Elements
	foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Extra as $ElementName => $element) {
		if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['group'] == 'Extra')) {
			$Count_Extra++;
			if ($element['feature'] == 'Enlighter') {
				$Extra_Enlighter++;
			} else if ($element['feature'] == 'Navigator') {
				$Extra_Navigator++;
			}
		}
	}
	
	// Count Demo Elements
	foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Demos as $ElementName => $element) {
		if (($element['deprecated'] == 'false') && ($element['type'] != 'external') && ($element['base'] != '') && ($element['group'] == 'Demos')) {
			$Count_Extra++;
			$Extra_Demos++;
		}
	}	
	
	// Count Main + Parent Elements
	$Count_Deprecated							= TS_VCSC_CountArrayMatches($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Elements, 	'deprecated', 		'true');
	$Count_External								= TS_VCSC_CountArrayMatches($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Elements, 	'type', 			'external');
	
	// Count Child Elements
	$Count_Deprecated							+= TS_VCSC_CountArrayMatches($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Children,	'deprecated', 		'true');
	$Count_External								+= TS_VCSC_CountArrayMatches($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Children,	'type', 			'external');
	
	// Total Counts
	$Count_Main									= $Count_Media + $Count_Google + $Count_Buttons + $Count_Counters + $Count_Posts + $Count_Titles + $Count_Popups + $Count_Timelines + $Count_Other + $Count_Beta;
	$Count_Total								= $Count_Media + $Count_Google + $Count_Buttons + $Count_Counters + $Count_Posts + $Count_Titles + $Count_Popups + $Count_Timelines + $Count_Other + $Count_Beta + $Extra_Enlighter + $Extra_Navigator;

	if (($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_CustomPostTypesTimeline == "true") && ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Elements["TS CSS Media Timeline"]["active"] == "true")) {
		$Count_Main								= $Count_Main - 1;
		$Count_Total							= $Count_Total - 1;
	}	
	
	$Count_Fonts								= sizeof($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Fonts_Google);

	$memory_recommended							= 20 * 1024 * 1024;
	$memory_required							= 10 * 1024 * 1024;
	$memory_allocated							= ini_get('memory_limit');
	$memory_allocated 							= preg_replace("/[^0-9]/", "", $memory_allocated) * 1024 * 1024;
	$memory_peakusage 							= memory_get_peak_usage(true);
	$memory_remaining							= $memory_allocated - $memory_peakusage;
	$memory_utilization							= $memory_peakusage / $memory_allocated * 100;
	$memory_checkup								= (($memory_remaining < $memory_recommended) ? "false" : "true");
	$memory_minimum								= (($memory_remaining < $memory_required) ? "false" : "true");
	
	// Advanced Link Selector
    $ts_vcsc_extend_settings_linkerEnabled		= ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterLinkPicker['enabled'] == "true" ? 1 : 0);
	$ts_vcsc_extend_settings_linkerGlobal		= ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterLinkPicker['global'] == "true" ? 1 : 0);
	$ts_vcsc_extend_settings_linkerOffset		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterLinkPicker['offset'];
	$ts_vcsc_extend_settings_linkerPosts		= ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterLinkPicker['posts'] == "true" ? 1 : 0);
	$ts_vcsc_extend_settings_linkerCustom		= ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterLinkPicker['custom'] == "true" ? 1 : 0);
	$ts_vcsc_extend_settings_linkerOrderby		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterLinkPicker['orderby'];
	$ts_vcsc_extend_settings_linkerOrder		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterLinkPicker['order'];
	
	// Numeric Slider Inputs (NoUiSlider)
	$ts_vcsc_extend_settings_nouisliderEnabled	= ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterNoUiSlider['enabled'] == "true" ? 1 : 0);
	$ts_vcsc_extend_settings_nouisliderPips		= ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterNoUiSlider['pips'] == "true" ? 1 : 0);
	$ts_vcsc_extend_settings_nouisliderTooltip	= ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterNoUiSlider['tooltip'] == "true" ? 1 : 0);
	$ts_vcsc_extend_settings_nouisliderInput	= ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterNoUiSlider['input'] == "true" ? 1 : 0);
	
	if (($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginValid == "true") && ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginExtended == "false") && ((strpos($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginEnvato, $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginLicense) != FALSE))) {
		$autoupdate_allowed						= "true";
	} else {
		$autoupdate_allowed						= "false";
	}
	
	if (TS_VCSC_VersionCompare($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Version, '4.5.0') >= 0) {
		$visual_composer_link					= 'admin.php?page=vc-general';
		if (TS_VCSC_VersionCompare($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Version, '4.8.0') >= 0) {
			$visual_composer_roles				= 'admin.php?page=vc-roles';
		} else {
			$visual_composer_roles				= 'admin.php?page=vc-general';
		}
	} else {
		$visual_composer_link					= 'options-general.php?page=vc_settings';
		$visual_composer_roles					= 'options-general.php?page=vc_settings';
	}
?>
<div id="ts-settings-general" class="tab-content">
	<div class="ts-vcsc-section-main">
		<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-info"></i>General Information</div>
		<div class="ts-vcsc-section-content">
			<div class="ts-vcsc-notice-field ts-vcsc-success" style="margin-top: 10px; font-size: 13px; text-align: justify;">
				In order to use this plugin, you MUST have the WP Bakery Page Builder plugin installed; either as a normal plugin or as part of your theme. If WP Bakery Page Builder is part of your theme, please ensure that it has not been modified; some theme developers heavily modify WP Bakery Page Builder in order to allow for certain theme functions. Unfortunately, some of these modification prevent this extension pack from working correctly.
			</div>
			<div style="margin-top: 20px; margin-bottom: 10px;">
				<h3>Composium - WP Bakery Page Builder Extensions</h3>
				<div style="margin-top: 20px;">
					<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder">
						<span class="ts-advanced-link-tooltip-content"><?php echo __("Click here to go to the official manual for the plugin.", "ts_visual_composer_extend"); ?></span>
						<a href="http://www.composium.krautcoding.com/documentation/" target="_blank" class="ts-advanced-link-button-main ts-advanced-link-button-orange ts-advanced-link-button-manual" style="margin: 0 20px 0 0;">
							<?php echo __("Plugin Manual", "ts_visual_composer_extend"); ?>
						</a>
					</div>
					<?php
						if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginExtended == "false") {
							echo '<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder">
								<span class="ts-advanced-link-tooltip-content">' . __("Click here to go to the official support forum for the plugin.", "ts_visual_composer_extend") . '</span>
								<a href="http://helpdesk.krautcoding.com/forums/forum/wordpress-plugins/visual-composer-extensions/" target="_blank" class="ts-advanced-link-button-main ts-advanced-link-button-red ts-advanced-link-button-support">'
									. __("Support Forum", "ts_visual_composer_extend") .
								'</a>
							</div>';
						}
					?>
					<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder">
						<span class="ts-advanced-link-tooltip-content"><?php echo __("Click here to go to the public knowledge base for the plugin.", "ts_visual_composer_extend"); ?></span>
						<a href="http://helpdesk.krautcoding.com/category/visual-composer-extensions/" target="_parent" class="ts-advanced-link-button-main ts-advanced-link-button-green ts-advanced-link-button-knowledge">
							<?php echo __("Knowledge Base", "ts_visual_composer_extend"); ?>
						</a>
					</div>
					<?php
						if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginExtended == "false") {
							echo '<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder ts-advanced-link-tooltip-right">
								<span class="ts-advanced-link-tooltip-content">' . __("Click here to go to the page used to confirm your license in order to unlock the auto-update feature.", "ts_visual_composer_extend") . '</span>
								<a href="admin.php?page=TS_VCSC_License" target="_parent" class="ts-advanced-link-button-main ts-advanced-link-button-turquoise ts-advanced-link-button-key" style="margin-right: 0px;">'
									. __("License Key", "ts_visual_composer_extend") .
								'</a>
							</div>';
						}				
						echo '<div style="border: 1px solid #ededed; margin: 20px 0 0 0; padding: 10px 10px 0 10px; background: #FAFAFA;">';
							echo '<p>Current Version: ' . COMPOSIUM_VERSION . '</p>';
							echo '<p style="font-size: 90%; font-style: italic;">WP Bakery Page Builder Version: '. $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Version . '</p>';
							if (function_exists('is_multisite') && is_multisite()) {
								echo '<p>Multisite Environment: Yes</p>';
								echo '<p>Plugin Activated Network Wide: ' . ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginIsMultiSiteActive == "true" ? "Yes" : "No") . '</p>';
							} else {
								echo '<p>Multisite Environment: No</p>';
							}
							echo '<p>Available Elements: ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_CountTotalElements . ' / <span style="font-weight: bold; color: #0078CE;">Active Elements: ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_CountActiveElements . '</span></p>';
							echo '<p style="font-size: 10px;">Additional Deprecated Elements: ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_CountDeprecatedElements . '</p>';
							if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorIconFontsInternal == "true") {
								$TS_VCSC_TotalIconFontsInstalled = (count($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Installed_Icon_Fonts) + count($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Composer_Icon_Fonts));
							} else {
								$TS_VCSC_TotalIconFontsInstalled = count($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Installed_Icon_Fonts);
							}
							if (get_option('ts_vcsc_extend_settings_tinymceCustomArray', '') != '') {
								echo '<p>Available Fonts: ' . $TS_VCSC_TotalIconFontsInstalled . ' / <span style="font-weight: bold; color: #0078CE;">Active Fonts: ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Active_Icon_Fonts . '</span></p>';
							} else {
								echo '<p>Available Fonts: ' . ($TS_VCSC_TotalIconFontsInstalled - 1) . ' / <span style="font-weight: bold; color: #0078CE;">Active Fonts: ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Active_Icon_Fonts . '</span></p>';
							}
							echo '<p>Available Icons: ' . number_format($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Total_Icon_Count) . ' / <span style="font-weight: bold; color: #0078CE;">Active Icons: ' . number_format($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Active_Icon_Count) . '</span></p>';
							if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginExtended == "true") {
								echo '<p style="text-align: justify;">Need more help? Please contact the developer of your theme as it includes this plugin via extended license.<br/><br/>';
							}
						echo '</div>';
					?>
				</div>
			</div>
			<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 15px; font-size: 13px; text-align: justify;">
				This plugin includes a multitude of setting options, controlling various aspects of the plugin scope. In order to make it easier to navigate those options, the plugin will by default only provide access to the most common and important options. If you want access to extended options, use the switch below to toggle between simple and extended options.
			</div>
			<div style="margin-top: 20px; margin-bottom: 20px">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Use Extended Plugin Options Mode:</div>
				<p style="font-size: 12px;">Define whether you want to use the simple options mode with limited access, or the extended options mode with access to all available plugin options:</p>	
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_allowFullOptions",
						"label"				=> "Use Extended Options Mode",
						"value"             => $ts_vcsc_extend_settings_allowFullOptions,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_allowFullOptions);
				?>				
			</div>	
		</div>		
	</div>
	<div class="ts-vcsc-section-main">
		<div class="ts-vcsc-section-title ts-vcsc-section-hide"><i class="dashicons-desktop"></i>Quick System Check</div>
		<div class="ts-vcsc-section-content slideFade" style="display: none;">
			<?php
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginPHP == "false") {
					echo '<div class="ts-vcsc-info-field ts-vcsc-critical" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; font-weight: normal; text-align: justify;">';
						echo 'Your server is currently running the outdated PHP version <span style="font-weight: bold;">' . PHP_VERSION . '</span>, which is not sufficient for some of the advanced features and custom post types this
						plugin provides for. In order to use all features, please change your server settings to use at least PHP v5.4.x. WordPress itself recommends using PHP v5.6.0 or higher, as all older PHP versions have been
						officially retired and are unsupported.';				
						if (array_key_exists(substr(PHP_VERSION, 0, 3), $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PHP_End_Of_Life)) {
							echo '<br/><br/><span style="font-weight: bold;">Your current PHP version has officially been retired and deprecated on ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PHP_End_Of_Life[substr(PHP_VERSION, 0, 3)] . '.</span>';
						}
					echo '</div>';
				}
			?>
			<div class="ts-vcsc-info-field ts-vcsc-warning">The provided summary is using information returned by your server based on php.ini settings. Depending upon your hosting company and hosting package, your server might
			actually provide less memory than requested and shown in the php.ini; please contact your hosting company for more detailed and accurate information.</div>
			<p style="margin: 10px 0 0 0;">Allocated Memory: <?php echo number_format(($memory_allocated / 1024 / 1024), 0); ?>MB</p>
			<p style="margin: 0;">Already Utilized Memory: <?php echo number_format(($memory_peakusage / 1024 / 1024), 0); ?>MB</p>
			<p style="margin: 0;">Remaining Memory: <?php echo number_format(($memory_remaining / 1024 / 1024), 0); ?>MB</p>
			<p style="margin: 0;">Utilization Rate: <?php echo number_format($memory_utilization, 2); ?>%</p>			
			<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder" style="margin-top: 10px; margin-bottom: 10px;">
				<span class="ts-advanced-link-tooltip-content"><?php echo __("Click here to see a full summary of your current system setup.", "ts_visual_composer_extend"); ?></span>
				<a href="admin.php?page=TS_VCSC_System" class="ts-advanced-link-button-main ts-advanced-link-button-turquoise ts-advanced-link-button-system">
					<?php _e("Full System Info", "ts_visual_composer_extend"); ?>
				</a>
			</div>			
			<?php
				if ($memory_checkup == "true") {
					echo '<div class="ts-vcsc-info-field ts-vcsc-success" style="margin-top: 10px; font-size: 13px; text-align: justify; font-weight: bold;">
						Your site seems to have sufficient PHP memory remaining to use WP Bakery Page Builder and this add-on without problems. Have in mind that activating additional elements or features of this
						add-on and/or adding new plugins will further increase your memory usage and naturally impact the overall performance of WP Bakery Page Builder.
					</div>';
				} else {
					echo '<div class="ts-vcsc-info-field ts-vcsc-' . ($memory_minimum == "true" ? "warning" : "critical") . '" style="margin-top: 10px; margin-bottom: 10px; font-size: 13px; text-align: justify; font-weight: bold;">
						Your site is ' . ($memory_minimum == "true" ? "" : "VERY") . ' close to memory exhaustion. You have only ' . (number_format(($memory_remaining / 1024 / 1024), 0)) . 'MB of memory remaining,
						when in idle mode, which might not be enough once you actually edit a page or post with WP Bakery Page Builder. In general, it is advised to have around ' . (number_format(($memory_recommended / 1024 / 1024), 0)) , 'MB of memory remaining, when idling. Depending upon your theme and other activated plugins, that number might actually be more or less.
					</div>';
				}
			?>
			<div class="ts-vcsc-info-field ts-vcsc-warning" style="margin-top: 20px; margin-bottom: 10px; font-size: 13px; text-align: justify; font-weight: normal;">
				The memory consumption shown above only reflects the current consumption on this particular settings page. Whenever you edit a page or post, the memory consumption will increase significantly, as WP Bakery Page Builder, this add-on, your theme and possibly other active plugins will load additional files that are not loaded outside of any edit pages. To live monitor your memory usage on all admin pages/sections, it is recommended to install this free plugin from the WordPress repository: <a href="https://wordpress.org/plugins/query-monitor/" target="_blank">Query Monitor</a>
			</div>	
		</div>
	</div>	
	<div class="ts-vcsc-section-main">
		<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-admin-generic"></i>General Plugin Settings</div>
		<div class="ts-vcsc-section-content">	
			<div style="margin-top: 20px; display: <?php echo ($autoupdate_allowed == "true" ? "block" : "none"); ?>;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Enable Auto-Update Feature:</div>
				<p style="font-size: 12px;">Define whether you want to use the auto-update feature of the plugin, allowing the plugin to be updated through WordPress:</p>
				<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					If the auto-update procedure fails, it is most likely because your internal WordPress post size and upload limits and or available PHP memory is not sufficient to handle the size of the update file (retrieval,
					extracting, replacing). In that case, you should update the plugin via manual FTP upload, replacing all existing files on your server.
				</div>	
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_allowAutoUpdate",
						"label"				=> "Enable Auto-Update Feature",
						"value"             => $ts_vcsc_extend_settings_allowAutoUpdate,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_allowAutoUpdate);
				?>
			</div>
			<div style="margin-top: <?php echo ($autoupdate_allowed == "true" ? "30" : "10"); ?>px;" class="<?php echo $TS_VCSC_SimpleOptionsClass; ?>">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Placement of "Composium - WP Bakery Page Builder Extensions" Menu:</div>
				<p style="font-size: 12px;">Define where the menu for this plugin should be placed in WordPress; if disabled, the main menu will be placed in the 'Settings' section:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_mainmenu",
						"label"				=> 'Give "Composium - WP Bakery Page Builder Extensions" its own menu',
						"value"             => $ts_vcsc_extend_settings_mainmenu,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_mainmenu);
				?>
			</div>		
			<div style="margin-top: 30px;" class="<?php echo $TS_VCSC_SimpleOptionsClass; ?>">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Use of Language Domain Translations:</div>
				<p style="font-size: 12px;">Define if the plugin can use its language domain files (stored in the 'locale' folder) in order to automatically be translated into available languages:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_translationsDomain",
						"label"				=> "Use Plugin Language Files",
						"value"             => $ts_vcsc_extend_settings_translationsDomain,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_translationsDomain);
				?>
			</div>			
			<div style="margin-top: 30px;" class="<?php echo $TS_VCSC_SimpleOptionsClass; ?>">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Show Dashboard Panel:</div>
				<p style="font-size: 12px;">Define if the plugin should show its dashboard panel with basic plugin information:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_dashboard",
						"label"				=> "Show Dashboard Panel",
						"value"             => $ts_vcsc_extend_settings_dashboard,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_dashboard);
				?>
			</div>			
			<div style="margin-top: 30px;" class="<?php echo $TS_VCSC_SimpleOptionsClass; ?>">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Always Load + Register Shortcodes:</div>
				<p style="font-size: 12px;">Define if the plugin should always load and register its associated shortcodes:</p>
				<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					By default, the plugin will only load and register its associated shortcode definitions when rendering a page on the frontend, but not while viewing the backend (admin) section of WordPress. If you are using
					other plugins that for some reason load and process page/post content from within their backend sections, it might be necessary to load and process shortcode definitions even on the backend part of your website.
					If so, simply enable the option below, and the plugin will load and process its shortcode definitions at all times. Please be aware that enabling this option will automatically increase the memory requirements of
					the plugin when using the WordPress admin section (increase will depend upon number of enabled elements).
				</div>				
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_shortcodesalways",
						"label"				=> "Always Load + Register Shortcodes",
						"value"             => $ts_vcsc_extend_settings_shortcodesalways,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_shortcodesalways);
				?>
			</div>			
			<div style="margin-top: 30px; margin-bottom: 40px;" class="<?php echo $TS_VCSC_SimpleOptionsClass; ?>">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Priority for Output of JS Variables:</div>
				<p style="font-size: 12px;">Define the priority WordPress should use when outputting plugin settings as JS variables:</p>
				<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					Some of the plugin settings control how certain JavaScript functions on the frontend work. In order to do so, those settings must be outputted on each page (using the HEAD section of the page), while at the same time ensuring that the variables have been generated BEFORE any respective JS function needs it. By default, the plugin will give the variable output a priority of 6, which is the right priority for most websites. But if you use a caching plugin for example, the order in which JS files are loaded might be changed, sometimes requiring a different priority for the variable output, which you can change using the option below.
				</div>	
				<div class="ts-nouislider-input-slider">
					<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="ts_vcsc_extend_settings_variablesPriority" id="ts_vcsc_extend_settings_variablesPriority" class="ts_vcsc_extend_settings_variablesPriority ts-nouislider-serial nouislider-input-selector nouislider-input-composer" type="number" min="1" max="999" step="1" value="<?php echo $ts_vcsc_extend_settings_variablesPriority; ?>"/>
					<span style="float: left; margin-right: 30px; margin-top: 10px;" class="unit"></span>
					<div id="ts_vcsc_extend_settings_variablesPriority_slider" class="ts-nouislider-input ts-nouislider-settings-element" data-value="<?php echo $ts_vcsc_extend_settings_variablesPriority; ?>" data-min="1" data-max="999" data-decimals="0" data-step="1" style="width: 250px; float: left; margin-top: 10px;"></div>
				</div>
			</div>
			<div style="width: 100%; height: 20px;"></div>
		</div>		
	</div>
	<div class="ts-vcsc-section-main" style="display: <?php echo ((version_compare(PHP_VERSION, '5.2.0') >= 0) ? "block" : "none"); ?>;">
		<div class="ts-vcsc-section-title ts-vcsc-section-hide"><i class="dashicons-googleplus"></i>Google Fonts Manager</div>
		<div class="ts-vcsc-section-content slideFade" style="display: none;">
			<div class="ts-vcsc-notice-field ts-vcsc-success" style="margin-top: 10px; font-size: 13px; text-align: justify;">
				Some elements allow you to assign a custom font to titles or content sections of the element. By default, the add-on will give you access to currently <?php echo $Count_Fonts; ?> different Google Fonts. If that is simply too much for you, the built-in Google Fonts Manager will allow you to define your custom set of Google Fonts by simply selecting the fonts you want to use, while leaving all other disabled. You can even assign fonts to a "favorite" list so that those fonts will always be listed first in the element settings.
			</div>
			<div style="margin-top: 10px; margin-bottom: 10px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Google Fonts Manager:</div>
				<p style="font-size: 12px;">Enable or disable the use of the Google Fonts Manager:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_allowGoogleManager",
						"label"				=> "Enable Google Fonts Manager",
						"value"             => $ts_vcsc_extend_settings_allowGoogleManager,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_allowGoogleManager);
				?>
			</div>
		</div>
	</div>	
	<div class="ts-vcsc-section-main" style="display: <?php echo ((version_compare(PHP_VERSION, '5.2.0') >= 0) ? "block" : "none"); ?>;">
		<div class="ts-vcsc-section-title ts-vcsc-section-hide"><i class="dashicons-editor-textcolor"></i>Custom Fonts Manager</div>
		<div class="ts-vcsc-section-content slideFade" style="display: none;">
			<div class="ts-vcsc-notice-field ts-vcsc-success" style="margin-top: 10px; font-size: 13px; text-align: justify;">
				Some elements allow you to assign a custom font to titles or content sections of the element. If your site is already using custom fonts provided by other plugins or your theme, or you have access to fonts stored remotely, you can use the "Custom Fonts Manager" to manually register those fonts with this plugin, so it can be used within elements utilizing a font picker option.
			</div>
			<div style="margin-top: 10px; margin-bottom: 10px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Custom Fonts Manager:</div>
				<p style="font-size: 12px;">Enable or disable the use of the Custom Fonts Manager:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_allowCustomManager",
						"label"				=> "Enable Custom Fonts Manager",
						"value"             => $ts_vcsc_extend_settings_allowCustomManager,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_allowCustomManager);
				?>
			</div>
		</div>
	</div>	
	<?php
	if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconicumStandard == "false") { ?>
		<div class="ts-vcsc-section-main">
			<div class="ts-vcsc-section-title ts-vcsc-section-hide"><i class="dashicons-awards"></i>Iconicum - Font Icon Generator</div>
			<div class="ts-vcsc-section-content slideFade" style="display: none;">
				<div class="ts-vcsc-notice-field ts-vcsc-success" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					"Composium - WP Bakery Page Builder Extensions" includes an additional standalone icon generator that allows you to use all the font icons that come with "Composium - WP Bakery Page Builder Extensions" outside of the elements that can utilize icons. By using the provided icon generator, you can easily generate icon shortcodes and use those shortcodes anywhere on your site where a standard tinyMCE editor field is provided to you.
				</div>					
				<div style="margin-top: 10px; margin-bottom: 20px;">
					<div style="font-weight: bold; font-size: 14px; margin: 0;">Provide Menu Shortcode Generator for Font Icons:</div>
					<p style="font-size: 12px;">Adds an icon shortcode generator to the settings menu:</p>
					<?php
						$settings = array(
							"param_name"        => "ts_vcsc_extend_settings_useMenuGenerator",
							"label"				=> "Enable Menu Font Icon Generator",
							"value"             => $ts_vcsc_extend_settings_useMenuGenerator,
							"order"				=> 1,
						);
						echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_useMenuGenerator);
					?>
				</div>					
				<div style="margin-top: 10px; margin-bottom: 10px;">
					<div style="font-weight: bold; font-size: 14px; margin: 0;">Provide tinyMCE Shortcode Generator for Font Icons:</div>
					<p style="font-size: 12px;">Adds a shortcode generator button to any standard tinyMCE editor menu to embed font icons directly into the text editor:</p>
					<?php
						$settings = array(
							"param_name"        => "ts_vcsc_extend_settings_useIconGenerator",
							"label"				=> "Enable tinyMCE Font Icon Generator",
							"value"             => $ts_vcsc_extend_settings_useIconGenerator,
							"order"				=> 1,
						);
						echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_useIconGenerator);
					?>
				</div>			
				<div id="ts_vcsc_extend_settings_useIconGenerator_true" style="margin-top: 20px; margin-bottom: 10px; margin-left: 25px; <?php echo ($ts_vcsc_extend_settings_useIconGenerator == 0 ? 'display: none;' : 'display: block;'); ?>">
					<h4>Placement of Shortcode Generator Button:</h4>
					<p style="font-size: 12px;">If the option is disabled, the button will be placed into the tinyMCE menu bar instead:</p>
					<?php
						$settings = array(
							"param_name"        => "ts_vcsc_extend_settings_useTinyMCEMedia",
							"label"				=> "Place Generator Button next to 'Add Media' Button",
							"value"             => $ts_vcsc_extend_settings_useTinyMCEMedia,
							"order"				=> 1,
						);
						echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_useTinyMCEMedia);
					?>
				</div>
			</div>
		</div>
	<?php } else { ?>
		<div class="ts-vcsc-section-main">
			<div class="ts-vcsc-section-title ts-vcsc-section-hide"><i class="dashicons-awards"></i>Iconicum - Font Icon Generator</div>
			<div class="ts-vcsc-section-content slideFade" style="display: none;">
				<div class="ts-vcsc-info-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					"Iconicum - WordPress Icon Fonts" is already installed and activated as standalone plugin. Therefore, the customized version that is included with "Composium - WP Bakery Page Builder Extensions" has been disabled in order to prevent conflicts.
				</div>				
			</div>
		</div>
	<?php } ?>
	<div class="ts-vcsc-section-main" style="display: <?php echo ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginPHP == "true" ? "block" : "none"); ?>;">
		<div class="ts-vcsc-section-title ts-vcsc-section-hide"><i class="dashicons-clock"></i>Website Downtime Manager (BETA)</div>
		<div class="ts-vcsc-section-content slideFade" style="display: none;">
			<div class="ts-vcsc-info-field ts-vcsc-warning" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
				The website downtime manager (BETA) allows you to create custom downtime (offline/maintenance) pages, using WP Bakery Page Builder, and to place your website into a downtime/maintenance mode, during which your custom page will be shown. Enabling the downtime manager will provide you with a new custom post type "VC Downpages" to create your downtime page, and a new menu entry in your "VC Extensions" menu to manage your scheduled downtime. While this feature has been thoroughly tested from a developers perspective and has been found to be working exactly as intended, we require some feedback from actual end users before officially moving it out of its BETA stage; after all, every site and server setup is a little different.
			</div>
			<div style="margin-top: 10px; margin-bottom: 10px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Enable Website Downtime Manager + Post Type:</div>
				<p style="font-size: 12px;">Enable the website downtime manager to easily and quickly put your website into downtime (maintenance) mode, while showing a custom page to your visitors:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_allowDowntimeManager",
						"label"				=> "Enable Website Downtime Manager + Post Type",
						"value"             => $ts_vcsc_extend_settings_allowDowntimeManager,
						"order"				=> 1,
						"class"				=> "ts-downtime-manager-switch",
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_allowDowntimeManager);
				?>
			</div>
		</div>
	</div>	
	<div class="ts-vcsc-section-main <?php echo $TS_VCSC_SimpleOptionsClass; ?>">
		<div class="ts-vcsc-section-title ts-vcsc-section-hide"><i class="dashicons-welcome-widgets-menus"></i>Widget Sidebars Manager</div>
		<div class="ts-vcsc-section-content slideFade" style="display: none;">
			<div class="ts-vcsc-info-field ts-vcsc-warning" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
				If the number of widget sidebars that is provided by your theme and any other plugins isn't sufficient for you, enable the "Widget Sidebars Manager" here, which will allow you to create up to 25 additional sidebars.
			</div>
			<div style="margin-top: 10px; margin-bottom: 10px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Enable Widget Sidebars Manager:</div>
				<p style="font-size: 12px;">Enable the widget sidebars manager to easily and quickly add more widget sidebars to your site:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_allowSidebarsManager",
						"label"				=> "Enable Widget Sidebars Manager",
						"value"             => $ts_vcsc_extend_settings_allowSidebarsManager,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_allowSidebarsManager);
				?>
			</div>
		</div>
	</div>
	<div class="ts-vcsc-section-main <?php echo $TS_VCSC_SimpleOptionsClass; ?>">
		<div class="ts-vcsc-section-title ts-vcsc-section-hide"><i class="dashicons-editor-code"></i>Shortcodes in Widgets (Sidebars)</div>
		<div class="ts-vcsc-section-content slideFade" style="display: none;">
			<div class="ts-vcsc-info-field ts-vcsc-warning" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
				By default, WordPress does not allow you to use shortcodes when using the text widget in any sidebar, although it is actually possible. The setting below will allow you to enable/disable that otherwise hidden feature. However, if you notice any additional paragraphs in widget output that comes from other plugins or your theme (which can make that output invalid, particularly when it is JS code), you should keep this feature deactivated.
				<strong>Please not that other plugins or your theme can overwrite that setting, depending upon when it is called upon.</strong>
			</div>
			<div style="margin-top: 10px; margin-bottom: 10px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Enable Shortcodes in Widgets:</div>
				<p style="font-size: 12px;">Allow the usage of shortcodes with the standard text widget in WordPress:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_allowShortcodesWidgets",
						"label"				=> "Enable Shortcodes in Widgets",
						"value"             => $ts_vcsc_extend_settings_allowShortcodesWidgets,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_allowShortcodesWidgets);
				?>
			</div>
		</div>
	</div>	
	<div class="ts-vcsc-section-main <?php echo $TS_VCSC_SimpleOptionsClass; ?>">
		<div class="ts-vcsc-section-title ts-vcsc-section-hide"><i class="dashicons-editor-paragraph"></i>WordPress Auto-Paragraph Routine</div>
		<div class="ts-vcsc-section-content slideFade" style="display: none;">
			<div class="ts-vcsc-info-field ts-vcsc-warning" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
				By default, WordPress will attempt to wrap any content that is not already wrapped with a valid HTML wrapper (DIV, P, SPAN, etc.) with a paragraph wrap (P), which can sometimes cause issues whenver content is not meant to be wrapped at all. In those cases, you can deactivate the auto-paragraph wrapper below. <strong>Please not that other plugins or your theme can overwrite that setting, depending upon when it is called upon.</strong>
			</div>
			<div style="margin-top: 10px; margin-bottom: 10px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Enable Auto-Paragraph Routine:</div>
				<p style="font-size: 12px;">Allow WordPress to process its auto-paragraph routines for content and excerpts:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_allowAutoParagraphs",
						"label"				=> "Enable Auto-Paragraph Routine",
						"value"             => $ts_vcsc_extend_settings_allowAutoParagraphs,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_allowAutoParagraphs);
				?>
			</div>
		</div>
	</div>	
	<div class="ts-vcsc-section-main" style="display: none !important;">
		<div class="ts-vcsc-section-title ts-vcsc-section-hide"><i class="dashicons-image-flip-vertical"></i>Page Smooth Scroll (BETA)</div>
		<div class="ts-vcsc-section-content slideFade" style="display: none;">
			<div class="ts-vcsc-info-field ts-vcsc-warning" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
				If your theme or another active plugin already provides a smooth scroll routine, do NOT activate this feature, as you will otherwise create conflicting scroll callbacks, which can severaly impact the scroll behavior of your pages.
			</div>
			<div style="margin-top: 10px; margin-bottom: 10px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Smooth Scroll for Pages:</div>
				<p style="font-size: 12px;">Extend all pages with Smooth Scroll Feature (will not be applied on mobile devices); do not use if your theme or another plugin is already implementing a smooth scroll feature:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_additionsSmoothScroll",
						"label"				=> "Extend Pages with Smooth Scroll",
						"value"             => $ts_vcsc_extend_settings_additionsSmoothScroll,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_additionsSmoothScroll);
				?>
			</div>
		</div>
	</div>	
	<div class="ts-vcsc-section-main" style="display: none !important;">
		<div class="ts-vcsc-section-title ts-vcsc-section-hide">Other Settings</div>
		<div class="ts-vcsc-section-content slideFade" style="display: none;">
			<div style="margin-top: 10px; margin-bottom: 10px;">
				<h4>Viewing Device Detection:</h4>
				<p style="font-size: 12px;">Enable or disable the use of the Device Detection:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_loadDetector",
						"label"				=> "Use Device Type Detection",
						"value"             => $ts_vcsc_extend_settings_loadDetector,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_loadDetector);
				?>
			</div>
		</div>
	</div>
</div>