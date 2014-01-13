<div style="text-align: left">
<?php

// print out the current databases
$info_link = base_url().'admin/managedb/info/';
$target = base_url().'admin/managedb/create';
$dbnames = array('lisdb','lismdb','lispdb','lissdb');
$dbtitles = array(
    'lismdb'     =>  'management',
    'lispdb'    =>  'profiles',
    'lissdb'    =>  'services',
    'lisdb'     =>  'accounts',
 );
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
        <? foreach($dbnames as $dbname) { ?>
	<tr>
            <td>Database :  <b><?=strtoupper($dbname)?></b> (<?=$dbtitles[$dbname]?>)</td>
	    <td>
		<form action="<?=$target?>" method="post" class="inline-form">
		    <input type="hidden" name="db" value="<?=$dbname?>">
		    <input type="hidden" name="tables" value="no">
                    <?
                        if ($db_state[$dbname] == 0)
                            echo "<button type='submit' class='btn btn-primary btn-small'>Create</button>";
                        else
                            echo "<button type='submit' class='btn btn-small' disabled>Create</button>";
                    ?>
		</form>
	    </td>
	    <td>
		<form action="<?=$target?>" method="post" class="inline-form">
		    <input type="hidden" name="db" value="<?=$dbname?>">
		    <input type="hidden" name="tables" value="yes">
                    <?
                        if ($db_state[$dbname] < 2)
                            echo "<button type='submit' class='btn btn-primary btn-small'>+ tables</button>";
                        else
                            echo "<button type='submit' class='btn btn-small' disabled>+ tables</button>";
                    ?>
		</form>
	    </td>
	</tr>
        <? } ?>
    </table>
</div>
