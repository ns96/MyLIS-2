<?php
    
$user_link = base_url()."group/manage/users_main";
$location_link = base_url()."group/manage/locations_main";
$inventory_link = base_url()."group/manage/inventory_main";
$module_link = base_url()."group/manage/modules_main";
$groupinfo_link = base_url()."group/manage/groupinfo_main";
$home_link = base_url()."group/main";;
?>

<ul class="nav nav-tabs">
    <li id="user-tab">
        <a href="<?=$user_link?>">Users</a>
    </li>
    <li id="location-tab">
        <a href="<?=$location_link?>">Locations</a>
    </li>
    <li id="inventory-tab">
        <a href="<?=$inventory_link?>">Inventory</a>
    </li>
    <li id="modules-tab">
        <a href="<?=$module_link?>">Modules</a>
    </li>
    <li id="group-tab">
        <a href="<?=$groupinfo_link?>">Group Information</a>
    </li>
    <li id="home-tab">
        <a href="<?=$home_link?>">Home</a>
    </li>
</ul>
<div style="background-color:white; height: 10px; margin-bottom: 20px; border-bottom: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD"></div>

<script type='text/javascript'>
    var page = '<?=$page?>';
    switch (page){
        case 'user':
            $("#user-tab").addClass('active');
            break;
        case 'location':
            $("#location-tab").addClass('active');
            break;
        case 'inventory':
            $("#inventory-tab").addClass('active');
            break;
        case 'module':
            $("#modules-tab").addClass('active');
            break;
        case 'group':
            $("#group-tab").addClass('active');
            break;
    }
</script>


