<?php
$base = base_url()."admin/";
$title = "MyLIS Admin";
  
// set menu back ground color or image
$menu_color = 'rgb(180, 200, 255)';

// initialize some links
$managedb_link = encodeURL($base."managedb");
$accounts_link = encodeURL($base."accounts");
$add_link = encodeURL($base."accounts/create");
$add_test_link = encodeURL($base."accounts/create/test");
$add_sandbox_link = encodeURL($base."accounts/create/sandbox");
$email_list_link = encodeURL($base."emails");
$message_link = encodeURL($base."messages");
$fm_link = encodeURL($base."filemanager");
$update_link = encodeURL($base."accounts/upgrade");
$manage_link = encodeURL($base."manage");
$help_link = encodeURL($base."help");
$profile_link = encodeURL($base."main/profile");
$logout_link = encodeURL($base."login/logout");

// display the main page now
echo "<html>";
echo "<head>";
echo "<title>$title</title>";
echo '<style type="text/css">';
echo 'body {font: 16px Arial, Times, serif; color: black;}';
echo 'ul {list-style-type: square}';
echo 'li {padding-left:0px; padding-top: 10px; padding-bottom: 10px; border-bottom: 0px solid gray; color: rgb(50, 50, 50); font: 16px Arial, Times, serif;}';
echo 'a {color: blue;}';
echo '</style>';
echo "</head>";
echo '<body>';
echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
echo '<tbody>';

// create title
echo '<tr>';
echo '<td colspan="2" rowspan="1" style="vertical-align: top; 
background-color: rgb(230, 255, 230);">';
echo '<h3 style="color: rgb(0, 0, 102);">MyLIS Administrator 1.2</h3>';
echo '<div style="text-align: right;">Welcome : ';
echo "<span style=\"font-weight: bold;\">$fullname</span>";
echo " | <a href=\"$profile_link\">Your Profile</a>";
echo " | <a href=\"$logout_link\">Logout<a><br>";
echo '</div></td></tr>';

// create the navigation menu
echo '<td background="'.$menu_image.'" style="width: 25%; vertical-align: top;">';
echo '<ul>';

if($role == 'admin') {
echo "<li><a href=\"$managedb_link\"><b>Manage DB</b></a></li>";
}
echo "<li><a href=\"$accounts_link\"><b>View Accounts</b></a></li>";
echo "<li><a href=\"$add_link\"><b>Add Account</b></a></li>";

if($role == 'admin') {
echo "<li><a href=\"$email_list_link\"><b>Email List Manager</b></a></li>";
echo "<li><a href=\"$add_test_link\" target=\"_blank\"><b>Add Test Account</b></a></li>";
echo "<li><a href=\"$add_sandbox_link\" target=\"_blank\"><b>Add Sandbox Account</b></a></li>";
echo "<li><a href=\"$message_link\"><b>Message Poster</b></a></li>";
echo "<li><a href=\"$fm_link\"><b>File Manager</b></a></li>";
echo "<li><a href=\"$update_link\"><b>Update Accounts</b></a></li>";
echo "<li><a href=\"$manage_link\"><b>Manage</b></big></a></li>";
}
echo "<li><a href=\"$help_link\"><b style=\"color: red;\">Help</b></a></li>";
echo '</ul></td>';

// now dislay the general message about the LIS system
echo '<td style="vertical-align: top;">';
echo '<strong>Welcome  to the MyLIS Administrator site.</strong><br>';

//show any messages
echo '<hr style="width: 100%; height: 2px;">';
echo 'What\'s new<br><br>
Site going live on 2/14/08<br><br>Use this to manage the MyLIS site';

echo $messages_html;

echo '</td></tr>';

// add the row holding the lis version and session id
echo '<tr>';
echo '<td style="vertical-align: top;"></td>';
echo '<td style="vertical-align: top;">';
echo "<small>MyLIS Administrator Version $properties[version] || Session ID is ".session_id().'<br></small>';
echo '</td></tr>';

echo '</tbody></table>';
echo "</body>";
echo "</html>";
