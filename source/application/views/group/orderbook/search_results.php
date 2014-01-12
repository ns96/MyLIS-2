<?php
    $orderbook_link = base_url().'group/orderbook'; // go back to the main page
    $search_target = base_url().'group/orderbook/search';
?>
<div style="margin:0px 15px">
<table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2">
    <tr>
	<td style="text-align: left">
	    <a href="<?=$orderbook_link?>">Back to Orders</a>
	</td>
	<td style="text-align: right; vertical-align: top;">
	    <!------- SEARCH FORM ---------->
	    <form name="search_form" action="<?=$search_target?>" method="post" class="form-inline">
		<input type="hidden" name="search_form" value="posted">
		<input type="hidden" name="order_id" value="<?=$order_id?>">
		<input type="checkbox" name="myorders" value="yes" checked="checked">
		My orders 
		<input type="text" name="searchfor" value="<?=$searchfor?>" class="input-block-level input-medium"> 
		<button type="submit" class="btn btn-primary btn-small">Search</button>
	    </form>
	</td>
    </tr>
</table>
</div>
<?
// display tables holding the orders and items that were found
$count1 = count($orders);
$count2 = count($items);
$target_infolink = base_url().'group/orderbook/order_multiple_info';

// display orders
if($count1 > 0) {
    ?>
    <form name="form2" action="<?=$target_infolink?>" method="post">
    <input type="hidden" name="task" value="orderbook_info2">
    
    <div style="text-align: left; margin-bottom: 5px">
	<small>
	    Orders Found : <b><span style="color: rgb(0, 0, 235);"><?=($count1 - 1)?></span></b> || 
	    Total Expense : <b><span style="color: rgb(235, 0, 0);"><?=$orders['total']?></span></b>
	</small>
    </div>
    
    <!-- see any of the accounts need to be total separately and print those out -->
    <? if(sizeof($tally_totals) > 0) {
      echo '|| <small>Account Totals : ';
      foreach($tally_totals as $account => $total) {
        echo '{'.$account.' = <b><span style="color: rgb(235, 0, 0);">$'.$total.'</span></b>} ';
      }
      echo '</small>';
    } ?>
    
    <table class="table table-bordered table-condensed" id="orderlist_table">
	<thead>
	    <th>Order ID</th>
	    <th>Placed By</th>
	    <th>Company</th>
	    <th>Account</th>
	    <th>Status</th>
	    <th>Date</th>
	    <th>Total</th>
	</thead>
	<tbody>
    <?
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
	?>
	<tr>
	    <td>
		<input type="checkbox" name="order_ids[]" value="<?=$order_id?>"> 
		&nbsp;&nbsp;&nbsp;<a href="<?=$order_link?>"><?=$order_id?></a>
	    </td>
	    <td><?=$ordered_by?></td>
	    <td><?=$company?></td>
	    <td><?=$account?></td>
	    <td><?=$status?></td>
	    <td><?=$date?></td>
	    <td><span style="color: rgb(235, 0, 0);"><?=$total?></span></td>
	</tr>
	<?
    }
    ?>
	</tbody>
    </table>
    <div style="text-align: right">
	<button type="submit" class="btn btn-primary btn-small">View Selected</button>
    </div>
    </form>
<?}
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
    ?>
    <form name="form3" action="<?=$target_infolink?>" method="post">
	<input type="hidden" name="task" value="orderbook_info2">

	<div style="text-align: left; margin-bottom: 5px">
	    <small>
		Items Found : <b><span style="color: rgb(0, 0, 235);"><?=($count2)?></span></b>
	    </small>
	</div>

	<!-- see any of the accounts need to be total separately and print those out -->
	<? if(sizeof($tally_totals) > 0) {
	echo '|| <small>Account Totals : ';
	foreach($tally_totals as $account => $total) {
	    echo '{'.$account.' = <b><span style="color: rgb(235, 0, 0);">$'.$total.'</span></b>} ';
	}
	echo '</small>';
	} ?>

	<table class="table table-bordered table-condensed" id="orderlist_table">
	    <thead>
		<th>Order ID</th>
		<th>Company</th>
		<th>Description</th>
		<th>Status</th>
		<th>Date</th>
		<th>Cost</th>
	    </thead>
	    <tbody>
    <?
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
      ?>
	<tr>
	    <td>
		<input type="checkbox" name="order_ids[]" value="<?=$order_id.'_'.$item_id?>"> 
		&nbsp;&nbsp;&nbsp;<a href="<?=$order_link?>"><?=$order_id?></a>
	    </td>
	    <td><?=$company?></td>
	    <td><?=$description?></td>
	    <td><?=$status?></td>
	    <td><?=$date?></td>
	    <td><span style="color: rgb(235, 0, 0);"><?=$price?></span></td>
	</tr>
      <?
    }
    ?>
	</tbody>
    </table>
    <div style="text-align: right">
	<button type="submit" name="itemsSave" onclick="saveItemsToList()" class="btn btn-small">Save Items to List</button>
	<button type="submit" class="btn btn-primary btn-small">View Selected Orders</button>
    </div>
    </form>
<?
}
else {
  echo 'No items found matching search term ...<br>';
}


