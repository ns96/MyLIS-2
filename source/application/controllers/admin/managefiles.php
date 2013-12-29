<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Managefiles extends Admin_Controller {
    
    private $userobj = null;
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');
    }
    
    public function index(){
	$data['page_title'] = 'MyLIS File Manager';
	$this->load_view('admin/managefiles/main',$data);
    }
    
    
}