<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Handles the functionality of the Admin area's main page
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis
 */
class Main extends Admin_Controller {

    private $userobj = null;
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');
	
	$this->restrict_access();
    }
    
    /**
     * Loads the admin main page
     */
    public function index()
    {
	// Load necessery data for the view
	$data = array();
	$data['page_title']	    = "Home Page";
	$data['properties']	    = $this->properties;
	$data['fullname']	    = $this->userobj->name;
	$data['role']		    = $this->userobj->role;
	$data['menu_image']	    = base_url()."images/".$this->properties['background.image'];
	$data['messages_html'] = $this->display_messages();
	$data['user'] = $this->userobj;
	
	$this->load_view('admin/main/main',$data);
    }
    
    /**
     * Dummy implementation for displaying messages
     * 
     * @return string
     */
    protected function display_messages() {
	$output = "<br><br>Sorry ".$this->userobj->name.", no messages for you<br>";
	return $output;
    }
    
}