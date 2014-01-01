<?php


// add the table that allows adding of a new user
$cell_color1 = 'rgb(180,200,230)'; // a light blue
$cell_color2 = 'rgb(240,240,240)'; // a light gray

$target_link = base_url().'admin/emails/import';
?>

<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
		Import E-mail List To Be Formated
	    </td>
	</tr>
	</tbody>
    </table>
    <form action="<?=$target_link?>" method="POST" enctype="multipart/form-data" class="form-inline">
	<input type="hidden" name="email_import_form" value="posted">     
	<table class="formTable">
	    <tr>
		<td>
		    <input type="radio" name="action" value="overwrite"> Overwrite Existing Database Entries
		    <input type="radio" name="action" value="append" checked style="margin-left:10px"> Add to Existing Database Entries
		</td>
	    </tr>
	    <tr>
		<td>
		    <label for="fileupload" class="control-label">Tab Delimited Text File:</label>
			    <input id="fileupload" name="fileupload" class="filestyle" type="file" data-icon="false" style="position: fixed; left: -500px;">  
		    </label>
		    <button type="submit" class="btn btn-primary btn-small" style="margin-left:10px">Import</button>
		</td>
	    </tr>
	</table>
    </form>
</div>



