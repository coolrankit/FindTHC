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
<div id="wrapper"><div id="wrap">
	<div id="header-wrap">
		<div id="header">
			<?php echo display_menu('primary', 'page', 3, true, 'slide');?>
			<div id="logo" class="width"><a href="<?php echo home_url();?>" title="<?php bloginfo('name'); ?>"><img class="logo" alt="<?php bloginfo('name'); ?>" src="<?php echo IMAGES_URL.'/logo-small.png';?>" width="180px" height="79px"></a></div>
		</div>
		<?php $radius = (($_POST['radius'])? $_POST['radius']:(($_SESSION['radius'])? $_SESSION['radius']:50));?>
		<form method="post" action="" id="locrad">Within: <input type="number" name="radius" value="<?php echo $radius;?>" min=""> Kms</form>
		<div id="topmap"></div>
		<?php echo thc_topmap();//$marks = get_map_markers('home'); echo serialize($marks);?>
	</div>
	
	<div id="container-wrap"><div id="container" class="width margin clearfix">

		<div id="content" class="padded clearfix">
			<div class="filters">
			<form method="get" action="">
			<?php
				$type = $_GET['type'];
				$price = $_GET['price'];
				$medical = $_GET['medical'];
				$rating = $_GET['rating'];
				$pheno = $_GET['pheno'];
				$phenv = $_GET['phenv'];
				$phenv = str_replace(array('\"',"\'"), array('"',"'"), $phenv);
				$view = $_GET['view'];
				$ma1 = get_option('all_medicals');
				$ka = get_option('all_phentypes');
				if($view){echo '<input type="hidden" name="view" value="'.$view.'">';}
				echo '<select name="type"><option value="">Selet Type</option><option value="sativa" '.selected("sativa", $type, false).'>Sativa</option><option value="indica" '.selected("indica", $type, false).'>Indica</option><option value="hybrid" '.selected("hybrid", $type, false).'>Hybrid</option>'.(($view != 'ss')? '<option value="concentrate" '.selected("concentrate", $type, false).'>Concentrate</option><option value="edible" '.selected("edible", $type, false).'>Edible</option><option value="clone" '.selected("clone", $type, false).'>Clone</option><option value="seed" '.selected("seed", $type, false).'>Seed</option>':'').'</select>';
				if($view != 'ss'){echo '<input type="number" placeholder="Max Price" name="price" value="'.$price.'" min="0" size="8">';}
				echo '<select name="medical"><option value="0">Medical Value</option>'.((get_current_user_id() > 0)? '<option value="1" '.selected(1, $medical, false).'>My Medical Conditions</option>':'');
				if(!empty($ma1) && is_array($ma1)){foreach($ma1 as $m){echo '<option value="'.$m.'" '.selected($m, $medical, false).'>'.$m.'</option>';}}
				echo '</select>';
				echo '<select name="rating"><option value=">= 0" '.selected(">= 0", $rating, false).'>Rating</option><option value="= 5" '.selected("= 5", $rating, false).'>5</option><option value=">= 4" '.selected(">= 4", $rating, false).'>4+</option><option value=">= 3" '.selected(">= 3", $rating, false).'>3+</option><option value=">= 2" '.selected(">= 2", $rating, false).'>2+</option><option value=">= 1" '.selected(">= 1", $rating, false).'>1+</option></select>';
				$ks = $vs = '';
				if(is_array($ka) && !empty($ka)){$ks = '<select name="pheno" id="pho" onChange="phenfilter();"><option value="" thcd="">Select Phenotypes</option>'; foreach($ka as $i=>$k){
					$ks .= '<option value="'.$i.'" thcd="'.$i.'" '.selected($i, $pheno, false).'>'.$k.'</option>';
					$va = get_option('_phen-'.$k);
					if($va && is_array($va) && !empty($va)){$vs .= '<select name="phenv" class="phenv" id="ph'.$i.'">'; foreach($va as $v){
						$vs .= '<option value="'.htmlspecialchars($v).'" '.selected($v, $phenv, false).'>'.htmlspecialchars(str_replace(array('\"',"\'"), array('"',"'"), $v)).'</option>';}$vs .= '</select>';
					}
					else {$vs .= '<input id="ph'.$i.'" type="text" name="phenv" class="phenv" placeholder="Value" value="">';}
				}$ks .= '</select>'; echo $ks.$vs.'<script>phenfilter();</script>';}
				echo '<input class="btn" type="submit" value="Filter">';
			?>
			</form>
			</div>
			
			<?php /*if($view=='ss') {?>
			<div class="main1 clearfix">
			<h2>All Strains : <?php if(!$view) {?><a class="fright" href="?view=rs&<?php echo $_SERVER['QUERY_STRING'];?>">View All</a><?php }?></h2>
			<?php $thc0_query = thc_get_query("main-0"); //$GLOBALS['wp_query'] = $thc0_query;?>
			<?php if($thc0_query->have_posts()) : while ($thc0_query->have_posts()) : $thc0_query->the_post();?>
				<div class="hentry fleft">
					<div class="hentry-thumb">
					<?php if($tid = get_post_thumbnail_id($post->ID)) {
							echo '<a href="'.get_the_permalink().'" title="'.get_the_title().'">'.get_the_post_thumbnail($post->ID,'thumbnail').'</a>';
					} else {echo '<a href="'.get_the_permalink().'" title="'.get_the_title().'"><img src="'.IMAGES_URL.'/no150.jpg"></a>';} ?>
					</div>
					<div class="hentry-content">
						<p><a href="<?php echo get_the_permalink();?>" title="<?php the_title();?>"><?php echo thc_get_data(get_the_ID(), 'aka_name');?></a></p>
					</div>
					<div class="hentry-content">
						<p><strong>Medical Values :</strong></p>
						<?php $medis = get_thct_medis(get_the_ID(), 3);
						if($medis){foreach($medis as $z){
							echo '<p>'.$z.' : '.get_rating(get_the_ID(), '_medi-', $z).'</p>';
						}}else{echo '<p>No Data Found.</p>';}?>
					</div>
					<div class="hentry-content">
						<p><strong>Flavours :</strong></p>
						<?php $flavs = get_thct_flavs(get_the_ID(), 2);
						if($flavs){foreach($flavs as $z){
							echo '<p>'.$z.' : '.get_rating(get_the_ID(), '_flav-', $z).'</p>';
						}}else{echo '<p>No Data Found.</p>';}?>
					</div>
				</div>
			<?php endwhile;?>
			<?php  if ($thc0_query->max_num_pages > 1 && $view) {?>
			<div class="navigation">
				<?php if(function_exists('wp_pagenavi')) {wp_pagenavi(array('query' => $thc0_query));}
				else {
				?><div class="alignleft"><?php next_posts_link(__('<span>&laquo;</span> Older posts', 'RegulusReign'));?></div>
				<div class="alignright"><?php previous_posts_link(__('Newer posts <span>&raquo;</span>', 'RegulusReign'));?></div><?php
				}?> 
			</div><!-- .navigation -->
			<?php } ?>
			<?php else:?>
			<p>No strain found as per your criteria.</p>
			<?php endif;?>
			<?php wp_reset_postdata();?>
			</div>
			<?php }?>

			<?php */ if($view=='rs' || !$view) {?>
			<div class="main1 clearfix">
			<h2>Rcommended Strains for You : <?php if(!$view) {?><a class="fright" href="?view=rs&<?php echo $_SERVER['QUERY_STRING'];?>">View All</a><?php }?></h2>
			<?php $thc1_query = thc_get_query("main-1"); //$GLOBALS['wp_query'] = $thc1_query;?>
			<?php if($thc1_query->have_posts()) : while ($thc1_query->have_posts()) : $thc1_query->the_post();?>
				<div class="hentry fleft">
					<div class="hentry-thumb">
					<?php if($tid = get_post_thumbnail_id($post->ID)) {
							echo '<a href="'.get_the_permalink().'" title="'.get_the_title().'">'.get_the_post_thumbnail($post->ID,'thumbnail').'</a>';
					} else {echo '<a href="'.get_the_permalink().'" title="'.get_the_title().'"><img src="'.IMAGES_URL.'/no150.jpg"></a>';} ?>
					</div>
					<div class="hentry-content">
						<p><a href="<?php echo get_the_permalink($post->post_parent);?>" title="<?php the_title();?>"><?php echo thc_get_data(get_the_ID(), 'aka_name') .' - '.  get_the_title($post->post_parent);?></a></p>
						<?php echo '<p><strong>Tested : </strong>';
						$tests = thc_get_testeds($post->ID);
						if($tests){$i=1;foreach($tests as $zz){
							echo $zz[key].': '.$zz[val].'%'.(($i<3)? ', ':'');
							$i++;
						}}else{echo 'No Data Found';}
						echo '</p>'; ?>
					</div>
					<div class="hentry-content">
						<p><strong>Medical Values :</strong></p>
						<?php $medis = get_thct_medis(get_the_ID(), 3);
						if($medis){foreach($medis as $z){
							echo '<p>'.$z.' : '.get_rating(get_the_ID(), '_medi-', $z).'</p>';
						}}else{echo '<p>No Data Found.</p>';}?>
					</div>
					<div class="hentry-content">
						<p><strong>Flavours :</strong></p>
						<?php $flavs = get_thct_flavs(get_the_ID(), 2);
						if($flavs){foreach($flavs as $z){
							echo '<p>'.$z.' : '.get_rating(get_the_ID(), '_flav-', $z).'</p>';
						}}else{echo '<p>No Data Found.</p>';}?>
					</div>
				</div>
			<?php endwhile;?>
			<?php  if ($thc1_query->max_num_pages > 1 && $view) {?>
			<div class="navigation">
				<?php if(function_exists('wp_pagenavi')) {wp_pagenavi(array('query' => $thc1_query));}
				else {
				?><div class="alignleft"><?php next_posts_link(__('<span>&laquo;</span> Older posts', 'RegulusReign'));?></div>
				<div class="alignright"><?php previous_posts_link(__('Newer posts <span>&raquo;</span>', 'RegulusReign'));?></div><?php
				}?> 
			</div><!-- .navigation -->
			<?php } ?>
			<?php else:?>
			<p>No strain found as per your criteria.</p>
			<?php endif;?>
			<?php wp_reset_postdata();?>
			</div>
			<?php }?>

			<?php if($view=='rd' || !$view) {?>
			<div class="main2 clearfix">
			<h2>Rcommended Dispensaries for You : <?php if(!$view) {?><a class="fright" href="?view=rd&<?php echo $_SERVER['QUERY_STRING'];?>">View All</a><?php }?></h2>
			<?php $thc2_query = thc_get_query("main-2"); //$GLOBALS['wp_query'] = $thc2_query;?>
			<?php if($thc2_query->have_posts()) : while ($thc2_query->have_posts()) : $thc2_query->the_post();?>
				<div class="hentry fleft">
					<div class="hentry-thumb">
					<?php if($tid = get_post_thumbnail_id($post->ID)) {
							echo '<a href="'.get_the_permalink().'" title="'.get_the_title().'">'.get_the_post_thumbnail($post->ID,'thumbnail').'</a>';
					} else {echo '<a href="'.get_the_permalink().'" title="'.get_the_title().'"><img src="'.IMAGES_URL.'/no150.jpg"></a>';} ?>
					</div>
					<div class="hentry-content">
						<p><a href="<?php echo get_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a></p>
					</div>
					<div class="hentry-content">
						<p><strong>Best Menu Items :</strong></p>
						<?php $mens = get_posts(array('post_type'=>'listing', 'post_parent'=>get_the_ID(), 'post_status'=>'publish', 'posts_per_page'=>3, 'extra'=>'main-4', 'suppress_filters'=>false));
						if($mens){foreach($mens as $z){
							echo '<p>'.$z->post_title.' : '.do_rating($z->mediVA).'</p>';
						}}else{echo '<p>No Item Found.</p>';}?>
					</div>
					<div class="hentry-content">
						<p><strong>Overall Rating :</strong></p>
						<?php $rates = get_posts(array('post_type'=>'listing', 'post_parent'=>get_the_ID(), 'post_status'=>'publish', 'posts_per_page'=>-1, 'extra'=>'main-5', 'suppress_filters'=>false));
						if($rates){
							echo '<p>Quality : '.do_rating($rates[0]->mediVA).'</p>';
						}else{echo '<p>No Data Found.</p>';}?>
					</div>
				</div>
			<?php endwhile;?>
			<?php  if ($thc2_query->max_num_pages > 1 && $view) {?>
			<div class="navigation">
				<?php if(function_exists('wp_pagenavi')) {wp_pagenavi(array('query' => $thc2_query));}
				else {
				?><div class="alignleft"><?php next_posts_link(__('<span>&laquo;</span> Older posts', 'RegulusReign'));?></div>
				<div class="alignright"><?php previous_posts_link(__('Newer posts <span>&raquo;</span>', 'RegulusReign'));?></div><?php
				}?> 
			</div><!-- .navigation -->
			<?php } ?>
			<?php else:?>
			<p>No dispensary found as per your criteria.</p>
			<?php endif;?>
			<?php wp_reset_postdata();?>
			</div>
			<?php }?>

			<?php if($view=='fd' || !$view) {?>
			<div class="main2 clearfix">
			<h2>Featured Dispensaries : <?php if(!$view) {?><a class="fright" href="?view=fd&<?php echo $_SERVER['QUERY_STRING'];?>">View All</a><?php }?></h2>
			<?php $thc3_query = thc_get_query("main-3"); //$GLOBALS['wp_query'] = $thc3_query;?>
			<?php if($thc3_query->have_posts()) : while ($thc3_query->have_posts()) : $thc3_query->the_post();?>
				<div class="hentry fleft">
					<div class="hentry-thumb">
					<?php if($tid = get_post_thumbnail_id($post->ID)) {
							echo '<a href="'.get_the_permalink().'" title="'.get_the_title().'">'.get_the_post_thumbnail($post->ID,'thumbnail').'</a>';
					} else {echo '<a href="'.get_the_permalink().'" title="'.get_the_title().'"><img src="'.IMAGES_URL.'/no150.jpg"></a>';} ?>
					</div>
					<div class="hentry-content">
						<p><a href="<?php echo get_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a></p>
					</div>
					<div class="hentry-content">
						<p><strong>Best Menu Items :</strong></p>
						<?php $mens = get_posts(array('post_type'=>'listing', 'post_parent'=>get_the_ID(), 'post_status'=>'publish', 'posts_per_page'=>3, 'extra'=>'main-6', 'suppress_filters'=>false));
						if($mens){foreach($mens as $z){
							echo '<p>'.$z->post_title.' : '.do_rating($z->mediVA).'</p>';
						}}else{echo '<p>No Item Found.</p>';}?>
					</div>
					<div class="hentry-content">
						<p><strong>Overall Rating :</strong></p>
						<?php $rates = get_posts(array('post_type'=>'listing', 'post_parent'=>get_the_ID(), 'post_status'=>'publish', 'posts_per_page'=>-1, 'extra'=>'main-5', 'suppress_filters'=>false));
						if($rates){
							echo '<p>Quality : '.do_rating($rates[0]->rating).'</p>';
						}else{echo '<p>No Data Found.</p>';}?>
					</div>
				</div>
			<?php endwhile;?>
			<?php  if ($thc3_query->max_num_pages > 1 && $view) {?>
			<div class="navigation">
				<?php if(function_exists('wp_pagenavi')) {wp_pagenavi(array('query' => $thc3_query));}
				else {
				?><div class="alignleft"><?php next_posts_link(__('<span>&laquo;</span> Older posts', 'RegulusReign'));?></div>
				<div class="alignright"><?php previous_posts_link(__('Newer posts <span>&raquo;</span>', 'RegulusReign'));?></div><?php
				}?> 
			</div><!-- .navigation -->
			<?php } ?>
			<?php else:?>
			<p>No featured dispensary found.</p>
			<?php endif;?>
			<?php wp_reset_postdata();?>
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