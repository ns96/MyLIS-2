<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Instrulog extends Group_Controller {
    
    private $userobj = null;
    
    public function __construct() {
	parent::__construct();
	$this->userobj = $this->session->userdata('user');
    }
    
    // Loads the reservation page
    public function index($instrument_id=''){
	
	// Loading data for the calendar subview
	$prm = $this->input->get('prm');
	$chm = $this->input->get('chm');
	$month = ''; 
	
	if ((!empty($prm)) && ($prm>0)){
	    $month= $prm + $chm;
	} else {
	    $month= date("m");
	}
	$data['m'] = $month;
	
	// Loading data for hoursTable subview
	$selected_date = $this->get_selected_date();
	$date = "$selected_date[1]/$selected_date[0]/$selected_date[2]";
	
	$this->load->model('instrulog_model');
	$reservations = $this->instrulog_model->get_reservations($date,$instrument_id);
	$data['s_date'] = $selected_date;

	$fieldsHTML1 = '';
	
	for($i = 0; $i < 12; $i++) {
	    $hour = $i.':00 AM';
	    if($i == 0) {
		$hour = '12:00 AM';
	    }
	    $fieldsHTML1 .= $this->get_reserve_field($i, $hour, $reservations);
	}

	$fieldsHTML2 = '';
	for($i = 12; $i < 24; $i++) {
	    $hour = $i;
	    if($i != 12) {
		$hour -= 12;
	    }
	    $hour = $hour.':00 PM';
	    $fieldsHTML2 .= $this->get_reserve_field($i, $hour, $reservations);
	}
	$data['fieldsHTML1'] = $fieldsHTML1;
	$data['fieldsHTML2'] = $fieldsHTML2;
	$data['instrument_id'] = $instrument_id;

	if (!empty($instrument_id)){
	    $instrument = $this->instrulog_model->get_instrument($instrument_id);
	    $instrument_name = $instrument['instrument'];
	} else {
	    $instrument_name = 'No Instrument Selected';
	}
	
	// Loading data for 'Add new Instrument' form
	$users = $this->get_current_users();
	$data['users'] = $users;
	
	// Loading data for Instrument List
	$instrumentList = $this->instrulog_model->get_instruments();
	$this->load->model('user_model');
	
	$instrumentsHTML = '';
	if(count($instrumentList) > 0) {
	    foreach($instrumentList as $instrument){
		$data['instrument'] = $instrument;
		$data['session_userid'] = $this->userobj->userid;
		$data['session_role'] = $this->userobj->role;
		$instrumentsHTML .= $this->load->view('group/instrulog/instrumentItem',$data,TRUE);
	    }
	} else {
	    $instrumentsHTML .= '<span style="color: #cc0000;"><small>
	    <b>Alert! Use form above to add an instrument.</b></small></span>';
	}
	$data['instrumentsHTML'] = $instrumentsHTML;
	
	// Load the main view which will load the subviews
	$data['page_title'] = "Instrument Log (<i>$instrument_name</i>)";
	$this->load_view('group/instrulog/main',$data);
    }
    
    // If a day was selected (from calendar), get the selected date from URL.
    // Otherwise return the current date.
    function get_selected_date() {

	$sd = $this->input->get('sd');
	$sm = $this->input->get('sm');
	$sy = $this->input->get('sy');
	
	if (empty($sd)){
	    $sd= date("d");  // curent day
	    $sm= date("m"); // current month
	    $sy= date("Y"); // current year
	}

	return array($sd, $sm, $sy);
    }
    
    // function to get reserve check box and text input. i = hour in 24 hour clock, display hour with am/pm
    // reservations array. If the user has no right to change the reservations, the checkboxes will be disabled.
    function get_reserve_field($i, $hour, $reservations) {
	$userid = $this->userobj->userid;
	$role = $this->userobj->role;

	$checkbox = '<input name="hour_'.$i.'" value="yes" type="checkbox">';
 
	$this->load->model('user_model');
	if(isset($reservations[$i])) {
	    $reservation = $reservations[$i];
	    $ruserid = $reservation->userid;
	    $note = $reservation->note;
	    $ruser = $this->user_model->get_user($ruserid);
	    $name = "( <b>$ruser->name</b> )";

	    if($ruserid == $userid || $role == 'admin') {
		$checkbox = '<input name="hour_'.$i.'" value="yes" type="checkbox" 
		checked="checked">';
	    }
	    else {
		$checkbox = '<input name="hour_'.$i.'" value="yes" type="checkbox" 
		checked="checked" disabled="disabled">';
	    }
	} else {
	    $note = 'Note: ';
	    $name = '';
	}

	$html = '<div class="instrulog_box">'.$checkbox.'<small><b> '.$hour.' 
	'.$name.'</b><br> <textarea name="note_'.$i.'" rows="1" class="input-block-level" style="margin-top:5px">'.htmlentities($note).'</textarea></small></div>';
	
	return $html;
    }
    
    // Delete an instrument from the group
    public function delete($instrument_id){

	$this->load->model('instrulog_model');
	$this->instrulog_model->delete_instrument($instrument_id);

	redirect('group/instrulog');
    }
    
    // Add a new instrument to the group
    public function add(){
	
	if (isset($_POST['add_instrument_form'])){
	    
	    $data['instrument'] = $this->input->post('instrument');
	    $data['manager_id'] = $this->input->post('manager');
	    $data['file_ids'] = ''; // blank on purpose
	    $data['notes'] = ''; // blank on purpose
	    $data['userid'] = $this->userobj->userid;
	    
	    if(!empty($data['instrument'])) {
		$this->load->model('instrulog_model');
		$this->instrulog_model->add_instrument($data);
		$instrulog_id = mysql_insert_id();
		redirect('group/instrulog/index?instrument_id='.$instrulog_id);
	    } else {
		redirect('group/instrulog/index');
	    }
	} else {
	    redirect('group/instrulog/index');
	}
    }

    // Update the reservations
    public function update($instrument_id=''){
	
	if (isset($_POST['update_reservations_form'])){
	    
	    $date = $this->input->post('date');
	    $userid = $this->userobj->userid;

	    $this->load->model('instrulog_model');
	    $reservations = $this->instrulog_model->get_reservations($date,$instrument_id);
	    
	    // check to see if to reserve all free time slots
	    if($this->input->post('alltimes') == 'yes') {
		for($i = 0; $i < 24; $i++) {
		    $data= array();
		    $note = $this->input->post("note_$i");
		    if(!isset($reservations[$i])) { // add reservation if none exist
			$data['instrument_id'] = $instrument_id;
			$data['date'] = $date;
			$data['i'] = $i;
			$data['userid'] = $userid;
			$data['note'] = $note;
			$this->instrulog_model->add_reservation($data);
		    }
		}
	    } else {
		for($i = 0; $i < 24; $i++) {
		    $res = $this->input->post("hour_$i");
		    $note = $this->input->post("note_$i");
		    $data = array();
		    if(isset($reservations[$i]) && $res != 'yes') { // remove reservation
			$this->instrulog_model->delete_reservation($reservations[$i]->reservation_id);
		    } elseif (isset($reservations[$i]) && $res == 'yes') { // update an entry that's already there
			$data['reservation_id'] = $reservations[$i]->reservation_id;
			$data['note'] = $note;
			$this->instrulog_model->update_reservation($data);
		    } elseif (!isset($reservations[$i]) && $res == 'yes') { // add reservation
			$data['instrument_id'] = $instrument_id;
			$data['i'] = $i;
			$data['note'] = $note;
			$data['userid'] = $userid;
			$data['date'] = $date;
			$this->instrulog_model->add_reservation($data);
		    }
		}
	    }
	    redirect('group/instrulog');
	} else {
	    redirect('group/instrulog/index/'.$instrument_id);
	}
    }
    
}