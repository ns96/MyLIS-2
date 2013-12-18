<ul class="nav nav-tabs" style="margin-left:15px; margin-right: 15px">
    <?
    foreach ($semesters as $semester_id => $name) {
        $link = base_url()."group/meetings?semester_id=$semester_id";
        echo "<li id='semester_$semester_id'>";
        echo '<a href="'.$link.'">'.$name.'</a>';
        echo '</li>';
    }
    ?>
</ul>
<div style="background-color:white; height: 10px; margin-left:15px; margin-right: 15px; margin-bottom: 20px; border-bottom: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD"></div>

<script type='text/javascript'>
    var active = '<?='semester_'.$default_semester?>';
    $("#"+active).addClass('active');
</script>

<?

// initialize some links, semester names, and dates
$home_link = base_url()."group/main";
$previous_link = base_url()."group/meetings?year=".($year - 1);
$current_link = base_url()."group/meetings";
$next_link = base_url()."group/meetings?year=".($year + 1);

echo '<div style="margin:15px"><table style="text-align: left; width:100%; font-size:14px;" border="0"><tr><td>
<a href="#add_date">( Add Date/Meeting Slot )</a>
 </td>
<td style="text-align: right;">
[ <a href="'.$previous_link.'"><< Previous Year</a> ] 
[ <a href="'.$current_link.'">Current</a> ] 
[ <a href="'.$next_link.'">Next Year >></a> ] 
[ <a href="'.$home_link.'">Home</a> ]
</td></tr></table></div>';

// add the meetings sorted by date
echo '<div class="formWrapper">';
echo $semesterHTML;
echo '</div>';

// display the add slot form
echo $addSlotHTML;

// display the add date form
echo $addDateHTML;

