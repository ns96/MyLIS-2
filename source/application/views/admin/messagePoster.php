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
echo '<h3><span style="color: rgb(0, 0, 102);">Message Poster</span><br></h3>';
echo '</td>';
echo '<td style="vertical-align: top; text-align: right;">';
echo '<b>[ <a href="'.base_url().'admin/main">Home</a> ]</b><br>';
echo '</td></tr></tbody></table>';
echo '<hr style="width: 100%; height: 2px;">';


// create the table holding the add message form
echo '<form action="'.base_url().'admin/messages" method="POST">';
echo '<input type="hidden" name="message_poster_form" value="posted">';

echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
echo '<tbody>';

echo '<tr>';
echo '<td style="vertical-align: center;"><b>Accounts : </b></td>';
echo '<td style="vertical-align: top;">
<input type="text" name="accounts" size="75" value="ALL"></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: center;"><b>Post Date : </b></td>';
echo '<td style="vertical-align: top;">
<input type="checkbox" name="now" checked="checked">Post Immediately or 
Post From <input type="text" name="post_start" size="10" value="'.$post_start.'"> 
to <input type="text" name="post_end" size="10" value="'.$post_end.'"></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: center;"><b>Website URL : </b></td>';
echo '<td style="vertical-align: top;">
<input type="text" name="url" size="75"></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: top;"><b>Message : </b></td>';
echo '<td colspan="3" rowspan="1" style="vertical-align: top;">
<textarea rows="4" cols="65" name="message"></textarea></td>';
echo '</tr>';
echo '</tbody></table>';

echo '<div style="text-align: right;">';
echo '<input type="submit" value="Post Message">';
echo '</div>';
echo '</form>';

if(count($messageList) > 0) {
    foreach($messageList as $messageItem) {
	// initialize some variables and links
	$message_id = $messageItem['message_id'];
	$manager = $accounts[$messageItem['managerid']];
	$edit_link = encodeUrl(base_url()."admin/messages/edit/".$message_id);
	$remove_link = encodeUrl(base_url()."admin/messages/delete/".$message_id);

	echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
	echo '<tbody>';

	echo '<tr>';
	echo '<td style="vertical-align: top; width: 20%;"><b>Message # '.$message_id.'</b></td>';
	echo '<td style="vertical-align: top;">Entered : '.$messageItem['message_date'].' || 
	Manager : '.$manager->name.' || 
	[ <a href="'.$edit_link.'">Edit</a> ] [ <a href="'.$remove_link.'">Remove</a> ]
	</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td style="vertical-align: top; width: 20%;">Accounts</td>';
	echo '<td style="vertical-align: top;">'.$messageItem['account_ids'].'</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td style="vertical-align: top; width: 20%;">Post Date</td>';
	echo '<td style="vertical-align: top;">
	From <span style="color: rgb(255, 0, 0);">'.$messageItem['post_start'].'</span> to 
	<span style="color: rgb(255, 0, 0);">'.$messageItem['post_end'].'</span></td>';
	echo '</tr>';

	$url = $messageItem['url'];
	echo '<tr>';
	echo '<td style="vertical-align: top; width: 20%;">Website URL</td>';
	if(!empty($url)) {
	    echo '<td style="vertical-align: top;"><a href="'.$url.'">'.$url.'</a></td>';
	}
	else {
	    echo '<td style="vertical-align: top;">none</td>';
	}
	echo '</tr>';

	$new_lines = array("\n", "\r\n");
	$message = str_replace($new_lines, "<br>", $messageItem['message']);
	echo '<tr>';
	echo '<td style="vertical-align: top; width: 20%;">Message</td>';
	echo '<td style="vertical-align: top;">'.$message.'</td>';
	echo '</tr>';

	echo '</tbody></table>';
    }
}
else {
    echo 'No messages posted';
}
echo '</body></html>';
