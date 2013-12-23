<?php

if($type == 'Chemical') {
  $target_link = base_url().'group/manage/inventory_edit_chemical_categories';
  $bar_color = 'rgb(180,200,230)';
}
else { // must be supplies
  $target_link = base_url().'group/manage/inventory_edit_supply_categories';
  $bar_color = '#A1B2CB';
}
?>

<div class="formWrapper">
<table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
    <tbody>
    <tr>
	<td style="background-color:<?=$bar_color?>; width: 25%;">
	    Edit <?=$type?> Categories
	</td>
    </tr>
    </tbody>
</table>
<form action="<?=$target_link?>" method="POST" enctype="multipart/form-data" class="form-inline">
    <input type="hidden" name="add_chemical_form" value="posted" >      
    <table class="formTable">
	<?
	echo '<tr>';
	$i = 1;
	$total = count($categories);
	$x = 4 - $total%4;

	foreach($categories as $key => $value) {
	    echo '<td width="25%">
	    <input type="checkbox" name="catids[]" value="'.$key.'"> 
	    <input type="text" name="cat_'.$key.'" value="'.$value.'" class="input-medium">';
	    if($i < 4) {
		echo '</td>';
		$i++;
	    } else {
		echo '</td></tr><tr>';
		$i = 1;
	    }
	}
	?>
	<tr>
	    <td colspan="3">
		<input type="radio" value="remove" name="modify_task">
		Remove Selected
		<input type="radio" value="update" name="modify_task" checked="checked">
		Update Selected
	    </td>
	    <td style="text-align: right">
		<button type="submit" class="btn btn-primary btn-small">Do Selected Task</button>
	    </td>
	</tr>
    </table>
</form>
</div>


