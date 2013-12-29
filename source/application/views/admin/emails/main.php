<?php

echo '<fieldset style="font-weight: bold; color: #3366FF;">';
echo '<legend>Import E-mail List</legend>';

// add the table that allows adding of a new user
$cell_color1 = 'rgb(180,200,230)'; // a light blue
$cell_color2 = 'rgb(240,240,240)'; // a light gray

$target_link = base_url().'admin/emails/import';
// add table for importing chemical inventory
echo '<form enctype="multipart/form-data" action="'.$target_link.'" method="POST">';
echo '<input type="hidden" name="email_import_form" value="posted">';

echo '<table style="background-color: rgb(255, 255, 255); width: 100%; text-align: left;"
border="0" cellpadding="1" cellspacing="2"><tbody>';

echo '<tr>';
echo '<td colspan="1" rowspan="1" style="vertical-align: top; text-align: left; background-color: '.$cell_color1.';">
<small><b>Import E-mail List To Be Formated</b></td>';
echo '</tr>';

echo '<tr>';
echo '<td colspan="1" rowspan="1" style="vertical-align: top; text-align: left; background-color: '.$cell_color2.';">';
echo '<small><span style="color: #cc0000;"><b>'; 
echo '<input type="radio" name="action" value="overwrite"> Overwrite Existing Database Entries 
<input type="radio" name="action" value="append" checked> Add to Existing Database Entries
</b></span></small>'; 
echo '</td></tr>';

echo '<tr>';
echo '<td colspan="1" rowspan="1" style="vertical-align: top; background-color: '.$cell_color2.';">';
echo '<small><b>Tab Delimited Text File</b> : <input name="fileupload" type="file" size="40" > '; 
echo '<input type="submit" value="Import" 
style="background: rgb(238, 238, 238); color: #3366FF"></td>';
echo '</td></tr>';
echo '</tbody></table></form>';

echo '</fieldset>';
