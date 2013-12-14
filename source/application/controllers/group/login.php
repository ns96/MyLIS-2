<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Group_Controller {
    
	public $groupname = null;
	
	public function __construct(){
	    parent::__construct();
	}

	// Loads the login page for users that are not already authenticated
	public function index()
	{
	    if ($this->session->userdata('userid')) {
		if ((empty($_GET['group'])) || ($this->session->userdata('group') != $_GET['group']))
		    $this->load_view('errors/unauthorized'); 
		else {
		    redirect('group/main');
		}
	    } else {
		$data['target'] = base_url().'group/login/login_request?group='.$this->properties['lis.account'];
		$this->load->view('group/login',$data); 
	    }
	}
	
	// Handles the login requests
	public function login_request()
	{
	    // if the user is not logged in
	    if (!$this->session->userdata('userid')) {
		
		//get the posted data
		$userid	    = $this->input->post('userid');
		$password   = $this->input->post('password');
		
		// If the username or password were not empty
		if(!empty($userid) && !empty($password)) {
		    $user = $this->validateUser($userid, $password);
                        
		    // If the user credentials were not valid
		    if(empty($user)) {
			$this->load_view('errors/group_login_failed');
		    } else {  
		    // If the credentials were valid
			//  If the account has not been expired
			if(!$this->isExpired()) {
			    // If credentials are valid set the session variables
			    // and redirect to main group page
			    $user->password = ''; // strip out password from data that will be stored to session
			    $this->session->set_userdata('user',$user); 
			    $this->session->set_userdata('group',$this->properties['lis.account']);

			    // Add a log entry
			    $params['user'] = $user;
			    $this->load->library('logger',$params);
			    $this->logger->addLog('main');

			    redirect('group/main');
			} else {
			    // If the account has been expired
                            $data['login_error'] = $this->load->view('errors/expired_account',null,TRUE);
                            $data['target'] = base_url().'group/login/login_request?group='.$this->properties['lis.account'];
			    $this->load->view('group/login',$data);
			}
		    }
		} else { 
		    $data1['groupname'] = $this->properties['lis.account'];
		    $data['login_error'] = $this->load->view('errors/group_login_failed',$data1,TRUE);
                    $data['target'] = base_url().'group/login/login_request?group='.$this->properties['lis.account'];
                    $this->load->view('group/login',$data);
		}
	    } else {
		// If this is an already logged in user
		redirect('group/main');
	    }
	}
	
	// Validates the user's credentials
	function validateUser($userid, $password) {
	    $users = $this->getCurrentUsers(); // ony allow current users to login

	    // see if this userid is in the database
	    if((isset($users[$userid]))&&($users[$userid]->password == $password)) {
		    $user = $users[$userid];
	    } else {
		$user=null;
	    }
	    return $user;
	}
	
	// function to check to see if the account has expired. 
	// if it has then print out message
	function isExpired() {
	    $expired = false;
	    $expire_date = $this->properties['lis.expire'];

	    if($this->getDaysRemaining($expire_date) < 0) {
		$expired = true;
	    }

	    return $expired;
	}
	
	// function to return days remaining from todays date and the date variable
	function getDaysRemaining($date) {
	    $days = 0;
	    $sa  = explode('/', $date);
	    $date_utc = mktime(0, 0, 0, $sa[0], $sa[1], $sa[2]);
	    $now_utc = mktime() + $this->lis_tz[$this->properties['lis.timezone']];
	    $diff_seconds = $date_utc - $now_utc;
	    $days = floor($diff_seconds/86400) + 1; // need to add one
	    return $days;
	}
	
	// Logs a user out
	public function logout(){
	    $groupname = $this->session->userdata('group');
	    session_destroy();
	    $this->session->unset_userdata('userid');
	    $this->session->unset_userdata('role');
	    $this->session->unset_userdata('group');
	    redirect('group/login?group='.$groupname);
	}
}