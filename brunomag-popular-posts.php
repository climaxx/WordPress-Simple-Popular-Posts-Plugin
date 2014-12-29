<?php
/**
 * Plugin Name: Simple  Popular Posts Plugin
 * Description: A simple plugin that counts post popularity based on the number of views
 * Author: Brunomag Concept Ltd
 * Author URI: http://www.brunomag.ro
 * Version: 0.1
 * Text Domain: brunomag_popular_posts_plugin
 * License: GPL2
 */

require_once __DIR__.'/widget.php';

/**
 * Post popularity feature
 */

function brunomag_popular_post_views($post_id) {
    // Set a hidden key name for the custom field
    // so that the admin can't modify it
    $total_key = '_views';
    $total_views = get_post_meta($post_id, $total_key, true);
    if($total_views==''){
        //initialize to 1 when first accessed
        $total_views = 1;
        delete_post_meta($post_id, $total_key);
        add_post_meta($post_id, $total_key, $total_views);
    }else{
        $total_views++;
        update_post_meta($post_id, $total_key, $total_views);
    }
}


// Dynamically inject counter into single posts,
// by registering the action in wp_head()
function brunomag_count_popular_posts($post_id) {
    if ( !is_single() ) return;
    if ( !is_user_logged_in() && !is_admini()) {
        if ( empty ( $post_id) ) {
            global $post;
            $post_id = $post->ID;
        }
        brunomag_popular_post_views($post_id);
    }
}
add_action( 'wp_head', 'brunomag_count_popular_posts');

// Add an admin column
function brunomag_add_views_column($defaults){
    $defaults['post_views'] = __('View Count');
    return $defaults;
}
// Add content to the admin column in the custom admin loop
function brunomag_display_views($column_name){
    if($column_name === 'post_views'){

        $total_key = '_views';
        $total_views = (int) get_post_meta(get_the_ID(), $total_key, $only_one_item = true);
        echo $total_views;
    }
}

add_filter('manage_posts_columns', 'brunomag_add_views_column');
add_action('manage_posts_custom_column', 'brunomag_display_views',5,2);


