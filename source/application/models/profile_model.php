<?php

class Profile_model extends CI_Model {
    
    var $lisdb = null;
    var $lismdb = null;
    var $lispdb = null;
    var $table = null;
    //var $utable = null;
    var $gtable = null;
    
    public function __construct() {
	parent::__construct();
	$this->lisdb = $this->load->database('lisdb',TRUE);
	$this->lismdb = $this->load->database('lismdb',TRUE);
	$this->lispdb = $this->load->database('lispdb',TRUE);
	$this->table = $this->session->userdata('group')."_users";
	//$this->utable = $this->session->userdata('group')."_profiles";
	$this->gtable = 'profiles';
    }
    
    // Updates the user profile
    public function update($profile){
	// update the users database table now
	$sql = "UPDATE $this->table SET password = '$profile[password]', name = '$profile[name]', email = '$profile[email]', info = '$profile[info]' WHERE userid = '$profile[userid]'";
	$this->lisdb->query($sql);

	// update the global users table if the users_id which should be an email, is in there already
	$sql = "UPDATE users SET password = '$profile[password]' WHERE email = '$profile[userid]'";
	$this->lismdb->query($sql);

    }
    
    public function get_group_profile($group){
	$array = null;
	$sql = "SELECT * FROM profiles WHERE account_id = '$group'";
	$records = $this->lispdb->query($sql)->result_array();

	return $records[0];
    }
    
    public function update_group($profile){
	// update the database now
	$sql = "UPDATE $this->gtable SET pi_name = '$profile[pi_name]', pi_email = '$profile[pi_email]', url = '$profile[url]', keywords = '$profile[keywords]', description = '$profile[description]', instruments = '$profile[instruments]', edit_date = '$profile[edit_date]', userid='$profile[userid]' WHERE account_id = '$profile[group]'";
	$this->lispdb->query($sql);
    }
    
}