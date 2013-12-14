<?php

if(count($yearTasks) >= 1) {
  foreach($yearTasks as $array){
    $grouptask_id = $array['grouptask_id'];
    $manager_id = $array['manager_id'];
    $task_name = $array['task_name'];
    $year = $array['year'];
    $userid = $array['userid'];

    $link = base_url()."group/grouptask?grouptask_id=$grouptask_id&sy=$year";
    $edit_link = base_url()."group/grouptask?egrouptask_id=$grouptask_id&sy=$year";
    $copy_link1 = base_url()."group/grouptask/copy_task/1?grouptask_id=$grouptask_id";
    $copy_link2 = base_url()."group/grouptask/copy_task/2?grouptask_id=$grouptask_id&year=".($year + 1);
    $delete_link = base_url()."group/grouptask/delete_task?grouptask_id=$grouptask_id&sy=$year";

    echo "<p><a href =\"$link\" target=\"_parent\">$task_name</a> ($year)<br>";
    if($session_userid = $userid || $session_role == 'admin') {
      echo "[ <a href =\"$edit_link\" target=\"_parent\">edit</a> ] ";
      echo "[ <a href =\"$copy_link1\" target=\"_parent\">copy</a> ] ";
      echo "[ <a href =\"$copy_link2\" target=\"_parent\">copy to ".($year + 1)."</a> ] ";
      echo "( <a href =\"$delete_link\" target=\"_parent\">delete</a> )";
    }
    echo '</p>';
  }
} else {
  echo '<span style="color: #cc0000;"><small>
  <b>Alert! Use form above to add a group task.</b></small></span>';
}

