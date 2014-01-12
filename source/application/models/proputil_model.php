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
  
  /**
   * Reads a property from database
   * 
   * @param string $key
   * @return string|null 
   */
  function get_property($key) {
    $lisdb = $this->load->database('lisdb',TRUE);
    $lisdb->where('key_id',$key);
    $records = $lisdb->get($this->table)->result_array();
    if (count($records)>0)
	return $records[0]['value'];
    else
	return null;
  }
  
  /**
   * Checks if the key is already in DB
   * 
   * @param string $key
   * @return boolean 
   */
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
  
  /**
   * Saves a property in the database
   * 
   * @param string $key
   * @param string $value 
   */
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
  
  /**
   * Returns all the properties as an array with key value pair
   * 
   * @return array 
   */
  function get_properties() {
    
    $properties = array();
    
    $lisdb = $this->load->database('lisdb',TRUE);
    $records = $lisdb->get($this->table)->result_array();
    
    foreach($records as $record){
	$properties[$record['key_id']]=$record['value'];
    }
    return $properties;
  }
  
  /**
   * Removes a property
   * 
   * @param string $key 
   */
  function remove_property($key) {
      
    $lisdb = $this->load->database('lisdb',TRUE);
    $lisdb->where('key_id',$key);
    $lisdb->delete($this->table);
  }
}
?>
