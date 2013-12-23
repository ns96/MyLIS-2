<?php

echo $menuHTML;

$cell_color1 = 'rgb(180,200,230)'; // a light blue
$cell_color2 = 'rgb(240,240,240)'; // a light gray
$target_link = base_url().'group/manage/groupinfo_main';

$site_manager = $this->properties['site.manager'];
if(isset($users[$site_manager])) {
  $smn = $users[$site_manager];
  $site_manager_name = $smn->name;
}
else {
  $site_manager_name = 'No One Selected';
}
?>

<div style="text-align: left; margin-left:15px">
    <button class="btn btn-mini" type="button" id="groupstatus">Show Current Status</button>
</div>
<script type="text/javascript">
    $("groupstatus").onClick(function(){
	
    });
</script>
<div style="margin:5px 15px; display: none"> 
    <table class="status_table">
	<thead>
	    <th>Status</th>
	    <th>Cost</th>
	    <th>Max users</th>
	    <th>Storage</th>
	    <th>Activation Date</th>
	    <th>Expiration Date</th>
	</thead>
	<tbody>
	    <tr>
		<td><?=ucfirst($info['status'])?></td>
		<td>$<?=$info['cost']?></td>
		<td><?=$info['max_users']?></td>
		<td><?=$info['storage']?></td>
		<td><?=$info['activate_date']?></td>
		<td><?=$info['expire_date']?></td>
	    </tr>
	</tbody>
    </table>
</div>

<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td style="background-color: rgb(180,200,230); width: 25%;">
		Edit Account Information - <span style="color:grey">Group ID :<?=$account_id?></span>
	    </td>
	</tr>
	</tbody>
    </table>
    <form action="<?=$target_link?>" method="POST" enctype="multipart/form-data" class="form-inline">
	<input type="hidden" name="groupinfo_update_form" value="posted">      
	<table class="formTable">
	    <tr>
		<td><label for="fname" class="control-label">PI Name :</label></td>
		<td>
		    <table style="font-size:12px">
			<tr>
			    <td>First name :</td>
			    <td>
				<input type="text" name="fname" class="input-medium" value="<?=htmlentities($info['pi_fname'])?>">
			    </td>
			</tr>
			<tr>
			    <td> Middle Name:</td>
			    <td>
				<input type="text" name="mi" class="input-medium" value="<?=$info['pi_mi']?>">
			    </td>
			</tr>
			<tr>
			    <td>Last name :</td>
			    <td>
				<input type="text" name="lname" class="input-medium" value="<?=htmlentities($info['pi_lname'])?>">
			    </td>
			</tr>
		    </table>
		</td>
		<td><label for="group_name" class="control-label">Group Name :</label></td>
		<td>
		    <input type="text" name="group_name" class="input-large" value="<?=htmlentities($info['group_name'])?>">
		</td>
	    </tr>
	    <tr>
		<td><label for="group_type" class="control-label">Group @ :</label></td>
		<td>
		    <select name="group_type" class="input-large" >
		    <option><?=$info['group_type']?></option>
		    <?
		    foreach($this->gtypes as $gtype) {
			echo "<option>$gtype</option>";
		    }
		    ?>
		    </select>
		</td>
		<td><label for="discipline" class="control-label">Group @ :</label></td>
		<td>
		    <select name="discipline" class="input-large" >
			<option><?=$info['discipline']?></option>
			<?
			foreach($this->disciplines as $discipline) {
			    echo "<option>$discipline</option>";
			}
			?>
		    </select>
		</td>
	    </tr>
	    <tr>
		<td><label for="institution_name" class="control-label">Institution Name :</label></td>
		<td>
		    <input type="text" name="institution_name" class="input-large" value="<?=$info['institution']?>">
		</td>
		<td><label for="phone" class="control-label">Phone :</label></td>
		<td>
		    <input type="text" name="phone" class="input-large" value="<?=$info['phone']?>">
		</td>
	    </tr>
	    <tr>
		<td><label for="fax" class="control-label">Fax :</label></td>
		<td>
		    <input type="text" name="fax" class="input-large" value="<?=$info['fax']?>">
		</td>
		<td><label for="email" class="control-label">PI's E-mail :</label></td>
		<td>
		    <input type="text" name="email" class="input-large" value="<?=$info['email']?>">
		</td>
	    </tr>
	    <tr>
		<td><label for="address" class="control-label">Address :</label></td>
		<td>
		    <textarea name="address" class="input-block-level"><?=$info['address']?></textarea>
		</td>
		<td><label for="address" class="control-label">Site Manager :</label></td>
		<td>
		    <select name="site_manager">
		    <option value="<?=$site_manager?>"><?=$site_manager_name?></option>
		    <?
		    foreach($users as $user) {
			$userid = $user->userid;
			$name = $user->name;
			echo '<option value="'.$userid.'">'.$name.'</option>';
		    }
		    ?>
		    </select>
		</td>
	    </tr>
	    <tr>
		<td colspan="4" style="text-align:center; padding-top:6px">
		    <button type="submit" class="btn btn-primary btn-small">Update Information</button>
		</td>
	    </tr>
	</table>
    </form>
</div>


