<?php
	add_shortcode('TS_VCSC_Preloaders', 'TS_VCSC_Preloaders');
	function TS_VCSC_Preloaders ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();
		
		extract(shortcode_atts(array(
			'preloader' 						=> 0,
		), $atts));
		
		if ($preloader > -1) {
			echo '<div class="ts-vcsc-preloader-preview-wrap" style="position: relative; display: block; width: 100%; height: 128px; margin: 0; padding: 0;">';	
				echo TS_VCSC_CreatePreloaderCSS("ts-vcsc-preloader-preview-" . mt_rand(999999, 9999999), "", $preloader, "true");
			echo '</div>';
		}
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>