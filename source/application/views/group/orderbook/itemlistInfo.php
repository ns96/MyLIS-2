<?php

$company = $order['company'];
$ponum = $order['ponum'];
$conum = $order['conum'];
$priority = $order['priority'];
$status = $order['status'];
$status_date = $order['status_date'];
$account = $order['account'];
$g_expense = '$'.sprintf("%01.2f", $order['g_expense']);
$p_expense = '$'.sprintf("%01.2f", $order['p_expense']);
$s_expense = '$'.sprintf("%01.2f", $order['s_expense']);
$sum = $order['g_expense'] + $order['p_expense'] + $order['s_expense'];
$total = '$'.sprintf("%01.2f", $sum);
$notes = str_replace("\n", "<br>", $order['notes']); // replace any new line with a break
$owner = $order['owner'];
$maxitems = $order['maxitems'];

// based on the priority see whether to share with group to to Yes or No
if($priority == 'High') {
  $priority = 'Yes';
}
else {
  $priority = 'No';
}

// some links
$edit_link = base_url()."group/orderbook/itemlist?order_id=$order_id";
$remove_link = base_url()."group/orderbook/itemlist_delete?order_id=$order_id";
$back_link = base_url()."group/orderbook"; // go back to the orders

$user = $users[$owner];
$owner_name = $user->name;
?>

<div style="text-align: right">
    <a href="<?=$back_link?>">Back to Orders</a>
</div>

<div class="tabbable" id="info_navtab" style="margin-left: 15px; margin-right: 15px;"> <!-- Only required for left/right tabs -->
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab">Item List ID: <?=$order_id?></a></li>
    <div style="text-align:left; vertical-align: middle; margin-top:5px;">
	<?
	if($owner == $user_id || $role == 'admin' || $role == 'buyer') {
	    echo '<a href="'.$edit_link.'" class="btn btn-success btn-small" style="margin-left:15px">Edit</a>';
	    echo '<a href="'.$remove_link.'" class="btn btn-danger btn-small" style="margin-left:10px">Delete Item List</a>';
	}
    ?>
    </div>
  </ul>
</div>

<div class="formWrapper" style="margin-top: 0px">
    <div style="margin:10px 10px;">
	<span style="color:#0088CC; margin-bottom: 3px;">General Information</span>
	<table class="order_table">
	    <tr>
		<td class="order_label">Created By :</td>
		<td><span class="label label-info"><?=$owner_name?></span></td>
		<td class="order_label">Company :</td>
		<td><?=$company?></td>
	    </tr>
	    <tr>
		<td class="order_label">Max # of Items :</td>
		<td><?=$maxitems?></td>
		<td class="order_label">Shared with Group :</td>
		<td><?=$priority?></td>
		<td class="order_label">Date Last Modified:</td>
		<td><?=$status_date?></td>
	    </tr>
	    <tr>
		<td class="order_label">Order Notes </td>
		<td colspan="5"><?=$notes?></td>
	    </tr>
	</table>
	<span style="color:#0088CC; margin-bottom: 3px;">Itemlist Items</span>
	<table class="order_table">
	    <thead>
		<th>Item #</th>
		<th>Type</th>
		<th>Company</th>
		<th>Product ID</th>
		<th>Description</th>
		<th>Units</th>
		<th>Cost</th>
	    </thead>
	    <tbody>
		<?
		for($i = 1; $i <= $maxitems; $i++) {
		    if(isset($order['item_'.$i])) {
			$info = preg_split("/\t/", $order['item_'.$i]);
			$type = trim($info[0]);
			$company = trim($info[1]);
			$product = trim($info[2]);
			$description =trim($info[3]);
			$units = trim($info[5]);
			$price = '$'.sprintf("%01.2f", $info[6]);
			$owner = trim($info[7]);
			$item_id = trim($info[10]);

			echo '<tr>';
			echo '<td>'.$item_id.'</td>';
			echo '<td>'.$type.'</td>';
			echo '<td>'.$company.'</td>';
			echo '<td>'.$product.'</td>';
			echo '<td>'.$description.'</td>';
			echo '<td>'.$units.'</td>';
			echo '<td>'.$price.'</td>';
			echo '</tr>';
		    }
		}
		?>
	    </tbody>
	</table>
    </div>
</div>





