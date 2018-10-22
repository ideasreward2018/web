<?php
	global $VISUAL_COMPOSER_EXTENSIONS;
?>
<div id="ts-settings-files" class="tab-content">
	<div class="ts-vcsc-section-main">
		<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-download"></i>External Files Settings</div>
		<div class="ts-vcsc-section-content">
			<div class="ts-vcsc-notice-field ts-vcsc-critical" style="margin-top: 10px; font-size: 13px; text-align: justify; font-weight: bold;">
				Changes to the default settings done in this section can severely impact the overall functionality of this add-on or WordPress itself. Only make changes if you really know what you are doing and if the add-on is not working correctly with the default settings!
			</div>	
			<div>
				<h4>Force Load of jQuery:</h4>
				<p style="font-size: 12px; text-align: justify;">Please define if you want to force a load of jQuery and jQuery Migrate:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_loadjQuery",
						"label"				=> "Force Load of jQuery",
						"value"             => $ts_vcsc_extend_settings_loadjQuery,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_loadjQuery);
				?>
			</div>			
			<hr class='style-six' style='margin-top: 20px;'>				
			<div>
				<h4>Load ONLY Lightbox Files on ALL Pages:</h4>
				<p style="font-size: 12px; text-align: justify;">Please define if you want to load the lightbox files on ALL pages, even if no shortcode has been detected:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_loadLightbox",
						"label"				=> "Load Lightbox On All Pages",
						"value"             => $ts_vcsc_extend_settings_loadLightbox,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_loadLightbox);
				?>
			</div>
			<hr class='style-six' style='margin-top: 20px;'>
			<div>
				<h4>Load Hammer.js in v2.x Release:</h4>
				<p style="font-size: 12px; text-align: justify;">Please define if you want to use the current v2.x release of Hammer.js, or the older (deprecated) v1.x release:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_loadHammerNew",
						"label"				=> "Load v2.x Release of Hammer.js",
						"value"             => $ts_vcsc_extend_settings_loadHammerNew,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_loadHammerNew);
				?>
			</div>		
			<hr class='style-six' style='margin-top: 20px;'>				
			<div>
				<h4>Load ONLY Tooltip Files on ALL Pages:</h4>
				<p style="font-size: 12px; text-align: justify;">Please define if you want to load the tooltip files on ALL pages, even if no shortcode has been detected:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_loadTooltip",
						"label"				=> "Load Tooltips On All Pages",
						"value"             => $ts_vcsc_extend_settings_loadTooltip,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_loadTooltip);
				?>
			</div>			
			<hr class='style-six' style='margin-top: 20px;'>				
			<div>
				<h4>Load ONLY Icon Font Files on ALL Pages:</h4>
				<p style="font-size: 12px; text-align: justify;">Please define if you want to load the active Icon Font files on ALL pages, even if no shortcode has been detected:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_loadFonts",
						"label"				=> "Load Active Icon Fonts On All Pages",
						"value"             => $ts_vcsc_extend_settings_loadFonts,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_loadFonts);
				?>
			</div>		
			<hr class='style-six' style='margin-top: 20px;'>				
			<div>
				<h4>Load ALL Core Files on ALL Pages:</h4>
				<p style="font-size: 12px; text-align: justify;">Please define if you want to load ALL of the plugin's core files on ALL pages, even if no shortcode has been detected:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_loadForcable",
						"label"				=> "Load ALL Core Files On All Pages",
						"value"             => $ts_vcsc_extend_settings_loadForcable,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_loadForcable);
				?>
			</div>			
			<hr class='style-six' style='margin-top: 20px;'>		
			<div>
				<h4>Load External Files in HEAD:</h4>
				<p style="font-size: 12px; text-align: justify;">Please define if you want to attempt to load the JS Files in the HEAD section (final control is with WordPress):</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_loadHeader",
						"label"				=> "Attempt to Load all Files in HEAD",
						"value"             => $ts_vcsc_extend_settings_loadHeader,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_loadHeader);
				?>
			</div>		
			<hr class='style-six' style='margin-top: 20px;'>		
			<div>
				<h4>Load Files via WordPress Standard:</h4>
				<p style="font-size: 12px; text-align: justify;">Please define if you want to load the script files via "wp_enqueue_script" and "wp_enqueue_style":</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_loadEnqueue",
						"label"				=> "Load Files with Standard Method",
						"value"             => $ts_vcsc_extend_settings_loadEnqueue,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_loadEnqueue);
				?>
			</div>		
			<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 30px; margin-bottom: 30px; font-size: 13px; text-align: justify;">
				This plugin will load some external CSS and JS files in order to make the content elements work on the front end. Your theme or another plugin might already load the same file, which in some cases can cause problems. Use this page to enable/disable the files this plugin should be allowed to load on the front end.
			</div>				
			<div>
				<h4>Load Modernizr File:</h4>
				<p style="font-size: 12px; text-align: justify;">Please define if you want to load the Modernizr file to ensure CSS3 compatibility for most browsers:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_loadModernizr",
						"label"				=> "Load Modernizr Script",
						"value"             => $ts_vcsc_extend_settings_loadModernizr,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_loadModernizr);
				?>
			</div>		
			<hr class='style-six' style='margin-top: 20px;'>				
			<div>
				<h4>Load Waypoints File:</h4>
				<p style="font-size: 12px; text-align: justify;">Please define if you want to load the Waypoints File for Viewport Animations; preventing that file from being loaded via this option will also prevent WP Bakery Page Builder itself from loading it, so ensure that the file is in fact already loaded by your theme or another plugin:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_loadWaypoints",
						"label"				=> "Load WayPoints Script",
						"value"             => $ts_vcsc_extend_settings_loadWaypoints,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_loadWaypoints);
				?>
			</div>			
			<hr class='style-six' style='margin-top: 20px; display: <?php echo ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_UseEnlighterJS == "true" ? "block" : "none") ?>;'>				
			<div style="display: <?php echo ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_UseEnlighterJS == "true" ? "block" : "none") ?>; margin-bottom: 10px;">
				<h4>Load MooTools Library:</h4>
				<p style="font-size: 12px; text-align: justify;">Please define if you want to load the MooTools library for the EnlighterJS - Syntax Highlighter elements:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_loadMooTools",
						"label"				=> "Load MooTools Library",
						"value"             => $ts_vcsc_extend_settings_loadMooTools,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_loadMooTools);
				?>
			</div>
		</div>
	</div>
</div>