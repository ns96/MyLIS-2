<?php

class Updater_model extends CI_Model {
    
    var $lisdb = null;
    
    public function __construct() {
	parent::__construct();
        $this->lisdb = $this->load->database('lisdb',TRUE);
    }
    
    // function to actually upgrade the 
    function update_account($account_id) {
	$output = '';
	$output .= "Updating <b>$account_id </b><br>";
	//$this->upgradeTo_1_1($account_id);
	$output .= $this->upgradeTo_1_3($account_id);
	$output .= "Finish update of <b>$account_id</b><br><br>";
	return $output;
    }
    
    // function to update to version 1.1.
    function upgradeTo_1_1($account_id) {
	$echo = '';
	$echo .= '<div style="margin-left: 40px;">';

	// add column to maxitems to orders table 
	$table = $account_id.'_orders';
	$sql = "ALTER TABLE $table ADD COLUMN maxitems SMALLINT DEFAULT 10";
	$this->lisdb->query($sql);
	$error = mysql_error();
	if(!empty($error)) {
	    $echo .= "SQL Error : $error <br>";
	} else {
	    $echo .= 'Added maxitems to orders table';
	}

	$echo .= '</div>';
	return $echo;
    }

    // function to update to version 1.1.
    function upgradeTo_1_3($account_id) {
	$echo = '';
	$echo .= '<div style="margin-left: 40px;">';

	// add group column to the chemicals table
	$table = $account_id.'_chemicals';
	$sql = "ALTER TABLE $table ADD COLUMN group VARCHAR(50)";
	$this->lisdb->query($sql);
	$error = mysql_error();

	if(!empty($error)) {
	    $echo .= "SQL Error : $error <br>";
	} else {
	    $echo .= 'Added group column to chemical table<br>';
	}

	// add the barcode column to the chemicals table
	$sql = "ALTER TABLE $table ADD COLUMN barcode VARCHAR(128)";
	$this->lisdb->query($sql);
	$error = mysql_error();

	if(!empty($error)) {
	    $echo .= "SQL Error : $error <br>";
	} else {
	    $echo .= 'Added barcode column to chemical table<br>';
	}

	// add group column to the supplies table
	$table = $account_id.'_supplies';
	$sql = "ALTER TABLE $table ADD COLUMN group VARCHAR(50)";
	$this->lisdb->query($sql);
	$error = mysql_error();

	if(!empty($error)) {
	    $echo .= "SQL Error : $error <br>";
	} else {
	    $echo .= 'Added group column to supplies table<br>';
	}

	// add the barcode column to the supplies table
	$sql = "ALTER TABLE $table ADD COLUMN barcode VARCHAR(128)";
	$this->lisdb->query($sql);
	$error = mysql_error();

	if(!empty($error)) {
	    $echo .= "SQL Error : $error <br>";
	} else {
	    $echo .= 'Added barcode column to supplies table<br>';
	}

	// add group column to the users table
	$table = $account_id.'_users';
	$sql = "ALTER TABLE $table ADD COLUMN group VARCHAR(50)";
	$this->lisdb->query($sql);
	$error = mysql_error();

	if(!empty($error)) {
	    $echo .= "SQL Error : $error <br>";
	} else {
	    $echo .= 'Added group column to chemical table<br>';
	}

	$echo .= '</div>';
	return $echo;
    }
    
    
}