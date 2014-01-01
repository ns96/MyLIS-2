<?php

// initialize some links and variables
$title = "MyLIS Account";
$db_link = encodeUrl(base_url().'admin/managedb/account/'.$account_id);
$back_link = encodeUrl(base_url().'admin/accounts');
$edit_link = encodeUrl(base_url().'admin/accounts/edit/'.$account_id);

$group_pi = $accountInfo['pi_fname'].' '.$accountInfo['pi_mi'].' '.$accountInfo['pi_lname'];

// get the userid and password from DB
$userids_passwords = '';
foreach($accountUsers as $userInfo) {
    $userids_passwords .=$userInfo['email'].' : '.$userInfo['password'].' ;';
}

$notes = $accountInfo['notes'];
if(empty($notes)) {
    $notes = 'None';
}
?>

<div style='text-align:right'>
    [ <a href='<?=$db_link?>'>DB</a> ] 
    [ <a href='<?=$edit_link?>'>Edit</a> ] 
    [ <a href='<?=$back_link?>'>Back</a> ] 
</div>


<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
		Basic Information
	    </td>
	</tr>
	</tbody>
    </table>
	<input type="hidden" name="add_account_form" value="posted">     
	<table class="formTable top-align-cells">
	    <tr>
		<td><label for="account_id" class="control-label">Account ID :</label></td>
		<td colspan="3"><input type="text" name="account_id" class="input-block-level" disabled value="<?=$managerInfo->name?>"></td>
	    </tr>
	    <tr>
		<td><label for="pi_name" class="control-label">Principal Investigator :</label></td>
		<td colspan="3"><input type="text" name="pi_name" class="input-block-level" disabled value="<?=$group_pi?>"></td>
	    </tr>
	    <tr>
		<td><label for="group_name" class="control-label">Group Name :</label></td>
		<td><input type="text" name="group_name" class="input-block-level" disabled value="<?=$accountInfo['group_name']?>"></td>
		<td><label for="institution_name" class="control-label">Institution Name :</label></td>
		<td><input type="text" name="institution_name" class="input-block-level" disabled value="<?=$accountInfo['institution']?>"></td>
	    </tr>
	    <tr>
		<td><label for="group_type" class="control-label">Group @ :</label></td>
		<td><input type="text" name="group_type" class="input-block-level" disabled value="<?=$accountInfo['group_type']?>"></td>
		<td><label for="discipline" class="control-label">Discipline :</label></td>
		<td><input type="text" name="discipline" class="input-block-level" disabled value="<?=$accountInfo['discipline']?>"></td>
	    </tr>
	    <tr>
		<td><label for="phone" class="control-label">Phone Number :</label></td>
		<td><input type="text" name="phone" class="input-block-level" disabled value="<?=$accountInfo['phone']?>"></td>
		<td><label for="fax" class="control-label">Fax Number :</label></td>
		<td><input type="text" name="fax" class="input-block-level" disabled value="<?=$accountInfo['fax']?>"></td>
	    </tr>
	    <tr>
		<td><label for="email" class="control-label">E-mail :</label></td>
		<td><input type="text" name="email" class="input-block-level" disabled value="<?=$accountInfo['email']?>"></td>
		<td><label for="address" class="control-label">Address :</label></td>
		<td><textarea name="address" class="input-block-level" disabled><?=$accountInfo['address']?></textarea></td>
	    </tr>
	    <tr>
		<td><label for="password" class="control-label">UserID/Passwords :</label></td>
		<td colspan="3"><input type="text" name="password" class="input-block-level" disabled value="<?=$userids_passwords?>"></td>
	    </tr>
	</table>
</div>

<!-- table holding billing info -->
<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
		Billing Information
	    </td>
	</tr>
	</tbody>
    </table>
    <table class="formTable">
	<tr>
	    <td width="15%"><label for="term" class="control-label">Term (years) :</label></td>
	    <td><label for="cost" class="control-label">Cost per Year ($0.00) :</label></td>
	    <td><label for="activate_date" class="control-label">Activated (mm/dd/yyyy) :</label></td>
	    <td><label for="email" class="control-label">Expires (mm/dd/yyyy) :</label></td>
	    <td><label for="activate_code" class="control-label">Activation Code :</label></td>
	</tr>
	<tr>
	    <td>
		<input type="text" name="email" class="input-block-level" disabled value="<?=$accountInfo['term']?>">
	    </td>
	    <td>
		<input type="text" name="cost" disabled value="<?=$accountInfo['cost']?>">
	    </td>
	    <td>
		<input type="text" name="activate_date" disabled value="<?=$accountInfo['activate_date']?>">
	    </td>
	    <td>
		<input type="text" name="email" class="input-block-level" disabled value="<?=$accountInfo['email']?>">
	    </td>
	    <td>
		<input type="text" name="activate_code" class="input-block-level" disabled value="<?=$accountInfo['activate_code']?>">
	    </td>
	</tr>
    </table>
</div>

<!-- table holding some account settings -->
<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
		Account Settings
	    </td>
	</tr>
	</tbody>
    </table>
    <table class="formTable">
	<tr>
	    <td><label for="status" class="control-label">Status :</label></td>
	    <td><label for="storage" class="control-label">Storage :</label></td>
	    <td><label for="max_users" class="control-label">Max Users :</label></td>
	    <td><label for="time_zone" class="control-label">Time Zone :</label></td>
	</tr>
	<tr>
	    <td>
		<input type="text" name="status" class="input-block-level" disabled value="<?=ucfirst($accountInfo['status'])?>">
	    </td>
	    <td>
		<input type="text" name="storage" disabled value="<?=$accountInfo['storage']?>">
	    </td>
	    <td>
		<input type="text" name="max_users" disabled value="<?=$accountInfo['max_users']?>">
	    </td>
	    <td>
		<input type="text" name="time_zone" class="input-block-level" disabled value="<?=$accountInfo['time_zone']?>">
	    </td>
	</tr>
	<tr>
	    <td><label for="notes" class="control-label">Notes :</label></td>
	    <td colspan="3"><textarea name="notes" class="input-block-level" disabled><?=$notes?></textarea></td>
	</tr>
    </table>
</div>

<b>Research Profile</b> ( Publicly Viewable : <?=$accountProfile['public']?> )

<!-- table holding extra info -->
<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
		Extra Information
	    </td>
	</tr>
	</tbody>
    </table>
    <table class="formTable">
	<tr>
	    <td><label for="keywords" class="control-label">Keywords :</label></td>
	    <td width="80%"><input type="text" name="keywords" class="input-block-level" disabled value="<?=$accountProfile['keywords']?>"></td>
	</tr>
	<tr>
	    <td><label for="description" class="control-label">Research Description :</label></td>
	    <td width="80%"><textarea name="description" class="input-block-level" disabled value="<?=$accountProfile['description']?>"></textarea></td>
	</tr>
	<tr>
	    <td><label for="piurl" class="control-label">Instruments :</label></td>
	    <td width="80%"><input type="text" name="piurl" class="input-block-level" disabled value="<?=$accountProfile['instruments']?>"></td>
	</tr>
	<tr>
	    <td><label for="piurl" class="control-label">Website :</label></td>
	    <td width="80%"><a href='<?=$accountProfile['url']?>' target='_blank'><?=$accountProfile['url']?></a></td>
	</tr>
	<tr>
	    <td><label for="piurl" class="control-label">Collaborator IDs :</label></td>
	    <td width="80%"><input type="text" name="piurl" class="input-block-level" disabled value="<?=$accountProfile['collaborator_ids']?>"></td>
	</tr>
    </table>
</div>




