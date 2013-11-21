<table style="text-align: left; width: 100%; margin-bottom: 4px" border="1px solid #C6CACF" cellpadding="2" cellspacing="0">
    <tbody>
	<tr>
	    <td style="background-color: #D7DBE0; border-right: none">
		<small><span style="font-weight: bold;"><?=$poster->name?></span> <span style="color:grey;  margin-left: 5px">(<?=$date?>)</span>
		<?php
		if(!empty($link)) {
		    echo "<a href='$link' target='_blank'><img src='".base_url()."images/icons/weblink.png' class='icon' /></a>";
		}
		?>
		</small>
	    </td>
	    <td align="right" style="background-color: #D7DBE0; border-left: none">
		<?
		if(!empty($file_link)) {
		    echo "<a href='$file_link' target='_parent' title='download'><img src='".base_url()."images/icons/download2.png' class='icon' /></a>";
		}

		if(!empty($edit_link)) {
		    echo "<a href='$edit_link' target='_parent' title='edit'><img src='".base_url()."images/icons/edit.png' class='icon' /></a>";
		}

		if(!empty($delete_link)) {
		    echo "<a href='$delete_link' target='_parent' title='delete'><img src='".base_url()."images/icons/delete.png' class='icon' /></a>";
		}
		?>
	    </td>
	</tr>
	<tr>
	    <td colspan="2" style="background-color: white">
		<pre><?=$message?></pre>
	    </td>
	</tr>
    </tbody>
</table>
