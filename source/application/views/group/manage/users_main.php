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
?>
<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
        <tbody>
        <tr>
            <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
                Add New User 
                <span style="font-size:14px; font-weight: normal; margin-left: 15px; text-shadow: none">
                    <i>(all fields required)</i>
                </span>
            </td>
        </tr>
        </tbody>
    </table>
    <form action="<?=$add_user_link?>" enctype="multipart/form-data" method="POST" class="form-inline" style="margin-right:10px">
        <input type="hidden" name="user_add_form" value="posted">  
        <table class="formTable">
            <tr>
                <td>
                    <label for="userid" class="control-label">User ID (3 char. min)</label>
                    <input type="text" id="userid" name="userid" class="input-block-level">
                </td>
                <td>
                    <label for="password" class="control-label">Password (4 char. min)</label>
                    <input type="text" id="password" name="password" class="input-block-level">
                </td>
                <td>
                    <label for="role" class="control-label">Role :</label>
                    <select name="role" size="1">
                        <option value="user">Regular User</option>
                        <option value="buyer">Purchaser</option>
                        <option value="guest">Guest User</option>
                        <option value="guestbuyer">Guest Buyer</option>
                        <option value="admin">Administrator</option>
                    </select>
                </td>
                <td>
                    <label for="name" class="control-label">Full Name :</label>
                    <input type="text" id="name" name="name" class="input-block-level">
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top">
                    <label for="email" class="control-label">E-mail Address :</label>
                    <input type="text" id="email" name="email" class="input-block-level">
                </td>
                <td colspan="3">
                    <label for="info" class="control-label">Additional Info :</label>
                    <textarea id="info" name="info" class="input-block-level">Group Member</textarea>
                </td>
            </tr>
        </table>
        <button type="submit" class="btn btn-primary btn-small" onclick="addUser()">Add User</button>
    </form>
</div>
<?

// print any errors if any
echo '<small><b>'.$um_error.'</b></small>';

$update_userlist_link = base_url().'group/manage/userlist_modify';
// display the table that show the current list of users
?>
<div class="formWrapper">
    <form action="<?=$update_userlist_link?>" method="POST" class="form-inline" style="margin-right:10px">
        <input type="hidden" name="userlist_modify" value="posted">     
        <table class="formTable" id="add_user_form">
            <thead style="font-size:14px;">
                <th></th>
                <th>User ID</th>
                <th>Password</th>
                <th>Role</th>
                <th>Full Name</th>
                <th>E-mail</th>
                <th>Additional Info</th>
            </thead>
            <tbody>
<?

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
?>
  <tr>
    <td>
          <input type="checkbox" name="userids[]" value="<?=htmlentities($userid)?>">
    </td>
    <td>
          <?=$userid?>
    </td>
<?
  $userid = cleanUserID($userid);  // remove any @ or . in userid and replace with underscore
?>
    <td>
        <input type="text" id="password_<?=$userid?>" name="password_<?=$userid?>" class="input-block-level" value="<?=$password?>">
    </td>
    <td style="vertical-align: center; background-color: '.$cell_color2.';">
        <select name="role_<?=$userid?>" class="input-medium">
            <option value="<?=$role?>"><?=$role_name?></option>
            <option value="user">Regular User</option>
            <option value="buyer">Purchaser</option>
            <option value="guest">Guest User</option>
            <option value="guestbuyer">Guest Buyer</option>
            <option value="admin">Administrator</option>
        </select>
    </td>
    <td>
        <input type="text" name="name_<?=$userid?>" class="input-block-level" value="<?=htmlentities($name)?>">
    </td>
    <td>
        <input type="text" name="email_<?=$userid?>" class="input-block-level" value="<?=$email?>">
    </td>
    <td>
        <textarea name="info_<?=$userid?>" class="input-block-level"><?=$info?></textarea>
    </td>
  </tr>
  <?
}
?>
    <tr>
        <td colspan="6">
            <label class="radio">
                <input type="radio" name="modify_task" id="optionsRadios1" value="remove" checked>
                Remove Selected
            </label>
            <label class="radio">
                <input type="radio" name="modify_task" id="optionsRadios2" value="update">
                Update Selected
            </label>
        </td>
        <td>
            <div align="center">
                <button type="submit" class="btn btn-primary btn-small">Do Selected Task</button>
            </div>
        </td>
    </tr>

            </tbody>
        </table>
    </form>
</div>