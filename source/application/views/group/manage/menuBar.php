<?php
    
$user_link = base_url()."group/manage/users_main";
$location_link = base_url()."group/manage/locations_main";
$inventory_link = base_url()."group/manage/inventory_main";
$module_link = base_url()."group/manage/modules_main";
$filemanager_link = base_url()."group/manage/filemanager_main";
$groupinfo_link = base_url()."group/manage/groupinfo_main";
$home_link = base_url()."group/main";;

echo '<table style="text-align: left; width: 100%;" border="0"
cellpadding="2" cellspacing="0"><tbody><tr>';

if($page == 'user') {
  echo '<td style="text-align: center; background-color: rgb(109, 132, 180);">
  <span style="color: rgb(255, 255, 255); font-weight: bold;">Users</span></td>';
}
else {
  echo '<td style="text-align: center;"><a
  style="color: rgb(0, 0, 0);" href="'.$user_link.'">
  <span style="font-weight: bold;">Users</span></a></td>';
}

if($page == 'location') {
  echo '<td style="text-align: center; background-color: rgb(109, 132, 180);">
  <span style="color: rgb(255, 255, 255); font-weight: bold;">Locations</span></td>';
}
else {
  echo '<td style="text-align: center;"><a
  style="color: rgb(0, 0, 0);" href="'.$location_link.'">
  <span style="font-weight: bold;">Locations</span></a></td>';
}

if($page == 'inventory') {
  echo '<td style="text-align: center; background-color: rgb(109, 132, 180);">
  <span style="color: rgb(255, 255, 255); font-weight: bold;">Inventory</span></td>';
}
else {
  echo '<td style="text-align: center;"><a
  style="color: rgb(0, 0, 0);" href="'.$inventory_link.'">
  <span style="font-weight: bold;">Inventory</span></a></td>';
}

if($page == 'module') {
  echo '<td style="text-align: center; background-color: rgb(109, 132, 180);">
  <span style="color: rgb(255, 255, 255); font-weight: bold;">Modules</span></td>';
}
else {
  echo '<td style="text-align: center;"><a
  style="color: rgb(0, 0, 0);" href="'.$module_link.'">
  <span style="font-weight: bold;">Modules</span></a></td>';
}

/*if($page == 'filemanager') {
  echo '<td style="text-align: center; background-color: rgb(109, 132, 180);">
  <span style="color: rgb(255, 255, 255); font-weight: bold;">File Manager</span></td>';
}
else {
  echo '<td style="text-align: center;"><a
  style="color: rgb(0, 0, 0);" href="'.$filemanager_link.'">
  <span style="font-weight: bold;">File Manager</span></a></td>';
}*/

if($page == 'group') {
  echo '<td style="text-align: center; background-color: rgb(109, 132, 180);">
  <span style="color: rgb(255, 255, 255); font-weight: bold;">Group Information</span></td>';
}
else {
  echo '<td style="text-align: center;"><a
  style="color: rgb(0, 0, 0);" href="'.$groupinfo_link.'">
  <span style="font-weight: bold;">Group Information</span></a></td>';
}

echo '<td style="text-align: center;"><a
style="color: rgb(0, 0, 0);" href="'.$home_link.'">
<span style="font-weight: bold;">Home</span></a></td>';

echo '</tr></tbody></table>';
printColoredLine('rgb(109, 132, 180)', '2px');
echo '<pre></pre>'; // add some spacing

