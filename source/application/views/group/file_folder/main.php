<?php

// initialize some links
$add_link = '#add'; // the add link
$myfiles_link = encodeURL(base_url()."group/file_folder?filter=myfiles"); // list only the users web links
$allfiles_link = encodeURL(base_url()."group/file_folder"); // list all links
$home_link = encodeURL(base_url()."group/main");

// display the main page now
echo '<table style="text-align: left; width: 100%;" border="0"
cellpadding="2" cellspacing="2"><tr><td>
<big><b><span style="color: rgb(51, 102, 255);">File Folder</span></b></big><td>
<td style="text-align: right;">
[ <a href="'.$add_link.'">Add New File</a> ] 
[ <a href="'.$myfiles_link.'">My Files</a> ] 
[ <a href="'.$allfiles_link.'">List All</a> ] 
[ <a href="'.$home_link.'">Home</a> ]
</td></tr></tbody></table>';

echo printColoredLine('rgb(51, 102, 255)', '2px').'<pre></pre>';

echo $linksHTML;

echo '<pre></pre>';
echo printColoredLine('rgb(51, 102, 255)', '2px');
echo '<pre></pre>';

echo $addForm;


