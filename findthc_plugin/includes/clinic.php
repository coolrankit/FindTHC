<?php
add_action('init', 'register_clinic_type');
function register_clinic_type() {
	$labels = array(
		'name'               => _x( 'Doctor\'s Clinics', 'post type general name', 'thcPlugin' ),
		'singular_name'      => _x( 'Doctor\'s Clinic', 'post type singular name', 'thcPlugin' ),
		'menu_name'          => _x( 'Doctor\'s Clinics', 'admin menu', 'thcPlugin' ),
		'name_admin_bar'     => _x( 'Doctor\'s Clinic', 'add new on admin bar', 'thcPlugin' ),
		'add_new'            => _x( 'Add New', 'clinic', 'thcPlugin' ),
		'add_new_item'       => __( 'Add New Clinic', 'thcPlugin' ),
		'new_item'           => __( 'New Doctor\'s Clinic', 'thcPlugin' ),
		'edit_item'          => __( 'Edit Doctor\'s Clinic', 'thcPlugin' ),
		'view_item'          => __( 'View Doctor\'s Clinic', 'thcPlugin' ),
		'all_items'          => __( 'All Doctor\'s Clinics', 'thcPlugin' ),
		'search_items'       => __( 'Search Doctor\'s Clinics', 'thcPlugin' ),
		'parent_item_colon'  => __( 'Parent Doctor\'s Clinics:', 'thcPlugin' ),
		'not_found'          => __( 'No doctor\'s clinics found.', 'thcPlugin' ),
		'not_found_in_trash' => __( 'No doctor\'s clinics found in Trash.', 'thcPlugin' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'thcPlugin' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'clinic' ),
		'capability_type'    => 'clinic',
		/*'capabilities'       => array(
    							'create_posts' => true, // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
								),*/
		'map_meta_cap'       => true,
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'thumbnail')
	);

	register_post_type( 'clinic', $args );
}
add_action( 'add_meta_boxes_clinic', 'clinic_meta_boxes' );
function clinic_meta_boxes(){
	add_meta_box('clinic-data-box', 'Clinic\'s Data', 'clinic_data_box_html', 'clinic', $context='advanced', $priority='default', $callback_args=null);
	add_meta_box('clinic-schedule-box', 'Hours Opened', 'clinic_schedulebox_html', 'clinic', $context='advanced', $priority='default', $callback_args=null);
}
function clinic_data_box_html($post){
	global $wpdb;
	if($post && $post->ID){$ddata = get_all_data($post->ID);}
	if(current_user_can('administrator')){
		$blog_id = get_current_blog_id();
		$roles = array('administrator', 'doctor');
		$meta_query = array('key' => $wpdb->get_blog_prefix($blog_id) . 'capabilities', 'value' => '"(' . implode('|', array_map('preg_quote', $roles)) . ')"', 'compare' => 'REGEXP');
		$user_query = new WP_User_Query(array('meta_query' => array($meta_query)));
		$users = $user_query->get_results();
		$uc = get_userdata(get_current_user_id());
		echo '<p><label class="th-label">Owner</label>: <select class="combo" name="post_author_override">';
		if($users){foreach($users as $u){echo '<option value="'.$u->ID.'" '.selected($u->ID, $post->post_author, false).'>'.$u->display_name.'</option>';}}
		echo '</select></p>';
	}
	echo '<p><label class="th-label">Phone</label>: <input type="text" name="ddata[phone]" value="'.$ddata[phone].'"></p>';
	echo '<p><label class="th-label">Email</label>: <input type="text" name="ddata[email]" value="'.$ddata[email].'"></p>';
	echo '<p><label class="th-label">Address</label>: <input type="text" name="ddata[address]" id="autocomplete" value="'.$ddata[address].'" placeholder="Type and select one"></p>';
	echo '<p><label class="th-label">Latitude</label>: <input type="text" name="ddata[latitude]" id="latitude" value="'.$ddata[latitude].'">';
	echo '<span class="th-label-s"></span><label class="th-label">Longitude</label>: <input type="text" name="ddata[longitude]" id="longitude" value="'.$ddata[longitude].'"></p>';
	echo '<div id="map" style="height:400px;"></div>';
	echo '
		<script>
	var autocomplete;
	var marker;
	var map;
	var mlat;
	var mlng;
	function initLoc(){
		initAutocomplete();
		initMap();
	}
	function initAutocomplete() {
		autocomplete = new google.maps.places.Autocomplete((document.getElementById("autocomplete")), {types: ["geocode"]});
		autocomplete.addListener("place_changed", fillInAddress1);
	}
	function fillInAddress1() {
		var place = autocomplete.getPlace();
		jQuery("#latitude").val(place.geometry.location.lat());
		jQuery("#longitude").val(place.geometry.location.lng());
		marker.setPosition(place.geometry.location);
		map.setCenter(place.geometry.location);
	}
	function initMap() { 
		mlat = Number(jQuery("#latitude").val());
		mlng = Number(jQuery("#longitude").val());
		var myLatLng = {lat: mlat, lng: mlng}; 
		map = new google.maps.Map(document.getElementById("map"), {
			zoom: 4,
			center: myLatLng
		}); 
		marker = new google.maps.Marker({
			position: myLatLng, 
			map: map, 
			draggable: true
		});
		marker.addListener("dragend", fillInAddress2);
		map.addListener("click", fillInAddress3);
	}
	function fillInAddress2() {
		var mpos = marker.getPosition();
		jQuery("#latitude").val(mpos.lat());
		jQuery("#longitude").val(mpos.lng());
		map.setCenter(mpos.latlng);
	}
	function fillInAddress3(e) {
		marker.setPosition(e.latLng);
		jQuery("#latitude").val(e.latLng.lat());
		jQuery("#longitude").val(e.latLng.lng());
		map.setCenter(e.latlng);
	}
	</script>';
	echo '<script src="http://maps.google.com/maps/api/js?sensor=false&libraries=places&callback=initLoc"></script>';
}
function clinic_schedulebox_html($post){
	if($post && $post->ID){$dschd = get_post_meta($post->ID, '_all_schedules', true);}
	//echo '<p><label class="th-label">Sunday</label>: <input type="text" name="dschd[sunday]" value="'.$dschd[sunday].'"></p>';
	//echo '<p><label class="th-label">Monday</label>: <input type="text" name="dschd[monday]" value="'.$dschd[monday].'"></p>';
	//echo '<p><label class="th-label">Tuesday</label>: <input type="text" name="dschd[tuesday]" value="'.$dschd[tuesday].'"></p>';
	//echo '<p><label class="th-label">Wednesday</label>: <input type="text" name="dschd[wednesday]" value="'.$dschd[wednesday].'"></p>';
	//echo '<p><label class="th-label">Thursday</label>: <input type="text" name="dschd[thursday]" value="'.$dschd[thursday].'"></p>';
	//echo '<p><label class="th-label">Friday</label>: <input type="text" name="dschd[friday]" value="'.$dschd[friday].'"></p>';
	//echo '<p><label class="th-label">Saturday</label>: <input type="text" name="dschd[saturday]" value="'.$dschd[saturday].'"></p>';
	
	echo '<p><label class="th-label">Sunday</label>: <input type="text" name="dschd[sun][ts][t]" value="'.$dschd[sun][ts][t].'"> <select name="dschd[sun][ts][o]"><option value="AM">AM</option><option value="PM" '.selected($dschd[sun][ts][o], "PM", false).'>PM</option></select> To <input type="text" name="dschd[sun][te][t]" value="'.$dschd[sun][te][t].'"> <select name="dschd[sun][te][o]"><option value="AM">AM</option><option value="PM" '.selected($dschd[sun][te][o], "PM", false).'>PM</option></select></p>';
	echo '<p><label class="th-label">Monday</label>: <input type="text" name="dschd[mon][ts][t]" value="'.$dschd[mon][ts][t].'"> <select name="dschd[mon][ts][o]"><option value="AM">AM</option><option value="PM" '.selected($dschd[mon][ts][o], "PM", false).'>PM</option></select> To <input type="text" name="dschd[mon][te][t]" value="'.$dschd[mon][te][t].'"> <select name="dschd[mon][te][o]"><option value="AM">AM</option><option value="PM" '.selected($dschd[mon][te][o], "PM", false).'>PM</option></select></p>';
	echo '<p><label class="th-label">Tuesday</label>: <input type="text" name="dschd[tue][ts][t]" value="'.$dschd[tue][ts][t].'"> <select name="dschd[tue][ts][o]"><option value="AM">AM</option><option value="PM" '.selected($dschd[tue][ts][o], "PM", false).'>PM</option></select> To <input type="text" name="dschd[tue][te][t]" value="'.$dschd[tue][te][t].'"> <select name="dschd[tue][te][o]"><option value="AM">AM</option><option value="PM" '.selected($dschd[tue][te][o], "PM", false).'>PM</option></select></p>';
	echo '<p><label class="th-label">Wednesday</label>: <input type="text" name="dschd[wed][ts][t]" value="'.$dschd[wed][ts][t].'"> <select name="dschd[wed][ts][o]"><option value="AM">AM</option><option value="PM" '.selected($dschd[wed][ts][o], "PM", false).'>PM</option></select> To <input type="text" name="dschd[wed][te][t]" value="'.$dschd[wed][te][t].'"> <select name="dschd[wed][te][o]"><option value="AM">AM</option><option value="PM" '.selected($dschd[thu][te][o], "PM", false).'>PM</option></select></p>';
	echo '<p><label class="th-label">Thursday</label>: <input type="text" name="dschd[thu][ts][t]" value="'.$dschd[thu][ts][t].'"> <select name="dschd[thu][ts][o]"><option value="AM">AM</option><option value="PM" '.selected($dschd[thu][ts][o], "PM", false).'>PM</option></select> To <input type="text" name="dschd[thu][te][t]" value="'.$dschd[thu][te][t].'"> <select name="dschd[thu][te][o]"><option value="AM">AM</option><option value="PM" '.selected($dschd[thu][te][o], "PM", false).'>PM</option></select></p>';
	echo '<p><label class="th-label">Friday</label>: <input type="text" name="dschd[fri][ts][t]" value="'.$dschd[fri][ts][t].'"> <select name="dschd[fri][ts][o]"><option value="AM">AM</option><option value="PM" '.selected($dschd[fri][ts][o], "PM", false).'>PM</option></select> To <input type="text" name="dschd[fri][te][t]" value="'.$dschd[fri][te][t].'"> <select name="dschd[fri][te][o]"><option value="AM">AM</option><option value="PM" '.selected($dschd[fri][te][o], "PM", false).'>PM</option></select></p>';
	echo '<p><label class="th-label">Saturday</label>: <input type="text" name="dschd[sat][ts][t]" value="'.$dschd[sat][ts][t].'"> <select name="dschd[sat][ts][o]"><option value="AM">AM</option><option value="PM" '.selected($dschd[sat][ts][o], "PM", false).'>PM</option></select> To <input type="text" name="dschd[sat][te][t]" value="'.$dschd[sat][te][t].'"> <select name="dschd[sat][te][o]"><option value="AM">AM</option><option value="PM" '.selected($dschd[sat][te][o], "PM", false).'>PM</option></select></p>';
}
add_action('save_post', 'clinic_save_post', 10, 2);
function clinic_save_post($pid, $post){
	if($post->post_type == 'clinic'){
		$dts = $hrs = array();
		$ddts = $_POST['ddata']; if(is_array($ddts)){$dts = $ddts;}
		$dhrs = $_POST['dschd']; if(is_array($dhrs)){$hrs = $dhrs;}
		update_post_meta($pid, '_all_datas', $dts);
		update_post_meta($pid, '_all_schedules', $hrs);
		if($dts){
			if(!$dts[latitude] || !is_numeric($dts[latitude])){$dts[latitude] = 0;}
			if(!$dts[longitude] || !is_numeric($dts[longitude])){$dts[longitude] = 0;}
		}
		foreach($dts as $k=>$v){
			update_post_meta($pid, $k, $v);
		}
		foreach($hrs as $k=>$v){
			if(empty($v)){delete_post_meta($pid, '_scdl-'.$k);}
			else {update_post_meta($pid, '_scdl-'.$k, $v);}
		}
		$au = $_POST['post_author_override'];
		if(isset($_POST['post_author_override']) && $au>0){
			//global $wpdb; $table = $wpdb->prefix.'posts';
			//$wpdb->update($table, array('post_author'=>$au), array('post_parent'=>$pid));
		}
		if($dts){
			$locar = array('pID'=>$pid, 'pType'=>$post->post_type, 'latitude'=>$dts[latitude], 'longitude'=>$dts[longitude]);
			addEdit_thct_location($locar);
		}		
	}
}
add_action( 'delete_post', 'clinic_delete' );
function clinic_delete($pid){
	$post = get_post($pid);
	if ( $post->post_type != 'clinic' ) return;
	delete_thct_location($pid);
}

/*add_filter('views_edit-dispensary','views_dispensary');
function views_dispensary($views){
	//$vws = $views;
	//foreach($vws as $k=>$v){if(($k!='mine')){}}
	return $views;
}
add_filter( 'map_meta_cap', 'my_map_meta_cap', 10, 4 );
function my_map_meta_cap( $caps, $cap, $user_id, $args ) {

	//if ( 'create_dispensarys' == $cap) {
		//$post = get_post( $args[0] );
		//$post_type = get_post_type_object( $post->post_type );

		//$caps = array();
	//}

	if ( 'create_dispensarys' == $cap ) {
		//$caps=array(); //false
	}
	//
	return $caps;
}*/
?>
