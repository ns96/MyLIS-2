<?php
// set the links now
$publication_link = base_url().'group/publications/main';
$add_link = base_url().'group/publications/add';
?>

<div style="text-align: right; margin: 0px 15px"><a href="<?=$publication_link?>">Back to Publication List</a></div>

<div class="formWrapper">
    <form action="<?=$add_link?>" method="POST" class="form-inline">
	<table id="pub_info_table">
	    <tr>
		<td class="pub_info_label">Title :</td>
		<td colspan="4">
		    <input name="title" type="text" class="input-block-level" placeholder="Enter Title Here">
		</td>
	    </tr>
	    <tr>
		<td class="pub_info_label">Author(s) :</td>
		<td colspan="4">
		    <input type="text" name="authors" class="input-block-level" value="<?=$user->name?>">
		</td>
	    </tr>
	    <tr>
		<td colspan="5">&nbsp;</td>
	    </tr>
	    <tr>
		<td class="pub_info_label">Type :</td>
		<td class="pub_info_label">Status :</td>
		<td class="pub_info_label">Start Date :</td>
		<td class="pub_info_label">Last Update :</td>
		<td class="pub_info_label">Deadline :</td>
	    </tr>
	    <tr>
		<td>
		    <select name="type">
			<option value="paper">Paper</option>
			<option value="review">Review</option>
			<option value="research proposal">Research Proposal</option>
			<option value="dissertation">Dissertation</option>
			<option value="patent">Patent</option>
		    </select>
		</td>
		<td>
		    <select name="status">
			<option value="proposed">Proposed</option>
			<option value="in progress">In Progress</option>
			<option value="submitted">submitted</option>
			<option value="published">published</option>
		    </select>
		</td>
		<td>
		    <input type="text" name="start_date" class="input-block-level" value="<?=getLISDate()?>">
		</td>
		<td><input type="text" class="input-block-level" value="n/a"></td>
		<td><input type="text" name="end_date" class="input-block-level" placeholder="mm/dd/yyyy"></td>
	    </tr>
	    <tr>
		<td colspan="5">&nbsp;</td>
	    </tr>
	    <tr>
		<td class="pub_info_label">Abstract :</td>
		<td colspan="4">
		    <textarea name="abstract" placeholder="Enter Abstract Here" class="input-block-level"></textarea>
		</td>
	    </tr>
	    <tr>
		<td class="pub_info_label">Comments :</td>
		<td colspan="4">
		    <textarea name="comments" placeholder="Enter Any Comments Here" class="input-block-level"></textarea>
		</td>
	    </tr>
	    <tr>
		<td colspan="5" style="text-align: center">
		    <button type="submit" class="btn btn-primary btn-small">Add Publication</button>
		</td>
	    </tr>
	</table>
    </form>
</div>
    