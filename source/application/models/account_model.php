<?php

class Account_model extends CI_Model {
    
    var $lisdb = null;
    var $lismdb = null;
    var $lispdb = null;
    
    public function __construct() {
	parent::__construct();
	$this->lismdb = $this->load->database('lismdb',TRUE);
        $this->lisdb = $this->load->database('lisdb',TRUE);
	$this->lispdb = $this->load->database('lispdb',TRUE);
    }
    
    public function getAllAccounts(){
	$sql = "SELECT * FROM accounts";
	$records = $this->lismdb->query($sql)->result_array();
	return $records; 
    }
    
    // function to return information about the database
    public function getAccountInfo($account_id){
        $array = null;
        $sql = "SELECT * FROM accounts WHERE account_id = '$account_id'";
        $records = $this->lismdb->query($sql)->result_array();

        return $records[0];
    }
    
    // checks to see if an account already exist
    public function accountExists($account_id){
	$exists = 0;
    
	$sql = "SELECT * FROM accounts WHERE account_id = '$account_id'";
	$records = $this->lismdb->query($sql)->result_array();

	if(count($records) >= 1) {
	    $exists = 1;
	}
	return $exists;
    } 
    
    public function getAccountUsers($account_id){
	$sql = "SELECT * FROM users WHERE account_id = '$account_id'";
	$records = $this->lismdb->query($sql)->result_array();
	return $records;
    }
    
    public function getAccountProfile($account_id){
	$sql = "SELECT * FROM profiles WHERE account_id = '$account_id'";
	$records = $this->lispdb->query($sql)->result_array();
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
        $sql = "UPDATE accounts SET pi_fname = '$data[fname]', pi_mi = '$data[mi]',pi_lname = '$data[lname]', group_name = '$data[group_name]', group_type = '$data[group_type]',discipline = '$data[discipline]',
        institution = '$data[institution]', address = '$data[address]', phone = '$data[phone]', fax = '$data[fax]', email = '$data[email]' WHERE account_id = '$data[account_id]'";
        $this->lismdb->query($sql);

        // update the profile database
        $sql = "UPDATE profiles SET pi_name = '$data[group_pi]', pi_email = '$data[email]', pi_phone = '$data[phone]', group_type = '$data[group_type]',institution = '$data[institution]', 
        address = '$data[address]', discipline = '$data[discipline]' WHERE account_id = '$data[account_id]'";
        $this->lispdb->query($sql);
    }
    
    public function update_account_info_by_admin($data){
        // update the database now
        $sql = "UPDATE accounts SET pi_fname = '$data[fname]', pi_mi = '$data[mi]',pi_lname = '$data[lname]', group_name = '$data[group_name]', group_type = '$data[group_type]',discipline = '$data[discipline]',
        institution = '$data[institution]', address = '$data[address]', phone = '$data[phone]', fax = '$data[fax]', email = '$data[email]', cost = '$data[cost]', expire_date = '$data[expire_date]', activate_code = '$data[activate_code]', notes = '$data[notes]', manager_id = '$data[manager_id]' WHERE account_id = '$data[account_id]'";
        $this->lismdb->query($sql);

        // update the profile database
        $sql = "UPDATE profiles SET pi_name = '$data[pi_name]', pi_email = '$data[email]', pi_phone = '$data[phone]', group_type = '$data[group_type]',institution = '$data[institution]', 
        address = '$data[address]', discipline = '$data[discipline]', keywords = '$data[keywords]', description = '$data[description]', instruments = '$data[instruments]', url = '$data[piurl]', collaborator_ids = '$data[collaborator_ids]', public = '$data[public]' WHERE account_id = '$data[account_id]'";
        $this->lispdb->query($sql);
    }
    
    public function renew_account($expire_date,$term,$account_id){
	$sql = "UPDATE accounts set expire_date = '$expire_date', term = '$term' WHERE account_id = '$account_id'";
	$this->lismdb->query($sql);
    }
    
    public function add_account($data){
	$sql = "INSERT INTO accounts VALUES('$account_id', '$fname', '$mi', '$lname', '$group_name', '$group_type', 
	'$discipline', '$institution', '$address', '$phone', '$fax', '$email', '$term', 
	'$cost', '$activate_date', '$expire_date', '$status', '$storage', '$max_users', '$time_zone', 
	'$activate_code', '$notes', '$department_id', '$network_ids', $manager_id')";
	$this->lismdb->query($sql);

	$sql = "INSERT INTO users VALUES('$email', '$account_id', '$password1')";
	$this->lismdb->query($sql);

	// now insert info into research profile database
	mysql_select_db($this->properties['lisprofile.database']) or die(mysql_error());
	$sql = "INSERT INTO profiles VALUES('', '$account_id', '$group_pi', '$email', '$phone', '$group_type', '$institution', 
	'$address', '$discipline', '$keywords', '$description', 'List Instruments',  '$piurl', 
	'List Colloborators IDs', 'YES', '$activate_date', '$email')";
	$this->lismdb->query($sql);
    }
    
    public function add_account_admin($data){
	$table = $account_id.'_users';
	$sql = "INSERT INTO $table VALUES('$email', '$password1', 'admin', '$group_pi', '$email', 'Group PI', 'none')";
	$this->lisdb->query($sql);
    }
    
    // function to set default locations and categories
    function setDefaultDatabaseEntries($account_id) {
	$ct_table = $account_id.'_categories';
	$l_table = $account_id.'_locations';
	$c_table = $account_id.'_chemicals';
	$s_table = $account_id.'_supplies';

	// set defualts categories
	$sql = "INSERT INTO $ct_table VALUES ('', '$s_table', 'Supply', 'General', 'myadmin'),
	('', '$s_table', 'Supply', 'Software', 'myadmin'),
	('', '$s_table', 'Supply', 'Equipment', 'myadmin'),
	('', '$s_table', 'Supply', 'Tools', 'myadmin'),
	('', '$c_table', 'Chemical', 'Organic', 'myadmin'),
	('', '$c_table', 'Chemical', 'Inorganic', 'myadmin'),
	('', '$c_table', 'Chemical', 'Acid', 'myadmin'),
	('', '$c_table', 'Chemical', 'Base', 'myadmin'),
	('', '$c_table', 'Chemical', 'Solvent', 'myadmin'),
	('', '$c_table', 'Chemical', 'Flammable', 'myadmin')";
	$this->lisdb->query($sql);

	// set default location table now
	$sql = "INSERT INTO $l_table VALUES ('?', 'Unknown', 'Ask owner of item', 'myadmin', 'myadmin')";
	$this->lisdb->query($sql);
    }
    
    public function setVersionNumber($account_id,$version){
	$table = $account_id.'_properties';
	$sql = "INSERT INTO $table VALUES('version', '$version', 'myadmin')";
	$this->lisdb->query($sql);
    }
    
    // function to remove tables from an account
    public function removeMyLISTables($account_id) {

	// delete this account from the database
	$sql = "DELETE FROM accounts WHERE account_id = '$account_id'";
	$this->lismdb->query($sql);

	// remove the tables from LISM
	$this->loadTableNames();

	foreach ($this->lis_tables as $tableName) {
	    $sql  = 'DROP TABLE IF EXISTS '.$account_id."$tableName";
	    $this->lisdb->query($sql);
	}
    }
    
    // function to remove mylis users from the users table
    function removeMyLISUsers($account_id) {

	// delete this accpunt from the database
	$sql = "DELETE FROM users WHERE account_id = '$account_id'";
	$this->lismdb->query($sql);
    }
    
    function loadTableNames(){
	$this->lis_tables = array(
	    'chemicals',
	    'supplies',
	    'messages',
	    'lismessages',
	    'gmdates',
	    'gmslots',
	    'orders',
	    'order_items',
	    'doclibrary',
	    'weblinks',
	    'publications',
	    'users',
	    'locations',
	    'categories',
	    'log',
	    'instrulog',
	    'reservations',
	    'grouptask',
	    'grouptask_item',
	    'folder_files',
	    'files',
	    'properties'
	);
    }
    
}