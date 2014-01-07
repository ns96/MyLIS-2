<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
        <tbody>
        <tr>
            <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
                <?=$title?> Meeting Date
            </td>
        </tr>
        </tbody>
    </table>
    <form action="<?=$target_link?>" method="POST" class="form-inline" style="margin-right:10px">
        <input type="hidden" name="add_date_form" value="posted">
        <input type="hidden" name="gmdate_id" value="<?=$gmdate_id?>">      
        <table class="formTable">
            <tr>
                <td>
                    <label for="gmdate" class="control-label">Date :</label>
                    <input type="text" name="gmdate" class="input-block-level input-medium" value="<?=$date?>">
                </td>
                <td>
                    <label for="gmtime" class="control-label">Time :</label>
                    <input type="text" name="gmtime" class="input-block-level input-medium" value="<?=$time?>">
                </td>
                <td>
                    <label for="semester_id" class="control-label">Semester :</label>
                    <select name="semester_id">
                        <?
                        if(!empty($gmdate_id)) {
                            $semester_id = $gmdate_info['semester_id'];
                            $name = $semesters[$semester_id];
                            echo '<option value="'.$semester_id.'">'.$name.'</option>';
                        }
                        foreach ($semesters as $semester_id => $name ) {
                            echo '<option value="'.$semester_id.'">'.$name.'</option>';
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <button type="submit" class="btn btn-primary btn-small"><?=$title?> Date</button>
                </td>
            </tr>
        </table>
    </form>
</div>
