<?php

/**
 * Handles the updating of account information.
 * 
 * Used by admin admin and group controllers
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis 
 */
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
    
    public function get_all_accounts(){
	$sql = "SELECT * FROM accounts";
	$records = $this->lismdb->query($sql)->result_array();
	return $records; 
    }
    
    /**
     * Retreives information about an account
     * 
     * @param string $account_id
     * @return array 
     */
    public function get_account_info($account_id){
        $array = null;
        $sql = "SELECT * FROM accounts WHERE account_id = '$account_id'";
        $records = $this->lismdb->query($sql)->result_array();

        return $records[0];
    }
    
    /**
     * Checks to see if an account already exist
     * 
     * @param string $account_id
     * @return int 
     */
    public function account_exists($account_id){
	$exists = 0;
    
	$sql = "SELECT * FROM accounts WHERE account_id = '$account_id'";
	$records = $this->lismdb->query($sql)->result_array();

	if(count($records) >= 1) {
	    $exists = 1;
	}
	return $exists;
    } 
    
    public function get_account_users($account_id){
	$sql = "SELECT * FROM users WHERE account_id = '$account_id'";
	$records = $this->lismdb->query($sql)->result_array();
	return $records;
    }
    
    public function get_account_profile($account_id){
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
    
    public function activate_account($expire_date,$account_id){
	$sql = "UPDATE accounts set expire_date = '$expire_date', status = 'active' WHERE account_id = '$account_id'";
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
	$sql = "INSERT INTO accounts VALUES('$data[account_id]', '$data[fname]', '$data[mi]', '$data[lname]', '$data[group_name]', '$data[group_type]', 
	'$data[discipline]', '$data[institution]', '$data[address]', '$data[phone]', '$data[fax]', '$data[email]', $data[term], 
	$data[cost], '$data[activate_date]', '$data[expire_date]', '$data[status]', $data[storage], $data[max_users], '$data[time_zone]', 
	'$data[activate_code]', '$data[notes]', '$data[department_id]', '$data[network_ids]', '$data[manager_id]')";
	$this->lismdb->query($sql);

	$sql = "INSERT INTO users VALUES('$data[email]', '$data[account_id]', '$data[password1]')";
	$this->lismdb->query($sql);

	// now insert info into research profile database
	$sql = "INSERT INTO profiles VALUES('', '$data[account_id]', '$data[group_pi]', '$data[email]', '$data[phone]', '$data[group_type]', '$data[institution]', 
	'$data[address]', '$data[discipline]', '$data[keywords]', '$data[description]', 'List Instruments',  '$data[piurl]', 
	'List Colloborators IDs', 'YES', '$data[activate_date]', '$data[email]')";
	$this->lispdb->query($sql);
    }
    
    public function add_account_admin($data){
	$table = $data['account_id'].'_users';
	$sql = "INSERT INTO $table VALUES('$data[email]', '$data[password1]', 'admin', '$data[group_pi]', '$data[email]', 'Group PI', 'none')";
	$this->lisdb->query($sql);
    }
    
    /**
     * Sets default locations and categories
     * 
     * @param string $account_id 
     */
    function set_default_database_entries($account_id) {
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
    
    public function set_version_number($account_id,$version){
	$table = $account_id.'_properties';
	$sql = "INSERT INTO $table VALUES('version', '$version', 'myadmin')";
	$this->lisdb->query($sql);
    }
    
    public function set_login_count($account_id,$login_count){
	$table = $account_id.'_properties';
	$sql = "INSERT INTO $table VALUES('login.count', '$login_count', 'myadmin')";
	$this->lisdb->query($sql);
    }
    
    /**
     * Removes tables from an account
     * 
     * @param string $account_id 
     */
    public function remove_MyLIS_tables($account_id) {

	// delete this account from the database
	$sql = "DELETE FROM accounts WHERE account_id = '$account_id'";
	$this->lismdb->query($sql);

	// remove the tables from LISM
	$this->load_table_names();

	foreach ($this->lis_tables as $tableName) {
	    $sql  = 'DROP TABLE IF EXISTS '.$account_id."_$tableName";
	    $this->lisdb->query($sql);
	}
    }
    
    /**
     * Removes mylis users from the users table
     * 
     * @param type $account_id 
     */
    public function remove_MyLIS_users($account_id) {
	// delete this accpunt from the database
	$sql = "DELETE FROM users WHERE account_id = '$account_id'";
	$this->lismdb->query($sql);
    }
    
    protected function load_table_names(){
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