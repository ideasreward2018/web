<?php
	// Function for Conditional Element Output
	// ---------------------------------------
	if (!function_exists('TS_VCSC_CheckConditionalOutput')){
		function TS_VCSC_CheckConditionalOutput($conditionals) {
			global $VISUAL_COMPOSER_EXTENSIONS;
			// Holder Variable for Empty Checks
			$emptyholder						= "";
			// Always Render in Frontend Editor
			if (($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") || (empty($conditionals))) {
				return true;
			}
			// Convert Conditional Data
			if (!empty($conditionals)) {
				$conditionals					= json_decode(base64_decode($conditionals), true);
			}
			if (!TS_VCSC_CheckIsAssociateArray($conditionals)) {
				$conditionals					= array(
					'viewerstatus'				=> 'everybody',
					'restriction'				=> 'none',						
					'userroles'					=> '',
					'userscope'					=> 'any',
					'usercaps'					=> '',
					'otherscope'				=> 'any',
					'othertags'					=> '',
					'devicetypes'				=> '',
				);
			}
			// Contingency Checks
			if (!isset($conditionals['viewerstatus'])) {
				$conditionals['viewerstatus']	= 'everybody';
			}
			if (!isset($conditionals['restriction'])) {
				$conditionals['restriction']	= 'none';
			}
			if (!isset($conditionals['userroles'])) {
				$conditionals['userroles']		= '';
			}
			if (!isset($conditionals['userscope'])) {
				$conditionals['userscope']		= 'any';
			}
			if (!isset($conditionals['usercaps'])) {
				$conditionals['usercaps']		= '';
			}
			if (!isset($conditionals['otherscope'])) {
				$conditionals['otherscope']		= 'any';
			}
			if (!isset($conditionals['othertags'])) {
				$conditionals['othertags']		= '';
			}
			if (!isset($conditionals['devicetypes'])) {
				$conditionals['devicetypes']	= '';
			}
			// Convert Strings to Arrays
			$emptyholder						= trim($conditionals['userroles']);
			if (!empty($emptyholder)) {
				$conditionals['userroles']		= explode(',', $emptyholder);
			} else {
				$conditionals['userroles']		= array();
			}
			$emptyholder						= trim($conditionals['usercaps']);
			if (!empty($emptyholder)) {
				$conditionals['usercaps']		= explode(',', $emptyholder);
			} else {
				$conditionals['usercaps']		= array();
			}
			$emptyholder						= trim($conditionals['othertags']);
			if (!empty($emptyholder)) {
				$conditionals['othertags']		= explode(',', $emptyholder);
			} else {
				$conditionals['othertags']		= array();
			}
			$emptyholder						= trim($conditionals['devicetypes']);
			if (!empty($emptyholder)) {
				$conditionals['devicetypes']	= explode(',', $emptyholder);
			} else {
				$conditionals['devicetypes']	= array();
			}
			// Define Pass Variables
			$pass_isloggedin					= false;
			$pass_userroles						= false;
			$pass_usercaps						= false;
			$pass_otherany						= array();
			$pass_otherall						= array();
			$pass_devicetypes					= array();
			$pass_aggregated					= array();
			// Get Current User Data
			if (($conditionals['viewerstatus'] == "loggedin") && ($conditionals['restriction'] != "none")) {
				$user_current 					= wp_get_current_user();
				$user_roles						= (array) $user_current->roles;
				$user_rights					= (array) $user_current->allcaps;
				$user_caps						= array();
			}
			// Get Device Information
			if (count($conditionals['devicetypes']) == 0) {
				array_push($pass_aggregated, true);
			} else {
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_MobileDetectorInit();
				if (in_array('desktops', $conditionals['devicetypes'], true)) {
					array_push($pass_devicetypes, ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_MobileDetector_Desktop == "true" ? true : false));
				}
				if (in_array('mobiles', $conditionals['devicetypes'], true)) {
					array_push($pass_devicetypes, ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_MobileDetector_Mobile == "true" ? true : false));
				} else {
					if (in_array('tablets', $conditionals['devicetypes'], true)) {
						array_push($pass_devicetypes, ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_MobileDetector_Tablet == "true" ? true : false));
					}
					if (in_array('phones', $conditionals['devicetypes'], true)) {
						array_push($pass_devicetypes, ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_MobileDetector_Phone == "true" ? true : false));
					}
				}
				if (array_sum($pass_devicetypes) > 0) {
					array_push($pass_aggregated, true);
				} else {
					array_push($pass_aggregated, false);
				}
			}
			// Check Viewer Status
			if ($conditionals['viewerstatus'] == "everybody") {
				array_push($pass_aggregated, true);
			} else if ($conditionals['viewerstatus'] == "loggedin") {
				array_push($pass_aggregated, is_user_logged_in());
			} else if ($conditionals['viewerstatus'] == "external") {
				array_push($pass_aggregated, !is_user_logged_in());
			}
			// Check User Roles or Capabilities
			if ($conditionals['viewerstatus'] == "loggedin") {
				if ($conditionals['restriction'] == "userroles") {
					if (count(array_intersect($conditionals['userroles'], $user_roles)) > 0) {
						$pass_userroles			= true;
					}
					array_push($pass_aggregated, $pass_userroles);
				} else if ($conditionals['restriction'] == "userrights") {
					foreach($user_rights as $key => $value) {
						if ((is_null($value)) || ($value === false) || ($value === 'false')) {
							unset($user_rights[$key]);
						} else {
							array_push($user_caps, $key);
						}
					}
					if (count($conditionals['usercaps']) == 0) {
						array_push($pass_aggregated, true);
					} else if ($conditionals['userscope'] == "any") {
						if (count(array_intersect($conditionals['usercaps'], $user_caps)) > 0) {
							$pass_usercaps		= true;
						}
						array_push($pass_aggregated, $pass_usercaps);
					} else if ($conditionals['userscope'] == "all") {
						if (count(array_diff($conditionals['usercaps'], $user_caps)) == 0) {
							$pass_usercaps		= true;
						}
						array_push($pass_aggregated, $pass_usercaps);
					}
				}
			}
			// Check Other Conditional Tags
			if (count($conditionals['othertags']) == 0) {
				array_push($pass_aggregated, true);
			} else {
				if ($conditionals['otherscope'] == "any") {
					foreach($conditionals['othertags'] as $function){
						if ((function_exists($function)) && (call_user_func($function))) {
							array_push($pass_otherany, true);
						} else {
							array_push($pass_otherany, false);
						}					
					}
					if (array_sum($pass_otherany) > 0) {
						array_push($pass_aggregated, true);
					} else {
						array_push($pass_aggregated, false);
					}
				} else if ($conditionals['otherscope'] == "all") {
					foreach($conditionals['othertags'] as $function){
						if ((function_exists($function)) && (call_user_func($function))) {
							array_push($pass_otherall, true);
						} else {
							array_push($pass_otherall, false);
						}
					}
					if (array_sum($pass_otherall) == count($pass_otherall)) {
						array_push($pass_aggregated, true);
					} else {
						array_push($pass_aggregated, false);
					}
				}
			}
			// Unset Variables
			unset($pass_otherany);
			unset($pass_otherall);
			unset($pass_devicetypes);
			if (($conditionals['viewerstatus'] == "loggedin") && ($conditionals['restriction'] != "none")) {
				unset($user_current);
				unset($user_roles);
				unset($user_rights);
				unset($user_caps);
			}
			unset($emptyholder);
			unset($conditionals);
			// Return Aggregated Results
			if(array_sum($pass_aggregated) == count($pass_aggregated)) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	// Function to Create Custom Image Sizes
	// -------------------------------------
	if (!function_exists('TS_VCSC_CreateCustomImageSize')){
		function TS_VCSC_CreateCustomImageSize($id, $width, $height) {
			if (function_exists('wpb_getImageBySize')) {
				$return						= array();
				$image 						= wpb_getImageBySize(array('attach_id' => $id, 'thumb_size' => $width . 'x' . $height, 'class' => ''));
				$thumbnail					= $image['thumbnail'];
				preg_match('/(src=["\'](.*?)["\'])/', $thumbnail, $match);
				$thumbnail 					= preg_split('/["\']/', $match[0]);
				$filename 					= basename($thumbnail[1]);
				$filename 					= substr($filename, 0, strrpos($filename, "."));				
				if (strpos($filename, $width . 'x' . $height) !== false) {
					$filewidth				= $width;
					$fileheight				= $height;
				} else {
					$filewidth				= $image['p_img_large'][1];
					$fileheight				= $image['p_img_large'][2];
				}
				unset($image);
				array_push($return, $thumbnail[1]);
				array_push($return, $filewidth);
				array_push($return, $fileheight);
				array_push($return, true);
				return $return;
			} else {
				return wp_get_attachment_image_src($id, array($width,$height));
			}
		}
	}
	
	// Function to Retrieve Image MetaData
	// -----------------------------------
	if (!function_exists('TS_VCSC_GetImageMetaData')){
		function TS_VCSC_GetImageMetaData($img_id) {
			$image_array					= array();
			if ($img_id != "") {
				$image_data 				= get_post($img_id);
				$image_array['alt']			= get_post_meta($img_id, '_wp_attachment_image_alt', true);
				$image_array['caption']		= (isset($image_data->post_excerpt) ? $image_data->post_excerpt : '');
				$image_array['content']		= (isset($image_data->post_content) ? $image_data->post_content : '');
				$image_array['title']		= (isset($image_data->post_title) ? $image_data->post_title : '');
				$image_array['type']		= (isset($image_data->post_mime_type) ? $image_data->post_mime_type : '');
			}
			return $image_array;
		}
	}	
	
    // Function to retrieve Attachment ID from Link
    // --------------------------------------------
    if (!function_exists('TS_VCSC_GetAttachmentIDFromLink')){
        function TS_VCSC_GetAttachmentIDFromLink ($image_src) {
	    global $wpdb;
	    $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
	    $id = $wpdb->get_var($query);
	    return $id;
        }
    }
	
	// Function to Migrate Tooltip Settings
	// ------------------------------------
    if (!function_exists('TS_VCSC_TooltipMigrateStyle')){
        function TS_VCSC_TooltipMigrateStyle ($tooltip_style) {
			if (($tooltip_style == "") || ($tooltip_style == "ts-simptip-style-black") || ($tooltip_style == "tooltipster-black")) {
				$tooltip_style			= "tooltipster-black";
			} else if (($tooltip_style == "ts-simptip-style-gray") || ($tooltip_style == "tooltipster-gray")) {
				$tooltip_style			= "tooltipster-gray";
			} else if (($tooltip_style == "ts-simptip-style-green") || ($tooltip_style == "tooltipster-green")) {
				$tooltip_style			= "tooltipster-green";
			} else if (($tooltip_style == "ts-simptip-style-blue") || ($tooltip_style == "tooltipster-blue")) {
				$tooltip_style			= "tooltipster-blue";
			} else if (($tooltip_style == "ts-simptip-style-red") || ($tooltip_style == "tooltipster-red")) {
				$tooltip_style			= "tooltipster-red";
			} else if (($tooltip_style == "ts-simptip-style-orange") || ($tooltip_style == "tooltipster-orange")) {
				$tooltip_style			= "tooltipster-orange";
			} else if (($tooltip_style == "ts-simptip-style-yellow") || ($tooltip_style == "tooltipster-yellow")) {
				$tooltip_style			= "tooltipster-yellow";
			} else if (($tooltip_style == "ts-simptip-style-purple") || ($tooltip_style == "tooltipster-purple")) {
				$tooltip_style			= "tooltipster-purple";
			} else if (($tooltip_style == "ts-simptip-style-pink") || ($tooltip_style == "tooltipster-pink")) {
				$tooltip_style			= "tooltipster-pink";
			} else if (($tooltip_style == "ts-simptip-style-white") || ($tooltip_style == "tooltipster-white")) {
				$tooltip_style			= "tooltipster-white";
			} else if (($tooltip_style == "ts-simptip-style-custom") || ($tooltip_style == "tooltipster-custom")) {
				$tooltip_style			= "tooltipster-custom";
			}
			return $tooltip_style;
        }
    }
	if (!function_exists('TS_VCSC_TooltipMigratePosition')){
        function TS_VCSC_TooltipMigratePosition ($tooltip_position) {
			if (($tooltip_position == "ts-simptip-position-top") || ($tooltip_position == "top")) {
				$tooltip_position		= "top";
			} else if (($tooltip_position == "ts-simptip-position-left") || ($tooltip_position == "left")) {
				$tooltip_position		= "left";
			} else if (($tooltip_position == "ts-simptip-position-right") || ($tooltip_position == "right")) {
				$tooltip_position		= "right";
			} else if (($tooltip_position == "ts-simptip-position-bottom") || ($tooltip_position == "bottom")) {
				$tooltip_position		= "bottom";
			}
			return $tooltip_position;
        }
    }
    
    // Function to extract String in Between Strings
    // ---------------------------------------------
    if (!function_exists('TS_VCSC_GetStringBetween')){
        function TS_VCSC_GetStringBetween ($string, $start, $finish) {
            $string = " " . $string;
            $position = strpos($string, $start);
            if ($position == 0) return "";
            $position += strlen($start);
            $length = strpos($string, $finish, $position) - $position;
            return substr($string, $position, $length);
        }
    }
	if (!function_exists('TS_VCSC_GetContentsBetween')){
		function TS_VCSC_GetContentsBetween($str, $startDelimiter, $endDelimiter) {
			$contents = array();
			$startDelimiterLength = strlen($startDelimiter);
			$endDelimiterLength = strlen($endDelimiter);
			$startFrom = $contentStart = $contentEnd = 0;
			while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
			  $contentStart += $startDelimiterLength;
			  $contentEnd = strpos($str, $endDelimiter, $contentStart);
			  if (false === $contentEnd) {
				break;
			  }
			  $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
			  $startFrom = $contentEnd + $endDelimiterLength;
			}  
			return $contents;
		}
	}
    
    
    // Functions to retrieve Video Thumbnails
    // --------------------------------------
    if (!function_exists('TS_VCSC_VideoImage_Youtube')){
        function TS_VCSC_VideoImage_Youtube($url, $quality){
            // Get Image from Video URL
            $urls 		= parse_url($url);
            $imgPath 	= '';
            if ((isset($urls['host'])) && ($urls['host'] == 'youtu.be')) {
                //Expect the URL to be http://youtu.be/abcd, where abcd is the video ID
                $imgPath = ltrim($urls['path'],'/');
            } else if ((isset($urls['path'])) && (strpos($urls['path'], 'embed') == 1)) {
                // Expect the URL to be http://www.youtube.com/embed/abcd
				$imgPath = explode('/', $urls['path']);
                $imgPath = end($imgPath);
            } else if (strpos($url, '/') === false) { 
                //Expect the URL to be abcd only
                $imgPath = $url;
            } else {
                //Expect the URL to be http://www.youtube.com/watch?v=abcd
                parse_str($urls['query']);
                $imgPath = $v;
            }
			if ($quality == "standard") {
				return "https://img.youtube.com/vi/" . $imgPath . "/sddefault.jpg";
			} else if ($quality == "medium") {
				return "https://img.youtube.com/vi/" . $imgPath . "/mqdefault.jpg";
			} else if ($quality == "high") {
				return "https://img.youtube.com/vi/" . $imgPath . "/hqdefault.jpg";
			} else if ($quality == "max") {
				return "https://img.youtube.com/vi/" . $imgPath . "/maxresdefault.jpg";
			} else {
				return "https://img.youtube.com/vi/" . $imgPath . "/default.jpg";
			}
        }
    }
    if (!function_exists('TS_VCSC_VideoID_Youtube')){
        function TS_VCSC_VideoID_Youtube($url){
            // Get image from video URL
            $urls 		= parse_url($url);
            $imgPath 	= '';
            if ((isset($urls['host'])) && ($urls['host'] == 'youtu.be')) {
                //Expect the URL to be http://youtu.be/abcd, where abcd is the video ID
                $imgPath = ltrim($urls['path'], '/');
            } else if ((isset($urls['path'])) && (strpos($urls['path'], 'embed') == 1)) {
                // Expect the URL to be http://www.youtube.com/embed/abcd
				$imgPath = explode('/', $urls['path']);
                $imgPath = end($imgPath);
            } else if (strpos($url, '/') === false) { 
                //Expect the URL to be abcd only
                $imgPath = $url;
            } else {
                //Expect the URL to be http://www.youtube.com/watch?v=abcd
                parse_str($urls['query']);
                $imgPath = $v;
            }
            return $imgPath;
        }
    }
    if (!function_exists('TS_VCSC_PlaylistID_Youtube')){
        function TS_VCSC_PlaylistID_Youtube($url){
            $urls           = parse_url($url);
            $imgPath 	    = '';
            if (strpos($url, '/') === false) {
                $imgPath    = $url;
            } else {
                parse_str($urls['query'], $q);
                $imgPath    = $q['list'];
            }
            return $imgPath;
        }
    }
    if (!function_exists('TS_VCSC_VideoImage_Vimeo')){
        function TS_VCSC_VideoImage_Vimeo($url){
            $image_url = parse_url($url);
            if ((isset($image_url['host'])) && ($image_url['host'] == 'www.vimeo.com' || $image_url['host'] == 'vimeo.com')) {
                $path = trim(TS_VCSC_RetrieveExternalData("http://vimeo.com/api/v2/video/" . substr($image_url['path'], 1) . ".php"));
                $hash = @unserialize($path);
                if (isset($hash[0]["thumbnail_large"])) {
                    return $hash[0]["thumbnail_large"];
                } else {
                    return '';
                }
            } else {
                return '';
            }
        }
    }
    if (!function_exists('TS_VCSC_VideoID_Vimeo')){
        function TS_VCSC_VideoID_Vimeo($url){
            $image_url = parse_url($url);
            if ((isset($image_url['host'])) && ($image_url['host'] == 'www.vimeo.com' || $image_url['host'] == 'vimeo.com')) {
				if (isset($image_url['path'])) {
					return substr($image_url['path'], 1);
				} else {
					return '';
				}
            } else {
                return '';
            }
        }
    }
    if (!function_exists('TS_VCSC_VideoImage_Motion')){
        function TS_VCSC_VideoImage_Motion($url){
            $image_url 	= parse_url($url);
            if ((isset($image_url['host'])) && ($image_url['host'] == 'www.dailymotion.com' || $image_url['host'] == 'dailymotion.com')) {
                $url	= $image_url['path'];
                $parts 	= explode('/', $url);
                $parts 	= explode('_', $parts[2]);
                return "https://www.dailymotion.com/thumbnail/video/" . $parts[0];
            } else if ((isset($image_url['host'])) && ($image_url['host'] == 'dai.ly')) {
                $imgPath = ltrim($image_url['path'],'/');
                return "https://www.dailymotion.com/thumbnail/video/" . $imgPath;
            } else {
                return '';
            }
        }
    }
    if (!function_exists('TS_VCSC_VideoID_Motion')){
        function TS_VCSC_VideoID_Motion($url){
            $image_url 	= parse_url($url);
            $imgPath 	= '';
            if ((isset($image_url['host'])) && ($image_url['host'] == 'www.dailymotion.com' || $image_url['host'] == 'dailymotion.com')) {
                $url	= $image_url['path'];
                $parts 	= explode('/', $url);
                $parts 	= explode('_', $parts[2]);
                return $parts[0];
            } else if ((isset($image_url['host'])) && ($image_url['host'] == 'dai.ly')) {
                $imgPath = ltrim($image_url['path'],'/');
                return $imgPath;
            } else {
                return $imgPath;
            }
        }
    }
    if (!function_exists('TS_VCSC_VideoImage_Facebook')){
        function TS_VCSC_VideoImage_Facebook($url){
            $image_url 	= parse_url($url);			
            if ((isset($image_url['host'])) && ($image_url['host'] == 'www.facebook.com' || $image_url['host'] == 'facebook.com')) {
                $url	= $image_url['path'];
                $parts 	= explode('/', $url);
                return "https://graph.facebook.com/" . $parts[3] . "/picture";
			} else if (($url != "") && (!preg_match('#[^0-9]#', $url))) {
				return "https://graph.facebook.com/" . $url . "/picture";
            } else {
                return '';
            }
        }
    }
    if (!function_exists('TS_VCSC_VideoID_Facebook')){
        function TS_VCSC_VideoID_Facebook($url){
            $image_url 	= parse_url($url);
            if ((isset($image_url['host'])) && ($image_url['host'] == 'www.facebook.com' || $image_url['host'] == 'facebook.com')) {
                $url	= $image_url['path'];
                $parts 	= explode('/', $url);
                return $parts[3];
            } else {
                return $url;
            }
        }
    }
    
    // Google/Custom Font Manager Functions
    // ------------------------------------
    if (!function_exists('TS_VCSC_GetFontFamily')){
        function TS_VCSC_GetFontFamily($id = '', $font_family, $font_type, $style = true, $return = true, $important = true) {
			global $VISUAL_COMPOSER_EXTENSIONS;
            $url            		= plugin_dir_url( __FILE__ );
            $output         		= '';
			if (!function_exists("my_strstr")) {
				function my_strstr($haystack, $needle, $before_needle = false) {
					if (!$before_needle) {
						return strstr($haystack, $needle);
					} else {
						return substr($haystack, 0, strpos($haystack, $needle));
					}
				}
			}
            if ($font_type == 'google') {
                wp_enqueue_style('ts-font-' . str_replace(':', '-', $font_family), 'https://fonts.googleapis.com/css?family=' . $font_family , null, false, 'all');
                $format_name 		= strpos($font_family, ':');
                if ($format_name !== false) {
                    $google_font 	= my_strstr(str_replace('+', ' ', $font_family), ':', true);
                } else {
                    $google_font 	= str_replace('+', ' ', $font_family);
                }
				if ($style) {
					$output 		.= '<style type="text/css">#' . $id . ' {font-family: "' . $google_font . '"' . ($important ? " !important" : "") . ';}</style>';
				} else {
					$output 		.= 'font-family: ' . $google_font . '' . ($important ? ' !important' : '') . '; ';
				}
			} else if ($font_type == 'custom') {
				$format_font		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_RegisteredCustomFonts;
                $format_name 		= strpos($font_family, ':');
                if ($format_name !== false) {
                    $custom_font 	= my_strstr(str_replace('+', ' ', $font_family), ':', true);
                } else {
                    $custom_font 	= str_replace('+', ' ', $font_family);
                }	
				if (count($format_font) > 0) {
					foreach ($format_font as $fonts => $font) {
						if (base64_decode($font->family) == $custom_font) {
							if (($font->path != "") && ($font->load == "demand")) {
								if ($font->enqueue != "") {
									wp_enqueue_style('ts-font-' . strtolower(base64_decode($font->enqueue)), rawurldecode($font->path), null, false, 'all');
								} else {
									wp_enqueue_style('ts-font-' . strtolower(preg_replace('/[^a-zA-Z0-9]/','', $custom_font)), rawurldecode($font->path), null, false, 'all');
								}								
							}
							break;
						}
					}
				}
				if ($style) {
					$output 		.= '<style type="text/css">#' . $id . ' {font-family: "' . $custom_font . '"' . ($important ? " !important" : "") . ';}</style>';
				} else {
					$output 		.= 'font-family: ' . $custom_font . '' . ($important ? ' !important' : '') . '; ';
				}
            } else if ($font_type == 'fontface') {
                $stylesheet 		= $url . 'assets/fontface/fontface_stylesheet.css';
                $font_dir 			= FONTFACE_URI;
                if (file_exists( $stylesheet)) {
                    $file_content = file_get_contents($stylesheet);
                    if (preg_match("/@font-face\s*{[^}]*?font-family\s*:\s*('|\")$font_family\\1.*?}/is", $file_content, $match)) {
                        $fontface_style = preg_replace("/url\s*\(\s*['|\"]\s*/is", "\\0$font_dir/", $match[0])."\n";
                    }
                    $output 			= '\n<style type="text/css">' . $fontface_style . '\n';
                    $output 			.= '#' . $id . ' {font-family: "'.$font_family.'"' . ($important ? " !important" : "") . ';}</style>';
                }
            } else if ($font_type == 'safefont') {
                $format_name 		= strpos($font_family, ':');
                if ($format_name !== false) {
                    $safe_font 		= my_strstr(str_replace('+', ' ', $font_family), ':', true);
                } else {
                    $safe_font 		= str_replace('+', ' ', $font_family);
                }
				if ($style) {
					$output 		.= '<style type="text/css">#' . $id . ' {font-family: ' . $safe_font . ' ' . ($important ? "!important" : "") . ';}</style>';
				} else {
					$output 		.= "font-family: " . $safe_font . "" . ($important ? " !important" : "") . "; ";
				}
            }
            if ($return) {
                return $output;
            } else {
                return '';
            }
        }
    }
	
	
	// Custom Button Layout Styling
	// ----------------------------
	if (!function_exists('TS_VCSC_GetCustomFlatButtonStyle')){
		function TS_VCSC_GetCustomFlatButtonStyle($identifier, $classes, $output = 'styleall', $position = '', $hover = false, $color_background, $color_shadow, $section = 'container', $color_other) {
			$styling 					= '';
			$subsection					= '';
			if ($classes != '') {
				$classes 				= explode(" ", $classes);
			}
			if (($output == 'stylestart') || ($output == 'styleall')) {
				$styling .= '<style id="ts-custom-style-' . $identifier . '" type="text/css">';
			}
				if (($output == 'stylecss') || ($output == 'styleall')) {
					if ($section == "icon") {
						$subsection 	= '.ts-color-button-icon ';
					} else if ($section == "text") {
						$subsection 	= '.ts-color-button-title ';
					}
					foreach($classes as $value) {
						if ($hover) {
							$styling .= 'body #' . $identifier . ' a.' . $value . $position . ':hover ' . $subsection . '{';
						} else {
							$styling .= 'body #' . $identifier . ' a.' . $value . $position . ' ' . $subsection .'{';
						}
							if ($section == "container") {
								$styling .= 'background: ' . $color_background . ' !important;';
								$styling .= 'border-bottom: 2px solid ' . $color_shadow . '  !important;';
								$styling .= 'box-shadow: inset 0 -2px ' . $color_shadow . '  !important;';
								$styling .= '-webkit-box-shadow: inset 0 -2px ' . $color_shadow . '  !important;';
								$styling .= '-moz-box-shadow: inset 0 -2px ' . $color_shadow . '  !important;';
							}
							if (($section == "icon") || ($section == "text") || ($position != '')) {
								$styling .= 'color: ' . $color_other . ' !important;';
							}
						$styling .= '}';
					}
				}
			if (($output == 'styleend') || ($output == 'styleall')) {
				$styling .= '</style>';
			}
			return $styling;
		}
	}

    
	// Advanced Link Picker
	if (!function_exists('TS_VCSC_Advancedlinks_GetLinkData')){
		function TS_VCSC_Advancedlinks_GetLinkData($link_string) {
			global $VISUAL_COMPOSER_EXTENSIONS;
			$link_content						= (($link_string == '|') || ($link_string == '||') || ($link_string == '|||') || ($link_string == '||||') || ($link_string == '|||||') || ($link_string == '||||||')) ? '' : $link_string;
			$link_content						= TS_VCSC_Advancedlinks_BuildLinkArray($link_content);
			if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_ParameterLinkPicker['enabled'] == "false") {
				if (($link_content['url'] == "") && ($link_content['id'] != "")) {
					$link_content['url']		= get_permalink($link_id);					
				}
			} else {	
				$link_href						= $link_content['url'];
				$link_title						= $link_content['title'];
				$link_target					= $link_content['target'];
				$link_source					= $link_content['source'];
				$link_id						= $link_content['id'];
				$link_name						= $link_content['name'];
				$link_rel						= $link_content['rel'];
				if (($link_source == "page") && ($link_id != '')) {
					$link_content['url']		= get_permalink($link_id);
				} else if (($link_source == "post") && ($link_id != '')) {
					$link_content['url']		= get_permalink($link_id);
				} else if (($link_source == "custom") && ($link_id != '')) {
					$link_content['url']		= get_permalink($link_id);
				}
			}
			if ((preg_match("@^[a-zA-Z0-9%+-_]*$@", $link_content['url'])) || (urlencode($link_content['url']) == str_replace(array('%', '+'), array('%25', '%2B'), $link_content['url']))) {
			//if ((preg_match("@^[a-zA-Z0-9%+-_]*$@", $link_content['url'])) || (urlencode($link_content['url']) == str_replace(['%', '+'], ['%25', '%2B'], $link_content['url']))) {
				$link_content['url']			= urldecode($link_content['url']);
			}
			unset($link_content['source']);
			unset($link_content['id']);
			unset($link_content['name']);
			return $link_content;
		}
	}	
	if (!function_exists('TS_VCSC_Advancedlinks_BuildLinkArray')){
		function TS_VCSC_Advancedlinks_BuildLinkArray($value) {
			global $VISUAL_COMPOSER_EXTENSIONS;
			// Parse string like "title:Hello world|weekday:Monday" to array('title' => 'Hello World', 'weekday' => 'Monday')
			return TS_VCSC_Advancedlinks_ParseMultiAttribute($value, array('url' => '', 'title' => '', 'target' => '', 'rel' => '', 'source' => '', 'id' => '', 'name' => ''));
		}
	}
	if (!function_exists('TS_VCSC_Advancedlinks_ParseMultiAttribute')){
		function TS_VCSC_Advancedlinks_ParseMultiAttribute($value, $default = array()) {
			$result             	= $default;
			$params_pairs       	= explode('|', $value);
			if (!empty($params_pairs)) {
				foreach($params_pairs as $pair) {
					$param      	= preg_split('/\:/', $pair);
					if (!empty($param[0]) && isset($param[1])) {
						$result[$param[0]] = trim(rawurldecode($param[1]));
					}
				}
			}
			return $result;
		}
	}
	
	// Other Utilized Functions
    // ------------------------
	if (!function_exists('TS_VCSC_ConvertLegacyAnimation')){
		function TS_VCSC_ConvertLegacyAnimation($animation) {
			$animation_old  = array(
				"top-to-bottom"					=> "slideInDown",
				"bottom-to-top"					=> "slideInUp",
				"left-to-right"					=> "slideInLeft",
				"right-to-left"					=> "slideInRight",
				"appear"						=> "fadeIn"
			);
			if (array_key_exists($animation, $animation_old)) {
				$animation	    				= $animation_old[$animation];
			} else {
				$animation	    				= $animation;
			};
			return $animation;
		}
	}
	if (!function_exists('TS_VCSC_IsValidBase64Encoded')){
		function TS_VCSC_IsValidBase64Encoded($string){
			$decoded = base64_decode($string, true);
			// Check if there is no invalid character in strin
			if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string)) return false;	
			// Decode the string in strict mode and send the responce
			if(!base64_decode($string, true)) return false;	
			// Encode and compare it to origional one
			if(base64_encode($decoded) != $string) return false;	
			return true;
		}
	}
	if (!function_exists('TS_VCSC_RemoveEmptyParagraphs')){
		function TS_VCSC_RemoveEmptyParagraphs($content) {
			$content 						= force_balance_tags($content);
			$content 						= preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
			$content 						= preg_replace('~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $content);
			return $content;
		}
	}
	if (!function_exists('TS_VCSC_TrueFalseEqualizer')){
		function TS_VCSC_TrueFalseEqualizer($value, $default) {
			if (isset($value)) {
				if (($value === "false") || ($value === false) || ($value === "0") || ($value === 0)) {
					return "false";
				} else {
					return "true";
				}
			} else {
				return $default;
			}
		}
	}
	if (!function_exists('TS_VCSC_ChildElementsActive')){
		function TS_VCSC_ChildElementsActive($array, $key, $val) {
			foreach ($array as $item) {
				if (is_array($item)) {
				   TS_VCSC_ChildElementsActive($item, $key, $val);
				}
				if (isset($item[$key]) && $item[$key] == $val) {
					return $item;
				}
			};
			return false;
		}
	}
	if (!function_exists('TS_VCSC_STRRPOS_String')){
		function TS_VCSC_STRRPOS_String($haystack, $needle, $offset = 0) { 
			if (trim($haystack) != "" && trim($needle) != "" && $offset <= strlen($haystack)) { 
				$last_pos 		= $offset; 
				$found 			= false; 
				while (($curr_pos = strpos($haystack, $needle, $last_pos)) !== false) { 
					$found 		= true; 
					$last_pos 	= $curr_pos + 1; 
				} 
				if ($found) { 
					return $last_pos - 1; 
				} else { 
					return false; 
				} 
			} else { 
				return false; 
			} 
		} 
	}
	if (!function_exists('TS_VCSC_ArrayFindPartial')){
		function TS_VCSC_ArrayFindPartial($needle, array $haystack) {
			foreach ($haystack as $key => $value) {
				if (false !== stripos($value, $needle)) {
					return $key;
				}
			}
			return false;
		}
	}
	if (!function_exists('TS_VCSC_CheckIsAssociateArray')){
		function TS_VCSC_CheckIsAssociateArray($array) {
			if ((empty($array)) || ((sizeof($array) == 0))) {
				return false;
			}
			foreach(array_keys($array) as $key) {
				if (!is_int($key)) return true;
			}
			return false;
		}
	}
    if (!function_exists('TS_VCSC_Color_Average')){
        function TS_VCSC_Color_Average($color1, $color2, $factor) {
            // extract RGB values for color1.
            list($r1, $g1, $b1) = str_split(ltrim($color1, '#'), 2);
            // extract RGB values for color2.
            list($r2, $g2, $b2) = str_split(ltrim($color2, '#'), 2);
            // get the average RGB values.
            $r_avg = (hexdec($r1) * (1-$factor) + hexdec($r2) * $factor);
            $g_avg = (hexdec($g1) * (1-$factor) + hexdec($g2) * $factor);
            $b_avg = (hexdec($b1) * (1-$factor) + hexdec($b2) * $factor);  
            $color_avg = '#' . sprintf("%02s", dechex($r_avg)) . sprintf("%02s", dechex($g_avg)) . sprintf("%02s", dechex($b_avg));
            return $color_avg;
        }
    }
    if (!function_exists('TS_VCSC_CountArrayMatches')){
        function TS_VCSC_CountArrayMatches(array $arr, $arg, $filterValue) {
            $count = 0;
            foreach ($arr as $elem) {
                if (is_array($elem) && isset($elem[$arg]) && $elem[$arg] == $filterValue) {
                    $count++;
                }
            }
            return $count;
        }
    }
    if (!function_exists('TS_VCSC_Memory_Usage')){
        function TS_VCSC_Memory_Usage($decimals = 2) {
            $result = 0;
            if (function_exists('memory_get_usage')) {
                $result = memory_get_usage() / 1024;
            } else {
                if (function_exists('exec')) {
                    $output = array();
                    if (substr(strtoupper(PHP_OS), 0, 3) == 'WIN') {
                        exec('tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $output);
                        $result = preg_replace('/[\D]/', '', $output[5]);
                    } else {
                        exec('ps -eo%mem,rss,pid | grep ' . getmypid(), $output);
                        $output = explode('  ', $output[0]);
                        $result = $output[1];
                    }
                }
            }
            return number_format(intval($result) / 1024, $decimals, '.', '');
        }
    }
    if (!function_exists('TS_VCSC_LetToNumber')){
        function TS_VCSC_LetToNumber( $v ) {
            $l   = substr( $v, -1 );
            $ret = substr( $v, 0, -1 );
            switch ( strtoupper( $l ) ) {
                case 'P': // fall-through
                case 'T': // fall-through
                case 'G': // fall-through
                case 'M': // fall-through
                case 'K': // fall-through
                    $ret *= 1024;
                    break;
                default:
                    break;
            }
            return $ret;
        }
    }
    if (!function_exists('TS_VCSC_CleanNumberData')){
        function TS_VCSC_CleanNumberData($a) {
            if(is_numeric($a)) {
                $a = preg_replace('/[^0-9,]/s', '', $a);
            }
            return $a;
        }
    }
    if (!function_exists('TS_VCSC_FormatSizeUnits')){
        function TS_VCSC_FormatSizeUnits($bytes) {
            if ($bytes >= 1073741824) {
                $bytes = number_format($bytes / 1073741824, 2) . ' GB';
            } elseif ($bytes >= 1048576) {
                $bytes = number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                $bytes = number_format($bytes / 1024, 2) . ' KB';
            } elseif ($bytes > 1) {
                $bytes = $bytes . ' Bytes';
            } elseif ($bytes == 1) {
                $bytes = $bytes . ' Byte';
            } else {
                $bytes = '0 Bytes';
            }
            return $bytes;
        }
    }
    if (!function_exists('TS_VCSC_TruncateHTML')){
        /**
        * Truncates text.
        *
        * Cuts a string to the length of $length and replaces the last characters
        * with the ending if the text is longer than length.
        *
        * @param string  $text String to truncate.
        * @param integer $length Length of returned string, including ellipsis.
        * @param string  $ending Ending to be appended to the trimmed string.
        * @param boolean $exact If false, $text will not be cut mid-word
        * @param boolean $considerHtml If true, HTML tags would be handled correctly
        * @return string Trimmed string.
        */
        function TS_VCSC_TruncateHTML($text, $length = 100, $ending = '...', $exact = true, $considerHtml = false) {
            if ($considerHtml) {
                // if the plain text is shorter than the maximum length, return the whole text
                if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                    return $text;
                }
                // splits all html-tags to scanable lines
                preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
                $total_length 	= 0;
                $open_tags 		= array();
                $truncate 		= '';
                foreach ($lines as $line_matchings) {
                    // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                    if (!empty($line_matchings[1])) {
                        // if it's an "empty element" with or without xhtml-conform closing slash (f.e. <br/>)
                        if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                            // do nothing
                        // if tag is a closing tag (f.e. </b>)
                        } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                            // delete tag from $open_tags list
                            $pos = array_search($tag_matchings[1], $open_tags);
                            if ($pos !== false) {
                                unset($open_tags[$pos]);
                            }
                        // if tag is an opening tag (f.e. <b>)
                        } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                            // add tag to the beginning of $open_tags list
                            array_unshift($open_tags, strtolower($tag_matchings[1]));
                        }
                        // add html-tag to $truncate'd text
                        $truncate .= $line_matchings[1];
                    }
                    // calculate the length of the plain text part of the line; handle entities as one character
                    $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                    if (($total_length + $content_length) > $length) {
                        // the number of characters which are left
                        $left 				= $length - $total_length;
                        $entities_length 	= 0;
                        // search for html entities
                        if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                            // calculate the real length of all entities in the legal range
                            foreach ($entities[0] as $entity) {
                                if ($entity[1] + 1 - $entities_length <= $left) {
                                    $left--;
                                    $entities_length += strlen($entity[0]);
                                } else {
                                    // no more characters left
                                    break;
                                }
                            }
                        }
                        $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                        // maximum lenght is reached, so get off the loop
                        break;
                    } else {
                        $truncate .= $line_matchings[2];
                        $total_length += $content_length;
                    }
                    // if the maximum length is reached, get off the loop
                    if ($total_length >= $length) {
                        break;
                    }
                }
            } else {
                if (strlen($text) <= $length) {
                    return $text;
                } else {
                    $truncate = substr($text, 0, $length);
                }
            }
            // if the words shouldn't be cut in the middle...
            if (!$exact) {
                // ...search the last occurance of a space...
                $spacepos = strrpos($truncate, ' ');
                if (isset($spacepos)) {
                    // ...and cut the text in this position
                    $truncate = substr($truncate, 0, $spacepos);
                }
            }
            // add the defined ending to the text
            $truncate .= ' ' . $ending;
            if ($considerHtml) {
                // close all unclosed html-tags
                foreach ($open_tags as $tag) {
                    $truncate .= '</' . $tag . '>';
                }
            }
            return $truncate;
        }
    }
    if (!function_exists('TS_VCSC_CurrentPageURL')){
        function TS_VCSC_CurrentPageURL() {
            $pageURL = 'http';
            if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
            $pageURL .= "://";
            if ($_SERVER["SERVER_PORT"] != "80") {
                $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
            } else {
                $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
            }
            return $pageURL;
        }
    }
    if (!function_exists('TS_VCSC_CurrentPageName')){
        function TS_VCSC_CurrentPageName() {
            return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
        }
    }
    if (!function_exists('TS_VCSC_CheckShortcode')){
        function TS_VCSC_CheckShortcode($shortcode = '') {
            $post_to_check = get_post(get_the_ID());
            // false because we have to search through the post content first
            $found = false;
            // if no short code was provided, return false
            if (!$shortcode) {
                return $found;
            }
            // check the post content for the short code
            if (stripos($post_to_check->post_content, '[' . $shortcode) !== false) {
                // we have found the short code
                $found = true;
            }
            // return our final results
            return $found;
        }
    }
    if (!function_exists('TS_VCSC_GetRowSeparator')){
        function TS_VCSC_GetRowSeparator($index, $color1, $color2, $height, $classes) {
			$svgclasses 		= '';
			$svgstyles			= '';
			if (($index > 19) && ($index < 24)) {
				$svgclasses		= 'ts-row-shape-diagonals-basic';
			} else if ($index == 24) {
				$svgclasses		= 'ts-row-shape-top-arrow-out';
			} else if ($index == 25) {
				$svgclasses		= 'ts-row-shape-top-arrow-in';
			} else if ($index == 26) {
				$svgclasses		= 'ts-row-shape-bottom-arrow-out';
			} else if ($index == 27) {
				$svgclasses		= 'ts-row-shape-bottom-arrow-in';
			}
            $svgshapes = array(
                // No Effect (Square) 											/ 0
                '<rect fill="' . $color1 . '" y="-1" width="100" height="1"/>',
                // Triangle Inwards												/ 1
                '<path fill="' . $color1 . '" d="M-0.3,0l50.2,10L100,0H-0.3z"></path>',
                //  Triangle Outwards											/ 2
                '<path fill="' . $color1 . '" d="M0,0h100v9.9L50,0L0,9.9"></path>',
                // Slight Center Curve Inwards									/ 3
                '<path fill="' . $color1 . '" d="M100.2,0C60.1,10.7,40.1,10.7,0,0H100.2z"></path>',
                // Slight Left Curve Inwards									/ 4
                '<path fill="' . $color1 . '" d="M99.9,0C50,8.7,20,8.7,0.1,0H99.9z"></path>',
                // Slight Right Curve Inwards									/ 5
                '<path fill="' . $color1 . '" d="M99.9,0C80,8.7,50,8.7,0.1,0L99.9,0z"></path>',
                // Sharp Left Curce Inwards										/ 6
                '<path fill="' . $color1 . '" d="M99.9,0C28.1,8.7-0.9,8.7,0,0L99.9,0z"></path>',
                // Sharp Right Curve Inwards									/ 7
                '<path fill="' . $color1 . '" d="M100,0c0.9,8.7-28.2,8.7-99.9,0H100z"></path>',
                // Sharp Left Line Inwards										/ 8
                '<path fill="' . $color1 . '" d="M0,9.1L0,0l100,0"></path>',
                // Sharp Right Line Inwards										/ 9
                '<path fill="' . $color1 . '" d="M0,0h100v9.1"></path>',
                // Inwards Wave Pattern											/ 10
                '<path fill="' . $color1 . '" d="M-0.1,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0
                c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0
                c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0
                c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0c1.7,11.8,3.3,11.8,5,0H-0.1z"></path>',
                // Inwards Cloud Pattern										/ 11
                '<path fill="' . $color1 . '" d="M9,0c-3,8.9-6.1,8.9-9.1,0H9z M13.5,0c-3,11.2-6.1,11.2-9.1,0 M18.1,0C15,7.8,12,7.8,9,0 M22.6,0c-3,10.1-6.1,10.1-9.1,0
                M27.2,0c-3,7.8-6.1,7.8-9.1,0 M31.7,0c-3,12.3-6.1,12.3-9.1,0 M36.3,0c-3,10.1-6.1,10.1-9.1,0 M40.8,0c-3,7.8-6.1,7.8-9.1,0
                M45.4,0c-3,10.1-6.1,10.1-9.1,0 M49.9,0c-3,5.6-6.1,5.6-9.1,0 M54.5,0c-3,8.9-6.1,8.9-9.1,0 M59,0c-3,6.7-6.1,6.7-9.1,0 M63.6,0
               c-3,4.5-6.1,4.5-9.1,0 M68.2,0c-3,5.6-6.1,5.6-9.1,0 M72.7,0c-3,8.9-6.1,8.9-9.1,0 M77.3,0c-3,6.2-6.1,6.2-9.1,0 M81.8,0
               c-3,7.8-6.1,7.8-9.1,0 M86.4,0c-3,8.9-6.1,8.9-9.1,0 M90.9,0c-3,5.6-6.1,5.6-9.1,0 M95.5,0c-3,8.4-6.1,8.4-9.1,0 M100,0
               c-3,9.5-6.1,9.5-9.1,0H100z"></path>',
                // Simple Cloud Pattern											/ 12
                '<path fill="' . $color1 . '" d="M-5 100 Q 0 20 5 100 Z M0 100 Q 5 0 10 100 M5 100 Q 10 30 15 100 M10 100 Q 15 10 20 100 M15 100 Q 20 30 25 100 M20 100 Q 25 -10 30 100 M25 100 Q 30 10 35 100 M30 100 Q 35 30 40 100 M35 100 Q 40 10 45 100 M40 100 Q 45 50 50 100 M45 100 Q 50 20 55 100 M50 100 Q 55 40 60 100 M55 100 Q 60 60 65 100 M60 100 Q 65 50 70 100 M65 100 Q 70 20 75 100 M70 100 Q 75 45 80 100 M75 100 Q 80 30 85 100 M80 100 Q 85 20 90 100 M85 100 Q 90 50 95 100 M90 100 Q 95 25 100 100 M95 100 Q 100 15 105 100 Z"></path>',
               // Stamps														/ 13
               '<path fill="' . $color1 . '" d="M0 0 Q 2.5 40 5 0 Q 7.5 40 10 0Q 12.5 40 15 0Q 17.5 40 20 0Q 22.5 40 25 0 Q 27.5 40 30 0 Q 32.5 40 35 0 Q 37.5 40 40 0 Q 42.5 40 45 0 Q 47.5 40 50 0 Q 52.5 40 55 0 Q 57.5 40 60 0 Q 62.5 40 65 0 Q 67.5 40 70 0 Q 72.5 40 75 0 Q 77.5 40 80 0 Q 82.5 40 85 0 Q 87.5 40 90 0 Q 92.5 40 95 0Q 97.5 40 100 0 Z"></path>',
               // Slits															/ 14
               '<path id="slitPath2" fill="' . $color2 . '" d="M50 100 C49 80 47 0 40 0 L47 0 Z" /><path id="slitPath3" fill="' . $color2 . '" d="M50 100 C51 80 53 0 60 0 L53 0 Z" /><path id="slitPath1" fill="' . $color1 . '" d="M47 0 L50 100 L53 0 Z" ></path>',
               // Big Triangle													/ 15
               '<path d="M0 0 L50 100 L100 0 Z" ></path>',
               // Big Triangle with Shadow										/ 16
               '<path id="trianglePath1" fill="' . $color1 . '" d="M0 0 L50 100 L100 0 Z" /><path id="trianglePath2" fill="' . $color2 . '" d="M50 100 L100 40 L100 0 Z" ></path>',
               // Curve Up														/ 17
               '<path fill="' . $color1 . '" d="M0 100 C 20 0 50 0 100 100 Z"></path>',
               // Curve Down													/ 18
               '<path fill="' . $color1 . '" d="M0 0 C 50 100 80 100 100 0 Z"></path>',
               // Big Half Circle												/ 19
               '<path fill="' . $color1 . '" d="M0 100 C40 0 60 0 100 100 Z"></path>',

			   // Top Diagonal (Full Left - None Right)							/ 20
			   '<polygon points="0,1 1,0 0,0" fill="' . $color1 . '"></polygon>',			   
			   // Top Diagonal (None Left - Full Right)							/ 21
			   '<polygon points="0,0 1,0 1,1" fill="' . $color1 . '"></polygon>',
			   // Bottom Diagonal (Full Left - None Right)						/ 22
			   '<polygon points="0,1 1,1 0,0" fill="' . $color1 . '"></polygon>',
			   // Bottom Diagonal (None Left - Full Right)						/ 23
			   '<polygon points="1,0 1,1 0,1" fill="' . $color1 . '"></polygon>',
			   
			   // Top Center Arrow Out											/ 24
			   '<polygon points="0,0 1,0 0.5,1" fill="' . $color1 . '"></polygon>',
			   // Top Center Arrow In											/ 25
			   '<polygon points="0,0 1,0 0.5,1" fill="' . $color1 . '"></polygon>',
			   // Bottom Center Arrow Out										/ 26
			   '<polygon points="0,0 1,0 0.5,1" fill="' . $color1 . '"></polygon>',
			   // Bottom Center Arrow In										/ 27			   
			   '<polygon points="0,0 1,0 0.5,1" fill="' . $color1 . '"></polygon>',
            );
            if ($index < 12) {
                return '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="' . $classes . ' ' . $svgclasses . '" width="100%" height="' . $height . '" fill="' . $color1 . '" viewBox="0 0 100 10" preserveAspectRatio="none" style="height: ' . $height . 'px; width: 100%;">' . '' . $svgshapes[$index] . '</svg>';
            } else if ($index < 20) {
                return '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="' . $classes . ' ' . $svgclasses . '" width="100%" height="' . $height . '" fill="' . $color1 . '" viewBox="0 0 100 100" preserveAspectRatio="none" style="height: ' . $height . 'px; width: 100%;">' . '' . $svgshapes[$index] . '</svg>';
            } else if ($index < 24) {
				if (($index == 20) || ($index == 21)) {
					$svgstyles		= 'top: 1px';
				} else if (($index == 22) || ($index == 23)) {
					$svgstyles		= 'bottom: 0px';
				}
                return '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="' . $classes . ' ' . $svgclasses . '" width="100%" height="' . $height . '" fill="' . $color1 . '" viewBox="0 0 1 1" preserveAspectRatio="none" style="height: ' . $height . 'px; width: 100%; ' . $svgstyles . '">' . '' . $svgshapes[$index] . '</svg>';
            } else if (($index > 23) && ($index < 28)) {
				if ($index == 24) {
					$svgstyles	= 'height: ' . $height . 'px; width: ' . ($height * 2) . 'px; margin-left: -' . $height . 'px; top: -' . ($height - 1) . 'px;';	
				} else if ($index == 25) {
					$svgstyles	= 'height: ' . $height . 'px; width: ' . ($height * 2) . 'px; margin-left: -' . $height . 'px; top: -' . ($height - 1) . 'px;';	
				} else if ($index == 26) {
					$svgstyles	= 'height: ' . $height . 'px; width: ' . ($height * 2) . 'px; margin-left: -' . $height . 'px; bottom: -100%;';	
				} else if ($index == 27) {
					$svgstyles	= 'height: ' . $height . 'px; width: ' . ($height * 2) . 'px; margin-left: -' . $height . 'px; bottom: 0px;';	
				}	
                return '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="' . $classes . ' ' . $svgclasses . '" width="100%" height="' . $height . '" fill="' . $color1 . '" viewBox="0 0 1 1" preserveAspectRatio="none" style="' . $svgstyles . '">' . '' . $svgshapes[$index] . '</svg>';
            }
        }
    }
    if (!function_exists('TS_VCSC_CheckString')){
        function TS_VCSC_CheckString($string = '') {
            $post_to_check = get_post(get_the_ID());
            // false because we have to search through the post content first
            $found = false;
            // if no string was provided, return false
            if (!$string) {
                return $found;
            }
            // check the post content for the short code
            if (stripos($post_to_check->post_content, '' . $string) !== false) {
                // we have found the string
                $found = true;
            }
            // return our final results
            return $found;
        }
    }
    if (!function_exists('TS_VCSC_GetExtraClass')){
        function TS_VCSC_GetExtraClass($el_class) {
            $output = '';
            if ( $el_class != '' ) {
                $output = " " . str_replace(".", "", $el_class);
            }
            return $output;
        }
    }
    if (!function_exists('TS_VCSC_endBlockComment')){
        function TS_VCSC_endBlockComment($string) {
            return (!empty($_GET['wpb_debug']) && $_GET['wpb_debug']=='true' ? '<!-- END '.$string.' -->' : '');
        }
    }
    if (!function_exists('TS_VCSC_GetCSSAnimation')){
        function TS_VCSC_GetCSSAnimation($css_animation, $convert = "false") {			
			// Old Entry Animation from Visual Composer
			$animation_old		= array(
				"top-to-bottom"			=> "ts-viewport-css-slideInDown",
				"bottom-to-top"			=> "ts-viewport-css-slideInUp",
				"left-to-right"			=> "ts-viewport-css-slideInLeft",
				"right-to-left"			=> "ts-viewport-css-slideInRight",
				"appear"				=> "ts-viewport-css-fadeIn"
			);
            $output 			= '';
            if ($css_animation != '') {
                if (get_option('ts_vcsc_extend_settings_loadWaypoints', 1) == 1) {
                    if (wp_script_is('waypoints', $list = 'registered')) {
                        wp_enqueue_script('waypoints');
                    } else {
                        wp_enqueue_script('ts-extend-waypoints');
                    }
                }
                if ($convert == "false") {
					$output 	= ' wpb_animate_when_almost_visible wpb_' . $css_animation;
				} else {
					if (array_key_exists($css_animation, $animation_old)) {
						$output	= $animation_old[$css_animation];
					} else {
						$output	= $css_animation;
					}
				}
            }
            return $output;
        }
    }
    if (!function_exists('TS_VCSC_DeleteOptionsPrefixed')){
        function TS_VCSC_DeleteOptionsPrefixed($prefix) {
            global $wpdb;
            $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'" );
        }
    }
    if (!function_exists('TS_VCSC_SortMultiArray')){
        function TS_VCSC_SortMultiArray(&$array, $key) {
            foreach($array as &$value) {
                $value['__________'] = $value[$key];
            }
            /* Note, if your functions are inside of a class, use: 
                usort($array, array("My_Class", 'TS_VCSC_SortByDummyKey'));
            */
            usort($array, 'TS_VCSC_SortByDummyKey');
            foreach($array as &$value) {   // removes the dummy key from your array
                unset($value['__________']);
            }
            return $array;
        }
    }
    if (!function_exists('TS_VCSC_SortByDummyKey')){
        function TS_VCSC_SortByDummyKey($a, $b) {
            if($a['__________'] == $b['__________']) return 0;
            if($a['__________'] < $b['__________']) return -1;
            return 1;
        }
    }
	if (!function_exists('TS_VCSC_ArrayFlatten')){
		function TS_VCSC_ArrayFlatten($array, $check) {
		   $return 							= array();
		   foreach ($array as $key => $value) {
				if ((is_array($value)) && ($check == true)) {
					$return 				= array_merge($return, TS_VCSC_ArrayFlatten($value, false));
				} else {
					$return[$key] 			= $value;
				}
		   }
		   return $return;
		}
	}
    if (!function_exists('TS_VCSC_getRemoteFile')){
        function TS_VCSC_getRemoteFile($url) {
            // get the host name and url path
            $parsedUrl = parse_url($url);
            $host = $parsedUrl['host'];
            if (isset($parsedUrl['path'])) {
                $path = $parsedUrl['path'];
            } else {
                // the url is pointing to the host like http://www.mysite.com
                $path = '/';
            }
            if (isset($parsedUrl['query'])) {
                $path .= '?' . $parsedUrl['query'];
            }
            if (isset($parsedUrl['port'])) {
                $port = $parsedUrl['port'];
            } else {
                // most sites use port 80
                $port = '80';
            }
            $timeout = 10;
            $response = '';
            // connect to the remote server
            $fp = @fsockopen($host, '80', $errno, $errstr, $timeout );
            if( !$fp ) {
                echo "Cannot retrieve $url";
            } else {
                // send the necessary headers to get the file
                fputs($fp, "GET $path HTTP/1.0\r\n" .
                "Host: $host\r\n" .
                "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.3) Gecko/20060426 Firefox/1.5.0.3\r\n" .
                "Accept: */*\r\n" .
                "Accept-Language: en-us,en;q=0.5\r\n" .
                "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n" .
                "Keep-Alive: 300\r\n" .
                "Connection: keep-alive\r\n" .
                "Referer: http://$host\r\n\r\n");
                // retrieve the response from the remote server
                while ( $line = fread( $fp, 4096 ) ) {
                    $response .= $line;
                }
                fclose( $fp );
                // strip the headers
                $pos = strpos($response, "\r\n\r\n");
                $response = substr($response, $pos + 4);
            }
            // return the file content
            return $response;
        }
    }
    if (!function_exists('TS_VCSC_RetrieveExternalData')){
        function TS_VCSC_RetrieveExternalData($url){
            if (function_exists('curl_init')) {
                //echo 'Using CURL';
                // initialize a new curl resource
				$ch                         = curl_init();
				$timeout                    = 60;                             
				curl_setopt($ch, CURLOPT_URL,               $url);
				curl_setopt($ch, CURLOPT_HEADER, 			0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,    true);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,    $timeout);
				curl_setopt($ch, CURLOPT_MAXREDIRS,         3);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 	false);
				curl_setopt($ch, CURLOPT_USERAGENT,         'Composium - Visual Composer Extensions (7190695) by Tekanewa Scripts');
				$content					= curl_exec($ch);
				if (!curl_errno($ch)) {
					$success				= true;
				} else {
					$error					= curl_errno($ch);
				}
				curl_close($ch);
            } else if (ini_get('allow_url_fopen') == '1') {
                //echo 'Using file_get_contents';
                $content = @file_get_contents($url);
                if ($content !== false) {
                    $content = $content;
                } else {
                    $content = '';
                }
            } else {
                //echo 'Using Others';
                $content = TS_VCSC_getRemoteFile($url);
            }
            return $content;
        }
    }
    if (!function_exists('TS_VCSC_checkValidURL')){
        function TS_VCSC_checkValidURL($url) {
            if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
                return true;
            } else {
                return false;
            }
        }
    }
    if (!function_exists('TS_VCSC_makeValidURL')){
        function TS_VCSC_makeValidURL($url) {
            if (preg_match("~^(?:f|ht)tps?://~i", $url)) {
                return $url;
            } else {
                return 'http://' . $url;
            }
        }
    }
    if (!function_exists('TS_VCSC_numberOfDecimals')){
        function TS_VCSC_numberOfDecimals($value) {
            if ((int)$value == $value) {
                return 0;
            } else if (!is_numeric($value)) {
                // throw new Exception('numberOfDecimals: ' . $value . ' is not a number!');
                return false;
            }
            return strlen($value) - strrpos($value, '.') - 1;
        }
    }  
    if (!function_exists('TS_VCSC_GetRandomColorHex')){
        function TS_VCSC_GetRandomColorHex($max_r = 255, $max_g = 255, $max_b = 255) {
            // ensure that values are in the range between 0 and 255
            $max_r = max(0, min($max_r, 255));
            $max_g = max(0, min($max_g, 255));
            $max_b = max(0, min($max_b, 255));
            // generate and return the random color
            return str_pad(dechex(rand(0, $max_r)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(rand(0, $max_g)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(rand(0, $max_b)), 2, '0', STR_PAD_LEFT);
        }
    }
	if (!function_exists('TS_VCSC_ConverToRoman')) {
		function TS_VCSC_ConverToRoman($num){ 
			$n = intval($num); 
			$res = '';		
			//array of roman numbers
			$romanNumber_Array = array( 
				'M'  => 1000, 
				'CM' => 900, 
				'D'  => 500, 
				'CD' => 400, 
				'C'  => 100, 
				'XC' => 90, 
				'L'  => 50, 
				'XL' => 40, 
				'X'  => 10, 
				'IX' => 9, 
				'V'  => 5, 
				'IV' => 4, 
				'I'  => 1); 		
			foreach ($romanNumber_Array as $roman => $number){ 
				//divide to get  matches
				$matches = intval($n / $number); 		
				//assign the roman char * $matches
				$res .= str_repeat($roman, $matches); 		
				//substract from the number
				$n = $n % $number; 
			} 		
			// return the result
			return $res; 
		}
	}
	if (!function_exists('TS_VCSC_ConvertToAlpha')) {
		function TS_VCSC_ConvertToAlpha($num){
			return chr(substr("000".($num+65),-3));
		}
	}
	if (!function_exists('TS_VCSC_ConvertPlaceholderComma')) {
		function TS_VCSC_ConvertPlaceholderComma($string){
			$string = str_replace(array("|comma|", "/comma/", "{comma}", "[comma]"), ",", $string);
			return $string;
		}
	}
	if (!function_exists('TS_VCSC_CheckRegisteredFileStatus')) {
		function TS_VCSC_CheckRegisteredFileStatus($file, $type) {			
			if (($type == "style") && ($file != '')) {
				return $filestatus = array(
					'registered'		=> wp_style_is($file, 'registered'),
					'enqueued'			=> wp_style_is($file, 'enqueued'),
					'done'				=> wp_style_is($file, 'done'),
					'to_do'				=> wp_style_is($file, 'to_do'),
				);
			} else if (($type == "script") && ($file != '')) {
				return $filestatus = array(
					'registered'		=> wp_script_is($file, 'registered'),
					'enqueued'			=> wp_script_is($file, 'enqueued'),
					'done'				=> wp_script_is($file, 'done'),
					'to_do'				=> wp_script_is($file, 'to_do'),
				);
			} else {
				return $filestatus = array(
					'registered'		=> false,
					'enqueued'			=> false,
					'done'				=> false,
					'to_do'				=> false,
				);
			}
		}
	}
	if (!function_exists('TS_VCSC_FrontendAppendCustomRules')) {
		function TS_VCSC_FrontendAppendCustomRules($type) {
			if ($type == "style") {
				wp_enqueue_style('ts-visual-composer-extend-custom');
				return "true";
			} else if ($type == "script") {
				wp_enqueue_style('ts-visual-composer-extend-custom');
				return "true";
			} else {
				return "false";
			}
		}
	}
?>