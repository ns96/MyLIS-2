<?php

$target_link = base_url().'group/grouptask/update_notes';
// display table that allow editing the notes of the text
?>
<div style="background-color:#dff0d8; border:1px solid grey; padding: 0px; margin:10px">
<form action="<?=$target_link?>" method="POST" class="form-inline" style="margin:0px">
    <input type="hidden" name="task" value="grouptask_update_notes">
    <input type="hidden" name="grouptask_id" value="<?=$grouptask_id?>">      
    <table class="formTable">
	<tr>
	    <td width="70%">
		<textarea name="notes" class="input-block-level">
		<?
		if(empty($grouptask_info['notes'])) {
		    echo 'Enter task notes here ...';
		} else {
		    echo $grouptask_info['notes'];
		}
		?>
		</textarea>
	    </td>
	    <td align="center" valign="middle">
		<button class="btn btn-primary">Update Task Notes</button>
	    </td>
	</tr>
    </table>
</form>
</div>

