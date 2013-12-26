<?php

$page_link = encodeUrl(base_url().'admin/accounts/create');
$edit_link = encodeUrl(base_url().'admin/accounts/edit/'.$account_id);
$home_link = encodeUrl(base_url().'admin/accounts');

echo "<h3 style=\"text-align: center;\">Account \"$account_id\" Added to database.<br><br>";
echo "[ <a href=\"$page_link\">Add An Other</a> ] [ <a href=\"$edit_link\">EDIT</a> ] 
[ <a href=\"$home_link\">OK</a> ]</h3>";
