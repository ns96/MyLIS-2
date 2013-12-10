<?php

$target_link = base_url().'group/manage/users_import';
$file_link = $home_dir.'dbfiles/users.xls';

// add the table that allows adding of a new user
$cell_color1 = 'rgb(180,200,230)'; // a light blue
$cell_color2 = 'rgb(240,240,240)'; // a light gray

// print any messages and reset the message
echo '<small><i>'.$im_message.'</i></small>';

// add table for importing a user list
echo '<form enctype="multipart/form-data" action="'.$target_link.'" method="POST">';
echo '<input type="hidden" name="users_import_form" value="posted">';

echo '<table style="background-color: rgb(255, 255, 255); width: 100%; text-align: left;"
border="0" cellpadding="1" cellspacing="2"><tbody>';

echo '<tr>';
echo '<td colspan="1" rowspan="1" style="vertical-align: top; text-align: left; background-color: '.$cell_color1.';">
<small><b>Import User List</b> (tab delimited file) 
[ <a href="'.$file_link.'" target="_blank">Download Excel Template</a> ]</small></td>';
echo '</tr>';

/*echo '<tr>';
echo '<td colspan="1" rowspan="1" style="vertical-align: top; text-align: left; background-color: '.$cell_color2.';">';
echo '<small><span style="color: '.$this->dark_red.';"><b>'; 
echo '<input type="radio" name="action" value="overwrite"> Overwrite Existing Database Entries 
<input type="radio" name="action" value="append" checked> Add to Existing Database Entries
</b></span></small>'; 
echo '</td></tr>';*/

echo '<tr>';
echo '<td colspan="1" rowspan="1" style="vertical-align: top; background-color: '.$cell_color2.';">';
echo '<small><b>Tab Delimited Text File</b> : <input name="fileupload" type="file" size="40" > '; 
echo '<input type="submit" value="Import" 
style="background: rgb(238, 238, 238); color: #3366FF"></td>';
echo '</td></tr>';
echo '</tbody></table></form>';

