<?php
$target_link = base_url().'admin/accounts/remove/'.$account_id;
?>
<form action='<?=$target_link?>' method='POST' class="form-inline">
    <input type="hidden" name="account_remove_form" value="posted">

    <table class="formTable">
	<tr>
	    <td style="text-align: center">
		Are you sure you want to remove 
		<b> <span style="color: rgb(255, 0, 0);"><big> <?=$account_id?></big></span></b>
		and all associated files?
		<br><br>
		<input type="text" name="code" placeholder="Removal Code Required">
		<button type="submit" class="btn btn-danger btn-small">Remove</button>
	    </td>
	</tr>
    </table>
</form>

