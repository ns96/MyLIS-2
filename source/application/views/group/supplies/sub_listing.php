<?php
$count = count($items);

if($count < 1) {
    echo 'No Entries Found ...';
} else {
    $cell_color1 = 'rgb(150,200,255)';
    $cell_color2 = 'rgb(230,230,230)';
    $location_link = base_url()."group/supplies/listLocations";

    echo '<form enctype="multipart/form-data" action="'.base_url().'group/supplies/bulkActions/" method="POST">';
    echo '<input type="hidden" name="listing_form" value="posted">';
    echo '<table style="background-color: rgb(255, 255, 255); width: 100%; text-align: left;"
    border="0" cellpadding="2" cellspacing="2"><tbody>';

    echo '<tr>';
    echo '<td style="vertical-align: top; background-color: '.$cell_color1.';"><small><b>Supply ID</b></small></td>';
    echo '<td style="vertical-align: top; background-color: '.$cell_color1.';"><small><b>Model #</b></small></td>';
    echo '<td style="vertical-align: top; background-color: '.$cell_color1.';"><small><b>Name</b></small></td>';
    echo '<td style="vertical-align: top; background-color: '.$cell_color1.';"><small><b>Amount</b></small></td>';
    echo '<td style="vertical-align: top; background-color: '.$cell_color1.';"><small><b>Status</b></small></td>';
    echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
    <small><b>Location [ <a href="'.$location_link.'" target="_blank">?</a> ]</b></small></td>';
    echo '</tr>';

    // variable that holds the ids as string
    $all_ids = '';

    foreach($items as $supply){
	$item_id = $supply['item_id'];
	$model = $supply['model'];
	$name = $supply['name'];
	$amount = $supply['amount'];
	$unit = $supply['units'];
	$status = $supply['status'];
	$location_id = $supply['location_id'];

	// add this to the ids list
	$all_ids .= $item_id.' ';

	// define some variables
	$amount = $amount.'x '.$unit; // the total amount
	$info_link = base_url().'group/supplies/view?item_id='.$item_id;

	echo '<tr>';
	echo '<td style="vertical-align: top; background-color: '.$cell_color2.';"><small>
	<input type="checkbox" name="item_ids[]" value="'.$item_id.'">';
	echo "[ <a href=\"$info_link\">$item_id</a> ]</td></small>";

	echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>
	'.$model.'</td></small>';
	echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>
	'.$name.'</small></td>';
	echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>
	'.$amount.'</small></td>';
	echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>
	'.$status.'</small></td>';
	echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>
	'.$location_id.'</small></td>';
	echo '</tr>';
    }

    echo '<tr>';
    echo '<td style="vertical-align: top; background-color: '.$cell_color2.';"><small>
    <input type="checkbox" name="all" value="'.$all_ids.'"> ALL </small></td>';

    echo '<td colspan="4" rowspan="1" style="vertical-align: top; background-color: '.$cell_color2.';"><small>
    <input type="radio" value="view" name="group_task" checked="checked">
    <span style="font-weight: bold; color: rgb(0, 0, 0);">View Selected</span> 
    <input type="radio" value="transfer" name="group_task">
    <span style="font-weight: bold; color: rgb(0, 0, 0);">Make Selected Mine</span> 
    </small></td>';
    echo '<td style="vertical-align: center; text-align: center; background-color: '.$cell_color2.';">
    <input type="submit" value="Do Task" 
    style="background: rgb(238, 238, 238); color: #3366FF"></td>';
    echo '</tr>';

    echo '</tbody></table></form>';
}


