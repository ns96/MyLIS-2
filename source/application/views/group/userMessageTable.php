<?php

echo '<table style="text-align: left; width: 100%;" border="0"
cellpadding="2" cellspacing="2"><tbody>
<tr>
<td style="background-color: rgb(180,200,230);">
<small><span style="font-weight: bold;">'.$poster->name.'</span> ('.$date.') ';

if(!empty($link)) {
    echo "[ <a href=\"$link\" target=\"_blank\">website link</a> ]";
}

if(!empty($file_link)) {
    echo "[ <a href=\"$file_link\" target=\"_parent\">download file</a> ]";
}

if(!empty($edit_link)) {
    echo "[ <a href=\"$edit_link\" target=\"_parent\">edit</a> ]";
}

if(!empty($delete_link)) {
    echo "[ <a href=\"$delete_link\" target=\"_parent\">delete</a> ]";
}

echo '</small></td></tr>';
echo '<tr><td><pre>'.$message.'</pre></td></tr>';
echo '</tbody></table>';

printColoredLine('rgb(180,200,230)', '1px');
