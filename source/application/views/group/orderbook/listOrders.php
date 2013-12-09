<?php

$dated_orders_link = base_url()."group/orderbook/orders_mine";
echo '<form name="form1" action="'.$dated_orders_link.'" method="post">';
echo '<input type="hidden" name="task" value="orderbook_orders">'; // must change this

// the first table
echo '<table style="width: 100%; text-align: left;" border="0" cellpadding="2"
cellspacing="2"><tbody>';

echo '<tr>';
echo '<td style="vertical-align: top;">';

if($page == 'admin') {
  echo '<input type="hidden" name="page" value="admin">'; // needed to redisplay correct page

  echo '<small><b>Orders By : ';
  echo '<select name="ordered_by" size="1">';
  echo '<option value="all">ALL</option>';
  foreach($users as $user) {
    echo "<option value=\"$user->userid\">$user->name</option>";
  }
  echo '</select> ';
}

echo 'Year : <select name="year" size="1">';
echo '<option value="'.$year.'">'.$year.'</option>';
echo '<option value="all">ALL</option>';
echo '<option value="2006">2006</option>';
echo '<option value="2007">2007</option>';
echo '<option value="2008">2008</option>';
echo '<option value="2009">2009</option>';
echo '<option value="2010">2010</option>';
echo '<option value="2011">2011</option>';
echo '<option value="2012">2012</option>';
echo '<option value="2013">2013</option>';
echo '<option value="2014">2014</option>';
echo '<option value="2015">2015</option>';
echo '<option value="2016">2016</option>';
echo '<option value="2017">2017</option>';
echo '<option value="2018">2018</option>';
echo '</select> ';

echo 'Month : <select name="month" size="1">';
echo '<option value="all">ALL</option>';
foreach($months as $num => $name) {
  echo "<option value=\"$num\">$name</option>";
}
echo '</select> <input type="submit" value="Display Orders" 
style="background: rgb(238, 238, 238); color: #3366FF"></b></small></td>';

$back_link = base_url()."group/orderbook"; // link to go back to the orders
echo '<td style="vertical-align: top; text-align: right;">
[ <a href="'.$back_link.'">Back to Orders</a> ]</td></tr>';
echo '</tbody></table>';

echo '</form>';
$count  = count($orders);

if($count == 0) {
  echo 'No orders found...';
}
else { // display the results
    $count  = count($orders);
    
    $view_orders_link = base_url()."group/orderbook/order_multiple_info";
    echo '<form name="form2" action="'.$view_orders_link.'" method="post">';
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


