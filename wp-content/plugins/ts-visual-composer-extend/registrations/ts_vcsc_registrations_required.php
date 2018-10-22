<?php
	// Function to Compare + Check WordPress Versions
	// ----------------------------------------------
	if (!function_exists('TS_VCSC_WordPressCheckup')) {
		function TS_VCSC_WordPressCheckup($version = '3.8') {
			global $wp_version;		
			if (version_compare($wp_version, $version, '>=')) {
				return "true";
			} else {
				return "false";
			}
		}
	}
	
	// Function for Full Variable Output With Highlighting
	// ---------------------------------------------------
	if (!function_exists('TS_VCSC_HighlightText')) {
		function TS_VCSC_HighlightText($text, $export = true) {
			if (($export == true) || ($export == 'true')) {
				$text 						= var_export($text, true);
			}
			$text 							= trim($text);
			$text 							= highlight_string("<?php " . $text, true);  // highlight_string() requires opening PHP tag or otherwise it will not colorize the text
			$text 							= trim($text);
			$text 							= preg_replace("|^\\<code\\>\\<span style\\=\"color\\: #[a-fA-F0-9]{0,6}\"\\>|", "", $text, 1);  // remove prefix
			$text 							= preg_replace("|\\</code\\>\$|", "", $text, 1);  // remove suffix 1
			$text 							= trim($text);  // remove line breaks
			$text 							= preg_replace("|\\</span\\>\$|", "", $text, 1);  // remove suffix 2
			$text 							= trim($text);  // remove line breaks
			$text 							= preg_replace("|^(\\<span style\\=\"color\\: #[a-fA-F0-9]{0,6}\"\\>)(&lt;\\?php&nbsp;)(.*?)(\\</span\\>)|", "\$1\$3\$4", $text);  // remove custom added "<?php "
			return $text;
		}
	}
	
	
	// Function for WPML Post ID Conversion
	// ------------------------------------
	if (!function_exists('TS_VCSC_WPMLConversionID')){
		function TS_VCSC_WPMLConversionID($post_id, $post_type) {
			$post_old						= $post_id;
			$post_id 						= apply_filters('wpml_object_id', intval($post_id), $post_type, true);
			if (empty($post_id)) {
				$post_id					= $post_old;
			}
			return intval($post_id);
		}
	}
	
	// Function to Check if Currently Editing Page + Post
	// --------------------------------------------------
	if (!function_exists('TS_VCSC_IsEditPagePost')){
		function TS_VCSC_IsEditPagePost($new_edit = null){
			global $pagenow, $typenow;
			$frontend = TS_VCSC_CheckFrontEndEditor();
			if (function_exists('vc_is_inline')){
				$vc_is_inline = vc_is_inline();
				if ((vc_is_inline() == false) && (vc_is_inline() != '') && (vc_is_inline() != true) && (!is_admin())) {
					return false;
				} else if ((vc_is_inline() == true) && (vc_is_inline() != '') && (vc_is_inline() != true) && (!is_admin())) {
					return true;
				} else if (((vc_is_inline() == NULL) || (vc_is_inline() == '')) && (!is_admin())) {
					if ($frontend == true) {
						$vc_is_inline = true;
						return true;
					} else {
						$vc_is_inline = false;
						return false;
					}
				}
			} else {
				$vc_is_inline = false;
				if (!is_admin()) return false;
			}
			if (($frontend == true) && (!is_admin())) {
				return true;
			} else if ($new_edit == "edit") {
				return in_array($pagenow, array('post.php'));
			} else if ($new_edit == "new") {
				return in_array($pagenow, array('post-new.php'));
			} else if ($vc_is_inline == true) {
				return true;
			} else {
				return in_array($pagenow, array('post.php', 'post-new.php'));
			}
		}
	}
	
	// Function to Parse File Paths
	// ----------------------------
	if (!function_exists('TS_VCSC_GetPathInfo')){
		function TS_VCSC_GetPathInfo($filepath) {
			preg_match('%^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^\.\\\\/]+?)|))[\\\\/\.]*$%im', $filepath, $m);
			if ($m[1]) $ret['dirname']		= $m[1];
			if ($m[2]) $ret['basename']		= $m[2];
			if ($m[5]) $ret['extension']	= $m[5];
			if ($m[3]) $ret['filename']		= $m[3];
			return $ret;
		}
	}
	
	// Function to Check for VC Frontend Editor
	// ----------------------------------------
	if (!function_exists('TS_VCSC_CheckFrontEndEditor')){
		function TS_VCSC_CheckFrontEndEditor() {
			$request						= 'http://';
			if (isset($_SERVER['SERVER_NAME'])) {
				$request					.= $_SERVER['SERVER_NAME'];
			} else if (isset($_SERVER['HTTP_HOST'])) {
				$request					.= $_SERVER['HTTP_HOST'];
			}
			if (isset($_SERVER['REQUEST_URI'])) {
				$request 					.= $_SERVER['REQUEST_URI'];
			}
			if (strpos($request, "admin-ajax.php") !== false) {
				$finalurl					= $_SERVER["HTTP_REFERER"];
			} else {
				$finalurl					= $request;
			}
			if ((strpos($finalurl, "vc_editable=true") !== false) || (strpos($finalurl, "vc_action=vc_inline") !== false)) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	// Function to Compare + Check Version Numbers
	// -------------------------------------------
	if (!function_exists('TS_VCSC_VersionCompare')){
		function TS_VCSC_VersionCompare($a, $b) {
			//Compare two sets of versions, where major/minor/etc. releases are separated by dots. 
			//Returns 0 if both are equal, 1 if A > B, and -1 if B < A.
			$a = trim($a);
			$b = trim($b);
			$a = preg_replace("/[^0-9.]/", "", $a);
			$b = preg_replace("/[^0-9.]/", "", $b);
			$a = explode(".", TS_VCSC_CustomSTRrTrim($a, ".0")); //Split version into pieces and remove trailing .0 
			$b = explode(".", TS_VCSC_CustomSTRrTrim($b, ".0")); //Split version into pieces and remove trailing .0 
			//Iterate over each piece of A 
			foreach ($a as $depth => $aVal) {
				if (isset($b[$depth])) {
				//If B matches A to this depth, compare the values 
					if ($aVal > $b[$depth]) {
						return 1; //Return A > B
						//break;
					} else if ($aVal < $b[$depth]) {
						return -1; //Return B > A
						//break;
					}
				//An equal result is inconclusive at this point 
				} else  {
					//If B does not match A to this depth, then A comes after B in sort order 
					return 1; //so return A > B
					//break;
				} 
			} 
			//At this point, we know that to the depth that A and B extend to, they are equivalent. 
			//Either the loop ended because A is shorter than B, or both are equal. 
			return (count($a) < count($b)) ? -1 : 0; 
		}
	}
	
    // Function to Retrieve Current Post Type
    // --------------------------------------
    if (!function_exists('TS_VCSC_GetCurrentPostType')){
        function TS_VCSC_GetCurrentPostType() {
            global $post, $typenow, $current_screen;
            if ($post && $post->post_type) {
                // We have a post so we can just get the post type from that
                return $post->post_type;		
            } else if ($typenow) {
                // Check the global $typenow
                return $typenow;
            } else if ($current_screen && $current_screen->post_type) {
                // Check the global $current_screen Object
                return $current_screen->post_type;	
            } else if (isset($_REQUEST['post_type'])) {
                // Check the Post Type QueryString
                return sanitize_key($_REQUEST['post_type']);
			} else if (empty($typenow) && !empty($_GET['post'])) {
				// Try to get via get_post(); Attempt A
				$post 		= get_post($_GET['post']);
				$typenow 	= $post->post_type;
				return $typenow;
			} else if (empty($typenow) && !empty($_POST['post_ID'])) {
				// Try to get via get_post(); Attempt B
				$post 		= get_post($_POST['post_ID']);
				$typenow 	= $post->post_type;
				return $typenow;
			} else if (function_exists('get_current_screen')) {
				// Try to get via get_current_screen()
				$current 	= get_current_screen();
				if (isset($current) && ($current != false) && ($current->post_type)) {
					return $current->post_type;
				} else {
					return null;
				}
			}
            // We Do Not Know The Post Type!!!
            return null;
        }
    }
	
	// Function to Retrieve User Roles + Capabilities
	// ----------------------------------------------
	if (!function_exists('TS_VCSC_GetUserRolesCapabilities')){
		function TS_VCSC_GetUserRolesCapabilities() {
			global $VISUAL_COMPOSER_EXTENSIONS;
			global $wp_roles;
			if (!isset($wp_roles)) {
				$wp_roles 												= new WP_Roles();
			}
			$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Conditionals_Roles 	= $wp_roles->get_names();
			$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Conditionals_Rights	= array();
			foreach ($wp_roles->roles as $role) {
				foreach ($role['capabilities'] as $capabilities => $capability) {
					if (!in_array($capabilities, $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Conditionals_Rights)){
						array_push($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Conditionals_Rights, $capabilities);
					}
				}
			}
		}
	}
		
	// Function to Retrieve Categories for Custom Post
	// -----------------------------------------------
	if (!function_exists('TS_VCSC_GetCategoriesCustomPost')){
		function TS_VCSC_GetCategoriesCustomPost($id = false, $tcat = 'category') {
			$categories = get_the_terms( $id, $tcat );
			if (!$categories) {
				$categories = array();
			}	
			$categories = array_values( $categories );	
			foreach ( array_keys( $categories ) as $key ) {
				_make_cat_compat( $categories[$key] );
			}	
			return apply_filters('get_the_categories', $categories);
		}
	}
	
	// Function to Check if a Plugin is Active
	// ---------------------------------------
    if (!function_exists('TS_VCSC_PluginIsActive')){
        function TS_VCSC_PluginIsActive($plugin_path) {
            $return_var = in_array($plugin_path, apply_filters('active_plugins', get_option('active_plugins')));
            return $return_var;
        }
    }
	
	// Function to Trim trailing .0 from Version Numbers
	// -------------------------------------------------
	if (!function_exists('TS_VCSC_CustomSTRrTrim')){
		function TS_VCSC_CustomSTRrTrim($message, $strip) {
			$lines = explode($strip, $message); 
			$last  = ''; 
			do { 
				$last = array_pop($lines); 
			} while (empty($last) && (count($lines)));
			return implode($strip, array_merge($lines, array($last))); 
		}
	}
	
	// Function for Basic Case Insensitive Sorting
	// -------------------------------------------
    if (!function_exists('TS_VCSC_CaseInsensitiveSort')){
        function TS_VCSC_CaseInsensitiveSort($a,$b) { 
            return strtolower($b) < strtolower($a); 
        }
    }
	
	// Function to Create Page + Post Collection
	// -----------------------------------------
    if (!function_exists('TS_VCSC_GetPostOptions')){
        function TS_VCSC_GetPostOptions($query_args, $simple = false) {
			//remove_all_filters('posts_orderby');
            $args = wp_parse_args($query_args, array(
                'post_type' 		=> 'post',
                'posts_per_page'	=> -1,
				'offset'			=> 0,
                'orderby' 			=> 'title',
                'order' 			=> 'ASC',
				'post_status'      	=> 'publish',
            ) );
			$post_options 			= array();
			$post_data				= get_post_type_object($args['post_type']);
			// Retrieve Post Data
            $posts 					= get_posts($args);
            if ($posts) {
                foreach ($posts as $post) {
					if ($simple) {
						$post_options[$post->ID] 	= $post->post_title;
					} else {
						$post_options[] = array(
							'name' 					=> $post->post_title,
							'value' 				=> $post->ID,
							'type'					=> $post_data->labels->singular_name,
							'link'					=> urlencode(get_permalink($post->ID)),
						);
					}
                }
            }
            //TS_VCSC_SortMultiArray($post_options, 'name');
            return $post_options;
        }
    }
	
    // Function to check Current User Role
    // -----------------------------------
    if (!function_exists('TS_VCSC_CheckUserRole')){
        function TS_VCSC_CheckUserRole($roles, $user_id = NULL) {
            // Get user by ID, else get current user
            if ($user_id) {
                $user = get_userdata($user_id);
            } else {
                $user = wp_get_current_user();
            }
            // No user found, return
            if (empty($user)) {
                return false;
            }
            // Append administrator to roles, if necessary
            if (!in_array('administrator', $roles)) {
                $roles[] = 'administrator';
            }
            // Loop through user roles
            foreach ($user->roles as $role) {
                // Does user have role
                if (in_array($role, $roles)) {
                    return true;
                }
            }
            // User not in roles
            return false;
        }
    }
	if (!function_exists('TS_VCSC_CheckCurrentUserRoles')){
		function TS_VCSC_CheckCurrentUserRoles($role, $user_id = null) {
			if (is_numeric($user_id)) {
				$user = get_userdata($user_id);
			} else {
				$user = wp_get_current_user();
			}			 
			if (empty($user)) {
				return false;
			}
			return in_array($role, (array) $user->roles);
		}
	}
	
	// Function to Minify HTML/JS/CSS Code
	// ------------------------------------
	if (!function_exists('TS_VCSC_MinifyHTML')) {
		function TS_VCSC_MinifyHTML($input) {
			if (trim($input) === "") return $input;
			// Remove extra white-space(s) between HTML Attribute(s)
			/*$input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function($matches) {
				return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
			}, str_replace("\r", "", $input));*/		
			$input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', 'TS_VCSC_CleanUpHTML', str_replace("\r", "", $input));	
			// Minify Inline CSS Declaration(s)
			if(strpos($input, ' style=') !== false) {
				/*$input = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', function($matches) {
					return '<' . $matches[1] . ' style=' . $matches[2] . TS_VCSC_MinifyCSS($matches[3]) . $matches[2];
				}, $input);*/
				$input = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', 'TS_VCSC_CleanUpSTYLE', $input);
			}
			return preg_replace(
				array(
					// t = text
					// o = tag open
					// c = tag close
					// Keep important white-space(s) after self-closing HTML tag(s)
					'#<(img|input)(>| .*?>)#s',
					// Remove a line break and two or more white-space(s) between tag(s)
					'#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s',
					'#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s', // t+c || o+t
					'#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s', // o+o || c+c
					'#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s', // c+t || t+o || o+t -- separated by long white-space(s)
					'#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s', // empty tag
					'#<(img|input)(>| .*?>)<\/\1>#s', // reset previous fix
					'#(&nbsp;)&nbsp;(?![<\s])#', // clean up ...
					'#(?<=\>)(&nbsp;)(?=\<)#', // --ibid
					// Remove HTML comment(s) except IE comment(s)
					'#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s'
				),
				array(
					'<$1$2</$1>',
					'$1$2$3',
					'$1$2$3',
					'$1$2$3$4$5',
					'$1$2$3$4$5$6$7',
					'$1$2$3',
					'<$1$2',
					'$1 ',
					'$1',
					""
				),
			$input);
		}
	}
	if (!function_exists('TS_VCSC_CleanUpHTML')) {
		function TS_VCSC_CleanUpHTML($matches) {
			return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
		}
	}
	if (!function_exists('TS_VCSC_CleanUpSTYLE')) {
		function TS_VCSC_CleanUpSTYLE($matches) {
			return '<' . $matches[1] . ' style=' . $matches[2] . TS_VCSC_MinifyCSS($matches[3]) . $matches[2];
		}
	}
	if (!function_exists('TS_VCSC_MinifyCSS')) {
		// Based On: https://gist.github.com/tovic/d7b310dea3b33e4732c0
		function TS_VCSC_MinifyCSS($input) {
			if (trim($input) === "") return $input;
			return preg_replace(
				array(
					// Remove comment(s)
					'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
					// Remove unused white-space(s)
					'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
					// Replace '0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)' with '0'
					'#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
					// Replace ':0 0 0 0' with ':0'
					'#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
					// Replace 'background-position:0' with 'background-position:0 0'
					'#(background-position):0(?=[;\}])#si',
					// Replace '0.6' with '.6', but only when preceded by ':', ',', '-' or a white-space
					'#(?<=[\s:,\-])0+\.(\d+)#s',
					// Minify string value
					'#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
					'#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
					// Minify HEX color code
					'#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
					// Replace '(border|outline):none' with '(border|outline):0'
					'#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
					// Remove empty selector(s)
					'#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
				),
				array(
					'$1',
					'$1$2$3$4$5$6$7',
					'$1',
					':0',
					'$1:0 0',
					'.$1',
					'$1$3',
					'$1$2$4$5',
					'$1$2$3',
					'$1:0',
					'$1$2'
				),
			$input);
		}
	}
	if (!function_exists('TS_VCSC_MinifyJS')) {
		function TS_VCSC_MinifyJS($input) {
			if (trim($input) === "") return $input;
			return preg_replace(
				array(
					// Remove comment(s)
					'#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
					// Remove white-space(s) outside the string and regex
					'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
					// Remove the last semicolon
					'#;+\}#',
					// Minify object attribute(s) except JSON attribute(s). From '{'foo':'bar'}' to '{foo:'bar'}'
					'#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
					// --ibid. From 'foo['bar']' to 'foo.bar'
					'#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
				),
				array(
					'$1',
					'$1$2',
					'}',
					'$1$3',
					'$1.$3'
				),
			$input);
		}
	}
	
	// Custom Icon Font Import/Uninstall Routines
	// ------------------------------------------
    if (!function_exists('TS_VCSC_CustomFontImportMessages')){
        function TS_VCSC_CustomFontImportMessages($type, $message) {
            echo '<script>
                jQuery(document).ready(function() {
                    jQuery("#ts_vcsc_icons_upload_custom_pack_form").trigger("reset");
					SweetContent					= "' . $message . '";
					SweetType						= "' . $type . '";
					if (SweetType == "success") {
						SweetConfirm				= true;
						SweetCancel					= false;
					} else if ((SweetType == "warning") || (SweetType == "error")) {
						SweetConfirm				= false;
						SweetCancel					= true;
					}
					TS_VCSC_SweetAlert({
						title:                      "Composium - Visual Composer Extensions",
						text:                       "",
						html:                       SweetContent,
						type:                       SweetType,
						allowOutsideClick:          false,
						allowEscapeKey:             false,
						showConfirmButton:          SweetConfirm,
						showCancelButton:           SweetCancel,
						confirmButtonColor:         "#3085d6",
						cancelButtonColor:          "#d33",
						confirmButtonText:          "Close",
						cancelButtonText:           "Close",
						closeOnConfirm:             true,
						closeOnCancel:              true,
					}, function(isConfirm) {
						if (isConfirm) {
                            jQuery("#ts-custom-iconfont-import-file").val("");
                            //location.reload();
                            window.location.href = window.location.href;
						} else {
                            jQuery("#ts-custom-iconfont-import-file").val("");
                            //location.reload();
                            window.location.href = window.location.href;
						}
					});    
                });
            </script>';
        }
    }
    if (!function_exists('TS_VCSC_ResetCustomFont')){
        function TS_VCSC_ResetCustomFont() {
            update_option('ts_vcsc_extend_settings_tinymceCustom', 			'0');
            update_option('ts_vcsc_extend_settings_tinymceCustomJSON', 		'');
            update_option('ts_vcsc_extend_settings_tinymceCustomPath', 		'');
            update_option('ts_vcsc_extend_settings_tinymceCustomArray', 	'');
            update_option('ts_vcsc_extend_settings_tinymceCustomName', 		'Custom User Font');
            update_option('ts_vcsc_extend_settings_tinymceCustomAuthor', 	'Custom User');
            update_option('ts_vcsc_extend_settings_tinymceCustomCount', 	'0');
            update_option('ts_vcsc_extend_settings_tinymceCustomDate',		'');
			update_option('ts_vcsc_extend_settings_tinymceCustomPHP',		'');
        }
    }
    if (!function_exists('TS_VCSC_RemoveDirectory')){
        function TS_VCSC_RemoveDirectory($directory, $empty = false) { 
            if (substr($directory, -1) == "/") { 
                $directory = substr($directory, 0, -1); 
            } 
            if (!file_exists($directory) || !is_dir($directory)) { 
                return false;
            } elseif (!is_readable($directory)) { 
                return false; 
            } else { 
                $directoryHandle = opendir($directory); 
                while ($contents = readdir($directoryHandle)) { 
                    if ($contents != '.' && $contents != '..') { 
                        $path = $directory . "/" . $contents;
                        if (is_dir($path)) { 
                            TS_VCSC_RemoveDirectory($path); 
                        } else { 
                            unlink($path); 
                        } 
                    } 
                } 
                closedir($directoryHandle); 
                if ($empty == false) { 
                    if (!rmdir($directory)) { 
                        return false; 
                    } 
                }
                return true; 
            } 
        }
    }
	
	// Other Required Functions
	// ------------------------
    if (!function_exists('TS_VCSC_cURLcheckBasicFunctions')){
        function TS_VCSC_cURLcheckBasicFunctions() {
            if( !function_exists("curl_init") &&
                !function_exists("curl_setopt") &&
                !function_exists("curl_exec") &&
                !function_exists("curl_close") ) return false;
            else return true;
        }
    }
	if (!function_exists('TS_VCSC_CreatePreloaderCSS')){
		function TS_VCSC_CreatePreloaderCSS($id, $class, $style, $enqueue) {
			$preloader 						= '';
			$style 							= intval($style);			
			if ($style > -1) {
				if ($enqueue == "true") {
					wp_enqueue_style('ts-extend-preloaders');
				}
				$spancount 					= 0;
				$spandatas					= array(
					0 => 0, 1 => 5, 2 => 4, 3 => 0, 4 => 5, 5 => 0, 6 => 4, 7 => 4, 8 => 0, 9 => 4,
					10 => 0, 11 => 2, 12 => 2, 13 => 1, 14 => 5, 15 => 3, 16 => 6, 17 => 6, 18 => 3, 19 => 0,
					20 => 4, 21 => 5, 22 => 0,
				);
				$spancount 					= (isset($spandatas[$style]) ? $spandatas[$style] : 0);
				$preloader .= '<div id="' . $id . '" class="' . $class . ' ts-preloader-animation-main ts-preloader-animation-' . $style . '">';
					for ($x = 1; $x <= $spancount; $x++) {
						$preloader .= '<span></span>';
					}
				$preloader .= '</div>';
			}
			return $preloader;
		}
	}
    if (!function_exists('TS_VCSC_GetTheCategoryByTax')){
        function TS_VCSC_GetTheCategoryByTax($id = false, $tcat = 'category') {
            $categories = get_the_terms($id, $tcat);
            if ((!$categories) || is_wp_error($categories)) {
                $categories = array();
            }
            $categories = array_values($categories);
            foreach (array_keys($categories) as $key) {
                _make_cat_compat($categories[$key]);
            }
            return apply_filters('get_the_categories', $categories);
        }
    }
	if (!function_exists('TS_VCSC_CheckIfServerIsLocalHost')){
		function TS_VCSC_CheckIfServerIsLocalHost() {
			$whitelist = array("localhost", "127.0.0.1", "::1");
			if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
				return true;
			} else {
				return false;
			}
		}
	}
?>