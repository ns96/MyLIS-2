<?php
    
$page_url = encodeUrl(base_url().'admin/managedb');

// display the main page now
echo '<table style="width: 100%; text-align: left;" border="0" cellpadding="2" cellspacing="0">';
echo '<tbody>';
echo '<tr>';
echo '<td style="vertical-align: top;">';
echo '<h3><span style="color: rgb(0, 0, 102);">Database Tables ( "'.$account_id.'" )</span><br></h3>';
echo '</td>';
echo '<td style="vertical-align: top; text-align: right;">';
echo "<b>[ <a href=\"$page_url\">Home</a> ]</b><br>";
echo '</td></tr></tbody></table>';

echo '<hr style="width: 100%; height: 2px;">';


if(count($statusInfo) < 1) {
    echo 'There are no database tabels for this accounts...';
}
else {
    $html = '';
    $total_size = 0;

    foreach($statusInfo as $tableStatus) {
	$total = $tableStatus['Data_length']+$tableStatus['Index_length'];
	$total_size += $total;

	$html .=  '<tr>';
	$html .=  '<td style="vertical-align: center;">'.$tableStatus['Name'].'</td>';
	$html .=  '<td style="vertical-align: center;">'.$tableStatus['Data_length'].'</td>';
	$html .=  '<td style="vertical-align: center;">'.$tableStatus['Index_length'].'</td>';
	$html .=  '<td style="vertical-align: center;">'.$total.'</td>';
	$html .=  '<td style="vertical-align: center;">'.$tableStatus['Rows'].'</td>';
	$html .=  '</tr>';
    }

    echo 'There are '.count($statusInfo).' tables in this database.<br>
    Total Size : '.$total_size.'<br><br>';

    echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
    echo '<tbody>';
    echo '<tr>';
    echo '<td style="vertical-align: center;"><b>Table Name</b></td>';
    echo '<td style="vertical-align: center;"><b>Data Size</b></td>';
    echo '<td style="vertical-align: center;"><b>Index Size</b></td>';
    echo '<td style="vertical-align: center;"><b>Total Size</b></td>';
    echo '<td style="vertical-align: center;"><b>Total Rows</b></td>';
    echo '</tr>';
    echo $html;
    echo '</tbody></table>';
}

