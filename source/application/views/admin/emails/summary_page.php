<?php

echo $mainHTML;

$i = count($institutions);
echo '<fieldset style="font-weight: bold; color: #3366FF;">';
echo '<legend>Summary of Imported E-mails</legend>';
echo "<span style='font-weight: bold; color: #cc0000 ;'>
$total E-mails for $i institutions <small>
[ <a href='$filename' target='_blank'>Download Text File</a> ]</small>
</span><br><br>
<span style=\"font-weight: normal; color: black;\">$text </span>";
echo '</fieldset>';
