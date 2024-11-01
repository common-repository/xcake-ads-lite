<?php
/*
Plugin Name: xCake Ads Lite
Plugin URI: http://www.xcake.com.br
Description: Manage ads on your sidebar easily. Still in beta testing mode. Please report bugs to canha(at)xcakeblogs.com.br
Author: xCakeBlogs (Canha)
Author URI: http://www.xcake.com.br
Version: 0.1.1
*/

/* Work it harder, make it better, do it faster, makes us strong, more than ever, hour after, our work is never over */



define("MANAGEMENT_PERMISSION", "edit_themes"); //The minimum privilege required to manage ads. http://tinyurl.com/wpprivs

include( dirname(__FILE__) . '/xc_ad_functions.php');

$table_name = $wpdb->prefix . "xc_ads";

/* Redirect */
function xc_ad_redirect() {
	if (isset($_GET['xcad']) && $_GET['xcad'] != "" && ctype_digit($_GET['xcad'])) {
	$theid = $_GET['xcad'];
	global $wpdb;
	$adtable_name = $wpdb->prefix . "xcads";
	$thead = $wpdb->get_row("SELECT targetz FROM $adtable_name WHERE ident = '$theid'", OBJECT);
	$update = "UPDATE ". $adtable_name ." SET clicks=clicks+1 WHERE ident='$theid'";
	$results = $wpdb->query( $update );
	header("Location: $thead->targetz");
	exit;
	}
}

/* Menus */
if (is_admin()) { add_action('admin_menu', 'xc_ad_admin_actions'); } //Run menus

if (is_admin()) {
	function xc_ad_admin_actions() {
		add_menu_page("xCake Ad", "xCake Ads", MANAGEMENT_PERMISSION, __FILE__, "xc_ad_main");		
		add_submenu_page(__FILE__, __('Create Ad', 'xc_ad'), __('Create Ad', 'xc_ad'), MANAGEMENT_PERMISSION, "xc_ad_addmenu", "xc_ad_addmenu");
	}
}

register_activation_hook(__FILE__, 'xc_ads_runinstall');

/* Install */
function xc_ads_runinstall () {
	global $wpdb;
	global $xcad_db_version;
	$table_name = $wpdb->prefix . "xcads";
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
   $sql = "CREATE TABLE ".$table_name." (
	ident int(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name TEXT NOT NULL,
	email TEXT NOT NULL,
	targetz TEXT NOT NULL,
	clicks TEXT NOT NULL,
	maxclicks TEXT NOT NULL,
	start_date TEXT NOT NULL,
	exp_page TEXT NOT NULL,
	estatus TEXT NOT NULL,
	codeprint TEXT NOT NULL,
	adtype TEXT NOT NULL,
	UNIQUE KEY ident (ident)
	);
	";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	}
}

/* Create widgets */
function xcads_widgets_init() {
	$xc_group_descr1 = 'xCake Ads';
	$widget_ops = array('classname'    =>  'widget_xc_group1','description'  =>  'Show Ads',);
	wp_register_sidebar_widget( 'xc_asdf_group1', $xc_group_descr1, 'xcad_group1_widget', $widget_ops );
	wp_register_widget_control( 'xc_asdf_group1', $xc_group_descr1, 'xcad_group1_widget_control' );
}

/* Actions */
add_action('init', 'xc_loadlang');
add_action('init', 'xc_ad_redirect');
add_action('init', 'xcads_widgets_init');

/* Music's got me feeling so free, We're gonna celebrate, Celebrate and dance for free, One more time */

/*
Copyright 2011 xCakeBlogs.com.br

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
?>