<?php

// initialize some links
$log_link = encodeUrl(base_url().'admin/managefiles/view_log');
$update_link = base_url().'admin/managefiles/update';
$remove_link = base_url().'admin/managefiles/remove';

echo "<div style='text-align:right; margin:0px 15px;'> <a href='$log_link'>View Log</a></div>";

// print out any messages
if(isset($message)) {
    echo 'Message : <span style="color: rgb(255, 0, 0);">'.$message.'</span><br>';
}
?>

<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
		Managed Files
	    </td>
	</tr>
	</tbody>
    </table>
    <form action="<?=$update_link?>" method="POST" class="form-inline">
	<input type="hidden" name="task" value="filemanager_update_file">      
	<table class="formTable">
	    <thead>
		<th>File name</th>
		<th>Type</th>
		<th>Description</th>
	    </thead>
	    <tbody>
		<?
		foreach($fileList as $file) {
		    $sa  = explode(';', $file);
		    echo '<tr>';
		    echo '<td>
		    <input type="checkbox" name="files[]" value="'.$sa[0].'"> '.$sa[0].'</td>';
		    echo '<td>'.$sa[1].'</td>';
		    echo '<td>'.$sa[2].'</td>';
		    echo '</tr>';
		}
		?>
		<tr>
		    <td colspan="3">&nbsp;</td>
		</tr>
		<tr>
		    <td colspan="3" >
			<input type="checkbox" name="all" checked="checked" style="border-color:red"> Update All Files 
		    </td>
		</tr>
	    </tbody>
	</table>
	<table class="formTable" style="margin-top:10px">
	    <tr>
		<td><label for="accounts" class="control-label">Accounts :</label></td>
		<td><input type="text" name="accounts" size="75" value="ALL" class="input-block-level"></td>
	    </tr>
	    <tr>
		<td><label for="notes" class="control-label">Notes: :</label></td>
		<td><textarea rows="2" cols="65" name="notes" class="input-block-level"></textarea></td>
	    </tr>
	</table>
	<button type="submit" class="btn btn-primary btn-small">Update Selected Files</button>
    </form>
</div>

<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
		Files in Trash
	    </td>
	</tr>
	</tbody>
    </table>
    <form action="<?=$remove_link?>" method="POST" class="form-inline">
	<input type="hidden" name="task" value="filemanager_remove_file">      
	<table class="formTable">
	    <thead>
		<th>File name</th>
		<th>Type</th>
		<th>Date/Time</th>
		<th>Description</th>
	    </thead>
	    <tbody>
		<?
		foreach($trashList as $file) {
		    $sa  = explode(';', $file);
		    echo '<tr>';
		    echo '<td style="vertical-align: center;">
		    <input type="checkbox" name="files[]" value="'.$sa[0].'">'.$sa[0].'</td>';
		    echo '<td style="vertical-align: center;">'.$sa[1].'</td>';
		    echo '<td style="vertical-align: center;">'.$sa[2].'</td>';
		    echo '<td style="vertical-align: center;">'.$sa[3].'</td>';
		    echo '</tr>';
		}
		?>
		<tr>
		    <td colspan="3">&nbsp;</td>
		</tr>
		<tr>
		    <td colspan="3">
			    <input type="checkbox" name="all" checked="checked"> 
			Select All
		    </td>
		</tr>
	    </tbody>
	</table>
	<button type="submit" class="btn btn-danger btn-small">Remove Selected Files</button>
    </form>
</div>
