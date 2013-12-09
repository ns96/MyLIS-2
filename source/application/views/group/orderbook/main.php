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

// ----- SEARCH FORM --------
$search_target = base_url()."group/orderbook/search";
echo '<table style="text-align: left; width: 100%;" border="0" 
cellpadding="2" cellspacing="2"><tr><td style="vertical-align: top;">
<b><span style="color: #3366FF;">Group Orders ('.$year.')</span></b><td>
<td style="text-align: right; vertical-align: top;">';
echo '<form name="search_form" action="'.$search_target.'" method="post">';
echo '<input type="hidden" name="task" value="orderbook_search">';
echo '<input type="hidden" name="order_id" value="'.$order_id.'">';
echo '<input type="checkbox" name="myorders" value="yes" checked="checked"> ';
echo 'My orders ';
echo '<input type="text" name="searchfor" size="35" value=""> ';
echo ' <input type="submit" value="Search" 
style="background: rgb(238, 238, 238); color: #3366FF">';
echo '</form>';
echo '</td></tr></tbody></table>';
// ----- END OF SEARCH FORM --------

echo printColoredLine('#3366FF', '2px').'<pre></pre>';

echo '<table style="width: 100%; text-align: left;" border="0" cellpadding="2"
cellspacing="2"><tbody>';
echo '<tr>';
echo '<td style="vertical-align: top;">
[ <a href="'.$neworder_link.'">New Order</a> ] 
[ <a href="'.$newitemlist_link.'">New Item List</a> ] 
[ <a href="'.$myorderbook_link.'">My Orders</a> ] 
[ <a href="'.$viewitemlist_link.'">Item Lists</a> ]</td>';

echo '<td style="vertical-align: top; text-align: right;">';

$icount = count($pendingItems);
echo '[ <a href="'.$pending_link.'">Pending Items</a> ('.$icount.')] ';

$oicount = count($orderedItems);
echo '[ <a href="'.$ordered_items_link.'">Ordered Items</a> ('.$oicount.') ] ';

if($role == 'admin' || $role == 'buyer') {
    echo '[ <a href="'.$orderbook_link.'">Orders</a> ] ';
}

// if role is a guest buyer display the logout link instead of home link
if($role == 'guestbuyer') {
    $home_link = base_url()."group/login/logout";
    echo '[ <a href="'.$home_link.'">Logout</a> ] ';
}
else {
    echo '[ <a href="'.$home_link.'">Home</a> ] ';
}

echo '</td></tr></tbody></table><br>';

// the form
echo '<form name="form1" action="'.base_url().'group/orderbook/order_process" method="post">';
echo '<input type="hidden" name="task" value="orderbook_add">';
echo '<input type="hidden" name="task2" value="save">'; // the value of this changes depending on 

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
    echo '<input type="hidden" name="order_id" value="'.$order_id.'">';
    echo '<small>Current Order ID : <b><span style="color: rgb(235, 0, 0);">'.$order_id.'</span></b>';
    echo ' || Status : <b><span style="color: rgb(235, 0, 0);">'.strtoupper($status).'</span></b> ( '.$status_date.' )';
    echo '<br></small>';
}

// set the status
echo '<input type="hidden" name="status" value="'.$status.'">';

// add the table holding order wide variables
echo '<table style="background-color: rgb(225, 255, 255); text-align: left; width: 100%;" border="1" cellpadding="2"
cellspacing="0"><tbody>';

echo '<tr>';
echo '<td style="vertical-align: top;"><small>Company ';
if($role == 'admin' || $role == 'buyer') {
    echo '[ <a href="javascript:removeSelectedCompany()">X</a> ]';
}
echo '</small><br>';
echo '<select name="companies" size="1" onChange="changeCompany(this.form.companies, 0)">';
echo '<option value="">Select One</option>';
$length = sizeof($cnames);
for($i = 0; $i < $length; $i++) {
    echo '<option value="'.$cnames[$i].'">['.($i + 1).'] '.$cnames[$i].'</option>';
}
echo '<option value="Enter Company">Other</option></select> ';
echo '<input type="text" name="company_0" size="15" value="'.$company.'"></td>';

echo '<td style="vertical-align: top;"><small>PO #</small><br>
<input type="text" name="ponum" size="10" value="'.$ponum.'"></td>';
echo '<td style="vertical-align: top;"><small>Confirmation #</small><br>
<input type="text" name="conum" size="10" value="'.$conum.'"></td>';
echo '<td style="vertical-align: top;"><small>Priority</small><br>
<select name="priority" size="1">';
if(!empty($priority)) {
    echo "<option value=\"$priority\">$priority</option>";
}
echo '<option value="Low">Low</option>
<option value="High">High</option>
</select>';
echo '<td style="vertical-align: top;"><small>Order Date</small><br>
<input type="text" name="orderdate" size="10" readonly="readonly" value="'.$status_date.'"></td>';
echo '</tr>';

echo '<tr>';

echo '<td style="vertical-align: top;"><small>Account # ';
if($role == 'admin' || $role == 'buyer') {
    echo '[ <a href="javascript:removeSelectedAccount()">X</a> ] ';
    echo '[ <a href="javascript:addAccountToTallyList()">Tally</a> ] ';
    echo '[ <a href="javascript:removeAccountFromTallyList()">Remove Tally</a> ]';
}
echo '</small><br>';
echo '<select name="accounts" size="1" onChange="changeAccount(this.form.accounts, 0)">';
echo '<option value="">Select One</option>';

$length = sizeof($accounts);
for($i = 0; $i < $length; $i++) {
    $tally_account = $tallyAcounts[$accounts[$i]];
    if(!empty($tally_account) && ($role == 'admin' || $role == 'buyer')) {
	echo '<option value="'.$accounts[$i].'">'.$tally_account.'</option>';
    }
    else {
	echo '<option value="'.$accounts[$i].'">'.$accounts[$i].'</option>';
    }
}
echo '<option value="Enter Account">Other</option></select> ';
echo '<input type="text" name="account" size="15" value="'.$account.'"></td>';

echo '<td style="vertical-align: top;"><small>Group Expense</small><br>
<input type="text" name="gexpense" size="10" readonly="readonly" value="'.$g_expense.'"></td>';
echo '<td style="vertical-align: top;"><small>Personal Expense</small><br>
<input type="text" name="pexpense" size="10" readonly="readonly" value="'.$p_expense.'"></td>';
echo '<td style="vertical-align: top;"><small>S & H</small><br>
<input type="text" name="sexpense" size="10" value="'.$s_expense.'"></td>';
echo '<td style="vertical-align: top;"><small>Order Total</small><br>
<input type="text" name="total" size="10" readonly="readonly" value="'.$total.'"></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top;"><span style="color: rgb(235, 0, 0);"><small><b>Order Notes :</b></small></span></td>';
echo '<td colspan="4" rowspan="1" style="vertical-align: top;">
<textarea name="notes" rows="2" cols="65">'.$notes.'</textarea></td>';
echo '</tr>';

echo '</tbody></table><br>';

// add the table that allows inputing of items
echo '<table style="background-color: rgb(225, 255, 255); text-align: left; width: 100%;" border="1" cellpadding="2"
cellspacing="0"><tbody>';

echo '<tr>';
echo '<td width="6%" style="vertical-align: top;"><small><b>Item #</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Type</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Company</b></small></td>';
echo '<td style="vertical-align: top;"><small><b><a href="javascript:openItemListPage()">Product ID</a></b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Description</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Units</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Cost</b></small></td>';
echo '<td width="6%" style="vertical-align: top; text-align: center;"><small><b>My Item</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Status</b></small></td>';
echo '</tr>';


// add slots for up to ten itmes
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

    echo '<tr>';
    echo '<td style="vertical-align: top;">
    <input type="checkbox" name="item_'.$i.'" value="'.$i.'">'.$i.'</td>';

    echo '<td style="vertical-align: top;">
    <select name="type_'.$i.'" size="1">';
    if(!empty($type)) {
	echo '<option value="'.$type.'">'.$type.'</option>';
    }
    echo '<option value="Chemical">Chemical</option>
    <option value="Supply">Supply</option>
    <option value="Other">Other</option>
    </select></td>';

    echo '<td style="vertical-align: top;">
    <input type="text" name="company_'.$i.'" size="10" value="'.$company.'" 
    onChange="putCompany(this.form.company_'.$i.')"></td>';

    echo '<td style="vertical-align: top;">
    <input type="text" name="product_'.$i.'" size="8" value="'.$product.'"
    onChange="putProduct(this.form.product_'.$i.', this.form.type_'.$i.', 
    this.form.company_'.$i.', this.form.description_'.$i.', this.form.units_'.$i.', this.form.price_'.$i.')"></td>';

    echo '<td style="vertical-align: top;">
    <input type="text" name="description_'.$i.'" size="25" value="'.$description.'"></td>';

    echo '<td style="vertical-align: top;">
    <select name="amount_'.$i.'" size="1">';
    if(!empty($amount)) {
	echo '<option value="'.$amount.'">'.$amount.'x</option>';
    }
    echo '<option value="1">1x</option>
    <option value="2">2x</option>
    <option value="3">3x</option>
    <option value="4">4x</option>
    <option value="5">5x</option>
    <option value="6">6x</option>
    <option value="7">7x</option>
    <option value="8">8x</option>
    <option value="9">9x</option>
    <option value="10">10x</option>
    </select><input type="text" name="units_'.$i.'" size="4" value="'.$units.'" ></td>';

    echo '<td style="vertical-align: top;">
    <input type="text" name="price_'.$i.'" size="6" value="'.$price.'" ></td>';

    echo '<td style="vertical-align: top; text-align: center;">';
    if($owner == $user_id) {
	echo '<input type="checkbox" name="myitem_'.$i.'" value="'.$i.'" checked="checked"></td>';
    }
    else if(!empty($owner)) {
	echo '<input type="checkbox" name="myitem_'.$i.'" value="'.$i.'"></td>';
    }
    else { // defualt check my item
	echo '<input type="checkbox" name="myitem_'.$i.'" value="'.$i.'"></td>';
    }

    echo '<td style="vertical-align: top;">';

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

    echo '</td></tr>';
}

if(!empty($order_id) && $status != 'ordered') { // add button that allows the number of items in the list to be incremented
    echo '<tr><td style="text-align: center;">';
    echo '<input type="button" name="butInc" value="+5" 
    style="background: rgb(238, 238, 238); color: #3366FF" 
    onclick="submitOrder(\'increment\')"/></td>';
    echo '<td colspan="8" rowspan="1" style="vertical-align: top;"><br></td>';
}

echo '</tbody></table>';

// Add the buttons
echo '<div style="color: rgb(51, 102, 255); text-align: right;">
<input type="button" name="butSave" value="Save" 
style="background: rgb(238, 238, 238); color: #3366FF" 
onclick="submitOrder(\'save\')"/> ';

if(!empty($order_id)) {
    echo '<input type="button" name="butSaveitem" value="Save Item to List" 
    style="background: rgb(238, 238, 238); color: #3366FF" 
    onclick="submitOrder(\'item_save\')"/> ';

    echo '<input type="button" name="butRemoveItem" value="Remove Item" 
    style="background: rgb(238, 238, 238); color: #3366FF" 
    onclick="submitOrder(\'remove\')"/> ';

    if($status == 'saved' || $status == 'requested') {
	echo '<input type="button" name="butRemoveOrder" value="Remove Order" 
	style="background: rgb(238, 238, 238); color: #3366FF" 
	onclick="submitOrder(\'remove_order\')"/> ';
    }
    else if($status == 'ordered') {
	echo '<input type="button" name="butReceive" value="Items Received" 
	style="background: rgb(238, 238, 238); color: #3366FF" 
	onclick="submitOrder(\'item_received\')"/> ';
    }
}

if($status == 'saved') {
    echo '<input type="button" name="butSubmit" value="Submit Order" 
    style="background: rgb(238, 238, 238); color: #3366FF" 
    onclick="submitOrder(\'submit\')"/>';
}
echo '</div>';

echo '</form>';
