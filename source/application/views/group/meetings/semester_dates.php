<?php

// get any meeting slots from database and display them
$default_link = base_url()."group/meetings?default_semester=$semester_id";
?>
<table class="semesterTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
    <tbody>
        <tr>
            <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
                <?=$name?> [ <a href="<?=$default_link?>">make default</a> ]
            </td>
        </tr>
    </tbody>
</table>
<?
// display the dates now
foreach ($dates as $gmdate_id => $gd) {
    if($semester_id == $gd->semester_id) {
	$edit_link = base_url()."group/meetings?gmdate_id=$gmdate_id";
	$delete_link = base_url()."group/meetings/delete_date?gmdate_id=$gmdate_id";

	echo "<table class='semester_table'>";
        echo '<tr>';
	echo '<td valign="center" align="center">';
	echo '<small><span style="color: #212063;"><b>'.$gd->date.'</b></span><br>';
	echo '<span style="color: #212063;">('.$gd->time.')</span><br></small>';
        
        echo "<div style='padding:3px;'>";
	echo '<a href="'.$edit_link.'#add_date">';
        echo '<button class="btn btn-mini btn-success" type="button">Edit</button>';
        echo '</a>';
	if($gd->userid == $userid || $role == 'admin') {
	    echo '<a href="'.$delete_link.'">';
            echo '<button class="btn btn-mini btn-danger" type="button" style="margin-left:8px">Delete</button>';
            echo '</a>';
	}
        echo "</div>";

	echo '</td>';
	echo '<td>';
	
	$dateSlots = $allSlots[$gmdate_id];

	if(count($dateSlots) >= 1) {
	    foreach($dateSlots as $singleSlot) {
		$slot_id  = $singleSlot['slot_id'];
		$file_id = $singleSlot['file_id'];

		$edit_link = base_url()."group/meetings?slot_id=$slot_id";
		$upload_link = base_url()."group/meetings/add_file?slot_id=$slot_id&file_id=$file_id";
		$upload_name = 'upload file';

		echo '<table cellpadding="2" cellspacing="2" border="0" width="100%"><tbody>';
		echo '<tr>';
		echo '<td valign="top" width="20%"><small>';
		echo '<a href="'.$edit_link.'#add_slot">'.$singleSlot['type'].'</a></small><br></td>';

		echo '<td valign="top" width="15%"><small>'.$singleSlot['presenter'].'</small><br></td>';
		echo '<td valign="top" width="50%"><small>';

		if(empty($file_id)) {
		    echo $singleSlot['title'];
		}
		else {
		    $upload_name = 'update file';
		    $download_link = $singleSlot['fileURL'];
		    echo '<a href="'.$download_link.'">'.$singleSlot['title'].'</a>';
		}

		echo '</small><br></td>';

		echo '<td valign="top" width="15%">';

		if($singleSlot['type'] != 'Refreshments') {
		    echo '<small>[ <a href="'.$upload_link.'">'.$upload_name.'</a> ]</small>';
		}

		echo '<br></td>'; 
		echo '</tr>';
		echo '</tbody></table>';
	    }
	} else {
	    echo '<br>'; // so it prints the table borders
	}
	echo '</td>';
	echo '</tr>';
        echo '</table>';
    }
}

