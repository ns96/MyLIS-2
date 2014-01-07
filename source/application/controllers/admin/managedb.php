<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Managedb extends Admin_Controller {
    
    private $userobj = null;
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');
	$this->restrict_access();
    }
    
    public function index(){
	
	$this->load->model('managedb_model');
	$databaseList = $this->managedb_model->current_databases();
	
	
	$data['page_title'] = "Manage DB";
	$data['home_link'] = encodeUrl($this->properties['home.url']);
	$data['databases'] = $databaseList;
	$this->load_view('admin/managedb/main',$data);
    }
    
    public function info($dbname){
	$this->load->model('managedb_model');
	$tables = $this->managedb_model->get_db_tables($dbname);
	
	$data['page_title'] = "DB Info ($dbname)";
	$data['db'] = $dbname;
	$data['tables'] = $tables;
	$this->load_view('admin/managedb/info',$data);
    }
    
    public function create(){
	$db = 'mylis0_'.$this->input->post('db');
	$tables = $this->input->post('tables');

	// load the names of the databases that are already in the database
	$dbnames = array();

	$this->load->model('managedb_model');
	$databaseList = $this->managedb_model->current_databases();

	foreach($databaseList as $array) {
	    $dbname = $array['Database'];
	    $dbnames[$dbname] = "db...";
	}

	// check to see if the dn doesn't already exist
	if(isset($dbnames[$db]) && $tables == 'yes') {
	    $this->create_tables($db);
	} else if(!isset($dbnames[$db])){ // create database
	    $this->managedb_model->create_db($db);

	    if($tables == 'yes') {
		$this->create_tables($db);
	    }
	}
	redirect('admin/managedb');
    }
    
    // function to add tables to the database
    public function create_tables($db) {
	$this->initialize_table_names();

	if(strstr($db, 'LISMDB')) {
	    $this->managedb_model->create_lismdb_tables($db);
	}
	else if(strstr($db, 'LISPDB')) {
	    $this->managedb_model->create_lispdb_tables($db);
	}
	else if(strstr($db, 'LISSDB')) {
	    $this->managedb_model->create_lissdb_tables($db);
	}
	else if(strstr($db, 'LISDB')) {
	    $this->managedb_model->create_lisdb_tables($db);
	}
    }
    
    public function account($account_id){
	$this->load->model('managedb_model');
	$data['statusInfo'] = $this->managedb_model->get_table_status($account_id);
	
	$data['page_title'] = "DB Info ($account_id)" ;
	$data['account_id'] = $account_id;
	$this->load_view('admin/managedb/account_db',$data);
    }
    
    
    
}
