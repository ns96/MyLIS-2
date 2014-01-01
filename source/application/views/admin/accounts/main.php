<?php

// initialize some links
$add_link = base_url().'admin/accounts/create';

// get the accounts from database
$total_acct = 0;
$active_acct = 0;
$trial_acct = 0;
$susp_acct = 0;

$html = '';

if(count($accountList) >= 1) {
    $total_acct =count($accountList);

    $html .= "<div style='margin:0px 15px'>";
    $html .= '<table class="table table-condensed table-bordered">';
    $html .=  '<thead>';
    $html .=  '<th>#</th>';
    $html .=  '<th>Account</th>';
    $html .=  '<th>Principal Investigator</th>';
    $html .=  '<th>Department</th>';
    $html .=  '<th>Institution</th>';
    $html .=  '<th>Status</th>';
    $html .=  '<th>Ends</th>';
    $html .=  '<th>Actions</th>';
    $html .=  '</thead><tbody>';

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
	$delete_link = encodeUrl(base_url().'admin/accounts/remove/'.$array['account_id']);

	$pi_name = $array['pi_fname'].' '.$array['pi_mi'].' '.$array['pi_lname'];

	$html .= '<tr>';
	$html .= '<td>'.$i.'</td>';
	$html .= "<td><a href=\"$acct_link\">$array[account_id]</a></td>";
	$html .= '<td>'.$pi_name.'</td>';
	$html .= '<td>'.$array['discipline'].'</td>';
	$html .= '<td>'.$array['institution'].'</td>';
	$html .= '<td>'.$array['status'].'</td>';
	$html .= '<td>'.$array['expire_date'].'</td>';
	$html .= "<td style='text-align:center'><a href='$edit_link' class='btn btn-mini btn-success'>edit</a> 
	<a href='$delete_link' class='btn btn-mini btn-danger'>delete</a> </td>";
	$html .= '</tr>';

	$i++;
    }
    $html .= '</tbody></table>';
    $html .= "</div>";
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
?>

<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
		Account Search Form
	    </td>
	</tr>
	</tbody>
    </table>
    <form action="<?=$search_link?>" method="POST" class="form-inline">
	<input type="hidden" name="task" value="accounts_find_acct">     
	<table class="formTable">
	    <tr>
		<td>Account ID :</td>
		<td><input type="text" name="account_id"></td>
		<td>Research Keyword :</td>
		<td><input type="text" name="kword" class="input-large"></td>
	    </tr>
	    <tr>
		<td>Group or PI Name :</td>
		<td><input type="text" name="gname"></td>
		<td>Research Profile :</td>
		<td><input type="text" name="profile" class="input-large"></td>
	    </tr>
	    <tr>
		<td>Institution :</td>
		<td><input type="text" name="institution"></td>
		<td>Group @ :</td>
		<td>
		    <select name="gtype" class="input-large">
		    <? foreach($gtypes as $gtype) {
			echo "<option>$gtype</option>";
		    } ?>
		    </select>
		</td>
	    </tr>
	    <tr>
		<td>Institution Address :</td>
		<td><input type="text" name="address"></td>
		<td>Discipline :</td>
		<td>
		    <select name="gtype" class="input-large">
		    <? foreach($disciplines as $discipline) {
			echo "<option>$discipline</option>";
		    } ?>
		    </select>
		</td>
	    </tr>
	    <tr>
		<td>Instruments/Services :</td>
		<td><input type="text" name="services"></td>
		<td></td>
		<td></td>
	    </tr>
	    <tr>
		<td colspan="4" style="text-align: center">
		    <button type="submit" class="btn btn-primary btn-small">Find Accounts</button>
		</td>
	    </tr>
	</table>
    </form>
</div>

<?


if(!empty($no_acct)) {
    echo 'No Such Account : <span style="color: rgb(255, 0, 0);">'.$no_acct.'</span>';
}

// print out any messages
if(!empty($mtype) && $mtype == 'remove') {
    echo "<div class='alert alert-info'>";
    echo "<b>Message : </b> Account ".$message." removed from database...";
    echo "</div>";
}

// print out the table containing the accounts
echo $html;
