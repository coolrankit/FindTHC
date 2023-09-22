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
					<h2 class="entry-title"><?php echo thc_page_title($post);?> <?php if(can_claim($post)){echo '<input type="button" value="Claim This Business" class="btn fright" onclick="jQuery(\'.claim\').toggle();">';}?></h2>
					<?php if(can_claim($post)){?>
					<div class="claim fright">
					<form method="post" action="">
					<?php
					$fclm = thcClaims();
					if($fclm==3){echo '<h4 align="center">Thank You.<br>We recieved your request. You\'ll recieve email confirmation on approval of your requesst.</h4>';} else {
						if($fclm==1){echo '<p>Please enter valid email address.</p>';}
						if($fclm==2){echo '<p>Error in registering the user.</p>';}
						$usr = wp_get_current_user();
						if(current_user_can('rep')){$rc = $usr->ID; echo '<input type="hidden" name="clm[status]" value="1">';}
						elseif($usr && $usr->ID > 0){$em=$usr->user_email;}
						echo '<input type="hidden" name="clm[post]" value="'.$post->ID.'">';
						echo '<input type="hidden" name="clm[dated]" value="'.current_time('mysql').'">';
					?>
					<p><label class="th-label">Your Email</label>: <input type="text" name="clm[email]" value="<?php echo $em;?>"></p>
					<p><label class="th-label">Representative ID</label>: <input type="text" name="clm[reps]" value="<?php echo $rc;?>"></p>
					<br><p align="center"><input type="submit" value="Claim It" class="btn"></p>
					<?php }?>
					</form>
					</div>
					<?php }?>
					<div class="entry-content fleft">
						<p><strong>Address :</strong></p>
						<p style="max-width:280px;"><?php echo thc_get_data(get_the_ID(), 'address');?></p>
						<p><strong>Phone :</strong></p>
						<p><?php echo thc_get_data(get_the_ID(), 'phone');?></p>
						<p><strong>Email :</strong></p>
						<p><?php echo thc_get_data(get_the_ID(), 'email');?></p>
					</div>
					<div class="entry-content fleft">
						<p><strong>Hours Open :</strong></p>
						<?php if(thc_get_data(get_the_ID(), '_scdl-allday')) {?>
						<p>Always Open (24x7)</p>
						<?php } else {?>
						<p><label>Monday</label> : <?php echo thc_get_data(get_the_ID(), '_scdl-monday');?></p>
						<p><label>Tuesday</label> : <?php echo thc_get_data(get_the_ID(), '_scdl-tuesday');?></p>
						<p><label>Wednesday</label> : <?php echo thc_get_data(get_the_ID(), '_scdl-wednesday');?></p>
						<p><label>Thursday</label> : <?php echo thc_get_data(get_the_ID(), '_scdl-thursday');?></p>
						<p><label>Friday</label> : <?php echo thc_get_data(get_the_ID(), '_scdl-friday');?></p>
						<p><label>Saturday</label> : <?php echo thc_get_data(get_the_ID(), '_scdl-saturday');?></p>
						<p><label>Sunday</label> : <?php echo thc_get_data(get_the_ID(), '_scdl-sunday');?></p>
						<?php }?>
					</div>
					<div class="clear"></div>
					<div class="entry-content"><?php echo get_social_links(get_the_ID());?></div>
					<div class="entry-content"><?php echo get_special_offer(get_the_ID());?></div>
					<div class="entry-content">
					<h3 class="title">All Menu Items :</h3>
					<?php $lists1 = thc_delivery_listings($post->ID); $typ = '';
					if($lists1){
						echo '<table class="list-table deli" border="1px" bordercolor="#ccc">';
						foreach($lists1 as $z){
							if($typ == '' || $typ != ucwords(thc_get_data($z->ID, 'type'))){$typ = ucwords(thc_get_data($z->ID, 'type'));
								echo '<tr class="listcat"><td>'.$typ.' &raquo;</td><td>gm</td><td>1/8</td><td>1/4</td><td>1/2</td><td>oz</td></tr>';// onClick="tog_deli_item(\''.$typ.'\');"
							}
							$prc = get_thct_price($z->ID);
							$item = '';
							$item .= '<div class="list-item">';
							$item .= '<p class="item-title"><a href="'.get_permalink(thc_get_data($z->ID, 'strain_id')).'" title="Learn More!">'.get_the_title(thc_get_data($z->ID, 'strain_id')).'</a><span>'.$prc->pgm.'<span>gm</span>'.$prc->p18.'<span>1/8</span>'.$prc->p14.'<span>1/4</span>'.$prc->p12.'<span>1/2</span>'.$prc->poz.'<span>oz</span></span></p>';
							$imgs = thc_get_data($z->ID, 'images');//get_posts(array('post_type'=>'attachment', 'post_status'=>'inherit', 'post_parent'=>$z->ID, 'posts_per_page'=>5));
							
							if($imgs){$item .= '<p>';foreach($imgs as $k=>$v){if(wp_attachment_is_image($k)){
								$item .= '<img class="item-img" src="'.wp_get_attachment_thumb_url($k).'">';
							}}$item .= '</p>';}
							$item .= '<div class="hentry-content fleft">';
								$item .= '<p><strong>Medical Values :</strong></p>';
								$medis = get_thct_medis($z->ID);
								if($medis){foreach($medis as $zz){
									$item .= '<p>'.$zz.' : '.get_rating($z->ID, '_medi-', $zz).'</p>';
								}}else{$item .= '<p>No Data Found.</p>';}
							$item .= '</div>';
							$item .= '<div class="hentry-content fleft">';
								$item .= '<p><strong>Flavours :</strong></p>';
								$flavs = get_thct_flavs($z->ID);
								if($flavs){foreach($flavs as $zz){
									$item .= '<p>'.$zz.' : '.get_rating($z->ID, '_flav-', $zz).'</p>';
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
							echo '<tr class="deliitem '.$typ.'"><td><div class="clear">'.$z->post_title.$item.'</div></td><td>'.$prc->pgm.'</td><td>'.$prc->p18.'</td><td>'.$prc->p14.'</td><td>'.$prc->p12.'</td><td>'.$prc->poz.'</td></tr>';
						}
						echo '</table>';
					}
					?>
					<?php $lists2 = thc_seed_listings($post->ID); $typ = '';
					if($lists2){
						echo '<table class="list-table seed" border="1px" bordercolor="#ccc">';
						foreach($lists2 as $z){
							if($typ == '' || $typ != ucwords(thc_get_data($z->ID, 'type'))){$typ = ucwords(thc_get_data($z->ID, 'type'));
								echo '<tr class="listcat"><td>'.$typ.' &raquo;</td><td>1 x R</td><td>5 x R</td><td>10 x R</td><td>20 x R</td><td>30 x R</td><td>40 x R</td><td>50 x R</td></tr>';// onClick="tog_seed_item(\''.$typ.'\');"
							}
							$pr = get_thct_price($z->ID);
							$item = '';
							$item .= '<div class="list-item">';
							$item .= '<p class="item-title"><a href="'.get_permalink(thc_get_data($z->ID, 'strain_id')).'" title="Learn More!">'.get_the_title(thc_get_data($z->ID, 'strain_id')).'</a></p>';//<span>'.$prc[18].'<span>1/8</span>'.$prc[14].'<span>1/4</span>'.$prc[12].'<span>1/2</span>'.$prc[oz].'<span>oz</span></span>
							$imgs = thc_get_data($z->ID, 'images');//$imgs = get_posts(array('post_type'=>'attachment', 'post_status'=>'inherit', 'post_parent'=>$z->ID, 'posts_per_page'=>5));
							
							if($imgs){$item .= '<p>';foreach($imgs as $k=>$v){if(wp_attachment_is_image($k)){
								$item .= '<img class="item-img" src="'.wp_get_attachment_thumb_url($v).'">';
							}}$item .= '</p>';}
							$item .= '<div class="hentry-content fleft">';
								$item .= '<p><strong>Medical Values :</strong></p>';
								$medis = get_thct_medis($z->ID);
								if($medis){foreach($medis as $zz){
									$item .= '<p>'.$zz.' : '.get_rating($z->ID, '_medi-', $zz).'</p>';
								}}else{$item .= '<p>No Data Found.</p>';}
							$item .= '</div>';
							$item .= '<div class="hentry-content fleft">';
								$item .= '<p><strong>Flavours :</strong></p>';
								$flavs = get_thct_flavs($z->ID);
								if($flavs){foreach($flavs as $zz){
									$item .= '<p>'.$zz.' : '.get_rating($z->ID, '_flav-', $zz).'</p>';
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
							echo '<tr class="seeditem '.$typ.'"><td><div class="clear">'.$z->post_title.$item.'</div></td><td>'.$pr->p1xR.'</td><td>'.$pr->p5xR.'</td><td>'.$pr->p10xR.'</td><td>'.$pr->p20xR.'</td><td>'.$pr->p30xR.'</td><td>'.$pr->p40xR.'</td><td>'.$pr->p50xR.'</td></tr>';
						}
						echo '</table>';
					}
					if(!$lists1 && !$lists2){echo '<p class="lev2">No Data Found.</p>';}
					?>
					<div class="clear"></div>
					</div>
				</div>
			<?php endwhile;?>
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