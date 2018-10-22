<?php
    // Create Custom Messages
    function TS_VCSC_Skillsets_Post_Messages($messages) {
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
    function TS_VCSC_Skillsets_Post_Help( $contextual_help, $screen_id, $screen ) { 
        if ( 'edit-ts_skillsets' == $screen->id ) {
            $contextual_help = '<h2>Skillsets</h2>
            <p>Skillsets are an easy way to display feedback you received from your customers or to show any other quotes on your website.</p> 
            <p>You can view/edit the details of each testimonial by clicking on its name, or you can perform bulk actions using the dropdown menu and selecting multiple items.</p>';
        } else if ('ts_skillsets' == $screen->id) {
            $contextual_help = '<h2>Editing Skillsets</h2>
            <p>This page allows you to view/modify testimonial details. Please make sure to fill out the available boxes with the appropriate details. Skillset information can only be used with the Visual Composer Extensions Plugin.</p>';
        }
        return $contextual_help;
    }
	
	// Add Custom Metaboxes to Post Type
	function TS_VCSC_Skillsets_Codestar(array $meta_boxes) {
		global $pagenow;		
		$screen 								= TS_VCSC_GetCurrentPostType();
		$prefixA 								= 'ts_vcsc_skillset_basic_';

		// Migration of Old Metadata for Existing Posts
		if (($screen == 'ts_skillsets') && ($pagenow == 'post.php')) {
			$metaOld						= array($prefixA . 'group');
			$metaSwitch						= array();
			$metaGallery					= array();
			$metaImage						= array();
			if (function_exists('TS_VCSC_Codestar_Migrate_Routine')){
				TS_VCSC_Codestar_Migrate_Routine(get_the_ID(), 'ts_skillsets', $metaOld, $metaSwitch, $metaGallery, $metaImage, 'ts_vcsc_skillset_basic', 0, 'ts_vcsc_skillset_migrated', false, false, false);
			}
		}

		// Configure Metaboxes - Skillsets
		if (($screen == 'ts_skillsets') && ($pagenow == 'post-new.php' || $pagenow == 'post.php')) {			
			$meta_boxes[]      				= array(
				'id'            			=> 'ts_vcsc_skillset_basic',
				'title'         			=> 'Skill Sets',
				'post_type'     			=> 'ts_skillsets',
				'context'       			=> 'normal',
				'priority'      			=> 'low',
				'sections'      			=> array(
					// Skillsets Section
					array(
						'name'      		=> $prefixA . 'section',
						'title'     		=> 'Skillsets',
						'icon'      		=> 'fa fa-align-left',
						'fields'    		=> array(
							array(
								'type'    	=> 'subheading',
								'content' 	=> 'Add as many skill levels as you need by using the "Add New Skill" button.',
							),
							array(
								'type'    	=> 'notice',
								'class'   	=> 'warning',
								'content' 	=> 'Skills can also be re-ordered by simply dragging them to their desired position, or removed by using the appropriate button within each skill set.',
							),
							array(
								'id'              	=> $prefixA . 'group',
								'type'            	=> 'group',
								'title'           	=> 'Skill Sets',
								'button_title'    	=> 'Add New Skill',
								'accordion_title' 	=> 'New Skill',
								'fields'          	=> array(
									array(
										'id'    	=> 'skillname',
										'type'  	=> 'text',
										'title' 	=> 'Skill Name:',
									),
									array(
										'id'    	=> 'skillvalue',
										'type'  	=> 'number',
										'title' 	=> 'Skill Value in %:',
									),
									array(
										'id'    	=> 'skillcolor',
										'type'  	=> 'color_picker',
										'title' 	=> 'Skill Color:',
										'default'	=> '#00afd1',
									),
								),
								'default'			=> array(
									array(
									  'skillname'	=> 'My Awesome Skill',
									  'skillvalue'	=> 99,
									  'skillcolor'	=> '#00afd1',
									),
								),
							),
						),
					),			
				),
			);
			// Hidden Migration Setting
			$meta_boxes[]      				= array(
				'id'            			=> 'ts_vcsc_custompost_migrated',
				'title'         			=> 'Skillset Migration',
				'post_type'     			=> 'ts_skillsets',
				'context'       			=> 'normal',
				'priority'      			=> 'high',
				'sections'      			=> array(
					// Migration Section
					array(
						'name'      		=> 'ts_vcsc_custompost_section',
						'title'     		=> 'Skillset Migration',
						'icon'      		=> 'fa fa-check-square-o',
						'fields'    		=> array(
							array(
								'id'		=> 'ts_vcsc_skillset_migrated',
								'type'    	=> 'inputhidden',
								'title'		=> 'Migration Success:',
								'default' 	=> 'true',
							),
						),
					),	
				),
			);
		}
		return $meta_boxes;
	}
	
	// Load Required JS+CSS Files
	function TS_VCSC_Skillsets_Post_Files() {
		global $pagenow;
		$screen = TS_VCSC_GetCurrentPostType();
		if ($screen=='ts_skillsets') {
			if ($pagenow=='post-new.php' || $pagenow=='post.php') {
				if (!wp_script_is('jquery')) {
					wp_enqueue_script('jquery');
				}
				wp_enqueue_style('ts-font-teammates');
				wp_enqueue_style('ts-extend-posttypes');
				wp_enqueue_script('ts-extend-posttypes');
			}
		}
	}
	
	// Remove RevSlider + Essential Grid Metaboxes
	function TS_VCSC_Skillsets_RemoveExternalMetaboxes() { 
		global $pagenow;
		$screen = TS_VCSC_GetCurrentPostType();
		if ($screen=='ts_skillsets') {
			if ($pagenow=='post-new.php' || $pagenow=='post.php') {
				remove_meta_box('eg-meta-box', 'ts_skillsets', 'normal'); 
				remove_meta_box('mymetabox_revslider_0', 'ts_skillsets', 'normal'); 
			} 
		} 
	}
	
	// Call All Routines
	if (is_admin()) {
		add_filter('post_updated_messages', 						'TS_VCSC_Skillsets_Post_Messages');
		add_action('contextual_help', 								'TS_VCSC_Skillsets_Post_Help', 					10, 3);
		add_filter('cs_metabox_options', 							'TS_VCSC_Skillsets_Codestar');
		add_action('admin_enqueue_scripts',							'TS_VCSC_Skillsets_Post_Files', 				9999999999);
		add_action('add_meta_boxes', 								'TS_VCSC_Skillsets_RemoveExternalMetaboxes', 	9999999999);
	}
?>