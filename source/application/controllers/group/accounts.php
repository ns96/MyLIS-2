<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accounts extends Group_Controller {
    
    private $userobj = null;
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');
    }
    
    public function user_profile(){
	$base = base_url()."group/";
	$post_link = $base."accounts/user_profile";
	
	if ($this->input->post('profile_update_form')){
	    if($this->checkFormInput()) {
		$data['name'] = $this->input->post('name'); // users fullname
		$data['password'] = $this->input->post('password'); // user password
		$data['email'] = $this->input->post('email'); // email
		$data['info'] = $this->input->post('info'); // additional information about the user
		$data['userid'] = $this->userobj->userid;

		$this->load->model('profile_model');
		$this->profile_model->update($data);

		// now redirect group profile page
		redirect($base."accounts/user_profile");
	    }
	} else {
	    $ok_link = $base."main";

	    $this->load->model('user_model');
	    $password = $this->user_model->getUserPassword($this->userobj->userid);
	    
	    echo "<form action=\"$post_link\" method=\"POST\">";
	    echo "<input type='hidden' name='profile_update_form' value='posted' />";
	    echo '<table style="text-align: left; width: 100%;" border="0" cellpadding="2" 
	    cellspacing="2"><tbody>
	    <tr>
	    <td style="background-color: rgb(180,200,230);" colspan="2" rowspan="1"><small>
	    <span style="font-weight: bold;">Update Your Profile</span></small></td></tr>';

	    echo '
	    </tr>
	    <tr>
	    <td>User ID :</td>
	    <td><b>'.$this->userobj->userid.'</b></td>
	    </tr>
	    </tr>
	    <tr>
	    <td>Full Name :</td>
	    <td><input size="50" name="name" value="'.htmlentities($this->userobj->name).'"></td>
	    </tr>
	    <tr>
	    <td>Password :</td>
	    <td><input size="50" name="password" value="'.$password.'"></td>
	    </tr>
	    <tr>
	    <td>E-mail :</td>
	    <td><input size="50" name="email" value="'.$this->userobj->email.'"></td>
	    </tr>
	    <tr>
	    <td>Additonal Info:</td>
	    <td><input size="50" name="info" value="'.$this->userobj->info.'"></td>
	    </tr>';

	    echo '<tr>';
	    echo '<td><br></td>';
	    echo '<td style="text-align: right;">';
	    echo "[ <a href=\"$ok_link\" target=\"_parent\">OK</a> ] ";
	    echo '<input value="Update Profile" type="submit" 
	    style="background: rgb(238, 238, 238); color: rgb(51, 102, 255)"></td>';
	    echo '</tr>';
	    echo '</tbody></table>';

	    printColoredLine('rgb(180,200,230)', '2px');
	}
    }
    
    public function group_profile(){
	$base = base_url()."group/";
	
	if ($this->input->post('group_profile_update_form')){
	    $userid = $this->userobj->userid;

	    if($this->checkGroupFormInput()) {
		$data['pi_name'] = $this->input->post('pi_name'); // PI first name
		$data['pi_email'] = $this->input->post('pi_email'); // PI email
		$data['url'] = checkURL($this->input->post('url')); // the group web page of the PI
		$data['keywords'] = $this->input->post('keywords'); // research keywords
		$data['description'] = trim($this->input->post('description')); // research decription
		$data['instruments'] = trim($this->input->post('instruments')); // list of instruments
		$data['edit_date'] = getLISDateTime();
		$data['userid'] = $userid;
		$data['group'] = $this->session->userdata('group');

		$this->load->model('profile_model');
		$this->profile_model->update_group($data);

		redirect($base."accounts/group_profile");
	    }
	} else {
	    $post_link = $base."accounts/group_profile";
	    $ok_link = $base."main";
	    $group = $this->session->userdata('group');

	    $this->load->model('profile_model');
	    $info = $this->profile_model->getGroupProfile($group);
	    $edit_date = $info['edit_date'];
	    $edit_user = $info['userid'];

	    $this->load->model('user_model');
	    $editor = $this->user_model->getUser($edit_user);

	    echo "<form action=\"$post_link\" method=\"POST\">";
	    echo "<input type='hidden' name='group_profile_update_form' value='posted' />";
	    echo '<table style="text-align: left; width: 100%;" border="0" cellpadding="2" 
	    cellspacing="2"><tbody>
	    <tr>
	    <td style="background-color: rgb(100,255,100);" colspan="2" rowspan="1"><small>
	    <span style="font-weight: bold;">Update Group Research Profile</span> ';
	    echo '|| Last updated on '.$edit_date.' by '.$editor->name.'</small></td></tr>';

	    echo '
	    </tr>
	    <tr>
	    <td>Group ID :</td>
	    <td><b>'.$group.'</b></td>
	    </tr>
	    </tr>
	    <tr>
	    <td>PI Name :</td>
	    <td><input size="50" name="pi_name" value="'.htmlentities($info['pi_name']).'"></td>
	    </tr>
	    <tr>
	    <td>PI Email :</td>
	    <td><input size="50" name="pi_email" value="'.$info['pi_email'].'"></td>
	    </tr>
	    <tr>
	    <td>Group Site URL :</td>
	    <td><input size="50" name="url" value="'.$info['url'].'"></td>
	    </tr>
	    <tr>
	    <td>Research Keywords :</td>
	    <td><input size="50" name="keywords" value="'.$info['keywords'].'"></td>
	    </tr>
	    <tr>
	    <td style="vertical-align: top;">Research Description :</td>
	    <td><textarea cols="50" rows="6" name="description">'.$info['description'].'</textarea></td>
	    </tr>
	    <tr>
	    <td style="vertical-align: top;">Instrument List :</td>
	    <td><textarea cols="50" rows="6" name="instruments">'.$info['instruments'].'</textarea></td>
	    </tr>';

	    echo '<tr>';
	    echo '<td><br></td>';
	    echo '<td style="text-align: right;">';
	    echo "[ <a href=\"$ok_link\" target=\"_parent\">OK</a> ] ";
	    echo '<input value="Update Profile" type="submit" 
	    style="background: rgb(238, 238, 238); color: rgb(51, 102, 255)"></td>';
	    echo '</tr>';
	    echo '</tbody></table>';

	    printColoredLine('rgb(100,255,100)', '2px');
	}
    }
    
    // function to check the form input when editing a user information
    private function checkFormInput() {
	$error = '';

	$name = $this->input->post('name'); // PI first name
	$email = $this->input->post('email'); // PI email
	$password = $this->input->post('password');

	if(empty($name)) {
	    $error .= '<li>Please Enter Your Full Name</li>';
	}

	if(empty($email) || !$this->valid_email($email)) {
	    $error .= '<li>Please Enter Valid Email Address </li>';
	}

	if(!$this->valid_password($password)) {
	    $error .= '<li>Please Enter Valid Password (6 or more characters) </li>';
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
	}
	else {
	    return true;
	}
    }
    
    // function to check the input form data
    private function checkGroupFormInput() {
	$error = '';

	$pi_name     = $this->input->post('pi_name'); // PI first name
	$pi_email    = $this->input->post('pi_email'); // PI email
	$url	     = $this->input->post('url'); // the group web page of the PI
	$keywords    = $this->input->post('keywords'); // research keywords
	$description = trim($this->input->post('description')); // research decription

	if(empty($pi_name)) {
	    $error .= '<li>Please Enter PI\'s Name</li>';
	}

	if(empty($pi_email) || !$this->valid_email($pi_email)) {
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

    // used to check to see if we have an email address
    private function valid_email($email) {
	// First, we check that there's one @ symbol, and that the lengths are right
	if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
	    // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
	    return false;
	}
	// Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) {
	    if (!preg_match("@^(([A-Za-z0-9!#$%&#038;'*+/=?^_`{|}~-][A-Za-z0-9!#$%&#038;'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$@", $local_array[$i])) {
		return false;
	    }
	}  
	if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
	    $domain_array = explode(".", $email_array[1]);
	    if (sizeof($domain_array) < 2) {
		return false; // Not enough parts to domain
	    }
	    for ($i = 0; $i < sizeof($domain_array); $i++) {
		if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
		    return false;
		}
	    }
	}
	return true;
    }
    
    // function to check to see if the password is valid
    private function valid_password($password) {
	$valid = true;
	if(strlen($password) < 6) {
	    return false;
	}
	return $valid;
    }
}