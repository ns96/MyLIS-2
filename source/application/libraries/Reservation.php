<?php

/**
 * Used by the instrulog_model
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis
 */
class Reservation {
  var $reservation_id = ''; // the reservation id
  var $hour = ''; // the key used to access this
  var $userid = ''; // the userid object
  var $note = ''; // the userid object
  
  // the main construct
  public function __construct($data) {
    $this->reservation_id = $data['reservation_id'];
    $this->hour = $data['hour'];
    $this->userid = $data['userid'];
    $this->note = $data['note'];
  }
  
}
