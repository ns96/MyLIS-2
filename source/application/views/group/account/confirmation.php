<?php

$ok_link = base_url().'group/main';
    
$body = "<b>Account Upgrade Confirmation</b><br>";
$body .= "Order ID : $sale_info[3]<br>";
$body .= "Placed By : $sale_info[2] at $sale_info[4]<br>";
$body .= "New File Storage Limit : $sale_info[5]MB<br>";
$body .= 'Cost Per Year : $'.$sale_info[6].'.00<br><br>';
$body .= "As per the sales agreement, payment must be made within 30 
days after receipt of sales invoice, which will be sent to the billing address given. 
The sale will be null and void otherwise and the account will revert back to 
the old storage limit.<br><br>";
$body .= "If you have any questions, please contact 
<a href=\"mailto:sales@mylis.net\">sales@instras.com</a>. Please 
include your Order ID in email. <br><br>Confirmation emails have been sent to
you and the group PI [ <a href=\"$ok_link\" target=\"_parent\">OK</a> ]";

echo "$body";

