<?php
$cancel_link = base_url()."group/meetings";
$target_link = base_url()."group/meetings/add_file";
?>

<div class="formWrapper">
<form action="<?=$target_link?>" method="POST" enctype="multipart/form-data" class="form-inline">
    <input type="HIDDEN" name="add_slotfile_form" value="posted">
    <input type="HIDDEN" name="slot_id" value="<?=$slot_id?>">
    <input type="HIDDEN" name="file_id" value="<?=$file_id?>">

    <table class="formTable">
	<tbody>
	<tr>
	    <td>
		File Type : 
	    </td>
	    <td>
		<select name="filetype_1" class="input-medium">
		    <option value="none">No File</option>
		    <option value="url">Website URL</option>
		    <option value="pdf">PDF</option>
		    <option value="doc">Word</option>
		    <option value="ppt">Powerpoint</option>
		    <option value="xls">Excel</option>
		    <option value="rtf">RTF</option>
		    <option value="odt">OO Text</option>
		    <option value="odp">OO Impress</option>
		    <option value="ods">OO Calc</option>
		    <option value="zip">Zip</option>
		    <option value="other">Other</option>
		</select>
	    </td>
	</tr>
	<tr>
	    <td>
	    File Name 
	    </td>
	    <td>
		<label for="fileupload_1" class="control-label"></label>
		    <input id="fileupload_1" name="fileupload_1" class="filestyle" type="file" data-icon="false" style="position: fixed; left: -500px;">  
		</label>
		<span style="color:grey; margin-left: 10px">(2MB Max)</span>
	    </td>
	</tr>
	<tr>
	    <td>
		Website Link :
	    </td>
	    <td>
		<input name="url_1" type="text" class="input-block-level">
	    </td>
	</tr>
	<tr>
	    <td>
		File Description :
	    </td>
	    <td>
		<input name="description_1" type="text" class="input-block-level">
	    </td>
	</tr>
	<tr>
	    <td colspan="2" style="text-align: center">
		<a href="<?=$cancel_link?>" class="btn btn-danger">Cancel</a>
		<button type="submit" class="btn btn-primary btn-small" style="margin-left: 15px">Upload File</button>
	    </td>
	</tr>
	</tbody>
    </table>
</form>
</div>
