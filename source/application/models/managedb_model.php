<?php

class Managedb_model extends CI_Model {
    
    var $lisdb = null;
    var $lismdb = null;
    var $lispdb = null;
    var $lissdb = null;
    
    
    public function __construct() {
	parent::__construct();
    }
    
    public function current_databases(){
	$sql = "SHOW DATABASES";
	$databases = $this->lisdb->query($sql)->result_array();
	return $databases;
    }
    
    public function get_db_tables($db){
	$sql = "SHOW TABLE STATUS FROM $db";
	$tables = $this->lisdb->query($sql)->result_array();
	return $tables;
    }
    
    public function get_table_status($account_id){
	$this->lisdb = $this->load->database('lisdb',TRUE);
	$sql = "SHOW TABLE STATUS LIKE '".$account_id."_%'";
	$statusInfo = $this->lisdb->query($sql)->result_array();
	return $statusInfo;
    }
    
    public function create_db($db){
	$sql = "CREATE DATABASE $db";
	$this->lisdb->query($sql);
    }
    
    public function create_lisdb_tables($db){
	$this->initializeTableNames();
	$aname = 'test_';  // account name that is appended to the table
	foreach ($this->lis_tables as $key => $value) {
	    if($key != 'lismessages') {
		$this->managedb_model->create_table(2,$db,$key,$value,$aname);
	    }
	}
	// create the account wide tables "lismessages, etc..."
	$this->managedb_model->create_table(3,$db,null,$this->lis_tables['lismessages']);
    }
    
    public function create_lismdb_tables($db){
	$this->initializeTableNames();
	foreach ($this->lism_tables as $key => $value) {
	    $this->managedb_model->create_table(1,$db,$key,$value);
	}
    }
    
    public function create_lispdb_tables($db){
	$this->initializeTableNames();
	foreach ($this->lisp_tables as $key => $value) {
	    $this->managedb_model->create_table(1,$db,$key,$value);
	}
    }
    
    public function create_lissdb_tables($db){
	$this->initializeTableNames();
	foreach ($this->liss_tables as $key => $value) {
	    $this->managedb_model->create_table(1,$db,$key,$value);
	}
    }
    
    public function create_table($mode,$db,$key,$value=null,$account=null){
	
	switch($db){
	    case 'LISMDB':
		$this->database = $this->load->database('lismdb',TRUE);
		break;
	    case 'LISPDB':
		$this->database = $this->load->database('lispdb',TRUE);
		break;
	    case 'LISSDB':
		$this->database = $this->load->database('lissdb',TRUE);
		break;
	    case 'LISDB':
		$this->database = $this->load->database('lisdb',TRUE);
		break;
	}
	
	switch($mode){
	    case 1:
		$sql = "CREATE TABLE IF NOT EXISTS $key ($value)";
		$this->database->query($sql);
		break;
	    case 2:
		$sql  = 'CREATE TABLE IF NOT EXISTS '.$account."$key ($value)";
		$this->database->query($sql);
		break;
	    case 3:
		$sql  = 'CREATE TABLE IF NOT EXISTS lismessages ('.$value.')';
		$this->database->query($sql);
		break;
	}
	
    }
    
    // function to return a property for a particular account. usefull for getting a variable before account is deleted
    function getMyLISProperty($account_id, $key_id) {
	$table = $account_id.'_properties';
	$sql = "SELECT value FROM $table WHERE key_id = '$key_id'";
	$records = $this->lisdb->query($sql)->result_array();

	return $records[0]['value'];
    }
    
    // function to create the tables for a particular account
    function createMyLISTables($account_id) {
	$this->initializeTableNames();

	foreach ($this->lis_tables as $key => $value) {
	    if($key != 'lismessages') {
		$sql  = 'CREATE TABLE IF NOT EXISTS '.$account_id."_$key ($value)";
		$this->lisdb->query($sql);
	    }
	}
    }
    
    // function to initialize the table names
    function initializeTableNames() {
	// the lis administrator tables
	$this->lism_tables = array(
	'accounts' =>
	'account_id VARCHAR(25) NOT NULL PRIMARY KEY,
	pi_fname VARCHAR(25) NOT NULL,
	pi_mi VARCHAR(2),
	pi_lname VARCHAR(25) NOT NULL,
	group_name VARCHAR(50) NOT NULL,
	group_type VARCHAR(50) NOT NULL,
	discipline VARCHAR(50) NOT NULL,
	institution VARCHAR(50) NOT NULL,
	address VARCHAR(255) NOT NULL,
	phone VARCHAR(25) NOT NULL,
	fax VARCHAR(25) NOT NULL,
	email VARCHAR(50) NOT NULL,
	term TINYINT NOT NULL,
	cost DECIMAL(7,2) NOT NULL,
	activate_date VARCHAR(15) NOT NULL,
	expire_date VARCHAR(15) NOT NULL,
	status ENUM(\'demo\',  \'trial\', \'active\', \'premium\', \'suspended\') NOT NULL,
	storage SMALLINT NOT NULL,
	max_users TINYINT NOT NULL,
	time_zone VARCHAR(3),
	activate_code VARCHAR(25),
	notes TEXT,
	department_id VARCHAR(25),
	network_ids VARCHAR(1024) NOT NULL,
	manager_id VARCHAR(25) NOT NULL',

	'departments' =>
	'department_id VARCHAR(25) PRIMARY KEY,
	institution VARCHAR(50) NOT NULL,
	address VARCHAR(255) NOT NULL,
	phone VARCHAR(25) NOT NULL,
	fax VARCHAR(25) NOT NULL,
	email VARCHAR(50) NOT NULL,
	cost DECIMAL(7,2) NOT NULL,
	activate_date VARCHAR(15) NOT NULL,
	expire_date VARCHAR(15) NOT NULL,
	total_accounts INT NOT NULL,
	notes TEXT NOT NULL,
	manager_id VARCHAR(25) NOT NULL',

	'update_log' =>
	'update_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	date VARCHAR(15) NOT NULL,
	type VARCHAR(20) NOT NULL,
	account TEXT NOT NULL,
	notes TEXT NOT NULL,
	manager_id VARCHAR(25) NOT NULL',

	'sales' =>
	'sales_id SMALLINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	date VARCHAR(25) NOT NULL,
	type VARCHAR(50) NOT NULL,
	cost TEXT NOT NULL,
	account_id VARCHAR(25) NOT NULL,
	name  VARCHAR(100) NOT NULL,
	pi_name  VARCHAR(100) NOT NULL,
	email VARCHAR(50) NOT NULL,
	pi_email VARCHAR(100) NOT NULL,
	phone VARCHAR(25) NOT NULL,
	po_number VARCHAR(25) NOT NULL,
	billing_address VARCHAR(255) NOT NULL,
	userid VARCHAR(25) NOT NULL,
	status ENUM(\'pending\', \'paid\', \'cancelled\') NOT NULL,
	status_date VARCHAR(25) NOT NULL,
	notes TEXT NOT NULL,
	manager_id VARCHAR(25)',

	'managers' =>
	'manager_id VARCHAR(25) NOT NULL PRIMARY KEY,
	f_name VARCHAR(25) NOT NULL,
	l_name VARCHAR(25) NOT NULL,
	address VARCHAR(255) NOT NULL,
	email VARCHAR(50) NOT NULL,
	phone VARCHAR(25) NOT NULL',

	'users' =>
	'email VARCHAR(50) NOT NULL PRIMARY KEY,
	account_id VARCHAR(25) NOT NULL,
	password VARCHAR(25) NOT NULL',

	'properties' =>
	'property VARCHAR(100) NOT NULL PRIMARY KEY,
	value VARCHAR(50) NOT NULL,
	account_ids VARCHAR(1000) NOT NULL,
	date VARCHAR(25) NOT NULL'
	);

	// this is the group profiles tables
	$this->lisp_tables = array(
	'profiles' =>
	'profile_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	account_id VARCHAR(25) NOT NULL,
	pi_name VARCHAR(50) NOT NULL,
	pi_email  VARCHAR(50) NOT NULL,
	pi_phone VARCHAR(25) NOT NULL,
	group_type VARCHAR(50) NOT NULL,
	institution VARCHAR(50) NOT NULL,
	address VARCHAR(255) NOT NULL,
	discipline VARCHAR(50) NOT NULL,
	keywords VARCHAR(1000) NOT NULL,
	description TEXT NOT NULL,
	instruments TEXT NOT NULL,
	url VARCHAR(255) NOT NULL,
	collaborator_ids VARCHAR(1024) NOT NULL,
	public ENUM(\'YES\',  \'NO\'),
	edit_date VARCHAR(25),
	userid VARCHAR(50)',

	'collaborators' =>
	'collaborator_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	profile_id INT,
	group_pi VARCHAR(50) NOT NULL,
	group_name VARCHAR(50) NOT NULL,
	institution VARCHAR(50) NOT NULL,
	discipline VARCHAR(50) NOT NULL,
	url VARCHAR(255) NOT NULL,
	projects TEXT',
	);

	// this is the service request tables
	$this->liss_tables = array(
	'service_request' => 
	'request_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(50) NOT NULL,
	email  VARCHAR(50) NOT NULL,
	description TEXT NOT NULL,
	url VARCHAR(255)',

	'rating' =>
	'rating_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	profile_id INT NOT NULL,
	rating TINYINT NOT NULL,
	notes TEXT'
	);

	// the lis account tables
	$this->lis_tables = array(
	'chemicals' =>
	'chem_id SMALLINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	cas VARCHAR(25) NOT NULL,
	name VARCHAR(100) NOT NULL,
	company VARCHAR(50) NOT NULL,
	product_id VARCHAR(50) NOT NULL,
	amount VARCHAR(25) NOT NULL,
	units VARCHAR(25) NOT NULL,
	entry_date VARCHAR(25) NOT NULL,
	status VARCHAR(25) NOT NULL,
	status_date VARCHAR(25) NOT NULL,
	mfmw VARCHAR(50),
	category VARCHAR(50) NOT NULL,
	location_id VARCHAR(15) NOT NULL,
	notes VARCHAR(255) NOT NULL,
	owner VARCHAR(50) NOT NULL,
	userid VARCHAR(50) NOT NULL',
	//groupid VARCHAR(50),
	//barcode VARCHAR(128)',

	'supplies' =>
	'item_id SMALLINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	model VARCHAR(25) NOT NULL,
	name VARCHAR(100) NOT NULL,
	company VARCHAR(50) NOT NULL,
	product_id VARCHAR(50) NOT NULL,
	amount VARCHAR(25) NOT NULL,
	units VARCHAR(25) NOT NULL,
	entry_date VARCHAR(25) NOT NULL,
	status VARCHAR(25) NOT NULL,
	status_date VARCHAR(25) NOT NULL,
	sn VARCHAR(50),
	category VARCHAR(50) NOT NULL,
	location_id VARCHAR(15) NOT NULL,
	notes VARCHAR(255) NOT NULL,
	owner VARCHAR(50) NOT NULL,
	userid VARCHAR(50) NOT NULL',
	//groupid VARCHAR(50),
	//barcode VARCHAR(128)',

	'messages' =>
	'message_id SMALLINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	date VARCHAR(25) NOT NULL,
	title VARCHAR(100) NOT NULL,
	message TEXT NOT NULL,
	url VARCHAR(255),
	file_id VARCHAR(25),
	userid VARCHAR(50) NOT NULL',

	'lismessages' =>
	'message_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	message_date VARCHAR(25) NOT NULL,
	post_start VARCHAR(25),
	post_end VARCHAR(25),
	account_ids TEXT NOT NULL,
	message TEXT NOT NULL,
	url VARCHAR(255),
	managerid VARCHAR(50)',

	'gmdates' =>
	'gmdate_id SMALLINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	semester_id TINYINT NOT NULL,
	gmdate DATE NOT NULL,
	gmtime VARCHAR(15) NOT NULL,
	userid VARCHAR(50) NOT NULL',

	'gmslots' =>
	'slot_id SMALLINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	gmdate_id INT NOT NULL,
	type VARCHAR(25) NOT NULL,
	presenter VARCHAR(50) NOT NULL,
	title VARCHAR(255) NOT NULL,
	file_id VARCHAR(25),
	modify_date VARCHAR(25) NOT NULL,
	userid VARCHAR(50) NOT NULL',

	'orders' =>
	'order_id SMALLINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	company VARCHAR(50) NOT NULL,
	ponum VARCHAR(25) NOT NULL,
	conum VARCHAR(25) NOT NULL,
	priority VARCHAR(25) NOT NULL,
	account VARCHAR(25),
	order_date VARCHAR(25) NOT NULL,
	status VARCHAR(25) NOT NULL,
	status_date VARCHAR(25) NOT NULL,
	g_expense DECIMAL(10,2) NOT NULL,
	p_expense DECIMAL(10,2) NOT NULL,
	s_expense DECIMAL(10,2) NOT NULL,
	t_expense DECIMAL(10,2) NOT NULL,
	notes VARCHAR (255) NOT NULL,
	owner VARCHAR(50) NOT NULL,
	userid VARCHAR(50) NOT NULL,
	maxitems SMALLINT DEFAULT 10',

	'order_items' =>
	'item_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	order_id SMALLINT NOT NULL, 
	stock_id SMALLINT,
	item_num SMALLINT UNSIGNED NOT NULL,
	type VARCHAR(25) NOT NULL,
	company VARCHAR(50) NOT NULL,
	product_id VARCHAR(25) NOT NULL,
	description VARCHAR(100) NOT NULL,
	amount VARCHAR(25),
	units VARCHAR(25) NOT NULL,
	price DECIMAL(10,2) NOT NULL,
	status VARCHAR(25) NOT NULL,
	status_date VARCHAR(25) NOT NULL,
	owner VARCHAR(50) NOT NULL,
	userid VARCHAR(50) NOT NULL',

	'doclibrary' =>
	'library_id SMALLINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	format VARCHAR(25) NOT NULL,
	title VARCHAR(100) NOT NULL,
	description TEXT NOT NULL,
	url VARCHAR(255),
	file_id VARCHAR(25),
	category_id SMALLINT NOT NULL,
	status VARCHAR(25) NOT NULL,
	status_userid VARCHAR(25) NOT NULL,
	status_date VARCHAR(25) NOT NULL,
	group_edit ENUM(\'YES\',  \'NO\'),
	userid VARCHAR(50) NOT NULL',

	'weblinks' =>
	'link_id SMALLINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	title VARCHAR(255) NOT NULL,
	url VARCHAR(255) NOT NULL,
	category_id SMALLINT NOT NULL,
	userid VARCHAR(50) NOT NULL',

	'publications' =>
	'publication_id SMALLINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	title VARCHAR(255) NOT NULL,
	authors VARCHAR(1000) NOT NULL,
	type VARCHAR(25) NOT NULL,
	status VARCHAR(25) NOT NULL,
	start_date VARCHAR(25) NOT NULL,
	modify_date VARCHAR(25) NOT NULL,
	end_date VARCHAR(25) NOT NULL,
	abstract TEXT,
	comments TEXT,
	file_ids VARCHAR(1000),
	userid VARCHAR(50) NOT NULL',

	'users' =>
	'userid VARCHAR(50) NOT NULL PRIMARY KEY,
	password VARCHAR(25) NOT NULL,
	role VARCHAR(100) NOT NULL,
	name VARCHAR(100) NOT NULL,
	email VARCHAR(50) NOT NULL,
	status VARCHAR(50) NOT NULL,
	info TEXT',
	//groupid VARCHAR(50)',

	'locations' =>
	'location_id VARCHAR(25) NOT NULL PRIMARY KEY,
	room VARCHAR(25) NOT NULL,
	description VARCHAR(100) NOT NULL,
	owner VARCHAR(50) NOT NULL,
	userid VARCHAR(50) NOT NULL',

	'categories' =>
	'category_id SMALLINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	table_name VARCHAR(50) NOT NULL,
	type VARCHAR(25) NOT NULL,
	value VARCHAR(50) NOT NULL,
	userid VARCHAR(50) NOT NULL',

	'log' =>
	'log_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	datetime VARCHAR(50) NOT NULL,
	section VARCHAR(100) NOT NULL,
	userid VARCHAR(50) NOT NULL',

	'instrulog' =>
	'instrulog_id SMALLINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	instrument VARCHAR(50) NOT NULL,
	manager_id VARCHAR(50) NOT NULL,
	file_ids VARCHAR(255),
	notes TEXT,
	userid VARCHAR(50) NOT NULL',

	'reservations' =>
	'reservation_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	type VARCHAR(50) NOT NULL,
	table_id SMALLINT NOT NULL,
	date VARCHAR(25) NOT NULL,
	hour TINYINT NOT NULL,
	min TINYINT NOT NULL,
	note VARCHAR(255),
	userid VARCHAR(50) NOT NULL',

	'grouptask' =>
	'grouptask_id SMALLINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	task_name VARCHAR(50) NOT NULL,
	type VARCHAR(50) NOT NULL,
	year VARCHAR(4) NOT NULL,
	manager_id VARCHAR(50) NOT NULL,
	notes TEXT,
	userid VARCHAR(50) NOT NULL',

	'grouptask_item' =>
	'item_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	grouptask_id SMALLINT NOT NULL,
	item_num TINYINT,
	item_week TINYINT,
	item_month TINYINT,
	completed VARCHAR(3),
	note VARCHAR(255),
	userid VARCHAR(50) NOT NULL',

	'folder_files' =>
	'file_id SMALLINT NOT NULL PRIMARY KEY,
	title VARCHAR(100) NOT NULL,
	category_id SMALLINT NOT NULL,
	userid VARCHAR(50) NOT NULL',

	'files' =>
	'file_id SMALLINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	type VARCHAR(25) NOT NULL,
	description VARCHAR(255),
	url VARCHAR(255),
	table_name VARCHAR(50) NOT NULL,
	table_key VARCHAR(50) NOT NULL,
	size INT NOT NULL,
	owner  VARCHAR(50) NOT NULL,
	userid VARCHAR(50) NOT NULL',

	'properties' =>
	'key_id VARCHAR(255) PRIMARY KEY,
	value VARCHAR(255) NOT NULL,
	userid VARCHAR(50) NOT NULL'
	);
    }
    
}
