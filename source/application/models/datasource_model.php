<?php
/** Class to pupulate demo account and developement account with data

Copyright (c) 2008 Nathan Stevens

@author Nathan Stevens
@version 0.22 4-4-2009
*/

class datasource {
  var $properties = null;
  var $user = null;
  var $account_id = null;
  var $lisdb = null;
  var $user_names = array();
  
  public function __construct() {
    parent::__construct();
    $this->CI =& get_instance();
    $this->properties = $this->CI->properties;
    $this->user = $this->session->userdata('user');
    $this->lisdb = $this->load->database('lisdb',TRUE);
  }
  
  // function to add demo data
  function addSandboxData($account_id) {
    $this->account_id = $account_id;
    $this->addUsers();
    $this->addMessages();
    $this->addLocations();
    $this->addChemicals(250);
    $this->addSupplies(75);
    $this->addGroupMeetings();
    $this->addOrders();
    $this->addPublications();
    $this->addInstruments();
    $this->addGroupTask();
    $this->addBibliographies();
    $this->addFiles();
    $this->addWeblinks();
  }
  
  // function to add a set of three users to database
  function addUsers() {
    $table = $this->account_id.'_users';
    $this->user_names[] = 'Carl Higgins';
    $this->user_names[] = 'Dylan Marks';
    $this->user_names[] = 'Debora Callinger';
    $this->user_names[] = 'Adam Maple';
    $password = 'pass';
    
    // set defualts categories
    $sql = "INSERT INTO $table 
    VALUES ('user1', '$password', 'user', 'Carl Higgins', 'user1@nano.edu', 'present', ''),
    ('user2', '$password', 'user', 'Dylan Marks', 'user2@nano.edu', 'present', ''),
    ('user3', '$password', 'user', 'Debora Callinger', 'user3@nano.edu', 'present', ''),
    ('user4', '$password', 'user', 'Adam Maple', 'user4@nano.edu', 'present', '')";
    $this->lisdb->query($sql);
  }
  
  // add messages
  function addMessages() {
    $table = $this->account_id.'_messages';
    $date = getLISDateTime();
    $text1 = 'Some message of interest to the group in general dealing with chemicals';
    $text2 = 'Message about article which talks about the nature of the human animal';
    $text3 = 'Science article highlighting the discovery of intelligent life<br>found in the oceans of on an ice moon';
    
    $sql = "INSERT INTO $table VALUES ('', '$date', 'User Message 1', '$text3', 'http://www.science.org', '', 'user3')";
    $this->lisdb->query($sql);
  }
  
  // function to add 5 locations
  function addLocations() {
    $table = $this->account_id.'_locations';
    
    // set default location table now
    $sql = "INSERT INTO $table 
    VALUES ('CA', 'R-500', 'Cabinet by window', 'user1', 'myadmin'),
    ('CB', 'R-400', 'Cabinet by door', 'user2', 'myadmin'),
    ('CC', 'R-300', 'Cabinet by fume hood', 'user3', 'myadmin'),
    ('FC', 'R-500', 'Flamable cabinet by fume hood', 'user1', 'myadmin')";
    $this->lisdb->query($sql);
  }
  
  // function to add dumy chemicals
  function addChemicals($count) {
    $table = $this->account_id.'_chemicals';
    $date = getLISDateTime();
    $locations = array('CA', 'CB', 'CC', 'FC');
    
    $sql = "INSERT INTO $table VALUES ";
    for($i = 1; $i <= $count; $i++) {
      $name1 = 'Chemical '.$i*rand(1, 3);
      $name2 = 'Chemical '.$i*rand(1, 3);
      $units = rand(1, 500).' ml';
      $user = 'user'.rand(1, 4);
      $cas = 'ABC-0'.$amount.'00-0'.$i;
      $loc = $locations[rand(0,3)];
      
      $sql .= "('', '$cas', '$name1', 'Aldrich', '3456-5ML', '1', '$units', '$date', 'instock', '$date', '500/C10H25N5', 'Organic', '?', 'None', '$user', 'myadmin'),";
      $sql .= "('', '$cas', '$name2', 'VWR', '4555-5ML', '2', '$units', '$date', 'instock', '$date', '1000/C10H25N5Si90', 'Inorganic', '$loc', 'Toxic', 'jhsmith@nano.edu', 'myadmin')";
      
      if($i != $count) {
        $sql .= ',';
      }
    }
    $this->lisdb->query($sql);
  }
  
  // function to add dummy supplies
  function addSupplies($count) {
    $table = $this->account_id.'_supplies';
    $date = getLISDateTime();
    $locations = array('CA', 'CB', 'CC', 'FC');
    
    $sql = "INSERT INTO $table VALUES ";
    for($i = 1; $i <= $count; $i++) {
      $name1 = 'Supply '.$i.'-'.rand(1, 3);
      $name2 = 'Supply '.$i.'-'.rand(1, 3);
      $units = rand(2, 10).' units';
      $user = 'user'.rand(1, 4);
      $loc = $locations[rand(0,3)];
      
      $sql .= "('', 'N/A', '$name1', 'Acme Industrial', '3456', '1', '$units', '$date', 'instock', '$date', 'N/A', 'Equipment', '?', 'None', '$user', 'myadmin'),";
      $sql .= "('', 'N/A', '$name2', 'AcmeSoft', '4555', '1', '$units', '$date', 'instock', '$date', 'N/A', 'Software', '$loc', 'None', 'jhsmith@nano.edu', 'myadmin')";
      
      if($i != $count) {
        $sql .= ',';
      }
    }
    $this->lisdb->query($sql);
  }
  
  function addGroupMeetings() {
     $year = date("Y");
     $this->addGroupMeetingForYear($year-1);
     $this->addGroupMeetingForYear($year);
     $this->addGroupMeetingForYear($year+1);
  }
  
  // function to add group meeting dates
  function addGroupMeetingForYear($year) {
    // add group meeting dates
    $table = $this->account_id.'_gmdates';
    $semester = 2; // winter semester
    $date_id = array();
    
    for($i = 1; $i <= 12; $i++) {
      $date = $this->getFirstMonday($i, $year);
      $sql = "INSERT INTO $table VALUES ('', '$semester', '$date', '10:00 PM', 'myadmin')";
      $this->lisdb->query($sql);
      $date_id[$i] = $this->lisdb->insert_id();
      $i++;
      
      $date = $this->getFirstMonday($i, $year);
      $sql = "INSERT INTO $table VALUES ('', '$semester', '$date', '10:00 PM', 'myadmin')";
      $this->lisdb->query($sql);
      $date_id[$i] = $this->lisdb->insert_id();
      $i++;
      
      $date = $this->getFirstMonday($i, $year);
      $sql = "INSERT INTO $table VALUES ('', '$semester', '$date', '10:00 PM', 'myadmin')";
      $this->lisdb->query($sql);
      $date_id[$i] = $this->lisdb->insert_id();
      $semester++;
    }
    
    // add group meeting slots
    $table = $this->account_id.'_gmslots';
    for($i = 1; $i <= 12; $i++) {
      $presenter1 = $this->user_names[rand(0,3)];
      $presenter2 = $this->user_names[rand(0,3)];
      $presenter3 = $this->user_names[rand(0,3)];
      $title = 'Research Progress Report';
      $mdate = "1/$i/$year";
      $gmdate_id = $date_id[$i];
      
      $sql = "INSERT INTO $table 
      VALUES ('', '$gmdate_id', 'Research Talk', '$presenter1', '$title', '', '$mdate', 'myadmin'),
      ('', '$gmdate_id', 'Research Talk', '$presenter2', '$title', '', '$mdate', 'myadmin'),
      ('', '$gmdate_id', 'Literature Talk', '$presenter3', 'Latest Nano/Bio Tech Articles', '', '$mdate', 'myadmin')";
      $this->lisdb->query($sql);
    }
  }
  
  // function to get the fisrt monday of month
  function getFirstMonday($month, $year) {
    $num = date("w",mktime(0,0,0,$month,1,$year));
    
    if($num==1) {
      return date("Y/m/d H:i:s", mktime(0,0,0,$month,1,$year));
    }
    elseif($num>1) {
      return date("Y/m/d H:i:s", mktime(0,0,0,$month,1,$year)+(86400*(8-$num)));
    }
    else {
      return date("Y/m/d H:i:s", mktime(0,0,0,$month,1,$year)+(86400*(1-$num)));
    }
  }
  
  // function to add dummy orders
  function addOrders() {
    $year = date("Y");
    $month = date("m");
    $companies = array('Fisher', 'Aldrich', 'VWR', 'Chem Glass', 'Invitrogen');
    $accounts = array('F56453', 'A34567', 'V23875', 'CG45424', 'I664367D');
    
    // add companies and account number now
    $ct_table = $this->account_id.'_categories';
    $o_table = $this->account_id.'_orders';
    
    $sql = "INSERT INTO $ct_table VALUES('', '$o_table', 'company', 'Chem Glass', 'admin'),
    ('', '$o_table', 'company', 'Invitrogen', 'admin')";
    $this->lisdb->query($sql);
    
    foreach($accounts as $account) {
      $sql = "INSERT INTO $ct_table VALUES('', '$o_table', 'account', '$account', 'admin')";
      $this->lisdb->query($sql);
    }
    
    // add orders for last year and this year
    $this->addOrdersForYear($year, $month, $companies, $accounts);
    $this->addOrdersForYear($year-1, 12, $companies, $accounts);
  }
  
  //function to add orders for a particular year
  function addOrdersForYear($year, $month, $companies, $accounts) {
    //echo "Year $year Month $month<br>";
    $table = $this->account_id.'_orders';
    $itable = $this->account_id.'_order_items';
    
    for($i = 1; $i <= $month; $i++) {
      // add 10 orders per month
      for($k = 1; $k <= 10; $k++) {
        $index = rand(0,4);
        $company = $companies[$index];
        $account = $accounts[$index];
        
        $po = '3456789';
        
        if($k > 3 ) {
          $user = 'user'.rand(1,4);
        }
        else {
          $user = 'jhsmith@nano.edu';
        }
        
        $sh = 15.00;
        $date1 = $i.'/'.($k*2).'/'.$year;
        $date2 = $i.'/'.($k*2 + 5).'/'.$year;
        
        // set status depending if we are in same year
        $status = 'recieved';
        if($year == date("Y") && $i == date("m")) {
          $status = 'ordered';
        }
        
        // if k = 2 || 4 and month is 2 or 8 then store this order as an item list
        if(($k == 2 || $k == 4) && ($i == 2 || $i == 8)) {
          $status = 'itemlist';
        }
        
        $sql = "INSERT INTO $table VALUES ('', '$company', '$po', 'n/a', 'LOW', '$account', '$date1', '$status', '$date2', 
        '0.00', '0.00', '$sh', '0.00', 'None', '$user', 'myadmin', '10')";
        $this->lisdb->query($sql);
        $order_id = $this->lisdb->insert_id();
        
        // add items now
        $total = 0;
        for($j = 1; $j <= 10; $j++) {
          $type = 'Chemical';
          $description = 'Chemical '.$j*$i;
          $units = $j*$k*$i.' mg';
          if($j%3 == 0) {
            $type = 'Supply';
            $description = 'Supply '.$j*$i;
            $units = 'box';
          }
          $price = 10*rand(1, 20);
          $total += $price;
          
          if($status == 'ordered' && $j >= 7) {
            $status  = 'pending';
          }
          
          $sql = "INSERT INTO $itable VALUES ('', '$order_id', '', '$j', '$type', '$company', 'S4563', '$description', '1', 
          '$units', '$price', '$status', '$date2', '$user', 'myadmin')";
          $this->lisdb->query($sql);
        }
        
        // update the order total
        $sql = "UPDATE $table SET g_expense = $total, t_expense='$total' WHERE order_id='$order_id'";
       $this->lisdb->query($sql);
      }
    }
  }
  
  // function to add dummy publication data
  function addPublications() {
    $table = $this->account_id.'_publications';
    $year = date("Y");
    $month = date("m");
    
    $type_list = array('paper', 'review', 'patent');
    $status_list = array('Proposed', 'In Progress', 'Submitted', 'Accepted', 'Withdrawn');
    for($i = 1; $i <= 24; $i++) {
      $type = $type_list[rand(0,2)];
      $status = $status_list[rand(0,4)];
      
      if($type == 'paper') {
        $title = 'Paper on Some Interesting Subject';
        $abstract  = 'The abstract of this paper goes here ...';
      }
      elseif($type == 'review') {
        $title = 'Review of Dynamic Systems';
        $abstract  = 'The abstract of this review goes here ...';
      }
      else {
        $title = 'Method how to Create and Delete Records with Super Efficiency';
        $abstract  = 'The abstract of this patent goes here ...';
      }
      
      $authors = $this->user_names[0].', '.$this->user_names[1].', '.$this->user_names[2].', '.
      $this->user_names[3].', and John H. Smith';
      
      $m = rand(1,12);
      $date1 = "$m/5/$year";
      $date2 = "$m/27/$year";
      
      if($i > 3 ) {
        $user = 'user'.rand(1,4);
      }
      else {
        $user = 'jhsmith@nano.edu';
      }
      
      $sql = "INSERT INTO $table VALUES ('', '$title', '$authors', '$type', '$status', '$date1', '$date2', 
      'not set', '$abstract', 'n/a', '', '$user')";
      $this->lisdb->query($sql);
    }
  }
  
  // function to add instrument logs
  function addInstruments() {
    $table = $this->account_id.'_instrulog';
    $rtable = $this->account_id.'_reservations';
    $year = date("Y");
    $month = date("m");
    $days = date("t");
    
    for($i = 1; $i <= 5; $i++) {
      $instrument = 'Group Instrument '.$i;
      $manager_id = 'user'.rand(1,4);
            
      $sql = "INSERT INTO $table VALUES('', '$instrument', '$manager_id', 'n/a', 'n/a', 'myadmin')";
      $this->lisdb->query($sql);
      $instrulog_id = $this->lisdb->insert_id();
      
      // add reservations for current days of month
      for($k = 1; $k <= $days; $k++) {
        $date = "$month/$k/$year";
        $start = rand(7,12);
        $end = rand(14, 24);
        for($j = $start; $j <= $end; $j++) {
          $user = 'user'.rand(1,4);
          $sql = "INSERT INTO $rtable VALUES('', 'instrument', '$instrulog_id', '$date', '$j', '0', '', '$user')";
         $this->lisdb->query($sql);
        }
      }
    }
  }
  
  // function to add group task
  function addGroupTask() {
    $table = $this->account_id.'_grouptask';
    $itable = $this->account_id.'_grouptask_item';
    $year = date("Y");
    $month = date("m");
    
    for($i = 1; $i <= 7; $i++) {
      $task = 'Group Task '.$i;
      $manager_id = 'user'.rand(1,4);
      
      if($i%2 == 0) {
        $type = 'monthly';
        $task = 'Monthly Task '.$i;
      } else {
        $type = 'list';
      }
      $note = 'All group members are expected to complete their assigned task.';
      
      $sql = "INSERT INTO $table VALUES('', '$task', '$type', '$year', '$manager_id', '$note', 'myadmin')";
      $this->lisdb->query($sql);
      $grouptask_id = $this->lisdb->insert_id();
      
      // add the task items now
      if($type == 'monthly') {
        for($k = 1; $k <= 12; $k++) {
          if($k < $month) {
            $completed = 'YES';
          } else {
            $completed = 'NO';
          }
          
          $user = $this->user_names[rand(0,3)];
          
          $sql = "INSERT INTO $itable VALUES('', '$grouptask_id', '0', '0', '$k', '$completed', '', '$user')";
          $this->lisdb->query($sql);
        }
      }
      else {
        for($k = 1; $k <= 10; $k++) {
          if($k <= 4) {
            $completed = 'YES';
          } else {
            $completed = 'NO';
          }
          
          $user = $this->user_names[rand(0,3)];
          
          $sql = "INSERT INTO $itable VALUES('', '$grouptask_id', '$k', '0', '0', '$completed', '', '$user')";
          $this->lisdb->query($sql);
        }
      }
    }
  }
  
  // function to add a few fake files
  function addBibliographies() {
    $table = $this->account_id.'_doclibrary';
    $ctable = $this->account_id.'_categories';
    $ftable = $this->account_id.'_files';
    $categories = array('Cell Biology', 'Neurology', 'Drug Compounds', 'Biological Materials', 'Cancer Research', 'Molecular Probes');
    
    for($i = 1; $i <= 6; $i++) {
      $category = $categories[$i-1];
      $sql = "INSERT INTO $ctable VALUES('', '$table','filing', '$category', 'myadmin')";
      $this->lisdb->query($sql);
      $cat_id = $this->lisdb->insert_id();
      
      $max = rand(1, 6);
      for($k = 1; $k <= $max; $k++) {
        // add a dumy file now
        $title = 'Library '.$k;
        $description = 'EndNote file containing references related to '.$category;
        $url = 'http://www.ncbi.nlm.nih.gov/pubmed/';
        $status = 'current';
        $date_time = getLISDateTime();
        
        if($k < 3) {
          $userid = 'jhsmith@nano.edu';
        } else {
          $userid = 'user'.rand(1,4);
        }
        
        $sql = "INSERT INTO $table VALUES('', 'EndNote', '$title','$description','$url', '', '$cat_id', 
        '$status', '$userid', '$date_time', 'YES', '$userid')";
        $this->lisdb->query($sql);
        $library_id = $this->lisdb->insert_id();
        
        $sql = "INSERT INTO $ftable VALUES('', 'url','$description', '$url', '$table', '$library_id', '0', '$userid', '$userid')";
        $this->lisdb->query($sql);
        $file_id = $this->lisdb->insert_id();
        
        $sql = "UPDATE $table SET file_id = '$file_id' WHERE library_id = '$library_id'";
        $this->lisdb->query($sql);
      }
    }
  }
  
  // function to add file folders
  function addFiles() {
    $table = $this->account_id.'_folder_files';
    $ctable = $this->account_id.'_categories';
    $ftable = $this->account_id.'_files';
    $categories = array('Instrument Manuals', 'Lab Safety', 'Reagents', 'Protocols');
    
    for($i = 1; $i <= 4; $i++) {
      $category = $categories[$i-1];
      $sql = "INSERT INTO $ctable VALUES('', '$table','filing', '$category', 'myadmin')";
      $this->lisdb->query($sql);
      $cat_id = $this->lisdb->insert_id();
      
      $max = rand(1, 8);
      for($k = 1; $k <= $max; $k++) {
        // add a dumy file now
        $title = $category.' File '.$k;
        $url = 'http://www.ncbi.nlm.nih.gov/pubmed/';
        
        if($k < 3) {
          $userid = 'jhsmith@nano.edu';
        } else {
          $userid = 'user'.rand(1,4);
        }
        
        $sql = "INSERT INTO $ftable VALUES('', 'url','$description', '$url', '$table', '0', '0', '$userid', '$userid')";
        $this->lisdb->query($sql);
        $file_id = $this->lisdb->insert_id();
        
        $sql = "INSERT INTO $table VALUES('$file_id', '$title', '$cat_id', '$userid')";
        $this->lisdb->query($sql);
      }
    }
  }
  
  // function to add weblinks
  function addWeblinks() {
    $table = $this->account_id.'_weblinks';
    $ctable = $this->account_id.'_categories';
    $categories = array('Journal Sites', 'Spectroscopy Sites', 'Cell Biology Sites', 'Safe Sites');
    
    for($i = 1; $i <= 4; $i++) {
      $category = $categories[$i-1];
      $sql = "INSERT INTO $ctable 
      VALUES('', '$table','filing', '$category', 'myadmin')";
      $this->lisdb->query($sql);
      $cat_id = $this->lisdb->insert_id();
      
      $max = rand(4, 8);
      for($k = 1; $k <= $max; $k++) {
        // add a dumy file now
        $title = $category.' Web Link '.$k;
        $url = 'http://www.ncbi.nlm.nih.gov/pubmed/';
        
        if($k < 3) {
          $userid = 'jhsmith@nano.edu';
        } else {
          $userid = 'user'.rand(1,4);
        }
                
        $sql = "INSERT INTO $table VALUES('', '$title', '$url', '$cat_id', '$userid')";
        $this->lisdb->query($sql);
      }
    }
  }
}?>
