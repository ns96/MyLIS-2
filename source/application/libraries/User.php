<?php
/**
User objects for the mylis system
Copyright (c) 2007 Nathan Stevens

@author Nathan Stevens
@version 0.1 10-31-07
*/
// The user object
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
