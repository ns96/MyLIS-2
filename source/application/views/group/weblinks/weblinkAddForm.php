<a name="add"></a>
<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
		Add a New Weblink
	    </td>
	</tr>
	</tbody>
    </table>
    <form action="<?=$target_link?>" method="POST" class="form-inline">    
	<table class="formTable">
	    <tr>
		<td><label for="title" class="control-label">Link Title :</label></td>
		<td><input type="text" name="title" value="" class="input-block-level"></td>
	    </tr>
	    <tr>
		<td><label for="url" class="control-label">Website URL :</label></td>
		<td><input type="text" name="url" value="" class="input-block-level"></td>
	    </tr>
	    <tr>
		<td><label for="category" class="control-label">Category :</label></td>
		<td>
		    <select name="category">
			<?
			foreach($categories as $key => $category) {
			    echo '<option value="'.$key.'">'.$category.'</option>';
			}
			?>
		    </select> or New Category 
		    <input type="text" name="other_category">
		</td>
	    </tr>
	    <tr>
		<td colspan="2">
		    <button type="submit" class="btn btn-primary btn-small">Add Web Link</button>
		</td>
	    </tr>
	</table>
    </form>
</div>

