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

echo "<div style='text-align:right; margin:0px 15px'><a href='$orderbook_link'>Back to Orders</a></div>";

?>

<div class="formWrapper">
    <table class="formUpperBar" style="width: 100%" cellpadding="4" cellspacing="2">
        <tbody>
        <tr>
            <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
                <?
		if(!empty($order_id)) 
		    echo "Edit Order";
		else 
		    echo "Make New Order";
		?>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="formSubBar">General Itemlist Information</div>
    <form name="form1" action="<?=$target_link?>" method="post">
	<input type="hidden" name="save_itemlist_form" value="posted">
	<? if(!empty($order_id)) {
	    echo '<input type="hidden" name="order_id" value="'.$order_id.'"><br>';
	} ?>
	<table class="formTable">
	    <tr>
		<td>
		    <label for="companies" class="control-label"> Company:</label>
		    <select name="companies" class="input-medium" onChange="changeCompany(this.form.companies, 0)">';
			<option value="">Select Company</option>
			<?
			$length = sizeof($cnames);
			for($i = 0; $i < $length; $i++) {
			    echo '<option value="'.$cnames[$i].'">['.($i + 1).'] '.$cnames[$i].'</option>';
			}
			?>
			<option value="Enter Company">Other</option>
		    </select>
		    <input type="text" name="company_0" class="input-medium" value="<?=$company?>">
		</td>
		<td>
		    <label for="maxitems" class="control-label"> # of Items (<?=$maxItemsMax?> max.)</label>
		    <input type="text" name="maxitems" value="<?=$maxitems?>" class="input-medium">
		</td>
		<td>
		    <label for="priority" class="control-label">Share With Group?</label>
		    <select name="priority" class="input-medium">
			<?
			if(!empty($priority)) {
			    if($priority == 'High') {
				echo "<option value='$priority'>Yes</option>";
			    }
			    else {
				echo "<option value='$priority'>No</option>";
			    }
			}
			echo '<option value="High">Yes</option>';
			?>
			<option value="Low">No</option>
		    </select>
		</td>
		<td>
		    <label for="orderdate" class="control-label">Modify Date</label>
		    <input type="text" name="orderdate" readonly="readonly" value="<?=$status_date?>">
		</td>
	    </tr>
	    <tr>
		<td colspan="3">
		    <label for="notes" class="control-label" style="display: inline">Itemlist Notes :</label>
		    <input type="text" name="notes" value="<?=$notes?>" class="input-xxlarge">
		</td>
		<td style="text-align: center">
		    <button name="butSave" type="submit" class="btn btn-primary" style="margin-bottom: 10px" onclick="submitOrder('save')">Save List</button>
		</td>
	    </tr>
	</table>
	<br>
	<div class="formSubBar">Items</div>
	<table class="formTable-compact">
	    <thead>
		<th>Item #</th>
		<th>Type</th>
		<th width="20%"><a href="javascript:openItemListPage()">Product ID</a></th>
		<th>Description</th>
		<th width="15%">Units</th>
		<th width="11%">Cost</th>
	    </thead>
	    <tbody>
		<?
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
		    ?>
		    <tr>
			<td>
			    <input type="checkbox" name="item_<?=$i?>" value="<?=$i?>"> <?=$i?>
			</td>
			<td>
			    <select name="type_<?=$i?>" class="input-block-level">
				<? if(!empty($type)) {
				    echo '<option value="$type">$type</option>';
				} ?>
				<option value="Chemical">Chemical</option>
				<option value="Supply">Supply</option>
				<option value="Other">Other</option>
			    </select>
			</td>
			<td>
			    <input type="text" name="product_<?=$i?>" value="<?=$product?>" class="input-block-level">
			</td>
			<td>
			    <input type="text" name="description_<?=$i?>" value="<?=$description?>" class="input-block-level">
			</td>
			<td>
			    <input type="text" name="units_<?=$i?>" value="<?=$units?>" class="input-block-level">
			</td>
			<td>
			    <input type="text" name="price_<?=$i?>" value="<?=$price?>" class="input-block-level">
			</td>
		    </tr>
		    <?
		}
		?>
	    </tbody>
	</table>
	<div style="text-align: right">
	    <?
	    if(!empty($order_id)) {
		echo "<button name='butRemove' type='submit' class='btn' style='margin-bottom: 10px; margin-right:10px' onclick='submitOrder(".'remove'.")'>Remove Item</button>";
		if($role == 'admin' || $role == 'buyer' || $owner == $user_id) {
		    echo "<button name='' type='submit' class='btn btn-danger' style='margin-bottom: 10px; margin-right:10px' onclick='submitOrder(".'remove_order'.")'>Remove List</button>";
		}
	    }
	    echo "<button name='butSave' type='submit' class='btn btn-primary' style='margin-bottom: 10px' onclick='submitOrder(".'save'.")'>Save List</button>";
	    ?>
	</div>
    </form>
</div>


