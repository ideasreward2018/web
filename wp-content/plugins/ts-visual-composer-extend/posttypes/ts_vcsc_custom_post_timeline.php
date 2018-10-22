<?php
    // Create Custom Messages
    function TS_VCSC_Timeline_Post_Messages($messages) {
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
    function TS_VCSC_Timeline_Post_Help( $contextual_help, $screen_id, $screen ) { 
        if ( 'edit-ts_timeline' == $screen->id ) {
            $contextual_help = '<h2>Timeline Sections</h2>
            <p>Timeline sections can be used to create an interactive CSS/jQuery powered timeline with "Composium - Visual Composer Extensions".</p> 
            <p>You can view/edit the details of each timeline section by clicking on its name, or you can perform bulk actions using the dropdown menu and selecting multiple items.</p>';
        } else if ('ts_timeline' == $screen->id) {
            $contextual_help = '<h2>Editing Timeline Sections</h2>
            <p>This page allows you to view/modify timeline section details. Please make sure to fill out the available boxes with the appropriate details. Timeline section information can only be used with the Visual Composer Extensions Plugin.</p>';
        }
        return $contextual_help;
    }
	
	// Add Custom Metaboxes to Post Type
	function TS_VCSC_Timeline_Codestar(array $meta_boxes) {
		global $pagenow;
		global $VISUAL_COMPOSER_EXTENSIONS;
		$screen 				= TS_VCSC_GetCurrentPostType();
		$prefixA 				= 'ts_vcsc_timeline_type_';
		$prefixB 				= 'ts_vcsc_timeline_media_';
		$prefixC 				= 'ts_vcsc_timeline_event_';
		$prefixD 				= 'ts_vcsc_timeline_break_';
		$prefixE 				= 'ts_vcsc_timeline_link_';
		$prefixF 				= 'ts_vcsc_timeline_tooltip_';
		$prefixG 				= 'ts_vcsc_timeline_lightbox_';
		$prefixH 				= 'ts_vcsc_timeline_styling_';
		
		// Migration of Old Metadata for Existing Posts
		if (($screen == 'ts_timeline') && ($pagenow == 'post.php')) {
			$metaOld 							= array(
				// Section - Event Type
				$prefixA . 'type', $prefixA . 'radiusborder',
                // Section - Featured Media
				$prefixB . 'featuredmedia', $prefixB . 'lightboxfeatured', $prefixB . 'featuredimage', $prefixB . 'attributealtvalue', $prefixB . 'attributetitle', $prefixB . 'featuredslider',
                $prefixB . 'slidertitles', $prefixB . 'slidermaxheight', $prefixB . 'featuredyoutubeurl', $prefixB . 'featuredyoutuberelated', $prefixB . 'featuredyoutubeplay', $prefixB . 'featureddailymotionurl',
                $prefixB . 'featureddailymotionplay', $prefixB . 'featuredvimeourl', $prefixB . 'featuredvimeoplay', $prefixB . 'featuredmediaheight', $prefixB . 'featuredmediawidth', $prefixB . 'featuredmediaalign',
                // Section - Lightbox Settings
                $prefixG . 'lightboxgroup', $prefixG . 'lightboxgroupname', $prefixG . 'lightboxeffect', $prefixG . 'lightboxbacklight', $prefixG . 'lightboxbacklightcolor',
                // Section - Event Content
                $prefixB . 'fullwidth', $prefixC . 'eventdatetext', $prefixC . 'eventdateicon', $prefixC . 'eventtitletext', $prefixC . 'eventtitlealign', $prefixC . 'eventtitlecolor', $prefixC . 'eventcontent',
                // Section - Break Content
                $prefixD . 'breaktitletext', $prefixD . 'breaktitlealign', $prefixD . 'breaktitlecolor', $prefixD . 'breakcontent', $prefixD . 'breakbackground', $prefixD . 'breakfull',
                // Section - Event Link
                $prefixE . 'dedicatedpage', $prefixE . 'dedicatedlink', $prefixE . 'dedicatedtarget', $prefixE . 'dedicatedicon', $prefixE . 'dedicatedcolor', $prefixE . 'dedicatedlabel', $prefixE . 'dedicatedtooltip', $prefixE . 'dedicatedwidth', $prefixE . 'dedicatedalign', $prefixE . 'dedicateddefault', $prefixE . 'dedicatedhover',
                // Section - Event Tooltip
                $prefixF . 'tooltiptext', $prefixF . 'tooltipposition', $prefixF . 'tooltipstyle',
			);
			$metaSwitch							= array(
				$prefixB . 'lightboxfeatured',
				$prefixB . 'featuredyoutuberelated',
				$prefixB . 'featuredyoutubeplay',
				$prefixB . 'featureddailymotionplay',
				$prefixB . 'featuredvimeoplay',
				$prefixG . 'lightboxgroup',
				$prefixB . 'fullwidth',
				$prefixD . 'breakfull',
				$prefixE . 'dedicatedtarget',    
			);
			$metaGallery						= array($prefixB . 'featuredslider',);
			$metaImage							= array($prefixB . 'featuredimage',);
			if (function_exists('TS_VCSC_Codestar_Migrate_Routine')){
				TS_VCSC_Codestar_Migrate_Routine(get_the_ID(), 'ts_timeline', $metaOld, $metaSwitch, $metaGallery, $metaImage, 'ts_vcsc_timeline_information', 0, 'ts_vcsc_timeline_migrated', false, false, false);
			}
		}

		if (($screen == 'ts_timeline') && ($pagenow == 'post-new.php' || $pagenow == 'post.php')) {
			$availablePages 					= array();
			$availablePages['-1']				= 'No Page for Section';
			$availablePages['external'] 		= 'External Page for Section';
			$availablePages						= $availablePages + TS_VCSC_GetPostOptions(array('post_type' => 'page', 'posts_per_page' => -1), true);
			
			$meta_boxes[]      					= array(
				'id'            				=> 'ts_vcsc_timeline_information',
				'title'         				=> 'Timeline Section Information',
				'post_type'     				=> 'ts_timeline',
				'context'       				=> 'normal',
				'priority'      				=> 'high',
				'sections'      				=> array(
					// Section - Event Type
					array(
						'name'      			=> 'ts_vcsc_timeline_type',
						'title'     			=> 'Section Type',
						'icon'      			=> 'fa fa-puzzle-piece',
						'fields'    			=> array(
							array(
							  'type'    		=> 'heading',
							  'content' 		=> 'Section Type',
							),
							array(
								'type'    		=> 'notice',
								'class'   		=> 'info',
								'content' 		=> 'The standard "Event" section is used to display detailed information about an event within the timeline. A "Break" section visually interrupts the timeline column layout and can be used to mark the beginning of a new "era" in the timeline.',
							),
							array(						
								'id'      		=> $prefixA . 'type',
								'type'   		=> 'radio',
								'title'    		=> 'Section Type:',
								'default' 		=> 'event',
								'help' 			=> 'Check the type of timeline section you wan to create.',
								'options' 		=> array(
									'event' 		=> __( 'Event', 'ts_visual_composer_extend' ),
									'break'   		=> __( 'Break', 'ts_visual_composer_extend' ),
								),
								'class'			=> 'ts_vcsc_timeline_type_type',
								'attributes' 	=> array(
									'data-depend-id' 	=> $prefixA . 'type',
								),
							),
							array(
								'type'    		=> 'notice',
								'class'   		=> 'warning',
								'content' 		=> 'By default, this section will use the global styling as you define it in the element settings for the overall timeline, when adding the timeline element to a page or post. If you want to give this section a
								custom styling, use the controls provided below, which will provide you with an additional tab to customize some styling options for this specific section.',
							),
							array(
								'id'    		=> $prefixA . 'customevent',
								'type'  		=> 'switcher',
								'title' 		=> 'Customize Event Styling:',
								'default' 		=> false,
								'help' 			=> 'Check the box if you want this timeline event to be a featured event, displayed full width over both columns.',
								'dependency'	=> array($prefixA . 'type', '==', 'event'),
							),
							array(
								'id'    		=> $prefixA . 'custombreak',
								'type'  		=> 'switcher',
								'title' 		=> 'Customize Break Styling:',
								'default' 		=> false,
								'help' 			=> 'Check the box if you want this timeline event to be a featured event, displayed full width over both columns.',
								'dependency'	=> array($prefixA . 'type', '==', 'break'),
							),
						),
					),
					// Section - Event Content
					array(
						'name'      			=> 'ts_vcsc_timeline_event',
						'title'     			=> 'Event Information',
						'icon'      			=> 'fa fa-list-alt',
						'fields'    			=> array(
							array(
								'type'    		=> 'heading',
								'content' 		=> 'Event Information',
							),
							array(
								'type'    		=> 'notice',
								'class'   		=> 'info',
								'content' 		=> 'Use this section to add an actual "event" to your timeline, along with some featured media (image/slider/video). Content can be as short or long as you require, and you have
								the option to assign a popup tooltip to the section as well, if you want to show some additional information.',
							),
							array(
								'id'    		=> $prefixB . 'fullwidth',
								'type'  		=> 'switcher',
								'title' 		=> 'Featured Event:',
								'default' 		=> false,
								'help' 			=> 'Check the box if you want this timeline event to be a featured event, displayed full width over both columns.',
								'dependency'	=> array($prefixA . 'type', '==', 'event'),
							),					
							array(
								'type'    		=> 'subheading',
								'content' 		=> 'Event Date / Time',
							),
							array(
								'id' 			=> $prefixC . 'eventdatetext',
								'type' 			=> 'text',
								'title' 		=> 'Date / Time:',
								'help' 			=> 'Enter a date and/or time for this timeline section.',
								'dependency'	=> array($prefixA . 'type', '==', 'event'),								
							),
							array(
								'id'      		=> $prefixC . 'eventdateicon',
								'type'    		=> 'radio',
								'title'    		=> 'Date / Time Icon:',
								'help'    		=> 'Select the icon that should be shown alongside the date / time.',
								'options' 		=> array(
									'clock' 		=> '<i class="dashicons dashicons-clock ts-post-font-icon"></i> ' . 'Clock',
									'calendar'   	=> '<i class="dashicons dashicons-calendar ts-post-font-icon"></i> ' . 'Calendar',
									'info'     		=> '<i class="dashicons dashicons-info ts-post-font-icon"></i> ' . 'Info',
									'location'		=> '<i class="dashicons dashicons-location ts-post-font-icon"></i> ' . 'Pin',
									'heart'			=> '<i class="dashicons dashicons-heart ts-post-font-icon"></i> ' . 'Heart',
									'megaphone'		=> '<i class="dashicons dashicons-megaphone ts-post-font-icon"></i> ' . 'Megaphone',
									'art'			=> '<i class="dashicons dashicons-art ts-post-font-icon"></i> ' . 'Art',
									'none'     		=> 'None',
								),
								'default' 		=> 'none',
								'attributes' 	=> array(
									'data-depend-id' => $prefixC . 'eventdateicon',
								),
								'dependency'	=> array($prefixA . 'type', '==', 'event'),
							),
							array(
								'type'    		=> 'subheading',
								'content' 		=> 'Event Title',
							),
							array(
								'id' 			=> $prefixC . 'eventtitletext',
								'type' 			=> 'text',
								'title' 		=> 'Title:',
								'help' 			=> 'Enter the title for the timeline event.',
								'dependency'	=> array($prefixA . 'type', '==', 'event'),
							),
							array(
								'id'      		=> $prefixC . 'eventtitlealign',
								'type'    		=> 'select',
								'title'    		=> 'Alignment:',
								'help'    		=> 'Select how the title in the timeline event should be aligned.',
								'options' 		=> array(
									"center" 						=> __( 'Center', "ts_visual_composer_extend" ),
									"left" 							=> __( 'Left', "ts_visual_composer_extend" ),
									"right" 						=> __( 'Right', "ts_visual_composer_extend" ),
									"justify" 						=> __( 'Justify', "ts_visual_composer_extend" ),
								),
								'default' 		=> 'center',
								'class'			=> 'chosen',
								'dependency'	=> array($prefixA . 'type', '==', 'event'),
							),
							array(
								'type'    		=> 'subheading',
								'content' 		=> 'Event Content',
							),
							array(
								'type'    		=> 'notice',
								'class'   		=> 'warning',
								'content' 		=> 'You will be able to assign a font icon (and the icon color) to this event section when adding the event section to a specific timeline in Visual Composer.',
								'dependency'	=> array($prefixA . 'type', '==', 'event'),
							),
							array(
								'id' 			=> $prefixC . 'eventcontent',
								'type' 			=> 'wysiwyg',
								'title' 		=> 'Content:',
								'help' 			=> 'Enter the main content for the timeline event.',
								'settings' 		=> array(
									'wpautop' 			=> false, 										// use wpautop?
									'media_buttons' 	=> false, 										// show insert/upload button(s)
									'textarea_rows' 	=> 16, 											// rows="..."
									'tabindex' 			=> '',
									'editor_css' 		=> '', 											// intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
									'editor_class' 		=> '', 											// add extra class(es) to the editor textarea
									'teeny' 			=> false, 										// output the minimal editor config used in Press This
									'dfw' 				=> false, 										// replace the default fullscreen with DFW (needs specific css)
									'tinymce' 			=> true, 										// load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
									'quicktags' 		=> true, 										// load Quicktags, can be used to pass settings directly to Quicktags using an array()
									'sanitize' 			=> false,
								),
								'sanitize' 		=> false,
								'dependency'	=> array($prefixA . 'type', '==', 'event'),
							),
						),
					),
					// Section - Break Content
					array(
						'name'      			=> 'ts_vcsc_timeline_break',
						'title'     			=> 'Break Information',
						'icon'      			=> 'fa fa-list-alt',
						'fields'    			=> array(
							array(
								'type'    		=> 'heading',
								'content' 		=> 'Break Information',
							),
							array(
								'type'    		=> 'notice',
								'class'   		=> 'info',
								'content' 		=> 'Use this section to create a visual "break" in the timeline, indicating the beginning of a new period/era. Naturally, setting options for a "break" in the timeline are limited
								when compared to an event section and content should be kept rather short.',
							),
							array(
								'id'      		=> $prefixD . 'breakfull',
								'type'   		=> 'switcher',
								'title'    		=> 'Make Full Width:',
								'help' 			=> 'Select if the break section should be made full width (both columns), or centered at half width.',
								'default' 		=> false,
								'dependency'	=> array($prefixA . 'type', '==', 'break'),
							),
							array(
								'id' 			=> $prefixD . 'breaktitletext',
								'type' 			=> 'text',
								'title' 		=> 'Break Title:',
								'help' 			=> 'Enter the title for the timeline break.',
								'dependency'	=> array($prefixA . 'type', '==', 'break'),
							),
							array(
								'id'      		=> $prefixD . 'breaktitlealign',
								'type'    		=> 'select',
								'title'    		=> 'Title Alignment:',
								'help'    		=> 'Select how the title in the timeline event should be aligned.',
								'options' 		=> array(
									"center" 						=> __( 'Center', "ts_visual_composer_extend" ),
									"left" 							=> __( 'Left', "ts_visual_composer_extend" ),
									"right" 						=> __( 'Right', "ts_visual_composer_extend" ),
									"justify" 						=> __( 'Justify', "ts_visual_composer_extend" ),
								),
								'default' 		=> 'center',
								'class'			=> 'chosen',
								'dependency'	=> array($prefixA . 'type', '==', 'break'),
							),
							array(
								'type'    		=> 'notice',
								'class'   		=> 'warning',
								'content' 		=> 'You will be able to assign a font icon (and the icon color to this break section when adding the break section to a specific timeline in Visual Composer.',
								'dependency'	=> array($prefixA . 'type', '==', 'break'),
							),
							array(
								'id' 			=> $prefixD . 'breakcontent',
								'type' 			=> 'wysiwyg',
								'title' 		=> 'Break Content:',
								'help' 			=> 'Enter the main content for this timeline break item.',
								'settings' 		=> array(
									'wpautop' 			=> false, 										// use wpautop?
									'media_buttons' 	=> false, 										// show insert/upload button(s)
									'textarea_rows' 	=> 16, 											// rows="..."
									'tabindex' 			=> '',
									'editor_css' 		=> '', 											// intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
									'editor_class' 		=> '', 											// add extra class(es) to the editor textarea
									'teeny' 			=> false, 										// output the minimal editor config used in Press This
									'dfw' 				=> false, 										// replace the default fullscreen with DFW (needs specific css)
									'tinymce' 			=> true, 										// load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
									'quicktags' 		=> true, 										// load Quicktags, can be used to pass settings directly to Quicktags using an array()
									'sanitize' 			=> false,
								),
								'sanitize' 		=> false,
								'dependency'	=> array($prefixA . 'type', '==', 'break'),
							),
						),
					),
					// Section - Featured Media
					array(
						'name'      			=> 'ts_vcsc_timeline_media',
						'title'     			=> 'Featured Media',
						'icon'      			=> 'fa fa-picture-o',
						'fields'    			=> array(							
							array(
							  'type'    		=> 'heading',
							  'content' 		=> 'Featured Media',
							),
							array(
								'type'    		=> 'notice',
								'class'   		=> 'info',
								'content' 		=> 'A "Break" section visually interrupts the timeline column layout and can be used to mark the beginning of a new "era" in the timeline. The standard "Event" section is used to display detailed information about an event within the timeline.',
								'dependency'	=> array($prefixA . 'type', '==', 'event'),
							),
							array(
								'type'    		=> 'notice',
								'class'   		=> 'warning',
								'content' 		=> 'It is not possible to add any featured media item(s) to "Break" sections.',
								'dependency'	=> array($prefixA . 'type', '==', 'break'),
							),
							array(
								'id'      		=> $prefixB . 'featuredmedia',
								'type'    		=> 'select',
								'title'    		=> 'Featured Media:',
								'help'    		=> 'Select the featured media type for this timeline item.',
								'options' => array(
									"none" 					=> "None",
									"image" 				=> "Single Image",
									"slider"				=> "Image Slider",
									"youtube_default"		=> "YouTube Video (Lightbox; Auto Cover)",
									"youtube_custom"		=> "YouTube Video (Lightbox; Custom Cover)",
									"youtube_embed"			=> "YouTube Video (Direct iFrame)",
									"dailymotion_default"	=> "DailyMotion Video (Lightbox; Auto Cover)",
									"dailymotion_custom"	=> "DailyMotion Video (Lightbox; Custom Cover)",
									"dailymotion_embed"		=> "DailyMotion Video (Direct iFrame)",
									"vimeo_default"			=> "Vimeo Video (Lightbox; Auto Cover)",
									"vimeo_custom"			=> "Vimeo Video (Lightbox; Custom Cover)",
									"vimeo_embed"			=> "Vimeo Video (Direct iFrame)",
								),
								'default' 		=> 'none',
								'class'      	=> 'chosen',
								'dependency'	=> array($prefixA . 'type', '==', 'event'),
							),
							// Open in Lightbox
							array(
								'id'      		=> $prefixB . 'lightboxfeatured',
								'type'    		=> 'switcher',
								'title'    		=> 'Open in Lightbox:',
								'title' 		=> 'Check the box if you want to apply a lightbox to the featured media.',
								'default' 		=> true,
								'dependency'	=> array($prefixA . 'type|' . $prefixB . 'featuredmedia', '==|any', 'event|image,slider'),
							),	
							// Single Image Selection array('image','youtube_custom','dailymotion_custom','vimeo_custom'))
							array(
								'id' 			=> $prefixB . 'featuredimage',
								'type' 			=> 'image',
								'title' 		=> 'Select Image:',
								'help' 			=> 'Select an image for the timeline item.',
								'dependency'	=> array($prefixA . 'type|' . $prefixB . 'featuredmedia', '==|any', 'event|image,youtube_custom,dailymotion_custom,vimeo_custom'),
							),
							// Custom ALT + Title Attributes
							array(
								'id' 			=> $prefixB . 'attributealtvalue',
								'type' 			=> 'text',
								'title' 		=> 'Custom ALT Attribute:',
								'help' 			=> 'Enter a custom value for the ALT attribute for the image, otherwise file name will be set.',
								'dependency'	=> array($prefixA . 'type|' . $prefixB . 'featuredmedia', '==|any', 'event|image,youtube_custom,dailymotion_custom,vimeo_custom'),
							),
							array(
								'id' 			=> $prefixB . 'attributetitle',
								'type' 			=> 'text',
								'title' 		=> 'Custom Title Attribute:',
								'help' 			=> 'Enter a custom title for the media item, otherwise the timeline section title will be used.',
								'dependency'	=> array($prefixA . 'type|' . $prefixB . 'featuredmedia', '==|any', 'event|image,youtube_custom,dailymotion_custom,vimeo_custom'),
							),
							// Slider Selection array('slider'))
							array(
								'id'          	=> $prefixB . 'featuredslider',
								'type'        	=> 'gallery',
								'title'       	=> 'Select Images:',
								'help' 			=> 'Select the images for the event slider; move images to arrange order in which to display.',
								'add_title'   	=> 'Add Images',
								'edit_title'  	=> 'Edit Images',
								'clear_title' 	=> 'Remove Images',
								'dependency'	=> array($prefixA . 'type|' . $prefixB . 'featuredmedia', '==|any', 'event|slider'),
							),
							array(
								'id' 			=> $prefixB . 'slidertitles',
								'type' 			=> 'textarea',
								'title' 		=> 'Custom Title Attributes:',
								'help' 			=> 'Enter custom titles for each image; seperate title by line break and use empty lines for images without title.',
								'dependency'	=> array($prefixA . 'type|' . $prefixB . 'featuredmedia', '==|any', 'event|slider'),
							),
							array(
								'id' 			=> $prefixB . 'slidermaxheight',
								'type' 			=> 'number',
								'title' 		=> 'Maximum Image Height:',
								'help' 			=> 'Define the maximum height of the images in the slider in pixels; helpful to prevent unnecessary position adjustments of timeline sections due to various image size ratios.',
								'default' 		=> '400',
								'attributes' => array(
									'min'			=> 100,
									'max'			=> 800,
								),
								'dependency'	=> array($prefixA . 'type|' . $prefixB . 'featuredmedia', '==|any', 'event|slider'),
							),
							// YouTube Video array('youtube_default','youtube_custom','youtube_embed'))
							array(
								'id' 			=> $prefixB . 'featuredyoutubeurl',
								'type' 			=> 'text',
								'title'			=> 'YouTube Video URL:',
								'validate' 		=> 'urladdress',
								'dependency'	=> array($prefixA . 'type|' . $prefixB . 'featuredmedia', '==|any', 'event|youtube_default,youtube_custom,youtube_embed'),
							),
							array(
								'id'      		=> $prefixB . 'featuredyoutuberelated',
								'type'    		=> 'switcher',
								'title'    		=> 'Show Related Videos:',
								'help' 			=> 'Check the box if you want to show related videos at the end of the video.',
								'default' 		=> false,
								'dependency'	=> array($prefixA . 'type|' . $prefixB . 'featuredmedia', '==|any', 'event|youtube_default,youtube_custom,youtube_embed'),
							),
							array(
								'id'      		=> $prefixB . 'featuredyoutubeplay',
								'type'    		=> 'switcher',
								'title'    		=> 'Autoplay Video:',
								'help' 			=> 'Check the box if you want to auto-play the video once opened in the lightbox or on pageload (iFrame).',
								'default' 		=> false,
								'dependency'	=> array($prefixA . 'type|' . $prefixB . 'featuredmedia', '==|any', 'event|youtube_default,youtube_custom,youtube_embed'),
							),
							// DailyMotion Video array('dailymotion_default','dailymotion_embed','dailymotion_embed') )
							array(
								'id' 			=> $prefixB . 'featureddailymotionurl',
								'type' 			=> 'text',
								'title'			=> 'DailyMotion Video URL:',
								'validate' 		=> 'urladdress',
								'dependency'	=> array($prefixA . 'type|' . $prefixB . 'featuredmedia', '==|any', 'event|dailymotion_default,dailymotion_custom,dailymotion_embed'),
							),
							array(
								'id'      		=> $prefixB . 'featureddailymotionplay',
								'type'    		=> 'switcher',
								'title'    		=> 'Autoplay Video:',
								'help' 			=> 'Check the box if you want to auto-play the video once opened in the lightbox or on pageload (iFrame).',
								'default' 		=> false,
								'dependency'	=> array($prefixA . 'type|' . $prefixB . 'featuredmedia', '==|any', 'event|dailymotion_default,dailymotion_custom,dailymotion_embed'),
							),
							// Vimeo Video array('vimeo_default','vimeo_custom','vimeo_embed') )
							array(
								'id' 			=> $prefixB . 'featuredvimeourl',
								'type' 			=> 'text',
								'title'			=> 'Vimeo Video URL:',
								'validate' 		=> 'urladdress',
								'dependency'	=> array($prefixA . 'type|' . $prefixB . 'featuredmedia', '==|any', 'event|vimeo_default,vimeo_custom,vimeo_embed'),
							),
							array(
								'id'      		=> $prefixB . 'featuredvimeoplay',
								'type'    		=> 'switcher',
								'title'    		=> 'Autoplay Video:',
								'help' 			=> 'Check the box if you want to auto-play the video once opened in the lightbox or on pageload (iFrame).',
								'default' 		=> false,
								'dependency'	=> array($prefixA . 'type|' . $prefixB . 'featuredmedia', '==|any', 'event|vimeo_default,vimeo_custom,vimeo_embed'),
							),
							// Media Dimensions array('image','youtube_default','youtube_custom','youtube_embed','dailymotion_default','dailymotion_custom','dailymotion_embed','vimeo_default','vimeo_custom','vimeo_embed') )
							array(
								'id'      		=> $prefixB . 'featuredmediaheight',
								'type'    		=> 'select',
								'title'    		=> 'Height Setting:',
								'help'    		=> 'Select what height setting should be applied to the media element (change only if image height does not display correctly).',
								'options' => array(
									"height: 100%;" 				=> __( '100% Height Setting', "ts_visual_composer_extend" ),
									"height: auto;" 				=> __( 'Auto Height Setting', "ts_visual_composer_extend" ),
								),
								'class'      	=> 'chosen',
								'default' 		=> 'height: 100%;',
								'dependency'	=> array($prefixA . 'type|' . $prefixB . 'featuredmedia', '==|any', 'event|image,youtube_default,youtube_custom,youtube_embed,dailymotion_default,dailymotion_custom,dailymotion_embed,vimeo_default,vimeo_custom,vimeo_embed'),
							),
							array(
								'id' 			=> $prefixB . 'featuredmediawidth',
								'type' 			=> 'number',
								'title' 		=> 'Media Width:',
								'help' 			=> 'Define the media element width in percent (%).',
								'default' 		=> '100',
								'attributes' 	=> array(
									'min'			=> 50,
									'max'			=> 100,
								),
								'dependency'	=> array($prefixA . 'type|' . $prefixB . 'featuredmedia', '==|any', 'event|image,youtube_default,youtube_custom,youtube_embed,dailymotion_default,dailymotion_custom,dailymotion_embed,vimeo_default,vimeo_custom,vimeo_embed'),
							),
							array(
								'id'      		=> $prefixB . 'featuredmediaalign',
								'type'    		=> 'select',
								'title'    		=> 'Media Alignment:',
								'help'    		=> 'If not full width (100%), select how the media element should be aligned.',
								'options' => array(
									"center" 						=> __( 'Center', "ts_visual_composer_extend" ),
									"left" 							=> __( 'Left', "ts_visual_composer_extend" ),
									"right" 						=> __( 'Right', "ts_visual_composer_extend" ),
								),
								'class'      	=> 'chosen',
								'default' 		=> 'center',
								'dependency'	=> array($prefixA . 'type|' . $prefixB . 'featuredmedia', '==|any', 'event|image,youtube_default,youtube_custom,youtube_embed,dailymotion_default,dailymotion_custom,dailymotion_embed,vimeo_default,vimeo_custom,vimeo_embed'),
							),
						),
					),
					// Section - Lightbox Settings
					array(
						'name'      			=> 'ts_vcsc_timeline_lightbox',
						'title'     			=> 'Lightbox Settings',
						'icon'      			=> 'fa fa-desktop',
						'fields'    			=> array(
							array(
								'type'    		=> 'heading',
								'content' 		=> 'Lightbox Settings',
							),
							array(
								'type'    		=> 'notice',
								'class'   		=> 'warning',
								'content' 		=> 'The section type "Break" does not include any featured media and therefore does not allow for any lightbox usage either.',
								'dependency'	=> array($prefixA . 'type', '==', 'break'),
							),
							array(
								'type'    		=> 'notice',
								'class'   		=> 'warning',
								'content' 		=> 'The lightbox settings will only be applied if the featured media is set up to be used inside the lightbox.',
								'dependency'	=> array($prefixA . 'type', '==', 'event'),
							),
							array(
								'id'      		=> $prefixG . 'lightboxgroup',
								'type'    		=> 'switcher',
								'title'    		=> 'Create AutoGroup:',
								'help' 			=> 'Switch the toggle if you want the plugin to group this image with all other non-gallery images on the page.',
								'default' 		=> true,
								'dependency'	=> array($prefixA . 'type', '==', 'event'),
							),
							array(
								'id' 			=> $prefixG . 'lightboxgroupname',
								'type' 			=> 'text',
								'title' 		=> 'Group Name:',
								'help' 			=> 'Enter a custom group name to manually build group with other non-gallery items.',
								'dependency'	=> array($prefixG . 'lightboxgroup', '==', 'false'),
							),
							array(
								'id'      		=> $prefixG . 'lightboxeffect',
								'type'    		=> 'select',
								'title'    		=> 'Transition Effect:',
								'help'    		=> 'Select the transition effect to be used for the image in the lightbox.',
								'options' 		=> array_flip($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Lightbox_Animations),
								'class'			=> 'chosen',
								'default' 		=> 'random',
								'dependency'	=> array($prefixA . 'type', '==', 'event'),
							),
							array(
								'id'      		=> $prefixG . 'lightboxbacklight',
								'type'    		=> 'select',
								'title'   		=> 'Backlight Effect:',
								'help'    		=> 'Select the backlight effect for the image.',
								'options' => array(
									"auto" 							=> __( 'Auto Color', "ts_visual_composer_extend" ),
									"custom" 						=> __( 'Custom Color', "ts_visual_composer_extend" ),
									"hideit" 						=> __( 'No Backlight (only for simple Black Lightbox Overlay)', "ts_visual_composer_extend" ),
								),
								'class'			=> 'chosen',
								'default' 		=> 'auto',
								'dependency'	=> array($prefixA . 'type', '==', 'event'),
							),
							array(
								'id' 			=> $prefixG . 'lightboxbacklightcolor',
								'type' 			=> 'color_picker',
								'title' 		=> 'Custom Backlight Color:',
								'help' 			=> 'Define the backlight color for the lightbox image.',
								'default'  		=> '#ffffff',
								'dependency'	=> array($prefixA . 'type|' . $prefixG . 'lightboxbacklight', '==|==', 'event|custom'),
							),
						),
					),					
					// Section - Event Link
					array(
						'name'      			=> 'ts_vcsc_timeline_link',
						'title'     			=> 'Event Link',
						'icon'      			=> 'fa fa-link',
						'fields'    			=> array(
							array(
								'type'    		=> 'heading',
								'content' 		=> 'Event Link',
							),
							array(
								'type'    		=> 'notice',
								'class'   		=> 'info',
								'content' 		=> 'If you want to provide a link to an internal or external page that includes more detailed information about this event, use the provided options below.',
							),
							array(
								'id' 			=> $prefixE . 'dedicatedpage',
								'type' 			=> 'select',
								'title' 		=> 'Event Page:',
								'help' 			=> 'If existing, select a page that is dedicated to this particular event.',
								'options' 		=> $availablePages,
								'class'			=> 'chosen',
								'default' 		=> '-1',
							),
							array(
								'id' 			=> $prefixE . 'dedicatedlink',
								'type' 			=> 'text',
								'title' 		=> '<i class="dashicons dashicons-admin-links ts-post-font-icon"></i> External URL:',
								'validate' 		=> 'urladdress',
								'dependency'   	=> array($prefixE . 'dedicatedpage', '==', 'external'),
							),
							array(
								'id'      		=> $prefixE . 'dedicatedtarget',
								'type'    		=> 'switcher',
								'title'    		=> 'Open in New Tab/Window:',
								'help' 			=> 'Check how the link should be opened.',
								'default' 		=> true,
								'dependency'   	=> array($prefixE . 'dedicatedpage', '!=', '-1'),
							),				
							array(
								'id'      		=> $prefixE . 'dedicatedicon',
								'type'    		=> 'radio',
								'title'    		=> 'Button Icon:',
								'help'    		=> 'Select the icon that should be shown alongside the button label.',								
								'options' 		=> array(
									'visibility' 	=> '<i class="dashicons dashicons-visibility ts-post-font-icon"></i> ' . 'Eye',
									'info' 			=> '<i class="dashicons dashicons-info ts-post-font-icon"></i> ' . 'Info',
									'admin-links' 	=> '<i class="dashicons dashicons-admin-links ts-post-font-icon"></i> ' . 'Link',
									'search' 		=> '<i class="dashicons dashicons-search ts-post-font-icon"></i> ' . 'Search',
									'lightbulb'   	=> '<i class="dashicons dashicons-lightbulb ts-post-font-icon"></i> ' . 'Lightbulb',
									'admin-network'	=> '<i class="dashicons dashicons-admin-network ts-post-font-icon"></i> ' . 'Key',
									'book'   		=> '<i class="dashicons dashicons-book ts-post-font-icon"></i> ' . 'Boook',
									'awards'   		=> '<i class="dashicons dashicons-awards ts-post-font-icon"></i> ' . 'Award',
									'none'     		=> 'None',
								),
								'default' 		=> 'none',
								'dependency'   	=> array($prefixE . 'dedicatedpage', '!=', '-1'),
							),
							array(
								'id' 			=> $prefixE . 'dedicatedcolor',
								'type' 			=> 'color_picker',
								'title' 		=> 'Icon Color:',
								'default' 		=> '#ffffff',
								'dependency'   	=> array($prefixE . 'dedicatedicon', '!=', 'none'),
							),
							array(
								'id' 			=> $prefixE . 'dedicatedlabel',
								'type' 			=> 'text',
								'title' 		=> 'Button Label:',
								'default' 		=> 'Read More',
								'dependency'   	=> array($prefixE . 'dedicatedpage', '!=', '-1'),
							),
							array(
								'id' 			=> $prefixE . 'dedicatedtooltip',
								'type' 			=> 'text',
								'title' 		=> 'Button Tooltip:',
								'dependency'   	=> array($prefixE . 'dedicatedpage', '!=', '-1'),
							),
							array(
								'id' 			=> $prefixE . 'dedicatedwidth',
								'type' 			=> 'number',
								'title' 		=> 'Button Width:',
								'help' 			=> 'Define the button width in percent (%) of the available space.',
								'default' 		=> '100',
								'attributes' => array(
									'min'			=> 50,
									'max'			=> 100,
								),
								'dependency'   	=> array($prefixE . 'dedicatedpage', '!=', '-1'),
							),
							array(
								'id'      		=> $prefixE . 'dedicatedalign',
								'type'    		=> 'select',
								'title'    		=> 'Button Alignment:',
								'help'   	 	=> 'Select how the link button should be aligned.',
								'options' => array(
									"center" 						=> __( 'Center', "ts_visual_composer_extend" ),
									"left" 							=> __( 'Left', "ts_visual_composer_extend" ),
									"right" 						=> __( 'Right', "ts_visual_composer_extend" ),
								),
								'default' 		=> 'center',
								'class'			=> 'chosen',
								'dependency'   	=> array($prefixE . 'dedicatedpage', '!=', '-1'),
							),
							array(
								'id'      		=> $prefixE . 'dedicateddefault',
								'type'    		=> 'select',
								'title'    		=> 'Button Default Style:',
								'help'    		=> 'Select the default button style for the "Read More" Link.',
								'options' 		=> array(
									"ts-dual-buttons-color-default"										=> __( 'Default Style', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-sun-flower"									=> __( 'Sun Flower Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-orange-flat"									=> __( 'Orange Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-carrot-flat"									=> __( 'Carot Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-pumpkin-flat"								=> __( 'Pumpkin Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-alizarin-flat"								=> __( 'Alizarin Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-pomegranate-flat"							=> __( 'Pomegranate Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-turquoise-flat"								=> __( 'Turquoise Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-green-sea-flat"								=> __( 'Green Sea Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-emerald-flat"								=> __( 'Emerald Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-nephritis-flat"								=> __( 'Nephritis Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-peter-river-flat"							=> __( 'Peter River Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-belize-hole-flat"							=> __( 'Belize Hole Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-amethyst-flat"								=> __( 'Amethyst Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-wisteria-flat"								=> __( 'Wisteria Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-wet-asphalt-flat"							=> __( 'Wet Asphalt Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-midnight-blue-flat"							=> __( 'Midnight Blue Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-clouds-flat"									=> __( 'Clouds Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-silver-flat"									=> __( 'Silver Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-concrete-flat"								=> __( 'Concrete Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-asbestos-flat"								=> __( 'Asbestos Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-color-graphite-flat"								=> __( 'Graphite Flat', "ts_visual_composer_extend" ),
								),
								'default' 		=> 'ts-dual-buttons-color-default',
								'class'			=> 'chosen',
								'dependency'   	=> array($prefixE . 'dedicatedpage', '!=', '-1'),
							),
							array(
								'id'      		=> $prefixE . 'dedicatedhover',
								'type'    		=> 'select',
								'title'    		=> 'Button Hover Style:',
								'help'    		=> 'Select the hover button style for the "Read More" Link.',
								'options' 		=> array(
									"ts-dual-buttons-preview-default ts-dual-buttons-hover-default"							=> __( 'Default Style', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-sun-flower ts-dual-buttons-hover-sun-flower"					=> __( 'Sun Flower Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-orange-flat ts-dual-buttons-hover-orange-flat"					=> __( 'Orange Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-carrot-flat ts-dual-buttons-hover-carrot-flat"					=> __( 'Carot Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-pumpkin-flat ts-dual-buttons-hover-pumpkin-flat"				=> __( 'Pumpkin Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-alizarin-flat ts-dual-buttons-hover-alizarin-flat"				=> __( 'Alizarin Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-pomegranate-flat ts-dual-buttons-hover-pomegranate-flat"		=> __( 'Pomegranate Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-turquoise-flat ts-dual-buttons-hover-turquoise-flat"			=> __( 'Turquoise Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-green-sea-flat ts-dual-buttons-hover-green-sea-flat"			=> __( 'Green Sea Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-emerald-flat ts-dual-buttons-hover-emerald-flat"				=> __( 'Emerald Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-nephritis-flat ts-dual-buttons-hover-nephritis-flat"			=> __( 'Nephritis Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-peter-river-flat ts-dual-buttons-hover-peter-river-flat"		=> __( 'Peter River Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-belize-hole-flat ts-dual-buttons-hover-belize-hole-flat"		=> __( 'Belize Hole Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-amethyst-flat ts-dual-buttons-hover-amethyst-flat"				=> __( 'Amethyst Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-wisteria-flat ts-dual-buttons-hover-wisteria-flat"				=> __( 'Wisteria Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-wet-asphalt-flat ts-dual-buttons-hover-wet-asphalt-flat"		=> __( 'Wet Asphalt Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-midnight-blue-flat ts-dual-buttons-hover-midnight-blue-flat"	=> __( 'Midnight Blue Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-clouds-flat ts-dual-buttons-hover-clouds-flat"					=> __( 'Clouds Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-silver-flat ts-dual-buttons-hover-silver-flat"					=> __( 'Silver Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-concrete-flat ts-dual-buttons-hover-concrete-flat"				=> __( 'Concrete Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-asbestos-flat ts-dual-buttons-hover-asbestos-flat"				=> __( 'Asbestos Flat', "ts_visual_composer_extend" ),
									"ts-dual-buttons-preview-graphite-flat ts-dual-buttons-hover-graphite-flat"				=> __( 'Graphite Flat', "ts_visual_composer_extend" ),
								),
								'default' 		=> 'ts-dual-buttons-preview-default ts-dual-buttons-hover-default',
								'class'			=> 'chosen',
								'dependency'   	=> array($prefixE . 'dedicatedpage', '!=', '-1'),
							),
						),
					),
					// Section - Event Tooltip
					array(
						'name'      			=> 'ts_vcsc_timeline_tooltip',
						'title'     			=> 'Event Tooltip',
						'icon'      			=> 'fa fa-comment',
						'fields'    			=> array(
							array(
								'type'    		=> 'heading',
								'content' 		=> 'Event Tooltip',
							),
							array(
								'type'    		=> 'notice',
								'class'   		=> 'info',
								'content' 		=> 'If you want to provide some more information, but do not want to show it in the main content, you can use the optional tooltip for the timeline section. The tooltip will be applied to the overall timeline section.',
							),
							array(
								'id' 			=> $prefixF . 'tooltiptext',
								'type'			=> 'wysiwyg',
								'title' 		=> 'Tooltip Content:',
								'help' 			=> 'Enter a tooltip for the timeline event. Basic HTML code can be used for styling.',
								'settings' 		=> array(
									'textarea_rows' => 5,
									'tinymce'       => false,
									'media_buttons' => false,
								),
							),
							array(
								'id'      		=> $prefixF . 'tooltipposition',
								'type'    		=> 'select',
								'title'    		=> 'Tooltip Position:',
								'help'    		=> 'Select the tooltip position.',
								'options' 		=> array(
									"top" 							=> __( 'Top', "ts_visual_composer_extend" ),
									"bottom" 						=> __( 'Bottom', "ts_visual_composer_extend" ),
								),
								'default' 		=> 'top',
								'class'			=> 'chosen',
							),
							array(
								'id'      		=> $prefixF . 'tooltipstyle',
								'type'    		=> 'select',
								'title'    		=> 'Tooltip Style:',
								'help'    		=> 'Select the tooltip style.',
								'options' 		=> array(
									"black" 						=> __( 'Black', "ts_visual_composer_extend" ),
									"gray" 							=> __( 'Gray', "ts_visual_composer_extend" ),
									"green" 						=> __( 'Green', "ts_visual_composer_extend" ),
									"blue" 							=> __( 'Blue', "ts_visual_composer_extend" ),
									"red" 							=> __( 'Red', "ts_visual_composer_extend" ),
									"orange" 						=> __( 'Orange', "ts_visual_composer_extend" ),
									"yellow" 						=> __( 'Yellow', "ts_visual_composer_extend" ),
									"purple" 						=> __( 'Purple', "ts_visual_composer_extend" ),
									"pink" 							=> __( 'Pink', "ts_visual_composer_extend" ),
									"white" 						=> __( 'White', "ts_visual_composer_extend" ),
								),
								'default' 		=> 'black',
								'class'			=> 'chosen',
							),
						),
					),
					// Section Custom Styling
					array(
						'name'      			=> 'ts_vcsc_timeline_styling',
						'title'     			=> 'Custom Styling',
						'icon'      			=> 'fa fa-paint-brush',
						'fields'    			=> array(
							array(
								'type'    		=> 'heading',
								'content' 		=> 'Custom Styling',
							),
							array(
								'type'    		=> 'notice',
								'class'   		=> 'info',
								'content' 		=> 'Use the controls below to customize some color and style settings for this timeline section. All settings will override the global color and style settings that are define in the
								timeline element itself.',
							),
							array(
								'id' 			=> $prefixC . 'eventbackcolor',
								'type' 			=> 'color_picker',
								'default'  		=> '#ffffff',
								'title' 		=> 'Section: Background Color:',
								'help' 			=> 'Define the overall background color for this timeline section.',								
								'dependency'	=> array($prefixA . 'type', '==', 'event'),
							),
							array(
								'id' 			=> $prefixC . 'eventtitlecolor',
								'type' 			=> 'color_picker',
								'default'  		=> '#676767',
								'title' 		=> 'Title: Font Color:',
								'help' 			=> 'Define the font color for the title in this timeline section.',								
								'dependency'	=> array($prefixA . 'type', '==', 'event'),
							),
							array(
								'id' 			=> $prefixC . 'eventcontentcolor',
								'type' 			=> 'color_picker',
								'default'  		=> '#676767',
								'title' 		=> 'Content: Font Color:',
								'help' 			=> 'Define the font color for the content in this timeline section.',								
								'dependency'	=> array($prefixA . 'type', '==', 'event'),
							),
							array(
								'id'    		=> $prefixC . 'eventdatecoloricon',
								'title'    		=> 'Date/Time: Icon Color:',
								'type' 			=> 'color_picker',
								'default'  		=> '#777678',
								'help'			=> 'Define the color that should be used for the icon next to the date/time string.',
								'dependency'	=> array($prefixA . 'type|' . $prefixC . 'eventdateicon', '==|!=', 'event|none'),
							),
							array(
								'id'    		=> $prefixC . 'eventdatecolordate',
								'title'    		=> 'Date/Time: Font Color:',
								'type' 			=> 'color_picker',
								'default'  		=> '#777678',
								'help'			=> 'Define the font color that should be used for the date/time string.',
								'dependency'	=> array($prefixA . 'type', '==', 'event'),
							),
							array(
								'id'    		=> $prefixC . 'eventdatecolorback',
								'title'    		=> 'Date/Time: Background Color:',
								'type' 			=> 'color_picker',
								'default'  		=> '#f5f5f5',
								'help'			=> 'Define the over all background color for the date/time string and icon.',
								'dependency'	=> array($prefixA . 'type', '==', 'event'),
							),
							array(
								'id' 			=> $prefixD . 'breakbackground',
								'type' 			=> 'color_picker',
								'default'  		=> '#dadada',
								'title' 		=> 'Section: Background Color:',
								'help' 			=> 'Define the background color for the break section.',								
								'dependency'	=> array($prefixA . 'type', '==', 'break'),
							),
							array(
								'id' 			=> $prefixD . 'breaktitlecolor',
								'type' 			=> 'color_picker',
								'default'  		=> '#676767',
								'title' 		=> 'Title: Font Color:',
								'help' 			=> 'Define the font color for the title in this timeline break item.',								
								'dependency'	=> array($prefixA . 'type', '==', 'break'),
							),
							array(
								'id' 			=> $prefixD . 'breakcontentcolor',
								'type' 			=> 'color_picker',
								'default'  		=> '#676767',
								'title' 		=> 'Content: Font Color:',
								'help' 			=> 'Define the font color for the content in this timeline break item.',								
								'dependency'	=> array($prefixA . 'type', '==', 'break'),
							),
						),
					),
				),
			);
			// Hidden Migration Setting
			$meta_boxes[]      				= array(
				'id'            			=> 'ts_vcsc_custompost_migrated',
				'title'         			=> 'Timeline Migration',
				'post_type'     			=> 'ts_timeline',
				'context'       			=> 'normal',
				'priority'      			=> 'high',
				'sections'      			=> array(
					// Migration Section
					array(
						'name'      		=> 'ts_vcsc_custompost_section',
						'title'     		=> 'Timeline Migration',
						'icon'      		=> 'fa fa-check-square-o',
						'fields'    		=> array(
							array(
								'id'		=> 'ts_vcsc_timeline_migrated',
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
	function TS_VCSC_Timeline_Post_Files() {
		global $pagenow;		
		$screen = TS_VCSC_GetCurrentPostType();
		if ($screen == 'ts_timeline') {
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
	
	// Remove RevSlider + Essential Grid Metaboxes
	function TS_VCSC_Timeline_RemoveExternalMetaboxes() { 
		global $pagenow;
		$screen = TS_VCSC_GetCurrentPostType();
		if ($screen=='ts_timeline') {
			if ($pagenow=='post-new.php' || $pagenow=='post.php') {
				remove_meta_box('eg-meta-box', 'ts_timeline', 'normal'); 
				remove_meta_box('mymetabox_revslider_0', 'ts_timeline', 'normal'); 
			} 
		} 
	}
	
	// Call All Routines
	if (is_admin()) {
		add_filter('post_updated_messages', 						'TS_VCSC_Timeline_Post_Messages');
		add_action('contextual_help', 								'TS_VCSC_Timeline_Post_Help', 				10, 3);
		add_filter('cs_metabox_options', 							'TS_VCSC_Timeline_Codestar');
		add_action('admin_enqueue_scripts', 						'TS_VCSC_Timeline_Post_Files', 				9999999999);
		add_action('add_meta_boxes', 								'TS_VCSC_Timeline_RemoveExternalMetaboxes', 9999999999);
	}
?>