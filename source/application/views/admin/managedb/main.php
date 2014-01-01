<div style="text-align: left">
<?php

// print out the current databases
$info_link = base_url().'admin/managedb/info/';
$target = base_url().'admin/managedb/create';
?>

<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
		Current Databases
	    </td>
	</tr>
	</tbody>
    </table>
    <table id="dblist_table">
	<?
	foreach($databases as $array) {
	    $dbname = $array['Database'];
	    $url = encodeUrl($info_link.$dbname);
	    ?>
	    <tr>
		<td><?=$dbname?></td>
		<td><a href='<?=$url?>' class="btn btn-mini">display info</a></td>
	    </tr>
	    <?
	}
	?>
    </table>
</div>
    
<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td colspan="2" style="background-color: rgb(180,200,230); width: 25%;">
		Create MyLIS Database
	    </td>
	</tr>
	</tbody>
    </table>
    <table id="dblist_table">
	<tr>
	    <td>Database :  <b>LISMDB</b> (management)</td>
	    <td>
		<form action="<?=$target?>" method="post" class="inline-form">
		    <input type="hidden" name="db" value="LISMDB">
		    <input type="hidden" name="tables" value="no">
		    <button type="submit" class="btn btn-primary btn-small">Create</button>
		</form>
	    </td>
	    <td>
		<form action="<?=$target?>" method="post" class="inline-form">
		    <input type="hidden" name="db" value="LISMDB">
		    <input type="hidden" name="tables" value="yes">
		    <button type="submit" class="btn btn-primary btn-small">+ tables</button>
		</form>
	    </td>
	</tr>
	<tr>
	    <td>Database :  <b>LISPDB</b> (profiles)</td>
	    <td>
		<form action="<?=$target?>" method="post" class="inline-form">
		    <input type="hidden" name="db" value="LISPDB">
		    <input type="hidden" name="tables" value="no">
		    <button type="submit" class="btn btn-primary btn-small">Create</button>
		</form>
	    </td>
	    <td>
		<form action="<?=$target?>" method="post" class="inline-form">
		    <input type="hidden" name="db" value="LISPDB">
		    <input type="hidden" name="tables" value="yes">
		    <button type="submit" class="btn btn-primary btn-small">+ tables</button>
		</form> 
	    </td>
	</tr>
	<tr>
	    <td>Database :  <b>LISSDB</b> (service)</td>
	    <td>
		<form action="<?=$target?>" method="post" class="inline-form">
		    <input type="hidden" name="db" value="LISSDB">
		    <input type="hidden" name="tables" value="no">
		    <button type="submit" class="btn btn-primary btn-small">Create</button>
		</form>
	    </td>
	    <td>
		<form action="<?=$target?>" method="post" class="inline-form">
		    <input type="hidden" name="db" value="LISSDB">
		    <input type="hidden" name="tables" value="yes">
		    <button type="submit" class="btn btn-primary btn-small">+ tables</button>
		</form> 
	    </td>
	</tr>
	<tr>
	    <td>Database :  <b>LISDB</b> (accounts)</td>
	    <td>
		<form action="<?=$target?>" method="post" class="inline-form">
		    <input type="hidden" name="db" value="LISDB">
		    <input type="hidden" name="tables" value="no">
		    <button type="submit" class="btn btn-primary btn-small">Create</button>
		</form>
	    </td>
	    <td>
		<form action="<?=$target?>" method="post" class="inline-form">
		    <input type="hidden" name="db" value="LISDB">
		    <input type="hidden" name="tables" value="yes">
		    <button type="submit" class="btn btn-primary btn-small">+ tables</button>
		</form>
	    </td>
	</tr>
    </table>
</div>
