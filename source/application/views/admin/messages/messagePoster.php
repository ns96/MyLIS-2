<?php
    $target_link = base_url().'admin/messages';
?>

<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
		Post a new Message
	    </td>
	</tr>
	</tbody>
    </table>
    <form action="<?=$target_link?>" method="POST" class="form-inline">
	<input type="hidden" name="message_poster_form" value="posted">      
	<table class="formTable">
	    <tr>
		<td width="15%"><label for="accounts" class="control-label">Accounts: :</label></td>
		<td><input type="text" name="accounts" value="ALL" class="input-block-level"></td>
	    </tr>
	    <tr>
		<td><label for="now" class="control-label">Post Date :</label></td>
		<td>
		    <input type="checkbox" name="now" checked="checked"> Post Immediately
		    <div style="display:none; margin-top: 8px" id="dateSelector">
			Post From <input type="text" name="post_start" value="<?=$post_start?>"> 
			to <input type="text" name="post_end" value="<?=$post_end?>">
		    </div>
		</td>
	    </tr>
	    <tr>
		<td><label for="url" class="control-label">Website URL :</label></td>
		<td>
		    <input type="text" name="url" class="input-block-level">
		</td>
	    </tr>
	    <tr>
		<td><label for="message" class="control-label">Message :</label></td>
		<td><textarea name="message" class="input-block-level"></textarea></td>
	    </tr>
	</table>
	<button type="submit" class="btn btn-primary btn-small">Post Message</button>
    </form>
</div>

<script type='text/javascript'>
    $( "input[name='now']").change(function() {
      if (this.checked) { 
          $("#dateSelector").hide();
      } else {
          $("#dateSelector").show();
      }
    });
</script>

<div class="formWrapper2">
    <table class="formTopBar2" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
		List of Messages
	    </td>
	</tr>
	</tbody>
    </table>
</div>
<?php
if(count($messageList) > 0) {
    echo "<div style='margin: 0px 15px'>";
    echo "<table id='message_outer_table'>";
    foreach($messageList as $messageItem) {
	// initialize some variables and links
	$message_id = $messageItem['message_id'];
	$manager = $accounts[$messageItem['managerid']];
	$edit_link = encodeUrl(base_url()."admin/messages/edit/".$message_id);
	$remove_link = encodeUrl(base_url()."admin/messages/delete/".$message_id);
	?>
	
	<tr>
	    <td style="text-align:center">
		Message # <?=$message_id?><br>
		<div style="margin-top:5px">
		    <a href="<?=$edit_link?>" class="btn btn-success btn-mini">Edit</a>
		    <a href="<?=$remove_link?>" class="btn btn-danger btn-mini">Remove</a>
		</div>
	    </td>
	    <td>
		<table id='message_inner_table'>
		    <tr>
			<td width="20%">Entered :</td>
			<td><?=$messageItem['message_date']?></td>
		    </tr>
		    <tr>
			<td>Manager :</td>
			<td><?=$manager->name?></td>
		    </tr>
		    <tr>
			<td>Accounts :</td>
			<td><?=$messageItem['account_ids']?></td>
		    </tr>
		    <tr>
			<td>Post Date :</td>
			<td>
			    From <span style="color: rgb(255, 0, 0);"><?=$messageItem['post_start']?></span> to 
			    <span style="color: rgb(255, 0, 0);"><?=$messageItem['post_end']?></span>
			</td>
		    </tr>
		    <tr>
			<td>Website URL :</td>
			<td>
			    <?
			    $url = $messageItem['url'];
			    if(!empty($url)) {
				echo '<a href="'.$url.'">'.$url.'</a>';
			    } else {
				echo 'none';
			    }
			    ?>
			</td>
		    </tr>
		    <tr>
			<td>Message:</td>
			<td>
			    <?
			    $new_lines = array("\n", "\r\n");
			    $message = str_replace($new_lines, "<br>", $messageItem['message']);
			    echo $message;
			    ?>
			</td>
		    </tr>
		</table>
	    </td>
	</tr>
	<?
    }
    echo "</table>";
    echo "</div>";
}
else {
    echo 'No messages posted';
}

