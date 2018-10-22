<?php
	if (!class_exists('TS_Postsimage')){
		class TS_Postsimage {
			function __construct() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						$this->TS_VCSC_Add_Posts_Image_Lean();
					} else if (function_exists('vc_map')) {
						add_action('init',                                  array($this, 'TS_VCSC_Add_Posts_Image_Elements'), 9999999);
					}
				} else {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_Posts_Image_Lean'), 9999999);
					} else if (function_exists('vc_map')) {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_Posts_Image_Elements'), 9999999);
					}
				}
				if ((is_admin() == false) || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAJAX == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAlways == "true")) {
					add_shortcode('TS_VCSC_Posts_Image_Grid_Standalone',	array($this, 'TS_VCSC_Posts_Image_Grid_Standalone'));
				}
			}
			
			// Register Element(s) via LeanMap
			function TS_VCSC_Add_Posts_Image_Lean() {
				vc_lean_map('TS_VCSC_Posts_Image_Grid_Standalone',			array($this, 'TS_VCSC_Add_Posts_Image_Elements'), null);
			}
			
			// Image Posts Grid
			function TS_VCSC_Posts_Image_Grid_Standalone ($atts, $content = null) {
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();

				// Load Required Files
				wp_enqueue_script('ts-extend-krautlightbox');
				wp_enqueue_style('ts-extend-krautlightbox');			
				wp_enqueue_style('ts-font-ecommerce');
				wp_enqueue_style('ts-extend-animations');
				wp_enqueue_style('ts-visual-composer-extend-front');
				wp_enqueue_script('ts-visual-composer-extend-front');
				
				extract( shortcode_atts( array(
					'post_type'						=> 'post',
					'post_custom'					=> 'false',
					'post_taxos'					=> '',
					'post_status'					=> 'publish',
					'limit_posts'					=> 'false',								// false, true, include					
					'limit_by'						=> 'category',							// post_tag, cust_tax
					'limit_term'					=> '',
					'limit_include'					=> '',
					'filter_by'						=> 'category', 							// post_tag, cust_tax
					'posts_limit'					=> 25,
					
					'content_images'				=> '',
					'content_images_titles'			=> '',
					'content_images_links'			=> '',
					'content_images_groups'			=> '',
					'content_images_size'			=> 'medium',
					
					'filters_show'					=> 'true',
					'filters_available'				=> 'Available Groups',
					'filters_selected'				=> 'Filtered Groups',
					'filters_nogroups'				=> 'No Groups',
					'filters_toggle'				=> 'Toggle Filter',
					'filters_toggle_style'			=> '',
					'filters_showall'				=> 'Show All',
					'filters_showall_style'			=> '',
				
					'data_grid_machine'				=> 'internal',
					'data_grid_invalid'				=> 'false',
					'data_grid_target'				=> '_blank',
					'data_grid_breaks'				=> '240,480,720,960',
					'data_grid_width'				=> 250,
					'data_grid_space'				=> 2,
					'data_grid_order'				=> 'false',
					'data_grid_always'				=> 'true',
					
					'fullwidth'						=> 'false',
					'breakouts'						=> 6,
					
					'margin_top'					=> 0,
					'margin_bottom'					=> 0,
					'el_id' 						=> '',
					'el_class'              		=> '',
					'css'							=> '',
				), $atts ));
	
				$output								= '';
				
				// Turn Photon Off (For Correct Image Information)
				$photon_removed 					= ''; 
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_JetpackPhoton_Active == "true") {
					$photon_removed 				= remove_filter('image_downsize', array(Jetpack_Photon::instance(), 'filter_image_downsize'));
				}
				
				// Check for Front End Editor
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					$grid_class						= 'ts-image-link-grid-edit';
					$grid_message					= '<div class="ts-composer-frontedit-message">' . __( 'The grid is currently viewed in front-end edit mode; grid and filter features are disabled for performance and compatibility reasons.', "ts_visual_composer_extend" ) . '</div>';
					$image_style					= 'width: 20%; height: 100%; display: inline-block; margin: 0; padding: 0;';
					$grid_style						= 'height: 100%;';
					$frontend_edit					= 'true';
				} else {
					if ($data_grid_machine == 'internal') {
						$grid_class					= 'ts-image-link-grid';
					} else if ($data_grid_machine == 'freewall') {
						$grid_class					= 'ts-freewall-link-grid';
					}
					$image_style					= '';
					$grid_style						= '';
					$grid_message					= '';
					$frontend_edit					= 'false';
				}
	
				$randomizer							= mt_rand(999999, 9999999);
			
				if (!empty($el_id)) {
					$modal_id						= $el_id;
				} else {
					$modal_id						= 'ts-vcsc-posts-link-grid-' . $randomizer;
				}
					
				$valid_images 						= 0;
				if (!empty($data_grid_breaks)) {
					$data_grid_breaks 				= str_replace(' ', '', $data_grid_breaks);
					$count_columns					= substr_count($data_grid_breaks, ",") + 1;
				} else {
					$count_columns					= 0;
				}
				$i 									= -1;
				$b									= 0;
				$output 							= '';
				
				if ($filters_toggle_style != '') {
					wp_enqueue_style('ts-extend-buttonsflat');
				}
				wp_enqueue_style('ts-extend-multiselect');
				wp_enqueue_script('ts-extend-multiselect');
				
				// Contingency Check for Custom Post Types
				if ($post_type != 'post') {
					$limit_posts					= 'false';
					$limit_term						= '';
					$limit_include					= '';
					$limit_by						= 'cust_tax';
					$filter_by						= 'cust_tax';
				}
				
				$limit_term 						= str_replace(' ', '', $limit_term);
				$limit_include 						= str_replace(' ', '', $limit_include);
				if (($limit_posts == 'include') && ($limit_include == "")) {
					$limit_posts					= "false";
				}	
				if ($limit_by == 'category') {
					$limit_tax 						= 'category';
				} else if ($limit_by == 'post_tag') {
					$limit_tax 						= 'post_tag';
				} else if ($limit_by == 'cust_tax') {
					$limit_tax 						= $post_taxos;
				}
				
				// Set the taxonomy for the filter menu
				if ($filter_by == 'category') {
					$menu_tax 						= 'category';
				} else if ($filter_by == 'post_tag') {
					$menu_tax 						= 'post_tag';
				} else if ($filter_by == 'cust_tax') {
					$menu_tax 						= $post_taxos; 
				}
	
				// Set the WP Query Arguments
				if ($post_custom == "false") {
					$post_status					= array('publish');
				} else {
					$post_status					= explode(",", $post_status);
					array_unshift($post_status, "publish");
				}
				$args = array(
					'post_type' 					=> $post_type,
					'posts_per_page' 				=> $posts_limit + 1,
					'post_status' 					=> $post_status,
				);
				if ((($limit_posts == 'true') || ($limit_posts == 'include')) && (taxonomy_exists($limit_tax))) {
					if ($limit_posts == "true") {
						$limited_terms 				= explode(',', $limit_term);
						$limited_operator			= 'NOT IN';
					} else if ($limit_posts == "include") {
						$limited_terms 				= explode(',', $limit_include);
						$limited_operator			= 'IN';
					} else {
						$limited_terms				= array();
						$limited_operator			= '';
					}					
					$args['tax_query'] = array(
						array (
							'taxonomy' 				=> $limit_tax,
							'field' 				=> 'slug',
							'terms' 				=> $limited_terms,
							'operator' 				=> $limited_operator
						)
					);
				}
				$isoposts 							= new WP_Query($args);
				
				if ($data_grid_machine == 'internal') {
					$class_name						= 'ts-image-link-grid-frame';
				} else if ($data_grid_machine == 'freewall') {
					wp_enqueue_script('ts-extend-freewall');
					$class_name						= 'ts-image-freewall-grid-frame';
				}
				
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_name . ' ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Posts_Image_Grid_Standalone', $atts);
				} else {
					$css_class 						= $class_name . ' ' . $el_class;
				}
	
				$fullwidth_allow					= "true";
				$postCounter 						= 0;
				$modal_gallery						= '';
				
				// Front-Edit Message
				if ($frontend_edit == "true") {
					$modal_gallery .= $grid_message;
					if (post_type_exists($post_type) && $isoposts->have_posts()) { 
						while ($isoposts->have_posts() ) :
							$isoposts->the_post();
							$matched_terms						= 0;
							$post_thumbnail						= get_the_post_thumbnail();
							if (($matched_terms == 0) && (($post_thumbnail != '') || ($data_grid_invalid == "false"))) {
								$postCounter++;
								if ($postCounter < $posts_limit + 1) {
									if ('' != $post_thumbnail) {		
										$grid_image				= wp_get_attachment_image_src(get_post_thumbnail_id(), $content_images_size);
										$modal_image			= wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
										$grid_image				= $grid_image[0];
										$modal_image			= $modal_image[0];
									} else {
										$grid_image				= TS_VCSC_GetResourceURL('images/defaults/no_featured.png');
										$modal_image			= TS_VCSC_GetResourceURL('images/defaults/no_featured.png');
									}
									$categories					= array();
									if (taxonomy_exists($menu_tax)) {
										foreach(get_the_terms($isoposts->post->ID, $menu_tax) as $term) array_push($categories, $term->name);
										$categories 			= implode($categories, ',');
									}									
									$valid_images++;
									$postTitleAttribute = the_title_attribute('echo=0');
									$modal_gallery .= '<a style="' . $image_style . '" href="' . get_permalink() . '" target="_blank" title="' . $postTitleAttribute . '">';
										$modal_gallery .= '<img id="ts-image-link-picture-' . $randomizer . '-' . $i .'" class="ts-image-link-picture" src="' . $grid_image . '" rel="link-group-' . $randomizer . '" data-include="true" data-image="' . $modal_image . '" width="100%" height="auto" title="' . $postTitleAttribute . '" data-groups="' . $categories . '" data-target="' . $data_grid_target . '" data-link="' . get_permalink() . '">';
									$modal_gallery	.= '</a>';
									$categories					= array();
								}
							}
						endwhile;
						wp_reset_postdata();
						wp_reset_query();
					} else {
						$modal_gallery .= '<p>Nothing found. Please check back soon!</p>';
					}
				} else {
					if (post_type_exists($post_type) && $isoposts->have_posts()) {
						if ($data_grid_machine == 'freewall') {
							$filter_settings				= 'data-gridfilter="' . $filters_show . '" data-gridavailable="' . $filters_available . '" data-gridselected="' . $filters_selected . '" data-gridnogroups="' . $filters_nogroups . '" data-gridtoggle="' . $filters_toggle . '" data-gridtogglestyle="' . $filters_toggle_style . '" data-gridshowall="' . $filters_showall . '" data-gridshowallstyle="' . $filters_showall_style . '"';
							$modal_gallery .= '<div id="ts-lightbox-freewall-grid-' . $randomizer . '-container" class="ts-lightbox-freewall-grid-container" data-random="' . $randomizer . '" data-width="' . $data_grid_width . '" data-gutter="' . $data_grid_space . '" ' . $filter_settings . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';		
						}
							while ($isoposts->have_posts() ) : $isoposts->the_post();
								$matched_terms						= 0;
								$post_thumbnail						= get_the_post_thumbnail();
								if (($matched_terms == 0) && (($post_thumbnail != '') || ($data_grid_invalid == "false"))) {
									$postCounter++;
									if ($postCounter < $posts_limit + 1) {
										if ('' != $post_thumbnail) { 
											$grid_image				= wp_get_attachment_image_src(get_post_thumbnail_id(), $content_images_size);
											$modal_image			= wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
											$grid_image				= $grid_image[0];
											$modal_image			= $modal_image[0];
										} else {
											$grid_image				= TS_VCSC_GetResourceURL('images/defaults/no_featured.png');
											$modal_image			= TS_VCSC_GetResourceURL('images/defaults/no_featured.png');
										}
										$categories					= array();
										if (taxonomy_exists($menu_tax)) {
											if ($filters_show == "true") {
												foreach(get_the_terms($isoposts->post->ID, $menu_tax) as $term) array_push($categories, $term->name);
											}
											$categories 			= implode($categories, ',');
										}					
										$valid_images++;
										$postTitleAttribute = the_title_attribute('echo=0');
										if ($data_grid_machine == 'internal') {
											$modal_gallery .= '<img id="ts-image-link-picture-' . $randomizer . '-' . $i .'" class="ts-image-link-picture" src="' . $grid_image . '" rel="link-group-' . $randomizer . '" data-no-lazy="1" data-include="true" data-image="' . $modal_image . '" width="100%" height="auto" title="' . $postTitleAttribute . '" data-groups="' . $categories . '" data-target="' . $data_grid_target . '" data-link="' . get_permalink() . '">';
										} else if ($data_grid_machine == 'freewall') {
											$modal_gallery .= '<div id="ts-lightbox-freewall-item-' . $randomizer . '-' . $i .'-parent" class="ts-lightbox-freewall-item ts-lightbox-freewall-active ' . $el_class . ' nchgrid-item nchgrid-tile" data-fixSize="false" data-groups="' . $categories . '" data-target="' . $data_grid_target . '" data-link="' . get_permalink() . '" data-showing="true" data-groups="' . (!empty($categories) ? (str_replace('/', ',', $categories)) : "") . '" style="width: ' . $data_grid_width . 'px; margin: 0; padding: 0;">';
												$modal_gallery .= '<a id="ts-lightbox-freewall-item-' . $randomizer . '-' . $i .'" href="' . get_permalink() . '" target="' . $data_grid_target . '" title="' . $postTitleAttribute . '">';
													$modal_gallery .= '<img id="ts-lightbox-freewall-picture-' . $randomizer . '-' . $i .'" class="ts-lightbox-freewall-picture" src="' . $grid_image . '" width="100%" height="auto" title="' . $postTitleAttribute . '">';
													$modal_gallery .= '<div class="nchgrid-caption"></div>';
														$modal_gallery .= '<div class="nchgrid-caption-text ' . ($data_grid_always == 'true' ? 'nchgrid-caption-text-always' : '') . '">' . get_the_title() . '</div>';
												$modal_gallery .= '</a>';
											$modal_gallery .= '</div>';
										}
										$categories					= array();
									}
								}
							endwhile;
						if ($data_grid_machine == 'freewall') {
							$modal_gallery .= '</div>';
						}
						wp_reset_postdata();
						wp_reset_query();
					} else {
						$modal_gallery .= '<p>Nothing found. Please check back soon!</p>';
					}
				}
			
				if ($valid_images < $count_columns) {
					$data_grid_string	= explode(',', $data_grid_breaks);
					$data_grid_breaks	= array();
					foreach ($data_grid_string as $single_break) {
						$b++;
						if ($b <= $valid_images) {
							array_push($data_grid_breaks, $single_break);
						} else {
							break;
						}
					}
					$data_grid_breaks	= implode(",", $data_grid_breaks);
				} else {
					$data_grid_breaks 	= $data_grid_breaks;
				}
				
				$output .= '<div id="' . $modal_id . '-frame" class="' . $grid_class . ' ' . $css_class . ' ' . (($fullwidth == "true" && $fullwidth_allow == "true") ? "ts-lightbox-nacho-full-frame" : "") . '" data-random="' . $randomizer . '" data-grid="' . $data_grid_breaks . '" data-margin="' . $data_grid_space . '" data-always="' . $data_grid_always . '" data-order="' . $data_grid_order . '" data-break-parents="' . $breakouts . '" data-inline="' . $frontend_edit . '" data-gridfilter="' . $filters_show . '" data-gridavailable="' . $filters_available . '" data-gridselected="' . $filters_selected . '" data-gridnogroups="' . $filters_nogroups . '" data-gridtoggle="' . $filters_toggle . '" data-gridtogglestyle="' . $filters_toggle_style . '" data-gridshowall="' . $filters_showall . '" data-gridshowallstyle="' . $filters_showall_style . '" style="margin-top: '  . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; position: relative;">';
					if ($data_grid_machine == 'internal') {
						$output .= '<div id="nch-lb-grid-' . $randomizer . '" class="nch-lb-grid" data-filter="nch-lb-filter-' . $randomizer . '" style="' . $grid_style . '" data-toggle="nch-lb-toggle-' . $randomizer . '" data-random="' . $randomizer . '">';
					}
						$output .= $modal_gallery;
					if ($data_grid_machine == 'internal') {
						$output .= '</div>';
					}
				$output .= '</div>';
				
				echo $output;
				
				// Turn Photon Back On
				if ($photon_removed != '') {
					add_filter('image_downsize', array(Jetpack_Photon::instance(), 'filter_image_downsize'), 10, 3);
				}
				$photon_removed 					= '';
				
				$myvariable = ob_get_clean();
				return $myvariable;
			}
		
			// Add Image Posts Elements
			function TS_VCSC_Add_Posts_Image_Elements() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Standalone Posts Image Grid
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					"name"                              => __( "TS Posts Image Grid", "ts_visual_composer_extend" ),
					"base"                              => "TS_VCSC_Posts_Image_Grid_Standalone",
					"icon" 	                            => "ts-composer-element-icon-posts-image-grid",
					"category"                          => __( 'VC Extensions', "ts_visual_composer_extend" ),
					"description"                       => __("Place a Posts Image Grid element", "ts_visual_composer_extend"),
					"admin_enqueue_js"            		=> "",
					"admin_enqueue_css"           		=> "",
					"params"                            => array(
						// Posts Ticker Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_1",
							"seperator"					=> "Content Settings",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Post Type", "ts_visual_composer_extend" ),
							"param_name"        		=> "post_type",
							"value"             		=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PublicPostTypesSelect,
							"admin_label"		        => true,
							"description"       		=> __( "Select the post type you want to retrieve posts from.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              		=> "messenger",
							"param_name"        		=> "post_messenger",
							"color"						=> "#006BB7",
							"size"						=> "13",
							"layout"					=> "notice",
							"message"            		=> __( "Please note that this element, in its output and filter capabilities, will only provide limited support for custom post types as it is primarily designed for the standard WordPress post type.", "ts_visual_composer_extend" ),
							"dependency" 				=> array("element" 	=> "post_type", "value" 	=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PublicPostTypesDepend),
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Post Taxonomy", "ts_visual_composer_extend" ),
							"param_name"        		=> "post_taxos",
							"value"             		=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PublicPostTaxosSelect,
							"description"       		=> __( "Select the custom taxonomy associated with your selected custom post type.", "ts_visual_composer_extend" ),
							"dependency" 				=> array("element" 	=> "post_type", "value" 	=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PublicPostTypesDepend),
						),		
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Categories Limitation", "ts_visual_composer_extend" ),
							"param_name"        		=> "limit_posts",
							"value"             		=> array(
								__( "No Limitations", "ts_visual_composer_extend" )                     	=> "false",
								__( "All But Excluded Categories", "ts_visual_composer_extend" )			=> "true",
								__( "Only Included Categories", "ts_visual_composer_extend" )				=> "include",
							),
							"admin_label"		        => true,
							"description"       		=> __( "Select if and how you want to limit the posts to specific categories.", "ts_visual_composer_extend" ),
							"dependency" 				=> array("element" => "post_type", "value" => "post"),
						),
						array(
							"type"                      => "standardpostcat",
							"heading"                   => __( "Select Excluded Categories", "ts_visual_composer_extend" ),
							"param_name"                => "limit_term",
							"posttype"                  => "post",
							"posttaxonomy"              => "category",
							"taxonomy"              	=> "category",
							"postsingle"				=> "Post",
							"postplural"				=> "Posts",
							"postclass"					=> "post",
							"method"					=> "exclude",
							"value"                     => "",
							"description"               => __( "Please select the categories you want to use or exclude for the element.", "ts_visual_composer_extend" ),
							"dependency" 				=> array("element" 	=> "limit_posts", "value" 	=> "true"),
						),
						array(
							"type"                      => "standardpostcat",
							"heading"                   => __( "Select Included Categories", "ts_visual_composer_extend" ),
							"param_name"                => "limit_include",
							"posttype"                  => "post",
							"posttaxonomy"              => "category",
							"taxonomy"              	=> "category",
							"postsingle"				=> "Post",
							"postplural"				=> "Posts",
							"postclass"					=> "post",
							"method"					=> "include",
							"value"                     => "",							
							"description"               => __( "Please select the categories you want to include for the element.", "ts_visual_composer_extend" ),
							"dependency" 				=> array("element" 	=> "limit_posts", "value" 	=> "include"),
						),
						array(
							"type"              	    => "switch_button",
							"heading"                   => __( "Use Additional Stati", "ts_visual_composer_extend" ),
							"param_name"                => "post_custom",
							"value"                     => "false",
							"description"               => __( "Switch the toggle if you want to also include post with a status other then 'publish'.", "ts_visual_composer_extend" )
						),
						array(
							"type"                      => "standardpoststati",
							"heading"                   => __( "Select Additional Stati", "ts_visual_composer_extend" ),
							"param_name"                => "post_status",
							"value"                     => "",							
							"description"               => __( "Please select the additional post stati you want to include for the element; all posts with 'publish' status are automatically included.", "ts_visual_composer_extend" ),
							"dependency" 				=> array("element" 	=> "post_custom", "value" 	=> "true"),
						),	
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Total Number of Posts", "ts_visual_composer_extend" ),
							"param_name"                => "posts_limit",
							"value"                     => "25",
							"min"                       => "1",
							"max"                       => "100",
							"step"                      => "1",
							"unit"                      => '',
							"admin_label"		        => true,
							"description"               => __( "Select the total number of posts to be retrieved from WordPress.", "ts_visual_composer_extend" ),
						),						
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Exclude Non-Image Posts", "ts_visual_composer_extend" ),
							"param_name"		    	=> "data_grid_invalid",
							"value"				    	=> "false",
							"description"		    	=> __( "Switch the toggle to automatically exclude posts without a featured image.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Always Show Post Title", "ts_visual_composer_extend" ),
							"param_name"		    	=> "data_grid_always",
							"value"				    	=> "true",
							"description"		    	=> __( "Switch the toggle to always show the post title with the post image.", "ts_visual_composer_extend" ),
						),		
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Link Target", "ts_visual_composer_extend" ),
							"param_name"        		=> "data_grid_target",
							"value"             		=> array(
								__( "New Window", "ts_visual_composer_extend" )                     		=> "_blank",
								__( "Same Window", "ts_visual_composer_extend" )                    		=> "_parent",
							),
							"description"       		=> __( "Select how the links should be opened.", "ts_visual_composer_extend" ),
						),
						// Grid Settings
						array(
							"type"                  	=> "seperator",
							"param_name"            	=> "seperator_2",
							"seperator"					=> "Grid Settings",
							"group" 					=> "Grid Settings",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Grid Render Machine", "ts_visual_composer_extend" ),
							"param_name"        		=> "data_grid_machine",
							"value"             		=> array(
								__( "Rectangle Auto Posts Grid", "ts_visual_composer_extend" )				=> "internal",
								__( "Freewall Fluid Posts Grid", "ts_visual_composer_extend" )				=> "freewall",
							),
							"admin_label"       		=> true,
							"description"       		=> __( "Select which script should be used to render the grid layout.", "ts_visual_composer_extend" ),
							"group" 					=> "Grid Settings"
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Grid Break Points", "ts_visual_composer_extend" ),
							"param_name"            	=> "data_grid_breaks",
							"value"                 	=> "240,480,720,960",
							"description"           	=> __( "Define the break points (columns) for the grid based on available screen size; separate by comma.", "ts_visual_composer_extend" ),
							"admin_label"           	=> true,
							"dependency" 				=> array("element" 	=> "data_grid_machine", "value" => "internal"),
							"group" 					=> "Grid Settings"
						),
						array(
							"type"                  	=> "nouislider",
							"heading"               	=> __( "Item Width", "ts_visual_composer_extend" ),
							"param_name"            	=> "data_grid_width",
							"value"                 	=> "250",
							"min"                   	=> "100",
							"max"                   	=> "500",
							"step"                  	=> "1",
							"unit"                  	=> 'px',
							"description"           	=> __( "Define the desired width of each image in the grid; will be adjusted if necessary.", "ts_visual_composer_extend" ),
							"dependency" 				=> array("element" 	=> "data_grid_machine", "value" => "freewall"),
							"group" 					=> "Grid Settings"
						),
						array(
							"type"                  	=> "nouislider",
							"heading"               	=> __( "Grid Space", "ts_visual_composer_extend" ),
							"param_name"            	=> "data_grid_space",
							"value"                 	=> "2",
							"min"                   	=> "0",
							"max"                   	=> "20",
							"step"                  	=> "1",
							"unit"                  	=> 'px',
							"description"           	=> __( "Define the space between images in grid.", "ts_visual_composer_extend" ),
							"admin_label"           	=> true,
							"group" 					=> "Grid Settings"
						),						
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Image Source", "ts_visual_composer_extend" ),
							"param_name"            	=> "content_images_size",
							"width"                 	=> 150,
							"value"                 	=> array(
								__( 'Thumbnail Size Image', "ts_visual_composer_extend" )				=> "thumbnail",
								__( 'Medium Size Image', "ts_visual_composer_extend" )					=> "medium",								
								__( 'Large Size Image', "ts_visual_composer_extend" )					=> "large",
								__( 'Full Size Image', "ts_visual_composer_extend" )					=> "full",
							),
							"default"					=> "medium",
							"standard"					=> "medium",
							"std"						=> "medium",
							"description"           	=> __( "Select which image size based on WordPress settings should be used for the grid images.", "ts_visual_composer_extend" ),
							"group" 					=> "Grid Settings"
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Maintain Posts Order", "ts_visual_composer_extend" ),
							"param_name"		    	=> "data_grid_order",
							"value"				    	=> "false",
							"description"		    	=> __( "Switch the toggle to keep original posts order in grid; it is adviced to have the plugin determine order for best layout.", "ts_visual_composer_extend" ),
							"dependency" 				=> array("element" 	=> "data_grid_machine", "value" => "internal"),
							"group" 					=> "Grid Settings"
						),
						array(
							"type"              		=> "switch_button",
							"heading"               	=> __( "Make Grid Full-Width", "ts_visual_composer_extend" ),
							"param_name"            	=> "fullwidth",
							"value"                 	=> "false",
							"description"           	=> __( "Switch the toggle if you want to attempt showing the gallery in full width (will not work with all themes).", "ts_visual_composer_extend" ),
							"admin_label"           	=> true,
							"group" 					=> "Grid Settings"
						),
						array(
							"type"                 	 	=> "nouislider",
							"heading"               	=> __( "Full Grid Breakouts", "ts_visual_composer_extend" ),
							"param_name"            	=> "breakouts",
							"value"                 	=> "6",
							"min"                   	=> "0",
							"max"                   	=> "99",
							"step"                  	=> "1",
							"unit"                  	=> '',
							"description"           	=> __( "Define the number of parent containers the gallery should attempt to break away from.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "fullwidth", 'value' => 'true' ),
							"group" 					=> "Grid Settings"
						),
						// Filter Settings
						array(
							"type"                  	=> "seperator",
							"param_name"            	=> "seperator_3",
							"seperator"					=> "Filter Settings",
							"group" 					=> "Filter Settings",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Filter Option", "ts_visual_composer_extend" ),
							"param_name"		    	=> "filters_show",
							"value"				    	=> "true",
							"admin_label"           	=> true,
							"description"		    	=> __( "Switch the toggle to provide a basic filter option for the post images.", "ts_visual_composer_extend" ),
							"group" 					=> "Filter Settings"
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Filter Toggle: Text", "ts_visual_composer_extend" ),
							"param_name"            	=> "filters_toggle",
							"value"                 	=> "Toggle Filter",
							"description"           	=> __( "Enter a text to be used for the filter button.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "filters_show", 'value' => 'true' ),
							"group" 					=> "Filter Settings",
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Filter Toggle: Style", "ts_visual_composer_extend" ),
							"param_name"            	=> "filters_toggle_style",
							"width"                 	=> 150,
							"value"                 	=> array(
								__( 'No Style', "ts_visual_composer_extend" )				=> "",
								__( 'Sun Flower Flat', "ts_visual_composer_extend" )		=> "ts-color-button-sun-flower",
								__( 'Orange Flat', "ts_visual_composer_extend" )			=> "ts-color-button-orange-flat",
								__( 'Carot Flat', "ts_visual_composer_extend" )     		=> "ts-color-button-carrot-flat",
								__( 'Pumpkin Flat', "ts_visual_composer_extend" )			=> "ts-color-button-pumpkin-flat",
								__( 'Alizarin Flat', "ts_visual_composer_extend" )			=> "ts-color-button-alizarin-flat",
								__( 'Pomegranate Flat', "ts_visual_composer_extend" )		=> "ts-color-button-pomegranate-flat",
								__( 'Turquoise Flat', "ts_visual_composer_extend" )			=> "ts-color-button-turquoise-flat",
								__( 'Green Sea Flat', "ts_visual_composer_extend" )			=> "ts-color-button-green-sea-flat",
								__( 'Emerald Flat', "ts_visual_composer_extend" )			=> "ts-color-button-emerald-flat",
								__( 'Nephritis Flat', "ts_visual_composer_extend" )			=> "ts-color-button-nephritis-flat",
								__( 'Peter River Flat', "ts_visual_composer_extend" )		=> "ts-color-button-peter-river-flat",
								__( 'Belize Hole Flat', "ts_visual_composer_extend" )		=> "ts-color-button-belize-hole-flat",
								__( 'Amethyst Flat', "ts_visual_composer_extend" )			=> "ts-color-button-amethyst-flat",
								__( 'Wisteria Flat', "ts_visual_composer_extend" )			=> "ts-color-button-wisteria-flat",
								__( 'Wet Asphalt Flat', "ts_visual_composer_extend" )		=> "ts-color-button-wet-asphalt-flat",
								__( 'Midnight Blue Flat', "ts_visual_composer_extend" )		=> "ts-color-button-midnight-blue-flat",
								__( 'Clouds Flat', "ts_visual_composer_extend" )			=> "ts-color-button-clouds-flat",
								__( 'Silver Flat', "ts_visual_composer_extend" )			=> "ts-color-button-silver-flat",
								__( 'Concrete Flat', "ts_visual_composer_extend" )			=> "ts-color-button-concrete-flat",
								__( 'Asbestos Flat', "ts_visual_composer_extend" )			=> "ts-color-button-asbestos-flat",
								__( 'Graphite Flat', "ts_visual_composer_extend" )			=> "ts-color-button-graphite-flat",
							),
							"description"           	=> __( "Select the color scheme for the filter button.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "filters_show", 'value' => 'true' ),
							"group" 					=> "Filter Settings",
						),						
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Show All Toggle: Text", "ts_visual_composer_extend" ),
							"param_name"            	=> "filters_showall",
							"value"                 	=> "Show All",
							"description"          	 	=> __( "Enter a text to be used for the show all button.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "filters_show", 'value' => 'true' ),
							"group" 					=> "Filter Settings",
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Show All Toggle: Style", "ts_visual_composer_extend" ),
							"param_name"            	=> "filters_showall_style",
							"width"                 	=> 150,
							"value"                 	=> array(
								__( 'No Style', "ts_visual_composer_extend" )				=> "",
								__( 'Sun Flower Flat', "ts_visual_composer_extend" )		=> "ts-color-button-sun-flower",
								__( 'Orange Flat', "ts_visual_composer_extend" )			=> "ts-color-button-orange-flat",
								__( 'Carot Flat', "ts_visual_composer_extend" )     		=> "ts-color-button-carrot-flat",
								__( 'Pumpkin Flat', "ts_visual_composer_extend" )			=> "ts-color-button-pumpkin-flat",
								__( 'Alizarin Flat', "ts_visual_composer_extend" )			=> "ts-color-button-alizarin-flat",
								__( 'Pomegranate Flat', "ts_visual_composer_extend" )		=> "ts-color-button-pomegranate-flat",
								__( 'Turquoise Flat', "ts_visual_composer_extend" )			=> "ts-color-button-turquoise-flat",
								__( 'Green Sea Flat', "ts_visual_composer_extend" )			=> "ts-color-button-green-sea-flat",
								__( 'Emerald Flat', "ts_visual_composer_extend" )			=> "ts-color-button-emerald-flat",
								__( 'Nephritis Flat', "ts_visual_composer_extend" )			=> "ts-color-button-nephritis-flat",
								__( 'Peter River Flat', "ts_visual_composer_extend" )		=> "ts-color-button-peter-river-flat",
								__( 'Belize Hole Flat', "ts_visual_composer_extend" )		=> "ts-color-button-belize-hole-flat",
								__( 'Amethyst Flat', "ts_visual_composer_extend" )			=> "ts-color-button-amethyst-flat",
								__( 'Wisteria Flat', "ts_visual_composer_extend" )			=> "ts-color-button-wisteria-flat",
								__( 'Wet Asphalt Flat', "ts_visual_composer_extend" )		=> "ts-color-button-wet-asphalt-flat",
								__( 'Midnight Blue Flat', "ts_visual_composer_extend" )		=> "ts-color-button-midnight-blue-flat",
								__( 'Clouds Flat', "ts_visual_composer_extend" )			=> "ts-color-button-clouds-flat",
								__( 'Silver Flat', "ts_visual_composer_extend" )			=> "ts-color-button-silver-flat",
								__( 'Concrete Flat', "ts_visual_composer_extend" )			=> "ts-color-button-concrete-flat",
								__( 'Asbestos Flat', "ts_visual_composer_extend" )			=> "ts-color-button-asbestos-flat",
								__( 'Graphite Flat', "ts_visual_composer_extend" )			=> "ts-color-button-graphite-flat",
							),
							"description"           	=> __( "Select the color scheme for the show all button.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "filters_show", 'value' => 'true' ),
							"group" 					=> "Filter Settings",
						),	
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Text: Available Groups", "ts_visual_composer_extend" ),
							"param_name"            	=> "filters_available",
							"value"                 	=> "Available Groups",
							"description"           	=> __( "Enter a text to be used a header for the section with the available groups.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "filters_show", 'value' => 'true' ),
							"group" 					=> "Filter Settings",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Text: Selected Groups", "ts_visual_composer_extend" ),
							"param_name"            	=> "filters_selected",
							"value"                 	=> "Filtered Groups",
							"description"           	=> __( "Enter a text to be used a header for the section with the selected groups.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "filters_show", 'value' => 'true' ),
							"group" 					=> "Filter Settings",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Text: Ungrouped Images", "ts_visual_composer_extend" ),
							"param_name"            	=> "filters_nogroups",
							"value"                 	=> "No Groups",
							"description"           	=> __( "Enter a text to be used to group images without any other groups applied to it.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "filters_show", 'value' => 'true' ),
							"group" 					=> "Filter Settings",
						),
						// Other Settings
						array(
							"type"						=> "seperator",
							"param_name"                => "seperator_4",
							"seperator"					=> "Other Settings",
							"group" 			        => "Other Settings",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Margin: Top", "ts_visual_composer_extend" ),
							"param_name"                => "margin_top",
							"value"                     => "0",
							"min"                       => "-50",
							"max"                       => "500",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Select the top margin for the element.", "ts_visual_composer_extend" ),
							"group" 			        => "Other Settings",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Margin: Bottom", "ts_visual_composer_extend" ),
							"param_name"                => "margin_bottom",
							"value"                     => "0",
							"min"                       => "-50",
							"max"                       => "500",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Select the bottom margin for the element.", "ts_visual_composer_extend" ),
							"group" 			        => "Other Settings",
						),
						array(
							"type"                      => "textfield",
							"heading"                   => __( "Define ID Name", "ts_visual_composer_extend" ),
							"param_name"                => "el_id",
							"value"                     => "",
							"description"               => __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
							"group" 			        => "Other Settings",
						),
						array(
							"type"                  	=> "tag_editor",
							"heading"           		=> __( "Extra Class Names", "ts_visual_composer_extend" ),
							"param_name"            	=> "el_class",
							"value"                 	=> "",
							"description"      		 	=> __( "Enter additional class names for the element.", "ts_visual_composer_extend" ),
							"group" 					=> "Other Settings",
						),
					)
				);
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
					return $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element;
				} else {			
					vc_map($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element);
				};
			}
		}
	}
	// Register Container and Child Shortcode with Visual Composer
	if ((class_exists('WPBakeryShortCode')) && (!class_exists('WPBakeryShortCode_TS_VCSC_Posts_Image_Grid_Standalone'))) {
		class WPBakeryShortCode_TS_VCSC_Posts_Image_Grid_Standalone extends WPBakeryShortCode {};
	}	
	// Initialize "TS Posts Image" Class
	if (class_exists('TS_Postsimage')) {
		$TS_Postsimage = new TS_Postsimage;
	}
?>