<table class="publication_table">
    <?
    // create the table rows with the publications of a certain poster
    $row_count = 0;
    $totalRows = count($publications);
    
    foreach($publications as $publication) {
	if ($row_count == 0) {?>
	    <tr>
		<td rowspan="<?=$totalRows+1?>" style="background-color: #E9EDF3; text-align: center">
		    <?=$poster->name?>
		</td>
	    </tr>
	<?}

	    $link = base_url().'group/publications/show/'.$publication['publication_id'];
	    $edit_link = base_url().'group/publications/edit/'.$publication['publication_id'];
	    $title = $publication['title'];

	    echo '<tr>';
	    echo "<td style='width: 47%; vertical-align: top;'><a href='$link'>$title</a><br></td>";
	    echo "<td style='width: 12%; vertical-align: top;'>$publication[status]<br></td>";
	    echo "<td style='width: 12%; vertical-align: top;'>$publication[modify_date]<br></td>";
	    if($poster->userid == $session_userid || $poster->role == 'admin') {
		echo "<td style='width: 12%; vertical-align: top;'><small><a href='$edit_link'>".strtoupper($publication['type'])."</a></small><br></td>";
	    }
	    else {
		echo "<td style='width: 12%; vertical-align: top;'><small>".strtoupper($publication['type'])."</small><br></td>";
	    }
	    echo '</tr>';
	
	$row_count++;
    }
    ?>
</table>


