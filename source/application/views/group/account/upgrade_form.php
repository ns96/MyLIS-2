<?php

$cancel_link = base_url().'group/main';
$target_link = base_url().'group/accounts/upgrade';

$agreeText = '<small>I understand that the bill has to be paid in full 
    within 30 days (Net 30 days) or the account will revert to the previous file storage
    limit.</small>';

$role = $user->role;

$pi_name = "$info[pi_fname] $info[pi_mi] $info[pi_lname]";

if($role == 'admin') { ?>
<div style="margin:15px">
    <form action="<?=$target_link?>" method="POST" class="form-inline">
	<input type="hidden" name="upgrade_form" value="posted">     
	<table class="formTable">
	    <tr>
		<td><label for="storage" class="control-label">Storage Upgrade :</label></td>
		<td colspan="3">
		    <select name="storage" class="input-xlarge">
			<option value="200">200 MB @ $'.$cost1.'.00 per year</option>
			<option value="1000">1000 MB @ $'.$cost2.'.00 per year</option>
			<option value="5000">5000 MB @ $'.$cost3.'.00 per year</option>
		    </select>
		</td>
	    </tr>
	    <tr>
		<td>
		    <label for="name" class="control-label">Your name :</label>
		</td>
		<td>
		    <input type="text" name="name" value="<?=htmlentities($user->name)?>" class="input-block-level">
		</td>
		<td>
		    <label for="pi_name" class="control-label">PI Name :</label>
		</td>
		<td>
		    <input type="text" name="pi_name" value="<?=htmlentities($pi_name)?>" class="input-block-level">
		</td>
	    </tr>
	    <tr>
		<td>
		    <label for="email" class="control-label">Your e-mail :</label>
		</td>
		<td>
		    <input type="text" name="email" value="<?=$user->email?>" class="input-block-level">
		</td>
		<td>
		    <label for="pi_email" class="control-label">PI Email :</label>
		</td>
		<td>
		    <input type="text" name="pi_email" value="<?=$info['email']?>" class="input-block-level">
		</td>
	    </tr>
	    <tr>
		<td>
		    <label for="phone" class="control-label">Phone :</label>
		</td>
		<td>
		    <input type="text" name="phone" value="<?=$info['phone']?>" class="input-block-level">
		</td>
		<td>
		    <label for="po_number" class="control-label">P.O. Number :</label>
		</td>
		<td>
		    <input type="text" name="po_number" class="input-block-level">
		</td>
	    </tr>
	    <tr>
		<td>
		    <label for="address" class="control-label">Billing Address :</label>
		</td>
		<td colspan="3">
		    <textarea rows="3" name="address" class="input-block-level"><?=$info['address']?></textarea>
		</td>
	    </tr>
	    <tr>
		<td colspan="4" style="padding-top: 5px">
		    <input name="agree" value="yes" type="checkbox"> <?=$agreeText?>
		</td>
	    </tr>
	</table>
	<div style="margin-top:5px">
	    <button type="submit" class="btn btn-primary btn-small">Upgrade Account</button>
	    <a href="<?=$cancel_link?>" target="_parent" class="btn btn-danger">Cancel</a>
	</div>
    </form>
</div>

<?
} else {
   echo "Sorry, only users with admin privileges are allowed to upgrade this account";
}




