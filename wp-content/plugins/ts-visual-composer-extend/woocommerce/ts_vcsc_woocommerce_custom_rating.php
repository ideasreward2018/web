<?php
	if (!class_exists('TS_WooCommerce_Rating_Basic')){
		class TS_WooCommerce_Rating_Basic {
			function __construct() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						$this->TS_VCSC_WooCommerce_Rating_Basic_Lean();
					} else if (function_exists('vc_map')) {
						add_action('init',                                  array($this, 'TS_VCSC_WooCommerce_Rating_Basic_Elements'), 9999999);
					}
				} else {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						add_action('admin_init',							array($this, 'TS_VCSC_WooCommerce_Rating_Basic_Lean'), 9999999);
					} else if (function_exists('vc_map')) {
						add_action('admin_init',							array($this, 'TS_VCSC_WooCommerce_Rating_Basic_Elements'), 9999999);
					}
				}
				if ((is_admin() == false) || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAJAX == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAlways == "true")) {
					add_shortcode('TS_VCSC_WooCommerce_Rating_Basic',		array($this, 'TS_VCSC_WooCommerce_Rating_Basic_Function'));
				}
			}
			
			// Register Element(s) via LeanMap
			function TS_VCSC_WooCommerce_Rating_Basic_Lean() {
				vc_lean_map('TS_VCSC_WooCommerce_Rating_Basic', 			array($this, 'TS_VCSC_WooCommerce_Rating_Basic_Elements'), null);
			}
			
			// Recent Products Slider
			function TS_VCSC_WooCommerce_Rating_Basic_Function ($atts) {
				global $VISUAL_COMPOSER_EXTENSIONS;
				global $product;
				global $woocommerce;
				ob_start();
	
				wp_enqueue_style('ts-font-ecommerce');
				wp_enqueue_style('ts-extend-ratingscale');
				wp_enqueue_style('ts-visual-composer-extend-front');
				wp_enqueue_script('ts-visual-composer-extend-front');
			
				extract( shortcode_atts( array(
					'best_rated'					=> 'false',
					'id' 							=> '',	
					'rating_maximum'				=> 5,
					'rating_size'					=> 24,
					'rating_quarter'				=> 'true',
					'rating_title'					=> 'true',
					'rating_auto'					=> 'true',
					'rating_position'				=> 'top',
					'rating_rtl'					=> 'false',
					'rating_symbol'					=> 'other',
					'rating_icon'					=> 'ts-ecommerce-starfull1',
					'color_rated'					=> '#FFD800',
					'color_empty'					=> '#e3e3e3',
					// Rating Settings
					'caption_show'					=> 'true',
					'caption_position'				=> 'left',
					'caption_digits'				=> '.',
					'caption_danger'				=> '#d9534f',
					'caption_warning'				=> '#f0ad4e',
					'caption_info'					=> '#5bc0de',
					'caption_primary'				=> '#428bca',
					'caption_success'				=> '#5cb85c',
					// Title Additions
					'title_size'					=> 24,
					'title_truncate'				=> 'true',
					'use_name'						=> 'true',
					'custom_title'					=> '',
					'show_cart'						=> 'true',
					'cart_color'					=> '#cccccc',
					'show_link'						=> 'true',
					'link_color'					=> '#cccccc',
					// Tooltip Settings
					'tooltip_css'					=> 'false',
					'tooltip_content'				=> '',
					'tooltip_position'				=> 'ts-simptip-position-top',
					'tooltip_style'					=> 'ts-simptip-style-black',
					'tooltip_arrow'					=> 'true',
					'tooltip_background'			=> '#000000',
					'tooltip_border'				=> '#000000',
					'tooltip_color'					=> '#ffffff',
					'tooltip_animation'				=> 'swing',
					'tooltipster_offsetx'			=> 0,
					'tooltipster_offsety'			=> 0,
					// Other Settings
					'margin_top'					=> 20,
					'margin_bottom'					=> 20,
					'el_id'							=> '',
					'el_class'						=> '',
					'css'							=> '',
				), $atts ));
	
				// Final Query Arguments
				$meta_query 						= array();
				$args = array(
					'p'								=> $id,
					'post_type'						=> 'product',
					'post_status'		  			=> 'publish',
					'ignore_sticky_posts'  			=> 1,
					'posts_per_page' 	   			=> -1,
					'orderby' 			  			=> 'date',
					'order' 						=> 'desc',
					'paged' 						=> 1,
					'meta_query' 		   			=> $meta_query
				);
				$loop 								= new WP_Query($args);
				if ($loop->have_posts()) {
					$best_rating					= 0;
					while ($loop->have_posts()) : $loop->the_post();
						$product_id 				= get_the_ID();
						$product 					= new WC_Product($product_id);
						if ($product_id == $id) {
							$product_title 			= get_the_title($product_id);
							$post 					= get_post($product_id);
							$product 				= new WC_Product($product_id);
							$attachment_ids 		= $product->get_gallery_image_ids();
							$price 					= $product->get_price_html();
							$product_sku			= $product->get_sku();
							$attributes 			= $product->get_attributes();
							$stock 					= $product->is_in_stock() ? 'true' : 'false';
							$onsale 				= $product->is_on_sale() ? 'true' : 'false';
							$link					= get_permalink();
							// Rating Settings
							$rating					= $product->get_average_rating();
							if (function_exists('wc_get_rating_html')) {
								$rating_html		= wc_get_rating_html($rating);
							} else {
								$rating_html		= $product->get_rating_html();
							}
							if ($rating == '') {
								$rating				= 0;
							}
							if ($rating_quarter == "true") {
								$rating_value		= floor($rating * 4) / 4;
							} else {
								$rating_value		= $rating;
							}
							$rating_value			= number_format($rating_value, 2, $caption_digits, '');
							$variables				= wc_get_product($product_id);									
							$variable				= $variables->is_type('variable') ? 'true' : 'false';									
							unset($variables);
							break;
						}
					endwhile;
				}		
				wp_reset_postdata();
				wp_reset_query();
	
				if ($rating_title == "true") {
					if ($use_name == "true") {
						$rating_title 				= $product_title;
					} else {
						$rating_title 				= $custom_title;
					}
				} else {
					$rating_title					= '';
				}
				if ($rating_rtl == "false") {
					$rating_width					= $rating_value / $rating_maximum * 100;
				} else {
					$rating_width					= 100 - ($rating_value / $rating_maximum * 100);
				}
				
				if ($rating_symbol == "other") {
					if ($rating_icon == "ts-ecommerce-starfull1") {
						$rating_class			= 'ts-rating-stars-star1';
					} else if ($rating_icon == "ts-ecommerce-starfull2") {
						$rating_class			= 'ts-rating-stars-star2';
					} else if ($rating_icon == "ts-ecommerce-starfull3") {
						$rating_class			= 'ts-rating-stars-star3';
					} else if ($rating_icon == "ts-ecommerce-starfull4") {
						$rating_class			= 'ts-rating-stars-star4';
					} else if ($rating_icon == "ts-ecommerce-heartfull") {
						$rating_class			= 'ts-rating-stars-heart1';
					} else if ($rating_icon == "ts-ecommerce-heart") {
						$rating_class			= 'ts-rating-stars-heart2';
					} else if ($rating_icon == "ts-ecommerce-thumbsup") {
						$rating_class			= 'ts-rating-stars-thumb';
					} else if ($rating_icon == "ts-ecommerce-ribbon4") {
						$rating_class			= 'ts-rating-stars-ribbon';
					} else {
						$rating_class			= 'ts-rating-stars-star1';
					}
				} else {
					$rating_class				= 'ts-rating-stars-smile';
				}
		
				if (($rating_value >= 0) && ($rating_value <= 1)) {
					$caption_class				= 'ts-label-danger';
					$caption_background			= 'background-color: ' . $caption_danger . ';';
				} else if (($rating_value > 1) && ($rating_value <= 2)) {
					$caption_class				= 'ts-label-warning';
					$caption_background			= 'background-color: ' . $caption_warning . ';';
				} else if (($rating_value > 2) && ($rating_value <= 3)) {
					$caption_class				= 'ts-label-info';
					$caption_background			= 'background-color: ' . $caption_info . ';';
				} else if (($rating_value > 3) && ($rating_value <= 4)) {
					$caption_class				= 'ts-label-primary';
					$caption_background			= 'background-color: ' . $caption_primary . ';';
				} else if (($rating_value > 4) && ($rating_value <= 5)) {
					$caption_class				= 'ts-label-success';
					$caption_background			= 'background-color: ' . $caption_success . ';';
				}
				
				// Line Height Adjustment
				if (($show_cart == "true") || ($show_link == "true")) {
					if ($title_size > 24) {
						$line_height				= $title_size;
					} else {
						$line_height				= 24;
					}
				} else {
					$line_height					= $title_size;
				}
				// Tooltip
				if ($tooltip_css == "true") {
					$tooltip_position			= TS_VCSC_TooltipMigratePosition($tooltip_position);
					$tooltip_style				= TS_VCSC_TooltipMigrateStyle($tooltip_style);
					if (strip_tags($tooltip_content) != '') {
						wp_enqueue_style('ts-extend-tooltipster');
						wp_enqueue_script('ts-extend-tooltipster');	
						$rating_tooltipcontent	= 'data-tooltipster-html="false" data-tooltipster-title="" data-tooltipster-text="' . strip_tags($tooltip_content) . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="' . $tooltip_arrow . '" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="' . $tooltip_animation . '" data-tooltipster-trigger="hover" data-tooltipster-background="' . $tooltip_background . '" data-tooltipster-border="' . $tooltip_border . '" data-tooltipster-color="' . $tooltip_color . '" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . $tooltipster_offsety . '"';
						$rating_tooltipclasses	= 'ts-has-tooltipster-tooltip';
					} else {
						$rating_tooltipcontent	= '';
						$rating_tooltipclasses	= '';
					}
				} else {
					$rating_tooltipclasses		= "";
					if (strlen($tooltip_content) != 0) {
						$rating_tooltipcontent	= ' title="' . $tooltip_content . '"';
					} else {
						$rating_tooltipcontent	= "";
					}
				}
	
				// Visual Composer Override
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 					= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_WooCommerce_Rating_Basic', $atts);
				} else {
					$css_class					= '';
				}
				
				$output = '';
				$output .= '<div class="ts-rating-stars-frame ' . $el_class . ' ' . $css_class . '" data-auto="' . $rating_auto . '" data-size="' . $rating_size . '" data-width="' . ($rating_size * 5) . '" data-rating="' . $rating_value . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					if (($rating_position == 'top') && ($rating_title != '')) {
						$output .= '<div class="ts-rating-title ts-rating-title-top ' . ($title_truncate == "true" ? " ts-truncated" : "") . '" style="font-size: ' . $title_size . 'px; line-height: ' . $line_height . 'px; vertical-align: middle;">';
							if ($show_cart == "true") {
								if ($variable == "true") {
									$output .= '<a style="position: inherit; margin-right: 10px;" class="ts-woocommerce-product-purchase" href="' . $link . '" rel="nofollow" data-id="' . $product_id . '" data-sku="' . $product_sku . '"><i style="color: ' . $cart_color . '" class="ts-woocommerce-product-icon ts-woocommerce-product-cart ts-ecommerce-cart4"></i></a>';
								} else {
									$output .= '<a style="position: inherit; margin-right: 10px;" class="ts-woocommerce-product-purchase" href="?add-to-cart=' . $product_id . '" rel="nofollow" data-id="' . $product_id . '" data-sku="' . $product_sku . '"><i style="color: ' . $cart_color . '" class="ts-woocommerce-product-icon ts-woocommerce-product-cart ts-ecommerce-cart4"></i></a>';
								}
							}
							if ($show_link == "true") {
								$output .= '<a style="position: inherit; margin-right: 10px;" href="' . $link . '" class="ts-woocommerce-product-link"><i style="color: ' . $link_color . '" class="ts-woocommerce-product-icon ts-woocommerce-product-view ts-ecommerce-forward"></i></a>';
							}
							$output .= $rating_title;
						$output .= '</div>';
					}			
					if ($rating_tooltipcontent != '') {
						$output .= '<div class="ts-rating-tooltip ' . $rating_tooltipclasses . '" ' . $rating_tooltipcontent . '>';
					}			
						$output .= '<div class="ts-star-rating' . ($rating_rtl == "false" ? "" : "-rtl") . ' ts-rating-active " style="font-size: ' . $rating_size . 'px; line-height: ' . ($rating_size + 5) . 'px;">';
							if (($caption_show == "true") && ($caption_position == "left")) {
								$output .= '<div class="ts-rating-caption" style="margin-right: 10px;">';
									if ($rating_rtl == "false") {
										$output .= '<span class="label ' . $caption_class . '" style="' . $caption_background . '">' . $rating_value . ' / ' . number_format($rating_maximum, 2, $caption_digits, '') . '</span>';
									} else {
										$output .= '<span class="label ' . $caption_class . '" style="' . $caption_background . '">' . number_format($rating_maximum, 2, $caption_digits, '') . ' / ' . $rating_value . '</span>';
									}
								$output .= '</div>';
							}
							$output .= '<div class="ts-rating-container' . ($rating_rtl == "false" ? "" : "-rtl") . ' ts-rating-glyph-holder ' . $rating_class . '" style="color: ' . ($rating_rtl == "false" ? $color_empty : $color_rated) . ';">';
								$output .= '<div class="ts-rating-stars ' . $rating_class . '" style="color: ' . ($rating_rtl == "false" ? $color_rated : $color_empty) . '; width: ' . $rating_width . '%;"></div>';
							$output .= '</div>';
							if (($caption_show == "true") && ($caption_position == "right")) {
								$output .= '<div class="ts-rating-caption" style="margin-left: 10px;">';
									if ($rating_rtl == "false") {
										$output .= '<span class="label ' . $caption_class . '" style="' . $caption_background . '">' . $rating_value . ' / ' . number_format($rating_maximum, 2, $caption_digits, '') . '</span>';
									} else {
										$output .= '<span class="label ' . $caption_class . '" style="' . $caption_background . '">' . number_format($rating_maximum, 2, $caption_digits, '') . ' / ' . $rating_value . '</span>';
									}
								$output .= '</div>';
							}
						$output .= '</div>';			
					if ($rating_tooltipcontent != '') {
						$output .= '</div>';
					}			
					if (($rating_position == 'bottom') && ($rating_title != '')) {
						$output .= '<div class="ts-rating-title ts-rating-title-bottom ' . ($title_truncate == "true" ? " ts-truncated" : "") . '" style="font-size: ' . $title_size . 'px; line-height: ' . $line_height . 'px; vertical-align: middle;">';
							if ($show_cart == "true") {
								if ($variable == "true") {
									$output .= '<a style="position: inherit; margin-left: 10px;" class="ts-woocommerce-product-purchase" href="' . $link . '" rel="nofollow" data-id="' . $product_id . '" data-sku="' . $product_sku . '"><i style="color: ' . $cart_color . '" class="ts-woocommerce-product-icon ts-woocommerce-product-cart ts-ecommerce-cart4"></i></a>';
								} else {
									$output .= '<a style="position: inherit; margin-left: 10px;" class="ts-woocommerce-product-purchase" href="?add-to-cart=' . $product_id . '" rel="nofollow" data-id="' . $product_id . '" data-sku="' . $product_sku . '"><i style="color: ' . $cart_color . '" class="ts-woocommerce-product-icon ts-woocommerce-product-cart ts-ecommerce-cart4"></i></a>';
								}
							}
							if ($show_link == "true") {
								$output .= '<a style="position: inherit; margin-left: 10px;" href="' . $link . '" class="ts-woocommerce-product-link"><i style="color: ' . $link_color . '" class="ts-woocommerce-product-icon ts-woocommerce-product-view ts-ecommerce-forward"></i></a>';
							}
							$output .= $rating_title;
						$output .= '</div>';
					}
				$output .= '</div>';
		
				echo $output;
				
				$myvariable = ob_get_clean();
				return $myvariable;
			}
		
			// Add WooCommerce Basic Slider Elements
			function TS_VCSC_WooCommerce_Rating_Basic_Elements() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Basic Products Slider
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					"name"                              => __( "Single Product Rating", "ts_visual_composer_extend" ),
					"base"                              => "TS_VCSC_WooCommerce_Rating_Basic",
					"icon" 	                            => "ts-composer-element-icon-woocommerce-custom",
					"category"                          => __( 'VC WooCommerce', "ts_visual_composer_extend" ),
					"description"                       => __("Place a rating for a product", "ts_visual_composer_extend"),
					"admin_enqueue_js"                	=> "",
					"admin_enqueue_css"               	=> "",
					"params"                            => array(
						// Rating Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_1",
							"seperator"         		=> "Product Selection",
						),
						array(
							"type"                      => "custompost",
							"heading"                   => __( "Product", "ts_visual_composer_extend" ),
							"param_name"                => "id",
							"posttype"                  => "product",
							"posttaxonomy"              => "product_cat",
							"taxonomy"              	=> "product_cat",
							"postsingle"				=> "Product",
							"postplural"				=> "Products",
							"postclass"					=> "product",
						),
						array(
							"type"                      => "hidden_input",
							"heading"                   => __( "Product", "ts_visual_composer_extend" ),
							"param_name"                => "custompost_name",
							"value"                     => "",
							"admin_label"		        => true
						),
						// Rating Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_2",
							"seperator"         		=> "Rating Settings",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Symbol", "ts_visual_composer_extend" ),
							"param_name"        		=> "rating_symbol",
							"value"             		=> array(
								__( "Other Icon", "ts_visual_composer_extend" )                 => "other",
								__( "Smileys", "ts_visual_composer_extend" )                    => "smile",
							),
							"admin_label"				=> true,
							"description"       		=> __( "Select how you want to display the rating.", "ts_visual_composer_extend" )
						),
						array(
							"type" 						=> "icons_panel",
							'heading' 					=> __( 'Rating Icon', 'ts_visual_composer_extend' ),
							'param_name' 				=> 'rating_icon',
							'value'						=> '',
							"default"					=> "ts-ecommerce-starfull1",
							"settings" 					=> array(
								"emptyIcon" 							=> false,
								'emptyIconValue'						=> 'transparent',
								"hasSearch"								=> false,
								"type" 									=> 'rating',
							),
							"admin_label"       		=> true,
							"description"       		=> __( "Select which icon should be used to reflect the rating.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "rating_symbol", 'value' => 'other' ),
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "RTL Alignment", "ts_visual_composer_extend" ),
							"param_name"        		=> "rating_rtl",
							"value"             		=> "false",
							"description"       		=> __( "Switch the toggle if you want to show the rating in 'RTL' (Right-To-Left) alignment.", "ts_visual_composer_extend" )
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Use Quarter Increments", "ts_visual_composer_extend" ),
							"param_name"        		=> "rating_quarter",
							"value"             		=> "true",
							"description"       		=> __( "Switch the toggle if you want to show the rating in quarter increments; otherwise, actual value will be shown.", "ts_visual_composer_extend" )
						),		
						// Style Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_3",
							"seperator"         		=> "Style Settings",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Max. Icon Size", "ts_visual_composer_extend" ),
							"param_name"        		=> "rating_size",
							"value"             		=> "24",
							"min"               		=> "12",
							"max"               		=> "512",
							"step"              		=> "1",
							"decimals"					=> "0",
							"unit"              		=> 'px',
							"admin_label"				=> true,
							"description"       		=> __( "Select the maximum individual rating icon size; site will scale to fit into column if necessary.", "ts_visual_composer_extend" ),
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Auto-Size Adjust", "ts_visual_composer_extend" ),
							"param_name"        		=> "rating_auto",
							"value"             		=> "true",
							"admin_label"				=> true,
							"description"       		=> __( "Switch the toggle if you want the rating to automatically adjust the icon size in order to fit into columns.", "ts_visual_composer_extend" )
						),				
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Rated Icon Fill Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "color_rated",
							"value"             		=> "#FFD800",
							"description"       		=> __( "Define the fill color for the rated icons.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Empty Icon Fill Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "color_empty",
							"value"             		=> "#e3e3e3",
							"description"       		=> __( "Define the fill color for the empty icons.", "ts_visual_composer_extend" ),
						),
						// Title Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_4",
							"seperator"         		=> "Title Settings",
							"group" 					=> "Title Settings",
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Show Title", "ts_visual_composer_extend" ),
							"param_name"        		=> "rating_title",
							"value"             		=> "true",
							"description"       		=> __( "Switch the toggle if you want to show a title with the rating.", "ts_visual_composer_extend" ),
							"group" 					=> "Title Settings",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Title Font Size", "ts_visual_composer_extend" ),
							"param_name"        		=> "title_size",
							"value"             		=> "24",
							"min"               		=> "12",
							"max"               		=> "60",
							"step"             		 	=> "1",
							"unit"              		=> 'px',
							"description"       		=> __( "Select the font size for the rating title.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "rating_title", 'value' => 'true' ),
							"group" 					=> "Title Settings",
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Truncate Title via CSS", "ts_visual_composer_extend" ),
							"param_name"        		=> "title_truncate",
							"value"             		=> "true",
							"description"       		=> __( "Switch the toggle if you want to truncate a long title via CSS.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "rating_title", 'value' => 'true' ),
							"group" 					=> "Title Settings",
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Use Product Name as Title", "ts_visual_composer_extend" ),
							"param_name"        		=> "use_name",
							"value"             		=> "true",
							"description"       		=> __( "Switch the toggle if you want to use the product name as rating title.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "rating_title", 'value' => 'true' ),
							"group" 					=> "Title Settings",
						),
						array(
							"type"              		=> "textfield",
							"heading"          		 	=> __( "Custom Rating Title", "ts_visual_composer_extend" ),
							"param_name"        		=> "custom_title",
							"value"             		=> "",
							"description"       		=> __( "Enter a custom title for the rating element.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "use_name", 'value' => 'false' ),
							"group" 					=> "Title Settings",
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Show Cart Button in Title", "ts_visual_composer_extend" ),
							"param_name"        		=> "show_cart",
							"value"             		=> "true",
							"description"       		=> __( "Switch the toggle if you want to show an add to cart button in the rating title.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "rating_title", 'value' => 'true' ),
							"group" 					=> "Title Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Cart Button Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "cart_color",
							"value"             		=> "#cccccc",
							"description"       		=> __( "Define the color for the add to cart button in the rating title.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "show_cart", 'value' => 'true' ),
							"group" 					=> "Title Settings",
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Show Product Link in Title", "ts_visual_composer_extend" ),
							"param_name"        		=> "show_link",
							"value"             		=> "true",
							"description"       		=> __( "Switch the toggle if you want to show a link to the product page in the rating title.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "rating_title", 'value' => 'true' ),
							"group" 					=> "Title Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Link Button Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "link_color",
							"value"             		=> "#cccccc",
							"description"       		=> __( "Define the color for the link to the product page in the rating title.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "show_link", 'value' => 'true' ),
							"group" 					=> "Title Settings",
						),
						// Rating Caption
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_5",
							"seperator"         		=> "Caption Settings",
							"group" 					=> "Caption Settings",
						),
						array(
							"type"						=> "switch_button",
							"heading"           		=> __( "Show Rating Caption", "ts_visual_composer_extend" ),
							"param_name"        		=> "caption_show",
							"value"             		=> "true",
							"description"       		=> __( "Switch the toggle if you also want to show a caption with the rating as number.", "ts_visual_composer_extend" ),
							"group" 					=> "Caption Settings",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Position", "ts_visual_composer_extend" ),
							"param_name"        		=> "caption_position",
							"value"             		=> array(
								__( "Left", "ts_visual_composer_extend" )					=> "left",
								__( "Right", "ts_visual_composer_extend" )					=> "right",
							),
							"description"       		=> __( "Select where the numeric rating caption block should be placed.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "caption_show", 'value' => 'true' ),
							"group" 					=> "Caption Settings",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Decimals Seperator", "ts_visual_composer_extend" ),
							"param_name"        		=> "caption_digits",
							"width"             		=> 150,
							"value"             		=> array(
								__( 'Dot', "ts_visual_composer_extend" )          => ".",
								__( 'Comma', "ts_visual_composer_extend" )        => ",",                        
								__( 'Space', "ts_visual_composer_extend" )        => " ",
							),
							"description"       		=> __( "Select a character to seperate decimals in the rating value.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "caption_show", 'value' => 'true' ),
							"group" 					=> "Caption Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Caption Background 0-1", "ts_visual_composer_extend" ),
							"param_name"        		=> "caption_danger",
							"value"             		=> "#d9534f",
							"description"       		=> __( "Define the caption background for rating values between 0 - 1.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "caption_show", 'value' => 'true' ),
							"group" 					=> "Caption Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Caption Background 1-2", "ts_visual_composer_extend" ),
							"param_name"        		=> "caption_warning",
							"value"             		=> "#f0ad4e",
							"description"       		=> __( "Define the caption background for rating values between 1 - 2.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "caption_show", 'value' => 'true' ),
							"group" 					=> "Caption Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Caption Background 2-3", "ts_visual_composer_extend" ),
							"param_name"        		=> "caption_info",
							"value"             		=> "#5bc0de",
							"description"       		=> __( "Define the caption background for rating values between 2 - 3.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "caption_show", 'value' => 'true' ),
							"group" 					=> "Caption Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Caption Background 3-4", "ts_visual_composer_extend" ),
							"param_name"        		=> "caption_primary",
							"value"             		=> "#428bca",
							"description"       		=> __( "Define the caption background for rating values between 3 - 4.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "caption_show", 'value' => 'true' ),
							"group" 					=> "Caption Settings",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Caption Background 4-5", "ts_visual_composer_extend" ),
							"param_name"        		=> "caption_success",
							"value"             		=> "#5cb85c",
							"description"       		=> __( "Define the caption background for rating values between 4 - 5.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "caption_show", 'value' => 'true' ),
							"group" 					=> "Caption Settings",
						),
						// Rating Tooltip
						array(
							"type"						=> "seperator",
							"param_name"				=> "seperator_6",
							"seperator"         		=> "Tooltip Settings",
							"group" 					=> "Tooltip Settings",
						),
						array(
							"type"						=> "switch_button",
							"heading"					=> __( "Use Advanced Tooltip", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltip_css",
							"value"						=> "false",
							"description"       		=> __( "Switch the toggle if you want to apply an advanced tooltip to the element.", "ts_visual_composer_extend" ),
							"group" 					=> "Tooltip Settings",
						),
						array(
							"type"						=> "textarea",
							"heading"					=> __( "Tooltip Content", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltip_content",
							"value"						=> "",
							"description"				=> __( "Enter the tooltip content here (do not use quotation marks).", "ts_visual_composer_extend" ),
							"group" 					=> "Tooltip Settings",
						),
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Tooltip Position", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltip_position",
							"value"					=> array(
								__( "Top", "ts_visual_composer_extend" )                            => "ts-simptip-position-top",
								__( "Bottom", "ts_visual_composer_extend" )                         => "ts-simptip-position-bottom",
							),
							"description"				=> __( "Select the tooltip position in relation to the element.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "tooltip_css", 'value' => 'true' ),
							"group" 					=> "Tooltip Settings",
						),
						array(
							"type"						=> "dropdown",
							"heading"					=> __( "Tooltip Style", "ts_visual_composer_extend" ),
							"param_name"				=> "tooltip_style",
							"value"             		=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ToolTipster_Layouts,
							"description"				=> __( "Select the tooltip style.", "ts_visual_composer_extend" ),
							"dependency"				=> array( 'element' => "tooltip_css", 'value' => 'true' ),
							"group" 					=> "Tooltip Settings",
						),
						// Other Rating Settings
						array(
							"type"              		=> "seperator",
							"param_name"        		=> "seperator_7",
							"seperator"         		=> "Other Settings",
							"group" 					=> "Other Settings",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Margin: Top", "ts_visual_composer_extend" ),
							"param_name"        		=> "margin_top",
							"value"             		=> "20",
							"min"               		=> "-50",
							"max"               		=> "500",
							"step"              		=> "1",
							"unit"              		=> 'px',
							"description"       		=> __( "Select the top margin for the element.", "ts_visual_composer_extend" ),
							"group" 					=> "Other Settings",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Margin: Bottom", "ts_visual_composer_extend" ),
							"param_name"        		=> "margin_bottom",
							"value"             		=> "20",
							"min"               		=> "-50",
							"max"               		=> "500",
							"step"             		 	=> "1",
							"unit"              		=> 'px',
							"description"       		=> __( "Select the bottom margin for the element.", "ts_visual_composer_extend" ),
							"group" 					=> "Other Settings",
						),
						array(
							"type"              		=> "textfield",
							"heading"          		 	=> __( "Define ID Name", "ts_visual_composer_extend" ),
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
		}
	}
	// Register Container and Child Shortcode with Visual Composer
	if ((class_exists('WPBakeryShortCode')) && (!class_exists('WPBakeryShortCode_TS_VCSC_WooCommerce_Rating_Basic'))) {
		class WPBakeryShortCode_TS_VCSC_WooCommerce_Rating_Basic extends WPBakeryShortCode {};
	}
	// Initialize "WooCommerce Basic Rating" Class
	if (class_exists('TS_WooCommerce_Rating_Basic')) {
		$TS_WooCommerce_Rating_Basic = new TS_WooCommerce_Rating_Basic;
	}
?>