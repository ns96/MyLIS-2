<?php

class User_model extends CI_Model {
    
    var $lisdb = null;
    var $table = '';
    
    public function __construct() {
	parent::__construct();
	$this->lisdb = $this->load->database('lisdb',TRUE);
        $this->table = $this->session->userdata('group')."_users";
    }
    
    public function getUser($userid){
	$this->lisdb->where('userid',$userid);
	$record = $this->lisdb->get($this->table)->result_array();
	
	if (count($record)>0){	    
	    $userdata['userid'] = $record[0]['userid'];
	    $userdata['password'] = '';
	    $userdata['role'] = $record[0]['role'];
	    $userdata['name'] = $record[0]['name'];
	    $userdata['email'] = $record[0]['email'];
	    $userdata['status'] = $record[0]['status'];
	    $userdata['info'] = $record[0]['info'];
	    
	    $this->load->library('user',$userdata);
	    $user = $this->user;
	} else {
	    $user = null;
	}

	return $user;
    }
    
    public function getUserPassword($userid){
	$this->lisdb->where('userid',$userid);
	$record = $lisdb->get($this->table)->result_array();
	
	if (count($record)>0){	
	    $password = $record[0]['password'];
	} else {
	    $password = null;
	}
	return $password;
    }
    
    public function addUser($data){
        $sql = "INSERT INTO $this->table VALUES('$data[userid]', '$data[password]','$data[role]', '$data[name]', '$data[email]', '$data[status]', '$data[info]')";
        $this->lisdb->query($sql);
    }
    
    public function deleteUser($userid){
        $sql = "DELETE FROM $this->table WHERE userid='$userid'";
        $this->lisdb->query($sql);
    }
    
    public function getGroupUsers($groupname){
	$table = $groupname.'_users';
	$userList = $this->lisdb->get($table)->result_array();
	return $userList;
    }
    
}