<?php
$count = count($items);

if($count < 1) {
    echo 'No Entries Found ...';
} else {
    $location_link = base_url()."group/supplies/listLocations";

    echo '<form enctype="multipart/form-data" action="'.base_url().'group/supplies/bulkActions/" method="POST">';
    echo '<input type="hidden" name="listing_form" value="posted">';
    echo '<table id="item_table" class="table table-condensed table-bordered"><thead>';

    echo '<tr>';
    echo '<th style="vertical-align: top; "></th>';
    echo '<th style="vertical-align: top; ">Supply ID</th>';
    echo '<th style="vertical-align: top; ">Model #</th>';
    echo '<th style="vertical-align: top; ">Name</th>';
    echo '<th style="vertical-align: top; ">Amount</th>';
    echo '<th style="vertical-align: top; ">Status</th>';
    echo '<th style="vertical-align: top; ">Location [ <a href="'.$location_link.'" target="_blank">?</a> ]</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
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
	echo '<td><input type="checkbox" name="item_ids[]" value="'.$item_id.'"></td>';
	echo "<td><a href=\"$info_link\">$item_id</a></td>";

	echo '<td>'.$model.'</td>';
	echo '<td>'.$name.'</td>';
	echo '<td>'.$amount.'</td>';
	echo '<td>'.$status.'</td>';
	echo '<td>'.$location_id.'</td>';
	echo '</tr>';
    }

    echo '<tr>';
    echo '<td><small>
    <input type="checkbox" name="all" value="'.$all_ids.'"> ALL </small></td>';

    echo '<td colspan="4" rowspan="1">
    <input type="radio" value="view" name="group_task" checked="checked"> View Selected 
    <input type="radio" value="transfer" name="group_task"> Make Selected Mine 
    </td>';
    echo '<td colspan="2">';
    echo '<div align="center"><input type="submit" value="Do Task" class="btn btn-primary"></div>';
    echo '</td>';
    echo '</tr>';

    echo '</tbody></table></form>';
}


