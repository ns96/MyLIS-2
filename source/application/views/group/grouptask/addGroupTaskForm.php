<?php

// some javascript code
echo '<script language="Javascript">
<!--Hide script from older browsers

function changeTaskType() {
  var value = document.forms.form2.tasktype.value;

  if(value == "monthly") {
    document.forms.form2.tasknum.value = '.$y.';
  }
  else if (value == "list") {
    document.forms.form2.tasknum.value = "10";
  }
  else {
    document.forms.form2.tasknum.value = "";
  }
  //alert("This works " + value);
}
// End hiding script from older browsers-->              
</script>';

$target_link = base_url().'group/grouptask/addEditTask';
echo "<form name=\"form2\" action=\"$target_link\" method=\"POST\">";
echo '<input type="hidden" name="task" value="grouptask_addtask">';
echo '<input type="hidden" name="egrouptask_id" value="'.$egrouptask_id.'">';
echo '<input type="hidden" name="selected_year" value="'.$y.'">';

echo '<table style="text-align: left; width: 100%;" border="0" 
cellpadding="2" cellspacing="0"><tbody>';

echo '<tr>';
echo '<td><small><b>Task Name : </b></small></td>';
echo '<td><input maxlength="50" size="22" name="taskname" value="'.$task_name.'"></td>';
echo '</tr>';

if(empty($egrouptask_id)) {
  echo '<tr>';
  echo '<td><small><b>Type : </small></b></td>';
  echo '<td><select name="tasktype" onChange="changeTaskType()">';
  echo '<option value="monthly">Monthly for</option>';
  //echo '<option value="weekly">Weekly</option>';
  echo '<option value="list">List of</option>';
  echo '</select>';
  echo '<input size="4" name="tasknum" value="'.$y.'">';
  echo '</td></tr>';
}

echo '<tr>';
echo '<td><small><b>Manager :</small></b></td><td>';
echo '<select name="manager_id">';
if(!empty($manager_id)) {
  $user = $users[$manager_id];
  echo '<option value="'.$manager_id.'">'.$user->name.'</option>';
}
foreach($users as $user) {
  echo '<option value='.$user->userid.'>'.$user->name.'</option>';
}
echo '</select></td>';
echo '</tr>';

echo '<tr>';
echo '<td><br></td>';
echo '<td><input name="Add" value="'.$button_title.'" type="submit" 
style="background: rgb(238, 238, 238); color: #3366FF">';
echo '</td></tr>';
echo '</tbody></table>';
echo '</form>';

