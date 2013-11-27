<?php

$instrulog_id	= $instrument['instrulog_id'];
$manager_id	= $instrument['manager_id'];
$instrumentName	= $instrument['instrument'];
$userid		= $instrument['userid'];
$manager	= $this->user_model->getUser($manager_id);

$link = base_url()."group/instrulog/index/".$instrulog_id;
$delete_link = base_url()."group/instrulog/delete/".$instrulog_id;

echo "<p><a href ='$link' target='_parent'>$instrumentName</a><br>Manager : $manager->name ";
if($session_userid = $userid || $session_role == 'admin') {
    echo "[ <a href =\"$delete_link\" target=\"_parent\">delete</a> ]";
}
echo '</p>';
