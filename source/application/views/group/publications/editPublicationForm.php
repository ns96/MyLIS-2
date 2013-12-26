<?php

// initialize some links
$view_link = base_url()."group/publications/show/".$pub['publication_id']; 
$publication_link = base_url()."group/publications"; 
$delete_link = base_url()."group/publications/delete/".$pub['publication_id'];

// get information about the publication
$author = $pub['userid'];
$title = htmlentities($pub['title']);
$authors = htmlentities($pub['authors']);
$abstract = htmlentities($pub['abstract']);
$comments = htmlentities($pub['comments']);
?>

<div style="text-align:right; margin:15px;">
    <a href="<?=$publication_link?>">Back to Publication List</a>
</div>

<div class="formWrapper">
    
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
		Edit Publication Information
	    </td>
	</tr>
	</tbody>
    </table>
    
    <form method="POST" action ="<?=base_url()?>group/publications/edit/<?=$pub['publication_id']?>">
    <input type="hidden" name="publication_edit_form" value="posted">
    <table id="pub_info_table">
	<tr>
	    <td class="pub_info_label">Title :</td>
	    <td colspan="4">
		<input type="text" name="title" class="input-block-level" value="<?=$title?>">
	    </td>
	</tr>
	<tr>
	    <td class="pub_info_label">Author(s) :</td>
	    <td colspan="4">
		<input type="text" name="authors" class="input-block-level" value="<?=$authors?>">
	    </td>
	</tr>
	<tr>
	    <td colspan="5">&nbsp;</td>
	</tr>
	<tr>
	    <td class="pub_info_label">Type :</td>
	    <td class="pub_info_label">Status :</td>
	    <td class="pub_info_label">Start Date :</td>
	    <td class="pub_info_label">Last Update :</td>
	    <td class="pub_info_label">Deadline :</td>
	</tr>
	<tr>
	    <td>
		<select name="type" class="input-block-level">
		    <option value="<?=$pub['type']?>"><?=ucwords($pub['type'])?></option>
		    <option value="paper">Paper</option>
		    <option value="review">Review</option>
		    <option value="research proposal">Research Proposal</option>
		    <option value="dissertation">Dissertation</option>
		    <option value="patent">Patent</option>
		</select>
	    </td>
	    <td>
		<select name="status" class="input-block-level">
		    <option value="<?=$pub['status']?>"><?=$pub['status']?></option>
		    <option value="Proposed">Proposed</option>
		    <option value="In Progress">In Progress</option>
		    <option value="Submitted">Submitted</option>
		    <option value="Accepted">Accepted</option>
		    <option value="In Press">In Press</option>
		    <option value="Withdrawn">Withdrawn</option>
		</select>
	    </td>
	    <td><input type="text" name="start_date" class="input-block-level" value="<?=$pub['start_date']?>"></td>
	    <td><input type="text" name="modify_date" class="input-block-level" value="<?=$pub['modify_date']?>"></td>
	    <td><input type="text" name="end_date" class="input-block-level" value="<?=$pub['end_date']?>"></td>
	</tr>
	<tr>
	    <td colspan="5">&nbsp;</td>
	</tr>
	<tr>
	    <td class="pub_info_label">Abstract :</td>
	    <td colspan="4"><textarea name="abstract" class="input-block-level"><?=$abstract?></textarea></td>
	</tr>
	<tr>
	    <td class="pub_info_label">Comments :</td>
	    <td colspan="4"><textarea name="comments" class="input-block-level"><?=$comments?></textarea></td>
	</tr>
	<tr>
	    <td colspan="5" style="text-align: center">
		<button type="submit" class="btn btn-primary">Update Publication</button>
		<a href="<?=$delete_link?>" class="btn btn-danger">Delete Publication</a>
	    </td>
	</tr>
    </table>
    </form>    
</div>

<div style="text-align: center; font-weight: bold;">Publication Files</div>
<div style="margin:0px 15px 10px 15px">
<table id="pub_info_filetable">
    <thead>
	<th width="5%">File</th>
	<th width="72%">Description</th>
	<th style="text-align: center">Actions</th>
    </thead>
    <tbody>
	<?
	if(count($fileData)>0) {
	    $i = 1;
	    foreach ($fileData as $fileItem) {
		$delete_link = base_url()."group/publications/delete/".$fileItem['id'];
		echo '<tr>';
		echo '<td>'.$i.'</td>';
		echo '<td>'.$fileItem['info']['description'].'</td>';
		echo '<td style="text-align:center"><a href="'.$fileItem['link'].'">Get '.strtoupper($fileItem['info']['type']).'</a> ';
		echo '<form method="post" action="'.base_url().'group/publications/deleteFile" style="display:inline">
		    <input type="hidden" name="publication_id" value="'.$pub['publication_id'].'" >
		    <input type="hidden" name="file_id" value="'.$fileItem['id'].'" >
		    <input type="submit" value="Delete" class="btn btn-danger btn-small" ></form>';
		echo '</td></tr>';
		$i++;
	    }
	} else {
	    echo '<tr>';
	    echo '<td>0</td>';
	    echo '<td>No Files Found</td>';
	    echo '<td><br></td>';
	    echo '</tr>';
	}
	?>
    </tbody>
</table> 
</div>
    
<?
    $add_link = base_url().'group/publications/addFile';
?>
<div class="formWrapper">
    
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
		Add New File
	    </td>
	</tr>
	</tbody>
    </table>
    
    <form action="<?=$add_link?>" method="POST" enctype="multipart/form-data" class="form-inline" >
	<input type="hidden" name="add_publication_file_form" value="posted">
	<input type="hidden" name="publication_id" value="<?=$pub['publication_id']?>">      
	<table class="formTable">
	    <tr>
		<td><label for="website_link" class="control-label">Select File:</label></td>
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
		    <div align="left" style="display:inline">
			<input id="fileupload_1" name="fileupload_1" class="filestyle" type="file" data-icon="false" style="position: fixed; left: -500px;">  
			<span style="color:grey; margin-left: 5px; margin-right: 10px">(maximum filesize: 2MB)</span>
		    </div>
		</td>
	    </tr>
	    <tr>
		<td><label for="url_1" class="control-label">Website Link:</label></td>
		<td>
		    <input type="text" name="url_1" class="input-block-level">
		</td>
	    </tr>
	    <tr>
		<td><label for="description_1" class="control-label">File description:</label></td>
		<td>
		    <input type="text" name="description_1" class="input-block-level">
		</td>
	    </tr>
	</table>
	<button type="submit" class="btn btn-primary btn-small">Upload File</button>
    </form>
    
</div>