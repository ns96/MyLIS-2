<?php
echo "<html>";
echo "<head>";
echo "<title>MyLIS Administrator Login</title>";
echo "</head>";
echo '<body>';
echo '<hr style="width: 100%; height: 1px;">';
echo '<div style="text-align: center;">';

// The form
echo '<form method="post" action="'.base_url().'admin/login/login_request">';
echo '<input type="hidden" name="logintry" value="yes">';

// Add the outer table
echo "<table style=\"background-color: rgb(255, 150, 150); margin-left: auto; margin-right: auto; width: 40%; text-align: left;\"
border=\"0\" cellpadding=\"1\" cellspacing=\"0\">";
echo '<tbody>';
echo '<tr align="center"><td style="vertical-align: top;">';
echo "<br><big><big style=\"font-weight: bold; color: rgb(0, 0, 0);\">MyLIS Admin Login<br><br></big></big></td></tr>";
echo '<td style="vertical-align: top;">';

// the middle table
echo '<table style="background-color: rgb(255, 255, 255); text-align: left; width: 100%; height: 100%;"
border="0" cellpadding="2" cellspacing="2"><tbody>';
echo '<tr> <td style="vertical-align: top; text-align: center;">';

// the inner table
echo '<table style="margin-left: auto; margin-right: auto; width: 80%; text-align: left;"
border="0" cellpadding="2" cellspacing="0"><tbody>';

echo '<tr> <td style="vertical-align: top; text-align: right;">';
echo '<img alt="" src="'.base_url().'images/userid.gif" style="width: 84px; height: 23px;"> </td>';
echo '<td style="vertical-align: top;"><input name="userid" size="25"> </td></tr>';

echo '<tr><td style="vertical-align: top; text-align: right;">';
echo '<img alt="" src="'.base_url().'images/password.gif" style="width: 84px; height: 23px;"> </td>';
echo '<td style="vertical-align: top;"><input type="password" name="password" size="25"> </td></tr>';

echo '</tbody></table>'; // inner table end

echo '<div style="text-align: center;">';
echo '<input name="log in" value="Log in" style="margin: 10px 5px 5px 120px; background: rgb(238, 238, 238); color: rgb(51, 102, 255);"
type="submit"> </div></td></tr>';

echo '</tbody></table>'; // middle table end

echo '</td></tr>';
echo '</tbody></table>'; // the outer table end
echo '</form>';

echo "<hr style=\"width: 100%; height: 1px;\">";
echo 'Please contact your <a href="mailto:lisadmin@instras.com">site administrator</a> if you have trouble logging in.';
echo '<hr style="width: 100%; height: 1px;"></div>';

echo "</body>";
echo "</html>";