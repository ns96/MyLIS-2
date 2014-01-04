<?php
$count = count($items);

if($count < 1) {
    echo 'No Entries Found ...';
} else {
    $location_link = base_url()."group/chemicals/list_locations";

    echo '<form enctype="multipart/form-data" action="'.base_url().'group/chemicals/bulk_actions/" method="POST">';
    echo '<input type="hidden" name="listing_form" value="posted">';
    echo '<table id="chem_table" class="table table-condensed table-bordered"><thead>';

    echo '<tr>';
    echo '<th style="vertical-align: top; "></th>';
    echo '<th style="vertical-align: top; ">Chem ID</th>';
    echo '<th style="vertical-align: top; ">CAS #</th>';
    echo '<th style="vertical-align: top; ">Name</th>';
    echo '<th style="vertical-align: top; ">Amount</th>';
    echo '<th style="vertical-align: top; ">Status</th>';
    echo '<th style="vertical-align: top; ">Location [ <a href="'.$location_link.'" target="_blank">?</a> ]</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    // variable that holds the ids as string
    $all_ids = '';

    foreach($items as $chemical){
	$chem_id = $chemical['chem_id'];
	$cas = $chemical['cas'];
	$name = $chemical['name'];
	$amount = $chemical['amount'];
	$unit = $chemical['units'];
	$status = $chemical['status'];
	$location_id = $chemical['location_id'];

	// add this to the ids list
	$all_ids .= $chem_id.' ';

	// define some variables
	$amount = $amount.'x '.$unit; // the total amount
	$info_link = base_url().'group/chemicals/view?chem_id='.$chem_id;

	echo '<tr>';
	echo '<td><input type="checkbox" name="chem_ids[]" value="'.$chem_id.'"></td>';
	echo "<td><a href=\"$info_link\">$chem_id</a></td>";

	echo '<td>'.$cas.'</td>';
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


