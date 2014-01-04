<?php

class File_folder_model extends CI_Model {
    
    var $lisdb = null;
    var $table = ''; // the folder_files table
    var $c_table = ''; // the categories table
    
    public function __construct() {
	parent::__construct();
	$this->lisdb = $this->load->database('lisdb',TRUE);
	$this->table = $this->session->userdata('group').'_folder_files';
        $this->c_table = $this->session->userdata('group').'_categories';
    }
    
    public function get_categories($type){
        $categories = array();

        if($type == 'filing') { // add a filing category
          $categories['cat_-1'] = 'Unfiled';
        }
        $sql = "SELECT * FROM $this->c_table WHERE (table_name='$this->table' AND type='$type')";
        $records = $this->lisdb->query($sql)->result_array();

        if(count($records) >= 1) {
          foreach($records as $array) {
            $cat_id = 'cat_'.$array['category_id'];
            $value = $array['value'];
            $categories[$cat_id] = $value;
          }
        }

        return $categories;
    }
    
    // function to add a new category to the category db
    function add_category($type, $category, $userid) {
      $sql = "INSERT INTO $this->c_table VALUES('', '$this->table','$type', '$category', '$userid')";
      $this->lisdb->query($sql);
      return $this->lisdb->insert_id();
    }
    
    public function get_links($cat_id,$myfiles=null,$userid=null){
        if($myfiles == 'yes') {
          $sql = "SELECT * FROM $this->table WHERE (category_id='$cat_id' AND userid='$userid')";
        }
        else {
          $sql = "SELECT * FROM $this->table WHERE category_id='$cat_id'";
        }
        $records = $this->lisdb->query($sql)->result_array();
        return $records;
    }
    
    // function to get a file
    public function get_file($file_id) {
      $sql = "SELECT * FROM $this->table WHERE file_id='$file_id'";
      $records = $this->lisdb->query($sql)->result_array();
      return $records[0];
    }
    
    public function delete_file($file_id){
        $sql = "DELETE FROM $this->table WHERE file_id = '$file_id'";
        $this->lisdb->query($sql);
    }
    
    public function add_file($data){
        $sql = "INSERT INTO $this->table VALUES('$data[file_id]', '$data[title]', '$data[cat_id]', '$data[userid]')";
        $this->lisdb->query($sql);
    }
    
    public function update_file($title,$cat_id,$file_id){
        $sql = "UPDATE $this->table SET title = '$title', category_id = '$cat_id' WHERE file_id = '$file_id'";
        $this->lisdb->query($sql);
    }
    
}