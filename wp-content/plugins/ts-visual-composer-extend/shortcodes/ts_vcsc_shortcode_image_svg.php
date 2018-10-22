<?php
	add_shortcode('TS_VCSC_Image_SVG', 'TS_VCSC_Image_SVG_Function');
	function TS_VCSC_Image_SVG_Function ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();

		// Load Required Files
		wp_enqueue_script('ts-extend-krautlightbox');
		wp_enqueue_style('ts-extend-krautlightbox');
		wp_enqueue_script('ts-extend-snapsvg');
		wp_enqueue_style('ts-visual-composer-extend-front');
		wp_enqueue_script('ts-visual-composer-extend-front');
		wp_enqueue_style('ts-extend-imageeffects');
		wp_enqueue_script('ts-extend-imageeffects');
	
		extract( shortcode_atts( array(
			'style'							=> 'imagesvg1',
			'image'							=> '',
			'content_image_size'			=> 'medium',
			'grayscale'						=> 'true',
			
			'speed_change'					=> 'false',
			'speed_svg'						=> 250,
			'speed_title'					=> 500,
			'speed_message'					=> 500,
			'speed_link'					=> 500,
			
			'link_container'				=> 'false',
			'trigger'						=> 'image',
			'link'							=> '',

			'scroll_target'					=> '',
			'scroll_speed'					=> 2000,
			'scroll_effect'					=> 'linear',
			'scroll_offset'					=> 'desktop:0px;tablet:0px;mobile:0px',
			'scroll_hashtag'				=> 'false',
			
			'color_fill'					=> '#ffffff',
			'color_title'					=> '#3498db',
			'color_text'					=> '#333333',
			'color_button_back'				=> '#ffffff',
			'color_button_text'				=> '#333333',
			
			'text_title'					=> '',
			'text_content'					=> '',
			'text_button'					=> '',
			
			'lightbox_group'				=> 'true',
			'lightbox_group_name'			=> '',
			'lightbox_size'					=> 'full',
			'lightbox_effect'				=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LightboxDefaultAnimation,
			'lightbox_speed'				=> 5000,
			'lightbox_social'				=> 'false',
			'lightbox_backlight'			=> 'auto',
			'lightbox_backlight_color'		=> '#ffffff',
			
			'content_tooltip'				=> 'true',
			'content_tooltip_position'		=> 'ts-simptip-position-top',
			'content_tooltip_style'			=> 'ts-simptip-style-black',
			
			'tooltipster_offsetx'			=> 0,
			'tooltipster_offsety'			=> 0,
			
			'margin_top'					=> 0,
			'margin_bottom'					=> 0,
			'el_id' 						=> '',
			'el_class'                  	=> '',
			'css'							=> '',
		), $atts ));
		
		$output = $notice = $visible = $styles = '';
		
		$randomizer							= mt_rand(999999, 9999999);
		$inline								= TS_VCSC_FrontendAppendCustomRules('style');
		
		if (!empty($el_id)) {
			$image_svg_id					= $el_id;
		} else {
			$image_svg_id					= 'ts-vcsc-image-svg-' . $randomizer;
		}

		// Images		
		if (!empty($image)) {
			$modal_image 					= wp_get_attachment_image_src($image, $lightbox_size);
			$modal_thumb 					= wp_get_attachment_image_src($image, $content_image_size);
		}
		
		// Link Values
		if ($trigger == "link") {
			$link 							= TS_VCSC_Advancedlinks_GetLinkData($link);
			$a_href							= $link['url'];
			$a_title 						= $link['title'];
			$a_target 						= $link['target'];
			$a1_rel 						= $link['rel'];
			if (!empty($a1_rel)) {
				$a1_rel 					= 'rel="' . esc_attr(trim($a1_rel)) . '"';
			}
		} else if (($trigger == "scroll") && ($scroll_target != '')) {
			$scroll_target					= str_replace("#", "", $scroll_target);
			$a_href							= "#" . $scroll_target;
			$a_title 						= "";
			$a_target 						= "_parent";
			$a_rel							= 'rel="bookmark"';
		}
		
		// Scroll Navigation
		if (($trigger == "scroll") && ($scroll_target != '')) {
			wp_enqueue_script('jquery-easing');
			$scroll_offset 					= explode(';', $scroll_offset);			
			$offsetDesktop					= explode(':', $scroll_offset[0]);
			$offsetDesktop					= str_replace("px", "", $offsetDesktop[1]);
			$offsetTablet					= explode(':', $scroll_offset[1]);
			$offsetTablet					= str_replace("px", "", $offsetTablet[1]);
			$offsetMobile					= explode(':', $scroll_offset[2]);
			$offsetMobile					= str_replace("px", "", $offsetMobile[1]);	
			$scroll_class					= 'ts-button-page-navigator';			
			$scroll_data					= 'data-scroll-target="' . $scroll_target . '" data-scroll-speed="' . $scroll_speed . '" data-scroll-effect="' . $scroll_effect . '" data-scroll-offsetdesktop="' . $offsetDesktop . '" data-scroll-offsettablet="' . $offsetTablet . '" data-scroll-offsetmobile="' . $offsetMobile . '" data-scroll-hashtag="' . $scroll_hashtag . '"';
		} else {
			$scroll_class					= '';
			$scroll_data					= '';
		}
		
		// Lightbox
		if ($lightbox_backlight == "auto") {
			$nacho_color					= '';
		} else if ($lightbox_backlight == "custom") {
			$nacho_color					= 'data-color="' . $lightbox_backlight_color . '"';
		} else if ($lightbox_backlight == "hideit") {
			$nacho_color					= 'data-color="rgba(0, 0, 0, 0)"';
		}
		
		// Tooltip
		if ((($text_title != '') || ($text_content != '')) && ($content_tooltip == "true")) {
			wp_enqueue_style('ts-extend-tooltipster');
			wp_enqueue_script('ts-extend-tooltipster');
			$tooltip_position				= TS_VCSC_TooltipMigratePosition($content_tooltip_position);
			$tooltip_style					= TS_VCSC_TooltipMigrateStyle($content_tooltip_style);
			$tooltip_content 				= 'data-tooltipster-title="' . $text_title . '" data-tooltipster-text="' . $text_content . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="swing" data-tooltipster-trigger="hover" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . $tooltipster_offsety . '"';
			$tooltip_class					= 'ts-has-tooltipster-tooltip';			
			$container_addition				= 0;
		} else {
			$tooltip_content				= '';
			$tooltip_class					= '';
			$container_addition				= 0;
		}
		
		// Visual Composer Override
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Image_SVG', $atts);
		} else {
			$css_class						= '';
		}
		
		// Custom Styling
		if ($speed_change == "true") {
			if ($inline == "false") {
				$styles .= '<style id="' . $image_svg_id . '-styles" type="text/css">';
			}
				$styles .= 'body #' . $image_svg_id . ' .ts-image-svg-main figure figcaption h2 {';
					$styles .= '-webkit-transition-duration: ' . $speed_title . 'ms;';
					$styles .= '-moz-transition-duration: ' . $speed_title . 'ms;';
					$styles .= '-ms-transition-duration: ' . $speed_title . 'ms;';
					$styles .= '-o-transition-duration: ' . $speed_title . 'ms;';
					$styles .= 'transition-duration: ' . $speed_title . 'ms;';
				$styles .= '}';
				$styles .= 'body #' . $image_svg_id . ' .ts-image-svg-main figure figcaption p {';
					$styles .= '-webkit-transition-duration: ' . $speed_message . 'ms;';
					$styles .= '-moz-transition-duration: ' . $speed_message . 'ms;';
					$styles .= '-ms-transition-duration: ' . $speed_message . 'ms;';
					$styles .= '-o-transition-duration: ' . $speed_message . 'ms;';
					$styles .= 'transition-duration: ' . $speed_message . 'ms;';
				$styles .= '}';
				$styles .= 'body #' . $image_svg_id . ' .ts-image-svg-main figure .ts-image-svg-button {';
					$styles .= '-webkit-transition-duration: ' . $speed_link . 'ms;';
					$styles .= '-moz-transition-duration: ' . $speed_link . 'ms;';
					$styles .= '-ms-transition-duration: ' . $speed_link . 'ms;';
					$styles .= '-o-transition-duration: ' . $speed_link . 'ms;';
					$styles .= 'transition-duration: ' . $speed_link . 'ms;';
				$styles .= '}';
			if ($inline == "false") {
				$styles .= '</style>';
			}
		}
		if (($styles != "") && ($inline == "true")) {
			wp_add_inline_style('ts-visual-composer-extend-custom', TS_VCSC_MinifyCSS($styles));
		}
		
		$output .= '<div id="' . $image_svg_id . '-holder" class="ts-image-svg-holder ' . $css_class . ' ' . $tooltip_class . '" ' . $tooltip_content . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
			if (($styles != "") && ($inline == "false")) {
					$output .= TS_VCSC_MinifyCSS($styles);
			}
			if ($style == "imagesvg1") {
				$output .= '<div id="' . $image_svg_id . '" class="ts-image-svg-container nch-lightbox-svg-trigger">';
					$output .= '<div class="ts-image-svg-style1 ts-image-svg-main clearfix">';
						// "m 180,34.57627 -180,0 L 0,0 180,0 z">';
						if ($link_container == "false") {
							$output .= '<div class="ts-image-svg-wrapper" data-easing="easeinout" data-speed="' . $speed_svg . '" data-path-hover="m 180,50 -180,0 L 0,0 180,0 z">';
						} else {
							if ($trigger == "link") {
								$output .= '<a href="' . $a_href . '" target="' . $a_target . '" ' . $a1_rel . ' title="" class="ts-image-svg-wrapper" data-easing="easeinout" data-speed="' . $speed_svg . '" data-path-hover="m 180,50 -180,0 L 0,0 180,0 z">';
							} else if ($trigger == "image") {
								$output .= '<a href="' . $modal_image[0] . '" class="ts-image-svg-wrapper nch-lightbox-media no-ajaxy" data-easing="easeinout" data-speed="' . $speed_svg . '" data-path-hover="m 180,50 -180,0 L 0,0 180,0 z" data-title="' . $text_title . '" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
							} else if ($trigger == "scroll") {
								$output .= '<a href="' . $a_href . '" class="ts-image-svg-wrapper ' . $scroll_class . '" target="' . $a_target . '" ' . $a_rel . ' title="" ' . $scroll_data . ' data-easing="easeinout" data-speed="' . $speed_svg . '" data-path-hover="m 180,50 -180,0 L 0,0 180,0 z">';
							}
						}
							$output .= '<figure>';
								$output .= '<img class="' . ($grayscale == "true" ? "nch-lightbox-trigger-grayscale" : "") . '" data-no-lazy="1" src="' . $modal_image[0] . '" />';
								$output .= '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" fill="' . $color_fill . '" viewBox="0 0 180 320" preserveAspectRatio="none"><path d="M 180,160 0,218 0,0 180,0 z"/></svg>';
								$output .= '<figcaption>';
									$output .= '<div class="ts-image-svg-content">';
										$output .= '<h2 style="color: ' . $color_title . ';">' . $text_title . '</h2>';
										$output .= '<p style="color: ' . $color_text . ';">' . $text_content . '</p>';
									$output .= '</div>';
									$output .= '<div class="ts-image-svg-event">';
										if (($trigger == "link") && ($link_container == "false")) {
											$output .= '<a href="' . $a_href . '" target="' . $a_target . '" title="">';
										} else if (($trigger == "image") && ($link_container == "false")) {
											$output .= '<a href="' . $modal_image[0] . '" class="nch-lightbox-media no-ajaxy" data-title="' . $text_title . '" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
										} else if (($trigger == "scroll") && ($link_container == "false")) {
											$output .= '<a href="' . $a_href . '" class="' . $scroll_class . '" target="' . $a_target . '" ' . $a_rel . ' title="" ' . $scroll_data . '>';
										}
											$output .= '<button class="ts-image-svg-button" style="background: ' . $color_button_back . '; color: ' . $color_button_text . ';">' . $text_button . '</button>';
										if ($link_container == "false") {
											$output .= '</a>';
										}
									$output .= '</div>';
								$output .= '</figcaption>';
							$output .= '</figure>';
						if ($link_container == "false") {
							$output .= '</div>';
						} else {
							$output .= '</a>';
						}
					$output .= '</div>';
				$output .= '</div>';
			}
			if ($style == "imagesvg2") {
				$output .= '<div id="' . $image_svg_id . '" class="ts-image-svg-container nch-lightbox-svg-trigger">';
					$output .= '<div class="ts-image-svg-style2 ts-image-svg-main clearfix">';
						// "m 0,0 0,47.7775 c 24.580441,3.12569 55.897012,-8.199417 90,-8.199417 34.10299,0 65.41956,11.325107 90,8.199417 L 180,0 z">';
						if ($link_container == "false") {
							$output .= '<div class="ts-image-svg-wrapper" data-easing="easeinout" data-speed="' . $speed_svg . '" data-path-hover="m 0,0 0,75 c 30,0 60,-20 90,-20 40,0 60,20 90,20 l 0,-75 z">';
						} else {
							if ($trigger == "link") {
								$output .= '<a href="' . $a_href . '" target="' . $a_target . '" ' . $a1_rel . ' title="" class="ts-image-svg-wrapper" data-easing="easeinout" data-speed="' . $speed_svg . '" data-path-hover="m 0,0 0,75 c 30,0 60,-20 90,-20 40,0 60,20 90,20 l 0,-75 z">';
							} else if ($trigger == "image") {
								$output .= '<a href="' . $modal_image[0] . '" class="ts-image-svg-wrapper nch-lightbox-media no-ajaxy" data-easing="easeinout" data-speed="' . $speed_svg . '" data-path-hover="m 0,0 0,75 c 30,0 60,-20 90,-20 40,0 60,20 90,20 l 0,-75 z" data-title="' . $text_title . '" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
							} else if ($trigger == "scroll") {
								$output .= '<a href="' . $a_href . '" class="ts-image-svg-wrapper ' . $scroll_class . '" target="' . $a_target . '" ' . $a_rel . ' title="" ' . $scroll_data . ' data-easing="easeinout" data-speed="' . $speed_svg . '" data-path-hover="m 0,0 0,75 c 30,0 60,-20 90,-20 40,0 60,20 90,20 l 0,-75 z">';
							}
						}					
							$output .= '<figure class="ts-image-svg-figure">';
								$output .= '<img class="' . ($grayscale == "true" ? "nch-lightbox-trigger-grayscale" : "") . '" data-no-lazy="1" src="' . $modal_image[0] . '" />';
								$output .= '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" fill="' . $color_fill . '" viewBox="0 0 180 320" preserveAspectRatio="none"><path d="m 0,0 0,171.14385 c 24.580441,15.47138 55.897012,24.75772 90,24.75772 34.10299,0 65.41956,-9.28634 90,-24.75772 L 180,0 0,0 z"/></svg>';
								$output .= '<figcaption class="ts-image-svg-figcaption">';
									$output .= '<div class="ts-image-svg-content">';
										$output .= '<h2 style="color: ' . $color_title . ';">' . $text_title . '</h2>';
										$output .= '<p style="color: ' . $color_text . ';">' . $text_content . '</p>';
									$output .= '</div>';
									$output .= '<div class="ts-image-svg-event">';
										if (($trigger == "link") && ($link_container == "false")) {
											$output .= '<a href="' . $a_href . '" target="' . $a_target . '" title="">';
										} else if (($trigger == "image") && ($link_container == "false")) {
											$output .= '<a href="' . $modal_image[0] . '" class="nch-lightbox-media no-ajaxy" data-title="' . $text_title . '" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
										} else if (($trigger == "scroll") && ($link_container == "false")) {
											$output .= '<a href="' . $a_href . '" class="' . $scroll_class . '" target="' . $a_target . '" ' . $a_rel . ' title="" ' . $scroll_data . '>';
										}
											$output .= '<button class="ts-image-svg-button" style="background: ' . $color_button_back . '; color: ' . $color_button_text . ';">' . $text_button . '</button>';
										if ($link_container == "false") {
											$output .= '</a>';
										}
									$output .= '</div>';
								$output .= '</figcaption>';
							$output .= '</figure>';
						if ($link_container == "false") {
							$output .= '</div>';
						} else {
							$output .= '</a>';
						}
					$output .= '</div>';
				$output .= '</div>';
			}
			if ($style == "imagesvg3") {
				$output .= '<div id="' . $image_svg_id . '" class="ts-image-svg-container nch-lightbox-svg-trigger">';
					$output .= '<div class="ts-image-svg-style3 ts-image-svg-main clearfix">';
						// "m 0,0 0,47.7775 c 24.580441,3.12569 55.897012,-8.199417 90,-8.199417 34.10299,0 65.41956,11.325107 90,8.199417 L 180,0 z">';
						if ($link_container == "false") {
							$output .= '<div class="ts-image-svg-wrapper" data-easing="easeinout" data-speed="' . $speed_svg . '" data-path-hover="m 0,0 180,0 0,60 c 0,0 -55,-25 -90,16 C 50,30 0,60 0,60 z">';
						} else {
							if ($trigger == "link") {
								$output .= '<a href="' . $a_href . '" target="' . $a_target . '" ' . $a1_rel . ' title="" class="ts-image-svg-wrapper" data-easing="easeinout" data-speed="' . $speed_svg . '" data-path-hover="m 0,0 180,0 0,60 c 0,0 -55,-25 -90,16 C 50,30 0,60 0,60 z">';
							} else if ($trigger == "image") {
								$output .= '<a href="' . $modal_image[0] . '" class="ts-image-svg-wrapper nch-lightbox-media no-ajaxy" data-easing="easeinout" data-speed="' . $speed_svg . '" data-path-hover="m 0,0 180,0 0,60 c 0,0 -55,-25 -90,16 C 50,30 0,60 0,60 z" data-title="' . $text_title . '" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
							} else if ($trigger == "scroll") {
								$output .= '<a href="' . $a_href . '" class="ts-image-svg-wrapper ' . $scroll_class . '" target="' . $a_target . '" ' . $a_rel . ' title="" ' . $scroll_data . ' data-easing="easeinout" data-speed="' . $speed_svg . '" data-path-hover="m 0,0 180,0 0,60 c 0,0 -55,-25 -90,16 C 50,30 0,60 0,60 z">';
							}
						}					
							$output .= '<figure class="ts-image-svg-figure">';
								$output .= '<img class="' . ($grayscale == "true" ? "nch-lightbox-trigger-grayscale" : "") . '" data-no-lazy="1" src="' . $modal_image[0] . '" />';
								$output .= '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" fill="' . $color_fill . '" viewBox="0 0 180 320" preserveAspectRatio="none"><path d="M0-2h180v186.8c0,0-44,21-90-12.1c-48.8-35.1-90,12.1-90,12.1V-2z"/></svg>';
								$output .= '<figcaption class="ts-image-svg-figcaption">';
									$output .= '<div class="ts-image-svg-content">';
										$output .= '<h2 style="color: ' . $color_title . ';">' . $text_title . '</h2>';
										$output .= '<p style="color: ' . $color_text . ';">' . $text_content . '</p>';
									$output .= '</div>';
									$output .= '<div class="ts-image-svg-event">';
										if (($trigger == "link") && ($link_container == "false")) {
											$output .= '<a href="' . $a_href . '" target="' . $a_target . '" title="">';
										} else if (($trigger == "image") && ($link_container == "false")) {
											$output .= '<a href="' . $modal_image[0] . '" class="nch-lightbox-media no-ajaxy" data-title="' . $text_title . '" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
										} else if (($trigger == "scroll") && ($link_container == "false")) {
											$output .= '<a href="' . $a_href . '" class="' . $scroll_class . '" target="' . $a_target . '" ' . $a_rel . ' title="" ' . $scroll_data . '>';
										}
											$output .= '<button class="ts-image-svg-button" style="background: ' . $color_button_back . '; color: ' . $color_button_text . ';">' . $text_button . '</button>';
										if ($link_container == "false") {
											$output .= '</a>';
										}
									$output .= '</div>';
								$output .= '</figcaption>';
							$output .= '</figure>';
						if ($link_container == "false") {
							$output .= '</div>';
						} else {
							$output .= '</a>';
						}
					$output .= '</div>';
				$output .= '</div>';
			}
			if ($style == "imagesvg4") {
				$output .= '<div id="' . $image_svg_id . '" class="ts-image-svg-container nch-lightbox-svg-trigger">';
					$output .= '<div class="ts-image-svg-style4 ts-image-svg-main clearfix">';
						// "M 0,0 0,38 90,58 180.5,38 180,0 z">';
						if ($link_container == "false") {
							$output .= '<div class="ts-image-svg-wrapper" data-easing="easeinout" data-speed="' . $speed_svg . '" data-path-hover="M 0,0 0,50 90,75 180.5,50 180,0 z">';
						} else {
							if ($trigger == "link") {
								$output .= '<a href="' . $a_href . '" target="' . $a_target . '" ' . $a1_rel . ' title="" class="ts-image-svg-wrapper" data-easing="easeinout" data-speed="' . $speed_svg . '" data-path-hover="M 0,0 0,50 90,75 180.5,50 180,0 z">';
							} else if ($trigger == "image") {
								$output .= '<a href="' . $modal_image[0] . '" class="ts-image-svg-wrapper nch-lightbox-media no-ajaxy" data-easing="easeinout" data-speed="' . $speed_svg . '" data-path-hover="M 0,0 0,50 90,75 180.5,50 180,0 z" data-title="' . $text_title . '" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
							} else if ($trigger == "scroll") {
								$output .= '<a href="' . $a_href . '" class="ts-image-svg-wrapper ' . $scroll_class . '" target="' . $a_target . '" ' . $a_rel . ' title="" ' . $scroll_data . ' data-easing="easeinout" data-speed="' . $speed_svg . '" data-path-hover="M 0,0 0,50 90,75 180.5,50 180,0 z">';
							}
						}	
							$output .= '<figure class="ts-image-svg-figure">';
								$output .= '<img class="' . ($grayscale == "true" ? "nch-lightbox-trigger-grayscale" : "") . '" data-no-lazy="1" src="' . $modal_image[0] . '" />';
								$output .= '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" fill="' . $color_fill . '" viewBox="0 0 180 320" preserveAspectRatio="none"><path d="M 0,0 0,190 90,140 180,190 180,0 0,0 z"/></svg>';
								$output .= '<figcaption class="ts-image-svg-figcaption">';
									$output .= '<div class="ts-image-svg-content">';
										$output .= '<h2 style="color: ' . $color_title . ';">' . $text_title . '</h2>';
										$output .= '<p style="color: ' . $color_text . ';">' . $text_content . '</p>';
									$output .= '</div>';
									$output .= '<div class="ts-image-svg-event">';
										if (($trigger == "link") && ($link_container == "false")) {
											$output .= '<a href="' . $a_href . '" target="' . $a_target . '" title="">';
										} else if (($trigger == "image") && ($link_container == "false")) {
											$output .= '<a href="' . $modal_image[0] . '" class="nch-lightbox-media no-ajaxy" data-title="' . $text_title . '" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
										} else if (($trigger == "scroll") && ($link_container == "false")) {
											$output .= '<a href="' . $a_href . '" class="' . $scroll_class . '" target="' . $a_target . '" ' . $a_rel . ' title="" ' . $scroll_data . '>';
										}
											$output .= '<button class="ts-image-svg-button" style="background: ' . $color_button_back . '; color: ' . $color_button_text . ';">' . $text_button . '</button>';
										if ($link_container == "false") {
											$output .= '</a>';
										}
									$output .= '</div>';
								$output .= '</figcaption>';
							$output .= '</figure>';
						if ($link_container == "false") {
							$output .= '</div>';
						} else {
							$output .= '</a>';
						}
					$output .= '</div>';
				$output .= '</div>';
			}
			if ($style == "imagesvg5") {
				$output .= '<div id="' . $image_svg_id . '" class="ts-image-svg-container nch-lightbox-svg-trigger">';
					$output .= '<div class="ts-image-svg-style5 ts-image-svg-main clearfix">';
						if ($link_container == "false") {
							$output .= '<div class="ts-image-svg-wrapper" data-easing="easeinout" data-speed="' . $speed_svg . '" data-path-hover="m 0,320 L 0,290 L 180,260 L 180,320 z">';
						} else {
							if ($trigger == "link") {
								$output .= '<a href="' . $a_href . '" target="' . $a_target . '" ' . $a1_rel . ' title="" class="ts-image-svg-wrapper" data-easing="easeinout" data-speed="' . $speed_svg . '" data-path-hover="m 0,320 L 0,290 L 180,260 L 180,320 z">';
							} else if ($trigger == "image") {
								$output .= '<a href="' . $modal_image[0] . '" class="ts-image-svg-wrapper nch-lightbox-media no-ajaxy" data-easing="easeinout" data-speed="' . $speed_svg . '" data-path-hover="m 0,320 L 0,290 L 180,260 L 180,320 z" data-title="' . $text_title . '" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
							} else if ($trigger == "scroll") {
								$output .= '<a href="' . $a_href . '" class="ts-image-svg-wrapper ' . $scroll_class . '" target="' . $a_target . '" ' . $a_rel . ' title="" ' . $scroll_data . ' data-easing="easeinout" data-speed="' . $speed_svg . '" data-path-hover="m 0,320 L 0,290 L 180,260 L 180,320 z">';
							}
						}
							$output .= '<figure>';
								$output .= '<img class="' . ($grayscale == "true" ? "nch-lightbox-trigger-grayscale" : "") . '" data-no-lazy="1" src="' . $modal_image[0] . '" />';
								$output .= '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" fill="' . $color_fill . '" viewBox="0 0 180 320" preserveAspectRatio="none"><path d="M 0,320 0,320 180,320 180,320 z"/></svg>';
								$output .= '<figcaption>';
									$output .= '<div class="ts-image-svg-content">';
										$output .= '<h2 style="color: ' . $color_title . '; background: ' . $color_fill . ';">' . $text_title . '</h2>';
										$output .= '<p style="color: ' . $color_text . '; background: ' . $color_fill . ';">' . $text_content . '</p>';
									$output .= '</div>';
									$output .= '<div class="ts-image-svg-event">';
										if (($trigger == "link") && ($link_container == "false")) {
											$output .= '<a href="' . $a_href . '" target="' . $a_target . '" title="">';
										} else if (($trigger == "image") && ($link_container == "false")) {
											$output .= '<a href="' . $modal_image[0] . '" class="nch-lightbox-media no-ajaxy" data-title="' . $text_title . '" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
										} else if (($trigger == "scroll") && ($link_container == "false")) {
											$output .= '<a href="' . $a_href . '" class="' . $scroll_class . '" target="' . $a_target . '" ' . $a_rel . ' title="" ' . $scroll_data . '>';
										}
											$output .= '<div class="ts-image-svg-button" style="color: ' . $color_button_text . ';"><span style="">' . $text_button . '</span></div>';
										if ($link_container == "false") {
											$output .= '</a>';
										}
									$output .= '</div>';
								$output .= '</figcaption>';
							$output .= '</figure>';
						if ($link_container == "false") {
							$output .= '</div>';
						} else {
							$output .= '</a>';
						}
					$output .= '</div>';
				$output .= '</div>';
			}
		$output .= '</div>';
			
		echo $output;
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>