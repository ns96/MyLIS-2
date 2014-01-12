<?php

echo "<div style='text-align:right'><a href='$back_link'>Back</a></div>";

$count = count($items);
if($count > 0) {
    echo "<small><span style=\"color: rgb(225, 0, 0);\"><b>$count</b></span> Total ...</small><br>";
    $location_link = base_url()."group/supplies/list_locations";
    
    // variable that holds the ids as string
    $all_ids = '';
    
    ?>
    <form enctype="multipart/form-data" action="<?=base_url()?>group/supplies/bulk_actions/" method="POST">
	<input type="hidden" name="listing_form" value="posted">
	<table class="table table-condensed table-bordered">
	    <thead>
		<th>Item ID</th>
		<th>Model #</th>
		<th>Name</th>
		<th>Amount</th>
		<th>Status</th>
		<th>Location [ <a href="<?=$location_link?>" target="_blank">?</a> ]</th>
	    </thead>
	    <tbody>
		<?
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
		    ?>
		    <tr>
			<td>
			    <input type="checkbox" name="item_ids[]" value="<?=$item_id?>">
			    &nbsp;&nbsp;<a href='<?=$info_link?>'><?=$item_id?></a>
			</td>
			<td><?=$model?></td>
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


