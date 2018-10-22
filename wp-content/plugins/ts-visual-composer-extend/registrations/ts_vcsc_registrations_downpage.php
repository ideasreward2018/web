<?php
	if (!class_exists('TS_VCSC_Downtime_Mode')){
		class TS_VCSC_Downtime_Mode {
			// Declare Private Variables
			private $Target_Page;
			private $Target_Valid;
			private $Target_Maintenance;
			private $Timer_Active;
			private $Timer_Scope;
			private $Timer_Timezone;
			private $Timer_Target;
			private $UserRoles_Allowed;
			private $UserRoles_Confirm;
			private $Override_Active;
			private $Override_Slug;
			private $Override_Valid;
			
			// Initialize Downpage
			function __construct() {				
				global $VISUAL_COMPOSER_EXTENSIONS;
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['active'] == 1) {
					// Check for Missing Page ID / maintenance.php File
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['singlepage'] == 1) {
						$this->Target_Page							= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['alldevices'];
					} else {
						if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_MobileDetector_Desktop == "true") {
							$this->Target_Page						= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['desktop'];
						} else if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_MobileDetector_Tablet == "true") {
							$this->Target_Page						= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['tablet'];
						} else if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_MobileDetector_Mobile == "true") {
							$this->Target_Page						= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['mobile'];
						}
					}
					if ($this->Target_Page != '') {
						$this->Target_Valid							= "true";
						if ($this->Target_Page == 'maintenance') {
							if ($this->TS_VCSC_DownTime_CheckMaintenancePHP() == "true") {
								$this->Target_Maintenance			= "true";
							} else {
								$this->Target_Maintenance			= "false";
								$this->Target_Valid					= "false";
							}
						}
					} else {
						$this->Target_Valid							= "false";
					}
					// Check for Date/Time/Range Match
					$this->Timer_Scope								= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['timer'];
					$this->Timer_Timezone							= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['timezone'];
					$this->Timer_Active 							= "false";
					if ($this->Timer_Scope == "dateonly") {
						$this->Timer_Target							= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['dateonly'];
						$this->Timer_Active							= $this->TS_VCSC_DownTime_CheckForDownStatus($this->Timer_Timezone, $this->Timer_Scope);
					} else if ($this->Timer_Scope == "datetime") {
						$this->Timer_Target							= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['datetime'];
						$this->Timer_Active							= $this->TS_VCSC_DownTime_CheckForDownStatus($this->Timer_Timezone, $this->Timer_Scope);
					} else if ($this->Timer_Scope == "timerange") {
						$this->Timer_Target							= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['timerange'];
						$this->Timer_Active							= $this->TS_VCSC_DownTime_CheckForDownStatus($this->Timer_Timezone, $this->Timer_Scope);
					} else if ($this->Timer_Scope == "endless") {
						$this->Timer_Target							= '';
						$this->Timer_Active							= "true";
					}
					// Check for User Role Exclusion
					$this->UserRoles_Allowed 						= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['userroles'];
					$this->UserRoles_Allowed						= explode(",", $this->UserRoles_Allowed);
					$this->UserRoles_Confirm						= "false";
					foreach ($this->UserRoles_Allowed as $role) {
						if (TS_VCSC_CheckCurrentUserRoles($role) == true) {
							$this->UserRoles_Confirm				= "true";
							break;
						}
					}
					// Handler for Preview URL
					$this->Override_Active							= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['override'];
					$this->Override_Slug							= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['preview'];
					$this->Override_Valid							= "false";
					if (($this->Override_Active == 1) || ($this->Override_Active == "1")) {
						// Check for Override Slug in URL
						$this->TS_VCSC_DownTime_CheckOverrideSlug($this->Override_Slug);				
						// Check if Override Cookie Valid
						if ($this->TS_VCSC_DownTime_CookieValid() === true) {
							$this->Override_Valid					= "true";
						}
					}
					// Initialize Downtime Mode
					if ((!is_admin()) && ($this->UserRoles_Confirm == "false") && ($this->Timer_Active == "true") && ($this->Override_Valid == "false") && ($this->Target_Valid == "true")) {
						remove_action('template_redirect', 			'redirect_canonical');
						if ($this->Target_Maintenance == "true") {
							add_action('template_redirect', 		array($this, 'TS_VCSC_DownTime_RenderTemplate'), 10);
						} else {
							add_action('template_redirect', 		array($this, 'TS_VCSC_DownTime_RenderHTTPCode'), 10);
							add_action('template_redirect', 		array($this, 'TS_VCSC_DownTime_RenderTemplate'), 11);
							add_action('parse_request', 			array($this, 'TS_VCSC_DownTime_ParseRequest'));
						}
					}
					// Create Admin Bar Message
					add_action('admin_bar_menu',					array($this, 'TS_VCSC_DownTime_AdminBarMessage'), 1000);
				}
			}
			
			// Show Downtime Message in WordPress Admin Bar
			function TS_VCSC_DownTime_AdminBarMessage(){
				global $wp_admin_bar;
				if ($this->Timer_Active == "true") {
					$wp_admin_bar->add_menu( array(
						'id'     	=> 'ts-downtime-mode-message',
						'href' 		=> admin_url() . 'admin.php?page=TS_VCSC_Downtime',
						'parent' 	=> 'top-secondary',
						'title'  	=> apply_filters('debug_bar_title', __('Downtime Mode Active', 'ts_visual_composer_extend')),
						'meta'   	=> array('class' => 'ts-downtime-mode-message-active'),
					));
				} else {
					$wp_admin_bar->add_menu( array(
						'id'     	=> 'ts-downtime-mode-message',
						'href' 		=> admin_url() . 'admin.php?page=TS_VCSC_Downtime',
						'parent' 	=> 'top-secondary',
						'title'  	=> apply_filters('debug_bar_title', __('Downtime Mode Expired', 'ts_visual_composer_extend')),
						'meta'   	=> array('class' => 'ts-downtime-mode-message-expired'),
					));
				}
			}

			// Routines for URL Override Routine
			function TS_VCSC_DownTime_CheckOverrideSlug($overrideSlug) {
				if ((!$overrideSlug) || ($overrideSlug == "")) {
					return;
				}
				if ($this->TS_VCSC_DownTime_CheckIsSSL()) {
					$current 									= 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
				} else {
					$current 									= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
				}				
				$cleaned 										= str_replace("?" . $overrideSlug, "", $current);
				$siteurl										= site_url();
				if (strpos($current, "?" . $overrideSlug) !== false){
					$this->TS_VCSC_DownTime_CookieSet();
					wp_redirect($cleaned);
					exit;
				}
			}
			function TS_VCSC_DownTime_CheckIsSSL() {
				return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
			}
			function TS_VCSC_DownTime_CookieSet() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				$duration 										= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['cookie'];
				if (($duration === false) || ($duration === '') || ($duration < 0)) {
					$duration 									= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Defaults['cookie'];
				}
				$duration 										= (int) $duration;
				// Get Current Date + Time
				$time_current 									= $this->TS_VCSC_DownTime_GetCurrentTimestamp(true, 'm/d/Y H:i:s', 0);
				if ($duration > 0) {
					$time_expires 								= $this->TS_VCSC_DownTime_GetCurrentTimestamp(true, 'm/d/Y H:i:s', $duration);
				} else {
					$time_expires								= 0;
				}
				$expiration										= ($duration > 0 ? time() + $duration * 60 : 0);
				setcookie('TS_VCSC_DownTime_Cookie', $time_expires, $expiration);
			}
			function TS_VCSC_DownTime_CookieValid() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				if (!isset($_COOKIE['TS_VCSC_DownTime_Cookie'])) {
					return false;
				}				
				// Get Current Date + Time
				$time_current 									= $this->TS_VCSC_DownTime_GetCurrentTimestamp(true, 'm/d/Y g:i:s A', 0);
				// Get Cookie Expiration Time
				$time_expires 									= $_COOKIE['TS_VCSC_DownTime_Cookie'];
				// Compare Time + Determine Validity
				if (($time_expires > $time_current) || ($time_expires == 0)) {
					return true;
				} else {
					return false;
				}
			}
			function TS_VCSC_DownTime_CookieArray($var_array) {
				$out 											= '';
				if (is_array($var_array)) {
					foreach ($var_array as $index => $data) {
						$out .= ($data!="") ? $index . "=" . $data . "|" : "";
					}
				}
				return rtrim($out,"|");
			}			
			function TS_VCSC_DownTime_CookieBreak($cookie_string) {
				$array											= explode("|", $cookie_string);
				foreach ($array as $i=>$stuff) {
					$stuff										= explode("=", $stuff);
					$array[$stuff[0]] = $stuff[1];
					unset($array[$i]);
				}
				return $array;
			}
						
			// Template for Frontend Rendering Of Downpage
			function TS_VCSC_DownTime_RenderTemplate() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				if ($this->Target_Maintenance == "true") {
					$maintenance_file 								= WP_CONTENT_DIR . '/maintenance.php';
					require_once($maintenance_file);
				} else {
					require_once($VISUAL_COMPOSER_EXTENSIONS->templates_dir . 'ts_downpage_template.php');
				}
				exit();
			}
			function TS_VCSC_DownTime_RenderHTTPCode() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				$code 											= (int) $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Downtime_Manager_Settings['downstatus'];				
				if (($code != '') && ($code != false) && ($code != "false")) {
					status_header($code);
				}
			}		
			function TS_VCSC_DownTime_ParseRequest($wp) {
				global $VISUAL_COMPOSER_EXTENSIONS;	
				if ((is_admin()) || ($this->Target_Page == '')) {
					return;
				}
				$post 							= get_post($this->Target_Page);		
				$wp->query_vars['post_type'] 	= 'ts_downtime';
				$wp->query_vars['page_id'] 		= $post->ID;
				$wp->query_vars['pagename'] 	= $post->post_name;		
			}
			
			// Get Current Time + Date with Offset
			function TS_VCSC_DownTime_GetCurrentTimestamp($current_stamp = false, $current_format = 'H:i:s', $current_addto = 0) {
				$current_local 					= current_time('timestamp', 1);
				$current_local					= $current_local + $this->Timer_Timezone * 3600 + $current_addto * 60;
				$current_moment					= $this->TS_VCSC_DownTime_CreateTimeValueArray($current_local);
				$current_day					= $current_moment['mday'];
				$current_month					= $current_moment['mon'];
				$current_year					= $current_moment['year'];
				$current_weekday				= $current_moment['weekday'];
				$current_seconds				= $current_moment['seconds'];
				$current_minutes				= $current_moment['minutes'];
				$current_hours					= $current_moment['hours'];
				$current_unix					= $current_moment['0'];
				$current_date					= '';
				$current_time					= date($current_format, mktime($current_hours, $current_minutes, $current_seconds));
				if ($current_stamp) {
					return $current_local;
				} else {
					return $current_time;
				}
			}
			
			// Create Array with Date/Time Values
			function TS_VCSC_DownTime_CreateTimeValueArray($ts = null){ 
				$k = array('seconds', 'minutes', 'hours', 'mday', 'wday', 'mon', 'year', 'yday', 'weekday', 'month', 0);
				return(array_combine($k, explode(":", gmdate('s:i:G:j:w:n:Y:z:l:F:U', is_null($ts) ? time() : $ts)))); 
			}
			
			// Check Current Time Against Time Range
			function TS_VCSC_Downtime_CheckAgainstCurrent($timeonly, $scope = null, $period, $current){
				$render						= "false";
				if ($scope == "full") {
					$render					= "true";
				} else if ($scope == "none") {
					$render					= "false";
				} else if (($scope == "period") || (($scope == null) && ($timeonly == true))) {
					$period 				= explode(",", $period);
					$start					= $period[0] * 5;
					$stop					= $period[1] * 5;
					$start					= date('H:i:s', mktime(0, $start, 0));
					$stop					= date('H:i:s', mktime(0, $stop, 0));
					if (($current >= $start) && ($current <= $stop)) {
						$render				= "true";
					}
				}
				return $render;
			}
			
			// Check if Downtime Settings Trigger Downpage
			function TS_VCSC_DownTime_CheckForDownStatus($timezone, $dependency) {
				global $VISUAL_COMPOSER_EXTENSIONS;
				$render_content			= "false";
				// Get Current Date/Time Values
				$current_local 			= current_time('timestamp', 1);
				$current_local			= $current_local + $timezone * 3600;
				$current_moment			= $this->TS_VCSC_DownTime_CreateTimeValueArray($current_local);
				$current_day			= $current_moment['mday'];
				$current_month			= $current_moment['mon'];
				$current_year			= $current_moment['year'];
				$current_weekday		= $current_moment['weekday'];
				$current_seconds		= $current_moment['seconds'];
				$current_minutes		= $current_moment['minutes'];
				$current_hours			= $current_moment['hours'];
				$current_unix			= $current_moment['0'];
				$current_date			= '';
				$current_time			= date('H:i:s', mktime($current_hours, $current_minutes, $current_seconds));
				// Check for Dependency
				if (($dependency == 'datetime') || ($dependency == 'dateonly')) {
					if ($current_local <= strtotime($this->Timer_Target)) {
						$render_content	= "true";
					}
				} else if ($dependency == 'timerange') {
					$render_content		= $this->TS_VCSC_Downtime_CheckAgainstCurrent(true, null, $this->Timer_Target, $current_time);
				}
				return $render_content;
			}
			// Check for Custom maintenance.php File
			function TS_VCSC_DownTime_CheckMaintenancePHP() {
				$maintenance_file 			= WP_CONTENT_DIR . "/maintenance.php";
				if (file_exists($maintenance_file)) {
					return "true";
				} else {
					return "false";
				}
			}
		}
	}
	if (class_exists('TS_VCSC_Downtime_Mode')) {
		$TS_VCSC_Downtime_Mode = new TS_VCSC_Downtime_Mode;
	}
?>