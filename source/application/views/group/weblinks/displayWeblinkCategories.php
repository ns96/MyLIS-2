<?php

echo '<div style="font-size:14px; margin-top:5px; text-align:right">
[ <a href="'.$add_link.'">Add New Web Link</a> ] 
[ <a href="'.$myLinks.'">My Web Links</a> ] 
[ <a href="'.$listAll.'">List All</a> ] 
</div>';

// display links by categories
echo $linksHTML;

echo $addForm;
