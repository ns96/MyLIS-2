<?php
if (!empty($file_id)){
    $target_link = base_url().'group/file_folder/editfile';
} else {
    $target_link = base_url().'group/file_folder/addfile';
}

if(isset($fileInfo['title'])) {
    $file_title = $fileInfo['title'];
} else {
    $file_title = '';
}

?>

<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
        <tbody>
        <tr>
            <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
                Add New File
            </td>
        </tr>
        </tbody>
    </table>
    <form action="<?=$target_link?>" method="POST" enctype="multipart/form-data" class="form-inline" style="margin-right:10px">
        <input type="hidden" name="task" value="'.$task.'">
        <input type="hidden" name="file_id" value="'.$file_id.'">     
        <table class="formTable">
            <tr>
                <td>
                    <label for="title" class="control-label">File title :</label>
                </td>
                <td>
                    <input type="text" name="title" class="input-block-level" value="<?=$file_title?>">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="category" class="control-label">Category :</label>
                </td>
                <td>
                    <select name="category" class="input-medium">
                        <?
                        if(isset($fileInfo['category_id'])) {
                            $cat_id = 'cat_'.$fileInfo['category_id'];
                            $category = $categories[$cat_id];
                            echo '<option value="'.$cat_id.'">'.$category.'</option>';
                          }
                          foreach($categories as $key => $category) {
                            echo '<option value="'.$key.'">'.$category.'</option>';
                          }
                        ?>
                    </select>
                    or New Category
                    <input type="text" name="other_category">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cas" class="control-label">Upload file (2MB Max):</label>
                </td>
                <td>
                    <?=$urlUploadField?>
                </td>
            </tr>
        </table>
        <button type="submit" class="btn btn-primary btn-small"><?=$title?></button>
    </form>
</div>
