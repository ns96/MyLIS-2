<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Weblinks extends Group_Controller {
    
    private $userobj;
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');
    }
    
    public function index($link_id=null){
    
	if (isset($_GET['mylinks']) && ($_GET['mylinks'] == 'yes')){
	    $mylinks = true;
	} else {
	    $mylinks = false;
	}
	
	// initialize some links
	$data['add_link'] = base_url().'group/weblinks#add'; // the add link
	$data['myLinks'] = encodeURL(base_url()."group/weblinks?mylinks=yes"); // list only the users web links
	$data['listAll'] = encodeURL(base_url()."group/weblinks"); // list all links
	$data['home_link'] = encodeURL(base_url()."group/main");

	$this->load->model('weblinks_model');
	// Load the weblink categories
	$categories = $this->weblinks_model->get_categories();
	
	$linksHTML = '';
	// Get the display view for each category
	foreach($categories as $key => $category) {
	    $cat_id = $this->get_category_id($key);
	    $linksHTML .= $this->display_by_category($cat_id, $category, $mylinks);
	}
	$data['linksHTML'] = $linksHTML;
	
	$data2['categories'] = $categories;
	if(!empty($link_id)) { // If we are editing a weblink load weblinks's data
	    $data2['link_id'] = $link_id;
	    $data2['weblinkItem'] = $this->weblinks_model->get_weblink($link_id);
	    $data2['target_link'] = base_url()."group/weblinks/update";
	    $data['addForm'] = $this->load_view('group/weblinks/weblinkEditForm',$data2,TRUE);
	} else { // otherzise, we are adding a new weblink
	    $data2['target_link'] = base_url()."group/weblinks/add";
	    $data['addForm'] = $this->load_view('group/weblinks/weblinkAddForm',$data2,TRUE);
	}
	
	//
	$data['page_title'] = 'Web Link Repository';
	$this->load_view('group/weblinks/displayWeblinkCategories',$data);
    }
    
    // Handles the posting of a new weblink
    public function add(){
	$title		= $this->input->post('title');
	$url		= $this->input->post('url');
	$category	= $this->input->post('category');
	$other_category = $this->input->post('other_category');

	// Save the new weblink as long as the url is not empty
	if(!empty($url)) {
	    $data['userid'] = $this->userobj->userid;
	    $data['cat_id'] = $this->get_category_id($category);
	    $data['url'] = checkURL($url);
	    if(empty($title)) {
		$title = $url;
	    }
	    $data['title'] = $title;
	    
	    $this->load->model('weblinks_model');
	    
	    // If the user posted the name of a new weblink category add the 
	    // category to the database before saving the new weblink
	    if(!empty($other_category)) {
		$cat_id = $this->weblinks_model->add_category($other_category,$this->userobj->userid);
		$data['category'] = $cat_id;
	    } else {
		$data['category'] = $category;
	    }
	    // Save the new weblink
	    $this->weblinks_model->add($data);
	}

	redirect('group/weblinks');
    }
    
    // Handles the posting of weblinks edited data
    public function update(){
	if (isset($_POST['weblink_edit_form'])){
	    $link_id	    = $this->input->post('link_id');
	    $title	    = $this->input->post('title');
	    $url	    = $this->input->post('url');
	    $category	    = $this->input->post('category');
	    $other_category = $this->input->post('other_category');

	    if(!empty($url)) {
		$cat_id = $this->get_category_id($category);
		$url = checkURL($url);

		if(empty($title)) {
		    $title = $url;
		}
		
		$this->load->model('weblinks_model');
		if(!empty($other_category)) {
		    $cat_id = $this->weblinks_model->add_category($other_category);
		}
		
		$data['link_id'] = $link_id;
		$data['title'] = $title;
		$data['url'] = $url;
		$data['cat_id'] = $cat_id;
		$this->weblinks_model->update($data);
	    }
	    redirect('group/weblinks');
	} else {
	    redirect('group/weblinks');
	}
    }
    
    // Handles requests for deleting a weblink
    public function delete($link_id){
	 $this->load->model('weblinks_model');
	 $this->weblinks_model->delete($link_id);
	 
	 redirect('group/weblinks');
    }
    
    // Return the HTML the lists the weblinks of a category
    private function display_by_category($category_id,$category,$mylinks){
	$userid = $this->userobj->userid;
	$role = $this->userobj->role;

	// The $mylinks parameter defines whether we are listing all the group's weblinks
	// that belong to a category or just the PI's weblinks
	if ($mylinks) {
	    $weblinkList = $this->weblinks_model->get_category_my_weblinks($category_id,$userid);
	} else {
	    $weblinkList = $this->weblinks_model->get_category_weblinks($category_id);
	}
	
	// For each category, we load the relevant HTML that lists its weblinks
	$output ='';
	if(count($weblinkList)>0) {
	    $data['userid'] = $userid;
	    $data['role'] = $role;
	    $data['weblinkList'] = $weblinkList;
	    $data['category'] = $category;
	    $output .= $this->load_view('group/weblinks/categoryWeblinks',$data,TRUE);
	}
	
	return $output; 
    }
    
    private function get_category_id($cat_id) {
	$array = explode('_', $cat_id);
	return $array[1];
    }
}