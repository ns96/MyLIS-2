<?php

function loadGroupMenu(){
	
    $base = base_url()."group/";
    $CI = & get_instance();
    $role = $CI->session->userdata('user')->role;

    $menu = array(
	'chemical'	=>  array(
		'controller'	=>  "chemicals",
		'title'		=>  'Chemicals',
		'icon'		=>  base_url().'images/icons/tube.png',
	),
	'chemical2'	=>  array(
		'controller'	=>  "chemicals",
		'title'	    	=>  'Chemicals',
		'icon'		=>  base_url().'images/icons/tube.png',
	),
	'labsupply'	=>  array(
		'controller'	=>  "supplies",
		'title'		=>  'Sypplies',
		'icon'		=>  base_url().'images/icons/supplies.png',
	),
	'groupmeeting'	=>  array(
		'controller'	=>  "meetings",
		'title'		=>  'Meetings',
		'icon'		=>  base_url().'images/icons/meeting.png',
	),
	'orderbook'	=>  array(
		'controller'	=>  "orders",
		'title'		=>  'Order Book',
		'icon'		=>  base_url().'images/icons/orders2.png',
	),
	'publication'	=>  array(
		'controller'	=>  "publications",
		'title'		=>  'PubTracker',
		'icon'		=>  base_url().'images/icons/pdfs.png',
	),
	'instrulog'	=>  array(
		'controller'	=>  "instrulog",
		'title'		=>  'Instrument Log',
		'icon'		=>  base_url().'images/icons/log.png',
	),
	'grouptask'	=>  array(
		'controller'	=>  "instrulog",
		'title'		=>  'Group Tasks',
		'icon'		=>  '',
	),
	'folder'	=>  array(
		'controller'	=>  "file_folder",
		'title'		=>  'File Folder',
		'icon'		=>  '',
	),
	'weblinks'	=>  array(
		'controller'	=>  "weblinks",
		'title'		=>  'Web Links',
		'icon'		=>  base_url().'images/icons/weblink.png',
	),
    );

    $menuHTML = '';
    foreach($menu as $key => $menuItem){
	if($CI->properties['show.'.$key] == 'yes' && viewLink($key,$role)) {
	    if ($CI->uri->segment(2) == $menuItem['controller']) $classHTML = "class='active'";
		else $classHTML = '';
	    $menuHTML .= "<li $classHTML><a href='".$base.$menuItem['controller']."'>".$menuItem['title']."<img src='".$menuItem['icon']."' class='menu_image' /></a></li>";
	}
    }

    if ($role == 'admin'){
	$menuHTML .= "<li><a href='".$base."manage'>Manage<img src='".base_url()."images/icons/manage.png' class='menu_image' /></a></li>";
    } 

    $menuHTML .= "<li><a href='http://docs.google.com/Doc?id=dg5bsrjs_28dqsgkk5m'>Help<img src='".base_url()."images/icons/help.png' class='menu_image' /></a></li>";

    echo $menuHTML;
}

function loadTopArea(){
    $CI = & get_instance();
    $fullname = $CI->session->userdata('user')->name;
    
    $home_link = base_url().'group/main';
    $profile_link = base_url().'group/accounts/user_profile';
    $group_profile_link = base_url().'group/accounts/group_profile';
    $logout_link = base_url()."group/login/logout";
    
    if ('1' == '1') { ?>
    <a href='<?=base_url()."group/main"?>' style="text-decoration:none; color:#285E8E"><img class="toparea_image" src="<? echo base_url()."images/icons/mylis.png" ?>" />
	My Laboratory Information System</a>
	<div class="btn-group toparea_dropdown">
	    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
		<?=$fullname ?> <span class="caret"></span>
	    </button>
	    <ul class="dropdown-menu" role="menu">
		<li><a href="<?=$home_link ?>">Home</a></li>
		<li><a href="<?=$profile_link ?>">My Profile</a></li>
		<li><a href="<?=$group_profile_link ?>">Group Research Profile</a></li>
		<li class="divider"></li>
		<li><a href="<?=$logout_link ?>">Logout</a></li>
	    </ul>
	</div>
    <div style="clear:both"></div>
    <? }
}

function showPageTitle($title){
     if ('1' == '1') { ?>
	<div style="background-color:#e5e5e5; padding:2px; margin-bottom: 15px">
	    <div style="border-top: 2px solid #d5d5d5; border-bottom: 2px solid #d5d5d5; padding:2px;">
		<span style="font-weight: bold; font-size: 16px; color:grey"><?=$title?></span>
	    </div>
	</div>
    <? }
}