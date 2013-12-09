<?php

$orderbook_link = base_url()."group/orderbook";
$home_link = base_url()."group/main";
$target_link = base_url()."group/orderbook/itemlist";

// some javascript code
echo '<script language="Javascript">
<!--Hide script from older browsers
var companies = new Array('.$js_cnames.');

function changeCompany(companies, num) {
  var name = companies.options[companies.selectedIndex].value;
  if(num == 0) {
    document.forms.form1.company_0.value = name;
  }

  //alert("This works " + name);
}

function putCompany(company) {
  var num = company.value;

  if(num >= 1 &&  num <=  companies.length) {
    company.value = companies[num -1];
  }
}

function submitOrder(task) {
  // check to see if company is missing
  var company = document.forms.form1.company_0.value;
  if(company.length == 0) {
    alert("Please Select A Company");
    return;
  }

  document.forms.form1.task2.value = task;
  document.forms.form1.submit();
}

// End hiding script from older browsers-->              
</script>';
//--end of javascript code 

// display the page header
echo '<table style="width: 100%; text-align: left;" border="0" cellpadding="2" cellspacing="0">';
echo '<tbody>';
echo '<tr>';
echo '<td style="vertical-align: top;">';
echo '<span style="color: #3366FF;"><b>'.$title.'</b></span><br>';
echo '</td>';
echo '<td style="vertical-align: top; text-align: right;">';
echo "[ <a href=\"$orderbook_link\">Back to Orders</a> ] ";
//echo "[ <a href=\"$home_link\">Home</a> ]<br>";
echo '</td></tr></tbody></table>';

echo printColoredLine('#3366FF', '2px').'<pre></pre>';

// the form
echo '<form name="form1" action="'.$target_link.'" method="post">';
echo '<input type="hidden" name="save_itemlist_form" value="posted">';

// set the order id if we loaded a saved record
if(!empty($order_id)) {
  echo '<input type="hidden" name="order_id" value="'.$order_id.'"><br>';
}

// add the table holding order wide variables
echo '<table style="background-color: rgb(225, 255, 255); text-align: left; width: 100%;" border="1" cellpadding="2"
cellspacing="0"><tbody>';

echo '<tr>';
echo '<td style="vertical-align: center;">
<select name="companies" size="1" onChange="changeCompany(this.form.companies, 0)">';
echo '<option value="">Select Company</option>';
$length = sizeof($cnames);
for($i = 0; $i < $length; $i++) {
  echo '<option value="'.$cnames[$i].'">['.($i + 1).'] '.$cnames[$i].'</option>';
}
echo '<option value="Enter Company">Other</option></select> ';
echo '<input type="text" name="company_0" size="15" value="'.$company.'"></td>';

echo '<td style="vertical-align: center;"><small># of Items ('.$maxItemsMax.' max.)</small> 
<input type="text" name="maxitems" size="3" value="'.$maxitems.'"></td>';

echo '<td style="vertical-align: center;"><small>Share With Group?</small> 
<select name="priority" size="1">';
if(!empty($priority)) {
  if($priority == 'High') {
    echo "<option value=\"$priority\">Yes</option>";
  }
  else {
    echo "<option value=\"$priority\">No</option>";
  }
}
echo '<option value="High">Yes</option>
<option value="Low">No</option>
</select>';

echo '<td style="vertical-align: center;"><small>Modify Date</small> 
<input type="text" name="orderdate" size="8" readonly="readonly" value="'.$status_date.'"></td>';
echo '</tr>';

echo '<tr>';
echo '<td style="vertical-align: center;"><span style="color: rgb(235, 0, 0);"><small><b>Item List Notes :</b></small></span></td>';
echo '<td colspan="2" rowspan="1" style="vertical-align: top;">
<input type="text" name="notes" size="60" value="'.$notes.'"></td>';

echo '<td style="vertical-align: center; text-align: center;">
<input type="button" name="butSave" value="Save List" 
style="background: rgb(238, 238, 238); color: #3366FF" 
onclick="submitOrder(\'save\')"/></td>';

echo '</tr>';

echo '</tbody></table><br>';

// add the table that allows inputing of items
echo '<table style="background-color: rgb(225, 255, 255); text-align: left; width: 100%;" border="1" cellpadding="2"
cellspacing="0"><tbody>';

echo '<tr>';
echo '<td width="6%" style="vertical-align: top;"><small><b>Item #</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Type</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Product ID</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Description</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Units</b></small></td>';
echo '<td style="vertical-align: top;"><small><b>Cost</b></small></td>';
echo '</tr>';

// add slots for up to ten itmes
for($i = 1; $i <= $maxitems; $i++) {
  // check to see if an item exist for this order
  $type = '';
  $product = '';
  $description = '';
  $units = '';
  $price = '$0.00';

  if(isset($order['item_'.$i])) {
    $info = preg_split("/\t/", $order['item_'.$i]);
    $type = trim($info[0]);
    $product = trim($info[2]);
    $description =trim($info[3]);
    $units = trim($info[5]);
    $price = '$'.sprintf("%01.2f", $info[6]);
  }

  echo '<tr>';
  echo '<td style="vertical-align: top;">
  <input type="checkbox" name="item_'.$i.'" value="'.$i.'">'.$i.'</td>';

  echo '<td style="vertical-align: top;">
  <select name="type_'.$i.'" size="1">';
  if(!empty($type)) {
    echo '<option value="'.$type.'">'.$type.'</option>';
  }
  echo '<option value="Chemical">Chemical</option>
  <option value="Supply">Supply</option>
  <option value="Other">Other</option>
  </select></td>';

  echo '<td style="vertical-align: top;">
  <input type="text" name="product_'.$i.'" size="8" value="'.$product.'"></td>';

  echo '<td style="vertical-align: top;">
  <input type="text" name="description_'.$i.'" size="25" value="'.$description.'"></td>';

  echo '<td style="vertical-align: top;">
  <input type="text" name="units_'.$i.'" size="4" value="'.$units.'" ></td>';

  echo '<td style="vertical-align: top;">
  <input type="text" name="price_'.$i.'" size="6" value="'.$price.'" ></td>';

  echo '</tr>';
}

echo '</tbody></table>';

// Add the buttons
echo '<div style="color: rgb(51, 102, 255); text-align: right;">';

if(!empty($order_id)) {
  echo '<input type="button" name="butRemove" value="Remove Item" 
  style="background: rgb(238, 238, 238); color: #3366FF" 
  onclick="submitOrder(\'remove\')"/> ';

  if($role == 'admin' || $role == 'buyer' || $owner == $user_id) {
    echo '<input type="button" name="butRemove" value="Remove List" 
    style="background: rgb(238, 238, 238); color: #3366FF" 
    onclick="submitOrder(\'remove_order\')"/> ';
  }
}

echo '<input type="button" name="butSave" value="Save List" 
style="background: rgb(238, 238, 238); color: #3366FF" 
onclick="submitOrder(\'save\')"/>';

echo '</div>';

echo '</form>';

