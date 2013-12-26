<div style="text-align: left">
<?php

// print out the current databases
$info_link = base_url().'admin/managedb/info/';

echo "<strong>Current Databases >> </strong><br>";
echo '<ul>';
foreach($databases as $array) {
    $dbname = $array['Database'];
    $url = encodeUrl($info_link.$dbname);
    echo '<li>Database :  <b>'.$dbname."</b> [ <a href=\"$url\">display info</a> ] </li>";
}
echo '</ul>';

$target = base_url().'admin/managedb/create';
?>
<br><strong>Create MyLIS Database >> </strong><br>
<ul>
    <li>
	Database :  <b>LISMDB (management)</b>
	<form action="<?=$target?>" method="post" class="inline-form">
	    <input type="hidden" name="db" value="LISMDB">
	    <input type="hidden" name="tables" value="no">
	    <button type="submit" class="btn btn-primary btn-small">Create</button>
	</form>
	<form action="<?=$target?>" method="post" class="inline-form">
	    <input type="hidden" name="db" value="LISMDB">
	    <input type="hidden" name="tables" value="yes">
	    <button type="submit" class="btn btn-primary btn-small">+ tables</button>
	</form>
    </li>
    <li>
	Database :  <b>LISPDB (profiles)</b> 
	<form action="<?=$target?>" method="post" class="inline-form">
	    <input type="hidden" name="db" value="LISPDB">
	    <input type="hidden" name="tables" value="no">
	    <button type="submit" class="btn btn-primary btn-small">Create</button>
	</form>
	<form action="<?=$target?>" method="post" class="inline-form">
	    <input type="hidden" name="db" value="LISPDB">
	    <input type="hidden" name="tables" value="yes">
	    <button type="submit" class="btn btn-primary btn-small">+ tables</button>
	</form> 
    </li>
    <li>
	Database :  <b>LISSDB (service)</b> 
	<form action="<?=$target?>" method="post" class="inline-form">
	    <input type="hidden" name="db" value="LISSDB">
	    <input type="hidden" name="tables" value="no">
	    <button type="submit" class="btn btn-primary btn-small">Create</button>
	</form>
	<form action="<?=$target?>" method="post" class="inline-form">
	    <input type="hidden" name="db" value="LISSDB">
	    <input type="hidden" name="tables" value="yes">
	    <button type="submit" class="btn btn-primary btn-small">+ tables</button>
	</form> 
    </li>
    <li>
	Database :  <b>LISDB (accounts)</b> 
	<form action="<?=$target?>" method="post" class="inline-form">
	    <input type="hidden" name="db" value="LISDB">
	    <input type="hidden" name="tables" value="no">
	    <button type="submit" class="btn btn-primary btn-small">Create</button>
	</form>
	<form action="<?=$target?>" method="post" class="inline-form">
	    <input type="hidden" name="db" value="LISDB">
	    <input type="hidden" name="tables" value="yes">
	    <button type="submit" class="btn btn-primary btn-small">+ tables</button>
	</form>
    </li>
</ul>

</div>