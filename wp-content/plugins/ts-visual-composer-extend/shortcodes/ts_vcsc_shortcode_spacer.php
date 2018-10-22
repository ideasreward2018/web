<?php
	add_shortcode('TS-VCSC-Spacer', 'TS_VCSC_Spacer_Function');
	function TS_VCSC_Spacer_Function ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();
	
		extract( shortcode_atts( array(
			'implement'					=> 'always', // always,devices
			'height'					=> 10,
			'height_devices'			=> 'desktop:10px;tablet_portrait:10px;tablet_landscape:10px;mobile_portrait:10px;mobile_landscape:10px;',
			'height_screens'			=> 'extra_large:10px;large:10px;medium:10px;small:10px;extra_small:10px;',
			'screen_invert'				=> 'true',
			'screen_check'				=> 'false',
			'screen_width'				=> 1024,
			'conditionals'				=> '',
			'css'						=> '',
		), $atts ));
		
		// Check Conditional Output
		$render_conditionals			= (empty($conditionals) ? true : TS_VCSC_CheckConditionalOutput($conditionals));
		if (!$render_conditionals) {
			$myvariable 				= ob_get_clean();
			return $myvariable;
		}
		
		// Load Required Files
		wp_enqueue_style('ts-visual-composer-extend-front');
		wp_enqueue_script('ts-visual-composer-extend-front');
		
		$output = $notice = $visible = '';
		
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 					= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS-VCSC-Spacer', $atts);
		} else {
			$css_class					= '';
		}
		
		// Parse Device Settings
		$height_devices 				= explode(';', $height_devices);			
		$heightDesktop					= explode(':', $height_devices[0]);
		$heightDesktop					= str_replace("px", "", $heightDesktop[1]);
		$heightTabletPortrait			= explode(':', $height_devices[1]);
		$heightTabletPortrait			= str_replace("px", "", $heightTabletPortrait[1]);
		$heightTabletLandscape			= explode(':', $height_devices[2]);
		$heightTabletLandscape			= str_replace("px", "", $heightTabletLandscape[1]);		
		$heightMobilePortrait			= explode(':', $height_devices[3]);
		$heightMobilePortrait			= str_replace("px", "", $heightMobilePortrait[1]);
		$heightMobileLandscape			= explode(':', $height_devices[4]);
		$heightMobileLandscape			= str_replace("px", "", $heightMobileLandscape[1]);
		
		// Parse Screen Settings
		$height_screens 				= explode(';', $height_screens);
		$heightExtraLarge				= explode(':', $height_screens[0]);
		$heightExtraLarge				= str_replace("px", "", $heightExtraLarge[1]);
		$heightLarge					= explode(':', $height_screens[1]);
		$heightLarge					= str_replace("px", "", $heightLarge[1]);
		$heightMedium					= explode(':', $height_screens[2]);
		$heightMedium					= str_replace("px", "", $heightMedium[1]);
		$heightSmall					= explode(':', $height_screens[3]);
		$heightSmall					= str_replace("px", "", $heightSmall[1]);
		$heightExtraSmall				= explode(':', $height_screens[4]);
		$heightExtraSmall				= str_replace("px", "", $heightExtraSmall[1]);
		
		// Class Assignment
		if (($implement == 'devices') || ($implement == 'screens') || (($implement == 'always') && ($screen_check == 'true'))) {
			$spacer_class				= 'ts-spacer-advanced';
		} else {
			$spacer_class				= '';
		}
		
		// Data Attributes
		$spacer_data					= 'data-implement="' . $implement . '" data-height-always="' . absint($height) . '" data-screen-check="' . $screen_check . '" data-screen-width="' . $screen_width . '" data-screen-invert="' . $screen_invert . '"';
		$spacer_data					.= ' data-desktop="' . $heightDesktop . '" data-tablet-landscape="' . $heightTabletLandscape . '" data-tablet-portrait="' . $heightTabletPortrait . '" data-mobile-landscape="' . $heightMobileLandscape . '" data-mobile-portrait="' . $heightMobilePortrait . '"';
		$spacer_data					.= ' data-screen-extralarge="' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Screen_Sizes_Custom['ExtraLarge'] . '" data-screen-large="' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Screen_Sizes_Custom['Large'] . '" data-screen-medium="' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Screen_Sizes_Custom['Medium'] . '" data-screen-small="' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Screen_Sizes_Custom['Small'] . '" data-screen-extrasmall="' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Screen_Sizes_Custom['ExtraSmall'] . '"';
		$spacer_data					.= ' data-height-extralarge="' . $heightExtraLarge . '" data-height-large="' . $heightLarge . '" data-height-medium="' . $heightMedium . '" data-height-small="' . $heightSmall . '" data-height-extrasmall="' . $heightExtraSmall . '"';
		
		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {			
			if ($implement == "always") {
				$notice 	.= '<span style="text-align: center; color: #D10000; margin: 0 ; padding: 0; font-weight: bold; vertical-align: middle; line-height: ' . $height . 'px;">TS Spacer / Clear (' . $height . 'px)</span>';
				$visible 	.= 'text-align: center; min-height: 30px; height: ' . absint($height) . 'px; visibility: visible; border-top: 1px solid #ededed; border-bottom: 1px solid #ededed; padding: 5px 0;';
			} else if ($implement == "devices") {
				$height_desktop			= explode(";", $height_devices);
				$height_desktop			= $height_desktop[0];
				$height_desktop			= preg_replace('/\D/', '', $height_desktop);
				$height_devices			= implode(";", $height_devices);
				$height_devices			= rtrim($height_devices, ";");
				$height_devices			= str_replace(";", " / ", $height_devices);
				$height_devices			= str_replace(":", ": ", $height_devices);
				$height_devices			= str_replace("_", " ", $height_devices);
				$height_devices			= ucwords($height_devices);
				$notice 	.= '<span style="text-align: center; color: #D10000; margin: 0 ; padding: 0; font-weight: bold; vertical-align: middle; line-height: ' . $height_desktop . 'px;">TS Spacer / Clear (' . $height_devices . ')</span>';
				$visible 	.= 'text-align: center; min-height: 30px; height: ' . absint($height_desktop) . 'px; visibility: visible; border-top: 1px solid #ededed; border-bottom: 1px solid #ededed; padding: 5px 0;';
			}			
		} else {
			$visible 	.= 'text-align: center; line-height: ' . absint($height) . 'px; height: ' . absint($height) . 'px;';
		}

		$output = '<div class="ts-spacer ' . $spacer_class . ' clearboth ' . $css_class . '" ' . $spacer_data . ' style="' . $visible . '">' . $notice . '</div>';
		
		echo $output;
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>