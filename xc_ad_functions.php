<?php


/* Check if columns exists - an installation / upgrading function */
/*
function xc_checkColumns($db, $column, $column_attr = "TEXT NOT NULL" ){
    $exists = false;
    $columns = dbDelta("SHOW COLUMNS FROM $db");
    while($c = mysql_fetch_assoc($columns)){
        if($c['Field'] == $column){
            $exists = true;
            break;
        }
    }      
    if(!$exists){
        mysql_query("ALTER TABLE `$db` ADD `$column`  $column_attr");
    }
}
*/

/* Language */

function xc_loadlang() {
	//http://www.wdmac.com/how-to-create-a-po-language-translation
	//http://www.lost-in-code.com/platforms/wordpress/wordpress-translate-a-plugin/
	load_plugin_textdomain( 'xc_ad', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

/* Echo ads */
function xcConvertImageAd($xc_cia_img, $xc_cia_url, $xc_cia_name, $xc_cia_id) {
	$xc_cia_link = get_bloginfo('url')."/index.php?xcad=".$xc_cia_id;
	return "<li><a href='".$xc_cia_link."' title='".$xc_cia_name."' id='xcake_ads_id".$xc_cia_id."'><img src='".$xc_cia_img."' alt='".$xc_cia_name."' /></a></li>";
}

function xcConvertTextAd($xc_cta_text, $xc_cta_url, $xc_cta_id) {
	$xc_cta_link = get_bloginfo('url')."/index.php?xcad=".$xc_cta_id;
	return "<li><a href='".$xc_cta_link."' title='".$xc_cta_text."' id='xcake_ads_id_".$xc_cta_id."'>".$xc_cta_text."</a></li>";
}

/* Menu */
function xc_ad_main() {global $wpdb; include 'xc_ad_main.php';}
function xc_ad_addmenu() {global $wpdb; include 'xc_ad_add.php';}

/* Show ads */
function xcad_ShowAds($xcad_amountads, $xcad_randomize, $xcad_adtype) {
	global $wpdb;
	$table_name = $wpdb->prefix . "xcads";

	if ($xcad_amountads == 0 && $xcad_randomize == FALSE) {
		$xcadshow = $wpdb->get_results("SELECT * FROM `$table_name` WHERE adtype = '$xcad_adtype' ORDER BY 'ident'");
	}
	elseif ($xcad_amountads == 0 && $xcad_randomize == TRUE) {
		$xcadshow = $wpdb->get_results("SELECT * FROM `$table_name` WHERE adtype = '$xcad_adtype' ORDER BY RAND()");
	}
	elseif ($xcad_amountads != 0 && $xcad_randomize == TRUE) {
		$xcadshow = $wpdb->get_results("SELECT * FROM `$table_name` WHERE adtype = '$xcad_adtype' ORDER BY RAND() LIMIT 0 , $xcad_amountads");
	}
	elseif ($xcad_amountads != 0 && $xcad_randomize == FALSE) {
		$xcadshow = $wpdb->get_results("SELECT * FROM `$table_name` WHERE adtype = '$xcad_adtype' ORDER BY 'ident' LIMIT 0 , $xcad_amountads");
	}
	echo "<ul id='xc_ads_ul'>";
		foreach ($xcadshow as $xcadshow) {
			$xc_ad_img = $xcadshow->codeprint;
			$xc_ad_url = $xcadshow->targetz;
			$xc_ad_name = $xcadshow->name;
			$xc_ad_latestid = $xcadshow->ident;
			$xc_ad_adtype = $xcadshow->adtype;
			if ($xc_ad_adtype == "xc_image") {
				echo xcConvertImageAd($xc_ad_img, $xc_ad_url, $xc_ad_name, $xc_ad_latestid);
			}
			elseif ($xc_ad_adtype == "xc_flash") echo $xc_ad_img;
			elseif ($xc_ad_adtype == "xc_text") {
				echo xcConvertTextAd($xc_ad_img,$xc_ad_url, $xc_ad_latestid);
			}

		}
	echo "</ul>";
	
}

/* Widgets */
function xcad_group1_widget($args) {
	$data = get_option('xcax_group1_widget_values');
	echo $args['before_widget'];
    echo $args['before_title'] . $data['option1'] . $args['after_title'];
    $xcad_group1 = "1";
    $xcad_amountads = $data['option2'];
    $xcad_randomize = $data['option3'];
    $xcad_adtype = $data['option4'];
    xcad_ShowAds($xcad_amountads, $xcad_randomize, $xcad_adtype);
    echo $args['after_widget'];
}

/* Controlador do widget */
function xcad_group1_widget_control(){
	$data = get_option('xcax_group1_widget_values');
	if ($data['option3'] == TRUE) $xcad_checkchecked1 = 'checked="checked"';
	else $xcad_checkchecked1 = '';
	?>
	<p><label for="xcwid_title1"><?php _e('Title','xc_ad'); ?><input class="widefat" name="xcwid_title1" type="text" value="<?php echo $data['option1']; ?>" /></label></p>
	<p><label for="xcwid_ammount1"><?php _e('Amount of ads to show (0 to display all)','xc_ad'); ?><input class="widefat" name="xcwid_ammount1" type="text" value="<?php echo $data['option2']; ?>" /></label></p>
	<p><label for="xcwid_adtype"><?php _e('Ad type to display','xc_ad'); ?><br />
	<select name="xcwid_adtype">
	<option value="xc_image" <?php if($data['option4'] == "xc_image") echo "selected"; ?>><?php _e('Image','xc_ad'); ?></option>
  	<option value="xc_text" <?php if($data['option4'] == "xc_text") echo "selected"; ?>><?php _e('Text','xc_ad'); ?></option>
  	<option value="xc_flash" <?php if($data['option4'] == "xc_flash") echo "selected"; ?>><?php _e('Flash / Code','xc_ad'); ?></option>
	</select>
	</label></p>

	<p><label for="xcwid_random1"><input type="checkbox" name="xcwid_random1" value="yes" <?php echo $xcad_checkchecked1; ?> /> <?php _e('Randomize','xc_ad'); ?></label>
</p>
  
  <?php
	if (isset($_POST['xcwid_title1'])){ 
	$data['option1'] = attribute_escape($_POST['xcwid_title1']);
	$data['option2'] = attribute_escape($_POST['xcwid_ammount1']);
	$data['option3'] = attribute_escape($_POST['xcwid_random1']);
	$data['option4'] = attribute_escape($_POST['xcwid_adtype']);
	update_option('xcax_group1_widget_values', $data);
	}
}

?>