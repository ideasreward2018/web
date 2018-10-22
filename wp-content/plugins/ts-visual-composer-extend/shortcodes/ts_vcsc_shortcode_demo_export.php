<?php
	add_shortcode('TS_VCSC_Content_Export', 'TS_VCSC_Content_Export');
	function TS_VCSC_Content_Export ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();
		
		extract(shortcode_atts(array(
			'target'					=> 'current',
			'pageid' 					=> '',
			'postid'					=> '',
			'clipboard'					=> 'false',
			'toggle_switch'				=> 'false',
			'toggle_background'			=> '#2ac4ea',
			'toggle_color'				=> '#f6f6f6',
			'string_copy'				=> 'Copy',
			'string_toggle'				=> 'Export Page Content',
			'string_success'			=> 'The content for this page or post has been copied to your clipboard!',
			'string_error'				=> 'The content for this page or post could NOT be copied to your clipboard!',
			'margin_top'				=> 0,
			'margin_bottom'				=> 0,
		), $atts));
		
		// Load Required JS/CSS Files
		wp_enqueue_style('ts-visual-composer-extend-demos');
		if (($clipboard == "true") || ($toggle_switch == "true")) {
			if ($clipboard == "true") {
				wp_enqueue_script('ts-extend-clipboard');
			}
			wp_enqueue_script('ts-visual-composer-extend-demos');
		}
		
		// Declare Variables
		$randomizer						= mt_rand(999999, 9999999);
		$output							= '';
		$content						= '';
		$finalid						= '';
		
		// Determine Page/Post ID
		if ($target == "current") {
			$finalid					= get_the_ID();
		} else if ($target == "page") {
			$finalid					= $pageid;
		} else if ($target == "post") {
			$finalid					= $postid;
		}
		
		// Retrieve Page/Post Content
		if ($finalid != "") {
			$content 					= get_post_field('post_content', $finalid);
			$content 					= str_replace(']]>', ']]&gt;', $content);
		}

		// Create Final Output
		$output .= '<div id="ts-content-export-wrapper-' . $randomizer . '" class="ts-content-export-wrapper" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
			if ($toggle_switch == "true") {
				$output .= '<div id="ts-content-export-toggle-' . $randomizer . '" class="ts-content-export-toggle ts-content-export-main">';
					$output .= '<div id="ts-content-export-title-' . $randomizer . '" class="ts-content-export-title ts-content-export-hide" style="background: ' . $toggle_background . '; color: ' . $toggle_color . ';">';
						$output .= '<span class="ts-content-export-toggle-hide fa fa-plus" style="display: inline-block;"></span>';
						$output .= '<span class="ts-content-export-toggle-show fa fa-minus" style="display: none;"></span>';
						$output .= '<span class="ts-content-export-toggle-string">' . $string_toggle . '</span>';
					$output .= '</div>';
					$output .= '<div id="ts-content-export-content-' . $randomizer . '" class="ts-content-export-content slideFade" style="display: none;">';
			}
						$output .= '<div id="ts-content-export-holder-' . $randomizer . '" class="ts-content-export-holder">';
							$output .= '<textarea id="ts-content-export-textarea-' . $randomizer . '" class="ts-content-export-textarea">' . $content . '</textarea>';
							if ($clipboard == "true") {
								$output .= '<div id="ts-content-export-clipboard-' . $randomizer . '" class="ts-content-export-clipboard" data-clipboard-target="#ts-content-export-textarea-' . $randomizer . '" data-success="ts-content-export-success-' . $randomizer . '" data-error="ts-content-export-error-' . $randomizer . '">';
									$output .= '<span id="ts-content-export-icon-' . $randomizer . '" class="ts-content-export-icon fa fa-clipboard"></span>';
									$output .= '<span id="ts-content-export-text-' . $randomizer . '" class="ts-content-export-text">' . $string_copy . '</span>';
								$output .= '</div>';
							}			
							$output .= '<div id="ts-content-export-success-' . $randomizer . '" class="ts-content-export-success" style="display: none;"><span class="fa fa-check"></span>' . $string_success . '</div>';
							$output .= '<div id="ts-content-export-error-' . $randomizer . '" class="ts-content-export-error" style="display: none;"><span class="fa fa-exclamation-triangle"></span>' . $string_error . '</div>';
						$output .= '</div>';
			if ($toggle_switch == "true") {
					$output .= '</div>';
				$output .= '</div>';
			}
		$output .= '</div>';
		
		echo $output;
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>