<?php

$userid = $user->userid;
$role = $user->role;

// initialize some links

$list_mine = base_url()."group/supplies/listing/mine"; // list all supplies that I own
$list_all = base_url()."group/supplies/listing/all"; // list all supplies
$list_bycategory = base_url()."group/supplies/listing/by_category"; // list all by categories
$list_bylocation = base_url()."group/supplies/listing/by_location"; // list all by categories
$list_locations = base_url()."group/supplies/list_locations"; // displays the list of locations
$search_target = base_url()."group/supplies/search";
$location_link = base_url()."group/supplies/list_locations";
?>
[ <a href="<?=$list_mine?>">My Supplies</a> ] 
[ <a href="<?=$list_all?>">List All</a> ] 
[ <a href="<?=$list_bycategory?>">List All By Category</a> ] 
[ <a href="<?=$list_bylocation?>">List All By Location</a> ] 
<br>
<div class="formWrapper-inline">
        <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
            <tbody>
            <tr>
                <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
                    Search the inventory
                </td>
            </tr>
            </tbody>
        </table>
        <form action="<?=$search_target?>" method="POST" class="form-inline" style="margin-right:10px">
            <input type="hidden" name="search_supplies_form" value="posted">     
            <table class="formTable">
                    <tr>
                        <td style="width:100px">
                            <label for="radio" class="control-label">Search by:</label>
                        </td>
                        <td>
                            <label class="radio">
                                <input type="radio" name="searchby" id="optionsRadios1" value="id" checked>
                                Supply ID
                            </label>
                            <label class="radio">
                                <input type="radio" name="searchby" id="optionsRadios2" value="name">
                                Name
                            </label>
                            <label class="radio">
                                <input type="radio" name="searchby" id="optionsRadios3" value="model">
                                MODEL
                            </label>
                            <label class="radio">
                                <input type="radio" name="searchby" id="optionsRadios4" value="location">
                                Location
                            </label>
                            <br>
                            <div id="location_list_option" style="margin-top:5px; display:none">
                                <select name="location">
                                    <?
                                    foreach($locations as $location) {
                                        echo '<option value="'.$location.'">'.$location.'</option>';
                                    }
                                    ?>
                                </select>
                                <input type="button" value="Location List" onClick="window.open('<?=$location_link?>','locations','width=500,height=600,location=no,resizable=yes,scrollbars=yes')">
                            </div>
                        </td>
                        <td rowspan="2">
                            <button type="submit" class="btn btn-large" style="margin-left:15px; margin-right: 15px;">Inventory Search</button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="radio" class="control-label">Search for:</label>
                        </td>
                        <td>
                            <input type="text" name="searchterm" id="searchterm" value="" class="input-block-level">
                        </td>
                    </tr>
            </table>
        </form>
</div>

<script type='text/javascript'>
    $( "input[name='searchby']").change(function() {
      if ($('#optionsRadios4').is(':checked')) { 
          $("#location_list_option").show();
      } 
      else {
          $("#location_list_option").hide();
      }
    });
</script>
<?

// add the add supply form if useris != guest
if($user->role == 'guest') {
    return;
}

if(!empty($item_id)) {
    echo '<small>';
    if($message == 'delete') {
	echo ' || <span style="font-weight: bold; color: rgb(255, 50, 50);">Message :</span> ';
	echo "Supply with ID $item_id deleted from database";
    }
    else {
	$info_link = base_url()."group/supplies/info&item_id=$item_id";
	echo ' || <span style="font-weight: bold; color: rgb(255, 50, 50);">Message :</span> ';
	echo "Supply added to database with ID \"<a href=\"$info_link\">$item_id</a>\"";
    }
    echo '</small>';
}

$add_supply_link = base_url().'group/supplies/add';
// add the form that allows input of new supply
?>

<div class="formWrapper">
        <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
            <tbody>
            <tr>
                <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
                    Add New entry
                </td>
            </tr>
            </tbody>
        </table>
        <form action="<?=$add_supply_link?>" method="POST" class="form-inline" style="margin-right:10px">
            <input type="hidden" name="add_supply_form" value="posted" >      
            <table class="formTable">
                <tr>
                    <td style="width: 25%">
                        <label for="model" class="control-label">Model # :</label><br>
                        <input type="text" id="model" name="model" class="input-block-level">
                    </td>
                    <td style="width: 25%">
                        <label for="name" class="control-label">Name :</label><br>
                        <input type="text" id="name" name="name" class="input-block-level">
                    </td>
                    <td style="width: 25%"></td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <label for="company" class="control-label">Company :</label><br>
                        <input type="text" id="company" name="company" class="input-block-level">
                    </td>
                    <td>
                        <label for="productid" class="control-label">Product ID :</label>
                        <input type="text" id="productid" name="productid" class="input-block-level">
                    </td>
                    <td>
                        <label for="amount" class="control-label">Amount :</label><br>
                        <select name="amount" size="1" class="input-small">
                            <option value="1">1x</option>
                            <option value="2">2x</option>
                            <option value="3">3x</option>
                            <option value="4">4x</option>
                            <option value="5">5x</option>
                            <option value="6">6x</option>
                            <option value="7">7x</option>
                            <option value="8">8x</option>
                            <option value="9">9x</option>
                            <option value="10">10x</option>
                        </select>
                        <input type="text" id="units" name="units" class="input-block-level input-small">
                    </td>
                    <td>
                        <label for="status" class="control-label">Status :</label><br>
                        <select name="status" size="1" class="input-medium">
                            <option value="In Stock">In Stock</option>
                            <option value="Out of Stock">Out of Stock</option>
                            <option value="Ordered">Ordered</option>
                            <option value="Checked Out">Checked Out</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label for="categories" class="control-label">Category :</label><br>
                        <select name="categories[]" size="1" class="input-medium">';
                        <?
                        foreach($categories as $category) {
                            if($category != 'My Supplies') {
                            echo '<option value="'.$category.'">'.$category.'</option>';
                            }
                        }
                        ?>
                        </select>
                        other: 
                        <input type="text" id="other_category" name="other_category">
                    </td>
                    <td colspan="2">
                        <label for="location" class="control-label">Location :</label><br>
                        <select name="location" size="1" class="input-medium">';
                        <?
                        foreach($locations as $location) {
                            echo '<option value="'.$location.'">'.$location.'</option>';
                        }
                        ?>
                        </select>
                        other:
                        <input type="text" id="other_category" name="other_location" placeholder="Location ID, Room #, Description" >
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <label for="notes" class="control-label">Notes :</label><br>
                        <textarea id="notes" name="notes" class="input-block-level"></textarea>
                    </td>
                    <td align="center" style="vertical-align: middle">
                        <input type="checkbox" name="personal" value="personal" checked="checked"> Personal Item
                    </td>
                </tr>
            </table>
            <br>
            <button type="submit" class="btn btn-primary btn-small">Add Supply</button>
        </form>
    </div>
<?

// add the form that allows transfering of supplies
$transfer_supply_link = base_url().'group/supplies/transfer';
if($role == 'admin') { ?>
    
    <div class="formWrapper">
        <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
            <tbody>
            <tr>
                <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
                    Transfer Supply Ownership
                </td>
            </tr>
            </tbody>
        </table>
        <form action="<?=$transfer_supply_link?>" method="POST" class="form-inline" style="margin-right:10px">
            <input type="hidden" name="transfer_supply_form" value="posted" >
            <input type="hidden" name="item_id" value="-1">       
            <label for="from_user" class="control-label">Transfer From :</label>
            <select name="from_user" class="input-medium">
                <?
                    foreach($users as $user) {
                        echo '<option value="'.$user->userid.'">'.$user->name.'</option>';
                    }
                ?>
            </select>     
            <label for="to_user" class="control-label" style="margin-left: 10px">Group Supply :</label>
            <select name="to_user" class="input-medium">
                <?
                    foreach($users as $user) {
                        echo '<option value="'.$user->userid.'">'.$user->name.'</option>';
                    }
                ?>
            </select>
            <button type="submit" class="btn btn-primary btn-small" style="margin-left: 10px">Transfer</button>
        </form>
    </div>
    
    <?
}