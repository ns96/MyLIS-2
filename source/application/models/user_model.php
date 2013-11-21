<?php

class User_model extends CI_Model {
    
    var $lisdb = null;
    
    public function __construct() {
	parent::__construct();
	$this->lisdb = $this->load->database('lisdb',TRUE);
    }
    
    public function getUser($userid){
	$table = $this->session->userdata('group')."_users";
	$this->lisdb->where('userid',$userid);
	$record = $this->lisdb->get($table)->result_array();
	
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
	$table = $this->session->userdata('group')."_users";
	$lisdb = $this->load->database('lisdb',TRUE);
	$lisdb->where('userid',$userid);
	$record = $lisdb->get($table)->result_array();
	
	if (count($record)>0){	
	    $password = $record[0]['password'];
	} else {
	    $password = null;
	}
	return $password;
    }
    
    public function getGroupUsers($groupname){
	$lisdb = $this->load->database('lisdb',TRUE);
	$table = $groupname.'_users';
	$userList = $lisdb->get($table)->result_array();
	return $userList;
    }
    
}