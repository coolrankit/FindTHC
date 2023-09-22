<?php
// Get SQL for Distance
function thc_dist_sql($lat1=0, $lon1=0, $lat2=0, $lon2=0){
	$r = "(DEGREES(ACOS(SIN(RADIANS($lat1)) * SIN(RADIANS($lat2)) +  COS(RADIANS($lat1)) * COS(RADIANS($lat2)) * COS(RADIANS($lon1 - $lon2)))) * 60 * 1.1515 * 1.609344)";
	return $r;
}

// Main Query Modifying Action
add_action( 'pre_get_posts', 'thc_edit_post_query' );
function thc_edit_post_query( $query ) {
	if (is_home() && $query->is_main_query() ) {
		$query->set( 'post_type', 'strain' );
		$query->set( 'post_status', 'publish' );
		//echo $query->get( 'paged' );
		//add_filter('posts_clauses', 'edit_thc_mainq', 20, 1);		
		//if($_GET['view']=='rs'){add_filter('posts_clauses', 'edit_thc_main_1', 20, 1);}
		//else {add_filter('posts_clauses', 'edit_thc_mainq', 20, 1);}
		//echo 6;
	}
	$ex = $query->get('extra');
	if($ex=='thc-local'){add_filter('posts_clauses', 'edit_thc_locals', 20, 2);}
	if($ex=='thc-local-tested'){add_filter('posts_clauses', 'edit_thc_locals', 20, 2);}
	if($ex=='thc-local-seeds'){add_filter('posts_clauses', 'edit_thc_locals', 20, 2);}
	if($ex=='map-strain'){add_filter('posts_clauses', 'edit_map_strains', 20, 2);}
	if($ex=='map-home'){add_filter('posts_clauses', 'edit_map_home', 20, 1);}
	if($ex=='trial'){add_filter('posts_clauses', 'edit_post_trial', 20, 1);}
	
	if($ex=='main-0'){add_filter('posts_clauses', 'edit_thc_main_0', 20, 1);}
	if($ex=='main-1'){add_filter('posts_clauses', 'edit_thc_main_1', 20, 1);}
	if($ex=='main-2'){add_filter('posts_clauses', 'edit_thc_main_2', 20, 1);}
	if($ex=='main-3'){add_filter('posts_clauses', 'edit_thc_main_3', 20, 1);}
	if($ex=='main-4'){add_filter('posts_clauses', 'edit_thc_main_4', 20, 1);}
	if($ex=='main-5'){add_filter('posts_clauses', 'edit_thc_main_5', 20, 1);}
	if($ex=='main-6'){add_filter('posts_clauses', 'edit_thc_main_6', 20, 1);}
}
// Get Local Dispensaries
function thc_local_dispensaries($p, $t=false, $c=false, $i=-1){
	if($c){$ex = 'thc-local-seeds';}
	elseif(!$t){$ex = 'thc-local';}
	else{$ex = 'thc-local-tested';}
	$args = array('post_type'=>'listing', 'posts_per_page'=>$i, 'suppress_filters' => false, 'extra'=>$ex, 'extra2'=>$p->ID);
	return get_posts($args);
}

// Get map markers on strain or home page
function get_map_markers($t='', $id=0){
	$marg = $mka = array();
	if($t=="strain"){$ex = 'map-strain';}
	elseif($t=="home"){$ex = 'map-home';}
	$args = array('post_type'=>'dispensary', 'posts_per_page'=>20, 'suppress_filters' => false, 'extra'=>$ex, 'extra2'=>$id);
	return get_posts($args);
}

// Getting Listings of Local Dispensaries
function edit_thc_locals($data, $d2){
	global $wpdb; $pt = $wpdb->prefix.'posts'; $ct = $wpdb->prefix.'thcv_compact'; $ct2 = $wpdb->prefix.'thct_tests';
	
	$extra = $d2->query[extra]; $strain = $d2->query[extra2];
	$uid = get_current_user_id();
	global $gloc; $lat = $gloc["lat"]; $lon = $gloc["lon"];
	
	$data['join'] = " INNER JOIN $ct AS thc ON ($pt.ID = thc.pID AND thc.strain = '$strain')";

	//if($strain){$data['where'] .= " AND thc.strain = '$strain'";}
	if($extra=='thc-local-seeds'){$data['where'] .= " AND (thc.pCat = 'clone' OR thc.pCat = 'seed')";}
	if($extra=='thc-local'){$data['where'] .= " AND (thc.pCat != 'clone' AND thc.pCat != 'seed')";}
	if($extra=='thc-local-tested'){
		$data['where'] .= " AND (thc.pCat != 'clone' AND thc.pCat != 'seed')";
		$data['join'] .= " INNER JOIN $ct2 AS thc2 ON ($pt.ID = thc2.pID AND thc2.status = 1)";
	}

	$data['groupby'] = "$pt.ID";
	$data['orderby'] = thc_dist_sql($lat, $lon, 'thc.lat', 'thc.lon'). " ASC";
	//$data['orderby'] = "(".thc_dist_sql($lat, $lon, 'thc.lat', 'thc.lon')." - mediVA) ASC";

	remove_filter('posts_clauses', 'edit_thc_locals', 20);
	return $data;
}

// Getting Strains on main page
function edit_thc_mainq($data){
	global $wpdb; $pt = $wpdb->prefix.'posts'; $ct = $wpdb->prefix.'thcv_ratings';

	$uid = get_current_user_id();
	$ma = get_the_author_meta( '_all_medicinals', $uid );
	
	if($ma && !empty($ma)){
		$data['join'] = " INNER JOIN $ct AS thc ON ($pt.ID = thc.post AND thc.type = '_medi-')";
		$data['where'] .= " AND (thc.key = '".implode("' OR thc.key = '", $ma)."')";
		$data['fields'] .= ", AVG(thc.val) AS mediVA";
		$data['groupby'] = "$pt.ID";
		$data['orderby'] = "mediVA DESC";
	}

	remove_filter('posts_clauses', 'edit_thc_mainq', 20);
	return $data;
}

// Getting map markers on strain page
function edit_map_strains($data, $d2){
	global $wpdb; $pt = $wpdb->prefix.'posts'; $pmt = $wpdb->prefix.'postmeta'; $ct = $wpdb->prefix.'thcv_compact';
	
	$extra = $d2->query[extra]; $strain = $d2->query[extra2];
	$uid = get_current_user_id();
	global $gloc; $lat = $gloc["lat"]; $lon = $gloc["lon"];
	
	$data['join'] = " INNER JOIN $ct AS thc ON ($pt.ID = thc.dispensary)";
	$data['join'] .= " INNER JOIN $pmt AS thc2 ON ($pt.ID = thc2.post_id AND thc2.meta_key = 'address')";

	if($strain){$data['where'] .= " AND thc.strain = '$strain'";}

	$data['fields'] .= ", thc.lat, thc.lon, thc.dispType, thc2.meta_value AS address";
	$data['groupby'] = "$pt.ID";
	$data['orderby'] = thc_dist_sql($lat, $lon, 'thc.lat', 'thc.lon'). " ASC";
	//$data['orderby'] = "(".thc_dist_sql($lat, $lon, 'thc.lat', 'thc.lon')." - mediVA) ASC";

	remove_filter('posts_clauses', 'edit_map_strains', 20);
	return $data;
}

// Getting map markers on home page
function edit_map_home($data){
	global $wpdb; $pt = $wpdb->prefix.'posts'; $pmt = $wpdb->prefix.'postmeta'; $ct = $wpdb->prefix.'thcv_compact';
	
	$uid = get_current_user_id();
	$ma = get_the_author_meta( '_all_medicinals', $uid );
	global $gloc; $lat = $gloc["lat"]; $lon = $gloc["lon"];
	
	$data['join'] = " INNER JOIN $ct AS thc ON ($pt.ID = thc.dispensary)";
	$data['join'] .= " INNER JOIN $pmt AS thc2 ON ($pt.ID = thc2.post_id AND thc2.meta_key = 'address')";

	if($strain){$data['where'] .= " AND thc.strain = '$strain'";}
	if($ma && !empty($ma) && is_array($ma)){ $data['where'] .= " AND (thc.mediK = '".implode("' OR thc.mediK = '", $ma)."')"; }
	
	$data['fields'] .= ", thc.lat, thc.lon, thc.dispType, thc2.meta_value AS address";
	$data['groupby'] = "$pt.ID";
	$data['orderby'] = thc_dist_sql($lat, $lon, 'thc.lat', 'thc.lon'). " ASC";
	//$data['orderby'] = "(".thc_dist_sql($lat, $lon, 'thc.lat', 'thc.lon')." - mediVA) ASC";

	remove_filter('posts_clauses', 'edit_map_home', 20);
	return $data;
}

// NEW QUERIES
function thc_get_query($of){
	global $paged; global $thco;
	$view = $_GET['view'];
	$arg = array();
	switch($of){
	case "main-0":
	$arg = array(
		'post_type'=>'strain',
		'post_status'=>'publish',
		//'posts_per_page'=>6,
		'paged' => $paged,
		'extra'=>'main-0'
	);
	break;
	case "main-1":
	$arg = array(
		'post_type'=>'listing',
		'post_status'=>'publish',
		//'posts_per_page'=>6,
		'paged' => $paged,
		'extra'=>'main-1'
	);
	//if($view){unset($arg['posts_per_page']);}
	break;
	case "main-2":
	$arg = array(
		'post_type'=>'dispensary',
		'post_status'=>'publish',
		//'posts_per_page'=>6,
		'paged' => $paged,
		'extra'=>'main-2'
	);
	//if($view){unset($arg['posts_per_page']);}
	break;
	case "main-3":
	$arg = array(
		'post_type'=>'dispensary',
		'post_status'=>'publish',
		//'posts_per_page'=>6,
		'extra'=>'main-3',
		'paged' => $paged,
	);
	//if($view){unset($arg['posts_per_page']);}
	break;
	}
	if($view){$arg['posts_per_page'] = $thco[all];} else{$arg['posts_per_page'] = $thco[home];}
	return new WP_Query($arg);
}
function edit_thc_main_0($data){
	global $wpdb; $pt = $wpdb->prefix.'posts'; $wppmt = $wpdb->prefix.'postmeta'; $table8 = $wpdb->prefix.'thcv_ratings'; $table6 = $wpdb->prefix.'thct_phenotypes'; 

	$type = $_GET['type'];
	$price = $_GET['price'];
	$medical = $_GET['medical'];
	$rating = $_GET['rating'];
	$pheno = $_GET['pheno'];
	$phenv = $_GET['phenv'];
	//$ma1 = get_option('all_medicals');
	$uid = get_current_user_id();
	$ma2 = get_the_author_meta( '_all_medicinals', $uid );
	global $gloc; $lat = $gloc["lat"]; $lon = $gloc["lon"];
	
	if($medical == 1){$ma = $ma2;}
	//elseif((!$medical || $medical == '0') && $uid){$ma = $ma2;}
	elseif(!$medical || $medical == '0'){$ma = false;}
	elseif($medical) {$ma = array($medical);}
	$phenv = str_replace(array('\"',"\'"), array('"',"'"), $phenv);
	
	$data['join'] = " LEFT JOIN $wppmt AS b ON ($pt.ID = b.post_id AND b.meta_key = 'type')";
	$data['join'] .= " LEFT JOIN $table8 AS d ON ($pt.ID = d.post AND d.type = 'potency')";
	$data['join'] .= " LEFT JOIN $table6 AS e ON ($pt.ID = e.strain)";
	$data['join'] .= " LEFT JOIN $table8 AS f ON ($pt.ID = f.post AND f.type = '_medi-')";
	$data['join'] .= " LEFT JOIN $table8 AS g ON ($pt.ID = g.post AND g.type = '_flav-')";

	//$data['where'] .= " AND thc.featured = 'yes'";
	if($type && $type !=''){$data['where'] .= " AND b.meta_value = '$type'";}
	//if($price && $price > 0){$data['where'] .= " AND thc.price <= $price";}
	if($ma && !empty($ma)){ $data['where'] .= " AND (f.key = '".implode("' OR f.key = '", $ma)."')"; }
	if($rating){$data['where'] .= " AND (f.val $rating";}
	if($rating == ">= 0"){$data['where'] .= " OR f.`val` IS NULL";}
	if($rating){$data['where'] .= ")";}
	if($pheno && $phenv){$data['where'] .= " AND e.$pheno = '$phenv'";}

	$data['fields'] .= ", AVG(f.`val`) AS mediVA";
	$data['groupby'] = "$pt.ID";
	$data['orderby'] = "mediVA DESC";
	//$data['orderby'] = thc_dist_sql($lat, $lon, 'thc.lat', 'thc.lon'). " ASC";
	//$data['orderby'] = "(".thc_dist_sql($lat, $lon, 'thc.lat', 'thc.lon')." - mediVA) ASC";
	remove_filter('posts_clauses', 'edit_thc_main_0', 20);

	return $data;
}
function edit_thc_main_1($data){
	global $wpdb; $pt = $wpdb->prefix.'posts'; $ct = $wpdb->prefix.'thcv_compact';

	$type = $_GET['type'];
	$price = $_GET['price'];
	$medical = $_GET['medical'];
	$rating = $_GET['rating'];
	$pheno = $_GET['pheno'];
	$phenv = $_GET['phenv'];
	$ma1 = get_option('all_medicals');
	$uid = get_current_user_id();
	$ma2 = get_the_author_meta( '_all_medicinals', $uid );
	global $gloc; $lat = $gloc["lat"]; $lon = $gloc["lon"];
	
	if($medical == 1){$ma = $ma2;}
	//elseif((!$medical || $medical == '0') && $uid){$ma = $ma2;}
	elseif(!$medical || $medical == '0'){$ma = $ma1;}
	elseif($medical) {$ma = array($medical);}
	$phenv = str_replace(array('\"',"\'"), array('"',"'"), $phenv);
	
	$data['join'] = " INNER JOIN $ct AS thc ON ($pt.ID = thc.pID)";

	//$data['where'] .= " AND thc.featured = 'yes'";
	if($type && $type !=''){$data['where'] .= " AND thc.pCat = '$type'";}
	if($price && $price > 0){$data['where'] .= " AND thc.price <= $price";}
	if($ma && !empty($ma)){ $data['where'] .= " AND (thc.mediK = '".implode("' OR thc.mediK = '", $ma)."')"; }
	if($rating){$data['where'] .= " AND (thc.mediV $rating";}
	if($rating == ">= 0"){$data['where'] .= " OR thc.mediV IS NULL";}
	if($rating){$data['where'] .= ")";}
	if($pheno && $phenv){$data['where'] .= " AND thc.$pheno = '$phenv'";}

	$data['fields'] .= ", AVG(thc.mediV) AS mediVA";
	$data['groupby'] = "$pt.ID";
	$data['orderby'] = "mediVA DESC";
	//$data['orderby'] = thc_dist_sql($lat, $lon, 'thc.lat', 'thc.lon'). " ASC";
	//$data['orderby'] = "(".thc_dist_sql($lat, $lon, 'thc.lat', 'thc.lon')." - mediVA) ASC";

	remove_filter('posts_clauses', 'edit_thc_main_1', 20);

	return $data;
}
function edit_thc_main_2($data){
	global $wpdb; $pt = $wpdb->prefix.'posts'; $ct = $wpdb->prefix.'thcv_compact';

	$type = $_GET['type'];
	$price = $_GET['price'];
	$medical = $_GET['medical'];
	$rating = $_GET['rating'];
	$pheno = $_GET['pheno'];
	$phenv = $_GET['phenv'];
	$ma1 = get_option('all_medicals');
	$uid = get_current_user_id();
	$ma2 = get_the_author_meta( '_all_medicinals', $uid );
	global $gloc; $lat = $gloc["lat"]; $lon = $gloc["lon"];
	
	if($medical == 1){$ma = $ma2;}
	//elseif((!$medical || $medical == '0') && $uid){$ma = $ma2;}
	elseif(!$medical || $medical == '0'){$ma = $ma1;}
	elseif($medical) {$ma = array($medical);}
	$phenv = str_replace(array('\"',"\'"), array('"',"'"), $phenv);
	
	$data['join'] = " INNER JOIN $ct AS thc ON ($pt.ID = thc.dispensary)";

	//$data['where'] .= " AND thc.featured = 'yes'";
	if($type && $type !=''){$data['where'] .= " AND thc.pCat = '$type'";}
	if($price && $price > 0){$data['where'] .= " AND thc.price <= $price";}
	if($ma && !empty($ma)){ $data['where'] .= " AND (thc.mediK = '".implode("' OR thc.mediK = '", $ma)."')"; }
	if($rating){$data['where'] .= " AND (thc.mediV $rating";}
	if($rating == ">= 0"){$data['where'] .= " OR thc.mediV IS NULL";}
	if($rating){$data['where'] .= ")";}
	if($pheno && $phenv){$data['where'] .= " AND thc.$pheno = '$phenv'";}

	$data['fields'] .= ", AVG(thc.mediV) AS mediVA";
	$data['groupby'] = "$pt.ID";
	$data['orderby'] = "mediVA DESC";
	//$data['orderby'] = thc_dist_sql($lat, $lon, 'thc.lat', 'thc.lon'). " ASC";
	//$data['orderby'] = "(".thc_dist_sql($lat, $lon, 'thc.lat', 'thc.lon')." - mediVA) ASC";

	remove_filter('posts_clauses', 'edit_thc_main_2', 20);
	return $data;
}
function edit_thc_main_3($data){
	global $wpdb; $pt = $wpdb->prefix.'posts'; $ct = $wpdb->prefix.'thcv_compact';

	$type = $_GET['type'];
	$price = $_GET['price'];
	$medical = $_GET['medical'];
	$rating = $_GET['rating'];
	$pheno = $_GET['pheno'];
	$phenv = $_GET['phenv'];
	$ma1 = get_option('all_medicals');
	$uid = get_current_user_id();
	$ma2 = get_the_author_meta( '_all_medicinals', $uid );
	global $gloc; $lat = $gloc["lat"]; $lon = $gloc["lon"];
	
	if($medical == 1){$ma = $ma2;}
	//elseif((!$medical || $medical == '0') && $uid){$ma = $ma2;}
	elseif(!$medical || $medical == '0'){$ma = $ma1;}
	elseif($medical) {$ma = array($medical);}
	$phenv = str_replace(array('\"',"\'"), array('"',"'"), $phenv);
	
	$data['join'] = " INNER JOIN $ct AS thc ON ($pt.ID = thc.dispensary)";

	$data['where'] .= " AND thc.featured = 'yes'";
	if($type && $type !=''){$data['where'] .= " AND thc.pCat = '$type'";}
	if($price && $price > 0){$data['where'] .= " AND thc.price <= $price";}
	if($ma && !empty($ma)){ $data['where'] .= " AND (thc.mediK = '".implode("' OR thc.mediK = '", $ma)."')"; }
	if($rating){$data['where'] .= " AND (thc.mediV $rating";}
	if($rating == ">= 0"){$data['where'] .= " OR thc.mediV IS NULL";}
	if($rating){$data['where'] .= ")";}
	if($pheno && $phenv){$data['where'] .= " AND thc.$pheno = '$phenv'";}

	$data['fields'] .= ", AVG(thc.mediV) AS mediVA";
	$data['groupby'] = "$pt.ID";
	$data['orderby'] = "mediVA DESC";
	//$data['orderby'] = thc_dist_sql($lat, $lon, 'thc.lat', 'thc.lon'). " ASC";
	//$data['orderby'] = "(".thc_dist_sql($lat, $lon, 'thc.lat', 'thc.lon')." - mediVA) ASC";

	remove_filter('posts_clauses', 'edit_thc_main_3', 20);
	return $data;
}
function edit_thc_main_4($data){
	global $wpdb; $pt = $wpdb->prefix.'posts'; $ct = $wpdb->prefix.'thcv_compact';

	$type = $_GET['type'];
	$price = $_GET['price'];
	$medical = $_GET['medical'];
	$rating = $_GET['rating'];
	$pheno = $_GET['pheno'];
	$phenv = $_GET['phenv'];
	$ma1 = get_option('all_medicals');
	$uid = get_current_user_id();
	$ma2 = get_the_author_meta( '_all_medicinals', $uid );
	global $gloc; $lat = $gloc["lat"]; $lon = $gloc["lon"];
	
	if($medical == 1){$ma = $ma2;}
	//elseif((!$medical || $medical == '0') && $uid){$ma = $ma2;}
	elseif(!$medical || $medical == '0'){$ma = $ma1;}
	elseif($medical) {$ma = array($medical);}
	$phenv = str_replace(array('\"',"\'"), array('"',"'"), $phenv);
	
	$data['join'] = " INNER JOIN $ct AS thc ON ($pt.ID = thc.pID)";

	//$data['where'] .= " AND thc.featured = 'yes'";
	if($type && $type !=''){$data['where'] .= " AND thc.pCat = '$type'";}
	if($price && $price > 0){$data['where'] .= " AND thc.price <= $price";}
	if($ma && !empty($ma)){ $data['where'] .= " AND (thc.mediK = '".implode("' OR thc.mediK = '", $ma)."')"; }
	if($rating){$data['where'] .= " AND (thc.mediV $rating";}
	if($rating == ">= 0"){$data['where'] .= " OR thc.mediV IS NULL";}
	if($rating){$data['where'] .= ")";}
	//if($pheno && $phenv){$data['where'] .= " AND thc.$pheno = '$phenv'";}

	$data['fields'] .= ", AVG(thc.mediV) AS mediVA, thc.price";
	$data['groupby'] = "$pt.ID";
	$data['orderby'] = "mediVA DESC";
	//$data['orderby'] = thc_dist_sql($lat, $lon, 'thc.lat', 'thc.lon'). " ASC";
	//$data['orderby'] = "(".thc_dist_sql($lat, $lon, 'thc.lat', 'thc.lon')." - mediVA) ASC";

	remove_filter('posts_clauses', 'edit_thc_main_4', 20);
	return $data;
}
function edit_thc_main_5($data){
	global $wpdb; $pt = $wpdb->prefix.'posts'; $ct = $wpdb->prefix.'thcv_compact';

	$type = $_GET['type'];
	$price = $_GET['price'];
	//$medical = $_GET['medical'];
	$rating = $_GET['rating'];
	$pheno = $_GET['pheno'];
	$phenv = $_GET['phenv'];
	$ma1 = get_option('all_medicals');
	$uid = get_current_user_id();
	$ma2 = get_the_author_meta( '_all_medicinals', $uid );
	global $gloc; $lat = $gloc["lat"]; $lon = $gloc["lon"];
	
	if($medical == 1){$ma = $ma2;}
	//elseif((!$medical || $medical == '0') && $uid){$ma = $ma2;}
	elseif(!$medical || $medical == '0'){$ma = $ma1;}
	elseif($medical) {$ma = array($medical);}
	$phenv = str_replace(array('\"',"\'"), array('"',"'"), $phenv);
	
	$data['join'] = " INNER JOIN $ct AS thc ON ($pt.ID = thc.pID)";

	//$data['where'] .= " AND thc.featured = 'yes'";
	//if($type && $type !=''){$data['where'] .= " AND thc.pCat = '$type'";}
	//if($price && $price > 0){$data['where'] .= " AND thc.price <= $price";}
	if($ma && !empty($ma)){ $data['where'] .= " AND (thc.mediK = '".implode("' OR thc.mediK = '", $ma)."')"; }
	//if($rating){$data['where'] .= " AND thc.mediV $rating";}
	//if($pheno && $phenv){$data['where'] .= " AND thc.$pheno = '$phenv'";}

	$data['fields'] .= "AVG(thc.mediV) AS mediVA";
	$data['groupby'] = "$pt.post_parent";
	$data['orderby'] = "mediVA DESC";
	//$data['orderby'] = thc_dist_sql($lat, $lon, 'thc.lat', 'thc.lon'). " ASC";
	//$data['orderby'] = "(".thc_dist_sql($lat, $lon, 'thc.lat', 'thc.lon')." - mediVA) ASC";

	remove_filter('posts_clauses', 'edit_thc_main_5', 20);
	return $data;
}
function edit_thc_main_6($data){
	global $wpdb; $pt = $wpdb->prefix.'posts'; $ct = $wpdb->prefix.'thcv_compact';

	$type = $_GET['type'];
	$price = $_GET['price'];
	//$medical = $_GET['medical'];
	$rating = $_GET['rating'];
	$pheno = $_GET['pheno'];
	$phenv = $_GET['phenv'];
	$ma1 = get_option('all_medicals');
	$uid = get_current_user_id();
	$ma2 = get_the_author_meta( '_all_medicinals', $uid );
	global $gloc; $lat = $gloc["lat"]; $lon = $gloc["lon"];
	
	if($medical == 1){$ma = $ma2;}
	//elseif((!$medical || $medical == '0') && $uid){$ma = $ma2;}
	elseif(!$medical || $medical == '0'){$ma = $ma1;}
	elseif($medical) {$ma = array($medical);}
	$phenv = str_replace(array('\"',"\'"), array('"',"'"), $phenv);
	
	$data['join'] = " INNER JOIN $ct AS thc ON ($pt.ID = thc.pID)";

	//$data['where'] .= " AND thc.featured = 'yes'";
	//if($type && $type !=''){$data['where'] .= " AND thc.pCat = '$type'";}
	//if($price && $price > 0){$data['where'] .= " AND thc.price <= $price";}
	if($ma && !empty($ma)){ $data['where'] .= " AND (thc.mediK = '".implode("' OR thc.mediK = '", $ma)."')"; }
	//if($rating){$data['where'] .= " AND thc.mediV $rating";}
	//if($pheno && $phenv){$data['where'] .= " AND thc.$pheno = '$phenv'";}

	$data['fields'] .= ", AVG(thc.mediV) AS mediVA";
	$data['groupby'] = "$pt.ID";
	$data['orderby'] = "mediVA DESC";
	//$data['orderby'] = thc_dist_sql($lat, $lon, 'thc.lat', 'thc.lon'). " ASC";
	//$data['orderby'] = "(".thc_dist_sql($lat, $lon, 'thc.lat', 'thc.lon')." - mediVA) ASC";

	remove_filter('posts_clauses', 'edit_thc_main_6', 20);
	return $data;
}
?>