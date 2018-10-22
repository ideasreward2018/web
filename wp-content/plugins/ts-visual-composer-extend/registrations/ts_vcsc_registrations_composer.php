<?php
	add_filter('vc_after_init',						'TS_VCSC_IconFontsAddCustomElements');
	add_filter('vc_iconpicker-type-composium',		'TS_VCSC_IconFontsAddCustomComposer');

	function TS_VCSC_IconFontsAddCustomComposer() {
		global $VISUAL_COMPOSER_EXTENSIONS;
		if ((class_exists('WPBMap')) && ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconSelectorComposer == "true")) {
			return $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Icons_Compliant_Custom["Custom User Font"];
		} else {
			return array();
		}
	}
	
	function TS_VCSC_IconFontsAddCustomElements() {
		global $VISUAL_COMPOSER_EXTENSIONS;
		if ((class_exists('WPBMap')) && (($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconSelectorComposer == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Loading == "true") || (!is_admin()))) {
			// Add to "Icon" Element (vc_icon)
			$param 									= WPBMap::getParam('vc_icon', 'type');
			$param['value'][__( 'Custom User Font', 'ts_visual_composer_extend')] = 'composium';
			vc_update_shortcode_param('vc_icon', $param);
			vc_add_param('vc_icon', array(
					'type' 							=> 'iconpicker',
					'heading' 						=> esc_html__('Icon', 'ts_visual_composer_extend'),
					'param_name' 					=> 'icon_composium',
					'settings' 						=> array(
						'emptyIcon' 				=> true,
						'type' 						=> 'composium',
						'iconsPerPage' 				=> 200,
					),
					'dependency' 					=> array(
						'element' 					=> 'type',
						'value' 					=> 'composium',
					),
				)
			);
			// Add to "Button" Element (vc_btn)
			$param 									= WPBMap::getParam('vc_btn', 'i_type');
			$param['value'][__( 'Custom User Font', 'ts_visual_composer_extend')] = 'composium';
			vc_update_shortcode_param('vc_btn', $param);
			vc_add_param('vc_btn', array(
					'type' 							=> 'iconpicker',
					'heading' 						=> esc_html__('Icon', 'ts_visual_composer_extend'),
					'param_name' 					=> 'i_icon_composium',
					'settings' 						=> array(
						'emptyIcon' 				=> true,
						'type' 						=> 'composium',
						'iconsPerPage' 				=> 200,
					),
					'dependency' 					=> array(
						'element' 					=> 'i_type',
						'value' 					=> 'composium',
					),
				)
			);
			// Add to "Call To Action" Element (vc_cta)
			
			
			// Add to "Tabs / Accordion / Tour" Element (vc_tta_section)

			// Add to "Message Box" Element (vc_message)
			
			// Add to "Separator With Text" Element (vc_text_separator)
		}
	}
?>