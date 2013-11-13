<?php

echo '<table style="text-align: left; width: 100%;" border="0"
cellpadding="2" cellspacing="2"><tbody>
<tr>
<td style="background-color: rgb(255,200,200);">
<small><span style="font-weight: bold;">MyLIS Message</span> ('.$message_date.') ';
if(!empty($link)) {
echo "[ <a href=\"$link\" target=\"_blank\">website link</a> ]";
}
echo '</small></td></tr>';
echo '<tr><td><pre>'.$message.'</pre></td></tr>';
echo '</tbody></table>';

printColoredLine('rgb(255,200,200)', '1px');
