<?php

// initialize some links
if (!empty($file_id)){
    $add_link = base_url().'group/file_folder#add'; // the add link
} else {
    $add_link = '#add'; // the add link
}
$myfiles_link = encodeURL(base_url()."group/file_folder?filter=myfiles"); // list only the users web links
$allfiles_link = encodeURL(base_url()."group/file_folder"); // list all links
$home_link = encodeURL(base_url()."group/main");

// display the main page now
echo '<table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2"><tr>
<td style="text-align: right;">
[ <a href="'.$add_link.'">Add New File</a> ] 
[ <a href="'.$myfiles_link.'">My Files</a> ] 
[ <a href="'.$allfiles_link.'">List All</a> ] 
</td></tr></tbody></table>';


echo $linksHTML;

echo $addForm;


