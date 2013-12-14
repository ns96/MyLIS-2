<?php

if($type == 'Chemical') {
  $target_link = base_url().'group/manage/inventory_edit_chemical_categories';
}
else { // must be supplies
  $target_link = base_url().'group/manage/inventory_edit_supply_categories';
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
<small><b>Edit '.$type.' Categories</b></small></td>';
echo '</tr>';

echo '<tr>';
$i = 1;
$total = count($categories);
$adj = '';
$x = 4 - $total%4;
if($x != 4) {
  $adj .= '<td colspan="'.$x.'" rowspan="1" style="vertical-align: center; 
  background-color: '.$cell_color2.';"><br></td>';
}

foreach($categories as $key => $value) {
  echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
  <input type="checkbox" name="catids[]" value="'.$key.'"> 
  <input type="text" name="cat_'.$key.'" size="20" value="'.$value.'">';

  if($i < 4) {
    echo '</td>';
    $i++;
  }
  else {
    echo '</td></tr><tr>';
    $i = 1;
  }
}

// print any empty slots
echo "$adj</tr>";

echo '<tr>';
echo '<td colspan="2" rowspan="1" style="vertical-align: top; background-color: '.$cell_color2.';">
<input type="radio" value="remove" name="modify_task"><small>
<span style="font-weight: bold; color: #cc0000;">Remove Selected</span> 
<input type="radio" value="update" name="modify_task" checked="checked">
<span style="font-weight: bold; color: #cc0000;">Update Selected</span> 
</small></td>';
echo '<td colspan="2" rowspan="1" style="vertical-align: center; text-align: right; background-color: '.$cell_color2.';">
<input type="submit" value="Do Selected Task" 
style="background: rgb(238, 238, 238); color: #3366FF"></td>';
echo '</tr>';

echo '</tbody></table></form>';

