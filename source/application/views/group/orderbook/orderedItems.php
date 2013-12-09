<?php

$update_link = base_url()."group/orderbook/updateStatus";
echo '<form name="form1" action="'.$update_link.'" method="post">';
echo '<input type="hidden" name="task" value="orderbook_update2">'; // must change this

echo '<div style="text-align: right;">';
echo 'Status Code :: <b><span style="color: rgb(0, 0, 235);">R</span></b> = Received ||
<b><span style="color: rgb(0, 0, 235);">C</span></b> = Itemed Canceled ';

$back_link = base_url()."group/orderbook"; // link to go back to the orders
echo '[ <a href="'.$back_link.'">Back to Orders</a> ]</div><br>';

// add the table that displays the ordered items
echo '<table style="background-color: rgb(245, 245, 245);; text-align: left; width: 100%;" border="1" cellpadding="2"
cellspacing="0"><tbody>';

echo '<tr>';
echo '<td style="vertical-align: top; text-align: center;"><small><b>Order_ID</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Ordered_By</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Placed_By</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Company</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Product ID</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Description</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Units</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Cost</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Date</b></small></td>';
echo '<td style="vertical-align: top; text-align: center;">
<small><b><span style="color: rgb(0, 0, 235);">R</span></b></small></td>'; // received
echo '<td style="vertical-align: top; text-align: center;">
<small><b><span style="color: rgb(0, 0, 235);">C</span></b></small></td>'; // cancelled
echo '</tr>';

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

  echo '<tr>';
  echo '<td style="vertical-align: top; text-align: center;"><small>
  [ <a href="'.$order_link.'">'.$order_id.'</a> ]</small></td>';
  echo '<td style="vertical-align: top;"><small>'.$owner.'</small></td>';
  echo '<td style="vertical-align: top;"><small>'.$name.'</small></td>';
  echo '<td style="vertical-align: top;"><small>'.$company.'</small></td>';
  echo '<td style="vertical-align: top;"><small>'.$product.'</small></td>';
  echo '<td style="vertical-align: top;"><small>'.$description.'</small></td>';
  echo '<td style="vertical-align: top;"><small>'.$amount.'x '.$units.'</small></td>';
  echo '<td style="vertical-align: top;"><small>'.$price.'</small></td>';
  echo '<td style="vertical-align: top;"><small>'.$date.'</small></td>';
  echo '<td style="vertical-align: top; text-align: center;">
  <input type="radio" name="status_'.$id.'" value="received"></td>';
  echo '<td style="vertical-align: top; text-align: center;">
  <input type="radio" name="status_'.$id.'" value="cancelled"></td>';
  echo '</tr>';
}

echo '</tbody></table>';
echo '<div style="text-align: right;">
<input type="submit" value="Update Status" 
style="background: '.$this->b_color.'; color: '.$this->title_color.'"> ';
echo '<input type="reset" value="Reset!" 
style="background: '.$this->b_color.'; color: '.$this->title_color.'"></div>';
echo '</form>';

