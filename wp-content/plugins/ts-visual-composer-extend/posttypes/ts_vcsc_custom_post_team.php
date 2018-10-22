<?php
    // Create Custom Messages
    function TS_VCSC_Team_Post_Messages($messages) {
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
    function TS_VCSC_Team_Post_Help( $contextual_help, $screen_id, $screen ) { 
        if ( 'edit-ts_team' == $screen->id ) {
            $contextual_help = '<h2>Team Members</h2>
            <p>Team Members show the details and contact information for staff or group members that you want to provide to your visitors.</p> 
            <p>You can view/edit the details of each team member by clicking on its name, or you can perform bulk actions using the dropdown menu and selecting multiple items.</p>';
        } else if ('ts_team' == $screen->id) {
            $contextual_help = '<h2>Editing Team Members</h2>
            <p>This page allows you to view/modify team member details. Please make sure to fill out the available boxes with the appropriate details. Team Member information can only be used with the Visual Composer Extensions Plugin.</p>';
        }
        return $contextual_help;
    }
	
	// Add Custom Metaboxes to Post Type
	function TS_VCSC_Team_CodeStar(array $meta_boxes) {
		global $pagenow;		
		$screen 								= TS_VCSC_GetCurrentPostType();
		$prefixA 								= 'ts_vcsc_team_basic_';
		$prefixB 								= 'ts_vcsc_team_contact_';
		$prefixC								= 'ts_vcsc_team_social_';
		$prefixD								= 'ts_vcsc_team_skills_';
		$prefixE								= 'ts_vcsc_team_opening_';
		
		// Migration of Old Metadata for Existing Posts
		if (($screen == 'ts_team') && ($pagenow == 'post.php')) {
			$metaOld 							= array(
				// Section - Basic Information
				$prefixA . 'position', $prefixB . 'email', $prefixB . 'phone', $prefixB . 'cell', $prefixB . 'portfolio', $prefixB . 'portfoliolabel', $prefixB . 'other', $prefixB . 'otherlabel', $prefixB . 'skype',
				// Section - Business / Opening Hours
				$prefixD . 'symbol', $prefixD . 'symbolcolor', $prefixD . 'header', $prefixD . 'opening',
				// Section - Team Page Link
				$prefixA . 'dedicatedpage', $prefixA . 'dedicatedlink', $prefixA . 'dedicatedtarget', $prefixA . 'dedicatedicon', $prefixA . 'dedicatedcolor', $prefixA . 'dedicatedlabel', $prefixA . 'dedicatedtooltip', $prefixA . 'dedicatedtype',
				// Section - File Attachment
				$prefixA . 'buttonfile', $prefixA . 'buttonicon', $prefixA . 'buttoncolor', $prefixA . 'buttonlabel', $prefixA . 'buttontooltip', $prefixA . 'buttontype',
				// Section - Social Networks
				$prefixC . 'facebook', $prefixC . 'google', $prefixC . 'twitter', $prefixC . 'linkedin', $prefixC . 'xing', $prefixC . 'envato', $prefixC . 'rss', $prefixC . 'forrst', $prefixC . 'flickr', $prefixC . 'instagram', $prefixC . 'picasa', $prefixC . 'pinterest', $prefixC . 'vimeo', $prefixC . 'youtube',
				// Section - Skill Sets
				$prefixD . 'skillset',
			);
			$metaSwitch							= array($prefixA . 'dedicatedtarget',);
			$metaGallery						= array();
			$metaImage							= array();
			if (function_exists('TS_VCSC_Codestar_Migrate_Routine')){
				TS_VCSC_Codestar_Migrate_Routine(get_the_ID(), 'ts_team', $metaOld, $metaSwitch, $metaGallery, $metaImage, 'ts_vcsc_team_information', 0, 'ts_vcsc_team_migrated', false, false, false);
			}
		}
		
		if (($screen == 'ts_team') && ($pagenow == 'post-new.php' || $pagenow == 'post.php')) {		
			// Define Available Button Types
			$TS_VCSC_Button_Types = array(
				// Default Color Buttons
				'ts-button-3d'													=> 'Standard / 3D - Square',
				'ts-button-3d ts-button-rounded'								=> 'Standard / 3D - Rounded',
				'ts-button-3d ts-button-pill'									=> 'Standard / 3D - Pill',
				'ts-button-default'												=> 'Standard / Default - Square',
				'ts-button-default glow'										=> 'Standard / Default - Square (Glow)',
				'ts-button-rounded ts-button-default'							=> 'Standard / Default - Rounded',
				'ts-button-rounded ts-button-default glow'						=> 'Standard / Default - Rounded (Glow)',
				'ts-button-pill ts-button-default'								=> 'Standard / Default - Pill',
				'ts-button-pill ts-button-default glow'							=> 'Standard / Default - Pill (Glow)',
				'ts-button-flat'												=> 'Standard / Flat - Square',
				'ts-button-flat glow'											=> 'Standard / Flat - Square (Glow)',
				'ts-button-rounded ts-button-flat'								=> 'Standard / Flat - Rounded',
				'ts-button-rounded ts-button-flat glow'							=> 'Standard / Flat - Rounded (Glow)',
				'ts-button-pill ts-button-flat'									=> 'Standard / Flat - Pill',
				'ts-button-pill ts-button-flat glow'							=> 'Standard / Flat - Pill (Glow)',
				// Primary Color Buttons
				'ts-button-3d-primary'											=> 'Primary / 3D - Square',
				'ts-button-3d-primary ts-button-rounded'						=> 'Primary / 3D - Rounded',
				'ts-button-3d-primary ts-button-pill'							=> 'Primary / 3D - Pill',
				'ts-button-default ts-button-primary'							=> 'Primary / Default - Square',
				'ts-button-default glow ts-button-primary'						=> 'Primary / Default - Square (Glow)',
				'ts-button-rounded-primary ts-button-default'					=> 'Primary / Default - Rounded',
				'ts-button-rounded-primary ts-button-default'					=> 'Primary / Default - Rounded (Glow)',
				'ts-button-pill ts-button-primary'								=> 'Primary / Default - Pill',
				'ts-button-pill ts-button-primary glow'							=> 'Primary / Default - Pill (Glow)',
				'ts-button-flat-primary'										=> 'Primary / Flat - Square',
				'ts-button-flat-primary glow'									=> 'Primary / Flat - Square (Glow)',
				'ts-button-rounded ts-button-flat-primary'						=> 'Primary / Flat - Rounded',
				'ts-button-rounded ts-button-flat-primary glow'					=> 'Primary / Flat - Rounded (Glow)',
				'ts-button-pill ts-button-flat-primary'							=> 'Primary / Flat - Pill',
				'ts-button-pill ts-button-flat-primary glow'					=> 'Primary / Flat - Pill (Glow)',
				// Action Color Buttons
				'ts-button-3d-action'											=> 'Action / 3D - Square',
				'ts-button-3d-action ts-button-rounded'							=> 'Action / 3D - Rounded',
				'ts-button-3d-action ts-button-pill'							=> 'Action / 3D - Pill',
				'ts-button-default ts-button-action'							=> 'Action / Default - Square',
				'ts-button-default glow ts-button-action'						=> 'Action / Default - Square (Glow)',
				'ts-button-rounded ts-button-default ts-button-action'			=> 'Action / Default - Rounded',
				'ts-button-rounded ts-button-default glow ts-button-action'		=> 'Action / Default - Rounded (Glow)',
				'ts-button-pill ts-button-default ts-button-action'				=> 'Action / Default - Pill',
				'ts-button-pill ts-button-default glow ts-button-action'		=> 'Action / Default - Pill (Glow)',
				'ts-button-flat-action'											=> 'Action / Flat - Square',
				'ts-button-flat-action glow'									=> 'Action / Flat - Square (Glow)',
				'ts-button-rounded ts-button-flat-action'						=> 'Action / Flat - Rounded',
				'ts-button-rounded ts-button-flat-action glow'					=> 'Action / Flat - Rounded (Glow)',
				'ts-button-pill ts-button-flat-action'							=> 'Action / Flat - Pill',
				'ts-button-pill ts-button-flat-action glow'						=> 'Action / Flat - Pill (Glow)',
				// Highlight Color Buttons
				'ts-button-3d-highlight'										=> 'Highlight / 3D - Square',
				'ts-button-3d-highlight ts-button-rounded'						=> 'Highlight / 3D - Rounded',
				'ts-button-3d-highlight ts-button-pill'							=> 'Highlight / 3D - Pill',
				'ts-button-default ts-button-highlight'							=> 'Highlight / Default - Square',
				'ts-button-default glow ts-button-highlight'					=> 'Highlight / Default - Square (Glow)',
				'ts-button-rounded ts-button-default ts-button-highlight'		=> 'Highlight / Default - Rounded',
				'ts-button-rounded ts-button-default glow ts-button-highlight'	=> 'Highlight / Default - Rounded (Glow)',
				'ts-button-pill ts-button-default ts-button-highlight'			=> 'Highlight / Default - Pill',
				'ts-button-pill ts-button-default glow ts-button-highlight'		=> 'Highlight / Default - Pill (Glow)',
				'ts-button-flat-highlight'										=> 'Highlight / Flat - Square',
				'ts-button-flat-highlight glow'									=> 'Highlight / Flat - Square (Glow)',
				'ts-button-rounded ts-button-flat-highlight'					=> 'Highlight / Flat - Rounded',
				'ts-button-rounded ts-button-flat-highlight glow'				=> 'Highlight / Flat - Rounded (Glow)',
				'ts-button-pill ts-button-flat-highlight'						=> 'Highlight / Flat - Pill',
				'ts-button-pill ts-button-flat-highlight glow'					=> 'Highlight / Flat - Pill (Glow)',
				// Caution Color Buttons
				'ts-button-3d-caution'											=> 'Caution / 3D - Square',
				'ts-button-3d-caution ts-button-rounded'						=> 'Caution / 3D - Rounded',
				'ts-button-3d-caution ts-button-pill'							=> 'Caution / 3D - Pill',
				'ts-button-default ts-button-caution'							=> 'Caution / Default - Square',
				'ts-button-default glow ts-button-caution'						=> 'Caution / Default - Square (Glow)',
				'ts-button-rounded ts-button-default ts-button-caution'			=> 'Caution / Default - Rounded',
				'ts-button-rounded ts-button-default glow ts-button-caution'	=> 'Caution / Default - Rounded (Glow)',
				'ts-button-pill ts-button-default ts-button-caution'			=> 'Caution / Default - Pill',
				'ts-button-pill ts-button-default glow ts-button-caution'		=> 'Caution / Default - Pill (Glow)',
				'ts-button-flat-caution'										=> 'Caution / Flat - Square',
				'ts-button-flat-caution glow'									=> 'Caution / Flat - Square (Glow)',
				'ts-button-rounded ts-button-flat-caution'						=> 'Caution / Flat - Rounded',
				'ts-button-rounded ts-button-flat-caution glow'					=> 'Caution / Flat - Rounded (Glow)',
				'ts-button-pill ts-button-flat-caution'							=> 'Caution / Flat - Pill',
				'ts-button-pill ts-button-flat-caution glow'					=> 'Caution / Flat - Pill (Glow)',
				// Royal Color Buttons
				'ts-button-3d-royal'											=> 'Royal / 3D - Square',
				'ts-button-3d-royal ts-button-rounded'							=> 'Royal / 3D - Rounded',
				'ts-button-3d-royal ts-button-pill'								=> 'Royal / 3D - Pill',
				'ts-button-default ts-button-royal'								=> 'Royal / Default - Square',
				'ts-button-default glow ts-button-royal'						=> 'Royal / Default - Square (Glow)',
				'ts-button-rounded ts-button-default ts-button-royal'			=> 'Royal / Default - Rounded',
				'ts-button-rounded ts-button-default glow ts-button-royal'		=> 'Royal / Default - Rounded (Glow)',
				'ts-button-pill ts-button-default ts-button-royal'				=> 'Royal / Default - Pill',
				'ts-button-pill ts-button-default glow ts-button-royal'			=> 'Royal / Default - Pill (Glow)',
				'ts-button-flat-royal'											=> 'Royal / Flat - Square',
				'ts-button-flat-royal glow'										=> 'Royal / Flat - Square (Glow)',
				'ts-button-rounded ts-button-flat-royal'						=> 'Royal / Flat - Rounded',
				'ts-button-rounded ts-button-flat-royal glow'					=> 'Royal / Flat - Rounded (Glow)',
				'ts-button-pill ts-button-flat-royal'							=> 'Royal / Flat - Pill',
				'ts-button-pill ts-button-flat-royal glow'						=> 'Royal / Flat - Pill (Glow)',
			);
	
			$availablePages 					= array();
			$availablePages['-1']				= 'No Page for Teammate';
			$availablePages['external'] 		= 'External Page Teammate';
			$availablePages						= $availablePages + TS_VCSC_GetPostOptions(array('post_type' => 'page', 'posts_per_page' => -1), true);
			// Hidden Migration Setting
			$meta_boxes[]      					= array(
				'id'            				=> 'ts_vcsc_team_information',
				'title'         				=> 'Team Member Information',
				'post_type'     				=> 'ts_team',
				'context'       				=> 'normal',
				'priority'      				=> 'high',
				'sections'      				=> array(
					// Section - Basic Information
					array(
						'name'      			=> 'ts_vcsc_team_contact',
						'title'     			=> 'Contact Information',
						'icon'      			=> 'fa fa-user',
						'fields'    			=> array(
							array(
							  'type'    		=> 'heading',
							  'content' 		=> 'Contact Information',
							),
							array(
								'type'    		=> 'notice',
								'class'   		=> 'warning',
								'content' 		=> 'Use the "Featured Image" section to assign a photo of the team member. It is recommended to only use profile images that share the same scaling ratio; square images will work best.',
							),
							array('id' => $prefixA . 'position', 'type' => 'text', 'title' => 'Position:', 'help' => 'Provide some information about the team members position in your company or group.'),
							array('id' => $prefixB . 'email', 'type' => 'text', 'title' => '<i class="ts-teamicon-email3 ts-font-icon"></i> Email Address:', 'validate' => 'emailaddress',),
							array('id' => $prefixB . 'phone', 'type' => 'text', 'title' => '<i class="ts-teamicon-phone2 ts-font-icon"></i> Phone Number:',),
							array('id' => $prefixB . 'cell', 'type' => 'text','title' => '<i class="ts-teamicon-mobile ts-font-icon"></i> Cell Number:',),
							array('id' => $prefixB . 'portfolio', 'type' => 'text','title' => '<i class="ts-teamicon-portfolio ts-font-icon"></i> Portfolio URL:', 'validate' => 'urladdress',),
							array('id' => $prefixB . 'portfoliolabel', 'type' => 'text', 'title' => 'Label for Portfolio URL:', 'std' => '', 'help' => 'If left empty, the actual URL to the portfolio site will be shown.'),
							array('id' => $prefixB . 'other','type' => 'text','title' => '<i class="ts-teamicon-link ts-font-icon"></i> Personal URL:', 'validate' => 'urladdress',),
							array('id' => $prefixB . 'otherlabel', 'type' => 'text', 'title' => 'Label for Personal URL:', 'std' => '', 'help' => 'If left empty, the actual URL to the personal site will be shown.'),
							array('id' => $prefixB . 'skype', 'type' => 'text', 'title' => '<i class="ts-teamicon-skype ts-font-icon"></i> Skype User Name:',),
						),
					),
					// Section - Social Networks
					array(
						'name'      			=> 'ts_vcsc_team_social',
						'title'     			=> 'Social Networks',
						'icon'      			=> 'fa fa-share-square-o',
						'fields'    			=> array(
							array(
							  'type'    		=> 'heading',
							  'content' 		=> 'Social Networks',
							),
							array(
								'type'    		=> 'notice',
								'class'   		=> 'info',
								'content' 		=> 'Use the inputs below to provide links to profiles on social networks the team member might be using.',
							),
							array('title' => '<i class="ts-teamicon-facebook1 ts-font-icon"></i> Facebook URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'facebook', 'type' => 'text'),
							array('title' => '<i class="ts-teamicon-googleplus1 ts-font-icon"></i> Google+ URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'google', 'type' => 'text'),
							array('title' => '<i class="ts-teamicon-twitter1 ts-font-icon"></i> Twitter URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'twitter', 'type' => 'text'),
							array('title' => '<i class="ts-teamicon-linkedin ts-font-icon"></i> Linkedin URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'linkedin', 'type' => 'text'),
							array('title' => '<i class="ts-teamicon-xing3 ts-font-icon"></i> Xing URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'xing', 'type' => 'text'),
							array('title' => '<i class="ts-teamicon-envato ts-font-icon"></i> Envato URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'envato', 'type' => 'text'),
							array('title' => '<i class="ts-teamicon-rss1 ts-font-icon"></i> RSS URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'rss', 'type' => 'text'),
							array('title' => '<i class="ts-teamicon-forrst1 ts-font-icon"></i> Forrst URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'forrst', 'type' => 'text'),
							array('title' => '<i class="ts-teamicon-flickr3 ts-font-icon"></i> Flickr URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'flickr', 'type' => 'text'),
							array('title' => '<i class="ts-teamicon-instagram ts-font-icon"></i> Instagram URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'instagram', 'type' => 'text'),
							array('title' => '<i class="ts-teamicon-picasa1 ts-font-icon"></i> Picasa URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'picasa', 'type' => 'text'),
							array('title' => '<i class="ts-teamicon-pinterest1 ts-font-icon"></i> Pinterest URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'pinterest', 'type' => 'text'),
							array('title' => '<i class="ts-teamicon-vimeo1 ts-font-icon"></i> Vimeo URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'vimeo', 'type' => 'text'),
							array('title' => '<i class="ts-teamicon-youtube1 ts-font-icon"></i> Youtube URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'youtube', 'type' => 'text'),
						),
					),
					// Section - Skill Sets
					array(
						'name'      			=> 'ts_vcsc_team_skills',
						'title'     			=> 'Member Skill Sets',
						'icon'      			=> 'fa fa-align-left',
						'fields'    			=> array(
							array(
							  'type'    		=> 'heading',
							  'content' 		=> 'Member Skill Sets',
							),
							array(
								'type'    		=> 'notice',
								'class'   		=> 'info',
								'content' 		=> 'Add as many skill levels as you need by using the "Add New Skill" button. Skills can also be re-ordered by simply dragging them to their desired position, or removed by using the appropriate button within each skill set.',
							),
							array(
								'id'              	=> $prefixD . 'skillset',
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
							),
						),
					),
					// Section - Business / Opening Hours
					array(
						'name'      			=> 'ts_vcsc_team_opening',
						'title'     			=> 'Business Information',
						'icon'      			=> 'fa fa-building-o',
						'fields'    			=> array(
							array(
							  'type'    		=> 'heading',
							  'content' 		=> 'Business Information',
							),
							array(
								'type'    		=> 'notice',
								'class'   		=> 'info',
								'content' 		=> 'Use this section to provide additional information, such as business / opening hours, or other optional information. The matching elements in Visual Composer will allow you to show or hide this section individually.',
							),
							array(
								'id'             => $prefixD . 'symbol',
								'type'           => 'radio',
								'title'          => 'Select Icon',
								'options'        => array(
									'clock1' 		=> '<i class="ts-teamicon-clock1 ts-font-icon"></i> ' . 'Clock',
									'calendar1'   	=> '<i class="ts-teamicon-calendar1 ts-font-icon"></i> ' . 'Calendar',
									'info1'     	=> '<i class="ts-teamicon-info1 ts-font-icon"></i> ' . 'Info',
									'location1'		=> '<i class="ts-teamicon-location1 ts-font-icon"></i> ' . 'Pin',
									'none'     		=> 'None',
								),
								'attributes' => array(
									'data-depend-id' 	=> $prefixD . 'symbol',
								),
								'help'			=> 'Select the icon that should be shown alongside the header.',
								'default'		=> 'clock1',
							),
							array(
								'id' 			=> $prefixD . 'symbolcolor',
								'type' 			=> 'color_picker',
								'title' 		=> 'Icon Color:',
								'default' 		=> '#666666',
								'dependency'  	=> array($prefixD . 'symbol', '!=', 'none'),
							),
							array(
								'id' 			=> $prefixD . 'header',
								'type' 			=> 'text',
								'title' 		=> 'Title:',
								'help' 			=> 'Enter a header that will be shown above the custom content you will provide below.',
							),
							array(
								'id'       		=> $prefixD . 'opening',
								'type'     		=> 'wysiwyg',
								'title'    		=> 'Information:',
								'settings' 		=> array(
									'textarea_rows' => 5,
									'tinymce'       => false,
									'media_buttons' => false,
								),
								'help' 			=> 'You can use any text and basic HTML code; create line breaks via "Enter" button or by using the appropriate HTML code.',
							),
						),
					),
					// Section - Team Page Link
					array(
						'name'      			=> 'ts_vcsc_team_page',
						'title'     			=> 'Member Page Link',
						'icon'      			=> 'fa fa-external-link',
						'fields'    			=> array(
							array(
							  'type'    		=> 'heading',
							  'content' 		=> 'Member Page Link',
							),
							array(
								'type'    		=> 'notice',
								'class'   		=> 'info',
								'content' 		=> 'When used in a slider, you might not want to show all data in the slider; so provide a link (button) to a page that shows the full profile. You can select whether to show or hide the link button in the individual elements settings later.',
							),
							array(
								'id'         	=> $prefixA . 'dedicatedpage',
								'type'       	=> 'select',
								'title'      	=> 'Dedicated Page:',
								'options'    	=> $availablePages,
								'desc' 			=> 'If existing, select a page that is dedicated to this particular team member.',
								'default'		=> -1,
								'class'      	=> 'chosen',
							),
							array(
								'id' 			=> $prefixA . 'dedicatedlink',
								'type' 			=> 'text',
								'title'			=> '<i class="ts-teamicon-link ts-font-icon"></i> External URL:',
								'validate' 		=> 'urladdress',
								'dependency'   	=> array($prefixA . 'dedicatedpage', '==', 'external'),
							),
							array(
								'id'    		=> $prefixA . 'dedicatedtarget',
								'type'  		=> 'switcher',
								'title' 		=> 'Open in New Tab/Window:',
								'default'		=> false,
								'dependency'   	=> array($prefixA . 'dedicatedpage', '!=', '-1'),
							),
							array(
								'id'             => $prefixA . 'dedicatedicon',
								'type'           => 'radio',
								'title'          => 'Button Icon',
								'options'        => array(
									'eye2' 			=> '<i class="ts-teamicon-eye2 ts-font-icon"></i> ' . 'Eye 1',
									'eye5' 			=> '<i class="ts-teamicon-eye5 ts-font-icon"></i> ' . 'Eye 2',
									'eye1' 			=> '<i class="ts-teamicon-eye1 ts-font-icon"></i> ' . 'Eye 3',
									'eye3' 			=> '<i class="ts-teamicon-eye3 ts-font-icon"></i> ' . 'Eye 4',
									'info1'   		=> '<i class="ts-teamicon-info1 ts-font-icon"></i> ' . 'Info 1',
									'info4'   		=> '<i class="ts-teamicon-info4 ts-font-icon"></i> ' . 'Info 2',
									'link'   		=> '<i class="ts-teamicon-link ts-font-icon"></i> ' . 'Link 1',
									'link5'   		=> '<i class="ts-teamicon-link5 ts-font-icon"></i> ' . 'Link 2',
									'none'     		=> 'None',
								),
								'desc'    		=> 'Select the icon that should be shown alongside the button label.',
								'default' 		=> 'eye2',
								'attributes' => array(
									'data-depend-id' 	=> $prefixA . 'dedicatedicon',
								),
								'dependency'   	=> array($prefixA . 'dedicatedpage', '!=', '-1'),
							),
							array(
								'id' 			=> $prefixA . 'dedicatedcolor',
								'type' 			=> 'color_picker',
								'title' 		=> 'Icon Color:',
								'default' 		=> '#666666',
								'dependency'  	=> array($prefixA . 'dedicatedicon|' . $prefixA . 'dedicatedpage', '!=|!=', 'none|-1'),
							),
							array(
								'id' 			=> $prefixA . 'dedicatedlabel',
								'type' 			=> 'text',
								'title' 		=> 'Button Label:',
								'default'		=> 'View Teammate',
								'dependency'   	=> array($prefixA . 'dedicatedpage', '!=', '-1'),
							),
							array(
								'id' 			=> $prefixA . 'dedicatedtooltip',
								'type' 			=> 'text',
								'title' 		=> 'Button Tooltip:',
								'dependency'   	=> array($prefixA . 'dedicatedpage', '!=', '-1'),
							),
							array(
								'id' 			=> $prefixA . 'dedicatedtype',
								'type' 			=> 'select',
								'options' 		=> $TS_VCSC_Button_Types,
								'title' 		=> 'Button Type:',
								'default' 		=> 'ts-button-3d',
								'desc' 			=> '',
								'dependency'   	=> array($prefixA . 'dedicatedpage', '!=', '-1'),
							),
						),
					),
					// Section - File Attachment
					array(
						'name'      			=> 'ts_vcsc_team_file',
						'title'     			=> 'File Attachment',
						'icon'      			=> 'fa fa-download',
						'fields'    			=> array(
							array(
							  'type'    		=> 'heading',
							  'content' 		=> 'File Attachment',
							),
							array(
								'type'    		=> 'notice',
								'class'   		=> 'info',
								'content' 		=> 'If applicable, you can attach a download option to your team member; useful for resumes or other information you want to provide for your viewers.',
							),
							array(
								'id'            => $prefixA . 'buttonfile',
								'type'          => 'upload',
								'title'         => 'Attachment',
								'settings'      => array(
									'upload_type'  => 'video,audio,application/pdf,application/msword,application/vnd.ms-powerpoint,application/vnd.ms-excel,application/wordperfect,application/vnd.oasis.opendocument.text,application/vnd.oasis.opendocument.presentation,application/vnd.oasis.opendocument.spreadsheet,application/vnd.apple.pages,application/vnd.apple.numbers',
									'button_title' => 'Upload or Add',
									'frame_title'  => 'Select a File',
									'insert_title' => 'Use this File',
								),
								'help' 			=> 'Attach a file, including information such as a resume, for your viewers to download.',
							),
							array(
								'id'             => $prefixA . 'buttonicon',
								'type'           => 'radio',
								'title'          => 'Button Icon',
								'options'        => array(
									'download3'		=> '<i class="ts-teamicon-download3 ts-font-icon"></i> ' . 'Download 1',
									'download4'		=> '<i class="ts-teamicon-download4 ts-font-icon"></i> ' . 'Download 2',
									'download5'		=> '<i class="ts-teamicon-download5 ts-font-icon"></i> ' . 'Download 3',
									'download7'		=> '<i class="ts-teamicon-download7 ts-font-icon"></i> ' . 'Download 4',
									'file4' 		=> '<i class="ts-teamicon-file4 ts-font-icon"></i> ' . 'File 1',
									'file14'   		=> '<i class="ts-teamicon-file14 ts-font-icon"></i> ' . 'File 2',	
									'link'   		=> '<i class="ts-teamicon-link ts-font-icon"></i> ' . 'Link 1',
									'link5'   		=> '<i class="ts-teamicon-link5 ts-font-icon"></i> ' . 'Link 2',
									'none'     		=> 'None',
								),
								'attributes' => array(
									'data-depend-id' 	=> $prefixA . 'buttonicon',
								),
								'help'    		=> 'Select the icon that should be shown alongside the button label.',
								'default' 		=> 'download3',
								'dependency'   	=> array($prefixA . 'buttonfile', '!=', ''),
							),
							array(
								'id' 			=> $prefixA . 'buttoncolor',
								'type' 			=> 'color_picker',
								'title' 		=> 'Icon Color:',
								'default' 		=> '#666666',
								'dependency'  	=> array($prefixA . 'buttonicon|' . $prefixA . 'buttonfile', '!=|!=', 'none|'),
							),
							array(
								'id' 			=> $prefixA . 'buttonlabel',
								'type' 			=> 'text',
								'title' 		=> 'Button Label:',
								'default'		=> 'Download File',
								'dependency'   	=> array($prefixA . 'buttonfile', '!=', ''),
							),
							array(
								'id' 			=> $prefixA . 'buttontooltip',
								'type' 			=> 'text',
								'title' 		=> 'Button Tooltip:',
								'dependency'   	=> array($prefixA . 'buttonfile', '!=', ''),
							),
							array(
								'id' 			=> $prefixA . 'buttontype',
								'type' 			=> 'select',
								'options' 		=> $TS_VCSC_Button_Types,
								'title' 		=> 'Button Type:',
								'default' 		=> 'ts-button-3d',
								'desc' 			=> '',
								'dependency'   	=> array($prefixA . 'buttonfile', '!=', ''),
							),
						),
					),
				),
			);
			$meta_boxes[]      				= array(
				'id'            			=> 'ts_vcsc_custompost_migrated',
				'title'         			=> 'Team Migration',
				'post_type'     			=> 'ts_team',
				'context'       			=> 'normal',
				'priority'      			=> 'high',
				'sections'      			=> array(
					// Migration Section
					array(
						'name'      		=> 'ts_vcsc_custompost_section',
						'title'     		=> 'Team Migration',
						'icon'      		=> 'fa fa-check-square-o',
						'fields'    		=> array(
							array(
								'id'		=> 'ts_vcsc_team_migrated',
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
	function TS_VCSC_Team_Post_Files() {
		global $pagenow;		
		$screen = TS_VCSC_GetCurrentPostType();
		if ($screen == 'ts_team') {
			if ($pagenow == 'post-new.php' || $pagenow == 'post.php') {
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
	
	// Add Featured Image Column
	function TS_VCSC_Add_Team_Image_Column( $defaults ){
		$defaults = array_merge(
			array('ts_team_post_thumbs' => __('Thumbnail')),
			$defaults
		);
		return $defaults;
	}
	function TS_VCSC_Show_Team_Image_Column( $column_name, $id ) {
		if ( $column_name === 'ts_team_post_thumbs' ) {
			echo the_post_thumbnail('thumbnail');
		}
	}
	
	// Remove RevSlider + Essential Grid Metaboxes
	function TS_VCSC_Team_RemoveExternalMetaboxes() { 
		global $pagenow;
		$screen = TS_VCSC_GetCurrentPostType();
		if ($screen=='ts_team') {
			if ($pagenow=='post-new.php' || $pagenow=='post.php') {
				remove_meta_box('eg-meta-box', 'ts_team', 'normal'); 
				remove_meta_box('mymetabox_revslider_0', 'ts_team', 'normal'); 
			} 
		} 
	}
	
	// Call All Routines
	if (is_admin()) {
		add_filter('post_updated_messages', 						'TS_VCSC_Team_Post_Messages');
		add_action('contextual_help', 								'TS_VCSC_Team_Post_Help', 				10, 3);
		add_action('admin_enqueue_scripts', 						'TS_VCSC_Team_Post_Files', 				9999999999);
		add_action('add_meta_boxes', 								'TS_VCSC_Team_RemoveExternalMetaboxes', 9999999999);
		add_filter('cs_metabox_options', 							'TS_VCSC_Team_Codestar');
		add_filter('manage_ts_team_posts_columns', 					'TS_VCSC_Add_Team_Image_Column');
		add_action('manage_ts_team_posts_custom_column', 			'TS_VCSC_Show_Team_Image_Column', 		10, 2 );
	}
?>