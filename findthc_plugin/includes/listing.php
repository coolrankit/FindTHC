<?php
add_action('init', 'register_listing_type');
function register_listing_type() {
	$labels = array(
		'name'               => _x( 'Items', 'post type general name', 'thcPlugin' ),
		'singular_name'      => _x( 'Item', 'post type singular name', 'thcPlugin' ),
		'menu_name'          => _x( 'Items', 'admin menu', 'thcPlugin' ),
		'name_admin_bar'     => _x( 'Item', 'add new on admin bar', 'thcPlugin' ),
		'add_new'            => _x( 'Add New', 'item', 'thcPlugin' ),
		'add_new_item'       => __( 'Add New Item', 'thcPlugin' ),
		'new_item'           => __( 'New Item', 'thcPlugin' ),
		'edit_item'          => __( 'Edit Item', 'thcPlugin' ),
		'view_item'          => __( 'View Item', 'thcPlugin' ),
		'all_items'          => __( 'All Items', 'thcPlugin' ),
		'search_items'       => __( 'Search Menu Items', 'thcPlugin' ),
		'parent_item_colon'  => __( 'Parent Menu Items:', 'thcPlugin' ),
		'not_found'          => __( 'No items found.', 'thcPlugin' ),
		'not_found_in_trash' => __( 'No items found in Trash.', 'thcPlugin' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'thcPlugin' ),
		'public'             => false,
		'show_ui'            => false,
		'show_in_menu'       => false, //'edit.php?post_type=dispensary',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'listing' ),
		'capability_type'    => 'listing',
		//'capabilities'       => array('create_posts' => true), // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
		'map_meta_cap'       => true,
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'thumbnail')
	);

	register_post_type( 'listing', $args );
}

add_action( 'delete_post', 'listing_delete' );
function listing_delete($pid){
	$post = get_post($pid);
	if ( $post->post_type != 'listing' ) return;
	delete_thct_price($pid);
	delete_thct_rating($pid);
	delete_thct_test($pid);
}
?>