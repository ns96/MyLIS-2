<?php

// initialize some links
$home_link = encodeUrl($this->properties['home.url']);
$log_link = encodeUrl($script.'?task=filemanager_displaylog');

echo '<table style="width: 100%; text-align: left;" border="0" cellpadding="2" cellspacing="0">';
echo '<tbody>';
echo '<tr>';
echo '<td style="vertical-align: top;">';
echo '<h3><span style="color: rgb(0, 0, 102);">MyLIS File Manager</span><br></h3>';
echo '</td>';
echo '<td style="vertical-align: top; text-align: right;">';
echo "<b>[ <a href=\"$log_link\">View Log</a> ] ";
echo "[ <a href=\"$home_link\">Home</a> ]</b><br>";
echo '</td></tr></tbody></table>';
echo '<hr style="width: 100%; height: 2px;">';

// print out any messages
    if(isset($_GET['message'])) {
    echo 'Message : <span style="color: rgb(255, 0, 0);">'.$_GET['message'].'</span><br>';
}

// create the table holding the filenames
echo "<form action=\"$script\" method=\"POST\">";
echo '<input type="hidden" name="task" value="filemanager_update_file">';

// create tables holding directories in the trash directory
echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
echo '<tbody>';

echo '<tr>';
echo '<td style="vertical-align: center;"><b>Files</b></td>';
echo '<td style="vertical-align: center;"><b>Type</b></td>';
echo '<td style="vertical-align: center;"><b>Description</b></td>';
echo'</tr>';

$f_list = $this->getFileList();
foreach($f_list as $file) {
    $sa  = split(';', $file);
    echo '<tr>';
    echo '<td style="vertical-align: center;">
    <input type="checkbox" name="files[]" value="'.$sa[0].'">'.$sa[0].'</td>';
    echo '<td style="vertical-align: center;">'.$sa[1].'</td>';
    echo '<td style="vertical-align: center;">'.$sa[2].'</td>';
    echo '</tr>';
}

echo '<tr>';
echo '<td style="vertical-align: center;" >
<input type="checkbox" name="all" checked="checked">all</td>';
echo '<td colspan="2" rowspan="1" style="vertical-align: center;">
Update All Files </td>';
echo '</tr>';
echo '</tbody></table><br>';

echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
echo '<tbody>';

echo '<tr>';
echo '<td style="vertical-align: top;"><b>Accounts : </b></td>';
echo '<td style="vertical-align: top;">
<input type="text" name="accounts" size="75" value="ALL"></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top;"><b>Notes : </b></td>';
echo '<td colspan="3" rowspan="1" style="vertical-align: top;">
<textarea rows="2" cols="65" name="notes"></textarea></td>';
echo '</tr>';
echo '</tbody></table>';

echo '<div style="text-align: right;">';
echo '<input type="submit" value="Update Files">';
echo '</div>';
echo '</form>';

// table that allows the deletion of files or directories in the trash directory
echo '<hr style="width: 100%; height: 2px;">';
echo "<form action=\"$script\" method=\"POST\">";
echo '<input type="hidden" name="task" value="filemanager_remove_file">';

echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
echo '<tbody>';

echo '<tr>';
echo '<td style="vertical-align: center;"><b>Files in Trash</b></td>';
echo '<td style="vertical-align: center;"><b>Type</b></td>';
echo '<td style="vertical-align: center;"><b>Date/Time</b></td>';
echo '<td style="vertical-align: center;"><b>Description</b></td>';

echo'</tr>';

$f_list = $this->getTrashFiles();
foreach($f_list as $file) {
    $sa  = split(';', $file);
    echo '<tr>';
    echo '<td style="vertical-align: center;">
    <input type="checkbox" name="files[]" value="'.$sa[0].'">'.$sa[0].'</td>';
    echo '<td style="vertical-align: center;">'.$sa[1].'</td>';
    echo '<td style="vertical-align: center;">'.$sa[2].'</td>';
    echo '<td style="vertical-align: center;">'.$sa[3].'</td>';
    echo '</tr>';
}

echo '<tr>';
echo '<td style="vertical-align: center;" >
<input type="checkbox" name="all" checked="checked">all</td>';
echo '<td colspan="3" rowspan="1" style="vertical-align: center;">
Remove All Files </td>';
echo '</tr>';

echo '</tbody></table>';

echo '<div style="text-align: right;">';
echo '<input type="submit" value="Remove Files">';
echo '</div>';
echo '</form>';

