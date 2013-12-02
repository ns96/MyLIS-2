<?php

// get the variables now
$item_id = $itemInfo['item_id'];
$model = $itemInfo['model'];
$name = trim($itemInfo['name']);
$company = $itemInfo['company'];
$product_id = $itemInfo['product_id'];
$amount = $itemInfo['amount'];
$units = $itemInfo['units'];
$entry = $itemInfo['entry_date'];
$status = $itemInfo['status'];
$status_date = $itemInfo['status_date'];
$sn = $itemInfo['sn']; // not used now
$category = $itemInfo['category'];
$location_id = $itemInfo['location_id'];
$notes = $itemInfo['notes'];
$owner = $itemInfo['owner'];
$userid = $itemInfo['userid'];

// combine amount with units
$amount = $amount.'x '.$units;

// depending status change message
$status_user = $userid;
if($status_user != 'admin') {
    $user = $users[$userid];
    $status_user = $user->name;
}

if(strstr('Order', $status)) {
    $status = 'Ordered by '.$status_user.' on '.$status_date;
}
else if(strstr('Checked Out', $status)) {
    $status = 'Checked Out by '.$status_user.' on '.$status_date;
}
else {
    $status .= ' on '.$status_date;
}

// get the full location

$location_id = $location_id.' ( Room : '.$locationInfo['room'].' || '.$locationInfo['description'].' )';  

// get the full owners name
if($owner != 'admin') {
    $user = $users[$owner];
    $owner = $user->name;
}
else {
    $owner = 'Group Supply';
}

// creat the table now
$cell_color1 = 'rgb(150,200,255)';
$cell_color2 = 'rgb(230,230,230)';

echo '<table style="background-color: rgb(255, 255, 255); width: 100%; text-align: left;"
border="0" cellpadding="2" cellspacing="2"><tbody>';

echo '<tr>';
echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
<small><b>Supply ID</b></small></td>';
echo '<td style="vertical-align: top; background-color: '.$cell_color2.';"><small>';
    
    // display the Supply ID
    echo '<b><span style="color: rgb(235, 0, 0);">'.$item_id.'</span></b>';

echo '</small></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
<small><b>Model #</b></small></td>';
echo '<td style="vertical-align: top; background-color: '.$cell_color2.';">
<small>'.$model.'</small></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
<small><b>Name</b></small></td>';
echo '<td style="vertical-align: top; background-color: '.$cell_color2.';">
<small>'.$name.'</small></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
<small><b>Category</b></small></td>';
echo '<td style="vertical-align: top; background-color: '.$cell_color2.';">
<small>'.$category.'</small></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
<small><b>Company</b></small></td>';
echo '<td style="vertical-align: top; background-color: '.$cell_color2.';">
<small>'.$company.'</small></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
<small><b>Product ID</b></small></td>';
echo '<td style="vertical-align: top; background-color: '.$cell_color2.';">
<small>'.$product_id.'</small></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
<small><b>Amount</b></small></td>';
echo '<td style="vertical-align: top; background-color: '.$cell_color2.';">
<small>'.$amount.'</small></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
<small><b>Status</b></small></td>';
echo '<td style="vertical-align: top; background-color: '.$cell_color2.';">
<small>'.$status.'</small></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
<small><b>Location</b></small></td>';
echo '<td style="vertical-align: top; background-color: '.$cell_color2.';">
<small>'.$location_id.'</small></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
<small><b>Owner</b></small></td>';
echo '<td style="vertical-align: top; background-color: '.$cell_color2.';">
<small>'.$owner.'</small></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
<small><b>Notes</b></small></td>';
echo '<td style="vertical-align: top; background-color: '.$cell_color2.';">
<small>'.$notes.'</small></td>';
echo '</tr>';

if($role != 'guest') {
    // put some links for modify the status of this item
    $cs_link0 = base_url()."group/supplies/changeStatus/in-stock?item_id=".$item_id; // change status to in stock
    $cs_link1 = base_url()."group/supplies/changeStatus/out-of-stock?item_id=".$item_id; // change status to out of stock
    $cs_link2 = base_url()."group/supplies/changeStatus/checked-out?item_id=".$item_id; // change status to checked out
    $cs_link3 = base_url()."group/supplies/changeStatus/returned?item_id=".$item_id; // change status to returned
    $cs_link4 = base_url()."group/supplies/changeStatus/ordered?item_id=".$item_id; // change status to ordered
    $mine_link = base_url()."group/supplies/transfer?item_id=".$item_id; // used to transfer this make to current user
    $edit_link = base_url()."group/supplies/edit?item_id=".$item_id; // used to edit the information about this supply
    $delete_link = base_url()."group/supplies/delete?item_id=".$item_id; // used to remove this supply

    echo '<tr>';
    echo '<td style="vertical-align: top; background-color: '.$cell_color1.';"><small>
    <b>Change Status</b></small></td>';
    echo '<td style="vertical-align: top; background-color: '.$cell_color2.';"><small>
    [ <a href="'.$cs_link0.'">In Stock</a> ] 
    [ <a href="'.$cs_link1.'">Out of Stock</a> ] 
    [ <a href="'.$cs_link2.'">Checked Out</a> ] 
    [ <a href="'.$cs_link3.'">Returned</a> ] 
    [ <a href="'.$cs_link4.'">Ordered</a> ] 
    [ <a href="'.$mine_link.'">Make Mine</a> ] 
    [ <a href="'.$edit_link.'">Edit</a> ] ';
    /*if($role == 'admin') { // should really only let admin delete, but ...
    echo '[ <a href="'.$delete_link.'">Delete</a> ]';
    }*/
    echo '[ <a href="'.$delete_link.'">Delete</a> ]';
    echo '</small></td></tr>';
}

echo '</tbody></table>';
