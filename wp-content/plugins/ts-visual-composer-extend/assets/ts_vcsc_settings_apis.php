<?php
	global $VISUAL_COMPOSER_EXTENSIONS;
?>
<div id="ts-settings-apis" class="tab-content">
	<div class="ts-vcsc-section-main">
		<div class="ts-vcsc-section-title ts-vcsc-section-show"><i class="dashicons-admin-network"></i>External API Information</div>
		<div class="ts-vcsc-section-content">
			<div class="ts-vcsc-notice-field ts-vcsc-success" style="margin-top: 10px; font-size: 13px; text-align: justify;">
				Some external services, such as Google, might require you to provide an API key or other identifying credentials, in order to use those services. You can enter and store those information using the provided inputs below.
			</div>		
			<div>
				<h4>Google Maps API Key:</h4>
				<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					The usage of Google Maps is free for non-commercial users. Since 01/2012, commercial users have a current usage limit of 25.000 free requests a day – with additional usage cost of 0.5$/1000 requests.
					In order to comply with the Google Maps terms of services, commercial users have to register for a free API key. This API key can also be used by non-commercial users in order to monitor their Google Maps
					API usage. You can create your API key in the <a href="https://developers.google.com/" target="_blank">Google Developers Console</a>.
				</div>	
				<label class="Uniform" style="display: inline-block;" for="ts_vcsc_extend_settings_externalAPIGoogleMaps">Google Maps API Key:</label>
				<input class="ts_vcsc_extend_settings_externalAPIGoogleMaps" data-error="API Key - Google Maps" data-order="9" type="text" style="width: 50%;" id="ts_vcsc_extend_settings_externalAPIGoogleMaps" name="ts_vcsc_extend_settings_externalAPIGoogleMaps" value="<?php echo (isset($TS_VCSC_External_API_Settings['GoogleMaps']) ? $TS_VCSC_External_API_Settings['GoogleMaps'] : ""); ?>" size="100">
			</div>
			<div style="margin-top: 20px;">
				<h4>YouTube API Key:</h4>
				<div class="ts-vcsc-notice-field ts-vcsc-warning" style="margin-top: 10px; font-size: 13px; text-align: justify;">
					In order to learn more about the current YouTube quota limits, please check the official <a href="https://developers.google.com/youtube/v3/getting-started#quota" target="_blank">documentation here</a>.
					You can create your API key in the <a href="https://developers.google.com/" target="_blank">Google Developers Console</a>.
				</div>	
				<label class="Uniform" style="display: inline-block;" for="ts_vcsc_extend_settings_externalAPIYouTube">YouTube API Key:</label>
				<input class="ts_vcsc_extend_settings_externalAPIYouTube" data-error="API Key - YouTube" data-order="9" type="text" style="width: 50%;" id="ts_vcsc_extend_settings_externalAPIYouTube" name="ts_vcsc_extend_settings_externalAPIYouTube" value="<?php echo (isset($TS_VCSC_External_API_Settings['YouTube']) ? $TS_VCSC_External_API_Settings['YouTube'] : ""); ?>" size="100">
			</div>	
		</div>
	</div>
</div>