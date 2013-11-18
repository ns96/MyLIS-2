<?php

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
	$sql = "SELECT * FROM $this->table WHERE message_id=$message_id";
	$records = $this->lisdb->query($sql)->result_array();

	return $records[0];
    }
    
    public function getSystemMessage($message_id){
	$sql = "SELECT * FROM lismessages WHERE message_id='$message_id'";
	$messages = $this->lisdb->query($sql)->result_array();
	return $messages[0];
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
    
    public function getAllSystemMessages(){
	// create the tables holding the current messages
	$sql = "SELECT * FROM lismessages";
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
    
    public function addSystemMessage($data){
	$sql = "INSERT INTO lismessages VALUES(' ', '$data[message_date]', '$data[post_start]', '$data[post_end]', '$data[account_ids]', '$data[message]', '$data[url]', '$data[manager]')";
	$sql_result = $this->lisdb->query($sql);
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
    
    public function updateSystemMessage($data){
	$sql = "UPDATE lismessages set account_ids='$data[account_ids]',post_start='$data[post_start]',post_end='$data[post_end]',
	url='$data[url]', message='$data[message]' WHERE message_id = $data[message_id]";

	$this->lisdb->query($sql);
    }
    
    public function deleteMessage($id){
	$sql = "DELETE FROM $this->table WHERE message_id = '$id'";
	$this->lisdb->query($sql);
    }
    
    public function deleteSystemMessage($id){
	$sql = "DELETE FROM lismessages WHERE message_id='$id'";
	$this->lisdb->query($sql);

	// if there are no records left in the database reset the count to zero
	$sql = "SELECT * FROM lismessages";
	$records = $this->lisdb->query($sql)->result_array();

	if(count($records) == 0) {
	    $sql = "ALTER TABLE lismessages AUTO_INCREMENT=1";
	    $this->lisdb->query($sql);
	}
    }
    
}