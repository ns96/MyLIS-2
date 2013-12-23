<?php

echo $menuHTML;

// add the table that allows setting whether a module is visible
$cell_color1 = 'rgb(180,200,230)'; // a light blue
$cell_color2 = 'rgb(240,240,240)'; // a light gray
$update_link = base_url().'group/manage/modules_update';
$configure_link = base_url().'group/manage/modules_configure';

$module_list = array(
    array(
	'property'	=>  'show.chemical',
	'input_name'	=>  'chemical',
	'module_name'	=>  'Chemicals',
	'text'		=>  'Inventory of group chemicals.'
    ),
    array(
	'property'	=>  'show.chemical2',
	'input_name'	=>  'chemical2',
	'module_name'	=>  'Department Chemicals',
	'text'		=>  "".chemical2($properties)
    ),
    array(
	'property'	=>  'show.labsupply',
	'input_name'	=>  'labsupply',
	'module_name'	=>  'Supplies',
	'text'		=>  'Inventory of group supplies.'
    ),
    array(
	'property'	=>  'show.groupmeeting',
	'input_name'	=>  'groupmeeting',
	'module_name'	=>  'Group Meeting',
	'text'		=>  'List of group meetings with attached files.'
    ),
    array(
	'property'	=>  'show.orderbook',
	'input_name'	=>  'orderbook',
	'module_name'	=>  'Order Book',
	'text'		=>  'List of group orders.'
    ),
    array(
	'property'	=>  'show.publication',
	'input_name'	=>  'publication',
	'module_name'	=>  'PubTracker',
	'text'		=>  'List of group publications at various stages of completion.'
    ),
    array(
	'property'	=>  'show.instrulog',
	'input_name'	=>  'instrulog',
	'module_name'	=>  'Instrulog',
	'text'		=>  'Online log book for group instruments.'
    ),
    array(
	'property'	=>  'show.grouptask',
	'input_name'	=>  'grouptask',
	'module_name'	=>  'Group Task',
	'text'		=>  'Set group task for members.'
    ),
    array(
	'property'	=>  'show.folder',
	'input_name'	=>  'folder',
	'module_name'	=>  'File Folder',
	'text'		=>  'A simple file folder for storing important group files.'
    ),
    array(
	'property'	=>  'show.weblinks',
	'input_name'	=>  'weblinks',
	'module_name'	=>  'Web Links',
	'text'		=>  'A list of websites the group may find usefull.'
    ),
);

?>

<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td style="background-color: rgb(180,200,230)">
		Select Modules Which Are Displayed 
	    </td>
	</tr>
	</tbody>
    </table>
    <form action="<?=$update_link?>" method="POST" class="form-inline"  enctype="multipart/form-data" >
	<input type="hidden" name="task" value="manager_module_updatemenu">    
	<table class="formTable2">
	    <thead>
		<th>Show</th>
		<th>Module Name</th>
		<th>Module Description and Options</th>
	    </thead>
	    <tbody>
		<?
		foreach($module_list as $module){
		    $checked = '';
		    if($properties[$module['property']] == 'yes') {
			$checked = 'checked';
		    }
		    ?>
		<tr>
		    <td>
			<input type="checkbox" name="<?=$module['input_name']?>" value="yes" <?=$checked?>>
		    </td>
		    <td><?=$module['module_name']?></td>
		    <td><?=$module['text']?></td>
		</tr>	   
		<? } ?>
	    </tbody>
	</table>
	<button type="submit" class="btn btn-primary btn-small" style="margin-top: 5px">Update Menu items</button>
	</form>
</div>

<?

function chemical2($properties){

if($properties['show.chemical2'] == 'yes') {
  $link = $proputil['chemical2.link'];
  $sitename = $proputil['chemical2.sitename'];
} else {
    $link = '';
    $sitename = '';
}
$output = "Inventory of department chemicals. Please provide name and url of list below.<br>
	Name : <input type='text' name='name' value='".$sitename."'> 
	URL : <input type='text' name='url' value='".$link."'> ";
return $output;
}
?>

<div class="formWrapper">
    <table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tbody>
	<tr>
	    <td style="background-color: rgb(180,200,230)">
		Configure Modules
	    </td>
	</tr>
	</tbody>
    </table>
    <form action="<?=$configure_link?>" method="POST" enctype="multipart/form-data" class="form-inline">
	<input type="hidden" name="task" value="manager_module_updateconfig">      
	<table class="formTable">
	    <thead>
		<th>Module Name</th>
		<th>Module Configuration Options</th>
	    </thead>
	    <tbody>
		<tr>
		    <td rowspan="3" style="vertical-align: middle; text-align: center; font-weight: bold">
			Order Book
		    </td>
		    <td>
			<?
			$checked = '';
			if((isset($proputil['orders.private']))&&($proputil['orders.private'] == 'yes')) {
			    $checked = 'checked';
			}
			echo '<input type="checkbox" name="orders_private" value="yes" '.$checked.'> 
			Keep individual orders private.';
			?>
		    </td>
		</tr>
		<tr>
		    <td>
			<?
			$checked = '';
			if((isset($proputil['orders.notifybuyer']))&&($proputil['orders.notifybuyer'] == 'yes')) {
			    $checked = 'checked';
			}
			echo '<input type="checkbox" name="orders_notifybuyer" value="yes" '.$checked.'> 
			Notify buyer that a user as submitted items for odering.';
			?>
		    </td>
		</tr>
		<tr>
		    <td>
			<?
			$checked = '';
			if((isset($proputil['orders.notifyuser']))&&($proputil['orders.notifyuser'] == 'yes')) {
			    $checked = 'checked';
			}
			echo '<input type="checkbox" name="orders_notifyuser" value="yes" '.$checked.'> 
			Notify users when items they ordered have arrived.';
			?>
		    </td>
		</tr>
	    </tbody>
	</table>
	<button type="submit" class="btn btn-primary btn-small" style="margin-top: 5px">Update Configuration</button>
    </form>
</div>


