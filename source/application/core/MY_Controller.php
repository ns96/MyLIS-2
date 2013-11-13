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
	// load the timezones array
	$this->load->library('listimezones');
	$this->lis_tz = $this->listimezones->get_tz();
    }
    
    // Reads configuration data (from ini file) for group or admin account
    protected function loadProperties($conf_location) {
	    $propertiesFile = CIPATH.$conf_location;
	    $properties = parse_ini_file($propertiesFile,false,INI_SCANNER_RAW);

	    return $properties;
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
	
	// This function should be called by every admin page or before
	// any admin action to prevent unauathorized access
	public function restrict_access(){
	    if ($this->session->userdata('user')) {
		if (!($this->session->userdata('group') == 'admin')){
		    // The user is logged in but is not an admin
		    $this->load->view('errors/unauthorized'); 
		    $this->output->_display(); die();
		}
	    } else {
		// The user is not logged in
		$this->load->view('admin/login');
		$this->output->_display(); die();
	    }
	}
}

// Super class for group-related controllers
class Group_Controller extends Lis_Controller {

	public $groupname = null;
    
	public function __construct() {
	    parent::__construct();

	    // Read the group name (wether the user is logged in or this is 
	    // the login page. If neither is the case then the URL is not
	    // a legal URL.
	    if ($this->session->userdata('group'))
		$this->groupname = $this->session->userdata('group');
	    elseif (!empty($_GET['group']))
		$this->groupname = $_GET['group'];
	    else {
		// user is not logged in neither this is a login request (maybe the session 
		// has been timed out or a visitor tries to access a group's page)
		$this->load->view('intro');
		$this->output->_display(); die();
	    }
	    // set the default time zone
	    date_default_timezone_set('America/New_York');
	    // Load some configuration from the ini file
	    $conf_location = '/accounts/mylis_'.$this->groupname.'/conf/lis.ini';
	    $this->properties = $this->loadProperties($conf_location);
	    // Add some extra configuration data
	    $this->properties['version_number'] = "1.31";
	    $this->properties['version'] = $this->properties['version_number']." 01/30/2012";
	    $this->properties['home.directory'] = CIPATH."/accounts/mylis_".$this->groupname;
	}	
	
	// This function is called by every group page or before any 
	// group action to prevent unauathorized access
	public function restrict_access(){
	    if (!$this->session->userdata('user')) {
		// the user is not logged in
		$this->load->view('admin/login');
		$this->output->_display(); die();
	    }
	}
	
}
