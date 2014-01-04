<?php

$userid = $user->userid;
$role = $user->role;

// initialize some links

$list_mine = base_url()."group/chemicals/listing/mine"; // list all chemicals that I own
$list_all = base_url()."group/chemicals/listing/all"; // list all chemicals
$list_bycategory = base_url()."group/chemicals/listing/by_category"; // list all by categories
$list_bylocation = base_url()."group/chemicals/listing/by_location"; // list all by categories
$list_locations = base_url()."group/chemicals/list_locations"; // displays the list of locations
$search_target = base_url()."group/chemicals/search";
$location_link = base_url()."group/chemicals/list_locations";
$home_link = base_url()."group/chemicals";

echo '[ <a href="'.$list_mine.'">My Chemicals</a> ] ';
echo '[ <a href="'.$list_all.'">List All</a> ] ';
echo '[ <a href="'.$list_bycategory.'">List All By Category</a> ] ';
echo '[ <a href="'.$list_bylocation.'">List All By Location</a> ] ';

// add the search form
?>
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
            <input type="hidden" name="search_chemicals_form" value="posted">     
            <table class="formTable">
                    <tr>
                        <td style="width:100px">
                            <label for="radio" class="control-label">Search by:</label>
                        </td>
                        <td>
                            <label class="radio">
                                <input type="radio" name="searchby" id="optionsRadios1" value="id" checked>
                                Chem ID
                            </label>
                            <label class="radio">
                                <input type="radio" name="searchby" id="optionsRadios2" value="name">
                                Name
                            </label>
                            <label class="radio">
                                <input type="radio" name="searchby" id="optionsRadios3" value="cas">
                                CAS#
                            </label>
                            <label class="radio">
                                <input type="radio" name="searchby" id="optionsRadios4" value="location">
                                Location
                            </label>
                            <br>
                            <div id="location_list_option" style="margin-top:5px; display:none">
                                <select name="location">';
                                    <?
                                    foreach($locations as $location) {
                                        echo '<option value="'.$location.'">'.$location['location_id'].'</option>';
                                    }
                                    ?>
                                </select>
                                <input type="button" value="Location List" class="btn btn-info" onClick="window.open('<?=$location_link?>','locations','width=500,height=600,location=no,resizable=yes,scrollbars=yes')">
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

// add the add chemical form if useris != guest
if($user->role == 'guest') {
    return;
}

if(!empty($chem_id)) {
    echo '<small>';
    if($message == 'delete') {
	echo ' || <span style="font-weight: bold; color: rgb(255, 50, 50);">Message :</span> ';
	echo "Chemical with ID $chem_id deleted from database";
    }
    else {
	$info_link = base_url()."group/chemicals/info&chem_id=$chem_id";
	echo ' || <span style="font-weight: bold; color: rgb(255, 50, 50);">Message :</span> ';
	echo "Chemical added to database with ID \"<a href=\"$info_link\">$chem_id</a>\"";
    }
    echo '</small>';
}

$add_chemical_link = base_url().'group/chemicals/add';
// add the form that allows input of new chemical
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
        <form action="<?=$add_chemical_link?>" method="POST" class="form-inline" style="margin-right:10px">
            <input type="hidden" name="add_chemical_form" value="posted" >      
            <table class="formTable">
                <tr>
                    <td style="width: 25%">
                        <label for="cas" class="control-label">CAS# :</label><br>
                        <input type="text" id="cas" name="cas" class="input-block-level">
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
                        <input type="text" id="units" name="units" class="input-block-level input-small" placeholder="units">
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
                            if($category != 'My Chemicals') {
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
                        <input type="text" id="other_location" name="other_location" placeholder="Location ID, Room #, Description" >
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
            <button type="submit" class="btn btn-primary btn-small">Add Chemical</button>
        </form>
    </div>
<?

// add the form that allows transfering of chemicals
$transfer_chemical_link = base_url().'group/chemicals/transfer';
if($role == 'admin') { ?>
    
    <div class="formWrapper">
        <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
            <tbody>
            <tr>
                <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
                    Transfer Chemical Ownership
                </td>
            </tr>
            </tbody>
        </table>
        <form action="<?=$transfer_chemical_link?>" method="POST" class="form-inline" style="margin-right:10px">
            <input type="hidden" name="transfer_chemical_form" value="posted" >
            <input type="hidden" name="chem_id" value="-1">       
            <label for="from_user" class="control-label">Transfer From :</label>
            <select name="from_user" class="input-medium">
                <?
                    foreach($users as $user) {
                        echo '<option value="'.$user->userid.'">'.$user->name.'</option>';
                    }
                ?>
            </select>     
            <label for="to_user" class="control-label" style="margin-left: 10px">Group Chemical :</label>
            <select name="to_user" class="input-medium">
                <?
                    foreach($users as $user) {
                        echo '<option value="'.$user->userid.'">'.$user->name.'</option>';
                    }
                ?>
            </select>
            <button type="submit" class="btn btn-primary btn-small" style="margin-left:10px">Transfer</button>
        </form>
    </div>
    
    <?
}