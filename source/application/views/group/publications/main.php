<?php

// initialize some links
$add_link = encodeURL(base_url()."group/publications/add");
$home_link = encodeURL(base_url()."group/main");

// display the main page now
echo '<table style="text-align: left; width: 100%;" border="0"
cellpadding="2" cellspacing="2"><tr><td>
<big><b><span style="color: #3366FF;">PubTracker</span>
</b></big> '.$status_text.'</td>
<td style="text-align: right;">
[ <a href="'.$add_link.'">Add Publication</a> ] 
[ <a href="'.$home_link.'">Home</a> ]
</td></tr></tbody></table>';

echo printColoredLine('#3366FF', '2px').'<pre></pre>';

// add the publications sorted by who posted the article
echo '<table cellpadding="2" cellspacing="0" border="1" width="100%"><tbody>';
echo '<tr>';
echo '<td style="width: 20%; vertical-align: top;"><font color="#212063"><small><b>Main Group Author</b></small></font><br></td>';
echo '<td style="vertical-align: top;">';
echo '<table cellpadding="2" cellspacing="2" border="0" width="100%"><tbody>';
echo '<tr>';
echo '<td style="width: 55%; vertical-align: top;" bgcolor="#b5cbe7"><font color="#212063"><small><b>Title</b></small></font><br></td>';
echo '<td style="width: 15%; vertical-align: top;" bgcolor="#b5cbe7"><font color="#212063"><small><b>Status</b></small></font><br></td>';
echo '<td style="width: 15%; vertical-align: top;" bgcolor="#b5cbe7"><font color="#212063"><small><b>Date</b></small></font><br></td>';
echo '<td style="width: 15%; vertical-align: top;" bgcolor="#b5cbe7"><font color="#212063"><small><b>Type</b></small></font><br></td>';
echo '</tr>';
echo '</tbody></table>';
echo '</td>';
echo '</tr>';

echo $pubsHTML;

echo '</tbody></table>';
