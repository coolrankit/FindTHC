<?php
function add_related_roles(){
	add_role( 'dispensary', 'Dispensary', array('read' => true, 'level_0' => true ));
	add_role( 'seeder', 'Seeder', array('read' => true, 'level_0' => true ));
	add_role( 'grower', 'Grower', array('read' => true, 'level_0' => true ));
	add_role( 'breeder', 'Breeder', array('read' => true, 'level_0' => true ));
	add_role( 'lab', 'Laboratory', array('read' => true, 'level_0' => true ));
	add_role( 'rep', 'Representative', array('read' => true, 'level_0' => true ));
	add_role( 'doctor', 'Doctor', array('read' => true, 'level_0' => true ));
}
function remove_related_roles(){
	remove_role( 'dispensary' );
	remove_role( 'seeder' );
	remove_role( 'grower' );
	remove_role( 'breeder' );
	remove_role( 'lab' );
	remove_role( 'rep' );
	remove_role( 'doctor' );
}
function thc_createTable($theTable, $sql){
	global $wpdb;
	if($wpdb->get_var("show tables like '". $theTable . "'") != $theTable) {
		$wpdb->query($sql);
	}
}
function thc_createView($theTable, $sql){
	global $wpdb;
	$sql = "CREATE OR REPLACE VIEW `$theTable` AS ". $sql;
	$wpdb->query($sql);
}

add_action( 'admin_init', 'add_extra_caps');
function add_extra_caps() {
	$role = get_role( 'administrator' );

	$role->add_cap( 'edit_strains' ); 
	$role->add_cap( 'edit_published_strains' ); 
	$role->add_cap( 'edit_private_strains' ); 
	$role->add_cap( 'edit_others_strains' ); 
	$role->add_cap( 'delete_published_strains' ); 
	$role->add_cap( 'delete_private_strains' ); 
	$role->add_cap( 'delete_others_strains' ); 
	$role->add_cap( 'read_published_strains' ); 
	$role->add_cap( 'read_private_strains' ); 
	$role->add_cap( 'publish_strains' ); 
	$role->add_cap( 'delete_strains' ); 

	$role->add_cap( 'edit_dispensarys' ); 
	$role->add_cap( 'edit_published_dispensarys' ); 
	$role->add_cap( 'edit_private_dispensarys' ); 
	$role->add_cap( 'edit_others_dispensarys' ); 
	$role->add_cap( 'delete_published_dispensarys' ); 
	$role->add_cap( 'delete_private_dispensarys' ); 
	$role->add_cap( 'delete_others_dispensarys' ); 
	$role->add_cap( 'read_published_dispensarys' ); 
	$role->add_cap( 'read_private_dispensarys' ); 
	$role->add_cap( 'publish_dispensarys' ); 
	$role->add_cap( 'delete_dispensarys' ); 

	$role->add_cap( 'edit_listings' ); 
	$role->add_cap( 'edit_published_listings' ); 
	$role->add_cap( 'edit_private_listings' ); 
	$role->add_cap( 'edit_others_listings' ); 
	$role->add_cap( 'delete_published_listings' ); 
	$role->add_cap( 'delete_private_listings' ); 
	$role->add_cap( 'delete_others_listings' ); 
	$role->add_cap( 'read_published_listings' ); 
	$role->add_cap( 'read_private_listings' ); 
	$role->add_cap( 'publish_listings' ); 
	$role->add_cap( 'delete_listings' ); 
	
	$role->add_cap( 'edit_labs' ); 
	$role->add_cap( 'edit_published_labs' ); 
	$role->add_cap( 'edit_private_labs' ); 
	$role->add_cap( 'edit_others_labs' ); 
	$role->add_cap( 'delete_published_labs' ); 
	$role->add_cap( 'delete_private_labs' ); 
	$role->add_cap( 'delete_others_labs' ); 
	$role->add_cap( 'read_published_labs' ); 
	$role->add_cap( 'read_private_labs' ); 
	$role->add_cap( 'publish_labs' ); 
	$role->add_cap( 'delete_labs' ); 

	$role->add_cap( 'edit_psets' ); 
	$role->add_cap( 'edit_published_psets' ); 
	$role->add_cap( 'edit_private_psets' ); 
	$role->add_cap( 'edit_others_psets' ); 
	$role->add_cap( 'delete_published_psets' ); 
	$role->add_cap( 'delete_private_psets' ); 
	$role->add_cap( 'delete_others_psets' ); 
	$role->add_cap( 'read_published_psets' ); 
	$role->add_cap( 'read_private_psets' ); 
	$role->add_cap( 'publish_psets' ); 
	$role->add_cap( 'delete_psets' ); 

	$role->add_cap( 'edit_journals' ); 
	$role->add_cap( 'edit_published_journals' ); 
	$role->add_cap( 'edit_private_journals' ); 
	$role->add_cap( 'edit_others_journals' ); 
	$role->add_cap( 'delete_published_journals' ); 
	$role->add_cap( 'delete_private_journals' ); 
	$role->add_cap( 'delete_others_journals' ); 
	$role->add_cap( 'read_published_journals' ); 
	$role->add_cap( 'read_private_journals' ); 
	$role->add_cap( 'publish_journals' ); 
	$role->add_cap( 'delete_journals' ); 

	$role->add_cap( 'edit_seeds' ); 
	$role->add_cap( 'edit_published_seeds' ); 
	$role->add_cap( 'edit_private_seeds' ); 
	$role->add_cap( 'edit_others_seeds' ); 
	$role->add_cap( 'delete_published_seeds' ); 
	$role->add_cap( 'delete_private_seeds' ); 
	$role->add_cap( 'delete_others_seeds' ); 
	$role->add_cap( 'read_published_seeds' ); 
	$role->add_cap( 'read_private_seeds' ); 
	$role->add_cap( 'publish_seeds' ); 
	$role->add_cap( 'delete_seeds' ); 

	$role->add_cap( 'edit_clinics' ); 
	$role->add_cap( 'edit_published_clinics' ); 
	$role->add_cap( 'edit_private_clinics' ); 
	$role->add_cap( 'edit_others_clinics' ); 
	$role->add_cap( 'delete_published_clinics' ); 
	$role->add_cap( 'delete_private_clinics' ); 
	$role->add_cap( 'delete_others_clinics' ); 
	$role->add_cap( 'read_published_clinics' ); 
	$role->add_cap( 'read_private_clinics' ); 
	$role->add_cap( 'publish_clinics' ); 
	$role->add_cap( 'delete_clinics' ); 

	$role = get_role( 'seeder' );
	$role->add_cap( 'edit_seeds' ); 
	$role->add_cap( 'edit_published_seeds' ); 
	$role->add_cap( 'edit_private_seeds' ); 
	$role->add_cap( 'delete_published_seeds' ); 
	$role->add_cap( 'delete_private_seeds' ); 
	$role->add_cap( 'read_published_seeds' ); 
	$role->add_cap( 'read_private_seeds' ); 
	$role->add_cap( 'publish_seeds' ); 
	$role->add_cap( 'delete_seeds' ); 
	
	$role = get_role( 'grower' );
	$role->add_cap( 'upload_files' ); 
	$role->add_cap( 'edit_strains' ); 
	$role->add_cap( 'edit_published_strains' ); 
	$role->add_cap( 'edit_private_strains' ); 
	$role->add_cap( 'delete_published_strains' ); 
	$role->add_cap( 'delete_private_strains' ); 
	$role->add_cap( 'read_published_strains' ); 
	$role->add_cap( 'read_private_strains' ); 
	$role->add_cap( 'publish_strains' ); 
	$role->add_cap( 'delete_strains' ); 

	$role->add_cap( 'edit_journals' ); 
	$role->add_cap( 'edit_published_journals' ); 
	$role->add_cap( 'edit_private_journals' ); 
	$role->add_cap( 'delete_published_journals' ); 
	$role->add_cap( 'delete_private_journals' ); 
	$role->add_cap( 'read_published_journals' ); 
	$role->add_cap( 'read_private_journals' ); 
	$role->add_cap( 'publish_journals' ); 
	$role->add_cap( 'delete_journals' ); 
	
	$role = get_role( 'breeder' );
	$role->add_cap( 'upload_files' ); 
	$role->add_cap( 'edit_strains' ); 
	$role->add_cap( 'edit_published_strains' ); 
	$role->add_cap( 'edit_private_strains' ); 
	$role->add_cap( 'delete_published_strains' ); 
	$role->add_cap( 'delete_private_strains' ); 
	$role->add_cap( 'read_published_strains' ); 
	$role->add_cap( 'read_private_strains' ); 
	$role->add_cap( 'publish_strains' ); 
	$role->add_cap( 'delete_strains' ); 

	$role->add_cap( 'edit_psets' ); 
	$role->add_cap( 'edit_published_psets' ); 
	$role->add_cap( 'edit_private_psets' ); 
	$role->add_cap( 'edit_others_psets' ); 
	$role->add_cap( 'delete_published_psets' ); 
	$role->add_cap( 'delete_private_psets' ); 
	$role->add_cap( 'delete_others_psets' ); 
	$role->add_cap( 'read_published_psets' ); 
	$role->add_cap( 'read_private_psets' ); 
	$role->add_cap( 'publish_psets' ); 
	$role->add_cap( 'delete_psets' ); 
	
	$role->add_cap( 'edit_journals' ); 
	$role->add_cap( 'edit_published_journals' ); 
	$role->add_cap( 'edit_private_journals' ); 
	$role->add_cap( 'delete_published_journals' ); 
	$role->add_cap( 'delete_private_journals' ); 
	$role->add_cap( 'read_published_journals' ); 
	$role->add_cap( 'read_private_journals' ); 
	$role->add_cap( 'publish_journals' ); 
	$role->add_cap( 'delete_journals' ); 
	
	$role = get_role( 'dispensary' );
	$role->add_cap( 'upload_files' ); 
	$role->add_cap( 'edit_dispensarys' ); 
	$role->add_cap( 'edit_published_dispensarys' ); 
	$role->add_cap( 'edit_private_dispensarys' ); 
	$role->add_cap( 'delete_published_dispensarys' ); 
	$role->add_cap( 'delete_private_dispensarys' ); 
	$role->add_cap( 'read_published_dispensarys' ); 
	$role->add_cap( 'read_private_dispensarys' ); 
	$role->add_cap( 'publish_dispensarys' ); 
	$role->add_cap( 'delete_dispensarys' ); 
	$role->remove_cap( 'create_dispensarys' ); 

	$role->add_cap( 'edit_listings' ); 
	$role->add_cap( 'edit_published_listings' ); 
	$role->add_cap( 'edit_private_listings' ); 
	$role->add_cap( 'delete_published_listings' ); 
	$role->add_cap( 'delete_private_listings' ); 
	$role->add_cap( 'read_published_listings' ); 
	$role->add_cap( 'read_private_listings' ); 
	$role->add_cap( 'publish_listings' ); 
	$role->add_cap( 'delete_listings' ); 

	$role = get_role( 'lab' );
	$role->add_cap( 'upload_files' ); 
	$role->add_cap( 'edit_labs' ); 
	$role->add_cap( 'edit_published_labs' ); 
	$role->add_cap( 'edit_private_labs' ); 
	$role->add_cap( 'delete_published_labs' ); 
	$role->add_cap( 'delete_private_labs' ); 
	$role->add_cap( 'read_published_labs' ); 
	$role->add_cap( 'read_private_labs' ); 
	$role->add_cap( 'publish_labs' ); 
	$role->add_cap( 'delete_labs' ); 
	/*$role->remove_cap( 'edit_dispensarys' ); 
	$role->remove_cap( 'edit_published_dispensarys' ); 
	$role->remove_cap( 'edit_private_dispensarys' ); 
	$role->remove_cap( 'edit_others_dispensarys' ); 
	$role->remove_cap( 'delete_published_dispensarys' ); 
	$role->remove_cap( 'delete_private_dispensarys' ); 
	$role->remove_cap( 'delete_others_dispensarys' ); 
	$role->remove_cap( 'read_published_dispensarys' ); 
	$role->remove_cap( 'read_private_dispensarys' ); 
	$role->remove_cap( 'publish_dispensarys' ); 
	$role->remove_cap( 'delete_dispensarys' ); */

	$role = get_role( 'rep' );
	if($role){
	$role->add_cap( 'upload_files' ); 
	$role->add_cap( 'edit_dispensarys' ); 
	$role->add_cap( 'edit_published_dispensarys' ); 
	$role->add_cap( 'edit_private_dispensarys' ); 
	$role->add_cap( 'edit_others_dispensarys' ); 
	$role->add_cap( 'delete_published_dispensarys' ); 
	$role->add_cap( 'delete_private_dispensarys' ); 
	$role->add_cap( 'delete_others_dispensarys' ); 
	$role->add_cap( 'read_published_dispensarys' ); 
	$role->add_cap( 'read_private_dispensarys' ); 
	$role->add_cap( 'publish_dispensarys' ); 
	$role->add_cap( 'delete_dispensarys' ); 

	$role->add_cap( 'edit_listings' ); 
	$role->add_cap( 'edit_published_listings' ); 
	$role->add_cap( 'edit_private_listings' ); 
	$role->add_cap( 'edit_others_listings' ); 
	$role->add_cap( 'delete_published_listings' ); 
	$role->add_cap( 'delete_private_listings' ); 
	$role->add_cap( 'delete_others_listings' ); 
	$role->add_cap( 'read_published_listings' ); 
	$role->add_cap( 'read_private_listings' ); 
	$role->add_cap( 'publish_listings' ); 
	$role->add_cap( 'delete_listings' );
	}

	$role = get_role( 'doctor' );
	if($role){
	$role->add_cap( 'upload_files' ); 
	$role->add_cap( 'edit_clinics' ); 
	$role->add_cap( 'edit_published_clinics' ); 
	$role->add_cap( 'edit_private_clinics' ); 
	$role->add_cap( 'delete_published_clinics' ); 
	$role->add_cap( 'delete_private_clinics' ); 
	$role->add_cap( 'read_published_clinics' ); 
	$role->add_cap( 'read_private_clinics' ); 
	$role->add_cap( 'publish_clinics' ); 
	$role->add_cap( 'delete_clinics' ); 
	}

	if(get_option("publish_strain")){
		$role = get_role( 'grower' ); if($role){$role->remove_cap( 'publish_strains' );}
		$role = get_role( 'breeder' ); if($role){$role->remove_cap( 'publish_strains' );}
	} else {
		$role = get_role( 'grower' ); if($role){$role->add_cap( 'publish_strains' );}
		$role = get_role( 'breeder' ); if($role){$role->add_cap( 'publish_strains' );}
	}
}
add_action( 'init', 'set_user_loc');
function set_user_loc(){
	global $gloc;
	if(empty($gloc) && !is_admin()){
		$uid = get_current_user_id();
		$lat = 0; $lon = 0;
		if($uid>0){$lat = get_the_author_meta( 'latitude', $uid ); $lon = get_the_author_meta( 'longitude', $uid );}
		if(!$lat && !$lon){
			if($_COOKIE['thcploc']){$aloc = explode(",", $_COOKIE['thcploc']); $lat = $aloc[0]; $lon = $aloc[1];}
			else {
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => 'http://ipinfo.io/'.$_SERVER['REMOTE_ADDR'].'/geo'
				));
				$data = curl_exec($curl);
				curl_close($curl);
				//$data = file_get_contents('http://ipinfo.io/'.$_SERVER['REMOTE_ADDR'].'/geo');
				$data = json_decode($data, true);
				if($data[loc]){
					$aloc = explode(",", $data[loc]); $lat = $aloc[0]; $lon = $aloc[1];
					setcookie('thcploc', $data[loc], (time()+(365*24*3600)));
				}
			}
		}
		if(!$lat && !$lon){$lat = 33.641767; $lon = -116.273943;}
		$gloc = array("lat"=>$lat, "lon"=>$lon);
	}
}
add_action( 'pre_get_posts', 'thc_admin_get_strains' );
function thc_admin_get_strains($query){
	$uid = get_current_user_id();
	$post_type = $query->get('post_type');
	//echo $query->get('extra');
	if(is_admin() && (current_user_can('grower') || current_user_can('breeder') || current_user_can('dispensary') || current_user_can('seeder') || current_user_can('lab')) && $query->get('extra') != 'allauthors'){
		$query->set('author', $uid);
	}
	if(is_admin() && $post_type=="listing" && $_GET['dis'] && $_GET['dis']>0){
		$query->set('post_parent', $_GET['dis']);
	}
	if(is_admin() && $post_type=="dispensary" && current_user_can('rep')){
		$query->set('meta_key', 'representative');
		$query->set('meta_value', $uid);
		$query->set('meta_compare', '=');
	}
}
add_filter('wp_count_posts', 'thc_wp_count_posts', 10, 3);
function thc_wp_count_posts( $counts, $type, $perm ) {
	global $wpdb; $pq = $mq = ""; $aq = "post_author";
	if ( ! is_admin() || 'readable' !== $perm ) {return $counts;}
	$post_type_object = get_post_type_object($type);
	$uid = get_current_user_id();
	if (current_user_can( $post_type_object->cap->edit_others_posts ) ) {return $counts;}
	if(is_admin() && $type=="listing" && $_GET['dis'] && $_GET['dis']>0){$pq = " AND post_parent=".$_GET['dis'];}
	if(is_admin() && current_user_can('rep')){$mq = " LEFT JOIN {$wpdb->postmeta} ON ({$wpdb->posts}.ID = {$wpdb->postmeta}.post_id AND {$wpdb->postmeta}.meta_key = 'representative')"; $aq = "meta_value = %d OR post_author";}
	$query = "SELECT post_status, COUNT( * ) AS num_posts".$mq." FROM {$wpdb->posts} WHERE post_type = %s AND (".$aq." = %d)".$pq." GROUP BY post_status";
	$results = (array) $wpdb->get_results( $wpdb->prepare( $query, $type, get_current_user_id() ), ARRAY_A );
	$counts = array_fill_keys( get_post_stati(), 0 );
	foreach ( $results as $row ) {$counts[ $row['post_status'] ] = $row['num_posts'];}
	return (object) $counts;
}
add_filter( 'views_edit-listing', 'thc_edit_listing_views' );
function thc_edit_listing_views( $views ) {
	echo '<style>h1{display:none;}</style>';
	echo '<h1 style="display:block;">Menu Items for '.get_the_title($_GET['dis']).' <a class="page-title-action" href="'.admin_url('post-new.php?post_type=listing&dis='.$_GET['dis']).'">Add New</a></h1>';
	foreach ( $views as $index => $view ) {$views[ $index ] = str_replace( 'post_type=listing', 'post_type=listing&dis='.$_GET['dis'], $view );}
	unset($views["mine"]);
	return $views;
}
add_filter('login_redirect', 'thc_login_redirect', 20, 3);
function thc_login_redirect($redirect_to, $requested_redirect_to, $user){
	if(!user_can($user->ID, 'subscriber')){return admin_url('index.php');}
	elseif(user_can($user->ID, 'subscriber')){return home_url();}
}

add_action('wp_ajax_thc_rate_it', 'thc_rate_it');
add_action('wp_ajax_nopriv_thc_rate_it', 'thc_rate_it');
function thc_rate_it(){
	global $wpdb; $table = $wpdb->prefix.'thct_ratings';
	$uid = get_current_user_id();
	$id = $_REQUEST['id'];
	$typ = $_REQUEST['type'];
	$key = $_REQUEST['key'];
	$val = $_REQUEST['val'];
	if($uid>0 && $id>0 && $typ && $val){
	$key = esc_sql($key);
	$data = array('voter'=>$uid, 'type'=>$typ, 'key'=>$key, 'val'=>$val, 'post'=>$id);
	$p = get_post($id);
	if($p->post_type=='listing'){$data['listing'] = $id; $data['dispensary']=$p->post_parent; $data['strain']=thc_get_data($id, 'strain_id');}
	elseif($p->post_type=='strain'){$data['strain'] = $id;}
	$wpdb->insert($table, $data);
	$r = get_rate($id, $typ, $key);
	echo ($r*10);
	}
}
add_action('wp_ajax_thc_add_new_phen', 'thc_add_new_phen');
add_action('wp_ajax_nopriv_thc_add_new_phen', 'thc_add_new_phen');
function thc_add_new_phen(){
	$x = $_REQUEST['pheno'];
	if($x){
		$va = get_option('_phen-'.$x);
		if($va && is_array($va) && !empty($va)){
			$vs = '<select name="spvals[]">';
			foreach($va as $y){$y = str_replace(array('\"',"\'"), array('"',"'"), $y); $y = htmlspecialchars($y); $vs .= '<option value="'.$y.'">'.$y.'</option>';}
			$vs .= '</select>';
		}
		else{$vs = '<input type="text" name="spvals[]" placeholder="Value" value="">';}
		echo '<p><input type="hidden" name="spkeys[]" value="'.$x.'"><input type="text" value="'.$x.'" disabled> : '.$vs.' <input type="button" class="button" value="X" onClick="rem_phens_vals(this)"></p>';
	}
}
add_action('wp_ajax_thc_add_new_set', 'thc_add_new_set');
add_action('wp_ajax_nopriv_thc_add_new_set', 'thc_add_new_set');
function thc_add_new_set(){
	$i = $_REQUEST['aci'];
	$ka = get_option('all_phentypes');
	if($i){
		echo '<div class="postbox">';
		echo '<div class="thchandlediv expand" aria-expanded="false" title="Click to toggle" onClick="exp_sets_vals(this);"></div>';
		echo '<div class="thchandlediv" onClick="rem_sets_vals(this)" title="Click to remove" thcID=""></div>';
		echo '<h3 id="null"><span>Phenotype '.$i.'</span></h3>';
		echo '<div class="inside">';
		echo '<p><label class=""th-label">Ratio</label>: <input type="number" name="setvals['.$i.'][ratio]" value="100" min="1" max="100"> %</p>';
		if(is_array($ka) && !empty($ka)){foreach($ka as $k=>$kn){
			$va = get_option('_phen-'.$k);
			if($va && is_array($va) && !empty($va)){$vs = '<select name="setvals['.$i.']['.$k.']">'; foreach($va as $v){
				$v = str_replace(array('\"',"\'"), array('"',"'"), $v); $v = htmlspecialchars($v);
				$vs .= '<option value="'.$v.'">'.$v.'</option>';}$vs .= '</select>';
			} else {
				$vs = '<input type="number" name="setvals['.$i.']['.$k.']" placeholder="Value" value="">';
			}
			echo '<p><label style="display:inline-block;width:200px;">'.$kn.'</label> : '.$vs.'</p>';
		}} else {
			echo '<p>No phenotype characteristics found.</p>';
		}
		echo '</div>';
		echo '</div>';
	}
}
add_action('show_user_profile', 'thc_profile_fields');
add_action('edit_user_profile', 'thc_profile_fields');

function thc_profile_fields($user) {
	?>
	<hr>
	<table class="form-table">
	<tr>
		<th>
			<label for="adddress"><?php _e('Address'); ?></label>
		</th>
		<td>
			<input type="text" name="address" id="autocomplete" value="<?php echo esc_attr( get_the_author_meta( 'address', $user->ID ) ); ?>" class="regular-text" />
			<br><span class="description"><?php _e('Your address.', 'thcPlugin'); ?></span>
		</td>
	</tr>
	<tr>
		<th>
			<label for="address"><?php _e('Latitude', 'thcPlugin'); ?></label>
		</th>
		<td>
			<input type="text" name="latitude" id="latitude" value="<?php echo esc_attr( get_the_author_meta( 'latitude', $user->ID ) ); ?>" class="regular-text" />
			<br><span class="description"><?php _e('If Latitude does not change in address change, enter it manually.', 'thcPlugin'); ?></span>
		</td>
	</tr>
	<tr>
		<th>
			<label for="longitude"><?php _e('Longitude', 'thcPlugin'); ?></label>
		</th>
		<td>
			<input type="text" name="longitude" id="longitude" value="<?php echo esc_attr( get_the_author_meta( 'longitude', $user->ID ) ); ?>" class="regular-text" />
			<br><span class="description"><?php _e('If Longitude does not change in address change, enter it manually.', 'thcPlugin'); ?></span>
		</td>
	</tr>
	<tr>
		<th>
			<label for="longitude"><?php _e('Medical Conditions', 'thcPlugin'); ?></label>
		</th>
		<td>
			<?php
			if($user && $user->ID){$meds = get_the_author_meta( '_all_medicinals', $user->ID );}
			if(!is_array($meds)){$meds=array();}
			$ma = get_option('all_medicals');
			echo '<select multiple name="umcons" class="chosen" data-placeholder="Medical Conditions" style="width:250px;">';
			if($ma){foreach($ma as $m){echo '<option value="'.$m.'" '.((in_array($m, $meds))? 'selected':'').'>'.$m.'</option>';}}
			echo '</select>';
			echo '<script>jQuery(".chosen").each(function(){jQuery(this).chosen({allow_single_deselect:true});jQuery(this).before(\'<input type="hidden" name="\'+jQuery(this).attr("name")+\'">\').change(function(){jQuery(this).prev().val(jQuery(this).val());}).removeAttr("name");jQuery(this).prev().val(jQuery(this).val());}); jQuery(\'.chosen\').live(\'chosen:updated\', function(event){jQuery(this).prev().val(\'\');});</script>';
			?>
		</td>
	</tr>
	</table>
	<script>
	var autocomplete;
	function initAutocomplete() {
		autocomplete = new google.maps.places.Autocomplete((document.getElementById("autocomplete")), {types: ["geocode"]});
		autocomplete.addListener("place_changed", fillInAddress);
	}
	function fillInAddress() {
		var place = autocomplete.getPlace();
		document.getElementById("latitude").value = place.geometry.location.lat();
		document.getElementById("longitude").value = place.geometry.location.lng();
	}
	</script>
	<script src="http://maps.google.com/maps/api/js?sensor=false&libraries=places&callback=initAutocomplete"></script>
	<?php
}
add_action( 'profile_update', 'thc_profile_update', 10, 2 );
function thc_profile_update( $user_id, $old_user_data ) {
	if(isset($_POST['umcons'])){update_user_meta($user_id, '_all_medicinals', array_filter(explode(',', $_POST['umcons'])));}
	if(isset($_POST['address'])){update_user_meta($user_id, 'address', $_POST['address']);}
	if(isset($_POST['latitude'])){update_user_meta($user_id, 'latitude', $_POST['latitude']);}
	if(isset($_POST['longitude'])){update_user_meta($user_id, 'longitude', $_POST['longitude']);}
}
/*********************************************************************************/
function get_rated($id=0, $typ='', $key='', $html=false){
	global $wpdb; $table = $wpdb->prefix.'thct_ratings'; $r = false;
	if($id && $typ){
		$key = esc_sql($key);
		$sql = "SELECT AVG(`val`) AS `rating`, COUNT(`val`) AS `votes` FROM `$table` WHERE `post`='$id' AND `type`='$typ'".(($key)? " AND `key`='$key'":"");
		$r = $wpdb->get_row($sql);
	}
	if($html){
		if($r && $r->rating >= 0){$r = number_format($r->rating, 1).' from '.$r->votes.' reviews';}
		else {$r = '0.0 from 0 reviews';}
	}
	return $r;
}
function get_rate($id=0, $typ='', $key=''){
	global $wpdb; $table = $wpdb->prefix.'thct_ratings'; $r = 0;
	if($id && $typ){
		$key = esc_sql($key);
		$sql = "SELECT AVG(`val`) FROM `$table` WHERE `post`='$id' AND `type`='$typ' AND `key`='$key'";
		$r = $wpdb->get_var($sql);
		if($r){$r += 0;} else {$r = 0;}
	}
	if($r>0){$r = round($r, 1);}
	return $r;
}
function has_rate($id=0, $typ='', $key=''){
	global $wpdb; $table = $wpdb->prefix.'thct_ratings';
	$r = false; $uid = get_current_user_id();
	if($id && $typ){
		$key = esc_sql($key);
		$sql = "SELECT COUNT(`val`) FROM `$table` WHERE `voter`='$uid' AND `post`='$id' AND `type`='$typ' AND `key`='$key'";
		$r = $wpdb->get_var($sql);
	} else {$r = true;}
	if($r>0){return false;} else{return true;}
}
function can_rate($id=0, $typ='', $key=''){
	$r = false; $uid = get_current_user_id();
	if($uid>0){$r = true;} else {$r = false;}
	if($r && $id && $typ){$r = has_rate($id, $typ, $key);}
	return $r;
}
function get_rating($id=0, $typ='', $key='', $cr=true){
	$v = get_thct_rating($id, $typ, $key);
	if($v && $v>0){$v *= 10;} else{$v=0;}
	if(get_current_user_id() > 0){$ncrm = 'You already rated it.';} else {$ncrm = 'Login to rate it.';}
	if($cr){$cr = can_rate($id, $typ, $key);} elseif(is_admin()){$ncrm = 'You can\'t rate it here.';} else{$ncrm = 'You can\'t rate it.';}
	$d = '" rate-id="'.$id.'" rate-type="'.$typ.'" rate-key="'.$key; 
	return '<span class="rs-wrapper '.(($cr)? 'can-rate'.$d:'').'" style="width:'.$v.'px;max-width:50px;"><span class="rs-wrap"><a rate-value="1" href="javascript:;" class="star1" title="'.(($cr)? 'Rate it 1 star! You can rate only once.':$ncrm).'"><img src="'.THCP_URL.'/images/1s-e.png"></a><a rate-value="2" href="javascript:;" class="star2" title="'.(($cr)? 'Rate it 2 star! You can rate only once.':$ncrm).'"><img src="'.THCP_URL.'/images/1s-e.png"></a><a rate-value="3" href="javascript:;" class="star3" title="'.(($cr)? 'Rate it 3 star! You can rate only once.':$ncrm).'"><img src="'.THCP_URL.'/images/1s-e.png"></a><a rate-value="4" href="javascript:;" class="star4" title="'.(($cr)? 'Rate it 4 star! You can rate only once.':$ncrm).'"><img src="'.THCP_URL.'/images/1s-e.png"></a><a rate-value="5" href="javascript:;" class="star5" title="'.(($cr)? 'Rate it 5 star! You can rate only once.':$ncrm).'"><img src="'.THCP_URL.'/images/1s-e.png"></a></span></span><span class="rs-cover"></span>';
}
function do_rating($v=0, $cr=false){
	if($v && $v>0){$v *= 10;} else{$v=0;}
	$ncrm = 'You can\'t rate it.';
	$d = '" rate-id="'.$id.'" rate-type="'.$typ.'" rate-key="'.$key; 
	return '<span class="rs-wrapper '.(($cr)? 'can-rate'.$d:'').'" style="width:'.$v.'px;max-width:50px;"><span class="rs-wrap"><a rate-value="1" href="javascript:;" class="star1" title="'.(($cr)? 'Rate it 1 star! You can rate only once.':$ncrm).'"><img src="'.THCP_URL.'/images/1s-e.png"></a><a rate-value="2" href="javascript:;" class="star2" title="'.(($cr)? 'Rate it 2 star! You can rate only once.':$ncrm).'"><img src="'.THCP_URL.'/images/1s-e.png"></a><a rate-value="3" href="javascript:;" class="star3" title="'.(($cr)? 'Rate it 3 star! You can rate only once.':$ncrm).'"><img src="'.THCP_URL.'/images/1s-e.png"></a><a rate-value="4" href="javascript:;" class="star4" title="'.(($cr)? 'Rate it 4 star! You can rate only once.':$ncrm).'"><img src="'.THCP_URL.'/images/1s-e.png"></a><a rate-value="5" href="javascript:;" class="star5" title="'.(($cr)? 'Rate it 5 star! You can rate only once.':$ncrm).'"><img src="'.THCP_URL.'/images/1s-e.png"></a></span></span><span class="rs-cover"></span>';
}
function thc_get_data($id=0, $k=''){
	$v = get_post_meta($id, $k, true);
	if($v){switch($k){
		case 'award':
		case 'award2':
		case 'award3':
		case 'award4':
		case 'award5':
		$x = $v; $v = '';
		if($x && $x[rank] && $x[type]){$v = $x[rank].' in '.$x[type];}
		elseif($x && $x[rank]){$v = $x[rank];}
		if($x && $x[rank] && $x[name] && $x[year]){$v .= ' - '.$x[name].' - '.$x[year];}
		elseif($x && $x[rank] && $x[name]){$v .= ' - '.$x[name];}
		break;
	}}
	switch($k){
		case 'jlink':
		$v = (($v)? $v:get_permalink($id));
		break;
		case 'aka_name':
		$v = (($v)? $v:get_the_title($id));
		break;
		case '_scdl-sunday':
		case '_scdl-monday':
		case '_scdl-tuesday':
		case '_scdl-wednesday':
		case '_scdl-thursday':
		case '_scdl-friday':
		case '_scdl-saturday':
		$d=substr($k,6,3);
		$v = get_post_meta($id, $d, true);
		if($v && is_array($v) && $v[ts][t] && $v[te][t]){$v = $v[ts][t].$v[ts][o].' to '.$v[te][t].$v[te][o];}
		else{$v = "Closed";}
		break;
	}
	return $v;
}
function thc_page_leneage($p){
	$r = 'Findthc > ';
	$u = get_userdata($p->post_author);
	if(user_can($p->post_author, 'breeder')){$r .= 'Breeder > '.$u->display_name.' > ';}
	elseif(user_can($p->post_author, 'grower')){$r .= 'Grower > '.$u->display_name.' > ';}
	$r .= $p->post_title.' &raquo;';
	return $r;
}
function thc_page_title($p){
	$r = thc_page_leneage($p);
	$s = array('Findthc > ', 'Breeder > ', 'Grower > ', ' &raquo;');
	$r = str_replace($s, '', $r);
	$r = str_replace('>', '-', $r);
	return $r;
}
function thc_delivery_listings($id=0, $i=-1){
	$marg = array('relation' => 'AND',
			array('key'=>'type', 'compare'=>'!=', 'value'=>'clone'),
			array('key'=>'type', 'compare'=>'!=', 'value'=>'seed'),
	);
	$args = array('post_type'=>'listing', 'post_parent'=>$id, 'posts_per_page'=>$i, 'orderby' => array( 'type' => 'ASC', 'title' => 'ASC' ), 'meta_query'=>$marg, 'extra'=>'allauthors');//
	return get_posts($args);
}
function thc_seed_listings($id=0, $i=-1){
	$marg = array('relation' => 'OR',
			array('key'=>'type', 'compare'=>'=', 'value'=>'clone'),
			array('key'=>'type', 'compare'=>'=', 'value'=>'seed'),
	);
	$args = array('post_type'=>'listing', 'post_parent'=>$id, 'posts_per_page'=>$i, 'meta_query'=>$marg, 'orderby' => array( 'type' => 'ASC', 'title' => 'ASC' ), 'extra'=>'allauthors');
	return get_posts($args);
}
function thc_get_parents($id){
	$f = get_post_meta($id, 'father', true);
	$m = get_post_meta($id, 'mother', true);
	if($f>0){$r[]=$f+0;} else {$r[]=0;}
	if($m>0){$r[]=$m+0;} else {$r[]=0;}
	return $r;
}
function thc_fam_txt($i=0, $p=0, $pp=0){
	if($i==$p && $p==0){return '';}
	elseif($i==$p && $p==$pp){return '';}
	elseif($i==0 && $p==$pp){return '';}
	elseif($i>0){return '<a href="'.get_permalink($i).'">'.get_the_title($i).'</a>';}
	elseif($i==0){return 'Unknown';}
}
function thc_family_tree($l1){
	$html = $html3 = $html4 = $html5 = $d3 = $d4 = $d5 =''; $i=1;
	// 1
	$html .= '<table class="family-tree" border="0px" bordercolor="#ccc" style="text-align:center;" cellpadding="0" cellspacing="0">';
	$l2s = thc_get_parents($l1);
	// 5
	$html5 .= '<tr class="lv5">';
	foreach($l2s as $l2){$l3s = thc_get_parents($l2); foreach($l3s as $l3){$l4s = thc_get_parents($l3); foreach($l4s as $l4){$l5s = thc_get_parents($l4); foreach($l5s as $l5){
		if(($i%2)==0){if($d5 != ''){$i5 = '<img src="'.THCP_URL.'/images/1px0.png">';} else{$i5 = '';} $d5='';}
		else {$i5 = ''; $d5 .= thc_fam_txt($l5, $l4, $l3);}
		$html5 .= '<td style="width:6.25%" class="td'.($i%2).'">'.$i5.'<span class="s50"></span>'.thc_fam_txt($l5, $l4, $l3).'</td>'; $i++;
	}}}} $i=1;
	$html5 .= '</tr>';
	// 4
	$html4 .= '<tr class="lv4">';
	foreach($l2s as $l2){$l3s = thc_get_parents($l2); foreach($l3s as $l3){$l4s = thc_get_parents($l3); foreach($l4s as $l4){
		if(($i%2)==0){if($d4 != ''){$i4 = '<img src="'.THCP_URL.'/images/1px0.png">';} else{$i4 = '';} $d4='';}
		else {$i4 = ''; $d4 .= thc_fam_txt($l4, $l3, $l2);}
		$html4 .= '<td colspan="2" class="td'.($i%2).'">'.$i4.'<span class="s50"></span>'.thc_fam_txt($l4, $l3, $l2).'</td>'; $i++;
	}}} $i=1;
	$html4 .= '</tr>';
	//3
	$html3 .= '<tr class="lv3">';
	foreach($l2s as $l2){$l3s = thc_get_parents($l2); foreach($l3s as $l3){
		if(($i%2)==0){if($d3 != ''){$i3 = '<img src="'.THCP_URL.'/images/1px0.png">';} else{$i3 = '';} $d3='';}
		else {$i3 = ''; $d3 .= thc_fam_txt($l3, $l2, $l1);}
		$html3 .= '<td colspan="4" class="td'.($i%2).'">'.$i3.'<span class="s50"></span>'.thc_fam_txt($l3, $l2, $l1).'</td>'; $i++;
	}} $i=1;
	$html3 .= '</tr>';
	// 2
	$html2 .= '<tr class="lv2">';
	foreach($l2s as $l2){
		if(($i%2)==0){if($d2 != ''){$i2 = '<img src="'.THCP_URL.'/images/1px0.png">';} else{$i2 = '';} $d2='';}
		else {$i2 = ''; $d2 .= thc_fam_txt($l2, $l1);}
		$html2 .= '<td colspan="8" class="td'.($i%2).'">'.$i2.'<span class="s50"></span>'.thc_fam_txt($l2, $l1).'</td>'; $i++;
	} $i=1;
	$html2 .= '</tr>';
	// 1
	$html1 .= '<tr class="lv1"><td colspan="16">'.thc_fam_txt($l1).'</td></tr>';

	$html .= $html1;
	$html .= $html2;
	$html .= $html3;
	$html .= $html4;
	$html .= $html5;
	$html .= '</table>';
	//$html = str_replace('</span><img src="'.THCP_URL.'/images/1px0.png">', '</span>', $html);
	$html = str_replace('<span class="s50"></span></td>', '</td>', $html);
	return $html;
}
/*******************************************************************************************************************/
function thc_topmap(){
	$uid = get_current_user_id(); $html = '';
	global $gloc; $lat = $gloc["lat"]; $lon = $gloc["lon"];
	$html .= "<script>\n";
	$html .="var map = new google.maps.Map(document.getElementById('topmap'), {
		zoom: 1,
		center: new google.maps.LatLng($lat, $lon),
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: true,
		streetViewControl: true,
		panControl: false,
		zoomControlOptions: {position: google.maps.ControlPosition.RIGHT_BOTTOM}
	});\n";
	$html .= "var istore = '".THCP_URL."/images/pin-dispensary.png';\n";
	$html .= "var idelivery = '".THCP_URL."/images/pin-delivery.png';\n";
	$html .= "var iclone = '".THCP_URL."/images/pin-clone.png';\n";
	$html .= "var iclinic = '".THCP_URL."/images/pin-doctor.png';\n";
	$html .= "var ifb = '".THCP_URL."/images/pin-default.png';\n";
	//$html .= "var iseed = '".THCP_URL."/images/pin-clone.png';\n";
	//$html .= "var ibth = '".THCP_URL."/images/pin-dispensary.png';\n";
	$html .= "var locations = [\n";		
	if(is_singular('strain')){
		$marks = get_map_markers('strain', get_the_ID());
		if($marks && !empty($marks)){foreach($marks as $m){
			$html .= "['<h4><a ".'href="'.get_permalink($m->ID).'"'.">{$m->post_title}</a></h4><p>{$m->address}</p><p>Type: ".(($m->dispType=='delivery')? 'Delivery Only':'').(($m->dispType=='store')? 'Store Front':'').(($m->dispType=='clone')? 'Seeds/Clones Only':'')."</p>', {$m->lat}, {$m->lon}, i{$m->dispType}],\n";
		}} else {
			$html .= "['<h4>Your Location</h4><p>No nearby item found to mark on map.</p>', $lat, $lon, ifb],\n";
		}
	}
	if(is_home() || is_front_page()){
		$marks = get_map_markers('home');
		if($marks && !empty($marks)){foreach($marks as $m){
			$html .= "['<h4><a ".'href="'.get_permalink($m->ID).'"'.">{$m->post_title}</a></h4><p>{$m->address}</p><p>".(($m->dispType=='delivery')? 'Delivery Only':'').(($m->dispType=='store')? 'Store Front':'').(($m->dispType=='clone')? 'Seeds/Clones Only':'')."</p>', {$m->lat}, {$m->lon}, i{$m->dispType}],\n";
		}} else {
			$html .= "['<h4>Your Location</h4><p>No nearby item found to mark on map.</p>', $lat, $lon, ifb],\n";
		}
	}
	if(is_singular('dispensary')){
			$html .= "['<h4><a ".'href="'.get_permalink(get_the_ID()).'"'.">".get_the_title(get_the_ID())."</a></h4><p>".thc_get_data(get_the_ID(), 'address')."</p>', ".thc_get_data(get_the_ID(), 'latitude').", ".thc_get_data(get_the_ID(), 'longitude').", i".thc_get_data(get_the_ID(), 'type')."],\n";
	}
	if(is_singular('clinic')){
			$html .= "['<h4><a ".'href="'.get_permalink(get_the_ID()).'"'.">".get_the_title(get_the_ID())."</a></h4><p>".thc_get_data(get_the_ID(), 'address')."</p>', ".thc_get_data(get_the_ID(), 'latitude').", ".thc_get_data(get_the_ID(), 'longitude').", iclinic],\n";
	}
	$html .= "];\n";		
	$html .= "var infowindow = new google.maps.InfoWindow({maxWidth: 160});\n";		
	$html .= "var markers = new Array();\n";		
	$html .= "for (var i = 0; i < locations.length; i++) {  
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(locations[i][1], locations[i][2]),
			map: map,
			icon: locations[i][3]
		});
		markers.push(marker);
	
		google.maps.event.addListener(marker, 'click', (function(marker, i) {
			return function() {
				infowindow.setContent(locations[i][0]);
				infowindow.open(map, marker);
			}
		})(marker, i));\n}\n";		
	$html .= "function autoCenter() {
		var bounds = new google.maps.LatLngBounds();
		for (var i = 0; i < markers.length; i++) {  
			bounds.extend(markers[i].position);
		}
		map.fitBounds(bounds);
	}
	autoCenter();";		
	$html .= "\n</script>";
	return $html;
}
function thc_seeds($id=0, $c=3){
	$args = array(
		'posts_per_page'   => $c,
		'orderby'          => 'title',
		'order'            => 'DESC',
		'post_type'        => 'seed',
		'post_status'      => 'publish',
		'meta_key'      => 'strain_id',
		'meta_value'      => $id,
		'meta_compare'      => '=',
	);
	return get_posts($args);
}
function addSuggestion($data){
	global $wpdb; $table = $wpdb->prefix . 'thct_suggestions';
	if($data && $data[ID]){
		$wpdb->update($table, $data, array("ID"=>$data[ID]));
	} elseif($data){
		$wpdb->insert($table, $data);
	}
}
function addEditClaim($data){
	global $wpdb; $table = $wpdb->prefix . 'thct_claims';
	if($data && $data[ID]){
		$wpdb->update($table, $data, array("ID"=>$data[ID]));
	} elseif($data){
		$wpdb->insert($table, $data);
	}
}
function get_journals($sid=0){
	$args = array(
		'posts_per_page'   => 12,
		'orderby'          => 'title',
		'order'            => 'DESC',
		'post_type'        => 'journal',
		'post_status'      => 'publish',
		'meta_key'         => 'strain_id',
		'meta_value'       => $sid,
		'meta_compare'     => '=',
	);
	return get_posts($args);
}
add_action('init', 'thcSuggest');
function thcSuggest(){
	if($_SERVER['REQUEST_METHOD']=="POST" && $_POST['ovd']){
		$ovd = $_POST['ovd'];
		if(is_array($ovd) && !empty($ovd) && !empty($ovd['data'])){$ovd['data'] = serialize($ovd['data']);
			if($ovd[dtype]=='gj'){
				$ovd['data'] = unserialize($ovd['data']);
				if($ovd[title] && $ovd[content] && $ovd[item]>0 && $ovd[user]>0){
					$arg = array('post_title'=>$ovd[title], 'post_content'=>$ovd[content], 'post_author'=>$ovd[user], 'post_type'=>'journal', 'post_status'=>'publish');
					$pid = wp_insert_post($arg);
					if($pid){
						$ovd['data'][strain_id] = $ovd[item];
						update_post_meta($pid, '_all_datas', $ovd['data']);
						foreach($ovd['data'] as $k=>$v){update_post_meta($pid, $k, $v);}
					}
				}
			}
			elseif($ovd[dtype]=='ac'){
				$cms = unserialize($ovd['data']);
				$cmds = array('pID'=>$ovd[item], 'voter'=>$ovd[user], 'strain'=>$ovd[item], 'type'=>'chemo', 'phenID'=>$ovd[pheno], 'lab'=>$ovd[lab]);
				if($cmds && $cms && is_array($cms)){
					$f1 = 0; $f2 = 0; $st = 0; $cms1 = $cms;
					$cmds[status] = $st;
					if(is_array($cmds) && $cmds[phenID] && $cmds[lab] && $cmds[phenID] != 0 && $cmds[lab] != 0){$f1 = 1;}
					if(is_array($cms1)){foreach($cms1 as $c=>$m){if($m && $m != ""){$f2 = 1;}else{unset($cms[$c]);}}}
					if($f1 && $f2 && count($cms)){addEdit_thct_test(array_merge($cmds, $cms));}
				}
			}
			else {addSuggestion($ovd);}
		}
	}
}
function thcClaimMail($clm){
	$to = $clm[email];
	$p = get_post($clm[post]);
	$sub = "Approval for your claimed business on ".get_bloginfo('name');
	$from = get_option('admin_email');
	$head = "MIME-Version: 1.0\r\n";
	$head .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$head .= "From: ".get_bloginfo('name')." <$from>\r\n";
	$head .= "X-Mailer: PHP/".phpversion();
	$msg = "<html><body>";
	$msg .= "<p>Dear user,</p>";
	$msg .= "<p>Your claimed ".$p->post_type.' <a href="'.get_permalink($clm[post]).'">'.get_the_title($clm[post]).'</a> has now successfully assigned to you.</p>';
	$msg .= "<p>Thank you.</p>";
	$msg .= "</body></html>";
	@mail($to, $sub, $msg, $head);
}
function setClaimIDs($id=0, $a=1, $t=false){
	global $wpdb; $table = $wpdb->prefix.'posts';
	$sql = "SELECT `ID` FROM `$table` WHERE `post_parent`='$id'";
	$rs = $wpdb->get_results($sql);
	$u['post_author']=$a;
	if($rs){foreach($rs as $r){$u['ID'] = $r->ID; wp_update_post($u);}}
	$u['ID'] = $id; wp_update_post($u);
}
function thcClaims(){
	$m=0;
	if($_SERVER['REQUEST_METHOD']=="POST" && $_POST['clm']){
		$clm = $_POST['clm'];
		if(is_email($clm[email])){
			addEditClaim($clm);// $m = 0; //'<p align="center">Thank You.<br>We recieved your request. You\'ll recieve email confirmation on approval of your requesst.</p>';
			if($clm[status] == 1){
				$ee = email_exists($clm[email]);
				$ir = (is_integer($clm[reps]) && $clm[reps]>0 && user_can($clm[reps], 'rep'));
				if($ee){
					$p = get_post($clm[post]);
					$u = new WP_User($ee);
					if($p->post_type=='dispensary'){$u->add_role('dispensary'); setClaimIDs($clm[post], $ee, 'dispensary'); if($ir){update_post_meta($clm[post], 'representative', $clm[reps]);}}
					//if($p->post_type=='clinic'){$u->add_role('doctor');}
					//if($p->post_type=='lab'){$u->add_role('lab');}
					thcClaimMail($clm);
					$m = 3;
				} else {
					$ee = register_new_user($clm[email], $clm[email]);
					if(!is_wp_error($ee)){
					$p = get_post($clm[post]);
					$u = new WP_User($ee);
					if($p->post_type=='dispensary'){$u->add_role('dispensary'); setClaimIDs($clm[post], $ee, 'dispensary'); if($ir){update_post_meta($clm[post], 'representative', $clm[reps]);}}
					//if($p->post_type=='clinic'){$u->add_role('doctor');}
					//if($p->post_type=='lab'){$u->add_role('lab');}
					thcClaimMail($clm);
					$m = 3;
					} else {$m = 2;}
				}
			}
		} else {$m = 1;}
	}
	return $m;
}
function can_claim($p){
	if($p->post_type=='dispensary'){
	if(user_can($p->post_author, 'dispensary')){return false;}
	else{return true;}
	}
	elseif($_POST['clm']){return true;}
	else{return true;}
}
/***********************************************************
AFTER REVISION
***********************************************************/
function addEdit_thct_price($data){
	global $wpdb; $table = $wpdb->prefix."thct_prices";
	$wpdb->replace($table, $data);
}
function get_thct_price($id=0, $col='pID'){
	global $wpdb; $table = $wpdb->prefix."thct_prices";
	$sql = "SELECT * FROM `$table` WHERE `$col`='$id'";
	return $wpdb->get_row($sql);
}
function delete_thct_price($id=0, $col='pID'){
	global $wpdb; $table = $wpdb->prefix."thct_prices";
	$wpdb->delete($table, array($col=>$id));
}

function addEdit_thct_location($data){
	global $wpdb; $table = $wpdb->prefix."thct_locations";
	$wpdb->replace($table, $data);
}
function get_thct_location($id=0, $col='pID'){
	global $wpdb; $table = $wpdb->prefix."thct_locations";
	$sql = "SELECT * FROM `$table` WHERE `$col`='$id'";
	return $wpdb->get_row($sql);
}
function delete_thct_location($id=0, $col='pID'){
	global $wpdb; $table = $wpdb->prefix."thct_locations";
	$wpdb->delete($table, array($col=>$id));
}

function add_thct_rating($data){
	global $wpdb; $table = $wpdb->prefix."thct_ratings";
	if($data[type]=='potency' && !isset($data[val])){
		$sql = "SELECT COUNT(*) FROM `$table` WHERE `post`='".$data[post]."' AND `key`='potency'";
		$i = $wpdb->get_var($sql);
		if(!$i){$wpdb->insert($table, $data);}
	} else {$wpdb->insert($table, $data);}
}
function get_thct_rating($id=0, $type='', $key='', $col='post'){
	global $wpdb; $table = $wpdb->prefix."thct_ratings";
	$sql = "SELECT AVG(`val`) FROM `$table` WHERE `$col`='$id' AND `type`='$type' AND `key`='$key'";
	return $wpdb->get_var($sql);
}
function update_thct_rating($data, $whr){
	global $wpdb; $table = $wpdb->prefix."thct_ratings";
	return $wpdb->update($table, $data, $whr);
}
function delete_thct_rating($id=0, $type='', $key='', $col='post'){
	global $wpdb; $table = $wpdb->prefix."thct_ratings";
	$whr = array($col=>$id);
	if($type){$whr['type'] = $type;}
	if($key){$whr['key'] = $key;}
	$wpdb->delete($table, $whr);
}
function get_thct_medis($id=0, $lim=0, $col='post'){
	global $wpdb; $table = $wpdb->prefix."thct_ratings"; $r = array();
	$sql = "SELECT `key` FROM `$table` WHERE `$col`='$id' AND `type`='_medi-' GROUP BY `key` ORDER BY AVG(`val`) DESC".(($lim)? " LIMIT ".$lim:"");
	$rs = $wpdb->get_results($sql, ARRAY_N);
	if(is_array($rs) && !empty($rs)){foreach($rs as $v){$r = array_merge($r, $v);}}
	return $r;
}
function get_thct_flavs($id=0, $lim=0, $col='post'){
	global $wpdb; $table = $wpdb->prefix."thct_ratings"; $r = array();
	$sql = "SELECT `key` FROM `$table` WHERE `$col`='$id' AND `type`='_flav-' GROUP BY `key` ORDER BY AVG(`val`) DESC".(($lim)? " LIMIT ".$lim:"");
	$rs = $wpdb->get_results($sql, ARRAY_N);
	if(is_array($rs) && !empty($rs)){foreach($rs as $v){$r = array_merge($r, $v);}}
	return $r;
}

function addEdit_thct_phenotype($data){
	global $wpdb; $table = $wpdb->prefix."thct_phenotypes";
	$wpdb->replace($table, $data);
}
function get_thct_phenotype($id=0, $col='ID'){
	global $wpdb; $table = $wpdb->prefix."thct_phenotypes";
	$sql = "SELECT * FROM `$table` WHERE `$col`='$id'";
	return $wpdb->get_row($sql);
}
function delete_thct_phenotype($id=0, $col='ID'){
	global $wpdb; $table = $wpdb->prefix."thct_phenotypes";
	$wpdb->delete($table, array($col=>$id));
}
function get_thct_phenotypes($id=0, $col='strain'){
	global $wpdb; $table = $wpdb->prefix."thct_phenotypes";
	$sql = "SELECT * FROM `$table` WHERE `$col`='$id'";
	return $wpdb->get_results($sql);
}

function addEdit_thct_test($data){
	global $wpdb; $table = $wpdb->prefix."thct_tests";
	$wpdb->replace($table, $data);
}
function update_thct_test($data, $col='ID'){
	global $wpdb; $table = $wpdb->prefix."thct_tests";
	if($data[$col]){$wpdb->update($table, $data, array($col=>$data[$col]));}
}
function get_thct_test($id=0, $st=-1, $col='pID'){
	global $wpdb; $table = $wpdb->prefix."thct_tests";
	$sql = "SELECT * FROM `$table` WHERE `$col`='$id'".(($st>=0)? " AND `status`='$st'":"");
	return $wpdb->get_row($sql);
}
function delete_thct_test($id=0, $col='pID'){
	global $wpdb; $table = $wpdb->prefix."thct_tests";
	$wpdb->delete($table, array($col=>$id));
}
function get_thct_tests($id=0, $st=-1, $col='pID'){
	global $wpdb; $table = $wpdb->prefix."thct_tests";
	$sql = "SELECT * FROM `$table` WHERE `$col`='$id'".(($st>=0)? " AND `status`='$st'":"");
	return $wpdb->get_results($sql);
}
function get_thct_tests_avg($id=0, $tst='THC', $pid=0, $col='pID'){
	global $wpdb; $table = $wpdb->prefix."thct_tests";
	$sql = "SELECT AVG(`$tst`) AS `ratio`, COUNT(`$tst`) AS `total` FROM `$table` WHERE `$col`='$id' AND `status`='1'".(($pid)? " AND `phenID`='$pid'":"");
	return $wpdb->get_row($sql);
}
function the_thct_tests_avg($id=0, $pid=0, $tst='THC', $col='pID'){
	if(get_thct_tests($pid, 1, 'phenID')){
	$r = get_thct_tests_avg($id, $tst, $pid, $col);
	if($r && $r->total){echo '<p><strong>'.$tst.' :</strong> '.$r->ratio.'% from '.$r->total.' tests</p>';}
	else{echo '<p><strong>'.$tst.' :</strong> - </p>';}
	}
}
function thc_get_testeds($id=0){
	$t = get_thct_tests($id, 1);
	if($t && $t[0]->THC){$r[]=array('key'=>'THC', 'val'=> $t[0]->THC);}
	if($t && $t[0]->CBD){$r[]=array('key'=>'CBD', 'val'=> $t[0]->CBD);}
	if($t && $t[0]->CBN){$r[]=array('key'=>'CBN', 'val'=> $t[0]->CBN);}
	return $r;
}
function get_social_links($id=0, $e=false){
	$socials = get_post_meta($id, '_all_socials', true);
	$title = get_the_title($id);
	if(is_array($socials) && !empty($socials)){
	$html = '<p class="th-social">';
	foreach($socials as $k=>$v){
	$t = (($k=='gplus')? $title.' on Google+':$title.' on '.ucwords($k));
	$html .= '<a href="'.$v.'" title="'.$t.'"><img src="'.THCP_URL.'/images/social/'.$k.'.png" alt="'.$t.'"></a>';
	}
	$html .= '</p>';
	}
	if($e){echo $html;} else{return $html;}
}
function get_special_offer($id=0, $e=false){
	$offer = get_post_meta($id, '_all_offers', true);
	$html = $c = '';
	if(is_array($offer) && $offer[active]){
		if(!$offer[content]){$c .=' no-content';}
		if(!$offer[title]){$c .=' no-title';}
		if(!$offer[price]){$c .=' no-price';}
		if(!$offer[image] || empty($offer[image])){$c .=' no-image';}
		if($offer[image] && !empty($offer[image])){foreach($offer[image] as $k=>$v){$i = $k;}}
		$i = wp_get_attachment_image_src($i, 'medium');
		
		$html = '<div class="offer-wrap clearfix'.$c.'">';
		if($i[0]){
		$html .= '<div class="image-wrap">';
		$html .= '<img src="'.$i[0].'" alt="'.$offer[title].'" class="image">';
		$html .= '</div>';
		}
		$html .= '<div class="price-wrap">';
		$html .= '<p class="price">'.$offer[price].'</p>';
		$html .= '</div>';
		$html .= '<div class="off-wrap">';
		$html .= '<h2>'.$offer[title].'</h2>';
		$html .= $offer[content];
		$html .= '</div>';
	}
	if($e){echo $html;} else{return $html;}
}
function get_all_data($id){
	$p = get_post($id); $r = array();
	if($p->post_type=="dispensary"){
		$r[type] = get_post_meta($id, 'type', true);
		$r[phone] = get_post_meta($id, 'phone', true);
		$r[email] = get_post_meta($id, 'email', true);
		$r[address] = get_post_meta($id, 'address', true);
		$r[latitude] = get_post_meta($id, 'latitude', true);
		$r[longitude] = get_post_meta($id, 'longitude', true);
	}
	elseif($p->post_type=="strain") {
		$r[aka_name] = get_post_meta($id, 'aka_name', true);
		$r[type] = get_post_meta($id, 'type', true);
		$r[father] = get_post_meta($id, 'father', true);
		$r[mother] = get_post_meta($id, 'mother', true);
		$r[inyield] = get_post_meta($id, 'inyield', true);
		$r[outyield] = get_post_meta($id, 'outyield', true);
		$r[inflower] = get_post_meta($id, 'inflower', true);
		$r[outhervest] = get_post_meta($id, 'outhervest', true);
		$r[award] = get_post_meta($id, 'award', true);
		$r[award2] = get_post_meta($id, 'award2', true);
		$r[award3] = get_post_meta($id, 'award3', true);
		$r[award4] = get_post_meta($id, 'award4', true);
		$r[award5] = get_post_meta($id, 'award5', true);
	}
	else {$r = get_post_meta($id, '_all_datas', true);}
	return $r;
}
add_action( 'init', 'thc_user_action');
function thc_user_action(){
	global $msg;
	if($_SERVER['REQUEST_METHOD'] == "GET" && $_GET["tact"] == "out"){
		wp_logout();
		wp_safe_redirect(get_site_url());
		exit;
	}
	if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST["tact"] == "login"){
		$creds = array("user_login"=>esc_sql($_POST["ulogin"]), "user_password"=>esc_sql($_POST["upass"]));
		$user = wp_signon( $creds, false );
		if (is_wp_error($user)) {
			$msg = '<strong>ERROR:</strong> Invalid username or password. <a href="'.get_site_url('','/wp-login.php?action=lostpassword').'">Lost your password?</a>';
		} else {
			wp_safe_redirect(get_site_url());
			exit;
		}
	}
	if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST["tact"] == "register"){
		$creds = array("user_login"=>esc_sql($_POST["ulogin"]), "user_email"=>esc_sql($_POST["ulogin"]), "user_password"=>esc_sql($_POST["upass"]), "user_nicename"=>esc_sql($_POST["uname"]), "display_name"=>esc_sql($_POST["uname"]), "role"=>esc_sql($_POST["urole"]));
		$user = wp_insert_user($creds);
		if (is_wp_error($user)) {
			$msg = $user->get_error_message();
		} else {
			$msg = $user;
		}
	}
	if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST["tact"] == "profile"){
		$creds = array("ID"=>esc_sql($_POST["uid"]), "user_nicename"=>esc_sql($_POST["uname"]), "display_name"=>esc_sql($_POST["uname"]));
		if(!empty($_POST["upass"])){$creds["user_password"]=esc_sql($_POST["upass"]);}
		$user = wp_update_user($creds);
		if (is_wp_error($user)) {
			$msg = $user->get_error_message();
		} else {
			$msg = $user;
		}
	}
}
add_shortcode('THCLOGIN', 'thc_loginPage');
function thc_loginPage(){
	global $thcp; global $msg;
	$uid = get_current_user_id();
	?>
	<form action="" method="post">
		<?php if($msg){echo '<p>'.$msg.'</p>';}?>
		<?php if($uid>0){echo '<h2 align="center">You are already registered and logged in.</h2>';} else {?>
		<p><label class="th-label-s">Username</label> : <input type="text" name="ulogin" value=""></p>
		<p><label class="th-label-s">Password</label> : <input type="password" name="upass" value=""></p>
		<p><input type="submit" value="Login" class="btn"></p>
		<input type="hidden" name="tact" value="login">
		<p>Not a member yet? <a href="<?php echo get_permalink($thcp[signup]);?>">Sign Up</a> here.</p>
		<?php }?>
	</form>
	<?php
}
add_shortcode('THCREGISTER', 'thc_registerPage');
function thc_registerPage(){
	global $thcp; global $msg;
	$uid = get_current_user_id();
	?>
	<form action="" method="post">
		<?php if($msg && !is_numeric($msg)){echo '<p>'.$msg.'</p>';}?>
		<?php if($msg && is_numeric($msg)){echo '<p>You are successfully registered. Please <a href="'.get_permalink($thcp[login]).'">Log In</a> here.</p>';}?>
		<?php if($uid>0) {echo '<h2 align="center">You are already registered and logged in.</h2>';}?>
		<?php if(!$uid && !is_numeric($msg)) {?>
		<p><label class="th-label-s">Email</label> : <input type="text" name="ulogin" value=""></p>
		<p><label class="th-label-s">Password</label> : <input type="password" name="upass" value=""></p>
		<p><label class="th-label-s">Name</label> : <input type="text" name="uname" value=""></p>
		<p><label class="th-label-s">Role</label> : <select name="urole"><option value="subscriber">Patient</option><option value="dispensary">Dispensary</option><option value="seeder">Seeder</option><option value="grower">Grower</option><option value="breeder">Breeder</option><option value="lab">Laboratory</option><option value="doctor">Doctor</option><option value="rep">Representative</option></select></p>
		<p><input type="submit" value="Sign Up" class="btn"></p>
		<input type="hidden" name="tact" value="register">
		<p>Already a member? <a href="<?php echo get_permalink($thcp[login]);?>">Log In</a> here.</p>
		<?php }?>
	</form>
	<?php
}
add_shortcode('THCPROFILE', 'thc_profilePage');
function thc_profilePage(){
	global $thcp; global $msg;
	$user = wp_get_current_user();
	?>
	<form action="" method="post">
		<?php if($msg && !is_numeric($msg)){echo '<p>'.$msg.'</p>';}?>
		<?php if($msg && is_numeric($msg)){echo '<p>Profile updated successfully.</p>';}?>
		<?php if($user && $user->ID>0){?>
		<input type="hidden" name="uid" value="<?php echo $user->ID;?>">
		<p><label class="th-label-s">Email</label> : <input type="text" name="ulogin" value="<?php echo $user->user_email;?>" disabled></p>
		<p><label class="th-label-s">Password</label> : <input type="password" name="upass" value=""></p>
		<p><label class="th-label-s">Name</label> : <input type="text" name="uname" value="<?php echo $user->display_name;?>"></p>
		<p><label class="th-label-s">Role</label> : <select name="urole" disabled><option value="administrator" <?php if(in_array("administrator", $user->roles)){echo 'selected';}?>>Admin</option><option value="subscriber" <?php if(in_array("subscriber", $user->roles)){echo 'selected';}?>>Patient</option><option value="dispensary" <?php if(in_array("dispensary", $user->roles)){echo 'selected';}?>>Dispensary</option><option value="seeder" <?php if(in_array("seeder", $user->roles)){echo 'selected';}?>>Seeder</option><option value="grower" <?php if(in_array("grower", $user->roles)){echo 'selected';}?>>Grower</option><option value="breeder" <?php if(in_array("breeder", $user->roles)){echo 'selected';}?>>Breeder</option><option value="lab" <?php if(in_array("lab", $user->roles)){echo 'selected';}?>>Laboratory</option><option value="doctor" <?php if(in_array("doctor", $user->roles)){echo 'selected';}?>>Doctor</option><option value="rep" <?php if(in_array("rep", $user->roles)){echo 'selected';}?>>Representative</option></select></p>
		<p><label class="th-label-s">Address</label> : <input type="text" name="address" id="autocomplete" value="<?php echo esc_attr( get_the_author_meta( 'address', $user->ID ) ); ?>"></p>
		<p><label class="th-label-s">Latitude</label> : <input type="text" name="latitude" id="latitude" value="<?php echo esc_attr( get_the_author_meta( 'latitude', $user->ID ) ); ?>" ></p>
		<p><label class="th-label-s">Longitude</label> : <input type="text" name="longitude" id="longitude" value="<?php echo esc_attr( get_the_author_meta( 'longitude', $user->ID ) ); ?>"></p>
		<?php
		if($user && $user->ID){$meds = get_the_author_meta( '_all_medicinals', $user->ID );}
		if(!is_array($meds)){$meds=array();}
		$ma = get_option('all_medicals');
		echo '<p><label class="th-label">Medical Conditions</label> : <select multiple name="umcons" class="chosen" data-placeholder="Medical Conditions" style="width:250px;">';
		if($ma){foreach($ma as $m){echo '<option value="'.$m.'" '.((in_array($m, $meds))? 'selected':'').'>'.$m.'</option>';}}
		echo '</select></p>';
		echo '<script>jQuery(".chosen").each(function(){jQuery(this).chosen({allow_single_deselect:true});jQuery(this).before(\'<input type="hidden" name="\'+jQuery(this).attr("name")+\'">\').change(function(){jQuery(this).prev().val(jQuery(this).val());}).removeAttr("name");jQuery(this).prev().val(jQuery(this).val());}); jQuery(\'.chosen\').live(\'chosen:updated\', function(event){jQuery(this).prev().val(\'\');});</script>';
		?>
		<p><input type="submit" value="Update" class="btn"></p>
		<input type="hidden" name="tact" value="profile">
	<script>
	var autocomplete;
	function initAutocomplete() {
		autocomplete = new google.maps.places.Autocomplete((document.getElementById("autocomplete")), {types: ["geocode"]});
		autocomplete.addListener("place_changed", fillInAddress);
	}
	function fillInAddress() {
		var place = autocomplete.getPlace();
		document.getElementById("latitude").value = place.geometry.location.lat();
		document.getElementById("longitude").value = place.geometry.location.lng();
	}
	</script>
	<script src="http://maps.google.com/maps/api/js?sensor=false&libraries=places&callback=initAutocomplete"></script>
	<?php } else {
		echo 'You are not registerd or logged in. Please <a href="'.get_permalink($thcp[login]).'">Log In</a> or <a href="'.get_permalink($thcp[signup]).'">Sign Up</a> here.';
	}?>
	</form>
	<?php
}
?>