<?php
if(!empty($slot_id)) {
    $form_link = base_url()."group/meetings/editSlot";
    $form_name = 'add_slot_form';
} else {
    $form_link = base_url()."group/meetings/addSlot";
    $form_name = 'edit_slot_form';
}

if (isset($slot_info['presenter']))
    $presenter = $slot_info['presenter'];
else
    $presenter = '';

if (isset($slot_info['title']))
    $ptitle = $slot_info['title'];
else
    $ptitle = '';

echo '<a name="add_slot"></a>'; // target for link from top
?>
<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
        <tbody>
        <tr>
            <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
                <? if(!empty($slot_id)) {
                    echo "Update slot";
                } else {
                    echo "Add slot";
                }
                ?>
            </td>
        </tr>
        </tbody>
    </table>
    <form action="<?=$form_link?>" method="POST" class="form-inline" style="margin-right:10px">
        <input type="hidden" name="<?=$form_name?>" value="posted"> 
        <input type="hidden" name="slot_id" value="<?=$slot_id?>">
        <table class="formTable">
                <tr>
                    <td>
                        <label for="gmdate_id" class="control-label">Date :</label><br>
                        <select name="gmdate_id" class="input-medium">
                            <?
                            if(isset($slot_info['gmdate_id'])) {
                                $gmdate_id = $slot_info['gmdate_id'];
                                $gd = $gmdates[$gmdate_id];
                                echo '<option value="'.$gmdate_id.'">'.$gd->date.'</option>';
                            }
                            foreach($gmdates as $gmdate_id => $gd) {
                                echo '<option value="'.$gmdate_id.'">'.$gd->date.'</option>';
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <label for="type" class="control-label">Type :</label><br>
                        <select name="type" class="input-medium">
                            <?
                            if(isset($slot_info['type'])) {
                                $type = $slot_info['type'];
                                echo '<option value="'.$type.'">'.$type.'</option>';
                            }
                            ?>
                            <option value="Research Talk">Research Talk</option>
                            <option value="Literature Talk">Literature Talk</option>
                            <option value="Visitor Talk">Visitor Talk</option>
                            <option value="Refreshments">Refreshments</option>
                            <option value="Other">Other</option>
                        </select>
                    </td>
                    <td>
                        <label for="presenter" class="control-label">Presenter :</label><br>
                        <input type="text" id="presenter" name="presenter" class="input-block-level input-xlarge" value="<?=htmlentities($presenter)?>">
                    </td>
                    <td>
                        <label for="title" class="control-label">Title :</label><br>
                        <input type="text" id="title" name="title" class="input-block-level input-xlarge" value="<?=htmlentities($ptitle)?>">
                    </td>
                </tr>
        </table>
        <br>
        <button type="submit" class="btn btn-primary btn-small"><?=$title?></button>
        <?
        // link to delete slot
        if(!empty($slot_id)) {
            $delete_link = base_url()."group/meetings/deleteSlot?slot_id=".$slot_id;
            echo '[ <a href="'.$delete_link.'">delete slot</a> ] ';
        }

        // see if to print the links to edit or remove any attach files
        if(!empty($slot_info['file_id'])) {
            $file_id = $slot_info['file_id'];
            $delete_file_link = base_url()."group/meetings/deleteFile?slot_id=$slot_id&file_id=$file_id";
            echo '[ <a href="'.$delete_file_link.'">delete file</a> ] ';
        }
        ?>
    </form>
</div>
<?


