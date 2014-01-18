<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $page_title.' ('.$this->properties['group.name']."'s Group)"; ?></title>
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
	    <div class="login-well">
		
		<div class="login-well2">
			<table style="margin:0 auto">
			    <tr>
				<td><img src='<?=base_url()."images/icons/mylis.png"?>' /></td>
				<td vertical-align="middle">The <?=$this->properties['group.name']?>'s Group Login</td>
			    </tr>
			</table>
		    </div>
		
		<form action="<?=$target?>" method="POST" class="form-horizontal" style="margin:20px">
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
    <div>
        <? if (isset($login_error)) echo $login_error; ?>
    </div>
<hr style="width: 100%; height: 1px;">
    <div align="center">Please contact your <a href="mailto:lisadmin@instras.com">site administrator</a> if you have trouble logging in.</div>
<hr style="width: 100%; height: 1px;">
    
</body>
</html>