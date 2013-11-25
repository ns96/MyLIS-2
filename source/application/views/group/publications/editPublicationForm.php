<?php

// initialize some links
$view_link = base_url()."group/publications/show/".$pub['publication_id']; 
$publication_link = base_url()."group/publications"; 
$delete_link = base_url()."group/publications/delete/".$pub['publication_id'];

// get information about the publication
$author = $pub['userid'];
$title = htmlentities($pub['title']);
$authors = htmlentities($pub['title']);
$abstract = htmlentities($pub['abstract']);
$comments = htmlentities($pub['comments']);

echo '<table style="text-align: left; width: 100%;" border="0"
cellpadding="2" cellspacing="2"><tr><td>
<b><font color="#3366FF"> Update Publication # </font><font color="#cc0000">'.$pub['publication_id'].'</font></b></td>
<td style="text-align: right;">
[ <a href="'.$view_link.'">View</a> ] 
[ <a href="'.$delete_link.'">Delete Publication</a> ]
[ <a href="'.$publication_link.'">Publication List</a> ] 
</td></tr></tbody></table>';


echo "<form method=\"POST\" action ='".base_url()."group/publications/edit/".$pub['publication_id']."'>";
echo '<input type="HIDDEN" name="publication_edit_form" value="posted">';

echo printColoredLine('#3366FF', '2px').'<pre></pre>';

// add the title table
echo '<table cellpadding="2" cellspacing="2" border="0" width="100%"><tbody>';
echo '<tr>';
echo '<td valign="top" width="15%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Title</b></small></font><br></td>';
echo '<td valign="top" width="85%" bgcolor="#F0F0F0">';
echo '<input name="title" type="text" size="80" value="'.$title.'">';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td valign="top" width="15%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Author(s)</b></small></font><br></td>';
echo '<td valign="top" width="85%" bgcolor="#F0F0F0">';
echo '<input name="authors" type="text" size="80" value="'.$pub['authors'].'">';
echo '</td>';
echo '</tr>';
echo '</tbody></table>';
echo '<br>';

// add the type, status, dates here
echo '<table cellpadding="2" cellspacing="2" border="0" width="100%"><tbody>';
echo '<tr>';
echo '<td valign="top" width="15%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Type</b></small></font><br></td>';
echo '<td valign="top" width="25%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Status</b></small></font><br></td>';
echo '<td valign="top" width="20%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Start Date</b></small></font><br></td>';
echo '<td valign="top" width="20%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Last Update</b></small></font><br></td>';
echo '<td valign="top" width="20%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Deadline</b></small></font><br></td>';
echo '</tr>';
echo '<tr>';
echo '<td valign="top" width="15%" bgcolor="#F0F0F0">';
echo '<select name="type" size="1">';
echo '<option value="'.$pub['type'].'">'.ucwords($pub['type']).'</option>';
echo '<option value="paper">Paper</option>';
echo '<option value="review">Review</option>';
echo '<option value="research proposal">Research Proposal</option>';
echo '<option value="dissertation">Dissertation</option>';
echo '<option value="patent">Patent</option>';
echo '</select>';
echo '</td>';
echo '<td valign="top" width="25%" bgcolor="#F0F0F0">';
echo '<select name="status" size="1">';
echo '<option value="'.$pub['status'].'">'.$pub['status'].'</option>';
echo '<option value="Proposed">Proposed</option>';
echo '<option value="In Progress">In Progress</option>';
echo '<option value="Submitted">Submitted</option>';
echo '<option value="Accepted">Accepted</option>';
echo '<option value="In Press">In Press</option>';
echo '<option value="Withdrawn">Withdrawn</option>';
echo '</select>';
echo '</td>';
echo '<td valign="top" width="20%" bgcolor="#F0F0F0">';
echo '<input name="start_date" type="text" size="15" value="'.$pub['start_date'].'">';
echo '</td>';
echo '<td valign="top" width="20%" bgcolor="#F0F0F0">';
echo '<input name="modify_date" type="text" size="15" value="'.$pub['modify_date'].'">';
echo '</td>';
echo '<td valign="top" width="20%" bgcolor="#F0F0F0">';
echo '<input name="end_date" type="text" size="15" value="'.$pub['end_date'].'">';
echo '</td>';
echo '</tr>';
echo '</tbody></table>';

// add the abstract table here
echo '<br>';
echo '<table cellpadding="2" cellspacing="2" border="0" width="100%"><tbody>';
echo '<tr>';
echo '<td valign="top" width="15%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Abstract</b></small></font><br></td>';
echo '<td valign="top" width="85%" bgcolor="#F0F0F0">';
echo '<textarea name="abstract" rows="5" cols="80" >'.$abstract.'</textarea>';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td valign="top" width="15%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Comments</b></small></font><br></td>';
echo '<td valign="top" width="85%" bgcolor="#F0F0F0">';
echo '<textarea name="comments" rows="5" cols="80">'.$comments.'</textarea>';
echo '</td>';
echo '</tr>';
echo '</tbody></table>';
echo '<br>';

// table holding submit button
echo "<div style=\"text-align: right;\">";
echo '<input type="submit" value="Update Publication" 
style="background: rgb(238, 238, 238); color: #3366FF">';
echo '</div>';
echo '</form>';

echo printColoredLine('#3366FF', '1px').'<pre></pre>';

// add the tables that allow removal and addition of files
echo '<font color="#3366FF"><b>File Management</b></font>';

echo '<table cellpadding="2" cellspacing="2" border="0" width="100%"><tbody>';
echo '<tr>';
echo '<td valign="top" width="5%" bgcolor="#b5cbe7"><font color="#212063"><small><b>File</b></small></font><br></td>';
echo '<td valign="top" width="80%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Description</b></small></font><br></td>';
echo '<td valign="top" width="15%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Type/Delete</b></small></font><br></td>';
echo '</tr>';

if(count($fileData)>0) {
    $i = 1;
    foreach ($fileData as $fileItem) {
	$delete_link = base_url()."group/publications/delete/".$fileItem['id'];
	echo '<tr>';
	echo '<td valign="top" width="5%" bgcolor="#F0F0F0"><font color="#000000"><small>'.$i.'</small></font></td>';
	echo '<td valign="top" width="80%" bgcolor="#F0F0F0"><font color="#000000"><small>'.$fileItem['info']['description'].'</small></font></td>';
	echo '<td valign="top" width="15%" bgcolor="#F0F0F0"><font color="#000000"><small>[ <a href="'.$fileItem['link'].'">'.$fileItem['info']['type'].'</a> ]</small></font>'; 
	echo '<form method="post" action="'.base_url().'group/publications/deleteFile">
	    <input type="hidden" name="publication_id" value="'.$pub['publication_id'].'" >
	    <input type="hidden" name="file_id" value="'.$fileItem['id'].'" >
	    <input type="submit" value="Delete" ></form>';
	echo '</td></tr>';
	$i++;
    }
} else {
    echo '<tr>';
    echo '<td valign="top" width="5%" bgcolor="#F0F0F0"><font color="#000000"><small>0</small></font></td>';
    echo '<td valign="top" width="85%" bgcolor="#F0F0F0"><font color="#000000"><small>No Files Found</small></font></td>';
    echo '<td valign="top" width="10%" bgcolor="#F0F0F0"><br></td>';
    echo '</tr>';
}
echo '</tbody></table>';

// add the table to add a new file
echo '<br>';
echo '<table cellpadding="2" cellspacing="2" border="0" width="100%"><tbody>';
echo '<tr>';
echo '<td valign="top" width="25%" bgcolor="#b5cbe7" colspan="2" rowspan="1"><font color="#212063"><small><b>Add New File</b></small></font><br></td>';
echo '</tr>';
echo '<tr>';
echo '<td valign="top" width="25%" bgcolor="#F0F0F0"><font color="#000000"><small>File Type : </small></font>';
echo '<form method="POST" action ="'.base_url().'group/publications/addFile" enctype="multipart/form-data">';
echo '<input type="HIDDEN" name="add_publication_file_form" value="posted">';
echo '<input type="HIDDEN" name="publication_id" value="'.$pub['publication_id'].'">';
echo '<select name="filetype_1" size="1">
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
</select>';
echo '</td>';
echo '<td valign="top" width="75%" bgcolor="#F0F0F0">';
echo '<small>File Name :</small>';
echo '<input name="fileupload_1" size="30" type="file">';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td valign="top" width="25%" bgcolor="#F0F0F0"><font color="#000000"><small>Website Link :</small></font>';
echo '</td>';
echo '<td valign="top" width="75%" bgcolor="#F0F0F0">';
echo '<input name="url_1" type="text" size="50">';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td valign="top" width="25%" bgcolor="#F0F0F0"><font color="#000000"><small>File Description :</small></font>';
echo '</td>';
echo '<td valign="top" width="75%" bgcolor="#F0F0F0">';
echo '<input name="description_1" type="text" size="50">';
echo '<input name="Submit" value="Upload File" type="submit"
style="background: rgb(238, 238, 238); color:#3366FF"> <small>(2MB Max)</small>';
echo '</td>';
echo '</tr>';
echo '</tbody></table>';
echo '</form>';
echo '</body></html>';