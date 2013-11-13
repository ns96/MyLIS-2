<?php

$activate_form = 'Account expires on <b>'.$expire.'</b>. Please input 
activation code below to prevent account from being disabled.</small></td></tr>
<tr><td style="text-align: right;"><small>
<form method="post" action="../../../admin/cgi-bin/lisadmin.php?task=signup_activate" 
name="activation" target="_parent">
<input type="hidden" name="account_id" value="'.$account_id.'">
<input type="hidden" name="main_page" value="'.$base.'main">
<b>Activation Code : </b><input maxlength="10" size="10" name="activate_code">
<input name="submit" value="Submit Code" type="submit"></form></small>';

echo '<table style="text-align: left; width: 100%;" border="0"
cellpadding="2" cellspacing="2"><tbody>
<tr>
<td style="background-color: rgb(255,200,200);"><small><span style="font-weight: bold;">MyLIS Message</span> ('.$date.')</small></td>
</tr><tr><td><small>';

if($activated == 'yes') {
    echo 'Account Activated! Expires on ('.$expire.')';
}
elseif($activated == 'no') {
    echo '<b>Error! Wrong Activation Code ...</b><br>'.$activate_form;
}
else {
    echo $activate_form;
}

printColoredLine('rgb(255,200,200)', '2px');
echo '</small></td></tr></tbody></table>';
