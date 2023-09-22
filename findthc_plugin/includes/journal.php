<?php
add_action('init', 'register_journal_type');
function register_journal_type() {
	$labels = array(
		'name'               => _x( 'Grow Journals', 'post type general name', 'thcPlugin' ),
		'singular_name'      => _x( 'Grow Journal', 'post type singular name', 'thcPlugin' ),
		'menu_name'          => _x( 'Grow Journals', 'admin menu', 'thcPlugin' ),
		'name_admin_bar'     => _x( 'Grow Journal', 'add new on admin bar', 'thcPlugin' ),
		'add_new'            => _x( 'Add New', 'journal', 'thcPlugin' ),
		'add_new_item'       => __( 'Add New Journal', 'thcPlugin' ),
		'new_item'           => __( 'New Journal', 'thcPlugin' ),
		'edit_item'          => __( 'Edit Journal', 'thcPlugin' ),
		'view_item'          => __( 'View Journal', 'thcPlugin' ),
		'all_items'          => __( 'All Journals', 'thcPlugin' ),
		'search_items'       => __( 'Search Journals', 'thcPlugin' ),
		'parent_item_colon'  => __( 'Parent Journals:', 'thcPlugin' ),
		'not_found'          => __( 'No journals found.', 'thcPlugin' ),
		'not_found_in_trash' => __( 'No journals found in Trash.', 'thcPlugin' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'thcPlugin' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'journal' ),
		'capability_type'    => 'journal',
		'map_meta_cap'       => true,
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title', 'editor'), //, 'thumbnail',
	);

	register_post_type( 'journal', $args );
}

add_action( 'add_meta_boxes_journal', 'journal_meta_boxes' );
function journal_meta_boxes(){
	add_meta_box('journal-data-box', 'Journal Data', 'journal_data_box_html', 'journal', $context='advanced', $priority='default', $callback_args=null);
	add_meta_box('journal-chems-box', 'Chemotypes', 'journal_chems_box_html', 'journal', $context='advanced', $priority='default', $callback_args=null);
}
function journal_data_box_html($post){
	$args = array(
		'posts_per_page'   => -1,
		'orderby'          => 'title',
		'order'            => 'DESC',
		//'exclude'          => $post->ID,
		'post_type'        => 'strain',
		'post_status'      => 'publish',
	);
	$sts = get_posts( $args );
	
	global $wpdb;
	if(current_user_can('administrator')){
		$blog_id = get_current_blog_id();
		$roles = array('administrator', 'grower', 'breeder');
		$meta_query = array('key' => $wpdb->get_blog_prefix($blog_id) . 'capabilities', 'value' => '"(' . implode('|', array_map('preg_quote', $roles)) . ')"', 'compare' => 'REGEXP');
		$user_query = new WP_User_Query(array('meta_query' => array($meta_query)));
		$users = $user_query->get_results();
		$uc = get_userdata(get_current_user_id());
		echo '<p><label class="th-label">Owner</label>: <select class="combo" name="post_author_override">';
		if($users){foreach($users as $u){echo '<option value="'.$u->ID.'" '.selected($u->ID, $post->post_author, false).'>'.$u->display_name.'</option>';}}
		echo '</select></p>';
	}

	
	if($post && $post->ID){$fdata = get_all_data($post->ID); $sd = get_post_meta($post->ID, 'strain_id', true);}
	$so1 = '<select name="fdata[strain_id]" class="chosen" data-placeholder="Unknown" style="width:250px;"><option value="0">Unknown</option>';
	if($sts){foreach($sts as $s){$so1 .= '<option value="'.$s->ID.'" '.selected($s->ID, $sd, false).'>'.$s->post_title.'</option>';}}
	$so1 .= '</select>';
	
	echo '<p><label class="th-label">Grown Strain</label>: '.$so1.'</p>';
	echo '<hr>';
	echo '<p><label class="th-label">Location</label>: <select name="fdata[location]"><option value="Indoor" '.selected("Indoor", $fdata['location'], false).'>Indoor</option><option value="Outdoor" '.selected("Outdoor", $fdata['location'], false).'>Outdoor</option></select></p>';
	
	echo '<p><label class="th-label">Medium</label>: <select name="fdata[medium]"><option value="Soil" '.selected("Soil", $fdata[medium], false).'>Soil</option><option value="Coco Coir" '.selected("Coco Coir", $fdata[medium], false).'>Coco Coir</option><option value="Rockwool" '.selected("Rockwool", $fdata[medium], false).'>Rockwool</option><option value="Clay Pebbles" '.selected("Clay Pebbles", $fdata[medium], false).'>Clay Pebbles</option></select></p>';
	
	echo '<p><label class="th-label">Nutrient</label>: <select name="fdata[nutrient]"><option value="Organic" '.selected("Organic", $fdata[nutrient], false).'>Organic</option><option value="Natural" '.selected("Natural", $fdata[nutrient], false).'>Natural</option><option value="Synthetic" '.selected("Synthetic", $fdata[nutrient], false).'>Synthetic</option></select></p>';
	
	echo '<p><label class="th-label">System</label>: <select name="fdata[gsystem]"><option value="Hand Water" '.selected("Hand Water", $fdata[gsystem], false).'>Hand Water</option><option value="Drip" '.selected("Drip", $fdata[gsystem], false).'>Drip</option><option value="Dwc" '.selected("Dwc", $fdata[gsystem], false).'>Dwc</option><option value="Flood & Drain" '.selected("Flood & Drain", $fdata[gsystem], false).'>Flood & Drain</option><option value="Aeroponics" '.selected("Aeroponics", $fdata[gsystem], false).'>Aeroponics</option><option value="Aquaponics" '.selected("Aquaponics", $fdata[gsystem], false).'>Aquaponics</option></select></p>';
	
	echo '<p><label class="th-label">Flowering in</label>: <input type="number" name="fdata[flowering]" value="'.$fdata[flowering].'" size="6" min="0"> days</p>';

	$w = str_replace(array('\"',"\'"), array('"',"'"), $fdata[distance]); $w = htmlspecialchars($w);
	$va = get_option('_phen-distance');
	if($va && is_array($va) && !empty($va)){{$vs = '<select name="fdata[distance]">'; foreach($va as $v){
		$v = str_replace(array('\"',"\'"), array('"',"'"), $v); $v = htmlspecialchars($v);
		$vs .= '<option value="'.$v.'" '.selected($v, $w, false).'>'.$v.'</option>';}$vs .= '</select>';
	}$vs .= '</select>';} else {
		$vs = '<input type="number" name="fdata[distance]" placeholder="Value" value="'.$w.'">';
	}
	echo '<p><label class="th-label">Nodal Distance</label>: '.$vs.'</p>';
	
	echo '<p><label class="th-label">Journal URL</label>: <input type="text" name="fdata[jlink]" value="'.$fdata[jlink].'"></p>';
	echo '<script>jQuery(".chosen").each(function(){jQuery(this).chosen({allow_single_deselect:true});jQuery(this).before(\'<input type="hidden" name="\'+jQuery(this).attr("name")+\'">\').change(function(){jQuery(this).prev().val(jQuery(this).val());}).removeAttr("name");jQuery(this).prev().val(jQuery(this).val());}); jQuery(\'.chosen\').live(\'chosen:updated\', function(event){jQuery(this).prev().val(\'\');});</script>';
}
function journal_chems_box_html($post){
	$args = array(
		'posts_per_page'   => -1,
		'orderby'          => 'title',
		'order'            => 'DESC',
		'post_type'        => 'lab',
		'post_status'      => 'publish',
		'extra'      	   => 'allauthors',
	);
	$labs = get_posts( $args );
	$uid = get_current_user_id();
	
	if($post && $post->ID){$chemsd = get_thct_test($post->ID);}

	if($labs){
		if($chemsd && $chemsd->ID){echo '<input type="hidden" name="chemsd[ID]" value="'.$chemsd->ID.'">';}
		echo '<input type="hidden" name="chemsd[pID]" value="'.$post->ID.'">';
		echo '<input type="hidden" name="chemsd[voter]" value="'.$uid.'">';
		echo '<input type="hidden" name="chemsd[type]" value="jurno">';
		echo '<p><label class="th-label">Laboratory</label>: <select class="combo" name="chemsd[lab]"><option value="0">Unknown</option>';
		if($labs){foreach($labs as $l){echo '<option value="'.$l->ID.'" '.selected($l->ID, $chemsd->lab, false).'>'.$l->post_title.'</option>';}}
		echo '</select></p>';
		echo '<p><label class="th-label">THC</label>: <input type="number" name="chems[THC]" value="'.(($chemsd->THC)? $chemsd->THC:'').'" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">CBD</label>: <input type="number" name="chems[CBD]" value="'.(($chemsd->CBD)? $chemsd->CBD:'').'" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">CBN</label>: <input type="number" name="chems[CBN]" value="'.(($chemsd->CBN)? $chemsd->CBN:'').'" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">CBG</label>: <input type="number" name="chems[CBG]" value="'.(($chemsd->CBG)? $chemsd->CBG:'').'" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">CBC</label>: <input type="number" name="chems[CBC]" value="'.(($chemsd->CBC)? $chemsd->CBC:'').'" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">Limonene</label>: <input type="number" name="chems[Limonene]" value="'.(($chemsd->Limonene)? $chemsd->Limonene:'').'" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">Myrcene</label>: <input type="number" name="chems[Myrcene]" value="'.(($chemsd->Myrcene)? $chemsd->Myrcene:'').'" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">Pinene</label>: <input type="number" name="chems[Pinene]" value="'.(($chemsd->Pinene)? $chemsd->Pinene:'').'" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">Linalool</label>: <input type="number" name="chems[Linalool]" value="'.(($chemsd->Linalool)? $chemsd->Linalool:'').'" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">B-Caryophyllene</label>: <input type="number" name="chems[BCaryophyllene]" value="'.(($chemsd->BCaryophyllene)? $chemsd->BCaryophyllene:'').'" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">Nerolidol</label>: <input type="number" name="chems[Nerolidol]" value="'.(($chemsd->Nerolidol)? $chemsd->Nerolidol:'').'" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">Phytol</label>: <input type="number" name="chems[Phytol]" value="'.(($chemsd->Phytol)? $chemsd->Phytol:'').'" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">Cineol</label>: <input type="number" name="chems[Cineol]" value="'.(($chemsd->Cineol)? $chemsd->Cineol:'').'" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">Humulene</label>: <input type="number" name="chems[Humulene]" value="'.(($chemsd->Humulene)? $chemsd->Humulene:'').'" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">Borneol</label>: <input type="number" name="chems[Borneol]" value="'.(($chemsd->Borneol)? $chemsd->Borneol:'').'" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">Terpinolene</label>: <input type="number" name="chems[Terpinolene]" value="'.(($chemsd->Terpinolene)? $chemsd->Terpinolene:'').'" max="100" min="0" step="0.01" size="6"> %';
		echo '<p>** Leave non tested chemotypes field BLANK, not ZERO.</p>';
		echo '<p>** Please check your values before saving, once added, you cannot change.</p>';
	}
}


add_action('save_post', 'journal_save_post', 10, 2);
function journal_save_post($pid, $post){
	if($post->post_type == 'journal'){
		$edts = get_all_data($pid);
		$dts = array();
		
		$fdatas = $_POST['fdata']; if(is_array($fdatas)){$dts = $fdatas;}
		update_post_meta($pid, '_all_datas', $dts);
		foreach($dts as $k=>$v){
			update_post_meta($pid, $k, $v);
		}

		$cmds = $_POST['chemsd'];
		$cms = $_POST['chems'];
		if($cmds && $cms){
			$f1 = 0; $f2 = 0; $st = 0; $cms1 = $cms;
			$cmds[strain] = $dts[strain_id];
			$ot = get_thct_test($pid);
			if($ot && $ot->status != 0){
				$st = $ot->status;
				foreach($cms as $k=>$v){if($ot->$k != $v){$st = 0;}}
			}
			$cmds[status] = $st;
			if(is_array($cmds) && $cmds[strain] && $cmds[lab] && $cmds[strain] != 0 && $cmds[lab] != 0){$f1 = 1;}
			if(is_array($cms1)){foreach($cms1 as $c=>$m){if($m && $m != ""){$f2 = 1;}else{unset($cms[$c]);}}}
			if($f1 && $f2 && count($cms)){addEdit_thct_test(array_merge($cmds, $cms));}
		}
	}
}
add_action( 'delete_post', 'journal_delete' );
function journal_delete($pid){
	$post = get_post($pid);
	if ( $post->post_type != 'journal' ) return;
	delete_thct_test($pid);
}
?>