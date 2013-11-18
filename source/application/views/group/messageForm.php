<?php

echo '<br><br><br>';
printColoredLine('rgb(0,0,100)', '2px');
echo "<form action=\"$target_url\" method=\"POST\" enctype=\"multipart/form-data\">";
echo '<input type="hidden" name="message_id" value="'.$message_id.'">';

echo '<table style="text-align: left; width: 100%; 
background-color: rgb(240,240,240);" border="0" cellpadding="2" cellspacing="2"><tbody>
<tr>
<td style="background-color: rgb(180,200,230); width: 25%;"><small>
<span style="font-weight: bold;">'.$title.'</span></small></td>
<td style="background-color: rgb(180,200,230); text-align: right;"><small>';

if(!empty($messageItem['file_id'])) {
    $file_id = $messageItem['file_id'];
    $delete_link = base_url()."group/messages/deleteMessageFile/".$file_id;
    echo "[ <a href=\"$delete_link\">delete file</a> ]";
}

if(!empty($message_id)) {
    $add_link = base_url()."/group/main";
    echo " [ <a href=\"$add_link\">new message</a> ]";
}

echo '</small></td></tr>';
echo '<tr><td><small>Website Link : </small></td>
<td><input size="50" name="url" value="'.$messageItem['url'].'"></td></tr>
<tr><td style="vertical-align: top;"><small>Message :</small></td>
<td><textarea cols="50" rows="2" name="message">'.$messageItem['message'].'</textarea></td></tr>
<tr><td><small>Attach File (2MB Max)</small></td><td>
<small>'.$this->filemanager->getFileUploadField(1).'</small></td></tr>
<tr><td><br></td><td style="text-align: left;">
<input value="'.$title.'" type="submit" 
style="background: rgb(238, 238, 238); color: rgb(51, 102, 255)"></td></tr>';
echo '</tbody></table></form>';