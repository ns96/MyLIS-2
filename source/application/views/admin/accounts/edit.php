<?php

// initialize some links and variables
$title = "Edit MyLIS Account";
$remove_link = encodeUrl(base_url().'admin/accounts/remove/'.$account_id);
$back_link = encodeUrl(base_url().'admin/accounts/view.'.$account_id);

echo '<table style="width: 100%; text-align: left;" border="0" cellpadding="2" cellspacing="0">';
echo '<tbody>';
echo '<tr>';
echo '<td style="vertical-align: top;">';
echo '<h3><span style="color: rgb(0, 0, 102);">Edit MyLIS Account ( </span>
<span style="color: rgb(255, 0, 0);">'.$account_id.'</span>
<span style="color: rgb(0, 0, 102);">)</span><br></h3>';
echo '</td>';
echo '<td style="vertical-align: top; text-align: right;">';
echo "<b>[ <a href=\"$remove_link\">Remove Account</a> ] ";
echo "[ <a href=\"$back_link\">Back</a> ]</b><br>";
echo '</td></tr></tbody></table>';
echo '<hr style="width: 100%; height: 2px;">';

$edit_link = base_url().'admin/accounts/edit/'.$account_id;
$renew_link = base_url().'admin/accounts/renew';
// create the form now used to renew primium accounts here
echo "<form action='$renew_link' method='POST'>";
echo '<input type="hidden" name="task" value="accounts_renew_acct">';
echo '<input type="hidden" name="account_id" value="'.$account_id.'">';

echo 'Renew Account for <select name="term" size="1">
<option value="1">1 Year</option>
<option value="2">2 Years</option>
<option value="3">3 Years</option>
<option value="4">4 Years</option>
</select> ';
echo '<input type="submit" value="Renew">';
echo '</form>';

// create form that allows editing user information
echo "<form action='$edit_link' method='POST'>";
echo '<input type="hidden" name="account_edit_form" value="posted">';
echo '<input type="hidden" name="acct_manager" value="'.$accountInfo['manager_id'].'">';
echo '<input type="hidden" name="account_id" value="'.$account_id.'">';

echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
echo '<tbody>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Account Manager</b></td>';
echo '<td style="width: 75%; vertical-align: center;">';
echo '<select name="manager_id" size="1">';

echo  "<option value=\"$managerInfo->userid\" selected>$managerInfo->name</option>";
foreach($userList as $user) {
    echo  "<option value=\"$user->userid\">$user->name</option>";
}
echo '</select></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Principal Investigator</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<input type="text" name="fname" size="20" value="'.$accountInfo['pi_fname'].'">&nbsp;&nbsp;
<input type="text" name="mi" size="2" value="'.$accountInfo['pi_mi'].'">&nbsp;&nbsp;
<input type="text" name="lname" size="20" value="'.$accountInfo['pi_lname'].'"> 
</td></tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Group Name</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<input type="text" name="group_name" size="25" value="'.$accountInfo['group_name'].'"></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Group @</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<select size="1" name="group_type">
<option>'.$accountInfo['group_type'].'</option>';
foreach($gtypes as $gtype) {
    echo "<option>$gtype</option>";
}
echo '</select></td></tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Discipline</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<select name="discipline"><option>'.$accountInfo['discipline'].'</option>';
foreach($disciplines as $discipline) {
    echo "<option>$discipline</option>";
}
echo '</select></td></tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Institution Name</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<input type="text" name="institution_name" size="50" value="'.$accountInfo['institution'].'"></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Phone Number</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<input type="text" name="phone" size="25" value="'.$accountInfo['phone'].'"></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Fax Number</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<input type="text" name="fax" size="25" value="'.$accountInfo['fax'].'"></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>E-mail</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<input type="text" name="email" size="25" value="'.$accountInfo['email'].'"></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: top;"><b>Address</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<textarea rows="4" cols="50" name="address">'.$accountInfo['address'].'</textarea></td>';
echo'</tr>';
echo '</tbody></table><br>';

// table holding billing info
echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
echo '<tbody>';

echo '<tr>';
echo '<td style="vertical-align: center;"><b>Term (years)</b></td>';
echo '<td style="vertical-align: center;"><b>Cost per Year ($0.00)</b></td>';
echo '<td style="vertical-align: center;"><b>Activate (mm/dd/yyyy)</b></td>';
echo '<td style="vertical-align: center;"><b>Expires (mm/dd/yyyy)</b></td>';
echo '<td style="vertical-align: center;"><b>Activation Code</b></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="vertical-align: center;">'.$accountInfo['term'].'</td>';
echo '<td style="vertical-align: center;">$
<input type="text" name="cost" size="15" value="'.$accountInfo['cost'].'"></td>';
echo '<td style="vertical-align: center;">'.$accountInfo['activate_date'].'</td>';
echo '<td style="vertical-align: center;">
<input type="text" name="expire_date" size="15" value="'.$accountInfo['expire_date'].'"></td>';
echo '<td style="vertical-align: center;">
<input type="text" name="activate_code" size="10" value="'.$accountInfo['activate_code'].'"></td>';
echo '<tr>';

echo '</tbody></table><br>';

// table holding some account settings
echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
echo '<tbody>';

echo '<tr>';
echo '<td style="vertical-align: center;"><b>Status</b></td>';
echo '<td style="vertical-align: center;"><b>Storage</b></td>';
echo '<td style="vertical-align: center;"><b>Max Users</b></td>';
echo '<td style="vertical-align: center;"><b>Time Zone</b></td>';
echo'</tr>';

echo '<tr>';

echo '<td style="vertical-align: center;">
<select name="status" size="1">
<option value="'.$accountInfo['status'].'">'.ucfirst($accountInfo['status']).'</option>
<option value="trial">Trial</option>
<option value="active">Active</option>
<option value="premium">Premium</option>
<option value="suspended">Suspended</option>
</select></td>';

echo '<td style="vertical-align: center;">
<select name="storage" size="1">
<option value="'.$accountInfo['storage'].'">'.$accountInfo['storage'].' MB</option>
<option value="10">10 MB (+ $0.00)</option>
<option value="200">200 MB (+ $'.$properties['storage.cost.200MB'].')</option>
<option value="500">500 MB (+ $'.$properties['storage.cost.500MB'].')</option>
<option value="1000">1000 MB (+ $'.$properties['storage.cost.1000MB'].')</option>
</select></td>';

echo '<td style="vertical-align: center;">
<input type="text" name="max_users" size="15" value="'.$accountInfo['max_users'].'"></td>';

echo '<td style="vertical-align: center;"><select name="time_zone" size="1">';
echo '<option value="'.$accountInfo['time_zone'].'">'.$accountInfo['time_zone'].'</option>';
foreach($lis_tz as $key => $value) {
echo '<option value="'.$key.'">'.$key.'</option>';
}
echo '</select></td>';

echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top;"><b> Current Notes :</b></td>';
echo '<td colspan="3" rowspan="1" style="vertical-align: top;">
<textarea rows="3" cols="65" name="notes">'.$accountInfo['notes'].'</textarea></td>';
echo '<tr>';

echo '<tr>';
echo '<td style="vertical-align: top;"><b> New Note :</b></td>';
echo '<td colspan="3" rowspan="1" style="vertical-align: top;">
<input size="65" name="new_note"></td>';
echo '<tr>';

echo '</tbody></table><hr><hr>';

// add table holding research profile

echo '<big><b>Research Profile</b></big> ( Publicly Viewable : 
<select name="public" size="1">
<option value="'.$accountProfile['public'].'">'.$accountProfile['public'].'</option>
<option value="YES">YES</option>
<option value="NO">NO</option>
</select> )<br>';

echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
echo '<tbody>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Keywords</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<input type="text" name="keywords" size="50" value="'.$accountProfile['keywords'].'">';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Research Description</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<textarea rows="4" cols="50" name="description">'.$accountProfile['description'].'</textarea><br></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Group Instruments</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<textarea rows="4" cols="50" name="instruments">'.$accountProfile['instruments'].'</textarea><br></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Website</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<input type="text" name="piurl" size="50" value="'.$accountProfile['url'].'">';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Collaborator IDs</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<input type="text" name="collaborators" size="50" value="'.$accountProfile['collaborator_ids'].'">';
echo'</tr>';

echo '</tbody></table>';
echo '<div style="text-align: right;">';
echo '<input type="submit" value="Edit Account">';
echo '</div>';
echo '</form>';
    
