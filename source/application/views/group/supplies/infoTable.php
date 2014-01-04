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

// put some links for modify the status of this item
$cs_link0 = base_url()."group/supplies/change_status/in-stock?item_id=".$item_id; // change status to in stock
$cs_link1 = base_url()."group/supplies/change_status/out-of-stock?item_id=".$item_id; // change status to out of stock
$cs_link2 = base_url()."group/supplies/change_status/checked-out?item_id=".$item_id; // change status to checked out
$cs_link3 = base_url()."group/supplies/change_status/returned?item_id=".$item_id; // change status to returned
$cs_link4 = base_url()."group/supplies/change_status/ordered?item_id=".$item_id; // change status to ordered
$mine_link = base_url()."group/supplies/transfer?item_id=".$item_id; // used to transfer this make to current user
$edit_link = base_url()."group/supplies/edit?item_id=".$item_id; // used to edit the information about this supply
$delete_link = base_url()."group/supplies/delete?item_id=".$item_id; // used to remove this supply

// creat the table now
?>    
    <table class="info_table">
        <tr>
                <td>Supply ID</td>
                <td><?=$item_id?></td>
                <td rowspan="11">
                    <img src="<?=base_url().'images/icons/info.png'?>" />
                </td>
        </tr>
        <tr>
                <td>Model #</td>
                <td><?=$model?></td>
        </tr>
        <tr>
                <td>Name</td>
                <td><?=$name?></td>
        </tr>
        <tr>
                <td>Category</td>
                <td><?=$category?></td>
        </tr>
        <tr>
                <td>Company</td>
                <td><?=$company?></td>
        </tr>
        <tr>
                <td>Product ID</td>
                <td><?=$product_id?></td>
        </tr>
        <tr>
                <td>Amount</td>
                <td><?=$amount?></td>
        </tr>
        <tr>
                <td>Status</td>
                <td>
                    <?=$status?>
                    <? if($role != 'guest') { ?>
                            <div class="btn-group" style="margin-left: 20px">
                                <a class="btn dropdown-toggle btn-small" data-toggle="dropdown" href="#">
                                  Change status
                                  <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                  <!-- dropdown menu links -->
                                  <li><a href="<?=$cs_link0?>">In Stock</a></li>
                                  <li><a href="<?=$cs_link1?>">Out of Stock</a></li>
                                  <li><a href="<?=$cs_link2?>">Checked Out</a></li>
                                  <li><a href="<?=$cs_link3?>">Returned</a></li>
                                  <li><a href="<?=$cs_link4?>">Ordered</a></li>
                                </ul>
                            </div>
                <? } ?>
                </td>
        </tr>
        <tr>
                <td>Location</td>
                <td><?=$location_id?></td>
        </tr>
        <tr>
                <td>Owner</td>
                <td>
                        <?=$owner?>
                        <? if($role != 'guest') { ?>
                            <a href="<?=$mine_link?>" style="margin-left: 20px"><button class="btn btn-small">Make mine</button></a>
                        <? } ?>
                </td>
        </tr>
        <tr>
                <td>Notes</td>
                <td><?=$notes?></td>
        </tr>
    </table>
        <? if($role != 'guest') { ?>
                <td rowspan="12">
                   <a href="<?=$edit_link?>"><button class="btn btn-success">Edit</button></a>
                   <a href="<?=$delete_link?>" style="margin-left: 20px"><button class="btn btn-danger">Delete</button></a>
                </td>
        <? } ?>


<?

