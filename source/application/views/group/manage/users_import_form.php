<?php

$target_link = base_url().'group/manage/users_import';
$file_link = $home_dir.'dbfiles/users.xls';

// add the table that allows adding of a new user
$cell_color1 = 'rgb(180,200,230)'; // a light blue
$cell_color2 = 'rgb(240,240,240)'; // a light gray

// print any messages and reset the message
echo '<small><i>'.$im_message.'</i></small>';

// add table for importing a user list

?>
<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
        <tbody>
        <tr>
            <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
                Import User List 
                <span style="font-size:14px; font-weight: normal; margin-left: 15px; text-shadow: none">
                    <a href="<?=$file_link?>" target="_blank">Download Excel Template</a>
                </span>
            </td>
        </tr>
        </tbody>
    </table>
    <form action="<?=$target_link?>" enctype="multipart/form-data" method="POST" class="form-inline" style="margin-right:10px">
        <input type="hidden" name="users_import_form" value="posted">   
        <table class="formTable">
            <tr>
                <td>
                    <label for="fileupload" class="control-label">Tab Delimited Text File :</label>
                    <input type="file" id="fileupload" name="fileupload" class="input-block-level">
                    <button type="submit" class="btn btn-primary btn-small">Import</button>
                </td>
            </tr>
        </table>
    </form>
</div>


