<?php

$target_link = base_url().'group/grouptask/update_notes';
// display table that allow editing the notes of the text
echo "<form action='$target_link' method='POST'>";
echo '<input type="hidden" name="task" value="grouptask_update_notes">';
echo '<input type="hidden" name="grouptask_id" value="'.$grouptask_id.'">';

echo '<table style="text-align: left; width: 100%;" border="1"
cellpadding="2" cellspacing="0"><tbody>';
echo '<tr>';
echo '<td style="width: 70%; vertical-align: top;">
<textarea cols="50" rows="2" name="notes">';
if(empty($grouptask_info['notes'])) {
  echo 'Enter task notes here ...';
}
else {
  echo $grouptask_info['notes'];
}
echo '</textarea></td>';

echo '<td style="text-align: right; vertical-align: top">';
echo '<input value="Update Task Notes" type="submit" 
style="background: rgb(238, 238, 238); color: #3366FF">';
echo '</td></tr>';

echo '</tbody></table></form>';

