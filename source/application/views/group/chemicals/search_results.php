<?php

echo "<div style='text-align:right'><a href='$back_link'>Back</a></div>";

$count = count($items);
if($count > 0) {
    echo "<small><span style=\"color: rgb(225, 0, 0);\"><b>$count</b></span> Total ...</small><br>";
    $location_link = base_url()."group/chemicals/listLocations";
    
    // variable that holds the ids as string
    $all_ids = '';
    
    ?>
    <form enctype="multipart/form-data" action="<?=base_url()?>group/chemicals/bulkActions/" method="POST">
	<input type="hidden" name="listing_form" value="posted">
	<table class="table table-condensed table-bordered">
	    <thead>
		<th>Chem ID</th>
		<th>CAS #</th>
		<th>Name</th>
		<th>Amount</th>
		<th>Status</th>
		<th>Location [ <a href="<?=$location_link?>" target="_blank">?</a> ]</th>
	    </thead>
	    <tbody>
		<?
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
		    ?>
		    <tr>
			<td>
			    <input type="checkbox" name="chem_ids[]" value="<?=$chem_id?>">
			    &nbsp;&nbsp;<a href='<?=$info_link?>'><?=$chem_id?></a>
			</td>
			<td><?=$cas?></td>
			<td><?=$name?></td>
			<td><?=$amount?></td>
			<td><?=$status?></td>
			<td><?=$location_id?></td>
		    </tr>
		<? } ?>
		    <tr>
			<td>
			    <input type="checkbox" name="all" value="<?=$all_ids?>"> ALL
			</td>
			<td colspan="4">
			    <input type="radio" value="view" name="group_task" checked="checked">
			    View Selected
			    <input type="radio" value="transfer" name="group_task" style="margin-left: 10px">
			    Make Selected Mine
			</td>
			<td style="text-align: center">
			    <button type="submit" class="btn btn-primary btn-small">Do Task</button>
			</td>
		    </tr>
	    </tbody>
	</table>
    </form>
    <?
} else {
    echo 'No Entries Found ...';
}
