<?php

echo "<html>";
echo "<head>";
echo "<title>$title</title>";
echo "</head>";
echo '<body>';
echo '<table style="width: 100%; text-align: left;" border="0" cellpadding="2" cellspacing="0">';
echo '<tbody>';
echo '<tr>';
echo '<td style="vertical-align: top;">';
echo '<span style="color: #3366FF;"><b>'.$title.'</b></span><br>';
echo '</td>';
echo '<td style="vertical-align: top; text-align: right;">';
echo "[ <a href=\"$back_link\">Back</a> ]<br>";
echo '</td></tr></tbody></table>';

echo printColoredLine('#3366FF', '2px').'<pre></pre>';

echo $sub_listing_HTML;

echo '</body></html>';