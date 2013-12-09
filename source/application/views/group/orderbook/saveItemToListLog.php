<?php

echo '<h2 style="text-align: center; color:#3366FF;">Save Items to List Log </h2>';
echo '<div style="text-align: center;">';

echo $logHTML;


$back_link = base_url()."group/orderbook?order_id=".$order_id;
echo '<br>[ <a href="'.$back_link.'">OK</a> ] ';
echo '</div>';   