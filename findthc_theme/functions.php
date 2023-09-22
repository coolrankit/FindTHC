<?php
	// Define REGULUS
	if(!defined('THEME_URL')) {define('THEME_URL', get_template_directory_uri());}
	if(!defined('THEME_DIR')) {define('THEME_DIR', TEMPLATEPATH);}
	// Define IMAGES
	if(!defined('IMAGES_URL')) {define('IMAGES_URL', get_template_directory_uri() . '/images');}
	if(!defined('IMAGES_DIR')) {define('IMAGES_DIR', TEMPLATEPATH . '/images');}
	// Define JS
	if(!defined('JS_URL')) {define('JS_URL', get_template_directory_uri() . '/js');}
	if(!defined('JS_DIR')) {define('JS_DIR', TEMPLATEPATH . '/js');}
	// Define INCLUDES
	if(!defined('INCLUDES_URL')) {define('INCLUDES_URL', get_template_directory_uri() . '/includes');}
	if(!defined('INCLUDES_DIR')) {define('INCLUDES_DIR', TEMPLATEPATH . '/includes');}

	add_theme_support('post-thumbnails');
	//set_post_thumbnail_size(300, 250, true);
	//update_option('thumbnail_size_w', 300);
	//update_option('thumbnail_size_h', 250);
	//update_option('thumbnail_crop', 1);
	register_nav_menu('primary',  __('Primary Menu', 'RegulusReign'));
	//register_nav_menu('secondary',  __('Secondary Menu', 'RegulusReign'));
	if(!wp_script_is('jquery')) {wp_enqueue_script('jquery');}
	if(!wp_script_is('hoverIntent')) {wp_enqueue_script('hoverIntent', JS_URL . '/hoverIntent.js');}
	if(!wp_script_is('superfish')) {wp_enqueue_script('superfish', JS_URL . '/superfish.js');}


add_action( 'wp_enqueue_scripts', 'thct_enq' );
function thct_enq($hook){
	global $thcopt;
	global $thcp;
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'thct_script', JS_URL . '/script.js', 'jquery' );
	wp_enqueue_script( 'thct_gmap', 'https://maps.google.com/maps/api/js'.(($thcopt[gmapkey])? '?key='.$thcopt[gmapkey]:'') );
	if(is_home() || is_front_page() || is_page($thcp['strains']) || is_singular('strain')){
		wp_enqueue_script( 'thct_gmap1', JS_URL . '/markerclusterer.js', 'thct_gmap' );
	}
}

add_filter( 'wp_title', 'thc_wp_title', 20, 2 );
function thc_wp_title( $title, $sep ) {
	global $paged, $page;
	if ( is_feed() ) {
		return $title;
	}
	$title .= get_bloginfo( 'name', 'display' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'thcTheme' ), max( $paged, $page ) );
	}
	return $title;
}
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name'=> 'Footer 1',
		'id' => 'foot_1',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h5 class="widgettitle">',
		'after_title' => '</h5>',
	));
	register_sidebar(array(
		'name'=> 'Footer 2',
		'id' => 'foot_2',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h5 class="widgettitle">',
		'after_title' => '</h5>',
	));
	register_sidebar(array(
		'name'=> 'Footer 3',
		'id' => 'foot_3',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h5 class="widgettitle">',
		'after_title' => '</h5>',
	));
}
function display_menu($name, $type='page', $depth=1, $fish=false, $effect='fade'){
	wp_nav_menu('depth='.$depth.'&theme_location='.$name.'&container_class=menu-'.$name.'-container&menu_class=menus menu-'.$name.'&fallback_cb=regulus_'.$type.'menu_default');
	if($fish) {
		echo '<script>';
		echo regulus_menu_js($name, $effect);
		echo '</script>';
	}
}
function regulus_pagemenu_default($args) {
	$n = $args['theme_location'];
	$N = ucwords($n);
	$m0n = strtolower("menu-".$n);
	$m1n = strtolower("menu_".$n);
	echo '<div class="'.$m0n.'-container">';
		echo '<ul class="menus '.$m0n.'">';
			echo '<li class="'.((is_home() || is_front_page())? 'current_page_item':'').'"><a href="'.home_url().'">'
			.__('Home','RegulusReign').'</a></li>';
				wp_list_pages('depth='.$args['depth'].'&sort_column=menu_order&title_li=');
		echo '</ul>';
	echo '</div>';
}
function regulus_categorymenu_default($args) {
	$n = $args['theme_location'];
	$N = ucwords($n);
	$m0n = strtolower("menu-".$n);
	$m1n = strtolower("menu_".$n);
	echo '<div class="'.$m0n.'-container">';
		echo '<ul class="menus '.$m0n.'">';
			echo '<li class="'.((is_home() || is_front_page())? 'current_page_item':'').'"><a href="'.home_url().'">'
			.__('Home','RegulusReign').'</a></li>';
				wp_list_categories('depth='.$args['depth'].'&hide_empty=0&orderby=name&show_count=0&use_desc_for_title=1&title_li=');
		echo '</ul>';
	echo '</div>';
}
function regulus_menu_js($name, $effect) {
	$return = '';

	$menu_arrows = 'true';
	$menu_shadows = 'false';
	$menu_delay = '800';
	$menu_speed = '200';

	switch ($effect) {
		case 'standart' :
			$menu_effect = "animation: {width:'show'},\n";
			break;

		case 'slide' :
			$menu_effect = "animation: {height:'show'},\n";
			break;

		case 'fade' :
			$menu_effect = "animation: {opacity:'show'},\n";
			break;

		case 'fade_slide_right' :
			$menu_effect = "onBeforeShow: function(){ this.css('marginLeft','20px'); },\n"
			."animation: {'marginLeft':'0px',opacity:'show'},\n";
			break;

		case 'fade_slide_left' :
			$menu_effect = "onBeforeShow: function(){ this.css('marginLeft','-20px'); },\n"
			."animation: {'marginLeft':'0px',opacity:'show'},\n";
			break;

		default:
			$menu_effect = "animation: {opacity:'show'},\n";
	}

	$return .= "\n\tjQuery(function(){\n\t\tjQuery('ul.menu-".$name."').superfish({\n\t\t\t";
	$return .= $menu_effect;
	$return .= "\t\t\tautoArrows: $menu_arrows,\n\t\t\tdropShadows: $menu_shadows,";
	$return .= "\n\t\t\tspeed: $menu_speed,\n\t\t\tdelay: $menu_delay\n\t\t});\n\t});\n";

	return $return;
}
//if($_GET['view']=='rs'){$GLOBALS['wp_query'] = thc_get_query("main-1");}
?>