<?php
    

// initialize some links and variables
$title = "MyLIS Account";
$view_link = base_url().'admin/accounts/view/'.$account_id;
$db_link = encodeUrl(base_url().'admin/managedb/account/'.$account_id);
$back_link = encodeUrl(base_url().'admin/accounts');
$edit_link = encodeUrl(base_url().'admin/accounts/edit/'.$account_id);
$home_link = encodeUrl(base_url().'admin/main');

echo '<table style="width: 100%; text-align: left;" border="0" cellpadding="2" cellspacing="0">';
echo '<tbody>';
echo '<tr>';
echo '<td style="vertical-align: top;">';
echo '<h3><span style="color: rgb(0, 0, 102);">MyLIS Account ( </span>
<span style="color: rgb(255, 0, 0);">'.$account_id.'</span>
<span style="color: rgb(0, 0, 102);">)</span><br></h3>';
echo '</td>';
echo '<td style="vertical-align: top; text-align: right;">';
echo "<b>[ <a href=\"$view_link\" target=\"_blank\">View</a> ] ";
echo "<b>[ <a href=\"$db_link\">DB</a> ] ";
echo "[ <a href=\"$edit_link\">Edit</a> ] ";
echo "[ <a href=\"$back_link\">Back</a> ] ";
echo "[ <a href=\"$home_link\">Home</a> ]</b><br>";
echo '</td></tr></tbody></table>';
echo '<hr style="width: 100%; height: 2px;">';

if (!empty($accountInfo)){
    
} else {
    echo "No account found!";
}
// display the tables holding the information now
echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
echo '<tbody>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Account Manager</b></td>';
echo '<td style="width: 75%; vertical-align: center;">'.$managerInfo->name.'<br></td>';
echo'</tr>';

$group_pi = $accountInfo['pi_fname'].' '.$accountInfo['pi_mi'].' '.$accountInfo['pi_lname'];

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Principal Investigator</b></td>';
echo '<td style="width: 75%; vertical-align: center;">'.$group_pi.'</td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Group Name</b></td>';
echo '<td style="width: 75%; vertical-align: center;">'.$accountInfo['group_name'].'<br></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Group @</b></td>';
echo '<td style="width: 75%; vertical-align: center;">'.$accountInfo['group_type'].'</td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Discipline</b></td>';
echo '<td style="width: 75%; vertical-align: center;">'.$accountInfo['discipline'].'</td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Institution</b></td>';
echo '<td style="width: 75%; vertical-align: center;">'.$accountInfo['institution'].'</td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Phone Number</b></td>';
echo '<td style="width: 75%; vertical-align: center;">'.$accountInfo['phone'].'</td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Fax Number</b></td>';
echo '<td style="width: 75%; vertical-align: center;">'.$accountInfo['fax'].'</td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>E-mail</b></td>';
echo '<td style="width: 75%; vertical-align: center;">'.$accountInfo['email'].'</td>';
echo'</tr>';

/*$new_lines = array("\n", "\r\n");
$address = str_replace($new_lines, "<br>", $array[address]);
no longer used 11/5/07*/
echo '<tr>';
echo '<td style="width: 25%; vertical-align: top;"><b>Address</b></td>';
echo '<td style="width: 75%; vertical-align: center;"><pre>'.$accountInfo['address'].'</pre></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: top;"><b>UserID/Passwords</b></td>';
echo '<td style="width: 75%; vertical-align: center;">'.$userids_passwords.'</td>';
echo'</tr>';

echo '</tbody></table><br>';

// table holding billing info
echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
echo '<tbody>';

echo '<tr>';
echo '<td style="vertical-align: center;"><b>Term (years)</b></td>';
echo '<td style="vertical-align: center;"><b>Cost per Year</b></td>';
echo '<td style="vertical-align: center;"><b>Activated (mm/dd/yyyy)</b></td>';
echo '<td style="vertical-align: center;"><b>Expires (mm/dd/yyyy)</b></td>';
echo '<td style="vertical-align: center;"><b>Activation Code</b></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="vertical-align: center;">'.$accountInfo['term'].'</td>';
echo '<td style="vertical-align: center;">$'.$accountInfo['cost'].'</td>';
echo '<td style="vertical-align: center;">'.$accountInfo['activate_date'].'</td>';
echo '<td style="vertical-align: center;">'.$accountInfo['expire_date'].'</td>';
echo '<td style="vertical-align: center;">'.$accountInfo['activate_code'].'</td>';
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
echo '<td style="vertical-align: center;">'.ucfirst($accountInfo['status']).'</td>';
echo '<td style="vertical-align: center;">'.$accountInfo['storage'].' MB</td>';
echo '<td style="vertical-align: center;">'.$accountInfo['max_users'].'</td>';
echo '<td style="vertical-align: center;">'.$accountInfo['time_zone'].'</td>';
echo '</tr>';
echo '<tr>';
echo '<td style="vertical-align: top;"><b>Notes :</b></td>';

//$notes = str_replace($new_lines, "<br>", $array[notes]);
$notes = $accountInfo['notes'];
if(empty($notes)) {
    $notes = 'None';
}

echo '<td colspan="3" rowspan="1" style="vertical-align: top;"><pre>'.$notes.'</pre></td>';
echo '</tr>';
echo '</tbody></table><br>';

// display the tables holding the information now
echo '<b>Research Profile</b> ( Publicly Viewable : '.$accountProfile['public'].' )';
echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
echo '<tbody>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Keywords</b></td>';
echo '<td style="width: 75%; vertical-align: center;">'.$accountProfile['keywords'].'<br></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Research Description</b></td>';
echo '<td style="width: 75%; vertical-align: center;"><pre>'.$accountProfile['description'].'</pre><br></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Instruments</b></td>';
echo '<td style="width: 75%; vertical-align: center;"><pre>'.$accountProfile['instruments'].'</pre><br></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Website</b></td>';
echo '<td style="width: 75%; vertical-align: center;"><a href="'.$accountProfile['url'].'" target="_blank">'.$accountProfile['url'].'</a><br></td>';
echo'</tr>';

echo '<tr>';
echo '<td style="width: 25%; vertical-align: center;"><b>Collaborator IDs</b></td>';
echo '<td style="width: 75%; vertical-align: center;">'.$accountProfile['collaborator_ids'].'<br></td>';
echo'</tr>';

echo '</tbody></table><br>';
