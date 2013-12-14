<?php

echo $menuHTML;

// add the table that allows setting whether a module is visible
$cell_color1 = 'rgb(180,200,230)'; // a light blue
$cell_color2 = 'rgb(240,240,240)'; // a light gray
$update_link = base_url().'group/manage/modules_update';
$configure_link = base_url().'group/manage/modules_configure';

echo '<small><b>Select Modules Which Are Displayed ::</b></small><br>';

echo '<form enctype="multipart/form-data" action="'.$update_link.'" method="POST">';
echo '<input type="hidden" name="task" value="manager_module_updatemenu">';

echo '<table style="background-color: rgb(255, 255, 255); width: 100%; text-align: left;"
border="0" cellpadding="1" cellspacing="2"><tbody>';

echo '<tr>';
echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
<small><b>Show</b></small></td>';
echo '<td style="vertical-align: center; text-align: left; background-color: '.$cell_color1.';"><small>
<b>Module Name</b></small></td>';
echo '<td style="vertical-align: center; text-align: left; background-color: '.$cell_color1.';"><small>
<b>Module Description and Options</b></small></td>';
echo '</tr>';

$checked = '';
if($properties['show.chemical'] == 'yes') {
  $checked = 'checked';
}
echo '<tr>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<input type="checkbox" name="chemical" value="yes" '.$checked.'></td>';

echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<span style="color: blue; font-weight: bold;">Chemicals</font></td>';

echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>
Inventory of group chemicals.</small></td>';
echo '</tr>';

$checked = '';
if($properties['show.chemical2'] == 'yes') {
  $checked = 'checked';
  $link = $proputil['chemical2.link'];
  $sitename = $proputil['chemical2.sitename'];
} else {
    $link = '';
    $sitename = '';
}
echo '<tr>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<input type="checkbox" name="chemical2" value="yes" '.$checked.'></td>';

echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<span style="color: blue; font-weight: bold;">Department Chemicals</font></td>';

echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>
Inventory of department chemicals. Please provide name and url of list below.<br>
Name : <input type="text" name="name" size="20" value="'.$sitename.'"> 
URL : <input type="text" name="url" size="30" value="'.$link.'"> 
</small></td>';
echo '</tr>';

$checked = '';
if($properties['show.labsupply'] == 'yes') {
  $checked = 'checked';
}
echo '<tr>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<input type="checkbox" name="labsupply" value="yes" '.$checked.'></td>';

echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<span style="color: blue; font-weight: bold;">Supplies</font></td>';

echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>
Inventory of group supplies.</small></td>';
echo '</tr>';

$checked = '';
if($properties['show.groupmeeting'] == 'yes') {
  $checked = 'checked';
}
echo '<tr>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<input type="checkbox" name="groupmeeting" value="yes" '.$checked.'></td>';

echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<span style="color: blue; font-weight: bold;">Group Meeting</font></td>';

echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>
List of group meetings with attached files.</small></td>';
echo '</tr>';

$checked = '';
if($properties['show.orderbook'] == 'yes') {
  $checked = 'checked';
}
echo '<tr>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<input type="checkbox" name="orderbook" value="yes" '.$checked.'></td>';

echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<span style="color: blue; font-weight: bold;">Order Book</font></td>';

echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>
List of group orders.</small></td>';
echo '</tr>';

$checked = '';
if($properties['show.publication'] == 'yes') {
  $checked = 'checked';
}
echo '<tr>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<input type="checkbox" name="publication" value="yes" '.$checked.'></td>';

echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<span style="color: blue; font-weight: bold;">PubTracker</font></td>';

echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>
List of group publications at various stages of completion.</small></td>';
echo '</tr>';

$checked = '';
if($properties['show.instrulog'] == 'yes') {
  $checked = 'checked';
}
echo '<tr>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<input type="checkbox" name="instrulog" value="yes" '.$checked.'></td>';

echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<span style="color: blue; font-weight: bold;">Instrulog</font></td>';

echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>
Online log book for group instruments.</small></td>';
echo '</tr>';

$checked = '';
if($properties['show.grouptask'] == 'yes') {
  $checked = 'checked';
}
echo '<tr>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<input type="checkbox" name="grouptask" value="yes" '.$checked.'></td>';

echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<span style="color: blue; font-weight: bold;">Group Task</font></td>';

echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>
Set group task for members.</small></td>';
echo '</tr>';

$checked = '';
if($properties['show.folder'] == 'yes') {
  $checked = 'checked';
}
echo '<tr>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<input type="checkbox" name="folder" value="yes" '.$checked.'></td>';

echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<span style="color: blue; font-weight: bold;">File Folder</font></td>';

echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>
A simple file folder for storing important group files.</small></td>';
echo '</tr>';

$checked = '';
if($properties['show.weblinks'] == 'yes') {
  $checked = 'checked';
}
echo '<tr>';
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<input type="checkbox" name="weblinks" value="yes" '.$checked.'></td>';

echo '<td style="vertical-align: center; background-color: '.$cell_color2.';">
<span style="color: blue; font-weight: bold;">Web Links</font></td>';

echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>
A list of websites the group may find usefull.</small></td>';
echo '</tr>';

echo '<tr>';
echo '<td colspan="3" rowspan="1" style="vertical-align: center; text-align: right; background-color: '.$cell_color2.';">
<input type="submit" value="Update Menu items" 
style="background: rgb(238, 238, 238); color: #3366FF"></td>';
echo '</tr>';
echo '</form></tbody></table>';

/** 
  * Display table which allows configuration of various modules
  */

echo '<small><b>Configure Modules ::</b><small><br>';


echo '<form enctype="multipart/form-data" action="'.$configure_link.'" method="POST">';
echo '<input type="hidden" name="task" value="manager_module_updateconfig">';

echo '<table style="background-color: rgb(255, 255, 255); width: 100%; text-align: left;"
border="0" cellpadding="1" cellspacing="2"><tbody>';

echo '<tr>';
echo '<td style="vertical-align: top; background-color: '.$cell_color1.';">
<small><b>Module Name</b></small></td>';
echo '<td style="vertical-align: center; text-align: left; background-color: '.$cell_color1.';"><small>
<b>Module Configuration Options</b></small></td>';
echo '</tr>';

// add configuration options for the order book module
echo '<tr>';
echo '<td style="vertical-align: top; background-color: '.$cell_color2.';">
<span style="color: blue; font-weight: bold;">Order Book</font></td>';

$checked = '';
if((isset($proputil['orders.private']))&&($proputil['orders.private'] == 'yes')) {
  $checked = 'checked';
}
echo '<td style="vertical-align: center; background-color: '.$cell_color2.';"><small>
<input type="checkbox" name="orders_private" value="yes" '.$checked.'> 
Keep individual orders private.<br>';

$checked = '';
if((isset($proputil['orders.notifybuyer']))&&($proputil['orders.notifybuyer'] == 'yes')) {
  $checked = 'checked';
}
echo '<input type="checkbox" name="orders_notifybuyer" value="yes" '.$checked.'> 
Notify buyer that a user as submitted items for odering.<br>';

$checked = '';
if((isset($proputil['orders.notifyuser']))&&($proputil['orders.notifyuser'] == 'yes')) {
  $checked = 'checked';
}
echo '<input type="checkbox" name="orders_notifyuser" value="yes" '.$checked.'> 
Notify users when items they ordered have arrived.<br>';

echo '</small></td>';
echo '</tr>';

echo '<tr>';
echo '<td colspan="2" rowspan="1" style="vertical-align: center; text-align: right; background-color: '.$cell_color2.';">
<input type="submit" value="Update Configuration" 
style="background: rgb(238, 238, 238); color: #3366FF"></td>';
echo '</tr>';

echo '</form></tbody></table>';

