<?php
    $target_link = base_url()."admin/messages/edit/$message_id";
?>
<div style="text-align: right; margin:0px 15px"><a href='<?=$back_link?>'><img src='<?=base_url()?>images/icons/back.png' class='icon' title='back'/></a></div>

<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
		Edit Message #<?=$message_id?>
	    </td>
	</tr>
	</tbody>
    </table>
    <form action="<?=$target_link?>" method="POST" class="form-inline">
	<input type="hidden" name="edit_message_form" value="posted">      
	<table class="formTable">
	    <tr>
		<td><label for="notes" class="control-label">Accounts :</label></td>
		<td><input type="text" name="accounts" value="<?=$messageItem['account_ids']?>" class="input-block-level"></td>
	    </tr>
	    <tr>
		<td><label for="notes" class="control-label">Post Date :</label></td>
		<td>
		    Post From <input type="text" name="post_start" value="<?=$messageItem['post_start']?>"> 
		    to <input type="text" name="post_end" value="<?=$messageItem['post_end']?>">
		</td>
	    </tr>
	    <tr>
		<td><label for="notes" class="control-label">Website URL :</label></td>
		<td><input type="text" name="url" value="<?=$messageItem['url']?>" class="input-block-level"></td>
	    </tr>
	    <tr>
		<td><label for="notes" class="control-label">Message :</label></td>
		<td><textarea name="message" class="input-block-level"><?=$messageItem['message']?></textarea></td>
	    </tr>
	</table>
	<button type="submit" class="btn btn-primary btn-small">Post Edited Message</button>
    </form>
</div>
