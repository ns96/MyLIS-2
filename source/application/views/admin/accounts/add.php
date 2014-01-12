<?php

$home_link = base_url().'admin/main';
$target_link = base_url().'admin/accounts/create';
?>

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
    <form action="<?=$target_link?>" method="POST" class="form-inline">
	<input type="hidden" name="add_account_form" value="posted">     
	<table class="formTable top-align-cells">
	    <tr>
		<td><label for="account_id" class="control-label">Account ID :</label></td>
		<td><input type="text" name="account_id" class="input-block-level"></td>
		<td colspan="2"><span style="color:grey">leave blank to set automatically</span></td>
	    </tr>
	    <tr>
		<td><label for="fname" class="control-label">Principal Investigator :</label></td>
		<td><input type="text" name="fname" placeholder="First name" class="input-block-level"></td>
		<td><input type="text" name="mi" placeholder="Middle name" class="input-block-level"></td>
		<td><input type="text" name="lname" placeholder="Last name" class="input-block-level"></td>
	    </tr>
	    <tr>
		<td><label for="group_name" class="control-label">Group Name :</label></td>
		<td><input type="text" name="group_name" class="input-block-level"></td>
		<td><label for="institution_name" class="control-label">Institution Name :</label></td>
		<td><input type="text" name="institution_name" class="input-block-level"></td>
	    </tr>
	    <tr>
		<td><label for="group_type" class="control-label">Group @ :</label></td>
		<td>
		    <select name="group_type" class="input-block-level">
			<? foreach($gtypes as $gtype) {
			    echo "<option>$gtype</option>";
			} ?>
		    </select>
		</td>
		<td><label for="discipline" class="control-label">Discipline :</label></td>
		<td>
		    <select name="discipline" class="input-block-level" class="input-block-level">
			<? foreach($disciplines as $discipline) {
			    echo "<option>$discipline</option>";
			} ?>
		    </select>
		</td>
	    </tr>
	    <tr>
		<td><label for="phone" class="control-label">Phone Number :</label></td>
		<td><input type="text" name="phone" class="input-block-level"></td>
		<td><label for="fax" class="control-label">Fax Number :</label></td>
		<td><input type="text" name="fax" class="input-block-level"></td>
	    </tr>
	    <tr>
		<td><label for="email" class="control-label">E-mail :</label></td>
		<td><input type="text" name="email" class="input-block-level"></td>
		<td><label for="address" class="control-label">Address :</label></td>
		<td><textarea name="address" class="input-block-level"></textarea></td>
	    </tr>
	    <tr>
		<td><label for="password1" class="control-label">Password :</label></td>
		<td><input type="text" name="password1" class="input-block-level"></td>
		<td>Re-enter password</td>
		<td><input type="text" name="password2" class="input-block-level"></td>
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
	    <td><label for="email" class="control-label">Expires (mm/dd/yyyy) :</label></td>
	</tr>
	<tr>
	    <td>
		<select name="term">
		    <option value="1">1 Year</option>
		    <option value="2">2 Years</option>
		    <option value="3">3 Years</option>
		    <option value="4">4 Years</option>
		</select>
	    </td>
	    <td><input type="text" name="cost" value="<?=$properties['lis.cost']?>"></td>
	    <td><input type="text" name="activate_date" value="<?=$lisdate?>"></td>
	    <td>Automatically Set</td>
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
		    <option value="trial">Trial</option>
		    <option value="active">Active</option>
		    <option value="premium">Premium</option>
		    <option value="suspended">Suspended</option>
		    <option value="custom">Custom</option>
		</select>
	    </td>
	    <td>
		<select name="storage">
		    <option value="10">10 MB (+ $0.00)</option>
		    <option value="100">200 MB (+ $<?=$properties['storage.cost.200MB']?>)</option>
		    <option value="500">500 MB (+ $<?=$properties['storage.cost.500MB']?>)</option>
		    <option value="1000">1000 MB (+ $<?=$properties['storage.cost.1000MB']?>)</option>
		</select>
	    </td>
	    <td><input type="text" name="max_users"value="<?=$properties['lis.max.users']?>"></td>
	    <td>
		<select name="time_zone">
		    <? foreach($lis_tz as $key => $value) {
			echo '<option value="'.$key.'">'.$key.'</option>';
		    } ?>
		</select>
	    </td>
	</tr>
	<tr>
	    <td><label for="notes" class="control-label">Notes :</label></td>
	    <td colspan="3"><textarea name="notes" class="input-block-level"></textarea></td>
	</tr>
    </table>
</div>

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
	    <td><label for="keywords" class="control-label">Research Keywords :</label></td>
	    <td width="80%"><input type="text" name="keywords" class="input-block-level"></td>
	</tr>
	<tr>
	    <td><label for="description" class="control-label">Research Description :</label></td>
	    <td width="80%"><textarea name="description" class="input-block-level"></textarea></td>
	</tr>
	<tr>
	    <td><label for="piurl" class="control-label">Group or PI webpage :</label></td>
	    <td width="80%"><input type="text" name="piurl" class="input-block-level"></td>
	</tr>
    </table>
</div>
<button type="submit" class="btn btn-primary btn-small">Add Account</button>
</form>


