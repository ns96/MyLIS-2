<?php

$update_link = base_url()."group/orderbook/update_status";
$back_link = base_url()."group/orderbook"; // link to go back to the orders

echo '<div style="text-align: right; margin:0px 15px">';
echo '<a href="'.$back_link.'">Back to Orders</a></div>';
    
?>

<form name="form1" action="<?=$update_link?>" method="post" class="form-inline">
    <input type="hidden" name="task" value="orderbook_update2">
    <table class="table table-condensed table-bordered" id="pending_table">
	<thead>
	    <th>Order ID</th>
	    <th>Ordered By</th>
	    <th>Placed By</th>
	    <th>Company</th>
	    <th>Product ID</th>
	    <th>Description</th>
	    <th>Units</th>
	    <th>Cost</th>
	    <th>Date</th>
	    <th>R</th>
	    <th>C</th>
	</thead>
	<tbody>
	    <?
	    // print out all items that are pending
	    foreach($items as $item) {
		$info = preg_split("/\t/", $item);
		$order_id = trim($info[0]);
		$item_id = trim($info[1]);
		$owner = $this->getOrderedBy($order_id);
		$name = trim($info[2]);
		$company = trim($info[3]);
		$product = trim($info[4]);
		$description =trim($info[5]);
		$amount  = trim($info[6]);
		$units = trim($info[7]);
		$price = '$'.sprintf("%01.2f", $info[8]);
		$date = trim($info[9]);
		$id = $order_id.'_'.$item_id; // This will have to be broken up when updating

		$order_link = base_url()."group/orderbook/order_info?&order_id=$order_id"; 
		?>
		<tr>
		    <td><a href="<?=$order_link?>"><?=$order_id?></a></td>
		    <td><?=$owner?></td>
		    <td><?=$name?></td>
		    <td><?=$company?></td>
		    <td><?=$product?></td>
		    <td><?=$description?></td>
		    <td><?=$amount.'x '.$units?></td>
		    <td><input type="text" name="price_<?=$id?>" value="<?=$price?>" class="input-small"></td>
		    <td><?=$date?></td>
		    <td><input type="radio" name="status_<?=$id?>" value="received"></td>
		    <td><input type="radio" name="status_<?=$id?>" value="cancelled"></td>
		</tr>
	    <? } ?>
	</tbody>
    </table>

    <div style="text-align: right;">
	<button type="reset" class="btn btn btn-small" style="margin-right:10px">Reset!</button>
	<button type="submit" class="btn btn-primary btn-small">Update Status</button>
    </div>

</form>

