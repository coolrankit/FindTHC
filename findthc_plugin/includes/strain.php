<?php
add_action('init', 'register_strain_type');
function register_strain_type() {
	$labels = array(
		'name'               => _x( 'Strains', 'post type general name', 'thcPlugin' ),
		'singular_name'      => _x( 'Strain', 'post type singular name', 'thcPlugin' ),
		'menu_name'          => _x( 'Strains', 'admin menu', 'thcPlugin' ),
		'name_admin_bar'     => _x( 'Strain', 'add new on admin bar', 'thcPlugin' ),
		'add_new'            => _x( 'Add New', 'strain', 'thcPlugin' ),
		'add_new_item'       => __( 'Add New Strain', 'thcPlugin' ),
		'new_item'           => __( 'New Strain', 'thcPlugin' ),
		'edit_item'          => __( 'Edit Strain', 'thcPlugin' ),
		'view_item'          => __( 'View Strain', 'thcPlugin' ),
		'all_items'          => __( 'All Strains', 'thcPlugin' ),
		'search_items'       => __( 'Search Strains', 'thcPlugin' ),
		'parent_item_colon'  => __( 'Parent Strains:', 'thcPlugin' ),
		'not_found'          => __( 'No strains found.', 'thcPlugin' ),
		'not_found_in_trash' => __( 'No strains found in Trash.', 'thcPlugin' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'thcPlugin' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'strain' ),
		'capability_type'    => 'strain',
		'map_meta_cap'       => true,
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'thumbnail',)
	);

	register_post_type( 'strain', $args );
}

add_action( 'add_meta_boxes_strain', 'strain_meta_boxes' );
function strain_meta_boxes(){
	add_meta_box('strain-data-box', 'Strain Data', 'strain_data_box_html', 'strain', $context='advanced', $priority='default', $callback_args=null);
	//add_meta_box('strain-medical-box', 'Strain Medicinal Value', 'strain_medical_box_html', 'strain', $context='advanced', $priority='default', $callback_args=null);
	//add_meta_box('strain-flavor-box', 'Strain Flavors', 'strain_flavor_box_html', 'strain', $context='advanced', $priority='default', $callback_args=null);
	add_meta_box('strain-sets-box', 'Phenotypes', 'strain_sets_box_html', 'strain', $context='advanced', $priority='default', $callback_args=null);
	add_meta_box('strain-chems-box', 'Chemotypes', 'strain_chems_box_html', 'strain', $context='advanced', $priority='default', $callback_args=null);
}
function strain_data_box_html($post){
	global $wpdb;
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
	
	if($post && $post->ID){$sdata = get_all_data($post->ID); $f = get_post_meta($post->ID, 'father', true); $m = get_post_meta($post->ID, 'mother', true);}
	$so1 = '<select name="sdata[father]" class="chosen" data-placeholder="Unknown" style="width:250px;"><option value="-1">Unknown</option><option value="0" '.selected($f, $post->ID, false).'>Same Strain</option>';
	if($sts){foreach($sts as $s){$so1 .= '<option value="'.$s->ID.'" '.selected($s->ID, $f, false).'>'.$s->post_title.'</option>';}}
	$so1 .= '</select>';

	$so2 = '<select name="sdata[mother]" class="chosen" data-placeholder="Unknown" style="width:250px;"><option value="-1">Unknown</option><option value="0" '.selected($m, $post->ID, false).'>Same Strain</option>';
	if($sts){foreach($sts as $s){$so2 .= '<option value="'.$s->ID.'" '.selected($s->ID, $m, false).'>'.$s->post_title.'</option>';}}
	$so2 .= '</select>';

	if(current_user_can('administrator')){
		$blog_id = get_current_blog_id();
		$roles = array('administrator', 'grower', 'breeder');
		$meta_query = array('key' => $wpdb->get_blog_prefix($blog_id) . 'capabilities', 'value' => '"(' . implode('|', array_map('preg_quote', $roles)) . ')"', 'compare' => 'REGEXP');
		$user_query = new WP_User_Query(array('meta_query' => array($meta_query)));
		$users = $user_query->get_results();
		$uc = get_userdata(get_current_user_id());
		echo '<p><label class="th-label">Grower/Breeder</label>: <select class="combo" name="post_author_override">';
		if($users){foreach($users as $u){echo '<option value="'.$u->ID.'" '.selected($u->ID, $post->post_author, false).'>'.$u->display_name.'</option>';}}
		echo '</select></p>';
	}

	echo '<p><label class="th-label">AKA Name</label>: <input type="text" name="sdata[aka_name]" value="'.$sdata[aka_name].'"></p>';
	echo '<p><label class="th-label">Type</label>: <select name="sdata[type]"><option value="sativa" '.selected("sativa", $sdata[type], false).'>Sativa</option><option value="indica" '.selected("indica", $sdata[type], false).'>Indica</option><option value="hybrid" '.selected("hybrid", $sdata[type], false).'>Hybrid</option></select></p>';
	echo '<p><label class="th-label">Parents</label>: '.$so1.' + '.$so2.'</p>';	
	echo '<hr>';	
	echo '<p><label class="th-label">Indoor Yield in</label>: <input type="number" name="sdata[inyield]" value="'.$sdata[inyield].'"> gm/Sq.M</p>';	
	echo '<p><label class="th-label">Outdoor Yield in</label>: <input type="number" name="sdata[outyield]" value="'.$sdata[outyield].'"> gm/Sq.M</p>';	
	echo '<p><label class="th-label">Indoor Flowering Time</label>: <input type="text" name="sdata[inflower]" value="'.$sdata[inflower].'"></p>';	
	echo '<p><label class="th-label">Outdoor Harvest Time</label>: <input type="text" name="sdata[outhervest]" value="'.$sdata[outhervest].'"></p>';	
	echo '<hr>';	
	echo '<p><label class="th-label">Award 1</label>: <input style="width:70px;" type="text" name="sdata[award][rank]" value="'.$sdata[award][rank].'" placeholder="i.e. 2nd"> in Category <select name="sdata[award][type]"><option value="sativa" '.selected("sativa", $sdata[award][type], false).'>Sativa</option><option value="indica" '.selected("indica", $sdata[award][type], false).'>Indica</option><option value="hybrid" '.selected("hybrid", $sdata[award][type], false).'>Hybrid</option></select> at Competetion <input style="width:100px;" type="text" name="sdata[award][name]" value="'.$sdata[award][name].'"> on Year <input style="width:70px;" type="number" name="sdata[award][year]" value="'.$sdata[award][year].'" size="4" min="1000" max="9999"></p>';	
	echo '<p><label class="th-label">Award 2</label>: <input style="width:70px;" type="text" name="sdata[award2][rank]" value="'.$sdata[award2][rank].'" placeholder="i.e. 2nd"> in Category <select name="sdata[award2][type]"><option value="sativa" '.selected("sativa", $sdata[award2][type], false).'>Sativa</option><option value="indica" '.selected("indica", $sdata[award2][type], false).'>Indica</option><option value="hybrid" '.selected("hybrid", $sdata[award2][type], false).'>Hybrid</option></select> at Competetion <input style="width:100px;" type="text" name="sdata[award2][name]" value="'.$sdata[award2][name].'"> on Year <input style="width:70px;" type="number" name="sdata[award2][year]" value="'.$sdata[award2][year].'" size="4" min="1000" max="9999"></p>';	
	echo '<p><label class="th-label">Award 3</label>: <input style="width:70px;" type="text" name="sdata[award3][rank]" value="'.$sdata[award3][rank].'" placeholder="i.e. 2nd"> in Category <select name="sdata[award3][type]"><option value="sativa" '.selected("sativa", $sdata[award3][type], false).'>Sativa</option><option value="indica" '.selected("indica", $sdata[award3][type], false).'>Indica</option><option value="hybrid" '.selected("hybrid", $sdata[award3][type], false).'>Hybrid</option></select> at Competetion <input style="width:100px;" type="text" name="sdata[award3][name]" value="'.$sdata[award3][name].'"> on Year <input style="width:70px;" type="number" name="sdata[award3][year]" value="'.$sdata[award3][year].'" size="4" min="1000" max="9999"></p>';	
	echo '<p><label class="th-label">Award 4</label>: <input style="width:70px;" type="text" name="sdata[award4][rank]" value="'.$sdata[award4][rank].'" placeholder="i.e. 2nd"> in Category <select name="sdata[award4][type]"><option value="sativa" '.selected("sativa", $sdata[award4][type], false).'>Sativa</option><option value="indica" '.selected("indica", $sdata[award4][type], false).'>Indica</option><option value="hybrid" '.selected("hybrid", $sdata[award4][type], false).'>Hybrid</option></select> at Competetion <input style="width:100px;" type="text" name="sdata[award4][name]" value="'.$sdata[award4][name].'"> on Year <input style="width:70px;" type="number" name="sdata[award4][year]" value="'.$sdata[award4][year].'" size="4" min="1000" max="9999"></p>';	
	echo '<p><label class="th-label">Award 5</label>: <input style="width:70px;" type="text" name="sdata[award5][rank]" value="'.$sdata[award5][rank].'" placeholder="i.e. 2nd"> in Category <select name="sdata[award5][type]"><option value="sativa" '.selected("sativa", $sdata[award5][type], false).'>Sativa</option><option value="indica" '.selected("indica", $sdata[award5][type], false).'>Indica</option><option value="hybrid" '.selected("hybrid", $sdata[award5][type], false).'>Hybrid</option></select> at Competetion <input style="width:100px;" type="text" name="sdata[award5][name]" value="'.$sdata[award5][name].'"> on Year <input style="width:70px;" type="number" name="sdata[award5][year]" value="'.$sdata[award5][year].'" size="4" min="1000" max="9999"></p>';	
	echo '<hr>';	
	if($post && $post->ID){$meds = get_thct_medis($post->ID);}
	if(!is_array($meds)){$meds=array();}
	$ma = get_option('all_medicals');
	if($post && $post->ID){$flavs = get_thct_flavs($post->ID);}
	if(!is_array($flavs)){$flavs=array();}
	$fa = get_option('all_flavours');

	echo '<p><label class="th-label">Medicinal Values</label>: <select multiple name="smvals" class="chosen" data-placeholder="Medicinal Values" style="width:250px;">';
	if($ma){foreach($ma as $m){echo '<option value="'.$m.'" '.((in_array($m, $meds))? 'selected':'').'>'.$m.'</option>';}}
	echo '</select></p>';
	echo '<p><label class="th-label">Flavours</label>: <select multiple name="sfvals" class="chosen" data-placeholder="Flavours" style="width:250px;">';
	if($fa){foreach($fa as $f){echo '<option value="'.$f.'" '.((in_array($f, $flavs))? 'selected':'').'>'.$f.'</option>';}}
	echo '</select></p>';
	echo '<script>jQuery(".chosen").each(function(){jQuery(this).chosen({allow_single_deselect:true});jQuery(this).before(\'<input type="hidden" name="\'+jQuery(this).attr("name")+\'">\').change(function(){jQuery(this).prev().val(jQuery(this).val());}).removeAttr("name");jQuery(this).prev().val(jQuery(this).val());}); jQuery(\'.chosen\').live(\'chosen:updated\', function(event){jQuery(this).prev().val(\'\');});</script>';
}
/*function strain_medical_box_html($post){
	echo '<div id="meds-vals">';

}
function strain_flavor_box_html($post){
	echo '<div id="flavs-vals">';
	if($post && $post->ID){$flavs = get_thct_flavs($post->ID);}
	if(!empty($flavs)) :
	foreach($flavs as $v){
		echo '<p><input type="hidden" name="sfvals[]" value="'.$v.'"><input type="text" value="'.$v.'" disabled> <input type="button" class="button" value="X" onClick="rem_flavs_vals(this)"></p>';
	}
	endif;
	echo '</div>';
	$fa = get_option('all_flavours');
	echo '<p><select class="combo" id="sfvalso"><option value="0">Select Flavour</option>';
	if($fa){foreach($fa as $f){echo '<option value="'.$f.'">'.$f.'</option>';}}
	echo '</select>';
	echo ' <input type="button" class="button" value="Add" onClick="add_flavs_vals();"></p>';
}*/
function strain_chems_box_html($post){
	$args = array(
		'posts_per_page'   => -1,
		'orderby'          => 'title',
		'order'            => 'DESC',
		'post_type'        => 'lab',
		'post_status'      => 'publish',
		'extra'      	   => 'allauthors',
	);
	$labs = get_posts( $args );
	if($post && $post->ID){$pa = get_thct_phenotypes($post->ID);}
	$uid = get_current_user_id();

	echo '<p>** Chemotypes work on per existing phenotype basis. If you added/edited your phenotypes just now, please update the strain page, and come back here again.</p>';
	if($pa && $labs){
		echo '<hr>';
		echo '<input type="hidden" name="chemsd[pID]" value="'.$post->ID.'">';
		echo '<input type="hidden" name="chemsd[voter]" value="'.$uid.'">';
		echo '<input type="hidden" name="chemsd[strain]" value="'.$post->ID.'">';
		echo '<input type="hidden" name="chemsd[type]" value="chemo">';
		echo '<p><label class="th-label">Phenotype</label>: <select name="chemsd[phenID]"><option value="0">Select one</option>';
		foreach($pa as $i=>$p){$i++; echo '<option value="'.$p->ID.'">Phenotype '.$i.'</option>';}
		echo '</select></p>';
		echo '<p><label class="th-label">Laboratory</label>: <select class="combo" name="chemsd[lab]"><option value="0">Unknown</option>';
		if($labs){foreach($labs as $l){echo '<option value="'.$l->ID.'">'.$l->post_title.'</option>';}}
		echo '</select></p>';
		echo '<p><label class="th-label">THC</label>: <input type="number" name="chems[THC]" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">CBD</label>: <input type="number" name="chems[CBD]" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">CBN</label>: <input type="number" name="chems[CBN]" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">CBG</label>: <input type="number" name="chems[CBG]" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">CBC</label>: <input type="number" name="chems[CBC]" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">Limonene</label>: <input type="number" name="chems[Limonene]" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">Myrcene</label>: <input type="number" name="chems[Myrcene]" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">Pinene</label>: <input type="number" name="chems[Pinene]" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">Linalool</label>: <input type="number" name="chems[Linalool]" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">B-Caryophyllene</label>: <input type="number" name="chems[BCaryophyllene]" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">Nerolidol</label>: <input type="number" name="chems[Nerolidol]" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">Phytol</label>: <input type="number" name="chems[Phytol]" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">Cineol</label>: <input type="number" name="chems[Cineol]" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">Humulene</label>: <input type="number" name="chems[Humulene]" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">Borneol</label>: <input type="number" name="chems[Borneol]" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">Terpinolene</label>: <input type="number" name="chems[Terpinolene]" max="100" min="0" step="0.01" size="6"> %';
		echo '<p>** Leave non tested chemotypes field BLANK, not ZERO.</p>';
		echo '<p>** Please check your values before saving, once added, you cannot change.</p>';
	}
}
function strain_sets_box_html($post){
	$pa = get_thct_phenotypes($post->ID);
	$ka = get_option('all_phentypes');
	//echo '<p><label class=""th-label">Type</label>: <select name="setvals['.$i.'][type]"><option value="homogeneous" '.selected($p[type], "homogeneous", false).'>Homogeneous</option><option value="nonhomogeneous" '.selected($p[type], "nonhomogeneous", false).'>Nonhomogeneous</option></select></p>';
	echo '<div id="sets-vals">';
	if($pa){foreach($pa as $i=>$p){
		$i++;
		echo '<script>var aci = '.(count($pa) + 1).';</script>';
		echo '<div class="postbox closed">';
		echo '<div class="thchandlediv expand" aria-expanded="false" title="Click to toggle" onClick="exp_sets_vals(this);"></div>';
		echo '<div class="thchandlediv" onClick="rem_sets_vals(this)" title="Click to remove" thcID="'.$p->ID.'"></div>';
		echo '<h3 id="null"><span>Phenotype '.$i.'</span></h3>';
		echo '<div class="inside">';
		echo '<input type="hidden" name="setvals['.$i.'][ID]" value="'.$p->ID.'">';
		echo '<p><label class=""th-label">Ratio</label>: <input type="number" name="setvals['.$i.'][ratio]" value="'.$p->ratio.'" min="1" max="100"> %</p>';
		if(is_array($ka) && !empty($ka)){foreach($ka as $k=>$kn){
			$w = str_replace(array('\"',"\'"), array('"',"'"), $p->$k); $w = htmlspecialchars($w);
			$va = get_option('_phen-'.$k);
			if($va && is_array($va) && !empty($va)){{$vs = '<select name="setvals['.$i.']['.$k.']">'; foreach($va as $v){
				$v = str_replace(array('\"',"\'"), array('"',"'"), $v); $v = htmlspecialchars($v);
				$vs .= '<option value="'.$v.'" '.selected($v, $w, false).'>'.$v.'</option>';}$vs .= '</select>';
			}$vs .= '</select>';} else {
				$vs = '<input type="number" name="setvals['.$i.']['.$k.']" placeholder="Value" value="'.$w.'">';
			}
			echo '<p><label style="display:inline-block;width:200px;">'.$kn.'</label> : '.$vs.'</p>';
		}} else {
			echo '<p>No phenotype characteristics found.</p>';
		}
		echo '</div>';
		echo '</div>';
	}} else {echo '<script>var aci = 2;</script>';
		echo '<div class="postbox">';
		echo '<div class="thchandlediv expand" aria-expanded="false" title="Click to toggle" onClick="exp_sets_vals(this);"></div>';
		echo '<div class="thchandlediv" onClick="rem_sets_vals(this)" title="Click to remove" thcID=""></div>';
		echo '<h3 id="null"><span>Phenotype 1</span></h3>';
		echo '<div class="inside">';
		echo '<p><label class=""th-label">Ratio</label>: <input type="number" name="setvals[1][ratio]" value="100" min="1" max="100"> %</p>';
		if(is_array($ka) && !empty($ka)){foreach($ka as $k=>$kn){
			$va = get_option('_phen-'.$k);
			if($va && is_array($va) && !empty($va)){$vs = '<select name="setvals[1]['.$k.']">'; foreach($va as $v){
				$v = str_replace(array('\"',"\'"), array('"',"'"), $v); $v = htmlspecialchars($v);
				$vs .= '<option value="'.$v.'">'.$v.'</option>';}$vs .= '</select>';
			} else {
				$vs = '<input type="number" name="setvals[1]['.$k.']" placeholder="Value" value="">';
			}
			echo '<p><label style="display:inline-block;width:200px;">'.$kn.'</label> : '.$vs.'</p>';
		}} else {
			echo '<p>No phenotype characteristics found.</p>';
		}
		echo '</div>';
		echo '</div>';
	}
	echo '</div>';
	echo '<input type="hidden" name="setrems" id="setrems" value="">';
	echo '<p><input type="button" class="button" value="Add Phenotype" onClick="add_sets_vals();"></p>';
}

add_action('save_post', 'strain_save_post', 10, 2);
function strain_save_post($pid, $post){
	if($post->post_type == 'strain'){
		//$esps = get_post_meta($pid, '_all_phenotypes', true);
		$esfs = get_thct_flavs($pid); if(empty($esfs)){$esfs = array();}
		$esms = get_thct_medis($pid); if(empty($esms)){$esms = array();}
		$dts = $sps = $sfs = $sms = $sts = array();

		$sdatas = $_POST['sdata']; if(is_array($sdatas)){$dts = $sdatas;}
		//$spvals = $_POST['spvals']; $spkeys = $_POST['spkeys'];
		$sfvals = $_POST['sfvals']; $sfvals = array_filter(explode(',', $sfvals));
		$smvals = $_POST['smvals']; $smvals = array_filter(explode(',', $smvals));
		$setvals = $_POST['setvals'];
		$setrems = $_POST['setrems'];
		$cmds = $_POST['chemsd'];
		$cms = $_POST['chems'];
		
		//$settyps = $_POST['settyps'];
		//$setrtos = $_POST['setrtos'];
		
		if($cmds && $cms){
			$f1 = 0; $f2 = 0; $st = 0; $cms1 = $cms;
			//$cmds[strain] = $dts[strain_id];
			$ot = get_thct_test($pid);
			$cmds[status] = $st;
			if(is_array($cmds) && $cmds[phenID] && $cmds[lab] && $cmds[phenID] != 0 && $cmds[lab] != 0){$f1 = 1;}
			if(is_array($cms1)){foreach($cms1 as $c=>$m){if($m && $m != ""){$f2 = 1;}else{unset($cms[$c]);}}}
			if($f1 && $f2 && count($cms)){addEdit_thct_test(array_merge($cmds, $cms));}
		}
		
		if($setvals && is_array($setvals)){foreach($setvals as $sv){
			$data = array('strain'=>$pid);
			addEdit_thct_phenotype(array_merge($sv, $data));
		}}
		if($setrems){
			$sra = explode(',', $setrems);
			$sra = array_map('trim',$sra);
			$sra = array_filter($sra);
			foreach($sra as $srv){delete_thct_phenotype($srv);}
		}
		
		foreach($dts as $k=>$v){
			if(($k=='father' || $k=='mother') && $v=='0'){update_post_meta($pid, $k, $pid);}
			else{update_post_meta($pid, $k, $v);}
		}
		
		
		for($i=0;$i<count($sfvals);$i++){
			if(!empty($sfvals[$i])){$sfs[] = $sfvals[$i];}
		}
		if(is_array($esfs) && is_array($sfs)){foreach(array_diff($sfs, $esfs) as $k){
			$k = esc_sql($k);
			update_post_meta($pid, '_flav-'.$k, 0);
			$vdt = array('voter'=>1, 'post'=>$pid, 'strain'=>$pid, 'type'=>'_flav-', 'key'=>$k);
			add_thct_rating($vdt);
		}}
		if(is_array($esfs) && is_array($sfs)){foreach(array_diff($esfs, $sfs) as $k){
			$k = esc_sql($k);
			delete_post_meta($pid, '_flav-'.$k);
			delete_thct_rating($pid, '_flav-', $k);
		}}
		
		for($i=0;$i<count($smvals);$i++){
			if(!empty($smvals[$i])){$sms[] = $smvals[$i];}
		}
		if(is_array($esms) && is_array($sms)){foreach(array_diff($sms, $esms) as $k){
			$k = esc_sql($k);
			update_post_meta($pid, '_medi-'.$k, 0);
			$vdt = array('voter'=>1, 'post'=>$pid, 'strain'=>$pid, 'type'=>'_medi-', 'key'=>$k);
			add_thct_rating($vdt);
		}}
		if(is_array($esms) && is_array($sfs)){foreach(array_diff($esms, $sms) as $k){
			$k = esc_sql($k);
			delete_post_meta($pid, '_medi-'.$k);
			delete_thct_rating($pid, '_medi-', $k);
		}}
			//$vdt = array('voter'=>1, 'post'=>$pid, 'strain'=>$pid, 'type'=>'potency');
			//add_thct_rating($vdt);
	}
}
add_action( 'delete_post', 'strain_delete' );
function strain_delete($pid){
	$post = get_post($pid);
	if ( $post->post_type != 'strain' ) return;
	delete_thct_rating($pid);
	delete_thct_phenotype($pid, 'strain');
	delete_thct_test($pid);
}

?>