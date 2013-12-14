<?php

echo $menuHTML;
    
// add the table that allows adding of a new user
$cell_color1 = 'rgb(180,200,230)'; // a light blue
$cell_color2 = 'rgb(240,240,240)'; // a light gray


$target_link = base_url().'group/manage/locations_add';
echo '<form enctype="multipart/form-data" action="'.$target_link.'" method="POST">';
echo '<input type="hidden" name="task" value="manager_location_add">';

echo '<small><b>Add New Locations :: </b> <i>( All fields required ) </i></small><br>';

// add table allowing for the addig of locations
echo '<table style="background-color: rgb(255, 255, 255); width: 100%; text-align: left;"
border="0" cellpadding="1" cellspacing="2"><tbody>';

echo '<tr>';
echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
<br></td>';
echo '<td style="vertical-align: center; text-align: left; background-color: '.$cell_color1.';"><small>
<b>Location ID</b></small></td>';
echo '<td style="vertical-align: top; text-align: left; background-color: '.$cell_color1.';"><small>
<b>Room #</b> <input name="same_room" value="yes" type="checkbox"> All</small></td>';
echo '<td style="vertical-align: top; text-align: left; background-color: '.$cell_color1.';"><small>
<b>Description</b> <input name="same_description" value="yes" type="checkbox"> Same for All</small></td>';
echo '<td style="vertical-align: top; text-align: left; background-color: '.$cell_color1.';"><small>
<b>Assigned To</b> <input name="same_owner" value="yes" type="checkbox"> Same for All</small></td>';
echo '</tr>';

for($i = 0; $i < 5; $i++) {
  echo '<tr>';

  echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
  <small><b> '.($i + 1).' </b></small></td>';

  echo '<td style="vertical-align: top; background-color: '.$cell_color2.';">
  <input type="text" name="locationid_'.$i.'" size="10"></td>';

  echo '<td style="vertical-align: top; background-color: '.$cell_color2.';">
  <input type="text" name="room_'.$i.'" size="10"></td>';

  echo '<td style="vertical-align: top; background-color: '.$cell_color2.';">
  <input type="text" name="description_'.$i.'" size="30"></td>';

  echo '<td style="vertical-align: top; background-color: '.$cell_color2.';">';
  echo '<select name="owner_'.$i.'" size="1">';
  foreach($currentUsers as $user) {
    $userid = $user->userid;
    $name = $user->name;
    echo '<option value="'.$userid.'">'.$name.'</option>';
  }
  echo '</select><small> or Other </small><input type="text" name="otherowner_'.$i.'" size="15"></td>';

  echo '</tr>';
}
echo '<tr>';
echo '<td colspan="5" rowspan="1" style="vertical-align: top; text-align: right; background-color: '.$cell_color2.';">';
echo '<input type="submit" value="Add Location(s)" 
style="background: rgb(238, 238, 238); color: #3366FF"> </td>';
echo '</tr>';

echo '</form></tbody></table><br>';

// now display table which allows editing of the locations

// add the table that allows adding of a new user
$cell_color1 = 'rgb(180,200,230)'; // a light blue
$cell_color2 = 'rgb(240,240,240)'; // a light gray

// print any errors if any
echo '<small><b>Edit Current Locations ::</b><br>';
echo '<b>'.$lm_error.'</b></small>';

$target_link = base_url().'group/manage/locations_update';
echo '<form enctype="multipart/form-data" action="'.$target_link.'" method="POST">';
echo '<input type="hidden" name="task" value="manager_location_modify">';

echo '<table style="background-color: rgb(255, 255, 255); width: 100%; text-align: left;"
border="0" cellpadding="1" cellspacing="2"><tbody>';

echo '<tr>';
echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
<br></td>';

echo '<td style="vertical-align: center; text-align: left; background-color: '.$cell_color1.';"><small>
<b>Location ID</b></small></td>';
echo '<td style="vertical-align: center; text-align: left; background-color: '.$cell_color1.';"><small>
<b>Room #</b></small></td>';
echo '<td style="vertical-align: center; text-align: left; background-color: '.$cell_color1.';"><small>
<b>Description</b></small></td>';
echo '<td style="vertical-align: center; text-align: left; background-color: '.$cell_color1.';"><small>
<b>Assigned To</b></td>';
echo '</tr>';

foreach($locationList as $array) {
  $location_id = $array['location_id'];
  $room = $array['room'];
  $description = $array['description'];
  $owner = $array['owner'];
  $owner_name = $owner;

  if(isset($users[$owner])) {
    $owner_name = $users[$owner]->name;
  }

  echo '<tr>';
  echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
  <input type="checkbox" name="locationids[]" value="'.$location_id.'"></td>';

  echo '<td style="vertical-align: top; background-color: '.$cell_color2.';"><small><b>
  '.$location_id.'</b></small></td>';

  echo '<td style="vertical-align: top; background-color: '.$cell_color2.';">
  <input type="text" name="room_'.$location_id.'" size="10" value="'.$room.'"></td>';

  echo '<td style="vertical-align: top; background-color: '.$cell_color2.';">
  <input type="text" name="description_'.$location_id.'" size="30" value="'.htmlentities($description).'"></td>';

  echo '<td style="vertical-align: top; background-color: '.$cell_color2.';">';
  echo '<select name="owner_'.$location_id.'" size="1">';
  echo '<option value="'.$owner_name.'">'.$owner_name.'</option>';
  foreach($currentUsers as $cuser) {
    $cuserid = $cuser->userid;
    $cname = $cuser->name;
    echo '<option value="'.$cuserid.'">'.$cname.'</option>';
  }
  echo '</select><small> or Other </small><input type="text" name="otherowner_'.$location_id.'" size="15"></td>';

  echo '</tr>';
}

echo '<tr>';
echo '<td colspan="4" rowspan="1" style="vertical-align: top; background-color: '.$cell_color2.';">
<input type="radio" value="remove" name="modify_task"><small>
<span style="font-weight: bold; color: #cc0000;">Remove Selected</span> 
<input type="radio" value="update" name="modify_task" checked="checked">
<span style="font-weight: bold; color: #cc0000;">Update Selected</span> 
</small></td>';
echo '<td style="vertical-align: center; text-align: right; background-color: '.$cell_color2.';">
<input type="submit" value="Do Selected Task" 
style="background: rgb(238, 238, 238); color: #3366FF"></td>';
echo '</tr>';
echo '</form></tbody></table><br>';

