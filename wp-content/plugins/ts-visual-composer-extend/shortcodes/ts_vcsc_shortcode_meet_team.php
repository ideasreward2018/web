<?php
	add_shortcode('TS-VCSC-Meet-Team', 'TS_VCSC_Meet_Team_Function');
	function TS_VCSC_Meet_Team_Function ($atts, $content = null) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();

		wp_enqueue_style('ts-extend-tooltipster');
		wp_enqueue_script('ts-extend-tooltipster');
		wp_enqueue_style('ts-extend-animations');
		wp_enqueue_style('ts-font-teammates');
		wp_enqueue_style('ts-visual-composer-extend-front');
		wp_enqueue_script('ts-visual-composer-extend-front');
		
		extract( shortcode_atts( array(
			'style'					=> 'style1',
			'image'					=> '',
			'name'					=> '',
			'title'					=> '',
			'description'			=> '',
			'icon_style' 			=> 'simple',
			'icon_background'		=> '#f5f5f5',
			'icon_frame_color'		=> '#f5f5f5',
			'icon_frame_thick'		=> 1,
			'icon_margin' 			=> 5,
			'icon_align'			=> 'left',
			'icon_hover'			=> '',
			'phone'					=> '',
			'cell'					=> '',
			'portfolio'				=> '',
			'link'					=> '',
			'email'					=> '',
			'facebook'				=> '',
			'gplus'					=> '',
			'twitter'				=> '',
			'linkedin'				=> '',
			'xing'					=> '',
			'skype'					=> '',
			'flickr'				=> '',
			'picasa'				=> '',
			'instagram'				=> '',
			'vimeo'					=> '',
			'youtube'				=> '',
			'tooltip_style'			=> 'ts-simptip-style-black',
			'tooltip_position'		=> 'ts-simptip-position-top',
			'tooltip_animation'		=> 'swing',
			'tooltip_arrow'			=> 'true',
			'tooltip_background'	=> '#000000',
			'tooltip_border'		=> '#000000',
			'tooltip_color'			=> '#ffffff',
			'tooltip_offsetx'		=> 0,
			'tooltip_offsety'		=> 0,
			'animation_view'		=> '',
			'margin_top'			=> 0,
			'margin_bottom'			=> 0,
			'el_id' 				=> '',
			'el_class'              => '',
			'css'					=> '',
		), $atts ));
	
		if (!empty($el_id)) {
			$team_id						= $el_id;
		} else {
			$team_id						= 'ts-vcsc-meet-team-' . mt_rand(999999, 9999999);
		}
		
		$team_image = wp_get_attachment_image_src($image, 'large');
	
		if ($animation_view != '') {
			$animation_css              	= TS_VCSC_GetCSSAnimation($animation_view);
		} else {
			$animation_css					= '';
		}
		
		$output 							= '';
		
		$tooltip_position					= TS_VCSC_TooltipMigratePosition($tooltip_position);
		$tooltip_style						= TS_VCSC_TooltipMigrateStyle($tooltip_style);
		$tooltip_classes					= "ts-has-tooltipster-tooltip";
		$tooltip_content					= 'data-tooltipster-html="false" data-tooltipster-title="" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="' . $tooltip_arrow . '" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="' . $tooltip_animation . '" data-tooltipster-trigger="hover" data-tooltipster-background="' . $tooltip_background . '" data-tooltipster-border="' . $tooltip_border . '" data-tooltipster-color="' . $tooltip_color . '" data-tooltipster-offsetx="' . $tooltip_offsetx . '" data-tooltipster-offsety="' . $tooltip_offsety . '"';
	
		if ((empty($icon_background)) || ($icon_style == 'simple')) {
			$icon_frame_style				= '';
		} else {
			$icon_frame_style				= 'background: ' . $icon_background . ';';
		}
		
		if ($icon_frame_thick > 0) {
			$icon_top_adjust				= 'top: ' . (10 - $icon_frame_thick) . 'px;';
		} else {
			$icon_top_adjust				= '';
		}
		
		if ($icon_style == 'simple') {
			$icon_frame_border				= '';
		} else {
			$icon_frame_border				= ' border: ' . $icon_frame_thick . 'px solid ' . $icon_frame_color . ';';
		}
		
		$icon_horizontal_adjust				= '';
	
		$team_social 						= '';
	
		$team_social .= '<ul class="ts-teammate-icons ' . $icon_style . '">';
			if (strlen($email) != 0) {
				$team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $tooltip_classes . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" ' . $tooltip_content . ' data-tooltipster-text="Email"><a style="" target="_blank" class="ts-teammate-link email ' . $icon_hover . '" href="mailto:' . $email . '"><i class="ts-teamicon-email2 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
			}
			if (strlen($phone) != 0) {
				$team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $tooltip_classes . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" ' . $tooltip_content . ' data-tooltipster-text="' . $phone . '"><a style="" target="_blank" class="ts-teammate-link phone ' . $icon_hover . '" href="#"><i class="ts-teamicon-phone2 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
			}
			if (strlen($cell) != 0) {
				$team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $tooltip_classes . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" ' . $tooltip_content . ' data-tooltipster-text="' . $cell . '"><a style="" target="_blank" class="ts-teammate-link mobile ' . $icon_hover . '" href="#"><i class="ts-teamicon-mobile ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
			}
			if (strlen($skype) != 0) {
				$team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $tooltip_classes . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" ' . $tooltip_content . ' data-tooltipster-text="' . $skype . '"><a style="" target="_blank" class="ts-teammate-link skype ' . $icon_hover . '" href="#"><i class="ts-teamicon-skype ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
			}
			if (strlen($portfolio) != 0) {
				$team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $tooltip_classes . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" ' . $tooltip_content . ' data-tooltipster-text="Portfolio"><a style="" target="_blank" class="ts-teammate-link portfolio ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($portfolio) . '"><i class="ts-teamicon-portfolio ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
			}
			if (strlen($link) != 0) {
				$team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $tooltip_classes . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" ' . $tooltip_content . ' data-tooltipster-text="Other"><a style="" target="_blank" class="ts-teammate-link portfolio ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($link) . '"><i class="ts-teamicon-link ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
			}
			if (strlen($facebook) != 0) {
				$team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $tooltip_classes . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" ' . $tooltip_content . ' data-tooltipster-text="Facebook"><a style="" target="_blank" class="ts-teammate-link facebook ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($facebook) . '"><i class="ts-teamicon-facebook1 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
			}
			if (strlen($gplus) != 0) {
				$team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $tooltip_classes . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" ' . $tooltip_content . ' data-tooltipster-text="Google+"><a style="" target="_blank" class="ts-teammate-link gplus ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($gplus) . '"><i class="ts-teamicon-googleplus1 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
			}
			if (strlen($twitter) != 0) {
				$team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $tooltip_classes . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" ' . $tooltip_content . ' data-tooltipster-text="Twitter"><a style="" target="_blank" class="ts-teammate-link twitter ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($twitter) . '"><i class="ts-teamicon-twitter1 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
			}
			if (strlen($linkedin) != 0) {
				$team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $tooltip_classes . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" ' . $tooltip_content . ' data-tooltipster-text="LinkedIn"><a style="" target="_blank" class="ts-teammate-link linkedin ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($linkedin) . '"><i class="ts-teamicon-linkedin ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
			}
			if (strlen($xing) != 0) {
				$team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $tooltip_classes . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" ' . $tooltip_content . ' data-tooltipster-text="Xing"><a style="" target="_blank" class="ts-teammate-link xing ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($xing) . '"><i class="ts-teamicon-xing3 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
			}
			if (strlen($flickr) != 0) {
				$team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $tooltip_classes . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" ' . $tooltip_content . ' data-tooltipster-text="Flickr"><a style="" target="_blank" class="ts-teammate-link flickr ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($flickr) . '"><i class="ts-teamicon-flickr3 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
			}
			if (strlen($instagram) != 0) {
				$team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $tooltip_classes . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" ' . $tooltip_content . ' data-tooltipster-text="Instagram"><a style="" target="_blank" class="ts-teammate-link instagram ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($instagram) . '"><i class="ts-teamicon-instagram ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
			}
			if (strlen($picasa) != 0) {
				$team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $tooltip_classes . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" ' . $tooltip_content . ' data-tooltipster-text="Picasa"><a style="" target="_blank" class="ts-teammate-link picasa ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($picasa) . '"><i class="ts-teamicon-picasa1 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
			}
			if (strlen($vimeo) != 0) {
				$team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $tooltip_classes . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" ' . $tooltip_content . ' data-tooltipster-text="Vimeo"><a style="" target="_blank" class="ts-teammate-link vimeo ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($vimeo) . '"><i class="ts-teamicon-vimeo1 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
			}
			if (strlen($youtube) != 0) {
				$team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $tooltip_classes . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" ' . $tooltip_content . ' data-tooltipster-text="YouTube"><a style="" target="_blank" class="ts-teammate-link youtube ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($youtube) . '"><i class="ts-teamicon-youtube1 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
			}
		$team_social .= '</ul>';
		
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS-VCSC-Meet-Team', $atts);
		} else {
			$css_class						= '';
		}
		
		if ($style == "style1") {
			$output .= '<div id="' . $team_id . '" class="ts-team1 ts-team-member ' . $animation_css . ' ' . $el_class . ' ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
				$output .= '<div class="team-avatar">';
					$output .= '<img src="' . $team_image[0] . '" alt="">';
				$output .= '</div>';
				$output .= '<div class="team-user">';
					$output .= '<h4 class="team-title">' . $name . '</h4>';
					$output .= '<div class="team-job">' . $title . '</div>';
				$output .= '</div>';
				$output .= '<div class="team-information">';
					$output .= '' . $description . '';
				$output .= '<br></div>';
				$output .= $team_social;
			$output .= '</div>';
		} else if ($style == "style2") {
			$output .= '<article id="' . $team_id . '" class="ts-team2 ts-team-member ' . $animation_css . ' ' . $el_class . ' ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
				$output .= '<div class="ts-team2-header">';
					$output .= '<img src="' . $team_image[0] . '" class="img-circle">';
				$output .= '</div>';
				$output .= '<div class="ts-team2-content">';
					$output .= '<div class="ts-team2-line"></div>';
					$output .= '<h3>' . $name . '</h3>';
					$output .= '<p class="ts-team2-lead">' . $title . '</p>';
					$output .= '<p>' . $description . '</p>';
				$output .= '</div>';
				$output .= '<div class="ts-team2-footer">';
					$output .= $team_social;
				$output .= '</div>';
			$output .= '</article>';
		} else if ($style == "style3") {
			$output .= '<div id="' . $team_id . '" class="ts-team3 ts-team-member ' . $animation_css . ' ' . $el_class . ' ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
				$output .= '<img class="ts-team3-person-image" src="' . $team_image[0] . '" alt="' . $name . '">';
				$output .= '<p class="ts-team3-person-name">' . $name . '</p>';
				$output .= '<p class="ts-team3-person-position">' . $title . '</p>';
				$output .= '<p class="ts-team3-person-description">' . $description . '</p>';
				//$output .= '<div class="ts-team3-person-line"></div>';
				$output .= $team_social;
				$output .= '<div class="ts-team3-person-space"></div>';					
			$output .= '</div>';
		}
		
		echo $output;
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>