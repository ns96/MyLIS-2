<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Manages the arrangement of group meetings
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis
 */
class Meetings extends Group_Controller {
    
    private $userobj = null;
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');
	
	// Setup paramaters for initializing models below
	$params['user'] = $this->userobj;
	$params['account'] = $this->session->userdata('group');
	$params['properties'] = $this->properties;
	
	// Load a FileManager model
	$this->load->model('filemanager');
	$this->filemanager->initialize($params);
	
	$this->restrict_access();
    }
    
    /**
     * Loads the main group meeting page
     */
    public function index(){
    
	$this->load->model('meeting_model');
	
	// Retrieve all the meeting dates for the selected year
	$year = $this->input->get('year');
	if(empty($year)) {
	    $year = date('Y');
	} 
	
	$semesters = $this->get_semesters($year);
	$default_semester = $this->get_default_semester();
	$dates = $this->meeting_model->get_gm_dates($year);

	// For each meeting date
	foreach($dates as $gmdate_id => $gd){
	    $allSlots[$gmdate_id] = $this->meeting_model->get_date_slots($gmdate_id);
	    // For each slot of this date, get the file URL (if a file has been uploaded)
	    foreach($allSlots[$gmdate_id] as $key => $singleSlot){
		$fileid = $singleSlot['file_id'];
		if (!empty($fileid)){
		    $allSlots[$gmdate_id][$key]['fileURL'] = $this->filemanager->get_file_url($fileid);
		}
	    }
	}
	
	$semesterHTML = '';
	// If there is even a single meeting date
	if(count($dates) > 0) {
	    if($default_semester == 1) { // list dates for the entire year
		foreach ($semesters as $semester_id => $name) {
		    if($this->has_semester($dates, $semester_id)) {
			$data2['dates'] = $dates;
			$data2['allSlots'] = $allSlots;
			$data2['semester_id'] = $semester_id;
			$data2['name'] = $name;
			$data2['userid'] = $this->userobj->userid;
			$data2['role'] = $this->userobj->role;
			$semesterHTML .= $this->load->view('group/meetings/semester_dates',$data2,TRUE);
		    }
		}
	    } else { // list dates only for the sepecifies semester
		$data2['dates'] = $dates;
		$data2['allSlots'] = $allSlots;
		$data2['semester_id'] = $default_semester;
		$data2['name'] = $semesters[$default_semester];;
		$data2['userid'] = $this->userobj->userid;
		$data2['role'] = $this->userobj->role;
		$semesterHTML .= $this->load->view('group/meetings/semester_dates',$data2,TRUE);
	    }
	} else { // If there are not meeting dates 
	    $semesterHTML = '<tr><td valign="top" width="10%"><br></td>'; // print a blank
	    $semesterHTML .= '<td valign="top" bgcolor="#FFFFBE">';
	    $semesterHTML .= '<span style="color: #cc0000;"><small><b>Please add group 
	    meeting dates below ...</b></small></span>'; // display message to add meeting dates
	    $semesterHTML .= '</td></tr>';
	}
	
	// If there is not any meeting date registered, 
	// there should not be any 'add slot' form
	if (count($dates)>0){
	    $slot_id = $this->input->get('slot_id');
	    
	    // If a slot id has been posted, we diplay an 'Edit Slot' form
	    // instead of an 'Add Slot' form
	    if(!empty($slot_id)) {
		$slot_info = $this->meeting_model->get_slot_info($slot_id);
		$title = 'Update Slot';
	    } else {
		$slot_info = array();
		$title = 'Add Slot';
	    }
	    $data3['slot_info'] = $slot_info;
	    $data3['title'] = $title;
	    $data3['slot_id'] = $slot_id;
	    $data3['gmdates'] = $dates;
	    $addSlotHTML = $this->load->view('group/meetings/add_slot_form',$data3,TRUE);
	} else {
	    $addSlotHTML = '';
	}
	
	// If a date id has been posted, we diplay an 'Edit Date' form
	// instead of an 'Add Date' form
	$gmdate_id = $this->input->get('gmdate_id');
	if(!empty($gmdate_id)) {
	    $gmdate_info = $this->meeting_model->get_gm_date($gmdate_id);
	    $title = 'Update';
	    $date = dateToLIS($gmdate_info['gmdate']);
	    $time = $gmdate_info['gmtime'];
	    $target_link = base_url()."group/meetings/update_date";
	} else {
	    $gmdate_info = array();
	    $date = 'MM/DD/'.$year;
	    $time = '';
	    $title = 'Add';
	    $target_link = base_url()."group/meetings/add_date";
	}
	$data4['gmdate_id'] = $gmdate_id;
	$data4['date'] = $date;
	$data4['time'] = $time;
	$data4['title'] = $title;
	$data4['gmdate_info'] = $gmdate_info;
	$data4['semesters'] = $semesters;
	$data4['target_link'] = $target_link;
	$addDateHTML = $this->load->view('group/meetings/add_date_form',$data4,TRUE);
	
	// Now that we have all the html sub-sections of the page
	// we load the wrapper view
	$data['page_title'] = 'Group Meetings';
	$data['year'] = $year;
	$data['semesters'] = $semesters;
	$data['semesterHTML'] = $semesterHTML;
	$data['addSlotHTML'] = $addSlotHTML;
	$data['addDateHTML'] = $addDateHTML;
        $data['default_semester'] = $default_semester;
	
	$this->load_view('group/meetings/main',$data);
    }
    
    /**
     * Updates a specific slot of a group meeting date
     */
    public function edit_slot(){
	if (isset($_POST['edit_slot_form'])){
	    $this->load->model('meeting_model');
	    $userid = $this->userobj->userid;

	    $data['slot_id']	= $this->input->post('slot_id');
	    $data['gmdate_id']	= $this->input->post('gmdate_id');
	    $data['type']	= $this->input->post('type');
	    $data['presenter']	= $this->input->post('presenter');
	    $data['title']	= $this->input->post('title');
	    $data['file_id']	= ''; // blank on purpose
	    $data['modify_date'] = $this->get_lis_date();

	    $this->meeting_model->update_slot($data);
	}
	redirect('group/meetings');
    }
    
    /**
     * Adds a new slot on a meeting date
     */
    public function add_slot(){
	if (isset($_POST['add_slot_form'])){
	    $this->load->model('meeting_model');
	    $userid = $this->userobj->userid;

	    $data['slot_id']	= $this->input->post('slot_id');
	    $data['gmdate_id']	= $this->input->post('gmdate_id');
	    $data['type']	= $this->input->post('type');
	    $data['presenter']	= $this->input->post('presenter');
	    $data['title']	= $this->input->post('title');
	    $data['file_id']	 = ''; // blank on purpose
	    $data['modify_date'] = $this->get_lis_date();
	    $this->meeting_model->add_slot($data);
	}
	redirect('group/meetings');
    }
    
    /**
     * Removes a slot from meeting data
     */
    public function delete_slot(){
	$this->load->model('meeting_model');
	$slot_id = $this->input->get('slot_id');
    
	if(!empty($slot_id)) {
	    $slot_info = $this->meeting_model->get_slot_info($slot_id);
	    // delete any file
	    $file_id = $slot_info['file_id'];
	    if(!empty($file_id)) {
		$this->filemanager->delete_file($file_id);
	    }
	    $this->meeting_model->delete_slot($slot_id);
	}

	redirect('group/meetings');
    }
    
    /**
     * Adds a new meeting date
     */
    public function add_date(){
	$this->load->model('meeting_model');
	
	$data['userid'] = $this->userobj->userid;
	$data['gmdate'] = dateToMySQL($this->input->post('gmdate'));
	$data['gmtime'] = $this->input->post('gmtime');
	$data['semester_id'] = $this->input->post('semester_id');
	$this->meeting_model->add_date($data);
	
	redirect('group/meetings');
    }
    
    /**
     * Updates the information about a meeting date
     */
    public function update_date(){
	$this->load->model('meeting_model');
	
	$data['userid'] = $this->userobj->userid;
	$data['gmdate_id'] = $this->input->post('gmdate_id');
	$data['gmdate'] = dateToMySQL($this->input->post('gmdate'));
	$data['gmtime'] = $this->input->post('gmtime');
	$data['semester_id'] = $this->input->post('semester_id');

	$this->meeting_model->update_date();
	
	redirect('group/meetings');
    }
    
    /**
     * Deletes a meeting date alongside with it's slots
     */
    public function delete_date(){
	$userid = $this->userobj->userid;
	$role = $this->userobj->role;
	$gmdate_id = $this->input->get('gmdate_id');

	$this->load->model('meeting_model');
	$this->meeting_model->delete_date($gmdate_id);

	redirect('group/meetings');
    }
    
    /**
     * Displays a form for attaching a file to meeting date or attaches the
     * file if the form has been posted.
     */
    public function add_file(){
	if (isset($_POST['add_slotfile_form'])){
	    $slot_id = $this->input->post('slot_id');
	    $file_id = $this->input->post('file_id');
	    $modify_date = $this->get_lis_date();

	    if(empty($file_id)) { // add a new entry
		$file_id = $this->filemanager->upload_file(1, $this->s_table, $slot_id);
	    } else { // update an existing file
		$this->filemanager->update_file(1, $file_id);
	    }

	    $this->load->model('meeting_model');
	    $data['file_id'] = $file_id;
	    $data['modify_date'] = $modify_date;
	    $data['slot_id'] = $slot_id;
	    $this->meeting_model->update_slot_file($data);

	    redirect('group/meetings');
	} else {
	    $data['slot_id'] = $this->input->get('slot_id');
	    $data['file_id'] = $this->input->get('file_id');
	    
	    if(!empty($file_id)) {
		$data['title'] = 'Update Group Meeting File';
	    } else {
		$data['title'] = 'Add New Group Meeting File';
	    }
	    
	    $data['page_title'] = 'Add New Meeting File';
	    $this->load_view('group/meetings/add_file_form',$data);
	}
    }
    
    /**
     * Deletes a file that was attached to a meeting date
     */
    public function delete_file(){

	$file_id = $this->input->get('file_id');

	$this->load->model('meeting_model');
	$data['modify_date'] = $this->get_lis_date();
	$data['slot_id'] = $this->input->get('slot_id');
	$this->meeting_model->delete_slot_file($data);

	$this->filemanager->delete_file($file_id);

	redirect('group/meetings');
    }
    
    /**
     * Returns a list of semesters
     * 
     * @param int $year
     * @return array
     */
    protected function get_semesters($year) {
	$semesters = array(
	    '1' => 'Year '.$year,
	    '2' => 'Winter Semester',
	    '3' => 'Spring Semester',
	    '4' => 'Summer Semester',
	    '5' => 'Fall Semester'
	);
	return $semesters;
  }
  
    /**
     * Returns the default semester ID
     * 
     * @return int
     */
    protected function get_default_semester() {
	$semester_id = $this->input->get('semester_id');

	$ds = $this->input->get('default_semester');
	if(!empty($ds)) { // see whether to set the default semester
	    $this->set_default_semester($ds);
	    return $ds;
	}

	if(!empty($semester_id)) {
	    return $semester_id;
	} else { // read default value from database

	    $params['user'] = $this->userobj;
	    $params['account'] = $this->session->userdata('group');
	    $params['properties'] = $this->properties;
	    
	    $this->load->model('proputil_model');
	    $this->proputil_model->initialize($params);

	    $semester_id = $this->proputil_model->get_property('default.semester');
	    if(empty($semester_id)) {
		$semester_id = 1; // set default to display all
	    }
	    return $semester_id;
	}
    }

    /**
     * Checks if the list of dates contains the particular semester
     * 
     * @param array $dates
     * @param int $semester_id
     * @return boolean
     */
    protected function has_semester($dates, $semester_id) {
	$hasit = false;
	foreach ($dates as $gmdate_id => $gd) {
	    if($semester_id == $gd->semester_id) {
		$hasit = true;
		break;
	    }
	}
	return $hasit;
    }
  
    /**
     * Sets the default semester
     */
    protected function set_default_semester() {
	$semester_id = $this->input->get('default_semester');
	
	// Setup paramaters for initializing models below
	$params['user'] = $this->userobj;
	$params['account'] = $this->session->userdata('group');
	$params['properties'] = $this->properties;
	// Load a Proputil model
	$this->load->model('proputil_model');
	$this->proputil_model->initialize($params);

	$this->proputil_model->store_property('default.semester', $semester_id);
    }
}