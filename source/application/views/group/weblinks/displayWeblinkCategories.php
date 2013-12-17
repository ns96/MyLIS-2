<?php

// display the main page now
echo "<html>";
echo "<head>";
echo "<title>Web Links</title>";
echo "</head>";
echo '<body>';
echo '<table style="text-align: left; width: 100%;" border="0"
cellpadding="2" cellspacing="2"><tr><td>
<big><b><span style="color: rgb(51, 102, 255);">Web Links</span></b></big><td>
<td style="text-align: right;">
[ <a href="'.$add_link.'">Add New Web Link</a> ] 
[ <a href="'.$myLinks.'">My Web Links</a> ] 
[ <a href="'.$listAll.'">List All</a> ] 
[ <a href="'.$home_link.'">Home</a> ]
</td></tr></tbody></table>';

echo printColoredLine('rgb(51, 102, 255)', '2px').'<pre></pre>';

// display links by categories
echo $linksHTML;

echo printColoredLine('rgb(51, 102, 255)', '2px');
echo '<pre></pre>';
echo $addForm;

echo '</body></html>';