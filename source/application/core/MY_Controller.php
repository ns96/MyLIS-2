<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Superclass for all MyLIS controllers.
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis
 */
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
    
    /**
     * Reads configuration data (from .ini file) for group or admin account
     * 
     * @param string $conf_location
     * @return array
     */
    protected function load_properties($conf_location) {
	    $propertiesFile = CIPATH.$conf_location;
	    if (file_exists($propertiesFile))
		$properties = parse_ini_file($propertiesFile,false,INI_SCANNER_RAW); // INI_SCANNER_RAW is used in order not
	    								// to convert 'true' and 'false' values to 1 and o
	    else {
		echo "Error: Account configuration file could not be found!"; 
                die();
            }
            /**Storage Cost Per Year Information */
            $properties['storage.cost.200MB'] = 100.0;
            $properties['storage.cost.1000MB'] = 300.0;
            $properties['storage.cost.5000MB'] = 500.0;
            
	    return $properties;		    
    }
    
    /**
     * Advanced view loader that supports the use of template files
     * 
     * @param string $viewname
     * @param array $data
     * @param boolean $asString
     */
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

    /**
    * Function to return the date is lis format
    * 
    * @return string 
    */
    public function get_lis_date() {
	$now_utc = mktime() + $this->lis_tz[$this->properties['lis.timezone']];
	$array = getdate($now_utc);
	$date_now = $array['mon'].'/'.$array['mday'].'/'.$array['year'];

	return $date_now;
    }
    
    /**
    * function to get the lis time
    * 
    * @global type $properties
    * @global type $lis_tz
    * @return string 
    */
    public function get_lis_time() {

	$now_utc = mktime() + $this->lis_tz[$this->properties['lis.timezone']];
	$array = getdate($now_utc);

	$hour = $array['hours'];
	$am_pm = ' am';
	$min = $array['minutes'];

	if($hour > 12) {
	    $hour -= 12;
	    $am_pm = ' pm';
	}
	else if($hour == 12) {
	    $am_pm = ' pm';
	}

	// add a leading zero to the minutes if needed
	$minutes = $min;
	if($min < 10) {
	    $minutes = '0'.$min;
	}

	$time_now = $hour.':'.$minutes.$am_pm;

	return $time_now;
    }
  
    /**
    * Function to get the date and time now
    * 
    * @return string 
    */
    public function get_lis_date_time() {
	$date_time = $this->get_lis_date().' '.$this->get_lis_time();
	return $date_time;
    }      	
	
    /**
    * Function to get the expire date given a term
    * 
    * @param string $activate_date
    * @param string $status
    * @param int $term
    * @return string 
    */
    public function get_expire_date($activate_date, $status, $term) {
	$expire_date = '';

	if($status == 'trial') {
	    $expire_date = addDaysToDate($activate_date, $this->properties['lis.trial.days']);
	}
	else if($term == 1) {
	    $expire_date = addDaysToDate($activate_date, 365);
	}
	else if($term == 2) {
	    $expire_date = addDaysToDate($activate_date, 730);
	}
	else if($term == 3) {
	    $expire_date = addDaysToDate($activate_date, 1095);
	}
	else if($term == 4) {
	    $expire_date = addDaysToDate($activate_date, 1460);
	}
	return $expire_date;
    }    
    
}

/**
 * Super class for admin-related controllers
 */
class Admin_Controller extends Lis_Controller {
    
	public function __construct() {
	    parent::__construct();
	    
	    // set the default time zone
	    date_default_timezone_set('America/New_York');
	    
	    // Load some configuration from the ini file
	    $conf_location= '/admin/conf/lisadmin.ini';
	    $this->properties = $this->load_properties($conf_location); 
	    // Add some extra configuration data
	    $this->properties['version_number'] = "2.0";
	    $this->properties['version'] = $this->properties['version_number']." 01/18/2014";
	    $this->properties['home.directory'] = CIPATH."/admin/";
	    $this->properties['home.url'] = base_url().'admin/main';
	}
	
	/**
         * Returns an array which contains one data record for each administrator
         * 
         * @return array An array of 'User' objects
         */
	protected function load_users() {
	    
	    $users = array();
	
	    $userdata = array(
		'userid'    =>	$this->config->item('mylis_admin_username'), 
		'password'  =>	$this->config->item('mylis_admin_password'), 
		'role'	    =>	'admin', 
		'name'	    =>	'My LIS Admin',
		'email'	    =>	'admin@localhost.com', 
		'status'    =>	'present', 
		'info'	    =>	'Administrator'
	    );
	    
	    $users[$this->config->item('mylis_admin_username')] = new User($userdata);
	    return $users;
	}
	
	/**
         * This function should be called by every admin page or before
         * any admin action to prevent unauathorized access
         */
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

/**
 * Super class for group-related controllers
 */
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
	    $this->properties = $this->load_properties($conf_location);
	    // Add some extra configuration data
	    $this->properties['version_number'] = "2.0";
	    $this->properties['version'] = $this->properties['version_number']." 01/18/2014";
	    $this->properties['home.directory'] = CIPATH."/accounts/mylis_".$groupname;
	}	
	
	/**
         * This function is called by every group page or before any 
	 * group action to prevent unauathorized access
         */
	public function restrict_access(){
	    if (!$this->session->userdata('user')) { // the user is not logged in
		$login_target = base_url().'group/login/login_request?group='.$this->properties['lis.account'];
		
		// If the user came to MyLIS with a module direct link we need to store the module path to redirect 
		// him there after the login we should store the place from where the user came from to send him
		// after the logout
		$redirect_to = uri_string();
		if (!empty($redirect_to))
		    $login_target .= "&redirect=".$redirect_to;
		
		$home_link = $this->input->get('home_link');
		if (!empty($home_link))
		    $login_target .= "&home=".urlencode($home_link);
		
		$data['target'] = $login_target;
		$this->load->view('group/login',$data);
		$this->output->_display(); die();
	    } else {  // the user is already logged in
		if ($this->session->userdata('group') == 'admin'){
		    // The user is logged in as admin but this is a group area
		    $this->load->view('errors/unauthorized'); 
		    $this->output->_display(); die();
		}
	    }
	}
	
	/**
         * Returns a list of only current users, not past, of a group
         * 
         * @return array
         */
	protected function get_current_users() {
            $users = $this->load_users();
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
	
	/**
         * Loads the default user
         * 
         * @return object
         */
	protected function get_default_user() {
	    $userdata = array(
		    'userid'    =>	$this->config->item('mylis_default_username'), 
		    'password'  =>	$this->config->item('mylis_default_password'), 
		    'role'	=>	'admin', 
		    'name'	=>	'MyLIS Group Admin',
		    'email'	=>	'n/a', 
		    'status'    =>	'present', 
		    'info'	=>	''
		);

	    return new User($userdata);
	}

	/**
         * Return the list of group users
         * 
         * @return array
         */
	protected function load_users() {
	    $users = array();

	    // get the default user account
	    $du = $this->get_default_user();
	    $users["$du->userid"] = $du;

	    // Load users from the database
	    $this->load->model('user_model');
	    $userList = $this->user_model->get_group_users($this->properties['lis.account']);
	    
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
		    
		    $users[$userid] = new User($userdata);
		    $counter++;
		}
	    }

	    return $users;
	}
	
}
