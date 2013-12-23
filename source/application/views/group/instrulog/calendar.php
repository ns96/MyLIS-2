<?php

// function to return the background color of the day cell
  function getCellColor($d, $m, $y) {
    $color = '';
    $cd= date("d");  // curent day
    $cm= date("m"); // current month
    $cy= date("Y"); // current year
    
    // highlight the current day
    if($d == $cd && $m == $cm && $y == $cy) {
      $color = '#b5cbe7';
    }
    
    /* Should have a method to color the cell in if there are any reservation 12/18/07*/
    return $color;
  }

$main_link = base_url().'group/instrulog';

$d= date("d");     // Finds today's date
$y= date("Y");     // Finds today's year

$no_of_days = date('t',mktime(0,0,0,$m,1,$y)); // This is to calculate number of days in a month

$mn=date('M',mktime(0,0,0,$m,1,$y)); // Month is calculated to display at the top of the calendar

$yn=date('Y',mktime(0,0,0,$m,1,$y)); // Year is calculated to display at the top of the calendar

$j= date('w',mktime(0,0,0,$m,1,$y)); // This will calculate the week day of the first day of the month

$adj = '';
for($k=1; $k<=$j; $k++){ // Adjustment of date starting
    $adj .="<td>&nbsp;</td>";
}

/// Starting of top line showing name of the days of the week

if (!empty($instrument_id)){
    $prev_month_link = $main_link."/index/".$instrument_id."?prm=$m&chm=-1";
    $next_month_link = $main_link."/index/".$instrument_id."?prm=$m&chm=1";
} else {
    $prev_month_link = "$main_link?prm=$m&chm=-1";
    $next_month_link = "$main_link?prm=$m&chm=1";
}
    

echo " <table border='1' cellspacing='0' cellpadding='0' align=center><tr><td>";

echo "<table id='calendar_table' cellspacing='0' cellpadding='0' align=center width='100' border='1'>
<td> 
<a href='$prev_month_link'><</a></td>
<td colspan=5><b>$mn $yn </b></td>
<td>
<a href='$next_month_link'>></a></td></tr><tr>";

echo "<td>Sun</td>
<td>Mon</td>
<td>Tue</td>
<td>Wed</td>
<td>Thu</td>
<td>Fri</td>
<td>Sat</td></tr><tr>";

////// End of the top line showing name of the days of the week//////////

//////// Starting of the days//////////
$mn2 = date('m',mktime(0,0,0,$m,1,$y)); // get month as a number
for($i = 1;$i <= $no_of_days; $i++) {
    // create the link for going to that day sd = selected day, 
    // sm = selected month, and sy = selected year
    if (!empty($instrument_id))
	$day_link = base_url()."group/instrulog/index/".$instrument_id."?sd=$i&sm=$mn2&sy=$yn&prm=$m";
    else
	$day_link = base_url()."group/instrulog?sd=$i&sm=$mn2&sy=$yn&prm=$m";

    // set the background color
    $bgc = getCellColor($i, $mn2, $yn);

    // This will display the date inside the calender cell
    echo $adj."<td valign=top style='background-color: $bgc; text-align: center;' onclick='location.href=\"$day_link\"'>
    $i<br>";
    echo "</td>";
    $adj='';
    $j ++;
    if($j == 7) {
    echo "</tr><tr>";
    $j = 0;
    }
}

echo "<tr><td colspan=7 align=center><a href='$main_link'><b>Current Month</b></a></td></tr>";
echo "</tr></table></td></tr></table>";
