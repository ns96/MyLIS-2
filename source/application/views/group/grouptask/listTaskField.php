<?php
    
$item_id = $item_info['item_id'];
$item_num = $item_info['item_num'];
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

echo '<p style="background-color: '.$bgc.'">';
echo '<input name="item_ids[]" value="'.$item_id.'" type="checkbox">';
echo '<small><b>Entry '.$item_num.'</b> ';

if($completed != 'YES') {
  $link = base_url()."group/grouptask/task_completed?item_id=$item_id";
  $delete_link = base_url()."group/grouptask/delete_task_item?item_id=$item_id";

  echo '( <a href ="'.$link.'">task completed</a> ) ';
  if($ismanager) {
    echo '( <a href ="'.$delete_link.'">delete</a> )';
  }

  echo '<br>';
  echo '<input size="30" name="person_'.$item_id.'" value="'.htmlentities($person).'"><br>';
  echo '<input size="30" name="note_'.$item_id.'" value="'.htmlentities($note).'" 
  style="background-color:'.$bgc.'; border: 1px solid red; color:blue" >';
}
else {
  echo '<br><input size="30" name="person_'.$item_id.'" 
  value="'.htmlentities($person).'" disabled="disabled"
  style="background-color:white;"><br>';
  echo '<input size="30" name="note_'.$item_id.'" 
  value="'.htmlentities($note).'" disabled="disabled" 
  style="background-color:'.$bgc.'; border: 1px solid red; color:blue" >';
}

echo '</small></p>'; 

