<?php
    /*
     No Additional Setting Options
    */
    if (!class_exists('TS_Parameter_CustomPost')) {
        class TS_Parameter_CustomPost {
            function __construct() {
                global $VISUAL_COMPOSER_EXTENSIONS;
                if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_CustomPostTypesCheckup == "true") {
                    if (function_exists('vc_add_shortcode_param')) {
                        vc_add_shortcode_param('custompost',        array(&$this, 'custompost_settings_field'));
                        vc_add_shortcode_param('custompostcat',     array(&$this, 'custompostcat_settings_field'));
                        vc_add_shortcode_param('custompostcatid',   array(&$this, 'custompostcatid_settings_field'));
                    } else if (function_exists('add_shortcode_param')) {                    
                        add_shortcode_param('custompost',           array(&$this, 'custompost_settings_field'));
                        add_shortcode_param('custompostcat',        array(&$this, 'custompostcat_settings_field'));
                        add_shortcode_param('custompostcatid',      array(&$this, 'custompostcatid_settings_field'));
                    }
                }
            }        
            function custompost_settings_field($settings, $value) {
                $param_name     	            = isset($settings['param_name']) ? $settings['param_name'] : '';
                $posttype			            = isset($settings['posttype']) ? $settings['posttype'] : '';
                $posttaxonomy		            = isset($settings['posttaxonomy']) ? $settings['posttaxonomy'] : '';
                $postsingle			            = isset($settings['postsingle']) ? $settings['postsingle'] : '';
                $postplural			            = isset($settings['postplural']) ? $settings['postplural'] : '';
                $postclass			            = isset($settings['postclass']) ? $settings['postclass'] : '';
                $type           	            = isset($settings['type']) ? $settings['type'] : '';
                $method                         = isset($settings['method']) ? $settings['method'] : 'exclude';
                $holder           	            = isset($settings['holder']) ? $settings['holder'] : '';
                $link           	            = isset($settings['link']) ? $settings['link'] : 'false';
                $randomizer                     = mt_rand(999999, 9999999);
                $output         	            = '';
                $posts_fields 		            = array();
                $categories			            = '';
                $category_fields 	            = array();
                $categories_count	            = 0;
                $terms_slugs 		            = array();
                $value_arr 			            = $value;
                if (!is_array($value_arr)) {
                    $value_arr                  = array_map('trim', explode(',', $value_arr));
                }
				// Text Strings
				if ($method == "exclude") {
					$string_selectable 			= __( "Included Categories:", "ts_visual_composer_extend" );
					$string_selections			= __( "Filtered By:", "ts_visual_composer_extend" );
				} else if ($method == "include") {
					$string_selectable 			= __( "Filtered By:", "ts_visual_composer_extend" );
					$string_selections			= __( "Included Categories:", "ts_visual_composer_extend" );
				} else {
					$string_selectable			= "";
					$string_selections			= "";
				}
                if (!empty($settings['posttype']) ) {
                    $args = array(
                        'no_found_rows' 		=> 1,
                        'ignore_sticky_posts' 	=> 1,
                        'posts_per_page' 		=> -1,
                        'post_type' 			=> $posttype,
                        'post_status' 			=> 'publish',
                        'orderby' 				=> 'title',
                        'order' 				=> 'ASC',
                    );
                    $custompost_nocategory			= 0;
                    $custompost_query = new WP_Query($args);
                    if ($custompost_query->have_posts()) {
                        foreach($custompost_query->posts as $p) {
                            $categories = TS_VCSC_GetTheCategoryByTax($p->ID, $posttaxonomy);
                            if ($categories && !is_wp_error($categories)) {
                                $category_slugs_arr = array();
                                foreach ($categories as $category) {
                                    $category_slugs_arr[] = $category->slug;
                                    $category_data = array(
                                        'slug'		=> $category->slug,
                                        'name'		=> $category->cat_name,
                                        'count'		=> $category->count,
                                    );
                                    $category_fields[] = $category_data;
                                }
                                $categories_slug_str = join(",", $category_slugs_arr);
                            } else {
                                $custompost_nocategory++;
                                $categories_slug_str = '';
                            };
                            $posts_fields[] = sprintf(
                                '<option id="%s" class="%s" name="%s" value="%s" data-filter="false" data-id="%s" data-categories="%s" %s>%s (ID: %s)</option>',
                                $settings['param_name'] . '-' . $p->ID,
                                $settings['param_name'] . ' ' . $type,
                                $settings['param_name'] . '-' . $p->ID,
                                $p->ID,
                                $p->post_title,
                                $categories_slug_str,
                                selected(in_array($p->ID, $value_arr), true, false),
                                $p->post_title,
                                $p->ID
                            );
                        }
                    }
                    wp_reset_postdata();
                }
                $category_fields    = array_map("unserialize", array_unique(array_map("serialize", $category_fields)));
                $output .= '<div id="ts-custompost-selector-wrapper-' . $randomizer . '" class="ts-custompost-selector-wrapper ts-settings-parameter-gradient-grey">';
                    $output .= '<div class="ts-custompost-selector-parent" data-selectable="' . $string_selectable . '" data-selection="' . $string_selections . '">';
                        if (count($category_fields) > 1) {
                            // Toggle Switch					
							$output .= '<div id="ts-custompost-selector-switcheroo-' . $randomizer . '" class="ts-custompost-selector-switcheroo" style="display: block; width: 100%; float: left;">';
								$output .= '<div class="ts-custompost-selector-label">' . __( "Filter Controls", "ts_visual_composer_extend" ) . '</div>';
								$output .= '<div id="ts-custompost-filter-switch-' . $randomizer . '" class="ts-switch-button ts-codestar-field-switcher" data-value="false">';
									$output .= '<input id="ts-custompost-filter-switch-value-' . $randomizer . '" class="toggle-input ts-custompost-filter-switch-value" type="hidden" value="false" name="ts-custompost-filter-switch-value" style="display: none;"/>';
									$output .= '<div class="ts-codestar-fieldset">';
										$output .= '<label class="ts-codestar-label">';										
											$output .= '<input id="ts-custompost-filter-switch-checkbox-' . $randomizer . '" class="ts-custompost-filter-switch-checkbox" type="checkbox" value="false"> ';
											$output .= '<em data-on="'. __( "Yes", "ts_visual_composer_extend" ) . '" data-off="'. __( "No", "ts_visual_composer_extend" ) . '"></em>';
											$output .= '<span></span>';
										$output .= '</label>';
									$output .= '</div>';
								$output .= '</div>';
							$output .= '</div>';							
                            $output .= '<div class="ts-custompost-selector-message" style="margin-bottom: 20px;">' . __( "Switch the toggle if you want to show controls to filter available post types by categories.", "ts_visual_composer_extend" ) . '</div>';
                            // Categories Filter
                            $output .= '<div class="ts-custom-post-filter-frame" style="display: none; margin-bottom: 20px;" data-selectable="' . $string_selectable . '" data-selection="' . $string_selections . '">';
                                $output .= '<span class="ts-custompost-selector-label">' . __( "Filter by Category:", "ts_visual_composer_extend" ) . '</span>';
                                $output .= '<select multiple="multiple" id="' . $param_name . '_filter" data-selector="' . $param_name . '" class="ts-' . $postclass . '-selector-filter ts-custompost-selector-filter">';
                                    if ($custompost_nocategory > 0) {
                                        $output .= '<option id="" class="" name="" data-id="" data-author="" data-category="ts-custompost-none-applied" value="ts-custompost-none-applied">' . __( "No Category", "ts_visual_composer_extend" ) . ' (' . $custompost_nocategory . ')</option>';
                                    }
                                    foreach ($category_fields as $index => $array) {
                                        $output .= '<option id="" class="" name="" data-id="" data-author="" data-category="' . $category_fields[$index]['slug'] . '" value="' . $category_fields[$index]['slug'] . '">' . $category_fields[$index]['name'] . ' (' . $category_fields[$index]['count'] . ')</option>';
                                    }
                                $output .= '</select>';
                                $output .= '<span class="ts-custompost-selector-message">' . __( "Click on 'Available Categories' to filter by category; click on 'Filtered By' to remove from filter.", "ts_visual_composer_extend" ) . '</span>';
                            $output .= '</div>';
                        }
                        $output .= '<select name="ts-custompost-selector-mirror" id="ts-custompost-selector-mirror" class="ts-custompost-selector-mirror dropdown" value="" style="display: none !important;">';
                            $output .= implode( $posts_fields );
                        $output .= '</select>';                        
                        $output .= '<span class="ts-custompost-selector-label">' . __( "Select", "ts_visual_composer_extend" ) . ' ' . $postsingle . ':</span>';
                        $output .= '<select name="' . $param_name . '" id="' . $param_name . '" class="ts-' . $postclass . '-selector ts-custompost-selector wpb-input wpb-select dropdown wpb_vc_param_value ' . $param_name . ' ' . $type . '" value=" ' . $value . '" style="">';
                            $output .= '<option id="" class="placeholder" name="" value="" data-filter="false" data-id="" data-categories="">' . __( "Select", "ts_visual_composer_extend" ) . ' ' . $postsingle . '</option>';
                            $output .= implode( $posts_fields );
                        $output .= '</select>';
                    $output .= '</div>';
                 $output .= '</div>';
                return $output;
            }
            function custompostcat_settings_field($settings, $value) {
                global $VISUAL_COMPOSER_EXTENSIONS;
                $param_name     	                = isset($settings['param_name']) ? $settings['param_name'] : '';
                $posttype			                = isset($settings['posttype']) ? $settings['posttype'] : '';
                $posttaxonomy		                = isset($settings['posttaxonomy']) ? $settings['posttaxonomy'] : '';
                $postsingle			                = isset($settings['postsingle']) ? $settings['postsingle'] : '';
                $postplural			                = isset($settings['postplural']) ? $settings['postplural'] : '';
                $postclass			                = isset($settings['postclass']) ? $settings['postclass'] : '';
				$postslugs							= isset($settings['postslugs']) ? $settings['postslugs'] : 'true';
				$postempty							= isset($settings['postempty']) ? $settings['postempty'] : 'true';
				$postsource							= isset($settings['postsource']) ? $settings['postsource'] : 'cats';
				$postactive							= isset($settings['postactive']) ? $settings['postactive'] : 'false';
                $type           	                = isset($settings['type']) ? $settings['type'] : '';
                $method                             = isset($settings['method']) ? $settings['method'] : 'exclude';
                $holder           	                = isset($settings['holder']) ? $settings['holder'] : '';
                $output         	                = '';
                $randomizer                         = mt_rand(999999, 9999999);
				// Check if Posttype Active
				if ($postactive == "true") {
					if (($posttype == "ts_timeline") && (($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_CustomPostTypesTimeline == "false"))) {
						$output .= '<div id="ts-hidden-input-wrapper-' . $randomizer . '" class="ts-hidden-input-wrapper" data-name="' . $param_name . '" style="display: none;">';
							$output .= '<input name="' . $param_name . '" id="' . $param_name . '" class="wpb_vc_param_value ts_shortcode_hidden ' . $param_name . ' ' . $type . '" type="hidden" value="' . $value . '"/>';
						$output .= '</div>';
						$output .= '<div class="ts-vcsc-notice-field ts-vcsc-warning" style="font-size: 13px; text-align: justify;">';
							$output .= 'The "VC Timelines" post type is currently deactivated; therefore, any existing ' . ($postclass == "cats" ? "categories" : "tags") . ' that might have been used with the "VC Timelines" post type are unavailable.';
						$output .= '</div>';						
						return $output;
					}
				}
				// Other Variables
                $posts_fields 		                = array();
                $categories			                = '';
                $category_fields 	                = array();
                $categories_count	                = 0;
                $terms_slugs 		                = array();
                $value_arr 			                = $value;
                if (!is_array($value_arr)) {
                    $value_arr                      = array_map('trim', explode(',', $value_arr));
                }
				// Text Strings
				if ($method == "exclude") {
					if ($postsource == "cats") {
						$string_selectable			= __( "Available Categories:", "ts_visual_composer_extend" );
						$string_selections			= __( "Applied Categories:", "ts_visual_composer_extend" );
					} else {
						$string_selectable			= __( "Available Tags:", "ts_visual_composer_extend" );
						$string_selections			= __( "Applied Tags:", "ts_visual_composer_extend" );
					}
				} else if ($method == "include") {
					if ($postsource == "cats") {
						$string_selectable			= __( "Applied Categories:", "ts_visual_composer_extend" );
						$string_selections			= __( "Available Categories:", "ts_visual_composer_extend" );
					} else {
						$string_selectable			= __( "Applied Tags:", "ts_visual_composer_extend" );
						$string_selections			= __( "Available Tags:", "ts_visual_composer_extend" );
					}
				} else {
					$string_selectable			    = "";
					$string_selections			    = "";
				}
                if (!empty($settings['posttype']) ) {
                    $args = array(
                        'no_found_rows' 			=> 1,
                        'ignore_sticky_posts' 		=> 1,
                        'posts_per_page' 			=> -1,
                        'post_type' 				=> $posttype,
                        'post_status' 				=> 'publish',
                        'orderby' 					=> 'title',
                        'order' 					=> 'ASC',
                    );
                    $custompost_nocategory_count	= 0;
                    $custompost_nocategory_name		= 'ts-' . $postclass . '-none-applied';
                    $custompost_query               = new WP_Query($args);
                    if ($custompost_query->have_posts()) {
                        foreach($custompost_query->posts as $p) {
							$categories 			= TS_VCSC_GetTheCategoryByTax($p->ID, $posttaxonomy);
							if ($categories && !is_wp_error($categories)) {
								$category_slugs_arr = array();
								foreach ($categories as $category) {
									$category_slugs_arr[] = $category->slug;
									$category_data = array(
										'slug'		=> $category->slug,
										'name'		=> $category->cat_name,
										'count'		=> $category->count,
									);
									$category_fields[] = $category_data;
								}
								$categories_slug_str = join(",", $category_slugs_arr);
							} else {
								$custompost_nocategory_count++;
							}
                        }
                    }
                    wp_reset_postdata();
                }
                $category_fields 					= array_map("unserialize", array_unique(array_map("serialize", $category_fields)));
                $output .= '<div id="ts-custompost-categories-wrapper-' . $randomizer . '" class="ts-custompost-categories-wrapper ts-settings-parameter-gradient-grey">';
                    $output .= '<div class="ts-custompost-categories-holder">';
                        $output .= '<textarea name="' . $param_name . '" id="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" style="display: none;">' . $value . '</textarea >';
                        $output .= '<select multiple="multiple" name="' . $param_name . '_multiple" id="' . $param_name . '_multiple" data-holder="' . $param_name . '" class="ts-custompost-categories-selector wpb-input wpb-select dropdown ' . $param_name . '_multiple" value=" ' . $value . '" style="margin-bottom: 20px;" data-selectable="' . $string_selectable . '" data-selection="' . $string_selections . '">';
                            if (($custompost_nocategory_count > 0) && ($postempty == "true")) {
                                $output .= '<option id="" class="" name="" data-id="" data-author="" value="ts-' . $postclass . '-none-applied" ' . selected(in_array($custompost_nocategory_name, $value_arr), true, false) . '>' . (($postsource == "cats") ? __( "No Category", "ts_visual_composer_extend" ) : __( "No Tag", "ts_visual_composer_extend" )) . ' (' . $custompost_nocategory_count . ')</option>';
                            }
                            foreach ($category_fields as $index => $array) {
								if ($postslugs == "true") {
									$output .= '<option id="" class="" name="" data-id="" data-author="" value="' . $category_fields[$index]['slug'] . '" ' . selected(in_array($category_fields[$index]['slug'], $value_arr), true, false) . '>' . $category_fields[$index]['name'] . ' (' . $category_fields[$index]['count'] . ')</option>';
								} else {
									$output .= '<option id="" class="" name="" data-id="" data-author="" value="' . $category_fields[$index]['name'] . '" ' . selected(in_array($category_fields[$index]['name'], $value_arr), true, false) . '>' . $category_fields[$index]['name'] . ' (' . $category_fields[$index]['count'] . ')</option>';
								}                                
                            }
                        $output .= '</select>';
                        if ($method == "exclude") {
							if ($postsource == "cats") {
								$output .= '<span class="ts-custompost-selector-message">' . __( "Click on a name in 'Available Categories' to add category to element; click on a name in 'Applied Categories' to remove from element.", "ts_visual_composer_extend" ) . '</span>';
							} else {
								$output .= '<span class="ts-custompost-selector-message">' . __( "Click on a name in 'Available Tags' to add category to element; click on a name in 'Applied Tags' to remove from element.", "ts_visual_composer_extend" ) . '</span>';
							}                            
                        } else if ($method == "include") {
							if ($postsource == "cats") {
								$output .= '<span class="ts-custompost-selector-message">' . __( "Click on a name in 'Applied Categories' to remove category from element; click on a name in 'Available Categories' to add to element.", "ts_visual_composer_extend" ) . '</span>';
							} else {
								$output .= '<span class="ts-custompost-selector-message">' . __( "Click on a name in 'Applied Tags' to remove category from element; click on a name in 'Available Tags' to add to element.", "ts_visual_composer_extend" ) . '</span>';
							}
                        }
                    $output .= '</div>';
                $output .= '</div>';
                return $output;
            }
            function custompostcatid_settings_field($settings, $value) {
                $param_name     	            = isset($settings['param_name']) ? $settings['param_name'] : '';
                $posttype			            = isset($settings['posttype']) ? $settings['posttype'] : '';
                $posttaxonomy		            = isset($settings['posttaxonomy']) ? $settings['posttaxonomy'] : '';
                $postsingle			            = isset($settings['postsingle']) ? $settings['postsingle'] : '';
                $postplural			            = isset($settings['postplural']) ? $settings['postplural'] : '';
                $postclass			            = isset($settings['postclass']) ? $settings['postclass'] : '';
                $postmulti			            = isset($settings['postmulti']) ? $settings['postmulti'] : 'true';
                $postempty			            = isset($settings['postempty']) ? $settings['postempty'] : 'false';
                $type           	            = isset($settings['type']) ? $settings['type'] : '';
                $method                         = isset($settings['method']) ? $settings['method'] : 'exclude';
                $holder           	            = isset($settings['holder']) ? $settings['holder'] : '';
                $output         	            = '';
                $randomizer                     = mt_rand(999999, 9999999);
                $posts_fields 		            = array();
                $categories			            = '';
                $category_fields 	            = array();
                $categories_count	            = 0;
                $terms_slugs 		            = array();
                $value_arr 			            = $value;
                if (!is_array($value_arr)) {
                    $value_arr                  = array_map('trim', explode(',', $value_arr));
                }
				// Text Strings
				if ($method == "exclude") {
					$string_selectable 			= __( "Available Categories:", "ts_visual_composer_extend" );
					$string_selections			= __( "Applied Categories:", "ts_visual_composer_extend" );
				} else if ($method == "include") {
					$string_selectable 			= __( "Applied Categories:", "ts_visual_composer_extend" );
					$string_selections			= __( "Available Categories:", "ts_visual_composer_extend" );
				} else {
					$string_selectable			= "";
					$string_selections			= "";
				}
                if (!empty($posttaxonomy)) {
                    if ($postempty == "true") {
                        $terms                  = get_terms($posttaxonomy,'order_by=name&hide_empty=0&show_count=1');
                    } else {
                        $terms                  = get_terms($posttaxonomy,'order_by=name&hide_empty=1&show_count=1');
                    }
                    if (!empty($terms) && !is_wp_error($terms)){
                        foreach ($terms as $term) {
                            $category_data = array(
                                'id'		    => $term->term_id,
                                'slug'		    => $term->slug,
                                'name'		    => $term->name,
                                'count'		    => $term->count,
                            );
                            $category_fields[]  = $category_data;
                        }
                    }
                    $category_fields = array_map("unserialize", array_unique(array_map("serialize", $category_fields)));
                    $output .= '<div id="ts-custompost-categories-wrapper-' . $randomizer . '" class="ts-custompost-categories-wrapper ts-settings-parameter-gradient-grey">';
                        if ($postmulti == "true") {                        
                            $output .= '<div class="ts-custompost-categories-holder">';
                                $output .= '<textarea name="' . $param_name . '" id="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" style="display: none;">' . $value . '</textarea >';
                                $output .= '<select multiple="multiple" name="' . $param_name . '_multiple" id="' . $param_name . '_multiple" data-holder="' . $param_name . '" class="ts-custompost-categories-selector wpb-input wpb-select dropdown ' . $param_name . '_multiple" value=" ' . $value . '" style="margin-bottom: 20px;" data-selectable="' . $string_selectable . '" data-selection="' . $string_selections . '">';
                                    foreach ($category_fields as $index => $array) {
                                        $output .= '<option id="" class="" name="" data-id="" data-author="" value="' . $category_fields[$index]['id'] . '" ' . selected(in_array($category_fields[$index]['id'], $value_arr), true, false) . '>' . $category_fields[$index]['name'] . ' (' . $category_fields[$index]['count'] . ')</option>';
                                    }
                                $output .= '</select>';
                                if ($method == "exclude") {
                                    $output .= '<span class="ts-custompost-selector-message">' . __( "Click on a name in 'Available Categories' to add category to element; click on a name in 'Applied Categories' to remove from element.", "ts_visual_composer_extend" ) . '</span>';
                                } else {
                                    $output .= '<span class="ts-custompost-selector-message">' . __( "Click on a name in 'Applied Categories' to remove category from element; click on a name in 'Available Categories' to add to element.", "ts_visual_composer_extend" ) . '</span>';
                                }
                            $output .= '</div>';
                        } else {
                            $output .= '<div class="ts-custompost-categories-holder">';
                                $output .= '<select name="' . $param_name . '" id="' . $param_name . '" data-holder="' . $param_name . '" class="ts-custompostid-categories-selector wpb-input wpb-select dropdown wpb_vc_param_value ' . $param_name . ' ' . $type . '" value=" ' . $value . '" style="">';
                                    foreach ($category_fields as $index => $array) {
                                        $output .= '<option id="" class="" name="" data-id="" data-author="" value="' . $category_fields[$index]['id'] . '" ' . selected(in_array($category_fields[$index]['id'], $value_arr), true, false) . '>' . $category_fields[$index]['name'] . ' (' . $category_fields[$index]['count'] . ')</option>';
                                    }
                                $output .= '</select>';
                            $output .= '</div>';
                        }
                    $output .= '</div>';
                }
                return $output;
            }
        }
    }
    if (class_exists('TS_Parameter_CustomPost')) {
        $TS_Parameter_CustomPost = new TS_Parameter_CustomPost();
    }
?>