<?php
// initialize some links
$page_link = encodeUrl(base_url().'admin/managefiles');
$target_link = base_url().'admin/managefiles/remove_logs';

echo "<div style='text-align:right; margin:0px 15px;'> <a href='$page_link\'>Back</a></div>";


if(count($logs) > 0) {
    ?>
	<div class="formWrapper">
	    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
		<tbody>
		<tr>
		    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
			Log entries
		    </td>
		</tr>
		</tbody>
	    </table>
	    <form action="<?=$target_link?>" method="POST" class="form-inline">
		<input type="hidden" name="task" value="filemanager_remove">      
		<table class="formTable">
		    <thead>
			<th>Entry #</th>
			<th>Log Date/Time</th>
			<th>Accounts</th>
			<th>Files</th>
			<th>Notes</th>
			<th>Manager</th>
		    </thead>
		    <tbody>
			<?
			foreach($logs as $array) {
			    echo '<tr>';
			    echo '<td>';
			    echo '<input type="checkbox" name="entries[]" value="'.$array['update_id'].'">'.$array['update_id'].'</td>';
			    echo '<td>'.$array['update_date'].'</td>';
			    echo '<td>'.$array['update_acct'].'</td>';
			    echo '<td>'.$array['update_mod'].'</td>';
			    echo '<td>'.$array['notes'].'</td>';
			    echo '<td>'.$array['manager']->name.'</td>';
			    echo '</tr>';
			}
			?>
			<tr>
			    <td colspan="6">
				<input type="checkbox" name="all" checked="checked"> Remove All Entries
			    </td>
			</tr>
		    </tbody>
		</table>
		<button type="submit" class="btn btn-danger btn-small">Remove Entries</button>
	    </form>
	</div>
    <?
}
else {
    echo "No log entries...";
}
