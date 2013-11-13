<?php

$title = "MyLIS Account Expired";

echo "<html>";
echo "<head>";
echo "<title>$title</title>";
echo "</head>";
echo "<body bgcolor=\"white\">";
echo "<center>";
echo "<h2>";
echo "This MyLIS Account With Group ID (<span style=\"font-weight: bold; color: rgb(153, 0, 0)\"> 
".$this->properties['lis.account']." </span>) has Expired.<br>";
echo "Please contact support at <a href=\"mailto:support@mylis.net\">support@mylis.net</a> 
to have it reactivated.<br>Remember to include the above Group ID in the email.";
echo "</h2>";
echo "</center>";
echo "</body>";
echo "</html>";