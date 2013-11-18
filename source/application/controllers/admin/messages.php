<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages extends Admin_Controller {
    
    var $userobj = null;
    
    public function __construct(){
	parent::__construct();
	
	// Setup paramaters for initializing models below
	$params['user'] = $this->session->userdata('user');
	$params['account'] = $this->session->userdata('group');
	
	$this->load->model('filemanager');
	$this->filemanager->initialize($params);
	
	$this->load->model('proputil_model');
	$this->proputil_model->initialize($params);
	
	$this->userobj = $this->session->userdata('user');
    }
    
    // Handles the posting of new messages 
    public function index(){
	$this->restrict_access();

	// If a new message has been posted
	if (isset($_POST['message_poster_form'])){
	    // If the posted message is valid
	    if($this->checkFormInput()) {
		// Get posted message data
		$account_ids = $this->input->post('accounts');
		$now	     = $this->input->post('now');
		$post_start  = $this->input->post('post_start');
		$post_end    = $this->input->post('post_end');
		$message     = $this->input->post('message');
		$url	     = $this->input->post('url');

		// If 'Post Immediately' checkbox has been selected change $post_start 
		// current date
		if(!empty($now)) {
		    $post_start = getLISDate();
		}

		// If url is not empty add an 'http' prefix
		if(!empty($url) && !strstr($url, 'http://')) {
		    $url = 'http://'.$url;
		}
	
		// Load the message data to an array
		$data['account_ids'] = $account_ids;
		$data['post_start'] = $post_start;
		$data['post_end'] = $post_end;
		$data['message_date'] = getLISDateTime();
		$data['manager'] = $this->userobj->userid;
		$data['message'] = $message;
		$data['url'] = $url;
		// Save the message
		$this->load->model('message_model');
		$messageList = $this->message_model->addSystemMessage($data);
	    }
	    // If the message has been saved or the message data were not valid
	    // redirect to main page
		redirect('admin/main');
	} else { // If no form has been posted load an empty form
	    //
	    $this->load->model('message_model');
	    $messageList = $this->message_model->getAllSystemMessages();
	    
	    $data['post_start'] = getLISDate();
	    $data['post_end'] = addDaysToDate($data['post_start'], 7);
	    $data['messageList'] = $messageList;
	    $data['accounts'] = $this->loadUsers();
	    $data['title'] = 'Message Poster';
	
	    $this->load->view('admin/messagePoster',$data);
	}
    }
    
    // Handes the editing of existing messages
    public function edit($message_id){
	
	// If the edited message has been posted
	if (isset($_POST['edit_message_form'])){
	    // If the edited message contains valid data
	    if($this->checkFormInput()) {
		// Get posted message data
		$data['account_ids'] = $this->input->post('accounts');
		$data['now'] = $this->input->post('now');
		$data['post_start'] = $this->input->post('post_start');
		$data['post_end'] = $this->input->post('post_end');
		$data['message'] = $this->input->post('message');
		$data['message_id'] = $message_id;

		// If url is not empty add an 'http' prefix
		$url = $this->input->post('url');
		if(!empty($url) && !strstr($url, 'http://')) {
		    $url = 'http://'.$url;
		}
		$data['url'] = $url;
		// Save the edited message
		$this->load->model('message_model');
		$message = $this->message_model->updateSystemMessage($data);
	    }
	    
	    redirect('admin/messages');
	    
	} else { // Loads a message for editing
	    // Retrieve the message data from database
	    $this->load->model('message_model');
	    $message = $this->message_model->getSystemMessage($message_id);
	    // Load the message editing form
	    $data['title'] = "Edit Message";
	    $data['message_id'] = $message_id;
	    $data['home_link'] = encodeUrl(base_url()."admin/main");
	    $data['back_link'] = encodeUrl(base_url()."admin/messages");
	    $data['messageItem'] = $message;
	    
	    $this->load->view('admin/editMessageForm',$data);
	}
	
    }
    
    // Deletes a message
    public function delete($id){
	$this->load->model('message_model');
	$this->message_model->deleteSystemMessage($id);

	// Go to main page
	redirect('admin/messages');
   }
    
    // Checks the validity of posted data when editing a message or posting a new message
    private function checkFormInput() {
	$error;
	$account_ids	= $this->input->post('accounts');
	$now		= $this->input->post('now');
	$post_start	= $this->input->post('post_start');
	$post_end	= $this->input->post('post_end');
	$message	= $this->input->post('message');

	if(empty($account_ids)) {
	    $error .= 'Account ID<br>';
	}
	if(empty($now) && (empty($post_start) || !checkLISDate($post_start))) {
	    $error .= 'Message Post Date<br>';
	}
	if(empty($post_end) || !checkLISDate($post_end)) {
	    $error .= 'Message Remove Date<br>';
	}
	if(empty($message)) {
	    $error .= 'Message<br>';
	}
	if(!empty($error)) {
	    echo 'The following value(s) were not entered, or the formating is incorrect. <br><br>'.$error;
	    return 0;
	} else {
	    return 1;
	}
    }
    
    
}