<?php
    $post_link = base_url()."group/accounts/user_profile";
    $cancel_link = base_url()."group/main";
?>

<form action="<?=$post_link?>" method="POST" class="form-horizontal" style="margin:20px">
<table style="text-align: center; margin:0 auto; border-spacing: 30px; border-collapse: separate" border="0" cellpadding="2">
    <tr>
	<td>
	    <img src="<?=base_url()."images/icons/user-icon.png" ?>" />
	    <br>User ID: <span style="font-weight: bold; font-size: 16px"><?=$userid?></span>
	</td>
	<td>
	    <input type='hidden' name='profile_update_form' value='posted' />
	    <table style="text-align:right; border-spacing: 8px; border-collapse: separate">
		<tr>
		    <td>Full Name :</td>
		    <td>
			<input type="text" class="input-block-level input-large" name="name" value="<?=htmlentities($name)?>">
		    </td>
		</tr>
		<tr>
		    <td>Password :</td>
		    <td><input type="password" class="input-block-level input-large" name="password" value="<?=$password?>"></td>
		</tr>
		<tr>
		    <td>E-mail :</td>
		    <td><input type="text" class="input-block-level input-large" name="email" value="<?=$email?>"></td>
		</tr>
		<tr>
		    <td>Additonal Info:</td>
		    <td><input type="text" class="input-block-level input-large" name="info" value="<?=$info?>"></td>
		</tr>
	    </table>
	</td>
    </tr>
</table>
<input value="Update" type="submit" class="btn btn-primary btn-medium">
<a href="<?=$cancel_link?>" target="_parent" class="btn btn-danger btn-medium" style="line-height: 16px; margin-left: 10px">Cancel</a>
 </form>