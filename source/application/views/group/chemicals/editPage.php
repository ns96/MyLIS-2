<?php

$edit_link = base_url().'group/chemicals/edit';

echo "<html>";
echo "<head>";
echo "<title>Edit Chemical Info</title>";
echo "</head>";
echo '<body>';
echo '<form name="form1" action="'.$edit_link.'" method="post">';
echo '<input type="hidden" name="edit_chemical_form" value="posted">';
echo '<input type="hidden" name="chem_id" value="'.$chem_id.'">';

echo '<b><span style="color: #3366FF;">Edit Entry</span></b> ';
echo '( Chem ID : <span style="color: rgb(235, 0, 0);"><b>'.$chem_id.'</b></span>)';


// now show the fields that allow editing of the entries
echo '<table style="background-color: rgb(225, 255, 255); text-align: left; width: 100%;" border="1" cellpadding="2"
cellspacing="0"><tbody>';

echo '<tr>';
echo '<td style="vertical-align: top;"><small><b>CAS #</b></small><br>
<input type="text" name="cas" size="20" value="'.$cas.'"></td> ';
echo '<td colspan="3" rowspan="1" style="vertical-align: top;"><small><b>Name</b><small><br>
<input type="text" name="name" size="65" value="'.htmlentities($name).'"></td> ';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top;"><small><b>Company</b></small><br>
<input type="text" name="company" size="20" value="'.htmlentities($company).'"></td> ';
echo '<td style="vertical-align: top;">Product ID<br>
<input type="text" name="productid" size="20" value="'.$product_id.'"></td> ';
echo '<td style="vertical-align: top;"><small><b>Amount</b><small><br>
<select name="amount" size="1">
<option value="'.$amount.'">'.$amount.'x</option>
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
</select><input type="text" name="units" size="8" value="'.$units.'"></td>';
echo '<td style="vertical-align: top;"><small><b>Status</b></small><br>
<select name="status" size="1">
<option value="'.$status.'">'.$status.'</option>
<option value="In Stock">In Stock</option>
<option value="Out of Stock">Out of Stock</option>
<option value="Ordered">Ordered</option>
<option value="Checked Out">Checked Out</option>
</select></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top;"><small><b>Category</b></small><br>';
echo '<select name="categories[]" size="1">';
echo '<option value="'.$category.'">'.$category.'</option>';
foreach($categories as $category) {
    if($category != 'My Chemicals') {
    echo '<option value="'.$category.'">'.$category.'</option>';
    }
}
echo '</select> ';
echo '<small>Other : </small><input type="text" name="other_category" size="15"></td>';

$location_link = base_url()."group/chemicals/listLocations"; 
echo '<td colspan="3" rowspan="1" style="vertical-align: top;"><small><b>Location</b> 
[ <a href="'.$location_link.'" target="_blank">Location List</a> ]</small><br>';
echo '<select name="location" size="1">';
echo '<option value="'.$location_id.'">'.$location_id.'</option>';
foreach($locations as $location) {
    echo '<option value="'.$location.'">'.$location.'</option>';
}
echo '</select><small> Other : </small>';
echo '<input type="text" name="other_location" size="35"
value="Location ID, Room #, Description"></td>';
echo '</tr>';

echo '<tr>';
echo '<td colspan="2" rowspan="1" style="vertical-align: top;"><span style="color: rgb(255, 0, 0);"><small><b>Notes : </b></small></span>
<input type="text" name="notes" size="55" value="'.htmlentities($notes).'"></td> ';
echo '<td style="vertical-align: top;">';
if($owner == $userid) {
    echo '<input type="checkbox" name="personal" value="personal" checked="checked">';
}
else {
    echo '<input type="checkbox" name="personal" value="personal">';
}
echo '<small>Personal Item </small></td>';
echo '<td colspan="1" rowspan="1" style="vertical-align: top; text-align: center;">';
echo '<input type="submit" value="Update Info" 
style="background: rgb(238, 238, 238); color: #3366FF"> ';
echo '</td></tr>';

echo '</tbody></table></form>';
echo '</body></html>';