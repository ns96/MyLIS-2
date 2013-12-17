<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Supplies extends Group_Controller {
    
    private $userobj = null;
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');
    }
    
    public function index(){
	
	$this->load->model('supplies_model');
	
	$data['item_id'] = $this->input->get('item_id');
	$data['message'] = $this->input->get('message');
	
	$data['user'] = $this->userobj;
	$data['users'] = $this->getCurrentUsers();
	
	$data['locations'] = $this->supplies_model->getLocations();
	$data['categories'] = $this->supplies_model->getCategories();
	
	$data['page_title'] = 'Search Supplies Inventory';
	$this->load_view('group/supplies/main',$data);
    }
    
    // Handles the 'ownership transfer' or 'view info' of multiple items
    public function bulkActions(){
	if (isset($_POST['listing_form'])){
	    $this->load->model('supplies_model');
	    $userid = $this->userobj->userid;
	    
	    // Find item id's to act upon
	    if(isset($_POST["all"])) {
		$item_ids = preg_split("/[\s,]+/", $this->input->post("all"));
		$length = count($item_ids);
		unset($item_ids[$length - 1]);
	    }
	    else {
		$item_ids = $this->input->post("item_ids");
	    }
	    
	    $task = $this->input->post('group_task');
	    // Desired group action is 'TRANSFER'
	    if ($task == 'transfer'){
		if(!empty($item_ids)) {
		    $message = '';
		    foreach($item_ids as $item_id) {
			$data2['item_id'] = $item_id;
			$data2['userid'] = $userid;
			$this->supplies_model->transferSingleOwnership($data2);
			   $message .= '<small>Transferred Ownership of supply With ID <b>'.$item_id.'</b></small><br>';
		    }
		}
		// Show message about the transfered supplies and redirect
		$destination = base_url().'group/supplies';
		showModal('Ownership transfer',$message,$destination);
	    } // Desired group action is 'VIEW'
	    elseif($task == 'view'){

		$data['back_link'] = base_url().'group/supplies';
		$data['title'] = 'Supply Info';
		$data['page_title'] = 'Supply Information';
		$data['infoHTML'] = '';

		$data2['users'] = $this->getCurrentUsers();
		$data2['userid'] = $this->userobj->userid;
		$data2['role'] = $this->userobj->role;
		
		if(!empty($item_ids)) {
		    foreach($item_ids as $item_id) {
			$data2['itemInfo'] = $this->supplies_model->getInfo($item_id);
			$data2['locationInfo'] = $this->supplies_model->getFullLocation($data2['itemInfo']['location_id']);
			$data['infoHTML'] .= $this->load->view('group/supplies/infoTable',$data2,TRUE);
			$data['infoHTML'] .= "<br>";
		    }
		}
		$this->load_view('group/supplies/info',$data);
	    }
	} else {
	    redirect('group/supplies');
	}
    }
    
    public function changeStatus($newStatus){
	if (isset($_GET['item_id'])){
	    $this->load->model('supplies_model');
	    
	    $data['item_id'] = $this->input->get('item_id');
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
		    redirect('group/supplies');
	    }
	    $data['status'] = $newStatus;
	    $data['statusText'] = $statusText;

	    $this->supplies_model->changeStatus($data);
	    redirect('group/supplies/view?item_id='.$data['item_id']);
	    
	} else {
	    redirect('group/supplies');
	}
    }
    
    public function listing($type){
	$this->load->model('supplies_model');
	$user_id = $this->userobj->userid;
	
	$data['back_link'] = base_url().'group/supplies';
    
	switch ($type) {
	    case 'mine': 
		$data2['items'] = $this->supplies_model->getMine($user_id);
		$count = count($data2['items']);
		$data['sub_listing_HTML'] = $this->load->view('group/supplies/sub_listing',$data2,TRUE); 
		$data['title'] = 'My Supplies';
		$data['page_title'] = 'My Supplies';
                $data['count'] = count($data2['items']);
		$this->load_view('group/supplies/listing',$data);
		break;
	    case 'all':
		$data2['items'] = $this->supplies_model->getAll();
		$data['sub_listing_HTML'] = $this->load->view('group/supplies/sub_listing',$data2,TRUE); 
		$data['title'] = 'All supplies';
		$data['page_title'] = 'All supplies';
                $data['count'] = count($data2['items']);
		$this->load_view('group/supplies/listing',$data);
		break;
	    case 'by_category':
		$data['title'] = 'Supplies By Category';
		$data['page_title'] = 'Supplies By Category';
		$data['sub_listing_HTML'] = '';
		$categories = $this->supplies_model->getCategories();
		foreach($categories as $category) {
		    $data2 = array();
		    $data2['items'] = $this->supplies_model->getByCategory($category);
		    $count = count($data2['items']);
		    if($count > 0) {
			$data['sub_listing_HTML'] .= "<small><span style=\"color: rgb(225, 0, 0);\"><b>$count</b>
			</span> Total in Category : <b>$category</b><br></small>";
			$data['sub_listing_HTML'] .= $this->load->view('group/supplies/sub_listing',$data2,TRUE);
		    }
		}
		$this->load_view('group/supplies/listing',$data);
		break;
	    case 'by_location':
		$data['title'] = 'Supplies By Location';
		$data['page_title'] = 'Supplies By Location';
		$data['sub_listing_HTML'] = '';
		$locations = $this->supplies_model->getLocations();
		foreach($locations as $location) {
		    $data2 = array();
		    $data2['items'] = $this->supplies_model->getByLocation($location);
		    $count = count($data2['items']);
		    if($count > 0) {
			$data['sub_listing_HTML'] .= "<small><span style=\"color: rgb(225, 0, 0);\"><b>$count</b>
						    </span> Total in location : <b>$location</b><br></small>";
			$data['sub_listing_HTML'] .= $this->load->view('group/supplies/sub_listing',$data2,TRUE);
		    }
		}
		$this->load_view('group/supplies/listing',$data);
		break;
	    default:
		$data['title'] = 'Search Results';
	}
    }
    
    public function search(){
	if (isset($_POST['search_supplies_form'])){
	   
	    $this->load->model('supplies_model');
	    $user_id = $this->userobj->userid;

	    $data['back_link'] = base_url().'group/supplies';
	    $data['title'] = 'Search results';

	    $searchby = $this->input->post("searchby");
	    $category = $this->input->post("categories");
	    $location = $this->input->post("locations");
	    $searchterm = $this->input->post("searchterm");

	    if($searchby == 'id' && !empty($searchterm)) {
		$where_clause = $this->getWhereClause('id', $searchterm); // construct the where clause
		$results = $this->supplies_model->search($where_clause);
	    }
	    elseif($searchby == 'name' && !empty($searchterm)) {
		$where_clause = "name REGEXP '$searchterm'";
		$results = $this->supplies_model->search($where_clause);
	    }
	    elseif($searchby == 'model' && !empty($searchterm)) {
		$where_clause = "model REGEXP '$searchterm'";
		$results = $this->supplies_model->search($where_clause);
	    }
	    elseif($searchby == 'location' && !empty($searchterm)) {
		$where_clause = "name REGEXP '$searchterm' AND location_id='$location[0]'";
		$results = $this->supplies_model->search($where_clause);
	    } else {
		$results = array();
	    }
	    
	    $data['page_title'] = 'Search results';
	    $data['items'] = $results;
	    $this->load_view('group/supplies/search_results',$data);
	    
	} else {
	    redirect('group/supplies');
	}
    }
    
    public function add(){
	
	if (isset($_POST['add_supply_form']))
	{
	    $this->load->model('supplies_model');
	    $userid = $this->userobj->userid;

	    $model = $this->input->post("model");
	    $name = $this->input->post("name");
	    $company = $this->input->post("company");
	    $product_id = $this->input->post("productid");
	    $amount = $this->input->post("amount");
	    $units = $this->input->post("units");
	    $status = $this->input->post("status");
	    $sn = ''; // not used for now
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
		$this->supplies_model->addCategory($other_category, $userid); // add this category to DB
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
		$this->supplies_model->addLocation($location_info, $this->userobj); // add this location to DB
	    }
	    
	    if(empty($model)) {
		$model = 'Unknown';
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
	    
	    $data['model'] = $model;
	    $data['name'] = $name;
	    $data['company'] = $company;
	    $data['product_id'] = $product_id;
	    $data['amount'] = $amount;
	    $data['units'] = $units;
	    $data['entry_date'] = $entry_date;
	    $data['status'] = $status;
	    $data['status_date'] = $status_date;
	    $data['sn'] = $sn;
	    $data['category'] = $category;
	    $data['location'] = $location;
	    $data['notes'] = $notes;
	    $data['owner'] = $owner;
	    $data['userid'] = $userid;
	    $item_id = $this->supplies_model->addSupply($data);
	    redirect('group/supplies/view?item_id='.$item_id);
	    
	} else {
	    redirect('group/supplies/view?item_id='.$item_id);
	}
    }
    
    public function edit(){
	$this->load->model('supplies_model');
	 
	if (isset($_POST['edit_supply_form'])){
	    $item_id = $this->input->post("item_id");
	    
	    $model = $this->input->post("model");
	    $name = $this->input->post("name");
	    $company = $this->input->post("company");
	    $product_id = $this->input->post("productid");
	    $amount = $this->input->post("amount");
	    $units = $this->input->post("units");
	    $status = $this->input->post("status");
	    $sn = ''; // not used for now
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
		$this->supplies_model->addCategory($other_category, $userid); 
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
		$this->supplies_model->addLocation($location_info, $userid); // add this location to DB
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
	    
	    $data['model'] =  $model;
	    $data['name'] = $name;
	    $data['company'] = $company;
	    $data['product_id'] = $product_id;
	    $data['amount'] = $amount;
	    $data['units'] = $units;
	    $data['status'] = $status;
	    $data['owner'] = $owner;
	    $data['notes'] = $notes;
	    $data['status_date'] = $status_date;
	    $data['sn'] = $sn;
	    $data['category'] = $category;
	    $data['location'] = $location;
	    $data['notes'] = $owner;
	    $data['userid'] = $userid;
	    $data['item_id'] = $item_id;
	    
	    $this->supplies_model->updateSupply($data);
	

	    redirect('group/supplies/view?item_id='.$item_id);
	} else {
	    $item_id = $_GET["item_id"];
	    $userid = $this->userobj->userid;

	    $itemInfo = $this->supplies_model->getInfo($item_id);

	    $item_id = $itemInfo['item_id'];
	    $model = $itemInfo['model'];
	    $name = $itemInfo['name'];
	    $company = $itemInfo['company'];
	    $product_id = $itemInfo['product_id'];
	    $amount = $itemInfo['amount'];
	    $units = $itemInfo['units'];
	    $status = $itemInfo['status'];
	    $sn = $itemInfo['sn']; // not used
	    $category = $itemInfo['category'];
	    $location_id = $itemInfo['location_id'];
	    $notes = $itemInfo['notes'];
	    $owner = $itemInfo['owner'];

	    // get the categories and locations
	    $data['item_id'] = $item_id;
	    $data['status'] = $status;
	    $data['units'] = $units;
	    $data['owner'] = $owner;
	    $data['notes'] = $notes;
	    $data['location_id'] = $location_id;
	    $data['amount'] = $amount;
	    $data['model'] =  $model;
	    $data['product_id'] = $product_id;
	    $data['name'] = $name;
	    $data['company'] = $company;
	    $data['userid'] = $userid;
	    $data['locations'] = $this->supplies_model->getLocations();
	    $data['categories'] = $this->supplies_model->getCategories();

	    $data['item_id'] = $item_id;
	    $data['userid'] = $userid;
	    $data['page_title'] = 'Edit supply info';
	    $this->load_view('group/supplies/editPage',$data);
	}
	
    }
    
    public function delete(){
	$this->load->model('supplies_model');
	$item_id = $this->input->get("item_id");
    
	$this->supplies_model->deleteSupply($item_id);

	redirect('group/supplies');
    }
    
    public function view(){
	if (isset($_GET['item_id'])){
	    $this->load->model('supplies_model');
	    
	    $item_id = $this->input->get("item_id");
	    
	    $data['back_link'] = base_url().'group/supplies';
	    $data['title'] = 'Supply Info';
	    $data['page_title'] = 'Supply Information';
	    
	    $data2['users'] = $this->getCurrentUsers();
	    $data2['userid'] = $this->userobj->userid;
	    $data2['role'] = $this->userobj->role;
	    $data2['itemInfo'] = $this->supplies_model->getInfo($item_id);
	    $data2['locationInfo'] = $this->supplies_model->getFullLocation($data2['itemInfo']['location_id']);
	    $data['infoHTML'] = $this->load->view('group/supplies/infoTable',$data2,TRUE);
	    
	    $this->load_view('group/supplies/info',$data);
	} else {
	    
	}
    }
    
    public function transfer(){
	
	$users = $this->getCurrentUsers();
	$this->load->model('supplies_model');
	
	// The case of 'make selected mine' of supplies listing page
	if (isset($_POST['trasfer_supply_form'])){ 
	   
	    $userid = $this->userobj->userid;
	    $item_id = $this->input->post('item_id');

	    $from_user = $this->input->post("from_user");
	    $to_user = $this->input->post("to_user");

	    $to_name = $users[$to_user]->name;
	    if($to_user == 'admin') {
		$to_name = 'Group Supply';
	    }

	    $data['to_user'] = $to_user;
	    $data['from_user'] = $from_user;
	    $data['userid'] = $userid;
	    $this->supplies_model->transferFullOwnership($data);

	    $message = 'Supplies transferred from '.$users[$from_user]->name.' to '.$to_name;
	    showModal('Ownership transfer',$message);
	    redirect('group/supplies');
	} // the case of 'Make mine' button of supply's info page
	elseif (!empty($_GET['item_id'])) { 
	    $item_id = $this->input->get('item_id');
	    $userid = $this->userobj->userid;
	   
	    $data['item_id'] = $item_id;
	    $data['userid'] = $userid;
	    $this->supplies_model->transferSingleOwnership($data);

	    $message = "Supply with id =$item_id transferred to ".$users[$userid]->name;
	    $destination = base_url().'group/supplies/view?item_id='.$item_id;
	    showModal('Ownership transfer',$message,$destination);

	} else { // the url was loaded by mistake
	    redirect(base_url().'group/supplies');
	}
    }
    
    public function listLocations(){
	$this->load->model('supplies_model');
	$users = $this->getCurrentUsers();
	$locations = $this->supplies_model->getLocations();
	$home = base_url()."group/supplies";
	displayLocationList($locations,$users,$home);
    }
    
    // function to return the search terms
    protected function getWhereClause($searchby, $searchterm) {
	$where_clause;

	if($searchby == 'id') {
	$ids = preg_split("/[\s,]+/", $searchterm); // split on white spaces or commas

	$i = 0;
	foreach($ids as $id) {
	    if($i == 0) {
	    $where_clause .= "item_id='$id'";
	    }
	    else {
	    $where_clause .= " OR item_id='$id'";
	    }
	    $i++;
	}
	}

	return $where_clause;
    }
}