<table id="instrulog_wrapper_table">
    <tr>
	<td style="vertical-align: top; width:67%">
	   <? include FCPATH."application/views/group/instrulog/hoursTable.php"; ?>
	</td>
	<td style="vertical-align: top">
	    <div style="margin:10px">
		<? include FCPATH."application/views/group/instrulog/calendar.php"; ?>
	    </div>
	    <div class="formWrapper">
		<table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
		    <tbody>
		    <tr>
			<td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
			    Add new instrument
			</td>
		    </tr>
		    </tbody>
		</table>
		<? include FCPATH."application/views/group/instrulog/addInstrumentForm.php"; ?>
	    </div>
	    <div class="formWrapper">
		<table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
		    <tbody>
		    <tr>
			<td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
			    Group task list
			</td>
		    </tr>
		    </tbody>
		</table>
		<?=$instrumentsHTML?>
	    </div>
	</td>
    </tr>
</table>

