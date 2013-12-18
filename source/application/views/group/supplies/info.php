<?php
$home_link = base_url().'group/main';
echo '<table style="width: 100%; text-align: left;" border="0" cellpadding="2" cellspacing="0">';
echo '<tbody>';
echo '<tr>';
echo '<td align="left">';
echo "<a href='$home_link'><img src='".base_url()."images/icons/home.png' class='icon' title='Home'/></a><br>";
echo '</td>';
echo '<td style="vertical-align: top; text-align: right;">';
echo "<a href=\"$back_link\"><img src='".base_url()."images/icons/back.png' class='icon' title='back'/></a><br>";
echo '</td></tr></tbody></table>';

echo $infoHTML;