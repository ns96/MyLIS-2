<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
    }
    
    public function index(){
    
	$this->load->model('meeting_model');
	
	// Retrieve all the meeting dates for the selected year
	$year = $this->input->get('year');
	if(empty($year)) {
	    $year = date('Y');
	} 
	
	$semesters = $this->getSemesters($year);
	$default_semester = $this->getDefaultSemester();
	$dates = $this->meeting_model->getGMDates($year);

	// For each meeting date
	foreach($dates as $gmdate_id => $gd){
	    $allSlots[$gmdate_id] = $this->meeting_model->getDateSlots($gmdate_id);
	    // For each slot of this date, get the file URL (if a file has been uploaded)
	    foreach($allSlots[$gmdate_id] as $key => $singleSlot){
		$fileid = $singleSlot['file_id'];
		if (!empty($fileid)){
		    $allSlots[$gmdate_id][$key]['fileURL'] = $this->filemanager->getFileURL($fileid);
		}
	    }
	}
	
	$semesterHTML = '';
	// If there is even a single meeting date
	if(count($dates) > 0) {
	    if($default_semester == 1) { // list dates for the entire year
		foreach ($semesters as $semester_id => $name) {
		    if($this->hasSemester($dates, $semester_id)) {
			$data2['dates'] = $dates;
			$data2['allSlots'] = $allSlots;
			$data2['semester_id'] = $semester_id;
			$data2['name'] = $name;
			$data2['userid'] = $this->userobj->userid;
			$data2['role'] = $this->userobj->role;
			$semesterHTML .= $this->load->view('group/meetings/semesterDates',$data2,TRUE);
		    }
		}
	    } else { // list dates only for the sepecifies semester
		$data2['dates'] = $dates;
		$data2['allSlots'] = $allSlots;
		$data2['semester_id'] = $default_semester;
		$data2['name'] = $semesters[$default_semester];;
		$data2['userid'] = $this->userobj->userid;
		$data2['role'] = $this->userobj->role;
		$semesterHTML .= $this->load->view('group/meetings/semesterDates',$data2,TRUE);
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
		$slot_info = $this->meeting_model->getSlotInfo($slot_id);
		$title = 'Edit Group Meeting Slot';
	    } else {
		$slot_info = array();
		$title = 'Add Group Meeting Slot';
	    }
	    $data3['slot_info'] = $slot_info;
	    $data3['title'] = $title;
	    $data3['slot_id'] = $slot_id;
	    $data3['gmdates'] = $dates;
	    $addSlotHTML = $this->load->view('group/meetings/addSlotForm',$data3,TRUE);
	} else {
	    $addSlotHTML = '';
	}
	
	// If a date id has been posted, we diplay an 'Edit Date' form
	// instead of an 'Add Date' form
	$gmdate_id = $this->input->get('gmdate_id');
	if(!empty($gmdate_id)) {
	    $gmdate_info = $this->getGMDate($gmdate_id);
	    $title = 'Edit';
	    $date = dateToLIS($gmdate_info['gmdate']);
	    $time = $gmdate_info['gmtime'];
	    $target_link = base_url()."group/meetings/updateDate";
	} else {
	    $gmdate_info = array();
	    $date = 'MM/DD/'.$year;
	    $time = '';
	    $title = 'Add';
	    $target_link = base_url()."group/meetings/addDate";
	}
	$data4['gmdate_id'] = $gmdate_id;
	$data4['date'] = $date;
	$data4['time'] = $time;
	$data4['title'] = $title;
	$data4['gmdate_info'] = $gmdate_info;
	$data4['semesters'] = $semesters;
	$data4['target_link'] = $target_link;
	$addDateHTML = $this->load->view('group/meetings/addDateForm',$data4,TRUE);
	
	// Now that we have all the html sub-sections of the page
	// we load the wrapper view
	$data['page_title'] = 'Group Meetings';
	$data['year'] = $year;
	$data['semesters'] = $semesters;
	$data['semesterHTML'] = $semesterHTML;
	$data['addSlotHTML'] = $addSlotHTML;
	$data['addDateHTML'] = $addDateHTML;
	
	$this->load_view('group/meetings/main',$data);
    }
    
    public function editSlot(){
	if (isset($_POST['edit_slot_form'])){
	    $this->load->model('meeting_model');
	    $userid = $this->userobj->userid;

	    $data['slot_id']	= $this->input->post('slot_id');
	    $data['gmdate_id']	= $this->input->post('gmdate_id');
	    $data['type']	= $this->input->post('type');
	    $data['presenter']	= $this->input->post('presenter');
	    $data['title']	= $this->input->post('title');
	    $data['file_id']	= ''; // blank on purpose
	    $data['modify_date'] = getLISDate();

	    $this->meeting_model->updateSlot($data);
	}
	redirect('group/meetings');
    }
    
    public function addSlot(){
	if (isset($_POST['add_slot_form'])){
	    $this->load->model('meeting_model');
	    $userid = $this->userobj->userid;

	    $data['slot_id']	= $this->input->post('slot_id');
	    $data['gmdate_id']	= $this->input->post('gmdate_id');
	    $data['type']	= $this->input->post('type');
	    $data['presenter']	= $this->input->post('presenter');
	    $data['title']	= $this->input->post('title');
	    $data['file_id']	 = ''; // blank on purpose
	    $data['modify_date'] = getLISDate();

	    $this->meeting_model->addSlot($data);
	}
	redirect('group/meetings');
    }
    
    public function deleteSlot(){
	$this->load->model('meeting_model');
	$slot_id = $this->input->get('slot_id');
    
	if(!empty($slot_id)) {
	    $slot_info = $this->meeting_model->getSlotInfo($slot_id);
	    // delete any file
	    $file_id = $slot_info['file_id'];
	    if(!empty($file_id)) {
		$this->filemanager->deleteFile($file_id);
	    }
	    $this->meeting_model->deleteSlot($slot_id);
	}

	redirect('group/meetings');
    }
    
    public function addDate(){
	$this->load->model('meeting_model');
	
	$data['userid'] = $this->userobj->userid;
	$data['gmdate'] = dateToMySQL($this->input->post('gmdate'));
	$data['gmtime'] = $this->input->post('gmtime');
	$data['semester_id'] = $this->input->post('semester_id');

	$this->meeting_model->addDate();
	
	redirect('group/meetings');
    }
    
    public function updateDate(){
	$this->load->model('meeting_model');
	
	$data['userid'] = $this->userobj->userid;
	$data['gmdate_id'] = $this->input->post('gmdate_id');
	$data['gmdate'] = dateToMySQL($this->input->post('gmdate'));
	$data['gmtime'] = $this->input->post('gmtime');
	$data['semester_id'] = $this->input->post('semester_id');

	$this->meeting_model->updateDate();
	
	redirect('group/meetings');
    }
    
    public function deleteDate(){
	$userid = $this->userobj->userid;
	$role = $this->userobj->role;
	$gmdate_id = $this->input->get('gmdate_id');

	$this->load->model('meeting_model');
	$this->meeting_model->deleteDate($gmdate_id);

	redirect('group/meetings');
    }
    
    public function addFile(){
	if (isset($_POST['add_slotfile_form'])){
	    $slot_id = $this->input->post('slot_id');
	    $file_id = $this->input->post('file_id');
	    $modify_date = getLISDate();

	    if(empty($file_id)) { // add a new entry
		$file_id = $this->filemanager->uploadFile(1, $this->s_table, $slot_id);
	    } else { // update an existing file
		$this->filemanager->updateFile(1, $file_id);
	    }

	    $this->load->model('meeting_model');
	    $data['file_id'] = $file_id;
	    $data['modify_date'] = $modify_date;
	    $data['slot_id'] = $slot_id;
	    $this->meeting_model->updateSlotFile($data);

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
	    $this->load_view('group/meetings/addFileForm',$data);
	}
    }
    
    public function deleteFile(){

	$file_id = $this->input->get('file_id');

	$this->load->model('meeting_model');
	$data['modify_date'] = getLISDate();
	$data['slot_id'] = $this->input->get('slot_id');
	$this->meeting_model->deleteSlotFile($data);

	$this->filemanager->deleteFile($file_id);

	redirect('group/meetings');
    }
    
    // functon to return a list of semesters
    function getSemesters($year) {
	$semesters = array(
	    '1' => 'Year '.$year,
	    '2' => 'Winter Semester',
	    '3' => 'Spring Semester',
	    '4' => 'Summer Semester',
	    '5' => 'Fall Semester'
	);
	return $semesters;
  }
  
    // function to return the default semester ID
    function getDefaultSemester() {
	$semester_id = $this->input->get('semester_id');

	$ds = $this->input->get('default_semester');
	if(!empty($ds)) { // see whether to set the default semester
	    $this->setDefaultSemester($ds);
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

	    $semester_id = $this->proputil_model->getProperty('default.semester');
	    if(empty($semester_id)) {
		$semester_id = 1; // set default to display all
	    }
	    return $semester_id;
	}
    }

    // function to check if the list of dates contans the particular semester
    function hasSemester($dates, $semester_id) {
	$hasit = false;
	foreach ($dates as $gmdate_id => $gd) {
	    if($semester_id == $gd->semester_id) {
		$hasit = true;
		break;
	    }
	}
	return $hasit;
    }
  
    // function to set the default semester
    function setDefaultSemester() {
	$semester_id = $this->input->get('default_semester');
	
	// Setup paramaters for initializing models below
	$params['user'] = $this->userobj;
	$params['account'] = $this->session->userdata('group');
	$params['properties'] = $this->properties;
	// Load a Proputil model
	$this->load->model('proputil_model');
	$this->proputil_model->initialize($params);

	$this->proputil_model->storeProperty('default.semester', $semester_id);
    }
}