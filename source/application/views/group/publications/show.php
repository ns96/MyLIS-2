<?php

// set the links now
$publication_link = base_url().'group/publications';
$home_link = base_url().'group/main';

echo '<div style="text-align:right"><a href="'.$publication_link.'">Back to Publication List</a></div>';
?>

<div style="margin:15px">
<table id="pub_info_table">
    <tr>
	<td class="pub_info_label">Title :</td>
	<td colspan="4">
	    <input type="text" class="input-block-level" disabled value="<?=$pub['title']?>">
	</td>
    </tr>
    <tr>
	<td class="pub_info_label">Author(s) :</td>
	<td colspan="4">
	    <input type="text" class="input-block-level" disabled value="<?=$pub['authors']?>">
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
	<td><input type="text" class="input-block-level" disabled value="<?=$pub['type']?>"></td>
	<td><input type="text" class="input-block-level" disabled value="<?=$pub['status']?>"></td>
	<td><input type="text" class="input-block-level" disabled value="<?=$pub['start_date']?>"></td>
	<td><input type="text" class="input-block-level" disabled value="<?=$pub['modify_date']?>"></td>
	<td><input type="text" class="input-block-level" disabled value="<?=$pub['end_date']?>"></td>
    </tr>
    <tr>
	<td colspan="5">&nbsp;</td>
    </tr>
    <tr>
	<td class="pub_info_label">Abstract :</td>
	<td colspan="4"><input type="text" class="input-block-level" disabled value="<?=$pub['abstract']?>"></td>
    </tr>
    <tr>
	<td class="pub_info_label">Comments :</td>
	<td colspan="4"><input type="text" class="input-block-level" disabled value="<?=$pub['comments']?>"></td>
    </tr>
</table>
    
<table id="pub_info_filetable">
    <thead>
	<th width="5%">File</th>
	<th width="85%">Description</th>
	<th>Get</th>
    </thead>
    <tbody>
	<?
	if(count($fileData)>0) {
	    $i = 1;
	    foreach ($fileData as $fileItem) {
		echo '<tr>';
		echo '<td>'.$i.'</td>';
		echo '<td>'.$fileItem['info']['description'].'</td>';
		echo '<td>[ <a href="'.$fileItem['link'].'">'.$fileItem['info']['type'].'</a> ]</td>';
		echo '</tr>';
		$i++;
	    }
	} else {
	    echo '<tr>';
	    echo '<td>0</td>';
	    echo '<td>No Files Found</td>';
	    echo '<td><br></td>';
	    echo '</tr>';
	}
	?>
    </tbody>
</table> 
    
</div>

<form method="POST" action ="<?=base_url()?>group/publications/edit/<?=$pub_id?>">
    <div style="text-align: center">
	<?
	if($pub['userid'] == $session_userid || $session_role == 'admin') {
	    echo '<button type="submit" class="btn btn-success btn-small">Edit Publication</button>';
	}?>
    </div>
</form>
