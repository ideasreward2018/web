<?php 
	class TS_VCSC_Composer_SubCategories {
		function __construct($args = array()){
			global $VISUAL_COMPOSER_EXTENSIONS;
			$defaults = array(
				'url'											=> ''
			);
			foreach ($defaults as $property => $default){
				$this->$property 								= isset($args[$property]) ? $args[$property] : $default;
			}
			// Inject Filter Controls to Visual Composer
			add_action('vc_backend_editor_render', 				array($this, 'TS_VCSC_EditorRenderAction'));
			add_action('vc_frontend_editor_render_template', 	array($this, 'TS_VCSC_EditorRenderAction'));
			add_filter('vc_add_element_box_buttons', 			array($this, 'TS_VCSC_AddElementBoxButtons'), 10, 1);
		}		
		function TS_VCSC_GetSubCategories(){
			global $VISUAL_COMPOSER_EXTENSIONS;
			$TS_VCSC_Visual_Composer_Categories					= array();
			foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Groups as $GroupName => $category) {
				$TS_VCSC_Visual_Composer_Categories[$category["class"]]	= $category["title"];
			}			
			return apply_filters('composium_vc_subcategories', $TS_VCSC_Visual_Composer_Categories);
		}
		// Assign Each Shortcode Element Matching Filter Classes
		function TS_VCSC_AssignSubCategories(){
			global $VISUAL_COMPOSER_EXTENSIONS;
			$TS_VCSC_Visual_Composer_Categories					= array();
			foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Groups as $GroupName => $category) {
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Elements as $ElementName => $element) {
					if (($GroupName == $element["group"]) && ($element["deprecated"] == "false") && ($element["base"] != "")) {
						$TS_VCSC_Visual_Composer_Categories[$element['base']] = array($category["class"]);
					}
				}
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Children as $ElementName => $element) {
					if (($GroupName == $element["group"]) && ($element["deprecated"] == "false") && ($element["base"] != "")) {
						$TS_VCSC_Visual_Composer_Categories[$element['base']] = array($category["class"]);
					}
				}
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Types as $ElementName => $element) {
					if (($GroupName == $element["group"]) && ($element["deprecated"] == "false") && ($element["base"] != "")) {
						$TS_VCSC_Visual_Composer_Categories[$element['base']] = array($category["class"]);
					}
				}
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Extra as $ElementName => $element) {
					if (($GroupName == $element["group"]) && ($element["deprecated"] == "false") && ($element["base"] != "")) {
						$TS_VCSC_Visual_Composer_Categories[$element['base']] = array($category["class"]);
					}
				}
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Demos as $ElementName => $element) {
					if (($GroupName == $element["group"]) && ($element["deprecated"] == "false") && ($element["base"] != "")) {
						$TS_VCSC_Visual_Composer_Categories[$element['base']] = array($category["class"]);
					}
				}
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_CustomPostTypesWidgets == "true") {
					$TS_VCSC_Visual_Composer_Categories["TS_VCSC_VCWidget_Output"] = array("ts-composium-extras");
				}
			}
			return apply_filters('rhc_vc_shortcode_subcategories', $TS_VCSC_Visual_Composer_Categories);
		}
		// Add JavaScript Variable to Visual Composer
		function TS_VCSC_EditorRenderAction() {
			?>
				<script id="ts-composium-categories-fiter-script" type="text/javascript">
					try {
						var $TS_Composium_Filter				= {};
						$TS_Composium_Filter.vc = {
							// MD5 of the value passed to vc_map as Element Category
							category: 							'<?php echo '.js-category-' . md5(__( "VC Extensions", "ts_visual_composer_extend" )); ?>',
							// Available Sub Categories
							subcategories: 						<?php echo json_encode($this->TS_VCSC_AssignSubCategories()); ?>
						};
					} catch(e) {
						if (console && console.log) console.log(e.message);
					}
				</script>
			<?php
		}
		// Create Individual Filter Buttons
		function TS_VCSC_FilterButtons() {
			$output = '';
			$output .= sprintf('<button class="ts-composium-filter-button vc_ui-button widefat ts-composium-filter-active" data-composium-subcategory="*">%s</button>', __('Show All', 'ts_visual_composer_extend'));
			foreach ($this->TS_VCSC_GetSubCategories() as $value => $label) {
				$output .= sprintf('<button class="ts-composium-filter-button vc_ui-button widefat" data-composium-subcategory="%s">%s</button>', '.' . $value, __($label, 'ts_visual_composer_extend'));
			}
			return $output;
		}		
		// Add Container + Buttons to Visual Composer
		function TS_VCSC_AddElementBoxButtons($output) {	
			$output = sprintf('<div class="ts-composium-filter-container"><div class="ts-composium-filter-selectors" style="display: none;"><div class="ts-composium-filter-controls">%s</div></div><div class="ts-composium-filter-elements" style="min-height: 106px;">%s</div></div>', $this->TS_VCSC_FilterButtons(), $output);
			return $output;
		}
	}
	if (class_exists('TS_VCSC_Composer_SubCategories')) {
		$TS_VCSC_Composer_SubCategories = new TS_VCSC_Composer_SubCategories(array('url' => COMPOSIUM_URL));
	}
?>