<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Admin_Controller {
    
	public function __construct(){
	    parent::__construct();
	}
	
	// Loads the login page for users that are not already authenticated
	public function index()
	{
	    if ($this->session->userdata('userid')) {
		if (!($this->session->userdata('role') == 'admin')){
		    $this->load->view('errors/unauthorized'); 
		} else {
		    redirect('admin/main');
		}
	    } else {
		$this->load->view('admin/login'); 
	    }
	}
	
	// Validates the user's credentials
	private function validateUser($userid, $password) {
	    
	    // Load the array with administrators
	    $users = $this->loadUsers();

	    // Check if this userid is in the array
	    if((isset($users[$userid]))&&($users[$userid]->password == $password)) {
		    $user = $users[$userid];
	    } else {
		$user=null;
	    }
	    return $user;
	}
	
	// Handles the login requests
	public function login_request()
	{
	    //echo "<pre>"; var_dump($this->session->userdata, $_POST); die();
	    
	    // if the user is not logged in
	    if (!$this->session->userdata('user')) {
		
		// Get the posted data
		$userid	    = $this->input->post('userid');
		$password   = $this->input->post('password');
		$logintry   = $this->input->post('logintry');
		
		// If username and password are not empty
		if(!empty($userid) && !empty($password)) {
		    $user = $this->validateUser($userid, $password);
		    if(empty($user)) {
			$this->load->view('errors/admin_login_failed');
		    } else {
			// If credentials are valid set the session variables
			// and redirect to main admin page
			$this->session->set_userdata('user',$user); 
			$this->session->set_userdata('group',$user->role);

			// Add a log entry
			$params['user'] = $user;
			$this->load->library('logger',$params);
			$this->logger->addLog('main');
			
			redirect('admin/main');
		    }
		} else { // if the 'logintry' field has been posted then someone entered empty username/password
		    if(!empty($logintry)) {
			$this->load->view('errors/admin_login_failed');
		    } else { // else the session has been timed out
			$this->load->view('errors/session_timeout');
		    }
		}
	    } else {
		// Hey! You are already logged in! Go to main page!
		redirect('admin/main');
	    }
	}
	
	// Logs a user out
	public function logout(){
	    session_destroy();
	    $this->session->unset_userdata('user');
	    $this->session->unset_userdata('group');
	    redirect('admin/login');
	}
	
}