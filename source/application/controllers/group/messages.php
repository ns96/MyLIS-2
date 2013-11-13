<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages extends Group_Controller {
    
    var $user = null;
    
    public function __construct(){
	parent::__construct();
	$this->load->model('filemanager');
	
	$this->user = $this->session->userdata('user');
	
	$params['user'] = $this->session->userdata('user');
	$params['account'] = $this->session->userdata('group');
	$this->load->model('proputil');
	$this->proputil->initialize($params);
    }
    
   public function add(){
       // Gather posted data
	$url = $this->input->post('url');
	if(!empty($url)) {
	    $url = $this->checkURL($url);
	}
	
	$data['url'] = $url;
	$data['message'] = $this->input->post('message');
	$data['file_type'] = $this->input->post('filetype_1');
	$data['date_time'] = getLISDateTime();
	$data['userid'] = $this->user->userid;

	// If the message was empty go back to main page
	if(empty($url) && empty($message) && ($data['file_type'] == 'none')) {
	    redirect('group/main');
	}
	
	// If a file was attached, upload it and get the file id
	if($data['file_type'] != 'none') {
	    $data['file_id'] = $this->filemanager->uploadFile(1, $this->table, $message_id);
	}

	// Save the posted message
	$this->load->model('message_model');
	$this->message_model->addMessage($data);

	// Go back to main page
	redirect('group/main');
   }
   
   public function edit(){
	$url = $this->input->post('url');
	if(!empty($url)) {
	    $url = $this->checkURL($url);
	}
       
	$data['message_id'] = $this->input->post('message_id');
	$data['url'] = $url;
	$data['message'] = $this->input->post('message');
	$data['file_type'] = $this->input->post('filetype_1');
	$data['date_time'] = getLISDateTime();
	$data['userid'] = $this->user->userid;

	$this->load->model('message_model');
	$old_message = $this->message_model->getMessage($data['message_id']);

	// add file to file_id table now and update db table if need be
	if($data['file_type'] != 'none') {
	    $file_id = $old_message[file_id];
	    if(empty($file_id)) {
		$file_id = $this->filemanager->uploadFile(1, $this->table, $data['message_id']);
		$data['file_id'] = $file_id;
	    }
	    else {
		$this->filemanager->updateFile(1, $file_id);
	    }
	}

	$this->message_model->updateMessage($data);
	
	redirect('group/main');
   }
   
   public function delete($id){
	$this->load->model('message_model');
	
	// Retrieve the message data before delete it
	$messageItem = $this->message_model->getMessage($id);
	
	// Delete the message
	$this->message_model->deleteMessage($id);
	
	// If there was a file attached to the message, delete the file.
	if(!empty($messageItem['file_id'])) {
	    $this->filemanager->deleteFile($messageItem['file_id']);
	}

	// Go to main page
	redirect('group/main');
   }
   
   public function deleteMessageFile($id){
       
   }
   
   public function hide_welcome(){
       // function to hide the welcome message
	$userid = $this->user->userid;
	$key = 'show.welcome.'.$userid;
	$this->proputil->storeProperty($key, 'no');

	// Go to main page
	redirect('group/main');
   }
   
   // add the http to the  any url
    private function checkURL($url) {
	$new_url = '';
	if(!stristr($url, 'http')) {
	    $new_url = 'http://'.$url;
	}
	else {
	    $new_url = $url;
	}
	return $new_url;
    }
    
}