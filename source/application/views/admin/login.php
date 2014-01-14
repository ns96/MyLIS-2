<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $page_title; ?></title>
<link rel="stylesheet" type="text/css" href="<? echo base_url().'css/bootstrap.css'; ?>" />
<link rel="stylesheet" type="text/css" href="<? echo base_url().'css/my.css'; ?>" />

<script type='text/javascript' src="<? echo base_url(); ?>/js/jquery.min.js"></script>
<script type='text/javascript' src="<? echo base_url(); ?>/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    <div class="row">
	<div class="span4"></div>
	<div class="span4">
	    <div class="admin-login-well">
		
		<div class="admin-login-well2">
			<table style="margin:0 auto">
			    <tr>
				<td><img src='<?=base_url()."images/icons/mylis.png"?>' /></td>
				<td vertical-align="middle">MyLIS Administrator Login</td>
			    </tr>
			</table>
		    </div>
		
		<form action="<?=base_url().'admin/login/login_request'?>" method="POST" class="form-horizontal" style="margin:20px">
		   <table style="margin:0 auto; border-spacing: 10px; border-collapse: separate">
		       <tr>
			   <td>
				<label for="userid">Username:</label>
			   </td>
			   <td>
				<input type="text" id="userid" name="userid" class="input-block-level input-large" >
			   </td>
		       </tr>
			<tr>
			    <td>
				<label for="password">Password:</label>
			    </td>
			    <td>
				<input type="password" id="password" name="password" class="input-block-level input-large" >
			    </td>
			</tr>
		   </table>
		    
		    <div class="control-group">
			<button type="submit" class="btn btn-primary btn-medium">Login</button>
		    </div>
		    
		</form>
	    </div>
	</div>
	<div class="span4"></div>
    </div>
</div>
      
    
</body>
</html>