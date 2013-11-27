<?php

$home_link = base_url().'group/main';
$title = "Instrument Log (<i>$instrument_name</i>)";

// display the main page now
echo "<html>";
echo "<head>";
echo "<title>InstruLog</title>";
echo "</head>";
echo '<body>';
echo '<table style="text-align: left; width: 100%;" border="0"
cellpadding="2" cellspacing="2"><tr><td>
<b><span style="color: #3366FF;">'.$title.'</span></b><td>
<td style="text-align: right;">
[ <a href="'.$home_link.'">Home</a> ]
</td></tr></tbody></table>';

echo printColoredLine('#3366FF', '2px').'<pre></pre>';

// add the main table now
echo '<table style="text-align: left; width: 100%;" border="1"
cellpadding="2" cellspacing="0">
<tbody><tr><td>';

include FCPATH."application/views/group/instrulog/hoursTable.php";

echo '</td><td style="width: 30%; vertical-align: top;">';

include FCPATH."application/views/group/instrulog/calendar.php";

echo printColoredLine('#3366FF', '1px').'<br>';

include FCPATH."application/views/group/instrulog/addInstrumentForm.php";

echo printColoredLine('#3366FF', '1px');

echo "<div style='padding:10px'>".$instrumentsHTML."</div>";

echo '</td></tr></tbody></table>';
echo '</body></html>';