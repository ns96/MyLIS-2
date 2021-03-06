<?php

/**
 * Manages MyLIS log entries
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis
 */
class Logger {
  var $properties;
  var $user;
  var $logfile;
  var $old_logfile;
  var $CI;
  
  public function __construct($params) {
    // Set the object's properties
    $this->user = $params['user'];
    $this->CI =& get_instance();
    $this->properties = $this->CI->properties;
    $this->logfile = $this->properties['home.directory'].'/logs/lism.txt';
    $this->old_logfile = $this->properties['home.directory'].'/logs/old_lism.txt';
    // check to see if the log file exist, if not ceate it
    if(is_file($this->logfile)) {
      if(filesize($this->logfile) > 5000) { // if this is creater than 5kb copy to new file
        copy($this->logfile, $this->old_logfile);
        unlink($this->logfile);
        touch($this->logfile);
      }
    }
    else {
      touch($this->logfile); 
    }
    
    //ENVIRONMENT != 'development' || $this->output->enable_profiler(TRUE);
  }
  
  // function to add a log entry
  function addLog($task) {
    $date_time = $this->CI->get_lis_date_time();
    $userid = $this->user->userid;
    
    $fp = fopen($this->logfile, "a") or die ("Couldn't open the log file");
    fputs($fp, "$userid ; $task ; $date_time\n");
  }
}?>
