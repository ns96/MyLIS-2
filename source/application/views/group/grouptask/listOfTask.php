<?php

$max_num = 0;

// some javascript code
echo '<script language="Javascript">
<!--Hide script from older browsers

function updateList(task) {
  document.forms.listform.task2.value = task;
  document.forms.listform.submit();
}
// End hiding script from older browsers-->              
</script>';

$target_link = base_url().'group/grouptask/updateTasks';
$print_page_link = base_url().'group/grouptask/printable';

echo "<form name='listform' action='$target_link' method='POST'>";
echo '<input type="hidden" name="task" value="grouptask_update">';
echo '<input type="hidden" name="task2" value="">';
echo '<input type="hidden" name="grouptask_id" value="'.$grouptask_id.'">';

echo '<table style="text-align: left; width: 100%;" border="1"
cellpadding="2" cellspacing="0"><tbody><tr>';
echo '<td colspan="2" rowspan="1" style="width: 50%; vertical-align: top; background-color: #b5cbe7;" >
<small><b>Task Manager : <i>'.$manager->name.'</i></b> ';
echo '( <a href="'.$print_page_link.'">Printable View<a> )';
echo '</small></td>';
echo '</tr><tr>';

echo '<td style="width: 50%; vertical-align: top;">';

// printout the list now
echo $listTaskFields;

echo '</td></tr>';
echo '<tr>';

echo '<td align="left" >';

if($ismanager && $count <= 50) {
  echo '<input value="Add" type="button" 
  style="background: rgb(238, 238, 238); color: #3366FF" 
  onClick="updateList(\'add\')"> ';
  echo '<input type="hidden" name="max_num" value="'.$max_num.'">';
  echo '<input type="hidden" name="total" value="'.$count.'"> ';
  echo '<input size="2" name="add_amount" value="1"> ';
  echo '<small>more entry  ( 50 entries max )</small>';
}

echo '<br></td>';

echo '<td align="right">';
if($ismanager) {
  echo '<input value="Reset Selected" type="button" 
  style="background: rgb(238, 238, 238); color: #3366FF" 
  onClick="updateList(\'reset\')"> ';
}

echo '<input value="Update" type="button" 
style="background: rgb(238, 238, 238); color: #3366FF" 
onClick="updateList(\'update_info\')">';

echo '</td></tr>';
echo '</tbody></table></form>';

echo $taskNotesForm;
