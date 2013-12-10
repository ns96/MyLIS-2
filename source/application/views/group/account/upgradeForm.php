<?php

$cancel_link = base_url().'group/main';
$target_link = base_url().'group/accounts/upgrade';

$agreeText = '<small>I understand that the bill has to be paid in full 
    within 30 days (Net 30 days) or the account will revert to the previous file storage
    limit.</small>';

$role = $user->role;

$pi_name = "$info[pi_fname] $info[pi_mi] $info[pi_lname]";

echo "<form action=\"$target_link\" method=\"POST\">";
echo '<input type="hidden" name="upgrade_form" value="posted">';
echo '<table style="text-align: left; width: 100%;" border="0" cellpadding="2" 
cellspacing="2"><tbody>
<tr>
<td style="background-color: rgb(100,255,100);" colspan="2" rowspan="1"><small>
<span style="font-weight: bold;">Account Upgrade Form</span></small></td></tr>';
if($role == 'admin') {
  echo '<tr>
  <td style="width: 25%;">Storage Upgrade :</td>
  <td><select name="storage">
  <option value="200">200 MB @ $'.$cost1.'.00 per year</option>
  <option value="1000">1000 MB @ $'.$cost2.'.00 per year</option>
  <option value="5000">5000 MB @ $'.$cost3.'.00 per year</option>
  </select>
  </td>
  </tr>
  <tr>
  <td>Your Name :</td>
  <td><input size="30" name="name" value="'.htmlentities($user->name).'"></td>
  </tr>
  <tr>
  <td>Your Email :</td>
  <td><input size="30" name="email" value="'.$user->email.'"></td>
  </tr>
  <tr>
  <td>PI Name :</td>
  <td><input size="30" name="pi_name" value="'.htmlentities($pi_name).'"></td>
  </tr>
  <tr>
  <td>PI Email :</td>
  <td><input size="30" name="pi_email" value="'.$info['email'].'"></td>
  </tr>
  <tr>
  <td>Phone :</td>
  <td><input size="30" name="phone" value="'.$info['phone'].'"></td>
  </tr>
  <tr>
  <td>P.O. Number :</td>
  <td><input size="30" name="po_number"></td>
  </tr>
  <tr>
  <td>Billing Address :</td>
  <td><textarea cols="30" rows="3" name="address">'.$info['address'].'</textarea></td>
  </tr>
  <tr>
  <td><br></td>
  <td><input name="agree" value="yes" type="checkbox"> '.$agreeText.'</td>
  </tr>
  <tr>
  <td><br></td>
  <td style="text-align: right;">
  [ <a href="'.$cancel_link.'" target="_parent">Cancel</a> ] 
  <input value="Upgrade Account" type="submit"></td>
  </tr>';
}
else {
  echo '<tr><td style="width: 25%;"><br></td>
  <td> Sorry, only users with admin privileges are allowed to 
  upgrade this account.</td></tr>';
}

echo '</tbody></table>';
printColoredLine('rgb(100,255,100)', '2px');

