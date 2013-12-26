<?php

$item_id = $item_info['item_id'];
$completed = $item_info['completed'];

$bgc1 = 'rgb(255, 255, 190)';
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
	<small><b>$month_name</b> 

	<?
	if($completed != 'YES') {
	    $link = base_url()."group/grouptask/setTaskItemCompleted?item_id=$item_id";
	    echo '<a href ="'.$link.'" class="btn btn-success btn-mini" style="margin-left:10px">Mark as completed</a>';
	    ?>
		</div>
	    <input type="text" name="person_<?=$item_id?>" class="input-block-level" value="<?=htmlentities($person)?>">
	    <input type="text" name="note_<?=$item_id?>" class="input-block-level" value="<?=htmlentities($note)?>">
	    <?
	} else { ?>
	    </div>
	    <input type="text" name="person_<?=$item_id?>" class="input-block-level" value="<?=htmlentities($person)?>" disabled="disabled">
	    <input type="text" name="note_<?=$item_id?>" class="input-block-level" value="<?=htmlentities($note)?>" disabled="disabled">
	<? } ?>
	</small>
</div>


