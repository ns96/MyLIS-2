<?php
    
$back_url = base_url().'admin/managedb';
echo "<div style='text-align:right'>[ <a href='$back_url'>Back</a> ]</div>";

if(count($tables) < 1) {
    echo 'There are no tabels in this database...';
}
else {
    echo 'There are '.count($tables).' tables in this database.<br><br>';

    echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
    echo '<tbody>';
    echo '<tr>';
    echo '<td style="vertical-align: center;"><b>Table Name</b></td>';
    echo '<td style="vertical-align: center;"><b>Data Size</b></td>';
    echo '<td style="vertical-align: center;"><b>Index Size</b></td>';
    echo '<td style="vertical-align: center;"><b>Total Size</b></td>';
    echo '<td style="vertical-align: center;"><b>Total Rows</b></td>';
    echo '</tr>';

    foreach($tables as $array) {
	$total = $array['Data_length']+$array['Index_length'];

	echo '<tr>';
	echo '<td style="vertical-align: center;">'.$array['Name'].'</td>';
	echo '<td style="vertical-align: center;">'.$array['Data_length'].'</td>';
	echo '<td style="vertical-align: center;">'.$array['Index_length'].'</td>';
	echo '<td style="vertical-align: center;">'.$total.'</td>';
	echo '<td style="vertical-align: center;">'.$array['Rows'].'</td>';
	echo '</tr>';
    }

    echo '</tbody></table>';
}

