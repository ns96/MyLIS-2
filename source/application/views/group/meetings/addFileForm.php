<?php

$cancel_link = base_url()."group/meetings";
$target_link = base_url()."group/meetings/addFile";

echo "<html>";
echo "<head>";
echo "<title>Add Group Meeting File</title>";
echo "</head>";
echo '<body>';

echo '<form method="POST" action ="'.$target_link.'" enctype="multipart/form-data">';
echo '<input type="HIDDEN" name="add_slotfile_form" value="posted">';
echo '<input type="HIDDEN" name="slot_id" value="'.$slot_id.'">';
echo '<input type="HIDDEN" name="file_id" value="'.$file_id.'">';

echo '<table cellpadding="2" cellspacing="0" border="1" 
style="width: 70%; text-align: left; margin-left: auto; margin-right: auto;"><tbody>';
echo '<tr>';
echo '<td valign="top" width="40%" bgcolor="#b5cbe7" 
colspan="2" rowspan="1"><font color="#b5cbe7"><b>'.$title.'</b></font><br></td>';
echo '</tr>';
echo '<tr>';
echo '<td valign="top" width="40%" bgcolor="#f5f6f7"><font color="#000000">File Type : </font>';
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
echo '<td valign="top" width="75%" bgcolor="#f5f6f7">';
echo 'File Name ';
echo '<input type="file" name="fileupload_1" size="20">';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td valign="top" width="25%" bgcolor="#f5f6f7"><font color="#000000">Website Link :</font>';
echo '</td>';
echo '<td valign="top" width="75%" bgcolor="#f5f6f7">';
echo '<input name="url_1" type="text" size="30">';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td valign="top" width="25%" bgcolor="#f5f6f7"><font color="#000000">File Description :</font>';
echo '</td>';
echo '<td valign="top" width="75%" bgcolor="#f5f6f7">';
echo '<input name="description_1" type="text" size="30"><br>';
echo '[ <a href="'.$cancel_link.'">Cancel</a> ] ';
echo '<input name="Submit" value="Upload File" type="submit"
style="background: rgb(238, 238, 238); color: #3366FF"> <small>(2MB Max)</small>';
echo '</td>';
echo '</tr>';
echo '</tbody></table>';
echo '</form></body></html>';
