<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chemicals extends Group_Controller {
    
    private $userobj = null;
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');
	$this->restrict_access();
    }
    
    public function index(){
	
	$this->load->model('chemicals_model');
	
	$data['chem_id'] = $this->input->get('chem_id');
	$data['message'] = $this->input->get('message');
	
	$data['user'] = $this->userobj;
	$data['users'] = $this->get_current_users();
	
	$data['locations'] = $this->chemicals_model->get_locations();
	$data['categories'] = $this->chemicals_model->get_categories();
	
	$data['page_title'] = 'Search Chemical Inventory';
	$this->load_view('group/chemicals/main',$data);
    }
    
    // Handles the 'ownership transfer' or 'view info' of multiple chemical items
    public function bulk_actions(){
	if (isset($_POST['listing_form'])){
	    $this->load->model('chemicals_model');
	    $userid = $this->userobj->userid;
	    
	    // Find chemical id's to act upon
	    if(isset($_POST["all"])) {
		$chem_ids = preg_split("/[\s,]+/", $this->input->post("all"));
		$length = count($chem_ids);
		unset($chem_ids[$length - 1]);
	    }
	    else {
		$chem_ids = $this->input->post("chem_ids");
	    }
	    
	    $task = $this->input->post('group_task');
	    // Desired group action is 'TRANSFER'
	    if ($task == 'transfer'){
		if(!empty($chem_ids)) {
		    $message = '';
		    foreach($chem_ids as $chem_id) {
			$data2['chem_id'] = $chem_id;
			$data2['userid'] = $userid;
			$this->chemicals_model->transfer_single_ownership($data2);
			   $message .= '<small>Transferred Ownership of Chemical With ID <b>'.$chem_id.'</b></small><br>';
		    }
		}
		// Show message about the transfered chemicals and redirect
		$destination = base_url().'group/chemicals';
		showModal('Ownership transfer',$message,$destination);
	    } // Desired group action is 'VIEW'
	    elseif($task == 'view'){

		$data['back_link'] = base_url().'group/chemicals';
		$data['title'] = 'Chemical Info';
		$data['page_title'] = 'Chemical Information';
		$data['infoHTML'] = '';

		$data2['users'] = $this->get_current_users();
		$data2['userid'] = $this->userobj->userid;
		$data2['role'] = $this->userobj->role;
		
		if(!empty($chem_ids)) {
		    foreach($chem_ids as $chem_id) {
			$data2['chemInfo'] = $this->chemicals_model->get_info($chem_id);
			$data2['locationInfo'] = $this->chemicals_model->get_full_location($data2['chemInfo']['location_id']);
			$data['infoHTML'] .= $this->load->view('group/chemicals/info_table',$data2,TRUE);
			$data['infoHTML'] .= "<br><br>";
		    }
		}
		$this->load_view('group/chemicals/info',$data);
	    }
	} else {
	    redirect('group/chemicals');
	}
    }
    
    public function change_status($newStatus){
	if (isset($_GET['chem_id'])){
	    $this->load->model('chemicals_model');
	    
	    $data['chem_id'] = $this->input->get('chem_id');
	    $data['userid'] = $this->userobj->userid;
	    $data['status_date'] = getLISDate();

	    $status = '';
	    $sql = '';
	    switch($newStatus){
		case 'in-stock':
		    $statusText = 'In Stock';
		    break;
		case 'out-of-stock':
		    $statusText = 'Out of Stock';
		    break;
		case 'checked-out':
		    $statusText = 'Checked Out';
		    break;
		case 'returned':
		    $statusText = 'Returned';
		    break;
		case 'ordered':
		    $statusText = 'Ordered';
		    break;
		default:
		    redirect('group/chemicals');
	    }
	    $data['status'] = $newStatus;
	    $data['statusText'] = $statusText;

	    $this->chemicals_model->change_status($data);
	    redirect('group/chemicals/view?chem_id='.$data['chem_id']);
	    
	} else {
	    redirect('group/chemicals');
	}
    }
    
    public function listing($type){
	$this->load->model('chemicals_model');
	$user_id = $this->userobj->userid;
	
	$data['back_link'] = base_url().'group/chemicals';
    
	switch ($type) {
	    case 'mine': 
		$data2['items'] = $this->chemicals_model->get_mine($user_id);
		$data['sub_listing_HTML'] = $this->load->view('group/chemicals/sub_listing',$data2,TRUE); 
		$data['title'] = 'My Chemicals';
		$data['page_title'] = 'My Chemicals';
                $data['count'] = count($data2['items']);
		$this->load_view('group/chemicals/listing',$data);
		break;
	    case 'all':
		$data2['items'] = $this->chemicals_model->get_all();
		$data['sub_listing_HTML'] = $this->load->view('group/chemicals/sub_listing',$data2,TRUE); 
		$data['title'] = 'All Chemicals';
		$data['page_title'] = 'All Chemicals';
                $data['count'] = count($data2['items']);
		$this->load_view('group/chemicals/listing',$data);
		break;
	    case 'by_category':
		$data['title'] = 'Chemicals By Category';
		$data['page_title'] = 'Chemicals By Category';
		$data['sub_listing_HTML'] = '';
		$categories = $this->chemicals_model->get_categories();
		foreach($categories as $category) {
		    $data2 = array();
		    $data2['items'] = $this->chemicals_model->get_by_category($category);
		    $count = count($data2['items']);
		    if($count > 0) {
			$data['sub_listing_HTML'] .= "<small><span style=\"color: rgb(225, 0, 0);\"><b>$count</b>
			</span> Total in Category : <b>$category</b><br></small>";
			$data['sub_listing_HTML'] .= $this->load->view('group/chemicals/sub_listing',$data2,TRUE);
		    }
		}
		$this->load_view('group/chemicals/listing',$data);
		break;
	    case 'by_location':
		$data['title'] = 'Chemicals By Location';
		$data['page_title'] = 'Chemicals By Location';
		$data['sub_listing_HTML'] = '';
		$locations = $this->chemicals_model->get_locations();
		foreach($locations as $location) {
		    $data2 = array();
		    $data2['items'] = $this->chemicals_model->get_by_location($location);
		    $count = count($data2['items']);
		    if($count > 0) {
			$data['sub_listing_HTML'] .= "<small><span style=\"color: rgb(225, 0, 0);\"><b>$count</b>
						    </span> Total in location : <b>$location</b><br></small>";
			$data['sub_listing_HTML'] .= $this->load->view('group/chemicals/sub_listing',$data2,TRUE);
		    }
		}
		$this->load_view('group/chemicals/listing',$data);
		break;
	    default:
		$data['title'] = 'Search Results';
	}
    }
    
    public function search(){
	if (isset($_POST['search_chemicals_form'])){
	   
	    $this->load->model('chemicals_model');
	    $user_id = $this->userobj->userid;

	    $data['back_link'] = base_url().'group/chemicals';
	    $data['title'] = 'Search results';

	    $searchby = $this->input->post("searchby");
	    $category = $this->input->post("categories");
	    $location = $this->input->post("locations");
	    $searchterm = $this->input->post("searchterm");

	    if($searchby == 'id' && !empty($searchterm)) {
		$where_clause = $this->get_where_clause('id', $searchterm); // construct the where clause
		$results = $this->chemicals_model->search($where_clause);
	    }
	    elseif($searchby == 'name' && !empty($searchterm)) {
		$where_clause = "name REGEXP '$searchterm'";
		$results = $this->chemicals_model->search($where_clause);
	    }
	    elseif($searchby == 'cas' && !empty($searchterm)) {
		$where_clause = "cas REGEXP '$searchterm'";
		$results = $this->chemicals_model->search($where_clause);
	    }
	    elseif($searchby == 'location' && !empty($searchterm)) {
		$where_clause = "name REGEXP '$searchterm' AND location_id='$location[0]'";
		$results = $this->chemicals_model->search($where_clause);
	    } else {
		$results = array();
	    }
	    
	    $data['page_title'] = 'Search results';
	    $data['items'] = $results;
	    $this->load_view('group/chemicals/search_results',$data);
	    
	} else {
	    redirect('group/chemicals');
	}
    }
    
    public function add(){
	
	if (isset($_POST['add_chemical_form']))
	{
	    $this->load->model('chemicals_model');
	    $userid = $this->userobj->userid;

	    $cas = $this->input->post("cas");
	    $name = $this->input->post("name");
	    $company = $this->input->post("company");
	    $product_id = $this->input->post("productid");
	    $amount = $this->input->post("amount");
	    $units = $this->input->post("units");
	    $status = $this->input->post("status");
	    $mfmw = ''; // not used for now
	    $categories = $this->input->post("categories");
	    $category = $categories[0];
	    $other_category = $this->input->post("other_category");
	    $location = $this->input->post("location");
	    $other_location = $this->input->post("other_location");
	    $notes = $this->input->post("notes");
	    $personal = $this->input->post("personal");
	    
	    $entry_date = getLISDate();
	    $status_date = $entry_date;
	    
	    if(!empty($personal)) {
		$owner = $userid;
	    } else {
		$owner = 'myadmin';
	    }
	    
	    if(!empty($other_category)) {
		$category = $other_category;
		$this->chemicals_model->add_category($other_category, $userid); // add this category to DB
	    }
	    
	    // check to see if to add a new location
	    if(!empty($other_location) && !strstr($other_location, 'Location ID,')) {
		$location_info = preg_split("/,/", $other_location);
		//echo 'Size of array '.count($locations).'<br>';

		if(count($location_info) < 3 || count($location_info) > 4) {
		    $data0['page_title'] = 'Error';
		    $data0['error_message'] = 'Must provide complete location information.<br>
		    Format : "Location_id, Room #, Description<br>
		    The "Assigned To" value is not needed, but can be entered.<br>
		    Please use the back button to fix...';
		    $this->load_view('errors/error_and_back',$data0);
		    return;
		}

		$location = $location_info[0];
		$this->chemicals_model->add_location($location_info, $this->userobj); // add this location to DB
	    }
	    
	    if(empty($cas)) {
		$cas = 'Unknown';
	    }
	    if(empty($company)) {
		$company = 'Unknown';
	    }
	    if(empty($product_id)) {
		$product_id = 'Unknown';
	    }
	    if(empty($notes)) {
		$notes = 'None';
	    }
	    
	    if(empty($name) || empty($units)) {
		$data0['page_title'] = 'Error';
		$data0['error_message'] = 'Missing required value (name and/or units). Please use the back button to fix...';
		$this->load_view('errors/error_and_back',$data0);
		return;
	    }
	    
	    $data['cas'] = $cas;
	    $data['name'] = $name;
	    $data['company'] = $company;
	    $data['product_id'] = $product_id;
	    $data['amount'] = $amount;
	    $data['units'] = $units;
	    $data['entry_date'] = $entry_date;
	    $data['status'] = $status;
	    $data['status_date'] = $status_date;
	    $data['mfmw'] = $mfmw;
	    $data['category'] = $category;
	    $data['location'] = $location;
	    $data['notes'] = $notes;
	    $data['owner'] = $owner;
	    $data['userid'] = $userid;
	    $chem_id = $this->chemicals_model->add_chemical($data);
	    redirect('group/chemicals/view?chem_id='.$chem_id);
	    
	} else {
	    redirect('group/chemicals/view?chem_id='.$chem_id);
	}
    }
    
    public function edit(){
	$this->load->model('chemicals_model');
	 
	if (isset($_POST['edit_chemical_form'])){
	    $chem_id = $this->input->post("chem_id");
	    
	    $cas = $this->input->post("cas");
	    $name = $this->input->post("name");
	    $company = $this->input->post("company");
	    $product_id = $this->input->post("productid");
	    $amount = $this->input->post("amount");
	    $units = $this->input->post("units");
	    $status = $this->input->post("status");
	    $mfmw = ''; // not used for now
	    $other_category = $this->input->post("other_category");
	    $location = $this->input->post("location");
	    $other_location = $this->input->post("other_location");
	    $notes = $this->input->post("notes");
	    $personal = $this->input->post("personal");

	    // create some variable to add to the database
	    $userid = $this->userobj->userid;
	    $status_date = getLISDate();

	    if(!empty($personal)) {
		$owner = $userid;
	    } else {
		$owner = 'myadmin';
	    }

	    if(!empty($other_category)) {
		$category = $other_category;
		$this->chemicals_model->add_category($other_category, $userid); 
	    } else {
		$categories = $this->input->post("categories");
		$category = $categories[0];
	    }

	    // check to see if to add a new location
	    if(!empty($other_location) && !strstr($other_location, 'Location ID,')) {
		$location_info = preg_split("/,/", $other_location);
		//echo 'Size of array '.count($locations).'<br>';

		if(count($location_info) < 3 || count($location_info) > 4) {
		    $data0['page_title'] = 'Error';
		    $data0['error_message'] = 'Must provide complete location information.<br>
		    Format : "Location_id, Room #, Description<br>
		    The "Assigned To" value is not needed, but can be entered.<br>
		    Please use the back button to fix...';
		    $this->load_view('errors/error_and_back',$data0);
		    return;
		}

		$location = $location_info[0];
		$this->chemicals_model->add_location($location_info, $userid); // add this location to DB
	    }

	    if(empty($company)) {
		$company = 'Unknown';
	    }
	    if(empty($product_id)) {
		$product_id = 'Unknown';
	    }
	    if(empty($notes)) {
		$notes = 'None';
	    }
	    if(empty($name) || empty($units)) {
		$data0['page_title'] = 'Error';
		$data0['error_message'] = 'Missing required value (name and/or units). Please use the back button to fix...';
		$this->load_view('errors/error_and_back',$data0);
		return;
	    }
	    
	    $data['cas'] =  $cas;
	    $data['name'] = $name;
	    $data['company'] = $company;
	    $data['product_id'] = $product_id;
	    $data['amount'] = $amount;
	    $data['units'] = $units;
	    $data['status'] = $status;
	    $data['owner'] = $owner;
	    $data['notes'] = $notes;
	    $data['status_date'] = $status_date;
	    $data['mfmw'] = $mfmw;
	    $data['category'] = $category;
	    $data['location'] = $location;
	    $data['userid'] = $userid;
	    $data['chem_id'] = $chem_id;
	    
	    $this->chemicals_model->update_chemical($data);
	

	    redirect('group/chemicals/view?chem_id='.$chem_id);
	} else {
	    $chem_id = $_GET["chem_id"];
	    $userid = $this->userobj->userid;

	    $chemInfo = $this->chemicals_model->get_info($chem_id);

	    $chem_id = $chemInfo['chem_id'];
	    $cas = $chemInfo['cas'];
	    $name = $chemInfo['name'];
	    $company = $chemInfo['company'];
	    $product_id = $chemInfo['product_id'];
	    $amount = $chemInfo['amount'];
	    $units = $chemInfo['units'];
	    $status = $chemInfo['status'];
	    $mfmw = $chemInfo['mfmw']; // not used
	    $category = $chemInfo['category'];
	    $location_id = $chemInfo['location_id'];
	    $notes = $chemInfo['notes'];
	    $owner = $chemInfo['owner'];

	    // get the categories and locations
	    $data['chem_id'] = $chem_id;
	    $data['status'] = $status;
	    $data['units'] = $units;
	    $data['owner'] = $owner;
	    $data['notes'] = $notes;
	    $data['location_id'] = $location_id;
	    $data['amount'] = $amount;
	    $data['cas'] =  $cas;
	    $data['product_id'] = $product_id;
	    $data['name'] = $name;
	    $data['company'] = $company;
	    $data['userid'] = $userid;
	    $data['locations'] = $this->chemicals_model->get_locations();
	    $data['categories'] = $this->chemicals_model->get_categories();

	    $data['chem_id'] = $chem_id;
	    $data['userid'] = $userid;
	    $data['page_title'] = 'Edit chemical info';
	    $this->load_view('group/chemicals/edit_page',$data);
	}
	
    }
    
    public function delete(){
	$this->load->model('chemicals_model');
	$chem_id = $this->input->get("chem_id");
    
	$this->chemicals_model->delete_chemical($chem_id);

	redirect('group/chemicals');
    }
    
    public function view(){
	if (isset($_GET['chem_id'])){
	    $this->load->model('chemicals_model');
	    
	    $chem_id = $this->input->get("chem_id");
	    
	    $data['back_link'] = base_url().'group/chemicals';
	    $data['title'] = 'Chemical Info';
	    $data['page_title'] = 'Chemical Information';
	    
	    $data2['users'] = $this->get_current_users();
	    $data2['userid'] = $this->userobj->userid;
	    $data2['role'] = $this->userobj->role;
	    $data2['chemInfo'] = $this->chemicals_model->get_info($chem_id);
	    $data2['locationInfo'] = $this->chemicals_model->get_full_location($data2['chemInfo']['location_id']);
	    $data['infoHTML'] = $this->load->view('group/chemicals/info_table',$data2,TRUE);
	    
	    $this->load_view('group/chemicals/info',$data);
	} else {
	    
	}
    }
    
    public function transfer(){
	
	$users = $this->get_current_users();
	$this->load->model('chemicals_model');
	
	// The case of 'make selected mine' of chemicals listing page
	if (isset($_POST['trasfer_chemical_form'])){ 
	   
	    $userid = $this->userobj->userid;
	    $chem_id = $this->input->post('chem_id');

	    $from_user = $this->input->post("from_user");
	    $to_user = $this->input->post("to_user");

	    $to_name = $users[$to_user]->name;
	    if($to_user == 'admin') {
		$to_name = 'Group Chemical';
	    }

	    $data['to_user'] = $to_user;
	    $data['from_user'] = $from_user;
	    $data['userid'] = $userid;
	    $this->chemicals_model->transfer_full_ownership($data);

	    $message = 'Chemicals transferred from '.$users[$from_user]->name.' to '.$to_name;
	    showModal('Ownership transfer',$message);
	    redirect('group/chemicals');
	} // the case of 'Make mine' button of chemical's info page
	elseif (!empty($_GET['chem_id'])) { 
	    $chem_id = $this->input->get('chem_id');
	    $userid = $this->userobj->userid;
	   
	    $data['chem_id'] = $chem_id;
	    $data['userid'] = $userid;
	    $this->chemicals_model->transfer_single_ownership($data);

	    $message = "Chemical with id =$chem_id transferred to ".$users[$userid]->name;
	    $destination = base_url().'group/chemicals/view?chem_id='.$chem_id;
	    showModal('Ownership transfer',$message,$destination);

	    //redirect('group/chemicals/view?chem_id='.$chem_id);
	} else { // the url was loaded by mistake
	    redirect(base_url().'group/chemicals');
	}
    }
    
    public function list_locations(){
	$this->load->model('chemicals_model');
	$users = $this->get_current_users();
	$locations = $this->chemicals_model->get_locations();
	$home = base_url()."group/chemicals";
	displayLocationList($locations,$users,$home);
    }
    
    // function to return the search terms
    protected function get_where_clause($searchby, $searchterm) {
	$where_clause = '';

	if($searchby == 'id') {
	$ids = preg_split("/[\s,]+/", $searchterm); // split on white spaces or commas

	$i = 0;
	foreach($ids as $id) {
	    if($i == 0) {
		$where_clause .= "chem_id='$id'";
	    } else {
		$where_clause .= " OR chem_id='$id'";
	    }
	    $i++;
	}
	}

	return $where_clause;
    }
}