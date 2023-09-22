<?php
add_action('init', 'register_dispensary_type');
function register_dispensary_type() {
	$labels = array(
		'name'               => _x( 'Dispensaries', 'post type general name', 'thcPlugin' ),
		'singular_name'      => _x( 'Dispensary', 'post type singular name', 'thcPlugin' ),
		'menu_name'          => _x( 'Dispensaries', 'admin menu', 'thcPlugin' ),
		'name_admin_bar'     => _x( 'Dispensary', 'add new on admin bar', 'thcPlugin' ),
		'add_new'            => _x( 'Add New', 'dispensary', 'thcPlugin' ),
		'add_new_item'       => __( 'Add New Dispensary', 'thcPlugin' ),
		'new_item'           => __( 'New Dispensary', 'thcPlugin' ),
		'edit_item'          => __( 'Edit Dispensary', 'thcPlugin' ),
		'view_item'          => __( 'View Dispensary', 'thcPlugin' ),
		'all_items'          => __( 'All Dispensaries', 'thcPlugin' ),
		'search_items'       => __( 'Search Dispensaries', 'thcPlugin' ),
		'parent_item_colon'  => __( 'Parent Dispensaries:', 'thcPlugin' ),
		'not_found'          => __( 'No dispensaries found.', 'thcPlugin' ),
		'not_found_in_trash' => __( 'No dispensaries found in Trash.', 'thcPlugin' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'thcPlugin' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'dispensary' ),
		'capability_type'    => 'dispensary',
		//'capabilities'       => array('create_posts' => true), // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
		'map_meta_cap'       => true,
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title', 'thumbnail')
	);

	register_post_type( 'dispensary', $args );
}
add_action( 'add_meta_boxes_dispensary', 'dispensary_meta_boxes' );
function dispensary_meta_boxes(){
	add_meta_box('dispensary-data-box', 'Dispensary Data', 'dispensary_data_box_html', 'dispensary', $context='advanced', $priority='default', $callback_args=null);
	add_meta_box('dispensary-schedule-box', 'Hours Opened', 'dispensary_schedulebox_html', 'dispensary', $context='advanced', $priority='default', $callback_args=null);
	add_meta_box('dispensary-social-box', 'Social Media Links', 'dispensary_socialbox_html', 'dispensary', $context='advanced', $priority='default', $callback_args=null);
	add_meta_box('dispensary-offer-box', 'Special Offer', 'dispensary_offerbox_html', 'dispensary', $context='advanced', $priority='default', $callback_args=null);
	add_meta_box('dispensary-menu-box', 'Menu Items', 'dispensary_menubox_html', 'dispensary', $context='advanced', $priority='default', $callback_args=null);
}
function dispensary_data_box_html($post){
	global $wpdb;
	if($post && $post->ID){$ddata = get_all_data($post->ID);}
	if(current_user_can('administrator') || current_user_can('rep')){
		$blog_id = get_current_blog_id();
		$roles = array('administrator', 'dispensary', 'seeder'); 
		$meta_query = array('key' => $wpdb->get_blog_prefix($blog_id) . 'capabilities', 'value' => '"(' . implode('|', array_map('preg_quote', $roles)) . ')"', 'compare' => 'REGEXP');
		$user_query = new WP_User_Query(array('meta_query' => array($meta_query)));
		$users = $user_query->get_results();
		$uc = get_userdata(get_current_user_id());
		echo '<p><label class="th-label">Owner</label>: <select class="combo" name="post_author_override">';
		if($users){foreach($users as $u){echo '<option value="'.$u->ID.'" '.selected($u->ID, $post->post_author, false).'>'.$u->display_name.'</option>';}}
		echo '</select></p>';
		echo '<p><label class="th-label">Featured Dispensary</label>: <input name="featured" type="checkbox" value="yes" '.((get_post_meta($post->ID, 'featured', true))? 'checked':'').'></p>';
	}
	echo '<p><label class="th-label">Business Type</label>: <select name="ddata[type]" id="ddtype" onChange="tog_des_typ();"><option value="store" '.selected("store", $ddata[type], false).'>Store Front</option><option value="delivery" '.selected("delivery", $ddata[type], false).'>Delivery</option><option value="clone" '.selected("clone", $ddata[type], false).'>Seeds/Clones</option></select></p>';
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
function dispensary_schedulebox_html($post){
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
function dispensary_menubox_html($post){
	thc_listing_edit($post);
	echo '<div id="ltwrap">';
	dispensary_menulist_html($post);
	echo '</div>';
}
function dispensary_socialbox_html($post){
	if($post && $post->ID){$social = get_post_meta($post->ID, '_all_socials', true);}
	if(!is_array($social)){$social = array();}
	echo '<p><label class="th-label">Facebook Link</label>: <input type="text" name="social[facebook]" value="'.$social[facebook].'"></p>';
	echo '<p><label class="th-label">Twitter Link</label>: <input type="text" name="social[twitter]" value="'.$social[twitter].'"></p>';
	echo '<p><label class="th-label">Pinterest Link</label>: <input type="text" name="social[pinterest]" value="'.$social[pinterest].'"></p>';
	echo '<p><label class="th-label">Linkedin Link</label>: <input type="text" name="social[linkedin]" value="'.$social[linkedin].'"></p>';
	echo '<p><label class="th-label">Google+ Link</label>: <input type="text" name="social[gplus]" value="'.$social[gplus].'"></p>';
	echo '<p><label class="th-label">Youtube Link</label>: <input type="text" name="social[youtube]" value="'.$social[youtube].'"></p>';
	echo '<p><label class="th-label">Instagram Link</label>: <input type="text" name="social[instagram]" value="'.$social[instagram].'"></p>';
}
function dispensary_offerbox_html($post){
	if($post && $post->ID){$offer = get_post_meta($post->ID, '_all_offers', true);}
	if(!is_array($offer)){$offer = array();}
	echo '<p><label class="th-label">Activate</label>: <input name="offer[active]" type="checkbox" value="yes" '.(($offer[active])? 'checked':'').'></p>';
	echo '<p><label class="th-label">Title</label>: <input type="text" name="offer[title]" value="'.$offer[title].'"></p>';
	echo '<p><label class="th-label">Content</label>: <textarea name="offer[content]">'.$offer[content].'</textarea></p>';
	echo '<p><label class="th-label">Image</label>: <button class="thc-upload button" thc-name="offer[image]">Upload Image</button><span>';
	if($offer[image]){foreach($offer[image] as $k=>$v){
	echo '<span class="thc-uploaded" style="background-image:url(\''.$v.'\')"><input type="hidden" value="'.$v.'" name="offer[image]['.$k.']" /><span></span></span>';
	}}
	echo '</span></p>';
	echo '<p><label class="th-label">Price</label>: <input type="text" name="offer[price]" value="'.$offer[price].'"></p>';
}
function dispensary_menulist_html($post){
	echo '<h3 class="title">All Menu Items :</h3>';?>
	<?php $lists1 = thc_delivery_listings($post->ID); $typ = '';
	if($lists1){
		echo '<table class="list-table deli" border="1px" bordercolor="#ccc">';
		foreach($lists1 as $z){
			if($typ == '' || $typ != ucwords(thc_get_data($z->ID, 'type'))){$typ = ucwords(thc_get_data($z->ID, 'type'));
				echo '<tr class="listcat" onClick="tog_deli_item(\''.$typ.'\');"><td>'.$typ.' &raquo;</td><td>1/8</td><td>1/4</td><td>1/2</td><td>oz</td><td></td></tr>';
			}
			$prc = get_thct_price($z->ID);
			$item = '';
			$item .= '<div class="list-item" style="">';
			$item .= '<p class="item-title"><a href="'.get_permalink(thc_get_data($z->ID, 'strain_id')).'" title="Learn More!">'.get_the_title(thc_get_data($z->ID, 'strain_id')).'</a><span>'.$prc->p18.'<span>1/8</span>'.$prc->p14.'<span>1/4</span>'.$prc->p12.'<span>1/2</span>'.$prc->poz.'<span>oz</span></span></p>';
			$imgs = thc_get_data($z->ID, 'images');//$imgs = get_posts(array('post_type'=>'attachment', 'post_status'=>'inherit', 'post_parent'=>$z->ID, 'posts_per_page'=>5));
			
			if($imgs){$item .= '<p>';foreach($imgs as $k=>$v){if(wp_attachment_is_image($k)){
				$item .= '<img class="item-img" src="'.wp_get_attachment_thumb_url($k).'">';
			}}$item .= '</p>';}
			$item .= '<div class="hentry-content fleft">';
				$item .= '<p><strong>Medical Values :</strong></p>';
				$medis = get_thct_medis($z->ID);
				if($medis){foreach($medis as $zz){
					$item .= '<p>'.$zz.' : '.get_rating($z->ID, '_medi-', $zz, false).'</p>';
				}}else{$item .= '<p>No Data Found.</p>';}
			$item .= '</div>';
			$item .= '<div class="hentry-content fleft">';
				$item .= '<p><strong>Flavours :</strong></p>';
				$flavs = get_thct_flavs($z->ID);
				if($flavs){foreach($flavs as $zz){
					$item .= '<p>'.$zz.' : '.get_rating($z->ID, '_flav-', $zz, false).'</p>';
				}}else{$item .= '<p>No Data Found.</p>';}
			$item .= '</div>';
			$item .= '<div class="clear"></div>';
			$item .= '<div class="clear">';

				$item .= '<p><strong>Tested : </strong>';
				$tests = thc_get_testeds($z->ID);
				if($tests){$i=1;foreach($tests as $zz){
					$item .= $zz['key'].': '.$zz['val'].'%'.(($i<3)? ', ':'');
					$i++;
				}}else{$item .= 'No Data Found';}
				$item .= '</p>';
			$item .= '</div>';
			$item .= '<div class="clear"></div>';
			$item .= '<div>';
				$item .= '<p><strong>Description :</strong></p>';
				if($z->post_content){$item .= $z->post_content;}else{$item .= 'No Data Found';}
			$item .= '</div>';
			echo '<tr class="deliitem '.$typ.' i'.$z->ID.'"><td class="itemtitle"><div class="clear">'.$z->post_title.'</div></td><td>'.$prc->p18.'</td><td>'.$prc->p14.'</td><td>'.$prc->p12.'</td><td>'.$prc->poz.'</td><td><a class="page-title-action" onClick="thc_listing_edit('.$z->ID.');">Edit</a><a class="page-title-action" onClick="thc_listing_delete('.$z->ID.');">Delete</a></td></tr><tr class="itemdetail i'.$z->ID.'"><td colspan="6">'.$item.'</td></tr>';
		}
		echo '</table>';
	}
	?>
	<?php $lists2 = thc_seed_listings($post->ID); $typ = '';
	if($lists2){
		echo '<table class="list-table seed" border="1px" bordercolor="#ccc">';
		foreach($lists2 as $z){
			if($typ == '' || $typ != ucwords(thc_get_data($z->ID, 'type'))){$typ = ucwords(thc_get_data($z->ID, 'type'));
				echo '<tr class="listcat" onClick="tog_seed_item(\''.$typ.'\');"><td>'.$typ.' &raquo;</td><td>1 x R</td><td>5 x R</td><td>10 x R</td><td>20 x R</td><td>30 x R</td><td>40 x R</td><td>50 x R</td><td></td></tr>';
			}
			$pr = get_thct_price($z->ID);
			$item = '';
			$item .= '<div class="list-item">';
			$item .= '<p class="item-title"><a href="'.get_permalink(thc_get_data($z->ID, 'strain_id')).'" title="Learn More!">'.get_the_title(thc_get_data($z->ID, 'strain_id')).'</a></p>';//<span>'.$prc[18].'<span>1/8</span>'.$prc[14].'<span>1/4</span>'.$prc[12].'<span>1/2</span>'.$prc[oz].'<span>oz</span></span>
			$imgs = thc_get_data($z->ID, 'images');//$imgs = get_posts(array('post_type'=>'attachment', 'post_status'=>'inherit', 'post_parent'=>$z->ID, 'posts_per_page'=>5));
			
			if($imgs){$item .= '<p>';foreach($imgs as $k=>$v){if(wp_attachment_is_image($k)){
				$item .= '<img class="item-img" src="'.wp_get_attachment_thumb_url($k).'">';
			}}$item .= '</p>';}
			$item .= '<div class="hentry-content fleft">';
				$item .= '<p><strong>Medical Values :</strong></p>';
				$medis = get_thct_medis($z->ID);
				if($medis){foreach($medis as $zz){
					$item .= '<p>'.$zz.' : '.get_rating($z->ID, '_medi-', $zz, false).'</p>';
				}}else{$item .= '<p>No Data Found.</p>';}
			$item .= '</div>';
			$item .= '<div class="hentry-content fleft">';
				$item .= '<p><strong>Flavours :</strong></p>';
				$flavs = get_thct_flavs($z->ID);
				if($flavs){foreach($flavs as $zz){
					$item .= '<p>'.$zz.' : '.get_rating($z->ID, '_flav-', $zz, false).'</p>';
				}}else{$item .= '<p>No Data Found.</p>';}
			$item .= '</div>';
			$item .= '<div class="clear">';
				$item .= '<p><strong>Tested : </strong>';
				$tests = thc_get_testeds($z->ID);
				if($tests){$i=1;foreach($tests as $zz){
					$item .= $zz->key.': '.$zz->val.'%'.(($i<3)? ', ':'');
					$i++;
				}}else{$item .= 'No Data Found';}
				$item .= '</p>';
			$item .= '</div>';
			$item .= '<div class="clear">';
				$item .= '<p><strong>Description :</strong></p>';
				if($z->post_content){$item .= $z->post_content;}else{$item .= 'No Data Found';}
			$item .= '</div>';
			echo '<tr class="seeditem '.$typ.' i'.$z->ID.'"><td class="itemtitle"><div class="clear">'.$z->post_title.'</div></td><td>'.$pr->p1xR.'</td><td>'.$pr->p5xR.'</td><td>'.$pr->p10xR.'</td><td>'.$pr->p20xR.'</td><td>'.$pr->p30xR.'</td><td>'.$pr->p40xR.'</td><td>'.$pr->p50xR.'</td><td><a class="page-title-action" onClick="thc_listing_edit('.$z->ID.');">Edit</a><a class="page-title-action" onClick="thc_listing_delete('.$z->ID.');">Delete</a></td></tr><tr class="itemdetail i'.$z->ID.'"><td colspan="9">'.$item.'</td></tr>';
		}
		echo '</table>';
	}
	if(!$lists1 && !$lists2){echo '<p class="lev2">No Data Found.</p>';}
	echo '<div class="clear"></div>';
}
add_action('save_post', 'dispensary_save_post', 10, 2);
function dispensary_save_post($pid, $post){
	if($post->post_type == 'dispensary'){
		$dts = $hrs = array();
		$ddts = $_POST['ddata']; if(is_array($ddts)){$dts = $ddts;}
		$dhrs = $_POST['dschd']; if(is_array($dhrs)){$hrs = $dhrs;}
		$social = $_POST['social'];
		$offer = $_POST['offer'];
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
			global $wpdb; $table = $wpdb->prefix.'posts';
			$wpdb->update($table, array('post_author'=>$au), array('post_parent'=>$pid));
		}
		if(isset($_POST['featured'])){update_post_meta($pid, 'featured', $_POST['featured']);}
		else {delete_post_meta($pid, 'featured');}
		
		if($social && is_array($social)){
			$social = array_filter($social);
			update_post_meta($pid, '_all_socials', $social);
		}
		
		if($offer && is_array($offer)){
			$offer = array_filter($offer);
			update_post_meta($pid, '_all_offers', $offer);
		}

		if($dts){
			$locar = array('pID'=>$pid, 'pType'=>$post->post_type, 'latitude'=>$dts[latitude], 'longitude'=>$dts[longitude], 'dispType'=>$dts[type], 'featured'=>(($_POST['featured'])? "yes":""));
			addEdit_thct_location($locar);
		}
		
		if(current_user_can('rep')){
			update_post_meta($pid, 'representative', get_current_user_id());
		}		
	}
}
add_action( 'delete_post', 'dispensary_delete' );
function dispensary_delete($pid){
	$post = get_post($pid);
	if ( $post->post_type != 'dispensary' ) return;
	delete_thct_location($pid);
}
?>
