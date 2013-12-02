<?php

echo '<p style="background-color: rgb(225, 255, 255);">';
echo "<a name='add_date'></a><form action='$target_link' method='POST'>";
echo '<input type="hidden" name="add_date_form" value="posted">';
echo '<input type="hidden" name="gmdate_id" value="'.$gmdate_id.'">';
echo $title.' Meeting Date : ';
echo '<input maxlength="10" size="10" name="gmdate" value="'.$date.'"> ';
echo 'Time : <input maxlength="10" size="12" name="gmtime" value="'.$time.'"> ';
echo 'and Semester : <select name="semester_id">';
if(!empty($gmdate_id)) {
    $semester_id = $gmdate_info['semester_id'];
    $name = $semesters[$semester_id];
    echo '<option value="'.$semester_id.'">'.$name.'</option>';
}
foreach ($semesters as $semester_id => $name ) {
    echo '<option value="'.$semester_id.'">'.$name.'</option>';
}
echo '</select> ';
echo '<input value="'.$title.' Date" type="submit" 
style="background: rgb(238, 238, 238); color: #3366FF"></td></tr>';
echo '</form></p>';
