<?php

$home_link = base_url().'group/main';
$title = 'Group Task ( <i>'.$groupTaskName.'</i> )';

echo '<table style="text-align: left; width: 100%;" border="0"
cellpadding="2" cellspacing="2"><tr><td>
<b><span style="color: #3366FF;">'.$title.'</span></b><td>
<td style="text-align: right;">
[ <a href="'.$home_link.'">Home</a> ]
</td></tr></tbody></table>';

echo printColoredLine('#3366FF', '2px').'<pre></pre>';

// add the main table now
echo '<table style="text-align: left; width: 100%;" border="1"
cellpadding="2" cellspacing="0"><tbody>';

echo '<tr><td style="vertical-align: top;">';

echo $taskTable;

echo '</td><td style="width: 34%; vertical-align: top;">';

echo $yearSelector; // display the year selector

echo printColoredLine('#3366FF', '1px').'<br>';

echo $addTaskForm; // display the form to group task

echo printColoredLine('#3366FF', '1px');

$taskpage_link = base_url()."group/grouptask/taskpage?sy=".$selectedYear;
echo "<iframe name=\"iframe1\" width=\"100%\" height=\"400\" src=\"$taskpage_link\" scrolling=\"auto\" frameborder=\"0\">
</iframe>";

echo '</td></tr></tbody></table>';

