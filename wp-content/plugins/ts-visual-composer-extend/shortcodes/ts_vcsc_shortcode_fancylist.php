<?php
	add_shortcode('TS_VCSC_Fancy_List', 'TS_VCSC_Fancy_List_Function');
	function TS_VCSC_Fancy_List_Function ($atts, $content = null) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();

		extract( shortcode_atts( array(
			'list_type'					=> 'standard',
			'list_marker'				=> 'disc',
			'list_order'				=> 'decimal',
			'list_position'				=> 'outside',
			'line_height'				=> 18,
			'marker_spacing'			=> 10,
			'marker_image'				=> '',
			'marker_icon'				=> '',
			'marker_side'				=> 'left',
			'marker_position'			=> 'center',			
			'order_start1'				=> 0,
			'order_start2'				=> 1,
			'marker_color'				=> '#000000',
			'marker_size'				=> 12,			
			'content_wpautop'			=> 'false',
			'content_intend'			=> 0,
			'content_margin'			=> 5,
			'content_align'				=> 'left',
			'content_color'				=> '#000000',
			'content_size'				=> 14,
			'content_family'			=> 'Default:regular',
			'content_font'				=> 'default',			
			'frame_type'				=> '',
			'frame_position'			=> 'bottom',
			'frame_padding'				=> 5,
			'frame_thick'				=> 1,
			'frame_color'				=> '#cccccc',
			'conditionals'				=> '',
			'margin_top'                => 0,
			'margin_bottom'             => 0,
			'el_id' 					=> '',
			'el_class'                  => '',
			'css'						=> '',
		), $atts ));
		
		// Check Conditional Output
		$render_conditionals			= (empty($conditionals) ? true : TS_VCSC_CheckConditionalOutput($conditionals));
		if (!$render_conditionals) {
			$myvariable 				= ob_get_clean();
			return $myvariable;
		}
		
		wp_enqueue_style('ts-visual-composer-extend-front');
		
		$output 						= '';
		$styling						= '';
		$wpautop 						= ($content_wpautop == "true" ? true : false);
		$inline							= TS_VCSC_FrontendAppendCustomRules('style');
		
		if (!empty($el_id)) {
			$list_id					= $el_id;
		} else {
			$list_id					= 'ts-fancy-list-' . mt_rand(999999, 9999999);
		}
		
		if (($list_type == "icon") || ($list_type == "image")) {
			$list_marker				= 'none';
		}
		if ($list_type == "image") {
			$list_image					= "list-style: none !important; background-image: url('" . $marker_image . "'); background-repeat: no-repeat; background-size: " . $marker_size . "px " . $marker_size . "px;";
			if ($marker_side == "left") {
				$list_image				.= ' background-position: left 0px ' . $marker_position . '; padding-left: ' . ($marker_size + $marker_spacing) . 'px;';
			} else if ($marker_side == "right") {
				$list_image				.= ' background-position: right 0px ' . $marker_position . '; padding-right: ' . ($marker_size + $marker_spacing) . 'px;';
			}
		} else {
			$list_image					= "";
		}
		if ($frame_type != '') {
			$list_border				= 'border-' . $frame_position . ': ' . $frame_thick . 'px ' . $frame_type . ' ' . $frame_color . '; padding-' . $frame_position . ': ' . $frame_padding . 'px;';
		} else {
			$list_border				= "";
		}
		
		// Visual Composer Class Override
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 					= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Fancy_List', $atts);
		} else {
			$css_class					= '';
		}		
		
		if (function_exists('wpb_js_remove_wpautop')){
			$list_content				= (wpb_js_remove_wpautop(do_shortcode($content), $wpautop));
		} else {
			$list_content				= do_shortcode($content);
		}
		
		// Remove Empty Paragraphs
		$list_content 					= TS_VCSC_RemoveEmptyParagraphs($list_content);
		$list_content 					= str_ireplace('<p></p>', '', $list_content);		
		// Convert Ordered Lists to Unordered Lists
		$list_content 					= str_ireplace('<ol>', '<ul>', $list_content);
		$list_content 					= str_ireplace('</ol>', '</ul>', $list_content);
		// Remove Empty List Items
		$list_content 					= str_ireplace('<li></li>', '', $list_content);		
		// Store All Attributes From List
		$list_style 					= TS_VCSC_GetStringBetween($list_content, '<ul', '>');
		// Remove All Attributes From List
		$list_content 					= str_ireplace($list_style, '', $list_content);		
		// Extract Styles Attribute from Attrbiutes
		$list_style 					= TS_VCSC_GetStringBetween($list_style, 'style="', '"');
		// Remove Opening + Closing UL Tags
		$list_content					= preg_replace('/<ul>/i', '', $list_content, -1);
		$list_content 					= str_ireplace('</ul>', '', $list_content);
		// Convert All Opening LI Tags To DIV
		$list_array 					= str_ireplace('<li', '<div', $list_content);
		// Convert List To Array
		$list_array 					= explode('</li>', $list_array);
		// Check Array For Rouqe UL Tags + P Tags + Empty Strings
		foreach ($list_array as $key => $value){
			if ((trim($value) == '<ul>') || (trim($value) == '</ul>') || (trim($value) == '<p>') || (trim($value) == '</p>') || (trim($value) == '')) {
				unset($list_array[$key]);
			}
		}
		$list_length					= count($list_array);
		$list_counter					= 0;

		// Create Inline CSS Style
		if ($inline == "false") {
			$styling .= '<style id="' . $list_id . '-styling" type="text/css">';
		}
			$styling .= '#' . $list_id . ' .ts-fancy-list-wrapper {';
				$styling .= 'margin: 0; list-style-type: ' . ($list_type == "ordered" ? $list_order : $list_marker) . '; list-style-position: ' . $list_position . '; color: ' . $marker_color . '; font-size: ' . $marker_size . 'px; line-height: ' . $line_height . 'px; ' . $list_style;
				$styling .= TS_VCSC_GetFontFamily($list_id, $content_family, $content_font, false, true, false);
			$styling .= '}';
			if ($list_type == "icon") {
				$styling .= '#' . $list_id . ' .ts-fancy-list-wrapper li {';
					$styling .= 'list-style: none !important; border: none; margin: ' . $content_margin . 'px 0; line-height: ' . $line_height . 'px; font-family: inherit;';
					if (($frame_type != '') && ($frame_position == "right")) {
						$styling .= $list_border;
					}
				$styling .= '}';
				$styling .= '#' . $list_id . ' .ts-fancy-list-wrapper li i {';
					$styling .= 'color: ' . $marker_color . '; font-size: ' . $marker_size . 'px; text-align: ' . $marker_side . ';';
					if ($marker_side == "left") {
						$styling .= 'padding-right: ' . $marker_spacing . 'px;';
					} else if ($marker_side == "right") {
						$styling .= 'padding-left: ' . $marker_spacing . 'px;';
					}
				$styling .= '}';
				$styling .= '#' . $list_id . ' .ts-fancy-list-wrapper li div {';
					$styling .= 'color: ' . $content_color . '; font-size: ' . $content_size . 'px; line-height: ' . $line_height . 'px; text-align: ' . $content_align . '; font-family: inherit;';
					if (($frame_type != '') && ($frame_position == "left")) {
						$styling .= $list_border;
					}
				$styling .= '}';
			} else {
				$styling .= '#' . $list_id . ' .ts-fancy-list-wrapper li {';
					$styling .= 'border: none; margin: ' . $content_margin . 'px 0; line-height: ' . $line_height . 'px; font-family: inherit; ' . $list_image;
					if ($list_type == "ordered") {
						$styling .= 'list-style-type: ' . ($list_type == "ordered" ? $list_order : $list_marker) . '; list-style-position: ' . $list_position . ';';
					}
					if (($frame_type != '') && ($frame_position == "right")) {
						$styling .= $list_border;
					}
				$styling .= '}';
				$styling .= '#' . $list_id . ' .ts-fancy-list-wrapper li div {';
					$styling .= 'color: ' . $content_color . '; font-size: ' . $content_size . 'px; line-height: ' . $line_height . 'px; text-align: ' . $content_align . '; font-family: inherit;';
					if (($frame_type != '') && ($frame_position == "left")) {
						$styling .= $list_border;
					}
				$styling .= '}';
		}
		if ($inline == "false") {
			$styling .= '</style>';
		}
		if (($styling != "") && ($inline == "true")) {
			wp_add_inline_style('ts-visual-composer-extend-custom', TS_VCSC_MinifyCSS($styling));
		}
		
		// Create List Output
		if ($inline == "false") {
			$output .= TS_VCSC_MinifyCSS($styling);
		}
		$output .= '<div id="' . $list_id . '" class="ts-fancy-list-container ' . $el_class . ' ' . $css_class . '" style="margin-left: ' . $content_intend . 'px; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
			if ($list_type != 'ordered') {
				$output .= '<ul class="ts-fancy-list-wrapper ts-fancy-list-unordered ts-fancy-list-type-' . $list_type . ' ts-fancy-list-type-x-' . $marker_side . ' ts-fancy-list-type-y-' . $marker_position . '">';
			} else {
				$output .= '<ol class="ts-fancy-list-wrapper ts-fancy-list-ordered ts-fancy-list-type-' . $list_type . ' ts-fancy-list-type-x-' . $marker_side . ' ts-fancy-list-type-y-' . $marker_position . '" start="' . ((($list_order == 'decimal') || ($list_order == 'decimal-leading-zero')) ? $order_start1 : $order_start2) . '">';
			}
				foreach ($list_array as $key => $value){
					if (substr(trim($value), 0, 4) === "<div") {
						$list_counter++;
						if ($list_type == "icon") {
							$output .= '<li data-count="' . $list_counter . '" style="' . (((($frame_position == "bottom")&& ($list_counter < $list_length)) || (($frame_position == "top")&& ($list_counter > 1))) ? $list_border : "") . '">';
								if ($marker_side == "left") {
									$output .= '<i class="' . $marker_icon . '"></i>' . $value . '</div>';
								} else if ($marker_side == "right") {
									$output .= '' . $value . '</div><i class="' . $marker_icon . '"></i>';
								} else {
									$output .= '' . $value . '</div>';
								}
							$output .= '</li>';
						} else {
							$output .= '<li data-count="' . $list_counter . '" style="' . (((($frame_position == "bottom")&& ($list_counter < $list_length)) || (($frame_position == "top")&& ($list_counter > 1))) ? $list_border : "") . '">' . $value . '</div></li>';
						}
					}
				}
			if ($list_type != 'ordered') {
				$output .= '</ul>';
			} else {
				$output .= '</ol>';
			}
		$output .= '</div>';
		
		echo $output;
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>