<?php
    // Create Custom Messages
    function TS_VCSC_Downpages_Post_Messages($messages) {
		global $post, $post_ID;
		$post_type = get_post_type( $post_ID );
		$obj = get_post_type_object($post_type);
		$singular = $obj->labels->singular_name;
		$messages[$post_type] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __($singular.' updated.')),
			2 => __('Custom field updated.'),
			3 => __('Custom field deleted.'),
			4 => __($singular.' updated.'),
			5 => isset($_GET['revision']) ? sprintf( __($singular.' restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __($singular.' published.')),
			7 => __('Page saved.'),
			8 => sprintf( __($singular.' submitted.')),
			9 => sprintf( __($singular.' scheduled for: <strong>%1$s</strong>.'), date_i18n( __('M j, Y @ G:i'), strtotime($post->post_date))),
			10 => sprintf( __($singular.' draft updated.')),
		);
		return $messages;
    }
    
    // Add Content for Contextual Help Section
    function TS_VCSC_Downpages_Post_Help( $contextual_help, $screen_id, $screen ) { 
        if ( 'edit-ts_downtime' == $screen->id ) {
            $contextual_help = '<h2>Downpages</h2>
            <p>Downpages are shown to your visitors whenever you place your website into downtime/maintenance mode, during which the "normal" website will be hidden from the visitor.</p> 
            <p>You can view/edit the content of each downpage by clicking on its name, or you can perform bulk actions using the dropdown menu and selecting multiple items.</p>';
        } else if ('ts_downtime' == $screen->id) {
            $contextual_help = '<h2>Editing Downpages</h2>
            <p>This page allows you to create a custom page which can be shown to your visitors once you place your website into downtime or maintenance mode.</p>';
        }
        return $contextual_help;
    }
	
	// Add Custom Metaboxes to Post Type
	function TS_VCSC_Downpages_Codestar(array $meta_boxes) {
		global $pagenow;		
		$screen 								= TS_VCSC_GetCurrentPostType();
		$prefixA 								= 'ts_vcsc_downpages_layout_';
		$prefixB 								= 'ts_vcsc_downpages_meta_';
		$prefixC 								= 'ts_vcsc_downpages_theme_';		
		
		// Migration of Old Metadata for Existing Posts
		if (($screen == 'ts_downtime') && ($pagenow == 'post.php')) {
			$metaOld = array(
				'ts_vcsc_downpages_layout_boxed',
				'ts_vcsc_downpages_layout_width',
				'ts_vcsc_downpages_layout_maxwidth',
				'ts_vcsc_downpages_layout_background',
				'ts_vcsc_downpages_layout_fontcolor',
				'ts_vcsc_downpages_layout_spacing',
				'ts_vcsc_downpages_layout_margins',
				'ts_vcsc_downpages_layout_paddingv',
				'ts_vcsc_downpages_layout_paddingh',				
				'ts_vcsc_downpages_meta_titlesource',
				'ts_vcsc_downpages_meta_titlecustom',
				'ts_vcsc_downpages_meta_infosource',
				'ts_vcsc_downpages_meta_infocustom',				
				'ts_vcsc_downpages_theme_css',
				'ts_vcsc_downpages_theme_js',
			);
			$metaSwitch							= array($prefixA . 'boxed', $prefixA . 'spacing', $prefixB . 'css', $prefixB . 'js',);
			$metaGallery						= array();
			$metaImage							= array();
			if (function_exists('TS_VCSC_Codestar_Migrate_Routine')){
				TS_VCSC_Codestar_Migrate_Routine(get_the_ID(), 'ts_downtime', $metaOld, $metaSwitch, $metaGallery, $metaImage, 'ts_vcsc_downpages_layout', 0, 'ts_vcsc_downpages_migrated', false, false, false);
			}
		}
		
		// Configure Metabox - Downpages
		if (($screen == 'ts_downtime') && ($pagenow == 'post-new.php' || $pagenow == 'post.php')) {
			$meta_boxes[]      					= array(
				'id'            				=> 'ts_vcsc_downpages_layout',
				'title'         				=> 'Downpage Settings',
				'post_type'     				=> 'ts_downtime',
				'context'       				=> 'normal',
				'priority'      				=> 'high',
				'sections'      				=> array(
					// Page Layout
					array(
						'name'      			=> $prefixA . 'section',
						'title'     			=> 'Page Layout',
						'icon'      			=> 'fa fa-columns',
						'fields'    			=> array(
							array(
								'type'    		=> 'heading',
								'content' 		=> 'Page Layout',
							),
							array(
								'type'    		=> 'notice',
								'class'   		=> 'warning',
								'content' 		=> '"Composium - Visual Composer Extensions" provides an element "TS Page Background", which will allow you to easily assign a custom background (color, gradient, image, etc.) to your custom
								downpage. You might have to enable the element in the plugin settings first, before you can use it.',
							),
							array(
								'id'      		=> $prefixA . 'boxed',
								'type'    		=> 'switcher',
								'title'   		=> 'Boxed Layout',
								'default' 		=> true
							),

							array(
								'id' 			=> $prefixA . 'width',
								'type' 			=> 'number',
								'title' 		=> 'Box - Standard Width:',
								'desc' 			=> 'Define the standard width of the content box as percent of the overall available screen width.',
								'default' 		=> '80',
								'attributes' 	=> array(
									'min'			=> 50,
									'max'			=> 98,
								),
								'dependency'   	=> array($prefixA . 'boxed', '==', 'true'),
							),
							array(
								'id' 			=> $prefixA . 'maxwidth',
								'type' 			=> 'number',
								'title' 		=> 'Box - Maximum Width:',
								'desc' 			=> 'Define the maximum width of the content box as a fixed pixel value.',
								'default' 		=> '1280',
								'attributes' 	=> array(
									'min'			=> 640,
									'max'			=> 1980,
								),
								'dependency'   	=> array($prefixA . 'boxed', '==', 'true'),
							),
						)
					),
					// Content Styling
					array(
						'name'      			=> $prefixA . 'content',
						'title'     			=> 'Content Styling',
						'icon'      			=> 'fa fa-css3',
						'fields'    			=> array(
							array(
								'type'    		=> 'heading',
								'content' 		=> 'Content Styling',
							),
							array(
								'id' 			=> $prefixA . 'background',
								'type' 			=> 'color_picker',
								'title' 		=> 'Content - Background Color:',
								'desc' 			=> 'Define the background color for the content section (NOT the page background).',
								'default'  		=> '#ffffff',
							),
							array(
								'id' 			=> $prefixA . 'fontcolor',
								'type' 			=> 'color_picker',
								'title' 		=> 'Content - Font Color:',
								'desc' 			=> 'Define the global font color for the content section.',
								'default'  		=> '#696969',
							),
							array(
								'id'      		=> $prefixA . 'spacing',
								'type'   		=> 'switcher',
								'title'    		=> 'Content - Margins:',
								'default' 		=> true,
								'desc' 			=> 'Check the box if you want to apply a top/bottom margin (spacing) to the boxed container.',
							),
							array(
								'id' 			=> $prefixA . 'margins',
								'type' 			=> 'number',
								'title' 		=> 'Margin - Top/Bottom:',
								'desc' 			=> 'Define the outer top and bottom margin for the content section.',
								'default' 		=> '50',
								'attributes' 	=> array(
									'min'			=> 1,
									'max'			=> 100,
								),
								'dependency'   	=> array($prefixA . 'spacing', '==', 'true'),
							),
							array(
								'id' 			=> $prefixA . 'paddingv',
								'type' 			=> 'number',
								'title' 		=> 'Padding - Top/Bottom:',
								'desc' 			=> 'Define the inner top and bottom padding for the content section.',
								'default' 		=> '20',
								'attributes' 	=> array(
									'min'			=> 1,
									'max'			=> 100,
								),
							),
							array(
								'id' 			=> $prefixA . 'paddingh',
								'type' 			=> 'number',
								'title' 		=> 'Padding - Left/Right:',
								'desc' 			=> 'Define the inner left and right padding for the content section.',
								'default' 		=> '20',
								'attributes' 	=> array(
									'min'			=> 1,
									'max'			=> 100,
								),
							),
						),
					),
					// Page MetaData
					array(
						'name'      			=> $prefixB . 'data',
						'title'     			=> 'Page Meta Data',
						'icon'      			=> 'fa fa-info-circle',
						'fields'    			=> array(
							array(
								'type'    		=> 'heading',
								'content' 		=> 'Page Meta Data',
							),
							array(
								'type'    		=> 'subheading',
								'content' 		=> 'Provide some custom meta data for your downpage, to be used insted of the default information that you defined in the standard WordPress settings.',
							),
							array(
								'type' 			=> 'notice',
								'class'			=> 'warning',
								'content' 		=> "By default, the downpage will retrieve certain meta data, such as page title or page description (tag line), from the apprpriate WordPress settings. If you want to use different information
												for this downpage, you can use the corresponding inputs below.",
							),
							array(
								'id'             => $prefixB . 'titlesource',
								'type'           => 'radio',
								'title'          => 'Title Source:',
								'options'        => array(
									'site'   		=> 'Site Title',
									'page' 			=> 'Page Title',									
									'custom'     	=> 'Custom Title',
								),
								'attributes' => array(
									'data-depend-id' 	=> $prefixB . 'titlesource',
								),
								'help'    			=> 'Select the title source that should be used for this downpage.',
								'default' 			=> 'site',
							),
							array(
								'id' 			=> $prefixB . 'titlecustom',
								'type' 			=> 'text',
								'title' 		=> 'Custom Title:',
								'help' 			=> 'Enter the custom title that should be used for this downpage.',
								'dependency'   	=> array($prefixB . 'titlesource', '==', 'custom'),
							),
							array(
								'id'             => $prefixB . 'infosource',
								'type'           => 'radio',
								'title'          => 'Description Source:',
								'options'        => array(
									'site'   		=> 'Site Description',									
									'custom'     	=> 'Custom Description',
								),
								'attributes' => array(
									'data-depend-id' 	=> $prefixB . 'infosource',
								),
								'help'    			=> 'Select the description source that should be used for this downpage.',
								'default' 			=> 'site',
							),
							array(
								'id'            => $prefixB . 'infocustom',
								'type'          => 'textarea',
								'title'         => 'Custom Description:',
								'help'          => 'Enter the custom page description (tag line) that should be used for this downpage.',
								'attributes'    => array(
									'placeholder' 	=> '',
									'rows'        	=> 5,
								),
								'dependency'   	=> array($prefixB . 'infosource', '==', 'custom'),
							),							
						),
					),
					// Theme Files
					array(
						'name'      			=> $prefixC . 'files',
						'title'     			=> 'Theme Files',
						'icon'      			=> 'fa fa-file-code-o',
						'fields'    			=> array(
							array(
								'type'    		=> 'heading',
								'content' 		=> 'Theme Files',
							),
							array(
								'type'    		=> 'subheading',
								'content' 		=> 'When in downtime mode, your website will bypass your theme completely and only render this page, without any menu or other theme dependent features!',
							),
							array(
								'type' 			=> 'notice',
								'class'			=> 'warning',
								'content' 		=> "The downtime mode will therefore prevent your theme from loading it's respective CSS and JS files. If your theme is providing it's own elements for Visual Composer and those elements
												are not placed into a dedicated plugin (as required by Envato), you might need to load the theme's CSS and/or JS files in order to correctly render/style those elements. Beware that some theme
												JS files 'expect' certain theme features (like menus) to be present and might therefore produce JS errors if those features do not exist anymore, due to the downtime mode bypassing the theme.",
							),
							array(
								'id'      		=> $prefixC . 'css',
								'type'   		=> 'switcher',
								'title'    		=> 'Load CSS Files:',
								'default' 		=> 'false',
								'desc' 			=> 'Check the box if you want to load the CSS files that your theme would load on normal pages.',
							),
							array(
								'id'      		=> $prefixC . 'js',
								'type'   		=> 'switcher',
								'title'    		=> 'Load JS Files:',
								'default' 		=> 'false',
								'desc' 			=> 'Check the box if you want to load the JS files that your theme would load on normal pages.',
							),
						),
					),
				),
			);
			// Hidden Migration Setting
			$meta_boxes[]      				= array(
				'id'            			=> 'ts_vcsc_custompost_migrated',
				'title'         			=> 'Downpage Migration',
				'post_type'     			=> 'ts_downtime',
				'context'       			=> 'normal',
				'priority'      			=> 'high',
				'sections'      			=> array(
					// Migration Section
					array(
						'name'      		=> 'ts_vcsc_custompost_section',
						'title'     		=> 'Downpage Migration',
						'icon'      		=> 'fa fa-check-square-o',
						'fields'    		=> array(
							array(
								'id'		=> 'ts_vcsc_downpages_migrated',
								'type'    	=> 'inputhidden',
								'title'		=> 'Migration Success:',
								'default' 	=> 'true',
							),
						),
					),	
				),
			);
		};		
		return $meta_boxes;
	}
	
	// Load Required JS+CSS Files
	function TS_VCSC_Downpages_Post_Files() {
		global $pagenow;
		$screen = TS_VCSC_GetCurrentPostType();
		if ($screen=='ts_downtime') {
			if ($pagenow=='post-new.php' || $pagenow=='post.php') {
				if (!wp_script_is('jquery')) {
					wp_enqueue_script('jquery');
				}
				wp_enqueue_style('ts-font-teammates');
				wp_enqueue_style('ts-extend-select2');
				wp_enqueue_script('ts-extend-select2');
				wp_enqueue_style('ts-extend-posttypes');
				wp_enqueue_script('ts-extend-posttypes');
			}
		}
	}
	
	// Custom Posts Columns + Output
	function TS_VCSC_Downpages_PostsColumnsOrder($columns) {
		$new_columns['cb'] 			= '<input type="checkbox" />';
		$new_columns['title'] 		= _x('Title', 'ts_visual_composer_extend');
		$new_columns['usedfor'] 	= __('Used As Downpage For');		 
		$new_columns['category'] 	= __('Categories');
		$new_columns['date'] 		= _x('Date', 'ts_visual_composer_extend');	 
		return $new_columns;
	}
	function TS_VCSC_Downpages_PostsColumnsOutput($column_name, $id) {
		global $wpdb;
		global $VISUAL_COMPOSER_EXTENSIONS;
		switch ($column_name) {
			case 'usedfor':
				$Page_Userfor		= array();
				$Page_Single		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['singlepage'];
				if ($Page_Single == 1) {
					$Page_All		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['alldevices'];
					if ($id == $Page_All) {
						array_push($Page_Userfor, "All Devices");
					}
				} else {
					$Page_Desktop	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['desktop'];
					$Page_Tablet	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['tablet'];
					$Page_Mobile	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['mobile'];
					if ($id == $Page_Desktop) {
						array_push($Page_Userfor, "Desktop Devices");
					}
					if ($id == $Page_Tablet) {
						array_push($Page_Userfor, "Tablet Devices");
					}
					if ($id == $Page_Mobile) {
						array_push($Page_Userfor, "Mobile Devices");
					}
				}
				$Page_Userfor		= implode(", ", $Page_Userfor);
				if ($Page_Userfor != '') {
					echo $Page_Userfor;
				} else {
					echo '-';
				}
				break;
			case 'category':
				$categories 		= TS_VCSC_GetCategoriesCustomPost($id, 'ts_downtime_category');
				$categories 		= (array) $categories;
				$output				= array();
				foreach ($categories as $category => $data) {
					$data 			= (array) $data;
					$link			= admin_url('edit.php?post_type=ts_downtime&ts_downtime_category=' . $data['slug']);
					array_push($output, '<a href="' . $link . '">' . $data['name'] . '</a>');
				}
				echo implode(", ", $output);
				break;
			default:
				break;
		}
	}
	
	// Remove RevSlider + Essential Grid Metaboxes
	function TS_VCSC_Downpages_RemoveExternalMetaboxes() { 
		global $pagenow;
		$screen = TS_VCSC_GetCurrentPostType();
		if ($screen=='ts_downtime') {
			if ($pagenow=='post-new.php' || $pagenow=='post.php') {
				remove_meta_box('eg-meta-box', 'ts_downtime', 'normal'); 
				remove_meta_box('mymetabox_revslider_0', 'ts_downtime', 'normal'); 
			} 
		} 
	}
	
	// Call All Routines
	if (is_admin()) {
		add_filter('post_updated_messages', 						'TS_VCSC_Downpages_Post_Messages');
		add_action('contextual_help', 								'TS_VCSC_Downpages_Post_Help', 					10, 3);
		add_filter('cs_metabox_options', 							'TS_VCSC_Downpages_Codestar');
		add_action('admin_enqueue_scripts',							'TS_VCSC_Downpages_Post_Files', 				9999999999);
		add_action('add_meta_boxes', 								'TS_VCSC_Downpages_RemoveExternalMetaboxes', 	9999999999);
		add_filter('manage_edit-ts_downtime_columns', 				'TS_VCSC_Downpages_PostsColumnsOrder') ;
		add_action('manage_ts_downtime_posts_custom_column', 		'TS_VCSC_Downpages_PostsColumnsOutput', 		10, 2);
	}
?>