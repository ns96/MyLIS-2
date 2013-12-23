<?php

if($type == 'Chemical') {
  $file_link = $home_dir.'dbfiles/chemicals.xls';
  $target_link = base_url().'group/manage/inventory_import_chemicals';
  $bar_color = 'rgb(180,200,230)';
}
else { // must be supplies
  $file_link = $home_dir.'dbfiles/supplies.xls';
  $target_link = base_url().'group/manage/inventory_import_supplies';
  $bar_color = '#A1B2CB';
}
?>

<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td style="background-color: <?=$bar_color?>; width: 25%;">
		Import <?=$type?> Inventory (tab delimited file) 
		[ <a href="<?=$file_link?>" target="_blank">Download Excel Template</a> ]
	    </td>
	</tr>
	</tbody>
    </table>
    <?
    if($type == 'Chemical') {
	echo '<small><i>'.$im_message1.'</i></small>';
    } else {
	echo '<small><i>'.$im_message2.'</i></small>';
    }
    ?>
    <form action="<?=$target_link?>" method="POST" enctype="multipart/form-data" class="form-inline">
	<input type="hidden" name="add_chemical_form" value="posted" >      
	<table class="formTable">
	    <tr>
		<td colspan="2">
		    <input type="radio" name="action" value="overwrite"> Overwrite Existing Database Entries 
		    <input type="radio" name="action" value="append" checked style="margin-left:10px"> Add to Existing Database Entries
		</td>
	    </tr>
	    <tr>
		<td style="padding-top:5px">
		    <label for="fileupload" class="control-label">Tab Delimited Text File :</label>
			    <input id="fileupload" name="fileupload" class="filestyle" type="file" data-icon="false" style="position: fixed; left: -500px;">  
		    </label>
		</td>
		<td style="text-align:right">
		    <button type="submit" class="btn btn-primary btn-small">Import Inventory</button>
		</td>
	    </tr>
	</table>
    </form>
</div>
