<?php

echo '<a name="add"></a>'; // used to find it
echo "<form action='".$target_link."' method=\"POST\">";

echo '<table style="text-align: left; width: 70%; 
background-color: rgb(240, 240, 240);" border="1" cellpadding="1" cellspacing="0"><tbody>';

echo '<tr><td style="background-color: rgb(180, 200, 230); width: 25%;">New Link Title : </td>
<td style="background-color: rgb(180, 200, 230)"><input size="45" name="title" value=""></td></tr>';

echo '<tr><td>Website URL : </td>
<td><input size="45" name="url" value=""></td></tr>';

echo '<tr><td>Category : </td><td><select name="category">';

foreach($categories as $key => $category) {
    echo '<option value="'.$key.'">'.$category.'</option>';
}

echo '</select> or New Category 
<input size="20" name="other_category"></td></tr>';

echo '<tr><td><br></td>
<td style="text-align: left;"><input value="'.$title.'" type="submit" 
style="background: rgb(238, 238, 238); color: rgb(51, 102, 255)"></td></tr>';

echo '</tbody></table></form>';
