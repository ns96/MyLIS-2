<?php

$home_link = base_url().'admin/main';
$target_link = base_url().'admin/accounts/create';

echo '<table style="width: 100%; text-align: left;" border="0" cellpadding="2" cellspacing="0">';
echo '<tbody>';
echo '<tr>';
echo '<td style="vertical-align: top;">';
echo '<h3><span style="color: rgb(0, 0, 102);">Add MyLIS Account</span></h3>';
echo '</td>';
echo '<td style="vertical-align: top; text-align: right;">';
echo "<b>[ <a href=\"$home_link\">Home</a> ]</b><br>";
echo '</td></tr></tbody></table>';
echo '<hr style="width: 100%; height: 2px;">';

echo "<form action='$target_link' method='POST'>";
echo '<input type="hidden" name="add_account_form" value="posted">';

// table holding account information
echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
echo '<tbody>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Account ID</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<input type="text" name="account_id" size="20"> leave blank to set automatically</td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Principal Investigator</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<input type="text" name="fname" size="20"> 
<input type="text" name="mi" size="2"> 
<input type="text" name="lname" size="20"> 
</td></tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Group Name</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<input type="text" name="group_name" size="20"><br></td></tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Group @</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<select size="1" name="group_type">';
foreach($gtypes as $gtype) {
    echo "<option>$gtype</option>";
}
echo '</select></td></tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Discipline</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<select size="1" name="discipline">';
foreach($disciplines as $discipline) {
    echo "<option>$discipline</option>";
}
echo '</select></td></tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Institution Name</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<input type="text" name="institution_name" size="25"></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Phone Number</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<input type="text" name="phone" size="25"></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Fax Number</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<input type="text" name="fax" size="25"></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>E-mail</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<input type="text" name="email" size="25"></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Password</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<input type="text" name="password1" size="15"> Re-enter password 
<input type="text" name="password2" size="15"></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: top;"><b>Address</b></td>';
echo '<td style="width: 75%; vertical-align: center;">
<textarea rows="3" cols="45" name="address"></textarea></td>';
echo'</tr>';
echo '</tbody></table><br>';

// table holding billing info
echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
echo '<tbody>';

echo '<tr>';
echo '<td style="vertical-align: center;"><b>Term (years)</b></td>';
echo '<td style="vertical-align: center;"><b>Cost per Year ($0.00)</b></td>';
echo '<td style="vertical-align: center;"><b>Activated (mm/dd/yyyy)</b></td>';
echo '<td style="vertical-align: center;"><b>Expires (mm/dd/yyyy)</b></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="vertical-align: center;">
<select name="term" size="1">
<option value="1">1 Year</option>
<option value="2">2 Years</option>
<option value="3">3 Years</option>
<option value="4">4 Years</option>
</select></td>';

echo '<td style="vertical-align: center;">$
<input type="text" name="cost" size="15" value="'.$properties['lis.cost'].'"></td>';

echo '<td style="vertical-align: center;">
<input type="text" name="activate_date" size="15" value="'.getLISdate().'"></td>';

echo '<td style="vertical-align: center;">Automatically Set</td>';

echo '</tr>';
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
<option value="trial">Trial</option>
<option value="active">Active</option>
<option value="premium">Premium</option>
<option value="suspended">Suspended</option>
<option value="custom">Custom</option>
</select></td>';

echo '<td style="vertical-align: center;">
<select name="storage" size="1">
<option value="10">10 MB (+ $0.00)</option>
<option value="100">200 MB (+ $'.$properties['storage.cost.200MB'].')</option>
<option value="500">500 MB (+ $'.$properties['storage.cost.500MB'].')</option>
<option value="1000">1000 MB (+ $'.$properties['storage.cost.1000MB'].')</option>
</select></td>';

echo '<td style="vertical-align: center;">
<input type="text" name="max_users" size="15" value="'.$properties['lis.max.users'].'"></td>';

echo '<td style="vertical-align: center;"><select name="time_zone" size="1">';
foreach($lis_tz as $key => $value) {
    echo '<option value="'.$key.'">'.$key.'</option>';
}
echo '</select></td></tr>';

echo '<tr>';
echo '<td style="vertical-align: top;"><b>Notes :</b></td>';
echo '<td colspan="3" rowspan="1" style="vertical-align: top;">
<textarea rows="3" cols="65" name="notes"></textarea></td>';
echo '</tr>';
echo '</tbody></table><br>';

echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
echo '<tbody>';

echo '<tr>
<td style="vertical-align: top; width: 25%;"><b>Research Keywords :</b></td>
<td>Enter kewords related to research seperated by commas<br>
<input size="50" name="keywords"></td></tr>';

echo '<tr>
<td style="vertical-align: top;"><b>Research Description :</b></td>
<td><textarea cols="50" rows="4" name="description"></textarea></td></tr>';

echo '<tr><td><b>Group or PI webpage :</b></td>
<td><input size="50" name="piurl"></td></tr>';
echo '</tbody></table>';

echo '<div style="text-align: right;">';
echo '<input type="submit" value="Add Account">';
echo '</div>';
echo '</form>';

