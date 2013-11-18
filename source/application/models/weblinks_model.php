<?php

class Weblinks_model extends CI_Model {
    
    var $lisdb = null;
    var $web_table = null;
    var $cat_table = null;
    
    public function __construct() {
	parent::__construct();
	$this->lisdb = $this->load->database('lisdb',TRUE);
	$this->web_table = $this->session->userdata('group')."_weblinks";
	$this->cat_table = $this->session->userdata('group')."_categories";
    }
    
    // Retrieve info about a weblink
    public function getWeblink($link_id){
	$sql = "SELECT * FROM $this->web_table WHERE link_id='$link_id'";
	$records = $this->lisdb->query($sql)->result_array();

	return $records[0];
    }
    
    // Get all the group's weblinks that belong to a certain category
    public function getCategoryWeblinks($cat_id){
	$sql = "SELECT * FROM $this->web_table WHERE category_id='$cat_id'";
	$records = $this->lisdb->query($sql)->result_array();
	return $records;
    }
    
    // Get all the PI's weblinks that belong to a certain category
    public function getCategoryMyWeblinks($cat_id,$userid){
	$sql = "SELECT * FROM $this->web_table WHERE (category_id='$cat_id' AND userid='$userid')";
	$records = $this->lisdb->query($sql)->result_array();
	return $records;
    }
    
    // Get a list of all the weblink categories of the group
    public function getCategories(){
	$categories = array();
	$categories['cat_-1'] = 'Unfiled';

	$sql = "SELECT * FROM $this->cat_table WHERE table_name='$this->web_table'";
	$records = $this->lisdb->query($sql)->result_array();

	if(count($records) > 0) {
	    foreach($records as $categoryItem){
		$cat_id = 'cat_'.$categoryItem['category_id'];
		$value = $categoryItem['value'];
		$categories[$cat_id] = $value;
	    }
	}

	return $categories;
    }
 
    // Add a new weblink
    public function add($data){
	$sql = "INSERT INTO $this->web_table VALUES('', '$data[title]','$data[url]', '$data[cat_id]', '$data[userid]')";
	$this->lisdb->query($sql);
    }
    
    // Add a new weblink category for the group
    public function addCategory($name,$userid){
	$userid = $this->user->userid;
    
	$sql = "INSERT INTO $this->cat_table VALUES('', '$this->web_table','link', '$name', '$userid')";
	$this->lisdb->query($sql);

	return $this->lisdb->insert_id();
    }
    
    // Update a weblink
    public function update($data){
	$sql = "UPDATE $this->web_table SET title = '$data[title]', url = '$data[url]', category_id = '$data[cat_id]' WHERE link_id = '$data[link_id]'";
	$this->lisdb->query($sql);
    }
    
    // Delete a weblink
    public function delete($id){
	$sql = "DELETE FROM $this->web_table WHERE link_id = '$id'";
	$this->lisdb->query($sql);
    }
}