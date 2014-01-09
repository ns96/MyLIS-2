<?php

/**
 * Used as a placeholder for timezones
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis
 */
class Listimezones {
    
  public $lis_tz;
  
  public function __construct(){    
        // initialize the time zone array. Taken from PHP cookbook!
	$this->lis_tz = array(
	    'UTC' => 0,  // Universal
	    'EST' => -5*3600,  // Eastern Standard
	    'CST' => -6*3600,  // Central Standard
	    'MST' => -7*3600,  // Mountain Standard
	    'PST' => -8*3600,  // Pacific Standard
	);
  }
  
  public function get_tz(){
      return $this->lis_tz;
  }
  
}?>