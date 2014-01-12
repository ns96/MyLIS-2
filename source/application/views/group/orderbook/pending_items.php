<?php

    if(!empty($message))
	echo $message;

    $update_link = base_url()."group/orderbook/items_pending";
    $back_link = base_url()."group/orderbook"; // link to go back to the orders
    
    echo '<div style="text-align: right;">';

    if($role == 'admin' || $role == 'buyer') {
	echo 'Status Code :: <b><span style="color: rgb(0, 0, 235);">O</span></b> = Ordered ||
	<b><span style="color: rgb(0, 0, 235);">BO</span></b> = Back Ordered || 
	<b><span style="color: rgb(0, 0, 235);">C</span></b> = Cancel Item ';
    } 

    echo '[ <a href="'.$back_link.'">Back to Orders</a> ]</div>';
    
?>

<form name="form1" action="<?=$update_link?>" method="post" class="form-inline">
    <input type="hidden" name="pending_items_form" value="posted">
    <table class="table table-condensed table-bordered" id="pending_table">
	<thead>
	    <th>Order ID</th>
	    <th>Placed By</th>
	    <th>Company</th>
	    <th>Product ID</th>
	    <th>Description</th>
	    <th>Units</th>
	    <th>Cost</th>
	    <th>Date</th>
	    <?
	    if($role == 'admin' || $role == 'buyer') {
		echo '<th>O</th>'; // ordered
		echo '<th>BO</th>';
		echo '<th>C</th>';
	    }
	    ?>
	</thead>
	<tbody>
	    <?
	    // print out all items that are pending
	    foreach($items as $item) {
		$info = preg_split("/\t/", $item);
		$order_id = trim($info[0]);
		$item_id = trim($info[1]);
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
		    <td><?=$name?></td>
		    <td><?=$company?></td>
		    <td><?=$product?></td>
		    <td><?=$description?></td>
		    <td><?=$amount.'x '.$units?></td>
		    <td><input type="text" name="price_<?=$id?>" value="<?=$price?>" class="input-block-level input-small"></td>
		    <td><?=$date?></td>
		    <?
		    if($role == 'admin' || $role == 'buyer') {
			echo '<td><input type="radio" name="status_'.$id.'" value="ordered"></td>';
			echo '<td><input type="radio" name="status_'.$id.'" value="back ordered"></td>';
			echo '<td><input type="radio" name="status_'.$id.'" value="cancelled"></td>';
		    }
		    ?>
		</tr>
	    <? } ?>
	</tbody>
    </table>
    <?
    if($role == 'admin' || $role == 'buyer') {
	echo '<div style="text-align: right;">';
	echo '<button type="reset" class="btn btn btn-small" style="margin-right:10px">Reset!</button>';
	echo '<button type="submit" class="btn btn-primary btn-small">Update Status</button>';
	echo '</div>';
    }
    ?>
</form>

