<?php

class Manage_model extends CI_Model {
   
    var $lisdb = null;
    var $u_table = '';
    
    public function __construct() {
	parent::__construct();
	$$this->lisdb = $this->load->database('lisdb',TRUE);
        $this->u_table = $this->session->userdata('group').'_users';
    }
    
}