<?php
	global $VISUAL_COMPOSER_EXTENSIONS;
?>
<div id="ts-settings-composer" class="tab-content">
	<div class="ts-vcsc-section-main">
		<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-welcome-view-site"></i>Element Preview & Other Settings</div>
		<div class="ts-vcsc-section-content">
			<div style="margin-top: 10px; margin-bottom: 10px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Show Live Preview in Backend Editor:</div>
				<p style="font-size: 12px;">Define if the plugin should render a live preview of basic elements when using the backend editor:</p>
				<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					For some more basic element that don't have any dependencies on JavaScript routines, the plugin can create a live preview of how the element would look like in the frontend while editing in the backend editor. Additional attributes like links or CSS3 animations will of course not be shown, just a graphic rendering of the element. Additional stylesheets (CSS) will have to be loaded to define element styling.
				</div>	
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_backendPreview",
						"label"				=> "Show Live Preview",
						"value"             => $ts_vcsc_extend_settings_backendPreview,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_backendPreview);
				?>
			</div>			
			<div style="margin-top: 30px; margin-bottom: 10px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Show Preview Images in Backend Editor:</div>
				<p style="font-size: 12px;">Define if the plugin should show preview images for all elements using images, or just the image ID when editing a page in the back-end editor:</p>
				<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					By default, the plugin will always show a thumbnail preview image for all of its elements that can utilize images. If you have many of those elements on one site, it can slow down loading times while editing on the backend as the thumbnail for each image has to be loaded individually via Ajax request. If you prefer, you can therefore disable that preview and you will be provided with the WordPress image ID number instead. This setting will not affect the live preview rendering of basic elements as defined above.
				</div>	
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_previewImages",
						"label"				=> "Show Preview Images",
						"value"             => $ts_vcsc_extend_settings_previewImages,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_previewImages);
				?>
			</div>
			<div style="margin-top: 30px; margin-bottom: 10px;" class="<?php echo $TS_VCSC_SimpleOptionsClass; ?>">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Provide Container Toggle Controls:</div>
				<p style="font-size: 12px;">Define if the plugin should provide you with a toggle control for container elements when using the backend editor:</p>
				<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					Some elements in WP Bakery Page Builder are so-called container element, where you add (a selection of allowable) child elements to the main container, in order to provide content to the main element. Depending upon the element type, this can create rather large element previews, which is why a toggle control can be added to the container element, allowing you to make the container small (by effectively hiding the child elements). This in return will make it much easier to move large container elements throughout the page and minify scrolling. <strong>This option is only available for the backend editor.</strong>
				</div>	
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_containerToggle",
						"label"				=> "Provide Container Toggle",
						"value"             => $ts_vcsc_extend_settings_containerToggle,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_containerToggle);
				?>
			</div>
			<div style="margin-top: 30px; margin-bottom: 10px;" class="<?php echo $TS_VCSC_SimpleOptionsClass; ?>">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Provide Element Filter Controls:</div>
				<p style="font-size: 12px;">Define if the plugin should provide you with an advanced group filter for all add-on elements in the WP Bakery Page Builder element selection panel:</p>
				<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					If all elements of this add-on are enabled, you will find <?php echo $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_CountTotalElements; ?> new elements in the selection panel in WP Bakery Page Builder. With such a large number, finding the right element can take a moment, which is why this add-on also provides an option for an additional element group filter, when using the selection panel. The filter will only apply to elements from this add-on and should only be used with WP Bakery Page Builder versions that do not have an already modified selection panel (some theme authors restyle/modify the selection panel).
				</div>	
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_elementFilter",
						"label"				=> "Provide Element Filter",
						"value"             => $ts_vcsc_extend_settings_elementFilter,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_elementFilter);
				?>
			</div>
			<div style="margin-top: 30px; margin-bottom: 10px;" class="<?php echo $TS_VCSC_SimpleOptionsClass; ?>">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Allow Extended Container Elements Nesting:</div>
				<p style="font-size: 12px;">Define if the plugin should allow the usage of its container elements beyond the officially supported two levels of nested shortcodes:</p>
				<div class="ts-vcsc-notice-field ts-vcsc-critical" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					Officially, WP Bakery Page Builder does NOT support the usage of container elements within other elements that would create more than 2 sub-levels of nested elements. That means, that you can't use container elements within inner (child) rows or other elements such as tabs or accordions. You have the option to "unlock" some container elements within this plugin to be used in nested levels beyond the supported 2 levels, but be advised that neither WP Bakery Page Builder itself or this addon can guaranty that those container elements will behave and render correctly. You will still not be able to drag and drop container elements between columns that are nested more than two levels, only to insert new ones. <strong>Use this option carefully and at your own risk!</strong>
				</div>	
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_allowExtendedNesting",
						"label"				=> "Allow Extended Container Elements Nesting",
						"value"             => $ts_vcsc_extend_settings_allowExtendedNesting,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_allowExtendedNesting);
				?>
			</div>
		</div>		
	</div>	
	<div class="ts-vcsc-section-main <?php echo $TS_VCSC_SimpleOptionsClass; ?>">
		<div class="ts-vcsc-section-title ts-vcsc-section-hide"><i class="dashicons-admin-tools"></i>Element Setting Panels</div>
		<div class="ts-vcsc-section-content slideFade" style="display: none;">
			<div style="margin-top: 20px; margin-bottom: 10px; display: block;">
				<h3>tinyMCE Text Editors:</h3>
				<p style="font-size: 12px;">Define if the plugin should, whenever applicable, replace base64 encoded simple textarea with advanced tinyMCE text editors:</p>				
				<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					In order to safely store shortcode attributes that include HTML code or special characters, for example tooltip content, this plugin is encoding the content via base64, using a special textarea as input, that is provided and handled by WP Bakery Page Builder. If you prefer an actual tinyMCE text editor instead, porividng some basic text formatting tools, use the option below. Naturally, rendering a tinyMCE text editor is more ressource and time intensive than a basic textarea, so if you are more concerned over performance, keep the option disabled.
				</div>	
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_tinymceEncoded",
						"label"				=> "Use tinyMCE Text Editors",
						"value"             => $ts_vcsc_extend_settings_tinymceEncoded,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_tinymceEncoded);
				?>
			</div>			
			<div style="margin-top: 20px; margin-bottom: 10px; display: block;">
				<h3>Visual Icon Selector:</h3>
				<p style="font-size: 12px;">Define if the plugin should provide you with a visual icon selector for elements, or if you want to manually enter the icon class name:</p>				
				<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					While the visual icon selector is more convenient to use as you immediately know how the icon looks like, it might slow down your site if you have too many icons (icon fonts) activated as it takes more time to create the visual preview of 1,000+ icons, than it does for 200 icons. In those cases, you can disable the visual icon selector and instead provide your icon of choice by entering its class name.
				</div>	
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_visualSelector",
						"label"				=> "Use Visual Icon Selector",
						"value"             => $ts_vcsc_extend_settings_visualSelector,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_visualSelector);
				?>
			</div>	
			<div id="ts_vcsc_extend_settings_visualSelector_true" data-native="<?php echo $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorIconFontsInternal; ?>" style="margin-top: 20px; margin-bottom: 10px; margin-left: 25px; display: <?php echo ((($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorIconFontsInternal == "true") && ($ts_vcsc_extend_settings_visualSelector == 1)) ? "block;" : "none;"); ?>">
				<h4>Number of Icons per Page:</h4>
				<p style="font-size: 12px;">Define the number of icons that should be shown per page when using the icon picker:</p>
				<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					The more icons you are showing per page, the slower the icon picker element will initially render, as it takes more time to build a visual preview of 200 icons, than it would for 1,000. The limit set here will only apply to the native icon pickers utilized by this add-on; it will not transfer to the same type of icon picker used by WP Bakery Page Builder itself or other add-ons.
				</div>	
				<div class="ts-nouislider-input-slider" style="float: left; display: block; width: 100%; margin-bottom: 20px;">
					<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="ts_vcsc_extend_settings_nativePaginator" id="ts_vcsc_extend_settings_nativePaginator" class="ts_vcsc_extend_settings_nativePaginator ts-nouislider-serial nouislider-input-selector nouislider-input-composer" type="number" min="50" max="1000" step="1" value="<?php echo $ts_vcsc_extend_settings_nativePaginator; ?>"/>
					<span style="float: left; margin-right: 30px; margin-top: 10px;" class="unit">Icons</span>
					<div id="ts_vcsc_extend_settings_nativePaginator_slider" class="ts-nouislider-input ts-nouislider-settings-element" data-value="<?php echo $ts_vcsc_extend_settings_nativePaginator; ?>" data-min="50" data-max="1000" data-decimals="0" data-step="1" style="width: 250px; float: left; margin-top: 10px;"></div>
				</div>
			</div>
			<div style="display: none; margin-top: 10px; margin-bottom: 10px; width: 100%;">
				<h3>Numeric Slider Inputs (NoUiSlider):</h3>
				<p style="font-size: 12px;">For most numeric inputs the plugin provides for in element settings, a slider for faster value setting is provided:</p>
				<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					Whenever possible, this plugin will render numeric inputs in a layout that allows for multiple ways of setting the value (manual input, slider or plus/minus buttons). By default, the plugin will also render a scale beneath the slider to show the range of possible values, as well as a tooltip for the slider to showcase the defined value. Both those features can be disabled if you prefer a less cluttered layout.
				</div>
				<div style="margin-top: 0px; margin-bottom: 10px;">
					<h4>Show Tooltip:</h4>
					<p style="font-size: 12px;">Define if the numeric input slider should show a tooltip above the slider, highlighting the currently selected value:</p>
					<?php
						$settings = array(
							"param_name"        => "ts_vcsc_extend_settings_nouisliderTooltip",
							"label"				=> "Show Tooltip Above Slider",
							"value"             => $ts_vcsc_extend_settings_nouisliderTooltip,
							"order"				=> 1,
						);
						echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_nouisliderTooltip);
					?>
				</div>
				<img style="width: 100%; max-width: 700px; height: auto; margin: 20px auto; border: 1px solid #cccccc;" src="<?php echo TS_VCSC_GetResourceURL('images/other/parameter_nouislider.jpg'); ?>">
				<div style="margin-top: 0px; margin-bottom: 10px;">
					<h4>Show Pips / Scale:</h4>
					<p style="font-size: 12px;">Define if the numeric input slider should show a scale below the slider, indicating the range of allowable values and main steps:</p>
					<?php
						$settings = array(
							"param_name"        => "ts_vcsc_extend_settings_nouisliderPips",
							"label"				=> "Show Pips / Scale Below Slider",
							"value"             => $ts_vcsc_extend_settings_nouisliderPips,
							"order"				=> 1,
						);
						echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_nouisliderPips);
					?>
				</div>
			</div>
			<div style="display: block; margin-top: 10px; margin-bottom: 10px; width: 100%;">
				<h3>Advanced Link Selector:</h3>
				<p style="font-size: 12px;">Define if the plugin should provide you with an advanced link selector, based on page/post ID, instead of the standard one that is provided by WP Bakery Page Builder:</p>				
				<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					By default, this plugin will use the standard link selector that is part of WP Bakery Page Builder, which is usually sufficient and faster. But if for some reason, you are frequently changing page/post names and/or slugs, which would also change the permalink to that page or post, rendering the link created by the standard link selector invalid, we provide our advanced link selector as an alternative. Instead of using the last known permalink directly, the advanced link selector will use the numeric page/post ID as basis. That will allow links created with the advanced link picker to always be current, as long as you don't change the numeric page/post ID number.
				</div>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_linkerEnabled",
						"label"				=> "Use Advanced Link Selector",
						"value"             => $ts_vcsc_extend_settings_linkerEnabled,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_linkerEnabled);
				?>
			</div>
			<div id="ts_vcsc_extend_settings_linker_true" style="margin-top: 20px; margin-bottom: 10px; margin-left: 25px; <?php echo ($ts_vcsc_extend_settings_linkerEnabled == 0 ? 'display: none;' : 'display: block;'); ?>">
				<div style="margin-top: 0px; margin-bottom: 10px;">
					<h4>Show Standard Posts:</h4>
					<p style="font-size: 12px;">Define if the link selector should also show a listing of all standard WordPress posts, aside from pages:</p>
					<?php
						$settings = array(
							"param_name"        => "ts_vcsc_extend_settings_linkerPosts",
							"label"				=> "Show Standard Posts Listing",
							"value"             => $ts_vcsc_extend_settings_linkerPosts,
							"order"				=> 1,
						);
						echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_linkerPosts);
					?>
				</div>				
				<div style="margin-top: 0px; margin-bottom: 10px;">
					<h4>Show Custom Posts:</h4>
					<p style="font-size: 12px;">Define if the link selector should also show a listing of custom WordPress posts, aside from pages (custom posts must be registered as public, queryable and searchable):</p>
					<?php
						$settings = array(
							"param_name"        => "ts_vcsc_extend_settings_linkerCustom",
							"label"				=> "Show Custom Posts Listing",
							"value"             => $ts_vcsc_extend_settings_linkerCustom,
							"order"				=> 1,
						);
						echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_linkerCustom);
					?>
				</div>				
				<div style="margin-top: 10px; margin-bottom: 10px; width: 100%;">
					<h4>LazyLoad Offset:</h4>
					<p style="font-size: 12px;">Define the lazyload offset (interval) at which more pages/posts should be added to the link selector once you scroll to the end of the current list:</p>
					<div class="ts-nouislider-input-slider" style="float: left; display: block; width: 100%">
						<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="ts_vcsc_extend_settings_linkerOffset" id="ts_vcsc_extend_settings_linkerOffset" class="ts_vcsc_extend_settings_linkerOffset ts-nouislider-serial nouislider-input-selector nouislider-input-composer" type="number" min="10" max="99" step="1" value="<?php echo $ts_vcsc_extend_settings_linkerOffset; ?>"/>
						<span style="float: left; margin-right: 30px; margin-top: 10px;" class="unit">Links</span>
						<div id="ts_vcsc_extend_settings_linkerOffset_slider" class="ts-nouislider-input ts-nouislider-settings-element" data-value="<?php echo $ts_vcsc_extend_settings_linkerOffset; ?>" data-min="10" data-max="99" data-decimals="0" data-step="1" style="width: 250px; float: left; margin-top: 10px;"></div>
					</div>
				</div>
				<div style="margin-top: 20px; margin-bottom: 10px;">
					<h4>Order By Criteria</h4>
					<p>Please define which criteria should be used to order the pages or post in the link selector:</p>
					<label for="ts_vcsc_extend_settings_linkerOrderby" class="ts_vcsc_extend_settings_defaultLightbox">Page/Post Order By Criteria:</label>
					<select id="ts_vcsc_extend_settings_linkerOrderby" name="ts_vcsc_extend_settings_linkerOrderby" style="width: 250px; margin-left: 20px;">
						<option value="title" <?php echo selected('title', $ts_vcsc_extend_settings_linkerOrderby); ?>>Page/Post Title</option>
						<option value="date" <?php echo selected('date', $ts_vcsc_extend_settings_linkerOrderby); ?>>Page/Post Publish Date</option>
						<option value="modified" <?php echo selected('modified', $ts_vcsc_extend_settings_linkerOrderby); ?>>Page/Post Modify Date</option>
						<option value="id" <?php echo selected('id', $ts_vcsc_extend_settings_linkerOrderby); ?>>Page/Post ID</option>
						<option value="author" <?php echo selected('author', $ts_vcsc_extend_settings_linkerOrderby); ?>>Page/Post Author</option>
					</select>
				</div>
				<div style="margin-top: 20px; margin-bottom: 10px;">
					<h4>Order Direction</h4>
					<p>Please define which direction should be used to order the pages or post in the link selector:</p>
					<label for="ts_vcsc_extend_settings_linkerOrder" class="ts_vcsc_extend_settings_defaultLightbox">Page/Post Order Direction:</label>
					<select id="ts_vcsc_extend_settings_linkerOrder" name="ts_vcsc_extend_settings_linkerOrder" style="width: 250px; margin-left: 20px;">
						<option value="ASC" <?php echo selected('ASC', $ts_vcsc_extend_settings_linkerOrder); ?>>Ascending</option>
						<option value="DESC" <?php echo selected('DESC', $ts_vcsc_extend_settings_linkerOrder); ?>>Descending</option>
					</select>
				</div>
			</div>
		</div>		
	</div>
	<div class="ts-vcsc-section-main <?php echo $TS_VCSC_SimpleOptionsClass; ?>">
		<div class="ts-vcsc-section-title ts-vcsc-section-hide"><i class="dashicons-editor-code"></i>Shortcode Viewer Popup (BETA)</div>
		<div class="ts-vcsc-section-content slideFade" style="display: none;">
			<div class="ts-vcsc-info-field ts-vcsc-warning" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
				If you for some reason require the underlying shortcode for any given element, including its parameters settings, you can enable the feature below, which will add a new button (identified by a shortcode icon) to each element, which will open a modal popup. The modal popup will provide you with the underlying shortcode for the element (including all child elements), and give you the option to easily copy the shortcode to your clipboard for further usage.
			</div>
			<div class="ts-vcsc-info-field ts-vcsc-critical" style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
				Please note that this feature is currently only available for the WordPress backend editor, but not (yet) for the WP Bakery Page Builder frontend editor.
			</div>
			<div style="margin-top: 10px; margin-bottom: 10px;">
				<div style="font-weight: bold; font-size: 14px; margin: 0;">Enable Shortcode Viewer:</div>
				<p style="font-size: 12px;">Enable the shortcode viewer popup feature to easily retrieve element shortcodes:</p>
				<?php
					$settings = array(
						"param_name"        => "ts_vcsc_extend_settings_allowShortcodeViewer",
						"label"				=> "Enable Shortcode Viewer",
						"value"             => $ts_vcsc_extend_settings_allowShortcodeViewer,
						"order"				=> 1,
					);
					echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_allowShortcodeViewer);
				?>
			</div>
		</div>
	</div>	
	<?php
		if (TS_VCSC_CheckUserRole(array('administrator'))) {
			if ((function_exists('vc_enabled_frontend')) && (function_exists('vc_disable_frontend'))) {
	?>
				<div class="ts-vcsc-section-main <?php echo $TS_VCSC_SimpleOptionsClass; ?>">
					<div class="ts-vcsc-section-title ts-vcsc-section-hide"><i class="dashicons-admin-customizer"></i>Frontend Editor Usage</div>
					<div class="ts-vcsc-section-content slideFade" style="display: none;">
						<?php
							echo '<div style="margin-top: 10px; margin-bottom: 10px;">';
								echo '<div style="font-weight: bold; font-size: 14px; margin: 0;">Enable Frontend Editor:</div>';
								echo '<p style="font-size: 12px;">Define if the Frontend-Editor for WP Bakery Page Builder should be available or not:</p>';
								echo '<div class="ts-vcsc-notice-field ts-vcsc-critical" style="margin-top: 10px; font-size: 13px; text-align: justify;">';
									echo 'You can disable the Frontend-Editor for WP Bakery Page Builder by using the option below. <strong>This setting might not work if your theme or another plugin is applying a contradicting setting at a later time during the page creation process. </strong>Even with the Frontend-Editor enabled, we always recommend editing pages via the default backend editor as that is the way WordPress intends pages to be edited. Due to the complexity of some of our elements, we also can not guaranty full functionality of the Frontend-Editor since that editor is designed to handle the basic elements that are native to WP Bakery Page Builder, but is still not able to fully support more complex elements.';
								echo '</div>';
								$settings = array(
									"param_name"        => "ts_vcsc_extend_settings_frontendEditor",
									"label"				=> "Enable Frontend-Editor",
									"value"             => $ts_vcsc_extend_settings_frontendEditor,
									"order"				=> 1,
								);
								echo TS_VCSC_CodeStarButton_Settings_Field($settings, $ts_vcsc_extend_settings_frontendEditor);								
							echo '</div>';
						?>	
					</div>		
				</div>
	<?php
			}
		}
	?>
</div>