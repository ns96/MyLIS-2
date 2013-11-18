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

// function to get the home directory url
function getHomeUrl() {
  $script = $_SERVER['PHP_SELF'].'?task=main';
  $home_url = 'http://'.$_SERVER['HTTP_HOST'].$script;
  return encodeURL($home_url);
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