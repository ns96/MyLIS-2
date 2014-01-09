<?php

/**
 * Manages the information related to a group's publications.
 * 
 * Used only by group controllers
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis 
 */
class Publication_model extends CI_Model {
    
    var $lisdb = null;
    var $table = null;
    
    public function __construct() {
	parent::__construct();
	$this->lisdb = $this->load->database('lisdb',TRUE);
	$this->table = $this->session->userdata('group')."_publications";
    }
    
    public function get_table_status(){
	$status = array();

	$sql = "SELECT COUNT(*) AS count FROM ".$this->table;
	$record = $this->lisdb->query($sql)->result_array();
	$status[0] = $record[0]['count'];

	$sql = "SHOW TABLE STATUS LIKE '".$this->table."'";
	$record = $this->lisdb->query($sql)->result_array();
	$status[1] = date("m/d/Y g:i A",strtotime($record[0]['Update_time']));

	return $status;
    }
    
    public function get_posters(){
	$posters = array();
    
	$sql = "SELECT userid FROM $this->table";
	$records = $this->lisdb->query($sql)->result_array();
	foreach($records as $poster){
	    $userid = $poster['userid'];
	    $posters[$userid] = $userid;
	}

	return $posters;
    }
    
    public function get_user_publications($userid){
	$sql = "SELECT * FROM $this->table WHERE userid='$userid' ORDER BY modify_date DESC";
	$records = $this->lisdb->query($sql)->result_array();
	return $records;
    }
    
    public function get_publication($id){
	$sql = "SELECT * FROM $this->table WHERE publication_id='$id'";
	$records = $this->lisdb->query($sql)->result_array();
	return $records[0];
    }
    
    public function add_publication($data){
	$sql = "INSERT INTO $this->table VALUES('', '$data[title]','$data[authors]', '$data[type]', '$data[status]', '$data[start_date]', '$data[modify_date]', '$data[end_date]', '$data[abstract]', '$data[comments]', '$data[file_ids]', '$data[userid]')";
	$records = $this->lisdb->query($sql);
	$pub_id = $this->lisdb->insert_id();
	return $pub_id;
    }
    
    public function update_publication($data){
	$sql = "UPDATE $this->table SET title = '$data[title]', authors = '$data[authors]', type = '$data[type]', status = '$data[status]', 
	    start_date = '$data[start_date]', modify_date = '$data[modify_date]', end_date = '$data[end_date]', abstract = '$data[abstract]', 
	    comments = '$data[comments]' WHERE publication_id='$data[pub_id]'";
	$this->lisdb->query($sql);
    }
    
    public function delete_publication($pub_id){
	$sql = "DELETE FROM $this->table WHERE publication_id = '$pub_id'";
	$this->lisdb->query($sql);
    }
    
    public function add_file($data){
	$sql = "UPDATE $this->table SET modify_date = '$data[modify_date]', file_ids = '$data[file_ids]' WHERE publication_id='$data[pub_id]'";
	$this->lisdb->query($sql);
    }
    
    public function delete_file($data){
	$sql = "UPDATE $this->table SET modify_date = '$data[modify_date]', file_ids = '$data[new_file_ids]' WHERE publication_id='$data[pub_id]'";
	$this->lisdb->query($sql);
    }
    
}