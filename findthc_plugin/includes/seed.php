<?php
add_action('init', 'register_seed_type');
function register_seed_type() {
	$labels = array(
		'name'               => _x( 'Seeds', 'post type general name', 'thcPlugin' ),
		'singular_name'      => _x( 'Seed', 'post type singular name', 'thcPlugin' ),
		'menu_name'          => _x( 'Seeds', 'admin menu', 'thcPlugin' ),
		'name_admin_bar'     => _x( 'Seed', 'add new on admin bar', 'thcPlugin' ),
		'add_new'            => _x( 'Add New', 'seed', 'thcPlugin' ),
		'add_new_item'       => __( 'Add New Seed', 'thcPlugin' ),
		'new_item'           => __( 'New Seed', 'thcPlugin' ),
		'edit_item'          => __( 'Edit Seed', 'thcPlugin' ),
		'view_item'          => __( 'View Seed', 'thcPlugin' ),
		'all_items'          => __( 'All Seeds', 'thcPlugin' ),
		'search_items'       => __( 'Search Seeds', 'thcPlugin' ),
		'parent_item_colon'  => __( 'Parent Seeds:', 'thcPlugin' ),
		'not_found'          => __( 'No seeds found.', 'thcPlugin' ),
		'not_found_in_trash' => __( 'No seeds found in Trash.', 'thcPlugin' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'thcPlugin' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'seed' ),
		'capability_type'    => 'seed',
		'map_meta_cap'       => true,
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title'), //, 'thumbnail',
	);

	register_post_type( 'seed', $args );
}

add_action( 'add_meta_boxes_seed', 'seed_meta_boxes' );
function seed_meta_boxes(){
	add_meta_box('seed-data-box', 'Seed Pack Price $ URL', 'seed_data_box_html', 'seed', $context='advanced', $priority='default', $callback_args=null);
}
function seed_data_box_html($post){
	$args = array(
		'posts_per_page'   => -1,
		'orderby'          => 'title',
		'order'            => 'DESC',
		'exclude'          => $post->ID,
		'post_type'        => 'strain',
		'post_status'      => 'publish',
		'extra'      	   => 'allauthors',
	);
	$sts = get_posts( $args );
	if($post && $post->ID){$pdata = get_thct_price($post->ID); $udata = get_post_meta($post->ID, '_all_urls', true); $sd = get_post_meta($post->ID, 'strain_id', true);}
	
	global $wpdb;
	if(current_user_can('administrator')){
		$blog_id = get_current_blog_id();
		$roles = array('administrator', 'seeder');
		$meta_query = array('key' => $wpdb->get_blog_prefix($blog_id) . 'capabilities', 'value' => '"(' . implode('|', array_map('preg_quote', $roles)) . ')"', 'compare' => 'REGEXP');
		$user_query = new WP_User_Query(array('meta_query' => array($meta_query)));
		$users = $user_query->get_results();
		$uc = get_userdata(get_current_user_id());
		echo '<p><label class="th-label">Owner</label>: <select class="combo" name="post_author_override">';
		if($users){foreach($users as $u){echo '<option value="'.$u->ID.'" '.selected($u->ID, $post->post_author, false).'>'.$u->display_name.'</option>';}}
		echo '</select></p>';
	}

	$so1 = '<select name="strain_id" class="chosen" data-placeholder="Select Strain" style="width:250px;"><option value="">Select Strain</option>';
	if($sts){foreach($sts as $s){$so1 .= '<option value="'.$s->ID.'" '.selected($s->ID, $sd, false).'>'.$s->post_title.'</option>';}}
	$so1 .= '</select>';
	
	echo '<p><label class="th-label">Seed of Strain</label>: '.$so1.'</p>';
	echo '<p><label class="th-label-s">1 x</label>: $ <input type="number" name="pdata[p1xR]" value="'.$pdata->p1xR.'" size="6" min="0" step="0.01"><label class="th-label-s"></label>URL : <input type="text" name="udata[1]" value="'.$udata[1].'" min="0"></p>';
	echo '<p><label class="th-label-s">5 x</label>: $ <input type="number" name="pdata[p5xR]" value="'.$pdata->p5xR.'" size="6" min="0" step="0.01"><label class="th-label-s"></label>URL : <input type="text" name="udata[5]" value="'.$udata[5].'" min="0"></p>';
	echo '<p><label class="th-label-s">10 x</label>: $ <input type="number" name="pdata[p10xR]" value="'.$pdata->p10xR.'" size="6" min="0" step="0.01"><label class="th-label-s"></label>URL : <input type="text" name="udata[10]" value="'.$udata[10].'" min="0"></p>';
	echo '<p><label class="th-label-s">20 x</label>: $ <input type="number" name="pdata[p20xR]" value="'.$pdata->p20xR.'" size="6" min="0" step="0.01"><label class="th-label-s"></label>URL : <input type="text" name="udata[20]" value="'.$udata[20].'" min="0"></p>';
	echo '<p><label class="th-label-s">30 x</label>: $ <input type="number" name="pdata[p30xR]" value="'.$pdata->p30xR.'" size="6" min="0" step="0.01"><label class="th-label-s"></label>URL : <input type="text" name="udata[30]" value="'.$udata[30].'" min="0"></p>';
	echo '<p><label class="th-label-s">40 x</label>: $ <input type="number" name="pdata[p40xR]" value="'.$pdata->p40xR.'" size="6" min="0" step="0.01"><label class="th-label-s"></label>URL : <input type="text" name="udata[40]" value="'.$udata[40].'" min="0"></p>';
	echo '<p><label class="th-label-s">50 x</label>: $ <input type="number" name="pdata[p50xR]" value="'.$pdata->p50xR.'" size="6" min="0" step="0.01"><label class="th-label-s"></label>URL : <input type="text" name="udata[50]" value="'.$udata[50].'" min="0"></p>';
	echo '<p><label class="th-label-s">100 x</label>: $ <input type="number" name="pdata[p100xR]" value="'.$pdata->p100xR.'" size="6" min="0" step="0.01"><label class="th-label-s"></label>URL : <input type="text" name="udata[100]" value="'.$udata[100].'" min="0"></p>';
	echo '<script>jQuery(".chosen").each(function(){jQuery(this).chosen({allow_single_deselect:true});jQuery(this).before(\'<input type="hidden" name="\'+jQuery(this).attr("name")+\'">\').change(function(){jQuery(this).prev().val(jQuery(this).val());}).removeAttr("name");jQuery(this).prev().val(jQuery(this).val());}); jQuery(\'.chosen\').live(\'chosen:updated\', function(event){jQuery(this).prev().val(\'\');});</script>';
}

add_action('save_post', 'seed_save_post', 10, 2);
function seed_save_post($pid, $post){
	if($post->post_type == 'seed'){
		$pts = $uts = array();
		$ptss = $_POST['pdata']; if(is_array($ptss)){$pts = $ptss;}
		$utss = $_POST['udata']; if(is_array($utss)){$uts = $utss;}
		$sid = $_POST['strain_id'];
		
		if($pts){addEdit_thct_price(array_merge($pts, array('pID'=>$pid, 'strain'=>$sid, 'pType'=>$post->post_type, 'price'=>$pts[p10xR])));}
		foreach($uts as $k=>$v){
			if(!(strpos($v, 'http://')===0 || strpos($v, 'https://')===0)){$uts[$k] = 'http://'.$v;}
		}
		update_post_meta($pid, '_all_urls', $uts);
		update_post_meta($pid, 'strain_id', $sid);
	}
}
add_action( 'delete_post', 'seed_delete' );
function seed_delete($pid){
	$post = get_post($pid);
	if ( $post->post_type != 'seed' ) return;
	delete_thct_price($pid);
}

?>