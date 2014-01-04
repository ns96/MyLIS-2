<table style="text-align: left; width: 100%; margin-bottom: 4px" border="1px solid #C6CACF" cellpadding="2" cellspacing="0">
<tr>
    <td style="background-color: rgb(255,200,200);">
	<small><span style="font-weight: bold;">MyLIS Message</span> <span style="color:grey; margin-left: 5px">(<?=$message_date?>)</span>
	<?
	if(!empty($link)) {
	    echo "<a href=\"$link\" target=\"_blank\"><img src='".base_url()."images/icons/weblink.png' class='icon' /></a>";
	}
	?>
	</small>
    </td>
</tr>
<tr>
    <td style="background-color: white">
	<pre><?=$message?></pre>
    </td>
</tr>
</table>

