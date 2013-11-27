<?php

class Instrulog_model extends CI_Model {
    
    var $lisdb = null;
    var $table = null;
    var $r_table = null;
    
    public function __construct() {
	parent::__construct();
	$this->lisdb = $this->load->database('lisdb',TRUE);
	$this->table = $this->session->userdata('group')."_instrulog";
	$this->r_table = $this->session->userdata('group')."_reservations";
    }

    public function getInstruments(){
	$sql = "SELECT * FROM $this->table";
	$records = $this->lisdb->query($sql)->result_array();
	return $records;
    }
    
    public function getInstrument($id){
	$sql = "SELECT * FROM $this->table WHERE instrulog_id = $id";
	$records = $this->lisdb->query($sql)->result_array();
	return $records[0];
    }
    
    public function getReservations($s_date,$instrument_id){
    
	$reservations = array();

	// get reservation from DB
	$sql = "SELECT * FROM $this->r_table WHERE (date = '$s_date' AND type = 'instrument' AND table_id = '$instrument_id')";
	$records = $this->lisdb->query($sql)->result_array();
	
	if(count($records) > 0) {
	    $counter = 0;
	    foreach ($records as $record){
		$counter++;
		$this->load->library('reservation',$record,"temp".$counter); // dummy custom name for each object
		$reservations[$record['hour']] = $this->{"temp".$counter};
	    }
	}
	return $reservations;
    }
 
    public function addReservation($data){
	$sql = "INSERT INTO $this->r_table VALUES('', 'instrument', '$data[instrument_id]', '$data[date]', '$data[i]', '0', '$data[note]','$data[userid]')";
	$this->lisdb->query($sql);
    }
    
    public function updateReservation($data){
	$sql = "UPDATE $this->r_table SET note = '$data[note]' WHERE reservation_id = '$data[reservation_id]'";
	$this->lisdb->query($sql);
    }
    
    public function deleteReservation($id){
	$sql = "DELETE FROM $this->r_table WHERE reservation_id = '$id'";
	$this->lisdb->query($sql);
    }
    
    public function deleteInstrument($id){
	$sql = "DELETE FROM $this->table WHERE instrulog_id = '$id'";
	$this->lisdb->query($sql);

	// remove any entries in the reservations table
	$sql = "DELETE FROM $this->r_table WHERE (type = 'instrument' AND table_id = '$id')";
	$this->lisdb->query($sql);
    }
 
    public function addInstrument($data){
	$sql = "INSERT INTO $this->table VALUES('', '$data[instrument]', '$data[manager_id]', '$data[file_ids]', '$data[notes]', '$data[userid]')";
	$this->lisdb->query($sql);
	return $this->lisdb->insert_id();
    }
    
}