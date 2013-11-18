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
	$this->filemanager->loadPluginFiles();
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
	$data['fullname']	    = $this->userobj->name;
	$data['role']		    = $this->userobj->role;
	$data['group_name']	    = $this->session->userdata('group');
	$data['properties']	    = $this->properties;
	$data['storageQuotaText']   = $this->filemanager->getStorageQuotaText();
	$data['menu_image']	    = base_url()."images/".$this->properties['background.image'];
	// If the user clicked on a message edit link there should be a message id in the URL
	// and in that case we should load the 'Edit Message' and not the 'Post Message' form
	if ($this->input->get('message_id'))
	    $data['messageForm']	    = $this->loadMessageForm($this->input->get('message_id'));
	else
	    $data['messageForm']	    = $this->loadMessageForm();
	
	if (isset($plugins))
		$data['plugins'] = $plugins; 
	$data['ads_html'] = $this->google_ads->displayAds();
	
	$this->load->view('group/main',$data);

    }
	
    // This function is a URL destination ( .../group/main/displayMessages ) and not
    // a helper function. This URL is used as an 'src' property for an iframe.
    public function displayMessages() {
	$userid = $this->userobj->userid;
	$status = $this->userobj->status;
	$expire = $this->properties['lis.expire'];
	if (isset($_GET['activated']))
	    $activated = $_GET['activated'];
	$output = '';
	
	if($this->proputil_model->getProperty('show.welcome.'.$userid) != 'no') {
	    $output .= $this->loadWelcome($status);
	}
	if($status == 'trial' || isset($activated)) {
	    $output .= $this->loadActivateForm($expire,$activated);
	}
	if($this->isPremiumExpired()) {
	    $output .= $this->loadExpiredMessaged();
	}
	if(!$this->filemanager->hasSpace()) {
	    $output .= $this->loadQuotaUsedMessage();
	}
	
	$output .= $this->loadSystemMessages();
	$output .= $this->loadUserMessages();
	
	echo $output;
    }
    
    // Display the Post/Edit message form
    function loadMessageForm($message_id=null) {
	
	// Setup the necessery data for the view
	$data['base'] = base_url()."group/";
	$data['account_id'] = $this->properties['lis.account'];
	$data['message_id'] = $message_id;
	
	if(!empty($message_id)) {
	    $data['title'] = 'Edit Message ('.$message_id.')';
	    $this->load->model('message_model');
	    $data['messageItem'] = $this->message_model->getMessage($message_id);
	    $data['target_url'] = base_url()."group/messages/edit";
	} else {
	    $data['title'] = 'Post Message';
	    $data['target_url'] = base_url()."group/messages/add";
	    $messageItem['url'] = '';
	    $messageItem['message'] = '';
	    $messageItem['file_id'] = '';
	    $data['messageItem'] = $messageItem;
	}
	// Return the view as a string
	$output = $this->load->view('group/messageForm',$data,true);	
	return $output;
    }
    
    // Function to display the first time login message
    function loadWelcome($status) {
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

	$output = $this->load->view('group/welcomeMessage',$data,true);
	return $output;
    }
    
     // display message informing users that the file sotrage space quota has been used up
    function loadQuotaUsedMessage() {
	$data['base'] = base_url()."group/";
	$data['sales_link'] = $base."accounts/upgrade";
	$data['quota'] = $this->properties['storage.quota'];

	$message = $this->load->view('group/quotaUsedMessage',$data,true);
	return $message;
    }

    // function to display messages. Only gets called if account is in trial mode
    function loadActivateForm($expire, $activated) {
	$data['base'] = base_url()."group/";
	$data['date'] = getLISDate();
	$data['account_id'] = $this->properties['lis.account'];

	$form = $this->load->view('group/activateForm',$data,true);
	return $form;
    }
    
    // Checks if the message should be posted
    function shouldBePosted($post_start, $post_end) {
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
    function loadUserTable($messageItem) {
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
	$data['poster'] = $this->user_model->getUser($messageItem['userid']);
	
	if(!empty($link)) 
	    $data['link'] = $link;

	if(!empty($file_id)) {
	    $data['file_link'] = $this->filemanager->getFileURL($file_id);
	}

	if($this->userobj->userid == $userid || $this->userobj->role == 'admin') {
	    $data['edit_link'] = $base."main?message_id=".$message_id;
	}

	if(($this->userobj->userid == $userid) || ($this->userobj->role == 'admin')) {
	    $data['delete_link'] = $base."messages/delete/".$message_id;
	}

	$table = $this->load->view('group/userMessageTable',$data,true);
	return $table;
    }
    
    // function to display user messages
    function loadUserMessages() {

	$this->load->model('message_model');
	$messageList = $this->message_model->getUserMessages();
	$umessages = '';

	if(count($messageList)>0) {
	    foreach($messageList as $messageItem){
		$umessages .= $this->loadUserTable($messageItem);
	    }
	}
	return $umessages;
    }
    
    // function to echo html code for a system message
    function loadSystemTable($messageItem) {
	$data['message'] = $messageItem['message'];
	$data['message_date'] = $messageItem['message_date'];
	$data['link'] = $messageItem['url'];

	$table = $this->load->view('group/systemMessageTable',$data,true);
	return $table;
    }
    
    // function to display system messages
    function loadSystemMessages() {

	$account_id = $this->session->userdata('group');
	
	$this->load->model('message_model');
	$messageList = $this->message_model->getSystemMessages($account_id);
	$smessages = '';
	
	if(count($messageList)>0) {
	    foreach($messageList as $messageItem){
		if($this->shouldBePosted($messageItem['post_start'], $messageItem['post_end'])) {
		    $smessages .= $this->loadSystemTable($messageItem);
		}
	    }
	}
	return $smessages;
    }
    
    // function to display a message informing the user that the premium account
    // has expired
    function loadExpiredMessage() {
	/*12/8/07 Code This function */
	$output = '';
	return $output;
    }

    // function to check if the premium account is about to expired and needs to be renewed
    function isPremiumExpired() {
	/* Code 2/2/08 */
    }
    
}
