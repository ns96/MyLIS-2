<?php

/**
 * Handles the information update of account properties.
 * 
 * Used only by group controllers
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis 
 */
class Proputil_model extends CI_Model {
    var $user = null;
    var $table = '';
  
  public function initialize($params){
    $this->user = $params['user'];
    $this->table = $params['account'].'_properties';
  }
  
  // function to get a property from database
  function get_property($key) {
    $lisdb = $this->load->database('lisdb',TRUE);
    $lisdb->where('key_id',$key);
    $records = $lisdb->get($this->table)->result_array();
    if (count($records)>0)
	return $records[0]['value'];
    else
	return null;
  }
  
  // function to see if it as the key is already in DB
  function has_key($key) {
    $lisdb = $this->load->database('lisdb',TRUE);
    $lisdb->where('key_id',$key);
    $record = $lisdb->get($this->table)->result_array();
    
    if(count($record) > 0) {
      return true;
    }
    else {
      return false;
    }
  }
  
  // save a property in the database
  function store_property($key, $value) {
    $lisdb = $this->load->database('lisdb',TRUE);
    $userid = $this->user->userid;
    
    $has_key = $this->has_key($key); // check to make sure key is not alredy there
    if(!$has_key) {
	$data = array(
	    'key_id'	=>  $key,
	    'value'	=>  $value,
	    'userid'	=>  $userid,
	);
	$lisdb->insert($this->table,$data);
    }
    else {
	$data = array(
	    'value' =>	$value
	);
	$lisdb->where('key_id',$key);
	$lisdb->update($this->table,$data);
    }
  }
  
  // function to return all the properties as an array with key value pair
  function get_properties() {
    
    $properties = array();
    
    $lisdb = $this->load->database('lisdb',TRUE);
    $records = $lisdb->get($this->table)->result_array();
    //echo "<pre>"; var_dump($records); die();
    
    foreach($records as $record){
	$properties[$record['key_id']]=$record['value'];
    }
    return $properties;
  }
  
  // function to remove a property
  function remove_property($key) {
      
    $lisdb = $this->load->database('lisdb',TRUE);
    $lisdb->where('key_id',$key);
    $lisdb->delete($this->table);
  }
}
?>
