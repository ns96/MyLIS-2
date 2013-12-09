<?php

$update_link = base_url()."group/orderbook/updateStatus";
echo '<form name="form1" action="'.$update_link.'" method="post">';
echo '<input type="hidden" name="task" value="orderbook_update">'; // must change this

$back_link = base_url()."group/orderbook"; // link to go back to the orders

echo '<div style="text-align: right;">';

if($role == 'admin' || $role == 'buyer') {
  echo 'Status Code :: <b><span style="color: rgb(0, 0, 235);">O</span></b> = Ordered ||
  <b><span style="color: rgb(0, 0, 235);">BO</span></b> = Back Ordered || 
  <b><span style="color: rgb(0, 0, 235);">C</span></b> = Cancel Item ';
} 

echo '[ <a href="'.$back_link.'">Back to Orders</a> ]</div><br>';


// add the table that allows inputing of items
echo '<table style="background-color: rgb(245, 245, 245); text-align: left; width: 100%;" border="1" cellpadding="2"
cellspacing="0"><tbody>';

echo '<tr>';
echo '<td style="vertical-align: top; text-align: center;"><small><b>Order ID</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Placed By</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Company</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Product ID</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Description</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Units</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Cost</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Date</b></small></td>';
if($role == 'admin' || $role == 'buyer') {
  echo '<td style="vertical-align: top; text-align: center;">
  <small><b><span style="color: rgb(0, 0, 235);">O</span></b></small></td>'; // ordered
  echo '<td style="vertical-align: top; text-align: center;">
  <small><b><span style="color: rgb(0, 0, 235);">BO</span></b></small></td>';
  echo '<td style="vertical-align: top; text-align: center;">
  <small><b><span style="color: rgb(0, 0, 235);">C</span></b></small></td>';
}
echo '</tr>';

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

  echo '<tr>';
  echo '<td style="vertical-align: top; text-align: center;">
  <small>[ <a href="'.$order_link.'">'.$order_id.'</a> ]</small></td>';
  echo '<td style="vertical-align: top;"><small>'.$name.'</small></td>';
  echo '<td style="vertical-align: top;"><small>'.$company.'</small></td>';
  echo '<td style="vertical-align: top;"><small>'.$product.'</small></td>';
  echo '<td style="vertical-align: top;"><small>'.$description.'</small></td>';
  echo '<td style="vertical-align: top;"><small>'.$amount.'x '.$units.'</small></td>';
  echo '<td style="vertical-align: top;">
  <input type="text" name="price_'.$id.'" size="7" value="'.$price.'" ></td>';
  echo '<td style="vertical-align: top;"><small>'.$date.'</small></td>';

  if($role == 'admin' || $role == 'buyer') {
    echo '<td style="vertical-align: top; text-align: center;">
    <input type="radio" name="status_'.$id.'" value="ordered"></td>';
    echo '<td style="vertical-align: top; text-align: center;">
    <input type="radio" name="status_'.$id.'" value="back ordered"></td>';
    echo '<td style="vertical-align: top; text-align: center;">
    <input type="radio" name="status_'.$id.'" value="cancelled"></td>';
  }

  echo '</tr>';
}

echo '</tbody></table>';
if($role == 'admin' || $role == 'buyer') {
  echo '<div style="text-align: right;">
  <input type="submit" value="Update Status" 
  style="background: rgb(238, 238, 238); color: #3366FF"> ';
  echo '<input type="reset" value="Reset!" 
  style="background: rgb(238, 238, 238); color: #3366FF"></div>';
}
echo '</form>';
