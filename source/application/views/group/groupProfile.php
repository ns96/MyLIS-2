<?php
    $base = base_url()."group/";
    $post_link = $base."accounts/group_profile";
    $cancel_link = $base."group/main";
?>
<div style="font-size:12px">last edited on <span style="color:#285e8e"><?=$info['edit_date']?></span> by <span style="color:#285e8e"><?=$editor->name?></span></div>
<form action="<?=$post_link?>" method="POST" class="form-horizontal" style="margin:10px">
<table style="text-align: center; margin:0 auto; border-spacing: 30px; border-collapse: separate" border="0" cellpadding="2">
    <tr>
	<td>
	    <img src="<?=base_url()."images/icons/group-icon.png" ?>" />
	    <br>Group ID: <span style="font-weight: bold; font-size: 16px"><?=$group?></span>
	</td>
	<td>
	    <input type='hidden' name='group_profile_update_form' value='posted' />
	    <table style="text-align:right; border-spacing: 8px; border-collapse: separate">
		<tr>
		    <td>PI Name :</td>
		    <td>
			<input type="text" class="input-block-level input-xxlarge" name="pi_name" value="<?=htmlentities($info['pi_name'])?>">
		    </td>
		</tr>
		<tr>
		    <td>PI E-mail :</td>
		    <td><input type="text" class="input-block-level input-xxlarge" name="pi_email" value="<?=$info['pi_email']?>"></td>
		</tr>
		<tr>
		    <td>Group Site URL :</td>
		    <td><input type="text" class="input-block-level input-xxlarge" name="url" value="<?=$info['url']?>"></td>
		</tr>
		<tr>
		    <td>Research keywords:</td>
		    <td><input type="text" class="input-block-level input-xxlarge" name="keywords" value="<?=$info['keywords']?>"></td>
		</tr>
		<tr>
		    <td>Research Description:</td>
		    <td><textarea class="input-block-level" name="description"><?=$info['description']?></textarea></td>
		</tr>
		<tr>
		    <td>Instrument List:</td>
		    <td><textarea class="input-block-level" name="instruments"><?=$info['instruments']?></textarea></td>
		</tr>
	    </table>
	</td>
    </tr>
</table>
<input value="Update" type="submit" class="btn btn-primary btn-medium">
<a href="<?=$cancel_link?>" target="_parent" class="btn btn-danger btn-medium" style="line-height: 16px; margin-left: 10px">Cancel</a>
 </form>