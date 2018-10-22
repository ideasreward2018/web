<?php
	global $VISUAL_COMPOSER_EXTENSIONS;
	
	$MenuPosition_Widgets						= (((is_array($TS_VCSC_Menu_Positions)) && (array_key_exists('ts_widgets', $TS_VCSC_Menu_Positions))) 			? $TS_VCSC_Menu_Positions['ts_widgets'] 			: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Menu_Positions_Defaults['ts_widgets']);
	$MenuPosition_Timeline						= (((is_array($TS_VCSC_Menu_Positions)) && (array_key_exists('ts_timeline', $TS_VCSC_Menu_Positions))) 			? $TS_VCSC_Menu_Positions['ts_timeline'] 			: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Menu_Positions_Defaults['ts_timeline']);
	$MenuPosition_Team							= (((is_array($TS_VCSC_Menu_Positions)) && (array_key_exists('ts_team', $TS_VCSC_Menu_Positions))) 				? $TS_VCSC_Menu_Positions['ts_team'] 				: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Menu_Positions_Defaults['ts_team']);
	$MenuPosition_Testimonials					= (((is_array($TS_VCSC_Menu_Positions)) && (array_key_exists('ts_testimonials', $TS_VCSC_Menu_Positions))) 		? $TS_VCSC_Menu_Positions['ts_testimonials'] 		: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Menu_Positions_Defaults['ts_testimonials']);
	$MenuPosition_Skillsets						= (((is_array($TS_VCSC_Menu_Positions)) && (array_key_exists('ts_skillsets', $TS_VCSC_Menu_Positions))) 		? $TS_VCSC_Menu_Positions['ts_skillsets'] 			: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Menu_Positions_Defaults['ts_skillsets']);
	$MenuPosition_Logos							= (((is_array($TS_VCSC_Menu_Positions)) && (array_key_exists('ts_logos', $TS_VCSC_Menu_Positions))) 			? $TS_VCSC_Menu_Positions['ts_logos'] 				: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Menu_Positions_Defaults['ts_logos']);
	$MenuPosition_Downpage						= (((is_array($TS_VCSC_Menu_Positions)) && (array_key_exists('ts_downtime', $TS_VCSC_Menu_Positions))) 			? $TS_VCSC_Menu_Positions['ts_downtime']			: $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Menu_Positions_Defaults['ts_downtime']);
?>
<div id="ts-settings-posttypes" class="tab-content">
	<div class="ts-vcsc-section-main" style="display: <?php echo ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginPHP == "false" ? "block" : "none"); ?>;">
		<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-warning"></i>Server Setup Warning</div>
		<div class="ts-vcsc-section-content">	
			<?php
				echo '<div class="ts-vcsc-info-field ts-vcsc-critical" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; font-weight: normal; text-align: justify;">';
					echo 'Your server is currently running the outdated PHP version <span style="font-weight: bold;">' . PHP_VERSION . '</span>, which is not sufficient for some of the advanced features and custom post types this
					plugin provides for. In order to use all features, please change your server settings to use at least PHP v5.4.x. WordPress itself recommends using PHP v5.6.0 or higher, as all older PHP versions have been
					officially retired and are unsupported.';				
					if (array_key_exists(substr(PHP_VERSION, 0, 3), $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PHP_End_Of_Life)) {
						echo '<br/><br/><span style="font-weight: bold;">Your current PHP version has officially been retired and deprecated on ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PHP_End_Of_Life[substr(PHP_VERSION, 0, 3)] . '.</span>';
					}
				echo '</div>';
			?>
		</div>
	</div>
	<div class="ts-vcsc-section-main" style="display: <?php echo ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginPHP == "true" ? "block" : "none"); ?>;">
		<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-format-aside"></i>Manage Element Custom Post Types <span class="ts-vcsc-element-count">(<i class="dashicons-image-filter"></i> <?php echo ($Count_Types); ?>)</span></div>
		<div class="ts-vcsc-section-content">		
 			<div class="ts-vcsc-notice-field ts-vcsc-success" style="margin-top: 10px; font-size: 13px; text-align: justify;">
				Starting with version 2.0, "Composium - WP Bakery Page Builder Extensions" introduced custom post types, to be used for some of the elements and for more complex layouts. If your theme or another plugin already provides a similiar post type (i.e. a post type for "teams"), you can disable the corresponding custom post type that comes with "Composium - WP Bakery Page Builder Extensions". Disabling a custom post type will also disable the corresponding WP Bakery Page Builder elements and shortcodes associated with the post type. <strong>The custom post types listed below will provide you with up tpo <?php echo $Count_Types; ?> additional elements in WP Bakery Page Builder.</strong>
			</div>
			<div style="margin-top: 20px; display: <?php echo ((($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginExtended == "true") && (get_option('ts_vcsc_extend_settings_posttypeTimeline', 1) == 0)) ? "none;" : "block;"); ?>">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Composium Timeline:</div>
				<p style="font-size: 12px;">Enable or disable the custom post type "VC Timeline":</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_customTimelines",
						"label"				=> 'Enable "VC Timeline" Post Type <span class="ts-vcsc-element-count">(' .  $Post_Timeline . ')</span>',
						"value"             => $ts_vcsc_extend_settings_customTimelines,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_customTimelines);
				?>
			</div>		
			<div style="margin-top: 20px; display: <?php echo ((($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginExtended == "true") && (get_option('ts_vcsc_extend_settings_posttypeTeam', 1) == 0)) ? "none;" : "block;"); ?>">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Composium Team:</div>
				<p style="font-size: 12px;">Enable or disable the custom post type "VC Team":</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_customTeam",
						"label"				=> 'Enable "VC Team" Post Type <span class="ts-vcsc-element-count">(' .  $Post_Team . ')</span>',
						"value"             => $ts_vcsc_extend_settings_customTeam,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_customTeam);
				?>
			</div>
			<div style="margin-top: 20px; display: <?php echo ((($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginExtended == "true") && (get_option('ts_vcsc_extend_settings_posttypeTestimonial', 1) == 0)) ? "none;" : "block;"); ?>">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Composium Testimonials:</div>
				<p style="font-size: 12px;">Enable or disable the custom post type "VC Testimonials":</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_customTestimonial",
						"label"				=> 'Enable "VC Testimonials" Post Type <span class="ts-vcsc-element-count">(' .  $Post_Testimonial . ')</span>',
						"value"             => $ts_vcsc_extend_settings_customTestimonial,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_customTestimonial);
				?>
			</div>					
			<div style="margin-top: 20px; display: <?php echo ((($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginExtended == "true") && (get_option('ts_vcsc_extend_settings_posttypeSkillset', 1) == 0)) ? "none;" : "block;"); ?>">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Composium Skillsets:</div>
				<p style="font-size: 12px;">Enable or disable the custom post type "VC Skillsets":</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_customSkillset",
						"label"				=> 'Enable "VC Skillsets" Post Type <span class="ts-vcsc-element-count">(' .  $Post_Skillsets . ')</span>',
						"value"             => $ts_vcsc_extend_settings_customSkillset,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_customSkillset);
				?>
			</div>	
			<div style="margin-top: 20px; display: <?php echo ((($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginExtended == "true") && (get_option('ts_vcsc_extend_settings_posttypeLogo', 1) == 0)) ? "none;" : "block;"); ?>">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Composium Logos:</div>
				<p style="font-size: 12px;">Enable or disable the custom post type "VC Logos":</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_customLogo",
						"label"				=> 'Enable "VC Logos" Post Type <span class="ts-vcsc-element-count">(' .  $Post_Logo . ')</span>',
						"value"             => $ts_vcsc_extend_settings_customLogo,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_customLogo);
				?>
			</div>				
			<div style="height: 0px; width: 100%; margin: 0 0 10px 0; padding: 0;"></div>
		</div>
	</div>
	<?php if (($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginPHP == "true") && ((($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginExtended == "true") && (get_option('ts_vcsc_extend_settings_posttypeWidget', 1) == 1)) || (($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginExtended == "false")))) { ?>
		<div class="ts-vcsc-section-main">
			<div class="ts-vcsc-section-title ts-vcsc-section-hide"><i class="dashicons-welcome-widgets-menus"></i>Widgets + Template Builder Post Type (BETA) <span class="ts-vcsc-element-count">(<i class="dashicons-image-filter"></i> <?php echo ($Post_Widget); ?>)</span></div>
			<div class="ts-vcsc-section-content slideFade" style="display: none;">
				<div class="ts-vcsc-notice-field ts-vcsc-success" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					The custom post type "VC Templates" will allow you to use any WP Bakery Page Builder or add-on element when creating its content, which can then be shown in any sidebar via corresponding widget. The post type can also be used to create a template for an element, which can then be used anywhere else where WP Bakery Page Builder is enabled, simply by using the dedicated "TS VC Template (BETA)" element. That way, any changes made to the template, will automatically transfer to all pages and posts that use that template.<br/><br/>This post type, unlike the ones above, does not have any external dependencies. Any content created with this post type can only be edited with the standard WordPress backend editor and will not be available in the WP Bakery Page Builder frontend editor, once shown in a sidebar or via the dedicated element, since the frontend editor does not provide access to widget content. Whene editing a single "VC Templates" post type directly, the frontend editor will be available, however. In general, this feature is still considered to be in BETA mode!
				</div>
				<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					Please be aware that VWP Bakery Page Builder itself is NOT designed to be used in a sidebar, as WordPress is treating sidebar and main post/page content differently when rendering a page. As such, certain limitations will apply to elements that are used in a sidebar via widget. Please see the usage information in the custom post type "VC Templates".
				</div>	
				<div style="margin-top: 20px; margin-bottom: 20px;">
					<div style="font-weight: bold; font-size: 14px; margin: 0;">Composium Templates + Widgets:</div>
					<p style="font-size: 12px;">Enable or disable the custom post type "VC Templates":</p>
					<?php
						$settings = array(
							"param_name"        => "ts_vcsc_extend_settings_customWidgets",
							"label"				=> 'Enable "VC Templates" Post Type <span class="ts-vcsc-element-count">(' .  $Post_Widget . ')</span>',
							"value"             => $ts_vcsc_extend_settings_customWidgets,
							"order"				=> 1,
						);
						echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_customWidgets);
					?>
				</div>
				<div class="ts-vcsc-info-field ts-vcsc-warning" style="margin-top: 20px; font-size: 13px; text-align: justify;">
					In order to actually use WP Bakery Page Builder elements with this post type, you might have to go to the <a href="<?php echo $visual_composer_link; ?>" target="_parent">settings</a> page ("Role Manager") for Visual Composer itself, and assign this new post type to the list of allowable post types that WP Bakery Page Builder will be available for.
				</div>	
			</div>
		</div>
	<?php } ?>
	<div class="ts-vcsc-section-main" style="display: <?php echo ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginPHP == "true" ? "block" : "none"); ?>;">
		<div class="ts-vcsc-section-title ts-vcsc-section-hide"><i class="dashicons-clock"></i>Website Downtime Manager (BETA)</div>
		<div class="ts-vcsc-section-content slideFade" style="display: none;">
			<div class="ts-vcsc-info-field ts-vcsc-warning" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
				The website downtime manager allows you to create custom downtime (maintenance) pages, using WP Bakery Page Builder, and to place your website into a downtime/maintenance mode, during which your custom page will be shown. Enabling the downtime manager will provide you with a new custom post type "VC Downpages" to create your downtime page, and a new menu entry in your "VC Extensions" menu to manage your scheduled downtime.
			</div>
			<div style="margin-top: 10px; margin-bottom: 10px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Enable Website Downtime Manager + Post Type:</div>
				<p style="font-size: 12px;">Enable the website downtime manager to easily and quickly put your website into downtime (maintenance) mode, while showing a custom page to your visitors:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_allowDowntimeBackup",
						"label"				=> 'Enable Website Downtime Manager + Post Type',
						"value"             => $ts_vcsc_extend_settings_allowDowntimeManager,
						"order"				=> 1,
						"class"				=> "ts-downtime-backup-switch",
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_allowDowntimeManager);
				?>
			</div>
		</div>
	</div>	
	<div class="ts-vcsc-section-main <?php echo $TS_VCSC_SimpleOptionsClass; ?>" style="display: <?php echo ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginPHP == "true" ? "block" : "none"); ?>;">
		<div class="ts-vcsc-section-title ts-vcsc-section-hide"><i class="dashicons-lock"></i>WP Bakery Page Builder Usage in Custom Post Types</div>
		<div class="ts-vcsc-section-content slideFade" style="display: none;">
			<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
				By default, the plugin will attempt to automatically assign WP Bakery Page Builder as allowable editor to the following post types ...<br/><br/>- Widgets + Template Builder Post Type (BETA)<br/>- Website Downtime Manager (BETA)<br/><br/>... which is working just fine for most setups. If for some reason WP Bakery Page Builder is not available for those two post types, please use the option below to make those post types "public", allowing you to manually assign WP Bakery Page Builder to them by using the "Role Manager" within the settings page of WP Bakery Page Builder itself.
			</div>						
			<div style="margin-top: 20px; margin-bottom: 10px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">WP Bakery Page Builder Auto Assignment:</div>
				<p style="font-size: 12px;">Enable or disable the automatic assignment of WP Bakery Page Builder to certain custom post types:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_allowAutoAssignment",
						"label"				=> 'Use WP Bakery Page Builder Auto Assignment',
						"value"             => $ts_vcsc_extend_settings_allowAutoAssignment,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_allowAutoAssignment);
				?>
			</div>
		</div>
	</div>	
	<div class="ts-vcsc-section-main <?php echo $TS_VCSC_SimpleOptionsClass; ?>" style="display: <?php echo ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginPHP == "true" ? "block" : "none"); ?>;">
		<div class="ts-vcsc-section-title ts-vcsc-section-hide"><i class="dashicons-admin-settings"></i>Manage Custom Post Type Menu Positions</div>
		<div class="ts-vcsc-section-content slideFade" style="display: none;">
			<div class="ts-vcsc-notice-field ts-vcsc-critical" style="margin-top: 20px; font-size: 13px; text-align: justify;">
				Provided the associated post types are activated, using the settings above, the plugin will place each custom post type at a pre-determined position in your WordPress admin menu. But if another plugin or your theme is claiming the same position for another custom post type, the post type from this plugin might not be visible, as each position can only be used once. In that case, you can use the settings below to assign a different position to each custom post type this plugin provides for. <strong>Please ensure, that you assign an unique position to each post type.</strong>
			</div>						
			<div class="ts-nouislider-input-slider clearFixMe" style="margin-top: 20px; height: 50px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Position: VC Widgets:</div>
				<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="ts_vcsc_extend_settings_positionWidgets" id="ts_vcsc_extend_settings_positionWidgets" class="ts_vcsc_extend_settings_positionWidgets ts-nouislider-serial nouislider-input-selector nouislider-input-composer" type="number" min="1" max="125" step="1" value="<?php echo $MenuPosition_Widgets; ?>"/>
				<span style="float: left; margin-right: 30px; margin-top: 10px;" class="unit"></span>
				<div id="ts_vcsc_extend_settings_positionWidgets_slider" class="ts-nouislider-input ts-nouislider-settings-element" data-value="<?php echo $MenuPosition_Widgets; ?>" data-min="1" data-max="125" data-decimals="0" data-step="1" style="width: 250px; float: left; margin-top: 10px;"></div>
			</div>
			<div class="ts-nouislider-input-slider clearFixMe" style="margin-top: 20px; height: 50px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Position: VC Timeline:</div>
				<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="ts_vcsc_extend_settings_positionTimeline" id="ts_vcsc_extend_settings_positionTimeline" class="ts_vcsc_extend_settings_positionTimeline ts-nouislider-serial nouislider-input-selector nouislider-input-composer" type="number" min="1" max="125" step="1" value="<?php echo $MenuPosition_Timeline; ?>"/>
				<span style="float: left; margin-right: 30px; margin-top: 10px;" class="unit"></span>
				<div id="ts_vcsc_extend_settings_positionTimeline_slider" class="ts-nouislider-input ts-nouislider-settings-element" data-value="<?php echo $MenuPosition_Timeline; ?>" data-min="1" data-max="125" data-decimals="0" data-step="1" style="width: 250px; float: left; margin-top: 10px;"></div>
			</div>
			<div class="ts-nouislider-input-slider clearFixMe" style="margin-top: 20px; height: 50px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Position: VC Team:</div>
				<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="ts_vcsc_extend_settings_positionTeam" id="ts_vcsc_extend_settings_positionTeam" class="ts_vcsc_extend_settings_positionTeam ts-nouislider-serial nouislider-input-selector nouislider-input-composer" type="number" min="1" max="125" step="1" value="<?php echo $MenuPosition_Team; ?>"/>
				<span style="float: left; margin-right: 30px; margin-top: 10px;" class="unit"></span>
				<div id="ts_vcsc_extend_settings_positionTeam_slider" class="ts-nouislider-input ts-nouislider-settings-element" data-value="<?php echo $MenuPosition_Team; ?>" data-min="1" data-max="125" data-decimals="0" data-step="1" style="width: 250px; float: left; margin-top: 10px;"></div>
			</div>
			<div class="ts-nouislider-input-slider clearFixMe" style="margin-top: 20px; height: 50px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Position: VC Testimonials:</div>
				<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="ts_vcsc_extend_settings_positionTestimonials" id="ts_vcsc_extend_settings_positionTestimonials" class="ts_vcsc_extend_settings_positionTestimonials ts-nouislider-serial nouislider-input-selector nouislider-input-composer" type="number" min="1" max="125" step="1" value="<?php echo $MenuPosition_Testimonials; ?>"/>
				<span style="float: left; margin-right: 30px; margin-top: 10px;" class="unit"></span>
				<div id="ts_vcsc_extend_settings_positionTestimonials_slider" class="ts-nouislider-input ts-nouislider-settings-element" data-value="<?php echo $MenuPosition_Testimonials; ?>" data-min="1" data-max="125" data-decimals="0" data-step="1" style="width: 250px; float: left; margin-top: 10px;"></div>
			</div>
			<div class="ts-nouislider-input-slider clearFixMe" style="margin-top: 20px; height: 50px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Position: VC Skillsets:</div>
				<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="ts_vcsc_extend_settings_positionSkillsets" id="ts_vcsc_extend_settings_positionSkillsets" class="ts_vcsc_extend_settings_positionSkillsets ts-nouislider-serial nouislider-input-selector nouislider-input-composer" type="number" min="1" max="125" step="1" value="<?php echo $MenuPosition_Skillsets; ?>"/>
				<span style="float: left; margin-right: 30px; margin-top: 10px;" class="unit"></span>
				<div id="ts_vcsc_extend_settings_positionSkillsets_slider" class="ts-nouislider-input ts-nouislider-settings-element" data-value="<?php echo $MenuPosition_Skillsets; ?>" data-min="1" data-max="125" data-decimals="0" data-step="1" style="width: 250px; float: left; margin-top: 10px;"></div>
			</div>
			<div class="ts-nouislider-input-slider clearFixMe" style="margin-top: 20px; height: 50px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Position: VC Logos:</div>
				<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="ts_vcsc_extend_settings_positionLogos" id="ts_vcsc_extend_settings_positionLogos" class="ts_vcsc_extend_settings_positionLogos ts-nouislider-serial nouislider-input-selector nouislider-input-composer" type="number" min="1" max="125" step="1" value="<?php echo $MenuPosition_Logos; ?>"/>
				<span style="float: left; margin-right: 30px; margin-top: 10px;" class="unit"></span>
				<div id="ts_vcsc_extend_settings_positionLogos_slider" class="ts-nouislider-input ts-nouislider-settings-element" data-value="<?php echo $MenuPosition_Logos; ?>" data-min="1" data-max="125" data-decimals="0" data-step="1" style="width: 250px; float: left; margin-top: 10px;"></div>
			</div>
			<div class="ts-nouislider-input-slider clearFixMe" style="margin-top: 20px; height: 50px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Position: VC Downpages:</div>
				<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="ts_vcsc_extend_settings_positionDowntime" id="ts_vcsc_extend_settings_positionDowntime" class="ts_vcsc_extend_settings_positionDowntime ts-nouislider-serial nouislider-input-selector nouislider-input-composer" type="number" min="1" max="125" step="1" value="<?php echo $MenuPosition_Downpage; ?>"/>
				<span style="float: left; margin-right: 30px; margin-top: 10px;" class="unit"></span>
				<div id="ts_vcsc_extend_settings_positionDowntime_slider" class="ts-nouislider-input ts-nouislider-settings-element" data-value="<?php echo $MenuPosition_Downpage; ?>" data-min="1" data-max="125" data-decimals="0" data-step="1" style="width: 250px; float: left; margin-top: 10px;"></div>
			</div>
			<div style="height: 0px; width: 100%; margin: 0 0 10px 0; padding: 0;"></div>
		</div>
	</div>	
</div>