<?php

/**
 * Used extensively throughout the application to hold user information
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis
 */
class User {
  var $userid;
  var $password;
  var $role;
  var $name;
  var $email;
  var $status; // this tell whether the person is present or past member
  var $info;
  
  public function __construct($userdata) {
    //function user($userid, $password, $role, $name, $email, $status, $info) {
    $this->userid = $userdata['userid'];
    $this->password = $userdata['password'];
    $this->role = $userdata['role'];
    $this->name = $userdata['name'];
    $this->email = $userdata['email'];
    $this->status = $userdata['status'];
    $this->info = $userdata['info'];
  }
}?>
