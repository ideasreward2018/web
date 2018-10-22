<?php
    /*
     No Additional Setting Options
    */
    if (!class_exists('TS_Parameter_StandardPost')) {
        class TS_Parameter_StandardPost {
            function __construct() {	
                if (function_exists('vc_add_shortcode_param')) {
                    vc_add_shortcode_param('standardpostcat', 		array(&$this, 'standardpostcat_settings_field'));
					vc_add_shortcode_param('standardpoststati',		array(&$this, 'standardpoststati_settings_field'));
				} else if (function_exists('add_shortcode_param')) {
                    add_shortcode_param('standardpostcat', 			array(&$this, 'standardpostcat_settings_field'));
					add_shortcode_param('standardpoststati',		array(&$this, 'standardpoststati_settings_field'));
				}
            }        
            function standardpostcat_settings_field($settings, $value) {
                $param_name     				= isset($settings['param_name']) ? $settings['param_name'] : '';
                $posttype						= isset($settings['posttype']) ? $settings['posttype'] : '';
                $posttaxonomy					= isset($settings['posttaxonomy']) ? $settings['posttaxonomy'] : '';
                $postsingle						= isset($settings['postsingle']) ? $settings['postsingle'] : '';
                $postplural						= isset($settings['postplural']) ? $settings['postplural'] : '';
                $postclass						= isset($settings['postclass']) ? $settings['postclass'] : '';
                $type           				= isset($settings['type']) ? $settings['type'] : '';
				$method							= isset($settings['method']) ? $settings['method'] : 'exclude';
				$randomizer						= mt_rand(999999, 9999999);
                $output         				= '';
                $posts_fields 					= array();
                $categories						= '';
                $category_fields 				= array();
                $categories_count				= 0;
                $terms_slugs 					= array();
                $value_arr 						= $value;
                if (!is_array($value_arr)) {
                    $value_arr 					= array_map('trim', explode(',', $value_arr));
                }
				// Text Strings
				if ($method == "exclude") {
					$string_selectable 			= __( "Included Categories:", "ts_visual_composer_extend" );
					$string_selections			= __( "Excluded Categories:", "ts_visual_composer_extend" );
				} else if ($method == "include") {
					$string_selectable 			= __( "Excluded Categories:", "ts_visual_composer_extend" );
					$string_selections			= __( "Included Categories:", "ts_visual_composer_extend" );
				} else {
					$string_selectable			= "";
					$string_selections			= "";
				}
                // Categories for Standard Post Type
                $args = array(
                    'type'                     => 'post',
                    'child_of'                 => 0,
                    'parent'                   => '',
                    'orderby'                  => 'name',
                    'order'                    => 'ASC',
                    'hide_empty'               => 1,
                    'hierarchical'             => 1,
                    'exclude'                  => '',
                    'include'                  => '',
                    'number'                   => '',
                    'taxonomy'                 => 'category',
                    'pad_counts'               => false 
                );
                $categories 					= get_categories($args);
                $output .= '<div id="ts-standardpost-categories-holder-' . $randomizer . '" class="ts-standardpost-categories-holder ts-settings-parameter-gradient-grey ts-standardpost-categories-' . $method .'">';
                    $output .= '<textarea name="' . $param_name . '" id="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" style="display: none;">' . $value . '</textarea >';
                    $output .= '<select multiple="multiple" name="' . $param_name . '_multiple" id="' . $param_name . '_multiple" data-holder="' . $param_name . '" class="ts-standardpost-categories-selector wpb-input wpb-select dropdown ' . $param_name . '_multiple" value=" ' . $value . '" style="margin-bottom: 20px;" data-method="' . $method . '" data-selectable="' . $string_selectable . '" data-selection="' . $string_selections . '">';
                        foreach($categories as $category) { 
                            $output .= '<option id="" class="" name="" data-id="" data-author="" value="' . $category->slug . '" ' . selected(in_array($category->slug, $value_arr), true, false) . '>' . $category->name . ' (' . $category->count . ')</option>';
                        }
                    $output .= '</select>';
					if ($method == "exclude") {
						$output .= '<span class="ts-standardpost-categories-message">' . __( "Click on 'Included Categories' to exclude that category; click on 'Excluded Categories' to include a category again.", "ts_visual_composer_extend" ) . '</span>';
					} else if ($method == "include") {
						$output .= '<span class="ts-standardpost-categories-message">' . __( "Click on 'Excluded Categories' to include that category; click on 'Included Categories' to exclude a category again.", "ts_visual_composer_extend" ) . '</span>';
					}
				$output .= '</div>';
                return $output;
            }
			function standardpoststati_settings_field($settings, $value) {
                $param_name     				= isset($settings['param_name']) ? $settings['param_name'] : '';
                $posttype						= isset($settings['posttype']) ? $settings['posttype'] : '';		
                $type           				= isset($settings['type']) ? $settings['type'] : '';
				$method							= isset($settings['method']) ? $settings['method'] : 'include';
				$randomizer						= mt_rand(999999, 9999999);
                $output         				= '';
				$poststati						= get_post_stati(array(), 'names', 'and');
                $value_arr 						= $value;
                if (!is_array($value_arr)) {
                    $value_arr 					= array_map('trim', explode(',', $value_arr));
                }
				// Text Strings
				if ($method == "exclude") {
					$string_selectable 			= __( "Included Stati:", "ts_visual_composer_extend" );
					$string_selections			= __( "Excluded Stati:", "ts_visual_composer_extend" );
				} else if ($method == "include") {
					$string_selectable 			= __( "Excluded Stati:", "ts_visual_composer_extend" );
					$string_selections			= __( "Included Stati:", "ts_visual_composer_extend" );
				} else {
					$string_selectable			= "";
					$string_selections			= "";
				}
                $output .= '<div id="ts-standardpost-stati-holder-' . $randomizer . '" class="ts-standardpost-stati-holder ts-standardpost-stati-' . $method . ' ts-settings-parameter-gradient-grey">';
                    $output .= '<textarea name="' . $param_name . '" id="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" style="display: none;">' . $value . '</textarea >';
                    $output .= '<select multiple="multiple" name="' . $param_name . '_multiple" id="' . $param_name . '_multiple" data-holder="' . $param_name . '" class="ts-standardpost-stati-selector wpb-input wpb-select dropdown ' . $param_name . '_multiple" value=" ' . $value . '" style="margin-bottom: 20px;" data-method="' . $method . '" data-selectable="' . $string_selectable . '" data-selection="' . $string_selections . '">';
                        foreach($poststati as $status) {
							if ($status != "publish") {
								$output .= '<option id="" class="" name="" data-id="" data-author="" value="' . $status . '" ' . selected(in_array($status, $value_arr), true, false) . '>' . ucfirst($status) . '</option>';
							}
						}
                    $output .= '</select>';
					if ($method == "exclude") {
						$output .= '<span class="ts-standardpost-stati-message">' . __( "Click on 'Included Stati' to exclude that status; click on 'Excluded Stati' to include a status again.", "ts_visual_composer_extend" ) . '</span>';
					} else if ($method == "include") {
						$output .= '<span class="ts-standardpost-stati-message">' . __( "Click on 'Excluded Stati' to include that status; click on 'Included Stati' to exclude a status again.", "ts_visual_composer_extend" ) . '</span>';
					}
				$output .= '</div>';
                return $output;
			}
        }
    }
    if (class_exists('TS_Parameter_StandardPost')) {
        $TS_Parameter_StandardPost = new TS_Parameter_StandardPost();
    }
?>