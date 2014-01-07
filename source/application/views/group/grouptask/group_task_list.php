<div id="group_task_list" style="width: 100%">
<?php

if(count($yearTasks) >= 1) {
    echo "<table>";
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
    
    echo "<tr>";
    echo "<td><a href ='$link' target='_parent'>$task_name</a> ($year)</td>";
    if($session_userid = $userid || $session_role == 'admin') {
	?>
	<td>
	    <div class="btn-group" style="margin-left: 20px">
		<a class="btn dropdown-toggle btn-small" data-toggle="dropdown" href="#">
		    Select Action
		    <span class="caret"></span>
		</a>
		<ul class="dropdown-menu">
		    <!-- dropdown menu links -->
		    <li><a href="<?=$edit_link?>">Edit</a></li>
		    <li><a href="<?=$copy_link1?>">Copy</a></li>
		    <li><a href="<?=$copy_link2?>">Copy to <?=$year+1?></a></li>
		    <li><a href="<?=$delete_link?>">Delete</a></li>
		</ul>
	    </div>
	</td>
	<?
    } else {
	echo "<td></td>";
    }
    echo '</tr>';
  }
  echo "</table>";
} else {
  echo '<span style="color: #cc0000;"><small>
  <b>Alert! Use form above to add a group task.</b></small></span>';
}
?>
    </div>

