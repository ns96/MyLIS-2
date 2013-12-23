<?php

$instrulog_id	= $instrument['instrulog_id'];
$manager_id	= $instrument['manager_id'];
$instrumentName	= $instrument['instrument'];
$userid		= $instrument['userid'];
$manager	= $this->user_model->getUser($manager_id);

$link = base_url()."group/instrulog/index/".$instrulog_id;
$delete_link = base_url()."group/instrulog/delete/".$instrulog_id;
?>

<table style='margin-left:8px; margin-top:8px; font-size: 12px;'>
    <tr>
	<td colspan="2">
	    <a href ='$link' target='_parent'><?=$instrumentName?></a>
	</td>
    </tr>
    <tr>
	<td colspan="2">
	    Manager : <?=$manager->name?>
	</td>
	<td>
	    <?if($session_userid = $userid || $session_role == 'admin') {
		echo "<a href ='$delete_link' target='_parent' class='btn btn-danger btn-mini' style='margin-left:10px'>delete</a>";
	    }?>
	</td>
    </tr>
 </table>
<hr style='background-color:grey; margin:5px 2px'>
