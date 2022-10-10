<?php

if (class_exists('OxygenExtraElements')){
	return;
}

class OxygenExtraElements extends OxyEl {

    
    function init() {

		$this->El->useAJAXControls();
		
	}
    
	function class_names() {
		return '';
	}
    
    function button_place() {
        
        //if (class_exists('EditorEnhancer_Admin')) {   
        
       //     return "other";
            
      //  } 
        
      //  else {
            $extras_button_place = $this->extras_button_place();

            if ($extras_button_place) {
                return "extras::".$extras_button_place;
            }
            
      //  }

        
    }
    
    
    function button_priority() {
        return '';
    }
 

}