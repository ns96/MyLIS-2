<?php

// initialize some links and variables
$title = "Edit MyLIS Account";
$remove_link = encodeUrl(base_url().'admin/accounts/remove/'.$account_id);
$back_link = encodeUrl(base_url().'admin/accounts/view/'.$account_id);
$edit_link = base_url().'admin/accounts/edit/'.$account_id;
$renew_link = base_url().'admin/accounts/renew';

?>
<div style="text-align:right; margin:5px 15px"><a href="<?=$back_link?>"><img src='<?=base_url()?>images/icons/back.png' class='icon' title='back'/></a></div>

<form action='<?=$renew_link?>' method='POST'>
    <input type="hidden" name="task" value="accounts_renew_acct">
    <input type="hidden" name="account_id" value="<?=$account_id?>">
    <table style="width:100%">
	<tr>
	    <td style="text-align:left">
	    Renew Account for 
	    <select name="term" style="margin-bottom:0px" class="input-small">
		<option value="1">1 Year</option>
		<option value="2">2 Years</option>
		<option value="3">3 Years</option>
		<option value="4">4 Years</option>
	    </select> 
	    <button type="submit" class="btn btn-primary btn-small">Renew</button>
	    </td>
	    <td style="text-align:right"><a href='<?=$remove_link?>' class="btn btn-danger">Remove Account</a></td>
	</tr>
    </table>
</form>


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
    <form action="<?=$edit_link?>" method="POST" class="form-inline">
	<input type="hidden" name="account_edit_form" value="posted">
	<input type="hidden" name="acct_manager" value="<?=$accountInfo['manager_id']?>">
	<input type="hidden" name="account_id" value="<?=$account_id?>">     
	<table class="formTable top-align-cells">
	    <tr>
		<td><label for="manager_id" class="control-label">Account Manager :</label></td>
		<td>
		    <select name="manager_id">
		    <option value='<?=$managerInfo->userid?>' selected><?=$managerInfo->name?></option>
		    <? foreach($userList as $user) {
			echo  "<option value='$user->userid'>$user->name</option>";
		    } ?>
		    </select>
		</td>
		<td colspan="2"><span style="color:grey">leave blank to set automatically</span></td>
	    </tr>
	    <tr>
		<td><label for="fname" class="control-label">Principal Investigator :</label></td>
		<td><input type="text" name="fname" placeholder="First name" class="input-block-level" value="<?=$accountInfo['pi_fname']?>"></td>
		<td><input type="text" name="mi" placeholder="Middle name" class="input-block-level" value="<?=$accountInfo['pi_mi']?>"></td>
		<td><input type="text" name="lname" placeholder="Last name" class="input-block-level" value="<?=$accountInfo['pi_lname']?>"></td>
	    </tr>
	    <tr>
		<td><label for="group_name" class="control-label">Group Name :</label></td>
		<td><input type="text" name="group_name" class="input-block-level" value="<?=$accountInfo['group_name']?>"></td>
		<td><label for="institution_name" class="control-label">Institution Name :</label></td>
		<td><input type="text" name="institution_name" class="input-block-level" value="<?=$accountInfo['institution']?>"></td>
	    </tr>
	    <tr>
		<td><label for="group_type" class="control-label">Group @ :</label></td>
		<td>
		    <select name="group_type" class="input-block-level">
			<option><?=$accountInfo['group_type']?></option>
			<? foreach($gtypes as $gtype) {
			    echo "<option>$gtype</option>";
			} ?>
		    </select>
		</td>
		<td><label for="discipline" class="control-label">Discipline :</label></td>
		<td>
		    <select name="discipline" class="input-block-level" class="input-block-level">
			<option><?=$accountInfo['discipline']?></option>
			<? foreach($disciplines as $discipline) {
			    echo "<option>$discipline</option>";
			} ?>
		    </select>
		</td>
	    </tr>
	    <tr>
		<td><label for="phone" class="control-label">Phone Number :</label></td>
		<td><input type="text" name="phone" class="input-block-level" value="<?=$accountInfo['phone']?>"></td>
		<td><label for="fax" class="control-label">Fax Number :</label></td>
		<td><input type="text" name="fax" class="input-block-level" value="<?=$accountInfo['fax']?>"></td>
	    </tr>
	    <tr>
		<td><label for="email" class="control-label">E-mail :</label></td>
		<td><input type="text" name="email" class="input-block-level" value="<?=$accountInfo['email']?>"></td>
		<td><label for="address" class="control-label">Address :</label></td>
		<td><textarea name="address" class="input-block-level"><?=$accountInfo['address']?></textarea></td>
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
	    <td><label for="term" class="control-label">Term (years) :</label></td>
	    <td><label for="cost" class="control-label">Cost per Year ($0.00) :</label></td>
	    <td><label for="activate_date" class="control-label">Activated (mm/dd/yyyy) :</label></td>
	    <td><label for="expire_date" class="control-label">Expires (mm/dd/yyyy) :</label></td>
	    <td><label for="activate_code" class="control-label">Activation Code :</label></td>
	</tr>
	<tr>
	    <td><?=$accountInfo['term']?></td>
	    <td><input type="text" name="cost" value="<?=$accountInfo['cost']?>"></td>
	    <td><input type="text" name="activate_date" value="<?=$accountInfo['activate_date']?>"></td>
	    <td><input type="text" name="expire_date" value="<?=$accountInfo['expire_date']?>"></td>
	    <td><input type="text" name="activate_code" value="<?=$accountInfo['activate_code']?>"></td>
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
		<select name="status">
		    <option value="<?=$accountInfo['status']?>"><?=ucfirst($accountInfo['status'])?></option>
		    <option value="trial">Trial</option>
		    <option value="active">Active</option>
		    <option value="premium">Premium</option>
		    <option value="suspended">Suspended</option>
		</select>
	    </td>
	    <td>
		<select name="storage">
		    <option value="<?=$accountInfo['storage']?>"><?=$accountInfo['storage']?> MB</option>
		    <option value="10">10 MB (+ $0.00)</option>
		    <option value="200">200 MB (+ $<?=$properties['storage.cost.200MB']?>)</option>
		    <option value="500">500 MB (+ $<?=$properties['storage.cost.500MB']?>)</option>
		    <option value="1000">1000 MB (+ $<?=$properties['storage.cost.1000MB']?>)</option>
		</select>
	    </td>
	    <td><input type="text" name="max_users" value="<?=$accountInfo['max_users']?>"></td>
	    <td>
		<select name="time_zone">
		    <option value="<?=$accountInfo['time_zone']?>"><?=$accountInfo['time_zone']?></option>
		    <? foreach($lis_tz as $key => $value) {
			echo '<option value="'.$key.'">'.$key.'</option>';
		    } ?>
		</select>
	    </td>
	</tr>
	<tr>
	    <td colspan="2">
		<label for="notes" class="control-label">Notes :</label>
		<textarea name="notes" class="input-block-level"><?=$accountInfo['notes']?></textarea>
	    </td>
	    <td colspan="2">
		<label for="new_note" class="control-label">New Note :</label>
		<textarea name="new_note" class="input-block-level"></textarea>
	    </td>
	</tr>
    </table>
</div>

<big><b>Research Profile</b></big> ( Publicly Viewable : 
<select name="public" class="input-small">
    <option value="<?=$accountProfile['public']?>"><?=$accountProfile['public']?></option>
    <option value="YES">YES</option>
    <option value="NO">NO</option>
</select> )<br>

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
	    <td width="80%"><input type="text" name="keywords" class="input-block-level" value="<?=$accountProfile['keywords']?>"></td>
	</tr>
	<tr>
	    <td><label for="description" class="control-label">Research Description :</label></td>
	    <td width="80%"><textarea name="description" class="input-block-level"><?=$accountProfile['description']?></textarea></td>
	</tr>
	<tr>
	    <td><label for="instruments" class="control-label">Group Instruments :</label></td>
	    <td width="80%"><textarea name="instruments" class="input-block-level"><?=$accountProfile['instruments']?></textarea></td>
	</tr>
	<tr>
	    <td><label for="piurl" class="control-label">Webpage :</label></td>
	    <td width="80%"><input type="text" name="piurl" class="input-block-level" value="<?=$accountProfile['url']?>"></td>
	</tr>
	<tr>
	    <td><label for="collaborators" class="control-label">Collaborator IDs :</label></td>
	    <td width="80%"><textarea name="collaborators" class="input-block-level"><?=$accountProfile['collaborator_ids']?></textarea></td>
	</tr>
    </table>
</div>
<button type="submit" class="btn btn-primary btn-small">Update Account</button>
</form>


