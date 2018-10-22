<?php
	if (!class_exists('TS_Timeline_CSS')){
		class TS_Timeline_CSS {
			function __construct() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						$this->TS_VCSC_Add_Timeline_CSS_Element_Lean();
					} else if (function_exists('vc_map')) {
						add_action('init',									array($this, 'TS_VCSC_Add_Timeline_CSS_Element_Container'), 9999999);
						if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_CustomPostTypesTimeline == "true") {
							add_action('init',								array($this, 'TS_VCSC_Add_Timeline_CSS_Element_Section'), 9999999);
						}
						if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Elements["TS CSS Media Timeline"]["active"] == "true") {
							add_action('init',								array($this, 'TS_VCSC_Add_Timeline_CSS_Element_Break'), 9999999);
							add_action('init',								array($this, 'TS_VCSC_Add_Timeline_CSS_Element_Event'), 9999999);
						}
					}
				} else {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_Timeline_CSS_Element_Lean'), 9999999);
					} else if (function_exists('vc_map')) {
						add_action('admin_init',							array($this, 'TS_VCSC_Add_Timeline_CSS_Element_Container'), 9999999);
						if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_CustomPostTypesTimeline == "true") {
							add_action('admin_init',						array($this, 'TS_VCSC_Add_Timeline_CSS_Element_Section'), 9999999);
						}
						if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Elements["TS CSS Media Timeline"]["active"] == "true") {
							add_action('admin_init',						array($this, 'TS_VCSC_Add_Timeline_CSS_Element_Break'), 9999999);
							add_action('admin_init',						array($this, 'TS_VCSC_Add_Timeline_CSS_Element_Event'), 9999999);
						}
					}
				}
				if ((is_admin() == false) || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAJAX == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAlways == "true")) {
					add_shortcode('TS_VCSC_Timeline_CSS_Container',         array($this, 'TS_VCSC_Timeline_CSS_Function_Container'));
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_CustomPostTypesTimeline == "true") {
						add_shortcode('TS_VCSC_Timeline_CSS_Section',		array($this, 'TS_VCSC_Timeline_CSS_Function_Section'));
					}
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Elements["TS CSS Media Timeline"]["active"] == "true") {
						add_shortcode('TS_VCSC_Timeline_CSS_Break',         array($this, 'TS_VCSC_Timeline_CSS_Function_Break'));
						add_shortcode('TS_VCSC_Timeline_CSS_Event',         array($this, 'TS_VCSC_Timeline_CSS_Function_Event'));
					}
				}
			}
			
			// Register Element(s) via LeanMap
			function TS_VCSC_Add_Timeline_CSS_Element_Lean() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				vc_lean_map('TS_VCSC_Timeline_CSS_Container', 				array($this, 'TS_VCSC_Add_Timeline_CSS_Element_Container'), null);
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_CustomPostTypesTimeline == "true") {
					vc_lean_map('TS_VCSC_Timeline_CSS_Section', 			array($this, 'TS_VCSC_Add_Timeline_CSS_Element_Section'), null);
				}
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Elements["TS CSS Media Timeline"]["active"] == "true") {
					vc_lean_map('TS_VCSC_Timeline_CSS_Break', 				array($this, 'TS_VCSC_Add_Timeline_CSS_Element_Break'), null);
					vc_lean_map('TS_VCSC_Timeline_CSS_Event', 				array($this, 'TS_VCSC_Add_Timeline_CSS_Element_Event'), null);
				}
			}
			
			// Timeline Post Section
			function TS_VCSC_Timeline_CSS_Function_Section ($atts, $content = null) {
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();
		
				extract( shortcode_atts( array(
					'section'						=> '',					
					'section_icon'					=> '',
					'icon_color'					=> '#7c7979',					
					'tooltipster_offsetx'			=> 0,
					'tooltipster_offsety'			=> 0,			
					'content_wpautop'				=> 'false',	
					'el_id' 						=> '',
					'el_class'                  	=> '',
					'css'							=> '',
				), $atts ));
				
				$media_string						= '';
				$output 							= '';
				$wpautop 							= ($content_wpautop == "true" ? true : false);			
				$randomizer							= mt_rand(999999, 9999999);
				
				// Turn Photon Off (For Correct Image Information)
				/*$photon_removed 					= '';
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_JetpackPhoton_Active == "true") {
					$photon_removed 				= remove_filter('image_downsize', array(Jetpack_Photon::instance(), 'filter_image_downsize'));
				}*/
				
				// Check for Codestar Migration
				$codestarRetrieve					= "false";
				$codestarMigrated 					= get_post_meta($section, 'ts_vcsc_custompost_migrated', true);
				if (!empty($codestarMigrated)) {
					$codestarRetrieve				= "true";
				}
			
				if (!empty($el_id)) {
					$timeline_id					= $el_id;
				} else {
					$timeline_id					= 'ts-vcsc-timeline-section-' . $randomizer;
				}
				
				// Retrieve Timeline Post
				$timeline_array						= array();
				$category_fields 	                = array();
				$args = array(
					'p'								=> $section,
					'no_found_rows' 				=> 1,
					'ignore_sticky_posts' 			=> 1,
					'posts_per_page' 				=> 1,
					'post_type' 					=> 'ts_timeline',
					'post_status' 					=> 'publish',
					'orderby' 						=> 'title',
					'order' 						=> 'ASC',
				);
				$timeline_query = new WP_Query($args);
				if ($timeline_query->have_posts()) {
					foreach($timeline_query->posts as $p) {
						if ($p->ID == $section) {
							// Retrieve Post Categories
							$section_categories		= get_the_terms($p->ID, 'ts_timeline_category');
							$array_categories		= array();
							if ($section_categories != false) {
								foreach ($section_categories as $category) {
									$array_categories[] = $category->name;
								}
							}
							$section_categories 	= join(",", $array_categories);						
							// Retrieve Post Tags
							$sections_tags			= get_the_terms($p->ID, 'ts_timeline_tags');
							$array_tags				= array();
							if ($sections_tags != false) {
								foreach ($sections_tags as $tag) {
									$array_tags[] 	= $tag->name;
								}
							}
							$sections_tags 			= join(",", $array_tags);
							// Build Data Array
							$timeline_data = array(
								'author'			=> $p->post_author,
								'name'				=> $p->post_name,
								'title'				=> $p->post_title,
								'id'				=> $p->ID,
								'categories'		=> $section_categories,
								'tags'				=> $sections_tags
							);
							$timeline_array[] 		= $timeline_data;
							$array_categories		= array();
							$array_tags				= array();
						}
					}
				}
				wp_reset_postdata();
				
				// Section Main Data
				foreach ($timeline_array as $index => $array) {
					$Section_Author					= $timeline_array[$index]['author'];
					$Section_Name 					= $timeline_array[$index]['name'];
					$Section_Title 					= $timeline_array[$index]['title'];
					$Section_ID 					= $timeline_array[$index]['id'];
					$Section_Categories				= $timeline_array[$index]['categories'];
					$Section_Tags					= $timeline_array[$index]['tags'];
				}
				
				// Retrieve Timeline Post Meta Content
				if ($codestarRetrieve == "true") {
					$custom_fields 					= get_post_meta($Section_ID, 'ts_vcsc_timeline_information', true);
					if (!is_array($custom_fields)) {
						$custom_fields				= array();
					}
					$custom_fields_array			= array();
					$Timeline_Type					= (isset($custom_fields['ts_vcsc_timeline_type_type']) ? $custom_fields['ts_vcsc_timeline_type_type'] : "event");
					foreach ($custom_fields as $field_key => $field_values) {
						if (strpos($field_key, 'ts_vcsc_timeline_') !== false) {
							if ($field_key == "ts_vcsc_timeline_media_featuredimage") {
								$field_key			= "ts_vcsc_timeline_media_featuredimageid";
							}
							$field_key_split 		= explode("_", $field_key);
							$field_key_length 		= count($field_key_split) - 1;
							$custom_data = array(
								'group'				=> $field_key_split[$field_key_length - 1],
								'name'				=> 'Timeline_' . ucfirst($field_key_split[$field_key_length]),
								'value'				=> $field_values,
							);
							$custom_fields_array[] 	= $custom_data;
						}
					}
					foreach ($custom_fields_array as $index => $array) {
						${$custom_fields_array[$index]['name']} = $custom_fields_array[$index]['value'];
					}
				} else {
					$custom_fields 					= get_post_custom($Section_ID);
					$custom_fields_array			= array();
					foreach ($custom_fields as $field_key => $field_values) {
						if (!isset($field_values[0])) continue;
						if (in_array($field_key, array("_edit_lock", "_edit_last"))) continue;
						if (strpos($field_key, 'ts_vcsc_timeline_') !== false) {
							if ($field_key == "ts_vcsc_timeline_media_featuredimage_id") {
								$field_key			= "ts_vcsc_timeline_media_featuredimageid";
							}
							$field_key_split 		= explode("_", $field_key);
							$field_key_length 		= count($field_key_split) - 1;
							$custom_data = array(
								'group'				=> $field_key_split[$field_key_length - 1],
								'name'				=> 'Timeline_' . ucfirst($field_key_split[$field_key_length]),
								'value'				=> $field_values[0],
							);
							$custom_fields_array[] 	= $custom_data;
						}
					}
					foreach ($custom_fields_array as $index => $array) {
						${$custom_fields_array[$index]['name']} = $custom_fields_array[$index]['value'];
					}
				}

				// Contingency Checks
				$Timeline_Lightboxfeatured			= TS_VCSC_TrueFalseEqualizer((isset($Timeline_Lightboxfeatured) 	? $Timeline_Lightboxfeatured : ""), 	"true");
				$Timeline_Lightboxgroup				= TS_VCSC_TrueFalseEqualizer((isset($Timeline_Lightboxgroup) 		? $Timeline_Lightboxgroup : ""), 		"true");
				$Timeline_Dedicatedtarget			= TS_VCSC_TrueFalseEqualizer((isset($Timeline_Dedicatedtarget) 		? $Timeline_Dedicatedtarget : ""), 		"true");			
				$Timeline_Fullwidth					= TS_VCSC_TrueFalseEqualizer((isset($Timeline_Fullwidth) 			? $Timeline_Fullwidth : ""), 			"true");
				$Timeline_Breakfull					= TS_VCSC_TrueFalseEqualizer((isset($Timeline_Breakfull) 			? $Timeline_Breakfull : ""), 			"true");
				
				// Custom Styling
				if ((isset($Timeline_Customevent)) && ($Timeline_Type == "event")) {
					$Timeline_Customizer			= TS_VCSC_TrueFalseEqualizer((isset($Timeline_Customevent) 			? $Timeline_Customevent : ""), 			"true");
				} else if ((isset($Timeline_Custombreak)) && ($Timeline_Type == "break")) {
					$Timeline_Customizer			= TS_VCSC_TrueFalseEqualizer((isset($Timeline_Custombreak) 			? $Timeline_Custombreak : ""), 			"true");
				} else {
					$Timeline_Customizer			= "false";
				}
				$style_event_titlecolor				= '';
				$style_event_contentcolor			= '';
				$style_datetime_iconcolor			= '';
				$style_datetime_textcolor			= '';
				$style_datetime_background			= '';
				$style_event_background				= '';
				$style_break_background				= '';
				$style_break_contentcolor			= '';
				$style_break_titlecolor				= '';
				if ($Timeline_Customizer == "true") {
					if (isset($Timeline_Eventtitlecolor)) {
						$style_event_titlecolor 	= 'color: ' . $Timeline_Eventtitlecolor . ';';
					}
					if (isset($Timeline_Eventcontentcolor)) {
						$style_event_contentcolor	= 'color: ' . $Timeline_Eventcontentcolor . ';';
					}
					if (isset($Timeline_Eventdatecoloricon)) {
						$style_datetime_iconcolor	= 'color: ' . $Timeline_Eventdatecoloricon . ';';
					}
					if (isset($Timeline_Eventdatecolordate)) {
						$style_datetime_textcolor	= 'color: ' . $Timeline_Eventdatecolordate . ';';
					}
					if (isset($Timeline_Eventdatecolorback)) {
						$style_datetime_background	= 'background: ' . $Timeline_Eventdatecolorback . ';';
					}
					if (isset($Timeline_Eventbackcolor)) {
						$style_event_background		= "background: " . $Timeline_Eventbackcolor . ';';
					}
					if (isset($Timeline_Breakbackground)) {
						$style_break_background		= 'background: ' . $Timeline_Breakbackground . ';';
					}
					if (isset($Timeline_Breakcontentcolor)) {
						$style_break_contentcolor	= 'color: ' . $Timeline_Breakcontentcolor . ';';
					}
					if (isset($Timeline_Breaktitlecolor)) {
						$style_break_titlecolor 	= 'color: ' . $Timeline_Breaktitlecolor . ';';
					}
				}

				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-timeline-css-section ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Timeline_CSS_Section', $atts);
				} else {
					$css_class						= 'ts-timeline-css-section ' . $el_class;
				}
				
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					$vc_inline						= 'true';
					$vc_inline_style				= ' display: block;';
				} else {
					$vc_inline						= 'false';
					$vc_inline_style				= '';
				}			
				
				if ($vc_inline == "false") {			
					// Tooltip String
					if ((isset($Timeline_Tooltiptext)) && ($Timeline_Tooltiptext != "")) {
						if (isset($Timeline_Tooltipstyle)) {
							$tooltip_style			= "tooltipster-" . $Timeline_Tooltipstyle;
						} else {
							$tooltip_style			= "tooltipster-black";
						}
						if (isset($Timeline_Tooltipposition)) {
							$tooltip_position		= $Timeline_Tooltipposition;
						} else {
							$tooltip_position		= 'top';
						}
						$tooltip_content			= 'data-tooltipster-html="true" data-tooltipster-title="" data-tooltipster-text="' . base64_encode($Timeline_Tooltiptext) . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="swing" data-tooltipster-trigger="hover" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . $tooltipster_offsety . '"';
						$tooltip_class				= 'ts-has-tooltipster-tooltip';
					} else {
						$tooltip_content			= '';
						$tooltip_class				= '';
					}					
					// Timeline Event
					if ($Timeline_Type == "event") {
						// Feature Media Alignment
						if (isset($Timeline_Featuredmediaalign)) {
							if ($Timeline_Featuredmediaalign == "center") {
								$image_alignment	= "margin: 0 auto; float: none;";
							} else if ($Timeline_Featuredmediaalign == "left") {
								$image_alignment	= "margin: 0 0; float: left;";
							} else if ($Timeline_Featuredmediaalign == "right") {
								$image_alignment	= "margin: 0 0; float: right;";
							}
						} else {
							$image_alignment		= "margin: 0 auto; float: none;";
						}
						// Feature Media Dimensions
						$image_dimensions			= 'width: 100%; height: auto;';
						$parent_dimensions			= 'width: ' . $Timeline_Featuredmediawidth . '%; ' . $Timeline_Featuredmediaheight;
						// Lightbox Background Color
						if (isset($Timeline_Lightboxbacklight)) {
							if ($Timeline_Lightboxbacklight == "auto") {
								$nacho_color		= '';
							} else if ($Timeline_Lightboxbacklight == "custom") {
								$nacho_color		= 'data-color="' . $Timeline_Lightboxbacklightcolor . '"';
							} else if ($Timeline_Lightboxbacklight == "hideit") {
								$nacho_color		= 'data-color="rgba(0, 0, 0, 0)"';
							}
						} else {
							$nacho_color			= '';
						}
						// Adjustment for Inline Edit Mode of VC
						if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
							$Timeline_Fullwidth		= 'true';
							$vcinline_active		= 'true';
							$vcinline_class			= '';
							$vcinline_slider		= 'css-timeline-mediaslider-edit';
						} else {
							$Timeline_Fullwidth		= $Timeline_Fullwidth;
							$vcinline_active		= 'false';
							$vcinline_class			= '';
							$vcinline_slider		= 'css-timeline-mediaslider';
						}
						// Featured Media
						$media_string				= '';
						$slider_class				= '';
						if (isset($Timeline_Featuredmedia)) {
							// Featured Media: Image
							if ($Timeline_Featuredmedia == 'image') {
								if (isset($Timeline_Featuredimageid)) {
									$media_image 				= wp_get_attachment_image_src($Timeline_Featuredimageid, 'large');
									if ($media_image != false) {
										$image_extension 		= pathinfo($media_image[0], PATHINFO_EXTENSION);
										if (isset($Timeline_Attributealtvalue)) {
											$alt_attribute		= $Timeline_Attributealtvalue;
										} else {
											$alt_attribute		= basename($media_image[0]);
										}
										if (isset($Timeline_Attributetitle)) {
											$media_title 		= $Timeline_Attributetitle;
										} else if (isset($Timeline_Eventtitletext)) {
											$media_title 		= $Timeline_Eventtitletext;
										} else {
											$media_title		= '';
										}								
										if ($Timeline_Lightboxfeatured === "false") {
											$media_string .= '<div class="ts-timeline-media" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
												$media_string .= '<img class="" src="' . $media_image[0] . '" alt="' . $alt_attribute . '" style="max-width: ' . $media_image[1] . 'px; padding: 0; margin: 0 auto; display: block; ' . $image_dimensions . '">';
											$media_string .= '</div>';
										} else {
											$media_string .= '<div class="ts-timeline-media nchgrid-item nchgrid-tile nch-lightbox-image" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
												$media_string .= '<a href="' . $media_image[0] . '" class="nch-lightbox-media no-ajaxy" data-thumbnail="' . $media_image[0] . '" data-title="' . $media_title . '" rel="' . ($Timeline_Lightboxgroup == "true" ? "timelinegroup" : (isset($Timeline_Lightboxgroupname) ? $Timeline_Lightboxgroupname : "")) . '" data-share="0" data-effect="' . $Timeline_Lightboxeffect . '" data-duration="5000" ' . $nacho_color . '>';
													$media_string .= '<img src="' . $media_image[0] . '" alt="' . $alt_attribute . '" title="" style="max-width: ' . $media_image[1] . 'px; padding: 0; margin: 0 auto; display: block; ' . $image_dimensions . '">';
													$media_string .= '<div class="nchgrid-caption"></div>';
													if ($media_title != '') {
														$media_string .= '<div class="nchgrid-caption-text">' . $media_title . '</div>';
													}
												$media_string .= '</a>';
											$media_string .= '</div>';
										}
									}
								}
							}
							// Featured Media: Image Slider
							if ($Timeline_Featuredmedia == 'slider') {
								if (isset($Timeline_Featuredslider)) {
									if (isset($Timeline_Pagertl)) {
										$page_rtl				= $Timeline_Pagertl;								
									} else {
										$page_rtl				= "false";
									}							
									$featured_slider 			= array();
									$featured_images			= array();
									$featured_maxheight			= (isset($Timeline_Slidermaxheight) ? $Timeline_Slidermaxheight : 400);
									$featured_fixheight			= (isset($Timeline_Sliderfixheight) ? $Timeline_Sliderfixheight : 400);
									if ($codestarRetrieve == "true") {
										$images					= $Timeline_Featuredslider;
										$images					= explode(",", $images);
										if ($images) {
											foreach ($images as $attachment_id) {
												$featured_slider[]	= $attachment_id;
											}
										}
									} else {
										$images 				= get_post_meta($Section_ID, 'ts_vcsc_timeline_media_featuredslider', true);
										if ($images) {
											foreach ($images as $attachment_id => $img_full_url) {
												$featured_slider[]	= $attachment_id;
											}
										}
									}
									$i 							= -1;
									$b							= 0;
									$nachoLength 				= count($featured_slider) - 1;								
									if (isset($Timeline_Slidertitles)) {
										$titles_array 			= explode("\n", $Timeline_Slidertitles);
										$titles_array 			= array_filter($titles_array, 'trim');
									} else {
										$titles_array			= array();
									}								
									$media_string .= '<div id="ts-timeline-css-imageslider-' . $randomizer . '" class="ts-timeline-css-imageslider-container ' . $vcinline_slider . '" style="" data-id="' . $randomizer . '" data-parent="' . $timeline_id . '" data-items="' . count($featured_slider) . '" data-maxheight="' . $featured_maxheight . '">';
										$media_string .= '<div id="ts-timeline-css-imageslider-slides-' . $randomizer . '" class="ts-timeline-css-imageslider-slides">';
											foreach ($featured_slider as $single_image) {
												$i++;
												if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_JetpackPhoton_Active == "true") {
													add_filter('jetpack_photon_override_image_downsize', '__return_true');
												}
												$modal_image				= wp_get_attachment_image_src($single_image, 'full');
												if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_JetpackPhoton_Active == "true") {
													remove_filter('jetpack_photon_override_image_downsize', '__return_true');
												}
												if ($modal_image != false) {
													$modal_thumb			= wp_get_attachment_image_src($single_image, 'thumb');
													$image_ratio			= $modal_image[1] / $modal_image[2];
													$image_height			= ($modal_image[2] > $featured_maxheight ? $featured_maxheight : $modal_image[2]);
													$image_width			= round($image_height * $image_ratio, 0);
													$data_width				= $modal_image[1];
													$data_height 			= $modal_image[2];
													$image_extension		= pathinfo($modal_image[0], PATHINFO_EXTENSION);
													$featured_images[]		= $modal_thumb[0];		
													if ($Timeline_Lightboxfeatured === "false") {
														if ((($i == 0) && ($vcinline_active == "true")) || ($vcinline_active == "false")) {
															$media_string .= '<div id="ts-timeline-css-imageslider-image-' . $single_image .'-parent" class="ts-timeline-css-imageslider-image-parent ts-timeline-css-media-nolightbox nchgrid-item nchgrid-tile nch-lightbox-image ts-timeline-css-imageslider-item ' . ($i == 0 ? "ts-timeline-css-slider-view-active" : "ts-timeline-css-slider-view-hidden") . '" data-width="' . $data_width . '" data-height="' . $data_height . '" data-ratio="' . $image_ratio . '" data-order="' . $i . '" data-total="' . count($featured_slider) . '">';
																$media_string .= '<img src="' . $modal_image[0] . '" style="max-width: ' . $image_width . 'px; max-height: ' . $image_height . 'px; padding: 0; margin: 0 auto; display: block; ' . $image_dimensions . '">';
																$media_string .= '<div class="nchgrid-caption"></div>';
																if ((isset($titles_array[$i])) && ($titles_array[$i] != '')) {
																	$media_string .= '<div class="nchgrid-caption-text">' . strip_tags($titles_array[$i]) . '</div>';
																}
															$media_string .= '</div>';
														}
													} else {
														if (($i == $nachoLength) && ($vcinline_active == "false")) {
															$media_string .= '<div id="ts-timeline-css-imageslider-image-' . $single_image .'-parent" class="ts-timeline-css-imageslider-image-parent ts-timeline-css-media-lightbox nchgrid-item nchgrid-tile nch-lightbox-image ts-timeline-css-imageslider-item ' . ($i == 0 ? "ts-timeline-css-slider-view-active" : "ts-timeline-css-slider-view-hidden") . '" data-width="' . $data_width . '" data-height="' . $data_height . '" data-ratio="' . $image_ratio . '" data-order="' . $i . '" data-total="' . count($featured_slider) . '">';
																$media_string .= '<a id="' . $timeline_id . '-' . $single_image .'" href="' . $modal_image[0] . '" data-thumbnail="' . $modal_image[0] . '" data-title="' . (((isset($titles_array[$i])) && ($titles_array[$i] != '')) ? strip_tags($titles_array[$i]) : '') . '" class="nch-lightbox-media no-ajaxy ts-hover-image ' . $timeline_id . '-slider-image" rel="' . ($Timeline_Lightboxgroup == "true" ? "timelinegroup" : (isset($Timeline_Lightboxgroupname) ? $Timeline_Lightboxgroupname : "")) . '" data-effect="' . $Timeline_Lightboxeffect . '" data-share="0" data-autoplay="0" data-duration="5000" data-thumbsize="100" data-thumbs="bottom" ' . $nacho_color . '>';
																	$media_string .= '<img src="' . $modal_image[0] . '" style="max-width: ' . $image_width . 'px; max-height: ' . $image_height . 'px; padding: 0; margin: 0 auto; display: block; ' . $image_dimensions . '">';
																	$media_string .= '<div class="nchgrid-caption"></div>';
																	if ((isset($titles_array[$i])) && ($titles_array[$i] != '')) {
																		$media_string .= '<div class="nchgrid-caption-text">' . strip_tags($titles_array[$i]) . '</div>';
																	}
																$media_string .= '</a>';
															$media_string .= '</div>';
														} else if ((($i == 0) && ($vcinline_active == "true")) || ($vcinline_active == "false")) {
															$media_string .= '<div id="ts-timeline-css-imageslider-image-' . $single_image .'-parent" class="ts-timeline-css-imageslider-image-parent ts-timeline-css-media-lightbox nchgrid-item nchgrid-tile nch-lightbox-image ts-timeline-css-imageslider-item ' . ($i == 0 ? "ts-timeline-css-slider-view-active" : "ts-timeline-css-slider-view-hidden") . '" data-width="' . $data_width . '" data-height="' . $data_height . '" data-ratio="' . $image_ratio . '" data-order="' . $i . '" data-total="' . count($featured_slider) . '">';
																$media_string .= '<a id="' . $timeline_id . '-' . $single_image .'" href="' . $modal_image[0] . '" data-thumbnail="' . $modal_image[0] . '" data-title="' . (((isset($titles_array[$i])) && ($titles_array[$i] != '')) ? strip_tags($titles_array[$i]) : '') . '" class="nch-lightbox-media no-ajaxy ts-hover-image ' . $timeline_id . '-slider-image" rel="' . ($Timeline_Lightboxgroup == "true" ? "timelinegroup" : (isset($Timeline_Lightboxgroupname) ? $Timeline_Lightboxgroupname : "")) . '" data-effect="' . $Timeline_Lightboxeffect . '" ' . $nacho_color . '>';
																	$media_string .= '<img src="' . $modal_image[0] . '" style="max-width: ' . $image_width . 'px; max-height: ' . $image_height . 'px; padding: 0; margin: 0 auto; display: block; ' . $image_dimensions . '">';
																	$media_string .= '<div class="nchgrid-caption"></div>';
																	if ((isset($titles_array[$i])) && ($titles_array[$i] != '')) {
																		$media_string .= '<div class="nchgrid-caption-text">' . strip_tags($titles_array[$i]) . '</div>';
																	}
																$media_string .= '</a>';
															$media_string .= '</div>';
														}
													}												
												}
											}
										$media_string .= '</div>';
										$media_string .= '<div class="ts-timeline-css-imageslider-navigation">';
											$media_string .= '<div class="ts-timeline-css-imageslider-dotholder">';
												$i = -1;
												foreach ($featured_slider as $single_image) {
													$i++;
													$media_string .= '<div class="ts-timeline-css-imageslider-dot ' . ($i == 0 ? "ts-timeline-css-imageslider-dotactive" : "") . ' ts-has-tooltipster-tooltip" data-order="' . $i . '" data-image="' . (isset($featured_images[$i]) ? $featured_images[$i] : "") . '" data-tooltipster-html="false" data-tooltipster-title="" data-tooltipster-text="" data-tooltipster-image="' . (isset($featured_images[$i]) ? $featured_images[$i] : "") . '" data-tooltipster-position="top" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="tooltipster-thumb" data-tooltipster-animation="fade" data-tooltipster-trigger="hover" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . $tooltipster_offsety . '"></div>';
												}
											$media_string .= '</div>';
											$media_string .= '<div class="ts-timeline-css-imageslider-prev"><i class="dashicons dashicons-arrow-left-alt2"></i></div>';
											$media_string .= '<div class="ts-timeline-css-imageslider-next"><i class="dashicons dashicons-arrow-right-alt2"></i></div>';
										$media_string .= '</div>';
									$media_string .= '</div>';
									$slider_class				= '';								
								}
							} else {
								$slider_class					= '';
							}
							// Featured Media: YouTube
							if (isset($Timeline_Featuredyoutubeurl) && (($Timeline_Featuredmedia == 'youtube_default') || ($Timeline_Featuredmedia == 'youtube_custom') || ($Timeline_Featuredmedia == 'youtube_embed'))) {
								if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $Timeline_Featuredyoutubeurl)) {
									$featured_youtube_url		= $Timeline_Featuredyoutubeurl;
								} else {
									$featured_youtube_url		= 'https://www.youtube.com/watch?v=' . $Timeline_Featuredyoutubeurl;
								}
								if (isset($Timeline_Featuredyoutubeplay)) {
									if ($Timeline_Featuredyoutubeplay == "true") {
										$video_autoplay			= 'true';
									} else {
										$video_autoplay			= 'false';
									}
								} else {
									$video_autoplay				= 'false';
								}
								if (isset($Timeline_Featuredyoutuberelated)) {
									if ($Timeline_Featuredyoutuberelated == "true") {
										$video_related			= '&rel=1';
									} else {
										$video_related			= '&rel=0';
									}
								} else {
									$video_related				= '&rel=0';
								}
								if (isset($Timeline_Attributetitle)) {
									$media_title 				= $Timeline_Attributetitle;
								} else if (isset($Timeline_Eventtitletext)) {
									$media_title 				= $Timeline_Eventtitletext;
								} else {
									$media_title				= '';
								}	
								if (($Timeline_Featuredmedia == 'youtube_default')) {
									$media_image 				= TS_VCSC_VideoImage_Youtube($featured_youtube_url, "high");
									$media_string .= '<div class="nch-holder nchgrid-item nchgrid-tile nch-lightbox-youtube" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
										$media_string .= '<a href="' . $featured_youtube_url . '" class="nch-lightbox-media no-ajaxy" data-thumbnail="' . $media_image . '" data-title="' . $media_title . '" data-related="' . $video_related . '" data-videoplay="' . $video_autoplay . '" data-type="youtube" rel="' . ($Timeline_Lightboxgroup == "true" ? "timelinegroup" : (isset($Timeline_Lightboxgroupname) ? $Timeline_Lightboxgroupname : "")) . '" data-share="0" data-effect="' . (isset($Timeline_Lightboxeffect) ? $Timeline_Lightboxeffect : 'random') . '" data-duration="5000" ' . $nacho_color . '>';
											$media_string .= '<img src="' . $media_image . '" title="" style="display: block; ' . $image_dimensions . '">';
											$media_string .= '<div class="nchgrid-caption"></div>';
											if ($media_title != '') {
												$media_string .= '<div class="nchgrid-caption-text">' . $media_title . '</div>';
											}
										$media_string .= '</a>';
									$media_string .= '</div>';
								} else if ($Timeline_Featuredmedia == 'youtube_custom') {
									if (isset($Timeline_Featuredimageid)) {
										$media_image			= wp_get_attachment_image_src($Timeline_Featuredimageid, 'full');
										$media_image			= $media_image[0];
										$image_extension		= pathinfo($media_image, PATHINFO_EXTENSION);
										if (isset($Timeline_Attributealtvalue)) {
											$alt_attribute		= $Timeline_Attributealtvalue;
										} else {
											$alt_attribute		= basename($media_image[0]);
										}
									} else {
										$media_image			= TS_VCSC_GetResourceURL('images/defaults/default_youtube.jpg');
										$image_extension		= pathinfo($media_image, PATHINFO_EXTENSION);
										if (isset($Timeline_Attributealtvalue)) {
											$alt_attribute		= $Timeline_Attributealtvalue;
										} else {
											$alt_attribute		= basename($media_image);
										}
									}
									$media_string .= '<div class="nch-holder nchgrid-item nchgrid-tile nch-lightbox-youtube" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
										$media_string .= '<a href="' . $featured_youtube_url . '" class="nch-lightbox-media no-ajaxy" data-thumbnail="' . $media_image . '" data-title="' . $media_title . '" data-related="' . $video_related . '" data-videoplay="' . $video_autoplay . '" data-type="youtube" rel="' . ($Timeline_Lightboxgroup == "true" ? "timelinegroup" : (isset($Timeline_Lightboxgroupname) ? $Timeline_Lightboxgroupname : "")) . '" data-share="0" data-effect="' . (isset($Timeline_Lightboxeffect) ? $Timeline_Lightboxeffect : 'random') . '" data-duration="5000" ' . $nacho_color . '>';
											$media_string .= '<img src="' . $media_image . '" title="" style="display: block; ' . $image_dimensions . '">';
											$media_string .= '<div class="nchgrid-caption"></div>';
											if ($media_title != '') {
												$media_string .= '<div class="nchgrid-caption-text">' . $media_title . '</div>';
											}
										$media_string .= '</a>';
									$media_string .= '</div>';
								} else if ($Timeline_Featuredmedia == 'youtube_embed') {									
									$video_id 					= TS_VCSC_VideoID_Youtube($featured_youtube_url);
									if ($video_autoplay == "true") {
										$video_autoplay			= '?autoplay=1';
									} else {
										$video_autoplay			= '?autoplay=0';
									}
									$media_string .= '<div class="ts-video-container" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
										$media_string .= '<iframe width="100%" height="auto" src="//www.youtube.com/embed/' . $video_id . $video_autoplay . $video_related . '&wmode=opaque" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
									$media_string .= '</div>';
								}
							}
							// Featured Media: DailyMotion
							if (isset($Timeline_Featureddailymotionurl) && (($Timeline_Featuredmedia == 'dailymotion_default') || ($Timeline_Featuredmedia == 'dailymotion_custom') || ($Timeline_Featuredmedia == 'dailymotion_embed'))) {
								if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $Timeline_Featureddailymotionurl)) {
									$featured_dailymotion_url	= $Timeline_Featureddailymotionurl;
								} else {
									$featured_dailymotion_url	= 'http://www.dailymotion.com/video/' . $Timeline_Featureddailymotionurl;
								}
								if (isset($Timeline_Featureddailymotionplay)) {
									if ($Timeline_Featureddailymotionplay == "true") {
										$video_autoplay			= 'true';
									} else {
										$video_autoplay			= 'false';
									}
								} else {
									$video_autoplay				= 'false';
								}
								if (isset($Timeline_Attributetitle)) {
									$media_title 				= $Timeline_Attributetitle;
								} else if (isset($Timeline_Eventtitletext)) {
									$media_title 				= $Timeline_Eventtitletext;
								} else {
									$media_title				= '';
								}	
								if (($Timeline_Featuredmedia == 'dailymotion_default')) {
									$media_image 				= TS_VCSC_VideoImage_Motion($featured_dailymotion_url);
									$media_string .= '<div class="nch-holder nchgrid-item nchgrid-tile nch-lightbox-motion" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
										$media_string .= '<a href="' . $featured_dailymotion_url . '" class="nch-lightbox-media no-ajaxy" data-thumbnail="' . $media_image . '" data-title="' . $media_title . '" data-videoplay="' . $video_autoplay . '" data-type="dailymotion" rel="' . ($Timeline_Lightboxgroup == "true" ? "timelinegroup" : (isset($Timeline_Lightboxgroupname) ? $Timeline_Lightboxgroupname : "")) . '" data-share="0" data-effect="' . (isset($Timeline_Lightboxeffect) ? $Timeline_Lightboxeffect : 'random') . '" data-duration="5000" ' . $nacho_color . '>';
											$media_string .= '<img src="' . $media_image . '" title="" style="display: block; ' . $image_dimensions . '">';
											$media_string .= '<div class="nchgrid-caption"></div>';
											if ($media_title != '') {
												$media_string .= '<div class="nchgrid-caption-text">' . $media_title . '</div>';
											}
										$media_string .= '</a>';
									$media_string .= '</div>';
								} else if ($Timeline_Featuredmedia == 'dailymotion_custom') {
									if (isset($Timeline_Featuredimageid)) {
										$media_image			= wp_get_attachment_image_src($Timeline_Featuredimageid, 'full');
										$media_image			= $media_image[0];
										$image_extension		= pathinfo($media_image, PATHINFO_EXTENSION);
										if (isset($Timeline_Attributealtvalue)) {
											$alt_attribute		= $Timeline_Attributealtvalue;
										} else {
											$alt_attribute		= basename($media_image[0]);
										}
									} else {
										$media_image			= TS_VCSC_GetResourceURL('images/defaults/default_motion.jpg');
										$image_extension		= pathinfo($media_image, PATHINFO_EXTENSION);
										if (isset($Timeline_Attributealtvalue)) {
											$alt_attribute		= $Timeline_Attributealtvalue;
										} else {
											$alt_attribute		= basename($media_image);
										}
									}
									$media_string .= '<div class="nch-holder nchgrid-item nchgrid-tile nch-lightbox-motion" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
										$media_string .= '<a href="' . $featured_dailymotion_url . '" class="nch-lightbox-media no-ajaxy" data-thumbnail="' . $media_image . '" data-title="' . $media_title . '" data-videoplay="' . $video_autoplay . '" data-type="dailymotion" rel="' . ($Timeline_Lightboxgroup == "true" ? "timelinegroup" : (isset($Timeline_Lightboxgroupname) ? $Timeline_Lightboxgroupname : "")) . '" data-share="0" data-effect="' . (isset($Timeline_Lightboxeffect) ? $Timeline_Lightboxeffect : 'random') . '" data-duration="5000" ' . $nacho_color . '>';
											$media_string .= '<img src="' . $media_image . '" title="" style="display: block; ' . $image_dimensions . '">';
											$media_string .= '<div class="nchgrid-caption"></div>';
											if ($media_title != '') {
												$media_string .= '<div class="nchgrid-caption-text">' . $media_title . '</div>';
											}
										$media_string .= '</a>';
									$media_string .= '</div>';
								} else if ($Timeline_Featuredmedia == 'dailymotion_embed') {
									$video_id 					= TS_VCSC_VideoID_Motion($featured_dailymotion_url);
									if ($video_autoplay == "true") {
										$video_autoplay			= '?autoplay=1';
									} else {
										$video_autoplay			= '?autoplay=0';
									}
									$media_string .= '<div class="ts-video-container" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
										$media_string .= '<iframe width="100%" height="auto" src="http://www.dailymotion.com/embed/video/' . $video_id . $video_autoplay . '&forcedQuality=hq&wmode=opaque" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
									$media_string .= '</div>';
								}
							}
							// Featured Media: Vimeo
							if (isset($Timeline_Featuredvimeourl) && (($Timeline_Featuredmedia == 'vimeo_default') || ($Timeline_Featuredmedia == 'vimeo_custom') || ($Timeline_Featuredmedia == 'vimeo_embed'))) {
								if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $Timeline_Featuredvimeourl)) {
									$featured_vimeo_url			= $Timeline_Featuredvimeourl;
								} else {
									$featured_vimeo_url			= 'http://www.vimeo.com/video/' . $Timeline_Featuredvimeourl;
								}
								if (isset($Timeline_Featuredvimeoplay)) {
									if ($Timeline_Featuredvimeoplay == "true") {
										$video_autoplay			= 'true';
									} else {
										$video_autoplay			= 'false';
									}
								} else {
									$video_autoplay				= 'false';
								}
								if (isset($Timeline_Attributetitle)) {
									$media_title 				= $Timeline_Attributetitle;
								} else if (isset($Timeline_Eventtitletext)) {
									$media_title 				= $Timeline_Eventtitletext;
								} else {
									$media_title				= '';
								}	
								if (($Timeline_Featuredmedia == 'vimeo_default')) {
									$media_image 				= TS_VCSC_VideoImage_Vimeo($featured_vimeo_url);
									$media_string .= '<div class="nch-holder nchgrid-item nchgrid-tile nch-lightbox-vimeo" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
										$media_string .= '<a href="' . $featured_vimeo_url . '" class="nch-lightbox-media no-ajaxy" data-thumbnail="' . $media_image . '" data-title="' . $media_title . '" data-videoplay="' . $video_autoplay . '" data-type="vimeo" rel="' . ($Timeline_Lightboxgroup == "true" ? "timelinegroup" : (isset($Timeline_Lightboxgroupname) ? $Timeline_Lightboxgroupname : "")) . '" data-share="0" data-effect="' . (isset($Timeline_Lightboxeffect) ? $Timeline_Lightboxeffect : 'random') . '" data-duration="5000" ' . $nacho_color . '>';
											$media_string .= '<img src="' . $media_image . '" title="" style="display: block; ' . $image_dimensions . '">';
											$media_string .= '<div class="nchgrid-caption"></div>';
											if ($media_title != '') {
												$media_string .= '<div class="nchgrid-caption-text">' . $media_title . '</div>';
											}
										$media_string .= '</a>';
									$media_string .= '</div>';
								} else if ($Timeline_Featuredmedia == 'vimeo_custom') {
									if (isset($Timeline_Featuredimageid)) {
										$media_image			= wp_get_attachment_image_src($Timeline_Featuredimageid, 'full');
										$media_image			= $media_image[0];
										$image_extension		= pathinfo($media_image, PATHINFO_EXTENSION);
										if (isset($Timeline_Attributealtvalue)) {
											$alt_attribute		= $Timeline_Attributealtvalue;
										} else {
											$alt_attribute		= basename($media_image[0]);
										}
									} else {
										$media_image			= TS_VCSC_GetResourceURL('images/defaults/default_vimeo.jpg');
										$image_extension		= pathinfo($media_image, PATHINFO_EXTENSION);
										if (isset($Timeline_Attributealtvalue)) {
											$alt_attribute		= $Timeline_Attributealtvalue;
										} else {
											$alt_attribute		= basename($media_image);
										}
									}
									$media_string .= '<div class="nch-holder nchgrid-item nchgrid-tile nch-lightbox-vimeo" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
										$media_string .= '<a href="' . $featured_vimeo_url . '" class="nch-lightbox-media no-ajaxy" data-thumbnail="' . $media_image . '" data-title="' . $media_title . '" data-videoplay="' . $video_autoplay . '" data-type="vimeo" rel="' . ($Timeline_Lightboxgroup == "true" ? "timelinegroup" : (isset($Timeline_Lightboxgroupname) ? $Timeline_Lightboxgroupname : "")) . '" data-share="0" data-effect="' . (isset($Timeline_Lightboxeffect) ? $Timeline_Lightboxeffect : 'random') . '" data-duration="5000" ' . $nacho_color . '>';
											$media_string .= '<img src="' . $media_image . '" title="" style="display: block; ' . $image_dimensions . '">';
											$media_string .= '<div class="nchgrid-caption"></div>';
											if ($media_title != '') {
												$media_string .= '<div class="nchgrid-caption-text">' . $media_title . '</div>';
											}
										$media_string .= '</a>';
									$media_string .= '</div>';
								} else if ($Timeline_Featuredmedia == 'vimeo_embed') {
									$video_id 					= TS_VCSC_VideoID_vimeo($featured_vimeo_url);
									if ($video_autoplay == "true") {
										$video_autoplay			= '?autoplay=1';
									} else {
										$video_autoplay			= '?autoplay=0';
									}
									$media_string .= '<div class="ts-video-container" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
										$media_string .= '<iframe width="100%" height="auto" src="//player.vimeo.com/video/' . $video_id . $video_autoplay . '&wmode=opaque" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
									$media_string .= '</div>';
								}
							}
						}
						// Link Button
						if (isset($Timeline_Dedicatedpage) && ($Timeline_Dedicatedpage != -1)) {
							// Link Values
							if ($Timeline_Dedicatedpage == "external") {
								$a_href						= $Timeline_Dedicatedlink;
							} else {
								$a_href						= get_page_link($Timeline_Dedicatedpage);
							}
							if (isset($Timeline_Dedicatedtooltip)) {
								$a_title 					= $Timeline_Dedicatedtooltip;
							} else {
								$a_title					= "";
							}
							if (isset($Timeline_Dedicatedtarget)) {
								if ($Timeline_Dedicatedtarget == "true") {
									$a_target				= "_blank";
								} else {
									$a_target				= "_parent";
								}
							} else {
								$a_target					= "_blank";
							}
							// Button Alignment
							if (isset($Timeline_Dedicatedalign)) {
								if ($Timeline_Dedicatedalign == "center") {
									$buttonstyle			= "width: " . (isset($Timeline_Dedicatedwidth) ? $Timeline_Dedicatedwidth : 100) . "%; margin: 0 auto; float: none;";
								} else if ($Timeline_Dedicatedalign == "left") {
									$buttonstyle			= "width: " . (isset($Timeline_Dedicatedwidth) ? $Timeline_Dedicatedwidth : 100) . "%; margin: 0 auto; float: left;";
								} else if ($Timeline_Dedicatedalign == "right") {
									$buttonstyle			= "width: " . (isset($Timeline_Dedicatedwidth) ? $Timeline_Dedicatedwidth : 100) . "%; margin: 0 auto; float: right;";
								}
							} else {
								$buttonstyle				= 'width: 100%; margin: 0 auto; float: none;';
							}
							$button_string					= '';					
							if ((!empty($a_href)) && isset($Timeline_Dedicatedlabel)) {
								$button_string .= '<div class="ts-timeline-css-button-outer clearFixMe">';
									$button_string .= '<div class="ts-timeline-css-button-wrapper" style="' . $buttonstyle . '%;">';
									if (isset($Timeline_Dedicatedicon)) {
										if ($Timeline_Dedicatedicon != "none") {
											$button_string .= '<a class="ts-timeline-css-button-link ' . $Timeline_Dedicateddefault . ' ' . $Timeline_Dedicatedhover . '" href="' . $a_href . '" target="' . $a_target . '" title="' . $a_title . '"><i class="ts-timeline-css-button-icon dashicons dashicons-' . $Timeline_Dedicatedicon . '" style="' . (isset($Timeline_Dedicatedcolor) ? "color: " . $Timeline_Dedicatedcolor : "") . '"></i>' . $Timeline_Dedicatedlabel . '</a>';
										} else {
											$button_string .= '<a class="ts-timeline-css-button-link ' . $Timeline_Dedicateddefault . ' ' . $Timeline_Dedicatedhover . '" href="' . $a_href . '" target="' . $a_target . '" title="' . $a_title . '">' . $Timeline_Dedicatedlabel . '</a>';
										}
									} else {
										$button_string .= '<a class="ts-timeline-css-button-link ' . $Timeline_Dedicateddefault . ' ' . $Timeline_Dedicatedhover . '" href="' . $a_href . '" target="' . $a_target . '" title="' . $a_title . '">' . $Timeline_Dedicatedlabel . '</a>';
									}
									$button_string .= '</div>';
								$button_string .= '</div>';						
							} else {
								$button_string				= '';
							}
						} else {
							$button_string					= '';
						}						
						// Event Icon	
						if (((empty($section_icon)) || ($section_icon == "transparent")) && (!isset($Timeline_Eventcontent)) && (!isset($Timeline_Featuredimage))) {
							$title_margin					= 'margin: 0;';
						} else {
							$title_margin					= '';
						}						
						// Column Adjustment for Full Width Event
						if (($Timeline_Fullwidth == "true") && (!isset($Timeline_Eventtitletext)) && (empty($section_icon) || ($section_icon == "transparent")) && (!isset($Timeline_Eventcontent)) && (!isset($Timeline_Buttontext))) {
							$columnA_adjust					= 'width: 100%; margin: 0;';
							$columnB_adjust					= 'display: none; width: 0;';
						} else if (($Timeline_Fullwidth == "true") && ($Timeline_Featuredmedia == "none")) {
							$columnA_adjust					= 'display: none; width: 0; margin: 0;';
							$columnB_adjust					= 'width: 100%; margin: 0;';
						} else {
							$columnA_adjust					= '';
							$columnB_adjust					= '';
						}						
						// Final Output
						$output .= '<div id="' . $timeline_id . '" class="' . $css_class . ' ' . $slider_class . ' ' . ($Timeline_Fullwidth == "true" ? "ts-timeline-css-fullwidth" : "ts-timeline-css-event") . ' ts-timeline-css-visible ts-timeline-css-animated ' . (isset($Timeline_Eventdatetext) ? "ts-timeline-css-date-true" : "ts-timeline-css-date-false") . '" style="' . ($Timeline_Fullwidth == "true" ? "width: 100%;" : "") . ' ' . $vc_inline_style . '" data-categories="' . $Section_Categories . '" data-tags="' . $Section_Tags . '" data-filtered-categories="false" data-filtered-tags="false">';
							$output .= '<div class="ts-timeline-css-text-wrap ' . (isset($Timeline_Eventdatetext) ? "ts-timeline-css-text-wrap-date" : "ts-timeline-css-text-wrap-nodate") . ' ' . $tooltip_class . '" ' . $tooltip_content . ' style="' . (isset($Timeline_Eventdatetext) ? "padding-top: 40px;" : "") . ' ' . $style_event_background . '">';
								if (isset($Timeline_Eventdatetext)) {
									if (isset($Timeline_Eventdateicon)) {
										if ($Timeline_Eventdateicon == "none") {
											$output .= '<div class="ts-timeline-css-date" style="' . $style_datetime_background . '"><span class="ts-timeline-css-date-connect"><span class="ts-timeline-css-date-text" style="' . $style_datetime_textcolor . '">' . $Timeline_Eventdatetext . '</span></span></div>';
										} else {
											$output .= '<div class="ts-timeline-css-date" style="' . $style_datetime_background . '"><span class="ts-timeline-css-date-connect"><span class="ts-timeline-css-date-text" style="' . $style_datetime_textcolor . '"><i class="ts-timeline-css-date-icon dashicons dashicons-' . $Timeline_Eventdateicon . '" style="' . $style_datetime_iconcolor . '"></i>' . $Timeline_Eventdatetext . '</span></span></div>';
										}
									} else {
										$output .= '<div class="ts-timeline-css-date" style="' . $style_datetime_background . '"><span class="ts-timeline-css-date-connect"><span class="ts-timeline-css-date-text" style="' . $style_datetime_textcolor . '">' . $Timeline_Eventdatetext . '</span></span></div>';
									}
								}
								if ($Timeline_Fullwidth == "true") {
									$output .= '<div class="ts-timeline-css-fullwidth-colA" style="' . $columnA_adjust . '">';
										$output .= $media_string;
									$output .= '</div>';
									$output .= '<div class="ts-timeline-css-fullwidth-colB" style="' . $columnB_adjust . '">';
										if (isset($Timeline_Eventtitletext)) {
											if ($Timeline_Eventtitletext != "") {
												$output .= '<h3 class="ts-timeline-css-title" style="' . $style_event_titlecolor . ' text-align: ' . (isset($Timeline_Eventtitlealign) ? $Timeline_Eventtitlealign : 'center') . '; ' . (!isset($Timeline_Eventcontent) && (empty($section_icon)) ? "border: none; margin-bottom: 0; padding-bottom: 0;" : "") . ' ' . $title_margin . '">' . $Timeline_Eventtitletext . '</h3>';
											}
										}
										if (((!empty($section_icon)) && (($section_icon) != "transparent") && (($section_icon) != "")) || (isset($Timeline_Eventcontent))) {
											$output .= '<div class="ts-timeline-css-details ' . (((!isset($Timeline_Eventtitletext)) || ($Timeline_Eventtitletext == "")) ? "ts-timeline-css-details-border" : "") . '" style="padding-bottom: ' . (((!empty($a_href)) && (!empty($button_string))) ? 0 : 15) . 'px; ' . (!isset($Timeline_Eventcontent) && !empty($section_icon) ? "height: 60px;" : "") . '">';
												if (isset($Timeline_Eventcontent)) {
													$output .= '<div class="ts-timeline-css-text-wrap-inner" style="' . $style_event_contentcolor . '; ' . (empty($section_icon) ? "width: 100%; height: 100%; left: 0;" : " left: 0;") . '">';
														if (function_exists('wpb_js_remove_wpautop')){
															$output .= '<div class="ts-timeline-css-text" style="">' . wpb_js_remove_wpautop(do_shortcode($Timeline_Eventcontent), $wpautop) . '</div>';
														} else {
															$output .= '<div class="ts-timeline-css-text" style="">' . do_shortcode($Timeline_Eventcontent) . '</div>';
														}
													$output .= '</div>';
												}
												if ((!empty($section_icon)) && (($section_icon) != "transparent") && (($section_icon) != "")) {
													$output .= '<div class="ts-timeline-css-icon ts-timeline-css-icon-full" style="' . (!isset($Timeline_Eventcontent) ? "display: inline-block; width: 100%; left: 0; margin: 0 0 0 2%;" : "left: 80%;") . '"><i class="' . $section_icon . '" style="color: ' . $icon_color . ';"></i></div>';
												}
												if ((!empty($a_href)) && (!empty($button_string))) {
													$output .= '<div class="ts-timeline-css-button-container">';
														$output .= $button_string;
													$output .= '</div>';
												}
											$output .= '</div>';
										}
									$output .= '</div>';
									if ($Section_Tags != '') {
										$output .= '<div class="ts-timeline-css-output-tags"><i class="dashicons dashicons-tag"></i><span>' . str_replace(",", ", ", $Section_Tags) . '</span></div>';
									}
									if ($Section_Categories != '') {
										$output .= '<div class="ts-timeline-css-output-cats"><i class="dashicons dashicons-category"></i><span>' . str_replace(",", ", ", $Section_Categories) . '</span></div>';
									}
								} else {
									$output .= $media_string;
									if (isset($Timeline_Eventtitletext)) {
										if ($Timeline_Eventtitletext != "") {
											$output .= '<h3 class="ts-timeline-css-title" style="' . $style_event_titlecolor . '; text-align: ' . (isset($Timeline_Eventtitlealign) ? $Timeline_Eventtitlealign : 'center') . '; ' . (!isset($Timeline_Eventcontent) && (empty($section_icon)) ? "border: none; margin-bottom: 0; padding-bottom: 0;" : "") . ' ' . $title_margin . '">' . $Timeline_Eventtitletext . '</h3>';
										}
									}
									if (((!empty($section_icon)) && (($section_icon) != "transparent") && (($section_icon) != "")) || (isset($Timeline_Eventcontent))) {
										$output .= '<div class="ts-timeline-css-details ' . (((!isset($Timeline_Eventtitletext)) || ($Timeline_Eventtitletext == "")) ? "ts-timeline-css-details-border" : "") . '"style="padding-bottom: 15px; ' . (!isset($Timeline_Eventcontent) && !empty($section_icon) ? "height: 60px;" : "") . '">';
											if ((!empty($section_icon)) && (($section_icon) != "transparent") && (($section_icon) != "")) {
												$output .= '<div class="ts-timeline-css-icon ts-timeline-css-icon-half" style="' . (!isset($Timeline_Eventcontent) ? "display: inline-block; width: 100%; left: 0;" : "") . '"><i class="' . $section_icon . '" style="color: ' . $icon_color . ';"></i></div>';
											}
											if (isset($Timeline_Eventcontent)) {
												$output .= '<div class="ts-timeline-css-text-wrap-inner" style="' . $style_event_contentcolor . '; ' . (empty($section_icon) ? "width: 100%; height: 100%; left: 0;" : "") . '">';
													if (function_exists('wpb_js_remove_wpautop')){
														$output .= '<div class="ts-timeline-css-text" style="">' . wpb_js_remove_wpautop(do_shortcode($Timeline_Eventcontent), $wpautop) . '</div>';
													} else {
														$output .= '<div class="ts-timeline-css-text" style="">' . do_shortcode($Timeline_Eventcontent) . '</div>';
													}
												$output .= '</div>';
											}
										$output .= '</div>';
										if ((!empty($a_href)) && (!empty($button_string))) {
											$output .= '<div class="ts-timeline-css-button-container">';
												$output .= $button_string;
											$output .= '</div>';
										}
									}
									if ($Section_Tags != '') {
										$output .= '<div class="ts-timeline-css-output-tags"><i class="dashicons dashicons-tag"></i><span>' . str_replace(",", ", ", $Section_Tags) . '</span></div>';
									}
									if ($Section_Categories != '') {
										$output .= '<div class="ts-timeline-css-output-cats"><i class="dashicons dashicons-category"></i><span>' . str_replace(",", ", ", $Section_Categories) . '</span></div>';
									}
								}
								$output .= '<div class="clearFixMe"></div>';
							$output .= '</div>';
						$output .= '</div>';				
					}
					// Timeline Break
					if ($Timeline_Type == "break") {
						if (!isset($Timeline_Breakcontent)) {
							$title_margin					= ' margin: 0 !important;';
						} else {
							$title_margin					= '';
						}
						if (isset($Timeline_Breakfull)) {
							if ($Timeline_Breakfull == "true") {
								$break_width				= 'width: 100%; margin-left: auto; margin-right: auto;';
								$break_data					= 'true';
							} else {
								$break_width				= 'width: 50%;';
								$break_data					= 'false';
							}
						} else {
							$break_width					= 'width: 50%;';
							$break_data						= 'false';
						}
						$output .= '<div id="' . $timeline_id . '" class="ts-timeline-css-break ts-timeline-css-visible ' . $css_class . '" style="' . $break_width . ' ' . $vc_inline_style . '" data-fullwidth="' . $break_data . '" data-categories="' . $Section_Categories . '" data-tags="' . $Section_Tags . '" data-filtered-categories="false" data-filtered-tags="false">';
							$output .= '<div class="ts-timeline-css-text-wrap ' . $tooltip_class . '" ' . $tooltip_content . ' style="' . $style_break_background . '">';
								$output .= '<div class="ts-timeline-css-text-wrap-inner" style="width: 100%; left: 0; ' . $title_margin . '">';
									if (isset($Timeline_Breaktitletext)) {
										if ($Timeline_Breaktitletext != "") {
											$output .= '<h3 class="ts-timeline-css-title" style="padding: 0 10px; text-align: ' . (isset($Timeline_Breaktitlealign) ? $Timeline_Breaktitlealign : 'center') . '; ' . $style_break_titlecolor . ';' . $title_margin . '">' . $Timeline_Breaktitletext . '</h3>';
										}
									}
									if ((!empty($section_icon)) && (($section_icon) != "transparent") && (($section_icon) != "")) {
										$output .= '<div class="ts-timeline-css-icon ts-timeline-css-icon-break" style="margin: 10px auto;"><i class="' . $section_icon . '" style="color: ' . $icon_color . ';"></i></div>';
									}
									if (isset($Timeline_Breakcontent)) {
										if (function_exists('wpb_js_remove_wpautop')){
											$output .= '<div class="ts-timeline-css-text" style="' . $style_break_contentcolor . '">' . wpb_js_remove_wpautop(do_shortcode($Timeline_Breakcontent), $wpautop) . '</div>';
										} else {
											$output .= '<div class="ts-timeline-css-text" style="' . $style_break_contentcolor . '">' . do_shortcode($Timeline_Breakcontent) . '</div>';
										}
									}
								$output .= '</div>';
								$output .= '<div class="clearFixMe"></div>';
							$output .= '</div>';
						$output .= '</div>';
					}
				} else {
					$output .= '<div id="' . $timeline_id . '" class="' . $css_class . ' ts-timeline-css-fullwidth" style="width: 98%; ' . $vc_inline_style . '">';
						$output .= '<div class="ts-timeline-css-text-wrap">';
							$output .= '<div class="ts-timeline-css-text-wrap-inner" style="width: 100%; left: 0; margin: 20px auto;">';
								$output .= '<div>Section ID: ' . $Section_ID . '</div>';
								$output .= '<div>Section Title: ' . $Section_Title . '</div>';
								$output .= '<div>Section Type: ' . (($Timeline_Type == "event") ? "Event" : "Break") . '</div>';
							$output .= '</div>';
							$output .= '<div class="clearFixMe"></div>';
						$output .= '</div>';
					$output .= '</div>';
				}
				
				echo $output;
				
				// Turn Photon Back On
				/*if ($photon_removed) {
					add_filter('image_downsize', array(Jetpack_Photon::instance(), 'filter_image_downsize'), 10, 3);
				}
				$photon_removed 					= '';*/
				
				// Clear Out all Variables
				foreach ($custom_fields_array as $index => $array) {
					${$custom_fields_array[$index]['name']} = "";
					unset(${$custom_fields_array[$index]['name']});
				}
				$custom_fields_array				= '';
				$timeline_array						= '';
				$category_fields 	                = '';
				$media_string						= '';
				$output 							= '';
				
				$myvariable = ob_get_clean();
				return $myvariable;
			}
			
			// Timeline Break Section
			function TS_VCSC_Timeline_CSS_Function_Break ($atts, $content = null){
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();
				
				extract( shortcode_atts( array(
					// Main Settings
					'break_fullwidth' 				=> 'false',
					'break_title' 					=> '',
					'break_titlealign' 				=> 'center',
					'break_icon' 					=> '',
					'break_iconcolor' 				=> '#7c7979',
					// Categories & Tags
					'break_catsold'					=> '',
					'break_catsnew'					=> '',
					'break_tagsold'					=> '',
					'break_tagsnew'					=> '',
					// Customize Settings
					'break_customize' 				=> 'false',
					'break_colortitle' 				=> '#676767',
					'break_colorcontent' 			=> '#676767',
					'break_colorback' 				=> '#dadada',
					// Other Settings
					'content_wpautop'				=> 'false',	
					'el_id' 						=> '',
					'el_class'                  	=> '',
					'css'							=> '',
				), $atts ));
				
				$output								= "";
				$wpautop 							= ($content_wpautop == "true" ? true : false);
				$randomizer							= mt_rand(999999, 9999999);
				
				$style_break_background				= '';
				$style_break_contentcolor			= '';
				$style_break_titlecolor				= '';
				if ($break_customize == "true") {
					$style_break_background			= 'background: ' . $break_colorback . ';';
					$style_break_contentcolor		= 'color: ' . $break_colorcontent . ';';
					$style_break_titlecolor			= 'color: ' . $break_colortitle . ';';
				}
				
				if (!empty($el_id)) {
					$timeline_id					= $el_id;
				} else {
					$timeline_id					= 'ts-vcsc-timeline-section-' . $randomizer;
				}
				
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-timeline-css-section ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Timeline_CSS_Break', $atts);
				} else {
					$css_class						= 'ts-timeline-css-section ' . $el_class;
				}
				
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					$vc_inline						= 'true';
					$vc_inline_style				= ' display: block;';
				} else {
					$vc_inline						= 'false';
					$vc_inline_style				= '';
				}
				
				// Categories / Tags
				if (($break_catsold == "") && ($break_catsnew == "")) {
					$break_catstotal				= 'No Categories';
				} else if (($break_catsold != "") && ($break_catsnew != "")) {
					$break_catstotal				= $break_catsold . ',' . $break_catsnew;
				} else if (($break_catsold == "") && ($break_catsnew != "")) {
					$break_catstotal				= $break_catsnew;
				} else if (($break_catsold != "") && ($break_catsnew == "")) {
					$break_catstotal				= $break_catsold;
				}				
				if (($break_tagsold == "") && ($break_tagsnew == "")) {
					$break_tagstotal				= 'No Tags';
				} else if (($break_tagsold != "") && ($break_tagsnew != "")) {
					$break_tagstotal				= $break_tagsold . ',' . $break_tagsnew;
				} else if (($break_tagsold == "") && ($break_tagsnew != "")) {
					$break_tagstotal				= $break_tagsnew;
				} else if (($break_tagsold != "") && ($break_tagsnew == "")) {
					$break_tagstotal				= $break_tagsold;
				}

				// Final Output
				if ($vc_inline == "false") {
					if (!isset($content) && ($content != null)) {
						$title_margin				= ' margin: 0 !important;';
					} else {
						$title_margin				= '';
					}
					if (isset($break_fullwidth)) {
						if ($break_fullwidth == "true") {
							$break_width			= 'width: 100%; margin-left: auto; margin-right: auto;';
							$break_data				= 'true';
						} else {
							$break_width			= 'width: 50%;';
							$break_data				= 'false';
						}
					} else {
						$break_width				= 'width: 50%;';
						$break_data					= 'false';
					}
					$output .= '<div id="' . $timeline_id . '" class="ts-timeline-css-break ts-timeline-css-visible ' . $css_class . '" style="' . $break_width . ' ' . $vc_inline_style . '" data-fullwidth="' . $break_data . '" data-categories="' . $break_catstotal . '" data-tags="' . $break_tagstotal . '" data-filtered-categories="false" data-filtered-tags="false">';
						$output .= '<div class="ts-timeline-css-text-wrap" style="' . $style_break_background . '">';
							$output .= '<div class="ts-timeline-css-text-wrap-inner" style="width: 100%; left: 0; ' . $title_margin . '">';
								if ($break_title != "") {
									$output .= '<h3 class="ts-timeline-css-title" style="padding: 0 10px; text-align: ' . $break_titlealign . '; ' . $style_break_titlecolor . ';' . $title_margin . '">' . $break_title . '</h3>';
								}
								if ((!empty($break_icon)) && (($break_icon) != "transparent")) {
									$output .= '<div class="ts-timeline-css-icon ts-timeline-css-icon-break" style="margin: 10px auto;"><i class="' . $break_icon . '" style="color: ' . $break_iconcolor . ';"></i></div>';
								}
								if ($content != "") {
									if (function_exists('wpb_js_remove_wpautop')){
										$output .= '<div class="ts-timeline-css-text" style="' . $style_break_contentcolor . '">' . wpb_js_remove_wpautop(do_shortcode($content), $wpautop) . '</div>';
									} else {
										$output .= '<div class="ts-timeline-css-text" style="' . $style_break_contentcolor . '">' . do_shortcode($content) . '</div>';
									}
								}
							$output .= '</div>';
							$output .= '<div class="clearFixMe"></div>';
						$output .= '</div>';
					$output .= '</div>';
				} else {
					$output .= '<div id="' . $timeline_id . '" class="' . $css_class . ' ts-timeline-css-fullwidth" style="width: 98%; ' . $vc_inline_style . '">';
						$output .= '<div class="ts-timeline-css-text-wrap">';
							$output .= '<div class="ts-timeline-css-text-wrap-inner" style="width: 100%; left: 0; margin: 20px auto;">';
								$output .= '<div>Section ID: Direct</div>';
								$output .= '<div>Section Title: ' . $break_title . '</div>';
								$output .= '<div>Section Type: Break</div>';
							$output .= '</div>';
							$output .= '<div class="clearFixMe"></div>';
						$output .= '</div>';
					$output .= '</div>';
				}
				
				echo $output;
				
				$myvariable = ob_get_clean();
				return $myvariable;
			}
			
			// Timeline Event Section
			function TS_VCSC_Timeline_CSS_Function_Event ($atts, $content = null){
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();
				
				extract( shortcode_atts( array(
					// Main Settings
					'event_fullwidth' 				=> 'false',
					'event_date' 					=> '',
					'event_dateicon' 				=> '',
					'event_title' 					=> '',
					'event_titlealign' 				=> 'center',
					'event_icon' 					=> '',
					'event_iconcolor' 				=> '#7c7979',
					// Featured Media
					'media_type'					=> 'none',									
					'media_image'					=> '',
					'media_slider'					=> '',
					'media_lightboximage'			=> 'false',
					'media_lightboxvideo'			=> 'false',
					'media_singletitle'				=> '',
					'media_singlealt'				=> '',
					'media_grouptitle'				=> '',
					'media_groupalt'				=> '',					
					'media_videoyoutube'			=> '',
					'media_videovimeo'				=> '',
					'media_videomotion'				=> '',
					'media_videocustom'				=> 'false',
					'media_videocover'				=> '',
					'media_videorelated'			=> 'false',
					'media_videoauto'				=> 'false',					
					'media_height'					=> 'height: 100%;',
					'media_heightmax'				=> 400,
					'media_width'					=> 100,
					'media_align'					=> 'center',
					// Lightbox Settings
					'lightbox_groupauto'			=> 'true',
					'lightbox_groupname'			=> '',
					'lightbox_transition'			=> 'random',
					'lightbox_backlight'			=> 'auto',
					'lightbox_custom'				=> '#ffffff',
					// Link Settings
					'link_data'						=> '',
					'link_icon'						=> '',
					'link_label'					=> 'Read More',
					'link_width'					=> 100,
					'link_align'					=> 'center',
					'link_style1'					=> 'ts-dual-buttons-color-default',
					'link_style2'					=> 'ts-dual-buttons-preview-default ts-dual-buttons-hover-default',
					// Categories & Tags
					'event_catsold'					=> '',
					'event_catsnew'					=> '',
					'event_tagsold'					=> '',
					'event_tagsnew'					=> '',
					// Tooltip Settings
					'tooltip_content'				=> '',
					'tooltip_position'				=> 'top',
					'tooltip_style'					=> 'tooltipster-black',
					'tooltip_animation'				=> 'swing',
					'tooltipster_offsetx'			=> 0,
					'tooltipster_offsety'			=> 0,	
					// Customize Settings
					'event_customize' 				=> 'false',
					'event_titlecolor' 				=> '#676767',
					'event_titleback' 				=> '#ffffff',
					'event_contentcolor' 			=> '#676767',
					'event_contentback' 			=> '#dadada',					
					'event_datecolor' 				=> '#777678',
					'event_dateiconcolor' 			=> '#777678',
					'event_dateback' 				=> '#f5f5f5',
					// Other Settings
					'content_wpautop'				=> 'false',	
					'el_id' 						=> '',
					'el_class'                  	=> '',
					'css'							=> '',
				), $atts ));
				
				$output								= "";
				$wpautop 							= ($content_wpautop == "true" ? true : false);
				$randomizer							= mt_rand(999999, 9999999);
				
				if (!empty($el_id)) {
					$timeline_id					= $el_id;
				} else {
					$timeline_id					= 'ts-vcsc-timeline-section-' . $randomizer;
				}
				
				// Customize Settings
				$style_event_titlecolor				= '';
				$style_event_titleback				= '';
				$style_event_contentcolor			= '';
				$style_datetime_iconcolor			= '';
				$style_datetime_textcolor			= '';
				$style_datetime_background			= '';
				$style_event_background				= '';
				
				// Categories / Tags
				if (($event_catsold == "") && ($event_catsnew == "")) {
					$event_catstotal				= 'No Categories';
				} else if (($event_catsold != "") && ($event_catsnew != "")) {
					$event_catstotal				= $event_catsold . ',' . $event_catsnew;
				} else if (($event_catsold == "") && ($event_catsnew != "")) {
					$event_catstotal				= $event_catsnew;
				} else if (($event_catsold != "") && ($event_catsnew == "")) {
					$event_catstotal				= $event_catsold;
				}				
				if (($event_tagsold == "") && ($event_tagsnew == "")) {
					$event_tagstotal				= 'No Tags';
				} else if (($event_tagsold != "") && ($event_tagsnew != "")) {
					$event_tagstotal				= $event_tagsold . ',' . $event_tagsnew;
				} else if (($event_tagsold == "") && ($event_tagsnew != "")) {
					$event_tagstotal				= $event_tagsnew;
				} else if (($event_tagsold != "") && ($event_tagsnew == "")) {
					$event_tagstotal				= $event_tagsold;
				}
				
				if ($event_customize == "true") {
					$style_event_titlecolor 		= 'color: ' . $event_titlecolor . ';';
					$style_event_titleback 			= 'background: ' . $event_titleback . ';';
					$style_event_contentcolor		= 'color: ' . $event_contentcolor . ';';
					$style_datetime_iconcolor		= 'color: ' . $event_dateiconcolor . ';';
					$style_datetime_textcolor		= 'color: ' . $event_datecolor . ';';
					$style_datetime_background		= 'background: ' . $event_dateback . ';';
					$style_event_background			= "background: " . $event_contentback . ';';
				}
				
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-timeline-css-section ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Timeline_CSS_Event', $atts);
				} else {
					$css_class						= 'ts-timeline-css-section ' . $el_class;
				}
				
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					$vc_inline						= 'true';
					$vc_inline_style				= ' display: block;';
				} else {
					$vc_inline						= 'false';
					$vc_inline_style				= '';
				}	
				
				// Final Output
				if ($vc_inline == "false") {
					// Tooltip String
					if ((isset($tooltip_content)) && ($tooltip_content != "")) {
						$tooltip_content			= 'data-tooltipster-html="true" data-tooltipster-title="" data-tooltipster-text="' . $tooltip_content . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="' . $tooltip_animation . '" data-tooltipster-trigger="hover" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . $tooltipster_offsety . '"';
						$tooltip_class				= 'ts-has-tooltipster-tooltip';
					} else {
						$tooltip_content			= '';
						$tooltip_class				= '';
					}
					// Feature Media Alignment
					if (isset($media_align)) {
						if ($media_align == "center") {
							$image_alignment		= "margin: 0 auto; float: none;";
						} else if ($media_align == "left") {
							$image_alignment		= "margin: 0 0; float: left;";
						} else if ($media_align == "right") {
							$image_alignment		= "margin: 0 0; float: right;";
						}
					} else {
						$image_alignment			= "margin: 0 auto; float: none;";
					}
					// Feature Media Dimensions
					$image_dimensions				= 'width: 100%; height: auto;';
					$parent_dimensions				= 'width: ' . $media_width . '%; ' . $media_height;
					// Lightbox Background Color
					if ($lightbox_backlight == "auto") {
						$nacho_color				= '';
					} else if ($lightbox_backlight == "custom") {
						$nacho_color				= 'data-color="' . $lightbox_custom . '"';
					} else if ($lightbox_backlight == "hideit") {
						$nacho_color				= 'data-color="rgba(0, 0, 0, 0)"';
					} else if ($lightbox_backlight == "remove") {
						$nacho_color				= 'data-color="remove"';
					}
					// Adjustment for Inline Edit Mode of VC
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
						$Timeline_Fullwidth			= 'true';
						$vcinline_active			= 'true';
						$vcinline_class				= '';
						$vcinline_slider			= 'css-timeline-mediaslider-edit';
					} else {
						$Timeline_Fullwidth			= $event_fullwidth;
						$vcinline_active			= 'false';
						$vcinline_class				= '';
						$vcinline_slider			= 'css-timeline-mediaslider';
					}
					// Featured Media
					$media_string					= '';
					$slider_class					= '';
					// Featured Media
					if ($media_type != "none") {
						// Featured Media: Image
						if ($media_type == 'image') {
							if (isset($media_image)) {
								$media_image 				= wp_get_attachment_image_src($media_image, 'large');
								if ($media_image != false) {
									$image_extension 		= pathinfo($media_image[0], PATHINFO_EXTENSION);
									if (isset($media_singlealt)) {
										$alt_attribute		= $media_singlealt;
									} else {
										$alt_attribute		= basename($media_image[0]);
									}
									if (isset($media_singletitle)) {
										$media_title 		= $media_singletitle;
									} else if (isset($event_title)) {
										$media_title 		= $event_title;
									} else {
										$media_title		= '';
									}								
									if ($media_lightboximage === "false") {
										$media_string .= '<div class="ts-timeline-media" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
											$media_string .= '<img class="" src="' . $media_image[0] . '" alt="' . $alt_attribute . '" style="max-width: ' . $media_image[1] . 'px; padding: 0; margin: 0 auto; display: block; ' . $image_dimensions . '">';
										$media_string .= '</div>';
									} else {
										$media_string .= '<div class="ts-timeline-media nchgrid-item nchgrid-tile nch-lightbox-image" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
											$media_string .= '<a href="' . $media_image[0] . '" class="nch-lightbox-media no-ajaxy" data-thumbnail="' . $media_image[0] . '" data-title="' . $media_title . '" rel="' . ($lightbox_groupauto == "true" ? "timelinegroup" : (isset($lightbox_groupname) ? $lightbox_groupname : "")) . '" data-share="0" data-effect="' . $lightbox_transition . '" data-duration="5000" ' . $nacho_color . '>';
												$media_string .= '<img src="' . $media_image[0] . '" alt="' . $alt_attribute . '" title="" style="max-width: ' . $media_image[1] . 'px; padding: 0; margin: 0 auto; display: block; ' . $image_dimensions . '">';
												$media_string .= '<div class="nchgrid-caption"></div>';
												if ($media_title != '') {
													$media_string .= '<div class="nchgrid-caption-text">' . $media_title . '</div>';
												}
											$media_string .= '</a>';
										$media_string .= '</div>';
									}
								}
							}
						}
						// Featured Media: Image Slider
						if ($media_type == 'slider') {
							if (isset($media_slider)) {
								if (isset($media_rtlpage)) {
									$page_rtl				= $media_rtlpage;								
								} else {
									$page_rtl				= "false";
								}							
								$featured_slider 			= array();
								$featured_images			= array();
								$featured_maxheight			= (isset($media_heightmax) ? $media_heightmax : 400);
								$featured_fixheight			= (isset($media_heightfix) ? $media_heightfix : 400);
								$images						= $media_slider;
								$images						= explode(",", $images);
								if ($images) {
									foreach ($images as $attachment_id) {
										$featured_slider[]	= $attachment_id;
									}
								}
								$i 							= -1;
								$b							= 0;
								$nachoLength 				= count($featured_slider) - 1;								
								if (isset($media_grouptitle)) {
									$titles_array 			= explode("\n", $media_grouptitle);
									$titles_array 			= array_filter($titles_array, 'trim');
								} else {
									$titles_array			= array();
								}								
								$media_string .= '<div id="ts-timeline-css-imageslider-' . $randomizer . '" class="ts-timeline-css-imageslider-container ' . $vcinline_slider . '" style="" data-id="' . $randomizer . '" data-parent="' . $timeline_id . '" data-items="' . count($featured_slider) . '" data-maxheight="' . $featured_maxheight . '">';
									$media_string .= '<div id="ts-timeline-css-imageslider-slides-' . $randomizer . '" class="ts-timeline-css-imageslider-slides">';
										foreach ($featured_slider as $single_image) {
											$i++;
											if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_JetpackPhoton_Active == "true") {
												add_filter('jetpack_photon_override_image_downsize', '__return_true');
											}
											$modal_image				= wp_get_attachment_image_src($single_image, 'full');
											if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_JetpackPhoton_Active == "true") {
												remove_filter('jetpack_photon_override_image_downsize', '__return_true');
											}
											if ($modal_image != false) {
												$modal_thumb			= wp_get_attachment_image_src($single_image, 'thumb');
												$image_ratio			= $modal_image[1] / $modal_image[2];
												$image_height			= ($modal_image[2] > $featured_maxheight ? $featured_maxheight : $modal_image[2]);
												$image_width			= round($image_height * $image_ratio, 0);
												$data_width				= $modal_image[1];
												$data_height 			= $modal_image[2];
												$image_extension		= pathinfo($modal_image[0], PATHINFO_EXTENSION);
												$featured_images[]		= $modal_thumb[0];		
												if ($media_lightboximage === "false") {
													if ((($i == 0) && ($vcinline_active == "true")) || ($vcinline_active == "false")) {
														$media_string .= '<div id="ts-timeline-css-imageslider-image-' . $single_image .'-parent" class="ts-timeline-css-imageslider-image-parent ts-timeline-css-media-nolightbox nchgrid-item nchgrid-tile nch-lightbox-image ts-timeline-css-imageslider-item ' . ($i == 0 ? "ts-timeline-css-slider-view-active" : "ts-timeline-css-slider-view-hidden") . '" data-width="' . $data_width . '" data-height="' . $data_height . '" data-ratio="' . $image_ratio . '" data-order="' . $i . '" data-total="' . count($featured_slider) . '">';
															$media_string .= '<img src="' . $modal_image[0] . '" style="max-width: ' . $image_width . 'px; max-height: ' . $image_height . 'px; padding: 0; margin: 0 auto; display: block; ' . $image_dimensions . '">';
															$media_string .= '<div class="nchgrid-caption"></div>';
															if ((isset($titles_array[$i])) && ($titles_array[$i] != '')) {
																$media_string .= '<div class="nchgrid-caption-text">' . strip_tags($titles_array[$i]) . '</div>';
															}
														$media_string .= '</div>';
													}
												} else {
													if (($i == $nachoLength) && ($vcinline_active == "false")) {
														$media_string .= '<div id="ts-timeline-css-imageslider-image-' . $single_image .'-parent" class="ts-timeline-css-imageslider-image-parent ts-timeline-css-media-lightbox nchgrid-item nchgrid-tile nch-lightbox-image ts-timeline-css-imageslider-item ' . ($i == 0 ? "ts-timeline-css-slider-view-active" : "ts-timeline-css-slider-view-hidden") . '" data-width="' . $data_width . '" data-height="' . $data_height . '" data-ratio="' . $image_ratio . '" data-order="' . $i . '" data-total="' . count($featured_slider) . '">';
															$media_string .= '<a id="' . $timeline_id . '-' . $single_image .'" href="' . $modal_image[0] . '" data-thumbnail="' . $modal_image[0] . '" data-title="' . (((isset($titles_array[$i])) && ($titles_array[$i] != '')) ? strip_tags($titles_array[$i]) : '') . '" class="nch-lightbox-media no-ajaxy ts-hover-image ' . $timeline_id . '-slider-image" rel="' . ($lightbox_groupauto == "true" ? "timelinegroup" : (isset($lightbox_groupname) ? $lightbox_groupname : "")) . '" data-effect="' . $lightbox_transition . '" data-share="0" data-autoplay="0" data-duration="5000" data-thumbsize="100" data-thumbs="bottom" ' . $nacho_color . '>';
																$media_string .= '<img src="' . $modal_image[0] . '" style="max-width: ' . $image_width . 'px; max-height: ' . $image_height . 'px; padding: 0; margin: 0 auto; display: block; ' . $image_dimensions . '">';
																$media_string .= '<div class="nchgrid-caption"></div>';
																if ((isset($titles_array[$i])) && ($titles_array[$i] != '')) {
																	$media_string .= '<div class="nchgrid-caption-text">' . strip_tags($titles_array[$i]) . '</div>';
																}
															$media_string .= '</a>';
														$media_string .= '</div>';
													} else if ((($i == 0) && ($vcinline_active == "true")) || ($vcinline_active == "false")) {
														$media_string .= '<div id="ts-timeline-css-imageslider-image-' . $single_image .'-parent" class="ts-timeline-css-imageslider-image-parent ts-timeline-css-media-lightbox nchgrid-item nchgrid-tile nch-lightbox-image ts-timeline-css-imageslider-item ' . ($i == 0 ? "ts-timeline-css-slider-view-active" : "ts-timeline-css-slider-view-hidden") . '" data-width="' . $data_width . '" data-height="' . $data_height . '" data-ratio="' . $image_ratio . '" data-order="' . $i . '" data-total="' . count($featured_slider) . '">';
															$media_string .= '<a id="' . $timeline_id . '-' . $single_image .'" href="' . $modal_image[0] . '" data-thumbnail="' . $modal_image[0] . '" data-title="' . (((isset($titles_array[$i])) && ($titles_array[$i] != '')) ? strip_tags($titles_array[$i]) : '') . '" class="nch-lightbox-media no-ajaxy ts-hover-image ' . $timeline_id . '-slider-image" rel="' . ($lightbox_groupauto == "true" ? "timelinegroup" : (isset($lightbox_groupname) ? $lightbox_groupname : "")) . '" data-effect="' . $lightbox_transition . '" ' . $nacho_color . '>';
																$media_string .= '<img src="' . $modal_image[0] . '" style="max-width: ' . $image_width . 'px; max-height: ' . $image_height . 'px; padding: 0; margin: 0 auto; display: block; ' . $image_dimensions . '">';
																$media_string .= '<div class="nchgrid-caption"></div>';
																if ((isset($titles_array[$i])) && ($titles_array[$i] != '')) {
																	$media_string .= '<div class="nchgrid-caption-text">' . strip_tags($titles_array[$i]) . '</div>';
																}
															$media_string .= '</a>';
														$media_string .= '</div>';
													}
												}												
											}
										}
									$media_string .= '</div>';
									$media_string .= '<div class="ts-timeline-css-imageslider-navigation">';
										$media_string .= '<div class="ts-timeline-css-imageslider-dotholder">';
											$i = -1;
											foreach ($featured_slider as $single_image) {
												$i++;
												$media_string .= '<div class="ts-timeline-css-imageslider-dot ' . ($i == 0 ? "ts-timeline-css-imageslider-dotactive" : "") . ' ts-has-tooltipster-tooltip" data-order="' . $i . '" data-image="' . (isset($featured_images[$i]) ? $featured_images[$i] : "") . '" data-tooltipster-html="false" data-tooltipster-title="" data-tooltipster-text="" data-tooltipster-image="' . (isset($featured_images[$i]) ? $featured_images[$i] : "") . '" data-tooltipster-position="top" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="tooltipster-thumb" data-tooltipster-animation="fade" data-tooltipster-trigger="hover" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . $tooltipster_offsety . '"></div>';
											}
										$media_string .= '</div>';
										$media_string .= '<div class="ts-timeline-css-imageslider-prev"><i class="dashicons dashicons-arrow-left-alt2"></i></div>';
										$media_string .= '<div class="ts-timeline-css-imageslider-next"><i class="dashicons dashicons-arrow-right-alt2"></i></div>';
									$media_string .= '</div>';
								$media_string .= '</div>';
								$slider_class				= '';								
							}
						} else {
							$slider_class					= '';
						}
						// Featured Media: YouTube
						if (($media_videoyoutube != "") && ($media_type == 'youtube')) {
							if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $media_videoyoutube)) {
								$featured_youtube_url		= $media_videoyoutube;
							} else {
								$featured_youtube_url		= 'https://www.youtube.com/watch?v=' . $media_videoyoutube;
							}
							if ($media_videoauto == "true") {
								$video_autoplay				= 'true';
							} else {
								$video_autoplay				= 'false';
							}
							if ($media_videorelated == "true") {
								$video_related				= '&rel=1';
							} else {
								$video_related				= '&rel=0';
							}
							if ($media_singletitle != "") {
								$media_title 				= $media_singletitle;
							} else if ($event_title != "") {
								$media_title 				= $event_title;
							} else {
								$media_title				= '';
							}
							if (($media_lightboxvideo == 'true') && ($media_videocustom == "false")) {
								$media_image 				= TS_VCSC_VideoImage_Youtube($featured_youtube_url, "high");
								if ($media_singlealt != '') {
									$alt_attribute			= $media_singlealt;
								} else {
									$alt_attribute			= basename($media_image);
								}
								$media_string .= '<div class="nch-holder nchgrid-item nchgrid-tile nch-lightbox-youtube" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
									$media_string .= '<a href="' . $featured_youtube_url . '" class="nch-lightbox-media no-ajaxy" data-thumbnail="' . $media_image . '" data-title="' . $media_title . '" data-related="' . $video_related . '" data-videoplay="' . $video_autoplay . '" data-type="youtube" rel="' . ($lightbox_groupauto == "true" ? "timelinegroup" : (isset($lightbox_groupname) ? $lightbox_groupname : "")) . '" data-share="0" data-effect="' . (isset($lightbox_transition) ? $lightbox_transition : 'random') . '" data-duration="5000" ' . $nacho_color . '>';
										$media_string .= '<img src="' . $media_image . '" title="" style="display: block; ' . $image_dimensions . '" title="' . $media_title . '" alt="' . $alt_attribute . '">';
										$media_string .= '<div class="nchgrid-caption"></div>';
										if ($media_title != '') {
											$media_string .= '<div class="nchgrid-caption-text">' . $media_title . '</div>';
										}
									$media_string .= '</a>';
								$media_string .= '</div>';
							} else if (($media_lightboxvideo == 'true') && ($media_videocustom == "true")) {
								if ($media_videocover != '') {
									$media_image			= wp_get_attachment_image_src($media_videocover, 'full');
									$media_image			= $media_image[0];
									$image_extension		= pathinfo($media_image, PATHINFO_EXTENSION);
									if ($media_singlealt != '') {
										$alt_attribute		= $media_singlealt;
									} else {
										$alt_attribute		= basename($media_image[0]);
									}
								} else {
									$media_image			= TS_VCSC_GetResourceURL('images/defaults/default_youtube.jpg');
									$image_extension		= pathinfo($media_image, PATHINFO_EXTENSION);
									if ($media_singlealt != '') {
										$alt_attribute		= $media_singlealt;
									} else {
										$alt_attribute		= basename($media_image);
									}
								}
								$media_string .= '<div class="nch-holder nchgrid-item nchgrid-tile nch-lightbox-youtube" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
									$media_string .= '<a href="' . $featured_youtube_url . '" class="nch-lightbox-media no-ajaxy" data-thumbnail="' . $media_image . '" data-title="' . $media_title . '" data-related="' . $video_related . '" data-videoplay="' . $video_autoplay . '" data-type="youtube" rel="' . ($lightbox_groupauto == "true" ? "timelinegroup" : (isset($lightbox_groupname) ? $lightbox_groupname : "")) . '" data-share="0" data-effect="' . (isset($lightbox_transition) ? $lightbox_transition : 'random') . '" data-duration="5000" ' . $nacho_color . '>';
										$media_string .= '<img src="' . $media_image . '" title="" style="display: block; ' . $image_dimensions . '" title="' . $media_title . '" alt="' . $alt_attribute . '">';
										$media_string .= '<div class="nchgrid-caption"></div>';
										if ($media_title != '') {
											$media_string .= '<div class="nchgrid-caption-text">' . $media_title . '</div>';
										}
									$media_string .= '</a>';
								$media_string .= '</div>';
							} else if ($media_lightboxvideo == 'false') {									
								$video_id 					= TS_VCSC_VideoID_Youtube($featured_youtube_url);
								if ($video_autoplay == "true") {
									$video_autoplay			= '?autoplay=1';
								} else {
									$video_autoplay			= '?autoplay=0';
								}
								$media_string .= '<div class="ts-video-container" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
									$media_string .= '<iframe width="100%" height="auto" src="//www.youtube.com/embed/' . $video_id . $video_autoplay . $video_related . '&wmode=opaque" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
								$media_string .= '</div>';
							}
						}
						// Featured Media: DailyMotion
						if (($media_videomotion != "") && ($media_type == 'dailymotion')) {
							if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $media_videomotion)) {
								$featured_dailymotion_url	= $media_videomotion;
							} else {
								$featured_dailymotion_url	= 'http://www.dailymotion.com/video/' . $media_videomotion;
							}
							if ($media_videoauto == "true") {
								$video_autoplay				= 'true';
							} else {
								$video_autoplay				= 'false';
							}
							if ($media_singletitle != "") {
								$media_title 				= $media_singletitle;
							} else if ($event_title != "") {
								$media_title 				= $event_title;
							} else {
								$media_title				= '';
							}	
							if (($media_lightboxvideo == 'true') && ($media_videocustom == "false")) {
								$media_image 				= TS_VCSC_VideoImage_Motion($featured_dailymotion_url);
								if ($media_singlealt != '') {
									$alt_attribute			= $media_singlealt;
								} else {
									$alt_attribute			= basename($media_image);
								}
								$media_string .= '<div class="nch-holder nchgrid-item nchgrid-tile nch-lightbox-motion" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
									$media_string .= '<a href="' . $featured_dailymotion_url . '" class="nch-lightbox-media no-ajaxy" data-thumbnail="' . $media_image . '" data-title="' . $media_title . '" data-videoplay="' . $video_autoplay . '" data-type="dailymotion" rel="' . ($lightbox_groupauto == "true" ? "timelinegroup" : (isset($lightbox_groupname) ? $lightbox_groupname : "")) . '" data-share="0" data-effect="' . (isset($lightbox_transition) ? $lightbox_transition : 'random') . '" data-duration="5000" ' . $nacho_color . '>';
										$media_string .= '<img src="' . $media_image . '" title="" style="display: block; ' . $image_dimensions . '" title="' . $media_title . '" alt="' . $alt_attribute . '">';
										$media_string .= '<div class="nchgrid-caption"></div>';
										if ($media_title != '') {
											$media_string .= '<div class="nchgrid-caption-text">' . $media_title . '</div>';
										}
									$media_string .= '</a>';
								$media_string .= '</div>';
							} else if (($media_lightboxvideo == 'true') && ($media_videocustom == "true")) {
								if ($media_videocover != '') {
									$media_image			= wp_get_attachment_image_src($media_videocover, 'full');
									$media_image			= $media_image[0];
									$image_extension		= pathinfo($media_image, PATHINFO_EXTENSION);
									if ($media_singlealt != '') {
										$alt_attribute		= $media_singlealt;
									} else {
										$alt_attribute		= basename($media_image[0]);
									}
								} else {
									$media_image			= TS_VCSC_GetResourceURL('images/defaults/default_motion.jpg');
									$image_extension		= pathinfo($media_image, PATHINFO_EXTENSION);
									if ($media_singlealt != '') {
										$alt_attribute		= $media_singlealt;
									} else {
										$alt_attribute		= basename($media_image);
									}
								}
								$media_string .= '<div class="nch-holder nchgrid-item nchgrid-tile nch-lightbox-motion" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
									$media_string .= '<a href="' . $featured_dailymotion_url . '" class="nch-lightbox-media no-ajaxy" data-thumbnail="' . $media_image . '" data-title="' . $media_title . '" data-videoplay="' . $video_autoplay . '" data-type="dailymotion" rel="' . ($lightbox_groupauto == "true" ? "timelinegroup" : (isset($lightbox_groupname) ? $lightbox_groupname : "")) . '" data-share="0" data-effect="' . (isset($lightbox_transition) ? $lightbox_transition : 'random') . '" data-duration="5000" ' . $nacho_color . '>';
										$media_string .= '<img src="' . $media_image . '" title="" style="display: block; ' . $image_dimensions . '" title="' . $media_title . '" alt="' . $alt_attribute . '">';
										$media_string .= '<div class="nchgrid-caption"></div>';
										if ($media_title != '') {
											$media_string .= '<div class="nchgrid-caption-text">' . $media_title . '</div>';
										}
									$media_string .= '</a>';
								$media_string .= '</div>';
							} else if ($media_lightboxvideo == 'false') {	
								$video_id 					= TS_VCSC_VideoID_Motion($featured_dailymotion_url);
								if ($video_autoplay == "true") {
									$video_autoplay			= '?autoplay=1';
								} else {
									$video_autoplay			= '?autoplay=0';
								}
								$media_string .= '<div class="ts-video-container" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
									$media_string .= '<iframe width="100%" height="auto" src="http://www.dailymotion.com/embed/video/' . $video_id . $video_autoplay . '&forcedQuality=hq&wmode=opaque" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
								$media_string .= '</div>';
							}
						}
						// Featured Media: Vimeo
						if (($media_videovimeo != "") && ($media_type == 'vimeo_default')) {
							if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $media_videovimeo)) {
								$featured_vimeo_url			= $media_videovimeo;
							} else {
								$featured_vimeo_url			= 'http://www.vimeo.com/video/' . $media_videovimeo;
							}
							if ($media_videoauto == "true") {
								$video_autoplay				= 'true';
							} else {
								$video_autoplay				= 'false';
							}
							if ($media_singletitle != "") {
								$media_title 				= $media_singletitle;
							} else if ($event_title != "") {
								$media_title 				= $event_title;
							} else {
								$media_title				= '';
							}	
							if (($media_lightboxvideo == 'true') && ($media_videocustom == "false")) {
								$media_image 				= TS_VCSC_VideoImage_Vimeo($featured_vimeo_url);
								if ($media_singlealt != '') {
									$alt_attribute			= $media_singlealt;
								} else {
									$alt_attribute			= basename($media_image);
								}
								$media_string .= '<div class="nch-holder nchgrid-item nchgrid-tile nch-lightbox-vimeo" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
									$media_string .= '<a href="' . $featured_vimeo_url . '" class="nch-lightbox-media no-ajaxy" data-thumbnail="' . $media_image . '" data-title="' . $media_title . '" data-videoplay="' . $video_autoplay . '" data-type="vimeo" rel="' . ($lightbox_groupauto == "true" ? "timelinegroup" : (isset($lightbox_groupname) ? $lightbox_groupname : "")) . '" data-share="0" data-effect="' . (isset($lightbox_transition) ? $lightbox_transition : 'random') . '" data-duration="5000" ' . $nacho_color . '>';
										$media_string .= '<img src="' . $media_image . '" title="" style="display: block; ' . $image_dimensions . '" title="' . $media_title . '" alt="' . $alt_attribute . '">';
										$media_string .= '<div class="nchgrid-caption"></div>';
										if ($media_title != '') {
											$media_string .= '<div class="nchgrid-caption-text">' . $media_title . '</div>';
										}
									$media_string .= '</a>';
								$media_string .= '</div>';
							} else if (($media_lightboxvideo == 'true') && ($media_videocustom == "true")) {
								if ($media_videocover != '') {
									$media_image			= wp_get_attachment_image_src($media_videocover, 'full');
									$media_image			= $media_image[0];
									$image_extension		= pathinfo($media_image, PATHINFO_EXTENSION);
									if ($media_singlealt != '') {
										$alt_attribute		= $media_singlealt;
									} else {
										$alt_attribute		= basename($media_image[0]);
									}
								} else {
									$media_image			= TS_VCSC_GetResourceURL('images/defaults/default_vimeo.jpg');
									$image_extension		= pathinfo($media_image, PATHINFO_EXTENSION);
									if ($media_singlealt != '') {
										$alt_attribute		= $media_singlealt;
									} else {
										$alt_attribute		= basename($media_image);
									}
								}
								$media_string .= '<div class="nch-holder nchgrid-item nchgrid-tile nch-lightbox-vimeo" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
									$media_string .= '<a href="' . $featured_vimeo_url . '" class="nch-lightbox-media no-ajaxy" data-thumbnail="' . $media_image . '" data-title="' . $media_title . '" data-videoplay="' . $video_autoplay . '" data-type="vimeo" rel="' . ($lightbox_groupauto == "true" ? "timelinegroup" : (isset($lightbox_groupname) ? $lightbox_groupname : "")) . '" data-share="0" data-effect="' . (isset($lightbox_transition) ? $lightbox_transition : 'random') . '" data-duration="5000" ' . $nacho_color . '>';
										$media_string .= '<img src="' . $media_image . '" title="" style="display: block; ' . $image_dimensions . '" title="' . $media_title . '" alt="' . $alt_attribute . '">';
										$media_string .= '<div class="nchgrid-caption"></div>';
										if ($media_title != '') {
											$media_string .= '<div class="nchgrid-caption-text">' . $media_title . '</div>';
										}
									$media_string .= '</a>';
								$media_string .= '</div>';
							} else if ($media_lightboxvideo == 'false') {
								$video_id 					= TS_VCSC_VideoID_vimeo($featured_vimeo_url);
								if ($video_autoplay == "true") {
									$video_autoplay			= '?autoplay=1';
								} else {
									$video_autoplay			= '?autoplay=0';
								}
								$media_string .= '<div class="ts-video-container" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
									$media_string .= '<iframe width="100%" height="auto" src="//player.vimeo.com/video/' . $video_id . $video_autoplay . '&wmode=opaque" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
								$media_string .= '</div>';
							}
						}
					}
					// Link Button
					if (isset($link_data) && ($link_data != "")) {
						// Link Values
						$link 						= TS_VCSC_Advancedlinks_GetLinkData($link_data);
						$a_href						= $link['url'];
						$a_title 					= $link['title'];
						$a_target 					= $link['target'];
						$a_rel 						= $link['rel'];
						if (!empty($a_rel)) {
							$a_rel 					= 'rel="' . esc_attr(trim($a_rel)) . '"';
						}
						// Button Alignment
						if (isset($link_align)) {
							if ($link_align == "center") {
								$buttonstyle			= "width: " . (isset($link_width) ? $link_width : 100) . "%; margin: 0 auto; float: none;";
							} else if ($link_align == "left") {
								$buttonstyle			= "width: " . (isset($link_width) ? $link_width : 100) . "%; margin: 0 auto; float: left;";
							} else if ($link_align == "right") {
								$buttonstyle			= "width: " . (isset($link_width) ? $link_width : 100) . "%; margin: 0 auto; float: right;";
							}
						} else {
							$buttonstyle				= 'width: 100%; margin: 0 auto; float: none;';
						}
						$button_string					= '';					
						if ((!empty($a_href)) && isset($link_label)) {				
							$button_string .= '<div class="ts-timeline-css-button-outer clearFixMe">';
								$button_string .= '<div class="ts-timeline-css-button-wrapper" style="' . $buttonstyle . '%;">';
								if (isset($link_icon)) {
									if ($link_icon != "none") {
										$button_string .= '<a class="ts-timeline-css-button-link ' . $link_style1 . ' ' . $link_style2 . '" href="' . $a_href . '" target="' . $a_target . '" title="' . $a_title . '" ' . $a_rel . '><i class="ts-timeline-css-button-icon dashicons dashicons-' . $link_icon . '" style=""></i>' . $link_label . '</a>';
									} else {
										$button_string .= '<a class="ts-timeline-css-button-link ' . $link_style1 . ' ' . $link_style2 . '" href="' . $a_href . '" target="' . $a_target . '" title="' . $a_title . '" ' . $a_rel . '>' . $link_label . '</a>';
									}
								} else {
									$button_string .= '<a class="ts-timeline-css-button-link ' . $link_style1 . ' ' . $link_style2 . '" href="' . $a_href . '" target="' . $a_target . '" title="' . $a_title . '" ' . $a_rel . '>' . $link_label . '</a>';
								}
								$button_string .= '</div>';
							$button_string .= '</div>';						
						} else {
							$button_string				= '';
						}
					} else {
						$button_string					= '';
					}						
					// Event Icon	
					if (((empty($event_icon)) || ($event_icon == "transparent")) && (!isset($content))) {
						$title_margin					= 'margin: 0;';
					} else {
						$title_margin					= '';
					}						
					// Column Adjustment for Full Width Event
					if (($event_fullwidth == "true") && (!isset($event_title)) && (empty($section_icon) || ($section_icon == "transparent")) && (!isset($content)) && (!isset($link_label))) {
						$columnA_adjust					= 'width: 100%; margin: 0;';
						$columnB_adjust					= 'display: none; width: 0;';
					} else if (($event_fullwidth == "true") && ($media_type == "none")) {
						$columnA_adjust					= 'display: none; width: 0; margin: 0;';
						$columnB_adjust					= 'width: 100%; margin: 0;';
					} else {
						$columnA_adjust					= '';
						$columnB_adjust					= '';
					}
					// Final Output
					$output .= '<div id="' . $timeline_id . '" class="' . $css_class . ' ' . $slider_class . ' ' . ($event_fullwidth == "true" ? "ts-timeline-css-fullwidth" : "ts-timeline-css-event") . ' ts-timeline-css-visible ts-timeline-css-animated ' . (isset($event_date) ? "ts-timeline-css-date-true" : "ts-timeline-css-date-false") . '" style="' . ($event_fullwidth == "true" ? "width: 100%;" : "") . ' ' . $vc_inline_style . '" data-categories="' . $event_catstotal . '" data-tags="' . $event_tagstotal . '" data-filtered-categories="false" data-filtered-tags="false">';
						$output .= '<div class="ts-timeline-css-text-wrap ' . (isset($event_date) ? "ts-timeline-css-text-wrap-date" : "ts-timeline-css-text-wrap-nodate") . ' ' . $tooltip_class . '" ' . $tooltip_content . ' style="' . (isset($event_date) ? "padding-top: 40px;" : "") . ' ' . $style_event_background . '">';
							if ($event_date != "") {
								if (($event_dateicon == "none") || ($event_dateicon == "") || ($event_dateicon == "transparent")) {
									$output .= '<div class="ts-timeline-css-date" style="' . $style_datetime_background . '"><span class="ts-timeline-css-date-connect"><span class="ts-timeline-css-date-text" style="' . $style_datetime_textcolor . '">' . $event_date . '</span></span></div>';
								} else {
									$output .= '<div class="ts-timeline-css-date" style="' . $style_datetime_background . '"><span class="ts-timeline-css-date-connect"><span class="ts-timeline-css-date-text" style="' . $style_datetime_textcolor . '"><i class="ts-timeline-css-date-icon dashicons ' . $event_dateicon . '" style="' . $style_datetime_iconcolor . '"></i>' . $event_date . '</span></span></div>';
								}
							}
							if ($event_fullwidth == "true") {
								$output .= '<div class="ts-timeline-css-fullwidth-colA" style="' . $columnA_adjust . '">';
									$output .= $media_string;
								$output .= '</div>';
								$output .= '<div class="ts-timeline-css-fullwidth-colB" style="' . $columnB_adjust . '">';
									if (isset($event_title)) {
										if ($event_title != "") {
											$output .= '<h3 class="ts-timeline-css-title" style="' . $style_event_titlecolor . ' ' . $style_event_titleback . ' text-align: ' . (isset($event_titlealign) ? $event_titlealign : 'center') . '; ' . (!isset($content) && (empty($section_icon)) ? "border: none; margin-bottom: 0; padding-bottom: 0;" : "") . ' ' . $title_margin . '">' . $event_title . '</h3>';
										}
									}
									if (((!empty($event_icon)) && (($event_icon) != "transparent") && (($event_icon) != "")) || ($content != "")) {
										$output .= '<div class="ts-timeline-css-details ' . (((!isset($event_title)) || ($event_title == "")) ? "ts-timeline-css-details-border" : "") . '" style="padding-bottom: ' . (((!empty($a_href)) && (!empty($button_string))) ? 0 : 15) . 'px; ' . (!isset($content) && !empty($event_icon) ? "height: 60px;" : "") . '">';
											if ($content != '') {
												$output .= '<div class="ts-timeline-css-text-wrap-inner" style="' . $style_event_contentcolor . '; ' . (empty($event_icon) ? "width: 100%; height: 100%; left: 0;" : " left: 0;") . '">';
													if (function_exists('wpb_js_remove_wpautop')){
														$output .= '<div class="ts-timeline-css-text" style="">' . wpb_js_remove_wpautop(do_shortcode($content), $wpautop) . '</div>';
													} else {
														$output .= '<div class="ts-timeline-css-text" style="">' . do_shortcode($content) . '</div>';
													}
												$output .= '</div>';
											}
											if ((!empty($event_icon)) && (($event_icon) != "transparent") && (($event_icon) != "")) {
												$output .= '<div class="ts-timeline-css-icon ts-timeline-css-icon-full" style="' . (!isset($content) ? "display: inline-block; width: 100%; left: 0; margin: 0 0 0 2%;" : "left: 80%;") . '"><i class="' . $event_icon . '" style="color: ' . $event_iconcolor . ';"></i></div>';
											}
											if ((!empty($a_href)) && (!empty($button_string))) {
												$output .= '<div class="ts-timeline-css-button-container">';
													$output .= $button_string;
												$output .= '</div>';
											}
										$output .= '</div>';
									}
								$output .= '</div>';
								if ($event_tagstotal != '') {
									$output .= '<div class="ts-timeline-css-output-tags"><i class="dashicons dashicons-tag"></i><span>' . str_replace(",", ", ", $event_tagstotal) . '</span></div>';
								}
								if ($event_catstotal != '') {
									$output .= '<div class="ts-timeline-css-output-cats"><i class="dashicons dashicons-category"></i><span>' . str_replace(",", ", ", $event_catstotal) . '</span></div>';
								}
							} else {
								$output .= $media_string;
								if (isset($event_title)) {
									if ($event_title != "") {
										$output .= '<h3 class="ts-timeline-css-title" style="' . $style_event_titlecolor . '; text-align: ' . (isset($event_titlealign) ? $event_titlealign : 'center') . '; ' . (!isset($content) && (empty($section_icon)) ? "border: none; margin-bottom: 0; padding-bottom: 0;" : "") . ' ' . $title_margin . '">' . $event_title . '</h3>';
									}
								}
								if (((!empty($event_icon)) && (($event_icon) != "transparent") && (($event_icon) != "")) || ($content != "")) {
									$output .= '<div class="ts-timeline-css-details ' . (((!isset($event_title)) || ($event_title == "")) ? "ts-timeline-css-details-border" : "") . '" style="padding-bottom: 15px; ' . (!isset($content) && !empty($event_icon) ? "height: 60px;" : "") . '">';
										if ((!empty($event_icon)) && (($event_icon) != "transparent") && (($event_icon) != "")) {
											$output .= '<div class="ts-timeline-css-icon ts-timeline-css-icon-half" style="' . (!isset($content) ? "display: inline-block; width: 100%; left: 0;" : "") . '"><i class="' . $event_icon . '" style="color: ' . $event_iconcolor . ';"></i></div>';
										}
										if ($content != '') {
											$output .= '<div class="ts-timeline-css-text-wrap-inner" style="' . $style_event_contentcolor . '; ' . (empty($event_icon) ? "width: 100%; height: 100%; left: 0;" : "") . '">';
												if (function_exists('wpb_js_remove_wpautop')){
													$output .= '<div class="ts-timeline-css-text" style="">' . wpb_js_remove_wpautop(do_shortcode($content), $wpautop) . '</div>';
												} else {
													$output .= '<div class="ts-timeline-css-text" style="">' . do_shortcode($content) . '</div>';
												}
											$output .= '</div>';
										}
									$output .= '</div>';
									if ((!empty($a_href)) && (!empty($button_string))) {
										$output .= '<div class="ts-timeline-css-button-container">';
											$output .= $button_string;
										$output .= '</div>';
									}
								}
								if ($event_tagstotal != '') {
									$output .= '<div class="ts-timeline-css-output-tags"><i class="dashicons dashicons-tag"></i><span>' . str_replace(",", ", ", $event_tagstotal) . '</span></div>';
								}
								if ($event_catstotal != '') {
									$output .= '<div class="ts-timeline-css-output-cats"><i class="dashicons dashicons-category"></i><span>' . str_replace(",", ", ", $event_catstotal) . '</span></div>';
								}
							}
							$output .= '<div class="clearFixMe"></div>';
						$output .= '</div>';
					$output .= '</div>';
				} else {
					$output .= '<div id="' . $timeline_id . '" class="' . $css_class . ' ts-timeline-css-fullwidth" style="width: 98%; ' . $vc_inline_style . '">';
						$output .= '<div class="ts-timeline-css-text-wrap">';
							$output .= '<div class="ts-timeline-css-text-wrap-inner" style="width: 100%; left: 0; margin: 20px auto;">';
								$output .= '<div>Section ID: Direct</div>';
								$output .= '<div>Section Title: ' . $event_title . '</div>';
								$output .= '<div>Section Type: Event</div>';
							$output .= '</div>';
							$output .= '<div class="clearFixMe"></div>';
						$output .= '</div>';
					$output .= '</div>';
				}
				
				echo $output;
				
				$myvariable = ob_get_clean();
				return $myvariable;
			}
			
			// Timeline Container
			function TS_VCSC_Timeline_CSS_Function_Container ($atts, $content = null){
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();
				
				wp_enqueue_style('ts-extend-csstimeline');
				wp_enqueue_style('ts-visual-composer-extend-front');
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "false") {
					wp_enqueue_script('ts-extend-csstimeline');
					wp_enqueue_script('ts-extend-krautlightbox');
					wp_enqueue_style('ts-extend-krautlightbox');
					wp_enqueue_style('ts-font-ecommerce');
					wp_enqueue_style('dashicons');
					wp_enqueue_style('ts-extend-tooltipster');
					wp_enqueue_script('ts-extend-tooltipster');	
					wp_enqueue_style('ts-extend-buttonsdual');
					wp_enqueue_script('ts-visual-composer-extend-front');
				}
				
				extract( shortcode_atts( array(					
					// General Settings
					'timeline_preloader'				=> 0,
					'timeline_break'					=> '600',
					'timeline_layout'					=> 'ts-timeline-css-columns', 		// ts-timeline-css-columns, ts-timeline-css-right, ts-timeline-css-left, ts-timeline-css-responsive
					'timeline_switch'					=> 'ts-timeline-css-responsive',
					'timeline_stacks'					=> 'false',
					'timeline_initial'					=> 'left',
					'timeline_iframewrap'				=> 'false',
					// Viewport Animation
					'viewport_left'						=> '',
					'viewport_right'					=> '',
					'viewport_single'					=> '',
					// Center Line Settings
					'timeline_linetype'					=> 'default', 						// default, singlegradient, singlesolid, singledotted, singledashed, dualsolid, dualdotted, dualdashed
					'timeline_linegradient1'			=> '',
					'timeline_linegradient2'			=> '',
					'timeline_linecolor1'				=> '#cccccc',
					'timeline_linecolor2'				=> '#cccccc',
					'timeline_linestrength'				=> 4,
					'timeline_linespace'				=> 2,
					// LazyLoad Settings
					'timeline_lazy'						=> 'false',
					'timeline_trigger'					=> 'scroll',
					'timeline_count'					=> '10',
					'timeline_load'						=> 'Load More',
					// Sorter Settings
					'timeline_order'					=> 'asc',
					'timeline_sort'						=> 'true',
					'timeline_sort_label'				=> 'Sort Timeline:',
					'timeline_sort_asc'					=> 'Ascending',
					'timeline_sort_desc'				=> 'Descending',
					// Filter Settings
					'timeline_filter_allow'				=> 'false',
					'timeline_filter_multiple'			=> 'true',
					'timeline_filter_deselect'			=> 'false',
					'timeline_filter_confirm'			=> 'true',
					'timeline_filter_search'			=> 'true',
					'timeline_filter_cats'				=> 'true',
					'timeline_filter_tags'				=> 'true',
					'timeline_filter_labelcats'			=> 'Filter By Categories:',
					'timeline_filter_labeltags'			=> 'Filter By Tags:',
					'timeline_filter_nocats'			=> 'No Categories',
					'timeline_filter_notags'			=> 'No Tags',
					'timeline_filter_allcats'			=> 'All Categories',
					'timeline_filter_alltags'			=> 'All Tags',
					'timeline_filter_selected'			=> 'Selected',
					'timeline_filter_selectall'			=> 'Select All',
					// Sumo Text Strings
					'string_sumo_confirm'				=> 'Confirm',
					'string_sumo_cancel'				=> 'Cancel',
					'string_sumo_allselected'			=> 'All Selected',
					'string_sumo_placeholder'			=> 'Select Here',
					'string_sumo_searchcats'			=> 'Search Categories ...',
					'string_sumo_searchtags'			=> 'Search Tags ...',
					// Header Settings
					'timeline_title'					=> '',
					'timeline_title_custom'				=> 'false',
					'timeline_title_show'				=> 'true',
					'timeline_title_color'				=> '#7c7979',					
					'timeline_title_back'				=> '#ededed',
					'timeline_title_border'				=> '#ABABAB',
					'timeline_title_family'				=> 'Default',
					'timeline_title_type'				=> '',
					// Description Settings
					'timeline_description'				=> '',
					'timeline_description_custom'		=> 'false',
					'timeline_description_align'		=> 'center',
					'timeline_description_color'		=> '#7c7979',
					'timeline_description_family'		=> 'Default',
					'timeline_description_type'			=> '',
					'timeline_description_back'			=> '#ededed',
					'timeline_description_border'		=> '#c4c4c4',
					// Start Tag Settings
					'timeline_start'					=> '',
					'timeline_start_custom'				=> 'false',
					'timeline_start_color'				=> '#ffffff',					
					'timeline_start_family'				=> 'Default',
					'timeline_start_type'				=> '',
					'timeline_start_back'				=> '#7c7b7b',
					'timeline_start_border'				=> '#737373',
					// End Tag Settings
					'timeline_end'						=> '',
					'timeline_end_custom'				=> 'false',
					'timeline_end_color'				=> '#ffffff',
					'timeline_end_family'				=> 'Default',
					'timeline_end_type'					=> '',	
					'timeline_end_back'					=> '#7c7b7b',
					'timeline_end_border'				=> '#737373',
					// Additional Information
					'timeline_show_cats'				=> 'true',
					'timeline_show_tags'				=> 'true',
					// Customize Settings
					'timeline_custom_breaks'			=> 'false',
					'timeline_custom_events'			=> 'false',
					'timeline_custom_columns' 			=> 'main',
					// Date/Time Settings
					'timeline_date_singleicon'			=> '#7c7b7b',
					'timeline_date_singletext'			=> '#7c7b7b',
					'timeline_date_singleback'			=> '#f5f5f5',					
					'timeline_date_leftglobal'			=> 'true',
					'timeline_date_lefticon'			=> '#7c7b7b',
					'timeline_date_lefttext'			=> '#7c7b7b',
					'timeline_date_leftback'			=> '#f5f5f5',	
					'timeline_date_rightglobal'			=> 'true',
					'timeline_date_righticon'			=> '#7c7b7b',
					'timeline_date_righttext'			=> '#7c7b7b',
					'timeline_date_rightback'			=> '#f5f5f5',	
					// Border Settings
					'timeline_border_breaks' 			=> '',
					'timeline_border_single' 			=> '',
					'timeline_border_leftglobal' 		=> 'true',
					'timeline_border_leftcustom' 		=> '',
					'timeline_border_rightglobal' 		=> 'true',
					'timeline_border_rightcustom' 		=> '',
					// Dot Settings
					'timeline_dots_singlecolor'			=> '#bbbbbb',
					'timeline_dots_singleback'			=> '#ffffff',
					'timeline_dots_singleshadow'		=> 'rgba(0, 0, 0, 0.2)',
					'timeline_dots_singleline'			=> '#cccccc',
					'timeline_dots_leftglobal'			=> 'true',
					'timeline_dots_leftcolor'			=> '#bbbbbb',
					'timeline_dots_leftback'			=> '#ffffff',
					'timeline_dots_leftshadow'			=> 'rgba(0, 0, 0, 0.2)',
					'timeline_dots_leftline'			=> '#cccccc',
					'timeline_dots_rightglobal'			=> 'true',
					'timeline_dots_rightcolor'			=> '#bbbbbb',
					'timeline_dots_rightback'			=> '#ffffff',
					'timeline_dots_rightshadow'			=> 'rgba(0, 0, 0, 0.2)',
					'timeline_dots_rightline'			=> '#cccccc',
					// Break Settings
					'timeline_break_title'				=> '#676767',
					'timeline_break_titlefont'			=> 'Default',
					'timeline_break_titlesize'			=> 20,
					'timeline_break_titletype'			=> '',
					'timeline_break_titleback'			=> '#ededed',
					'timeline_break_text'				=> '#676767',
					'timeline_break_textfont'			=> 'Default',
					'timeline_break_texttype'			=> '',
					'timeline_break_textsize'			=> 13,
					'timeline_break_back'				=> '#dadada',
					// Section Settings
					'timeline_event_singletitle'		=> '#676767',
					'timeline_event_singletitlefont'	=> 'Default',
					'timeline_event_singletitletype'	=> '',
					'timeline_event_singletitleback'	=> '#ffffff',
					'timeline_event_singletext'			=> '#676767',
					'timeline_event_singletextfont'		=> 'Default',
					'timeline_event_singletexttype'		=> '',
					'timeline_event_singleback'			=> '#ffffff',
					'timeline_event_singletags'			=> '#b8bcbe',
					'timeline_event_singlecats'			=> '#b8bcbe',
					'timeline_event_leftglobal'			=> 'true',
					'timeline_event_lefttitle'			=> '#676767',
					'timeline_event_lefttitlefont'		=> 'Default',
					'timeline_event_lefttitletype'		=> '',
					'timeline_event_lefttitleback'		=> '#ffffff',
					'timeline_event_lefttext'			=> '#676767',
					'timeline_event_lefttextfont'		=> 'Default',
					'timeline_event_lefttexttype'		=> '',
					'timeline_event_leftback'			=> '#ffffff',
					'timeline_event_lefttags'			=> '#b8bcbe',
					'timeline_event_leftcats'			=> '#b8bcbe',
					'timeline_event_rightglobal'		=> 'true',
					'timeline_event_righttitle'			=> '#676767',
					'timeline_event_righttitlefont'		=> 'Default',
					'timeline_event_righttitletype'		=> '',
					'timeline_event_righttitleback'		=> '#ffffff',
					'timeline_event_righttext'			=> '#676767',
					'timeline_event_righttextfont'		=> 'Default',
					'timeline_event_righttexttype'		=> '',
					'timeline_event_rightback'			=> '#ffffff',
					'timeline_event_righttags'			=> '#b8bcbe',
					'timeline_event_rightcats'			=> '#b8bcbe',
					// Other Settings
					'content_wpautop'					=> 'false',					
					'margin_bottom'						=> '0',
					'margin_top' 						=> '0',					
					'el_id' 							=> '',
					'el_class'                  		=> '',
					'css'								=> '',
				), $atts ));
				
				if (($timeline_filter_allow == "true") && ($timeline_filter_cats == "false") && ($timeline_filter_tags == "false")) {
					$timeline_filter_allow				= "false";
				}
				
				if (($timeline_filter_allow == "true") || ($timeline_sort == "true")) {
					wp_enqueue_style('ts-extend-sumo');
					wp_enqueue_script('ts-extend-sumo');
				}
				
				$timeline_random                 		= mt_rand(999999, 9999999);
				
				if (!empty($el_id)) {
					$timeline_container_id				= $el_id;
				} else {
					$timeline_container_id				= 'ts-timeline-css-container-' . $timeline_random;
				}
				
				$output 								= '';
				$styles									= '';
				$rules									= '';
				$wpautop 								= ($content_wpautop == "true" ? true : false);
				$inline									= TS_VCSC_FrontendAppendCustomRules('style');
				
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					$vcinline_status					= 'true';
					$vcinline_class						= 'ts-timeline-css-edit';
					$vcinline_note						= '<div class="ts-composer-frontedit-message">' . __( 'This timeline is currently viewed in Visual Composer front-end editor mode. It is advised to edit such a complex element in back-end edit mode in order to avoid potential conflicts with other files loaded on the front-end of your website. The timeline is not functional in order to ensure display compatibility with the front-end editor.', "ts_visual_composer_extend" ) . '</div>';
					$vcinline_margin					= 35;
					$vcinline_controls					= 'false';
					$timeline_lazy						= 'false';
				} else {
					$vcinline_status					= 'false';
					$vcinline_class						= 'ts-timeline-css-view';
					$vcinline_note						= '';
					$vcinline_margin					= $margin_top;
					$vcinline_controls					= 'true';
					$timeline_lazy						= $timeline_lazy;
				}
	
				$timeline_class							= 'ts-timeline-css-container-' . str_replace("ts-timeline-css-", "", $timeline_layout);
				
				// CenterLine Extractions
				if (strpos($timeline_linetype, 'single') !== false) {
					$centerline_type					= "single";
				} else {
					$centerline_type					= "dual";
				}
				$centerline_style 						= str_replace($centerline_type, "", $timeline_linetype);
				
				// Font Extractions
				if (($timeline_title_custom == "true") && (strpos($timeline_title_family, 'Default') === false)) {
					$google_font_title					= TS_VCSC_GetFontFamily($timeline_container_id, $timeline_title_family, $timeline_title_type, false, true, false);
				} else {
					$google_font_title					= "";
				}
				if (($timeline_description_custom == "true") && (strpos($timeline_description_family, 'Default') === false)) {
					$google_font_desc					= TS_VCSC_GetFontFamily($timeline_container_id, $timeline_description_family, $timeline_description_type, false, true, false);
				} else {
					$google_font_desc					= "";
				}
				if (($timeline_start_custom == "true") && (strpos($timeline_start_family, 'Default') === false)) {
					$google_font_start					= TS_VCSC_GetFontFamily($timeline_container_id, $timeline_start_family, $timeline_start_type, false, true, false);
				} else {
					$google_font_start					= "";
				}
				if (($timeline_end_custom == "true") && (strpos($timeline_end_family, 'Default')) === false) {
					$google_font_end					= TS_VCSC_GetFontFamily($timeline_container_id, $timeline_end_family, $timeline_end_type, false, true, false);
				} else {
					$google_font_end					= "";
				}
				if (($timeline_custom_breaks == "true") && (strpos($timeline_break_titlefont, 'Default')) === false) {
					$google_font_breaktitle				= TS_VCSC_GetFontFamily($timeline_container_id, $timeline_break_titlefont, $timeline_break_titletype, false, true, false);
				} else {
					$google_font_breaktitle				= "";
				}
				if (($timeline_custom_breaks == "true") && (strpos($timeline_break_textfont, 'Default')) === false) {
					$google_font_breaktext				= TS_VCSC_GetFontFamily($timeline_container_id, $timeline_break_textfont, $timeline_break_texttype, false, true, false);
				} else {
					$google_font_breaktext				= "";
				}				
				if (($timeline_custom_events == "true") && (strpos($timeline_event_singletitlefont, 'Default')) === false) {
					$google_font_singletitle			= TS_VCSC_GetFontFamily($timeline_container_id, $timeline_event_singletitlefont, $timeline_event_singletitletype, false, true, false);
				} else {
					$google_font_singletitle			= "";
				}
				if (($timeline_custom_events == "true") && (strpos($timeline_event_singletextfont, 'Default')) === false) {
					$google_font_singletext				= TS_VCSC_GetFontFamily($timeline_container_id, $timeline_event_singletextfont, $timeline_event_singletexttype, false, true, false);
				} else {
					$google_font_singletext				= "";
				}
				if (($timeline_custom_events == "true") && ($timeline_event_leftglobal == "false") && (strpos($timeline_event_lefttitlefont, 'Default')) === false) {
					$google_font_lefttitle				= TS_VCSC_GetFontFamily($timeline_container_id, $timeline_event_lefttitlefont, $timeline_event_lefttitletype, false, true, false);
				} else {
					$google_font_lefttitle				= "";
				}
				if (($timeline_custom_events == "true") && ($timeline_event_leftglobal == "false") && (strpos($timeline_event_lefttextfont, 'Default')) === false) {
					$google_font_lefttext				= TS_VCSC_GetFontFamily($timeline_container_id, $timeline_event_lefttextfont, $timeline_event_lefttexttype, false, true, false);
				} else {
					$google_font_lefttext				= "";
				}
				if (($timeline_custom_events == "true") && ($timeline_event_rightglobal == "false") && (strpos($timeline_event_righttitlefont, 'Default')) === false) {
					$google_font_righttitle				= TS_VCSC_GetFontFamily($timeline_container_id, $timeline_event_righttitlefont, $timeline_event_righttitletype, false, true, false);
				} else {
					$google_font_righttitle				= "";
				}
				if (($timeline_custom_events == "true") && ($timeline_event_rightglobal == "false") && (strpos($timeline_event_righttextfont, 'Default')) === false) {
					$google_font_righttext				= TS_VCSC_GetFontFamily($timeline_container_id, $timeline_event_righttextfont, $timeline_event_righttexttype, false, true, false);
				} else {
					$google_font_righttext				= "";
				}
				
				// Filter Settings
				if ($timeline_filter_allow == "true") {
					$timeline_filter_data				= 'data-filter-allow="' . $timeline_filter_allow . '" data-filter-multiple="' . $timeline_filter_multiple . '" data-filter-deselect="' . $timeline_filter_deselect . '" data-filter-search="' . $timeline_filter_search . '" data-filter-confirm="' . $timeline_filter_confirm . '" data-filter-categories="' . $timeline_filter_cats . '" data-filter-tags="' . $timeline_filter_tags . '" data-filter-nocats="' . $timeline_filter_nocats . '" data-filter-notags="' . $timeline_filter_notags . '" data-filter-allcats="' . $timeline_filter_allcats . '" data-filter-alltags="' . $timeline_filter_alltags . '" data-filter-selected="' . $timeline_filter_selected . '" data-filter-selectall="' . $timeline_filter_selectall . '"';
				} else {
					$timeline_filter_data				= 'data-filter-allow="' . $timeline_filter_allow . '"';
				}
				$timeline_sumostrings					= 'data-sumo-confirm="' . $string_sumo_confirm . '" data-sumo-cancel="' . $string_sumo_cancel . '" data-sumo-allselected="' . $string_sumo_allselected . '" data-sumo-placeholder="' . $string_sumo_placeholder . '" data-sumo-searchcats="' . $string_sumo_searchcats . '" data-sumo-searchtags="' . $string_sumo_searchtags . '"';
				$timeline_rendering						= 'position: fixed; left: -99999px !important; top: -99999px !important;';
				
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 							= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-timeline-css-container ts-timeline-css-container-' . $timeline_order . ' clearFixMe ' . $el_class . ' ' . $vcinline_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Timeline_CSS_Container', $atts);
				} else {
					$css_class							= 'ts-timeline-css-container ts-timeline-css-container-' . $timeline_order . ' clearFixMe ' . $el_class . ' ' . $vcinline_class;
				}

				// Main Output
				$output .= '<div id="' . $timeline_container_id . '" class="' . $css_class . ' ' . $timeline_class . ' ' . ($vcinline_status == "false" ? "ts-timeline-css-rendering" : "") . '" data-type="timeline" data-frontend="' . $vcinline_status . '" data-iframewrap="' . $timeline_iframewrap . '" data-layout="' . $timeline_layout . '" data-initial="' . $timeline_initial . '" data-rowstacks="' . $timeline_stacks . '" ' . $timeline_filter_data . ' ' . $timeline_sumostrings . ' data-sorter="' . $timeline_sort . '" data-switch="' . $timeline_switch . '" data-order="' . $timeline_order .'" data-lazy="' . $timeline_lazy . '" data-count="' . $timeline_count . '" data-trigger="' . $timeline_trigger . '" data-break="' . $timeline_break . '" style="margin-top: ' . $vcinline_margin . 'px; margin-bottom: ' . $margin_bottom . 'px; width: 100%;">';
					$output .= $vcinline_note;
					// Custom Styling Output
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "false") {						
						// Main Header Section
						if ($timeline_title_custom == "true") {
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-title-wrapper {';
								$rules .= 'border-bottom-color: ' . $timeline_title_border . ';';
							$rules .= '}';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-title-wrapper .ts-timeline-css-title-string {';
								$rules .= 'color: ' . $timeline_title_color . ';';
								$rules .= 'background-color: ' . $timeline_title_back . ';';
								$rules .= $google_font_title;
							$rules .= '}';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-title-wrapper .ts-timeline-css-title-after {';
								$rules .= 'border-bottom-color: ' . $timeline_title_back . ';';
							$rules .= '}';
						}
						// Description Section
						if ($timeline_description_custom == "true") {
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-header {';
								$rules .= 'background-color: ' . $timeline_description_back . ';';
								$rules .= 'border: 1px solid ' . $timeline_description_border . ';';
							$rules .= '}';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-header:after {';
								$rules .= 'border-top-color ' . $timeline_description_border . ';';
							$rules .= '}';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-header .ts-timeline-css-header-title {';
								$rules .= 'color: ' . $timeline_description_color . ';';
								$rules .= 'text-align: ' . $timeline_description_align . ';';
								$rules .= $google_font_desc;
							$rules .= '}';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-header .ts-timeline-css-header-description {';
								$rules .= 'color: ' . $timeline_description_color . ';';
								$rules .= 'text-align: ' . $timeline_description_align . ';';
								$rules .= $google_font_desc;
							$rules .= '}';
						}
						// Start/End Indicators
						if ($timeline_start_custom == "true") {
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-header-wrap .ts-timeline-css-end .ts-timeline-css-end-text,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-footer-wrap .ts-timeline-css-end .ts-timeline-css-end-text {';
								$rules .= 'color: ' . $timeline_start_color . ';';
								$rules .= 'background: ' . $timeline_start_back . ';';
								$rules .= $google_font_start;
								$rules .= 'border: 1px solid ' . $timeline_start_border . ';';
							$rules .= '}';
						}
						if ($timeline_end_custom == "true") {
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-begin.ts-timeline-css-begin-top .ts-timeline-css-begin-text,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-begin.ts-timeline-css-begin-bottom .ts-timeline-css-begin-text {';						
								$rules .= 'color: ' . $timeline_end_color . ';';
								$rules .= 'background: ' . $timeline_end_back . ';';
								$rules .= $google_font_end;
								$rules .= 'border: 1px solid ' . $timeline_end_border . ';';
							$rules .= '}';
						}
						// Center Line (Spine)
						if ($timeline_linetype != "default") {
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-wrapper .ts-timeline-css-spine {';
								$rules .= 'background-image: none;';
								if ($centerline_type == "single") {
									$rules .= 'width: ' . $timeline_linestrength . 'px;';
									if ($centerline_style == "gradient") {
										$rules .= $timeline_linegradient1;
									} else {
										$rules .= 'margin-left: -' . ($timeline_linestrength / 2) . 'px;';
										$rules .= 'border-left: ' . $timeline_linestrength . 'px ' . $centerline_style . ' ' . $timeline_linecolor1 . ';';
									}
								} else if ($centerline_type == "dual") {
									$rules .= 'width: ' . ($timeline_linestrength + $timeline_linespace) . 'px;';
									$rules .= 'margin-left: -' . (($timeline_linestrength + $timeline_linespace) / 2) . 'px;';
									if ($centerline_style != "gradient") {
										$rules .= 'border-left: ' . ($timeline_linestrength / 2) . 'px ' . $centerline_style . ' ' . $timeline_linecolor1 . ';';
										$rules .= 'border-right: ' . ($timeline_linestrength / 2) . 'px ' . $centerline_style . ' ' . $timeline_linecolor2 . ';';
									}
								}									
							$rules .= '}';
							if (($centerline_type == "dual") && ($centerline_style == "gradient")) {
								$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-wrapper .ts-timeline-css-spine:before {';
									$rules .= 'content: "";';
									$rules .= 'width: ' . ($timeline_linestrength / 2) . 'px;';
									$rules .= $timeline_linegradient1;
								$rules .= '}';
								$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-wrapper .ts-timeline-css-spine:after {';
									$rules .= 'content: "";';
									$rules .= 'width: ' . ($timeline_linestrength / 2) . 'px;';
									$rules .= $timeline_linegradient2;
								$rules .= '}';
							}
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-wrapper.ts-timeline-css-columns .ts-timeline-css-spine {';
								if ($centerline_type == "single") {
									$rules .= 'margin-left: -' . ($timeline_linestrength / 2) . 'px;';
								} else if ($centerline_type == "dual") {
									$rules .= 'margin-left: -' . (($timeline_linestrength + $timeline_linespace) / 2) . 'px;';
								}
							$rules .= '}';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-wrapper.ts-timeline-css-left .ts-timeline-css-spine {';
								if ($centerline_type == "single") {
									$rules .= 'right: ' . ((16 - $timeline_linestrength) / 2) . 'px;';
								} else if ($centerline_type == "dual") {
									$rules .= 'right: ' . ((16 - $timeline_linestrength - $timeline_linespace) / 2) . 'px;';
								}
							$rules .= '}';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-wrapper.ts-timeline-css-right .ts-timeline-css-spine {';
								if ($centerline_type == "single") {
									$rules .= 'left: ' . ((16 - $timeline_linestrength) / 2) . 'px;';
								} else if ($centerline_type == "dual") {
									$rules .= 'left: ' . ((16 - $timeline_linestrength - $timeline_linespace) / 2) . 'px;';
								}
							$rules .= '}';
						}
						// General Settings
						if ($timeline_custom_events == "true") {
							// Event Date Time
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-content .ts-timeline-css-text-wrap .ts-timeline-css-date {';
								$rules .= 'background: ' . $timeline_date_singleback . ';';
							$rules .= '}';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-content .ts-timeline-css-text-wrap .ts-timeline-css-date .ts-timeline-css-date-icon {';
								$rules .= 'color: ' . $timeline_date_singleicon . ';';
							$rules .= '}';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-content .ts-timeline-css-text-wrap .ts-timeline-css-date .ts-timeline-css-date-text {';
								$rules .= 'color: ' . $timeline_date_singletext . ';';
							$rules .= '}';
							// Event Content
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-content .ts-timeline-css-text-wrap .ts-timeline-css-title {';
								$rules .= 'color: ' . $timeline_event_singletitle . ';';
								$rules .= 'background: ' . $timeline_event_singletitleback . ';';
								$rules .= $google_font_singletitle;
							$rules .= '}';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-content .ts-timeline-css-text-wrap .ts-timeline-css-text-wrap-inner {';
								$rules .= 'color: ' . $timeline_event_singletext . ';';
								$rules .= $google_font_singletext;
							$rules .= '}';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-content .ts-timeline-css-text-wrap .ts-timeline-css-output-cats {';
								$rules .= 'color: ' . $timeline_event_singlecats . ';';
							$rules .= '}';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-content .ts-timeline-css-text-wrap .ts-timeline-css-output-tags {';
								$rules .= 'color: ' . $timeline_event_singletags . ';';
							$rules .= '}';
							// Event Border + Background
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-content .ts-timeline-css-event .ts-timeline-css-text-wrap,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-fullwidth .ts-timeline-css-text-wrap {';
								$rules .= 'background: ' . $timeline_event_singleback . ';';
								$rules .= str_replace('|', '', $timeline_border_single);
							$rules .= '}';
							// Event Connector Dots
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-left .ts-timeline-css-event:before,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-left .ts-timeline-css-event-left:before,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-left .ts-timeline-css-event-right:before,';	
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-left .ts-timeline-css-fullwidth:before,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-right .ts-timeline-css-event:before,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-right .ts-timeline-css-event-left:before,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-right .ts-timeline-css-event-right:before,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-right .ts-timeline-css-fullwidth:before,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-left:before,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-right:before {';
								$rules .= 'border-top-color: ' . $timeline_dots_singleline . ';';
							$rules .= '}';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-left .ts-timeline-css-event:after,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-left .ts-timeline-css-event-left:after,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-left .ts-timeline-css-event-right:after,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-left .ts-timeline-css-fullwidth:after,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-right .ts-timeline-css-event:after,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-right .ts-timeline-css-event-left:after,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-right .ts-timeline-css-event-right:after,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-right .ts-timeline-css-fullwidth:after,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-left:after,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-right:after {';
								$rules .= 'background-color: ' . $timeline_dots_singlecolor . ';';
								$rules .= 'border-color: ' . $timeline_dots_singleback . ';';
								$rules .= '-webkit-box-shadow: 0px 0px 2px ' . $timeline_dots_singleshadow . ';';
								$rules .= '-moz-box-shadow: 0px 0px 2px ' . $timeline_dots_singleshadow . ';';
								$rules .= 'box-shadow: 0px 0px 2px ' . $timeline_dots_singleshadow . ';';									
							$rules .= '}';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-responsive .ts-timeline-css-event.ts-timeline-css-order-asc:after,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-responsive .ts-timeline-css-fullwidth.ts-timeline-css-order-asc:after {';
								$rules .= 'border-top-color: ' . $timeline_dots_singleline . ';';
							$rules .= '}';							
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-responsive .ts-timeline-css-event.ts-timeline-css-order-desc:before,';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-responsive .ts-timeline-css-fullwidth.ts-timeline-css-order-desc:before {';
								$rules .= 'border-bottom-color: ' . $timeline_dots_singleline . ';';
							$rules .= '}';
						}
						// Break Settings
						if ($timeline_custom_breaks == "true") {
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-content .ts-timeline-css-break .ts-timeline-css-text-wrap {';
								$rules .= str_replace('|', '', $timeline_border_breaks);
								$rules .= 'background: ' . $timeline_break_back . ';';
							$rules .= '}';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-content .ts-timeline-css-break .ts-timeline-css-text-wrap .ts-timeline-css-title {';
								$rules .= $google_font_breaktitle;
								$rules .= 'color: ' . $timeline_break_title . ';';
								$rules .= 'background: ' . $timeline_break_titleback . ';';
								$rules .= 'font-size: ' . $timeline_break_titlesize . 'px;';
							$rules .= '}';
							$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-content .ts-timeline-css-break .ts-timeline-css-text-wrap .ts-timeline-css-text {';
								$rules .= $google_font_breaktext;
								$rules .= 'color: ' . $timeline_break_text . ';';
								$rules .= 'font-size: ' . $timeline_break_textsize . 'px;';
							$rules .= '}';
						}
						// Column Event Settings
						if ($timeline_custom_events == "true") {
							// Left Column
							if ((($timeline_custom_columns == "left") || ($timeline_custom_columns == "both")) && ($timeline_layout == 'ts-timeline-css-columns')) {
								// Left Date/Time
								if ($timeline_date_leftglobal == "false") {
									$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-left .ts-timeline-css-text-wrap .ts-timeline-css-date {';
										$rules .= 'background: ' . $timeline_date_leftback . ';';
									$rules .= '}';
									$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-left .ts-timeline-css-text-wrap .ts-timeline-css-date .ts-timeline-css-date-icon {';
										$rules .= 'color: ' . $timeline_date_lefticon . ';';
									$rules .= '}';
									$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-left .ts-timeline-css-text-wrap .ts-timeline-css-date .ts-timeline-css-date-text {';
										$rules .= 'color: ' . $timeline_date_lefttext . ';';
									$rules .= '}';
								}								
								// Left Content
								if ($timeline_event_leftglobal == "false") {
									$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-left .ts-timeline-css-text-wrap .ts-timeline-css-title {';
										$rules .= 'color: ' . $timeline_event_lefttitle . ';';
										$rules .= 'background: ' . $timeline_event_lefttitleback . ';';
										$rules .= $google_font_lefttitle;
									$rules .= '}';
									$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-left .ts-timeline-css-text-wrap .ts-timeline-css-text-wrap-inner {';
										$rules .= 'color: ' . $timeline_event_lefttext . ';';
										$rules .= $google_font_lefttext;
									$rules .= '}';
									$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-left .ts-timeline-css-text-wrap .ts-timeline-css-output-cats {';
										$rules .= 'color: ' . $timeline_event_leftcats . ';';
									$rules .= '}';
									$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-left .ts-timeline-css-text-wrap .ts-timeline-css-output-tags {';
										$rules .= 'color: ' . $timeline_event_lefttags . ';';
									$rules .= '}';
								}
								// Left Border + Background
								if (($timeline_border_leftglobal == "false") || ($timeline_event_leftglobal == "false")) {
									$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-left .ts-timeline-css-text-wrap {';
										if ($timeline_border_leftglobal == "false") {
											$rules .= str_replace('|', '', $timeline_border_leftcustom);
										}
										if ($timeline_event_leftglobal == "false"){
											$rules .= 'background: ' . $timeline_event_leftback . ';';
										}
									$rules .= '}';
								}
								// Left Connector Dots
								if ($timeline_dots_leftglobal == "false") {
									$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-left:before {';
										$rules .= 'border-top-color: ' . $timeline_dots_leftline . ';';									
									$rules .= '}';
									$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-left:after {';
										$rules .= 'background-color: ' . $timeline_dots_leftcolor . ';';
										$rules .= 'border-color: ' . $timeline_dots_leftback . ';';
										$rules .= '-webkit-box-shadow: 0px 0px 2px ' . $timeline_dots_leftshadow . ';';
										$rules .= '-moz-box-shadow: 0px 0px 2px ' . $timeline_dots_leftshadow . ';';
										$rules .= 'box-shadow: 0px 0px 2px ' . $timeline_dots_leftshadow . ';';									
									$rules .= '}';
								}
							}
							// Right Column
							if ((($timeline_custom_columns == "right") || ($timeline_custom_columns == "both")) && ($timeline_layout == 'ts-timeline-css-columns')) {
								// Right Date/Time
								if ($timeline_date_rightglobal == "false") {
									$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-right .ts-timeline-css-text-wrap .ts-timeline-css-date {';
										$rules .= 'background: ' . $timeline_date_rightback . ';';
									$rules .= '}';
									$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-right .ts-timeline-css-text-wrap .ts-timeline-css-date .ts-timeline-css-date-icon {';
										$rules .= 'color: ' . $timeline_date_righticon . ';';
									$rules .= '}';
									$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-right .ts-timeline-css-text-wrap .ts-timeline-css-date .ts-timeline-css-date-text {';
										$rules .= 'color: ' . $timeline_date_righttext . ';';
									$rules .= '}';
								}
								// Right Content
								if ($timeline_event_rightglobal == "false") {
									$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-right .ts-timeline-css-text-wrap .ts-timeline-css-title {';
										$rules .= 'color: ' . $timeline_event_righttitle . ';';
										$rules .= 'background: ' . $timeline_event_righttitleback . ';';
										$rules .= $google_font_righttitle;
									$rules .= '}';
									$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-right .ts-timeline-css-text-wrap .ts-timeline-css-text-wrap-inner {';
										$rules .= 'color: ' . $timeline_event_righttext . ';';
										$rules .= $google_font_righttext;
									$rules .= '}';
									$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-right .ts-timeline-css-text-wrap .ts-timeline-css-output-cats {';
										$rules .= 'color: ' . $timeline_event_rightcats . ';';
									$rules .= '}';
									$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-right .ts-timeline-css-text-wrap .ts-timeline-css-output-tags {';
										$rules .= 'color: ' . $timeline_event_righttags . ';';
									$rules .= '}';
								}
								// Right Border + Background
								if (($timeline_border_rightglobal == "false") || ($timeline_event_rightglobal == "false")) {
									$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-right .ts-timeline-css-text-wrap {';
										if ($timeline_border_rightglobal == "false") {
											$rules .= str_replace('|', '', $timeline_border_rightcustom);
										}
										if ($timeline_event_rightglobal == "false") {
											$rules .= 'background: ' . $timeline_event_rightback . ';';
										}
									$rules .= '}';
								}
								// Right Connector Dots
								if ($timeline_dots_rightglobal == "false") {
									$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-right:before {';
										$rules .= 'border-top-color: ' . $timeline_dots_rightline . ';';
									$rules .= '}';
									$rules .= '#' . $timeline_container_id . '.ts-timeline-css-container .ts-timeline-css-columns .ts-timeline-css-event-right:after {';
										$rules .= 'background-color: ' . $timeline_dots_rightcolor . ';';
										$rules .= 'border-color: ' . $timeline_dots_rightback . ';';
										$rules .= '-webkit-box-shadow: 0px 0px 2px ' . $timeline_dots_rightshadow . ';';
										$rules .= '-moz-box-shadow: 0px 0px 2px ' . $timeline_dots_rightshadow . ';';
										$rules .= 'box-shadow: 0px 0px 2px ' . $timeline_dots_rightshadow . ';';	
									$rules .= '}';
								}
							}
						}
						// Final Output
						if ($rules != '') {
							if ($inline == "false") {
								$styles .= '<style id="' . $timeline_container_id . '-styles" type="text/css">';
									$styles .= $rules;
								$styles .= '</style>';
								echo TS_VCSC_MinifyCSS($styles);
							} else if ($inline == "true") {
								wp_add_inline_style('ts-visual-composer-extend-custom', TS_VCSC_MinifyCSS($rules));
							}
						}
					}
					// Controls Section
					$output .= '<div class="ts-timeline-css-controls">';
						// Filter Controls
						if (($timeline_filter_allow == "true") && (($timeline_filter_cats == "true") || ($timeline_filter_tags == "true"))) {
							// Categories Filter
							if ($timeline_filter_cats == "true") {
								$output .= '<div id="ts-timeline-css-filters-cats-' . $timeline_random . '" class="ts-timeline-css-filters-cats ts-timeline-css-filters" data-random="' . $timeline_random . '" data-target="ts-timeline-css-content-' . $timeline_random . '" style="display: none;">';
									$output .= '<label class="ts-timeline-css-filters-cats-label" style="display: inline-block; margin-left: 0;" for="ts-timeline-css-filters-cats-sections-' . $timeline_random . '">' . $timeline_filter_labelcats . '</label>';
									$output .= '<select id="ts-timeline-css-filters-cats-sections-' . $timeline_random . '" class="ts-timeline-css-filters-cats-sections" ' . ($timeline_filter_multiple == "true" ? 'multiple="multiple"' : '') . ' data-option="ts-timeline-css-filters-cats-sections-' . $timeline_random . '" data-target="ts-timeline-css-content-' . $timeline_random . '">';
										if ($timeline_filter_multiple == "false") {
											$output .= '<option value="all" data-type="categories" selected="selected">' . $timeline_filter_allcats . '</option>';
										}
									$output .= '</select>';
								$output .= '</div>';
							}
							// Tags Filter
							if ($timeline_filter_tags == "true") {
								$output .= '<div id="ts-timeline-css-filters-tags-' . $timeline_random . '" class="ts-timeline-css-filters-tags ts-timeline-css-filters" data-random="' . $timeline_random . '" data-target="ts-timeline-css-content-' . $timeline_random . '" style="display: none;">';
									$output .= '<label class="ts-timeline-css-filters-tags-label" style="display: inline-block; margin-left: 0;" for="ts-timeline-css-filters-tags-sections-' . $timeline_random . '">' . $timeline_filter_labeltags . '</label>';
									$output .= '<select id="ts-timeline-css-filters-tags-sections-' . $timeline_random . '" class="ts-timeline-css-filters-tags-sections" ' . ($timeline_filter_multiple == "true" ? 'multiple="multiple"' : '') . ' data-option="ts-timeline-css-filters-tags-sections-' . $timeline_random . '" data-target="ts-timeline-css-content-' . $timeline_random . '">';
										if ($timeline_filter_multiple == "false") {
											$output .= '<option value="all" data-type="tags" selected="selected">' . $timeline_filter_alltags . '</option>';
										}
									$output .= '</select>';
								$output .= '</div>';
							}
						}					
						// Sorter Controls
						if (($timeline_sort == "true") && ($vcinline_controls == "true")) {
							$output .= '<div id="ts-timeline-css-sorter-' . $timeline_random . '" class="ts-timeline-css-sorter" data-random="' . $timeline_random . '" data-target="ts-timeline-css-content-' . $timeline_random . '" style="display: none;">';
								$output .= '<label class="ts-timeline-css-sorter-label" style="display: inline-block; margin-left: 0;" for="ts-timeline-css-filters-tags-sections-' . $timeline_random . '">' . $timeline_sort_label . '</label>';
								$output .= '<select id="ts-timeline-css-sorter-sections-' . $timeline_random . '" class="ts-timeline-css-sorter-sections" data-option="ts-timeline-css-sorter-sections-' . $timeline_random . '" data-target="ts-timeline-css-content-' . $timeline_random . '">';
									$output .= '<option value="asc" ' . ($timeline_order == 'asc' ? 'selected="selected"' : '') . '>' . $timeline_sort_asc . '</option>';
									$output .= '<option value="desc" ' . ($timeline_order == 'desc' ? 'selected="selected"' : '') . '>' . $timeline_sort_desc . '</option>';
								$output .= '</select>';
							$output .= '</div>';
						}
					$output .= '</div>';
					// Timeline Title
					if (!empty($timeline_title)) {
						$output .= '<div class="ts-timeline-css-title-wrapper">';
							$output .= '<div class="ts-timeline-css-title-string">' . $timeline_title . '</div><div class="ts-timeline-css-title-after"></div>';
						$output .= '</div>';
					}					
					if (!empty($timeline_end)) {
						$output .= '<div class="ts-timeline-css-begin ts-timeline-css-begin-top">';
							$output .= '<div class="ts-timeline-css-begin-text">' . $timeline_end . '</div>';
						$output .= '</div>';
					}
					if ((!empty($timeline_title)) || (!empty($timeline_description)) || (!empty($timeline_end))) {
						$output .= '<div class="ts-timeline-css-header-wrap">';
							if ((!empty($timeline_title)) || (!empty($timeline_description))) {
								$output .= '<div class="ts-timeline-css-header">';
									if ((!empty($timeline_title)) && ($timeline_title_show == "true")) {
										$output .= '<h4 class="ts-timeline-css-header-title">' . $timeline_title . '</h4>';
									}
									if (!empty($timeline_description)) {
										$output .= '<p class="ts-timeline-css-header-description">' . rawurldecode(base64_decode(strip_tags($timeline_description))) . '</p>';
									}
								$output .= '</div>';
							}
							if (!empty($timeline_start)) {
								$output .= '<div class="ts-timeline-css-end">';
									$output .= '<div class="ts-timeline-css-end-text">' . $timeline_start . '</div>';
								$output .= '</div>';
							}
						$output .= '</div>';
					}					
					// Preloader Animation
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "false") {
						$output .= '<div class="ts-timeline-css-preloader">';
							$output .= TS_VCSC_CreatePreloaderCSS("ts-timeline-css-preloader-" . $timeline_random, "", $timeline_preloader, "true");
						$output .= '</div>';
					}
					// Timeline Sections
					$output .= '<div class="ts-timeline-css-wrapper ' . $timeline_layout . '" style="' . $timeline_rendering . '">';						
						$output .= '<div id="ts-timeline-css-spine-' . $timeline_random . '" class="ts-timeline-css-spine ts-timeline-css-animated"></div>';
						$output .= '<div id="ts-timeline-css-content-' . $timeline_random . '" class="ts-timeline-css-content ' . ($timeline_show_cats == "true" ? "ts-timeline-css-content-show-cats" : "ts-timeline-css-content-hide-cats") . ' ' . ($timeline_show_tags == "true" ? "ts-timeline-css-content-show-tags" : "ts-timeline-css-content-hide-tags") . '">';
							if (function_exists('wpb_js_remove_wpautop')){
								$output .= wpb_js_remove_wpautop(do_shortcode($content), $wpautop);
							} else {
								$output .= do_shortcode($content);
							}
						$output .= '</div>';
					$output .= '</div>';					
					if ($timeline_lazy == "true") {
						$output .= '<div class="ts-timeline-css-showmore-wrap">';
							$output .= '<span class="ts-timeline-css-showmore ts-dual-buttons-color-peter-river-flat ts-dual-buttons-color-belize-hole-flat">' . $timeline_load . '</span>';
						$output .= '</div>';
					}					
					if ((!empty($timeline_title)) || (!empty($timeline_description)) || (!empty($timeline_end))) {
						$output .= '<div class="ts-timeline-css-footer-wrap">';
							if (!empty($timeline_start)) {
								$output .= '<div class="ts-timeline-css-end">';
									$output .= '<div class="ts-timeline-css-end-text">' . $timeline_start . '</div>';
								$output .= '</div>';
							}
							if ((!empty($timeline_title)) || (!empty($timeline_description))) {
								$output .= '<div class="ts-timeline-css-footer">';
									if (!empty($timeline_title)) {
										$output .= '<h4 class="ts-timeline-css-footer-title">' . $timeline_title . '</h4>';
									}
									if (!empty($timeline_description)) {
										$output .= '<p class="ts-timeline-css-footer-description">' . rawurldecode(base64_decode(strip_tags($timeline_description))) . '</p>';
									}
								$output .= '</div>';
							}
						$output .= '</div>';
					}
					if (!empty($timeline_end)) {
						$output .= '<div class="ts-timeline-css-begin ts-timeline-css-begin-bottom">';
							$output .= '<div class="ts-timeline-css-begin-text">' . $timeline_end . '</div>';
						$output .= '</div>';
					}
	
				$output .= '</div>';
				
				echo $output;
				
				unset($rules);
				unset($styles);
				unset($output);
				
				$myvariable = ob_get_clean();
				return $myvariable;
			}
			
			// Add Timeline Elements
			function TS_VCSC_Add_Timeline_CSS_Element_Container() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Timeline Container Element
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					"name"                              => __("TS CSS Media Timeline", "ts_visual_composer_extend"),
					"base"                              => "TS_VCSC_Timeline_CSS_Container",
					"icon"                              => "ts-composer-element-icon-timeline-container",
					"category"                          => __("VC Extensions", "ts_visual_composer_extend"),
					"as_parent"                         => array('only' => 'TS_VCSC_Timeline_CSS_Section,TS_VCSC_Timeline_CSS_Event,TS_VCSC_Timeline_CSS_Break'),
					"description"                       => __("Build a custom Media Timeline", "ts_visual_composer_extend"),
					"controls" 							=> "full",
					"content_element"                   => true,
					"is_container" 						=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_UseExtendedNesting == "true" ? false : true),
					"container_not_allowed" 			=> false,
					"show_settings_on_create"           => true,
					"admin_enqueue_js"            		=> "",
					"admin_enqueue_css"           		=> "",
					"js_view"                           => "VcColumnView",
					"params"                            => array(
						// General Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_1",
							"seperator"					=> "General Setup",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Timeline Standard Layout", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_layout",
							"width"             		=> 200,
							"value"             		=> array(
								__( 'Dual Columns with Center Spine', "ts_visual_composer_extend" )				=> "ts-timeline-css-columns",
								__( 'One Column with Center Spine', "ts_visual_composer_extend" )				=> "ts-timeline-css-responsive",
								__( 'One Column Right with Left Spine', "ts_visual_composer_extend" )			=> "ts-timeline-css-right",
								__( 'One Column Left with Right Spine', "ts_visual_composer_extend" )			=> "ts-timeline-css-left",
							),
							"admin_label"           	=> true,
							"description"       		=> __( "Select the standard layout for the timeline.", "ts_visual_composer_extend" )
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "One Column Breakpoint", "ts_visual_composer_extend" ),
							"param_name"                => "timeline_break",
							"value"                     => "600",
							"min"                       => "100",
							"max"                       => "2048",
							"step"                      => "1",
							"unit"                      => 'px',
							"admin_label"           	=> true,
							"description"               => __( "Define a breakpoint in pixels at which the timeline should switch to a one column layout.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_layout", 'value' => 'ts-timeline-css-columns' )
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Timeline Switch Layout", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_switch",
							"width"             		=> 200,
							"value"             		=> array(
								__( 'One Column with Center Spine', "ts_visual_composer_extend" )				=> "ts-timeline-css-responsive",
								__( 'One Column Right with Left Spine', "ts_visual_composer_extend" )			=> "ts-timeline-css-right",
								__( 'One Column Left with Right Spine', "ts_visual_composer_extend" )			=> "ts-timeline-css-left",
							),
							"admin_label"           	=> true,
							"description"       		=> __( "Select the layout to which the timeline should switch if the breakpoint is triggered.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_layout", 'value' => 'ts-timeline-css-columns' )
						),						
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Columns Direction", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_initial",
							"width"             		=> 200,
							"value"             		=> array(
								__( 'Left To Right', "ts_visual_composer_extend" )							=> "left",
								__( 'Right To Left', "ts_visual_composer_extend" )							=> "right",
							),
							"admin_label"           	=> true,
							"description"       		=> __( "Select what column direction should be used for the dual columns layout.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_layout", 'value' => 'ts-timeline-css-columns' )
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Single Lines", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_stacks",
							"value"             		=> "false",
							"description"		    	=> __( "Switch the toggle if you want to show each timeline section in its own row, instead of creating a masonry like layout.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_layout", 'value' => 'ts-timeline-css-columns' )
						),						
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Initial Order", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_order",
							"width"             		=> 200,
							"value"             		=> array(
								__( 'Oldest (Top) to Newest (Bottom)', "ts_visual_composer_extend" )		=> "asc",
								__( 'Newest (Top) to Oldest (Bottom)', "ts_visual_composer_extend" )		=> "desc",
							),
							"admin_label"           	=> true,
							"description"       		=> __( "Select in which order the timeline events are arranged in Visual Composer.", "ts_visual_composer_extend" )
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Wrap iFrames", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_iframewrap",
							"value"             		=> "false",
							"description"		    	=> __( "Switch the toggle if you want to wrap all manually embedded iFrames within the section content with another DIV in order to force a standard 16:9 display ratio.", "ts_visual_composer_extend" ),
						),	
						// Preloader Setting
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_2",
							"seperator"					=> "Preloader Settings",
						),
						array(
							"type"				    	=> "livepreview",
							"heading"			    	=> __( "Preloader Style", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_preloader",
							"preview"					=> "preloaders",
							"value"                 	=> 0,
							"description"		    	=> __( "Select the style for the preloader animation to be shown while the element is rendering.", "ts_visual_composer_extend" ),
						),
						// Timeline Spine Line
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_3",
							"seperator"					=> "Timeline Spine",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Spine Line: Type", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_linetype",
							"width"             		=> 300,
							"value"             		=> array(
								__( "Default Styling", "ts_visual_composer_extend" )				=> "default",								
								__( "Single Solid Line", "ts_visual_composer_extend" )				=> "singlesolid",
								__( "Single Dotted Line", "ts_visual_composer_extend" )				=> "singledotted",
								__( "Single Dashed Line", "ts_visual_composer_extend" )				=> "singledashed",
								__( "Single Gradient Line", "ts_visual_composer_extend" )			=> "singlegradient",
								__( "Dual Solid Lines", "ts_visual_composer_extend" )				=> "dualsolid",
								__( "Dual Dotted Lines", "ts_visual_composer_extend" )				=> "dualdotted",
								__( "Dual Dashed Lines", "ts_visual_composer_extend" )				=> "dualdashed",
								__( "Dual Gradient Lines", "ts_visual_composer_extend" )			=> "dualgradient",
							),
							"description"       		=> __( "Select if you want to assign different styles to content sections when using the dual columns layout.", "ts_visual_composer_extend" ),
						),
						array(
							"type"						=> "advanced_gradient",
							"heading"					=> __("Spine Line: Gradient 1", "ts_visual_composer_extend"),						
							"param_name"				=> "timeline_linegradient1",
							"dependency"        		=> array( 'element' => "timeline_linetype", 'value' => array('singlegradient', 'dualgradient') ),
						),
						array(
							"type"						=> "advanced_gradient",
							"heading"					=> __("Spine Line: Gradient 2", "ts_visual_composer_extend"),						
							"param_name"				=> "timeline_linegradient2",
							"dependency"        		=> array( 'element' => "timeline_linetype", 'value' => array('dualgradient') ),
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Spine Line: Color 1", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_linecolor1",
							"value"             		=> "#cccccc",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_linetype", 'value' => array('singlesolid', 'singledotted', 'singledashed', 'dualsolid', 'dualdotted', 'dualdashed') ),
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Spine Line: Color 2", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_linecolor2",
							"value"             		=> "#cccccc",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_linetype", 'value' => array('dualsolid', 'dualdotted', 'dualdashed') ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Spine Line: Width", "ts_visual_composer_extend" ),
							"param_name"                => "timeline_linestrength",
							"value"                     => "4",
							"min"                       => "2",
							"max"                       => "10",
							"step"                      => "2",
							"unit"                      => 'px',
							"dependency"            	=> array( 'element' => "timeline_linetype", 'value' => array('singlegradient', 'singlesolid', 'singledotted', 'singledashed', 'dualgradient', 'dualsolid', 'dualdotted', 'dualdashed') ),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Spine Line: Dual Spacing", "ts_visual_composer_extend" ),
							"param_name"                => "timeline_linespace",
							"value"                     => "2",
							"min"                       => "0",
							"max"                       => "4",
							"step"                      => "2",
							"unit"                      => 'px',
							"dependency"            	=> array( 'element' => "timeline_linetype", 'value' => array('dualgradient', 'dualsolid', 'dualdotted', 'dualdashed') ),
						),
						// LazyLoad Imitation
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_4",
							"seperator"					=> "Lazy-Load Imitation",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Lazy-Load Effect", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_lazy",
							"value"             		=> "false",
							"admin_label"           	=> true,
							"description"		    	=> __( "Switch the toggle if you want to show a limited number of events at a time, showing more the further you scroll.", "ts_visual_composer_extend" )
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Number of Events", "ts_visual_composer_extend" ),
							"param_name"                => "timeline_count",
							"value"                     => "10",
							"min"                       => "1",
							"max"                       => "200",
							"step"                      => "1",
							"unit"                      => '',
							"description"               => __( "Define how many events should be shown per Lazy-Load Event.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_lazy", 'value' => 'true' )
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Lazy-Load Trigger", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_trigger",
							"width"             		=> 200,
							"value"             		=> array(
								__( 'Scroll', "ts_visual_composer_extend" )      	=> "scroll",
								__( 'Click', "ts_visual_composer_extend" )         	=> "click",
							),
							"description"       		=> __( "Select how the Lazy-Load Effect should be triggered for the timeline.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_lazy", 'value' => 'true' )
						),
						array(
							"type"                      => "textfield",
							"heading"                   => __( "Text for 'Load More' Button", "ts_visual_composer_extend" ),
							"param_name"                => "timeline_load",
							"value"                     => "Load More",
							"description"               => __( "Enter a text to be shown inside the 'Load More' trigger button.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_lazy", 'value' => 'true' )
						),
						// Timeline Header
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_5",
							"seperator"					=> "Timeline Header",
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "Header: Title", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_title",
							"value"             		=> "",
							"description"       		=> __( "Enter a title for the interactive media timeline.", "ts_visual_composer_extend" ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Header: Customize", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_title_custom",
							"value"             		=> "false",
							"description"		    	=> __( "Switch the toggle if you want to customize the header styling.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_title", 'not_empty' => true ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Header: Font Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_title_color",
							"value"             		=> "#7c7979",
							"description"       		=> __( "Define the font color for the header section.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_title_custom", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Header: Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_title_back",
							"value"             		=> "#ededed",
							"description"       		=> __( "Define the background color for the header section.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_title_custom", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Header: Border Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_title_border",
							"value"             		=> "#ababab",
							"description"       		=> __( "Define the bottom border color for the header section.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_title_custom", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 			        => "Additions",
						),		
						array(
							"type"              		=> "fontsmanager",
							"heading"           		=> __( "Header: Font Family", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_title_family",
							"value"             		=> "",
							"default"					=> "true",
							"connector"					=> "timeline_title_type",
							"dependency"            	=> array( 'element' => "timeline_title_custom", 'value' => 'true' ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "hidden_input",
							"param_name"        		=> "timeline_title_type",
							"value"             		=> "",
							"dependency"            	=> array( 'element' => "timeline_title_custom", 'value' => 'true' ),
							"group" 			        => "Additions",
						),		
						// Timeline Description
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_6",
							"seperator"					=> "Timeline Description",
							"group" 			        => "Additions",
						),						
						array(
							"type"              		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorBase64TinyMCE == "true" ? "wysiwyg_base64" : "textarea_raw_html"),
							"heading"           		=> __( "Description: Content", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_description",
							"value"             		=> base64_encode(""),
							"description"       		=> __( "Enter a description for the the overall timeline, shown at the beginning; HTML code can be used.", "ts_visual_composer_extend" ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Description: Show Header Title", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_title_show",
							"value"             		=> "true",
							"description"		    	=> __( "Switch the toggle if you want to show the header title with the description again, or only at the top of the timeline.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_title", 'not_empty' => true ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Description: Customize", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_description_custom",
							"value"             		=> "false",
							"description"		    	=> __( "Switch the toggle if you want to customize the description styling.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_description", 'not_empty' => true ),
							"group" 			        => "Additions",
						),
						/*array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Description: Alignment", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_description_align",
							"width"             		=> 200,
							"value"             		=> array(
								__( 'Center', "ts_visual_composer_extend" )      		=> "center",
								__( 'Left', "ts_visual_composer_extend" )         		=> "left",
								__( 'Right', "ts_visual_composer_extend" )       		=> "right",
								__( 'Justify', "ts_visual_composer_extend" )			=> "justify",
							),
							"description"       		=> __( "Select how the description text should be aligned.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_description_custom", 'value' => 'true' ),
							"group" 			        => "Additions",
						),*/				
						array(
							"type"						=> "image_selector",
							"heading"					=> __( "Description: Alignment", "ts_visual_composer_extend" ),
							"param_name"				=> "timeline_description_align",
							"template"					=> "alignfull",
							"value"						=> "center",
							"description"       		=> __( "Select how the description text should be aligned.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_description_custom", 'value' => 'true' ),
							"group" 			        => "Additions",
						),						
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Description: Font Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_description_color",
							"value"             		=> "#7c7979",
							"description"       		=> __( "Define the font color for the description text.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_description_custom", 'value' => 'true' ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Description: Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_description_back",
							"value"             		=> "#ededed",
							"description"       		=> __( "Define the background color for the description text.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_description_custom", 'value' => 'true' ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Description: Border Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_description_border",
							"value"             		=> "#c4c4c4",
							"description"       		=> __( "Define the border color for the description text.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_description_custom", 'value' => 'true' ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Description: Shadow Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_description_shadow",
							"value"             		=> "rgba(0, 0, 0, 0.2)",
							"description"       		=> __( "Define the shadow color for the description text.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_description_custom", 'value' => 'true' ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "fontsmanager",
							"heading"           		=> __( "Description: Font Family", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_description_family",
							"value"             		=> "",
							"default"					=> "true",
							"connector"					=> "timeline_description_type",
							"dependency"            	=> array( 'element' => "timeline_description_custom", 'value' => 'true' ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "hidden_input",
							"param_name"        		=> "timeline_description_type",
							"value"             		=> "",
							"dependency"            	=> array( 'element' => "timeline_description_custom", 'value' => 'true' ),
							"group" 			        => "Additions",
						),
						// Timeline Start Tag
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_7",
							"seperator"					=> "Timeline Start Tag",
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "Start: Tag", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_start",
							"value"             		=> "",
							"description"       		=> __( "Enter an optional start term for the Isotope Timeline.", "ts_visual_composer_extend" ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Start: Customize", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_start_custom",
							"value"             		=> "false",
							"description"		    	=> __( "Switch the toggle if you want to customize the start tag styling.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_start", 'not_empty' => true ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Start: Font Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_start_color",
							"value"             		=> "#ffffff",
							"description"       		=> __( "Define the font color for the start tag text.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_start_custom", 'value' => 'true' ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Start: Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_start_back",
							"value"             		=> "#7c7b7b",
							"description"       		=> __( "Define the background color for the start tag text.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_start_custom", 'value' => 'true' ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Start: Border Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_start_border",
							"value"             		=> "#737373",
							"description"       		=> __( "Define the border color for the start tag text.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_start_custom", 'value' => 'true' ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "fontsmanager",
							"heading"           		=> __( "Start: Font Family", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_start_family",
							"value"             		=> "",
							"default"					=> "true",
							"connector"					=> "timeline_start_type",
							"dependency"            	=> array( 'element' => "timeline_start_custom", 'value' => 'true' ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "hidden_input",
							"param_name"        		=> "timeline_start_type",
							"value"             		=> "",
							"dependency"            	=> array( 'element' => "timeline_start_custom", 'value' => 'true' ),
							"group" 			        => "Additions",
						),
						// Timeline End Tag
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_8",
							"seperator"					=> "Timeline End Tag",
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "End: Tag", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_end",
							"value"             		=> "",
							"description"       		=> __( "Enter an optional end term for the Isotope Timeline.", "ts_visual_composer_extend" ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "End: Customize", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_end_custom",
							"value"             		=> "false",
							"description"		    	=> __( "Switch the toggle if you want to customize the end tag styling.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "timeline_end", 'not_empty' => true ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "End: Font Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_end_color",
							"value"             		=> "#ffffff",
							"description"       		=> __( "Define the font color for the end tag text.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_end_custom", 'value' => 'true' ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "End: Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_end_back",
							"value"             		=> "#7c7b7b",
							"description"       		=> __( "Define the background color for the end tag text.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_end_custom", 'value' => 'true' ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "End: Border Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_end_border",
							"value"             		=> "#737373",
							"description"       		=> __( "Define the border color for the end tag text.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_end_custom", 'value' => 'true' ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "fontsmanager",
							"heading"           		=> __( "End: Font Family", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_end_family",
							"value"             		=> "",
							"default"					=> "true",
							"connector"					=> "timeline_end_type",
							"dependency"            	=> array( 'element' => "timeline_end_custom", 'value' => 'true' ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "hidden_input",
							"param_name"        		=> "timeline_end_type",
							"value"             		=> "",
							"dependency"            	=> array( 'element' => "timeline_end_custom", 'value' => 'true' ),
							"group" 			        => "Additions",
						),
						// Other Information
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_9",
							"seperator"					=> "Other Information",
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Show Categories for Sections", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_show_cats",
							"value"             		=> "true",
							"description"		    	=> __( "Switch the toggle if you want to show the categories assigned to each section below the section content.", "ts_visual_composer_extend" ),
							"group" 			        => "Additions",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Show Tags for Sections", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_show_tags",
							"value"             		=> "true",
							"description"		    	=> __( "Switch the toggle if you want to show the tags assigned to each section below the section content.", "ts_visual_composer_extend" ),
							"group" 			        => "Additions",
						),
						// Sort Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_10",
							"seperator"					=> "Sort Settings",
							"group" 			        => "Sorter",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Sort Buttons", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_sort",
							"value"             		=> "true",
							"admin_label"           	=> true,
							"description"		    	=> __( "Switch the toggle if you want to provide sort controls (up/down) for the timeline. Buttons will be hidden until all sections are visible, if lazyload effect has been used.", "ts_visual_composer_extend" ),
							"group" 			        => "Sorter",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "Label: Section Sorter", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_sort_label",
							"value"             		=> "Sort Timeline:",
							"description"       		=> __( "Enter the label text for the section sorter.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_sort", 'value' => 'true' ),
							"group" 			        => "Sorter",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "String: Ascending", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_sort_asc",
							"value"             		=> "Ascending",
							"description"       		=> __( "Enter the text string to be used inside the sorter to provide the option for an ascending direction.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_sort", 'value' => 'true' ),
							"group" 			        => "Sorter",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "String: Descending", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_sort_desc",
							"value"             		=> "Descending",
							"description"       		=> __( "Enter the text string to be used inside the sorter to provide the option for a descending direction.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_sort", 'value' => 'true' ),
							"group" 			        => "Sorter",
						),
						// Filter Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_11",
							"seperator"					=> "Filter Settings",
							"group" 			        => "Filter",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Filter Controls", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_filter_allow",
							"value"             		=> "false",
							"admin_label"           	=> true,
							"description"		    	=> __( "Switch the toggle if you want to provide filter options for the timeline, based on section categories and/or tags.", "ts_visual_composer_extend" ),
							"group" 			        => "Filter",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Allow Multiple", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_filter_multiple",
							"value"             		=> "true",
							"description"		    	=> __( "Switch the toggle if you want to allow the filter to be used with multiple options, or limited to just one filter option.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_filter_allow", 'value' => 'true' ),
							"group" 			        => "Filter",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Allow Deselect All", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_filter_deselect",
							"value"             		=> "false",
							"description"		    	=> __( "Switch the toggle if you want to allow to deselect all filter criteria, potentially resulting in an empty timeline.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_filter_multiple", 'value' => 'true' ),
							"group" 			        => "Filter",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Require Confirmation", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_filter_confirm",
							"value"             		=> "true",
							"description"		    	=> __( "Switch the toggle if you want to require a confirmation button for the selected filter options; better performance for a large number of filter options.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_filter_multiple", 'value' => 'true' ),
							"group" 			        => "Filter",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Provide Search Option", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_filter_search",
							"value"             		=> "true",
							"description"		    	=> __( "Switch the toggle if you want to provide a search option for the filter.", "ts_visual_composer_extend" ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_filter_allow", 'value' => 'true' ),
							"group" 			        => "Filter",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "String: Selected", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_filter_selected",
							"value"             		=> "Selected",
							"description"       		=> __( "Enter the text string to be used inside the filters to highlight how many items are currently selected.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_filter_allow", 'value' => 'true' ),
							"group" 			        => "Filter",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "String: Select All", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_filter_selectall",
							"value"             		=> "Select All",
							"description"       		=> __( "Enter the text string to be used inside the filters to provide an option to select all options at once.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_filter_allow", 'value' => 'true' ),
							"group" 			        => "Filter",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Categories Filter", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_filter_cats",
							"value"             		=> "true",
							"description"		    	=> __( "Switch the toggle if you want to provide a filter option for section categories.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_filter_allow", 'value' => 'true' ),
							"group" 			        => "Filter",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "Label: Categories Filter", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_filter_labelcats",
							"value"             		=> "Filter By Categories:",
							"description"       		=> __( "Enter the label text for the categories filter.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_filter_cats", 'value' => 'true' ),
							"group" 			        => "Filter",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "String: No Categories", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_filter_nocats",
							"value"             		=> "No Categories",
							"description"       		=> __( "Enter the text string to be used for sections without categories.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_filter_cats", 'value' => 'true' ),
							"group" 			        => "Filter",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Tags Filter", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_filter_tags",
							"value"             		=> "true",
							"description"		    	=> __( "Switch the toggle if you want to provide a filter option for section tags.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_filter_allow", 'value' => 'true' ),
							"group" 			        => "Filter",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "Label: Tags Filter", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_filter_labeltags",
							"value"             		=> "Filter By Tags:",
							"description"       		=> __( "Enter the label text for the tags filter.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_filter_tags", 'value' => 'true' ),
							"group" 			        => "Filter",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "String: No Tags", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_filter_notags",
							"value"             		=> "No Tags",
							"description"       		=> __( "Enter the text string to be used for sections without tags.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_filter_tags", 'value' => 'true' ),
							"group" 			        => "Filter",
						),
						// Timeline Break Sections
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_12",
							"seperator"					=> "Timeline Break Sections",
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Break Sections: Customize", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_custom_breaks",
							"value"             		=> "false",
							"description"		    	=> __( "Switch the toggle if you want to customize the break sections styling.", "ts_visual_composer_extend" ),
							"group" 			        => "Styling",
						),
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_12_1",
							"seperator"					=> "Break Sections: General",
							"uppercase"					=> "false",
							"bordertype"				=> "dashed",
							"fontsize"					=> 18,
							"dependency"            	=> array( 'element' => "timeline_custom_breaks", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Break Sections: Title Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_break_title",
							"value"             		=> "#676767",
							"dependency"            	=> array( 'element' => "timeline_custom_breaks", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "fontsmanager",
							"heading"           		=> __( "Break Sections: Title Font", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_break_titlefont",
							"value"             		=> "",
							"default"					=> "true",
							"connector"					=> "timeline_break_titletype",
							"dependency"            	=> array( 'element' => "timeline_custom_breaks", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "hidden_input",
							"param_name"        		=> "timeline_break_titletype",
							"value"             		=> "",
							"dependency"            	=> array( 'element' => "timeline_custom_breaks", 'value' => 'true' ),
							"group" 			        => "Styling",
						),		
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Break Sections: Text Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_break_text",
							"value"             		=> "#676767",
							"dependency"            	=> array( 'element' => "timeline_custom_breaks", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "fontsmanager",
							"heading"           		=> __( "Break Sections: Text Font", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_break_textfont",
							"value"             		=> "",
							"default"					=> "true",
							"connector"					=> "timeline_break_texttype",
							"dependency"            	=> array( 'element' => "timeline_custom_breaks", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "hidden_input",
							"param_name"        		=> "timeline_break_texttype",
							"value"             		=> "",
							"dependency"            	=> array( 'element' => "timeline_custom_breaks", 'value' => 'true' ),
							"group" 			        => "Styling",
						),		
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Break Sections: Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_break_back",
							"value"             		=> "#dadada",
							"dependency"            	=> array( 'element' => "timeline_custom_breaks", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_12_2",
							"seperator"					=> "Break Sections: Border",
							"uppercase"					=> "false",
							"bordertype"				=> "dashed",
							"fontsize"					=> 18,
							"dependency"            	=> array( 'element' => "timeline_custom_breaks", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type" 						=> "advanced_styling",
							"heading" 					=> __("Break Sections: Border", "ts_visual_composer_extend"),
							"param_name" 				=> "timeline_border_breaks",
							"style_type"				=> "border",
							"show_main"					=> "false",
							"show_preview"				=> "true",
							"show_width"				=> "true",
							"show_style"				=> "true",
							"show_radius" 				=> "true",					
							"show_color"				=> "true",
							"show_unit_width"			=> "true",
							"show_unit_radius"			=> "true",
							"override_all"				=> "true",
							"default_positions"			=> array(
								"All"							=> array("string" => __("All", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#dddddd", "radius" => "0", "unitradius" => "px"),
								"Top"							=> array("string" => __("Top", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#dddddd", "radius" => "0", "unitradius" => "px"),
								"Right"							=> array("string" => __("Right", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#dddddd", "radius" => "0", "unitradius" => "px"),
								"Bottom"						=> array("string" => __("Bottom", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#dddddd", "radius" => "0", "unitradius" => "px"),
								"Left"							=> array("string" => __("Left", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#dddddd", "radius" => "0", "unitradius" => "px"),
							),
							"description"       		=> __( "Define the border settings to be used for all break sections within the timeline.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_custom_breaks", 'value' => 'true' ),
							"group"						=> "Styling",
						),						
						// Timeline Event Sections
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_13",
							"seperator"					=> "Timeline Event Sections",
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Event Sections: Customize", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_custom_events",
							"value"             		=> "false",
							"description"		    	=> __( "Switch the toggle if you want to customize the event sections styling.", "ts_visual_composer_extend" ),
							"group" 			        => "Styling",
						),
						// General Style Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_13_1",
							"seperator"					=> "General",
							"uppercase"					=> "false",
							"bordertype"				=> "dashed",
							"fontsize"					=> 18,
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Event Sections: Title Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_singletitle",
							"value"             		=> "#676767",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Event Sections: Title Background", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_singletitleback",
							"value"             		=> "#ffffff",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),		
						array(
							"type"              		=> "fontsmanager",
							"heading"           		=> __( "Event Sections: Title Font", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_singletitlefont",
							"value"             		=> "",
							"default"					=> "true",
							"connector"					=> "timeline_event_singletitletype",
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "hidden_input",
							"param_name"        		=> "timeline_event_singletitletype",
							"value"             		=> "",
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Event Sections: Text Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_singletext",
							"value"             		=> "#676767",
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "fontsmanager",
							"heading"           		=> __( "Event Sections: Text Font", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_singletextfont",
							"value"             		=> "",
							"default"					=> "true",
							"connector"					=> "timeline_event_singletexttype",
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "hidden_input",
							"param_name"        		=> "timeline_event_singletexttype",
							"value"             		=> "",
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Event Sections: Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_singleback",
							"value"             		=> "#ffffff",
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Event Sections: Tags Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_singletags",
							"value"             		=> "#b8bcbe",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Event Sections: Categories Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_singlecats",
							"value"             		=> "#b8bcbe",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						// Dot Indicator Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_13_2",
							"seperator"					=> "Dot Indicators",
							"uppercase"					=> "false",
							"bordertype"				=> "dashed",
							"fontsize"					=> 18,
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Event Sections: Dot Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_dots_singlecolor",
							"value"             		=> "#bbbbbb",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Event Sections: Dot Background", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_dots_singleback",
							"value"             		=> "#ffffff",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Event Sections: Dot Shadow", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_dots_singleshadow",
							"value"             		=> "rgba(0, 0, 0, 0.2)",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Event Sections: Dot Connector", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_dots_singleline",
							"value"             		=> "#cccccc",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						// Timeline Date/Time Section
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_13_3",
							"seperator"					=> "Date/Time",
							"uppercase"					=> "false",
							"bordertype"				=> "dashed",
							"fontsize"					=> 18,
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Date/Time Info: Icon Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_date_singleicon",
							"value"             		=> "#7c7b7b",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Date/Time Info: Text Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_date_singletext",
							"value"             		=> "#7c7b7b",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Date/Time Info: Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_date_singleback",
							"value"             		=> "#f5f5f5",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),		
						// Border Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_13_4",
							"seperator"					=> "Border",
							"uppercase"					=> "false",
							"bordertype"				=> "dashed",
							"fontsize"					=> 18,
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type" 						=> "advanced_styling",
							"heading" 					=> __("Column Layout: Border", "ts_visual_composer_extend"),
							"param_name" 				=> "timeline_border_single",
							"style_type"				=> "border",
							"show_main"					=> "false",
							"show_preview"				=> "true",
							"show_width"				=> "true",
							"show_style"				=> "true",
							"show_radius" 				=> "true",					
							"show_color"				=> "true",
							"show_unit_width"			=> "true",
							"show_unit_radius"			=> "true",
							"override_all"				=> "true",
							"default_positions"			=> array(
								"All"							=> array("string" => __("All", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#dddddd", "radius" => "0", "unitradius" => "px"),
								"Top"							=> array("string" => __("Top", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#dddddd", "radius" => "0", "unitradius" => "px"),
								"Right"							=> array("string" => __("Right", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#dddddd", "radius" => "0", "unitradius" => "px"),
								"Bottom"						=> array("string" => __("Bottom", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#dddddd", "radius" => "0", "unitradius" => "px"),
								"Left"							=> array("string" => __("Left", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#dddddd", "radius" => "0", "unitradius" => "px"),
							),
							"description"       		=> __( "Define the border settings to be used for content sections in the single column layout, and for full-width sections.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group"						=> "Styling",
						),
						// Dual Columns Layout
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_13_4",
							"seperator"					=> "Dual Columns",
							"uppercase"					=> "false",
							"bordertype"				=> "dashed",
							"fontsize"					=> 18,
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group" 			        => "Styling",
						),
						array(
							"type"              		=> "messenger",
							"param_name"        		=> "messenger_1",
							"size"						=> "13",
							"layout"					=> "notice",
							"message"            		=> __( "By default, the styling you defined above will be used for event sections in all single and dual column layout options. But you can define a different styling for the left and/or right column in the dual column layout.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => "true" ),
							"group"						=> "Styling",
						),						
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Dual Columns Styling: Source", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_custom_columns",
							"width"             		=> 300,
							"value"             		=> array(
								__( "Use Single Column Styling", "ts_visual_composer_extend" )			=> "main",
								__( "Customize Left Column", "ts_visual_composer_extend" )				=> "left",
								__( "Customize Right Column", "ts_visual_composer_extend" )				=> "right",
								__( "Customize Both Columns", "ts_visual_composer_extend" )				=> "both",
							),
							"description"       		=> __( "Select if you want to assign different styles to event sections when using the dual columns layout.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_custom_events", 'value' => 'true' ),
							"group"						=> "Styling",
						),
						// Left Column Styling
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_15",
							"seperator"					=> "Left Column Sections",
							"dependency"            	=> array( 'element' => "timeline_custom_columns", 'value' => array('left', 'both') ),
							"group"						=> "Left Column",
						),
						array(
							"type"              		=> "messenger",
							"param_name"        		=> "messenger_2",
							"color"						=> "#006BB7",
							"size"						=> "14",
							"transform"					=> "none",
							"message"            		=> __( "By default, the events within the left column will use the same styling as you defined it for the single column layout, unless you adjust it below.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_custom_columns", 'value' => array('right', 'both') ),
							"group"						=> "Left Column",
						),
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_15_1",
							"seperator"					=> "General",
							"uppercase"					=> "false",
							"bordertype"				=> "dashed",
							"fontsize"					=> 18,
							"dependency"            	=> array( 'element' => "timeline_custom_columns", 'value' => array('left', 'both') ),
							"group"						=> "Left Column",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Left Column: General", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_leftglobal",
							"width"             		=> 300,
							"value"             		=> array(
								__( "Use Global General Styling", "ts_visual_composer_extend" )			=> "true",
								__( "Customize General Styling", "ts_visual_composer_extend" )			=> "false",
							),
							"dependency"            	=> array( 'element' => "timeline_custom_columns", 'value' => array('left', 'both') ),
							"group"						=> "Left Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Left Column: Title Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_lefttitle",
							"value"             		=> "#676767",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_event_leftglobal", 'value' => "false" ),
							"group"						=> "Left Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Left Column: Title Background", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_lefttitleback",
							"value"             		=> "#ffffff",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_event_leftglobal", 'value' => 'false' ),
							"group" 			        => "Left Column",
						),
						array(
							"type"              		=> "fontsmanager",
							"heading"           		=> __( "Left Column: Title Font", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_lefttitlefont",
							"value"             		=> "",
							"default"					=> "true",
							"connector"					=> "timeline_event_lefttitletype",
							"dependency"            	=> array( 'element' => "timeline_event_leftglobal", 'value' => "false" ),
							"group"						=> "Left Column",
						),
						array(
							"type"              		=> "hidden_input",
							"param_name"        		=> "timeline_event_lefttitletype",
							"value"             		=> "",
							"dependency"            	=> array( 'element' => "timeline_event_leftglobal", 'value' => "false" ),
							"group"						=> "Left Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Left Column: Text Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_lefttext",
							"value"             		=> "#676767",
							"dependency"            	=> array( 'element' => "timeline_event_leftglobal", 'value' => "false" ),
							"group"						=> "Left Column",
						),
						array(
							"type"              		=> "fontsmanager",
							"heading"           		=> __( "Left Column: Text Font", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_lefttextfont",
							"value"             		=> "",
							"default"					=> "true",
							"connector"					=> "timeline_event_lefttexttype",
							"dependency"            	=> array( 'element' => "timeline_event_leftglobal", 'value' => "false" ),
							"group"						=> "Left Column",
						),
						array(
							"type"              		=> "hidden_input",
							"param_name"        		=> "timeline_event_lefttexttype",
							"value"             		=> "",
							"dependency"            	=> array( 'element' => "timeline_event_leftglobal", 'value' => "false" ),
							"group"						=> "Left Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Left Column: Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_leftback",
							"value"             		=> "#ffffff",
							"dependency"            	=> array( 'element' => "timeline_event_leftglobal", 'value' => "false" ),
							"group"						=> "Left Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Left Column: Tags Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_lefttags",
							"value"             		=> "#b8bcbe",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_event_leftglobal", 'value' => "false" ),
							"group"						=> "Left Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Left Column: Categories Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_leftcats",
							"value"             		=> "#b8bcbe",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_event_leftglobal", 'value' => "false" ),
							"group"						=> "Left Column",
						),
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_15_2",
							"seperator"					=> "Dot Indicators",
							"uppercase"					=> "false",
							"bordertype"				=> "dashed",
							"fontsize"					=> 18,
							"dependency"            	=> array( 'element' => "timeline_custom_columns", 'value' => array('left', 'both') ),
							"group"						=> "Left Column",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Left Column: Indicator Dots", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_dots_leftglobal",
							"width"             		=> 300,
							"value"             		=> array(
								__( "Use Global Dots Styling", "ts_visual_composer_extend" )			=> "true",
								__( "Customize Indicator Dots", "ts_visual_composer_extend" )			=> "false",
							),
							"dependency"            	=> array( 'element' => "timeline_custom_columns", 'value' => array('left', 'both') ),
							"group"						=> "Left Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Left Column: Dot Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_dots_leftcolor",
							"value"             		=> "#bbbbbb",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_dots_leftglobal", 'value' => "false" ),
							"group"						=> "Left Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Left Column: Dot Background", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_dots_leftback",
							"value"             		=> "#ffffff",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_dots_leftglobal", 'value' => "false" ),
							"group"						=> "Left Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Left Column: Dot Shadow", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_dots_leftshadow",
							"value"             		=> "rgba(0, 0, 0, 0.2)",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_dots_leftglobal", 'value' => "false" ),
							"group"						=> "Left Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Left Column: Dot Connector", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_dots_leftline",
							"value"             		=> "#cccccc",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_dots_leftglobal", 'value' => "false" ),
							"group"						=> "Left Column",
						),
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_15_3",
							"seperator"					=> "Date/Time",
							"uppercase"					=> "false",
							"bordertype"				=> "dashed",
							"fontsize"					=> 18,
							"dependency"            	=> array( 'element' => "timeline_custom_columns", 'value' => array('left', 'both') ),
							"group"						=> "Left Column",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Left Column: Date/Time", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_date_leftglobal",
							"width"             		=> 300,
							"value"             		=> array(
								__( "Use Global Date/Time Styling", "ts_visual_composer_extend" )			=> "true",
								__( "Customize Date/Time Styling", "ts_visual_composer_extend" )			=> "false",
							),
							"dependency"            	=> array( 'element' => "timeline_custom_columns", 'value' => array('left', 'both') ),
							"group"						=> "Left Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Left Column: Date/Time Icon Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_date_lefticon",
							"value"             		=> "#7c7b7b",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_date_leftglobal", 'value' => 'false' ),
							"group" 			        => "Left Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Left Column: Date/Time Text Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_date_lefttext",
							"value"             		=> "#7c7b7b",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_date_leftglobal", 'value' => 'false' ),
							"group" 			        => "Left Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Left Column: Date/Time Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_date_leftback",
							"value"             		=> "#f5f5f5",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_date_leftglobal", 'value' => 'false' ),
							"group" 			        => "Left Column",
						),
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_15_4",
							"seperator"					=> "Border",
							"uppercase"					=> "false",
							"bordertype"				=> "dashed",
							"fontsize"					=> 18,
							"dependency"            	=> array( 'element' => "timeline_custom_columns", 'value' => array('left', 'both') ),
							"group"						=> "Left Column",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Left Column: Border", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_border_leftglobal",
							"width"             		=> 300,
							"value"             		=> array(
								__( "Use Global Border Styling", "ts_visual_composer_extend" )			=> "true",
								__( "Customize Border Styling", "ts_visual_composer_extend" )			=> "false",
							),
							"dependency"            	=> array( 'element' => "timeline_custom_columns", 'value' => array('left', 'both') ),
							"group"						=> "Left Column",
						),						
						array(
							"type" 						=> "advanced_styling",
							"heading" 					=> __("Left Column: Border", "ts_visual_composer_extend"),
							"param_name" 				=> "timeline_border_leftcustom",
							"style_type"				=> "border",
							"show_main"					=> "false",
							"show_preview"				=> "true",
							"show_width"				=> "true",
							"show_style"				=> "true",
							"show_radius" 				=> "true",					
							"show_color"				=> "true",
							"show_unit_width"			=> "true",
							"show_unit_radius"			=> "true",
							"override_all"				=> "true",
							"default_positions"			=> array(
								"All"							=> array("string" => __("All", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#dddddd", "radius" => "0", "unitradius" => "px"),
								"Top"							=> array("string" => __("Top", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#dddddd", "radius" => "0", "unitradius" => "px"),
								"Right"							=> array("string" => __("Right", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#dddddd", "radius" => "0", "unitradius" => "px"),
								"Bottom"						=> array("string" => __("Bottom", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#dddddd", "radius" => "0", "unitradius" => "px"),
								"Left"							=> array("string" => __("Left", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#dddddd", "radius" => "0", "unitradius" => "px"),
							),
							"description"       		=> __( "Define the border settings for the timeline content sections in the left column.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_border_leftglobal", 'value' => "false" ),
							"group"						=> "Left Column",
						),
						// Right Column Styling
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_16",
							"seperator"					=> "Right Column Sections",
							"dependency"            	=> array( 'element' => "timeline_custom_columns", 'value' => array('right', 'both') ),
							"group"						=> "Right Column",
						),
						array(
							"type"              		=> "messenger",
							"param_name"        		=> "messenger_3",
							"color"						=> "#006BB7",
							"size"						=> "14",
							"transform"					=> "none",
							"message"            		=> __( "By default, the events within the right column will use the same styling as you defined it for the single column layout, unless you adjust it below.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_custom_columns", 'value' => array('right', 'both') ),
							"group"						=> "Right Column",
						),
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_16_1",
							"seperator"					=> "General",
							"uppercase"					=> "false",
							"bordertype"				=> "dashed",
							"fontsize"					=> 18,
							"dependency"            	=> array( 'element' => "timeline_custom_columns", 'value' => array('right', 'both') ),
							"group"						=> "Right Column",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Right Column: General", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_rightglobal",
							"width"             		=> 300,
							"value"             		=> array(
								__( "Use Global General Styling", "ts_visual_composer_extend" )			=> "true",
								__( "Customize General Styling", "ts_visual_composer_extend" )			=> "false",
							),
							"dependency"            	=> array( 'element' => "timeline_custom_columns", 'value' => array('right', 'both') ),
							"group"						=> "Right Column",
						),						
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Right Column: Title Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_righttitle",
							"value"             		=> "#676767",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_event_rightglobal", 'value' => "false" ),
							"group"						=> "Right Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Right Column: Title Background", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_righttitleback",
							"value"             		=> "#ffffff",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_event_rightglobal", 'value' => 'false' ),
							"group" 			        => "Right Column",
						),
						array(
							"type"              		=> "fontsmanager",
							"heading"           		=> __( "Right Column: Title Font", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_righttitlefont",
							"value"             		=> "",
							"default"					=> "true",
							"connector"					=> "timeline_event_righttitletype",
							"dependency"            	=> array( 'element' => "timeline_event_rightglobal", 'value' => "false" ),
							"group"						=> "Right Column",
						),
						array(
							"type"              		=> "hidden_input",
							"param_name"        		=> "timeline_event_righttitletype",
							"value"             		=> "",
							"dependency"            	=> array( 'element' => "timeline_event_rightglobal", 'value' => "false" ),
							"group"						=> "Right Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Right Column: Text Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_righttext",
							"value"             		=> "#676767",
							"dependency"            	=> array( 'element' => "timeline_event_rightglobal", 'value' => "false" ),
							"group"						=> "Right Column",
						),
						array(
							"type"              		=> "fontsmanager",
							"heading"           		=> __( "Right Column: Text Font", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_righttextfont",
							"value"             		=> "",
							"default"					=> "true",
							"connector"					=> "timeline_event_righttexttype",
							"dependency"            	=> array( 'element' => "timeline_event_rightglobal", 'value' => "false" ),
							"group"						=> "Right Column",
						),
						array(
							"type"              		=> "hidden_input",
							"param_name"        		=> "timeline_event_righttexttype",
							"value"             		=> "",
							"dependency"            	=> array( 'element' => "timeline_event_rightglobal", 'value' => "false" ),
							"group"						=> "Right Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Right Column: Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_rightback",
							"value"             		=> "#ffffff",
							"dependency"            	=> array( 'element' => "timeline_event_rightglobal", 'value' => "false" ),
							"group"						=> "Right Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Right Column: Tags Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_righttags",
							"value"             		=> "#b8bcbe",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_event_rightglobal", 'value' => "false" ),
							"group"						=> "Right Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Right Column: Categories Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_event_rightcats",
							"value"             		=> "#b8bcbe",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_event_rightglobal", 'value' => "false" ),
							"group"						=> "Right Column",
						),						
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_16_2",
							"seperator"					=> "Dot Indicators",
							"uppercase"					=> "false",
							"bordertype"				=> "dashed",
							"fontsize"					=> 18,
							"dependency"            	=> array( 'element' => "timeline_custom_columns", 'value' => array('right', 'both') ),
							"group"						=> "Right Column",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Right Column: Indicator Dots", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_dots_rightglobal",
							"width"             		=> 300,
							"value"             		=> array(
								__( "Use Global Dots Styling", "ts_visual_composer_extend" )			=> "true",
								__( "Customize Indicator Dots", "ts_visual_composer_extend" )			=> "false",
							),
							"dependency"            	=> array( 'element' => "timeline_custom_columns", 'value' => array('right', 'both') ),
							"group"						=> "Right Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Right Column: Dot Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_dots_rightcolor",
							"value"             		=> "#bbbbbb",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_dots_rightglobal", 'value' => 'false' ),
							"group"						=> "Right Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Right Column: Dot Background", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_dots_rightback",
							"value"             		=> "#ffffff",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_dots_rightglobal", 'value' => 'false' ),
							"group"						=> "Right Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Right Column: Dot Shadow", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_dots_rightshadow",
							"value"             		=> "rgba(0, 0, 0, 0.2)",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_dots_rightglobal", 'value' => 'false' ),
							"group"						=> "Right Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Right Column: Dot Connector", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_dots_rightline",
							"value"             		=> "#cccccc",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_dots_rightglobal", 'value' => 'false' ),
							"group"						=> "Right Column",
						),						
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_16_3",
							"seperator"					=> "Date/Time",
							"uppercase"					=> "false",
							"bordertype"				=> "dashed",
							"fontsize"					=> 18,
							"dependency"            	=> array( 'element' => "timeline_custom_columns", 'value' => array('right', 'both') ),
							"group"						=> "Right Column",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Right Column: Date/Time", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_date_rightglobal",
							"width"             		=> 300,
							"value"             		=> array(
								__( "Use Global Date/Time Styling", "ts_visual_composer_extend" )			=> "true",
								__( "Customize Date/Time Styling", "ts_visual_composer_extend" )			=> "false",
							),
							"dependency"            	=> array( 'element' => "timeline_custom_columns", 'value' => array('right', 'both') ),
							"group"						=> "Right Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Right Column: Date/Time Icon Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_date_righticon",
							"value"             		=> "#7c7b7b",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_date_rightglobal", 'value' => 'false' ),
							"group" 			        => "Right Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Right Column: Date/Time Text Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_date_righttext",
							"value"             		=> "#7c7b7b",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_date_rightglobal", 'value' => 'false' ),
							"group" 			        => "Right Column",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Right Column: Date/Time Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_date_rightback",
							"value"             		=> "#f5f5f5",
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"dependency"            	=> array( 'element' => "timeline_date_rightglobal", 'value' => 'false' ),
							"group" 			        => "Right Column",
						),
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_16_4",
							"seperator"					=> "Border",
							"uppercase"					=> "false",
							"bordertype"				=> "dashed",
							"fontsize"					=> 18,
							"dependency"            	=> array( 'element' => "timeline_custom_columns", 'value' => array('right', 'both') ),
							"group"						=> "Right Column",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Right Column: Border", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_border_rightglobal",
							"width"             		=> 300,
							"value"             		=> array(
								__( "Use Global Border Styling", "ts_visual_composer_extend" )			=> "true",
								__( "Customize Border Styling", "ts_visual_composer_extend" )			=> "false",
							),
							"dependency"            	=> array( 'element' => "timeline_custom_columns", 'value' => array('right', 'both') ),
							"group"						=> "Right Column",
						),			
						array(
							"type" 						=> "advanced_styling",
							"heading" 					=> __("Right Column: Border", "ts_visual_composer_extend"),
							"param_name" 				=> "timeline_border_rightcustom",
							"style_type"				=> "border",
							"show_main"					=> "false",
							"show_preview"				=> "true",
							"show_width"				=> "true",
							"show_style"				=> "true",
							"show_radius" 				=> "true",					
							"show_color"				=> "true",
							"show_unit_width"			=> "true",
							"show_unit_radius"			=> "true",
							"override_all"				=> "true",
							"default_positions"			=> array(
								"All"							=> array("string" => __("All", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#dddddd", "radius" => "0", "unitradius" => "px"),
								"Top"							=> array("string" => __("Top", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#dddddd", "radius" => "0", "unitradius" => "px"),
								"Right"							=> array("string" => __("Right", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#dddddd", "radius" => "0", "unitradius" => "px"),
								"Bottom"						=> array("string" => __("Bottom", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#dddddd", "radius" => "0", "unitradius" => "px"),
								"Left"							=> array("string" => __("Left", "ts_visual_composer_extend"),	"width" => "1", "unitwidth" => "px", "style" => "solid", "color" => "#dddddd", "radius" => "0", "unitradius" => "px"),
							),
							"description"       		=> __( "Define the border settings for the timeline content sections in the right column.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_border_rightglobal", 'value' => "false" ),
							"group"						=> "Right Column",
						),
						// Other Settings
						array(
							"type"                      => "seperator",
							"param_name"                => "seperator_17",
							"seperator"					=> "Other Settings",
							"group" 			        => "Other",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Margin: Top", "ts_visual_composer_extend" ),
							"param_name"                => "margin_top",
							"value"                     => "0",
							"min"                       => "0",
							"max"                       => "200",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Select the top margin for the element.", "ts_visual_composer_extend" ),
							"group" 			        => "Other",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Margin: Bottom", "ts_visual_composer_extend" ),
							"param_name"                => "margin_bottom",
							"value"                     => "0",
							"min"                       => "0",
							"max"                       => "200",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Select the bottom margin for the element.", "ts_visual_composer_extend" ),
							"group" 			        => "Other",
						),
						array(
							"type"                      => "textfield",
							"heading"                   => __( "Define ID Name", "ts_visual_composer_extend" ),
							"param_name"                => "el_id",
							"value"                     => "",
							"description"               => __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
							"group" 			        => "Other",
						),
						array(
							"type"                  	=> "tag_editor",
							"heading"           		=> __( "Extra Class Names", "ts_visual_composer_extend" ),
							"param_name"            	=> "el_class",
							"value"                 	=> "",
							"description"      		 	=> __( "Enter additional class names for the element.", "ts_visual_composer_extend" ),
							"group" 					=> "Other",
						),
					)						
				);
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
					return $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element;
				} else {			
					vc_map($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element);
				};
			}
			function TS_VCSC_Add_Timeline_CSS_Element_Section() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Post Section Element
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					"name"                      		=> __( "TS Timeline Post", "ts_visual_composer_extend" ),
					"base"                      		=> "TS_VCSC_Timeline_CSS_Section",
					"icon" 	                    		=> "ts-composer-element-icon-timeline-post",
					"category"                  		=> __( 'VC Extensions', "ts_visual_composer_extend" ),
					"description"               		=> __("Place a timeline section from a post", "ts_visual_composer_extend"),
					"content_element"					=> true,
					"as_child"							=> array('only' => 'TS_VCSC_Timeline_CSS_Container'),
					"admin_enqueue_js"					=> "",
					"admin_enqueue_css"					=> "",
					"params"                    		=> array(
						// Timeline Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_1",
							"seperator"					=> "Timeline Section",
						),
						array(
							"type"						=> "custompost",
							"heading"					=> __( "Timeline Section", "ts_visual_composer_extend" ),
							"param_name"				=> "section",
							"posttype"					=> "ts_timeline",
							"posttaxonomy"				=> "ts_timeline_category",
							"taxonomy"					=> "ts_timeline_category",
							"postsingle"				=> "Timeline Section",
							"postplural"				=> "Timeline Sections",
							"postclass"					=> "timeline",
							"value"						=> ""
						),
						array(
							"type"						=> "hidden_input",
							"heading"					=> __( "Section Title", "ts_visual_composer_extend" ),
							"param_name"				=> "custompost_name",
							"value"						=> "",
							"admin_label"				=> true
						),
						array(
							"type" 						=> "icons_panel",
							'heading' 					=> __( 'Section Icon', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'section_icon',
							'value'						=> '',
							"settings" 					=> array(
								"emptyIcon" 					=> true,
								"emptyIconValue"				=> 'transparent',
								"type" 							=> 'extensions',
							),
							"description"       		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorVisualSelector == "true" ? __( "Select the icon to be shown with the section content.", "ts_visual_composer_extend" ) : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconSelectorString),
							"dependency"        		=> array( 'element' => "section", 'not_empty' => true )
						),						
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Icon Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "icon_color",
							"value"             		=> "#7c7979",
							"description"       		=> __( "Define the icon color to be used in the timeline item.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "section", 'not_empty' => true )
						),
						// Other Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_2",
							"seperator"					=> "Other Settings",
							"group" 					=> "Other Settings",
						),
						array(
							"type"						=> "nouislider",
							"heading"					=> __( "Tooltip X-Offset", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltipster_offsetx",
							"value"						=> "0",
							"min"						=> "-100",
							"max"						=> "100",
							"step"						=> "1",
							"unit"						=> 'px',
							"description"				=> __( "Define an optional X-Offset for any tooltips used in this timeline section.", "ts_visual_composer_extend" ),
							"group" 					=> "Other Settings",
						),
						array(
							"type"						=> "nouislider",
							"heading"					=> __( "Tooltip Y-Offset", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltipster_offsety",
							"value"						=> "0",
							"min"						=> "-100",
							"max"						=> "100",
							"step"						=> "1",
							"unit"						=> 'px',
							"description"				=> __( "Define an optional Y-Offset for any tooltips used in this timeline section.", "ts_visual_composer_extend" ),
							"group" 					=> "Other Settings",
						),	
						array(
							"type"              		=> "textfield",
							"heading"          	 		=> __( "Define ID Name", "ts_visual_composer_extend" ),
							"param_name"        		=> "el_id",
							"value"             		=> "",
							"description"       		=> __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
							"group" 					=> "Other Settings",
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
			function TS_VCSC_Add_Timeline_CSS_Element_Break() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Break Section Element
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					"name"                      		=> __( "TS Timeline Break", "ts_visual_composer_extend" ),
					"base"                      		=> "TS_VCSC_Timeline_CSS_Break",
					"icon" 	                    		=> "ts-composer-element-icon-timeline-break",
					"category"                  		=> __( 'VC Extensions', "ts_visual_composer_extend" ),
					"description"               		=> __("Place a timeline break section", "ts_visual_composer_extend"),
					"content_element"					=> true,
					"as_child"							=> array('only' => 'TS_VCSC_Timeline_CSS_Container'),
					"admin_enqueue_js"					=> "",
					"admin_enqueue_css"					=> "",
					"params"                    		=> array(
						// Break Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_1",
							"seperator"					=> "Break Section",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Break: Full Width", "ts_visual_composer_extend" ),
							"param_name"		    	=> "break_fullwidth",
							"value"             		=> "false",
							"description"		    	=> __( "Switch the toggle if you want to display this break section in full width of the timeline.", "ts_visual_composer_extend" ),
						),			
						array(
							"type"              		=> "textfield",
							"heading"          	 		=> __( "Break: Title", "ts_visual_composer_extend" ),
							"param_name"        		=> "break_title",
							"value"             		=> "",
							"admin_label"           	=> true,
						),
						/*array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Break: Title Alignment", "ts_visual_composer_extend" ),
							"param_name"        		=> "break_titlealign",
							"width"             		=> 300,
							"value"             		=> array(
								__( 'Left', "ts_visual_composer_extend" )			=> "left",
								__( 'Right', "ts_visual_composer_extend" )			=> "right",			 
								__( 'Center', "ts_visual_composer_extend" )			=> "center",
								__( 'Justify', "ts_visual_composer_extend" )		=> "justify",
							),
							"std"						=> "center",
							"standard"					=> "center",
							"default"					=> "center",
						),*/
						array(
							"type"						=> "image_selector",
							"heading"					=> __( "Break: Title Alignment", "ts_visual_composer_extend" ),
							"param_name"				=> "break_titlealign",
							"template"					=> "alignfull",
							"value"						=> "center",
							"dependency"        		=> array( 'element' => "break_title", 'not_empty' => true ),
						),
						array(
							"type"						=> "textarea_html",
							"heading"					=> __( "Break: Content", "ts_visual_composer_extend" ),
							"param_name"				=> "content",
							"value"						=> "",
						),
						array(
							"type" 						=> "icons_panel",
							'heading' 					=> __( 'Break: Icon', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'break_icon',
							'value'						=> '',
							"settings" 					=> array(
								"emptyIcon" 					=> true,
								"emptyIconValue"				=> 'transparent',
								"type" 							=> 'extensions',
							),
							"description"       		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorVisualSelector == "true" ? __( "Select the icon to be shown with the section content.", "ts_visual_composer_extend" ) : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconSelectorString),
						),						
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Break: Icon Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "break_iconcolor",
							"value"             		=> "#7c7979",
						),
						// Categories / Tags
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_2",
							"seperator"					=> "Break Categories",
							"group" 					=> "Cats / Tags",
						),						
						array(
							"type"                      => "custompostcat",
							"heading"                   => __( "Break: Existing Categories", "ts_visual_composer_extend" ),
							"param_name"                => "break_catsold",
							"posttype"                  => "ts_timeline",
							"posttaxonomy"              => "ts_timeline_category",
							"taxonomy"              	=> "ts_timeline_category",
							"postsingle"				=> "Section",
							"postplural"				=> "Sections",
							"postclass"					=> "cats",
							"postslugs"					=> "false",
							"postsource"				=> "cats",
							"postempty"					=> "false",
							"postactive"				=> "true",
							"value"                     => "",
							"description"               => __( "Please select the timeline categories you want to use for this section.", "ts_visual_composer_extend" ),
							"group" 					=> "Cats / Tags",
						),
						array(
							"type"                  	=> "tag_editor",
							"heading"               	=> __( "Break: New Categories", "ts_visual_composer_extend" ),
							"param_name"            	=> "break_catsnew",
							"value"                 	=> "",
							"delimiter"					=> ",",
							"lowercase"					=> "false",
							"description"           	=> __( "Enter any additional (new) individual categories you want to assign to this section; press enter after each category (NO commas allowed).", "ts_visual_composer_extend" ),
							"group" 					=> "Cats / Tags",
						),						
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_3",
							"seperator"					=> "Break Tags",
							"group" 					=> "Cats / Tags",
						),				
						array(
							"type"                      => "custompostcat",
							"heading"                   => __( "Break: Existing Tags", "ts_visual_composer_extend" ),
							"param_name"                => "break_tagsold",
							"posttype"                  => "ts_timeline",
							"posttaxonomy"              => "ts_timeline_tags",
							"taxonomy"              	=> "ts_timeline_tags",
							"postsingle"				=> "Section",
							"postplural"				=> "Sections",
							"postclass"					=> "tags",
							"postslugs"					=> "false",
							"postsource"				=> "tags",
							"postempty"					=> "false",
							"postactive"				=> "true",
							"value"                     => "",
							"description"               => __( "Please select the timeline tags you want to use for this section.", "ts_visual_composer_extend" ),
							"group" 					=> "Cats / Tags",
						),
						array(
							"type"                  	=> "tag_editor",
							"heading"               	=> __( "Break: New Tags", "ts_visual_composer_extend" ),
							"param_name"            	=> "break_tagsnew",
							"value"                 	=> "",
							"delimiter"					=> ",",
							"description"           	=> __( "Enter any additional (new) individual tags you want to assign to this section; press enter after each tag (NO commas allowed).", "ts_visual_composer_extend" ),
							"group" 					=> "Cats / Tags",
						),
						// Customize Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_4",
							"seperator"					=> "Break Customization",
							"group" 					=> "Styling",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Break: Customize", "ts_visual_composer_extend" ),
							"param_name"		    	=> "break_customize",
							"value"             		=> "false",
							"description"		    	=> __( "Switch the toggle if you want to customize some styling for this section.", "ts_visual_composer_extend" ),
							"group" 					=> "Styling",
						),		
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Break: Title Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "break_colortitle",
							"value"             		=> "#676767",
							"dependency"            	=> array( 'element' => "break_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Break: Content Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "break_colorcontent",
							"value"             		=> "#676767",
							"dependency"            	=> array( 'element' => "break_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Break: Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "break_colorback",
							"value"             		=> "#dadada",
							"dependency"            	=> array( 'element' => "break_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Styling",
						),
						// Other Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_5",
							"seperator"					=> "Other Settings",
							"group" 					=> "Other Settings",
						),
						array(
							"type"              		=> "textfield",
							"heading"          	 		=> __( "Define ID Name", "ts_visual_composer_extend" ),
							"param_name"        		=> "el_id",
							"value"             		=> "",
							"description"       		=> __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
							"group" 					=> "Other Settings",
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
			function TS_VCSC_Add_Timeline_CSS_Element_Event() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Event Section Element
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					"name"                      		=> __( "TS Timeline Event", "ts_visual_composer_extend" ),
					"base"                      		=> "TS_VCSC_Timeline_CSS_Event",
					"icon" 	                    		=> "ts-composer-element-icon-timeline-event",
					"category"                  		=> __( 'VC Extensions', "ts_visual_composer_extend" ),
					"description"               		=> __("Place a timeline event section", "ts_visual_composer_extend"),
					"content_element"					=> true,
					"as_child"							=> array('only' => 'TS_VCSC_Timeline_CSS_Container'),
					"admin_enqueue_js"					=> "",
					"admin_enqueue_css"					=> "",
					"params"                    		=> array(
						// Event Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_1",
							"seperator"					=> "Event Full Width",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Event: Full Width", "ts_visual_composer_extend" ),
							"param_name"		    	=> "event_fullwidth",
							"value"             		=> "false",
							"description"		    	=> __( "Switch the toggle if you want to display this event section in full width of the timeline.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_2",
							"seperator"					=> "Event Date/Time",
						),
						array(
							"type"              		=> "textfield",
							"heading"          	 		=> __( "Event: Date/Time", "ts_visual_composer_extend" ),
							"param_name"        		=> "event_date",
							"value"             		=> "",
						),
						array(
							"type" 						=> "icons_panel",
							"heading" 					=> __( 'Event: Date/Time Icon', 'ts_visual_composer_extend' ),
							"param_name" 				=> 'event_dateicon',
							"value"						=> "",
							"settings" 					=> array(
								"emptyIcon" 					=> true,
								"emptyIconValue"				=> 'transparent',
								"hasSearch"						=> false,
								"override"						=> true,
								"type" 							=> 'timeline',
							),
							"dependency"        		=> array( 'element' => "event_date", 'not_empty' => true ),
							"description"       		=> __( "Select which icon should be used next to the date/time string.", "ts_visual_composer_extend" ),
						),						
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_3",
							"seperator"					=> "Event Title",
						),
						array(
							"type"              		=> "textfield",
							"heading"          	 		=> __( "Event: Title", "ts_visual_composer_extend" ),
							"param_name"        		=> "event_title",
							"value"             		=> "",
							"admin_label"           	=> true,
						),
						/*array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Event: Title Alignment", "ts_visual_composer_extend" ),
							"param_name"        		=> "event_titlealign",
							"width"             		=> 300,
							"value"             		=> array(
								__( 'Left', "ts_visual_composer_extend" )			=> "left",
								__( 'Right', "ts_visual_composer_extend" )			=> "right",			 
								__( 'Center', "ts_visual_composer_extend" )			=> "center",
								__( 'Justify', "ts_visual_composer_extend" )		=> "justify",
							),
							"std"						=> "center",
							"standard"					=> "center",
							"default"					=> "center",
						),*/
						array(
							"type"						=> "image_selector",
							"heading"					=> __( "Event: Title Alignment", "ts_visual_composer_extend" ),
							"param_name"				=> "event_titlealign",
							"template"					=> "alignfull",
							"value"						=> "center",
							"dependency"        		=> array( 'element' => "event_title", 'not_empty' => true ),
						),
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_4",
							"seperator"					=> "Event Content",
						),
						array(
							"type"						=> "textarea_html",
							"heading"					=> __( "Event: Content", "ts_visual_composer_extend" ),
							"param_name"				=> "content",
							"value"						=> "",
						),
						array(
							"type" 						=> "icons_panel",
							'heading' 					=> __( 'Event: Content Icon', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'event_icon',
							'value'						=> '',
							"settings" 					=> array(
								"emptyIcon" 					=> true,
								"emptyIconValue"				=> 'transparent',
								"type" 							=> 'extensions',
							),
							"description"       		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorVisualSelector == "true" ? __( "Select the icon to be shown with the section content.", "ts_visual_composer_extend" ) : $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconSelectorString),
						),						
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Event: Content Icon Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "event_iconcolor",
							"value"             		=> "#7c7979",
						),
						// Featured Media
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_5",
							"seperator"					=> "Featured Media",
							"group" 					=> "Media",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Media: Featured Media", "ts_visual_composer_extend" ),
							"param_name"        		=> "media_type",
							"width"             		=> 300,
							"value"             		=> array(
								__( "None", "ts_visual_composer_extend" ) 											=> "none",
								__( "Single Image", "ts_visual_composer_extend" ) 									=> "image",
								__( "Image Slider", "ts_visual_composer_extend" ) 									=> "slider",								
								__( "YouTube Video", "ts_visual_composer_extend" )	 								=> "youtube",
								__( "DailyMotion Video", "ts_visual_composer_extend" )	 							=> "dailymotion",
								__( "Vimeo Video", "ts_visual_composer_extend" )	 								=> "vimeo",
							),
							"description"               => __( "Please select the featured media type for this timeline item.", "ts_visual_composer_extend" ),
							"group" 					=> "Media",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Media: YouTube Link/ID", "ts_visual_composer_extend" ),
							"param_name"            	=> "media_videoyoutube",
							"value"                 	=> "",
							"description"           	=> __( "Enter the link or ID for the YouTube video to be used as featured media element.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "media_type", 'value' => 'youtube' ),
							"group" 					=> "Media",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Media: Vimeo Link/ID", "ts_visual_composer_extend" ),
							"param_name"            	=> "media_videovimeo",
							"value"                 	=> "",
							"description"           	=> __( "Enter the link or ID for the Vimeo video to be used as featured media element.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "media_type", 'value' => 'vimeo' ),
							"group" 					=> "Media",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Media: DailyMotion Link/ID", "ts_visual_composer_extend" ),
							"param_name"            	=> "media_videomotion",
							"value"                 	=> "",
							"description"           	=> __( "Enter the link or ID for the DailyMotion video to be used as featured media element.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "media_type", 'value' => 'dailymotion' ),
							"group" 					=> "Media",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Media: Open in Lightbox", "ts_visual_composer_extend" ),
							"param_name"		    	=> "media_lightboximage",
							"value"             		=> "false",
							"description"		    	=> __( "Switch the toggle if you want to display the featured media within a lightbox.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "media_type", 'value' => array('image','slider') ),
							"group" 					=> "Media",
						),
						array(
							"type"                  	=> "attach_image",
							"heading"               	=> __( "Media: Image", "ts_visual_composer_extend" ),
							"param_name"            	=> "media_image",
							"value"                	 	=> "",
							"description"           	=> __( "Select the image to be used for the featured media element.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "media_type", 'value' => 'image' ),
							"group" 					=> "Media",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Media: Open in Lightbox", "ts_visual_composer_extend" ),
							"param_name"		    	=> "media_lightboxvideo",
							"value"             		=> "false",
							"description"		    	=> __( "Switch the toggle if you want to display the featured media within a lightbox.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "media_type", 'value' => array('youtube','dailymotion','vimeo') ),
							"group" 					=> "Media",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Media: Custom Cover", "ts_visual_composer_extend" ),
							"param_name"		    	=> "media_videocustom",
							"value"             		=> "false",
							"description"		    	=> __( "Switch the toggle if you want to use a custom image as video cover; otherwise the cover image will be retrieved from the video source.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "media_lightboxvideo", 'value' => "true" ),
							"group" 					=> "Media",
						),
						array(
							"type"                  	=> "attach_image",
							"heading"               	=> __( "Media: Cover", "ts_visual_composer_extend" ),
							"param_name"            	=> "media_videocover",
							"value"                	 	=> "",
							"description"           	=> __( "Select the image to be used as cover image for the featured media element.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "media_videocustom", 'value' => "true" ),
							"group" 					=> "Media",
						),						
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Media: TITLE Attribute(s)", "ts_visual_composer_extend" ),
							"param_name"            	=> "media_singletitle",
							"value"                 	=> "",
							"description"           	=> __( "Enter a TITLE attribute string for the featured media element.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "media_type", 'value' => array('image','youtube','dailymotion','vimeo') ),
							"group" 					=> "Media",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Media: ALT Attribute(s)", "ts_visual_composer_extend" ),
							"param_name"            	=> "media_singlealt",
							"value"                 	=> "",
							"description"           	=> __( "Enter an ALT attriubute string for the featured media element.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "media_type", 'value' => array('image','youtube','dailymotion','vimeo') ),
							"group" 					=> "Media",
						),
						array(
							"type"                  	=> "attach_images",
							"heading"               	=> __( "Media: Slider Images", "ts_visual_composer_extend" ),
							"param_name"            	=> "media_slider",
							"value"                 	=> "",
							"description"           	=> __( "Select the images to be used for the featured media slider.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "media_type", 'value' => 'slider' ),
							"group" 					=> "Media",
						),
						array(
							"type"                  	=> "exploded_textarea",
							"heading"               	=> __( "Media: TITLE Attribute(s)", "ts_visual_composer_extend" ),
							"param_name"            	=> "media_grouptitle",
							"value"                 	=> "",
							"description"           	=> __( "Enter TITLE attribute strings for the featured media images within the slider.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "media_type", 'value' => 'slider' ),
							"group" 					=> "Media",
						),
						array(
							"type"                  	=> "exploded_textarea",
							"heading"               	=> __( "Media: ALT Attribute(s)", "ts_visual_composer_extend" ),
							"param_name"            	=> "media_groupalt",
							"value"                 	=> "",
							"description"           	=> __( "Enter ALT attribute strings for the featured media images within the slider.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "media_type", 'value' => 'slider' ),
							"group" 					=> "Media",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Media: Show Related", "ts_visual_composer_extend" ),
							"param_name"		    	=> "media_videorelated",
							"value"             		=> "true",
							"description"		    	=> __( "Switch the toggle if you want to show related videos after the initial video ended.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "media_type", 'value' => 'youtube' ),
							"group" 					=> "Media",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Media: Auto-Play", "ts_visual_composer_extend" ),
							"param_name"		    	=> "media_videoauto",
							"value"             		=> "true",
							"description"		    	=> __( "Switch the toggle if you want to auto-play the video once opened in the lightbox or on pageload (iFrame).", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "media_type", 'value' => array('youtube','vimeo','dailymotion') ),
							"group" 					=> "Media",
						),
						array(
							"type"                  	=> "nouislider",
							"heading"               	=> __( "Media: Maximum Height", "ts_visual_composer_extend" ),
							"param_name"            	=> "media_heightmax",
							"value"                 	=> "400",
							"min"                   	=> "100",
							"max"                   	=> "800",
							"step"                  	=> "1",
							"unit"                  	=> 'px',
							"description"           	=> __( "Define the maximum height of the images in the slider in pixels; helpful to prevent unnecessary position adjustments of timeline sections due to various image size ratios.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "media_type", 'value' => 'slider' ),
							"group" 					=> "Media",
						),						
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Media: Height", "ts_visual_composer_extend" ),
							"param_name"        		=> "media_height",
							"width"             		=> 300,
							"value"             		=> array(
								__( '100% Height Setting', "ts_visual_composer_extend" )			=> "height: 100%;",
								__( 'Auto Height Setting', "ts_visual_composer_extend" )			=> "height: auto;",
							),
							"description"           	=> __( "Select what height setting should be applied to the media element (change only if image height does not display correctly).", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "media_type", 'value' => array('image','youtube','vimeo','dailymotion') ),
							"group" 					=> "Media",
						),						
						array(
							"type"                  	=> "nouislider",
							"heading"               	=> __( "Media: Width", "ts_visual_composer_extend" ),
							"param_name"            	=> "media_width",
							"value"                 	=> "100",
							"min"                   	=> "50",
							"max"                   	=> "100",
							"step"                  	=> "1",
							"unit"                  	=> '%',
							"description"           	=> __( "Define the width of the featured media element in relation to the section width.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "media_type", 'value' => array('image','youtube','vimeo','dailymotion') ),
							"group" 					=> "Media",
						),
						/*array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Media: Alignment", "ts_visual_composer_extend" ),
							"param_name"        		=> "media_align",
							"width"             		=> 300,
							"value"             		=> array(
								__( 'Center', "ts_visual_composer_extend" )			=> "center",
								__( 'Left', "ts_visual_composer_extend" )			=> "left",
								__( 'Right', "ts_visual_composer_extend" )			=> "right",
							),
							"description"           	=> __( "If not full width (100%), select how the media element should be aligned.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "media_type", 'value' => array('image','youtube','vimeo','dailymotion') ),
							"group" 					=> "Media",
						),*/
						array(
							"type"						=> "image_selector",
							"heading"					=> __( "Media: Alignment", "ts_visual_composer_extend" ),
							"param_name"				=> "media_align",
							"template"					=> "alignbasic",
							"value"						=> "center",
							"description"           	=> __( "If not full width (100%), select how the media element should be aligned.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "media_type", 'value' => array('image','youtube','vimeo','dailymotion') ),
							"group" 					=> "Media",
						),						
						// Lightbox Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_6",
							"seperator"					=> "Lightbox Settings",
							"group" 					=> "Lightbox",
						),	
						array(
							"type"              		=> "messenger",
							"param_name"        		=> "messenger1",
							"size"						=> "13",
							"message"            		=> __( "The following settings apply only if the featured media is set to be opened within the lightbox.", "ts_visual_composer_extend" ),
							"layout"					=> "notice",
							"group" 					=> __( "Lightbox", "ts_visual_composer_extend"),
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Lightbox: Create Auto-Group", "ts_visual_composer_extend" ),
							"param_name"		    	=> "lightbox_groupauto",
							"value"             		=> "true",
							"description"		    	=> __( "Switch the toggle if you want to create an autogroup with all other featured media elements within timelines on this page.", "ts_visual_composer_extend" ),
							"group" 					=> "Lightbox",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Lightbox: Group Name", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_groupname",
							"value"                 	=> "",
							"description"           	=> __( "Enter a custom group name for this featured media element within the lightbox leave empty for non-grouping.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "lightbox_groupauto", 'value' => 'false' ),
							"group" 					=> "Lightbox"
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Lightbox: Transition Effect", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_transition",
							"width"                 	=> 150,
							"value"                 	=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Animations,
							"default" 					=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LightboxDefaultAnimation,
							"std" 						=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LightboxDefaultAnimation,
							"description"           	=> __( "Select the transition effect to be used for this featured media element in the lightbox.", "ts_visual_composer_extend" ),
							"group" 					=> "Lightbox",
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Lightbox: Backlight Effect", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_backlight",
							"width"                 	=> 150,
							"value"                 	=> array(
								__( 'Auto Color', "ts_visual_composer_extend" )					=> "auto",
								__( 'Custom Color', "ts_visual_composer_extend" )				=> "custom",
								__( 'Transparent Backlight', "ts_visual_composer_extend" )		=> "hideit",
								__( 'Fully Remove Backlight', "ts_visual_composer_extend" )		=> "remove",
							),
							"description"           	=> __( "Select the backlight effect for this featured media element in the lightbox.", "ts_visual_composer_extend" ),
							"group" 					=> "Lightbox",
						),
						array(
							"type"                  	=> "colorpicker",
							"heading"               	=> __( "Lightbox: Custom Backlight Color", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_custom",
							"value"                	 	=> "#ffffff",
							"description"           	=> __( "Define the backlight color for this featured media element in the lightbox.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "lightbox_backlight", 'value' => 'custom' ),
							"group" 					=> "Lightbox",
						),
						// Link Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_7",
							"seperator"					=> "Link Settings",
							"group" 					=> "Link",
						),	
						array(
							"type" 						=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterLinkPicker['enabled'] == "false" ? "vc_link" : "advancedlinks"),
							"heading" 					=> __("Link: Button Data", "ts_visual_composer_extend"),
							"param_name" 				=> "link_data",
							"description" 				=> __("Provide a link and link information to another site/page, to be used with this timeline section.", "ts_visual_composer_extend"),
							"group" 					=> "Link",
						),
						array(
							"type" 						=> "icons_panel",
							"heading" 					=> __( 'Link: Button Icon', 'ts_visual_composer_extend' ),
							"param_name" 				=> 'link_icon',
							"value"						=> "",
							"settings" 					=> array(
								"emptyIcon" 					=> true,
								"emptyIconValue"				=> 'transparent',
								"hasSearch"						=> false,
								"override"						=> true,
								"type" 							=> 'timeline',
							),
							"dependency"        		=> array( 'element' => "event_date", 'not_empty' => true ),
							"description"       		=> __( "Select which icon should be used next to the link button text string.", "ts_visual_composer_extend" ),
							"group" 					=> "Link",
						),						
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Link: Button Text", "ts_visual_composer_extend" ),
							"param_name"            	=> "link_label",
							"value"                 	=> "Read More",
							"description"           	=> __( "Enter the text string to be used for the link button.", "ts_visual_composer_extend" ),
							"group" 					=> "Link"
						),
						array(
							"type"                  	=> "nouislider",
							"heading"               	=> __( "Link: Width", "ts_visual_composer_extend" ),
							"param_name"            	=> "link_width",
							"value"                 	=> "100",
							"min"                   	=> "50",
							"max"                   	=> "100",
							"step"                  	=> "1",
							"unit"                  	=> '%',
							"description"           	=> __( "Define the width of the link button in relation to the section width.", "ts_visual_composer_extend" ),
							"group" 					=> "Link",
						),
						/*array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Link: Alignment", "ts_visual_composer_extend" ),
							"param_name"        		=> "link_align",
							"width"             		=> 300,
							"value"             		=> array(
								__( 'Center', "ts_visual_composer_extend" )			=> "center",
								__( 'Left', "ts_visual_composer_extend" )			=> "left",
								__( 'Right', "ts_visual_composer_extend" )			=> "right",
							),
							"description"           	=> __( "If not full width (100%), select how the link button should be aligned.", "ts_visual_composer_extend" ),
							"group" 					=> "Link",
						),*/
						array(
							"type"						=> "image_selector",
							"heading"					=> __( "Link: Alignment", "ts_visual_composer_extend" ),
							"param_name"				=> "link_align",
							"template"					=> "alignbasic",
							"value"						=> "center",
							"description"           	=> __( "If not full width (100%), select how the link button should be aligned.", "ts_visual_composer_extend" ),
							"group" 					=> "Link",
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Link: General Style", "ts_visual_composer_extend" ),
							"param_name"            	=> "link_style1",
							"width"                 	=> 300,
							//"value"                 	=> array_merge($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Flat_Button_Default_Colors, $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Flat_Button_Default_Custom),
							"value"                 	=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Flat_Button_Default_Colors,
							"description"           	=> __( "Select the general style for the link button.", "ts_visual_composer_extend" ),
							"group" 					=> "Link",
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Link: Hover Style", "ts_visual_composer_extend" ),
							"param_name"            	=> "link_style2",
							"width"                 	=> 300,
							//"value"                 	=> array_merge($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Flat_Button_Hover_Colors, $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Flat_Button_Hover_Custom),
							"value"                 	=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Flat_Button_Hover_Colors,
							"description"           	=> __( "Select the hover style for the link button.", "ts_visual_composer_extend" ),
							"group" 					=> "Link",
						),	
						// Categories / Tags
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_8",
							"seperator"					=> "Event Categories",
							"group" 					=> "Cats / Tags",
						),						
						array(
							"type"                      => "custompostcat",
							"heading"                   => __( "Event: Existing Categories", "ts_visual_composer_extend" ),
							"param_name"                => "event_catsold",
							"posttype"                  => "ts_timeline",
							"posttaxonomy"              => "ts_timeline_category",
							"taxonomy"              	=> "ts_timeline_category",
							"postsingle"				=> "Section",
							"postplural"				=> "Sections",
							"postclass"					=> "cats",
							"postslugs"					=> "false",
							"postsource"				=> "cats",
							"postempty"					=> "false",
							"postactive"				=> "true",
							"value"                     => "",
							"description"               => __( "Please select the timeline categories you want to use for this section.", "ts_visual_composer_extend" ),
							"group" 					=> "Cats / Tags",
						),
						array(
							"type"                  	=> "tag_editor",
							"heading"               	=> __( "Event: New Categories", "ts_visual_composer_extend" ),
							"param_name"            	=> "event_catsnew",
							"value"                 	=> "",
							"delimiter"					=> ",",
							"lowercase"					=> "false",
							"description"           	=> __( "Enter any additional (new) individual categories you want to assign to this section; press enter after each category (NO commas allowed).", "ts_visual_composer_extend" ),
							"group" 					=> "Cats / Tags",
						),	
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_9",
							"seperator"					=> "Event Tags",
							"group" 					=> "Cats / Tags",
						),				
						array(
							"type"                      => "custompostcat",
							"heading"                   => __( "Event: Existing Tags", "ts_visual_composer_extend" ),
							"param_name"                => "event_tagsold",
							"posttype"                  => "ts_timeline",
							"posttaxonomy"              => "ts_timeline_tags",
							"taxonomy"              	=> "ts_timeline_tags",
							"postsingle"				=> "Section",
							"postplural"				=> "Sections",
							"postclass"					=> "tags",
							"postslugs"					=> "false",
							"postsource"				=> "tags",
							"postempty"					=> "false",
							"postactive"				=> "true",
							"value"                     => "",
							"description"               => __( "Please select the timeline tags you want to use for this section.", "ts_visual_composer_extend" ),
							"group" 					=> "Cats / Tags",
						),
						array(
							"type"                  	=> "tag_editor",
							"heading"               	=> __( "Event: New Tags", "ts_visual_composer_extend" ),
							"param_name"            	=> "event_tagsnew",
							"value"                 	=> "",
							"delimiter"					=> ",",
							"description"           	=> __( "Enter any additional (new) individual tags you want to assign to this section; press enter after each tag (NO commas allowed).", "ts_visual_composer_extend" ),
							"group" 					=> "Cats / Tags",
						),	
						// Customize Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_10",
							"seperator"					=> "Event Customization",
							"group" 					=> "Styling",
						),
						array(
							"type"              		=> "switch_button",
							"heading"			    	=> __( "Event: Customize", "ts_visual_composer_extend" ),
							"param_name"		    	=> "event_customize",
							"value"             		=> "false",
							"description"		    	=> __( "Switch the toggle if you want to customize some styling for this section.", "ts_visual_composer_extend" ),
							"group" 					=> "Styling",
						),		
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Event: Title Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "event_titlecolor",
							"value"             		=> "#676767",
							"dependency"            	=> array( 'element' => "event_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Event: Title Background", "ts_visual_composer_extend" ),
							"param_name"        		=> "event_titleback",
							"value"             		=> "#ffffff",
							"dependency"            	=> array( 'element' => "event_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Event: Content Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "event_contentcolor",
							"value"             		=> "#676767",
							"dependency"            	=> array( 'element' => "event_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Event: Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "event_contentback",
							"value"             		=> "#dadada",
							"dependency"            	=> array( 'element' => "event_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Date/Time: Font Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "event_datecolor",
							"value"             		=> "#777678",
							"dependency"            	=> array( 'element' => "event_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Date/Time: Icon Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "event_dateiconcolor",
							"value"             		=> "#777678",
							"dependency"            	=> array( 'element' => "event_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Styling",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Date/Time: Background Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "event_dateback",
							"value"             		=> "#f5f5f5",
							"dependency"            	=> array( 'element' => "event_customize", 'value' => 'true' ),
							"edit_field_class"			=> "vc_col-sm-6 vc_column",
							"group" 					=> "Styling",
						),						
						// Tooltip Settings
						array(
							"type"				   	 	=> "seperator",
							"param_name"		    	=> "seperator_10",
							"seperator"					=> "Tooltip Settings",
							"group" 					=> "Tooltip",
						),
						array(
							"type"              		=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorBase64TinyMCE == "true" ? "wysiwyg_base64" : "textarea_raw_html"),
							"heading"           		=> __( "Tooltip Content", "ts_visual_composer_extend" ),
							"param_name"        		=> "tooltip_content",
							"minimal"					=> "true",
							"value"             		=> base64_encode(""),
							"description"      	 		=> __( "Enter the tooltip content here; HTML code can be used.", "ts_visual_composer_extend" ),
							"group" 					=> "Tooltip",
						),
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Tooltip Position", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltip_position",
							"value"						=> array(
								__( "Top", "ts_visual_composer_extend" )                            => "top",
								__( "Bottom", "ts_visual_composer_extend" )                         => "bottom",
							),
							"description"				=> __( "Select the tooltip position in relation to the image.", "ts_visual_composer_extend" ),
							"group" 					=> "Tooltip",
						),
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Tooltip Style", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltip_style",
							"value"             		=> array(
								__( "Black", "ts_visual_composer_extend" )                          => "tooltipster-black",
								__( "Gray", "ts_visual_composer_extend" )                           => "tooltipster-gray",
								__( "Green", "ts_visual_composer_extend" )                          => "tooltipster-green",
								__( "Blue", "ts_visual_composer_extend" )                           => "tooltipster-blue",
								__( "Red", "ts_visual_composer_extend" )                            => "tooltipster-red",
								__( "Orange", "ts_visual_composer_extend" )                         => "tooltipster-orange",
								__( "Yellow", "ts_visual_composer_extend" )                         => "tooltipster-yellow",
								__( "Purple", "ts_visual_composer_extend" )                         => "tooltipster-purple",
								__( "Pink", "ts_visual_composer_extend" )                           => "tooltipster-pink",
								__( "White", "ts_visual_composer_extend" )                          => "tooltipster-white"
							),
							"description"				=> __( "Select the tooltip style.", "ts_visual_composer_extend" ),
							"group" 					=> "Tooltip",
						),
						array(
							"type"				    	=> "dropdown",
							"heading"			    	=> __( "Tooltip Animation", "ts_visual_composer_extend" ),
							"param_name"		    	=> "tooltip_animation",
							"value"                 	=> array(
								__("Swing", "ts_visual_composer_extend")                    => "swing",
								__("Fall", "ts_visual_composer_extend")                 	=> "fall",
								__("Grow", "ts_visual_composer_extend")                 	=> "grow",
								__("Slide", "ts_visual_composer_extend")                 	=> "slide",
								__("Fade", "ts_visual_composer_extend")                 	=> "fade",
							),
							"description"		    	=> __( "Select how the tooltip entry and exit should be animated once triggered.", "ts_visual_composer_extend" ),
							"group"						=> "Tooltip",
						),
						array(
							"type"						=> "nouislider",
							"heading"					=> __( "Tooltip X-Offset", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltipster_offsetx",
							"value"						=> "0",
							"min"						=> "-100",
							"max"						=> "100",
							"step"						=> "1",
							"unit"						=> 'px',
							"description"				=> __( "Define an optional X-Offset for the tooltip position.", "ts_visual_composer_extend" ),
							"group" 					=> "Tooltip",
						),
						array(
							"type"						=> "nouislider",
							"heading"					=> __( "Tooltip Y-Offset", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltipster_offsety",
							"value"						=> "0",
							"min"						=> "-100",
							"max"						=> "100",
							"step"						=> "1",
							"unit"						=> 'px',
							"description"				=> __( "Define an optional Y-Offset for the tooltip position.", "ts_visual_composer_extend" ),
							"group" 					=> "Tooltip",
						),						
						// Other Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_12",
							"seperator"					=> "Other Settings",
							"group" 					=> "Other",
						),
						array(
							"type"              		=> "textfield",
							"heading"          	 		=> __( "Define ID Name", "ts_visual_composer_extend" ),
							"param_name"        		=> "el_id",
							"value"             		=> "",
							"description"       		=> __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
							"group" 					=> "Other",
						),
						array(
							"type"                  	=> "tag_editor",
							"heading"           		=> __( "Extra Class Names", "ts_visual_composer_extend" ),
							"param_name"            	=> "el_class",
							"value"                 	=> "",
							"description"      		 	=> __( "Enter additional class names for the element.", "ts_visual_composer_extend" ),
							"group" 					=> "Other",
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
	if ((class_exists('WPBakeryShortCodesContainer')) && (!class_exists('WPBakeryShortCode_TS_VCSC_Timeline_CSS_Container'))) {
		class WPBakeryShortCode_TS_VCSC_Timeline_CSS_Container extends WPBakeryShortCodesContainer {};
	}
	if ((class_exists('WPBakeryShortCode')) && (!class_exists('WPBakeryShortCode_TS_VCSC_Timeline_CSS_Section'))) {
		class WPBakeryShortCode_TS_VCSC_Timeline_CSS_Section extends WPBakeryShortCode {};
	}
	// Initialize "TS CSS Media Timeline" Class
	if (class_exists('TS_Timeline_CSS')) {
		$TS_Timeline_CSS = new TS_Timeline_CSS;
	}
?>