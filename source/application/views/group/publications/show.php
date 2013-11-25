<?php

// set the links now
$publication_link = base_url().'group/publications';
$home_link = base_url().'group/main';

echo '<table style="text-align: left; width: 100%;" border="0"
cellpadding="2" cellspacing="2"><tr><td>
<b><span style="color: #3366FF;">Publication # </span><font color="#cc0000">'.$pub_id.'</font></b></td>
<td style="text-align: right;">
[ <a href="'.$publication_link.'">Publication List</a> ] 
[ <a href="'.$home_link.'">Home</a> ]
</td></tr></tbody></table>';


echo "<form method='POST' action ='".base_url()."group/publications/edit/".$pub_id."'>";

echo printColoredLine('#3366FF', '2px').'<pre></pre>';

//add the title table
echo '<table cellpadding="2" cellspacing="2" border="0" width="100%"><tbody>';
echo '<tr>';
echo '<td valign="top" width="15%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Title</b></small></font><br></td>';
echo '<td valign="top" width="85%" bgcolor="#F0F0F0"><font color="#000000"><small>'.$pub['title'].'</small></font><br></td>';
echo '</tr>';
echo '<tr>';
echo '<td valign="top" width="15%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Author(s)</b></small></font><br></td>';
echo '<td valign="top" width="85%" bgcolor="#F0F0F0"><font color="#000000"><small>'.$pub['authors'].'</small></font><br></td>';
echo '</tr>';
echo '</tbody></table>';
echo '<br>';

// add the dates table
echo '<table cellpadding="2" cellspacing="2" border="0" width="100%"><tbody>';
echo '<tr>';
echo '<td valign="top" width="15%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Type</b></small></font><br></td>';
echo '<td valign="top" width="25%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Status</b></small></font><br></td>';
echo '<td valign="top" width="20%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Start Date</b></small></font></small><br></td>';
echo '<td valign="top" width="20%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Last Update</b></small></font><br></td>';
echo '<td valign="top" width="20%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Deadline</b></small></font><br></small></td>';
echo '</tr>';
echo '<tr>';
echo '<td valign="top" width="15%" bgcolor="#F0F0F0"><font color="#000000"><small>'.$pub['type'].'</small></font><br></td>';
echo '<td valign="top" width="25%" bgcolor="#F0F0F0"><font color="#000000"><small>'.$pub['status'].'</small></font><br></td>';
echo '<td valign="top" width="20%" bgcolor="#F0F0F0"><font color="#000000"><small>'.$pub['start_date'].'</small></font><br></td>';
echo '<td valign="top" width="20%" bgcolor="#F0F0F0"><font color="#000000"><small>'.$pub['modify_date'].'</small></font><br></td>';
echo '<td valign="top" width="20%" bgcolor="#F0F0F0"><font color="#000000"><small>'.$pub['end_date'].'</small></font><br></td>';
echo '</tr>';
echo '</tbody></table>';
echo '<br>';
echo '<table cellpadding="2" cellspacing="2" border="0" width="100%"><tbody>';
echo '<tr>';
echo '<td valign="top" width="15%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Abstract</b></small></font><br></td>';
echo '<td valign="top" width="85%" bgcolor="#F0F0F0"><pre>'.$pub['abstract'].'</pre></td>';
echo '</tr>';
echo '<tr>';
echo '<td valign="top" width="15%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Comments</b></small></font><br></td>';
echo '<td valign="top" width="85%" bgcolor="#F0F0F0"><pre>'.$pub['comments'].'</pre></td>';
echo '</tr>';
echo '</tbody></table>';

// add the file list, but first see if its the special user or an author on the paper
echo '<br>';
echo '<table cellpadding="2" cellspacing="2" border="0" width="100%"><tbody>';
echo '<tr>';
echo '<td valign="top" width="5%" bgcolor="#b5cbe7"><font color="#212063"><small><b>File</b></small></font><br></td>';
echo '<td valign="top" width="85%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Description</b></small></font><br></td>';
echo '<td valign="top" width="10%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Get</b></small></font><br></td>';
echo '</tr>';

if(count($fileData)>0) {
    $i = 1;
    foreach ($fileData as $fileItem) {
	echo '<tr>';
	echo '<td valign="top" width="5%" bgcolor="#F0F0F0"><font color="#000000"><small>'.$i.'</small></font></td>';
	echo '<td valign="top" width="85%" bgcolor="#F0F0F0"><font color="#000000"><small>'.$fileItem['info']['description'].'</small></font></td>';
	echo '<td valign="top" width="10%" bgcolor="#F0F0F0"><font color="#000000"><small>[ <a href="'.$fileItem['link'].'">'.$fileItem['info']['type'].'</a> ]</small></font></td>';
	echo '</tr>';
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
echo '<br>';

echo "<div style=\"text-align: right;\">";
if($pub['userid'] == $session_userid || $session_role == 'admin') {
    echo '<input type="submit" value="Edit Publication" style="background: rgb(238, 238, 238); color: #3366FF">';
}

echo '</div></form>';
