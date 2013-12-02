<?php

// initialize some links, semester names, and dates
$home_link = base_url()."group/main";
$previous_link = base_url()."group/meetings?year=".($year - 1);
$current_link = base_url()."group/meetings";
$next_link = base_url()."group/meetings?year=".($year + 1);

echo '<table style="text-align: left; width: 100%;" border="0"
cellpadding="2" cellspacing="2"><tr><td>
<b><span style="color: #3366FF">Group Meetings for '.$year.'</span>
</b></td>
<td style="text-align: right;">
[ <a href="'.$previous_link.'">Previous Year</a> ] 
[ <a href="'.$current_link.'">Current</a> ] 
[ <a href="'.$next_link.'">Next Year</a> ] 
[ <a href="'.$home_link.'">Home</a> ]
</td></tr></tbody></table>';

echo printColoredLine('#3366FF', '2px').'<pre></pre>';

echo '<small><b>Select Semester : </b>';
foreach ($semesters as $semester_id => $name) {
    $link = base_url()."group/meetings?semester_id=$semester_id";
    echo '<a href="'.$link.'">'.$name.'</a> | ';
}
echo '( <a href="#add_date">Add Date/Meeting Slot</a> )';
echo '</small><br>';

// add the meetings sorted by date
echo '<table cellpadding="2" cellspacing="0" border="1" width="100%"><tbody>';

echo $semesterHTML;

echo '</tbody></table>';

// display the add slot form
echo $addSlotHTML;

// display the add date form
echo $addDateHTML;

