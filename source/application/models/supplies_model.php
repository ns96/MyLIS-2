<?php

class Supplies_model extends CI_Model {
    
    var $lisdb = null;
    var $table = ''; // the supplies table
    var $l_table = ''; // the locations table
    var $ct_table = ''; // the category table
    
    public function __construct() {
	parent::__construct();
	$this->lisdb = $this->load->database('lisdb',TRUE);
	$this->table = $this->session->userdata('group').'_supplies';
	$this->l_table = $this->session->userdata('group').'_locations';
	$this->ct_table = $this->session->userdata('group').'_categories';
    }
    
    public function transferFullOwnership($data){
	$sql = "UPDATE $this->table SET owner='$data[to_user]', userid='$data[userid]' WHERE owner='$data[from_user]'";
	$this->lisdb->query($sql);
    }
    
    public function transferSingleOwnership($data){
	$sql = "UPDATE $this->table SET owner='$data[userid]', userid='$data[userid]' WHERE item_id='$data[item_id]'";
	$this->lisdb->query($sql);
    }
    
    public function changeStatus($data){
	if ($data['status'] == 'in-stock'){
	    $sql = "UPDATE $this->table SET status='$data[statusText]', status_date='$data[status_date]', 
		    entry_date='$data[status_date]', userid='$data[userid]' WHERE item_id='$data[item_id]'";
	} else {
	    $sql = "UPDATE $this->table SET status='$data[statusText]', status_date='$data[status_date]', 
		userid='$data[userid]' WHERE item_id='$data[item_id]'";
	}
	$this->lisdb->query($sql);
    }
    
    public function getLocations(){
	$sql = "SELECT * FROM $this->l_table ORDER BY location_id";
	$records = $this->lisdb->query($sql)->result_array();
	
	$locations = array();
	if(count($records)>0) {
	    foreach($records as $location) {
		$locations[] =$location['location_id'];
	    }
	} else {
	    $locations[] = 'None In List';
	}
	
	return $locations;
    }
    
    public function getFullLocation($location_id){
	$sql = "SELECT * FROM $this->l_table WHERE location_id='$location_id'";
	$records = $this->lisdb->query($sql)->result_array();
	return $records[0];
    }
    
    public function getCategories(){
	$sql = "SELECT * FROM $this->ct_table WHERE table_name='$this->table' ORDER BY category_id";
	$records = $this->lisdb->query($sql)->result_array();
	
	$categories = array();
	if(count($records)>0) {
	    foreach($records as $category) {
		$categories[] =$category['value'];
	    }
	} else {
	    $categories[] = 'None In List';
	}
	
	return $categories;
    }
    
    public function addCategory($category, $userid){
	$sql = "INSERT INTO $this->ct_table VALUES(' ', '$this->table', 'supply', '$category', '$userid')";
	$this->lisdb->query($sql);
    }
    
    public function addLocation($location_info, $user){
	$location_id = trim($location_info[0]);
	$room = trim($location_info[1]);
	$description = trim($location_info[2]);

	$userid = $user->userid;

	if(isset($locations[3])) {
	    $owner = trim($location_info[3]);
	} else {
	    $owner = $user->name;
	}

	$sql = "INSERT INTO $this->l_table VALUES('$location_id', '$room', '$description', '$owner', '$userid')";
	$this->lisdb->query($sql);
    }
    
    public function getMine($userid){
	$sql = "SELECT * FROM $this->table WHERE owner='$userid' ORDER BY name";
	$records = $this->lisdb->query($sql)->result_array();
	return $records;
    }
    
    public function getAll(){
	$sql = "SELECT * FROM $this->table ORDER BY name";
	$records = $this->lisdb->query($sql)->result_array();
	return $records;
    }

    public function getByCategory($category){
	$sql = "SELECT * FROM $this->table WHERE category='$category' ORDER BY name";
	$records = $this->lisdb->query($sql)->result_array();
	return $records;
    }
    
    public function getByLocation($location){
	$sql = "SELECT * FROM $this->table WHERE location_id='$location' ORDER BY name";
	$records = $this->lisdb->query($sql)->result_array();
	return $records;
    }
    
    public function getInfo($item_id){
	$sql = "SELECT * FROM $this->table WHERE item_id='$item_id'";
	$records = $this->lisdb->query($sql)->result_array();
	return $records[0];
    }
    
    public function addSupply($data){
	$sql = "INSERT INTO $this->table VALUES('', '$data[model]', '$data[name]', '$data[company]', '$data[product_id]', '$data[amount]',
	'$data[units]', '$data[entry_date]', '$data[status]', '$data[status_date]', '$data[sn]', '$data[category]','$data[location]', '$data[notes]', '$data[owner]', '$data[userid]','')";
	$this->lisdb->query($sql);
	return $this->lisdb->insert_id();
    }
    
    public function updateSupply($data){
	$sql = "UPDATE $this->table SET model='$data[model]', name='$data[name]', company='$data[company]', product_id='$data[product_id]', 
	    amount='$data[amount]', units='$data[units]', status='$data[status]', status_date='$data[status_date]', sn='$data[sn]', 
	    category='$data[category]', location_id='$data[location]', notes='$data[notes]',owner='$data[owner]', userid='$data[userid]' WHERE item_id='$data[item_id]'";
	$this->lisdb->query($sql);
    }
    
    public function deleteSupply($item_id){
	$sql = "DELETE FROM $this->table WHERE item_id='$item_id'";
	$this->lisdb->query($sql);
    }
    
    public function search($where_clause){
	$sql = "SELECT * FROM $this->table WHERE $where_clause ORDER BY name";
	$records = $this->lisdb->query($sql)->result_array();
	return $records;
    }
    
}