<?php

/**
 * Manages the information related to group user information.
 * 
 * Used only by group controllers
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis 
 */
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
    
    public function get_user($userid){
	$sql = "SELECT * FROM $this->table WHERE userid = '$userid'";
	$records = $this->lisdb->query($sql)->result_array();
	
	if (count($records)>0){	    
	    $userdata['userid'] = $records[0]['userid'];
	    $userdata['password'] = '';
	    $userdata['role'] = $records[0]['role'];
	    $userdata['name'] = $records[0]['name'];
	    $userdata['email'] = $records[0]['email'];
	    $userdata['status'] = $records[0]['status'];
	    $userdata['info'] = $records[0]['info'];
	    
	    $user = new User($userdata);
	} else {
	    $user = null;
	}

	return $user;
    }
    
    public function get_user_password($userid){
	$this->lisdb->where('userid',$userid);
	$record = $this->lisdb->get($this->table)->result_array();
	
	if (count($record)>0){	
	    $password = $record[0]['password'];
	} else {
	    $password = null;
	}
	return $password;
    }
    
    public function add_user($data){
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
    
    public function delete_user($userid){
        $sql = "DELETE FROM $this->table WHERE userid='$userid'";
        $this->lisdb->query($sql);
    }
    
    public function complete_user_removal($userid){
        // change status to past so that user is not displayed
        $sql = "UPDATE $this->table SET status = 'past' WHERE userid = '$userid'";
        $this->lisdb->query($sql);

        // update the global users table if the users_id which should be an email, is in there already
        $sql = "DELETE FROM users WHERE email = '$userid'";
        $this->lismdb->query($sql);
    }
    
    public function get_group_users($groupname){
	$table = $groupname.'_users';
	$userList = $this->lisdb->get($table)->result_array();
	return $userList;
    }
    
}