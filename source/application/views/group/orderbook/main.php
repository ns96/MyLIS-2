<?php

// initialize some links
$neworder_link = base_url()."group/orderbook"; // this will add a new orderbook link
$newitemlist_link = base_url()."group/orderbook/itemlist"; // add a new item list
$viewitemlist_link = base_url()."group/orderbook/itemlist_all"; // view the company item lists
$myorderbook_link = base_url()."group/orderbook/orders_mine"; // list all orderbook I place
$pending_link = base_url()."group/orderbook/items_pending"; // view pending orderbook
$ordered_items_link = base_url()."group/orderbook/items_ordered"; // view ordered items
$orderbook_link = base_url()."group/orderbook/orders_all"; // list all orderbook
$manage_link = base_url()."group/orderbook/orders/manage"; // manager the orderbook
$home_link = base_url()."group/orderbook";

$year = date('Y');

echo $jsCode;
$icount = count($pendingItems);
$oicount = count($orderedItems);
$search_target = base_url()."group/orderbook/search";

?>
<table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2">
    <tr>
	<td style="text-align: left">
	    <!------- NAVIGATION LINKS ---------->
	    <div class="btn-group" style="margin-left: 20px">
		<a class="btn dropdown-toggle btn-small" data-toggle="dropdown" href="#">
		    Navigate To : 
		    <span class="caret"></span>
		</a>
		<ul class="dropdown-menu">
		    <!-- dropdown menu links -->
		    <li><a href="<?=$neworder_link?>">New Order</a></li>
		    <li><a href="<?=$newitemlist_link?>">New Item List</a></li>
		    <li><a href="<?=$myorderbook_link?>">My Orders</a></li>
		    <li><a href="<?=$viewitemlist_link?>">Item Lists</a></li>
		    <li><a href="<?=$pending_link?>">Pending Items (<?=$icount?>)</a></li>
		    <li><a href="<?=$ordered_items_link?>">Ordered Items (<?=$oicount?>)</a></li>
		    <?
		    if($role == 'admin' || $role == 'buyer') {
			echo '<li><a href="'.$orderbook_link.'">All Orders</a></li>';
		    }
		    ?>
		</ul>
	    </div>
	</td>
	<td style="text-align: right; vertical-align: top;">
	    <!------- SEARCH FORM ---------->
	    <form name="search_form" action="<?=$search_target?>" method="post" class="form-inline">
		<input type="hidden" name="task" value="orderbook_search">
		<input type="hidden" name="order_id" value="<?=$order_id?>'">
		<input type="checkbox" name="myorders" value="yes" checked="checked"> 
		My orders 
		<input type="text" name="searchfor" size="35" value="" class="input-block-level input-medium"> 
		<button type="submit" class="btn btn-primary btn-small">Search</button>
	    </form>
	</td>
    </tr>
</table>
<?
// ----- END OF SEARCH FORM --------

// retrieve any saved orders
echo '<small>';

if(!empty($savedOrders)) {
    if(count($savedOrders) > 1) {
	echo 'Saved Orders : ';
    }
    else {
	echo 'Saved Order : ';
    }

    foreach($savedOrders as $id => $date) {
	$link = base_url()."group/orderbook?order_id=$id"; // get the saved order
	echo "[ <a href=\"$link\">$id ( $date )</a> ] ";
    }
}

if(!empty($savedOrders)) {
    echo ' || ';
}

if(!empty($recentOrders)) {
    if(count($recentOrders) > 1) {
	echo 'Recent Orders : ';
    }
    else {
	echo 'Recent Order : ';
    }

    foreach($recentOrders as $id => $date) {
	$link = base_url()."group/orderbook?order_id=$id"; // get the a recent order
	echo "[ <a href=\"$link\">$id ( $date )</a> ] ";
    }
}

if(!empty($savedOrders)  || !empty($recentOrders)) { 
    echo '<br>';
}

echo '</small>';

if(!empty($order_id)) {
    echo '<small>Current Order ID : <b><span style="color: rgb(235, 0, 0);">'.$order_id.'</span></b>';
    echo ' || Status : <b><span style="color: rgb(235, 0, 0);">'.strtoupper($status).'</span></b> ( '.$status_date.' )';
    echo '<br></small>';
}

// the form
$order_link = base_url().'group/orderbook/order_process';
?>
<div class="formWrapper">
    <table class="formUpperBar" style="width: 100%" cellpadding="4" cellspacing="2">
        <tbody>
        <tr>
            <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
                <?
		if(!empty($order_id)) 
		    echo "Edit Order";
		else 
		    echo "Make New Order";
		?>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="formSubBar">General Order Information</div>
    <form action="<?=$order_link?>" method="POST" class="form-inline" style="margin-right:10px">
        <input type="hidden" name="task" value="orderbook_add">
        <input type="hidden" name="task2" value="save"> 
        <input type="hidden" name="status" value="<?=$status?>">
        <?
        if(!empty($order_id)) {
            echo '<input type="hidden" name="order_id" value="'.$order_id.'">';
	}
        ?>
        <table class="formTable">
                <tr>
                    <td>
                        <label for="companies" class="control-label">
                            Company :
                            <? 
                                if($role == 'admin' || $role == 'buyer') {
                                    echo '[ <a href="javascript:removeSelectedCompany()">X</a> ]';
				}
                            ?>
                        </label><br>
                        <select name="companies" size="1" onChange="changeCompany(this.form.companies, 0)" class="input-medium">
                            <option value="">Select One</option>
                            <?
                                $length = sizeof($cnames);
                                for($i = 0; $i < $length; $i++) {
                                    echo '<option value="'.$cnames[$i].'">['.($i + 1).'] '.$cnames[$i].'</option>';
                                }
                            ?>
                            <option value="Enter Company">Other</option>
                        </select>
                        <input type="text" name="company_0" value="<?=$company?>" class="input-block-level input-smallmedium">
                    </td>
                    <td>
                        <label for="ponum" class="control-label">PO #:</label><br>
                        <input type="text" name="ponum" class="input-block-level input-smallmedium" value="<?=$ponum?>">
                    </td>
                    <td>
                        <label for="conum" class="control-label">Confirmation #:</label>
                        <input type="text" name="conum" class="input-block-level input-smallmedium" value="<?=$conum?>">
                    </td>
                    <td>
                        <label for="priority" class="control-label">Priority :</label>
                        <select name="priority" size="1" class="input-small">
                            <?
                            if(!empty($priority)) {
                                echo "<option value=\"$priority\">$priority</option>";
                            }
                            ?>
                            <option value="Low">Low</option>
                            <option value="High">High</option>
                        </select>
                    </td>
                    <td>
                        <label for="orderdate" class="control-label">Order Date :</label>
                        <input type="text" name="orderdate" class="input-block-level" readonly="readonly" value="<?=$status_date?>">
                    </td>
                </tr>
                <tr>
                    <td>
			Account # 
			<?
			if($role == 'admin' || $role == 'buyer') {
			    echo '[ <a href="javascript:removeSelectedAccount()">X</a> ] ';
			    echo '[ <a href="javascript:addAccountToTallyList()">Tally</a> ] ';
			    echo '[ <a href="javascript:removeAccountFromTallyList()">Remove Tally</a> ]';
			}?>
			<select name="accounts" size="1" onChange="changeAccount(this.form.accounts, 0)" class="input-medium">
			    <option value="">Select One</option>
			    <?
			    for($i = 0; $i < $length; $i++) {
				$tally_account = $tallyAcounts[$accounts[$i]];
				if(!empty($tally_account) && ($role == 'admin' || $role == 'buyer')) {
				    echo '<option value="'.$accounts[$i].'">'.$tally_account.'</option>';
				}
				else {
				    echo '<option value="'.$accounts[$i].'">'.$accounts[$i].'</option>';
				}
			    }
			    ?>
			    <option value="Enter Account">Other</option>
			</select>
			<input type="text" name="account" class="input-smallmedium" value="<?=$account?>">
		    </td>
                    <td>
			<label for="gexpense" class="control-label">Group Expense :</label>
			<input type="text" name="gexpense" readonly="readonly" class="input-block-level input-smallmedium" value="<?=$g_expense?>">
		    </td>
                    <td>
			<label for="pexpense" class="control-label">Personal Expense :</label>
			<input type="text" name="pexpense" readonly="readonly" class="input-block-level input-smallmedium" value="<?=$p_expense?>">
		    </td>
                    <td>
			<label for="sexpense" class="control-label">S & H :</label>
			<input type="text" name="sexpense" class="input-block-level input-small" value="<?=$s_expense?>">
		    </td>
                    <td>
			<label for="total" class="control-label">Order Total :</label>
			<input type="text" name="total" class="input-block-level" readonly="readonly" value="<?=$total?>">
		    </td>
                </tr>
                <tr>
                    <td colspan="5">
			<label for="notes" class="control-label">Order Notes :</label>
			<textarea name="notes" class="input-block-level"><?=$notes?></textarea>
		    </td>
                </tr>
        </table>
	<br>
	<div class="formSubBar">Order Items</div>
	    <table class="formTable-compact">
		<thead>
		    <th>Item #</th>
		    <th>Type</th>
		    <th>Company</th>
		    <th><a href="javascript:openItemListPage()">Product ID</a></th>
		    <th>Description</th>
		    <th>Units</th>
		    <th>Cost</th>
		    <th>My Item</th>
		    <th>Status</th>
		</thead>
		<tbody>
		    <?
		    // add slots for up to ten items
		    for($i = 1; $i <= $maxitems; $i++) {
			// check to see if an item exist for this order
			$type = '';
			$company = '';
			$product = '';
			$description = '';
			$amount = '';
			$units = '';
			$price = '$0.00';
			$owner = '';
			$istatus = '';

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
			}
			else if($status == 'ordered') { // don't want to print any empty slots if the order has been placed already
			    continue;
			}
			?>
		    <tr>
			<td>
			    <input type="checkbox" name="item_<?=$i?>" value="<?=$i?>"> <?=$i?>
			</td>
			<td>
			    <select name="type_<?=$i?>" size="1" class="input-smallmedium">
				<?
				if(!empty($type)) {
				    echo '<option value="'.$type.'">'.$type.'</option>';
				}
				?>
				<option value="Chemical">Chemical</option>
				<option value="Supply">Supply</option>
				<option value="Other">Other</option>
			    </select>
			</td>
			<td>
			    <input type="text" name="company_<?=$i?>" value="<?=$company?>" onChange="putCompany(this.form.company_<?=$i?>)" class="input-smallmedium">
			</td>
			<td>
			    <input type="text" name="product_<?=$i?>" size="8" value="<?=$product?>" class="input-smallmedium" onChange="putProduct(this.form.product_<?=$i?>, this.form.type_<?=$i?>, this.form.company_<?=$i?>, this.form.description_<?=$i?>, this.form.units_<?=$i?>, this.form.price_<?=$i?>)">
			</td>
			<td>
			    <input type="text" name="description_<?=$i?>" class="input-medium" value="<?=$description?>">
			</td>
			<td>
			    <select name="amount_<?=$i?>" size="1" class="input-mini">';
				<?
				if(!empty($amount)) {
				    echo '<option value="'.$amount.'">'.$amount.'x</option>';
				}
				?>
				<option value="1">1x</option>
				<option value="2">2x</option>
				<option value="3">3x</option>
				<option value="4">4x</option>
				<option value="5">5x</option>
				<option value="6">6x</option>
				<option value="7">7x</option>
				<option value="8">8x</option>
				<option value="9">9x</option>
				<option value="10">10x</option>
			    </select>
			    <input type="text" name="units_<?=$i?>" value="<?=$units?>" class="input-mini">
			</td>
			<td>
			    <input type="text" name="price_<?=$i?>" value="<?=$price?>" class="input-small">
			</td>
			<td style="text-align:center">
			    <?
			    if($owner == $user_id) {
				echo '<input type="checkbox" name="myitem_'.$i.'" value="'.$i.'" checked="checked">';
			    } else { // defualt check my item
				echo '<input type="checkbox" name="myitem_'.$i.'" value="'.$i.'">';
			    }
			    ?>
			</td>
			<td>
			    <?
			    if($status == 'saved' || $status == 'requested') {
				echo '<input type="hidden" name="status_'.$i.'" value="pending">Pending';
			    }
			    else if($status == 'ordered' && !empty($istatus)) {
				echo '<input type="hidden" name="status_'.$i.'" value="'.$istatus.'">'.ucfirst($istatus);

				// if the iitem has been received create a link to edit the entry in the database
				if($istatus == 'received' && $type != 'Other') {         
				    $editdb_link = '';
				    if($type == 'Chemical') {
					$editdb_link = base_url()."group/chemicals/edit?chem_id=$stock_id";
				    }
				    else if($type == 'Supply') {
					$editdb_link = base_url()."group/supplies/edit?item_id=$stock_id";
				    }
				    echo ' [ <a href="'.$editdb_link.'" target="_blank">edit</a> ]';
				}
			    }
			    else {
				echo '<input type="hidden" name="status_'.$i.'" value="pending">N/A';
			    }
			    ?>
			</td>
		    </tr>	
			<?
		    } // end of slot item FOR-loop 
		    ?>
		</tbody>
	    </table>
	<?
	if(!empty($order_id) && $status != 'ordered') { // add button that allows the number of items in the list to be incremented
	    echo '<tr><td style="text-align: center;">';
	    echo '<input type="button" name="butInc" value="+5" 
	    style="background: rgb(238, 238, 238); color: #3366FF" 
	    onclick="submitOrder(\'increment\')"/></td>';
	    echo '<td colspan="8" rowspan="1" style="vertical-align: top;"><br></td>';
	}

	echo '</tbody></table>';

	// Add the buttons
	echo '<div style="text-align: right;">
	<input type="button" name="butSave" value="Save" class="btn" onclick="submitOrder(\'save\')"/> ';

	if(!empty($order_id)) {
	    echo '<input type="button" name="butSaveitem" value="Save Item to List" class="btn" onclick="submitOrder(\'item_save\')"/> ';

	    echo '<input type="button" name="butRemoveItem" value="Remove Item" class="btn btn-danger" onclick="submitOrder(\'remove\')"/> ';

	    if($status == 'saved' || $status == 'requested') {
		echo '<input type="button" name="butRemoveOrder" value="Remove Order" class="btn btn-danger" onclick="submitOrder(\'remove_order\')"/> ';
	    }
	    else if($status == 'ordered') {
		echo '<input type="button" name="butReceive" value="Items Received" class="btn" onclick="submitOrder(\'item_received\')"/> ';
	    }
	}

	if($status == 'saved') {
	    echo '<input type="button" name="butSubmit" value="Submit Order" class="btn" onclick="submitOrder(\'submit\')"/>';
	}
	?>
        <button type="submit" class="btn btn-primary btn-small">Order</button>
    </form>
</div>




