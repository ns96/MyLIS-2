<?php
    $activate_link = base_url().'group/accounts/activate';
?>
<table style="text-align: left; width: 100%; margin-bottom: 4px" border="1px solid #C6CACF" cellpadding="2" cellspacing="0">
<tr>
    <td style="background-color: rgb(255,200,200);">
	<small><span style="font-weight: bold;">MyLIS Message</span><span style="color:grey; margin-left: 5px">(<?=$date?>)</span></small>
    </td>
</tr>
<tr>
    <td style="background-color:#FFEEEE">
	<?
	if($activated == 'yes') {
	    echo "Account Activated! Expires on ($expire)";
	}
	else {
	    if($activated == 'no')
		echo '<b>Error! Wrong Activation Code ...</b><br>';
	    ?>
	    Account expires on <b><?=$expire?></b>. Please input activation code below to prevent account from being disabled.
	    <form method="post" action="<?=$activate_link?>" name="activation" target="_parent">
		<input type="hidden" name="account_id" value="<?=$account_id?>">
		<input type="hidden" name="main_page" value="<?=base_url().'group/main'?>">
		<b>Activation Code : </b><input maxlength="10" name="activate_code">
		<input name="submit" value="Submit Code" type="submit">
	    </form>
	<?
	} ?>
	
    </td>
</tr>
</table>



