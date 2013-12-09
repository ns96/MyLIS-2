<?php

$back_link = base_url()."group/orderbook"; // go back to the orders
$target_link = base_url()."group/orderbook/itemlist_multiple_info";

// get an array of company item lists. basically orders where the status is itemlist
$count = count($orders);

echo '<form name="form1" action="'.$target_link.'" method="post">';
echo '<input type="hidden" name="task" value="orderbook_itemlist_mview">';

// the first table
echo '<table style="width: 100%; text-align: left;" border="0" cellpadding="2"
cellspacing="2"><tbody>';

echo '<tr>';
echo '<td style="vertical-align: top;">';
if($count > 0) {
  //echo 'Company Item Lists Found : <b><span style="color: rgb(0, 0, 235);">'.($count - 1).'</span></b></td>';
} else {
  echo 'No item list found ...</td>';
}

echo '<td style="vertical-align: top; text-align: right;">
[ <a href="'.$back_link.'">Back to Orders</a> ]</td></tr>';
echo '</tbody></table>';

// add the table that hold the list of orders now
echo '<table style="background-color: rgb(245, 245, 245); text-align: left; width: 100%;" border="1" cellpadding="2"
cellspacing="0"><tbody>';

echo '<tr>';
echo '<td style="vertical-align: top; text-align: center;"><small><b>Item List ID</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Created By</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Company</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Shared With Group?</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Date Last Modified</b></small></td>';
echo '</tr>';

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
    echo '<td style="vertical-align: center; text-align: center;"><small>
    <input type="checkbox" name="order_ids[]" value="'.$order_id.'"> 
    [ <a href="'.$order_link.'">'.$order_id.'</a> ] ';
    if($role == 'admin' || $role == 'buyer' || $user_id == trim($info[0])) {
      echo '[ <a href="'.$edit_link.'">Edit</a> ] ';
    }
    echo '</small></td>'; 
    echo '<td style="vertical-align: center;"><small>'.$ordered_by.'</small></td>';
    echo '<td style="vertical-align: center;"><small>'.$company.'</small></td>';
    echo '<td style="vertical-align: center;"><small>'.$shared.'</small></td>';
    echo '<td style="vertical-align: center;"><small>'.$date.'</small></td>';
    echo '</tr>';
  }
}

echo '</tbody></table>';

echo '<div style="text-align: right;">';
echo '<input type="submit" value="View Selected" 
style="background: rgb(238, 238, 238); color: #3366FF">';
echo '</div></form>';


