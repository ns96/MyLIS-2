<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grouptask extends Group_Controller {
    
    var $userobj = null;
    var $myfiles = '';
    var $grouptask_id = ''; // the global group task ID
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');
	$this->restrict_access();
        
        // set the intrulog id here
        $gtask = $this->input->get('grouptask_id');
        if(!empty($gtask)) {
          $this->session->set_userdata('grouptask_id',$this->input->get('grouptask_id'));
        }
        $this->grouptask_id = $this->session->userdata('grouptask_id');
        
        $this->load->model('grouptask_model');
    }

    public function index(){
        if (!empty($this->grouptask_id)){
            $grouptask_info = $this->grouptask_model->get_group_task_information($this->grouptask_id);
            $type = $grouptask_info['type'];
        } else
            $type = '';

        if($type == 'monthly') {
            $data1['userid'] = $this->userobj->userid;
            $data1['role'] = $this->userobj->role;
            $data1['grouptask_id'] = $this->grouptask_id;
            $data1['grouptask_info'] = $grouptask_info;
	    $ismanager = false;
            if($this->userobj->role == 'admin' || $this->userobj->userid == $grouptask_info['manager_id']) {
              $ismanager = true;
            }
	    
	    $months = getMonths();
            
            $monthlyFields = '';
            // printout the hours now
            for($i = 1; $i < 7; $i++) {
              $month_name = $months[$i];
              $data2['month_name'] = $month_name;
              $data2['month_num'] = $i;
              $data2['item_info'] = $this->grouptask_model->get_task_item_information('monthly', $i);
              $monthlyFields .= $this->load->view('group/grouptask/monthly_task_field',$data2,TRUE);
            }

            $monthlyFields .= '</td>';
            $monthlyFields .= '<td style="width: 50%; vertical-align: top;">';

            for($i = 7; $i < 13; $i++) {
              $data2['month_name'] = $month_name;
              $data2['month_num'] = $i;
              $data2['item_info'] = $this->grouptask_model->get_task_item_information('monthly', $i);
              $monthlyFields .= $this->load->view('group/grouptask/monthly_task_field',$data2,TRUE);
            }

            $data3['grouptask_info'] = $grouptask_info;
            $data3['grouptask_id'] = $this->grouptask_id;
            $data1['taskNotesForm'] = $this->load->view('group/grouptask/task_notes_form',$data3,TRUE);
	    $data1['ismanager'] = $ismanager;
            
	    $this->load->model('user_model');
	    $data1['manager'] = $this->user_model->get_user($grouptask_info['manager_id']);
	    
            $data1['monthlyFields'] = $monthlyFields;
            $taskTable = $this->load->view('group/grouptask/monthly_task',$data1,TRUE);
        } else if($type == 'list') {
            $max_num = 0;
            $userList = $this->load_users();
            $ismanager = false;
            if($this->userobj->role == 'admin' || $this->userobj->userid == $grouptask_info['manager_id']) {
              $ismanager = true;
            }
            
            $data1['userid'] = $this->userobj->userid;
            $data1['role'] = $this->userobj->role;
            $data1['grouptask_id'] = $this->grouptask_id;
            $data1['grouptask_info'] = $grouptask_info;
            $data1['manager'] = $userList[$grouptask_info['manager_id']];
            $data1['ismanager'] = $ismanager;
            $groupTaskItems = $this->grouptask_model->get_group_task_items($this->grouptask_id);
            
            // get the number of entries for this group task
            $count =count($groupTaskItems);
            $half_count = ceil($count/2);
            
            $listTaskFields = '';
            for($i = 0; $i < $half_count; $i++) {
                $item_info = $groupTaskItems[$i];
                $data2['item_info'] = $item_info;
                $data2['ismanager'] = $ismanager;
                $listTaskFields .=$this->load->view('group/grouptask/list_task_field',$data2,TRUE);
                if($item_info['item_num'] > $max_num) {
                  $max_num = $item_info['item_num'];
                }
              }

              $listTaskFields .= '</td>';
              $listTaskFields .= '<td style="width: 50%; vertical-align: top;">';

              for($i = $half_count; $i < $count; $i++) {
                $item_info = $groupTaskItems[$i];
                $data2['item_info'] = $item_info;
                $data2['ismanager'] = $ismanager;
                $listTaskFields .=$this->load->view('group/grouptask/list_task_field',$data2,TRUE);
                if($item_info['item_num'] > $max_num) {
                  $max_num = $item_info['item_num'];
                }
              }
	      
	      $data1['max_num'] = $max_num;
              $data1['listTaskFields'] = $listTaskFields;
              $data1['count'] = $count;
              
              $data3['grouptask_info'] = $grouptask_info;
              $data3['grouptask_id'] = $this->grouptask_id;
              $data1['taskNotesForm'] = $this->load->view('group/grouptask/task_notes_form',$data3,TRUE);
              
              $taskTable = $this->load->view('group/grouptask/list_of_task',$data1,TRUE);
        } else {
            $taskTable = '';
        }
        
        $egrouptask_id = $this->input->get('egrouptask_id'); // group task id used for editing entry
        $y = $this->get_selected_year();

        // if egrouptask_id is not empty get its information from database
        if(!empty($egrouptask_id)) {
          $info = $this->grouptask_model->get_group_task_information($egrouptask_id);
          $manager_id = $info['manager_id'];
          $task_name = $info['task_name'];
          $button_title = 'Edit Task';
        } else {
            $manager_id = '';
            $button_title = 'Add New Task';
            $task_name = '';
        }
        $data4['users'] = $this->load_users();
        $data4['egrouptask_id'] = $egrouptask_id;
        $data4['y'] = $y;
        $data4['button_title'] = $button_title;
        $data4['task_name'] = $task_name;
        $addTaskForm = $this->load->view('group/grouptask/add_group_task_form',$data4,TRUE);
        
        $data['page_title'] = 'Group task management <span style="font-weight:normal; margin-left:15px">('.$this->get_group_task_name().')</span>';
        $data['taskTable'] = $taskTable;
        $data['selectedYear'] = $this->get_selected_year();
        $data['yearSelector'] = $this->display_year_selector();
        $data['addTaskForm'] = $addTaskForm;
	$data['taskPage'] = $this->load_task_page();
        $this->load_view('group/grouptask/main',$data);
    }
    
    public function update_tasks(){
        $task2 = $this->input->post('task2');
    
        if($task2 == 'reset') {
            $this->reset_task_items();
        } else if($task2 == 'update_info') {
            $this->update_task_items();
        } else if($task2 == 'add') {
            $this->add_task_item();
        } else {
            redirect('group/grouptask');
        }
    }
    
    public function printable(){
        $grouptask_info = $this->grouptask_model->get_group_task_information($this->grouptask_id);
        $task_name = $grouptask_info['task_name'];
        $year = $grouptask_info['year'];
        $type = $grouptask_info['type'];
        $notes = $grouptask_info['notes'];
        $months = array();
        $sql = '';
        if($type == 'monthly') {
            $months = getMonths();
	    $data['months'] = $months;
            $data['items'] = $this->grouptask_model->get_item_by_month($this->grouptask_id);
         } else if ($type == 'list') {
            $data['items'] = $this->grouptask_model->get_group_task_items($this->grouptask_id);
         }
        
        $data['task_name'] = $task_name;
        $data['year'] = $year;
        $data['type'] = $type;
        $this->load->view('group/grouptask/printable_page',$data);
    }
    
    public function set_task_item_completed(){
        $item_id = $this->input->get('item_id');
        $this->grouptask_model->set_task_item_completed($item_id);
  
        redirect('group/grouptask');
    }
    
    public function update_notes(){
        $notes = $this->input->post(notes);

        if(!strstr($notes, 'Enter task notes here')) {
            $this->grouptask_model->update_task_notes($this->grouptask_id,$notes);
        }

        redirect('group/grouptask');
    }
    
    public function add_edit_task(){
        $egrouptask_id = $this->input->post('egrouptask_id');
        $task_name = $this->input->post('taskname');
        $type = $this->input->post('tasktype');
        $manager_id = $this->input->post('manager_id');
        $notes = ''; // blank on purpose
        $grouptask_id = '';
        $userid = $this->userobj->userid;
        $year = $this->input->post('selected_year');

        if(!empty($task_name)) {
          if(empty($egrouptask_id)) { // add new entry
              $data['task_name'] = $task_name;
              $data['type'] = $type;
              $data['year'] = $year;
              $data['manager_id'] = $manager_id;
              $data['notes'] = $notes;
              $data['userid'] = $userid;
              $grouptask_id = $this->grouptask_model->add_task($data);

            // add entries to grouptask item table now
            if($type == 'monthly') {
                for ($i = 1; $i <= 12; $i++) {
                    $data['grouptask_id'] = $grouptask_id;
                    $data['item_num'] = '0';
                    $data['item_week'] = '0';
                    $data['item_month'] = $i;
                    $data['completed'] = 'NO';
                    $data['note'] = '';
                    $data['userid'] = '';
                    $this->grouptask_model->add_task_item($data);
                }
            } else if($type == 'list') {
              $list_count = $this->input->post('tasknum');
              if($list_count > 50) { // only allow up to 50 entries
                $list_count = 50;
              }

              for ($i = 1; $i <= $list_count; $i++) {
                    $data['grouptask_id'] = $grouptask_id;
                    $data['item_num'] = $i;
                    $data['item_week'] = '0';
                    $data['item_month'] = $i;
                    $data['completed'] = 'NO';
                    $data['note'] = '';
                    $data['userid'] = '';
                    $this->grouptask_model->add_task_item($data);
              }
            }
          } else { // update an existing entry
            $grouptask_id = $egrouptask_id;
            $data['task_name'] = $task_name;
            $data['manager_id'] = $manager_id;
            $data['notes'] = $notes;
            $data['grouptask_id'] = $grouptask_id;
            $this->grouptask_model->update_task($data);
          }
        }

        redirect("group/grouptask?grouptask_id=$grouptask_id");        
    }
    
    // function to add an item to the group task
    public function add_task_item() {
      $start_num = $this->input->post('max_num') + 1;
      $add_amount = $this->input->post('add_amount');
      $total = $this->input->post('total');
      $grouptask_id = $this->input->post('grouptask_id');

      for($i = $start_num; $i < ($start_num + $add_amount); $i++) {
        $data['grouptask_id'] = $grouptask_id;
        $data['item_num'] = $i;
        $data['item_week'] = '0';
        $data['item_month'] = '0';
        $data['completed'] = 'NO';
        $data['note'] = '';
        $data['userid'] = '';
        $this->grouptask_model->add_task_item($data);
        $total++;
        if($total > 50) {
          break;
        }
      }
      redirect('group/grouptask');
    }
    
    // function to reset the group task items
    public function reset_task_items() {
      $item_ids = $this->input->post('item_ids');

      if(!empty($item_ids)) {
            foreach($item_ids as $item_id) {
                $this->grouptask_model->reset_task_item($item_id);
            }
      }
      redirect('group/grouptask');
    }
    
    // function to update the group task items
    public function update_task_items() {
        $items = $this->grouptask_model->get_group_task_items($this->grouptask_id);

        foreach($items as $array) {
            $item_id = $array['item_id'];
            $person = $this->input->post("person_$item_id");
            $note = $this->input->post("note_$item_id");

            if($note == 'Note: ') {
              $note = '';
            }

            if($person != '(enter person assigned to task)') {
                $this->grouptask_model->update_task_item($note,$person,$item_id);
            }
        }
        redirect('group/grouptask');
    }
    
    public function copy_task($mode){
        $grouptask_id = $this->input->get('grouptask_id');
        $year = $this->input->get('year');

        $this->grouptask_model->copy_task($mode,$year,$grouptask_id);

        redirect('group/grouptask');
    }
    
    public function delete_task(){
        $grouptask_id = $this->input->get('grouptask_id');
        $this->grouptask_model->delete_task($grouptask_id);
        $this->session->unset_userdata('grouptask_id'); // unset the grouptask_id in session
        redirect('group/grouptask');
    }
    
    public function delete_task_item(){
        $item_id = $this->input->get('item_id');

        // remove the entry from instrulog table
        $this->grouptask_model->delete_task_item($item_id);

        redirect('group/grouptask');
    }
    
    // This is the source of the iframe area of main grouptask page
    public function load_task_page(){
        $y = $this->get_selected_year();
        $y_min = $y - 1;
        $y_max = $y + 1;

        $yearTasks = $this->grouptask_model->get_year_tasks($y_min,$y_max);
        $data['yearTasks'] = $yearTasks;
        $data['session_userid'] = $this->userobj->userid;
        $data['session_role'] = $this->userobj->role;
        $html = $this->load->view('group/grouptask/group_task_list',$data,TRUE);
	return $html;
    }
    
    // function to return the name of the selected group task
    protected function get_group_task_name() {
      $task_name = 'No Group Task Selected';

      if(!empty($this->grouptask_id)) {
        $array = $this->grouptask_model->get_group_task_information($this->grouptask_id);
        $task_name = $array['task_name'].' -- '.$array['year'];
      }
      return $task_name;
    }
    
    /** function to display button to allow selection of year
    *pry = previous year
    *chy = change year number
    */
    protected function display_year_selector() {
      $data['main_link'] = base_url().'group/grouptask';
      $data['y'] = $this->get_selected_year();

      $output = $this->load->view('group/grouptask/year_selector',$data,TRUE);
      return $output;
    }
    
    // function to get the selected year
    protected function get_selected_year() {
      $pry = $this->input->get('pry');
      $chy = $this->input->get('chy');
      $sy = $this->input->get('sy');
      $y = ''; //the current year

      if(isset($pry) && $pry > 2000) {
        $y= $pry + $chy;
      } 
      else if (isset($sy) && $sy > 2000) {
        $y= $sy;
      }
      else {
        $y= date('Y');
      }

      return $y;
    }
    

}
