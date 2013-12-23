<?php

echo $menuHTML;
    
// add the table that allows adding of a new user
$cell_color1 = 'rgb(180,200,230)'; // a light blue
$cell_color2 = 'rgb(240,240,240)'; // a light gray
$target_add_link = base_url().'group/manage/locations_add';
$target_edit_link = base_url().'group/manage/locations_update';
?>

<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
		Add New Locations <span style="font-weight: normal"><i>( All fields required )</i></span>
	    </td>
	</tr>
	</tbody>
    </table>
    <form action="<?=$target_add_link?>" method="POST" class="form-inline"  enctype="multipart/form-data" >
	<input type="hidden" name="task" value="manager_location_add">     
	<table class="formTable">
	    <thead>
		<th></th>
		<th>Location ID</th>
		<th>Room #: <input name="same_room" value="yes" type="checkbox"><span class="normal-text"> All</span></th>
		<th>Description<input name="same_description" value="yes" type="checkbox"><span class="normal-text"> Same for All</span></th>
		<th>Assigned To<input name="same_owner" value="yes" type="checkbox"><span class="normal-text"> Same for All</span></th>
	    </thead>
	    <tbody>
	    <?
	    for($i = 0; $i < 5; $i++) { 
	    ?>
	    <tr>
		<td><?=($i + 1)?></td>
		<td><input type="text" name="locationid_<?=$i?>" class="input-medium"></td>
		<td><input type="text" name="room_<?=$i?>" class="input-medium"></td>
		<td><input type="text" name="description_<?=$i?>" class="input-block-level"></td>
		<td>
		    <select name="owner_<?=$i?>" class="input-medium">
		    <? foreach($currentUsers as $user) {
			$userid = $user->userid;
			$name = $user->name;
			echo '<option value="'.$userid.'">'.$name.'</option>';
		    } ?>
		    </select> or Other 
		    <input type="text" name="otherowner_<?=$i?>" class="input-medium">
		</td>
	    </tr>
	    <? } ?>
	    </tbody>
	</table>
	<button type="submit" class="btn btn-primary btn-small">Add Location(s)</button>
	</form>
</div>

<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
		Edit Current Locations 
		<? if (!empty($lm_error)) echo "<br><small><b>$lm_error</b></small>"; ?>
	    </td>
	</tr>
	</tbody>
    </table>
    <form action="<?=$target_edit_link?>" method="POST" enctype="multipart/form-data" class="form-inline">
	<input type="hidden" name="task" value="manager_location_modify">    
	<table class="formTable">
	    <thead>
		<th></th>
		<th>Location ID:</th>
		<th>Room #:</th>
		<th>Description :</th>
		<th>Assigned To :</th>
	    </thead>
	    <tbody>
	    <?
	    foreach($locationList as $array) {
		$location_id = $array['location_id'];
		$room = $array['room'];
		$description = $array['description'];
		$owner = $array['owner'];
		$owner_name = $owner;

		if(isset($users[$owner])) {
		    $owner_name = $users[$owner]->name;
		}
	    ?>
	    <tr>
		<td><input type="checkbox" name="locationids[]" value="<?=$location_id?>"></td>
		<td><?=$location_id?></td>
		<td><input type="text" name="room_<?=$location_id?>" value="<?=$room?>" class="input-medium"></td>
		<td><input type="text" name="description_<?=$location_id?>" value="<?=htmlentities($description)?>" class="input-block-level"></td>
		<td>
		    <select name="owner_<?=$location_id?>" class="input-medium">
			<option value="<?=$owner_name?>"><?=$owner_name?></option>
			<?
			foreach($currentUsers as $cuser) {
			    $cuserid = $cuser->userid;
			    $cname = $cuser->name;
			    echo '<option value="'.$cuserid.'">'.$cname.'</option>';
			}
			?>
		    </select>
		    or Other
		    <input type="text" name="otherowner_<?=$location_id?>" class="input-medium">
		</td>
	    </tr>
	    <? } ?>
	    <tr>
		<td colspan="4">
		    <input type="radio" value="remove" name="modify_task">
		    Remove Selected
		    <input type="radio" value="update" name="modify_task" checked="checked">
		    Update Selected
		</td>
		<td align="right">
		    <button type="submit" class="btn btn-primary btn-small">Do Selected Task</button>
		</td>
	    </tr>
	    </tbody>
	</table>
    </form>
</div>


