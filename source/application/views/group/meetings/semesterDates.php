<?php

// get any meeting slots from database and display them
$default_link = base_url()."group/meetings?default_semester=$semester_id";

echo '<tr>';
echo '<td valign="top" width="12%"><br></td>'; // print a blank
echo '<td valign="top" bgcolor="#FFFFBE"><small>';
echo '<span style="color: #cc0000;"><b>'.$name.'</b></span> '; // display semester name
if($role == 'admin') {
    echo '[ <a href="'.$default_link.'">make default</a> ]';
}
echo '</small></td></tr>';

// display the dates now
foreach ($dates as $gmdate_id => $gd) {
    if($semester_id == $gd->semester_id) {
	$edit_link = base_url()."group/meetings/edit?gmdate_id=$gmdate_id";
	$delete_link = base_url()."group/meetings/deleteDate?gmdate_id=$gmdate_id";

	echo '<tr>';
	echo '<td valign="center" align="center">';
	echo '<small><span style="color: #212063;"><b>'.$gd->date.'</b></span><br>';
	echo '<span style="color: #212063;">('.$gd->time.')</span><br>';
	echo '[ <a href="'.$edit_link.'#add_date">edit</a> ] ';
	if($gd->userid == $userid || $role == 'admin') {
	    echo '[ <a href="'.$delete_link.'">delete</a> ]';
	}

	echo '</small></td>';
	echo '<td>';
	
	$dateSlots = $allSlots[$gmdate_id];

	if(count($dateSlots) >= 1) {
	    foreach($dateSlots as $singleSlot) {
		$slot_id  = $singleSlot['slot_id'];
		$file_id = $singleSlot['file_id'];

		$edit_link = base_url()."group/meetings?slot_id=$slot_id";
		$upload_link = base_url()."group/meetings/addfile?slot_id=$slot_id&file_id=$file_id";
		$upload_name = 'upload file';

		echo '<table cellpadding="2" cellspacing="2" border="0" width="100%"><tbody>';
		echo '<tr>';
		echo '<td valign="top" width="20%" bgcolor="#f5f6f7"><small>';
		echo '<a href="'.$edit_link.'#add_slot">'.$singleSlot['type'].'</a></small><br></td>';

		echo '<td valign="top" width="15%" bgcolor="#f5f6f7"><small>'.$singleSlot['presenter'].'</small><br></td>';
		echo '<td valign="top" width="50%" bgcolor="#f5f6f7"><small>';

		if(empty($file_id)) {
		    echo $singleSlot['title'];
		}
		else {
		    $upload_name = 'update file';
		    $download_link = $singleSlot['fileURL'];
		    echo '<a href="'.$download_link.'">'.$singleSlot['title'].'</a>';
		}

		echo '</small><br></td>';

		echo '<td valign="top" width="15%" bgcolor="#f5f6f7">';

		if($singleSlot['type'] != 'Refreshments') {
		    echo '<small>[ <a href="'.$upload_link.'">'.$upload_name.'</a> ]</small>';
		}

		echo '<br></td>'; /* fix this 12/26/07*/
		echo '</tr>';
		echo '</tbody></table>';
	    }
	} else {
	    echo '<br>'; // so it prints the table borders
	}
	echo '</td>';
	echo '</tr>';
    }
}
