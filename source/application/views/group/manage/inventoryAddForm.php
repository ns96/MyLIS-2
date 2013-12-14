<?php
    
if($type == 'Chemical') {
  $target_link = base_url().'group/manage/inventory_add_chemical_categories';
}
else { // must be supplies
  $target_link = base_url().'group/manage/inventory_add_supply_categories';
}

// add the table that allows adding of a new user
$cell_color1 = 'rgb(180,200,230)'; // a light blue
$cell_color2 = 'rgb(240,240,240)'; // a light gray

// add table for importing chemical inventory
echo '<form enctype="multipart/form-data" action="'.$target_link.'" method="POST">';

echo '<table style="background-color: rgb(255, 255, 255); width: 100%; text-align: left;"
border="0" cellpadding="1" cellspacing="2"><tbody>';

echo '<tr>';
echo '<td colspan="4" rowspan="1" style="vertical-align: top; text-align: left; background-color: '.$cell_color1.';">
<small><b>Add '.$type.' Categories</b></small></td>';
echo '</tr>';

echo '<tr>';
for($j = 0; $j < 4; $j++) {
  echo '<td style="vertical-align: center; text-align: left; background-color: '.$cell_color2.';">';
  echo '<small><b>'.($j + 1).'<b> <input size="20" name="cat_'.$j.'"></td>';
}
echo '</tr>';

echo '<tr>';
for($j = 4; $j < 8; $j++) {
  echo '<td style="vertical-align: center; text-align: left; background-color: '.$cell_color2.';">';
  echo '<small><b>'.($j + 1).'<b> <input size="20" name="cat_'.$j.'"></td>';
}
echo '</tr>';

echo '<tr>';
echo '<td colspan="4" rowspan="1" style="vertical-align: center; text-align: right; background-color: '.$cell_color2.';">
<input type="submit" value="Add Categories" 
style="background: rgb(238, 238, 238); color: #3366FF"></td>';
echo '</tr>';

echo '</tbody></table></form>';

