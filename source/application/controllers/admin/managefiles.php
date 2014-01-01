<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Managefiles extends Admin_Controller {
    
    private $userobj = null;
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');
	$this->load->model('admin_filemanager');
	$this->restrict_access();
    }
    
    public function index(){
	$data['page_title'] = 'MyLIS File Manager';
	if(isset($_GET['message'])) {
	    $data['message'] = $this->input->get('message');
	}
	$data['fileList'] = $this->admin_filemanager->getFileList();
	$data['trashList'] = $this->admin_filemanager->getTrashFiles();
	
	$this->load_view('admin/managefiles/main',$data);
    }
    
    public function view_log(){
	$data['page_title'] = 'Code Manager Log';
	$userList = $this->loadUsers();
	$logs = $this->admin_filemanager->getLogs();
	foreach($logs as $key => $log) {
	    $logs[$key]['manager'] = $userList[$log['manager_id']];
	}
	
	$data['logs'] = $logs;
	$this->load_view('admin/managefiles/view_log',$data);
    }
    
    public function remove_logs(){
	$all = $this->input->post('all');
	$entries = $this->input->post('entries');

	if(!empty($all)) {
	    $this->admin_filemanager->remove_all_logs();
	} else if(count($entries) > 0) {
	    $this->admin_filemanager->remove_log_entries($entries);
	}

	redirect('admin/managefiles/view_logs');
    }
    
    // function to update the files
    public function update(){
	$all = $this->input->post('all');
	$files = $this->input->post('files');
	$notes = $this->input->post('notes');

	// get the accounts that should be update
	$account_ids = $this->getAccountIDs();

	// get the modules that should be updated
	if(!empty($all)) {
	    $files = $this->getAllFiles();
	} else if(count($files) == 0) {
	    $data['page_title'] = 'Error!';
	    $data['error'] = "Error, No Files Selected...";
	    $this->load_view('errors/generic_error',$data);
	    return;
	}

	$lis_dir = $this->accounts_dir.$this->properties['lis.default.account'].'/';

	foreach($account_ids as $id) {
	    $acct_dir = $this->accounts_dir.'mylis_'.$id.'/';
	    foreach($files as $file) {
		$lis_file = $lis_dir.$file;
		$acct_file = $acct_dir.$file;
		copy($lis_file, $acct_file) or die("Couldn't copy $lis_file to $acct_file : $php_errormsg");
	    }
	}

	// add a log entry
	if(trim($_POST['accounts']) == 'ALL') {
	    $account_ids = array();
	    $account_ids[] = 'ALL';
	}
	
	$manager_id = $this->userobj->userid;
	$this->admin_filemanager->addLog($account_ids, $files, 'files', $notes,$manager_id);

	// redirect to the main page
	$message = 'All Accounts Updated...';
	redirect('admin/managefiles?message='.$message);
    }

    // function to rmove files in the trash dir
    public function remove(){
	$all = $this->input->post('all');
	$files = $this->input->post('files');

	if(!empty($all)) {
	    $files = $this->getAllTrashFiles();
	}
	else if(count($files) == 0) {
	    $data['page_title'] = 'Error!';
	    $data['error'] = "Error, No Files Selected...";
	    $this->load_view('errors/generic_error',$data);
	    return;
	}

	foreach($files as $file) {
	    $full_name = $this->trash_dir.$file;

	    if(is_dir($full_name)) {
		$this->admin_filemanager->delDir($full_name);
	    }
	    else {
		unlink($full_name);
	    }
	}

	redirect('admin/managefiles');
    }
   
    // get the accounts that should be updated
    function getAccountIDs() {
	$accounts = $this->input->post('accounts');
	$account_ids = array();

	if($accounts == 'ALL') {
	    $idList = $this->admin_filemanager->getAccountIDs();

	    if(count($idList) > 0) {
		foreach($idList as $array) {
		    $account_ids[] = $array['account_id'];
		}
	    }
	} else {
	    $sa  = explode(';', $accounts);
	    foreach($sa as $id) {
		$account_ids[] = trim($id);
	    }
	}

	return $account_ids;
    }
    
    // Method to get the names of the files
    /* Must figure what this does?*/
    function getAllFiles() {
	$f_list = $this->admin_filemanager->getFileList();
	$files = array();
	foreach($f_list as $file) {
	    $sa  = explode(';', $file);
	    $files[] = $sa[0];
	}

	return $files;
    }
    
    // function to get all trash files
    function getAllTrashFiles() {
	$f_list = $this->admin_filemanager->getTrashFiles();

	$files = array();
	foreach($f_list as $file) {
	    $sa  = explode(';', $file);
	    $files[] = $sa[0];
	}

	return $files;
    }
    
}