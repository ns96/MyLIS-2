<?php
    
$item_id = $item_info['item_id'];
$item_num = $item_info['item_num'];
$completed = $item_info['completed'];

$bgc1 = '#FFFFC9';
$bgc2 = 'rgb(200, 255, 200)';

// set the background color
if($completed != 'YES') {
  $bgc = $bgc1;
}
else {
  $bgc = $bgc2;
}

$person = '(enter person assigned to task)';
if(!empty($item_info['userid'])) {
  $person = $item_info['userid'];
}

$note = 'Note: ';
if(!empty($item_info['note'])) {
  $note = $item_info['note'];
}
?>

<div class="grouptask_box" style="background-color: <?=$bgc?>">
    <div style="min-height:25px">
	<input name="item_ids[]" value="<?=$item_id?>" type="checkbox">
	<small><b>Entry <?=$item_num?></b> 

	<?
	if($completed != 'YES') {
	    $link = base_url()."group/grouptask/task_completed?item_id=$item_id";
	    $delete_link = base_url()."group/grouptask/delete_task_item?item_id=$item_id";
	    echo '<a href ="'.$link.'" class="btn btn-success btn-mini" style="margin-left:10px">Mark as completed</a>';
	    if($ismanager) {
		echo '<a href ="'.$delete_link.'" class="btn btn-danger btn-mini" style="margin-left:5px">Delete</a>';
	    }
	    ?>
		</div>
	    <input type="text" name="person_<?=$item_id?>" class="input-block-level" value="<?=htmlentities($person)?>">
	    <input type="text" name="note_<?=$item_id?>" class="input-block-level" value="<?=htmlentities($note)?>">
	    <?
	} else { ?>
	    </div>
	    <input type="text" name="person_<?=$item_id?>" class="input-block-level" value="<?=htmlentities($person)?>" disabled="disabled">
	    <input type="text" name="note_<?=$item_id?>" class="input-block-level" value="<?=htmlentities($note)?>" disabled="disabled">
	<? }

echo '</small></div>'; 

