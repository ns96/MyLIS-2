<?php

$userid = $user->userid;
$role = $user->role;

// initialize some links

$list_mine = base_url()."group/chemicals/listing/mine"; // list all chemicals that I own
$list_all = base_url()."group/chemicals/listing/all"; // list all chemicals
$list_bycategory = base_url()."group/chemicals/listing/by_category"; // list all by categories
$list_bylocation = base_url()."group/chemicals/listing/by_location"; // list all by categories
$list_locations = base_url()."group/chemicals/list_locations"; // displays the list of locations
$search_target = base_url()."group/chemicals/search";
$location_link = base_url()."group/chemicals/listLocations";
$home_link = base_url()."group/chemicals";

echo "<html>";
echo "<head>";
echo "<title>Search Chemical Inventory</title>";
echo "</head>";
echo '<body>';
echo '<table style="width: 100%; text-align: left;" border="0" cellpadding="2" cellspacing="0">';
echo '<tbody>';
echo '<tr>';
echo '<td style="vertical-align: top;">';
echo '<span style="color: #3366FF;"><b>'.$page_title.'</b></span><br>';
echo '</td>';
echo '<td style="vertical-align: top; text-align: right;">';
echo "[ <a href='$home_link'>Home</a> ]<br>";
echo '</td></tr></tbody></table>';

echo printColoredLine('#3366FF', '2px').'<pre></pre>';

echo '[ <a href="'.$list_mine.'">My Chemicals</a> ] ';
echo '[ <a href="'.$list_all.'">List All</a> ] ';
echo '[ <a href="'.$list_bycategory.'">List All By Category</a> ] ';
echo '[ <a href="'.$list_bylocation.'">List All By Location</a> ] ';

// add the search form
echo '<form name="form1" action="'.$search_target.'" method="post">';
echo '<input type="hidden" name="search_chemicals_form" value="posted">';
echo '<br>';

// add the table
echo '<table style="background-color: rgb(225, 255, 255); text-align: left; width: 100%;" border="1" cellpadding="2"
cellspacing="0"><tbody>';

echo '<tr>';
echo '<td style="vertical-align: top;">';
echo '<small><span style="font-weight: bold; color: rgb(0, 0, 102);">Search By</span></small></td>';
echo '<td style="vertical-align: top;"><small>';

echo '<input type="radio" value="id" name="searchby">
<span style="font-weight: bold; color: rgb(255, 153, 0);">Chem ID</span> ';

echo '<input type="radio" value="name" name="searchby" checked="checked">
<span style="font-weight: bold; color: rgb(255, 153, 0);">Name</span> ';

echo '<input type="radio" value="cas" name="searchby">
<span style="font-weight: bold; color: rgb(255, 153, 0);">CAS #</span> ';

echo '<input type="radio" value="location" name="searchby">
<span style="font-weight: bold; color: rgb(255, 153, 0);">Location :</span> ';
echo '<select name="location" size="1">';
foreach($locations as $location) {
    echo '<option value="'.$location.'">'.$location.'</option>';
}
echo '</select> '; 
echo '<INPUT type="button" value="Location List" 
onClick="window.open(\''.$location_link.'\',\'locations\',\'width=500,height=600,location=no,resizable=yes,scrollbars=yes\')">
</small></td></tr>';

echo '<tr>';
echo '<td style="vertical-align: top;">';
echo '<small><span style="font-weight: bold; color: rgb(0, 0, 102);">Search For</span></small></td>';
echo '<td style="vertical-align: top;">';
echo '<input type="text" name="searchterm" size="65"> ';
echo '<input type="submit" value="Search" 
style="background: rgb(238, 238, 238); color: #3366FF"> ';
echo '</small></td></tr>';
echo '</tbody></table>';
echo '</form>';

// add the add chemical form if useris != guest
if($user->role == 'guest') {
    echo '</body></html>';
    return;
}

printColoredLine('#3366FF', '1px');

echo '<br><b><span style="color: #3366FF;">Add New Entry</span></b>';

if(!empty($chem_id)) {
    echo '<small>';
    if($message == 'delete') {
	echo ' || <span style="font-weight: bold; color: rgb(255, 50, 50);">Message :</span> ';
	echo "Chemical with ID $chem_id deleted from database";
    }
    else {
	$info_link = base_url()."group/chemicals/info&chem_id=$chem_id";
	echo ' || <span style="font-weight: bold; color: rgb(255, 50, 50);">Message :</span> ';
	echo "Chemical added to database with ID \"<a href=\"$info_link\">$chem_id</a>\"";
    }
    echo '</small>';
}

$add_chemical_link = base_url().'group/chemicals/add';
// add the form that allows input of new chemical
echo '<form name="form2" action="'.$add_chemical_link.'" method="post">';
echo '<input type="hidden" name="add_chemical_form" value="posted" >';

echo '<table style="background-color: rgb(225, 255, 255); text-align: left; width: 100%;" border="1" cellpadding="2"
cellspacing="0"><tbody>';

echo '<tr>';
echo '<td style="vertical-align: top;"><small><b>CAS #</b></small><br>
<input type="text" name="cas" size="20"></td> ';
echo '<td colspan="3" rowspan="1" style="vertical-align: top;"><small><b>Name</b></small><br>
<input type="text" name="name" size="65"></td> ';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top;"><small><b>Company</b></small><br>
<input type="text" name="company" size="20"></td> ';
echo '<td style="vertical-align: top;"><small><b>Product ID</b></small><br>
<input type="text" name="productid" size="20"></td> ';
echo '<td style="vertical-align: top;"><small><b>Amount</b></small><br>
<select name="amount" size="1">
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
</select><input type="text" name="units" size="8"></td>';
echo '<td style="vertical-align: top;"><small><b>Status</b></small><br>
<select name="status" size="1">
<option value="In Stock">In Stock</option>
<option value="Out of Stock">Out of Stock</option>
<option value="Ordered">Ordered</option>
<option value="Checked Out">Checked Out</option>
</select></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top;"><small><b>Category</b></small><br>';
echo '<select name="categories[]" size="1">';
foreach($categories as $category) {
    if($category != 'My Chemicals') {
    echo '<option value="'.$category.'">'.$category.'</option>';
    }
}
echo '</select> 
<small>Other : </small><input type="text" name="other_category" size="15"></td>';

echo '<td colspan="3" rowspan="1" style="vertical-align: top;"><small><b>Location</b></small><br>';
echo '<select name="location" size="1">';
foreach($locations as $location) {
    echo '<option value="'.$location.'">'.$location.'</option>';
}
echo '</select> <small>Other : </small>';

echo '<input type="text" name="other_location" size="35" 
value="Location ID, Room #, Description">';
echo '</td></tr>';

echo '<tr>';
echo '<td colspan="2" rowspan="1" style="vertical-align: top;">
<span style="color: rgb(255, 0, 0);"><small><b>Notes : </b></small></span>
<input type="text" name="notes" size="55"></td> ';
echo '<td style="vertical-align: top;">
<input type="checkbox" name="personal" value="personal" checked="checked"><small>Personal Item </small></td>';
echo '<td colspan="1" rowspan="1" style="vertical-align: top; text-align: center;">
<input type="submit" value="Add Chemical" 
style="background: rgb(238, 238, 238); color: #3366FF"> ';
echo '</td></tr>';

echo '</tbody></table>';
echo '</form>';

// add the form that allows transfering of chemicals
if($role == 'admin') {
    echo '<br>'.printColoredLine('#3366FF', '1px');
    echo '<b><span style="color: rgb(51, 102, 255);">Transfer Chemical Ownership</span></b>';

    $transfer_chemical_link = base_url().'group/chemicals/transfer';
    // add the form that allows input of new chemical
    echo '<form name="form3" action="'.$transfer_chemical_link.'" method="post">';
    echo '<input type="hidden" name="transfer_chemical_form" value="posted" >';
    echo '<input type="hidden" name="chem_id" value="-1">';

    echo '<table style="background-color: rgb(225, 255, 255); text-align: left; width: 80%;" border="1" cellpadding="2"
    cellspacing="0"><tbody>';
    echo '<tr>';
    echo '<td style="vertical-align: top;"><small><b>Transfer From : ';
    echo '<select name="from_user" size="1">';
    foreach($users as $user) {
	echo '<option value="'.$user->userid.'">'.$user->name.'</option>';
    }
    echo '</select>';
    echo ' To : <select name="to_user" size="1">';
    echo '<option value="admin">Group Chemical</option>';
    foreach($users as $user) {
	echo '<option value="'.$user->userid.'">'.$user->name.'</option>';
    }
    echo '</b></small></td>';

    echo '<td style="vertical-align: top; text-align: center;">
    <input type="submit" value="Transfer Chemical" 
    style="background: rgb(238, 238, 238); color: #3366FF"></td>';
    echo '</tr>';
    echo '</tbody></table>';
}

echo '</body></html>';