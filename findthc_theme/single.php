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
		<!-- <div id="topmap"></div> -->
		<?php //echo thc_topmap();?>
	</div>
	
	<div id="container-wrap"><div id="container" class="width margin clearfix">

		<div id="content" class="padded clearfix">
			<?php while (have_posts()) : the_post();?>
				<div class="entry clearfix">
					<!-- <p class="page-leneage"><?php //echo thc_page_leneage($post);?></p> -->
					<div class="entry-thumb">
					<?php if($tid = get_post_thumbnail_id($post->ID)) {
							echo get_the_post_thumbnail($post->ID,'medium');
					} ?>
					</div>
					<h2 class="entry-title"><?php echo thc_page_title($post);?></h2>
					<div class="entry-content">
					<?php the_content('');?>
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