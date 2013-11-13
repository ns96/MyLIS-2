<?php

echo '<table style="text-align: left; width: 100%;" border="0"
cellpadding="2" cellspacing="2"><tbody>
<tr>
<td style="background-color: rgb(100,255,100);"><small><span style="font-weight: bold;">Warning! File Storage Quota Exceeded</span></small></td>
</tr><tr><td><small>File quota of <b>'.$quota.'MB</b> has been exceeded preventing anymore 
files from being uploaded. This can be corrected in the following ways:<br>
<div style="margin-left: 20px;">
1. Delete your unneeded files.<br>
2. Link to files on an external web server from now on.<br>
3. Upgrade to a premium account for an 
<span style="font-weight: bold;">immediate</span> increase in storage up 
to <b>5GB</b> <a href="'.$sales_link.'">here</a>.</div></small>';
printColoredLine('rgb(100,255,100)', '2px');
echo '</td></tr></tbody></table>';
