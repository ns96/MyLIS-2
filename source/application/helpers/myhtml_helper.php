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
		'controller'	=>  "orderbook",
		'title'		=>  'Order Book',
		'icon'		=>  base_url().'images/icons/orders2.png',
	),
	'publication'	=>  array(
		'controller'	=>  "publications",
		'title'		=>  'PubTracker',
		'icon'		=>  base_url().'images/icons/article.png',
	),
	'instrulog'	=>  array(
		'controller'	=>  "instrulog",
		'title'		=>  'Instrument Log',
		'icon'		=>  base_url().'images/icons/log.png',
	),
	'grouptask'	=>  array(
		'controller'	=>  "grouptask",
		'title'		=>  'Group Tasks',
		'icon'		=>  base_url().'images/icons/todo.png',
	),
	'folder'	=>  array(
		'controller'	=>  "file_folder",
		'title'		=>  'File Folder',
		'icon'		=>  base_url().'images/icons/pdfs.png',
	),
	'weblinks'	=>  array(
		'controller'	=>  "weblinks",
		'title'		=>  'Web Links',
		'icon'		=>  base_url().'images/icons/weblink.png',
	),
    );

    $menuHTML = '';
    // Add a 'Home' item 
    if ($CI->uri->segment(2) == 'main'){
	$classHTML = "class='active'";
    } else {
	$classHTML = "";
    }
    $menuHTML .= "<li $classHTML><a href='".$base."main'>Home Page<img src='".base_url()."images/icons/home.png' class='menu_image' /></a></li>";
    // Add menu items that are configured as visible 
    foreach($menu as $key => $menuItem){
	if($CI->properties['show.'.$key] == 'yes' && viewLink($key,$role)) {
	    $part3 = $CI->uri->segment(3);
	    if (!empty($part3)) {
		$url_part = $CI->uri->segment(2).'/'.$CI->uri->segment(3);
		if ($url_part == $menuItem['controller']) {
		    $classHTML = "class='active'";
		} else 
		    $classHTML = '';
	    } else {
		if ($CI->uri->segment(2) == $menuItem['controller']) {
		    $classHTML = "class='active'";
		} else 
		    $classHTML = '';
	    }
	    $menuHTML .= "<li $classHTML><a href='".$base.$menuItem['controller']."'>".$menuItem['title']."<img src='".$menuItem['icon']."' class='menu_image' /></a></li>";
	}
    }
    // Add menu items visible only to admin
    if ($role == 'admin'){
	$menuHTML .= "<li><a href='".$base."manage'>Manage<img src='".base_url()."images/icons/manage.png' class='menu_image' /></a></li>";
    } 
    // Add a help item
    $menuHTML .= "<li><a href='http://docs.google.com/Doc?id=dg5bsrjs_28dqsgkk5m'>Help<img src='".base_url()."images/icons/help.png' class='menu_image' /></a></li>";

    echo $menuHTML;
}

function loadAdminMenu(){
	
    $base = base_url()."admin/";
    $CI = & get_instance();
    $role = $CI->session->userdata('user')->role;

    $menu = array(
	'home'	=>  array(
		'controller'	=>  "main",
		'title'		=>  'Home Page',
		'icon'		=>  base_url().'images/icons/home.png',
	),
	'managedb'	=>  array(
		'controller'	=>  "managedb",
		'title'		=>  'Manage DB',
		'icon'		=>  base_url().'images/icons/tube.png',
	),
	'emaillist'	=>  array(
		'controller'	=>  "emails",
		'title'		=>  'Email List Manager',
		'icon'		=>  base_url().'images/icons/supplies.png',
	),
	'view'		=>  array(
		'controller'	=>  "accounts/index",
		'title'	    	=>  'View Accounts',
		'icon'		=>  base_url().'images/icons/tube.png',
	),
	'add	'	=>  array(
		'controller'	=>  "accounts/create",
		'title'		=>  'Add account',
		'icon'		=>  base_url().'images/icons/meeting.png',
	),
	'test'	=>  array(
		'controller'	=>  "accounts/create/test",
		'title'		=>  'Add Test Account',
		'icon'		=>  base_url().'images/icons/demo.png',
	),
	'sandbox'	=>  array(
		'controller'	=>  "accounts/create/sandbox",
		'title'		=>  'Add Sandbox Account',
		'icon'		=>  base_url().'images/icons/sandbox.png',
	),
	'messages'	=>  array(
		'controller'	=>  "messages",
		'title'		=>  'Message Poster',
		'icon'		=>  base_url().'images/icons/log.png',
	),
	'managefiles'	=>  array(
		'controller'	=>  "managefiles",
		'title'		=>  'File Manager',
		'icon'		=>  base_url().'images/icons/pdfs.png',
	),
	'update'	=>  array(
		'controller'	=>  "accounts/update",
		'title'		=>  'Update Accounts',
		'icon'		=>  base_url().'images/icons/update.png',
	),
    );

    $menuHTML = '';
    
    foreach($menu as $key => $menuItem){
	$part3 = $CI->uri->segment(3);
	if (!empty($part3)) {
	    $url_part = $CI->uri->segment(2).'/'.$CI->uri->segment(3);
	    if ($url_part == $menuItem['controller']) {
		$classHTML = "class='active'";
	    } else 
		$classHTML = '';
	} else {
	    if ($CI->uri->segment(2) == $menuItem['controller']) {
		$classHTML = "class='active'";
	    } else 
		$classHTML = '';
	}
	$menuHTML .= "<li $classHTML><a href='".$base.$menuItem['controller']."'>".$menuItem['title']."<img src='".$menuItem['icon']."' class='menu_image' /></a></li>";
    }

    $menuHTML .= "<li><a href='http://docs.google.com/Doc?id=dg5bsrjs_28dqsgkk5m'>Help<img src='".base_url()."images/icons/help.png' class='menu_image' /></a></li>";

    echo $menuHTML;
}

function loadGroupTopArea(){
    $CI = & get_instance();
    $fullname = $CI->session->userdata('user')->name;
    
    $home_link = base_url().'group/main';
    $profile_link = base_url().'group/accounts/user_profile';
    $group_profile_link = base_url().'group/accounts/group_profile';
    $logout_link = base_url()."group/login/logout";
    
    if ('1' == '1') { ?>
    <a href='<?=base_url()."group/main"?>' style="text-decoration:none; color:#285E8E"><img class="toparea_image" src='<?= base_url()."images/icons/mylis.png" ?>' />
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

function loadAdminTopArea(){
    $CI = & get_instance();
    $fullname = $CI->session->userdata('user')->name;
    
    $home_link = base_url().'admin/main';
    $profile_link = base_url().'admin/accounts/user_profile';
    $logout_link = base_url()."admin/login/logout";
    
    if ('1' == '1') { ?>
    <a href='<?=base_url()."admin/main"?>' style="text-decoration:none; color:#285E8E"><img class="toparea_image" src="<? echo base_url()."images/icons/mylis.png" ?>" />
	My Laboratory Information System</a>
	<div class="btn-group toparea_dropdown">
	    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
		<?=$fullname ?> <span class="caret"></span>
	    </button>
	    <ul class="dropdown-menu" role="menu">
		<li><a href="<?=$home_link ?>">Home</a></li>
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

function showModal($title,$message,$destination=''){
  
    echo "<html><head>
    <link rel='stylesheet' type='text/css' href='".base_url().'css/jquery-ui-1.10.3.custom.min.css'."' />
    <link rel='stylesheet' type='text/css' href='".base_url().'css/my.css'."' />
    <script type='text/javascript' src='".base_url()."/js/jquery.min.js'></script>
    <script type='text/javascript' src='".base_url()."/js/jquery-ui-1.10.3.custom.min.js'></script>
    <script type='text/javascript' src='".base_url()."/js/my.js'></script></head><body>";
    echo "<script type='text/javascript'>myModalMessage('$title','$message','$destination');</script>";
    echo "</body></html>";
}
