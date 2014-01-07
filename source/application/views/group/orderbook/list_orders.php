<?php 
    if ($order_page == 'mine')
	$dated_orders_link = base_url()."group/orderbook/orders_mine";
    else
	$dated_orders_link = base_url()."group/orderbook/orders_all";
    $back_link = base_url()."group/orderbook"; // link to go back to the orders
?>
<div style="text-align: right; display: block-inline; float:right">
<form action="<?=$dated_orders_link?>" method="POST" class="form-inline" style="margin-right:10px">
    <input type="hidden" name="task" value="orderbook_orders">    
    <table>
	<tr>
	    <td>
		<?
		if($page == 'admin') {
		    echo '<input type="hidden" name="page" value="admin">'; // needed to redisplay correct page

		    echo 'Orders By : ';
		    echo '<select name="ordered_by">';
		    echo '<option value="all">ALL</option>';
		    foreach($users as $user) {
			echo "<option value=\"$user->userid\">$user->name</option>";
		    }
		    echo '</select> ';
		}
		?>
		<label for="year" class="control-label">Year :</label>
		<select name="year" class="input-small">
		    <option value="<?=$year?>"><?=$year?></option>
		    <option value="all">ALL</option>
		    <option value="2006">2006</option>
		    <option value="2007">2007</option>
		    <option value="2008">2008</option>
		    <option value="2009">2009</option>
		    <option value="2010">2010</option>
		    <option value="2011">2011</option>
		    <option value="2012">2012</option>
		    <option value="2013">2013</option>
		    <option value="2014">2014</option>
		    <option value="2015">2015</option>
		    <option value="2016">2016</option>
		    <option value="2017">2017</option>
		    <option value="2018">2018</option>
		</select>
		<label for="notes" class="control-label" style="margin-left: 15px">Month :</label>
		<select name="month" class="input-smallmedium">
		    <option value="all">ALL</option>
		    <? foreach($months as $num => $name) {
			echo "<option value=\"$num\">$name</option>";
		    } ?>
		</select>
	    </td>
	    <td style="text-align: left; padding-left: 15px">
		<button type="submit" class="btn btn-primary btn-small">Display Orders</button>
	    </td>
	</tr>
    </table>
</form>
</div>
<div style="clear: both"></div>
<?

$count  = count($orders);

if($count == 0) {
  echo 'No orders found...';
}
else { // display the results
    
    $view_orders_link = base_url()."group/orderbook/order_multiple_info";
    ?>
    <div style="text-align: left; margin-left: 15px;">
	<small>
	    Orders Found : <b><span style="color: rgb(0, 0, 235);"><?=($count - 1)?></span></b> || 
	    Total Expense : <b><span style="color: rgb(235, 0, 0);"><?=$orders['total']?></span></b>
	</small>
    </div>
    <?
    // see any of the accounts need to be total separately and print those out
	if(sizeof($tally_totals) > 0) {
	echo '|| <small>Account Totals : ';
	foreach($tally_totals as $account => $total) {
	    echo '{'.$account.' = <b><span style="color: rgb(235, 0, 0);">$'.$total.'</span></b>} ';
	}
	echo '</small>';
	}
    ?>
    <form action="<?=$view_orders_link?>" method="POST" class="form-inline">
	<input type="hidden" name="task" value="orderbook_info2">     
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
    <?
}


