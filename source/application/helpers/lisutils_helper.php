<?php

// checks if a role has the right to view a page-link
// (not implemented yet)
function viewLink($link, $role) {
    $view = true;
    /*Code 12/13/07*/
    return $view;
}

// function to add the session id to the url just in case cookies are off
function encodeURL($url) {
  $tmp_url = $url;
  
  if(defined('SID') && (!isset($_COOKIE[session_name()]))) {
    if(strstr($url, "?")) {
      $sa = explode('?', $url);
      
      $tmp_url = $sa[0].'?'.SID.'&'.$sa[1];
    }
    else {
      $tmp_url .= '?'.SID;
    }
  }
  
  return $tmp_url;
}

// function to return the base directory name
function getBaseDirectory() {
  $sp = strpos($_SERVER['SCRIPT_FILENAME'], "admin/cgi-bin");
  $directory = substr($_SERVER['SCRIPT_FILENAME'], 0, $sp);
  return $directory;
}

// function to return the base directory name
function getBaseUrl() {
  $sp = strpos($_SERVER['PHP_SELF'], "admin/cgi-bin");
  $directory = substr($_SERVER['PHP_SELF'], 0, $sp);
  $base_url = 'http://'.$_SERVER['HTTP_HOST']."$directory";
  return $base_url;
}

// function to get full script url
function getScriptUrl() {
  $script_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
  return encodeURL($script_url);
}

// function to get the login url
function getLoginUrl() {
  $sp = strpos($_SERVER['PHP_SELF'], "cgi-bin");
  $directory = substr($_SERVER['PHP_SELF'], 0, $sp);
  $login_url = 'http://'.$_SERVER['HTTP_HOST'].$directory;
  return $login_url;
}

// function to return the date is lis format
function getLISDate() {
  global $properties;
  global $lis_tz;
  
  $now_utc = mktime() + $lis_tz[$properties['lis.timezone']];
  $array = getdate($now_utc);
  $date_now = $array['mon'].'/'.$array['mday'].'/'.$array['year'];
  
  return $date_now;
}

// function to get the lis time
function getLISTime() {
  global $properties;
  global $lis_tz;
  
  $now_utc = mktime() + $lis_tz[$properties['lis.timezone']];
  $array = getdate($now_utc);
  
  $hour = $array['hours'];
  $am_pm = ' am';
  $min = $array['minutes'];
  
  if($hour > 12) {
    $hour -= 12;
    $am_pm = ' pm';
  }
  else if($hour == 12) {
    $am_pm = ' pm';
  }
  
  // add a leading zero to the minutes if needed
  $minutes = $min;
  if($min < 10) {
    $minutes = '0'.$min;
  }
  
  $time_now = $hour.':'.$minutes.$am_pm;
  
  return $time_now;
}

// function to get the date and time now
function getLISDateTime() {
  $date_time = getLISDate().' '.getLISTime();
  return $date_time;
}

// function to return the lis date and time given an epoch string
function getLISDateTimeFrom($edatetime) {
  $array = getdate($edatetime);
  
  $hour = $array['hours'];
  $am_pm = ' am';
  $min = $array['minutes'];
  
  if($hour > 12) {
    $hour -= 12;
    $am_pm = ' pm';
  }
  else if($hour == 12) {
    $am_pm = ' pm';
  }
  
  // add a leading zero to the minutes if needed
  $minutes = $min;
  if($min < 10) {
    $minutes = '0'.$min;
  }
  
  $ftime = $hour.':'.$minutes.$am_pm;
  $fdate = $array['mon'].'/'.$array['mday'].'/'.$array['year'];
  $date_time = $fdate.' '.$ftime;
  return $date_time;
}

// function to check to see if a date is in the correct LIS format
function checkLISDate($date) {
  $sa  = explode('/', $date);
  return checkdate($sa[0], $sa[1], $sa[2]);
}

function dateToLIS($date) {
  $sa  = explode('-', $date);
  $date = "$sa[1]/$sa[2]/$sa[0]";
  return $date;
}

// function to add a number of days to a given date
function addDaysToDate($date, $days) {
  $sa  = explode('/', $date);
  $old_date = mktime(0, 0, 0, $sa[0], $sa[1], $sa[2]);
  $new_date = $old_date + $days*86400;
  $array = getdate($new_date);
  $new_date = $array['mon'].'/'.$array['mday'].'/'.$array['year'];
  return $new_date;
}

// function to return days remaining from todays date and the date variable
function getDaysRemaining($date,$timediff) {
  $days = 0;
  $sa  = explode('/', $date);
  $date_utc = mktime(0, 0, 0, $sa[0], $sa[1], $sa[2]);
  $now_utc = mktime() + $timediff;
  $diff_seconds = $date_utc - $now_utc;
  $days = floor($diff_seconds/86400);
  return $days;
}

// function to get the expire date given a term
function getExpireDate($activate_date, $status, $term) {
  global $properties;
  $expire_date = '';
  
  if($status == 'trial') {
    $expire_date = addDaysToDate($activate_date, $properties['lis.trial.days']);
  }
  else if($term == 1) {
    $expire_date = addDaysToDate($activate_date, 365);
  }
  else if($term == 2) {
    $expire_date = addDaysToDate($activate_date, 730);
  }
  else if($term == 3) {
    $expire_date = addDaysToDate($activate_date, 1095);
  }
  else if($term == 4) {
    $expire_date = addDaysToDate($activate_date, 1460);
  }
  return $expire_date;
}

// function to print out a solid line in a certain color and size (1px, 2px, etc...)
function printColoredLine($color, $size) {
  echo "<div style='border-bottom:$size $color solid; width:100%;'></div>";
}

// function to replace @ and . in userid and replace with underscore
  function cleanUserID($userid) {
    $letters = array('@', '.');
    $userid = str_replace($letters, "_", $userid);
    return $userid;
  }

// add the http to the  any url
function checkURL($url) {
	$new_url = '';
	if(!stristr($url, 'http')) {
	    $new_url = 'http://'.$url;
	}
	else {
	    $new_url = $url;
	}
	return $new_url;
    }

// used to check to see if we have an email address
function valid_email($email) {
    // First, we check that there's one @ symbol, and that the lengths are right
    if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
	// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
	return false;
    }
    // Split it into sections to make life easier
    $email_array = explode("@", $email);
    $local_array = explode(".", $email_array[0]);
    for ($i = 0; $i < sizeof($local_array); $i++) {
	if (!preg_match("@^(([A-Za-z0-9!#$%&#038;'*+/=?^_`{|}~-][A-Za-z0-9!#$%&#038;'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$@", $local_array[$i])) {
	    return false;
	}
    }  
    if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
	$domain_array = explode(".", $email_array[1]);
	if (sizeof($domain_array) < 2) {
	    return false; // Not enough parts to domain
	}
	for ($i = 0; $i < sizeof($domain_array); $i++) {
	    if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
		return false;
	    }
	}
    }
    return true;
}
    
// function to check to see if the password is valid
  function valid_password($password) {
    $valid = true;
    if(strlen($password) < 6) {
      return false;
    }
    
    return $valid;
  }

  // function to display the list of location
  function displayLocationList($locations,$users,$home) {

    // display the main page now
    echo "<html>";
    echo "<head>";
    echo "<title>Location List</title>";
    echo "</head>";
    echo "<body>";

    // add the table that allows uploading of file
    $cell_color1 = 'rgb(180,200,235)';
    $cell_color2 = 'rgb(240,240,240)';

    echo '<table style="background-color: rgb(255, 255, 255); width: 100%; text-align: left;"
    border="0" cellpadding="2" cellspacing="2"><tbody>';

    echo '<tr>';
    echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
    <small><b>Location ID</b></small></td>';
    echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
    <small><b>Room #</b></small></td>';
    echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
    <small><b>Description</b></small></td>';
    echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
    <small><b>Assigned To</b></small></td>';
    echo '</tr>';

    foreach ($locations as $location){
	$location_id = $location['location_id'];
	$room = $location['room'];
	$description = $location['description'];
	$owner = $location['owner'];
	$owner_name = $owner;

	if(isset($users[$owner])) {
	    $owner_name = $users[$owner]->name;
	}
	$user_id = $location['userid'];

	echo '<tr>';
	echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
	<small><b>'.$location_id.'</b></small></td>';
	echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
	<small>'.$room.'</small></td>';
	echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
	<small>'.$description.'</small></td>';
	echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
	<small>'.$owner_name.'</small></td>';
	echo '</tr>';
    }

    echo '</tbody></table>';
    echo '</body></html>';
  }
  
  // function to return the category id
  function getCategoryID($cat_id) {
    $array = explode('_', $cat_id);
    return $array[1];
  }
  
  // function to return the names of months. used the the orderbook module
    function getMonths() {
    $months = array(1 => 'January', 'February', 'March', 'April', 'May',
		    'June', 'July', 'August', 'September', 'October', 
		    'November', 'December');
    return $months;
    }