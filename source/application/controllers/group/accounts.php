<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Handles the functionality of group account management.
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis
 */
class Accounts extends Group_Controller {
    
    private $userobj = null;
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');
	$this->restrict_access();
    }
    
    /**
     * Handles a request for upgrading a group account 
     * (increase account's storage limit)
     */
    public function upgrade(){
	
        $this->load->model('account_model');
        
        if (isset($_POST['upgrade_form'])){
            if($this->check_upgrade_form()) {
              
                $data['name']         = $this->input->post('name'); // Users name
                $data['pi_name']      = $this->input->post('pi_name'); // PI name
                $data['email']        = $this->input->post('email');
                $data['pi_email']     = $this->input->post('pi_email');
                $data['phone']        = $this->input->post('phone'); // phone number to contact user
                $data['po_number']    = $this->input->post('po_number'); // po number
                $data['address']      = trim($this->input->post('address')); // institution adress
                $agree        = $this->input->post('agree'); // the group web page of the PI
                
                $storage = $this->input->post('storage'); // amount of storage requested
                
                $data['sale_type'] = $storage.'MB'; // the type of sale
                $data['date'] = $this->get_lis_date_time(); // get the current date and time
                $data['cost'] = $this->properties["storage.cost.".$storage.'MB']; // get cost of this storage
                $data['userid'] = $this->userobj->userid;
                $data['account_id'] = $this->properties['lis.account'];
                
                // add to the sales table
                $order_id = $this->account_model->update_sales($data) + 1000;  // get the sale ID and add 1000

                // now update the accounts table
                $data1['expire_date'] = $this->get_expire_date();
                $data1['status'] = 'premium';
                $data1['account_id'] = $this->properties['lis.account'];
                $data1['storage'] = $storage;
                $data1['cost'] = $data['cost'];
                $this->account_model->upgrade_account($data1);

                // update the initiation file
                $new_props = array(
                    'lis.expire' => $data1['expire_date'],
                    'lis.status' => $data1['status'],
                    'storage.quota' => $storage
                );
                
                $params['user'] = $this->userobj;
                $params['account'] = $this->session->userdata('group');
                $params['properties'] = $this->properties;
                // Load a FileManager model
                $this->load->model('filemanager');
                $this->filemanager->initialize($params);
                $this->filemanager->modify_initiation_file($new_props);

                // send email to confirm sale
                $sale_info = array($data['email'], $data['pi_email'], $data['name'], $order_id, $data['date'], $storage, $data['cost']);
                $this->send_confirm_email($sale_info);

                // display confirmation message now
                $data2['page_title'] = 'Account upgraded!';
                $data2['sale_info'] = $sale_info;
                $this->load_view('group/account/confirmation',$data2);
            } else {
                $data['page_title'] = 'Error message';
                $data['error_message'] = $this->error_message;
                $this->load_view('errors/error_and_back',$data);
            }
        } else {       
            $data['page_title'] = 'Upgrade your Account';
            $data['user'] = $this->userobj;
            $data['info'] = $this->account_model->getAccountInfo($this->properties['lis.account']);
            $data['cost1'] = $this->properties['storage.cost.200MB'];
            $data['cost2'] = $this->properties['storage.cost.1000MB'];
            $data['cost3'] = $this->properties['storage.cost.5000MB'];
            $this->load_view('group/account/upgrade_form',$data);
        }
    }
    
    /**
     * Activates the account 
     */
    public function activate() {
	$this->load->model('account_model');
	
	$activate_code = $this->input->post('activate_code');
	$account_id = $this->input->post('account_id');
	$main_page = $this->input->post('main_page'); // URL of main page

	$accountInfo = $this->account_model->get_account_info($account_id);
	$stored_code = $accountInfo['activate_code'];

	if($activate_code == $stored_code) {
	    $activate_date = $accountInfo['activate_date'];
	    $term = $accountInfo['term'];
	    $expire_date = $this->getExpireDate($activate_date, '', $term);

	    $this->account_model->activate_account($expire_date,$account_id);

	    $params['properties'] = $this->properties;
	    $params['user'] = $this->userobj;
	    $this->load->model('admin_filemanager');
	    $this->admin_filemanager->initialize($params);
	    
	    $props = array(
		'lis.expire' => $expire_date,
		'lis.status' => $expire_date,
	    );
	    $this->admin_filemanager->modifyInitiationFile($account_id, $props);

	    $main_page .='&activated=yes';
	}
	else {
	    $main_page .='&activated=no';
	}
	header("Location: $main_page");
    }
    
    /**
     * Displays information about the profile of the logged in user or updates
     * this information if the relevant form has been submited.
     */
    public function user_profile(){
	
	$base = base_url()."group/";
	
	// If an updated profile has been posted
	if ($this->input->post('profile_update_form')){
	    if($this->check_form_input()) {
		$data['name'] = $this->input->post('name'); // users fullname
		$data['password'] = $this->input->post('password'); // user password
		$data['email'] = $this->input->post('email'); // email
		$data['info'] = $this->input->post('info'); // additional information about the user
		$data['userid'] = $this->userobj->userid;
		
		$this->load->model('profile_model');
		$this->profile_model->update($data);

		// update the session data
		$this->load->model('user_model');
		$user = $this->user_model->get_user($this->userobj->userid);
		$this->session->set_userdata('user',$user);
		
		// now redirect group profile page
		redirect($base."accounts/user_profile");
	    }
	} else {

	    $this->load->model('user_model');
	    $password = $this->user_model->get_user_password($this->userobj->userid);
	    
	    $data['page_title'] = "My Profile";
	    $data['userid'] = $this->userobj->userid;
	    $data['name'] = $this->userobj->name;
	    $data['email'] = $this->userobj->email;
	    $data['info'] = $this->userobj->info;
	    $data['password'] = $password;
	    
	    $this->load_view('group/account/my_profile',$data);
	}
    }
    
    /**
     * Displays information about the group's profile or updates
     * this information if the relevant form has been submited.
     */
    public function group_profile(){
	
	$base = base_url()."group/";
	
	if ($this->input->post('group_profile_update_form')){
	    $userid = $this->userobj->userid;

	    if($this->check_group_form_input()) {
		$data['pi_name'] = $this->input->post('pi_name'); // PI first name
		$data['pi_email'] = $this->input->post('pi_email'); // PI email
		$data['url'] = checkURL($this->input->post('url')); // the group web page of the PI
		$data['keywords'] = $this->input->post('keywords'); // research keywords
		$data['description'] = trim($this->input->post('description')); // research decription
		$data['instruments'] = trim($this->input->post('instruments')); // list of instruments
		$data['edit_date'] = $this->get_lis_date_time();
		$data['userid'] = $userid;
		$data['group'] = $this->session->userdata('group');

		$this->load->model('profile_model');
		$this->profile_model->update_group($data);

		redirect($base."accounts/group_profile");
	    }
	} else {
	    $group = $this->session->userdata('group');

	    $this->load->model('profile_model');
	    $info = $this->profile_model->get_group_profile($group);
	    $edit_date = $info['edit_date'];
	    $edit_user = $info['userid'];

	    $this->load->model('user_model');
	    $editor = $this->user_model->get_user($edit_user);
	    
	    $data['page_title'] = "Group Research Profile";
	    $data['group'] = $group;
	    $data['info'] = $info;
	    $data['editor'] = $editor;
	    
	    $this->load_view('group/account/group_profile',$data);

	    
	}
    }
    
    /**
     * Validates the posted form before updating user's profile information
     * 
     * @return boolean
     */
    protected function check_form_input() {
	$error = '';

	$name = $this->input->post('name'); // PI first name
	$email = $this->input->post('email'); // PI email
	$password = $this->input->post('password');

	if(empty($name)) {
	    $error .= '<li>Please Enter Your Full Name</li>';
	}

	if(empty($email) || !valid_email($email)) {
	    $error .= '<li>Please Enter Valid Email Address </li>';
	}

	if(!$this->valid_password($password)) {
	    $error .= '<li>Please Enter Valid Password (6 or more characters) </li>';
	}

	if(!empty($error)) {
	    echo '<h3>The following value(s) were not entered, or the formating is 
	    incorrect. <br>Please use the back button and correct the values.</h3>
	    <ul style="list-style-type: square;"> '.$error.'</ul>';

	    return false;
	}
	else {
	    return true;
	}
    }
    
    /**
     * Validates the posted form before upgrading an account
     * 
     * @return boolean
     */
    protected function check_upgrade_form() {
      $error = '';

      $name         = $this->input->post('name'); // Users name
      $pi_name      = $this->input->post('pi_name'); // PI name
      $email        = $this->input->post('email');
      $pi_email     = $this->input->post('pi_email');
      $phone        = $this->input->post('phone'); // phone number to contact user
      $po_number    = $this->input->post('po_number'); // po number
      $address      = trim($this->input->post('address')); // institution adress
      $agree        = $this->input->post('agree'); // the group web page of the PI

      if(empty($name)) {
        $error .= '<li>Please Your Name</li>';
      }

      if(empty($pi_name)) {
        $error .= '<li>Please Enter PI\'s Name</li>';
      }

       if(empty($email) || !valid_email($email)) {
        $error .= '<li>Please Enter Valid Email Address </li>';
      }

      if(empty($pi_email) || !valid_email($pi_email)) {
        $error .= '<li>Please Enter Valid PI Email Address </li>';
      }

      if(empty($phone) || strlen($phone) < 10) {
        $error .= '<li>Please Enter Phone Number </li>';
      }

      if(empty($po_number)) {
        $error .= '<li>Please Enter P.O. Number</li>';
      }

      if(empty($address)) {
        $error .= '<li>Please Enter Billing Address</li>';
      }

      if(empty($agree)) {
        $error .= '<li>You Must Agree to the Terms In Order for Order to be Processed</li>';
      }

      if(!empty($error)) {
        $back_link =  base_url()."group/main";

        $this->error_message = '<h4><span style="background-color: rgb(255, 100, 100);">
        Error, the following value(s) were not entered, or the formating is 
        incorrect. Please <b><a href="'.$back_link.'">Go Back</a></b> and correct the values.</span></h4>
       <ul style="list-style-type: square;"> '.$error.'</ul>';
        return false;
      }
      else {
        return true;
      }
    }
    
    /**
     * Validates the posted form before updating group's profile information
     * 
     * @return boolean
     */
    protected function check_group_form_input() {
	$error = '';

	$pi_name     = $this->input->post('pi_name'); // PI first name
	$pi_email    = $this->input->post('pi_email'); // PI email
	$url	     = $this->input->post('url'); // the group web page of the PI
	$keywords    = $this->input->post('keywords'); // research keywords
	$description = trim($this->input->post('description')); // research decription

	if(empty($pi_name)) {
	    $error .= '<li>Please Enter PI\'s Name</li>';
	}

	if(empty($pi_email) || !valid_email($pi_email)) {
	    $error .= '<li>Please Enter Valid PI\'s Email Address </li>';
	}

	if(empty($keywords)) {
	    $error .= '<li>Please Enter One or More Keywords Seperated By Commas </li>';
	}

	if(empty($url)) {
	    $error .= '<li>Please Enter Group\'s or PI Webpage URL</li>';
	}

	if(!empty($error)) {
	    echo "<html>";
	    echo "<head>";
	    echo "<title>Profile Update Error</title>";
	    echo "</head>";
	    echo "<body bgcolor=\"white\">";
	    echo '<h3><span style="background-color: rgb(255, 100, 100);">
	    Error, the following value(s) were not entered, or the formating is 
	    incorrect. Please use the back button and correct the values.</span></h3>
	    <ul style="list-style-type: square;"> '.$error.'</ul>';
	    echo "</body>";
	    echo "</html>";

	    return false;
	} else {
	    return true;
	}
    }
    
    /**
     * Validates user's password
     * 
     * @param string $password
     * @return boolean
     */
    protected function valid_password($password) {
	$valid = true;
	if(strlen($password) < 6) {
	    return false;
	}
	return $valid;
    }
    
    /**
     * Calculates the new expiration date of an account that is being upgraded
     * 
     * @return string
     */
    public function get_expire_date() {
      $expire_date = '';
      $account_info = $this->account_model->getAccountInfo($this->properties['lis.account']);

      $old_expire_date = $account_info['expire_date'];
      $status = $account_info['status'];

      if($status == 'premium') {
        $timediff = $this->lis_tz[$this->properties['lis.timezone']];
        $days_remaining = getDaysRemaining($old_expire_date,$timediff);
        if($days_remaining <= 60) { // only if the days remaining are less than 60 then add a year
          $expire_date = addDaysToDate($old_expire_date, 365);
        }
      }
      else {
        $expire_date = addDaysToDate($this->get_lis_date(), 365);
      }
      return $expire_date;
    }
    
    /**
     * Sends a confirmation e-mail for an account upgrade
     * 
     * @param array $sale_info
     */
    protected function send_confirm_email($sale_info) {
      $subject  = 'MyLIS Account Upgraded';
      $headers = 'From: sales@mylis.net'."\r\n".
      'Reply-To: sales@mylis.net'."\r\n".
      'X-Mailer: PHP/'.phpversion();

      $body = "This is an automated response to inform you the MyLIS account has been upgraded. \n \n";
      $body .= "Order ID : $sale_info[3] \n";
      $body .= "Placed By : $sale_info[2] at $sale_info[4] \n";
      $body .= "New File Storage Limit : $sale_info[5]MB \n";
      $body .= 'Cost Per Year : $'.$sale_info[6].".00 \n \n";
      $body .= "As per the sales agreement, payment must be made within 30 ";
      $body .= "days after receipt of sales invoice, which will be sent to the billing address given. ";
      $body .= "The sale will be null and void otherwise and the account will revert back to ";
      $body .= "the old storage limit.\n \n";
      $body .= "If you have any questions, please contact sales@instras.com Please include your Order ID in email.";

      mail($sale_info[0], $subject, $body, $headers); // send mail to the person who placed order
      if($sale_info[0] != $sale_info[1]) {
        mail($sale_info[1], $subject, $body, $headers); // send email to the PI
      }

      // send email to sales@mylis.net
      mail('sales@mylis.net', $subject, $body, $headers);
    }
    
}