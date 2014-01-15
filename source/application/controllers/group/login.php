<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Handles the login functionality of the Group area.
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis
 */
class Login extends Group_Controller {
    
	public $groupname = null;
	
	public function __construct(){
	    parent::__construct();
	}

	/**
         * Loads the login page for users that are not already authenticated
         */
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
	
	/**
         * Handles the login requests
         */
	public function login_request()
	{
	    // if the user is not logged in
	    if (!$this->session->userdata('userid')) {
		
		//get the posted data
		$userid	    = $this->input->post('userid');
		$password   = $this->input->post('password');
		
		// If the username or password were not empty
		if(!empty($userid) && !empty($password)) {
		    $user = $this->validate_user($userid, $password);
                        
		    // If the user credentials were not valid
		    if(empty($user)) {
			$data1['groupname'] = $this->input->get('group');
			$this->load->view('errors/group_login_failed',$data1);
		    } else {  
		    // If the credentials were valid
			//  If the account has not been expired
			if(!$this->is_expired()) {
			    // If credentials are valid set the session variables
			    // and redirect to main group page
			    $user->password = ''; // strip out password from data that will be stored to session
			    $this->session->set_userdata('user',$user); 
			    $this->session->set_userdata('group',$this->properties['lis.account']);
			    
			    // If the user came to MyLIS with a module direct link there may be a url
			    // of the place from where he came from. We need to store that in the session
			    // to send him back there after the logout
			    if (isset($_GET['home']))
				$this->session->set_userdata('logout_target',$this->input->get('home'));

			    // Add a log entry
			    $params['user'] = $user;
			    $this->load->library('logger',$params);
			    $this->logger->addLog('main');

			    // If the user came to MyLIS with a module direct link we will 
			    // redirect him to that module
			     if (isset($_GET['redirect'])){
				 $this->session->set_userdata('direct_entry','yes');
				 redirect($this->input->get('redirect'));
			     } else {
				redirect('group/main'); 
			     }
			} else {
			    // If the account has been expired
                            $data['login_error'] = $this->load->view('errors/expired_account',null,TRUE);
                            $data['target'] = base_url().'group/login/login_request?group='.$this->properties['lis.account'];
			    $this->load->view('group/login',$data);
			}
		    }
		} else { 
		    $data1['groupname'] = $this->input->get('group');
		    $this->load->view('errors/group_login_failed',$data1);
		}
	    } else {
		// If this is an already logged in user
		redirect('group/main');
	    }
	}
	
	/**
         * Validates the user's login credentials
         * 
         * @param string $userid
         * @param string $password
         * @return object The object, if not null, will be of 'User' class
         */
	protected function validate_user($userid, $password) {
	    $users = $this->get_current_users(); // ony allow current users to login

	    // see if this userid is in the database
	    if((isset($users[$userid]))&&($users[$userid]->password == $password)) {
		    $user = $users[$userid];
	    } else {
		$user=null;
	    }
	    return $user;
	}
	
	/**
         * Checks out if the account has expired.
         * 
         * @return boolean
         */
	protected function is_expired() {
	    $expired = false;
	    $expire_date = $this->properties['lis.expire'];

	    if($this->get_days_remaining($expire_date) < 0) {
		$expired = true;
	    }

	    return $expired;
	}
	
	/**
         * Returns days remaining from today's date till the date passed as parameter
         * 
         * @param string $date
         * @return int
         */
	protected function get_days_remaining($date) {
	    $days = 0;
	    $sa  = explode('/', $date);
	    $date_utc = mktime(0, 0, 0, $sa[0], $sa[1], $sa[2]);
	    $now_utc = mktime() + $this->lis_tz[$this->properties['lis.timezone']];
	    $diff_seconds = $date_utc - $now_utc;
	    $days = floor($diff_seconds/86400) + 1; // need to add one
	    return $days;
	}
	
	/**
         * Logs the user out
         */
	public function logout(){
	    $groupname = $this->session->userdata('group');
	    $logout_target = $this->session->userdata('logout_target');
	    session_destroy();
	    $this->session->unset_userdata('user');
	    $this->session->unset_userdata('role');
	    $this->session->unset_userdata('group');
	    $this->session->unset_userdata('direct_entry');
	    $this->session->unset_userdata('logout_target');
	    
	    if (!empty($logout_target))
		redirect($logout_target);
	    else 
		redirect('group/login?group='.$groupname);
	}
}