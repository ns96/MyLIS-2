<?php

$edit_link = base_url().'group/chemicals/edit';
$location_link = base_url()."group/chemicals/list_locations"; 

$back_link = base_url().'group/chemicals/view?chem_id='.$chem_id;
$back_image = base_url()."images/icons/back.png";
?>
<div style="margin:0px 15px;">
<table style="width: 100%; text-align: left; margin-bottom: 5px; font-size: 14px" border="0" cellpadding="2" cellspacing="0">
    <tbody>
	<tr>
	    <td align="left">
		<!-- another image link can be place on the left side -->
	    </td>
	    <td style="vertical-align: top; text-align: right;">
		<a href='<?=$back_link?>'><img src='<?=$back_image?>' class='icon' title='back'/></a><br>
	    </td>
	</tr>
    </tbody>
</table>
</div>

<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
		Edit Entry ( Chem ID : <span style="color: rgb(235, 0, 0);"><b><?=$chem_id?></b></span>)
	    </td>
	</tr>
	</tbody>
    </table>
    <form action="<?=$edit_link?>" method="POST" class="form-inline" style="margin-right:10px">
	<input type="hidden" name="edit_chemical_form" value="posted">
	<input type="hidden" name="chem_id" value="<?=$chem_id?>">   
	<table class="formTable">
	    <tr>
		<td>
		    <label for="cas" class="control-label">CAS #</label><br>
		    <input type="text" name="cas" class="input-block-level" value="<?=$cas?>">
		</td>
		<td>
		    <label for="name" class="control-label">Name :</label><br>
		    <input type="text" name="name" class="input-block-level" value="<?=htmlentities($name)?>">
		</td>
	    </tr>
	    <tr>
		<td>
		    <label for="company" class="control-label">Company :</label><br>
		    <input type="text" name="company" class="input-block-level" value="<?=htmlentities($company)?>">
		</td>
		<td>
		    <label for="productid" class="control-label">Product ID :</label><br>
		    <input type="text" name="productid" class="input-block-level" value="<?=$product_id?>">
		</td>
		<td style="vertical-align: bottom">
		     <label for="amount" class="control-label">Amount :</label>
		     <select name="amount" class="input-mini">
			<option value="<?=$amount?>"><?=$amount?></option>
			<option value="1">1x</option>
			<option value="2">2x</option>
			<option value="3">3x</option>
			<option value="4">4x</option>
			<option value="5">5x</option>
			<option value="6">6x</option>
			<option value="7">7x</option>
			<option value="8">8x</option>
			<option value="9">9x</option>
			<option value="10">10x</option>
		    </select>
		     <input type="text" name="units" class="input-small" value="<?=$units?>">
		</td>
		<td style="vertical-align: bottom">
		    <label for="status" class="control-label">Status :</label>
		    <select name="status" class="input-medium">
			<option value="<?=$status?>"><?=$status?></option>
			<option value="In Stock">In Stock</option>
			<option value="Out of Stock">Out of Stock</option>
			<option value="Ordered">Ordered</option>
			<option value="Checked Out">Checked Out</option>
		    </select>
		</td>
	    </tr>
	    <tr>
		<td colspan="2">
		    <label for="categories" class="control-label">Category :</label><br>
		    <table style="width: 100%">
			<tr>
			    <td>
				<select name="categories[]" class="input-medium">
				    <option value="<?=$category?>"><?=$category?></option>
				    <?
				    foreach($categories as $category) {
					if($category != 'My Chemicals') {
					echo '<option value="'.$category.'">'.$category.'</option>';
					}
				    }
				    ?>
				</select>
			    </td>
			    <td>Other :</td>
			    <td><input type="text" name="other_category" class="input-block-level"></td>
			</tr>
		    </table>
		</td>
		<td colspan="2">
		    <label for="location" class="control-label">Location : [ <a href="<?=$location_link?>" target="_blank">Location List</a> ]</label><br>
		    <table style="width: 100%">
			<tr>
			    <td>
				<select name="location" class="input-smallmedium">
				    <option value="<?=$location_id?>"><?=$location_id?></option>
				    <?
				    foreach($locations as $location) {
					echo '<option value="'.$location['location_id'].'">'.$location['location_id'].'</option>';
				    }
				    ?>
				</select>
			    </td>
			    <td>Other :</td>
			    <td>
				<input type="text" name="other_location" placeholder="Location ID, Room #, Description">
			    </td>
			</tr>
		    </table>
		</td>
	    </tr>
	    <tr>
		<td colspan="2">
		    <label for="notes" class="control-label">Notes :</label><br>
		    <textarea name="notes" class="input-block-level"><?=htmlentities($notes)?></textarea>
		</td>
		<td style="text-align: center">
		    <? if($owner == $userid) {
			echo '<input type="checkbox" name="personal" value="personal" checked="checked">';
		    }
		    else {
			echo '<input type="checkbox" name="personal" value="personal">';
		    } ?>
		    Personal Item
		</td>
		<td style="text-align: center">
		    <button type="submit" class="btn btn-primary btn-small">Update Info</button>
		</td>
	    </tr>
	</table>
    </form>
</div>

