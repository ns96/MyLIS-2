<?php

// initialize some links
$add_link = encodeURL(base_url()."group/publications/add");
$home_link = encodeURL(base_url()."group/main");

// display the main page now
echo '<div style="text-align:right; margin-bottom:5px">[ <a href="'.$add_link.'">Add Publication</a> ]</div>';

// add the publications sorted by who posted the article
?>
<table class="table_header_line" cellpadding="2" cellspacing="0">
    <tbody>
	<td style="width:17%">Main Group Author</td>
	<td style="width:47%">Title</td>
	<td style="width:12%">Status</td>
	<td style="width:12%">Date</td>
	<td style="width:12%">Type</td>
    </tbody>
</table>
<? echo $pubsHTML; ?>
