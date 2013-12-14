<?php

$months = getMonths();
$manager = getUser($grouptask_info['manager_id']);

$ismanager = false;
if($role == 'admin' || $userid == $grouptask_info['manager_id']) {
  $ismanager = true;
}

// some javascript code
echo '<script language="Javascript">
<!--Hide script from older browsers

function updateList(task) {
  document.forms.monthlyform.task2.value = task;
  document.forms.monthlyform.submit();
}
// End hiding script from older browsers-->              
</script>';

$target_link = base_url().'group/grouptask/updateTasks';
$print_page_link = base_url().'group/grouptask/printable';

echo "<form name=\"monthlyform\" action=\"$target_link\" method=\"POST\">";
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

echo $monthlyFields;

echo '</td></tr>';
echo '<tr align="right"><td colspan="2" rowspan="1">';

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

