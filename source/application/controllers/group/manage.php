<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends Group_Controller {
    
    var $userobj = null;
    var $u_table = null;
    var $main_error = '';
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');
        $this->u_table = $this->properties['lis.account'].'_users';
    }
   
    public function index(){
        redirect('group/manage/users_main');
    }
    
    public function users_main(){
        $home_dir = CIPATH."/accounts/mylis_".$params['account']."/";
        
        $data1['page'] = 'user';
        $menuHTML = $this->load->view('group/manage/menubar',$data1,TRUE);
        
        $data2['home_dir'] = $home_dir;
        $data2['im_message'] = $this->session->userdata('im_message');
        $this->session->unset_userdata('im_message');
        $importForm = $this->load->view('group/manage/users_import_form',$data,TRUE);
        
        $data['menuHTML'] = $menuHTML;
        $data['importForm'] = $importForm;
        $data['users'] = $this->loadUsers();
        $data2['um_error'] = $this->session->userdata('um_error');
        $this->load_view('group/manage/users_main',$data);
    }
    
    public function users_import(){
        if (isset($_POST['users_import_form'])){
           
            $tmp_name = $_FILES['fileupload']['tmp_name'];
            $error = '';

            if(is_uploaded_file($tmp_name)) {
              $lc = 2; // keep tracks of the current line
              $ec = 0; // keep tracks of the entries add to the database

              $fp = fopen($tmp_name, "r") or die("Couldn't open $tmp_name");

              // read in each line then add to database skipping the first
              $line = fgets($fp, 1024); //skip the first line
              while(!feof($fp)) {
                $line = fgets($fp, 1024);
                $sa  = split("\t", $line);

                if(!empty($line)) {
                  $userid = trim($sa[0]);
                  $password = trim($sa[1]); // should really check password, but not doing so for migration issues
                  $role = trim($sa[2]); // has to be user, buyer, guest, or admin
                  $name = addslashes($sa[3]);
                  $email = trim($sa[4]);
                  $status = trim($sa[5]); // hasto be present or past
                  $info = addslashes($sa[6]);

                  // check that user id is correct if not return error message
                  if(strlen($userid) < 4) {
                    $error .= 'Warning, The "'.$userid.'" User ID is less than 4 characters<br>';
                  }

                  // if role is not correct fix it
                  if($role != 'user' && $role != 'buyer' && $role != 'guest' && $role != 'admin') {
                    $role = 'user'; // set it to be a regular user
                  }

                  if($status != 'present' && $status != 'past') {
                    $status = 'present'; // make this user current
                  }

                  // check to make sure this user id is not already in the system if it is then just delete it
                  $this->load->model('user_model');
                  $this->user_model->deleteUser($userid);

                  // add this entry to the database now
                  $data['userid'] = $userid;         $data['password'] = $password;
                  $data['role'] = $role;             $data['name'] = $name;
                  $data['email'] = $email;           $data['status'] = $status;
                  $data['info'] = $info;
                  $this->user_model->addUser($data);

                  $ec++; // increment the entry count
                }
                else { // line is empty so do nothing
                  //$error .= 'Error, unable to add entry on line '.$lc.'<br>';
                }
                $lc++;
              }

              fclose($fp); // close the file now
            }
            else {
              $ec = 'Error no file selected. 0';
            }

            // set message informing user that the data has been updated
            $message = $ec.' entries added to user database ...<br>'.$error;
            $this->session->set_userdata('im_message',$message);

            redirect('group/manage/users_main');
        } else {
            redirect('group/manage/users_main');
        }
    }
    
    public function users_add(){
        if (isset($_POST['user_add_form'])){
            if($this->checkFormInput()) {
                $data['$userid'] = $this->input->post('userid'); 
                $data['password'] = $this->input->post('password');
                $data['name'] = $this->input->post('name');
                $data['email'] = $this->input->post('email');
                $data['role'] = $this->input->post('role');
                $data['info'] = $this->input->post('info');
                $data['status'] = 'present';

                $this->load->model('user_model');
                $this->user_model->addUser($data);
            } else {
                $data['error_message'] = $this->error_message;
                $this->load_view('error/error_and_back',$data);
                return;
            }
        }
        redirect('group/manage/users_main');
    }
    
    public function userlist_modify(){
        if (isset($_POST['userlist_modify'])){
            $modify_task = $this->input->post('modify_task');
            $userids = $this->input->post('userids');

            $this->session->set_userdata('um_error',''); // clear any errors

            if(!empty($userids)) {
              foreach($userids as $userid) {
                if($modify_task == 'update') {
                  $this->updateUser($userid);
                }
                else { // remove user
                  $this->removeUser($userid);
                }
              }
            }

            $this->session->set_userdata('um_error',$this->main_error);
        }
        redirect('group/manage/users_main');
    }
    
    // function to check form input
    function checkFormInput() {
      $error = '';

      $userid = $this->input->post('userid'); 
      $password = $this->input->post('password');
      $name = $this->input->post('name');
      $email = $this->input->post('email');

      if(!$this->valid_userid($userid)) {
        $error .= '<li>Please Enter Valid User ID</li>';
      }

      if(empty($email) || !valid_email($email)) {
        $error .= '<li>Please Enter Valid Email Address </li>';
      }

      if(!$this->valid_password($password)) {
        $error .= '<li>Please Enter Valid Password (6 or more characters) </li>';
      }

      if(empty($name)) {
        $error .= '<li>Please Enter Valid Name</li>';
      }

      if(!empty($error)) {
        $this->error_message = '<h3><span style="background-color: rgb(255, 100, 100);">
        Error, the following value(s) were not entered, or the formating is 
        incorrect. Please use the back button and correct the values.</span></h3>
       <ul style="list-style-type: square;"> '.$error.'</ul>';
        return false;
      }
      else {
        return true;
      }
    }
    
    // function to make sure userid is valid
    function valid_userid($userid) {
      global $users;

      if(strlen($userid) < 3 || isset($users[$userid])) { // make sure userid is not empty or already in list
        return false;
      }
      else {
        return true;
      }
    }

    // function to check to see if the password is valid
    function valid_password($password) {
      $valid = true;
      if(strlen($password) < 4) {
        return false;
      }

      return $valid;
  }
}