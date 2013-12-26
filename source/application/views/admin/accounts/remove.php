<?php
$target_link = base_url().'admin/accounts/remove/'.$account_id;
?>
<form action='<?=$target_link?>' method='POST'>
    <input type="hidden" name="account_remove_form" value="posted">

    <div style="text-align: center;">
	Are you sure you want to remove 
	<br>
	<b> <span style="color: rgb(255, 0, 0);"><big> <?=$account_id?></big></span></b>
	<br>
	and all associated files?
	<br><br>
	<input type="text" name="code" value="Removal Code Required">
	<input type="submit" value="Remove">
    </div>
</form>

