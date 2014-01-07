<?php
    $target_link = base_url()."group/instrulog/add";
?>

<form action="<?=$target_link?>" method="POST" class="form-inline" style="margin:0px">
    <input type="hidden" name="add_instrument_form" value="posted">      
    <table class="formTable-compact">
	<tr>
	    <td>
		<label for="instrument" class="control-label">Instrument name :</label>
                <input type="text" name="instrument" class="input-block-level input-medium">
	    </td>
	</tr>
	<tr>
	    <td>
		<label for="manager" class="control-label">Person In Charge :</label>
                <select name="manager" class="input-medium">
		    <?
		    foreach($users as $user) {
			echo '<option value='.$user->userid.'>'.$user->name.'</option>';
		    }
		    ?>
		</select>
	    </td>
	</tr>
	<tr>
	    <td align="center">
		<button type="submit" class="btn btn-primary btn-small">Add</button>
	    </td>
	</tr>
    </table>
</form>
