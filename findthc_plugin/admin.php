<?php
add_action('admin_menu', 'thcp_menus');
function thcp_menus(){
	add_menu_page('Findthc Admin', 'Findthc Admin', 'administrator', 'thcpadm', 'thcp_admin_page');
	//add_submenu_page('thcpadm', 'Trial Page', 'Trial Page', 'administrator', 'trialpage', 'thc_listing_edit');
}
function thcp_admin_page(){
	global $thco;
	if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST["apvals"])){
		$vs = $_POST["apvals"];
		if(is_array($vs)){foreach($vs as $k=>$v){
			$va = explode(',', $v);
			$va = array_map('trim',$va);
			$va = array_filter($va);
			update_option('_phen-'.$k, $va);
		}}
	}
	if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST["thcpfs"])){
		$pfs = $_POST["thcpfs"];
		$pfa = explode(',', $pfs);
		$pfa = array_map('trim',$pfa);
		$pfa = array_filter($pfa);
		update_option('all_flavours', $pfa);
	}
	if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST["thcpms"])){
		$pms = $_POST["thcpms"];
		$pma = explode(',', $pms);
		$pma = array_map('trim',$pma);
		$pma = array_filter($pma);
		update_option('all_medicals', $pma);
	}
	if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST["publish_strain"])){
		update_option("publish_strain", $_POST["publish_strain"]);
	} elseif($_SERVER['REQUEST_METHOD']=="POST" && !isset($_POST["publish_strain"])){
		delete_option("publish_strain");
	}
	if($_SERVER['REQUEST_METHOD']=="POST" && is_array($_POST["pcount"])){
		$do = $_POST["pcount"];
		if($do[home] && $do[home]>0){$thco[home] = $do[home];}
		if($do[all] && $do[all]>0){$thco[all] = $do[all];}
		update_option('thc_posts_count', $thco);
	}
	echo '<div class="wrap">';
	echo '<h1>Findthc Admin Settings</h1><hr>';
	echo '<form method="post" action="">';
	echo '<p><input type="checkbox" name="publish_strain" value="true" '.checked(get_option("publish_strain"), "true", false).'> Strain needs Admin\'s approval to be published.</p>';
	echo '<p><label>Items per section on home page</label> : <input type="number" min="1" name="pcount[home]" value="'.$thco[home].'"></p>';
	echo '<p><label>Items per page on view all page</label> : <input type="number" min="1" name="pcount[all]" value="'.$thco[all].'"></p>';
	echo '<p><input type="submit" value="Save Options" class="button"></p>';
	echo '<hr>';
	echo '<h3>Add/Edit Phenotypes</h3>';
	echo '<div id="phens-vals">';
	$pa = get_option('all_phentypes');
	if($pa && is_array($pa) && !empty($pa)){foreach($pa as $k=>$x){
		$poa = get_option('_phen-'.$k);
		if($poa && is_array($poa)){$os = implode(', ', $poa);} else{$os = '';}
		$os = str_replace(array('\"',"\'"), array('"',"'"), $os);
		$os = htmlspecialchars($os);
		echo '<p><label style="display:inline-block;width:200px;">'.$x.'</label> : <input type="text" name="apvals['.$k.']" placeholder="Option Values" value="'.$os.'"></p>';
	}}
	echo '</div>';
	echo '<p><input type="submit" value="Save Phenotypes" class="button"></p>';

	echo '<hr><h3>Add/Edit Flavours</h3>';
	$fa = get_option('all_flavours');
	if($fa && is_array($fa)){$fs = implode(', ', $fa);}
	else{$fs = '';}
	echo '<p>Add All flavours in the following box, use comma (,) as separator.</p>';
	echo '<p><textarea name="thcpfs" cols="70" rows="8">'.$fs.'</textarea></p>';
	echo '<p><input type="submit" value="Save Flavours" class="button"></p>';

	echo '<hr><h3>Add/Edit Medicinal Values</h3>';
	$ma = get_option('all_medicals');
	if($ma && is_array($ma)){$ms = implode(', ', $ma);}
	else{$ms = '';}
	echo '<p>Add All medicinal values in the following box, use comma (,) as separator.</p>';
	echo '<p><textarea name="thcpms" cols="70" rows="8">'.$ms.'</textarea></p>';
	echo '<p><input type="submit" value="Save Medicinal Values" class="button"></p>';

	echo '</form>';
	echo '</div>';
}
add_action('wp_ajax_thc_listing_edit', 'thc_listing_edit');
add_action('wp_ajax_nopriv_thc_listing_edit', 'thc_listing_edit');
function thc_listing_edit($post=false){
	if(!$post && $_REQUEST['alid'] && is_numeric($_REQUEST['alid'])){$post = get_post($_REQUEST['alid']);}
	elseif(is_numeric($post)){$post = get_post($post);}
	if($post && $post->post_type == 'dispensary'){$dip = $post;}
	elseif($post && $post->post_type == 'listing'){$lip = $post; $dip = get_post($lip->post_parent);}
	if(!$lip){echo '<div class="lfwrap" id="lfwrap">';}
	if(!$lip){echo '<div class="lfwrapper" id="lfwrapper">';}
	$args = array(
		'posts_per_page'   => -1,
		'orderby'          => 'title',
		'order'            => 'DESC',
		'post_type'        => 'lab',
		'post_status'      => 'publish',
		'extra'      	   => 'allauthors',
	);
	$args2 = array(
		'posts_per_page'   => -1,
		'orderby'          => 'title',
		'order'            => 'DESC',
		'post_type'        => 'strain',
		'post_status'      => 'publish',
		'extra'      	   => 'allauthors',
	);
	if($dip && $dip->ID){$did = $dip->ID; $dia = $dip->post_author;}
	if($lip && $lip->ID){$lid = $lip->ID; $lia = $lip->post_author;}
	//$lip; $lid; $lia;
	$t = get_post_meta($did, 'type', true);
	$prices = get_thct_price($lid);
	$sdata = get_all_data($lid);
	$images = get_post_meta($lid, 'images', true); if(!is_array($images) || empty($images)){$images = array();}
	$featured = get_post_meta($lid, 'featured', true); if(!is_array($featured) || empty($featured)){$featured = array();}
	$meds = get_thct_medis($lid); if(!is_array($meds) || empty($meds)){$meds = array();}
	$flavs = get_thct_flavs($lid); if(!is_array($flavs) || empty($flavs)){$flavs = array();}
	$chemsd = get_thct_test($lid);
	$ma = get_option('all_medicals');
	$fa = get_option('all_flavours');
	$labs = get_posts( $args );
	$stns = get_posts( $args2 );
	$uid = get_current_user_id();
	echo '';
//	echo '<form name="listingf" id="listingf" method="post" action="">' ;
	echo '<input type="hidden" name="listing[post_parent]" value="'.$did.'">';
	echo '<input type="hidden" name="listing[post_author]" value="'.$dia.'">';
	echo '<input type="hidden" name="listing[ID]" value="'.$lid.'">';
	echo '<p><label class="th-label">Item Title</label>: <input type="text" name="listing[post_title]" value="'.$lip->post_title.'"></p>';
	echo '<p><label class="th-label">Featured Image</label>: <button class="thc-upload button" thc-name="lmeta[sdata][featured]">Upload Image</button><span>';
	if($featured){foreach($featured as $k=>$v){
	echo '<span class="thc-uploaded" style="background-image:url(\''.$v.'\')"><input type="hidden" value="'.$v.'" name="lmeta[sdata][featured]['.$k.']" /><span></span></span>';
	}}
	echo '</span></p>';
	echo '<p><label class="th-label">Additional Images</label>: <button class="thc-upload button" thc-name="lmeta[sdata][images]" thc-multiple="5">Upload Image</button><span>';
	if($images){foreach($images as $k=>$v){
	echo '<span class="thc-uploaded" style="background-image:url(\''.$v.'\')"><input type="hidden" value="'.$v.'" name="lmeta[sdata][images]['.$k.']" /><span></span></span>';
	}}
	echo '</span></p>';//
	echo '<p><label class="th-label">Strain</label>: <select name="lmeta[sdata][strain_id]" class="chosen" data-placeholder="Select Strain" style="width:250px;"><option value="">Select Strain</option>';
	if($stns){foreach($stns as $s){echo '<option value="'.$s->ID.'" '.selected($s->ID, $sdata[strain_id], false).'>'.$s->post_title.'</option>';}}
	echo '</select></p>';
	echo '<p><label class="th-label">AKA Name</label>: <input type="text" name="lmeta[sdata][aka_name]" value="'.$sdata[aka_name].'"></p>';
	echo '<p><label class="th-label">Type</label>: <select name="lmeta[sdata][type]" id="sdtype" onChange="tog_prices();"><option value="sativa" '.selected("sativa", $sdata[type], false).'>Sativa</option><option value="indica" '.selected("indica", $sdata[type], false).'>Indica</option><option value="hybrid" '.selected("hybrid", $sdata[type], false).'>Hybrid</option><option value="concentrate" '.selected("concentrate", $sdata[type], false).'>Concentrate</option><option value="edible" '.selected("edible", $sdata[type], false).'>Edible</option><option value="seed" '.selected("seed", $sdata[type], false).'>Seed</option><option value="clone" '.selected("clone", $sdata[type], false).'>Clone</option></select></p>'; //
	echo '<p><label class="th-label">Awards</label>: <input type="text" name="lmeta[sdata][award][rank]" value="'.$sdata[award][rank].'" placeholder="i.e. 2nd"> in 
	Category <select name="sdata[award][type]"><option value="sativa" '.selected("sativa", $sdata[award][type], false).'>Sativa</option><option value="indica" '.selected("indica", $sdata[award][type], false).'>Indica</option><option value="hybrid" '.selected("hybrid", $sdata[award][type], false).'>Hybrid</option><option value="concentrate" '.selected("concentrate", $sdata[award][type], false).'>Concentrate</option><option value="edible" '.selected("edible", $sdata[award][type], false).'>Edible</option><option value="seed" '.selected("seed", $sdata[award][type], false).'>Seed</option><option value="clone" '.selected("clone", $sdata[award][type], false).'>Clone</option></select></p>'; //
	echo '<p> <label class="th-label"></label>at 
	Competetion <input type="text" name="lmeta[sdata][award][name]" value="'.$sdata[award][name].'"> on Year <input type="number" name="lmeta[sdata][award][year]" value="'.$sdata[award][year].'" size="4" min="1000" max="9999"></p>';	
	echo '<p><label class="th-label">Medicinal Values</label>: <select multiple name="lmeta[smvals]" class="chosen" data-placeholder="Medicinal Values" style="width:250px;">';//<option value="">Medicinal Values</option>
	if($ma){foreach($ma as $m){echo '<option value="'.$m.'" '.((in_array($m, $meds))? 'selected':'').'>'.$m.'</option>';}}
	echo '</select></p>';
	echo '<p><label class="th-label">Flavours</label>: <select multiple name="lmeta[sfvals]" class="chosen" data-placeholder="Flavours" style="width:250px;">';//<option value="">Flavours</option>
	if($fa){foreach($fa as $f){echo '<option value="'.$f.'" '.((in_array($f, $flavs))? 'selected':'').'>'.$f.'</option>';}}
	echo '</select></p>';
	echo '<hr>';	
	echo '<p><b>Item Prices (in '.(($t=='seed' || $t=='clone')? 'Quantity':'Gram').')</b></p>';
	echo '<div id="prices-seed" '.(($t=='seed' || $t=='clone')? 'style="display:block;"':'').'>';
	echo '<p><label class="th-label"><label class="th-label-s"></label>1 x R</label>: $ <input type="number" min="0" step="0.01" name="lmeta[prices][p1xR]" value="'.$prices->p1xR.'"></p>';
	echo '<p><label class="th-label"><label class="th-label-s"></label>5 x R</label>: $ <input type="number" min="0" step="0.01" name="lmeta[prices][p5xR]" value="'.$prices->p5xR.'"></p>';
	echo '<p><label class="th-label"><label class="th-label-s"></label>10 x R</label>: $ <input type="number" min="0" step="0.01" name="lmeta[prices][p10xR]" value="'.$prices->p10xR.'"></p>';
	echo '<p><label class="th-label"><label class="th-label-s"></label>20 x R</label>: $ <input type="number" min="0" step="0.01" name="lmeta[prices][p20xR]" value="'.$prices->p20xR.'"></p>';
	echo '<p><label class="th-label"><label class="th-label-s"></label>30 x R</label>: $ <input type="number" min="0" step="0.01" name="lmeta[prices][p30xR]" value="'.$prices->p30xR.'"></p>';
	echo '<p><label class="th-label"><label class="th-label-s"></label>40 x R</label>: $ <input type="number" min="0" step="0.01" name="lmeta[prices][p40xR]" value="'.$prices->p40xR.'"></p>';
	echo '<p><label class="th-label"><label class="th-label-s"></label>50 x R</label>: $ <input type="number" min="0" step="0.01" name="lmeta[prices][p50xR]" value="'.$prices->p50xR.'"></p>';
	echo '</div>';
	echo '<div id="prices-weight" '.(($t=='seed' || $t=='clone')? '':'style="display:block;"').'>';
	echo '<p><label class="th-label"><label class="th-label-s"></label>1/8</label>: $ <input type="number" min="0" step="0.01" name="lmeta[prices][p18]" value="'.$prices->p18.'"></p>';
	echo '<p><label class="th-label"><label class="th-label-s"></label>1/4</label>: $ <input type="number" min="0" step="0.01" name="lmeta[prices][p14]" value="'.$prices->p14.'"></p>';
	echo '<p><label class="th-label"><label class="th-label-s"></label>1/2</label>: $ <input type="number" min="0" step="0.01" name="lmeta[prices][p12]" value="'.$prices->p12.'"></p>';
	echo '<p><label class="th-label"><label class="th-label-s"></label>oz</label>: $ <input type="number" min="0" step="0.01" name="lmeta[prices][poz]" value="'.$prices->poz.'"></p>';
	echo '</div>';
	echo '<hr>';	
	echo '<p><strong>Test Results</strong></p>';
	if($labs){
		if($chemsd && $chemsd->ID){echo '<input type="hidden" name="lmeta[chemsd][ID]" value="'.$chemsd->ID.'">';}
		echo '<input type="hidden" name="lmeta[chemsd][pID]" value="'.$lid.'">';
		echo '<input type="hidden" name="lmeta[chemsd][voter]" value="'.$uid.'">';
		echo '<input type="hidden" name="lmeta[chemsd][type]" value="testo">';
		echo '<p><label class="th-label">Laboratory</label>: <select name="lmeta[chemsd][lab]" class="chosen" data-placeholder="Select Lab" style="width:250px;"><option value="">Select Lab</option>';
		if($labs){foreach($labs as $l){echo '<option value="'.$l->ID.'" '.selected($l->ID, $chemsd->lab, false).'>'.$l->post_title.'</option>';}}
		echo '</select></p>';
		echo '<p><label class="th-label">THC</label>: <input type="number" name="lmeta[chems][THC]" value="'.(($chemsd->THC)? $chemsd->THC:'').'" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">CBD</label>: <input type="number" name="lmeta[chems][CBD]" value="'.(($chemsd->CBD)? $chemsd->CBD:'').'" max="100" min="0" step="0.01" size="6"> %';
		echo '<p><label class="th-label">CBN</label>: <input type="number" name="lmeta[chems][CBN]" value="'.(($chemsd->CBN)? $chemsd->CBN:'').'" max="100" min="0" step="0.01" size="6"> %';
		echo '<p>** Leave non tested fields BLANK, not ZERO.</p>';
	}
	echo '<script>jQuery(".chosen").each(function(){jQuery(this).chosen({allow_single_deselect:true});jQuery(this).before(\'<input type="hidden" name="\'+jQuery(this).attr("name")+\'">\').change(function(){jQuery(this).prev().val(jQuery(this).val());}).removeAttr("name");jQuery(this).prev().val(jQuery(this).val());}); jQuery(\'.chosen\').live(\'chosen:updated\', function(event){jQuery(this).prev().val(\'\');});</script>';
	/*echo '<script>jQuery(\'.chosen\').live(\'chosen:updated\', function(event){jQuery(this).prev().val(\'\');});</script>';*/
	if($lip){echo '<script>jQuery("#lcontent").val(\''.$lip->post_content.'\');tinyMCE.activeEditor.setContent(\''.$lip->post_content.'\');tinyMCE.triggerSave();</script>';}
	if(!$lip){echo '</div>';}
//	echo '</form>';
	if(!$lip){
	echo '<hr>';
	echo '<div style="width:500px;"><label class="th-label"><b>Description</b></label>';
	wp_editor($lip->post_content, 'lcontent', array('media_buttons'=>false, 'textarea_name'=>'listing[post_content]', 'editor_height'=>250));//echo '<textarea name="listing[post_parent]" id="lcontent">'.$lip->post_content.'</textarea>';
	echo '</div>';
	echo '<p align="center"><a class="button" onClick="thc_listing_update();">Add Item to Menu</a></p>';//<label class="th-label-s"></label><a class="button" onClick="thc_listing_reset();">Reset Form</a>
	echo '<img src="'.THCP_URL.'/images/loading.gif" class="thcloading">';
	}
	if(!$lip){echo '</div>';}
}
add_action('wp_ajax_thc_listing_update', 'thc_listing_update');
add_action('wp_ajax_nopriv_thc_listing_update', 'thc_listing_update');
function thc_listing_update(){
	$main = $_REQUEST['data'];
	if($main){
		$mldata = $main['listing'];
		$dip = get_post($mldata[post_parent]);
		if($dip && $dip->post_status = 'auto-draft'){wp_update_post(array('ID'=>$dip->ID, 'post_status'=>'publish'));}
		if($dip){$pid = wp_insert_post(array_merge($mldata, array('post_status'=>'publish', 'post_type'=>'listing')));}
		if($pid){$post = get_post($pid);}

		if($pid && $post){
			$esfs = get_thct_flavs($pid); if(empty($esfs)){$esfs = array();}
			$esms = get_thct_medis($pid); if(empty($esms)){$esms = array();}
			$prc = $sts = $dts = $sfs = $sms = array();
			$sdatas = $main['lmeta']['sdata']; if(is_array($sdatas)){$dts = $sdatas;}
			$spvals = $main['lmeta']['prices']; if(is_array($spvals)){$prc = $spvals;}
			$sfvals = $main['lmeta']['sfvals']; $sfvals = explode(',', $sfvals); $sfvals = array_filter($sfvals); $sfs = $sfvals;
			$smvals = $main['lmeta']['smvals']; $smvals = explode(',', $smvals); $smvals = array_filter($smvals); $sms = $smvals;
			$cmds = $main['lmeta']['chemsd'];
			$cms = $main['lmeta']['chems'];
			$featured = $sdatas['featured'];
			//$images = $sdatas['images'];
			$sid = $sdatas['strain_id'];
			
			if(is_array($featured) && !empty($featured)){foreach($featured as $k=>$v){set_post_thumbnail($pid, $k);}}
			
			update_post_meta($pid, '_all_datas', $dts);
			foreach($dts as $k=>$v){
				update_post_meta($pid, $k, $v);
			}
			
			if($cmds && $cms){
				$f1 = 0; $f2 = 0; $st = 0; $cms1 = $cms;
				$cmds[strain] = $dts[strain_id];
				$cmds[pID] = $pid;
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
			
			if($dts[type]=='seed' || $dts[type]=='clone'){update_post_meta($pid, 'main_price', $prc[p10xR]); $mprice = $prc[p10xR];}
			else {update_post_meta($pid, 'main_price', $prc[poz]); $mprice = $prc[poz];}
			addEdit_thct_price(array_merge($prc, array('pID'=>$pid, 'dispensary'=>$post->post_parent, 'strain'=>$sid, 'pType'=>$post->post_type, 'price'=>$mprice)));

			if(is_array($esfs) && is_array($sfs)){foreach(array_diff($sfs, $esfs) as $k){
				$k = esc_sql($k);
				update_post_meta($pid, '_flav-'.$k, 0);
				$vdt = array('voter'=>1, 'post'=>$pid, 'listing'=>$pid, 'dispensary'=>$post->post_parent, 'strain'=>$sid, 'type'=>'_flav-', 'key'=>$k);
				add_thct_rating($vdt);
			}}
			if(is_array($esfs) && is_array($sfs)){foreach(array_diff($esfs, $sfs) as $k){
				$k = esc_sql($k);
				delete_post_meta($pid, '_flav-'.$k);
				delete_thct_rating($pid, '_flav-', $k);
			}}

			if(is_array($esms) && is_array($sms)){foreach(array_diff($sms, $esms) as $k){
				$k = esc_sql($k);
				update_post_meta($pid, '_medi-'.$k, 0);
				$vdt = array('voter'=>1, 'post'=>$pid, 'listing'=>$pid, 'dispensary'=>$post->post_parent, 'strain'=>$sid, 'type'=>'_medi-', 'key'=>$k);
				add_thct_rating($vdt);
			}}
			if(is_array($esms) && is_array($sfs)){foreach(array_diff($esms, $sms) as $k){
				$k = esc_sql($k);
				delete_post_meta($pid, '_medi-'.$k);
				delete_thct_rating($pid, '_medi-', $k);
			}}

			if(($esid != $sid)){//($parex != $parnw) || 
				$data = array('strain'=>$sid); $whr = array('listing'=>$pid);
				update_thct_rating($data, $whr);
			}
		}
	}
	if($dip){dispensary_menulist_html($dip);}
}
add_action('wp_ajax_thc_listing_delete', 'thc_listing_delete');
add_action('wp_ajax_nopriv_thc_listing_delete', 'thc_listing_delete');
function thc_listing_delete(){
	if($_REQUEST['alid'] && is_numeric($_REQUEST['alid'])){$p = wp_delete_post($_REQUEST['alid'], true);}
	if($p && $p->post_parent){$dip = get_post($p->post_parent);}
	if($dip){dispensary_menulist_html($dip);}
}

?>