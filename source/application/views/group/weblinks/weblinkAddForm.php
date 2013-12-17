<a name="add"></a>
<form action='<?=$target_link?>' method="POST";
    <table style="text-align: left; width: 70%;background-color: rgb(240, 240, 240);" border="1" cellpadding="1" cellspacing="0">
	<tbody>
	    <tr>
		<td style="background-color: rgb(180, 200, 230); width: 25%;">New Link Title : </td>
		<td style="background-color: rgb(180, 200, 230)"><input size="45" name="title" value=""></td>
	    </tr>

	    <tr>
		<td>Website URL : </td>
		<td><input size="45" name="url" value=""></td>
	    </tr>

	    <tr>
		<td>Category : </td>
		<td>
		    <select name="category">
			<?
			foreach($categories as $key => $category) {
			    echo '<option value="'.$key.'">'.$category.'</option>';
			}
			?>
		    </select> or New Category 
		    <input size="20" name="other_category">
		</td>
	    </tr>
	    <tr>
		<td><br></td>
		<td style="text-align: left;"><input value="<?=$title?>" type="submit" style="background: rgb(238, 238, 238); color: rgb(51, 102, 255)"></td>
	    </tr>
	</tbody>
    </table>
</form>
