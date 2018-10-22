<?php
	add_shortcode('TS_VCSC_MP3_Player', 'TS_VCSC_MP3_Player_Function');
	function TS_VCSC_MP3_Player_Function ($atts, $content = null) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();
		
		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "false") {
			wp_enqueue_script('ts-visual-composer-extend-front');			
			wp_enqueue_script('ts-extend-mp3player');
		}
		wp_enqueue_style('ts-extend-mp3player');
		
		extract( shortcode_atts( array(
			// Playlist Settings
			'mp3_playlist'					=> '',
			// Player Settings
			'player_preload'				=> 'metadata',		// metadata, auto, none
			'player_mode'					=> 'circulation', 	// random, single, circulation (loop), order (no loop)
			'player_auto'					=> 'false',
			'player_start'					=> 1,
			'player_volume'					=> 90,
			'player_mutex'					=> 'true',
			'player_narrow'					=> 'false',
			'player_height'					=> 513,
			'player_showlist'				=> 'true',
			'player_showlrc'				=> 'true',
			'player_theme'					=> '#b7daff',
			'player_loading'				=> 'Loading ...',
			// Tooltip Settings
			'tooltip_usage'					=> 'false',
			'tooltip_content'				=> '',
			'tooltip_position'				=> 'ts-simptip-position-top',
			'tooltip_style'					=> 'ts-simptip-style-black',
			'tooltip_animation'				=> 'swing',
			'tooltip_arrow'					=> 'true',
			'tooltip_background'			=> '#000000',
			'tooltip_border'				=> '#000000',
			'tooltip_color'					=> '#ffffff',
			'tooltip_offsetx'				=> 0,
			'tooltip_offsety'				=> 0,
			// Other Settings
			'margin_top'					=> 0,
			'margin_bottom'					=> 0,
			'el_id'							=> '',
			'el_class'						=> '',
			'css'							=> '',
		), $atts ));
		
		$output 							= '';
		$randomizer							= mt_rand(999999, 9999999);
	
		// Create Player ID
		if (!empty($el_id)) {
			$player_id						= $el_id;
		} else {
			$player_id						= 'ts-vcsc-mp3player-' . $randomizer;
		}

		// Prepare Playlist Group Values
		if (isset($mp3_playlist) && strlen($mp3_playlist) > 0 ) {			
			$mp3_data 						= json_decode(urldecode($mp3_playlist), true);
			if (!is_array($mp3_data)) {
				$temp 						= explode(',', $mp3_playlist);
				$paramValues 				= array();
				foreach ($temp as $value) {
					$data 					= explode( '|', $value );
					$colorIndex 			= 2;
					$newLine 				= array();
					$newLine['audio_mp3_title'] 		= isset($data[0]) ? $data[0] : '';
					$newLine['audio_mp3_author'] 		= isset($data[1]) ? $data[1] : '';
					$newLine['audio_mp3_source'] 		= isset($data[2]) ? $data[2] : '';
					$newLine['audio_mp3_local'] 		= isset($data[3]) ? $data[3] : 'false';
					$newLine['audio_mp3_remote'] 		= isset($data[4]) ? $data[4] : '';
					$newLine['audio_mp3_image'] 		= isset($data[5]) ? $data[5] : '';
					$newLine['audio_mp3_lyrics'] 		= isset($data[6]) ? $data[6] : '';
					$paramValues[] 			= $newLine;
				}
				$mp3_playlist 				= urlencode(json_encode($paramValues));
				$mp3_data 					= json_decode(urldecode($mp3_playlist), true);
			}
		}
		
		// Process Playlist Group Values
		$mp3_collection						= array();
		$mp3_counter						= 0;
		foreach ((array) $mp3_data as $key => $entry) {
			// Set Audio Track File
			if ((isset($entry['audio_mp3_title'])) && ($entry['audio_mp3_title'] != '')) {
				$entry['title']				= $entry['audio_mp3_title'];
			} else {
				$entry['title']				= '';
			}
			unset($entry['audio_mp3_title']);
			// Set Audio Track Author
			if ((isset($entry['audio_mp3_author'])) && ($entry['audio_mp3_author'] != '')) {
				$entry['author']			= $entry['audio_mp3_author'];
			} else {
				$entry['author']			= '';
			}
			unset($entry['audio_mp3_author']);
			// Get Path to Audio Track File
			if (isset($entry['audio_mp3_source'])) {
				if (($entry['audio_mp3_source'] == "true") && (isset($entry['audio_mp3_local'])) && ($entry['audio_mp3_local'] != '')) {
					$track_path				= wp_get_attachment_url($entry['audio_mp3_local']);
					$entry['url']			= $track_path;
				} else if (($entry['audio_mp3_source'] == "false") && (isset($entry['audio_mp3_remote'])) && ($entry['audio_mp3_remote'] != '')) {					
					$entry['url']			= $entry['audio_mp3_remote'];
				} else {					
					$entry['url']			= '';
				}				
			}
			unset($entry['audio_mp3_source']);
			unset($entry['audio_mp3_local']);
			unset($entry['audio_mp3_remote']);
			// Get Path to Audio Track Image
			if ((isset($entry['audio_mp3_image'])) && ($entry['audio_mp3_image'] != '')) {
				$track_image				= wp_get_attachment_image_src($entry['audio_mp3_image'], 'thumbnail');
				$entry['pic']				= $track_image[0];
			} else {				
				$entry['pic']				= '';
			}
			unset($entry['audio_mp3_image']);
			// Set Audio Track Lyrics
			if ((isset($entry['audio_mp3_lyrics'])) && ($entry['audio_mp3_lyrics'] != '')) {
				$entry['lrc']				= $entry['audio_mp3_lyrics'];
			} else {
				$entry['lrc']				= '';
			}
			unset($entry['audio_mp3_lyrics']);
			array_push($mp3_collection, $entry);
			$mp3_counter++;
		}
		
		// Encode Playlist Group Values
		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "false") {
			$mp3_collection					= base64_encode(json_encode($mp3_collection));
		}
		
		// Tooltip Setup
		if (($tooltip_usage == "true") && (strip_tags($tooltip_content) != '') && ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "false")) {
			$tooltip_position				= TS_VCSC_TooltipMigratePosition($tooltip_position);
			$tooltip_style					= TS_VCSC_TooltipMigrateStyle($tooltip_style);
			wp_enqueue_style('ts-extend-tooltipster');
			wp_enqueue_script('ts-extend-tooltipster');	
			$Tooltip_Content				= 'data-tooltipster-html="true" data-tooltipster-title="" data-tooltipster-text="' . strip_tags($tooltip_content) . '" data-tooltipster-image="" data-tooltipster-position="' . $tooltip_position . '" data-tooltipster-touch="false" data-tooltipster-arrow="' . $tooltip_arrow . '" data-tooltipster-theme="' . $tooltip_style . '" data-tooltipster-animation="' . $tooltip_animation . '" data-tooltipster-trigger="hover" data-tooltipster-background="' . $tooltip_background . '" data-tooltipster-border="' . $tooltip_border . '" data-tooltipster-color="' . $tooltip_color . '" data-tooltipster-offsetx="' . $tooltip_offsetx . '" data-tooltipster-offsety="' . $tooltip_offsety . '"';
			$Tooltip_Class					= 'ts-has-tooltipster-tooltip';
		} else {
			$Tooltip_Content				= '';
			$Tooltip_Class					= '';
		}
		
		// Player Data Attributes
		$mp3_attributes						= 'data-narrow="' . $player_narrow . '" data-loading="' . $player_loading . '" data-autoplay="' . $player_auto . '" data-volume="' . $player_volume . '" data-mutex="' . $player_mutex . '" data-mode="' . $player_mode . '" data-preload="' . $player_preload . '"';
		$mp3_attributes						.= ' data-theme="' . $player_theme . '" data-showlrc="' . $player_showlrc . '" data-liststart="' . ($player_start - 1) . '" data-listmaxheight="' . $player_height . 'px" data-trackcount="' . $mp3_counter . '" data-playlist="' . $mp3_collection . '" data-showlist="' . $player_showlist . '"';
		
		// Visual Composer Style Override
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 						= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_MP3_Player', $atts);
		} else {
			$css_class						= '';
		}

		// Create Final Output
		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "false") {
			$output .= '<div id="' . $player_id . '-container" class="ts-mp3-player-container ' . $Tooltip_Class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;" ' . $Tooltip_Content . '>';
				$output .= '<div id="' . $player_id . '" class="ts-mp3-player-element aplayer ' . $el_class . '" ' . $mp3_attributes . '></div>';
			$output .= '</div> ';
		} else {
			$mp3_preload = array_flip(array(
				__( "Metadata Only", "ts_visual_composer_extend" )							=> "metadata",
				__( "Automatic", "ts_visual_composer_extend" )								=> "auto",
				__( "None", "ts_visual_composer_extend" )                 					=> "none",
			));
			$mp3_playmode = array_flip(array(
				__( "Circulation (Loop)", "ts_visual_composer_extend" )						=> "circulation",
				__( "Order (No Loop)", "ts_visual_composer_extend" )						=> "order",
				__( "Single Track", "ts_visual_composer_extend" )							=> "single",
				__( "Random Track", "ts_visual_composer_extend" )							=> "random",
			));
			$mp3_autoplay = array_flip(array(
				__( "No Auto-Play", "ts_visual_composer_extend" )							=> "false",
				__( "Play once initialized", "ts_visual_composer_extend" )					=> "init",
				__( "Play ONLY in Browser View (Repeat)", "ts_visual_composer_extend" )		=> "inviewall",
				__( "Play ONLY in Browser View (Once)", "ts_visual_composer_extend" )		=> "inviewsingle",
				__( "Play once in Browser View (Repeat)", "ts_visual_composer_extend" )		=> "viewportall",
				__( "Play once in Browser View (Once)", "ts_visual_composer_extend" )		=> "viewportsingle",
			));
			$output .= '<div id="' . $player_id . '-frontend" class="ts-mp3-player-frontend">';
				$output .= '<span>' . __( "Player: Preload Method", "ts_visual_composer_extend" ) . ' - ' . $mp3_preload[$player_preload] . '</span>';
				$output .= '<span>' . __( "Player: Play Mode", "ts_visual_composer_extend" ) . ' - ' . $mp3_playmode[$player_mode] . '</span>';
				$output .= '<span>' . __( "Player: Auto-Play", "ts_visual_composer_extend" ) . ' - ' . $mp3_autoplay[$player_auto] . '</span>';
				$output .= '<span style="font-weight: bold;">' . __( 'Audio Track Listing:', "ts_visual_composer_extend" ) . '</span>';
				if (empty($mp3_collection)) {
					$output .= '<span>' . __( 'No audio tracks have been added to the player yet.', "ts_visual_composer_extend" ) . '</span>';
				} else {
					$mp3_counter			= 0;
					foreach ((array) $mp3_collection as $key => $entry) {
						$mp3_counter++;
						$output .= '<span>#' . $mp3_counter . ': ' . $entry['author'] . ' - ' . $entry['title'] . '</span>';
					}
				}
			$output .= '</div> ';
		}
		
		echo $output;
	
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>