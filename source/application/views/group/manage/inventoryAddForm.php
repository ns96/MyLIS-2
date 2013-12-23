<?php
    
if($type == 'Chemical') {
  $target_link = base_url().'group/manage/inventory_add_chemical_categories';
  $bar_color = 'rgb(180,200,230)';
}
else { // must be supplies
  $target_link = base_url().'group/manage/inventory_add_supply_categories';
  $bar_color = '#A1B2CB';
}
?>

<div class="formWrapper">
<table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
    <tbody>
    <tr>
	<td style="background-color: <?=$bar_color?>; width: 25%;">
	    Add <?=$type?> Categories
	</td>
    </tr>
    </tbody>
</table>
<form action="<?=$target_link?>" method="POST" enctype="multipart/form-data" class="form-inline" style="margin-right:10px">
    <input type="hidden" name="add_chemical_form" value="posted" >      
    <table class="formTable">
	<tr>
	    <? for($j = 0; $j < 4; $j++) { ?>
		<td>
		    <?=($j + 1)?>
		    <input type="text" name="cat_<?=$j?>">
		</td>
	    <? } ?>
	</tr>
	<tr>
	    <? for($j = 4; $j < 8; $j++) { ?>
		<td>
		    <?=($j + 1)?>
		    <input type="text" name="cat_<?=$j?>">
		</td>
	    <? } ?>
	</tr>
	<tr>
	    <td colspan="4" style="text-align: right">
		<button type="submit" class="btn btn-primary btn-small">Add Categories</button>
	    </td>
	</tr>
    </table>
</form>
</div>

