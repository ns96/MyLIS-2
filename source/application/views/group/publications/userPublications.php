<?php
echo '<tr>';
echo '<td style="width: 20%; vertical-align: top;"><font color=\"\#000000\"><small><b>'.$poster->name.'</b></small></font><br></td>';
echo '<td style="vertical-align: top;">';
    
echo '<table cellpadding="2" cellspacing="2" border="0" width="100%"><tbody>';
    
$pcount = 0;
foreach($publications as $publication) {
    $rm = $pcount % 2;
    if($rm == 0) {
	$bgc = '#F0F0F0';
    }
    else {
	$bgc = '#F0F0F0';
    }
    $pcount++;

    $link = base_url().'group/publications/show/'.$publication['publication_id'];
    $edit_link = base_url().'group/publications/edit/'.$publication['publication_id'];
    $title = $publication['title'];
    
    echo '<tr>';
    echo "<td style=\"width: 55%; vertical-align: top;\" bgcolor=\"$bgc\"><small><a href=\"$link\">$title</a></small><br></td>";
    echo "<td style=\"width: 15%; vertical-align: top;\" bgcolor=\"$bgc\"><small>$publication[status]</small><br></td>";
    echo "<td style=\"width: 15%; vertical-align: top;\"  bgcolor=\"$bgc\"><small>$publication[modify_date]<br></small></td>";
    if($poster->userid == $session_userid || $poster->role == 'admin') {
	echo "<td style=\"width: 15%; vertical-align: top;\" bgcolor=\"$bgc\"><small><a href=\"$edit_link\">".strtoupper($publication['type'])."</a></small><br></td>";
    }
    else {
	echo "<td style=\"width: 15%; vertical-align: top;\" bgcolor=\"$bgc\"><small>".strtoupper($publication['type'])."</small><br></td>";
    }
    echo '</tr>';
}

echo '</tbody></table>';
echo '</td></tr>';