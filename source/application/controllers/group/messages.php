<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Handles the administration of group messages
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis
 */
class Messages extends Group_Controller {
    
    var $userobj = null;
    var $table_name = null;
    
    public function __construct(){
	parent::__construct();
	
	// Setup paramaters for initializing models below
	$params['user'] = $this->session->userdata('user');
	$params['account'] = $this->session->userdata('group');
	$params['properties'] = $this->properties;
	
	$this->load->model('filemanager');
	$this->filemanager->initialize($params);
	
	$this->load->model('proputil_model');
	$this->proputil_model->initialize($params);
	$this->userobj = $this->session->userdata('user');
	
	$this->table = $this->session->userdata('group').'_messages';
	$this->restrict_access();
    }
    
   /**
    *  Handles the posting of new group messages  
    */
   public function add(){
       
        // // Get posted message data
	$url = $this->input->post('url');
	if(!empty($url)) {
	    $url = checkURL($url);
	}
	
	$message = $this->input->post('message');
	$file_type = $this->input->post('filetype_1');
	$date_time = $this->get_lis_date_time();
	$userid = $this->userobj->userid;
	
	// If the message was empty go back to main page
	if(empty($url) && empty($message) && ($data['file_type'] == 'none')) {
	    echo "empty message"; die();
	    redirect('group/main');
	}
	
	// If a file was attached, upload it and get the file id
	if($file_type != 'none') {
	    $file_id = $this->filemanager->upload_file(1, $this->table);
	}

	$data['url'] = $url;
	$data['message'] = $message;
	$data['file_id'] = $file_id;
	$data['date_time'] = $date_time;
	$data['userid'] = $userid;
	
	// Save the posted message
	$this->load->model('message_model');
	$this->message_model->add_message($data);

	// Go back to main page
	redirect('group/main');
   }
   
   /**
    * Handles the editing of existing group messages
    */
   public function edit(){
       
        // If the edited message has been posted
	if (isset($_POST['message_id'])){
	    $table = $this->session->userdata('group')."_messages";
	    // Get the posted data
	    $url = $this->input->post('url');
	    if(!empty($url)) {
		$url = checkURL($url);
	    }

	    $data['message_id'] = $this->input->post('message_id');
	    $data['url'] = $url;
	    $data['message'] = $this->input->post('message');
	    $data['file_type'] = $this->input->post('filetype_1');
	    $data['date_time'] = $this->get_lis_date_time();
	    $data['userid'] = $this->userobj->userid;

	    // Get the originall message data
	    $this->load->model('message_model');
	    $old_message = $this->message_model->get_message($data['message_id']);

	    // If a file has been posted with the message: if there wasn't any file
	    // attached to the message originaly save the file and get the file id, otherwise
	    //  just replace the old file with the new one
	    if($data['file_type'] != 'none') {
		$file_id = $old_message['file_id'];
		if(empty($file_id)) {
		    $file_id = $this->filemanager->upload_file(1, $table, $data['message_id']);
		    echo "new file id = ".$file_id."<br>";
		    $data['file_id'] = $file_id;
		}
		else {
		    $this->filemanager->update_file(1, $file_id);
		}
	    }
	    // Update the message data
	    $this->message_model->update_message($data);
	}
      
	redirect('group/main');
   }
   
   /**
    * Handles the deletion of a group message
    * 
    * @param int $id
    */
   public function delete($id){
	$this->load->model('message_model');
	
	// Retrieve the message data before delete it
	$messageItem = $this->message_model->get_message($id);
	
	// Delete the message
	$this->message_model->delete_message($id);
	
	// If there was a file attached to the message, delete the file.
	if(!empty($messageItem['file_id'])) {
	    $this->filemanager->delete_file($messageItem['file_id']);
	}

	// Go to main page
	redirect('group/main');
   }
   
   /**
    * Handles the deletion of a message file
    * Not implemented yet!
    * 
    * @param int $id
    */
   public function delete_message_file($id){
       // Not implemented yet!
       echo "This function is not implemented yet!";
   }
   
   /**
    * Changes the group settingsin order to hide the welcome message
    */
   public function hide_welcome(){
       // function to hide the welcome message
	$userid = $this->userobj->userid;
	$key = 'show.welcome.'.$userid;
	$this->proputil_model->store_property($key, 'no');

	// Go to main page
	redirect('group/main');
   }
    
}