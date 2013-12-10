<?php

echo $menuHTML;

// add the table that allows adding of a new user
$cell_color1 = 'rgb(180,200,230)'; // a light blue
$cell_color2 = 'rgb(240,240,240)'; // a light gray

// javascript code to validate adding users
echo '<script language="Javascript">
function addUser() {
  // check to see if company is missing
  var userid = document.forms.form2.userid.value;
  if(userid.length < 3) {
    alert("Please Enter Valid User ID");
    return;
  }

  var password = document.forms.form2.password.value;
  if(password.length < 4) {
    alert("Please Enter Valid Password");
    return;
  }

  var name = document.forms.form2.name.value;
  if(name.length < 3) {
    alert("Please Enter Full Name");
    return;
  }

  var email = document.forms.form2.email.value;
  if(!echeck(email)) {
    alert("Please Enter Valid E-mail");
    return;
  }

  document.forms.form2.submit();
}

// code for checking valud email address
function echeck(str) {
  var at = "@";
  var dot = ".";
  var lat = str.indexOf(at);
  var lstr = str.length;
  var ldot = str.indexOf(dot);

  if (str.indexOf(at) == -1) {
    return false;
  }

  if (str.indexOf(at) == -1 || str.indexOf(at) == 0 || str.indexOf(at) == lstr) {
    return false;
  }

  if (str.indexOf(dot) == -1 || str.indexOf(dot) == 0 || str.indexOf(dot) == lstr) {
                return false;
  }

  if (str.indexOf(at, (lat+1)) != -1){
                return false;
  }

  if (str.substring(lat-1, lat) == dot || str.substring(lat+1, lat+2) == dot) {
                return false;
  }

  if (str.indexOf(dot,(lat+2)) == -1) {
                return false;
  }

  if (str.indexOf(" ") != -1) {
                return false;
  }

  return true;					
}
// End hiding script from older browsers-->              
</script>';
//--end of javascript code 

echo $importForm;

$add_user_link = base_url().'group/manage/users_add';
// add form to add a user
echo '<form name="form2" enctype="multipart/form-data" action="'.$add_user_link.'" method="POST">';
echo '<input type="hidden" name="user_add_form" value="posted">';

echo '<table style="background-color: rgb(255, 255, 255); width: 100%; text-align: left;"
border="0" cellpadding="1" cellspacing="2"><tbody>';

echo '<tr>';
echo '<td colspan="5" rowspan="1" style="vertical-align: top; text-align: left; background-color: '.$cell_color1.';">
<small><b>Add New User</b> (<i>all fields required</i>)</small></td>';
echo '</tr>';

echo '<td style="vertical-align: top; background-color: '.$cell_color2.';"><small>
User ID (3 char. min)<br><input type="text" name="userid" size="15">
</small></td>';

echo '<td style="vertical-align: top; background-color: '.$cell_color2.';"><small>
Password (4 char. min)<br><input type="text" name="password" size="15">
</small></td>';

echo '<td style="vertical-align: top; background-color: '.$cell_color2.';"><small>
Role</small><br>';
echo '<select name="role" size="1">';
echo '<option value="user">Regular User</option>';
echo '<option value="buyer">Purchaser</option>';
echo '<option value="guest">Guest User</option>';
echo '<option value="guestbuyer">Guest Buyer</option>';
echo '<option value="admin">Administrator</option>';
echo '</select></td>';

echo '<td style="vertical-align: top; background-color: '.$cell_color2.';"><small>
Full Name<br><input type="text" name="name" size="25">
</small></td>';

echo '<td colspan="2" rowspan="1" style="vertical-align: top; background-color: '.$cell_color2.';"><small>
E-mail Address<br><input type="text" name="email" size="25">
</small></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>Additional Info : </small></td>';
echo '<td colspan="3" rowspan="1" style="vertical-align: top; background-color: '.$cell_color2.';"><small>
<input type="text" name="info" size="65" value="Group Member">
</td>';
echo '<td style="vertical-align: top; text-align: center; background-color: '.$cell_color2.';">';
echo '<input type="button" value="Add User" 
style="background: rgb(238, 238, 238); color: #3366FF" onclick="addUser()"></td>';
echo '</tr>';
echo '</form></tbody></table><br>';

// print any errors if any
echo '<small><b>'.$um_error.'</b></small>';

$update_userlist_link = base_url().'group/manage/userlist_modify';
// display the table that show the current list of users
echo '<form enctype="multipart/form-data" action="'.$update_userlist_link.'" method="POST">';
echo '<input type="hidden" name="userlist_modify" value="posted">';

echo '<table style="background-color: rgb(255, 255, 255); width: 100%; text-align: left;"
border="0" cellpadding="1" cellspacing="2"><tbody>';

echo '<tr>';
echo '<td style="vertical-align: center; background-color: '.$cell_color1.';">
<br></td>';
echo '<td style="vertical-align: center; background-color: '.$cell_color1.';">
<small><b>User ID</b></small></td>';
echo '<td style="vertical-align: center; background-color: '.$cell_color1.';">
<small><b>Password</b></small></td>';
echo '<td style="vertical-align: center; background-color: '.$cell_color1.';">
<small><b>Role</b></small></td>';
echo '<td style="vertical-align: center; background-color: '.$cell_color1.';">
<small><b>Full Name</b></small></td>';
echo '<td style="vertical-align: center; background-color: '.$cell_color1.';">
<small><b>E-mail</b></small></td>';
echo '</tr>';

// list the users
foreach($users as $user) {
  $userid = $user->userid;
  $password = $user->password;
  $role = $user->role;
  $name = $user->name;
  $email = $user->email;
  $status = $user->status;
  $info = $user->info;

  // don't print the default user or any past users aka people who have been deleted
  if($userid == 'myadmin' || $status == 'past') {
    continue;
  }

  $role_name;
  if($role == 'user') {
    $role_name = 'Regular User';
  }
  else if($role == 'buyer') {
    $role_name = 'Purchaser';
  }
  else if($role == 'guest') {
    $role_name = 'Guest User';
  }
  else if($role == 'guestbuyer') {
    $role_name = 'Guest Buyer';
  }
  else if($role == 'admin') {
    $role_name = 'Administrator';
  }

  echo '<tr>';
  echo '<td colspan="1" rowspan="2" style="vertical-align: top; background-color: '.$cell_color2.';">
  <input type="checkbox" name="userids[]" value="'.htmlentities($userid).'"></td>';

  echo '<td colspan="1" rowspan="2" style="vertical-align: top; background-color: '.$cell_color2.';">
  '.$userid.'</td>';

  // remove any @ or . in userid and replace with underscore
  $userid = $cleanUserID($userid);

  echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
  <input type="text" name="password_'.$userid.'" size="15" value="'.$password.'"></td>';

  echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">';
  echo '<select name="role_'.$userid.'" size="1">';
  echo '<option value="'.$role.'">'.$role_name.'</option>';
  echo '<option value="user">Regular User</option>';
  echo '<option value="buyer">Purchaser</option>';
  echo '<option value="guest">Guest User</option>';
  echo '<option value="guestbuyer">Guest Buyer</option>';
  echo '<option value="admin">Administrator</option>';
  echo '</select></td>';

  echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
  <input type="text" name="name_'.$userid.'" size="25" value="'.htmlentities($name).'"></td>';

  echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
  <input type="text" name="email_'.$userid.'" size="25" value="'.$email.'"></td>';

  echo '</tr>';

  echo '<tr>';
  echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>
  Additional Info :</small></td>';

  echo '<td colspan="3" rowspan="1" style="vertical-align: top; background-color: '.$cell_color2.';"><small>
  </small><input type="text" name="info_'.$userid.'" size="65" value="'.$info.'"></td>';

  echo '</tr>';
}

echo '<tr>';
echo '<td colspan="5" rowspan="1" style="vertical-align: top; background-color: '.$cell_color2.';">
<input type="radio" value="remove" name="modify_task"><small>
<span style="font-weight: bold; color: #cc0000;">Remove Selected</span> 
<input type="radio" value="update" name="modify_task" checked="checked">
<span style="font-weight: bold; color: #cc0000;">Update Selected</span> 
</small></td>';
echo '<td style="vertical-align: center; text-align: center; background-color: '.$cell_color2.';">
<input type="submit" value="Do Selected Task" 
style="background: rgb(238, 238, 238); color: #3366FF"></td>';
echo '</tr>';

echo '</tbody></table>';
echo '</form>';
