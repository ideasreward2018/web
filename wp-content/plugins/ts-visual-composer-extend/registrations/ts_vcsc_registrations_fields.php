<?php
	// Add New Fields to Codestar
	// --------------------------
	if (class_exists('CSFramework_Options')) {
		// Add New Hidden Input Field
		class CSFramework_Option_inputhidden extends CSFramework_Options {	
			public function __construct($field, $value = '', $unique = '') {
				parent::__construct($field, $value, $unique);
			}
			public function output(){
				echo $this->element_before();
				echo '<input type="hidden" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_class() . $this->element_attributes() .'/>';
				echo $this->element_after();
			}	
		}
		// Add New Password Input Field
		class CSFramework_Option_inputpassword extends CSFramework_Options {		
			public function __construct($field, $value = '', $unique = '') {
				parent::__construct($field, $value, $unique);
			}  
			public function output(){		  
				echo $this->element_before();
				echo '<input type="password" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_class() . $this->element_attributes() .'/>';
				echo $this->element_after();		  
			}		
		}
	}
?>