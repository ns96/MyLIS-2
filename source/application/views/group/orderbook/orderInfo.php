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

echo '<table style="background-color: rgb(245, 245, 245); text-align: left; width: 100%;" border="1" cellpadding="2"
cellspacing="2"><tbody>';

echo '<tr>';
echo '<td colspan="5" rowspan="1" style="vertical-align: top;">
<small>Current Order ID : <b><span style="color: rgb(235, 0, 0);">'.$order_id.'</span></b>';
echo ' || Status : <b><span style="color: rgb(235, 0, 0);">'.strtoupper($status).'</span></b>';

$user = $users[$owner];
$owner_name = $user->name;
echo ' || Placed By : <b><span style="color: rgb(0, 0, 235);">'.$owner_name.'</span></b>';

if($owner == $user_id || $role == 'admin' || $role == 'buyer') {
  echo ' [ <a href="'.$edit_link.'">Edit</a> ] ';
}

if($role == 'admin' || $role == 'buyer') {
  echo ' [ <a href="'.$remove_link.'">Delete Order</a> ] ';
}

echo ' [ <a href="'.$back_link.'">Back to Orders</a> ]</small></td></tr>';

echo '<tr>';
echo '<td style="vertical-align: top;">Company : <small><b>'.$company.'</b></small></td>';
echo '<td style="vertical-align: top;">PO # : <small><b>'.$ponum.'</b></small></td>';
echo '<td style="vertical-align: top;">Confirmation # : <small><b>'.$conum.'</b></small></td>';
echo '<td style="vertical-align: top;">Priority : <small><b>'.$priority.'</b></small></td>';
echo '<td style="vertical-align: top;">Date : <small><b>'.$status_date.'</b></small></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top;">Account # : <small><b>'.$account.'</b></small></td>';
echo '<td style="vertical-align: top;">Group Expense : <small><b>'.$g_expense.'</b></small></td>';
echo '<td style="vertical-align: top;">Personal Expense : <small><b>'.$p_expense.'</b></small></td>';
echo '<td style="vertical-align: top;">S & H : <small><b>'.$s_expense.'</b></small></td>';
echo '<td style="vertical-align: top;">Total : <small><b><span style="color: rgb(235, 0, 0);">'.$total.'</span></b></small></td>';
echo '</tr>';

echo '<td style="vertical-align: top;"><span style="color: rgb(235, 0, 0);"><small><b>Order Notes :</b></small></span></td>';
echo '<td colspan="4" rowspan="1" style="vertical-align: top;"><pre>'.$notes.'</pre></td>';
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
echo '<td style="vertical-align: top;"><small><b>Group Item</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Status</b></small></td>';
echo '</tr>';

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
    echo '<td style="vertical-align: top;"><small>'.$i.'</small></td>';
    echo '<td style="vertical-align: top;"><small>'.$type.'</small></td>';
    echo '<td style="vertical-align: top;"><small>'.$company.'</small></td>';
    echo '<td style="vertical-align: top;"><small>'.$product.'</small></td>';
    echo '<td style="vertical-align: top;"><small>'.$description.'</small></td>';
    echo '<td style="vertical-align: top;"><small>'.$amount.'x '.$units.'</small></td>';
    echo '<td style="vertical-align: top;"><small>'.$price.'</small></td>';
    if($owner != 'admin') {
      echo '<td style="vertical-align: top;"><small>No</small></td>';
    }
    else {
      echo '<td style="vertical-align: top;"><small>Yes</small></td>';
    }
    echo '<td style="vertical-align: top;"><small>'.ucfirst($istatus);

    // insert a link to edit the database entry
    if($istatus == 'received' && $type != 'Other') {
      $editdb_link = $this->getEditDBLink($type, $stock_id);
      echo ' [ <a href="'.$editdb_link.'" target="_blank">edit</a> ]';
    }
    echo '</small></td>';
  }
}

echo '</tbody></table>';
