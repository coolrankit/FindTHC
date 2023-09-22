<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php language_attributes();?> prefix="og: http://ogp.me/ns#">
<head>
<title><?php wp_title( '|', true, 'right' ); ?></title>
<!-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0">
<link rel="stylesheet" type="text/css" media="all" href="https://fonts.googleapis.com/css?family=Lobster" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url');?>" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_URL.'/menus.css';?>" />
<?php //if(is_singular()) {wp_enqueue_script('comment-reply');}?>
<?php //if(is_single()){echo '<script src="https://apis.google.com/js/platform.js" async defer><\/script>';}?>
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<?php wp_head();?>
</head>

<body <?php body_class();?>>
<div id="wrpper"><div id="wrap">
	<div id="header-wrap">
		<div id="header">
			<?php echo display_menu('primary', 'page', 3, true, 'slide');?>
			<div id="logo" class="width"><a href="<?php echo home_url();?>" title="<?php bloginfo('name'); ?>"><img class="logo" alt="<?php bloginfo('name'); ?>" src="<?php echo IMAGES_URL.'/logo-small.png';?>" width="180px" height="79px"></a></div>
		</div>
		<?php $radius = (($_POST['radius'])? $_POST['radius']:(($_SESSION['radius'])? $_SESSION['radius']:50));?>
		<form method="post" action="" id="locrad">Within: <input type="number" name="radius" value="<?php echo $radius;?>" min=""> Kms</form>
		<div id="topmap"></div>
		<?php echo thc_topmap();?>
	</div>

	<div id="container-wrap"><div id="container" class="width margin clearfix">

		<div id="content" class="padded clearfix">
			<?php while (have_posts()) : the_post();?>
				<div class="entry clearfix">
					<p class="page-leneage"><?php echo thc_page_leneage($post);?></p>
					<div class="entry-thumb">
					<?php if($tid = get_post_thumbnail_id($post->ID)) {
							echo get_the_post_thumbnail($post->ID,'medium');
					} ?>
					</div>
					<h2 class="entry-title"><?php echo thc_page_title($post);?></h2>
					<div class="entry-content">
						<p><strong>AKA :</strong> <?php echo thc_get_data(get_the_ID(), 'aka_name');?></p>
						<p><strong>Awards :</strong> <?php echo thc_get_data(get_the_ID(), 'award');?>
						<?php if(thc_get_data(get_the_ID(), 'award2')){echo ', '.thc_get_data(get_the_ID(), 'award2');}?>
						<?php if(thc_get_data(get_the_ID(), 'award3')){echo ', '.thc_get_data(get_the_ID(), 'award3');}?>
						<?php if(thc_get_data(get_the_ID(), 'award4')){echo ', '.thc_get_data(get_the_ID(), 'award4');}?>
						<?php if(thc_get_data(get_the_ID(), 'award5')){echo ', '.thc_get_data(get_the_ID(), 'award5');}?>
						</p>
						<br>
						<p><strong>Type :</strong> <?php echo thc_get_data(get_the_ID(), 'type');?></p>
						<p class="clearfix"><strong>Potency :</strong> <?php echo get_rating(get_the_ID(), 'potency');?> (<?php echo get_rated(get_the_ID(), 'potency', '', true);?>)</p>
						<br>
						<p><strong>Medical Values :</strong></p>
						<?php $medis = get_thct_medis(get_the_ID());
						if($medis){foreach($medis as $z){
							echo '<p class="lev2">'.$z.' : '.get_rating(get_the_ID(), '_medi-', $z).' ('.get_rated(get_the_ID(), '_medi-', $z, true).')</p>';
						}}else{echo '<p class="lev2">No Data Found.</p>';}?>
						<p><strong>Flavours :</strong></p>
						<?php $flavs = get_thct_flavs(get_the_ID());
						if($flavs){foreach($flavs as $z){
							echo '<p class="lev2">'.$z.' : '.get_rating(get_the_ID(), '_flav-', $z).' ('.get_rated(get_the_ID(), '_flav-', $z, true).')</p>';
						}}else{echo '<p class="lev2">No Data Found.</p>';}?>
						<br>
						<h3 class="title">Locally Available :</h3>
						<?php $locals = thc_local_dispensaries($post);
						if($locals){foreach($locals as $z){
							$prc = get_thct_price($z->ID);
							echo '<p class="lev2 disp-title"><a href="'.get_permalink($z->post_parent).'">'.get_the_title($z->post_parent).'</a> : <span>'.$prc->pgm.'<span>gm</span>'.$prc->p18.'<span>1/8</span>'.$prc->p14.'<span>1/4</span>'.$prc->p12.'<span>1/2</span>'.$prc->poz.'<span>oz</span></span> '.get_rated($z->ID, '_medi-', '', true).'</p>';
						}}else{echo '<p class="lev2">No Data Found.</p>';}?>
						<h3 class="title">Locally Available Tested :</h3>
						<?php $locals = thc_local_dispensaries($post, true);
						if($locals){foreach($locals as $z){
							$tdt = '';
							$prc = get_thct_price($z->ID);
							$tests = thc_get_testeds($z->ID);
							if($tests){$i=1;foreach($tests as $zz){
								$tdt .= $zz['key'].': '.$zz['val'].'%'.(($i<3)? ', ':'');
								$i++;
							}}
							echo '<p class="lev2 disp-title"><a href="'.get_permalink($z->post_parent).'">'.get_the_title($z->post_parent).'</a> : <span>'.$prc->pgm.'<span>gm</span>'.$prc->p18.'<span>1/8</span>'.$prc->p14.'<span>1/4</span>'.$prc->p12.'<span>1/2</span>'.$prc->poz.'<span>oz</span></span> '.$tdt.'</p>';
						}}else{echo '<p class="lev2">No Data Found.</p>';}?>

						<br>
						<h3 class="title">For Growers :</h3>
						<p><strong>Awards :</strong> <?php echo thc_get_data(get_the_ID(), 'award');?></p>
						<p><strong>Indoor Yelds :</strong> <?php echo thc_get_data(get_the_ID(), 'inyield');?> gm/Sq.M</p>
						<p><strong>Outdoor Yelds :</strong> <?php echo thc_get_data(get_the_ID(), 'outyield');?> gm/Sq.M</p>
						<p><strong>Indoor Flowering :</strong> <?php echo thc_get_data(get_the_ID(), 'inflower');?></p>
						<p><strong>Outdoor Harvest :</strong> <?php echo thc_get_data(get_the_ID(), 'outhervest');?></p>
						<?php if(current_user_can('edit_strains')){?>
						<p><input type="button" value="Suggest Edit" onclick="ovrOpen('ofg');" class="btn"></p>
						<?php }?>

						<br>
						<h3 class="title">Links to Grow Journal :</h3>
						<?php $jrs = get_journals(get_the_ID());if($jrs){echo '<table border="0" cellspacing="0" cellpadding="5px">'; $k=1; foreach($jrs as $z){
							if(($k%4)==1){echo '<tr>';}
							echo '<td>'.$k.'. <a href="'.thc_get_data($z->ID, 'jlink').'">'.thc_get_data($z->ID, 'location').'/'.thc_get_data($z->ID, 'medium').'/'.thc_get_data($z->ID, 'nutrient').'</a></td>';
							if(count($jrs)==$k){for($i=$k;$i<4;$i++){echo '<td></td>';}}
							if(($k%4)==0){echo '</tr>';}
							$k++;
						} echo '</table>'; }else{echo '<p class="lev2">No Data Found.</p>';}?>
						<?php if(current_user_can('edit_journals')){?>
						<p><input type="button" value="Add Journal" onclick="ovrOpen('ogj');" class="btn"></p>
						<?php }?>

						<br>
						<h3 class="title">For Breeders :</h3>
						<div class="psets">
						<?php
						$psets = get_thct_phenotypes(get_the_ID());
						$ka = get_option('all_phentypes');
						echo '<table cellspacing="0" border="0" cellpadding="5px"">';
						if($psets){ foreach($psets as $i=>$x){$i++;
							echo '<tr><td>';
							echo '<p><strong>Phenotype '.$i.' : '.$x->ratio.'%</strong></p>';
							if(is_array($ka)){foreach($ka as $k=>$v){
								echo '<p>'.$v.' : '.htmlspecialchars(str_replace(array('\"',"\'"), array('"',"'"), (($x->$k)? $x->$k:'-'))).'</p>';
							}}
							echo '</td>';
							echo '<td>';
							the_thct_tests_avg(get_the_ID(), $x->ID, "THC");
							the_thct_tests_avg(get_the_ID(), $x->ID, "CBD");
							the_thct_tests_avg(get_the_ID(), $x->ID, "CBN");
							the_thct_tests_avg(get_the_ID(), $x->ID, "CBG");
							the_thct_tests_avg(get_the_ID(), $x->ID, "CBC");
							the_thct_tests_avg(get_the_ID(), $x->ID, "Limonene");
							the_thct_tests_avg(get_the_ID(), $x->ID, "Myrcene");
							the_thct_tests_avg(get_the_ID(), $x->ID, "Pinene");
							echo '</td>';
							echo '<td>';
							the_thct_tests_avg(get_the_ID(), $x->ID, "Linalool");
							the_thct_tests_avg(get_the_ID(), $x->ID, "BCaryophyllene");
							the_thct_tests_avg(get_the_ID(), $x->ID, "Nerolidol");
							the_thct_tests_avg(get_the_ID(), $x->ID, "Phytol");
							the_thct_tests_avg(get_the_ID(), $x->ID, "Cineol");
							the_thct_tests_avg(get_the_ID(), $x->ID, "Humulene");
							the_thct_tests_avg(get_the_ID(), $x->ID, "Borneol");
							the_thct_tests_avg(get_the_ID(), $x->ID, "Terpinolene");
							echo '</td>';
							echo '</tr>';
						}}
						if(current_user_can('edit_strains')){
						echo '<tr><td><p><input type="button" value="Suggest Phenotype" onclick="ovrOpen(\'osp\');" class="btn"></p></td><td></td><td><p><input type="button" value="Add Chemotype" onclick="ovrOpen(\'oac\');" class="btn"></p></td></tr>';
						}
						echo '</table>';
						?>
						<br>
						</div>

						<h3 class="title">Seeds/Clones Available :</h3>
						<?php $locals = thc_local_dispensaries($post, false, true, 10);//thc_strain_prices($post->ID, 10);
							if($locals){
							echo '<table class="price-table" border="1px" bordercolor="#ccc"><tr><td></td>
							<td>1 x R</td><td>5 x R</td><td>10 x R</td><td>20 x R</td><td>30 x R</td><td>40 x R</td><td>50 x R</td></tr>';//<td>1 x F</td><td>5 x F</td><td>10 x F</td><td>20 x F</td><td>30 x F</td><td>40 x F</td><td>50 x F</td>
							foreach($locals as $z){
							$pr = get_thct_price($z->ID);
							echo '<tr><td><a href="'.get_permalink($z->post_parent).'">'.get_the_title($z->post_parent).'</a></td>
							<td>'.$pr->p1xR.'</td><td>'.$pr->p5xR.'</td><td>'.$pr->p10xR.'</td><td>'.$pr->p20xR.'</td><td>'.$pr->p30xR.'</td><td>'.$pr->p40xR.'</td><td>'.$pr->p50xR.'</td></tr>'; //<td>'.$pr['clone']['1xF'].'</td><td>'.$pr['clone']['5xF'].'</td><td>'.$pr['clone']['10xF'].'</td><td>'.$pr['clone']['20xF'].'</td><td>'.$pr['clone']['30xF'].'</td><td>'.$pr['clone']['40xF'].'</td><td>'.$pr['clone']['50xF'].'</td>
							}
							echo '</table>';
							}else{echo '<p class="lev2">No Data Found.</p>';}?>
						<br>
						<h3 class="title">Seeds Available :</h3>
						<?php $locals = thc_seeds($post->ID, 10);//thc_strain_prices($post->ID, 10);
							if($locals){
							echo '<table class="price-table" border="1px" bordercolor="#ccc"><tr><td></td>
							<td>1 x</td><td>5 x</td><td>10 x</td><td>20 x</td><td>30 x</td><td>40 x</td><td>50 x</td><td>100 x</td></tr>';//<td>1 x F</td><td>5 x F</td><td>10 x F</td><td>20 x F</td><td>30 x F</td><td>40 x F</td><td>50 x F</td>
							foreach($locals as $z){
							//$pr = get_post_meta($z->ID, '_all_prices', true);
							$pr = get_thct_price($z->ID);
							$ur = get_post_meta($z->ID, '_all_urls', true);
							echo '<tr><td>'.get_the_title($z->ID).'</td>
							<td><a href="'.$ur[1].'">'.$pr->p1xR.'</a></td>
							<td><a href="'.$ur[5].'">'.$pr->p5xR.'</a></td>
							<td><a href="'.$ur[10].'">'.$pr->p10xR.'</a></td>
							<td><a href="'.$ur[20].'">'.$pr->p20xR.'</a></td>
							<td><a href="'.$ur[30].'">'.$pr->p30xR.'</a></td>
							<td><a href="'.$ur[40].'">'.$pr->p40xR.'</a></td>
							<td><a href="'.$ur[50].'">'.$pr->p50xR.'</a></td>
							<td><a href="'.$ur[100].'">'.$pr->p100xR.'</a></td>
							</tr>'; //<td>'.$pr['clone']['1xF'].'</td><td>'.$pr['clone']['5xF'].'</td><td>'.$pr['clone']['10xF'].'</td><td>'.$pr['clone']['20xF'].'</td><td>'.$pr['clone']['30xF'].'</td><td>'.$pr['clone']['40xF'].'</td><td>'.$pr['clone']['50xF'].'</td>
							}
							echo '</table>';
							}else{echo '<p class="lev2">No Data Found.</p>';}?>

						<br>
						<h3 class="title">Family Tree :</h3>
						<?php echo thc_family_tree($post->ID);?>
					</div>
				</div>
			<?php endwhile;?>
		</div>

<div class="ovrw">
<?php if(current_user_can('edit_journals')){?>
<div class="ovrd" id="ogj">
<input type="button" value="X" onclick="ovrClose();" class="ovrc">
<form action="" id="ogjF" method="post">
<p><strong>Add Grow Journal:</strong></p>
<?php
echo '<input type="hidden" name="ovd[item]" value="'.$post->ID.'">';	
echo '<input type="hidden" name="ovd[itype]" value="'.$post->post_type.'">';	
echo '<input type="hidden" name="ovd[owner]" value="'.$post->post_author.'">';	
echo '<input type="hidden" name="ovd[user]" value="'.get_current_user_id().'">';	
echo '<input type="hidden" name="ovd[dtype]" value="gj">';	
echo '<p><label class="th-label">Location</label>: <select name="ovd[data][location]"><option value="Indoor">Indoor</option><option value="Outdoor">Outdoor</option></select></p>';
echo '<p><label class="th-label">Medium</label>: <select name="ovd[data][medium]"><option value="Soil">Soil</option><option value="Coco Coir">Coco Coir</option><option value="Rockwool">Rockwool</option><option value="Clay Pebbles">Clay Pebbles</option></select></p>';
echo '<p><label class="th-label">Nutrient</label>: <select name="ovd[data][nutrient]"><option value="Organic">Organic</option><option value="Natural">Natural</option><option value="Synthetic">Synthetic</option></select></p>';
echo '<p><label class="th-label">System</label>: <select name="ovd[data][gsystem]"><option value="Hand Water">Hand Water</option><option value="Drip">Drip</option><option value="Dwc">Dwc</option><option value="Flood & Drain">Flood & Drain</option><option value="Aeroponics">Aeroponics</option><option value="Aquaponics">Aquaponics</option></select></p>';
echo '<p><label class="th-label">Flowering in</label>: <input type="number" name="ovd[data][flowering]" value="" size="6" min="0"> days</p>';
$va = get_option('_phen-distance');
if($va && is_array($va) && !empty($va)){{$vs = '<select name="ovd[data][distance]">'; foreach($va as $v){
	$v = str_replace(array('\"',"\'"), array('"',"'"), $v); $v = htmlspecialchars($v);
	$vs .= '<option value="'.$v.'">'.$v.'</option>';}$vs .= '</select>';
}$vs .= '</select>';} else {
	$vs = '<input type="number" name="fdata[distance]" placeholder="Value" value="">';
}
echo '<p><label class="th-label">Nodal Distance</label>: '.$vs.'</p>';

echo '<p><label class="th-label">Journal URL</label>: <input type="text" name="ovd[data][jlink]" value="'.$fdata[jlink].'"></p>';
echo '<hr>';
echo '<p><label class="th-label">Journal Title</label>: <input type="text" name="ovd[title]" value=""></p>';
echo '<p><label class="th-label">Journal Description</label>:<br><textarea name="ovd[content]" style="width:98%;" rows="7"></textarea></p>';
?>
<p align="center"><input type="submit" value="Add Journal" class="btn"></p>
</form>
</div>
<?php }?>
<?php if(current_user_can('edit_strains')){?>
<div class="ovrd" id="ofg">
<input type="button" value="X" onclick="ovrClose();" class="ovrc">
<form action="" id="ofgF" method="post">
<p><strong>Suggest Edit For Growers:</strong></p>
<?php
echo '<input type="hidden" name="ovd[item]" value="'.$post->ID.'">';	
echo '<input type="hidden" name="ovd[itype]" value="'.$post->post_type.'">';	
echo '<input type="hidden" name="ovd[owner]" value="'.$post->post_author.'">';	
echo '<input type="hidden" name="ovd[user]" value="'.get_current_user_id().'">';	
echo '<input type="hidden" name="ovd[dtype]" value="fg">';	
echo '<p><label class="th-label">Indoor Yield in</label>: <input type="number" name="ovd[data][inyield]" value=""> gm/Sq.M</p>';	
echo '<p><label class="th-label">Outdoor Yield in</label>: <input type="number" name="ovd[data][outyield]" value=""> gm/Sq.M</p>';	
echo '<p><label class="th-label">Indoor Flowering Time</label>: <input type="text" name="ovd[data][inflower]" value=""></p>';	
echo '<p><label class="th-label">Outdoor Harvest Time</label>: <input type="text" name="ovd[data][outhervest]" value=""></p>';	
echo '<p><label class="th-label">Awards</label>: <input type="text" name="ovd[data][award][rank]" value="" placeholder="i.e. 2nd"> in 
Category <select name="ovd[data][award][type]"><option value="sativa">Sativa</option><option value="indica">Indica</option><option value="hybrid">Hybrid</option></select></p>';
echo '<p> <label class="th-label"></label>at Competetion <input type="text" name="ovd[data][award][name]" value=""> on Year <input type="number" name="ovd[data][award][year]" value="" size="4" min="1000" max="9999"></p>';	
?>
<p align="center"><input type="submit" value="Suggest It" class="btn"></p>
</form>
</div>

<div class="ovrd" id="osp">
<input type="button" value="X" onclick="ovrClose();" class="ovrc">
<form action="" id="ospF" method="post">
<p><strong>Suggest Phenotype:</strong></p>
<?php
echo '<input type="hidden" name="ovd[item]" value="'.$post->ID.'">';	
echo '<input type="hidden" name="ovd[itype]" value="'.$post->post_type.'">';	
echo '<input type="hidden" name="ovd[owner]" value="'.$post->post_author.'">';	
echo '<input type="hidden" name="ovd[user]" value="'.get_current_user_id().'">';	
echo '<input type="hidden" name="ovd[dtype]" value="sp">';
$ka = get_option('all_phentypes'); $vs = '';
echo '<p><label class=""th-label">Ratio</label>: <input type="number" name="ovd[data][ratio]" value="100" min="1" max="100"> %</p>';
		if(is_array($ka) && !empty($ka)){foreach($ka as $k=>$kn){
			$va = get_option('_phen-'.$k);
			if($va && is_array($va) && !empty($va)){$vs = '<select name="ovd[data]['.$k.']">'; foreach($va as $v){
				$v = str_replace(array('\"',"\'"), array('"',"'"), $v); $v = htmlspecialchars($v);
				$vs .= '<option value="'.$v.'">'.$v.'</option>';}$vs .= '</select>';
			} else {
				$vs = '<input type="number" name="ovd[data]['.$k.']" placeholder="Value" value="">';
			}
			echo '<p><label style="display:inline-block;width:200px;">'.$kn.'</label> : '.$vs.'</p>';
		}} else {
			echo '<p>No phenotype characteristics found.</p>';
		}
?>
<p align="center"><input type="submit" value="Suggest It" class="btn"></p>
</form>
</div>

<div class="ovrd" id="oac">
<input type="button" value="X" onclick="ovrClose();" class="ovrc">
<form action="" id="oacF" method="post">
<p><strong>Add Chemotype:</strong></p>
<?php
echo '<input type="hidden" name="ovd[item]" value="'.$post->ID.'">';	
echo '<input type="hidden" name="ovd[itype]" value="'.$post->post_type.'">';	
echo '<input type="hidden" name="ovd[owner]" value="'.$post->post_author.'">';	
echo '<input type="hidden" name="ovd[user]" value="'.get_current_user_id().'">';	
echo '<input type="hidden" name="ovd[dtype]" value="ac">';
$args = array(
'posts_per_page'   => -1,
'orderby'          => 'title',
'order'            => 'DESC',
'post_type'        => 'lab',
'post_status'      => 'publish',
);
$labs = get_posts( $args );
$pa = get_thct_phenotypes($post->ID);

echo '<p>** Chemotypes work on per existing phenotype basis. If you added/edited your phenotypes just now, please update the strain page, and come back here again.</p>';
if($pa && $labs){
echo '<hr>';
echo '<p><label class="th-label">Phenotype</label>: <select name="ovd[pheno]"><option value="0">Select one</option>';
foreach($pa as $i=>$p){$i++; echo '<option value="'.$p->ID.'">Phenotype '.$i.'</option>';}
echo '</select></p>';
echo '<p><label class="th-label">Laboratory</label>: <select class="combo" name="ovd[lab]"><option value="0">Unknown</option>';
if($labs){foreach($labs as $l){echo '<option value="'.$l->ID.'">'.$l->post_title.'</option>';}}
echo '</select></p>';
echo '<table border="0" cellspacing="0"><tr>';
echo '<td>';
echo '<p><label class="th-label">THC</label>: <input type="number" name="ovd[data][THC]" max="100" min="0" step="0.01" size="6"> %';
echo '<p><label class="th-label">CBD</label>: <input type="number" name="ovd[data][CBD]" max="100" min="0" step="0.01" size="6"> %';
echo '<p><label class="th-label">CBN</label>: <input type="number" name="ovd[data][CBN]" max="100" min="0" step="0.01" size="6"> %';
echo '<p><label class="th-label">CBG</label>: <input type="number" name="ovd[data][CBG]" max="100" min="0" step="0.01" size="6"> %';
echo '<p><label class="th-label">CBC</label>: <input type="number" name="ovd[data][CBC]" max="100" min="0" step="0.01" size="6"> %';
echo '<p><label class="th-label">Limonene</label>: <input type="number" name="ovd[data][Limonene]" max="100" min="0" step="0.01" size="6"> %';
echo '<p><label class="th-label">Myrcene</label>: <input type="number" name="ovd[data][Myrcene]" max="100" min="0" step="0.01" size="6"> %';
echo '<p><label class="th-label">Pinene</label>: <input type="number" name="ovd[data][Pinene]" max="100" min="0" step="0.01" size="6"> %';
echo '</td><td>';
echo '<p><label class="th-label-s"></label><label class="th-label">Linalool</label>: <input type="number" name="ovd[data][Linalool]" max="100" min="0" step="0.01" size="6"> %';
echo '<p><label class="th-label-s"></label><label class="th-label">B-Caryophyllene</label>: <input type="number" name="ovd[data][BCaryophyllene]" max="100" min="0" step="0.01" size="6"> %';
echo '<p><label class="th-label-s"></label><label class="th-label">Nerolidol</label>: <input type="number" name="ovd[data][Nerolidol]" max="100" min="0" step="0.01" size="6"> %';
echo '<p><label class="th-label-s"></label><label class="th-label">Phytol</label>: <input type="number" name="ovd[data][Phytol]" max="100" min="0" step="0.01" size="6"> %';
echo '<p><label class="th-label-s"></label><label class="th-label">Cineol</label>: <input type="number" name="ovd[data][Cineol]" max="100" min="0" step="0.01" size="6"> %';
echo '<p><label class="th-label-s"></label><label class="th-label">Humulene</label>: <input type="number" name="ovd[data][Humulene]" max="100" min="0" step="0.01" size="6"> %';
echo '<p><label class="th-label-s"></label><label class="th-label">Borneol</label>: <input type="number" name="ovd[data][Borneol]" max="100" min="0" step="0.01" size="6"> %';
echo '<p><label class="th-label-s"></label><label class="th-label">Terpinolene</label>: <input type="number" name="ovd[data][Terpinolene]" max="100" min="0" step="0.01" size="6"> %';
echo '</td></tr></table>';
echo '<p>** Leave non tested chemotypes field BLANK, not ZERO.</p>';
echo '<p>** Please check your values before saving, once added, you cannot change.</p>';
}
?>
<p align="center"><input type="submit" value="Add It" class="btn"></p>
</form>
</div>
<?php }?>
</div>

	</div></div>

	<div id="footer-wrap">
		<div id="footer" class="width clearfix">
		<div class="foot fleft">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer 1") ) : ?><?php endif;?>
		</div>
		<div class="foot fleft"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer 2") ) : ?><?php endif;?></div>
		<div class="foot fleft"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer 3") ) : ?><?php endif;?></div>
		</div>
		<div id="credit-wrap">
		<p class="credit">All Rights Reserved 2016 &copy; <a href="<?php home_url();?>" title="<?php bloginfo('name');?>"><?php bloginfo('name');?></a></p>
		</div>
	</div>
</div></div>
<?php wp_footer();?>
</body>
</html>