<?php

$back_link = base_url()."group/orderbook"; // go back to the orders
$target_link = base_url()."group/orderbook/itemlist_multiple_info";

// get an array of company item lists. basically orders where the status is itemlist
$count = count($orders);
echo "<div style='text-align:right; margin:0px 15px;'><a href='".$back_link."'>Back to Orders</a></div>";

if($count > 0) {
    ?>
    <form name="form1" action="<?=$target_link?>" method="post">
	<input type="hidden" name="task" value="orderbook_itemlist_mview">

	<table class="table table-condensed table-bordered">
	    <thead>
		<th width="10%">Item List ID</th>
		<th>Created By</th>
		<th>Company</th>
		<th>Shared With Group?</th>
		<th>Date Last Modified</th>
		<th>&nbsp;</th>
	    </thead>
	    <tbody>
		<?
		foreach($orders as $order_id => $order) {
		    if($order_id == 'total') { 
			continue;
		    }

		    $info = preg_split("/\t/", $order);
		    $user = $users[trim($info[0])];
		    $ordered_by = $user->name;
		    $company = trim($info[1]);
		    $date = trim($info[3]);
		    $priority = trim($info[5]);

		    if(stristr($priority, 'high')) {
			$shared = 'Yes';
		    } else {
			$shared = 'No';
		    }

		    // see whether to display the item list based on if it is public or not
		    if($role == 'admin' || $role == 'buyer' || $user_id == trim($info[0]) || $shared == 'Yes') {
			$order_link = base_url()."group/orderbook/itemlist_info?order_id=$order_id";       
			$edit_link = base_url()."group/orderbook/itemlist?order_id=$order_id";

			echo '<tr>';
			    echo '<td>
			    <input type="checkbox" name="order_ids[]" value="'.$order_id.'"> 
			     <a href="'.$order_link.'">'.$order_id.'</a>';
			    echo '</td>'; 
			    echo '<td>'.$ordered_by.'</td>';
			    echo '<td>'.$company.'</td>';
			    echo '<td>'.$shared.'</td>';
			    echo '<td>'.$date.'</td>';
			    echo '<td style="text-align:center">';
			    if($role == 'admin' || $role == 'buyer' || $user_id == trim($info[0])) {
			    echo '<a href="'.$edit_link.'" class="btn btn-success btn-mini">Edit</a>';
			    }
			    echo '</td>';
			echo '</tr>';
		    }
		}
		?>
	    </tbody>
	</table>
	<div style="text-align: right"><button type="submit" class="btn btn-primary btn-small">View Selected</button></div>
    </form>
    <?
} else {
  echo 'No item list found ...</td>';
}



