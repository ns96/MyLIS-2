<?php

$date = "$s_date[1]/$s_date[0]/$s_date[2]";
$am = "AM ( $date )";
$pm = "PM ( $date )";
$update_link = base_url()."group/instrulog/update/".$instrument_id;
?>
<form action='<?=$update_link?>' method='POST' class="form-inline">
<input type="hidden" name="update_reservations_form" value="posted">
<input type="hidden" name="date" value="<?=$date?>">

<table style="text-align: left; width: 100%; font-size: 14px" border="1" cellpadding="2" cellspacing="0">
    <tbody>
	<tr>
	    <td style="width: 50%; vertical-align: top; background-color: #b5cbe7; padding: 3px" >
		<small><b><?=$am?></b></small>
	    </td>
	    <td style="width: 50%; vertical-align: top; background-color: #b5cbe7; padding: 3px">
		<small><b><?=$pm?></b></small>
	    </td>
	</tr>
	<tr>
	    <td style="width: 50%; vertical-align: top;">
		<?=$fieldsHTML1?>
	    </td>
	    <td style="width: 50%; vertical-align: top;">
		<?=$fieldsHTML2?>
	    </td>
	</tr>
	<tr>
	    <td  style="text-align: left; border-right:0px; padding:3px 10px">
		<small><b>
		<input name="alltimes" value="yes" type="checkbox" style="margin-top: 0px">
		Reserve all free times/day 
		</small></b>
	    </td>
	    <td style="text-align: right; border-left:0px; padding: 3px 10px">
		<button type="submit" class="btn btn-primary btn-small">Update Reserve Times</button>
	    </td>
	</tr>
    </tbody>
</table>

</form>
	    