<?php

class User_model extends CI_Model {
    
    var $lisdb = null;
    var $lismdb = null;
    var $table = '';
    
    public function __construct() {
	parent::__construct();
	$this->lisdb = $this->load->database('lisdb',TRUE);
        $this->lismdb = $this->load->database('lismdb',TRUE);
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
	$record = $this->lisdb->get($this->table)->result_array();
	
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
    
    public function update_user($data){
        // update the users database table now
  
        $sql = "UPDATE $this->table SET password = '$data[password]', name = '$data[name]', email = '$data[email]', role = '$data[role]', 
        status = 'present', info = '$data[info]' WHERE userid = '$data[userid]'";
        $this->lisdb->query($sql);

        // update the global users table if the users_id which should be an email, is in there already
        $sql = "UPDATE users SET password = '$data[password]' WHERE email = '$data[userid]'";
        $this->lismdb->query($sql);
    }
    
    public function deleteUser($userid){
        $sql = "DELETE FROM $this->table WHERE userid='$userid'";
        $this->lisdb->query($sql);
    }
    
    public function completeUserRemoval($userid){
        // change status to past so that user is not displayed
        $sql = "UPDATE $this->table SET status = 'past' WHERE userid = '$userid'";
        $this->lisdb->query($sql);

        // update the global users table if the users_id which should be an email, is in there already
        $sql = "DELETE FROM users WHERE email = '$userid'";
        $this->lismdb->query($sql);
    }
    
    public function getGroupUsers($groupname){
	$table = $groupname.'_users';
	$userList = $this->lisdb->get($table)->result_array();
	return $userList;
    }
    
}