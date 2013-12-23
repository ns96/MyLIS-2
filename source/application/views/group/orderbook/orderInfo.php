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

// some links
$edit_link = base_url()."group/orderbook?order_id=$order_id";
$remove_link = base_url()."group/orderbook/order_delete?order_id=$order_id";
$back_link = base_url()."group/orderbook";

$user = $users[$owner];
$owner_name = $user->name;

?>

<div style="text-align: right">
    <a href="<?=$back_link?>">Back to Orders</a>
</div>

<div class="tabbable" id="info_navtab" style="margin-left: 15px; margin-right: 15px;"> <!-- Only required for left/right tabs -->
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab">Order ID: <?=$order_id?></a></li>
    <div style="text-align:left; vertical-align: middle; margin-top:5px;">
	<?
	if($owner == $user_id || $role == 'admin' || $role == 'buyer') {
	    echo '<a href="'.$edit_link.'" class="btn btn-success btn-small" style="margin-left:15px">Edit</a>';
	}
	if($role == 'admin' || $role == 'buyer') {
	    echo '<a href="'.$remove_link.'" class="btn btn-danger btn-small" style="margin-left:10px">Delete Order</a>';
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
	    <td class="order_label">Status</td>
	    <td><span class="label label-info"><?=strtoupper($status)?></span></td>
	    <td class="order_label">Placed By :</td>
	    <td><span class="label label-info"><?=$owner_name?></span></td>
	</tr>
	<tr>
	    <td class="order_label">Company :</td>
	    <td><?=$company?></td>
	    <td class="order_label">PO # :</td>
	    <td><?=$ponum?></td>
	    <td class="order_label">Confirmation # :</td>
	    <td><?=$conum?></td>
	</tr>
	<tr>
	    <td class="order_label">Priority :</td>
	    <td><?=$priority?></td>
	    <td class="order_label">Date :</td>
	    <td><?=$status_date?></td>
	    <td class="order_label">Account # :</td>
	    <td><?=$account?></td>
	</tr>
	<tr>
	    <td class="order_label">Group Expense :</td>
	    <td><?=$g_expense?></td>
	    <td class="order_label"">Personal Expense :</td>
	    <td><?=$p_expense?></td>
	    <td class="order_label">Total :</td>
	    <td><?=$total?></td>
	</tr>
	<tr>
	    <td class="order_label">Order Notes </td>
	    <td colspan="5"><?=$notes?></td>
	</tr>
    </table>
	<span style="color:#0088CC; margin-bottom: 3px;">Order Items</span>
    <table class="order_table">
	<thead>
	    <th>Item #</th>
	    <th>Type</th>
	    <th>Company</th>
	    <th>Product ID</th>
	    <th>Description</th>
	    <th>Units</th>
	    <th>Cost</th>
	    <th>Group Item</th>
	    <th>Status</th>
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
		    $amount  = trim($info[4]);
		    $units = trim($info[5]);
		    $price = '$'.sprintf("%01.2f", $info[6]);
		    $owner = trim($info[7]);
		    $istatus = trim($info[8]);
		    $stock_id = trim($info[9]);

		    echo '<tr>';
		    echo '<td>'.$i.'</td>';
		    echo '<td>'.$type.'</td>';
		    echo '<td>'.$company.'</td>';
		    echo '<td>'.$product.'</td>';
		    echo '<td>'.$description.'</td>';
		    echo '<td>'.$amount.'x '.$units.'</td>';
		    echo '<td>'.$price.'</td>';
		    if($owner != 'admin') {
			echo '<td>No</td>';
		    }
		    else {
			echo '<td>Yes</td>';
		    }
		    echo '<td>'.ucfirst($istatus);

		    // insert a link to edit the database entry
		    if($istatus == 'received' && $type != 'Other') {
			$editdb_link = '';
			if($type == 'Chemical') {
			    $editdb_link = base_url()."chemicals/edit?chem_id=$stock_id";
			}
			else if($type == 'Supply') {
			    $editdb_link = base_url()."supplies/edit?item_id=$stock_id";
			}
			echo '<a href="'.$editdb_link.'" target="_blank" class="btn btn-success btn-mini">edit</a>';
		    }
		    echo '</td></tr>';
		}
	    }
	    ?>
	</tbody>
    </table>
	
    </div>
</div>

