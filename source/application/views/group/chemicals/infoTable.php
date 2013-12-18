<?php

// get the variables now
$chem_id = $chemInfo['chem_id'];
$cas = $chemInfo['cas'];
$name = trim($chemInfo['name']);
$company = $chemInfo['company'];
$product_id = $chemInfo['product_id'];
$amount = $chemInfo['amount'];
$units = $chemInfo['units'];
$entry = $chemInfo['entry_date'];
$status = $chemInfo['status'];
$status_date = $chemInfo['status_date'];
$mfmw = $chemInfo['mfmw']; // not used now
$category = $chemInfo['category'];
$location_id = $chemInfo['location_id'];
$notes = $chemInfo['notes'];
$owner = $chemInfo['owner'];
$userid = $chemInfo['userid'];

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
    $owner = 'Group Chemical';
}

// construct url for the msds sites, pubchem, and nist
$msdsxchange_url = 'http://www.msdsxchange.com/english/index.cfm';
$sirimsds_url = 'http://www.hazard.com/msds/gn.cgi?query='.$name.'&start=0';
$msds_url = 'http://www.msdshazcom.com/search.cgi?zoom_query='.$name;
$pubchem_url = 'http://www.ncbi.nlm.nih.gov/sites/entrez?db=pccompound&term='.$name;
$nist_url = 'http://webbook.nist.gov/cgi/cbook.cgi?Name='.$name.'&Units=SI';

// put some links for modify the status of this item
$cs_link0 = base_url()."group/chemicals/changeStatus/in-stock?chem_id=".$chem_id; // change status to in stock
$cs_link1 = base_url()."group/chemicals/changeStatus/out-of-stock?chem_id=".$chem_id; // change status to out of stock
$cs_link2 = base_url()."group/chemicals/changeStatus/checked-out?chem_id=".$chem_id; // change status to checked out
$cs_link3 = base_url()."group/chemicals/changeStatus/returned?chem_id=".$chem_id; // change status to returned
$cs_link4 = base_url()."group/chemicals/changeStatus/ordered?chem_id=".$chem_id; // change status to ordered
$mine_link = base_url()."group/chemicals/transfer?chem_id=".$chem_id; // used to transfer this make to current user
$edit_link = base_url()."group/chemicals/edit?chem_id=".$chem_id; // used to edit the information about this chemical
$delete_link = base_url()."group/chemicals/delete?chem_id=".$chem_id; // used to remove this chemical

 // display the external links
echo "<a href=\"$msdsxchange_url\" target=\"_blank\">MSDS XChange</a> | ";
echo "<a href=\"$sirimsds_url\" target=\"_blank\">SIRI MSDS</a> | ";
echo "<a href=\"$msds_url\" target=\"_blank\">Seton MSDS</a> | ";
echo "<a href=\"$pubchem_url\" target=\"_blank\">PubChem</a> | ";
echo "<a href=\"$nist_url\" target=\"_blank\">NIST Chemistry WebBook</a>";

// creat the table now
?>    
    <table class="info_table">
        <tr>
                <td>Chem ID</td>
                <td><?=$chem_id?></td>
                <td rowspan="11">
                    <img src="<?=base_url().'images/icons/info.png'?>" />
                </td>
        </tr>
        <tr>
                <td>CAS #</td>
                <td><?=$cas?></td>
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


