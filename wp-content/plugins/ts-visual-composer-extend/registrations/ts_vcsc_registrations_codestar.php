<?php
    global $VISUAL_COMPOSER_EXTENSIONS;
	
	// Register Custom Fields with Codestar
	// ------------------------------------
	add_action('cs_load_option_fields', 'TS_VCSC_Codestar_CustomFields');
	if (!function_exists('TS_VCSC_Codestar_CustomFields')) {
		function TS_VCSC_Codestar_CustomFields() {
			global $VISUAL_COMPOSER_EXTENSIONS;
			require_once($VISUAL_COMPOSER_EXTENSIONS->registrations_dir . 'ts_vcsc_registrations_fields.php');
		}
	}
	
	// Function to Migrate Custom Post Types from CMB to Codestar
	// ----------------------------------------------------------
	if (!function_exists('TS_VCSC_Codestar_Migrate_ImageID')){
		function TS_VCSC_Codestar_Migrate_GetImageID ($url) {
			$attachment_id 									= 0;
			$dir 											= wp_upload_dir();
			if (false !== strpos($url, $dir['baseurl'] . '/' )) {
				$file 										= basename( $url );
				$query_args = array(
					'post_type'   							=> 'attachment',
					'post_status' 							=> 'inherit',
					'fields'      							=> 'ids',
					'meta_query'  							=> array(
						array(
							'value'   	=> $file,
							'compare' 	=> 'LIKE',
							'key'     	=> '_wp_attachment_metadata',
						),
					)
				);
				$query 										= new WP_Query( $query_args );
				if ($query->have_posts()) {
					foreach ($query->posts as $post_id) {
						$meta 								= wp_get_attachment_metadata($post_id);
						$original_file       				= basename($meta['file']);
						$cropped_image_files 				= wp_list_pluck($meta['sizes'], 'file');
						if ($original_file === $file || in_array($file, $cropped_image_files)) {
							$attachment_id 					= $post_id;
							break;
						}
					}
				}
			}
			return $attachment_id;
		}
	}
	if (!function_exists('TS_VCSC_Codestar_Migrate_Routine')){
		function TS_VCSC_Codestar_Migrate_Routine($metaID, $metaType, $metaOld, $metaSwitch, $metaGallery, $metaImage, $metaNew, $metaLevel, $metaDone, $metaDelete, $metaForce, $metaOutput) {
			$metaDataDone 									= get_post_meta($metaID, 'ts_vcsc_custompost_migrated', true);
			if ((empty($metaDataDone)) || ($metaForce)) {
				$metaDataNew 								= get_post_meta($metaID, $metaNew);				
				$metaMigrate								= array();
				if ((is_array($metaOld)) && ((empty($metaDataNew)) || ($metaForce))) {
					foreach ($metaOld as $old) {
						$metaDataOld						= get_post_meta($metaID, $old);						
						if ((!empty($metaDataOld)) && ((empty($metaDataNew)) || ($metaForce))) {
							if ($metaOutput) {
								if (!is_array($metaDataOld[$metaLevel])) {
									echo $old . ' / ' . $metaDataOld[$metaLevel] . '<br/>';
								} else {
									echo $old . ' / ' . print_r($metaDataOld[$metaLevel]) . '<br/>';
								}
							}
							if (is_array($metaDataOld[$metaLevel])) {
								if (in_array($old, $metaGallery)) {
									$metaDataGallery		= array();
									foreach ($metaDataOld[$metaLevel] as $id => $path) {
										$metaDataGallery[] 	= $id;
									}
									$metaMigrate[$old]		= implode(",", $metaDataGallery);
								} else {
									$metaMigrate[$old]		= $metaDataOld[$metaLevel];
								}
							} else {
								if ((in_array($old, $metaSwitch)) && (($metaDataOld[$metaLevel] === "on") || ($metaDataOld[$metaLevel] === "1") || ($metaDataOld[$metaLevel] === 1) || ($metaDataOld[$metaLevel] === "true") || ($metaDataOld[$metaLevel] === true))) {
									$metaMigrate[$old] 		= true;
								} else if ((in_array($old, $metaSwitch)) && (($metaDataOld[$metaLevel] === "off") || ($metaDataOld[$metaLevel] === "0") || ($metaDataOld[$metaLevel] === 0) || ($metaDataOld[$metaLevel] === "false") || ($metaDataOld[$metaLevel] === false))) {
									$metaMigrate[$old] 		= false;
								} else if (in_array($old, $metaImage)) {
									$metaMigrate[$old]		= TS_VCSC_Codestar_Migrate_GetImageID($metaDataOld[$metaLevel]);
								} else {
									$metaMigrate[$old]		= $metaDataOld[$metaLevel];
								}
							}
						}
					}
					update_post_meta($metaID, $metaNew, $metaMigrate);					
					update_post_meta($metaID, 'ts_vcsc_custompost_migrated', array($metaDone => 'true'));
					if ($metaDelete){
						delete_post_meta($metaID, $metaOld);
					}
				}
			}
		}
	}

	// Functions to Sanitize/Validate Inputs
	// -------------------------------------
	if (!function_exists('TS_VCSC_Codestar_Validate_Email')) {
		function TS_VCSC_Codestar_Validate_Email($value, $field) {
			if (($value != '') && (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $value))) {
				return __('Please provide a valid email address!', 'ts_visual_composer_extend');
			}
		}
		add_filter('cs_validate_emailaddress', 'TS_VCSC_Codestar_Validate_Email', 10, 2);
	}
	if (!function_exists('TS_VCSC_Codestar_Validate_URL')) {
		function TS_VCSC_Codestar_Validate_URL($value, $field) {
			if (($value != '') && (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $value))) {
				return __('Please provide a valid URL address, starting with http(s)!', 'ts_visual_composer_extend');
			}
		}
		add_filter('cs_validate_urladdress', 'TS_VCSC_Codestar_Validate_URL', 10, 2);
	}
?>