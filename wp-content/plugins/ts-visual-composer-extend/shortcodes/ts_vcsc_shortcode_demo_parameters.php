<?php
	add_shortcode('TS_VCSC_Parameter_Showcase', 'TS_VCSC_Parameter_Showcase');
	function TS_VCSC_Parameter_Showcase ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();
		
		extract(shortcode_atts(array(
			'demo_parameter'			=> 'switch_button',
		), $atts));
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>