<?php
    $target_link = base_url().'group/grouptask/addEditTask';
?>

<script language="Javascript">
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
</script>

<form action="<?=$target_link?>" method="POST" class="form-inline" style="margin-right:10px; font-size: 15px;">
    <input type="hidden" name="task" value="grouptask_addtask">
    <input type="hidden" name="egrouptask_id" value="'.$egrouptask_id.'">
    <input type="hidden" name="selected_year" value="'.$y.'">     
    <table class="formTable-compact">
	<tr>
	    <td width="30%">
		<label for="radio" class="control-label">Task Name :</label>
	    </td>
	    <td>
		<input type="text" name="taskname" class="input-block-level input-medium" value="<?=$task_name?>">
	    </td>
	</tr>
	<tr>
	    <td>
		<label for="tasktype" class="control-label">Type :</label>
	    </td>
	    <? if(empty($egrouptask_id)) { ?>
	    <td>
		<select name="tasktype" onChange="changeTaskType()" class="input-smallmedium">
		    <option value="monthly">Monthly for</option>
		    <option value="list">List of</option>
		</select>
		<input type="text" name="tasknum" class="input-block-level input-mini" value="<?=$y?>">
	    </td>
	    <? } ?>
	</tr>
	<tr>
	    <td>
		<label for="radio" class="control-label">Manager :</label>
	    </td>
	    <td>
		<select name="manager_id" class="input-medium">
		    <?
		    if(!empty($manager_id)) {
			$user = $users[$manager_id];
			echo '<option value="'.$manager_id.'">'.$user->name.'</option>';
		    }
		    foreach($users as $user) {
			echo '<option value='.$user->userid.'>'.$user->name.'</option>';
		    }
		    ?>
		</select>
	    </td>
	</tr>
	<tr>
	    <td colspan="2" style="text-align: center">
		<button type="submit" name="Add" class="btn btn-primary btn-small"><?=$button_title?></button>
	    </td>
	</tr>
    </table>
</form>
