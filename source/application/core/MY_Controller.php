<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Superclass for all controllers.
class Lis_Controller extends CI_Controller {
    
    public $properties;
    public $lis_tz;
    public $logger;
    
    public function __construct() {
	// We need the User class in order to store and retrieve the 'user' 
	// object to/from the session
	require_once(CIPATH."/application/libraries/User.php");
	
	parent::__construct();
	// Load some helpful functions
	$this->load->helper('lisutils');
	$this->load->helper('myhtml');
	// load the timezones array
	$this->load->library('listimezones');
	$this->lis_tz = $this->listimezones->get_tz();
    }
    
    // Reads configuration data (from ini file) for group or admin account
    protected function loadProperties($conf_location) {
	    $propertiesFile = CIPATH.$conf_location;
	    $properties = parse_ini_file($propertiesFile,false,INI_SCANNER_RAW); // INI_SCANNER_RAW is used in order not
										// to convert 'true' and 'false' values to 1 and o
            /**Storage Cost Per Year Information */
            $properties['storage.cost.200MB'] = 100.0;
            $properties['storage.cost.1000MB'] = 300.0;
            $properties['storage.cost.5000MB'] = 500.0;
            
	    return $properties;		    
    }
    
    protected function load_view($viewname,$data=null,$asString=false){
	    if ($asString) {
		$output = $this->load->view($viewname,$data,TRUE);
		return $output;	exit();
	    }
	
	    if (is_null($data))
		    $data = array(
			'view_name'	=>  $viewname,
		    );
	    else
		$data['view_name']	=  $viewname;
	    
	    $last_slash = strrpos($viewname, "/");
	    $found = false;
	    
	    // το full path για το τοπικό template.php - θα χρειαστεί στην file_exists
	    $view_dir = FCPATH."/application/views/".substr($viewname, 0, strrpos($viewname, "/"));
	    // το short path για το τοπικό template - θα χρειαστεί στην $this->load_view
	    $view_short_path = substr($viewname, 0, strrpos($viewname, "/"));
	    
	    // όσο δεν έχει βρεθεί τοπικό template.php και υπάρχει slash
	    while (($last_slash > 0)&&(!$found)) {
		// Αν υπάρχει τοπικό template.php σε αυτό το επίπεδο
		if (file_exists($view_dir."/template.php")){
		    $template = $view_short_path."/template";
		    $found = true;
		} else { // αλλιώς πάμε ένα επίπεδο πιο πάνω
		    $view_dir = substr($view_dir, 0, strrpos($view_dir, "/"));
		    $view_short_path = substr($viewname, 0, strrpos($view_short_path, "/"));
		    $last_slash = strrpos($view_short_path, "/");
		}
	    }
	    // Τώρα που έφυγαν όλα τα slash έμεινε ακόμα ένα επίπεδο
	    if (file_exists($view_dir."/template.php")){
		$template = $view_short_path."/template";
		$found = true;
	    } else { // αν δεν βρέθηκε τοπικό template φόρτωσε το master template
		$template = "template";
	    }
	    
	    // Load the template
	    $this->load->view($template,$data);
	}
	
}

// Super class for admin-related controllers
class Admin_Controller extends Lis_Controller {

	// Initializes all admin controllers by loading configuration parameters
	public function __construct() {
	    parent::__construct();
	    
	    // set the default time zone
	    date_default_timezone_set('America/New_York');
	    
	    // Load some configuration from the ini file
	    $conf_location= '/admin/conf/lisadmin.ini';
	    $this->properties = $this->loadProperties($conf_location); 
	    // Add some extra configuration data
	    $this->properties['version_number'] = "1.31";
	    $this->properties['version'] = $this->properties['version_number']." 01/30/2012";
	    $this->properties['home.directory'] = CIPATH."/admin/";
	}
	
	// Returns an array which contains one data record for each administrator
	protected function loadUsers() {
	    
	    $users = array();
	
	    $userdata = array(
		'userid'    =>	'johnsmith', 
		'password'  =>	'john$^&100', 
		'role'	    =>	'admin', 
		'name'	    =>	'John Smith',
		'email'	    =>	'john@mylis.net', 
		'status'    =>	'present', 
		'info'	    =>	'Administrator'
	    );
	    
	    $this->load->library('user',$userdata);
	    $users['johnsmith'] = $this->user;
	    return $users;
	}
	
	// This function should be called by every admin page or before
	// any admin action to prevent unauathorized access
	public function restrict_access(){
            
	    if ($this->session->userdata('user')) {
		if (!($this->session->userdata('group') == 'admin')){
		    // The user is logged in but is not an admin
		    $this->load_view('errors/unauthorized'); 
		    $this->output->_display(); die();
		}
	    } else {
		// The user is not logged in
		$this->load_view('admin/login');
		$this->output->_display(); die();
	    }
	}
}

// Super class for group-related controllers
class Group_Controller extends Lis_Controller {
    
	public function __construct() {
	    parent::__construct();

	    // Read the group name (wether the user is logged in or this is 
	    // the login page. If neither is the case then the URL is not
	    // a legal URL)
	    if ($this->session->userdata('group'))
		$groupname = $this->session->userdata('group');
	    elseif (!empty($_GET['group']))
		$groupname = $_GET['group'];
	    else {
		// user is not logged in neither this is a login request (maybe the session 
		// has been timed out or a visitor tries to access a group's page)
		redirect('welcome');
	    }
	    // set the default time zone
	    date_default_timezone_set('America/New_York');
	    // Load some configuration from the ini file
	    $conf_location = '/accounts/mylis_'.$groupname.'/conf/lis.ini';
	    $this->properties = $this->loadProperties($conf_location);
	    // Add some extra configuration data
	    $this->properties['version_number'] = "1.31";
	    $this->properties['version'] = $this->properties['version_number']." 01/30/2012";
	    $this->properties['home.directory'] = CIPATH."/accounts/mylis_".$groupname;
	    
	}	
	
	// This function is called by every group page or before any 
	// group action to prevent unauathorized access
	public function restrict_access(){
	    if (!$this->session->userdata('user')) {
		// the user is not logged in
		$this->load->view('group/login');
		$this->output->_display(); die();
	    } 
	}
	
	// function to get a list of only current users, not past, of a group
	function getCurrentUsers() {
	   $users = $this->loadUsers();
	    $current_users = array();

	    foreach($users as $user) {
		$userid= $user->userid;
		$status = $user->status;
		if($status == 'present' || $status == 'Group PI') {
		    $current_users[$userid] = $user;
		}
	    }

	    return $current_users;
	}
	
	// load the default user
	function getDefaultUser() {
	    $userdata = array(
		    'userid'    =>	'myadmin', 
		    'password'  =>	'change_password', 
		    'role'	=>	'admin', 
		    'name'	=>	'MyLIS Admin',
		    'email'	=>	'n/a', 
		    'status'    =>	'present', 
		    'info'	=>	''
		);

	    $this->load->library('user',$userdata);
	    return $this->user;
	}

	// funtion to return an array of users
	function loadUsers() {
	    $users = array();

	    // get the default user account
	    $du = $this->getDefaultUser();
	    $users["$du->userid"] = $du;

	    // Load users from the database
	    $this->load->model('user_model');
	    $userList = $this->user_model->getGroupUsers($this->properties['lis.account']);
	    
	    $total = count($userList);
	    if($total>=1) {
		$counter = 0;
		// For each user create a User object and add it to the user's list
		while($counter<$total) {
		    $userid = $userList[$counter]['userid'];
		    $password = $userList[$counter]['password'];
		    $role = $userList[$counter]['role'];
		    $name = $userList[$counter]['name'];
		    $email = $userList[$counter]['email'];
		    $status = $userList[$counter]['status'];
		    $info = $userList[$counter]['info'];
		    $userdata = array(
			'userid'    =>	$userid, 
			'password'  =>	$password, 
			'role'	=>	$role, 
			'name'	=>	$name,
			'email'	=>	$email, 
			'status'    =>	$status, 
			'info'	=>	$info
		    );
		    $this->load->library('user',$userdata,$userid);
		    $users[$userid] = $this->{$userid};
		    $counter++;
		}
	    }

	    return $users;
	}
	
}
