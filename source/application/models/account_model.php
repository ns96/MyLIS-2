<?php

class Account_model extends CI_Model {
    
    var $lisdb = null;
    var $lismdb = null;
    
    public function __construct() {
	parent::__construct();
	$this->lismdb = $this->load->database('lismdb',TRUE);
        $this->lisdb = $this->load->database('lisdb',TRUE);
    }
    
    // function to return information about the database
    public function getAccountInfo($account_id){
        $array = null;
        $sql = "SELECT * FROM accounts WHERE account_id = '$account_id'";
        $records = $this->lismdb->query($sql)->result_array();

        return $records[0];
    }
    
    public function update_sales($data){
        $sql = "INSERT INTO sales VALUES('', '$data[date]', '$data[sale_type]', '$data[cost]', '$data[account_id]', '$data[name]', 
        '$data[pi_name]', '$data[email]', '$data[pi_email]', '$data[phone]', '$data[po_number]', '$data[address]', '$data[userid]',  'pending', '$data[date]', '', '')";
        $this->lismdb->query($sql);
        return $this->lismdb->insert_id();
    }
    
    public function upgrade_account($data){
        $sql = "UPDATE accounts SET cost = '$data[cost]', expire_date = '$data[expire_date]',
                status = '$data[status]', storage = '$data[storage]' WHERE account_id = '$data[account_id]'";
        $this->lismdb->query($sql);
    }
    
    public function update_account_info($data){
        // update the database now
        $sql = "UPDATE accounts SET pi_fname = '$fname', pi_mi = '$mi',pi_lname = '$lname', group_name = '$group_name', group_type = '$group_type',discipline = '$discipline',
        institution = '$institution', address = '$address', phone = '$phone', fax = '$fax', email = '$email' WHERE account_id = '$account_id'";
        $this->lismdb->query($sql);

        // update the profile database
        mysql_select_db($this->properties['lisprofile.database']) or die(mysql_error());
        $sql = "UPDATE profiles SET pi_name = '$group_pi', pi_email = '$email', pi_phone = '$phone', group_type = '$group_type',institution = '$institution', 
        address = '$address', discipline = '$discipline' WHERE account_id = '$account_id'";
        $this->lisdb->query($sql);
    }
    
}