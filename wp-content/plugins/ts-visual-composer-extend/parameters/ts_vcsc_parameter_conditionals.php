<?php
    /*
     No Additional Setting Options
    */
    if (!class_exists('TS_Parameter_Conditionals')) {
        class TS_Parameter_Conditionals {
			private $TS_VCSC_Conditionals_Roles;
			private $TS_VCSC_Conditionals_Rights;
			private $TS_VCSC_Conditionals_Tags;
			private $TS_VCSC_Conditionals_Devices;
			
			// Initialize Conditionals Settings Parameter
            function __construct() {
                if (function_exists('vc_add_shortcode_param')) {
					vc_add_shortcode_param('ts_conditionals', array(&$this, 'conditionals_setting_field'));
				} else if (function_exists('add_shortcode_param')) {
                    add_shortcode_param('ts_conditionals', array(&$this, 'conditionals_setting_field'));
				}
            }
			
			// Retrieve User Roles + Capabilities + Tags
			function conditionals_arraydata() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				if ((!empty($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Conditionals_Roles)) && (!empty($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Conditionals_Rights))) {
					$this->TS_VCSC_Conditionals_Roles 	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Conditionals_Roles;
					$this->TS_VCSC_Conditionals_Rights 	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Conditionals_Rights;
				} else {
					global $wp_roles;
					if (!isset($wp_roles)) {
						$wp_roles 						= new WP_Roles();
					}
					$this->TS_VCSC_Conditionals_Roles 	= $wp_roles->get_names();
					$this->TS_VCSC_Conditionals_Rights	= array();
					foreach ($wp_roles->roles as $role) {
						foreach ($role['capabilities'] as $capabilities => $capability) {
							if (!in_array($capabilities, $this->TS_VCSC_Conditionals_Rights)){
								array_push($this->TS_VCSC_Conditionals_Rights, $capabilities);
							}
						}
					}
				}
				$this->TS_VCSC_Conditionals_Tags		= array(
					'is_home',
					'is_front_page',
					'is_singular',
					'is_page',
					'is_single',
					'is_sticky',
					'is_category',
					'is_tax',
					'is_author',
					'is_archive',
					'is_search',
					'is_attachment',
					'is_tag',
					'is_paged',
					'is_main_query',
					'is_feed',
					'is_trackback',
					'is_404',
					'is_preview',
				);
				$this->TS_VCSC_Conditionals_Devices		= array(
					'desktops',
					'mobiles',
					'tablets',					
					'phones',
				);
			}
			
			// Render Conditionals Settings Parameter
            function conditionals_setting_field($settings, $value){
                $param_name     					= isset($settings['param_name']) ? $settings['param_name'] : '';
                $type           					= isset($settings['type']) ? $settings['type'] : '';
                $prefix         					= isset($settings['prefix']) ? $settings['prefix'] : '';
				$connector							= isset($settings['connector']) ? $settings['connector'] : '';
				$output 							= '';
				$random 							= mt_rand (0, 1000000);
                $emptyholder                        = '';
				// Retrieve User Roles + Capabilities + Tags
				$this->conditionals_arraydata();
				// Contingency Checks
				if (!empty($value)) {
					$value							= json_decode(base64_decode($value), true);
				}
				if ((empty($value)) || (!TS_VCSC_CheckIsAssociateArray($value))) {
					$value							= array(
						'viewerstatus'				=> 'everybody',
						'restriction'				=> 'none',						
						'userroles'					=> '',
						'userscope'					=> 'any',
						'usercaps'					=> '',
						'otherscope'				=> 'any',
						'othertags'					=> '',
						'devicetypes'				=> '',
					);
				}
				if (!isset($value['viewerstatus'])) {
					$value['viewerstatus']	        = 'everybody';
				}
				if (!isset($value['restriction'])) {
					$value['restriction']	        = 'none';
				}
				if (!isset($value['userroles'])) {
					$value['userroles']		        = '';
				}
				if (!isset($value['userscope'])) {
					$value['userscope']		        = 'any';
				}
				if (!isset($value['usercaps'])) {
					$value['usercaps']		        = '';
				}
				if (!isset($value['otherscope'])) {
					$value['otherscope']		    = 'any';
				}
				if (!isset($value['othertags'])) {
					$value['othertags']		        = '';
				}
				if (!isset($value['devicetypes'])) {
					$value['devicetypes']	        = '';
				}
				$emptyholder                        = trim($value['userroles']);
                if (!empty($emptyholder)) {
					$value['userroles']				= explode(',', $emptyholder);
				} else {
					$value['userroles']				= array();
				}
                $emptyholder                        = trim($value['othertags']);
				if (!empty($emptyholder)) {
					$value['othertags']				= explode(',', $emptyholder);
				} else {
					$value['othertags']				= array();
				}
                $emptyholder                        = trim($value['devicetypes']);
				if (!empty($emptyholder)) {
					$value['devicetypes']			= explode(',', $emptyholder);
				} else {
					$value['devicetypes']			= array();
				}
				if (count($value['othertags']) > 0) {
					$toggle_othertags 				= "true";
				} else {
					$toggle_othertags 				= "false";
				}
				if (count($value['devicetypes']) > 0) {
					$toggle_devicetypes				= "true";
				} else {
					$toggle_devicetypes				= "false";
				}
				if (($value['restriction'] == 'userroles') && (count($value['userroles']) == 0)) {
					$value['restriction']			= 'none';
				} else if (($value['restriction'] == 'userrights') && (empty($value['usercaps']))) {
					$value['restriction']			= 'none';
				}
				// Render Conditional Parameter
				$output .= '<div id="ts-conditionals-wrapper-' . $random . '" class="ts-conditionals-wrapper ts-settings-parameter-gradient-grey" data-connector="' . $connector . '" data-string-viewerstatus="' . __( "Visibility", "ts_visual_composer_extend" ) . '" data-string-everybody="Everybody" data-string-loggedin="Logged In Only" data-string-external="External Only" data-string-userconditions="' . __( "User Conditions", "ts_visual_composer_extend" ) . '" data-string-none="' . __( "None", "ts_visual_composer_extend" ) . '" data-string-userroles="' . __( "User Role(s)", "ts_visual_composer_extend" ) . '" data-string-userrights="' . __( "User Right(s)", "ts_visual_composer_extend" ) . '" data-string-othertags="' . __( "Tag Conditions", "ts_visual_composer_extend" ) . '" data-string-yes="' . __( "Yes", "ts_visual_composer_extend" ) . '" data-string-no="' . __( "No", "ts_visual_composer_extend" ) . '">';
					// Render Logged-In User
					$output .= '<div class="ts-conditionals-title">' . __( "Visibility Condition", "ts_visual_composer_extend" ) . '</div>';					
					$output .= '<select class="ts-conditionals-viewerstatus">';
						$output .= '<option value="everybody" ' . selected($value['viewerstatus'], "everybody", false) . '>' . __( "Visible To Everybody", "ts_visual_composer_extend" ) . '</option>';
						$output .= '<option value="loggedin" ' . selected($value['viewerstatus'], "loggedin", false) . '>' . __( "Only Logged In Users", "ts_visual_composer_extend" ) . '</option>';
						$output .= '<option value="external" ' . selected($value['viewerstatus'], "external", false) . '>' . __( "Only External Visitors", "ts_visual_composer_extend" ) . '</option>';
					$output .= '</select>';
					$output .= '<div class="ts-conditionals-description vc_description">' . __( "Select if you want to limit the visibility of the contents of this element only to logged in user, external visitors only, or if it should be visble to everybody.", "ts_visual_composer_extend" ) . '</div>';							
					// Render User Restriction					
					$output .= '<div class="ts-conditionals-restrictions" style="display: ' . ($value['viewerstatus'] == "loggedin" ? "block" : "none") . ';">';
						$output .= '<div class="ts-conditionals-title">' . __( "User Conditions", "ts_visual_composer_extend" ) . '</div>';
						$output .= '<select class="ts-conditionals-restriction">';
							$output .= '<option value="none" ' . selected($value['restriction'], "none", false) . '>' . __( "No Other User Condition(s)", "ts_visual_composer_extend" ) . '</option>';
							$output .= '<option value="userroles" ' . selected($value['restriction'], "userroles", false) . '>' . __( "Fulfill Specific User Role(s)", "ts_visual_composer_extend" ) . '</option>';
							$output .= '<option value="userrights" ' . selected($value['restriction'], "userrights", false) . '>' . __( "Fulfill Specific User Right(s)", "ts_visual_composer_extend" ) . '</option>';
						$output .= '</select>';
						$output .= '<div class="ts-conditionals-description vc_description">' . __( "Select if the visiblity of the contents of this element should be limited to any specific user roles or capabilities.", "ts_visual_composer_extend" ) . '</div>';
					$output .= '</div>';
					// User Capabilities
					$output .= '<div class="ts-conditionals-userrights" style="display: ' . ($value['restriction'] == "userrights" ? "block" : "none") . ';">';						
						$output .= '<div class="ts-conditionals-autocomplete" data-autocomplete="' . implode(',', $this->TS_VCSC_Conditionals_Rights) . '" style="display: none;"></div>';						
						$output .= '<div class="ts-conditionals-title">' . __( "User Right(s) (Capabilities)", "ts_visual_composer_extend" ) . '</div>';
						$output .= '<select class="ts-conditionals-userscope" style="margin-bottom: 10px;">';
							$output .= '<option value="any" ' . selected($value['userscope'], "any", false) . '>' . __( "Fulfill At Least One Capability", "ts_visual_composer_extend" ) . '</option>';
							$output .= '<option value="all" ' . selected($value['userscope'], "all", false) . '>' . __( "Fulfill All Capabilities", "ts_visual_composer_extend" ) . '</option>';
						$output .= '</select>';						
						$output .= '<div class="ts-tag-editor-wrapper">';							
							$output .= '<input class="ts-conditionals-usercaps ts-tag-editor-input" type="text" value="' . $value['usercaps'] . '"/>';
							$output .= '<div class="ts-conditionals-description vc_description">' . __( "Enter the specific capabilities a logged in user must have (either at least one or all of them) in order to see the contents of this element when viewing this page or post; separate capabilities with a space or comma character.", "ts_visual_composer_extend" ) . ' ' . __( "Learn more about user capabilities:", "ts_visual_composer_extend" ) . ' ' . '<a href="https://codex.wordpress.org/Roles_and_Capabilities#Capabilities" target="_blank">User Capabilities</a>' . '</div>';
						$output .= '</div>';
					$output .= '</div>';
					// User Roles Selector
					$output .= '<div class="ts-conditionals-userroles" style="display: ' . ($value['restriction'] == "userroles" ? "block" : "none") . ';">';
						$output .= '<div class="ts-conditionals-title">' . __( "User Roles", "ts_visual_composer_extend" ) . '</div>';
						foreach ($this->TS_VCSC_Conditionals_Roles as $role => $name) {
							$output .= '<label class="ts-conditionals-label">';
								$output .= '<input id="ts-conditionals-userrole-' . $role . '" class="ts-conditionals-userrole" type="checkbox" value="' . $role . '" ' . (checked(in_array($role, $value['userroles']), true, false)) . '>';
							$output .= $name . '</label>';
						}					
						$output .= '<div class="ts-conditionals-description vc_description">' . __( "Select any user role(s) a logged in user can have in order to see the contents of this element when viewing this page or post. Learn more about user roles:", "ts_visual_composer_extend" ) . ' ' . '<a href="https://codex.wordpress.org/Roles_and_Capabilities#Roles" target="_blank">User Roles</a>' . '</div>';
					$output .= '</div>';
					// Render Device Type Settings
					$output .= '<div class="ts-conditionals-title">' . __( "Device Conditions", "ts_visual_composer_extend" ) . '</div>';
					$output .= '<div class="ts-conditionals-devicetoggle ts-switch-button ts-codestar-field-switcher" data-value="' . $toggle_devicetypes . '" data-render="string">';
						$output .= '<input type="hidden" style="display: none;" class="ts-codestar-value toggle-input" value="' . $toggle_devicetypes . '" name="ts-conditionals-devicetoggle-input"/>';
						$output .= '<div class="ts-codestar-fieldset">';
							$output .= '<label class="ts-codestar-label">';										
								$output .= '<input value="' . $toggle_devicetypes . '" class="ts-codestar-checkbox" type="checkbox" ' . ((($toggle_devicetypes == "true") || ($toggle_devicetypes == "1")) ? 'checked="checked"' : '') . '> ';
								$output .= '<em data-on="'. __("Yes", "ts_visual_composer_extend") .'" data-off="'. __("No", "ts_visual_composer_extend") .'"></em>';
								$output .= '<span></span>';
							$output .= '</label>';
						$output .= '</div>';
					$output .= '</div>';					
					$output .= '<div class="ts-conditionals-description vc_description">' . __( "Use the toggle if you want to limit the visibility of the elements content to specific device types.", "ts_visual_composer_extend" ) . '</div>';	
					$output .= '<div class="ts-conditionals-devicetypes" style="display: ' . ($toggle_devicetypes == "true" ? "block" : "none") . ';">';
						foreach($this->TS_VCSC_Conditionals_Devices as $condition){
							$output .= '<label class="ts-conditionals-label" style="margin-left: ' . ((($condition == "tablets") || ($condition == "phones")) ? '22' : '0') . 'px;">';
								$output .= '<input class="ts-conditionals-devicetype" type="checkbox" value="' . $condition . '" ' . (checked(in_array($condition, $value['devicetypes']), true, false)) . '>';
							$output .= ucfirst($condition) . '</label>';
						}	
						$output .= '<div class="ts-conditionals-description vc_description">' . __( "Select the device types that must be used in order to see the contents of this element when viewing this page or post; mobile devices automatically include all tablet and phone devices.", "ts_visual_composer_extend" ) . '</div>';	
					$output .= '</div>';
					// Render Conditional Settings
					$output .= '<div class="ts-conditionals-title">' . __( "Other Conditions", "ts_visual_composer_extend" ) . '</div>';
					$output .= '<div class="ts-conditionals-othertoggle ts-switch-button ts-codestar-field-switcher" data-value="' . $toggle_othertags . '" data-render="string">';
						$output .= '<input type="hidden" style="display: none;" class="ts-codestar-value toggle-input" value="' . $toggle_othertags . '" name="ts-conditionals-othertoggle-input"/>';
						$output .= '<div class="ts-codestar-fieldset">';
							$output .= '<label class="ts-codestar-label">';										
								$output .= '<input value="' . $toggle_othertags . '" class="ts-codestar-checkbox" type="checkbox" ' . ((($toggle_othertags == "true") || ($toggle_othertags == "1")) ? 'checked="checked"' : '') . '> ';
								$output .= '<em data-on="'. __("Yes", "ts_visual_composer_extend") .'" data-off="'. __("No", "ts_visual_composer_extend") .'"></em>';
								$output .= '<span></span>';
							$output .= '</label>';
						$output .= '</div>';
					$output .= '</div>';					
					$output .= '<div class="ts-conditionals-description vc_description">' . __( "Use the toggle if you want to apply other (non-user related) conditions to the visibility of the elements content.", "ts_visual_composer_extend" ) . '</div>';					
					$output .= '<div class="ts-conditionals-othertags" style="display: ' . ($toggle_othertags == "true" ? "block" : "none") . ';">';
						$output .= '<select class="ts-conditionals-otherscope" style="margin-bottom: 10px;">';
							$output .= '<option value="any" ' . selected($value['otherscope'], "any", false) . '>' . __( "Fulfill At Least One Condition", "ts_visual_composer_extend" ) . '</option>';
							$output .= '<option value="all" ' . selected($value['otherscope'], "all", false) . '>' . __( "Fulfill All Conditions", "ts_visual_composer_extend" ) . '</option>';
						$output .= '</select>';
						foreach($this->TS_VCSC_Conditionals_Tags as $condition){
							$output .= '<label class="ts-conditionals-label">';
								$output .= '<input class="ts-conditionals-othertag" type="checkbox" value="' . $condition . '" ' . (checked(in_array($condition, $value['othertags']), true, false)) . '>';
							$output .= $condition . '</label>';
						}					
						$output .= '<div class="ts-conditionals-description vc_description">' . __( "Select the conditionals tags that must be fulfilled (either at least one or all of them) in order to see the contents of this element when viewing this page or post. Learn more about conditional tags:", "ts_visual_composer_extend" ) . ' ' . '<a href="https://codex.wordpress.org/Conditional_Tags" target="_blank">Conditional Tags</a>' . '</div>';
					$output .= '</div>';
					// Hidden Input for Data Aggregation
					$value['userroles']				= implode(',', $value['userroles']);
					$value['othertags']				= implode(',', $value['othertags']);
					$value['devicetypes']			= implode(',', $value['devicetypes']);
					$output .= '<textarea id="ts-conditionals-aggregate-' . $random . '" name="' . $settings['param_name'] . '" class="ts-conditionals-aggregate wpb_vc_param_value ' . $param_name . ' ' . $type . '" style="display: none;">' . base64_encode(json_encode($value)) . '</textarea>';
				$output .= '</div>';
				return $output;
            }
        }
    }
    if (class_exists('TS_Parameter_Conditionals')) {
        $TS_Parameter_Conditionals = new TS_Parameter_Conditionals();
    }
?>