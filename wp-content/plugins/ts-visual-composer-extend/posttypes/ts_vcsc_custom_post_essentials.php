<?php
	global $VISUAL_COMPOSER_EXTENSIONS;
	
	// Add Category Filters to Custom Post Types
	// -----------------------------------------
	if (!class_exists('TS_VCSC_Tax_CTP_Filter')){
		class TS_VCSC_Tax_CTP_Filter {
			/**
			 * __construct 
			 * @author Ohad Raz <admin@bainternet.info>
			 * @since 0.1
			 * @param array $cpt [description]
			 */
			function __construct($cpt = array()){
				$this->cpt = $cpt;
				// Adding a Taxonomy Filter to Admin List for a Custom Post Type
				add_action( 'restrict_manage_posts', array($this, 'TS_VCSC_My_Restrict_Manage_Posts' ));
			}
			/**
			 * TS_VCSC_My_Restrict_Manage_Posts  add the slelect dropdown per taxonomy
			 * @author Ohad Raz <admin@bainternet.info>
			 * @since 0.1
			 * @return void
			 */
			public function TS_VCSC_My_Restrict_Manage_Posts() {
				// only display these taxonomy filters on desired custom post_type listings
				global $typenow;
				$types = array_keys($this->cpt);
				if (in_array($typenow, $types)) {
					// create an array of taxonomy slugs you want to filter by - if you want to retrieve all taxonomies, could use get_taxonomies() to build the list
					$filters = $this->cpt[$typenow];
					foreach ($filters as $tax_slug) {
						// retrieve the taxonomy object
						$tax_obj = get_taxonomy($tax_slug);
						$tax_name = $tax_obj->labels->name;
						// output html for taxonomy dropdown filter
						echo "<select name='".strtolower($tax_slug)."' id='".strtolower($tax_slug)."' class='postform'>";
						echo "<option value=''>Show All $tax_name</option>";
						$this->TS_VCSC_Generate_Taxonomy_Options($tax_slug,0,0,(isset($_GET[strtolower($tax_slug)])? $_GET[strtolower($tax_slug)] : null));
						echo "</select>";
					}
				}
			}
			/**
			 * TS_VCSC_Generate_Taxonomy_Options generate dropdown
			 * @author Ohad Raz <admin@bainternet.info>
			 * @since 0.1
			 * @param  string  $tax_slug 
			 * @param  string  $parent   
			 * @param  integer $level    
			 * @param  string  $selected 
			 * @return void            
			 */
			public function TS_VCSC_Generate_Taxonomy_Options($tax_slug, $parent = '', $level = 0,$selected = null) {
				$args = array('show_empty' => 1);
				if(!is_null($parent)) {
					$args = array('parent' => $parent);
				}
				$terms = get_terms($tax_slug,$args);
				$tab='';
				for($i=0;$i<$level;$i++){
					$tab.='--';
				}
				foreach ($terms as $term) {
					// output each select option line, check against the last $_GET to show the current option selected
					echo '<option value='. $term->slug, $selected == $term->slug ? ' selected="selected"' : '','>' .$tab. $term->name .' (' . $term->count .')</option>';
					$this->TS_VCSC_Generate_Taxonomy_Options($tax_slug, $term->term_id, $level+1,$selected);
				}
			}
		}
	}
	
	// Pull & Merge Custom Post Type Name
    $TS_VCSC_CustomPostTypeNames_Custom			= get_option('ts_vcsc_extend_settings_postTypeNamesCustom', '');
    if (!is_array($TS_VCSC_CustomPostTypeNames_Custom)) {
        $TS_VCSC_CustomPostTypeNames_Custom		= array();
    }
    $this->TS_VCSC_CustomPostTypesNames_Final 	= array(
        'ts_widgets'							=> ((array_key_exists('ts_widgets', $TS_VCSC_CustomPostTypeNames_Custom))		? $TS_VCSC_CustomPostTypeNames_Custom['ts_widgets']			: $this->TS_VCSC_PostTypeMenuNames_Default['ts_widgets']),
		'ts_downtime'							=> ((array_key_exists('ts_downtime', $TS_VCSC_CustomPostTypeNames_Custom))		? $TS_VCSC_CustomPostTypeNames_Custom['ts_downtime']		: $this->TS_VCSC_PostTypeMenuNames_Default['ts_downtime']),
		'ts_timeline'							=> ((array_key_exists('ts_timeline', $TS_VCSC_CustomPostTypeNames_Custom))		? $TS_VCSC_CustomPostTypeNames_Custom['ts_timeline']        : $this->TS_VCSC_PostTypeMenuNames_Default['ts_timeline']),
		'ts_team'								=> ((array_key_exists('ts_team', $TS_VCSC_CustomPostTypeNames_Custom))			? $TS_VCSC_CustomPostTypeNames_Custom['ts_team']         	: $this->TS_VCSC_PostTypeMenuNames_Default['ts_team']),
		'ts_testimonials'						=> ((array_key_exists('ts_testimonials', $TS_VCSC_CustomPostTypeNames_Custom))	? $TS_VCSC_CustomPostTypeNames_Custom['ts_testimonials']	: $this->TS_VCSC_PostTypeMenuNames_Default['ts_testimonials']),
		'ts_skillsets'							=> ((array_key_exists('ts_skillsets', $TS_VCSC_CustomPostTypeNames_Custom))		? $TS_VCSC_CustomPostTypeNames_Custom['ts_skillsets']		: $this->TS_VCSC_PostTypeMenuNames_Default['ts_skillsets']),
		'ts_logos'								=> ((array_key_exists('ts_logos', $TS_VCSC_CustomPostTypeNames_Custom))			? $TS_VCSC_CustomPostTypeNames_Custom['ts_logos']			: $this->TS_VCSC_PostTypeMenuNames_Default['ts_logos']),
	);

	// Enable/Disable Visual Composer for Selected Custom Post Types
	function TS_VCSC_ForceSet_Composer_PostTypes() {
		global $VISUAL_COMPOSER_EXTENSIONS;
		global $pagenow;
		$screen 								= TS_VCSC_GetCurrentPostType();
		if (($screen=='ts_timeline') || ($screen=='ts_team') || ($screen=='ts_testimonials') || ($screen=='ts_skillsets') || ($screen=='ts_logos')) {
			if ($pagenow=='post-new.php' || $pagenow=='post.php') {				
				add_filter('vc_role_access_with_post_types_get_state', 			'__return_false');
				add_filter('vc_role_access_with_backend_editor_get_state', 		'__return_false');
				add_filter('vc_role_access_with_frontend_editor_get_state', 	'__return_false');
				add_filter('vc_check_post_type_validation', 					'__return_false');
			}
		} else if (($screen=='ts_widgets') || ($screen=='ts_downtime')) {
			if ($pagenow=='post-new.php' || $pagenow=='post.php') {
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_UseAutoAssignmentVC == "true") {
					add_filter('vc_role_access_with_post_types_get_state',		'__return_true');
					add_filter('vc_role_access_with_backend_editor_get_state',	'__return_true');
					add_filter('vc_role_access_with_frontend_editor_get_state',	'__return_false');
					add_filter('vc_check_post_type_validation',					'__return_true');
				} else {
					add_filter('vc_role_access_with_frontend_editor_get_state',	'__return_false');
				}
			}
		}
	}
	if (defined('WPB_VC_VERSION')){
		add_action('admin_init', 'TS_VCSC_ForceSet_Composer_PostTypes');
	}
?>