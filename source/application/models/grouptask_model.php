<?php

class Grouptask_model extends CI_Model {
    
    var $lisdb = null;
    var $table = ''; // The group task table name
    var $i_table = ''; // The group task item table
    
    public function __construct() {
	parent::__construct();
	$this->lisdb = $this->load->database('lisdb',TRUE);
	$this->table = $this->session->userdata('group').'_grouptask';
        $this->i_table = $this->session->userdata('group').'_grouptask_item';
    }
    
    public function getYearTasks($y_min,$y_max){
        $sql = "SELECT * FROM $this->table WHERE (year >= $y_min AND year <= $y_max)";
        $records = $this->lisdb->query($sql)->result_array();
        return $records;
    }
    
    // function to return a group task informattion based on the current group task
    public function getGroupTaskInformation($grouptask_id){
        $sql = "SELECT * FROM $this->table WHERE grouptask_id='$grouptask_id'";
        $records = $this->lisdb->query($sql)->result_array();
        return $records[0];
    }
    
    // function to get the grouptask information
    function getTaskItemInformation($type, $item_value) {
      if($type == 'monthly') {
        $sql = "SELECT * FROM $this->i_table WHERE (grouptask_id='$this->grouptask_id' AND item_month='$item_value')";
      }
      else if($type == 'list') {
        $sql = "SELECT * FROM $this->i_table WHERE (grouptask_id='$this->grouptask_id' AND item_num='$item_value')";
      }
      else {
        $sql = "SELECT * FROM $this->i_table WHERE item_id='$item_value'";
      }

      $records = $this->lisdb->query($sql)->result_array();
      return $records[0];
    }
    
    // function to get the number of items for a grouptask
    public function getGroupTaskItems($grouptask_id=null) {
      $sql = "SELECT * FROM $this->i_table WHERE grouptask_id='$grouptask_id' ORDER BY item_num";
      $records = $this->lisdb->query($sql)->result_array();
      return $records;
    }
    
    public function getItemByMonth($grouptask_id){
        $sql = "SELECT * FROM $this->i_table WHERE grouptask_id='$grouptask_id' ORDER BY item_month";
        $records = $this->lisdb->query($sql)->result_array();
        return $records;
    }
    
    public function addTask($data){
        $sql = "INSERT INTO $this->table VALUES('', '$data[task_name]', '$data[type]', '$data[year]', '$data[manager_id]', '$data[notes]', '$data[userid]')";
        $this->lisdb->query($sql);
        return $this->lisdb->insert_id();
    }
    
    public function addTaskItem($data){
        $sql = "INSERT INTO $this->i_table VALUES('', '$data[grouptask_id]', '$data[item_num]', '$data[item_week]', '$data[item_month]', '$data[completed]', '$data[note]', '$data[userid]')";
        $this->lisdb->query($sql);
        return $this->lisdb->insert_id();
    }
    
    public function resetTaskItem($item_id){
        $sql = "UPDATE $this->i_table SET completed= 'NO' WHERE item_id = '$item_id'";
        $this->lisdb->query($sql);
    }
    
    public function updateTaskItem($note,$person,$item_id){
        $sql = "UPDATE $this->i_table SET note = '$note', userid='$person' WHERE item_id = '$item_id'";
        $this->lisdb->query($sql);
    }
    
    public function updateTaskNotes($grouptask_id,$notes){
        $sql = "UPDATE $this->table SET notes = '$notes' WHERE grouptask_id = '$grouptask_id'";
        $this->lisdb->query($sql);
    }
    
    public function updateTask($data){
        $sql = "UPDATE $this->table SET task_name= '$data[task_name]', manager_id = '$data[manager_id]', notes = '$data[notes]' WHERE grouptask_id = '$data[grouptask_id]'";
        $this->lisdb->query($sql);
    }
    
    public function setTaskItemCompleted($item_id){
        $sql = "UPDATE $this->i_table SET completed= 'YES' WHERE item_id = '$item_id'";
        $this->lisdb->query($sql);
    }
    
    public function deleteTask($grouptask_id){
        // remove the entry from instrulog table
        $sql = "DELETE FROM $this->table WHERE grouptask_id = '$grouptask_id'";
        $this->lisdb->query($sql);

        // remove any entries in the group_task items table
        $sql = "DELETE FROM $this->i_table WHERE grouptask_id = '$grouptask_id'";
        $this->lisdb->query($sql);
    }
    
    public function deleteTaskItem($item_id){
        $sql = "DELETE FROM $this->i_table WHERE item_id = '$item_id'";
        $this->lisdb->query($sql);
    }
    
    public function copyTask($mode,$year,$grouptask_id){
        $sql = "INSERT INTO $this->table (task_name, type, year, manager_id, notes, userid) 
            (SELECT task_name, type, year, manager_id, notes, userid FROM $this->table WHERE grouptask_id=$grouptask_id)";
        $this->lisdb->query($sql);
        $new_grouptask_id = $this->lisdb->insert_id();
            
        if ($mode == 2) { // copy to the following year
            // modify the year value
            $sql = "UPDATE $this->table SET year = '$year' WHERE grouptask_id = '$new_grouptask_id'";
            $this->lisdb->query($sql);
        }
        
        $this->copyGroupTaskItems($grouptask_id, $new_grouptask_id);
    }
    
    // function to copy grouptask items
    public function copyGroupTaskItems($grouptask_id, $new_grouptask_id) {
      // get all the old values
      $sql = "SELECT * FROM $this->i_table WHERE grouptask_id='$grouptask_id'";
      $records = $this->lisdb->query($sql)->result_array();

      foreach($records as $array) {
        $item_num = $array['item_num'];
        $item_week = $array['item_week'];
        $item_month = $array['item_month'];
        $userid = $array['userid'];

        $sql = "INSERT INTO $this->i_table VALUES('', '$new_grouptask_id', '$item_num', '$item_week', '$item_month', 'NO', '', '$userid')";
        $this->lisdb->query($sql);
      }
    }
    
}

