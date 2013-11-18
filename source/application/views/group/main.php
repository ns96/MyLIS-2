<?php
  
  $base = base_url()."group/";
  $title = "MyLIS ( ".$group_name." Group)";
  
  // set menu color
  $menu_color = 'rgb(180, 200, 255)';
  
  // initialize some links
  $chemical_link = $base."chemicals";
  $supply_link = $base."supplies";
  $meeting_link = $base."meetings";
  $orderbook_link = $base."orders";
  $pubtracker_link = $base."publications";
  $instrulog_link = $base."instrulog";
  $grouptask_link = $base."grouptask";
  $web_link = $base."weblinks";
  $folder_link = $base."file_folder";
  $manage_link = $base."manage";
  $backup_link = $base."backup";
  $profile_link = $base."accounts/user_profile";
  $group_profile_link = $base."accounts/group_profile";
  $help_link = 'http://docs.google.com/Doc?id=dg5bsrjs_28dqsgkk5m';
  $logout_link = $base."login/logout";
  $message_link = $base."/main/displayMessages";
  $upgrade_link =  $base."/accounts/upgrade";
  
  // display the main page now
  echo "<html>";
  echo "<head>";
  echo "<title>$title</title>";
  echo '<style type="text/css">';
  echo 'body {font: 12px Arial, Times, serif; color: black;}';
  echo '.list1 {padding-left:0px; padding-top: 10px; padding-bottom: 10px; border-bottom: 0px solid gray; color: rgb(50, 50, 50); font: 16px Arial, Times, serif;}';
  echo 'a {color: blue;}';
  echo ".rbg {background-image:url('../images/cbg2.gif')}";
  echo '</style>';
  echo "</head>";
  echo '<body>';
  echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
  echo '<tbody>';
  
  // create title
  echo '<tr CLASS="rbg">';
  echo '<td colspan="2" rowspan="1" style="vertical-align: top;">';
  echo '<h3 style="color: rgb(0, 0, 102);">My Laboratory Information System ';
  //echo '<span style="font-weight: bold; color: rgb(153, 0, 0)"><small>beta</small></span></h3>';
  echo '<span style="font-weight: bold; color: rgb(153, 0, 0)"><small>'.$properties['version_number'].'</small></span></h3>';
  echo '<div style="text-align: right; color: rgb(0, 0, 0);">Welcome : ';
  echo "<span style=\"font-weight: bold;\">".$fullname."</span>";
  echo " | <a href=\"$profile_link\" target=\"iframe1\">My Profile</a>";
  echo " | <a href=\"$group_profile_link\" target=\"iframe1\">Group Research Profile</a>";
  echo " | <a href=\"$logout_link\">Logout<a><br>";
  echo '</div></td></tr>';
  
  // create the navigation menu
  //echo '<td style="width: 25%; background-color: '.$menu_color.'; vertical-align: top;">';
  echo '<td colspan="1" rowspan="2" background="'.$menu_image.'" style="width: 25%; vertical-align: top;">';
  echo '<ul style="list-style-type: square">';

  $show = $properties['show.chemical'];
  if($show == 'yes' && viewLink('chemical', $role)) {
    echo "<li class=\"list1\"><a href=\"$chemical_link\"><b>Chemicals</b></a></li>";
  }
  
  $show = $properties['show.chemical2'];
  if($show == 'yes' && viewLink('chemical2', $role)) {
    $link = $pu->getProperty('chemical2.link');
    $sitename = $pu->getProperty('chemical2.sitename');
    echo "<li class=\"list1\"><a href=\"$link\" target=\"_blank\"><b>$sitename</b></a></li>";
  }
  
  $show = $properties['show.labsupply'];
  if($show == 'yes' && viewLink('labsupply', $role)) {
    echo "<li class=\"list1\"><a href=\"$supply_link\"><b>Supplies</b></a></li>";
  }
  
  $show = $properties['show.groupmeeting'];
  if($show == 'yes' && viewLink('groupmeeting', $role)) {
    echo "<li class=\"list1\"><a href=\"$meeting_link\"><b>Group Meetings</b></a></li>";
  }
  
  $show = $properties['show.orderbook'];
  if($show == 'yes' && viewLink('orderbook', $role)) {
    echo "<li class=\"list1\"><a href=\"$orderbook_link\"><b>Order Book</b></a></li>";
  }
  
  $show = $properties['show.publication'];
  if($show == 'yes' && viewLink('publication', $role)) {
    echo "<li class=\"list1\"><a href=\"$pubtracker_link\"><b>PubTracker</b></a></li>";
  }
  
  $show = $properties['show.instrulog'];
  if($show == 'yes' && viewLink('instrulog', $role)) {
    echo "<li class=\"list1\"><a href=\"$instrulog_link\"><b>Instrument Log</b></a></li>";
  }
  
  // 2/11/08 uncomment once module done
  $show = $properties['show.grouptask'];
  if($show == 'yes' && viewLink('grouptask', $role)) {
    echo "<li class=\"list1\"><a href=\"$grouptask_link\"><b>Group Tasks</b></a></li>";
  }
  
  $show = $properties['show.folder'];
  if($show == 'yes' && viewLink('folder', $role)) {
    echo "<li class=\"list1\"><a href=\"$folder_link\"><b>File Folder</b></a></li>";
  }
  
  $show = $properties['show.weblinks'];
  if($show == 'yes' && viewLink('weblinks', $role)) {
    echo "<li class=\"list1\"><a href=\"$web_link\"><b>Web Links</b></a></li>";
  }
  
  // load any custom code or plugins here
  if(isset($plugins)) {
    foreach($plugins as $pluginItem) {
      echo "<li class=\"list1\"><a href=\"$pluginItem[plugin_link]\"><b>$pluginItem[plugin_name]</b></a></li>";
    }
  }
  
  if($role == 'admin') {
    echo "<li class=\"list1\"><a href=\"$manage_link\"><b>Manage</b></a></li>";
    //debug code 9/29/08
    //echo "<li class=\"list1\"><a href=\"$backup_link\"><b>Backup</b></a></li>";
  }
  
  echo "<li class=\"list1\"><a href=\"$help_link\" target=\"_blank\"><b style=\"color: red;\">Help</b></a></li>";
  echo '</ul></td>';
  
  // now dislay the file usage information
  echo '<td style="vertical-align: top;">';
  echo '<strong>Welcome to the '.$properties['group.name'].' Group site!</strong> 
  <small>'.$storageQuotaText.' [ <a href="'.$upgrade_link.'" target="iframe1">upgrade</a> ]</small><br>';
  
  echo '<hr style="width: 100%; height: 2px;">';
  
  //display any messages and the message form here
  $mheight = 250; // the height of the message area (325 original)
  echo "<iframe name=\"iframe1\" width=\"100%\" height=\"$mheight\" src=\"$message_link\" scrolling=\"auto\" frameborder=\"0\">
  </iframe>";
  
  // add the form for adding messages here
  echo $messageForm;
  
  // display any adds here
  echo '<br>';
  echo $ads_html;
  
  echo '</td></tr>';
  
  // add the row holding the lis version and session id
  echo '<tr>';
  //echo '<td style="vertical-align: top;"></td>';
  echo '<td style="vertical-align: top;">';
  echo "<small><i>MyLIS Version $properties[version]</i>"; //|| Session ID is ".session_id().'<br></small>';
  echo '</td></tr>';
  
  echo '</tbody></table>';
  echo "</body>";
  echo "</html>";