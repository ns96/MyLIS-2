<?php

if ($case == 'demo'){
    echo '<table style="text-align: left; width: 100%;" border="0"
    cellpadding="2" cellspacing="2"><tbody>
    <tr>
    <td style="background-color: rgb(255,200,200);"><span style="font-weight: bold;">
    MyLIS Welcome Message </span>('.$date.')</td>
    </tr><tr><td>Welcome to the MyLIS research network. 
    You are currently logged into a fully functional demo account. If you like what you see, then
    please sign up for your own <b>Free</b> account <a href="'.$signup_link.'" target="_blank">here</a>.<br><br>
    <i>So far, <big><b>'.$user_count.'</b></big> individuals have used this demo.</i>';
} elseif ($case == 'admin'){
    echo '<table style="text-align: left; width: 100%;" border="0"
    cellpadding="2" cellspacing="2"><tbody>
    <tr>
    <td style="background-color: rgb(255,200,200);"><small><span style="font-weight: bold;">
    MyLIS Welcome Message </span>('.$date.')</small></td>
    </tr><tr><td><small>Welcome to the MyLIS research network. 
    The initial storage quota is 50 MB, but a premium account with up to 5GB additional 
    storage is available for purchase <a href="'.$sales_link.'">here</a>.<br><br>
    To get started, add <b>user accounts</b>, <b>room locations</b>, <b>categories</b>,
    and select <b>menus to display</b> using the <a href="'.$manage_link.'" target="_parent">manage</a> tab.
    Read the <a href="'.$help_link.'" target="_blank">help file</a> for instructions how to accomplish these tasks. 
    <a href="'.$hide_link.'" target="_parent"><b>Don\'t Show This Message Again</b></a></small>';
} else {
    echo '<table style="text-align: left; width: 100%;" border="0"
    cellpadding="2" cellspacing="2"><tbody>
    <tr>
    <td style="background-color: rgb(255,200,200);"><small><span style="font-weight: bold;">
    MyLIS Welcome Message </span>('.$date.')</small></td>
    </tr><tr><td><small>Welcome to the MyLIS research network. 
    The initial storage quota is 50 MB, but a premium account with up to 5GB additional 
    storage is available for purchase.<br><br>
    To get started, look over the <a href="'.$help_link.'" target="_blank">help manual</a>. 
    <a href="'.$hide_link.'" target="_parent"><b>Don\'t Show This Message Again</b></a></small>';
}

printColoredLine('rgb(255,200,200)', '2px');
echo '</td></tr></tbody></table>';