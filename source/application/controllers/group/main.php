<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends Group_Controller {
    
    private $userobj = null;
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');

	// Setup paramaters for initializing models below
	$params['user'] = $this->userobj;
	$params['account'] = $this->session->userdata('group');
	$params['properties'] = $this->properties;

	// Load a Message_model model
	$this->load->model('message_model');
	// Load a Proputil model
	$this->load->model('proputil_model');
	$this->proputil_model->initialize($params);
	// Load a FileManager model
	$this->load->model('filemanager');
	$this->filemanager->initialize($params);
	// Load the Google Ads class
	$this->load->library('google_ads',$params);
	
	//Load plugins
	$this->load->library('plugin_loader',$params);
	$this->filemanager->load_plugin_files();
	$pluginList = $this->plugin_loader->getPlugins();
	if(count($pluginList) > 0) {
	    $counter = 0;
	    foreach($pluginList as $key => $value) {
		$plugins[$counter]['plugin_name'] = $value[0];
		$plugins[$counter]['plugin_link'] = base_url().'group/plugins?'.$value[1];
		$counter++;
	    }
	}
    }
    
    public function index() {   
	
	$this->restrict_access();

	// Load necessery data for the view
	$data = array();
	$data['page_title'] = "Home Page";
	$data['fullname']	    = $this->userobj->name;
	$data['role']		    = $this->userobj->role;
	$data['group_name']	    = $this->session->userdata('group');
	$data['properties']	    = $this->properties;
	$data['quotaUsage']   = $this->filemanager->get_quota_usage();
	$data['menu_image']	    = base_url()."images/".$this->properties['background.image'];
	
	// If the user clicked on a message edit link there should be a message id in the URL
	// and in that case we should load the 'Edit Message' and not the 'Post Message' form
	if ($this->input->get('message_id'))
	    $data['messageForm']	    = $this->load_message_form($this->input->get('message_id'));
	else
	    $data['messageForm']	    = $this->load_message_form();

	if (isset($plugins))
		$data['plugins'] = $plugins; 
	
	$data['ads_html'] = $this->google_ads->displayAds();
	
	$this->load_view('group/main/main',$data);
    }
	
    // This function is a URL destination ( .../group/main/displayMessages ) and not
    // a helper function. This URL is used as an 'src' property for an iframe.
    public function display_messages() { 
	$userid = $this->userobj->userid;
	$status = $this->userobj->status;
	$expire = $this->properties['lis.expire'];
	if (isset($_GET['activated']))
	    $activated = $_GET['activated'];
	
	// Note: The messages (and so the HTML that is being returned by this
	// function) are being loaded inside an iFrame. So, we have a new
	// <head> section where we need to load the necessery CSS and Javascript
	$output = '';
	$output .= $this->load_view('group/main/iFrameHeader',null,TRUE);
	if($this->proputil_model->get_property('show.welcome.'.$userid) != 'no') {
	    $output .= $this->load_welcome($status);
	}
	if($status == 'trial' || isset($activated)) {
	    $output .= $this->load_activate_form($expire,$activated);
	}
	if($this->is_premium_expired()) {
	    $output .= $this->load_expired_message();
	}
	if(!$this->filemanager->has_space()) {
	    $output .= $this->load_quota_used_message();
	}
	$output .= $this->load_system_messages();
	$output .= $this->load_user_messages();
	$output .= $this->load_view('group/main/iFrameFooter',null,TRUE);
	echo $output;

    }
    
    // Display the Post/Edit message form
    function load_message_form($message_id=null) {
	
	// Setup the necessery data for the view
	$data['base'] = base_url()."group/";
	$data['account_id'] = $this->properties['lis.account'];
	$data['message_id'] = $message_id;
	
	if(!empty($message_id)) {
	    $this->load->model('message_model');
	    $data['messageItem'] = $this->message_model->get_message($message_id);
	} else {
	    $messageItem['url'] = '';
	    $messageItem['message'] = '';
	    $messageItem['file_id'] = '';
	    $data['messageItem'] = $messageItem;
	}
	// Return the view as a string
	$output = $this->load_view('group/main/messageForm',$data,true);	
	return $output;
    }
    
    // Function to display the first time login message
    function load_welcome($status) {
	$data['base'] = base_url()."group/";
	$role = $this->session->userdata('user')->role;
	$data['date'] = getLISDate();
	$data['hide_link'] = base_url()."group/messages/hide_welcome";
	$data['sales_link'] = base_url()."group/accounts/upgrade";
	$data['manage_link'] = base_url()."group/manage";
	$data['signup_link'] = '../../../signup.html';
	$data['help_link'] = 'http://docs.google.com/Doc?id=dg5bsrjs_28dqsgkk5m';

	if($status == 'demo') { // display demo message
	    $params = array(
		'user'  =>	$this->session->userdata('user')
	    );
	    $this->load->library('logger',$params);
	    $data['user_count'] = 14701  + $this->logger->getLoginCount();
	    $data['case'] = 'demo';
	}
	else if($role == 'admin') {
	    $data['case'] = 'admin';
	}
	else {
	    $data['case'] = 'other';
	}

	$output = $this->load_view('group/main/welcomeMessage',$data,true);
	return $output;
    }
    
     // display message informing users that the file sotrage space quota has been used up
    function load_quota_used_message() {
	$data['base'] = base_url()."group/";
	$data['sales_link'] = $base."accounts/upgrade";
	$data['quota'] = $this->properties['storage.quota'];

	$message = $this->load_view('group/main/quotaUsedMessage',$data,true);
	return $message;
    }

    // function to display messages. Only gets called if account is in trial mode
    function load_activate_form($expire, $activated) {
	$data['base'] = base_url()."group/";
	$data['date'] = getLISDate();
	$data['account_id'] = $this->properties['lis.account'];

	$form = $this->load_view('group/main/activateForm',$data,true);
	return $form;
    }
    
    // Checks if the message should be posted
    function should_be_posted($post_start, $post_end) {
	$decision = false;
	$timediff = $this->lis_tz[$this->properties['lis.timezone']];
	$days1 = getDaysRemaining($post_start,$timediff);
	$days2 = getDaysRemaining($post_end,$timediff);

	if($days1 <= 0 && $days2 >= 0) {
	    $decision = true;
	}
	return $decision;
    }

    // function to echo html code for a user's message 
    function load_user_table($messageItem) {
	//global $file_directory;
	
	$link = $messageItem['url'];
	$file_id = $messageItem['file_id'];
	$base = base_url()."group/";
	$userid = $messageItem['userid'];
	$message_id = $messageItem['message_id'];
	
	$data['base'] = $base;
	$data['date'] = $messageItem['date'];
	$data['message_id'] = $messageItem['message_id'];
	$data['message'] = $messageItem['message']; 

	$this->load->model('user_model');

	$data['poster'] = $this->user_model->get_user($messageItem['userid']);

	if(!empty($link)) {
	    $data['link'] = $link;
	} else {
	   $data['link'] = $link;
	}
	if(!empty($file_id)) {
	    $data['file_link'] = $this->filemanager->get_file_url($file_id);
	}

	if($this->userobj->userid == $userid || $this->userobj->role == 'admin') {
	    $data['edit_link'] = $base."main?message_id=".$message_id;
	}

	if(($this->userobj->userid == $userid) || ($this->userobj->role == 'admin')) {
	    $data['delete_link'] = $base."messages/delete/".$message_id;
	}
	
	$table = $this->load->view('group/main/userMessageTable',$data,true);

	return $table;
    }
    
    // function to display user messages
    function load_user_messages() {
	$messageList = $this->message_model->get_user_messages();
	$umessages = '';
	if(count($messageList)>0) {
	    foreach($messageList as $messageItem){
		$umessages .= $this->load_user_table($messageItem);
	    }
	}
	return $umessages;
    }
    
    // function to echo html code for a system message
    function load_system_table($messageItem) {
	$data['message'] = $messageItem['message'];
	$data['message_date'] = $messageItem['message_date'];
	$data['link'] = $messageItem['url'];

	$table = $this->load_view('group/main/systemMessageTable',$data,true);
	return $table;
    }
    
    // function to display system messages
    function load_system_messages() {

	$account_id = $this->session->userdata('group');
	$messageList = $this->message_model->get_system_messages($account_id);
	$smessages = '';
	
	if(count($messageList)>0) {
	    foreach($messageList as $messageItem){
		if($this->should_be_posted($messageItem['post_start'], $messageItem['post_end'])) {
		    $smessages .= $this->load_system_table($messageItem);
		}
	    }
	}
	return $smessages;
    }
    
    // function to display a message informing the user that the premium account
    // has expired
    function load_expired_message() {
	/*12/8/07 Code This function */
	$output = '';
	return $output;
    }

    // function to check if the premium account is about to expired and needs to be renewed
    function is_premium_expired() {
	/* Code 2/2/08 */
    }
    
}
