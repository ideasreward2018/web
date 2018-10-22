<?php
	function TS_VCSC_DownpageGetData($id, $type) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		// Set Return Variable
		$Downpage_Return					= "";
		// Check for Codestar Migration
		$codestarRetrieve					= "false";
		$codestarMigrated 					= get_post_meta($id, 'ts_vcsc_custompost_migrated', true);
		if (!empty($codestarMigrated)) {
			$codestarRetrieve				= "true";			
		}
		// Retrieve Codestar Data
		if (($codestarRetrieve == "true") && (($type == 'title') || ($type == 'bloginfo') || ($type == 'style'))) {
			$Downpage_Settings				= get_post_meta($id, 'ts_vcsc_downpages_layout', true);
		} else {
			$Downpage_Settings				= array();
		}
		// Create Return Data
		if ($type == "expiration") {
			$Timer_Scope					= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['timer'];
			$Timer_Current					= time();
			if ($Timer_Scope == "dateonly") {
				$Timer_Target				= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['dateonly'];
				$Timer_Target				= strtotime($Timer_Target);
				$Timer_Target				= $Timer_Target - $Timer_Current;
			} else if ($Timer_Scope == "datetime") {
				$Timer_Target				= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['datetime'];
				$Timer_Target				= strtotime($Timer_Target);
				$Timer_Target				= $Timer_Target - $Timer_Current;
			} else if ($Timer_Scope == "timerange") {
				$Timer_Target				= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['timerange'];
				$Timer_Target				= strtotime($Timer_Target);
				$Timer_Target				= $Timer_Target - $Timer_Current;
			} else if ($Timer_Scope == "endless") {
				$Timer_Target				= '86400';
			}
			$Downpage_Return				= $Timer_Target;
		} else if ($type == 'status') {
			$Downpage_Return				= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['downstatus'];
		} else if ($type == 'title') {
			$Downpage_TitleSource			= "site";
			if ($codestarRetrieve == "true") {
				if (isset($Downpage_Settings['ts_vcsc_downpages_meta_titlesource'])) {
					$Downpage_TitleSource	= $Downpage_Settings['ts_vcsc_downpages_meta_titlesource'];
				}
			} else {
				$Downpage_TitleSource		= get_post_meta($id, 'ts_vcsc_downpages_meta_titlesource', true);
			}
			if ($Downpage_TitleSource == "site") {
				$Downpage_Return			= get_bloginfo('name');
			} else if ($Downpage_TitleSource == "page") {
				$Downpage_Return			= wp_title('', false);
			} else if ($Downpage_TitleSource == "custom") {
				if ($codestarRetrieve == "true") {
					if (isset($Downpage_Settings['ts_vcsc_downpages_meta_titlecustom'])) {
						$Downpage_Return	= $Downpage_Settings['ts_vcsc_downpages_meta_titlecustom'];
					}
				} else {
					$Downpage_Return		= get_post_meta($id, 'ts_vcsc_downpages_meta_titlecustom', true);
				}
			}
		} else if ($type == 'bloginfo') {
			$Downpage_InfoSource			= "site";
			if ($codestarRetrieve == "true") {
				if (isset($Downpage_Settings['ts_vcsc_downpages_meta_infosource'])) {
					$Downpage_InfoSource	= $Downpage_Settings['ts_vcsc_downpages_meta_infosource'];
				}
			} else {
				$Downpage_InfoSource		= get_post_meta($id, 'ts_vcsc_downpages_meta_infosource', true);
			}
			if ($Downpage_InfoSource == "site") {
				$Downpage_Return			= get_bloginfo('description');
			} else if ($Downpage_InfoSource == "custom") {
				if ($codestarRetrieve == "true") {
					if (isset($Downpage_Settings['ts_vcsc_downpages_meta_infocustom'])) {
						$Downpage_Return	= $Downpage_Settings['ts_vcsc_downpages_meta_infocustom'];
					}
				} else {
					$Downpage_Return		= get_post_meta($id, 'ts_vcsc_downpages_meta_infocustom', true);
				}
			}
		} else if ($type == 'locale') {
			$Downpage_Return				= str_replace("_", "-", get_locale());
		} else if ($type == 'style') {
			if ($codestarRetrieve == "true") {
				if (isset($Downpage_Settings['ts_vcsc_downpages_layout_boxed'])) {
					$Downpage_Boxed			= ($Downpage_Settings['ts_vcsc_downpages_layout_boxed'] == true ? "true" : "false");
				} else {
					$Downpage_Boxed			= "true";
				}
				if (isset($Downpage_Settings['ts_vcsc_downpages_layout_spacing'])) {
					$Downpage_Spacing		= ($Downpage_Settings['ts_vcsc_downpages_layout_spacing'] == true ? "true" : "false");
				} else {
					$Downpage_Spacing		= "true";	
				}
				if (isset($Downpage_Settings['ts_vcsc_downpages_layout_width'])) {
					$Downpage_Width			= $Downpage_Settings['ts_vcsc_downpages_layout_width'];
				} else {
					$Downpage_Width			= "80";	
				}
				if (isset($Downpage_Settings['ts_vcsc_downpages_layout_maxwidth'])) {
					$Downpage_Maxwidth		= $Downpage_Settings['ts_vcsc_downpages_layout_maxwidth'];
				} else {
					$Downpage_Maxwidth		= "1280";	
				}
				if (isset($Downpage_Settings['ts_vcsc_downpages_layout_margins'])) {
					$Downpage_Margins		= $Downpage_Settings['ts_vcsc_downpages_layout_margins'];
				} else {
					$Downpage_Margins		= "50";	
				}
				if (isset($Downpage_Settings['ts_vcsc_downpages_layout_paddingv'])) {
					$Downpage_PaddingV		= $Downpage_Settings['ts_vcsc_downpages_layout_paddingv'];
				} else {
					$Downpage_PaddingV		= "20";	
				}
				if (isset($Downpage_Settings['ts_vcsc_downpages_layout_paddingh'])) {
					$Downpage_PaddingH		= $Downpage_Settings['ts_vcsc_downpages_layout_paddingh'];
				} else {
					$Downpage_PaddingH		= "20";	
				}
				if (isset($Downpage_Settings['ts_vcsc_downpages_layout_background'])) {
					$Downpage_Background	= $Downpage_Settings['ts_vcsc_downpages_layout_background'];
				} else {
					$Downpage_Background	= "#ffffff";	
				}
				if (isset($Downpage_Settings['ts_vcsc_downpages_layout_fontcolor'])) {
					$Downpage_Fontcolor		= $Downpage_Settings['ts_vcsc_downpages_layout_fontcolor'];
				} else {
					$Downpage_Fontcolor		= "#696969";	
				}
			} else {				
				$Downpage_Boxed				= get_post_meta($id, 'ts_vcsc_downpages_layout_boxed', true);
				$Downpage_Spacing			= get_post_meta($id, 'ts_vcsc_downpages_layout_spacing', true);
				$Downpage_Width				= get_post_meta($id, 'ts_vcsc_downpages_layout_width', true);
				$Downpage_Maxwidth			= get_post_meta($id, 'ts_vcsc_downpages_layout_maxwidth', true);
				$Downpage_Margins			= get_post_meta($id, 'ts_vcsc_downpages_layout_margins', true);
				$Downpage_PaddingV			= get_post_meta($id, 'ts_vcsc_downpages_layout_paddingv', true);
				$Downpage_PaddingH			= get_post_meta($id, 'ts_vcsc_downpages_layout_paddingh', true);
				$Downpage_Background		= get_post_meta($id, 'ts_vcsc_downpages_layout_background', true);
				$Downpage_Fontcolor			= get_post_meta($id, 'ts_vcsc_downpages_layout_fontcolor', true);
			}
			// Create Downpage CSS Settings
			$Downpage_Styling 				= "";
			$Downpage_Return .= '<style id="ts-downpage-styling-' . $id . '" media="all" type="text/css">';
				if (is_admin_bar_showing()) {
					$Downpage_Styling .= 'html {
						margin-top:			0px !important;
					}';
				}
				$Downpage_Styling .= 'body {
					width: 					100%;
					height: 				100%;
					margin: 				0;
					padding: 				0;
					display: 				block;
					position: 				relative;
					-webkit-box-sizing:		border-box;
					-moz-box-sizing:		border-box;
					box-sizing:				border-box;
				}';
				if (is_admin_bar_showing()) {
					$Downpage_Styling .= '#wpadminbar {
						display:			none !important;
					}';
				}
				$Downpage_Styling .= '.ts-downpage-container {
					display: 				block;
					position: 				relative;
					width: 					' . ($Downpage_Boxed == "false" ? "100%" : ($Downpage_Width . '%')) . ';
					max-width: 				' . ($Downpage_Boxed == "false" ? "100%" : ($Downpage_Maxwidth . 'px')) . ';
					margin: 				' . ((($Downpage_Boxed == "false") || ($Downpage_Spacing == "false")) ? "0 auto" : ($Downpage_Margins . 'px auto')) . ';
					padding: 				' . ($Downpage_PaddingV) . 'px ' . ($Downpage_PaddingH) . 'px;
					background: 			' . ($Downpage_Background) . ';
					color: 					' . ($Downpage_Fontcolor) . ';
					-webkit-box-sizing:		border-box;
					-moz-box-sizing:		border-box;
					box-sizing:				border-box;
				}';
			$Downpage_Return .= TS_VCSC_MinifyJS($Downpage_Styling);
			$Downpage_Return .= '</style>';
			
		};
		// Send Return Data
		return $Downpage_Return;
	}

	// Set Maintenance Header Status
	$Downpage_ID							= get_the_ID();
	$Downpage_Protocol 						= $_SERVER["SERVER_PROTOCOL"];
	$Downpage_Status						= TS_VCSC_DownpageGetData($Downpage_ID, 'status');
	$Downpage_RetryAfter					= TS_VCSC_DownpageGetData($Downpage_ID, 'expiration');
	if ("HTTP/1.1" != $Downpage_Protocol && "HTTP/1.0" != $Downpage_Protocol) {
		$Downpage_Protocol					= "HTTP/1.0";
	}
	if ($Downpage_Status == "503") {
		header("$Downpage_Protocol 503 Service Temporarily Unavailable", true, 503);
		header("Status: 503 Service Temporarily Unavailable");
		header("Content-Type: text/html; charset=utf-8");
		header("Retry-After: $Downpage_RetryAfter");
	} else {
		header("Content-Type: text/html; charset=utf-8");
	}
	
	// Create Page Output
	echo '<!DOCTYPE html>';
	echo '<html lang="' . TS_VCSC_DownpageGetData($Downpage_ID, 'locale') . '" xmlns="http://www.w3.org/1999/xhtml">';
		echo '<head>';
			echo '<title>' . TS_VCSC_DownpageGetData($Downpage_ID, 'title') . '</title>';
			echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">';
			echo '<meta name="description" content="' . TS_VCSC_DownpageGetData($Downpage_ID, 'bloginfo') . '" />';			
			wp_head();							
			echo TS_VCSC_DownpageGetData($Downpage_ID, 'style');
		echo '</head>';
		echo '<body>';
			echo '<div class="ts-downpage-container">';
				if (have_posts()) {
					while (have_posts()) {
						the_post();
						the_content();
					}
				}
			echo '</div>';
			wp_footer();
		echo '</body>';
	echo '</html>';
?>