<?php

// display the main page now
echo "<html>";
echo "<head>";
echo "<title>$title</title>";
echo '<style type="text/css">';
echo 'body {font: 14px Arial, Times, serif; color: black;}';
echo '</style>';
echo "</head>";
echo "<body>";
echo '<table style="width: 100%; text-align: left;" border="0" cellpadding="2" cellspacing="0">';
echo '<tbody>';
echo '<tr>';
echo '<td style="vertical-align: top;">';
echo '<h3><span style="color: rgb(0, 0, 102);">Edit Message</span><br></h3>';
echo '</td>';
echo '<td style="vertical-align: top; text-align: right;">';
echo "<b>[ <a href=\"$back_link\">Back</a> ] ";
echo "[ <a href=\"$home_link\">Home</a> ]</b><br>";
echo '</td></tr></tbody></table>';
echo '<hr style="width: 100%; height: 2px;">';

// create the table that allows editing message
echo "<form action='".base_url()."admin/messages/edit/$message_id' method='POST'>";
echo '<input type="hidden" name="edit_message_form" value="posted">';

echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
echo '<tbody>';

echo '<tr>';
echo '<td colspan="2" rowspan="1" style="vertical-align: top;"><b>Message # '.$message_id.'</b></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: center;"><b>Accounts : </b></td>';
echo '<td style="vertical-align: top;">
<input type="text" name="accounts" size="75" value="'.$messageItem['account_ids'].'"></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: center;"><b>Post Date : </b></td>';
echo '<td style="vertical-align: top;">
Post From <input type="text" name="post_start" size="10" value="'.$messageItem['post_start'].'"> 
to <input type="text" name="post_end" size="10" value="'.$messageItem['post_end'].'"></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: center;"><b>Website URL : </b></td>';
echo '<td style="vertical-align: top;">
<input type="text" name="url" size="75" value="'.$messageItem['url'].'"></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top;"><b>Message : </b></td>';
echo '<td colspan="3" rowspan="1" style="vertical-align: top;">
<textarea rows="4" cols="65" name="message">'.$messageItem['message'].'</textarea></td>';
echo '</tr>';
echo '</tbody></table>';

echo '<div style="text-align: right;">';
echo '<input type="submit" value="Post Edited Message">';
echo '</div>';
echo '</form>';

echo '</body></html>';
