<?php

$date = "$s_date[1]/$s_date[0]/$s_date[2]";
$am = "AM ( $date )";
$pm = "PM ( $date )";
$update_link = base_url()."group/instrulog/update/".$instrument_id;

echo "<form action='".$update_link."' method=\"POST\">";
echo '<input type="hidden" name="update_reservations_form" value="posted">';
echo '<input type="hidden" name="date" value="'.$date.'">'; // seletected data

echo '<table style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="0"><tbody><tr>';
echo '<td style="width: 50%; vertical-align: top; background-color: #b5cbe7;" ><small><b>'.$am.'</b></small></td>';
echo '<td style="width: 50%; vertical-align: top; background-color: #b5cbe7;"><small><b>'.$pm.'</b></small></td>';
echo '</tr><tr>';

echo '<td style="width: 50%; vertical-align: top;">';

echo $fieldsHTML1;

echo '</td>';
echo '<td style="width: 50%; vertical-align: top;">';

echo $fieldsHTML2;

echo '</td></tr>';
echo '<tr align="left"><td colspan="2" rowspan="1">';
echo '<input name="alltimes" value="yes" type="checkbox">';
echo 'Reserve all free times/day ';
echo '<input value="Update Reserve Times" type="submit" style="background: rgb(238, 238, 238); color: #3366FF">';
echo '</td></tr>';

echo '</tbody></table></form>';
