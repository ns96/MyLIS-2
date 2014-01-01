<?php
    
$page_url = encodeUrl(base_url().'admin/managedb');


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
	$html .=  '<td>'.$tableStatus['Name'].'</td>';
	$html .=  '<td>'.$tableStatus['Data_length'].'</td>';
	$html .=  '<td>'.$tableStatus['Index_length'].'</td>';
	$html .=  '<td>'.$total.'</td>';
	$html .=  '<td>'.$tableStatus['Rows'].'</td>';
	$html .=  '</tr>';
    }

    echo 'There are '.count($statusInfo).' tables in this database.<br>
    Total Size : '.$total_size.'<br><br>';
    ?>
    <table class="table table-condensed table-bordered">
	<thead>
	    <th>Table Name</th>
	    <th>Data Size</th>
	    <th>Index Size</th>
	    <th>Total Size</th>
	    <th>Total Rows</th>
	</thead>
	<tbody>
	    <?=$html?>
	</tbody>
    </table>
    <?
}

