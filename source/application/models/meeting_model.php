<?php

/**
 * Manages the information related to group meetings.
 * 
 * Used only by group controllers
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis 
 */
class Meeting_model extends CI_Model {
    
    var $table = ''; // The group meeting date table name
    var $s_table = ''; // The group meeting slot table name
    
    public function __construct() {
	parent::__construct();
	$this->lisdb = $this->load->database('lisdb',TRUE);
	$this->table = $this->session->userdata('group')."_gmdates";
	$this->s_table = $this->session->userdata('group')."_gmslots";
    }
    
    public function get_gm_dates($year){

	$sql = "SELECT * FROM $this->table WHERE gmdate LIKE '$year%' ORDER BY gmdate";
	$records = $this->lisdb->query($sql)->result_array();
	
	$gmdates = array();
	if(count($records) > 0) {
	    foreach($records as $date) {
		$gmdate_id = $date['gmdate_id'];
		$lis = dateToLIS($date['gmdate']);
		$gd = new gm_date($gmdate_id, $date['semester_id'], $lis, $date['gmtime'], $date['userid']);
		$gmdates[$gmdate_id] = $gd;
	    }
	}
	return $gmdates;
    }
    
    public function get_gm_date($gmdate_id){
        $sql = "SELECT * FROM $this->table WHERE gmdate_id = '$gmdate_id'";
        $records = $this->lisdb->query($sql)->result_array();
        return $records[0];
    }
    
    public function get_date_slots($gmdate_id){
	$sql = "SELECT * FROM $this->s_table WHERE gmdate_id ='$gmdate_id'";
	$records = $this->lisdb->query($sql)->result_array();
	return $records;
    }
    
    public function get_slot_info($slot_id){
	$slot_info = array();
	$sql = "SELECT * FROM $this->s_table WHERE slot_id = '$slot_id'";
	$records = $this->lisdb->query($sql)->result_array();
	return $records[0];
    }

    public function add_slot($data){
	$sql = "INSERT INTO $this->s_table VALUES('', '$data[gmdate_id]','$data[type]', '$data[presenter]', '$data[title]', '$data[file_id]', '$data[modify_date]', '$data[userid]')";
	$this->lisdb->query($sql);
    }
    
    public function update_slot($data){
	$sql = "UPDATE $this->s_table SET gmdate_id = '$data[gmdate_id]', type = '$data[type]', 
	    presenter = '$data[presenter]', title = '$data[title]', modify_date = '$data[modify_date]' WHERE slot_id = '$data[slot_id]'";
	$this->lisdb->query($sql);
    }
    
    public function delete_slot($slot_id){
	 $sql = "DELETE FROM $this->s_table WHERE slot_id = '$slot_id'";
	 $this->lisdb->query($sql);
    }

    public function add_date($data){
	$sql = "INSERT INTO $this->table VALUES('', '$data[semester_id]','$data[gmdate]', '$data[gmtime]', '$data[userid]')";
	$this->lisdb->query($sql);
    }
    
    public function update_date($data){
	$sql = "UPDATE $this->table SET semester_id = '$data[semester_id]', gmdate = '$data[gmdate]', 
	    gmtime = '$data[gmtime]', userid = '$data[userid]' WHERE gmdate_id = '$data[gmdate_id]'";
	$this->lisdb->query($sql);
    }
    
    public function delete_date($gmdate_id){
	$sql = "SELECT * FROM $this->s_table WHERE gmdate_id = '$gmdate_id'";
	$records = $this->lisdb->query($sql)->result_array();
	foreach ($records as $dateItem){
	    $this->deleteSlot($dateItem['slot_id']);
	}
	$sql = "DELETE FROM $this->table WHERE gmdate_id = '$gmdate_id'";
	$this->lisdb->query($sql);
    }
    
    public function update_slot_file($data){
	$sql = "UPDATE $this->s_table SET modify_date = '$data[modify_date]',file_id = '$data[file_id]' WHERE slot_id = '$data[slot_id]'";
	$this->lisdb->query($sql);
    }
    
    public function delete_slot_file($data){
	$sql = "UPDATE $this->s_table SET modify_date = '$data[modify_date]', file_id = '' WHERE slot_id = '$data[slot_id]'";
	$this->lisdb->query($sql);
    }
    
}

/*Class to store group meeting date information */
class gm_date {
  var $gmdate_id = '';
  var $semester_id = '';
  var $date = '';
  var $time = '';
  var $userid = '';
  
  function gm_date($gmdate_id, $semester_id, $date, $time, $userid) {
    $this->gmdate_id = $gmdate_id;
    $this->semester_id = $semester_id;
    $this->date = $date;
    $this->time = $time;
    $this->userid = $userid;
  }
}