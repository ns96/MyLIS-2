<?php
$back_image = base_url()."images/icons/back.png";
?>
<div style="margin:0px 15px">
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
		<a href='<?=$back_link?>'><img src='<?=$back_image?>' class='icon' title='back'/></a><br>
	    </td>
	</tr>
    </tbody>
</table>
</div>
<?
echo $sub_listing_HTML;
