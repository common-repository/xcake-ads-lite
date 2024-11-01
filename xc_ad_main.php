<?php
function xcAdType($xc_adtype) {
	switch ($xc_adtype) {
		case "xc_image":
			return "Image";
			break;
		case "xc_flash":
			return "Code";
			break;
		case "xc_text":
			return "Text";
			break;
	}
}

function get_plugin_url() {
// WP < 2.6
if ( !function_exists('plugins_url') )
return get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__));

return plugins_url(plugin_basename(dirname(__FILE__)));
}
if(isset($_GET['saved'])) {
	echo "<div class='updated'><p><strong>".__('Options saved', 'xc_ad')."</strong></p></div>";
}

echo '<div class="wrap"><h2>Main</h2>';
	echo "<table class='widefat' style='width:99%'><thead><tr>";
	echo "<th scope='col'>";
	_e('ID','xc_ad');
	echo "</th><th scope='col'>";
	_e('Name','xc_ad');
	echo "</th><th scope='col'>";
	_e('Clicks','xc_ad');
	echo "</th><th scope='col'>";
	_e('Start date','xc_ad');
	echo "</th><th scope='col'>";
	_e('Ad type','xc_ad');
	echo "</th><th scope='col'>";
	_e('Status','xc_ad');
	echo "</th><th scope='col'>";
	_e('Action','xc_ad');
	echo "</th></tr></thead><tbody>";
	global $wpdb;
	$table_name = $wpdb->prefix . "xcads";
	$xcadshow = $wpdb->get_results("SELECT * FROM `$table_name` WHERE estatus != '0' ORDER BY ident ASC", OBJECT);
	if ($xcadshow) {
	echo "<script type='text/javascript'>
			function confirmation(smthngsmthng) {
			var thing = \"admin.php?page=xc_ad_addmenu&type=delete&ident=\" + smthngsmthng;
			var answer = confirm('".__('Do you really wish to delete this ad?', 'xc_ad')."');
			if (answer){ window.location = thing; } 
			}
			</script>";
		foreach ($xcadshow as $xcadshow){
			echo "<tr>";
			echo "<td>".$xcadshow->ident."</td>";
			echo "<td>".$xcadshow->name."</td>";
			echo "<td>".$xcadshow->clicks."</td>";
			echo "<td>".$xcadshow->start_date."</td>";
			$xcadtype = $xcadshow->adtype;
			echo "<td>".xcAdType($xcadtype)."</td>";
			echo "<td>".$xcadshow->estatus."</td>";
			
			echo "<td><a href='#' title='Delete' onclick='confirmation(\"".$xcadshow->ident."\")'><img src='".get_plugin_url()."/images/del.png' /></a>&nbsp;&nbsp;<a href='admin.php?page=xc_ad_addmenu&type=edit&ident=".$xcadshow->ident."'><img src='".get_plugin_url()."/images/edit.png' /></a>
			</td>";
			echo "</tr>";
		}
	}
	else echo '<tr> <td colspan="8">'.__('No ads found.', 'xc_ad').'</td> </tr>';
	echo "</tbody></table>";
	echo '<div style="clear:both; height:20px;"></div><h6>';
	_e('Found any buys? Please report them to canha (at) xcakeblogs.com.br', 'xc_ad');
	echo '</h6></div>';

?>