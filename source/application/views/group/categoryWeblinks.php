<?php
echo '<span style="font-weight: bold; color: rgb(245, 140, 0);">'.$category.' ::</span><br>
<small><ul style="list-style-type: square;">';

foreach ($weblinkList as $weblinkItem){
    $link_id = $weblinkItem['link_id'];
    $title = $weblinkItem['title'];
    $url = $weblinkItem['url'];

    echo "<li><a href=\"$url\" target=\"_blank\">$title</a> ";

    if($userid == $weblinkItem['userid'] || $role == 'admin') {
	$edit_link = base_url()."group/weblinks/index/".$link_id;
	echo "[ <a href=\"$edit_link\">edit</a> ]";
    }

    if($userid == $weblinkItem['userid'] || $role == 'admin') {
	$delete_link = base_url()."group/weblinks/delete/".$link_id;
	echo "[ <a href=\"$delete_link\">delete</a> ]";
    }
    echo '</li>';
}
echo '</ul></small>';