<?php
    $target_link = base_url().'group/grouptask/update_tasks';
    $print_page_link = base_url().'group/grouptask/printable';
?>

<script language="Javascript">
    <!--Hide script from older browsers

    function updateList(task) {
	document.forms.monthlyform.task2.value = task;
	document.forms.monthlyform.submit();
    }
    // End hiding script from older browsers-->              
</script>

<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
		Task Manager : <i><?=$manager->name?></i>
		( <a href="<?=$print_page_link?>">Printable View<a> )
	    </td>
	</tr>
	</tbody>
    </table>
    <form name="monthlyform\" action="<?=$target_link?>" method="POST" class="form-inline" style="margin-right:10px">
	<input type="hidden" name="task" value="grouptask_update">
	<input type="hidden" name="task2" value="">
	<input type="hidden" name="grouptask_id" value="<?=$grouptask_id?>"> 
	<table class="formTable">
	    <tr>
		<td style="vertical-align: top">
		    <?=$monthlyFields?>
		</td>
	    </tr>
	    <tr>
		<td align="right" colspan="2">
		    <? if($ismanager) { ?>
			<button class="btn btn-primary" onClick="updateList('reset')">Reset Selected</button>
		    <? } ?>
		    <button class="btn btn-primary" onClick="updateList('update_info')">Update</button>
		</td>
	    </tr>
	</table>
    </form>
    <?=$taskNotesForm?>
</div>





