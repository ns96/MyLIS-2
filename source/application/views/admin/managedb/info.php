<?php
    
$back_url = base_url().'admin/managedb';
echo "<div style='text-align:right'><a href='$back_url'><img src='".base_url()."images/icons/back.png' class='icon' title='back'/></a></div>";

if(count($tables) < 1) {
    echo 'There are no tabels in this database...';
}
else {
    ?>
    There are <?=count($tables)?> tables in this database.<br><br>

    <table class="table table-condensed table-bordered">
	<thead>
	    <th>Table Name</th>
	    <th>Data Size</th>
	    <th>Index Size</th>
	    <th>Total Size</th>
	    <th>Total Rows</th>
	</thead>
	<tbody>
	    <? foreach($tables as $array) {
		$total = $array['Data_length']+$array['Index_length'];
		?>
		<tr>
		    <td><?=$array['Name']?></td>
		    <td><?=$array['Data_length']?></td>
		    <td><?=$array['Index_length']?></td>
		    <td><?=$total?></td>
		    <td><?=$array['Rows']?>&nbsp;</td>
		</tr>
	    <? } ?>
	</tbody>
    </table>
    <?
}

