<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File_folder extends Group_Controller {
    
    var $userobj = null;
    var $myfiles = '';
    
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
        
        // Load a File_folder model
        $this->load->model('file_folder_model');
    }
    
    public function index(){
        
        $filter = $this->input->get('filter');
        if ($filter == 'myfiles') $this->myfiles = 'yes';
        
        $userid = $this->userobj->userid;
        $role = $this->userobj->role;
        
        $categories = $this->file_folder_model->getCategories('filing'); // get the filing categeries user defined
        
        $linksHTML = '';
        // display links by categories
        foreach($categories as $key => $category) {
            $cat_id = getCategoryID($key);

            // get file database and links
            $categoryLinks = $this->file_folder_model->getLinks($cat_id,$this->myfiles,$userid);

            if(count($categoryLinks) >= 1) {
              $linksHTML .= '<div style="margin:0px 15px;"><table id="file_folder_table" class="table table-bordered table-condensed">';
	      $linksHTML .= "<caption>".$category."<img src='".base_url()."images/icons/pdfs.png' class='icon' title='back'/></caption>";
              $linksHTML .= '<thead><tr><th width="5%" style="text-align:center">#</th><th width="80%">File Title</th><th style="text-align:center">Actions</th></tr></thead><tbody>';

              $i = 1;
              foreach($categoryLinks as $array) {
		$linksHTML .= "<tr>";
                $file_id = $array['file_id'];
                $file_info = $this->filemanager->getFileInfo($file_id);

                 $linksHTML .= '<td>'.$i.'</td><td>'.$array['title'].'</td><td>';

                $download_link = $this->filemanager->getFileURL($array['file_id']);
		
                if($file_info['type'] != 'url') {
                   $linksHTML .= "<a href='$download_link'><img src='".base_url()."images/icons/download2.png' class='icon' title='download'/></a>";
                }
                else {
                   $linksHTML .= "<a href='$download_link'><img src='".base_url()."images/icons/download2.png' class='icon' title='download'/></a>";
                }

                if($userid == $array['userid'] || $role == 'admin') {
                  $edit_link = base_url()."group/file_folder?file_id=$file_id";
                   $linksHTML .= "<a href='$edit_link'><img src='".base_url()."images/icons/edit.png' class='icon' title='edit'/></a>";
                }

                if($userid == $array['userid'] || $role == 'admin') {
                  $delete_link = base_url()."group/file_folder/deleteFile?file_id=$file_id";
                   $linksHTML .= "<a href='$delete_link'><img src='".base_url()."images/icons/delete.png' class='icon' title='delete'/></a>";
                }
                $i++;
		$linksHTML .= "</td></tr>";
              }
               $linksHTML .= '</tbody></table></div>';
            }
        }
        
        $data1['account_id'] = $this->properties['lis.account'];
        $data1['categories'] = $categories;
        $file_id = $this->input->get('file_id');
        if(!empty($file_id)) {
            $data1['title'] = 'Edit File';
            $data1['task'] = 'folder_edit';
            $data1['file_id'] = $file_id;
            $data1['fileInfo'] = $this->file_folder_model->getFile($file_id);
        } else {
            $data1['title'] = 'Add File';
            $data1['task'] = 'folder_add';
            $data1['file_id'] = '';
        }
        
        $data1['urlUploadField'] = $this->filemanager->getURLFileUploadField(1);
        $data['addForm'] = $this->load->view('group/file_folder/addForm',$data1,TRUE);
        
        $data['page_title'] = 'Group Files and Folders';
        $data['categories'] = $categories;
        $data['linksHTML'] = $linksHTML;
	$this->load_view('group/file_folder/main',$data);
    }
    
    public function addfile(){
        $userid = $this->userobj->userid;

        if($this->checkFormInput()) {
          $title = $this->input->post('title');
          $category = $this->input->post('category');
          $other_category = $this->input->post('other_category');

          $cat_id = getCategoryID($category);
          if(!empty($other_category)) {
            $cat_id = $this->file_folder_model->addCategory('filing', $other_category,$userid);
          }

          // add the file inorder to get the file ID
          $table_name = $this->session->userdata('group').'_folder_files';
          $file_id = $this->filemanager->uploadFile(1, $table_name, 0);

          $data['file_id'] = $file_id;
          $data['title'] = $title;
          $data['userid'] = $userid;
          $data['cat_id'] = $cat_id;
          $this->file_folder_model->addFile($data);

          redirect('group/file_folder');
        } else {
            $data['error_message'] = $this->error_message;
            $this->load_view('error/error_and_back',$data);
            return;
        }
    }
    
    public function editFile(){
        if($this-> checkFormInput()) {
          $file_id = $this->input->post('file_id');
          $title = $this->input->post('title');
          $category = $this->input->post('category');
          $other_category = $this->input->post('other_category');

          $cat_id = getCategoryID($category);
          if(!empty($other_category)) {
            $cat_id = $this->file_folder_model->addCategory('filing', $other_category,$userid);
          }

          $this->file_folder_model->updateFile($title,$cat_id,$file_id);
          $this->filemanager->updateFile(1, $file_id);

          redirect('group/file_folder');
        } else {
            $data['error_message'] = $this->error_message;
            $this->load_view('error/error_and_back',$data);
            return;
        }
    }
    
    public function deleteFile(){
        $file_id = $this->input->get('file_id');
        $this->filemanager->deleteFile($file_id);  // delete the file
        $this->file_folder_model->deleteFile($file_id);

        redirect('group/file_folder');
    }
    
    // function to check the form input
    function checkFormInput() {
      $error = '';

      $title = $this->input->post('title'); // Users name

      if(empty($title)) {
        $error .= '<li>Please Enter a Title For This File</li>';
      }

      if(!empty($error)) {
        $this->error_message = '<h4><span style="background-color: rgb(255, 100, 100);">
        Error, the following value(s) were not entered, or the formating is 
        incorrect. Please Use The Back Button and correct the values.</span></h4>
        <ul style="list-style-type: square;"> '.$error.'</ul>';

        return false;
      }
      else {
        return true;
      }
    }
}