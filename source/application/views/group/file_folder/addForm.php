<?php

if (!empty($file_id)){
    $target_link = base_url().'group/file_folder/editfile';
} else {
    $target_link = base_url().'group/file_folder/addfile';
}
echo '<a name="add"></a>'; // target for link from top
echo "<form action=\"$target_link\" method=\"POST\" enctype=\"multipart/form-data\">";
echo '<input type="hidden" name="task" value="'.$task.'">';
echo '<input type="hidden" name="file_id" value="'.$file_id.'">';

echo '<table style="text-align: left; width: 70%; 
background-color: rgb(240, 240, 240);" border="1" cellpadding="1" cellspacing="0"><tbody>';

echo '<tr><td style="background-color: rgb(180,200,230); width: 25%;">File Title : </td>
<td style="background-color: rgb(180,200,230)"><input size="45" name="title" value="';
if(isset($fileInfo['title'])) {
    echo $fileInfo['title'];
}
echo '"></td></tr>';

echo '<tr><td>Category : </td><td><select name="category">';

if(isset($fileInfo['category_id'])) {
  $cat_id = 'cat_'.$fileInfo['category_id'];
  $category = $categories[$cat_id];
  echo '<option value="'.$cat_id.'">'.$category.'</option>';
}

foreach($categories as $key => $category) {
  echo '<option value="'.$key.'">'.$category.'</option>';
}

echo '</select> or New Category 
<input size="20" name="other_category"></td></tr>';

echo '<tr><td colspan="1" rowspan="2" style="vertical-align: top;"><br></td>
<td>'.$urlUploadField.'</td></tr>';

echo '<tr>
<td style="text-align: left;">
<input value="'.$title.'" type="submit" style="background: rgb(238, 238, 238); color: rgb(51, 102, 255)"> 
<small>(2MB Max)</small></td></tr>';

echo '</tbody></table></form>';
