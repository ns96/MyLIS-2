<?php

echo "<form action='".base_url()."group/instrulog/add' method='POST'>";
echo '<input type="hidden" name="add_instrument_form" value="posted">';
echo '<small><b>Add New Instrument :</small></b><br>';
echo '<input maxlength="50" size="25" name="instrument"><br>
<small><b>Person In Charge :</small></b><br><select name="manager">';
foreach($users as $user) {
    echo '<option value='.$user->userid.'>'.$user->name.'</option>';
}
echo '</select><input name="Add" value="Add" type="submit" style="background: rgb(238, 238, 238); color: #3366FF">';
echo '</form>';
