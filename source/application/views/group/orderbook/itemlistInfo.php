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

echo '<table style="background-color: rgb(245, 245, 245); text-align: left; width: 100%;" border="1" cellpadding="2"
cellspacing="2"><tbody>';

echo '<tr>';
echo '<td colspan="4" rowspan="1" style="vertical-align: top;">
Current Item List ID : <b><span style="color: rgb(235, 0, 0);">'.$order_id.'</span></b>';

$user = $users[$owner];
$owner_name = $user->name;
echo ' || Created By : <b><span style="color: rgb(0, 0, 235);">'.$owner_name.'</span></b>';

if($owner == $user_id || $role == 'admin' || $role == 'buyer') {
  echo ' [ <a href="'.$edit_link.'">Edit</a> ] ';
  echo ' [ <a href="'.$remove_link.'">Delete Item List</a> ] ';
}

echo ' [ <a href="'.$back_link.'">Back to Orders</a> ]</td></tr>';

echo '<tr>';
echo '<td style="vertical-align: top;">Company : <b>'.$company.'</b></td>';
echo '<td style="vertical-align: top;">Max # of Items : <b>'.$maxitems.'</b></td>';
echo '<td style="vertical-align: top;">Shared with Group : <b>'.$priority.'</b></td>';
echo '<td style="vertical-align: top;">Date Last Modified: <b>'.$status_date.'</b></td>';
echo '</tr>';

echo '<td style="vertical-align: top;"><span style="color: rgb(235, 0, 0);"><b>Order Notes :</b></span></td>';
echo '<td colspan="3" rowspan="1" style="vertical-align: top;">'.$notes.'</td>';
echo '</tr>';

echo '</tbody></table>';

// print out the items ordered in the next table
echo '<table style="background-color: rgb(245, 245, 245); text-align: left; width: 100%;" border="1" cellpadding="2"
cellspacing="2"><tbody>';

echo '<tr>';
echo '<td style="vertical-align: top;"><small><b>Item #</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Type</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Company</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Product ID</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Description</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Units</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Cost</b></small></td>';
echo '</tr>';

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
    echo '<td style="vertical-align: top;"><small>'.$item_id.'</small></td>';
    echo '<td style="vertical-align: top;"><small>'.$type.'</small></td>';
    echo '<td style="vertical-align: top;"><small>'.$company.'</small></td>';
    echo '<td style="vertical-align: top;"><small>'.$product.'</small></td>';
    echo '<td style="vertical-align: top;"><small>'.$description.'</small></td>';
    echo '<td style="vertical-align: top;"><small>'.$units.'</small></td>';
    echo '<td style="vertical-align: top;"><small>'.$price.'</small></td>';
  }
}

echo '</tbody></table>';



