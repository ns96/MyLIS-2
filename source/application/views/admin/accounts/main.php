<?php

// initialize some links
$add_link = base_url().'admin/accounts/create';

// display the main page now
echo '<table style="width: 100%; text-align: left;" border="0" cellpadding="2" cellspacing="0">';
echo '<tbody>';
echo '<tr>';
echo '<td style="vertical-align: top;">';
echo '<h3><span style="color: rgb(0, 0, 102);">MyLIS Accounts</span><br></h3>';
echo '</td>';
echo '<td style="vertical-align: top; text-align: right;">';
echo "<b>[ <a href=\"$add_link\">Add</a> ]</b><br>";
echo '</td></tr></tbody></table>';

echo '<hr style="width: 100%; height: 2px;">';

// get the accounts from database
$total_acct = 0;
$active_acct = 0;
$trial_acct = 0;
$susp_acct = 0;

$html = '';

if(count($accountList) >= 1) {
    $total_acct =count($accountList);

    $html .= '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
    $html .=  '<tbody>';
    $html .=  '<tr>';
    $html .=  '<td style="vertical-align: center;"><b>#</b></td>';
    $html .=  '<td style="vertical-align: center;"><b>Account</b></td>';
    $html .=  '<td style="vertical-align: center;"><b>Principal Investigator</b></td>';
    $html .=  '<td style="vertical-align: center;"><b>Department</b></td>';
    $html .=  '<td style="vertical-align: center;"><b>Institution</b></td>';
    $html .=  '<td style="vertical-align: center;"><b>Status</b></td>';
    $html .=  '<td style="vertical-align: center;"><b>Ends</b></td>';
    $html .=  '<td style="vertical-align: center;"><b>Edit</b></td>';
    $html .=  '</tr>';

    $i = 1;
    foreach($accountList as $array) {
	// increment the account type
	if($array['status'] == 'active') {
	    $active_acct++;
	}
	else if($array['status'] == 'trial') {
	    $trial_acct++;
	}
	else if($array['status'] == 'suspended') {
	    $susp_acct++;
	}

	$acct_link = encodeUrl(base_url().'admin/accounts/view/'.$array['account_id']);
	$edit_link = encodeUrl(base_url().'admin/accounts/edit/'.$array['account_id']);
	$delete_link = encodeUrl(base_url().'admin/accounts/delete?account_id='.$array['account_id']);

	$pi_name = $array['pi_fname'].' '.$array['pi_mi'].' '.$array['pi_lname'];

	$html .= '<tr>';
	$html .= '<td style="vertical-align: center;">'.$i.'</td>';
	$html .= "<td style=\"vertical-align: center;\"><a href=\"$acct_link\">$array[account_id]</a></td>";
	$html .= '<td style="vertical-align: center;">'.$pi_name.'</td>';
	$html .= '<td style="vertical-align: center;">'.$array['discipline'].'</td>';
	$html .= '<td style="vertical-align: center;">'.$array['institution'].'</td>';
	$html .= '<td style="vertical-align: center;">'.$array['status'].'</td>';
	$html .= '<td style="vertical-align: center;">'.$array['expire_date'].'</td>';
	$html .= "<td style=\"vertical-align: center;\">[ <a href=\"$edit_link\">edit</a> ] 
	[ <a href=\"$delete_link\">delete</a> ] </td>";
	$html .= '</tr>';

	$i++;
    }
    echo '</tbody></table>';
}
else {
    $html = '<b>No Accounts in Database...</b>';
}

// display the results now
echo "<b> Total Accounts </b>: $total_acct || ";
echo "<b>Active </b>: <span style=\"color: rgb(50, 200, 0);\">$active_acct</span> || ";
echo "<b>Trial </b>: <span style=\"color: rgb(255, 155, 0);\">$trial_acct</span> || ";
echo "<b>Suspended </b>: <span style=\"color: rgb(255, 0, 0);\">$susp_acct</span><br><br>";

$search_link = base_url().'admin/accounts/search';
// the search form
echo "<form action='$search_link' method='POST'>";
echo '<input type="hidden" name="task" value="accounts_find_acct">';

echo '<b>Search by :</b>';
echo '<table style="text-align: left; width: 70%;" border="1" cellpadding="1" cellspacing="0"><tbody>
<tr><td style="width: 25%;">Account ID :</td>
<td><input size="50" name="accountid"></td>
</tr>
<tr><td style="width: 25%;">Group or PI Name :</td>
<td><input size="50" name="gname"></td>
</tr>
<tr>
<td style="text-align: left;">Research Keyword :</td>
<td><input size="50" name="kword"></td>
</tr>
<tr>
<td>Research Profile :</td>
<td><input size="50" name="profile"></td>
</tr>
<tr>
<td>Institution :</td>
<td><input size="50" name="institution"></td></tr>';

echo '<tr><td>Group @ :</td>
<td><select size="1" name="gtype">';
foreach($gtypes as $gtype) {
    echo "<option>$gtype</option>";
}
echo '</td></tr>';

echo '<tr><td>Discipline :</td>
<td><select size="1" name="gtype">';
foreach($disciplines as $discipline) {
    echo "<option>$discipline</option>";
}
echo '</td></tr>';

echo '<tr>
<td>Institution Address :</td>
<td><input size="50" name="address"></td>
</tr>
<tr>
<td>Instruments/Services : </td>
<td><input size="50" name="services"></td>
</tr>
<tr>
<td><br></td>
<td><input id="mysearch" value="Find Accounts" type="submit"> </td>
</tr></tbody></table>';

if(!empty($no_acct)) {
    echo 'No Such Account : <span style="color: rgb(255, 0, 0);">'.$no_acct.'</span>';
}
echo '</form>';

// print out any messages
if(!empty($mtype) && $mtype == 'remove') {
    echo '<span style="color: rgb(255, 0, 0);"<b> Message : </b></span>
    Account "'.$message.'" removed from database...';
}

// print out the table containing the accounts
echo $html;
