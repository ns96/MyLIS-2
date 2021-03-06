<!-- 
    This is the default template for views. It just loads the boostrap .css and .js files and
    expects a $page_title variable to be passed from the controller.
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $page_title; ?></title>
<link rel="stylesheet" type="text/css" href="<? echo base_url().'css/bootstrap.css'; ?>" />
<link rel="stylesheet" type="text/css" href="<? echo base_url().'css/jquery-ui-1.10.3.custom.min.css'; ?>" />
<link rel="stylesheet" type="text/css" href="<? echo base_url().'css/my.css'; ?>" />

<script type='text/javascript' src="<? echo base_url(); ?>/js/jquery.min.js"></script>
<script type='text/javascript' src="<? echo base_url(); ?>/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type='text/javascript' src="<? echo base_url(); ?>/js/bootstrap.min.js"></script>
<script type='text/javascript' src="<? echo base_url(); ?>/js/my.js"></script>
</head>
<body>

<div class="container">
    <div class="row">
	<!-- TOP SECTION -->
	<div class="span12">
	    <div class="topwell">
		<? 
		  $restricted = $this->session->userdata('direct_entry');
		  if (!empty($restricted)) 
		      loadRestrictedGroupTopArea(); 
		  else
		      loadGroupTopArea();
		?>
	    </div>
	</div>
    </div>
    <div class="row">
	<!-- MAIN CONTENT SECTION -->
        <div class="span12">
	     <div class="well2">
		 <? showPageTitle($page_title); ?>
		 <? include FCPATH."application/views/".$view_name.".php"; ?>
	     </div>
	</div>
    </div>
</div>    
    
<script type='text/javascript' src="<? echo base_url(); ?>/js/bootstrap-filestyle.min.js"></script>
</body>
</html>