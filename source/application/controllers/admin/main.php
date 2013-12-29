<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends Admin_Controller {

    private $userobj = null;
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');
	
	$params['user'] = $this->userobj;
	$params['account'] = $this->session->userdata('group');
	$params['properties'] = $this->properties;

	// Load a Proputil model
	$this->load->model('proputil_model');
	$this->proputil_model->initialize($params);
    }
    
    public function index()
    {
	$this->restrict_access();
	
	// Load necessery data for the view
	$data = array();
	$data['page_title']	    = "Home Page";
	$data['properties']	    = $this->properties;
	$data['fullname']	    = $this->userobj->name;
	$data['role']		    = $this->userobj->role;
	$data['menu_image']	    = base_url()."images/".$this->properties['background.image'];
	$data['messages_html'] = $this->displayMessages();
	$data['user'] = $this->userobj;
	
	$this->load_view('admin/main',$data);
    }
    
     function displayMessages() {
	$output = "<br><br>Sorry ".$this->userobj->name.", no messages for you<br>";
	return $output;
    }
    
}