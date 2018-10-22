<?php
    if (!function_exists('TS_VCSC_IconFontsIntegrateVC')){
		function TS_VCSC_IconFontsIntegrateVC() {
			// Add Custom Icon Font to Visual Composer (if enabled)
			if ((($this->TS_VCSC_PluginExtended == "true") && (get_option('ts_vcsc_extend_settings_fontimport', 1) == 1)) || ($this->TS_VCSC_PluginExtended == "false")) {
				// Icon Element
				$library				= "";
				$param  				= WPBMap::getParam('vc_icon', 'type');
				$library 				= $param['value'];
				if (is_array($library)){
					$library['Custom User Font'] = 'customuserfont';
					$param['value']  	= $library;
					$param['weight'] 	= 2;
					vc_update_shortcode_param('vc_icon', $param);
				}
				vc_add_param('vc_icon', array(
					'type'       		=> 'iconpicker',
					'heading'    		=> __('Icon', 'js_composer'),
					'param_name' 		=> 'icon_customuserfont',
					'settings'   		=> array(
						'emptyIcon'    		=> false,
						'type'         		=> 'customuserfont',
						'iconsPerPage' 		=> $this->TS_VCSC_IconSelectorPager,
					),
					'dependency' 		=> array(
						'element' 			=> 'type',
						'value'   			=> 'customuserfont',
					),
					'description' 		=> __('Select icon from library.', 'js_composer'),
					'weight'      		=> 1,
				));
				// Message Box Element				
				$library				= "";
				$param  				= WPBMap::getParam('vc_message', 'color');
				$param['weight'] 		= 6;
				vc_update_shortcode_param('vc_message', $param);
				$param  				= WPBMap::getParam('vc_message', 'message_box_style');
				$param['weight'] 		= 5;
				vc_update_shortcode_param('vc_message', $param);
				$param  				= WPBMap::getParam('vc_message', 'style');
				$param['weight'] 		= 4;
				vc_update_shortcode_param('vc_message', $param);
				$param  				= WPBMap::getParam('vc_message', 'message_box_color');
				$param['weight'] 		= 3;
				vc_update_shortcode_param('vc_message', $param);
				$param  				= WPBMap::getParam('vc_message', 'icon_type');
				$library 				= $param['value'];
				if (is_array($library)){
					$library['Custom User Font'] = 'customuserfont';
					$param['value']  	= $library;
					$param['weight'] 	= 2;
					vc_update_shortcode_param('vc_message', $param);
				}
				vc_add_param('vc_message', array(
					'type'       		=> 'iconpicker',
					'heading'    		=> __('Icon', 'js_composer'),
					'param_name' 		=> 'icon_customuserfont',
					'settings'   		=> array(
						'emptyIcon'    		=> false,
						'type'         		=> 'customuserfont',
						'iconsPerPage' 		=> $this->TS_VCSC_IconSelectorPager,
					),
					'dependency' 		=> array(
						'element' 			=> 'icon_type',
						'value'   			=> 'customuserfont',
					),
					'description' 		=> __('Select icon from library.', 'js_composer'),
					'weight'      		=> 1,
				));				
			}
		}
    }
    if (!function_exists('TS_VCSC_IconFontsAddFilter')){
		function TS_VCSC_IconFontsAddFilter(){			
			return $this->TS_VCSC_Icons_Compliant_Custom["Custom User Font"];
		}
    }
?>