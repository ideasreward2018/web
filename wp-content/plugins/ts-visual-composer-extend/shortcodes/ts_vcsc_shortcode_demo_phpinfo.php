<?php
	add_shortcode('TS_VCSC_PHPInfo_Summary', 'TS_VCSC_PHPInfo_Summary');
	function TS_VCSC_PHPInfo_Summary ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();
		
		echo '<div class="ts-vcsc-phpinfo-information-wrap" style="width: 100%;">';	
			phpinfo();
		echo '</div>';
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>