<?php
$addSlot_link = base_url()."group/meetings/addSlot";
$editSlot_link = base_url()."group/meetings/editSlot";

echo '<a name="add_slot"></a>'; // target for link from top
if(!empty($slot_id)) {
    echo "<br><form action='$editSlot_link' method='POST'>";
    echo '<input type="hidden" name="edit_slot_form" value="posted">';
} else {
    echo "<br><form action='$addSlot_link' method='POST'>";
    echo '<input type="hidden" name="add_slot_form" value="posted">';
}
echo '<input type="hidden" name="slot_id" value="'.$slot_id.'">';

echo '<table style="text-align: left; width: 100%; 
background-color: rgb(225, 255, 255);" border="1" cellpadding="1" cellspacing="0"><tbody>';

echo '<tr>';
echo '<td style="width: 10%;"><small><b>Date</b></small></td>';
echo '<td style="width: 15%;"><small><b>Type</b></small></td>';
echo '<td style="width: 20%;"><small><b>Presenter</b></small></td>';
echo '<td style="width: 55%;"><small><b>Title</b></small></td>';
echo '</tr>';
echo '<tr>';

echo '<td>';
echo '<select name="gmdate_id">';
if(isset($slot_info['gmdate_id'])) {
    $gmdate_id = $slot_info['gmdate_id'];
    $gd = $gmdates[$gmdate_id];
    echo '<option value="'.$gmdate_id.'">'.$gd->date.'</option>';
}

foreach($gmdates as $gmdate_id => $gd) {
    echo '<option value="'.$gmdate_id.'">'.$gd->date.'</option>';
}
echo '</select></td>';

echo '<td>';
echo '<select name="type">';
if(isset($slot_info['type'])) {
    $type = $slot_info['type'];
    echo '<option value="'.$type.'">'.$type.'</option>';
}

echo '<option value="Research Talk">Research Talk</option>';
echo '<option value="Literature Talk">Literature Talk</option>';
echo '<option value="Visitor Talk">Visitor Talk</option>';
echo '<option value="Refreshments">Refreshments</option>';
echo '<option value="Other">Other</option>';
echo '</select></td>';

echo '<td>';
if (isset($slot_info['presenter']))
    $presenter = $slot_info['presenter'];
else
    $presenter = '';
echo '<input size="20" name="presenter" value="'.htmlentities($presenter).'"></td>';

echo '<td>';
if (isset($slot_info['title']))
    $ptitle = $slot_info['title'];
else
    $ptitle = '';
echo '<input size="50" name="title" value="'.htmlentities($ptitle).'"></td></tr>';

echo '<tr><td><br></td>
<td style="text-align: left;" colspan="4" rowspan="1">';

// link to delete slot
if(!empty($slot_id)) {
    $delete_link = base_url()."group/meetings/deleteSlot?slot_id=".$slot_id;
    echo '[ <a href="'.$delete_link.'">delete slot</a> ] ';
}

// see if to print the links to edit or remove any attach files
if(!empty($slot_info['file_id'])) {
    $file_id = $slot_info['file_id'];
    $delete_file_link = base_url()."group/meetings/deleteFile?slot_id=$slot_id&file_id=$file_id";
    echo '[ <a href="'.$delete_file_link.'">delete file</a> ] ';
}

echo '<input value="'.$title.'" type="submit" 
style="background: rgb(238, 238, 238); color: #3366FF"></td></tr>';

echo '</tbody></table></form>';
