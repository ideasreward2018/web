<?php
	if (!class_exists('TS_Loan_Calculator')){
		class TS_Loan_Calculator {
			private $TS_VCSC_Loan_Calculator_Language;
			
			function __construct() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						$this->TS_VCSC_Loan_Calculator_Lean();
					} else if (function_exists('vc_map')) {
						add_action('init',                                  array($this, 'TS_VCSC_Loan_Calculator_Elements'), 9999999);
					}
				} else {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
						add_action('admin_init',							array($this, 'TS_VCSC_Loan_Calculator_Lean'), 9999999);
					} else if (function_exists('vc_map')) {
						add_action('admin_init',							array($this, 'TS_VCSC_Loan_Calculator_Elements'), 9999999);
					}
				}
				if ((is_admin() == false) || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAJAX == "true") || ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_PluginAlways == "true")) {
					add_shortcode('TS_VCSC_Loan_Calculator',       			array($this, 'TS_VCSC_Loan_Calculator_Function'));
				}
				$this->TS_VCSC_Loan_Calculator_Language						= get_option('ts_vcsc_extend_settings_translationsLoanCalculator',	$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults);
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults as $key => $value) {
					if (!isset($this->TS_VCSC_Loan_Calculator_Language[$key])) {
						$this->TS_VCSC_Loan_Calculator_Language[$key]		= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults[$key];
					}
				}
			}
			
			// Register Element(s) via LeanMap
			function TS_VCSC_Loan_Calculator_Lean() {
				vc_lean_map('TS_VCSC_Loan_Calculator', 						array($this, 'TS_VCSC_Loan_Calculator_Elements'), null);
			}
			
			// Loan Calculator
			function TS_VCSC_Loan_Calculator_Function ($atts, $content = null){
				global $VISUAL_COMPOSER_EXTENSIONS;
				ob_start();
			
				extract( shortcode_atts( array(
					// Default Settings			
					'settings_theme'				=> 'turquoise',
					'settings_scrollable'			=> 'false',
					'settings_scrollheight'			=> 700,
					'settings_scrollpretty'			=> 'true',
					'settings_currency'				=> 'USD',
					'settings_placement'			=> 'left',
					'settings_spacer'				=> 'false',
					'settings_locale'				=> '',
					'settings_title'				=> $this->TS_VCSC_Loan_Calculator_Language['settings_title'],
					'settings_results'				=> $this->TS_VCSC_Loan_Calculator_Language['settings_results'],
					'settings_thousand'				=> 'K',
					'settings_million'				=> 'M',
					// Baseline Settings			
					'baseline_default'				=> 'payment', // payment, years, principal
					'baseline_visible'				=> 'false',
					'baseline_message'				=> $this->TS_VCSC_Loan_Calculator_Language['baseline_message'],
					'baseline_payment'				=> $this->TS_VCSC_Loan_Calculator_Language['baseline_payment'],
					'baseline_term'					=> $this->TS_VCSC_Loan_Calculator_Language['baseline_term'],
					'baseline_principal'			=> $this->TS_VCSC_Loan_Calculator_Language['baseline_principal'],
					// Principal Settings			
					'principal_default'				=> 100000,
					'principal_minimum'				=> 0,
					'principal_maximum'				=> 1000000,
					'principal_step'				=> 100,
					'principal_slider'				=> 'true',
					'principal_scales'				=> '0|100K|200K|300K|400K|500k|600K|700K|800K|900K|1M',
					'principal_label'				=> $this->TS_VCSC_Loan_Calculator_Language['principal_label'],
					'principal_error'				=> $this->TS_VCSC_Loan_Calculator_Language['principal_error'],
					// Interest Settings			
					'interest_default'				=> 5,
					'interest_minimum'				=> 0.01,
					'interest_maximum'				=> 25,
					'interest_step'					=> 0.01,
					'interest_slider'				=> 'true',
					'interest_scales'				=> '0%|5%|10%|15%|20%|25%',
					'interest_label'				=> $this->TS_VCSC_Loan_Calculator_Language['interest_label'],
					'interest_error'				=> $this->TS_VCSC_Loan_Calculator_Language['interest_error'],
					// Amortization Settings
					'years_default'					=> 30,
					'years_minimum'					=> 1,
					'years_maximum'					=> 30,
					'years_step'					=> 1,
					'years_slider'					=> 'true',
					'years_scales'					=> '0|5|10|15|20|25|30',
					'years_label'					=> $this->TS_VCSC_Loan_Calculator_Language['years_label'],
					'years_error'					=> $this->TS_VCSC_Loan_Calculator_Language['years_error'],
					// Payment Settings
					'payment_default'				=> 600,
					'payment_minimum'				=> 0,
					'payment_maximum'				=> 2500,
					'payment_step'					=> 10,
					'payment_slider'				=> 'true',
					'payment_scales'				=> '0|0.5K|1.0K|1.5K|2.0K|2.5K',
					'payment_label'					=> $this->TS_VCSC_Loan_Calculator_Language['payment_label'],
					'payment_error'					=> $this->TS_VCSC_Loan_Calculator_Language['payment_error'],
					// Fixed Term Settings
					'fixed_default'					=> '-1',
					'fixed_visible'					=> 'false',
					'fixed_label'					=> $this->TS_VCSC_Loan_Calculator_Language['fixed_label'],
					'fixed_match'					=> $this->TS_VCSC_Loan_Calculator_Language['fixed_match'],
					// Frequency Settings
					'frequency_default'				=> '12',
					'frequency_visible'				=> 'false',
					'frequency_label'				=> $this->TS_VCSC_Loan_Calculator_Language['frequency_label'],
					// Compounding Method
					'compounding_default'			=> '-1',
					'compounding_visible'			=> 'false',
					'compounding_label'				=> $this->TS_VCSC_Loan_Calculator_Language['compounding_label'],
					'compounding_simple'			=> $this->TS_VCSC_Loan_Calculator_Language['compounding_simple'],
					'compounding_monthly'			=> $this->TS_VCSC_Loan_Calculator_Language['compounding_monthly'],
					'compounding_quarter'			=> $this->TS_VCSC_Loan_Calculator_Language['compounding_quarter'],
					'compounding_semi'				=> $this->TS_VCSC_Loan_Calculator_Language['compounding_semi'],
					'compounding_annual'			=> $this->TS_VCSC_Loan_Calculator_Language['compounding_annual'],
					// Origination Settings
					'origination_default'			=> 'nextfirst', // nextfirst, current
					'origination_visible'			=> 'false',
					'origination_control'			=> 'custom', // custom, html5, none
					'origination_label'				=> $this->TS_VCSC_Loan_Calculator_Language['origination_label'],
					'origination_error'				=> $this->TS_VCSC_Loan_Calculator_Language['origination_error'],
					// Disclaimer Settings
					'disclaimer_show'				=> 'true',
					'disclaimer_message'			=> base64_encode($this->TS_VCSC_Loan_Calculator_Language['disclaimer_message']),
					// Button Settings
					'button_calculate'				=> $this->TS_VCSC_Loan_Calculator_Language['button_calculate'],
					'button_reset'					=> $this->TS_VCSC_Loan_Calculator_Language['button_reset'],
					'button_chartshow'				=> $this->TS_VCSC_Loan_Calculator_Language['button_chartshow'],
					'button_charthide'				=> $this->TS_VCSC_Loan_Calculator_Language['button_charthide'],
					'button_chartsave'				=> $this->TS_VCSC_Loan_Calculator_Language['button_chartsave'],
					'button_scheduleshow'			=> $this->TS_VCSC_Loan_Calculator_Language['button_scheduleshow'],
					'button_schedulehide'			=> $this->TS_VCSC_Loan_Calculator_Language['button_schedulehide'],
					'button_printable'				=> $this->TS_VCSC_Loan_Calculator_Language['button_printable'],
					// Chart Settings
					'chart_usage'					=> 'false',
					'chart_title'					=> $this->TS_VCSC_Loan_Calculator_Language['chart_title'],
					'chart_interest'				=> $this->TS_VCSC_Loan_Calculator_Language['chart_interest'],
					'chart_principal'				=> $this->TS_VCSC_Loan_Calculator_Language['chart_principal'],
					'chart_combined'				=> $this->TS_VCSC_Loan_Calculator_Language['chart_combined'],
					'chart_balance'					=> $this->TS_VCSC_Loan_Calculator_Language['chart_balance'],
					'chart_tooltips'				=> 'point', // nearest, point, index, none
					// Schedule Settings
					'schedule_usage'				=> 'false',			
					'schedule_scope'				=> 'swipe',
					'schedule_persist'				=> '1',
					'schedule_sort'					=> 'true',
					'schedule_sortswitch'			=> 'false',
					'schedule_initial'				=> 1,
					'schedule_order'				=> 'ascending',
					'schedule_noorder'				=> '',
					'schedule_modeswitch'			=> 'false',
					'schedule_modeexclude'			=> '',
					'schedule_minimap'				=> 'true',
					'schedule_text_reponsive'		=> $this->TS_VCSC_Loan_Calculator_Language['schedule_reponsive'],
					'schedule_text_stack'			=> $this->TS_VCSC_Loan_Calculator_Language['schedule_stack'],
					'schedule_text_swipe'			=> $this->TS_VCSC_Loan_Calculator_Language['schedule_swipe'],
					'schedule_text_toggle'			=> $this->TS_VCSC_Loan_Calculator_Language['schedule_toggle'],
					'schedule_text_columns'			=> $this->TS_VCSC_Loan_Calculator_Language['schedule_columns'],
					'schedule_text_error'			=> $this->TS_VCSC_Loan_Calculator_Language['schedule_error'],
					'schedule_text_sort'			=> $this->TS_VCSC_Loan_Calculator_Language['schedule_sort'],
					// Printable Schedule Settings
					'printable_usage'				=> 'false',
					'printable_image'				=> '',
					'printable_disclaimer'			=> 'true',
					'printable_summary'				=> 'true',
					'printable_chart'				=> 'true',
					'printable_table'				=> 'true',
					'printable_annual'				=> 'true',
					'printable_title'				=> $this->TS_VCSC_Loan_Calculator_Language['printable_title'],
					'printable_button'				=> $this->TS_VCSC_Loan_Calculator_Language['printable_button'],
					// Global Text Strings
					'string_single_year'			=> $this->TS_VCSC_Loan_Calculator_Language['single_year'],
					'string_single_month'			=> $this->TS_VCSC_Loan_Calculator_Language['single_month'],
					'string_single_day'				=> $this->TS_VCSC_Loan_Calculator_Language['single_day'],
					'string_plural_year'			=> $this->TS_VCSC_Loan_Calculator_Language['plural_year'],
					'string_plural_month'			=> $this->TS_VCSC_Loan_Calculator_Language['plural_month'],
					'string_plural_day'				=> $this->TS_VCSC_Loan_Calculator_Language['plural_day'],
					'string_not_available'			=> $this->TS_VCSC_Loan_Calculator_Language['not_available'],
					'string_period_weekly'			=> $this->TS_VCSC_Loan_Calculator_Language['period_weekly'],
					'string_period_biweekly'		=> $this->TS_VCSC_Loan_Calculator_Language['period_biweekly'],
					'string_period_semimonthly'		=> $this->TS_VCSC_Loan_Calculator_Language['period_semimonthly'],
					'string_period_monthly'			=> $this->TS_VCSC_Loan_Calculator_Language['period_monthly'],
					'string_period_quarterly'		=> $this->TS_VCSC_Loan_Calculator_Language['period_quarterly'],
					'string_period_semiannually'	=> $this->TS_VCSC_Loan_Calculator_Language['period_semiannually'],
					'string_period_annually'		=> $this->TS_VCSC_Loan_Calculator_Language['period_annually'],
					'string_payment_date'			=> $this->TS_VCSC_Loan_Calculator_Language['payment_date'],
					'string_payment_interest'		=> $this->TS_VCSC_Loan_Calculator_Language['payment_interest'],
					'string_payment_principal'		=> $this->TS_VCSC_Loan_Calculator_Language['payment_principal'],
					'string_payment_balance'		=> $this->TS_VCSC_Loan_Calculator_Language['payment_balance'],
					'string_accumulated_principal'	=> $this->TS_VCSC_Loan_Calculator_Language['accumulated_principal'],
					'string_accumulated_interest'	=> $this->TS_VCSC_Loan_Calculator_Language['accumulated_interest'],
					'string_accumulated_total'		=> $this->TS_VCSC_Loan_Calculator_Language['accumulated_total'],
					'string_summary_loanamount'		=> $this->TS_VCSC_Loan_Calculator_Language['summary_loanamount'],
					'string_summary_loanbalance'	=> $this->TS_VCSC_Loan_Calculator_Language['summary_loanbalance'],
					'string_summary_datepayout'		=> $this->TS_VCSC_Loan_Calculator_Language['summary_datepayout'],
					'string_summary_datefirst'		=> $this->TS_VCSC_Loan_Calculator_Language['summary_datefirst'],
					'string_summary_datelast'		=> $this->TS_VCSC_Loan_Calculator_Language['summary_datelast'],
					'string_summary_paymentfirst'	=> $this->TS_VCSC_Loan_Calculator_Language['summary_paymentfirst'],
					'string_summary_paymentlast'	=> $this->TS_VCSC_Loan_Calculator_Language['summary_paymentlast'],
					'string_summary_amortization'	=> $this->TS_VCSC_Loan_Calculator_Language['summary_amortization'],
					'string_summary_interest'		=> $this->TS_VCSC_Loan_Calculator_Language['summary_interest'],
					'string_summary_frequency'		=> $this->TS_VCSC_Loan_Calculator_Language['summary_frequency'],
					'string_summary_compounding'	=> $this->TS_VCSC_Loan_Calculator_Language['summary_compounding'],
					'string_summary_fixedterm'		=> $this->TS_VCSC_Loan_Calculator_Language['summary_fixedterm'],
					'string_summary_fixedpayments'	=> $this->TS_VCSC_Loan_Calculator_Language['summary_fixedpayments'],
					'string_summary_totalpayments'	=> $this->TS_VCSC_Loan_Calculator_Language['summary_totalpayments'],
					'string_summary_totalinterest'	=> $this->TS_VCSC_Loan_Calculator_Language['summary_totalinterest'],
					'string_summary_totalprincipal'	=> $this->TS_VCSC_Loan_Calculator_Language['summary_totalprincipal'],
					'string_notice_startdate'		=> $this->TS_VCSC_Loan_Calculator_Language['notice_startdate'],
					'string_notice_semimonthly'		=> $this->TS_VCSC_Loan_Calculator_Language['notice_semimonthly'],
					'string_notice_insufficient'	=> $this->TS_VCSC_Loan_Calculator_Language['notice_insufficient'],
					// Other Settings
					'margin_top'					=> 0,
					'margin_bottom'					=> 0,
					'el_id' 						=> '',
					'el_class' 						=> '',
					'css'							=> '',
				), $atts ));
				
				// Load Required Files
				wp_enqueue_style('dashicons');
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
					wp_enqueue_style('ts-extend-loancalculator');					
				} else {
					if (($principal_slider == "true") || ($principal_slider == "true") || ($years_slider == "true") || ($payment_slider == "true")) {
						wp_enqueue_style('ts-extend-jrange');
						wp_enqueue_script('ts-extend-jrange');
					}
					if (($settings_scrollable == "true") && ($settings_scrollpretty == "true")) {
						wp_enqueue_style('ts-extend-perfectscrollbar');
						wp_enqueue_script('ts-extend-perfectscrollbar');
					}
					if (($origination_visible == "true") && ($origination_control == "custom")) {
						wp_enqueue_script('ts-extend-picker');
					}
					if ($chart_usage == "true") {
						wp_enqueue_script('ts-extend-chartjs');
					}
					if ($schedule_usage == "true") {
						wp_enqueue_style('ts-extend-tablesaw');
						wp_enqueue_script('ts-extend-tablesaw');
					}
					wp_enqueue_style('ts-extend-loancalculator');
					wp_enqueue_script('ts-extend-loancalculator');
				}
				
				$randomizer							= mt_rand(999999, 9999999);
				$output 							= '';
			
				// Set Loan Calculator ID
				if (!empty($el_id)) {
					$calculator_id					= $el_id;
				} else {
					$calculator_id					= 'ts-vcsc-loan-calculator-' . $randomizer;
				}
				
				// Contingence Checks
				if ($origination_visible == "false") {
					$origination_control			= "none";
				}
				
				// Origination Date Calculations
				if ($origination_default == "nextfirst") {
					$origination_default			= strtotime('first day of next month');
				} else if ($origination_default == "current") {
					$origination_default			= strtotime('now');
				}
				$origination_year					= date('Y', $origination_default);
				$origination_month					= date('n', $origination_default);
				$origination_day					= date('j', $origination_default);
				$origination_full					= date('Y-m-d', $origination_default);
				
				// Scrollable Settings
				if ($settings_scrollable == "true") {
					$scrollable_class				= 'ts-loan-calculator-main-scrollable';
					$scrollable_style				= 'max-height: ' . $settings_scrollheight . 'px;';
				} else {
					$scrollable_class				= '';
					$scrollable_style				= '';
				}
				
				// Margin Settings
				$settings_margin					= 'margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;';
				
				// Logo Image
				if (($printable_usage == 'true') && ($printable_image != '')) {
					$printable_image					= wp_get_attachment_image_src($printable_image, 'full');
					if (isset($printable_image[0])) {
						$printable_image				= $printable_image[0];
					} else {
						$printable_image				= '';
					}
				}
				
				// Settings Compilation
				$settings_global					= 'data-global-randomizer="' . $randomizer . '" data-global-currency="' . $settings_currency . '" data-global-locale="' . $settings_locale . '" data-global-thousand="' . $settings_thousand . '" data-global-million="' . $settings_million . '"';
				$settings_scrollable				= 'data-scrollable-usage="' . $settings_scrollable . '" data-scrollable-height="' . $settings_scrollheight . '" data-scrollable-pretty="' . $settings_scrollpretty . '"';
				$settings_chart						= 'data-chart-usage="' . $chart_usage . '" data-chart-tooltips="' . $chart_tooltips . '" data-chart-interest="' . $chart_interest . '" data-chart-principal="' . $chart_principal . '" data-chart-combined="' . $chart_combined . '" data-chart-balance="' . $chart_balance . '" data-chart-title="' . $chart_title . '"';
				$settings_printable					= 'data-printable-usage="' . $printable_usage . '" data-printable-title="' . $printable_title . '" data-printable-button="' . $printable_button . '" data-printable-image="' . $printable_image . '" data-printable-summary="' . $printable_summary . '" data-printable-chart="' . $printable_chart . '" data-printable-table="' . $printable_table . '" data-printable-annual="' . $printable_annual . '" data-printable-disclaimer="' . $printable_disclaimer . '"';
				$settings_other						= 'data-origination-use="' . $origination_visible . '" data-frequency-use="' . $frequency_visible . '" data-fixed-use="' . $fixed_visible . '" data-compounding-use="' . $compounding_visible . '"';
				$settings_tablesaw					= 'data-tablesaw-use="' . $schedule_usage . '" data-tablesaw-mode="' . $schedule_scope . '" data-tablesaw-minimap data-tablesaw-sortable data-tablesaw-initial="' . $schedule_initial . '" data-tablesaw-order="' . $schedule_order . '"';
				$settings_tablesaw					.= ' data-tablesaw-mode-exclude="' . $schedule_modeexclude . '" data-tablesaw-string-modes="' . $schedule_text_swipe . ',' . $schedule_text_toggle . ',' . $schedule_text_stack . '"';
				$settings_tablesaw					.= ' data-tablesaw-string-columns="' . $schedule_text_reponsive . '" data-tablesaw-string-button="'  .$schedule_text_columns . '" data-tablesaw-string-error="' . $schedule_text_error . '" data-tablesaw-string-sort="' . $schedule_text_sort . '"';
				$settings_strings					= 'data-string-year="' . $string_single_year . '" data-string-years="' . $string_plural_year . '" data-string-month="' . $string_single_month . '" data-string-months="' . $string_plural_month . '" data-string-day="' . $string_single_day . '" data-string-days="' . $string_plural_day . '" data-string-unavailable="' . $string_not_available . '"';
				$settings_strings					.= ' data-string-paymentdate="' . $string_payment_date . '" data-string-paymentinterest="' . $string_payment_interest . '" data-string-paymentprincipal="' . $string_payment_principal . '" data-string-paymentbalance="' . $string_payment_balance . '"';
				$settings_strings					.= ' data-string-accumulatedprincipal="' . $string_accumulated_principal . '" data-string-accumulatedinterest="' . $string_accumulated_interest . '" data-string-accumulatedtotal="' . $string_accumulated_total . '"';
				
				// Visual Composer Class Override
				if (function_exists('vc_shortcode_custom_css_class')) {
					$settings_class					= $el_class . ' ' . apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Loan_Calculator', $atts);
				} else {
					$settings_class					= $el_class;
				}
		
				// Create Final Output
				$output .= '<div id="ts-loan-calculator-main-wrapper-' . $randomizer . '" class="ts-loan-calculator-main-wrapper ts-loan-calculator-theme-' . $settings_theme . ' ' . $scrollable_class . ' ' . $settings_class . '" ' . $settings_global . ' ' . $settings_scrollable . ' ' . $settings_chart . ' ' . $settings_other . ' ' . $settings_printable . ' ' . $settings_strings . ' style="' . $settings_margin . ' ' . $scrollable_style . '">';
					$output .= '<div id="ts-loan-calculator-main-data-' . $randomizer . '" class="ts-loan-calculator-main-data">';
						// Calculator Title
						$output .= '<div class="ts-loan-calculator-section-main ts-loan-calculator-section-title ts-loan-calculator-title-variables">' . $settings_title . '</div>';
						// Loan Baseline
						if ($baseline_visible == "true") {
							$output .= '<div class="ts-loan-calculator-section-main ts-loan-calculator-section-message">' . $baseline_message . '</div>';
						}
						$output .= '<div id="ts-loan-calculator-section-baseline-' . $randomizer . '" class="ts-loan-calculator-section-group ts-loan-calculator-section-baseline" data-default="' . $baseline_default . '" style="display: ' . ($baseline_visible == "true" ? "block" : "none") . ';">';
							$output .= '<div class="ts-loan-calculator-baseline-checkbox" data-baseline="payment" data-counter-one="ts-loan-calculator-baseline-years-' . $randomizer . '" data-counter-two="ts-loan-calculator-baseline-principal-' . $randomizer . '">';
								$output .= '<input id="ts-loan-calculator-baseline-payment-' . $randomizer . '" class="ts-loan-calculator-baseline-payment ts-loan-calculator-baseline-checkbox" name="ts-loan-calculator-baseline-payment-' . $randomizer . '" type="checkbox" value="' . ($baseline_default == "payment" ? "1" : "0") . '" ' . checked($baseline_default, "payment", false) . ' ' . disabled($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode, "true", false) . '>';
								$output .= '<label for="ts-loan-calculator-baseline-payment-' . $randomizer . '">' . $baseline_payment . '</label>';
							$output .= '</div>';
							$output .= '<div class="ts-loan-calculator-baseline-checkbox" data-baseline="years" data-counter-one="ts-loan-calculator-baseline-payment-' . $randomizer . '" data-counter-two="ts-loan-calculator-baseline-principal-' . $randomizer . '">';
								$output .= '<input id="ts-loan-calculator-baseline-years-' . $randomizer . '" class="ts-loan-calculator-baseline-years ts-loan-calculator-baseline-checkbox" name="ts-loan-calculator-baseline-years-' . $randomizer . '" type="checkbox" value="' . ($baseline_default == "years" ? "1" : "0") . '" ' . checked($baseline_default, "years", false) . ' ' . disabled($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode, "true", false) . '>';
								$output .= '<label for="ts-loan-calculator-baseline-years-' . $randomizer . '">' . $baseline_term . '</label>';
							$output .= '</div>';
							$output .= '<div class="ts-loan-calculator-baseline-checkbox" data-baseline="principal" data-counter-one="ts-loan-calculator-baseline-payment-' . $randomizer . '" data-counter-two="ts-loan-calculator-baseline-years-' . $randomizer . '">';
								$output .= '<input id="ts-loan-calculator-baseline-principal-' . $randomizer . '" class="ts-loan-calculator-baseline-principal ts-loan-calculator-baseline-checkbox" name="ts-loan-calculator-baseline-principal-' . $randomizer . '" type="checkbox" value="' . ($baseline_default == "principal" ? "1" : "0") . '" ' . checked($baseline_default, "principal", false) . ' ' . disabled($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode, "true", false) . '>';
								$output .= '<label for="ts-loan-calculator-baseline-principal-' . $randomizer . '">' . $baseline_principal . '</label>';
							$output .= '</div>';
						$output .= '</div>';
						// Principal Amount
						$output .= '<div id="ts-loan-calculator-section-principal-' . $randomizer . '" class="ts-loan-calculator-section-group ts-loan-calculator-section-principal ' . ($baseline_default == "principal" ? "ts-loan-calculator-currently-hidden" : "") . '" data-slider="' . $principal_slider . '" data-scales="' . $principal_scales . '" data-default="' . $principal_default . '" style="display: ' . ($baseline_default == "principal" ? "none" : "block") . ';">';
							$output .= '<input id="ts-loan-calculator-input-principal-' . $randomizer . '" class="ts-loan-calculator-input-principal" name="ts-loan-calculator-input-principal-' . $randomizer . '" type="number" value="' . $principal_default . '" min="' . $principal_minimum . '" max="' . $principal_maximum . '" step="' . $principal_step . '">';
							$output .= '<label class="ts-loan-calculator-input-label" for="ts-loan-calculator-input-principal-' . $randomizer . '">' . $principal_label . '</label>';
							$output .= '<div class="ts-loan-calculator-error-message ts-loan-calculator-error-principal" style="display: none;">' . $principal_error . '</div>';
						$output .= '</div>';
						// Interest Rate
						$output .= '<div id="ts-loan-calculator-section-interest-' . $randomizer . '" class="ts-loan-calculator-section-group ts-loan-calculator-section-interest" data-slider="' . $interest_slider . '" data-scales="' . $interest_scales . '" data-default="' . $interest_default . '">';
							$output .= '<input id="ts-loan-calculator-input-interest-' . $randomizer . '" class="ts-loan-calculator-input-interest ts-loan-calculator-input-slider" name="ts-loan-calculator-input-interest-' . $randomizer . '" type="number" name="interest" value="' . $interest_default . '" min="' . $interest_minimum . '" max="' . $interest_maximum . '" step="' . $interest_step . '">';
							$output .= '<label class="ts-loan-calculator-input-label" for="ts-loan-calculator-input-interest-' . $randomizer . '">' . $interest_label . '</label>';
							$output .= '<div class="ts-loan-calculator-error-message ts-loan-calculator-error-interest" style="display: none;">' . $interest_error . '</div>';
						$output .= '</div>';
						// Desired Amortization in Years
						$output .= '<div id="ts-loan-calculator-section-years-' . $randomizer . '" class="ts-loan-calculator-section-group ts-loan-calculator-section-years ' . ($baseline_default == "years" ? "ts-loan-calculator-currently-hidden" : "") . '" data-slider="' . $years_slider . '" data-scales="' . $years_scales . '" data-default="' . $years_default . '" data-label-single="' . $string_single_year . '" data-label-plural="' . $string_plural_year . '" style="display: ' . ($baseline_default == "years" ? "none" : "block") . ';">';           
							$output .= '<input id="ts-loan-calculator-input-years-' . $randomizer . '" class="ts-loan-calculator-input-years" name="ts-loan-calculator-input-years-' . $randomizer . '" type="number" value="' . $years_default . '" min="' . $years_minimum . '" max="' . $years_maximum . '" step="' . $years_step . '">';
							$output .= '<label class="ts-loan-calculator-input-label" for="ts-loan-calculator-input-years-' . $randomizer . '">' . $years_label . '</label>';
							$output .= '<div class="ts-loan-calculator-error-message ts-loan-calculator-error-years" style="display: none;">' . $years_error . '</div>';
						$output .= '</div>';
						// Desired Payment Amount
						$output .= '<div id="ts-loan-calculator-section-payment-' . $randomizer . '" class="ts-loan-calculator-section-group ts-loan-calculator-section-payment ' . ($baseline_default == "payment" ? "ts-loan-calculator-currently-hidden" : "") . '" data-slider="' . $payment_slider . '" data-scales="' . $payment_scales . '" data-default="' . $payment_default . '" style="display: ' . ($baseline_default == "payment" ? "none" : "block") . ';">';            
							$output .= '<input id="ts-loan-calculator-input-payment-' . $randomizer . '" class="ts-loan-calculator-input-payment" name="ts-loan-calculator-input-payment-' . $randomizer . '" type="number" value="' . $payment_default . '" min="' . $payment_minimum . '" max="' . $payment_maximum . '" step="' . $payment_step . '">';
							$output .= '<label class="ts-loan-calculator-input-label" for="ts-loan-calculator-input-payment-' . $randomizer . '">' . $payment_label . '</label>';
							$output .= '<div class="ts-loan-calculator-error-message ts-loan-calculator-error-payment" style="display: none;">' . $payment_error . '</div>';
						$output .= '</div>';
						// Interest Fixed Term
						$output .= '<div id="ts-loan-calculator-section-fixedterm-' . $randomizer . '" class="ts-loan-calculator-section-group ts-loan-calculator-section-fixedterm" data-default="' . $fixed_default . '" style="display: ' . ($fixed_visible == "true" ? "block" : "none") . ';">';          
							$output .= '<select id="ts-loan-calculator-input-fixedterm-' . $randomizer . '" class="ts-loan-calculator-input-fixedterm" name="ts-loan-calculator-input-fixedterm-' . $randomizer . '">';
								$output .= '<option value="-1" ' . selected($fixed_default, "-1", false) . '>' . $fixed_match . '</option>';
								$output .= '<option value="12" ' . selected($fixed_default, "12", false) . '>1 ' . $string_single_year . '</option>';
								$output .= '<option value="24" ' . selected($fixed_default, "24", false) . '>2 ' . $string_plural_year . '</option>';
								$output .= '<option value="36" ' . selected($fixed_default, "36", false) . '>3 ' . $string_plural_year . '</option>';
								$output .= '<option value="48" ' . selected($fixed_default, "48", false) . '>4 ' . $string_plural_year . '</option>';
								$output .= '<option value="60" ' . selected($fixed_default, "60", false) . '>5 ' . $string_plural_year . '</option>';
								$output .= '<option value="84" ' . selected($fixed_default, "84", false) . '>7 ' . $string_plural_year . '</option>';
								$output .= '<option value="120" ' . selected($fixed_default, "120", false) . '>10 ' . $string_plural_year . '</option>';
								$output .= '<option value="180" ' . selected($fixed_default, "180", false) . '>15 ' . $string_plural_year . '</option>';
								$output .= '<option value="240" ' . selected($fixed_default, "240", false) . '>20 ' . $string_plural_year . '</option>';
								$output .= '<option value="300" ' . selected($fixed_default, "300", false) . '>25 ' . $string_plural_year . '</option>';
							$output .= '</select>';
							$output .= '<label class="ts-loan-calculator-input-label" for="ts-loan-calculator-input-fixedterm-' . $randomizer . '">' . $fixed_label . '</label>';
						$output .= '</div>';
						// Payment Frequency
						$output .= '<div id="ts-loan-calculator-section-period-' . $randomizer . '" class="ts-loan-calculator-section-group ts-loan-calculator-section-period" data-default="' . $frequency_default . '" style="display: ' . ($frequency_visible == "true" ? "block" : "none") . ';">';            
							$output .= '<select id="ts-loan-calculator-input-period-' . $randomizer . '" class="ts-loan-calculator-input-period" name="ts-loan-calculator-input-period-' . $randomizer . '">';
								$output .= '<option value="-1" ' . selected($frequency_default, "-1", false) . '>' . $string_period_weekly . '</option>';
								$output .= '<option value="-2" ' . selected($frequency_default, "-2", false) . '>' . $string_period_biweekly . '</option>';
								$output .= '<option value="24" ' . selected($frequency_default, "24", false) . '>' . $string_period_semimonthly . '</option>';
								$output .= '<option value="12" ' . selected($frequency_default, "12", false) . '>' . $string_period_monthly . '</option>';
								$output .= '<option value="4" ' . selected($frequency_default, "4", false) . '>' . $string_period_quarterly . '</option>';
								$output .= '<option value="2" ' . selected($frequency_default, "2", false) . '>' . $string_period_semiannually . '</option>';
								$output .= '<option value="1" ' . selected($frequency_default, "1", false) . '>' . $string_period_annually . '</option>';
							$output .= '</select>';
							$output .= '<label class="ts-loan-calculator-input-label" for="ts-loan-calculator-input-period-' . $randomizer . '">' . $frequency_label . '</label>';
						$output .= '</div>';
						// Compounding Method
						$output .= '<div id="ts-loan-calculator-section-compounding-' . $randomizer . '" class="ts-loan-calculator-section-group ts-loan-calculator-section-compounding" data-default="' . $compounding_default . '" style="display: ' . ($compounding_visible == "true" ? "block" : "none") . ';">';            
							$output .= '<select id="ts-loan-calculator-input-compounding-' . $randomizer . '" class="ts-loan-calculator-input-compounding" name="ts-loan-calculator-input-compounding-' . $randomizer . '">';
								$output .= '<option value="-1" ' . selected($compounding_default, "-1", false) . '>' . $compounding_simple . '</option>';
								$output .= '<option value="12" ' . selected($compounding_default, "12", false) . '>' . $compounding_monthly . '</option>';
								$output .= '<option value="4" ' . selected($compounding_default, "4", false) . '>' . $compounding_quarter . '</option>';
								$output .= '<option value="2" ' . selected($compounding_default, "2", false) . '>' . $compounding_semi . '</option>';
								$output .= '<option value="1" ' . selected($compounding_default, "1", false) . '>' . $compounding_annual . '</option>';
							$output .= '</select>';
							$output .= '<label class="ts-loan-calculator-input-label" for="ts-loan-calculator-input-compounding-' . $randomizer . '">' . $compounding_label . '</label>';
						$output .= '</div>';
						// Origination Date
						$output .= '<div id="ts-loan-calculator-section-startdate-' . $randomizer . '" class="ts-loan-calculator-section-group ts-loan-calculator-section-startdate" data-control="' . $origination_control . '" data-language="en" data-default="' . $origination_full . '" style="display: ' . ($origination_visible == "true" ? "block" : "none") . ';">';
							$output .= '<input id="ts-loan-calculator-input-datepicker-' . $randomizer . '" class="ts-loan-calculator-input-datepicker" name="ts-loan-calculator-input-datepicker-' . $randomizer . '" type="' . ($origination_control == "html5" ? "date" : "text") . '" placeholder="YYYY-MM-DD" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="' . $origination_full . '"/>';
							$output .= '<input id="ts-loan-calculator-input-startyear-' . $randomizer . '" class="ts-loan-calculator-input-startyear" name="ts-loan-calculator-input-startyear-' . $randomizer . '" type="hidden" value="' . $origination_year . '" style="display: none;">';
							$output .= '<input id="ts-loan-calculator-input-startmonth-' . $randomizer . '" class="ts-loan-calculator-input-startmonth" name="ts-loan-calculator-input-startmonth-' . $randomizer . '" type="hidden" value="' . $origination_month . '" style="display: none;">';
							$output .= '<input id="ts-loan-calculator-input-startday-' . $randomizer . '" class="ts-loan-calculator-input-startday" name="ts-loan-calculator-input-startday-' . $randomizer . '" type="hidden" value="' . $origination_day . '" style="display: none;">';
							$output .= '<label class="ts-loan-calculator-input-label" for="ts-loan-calculator-input-datepicker-' . $randomizer . '">' . $origination_label . '</label>';
							$output .= '<div class="ts-loan-calculator-error-message ts-loan-calculator-error-datepicker" style="display: none;">' . $origination_error . '</div>';
						$output .= '</div>';
						// Disclaimer Message
						$output .= '<div id="ts-loan-calculator-section-disclaimer-' . $randomizer . '" class="ts-loan-calculator-section-group ts-loan-calculator-section-disclaimer" style="display: ' . ($disclaimer_show == "true" ? "block" : "none") . '">' . do_shortcode(rawurldecode(base64_decode(strip_tags($disclaimer_message)))) . '</div>';
						// Calculator Buttons
						$output .= '<div id="ts-loan-calculator-section-buttons-' . $randomizer . '" class="ts-loan-calculator-section-group ts-loan-calculator-section-buttons">';							
							if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "false") {
								$output .= '<div id="ts-loan-calculator-input-calculate-' . $randomizer . '" class="ts-loan-calculator-input-button ts-loan-calculator-input-calculate" name="ts-loan-calculator-input-calculate"><span>' . $button_calculate . '</span></div>';
								if ($printable_usage == "true") {
									$output .= '<div id="ts-loan-calculator-input-printable-' . $randomizer . '" class="ts-loan-calculator-input-button ts-loan-calculator-input-printable" name="ts-loan-calculator-input-printable" style="display: none;"><span>' . $button_printable . '</span></div>';
								}
								if ($chart_usage == "true") {
									$output .= '<div id="ts-loan-calculator-input-chartrender-' . $randomizer . '" class="ts-loan-calculator-input-button ts-loan-calculator-input-chartrender" name="ts-loan-calculator-input-chartrender" style="display: none;" data-chart-visible="false" data-chart-show="' . $button_chartshow . '" data-chart-hide="' . $button_charthide . '"><span>' . $button_chartshow . '</span></div>';
									$output .= '<div id="ts-loan-calculator-input-chartsave-' . $randomizer . '" class="ts-loan-calculator-input-button ts-loan-calculator-input-chartsave" name="ts-loan-calculator-input-chartsave" style="display: none;"><span>' . $button_chartsave . '</span></div>';
								}
								if ($schedule_usage == "true") {
									$output .= '<div id="ts-loan-calculator-input-schedule-' . $randomizer . '" class="ts-loan-calculator-input-button ts-loan-calculator-input-schedule" name="ts-loan-calculator-input-schedule" style="display: none;" data-schedule-visible="false" data-schedule-show="' . $button_scheduleshow . '" data-schedule-hide="' . $button_schedulehide . '"><span>' . $button_scheduleshow . '</span></div>';
								}
								$output .= '<div id="ts-loan-calculator-input-reset-' . $randomizer . '" class="ts-loan-calculator-input-button ts-loan-calculator-input-reset" name="ts-loan-calculator-input-reset" style="display: none;"><span>' . $button_reset . '</span></div>';
							} else {
								$output .= '<div id="ts-loan-calculator-input-frontend-' . $randomizer . '" class="ts-loan-calculator-input-button ts-loan-calculator-input-frontend" name="ts-loan-calculator-input-frontend"><span>' . $button_calculate . '</span></div>';
							}
						$output .= '</div>';
						// Validation Messages
						$output .= '<div id="ts-loan-calculator-section-validation-' . $randomizer . '" class="ts-loan-calculator-section-main ts-loan-calculator-section-validation">';				
							$output .= '<div class="ts-loan-calculator-notice-message ts-loan-calculator-notice-normal ts-loan-calculator-notice-startdate ts-loan-calculator-currently-hidden" style="display: none;">' . $string_notice_startdate . '</div>';
							$output .= '<div class="ts-loan-calculator-notice-message ts-loan-calculator-notice-normal ts-loan-calculator-notice-semimonthly ts-loan-calculator-currently-hidden" style="display: none;">' . $string_notice_semimonthly . '</div>';
							$output .= '<div class="ts-loan-calculator-notice-message ts-loan-calculator-notice-critical ts-loan-calculator-notice-insufficient ts-loan-calculator-currently-hidden" style="display: none;">' . $string_notice_insufficient . '</div>';
						$output .= '</div>';
						// Loan Results Section
						$output .= '<div class="ts-loan-calculator-section-main ts-loan-calculator-section-title ts-loan-calculator-title-results" style="display: none;">' . $settings_results . '</div>';
						$output .= '<div id="ts-loan-calculator-section-results-' . $randomizer . '" class="ts-loan-calculator-section-main ts-loan-calculator-section-results" style="display: none;">';
							$output .= '<div class="ts-loan-calculator-results-message"><span>' . $string_summary_loanamount . '</span> <span class="ts-loan-calculator-results-principal">' . $string_not_available . '</span></div>';
							$output .= '<div class="ts-loan-calculator-results-message" style="display: ' . ($origination_visible == "true" ? "block" : "none") . ';"><span>' . $string_summary_datepayout . '</span> <span class="ts-loan-calculator-results-payout">' . $string_not_available . '</span></div>';
							$output .= '<div class="ts-loan-calculator-results-message"><span>' . $string_summary_amortization . '</span> <span class="ts-loan-calculator-results-termsfull">' . $string_not_available . '</span></div>';
							$output .= '<div class="ts-loan-calculator-results-message"><span>' . $string_summary_interest . '</span> <span class="ts-loan-calculator-results-interestrate">' . $string_not_available . '</span></div>';
							$output .= '<div class="ts-loan-calculator-results-message" style="display: ' . ($frequency_visible == "true" ? "block" : "none") . ';"><span>' . $string_summary_frequency . '</span> <span class="ts-loan-calculator-results-frequency">' . $string_not_available . '</span></div>';
							$output .= '<div class="ts-loan-calculator-results-message" style="display: ' . ($compounding_visible == "true" ? "block" : "none") . ';"><span>' . $string_summary_compounding . '</span> <span class="ts-loan-calculator-results-compounding">' . $string_not_available . '</span></div>';
							$output .= '<div class="ts-loan-calculator-results-message" style="display: ' . ($fixed_visible == "true" ? "block" : "none") . ';"><span>' . $string_summary_fixedterm . '</span> <span class="ts-loan-calculator-results-termsfixed">' . $string_not_available . '</span></div>';
							$output .= '<div class="ts-loan-calculator-results-message" style="display: ' . ($fixed_visible == "true" ? "block" : "none") . ';"><span>' . $string_summary_fixedpayments . '</span> <span class="ts-loan-calculator-results-paymentsnumber">' . $string_not_available . '</span></div>';			
							$output .= '<div class="ts-loan-calculator-results-message"><span>' . $string_summary_paymentfirst . '</span> <span class="ts-loan-calculator-results-paymentsfirst">' . $string_not_available . '</span></div>';
							$output .= '<div class="ts-loan-calculator-results-message" style="display: ' . ($origination_visible == "true" ? "block" : "none") . ';"><span>' . $string_summary_datefirst . '</span> <span class="ts-loan-calculator-results-datesfirst">' . $string_not_available . '</span></div>';
							$output .= '<div class="ts-loan-calculator-results-message"><span>' . $string_summary_paymentlast . '</span> <span class="ts-loan-calculator-results-paymentslast">' . $string_not_available . '</span></div>';
							$output .= '<div class="ts-loan-calculator-results-message" style="display: ' . ($origination_visible == "true" ? "block" : "none") . ';"><span>' . $string_summary_datelast . '</span> <span class="ts-loan-calculator-results-dateslast">' . $string_not_available . '</span></div>';
							$output .= '<div class="ts-loan-calculator-results-message"><span>' . $string_summary_loanbalance . '</span> <span class="ts-loan-calculator-results-remaining">' . $string_not_available . '</span></div>';
							$output .= '<div class="ts-loan-calculator-results-message"><span>' . $string_summary_totalpayments . '</span> <span class="ts-loan-calculator-results-paymentstotal">' . $string_not_available . '</span></div>';
							$output .= '<div class="ts-loan-calculator-results-message"><span>' . $string_summary_totalinterest . '</span> <span class="ts-loan-calculator-results-paymentsinterest">' . $string_not_available . '</span></div>';
							$output .= '<div class="ts-loan-calculator-results-message"><span>' . $string_summary_totalprincipal . '</span> <span class="ts-loan-calculator-results-paymentsprincipal">' . $string_not_available . '</span></div>';
						$output .= '</div>';
					$output .= '</div>';
					// ChartJS Section
					$output .= '<div id="ts-loan-calculator-section-chartjs-' . $randomizer . '" class="ts-loan-calculator-section-main ts-loan-calculator-section-chartjs" style="display: none;">';
						$output .= '<canvas id="ts-loan-calculator-chartjs-canvas-' . $randomizer . '" class="ts-loan-calculator-section-main ts-loan-calculator-chartjs-canvas" data-initialized="false"></canvas>';
					$output .= '</div>';
					// Schedule Table Section
					$output .= '<div id="ts-loan-calculator-section-schedule-' . $randomizer . '" class="ts-loan-calculator-section-main ts-loan-calculator-section-schedule" ' . $settings_tablesaw . ' style="display: none;"></div>';
				$output .= '</div>';
				
				echo $output;
				
				$myvariable = ob_get_clean();
				return $myvariable;
			}
		
			// Add Loan Calculator Elements
			function TS_VCSC_Loan_Calculator_Elements() {
				global $VISUAL_COMPOSER_EXTENSIONS;
				// Add Loan Calculator
				$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element = array(
					"name"                      	=> __( "TS Loan Calculator", "ts_visual_composer_extend" ),
					"base"                     	 	=> "TS_VCSC_Loan_Calculator",
					"icon" 	                    	=> "ts-composer-element-icon-loancalculator",
					"category"                  	=> __( "VC Extensions", "ts_visual_composer_extend" ),
					"description"               	=> __("Place an advanced loan calculator", "ts_visual_composer_extend"),
					"admin_enqueue_js"        		=> "",
					"admin_enqueue_css"       		=> "",
					"params"                    	=> array(
						// Shortcode Settings
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_1a",
							"seperator"				=> "General Settings",
						),
						array(
							"type"              	=> "dropdown",
							"heading"           	=> __( "Calculator: Theme", "ts_visual_composer_extend" ),
							"param_name"        	=> "settings_theme",
							"width"             	=> 150,
							"value"             	=> array(
								__( 'Turquoise', "ts_visual_composer_extend" )			=> "turquoise",
								__( 'Blue', "ts_visual_composer_extend" )				=> "blue",
								__( 'Green', "ts_visual_composer_extend" )				=> "green",
								__( 'Red', "ts_visual_composer_extend" )				=> "red",
								__( 'Orange', "ts_visual_composer_extend" )				=> "orange",
								__( 'Purple', "ts_visual_composer_extend" )				=> "purple",
								__( 'Grey', "ts_visual_composer_extend" )				=> "grey",
							),
							"admin_label"       	=> true,
							"description"       	=> __( "Select the overall color theme for the calculator.", "ts_visual_composer_extend" ),
						),						
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Calculator: Title Settings", "ts_visual_composer_extend" ),
							"param_name"        	=> "settings_title",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['settings_title'],
							"description"       	=> __( "Enter the title for the calculator settings section.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Calculator: Title Results", "ts_visual_composer_extend" ),
							"param_name"        	=> "settings_results",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['settings_results'],
							"description"       	=> __( "Enter the title for the calculator results section.", "ts_visual_composer_extend" ),
						),						
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Calculator: Scrollable", "ts_visual_composer_extend" ),
							"param_name"        	=> "settings_scrollable",
							"value"             	=> "false",
							"admin_label"       	=> true,
							"description"       	=> __( "Switch the toggle if you want to limit the calculator height and make it scrollable.", "ts_visual_composer_extend" )
						),
						array(
							"type"              	=> "nouislider",
							"heading"           	=> __( "Calculator: Maximum Height", "ts_visual_composer_extend" ),
							"param_name"        	=> "settings_scrollheight",
							"value"             	=> "700",
							"min"               	=> "300",
							"max"               	=> "1000",
							"step"              	=> "1",
							"unit"              	=> 'px',
							"dependency"        	=> array( 'element' => "settings_scrollable", 'value' => 'true' ),
							"description"       	=> __( "Select the maximum height for the calculator.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Calculator: Custom Scrollbar", "ts_visual_composer_extend" ),
							"param_name"        	=> "settings_scrollpretty",
							"value"             	=> "false",
							"dependency"        	=> array( 'element' => "settings_scrollable", 'value' => 'true' ),
							"description"       	=> __( "Switch the toggle if you want to use a custom scrollbar for the calculator.", "ts_visual_composer_extend" )
						),
						// Baseline Settings
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_1b",
							"seperator"				=> "Baseline Settings",
						),
						array(
							"type"              	=> "dropdown",
							"heading"           	=> __( "Baseline: Unknown", "ts_visual_composer_extend" ),
							"param_name"        	=> "baseline_default",
							"width"             	=> 150,
							"value"             	=> array(
								__( 'Payment Amount', "ts_visual_composer_extend" )		=> "payment",
								__( 'Amortization Term', "ts_visual_composer_extend" )	=> "years",
								__( 'Loan Amount', "ts_visual_composer_extend" )		=> "principal",
							),
							"admin_label"       	=> true,
							"description"       	=> __( "Select the main unknown variable the loan calculator is supposed to determine.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Baseline: Selectors", "ts_visual_composer_extend" ),
							"param_name"        	=> "baseline_visible",
							"value"             	=> "false",
							"description"       	=> __( "Switch the toggle if you want to provide selectors to change the unknown baseline variable.", "ts_visual_composer_extend" )
						),
						array(
							"type"              	=> "textarea",
							"heading"           	=> __( "Baseline: Message", "ts_visual_composer_extend" ),
							"param_name"        	=> "baseline_message",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['baseline_message'],
							"dependency"        	=> array( 'element' => "baseline_visible", 'value' => 'true' ),
							"description"       	=> __( "Enter an instructive message for the baseline control section.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Baseline: Loan Payment", "ts_visual_composer_extend" ),
							"param_name"        	=> "baseline_payment",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['baseline_payment'],
							"dependency"        	=> array( 'element' => "baseline_visible", 'value' => 'true' ),
							"description"       	=> __( "Enter the label text for this baseline option.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Baseline: Amortization", "ts_visual_composer_extend" ),
							"param_name"        	=> "baseline_term",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['baseline_term'],
							"dependency"        	=> array( 'element' => "baseline_visible", 'value' => 'true' ),
							"description"       	=> __( "Enter the label text for this baseline option.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Baseline: Loan Amount", "ts_visual_composer_extend" ),
							"param_name"        	=> "baseline_principal",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['baseline_principal'],
							"dependency"        	=> array( 'element' => "baseline_visible", 'value' => 'true' ),
							"description"       	=> __( "Enter the label text for this baseline option.", "ts_visual_composer_extend" ),
						),
						// Disclaimer Settings
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_1c",
							"seperator"				=> "Disclaimer Settings",
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Disclaimer: Show", "ts_visual_composer_extend" ),
							"param_name"        	=> "disclaimer_show",
							"value"             	=> "true",
							"description"       	=> __( "Switch the toggle if you want to show a disclaimer message for the calculator.", "ts_visual_composer_extend" )
						),
						array(
							"type"              	=> ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_EditorBase64TinyMCE == "true" ? "wysiwyg_base64" : "textarea_raw_html"),
							"heading"           	=> __( "Disclaimer: Message", "ts_visual_composer_extend" ),
							"param_name"        	=> "disclaimer_message",
							"value"             	=> base64_encode($this->TS_VCSC_Loan_Calculator_Language['disclaimer_message']),
							"dependency"        	=> array( 'element' => "disclaimer_show", 'value' => 'true' ),
							"description"       	=> __( "Enter the disclaimer message you want to show for this calculator.", "ts_visual_composer_extend" ),
						),			
						// Button Settings
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_1d",
							"seperator"				=> "Main Buttons",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Text: Calculate Loan", "ts_visual_composer_extend" ),
							"param_name"        	=> "button_calculate",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['button_calculate'],
							"description"       	=> __( "Enter text to be shown for the main calculate button.", "ts_visual_composer_extend" ),
							"edit_field_class"		=> "vc_col-sm-6 vc_column",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Text: Reset Calculator", "ts_visual_composer_extend" ),
							"param_name"        	=> "button_reset",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['button_reset'],
							"description"       	=> __( "Enter text to be shown for the calculator reset button.", "ts_visual_composer_extend" ),
							"edit_field_class"		=> "vc_col-sm-6 vc_column",
						),
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_1e",
							"seperator"				=> "Optional Buttons",
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Button: Chart", "ts_visual_composer_extend" ),
							"param_name"        	=> "chart_usage",
							"value"             	=> "false",
							"admin_label"       	=> true,
							"description"       	=> __( "Switch the toggle if you want to provide a button to generate an interactive chart for the loan.", "ts_visual_composer_extend" )
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Button: Schedule", "ts_visual_composer_extend" ),
							"param_name"        	=> "schedule_usage",
							"value"             	=> "false",
							"admin_label"       	=> true,
							"description"       	=> __( "Switch the toggle if you want to provide a button to generate an annualized schedule for all loan payments.", "ts_visual_composer_extend" )
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Button: Printout", "ts_visual_composer_extend" ),
							"param_name"        	=> "printable_usage",
							"value"             	=> "false",
							"admin_label"       	=> true,
							"description"       	=> __( "Switch the toggle if you want to provide a button to generate a printable version of the calculator results.", "ts_visual_composer_extend" )
						),			
						// Default Loan Amount Settings
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_2a",
							"dependency"        	=> array( 'element' => "baseline_default", 'value' => array('payment', 'years') ),
							"seperator"				=> "Loan Amount",
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "nouislider",
							"heading"           	=> __( "Loan Amount: Default", "ts_visual_composer_extend" ),
							"param_name"        	=> "principal_default",
							"value"             	=> "100000",
							"min"               	=> "1000",
							"max"               	=> "1000000",
							"step"              	=> "100",
							"unit"              	=> '',
							"dependency"        	=> array( 'element' => "baseline_default", 'value' => array('payment', 'years') ),
							"admin_label"       	=> true,
							"description"       	=> __( "Define the default loan amount for the calculator.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Loan Amount: Slider Control", "ts_visual_composer_extend" ),
							"param_name"        	=> "principal_slider",
							"value"             	=> "true",
							"dependency"        	=> array( 'element' => "baseline_default", 'value' => array('payment', 'years') ),
							"description"       	=> __( "Switch the toggle if you want to provide a slider control to quickly change this value.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Loan Amount: Label Text", "ts_visual_composer_extend" ),
							"param_name"        	=> "principal_label",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['principal_label'],
							"dependency"        	=> array( 'element' => "baseline_default", 'value' => array('payment', 'years') ),
							"description"       	=> __( "Enter the text to be shown within the label for this input.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Loan Amount: Error Text", "ts_visual_composer_extend" ),
							"param_name"        	=> "principal_error",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['principal_error'],
							"dependency"        	=> array( 'element' => "baseline_default", 'value' => array('payment', 'years') ),
							"description"       	=> __( "Enter the text to be shown as error message for this value.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						// Default Interest Rate Settings
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_2b",
							"seperator"				=> "Interest Rate",
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "nouislider",
							"heading"           	=> __( "Interest Rate: Default", "ts_visual_composer_extend" ),
							"param_name"        	=> "interest_default",
							"value"             	=> "5",
							"min"               	=> "0.01",
							"max"               	=> "25",
							"step"              	=> "0.01",
							"unit"              	=> '',
							"admin_label"       	=> true,
							"description"       	=> __( "Define the default loan amount for the calculator.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Interest Rate: Slider Control", "ts_visual_composer_extend" ),
							"param_name"        	=> "interest_slider",
							"value"             	=> "true",
							"description"       	=> __( "Switch the toggle if you want to provide a slider control to quickly change this value.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Interest Rate: Label Text", "ts_visual_composer_extend" ),
							"param_name"        	=> "interest_label",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['interest_label'],
							"description"       	=> __( "Enter the text to be shown within the label for this input.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Interest Rate: Error Text", "ts_visual_composer_extend" ),
							"param_name"        	=> "interest_error",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['interest_error'],
							"description"       	=> __( "Enter the text to be shown as error message for this value.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						// Default Amortization Term
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_2c",
							"dependency"        	=> array( 'element' => "baseline_default", 'value' => array('payment', 'principal') ),
							"seperator"				=> "Amortization Term",
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "nouislider",
							"heading"           	=> __( "Amortization Term: Default", "ts_visual_composer_extend" ),
							"param_name"        	=> "years_default",
							"value"             	=> "30",
							"min"               	=> "1",
							"max"               	=> "30",
							"step"              	=> "1",
							"unit"              	=> '',
							"dependency"        	=> array( 'element' => "baseline_default", 'value' => array('payment', 'principal') ),
							"admin_label"       	=> true,
							"description"       	=> __( "Define the default amortization term for the calculator.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Amortization Term: Slider Control", "ts_visual_composer_extend" ),
							"param_name"        	=> "years_slider",
							"value"             	=> "true",
							"dependency"        	=> array( 'element' => "baseline_default", 'value' => array('payment', 'principal') ),
							"description"       	=> __( "Switch the toggle if you want to provide a slider control to quickly change this value.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Amortization Term: Label Text", "ts_visual_composer_extend" ),
							"param_name"        	=> "years_label",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['years_label'],
							"dependency"        	=> array( 'element' => "baseline_default", 'value' => array('payment', 'principal') ),
							"description"       	=> __( "Enter the text to be shown within the label for this input.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Amortization Term: Error Text", "ts_visual_composer_extend" ),
							"param_name"        	=> "years_error",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['years_error'],
							"dependency"        	=> array( 'element' => "baseline_default", 'value' => array('payment', 'principal') ),
							"description"       	=> __( "Enter the text to be shown as error message for this value.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						// Default Payment Amount
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_2d",
							"dependency"        	=> array( 'element' => "baseline_default", 'value' => array('years', 'principal') ),
							"seperator"				=> "Payment Amount",
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "nouislider",
							"heading"           	=> __( "Payment Amount: Default", "ts_visual_composer_extend" ),
							"param_name"        	=> "payment_default",
							"value"             	=> "600",
							"min"               	=> "1",
							"max"               	=> "2500",
							"step"              	=> "10",
							"unit"              	=> '',
							"dependency"        	=> array( 'element' => "baseline_default", 'value' => array('years', 'principal') ),
							"description"       	=> __( "Define the default amortization term for the calculator.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Payment Amount: Slider Control", "ts_visual_composer_extend" ),
							"param_name"        	=> "payment_slider",
							"value"             	=> "true",
							"dependency"        	=> array( 'element' => "baseline_default", 'value' => array('years', 'principal') ),
							"description"       	=> __( "Switch the toggle if you want to provide a slider control to quickly change this value.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Payment Amount: Label Text", "ts_visual_composer_extend" ),
							"param_name"        	=> "payment_label",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['payment_label'],
							"dependency"        	=> array( 'element' => "baseline_default", 'value' => array('years', 'principal') ),
							"description"       	=> __( "Enter the text to be shown within the label for this input.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Payment Amount: Error Text", "ts_visual_composer_extend" ),
							"param_name"        	=> "payment_error",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['payment_error'],
							"dependency"        	=> array( 'element' => "baseline_default", 'value' => array('years', 'principal') ),
							"description"       	=> __( "Enter the text to be shown as error message for this value.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						// Initial Fixed Term Settings
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_2e",
							"seperator"				=> "Fixed Term",
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "dropdown",
							"heading"           	=> __( "Fixed Term: Default", "ts_visual_composer_extend" ),
							"param_name"        	=> "fixed_default",
							"width"             	=> 150,
							"value"             	=> array(
								__( 'Match Amortization Term', "ts_visual_composer_extend" )	=> "-1",
								'1' . __( 'Year', "ts_visual_composer_extend" )					=> "12",
								'2' . __( 'Years', "ts_visual_composer_extend" )				=> "24",
								'3' . __( 'Years', "ts_visual_composer_extend" )				=> "36",
								'4' . __( 'Years', "ts_visual_composer_extend" )				=> "48",
								'5' . __( 'Years', "ts_visual_composer_extend" )				=> "60",
								'7' . __( 'Years', "ts_visual_composer_extend" )				=> "84",
								'10' . __( 'Years', "ts_visual_composer_extend" )				=> "120",
								'15' . __( 'Years', "ts_visual_composer_extend" )				=> "180",
								'20' . __( 'Years', "ts_visual_composer_extend" )				=> "240",
								'25' . __( 'Years', "ts_visual_composer_extend" )				=> "300",
							),
							"description"       	=> __( "Select the interest fixed term all calculations should be based on.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Fixed Term: Provide Option", "ts_visual_composer_extend" ),
							"param_name"        	=> "fixed_visible",
							"value"             	=> "false",
							"description"       	=> __( "Switch the toggle if you want to provide an option to change the fixed interest term.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Fixed Term: Label Text", "ts_visual_composer_extend" ),
							"param_name"        	=> "fixed_label",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['fixed_label'],
							"dependency"        	=> array( 'element' => "fixed_visible", 'value' => 'true' ),
							"description"       	=> __( "Enter the text to be shown within the label for this input.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Fixed Term: Option Text", "ts_visual_composer_extend" ),
							"param_name"        	=> "fixed_match",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['fixed_match'],
							"dependency"        	=> array( 'element' => "fixed_visible", 'value' => 'true' ),
							"description"       	=> __( "Enter the text to be shown within the loan summary if this option applies.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						// Payment Frequency Settings
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_2f",
							"seperator"				=> "Payment Frequency",
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "dropdown",
							"heading"           	=> __( "Payment Frequency: Default", "ts_visual_composer_extend" ),
							"param_name"        	=> "frequency_default",
							"width"             	=> 150,
							"value"             	=> array(
								__( 'Weekly', "ts_visual_composer_extend" )						=> "-1",
								__( 'Bi-Weekly', "ts_visual_composer_extend" )					=> "-2",
								__( 'Bi-Monthly', "ts_visual_composer_extend" )					=> "24",
								__( 'Monthly', "ts_visual_composer_extend" )					=> "12",
								__( 'Quarterly', "ts_visual_composer_extend" )					=> "4",
								__( 'Semi-Annually', "ts_visual_composer_extend" )				=> "2",
								__( 'Annually', "ts_visual_composer_extend" )					=> "1",
							),
							"description"       	=> __( "Select the default payment frequency for the calculator.", "ts_visual_composer_extend" ),				
							"default"				=> "12",
							"standard"				=> "12",
							"std"					=> "12",
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Payment Frequency: Provide Option", "ts_visual_composer_extend" ),
							"param_name"        	=> "frequency_visible",
							"value"             	=> "false",
							"description"       	=> __( "Switch the toggle if you want to provide an option to change the payment frequency.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Payment Frequency: Label Text", "ts_visual_composer_extend" ),
							"param_name"        	=> "frequency_label",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['frequency_label'],
							"dependency"        	=> array( 'element' => "frequency_visible", 'value' => 'true' ),
							"description"       	=> __( "Enter the text to be shown within the label for this input.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						// Interest Compounding Settings
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_2g",
							"seperator"				=> "Interest Compounding",
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "dropdown",
							"heading"           	=> __( "Interest Compounding: Default", "ts_visual_composer_extend" ),
							"param_name"        	=> "compounding_default",
							"width"             	=> 150,
							"value"             	=> array(
								__( 'Simple Interest (No Compounding)', "ts_visual_composer_extend" )	=> "-1",
								__( 'Monthly Compounding', "ts_visual_composer_extend" )				=> "12",
								__( 'Quarterly Compounding', "ts_visual_composer_extend" )				=> "4",
								__( 'Semi-Annual Compounding', "ts_visual_composer_extend" )			=> "2",
								__( 'Annual Compounding', "ts_visual_composer_extend" )					=> "1",
							),
							"description"       	=> __( "Select the default interest compounding method for the calculator.", "ts_visual_composer_extend" ),				
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Interest Compounding: Provide Option", "ts_visual_composer_extend" ),
							"param_name"        	=> "compounding_visible",
							"value"             	=> "false",
							"description"       	=> __( "Switch the toggle if you want to provide an option to change the interest compounding method.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Interest Compounding: Label Text", "ts_visual_composer_extend" ),
							"param_name"        	=> "compounding_label",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['compounding_label'],
							"dependency"        	=> array( 'element' => "compounding_visible", 'value' => 'true' ),
							"description"       	=> __( "Enter the text to be shown within the label for this input.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Interest Compounding: Option Text", "ts_visual_composer_extend" ),
							"param_name"        	=> "compounding_simple",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['compounding_simple'],
							"dependency"        	=> array( 'element' => "compounding_visible", 'value' => 'true' ),
							"description"       	=> __( "Enter the text to be shown within the loan summary if this option applies.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Interest Compounding: Option Text", "ts_visual_composer_extend" ),
							"param_name"        	=> "compounding_monthly",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['compounding_monthly'],
							"dependency"        	=> array( 'element' => "compounding_visible", 'value' => 'true' ),
							"description"       	=> __( "Enter the text to be shown within the loan summary if this option applies.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Interest Compounding: Option Text", "ts_visual_composer_extend" ),
							"param_name"        	=> "compounding_quarter",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['compounding_quarter'],
							"dependency"        	=> array( 'element' => "compounding_visible", 'value' => 'true' ),
							"description"       	=> __( "Enter the text to be shown within the loan summary if this option applies.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Interest Compounding: Option Text", "ts_visual_composer_extend" ),
							"param_name"        	=> "compounding_semi",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['compounding_semi'],
							"dependency"        	=> array( 'element' => "compounding_visible", 'value' => 'true' ),
							"description"       	=> __( "Enter the text to be shown within the loan summary if this option applies.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Interest Compounding: Option Text", "ts_visual_composer_extend" ),
							"param_name"        	=> "compounding_annual",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['compounding_annual'],
							"dependency"        	=> array( 'element' => "compounding_visible", 'value' => 'true' ),
							"description"       	=> __( "Enter the text to be shown within the loan summary if this option applies.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						// Loan Origination Settings
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_2h",
							"seperator"				=> "Loan Origination",
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "dropdown",
							"heading"           	=> __( "Loan Origination: Default", "ts_visual_composer_extend" ),
							"param_name"        	=> "origination_default",
							"width"             	=> 150,
							"value"             	=> array(
								__( 'First Of Next Month', "ts_visual_composer_extend" )				=> "nextfirst",
								__( 'Current Date', "ts_visual_composer_extend" )						=> "current",
							),
							"description"       	=> __( "Select the default origination (payout) date for the loan.", "ts_visual_composer_extend" ),				
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Loan Origination: Provide Option", "ts_visual_composer_extend" ),
							"param_name"        	=> "origination_visible",
							"value"             	=> "false",
							"description"       	=> __( "Switch the toggle if you want to provide an option to change the origination date for the loan.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Loan Origination: Label Text", "ts_visual_composer_extend" ),
							"param_name"        	=> "origination_label",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['origination_label'],
							"dependency"        	=> array( 'element' => "origination_visible", 'value' => 'true' ),
							"description"       	=> __( "Enter the text to be shown within the label for this input.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Loan Origination: Error Text", "ts_visual_composer_extend" ),
							"param_name"        	=> "origination_error",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['origination_error'],
							"dependency"        	=> array( 'element' => "origination_visible", 'value' => 'true' ),
							"description"       	=> __( "Enter the text to be shown as error message for this value.", "ts_visual_composer_extend" ),
							"group"					=> "Defaults",
						),
						// Chart Settings
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_3a",
							"seperator"				=> "Chart Settings",
							"dependency"        	=> array( 'element' => "chart_usage", 'value' => 'true' ),
							"group"					=> "Chart",
						),
						array(
							"type"              	=> "dropdown",
							"heading"           	=> __( "Chart: Tooltip", "ts_visual_composer_extend" ),
							"param_name"        	=> "chart_tooltips",
							"width"             	=> 150,
							"value"             	=> array(
								__( 'Single Point', "ts_visual_composer_extend" )				=> "point",
								__( 'Nearest Points', "ts_visual_composer_extend" )				=> "nearest",
								__( 'All Points', "ts_visual_composer_extend" )					=> "index",
								__( 'None', "ts_visual_composer_extend" )						=> "none",
							),
							"description"       	=> __( "Select if and which tooltips should be shown within the chart.", "ts_visual_composer_extend" ),				
							"dependency"        	=> array( 'element' => "chart_usage", 'value' => 'true' ),
							"group"					=> "Chart",
						),	
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Chart: Text Show", "ts_visual_composer_extend" ),
							"param_name"        	=> "button_chartshow",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['button_chartshow'],
							"dependency"        	=> array( 'element' => "chart_usage", 'value' => 'true' ),
							"group"					=> "Chart",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Chart: Text Hide", "ts_visual_composer_extend" ),
							"param_name"        	=> "button_charthide",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['button_charthide'],
							"dependency"        	=> array( 'element' => "chart_usage", 'value' => 'true' ),
							"group"					=> "Chart",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Chart: Text Save", "ts_visual_composer_extend" ),
							"param_name"        	=> "button_chartsave",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['button_chartsave'],
							"dependency"        	=> array( 'element' => "chart_usage", 'value' => 'true' ),
							"group"					=> "Chart",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Chart: Text Title", "ts_visual_composer_extend" ),
							"param_name"        	=> "chart_title",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['chart_title'],
							"dependency"        	=> array( 'element' => "chart_usage", 'value' => 'true' ),
							"group"					=> "Chart",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Chart: Label Interest", "ts_visual_composer_extend" ),
							"param_name"        	=> "chart_interest",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['chart_interest'],
							"dependency"        	=> array( 'element' => "chart_usage", 'value' => 'true' ),
							"group"					=> "Chart",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Chart: Label Principal", "ts_visual_composer_extend" ),
							"param_name"        	=> "chart_principal",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['chart_principal'],
							"dependency"        	=> array( 'element' => "chart_usage", 'value' => 'true' ),
							"group"					=> "Chart",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Chart: Label Payments", "ts_visual_composer_extend" ),
							"param_name"        	=> "chart_combined",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['chart_combined'],
							"dependency"        	=> array( 'element' => "chart_usage", 'value' => 'true' ),
							"group"					=> "Chart",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Chart: Label Balance", "ts_visual_composer_extend" ),
							"param_name"        	=> "chart_balance",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['chart_balance'],
							"dependency"        	=> array( 'element' => "chart_usage", 'value' => 'true' ),
							"group"					=> "Chart",
						),		
						// Schedule Settings
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_4a",
							"seperator"				=> "Schedule Settings",
							"dependency"        	=> array( 'element' => "schedule_usage", 'value' => 'true' ),
							"group"					=> "Schedule",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Schedule: Text Show", "ts_visual_composer_extend" ),
							"param_name"        	=> "button_scheduleshow",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['button_scheduleshow'],
							"dependency"        	=> array( 'element' => "schedule_usage", 'value' => 'true' ),
							"group"					=> "Schedule",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Schedule: Text Hide", "ts_visual_composer_extend" ),
							"param_name"        	=> "button_schedulehide",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['button_schedulehide'],
							"dependency"        	=> array( 'element' => "schedule_usage", 'value' => 'true' ),
							"group"					=> "Schedule",
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Schedule: Sort Year", "ts_visual_composer_extend" ),
							"param_name"        	=> "schedule_sort",
							"value"             	=> "true",
							"description"       	=> __( "Switch the toggle if you want to provide an option to sort the schedule based on the years column.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "schedule_usage", 'value' => 'true' ),
							"group"					=> "Schedule",
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Schedule: Minimap", "ts_visual_composer_extend" ),
							"param_name"        	=> "schedule_minimap",
							"value"             	=> "true",
							"description"       	=> __( "Switch the toggle if you want to provide an indicator for the currently visible columns when the responsive mode has been triggered.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "schedule_usage", 'value' => 'true' ),
							"group"					=> "Schedule",
						),
						// Printout Settings
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_5a",
							"seperator"				=> "Printout Settings",
							"dependency"        	=> array( 'element' => "printable_usage", 'value' => 'true' ),
							"group"					=> "Printout",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Printout: Text Create", "ts_visual_composer_extend" ),
							"param_name"        	=> "button_printable",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['button_printable'],
							"dependency"        	=> array( 'element' => "printable_usage", 'value' => 'true' ),
							"group"					=> "Printout",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Printout: Text Print", "ts_visual_composer_extend" ),
							"param_name"        	=> "printable_button",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['printable_button'],
							"dependency"        	=> array( 'element' => "printable_usage", 'value' => 'true' ),
							"group"					=> "Printout",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Printout: Text Title", "ts_visual_composer_extend" ),
							"param_name"        	=> "printable_title",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['printable_title'],
							"dependency"        	=> array( 'element' => "printable_usage", 'value' => 'true' ),
							"group"					=> "Printout",
						),
						array(
							"type"                  => "attach_image",
							"heading"               => __( "Printout: Image", "ts_visual_composer_extend" ),
							"param_name"            => "printable_image",
							"value"                 => "",
							"description"           => __( "Select an optional image to be used as logo or banner for the printout version.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "printable_usage", 'value' => 'true' ),
							"group"					=> "Printout",
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Printout: Include Disclaimer", "ts_visual_composer_extend" ),
							"param_name"        	=> "printable_disclaimer",
							"value"             	=> "true",
							"description"       	=> __( "Switch the toggle if you want to include the disclaimer message, if enabled, in the printout version.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "printable_usage", 'value' => 'true' ),
							"group"					=> "Printout",
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Printout: Include Summary", "ts_visual_composer_extend" ),
							"param_name"        	=> "printable_summary",
							"value"             	=> "true",
							"description"       	=> __( "Switch the toggle if you want to include the main loan summary in the printout version.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "printable_usage", 'value' => 'true' ),
							"group"					=> "Printout",
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Printout: Include Chart", "ts_visual_composer_extend" ),
							"param_name"        	=> "printable_chart",
							"value"             	=> "true",
							"description"       	=> __( "Switch the toggle if you want to include the chart, if generated, in the printout version.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "printable_usage", 'value' => 'true' ),
							"group"					=> "Printout",
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Printout: Include Schedule", "ts_visual_composer_extend" ),
							"param_name"        	=> "printable_table",
							"value"             	=> "true",
							"description"       	=> __( "Switch the toggle if you want to include the loan schedule in the printout version.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "printable_usage", 'value' => 'true' ),
							"group"					=> "Printout",
						),
						array(
							"type"              	=> "switch_button",
							"heading"           	=> __( "Printout: Annualized Schedule", "ts_visual_composer_extend" ),
							"param_name"        	=> "printable_annual",
							"value"             	=> "true",
							"description"       	=> __( "Switch the toggle if you want to show only the annualized schedule, or one showing every payment.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "printable_table", 'value' => 'true' ),
							"group"					=> "Printout",
						),
						// Translation Settings
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_6a",
							"seperator"				=> "Translation Settings",
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['single_year'],
							"param_name"        	=> "string_single_year",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['single_year'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['plural_year'],
							"param_name"        	=> "string_plural_year",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['plural_year'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['single_month'],
							"param_name"        	=> "string_single_month",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['single_month'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['plural_month'],
							"param_name"        	=> "string_plural_month",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['plural_month'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['single_day'],
							"param_name"        	=> "string_single_day",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['single_day'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['plural_day'],
							"param_name"        	=> "string_plural_day",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['plural_day'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['not_available'],
							"param_name"        	=> "string_not_available",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['not_available'],
							"group"					=> "Translations",
						),			
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['period_weekly'],
							"param_name"        	=> "string_period_weekly",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['period_weekly'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['period_biweekly'],
							"param_name"        	=> "string_period_biweekly",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['period_biweekly'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['period_semimonthly'],
							"param_name"        	=> "string_period_semimonthly",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['period_semimonthly'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['period_monthly'],
							"param_name"        	=> "string_period_monthly",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['period_monthly'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['period_quarterly'],
							"param_name"        	=> "string_period_quarterly",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['period_quarterly'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['period_semiannually'],
							"param_name"        	=> "string_period_semiannually",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['period_semiannually'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['period_annually'],
							"param_name"        	=> "string_period_annually",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['period_annually'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['payment_date'],
							"param_name"        	=> "string_payment_date",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['payment_date'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['payment_interest'],
							"param_name"        	=> "string_payment_interest",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['payment_interest'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['payment_principal'],
							"param_name"        	=> "string_payment_principal",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['payment_principal'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['payment_balance'],
							"param_name"        	=> "string_payment_balance",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['payment_balance'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['accumulated_principal'],
							"param_name"        	=> "string_accumulated_principal",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['accumulated_principal'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['accumulated_interest'],
							"param_name"        	=> "string_accumulated_interest",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['accumulated_interest'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['accumulated_total'],
							"param_name"        	=> "string_accumulated_total",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['accumulated_total'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['summary_loanamount'],
							"param_name"        	=> "string_summary_loanamount",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['summary_loanamount'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['summary_loanbalance'],
							"param_name"        	=> "string_summary_loanbalance",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['summary_loanbalance'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['summary_datepayout'],
							"param_name"        	=> "string_summary_datepayout",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['summary_datepayout'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['summary_datefirst'],
							"param_name"        	=> "string_summary_datefirst",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['summary_datefirst'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['summary_datelast'],
							"param_name"        	=> "string_summary_datelast",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['summary_datelast'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['summary_paymentfirst'],
							"param_name"        	=> "string_summary_paymentfirst",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['summary_paymentfirst'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['summary_paymentlast'],
							"param_name"        	=> "string_summary_paymentlast",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['summary_paymentlast'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['summary_amortization'],
							"param_name"        	=> "string_summary_amortization",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['summary_amortization'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['summary_interest'],
							"param_name"        	=> "string_summary_interest",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['summary_interest'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['summary_frequency'],
							"param_name"        	=> "string_summary_frequency",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['summary_frequency'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['summary_compounding'],
							"param_name"        	=> "string_summary_compounding",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['summary_compounding'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['summary_fixedterm'],
							"param_name"        	=> "string_summary_fixedterm",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['summary_fixedterm'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['summary_fixedpayments'],
							"param_name"        	=> "string_summary_fixedpayments",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['summary_fixedpayments'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['summary_totalpayments'],
							"param_name"        	=> "string_summary_totalpayments",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['summary_totalpayments'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['summary_totalinterest'],
							"param_name"        	=> "string_summary_totalinterest",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['summary_totalinterest'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "String:", "ts_visual_composer_extend" ) . ' ' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Loan_Calculator_Language_Defaults['summary_totalprincipal'],
							"param_name"        	=> "string_summary_totalprincipal",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['summary_totalprincipal'],
							"group"					=> "Translations",
						),			
						array(
							"type"              	=> "textarea",
							"heading"           	=> __( "Notice: Payment Date 1", "ts_visual_composer_extend" ),
							"param_name"        	=> "string_notice_startdate",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['notice_startdate'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textarea",
							"heading"           	=> __( "Notice: Payment Date 2", "ts_visual_composer_extend" ),
							"param_name"        	=> "string_notice_semimonthly",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['notice_semimonthly'],
							"group"					=> "Translations",
						),
						array(
							"type"              	=> "textarea",
							"heading"           	=> __( "Notice: Insufficient Payment", "ts_visual_composer_extend" ),
							"param_name"        	=> "string_notice_insufficient",
							"value"             	=> $this->TS_VCSC_Loan_Calculator_Language['notice_insufficient'],
							"group"					=> "Translations",
						),
						// Other Settings
						array(
							"type"              	=> "seperator",
							"param_name"        	=> "seperator_7a",
							"seperator"				=> "Other Settings",
							"group"					=> "Other",
						),
						array(
							"type"              	=> "nouislider",
							"heading"           	=> __( "Margin: Top", "ts_visual_composer_extend" ),
							"param_name"        	=> "margin_top",
							"value"             	=> "0",
							"min"               	=> "-50",
							"max"               	=> "500",
							"step"              	=> "1",
							"unit"              	=> 'px',
							"description"       	=> __( "Select the top margin for the element.", "ts_visual_composer_extend" ),
							"group"					=> "Other",
						),
						array(
							"type"              	=> "nouislider",
							"heading"           	=> __( "Margin: Bottom", "ts_visual_composer_extend" ),
							"param_name"        	=> "margin_bottom",
							"value"             	=> "0",
							"min"               	=> "-50",
							"max"               	=> "500",
							"step"              	=> "1",
							"unit"              	=> 'px',
							"description"       	=> __( "Select the bottom margin for the element.", "ts_visual_composer_extend" ),
							"group"					=> "Other",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Define ID Name", "ts_visual_composer_extend" ),
							"param_name"        	=> "el_id",
							"value"             	=> "",
							"description"       	=> __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
							"group"					=> "Other",
						),
						array(
							"type"                  => "tag_editor",
							"heading"           	=> __( "Extra Class Names", "ts_visual_composer_extend" ),
							"param_name"            => "el_class",
							"value"                 => "",
							"description"      		=> __( "Enter additional class names for the element.", "ts_visual_composer_extend" ),
							"group" 				=> "Other",
						),
					)					
				);
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_LeanMap == "true") {
					return $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element;
				} else {			
					vc_map($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VisualComposer_Element);
				};
			}
		}
	}
	// Register Container and Child Shortcode with Visual Composer
	if ((class_exists('WPBakeryShortCode')) && (!class_exists('WPBakeryShortCode_TS_VCSC_Loan_Calculator'))) {
		class WPBakeryShortCode_TS_VCSC_Loan_Calculator extends WPBakeryShortCode {};
	};
	// Initialize "TS Loan Calculator" Class
	if (class_exists('TS_Loan_Calculator')) {
		$TS_Loan_Calculator = new TS_Loan_Calculator;
	}
?>