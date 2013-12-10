<?php

class Account_model extends CI_Model {
    
    var $lisdb = null;
    
    public function __construct() {
	parent::__construct();
	$this->lismdb = $this->load->database('lismdb',TRUE);
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
    
}