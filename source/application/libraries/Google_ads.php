<?php
/** The ads helper class
Displays adds for the users
Copyright (c) 2008 Nathan Stevens

@author Nathan Stevens
@version 0.2 6-29-2010
*/
class Google_ads {
  var $properties;
  var $user;
  
  public function __construct($params) {
    $this->properties = $params['properties'];
    $this->user = $params['user'];
  }
  
  // display google add here
  function displayAds() {
    return '<script type="text/javascript"><!--
            google_ad_client = "pub-1741357337071083";
            /* 468x60, created 6/28/10 */
            google_ad_slot = "5401102386";
            google_ad_width = 468;
            google_ad_height = 60;
            //-->
           </script>
           <script type="text/javascript"
           src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
           </script>';
  }
  
  // funtion to see what task to do
  function doTask($task) {
    connectToDB();
    if($task == 'ads_main') {
      
    }
    else if($task == 'ads_delete') {
      
    }
    else {
      echo "Unknown task : $task in ads class";
    }
    closeDB();
  }
}?>
