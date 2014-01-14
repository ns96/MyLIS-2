<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Handles the administrative functionality that is related to 
 * displaying information about or making changes to database structure.
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis
 */
class Managedb extends Admin_Controller {
    
    private $userobj = null;
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');
	$this->restrict_access();
    }
    
    /**
     * Displays a list of MySQL databases and allows the admin to create
     * the MyLIS databases.
     */
    public function index(){
	
	$this->load->model('managedb_model');
	$databaseList = $this->managedb_model->current_databases();
	$db_state = $this->managedb_model->get_db_state();
	$data['page_title'] = "Manage DB";
	$data['home_link'] = encodeUrl($this->properties['home.url']);
	$data['databases'] = $databaseList;
        $data['db_state'] = $db_state;
	$this->load_view('admin/managedb/main',$data);
    }
    
    /**
     * Displays information about a certain MySQL database.
     * 
     * @param string $dbname
     */
    public function info($dbname){
	$this->load->model('managedb_model');
	$tables = $this->managedb_model->get_db_tables($dbname);
	
	$data['page_title'] = "DB Info ($dbname)";
	$data['db'] = $dbname;
	$data['tables'] = $tables;
	$this->load_view('admin/managedb/info',$data);
    }
    
    /**
     * Manages the creation of a certain type MyLIS database
     */
    public function create(){
	$db = $this->input->post('db');
        $full_db_name = 'mylis0_'.$db;
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
        $method_name = "create_".$db."_tables";
	if(isset($dbnames[$full_db_name]) && $tables == 'yes') {
	    $this->managedb_model->$method_name();
	} else if(!isset($dbnames[$full_db_name])){ // create database
	    
	    $this->managedb_model->create_db($db);

	    if($tables == 'yes') {
                $this->managedb_model->$method_name();
	    }
	}
	redirect('admin/managedb');
    }
    
    /**
     * Adds tables to a certain MyLIS database
     * 
     * @param string $db The database id
     */
    public function create_tables($db) {
	$this->managedb_model->initialize_table_names();

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
    
    /**
     * Displays information about the database tables related to a specific account
     * 
     * @param string $account_id
     */
    public function account($account_id){
	$this->load->model('managedb_model');
	$data['statusInfo'] = $this->managedb_model->get_table_status($account_id);
	
	$data['page_title'] = "DB Info ($account_id)" ;
	$data['account_id'] = $account_id;
	$this->load_view('admin/managedb/account_db',$data);
    }
    
    
    
}
