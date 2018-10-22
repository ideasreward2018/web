<?php
    // Create Custom Messages
    function TS_VCSC_Logos_Post_Messages($messages) {
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
    function TS_VCSC_Logos_Post_Help( $contextual_help, $screen_id, $screen ) { 
        if ( 'edit-ts_logos' == $screen->id ) {
            $contextual_help = '<h2>Logos</h2>
            <p>Logos are an easy way to display customers you provided work for, partner businesses on your website.</p> 
            <p>You can view/edit the details of each logo by clicking on its name, or you can perform bulk actions using the dropdown menu and selecting multiple items.</p>';
        } else if ('ts_logos' == $screen->id) {
            $contextual_help = '<h2>Editing Logos</h2>
            <p>This page allows you to view/modify logo details. Please make sure to fill out the available boxes with the appropriate details. Logo information can only be used with the Visual Composer Extensions Plugin.</p>';
        }
        return $contextual_help;
    }
	
	// Add Custom Metaboxes to Post Type
	function TS_VCSC_Logos_Codestar(array $meta_boxes) {
		global $pagenow;		
		$screen 							= TS_VCSC_GetCurrentPostType();
		$prefixA 							= 'ts_vcsc_logo_basic_';
		
		// Migration of Old Metadata for Existing Posts
		if (($screen == 'ts_logos') && ($pagenow == 'post.php')) {
			$metaOld						= array('ts_vcsc_logo_basic_name', 'ts_vcsc_logo_basic_link');
			$metaSwitch						= array();
			$metaGallery					= array();
			$metaImage						= array();
			if (function_exists('TS_VCSC_Codestar_Migrate_Routine')){
				TS_VCSC_Codestar_Migrate_Routine(get_the_ID(), 'ts_logos', $metaOld, $metaSwitch, $metaGallery, $metaImage, 'ts_vcsc_logo_basic', 0, 'ts_vcsc_logo_migrated', false, false, false);
			}
		}
		
		// Configure Metabox - Logos
		if (($screen == 'ts_logos') && ($pagenow == 'post-new.php' || $pagenow == 'post.php')) {
			$meta_boxes[]      				= array(
				'id'            			=> 'ts_vcsc_logo_basic',
				'title'         			=> 'Basic Information',
				'post_type'     			=> 'ts_logos',
				'context'       			=> 'normal',
				'priority'      			=> 'high',
				'sections'      			=> array(
					// Logos Section
					array(
						'name'      		=> $prefixA . 'section',
						'title'     		=> 'Logo Information',
						'icon'      		=> 'fa fa-picture-o',
						'fields'    		=> array(
							array(
								'type'    	=> 'subheading',
								'content' 	=> 'Use the "Featured Image" section to apply the logo itself to this post. It is recommended to only use logo images that share the same scaling ratio; square images will work best.',
							),
							array(
								'type'    	=> 'notice',
								'class'   	=> 'warning',
								'content' 	=> 'The "VC Logos" post type can also be used to create portfolios.',
							),
							array(
								'id'    	=> $prefixA . 'name',
								'type'  	=> 'text',
								'title' 	=> 'Title / Name:',
								'desc' 		=> 'Provide a title / name for the logo; otherwise post title will be used.',
							),
							array(
								'id'    	=> $prefixA . 'link',
								'type'  	=> 'text',
								'title' 	=> 'Link URL:',
								'desc' 		=> 'Provide a link to another site this logo or portfolio item is related to.',
							),
						),
					),			
				),
			);
			// Hidden Migration Setting
			$meta_boxes[]      				= array(
				'id'            			=> 'ts_vcsc_custompost_migrated',
				'title'         			=> 'Logo Migration',
				'post_type'     			=> 'ts_logos',
				'context'       			=> 'normal',
				'priority'      			=> 'high',
				'sections'      			=> array(
					// Migration Section
					array(
						'name'      		=> 'ts_vcsc_custompost_section',
						'title'     		=> 'Logo Migration',
						'icon'      		=> 'fa fa-check-square-o',
						'fields'    		=> array(
							array(
								'id'		=> 'ts_vcsc_logo_migrated',
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
	function TS_VCSC_Logos_Post_Files() {
		global $pagenow;
		$screen = TS_VCSC_GetCurrentPostType();
		if ($screen=='ts_logos') {
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
	
	// Add Featured Image Column
	function TS_VCSC_Add_Logos_Image_Column( $defaults ){
		$defaults = array_merge(
			array('ts_logos_post_thumbs' => __('Thumbnail')),
			$defaults
		);
		return $defaults;
	}
	function TS_VCSC_Show_Logos_Image_Column( $column_name, $id ) {
		if ( $column_name === 'ts_logos_post_thumbs' ) {
			echo the_post_thumbnail('thumbnail');
		}
	}
	
	// Remove RevSlider + Essential Grid Metaboxes
	function TS_VCSC_Logos_RemoveExternalMetaboxes() { 
		global $pagenow;
		$screen = TS_VCSC_GetCurrentPostType();
		if ($screen=='ts_logos') {
			if ($pagenow=='post-new.php' || $pagenow=='post.php') {
				remove_meta_box('eg-meta-box', 'ts_logos', 'normal'); 
				remove_meta_box('mymetabox_revslider_0', 'ts_logos', 'normal'); 
			} 
		} 
	}
	
	// Call All Routines
	if (is_admin()) {
		add_filter('post_updated_messages', 						'TS_VCSC_Logos_Post_Messages');
		add_action('contextual_help', 								'TS_VCSC_Logos_Post_Help', 					10, 3);
		add_filter('cs_metabox_options', 							'TS_VCSC_Logos_Codestar');
		add_action('admin_enqueue_scripts',							'TS_VCSC_Logos_Post_Files', 				9999999999);
		add_action('add_meta_boxes', 								'TS_VCSC_Logos_RemoveExternalMetaboxes', 	9999999999);
		add_filter('manage_ts_logos_posts_columns', 				'TS_VCSC_Add_Logos_Image_Column');
		add_action('manage_ts_logos_posts_custom_column', 			'TS_VCSC_Show_Logos_Image_Column', 			10, 2 );
	}
?>