<?php

// initilzie some links
$orderbook_link = base_url().'group/orderbook'; // go back to the main page
$search_target = base_url().'group/orderbook/search';

echo '<table style="text-align: left; width: 100%;" border="0" 
cellpadding="2" cellspacing="2">';
echo '<tr><td style="vertical-align: top;">';
echo '<b><span style="color: #3366FF;">Search Results</span></b> ';
echo '[ <a href="'.$orderbook_link.'">Back to Orders</a> ]<td>';
echo '<td style="text-align: right; vertical-align: buttom;">';

echo '<form name="search_form" action="'.$search_target.'" method="post">';
echo '<input type="hidden" name="search_form" value="posted">';
echo '<input type="hidden" name="order_id" value="'.$order_id.'">';
echo '<input type="checkbox" name="myorders" value="yes" checked="checked"> ';
echo 'My orders ';
echo '<input type="text" name="searchfor" size="35" value="'.$searchfor.'"> ';
echo ' <input type="submit" value="Search" 
style="background: rgb(238, 238, 238); color: #3366FF">';
echo '</form>';
    
echo '</td></tr></tbody></table>';

echo printColoredLine('#3366FF', '2px').'<pre></pre>';

// display tables holding the orders and items that were found
$count1 = count($orders);
$count2 = count($items);

$target_infolink = base_url().'group/orderbook/order_multiple_info';

// display orders
if($count1 > 0) {
    $count  = count($orders);
    
    echo '<form name="form2" action="'.$target_infolink.'" method="post">';
    echo '<input type="hidden" name="task" value="orderbook_info2">';
    /*remove one from count to account for the orders['total'] variable*/
    echo '<small>Orders Found : <b><span style="color: rgb(0, 0, 235);">'.($count - 1).'</span></b> || 
    Total Expense : <b><span style="color: rgb(235, 0, 0);">'.$orders['total'].'</span></b></small> ';
    
    // see any of the accounts need to be total separately and print those out
    if(sizeof($tally_totals) > 0) {
      echo '|| <small>Account Totals : ';
      foreach($tally_totals as $account => $total) {
        echo '{'.$account.' = <b><span style="color: rgb(235, 0, 0);">$'.$total.'</span></b>} ';
      }
      echo '</small>';
    }
    
    echo '<table style="background-color: rgb(245, 245, 245); text-align: left; width: 100%;" border="1" cellpadding="2"
    cellspacing="0"><tbody>';
    
    echo '<tr>';
    echo '<td style="vertical-align: top; text-align: center;"><small><b>Order ID</b></small></td>';
    echo '<td style="vertical-align: top;"><small><b>Placed By</b></small></td>';
    echo '<td style="vertical-align: top;"><small><b>Company</b></small></td>';
    echo '<td style="vertical-align: top;"><small><b>Account</b></small></td>';
    echo '<td style="vertical-align: top;"><small><b>Status</b></small></td>';
    echo '<td style="vertical-align: top;"><small><b>Date</b></small></td>';
    echo '<td style="vertical-align: top;"><small><b>Total</b></small></td>';
    echo '</tr>';
    
    foreach($orders as $order_id => $order) {
      if($order_id == 'total') { 
        continue;
      }
      
      $info = preg_split("/\t/", $order);
      $user = $users[trim($info[0])];
      $ordered_by = $user->name;
      $company = trim($info[1]);
      $status = trim($info[2]);
      $date = trim($info[3]);
      $total = trim($info[4]);
      $account = trim($info[6]);
      
      $order_link = base_url()."group/orderbook/order_info?order_id=$order_id";
      
      echo '<tr>';
      echo '<td style="vertical-align: top; text-align: center;"><small>
      <input type="checkbox" name="order_ids[]" value="'.$order_id.'"> 
      [ <a href="'.$order_link.'">'.$order_id.'</a> ]</small></td>';
      echo '<td style="vertical-align: top;"><small>'.$ordered_by.'</small></td>';
      echo '<td style="vertical-align: top;"><small>'.$company.'</small></td>';
      echo '<td style="vertical-align: top;"><small>'.$account.'</small></td>';
      echo '<td style="vertical-align: top;"><small>'.$status.'</small></td>';
      echo '<td style="vertical-align: top;"><small>'.$date.'</small></td>';
      echo '<td style="vertical-align: top;"><small><span style="color: rgb(235, 0, 0);">'.$total.'</span></small></td>';
      echo '</tr>';
    }
    
    echo '</tbody></table>';
    
    echo '<div style="text-align: right;">';
    echo '<input type="submit" value="View Selected" 
    style="background: rgb(238, 238, 238); color: #3366FF">';
    echo '</div></form>';
}
else {
  echo 'No orders found matching search term ...<br>';
}

// display items
if($count2 > 0) {
    $count  = count($items);
    
    // some java script code to add the ability to quickly add items to an item list
    echo '<script language="Javascript">
    <!--Hide script from older browsers
    // function to save items to list
    function saveItemsToList() {
      document.forms.form3.task.value = "orderbook_itemlist_saveitems";
      document.forms.form3.submit();
    }
    
    // End hiding script from older browsers-->              
    </script>';
    //--end of javascript code
    
    echo '<form name="form3" action="'.$target_infolink.'" method="post">';
    echo '<input type="hidden" name="task" value="orderbook_info2">';
    echo '<small>Items Found : <b><span style="color: rgb(0, 0, 235);">'.($count).'</span></b></small>';
    
    echo '<table style="background-color: rgb(245, 245, 245); text-align: left; width: 100%;" border="1" cellpadding="2"
    cellspacing="0"><tbody>';
    
    echo '<tr>';
    echo '<td style="vertical-align: top; text-align: center;"><small><b>Order ID</b></small></td>';
    //echo '<td style="vertical-align: top;"><small><b>Placed By</b></small></td>';
    echo '<td style="vertical-align: top;"><small><b>Company</b></small></td>';
    echo '<td style="vertical-align: top;"><small><b>Description</b></small></td>';
    echo '<td style="vertical-align: top;"><small><b>Status</b></small></td>';
    echo '<td style="vertical-align: top;"><small><b>Date</b></small></td>';
    echo '<td style="vertical-align: top;"><small><b>Cost</b></small></td>';
    echo '</tr>';
    
    foreach($items as $item_id => $item) {
      $info = preg_split("/\t/", $item);
      $order_id = trim($info[1]);
      $user = $users[trim($info[5])];
      $ordered_by = $user->name;
      $company = trim($info[2]);
      $description = trim($info[4]);
      $status = trim($info[6]);
      $date = trim($info[7]);
      $price = trim($info[8]);
      
      $order_link = base_url()."group/orderbook/order_info?order_id=$order_id";
      
      echo '<tr>';
      echo '<td style="vertical-align: top; text-align: center;"><small>
      <input type="checkbox" name="order_ids[]" value="'.$order_id.'_'.$item_id.'"> 
      [ <a href="'.$order_link.'">'.$order_id.'</a> ]</small></td>';
      //echo '<td style="vertical-align: top;"><small>'.$ordered_by.'<br></small></td>';
      echo '<td style="vertical-align: top;"><small>'.$company.'</small></td>';
      echo '<td style="vertical-align: top;"><small>'.$description.'</small></td>';
      echo '<td style="vertical-align: top;"><small>'.$status.'</small></td>';
      echo '<td style="vertical-align: top;"><small>'.$date.'</small></td>';
      echo '<td style="vertical-align: top;"><small><span style="color: rgb(235, 0, 0);">'.$price.'</span></small></td>';
      echo '</tr>';
    }
    
    echo '</tbody></table>';
    
    echo '<div style="text-align: right;">';
    
    echo '<input type="button" name="itemsSave" value="Save Items to List" 
    style="background: rgb(238, 238, 238); color: #3366FF" 
    onclick="saveItemsToList()"/> ';
    
    echo '<input type="submit" value="View Selected Orders" 
    style="background: rgb(238, 238, 238); color: #3366FF">';
    echo '</div></form>';
}
else {
  echo 'No items found matching search term ...<br>';
}


