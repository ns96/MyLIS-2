<?
if(!empty($message_id)) {
    $title = "Edit Message <img src='".base_url()."images/icons/edit.png' class='icon' />";
    $target_url = base_url()."group/messages/edit";
} else {
    $title = "Post Message <img src='".base_url()."images/icons/add.png' class='icon' />";
    $target_url = base_url()."group/messages/add";
}
?>

<table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2"><tbody>
<tr>
    <td style="background-color: rgb(180,200,230); width: 25%;">
	<?=$title?>
    </td>
    <td style="background-color: rgb(180,200,230); text-align: right;">
	<small>
	<?
	if(!empty($messageItem['file_id'])) {
	    $file_id = $messageItem['file_id'];
	    $delete_link = base_url()."group/messages/delete_message_file/".$file_id;
	    echo "[ <a href=\"$delete_link\">delete file</a> ]";
	}

	if(!empty($message_id)) {
	    $add_link = base_url()."group/main";
	    echo " [ <a href=\"$add_link\">new message</a> ]";
	}
	?>
	</small>
    </td>
</tr>
</tbody>
</table>
<form action="<?=$target_url?>" method="POST" enctype="multipart/form-data" class="form-horizontal" style="margin-right:10px">
  <input type="hidden" name="message_id" value="<?=$message_id ?>">
  <div class="control-group">
    <label for="website_link" class="control-label">Website Link:</label>
    <div class="controls">
      <input type="text" id="website_link" name="url" class="input-block-level" placeholder="Relevant website" value="<?=$messageItem['url'] ?>">
    </div>
  </div>
  <div class="control-group">
    <label for="message_area" class="control-label">Message:</label>
    <div class="controls">
      <textarea rows="3" id="message_area" name="message" class="input-block-level" placeholder="Type your message here..."><?=$messageItem['message'] ?></textarea>
    </div>
  </div>
  <div class="control-group">
      <label for="fileupload_1" class="control-label">
	  <?
	    if(!empty($messageItem['file_id'])) {
		echo "Attach new file:";
	    } else {
		echo "Attach file:";
	    }
	  ?>
      </label>
      <div align="left" class="controls">
	<input id="fileupload_1" name="fileupload_1" class="filestyle" type="file" data-icon="false" style="position: fixed; left: -500px;">  
      <span style="color:grey; margin-left: 5px; margin-right: 10px">(max file size: 2MB)</span>
      Select Type :
	<select name="filetype_1" class="input-medium">
	    <option value="none">No File</option>
	    <option value="pdf">PDF</option>
	    <option value="doc">Word</option>
	    <option value="ppt">Powerpoint</option>
	    <option value="xls">Excel</option>
	    <option value="rtf">RTF</option>
	    <option value="odt">OO Text</option>
	    <option value="odp">OO Impress</option>
	    <option value="ods">OO Calc</option>
	    <option value="zip">Zip</option>
	    <option value="other">Other</option>
	</select>
      </div>
  </div>
  <div class="control-group">
      <button type="submit" class="btn btn-primary btn-small">
	  <? if(!empty($message_id)) echo 'Update Message'; else echo 'Post Message'; ?>
      </button>
  </div>
</form>