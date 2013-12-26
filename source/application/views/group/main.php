<?php
  
  $base = base_url()."group/";
  
  $used = $quotaUsage['used'];
  $total = $quotaUsage['total'];
  $percentage = round(100*$used/$total);
  
  // initialize some links
  //$backup_link = $base."backup";
  $message_link = $base."/main/displayMessages";
  $upgrade_link =  $base."/accounts/upgrade";
  ?>

    <strong>Welcome to the <?=$properties['group.name']?> Group site!</strong>
    <br>
    <!-- QUOTA AREA -->
    <div class="progress-wrapper">
	<br>
	<form action="<?=$upgrade_link?>" method="post" style="float:right; margin:0px;"><input type="submit" value="Upgrade" class="btn btn-primary btn-small" style="margin-left:10px; margin-right:15px"></form>
	<div style="float:right; margin-left:10px">You have <?=($total-$used) ?>MB available in your quota!</div>
	<div class="progress">
	    <div class="bar" style="width: <?=$percentage."%" ?>"><?= $percentage."%"?></div>
	</div>
	<div style="clear:both"></div>
    </div>  
    <!-- MESSAGE LISTING AREA -->
    <div id="iFrame1Wrapper">
	<table class="formTopBar" style="width: 100%" cellpadding="4" cellspacing="2">
	<tr>
	    <td style="background-color: rgb(180,200,230); width: 25%;">
		Message Listing
	    </td>
	</tr>
	</table>
	<iframe id="iframe1" name="iframe1" width="100%" height="250" src="<?=$message_link?>" scrolling="auto" frameborder="0">
	</iframe>
    </div>
    <!-- MESSAGE FORM AREA -->
    <div class="formWrapper">
	<?=$messageForm ?>
    </div>
    <br> <?=$ads_html ?>
    