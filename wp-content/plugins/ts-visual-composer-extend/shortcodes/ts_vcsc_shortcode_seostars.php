<?php
	add_shortcode('TS_VCSC_Star_Rating', 'TS_VCSC_Star_Rating_Function');
	function TS_VCSC_Star_Rating_Function ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();

		extract( shortcode_atts( array(
			'rating_shortcode'			=> 'false',
			'rating_maximum'			=> 5,
			'rating_value'				=> 0,
			'rating_dynamic'			=> '',
			'rating_size'				=> 25,
			'rating_auto'				=> 'true',
			'rating_title'				=> '',
			'rating_position'			=> 'top',
			'rating_rtl'				=> 'false',
			'rating_symbol'				=> 'other',
			'rating_icon'				=> '',
			'color_rated'				=> '#FFD800',
			'color_empty'				=> '#e3e3e3',
			// Rating Settings
			'caption_show'				=> 'true',
			'caption_position'			=> 'left',
			'caption_digits'			=> '.',
			'caption_danger'			=> '#d9534f', // worst
			'caption_warning'			=> '#f0ad4e', // bad
			'caption_info'				=> '#5bc0de', // average
			'caption_primary'			=> '#428bca', // good
			'caption_success'			=> '#5cb85c', // best
			// Tooltip Settings
			'tooltip_css'				=> 'false',
			'tooltip_content'			=> '',
			'tooltip_position'			=> 'ts-simptip-position-top',
			'tooltip_style'				=> 'ts-simptip-style-black',
			'tooltip_animation'			=> 'swing',
			'tooltipster_offsetx'		=> 0,
			'tooltipster_offsety'		=> 0,
			// Other Settings
			'margin_top'				=> 20,
			'margin_bottom'				=> 20,
			'el_id'						=> '',
			'el_class'					=> '',
			'css'						=> '',
		), $atts ));
		
		if ($tooltip_css == "true") {
			wp_enqueue_style('ts-extend-tooltipster');
			wp_enqueue_script('ts-extend-tooltipster');
		}
		wp_enqueue_style('ts-font-ecommerce');
		wp_enqueue_style('ts-extend-ratingscale');
		wp_enqueue_style('ts-visual-composer-extend-front');
		wp_enqueue_script('ts-visual-composer-extend-front');
		
		// Rating as Shortcode
		if ($rating_shortcode == "true") {
			// Check for Encoding
			$rating_return				= strip_tags($rating_dynamic);
			$rating_encoded				= false;
            if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', (strip_tags($rating_return)))) {
				$rating_encoded			= false;
			} else {
				$rating_base64 			= base64_decode($rating_return, true);
				if (false === $rating_base64) {
					$rating_encoded		= false;
				} else if (base64_encode($rating_base64) != $rating_return) {
					$rating_encoded		= false;
				} else {
					$rating_encoded		= true;
				}
			}
			if ($rating_encoded) {
				$rating_value			= rawurldecode(base64_decode($rating_return));
			} else {
				$rating_value			= rawurldecode($rating_return);
			}
			$rating_value				= do_shortcode($rating_value);
			$rating_value				= number_format((float)$rating_value, 2, $caption_digits, '');
		} else {
			$rating_value				= number_format($rating_value, 2, $caption_digits, '');
		}
		
		// Contingency Check
		if ($rating_value > $rating_maximum) {
			$rating_value				= number_format($rating_maximum, 2, $caption_digits, '');;
		}
		
		if ($rating_rtl == "false") {
			$rating_width				= $rating_value / $rating_maximum * 100;
		} else {
			$rating_width				= 100 - ($rating_value / $rating_maximum * 100);
		}
		
		$rating_scale					= 'ts-rating-stars-max' . $rating_maximum;
		
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
			}
		} else {
			$rating_class				= 'ts-rating-stars-smile';
		}

		// Label Classes
		$caption_class					= '';
		$caption_background				= '';
		if ($rating_maximum == 3) {
			if (($rating_value >= 0) && ($rating_value <= 0.50)) {
				$caption_class			= 'ts-label-danger';
				$caption_background		= 'background-color: ' . $caption_danger . ';';		// worst
			} else if (($rating_value > 0.50) && ($rating_value <= 1)) {
				$caption_class			= 'ts-label-warning';
				$caption_background		= 'background-color: ' . $caption_warning . ';';	// bad
			} else if (($rating_value > 1) && ($rating_value <= 2)) {
				$caption_class			= 'ts-label-info';
				$caption_background		= 'background-color: ' . $caption_info . ';';		// average
			} else if (($rating_value > 2) && ($rating_value <= 2.50)) {
				$caption_class			= 'ts-label-primary';
				$caption_background		= 'background-color: ' . $caption_primary . ';';	// good
			} else if (($rating_value > 2.50) && ($rating_value <= 3)) {
				$caption_class			= 'ts-label-success';
				$caption_background		= 'background-color: ' . $caption_success . ';';	// best
			}
		} else if ($rating_maximum == 4) {
			if (($rating_value >= 0) && ($rating_value <= 0.75)) {
				$caption_class			= 'ts-label-danger';
				$caption_background		= 'background-color: ' . $caption_danger . ';';		// worst
			} else if (($rating_value > 0.75) && ($rating_value <= 1.50)) {
				$caption_class			= 'ts-label-warning';
				$caption_background		= 'background-color: ' . $caption_warning . ';';	// bad
			} else if (($rating_value > 1.50) && ($rating_value <= 2.50)) {
				$caption_class			= 'ts-label-info';
				$caption_background		= 'background-color: ' . $caption_info . ';';		// average
			} else if (($rating_value > 2.50) && ($rating_value <= 3.25)) {
				$caption_class			= 'ts-label-primary';
				$caption_background		= 'background-color: ' . $caption_primary . ';';	// good
			} else if (($rating_value > 3.25) && ($rating_value <= 4)) {
				$caption_class			= 'ts-label-success';
				$caption_background		= 'background-color: ' . $caption_success . ';';	// best
			}
		} else if ($rating_maximum == 5) {
			if (($rating_value >= 0) && ($rating_value <= 1)) {
				$caption_class			= 'ts-label-danger';
				$caption_background		= 'background-color: ' . $caption_danger . ';';		// worst
			} else if (($rating_value > 1) && ($rating_value <= 2)) {
				$caption_class			= 'ts-label-warning';
				$caption_background		= 'background-color: ' . $caption_warning . ';';	// bad
			} else if (($rating_value > 2) && ($rating_value <= 3)) {
				$caption_class			= 'ts-label-info';
				$caption_background		= 'background-color: ' . $caption_info . ';';		// average
			} else if (($rating_value > 3) && ($rating_value <= 4)) {
				$caption_class			= 'ts-label-primary';
				$caption_background		= 'background-color: ' . $caption_primary . ';';	// good
			} else if (($rating_value > 4) && ($rating_value <= 5)) {
				$caption_class			= 'ts-label-success';
				$caption_background		= 'background-color: ' . $caption_success . ';';	// best
			}
		} else if ($rating_maximum == 6) {
			if (($rating_value >= 0) && ($rating_value <= 1)) {
				$caption_class			= 'ts-label-danger';
				$caption_background		= 'background-color: ' . $caption_danger . ';';		// worst
			} else if (($rating_value > 1) && ($rating_value <= 2)) {
				$caption_class			= 'ts-label-warning';
				$caption_background		= 'background-color: ' . $caption_warning . ';';	// bad
			} else if (($rating_value > 2) && ($rating_value <= 4)) {
				$caption_class			= 'ts-label-info';
				$caption_background		= 'background-color: ' . $caption_info . ';';		// average
			} else if (($rating_value > 4) && ($rating_value <= 5)) {
				$caption_class			= 'ts-label-primary';
				$caption_background		= 'background-color: ' . $caption_primary . ';';	// good
			} else if (($rating_value > 5) && ($rating_value <= 6)) {
				$caption_class			= 'ts-label-success';
				$caption_background		= 'background-color: ' . $caption_success . ';';	// best
			}
		} else if ($rating_maximum == 7) {
			if (($rating_value >= 0) && ($rating_value <= 1.25)) {
				$caption_class			= 'ts-label-danger';
				$caption_background		= 'background-color: ' . $caption_danger . ';';		// worst
			} else if (($rating_value > 1.25) && ($rating_value <= 2.50)) {
				$caption_class			= 'ts-label-warning';
				$caption_background		= 'background-color: ' . $caption_warning . ';';	// bad
			} else if (($rating_value > 2.50) && ($rating_value <= 4.50)) {
				$caption_class			= 'ts-label-info';
				$caption_background		= 'background-color: ' . $caption_info . ';';		// average
			} else if (($rating_value > 4.50) && ($rating_value <= 5.75)) {
				$caption_class			= 'ts-label-primary';
				$caption_background		= 'background-color: ' . $caption_primary . ';';	// good
			} else if (($rating_value > 5.75) && ($rating_value <= 7)) {
				$caption_class			= 'ts-label-success';
				$caption_background		= 'background-color: ' . $caption_success . ';';	// best
			}
		} else if ($rating_maximum == 8) {
			if (($rating_value >= 0) && ($rating_value <= 1.50)) {
				$caption_class			= 'ts-label-danger';
				$caption_background		= 'background-color: ' . $caption_danger . ';';		// worst
			} else if (($rating_value > 1.50) && ($rating_value <= 3)) {
				$caption_class			= 'ts-label-warning';
				$caption_background		= 'background-color: ' . $caption_warning . ';';	// bad
			} else if (($rating_value > 3) && ($rating_value <= 5)) {
				$caption_class			= 'ts-label-info';
				$caption_background		= 'background-color: ' . $caption_info . ';';		// average
			} else if (($rating_value > 5) && ($rating_value <= 6.50)) {
				$caption_class			= 'ts-label-primary';
				$caption_background		= 'background-color: ' . $caption_primary . ';';	// good
			} else if (($rating_value > 6.50) && ($rating_value <= 8)) {
				$caption_class			= 'ts-label-success';
				$caption_background		= 'background-color: ' . $caption_success . ';';	// best
			}
		} else if ($rating_maximum == 9) {
			if (($rating_value >= 0) && ($rating_value <= 1.75)) {
				$caption_class			= 'ts-label-danger';
				$caption_background		= 'background-color: ' . $caption_danger . ';';		// worst
			} else if (($rating_value > 1.75) && ($rating_value <= 3.50)) {
				$caption_class			= 'ts-label-warning';
				$caption_background		= 'background-color: ' . $caption_warning . ';';	// bad
			} else if (($rating_value > 3.50) && ($rating_value <= 5.25)) {
				$caption_class			= 'ts-label-info';
				$caption_background		= 'background-color: ' . $caption_info . ';';		// average
			} else if (($rating_value > 5.25) && ($rating_value <= 7.25)) {
				$caption_class			= 'ts-label-primary';
				$caption_background		= 'background-color: ' . $caption_primary . ';';	// good
			} else if (($rating_value > 7.25) && ($rating_value <= 9)) {
				$caption_class			= 'ts-label-success';
				$caption_background		= 'background-color: ' . $caption_success . ';';	// best
			}
		} else if ($rating_maximum == 10) {
			if (($rating_value >= 0) && ($rating_value <= 2)) {
				$caption_class			= 'ts-label-danger';
				$caption_background		= 'background-color: ' . $caption_danger . ';';		// worst
			} else if (($rating_value > 2) && ($rating_value <= 4)) {
				$caption_class			= 'ts-label-warning';
				$caption_background		= 'background-color: ' . $caption_warning . ';';	// bad
			} else if (($rating_value > 4) && ($rating_value <= 6)) {
				$caption_class			= 'ts-label-info';
				$caption_background		= 'background-color: ' . $caption_info . ';';		// average
			} else if (($rating_value > 6) && ($rating_value <= 8)) {
				$caption_class			= 'ts-label-primary';
				$caption_background		= 'background-color: ' . $caption_primary . ';';	// good
			} else if (($rating_value > 8) && ($rating_value <= 10)) {
				$caption_class			= 'ts-label-success';
				$caption_background		= 'background-color: ' . $caption_success . ';';	// best
			}
		}

		// Tooltip
		if ($tooltip_css == "true") {
			$tooltip_position			= TS_VCSC_TooltipMigratePosition($tooltip_position);
			$tooltip_style				= TS_VCSC_TooltipMigrateStyle($tooltip_style);			
			if (strlen($tooltip_content) != 0) {
				$rating_tooltipclasses	= "ts-has-tooltipster-tooltip";
				$rating_tooltipcontent	= 'data-tooltipster-title="" data-tooltipster-text="' . $tooltip_content . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="true" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="' . $tooltip_animation . '" data-tooltipster-trigger="hover" data-tooltipster-offsetx="' . $tooltipster_offsetx . '" data-tooltipster-offsety="' . $tooltipster_offsety . '"';
			} else {
				$rating_tooltipclasses	= "";
				$rating_tooltipcontent	= "";
			}
		} else {
			$rating_tooltipclasses		= "";
			if (strlen($tooltip_content) != 0) {
				$rating_tooltipcontent	= ' title="' . $tooltip_content . '"';
			} else {
				$rating_tooltipcontent	= "";
			}
		}
		
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 					= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Star_Rating', $atts);
		} else {
			$css_class					= '';
		}
		
		$output 						= '';
	
		$output .= '<div class="ts-rating-stars-frame ' . $el_class . ' ' . $css_class . '" data-auto="' . $rating_auto . '" data-size="' . $rating_size . '" data-width="' . ($rating_size * 5) . '" data-rating="' . $rating_value . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
			if (($rating_position == 'top') && ($rating_title != '')) {
				$output .= '<div class="ts-rating-title ts-rating-title-top">' . $rating_title . '</div>';
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
					$output .= '<div class="ts-rating-container' . ($rating_rtl == "false" ? "" : "-rtl") . ' ts-rating-glyph-holder ' . $rating_class . ' ' . $rating_scale . '" style="color: ' . ($rating_rtl == "false" ? $color_empty : $color_rated) . ';">';
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
				$output .= '<div class="ts-rating-title ts-rating-title-bottom">' . $rating_title . '</div>';
			}
		$output .= '</div>';

		echo $output;
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>