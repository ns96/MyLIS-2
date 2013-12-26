<div style="margin:0px 15px;">
    <table id="file_folder_table" class="table table-bordered table-condensed">
	<caption><?=$category?><img src='<?=base_url()?>images/icons/weblink.png' class='icon' title='weblink category'/></caption>
	<thead>
	    <th width="5%" style="text-align:center">#</th>
	    <th width="80%">File Title</th>
	    <th style="text-align:center">Actions</th>
	</thead>
	<tbody>
	    <?
	    $counter = 1;
	    foreach ($weblinkList as $weblinkItem){
		$link_id = $weblinkItem['link_id'];
		$title = $weblinkItem['title'];
		$url = $weblinkItem['url'];
		echo "<tr>";
		echo "<td>$counter</td>";
		echo "<td><a href=\"$url\" target=\"_blank\">$title</a></td>";
		echo "<td>";
		if($userid == $weblinkItem['userid'] || $role == 'admin') {
		    $edit_link = base_url()."group/weblinks/index/".$link_id."#add";
		    echo "<a href='$edit_link'><img src='".base_url()."images/icons/edit.png' class='icon' title='edit'/>";
		}

		if($userid == $weblinkItem['userid'] || $role == 'admin') {
		    $delete_link = base_url()."group/weblinks/delete/".$link_id;
		    echo "<a href='$delete_link'><img src='".base_url()."images/icons/delete.png' class='icon' title='delete'/>";
		}
		echo "</td></tr>";
		$counter++;
	    }
	    ?>
	</tbody>
    </table>
</div>
