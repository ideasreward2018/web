<?php
    global $VISUAL_COMPOSER_EXTENSIONS;
	
	if (!class_exists('TS_VCSC_Animated_Progressbar')) {
		class TS_VCSC_Animated_Progressbar {
			/* ----------------------- */
			/* Define Global Variables */
			/* ----------------------- */
			public $TS_VCSC_ProgressbarIncrement;
			public $TS_VCSC_ProgressbarAddWidth;
			public $TS_VCSC_ProgressbarTextString;
			
			/* --------------- */
			/* Construct Class */
			/* --------------- */
            function __construct() {}

			/* ------------------------------------- */
			/* Update/Replace Progressbar Textstring */
			/* ------------------------------------- */
			function TS_VCSC_ProgressbarNewText($string){
				$this->TS_VCSC_ProgressbarTextString 	= $string;
			}
			
			/* ----------------------- */
			/* Add Progressbar to Page */
			/* ----------------------- */
			function TS_VCSC_ProgressbarCreate(){
				echo '<div class="ts-settings-progressbar-container">';
					echo '<div class="ts-settings-progressbar-wrapper clearfix">';
						echo '<div class="ts-settings-progressbar-name">' . $this->TS_VCSC_ProgressbarTextString . '</div>';
						echo '<div class="ts-settings-progressbar-bar">';
							echo '<div class="ts-settings-progressbar-value striped animated" style="width: 0%;">';
								echo '<span class="ts-settings-progressbar-tooltip">0%</span>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}

			/* ------------------------ */
			/* Calculate Next Increment */
			/* ------------------------ */
			function TS_VCSC_ProgressbarCalculate($count){
				$this->TS_VCSC_ProgressbarIncrement 	= 100 / $count;
			}
		
			/* ---------------------------------- */
			/* Animate Progressbar with Increment */
			/* ---------------------------------- */
			function TS_VCSC_ProgressbarAnimate(){
				$this->TS_VCSC_ProgressbarAddWidth 		+= $this->TS_VCSC_ProgressbarIncrement;		
				echo '<script>
					jQuery(".ts-settings-progressbar-container .ts-settings-progressbar-name").html("' . $this->TS_VCSC_ProgressbarTextString . '");
					jQuery(".ts-settings-progressbar-container .ts-settings-progressbar-value").stop().animate({width: "' . $this->TS_VCSC_ProgressbarAddWidth . '%"}, "fast");
					jQuery(".ts-settings-progressbar-container .ts-settings-progressbar-tooltip").html("' . round($this->TS_VCSC_ProgressbarAddWidth, 2) . '%");
				</script>';  
			}
		
			/* -------------------------------------- */
			/* Hide Progressbar + Preloader Animation */
			/* -------------------------------------- */
			function TS_VCSC_ProgressbarHide(){
				echo '<script>
					setTimeout(function(){
						jQuery(".ts-preloader-animation-main").fadeOut(500);
						jQuery(".ts-settings-statistics-compile-message").fadeOut(500);						
						jQuery(".ts-settings-progressbar-container").fadeOut(500);
					}, 1000);
				</script>';				
			}
		}
	}
?>