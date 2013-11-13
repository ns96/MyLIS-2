<?php
/** The message helper class
Displays messages for the users
Copyright (c) 2008 Nathan Stevens

@author Nathan Stevens
@version 0.2 6-7-09
*/

class Message_model extends CI_Model {
    
    var $lisdb = null;
    var $table = null;
    
    public function __construct() {
	parent::__construct();
	$this->lisdb = $this->load->database('lisdb',TRUE);
	$this->table = $this->session->userdata('group')."_messages";
    }
    
    // function to return the array containing the message
    public function getMessage($message_id) {
	$sql = "SELECT * FROM $this->table WHERE message_id='$message_id'";
	$records = $this->lisdb->query($sql)->result_array();

	return $records[0];
    }
    
    public function getUserMessages(){
	$sql = "SELECT * FROM $this->table ORDER BY message_id DESC";
	$messages = $this->lisdb->query($sql)->result_array();

	return $messages;
    }
    
    public function getSystemMessages($account_id){
	$sql = "SELECT * FROM lismessages WHERE account_ids='ALL' OR account_ids LIKE '%$account_id%'";
	$messages = $this->lisdb->query($sql)->result_array();
	return $messages;
    }
    
    public function addMessage($data){
    
	$sql = "INSERT INTO $this->table VALUES('', '$data[date_time]','', '$data[message]', '$data[url]', '', '$data[userid]')";
	$this->lisdb->query($sql);

	// add file to file_id table now and update db table if need be
	if(!empty($data['file_id'])) {
	    // insert the file_id into message table
	    $message_id = $this->lisdb->insert_id();
	    $sql = "UPDATE $this->table SET file_id = '$data[file_id]' WHERE message_id = '$message_id'";
	    $this->lisdb->query($sql);
	}
    }
    
    public function updateMessage($data){

	$sql = "UPDATE $this->table SET date = '$data[date_time]', message = '$data[message]', url = '$data[url]', userid='$data[userid]' WHERE message_id = '$data[message_id]'";
	$this->lisdb->query($sql);

	// if there wasn't any file attached to this message before, then a new file_id must be in $data
	if(!empty($data['file_id'])) {
	    $sql = "UPDATE $this->table SET file_id = '$data[file_id]' WHERE message_id = '$data[message_id]'";
	    $this->lisdb->query($sql);
	}
    }
    
    public function deleteMessage($id){
	$sql = "DELETE FROM $this->table WHERE message_id = '$id'";
	$this->lisdb->query($sql);
    }
    
    public function getPoster(){
	
    }
    
}