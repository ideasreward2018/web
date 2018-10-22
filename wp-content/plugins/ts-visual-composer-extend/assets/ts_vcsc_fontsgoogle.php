<?php
	global $VISUAL_COMPOSER_EXTENSIONS;

	if (isset($_POST['Submit'])) {
		echo '<div id="ts_vcsc_extend_settings_save" style="position: relative; margin: 20px auto 20px auto; width: 128px; height: 128px;">';
			echo TS_VCSC_CreatePreloaderCSS("ts-settings-panel-loader", "", 4, "false");
		echo '</div>';
		$TS_VCSC_Extension_Elements 		= array();
		foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Fonts_Google as $Font_Network => $font) {
			$key 							= str_replace(' ', '', $Font_Network);
			$font_lines = array(
				'active'					=> ((isset($_POST['ts_vcsc_google_font_' . $key])) ? $_POST['ts_vcsc_google_font_' . $key] : 'off'),
				'favorite'					=> ((isset($_POST['ts_vcsc_google_favorite_' . $key])) ? $_POST['ts_vcsc_google_favorite_' . $key] : 'off'),				
			);
			$TS_VCSC_Extension_Elements[$Font_Network] = $font_lines;
		}
		update_option('ts_vcsc_extend_settings_fontDefaults',					$TS_VCSC_Extension_Elements);
		echo '<script> window.location="' . $_SERVER['REQUEST_URI'] . '"; </script> ';
		//Header('Location: '.$_SERVER['REQUEST_URI']);
		Exit();
	} else {
		$font_array 						= array();	
		$font_count 						= 0;
		$font_stored 						= get_option('ts_vcsc_extend_settings_fontDefaults', '');
		if (($font_stored == false) || (empty($font_stored)) || ($font_stored == "") || (!is_array($font_stored))) {
			$font_stored					= array();
		}
		$group_array						= array();
		$group_count						= 0;
		//var_dump($font_stored);	
		foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Fonts_Google as $Font_Network => $font) {
			$font_lines = array(
				'name' 						=> $Font_Network,
				'google' 					=> $font['google'],
				'group'						=> ucfirst($Font_Network[0]),
				'settings'					=> str_replace(' ', '', $Font_Network),
				'variants'					=> $font['variants'],
				'active'					=> (isset($font_stored[$Font_Network]['active']) ? ($font_stored[$Font_Network]['active'] == 'on' ? "true" : "false") : $font['active']),
				'favorite'					=> (isset($font_stored[$Font_Network]['favorite']) ? ($font_stored[$Font_Network]['favorite'] == 'on' ? "true" : "false") : $font['favorite']),
			);
			$font_array[] 					= $font_lines;
			$font_count 					= $font_count + 1;
			if (!in_array(ucfirst($Font_Network[0]), $group_array)) {
				$group_array[]				= ucfirst($Font_Network[0]);
			}
		}
		TS_VCSC_SortMultiArray($font_array, 'name');
		/*
		// Retrieve current Google Font List (Format via: http://textmechanic.com/)
		$url 								= 'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyCgepCbgScRX7bUXrXb1NWHRIO_jk_VfXw';
		$ch 								= curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_REFERER, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$result 							= curl_exec($ch);
		curl_close($ch);
		$data 								= json_decode($result, true);
		$items 								= $data['items'];
		$current_array						= array();
		$format_array 						= array();
		$names_array 						= array();
		$i 									= 0;
		foreach ($items as $item) {			
			$key							= $item['family'];
			$variants						= '';
			foreach ($item['variants'] as $variant) {
			  $variants[] 					= $variant;
			}
			// Array (Names Only)
			array_push($names_array, str_replace(' ', '+', $item['family']));
			// Array (for manual Format)
			$font_data = array(
				"google" 					=> '"' . str_replace(' ', '+', $item['family']) . '",',
				"variants"					=> '"' . implode(",", $variants) . '",',
				"active"					=> '"false",',
				"favorite"					=> '"false",',
			);			
			$format_array[$key]				= $font_data;
			// Array (for immediate Use)
			$font_data = array(
				"google" 					=> str_replace(' ', '+', $item['family']),
				"variants"					=> implode(",", $variants),
				"active"					=> 'false',
				"favorite"					=> 'false',
			);			
			$current_array[$key]			= $font_data;			
			$i++;
		}
		var_dump($names_array);
		//var_dump($current_array);
		//print "<pre>";
		//print_r($format_array);
		//print "</pre>";		
		//file_put_contents($VISUAL_COMPOSER_EXTENSIONS->assets_dir . 'Current_Google_Fonts.txt', json_encode($names_array));		
		Exit();
		*/
	}
?>
<form class="ts-vcsc-google-manager-wrap" name="oscimp_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<div id="ts-settings-about" class="tab-content">
		<div class="ts-vcsc-settings-group-header">
			<div class="display_header">
				<h2><span class="dashicons dashicons-googleplus"></span>Composium - WP Bakery Page Builder Extensions v<?php echo TS_VCSC_GetPluginVersion(); ?> ... Google Fonts Manager</h2>
			</div>
			<div class="clear"></div>
		</div>
		<div class="ts-vcsc-settings-group-topbar ts-vcsc-settings-group-buttonbar">
			<a href="javascript:void(0);" class="ts-vcsc-settings-group-toggle" style="display: none;">Expand</a>
			<div class="ts-vcsc-settings-group-actionbar">
				<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder ts-advanced-link-tooltip-right ts-advanced-link-tooltip-bottom">
					<span class="ts-advanced-link-tooltip-content"><?php _e("Click here to save your Google Font selections.", "ts_visual_composer_extend"); ?></span>
					<button type="submit" name="Submit" id="ts_vcsc_extend_settings_submit_1" class="ts-advanced-link-button-main ts-advanced-link-button-blue ts-advanced-link-button-save" style="margin: 0;">
						<?php echo __("Save Collections", "ts_visual_composer_extend"); ?>
					</button>
				</div>				
			</div>
			<div class="clear"></div>
		</div>	
		<div class="ts-vcsc-settings-google-main">
			<div id="ts-vcsc-welcome-links" class="ts-vcsc-section-main">
				<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-googleplus"></i>Google Fonts Manager</div>
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
						Many elements from this plugin include a custom Google font picker, allowing you to override fonts pre-defined by a theme or plugin with a font of your choice. When not using the Google Font Manager, all available Google Fonts are deactivated by default, and you will only have access to so-called "web-safe fonts". This manager allows you to build a custom set of Google Fonts to be used with those elements by simply enabling/disabling the fonts you want to use and/or do not need. By marking some fonts as "favorite", those fonts will be listed on top of the font selector list (in alphabetical order) for faster access.
					</div>
					<?php
						$fonts_total								= sizeof($font_array);
						$fonts_active 								= TS_VCSC_CountArrayMatches($font_array, 'active', 'true');
						$fonts_favorite 							= TS_VCSC_CountArrayMatches($font_array, 'favorite', 'true');
						echo '<div class="ts-vcsc-google-count-summary">';
							echo '<div class="ts-vcsc-google-count-total"><i class="dashicons-marker"></i>Available Fonts: <span>' . $fonts_total . '</span></div>';
							echo '<div class="ts-vcsc-google-count-active"><i class="dashicons-yes"></i>Active Fonts: <span>' . $fonts_active . '</span></div>';
							echo '<div class="ts-vcsc-google-count-favorite"><i class="dashicons-star-filled"></i>Favorite Fonts: <span>' . $fonts_favorite . '</span></div>';
						echo '</div>';
					?>
				</div>
			</div>			
			<div class="ts-vcsc-section-main">
				<div class="ts-vcsc-section-title ts-vcsc-section-hide"><i class="dashicons-format-video"></i>How to use the Google Fonts Manager</div>
				<div class="ts-vcsc-section-content slideFade" style="display: none;">				
					<div class="ts-vcsc-notice-field ts-vcsc-success" style="margin-top: 10px; font-size: 13px; text-align: justify;">
						The selections you make in this "Google Fonts Manager" will directly impact the elements that are able to utilzie Google Fonts, so activate/deactivate fonts carefully. Naturally, the more Google fonts you activate, the more it will affect the speed at which settings panels for elements will render, when editing a page or post.
					</div>	
					<div style="width: 50%; height: 100%;">
						<div class="ts-video-container">
							<iframe style="width: 100%;" width="100%" height="100%" src="//www.youtube.com/embed/iRS6IQGewY0" frameborder="0" allowfullscreen></iframe>
						</div>
					</div>
				</div>
			</div>
			<div id="ts-vcsc-welcome-links" class="ts-vcsc-section-main">
				<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-edit"></i>Font Selections</div>
				<div class="ts-vcsc-section-content">
					<div class="ts-vcsc-notice-field ts-vcsc-success" style="margin-top: 10px; font-size: 13px; text-align: justify;">
						The following tabs, sorted alphabetically, will give you access to all <?php echo $fonts_total; ?> Google Fonts currently registered with this add-on. Simply click on any font you want to add to your personal collection, and once you created your personal set, save the collection. For quick access to your favorite fonts, which will be listed first in the font selectbox when editing an element, you can mark fonts as "favorite" as well.
					</div>
					<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
						For performance reasons, the Google Fonts Manager intentionally is not loading the respective CSS font files for each font. With <?php echo $fonts_total; ?> fonts total, that can result in extended load times, although the individual font files are fairly small, the combined amount is a different story. But you have the option to manually trigger the loading of those files by clicking on the respective button located in each tab, or the "load all" button above the tabs. <strong>Once a CSS font file has been loaded, you will be able to see how the font will actually look like.</strong>
					</div>
					<?php
						echo '<div class="ts-googlefont-manager-toggles" style="margin-top: 20px; margin-bottom: 20px;">';						
							echo '<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder ts-advanced-link-tooltip-right" style="float: right;">';
								echo '<span class="ts-advanced-link-tooltip-content">' . sprintf(__("Click here to load all %d respective CSS files for all Google fonts.", "ts_visual_composer_extend"), $fonts_total) . '</span>';
								echo '<div id="ts-vcsc-google-toggle-preview" class="ts-vcsc-google-toggle-preview ts-advanced-link-button-main ts-advanced-link-button-orange ts-advanced-link-button-jscss" style="min-width: 450px; margin: 0;">';
									echo sprintf(__("Load All %d CSS File(s) for Live Preview", "ts_visual_composer_extend"), $fonts_total);
								echo '</div>';
							echo '</div>';
							echo '<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder" style="margin-right: 20px;">';
								echo '<span class="ts-advanced-link-tooltip-content">' . sprintf(__("Click here to select all %d available Google fonts.", "ts_visual_composer_extend"), $fonts_total) . '</span>';
								echo '<div id="ts-vcsc-google-toggle-showall" class="ts-vcsc-google-toggle-showall ts-advanced-link-button-main ts-advanced-link-button-green ts-advanced-link-button-check" style="min-width: 350px; margin: 0;">';
									echo sprintf(__("Select All %d Font(s)", "ts_visual_composer_extend"), $fonts_total);
								echo '</div>';
							echo '</div>';
							echo '<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder" style="margin-right: 20px;">';
								echo '<span class="ts-advanced-link-tooltip-content">' . sprintf(__("Click here to unselect all %d available Google fonts.", "ts_visual_composer_extend"), $fonts_total) . '</span>';
								echo '<div id="ts-vcsc-google-toggle-hideall" class="ts-vcsc-google-toggle-hideall ts-advanced-link-button-main ts-advanced-link-button-red ts-advanced-link-button-cross" style="min-width: 350px; margin: 0;">';
									echo sprintf(__("Unselect All %d Font(s)", "ts_visual_composer_extend"), $fonts_total);
								echo '</div>';
							echo '</div>';
						echo '</div>';
					?>
					<h4>Google Font Selections:</h4>
					<div>					
						<label id="ts-vcsc-google-font-label" for="ts-vcsc-google-font-search">Search Fonts:</label>
						<input id="ts-vcsc-google-font-search" class="ts-vcsc-google-font-search" type="text" placeholder="">
						<i id="ts-vcsc-google-font-reset" class="ts-vcsc-google-font-reset dashicons-dismiss"></i>
					</div>	
					<?php
						echo '<div id="ts-googlefont-manager-tabs" class="ts-simpletabs-tabs-wrapper" style="margin-bottom: 10px;">';
							echo '<div class="ts-simpletabs-tabs-navigation">';
								echo '<ul class="ts-simpletabs-tabs-tab-links">';
									$tabcounter 						= 0;
									foreach ($group_array as $group) {
										$tabcounter++;
										$fontcount						= TS_VCSC_CountArrayMatches($font_array, 'group', $group);
										echo '<li id="ts-googlefont-manager-tab-trigger' . $group . '" class="' . (($tabcounter == 1) ? "active" : "") . '"><a href="#ts-googlefont-manager-tab' . $group . '"><span>' . $group . ' </span><span style="font-size: 10px;">(' . $fontcount . ')</span><i id="ts-googlefont-manager-tab-flagged' . $group . '" class="ts-googlefont-manager-tab-flagged dashicons-yes" style="display: none;"></i></a></li>';
									}
								echo '</ul>';
								echo '<div id="ts-simpletabs-tab-prev" class="ts-simpletabs-tabs-scroll" data-direction="left"><span><i class="dashicons dashicons-arrow-left-alt2"></i></span></div>';
								echo '<div id="ts-simpletabs-tab-next" class="ts-simpletabs-tabs-scroll" data-direction="right"><span><i class="dashicons dashicons-arrow-right-alt2"></i></span></div>';
							echo '</div>';
							echo '<div class="ts-simpletabs-tabs-content">';
								$tabcounter 						= 0;
								foreach ($group_array as $group) {
									$Font_Count						= TS_VCSC_CountArrayMatches($font_array, 'group', $group);
									$tabcounter++;
									echo '<div id="ts-googlefont-manager-tab' . $group . '" class="ts-simpletabs-tabs-tab-single ' . (($tabcounter == 1) ? "active" : "") . ' clearFixMe" data-link="ts-googlefont-manager-tab-trigger' . $group . '" data-group="' . $group . '" style="">';										
										echo '<div class="ts-googlefont-manager-toggles" style="margin-top: 10px; margin-bottom: 10px;">';
											echo '<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder ts-advanced-link-tooltip-right" style="float: right;">';
												echo '<span class="ts-advanced-link-tooltip-content">' . sprintf(__("Click here to load all %d respective CSS files for all Google fonts in group %s.", "ts_visual_composer_extend"), $Font_Count, $group) . '</span>';
												echo '<div id="ts-vcsc-google-font-preview' . $group . '" class="ts-vcsc-google-font-preview ts-advanced-link-button-main ts-advanced-link-button-orange ts-advanced-link-button-jscss" style="min-width: 450px; margin: 0;">';
													echo sprintf(__("Load %d CSS File(s) for Live Preview in Group %s", "ts_visual_composer_extend"), $Font_Count, $group);
												echo '</div>';
											echo '</div>';
											echo '<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder" style="margin-right: 20px;">';
												echo '<span class="ts-advanced-link-tooltip-content">' . sprintf(__("Click here to select all %d available Google fonts in group %s.", "ts_visual_composer_extend"), $Font_Count, $group) . '</span>';
												echo '<div id="ts-vcsc-google-font-showall' . $group . '" class="ts-vcsc-google-font-showall ts-advanced-link-button-main ts-advanced-link-button-green ts-advanced-link-button-check" style="min-width: 350px; margin: 0;">';
													echo sprintf(__("Select %d Font(s) in Group %s", "ts_visual_composer_extend"), $Font_Count, $group);
												echo '</div>';
											echo '</div>';
											echo '<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder" style="margin-right: 20px;">';
												echo '<span class="ts-advanced-link-tooltip-content">' . sprintf(__("Click here to unselect all %d available Google fonts in group %s.", "ts_visual_composer_extend"), $Font_Count, $group) . '</span>';
												echo '<div id="ts-vcsc-google-font-hideall' . $group . '" class="ts-vcsc-google-font-hideall ts-advanced-link-button-main ts-advanced-link-button-red ts-advanced-link-button-cross" style="min-width: 350px; margin: 0;">';
													echo sprintf(__("Unselect %d Font(s) in Group %s", "ts_visual_composer_extend"), $Font_Count, $group);
												echo '</div>';
											echo '</div>';
										echo '</div>';
										echo '<ul class="ts-vcsc-google-font-selectors" style="margin-top: 0px; font-size: 24px;">';
											foreach ($font_array as $index => $array) {
												$font_networks = '';
												$Font_Name 			= $font_array[$index]['name'];
												$Font_Settings		= $font_array[$index]['settings'];
												$Font_Group			= $font_array[$index]['group'];
												$Font_Google 		= $font_array[$index]['google'];
												$Font_Active		= $font_array[$index]['active'];
												$Font_Favorite		= $font_array[$index]['favorite'];
												$Font_Options		= $font_array[$index]['variants'];
												if (strpos($font_array[$index]['variants'], 'regular') !== false) {
													$Font_Variants	= 'regular';
												} else {
													$Font_Variants	= strtok($font_array[$index]['variants'], ",");
												}												
												$Font_Family		= "font-family: '" . $Font_Name . "';";												
												if ($Font_Group == $group) {
													$font_networks .= '<li class="ts-vcsc-google-font-single" style="display: inline-block; width: 30%; min-width: 200px; margin: 10px 0px;" data-filter="false" data-group="' . $Font_Group . '" data-name="' . $Font_Name . '" data-google="' . $Font_Google . '" data-variants="' . $Font_Variants . '">';
														$font_networks .= '<div class="ts_vcsc_google_font_holder" style="display: block; width: 100%;">';
															$font_networks .= '<input type="checkbox" id="ts_vcsc_google_font_' . $Font_Settings . '" class="ts_vcsc_google_font ts_vcsc_google_font_' . $Font_Settings . '" name="ts_vcsc_google_font_' . $Font_Settings . '" ' . ($Font_Active == "true" ? ' checked="checked"' : '') . '>';
															$font_networks .= '<label for="ts_vcsc_google_font_' . $Font_Settings . '" style="' . $Font_Family . '"><span>' . $Font_Name . '</span></label>';
															$font_networks .= '<i class="ts-vcsc-google-font-loadme dashicons-visibility" data-title="Click to load CSS File for ' . $Font_Name . ' font."></i>';
															$font_networks .= '<span class="ts_vcsc_google_font_variants">Variants: ' . str_replace(",", ", ", $Font_Options) . '</span>';
														$font_networks .= '</div>';
														$font_networks .= '<div class="ts_vcsc_google_favorite_holder" style="display: block; width: 100%; margin-top: 10px;">';
															$font_networks .= '<input type="checkbox" ' . ($Font_Active == "true" ? '' : 'disabled="disabled"') . ' id="ts_vcsc_google_favorite_' . $Font_Settings . '" class="ts_vcsc_google_favorite ts_vcsc_google_favorite_' . $Font_Settings . '" name="ts_vcsc_google_favorite_' . $Font_Settings . '" ' . ($Font_Favorite == "true" ? ' checked="checked"' : '') . '>';
															$font_networks .= '<label for="ts_vcsc_google_favorite_' . $Font_Settings . '" class="' . ($Font_Active == "true" ? "" : "disabled") . '"><span>Mark this Font as Favorite</span></label>';
														$font_networks .= '</div>';
													$font_networks .= '</li>';
													echo $font_networks;
												}
											}
										echo '</ul>';
									echo '</div>';
								}
							echo '</div>';
						echo '</div>';		
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="ts-vcsc-settings-group-bottombar ts-vcsc-settings-group-buttonbar" style="">
		<div class="ts-vcsc-settings-group-actionbar">
			<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder ts-advanced-link-tooltip-right">
				<span class="ts-advanced-link-tooltip-content"><?php _e("Click here to save your Google Font selections.", "ts_visual_composer_extend"); ?></span>
				<button type="submit" name="Submit" id="ts_vcsc_extend_settings_submit_2" class="ts-advanced-link-button-main ts-advanced-link-button-blue ts-advanced-link-button-save" style="margin: 0;">
					<?php _e("Save Collections", "ts_visual_composer_extend"); ?>
				</button>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</form>