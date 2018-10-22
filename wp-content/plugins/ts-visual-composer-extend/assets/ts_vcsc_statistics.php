<?php
	global $VISUAL_COMPOSER_EXTENSIONS;

    if (!class_exists('TS_Shortcodes_Statistics_Compile')) {
        class TS_Shortcodes_Statistics_Compile {
			/* ----------------------- */
			/* Define Global Variables */
			/* ----------------------- */
			public $TS_VCSC_Shortcodes_BaseData 					= array();
			public $TS_VCSC_Shortcodes_ElementData					= array();
			public $TS_VCSC_Shortcodes_PostTypes					= array();
			public $TS_VCSC_Shortcodes_PostPretty					= array();
			public $TS_VCSC_Shortcodes_Statistics					= array();
			public $TS_VCSC_Shortcodes_ScanUnits					= 0;
			public $TS_VCSC_Shortcodes_ScanTotal					= 0;
			public $TS_VCSC_Shortcodes_ScanStep						= 0;
			public $TS_VCSC_Shortcodes_ScanProgress					= 0;
			public $TS_VCSC_Shortcodes_ScanProcessed				= 0;
			public $TS_VCSC_Shortcodes_ScanFinished					= array();
			public $TS_VCSC_Shortcodes_QueryLoops					= 0;
			public $TS_VCSC_Shortcodes_QueryPages					= 1;
			public $TS_VCSC_Shortcodes_QuerySteps					= 50;
			public $TS_VCSC_Shortcodes_QueryCount					= 0;
			public $TS_VCSC_Shortcodes_QueryArray					= array();
			public $TS_VCSC_Shortcodes_QueryResult					= array();
			public $TS_VCSC_Shortcodes_Progressbar;
			
			/* --------------- */
			/* Construct Class */
			/* --------------- */
            function __construct() {}
			
			/* ------------------------ */
			/* Build Helper Data Arrays */
			/* ------------------------ */
			function TS_VCSC_StatisticsConstructor($progressbar = true) {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Standard Shortcodes
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Elements as $ElementName => $element) {
					if ($element['base'] != "") {
						array_push($this->TS_VCSC_Shortcodes_BaseData, $element['base']);
						$this->TS_VCSC_Shortcodes_ElementData[$element['base']] = array(
							'prettyname' 		=> $ElementName,
							'active' 			=> $element['active'],
							'deprecated'		=> $element['deprecated'],
							'group'				=> $element['group'],
							'introduced'		=> $element['introduced'],
							'retired'			=> $element['retired'],
							'type'				=> 'standard',
						);
					}
				}
				// Add Post Type Shortcodes
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Types as $ElementName => $element) {
					if ($element['base'] != "") {
						array_push($this->TS_VCSC_Shortcodes_BaseData, $element['base']);
						$this->TS_VCSC_Shortcodes_ElementData[$element['base']] = array(
							'prettyname' 		=> $ElementName,
							'active' 			=> $element['active'],
							'deprecated'		=> $element['deprecated'],
							'group'				=> $element['group'],
							'introduced'		=> $element['introduced'],
							'retired'			=> $element['retired'],
							'type'				=> 'posttype',
						);
					}
				}
				// Add Extra Shortcodes
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Extra as $ElementName => $element) {
					if ($element['base'] != "") {
						array_push($this->TS_VCSC_Shortcodes_BaseData, $element['base']);
						$this->TS_VCSC_Shortcodes_ElementData[$element['base']] = array(
							'prettyname' 		=> $ElementName,
							'active' 			=> $element['active'],
							'deprecated'		=> $element['deprecated'],
							'group'				=> $element['group'],
							'introduced'		=> $element['introduced'],
							'retired'			=> $element['retired'],
							'type'				=> 'extra',
						);
					}
				}
				// Add Demo Shortcodes
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Demos as $ElementName => $element) {
					if ($element['base'] != "") {
						array_push($this->TS_VCSC_Shortcodes_BaseData, $element['base']);
						$this->TS_VCSC_Shortcodes_ElementData[$element['base']] = array(
							'prettyname' 		=> $ElementName,
							'active' 			=> $element['active'],
							'deprecated'		=> $element['deprecated'],
							'group'				=> $element['group'],
							'introduced'		=> $element['introduced'],
							'retired'			=> $element['retired'],
							'type'				=> 'demo',
						);
					}
				}
				// Add WooCommerce Shortcodes
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_WooCommerce_Elements as $ElementName => $element) {
					if ($element['base'] != "") {
						array_push($this->TS_VCSC_Shortcodes_BaseData, $element['base']);
						$this->TS_VCSC_Shortcodes_ElementData[$element['base']] = array(
							'prettyname' 		=> $ElementName,
							'active' 			=> $element['active'],
							'deprecated'		=> $element['deprecated'],
							'group'				=> $element['group'],
							'introduced'		=> $element['introduced'],
							'retired'			=> $element['retired'],
							'type'				=> 'woocommerce',
						);
					}
				}
				// Add bbPress Shortcodes
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_bbPress_Elements as $ElementName => $element) {
					if ($element['base'] != "") {
						array_push($this->TS_VCSC_Shortcodes_BaseData, $element['base']);
						$this->TS_VCSC_Shortcodes_ElementData[$element['base']] = array(
							'prettyname' 		=> $ElementName,
							'active' 			=> $element['active'],
							'deprecated'		=> $element['deprecated'],
							'group'				=> $element['group'],							
							'introduced'		=> $element['introduced'],
							'retired'			=> $element['retired'],
							'type'				=> 'bbpress',
						);
					}
				}
				// Get All Registered Page + Post Types				
				foreach (get_post_types('', 'names') as $post_type) {
					if (($post_type != 'attachment') && ($post_type != 'revision') && ($post_type != 'nav_menu_item')) {
						$this->TS_VCSC_Shortcodes_PostTypes[]				= $post_type;
						$this->TS_VCSC_Shortcodes_PostPretty[$post_type] 	= get_post_type_object($post_type)->labels->singular_name;
					}
				}
				// Start Scan Routine
				$this->TS_VCSC_StatisticsMainRoutine($progressbar);
			}
			
			/* ---------------------------- */
			/* Initialize Main Scan Routine */
			/* ---------------------------- */
			function TS_VCSC_StatisticsMainRoutine($progressbar) {
				$this->TS_VCSC_Shortcodes_ScanUnits				= array();
				$this->TS_VCSC_Shortcodes_ScanTotal				= 0;
				$this->TS_VCSC_Shortcodes_ScanProgress			= 0;
				$this->TS_VCSC_Shortcodes_ScanProcessed			= 0;
				foreach ($this->TS_VCSC_Shortcodes_PostTypes as $type) {
					$this->TS_VCSC_Shortcodes_ScanTotal 		= wp_count_posts($type);
					$this->TS_VCSC_Shortcodes_ScanUnits[$type] 	= $this->TS_VCSC_Shortcodes_ScanTotal->publish;
				}
				$this->TS_VCSC_Shortcodes_ScanTotal				= 0;
				foreach ($this->TS_VCSC_Shortcodes_ScanUnits as $units) {
					$this->TS_VCSC_Shortcodes_ScanTotal			= $this->TS_VCSC_Shortcodes_ScanTotal + $units;
				}
				$this->TS_VCSC_Shortcodes_ScanStep				= 100 / $this->TS_VCSC_Shortcodes_ScanTotal;
				// Initialize Progressbar
				if ($progressbar) {
					$this->TS_VCSC_Shortcodes_Progressbar 		= new TS_VCSC_Animated_Progressbar();
					$this->TS_VCSC_Shortcodes_Progressbar->TS_VCSC_ProgressbarNewText("Processing all pages and posts ...");
					$this->TS_VCSC_Shortcodes_Progressbar->TS_VCSC_ProgressbarCreate();
				}
				// Create Default Data
				foreach ($this->TS_VCSC_Shortcodes_BaseData as $shortcode) {
					$this->TS_VCSC_Shortcodes_Statistics[$shortcode] = array(
						'ids' 			=> array(),
						'names'			=> array(),
						'source'		=> array(),
						'total' 		=> 0,
					);
				}
				// Create Reusable Variables
				$this->TS_VCSC_Shortcodes_QueryCount			= 0;
				$this->TS_VCSC_Shortcodes_QueryArray			= array();
				$this->TS_VCSC_Shortcodes_QueryLoops			= ceil($this->TS_VCSC_Shortcodes_ScanTotal / $this->TS_VCSC_Shortcodes_QuerySteps);
				// Initialize WordPress Query
				$args         			= array(
					'post_type'   		=> $this->TS_VCSC_Shortcodes_PostTypes,
					'post_status' 		=> 'publish',
					'posts_per_page' 	=> ($this->TS_VCSC_Shortcodes_ScanTotal < $this->TS_VCSC_Shortcodes_QuerySteps ? $this->TS_VCSC_Shortcodes_ScanTotal : $this->TS_VCSC_Shortcodes_QuerySteps),
					'nopaging' 			=> false,
					'paged' 			=> $this->TS_VCSC_Shortcodes_QueryPages,
				);
				$this->TS_VCSC_StatisticsInitQuery($args, $progressbar);
				// Reset Variables
				unset($this->TS_VCSC_Shortcodes_QueryResult);
				unset($this->TS_VCSC_Shortcodes_QueryArray);
				unset($this->TS_VCSC_Shortcodes_QueryCount);
				// Return Data
				update_option('ts_vcsc_extend_settings_statisticsLastCheck',				strtotime(date('Y/m/d H:i:s')));
				update_option('ts_vcsc_extend_settings_statisticsUsageData',				$this->TS_VCSC_Shortcodes_Statistics);
				update_option('ts_vcsc_extend_settings_statisticsElements',					$this->TS_VCSC_Shortcodes_ElementData);
			}			
			
			/* -------------------------------- */
			/* Initialize (New) WordPress Query */
			/* -------------------------------- */
			function TS_VCSC_StatisticsInitQuery($args, $progressbar) {				
				// Initialize WordPress Query
				$this->TS_VCSC_Shortcodes_QueryResult = new WP_Query($args);
				wp_reset_query();
				// Process WordPress Query
				$this->TS_VCSC_StatisticsProcessQuery($progressbar);
			}
			
			/* ----------------------------- */
			/* Process (New) WordPress Query */
			/* ----------------------------- */
			function TS_VCSC_StatisticsProcessQuery($progressbar) {
				// Retrieve Global Variables
				if ($this->TS_VCSC_Shortcodes_QueryResult->have_posts()) {
					$this->TS_VCSC_Shortcodes_QueryLoops--;
					$this->TS_VCSC_Shortcodes_QueryPages++;
					foreach ($this->TS_VCSC_Shortcodes_QueryResult->posts as $post) {
						$this->TS_VCSC_Shortcodes_ScanProcessed++;
						$this->TS_VCSC_Shortcodes_ScanProgress										= $this->TS_VCSC_Shortcodes_ScanProgress + $this->TS_VCSC_Shortcodes_ScanStep;
						if (!in_array($post->ID, $this->TS_VCSC_Shortcodes_ScanFinished)) {
							$this->TS_VCSC_Shortcodes_ScanFinished[]								= $post->ID;
							foreach ($this->TS_VCSC_Shortcodes_BaseData as $shortcode) {
								$this->TS_VCSC_Shortcodes_QueryCount								= substr_count($post->post_content, '[' . $shortcode . ' ');
								if ($this->TS_VCSC_Shortcodes_QueryCount > 0) {
									$this->TS_VCSC_Shortcodes_QueryArray							= is_array($this->TS_VCSC_Shortcodes_Statistics[$shortcode]['ids']) ? $this->TS_VCSC_Shortcodes_Statistics[$shortcode]['ids'] : array();
									$this->TS_VCSC_Shortcodes_QueryArray[] 							= $post->ID;
									$this->TS_VCSC_Shortcodes_Statistics[$shortcode]['ids'] 		= $this->TS_VCSC_Shortcodes_QueryArray;							
									$this->TS_VCSC_Shortcodes_QueryArray							= is_array($this->TS_VCSC_Shortcodes_Statistics[$shortcode]['names']) ? $this->TS_VCSC_Shortcodes_Statistics[$shortcode]['names'] : array();
									$this->TS_VCSC_Shortcodes_QueryArray[] 							= $post->post_title;
									$this->TS_VCSC_Shortcodes_Statistics[$shortcode]['names'] 		= $this->TS_VCSC_Shortcodes_QueryArray;
									$this->TS_VCSC_Shortcodes_Statistics[$shortcode]['total'] 		= $this->TS_VCSC_Shortcodes_Statistics[$shortcode]['total'] + 1;
									$this->TS_VCSC_Shortcodes_Statistics[$shortcode]['source'][]	= array(
										'id' 			=> $post->ID,
										'type' 			=> $post->post_type,
										'title' 		=> $post->post_title,
										'edit'			=> get_edit_post_link($post->ID),
										'view'			=> $post->guid,
										'count' 		=> $this->TS_VCSC_Shortcodes_QueryCount,
										'used'			=> ($this->TS_VCSC_Shortcodes_QueryCount > 0 ? "true" : "false"),
									);
								};
							}
							if ($progressbar) {
								$this->TS_VCSC_Shortcodes_Progressbar->TS_VCSC_ProgressbarNewText("Processing " . $this->TS_VCSC_Shortcodes_PostPretty[$post->post_type] . " ID #" . $post->ID . " (" . $this->TS_VCSC_Shortcodes_ScanProcessed . " of " . $this->TS_VCSC_Shortcodes_ScanTotal . ") ...");
								$this->TS_VCSC_Shortcodes_Progressbar->TS_VCSC_ProgressbarCalculate($this->TS_VCSC_Shortcodes_ScanTotal);
								$this->TS_VCSC_Shortcodes_Progressbar->TS_VCSC_ProgressbarAnimate();
							}
						}						
					}
					if ($this->TS_VCSC_Shortcodes_QueryLoops > 0) {
						$args         			= array(
							'post_type'   		=> $this->TS_VCSC_Shortcodes_PostTypes,
							'post_status' 		=> 'publish',
							'posts_per_page' 	=> ($this->TS_VCSC_Shortcodes_ScanTotal < $this->TS_VCSC_Shortcodes_QuerySteps ? $this->TS_VCSC_Shortcodes_ScanTotal : $this->TS_VCSC_Shortcodes_QuerySteps),
							'nopaging' 			=> false,
							'paged' 			=> $this->TS_VCSC_Shortcodes_QueryPages,
						);
						$this->TS_VCSC_StatisticsInitQuery($args, $progressbar);
					} else {
						if ($progressbar) {
							$this->TS_VCSC_Shortcodes_Progressbar->TS_VCSC_ProgressbarHide();
						}
					}
				}
			}
        }
    }
	
    if (!class_exists('TS_Shortcodes_Statistics_Autoset')) {
        class TS_Shortcodes_Statistics_Autoset {
			/* ----------------------- */
			/* Define Global Variables */
			/* ----------------------- */
			public $TS_VCSC_Shortcodes_Compile;
			public $TS_VCSC_Shortcodes_BaseData 					= array();
			public $TS_VCSC_Shortcodes_ElementData					= array();
			public $TS_VCSC_Shortcodes_PostTypes					= array();
			public $TS_VCSC_Shortcodes_UsePattern					= array();
			public $TS_VCSC_Shortcodes_Elements						= array();
			public $TS_VCSC_Extension_PostTypes						= array();
			public $TS_VCSC_Extension_PostCount						= 0;
			
			/* --------------- */
			/* Construct Class */
			/* --------------- */
			function __construct() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Retrieve Last Scan Results
				$this->TS_VCSC_Shortcodes_UsePattern 				= get_option('ts_vcsc_extend_settings_statisticsUsageData', array());
				$this->TS_VCSC_Shortcodes_Elements 					= get_option('ts_vcsc_extend_settings_statisticsElements', array());
				// Get Post Types
				$this->TS_VCSC_Statistics_GetPostTypes();
				// Reset Elements
				$this->TS_VCSC_Statistics_SetElements();
				// Reset Post Types
				$this->TS_VCSC_Statistics_SetPostTypes();
				// Conduct New Statistic Compilation
				if (class_exists('TS_Shortcodes_Statistics_Compile')) {
					$TS_VCSC_Shortcodes_Compile 					= new TS_Shortcodes_Statistics_Compile();
				}
				$TS_VCSC_Shortcodes_Compile->TS_VCSC_StatisticsConstructor(false);
				// Reset Element Stati Array
				$this->TS_VCSC_Statistics_SetStati();
			}
			
			/* -------------------------------- */
			/* Get Post Type Data + Build Array */
			/* -------------------------------- */
			function TS_VCSC_Statistics_GetPostTypes() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PostTypeMenuNames_Default as $PostType => $type) {
					$this->TS_VCSC_Extension_PostTypes[$PostType] 							= array(
						'option' 	=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PostTypeOptionNames_Default[$PostType],
						'count' 	=> 0,
					);
				}
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Types as $ElementName => $element) {
					$this->TS_VCSC_Extension_PostCount										= $this->TS_VCSC_Extension_PostTypes[$element['posttype']]['count'];
					$this->TS_VCSC_Extension_PostTypes[$element['posttype']]['count']		= $this->TS_VCSC_Shortcodes_UsePattern[$element['base']]['total'] + $this->TS_VCSC_Extension_PostCount;
				}
			}
			
			/* --------------------------------- */
			/* Update Data Arrays With New Stati */
			/* --------------------------------- */
			function TS_VCSC_Statistics_SetStati() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Standard Shortcodes
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Elements as $ElementName => $element) {
					if ($element['base'] != "") {
						array_push($this->TS_VCSC_Shortcodes_BaseData, $element['base']);
						$this->TS_VCSC_Shortcodes_ElementData[$element['base']] = array(
							'prettyname' 		=> $ElementName,
							'active' 			=> ($this->TS_VCSC_Shortcodes_UsePattern[$element['base']]['total'] > 0 ? "true" : "false"),
							'deprecated'		=> $element['deprecated'],
							'group'				=> $element['group'],
							'type'				=> 'standard',
						);
					}
				}
				// Add Post Type Shortcodes
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Types as $ElementName => $element) {
					if ($element['base'] != "") {
						array_push($this->TS_VCSC_Shortcodes_BaseData, $element['base']);
						$this->TS_VCSC_Shortcodes_ElementData[$element['base']] = array(
							'prettyname' 		=> $ElementName,
							'active' 			=> ($this->TS_VCSC_Extension_PostTypes[$element['posttype']]['count'] > 0 ? "true" : "false"),
							'deprecated'		=> $element['deprecated'],
							'group'				=> $element['group'],
							'type'				=> 'posttype',
						);
					}
				}
				// Add Extra Shortcodes
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Extra as $ElementName => $element) {
					if ($element['base'] != "") {
						array_push($this->TS_VCSC_Shortcodes_BaseData, $element['base']);
						$this->TS_VCSC_Shortcodes_ElementData[$element['base']] = array(
							'prettyname' 		=> $ElementName,
							'active' 			=> ($this->TS_VCSC_Shortcodes_UsePattern[$element['base']]['total'] > 0 ? "true" : "false"),
							'deprecated'		=> $element['deprecated'],
							'group'				=> $element['group'],
							'type'				=> 'extra',
						);
					}
				}
				// Add Demo Shortcodes
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Demos as $ElementName => $element) {
					if ($element['base'] != "") {
						array_push($this->TS_VCSC_Shortcodes_BaseData, $element['base']);
						$this->TS_VCSC_Shortcodes_ElementData[$element['base']] = array(
							'prettyname' 		=> $ElementName,
							'active' 			=> ($this->TS_VCSC_Shortcodes_UsePattern[$element['base']]['total'] > 0 ? "true" : "false"),
							'deprecated'		=> $element['deprecated'],
							'group'				=> $element['group'],
							'type'				=> 'demo',
						);
					}
				}
				// Add WooCommerce Shortcodes
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_WooCommerce_Elements as $ElementName => $element) {
					if ($element['base'] != "") {
						array_push($this->TS_VCSC_Shortcodes_BaseData, $element['base']);
						$this->TS_VCSC_Shortcodes_ElementData[$element['base']] = array(
							'prettyname' 		=> $ElementName,
							'active' 			=> ($this->TS_VCSC_Shortcodes_UsePattern[$element['base']]['total'] > 0 ? "true" : "false"),
							'deprecated'		=> $element['deprecated'],
							'group'				=> $element['group'],
							'type'				=> 'woocommerce',
						);
					}
				}
				// Add bbPress Shortcodes
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_bbPress_Elements as $ElementName => $element) {
					if ($element['base'] != "") {
						array_push($this->TS_VCSC_Shortcodes_BaseData, $element['base']);
						$this->TS_VCSC_Shortcodes_ElementData[$element['base']] = array(
							'prettyname' 		=> $ElementName,
							'active' 			=> ($this->TS_VCSC_Shortcodes_UsePattern[$element['base']]['total'] > 0 ? "true" : "false"),
							'deprecated'		=> $element['deprecated'],
							'group'				=> $element['group'],
							'type'				=> 'bbpress',
						);
					}
				}
				// Update WordPress Option
				update_option('ts_vcsc_extend_settings_statisticsElements',					$this->TS_VCSC_Shortcodes_ElementData);
			}
			
			/* --------------------------- */
			/* Set New Status For Elements */
			/* --------------------------- */
			function TS_VCSC_Statistics_SetElements() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Set Standard + 3rd Party Plugin Elements
				$TS_VCSC_Extension_Elements 												= array();
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Elements as $ElementName => $element) {
					if (array_key_exists($element['base'], $this->TS_VCSC_Shortcodes_UsePattern)) {
						if ($this->TS_VCSC_Shortcodes_UsePattern[$element['base']]['total'] == 0) {
							$TS_VCSC_Extension_Elements[$element['setting']] 				= 0;
						} else {
							$TS_VCSC_Extension_Elements[$element['setting']] 				= 1;
						}
					} else {
						$TS_VCSC_Extension_Elements[$element['setting']] 					= 0;
					}
				}
				update_option('ts_vcsc_extend_settings_StandardElements',					$TS_VCSC_Extension_Elements);
				// Set Extra Elements
				$TS_VCSC_Extension_Enlighter 												= 0;
				$TS_VCSC_Extension_Navigator 												= 0;
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Extra as $ElementName => $element) {
					if (array_key_exists($element['base'], $this->TS_VCSC_Shortcodes_UsePattern)) {
						if ($element['feature'] == 'Enlighter') {
							if ($this->TS_VCSC_Shortcodes_UsePattern[$element['base']]['total'] > 0) {
								$TS_VCSC_Extension_Enlighter++;
							}
						} else if ($element['feature'] == 'Navigator') {
							if ($this->TS_VCSC_Shortcodes_UsePattern[$element['base']]['total'] > 0) {
								$TS_VCSC_Extension_Navigator++;
							}
						}
					}
				}
				if ($TS_VCSC_Extension_Enlighter > 0) {
					update_option('ts_vcsc_extend_settings_allowEnlighterJS', 				1);
				} else {
					update_option('ts_vcsc_extend_settings_allowEnlighterJS', 				0);
				}
				if ($TS_VCSC_Extension_Navigator > 0) {
					update_option('ts_vcsc_extend_settings_allowPageNavigator', 			1);
				} else {
					update_option('ts_vcsc_extend_settings_allowPageNavigator', 			0);
				}
				// Set Demo Elements
				$TS_VCSC_Extension_Demos													= array();
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Demos as $ElementName => $element) {
					if (array_key_exists($element['base'], $this->TS_VCSC_Shortcodes_UsePattern)) {
						if ($this->TS_VCSC_Shortcodes_UsePattern[$element['base']]['total'] == 0) {
							$TS_VCSC_Extension_Demos[$element['setting']] 					= 0;
						} else {
							$TS_VCSC_Extension_Demos[$element['setting']] 					= 1;
						}
					} else {
						$TS_VCSC_Extension_Demos[$element['setting']] 						= 0;
					}
				}
				update_option('ts_vcsc_extend_settings_DemoElements',						$TS_VCSC_Extension_Demos);				
				// Set WooCommerce Elements
				$TS_VCSC_WooCommerce_Elements 												= array();
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_WooCommerce_Elements as $ElementName => $element) {
					if (array_key_exists($element['base'], $this->TS_VCSC_Shortcodes_UsePattern)) {
						if ($this->TS_VCSC_Shortcodes_UsePattern[$element['base']]['total'] == 0) {
							$TS_VCSC_WooCommerce_Elements[$element['setting']] 				= 0;
						} else {
							$TS_VCSC_WooCommerce_Elements[$element['setting']] 				= 1;
						}
					} else {
						$TS_VCSC_WooCommerce_Elements[$element['setting']] 					= 0;
					}
				}
				update_option('ts_vcsc_extend_settings_WooCommerceElements',				$TS_VCSC_WooCommerce_Elements);				
				// Set bbPress Elements
				$TS_VCSC_bbPress_Elements 													= array();
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_bbPress_Elements as $ElementName => $element) {
					if (array_key_exists($element['base'], $this->TS_VCSC_Shortcodes_UsePattern)) {
						if ($this->TS_VCSC_Shortcodes_UsePattern[$element['base']]['total'] == 0) {
							$TS_VCSC_bbPress_Elements[$element['setting']] 					= 0;
						} else {
							$TS_VCSC_bbPress_Elements[$element['setting']] 					= 1;
						}
					} else {
						$TS_VCSC_bbPress_Elements[$element['setting']] 						= 0;
					}
				}
				update_option('ts_vcsc_extend_settings_bbPressElements',					$TS_VCSC_bbPress_Elements);
			}
			
			/* ----------------------------- */
			/* Set New Status For Post Types */
			/* ----------------------------- */
			function TS_VCSC_Statistics_SetPostTypes() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Visual_Composer_Types as $ElementName => $element) {
					if (array_key_exists($element['base'], $this->TS_VCSC_Shortcodes_UsePattern)) {
						if ($this->TS_VCSC_Extension_PostTypes[$element['posttype']]['count'] == 0) {
							update_option($this->TS_VCSC_Extension_PostTypes[$element['posttype']]['option'],		0);
						} else {
							update_option($this->TS_VCSC_Extension_PostTypes[$element['posttype']]['option'],		1);
						}
					} else {
						update_option($this->TS_VCSC_Extension_PostTypes[$element['posttype']]['option'],			0);
					}
				}
			}
		}
	}
	
	if (isset($_POST['Statistics'])) {
		require_once("ts_vcsc_progressbar.php");
		echo '<div id="ts_vcsc_extend_settings_save" style="position: relative; margin: 20px auto 20px auto; width: 128px; height: 128px;">';
			echo TS_VCSC_CreatePreloaderCSS("ts-settings-panel-loader", "", 19, "false");
		echo '</div>';
		echo '<div class="ts-settings-statistics-compile-message">Compiling Elements Usage Summary</div>';
		if (class_exists('TS_Shortcodes_Statistics_Compile')) {
			$TS_Shortcodes_Statistics_Compile 						= new TS_Shortcodes_Statistics_Compile();
		}
		$TS_Shortcodes_Statistics_Compile->TS_VCSC_StatisticsConstructor();
		echo '<script>window.location="' . $_SERVER['REQUEST_URI'] . '";</script> ';
		Exit();
	} else if (isset($_POST['Autosettings'])) {
		require_once("ts_vcsc_progressbar.php");
		echo '<div id="ts_vcsc_extend_settings_save" style="position: relative; margin: 20px auto 20px auto; width: 128px; height: 128px;">';
			echo TS_VCSC_CreatePreloaderCSS("ts-settings-panel-loader", "", 4, "false");
		echo '</div>';
		if (class_exists('TS_Shortcodes_Statistics_Autoset')) {
			$TS_Shortcodes_Statistics_Autoset 						= new TS_Shortcodes_Statistics_Autoset();
		}
		echo '<script> window.location="' . $_SERVER['REQUEST_URI'] . '"; </script> ';
		Exit();
	} else {
		$TS_VCSC_Shortcodes_LastCheck 								= get_option('ts_vcsc_extend_settings_statisticsLastCheck', "");
		$TS_VCSC_Shortcodes_UsePattern 								= get_option('ts_vcsc_extend_settings_statisticsUsageData', array());
		$TS_VCSC_Shortcodes_Elements 								= get_option('ts_vcsc_extend_settings_statisticsElements', array());
		if (($TS_VCSC_Shortcodes_UsePattern == "") || (!is_array($TS_VCSC_Shortcodes_UsePattern))) {
			$TS_VCSC_Shortcodes_Validator							= "false";
		} else if (count($TS_VCSC_Shortcodes_UsePattern) == 0) {
			$TS_VCSC_Shortcodes_Validator							= "false";
		} else {
			$TS_VCSC_Shortcodes_Validator							= "true";
		}
		if ($TS_VCSC_Shortcodes_Validator == "false") {
			$TS_VCSC_Shortcodes_UsePattern							= array();
			$TS_VCSC_Shortcodes_LastCheck							= "";
			$TS_VCSC_Shortcodes_Elements							= array();
		}
		if ($TS_VCSC_Shortcodes_LastCheck != "") {
			$TS_VCSC_Shortcodes_LastCheck							= date("Y/m/d h:i:s A", $TS_VCSC_Shortcodes_LastCheck);
		}
		$TS_VCSC_Shortcodes_DeprecatedUsed							= 0;
		$TS_VCSC_Shortcodes_InactiveUsed							= 0;
		$TS_VCSC_Shortcodes_InactivePostType						= 0;
		$TS_VCSC_Shortcodes_ActiveUnused							= 0;
		$TS_VCSC_Shortcodes_ActivePostType							= 0;
		foreach ($TS_VCSC_Shortcodes_UsePattern as $Shortcode => $statistics) {
			if ( $TS_VCSC_Shortcodes_UsePattern[$Shortcode]['total'] > 0) {
				if ($TS_VCSC_Shortcodes_Elements[$Shortcode]['deprecated'] == "true") {
					$TS_VCSC_Shortcodes_DeprecatedUsed++;
				}
				if ($TS_VCSC_Shortcodes_Elements[$Shortcode]['active'] == "false") {
					$TS_VCSC_Shortcodes_InactiveUsed++;
					if ($TS_VCSC_Shortcodes_Elements[$Shortcode]['group'] == "Post Types") {
						$TS_VCSC_Shortcodes_InactivePostType++;
					}
				}
			} else if ($TS_VCSC_Shortcodes_Elements[$Shortcode]['active'] == "true") {
				$TS_VCSC_Shortcodes_ActiveUnused++;
				if ($TS_VCSC_Shortcodes_Elements[$Shortcode]['group'] == "Post Types") {
					$TS_VCSC_Shortcodes_ActivePostType++;
				}
			}
		}
	}
?>
<div class="ts-vcsc-settings-group-header">
	<div class="display_header">
		<h2><span class="dashicons dashicons-image-filter"></span>Composium - WP Bakery Page Builder Extensions v<?php echo TS_VCSC_GetPluginVersion(); ?> ... Element Usage Statistic</h2>
	</div>
	<div class="clear"></div>
</div>
<div id="ts-settings-generator" style="display: block;">
	<div class="ts-vcsc-icon-preview-wrap" style="margin-top: 0px;">
		<div class="ts-vcsc-section-main">
			<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-info"></i>Element Usage Statistic</div>
			<div class="ts-vcsc-section-content">	
				<?php
					if (current_user_can('manage_options')) {
						echo '<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder" style="margin: 10px auto;">
							<span class="ts-advanced-link-tooltip-content">' . __("Click here to return to the plugins settings page.", "ts_visual_composer_extend") . '</span>
							<a href="' . $VISUAL_COMPOSER_EXTENSIONS->settingsLink . '" target="_parent" class="ts-advanced-link-button-main ts-advanced-link-button-grey ts-advanced-link-button-settings">'. __("Back to Settings", "ts_visual_composer_extend") . '</a>
						</div>';
					}
				?>		
				<div class="ts-vcsc-notice-field ts-vcsc-success" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					Due to the modular framework of this plugin, you can enable and disable elements and features based on your needs. Naturally, the less elements and features you activate, the less resources this plugin requires, and the better its overall performance. The information shown below will give you a summary about which elements and features (based on their shortcodes), you are actually utiziling on your pages and posts. You can use that information and cross reference with the elements and features you have currently enabled, so you might be able to disable additional elements or features that you are not actually utilzing.
				</div>	
			</div>		
		</div>
		<div class="ts-vcsc-section-main">
			<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-admin-generic"></i>Compile New Statistic</div>
			<div class="ts-vcsc-section-content">	
				<?php					
					echo '<div id="ts-settings-statistics-lastcheck" style="margin-top: 10px;">';
						if ($TS_VCSC_Shortcodes_LastCheck != "") {
							echo '<div style="font-weight: bold;"><i class="ts-settings-statistics-compile-icondate"></i>Last Statistic Check: ' . $TS_VCSC_Shortcodes_LastCheck . '</div>';
						} else {
							echo '<div style="color: #c51414; font-weight: bold;"><i class="ts-settings-statistics-compile-iconerror"></i>No element usage statistic has been generated yet!</div>';
						}
					echo '</div>';
				?>
				<form id="ts-settings-statistics-compile-form" class="ts-settings-statistics-compile-form" data-lastscan="<?php echo $TS_VCSC_Shortcodes_LastCheck; ?>" name="oscimp_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" style="margin-bottom: 10px;">
					<span id="gallery_settings_true" style="display: none !important; margin-bottom: 20px;">
						<input type="text" style="width: 20%;" id="ts-settings-statistics-compile-true" name="ts-settings-statistics-compile-true" value="0" size="100">
					</span>
					<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
						Depending upon the total number of pages and posts on your site, the compilation of the usage statistic can take a short while. Please let the procedure finish and do not refresh this page while the compilation is still in process. For performance reason, the scan will only check for standalone and container elements, but not child elements, as child elements can not exist without the matching container element.
					</div>	
					<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder" style="margin: 5px 0 10px 0;">
						<span class="ts-advanced-link-tooltip-content"><?php _e("Click here to compile a new statistic and summary for usage of WP Bakery Page Builder elements from this plugin.", "ts_visual_composer_extend"); ?></span>
						<button type="submit" name="Statistics" id="ts-settings-statistics-compile-trigger" class="ts-advanced-link-button-main ts-advanced-link-button-blue ts-advanced-link-button-update">
							<?php _e("New Usage Statistic", "ts_visual_composer_extend"); ?>
						</button>
					</div>				
				</form>				
				<form id="ts-settings-statistics-autoset-form" class="ts-settings-statistics-autoset-form" data-lastscan="<?php echo $TS_VCSC_Shortcodes_LastCheck; ?>" name="oscimp_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" style="display: <?php echo ($TS_VCSC_Shortcodes_Validator == "true" ? "block" : "none"); ?>; margin-bottom: 10px;">
					<span id="gallery_settings_true" style="display: none !important; margin-bottom: 20px;">
						<input type="text" style="width: 20%;" id="ts-settings-statistics-autoset-true" name="ts-settings-statistics-autoset-true" value="0" size="100">
					</span>
					<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
						The last compilation scan revealed that you have currently some elements and/or post types activated, that are not actually used on any published page or post. Deactivating those elements and/or post types can improve overall site performance. Using the button below, the plugin will disable those elements and/or post types automatically, based on the results of the last compilation scan that occured on
						<?php echo $TS_VCSC_Shortcodes_LastCheck; ?>.						
					</div>
					<div class="ts-settings-statistics-autoset-message">There are <strong><?php echo $TS_VCSC_Shortcodes_DeprecatedUsed; ?></strong> <span style="color: #c51414; font-weight: bold;">deprecated</span> elements that are still used throughout your website. <span style="color: #c51414; display: <?php echo ($TS_VCSC_Shortcodes_DeprecatedUsed > 0 ? "inline-block" : "none"); ?>;">You should switch those elements to their newer replacement versions.</span></div>
					<div class="ts-settings-statistics-autoset-message">There are <strong><?php echo $TS_VCSC_Shortcodes_InactiveUsed; ?></strong> <span style="color: #0099d5; font-weight: bold;">inactive</span> elements (<?php echo $TS_VCSC_Shortcodes_InactivePostType; ?> of which are post type dependent) that are actually still used throughout your website. <span style="color: #0099d5; display: <?php echo ($TS_VCSC_Shortcodes_InactiveUsed > 0 ? "inline-block" : "none"); ?>;">You should activate those elements so they can be edited with WP Bakery Page Builder again.</span></div>
					<div class="ts-settings-statistics-autoset-message">There are <strong><?php echo $TS_VCSC_Shortcodes_ActiveUnused; ?></strong> <span style="color: #7ad03a; font-weight: bold;">active</span> elements (<?php echo $TS_VCSC_Shortcodes_ActivePostType; ?> of which are post type dependent) that are currently NOT used throughout your website. <span style="color: #7ad03a; display: <?php echo (($TS_VCSC_Shortcodes_ActiveUnused - $TS_VCSC_Shortcodes_ActivePostType) > 0 ? "inline-block" : "none"); ?>;">You should deactivate those elements in order to free up valuable system resources.</span></div>
					<div class="ts-vcsc-notice-field ts-vcsc-success" style="margin-top: 10px; font-size: 13px; text-align: justify; display: <?php echo ($TS_VCSC_Shortcodes_ActivePostType > 0 ? "block" : "none"); ?>;">
						Some <span style="color: #7ad03a; font-weight: bold;">active</span> elements (<?php echo $TS_VCSC_Shortcodes_ActivePostType; ?>) are tied to custom post types, which often times provide multiple elements in order to use that post type within WP Bakery Page Builder. If at least one of those post type associated elements is used, you will not be able to deactivate the other elements, as long as the post type itself remains active. Using the button below might therefore not be able to deactivate all currently unused but active elements, it those elements are tied to a custom post type that has at least one other of its elements utilized.
					</div>
					<div class="ts-advanced-link-button-wrapper ts-advanced-link-tooltip-holder" style="margin: 20px 0 10px 0; display: <?php echo (($TS_VCSC_Shortcodes_ActiveUnused - $TS_VCSC_Shortcodes_ActivePostType + $TS_VCSC_Shortcodes_InactiveUsed) > 0 ? "block" : "none"); ?>;">
						<span class="ts-advanced-link-tooltip-content"><?php _e("Click here to automatically implement most of the suggestions listed above, by (de)activating all (un)used elements and post types.", "ts_visual_composer_extend"); ?></span>
						<button type="submit" name="Autosettings" id="ts-settings-statistics-autoset-trigger" class="ts-advanced-link-button-main ts-advanced-link-button-orange ts-advanced-link-button-wrench">
							<?php _e("Apply Suggestions", "ts_visual_composer_extend"); ?>
						</button>
					</div>
				</form>
			</div>		
		</div>
		<div class="ts-vcsc-section-main" style="display: <?php echo ($TS_VCSC_Shortcodes_Validator == "true" ? "block" : "none"); ?>">
			<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-image-filter"></i>Element Usage Statistics</div>
			<div class="ts-vcsc-section-content clearFixMe">
				<div class="ts-vcsc-notice-field ts-vcsc-info" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					The table below represents a listing of all available elements that are part of this plugin. Clicking on any line will reveal further information, such as the number of pages an element is currently used on,
					as well as direct edit links for all pages that currently include the element. You are also able to sort the table by columns, and filter/search by keywords, making it easy to find a particular element you
					are interested in.
				</div>	
				<?php
					$TS_VCSC_Shortcodes_UniGroups										= array();
					if ($TS_VCSC_Shortcodes_Validator == "true") {
						foreach ($TS_VCSC_Shortcodes_UsePattern as $Shortcode => $statistics) {
							if (!in_array($TS_VCSC_Shortcodes_Elements[$Shortcode]['group'], $TS_VCSC_Shortcodes_UniGroups)) {
								array_push($TS_VCSC_Shortcodes_UniGroups, $TS_VCSC_Shortcodes_Elements[$Shortcode]['group']);
							}
						}
					}
					echo '<div id="ts-settings-statistics-groups" id="ts-settings-statistics-groups" style="display: none;" data-groups="' . implode(",", $TS_VCSC_Shortcodes_UniGroups) . '"></div>';
				?>
				<div class="ts-settings-statistics-table ts-datatables-container ts-datatables-theme-yellow">
					<table id="ts-settings-statistics-usage" class="ts-settings-statistics-usage ts-datatables-tablemain responsive display" style="width: 100%; max-width: 100%;">
						<thead>
							<tr class="ts-datatables-column-header">
								<th data-priority="1" data-orderable="false" data-searchable="false" class="all"></th>
								<th data-priority="1" data-type="string" data-orderable="true" data-searchable="true" class="all">Element Name</th>
								<th data-priority="5" data-type="string" data-orderable="true" data-searchable="true">Shortcode</th>
								<th data-priority="3" data-type="string" data-orderable="true"data-searchable="true">Group</th>
								<th data-priority="4" data-type="string" data-orderable="true" data-searchable="true">Active</th>
								<th data-priority="6" data-type="string" data-orderable="true" data-searchable="true">Deprecated</th>
								<th data-priority="2" data-type="num" data-searchable="false">Total Count</th>
								<th data-priority="7" data-type="num" data-orderable="false" data-searchable="false" class="none">Introduced</th>
								<th data-priority="8" data-type="num" data-orderable="false" data-searchable="false" class="none">Retired</th>
								<th data-priority="9" data-type="num" data-orderable="false" data-searchable="false" class="none">Pages / Posts</th>
								<th data-priority="10" data-type="html" data-orderable="false" data-searchable="false" class="none">ID Edit Link(s)</th>
							</tr>
						</thead>
						<tfoot>
							<tr class="ts-datatables-column-filter">
								<th><span class="ts-datatables-iconsearch"></span></th>
								<th><input class="ts-datatables-column-search" type="text" placeholder="Search Elements ..."/></th>
								<th><input class="ts-datatables-column-search" type="text" placeholder="Search Shortcodes ..."/></th>
								<th><select class="ts-datatables-column-select"><option value="">All</option></select></th>
								<th><select class="ts-datatables-column-select"><option value="">All</option></select></th>
								<th><select class="ts-datatables-column-select"><option value="">All</option></select></th>
								<th><span class="ts-datatables-iconminus"></span></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
						</tfoot>
						<tbody>
							<?php	
								if ($TS_VCSC_Shortcodes_Validator == "true") {
									$TS_VCSC_Shortcodes_UnitIDs								= array();
									$TS_VCSC_Shortcodes_UnitLinks							= array();
									$TS_VCSC_Shortcodes_TotalUsage							= 0;
									foreach ($TS_VCSC_Shortcodes_UsePattern as $Shortcode => $statistics) {
										$TS_VCSC_Shortcodes_PageIDs							= '';
										$TS_VCSC_Shortcodes_UnitIDs							= array();
										$TS_VCSC_Shortcodes_UnitLinks						= array();
										$TS_VCSC_Shortcodes_TotalUsage						= 0;
										if (is_array($TS_VCSC_Shortcodes_UsePattern[$Shortcode]['source'])) {
											foreach ($TS_VCSC_Shortcodes_UsePattern[$Shortcode]['source'] as $source) {										
												$TS_VCSC_Shortcodes_TotalUsage				= $TS_VCSC_Shortcodes_TotalUsage + $source['count'];
												$TS_VCSC_Shortcodes_UnitIDs[]				= array(
													'id' 		=> $source['id'],
													'link' 		=> '<a href="' . $source['edit'] . '" target="_blank" title="' . $source['title'] . ' (' . $source['count'] . 'x)">' . $source['id'] . '</a>',
												);
											}
										}
										TS_VCSC_SortMultiArray($TS_VCSC_Shortcodes_UnitIDs, 'id');
										foreach ($TS_VCSC_Shortcodes_UnitIDs as $unit) {
											$TS_VCSC_Shortcodes_UnitLinks[]					= $unit['link'];
										}
										$TS_VCSC_Shortcodes_UnitLinks						= trim(implode(", ", $TS_VCSC_Shortcodes_UnitLinks));
										if ($TS_VCSC_Shortcodes_UnitLinks == "") {
											$TS_VCSC_Shortcodes_UnitLinks					= "-";
										}
										echo '<tr data-units="' . $TS_VCSC_Shortcodes_UsePattern[$Shortcode]['total'] . '" data-total="' . $TS_VCSC_Shortcodes_TotalUsage . '">';
											echo '<td></td>';
											echo '<td>' . $TS_VCSC_Shortcodes_Elements[$Shortcode]['prettyname'] . '</td>';
											echo '<td>[' . $Shortcode . ']</td>';
											echo '<td>' . $TS_VCSC_Shortcodes_Elements[$Shortcode]['group'] . '</td>';
											echo '<td>' . $TS_VCSC_Shortcodes_Elements[$Shortcode]['active'] . '</td>';
											if (($TS_VCSC_Shortcodes_UsePattern[$Shortcode]['total'] > 0) && ($TS_VCSC_Shortcodes_Elements[$Shortcode]['deprecated'] == "true")) {
												echo '<td style="color: #c51414; font-weight: bold;">' . $TS_VCSC_Shortcodes_Elements[$Shortcode]['deprecated'] . '</td>';
											} else {
												echo '<td>' . $TS_VCSC_Shortcodes_Elements[$Shortcode]['deprecated'] . '</td>';
											}									
											echo '<td>' . $TS_VCSC_Shortcodes_TotalUsage . '</td>';
											echo '<td>' . ($TS_VCSC_Shortcodes_Elements[$Shortcode]['introduced'] != "" ? "v" . $TS_VCSC_Shortcodes_Elements[$Shortcode]['introduced'] : "N/A") . '</td>';
											echo '<td>' . ($TS_VCSC_Shortcodes_Elements[$Shortcode]['retired'] != "" ? "v" . $TS_VCSC_Shortcodes_Elements[$Shortcode]['retired'] : "No") . '</td>';
											echo '<td>' . $TS_VCSC_Shortcodes_UsePattern[$Shortcode]['total'] . '</td>';
											echo '<td>' . $TS_VCSC_Shortcodes_UnitLinks . '</td>';
										echo '</tr>';
									}
								}
							?>
						</tbody>
					</table>
				</div>
			</div>		
		</div>
	</div>
</div>