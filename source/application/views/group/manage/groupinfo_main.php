<?php

echo $menuHTML;

$cell_color1 = 'rgb(180,200,230)'; // a light blue
$cell_color2 = 'rgb(240,240,240)'; // a light gray
$target_link = base_url().'group/manage/groupinfo_main';

echo '<form enctype="multipart/form-data" action="'.$target_link.'" method="POST">';
echo '<input type="hidden" name="groupinfo_update_form" value="posted">';

echo '<table style="background-color: rgb(255, 255, 255); width: 100%; text-align: left;"
border="0" cellpadding="1" cellspacing="2"><tbody>';

echo '<tr>';
echo '<td colspan="2" rowspan="1" style="vertical-align: top; text-align: left; background-color: '.$cell_color1.';">
<small><b>Edit Account Information</b></small></td>';
echo '</tr>';

echo '<tr>';
echo '<td width="25%" style="vertical-align: center; background-color: '.$cell_color2.';"><small>
<b>Group ID :</b></small></td>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>
<span style="font-weight: bold; color: #cc0000;">'.$account_id.'</span></td>';
echo '</tr>';

echo '<tr>';
echo '<td width="25%" style="vertical-align: center; background-color: '.$cell_color2.';"><small>
<b>PI Name :</b></small></td>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>
<input type="text" name="fname" size="20" value="'.htmlentities($info['pi_fname']).'">
<input type="text" name="mi" size="2" value="'.$info['pi_mi'].'"> 
<input type="text" name="lname" size="20" value="'.htmlentities($info['pi_lname']).'"></td>';
echo '</tr>';

echo '<tr>';
echo '<td width="25%" style="vertical-align: center; background-color: '.$cell_color2.';"><small>
<b>Group Name :</b></small></td>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>
<input type="text" name="group_name" size="25" value="'.htmlentities($info['group_name']).'"></td>';
echo '</tr>';

echo '<tr>';
echo '<td width="25%" style="vertical-align: center; background-color: '.$cell_color2.';"><small>
<b>Group @ :</b></small></td>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<select size="1" name="group_type">
<option>'.$info['group_type'].'</option>';
foreach($this->gtypes as $gtype) {
  echo "<option>$gtype</option>";
}
echo '</select></td>';
echo '</tr>';

echo '<tr>';
echo '<td width="25%" style="vertical-align: center; background-color: '.$cell_color2.';"><small>
<b>Discipline :</b></small></td>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<select name="discipline"><option>'.$info['discipline'].'</option>';
foreach($this->disciplines as $discipline) {
  echo "<option>$discipline</option>";
}
echo '</select></td>';
echo '</tr>';

echo '<tr>';
echo '<td width="25%" style="vertical-align: center; background-color: '.$cell_color2.';"><small>
<b>Institution Name :</b></small></td>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<input type="text" name="institution_name" size="50" value="'.$info['institution'].'"></td>';
echo '</tr>';

echo '<tr>';
echo '<td width="25%" style="vertical-align: center; background-color: '.$cell_color2.';"><small>
<b>Phone :</b></small></td>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<input type="text" name="phone" size="25" value="'.$info['phone'].'"></td>';
echo '</tr>';

echo '<tr>';
echo '<td width="25%" style="vertical-align: center; background-color: '.$cell_color2.';"><small>
<b>Fax :</b></small></td>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<input type="text" name="fax" size="25" value="'.$info['fax'].'"></td>';
echo '</tr>';

echo '<tr>';
echo '<td width="25%" style="vertical-align: center; background-color: '.$cell_color2.';"><small>
<b>PI\'s E-mail :</b></small></td>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<input type="text" name="email" size="25" value="'.$info['email'].'"></td>';
echo '</tr>';

echo '<tr>';
echo '<td width="25%" style="vertical-align: top; background-color: '.$cell_color2.';"><small>
<b>Address :</b></small></td>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<textarea rows="3" cols="50" name="address">'.$info['address'].'</textarea></td>';
echo '</tr>';

$site_manager = $this->properties['site.manager'];
if(isset($users[$site_manager])) {
  $smn = $users[$site_manager];
  $site_manager_name = $smn->name;
}
else {
  $site_manager_name = 'No One Selected';
}

echo '<tr>';
echo '<td width="25%" style="vertical-align: top; background-color: '.$cell_color2.';"><small>
<b>Site Manager :</b></small></td>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">';
echo '<select name="site_manager" size="1">';
echo '<option value="'.$site_manager.'">'.$site_manager_name.'</option>';
foreach($users as $user) {
  $userid = $user->userid;
  $name = $user->name;
  echo '<option value="'.$userid.'">'.$name.'</option>';
}
echo '</select></td>';
echo '</tr>';

echo '<tr>';
echo '<td colspan="2" rowspan="1" style="vertical-align: top; text-align: right; background-color: '.$cell_color2.';">';
echo '<input type="submit" value="Update Information" 
style="background: rgb(238, 238, 238); color: #3366FF"></td>';
echo '</tr>';
echo '</tbody></table><form><br>';

// add table holding other information
echo '<table style="background-color: rgb(255, 255, 255); width: 100%; text-align: left;"
border="0" cellpadding="1" cellspacing="2"><tbody>';

echo '<tr>';
echo '<td style="vertical-align: top; text-align: left; background-color: '.$cell_color1.';">
<small><b>Status</b></small></td>';
echo '<td style="vertical-align: top; text-align: left; background-color: '.$cell_color1.';">
<small><b>Cost</b></small></td>';
echo '<td style="vertical-align: top; text-align: left; background-color: '.$cell_color1.';">
<small><b>Max Users</b></small></td>';
echo '<td style="vertical-align: top; text-align: left; background-color: '.$cell_color1.';">
<small><b>Storage</b></small></td>';
echo '<td style="vertical-align: top; text-align: left; background-color: '.$cell_color1.';">
<small><b>Activate Date</b></small></td>';
echo '<td style="vertical-align: top; text-align: left; background-color: '.$cell_color1.';">
<small><b>Expire Date</b></small></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top; text-align: left; background-color: '.$cell_color2.';">
<small>'.ucfirst($info['status']).'</small></td>';
echo '<td style="vertical-align: top; text-align: left; background-color: '.$cell_color2.';">
<small>$'.$info['cost'].'</small></td>';
echo '<td style="vertical-align: top; text-align: left; background-color: '.$cell_color2.';">
<small>'.$info['max_users'].'</small></td>';
echo '<td style="vertical-align: top; text-align: left; background-color: '.$cell_color2.';">
<small>'.$info['storage'].'MB</small></td>';
echo '<td style="vertical-align: top; text-align: left; background-color: '.$cell_color2.';">
<small>'.$info['activate_date'].'</small></td>';
echo '<td style="vertical-align: top; text-align: left; background-color: '.$cell_color2.';">
<small>'.$info['expire_date'].'</small></td>';
echo '</tr>';

echo '</tbody></table>';

