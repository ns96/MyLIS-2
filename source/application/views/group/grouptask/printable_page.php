<?php

echo "<html>";
echo "<head>";
echo "<title>Group Task Printable View</title>";
echo "</head>";
echo "<body bgcolor=\"white\">";
echo "<b>$task_name</b> ( $year ) ";

echo '<table style="width: 100%; text-align: left;" border="1" cellpadding="2" cellspacing="0">';
echo '<tbody>';

echo '<tr>';

if($type == 'monthly') {
  echo '<td style="vertical-align: top;"><b>Month</b></td>';
}
else if ($type == 'list') {
  echo '<td style="vertical-align: top;"><br></td>';
}

echo '<td style="vertical-align: top;"><b>Assigned To</b></td>';
echo '<td style="vertical-align: top;"><b>Notes<br></b></td>';
echo '<td style="vertical-align: top;"><b>Completed</b><br></td>';
echo '</tr>';

foreach($items as $array) {
    $item_id = $array['item_id'];
    $item_num = $array['item_num'];
    $item_month = $array['item_month'];
    $person = $array['userid'];
    $completed = $array['completed'];
    $note = $array['note'];

    echo '<tr>';
    if($type == 'monthly') {
      echo '<td style="vertical-align: top;"><small>'.$months[$item_month].'<small></td>';
    }
    else if ($type == 'list') {
      echo '<td style="vertical-align: top;"><small>'.$item_num.'</small></td>';
    }
    echo '<td style="vertical-align: top;"><small>'.$person.'</small><br></td>';
    echo '<td style="vertical-align: top;"><small>'.$note.'</small><br></td>';
    if(!empty($person)) {
      echo '<td style="vertical-align: top;"><small>'.$completed.'</small><br></td>';
    }
    else {
      echo '<td style="vertical-align: top;"><br></td>';
    }
    echo '</tr>';
}

echo '</tbody></table>';
if(!empty($notes)) {
  echo '<br><b>Task Notes >></b><br><pre>'.$notes.'</pre>';
}

echo "</body>";
echo "</html>";

