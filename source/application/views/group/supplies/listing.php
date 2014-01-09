<table style="width: 100%; text-align: left; margin-bottom: 5px; font-size: 14px" border="0" cellpadding="2" cellspacing="0">
    <tbody>
	<tr>
	    <td align="left">
		<!-- another image link can be place on the left side -->
	    </td>
	    <td align="center">
		<? if (isset($count) && ($count > 0)) echo $count.' entries found!'; ?>
	    </td>
	    <td style="vertical-align: top; text-align: right;">
		<a href='<?=$back_link?>'><img src='<?=base_url()?>images/icons/back.png' class='icon' title='back'/></a><br>
	    </td>
	</tr>
    </tbody>
</table>
<?
echo $sub_listing_HTML;
