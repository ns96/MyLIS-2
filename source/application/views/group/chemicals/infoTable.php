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

// creat the table now
$cell_color1 = 'rgb(150,200,255)';
$cell_color2 = 'rgb(230,230,230)';

echo '<table style="background-color: rgb(255, 255, 255); width: 100%; text-align: left;"
border="0" cellpadding="2" cellspacing="2"><tbody>';

echo '<tr>';
echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
<small><b>Chem ID</b></small></td>';
echo '<td style="vertical-align: top; background-color: '.$cell_color2.';"><small>';

    // construct url for the msds sites, pubchem, and nist
    //$msdsxchange_url = 'http://www.actiocms.com/msdsxchange/english/xchange_productname.cfm?alpha='.$name;
    $msdsxchange_url = 'http://www.msdsxchange.com/english/index.cfm';
    //$msdsxchange_url = "$script?task=chemicals_msdsx&name=$name";
    $sirimsds_url = 'http://www.hazard.com/msds/gn.cgi?query='.$name.'&start=0';
    $msds_url = 'http://www.msdshazcom.com/search.cgi?zoom_query='.$name;
    $pubchem_url = 'http://www.ncbi.nlm.nih.gov/sites/entrez?db=pccompound&term='.$name;
    $nist_url = 'http://webbook.nist.gov/cgi/cbook.cgi?Name='.$name.'&Units=SI';
    
    // display the chemical ID
    echo '<b><span style="color: rgb(235, 0, 0);">'.$chem_id.'</span></b>';
    echo '&nbsp;&nbsp;&nbsp;&nbsp;';
    
    // display the external links
    echo "<a href=\"$msdsxchange_url\" target=\"_blank\">MSDS XChange</a> | ";
    echo "<a href=\"$sirimsds_url\" target=\"_blank\">SIRI MSDS</a> | ";
    echo "<a href=\"$msds_url\" target=\"_blank\">Seton MSDS</a> | ";
    echo "<a href=\"$pubchem_url\" target=\"_blank\">PubChem</a> | ";
    echo "<a href=\"$nist_url\" target=\"_blank\">NIST Chemistry WebBook</a>";

echo '</small></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
<small><b>CAS #</b></small></td>';
echo '<td style="vertical-align: top; background-color: '.$cell_color2.';">
<small>'.$cas.'</small></td>';
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
    $cs_link0 = base_url()."group/chemicals/changeStatus/in-stock?chem_id=".$chem_id; // change status to in stock
    $cs_link1 = base_url()."group/chemicals/changeStatus/out-of-stock?chem_id=".$chem_id; // change status to out of stock
    $cs_link2 = base_url()."group/chemicals/changeStatus/checked-out?chem_id=".$chem_id; // change status to checked out
    $cs_link3 = base_url()."group/chemicals/changeStatus/returned?chem_id=".$chem_id; // change status to returned
    $cs_link4 = base_url()."group/chemicals/changeStatus/ordered?chem_id=".$chem_id; // change status to ordered
    $mine_link = base_url()."group/chemicals/transfer?chem_id=".$chem_id; // used to transfer this make to current user
    $edit_link = base_url()."group/chemicals/edit?chem_id=".$chem_id; // used to edit the information about this chemical
    $delete_link = base_url()."group/chemicals/delete?chem_id=".$chem_id; // used to remove this chemical

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
