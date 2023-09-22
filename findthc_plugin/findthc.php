<?php
/*
Plugin Name: Findthc Plugin
Plugin URI: http://www.findthc.info/
Description: Custom made plugin.
Author: Find THC
Version: 1.0
*/

define('THCP_DIR', plugin_dir_path( __FILE__ ));
define('THCP_URL', plugins_url('',__FILE__));
global $gloc; global $thco; global $thcp;
$thco = get_option('thc_posts_count');
$thcp = get_option('thc_pages');

register_activation_hook( __FILE__, 'thcp_activation' );
function thcp_activation() {
	add_related_roles();

	global $wpdb; global $thcp;
	$wppt = $wpdb->prefix.'posts'; $wppmt = $wpdb->prefix.'postmeta';
	
	$table1 = $wpdb->prefix.'thct_ratings';
	$sql1 = "CREATE TABLE `".$table1."` (
	`ID` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`voter` BIGINT NOT NULL ,
	`post` BIGINT NOT NULL ,
	`type` TEXT NOT NULL ,
	`key` TEXT NOT NULL ,
	`val` INT NULL DEFAULT NULL ,
	`listing` BIGINT NOT NULL ,
	`dispensary` BIGINT NOT NULL ,
	`strain` BIGINT NOT NULL 
	) ENGINE = MYISAM ;";
	thc_createTable($table1, $sql1);

	$table2 = $wpdb->prefix.'thct_suggestions';
	$sql2 = "CREATE TABLE `".$table2."` (
	`ID` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`item` BIGINT NOT NULL ,
	`itype` TEXT NOT NULL ,
	`owner` BIGINT NOT NULL ,
	`user` BIGINT NOT NULL ,
	`data` TEXT NOT NULL ,
	`dtype` TEXT NOT NULL 
	) ENGINE = MYISAM ;";
	thc_createTable($table2, $sql2);

	$table3 = $wpdb->prefix.'thct_claims';
	$sql3 = "CREATE TABLE `".$table3."` (
	`ID` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`email` TEXT NOT NULL ,
	`reps` BIGINT NOT NULL ,
	`post` BIGINT NOT NULL ,
	`status` INT NOT NULL ,
	`dated` DATETIME NOT NULL
	) ENGINE = MYISAM ;";
	thc_createTable($table3, $sql3);
	
	$table4 = $wpdb->prefix.'thct_prices';
	$sql4 = "CREATE TABLE `".$table4."` (
	`pID` BIGINT NOT NULL,
	`pType` TEXT NOT NULL,
	`strain` BIGINT NOT NULL,
	`dispensary` BIGINT NOT NULL,
	`price` FLOAT NOT NULL,
	`p18` FLOAT NOT NULL,
	`p14` FLOAT NOT NULL,
	`p12` FLOAT NOT NULL,
	`poz` FLOAT NOT NULL,
	`p1xR` FLOAT NOT NULL,
	`p5xR` FLOAT NOT NULL,
	`p10xR` FLOAT NOT NULL,
	`p20xR` FLOAT NOT NULL,
	`p30xR` FLOAT NOT NULL,
	`p40xR` FLOAT NOT NULL,
	`p50xR` FLOAT NOT NULL,
	`p100xR` FLOAT NOT NULL,
	PRIMARY KEY (`pID`)
	) ENGINE = MYISAM ;";
	thc_createTable($table4, $sql4);

	$table5 = $wpdb->prefix.'thct_locations';
	$sql5 = "CREATE TABLE `".$table5."` (
	`pID` BIGINT NOT NULL,
	`pType` TEXT NOT NULL,
	`latitude` TEXT NOT NULL,
	`longitude` TEXT NOT NULL,
	`dispType` TEXT NOT NULL,
	`featured` TEXT NOT NULL,
	PRIMARY KEY (`pID`)
	) ENGINE = MYISAM ;";
	thc_createTable($table5, $sql5);

	$table6 = $wpdb->prefix.'thct_phenotypes';
	$sql6 = "CREATE TABLE `".$table6."` (
	`ID` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`strain` BIGINT NOT NULL,
	`ratio` INT NOT NULL,
	`height` TEXT NOT NULL,
	`stock` TEXT NOT NULL,
	`structure` TEXT NOT NULL,
	`odor` TEXT NOT NULL,
	`potency` TEXT NOT NULL,
	`distance` TEXT NOT NULL,
	`yields` INT NOT NULL,
	`tomold` TEXT NOT NULL,
	`topest` TEXT NOT NULL,
	`flav` TEXT NOT NULL,
	`medi` TEXT NOT NULL
	) ENGINE = MYISAM ;";
	thc_createTable($table6, $sql6);

	$table7 = $wpdb->prefix.'thct_tests';
	$sql7 = "CREATE TABLE `".$table7."` (
	`ID` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`pID` BIGINT NOT NULL,
	`phenID` BIGINT NOT NULL,
	`strain` BIGINT NOT NULL,
	`type` TEXT NOT NULL,
	`status` INT NOT NULL,
	`lab` BIGINT NOT NULL,
	`voter` BIGINT NOT NULL,
	`THC` FLOAT NULL DEFAULT NULL,
	`CBD` FLOAT NULL DEFAULT NULL,
	`CBN` FLOAT NULL DEFAULT NULL,
	`CBG` FLOAT NULL DEFAULT NULL,
	`CBC` FLOAT NULL DEFAULT NULL,
	`Limonene` FLOAT NULL DEFAULT NULL,
	`Myrcene` FLOAT NULL DEFAULT NULL,
	`Pinene` FLOAT NULL DEFAULT NULL,
	`Linalool` FLOAT NULL DEFAULT NULL,
	`BCaryophyllene` FLOAT NULL DEFAULT NULL,
	`Nerolidol` FLOAT NULL DEFAULT NULL,
	`Phytol` FLOAT NULL DEFAULT NULL,
	`Cineol` FLOAT NULL DEFAULT NULL,
	`Humulene` FLOAT NULL DEFAULT NULL,
	`Borneol` FLOAT NULL DEFAULT NULL,
	`Terpinolene` FLOAT NULL DEFAULT NULL
	) ENGINE = MYISAM ;";
	thc_createTable($table7, $sql7);

	$table8 = $wpdb->prefix.'thcv_ratings';
	$sql8 = "SELECT `post`, `type`, IF(`type` = 'potency', `type`, `key`) AS `key`, AVG(`val`) AS `val`
	FROM `".$table1."`
	GROUP BY `post`, `type`, `key`";
	thc_createView($table8, $sql8);

	$table9 = $wpdb->prefix.'thcv_tests';
	$sql9 = "SELECT a . *, b.`post_title` AS `pTitle`, b.`post_type` AS `pType`, c.`ID` AS `dID`, c.`post_title` AS `dTitle`, d.`post_title` AS `sTitle`, e.`post_title` AS `lTitle`, e.`post_author` AS `lAuthor`, f.`ratio`, f.`height`, f.`stock`, f.`structure`, f.`odor`, f.`potency`, f.`distance`, f.`yields`, f.`tomold`, f.`topest`, f.`flav`, f.`medi`
	FROM `".$table7."` AS a
	LEFT JOIN `".$wppt."` AS b ON (a.`pID` = b.`ID`)
	LEFT JOIN `".$wppt."` AS c ON (b.`post_parent` = c.`ID`)
	LEFT JOIN `".$wppt."` AS d ON (a.`strain` = d.`ID`)
	LEFT JOIN `".$wppt."` AS e ON (a.`lab` = e.`ID`)
	LEFT JOIN `".$table6."` AS f ON (a.`phenID` = f.`ID`)";
	thc_createView($table9, $sql9);

	$table10 = $wpdb->prefix.'thcv_compact';
	$sql10 = "SELECT a.`pID`, a.`pType`, a.`strain`, a.`dispensary`, a.`price`, b.`meta_value` AS `pCat`, c.`dispType`, c.`featured`, c.`latitude` AS `lat`, c.`longitude` AS `lon`, d.`val` AS `potencyV`, f.`key` AS `mediK`, f.`val` AS `mediV`, g.`key` AS `flavK`, g.`val` AS `flavV`, e.`ratio`, e.`height`, e.`stock`, e.`structure`, e.`odor`, e.`potency`, e.`distance`, e.`yields`, e.`tomold`, e.`topest`, e.`flav`, e.`medi` 
	FROM `".$table4."` AS a
	LEFT JOIN `".$wppmt."` AS b ON (a.`pID` = b.`post_id` AND a.`pType` = 'listing' AND b.`meta_key` = 'type')
	LEFT JOIN `".$table5."` AS c ON (a.`dispensary` = c.`pID`)
	LEFT JOIN `".$table8."` AS d ON (a.`strain` = d.`post` AND d.`type` = 'potency')
	LEFT JOIN `".$table6."` AS e ON (a.`strain` = e.`strain`)
	LEFT JOIN `".$table8."` AS f ON (a.`pID` = f.`post` AND f.`type` = '_medi-')
	LEFT JOIN `".$table8."` AS g ON (a.`pID` = g.`post` AND g.`type` = '_flav-')";
	thc_createView($table10, $sql10);

	$ap = array('height'=>'Height', 'stock'=>'Stock', 'structure'=>'General Structure', 'odor'=>'Odor', 'potency'=>'Potency', 'distance'=>'Nodal Distance', 'yields'=>'Yields in Grams Per Sq Meter', 'tomold'=>'Resistance to Mold', 'topest'=>'Resistance to Pest', 'flav'=>'Flavours', 'medi'=>'Medicinal Value');
	update_option('all_phentypes', $ap);

	$af = array('Mimosa', 'Lilac', 'Orange', 'Cherry', 'Apricot', 'Violet', 'Rose', 'Jasmine', 'Blueberry', 'Raspberry', 'Strawberry', 'Pineapple', 'Mango', 'Mellon', 'Guava', 'Pear', 'Apple', 'Peach', 'Plum', 'Cedar', 'Black pepper', 'Green pepper', 'Rosemary', 'Sage', 'Basil', 'Thyme', 'Dill', 'Cinnamon', 'Saffron', 'Cloves', 'Parsley', 'Ginger', 'Mint', 'Jalapeno', 'Tabasco', 'Pink salt', 'Sea salt ammonia', 'Metallic', 'Vinegar', 'Coffee', 'Cocoa', 'Tea', 'Tabasco', 'Onion', 'Chives', 'Peas', 'Cucumber', 'Chestnut', 'Peanut', 'Sesame', 'Pistachio', 'Almond', 'Walnut', 'Macademian', 'Butter', 'Sour cream', 'whipped cream', 'Cream cheese', 'Blue cheese', 'Sour milk', 'Pink grapefruit', 'Blood orange', 'Sweet orange', 'Mandarin', 'Grapefruit', 'Bread fruit', 'Lime', 'Sweet lemon', 'Lemon grass');
	update_option('all_flavours', $af);

	$am = array('Nausea and Vomiting', 'Stress', 'Loss of Appetite', 'Muscle Tension and Spasm', 'Pain', 'Insomnia');
	update_option('all_medicals', $am);
	
	update_option('thc_posts_count', array('home'=>6, 'all'=>18));
	
	if(!$thcp){thc_addPages();}

}
function thc_addPages() {
	global $thcp;
	$my_posta = array('post_title' => __('Login', 'thcPlugin'), 'post_content' => '[THCLOGIN]', 'post_type' => 'page', 'post_status' => 'publish',
		'comment_status' => 'closed', 'post_author' => 1,);
	$my_postb = array('post_title' => __('Sign Up', 'thcPlugin'), 'post_content' => '[THCREGISTER]', 'post_type' => 'page', 'post_status' => 'publish',
		'comment_status' => 'closed', 'post_author' => 1,);
	$my_postc = array('post_title' => __('Profile', 'thcPlugin'), 'post_content' => '[THCPROFILE]', 'post_type' => 'page', 'post_status' => 'publish',
		'comment_status' => 'closed', 'post_author' => 1,);
	$pid = wp_insert_post($my_posta, false); if ($pid > 0) {$thcp['login'] = $pid;}
	$pid = wp_insert_post($my_postb, false); if ($pid > 0) {$thcp['signup'] = $pid;}
	$pid = wp_insert_post($my_postc, false); if ($pid > 0) {$thcp['profile'] = $pid;}
	update_option('thc_pages', $thcp);
}
register_deactivation_hook( __FILE__, 'thcp_deactivation' );
function thcp_deactivation() {
	remove_related_roles();
}

require_once(THCP_DIR."functions.php");
require_once(THCP_DIR."queries.php");

require_once(THCP_DIR."includes/strain.php");
require_once(THCP_DIR."includes/dispensary.php");
require_once(THCP_DIR."includes/listing.php");
require_once(THCP_DIR."includes/lab.php");
require_once(THCP_DIR."includes/journal.php");
require_once(THCP_DIR."includes/seed.php");
require_once(THCP_DIR."includes/clinic.php");
//require_once(THCP_DIR."includes/sets.php");

require_once(THCP_DIR."includes/backend.php");
require_once(THCP_DIR."admin.php");

add_action( 'admin_enqueue_scripts', 'thcp_admin_enq' );
function thcp_admin_enq($hook){
	//wp_enqueue_style( 'thcp_admin_css1', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css' );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_media();
	wp_enqueue_script( 'thcp_admin_script2', THCP_URL . '/assets/chosen/chosen.jquery.min.js' );
	wp_enqueue_style( 'thcp_admin_css2',  THCP_URL . '/assets/chosen/chosen.min.css' );
	//wp_enqueue_script('jquery-ui-core');
	//wp_enqueue_script('jquery-ui-widget'); 
	//wp_enqueue_script('jquery-ui-position');
	//wp_enqueue_script( 'jquery-ui-autocomplete' );
	//wp_enqueue_script( 'thcp_admin_script1', '//code.jquery.com/jquery-1.10.2.js' );
	//wp_enqueue_script( 'thcp_admin_script2', '//code.jquery.com/ui/1.11.4/jquery-ui.js' );
	//wp_enqueue_style( 'thcp_admin_css', '/resources/demos/style.css' );
	wp_enqueue_script( 'thcp_admin_script', THCP_URL . '/assets/admin-script.js' );
	wp_enqueue_style( 'thcp_admin_css', THCP_URL . '/assets/admin-style.css' );
}
add_action('wp_print_scripts', 'thc_print_scripts');
add_action('admin_print_scripts', 'thc_print_scripts');
function thc_print_scripts(){
	echo '<script> var thcajax = "'.site_url('/wp-admin/admin-ajax.php').'";</script>';
}
add_action( 'wp_enqueue_scripts', 'thcp_wp_enq' );
function thcp_wp_enq($hook){
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'thcp_admin_script2', THCP_URL . '/assets/chosen/chosen.jquery.min.js' );
	wp_enqueue_style( 'thcp_admin_css2',  THCP_URL . '/assets/chosen/chosen.min.css' );
}
?>
