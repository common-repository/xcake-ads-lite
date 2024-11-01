<?php
/* Mess around at your own risk. If it stops working, blame yourself ;D */
/* But,no, seriously, don't mess around. It's hell over here. About 5 months on and off this project and the code is all messed up. Upcomming version I'll fix this all. */

function xc_KillEwok($xc_ad_diediedie) {      
	global $wpdb;
	$adtable_name = $wpdb->prefix . "xcads";
	$wpdb->query("DELETE FROM `".$adtable_name."` WHERE ident = ".$xc_ad_diediedie);
	
}

if($_GET['type'] == "delete") {
	$xc_ad_todel = $_GET['ident'];
	xc_KillEwok($xc_ad_todel);
	echo "<script type='text/javascript'>window.location = '".$_SERVER['HTTP_REFERER']."';</script>";
}

/* Deliciousness at its best! */


if($_POST['xc_ad_sent'] == 'Y') {
	//Form data sent
	global $wpdb;
	$adtable_name = $wpdb->prefix . "xcads";
	//Lets get latests id
	$xc_thang = $wpdb->get_row( "SELECT * FROM $adtable_name ORDER BY ident DESC LIMIT 0 , 1" );
	$xc_ad_latestid = $xc_thang->ident;
	$xc_ad_latestid++;
	
	$xc_ad_name = stripslashes($_POST['xc_ad_name']);
	$xc_ad_adtype = $_POST['adtype'];
	if ($xc_ad_adtype == "xc_image") {
		$xc_ad_img = stripslashes($_POST['xc_ad_img']);
		$xc_ad_url = stripslashes($_POST['xc_ad_imgurl']);
		$xc_codeprint = $xc_ad_img;
		$xc_ad_targetz = $xc_ad_url;
	}
	elseif ($xc_ad_adtype == "xc_flash") {
		$xc_codeprint = stripslashes($_POST['xc_ad_flash']);
	}
	elseif ($xc_ad_adtype == "xc_text") {
		$xc_ad_text = stripslashes($_POST['xc_ad_text']);
		$xc_ad_texturl = stripslashes($_POST['xc_ad_texturl']);
		$xc_codeprint = $xc_ad_text;
		$xc_ad_targetz = $xc_ad_texturl;
	}
	$xc_ad_exp_days = stripslashes($_POST['xc_ad_exp_days']);
	$xc_ad_start_date = date('d/m/Y');
	$xc_ad_status = "active";
	
	$sql = "INSERT INTO `$adtable_name` (`ident`, `name`, `targetz`, `clicks`,	`maxclicks`, `start_date`, `exp_page`, `estatus`, `codeprint`, `adtype`) VALUES (NULL, '$xc_ad_name', '$xc_ad_targetz', '0', '0', '$xc_ad_start_date', '$xc_ad_exp_days', '$xc_ad_status', '$xc_codeprint', '$xc_ad_adtype');)";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	
	?>
	<div class="updated fade"><p><strong><?php _e('Options saved','xc_ad'); ?></strong></p></div><?php
}	

?>

<div class="wrap">
<?php include( dirname(__FILE__) . '/xc_ad_scripts.php'); ?>
<?php if($_GET['type'] == "edit") { ?>
<h2><?php _e('Edit ad','xc_ad'); ?></h2>
	<?php
	$theid = $_GET['ident'];
	global $wpdb;
	$adtable_name = $wpdb->prefix . "xcads";
	$xc_thang = $wpdb->get_row( "SELECT * FROM $adtable_name WHERE ident = '$theid'" );
	$xc_adtype = $xc_thang->adtype;
	switch($xc_adtype) {
		case "xc_image":
		$xc_image_field = 'style="display:table-row"';
		$xc_text_field = $xc_flash_field = 'style="display:none"';
		$xc_image_check = "checked";
		$xc_text_check = $xc_flash_check = '';
		break;
		
		case "xc_text":
		$xc_image_field = $xc_flash_field = 'style="display:none"';
		$xc_text_field = 'style="display:table-row"';
		$xc_image_check = $xc_flash_check = '';
		$xc_text_check = "checked";
		break;
		
		case "xc_flash":
		$xc_image_field = $xc_text_field = 'style="display:none"';
		$xc_flash_field = 'style="display:table-row"';
		$xc_image_check = $xc_text_check = '';
		$xc_flash_check = "checked";
		break;
		
		}
	$xc_name_field = $xc_thang->name;
	$xc_targetz_field = $xc_thang->targetz;
	$xc_code_field = $xc_thang->codeprint;
	$xc_enddate_field = $xc_thang->end_date;
	
	$xc_button_text = __('  Edit  ','xc_ad');
	
	} else { ?>
<h2><?php _e('Create ad','xc_ad'); ?></h2>
<?php 
	$xc_image_field = $xc_text_field = $xc_flash_field = 'style="display:none"';
	$xc_image_check = $xc_text_check = $xc_flash_check = '';
	
	$xc_button_text = __('  Add new  ','xc_ad');
}
?>

<form name="xcake_add" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<table class="widefat">
<input type="hidden" name="xc_ad_sent" value="Y">
<tr>
<td><?php _e('Name','xc_ad'); ?></td>
<td><input type="text" name="xc_ad_name" size="50" value="<?php echo $xc_name_field; ?>" /></td>
</tr>
<tr>
<td><?php _e('Ad type','xc_ad'); ?></td>
<td><input type="radio" name="adtype" value="xc_image" onClick="ShowMeImage();" <?php echo $xc_image_check; ?> /> <?php _e('Image','xc_ad'); ?><br />
<input type="radio" name="adtype" value="xc_text" onClick="ShowMeText();" <?php echo $xc_text_check; ?> /> <?php _e('Text','xc_ad'); ?><br />
<input type="radio" name="adtype" value="xc_flash" onClick="ShowMeFlash();" <?php echo $xc_flash_check; ?> /> <?php _e('Flash / Code','xc_ad'); ?></td>
</tr>
<tr id="xc_imagerow" <?php echo $xc_image_field; ?>>
<td><?php _e('Image URL','xc_ad'); ?></td>
<td><input type="text" name="xc_ad_img" size="50" value="<?php echo $xc_code_field; ?>" /></td>
</tr>
<tr id="xc_imagerowurl" <?php echo $xc_image_field; ?>>
<td><?php _e('Site URL','xc_ad'); ?></td>
<td><input type="text" name="xc_ad_imgurl" size="50" value="<?php echo $xc_targetz_field; ?>" /></td>
</tr>

<tr id="xc_flashrow" <?php echo $xc_flash_field; ?>>
<td><?php _e('Flash code / Other code','xc_ad'); ?></td>
<td><textarea name="xc_ad_flash" cols="40"><?php echo $xc_code_field; ?></textarea></td>
</tr>
<tr id="xc_textrow" <?php echo $xc_text_field; ?>>
<td><?php _e('Text to be linked','xc_ad'); ?></td>
<td><input type="text" name="xc_ad_text" size="50" value="<?php echo $xc_code_field; ?>" /></td>
</tr>
<tr id="xc_texturlrow" <?php echo $xc_text_field; ?>>
<td><?php _e('Site URL','xc_ad'); ?></td>
<td><input type="text" name="xc_ad_texturl" size="50" value="<?php echo $xc_targetz_field; ?>" /></td>
</tr>


<tr>
<td>&nbsp;</td>
<td><input type="submit" name="Submit" value="<?php echo $xc_button_text; ?>" /></td>
</tr>
</table>
</form>
<div style="clear:both; height:20px;"></div>
<h6><?php _e('Found any buys? Please report them to canha (at) xcakeblogs.com.br', 'xc_ad'); ?></h6>

</div><!-- wrap -->

