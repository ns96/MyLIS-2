<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Publications extends Group_Controller {
    
    private $userobj = null;
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');
	
	// Setup paramaters for initializing models below
	$params['user'] = $this->userobj;
	$params['account'] = $this->session->userdata('group');
	$params['properties'] = $this->properties;
	
	// Load a FileManager model
	$this->load->model('filemanager');
	$this->filemanager->initialize($params);
	
	$this->load->model('publication_model');
	
	$this->restrict_access();
    }
    
    public function index(){
	$this->load->model('user_model');
	
	$status = $this->publication_model->get_table_status();
	$status_text = '&nbsp;&nbsp;<span style="font-size:14px; font-weight:normal">( Total: '.$status[0].' - Last Updated: '.$status[1].' )</span>';
    
	$pubsHTML = '';
	$posters = $this->publication_model->get_posters();
	
	$pubsHTML = '';
	foreach ($posters as $poster){
	    $user = $this->user_model->get_user($poster);
	    $publications = $this->publication_model->get_user_publications($poster);
	    $data2['poster'] = $user;
	    $data2['publications'] = $publications;
	    $data2['session_userid'] = $this->userobj->userid;
	    $pubsHTML .= $this->load_view('group/publications/user_publications',$data2,TRUE);
	}
	$data['page_title'] = "Group Publications $status_text";
	$data['pubsHTML'] = $pubsHTML;
	
	$this->load_view('group/publications/main',$data);
    }
    
    public function show($pub_id){
	
	$pub = $this->publication_model->get_publication($pub_id);
	
	$fileIDs = $this->get_file_ids($pub['file_ids']);
	$count = count($fileIDs);

	$fileData = array();
	if($count > 0) {
	    $i = 0;
	    foreach ($fileIDs as $file_id) {
		$fileData[$i]['id'] = $file_id;
		$fileData[$i]['link'] = $this->filemanager->get_file_url($file_id);
		$fileData[$i]['info'] = $file_info = $this->filemanager->get_file_info($file_id);
		$i++;
	    }
	}
	
	$data['page_title'] = "Publication Info <span style='margin-left:8px; font-weight:normal'>(ID: ".$pub_id.")</span>";
	$data['pub_id'] = $pub_id;
	$data['pub'] = $pub;
	$data['session_userid'] = $this->userobj->userid;
	$data['session_role'] = $this->userobj->role;
	$data['fileData'] = $fileData;

	$this->load_view('group/publications/show',$data);
    }
    
    public function add() {
	
	if (isset($_POST['add_publication_form'])){

	    if(!$this->check_form_input()) { // something missing so just return
		$data['page_title'] = 'Add Publication';
		$data['error'] = $this->error;
		$this->load_view('group/publications/add_publication_error',$data);
		return;
	    }
	    $data['userid'] = $this->userobj->userid;
	    $data['title']	= $this->input->post('title');
	    $data['authors']	= $this->input->post('authors');
	    $data['type']	= $this->input->post('type');
	    $data['status']	= $this->input->post('status');
	    $data['start_date'] = $this->input->post('start_date');
	    $data['modify_date'] = $this->input->post('start_date'); // just set it to start date
	    $data['end_date']	= $this->input->post('end_date');
	    $data['abstract']	= $this->input->post('abstract');
	    $data['comments']	= $this->input->post('comments');
	    $data['file_ids']	= ''; // left blank on purpose

	    $pub_id = $this->publication_model->add_publication($data);

	    redirect('group/publications/edit/'.$pub_id);
	} else {
	    
	    $data['page_title'] = 'Add Publication';
	    $data['user'] = $this->userobj;
	    
	    $this->load_view('group/publications/add_publication_form',$data);
	}
    }   
    
    public function edit($pub_id){
	
	if (isset($_POST['publication_edit_form'])){

	    if(!$this->check_form_input()) { // something missing so just return
		return;
	    }

	    $data['pub_id']	= $pub_id;
	    $data['title']	= $this->input->post('title');
	    $data['authors']	= $this->input->post('authors');
	    $data['type']	= $this->input->post('type');
	    $data['status']	= $this->input->post('status');
	    $data['start_date'] = $this->input->post('start_date');
	    $data['modify_date'] = $this->input->post('modify_date'); // just set it to start date
	    $data['end_date']	= $this->input->post('end_date');
	    $data['abstract']	= $this->input->post('abstract');
	    $data['comments']	= $this->input->post('comments');

	    $this->publication_model->update_publication($data);

	    redirect('group/publications/show/'.$pub_id);
	} else {
	    $publication = $this->publication_model->get_publication($pub_id);
	    $fileIDs = $this->get_file_ids($publication['file_ids']);
	    $count = count($fileIDs);

	    $fileData = array();
	    if($count > 0) {
		$i = 0;
		foreach ($fileIDs as $file_id) {
		    $fileData[$i]['id'] = $file_id;
		    $fileData[$i]['link'] = $this->filemanager->get_file_url($file_id);
		    $fileData[$i]['info'] = $file_info = $this->filemanager->get_file_info($file_id);
		    $i++;
		}
	    }
	    
	    $data['page_title'] = "Edit Publication <span style='margin-left:8px; font-weight:normal'>(ID: ".$pub_id.")</span>";
	    $data['pub'] = $publication;
	    $data['fileData'] = $fileData;
	    
	    $this->load_view('group/publications/edit_publication_form',$data);
	}
	
    }
    
    public function delete($pub_id){
	$pub = $this->publication_model->get_publication($pub_id);

	$this->publication_model->delete_publication($pub_id);

	// delete any files
	$files = $this->get_file_ids($pub['file_ids']);
	$count = count($files);
	if($count > 0) {
	    foreach ($files as $file_id) {
		$this->filemanager->delete_file($file_id);
	    }
	}

	redirect('group/publications');
    }
    
    public function add_file(){
	
	if (isset($_POST['add_publication_file_form'])){
	    
	    $pub_id = $this->input->post('publication_id');
	    $pub = $this->publication_model->get_publication($pub_id);

	    // copy new files
	    $table = $this->session->userdata('group')."_publications";
	    $file_id = $this->filemanager->upload_file(1, $table, $pub_id);
	    
	    $data['modify_date'] = getLISDate();
	    $data['file_ids'] = $pub['file_ids'].' '.$file_id.',';
	    $data['pub_id'] = $pub_id;
	    
	    // add file entry to the database
	    $this->publication_model->add_file($data);

	    redirect('group/publications/show/'.$pub_id);
	} else {
	    return;
	}
	
    }
    
    public function delete_file(){
	
	$pub_id	    = $this->input->post('publication_id');
	$file_id    = $this->input->post('file_id');
	
	$pub = $this->publication_model->get_publication($pub_id);

	// delete the file
	$this->filemanager->delete_file($file_id);

	// get the file_id string and delete the only that file_id
	$file_ids = $pub['file_ids'];
	$search = $file_id.',';
	$data['new_file_ids'] = str_replace($search, "", $file_ids); // replace with empty string

	$data['pub_id'] = $pub_id;
	$data['modify_date'] = getLISDate();
	$this->publication_model->delete_file($data);

	redirect('group/publications/show/'.$pub_id);
    }
    
    // function to check the form input
    protected function check_form_input() {
	
	$this->error = '';
	$title = $_POST['title']; // Users name
	$authors = $_POST['authors']; // PI name

	if(empty($title)) {
	    $this->error .= '<li>Please Enter a Title For This Publication</li>';
	}

	if(empty($authors)) {
	    $this->error .= '<li>Please Enter Author(s)</li>';
	}

	if(!empty($this->error)) {
	    return false;
	} else {
	    return true;
	}
    }
    
    protected function get_file_ids($file_ids) {
	$files = array();
	$file_ids = trim($file_ids, ",");
	if(!empty($file_ids)) {
	    $files = explode("\s*,\s*", $file_ids);
	}
	return $files;
    }
    
}